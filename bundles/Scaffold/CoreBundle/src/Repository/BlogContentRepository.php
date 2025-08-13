<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Repository;

use App\Entity\BlogContent;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

/**
 * @extends ServiceEntityRepository<BlogContent>
 */
abstract class BlogContentRepository extends ServiceEntityRepository
{
    /**
     * @return array<BlogContent>
     */
    public function findPublished(int $offset = 0, int $limit = 100): array
    {
        $queryBuilder = $this->createQueryBuilder('blogPost')
            ->where('blogPost.publishedAt <= :publishedAt')
        ;

        $queryBuilder->orderBy('blogPost.publishedAt', 'DESC');

        $queryBuilder->setParameters(new ArrayCollection([
            new Parameter('publishedAt', new DateTimeImmutable()),
        ]));

        $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
