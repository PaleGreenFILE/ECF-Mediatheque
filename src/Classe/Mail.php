<?php

namespace App\Classe;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Mail
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($emailTo, $name, $sujet, $content)
    {
        $email = (new Email())
            ->from(new Address('hello@parlonscode.com', 'Studi #ECF 12/2021'))
            ->to(new Address($emailTo, $name))
            ->subject($sujet)
            ->text($content)
            ->html($content);

        $this->mailer->send($email);
    }

}
