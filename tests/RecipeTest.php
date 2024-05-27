<?php

namespace App\tests;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindRecipe(): void
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find(1);

        $this->assertNotNull($recipe);
        $this->assertEquals('test1', $recipe->getName());
    }

    public function testRemoveRecipe(): void
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find(1);

        $this->assertNotNull($recipe);

        $this->entityManager->remove($recipe);
        $this->entityManager->flush();

        $deletedRecipe = $this->entityManager->getRepository(Recipe::class)->find(1);
        $this->assertNull($deletedRecipe);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
