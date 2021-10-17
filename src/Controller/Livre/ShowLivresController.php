<?php

namespace App\Controller\Livre;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowLivresController extends AbstractController
{
    private $GenreRepository;
    
    function __construct(LivreRepository $LivreRepository, GenreRepository $GenreRepository)
    {
        $this->LivreRepository = $LivreRepository;
        $this->GenreRepository = $GenreRepository;
    }

    #[Route('/voir-les-livres-par-genre/{id<[0-9]+>}', name: 'app_show_livre')]
    public function showLivresByGenre(?Genre $genre): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        } else {
             $user_autorise = $this->getUser()->getIsAutorise();
             if ($user_autorise == false) {
                return $this->redirectToRoute('app_home');
             }
        }
        
        // ? Tester si le N° de genre existe
        if ($genre) {
            // ? Récupérer les livres du genre choisis par l'utilisateur
            $livres = $genre->getLivres()->getValues();
            $genreChoisis = $genre->getNom();
        } else {
            return $this->redirectToRoute('app_home');
        }

        $genres = $this->GenreRepository->findAll();

        return $this->render('livre/livreParGenre.html.twig', [
            'livres' => $livres,
            'genreChoisis' => $genreChoisis,
            'genres' => $genres,
        ]);
    }

    #[Route('/livre/{id<[0-9]+>}', name: 'app_detail_livre')]
    public function detailLivre($id, ?Livre $livres): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $user_autorise = $this->getUser()->getIsAutorise();
            if ($user_autorise == false) {
                return $this->redirectToRoute('app_home');
            }
        }

        // Vérifier si le livre existe
        $livre = $this->LivreRepository->find($id);

        if (!$livre) {
            throw $this->createNotFoundException("Le livre $id n'éxiste pas !...");
        }

        return $this->render('livre/detail_livre.html.twig', [
            'livre' => $livre
        ]);

    }
}