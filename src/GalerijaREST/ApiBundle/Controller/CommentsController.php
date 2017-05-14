<?php

namespace GalerijaREST\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use GalerijaREST\ApiBundle\Entity\Comment;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Form;

/**
 * Comment crud actions.
 */
class CommentsController extends FOSRestController
{
    /**
     * Get the list of comments.
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @ApiDoc()
     */
    public function getCommentsAction(ParamFetcherInterface $paramFetcher)
    {
        return $this->getDoctrine()->getRepository('ApiBundle:Comment')->findAll();
    }
}
