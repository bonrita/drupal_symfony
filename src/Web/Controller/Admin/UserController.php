<?php

namespace App\Web\Controller\Admin;


use App\Application\Overridden\Bundle\Dtc\CustomGridSourceManager;
use App\Domain\Entity\Role;
use App\Domain\Pagination\User as AppPagination;
use App\Infrastructure\Component\Discovery\PermissionHandler;
use App\Infrastructure\Form\Admin\User\AddRoleFormType;
use App\Infrastructure\Form\Admin\User\PermissionsCollectionFormType;
use App\Infrastructure\Form\Admin\User\TableCollectionType;
use App\Infrastructure\Form\Admin\User\TableFormType;
use App\Infrastructure\Form\Admin\User\UserCollectionFormType;
use App\Infrastructure\Form\Helper\PermissionCollection;
use App\Infrastructure\Form\Helper\UserCollection;
use App\Infrastructure\Manager\Plugin\HandlerCollection;
use App\Plugin\ActionInterface;
use App\Repository\UserRepository;
use Dtc\GridBundle\Grid\Renderer\RendererFactory;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

//use Dtc\GridBundle\Manager\GridSourceManager;

/**
 * @Route({"en": "/admin", "nl": "/nl/admin"},
 * name="user_admin_",
 * requirements={ "_locale" = "%app.locales%" }
 * )
 */
class UserController extends AbstractController
{

    /**
     * @Route({ "en": "/people/permissions", "nl": "/people/permissions" },
     *     name="permissions",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function permissions(Request $request)
    {
        $form = $this->createForm(PermissionsCollectionFormType::class, $this->getPermissions());

        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {

            $rawPerms = $request->request->get('permissions_collection_form')['permissions'];
            $roles = [];
            foreach ($rawPerms as $data) {
                foreach ($data as $roleId => $permission) {
                    $roles[$roleId][] = $permission;
                }
            }

            if (!empty($roles)) {
                foreach ($roles as $roleId => $permissions) {
                    $role = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(
                        [
                            'name' => $roleId,
                        ]
                    );
                    $role->setPermissions($permissions);
                    $this->getDoctrine()->getManager()->persist($role);
                }

                $this->getDoctrine()->getManager()->flush();
            }

            $this->addFlash(
                'success',
                'The permissions have been saved.'
            );

            // Clearing post data that is still in the browser cache.
            return $this->redirect($request->getUri());
        }

        return $this->render(
            'pages/admin/user/permissions.html.twig',
            [
                'form' => $form->createView(),
                'roles' => $this->getDoctrine()->getRepository(Role::class)->findAll(),
            ]
        );

    }

    /**
     * @Route({ "en": "/people", "nl": "/people" },
     *     name="people",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function people(Request $request)
    {

        $userCollection = new UserCollection();

        /** @var SlidingPagination $pagination */
        $pagination = $this->container->get(AppPagination::class)->getAll();


        foreach ($pagination->getItems() as $user) {
            $userWrapper = new \App\Infrastructure\Form\Helper\User($user, $this->container);
            $userCollection->getUsers()->add($userWrapper);
        }

        $form = $this->createForm(UserCollectionFormType::class, $userCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserCollection $data */
            $data = $form->getData();
            $button = $form->getClickedButton()->getName();

            switch ($button) {
                case 'filter':
                    $pagination = $this->container->get(AppPagination::class)->filter($form);
                    break;
                case 'execute_action':
                    $pagination = $this->container->get(AppPagination::class)->filter($form);

                    break;
                case 'reset':
                    return $this->redirectToRoute('user_admin_people');
                    break;
            }

            $userCollection = new UserCollection();
            foreach ($pagination->getItems() as $user) {
                $userWrapper = new \App\Infrastructure\Form\Helper\User($user, $this->container);
                $userCollection->getUsers()->add($userWrapper);
            }

            $form = $this->createForm(UserCollectionFormType::class, $userCollection);
        }

        return $this->render(
            'pages/admin/user/people-list.html.twig',
            [
                'form' => $form->createView(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route({ "en": "/people/roles", "nl": "/people/roles" },
     *     name="roles_list",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function roles(Request $request)
    {
        $renderer = $this->container->get('dtc_grid.renderer.factory')->create(
            'table'
        ); // or whichever renderer you want to use

        $gridSource = $this->get('overridden.dtc_grid.manager.source')->get('App:Role');
        $renderer->bind($gridSource);
        $renderer->getParams($params);

        return $this->render('pages/admin/user/roles-list.html.twig', $params);
    }

    /**
     * @Route({ "en": "/people/roles/add", "nl": "/people/roles/add" },
     *     name="roles_add",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function rolesAdd(Request $request)
    {

        $role = new Role('');
        $form = $this->createForm(AddRoleFormType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($role);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'The role has been saved.'
            );
        }

        return $this->render(
            'pages/admin/user/roles-add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route({ "en": "/people/roles/manage/{role}", "nl": "/people/roles/manage/{role}" },
     *     name="roles_edit",
     *     requirements={
     *     "_locale" = "%app.locales%",
     *     "role"="\d+",
     * })
     */
    public function rolesEdit(Request $request, Role $role)
    {
        $form = $this->createForm(AddRoleFormType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('doctrine')->getManager()->persist($role);
            $this->container->get('doctrine')->getManager()->flush();

            $this->addFlash(
                'success',
                'The role has been updated.'
            );

            return $this->redirectToRoute('user_admin_roles_list');
        }

        return $this->render(
            'pages/admin/user/roles-add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @return PermissionCollection
     */
    protected function getPermissions(): PermissionCollection
    {
        $perms = $this->container->get(PermissionHandler::class)->getPermissionInstances();

        $permissions = new PermissionCollection();

        foreach ($perms as $domain => $data) {
            foreach ($data['permissions'] as $permission) {
                $permissions->getPermissions()->add($permission);
            }
        }

        return $permissions;
    }

    public static function getSubscribedServices()
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                PermissionHandler::class => PermissionHandler::class,
                'dtc_grid.renderer.factory' => RendererFactory::class,
                'overridden.dtc_grid.manager.source' => CustomGridSourceManager::class,
                'kernel' => KernelInterface::class,
                AppPagination::class => AppPagination::class,
            ]
        );
    }
}