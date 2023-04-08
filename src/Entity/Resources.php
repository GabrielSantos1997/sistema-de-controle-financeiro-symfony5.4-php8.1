<?php

namespace App\Entity;

use App\Repository\ResourcesRepository;
use App\Trait\DescriptionTrait;
use App\Trait\IdTrait;
use App\Trait\IdentifierTrait;
use App\Trait\IsActiveTrait;
use App\Trait\TimestampsTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResourcesRepository::class)]
class Resources
{
    use IdTrait;
    use DescriptionTrait;
    use IdentifierTrait;
    use IsActiveTrait;
    use TimestampsTrait;

    #[ORM\Column(
        type: 'float',
        nullable: false
    )]
    protected $value;

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
