<?php

namespace App\Infrastructure\Helper;


use Twig\Environment;

class Email implements EmailInterface
{

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * Email constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @inheritdoc
     */
    public function sendMail(EmailMessageInterface $message): void
    {
        $this->mailer->send($this->generateMessage($message));
    }

    /**
     * @param EmailMessageInterface $message
     * @return \Swift_Mime_SimpleMessage
     */
    protected function generateMessage(EmailMessageInterface $message): \Swift_Mime_SimpleMessage
    {
        $from = $message->getUser()->getEmail()?:ini_get('sendmail_from');
        return (new \Swift_Message($message->getSubject(),
          $message->getBody()->getContent(),
          $message->getContentType()))
            ->setFrom($from)
            ->setTo($message->getUser()->getEmail());
    }

}