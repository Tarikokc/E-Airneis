<?php
// src/Admin/OrderDetailAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class OrderDetailAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('orderDetailId')
            ->add('order')
            ->add('product')
            ->add('quantity')
            ->add('unitPrice');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('orderDetailId')
            ->add('order')
            ->add('product')
            ->add('quantity')
            ->add('unitPrice')
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
      ->add('order')
      ->add('product')
      ->add('quantity')
      ->add('unitPrice');
  }
}
