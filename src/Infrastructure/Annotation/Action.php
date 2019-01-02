<?php

namespace App\Infrastructure\Annotation;

/**
 * Defines an Action annotation object.
 *
 * @Annotation
 */
class Action
{

    /**
     * The plugin ID.
     *
     * @var string
     */
    public $id;

    /**
     * The human-readable name of the action plugin.
     */
    public $label;

    /**
     * The route name for a confirmation form for this action.
     *
     * @todo Provide a more generic way to allow an action to be confirmed first.
     *
     * @var string (optional)
     */
    public $confirm_form_route_name = '';

    /**
     * The entity type the action can apply to.
     *
     * @todo Replace with \Drupal\Core\Plugin\Context\Context.
     *
     * @var string
     */
    public $type = '';

    /**
     * The category under which the action should be listed in the UI.
     */
    public $category = '';

    public $class = '';

}