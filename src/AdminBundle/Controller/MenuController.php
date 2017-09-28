<?php

namespace AdminBundle\Controller;


use AdminBundle\Entity\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function contentTypesListAction()
    {
        $postTypeRepository = $this->getDoctrine()
            ->getRepository(PostType::class);

        $postTypes = $postTypeRepository->findAll();

        return $this->render('AdminBundle::menu_item.html.twig', [
            'postTypes' => $postTypes
        ]);
    }
}