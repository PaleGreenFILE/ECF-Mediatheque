<?php

namespace App\Controller\Reservation;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Form\EmpruntFormType;
use App\Repository\UserRepository;
use App\Repository\LivreRepository;
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
    public function add($id, SessionInterface $session, Livre $livre, EntityManagerInterface $em, User $user): Response
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

        // ! Supprimer la session :
        // $session->remove('reservation');   

        // ! ##### TEST ######
        $curentUserId = $this->getUser()->getId();
        /** @var User*/
        $user = $this->UserRepository->find($curentUserId);
        $user_id = $user->getId();

        if ($user->getEmpruntMax() > 0) {
            $livre->retirerUnExemplaire();
            $user->deduitUnEmpruntMax();
            /** @var FlashBag */
            $flashBag = $session->getBag('flashes');
            $flashBag->add('success', 'Livre réservé.');
        } elseif ($user->getEmpruntMax() === 0) {
            /** @var FlashBag */
            $flashBag = $session->getBag('flashes');
            $flashBag->add('danger', 'Vous ne pouvez pas réserver ce livre, votre limite de réservation est atteinte');
        }
        
        $em->flush();

        // ! ##### TEST ######

        // * Retour attendu :
            // Reservation du livre $id 124 + 2 réservations du livre $id 136
            // [124 => 1, 136 => 2] 

        // 1. Retrouver le panier utilisateur
        // ? 2. Si panier n'existe pas return []
        $reservation = $session->get('reservation', []);

        // ? 3. Voir si le livre ($id) est deja dans le tableau
        if (array_key_exists($id, $reservation)) {
            $reservation[$id]++;
        } else {
            $reservation[$id] = 1;
        }

        // 3.a Oui => Augmente la quantié
        // 3.b Non => L'ajouter
        // 4. Enregistrer le tableau dans la session
        $session->set('reservation', $reservation);

        // dd($session->get('reservation'));

        return $this->redirectToRoute('app_detail_livre', [
            'id' => $livre->getId()
        ]);
    }

    #[Route('/reservation/panier', name: 'app_panier')]
    public function panier()
    {
        return $this->render('reservation/panier.html.twig');
    }

    #[Route('/reservation/test', name: 'test')]
    public function test(Request $request, EntityManagerInterface $em)
    {
        // dd($this->getUser()->getEmpruntMax());
        $limitLivre = $this->getUser()->getEmpruntMax();
        
        $emprunt = new Emprunt();

        $form = $this->createForm(EmpruntFormType::class, $emprunt);

        $form->handleRequest($request);

        if ($form -> isSubmitted() && $form->isValid()) {

            $em->persist($emprunt);
            $em->flush();

            $this->addFlash('success', 'Livre réservé avec succès');

            return $this->redirectToRoute('app_livre');
        }

        return $this->renderForm('reservation/test.html.twig', [
            'form' => $form
        ]);
    }

}
