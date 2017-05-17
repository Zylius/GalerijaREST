<?php

namespace GalerijaREST\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use GalerijaREST\ApiBundle\Entity\Comment;
use GalerijaREST\ApiBundle\Entity\Image;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Comment crud actions.
 */
class CommentController extends FOSRestController
{
    /**
     * Get the list of all comments.
     **
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     *
     * @ApiDoc()
     */
    public function getCommentsAction()
    {
        return $this->getDoctrine()->getRepository('ApiBundle:Comment')->findAll();
    }

    /**
     * Get the list of comments.
     *
     * @param Image $image
     *
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @ApiDoc()
     */
    public function getImageCommentsAction(Image $image)
    {
        return $image->getComments();
    }

    /**
     * Create a new comment for comment.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Comment",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(templateVar="comment")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @param Image $image
     *
     * @Annotations\RequestParam(name="comment_text", nullable=false, strict=true, description="Comment submitted.")
     * @Annotations\RequestParam(name="user", nullable=false, strict=true, requirements="[0-9]+", description="User id.")
     *
     * @return Comment
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     */
    public function postImageCommentAction(ParamFetcher $paramFetcher, Image $image)
    {
        $comment = new Comment();

        $comment->setComment($paramFetcher->get('comment'));
        $comment->setUser($this->getEntity('ApiBundle:User', $paramFetcher->get('user')));
        $comment->setImage($image);

        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();

        return $comment;
    }

    /**
     * Update a comment.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Comment",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the comment is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="comment")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @param Comment $comment
     * @param Image $image
     *
     * @Annotations\RequestParam(name="user", nullable=true, strict=true, requirements="[0-9]+", description="User id.")
     * @Annotations\RequestParam(name="comment_text", nullable=true, strict=true, requirements="^(?!null).+", description="Comment text.")
     *
     * @ParamConverter("comment", class="ApiBundle:Comment", options={"id": "comment"})
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return Comment
     *
     * @throws NotFoundHttpException when comment does not exist.
     */
    public function putImageCommentAction(ParamFetcher $paramFetcher, Image $image, Comment $comment)
    {
        if ($image !== $comment->getImage()) {
            throw new NotFoundHttpException("Comment {$comment->getId()} not found in image {$image->getId()}");
        }

        $image && $comment->setImage($image);
        $paramFetcher->get('user') && $comment->setUser($this->getEntity('ApiBundle:User', $paramFetcher->get('user')));
        $paramFetcher->get('comment') && $comment->setComment($paramFetcher->get('comment'));

        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();

        return $comment;
    }

    /**
     * Delete an image comment.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Comment",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the comment is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="comment")
     *
     * @param Image $image
     * @param Comment $comment
     *
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     * @ParamConverter("comment", class="ApiBundle:Comment", options={"id": "comment"})
     *
     * @return View
     *
     * @throws NotFoundHttpException when image does not exist.
     */
    public function deleteImageCommentAction(Image $image, Comment $comment)
    {
        if ($image !== $comment->getImage()) {
            throw new NotFoundHttpException("Comment {$comment->getId()} not found in image {$image->getId()}");
        }

        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();

        return View::create()->setStatusCode(204);
    }

    /**
     * Get a single comment.
     *
     * @ApiDoc(
     *   output = "GalerijaREST\ApiBundle\Entity\Comment",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="comment")
     *
     * @param Image $image
     * @param Comment $comment
     *
     * @ParamConverter("comment", class="ApiBundle:Comment", options={"id": "comment"})
     * @ParamConverter("image", class="ApiBundle:Image", options={"id": "image"})
     *
     * @return Comment
     *
     * @throws NotFoundHttpException when comment does not exist.
     */
    public function getImageCommentAction(Image $image, Comment $comment)
    {
        if ($image !== $comment->getImage()) {
            throw new NotFoundHttpException("Comment {$comment->getId()} not found in image {$image->getId()}");
        }

        return $comment;
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
