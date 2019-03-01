<?php

namespace App\Infrastructure\Helper;


use App\Domain\Entity\User;
use App\Domain\Entity\Role as EntityRole;
use Doctrine\Common\Persistence\ObjectManager;

class Role implements Rolelnterface
{
    /** @var ObjectManager  */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function addRoles(array $roles, User $user): void
    {
        foreach ($roles as $roleName) {
            $role = $this->objectManager->getRepository(EntityRole::class)->findOneBy(['name' => $roleName]);
            if (!$role) {
                $role = new EntityRole($roleName);
                $role->setTitle(ucfirst(strtolower(str_replace('ROLE_', '', $roleName))));
                $this->objectManager->persist($role);
            }

            $user->addRole($role);
        }
    }

}
