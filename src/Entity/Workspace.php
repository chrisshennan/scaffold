<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WorkspaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Entity\Workspace as ScaffoldWorkspace;

#[ORM\Entity(repositoryClass: WorkspaceRepository::class)]
class Workspace extends ScaffoldWorkspace
{
    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'workspaces')]
    private Collection $users;

    public function __construct()
    {
        parent::__construct();

        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addWorkspace($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeWorkspace($this);
        }

        return $this;
    }
}
