<?php

namespace App\Infrastructure\Manager\Plugin;


use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use Doctrine\Common\Reflection\Psr0FindFile;
use Doctrine\Common\Reflection\StaticReflectionParser;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class ManagerBase implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    private $annotationNamespace;

    /**
     * @var string
     */
    private $pluginNamespace;

    /**
     * @var string
     */
    private $pluginInterface;

    /**
     * @var string
     */
    private $annotationClass;

    /**
     * @var string
     */
    private $key = 'type';


    public function __construct(
        ContainerInterface $container,
        string $annotationNamespace,
        string $pluginNamespace,
        string $pluginInterface,
        string $annotationClass
    ) {
        $this->container = $container;
        $this->annotationNamespace = $annotationNamespace;
        $this->pluginNamespace = $pluginNamespace;
        $this->pluginInterface = $pluginInterface;
        $this->annotationClass = $annotationClass;
    }

    abstract protected function getDirectories(): array;
    abstract protected function getCacheKey(): string;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    protected function getRootDir(): string
    {
        return $this->container->get('kernel')->getRootDir();
    }

    public function getAnnotationReader(): Reader
    {
        $annotationReader = new SimpleAnnotationReader();

        $annotationReader->addNamespace($this->annotationNamespace);
        $annotationReader->addNamespace($this->pluginNamespace);

        return $annotationReader;
    }

    protected function getPlugins(): array
    {
        $annotationReader = $this->getAnnotationReader();
        $plugins = [];

        foreach ($this->getDirectories() as $namespace => $dirs) {
            foreach ($dirs as $dir) {
                if (file_exists($dir)) {
                    $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
                    );

                    foreach ($iterator as $fileinfo) {

                        if ($fileinfo->getExtension() === 'php') {
                            $parser = $this->getParser($namespace, $iterator, $fileinfo);

                            if (($annotation = $annotationReader->getClassAnnotation(
                                    $parser->getReflectionClass(),
                                    $this->annotationClass
                                )) && \in_array($this->pluginInterface, class_implements($annotation->class), true)) {
                                $plugins[$annotation->{$this->getKey()}][$annotation->id] = $annotation;
                            }
                        }
                    }
                }
            }
        }

        return $plugins;
    }

    /**
     * @param \Iterator $iterator
     * @param string $namespace
     * @param \SplFileInfo $fileInfo
     *
     * @return string
     */
    protected function getNameSpacedClass(\Iterator $iterator, string $namespace, \SplFileInfo $fileInfo): string
    {
        $sub_path = $iterator->getSubIterator()->getSubPath();
        $sub_path = $sub_path ? str_replace(DIRECTORY_SEPARATOR, '\\', $sub_path).'\\' : '';
        $class = $namespace.'\\'.$sub_path.$fileInfo->getBasename('.php');
        $nameSpacedClass = str_replace('\\', '_', $class);
        $nameSpacedClass = str_replace('App_', '', $nameSpacedClass);

        return $nameSpacedClass;
    }

    /**
     * @param string $namespace
     * @param \Iterator $iterator
     * @param \SplFileInfo $fileinfo
     *
     * @return StaticReflectionParser
     */
    protected function getParser(string $namespace, \Iterator $iterator, \SplFileInfo $fileinfo): StaticReflectionParser
    {
        $nameSpacedClass = $this->getNameSpacedClass($iterator, $namespace, $fileinfo);
        $prefix = str_replace('\\', '_', $namespace);
        $finder = new Psr0FindFile(
            [
                $prefix => [
                    $this->getRootDir(),
                ],
            ]
        );

        return new StaticReflectionParser($nameSpacedClass, $finder);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedServices()
    {
        return
            [
                'kernel' => KernelInterface::class,
            ];
    }

}