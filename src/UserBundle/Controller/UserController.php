<?php

namespace UserBundle\Controller;

use AdminBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    public function viewPostAction($id = 0)
    {
        $postRepository = $this->getDoctrine()
            ->getRepository(Post::class);

        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found');
        }

        return $this->render('UserBundle::post.html.twig', [
            'post' => $post,
        ]);
    }
}
