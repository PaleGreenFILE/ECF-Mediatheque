<?php

namespace App\Controller\Reservation;

use App\Entity\User;
use App\Entity\Livre;

class ReservationItem 
{
    public $livre;
    public $empruntRestant;
    
    public function __construct(Livre $livre, User $user)
    {
        $this->livre = $livre;
        $this->user = $user;
    }

    public function getEmprunRestant(): int
    {
        dd($this->user->getEmpruntMax());
        return $this->user->getEmpruntMax();
    }

}
