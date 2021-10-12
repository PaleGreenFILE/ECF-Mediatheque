<?php

namespace App\Controller\Livre;

use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivreController extends AbstractController
{
    private $LivreRepository;
    private $GenreRepository;

    function __construct(LivreRepository $LivreRepository, GenreRepository $GenreRepository)
    {
        $this->LivreRepository = $LivreRepository;
        $this->GenreRepository = $GenreRepository;
    }

    #[Route('/livre', name: 'app_livre')]
    public function index(Request $request): Response
    {
        // Filtre des livres dans un interval de dates -- TEST
        // dd($livres = $this->LivreRepository->selectDateInterval("1980-01-01", "1995-01-01"));

        $user_autorise = $this->getUser()->getIsAutorise();

        if ($user_autorise == false) {
            return $this->redirectToRoute('app_home');
        }

        // * Pagination

        // ? Définir la limite de livre par page
        $limit = 5;

        // ? Récupérer le numéro de ma page
        $page = (int)$request->query->get('page', 1);

        // ? Calculer le nombre total de livres
        $total = (int)$this->LivreRepository->getTotalLivres();

        // dd($total);
        // dd($page);

        // ? Récupérer tous les livres
        // $livres = $this->LivreRepository->findAll();

        // ? Récupérer les livres en limitant le nombre d'affichage
        $livres = $this->LivreRepository->getPaginationLivre($page, $limit);

        // ? Récupérer tous les genres
        $genres = $this->GenreRepository->findAll();

        return $this->render('livre/livre.html.twig', [
            'livres' => $livres,
            'genres' => $genres,
            'page'   => $page,
            'limit'  => $limit,
            'total'  => $total,
        ]);
    }
}
