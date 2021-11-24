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
    private $ReservationRepo;

    public function __construct(
        LivreRepository $LivreRepository,
        UserRepository $UserRepository,
        ReservationService $Service,
        SessionInterface $Session,
        ReservationRepository $ReservationRepo,
    ) {
        $this->LivreRepository = $LivreRepository;
        $this->UserRepository = $UserRepository;
        $this->ReservationService = $Service;
        $this->SessionInterface = $Session;
        $this->ReservationRepo = $ReservationRepo;
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

            $reservation->setEmpruntedAt(new \DateTime());
            $reservation->setIsValidate(false);
            $reservation->setIsRestitue(false);
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->get('session')->remove('reservation');
            $this->addFlash('success', 'Merci, votre réservation est bien enregistré, un Libraire va prochainement vous contacter.');
        } else {
            $this->addFlash('danger', 'Veuillez réserver au moins un livre.');
        }

        $curentUser = $this->getUser();

        // ? Récupérer la réservation de l'utilisateur connecté
        // dd($curentUser);
        $ReservationUserCurent = $this->ReservationRepo->findBy(['user' => $curentUser]);

        return $this->redirectToRoute('panier');

        return $this->render(
            'reservation/detail_reservation.html.twig', [
            'reservations' => $ReservationUserCurent,
            ]
        );

    }
}
