<?php

namespace App\Listener;

use Doctrine\ORM\Event\LifeCycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TimestampsListener
{
    public function prePersist(LifeCycleEventArgs $event)
    {
        $object = $event->getEntity();

        if (property_exists($object, 'isActive') && is_null($object->getIsActive())) {
            $object->setIsActive(true);
        }

        if (property_exists($object, 'createdAt')) {
            $object->setCreatedAt($object->getCreatedAt() ?? new \DateTime());
        }

        if (property_exists($object, 'updatedAt')) {
            $object->setUpdatedAt(new \DateTime());
        }

        if (property_exists($object, 'expiresAt')) {
            $className = get_class($object);
            if (defined($className . '::EXPIRE_TIME_SECONDS')) {
                $object->setExpiresAt((new \DateTime())->add(new \DateInterval('PT' . $className::EXPIRE_TIME_SECONDS . 'S')));
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $em = $event->getEntityManager();
        $unitOfWork = $em->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityUpdates() as $object) {
            if (property_exists($object, 'updatedAt')) {
                $object->setUpdatedAt(new \DateTime());

                $em->merge($object);
            }

            if (property_exists($object, 'isActive') && property_exists($object, 'deletedAt')) {
                if (!$object->getIsActive()) {
                    $object->setDeletedAt($object->getDeletedAt() ?? new \DateTime());
                    $em->merge($object);
                } elseif ($object->getIsActive()) {
                    $em->merge($object->setDeletedAt(null));
                }
            }
        }
    }
}
