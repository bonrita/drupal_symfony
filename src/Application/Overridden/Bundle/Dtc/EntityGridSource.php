<?php

namespace App\Application\Overridden\Bundle\Dtc;

use App\Infrastructure\Annotation\GridBundle\EditAction;
use Doctrine\ORM\EntityManager;
use Dtc\GridBundle\Annotation\DeleteAction;
use Dtc\GridBundle\Annotation\ShowAction;
use Dtc\GridBundle\Grid\Column\ActionGridColumn;
use Dtc\GridBundle\Grid\Source\EntityGridSource as CommunityEntityGridSource;
use Symfony\Component\Routing\RouterInterface;

class EntityGridSource extends CommunityEntityGridSource
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @inheritdoc
     */
    public function __construct(EntityManager $entityManager, $entityName, RouterInterface $router)
    {
        parent::__construct($entityManager, $entityName);
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public function setColumns($columns)
    {
        /** @var AbstractGridColumn $col */
        foreach ($columns as $col) {
            $this->columns[$col->getField()] = $col;

            if ($col instanceof ActionGridColumn) {
                $col->setRouter($this->router);
            }

        }
    }

    /**
     * @inheritdoc
     */
    protected function readGridAnnotations()
    {
        $metadata = $this->getClassMetadata();
        $reflectionClass = $metadata->getReflectionClass();
        $properties = $reflectionClass->getProperties();

        /** @var Grid $gridAnnotation */
        $sort = null;
        if ($gridAnnotation = $this->reader->getClassAnnotation($reflectionClass, 'Dtc\GridBundle\Annotation\Grid')) {
            $actions = $gridAnnotation->actions;
            $sort = $gridAnnotation->sort;
        }

        $gridColumns = [];
        foreach ($properties as $property) {
            /** @var \Dtc\GridBundle\Annotation\Column $annotation */
            $annotation = $this->reader->getPropertyAnnotation($property, 'Dtc\GridBundle\Annotation\Column');
            if ($annotation) {
                $name = $property->getName();
                $label = $annotation->label ?: $this->fromCamelCase($name);
                $gridColumns[$name] = ['class' => '\Dtc\GridBundle\Grid\Column\GridColumn', 'arguments' => [$name, $label]];
                $gridColumns[$name]['arguments'][] = null;
                if ($annotation->sortable) {
                    $gridColumns[$name]['arguments'][] = ['sortable' => true];
                } else {
                    $gridColumns[$name]['arguments'][] = [];
                }
                $gridColumns[$name]['arguments'][] = $annotation->searchable;
                $gridColumns[$name]['arguments'][] = $annotation->order;
            }
        }

        // Fall back to default column list if list is not specified
        if (!$gridColumns) {
            $gridColumnList = $this->getReflectionColumns();
            /** @var GridColumn $gridColumn */
            foreach ($gridColumnList as $field => $gridColumn) {
                $gridColumns[$field] = ['class' => '\Dtc\GridBundle\Grid\Column\GridColumn', 'arguments' => [$field, $gridColumn->getLabel(), null, ['sortable' => true], true, null]];
            }
        }

        if (isset($actions)) {
            $field = '\$-action';
            $actionArgs = [$field];
            $actionDefs = [];
            /* @var Action $action */
            foreach ($actions as $action) {
                $actionDef = ['label' => $action->label, 'route' => $action->route];
                if ($action instanceof ShowAction) {
                    $actionDef['action'] = 'show';
                }
                if ($action instanceof DeleteAction) {
                    $actionDef['action'] = 'delete';
                }
                // Add custom action.
                if ($action instanceof EditAction) {
                    $actionDef['action'] = 'edit';
                    $actionDef['route'] = 'user_admin_roles_edit';
                }
                $actionDefs[] = $actionDef;
            }
            $actionArgs[] = $actionDefs;
            // Override grid column class.
            $gridColumns[$field] = ['class' => '\App\Application\Overridden\Bundle\Dtc\ActionGridColumn',
                'arguments' => $actionArgs, ];
        }

        $this->sortGridColumns($gridColumns);
        try {
            $sortInfo = $this->extractSortInfo($sort);
            $this->validateSortInfo($sortInfo, $gridColumns);
        } catch (\InvalidArgumentException $exception) {
            throw new \InvalidArgumentException($reflectionClass->getName().' - '.$exception->getMessage(), $exception->getCode(), $exception);
        }

        return ['columns' => $gridColumns, 'sort' => $sortInfo];
    }


}