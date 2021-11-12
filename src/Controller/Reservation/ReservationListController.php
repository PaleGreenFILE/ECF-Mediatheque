<?php

namespace App\Controller\Reservation;

use App\Entity\Reservation;
use App\Repository\LivreRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationListController extends AbstractController
{
    private $LivreRepository;

    public function __construct(
        ReservationRepository $ReservationRepo,
        LivreRepository $LivreRepo,
    )
    {
        $this->LivreRepository = $LivreRepo;
        $this->ReservationRepository = $ReservationRepo;
    }


    #[Route('/membre/{id}/reservation', name:'detail_reservation')]
    public function showOneReservationAction()
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_home');
        }
        $user_autorise = $this->getUser()->getIsAutorise();
        if ($user_autorise === false) {
            return $this->redirectToRoute('app_home');
        }

        $curentUser = $this->getUser();

        // ? Récupérer la réservation de l'utilisateur connecté
        $ReservationUserCurent = $this->ReservationRepository->findBy(['user' => $curentUser]);
        // dd($ReservationUserCurent);

        // ? Afficher les livres
        // dd($this->ReservationRepository->getLivre());
        // dd($this->reservation->getLivre());


        return $this->render('reservation/detail_reservation.html.twig', [
            'reservations' => $ReservationUserCurent
        ]);

    }
}