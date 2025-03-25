<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
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
            'email' => 'admin.one@email.address',
            'firstName' => 'First',
            'surname' => 'Admin',
            'roles' => [
                'ROLE_WORKSPACE_ADMIN',
            ],
            'password' => 'password',
        ];

        yield [
            'email' => 'admin.two@email.address',
            'firstName' => 'Second',
            'surname' => 'Admin',
            'roles' => [
                'ROLE_WORKSPACE_ADMIN',
            ],
            'password' => 'password',
        ];
    }
}
