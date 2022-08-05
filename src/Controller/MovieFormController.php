<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieFormController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private MovieRepository $movieRepository
    )
    {
    }

    #[Route('/movie/form', name: 'app_movie_form')]
    public function addUpdate(Request $request): Response
    {
        $movieEntity = new Movie();

        // Crée un formulaire
        $form = $this->createForm(MovieType::class, $movieEntity);

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            dump($movieEntity);
            $this->entityManager->persist($movieEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_movie_form');
        }


        return $this->render('formAdd/movieForm/form.html.twig', [
            'controller_name' => 'MovieFormController',
            'form' => $form->createView()
        ]);
    }
    #[Route('/movie/delete/{idMovie}', name: 'app_movie_delete')]
    public function delete($idMovie): Response
    {

        // Crée un formulaire
        $movieDelete = $this->movieRepository->find($idMovie);

        // Mettre en file d'attente ce qu'ont veut DELETE
        $this->entityManager->remove($movieDelete);

        // Execute toute la file d'attente
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
