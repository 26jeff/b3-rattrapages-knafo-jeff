<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Faker\Factory;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 8; $i++) {
            $article = new Article();
            $article->setName($faker->words(2, true))
                ->setImage($faker->imageUrl(400, 400, 'food', true, 'Gourmet'))
                ->setDescription($faker->sentence(12))
                ->setPrice($faker->randomFloat(2, 2, 20))
                ->setRating($faker->randomFloat(1, 2, 20))
                ->setInStock($faker->boolean(60));
            $manager->persist($article);
        }
        $manager->flush();
    }
}
