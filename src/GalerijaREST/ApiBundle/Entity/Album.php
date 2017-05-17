<?php
/**
 * Created by PhpStorm.
 * User: Zylius
 * Date: 2017-05-14
 * Time: 13:16
 */

namespace GalerijaREST\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;

/**
 * Album
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Serializer\AccessorOrder("custom", custom = {"id"})
 */
class Album
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;

    /**
     * @var Image
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToOne(targetEntity="GalerijaREST\ApiBundle\Entity\Image")
     * @Serializer\Exclude()
     */
    private $coverPhoto;

    /**
     * @var Image[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\GalerijaREST\ApiBundle\Entity\Image", mappedBy="album")
     * @ORM\OrderBy({"id" = "ASC"})
     * @Type("Relation")
     */
    private $images;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="albums")
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->images = new ArrayCollection();
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
     * Set coverPhoto
     *
     * @param Image $coverPhoto
     * @return Album
     */
    public function setCoverPhoto(Image $coverPhoto = null)
    {
        $this->coverPhoto = $coverPhoto;
        return $this;
    }

    /**
     * Get coverPhoto
     *
     * @return Image
     */
    public function getCoverPhoto()
    {
        return $this->coverPhoto;
    }

    /**
     * Add images
     *
     * @param Image $images
     * @return Album
     */
    public function addImage(Image $images)
    {
        $this->images[] = $images;
        return $this;
    }

    /**
     * Remove images
     *
     * @param Image $images
     */
    public function removeImage(Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images
     *
     * @param array $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Album
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return Album
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Magic method to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Album
     */
    public function setUser(User $user = null)
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


    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("cover_photo")
     */
    public function getCoverPhotoId()
    {
        if ($this->coverPhoto === null) {
            return null;
        }
        return $this->coverPhoto->getId();
    }
}