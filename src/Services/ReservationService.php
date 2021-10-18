<?php

namespace App\Services;

use App\Entity\Livre;
use App\Repository\UserRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReservationService
{
    protected $session;
    protected $LivreRepository;

    public function __construct(
        SessionInterface $session,
        LivreRepository $LivreRepository,
        EntityManagerInterface $EntityManagerInterface,
        Security $security,
        UserRepository $UserRepository
    )
    {
        $this->session = $session;
        $this->LivreRepository = $LivreRepository;
        $this->EntityManagerInterface = $EntityManagerInterface;
        $this->security = $security;
        $this->UserRepository  = $UserRepository;
    }

    public function getPanier() : array
    {
        return $this->session->get('reservation', []);
    }

    protected function savePanier(array $reservation)
    {
        return $this->session->set('reservation', $reservation);
    }

    public function add(int $id)
    {
        $livre = $this->LivreRepository->find($id);
        // 1. Retrouver le panier utilisateur
        // ? 2. Si panier n'existe pas return []
        $reservation = $this->getPanier();

        // ? 3. Voir si le livre ($id) est deja dans le tableau
        $curentUserId = $this->security->getUser()->getId();

        if (array_key_exists($id, $reservation) && $this->UserRepository->find($curentUserId)->getEmpruntMax() > 0) {
            $reservation[$id]++;
        } else {
            $reservation[$id] = 1;
        }

        $livre->setQuantite($livre->getQuantite() - 1);
        $livre->setPret($livre->getPret() + 1);

        $this->savePanier($reservation);


        $curentUserId = $this->security->getUser()->getId();
        /** @var User*/
        $user = $this->UserRepository->find($curentUserId);
        $user_id = $user->getId();

        if ($user->getEmpruntMax() > 0) {
            /** @var FlashBag */
            $flashBag = $this->session->getBag('flashes');
            $flashBag->add('success', 'Livre réservé.');
        } elseif ($user->getEmpruntMax() === 0) {
            /** @var FlashBag */
            $flashBag = $this->session->getBag('flashes');
            $flashBag->add('danger', 'Vous ne pouvez pas réserver ce livre, votre limite de réservation est atteinte');
        }

        $curentUser = $this->security->getUser();

        // dump($curentUser);

        $curentUser->deduitUnEmpruntMax();

        $this->savePanier($reservation);

        // dd($curentUser);

        $this->EntityManagerInterface->flush();

    }

    public function remove(int $id)
    {
        $livre = $this->LivreRepository->find($id);

        $reservation = $this->getPanier();

        /** @var User*/
        $curentUser = $this->security->getUser();

        $empruntActuel = $curentUser->getEmpruntMax();
        // dump($empruntActuel);

        $updateEmpruntMax = $empruntActuel + $reservation[$id];

        // dd($this->security->getUser()->setEmpruntMax($updateEmpruntMax));
        $this->security->getUser()->setEmpruntMax($updateEmpruntMax);
        $this->EntityManagerInterface->flush();

        unset($reservation[$id]);

        $this->savePanier($reservation);


    }

    public function decrement(int $id): void
    {
        $livre = $this->LivreRepository->find($id);

        $reservation = $this->getPanier();
        /** @var User*/
        $curentUser = $this->security->getUser();


        // 1 livre => je supprime
        if ($reservation[$id] === 1) {
            $this->remove($id);
        } else {
            $reservation[$id]--;
        }

        $curentUser->ajouterUnEmpruntMax();

        $livre->setQuantite($livre->getQuantite() + 1);
        $livre->setPret($livre->getPret() - 1);



        $this->savePanier($reservation);

        $this->EntityManagerInterface->flush();

    }

    public function getDetailReservations(): array
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
