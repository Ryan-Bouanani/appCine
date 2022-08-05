<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserFormController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        
    }

    #[Route('/user/form', name: 'app_user_form')]
    public function addUpdate(Request $request): Response
    {
        $userEntity = new User();

        $form = $this->createForm(UserType::class, $userEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($userEntity);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_user_form');
        }


        return $this->render('formAdd/userForm/form.html.twig', [
            'controller_name' => 'UserFormController',
            'form' => $form->createView()
        ]);
    }
}
