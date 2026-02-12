<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DataFixtures;

use App\Entity\Author;
use App\Entity\BlogContent;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class BlogFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Only load the fixtures if we're using the Blog logic
        if (class_exists(BlogContent::class) === false) {
            return;
        }

        $blog = new BlogContent();
        $blog
            ->setTitle('My First Blog Post')
            ->setAuthor($this->getReference('author_1', Author::class))
            ->setSummary('Find out the basic styles available for your blog posts.')
            ->setSlug('my-first-blog-post')
            ->setPublishedAt(new DateTimeImmutable('-1 week'))
            ->setContent(<<<EOF
## This is a h2 heading

### This is a h3 heading

#### This is a h4 heading
EOF
            );
        
        $manager->persist($blog);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['demo'];
    }
}
