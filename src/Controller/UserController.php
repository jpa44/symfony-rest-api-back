<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private Security $security,
        private SerializerInterface $serializer,
        private UserPasswordEncoderInterface $passwordEncoder
    ) {}

    #[Route('/api/user', name: 'get_user', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser) {
            // the user must be logged in; if not, deny access
            return $this->json([
                'message' => 'You must be logged in to access this page.',
            ], 401);
        }

        $user = $this->serializer->serialize($currentUser, 'json');

        return $this->json($user);
    }

    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $users = $entityManager->getRepository(User::class)->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'roles' => $user->getRoles(),
                'email' => $user->getEmail(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/user', name: 'post_user', methods: ['POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->request->get('password')));
        $user->setRoles($request->request->get('roles'));
        $user->setFirstName($request->request->get('firstName'));

        $entityManager->persist($user);

        $entityManager->flush();

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'email' => $user->getEmail()
        ];

        return $this->json($data);
    }

    #[Route('/api/user/{id}', name: 'show_user', methods: ['GET'])]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $user = $entityManager
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            return $this->json('No user found for id' . $id, 404);
        }

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ];

        return $this->json($data);
    }

    #[Route('/api/user/{id}', name: 'edit_user', methods: ['PATCH'])]
    public function edit(Request $request, int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json('No user found for id' . $id, 404);
        }

        $user->setRoles(['ROLE_USER']);
        $user->setFirstName($request->request->get('firstName'));
        $entityManager->flush();

        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'email' => $user->getEmail(),
        ];

        return $this->json($data);
    }

    #[Route('/api/user/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function delete(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json('No user found for id' . $id, 404);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json('Deleted a user successfully with id ' . $id);
    }
}
