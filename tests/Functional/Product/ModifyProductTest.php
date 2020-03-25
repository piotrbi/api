<?php

namespace App\Tests\Functional\Product;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Tests\Functional\Traits\ProductTrait;

class ModifyProductTest extends ApiTestCase
{
    use ProductTrait;

    public function testSuccessFlow(): void
    {
        $product = $this->createProduct();

        $this->productModifyRequest($product->getId(), ['price' => 9999, 'title' => 'The Witcher']);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'title' => 'The Witcher',
            'price' => '99,99 zł',
            'id' => $product->getId()
        ]);
    }

    public function testRequestError(): void
    {
        $product = $this->createProduct();

        $this->productModifyRequest($product->getId(), ['price' => 'invalid']);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'title' => 'An error occurred',
            'detail' => 'The type of the "price" attribute must be "int", "string" given.',
        ]);
    }


    private function productModifyRequest(int $id, array $json): Response
    {
        return self::createClient()->request(
            'PUT',
            '/api/products/' . $id,
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
