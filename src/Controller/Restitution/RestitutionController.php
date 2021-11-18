<?php

namespace App\Controller\Restitution;

use App\Entity\Reservation;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestitutionController extends AbstractController
{
    #[Route('/restitution', name: 'restitution_user_list')]
    #[IsGranted("ROLE_LIBRAIRE")]
    public function check(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('restitution/user_list.html.twig',[
            'users' => $users,
        ]);
    }

    #[Route('/restitution/{id<[0-9]+>}', name: 'restitution_show')]
    #[IsGranted("ROLE_LIBRAIRE")]
    public function show($id, EntityManagerInterface $em): Response
    {
        $reservation = $em->getRepository(Reservation::class);
        $user = $em->getRepository(User::class);

        /**
         * @var Reservation|null $emprunt
        */
        $empruntUser = $reservation->findReservations($id);
        $targetUser = $user->findOneBy(['id' => $id]);

        if (!$empruntUser) {
            throw $this->createNotFoundException(sprintf
            ("Pas de réservation trouvé pour %s ", $targetUser->getFullname() ));
        }

        return $this->render(
            'restitution/index.html.twig', [
            'emprunt' => $empruntUser,
            'user' => $targetUser
            ]
        );
    }


    #[Route('/restitution/delete/{id<[0-9]+>}', name: 'save_restitution')]
    #[IsGranted("ROLE_LIBRAIRE")]
    public function recordReturn(Reservation $reservation, EntityManagerInterface $em): Response
    {
//        $reservationRepository = $em->getRepository(Reservation::class);
//        $reservation = $reservationRepository->findOneBy(['id' => $reservation]);

        // 1. Je récupére le nombre de livre(s) dans un emprunt
        $totalLivreReserve = count($reservation->getLivre()->getValues() );
//        dump($reservation);

        // 2. J'affiche l'emprunteur d'une réservation ciblée (par ParamConverter)
//             dump( $reservation->getUser()->getFullName() );
        // 3. Afficher sa capacité d'emprunt actuel
            // dump( $reservation->getUser()->getEmpruntMax() );
        // Action - 4. Calculer sa nouvelle capacité d'emprunt
        $resetEmpruntMax = $reservation->getUser()->getEmpruntMax() + $totalLivreReserve;

        // Todo :
        // 5. Recupérer l'id des livres et Traitement
            // ok - a. Recupérer l'id
            // b. Livre - setPret() - 1
            // c. Livre - setQuantite() + 1
            // d. Livre - setUpdatedAt() = now()
            // e. User - setEmpruntMax() + 1
            // f. Reservation - Remove $id
            // g. Reservation - setStatus() = "RENDU"

        $collection = $reservation->getLivre()->getValues();
        $user = $reservation->getUser();

    // a. Recupérer l'id
        foreach ($collection as $livre) {
            $livreId = $livre->getId();
            $livre->setQuantite($livre->getQuantite() + 1);
            $livre->setPret($livre->getPret() - 1);
            $livre->setUpdatedAt( new DateTimeImmutable() );

            $em->remove($reservation);

            /** @var User $user */
            $user->ajouterUnEmpruntMax();
        }

        $em->flush();
        $this->addFlash('success', 'Livre restitué avec succès.');

        return $this->redirectToRoute('restitution_user_list', [
            'id' => $user->getId()
        ]);

    }
}


