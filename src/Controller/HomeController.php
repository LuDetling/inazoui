<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(private AlbumRepository $albumRepository, private UserRepository $userRepository, private MediaRepository $mediaRepository)
    {
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('front/home.html.twig');
    }

    #[Route('/guests', name: 'guests')]
    public function guests(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $total =$this->userRepository->count(['admin' => false]);
        $guests = $this->userRepository->findBy(
            ['admin' => false],
            ['id' => 'DESC'],
            limit: 10,
            offset: 10 * ($page - 1)
        );

        return $this->render('front/guests.html.twig', [
            'guests' => $guests,
            'total' => $total,
            'page' => $page,
        ]);
    }

    #[Route('/guest/{id}', name: 'guest')]
    public function guest(int $id): Response
    {
        $guest = $this->userRepository->find($id);
        return $this->render('front/guest.html.twig', [
            'guest' => $guest
        ]);
    }

    /**
     * @Route("/portfolio/{id}", name="portfolio")
     */
    #[Route("/portfolio/{id}", name: "portfolio")]
    public function portfolio(?int $id = null): Response
    {
        $albums = $this->albumRepository->findAll();
        $album = $id ? $this->albumRepository->find($id) : null;
        $user = $this->userRepository->findOneBy(['admin' => true]);

        $isActive = $this->userRepository->findBy(['isActive' => true]);

        $medias = $album
            ? $this->mediaRepository->findBy([
                'album' => $album,
                'user' => $isActive
            ])
            : $this->mediaRepository->findBy(['user' => $user]);
        return $this->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('front/about.html.twig');
    }
}