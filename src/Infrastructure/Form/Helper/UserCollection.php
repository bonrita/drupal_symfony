<?php

namespace App\Infrastructure\Form\Helper;


use Doctrine\Common\Collections\ArrayCollection;

class UserCollection
{

    protected $users;

    // Extra form fields.
    public $name;
    public $status;
    public $role;
    public $permission;
    public $action;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers(): ArrayCollection
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     * @return UserCollection
     */
    public function setUsers(ArrayCollection $users): UserCollection
    {
        $this->users = $users;

        return $this;
    }

}