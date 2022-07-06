<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Stripe\StripeClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository): Response
    {
        $cart = $session->get('cart', []);

        if(!empty($cart)){

            $stripe = new StripeClient($_ENV['STRIPE_SK']);
            
            $items = [];

            foreach($cart as $id => $quantity) {
                $product = $productsRepository->find($id);

                $items[] = [
                    'amount' => $product->getPrice() * 100,
                    'quantity' => $quantity,
                    'currency' => 'eur',
                    'name' => $product->getName()
                ];
            }

            $protocol = $_SERVER['HTTPS'] ? 'https' : 'http';
            $host = $_SERVER['SERVER_NAME'];
            $successUrl = $protocol . '://' . $host . '/checkout/success/{CHECKOUT_SESSION_ID}';
            $failureUrl = $protocol . '://' . $host . '/checkout/failure/{CHECKOUT_SESSION_ID}';

            $session = $stripe->checkout->sessions->create([
                'success_url' => $successUrl,
                'cancel_url' => $failureUrl,
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => $items
            ]);

            $sessionId = $session->id;

        }else{
            return $this->redirectToRoute('app_cart_index');
        }

        

        return $this->render('checkout/index.html.twig', [
            'sessionId' => $sessionId
        ]);
    }

    #[Route('/checkout/success/{stripeSessionId}', name: 'app_checkout_success')]
    public function success(string $stripeSessionId, SessionInterface $session, MailerInterface $mailer, ProductsRepository $productsRepository): Response
    {
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $productsRepository->find($id),
                'quantity' => $quantity,
            ];
        }

        $email = (new TemplatedEmail())
            ->from('team@gmail.com')
            ->to('client@gmail.com')
            ->subject('Thank you !')
            ->htmlTemplate('emails/checkoutSuccess.html.twig')
            ->context([
                'cart' => $cartWithData
            ])
        ;

        $mailer->send($email);
        
        $session->set('cart', []);

        return $this->render('checkout/success.html.twig', []);
    }

    #[Route('/checkout/failure/{stripeSessionId}', name: 'app_checkout_failure')]
    public function failure(string $stripeSessionId): Response
    {
        return $this->render('checkout/failure.html.twig', []);
    }
}

