<?php

namespace App\Web\Controller;

use App\Domain\Entity\User;
use App\Infrastructure\Component\Discovery\PermissionHandler;
use App\Infrastructure\Component\Discovery\Yml;
use App\Infrastructure\Component\Time;
use App\Infrastructure\Component\Utility\UserPassInterface;
use App\Infrastructure\Form\PasswordVisibility;
use App\Infrastructure\Form\User\LoginFormType;
use App\Infrastructure\Form\User\PasswordFormType;
use App\Infrastructure\Form\User\PasswordResetFormType;
use App\Infrastructure\Form\User\RegisterType;
use App\Infrastructure\Helper\EmailMessageInterface;
use App\Infrastructure\Helper\Form\ValidatorInterface;
use App\Infrastructure\Helper\Role;
use App\Infrastructure\Helper\User\UserRegisterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route({"en": "/user", "nl": "/nl/gebruiker"},
 * name="user_page_",
 * requirements={ "_locale" = "%app.locales%" }
 * )
 */
class UserController extends AbstractController
{
    /**
     * @Route({ "en": "/", "nl": "/" },
     *     name="base",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function index(): Response
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            return $this->redirectToRoute('user_page_user_view', ['user' => $user->getId()]);
        } else {
            return $this->redirectToRoute('user_page_login');
        }

    }

    /**
     * @Route({ "en": "/register", "nl": "/inschrijven" },
     *     name="register",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function register(Request $request, PasswordVisibility $passwordVisibility): Response
    {
        $user = new User();
        $form = $this->createForm(
            RegisterType::class,
            $user,
            [
                'show_password' => $passwordVisibility->setShowPassword(false)->isShowPassword(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $passwordVisibility->preErrorChecking($form, 'data.plainPassword');

            if ($passwordVisibility->shouldPersistUser($form)) {

                $userRegister = $this->container->get(UserRegisterInterface::class);
                $message = $this->container->get(EmailMessageInterface::class);

                // Add role.
                $this->container->get(Role::class)->addRoles(['ROLE_AUTHENTICATED'], $user);

                $user->setPassword($userRegister->getPassword($user, $passwordVisibility));
                $userRegister->persistUser($user, $this->getDoctrine()->getManager());
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    $this->container->get('translator')->trans(
                        'messages.user.email.sent',
                        ['%email%' => $user->getEmail()]
                    )
                );

                $message->setUser($user)->setSubject('You are welcome to join us.')->setBody(
                    $this->render(
                        'emails/registration.html.twig',
                        [
                            'name' => $user->getUsername(),
                            'hash_params' => $this->container->get(UserPassInterface::class)->getResetParams($user),
                        ]
                    )
                );
                $userRegister->sendMail($message);

                return $this->redirectToRoute('home_page');
            }
        }

        return $this->render(
            'pages/user/register.html.twig',
            [
                'form' => $form->createView(),
                'show_password' => $passwordVisibility->isShowPassword(),
            ]
        );
    }

    /**
     * @Route({ "en": "/{user}", "nl": "/{user}" },
     *     name="user_view",
     *     requirements={
     *     "_locale" = "%app.locales%",
     *     "user"="\d+",
     *      })
     *
     */
    public function userView(Request $request, User $user): Response
    {

        $perms = $this->container->get(PermissionHandler::class)->getPermissions();

        return $this->render(
            'pages/user/view-profile.html.twig',
            [
                'username' => $user->getUsername(),
                'user' => $user->getId(),
            ]
        );
    }

    /**
     * @Route({ "en": "/{user}/edit", "nl": "/{user}/edit" },
     *     name="user_edit",
     *     requirements={
     *     "_locale" = "%app.locales%",
     *     "user"="\d+",
     *      })
     */
    public function userEdit(Request $request, User $user): Response
    {
        $form = $this->createForm(
            RegisterType::class,
            $user
        );

        $form->handleRequest($request);

        return $this->render(
            'pages/user/register.html.twig',
            [
                'form' => $form->createView(),
                'show_password' => true,
            ]
        );
    }

