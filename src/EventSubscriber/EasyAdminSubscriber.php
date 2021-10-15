<?php

namespace App\EventSubscriber;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Libraire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['ajoutUtilisateur']
        ];
    }

    public function ajoutUtilisateur(BeforeEntityPersistedEvent $event)
    {

        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $this->setPassword($entity);
    }

    public function setPassword(User $entity): void
    {
        $password = $entity->getPassword();

        $entity->setPassword(
            $this->passwordHasher->hashPassword(
                $entity,
                $password
            )
        );

        $entity->setRoles(['ROLE_USER']);
        
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
