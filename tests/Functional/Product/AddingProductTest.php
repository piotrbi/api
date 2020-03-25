<?php

namespace App\Tests\Functional\Product;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Entity\Product;

class AddingProductTest extends ApiTestCase
{
    public function testAddingNewProduct(): void
    {
        $response = $this->apiProductsRequest([
            'title' => 'New Product',
            'price' => 1099
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'New Product',
            'price' => '10,99 zł',
        ]);
        $this->assertRegExp('/^\d+$/', $response->toArray()['id']);
    }

    public function testBadPriceRequest(): void
    {
        $this->apiProductsRequest([
            'title' => 'New Product',
            'price' => "invalid"
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'The type of the "price" attribute must be "int", "string" given.',
        ]);
    }

    public function testBadTitleRequest(): void
    {
        $this->apiProductsRequest([
            'price' => 999
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'title: This value should not be blank.',
        ]);
    }

    private function apiProductsRequest(array $json): Response
    {
        return self::createClient()->request(
            'POST',
            '/api/products',
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
