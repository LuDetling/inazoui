<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GuestsTest extends WebTestCase
{
    public function testGuests(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/guests');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.guest');
        $this->assertSelectorTextContains('h3', 'Invités');

        $discover = $crawler->selectLink('découvrir')->link();
        $next = $crawler->selectLink('Suivant')->link();

        $client->click($discover);
        $client->click($next);
    }

    public function testPaginationWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/guests?page=2');

        $previous = $crawler->selectLink('Précédent')->link();
        $client->click($previous);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.pagination');
    }

    public function testInviteDetailPage(): void
    {
        $client = static::createClient();
        // Suppose que tu as un invité avec l’ID 1
        $crawler = $client->request('GET', '/guest/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h3'); // par exemple le nom
        $this->assertSelectorExists('img');
    }

    public function testInviteNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/guest/999999');

        $this->assertResponseStatusCodeSame(500);
    }
}
