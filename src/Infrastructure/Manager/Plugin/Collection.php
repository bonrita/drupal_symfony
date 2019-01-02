<?php

namespace App\Infrastructure\Manager\Plugin;


use App\Infrastructure\Plugin\PluginInterface;

class Collection
{

    /**
     * @var array
     */
    private $plugins;

    public function __construct()
    {
        $this->plugins = [];
    }

    public function addPlugin(PluginInterface $plugin): void
    {
        $this->plugins[\get_class($plugin)] = $plugin;
    }

    /**
     * @return array
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }

}