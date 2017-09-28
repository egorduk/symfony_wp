<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 20]),
                ],
            ])
            ->add('slug', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 20]),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new Length(['max' => 150]),
                ],
                'required' => false,
                'empty_data' => '',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AdminBundle\Entity\PostType',
        ]);
    }

    public function getName()
    {
        return 'admin_bundle_post_type';
    }
}