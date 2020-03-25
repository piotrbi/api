<?php

namespace App\Tests\Functional\Traits;

use App\Entity\Cart;

trait CartTrait
{
    private function createCart(array $products = []): Cart
    {
        $cart = new Cart();
        foreach ($products as $product) {
            $cart->addProduct($product);
        }

        $em = self::bootKernel()->getContainer()->get('doctrine')->getManager();
        $em->persist($cart);
        $em->flush();

        return $cart;
    }
}