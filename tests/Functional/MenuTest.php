<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MenuTest extends WebTestCase
{
    public function testMenu(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $guests = $crawler->selectLink('Invités')->link();
        $medias = $crawler->selectLink('Portfolio')->link();
        $me = $crawler->selectLink('Qui suis-je ?')->link();
        $login = $crawler->selectLink('Connexion')->link();

        $client->click($guests);
        $client->click($medias);
        $client->click($me);
        $client->click($login);

        $this->assertResponseIsSuccessful();
    }
}
