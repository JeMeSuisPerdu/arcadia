<?php 
namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Créer et envoyer l'e-mail
            $email = (new Email())
                ->from('arcadia.proinfo@gmail.com') // Ton adresse e-mail
                ->to('arcadia.proinfo@gmail.com') // Adresse de réception
                ->subject($data['title'])
                ->text($data['description'] . "\n\nContact: " . $data['email']);

            $mailer->send($email);

            // Ajouter un message flash et rediriger
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
