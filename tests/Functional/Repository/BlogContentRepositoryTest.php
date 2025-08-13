<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Entity\BlogContent;
use App\Factory\AuthorFactory;
use App\Factory\BlogContentFactory;
use App\Repository\BlogContentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[CoversClass(BlogContentRepository::class)]
class BlogContentRepositoryTest extends KernelTestCase
{
    use Factories;

    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindForPublicView(): void
    {
        BlogContentFactory::new([
            'author' => AuthorFactory::new(),
        ])
            ->sequence([
                ['title' => 'Public Blog Post 1', 'publishedAt' => new DateTimeImmutable('-1 day')],
                ['title' => 'Public Blog Post 2', 'publishedAt' => new DateTimeImmutable()],
                ['title' => 'Public Blog Post 3', 'publishedAt' => new DateTimeImmutable('+2 day')],
            ])->create();

        /** @var BlogContentRepository $blogContentRepository */
        $blogContentRepository = $this->entityManager->getRepository(BlogContent::class);
        $actual = $blogContentRepository->findPublished(0, 10);

        self::assertCount(2, $actual);
    }
}
