<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

/**
 * This trait has the short description propertie and methods for entities.
 */
trait DescriptionTrait
{
    #[ORM\Column(
        type: 'string',
        nullable: true,
        length: 120
    )]
    protected $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
