<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class Mailer
{
    private $mailer;
    private $translator;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        MailerInterface $mailer, 
        TranslatorInterface $translator
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function sendNewUserMessage(User $userEntity): TemplatedEmail {
        try {
            $passToken = $this->resetPasswordHelper->generateResetToken($userEntity);
            $email = (new TemplatedEmail())
                ->from(new Address('test@localhost.com', 'Route'))
                ->to($userEntity->getEmail())
                ->subject($this->translator->trans('User create title'))
                ->htmlTemplate('user/password_email.html.twig')
                ->context([
                    'resetToken' => $passToken,
                ]);

            $this->mailer->send($email);
            return $email;
        } catch (ResetPasswordExceptionInterface $e) {
            //throw error
        }
    }
}