<?php

declare(strict_types=1);

namespace App\Tests\src\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase
{
    public function testControllerUserDocumentPost(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/hello',
        );

        $this->assertResponseIsSuccessful();
    }
}
