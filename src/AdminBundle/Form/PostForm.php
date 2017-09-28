<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\PostType;
use AdminBundle\Entity\Taxonomy;
use AuthBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
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
            ->add('content', TextType::class, [
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
            ->add('postType', EntityType::class, [
                'class' => PostType::class,
                'choice_label' => 'name',
                'placeholder' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AdminBundle\Entity\Post',
        ]);
    }

    public function getName()
    {
        return 'admin_bundle_post';
    }
}