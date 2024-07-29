<?php

namespace App\Controller;

use App\Entity\User; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Psr\Log\LoggerInterface;
use OpenApi\Annotations as OA;

class LoginController extends AbstractController
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion utilisateur",
     *     description="Authentifie un utilisateur avec son adresse e-mail et son mot de passe. Retourne un token d'authentification en cas de succès.",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Identifiants de l'utilisateur",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),   

     *             @OA\Property(property="password", type="string", format="password", example="motdepasse")   

     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="Token d'authentification"),
     *             @OA\Property(property="user", type="object", ref=@Model(type=User::class, groups={"user:read"}))
     *         )
     *     ),
     *     @OA\Response(response=400, description="Données manquantes ou invalides"),
     *     @OA\Response(response=401, description="Identifiants invalides ou utilisateur non trouvé")
     * )
     */
    #[Route('/api/login', name: 'app_login', methods: ['POST'])] 
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        UserPasswordHasherInterface $passwordHasher,
        UserProviderInterface $userProvider, // Type-hint ajouté
        LoggerInterface $logger,
        EntityManagerInterface $entityManager 

    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $email = strtolower($data['email'] ?? '');
        
        $password = $data['password'] ?? '';

        if (!$email || !$password) { 
            $logger->error('Email or password missing');
            return new JsonResponse(['message' => 'Email or password missing'], 400);
        }

        $user = $userProvider->loadUserByIdentifier($email);

        if (!$user instanceof User) {
            $logger->error('User not found');
            return new JsonResponse(['message' => 'User not found'], 401);
        }

        if (!$passwordHasher->isPasswordValid($user, $password)) {
            $logger->error('Invalid credentials');
            return new JsonResponse(['message' => 'Invalid credentials'], 401); 
        }

        /// Génération d'un token aléatoire
        $token = bin2hex(random_bytes(30)); 
        $user->setToken($token); 
        $entityManager->flush(); 
        $logger->info('User logged in successfully', ['user' => $user->getUserIdentifier()]);
        

        return new JsonResponse([
            'token' => $token,
            'user' => [
                'id' => $user->getUserId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles()[0]
            ]
        ]);
    }
}
