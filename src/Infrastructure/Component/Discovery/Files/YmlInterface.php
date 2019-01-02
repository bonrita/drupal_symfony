<?php

namespace App\Infrastructure\Component\Discovery\Files;


interface YmlInterface
{
    public const EXT = 'yml';

    /**
     * Returns an array of file paths, keyed by provider.
     *
     * @return array
     */
    public function findData();

    public function setFolder(string $folder);
}