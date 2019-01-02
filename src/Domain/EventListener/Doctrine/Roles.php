<?php

namespace App\Domain\EventListener\Doctrine;


use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class Roles
{

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on the "User" entity
        if (!$entity instanceof User) {
            return;
        }
    }

}