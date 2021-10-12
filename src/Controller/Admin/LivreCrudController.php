<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LivreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre')
                ->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4'),

            AssociationField::new('genre', 'Genre')
                ->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4'),

            DateField::new('parution', 'Date de parution')
                ->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4'),

            TextEditorField::new('description')
                ->setColumns('col-sm-12 col-md-12 col-lg-12 col-xxl-12'),

            textField::new('auteur', 'Auteur')
                ->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4'),

            IntegerField::new('quantite', 'Exemplaire')
                ->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4')
        ];
    }

}
