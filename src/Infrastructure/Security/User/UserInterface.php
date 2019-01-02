<?php

namespace App\Infrastructure\Security\User;


use App\Domain\Entity\Role;

interface UserInterface
{
    public function isActive():bool;

    /**
     * Returns the UNIX timestamp when the user last logged in.
     *
     * @return int
     *   Timestamp of the last login time.
     */
    public function getLastLoginTime(): int;

    /**
     * Add role.
     *
     * @param Role $role
     * @return void
     */
    public function addRole(Role $role): void;
}