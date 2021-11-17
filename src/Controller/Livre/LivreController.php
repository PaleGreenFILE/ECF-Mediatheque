<?php

namespace App\Controller\Livre;

use App\Entity\Livre;
use App\Form\LivreFormType;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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

       
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_home');
        }

        $user_autorise = $this->getUser()->getIsAutorise();
        if ($user_autorise === false) {
            return $this->redirectToRoute('app_home');
        }

        // * Pagination

        // ? Définir la limite de livre par page
        $limit = 4;

        // ? Récupérer le numéro de ma page
        $page = (int)$request->query->get('page', 1);

        // ? Récupérer les genres pour filtrer
        $filtres = $request->get("genre");

        // ? Récupérer les livres en limitant le nombre d'affichage
        $livres = $this->LivreRepository->getPaginationLivre($page, $limit, $filtres);

        // ? Calculer le nombre total de livres
        $total = (int) $this->LivreRepository->getTotalLivres($filtres);

        // ? Récupérer tous les genres
        $genres = $this->GenreRepository->findAll();

        // ? Vérifie si j'ai une requête ajax
        if ($request->get('ajax')) {
            /*return new JsonResponse([
                'content' => $this->renderView('livre/_content_ajax.html.twig', compact(
                    'livres', 'genres', 'page', 'limit', 'total'
                ))
            ]);*/
            return new JsonResponse(
                [
                'content' => $this->renderView(
                    'livre/_table.html.twig', compact(
                        'livres', 'genres', 'page', 'limit', 'total'
                    )
                )
                ]
            );
        }
        // ? Passer les livres
        $Books = $this->LivreRepository->findAll();

        return $this->render(
            'livre/livre.html.twig', [
            'livres' => $livres,
            'genres' => $genres,
            'page'   => $page,
            'limit'  => $limit,
            'total'  => $total,
            'books'  => $Books
            ]
        );
    }

    #[IsGranted('ROLE_LIBRAIRE')]
    #[Route('/livre/add', name: 'add_livre')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreFormType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($livre);
            $em->flush();

            $this->addFlash('success', 'Votre livre est ajouté en base de donnée.');

            return $this->redirectToRoute('app_show_livre', ['id' => $livre->getId()]);
        }

        return $this->renderForm(
            'livre/create.html.twig', [
            'form' => $form
            ]
        );

    }
}
