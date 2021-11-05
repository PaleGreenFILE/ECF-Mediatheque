<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('fullName', 'Nom Complet')->hideOnForm()->setColumns('col-12'),

            TextField::new('prenom', "Prénom")->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4')->hideOnIndex(),

            TextField::new('nom', "Nom")->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4')->hideOnIndex(),

            DateField::new('date_naissance', 'Date de naissance')->setColumns('col-sm-4 col-md-4 col-lg-4 col-xxl-4'),

            textField::new('adresse')->setColumns('col-12'),

            EmailField::new('email', "Email",[
                'input' => 'fullname'
            ])->setColumns('col-6'),

            TextField::new('password', 'Mot de passe')->onlyWhenCreating()->setColumns('col-6'),


            ChoiceField::new ('roles')->setChoices([
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                'ROLE_LIBRAIRE' => 'ROLE_LIBRAIRE',
                'ROLE_USER' => 'ROLE_USER',
            ])->allowMultipleChoices()->setColumns('col-6')->hideOnIndex(),

            BooleanField::new('isAutorise', "Autoriser"),

        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['isAutorise' => 'ASC'])
            ->setPageTitle('index', 'Liste des inscrits')
            ->setPageTitle('new', fn () => 'Inscription')
            ->setPageTitle('detail',
                fn (User $user) => $user->getFullName()
            );
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->IsGranted('ROLE_LIBRAIRE')) {
            return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, 'detail')
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->disable(Action::NEW, Action::DELETE);
        }

        return $actions
            ->add(Crud::PAGE_INDEX, 'detail');
    }

}
