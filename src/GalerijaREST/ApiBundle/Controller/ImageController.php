<?php

namespace GalerijaREST\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use GalerijaREST\ApiBundle\Entity\Image;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Form;

/**
 * Image crud actions.
 */
class ImageController extends FOSRestController
{
    /**
     * Get the list of images.
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @ApiDoc()
     */
    public function getImagesAction(ParamFetcherInterface $paramFetcher)
    {
        return $this->getDoctrine()->getRepository('ApiBundle:Image')->findAll();
    }
}
