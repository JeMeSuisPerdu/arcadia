<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 
use App\Entity\Habitat;
use App\Entity\Image;

class HabitatController extends AbstractController
{
    #[Route('/habitats', name: 'habitats_list')]
public function listHabitats(EntityManagerInterface $entityManager): Response
{
    $habitats = $entityManager->getRepository(Habitat::class)->findAll();
    
    return $this->render('habitats/list.html.twig', [
        'habitats' => $habitats,
    ]);
}
#[Route('/habitat/{id}', name: 'habitat_detail')]
public function showHabitat(int $id, EntityManagerInterface $entityManager): Response
{
    $habitat = $entityManager->getRepository(Habitat::class)->find($id);

    if (!$habitat) {
        throw $this->createNotFoundException('Habitat non trouvé');
    }

    return $this->render('habitats/detail.html.twig', [
        'habitat' => $habitat,
        'animals' => $habitat->getAnimals(),
    ]);
}
#[Route('/image/{id}', name: 'image_show')]
public function showImage(int $id, EntityManagerInterface $entityManager): Response
{
    $image = $entityManager->getRepository(Image::class)->find($id);

    if (!$image) {
        throw $this->createNotFoundException('Image non trouvée');
    }

    return $this->redirect($image->getImageData()); // Redirige vers l'URL de l'image
}



}
