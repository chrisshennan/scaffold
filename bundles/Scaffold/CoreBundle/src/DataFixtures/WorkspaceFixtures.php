<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DataFixtures;

use App\Entity\User;
use App\Entity\Workspace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkspaceFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Only load the fixtures if we're using the Workspace logic
        if (class_exists(Workspace::class) === false) {
            return;
        }

        for ($i = 1; $i <= 2; ++$i) {
            $workspace = new Workspace();
            $workspace->setName('Workspace '.$i);
            $workspace->addUser($this->getReference('user_'.$i, User::class));

            $manager->persist($workspace);

            $this->addReference('workspace_'.$i, $workspace);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['demo'];
    }
}
