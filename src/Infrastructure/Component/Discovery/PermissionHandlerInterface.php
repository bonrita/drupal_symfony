<?php

namespace App\Infrastructure\Component\Discovery;

/**
 * Defines an interface to list available permissions.
 */
interface PermissionHandlerInterface
{
    public const TYPE = 'permissions';

    /**
     * Gets all available permissions.
     *
     * @return array
     *   An array whose keys are permission names and whose corresponding values
     *   are arrays containing the following key-value pairs:
     *   - title: The human-readable name of the permission, to be shown on the
     *     permission administration page. This should be wrapped in the t()
     *     function so it can be translated.
     *   - description: (optional) A description of what the permission does. This
     *     should be wrapped in the t() function so it can be translated.
     *   - restrict access: (optional) A boolean which can be set to TRUE to
     *     indicate that site administrators should restrict access to this
     *     permission to trusted users. This should be used for permissions that
     *     have inherent security risks across a variety of potential use cases
     *     (for example, the "administer filters" and "bypass node access"
     *     permissions provided by Drupal core). When set to TRUE, a standard
     *     warning message defined in user_admin_permissions() will be displayed
     *     with the permission on the permission administration page. Defaults
     *     to FALSE.
     */
    public function getPermissions();

}