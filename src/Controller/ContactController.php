<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(MailerInterface $mailer): Response
    {
        if(isset($_POST["email"])){
            $email = (new Email())
            ->from('team@gmail.com')
            ->to($_POST["email"])
            ->subject($_POST["subject"])
            ->text('Sending emails is fun again!')
            ->html($_POST["message"]);

            $mailer->send($email);
        }
        
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
