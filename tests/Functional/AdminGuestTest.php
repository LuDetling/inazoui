<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminGuestTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/admin/guests');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('table');
    }

    public function testAddGuest(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/guest/add');

        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form([
            'guest[email]' => 'lucas.detling@gmail.com',
            'guest[password]' => 'password',
            'guest[name]' => 'New Guest',
            'guest[description]' => 'New Description',
            'guest[isActive]' => true,
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/admin/guests', 302);
    }

    public function testUpdateGuest(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/guest/update/1');

        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Modifier');
        $form = $button->form([
            'guest[name]' => 'Updated Guest',
            'guest[description]' => 'Updated Description',
            'guest[isActive]' => false,
            'guest[password]' => 'newpassword',
            'guest[email]' => 'boby@gmail.com',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/admin/guests', 302);
    }

    public function testDeleteGuest(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['name' => 'ina']);
        $client->loginUser($user);

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        // Créer un utilisateur fictif
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('Test User');
        $user->setDescription('Test Description');
        $user->setIsActive(true);
        $user->setPassword('password'); // pas besoin d’encoder ici
        $em->persist($user);
        $em->flush();

        $userId = $user->getId();

        // Appeler la route de suppression
        $client->request('GET', '/admin/guest/delete/' . $userId);

        // Vérifier la redirection
        $this->assertResponseRedirects('/admin/guests', 302);

        // Vérifier que l’utilisateur n’existe plus
        $deletedUser = $em->getRepository(User::class)->find($userId);
        $this->assertNull($deletedUser);
    }
}
