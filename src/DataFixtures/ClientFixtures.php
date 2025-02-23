<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setFirstname($faker->firstName);
            $client->setLastname($faker->lastName);
            $client->setEmail($faker->email);
            $client->setPhoneNumber($faker->phoneNumber);
            $client->setAddress($faker->address);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
