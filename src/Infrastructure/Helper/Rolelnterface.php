<?php

namespace App\Infrastructure\Helper;


use App\Domain\Entity\User;

interface Rolelnterface
{
    public function addRoles(array $roles, User $user): void;
}