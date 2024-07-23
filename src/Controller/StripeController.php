<?php

// src/Controller/StripeController.php
namespace App\Controller;

use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/create-payment-intent', name: 'create_payment_intent')]
    public function createPaymentIntent(): JsonResponse
    {
        Stripe::setApiKey('sk_test_51PfKZmKixfMhfPrWA9cn5mAR9ZT7aKLbLWaLnbXVOoA7ol6Rc46CJptzgjaBo2gtKQlUsTQ2jw5ZB8jBn7DRlYsK00T6GkfZca');

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => 1099, // Montant en centimes
                'currency' => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return new JsonResponse(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Gérer les erreurs Stripe ici
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
