<?php

namespace App\DataFixtures;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $recipe = new Recipe();

       $recipe->setName("test1");
       $recipe->setUser(null);
       $recipe->setInstruction("test instriction test instriction test instriction test instriction test instriction test instriction test instriction test instriction test instriction test instriction");
       $recipe->setCategory(null);
       $recipe->setIngredients("test ingredients test ingredients test ingredients test ingredients");
       $recipe->setImagePath("https://cdn.pixabay.com/photo/2017/07/28/14/28/macarons-2548818_1280.jpg");

       $manager->persist($recipe);
        $manager->flush();
    }
}
