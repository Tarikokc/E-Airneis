<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class ProduitsAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('productId')
            ->add('Nom')
            ->add('Description')
            ->add('prix')
            ->add('Stock')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('productId')
            ->add('Nom')
            ->add('Description')
            ->add('prix')
            ->add('Stock')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('productId')
            ->add('Nom')
            ->add('Description')
            ->add('prix')
            ->add('Stock')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('productId')
            ->add('Nom')
            ->add('Description')
            ->add('prix')
            ->add('Stock')
        ;
    }
}
