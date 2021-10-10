<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivreController extends AbstractController
{
    private $LivreRepository;
    
    function __construct(LivreRepository $LivreRepository)
    {
        $this->LivreRepository = $LivreRepository;
    }

    #[Route('/livre', name: 'app_livre')]
    public function index(): Response
    {

        $livres = $this->LivreRepository->findAll();

        return $this->render('livre/livre.html.twig', [
            'livres' => $livres,
        ]);
    }
}
