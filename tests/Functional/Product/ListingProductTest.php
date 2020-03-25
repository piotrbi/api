<?php

namespace App\Tests\Functional\Product;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ListingProductTest extends ApiTestCase
{
    public function testProperResponseCode(): void
    {
        $client = self::createClient();

        $client->request(
            'GET',
            '/api/products',
            ['headers' => ['Accept' => 'application/hal+json']]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/hal+json; charset=utf-8');
        $this->assertJsonContains([
            'itemsPerPage' => 3,
        ]);
    }
}
