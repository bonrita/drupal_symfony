<?php

namespace App\Application\Message;


final class UserActionCommand
{

    /**
     * @var string
     */
    private $actionId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $users;

    public function __construct(string $actionId, array $users, string $type)
    {
        $this->actionId = $actionId;
        $this->users = $users;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getActionId(): string
    {
        return $this->actionId;
    }

}