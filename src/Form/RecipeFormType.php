<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RecipeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => false,                
                'attr' => [
                   'class' => 'w-full rounded-md border-0 py-1.5 text-gray-900 bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                   'placeholder' => 'Name',
                ],
            ])
            ->add('instruction', TextAreaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'Add instruction...',
                 ]
            ])
            ->add('ingredients', TextAreaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'Add ingredients...',
                 ]
            ])
            ->add('imagePath', FileType::class, array(
                'required' => false,
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'flex flec-col p-2.5'
                 ]
            ))
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Select Category',
                'label' => false,
                'attr' => [
                    'class' => 'appearance-none w-full bg-gray-50 border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline',
                 ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
