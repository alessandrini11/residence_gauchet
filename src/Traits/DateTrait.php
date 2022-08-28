<?php

namespace App\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait DateTrait
 */

trait DateTrait
{
    #[ORM\Column(nullable: true)]
    private ?DateTime $createdAt;

    #[ORM\Column(nullable: true)]
    private ?DateTime $updatedAt;

    /**
     * Get the date of creation
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * set date of creation
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the date of update.
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the date of update.
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime('now');

        return $this;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }
}