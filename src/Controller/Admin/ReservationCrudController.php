<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Reservation;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')
                ->onlyOnIndex(),

            BooleanField::new('isValidate', 'Commande prête'),

            BooleanField::new('isRestitue', 'Rendu'),

            // TextField::new('status')->function(updateStatus()),

            textField::new('status', 'Statut'),

            DateTimeField::new('empruntedAt', 'Date d\'emprunt'),

            AssociationField::new('user'),

            AssociationField::new('livre', 'id')
        ];
    }

     private $LivreRepository;

    public function __construct(
        ReservationRepository $ReservationRepo,
        LivreRepository $LivreRepo,
    )
    {
        $this->LivreRepository = $LivreRepo;
        $this->ReservationRepository = $ReservationRepo;
    }

    public function updateStatus($id, ReservationRepository $reservationRepository, EntityManagerInterface $em)
    {
        dd($reservationRepository->getIsValidate());
        if ($reservationRepository->isValidate() == true ) {
            $entity = $this->em->getRepository('ReservationRepository')->find($id);

            $entity->setStatus('PRET');
            $entity->setEmpruntedAt(new \DateTime('now'));

            $this->em->persist($entity);
            $this->em->flush();

            $this->addFlash('success', 'Données mise à jour');
        }
    }

    public function showOneReservationAction()
    {
        $curentUser = $this->getUser();

        // ? Récupérer la réservation de l'utilisateur connecté
        $ReservationUserCurent = $this->ReservationRepository->findBy(['user' => $curentUser]);

        return $this->render('reservation/detail_reservation.html.twig', [
            'reservations' => $ReservationUserCurent
        ]);

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['user' => 'ASC']);
    }

}