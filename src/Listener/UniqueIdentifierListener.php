<?php

namespace App\Listener;

use App\Utils\String\Identifier;
use Doctrine\ORM\Event\LifeCycleEventArgs;

class UniqueIdentifierListener
{
    public function prePersist(LifeCycleEventArgs $event)
    {
        $object = $event->getEntity();

        if (property_exists($object, 'identifier') && strlen($object->getIdentifier()) === 0) {
            $em = $event->getEntityManager();
            $entityClassName = $em->getClassMetadata(get_class($object))->getName();

            do {
                $identifier = Identifier::database();
            } while (is_object($em->getRepository($entityClassName)->findOneByIdentifier($identifier)));

            $object->setIdentifier($identifier);
        }
    }
}
