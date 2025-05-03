<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $button = $crawler->selectButton('Connexion');
        $form = $button->form([
            '_username' => 'ina',
            '_password' => 'password',
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/', Response::HTTP_FOUND);
        
        $client->followRedirect();
    }

    public function testLogout(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/logout');
        $this->assertResponseRedirects('/', Response::HTTP_FOUND);
        
    }
}
