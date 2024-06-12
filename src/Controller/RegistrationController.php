<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException; // Import correct

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            $logger->error('Invalid JSON');
            return new JsonResponse(['message' => 'Invalid JSON'], 400);
        }

        $user = new User();
        $user->setFirstName($data['firstname'] ?? '');
        $user->setLastName($data['lastname'] ?? '');
        $user->setEmail($data['email'] ?? '');
        $user->setPassword($passwordHasher->hashPassword($user, $data['password'] ?? ''));
        $user->setRole($data['role'] ?? false);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $logger->error('Validation errors', ['errors' => (string) $errors]);
            return new JsonResponse(['message' => 'Validation errors', 'errors' => (string) $errors], 400);
        }

        try {
            $token = bin2hex(random_bytes(30));
            $user->setToken($token);

            $em->persist($user);
            $em->flush();
            $logger->info('User registered successfully');
            return new JsonResponse([
                'token' => $token, // Inclure le token dans la réponse
                'user' => [
                    'id' => $user->getUserId(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRoles()[0]
                ],
                'message' => 'User registered successfully'
            ], 201);
        } catch (UniqueConstraintViolationException $e) {
            $logger->error('Email already exists');
            return new JsonResponse(['message' => 'Email already exists'], 400);
        } catch (\Exception $e) {
            $logger->error('An error occurred', ['error' => $e->getMessage()]);
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }
}
