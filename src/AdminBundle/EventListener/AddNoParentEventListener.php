<?php

namespace AdminBundle\EventListener;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AddNoParentEventListener
{
    private function addEventListener(FormBuilderInterface $builder)
    {
        // this function permit to valid values of topics
        $annonymFunction = function(FormInterface $form, $diplayOrder) {
            $entities = $this->container->get('doctrine.orm.default_entity_manager')
                ->getRepository('AdminBundle:Taxonomy')
                ->findAll();

            if ($entities) {
                $topics = array();

                foreach($topics as $topic) {
                    $topics[$topic->getName()] = $topic->getName();
                }
            } else $topics = null;

            $form->add('parent', EntityType::class, array(
                'attr' => array('class' => 'topic'),
                'choices' => $topics));
        };

        $builder
            ->get('displayOrder')
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($annonymFunction) {
                $annonymFunction($event->getForm()->getParent(), $event->getForm()->getData());
            });
    }
}