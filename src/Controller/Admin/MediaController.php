<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    public function __construct(private MediaRepository $mediaRepository, private EntityManagerInterface $entityManager)
    {
        
    }
    #[Route("/admin/media", name: "admin_media_index")]
    public function index(Request $request)
    {
        $page = $request->query->getInt('page', 1);

        $criteria = [];

        if (!$this->isGranted('ROLE_ADMIN')) {
            $criteria['user'] = $this->getUser();
        }

        $medias = $this->mediaRepository->findBy(
            $criteria,
            ['id' => 'ASC'],
            25,
            25 * ($page - 1)
        );
        $total = $this->mediaRepository->count($criteria);

        return $this->render('admin/media/index.html.twig', [
            'medias' => $medias,
            'total' => $total,
            'page' => $page
        ]);
    }

    #[Route("/admin/media/add", name: "admin_media_add")]
    public function add(Request $request)
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $media->setUser($this->getUser());
            }
            $media->setPath('uploads/' . md5(uniqid()) . '.' . $media->getFile()->guessExtension());
            $media->getFile()->move('uploads/', $media->getPath());
            $this->entityManager->persist($media);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_media_index');
        }

        return $this->render('admin/media/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route("/admin/media/delete/{id}", name: "admin_media_delete")]
    public function delete(int $id)
    {
        $media = $this->mediaRepository->find($id);
        $this->entityManager->remove($media);
        $this->entityManager->flush();
        unlink($media->getPath());

        return $this->redirectToRoute('admin_media_index');
    }

    #[Route("/admin/media/update/{id}", name: "admin_media_update")]
    public function update(Request $request, int $id)
    {
        $media = $this->mediaRepository->find($id);
        $form = $this->createForm(MediaType::class, $media, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $media->setUser($this->getUser());
            }
            if ($media->getFile()) {
                unlink($media->getPath());
                $media->setPath('uploads/' . md5(uniqid()) . '.' . $media->getFile()->guessExtension());
                $media->getFile()->move('uploads/', $media->getPath());
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_media_index');
        }

        return $this->render('admin/media/update.html.twig', ['form' => $form->createView()]);
    }
}