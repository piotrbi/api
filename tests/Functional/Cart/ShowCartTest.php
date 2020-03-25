<?php

namespace App\Tests\Functional\Cart;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Tests\Functional\Traits\CartTrait;
use App\Tests\Functional\Traits\ProductTrait;

class ShowCartTest extends ApiTestCase
{
    use ProductTrait;
    use CartTrait;

    public function testShowCart(): void
    {
        $cart = $this->createCart([
            $product1 = $this->createProduct(1000, 'Life Is Peachy'),
            $product2 = $this->createProduct(2000, 'Follow the Leader')
        ]);

        $response = $this->cartRequest($cart->getId());

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'totalPrice' => '30,00 zł',
        ]);
        $this->assertCount(2, $response->toArray()['_embedded']['products']);
    }

    public function testCartNotFound()
    {
        $this->cartRequest(999999);

        $this->assertResponseStatusCodeSame(404);
    }

    private function cartRequest(int $id): Response
    {
        return self::createClient()->request(
            'GET',
            '/api/carts/' . $id,
            [
                'headers' => [
                    'Accept' => 'application/hal+json'
                ]
            ]
        );
    }
}