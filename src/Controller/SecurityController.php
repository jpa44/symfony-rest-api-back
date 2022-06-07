<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Repository\UserRepository;
use App\Entity\User;


class SecurityController extends AbstractController
{

    public function __construct(private UserRepository               $userRepository,
                                private Security                     $security,
                                private SerializerInterface          $serializer,
                                private UserPasswordEncoderInterface $passwordEncoder
    )
    {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->security->getUser()->getToken();

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'token' => $token
        ], 200);

    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->request->get('password')));
        $user->setRoles(['ROLE_USER']);
        $user->setFirstName($request->request->get('firstName'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}