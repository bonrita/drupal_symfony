<?php

namespace App\Infrastructure\Form;


use Symfony\Component\Form\Form;

class PasswordVisibility
{

    /**
     * Show password field if true and otherwise not.
     *
     * @var bool
     */
    private $showPassword = false;

    /**
     * Whether the submitted data is valid.
     *
     * @var bool
     */
    private $isValid = false;

    /**
     * @return bool
     */
    public function isShowPassword(): bool
    {
        return $this->showPassword;
    }

    /**
     * @param bool $showPassword
     * @return PasswordVisibility
     */
    public function setShowPassword(bool $showPassword): PasswordVisibility
    {
        $this->showPassword = $showPassword;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     * @return PasswordVisibility
     */
    public function setIsValid(bool $isValid): PasswordVisibility
    {
        $this->isValid = $isValid;
        return $this;
    }

    public function isPasswordError(Form $form, string $propertyPath): bool
    {
        if ($form->getErrors(true)->count() === 1 &&
            $form->getErrors(true)->current()->getCause()->getPropertyPath() === $propertyPath) {
            return true;
        }
        return false;
    }

    public function preErrorChecking(Form $form, string $propertyPath): void
    {
        if (false === $this->setIsValid(false)->isShowPassword() &&
            $this->isPasswordError($form, $propertyPath)) {
            $this->setIsValid(true);
        }
    }

    public function shouldPersistUser(Form $form): bool
    {
        return (!$form->getErrors(true)->valid() || $this->isValid());
    }

}