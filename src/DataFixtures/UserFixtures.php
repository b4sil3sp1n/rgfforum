<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// cf. cours 12, Fixtures. Comptes de DEV uniquement : jamais en production.
class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // email, mot de passe, rôles
        $users = [
            ['operator@rgf.fr', 'admin', ['ROLE_ADMIN']],
            ['rasta@rgf.fr', 'user', ['ROLE_USER']],
        ];

        foreach ($users as [$email, $password, $roles]) {
            $user = new User();
            $user->setEmail($email)->setRoles($roles);
            // On ne stocke jamais le mot de passe en clair : on le hache
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
