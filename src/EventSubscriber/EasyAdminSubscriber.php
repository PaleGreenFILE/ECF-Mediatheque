<?php

namespace App\EventSubscriber;

use App\Entity\Libraire;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setCreatedAt']
        ];
    }

    public function setCreatedAt(BeforeEntityPersistedEvent $event)
    {

        $entity = $event->getEntityInstance();

        if (!($entity instanceof Libraire)) {
            return;
        }

        $now = new DateTimeImmutable('now');
        $entity->setCreatedAt($now);

    }
}
