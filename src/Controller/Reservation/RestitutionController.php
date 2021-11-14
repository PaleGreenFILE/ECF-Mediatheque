<?php

namespace App\Controller\Reservation;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestitutionController extends AbstractController
{
    #[Route('/restitution/{id<[0-9]+>}', name: 'restitution')]
    public function index($id, EntityManagerInterface $em): Response
    {
        $reservation = $em->getRepository(Reservation::class);

        /** @var Reservation|null $emprunt*/
//        $empruntUser = $reservation->findOneBy(array('user' => $id));
        $empruntUser = $reservation->findReservations($id);

        if (!$empruntUser) {
            throw $this->createNotFoundException(sprintf('Pas de réservation trouvé pour " l\'utilisateur %s ".' ,$id));
        }

/*        if (isset($empruntUser)) {
            dd($empruntUser);
        }*/

        return $this->render('restitution/index.html.twig', [
            'emprunt' => $empruntUser,
        ]);
    }
}


