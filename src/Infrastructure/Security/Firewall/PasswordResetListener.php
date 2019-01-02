<?php

namespace App\Infrastructure\Security\Firewall;


use App\Domain\Entity\User;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class PasswordResetListener implements ListenerInterface
{

    private $tokenStorage;
    private $authenticationManager;
    private $providerKey;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(TokenStorageInterface $tokenStorage, string $providerKey, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->providerKey = $providerKey;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $user = $this->doctrine->getRepository(User::class)->find(
            $request->attributes->get('user')
        );

        try {
            $upt = new UsernamePasswordToken($user->getUsername(), $user->getPassword(), $this->providerKey, $user->getRoles());
            $upt->setUser($user);
            $token = $this->authenticationManager->authenticate($upt);

            $token->setAttributes($token->getAttributes() + ['reset_password_verified' => true]);

            $this->tokenStorage->setToken($token);
        } catch (AuthenticationException $e) {
            $e->getToken()->setAttribute('exception', $e);
            $this->tokenStorage->setToken($e->getToken());
        }
    }

    public function setDoctrine(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

}