<?php

namespace App\Brt\MachineBundle\Transformer;


use Symfony\Component\Form\DataTransformerInterface;

class ViewManipulate implements DataTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return '';
        }

        // Lowercase.
        $name = strtolower($value);

        // Replace spaces, underscores, and dashes with underscores.
        $name = preg_replace('/(\s|_+|-+)+/', '_', $name);

        // Trim underscores from the ends.
        $name = trim($name, '_');

        // Remove all except alpha-numeric and underscore characters.
        $name = preg_replace('/[^a-z0-9_]+/', '', $name);

        $name = strtoupper($name);

        if (strpos($name, 'ROLE_') === false) {
            $name = "ROLE_{$name}";
        } elseif ('ROLE_' !== substr($name, 0, 5) && strpos($name, 'ROLE_') > 0) {
            $name = "ROLE_{$name}";
        }

        return $name;

    }

}