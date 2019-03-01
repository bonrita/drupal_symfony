<?php
/**
 * Created by PhpStorm.
 * User: bona
 * Date: 21-2-19
 * Time: 6:37
 */

namespace App\Infrastructure\Security\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LoginVoter extends Voter
{
    private const LOGIN = 'login';

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        // TODO: Implement supports() method.
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(
      $attribute,
      $subject,
      TokenInterface $token
    ) {
        // TODO: Implement voteOnAttribute() method.
    }

}
