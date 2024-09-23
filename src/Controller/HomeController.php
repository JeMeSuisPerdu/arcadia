<?php

namespace App\Controller;

use App\Entity\Avis; // N'oublie pas d'importer l'entité Avis
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager; 

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Utiliser $this->entityManager pour récupérer les avis
        $avis = $this->entityManager->getRepository(Avis::class)->findBy(['isVisible' => true]);

        // Passer les avis au template Twig pour l'affichage
        return $this->render('home/home.html.twig', [
            'avis' => $avis,
        ]);
    }
}
