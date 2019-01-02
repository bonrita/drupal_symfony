<?php

namespace App;

use App\Infrastructure\Plugin\PluginInterface;
use App\Application\DependencyInjection\Compiler\PluginPass;
use App\Application\DependencyInjection\Security\Factory\PasswordResetFactory;
use App\Application\Overridden\Bundle\Dtc\CustomGridSourceManager;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return $this->getProjectDir().'/var/log';
    }

    public function registerBundles()
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        // Feel free to remove the "container.autowiring.strict_mode" parameter
        // if you are using symfony/dependency-injection 4.0+ as it's the default behavior
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }

    public function build(ContainerBuilder $container)
    {
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new PasswordResetFactory());

        $container->registerForAutoconfiguration(PluginInterface::class)
            ->addTag('app.plugin');

        $container->addCompilerPass(new PluginPass());
    }

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('dtc_grid.manager.source')) {
            $definition = $container->getDefinition('dtc_grid.manager.source');
            $definition->setClass(CustomGridSourceManager::class);
        }
        $this->addRouter($container);
    }

    public function addRouter(ContainerBuilder $container)
    {
        $sourceManager = $container->getDefinition('dtc_grid.manager.source');

        if ($container->has('router')) {
            $sourceManager->addMethodCall('setRouter', [new Reference('router')]);
        }
    }


}
