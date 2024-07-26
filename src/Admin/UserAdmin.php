<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class UserAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('user_id')
            ->add('email')
            ->add('password')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('phoneNumber')
            ->add('registrationDate')
            ->add('role')
            ->add('token')
            ->add('paymentMethod')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('user_id')
            ->add('email')
            ->add('password')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('phoneNumber')
            ->add('registrationDate')
            ->add('role')
            ->add('token')
            ->add('paymentMethod')
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
            ->add('user_id')
            ->add('email')
            ->add('password')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('phoneNumber')
            ->add('registrationDate')
            ->add('role')
            ->add('token')
            ->add('paymentMethod')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('user_id')
            ->add('email')
            ->add('password')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('phoneNumber')
            ->add('registrationDate')
            ->add('role')
            ->add('token')
            ->add('paymentMethod')
        ;
    }
}
