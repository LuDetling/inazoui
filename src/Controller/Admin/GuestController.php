<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\GuestType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class GuestController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    )
    {
        // Constructor logic if needed
    }
    #[Route('/admin/guests', name: 'admin_guest_index')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $total =$this->userRepository->count(['admin' => false]);

        $guests = $this->userRepository->findBy(
            ['admin' => false],
            ['id' => 'ASC'],
            10,
            10 * ($page - 1)
        );

        return $this->render('admin/guest/index.html.twig', [
            'guests' => $guests,
            'total' => $total,
            'page' => $page
        ]);
    }

    #[Route('/admin/guest/delete/{id}', name: 'admin_guest_delete')]
    public function deleteGuest(int $id): RedirectResponse
    {
        $user = $this->userRepository->find($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_guest_index');
    }

    #[Route('/admin/guest/add', name: 'admin_guest_add')]
    public function addGuest(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(GuestType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $user->setAdmin(false);
            $user->setPassword(
                password_hash($form->get('password')->getData(), PASSWORD_BCRYPT)
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_guest_index');
        }
        return $this->render('admin/guest/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin/guest/update/{id}', name: 'admin_guest_update')]
    public function updateGuest(Request $request, int $id): Response
    {
        $user = $this->userRepository->find($id);
        $roles = $user->getRoles();
        $blocked = false;
        if (in_array('ROLE_BLOCKED', $roles)) {
            $blocked = true;
        }
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $form = $this->createForm(GuestType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData()) {
                $user->setPassword(
                    password_hash($form->get('password')->getData(), PASSWORD_BCRYPT)
                );
            }
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_guest_index');
        }
        return $this->render('admin/guest/update.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'blocked' => $blocked,
            'roles' => $roles,
        ]);
    }
}
