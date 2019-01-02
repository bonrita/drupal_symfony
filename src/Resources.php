<?php

namespace App;


class Resources
{
    /**
     * Returns the absolute path to the data directory.
     *
     * @return string The absolute path to the data directory
     */
    public static function getDataDirectory()
    {
        return __DIR__.'/Web/Resources/data';
    }

}