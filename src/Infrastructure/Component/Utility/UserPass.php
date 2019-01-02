<?php

namespace App\Infrastructure\Component\Utility;

use App\Infrastructure\Component\Time;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserPass implements UserPassInterface
{
    /**
     * @var RequestStack
     */
    protected $time;
    private $crypt;
    private $params;

    public function __construct(Time $time, Crypt $crypt, ParameterBagInterface $params)
    {
        $this->time = $time;
        $this->crypt = $crypt;
        $this->params = $params;
    }

    public function getStringToHash(User $user, int $timestamp): string
    {
        $data = $timestamp;
        $data .= $user->getLastLoginTime();
        $data .= $user->getId();
        $data .= $user->getEmail();

        return $data;
    }

    public function getHash(User $user, int $timestamp): string
    {
        $data = $this->getStringToHash($user, $timestamp);

        return $this->crypt->hmacBase64($data, $this->params->get('kernel.secret').$user->getPassword());
    }

    public function getResetParams(User $user): array
    {
        $timestamp = $this->time->getRequestTime();
        return [
            'uid' => $user->getId(),
            'timestamp' => $timestamp,
            'hash' => $this->getHash($user, $timestamp),
            'language' => $user->getLangcode(),
        ];
    }

}