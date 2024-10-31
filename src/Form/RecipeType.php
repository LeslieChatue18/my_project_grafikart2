<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre *',
            'attr' => [
                'placeholder' => 'Entrez le titre de votre recette'
            ],
            'required' => false,
            'constraints' => [
                new Length([
                    'min' => 10,
                    'minMessage' => 'Le titre doit comporter au moins {{ limit }} caractères.',
                    'max' => 255,
                    'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.'
                ]),
            ],
        ])
            ->add('slug',TextType::class ,['label'=> 'Nom d\'usage *','attr'=>['placeholder' => 'Entrez le slug de votre recette'],'constraints' =>[ new Length(['min'=>10 ,'minMessage'=> 'La taille de votre slug doit avoir au minimum {{ limit }} caractères'] )]])
            ->add('createdAt', null, [
                'widget' => 'single_text',
                'label'=> 'Date de création *'
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
                'label'=> 'Date de modification *'
            ])
            ->add('contents' ,null, ['label'=>'Indication de cuisson *','attr'=>['placeholder' => 'Entrez le contenu de votre recette'],'constraints' => [
                new Length([
                    'min' => 255,
                    'minMessage' => 'Le contenu doit comporter au moins {{ limit }} caractères.',
                    
                ]),
            ]])
            ->add('duration' ,null,['label'=> 'Temps de préparation *','attr'=>['placeholder' => 'Entrez le temps de préparation de votre recette']])
            ->add('save' , SubmitType::class , ['label'=> 'Envoyer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
