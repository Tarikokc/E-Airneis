<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountSettingsController extends AbstractController
{
    #[Route('/api/account/{userId}', name: 'api_get_account', methods: ['GET'])]
    public function getAccount(int $userId, EntityManagerInterface $entityManager): Response
    {
        /**
         * @var User $user
         */
        $user = $entityManager->getRepository(User::class)->find($userId);


        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $userData = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'address' => $user->getAddress(),
            'city' => $user->getCity(),
            'country' => $user->getCountry(),
            'phoneNumber' => $user->getPhoneNumber(),
            'paymentMethod' => $user->getPaymentMethod(),

        ];

        return $this->json($userData);
    }

    #[Route('/api/account/{userId}', name: 'api_update_account', methods: ['PUT'])]
    public function updateAccount(int $userId, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        /**
         * @var User $user
         */
        $user = $entityManager->getRepository(User::class)->find($userId);
        $data = json_decode($request->getContent(), true);

        // Validations manuelles
        $errors = [];
        // Exemple de validation pour le prénom (ajoutez d'autres validations selon vos besoins)
        if (empty($data['firstName']) || strlen($data['firstName']) < 2 || strlen($data['firstName']) > 50) {
            $errors['firstName'] = 'Le prénom doit contenir entre 2 et 50 caractères.';
        }

        // Traitement du mot de passe (si présent dans les données)
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
            } else {
                $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
                $user->setPassword($hashedPassword);
            }
        }

        if (!empty($errors)) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // Mise à jour de l'entité User
        $user->setFirstName($data['firstName'] ?? $user->getFirstName());
        $user->setLastName($data['lastName'] ?? $user->getLastName());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setAddress($data['address'] ?? $user->getAddress());
        $user->setCity($data['city'] ?? $user->getCity());
        $user->setCountry($data['country'] ?? $user->getCountry());
        $user->setPhoneNumber($data['phoneNumber'] ?? $user->getPhoneNumber());
        $user->setPaymentMethod($data['paymentMethod'] ?? $user->getPaymentMethod());


        $entityManager->flush();

        return $this->json(['message' => 'Informations mises à jour avec succès !']);
    }

    #[Route('/api/account/{userId}/{field}', name: 'api_delete_account_field', methods: ['DELETE'])]
    public function deleteAccountField(int $userId, string $field, EntityManagerInterface $entityManager): Response
    {
        /**
         * @var User $user
         */
        $user = $entityManager->getRepository(User::class)->find($userId);
    
        $allowedFields = ['address', 'city', 'phoneNumber']; // Champs autorisés
        if (!in_array($field, $allowedFields)) {
            return $this->json(['error' => 'Champ non autorisé à la suppression'], Response::HTTP_BAD_REQUEST);
        }
    
        // Mise à null du champ correspondant
        switch ($field) {
            case 'address':
                $user->setAddress(null);
                break;
            case 'city':
                $user->setCity(null);
                break;
            case 'phoneNumber':
                $user->setPhoneNumber(null);
                break;
        }
    
        $entityManager->flush();
    
        return $this->json(['message' => 'Champ supprimé avec succès !']);
    }
    
}
