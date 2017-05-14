<?php

namespace GalerijaREST\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use GalerijaREST\ApiBundle\Entity\Album;
use GalerijaREST\ApiBundle\Form\AlbumType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Album crud actions.
 */
class AlbumController extends FOSRestController
{
    /**
     * Get the list of albums.
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @ApiDoc()
     */
    public function getAlbumsAction(ParamFetcherInterface $paramFetcher)
    {

        return $this->getDoctrine()->getRepository('ApiBundle:Album')->findAll();
    }

    /**
     * Get a single album.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Album",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="album")
     *
     * @param int $id the album id
     *
     * @return Album
     *
     * @throws NotFoundHttpException when album does not exist.
     */
    public function getAlbumAction(int $id)
    {
        /** @var Album  $album */
        $album = $this->getDoctrine()->getRepository('ApiBundle:Album')->find($id);
        if (false === $album) {
            throw $this->createNotFoundException("Album does not exist.");
        }

        return $album;
    }
}
