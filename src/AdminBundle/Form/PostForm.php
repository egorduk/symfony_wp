<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\PostStatus;
use AdminBundle\Entity\PostType;
use AdminBundle\Entity\Taxonomy;
use AdminBundle\Form\DataTransformer\EntityHiddenTransformer;
use AdminBundle\Form\Type\EntityHiddenType;
use AuthBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 50]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('excerpt', TextType::class, [
                'constraints' => [
                    new Length(['max' => 25]),
                ],
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => false,
            ])
            ->add('taxonomy', EntityType::class, [
                'class' => Taxonomy::class,
                'choice_label' => 'name',
                'placeholder' => false,
            ])
            ->add('postStatus', EntityType::class, [
                'class' => PostStatus::class,
                'choice_label' => 'name',
                'placeholder' => false,
                'expanded' => true,
               // 'required' => true,
                'data' => $options['default_status'],
                //'empty_data' => '1',
            ])
            ->add('postType', EntityHiddenType::class, [
                'class' => PostType::class,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AdminBundle\Entity\Post',
            'default_status' => null,
        ]);
    }

    public function getName()
    {
        return 'admin_bundle_post';
    }
}