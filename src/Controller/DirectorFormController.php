<?php

namespace App\Controller;

use App\Entity\Director;
use App\Form\DirectorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirectorFormController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        
    }

    #[Route('/director/form', name: 'app_director_form')]
    public function addUpdate(Request $request): Response
    {
        $directorEntity = new Director();
        $form = $this->createForm(DirectorType::class, $directorEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($directorEntity);
            $this->entityManager->persist($directorEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_director_form');
        }

        return $this->render('formAdd/directorForm/form.html.twig', [
            'controller_name' => 'DirectorFormController',
            'form' => $form->createView()
        ]);
    }
}
