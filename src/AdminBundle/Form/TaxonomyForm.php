<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\Taxonomy;
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

class TaxonomyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'data' => '61111',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 20]),
                ],
            ])
            ->add('slug', TextType::class, [
                'data' => '2132131',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 20]),
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => Taxonomy::class,
                'choice_label' => 'name',
                'placeholder' => false,
                //'constraints' => new NotBlank(),
            ])
            /*->add('parent', ChoiceType::class, [
                'empty_data' => 'No',
            ])*/

            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ]);

        $em = $options['entity_manager'];

        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($em) {
                $entities = $em->getRepository('AdminBundle:Taxonomy')
                    ->findAll();

                $form = $event->getForm();

                dump($event->getData());

                if ($entities) {
                    $topics = array();

                    foreach($topics as $topic) {
                        $topics[$topic->getName()] = $topic->getName();
                    }
                } else {
                    $topics = null;
                }

                $form->add('parent', EntityType::class, [
                    //'attr' => array('class' => 'topic'),
                    'choices' => $topics,
                    'class' => Taxonomy::class,
                'choice_label' => 'name',
                    ]);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AdminBundle\Entity\Taxonomy',
            'csrf_protection' => false,
        ]);

        $resolver->setRequired('entity_manager');
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children['parent']->vars['choices'][] = new ChoiceView([], '0', 'No');

        usort($view->children['parent']->vars['choices'], function ($a, $b) {
           return $a->value < $b->value ? -1 : 1;
        });
    }

    public function getName()
    {
        return 'admin_bundle_taxonomy';
    }
}