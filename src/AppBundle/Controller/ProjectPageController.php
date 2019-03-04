<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectPage;
use AppBundle\Form\Project\ProjectPageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ProjectPage controller.
 */
class ProjectPageController extends Controller {

    /**
     * Lists all ProjectPage entities.
     *
     * @Route("/project/{projectId}/page", name="project_page_index")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param Project $project
     */
    public function indexAction(Request $request, $project) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ProjectPage e WHERE e.project = :project ORDER BY e.id';
        $query = $em->createQuery($dql);
        $query->setParameter('project', $project);
        $paginator = $this->get('knp_paginator');
        $projectPages = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'project' => $project,
            'projectPages' => $projectPages,
        );
    }

    /**
     * Creates a new ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/new", name="project_page_new")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Project $project
     */
    public function newAction(Request $request, Project $project) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $projectPage = new ProjectPage();
        $projectPage->setProject($project);
        $form = $this->createForm(ProjectPageType::class, $projectPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectPage);
            $em->flush();

            $this->addFlash('success', 'The new projectPage was created.');
            return $this->redirectToRoute('project_page_show', array('projectId' => $project->getId(), 'id' => $projectPage->getId()));
        }

        return array(
            'project' => $project,
            'projectPage' => $projectPage,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}", name="project_page_show")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method("GET")
     * @Template()
     * @param Project $project
     * @param ProjectPage $projectPage
     */
    public function showAction(Project $project, ProjectPage $projectPage) {
        if($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }
        return array(
            'project' => $project,
            'projectPage' => $projectPage,
        );
    }

    /**
     * Displays a form to edit an existing ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}/edit", name="project_page_edit")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ProjectPage $projectPage
     */
    public function editAction(Request $request, Project $project, ProjectPage $projectPage) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if($project->getId() !== $projectPage->getProject()->getId()) {
            throw new NotFoundHttpException();
        }
        $editForm = $this->createForm(ProjectPageType::class, $projectPage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projectPage has been updated.');
            return $this->redirectToRoute('project_page_show', array(
                'projectId' => $project->getId(),
                'id' => $projectPage->getId()
            ));
        }

        return array(
            'project' => $project,
            'projectPage' => $projectPage,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectPage entity.
     *
     * @Route("/project/{projectId}/page/{id}/delete", name="project_page_delete")
     * @ParamConverter("project", class="AppBundle:Project", options={"id": "projectId"})
     * @Method("GET")
     * @param Request $request
     * @param ProjectPage $projectPage
     */
    public function deleteAction(Request $request, Project $project, ProjectPage $projectPage) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectPage);
        $em->flush();
        $this->addFlash('success', 'The projectPage was deleted.');

        return $this->redirectToRoute('project_page_index', array(
            'projectId' => $project->getId(),
        ));
    }

}
