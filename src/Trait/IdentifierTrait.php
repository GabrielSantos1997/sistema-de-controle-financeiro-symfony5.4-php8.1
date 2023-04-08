<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * This trait has the base and default timestamps properties and methods for
 * entities.
 */
trait IdentifierTrait
{
    #[ORM\Column(
        type: 'string',
        nullable: false,
        length: 15
    )]
    protected $identifier;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('identifier', new Assert\Regex([
            'pattern' => '/^[a-zA-Z\d\-\_]{15}$/',
        ]));
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }
}
