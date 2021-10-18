<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="reservations")
     */
    private $livre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRestitue;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $EmpruntedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getIsValidate(): ?bool
    {
        return $this->isValidate;
    }

    public function setIsValidate(bool $isValidate): self
    {
        $this->isValidate = $isValidate;

        return $this;
    }

    public function getIsRestitue(): ?bool
    {
        return $this->isRestitue;
    }

    public function setIsRestitue(bool $isRestitue): self
    {
        $this->isRestitue = $isRestitue;

        return $this;
    }

    public function getEmpruntedAt(): ?\DateTimeImmutable
    {
        return $this->EmpruntedAt;
    }

    public function setEmpruntedAt(\DateTimeImmutable $EmpruntedAt): self
    {
        $this->EmpruntedAt = $EmpruntedAt;

        return $this;
    }
}
