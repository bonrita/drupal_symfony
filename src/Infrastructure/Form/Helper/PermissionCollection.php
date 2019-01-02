<?php

namespace App\Infrastructure\Form\Helper;


use Doctrine\Common\Collections\ArrayCollection;

class PermissionCollection
{

    protected $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getPermissions(): ArrayCollection
    {
        return $this->permissions;
    }

    /**
     * @param ArrayCollection $permissions
     * @return PermissionCollection
     */
    public function setPermissions(ArrayCollection $permissions): PermissionCollection
    {
        $this->permissions = $permissions;

        return $this;
    }


}