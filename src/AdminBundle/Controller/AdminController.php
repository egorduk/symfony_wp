<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\ContentType;
use AdminBundle\Entity\Taxonomy;
use AdminBundle\Form\ContentTypeForm;
use AdminBundle\Form\TaxonomyForm;
use AdminBundle\Form\UserForm;
use AdminBundle\Repository\ContentTypeRepository;
use AuthBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    public function postsAction()
    {
        return $this->render('AdminBundle::panel.html.twig');
    }

    public function postAction()
    {
        return $this->render('AdminBundle::panel.html.twig');
    }

    public function addContentTypeAction(Request $request)
    {
        $form = $this->createForm(ContentTypeForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $contentTypeRepository = $this->getDoctrine()
                ->getRepository(ContentType::class);

            $contentTypeRepository->save($formData, true);

            $this->addFlash(
                'success',
                'Content type was created!'
            );

            return $this->redirectToRoute('admin_content_types');
        }

        return $this->render('AdminBundle::content_type.html.twig', [
            'mode' => 'Add',
            'form' => $form->createView(),
        ]);
    }

    public function contentTypesAction()
    {
        $contentTypeRepository = $this->getDoctrine()
            ->getRepository(ContentType::class);

        $contentTypes = $contentTypeRepository->findAll();

        return $this->render('AdminBundle::content_types.html.twig', [
            'contentTypes' => $contentTypes
        ]);
    }

    public function deleteContentTypeAction($id)
    {
        $contentTypeRepository = $this->getDoctrine()
            ->getRepository(ContentType::class);

        $contentType = $contentTypeRepository->find($id);

        if (!$contentType) {
            throw $this->createNotFoundException('No content type found for id ' . $id);
        }

        $contentTypeRepository->remove($contentType, true);

        return $this->contentTypesAction();
    }

    public function editContentTypeAction(Request $request, $id)
    {
        $contentTypeRepository = $this->getDoctrine()
            ->getRepository(ContentType::class);

        $contentType = $contentTypeRepository->find($id);

        if (!$contentType) {
            throw $this->createNotFoundException('No content type found for id ' . $id);
        }

        $form = $this->createForm(ContentTypeForm::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $contentTypeRepository->save($formData, true);

            $this->addFlash(
                'success',
                'Content type was updated!'
            );

            return $this->redirectToRoute('admin_content_types');
        }

        return $this->render('AdminBundle::content_type.html.twig', [
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
}