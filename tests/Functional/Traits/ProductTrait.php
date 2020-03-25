<?php

namespace App\Tests\Functional\Traits;

use App\Entity\Product;

trait ProductTrait
{
    private function createProduct(int $price = 1, string $title = 'title'): Product
    {
        $product = new Product();
        $product->setPrice($price);
        $product->setTitle($title);

        $em = self::bootKernel()->getContainer()->get('doctrine')->getManager();
        $em->persist($product);
        $em->flush();

        return $product;
    }
}
