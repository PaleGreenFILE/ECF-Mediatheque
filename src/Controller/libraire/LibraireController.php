<?php

namespace App\Controller\libraire;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Entity\Libraire;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class LibraireController extends AbstractDashboardController
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/libraire", name="libraire")
     */
    public function index(): Response
    {
        // return parent::index();
        $users = $this->userRepository->findAll();

        return $this->render('libraire/dashboard.html.twig', [
            'users' => $users
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mediatheque Chapelle-Cureaux');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToRoute('Utilisateur non autorisés', 'fas fa-check', 'libraire_check_user');
        yield MenuItem::linkToCrud('Liste des inscrits', 'fas fa-users', User::class);

        yield MenuItem::linkToCrud('Liste des livres', 'fas fa-book', Livre::class);
        
        yield MenuItem::linkToCrud('Emprunt', 'fas fa-book-reader', Emprunt::class);
    }
}
