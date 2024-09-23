<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ImageController extends AbstractController
{
    private $entityManager; 

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_EMPLOYE') && !$this->isGranted('ROLE_VETERINAIRE')) {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }
        $file = $request->files->get('image');
        
        if ($file) {
            // Gérer le stockage de l'image dans un répertoire et obtenir l'URL
            $filename = uniqid() . '.' . $file->guessExtension(); // Générer un nom unique pour l'image
            $file->move($this->getParameter('images_directory'), $filename); // Assurez-vous que le paramètre est défini dans services.yaml
    
            // Créer une nouvelle instance de l'entité Image
            $image = new Image();
            $image->setImageData('/images/' . $filename); // Définir l'URL de l'image
    
            // Persist the image entity
            $this->entityManager->persist($image);
            $this->entityManager->flush(); 
            
            return new Response('Image uploaded successfully!', Response::HTTP_CREATED);
        }
    
        return new Response('No image uploaded.', Response::HTTP_BAD_REQUEST);
    }

    #[Route('/upload', name: 'upload_form')]
    public function uploadImageForm(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_EMPLOYE') && !$this->isGranted('ROLE_VETERINAIRE')) {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }
        return $this->render('image/post.html.twig', [

        ]);    
    }

    #[Route('/upload-animal', name: 'upload_image_animal', methods: ['POST'])]
    public function uploadImageAnimal(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_EMPLOYE') && !$this->isGranted('ROLE_VETERINAIRE')) {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }
        $file = $request->files->get('image');
        
        if ($file) {
            // Gérer le stockage de l'image dans un répertoire et obtenir l'URL
            $filename = uniqid() . '.' . $file->guessExtension(); // Générer un nom unique pour l'image
            $file->move($this->getParameter('img_animal_directory'), $filename); // Assurez-vous que le paramètre est défini dans services.yaml
    
            // Créer une nouvelle instance de l'entité Image
            $image = new Image();
            $image->setImageData('/img_animal/' . $filename); // Définir l'URL de l'image
    
            // Persist the image entity
            $this->entityManager->persist($image);
            $this->entityManager->flush(); 
            
            return new Response('Image uploaded successfully!', Response::HTTP_CREATED);
        }
    
        return new Response('No image uploaded.', Response::HTTP_BAD_REQUEST);
    }


    #[Route('/uploading', name: 'upload_form_animal')]
    public function uploadImgAnimal(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_EMPLOYE') && !$this->isGranted('ROLE_VETERINAIRE')) {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette ressource.');
        }
        return $this->render('image/post.html.twig', [
        ]);    
    }

}
