<?php

namespace App\Infrastructure\Security\User;


use App\Domain\Entity\User;
use App\Infrastructure\Security\Exception\PasswordResetException;
use App\Infrastructure\Framework\Security\User\UserInterface as AppUserInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUserInterface) {
            return;
        }

        if (!$user->isActive()) {
            $ex = new DisabledException('User account is disabled.');
            $ex->setUser($user);
            throw $ex;
        }

        $current = $this->requestStack->getCurrentRequest()->server->get('REQUEST_TIME');
        $timestamp = $this->requestStack->getCurrentRequest()->attributes->get('timestamp');
        $loginTime = $user->getLastLoginTime();

        if ($current - $timestamp > User::PASSWORD_RESET_TIMEOUT) {
            throw new PasswordResetException('messages.login.link.expired');
        }

        if ($loginTime > $timestamp) {
            throw new PasswordResetException('messages.login.link.already.used');
        }

    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
    }

}
