<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

/**
 * This trait has the base and default timestamps properties and methods for
 * entities.
 */
trait TimestampsSimpleTrait
{
    #[ORM\Column(
        type: 'datetime',
        nullable: false
    )]
    protected $createdAt;

    #[ORM\Column(
        type: 'datetime',
        nullable: false
    )]
    protected $updatedAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
