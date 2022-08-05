<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ActorRepository;
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private MovieRepository $movieRepository,
        private EntityManagerInterface $entityManager, 
        private ActorRepository $actorRepository,
        private DirectorRepository $directorRepository,
        private CategoryRepository $categoryRepository,
        private UserRepository $userRepository,
        private ReviewRepository $reviewRepository
    )
    {
        
    }

    #[Route('/home', name: 'app_home')]
    public function listMovies(): Response
    {
        // rend soit le user connecte soit null
        $user = $this->getUser();
        dump($user);

        // si user non connecter
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }
        // check role user
        if ($this->isGranted('ROLE_ADMIN')) {
            
        } 

        // la commentaire prend comme utilisateur le compte avec lequelle il est connecté
        $review = new Review;
        $review->setUser($user);

        $allMovies = $this->movieRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'allMovies' => $allMovies
        ]);
    }


    #[Route('/detail/{id}/{idReview}', name: 'app_detail')]
    public function detailMovie($id, $idReview = null, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $reviews = $movie->getReviews();
        $nbReviews = count($reviews);
        foreach ($reviews as $review) {
            $moyenneNotes = number_format($review->getNote() / $nbReviews, 2);
        }


        // * review
          // oncree une nouvelle review
          $reviewEntity = new Review();
          $reviewEntity->setMovie($movie);
          $reviewEntity->setCreatedAt(new DateTime());
            // rend soit le user connecte soit null
            $user = $this->getUser();
            $reviewEntity->setUser($user);
            dump($reviewEntity);
          // si id fournit alors on update la review relié à l'id
          if ($idReview !== null) {
              $reviewEntity = $this->reviewRepository->find($idReview);
          }
          dump($idReview);
          // on l'ajoute à notre formulaire
          $form = $this->createForm(ReviewType::class, $reviewEntity);
  
          // on ecoute le formulaire
          $form->handleRequest($request);
  
          // Et on push notre review une fois le form valider
          if ($form->isSubmitted() && $form->isValid()) {
              $this->entityManager->persist($reviewEntity);
              $this->entityManager->flush();
              return $this->redirectToRoute('app_review_form');
          }
  


        return $this->render('detailMovie/detail.html.twig', [
            'controller_name' => 'HomeController',
            'movie' => $movie,
            'moyenneNotes' => $moyenneNotes,
              // et on envoie le form dansla template
              'form' => $form->createView()
        ]);
    }

    #[Route('/detailActor/{idActor}', name: 'app_detailActor')]
    public function detailActor($idActor): Response
    {
        $actor = $this->actorRepository->find($idActor);

        return $this->render('detailActor/actor.html.twig', [
            'controller_name' => 'HomeController',
            'actor' => $actor
        ]);
    }
    #[Route('/detailDirector/{idDirector}', name: 'app_detailDirector')]
    public function detailDirector($idDirector): Response
    {
        $director = $this->directorRepository->find($idDirector);

        return $this->render('detailDirector/director.html.twig', [
            'controller_name' => 'HomeController',
            'director' => $director
        ]);
    }
    #[Route('/detailCategorie/{idCategorie}', name: 'app_detailCategorie')]
    public function detailCategorie($idCategorie): Response
    {
        $categorie = $this->categoryRepository->find($idCategorie);

        return $this->render('detailCategorie/categorie.html.twig', [
            'controller_name' => 'HomeController',
            'categorie' => $categorie
        ]);
    }

    #[Route('/detailUser/{idUser}', name: 'app_detailUser')]
    public function detailUser($idUser): Response
    {
        $user = $this->userRepository->find($idUser);

        return $this->render('detailUser/userDetail.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user
        ]);
    }
}
