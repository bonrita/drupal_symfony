<?php

namespace App\Infrastructure\Component\Discovery;


use App\Infrastructure\Component\Discovery\Files\YmlInterface;
use App\Infrastructure\Form\Helper\Permission;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Yaml\Yaml;


/**
 * Provides the available permissions based on yml files.
 *
 * To define permissions you can use a $domain.yml file. This file
 * defines machine names, human-readable names, restrict access (if required for
 * security warning), and optionally descriptions for each permission type. The
 * machine names are the canonical way to refer to permissions for access
 * checking.
 *
 * If you need to define dynamic permissions you can use the
 * permission_callbacks key to declare a callable that will return an array of
 * permissions, keyed by machine name. Each item in the array can contain the
 * same keys as an entry in $domain.yml.
 *
 * Here is an example from the core filter component.
 * @code
 * # The key is the permission machine name, and is required.
 * administer_filters:
 *   # (required) Human readable name of the permission used in the UI.
 *   title: 'Administer text formats and filters'
 *   # (optional) Additional description fo the permission used in the UI.
 *   description: 'Define how text is handled by combining filters into text formats.'
 *   # (optional) Boolean, when set to true a warning about site security will
 *   # be displayed on the Permissions page. Defaults to false.
 *   restrict access: false
 *
 * # An array of callables used to generate dynamic permissions.
 * permission_callbacks:
 *   # Each item in the array should return an associative array with one or
 *   # more permissions following the same keys as the permission defined above.
 *   - \Component\filter\FilterPermissions::permissions
 * @endcode
 *
 * @see Action.yml
 * @see \Component\filter\FilterPermissions
 */
class PermissionHandler implements ServiceSubscriberInterface, PermissionHandlerInterface
{

    /**
     * @var ContainerInterface
     */
    private $locator;


    /**
     * Permission constructor.
     * @param ContainerInterface $locator
     */
    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @inheritdoc
     */
    public function getPermissions()
    {
        $permissions = $this->buildPermissions();

        return $this->sortPermissions($permissions);
    }

    public function getPermissionInstances()
    {
        $permissions = $this->getPermissions();

        foreach ($permissions as $domain => $data) {
            $instances = [];
            foreach ($data['permissions'] as $id => $permission) {
                $perm = new Permission($id);
                $perm->setName($permission['title']);
                $perm->setDescription($permission['description'] ?? '');
                $perm->setDomain($data['info']['title']);
                $perm->setDomainDescription($data['info']['description'] ?? '');

                $instances[] = $perm;
            }

            $permissions[$domain]['permissions'] = $instances;
        }

        return $permissions;
    }

    /**
     * Builds all permissions provided by .permissions.yml files.
     *
     * @return array[]
     *   Each return permission is an array with the following keys:
     *   - title: The title of the permission.
     *   - description: The description of the permission, defaults to NULL.
     */
    protected function buildPermissions(): array
    {
        $permissions = [];
        $data = $this->locator->get(YmlInterface::class)->setFolder(self::TYPE)->findData();

        /** @var SplFileInfo $fileInfo */
        foreach ($data as $domain => $fileInfo) {
            $info = Yaml::parse($fileInfo->getContents());
            $permissions[$domain] = $this->translate($info);
        }

        return $permissions;
    }

    protected function translate(array $info): array
    {
        foreach ($info as $key => $item) {
            if (\is_array($item)) {
                $this->translate($item);
            } elseif (\in_array($key, ['title', 'description'])) {
                $info[$key] = $this->locator->get('translator')->trans($key);
            }
        }

        return $info;
    }

    /**
     * Sorts the given permissions by domain name and title.
     *
     * @param array $permissions
     *   The permissions to be sorted.
     *
     * @return array[]
     */
    protected function sortPermissions(array $permissions)
    {
        ksort($permissions);

        return $permissions;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices()
    {
        return [
            YmlInterface::class => YmlInterface::class,
            'translator' => TranslatorInterface::class,
        ];
    }

}
