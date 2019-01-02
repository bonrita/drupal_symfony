<?php

namespace App\Application\Overridden\Bundle\Dtc;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Dtc\GridBundle\Grid\Source\DocumentGridSource;
use Dtc\GridBundle\Manager\GridSourceManager;
use Symfony\Component\Routing\RouterInterface;

class CustomGridSourceManager extends GridSourceManager
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @inheritdoc
     */
    protected function getGridSource($manager, $entityOrDocument)
    {
        $repository = $manager->getRepository($entityOrDocument);
        if ($repository) {
            $className = $repository->getClassName();
            $classMetadata = $manager->getClassMetadata($className);
            $name = $classMetadata->getName();
            $reflectionClass = $classMetadata->getReflectionClass();
            $annotation = $this->reader->getClassAnnotation($reflectionClass, 'Dtc\GridBundle\Annotation\Grid');
            if (!$annotation) {
                throw new \Exception("GridSource requested for '$entityOrDocument' but no Grid annotation found");
            }
            if ($manager instanceof EntityManagerInterface) {
                // Class overridden to use our column.
                $gridSource = new EntityGridSource($manager, $name, $this->router);
            } else {
                $gridSource = new DocumentGridSource($manager, $name);
            }
            $gridSource->setAnnotationReader($this->reader);
            $gridSource->setCacheDir($this->cacheDir);

            $gridSource->setDebug($this->debug);
            $gridSource->autoDiscoverColumns();
            $this->sourcesByName[$name] = $gridSource;
            $this->sourcesByClass[$className] = $gridSource;
            $gridSource->setId($className);

            return $gridSource;
        }

        return null;
    }

    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

}