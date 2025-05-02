<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Scaffold\CoreBundle\Entity\WaitlistEntry;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class WaitlistEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WaitlistEntry::class);
    }
}
