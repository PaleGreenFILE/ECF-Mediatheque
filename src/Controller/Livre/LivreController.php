<?php

namespace App\Controller\Livre;

use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
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
    public function index(): Response
    {

        // ? Récupérer tous les livres
        $livres = $this->LivreRepository->findAll();

        // ? Récupérer tous les genres
        $genres = $this->GenreRepository->findAll();

        return $this->render('livre/livre.html.twig', [
            'livres' => $livres,
            'genres' => $genres
        ]);
    }
}
