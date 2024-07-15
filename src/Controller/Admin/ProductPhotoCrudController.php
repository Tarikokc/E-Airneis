<?php

namespace App\Controller\Admin;

use App\Entity\ProductPhoto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ProductPhotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductPhoto::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('photoUrl')
                ->setLabel('URL de la photo'), 
            BooleanField::new('isPrimary')
                ->setLabel('Photo principale'),
            AssociationField::new('product')
                ->setLabel('Produit'),
        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Photo de produit')
            ->setEntityLabelInPlural('Photos de produit');
    }
    
}
