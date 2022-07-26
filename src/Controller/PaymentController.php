<?php

    namespace App\Controller;

    use Stripe\Checkout\Session;
    use Stripe\Exception\ApiErrorException;
    use Stripe\Stripe;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

    class PaymentController extends AbstractController
    {

        #[Route('/payment', name: 'payment')]
        public function index(): Response
        {
            return $this->render('payment/index.html.twig', [
                'controller_name' => 'PaymentController',
            ]);
        }


        /**
         * @throws ApiErrorException
         */
        #[Route('/checkout', name: 'checkout')]
        public function checkout($stripeSK): Response
        {
            Stripe::setApiKey($stripeSK);

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [                    [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount'  => 2000,
                    ],
                    'quantity'   => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->redirect($checkout_session->url, 303);
        }


        #[Route('/success-url', name: 'success_url')]
        public function successUrl(): Response
        {
            return $this->render('payment/index.html.twig', []);
        }


        #[Route('/cancel-url', name: 'cancel_url')]
        public function cancelUrl(): Response
        {
            return $this->render('payment/index.html.twig', []);
        }
    }
