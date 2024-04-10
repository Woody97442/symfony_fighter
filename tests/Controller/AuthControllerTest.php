<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', [], [], [], json_encode([
            'email' => 'test@gmail.com',
            'password' => 'password',
            'username' => 'test',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonStringEqualsJsonString('{"message":"User created"}', $client->getResponse()->getContent());
    }

    public function testLogin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/login_check', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'testAdmin',
            'password' => 'password'
        ]));

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('token', $responseData);
    }
}
