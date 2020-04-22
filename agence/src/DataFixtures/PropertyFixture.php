<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for($i=0; $i< 100; $i++) {
            $property = new Property();
            $property
                ->setTitle($faker->words(3,true))
                ->setDescription($faker->sentences(3, true))
                ->setSurface($faker->numberBetween(20,350))
                ->setRooms($faker->numberBetween(2,10))
                ->setBedrooms($faker->numberBetween(1,9))
                ->setFloor($faker->numberBetween(0,15))
                ->setPrice($faker->numberBetween(100000,1234000))
                ->setHeat($faker->numberBetween(0, count(Property::HEAT) - 1))
                ->setCity($faker->city)
                ->setAdress($faker->address)
                ->setPostalCode($faker->postcode)
                ->setSold(false);

            $manager->persist($property);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
