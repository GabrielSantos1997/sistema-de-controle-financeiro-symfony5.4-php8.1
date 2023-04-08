<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

/**
 * This trait has the base and default id property and methods for entities.
 */
trait IdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
