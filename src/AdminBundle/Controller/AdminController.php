<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Post;
use AdminBundle\Entity\PostStatus;
use AdminBundle\Entity\PostType;
use AdminBundle\Entity\Taxonomy;
use AdminBundle\Form\ContentTypeForm;
use AdminBundle\Form\PostForm;
use AdminBundle\Form\PostTypeForm;
use AdminBundle\Form\TaxonomyForm;
use AdminBundle\Form\UserForm;
use AuthBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle::index.html.twig');
    }

    public function panelAction()
    {
        return $this->render('AdminBundle::panel.html.twig');
    }

    public function addPostTypeAction(Request $request)
    {
        $form = $this->createForm(PostTypeForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $postTypeRepository = $this->getDoctrine()
                ->getRepository(PostType::class);

            $postTypeRepository->save($formData, true);

            $this->addFlash(
                'success',
                'Content type was created!'
            );

            return $this->redirectToRoute('admin_post_types');
        }

        return $this->render('AdminBundle::post_type.html.twig', [
            'mode' => 'Add',
            'form' => $form->createView(),
        ]);
    }

    public function postTypesAction()
    {
        $contentTypeRepository = $this->getDoctrine()
            ->getRepository(PostType::class);

        $contentTypes = $contentTypeRepository->findAll();

        return $this->render('AdminBundle::post_types.html.twig', [
            'contentTypes' => $contentTypes
        ]);
    }

    public function deletePostTypeAction($id)
    {
        $contentTypeRepository = $this->getDoctrine()
            ->getRepository(PostType::class);

        $contentType = $contentTypeRepository->find($id);

        if (!$contentType) {
            throw $this->createNotFoundException('No content type found for id ' . $id);
        }

        $contentTypeRepository->remove($contentType, true);

        return $this->postTypesAction();
    }

    public function editPostTypeAction(Request $request, $id)
    {
        $contentTypeRepository = $this->getDoctrine()
            ->getRepository(PostType::class);

        $contentType = $contentTypeRepository->find($id);

        if (!$contentType) {
            throw $this->createNotFoundException('No content type found for id ' . $id);
        }

        $form = $this->createForm(PostTypeForm::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $contentTypeRepository->save($formData, true);

            $this->addFlash(
                'success',
                'Content type was updated!'
            );

            return $this->redirectToRoute('admin_post_types');
        }

        return $this->render('AdminBundle::post_type.html.twig', [
            'mode' => 'Edit',
            'form' => $form->createView(),
        ]);
    }

    public function taxonomiesAction()
    {
        $taxonomies = $this->get('admin.taxonomy_helper')
            ->getAllTaxonomies();

        return $this->render('AdminBundle::taxonomies.html.twig', [
            'taxonomies' => $taxonomies,
        ]);
    }

    public function addTaxonomyAction(Request $request)
    {
        $form = $this->createForm(TaxonomyForm::class, null, [
            'entity_manager' => $this->getDoctrine()->getManager(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $taxonomyRepository = $this->getDoctrine()
                ->getRepository(Taxonomy::class);

            $taxonomyRepository->save($formData, true);

            $this->addFlash(
                'success',
                'Taxonomy was created!'
            );

            return $this->redirectToRoute('admin_taxonomies');
        }

        return $this->render('AdminBundle::taxonomy.html.twig', [
            'mode' => 'Add',
            'form' => $form->createView(),
        ]);
    }

    public function deleteTaxonomyAction($id)
    {
        $taxonomyRepository = $this->getDoctrine()
            ->getRepository(Taxonomy::class);

        $taxonomy = $taxonomyRepository->find($id);

        if (!$taxonomy) {
            throw $this->createNotFoundException('No taxonomy found for id ' . $id);
        }

        $taxonomyRepository->remove($taxonomy, true);

        return $this->taxonomiesAction();
    }

    public function editTaxonomyAction(Request $request, $id)
    {
        $taxonomyRepository = $this->getDoctrine()
            ->getRepository(Taxonomy::class);

        $taxonomy = $taxonomyRepository->find($id);

        if (!$taxonomy) {
            throw $this->createNotFoundException('No taxonomy found for id ' . $id);
        }

        $parentTaxonomy = $taxonomyRepository->find($taxonomy->getParent());

        $taxonomy->setParent($parentTaxonomy);

        $form = $this->createForm(TaxonomyForm::class, $taxonomy, array(
            'entity_manager' => $this->getDoctrine()->getManager(),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $taxonomyRepository->save($formData, true);

            $this->addFlash(
                'success',
                'Taxonomy was updated!'
            );

            return $this->redirectToRoute('admin_taxonomies');
        }

        return $this->render('AdminBundle::taxonomy.html.twig', [
            'mode' => 'Edit',
            'form' => $form->createView(),
        ]);
    }

    public function usersAction()
    {
        $userManager = $this->get('fos_user.user_manager');

        $users = $userManager->findUsers();

        return $this->render('AdminBundle::users.html.twig', [
            'users' => $users,
        ]);
    }

    public function deleteUserAction($id)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserBy([
            'id' => $id
        ]);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $userManager->deleteUser($user);

        return $this->usersAction();
    }

    public function addUserAction(Request $request)
    {
        $form = $this->createForm(UserForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var User $user */
            $user = $form->getData();
            $user->setPlainPassword($user->getPassword());
            $user->setEnabled(true);

            $this->get('fos_user.user_manager')
                ->updateUser($user);

            $this->addFlash(
                'success',
                'User was created!'
            );

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('AdminBundle::user.html.twig', [
            'mode' => 'Add',
            'form' => $form->createView(),
        ]);
    }

    public function addPostAction(Request $request, $id = 0)
    {
        $postTypeRepository = $this->getDoctrine()
            ->getRepository(PostType::class);

        $postType = $postTypeRepository->find($id);

        if (!$postType) {
            throw $this->createNotFoundException('No post type found');
        }

        $post = new Post();
        $post->setPostType($postType);

        $status = $this->getDoctrine()
            ->getRepository(PostStatus::class)
            ->findOneBy(['code' => 'p']);

        $form = $this->createForm(PostForm::class, $post, ['default_status' => $status]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $this->getDoctrine()
                ->getRepository(Post::class)
                ->save($formData, true);

            $this->addFlash(
                'success',
                'Post was created!'
            );

            $postTypeId = $postType->getId();

            return $this->viewPostsAction($postTypeId);
        }

        return $this->render('AdminBundle::post.html.twig', [
            'mode' => 'Add',
            'form' => $form->createView(),
        ]);
    }

    public function postsAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('AdminBundle::posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function viewPostsAction($id = 0)
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(['postType' => $id]);

        return $this->render('AdminBundle::posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function deletePostAction($id)
    {
        $postRepository = $this->getDoctrine()
            ->getRepository(Post::class);

        /* @var Post $post */
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found for id ' . $id);
        }

        $postTypeId = $post->getPostType()
            ->getId();

        $postRepository->remove($post);

        return $this->viewPostsAction($postTypeId);
    }

    public function editPostAction(Request $request, $id)
    {
        $postRepository = $this->getDoctrine()
            ->getRepository(Post::class);

        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found');
        }

        $form = $this->createForm(PostForm::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var Post $formData */
            $formData = $form->getData();

            $postRepository->save($formData);

            $this->addFlash(
                'success',
                'Post was updated!'
            );

            $postTypeId = $formData->getPostType()
                ->getId();

            return $this->viewPostsAction($postTypeId);
        }

        return $this->render('AdminBundle::post.html.twig', [
            'mode' => 'Save',
            'form' => $form->createView(),
        ]);
    }
}