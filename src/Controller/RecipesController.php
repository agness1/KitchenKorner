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
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\SecurityBundle\Security;

class RecipesController extends AbstractController
{
    private $em;
    private $recipeRepository;
    private $categoryRepository;
    private $security;

    public function __construct(EntityManagerInterface $em, RecipeRepository $recipeRepository, CategoryRepository $categoryRepository, Security $security)
    {
        $this->em = $em;
        $this->recipeRepository = $recipeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->security = $security->getUser();
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $recipes = $this->recipeRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        return $this->render('home/home.html.twig', [
            'recipes' => $recipes,
            'categories' => $categories
        ]);
    }

    #[Route('/recipe/create', name: 'create_recipe')]
    public function create(Request $request): Response
    {
        $user = $this->security;

        if (!$user) {
            return $this->redirect('/login');
        }
        $recipe = new Recipe();
        $user = $this->getUser();
        $recipe->setUser($user);

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newRecipe = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
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

    #[Route('/recipes/{id}', name: 'show_recipe', methods: ['GET'])]
    public function show($id): Response
    {
        $recipes = $this->recipeRepository->find($id);

        return $this->render('recipes/details.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/user-recipes/{userId}', name: 'show_user_recipe', methods: ['GET'])]
    public function showUserRecipe($userId): Response
    {
        $user = $this->security;

        if (!$user) {
            return $this->redirect('/login');
        }
        $recipes = $this->recipeRepository->findBy(['user' => $userId]);

        return $this->render('recipes/show.html.twig',  [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recipes/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_recipe')]
    public function delete($id, Request $request): Response
    {
        $user = $this->security;
        $recipe = $this->recipeRepository->find($id);
        $this->em->remove($recipe);
        $this->em->flush();

        return $this->redirectToRoute('show_user_recipe', ['userId' => $user->getId()]);
    }

    #[Route('/recipes/edit/{id}', name: 'edit_recipe')]
    public function edit($id, Request $request): Response
    {
        $user = $this->security;
        $recipe = $this->recipeRepository->find($id);
        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                if ($recipe->getImagePath() !== null) {
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . $recipe->getImagePath()
                    )) {
                        $this->GetParameter('kernel.project_dir') . $recipe->getImagePath();
                    }
                    $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                    try {
                        $imagePath->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $recipe->setImagePath('/uploads/' . $newFileName);
                    $this->em->flush();

                    return $this->redirectToRoute('show_user_recipe', ['userId' => $user->getId()]);
                }
            } else {
                $recipe->setName($form->get('name')->getData());
                $recipe->setInstruction($form->get('instruction')->getData());
                $recipe->setIngredients($form->get('ingredients')->getData());
                $recipe->setCategory($form->get('category')->getData());

                $this->em->flush();
                return $this->redirectToRoute('show_user_recipe', ['userId' => $user->getId()]);;
            }
        }

        return $this->render('recipes/update.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ]);
    }
}
