<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    public const STATUS_ATTENTE = 'ATTENTE';
    public const STATUS_PRET = 'PRET';
    public const STATUS_RETARD = 'RETARD';

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
     * @ORM\ManyToMany(targetEntity=Livre::class, inversedBy="reservations")
     */
    private $livre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsRestitue;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $EmpruntedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = 'ATTENTE';

    public function __construct()
    {
        $this->livre = new ArrayCollection();
    }

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

    /**
     * @return Collection|Livre[]
     */
    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livre->contains($livre)) {
            $this->livre[] = $livre;
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        $this->livre->removeElement($livre);

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
        return $this->IsRestitue;
    }

    public function setIsRestitue(bool $IsRestitue): self
    {
        $this->IsRestitue = $IsRestitue;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
