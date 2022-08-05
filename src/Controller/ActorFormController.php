<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorFormController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private ActorRepository $actorRepository
    )
    {
        
    }
    
    #[Route('/actor/form/{idActor}', name: 'app_actor_form')]
    public function addUpdate($idActor = null, Request $request): Response
    {   
        
        $actorEntity = new Actor();
        if ($idActor !== null) {
            $actorEntity = $this->actorRepository->find($idActor);
        }
        dump($actorEntity);
        $form = $this->createForm(ActorType::class, $actorEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($actorEntity);
            $this->entityManagerInterface->persist($actorEntity);
            $this->entityManagerInterface->flush();
            return $this->redirectToRoute('app_actor_form');
        }

        return $this->render('formAdd/actorForm/form.html.twig', [
            'controller_name' => 'ActorController',
            'form' => $form->createView()
        ]);
    }
}
