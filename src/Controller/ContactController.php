<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Validator\Validator\ValidatorInterface;
// use Symfony\Component\Validator\Constraints\NotBlank;
// use Symfony\Component\Validator\Constraints\Email;


class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'app_contact_submit', methods: ['POST'])]
    public function submit(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = [];

        // Validation du nom
        if (empty($data['name'])) {
            $errors['name'] = "Le nom est obligatoire.";
        }

        // Validation de l'email
        if (empty($data['email'])) {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'adresse email n'est pas valide.";
        }

        // Validation du sujet
        if (empty($data['subject'])) {
            $errors['subject'] = "Le sujet est obligatoire.";
        }

        // Validation du message
        if (empty($data['message'])) {
            $errors['message'] = "Le message est obligatoire.";
        }

        if (!empty($errors)) {
            return $this->json(['error' => $errors], 422);
        }

        $message = new Message();
        $message->setName($data['name']);
        $message->setEmail($data['email']);
        $message->setSubject($data['subject']);
        $message->setMessage($data['message']);
        $message->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
