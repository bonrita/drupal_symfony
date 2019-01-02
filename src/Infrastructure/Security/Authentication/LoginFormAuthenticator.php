<?php

namespace App\Infrastructure\Security\Authentication;


use App\Domain\Entity\User;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements ServiceSubscriberInterface
{
    private $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        $data = $request->request->get('login_form');

        return !empty($data) && \is_array($data) && array_key_exists('name', $data) && array_key_exists(
                'password',
                $data
            );
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $data = $request->request->get('login_form');

        return [
            'name' => $data['name'],
            'password' => $data['password'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->locator->get('doctrine')->getRepository(User::class)->loadUserByUsername($credentials['name']);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->locator->get('security.password_encoder')->isPasswordValid($user, $credentials['password']);
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
    protected function getLoginUrl()
    {
        return $this->locator->get('router')->generate('user_page_login');
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            'router' => RouterInterface::class,
            'doctrine' => ManagerRegistry::class
        ];
    }

}