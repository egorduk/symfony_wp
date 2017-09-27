<?php

namespace AdminBundle\Controller;


use AdminBundle\Entity\ContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function contentTypesListAction()
    {
        $contentTypeRepository = $this->getDoctrine()
            ->getRepository(ContentType::class);

        $contentTypes = $contentTypeRepository->findAll();

        return $this->render('AdminBundle::menu_item.html.twig', [
            'contentTypes' => $contentTypes
        ]);
    }
}