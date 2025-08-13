<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DataFixtures;

use App\Entity\Author;
use App\Entity\BlogContent;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Only load the fixtures if we're using the Blog logic
        if (class_exists(Author::class) === false) {
            return;
        }

        $author = new Author();
        $author
            ->setName('Build With Scaffold')
            ->setEmail('scaffold@email.address')
            ->setWebsite('https://buildwithscaffold.com')
            ->setBiography('Build your next project with Scaffold.  A Symfony and Tailwind CSS boilerplate by Chris Shennan')
            ->setJobTitle('A Symfony and Tailwind CSS boilerplate by Chris Shennan')
        ;

        $this->addReference('author_1', $author);

        $manager->persist($author);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['demo'];
    }
}
