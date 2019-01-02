<?php

namespace App\Application\Overridden\Bundle\Dtc;

use Dtc\GridBundle\Grid\Column\ActionGridColumn as CommunityActionGridColumn;
use Dtc\GridBundle\Grid\Source\GridSourceInterface;

class ActionGridColumn extends CommunityActionGridColumn
{
    /**
     * @inheritdoc
     */
    public function format($object, GridSourceInterface $gridsource)
    {
        $content = parent::format($object, $gridsource);

        $method = 'get'.ucfirst($this->idField);
        $id = $object->$method();

        foreach ($this->actions as $action => $options) {
            $label = $options['label'];
            if ($content) {
                $content .= ' ';
            }
            switch ($options['action']) {
                case 'edit':
                    $route = $this->router->generate($options['route'], ['role' => $object->getId()]);

                    $content .= '<a class="btn btn-primary" href="'.$route.'" role="button">'.$label.'</a>';
                    break;
            }
        }

        return $content;
    }

}