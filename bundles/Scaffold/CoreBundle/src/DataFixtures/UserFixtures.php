<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Only load the fixtures if we're using the User logic
        if (class_exists(User::class) === false) {
            return;
        }

        foreach ($this->getUserDetails() as $index => $details) {
            $user = new User();
            $user->setEmail($details['email']);
            $user->setFirstName($details['firstName']);
            $user->setSurname($details['surname']);
            $user->setRoles($details['roles']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $details['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference('user_'.$index + 1, $user);
        }

        $manager->flush();
    }

    private function getUserDetails(): Generator
    {
        yield [
            'email' => 'site.admin@email.address',
            'firstName' => 'Site',
            'surname' => 'Admin',
            'roles' => [
                'ROLE_SITE_ADMIN',
            ],
            'password' => 'password',
        ];

        yield [
            'email' => 'user@email.address',
            'firstName' => 'Normal',
            'surname' => 'User',
            'roles' => [
                'ROLE_WORKSPACE_ADMIN',
            ],
            'password' => 'password',
        ];
    }

    public static function getGroups(): array
    {
        return ['demo'];
    }
}
