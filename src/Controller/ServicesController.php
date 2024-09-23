<?php

namespace App\Controller;
use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\throwException;

class ServicesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/services', name: 'app_services_list')]
    public function showServices(): Response
    {
        $services = $this->entityManager->getRepository(Service::class)->findAll();

        if (empty($services)) {
            throw new \Exception("Aucun service trouvé.");
        }        
        
        return $this->render('services/index.html.twig', [
            'services' => $services,
        ]);
    }
}
