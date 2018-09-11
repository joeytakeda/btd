<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Venue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Venue controller.
 *
 * @Route("/venue")
 */
class VenueController extends Controller {

    /**
     * Lists all Venue entities.
     *
     * @Route("/", name="venue_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Venue e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $venues = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'venues' => $venues,
        );
    }

   /**
     * @param Request $request
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * @Route("/typeahead", name="venue_typeahead")
     * @Method("GET")
     * @return JsonResponse
     */
    public function typeaheadAction(Request $request) {
        $q = $request->query->get('q');
        if (!$q) {
            return new JsonResponse([]);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Venue::class);
        $data = [];
        foreach ($repo->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Creates a new Venue entity.
     *
     * @Route("/new", name="venue_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $venue = new Venue();
        $form = $this->createForm('AppBundle\Form\VenueType', $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($venue);
            $em->flush();

            $this->addFlash('success', 'The new venue was created.');
            return $this->redirectToRoute('venue_show', array('id' => $venue->getId()));
        }

        return array(
            'venue' => $venue,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Venue entity.
     *
     * @Route("/{id}", name="venue_show")
     * @Method("GET")
     * @Template()
     * @param Venue $venue
     */
    public function showAction(Venue $venue) {

        return array(
            'venue' => $venue,
        );
    }

    /**
     * Displays a form to edit an existing Venue entity.
     *
     * @Route("/{id}/edit", name="venue_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param Venue $venue
     */
    public function editAction(Request $request, Venue $venue) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm('AppBundle\Form\VenueType', $venue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The venue has been updated.');
            return $this->redirectToRoute('venue_show', array('id' => $venue->getId()));
        }

        return array(
            'venue' => $venue,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Venue entity.
     *
     * @Route("/{id}/delete", name="venue_delete")
     * @Method("GET")
     * @param Request $request
     * @param Venue $venue
     */
    public function deleteAction(Request $request, Venue $venue) {
        if( ! $this->isGranted('ROLE_CONTENT_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($venue);
        $em->flush();
        $this->addFlash('success', 'The venue was deleted.');

        return $this->redirectToRoute('venue_index');
    }

}
