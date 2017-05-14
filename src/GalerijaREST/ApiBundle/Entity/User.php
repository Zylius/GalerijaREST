<?php
/**
 * Created by PhpStorm.
 * User: Zylius
 * Date: 2017-05-14
 * Time: 13:16
 */

namespace GalerijaREST\ApiBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_table")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Image[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\GalerijaREST\ApiBundle\Entity\Image", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $images;

    /**
     * @var Album[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Album", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $albums;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->albums = new ArrayCollection();
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
     * Add images
     *
     * @param Image $images
     * @return User
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
     * @return Collection|Image[]
     */
    public function getImages()
    {
        return $this->images;
    }


    /**
     * Add albums
     *
     * @param Album $albums
     * @return User
     */
    public function addAlbum(Album $albums)
    {
        $this->albums[] = $albums;
        return $this;
    }
    /**
     * Remove albums
     *
     * @param Album $albums
     */
    public function removeAlbum(Album $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Get albums
     *
     * @return Collection|Album[]
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Get comments
     *
     * @return Collection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }
}