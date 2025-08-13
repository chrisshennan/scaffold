<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 */
abstract class UserRepository extends ServiceEntityRepository
{
}
