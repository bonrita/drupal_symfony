<?php

namespace App\Infrastructure\Security\Authentication\Provider;


use App\Infrastructure\Security\Exception\PasswordResetException;
use Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


/**
 * Class PasswordResetAuthenticationProvider
 * @package App\Infrastructure\Security\Authentication\Provider
 */
class PasswordResetProvider extends UserAuthenticationProvider
{

    private $encoderFactory;
    private $userProvider;

    public function __construct(
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        string $providerKey,
        EncoderFactoryInterface $encoderFactory,
        bool $hideUserNotFoundExceptions = true
    ) {
        parent::__construct($userChecker, $providerKey, $hideUserNotFoundExceptions);

        $this->encoderFactory = $encoderFactory;
        $this->userProvider = $userProvider;
    }

    /**
     * @inheritDoc
     */
    protected function retrieveUser($username, UsernamePasswordToken $token)
    {
        $user = $token->getUser();
        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(TokenInterface $token)
    {
        try {
            $authenticatedToken = parent::authenticate($token);
        } catch (PasswordResetException $exception) {
            $e = new CredentialsExpiredException($exception->getMessageKey());
            $e->setToken($token);
            throw $e;
        }

        return $authenticatedToken;
    }


    /**
     * @inheritDoc
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        $currentUser = $token->getUser();
        if ($currentUser instanceof UserInterface && $currentUser->getPassword() !== $user->getPassword()) {
            throw new BadCredentialsException('The credentials were changed from another session.');
        }
    }

    /**
     * This function is specific to PasswordResetProvider.
     *
     * For more information specific to the logic here, see
     * https://github.com/symfony/symfony-docs/pull/3134#issuecomment-27699129
     * https://symfony.com/doc/current/security/custom_authentication_provider.html#the-authentication-provider
     */
    protected function validateDigest($digest, $nonce, $created, $secret): bool
    {

        return false;
    }

}
