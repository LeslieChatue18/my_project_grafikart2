<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController {

    function redirectToAnotherPages(){
       return $this->redirect('https://chatgpt.com/');
    }
     //Les fonctions en symfony doivent toujours retourner une reponse
    //route en tant que attribut -----> #[Route("/accueil" ,"accueil")]
    function index (): Response {
        return new JsonResponse(['Titre'=>"Hello Guys welcome back to my channel" , 'Sous-titre' => 'Vlogs']);

    }

    function index2 (RecipeRepository $recipeRepository ): Response {
        return $this->render('home/index.html.twig',['recipes' => $recipeRepository->findBy([], ['title' => 'ASC'])]);
    }

    //Fonction request
    #[Route("/param","get")]
    function getParam(Request $request){
       //Peu recommandé return new Response('Bonjour Mr'.$_GET['name']);
        return new Response('Bonjour Mr'.$request->query->get('name').' et Madame '.$request->query->get('prenom'));
    }
#[Route("/Redirection", "redirectForRoad")]
    function Redirection(){
        return $this->redirectToRoute("redirect",[]);
    }
}

    
   
