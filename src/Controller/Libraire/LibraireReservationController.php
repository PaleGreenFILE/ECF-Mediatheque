<?php

namespace App\Controller\Libraire;

use App\Classe\Mail;
use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Libraire;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class LibraireReservationController extends AbstractDashboardController
{
    private UserRepository $userRepository;
    private ReservationRepository $reservationRepository;
    private $session;

    public function __construct(
        UserRepository $userRepository,
        ReservationRepository $reservationRepository,
        RequestStack $requestStack,
        SessionInterface $session
    ) {
        $this->userRepository = $userRepository;
        $this->reservationRepository = $reservationRepository;
        $this->requestStack = $requestStack;

    }
    #[Route('/libraire/check-reservation', name:'check_reservation')]
    public function index(): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        } else {
            $user_autorise = $this->getUser()->getIsAutorise();
            if ($user_autorise == false) {
                return $this->redirectToRoute('app_home');
            }
        }
        $Reservations = $this->reservationRepository->findAll();
        $request = $this->requestStack->getCurrentRequest();

        if ($request->query->get('RestHereCoco')) {
            return $this->redirectToRoute('check_reservation');
        }

        return $this->render(
            'libraire/back_reservation.html.twig', [
            'reservations' => $Reservations,
            ]
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mediatheque Chapelle-Cureaux')
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        // yield MenuItem::linkToDashboard('detail_reservation', 'fas fa-check');

        yield MenuItem::linkToCrud('Liste des inscrits', 'fas fa-users', User::class);

        yield MenuItem::linkToCrud('Liste des livres', 'fas fa-book', Livre::class);

        yield MenuItem::linkToCrud('Reservation', 'fas fa-reservation', Reservation::class);

    }


    #[Route('/libraire/mailing/{id<[0-9]+>}', name:'mailing')]
    public function sendMailRetard(User $user, Mail $mail)
    {
        // dd($reservation->getUser()->getEmail());
        // ! Remplacer par le nom
        // $user = $reservation->getUser()->getEmail();
        // $mailTo = 'bridevproject@gmail.com';
        // $content = 'Bonjour ' .$user. ' vous n\'avez pas restitué les livres empruntés dans le temps';
        // $mail->send($mailTo, $user, 'hello@parlonscode.com', "Retard ...", `
        // `);

        $this->addFlash('success', 'Email de rappel envoyé à ' .$user->getFullName(). ' avec succès');

        // dd($user, $mailTo, $mail);
        return $this->redirectToRoute('restitution_user_list');
    }

}
