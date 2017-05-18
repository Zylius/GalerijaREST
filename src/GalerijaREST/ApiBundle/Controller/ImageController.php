<?php

namespace GalerijaREST\ApiBundle\Controller;

use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use GalerijaREST\ApiBundle\Entity\Album;
use GalerijaREST\ApiBundle\Entity\Image;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Image crud actions.
 */
class ImageController extends FOSRestController
{
    /**
     * Get the list of images by album.
     *
     * @return Image[]
     *
     * @param Album $album
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the album is not found"
     *   }
     * )     */
    public function getAlbumImagesAction(Album $album)
    {
        return $album->getImages();
    }

    /**
     * Get the list of images.
     *
     * @return Image[]
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )     */
    public function getImagesAction()
    {
        return $this->getDoctrine()->getRepository('ApiBundle:Image')->findAll();
    }

    /**
     * Get a single image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the image is not found in album"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param Album $album
     * @param Image $image
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     *
     * @return Image
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function getAlbumImageAction(Album $album, Image $image)
    {
        if ($album !== $image->getAlbum()) {
            throw new NotFoundHttpException("Image {$image->getId()} not found in album {$album->getId()}");
        }

        return $this->getImageAction($image);
    }

    /**
     * Get a single image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the image is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param Image $image
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return Image
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function getImageAction(Image $image)
    {
        return $image;
    }

    /**
     * Create a new image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the data passed is incorrect",
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @Annotations\RequestParam(name="name", nullable=false, strict=true, description="File name.")
     * @Annotations\RequestParam(name="album", nullable=false, strict=true, requirements="[0-9]+", description="Album id.")
     * @Annotations\RequestParam(name="user", nullable=false, strict=true, requirements="[0-9]+", description="User id.")
     * @Annotations\RequestParam(name="description", nullable=true, strict=true, description="Cover photo id.", default="No description")
     * @Annotations\RequestParam(name="image_data", nullable=false, strict=true, description="Image data in base64.")
     *
     * @return Image
     */
    public function postImageAction(ParamFetcher $paramFetcher)
    {
        return $this->postAlbumImageAction(
            $paramFetcher,
            $this->getEntity('ApiBundle:Album', $paramFetcher->get('album'))
        );
    }

    /**
     * Update an image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the data passed is incorrect",
     *     404 = "Returned when the image is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @param Image $image
     *
     * @Annotations\RequestParam(name="name", nullable=true, strict=true, requirements="^(?!null).+", description="File name.")
     * @Annotations\RequestParam(name="album", nullable=true, strict=true, requirements="[0-9]+", description="Album id.")
     * @Annotations\RequestParam(name="user", nullable=true, strict=true, requirements="[0-9]+", description="User id.")
     * @Annotations\RequestParam(name="description", nullable=true, strict=true, description="Description about image.", default="No description")
     * @Annotations\RequestParam(name="image_data", nullable=true, strict=true, requirements="^(?!null).+", description="Image data in base64.")
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return Image
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function putImageAction(ParamFetcher $paramFetcher, Image $image)
    {
        return $this->putAlbumImageAction(
            $paramFetcher,
            $paramFetcher->get('album') ? $this->getEntity('ApiBundle:Album', $paramFetcher->get('album')) : null,
            $image
        );
    }

    /**
     * Update an image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the data passed is incorrect",
     *     404 = "Returned when the image is not found in album"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @param Image $image
     * @param Album $album
     *
     * @Annotations\RequestParam(name="name", nullable=true, strict=true, requirements="^(?!null).+", description="File name.")
     * @Annotations\RequestParam(name="user", nullable=true, strict=true, requirements="[0-9]+", description="User id.")
     * @Annotations\RequestParam(name="description", nullable=true, strict=true, description="Description about image.", default="No description")
     * @Annotations\RequestParam(name="image_data", nullable=true, strict=true, requirements="^(?!null).+", description="Image data in base64.")
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     *
     * @return Image
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function putAlbumImageAction(ParamFetcher $paramFetcher, Album $album = null, Image $image)
    {
        $album && $image->setAlbum($album);
        $paramFetcher->get('user') && $image->setUser($this->getEntity('ApiBundle:User', $paramFetcher->get('user')));
        $paramFetcher->get('description') && $image->setDescription($paramFetcher->get('description'));
        $paramFetcher->get('image_data') && $image->setData($paramFetcher->get('image_data'));
        $paramFetcher->get('name') && $image->setName($paramFetcher->get('name'));

        $this->getDoctrine()->getManager()->persist($image);
        $this->getDoctrine()->getManager()->flush();

        return $image;
    }
    
    /**
     * Create a new image with album.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the data passed is incorrect"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @param Album|object $album
     *
     * @Annotations\RequestParam(name="name", nullable=false, strict=true, description="File name.")
     * @Annotations\RequestParam(name="user", nullable=false, strict=true, requirements="[0-9]+", description="User id.")
     * @Annotations\RequestParam(name="description", nullable=true, strict=true, description="Description about image.", default="No description")
     * @Annotations\RequestParam(name="image_data", nullable=false, strict=true, description="Image data in base64.")
     *
     * @return Image
     *
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     */
    public function postAlbumImageAction(ParamFetcher $paramFetcher, Album $album)
    {
        $image = new Image();

        $image->setAlbum($album);
        $image->setUser($this->getEntity('ApiBundle:User', $paramFetcher->get('user')));
        $image->setDescription($paramFetcher->get('description'));
        $image->setData($paramFetcher->get('image_data'));
        $image->setName($paramFetcher->get('name'));
        $this->getDoctrine()->getManager()->persist($image);
        $this->getDoctrine()->getManager()->flush();

        return $image;
    }

    /**
     * Delete an image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the image is not found in album"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param Album $album
     * @param Image $image
     *
     * @ParamConverter("album", class="ApiBundle:Album", options={"id": "album"})
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return View
     *
     * @throws NotFoundHttpException when album does not exist.
     */
    public function deleteAlbumImageAction(Album $album, Image $image)
    {
        if ($album !== $image->getAlbum()) {
            throw new NotFoundHttpException("Image {$image->getId()} not found in album {$album->getId()}");
        }

        $this->getDoctrine()->getManager()->remove($image);
        $this->getDoctrine()->getManager()->flush();

        return View::create()->setStatusCode(204);
    }

    /**
     * Delete an image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Image",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the image is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param Image $image
     *
     * @ParamConverter("album", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return View
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function deleteImageAction(Image $image)
    {
        $this->getDoctrine()->getManager()->remove($image);
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
