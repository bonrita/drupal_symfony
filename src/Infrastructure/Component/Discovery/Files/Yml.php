<?php

namespace App\Infrastructure\Component\Discovery\Files;


use App\Resources;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Yml implements YmlInterface
{

    /**
     * @var string
     */
    private $folder = '';

    /**
     * {@inheritdoc}
     */
    public function findData(): array
    {
        $files = [];

        $finder = new Finder();
        $finder->in($this->getDirectory())->name('*.'.self::EXT);

        /** @var SplFileInfo $item */
        foreach ($finder as $index => $item) {
            $domain = $this->getDomain($item);
            $files[$domain] = $item;
        }

        return $files;
    }


    protected function getDomain(SplFileInfo $fileInfo)
    {
        return trim(str_replace($fileInfo->getExtension(), '', $fileInfo->getFilename()), '.');
    }

    public function setFolder(string $folder)
    {
        $this->folder = $folder;

        return $this;
    }

    protected function getDirectory(): string
    {
        $folder = empty($this->folder) ? '' : "/{$this->folder}";

        return Resources::getDataDirectory().$folder;
    }

}
