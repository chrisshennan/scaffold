<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Author>
 */
abstract class AuthorRepository extends ServiceEntityRepository
{
}
