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

class LoginController extends AbstractController
{
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
        // $password = $data['password'] ?? null; // Récupérer le mot de passe avec un opérateur de coalescence nulle
        
        $password = $data['password'] ?? '';

        if (!$email || !$password) { // Vérifier si l'email ou le mot de passe sont vides
            $logger->error('Email or password missing');
            return new JsonResponse(['message' => 'Email or password missing'], 400);
        }

        $user = $userProvider->loadUserByIdentifier($email);

        // Vérifier si l'utilisateur est bien une instance de User
        if (!$user instanceof User) {
            $logger->error('User not found');
            return new JsonResponse(['message' => 'User not found'], 401);
        }

        if (!$passwordHasher->isPasswordValid($user, $password)) {
            $logger->error('Invalid credentials');
            return new JsonResponse(['message' => 'Invalid credentials'], 401); // Unauthorized
        }

        /// Génération d'un token aléatoire
        $token = bin2hex(random_bytes(30)); // 60 caractères hexadécimaux 
        $user->setToken($token); // Stocker le token dans l'entité User
        $entityManager->flush(); // Enregistrer les modifications dans la base de données

        $logger->info('User logged in successfully', ['user' => $user->getUserIdentifier()]); // Utilisation de getUserIdentifier()
        

        return new JsonResponse([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles()[0]
            ]
        ]);
    }
}
