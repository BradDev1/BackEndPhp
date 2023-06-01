<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use App\Entity\Post;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $todo= Array();
           for ($i = 0; $i < 50; $i++) {
               $todo[$i] = new Post();
               $todo[$i]->setTitle($faker->name);
               $todo[$i]->setContent($faker->text);
               $todo[$i]->setDate($faker->Datetime);

               $manager->persist($todo[$i]);
           }
        $manager->flush();
    }
}
