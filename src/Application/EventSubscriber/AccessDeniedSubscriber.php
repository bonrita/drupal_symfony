<?php

namespace App\Application\EventSubscriber;


use App\Domain\Entity\Role;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class AccessDeniedSubscriber.
 *
 * @package App\Application\EventSubscriber
 */
class AccessDeniedSubscriber implements EventSubscriberInterface
{

    /**
     * The token storage.
     *
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * AccessDeniedSubscriber constructor.
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     *   The token storage.
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Redirects users when access is denied.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     *   The event to process.
     */
    public function onException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof AccessDeniedHttpException) {
            $routeName = $event->getRequest()->attributes->get('_route');
            $token     = $this->tokenStorage->getToken();

            $roles = array_map(
                function ($role) {
                    return $role->getRole();
                },
                $token->getRoles()
            );

            if (in_array(Role::ROLE_AUTHENTICATED, $roles)) {
                switch ($routeName) {
                    case 'user_page_login';
                        $url = $this->getUserViewUrl($token);
                        $event->setResponse(new RedirectResponse($url));
                        break;
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        $events[KernelEvents::EXCEPTION][] = ['onException'];

        return $events;
    }

    /**
     * Get the user view url.
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface|null $token
     *   The token instance.
     *
     * @return string
     *   The url.
     */
    protected function getUserViewUrl(TokenInterface $token): string
    {
        $user = $token->getUser();

        // Init route with dynamic placeholders
        $route = new Route(
            '/user/{user}',
            ['_controller' => 'UserController'],
            ['user' => '\d+', '_locale' => '%app.locales%']
        );

        // Add Route object(s) to RouteCollection object
        $routes = new RouteCollection();
        $routes->add('user_page_user_view', $route);

        $context = new RequestContext('');

        // Redirect an authenticated user to the profile page.
        $urlGenerator = new UrlGenerator($routes, $context);
        $url          = $urlGenerator->generate(
            'user_page_user_view',
            ['user' => $user->getId()]
        );

        return $url;
    }

}
