<?php

namespace App\Web\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Route\RouteCollection;

//'sonata.admin.configuration.dashboard_groups' => array(
//    'administration' => array(
//        'label' => 'messages.admin.label',
//        'label_catalogue' => 'messages',
//        'icon' => '<i class="fa fa-cogs"></i>',
//        'items' => array(
//            0 => array(
//                'admin' => 'app.sonata.admin.user',
//                'label' => '',
//                'route' => '',
//                'route_params' => array(
//
//                ),
//                'route_absolute' => false,
//                'roles' => array(
//
//                ),
//            ),
//        ),
//        'on_top' => false,
//        'keep_open' => false,
//        'item_adds' => array(
//
//        ),
//        'roles' => array(
//
//        ),
//    ),
//),
class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('username', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('username');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('username');
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes()
    {
        // See @getBaseRoutePattern. It gives you a chance to define the base route pattern.
        $this->baseRoutePattern = 'people';
        return parent::getRoutes();
    }

}