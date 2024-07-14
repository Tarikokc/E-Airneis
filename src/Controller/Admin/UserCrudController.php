<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('userId'),
            EmailField::new('email'),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('address'),
            TextField::new('city'),
            TextField::new('country'),
            TextField::new('phoneNumber'),
            DateField::new('registrationDate'),
            BooleanField::new('role'),
            // TextField::new('token'), // Vous pouvez omettre le champ token si vous ne voulez pas le gérer ici
        ];
    }
    
}
