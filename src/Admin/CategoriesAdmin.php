<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class CategoriesAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('categoryId')
            ->add('categoryName')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('categoryId')
            ->add('categoryName')
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
            ->add('categoryId')
            ->add('categoryName')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('categoryId')
            ->add('categoryName')
        ;
    }
}
