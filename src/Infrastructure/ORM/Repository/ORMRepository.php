<?php

namespace App\Infrastructure\ORM\Repository;


use Doctrine\ORM\EntityRepository;

class ORMRepository extends EntityRepository
{

    public function flush()
    {
        $identityMap = $this->_em->getUnitOfWork()->getIdentityMap();

        if (isset($identityMap[$this->getClassName()])) {
            $this->_em->flush($identityMap[$this->getClassName()]);
        }
    }

}