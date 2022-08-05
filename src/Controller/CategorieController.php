<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager 
    )
    {}

    #[Route('/category/form', name: 'app_categorie_form')]
    public function addUpdate(Request $request): Response
    {
        $categorieEntity = new Category();
        $form = $this->createForm(CategorieType::class, $categorieEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($categorieEntity);
            $this->entityManager->persist($categorieEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_categorie_form');
        }


        return $this->render('formAdd/categorieForm/form.html.twig', [
            'controller_name' => 'CategorieController',
            'form' => $form->createView()
        ]);
    }
}
