<?php

namespace App\Infrastructure\Security\Exception;


use Symfony\Component\Security\Core\Exception\RuntimeException;

class PasswordResetException extends RuntimeException
{

    /**
     * Message key to be used by the translation component.
     *
     * @return string
     */
    public function getMessageKey()
    {
        return $this->getMessage();
    }

}