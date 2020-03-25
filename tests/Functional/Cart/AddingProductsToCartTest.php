<?php

namespace App\Tests\Functional\Cart;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Tests\Functional\Traits\CartTrait;
use App\Tests\Functional\Traits\ProductTrait;

class AddingProductsToCartTest extends ApiTestCase
{
    use ProductTrait;
    use CartTrait;

    public function testSuccessFlow(): void
    {
        $cart = $this->createCart();

        $response = $this->cartRequest(
            $cart->getId(),
            [
                'products' => [
                    '/api/products/' . $this->createProduct(1000, 'Life Is Peachy')->getId(),
                    '/api/products/' . $this->createProduct(2000, 'Follow the Leader')->getId(),
                    '/api/products/' . $this->createProduct(3000, 'Issues')->getId(),
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'totalPrice' => '60,00 zł',
        ]);
        $this->assertCount(3, $response->toArray()['_embedded']['products']);
    }

    public function testMaximumCartSize(): void
    {
        $cart = $this->createCart();

        $this->cartRequest(
            $cart->getId(),
            [
                'products' => [
                    '/api/products/' . $this->createProduct(1000, 'Life Is Peachy')->getId(),
                    '/api/products/' . $this->createProduct(2000, 'Follow the Leader')->getId(),
                    '/api/products/' . $this->createProduct(3000, 'Issues')->getId(),
                    '/api/products/' . $this->createProduct(1, 'Load')->getId(),
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'products: You cannot add more than 3 product to the cart',
        ]);
    }

    public function testRemovingFromCart(): void
    {
        $cart = $this->createCart([
            $product1 = $this->createProduct(1000, 'Life Is Peachy'),
            $product2 = $this->createProduct(2000, 'Follow the Leader')
        ]);

        $response = $this->cartRequest(
            $cart->getId(),
            ['products' => ['/api/products/' . $product2->getId()]]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'totalPrice' => '20,00 zł',
        ]);
        $this->assertCount(1, $response->toArray()['_embedded']['products']);
        $this->assertEquals('Follow the Leader', $response->toArray()['_embedded']['products'][0]['title']);
    }

    private function cartRequest($id, array $json): Response
    {
        return self::createClient()->request(
            'PUT',
            '/api/carts/' . $id,
            [
                'json' => $json,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/hal+json'
                ]
            ]
        );
    }
}