<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use StoreBundle\Entity\Repository\AlbumRepository;

/**
 * Album
 *
 * @ORM\Table(name="album", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity(repositoryClass="StoreBundle\Entity\Repository\AlbumRepository")
 */
class Album extends AlbumRepository
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
     * @ORM\Column(name="title", type="string", length=128, nullable=false, options={"comment": "志辑标题"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true, options={"comment": "描述"})
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="photoNum", type="integer", nullable=true, options={"comment": "图片数量", "default": 0})
     */
    private $photoNum = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="createAt", type="datetime", nullable=true, options={"comment": "创建时间"})
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"comment": "创建时间", "default": 0})
     */
    private $status = 0;

    /**
     * Album constructor.
     */
    public function __construct( )
    {
        $this->createdAt = new \DateTime();
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
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Album
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
     * Set photoNum
     *
     * @param integer $photoNum
     * @return Album
     */
    public function setPhotoNum($photoNum)
    {
        $this->photoNum = $photoNum;

        return $this;
    }

    /**
     * Get photoNum
     *
     * @return integer 
     */
    public function getPhotoNum()
    {
        return $this->photoNum;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Album
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Album
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
