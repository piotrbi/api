<?php

namespace App\Tests\Functional\Product;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Entity\Product;

class RemovingProductTest extends ApiTestCase
{
    public function testDelete(): void
    {
        $product = new Product();
        $product->setPrice(1);
        $product->setTitle('title');

        $em = self::bootKernel()->getContainer()->get('doctrine')->getManager();
        $em->persist($product);
        $em->flush();

        $this->productDeleteRequest($product->getId());

        $this->assertResponseStatusCodeSame(204);
    }

    public function testNotFound(): void
    {
        $this->productDeleteRequest(99999);

        $this->assertResponseStatusCodeSame(404);
    }

    private function productDeleteRequest(int $id): Response
    {
        return self::createClient()->request(
            'DELETE',
            '/api/products/' . $id,
            [
                'headers' => [
                    'Accept' => '*/*'
                ]
            ]
        );
    }
}
