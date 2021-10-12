<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class AdminController extends AbstractDashboardController
{
    private $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'L\'utilisateur a essayé d\'accéder à une page sans avoir le ROLE_ADMIN');
        // return parent::index();
        $users = $this->userRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
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

        yield MenuItem::linkToRoute('Site - Home Page', 'fa fa-jedi', 'app_home');

        yield MenuItem::linkToCrud('Liste des inscrits', 'fas fa-users', User::class);

        yield MenuItem::linkToCrud('Liste des livres', 'fas fa-book', Livre::class);

        yield MenuItem::linkToCrud('Genres', 'fas fa-box', Genre::class);
    }
}
