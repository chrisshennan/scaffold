<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableTrait
{
    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable]
    private ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): static
    {
        $this->createdAt = new DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): static
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }
}
