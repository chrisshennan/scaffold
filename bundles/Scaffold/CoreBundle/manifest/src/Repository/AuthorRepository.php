<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Persistence\ManagerRegistry;
use Scaffold\CoreBundle\Repository\AuthorRepository as ScaffoldAuthorRepository;

class AuthorRepository extends ScaffoldAuthorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }
}
