<?php

namespace App\Infrastructure\Helper;


use App\Domain\Entity\User;

interface EmailMessageInterface
{
    public function getUser(): User;

    public function setUser(User $user): EmailMessageInterface;

    public function getFrom(): string;

    public function setFrom($from): EmailMessageInterface;

    public function getBody();

    public function setBody($body);

    public function setContentType($contentType): EmailMessageInterface;

    public function getContentType(): string;

    public function getSubject(): string;

    public function setSubject($subject): EmailMessageInterface;

}