<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\UserProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;

class UserController extends AbstractController
{
    use ResetPasswordControllerTrait;
    private $mainCompany;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        Security $security
    ) {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $repository): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $repository->findAllByCompany($this->mainCompany),
        ]);
    }

    #[Route('/user/new', name: 'app_user_new')]
    public function add(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer,
        TranslatorInterface $translator
    ): Response {
        $form = $this->createForm(UserType::class, new User());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userEntity = $form->getData();
            $rolesForm = $form->get('rols')->getData();
            $roles = explode(",", $rolesForm);
            $userRepository->add($userEntity, $this->mainCompany, $roles);

            $this->sendEmail($userEntity, $mailer, $translator);

            $flashMessage = $translator->trans('User create flash', ['code' => $userEntity->getName()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{userEntity}/edit', name: 'app_user_edit')]
    public function edit(
        User $userEntity,
        Request $request,
        UserRepository $userRepository,
        TranslatorInterface $translator,
    ): Response {
        $roles = implode(",", $userEntity->getRoles());
        $form = $this->createForm(UserType::class, $userEntity, array("edit" => true, "roles" => $roles));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEntity = $form->getData();
            $rolesForm = $form->get('rols')->getData();
            $roles = explode(",", $rolesForm);
            $userRepository->add($userEntity, $userEntity->getMainCompany(), $roles, true);

            $flashMessage = $translator->trans('User edit flash', ['code' => $userEntity->getName()]);
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_user');
        }

        $userEntity = $form->getData();
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/{userEntity}/delete', name: 'app_user_delete')]
    public function delete(
        User $userEntity,
        UserRepository $repository,
        TranslatorInterface $translator
    ): Response {
        $flashMessage = $translator->trans('User delete flash', ['code' => $userEntity->getName()]);
        $this->addFlash('success', $flashMessage);

        $repository->delete($userEntity, true);
        return $this->redirectToRoute('app_user');
    }

    public function sendEmail($userEntity, MailerInterface $mailer, TranslatorInterface $translator)
    {
        try {
            $passToken = $this->resetPasswordHelper->generateResetToken($userEntity);
            $email = (new TemplatedEmail())
                ->from(new Address('test@localhost.com', 'Route'))
                ->to($userEntity->getEmail())
                ->subject('Your password reset request')
                ->htmlTemplate('user/password_email.html.twig')
                ->context([
                    'resetToken' => $passToken,
                ]);

            $mailer->send($email);
        } catch (ResetPasswordExceptionInterface $e) {
            $flashMessage = $translator->trans('User create error flash');
            $this->addFlash('error', $flashMessage);
            return $this->redirectToRoute('app_user');
        }
    }
}
