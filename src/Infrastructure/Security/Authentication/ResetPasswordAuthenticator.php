<?php

namespace App\Infrastructure\Security\Authentication;


use App\Domain\Entity\Role;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Translation\TranslatorInterface;

class ResetPasswordAuthenticator extends AbstractGuardAuthenticator implements ServiceSubscriberInterface
{

    private $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $this->locator->get('session')->getFlashBag()->add(
            'warning',
            $this->locator->get('translator')->trans('messages.user.password.reset.here')
        );

        return new RedirectResponse('/user/password');
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        $token = $this->locator->get('security.token_storage')->getToken();

        if (!$token) {
            return false;
        }

        $roles = array_map(
            function (Role $role) {
                return $role->getRole();
            },
            $token->getRoles()
        );

//        $token->setAuthenticated(false);
//        return false;
        if (array_key_exists('password_reset', $token->getAttributes()) && \in_array(
                Role::ROLE_AUTHENTICATED,
                $roles,
                true
            )) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $user = $this->locator->get('security.token_storage')->getToken()->getUser();

        return [
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->locator->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $gg = 0;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $gg = 0;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $gg = 0;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        $gg = 0;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            'security.authorization_checker' => AuthorizationCheckerInterface::class,
            'security.token_storage' => TokenStorageInterface::class,
            'session' => SessionInterface::class,
            'translator' => TranslatorInterface::class,
        ];
    }

}