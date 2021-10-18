<?php

namespace App\Controller\Reservation;

use App\Repository\UserRepository;
use App\Repository\LivreRepository;
use App\Services\ReservationService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmationController extends AbstractController
{
    private $LivreRepository;
    private $UserRepository;
    private $ReservationService;

    public function __construct(
        LivreRepository $LivreRepository,
        UserRepository $UserRepository,
        ReservationService $Service
    )
    {
        $this->LivreRepository = $LivreRepository;
        $this->UserRepository = $UserRepository;
        $this ->ReservationService = $Service;
    }

    #[Route('/panier/confirmation', name: 'confirmation_reservation')]
    public function confirmation(ReservationService $Service)
    {

        $reservation = $Service->getDetailReservations();
        $curentUserName = $this->getUser()->getFullName();
        $curentUserId = $this->getUser()->getId();

        dd($curentUserName, $curentUserId, $reservation);

        return $this->render('detail_reservation.html.twig', [
            'items' => $reservation
        ]);
    }
}