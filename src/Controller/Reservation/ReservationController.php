<?php

namespace App\Controller\Reservation;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Form\EmpruntFormType;
use App\Repository\UserRepository;
use App\Repository\LivreRepository;
use App\Services\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    private $LivreRepository;

    function __construct(LivreRepository $LivreRepository, UserRepository $UserRepository)
    {
        $this->LivreRepository = $LivreRepository;
        $this->UserRepository  = $UserRepository;
    }

    #[Route('/reservation/livre/{id<[0-9]+>}', name: 'app_reservation')]
    public function add($id, Livre $livre, ReservationService $reservationService, EntityManagerInterface $em): Response
    {
        // ? Redirection de l'utilisateur si il n'est pas connecté
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        } else {
             $user_autorise = $this->getUser()->getIsAutorise();
             if ($user_autorise == false) {
                return $this->redirectToRoute('app_home');
             }
        }

        // ? Vérifier si le livre existe
        $livre = $this->LivreRepository->find($id);

        if (!$livre) {
            throw $this->createNotFoundException("Le livre $id n'éxiste pas !...");
        }

        // ! Injecter le service
        $reservationService->add($id);

        $em->flush();

        // ! Supprimer la session :
        // $session->remove('reservation');   

        // ! ##### TEST ######

        // dd($session->get('reservation'));

        return $this->redirectToRoute('app_detail_livre', [
            'id' => $livre->getId()
        ]);
    }

    #[Route('/panier' , name: 'panier')]
    public function showPanier(SessionInterface $session, ReservationService $reservationService): Response
    {

        $detailPanier = $reservationService->getDetailReservations();

        $curentUserId = $this->getUser()->getId();
        $user = $this->UserRepository->find($curentUserId);
        $empruntRestant = $user->getEmpruntMax();

        // dd($detailPanier);
        return $this->render('reservation/panier.html.twig', [
            'items' => $detailPanier,
            'emprunt' => $empruntRestant
        ]);
    }

}
