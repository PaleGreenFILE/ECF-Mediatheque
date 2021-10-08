<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectController extends AbstractController
{
    #[Route('/access',  methods: 'GET', name:"access")]
    public function redirectAccordingRole(UserInterface $user): Response
    {
        dd($userRoles = $user->getRoles());

        if (in_array("ROLE_ADMIN", $userRoles) && in_array("ROLE_USER", $userRoles))   {
            return $this->redirectToRoute('admin');
        } elseif (in_array("ROLE_LIBRAIRE", $userRoles)) {
            return $this->redirectToRoute('libraire');
        } else {
            return $this->redirectToRoute('app_home');
        }
    }
}
