<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Scaffold\CoreBundle\Repository\UserRepository as ScaffoldUserRepository;

class UserRepository extends ScaffoldUserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
