<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    /**
     * Send an email with a template and context data to the recipient
     * @param string $from
     * @param string $to
     *  @param string $subject
     * @param string $template
     * @param array $context
     *
     * @throws TransportExceptionInterface
     */
    public function send(string $from, string $to, string $subject, string $template, array $context): void
    {
        $email = (new TemplatedEmail())
                    ->from($from)
                    ->to($to)
                    ->subject($subject)
                    ->htmlTemplate("email/$template.html.twig")
                    ->context($context);
        $this->mailer->send($email);

    }

}