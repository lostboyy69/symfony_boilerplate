<?php

namespace App\DataFixtures;



use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $admin = new User();    
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin_password'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstname('admin');
        $admin->setLastname('admin');
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user_password'));
        $user->setRoles(['ROLE_USER']);
        $user->setFirstname('user');
        $user->setLastname('user');

        $managerUser = new User();
        $managerUser->setEmail('manager@example.com');
        $managerUser->setFirstname('Manager');
        $managerUser->setLastname('User');
        $managerUser->setPassword($this->passwordHasher->hashPassword($managerUser, 'manager_password'));
        $managerUser->setRoles(['ROLE_MANAGER']);
        $manager->persist($user);
        $manager->flush();
    }
}
