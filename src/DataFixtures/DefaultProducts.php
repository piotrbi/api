<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DefaultProducts extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['The Godfather', 5999],
            ['Steve Jobs', 4995],
            ['The Return of Sherlock Holmes', 3999],
            ['The Little Prince', 2999],
            ['I Hate Myselfie!', 1999],
            ['The Trial', 999]
        ];

        foreach ($data as $item) {
            $product = new Product();
            $product->setTitle($item[0]);
            $product->setPrice($item[1]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
