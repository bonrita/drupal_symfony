<?php

namespace App\Infrastructure\Annotation\GridBundle;


use Dtc\GridBundle\Annotation\Action;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
class EditAction extends Action
{
    /**
     * @var string
     */
    public $label = 'Edit';

    /**
     * @var string
     */
    public $route = 'dtc_grid_show';
}