<?php

namespace App\Infrastructure\Helper;


interface EmailInterface
{
    /**
     * @param EmailMessageInterface $message
     */
    public function sendMail(EmailMessageInterface $message): void;
}