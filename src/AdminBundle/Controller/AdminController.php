<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\ContentType;
use AdminBundle\Form\ContentTypeForm;
use AdminBundle\Repository\ContentTypeRepository;
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

            $contentTypeRepository = $this->getDoctrine()
                ->getRepository(ContentType::class);

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
}
