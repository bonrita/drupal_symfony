<?php

namespace App\Infrastructure\Helper\Form;


use App\Domain\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class Validator implements ValidatorInterface
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @var FormInterface
     */
    private $builder;

    public function setFormBuilder(FormInterface $builder): ValidatorInterface
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Validate existance of username or email.
     * @param string $input
     */
    public function usernameOrEmail(string $input): ?User
    {

        $user = $this->managerRegistry->getRepository(User::class)->loadUserByUsername($input);
        if (null === $user) {
            $this->builder->addError(new FormError('forms.request.password.error'));
        }

        return $user;
    }

}