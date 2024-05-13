<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipe;
use App\Form\RecipeFormType;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/recipe/create', name: 'create_recipe')]
    public function create(Request $request): Response 
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $newRecipe = $form->getData();
          $imagePath = $form->get('imagePath')->getData();
          if ($imagePath) {
            $newFileName = uniqid() . '.' . $imagePath->guessExtension();
            try {
                $imagePath->move(
                $this->getParameter('kernel.project_dir') . '/public/uploads', $newFileName
                );
            } catch (FileException $e) {
            return new Response($e->getMessage());
            }
            $newRecipe->setImagePath('/uploads/' . $newFileName);
          }

          $this->em->persist($newRecipe);
          $this->em->flush();

          return $this->redirectToRoute('app_home');
        }
        return $this->render('recipes/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/recipes/{id}', name: 'show_recipe', methods:['GET'])]
    public function show($id): Response
    {
        return $this->render('recipes/details.html.twig');
    }
}
