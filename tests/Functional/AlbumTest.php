<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumTest extends WebTestCase
{
    public function getUser(string $username = 'Invité0'){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => $username]);
        $client = static::createClient();
        $client->loginUser($testUser);
        return $testUser;
    }
    public function testCreateAlbumUser(): void
    {
        $client = static::createClient();

        $user = $this->getUser();

        $crawler = $client->request('GET', '/admin/media/add');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Photographe');
    }
}
