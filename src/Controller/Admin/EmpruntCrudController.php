<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Emprunt;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EmpruntCrudController extends AbstractCrudController
{
    public function generateEmprunt()
    {
        $test = fn (Livre $livre) => $livre->$this->getIsbn() .'-'. $livre->$this->getQuantite();
    }

    public static function getEntityFqcn(): string
    {
        return Emprunt::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            
            TextField::new('numEmprunt', 'id_Emprunt'),
            
            AssociationField::new('livre', 'Emprunt')
                ->setColumns('col-sm-12 col-md-6 col-lg-6 col-xxl-6'),

            AssociationField::new('user', 'Prêter à')
                ->setColumns('col-sm-12 col-md-6 col-lg-6 col-xxl-6'),            
            
            FormField::addRow()
                ->addCssClass('mt-5'),

            DateField::new('EmprunterAt', 'Date de l\'emprunt')
                ->setColumns('col-sm-12 col-md-6 col-lg-6 col-xxl-6'),
            
            DateField::new('RendreAt', 'Date de retour prévu')
                ->setColumns('col-sm-12 col-md-6 col-lg-6 col-xxl-6'),
            
            BooleanField::new('isRendu', 'Rendu')
                ->addCssClass('mt-5 px-3'),
            

            // ! test
            FormField::addPanel('Numéro d\'emprunt unique par Livre')
                ->addCssClass('text-capitalize'),

            
            TextField::new('numEmprunt', 'Numéro d\'Emprunt',
            [
                'label' => 'NumEmprunt',
            ]), 
        ];
    }
    
}
