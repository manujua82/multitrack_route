<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\MainCompany;
use App\Entity\Correlatives;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->registerUser($entityManager, $userPasswordHasher, $user, $form);
            $entityManager->flush();

            try {
                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('accounts@routepremium.com', 'Route Premium Team'))
                        ->to($user->getEmail())
                        ->subject($translator->trans('Please Confirm your Email'))
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
            } catch (\Exception $e) {
            }

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash($translator->trans('success'), $translator->trans('Your email address has been verified.'));

        return $this->redirectToRoute('app_login');
    }

    private function registerUser($entityManager, $userPasswordHasher, $user, $form)
    {
        $company = $this->registerCompany($entityManager, $form->get('email')->getData());
        $this->registerCorrelatives($entityManager, $company);

        $user->setMainCompany($company);
        $user->setActive(true);
        $user->setDeleted(false);
        $user->setRoleGroup('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        if (true === $form['agreeTerms']->getData()) {
            $user->setAgreedTerms();
        }

        $entityManager->persist($user);

        return $user;
    }

    private function registerCompany($entityManager, $email)
    {
        $company = new MainCompany();
        $company->setName($email);
        $entityManager->persist($company);
        return $company;
    }

    private function registerCorrelatives($entityManager, $company)
    {
        $route = new Correlatives();
        $route->setCorrelative($company, 'ROUTE', 'RT');
        $entityManager->persist($route);

        $order = new Correlatives();
        $order->setCorrelative($company, 'ORDER', 'ORD');
        $entityManager->persist($order);
    }
}
