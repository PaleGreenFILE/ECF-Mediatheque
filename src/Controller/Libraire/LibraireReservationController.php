<?php

namespace App\Controller\Libraire;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Libraire;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class LibraireReservationController extends AbstractDashboardController
{
    private $userRepository;
    private $reservationRepository;

    public function __construct(
        UserRepository $userRepository,
        ReservationRepository $reservationRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->reservationRepository = $reservationRepository;
    }
    #[Route('/libraire/check-reservation', name:'check_reservation')]
    public function index(): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $user_autorise = $this->getUser()->getIsAutorise();
            if ($user_autorise == false) {
                return $this->redirectToRoute('app_home');
            }
        }

        // ? Récupérer la réservation de l'utilisateur connecté

        // dd($this->reservationRepository->findAll());
        $ReservationUserCurent = $this->reservationRepository->findAll();

        return $this->render('libraire/back_reservation.html.twig', [
            'reservations' => $ReservationUserCurent,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new ()
            ->setTitle('Mediatheque Chapelle-Cureaux');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        // yield MenuItem::linkToDashboard('detail_reservation', 'fas fa-check');

        yield MenuItem::linkToCrud('Liste des inscrits', 'fas fa-users', User::class);

        yield MenuItem::linkToCrud('Liste des livres', 'fas fa-book', Livre::class);

        yield MenuItem::linkToCrud('Reservation', 'fas fa-reservation', Reservation::class);

    }

}
