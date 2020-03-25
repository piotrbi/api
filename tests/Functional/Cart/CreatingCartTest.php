<?php

namespace App\Tests\Functional\Cart;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Tests\Functional\Traits\ProductTrait;

class AddingProductTest extends ApiTestCase
{
    use ProductTrait;

    public function testCreatingEmptyCart(): void
    {
        $response = $this->cartRequest([]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'totalPrice' => '0,00 zł',
        ]);
        $this->assertRegExp('~^/api/carts/\d+$~', $response->toArray()['_links']['self']['href']);
    }

    public function testCreatingCartWithProducts(): void
    {
        $product1 = $this->createProduct(1099);
        $product2 = $this->createProduct(1099);

        $response = $this->cartRequest([
            'products' => [
                '/api/products/' . $product1->getId(),
                '/api/products/' . $product2->getId(),
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'totalPrice' => '21,98 zł',
        ]);
        $this->assertCount(2, $response->toArray()['_embedded']['products']);
    }

    private function cartRequest(array $json): Response
    {
        return self::createClient()->request(
            'POST',
            '/api/carts',
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