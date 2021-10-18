<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmpruntRepository;

/**
 * @ORM\Entity(repositoryClass=EmpruntRepository::class)
 */
class EmpruntOld
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $EmprunterAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $RendreAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRendu;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $numEmprunt;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Livre::class, cascade={"persist", "remove"})
     */
    private $livre;

    public function __construct()
    {
        $this->EmprunterAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmprunterAt(): ?\DateTimeImmutable
    {
        return $this->EmprunterAt;
    }

    public function setEmprunterAt(\DateTimeImmutable $EmprunterAt): self
    {
        $this->EmprunterAt = $EmprunterAt;

        return $this;
    }

    public function getRendreAt(): ?\DateTimeImmutable
    {
        return $this->RendreAt;
    }

    public function setRendreAt(\DateTimeImmutable $RendreAt): self
    {
        $this->RendreAt = $RendreAt;

        return $this;
    }

    public function getIsRendu(): ?bool
    {
        return $this->isRendu;
    }

    public function setIsRendu(bool $isRendu): self
    {
        $this->isRendu = $isRendu;

        return $this;
    }

    public function getNumEmprunt(): ?string
    {
        return $this->numEmprunt;
        // return $this->getIsbn().'-'. $this->getQuantite();
    }

    public function setNumEmprunt(?string $numEmprunt): self
    {
        $this->numEmprunt = $numEmprunt;

        return $this;
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
}
