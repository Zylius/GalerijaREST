<?php

namespace GalerijaREST\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use GalerijaREST\ApiBundle\Entity\Tag;
use GalerijaREST\ApiBundle\Entity\Image;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Tag crud actions.
 */
class TagController extends FOSRestController
{
    /**
     * Get the list of all tags.
     **
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Tag",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )     */
    public function getTagsAction()
    {
        return $this->getDoctrine()->getRepository('ApiBundle:Tag')->findAll();
    }

    /**
     * Get the list of tags.
     *
     * @param Image $image
     *
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Tag",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the image is not found"
     *   }
     * )     */
    public function getImageTagsAction(Image $image)
    {
        return $image->getTags();
    }

    /**
     * Create a new tag for image.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Tag",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the data passed is incorrect"
     *   }
     * )
     *
     * @Annotations\View(templateVar="tag")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @param Image $image
     *
     * @Annotations\RequestParam(name="name", nullable=false, strict=true, description="Tag submitted.")
     *
     * @return Tag
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     */
    public function postImageTagAction(ParamFetcher $paramFetcher, Image $image)
    {
        $tag = new Tag();

        $tag->setName($paramFetcher->get('name'));
        $tag->setImage($image);

        $this->getDoctrine()->getManager()->persist($tag);
        $this->getDoctrine()->getManager()->flush();

        return $tag;
    }

    /**
     * Update a tag.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Tag",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the data passed is incorrect",
     *     404 = "Returned when the tag is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="tag")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @param Tag $tag
     * @param Image $image
     *
     * @Annotations\RequestParam(name="name", nullable=true, strict=true, requirements="^(?!null).+", description="Tag submitted.")
     *
     * @ParamConverter("tag", class="ApiBundle:Tag", options={"id": "tag"})
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return Tag
     *
     * @throws NotFoundHttpException when tag does not exist.
     */
    public function putImageTagAction(ParamFetcher $paramFetcher, Image $image, Tag $tag)
    {
        if ($image !== $tag->getImage()) {
            throw new NotFoundHttpException("Tag {$tag->getId()} not found in image {$image->getId()}");
        }

        $paramFetcher->get('name') && $tag->setName($paramFetcher->get('name'));

        $this->getDoctrine()->getManager()->persist($tag);
        $this->getDoctrine()->getManager()->flush();

        return $tag;
    }

    /**
     * Delete an image tag.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Tag",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the tag is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="tag")
     *
     * @param Image $image
     * @param Tag $tag
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     * @ParamConverter("tag", class="ApiBundle:Tag", options={"id": "tag"})
     *
     * @return View
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function deleteImageTagAction(Image $image, Tag $tag)
    {
        if ($image !== $tag->getImage()) {
            throw new NotFoundHttpException("Tag {$tag->getId()} not found in image {$image->getId()}");
        }

        $this->getDoctrine()->getManager()->remove($tag);
        $this->getDoctrine()->getManager()->flush();

        return View::create()->setStatusCode(204);
    }

    /**
     * Get a single tag.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Tag",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the tag is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="tag")
     *
     * @param Image $image
     * @param Tag $tag
     *
     * @ParamConverter("tag", class="ApiBundle:Tag", options={"id": "tag"})
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return Tag
     *
     * @throws NotFoundHttpException when tag does not exist.
     */
    public function getImageTagAction(Image $image, Tag $tag)
    {
        if ($image !== $tag->getImage()) {
            throw new NotFoundHttpException("Tag {$tag->getId()} not found in image {$image->getId()}");
        }

        return $tag;
    }
}
