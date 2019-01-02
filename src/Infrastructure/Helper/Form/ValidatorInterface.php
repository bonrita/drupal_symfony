<?php

namespace App\Infrastructure\Helper\Form;


use App\Domain\Entity\User;
use Symfony\Component\Form\FormInterface;

interface ValidatorInterface
{
    public function setFormBuilder(FormInterface $builder): ValidatorInterface;

    public function usernameOrEmail(string $input): ?User;

}