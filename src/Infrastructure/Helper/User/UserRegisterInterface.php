<?php

namespace App\Infrastructure\Helper\User;

use App\Domain\Entity\User;
use App\Infrastructure\Helper\EmailMessageInterface;
use App\Services\Form\PasswordVisibility;
use Doctrine\Common\Persistence\ObjectManager;
use Twig\Environment;

interface UserRegisterInterface
{

    /**
     * @param User $user
     * @param PasswordVisibility $passwordVisibility
     * @return string
     * @throws \Exception
     */
    public function getPassword(User $user, PasswordVisibility $passwordVisibility): string;

    /**
     * @param User $user
     * @param ObjectManager $objectManager
     */
    public function persistUser(User $user, ObjectManager $objectManager): void;

    /**
     * @param EmailMessageInterface $message
     */
    public function sendMail(EmailMessageInterface $message): void;

}