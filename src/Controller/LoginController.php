<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LoginController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirection vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/register', name: 'register_form', methods: ['GET'])]
    public function createUserForm(): Response
    {
        return $this->render('register/register.html.twig'); 
    }
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/create-user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur();
        
        // Récupère les données du formulaire
        $utilisateur->setUsername($request->request->get('username'));
        $utilisateur->setNom($request->request->get('nom'));
        $utilisateur->setPrenom($request->request->get('prenom'));
    
        // Récupérer le rôle sélectionné
        $role = $request->request->get('role');
        $utilisateur->setRoles([$role]); // Définit le rôle sélectionné
        
        // Hachage du mot de passe
        $plainPassword = $request->request->get('password');
        $hashedPassword = $passwordHasher->hashPassword($utilisateur, $plainPassword);
        $utilisateur->setPassword($hashedPassword);
        
        // Enregistre l'utilisateur dans la base de données
        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();
        
        return new Response('Utilisateur créé avec succès !');
    }
    
    
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
