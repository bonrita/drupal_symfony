<?php

namespace App\Infrastructure\Component\Utility;


use App\Domain\Entity\User;

interface UserPassInterface
{
    public function getResetParams(User $user): array;

    public function getHash(User $user, int $timestamp): string;
}