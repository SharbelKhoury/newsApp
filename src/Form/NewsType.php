<?php

namespace App\Form;

use App\Entity\News;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextAreaType::class, [
                'attr' => [
                    'class' => 'wysiwyg-editor',
                    // Add class for CKEditor
                ],
                'required' => true,
            ])
            ->add('images', FileType::class, [
                'label' => 'Upload Images',
                'multiple' => true, // Allow multiple file uploads
                'mapped' => false, // If you are handling file uploads manually
            ])
            ->add('customDate')
            ->add('category', EntityType::class, [
                'class' => Category::class, // Specify the entity class
                'choice_label' => 'title',  // Use the field to display
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'title',
                'multiple' => true, // Ensure this is true for ManyToMany
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
