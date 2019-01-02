<?php

namespace App\Infrastructure\Plugin\Action;

use App\Infrastructure\Annotation\Action;
use App\Infrastructure\Plugin\PluginInterface;
use App\Infrastructure\Plugin\UserActionBase;


/**
 * Block a user.
 *
 * @Action(
 *   id = "un_block_user",
 *   label = "Un block the selected user",
 *   type = "user",
 *   category="user",
 *   class="App\Infrastructure\Plugin\Action\UnBlockUser"
 * )
 */
class UnBlockUser extends UserActionBase implements PluginInterface
{
    public function execute(): void
    {
        if (!empty($this->users)) {
            foreach ($this->users as $user) {
                $user->setIsActive(true);
                $this->container->get('doctrine')->getManager()->persist($user);
            }

            $this->container->get('doctrine')->getManager()->flush();
        }
    }

}