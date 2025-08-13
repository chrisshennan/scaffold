<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Repository;

use App\Entity\Workspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Workspace>
 */
abstract class WorkspaceRepository extends ServiceEntityRepository
{
}
