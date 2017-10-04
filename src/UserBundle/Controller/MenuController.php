<?php

namespace UserBundle\Controller;


use AdminBundle\Entity\Post;
use AdminBundle\Entity\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function postsListAction()
    {
        $postRepository = $this->getDoctrine()
            ->getRepository(Post::class);

        $posts = $postRepository->getPublishPosts();

        return $this->render('UserBundle::posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function postTypesListAction()
    {
        $postTypeRepository = $this->getDoctrine()
            ->getRepository(PostType::class);

        $postTypes = $postTypeRepository->findAll();

        return $this->render('UserBundle::post_types.html.twig', [
            'postTypes' => $postTypes,
        ]);
    }
}