    /**
     * @Route({ "en": "/reset/{user}/{timestamp}/{hash}/login", "nl": "/reset/{user}/{timestamp}/{hash}/login" },
     *     name="reset_login",
     *     requirements={
     *     "_locale" = "%app.locales%",
     *     "user"="\d+",
     *     "timestamp"="\d+"
     *     })
     *
     * @param Request $request
     * @param User $user
     * @param int $timestamp
     * @param string $hash
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPassLogin(Request $request, User $user, int $timestamp, string $hash)
    {
        /** @var TokenStorage $tokenStorage */
        $tokenStorage = $this->container->get('security.token_storage');
        $token = $tokenStorage->getToken();

        if ($token && $token->hasAttribute('exception')) {
            /** @var RuntimeException $exception */
            $exception = $token->getAttribute('exception');

            $this->addFlash(
                'danger',
                $this->container->get('translator')->trans($exception->getMessage())
            );
            $tokenStorage->setToken(null);

            return $this->redirectToRoute('user_page_pass');
        }

        $this->addFlash(
            'success',
            $this->container->get('translator')->trans('messages.user.just_used_one_time_login_link')
        );

        $attributes = $token->getAttributes() + ['password_reset' => true];

        $token->setAttributes($attributes);


        return $this->redirectToRoute('user_page_reset_pass');
    }

    /**
     * @Route({ "en": "/reset/password", "nl": "/reset/password" },
     *     name="reset_pass",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function resetPassword(Request $request, PasswordVisibility $passwordVisibility)
    {
        $form = $this->createForm(PasswordFormType::class, []);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRegister = $this->container->get(UserRegisterInterface::class);
            /** @var User $user */
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $user->setPlainPassword($form->getData()['plainPassword']);
            $passwordVisibility->setShowPassword(true);

            $time = $this->container->get(Time::class);
            $user->setLogin($time->getRequestTime());

            $user->setPassword($userRegister->getPassword($user, $passwordVisibility));
            $userRegister->persistUser($user, $this->getDoctrine()->getManager());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $this->container->get('translator')->trans('forms.password.reset.success.message', [], 'forms')
            );

            // Delete the token for resenting the password as it is no longer needed after a successful password reset.
            $this->container->get('security.token_storage')->setToken(null);

            // @todo redirect to login page.
            return $this->redirectToRoute('home_page');
        }

        return $this->render(
            'pages/user/password.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route({ "en": "/password", "nl": "/password" },
     *     name="pass",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function password(Request $request, ValidatorInterface $validator)
    {
        $form = $this->createForm(PasswordResetFormType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $validator->setFormBuilder($form)->usernameOrEmail($form->getData()['username']);

            if ($form->isValid()) {
                $this->addFlash(
                    'success',
                    $this->container->get('translator')->trans('messages.user.password.email.sent')
                );

                $userRegister = $this->container->get(UserRegisterInterface::class);

                // Send mail.
                $message = $this->container->get(EmailMessageInterface::class);

                $message->setUser($user)->setSubject('One time login link.')->setBody(
                    $this->render(
                        'emails/one-time-login-link.html.twig',
                        [
                            'name' => $user->getUsername(),
                            'hash_params' => $this->container->get(UserPassInterface::class)->getResetParams($user),
                        ]
                    )
                );

                // Reset login.
                $user->setLogin(0);
                $userRegister->persistUser($user, $this->getDoctrine()->getManager());
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('home_page');
            }
        }

        return $this->render(
            'pages/user/forgot-credentials.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route({ "en": "/login", "nl": "/login" },
     *     name="login",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($user instanceof UserInterface) {

// @todo redirect to the user edit form of the current user.
            return $this->redirectToRoute('home_page');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(
            LoginFormType::class,
            [
                'name' => $lastUsername,
            ]
        );

        return $this->render(
            'pages/user/login.html.twig',
            [
                'form' => $form->createView(),
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }


    public static function getSubscribedServices()
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                'translator' => TranslatorInterface::class,
                UserPassInterface::class => UserPassInterface::class,
                UserRegisterInterface::class => UserRegisterInterface::class,
                EmailMessageInterface::class => EmailMessageInterface::class,
                Role::class => Role::class,
                Time::class => Time::class,
                PermissionHandler::class => PermissionHandler::class,
            ]
        );
    }

}