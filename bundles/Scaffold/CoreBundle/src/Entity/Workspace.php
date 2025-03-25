<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Traits\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

#[ORM\MappedSuperclass]
class Workspace implements \Stringable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator('uuid7_generator')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private string $name;

    public function __construct()
    {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->getName();
    }
}
