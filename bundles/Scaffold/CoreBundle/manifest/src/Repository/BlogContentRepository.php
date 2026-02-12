<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BlogContent;
use Doctrine\Persistence\ManagerRegistry;
use Scaffold\CoreBundle\Repository\BlogContentRepository as ScaffoldBlogContentRepository;

class BlogContentRepository extends ScaffoldBlogContentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogContent::class);
    }
}
