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
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Serializer\AccessorOrder("custom", custom = {"id"})
 */
class Tag
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="tags")
     * @Serializer\Exclude()
     */
    private $image;

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
     * Set name
     *
     * @param string $name
     * @return Tag
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
     * Set image
     *
     * @param Image $image
     * @return Tag
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
}