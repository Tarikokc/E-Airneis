<?php
// src/Admin/OrderAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;

final class OrderAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('order_id')
            ->add('user')
            ->add('order_date')
            ->add('total_amount');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('order_id')
            ->add('user')
            ->add('order_date', null, ['format' => 'd/m/Y'])
            ->add('total_amount')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }


    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('user')
            ->add('order_date', DateType::class, [
                'widget' => 'single_text', // Choisir le bon type de widget pour la date
                'format' => 'yyyy-MM-dd', // Format de la date
                'required' => false // Définir si la date est requise ou non
            ])
            ->add('total_amount');
    }
}
