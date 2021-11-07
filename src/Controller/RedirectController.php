<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectController extends AbstractController
{
    #[Route('/access', name: "access", methods: 'GET')]
    public function redirectAccordingRole(UserInterface $user): Response
    {
        // dd($userRoles = $user->getRoles());

        $userRoles = $user->getRoles();

        if (in_array("ROLE_ADMIN", $userRoles)) {
            return $this->redirectToRoute('admin');
        }

        if (in_array("ROLE_LIBRAIRE", $userRoles)) {
            return $this->redirectToRoute('libraire');
        }

        return $this->redirectToRoute('app_livre');
    }
}
