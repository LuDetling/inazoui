<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminMediasTest extends WebTestCase
{
    public function testAdminMedia(): void
    {

        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/media');

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateMedia(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/media/update/1');

        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Modifier');
        $form = $button->form([
            'media[title]' => 'New Title',
            'media[user]' => '1',
            'media[album]' => '2',
            'media[file]' => 'path/to/new/file.jpg',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/admin/media', 302);
    }

    public function testAddMedia(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/media/add');

        $this->assertResponseIsSuccessful();

        $uploadedFile = new UploadedFile(
            __DIR__.'/../fixtures/0001.jpg', // Chemin vers le fichier de test
            'file.jpg',
            'image/jpeg',
            null,
            true // Simule un fichier uploadé
        );

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form([
            'media[title]' => 'New Media',
            'media[user]' => '1',
            'media[album]' => '2',
            'media[file]' => $uploadedFile, // Simule un fichier uploadé
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/media', 302);

    }
}
