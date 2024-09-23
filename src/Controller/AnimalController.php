<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 
use App\Entity\Animal;
class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'animal_detail')]
    public function showAnimal(int $id, EntityManagerInterface $entityManager): Response
    {
        $animal = $entityManager->getRepository(Animal::class)->find($id);
    
        if (!$animal) {
            throw $this->createNotFoundException('Animal non trouvé');
        }
    
        return $this->render('animals/detail.html.twig', [
            'animal' => $animal,
            'veterinaryReviews' => $animal->getRapportVeterinaire(),
        ]);
    }
    
    
}