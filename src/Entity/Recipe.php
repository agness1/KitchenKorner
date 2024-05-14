<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'The name must contain at least {{ limit }} characters.',
        maxMessage: 'Name cannot be longer than {{ limit }} characters',
    )]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?User $user = null;

    #[ORM\Column(length: 700)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 700,
        minMessage: 'Your instruction must be at least {{ limit }} characters long',
        maxMessage: 'Your instruction cannot be longer than {{ limit }} characters',
    )]
    private ?string $instruction = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Assert\NotBlank]
    private ?Category $category = null;

    #[ORM\Column(length: 300)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 300,
        minMessage: 'Your ingredients must be at least {{ limit }} characters long',
        maxMessage: 'Your ingredients cannot be longer than {{ limit }} characters',
    )]
    private ?string $ingredients = null;

    #[ORM\Column(length: 255)]
    #[Assert\Image(
        minWidth: 200,
        maxWidth: 400,
        minHeight: 200,
        maxHeight: 400,
    )]
    private ?string $imagePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): static
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
