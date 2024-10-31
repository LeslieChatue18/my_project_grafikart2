<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Attribute;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Constraint\Count;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeRecipeController extends AbstractController
{
    #[Route('/recipe/{nom_recette}-{prix_recette}', name: 'app_home_recipe', requirements:['prix_recette'=> '\d+' ,'nom_recette' => '[a-z0-9-]+'])]
    public function index(Request $request): Response
    {
        return new Response('Le prix de votre plat : ' .$request->attributes->get('nom_recette'). ' est de '. $request->attributes->get('prix_recette'). ' Euros');
    }




    #[Route('/recette/{nom}-{prix}' , 'recette.show',requirements:['prix'=> '\d+' ,'nom' => '[a-z0-9-]+'])]
    public function recipeDirect(Request $request , string $nom , int $prix ){
    //dd($nom.'---->'.$prix);
    //$nom1=$request->attributes->getString('nom');
    //$prix1=$request->attributes->getInt('prix');
    //return new Response("L'addition pour votre plat : ".$nom1." est ".$prix1);
    return $this->render('home_recipe/index.html.twig' ,['nom' => $nom , 'prix' => $prix , 'demo' => '<strong>hello</strong>' , 'person'=>[
        'lastname'=>'John',
        'firstname' => 'Doe'

    ]]);
    }




    #[Route('/pages' , 'pages.show')]
    public function pages(Request $request , RecipeRepository $recipeRepository){
        return $this->render('home_recipe/pages.html.twig',['recipes' => $recipeRepository->findAll()]);
    }




    #[Route('/recipes' , 'recipes.show')]
    function showRecipe(Request $request , RecipeRepository $recipeRepository){
return $this->render('home_recipe/index.html.twig',['recipes' => $recipeRepository->findAll()]);
    }




    #[Route('/recipes/{id}' , 'recipes_une.show')]
    function showRecipeByOne(Request $request , RecipeRepository $recipeRepository , $id){
        $id=$request->attributes->get('id');
        return $this->render('home_recipe/pages.html.twig',['recette_une' => $recipeRepository->find($id) , 'recipes' => $recipeRepository->findAll()]);
    }



    #[Route('/search' , 'search.show')]
    function searchRecipeByOne(Request $request , RecipeRepository $recipeRepository , EntityManagerInterface $entity){
        $title=$request->query->get('title');
        //Pour ajouter , modifier , supprimer une recette

        /////////////---------------------///////////
        #########-----------------###############
        //Pour Enregistrer les données dans la base j'ai besoin d'un persit puis d'un flush
        //Pour Modifier les données dans la base j'ai besoin d'un flush
        //Pour Supprimer les données dans la base j'ai besoin d'un flush
        //CONDITION DE CREATION
        $re=$recipeRepository->getRecipeBySearch('Ndole manioc');
        if(Count($re) >=1){
dump($re);
        }else{
            dump($re);
        
        //Premierement je crée la recette
        $recipe= new Recipe();
        //J'ajoute une recette en donnant les valeurs à chaque champs
        $recipe->setTitle('Ndole manioc');
        $recipe->setSlug('Ndole');
        $recipe->setContents("Cette recette est une recette Africaine qui a vu le jour au cameroun dans la region de Douala Détachez les feuilles de ndolé des branches. Pendant ce temps, mettez une grosse marmite au feu avec de l’eau et du sel. Incorporez les feuilles dans l’eau chaude pendant 10 minutes. Lorsqu’elles sont cuites, rincez les en les mettant dans une bassine d’eau froide. Égouttez en pressant les feuilles pour en faire des boulettes.  Gouttez et recommencez la même opération jusqu’à ce que l’amertume des feuilles disparaisse.

Mettez les arachides dans une casserole, ajoutez de l’eau et laissez cuire environ 5 minutes. Après ébullition, retirez du feu. Égouttez puis réduisez-les en purée.

Épluchez, lavez les oignons et coupez les finement. Écrasez les 3/4 des oignons avec le gingembre, le piment et la tomate. Nettoyez le poisson fumé et découpez le en petits morceaux.

Faites revenir dans l’huile, la tomate et les ingrédients écrasés pendant 5 minutes.
Ensuite, ajoutez les arachides et mélanger doucement. Portez à ébullition tout en remuant pour éviter que ça colle au fond de la casserole. Mettez ensuite les feuilles de Ndole boule par boule en remuant constamment.

Assaisonnez avec le cube , rajoutez les crevettes et le poisson. Laissez cuire à feu très doux pendant 15 minutes.Tournez de temps en temps.

Dans une poêle, faites chauffer l’huile de palme, faites y revenir le reste d’oignons . Renversez le mélange sur la première préparation en remuant.

Vérifiez l’assaisonnement.

Servez avec de la banane plantain, du bâton de manioc, du riz ou du macabo");
$recipe->setCreatedAt(new \DateTimeImmutable());
$recipe->setUpdatedAt(new \DateTimeImmutable());
$recipe->setDuration(60);
//J'enregistre la recette 
$entity->persist($recipe);
//Flush permet de faire en sorte que les modifications soit enregistrées dans la base de donnée
$entity->flush();
}
////-----------------------//////
#      Modifier une recette     #
$findRecipe=$recipeRepository->getRecipeBySearch("Ndole");
/*foreach($findRecipe as $fr){
    $fr->setTitle('Ndole plantain');
    $entity->flush();
}*/



////-----------------------//////
#      Supprimer une recette     #

/*foreach($findRecipe as $fr){
    $entity->remove($fr);
    $entity->flush();
}
*/


        return $this->render('home_recipe/search.html.twig',['search_recipe' => $recipeRepository->getRecipeBySearch($title) , 'recipes' => $recipeRepository->findAll()]);
    }




#[Route ( '/form/{id}' , 'formRecette')]
    public function EditForm( Recipe $recipe,Request $request , RecipeRepository $recipeRepository , EntityManagerInterface $em , $id){
        $formRecipe= $this->createForm(RecipeType::class , $recipe);   
        $id=$request->attributes->get('id');  
        $formRecipe->handleRequest($request);//Recupere toutes les modifications(nouvelles données insérées) faites au formulaires 
        if($formRecipe->isSubmitted() && $formRecipe->isValid()){
            $em->flush();
            $this->addFlash('success' , 'Votre modification a bien été prise en compte');
            return $this->redirectToRoute('accueil');
        }
        return $this->render('home_recipe/formEdit.html.twig',['recipes' => $recipeRepository->findAll() , 'recette' => $recipe , 'formRecipe' => $formRecipe , 'nom' => $recipeRepository->find($id)]) ;
       }




#[Route ('/ajouter' , 'addRecipe')]
    public function addRecipe(Request $request, RecipeRepository $recipeRepository , EntityManagerInterface $em){
        $recipe= new Recipe();
        $formRecipe=$this->createForm(RecipeType::class,$recipe  );
        $formRecipe->handleRequest($request);
        
    if($formRecipe->isSubmitted() && $formRecipe->isValid()){
        $rec=$request->request->all();
     //   dd(date_create_immutable($rec['recipe']['createdAt']));
        $recipe->setTitle($rec['recipe']['title']);
        $recipe->setSlug($rec['recipe']['slug']);
        $recipe->setContents($rec['recipe']['contents']);
        $recipe->setUpdatedAt(date_create_immutable($rec['recipe']['updatedAt']));
        $recipe->setCreatedAt(date_create_immutable($rec['recipe']['createdAt']));
        $recipe->setDuration($rec['recipe']['duration']);
        //J'enregistre la recette 
        $em->persist($recipe);
        //Flush permet de faire en sorte que les modifications soit enregistrées dans la base de donnée
        $em->flush();
        $this->addFlash("success" ,"Votre recette a bien été enregistrée");
        return $this->redirectToRoute('accueil');
    }
        return $this->render('home_recipe/addRecipe.html.twig',['recipes' => $recipeRepository->findAll() , 'form'=> $formRecipe ]);
    }

#[Route ('/delete/{id}' , 'delete' , methods:['DELETE'])]
    public function removeRecipe(Request $request , EntityManagerInterface $em ,RecipeRepository $recipe){
     $id=$request->attributes->get('id');
     $em->remove($recipe->find($id));
     $em->flush();
     $this->addFlash("success" , "Suppression reussie");
     return $this->redirectToRoute('accueil');
    }
}
