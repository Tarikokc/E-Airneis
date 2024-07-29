<?php

// src/Controller/StripeController.php
namespace App\Controller;

use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\PaymentIntent;

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

    #[Route('/paiement/success', name: 'payment_success')]
    public function paymentSuccess(Request $request): Response
    {
        $paymentIntentId = $request->query->get('payment_intent');

        Stripe::setApiKey('sk_test_51PfKZmKixfMhfPrWA9cn5mAR9ZT7aKLbLWaLnbXVOoA7ol6Rc46CJptzgjaBo2gtKQlUsTQ2jw5ZB8jBn7DRlYsK00T6GkfZca');

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                return new JsonResponse(['success' => true], 200);
            } else {
                return new JsonResponse(['success' => false, 'error' => 'Paiement échoué'], 400); 
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->render('paiement/erreur.html.twig', ['error' => $e->getMessage()]);
        }
    }
}
