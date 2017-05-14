<?php

namespace GalerijaREST\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use GalerijaREST\ApiBundle\Entity\Image;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Image crud actions.
 */
class AlbumImageController extends FOSRestController
{
    /**
     * Get the list of images.
     *
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @ApiDoc()
     */
    public function getImagesAction()
    {
        return array_map(function (Image $image) {
                return $image->getId();
            },
            $this->getDoctrine()->getRepository('ApiBundle:Image')->findAll()
        );
    }

    /**
     * Get a single image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param int $albumId the album id
     * @param int $id the image id
     *
     * @return Image
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function getImageAction($albumId, $id)
    {
        /** @var Image  $image */
        $image = $this->getDoctrine()->getRepository('ApiBundle:Image')->findBy(
            [
                'album' => $albumId,
                'id' => $id
            ]
        );
        if (false === $image) {
            throw $this->createNotFoundException("Note does not exist.");
        }

        return $image;
    }
}
