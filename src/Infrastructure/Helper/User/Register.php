<?php

namespace App\Infrastructure\Helper\User;


use App\Domain\Entity\User;
use App\Infrastructure\Helper\EmailInterface;
use App\Infrastructure\Helper\EmailMessageInterface;
use App\Services\Form\PasswordVisibility;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

class Register implements UserRegisterInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * Register constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EmailInterface $mailer
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EmailInterface $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    /**
     * @inheritdoc
     */
    public function getPassword(User $user, PasswordVisibility $passwordVisibility): string
    {
        if ($passwordVisibility->isShowPassword()) {
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        } else {
            $password = $this->passwordEncoder->encodePassword($user, md5(random_bytes(10)));
        }
        return $password;
    }

    /**
     * @inheritDoc
     */
    public function persistUser(User $user, ObjectManager $objectManager): void
    {
        $objectManager->persist($user);
    }

    /**
     * @inheritDoc
     */
    public function sendMail(EmailMessageInterface $message): void
    {
        $this->mailer->sendMail($message);
    }


}