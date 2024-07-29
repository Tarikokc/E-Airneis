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
    /**
     * @OA\Post(
     *     path="/create-payment-intent",
     *     summary="Créer une intention de paiement Stripe",
     *     description="Crée une intention de paiement Stripe pour un montant de 10,99 €.",
     *     tags={"Paiements"},
     *     @OA\Response(
     *         response=200,
     *         description="Intention de paiement créée avec succès",
     *         @OA\JsonContent(
     *             required={"clientSecret"},
     *             @OA\Property(property="clientSecret", type="string", description="Le secret client pour le Payment Intent")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur lors de la création de l'intention de paiement",
     *         @OA\JsonContent(
     *             required={"error"},
     *             @OA\Property(property="error", type="string", description="Message d'erreur")
     *         )
     *     )
     * )
     */
    #[Route('/create-payment-intent', name: 'create_payment_intent')]
    public function createPaymentIntent(): JsonResponse
    {
        Stripe::setApiKey('sk_test_51PfKZmKixfMhfPrWA9cn5mAR9ZT7aKLbLWaLnbXVOoA7ol6Rc46CJptzgjaBo2gtKQlUsTQ2jw5ZB8jBn7DRlYsK00T6GkfZca');

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => 1099,
                'currency' => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return new JsonResponse(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Gérer les erreurs Stripe ici
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/paiement/success",
     *     summary="Vérifier le succès d'un paiement",
     *     description="Vérifie l'état d'une intention de paiement Stripe à partir de son ID.",
     *     tags={"Paiements"},
     *     @OA\Parameter(
     *         name="payment_intent",
     *         in="query",
     *         description="ID de l'intention de paiement à vérifier",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Le paiement a été effectué avec succès",
     *         @OA\JsonContent(
     *             required={"success"},
     *             @OA\Property(property="success", type="boolean", description="True si le paiement a réussi, false sinon")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Le paiement a échoué",
     *         @OA\JsonContent(
     *             required={"success", "error"},
     *             @OA\Property(property="success", type="boolean", description="False car le paiement a échoué"),
     *             @OA\Property(property="error", type="string", description="Message d'erreur")
     *         )
     *     )
     * )
     */
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
