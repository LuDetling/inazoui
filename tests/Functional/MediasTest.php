<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MediasTest extends WebTestCase
{
    public function testMedias(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/portfolio');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Portfolio');
        
        $album = $crawler->selectLink('Album 1')->link();
        $client->click($album);
        $this->assertResponseIsSuccessful();
    }

    public function testMediaInAlbum():void 
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/portfolio/1');

        $this->assertSelectorExists('img');
    }
}
