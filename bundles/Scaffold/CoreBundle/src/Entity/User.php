<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Traits\TimestampableTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\MappedSuperclass]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator('uuid7_generator')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $surname;

    /**
     * @var string[]
     */
    #[ORM\Column]
    private array $roles = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    #[\Override]
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    #[\Override]
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getFullName(): string
    {
        return $this->getFirstName().' '.$this->getSurname();
    }

    /**
     * @return string[]
     */
    #[\Override]
    public function getRoles(): array
    {
        if (!empty($this->roles)) {
            return $this->roles;
        }

        return ['ROLE_USER'];
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isSiteAdmin(): bool
    {
        return in_array('ROLE_SITE_ADMIN', $this->getRoles());
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
