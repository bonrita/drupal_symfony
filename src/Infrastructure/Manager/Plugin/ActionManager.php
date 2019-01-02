<?php

namespace App\Infrastructure\Manager\Plugin;


use App\Infrastructure\Annotation\Action;
use App\Infrastructure\Plugin\ActionInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class ActionManager extends ManagerBase
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct(
            $container,
            'App\Infrastructure\Annotation',
            'App\Infrastructure\Plugin\Action',
            ActionInterface::class,
            Action::class
        );
    }

    protected function getDirectories(): array
    {
        return [
            'Infrastructure\Plugin\Action' => ["{$this->getRootDir()}/Infrastructure/Plugin/Action"],
        ];
    }

    public function getPluginByType()
    {
        $cache = $this->container->get(AdapterInterface::class);
        $item = $cache->getItem($this->getCacheKey());

        if (!$item->isHit()) {
            $item->set($this->getPlugins());
            $cache->save($item);
        }

        $plugins = $item->get();

        return $plugins;
    }

    /**
     * @param string $key
     * @param string $pluginId
     *
     * @return ActionInterface|null
     */
    public function getPlugin(string $key, string $pluginId):? ActionInterface {
        $plugins = $this->getPluginByType();
        $pluginCollection = $this->container->get(Collection::class)->getPlugins();

        /** @var Action $pluginAnnotation */
        $pluginAnnotation = $plugins[$key][$pluginId];
        if (array_key_exists($pluginAnnotation->class, $pluginCollection)) {
            return $pluginCollection[$pluginAnnotation->class];
        }

        throw new \UnexpectedValueException('The requested pluginId  does not exist.');
    }

    protected function getCacheKey(): string
    {
        return "action_{$this->getKey()}";
    }

    public function getPluginServices(): array
    {
        $services = [];
        foreach ($this->getPluginByType() as $index => $item) {
            $services[$item->class] = $item->class;
        }

        return $services;
    }

    public static function getSubscribedServices()
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                AdapterInterface::class => AdapterInterface::class,
                Collection::class => Collection::class,
            ]
        );
    }

}