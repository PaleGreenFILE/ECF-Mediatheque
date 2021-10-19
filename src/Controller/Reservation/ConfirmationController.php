<?php

namespace App\Controller\Reservation;

use DateTimeImmutable;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Repository\LivreRepository;
use App\Services\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmationController extends AbstractController
{
    private $LivreRepository;
    private $UserRepository;
    private $ReservationService;

    public function __construct(
        LivreRepository $LivreRepository,
        UserRepository $UserRepository,
        ReservationService $Service,
        SessionInterface $Session
    )
    {
        $this->LivreRepository = $LivreRepository;
        $this->UserRepository = $UserRepository;
        $this->ReservationService = $Service;
        $this->SessionInterface = $Session;
    }

    #[Route('/panier/confirmation', name: 'confirmation_reservation')]
    public function confirmation(ReservationService $Service, EntityManagerInterface $entityManager)
    {

        $reservationDetail = $Service->getDetailReservations();
        $curentUserName = $this->getUser()->getFullName();
        $curentUser = $this->getUser();

        if ($reservationDetail != null) {

            $reservation = new Reservation();

            $reservation->setUser($curentUser);

            foreach ($reservationDetail as $one) {
                $reservation->addLivre($one['livre']);
            }

            $reservation->setEmpruntedAt(new DateTimeImmutable());
            $reservation->setIsValidate(false);
            $reservation->setIsRestitue(false);
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->get('session')->remove('reservation');
            $this->addFlash('success', 'Merci, votre réservation est bien enregistré, un Libraire va prochainement vous contacter.');
        } else {
            $this->addFlash('danger', 'Veuillez réserver au moins un livre.');
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/membre/{id}/reservation', name:'detail_reservation')]
    public function showOneReservationAction($id, ReservationRepository $reservationRepository, LivreRepository $livreRepository)
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $user_autorise = $this->getUser()->getIsAutorise();
            if ($user_autorise == false) {
                return $this->redirectToRoute('app_home');
            }

            $reservations = $reservationRepository->findReservations($this->getUser());
            // dd($reservations);

        }

    }

}