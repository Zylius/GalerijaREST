<?php
/**
 * Created by PhpStorm.
 * User: Zylius
 * Date: 2017-05-14
 * Time: 13:34
 */

namespace GalerijaREST\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Image
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
     * @ORM\Column(name="fileName", type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="image")
     * @ORM\OrderBy({"id" = "ASC"})
     * @Serializer\Type("Relation")
     */
    private $tags;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\GalerijaREST\ApiBundle\Entity\Comment", mappedBy="image")
     * @ORM\OrderBy({"id" = "ASC"})
     * @Serializer\Type("Relation")
     */
    private $comments;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\GalerijaREST\ApiBundle\Entity\User", inversedBy="images")
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @var Album
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="\GalerijaREST\ApiBundle\Entity\Album", inversedBy="images")
     * @Serializer\Type("Relation")
     * @Serializer\Exclude()
     */
    private $album;

    /**
     *  @var string
     *
     * @ORM\Column(name="data", type="text")
     * @Serializer\SerializedName("image_data")
     * @Assert\NotBlank
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extension;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * Add tags
     *
     * @param Tag $tags
     * @return Image
     */
    public function addTag(Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return Collection|Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add comments
     *
     * @param Comment $comments
     * @return Image
     */
    public function addComment(Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param Comment $comments
     */
    public function removeComment(Comment $comments)
    {
        $this->comments->removeElement($comments);
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

    /**
     * Set user
     *
     * @param User $user
     * @return Image
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
     * Set album
     *
     * @param Album $album
     * @return Image
     */
    public function setAlbum(Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Sets file.
     *
     * @param string $data
     *
     * @throws \HttpInvalidParamException
     */
    public function setData(string $data)
    {
        $data = trim($data);
        $extension = $this->checkExtension($data);
        if (!$extension) {
            throw new BadRequestHttpException("Image data parameter was invalid.");
        }
        $this->extension = $extension;
        $this->data = $data;
    }

    /**
     * Checks extension of the base64 data.
     *
     * @param $data
     *
     * @return bool|string
     */
    private function checkExtension($data){
        $imageContents = base64_decode($data);

        // If its not base64 end processing and return false
        if ($imageContents === false) {
            return false;
        }

        $validExtensions = ['png', 'jpeg', 'jpg', 'gif'];


        if (substr($data, 0, 11) !== 'data:image/') {
            return false;
        }

        $extension = strtok(str_replace('data:image/', '', $data), ';');

        if (!in_array(strtolower($extension), $validExtensions)) {
            return false;
        }

        return $extension;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("album")
     */
    public function getAlbumId()
    {
        return $this->album->getId();
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