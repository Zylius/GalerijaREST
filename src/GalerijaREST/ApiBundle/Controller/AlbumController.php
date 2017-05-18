<?php

namespace GalerijaREST\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use GalerijaREST\ApiBundle\Entity\Album;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Album crud actions.
 */
class AlbumController extends FOSRestController
{
    /**
     * Get the list of albums.
     *
     *
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @ApiDoc()
     */
    public function getAlbumsAction()
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
     *     404 = "Returned when the album is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="album")
     *
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     *
     * @return Album
     *
     * @throws NotFoundHttpException when album does not exist.
     */
    public function getAlbumAction(Album $album)
    {
        return $album;
    }

    /**
     * Create a new album.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Album",
     *   statusCodes = {
     *     200 = "Returned when successfully created",
     *     400 = "Returned when the data passed is incorrect",
     *   }
     * )
     *
     * @Annotations\View(templateVar="album")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @Annotations\RequestParam(name="title", nullable=false, strict=true, description="Title.")
     * @Annotations\RequestParam(name="user", nullable=false, strict=true,  requirements="[0-9]+", description="User id.")
     * @Annotations\RequestParam(name="cover_photo", nullable=true, strict=true,  requirements="[0-9]+",  description="Cover photo id.")
     *
     *
     * @return Album|Form
     *
     * @throws NotFoundHttpException when album does not exist.
     */
    public function postAlbumsAction(ParamFetcher $paramFetcher)
    {
        $album = new Album();

        $album->setTitle($paramFetcher->get('title'));
        $album->setUser($this->getEntity('ApiBundle:User', $paramFetcher->get('user')));
        $album->setCoverPhoto($this->getEntity('ApiBundle:Image', $paramFetcher->get('cover_photo')));

        $this->getDoctrine()->getManager()->persist($album);
        $this->getDoctrine()->getManager()->flush();

        return $album;
    }

    /**
     * Update an album.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Album",
     *   statusCodes = {
     *     200 = "Returned when successfully edited",
     *     400 = "Returned when the data passed is incorrect",
     *     404 = "Returned when the album is not found",
     *   }
     * )
     *
     * @Annotations\View(templateVar="album")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @param Album $album
     *
     * @Annotations\RequestParam(name="title", nullable=false, strict=true, description="Title.")
     * @Annotations\RequestParam(name="user", nullable=true, strict=true, requirements="[0-9]+", description="User id.")
     * @Annotations\RequestParam(name="cover_photo", nullable=true, strict=true, description="Cover photo id.")
     *
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     *
     * @return Album|Form
     *
     * @throws NotFoundHttpException when album does not exist.
     */
    public function putAlbumAction(ParamFetcher $paramFetcher, Album $album)
    {
        $paramFetcher->get('title') && $album->setTitle($paramFetcher->get('title'));
        $album->setCoverPhoto($this->getEntity('ApiBundle:Image',$paramFetcher->get('cover_photo'), true));
        $paramFetcher->get('user') && $album->setUser($this->getEntity('ApiBundle:User',$paramFetcher->get('user')));

        $this->getDoctrine()->getManager()->persist($album);
        $this->getDoctrine()->getManager()->flush();

        return $album;
    }

    /**
     * Delete an album.
     *
     * @ApiDoc(
     *   statusCodes = {
     *     204 = "Returned when successfully deleted",
     *     404 = "Returned when the album is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="album")
     *
     * @param Album $album
     *
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     *
     * @return View
     *
     * @throws NotFoundHttpException when album does not exist.
     */
    public function deleteAlbumAction(Album $album)
    {
        $this->getDoctrine()->getManager()->remove($album);
        $this->getDoctrine()->getManager()->flush();

        return View::create()->setStatusCode(204);
    }

    /**
     * Returns an entity by id.
     *
     * @param $className
     * @param $id
     * @param bool $allowNull
     *
     * @return object
     */
    private function getEntity(string $className, $id = null, bool $allowNull = false)
    {
        if ($id === null || $id === "null") {
            if (!$allowNull) {
                throw new BadRequestHttpException("{$className} cannot be set to null.");
            }
            return null;
        }

        $result = $this->getDoctrine()->getManager()->getRepository($className)->find($id);

        if ($result === null) {
            throw new NotFoundHttpException("Object {$className} not found with id {$id}.");
        }

        return $result;
    }
}
