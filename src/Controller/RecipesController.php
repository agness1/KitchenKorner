<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecipeRepository;

class RecipesController extends AbstractController
{
    private $em;
    private $recipeRepository;

    public function __construct(EntityManagerInterface $em, RecipeRepository $recipeRepository)
    {
        $this->em = $em;
        $this->recipeRepository = $recipeRepository;

    }

    
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $recipes = $this->recipeRepository->findAll();
        return $this->render('home/home.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recipes/{id}', name: 'show_recipe', methods:['GET'])]
    public function show($id): Response
    {
        return $this->render('recipes/details.html.twig');
    }
}
