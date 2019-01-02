<?php

namespace App\Infrastructure\Plugin\Action;

use App\Infrastructure\Annotation\Action;
use App\Infrastructure\Plugin\PluginInterface;
use App\Infrastructure\Plugin\UserActionBase;

/**
 * Block a user.
 *
 * @Action(
 *   id = "block_user",
 *   label = "Block the selected user",
 *   type = "user",
 *   category="user",
 *   class="App\Infrastructure\Plugin\Action\BlockUser"
 * )
 */
class BlockUser extends UserActionBase implements PluginInterface
{
    public function execute(): void
    {
        if (!empty($this->users)) {
            foreach ($this->users as $user) {
                $user->setIsActive(false);
                $this->container->get('doctrine')->getManager()->persist($user);
            }

            $this->container->get('doctrine')->getManager()->flush();
        }

    }

}