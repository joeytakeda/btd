<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ArtworkContribution;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ArtworkContribution controller.
 *
 * @Route("/artwork_contribution")
 */
class ArtworkContributionController extends Controller {
    /**
     * Lists all ArtworkContribution entities.
     *
     * @Route("/", name="artwork_contribution_index", methods={"GET"})
     *
     * @Template()
     *
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ArtworkContribution e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $artworkContributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'artworkContributions' => $artworkContributions,
        );
    }
}
