<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ChampionTest extends WebTestCase
{
    public function testCreatedByAdmin(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('testAdmin@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('POST', '/admin/champion/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Fury',
            'pvmax' => '500',
            'powermax' => '45'
        ]));

        $this->assertJsonStringEqualsJsonString('{"message":"Champion created"}', $client->getResponse()->getContent());
    }

    public function testCreateBydUser(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('testUser@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('POST', '/admin/champion/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Fury',
            'pvmax' => '500',
            'powermax' => '45'
        ]));

        $this->assertResponseStatusCodeSame(403);
    }

    public function testInvalideRequest(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('testAdmin@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('POST', '/admin/champion/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Fury',
            'pvmax' => '500',
            'powermax' => ''
        ]));

        $this->assertResponseStatusCodeSame(400);
    }
}
