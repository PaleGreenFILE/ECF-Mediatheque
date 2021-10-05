<?php

namespace App\Controller\Admin;

use App\Entity\Libraire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LibraireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Libraire::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom', 'Nom')->setColumns('col-sm-6 col-lg-5 col-xxl-3'),
            TextField::new('prenom', 'Prenom')->setColumns('col-sm-6 col-lg-5 col-xxl-3'),
            TextField::new('email', 'Email')->setColumns('col-sm-6 col-lg-5 col-xxl-3'),
            TextField::new('password', 'Mot de passe')->setColumns('col-sm-6 col-lg-5 col-xxl-3')->onlyWhenCreating(),
            ChoiceField::new ('roles')->setColumns('col-sm-6 col-lg-5 col-xxl-3')->setChoices([
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                'ROLE_LIBRAIRE' => 'ROLE_LIBRAIRE',
            ])->allowMultipleChoices(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste du personnels')
            ->setPageTitle('new', fn () => 'Ajouter un nouvel employé');

    }
}
