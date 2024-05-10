<?php

namespace App\DataFixtures;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $category = new Category();

      $categoryNames = [
        "Main Course",
        "Side Dishes",
        "Snacks",
        "Desserts",
        "Salads",
        "Soups",
        "Appetizers",
        "Beverages",
        "Breads",
        "Breakfast"
    ];

    foreach($categoryNames as $categoryName) {
        $category = new Category();
        $category->setName($categoryName);
        $manager->persist($category);
    }

        $manager->flush();

    }
}