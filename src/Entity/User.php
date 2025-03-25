<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Entity\User as ScaffoldUser;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User extends ScaffoldUser
{
    /**
     * @var Collection<int, Workspace>
     */
    #[ORM\ManyToMany(targetEntity: Workspace::class, inversedBy: 'users')]
    private Collection $workspaces;

    public function __construct()
    {
        $this->workspaces = new ArrayCollection();
    }

    /**
     * @return Collection<int, Workspace>
     */
    public function getWorkspaces(): Collection
    {
        return $this->workspaces;
    }

    public function addWorkspace(Workspace $workspace): static
    {
        if (!$this->workspaces->contains($workspace)) {
            $this->workspaces->add($workspace);
        }

        return $this;
    }

    public function removeWorkspace(Workspace $workspace): static
    {
        $this->workspaces->removeElement($workspace);

        return $this;
    }
}
