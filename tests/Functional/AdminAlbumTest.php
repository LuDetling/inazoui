<?php

namespace App\Tests\Functional;

use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminAlbumTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/admin/album');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('table');
    }

    public function testAddAlbum(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/admin/album/add');

        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form([
            'album[name]' => 'New Album',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/admin/album', 302);
    }

    public function testUpdateAlbum(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/admin/album/update/1');

        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Modifier');
        $form = $button->form([
            'album[name]' => 'Updated Album',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/admin/album', 302);
    }

    // public function testDeleteAlbum(): void
    // {
    //     $client = static::createClient();
    //     $userRepository = $client->getContainer()->get(UserRepository::class);
    //     $user = $userRepository->findOneBy(['name' => 'ina']);
    //     $client->loginUser($user);

    //     $albumRepository = $client->getContainer()->get(AlbumRepository::class);
    //     $album = $albumRepository->find(1);
    //     $albumId = $album->getId();

    //     $crawler = $client->request('GET', '/admin/album/delete/'.$albumId);

    //     $this->assertResponseRedirects('/admin/album', 302);}
        
}
