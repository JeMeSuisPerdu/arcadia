<?php
namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis/new', name: 'avis_new')]
    public function newAvis(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisFormType::class, $avis);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setVisible($avis->isVisible() ?? false); // Définit la visibilité
            $entityManager->persist($avis);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_home'); // Redirige après la soumission
        }
        
        return $this->render('avis/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
