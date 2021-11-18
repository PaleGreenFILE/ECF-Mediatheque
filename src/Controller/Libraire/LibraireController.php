<?php

namespace App\Controller\Libraire;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Libraire;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class LibraireController extends AbstractDashboardController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route("/libraire", name:"libraire")]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_LIBRAIRE', 'Accès à Libraire', "Cette page est exclusivement réservée aux employés.");
        // return parent::index();
        $users = $this->userRepository->findAll();

        return $this->render(
            'libraire/dashboard.html.twig', [
            'users' => $users
            ]
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mediatheque Chapelle-Cureaux');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToRoute('Naviguer vers le site', 'fa fa-chalkboard-teacher', 'app_livre');

        yield MenuItem::linkToCrud('Liste des inscrits', 'fas fa-users', User::class);

        yield MenuItem::linkToCrud('Liste des livres', 'fas fa-book', Livre::class);

        yield MenuItem::linkToRoute('Liste des emprunts', 'fas fa-journal-whills', 'restitution_user_list');

//        yield MenuItem::linkToRoute('Réservation', 'fas fa-stamp', 'check_reservation');

    }

}
