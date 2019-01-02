<?php

namespace App\Infrastructure\Twig;


use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array(
            new TwigFunction('getLocales', [$this, 'getLocales']),
            new TwigFunction('getFormElementErrorMessage', [$this, 'getFormElementErrorMessage']),
        );
    }

    public function getLocales(string $locales): ?array
    {
        $languages = [];
        $data = explode('|', $locales);

        foreach ($data as $datum) {
            $items = explode('_', $datum);
            $languages[] = [
                'lang' => $items[0],
                'country_code' => $items[1]
            ];
        }

        return $languages;
    }

    public function getFormElementErrorMessage(FormErrorIterator $errorInstance, $propertyPath)
    {
        $message = '';
        /** @var FormError $error */
        foreach ($errorInstance as $error) {
            if ($error->getCause()->getPropertyPath() === $propertyPath) {
                $message = $error->getCause()->getMessage();
            }
        }

        return $message;
    }

}
