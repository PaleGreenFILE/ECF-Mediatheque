<?php

namespace App\Services;

use App\Entity\Livre;
use App\Repository\UserRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Controller\Reservation\ReservationItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReservationService
{
    protected $session;
    protected $LivreRepository;

    public function __construct(
        SessionInterface $session, 
        LivreRepository $LivreRepository, 
        EntityManagerInterface $em, 
        Security $security,
        UserRepository $UserRepository
    )
    {
        $this->session = $session;
        $this->LivreRepository = $LivreRepository;
        $this->EntityManagerInterface = $em;
        $this->security = $security;
        $this->UserRepository  = $UserRepository;;
    }

    public function add(int $id)
    {
        $livre = $this->LivreRepository->find($id);

        // 1. Retrouver le panier utilisateur
        // ? 2. Si panier n'existe pas return []
        $reservation = $this->session->get('reservation', []);

        // ? 3. Voir si le livre ($id) est deja dans le tableau
        if (array_key_exists($id, $reservation)) {
            $reservation[$id]++;
        } else {
            $reservation[$id] = 1;
        }

        // 3.a Oui => Augmente la quantié
        // 3.b Non => L'ajouter
        // 4. Enregistrer le tableau dans la session
        $this->session->set('reservation', $reservation);
    
        

        $curentUserId = $this->security->getUser()->getId();
        /** @var User*/
        $user = $this->UserRepository->find($curentUserId);
        $user_id = $user->getId();

        if ($user->getEmpruntMax() > 0) {
            // $this->livre->retirerUnExemplaire();
            $livre->retirerUnExemplaire();
            $user->deduitUnEmpruntMax();
            /** @var FlashBag */
            $flashBag = $this->session->getBag('flashes');
            $flashBag->add('success', 'Livre réservé.');
        } elseif ($user->getEmpruntMax() === 0) {
            /** @var FlashBag */
            $flashBag = $this->session->getBag('flashes');
            $flashBag->add('danger', 'Vous ne pouvez pas réserver ce livre, votre limite de réservation est atteinte');
        }

    }

    public function remove(int $id)
    {
        $reservation = $this->session->get('reservation', []);

        unset($reservation[$id]);

        $this->session->set('reservation', $reservation);
    }
    
    public function decrement(int $id): int
    {
        $reservation = $this->session->get('reservation', []);

        // if (!array_key_exists($id, $reservation)) {
        //     continue;
        // }

        // 1 livre => je supprime
        if ($reservation[$id] === 1) {
            $this->remove($id);
            // return;
        }

        // Plus d'1 livre => decrement
        $reservation[$id]--;
    }

    public function getDetailReservations():array
    {
        $detailPanier = [];

        $curentUserId = $this->security->getUser()->getId();
        $user = $this->UserRepository->find($curentUserId);

        $empruntRestant = $user->getEmpruntMax();

        foreach($this->session->get('reservation', []) as $id => $quantite){
            $livre = $this->LivreRepository->find($id);

            if (!$livre) {
               continue;
            }

            $detailPanier[] = [
                'livre' => $livre,
                'quantite' => $quantite,
            ];

        }

        return $detailPanier;

    }

        
        
}
