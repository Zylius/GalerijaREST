<?php
/**
 * Created by PhpStorm.
 * User: Zylius
 * Date: 2017-05-14
 * Time: 13:16
 */

namespace GalerijaREST\ApiBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Serializer\AccessorOrder("custom", custom = {"id"})
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     * @Serializer\SerializedName("comment_text")
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=false)
     */
    private $createdOn;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\GalerijaREST\ApiBundle\Entity\User", inversedBy="comments")
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="\GalerijaREST\ApiBundle\Entity\Image", inversedBy="comments")
     * @Serializer\Exclude()
     */
    private $image;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime("now");
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set image
     *
     * @param Image $image
     * @return Comment
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Comment
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Returns when the comment was created.
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("image")
     */
    public function getImageId()
    {
        if ($this->image === null) {
            return null;
        }
        return $this->image->getId();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("user")
     */
    public function getUserId()
    {
        if ($this->user === null) {
            return null;
        }
        return $this->user->getId();
    }
}