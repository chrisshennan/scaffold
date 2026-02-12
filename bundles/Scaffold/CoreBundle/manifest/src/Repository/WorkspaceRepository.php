<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Workspace;
use Doctrine\Persistence\ManagerRegistry;
use Scaffold\CoreBundle\Repository\WorkspaceRepository as ScaffoldWorkspaceRepository;

class WorkspaceRepository extends ScaffoldWorkspaceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workspace::class);
    }
}
