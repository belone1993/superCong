<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity(repositoryClass="StoreBundle\Entity\Repository\ImageRepository")
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
     * @ORM\Column(name="imageName", type="string", length=128, nullable=false, options={"comment": "图片名"})
     */
    private $imageName;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=32, nullable=false, options={"comment": "图片后缀"})
     */
    private $extension;

    /**
     * @var integer
     *
     * @ORM\Column(name="postId", type="integer", nullable=false, options={"comment": "文章ID"})
     */
    private $postId;

    /**
     * @var string
     *
     * @ORM\Column(name="imagePath", type="string", nullable=true, options={"comment": "图片所在目录"})
     */
    private $imagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="realPath", type="string", nullable=true, options={"comment": "绝对路径"})
     */
    private $realPath;

    /**
     * @var string
     *
     * @ORM\Column(name="imageTime", type="datetime", nullable=true, options={"comment": "创建时间"})
     */
    private $imageTime;

    /**
     * @var string
     *
     * @ORM\Column(name="imageStatus", type="integer", nullable=true, options={"comment": "图片状态", "default": 0})
     */
    private $imageStatus = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="imageSize", type="string", nullable=true, options={"comment": "图片大小", "default": 0})
     */
    private $imageSize = 0;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="images", fetch="EAGER")
     * @ORM\JoinColumn(name="postId", referencedColumnName="id")
     */
    private $postInfo;

    function __construct()
    {
        $this->imageTime = new \DateTime();
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
     * Set imageName
     *
     * @param string $imageName
     * @return Image
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string 
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set postId
     *
     * @param integer $postId
     * @return Image
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get postId
     *
     * @return integer 
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     * @return Image
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set realPath
     *
     * @param string $realPath
     * @return Image
     */
    public function setRealPath($realPath)
    {
        $this->realPath = $realPath;

        return $this;
    }

    /**
     * Get realPath
     *
     * @return string 
     */
    public function getRealPath()
    {
        return $this->realPath;
    }

    /**
     * Set imageTime
     *
     * @param \DateTime $imageTime
     * @return Image
     */
    public function setImageTime($imageTime)
    {
        $this->imageTime = $imageTime;

        return $this;
    }

    /**
     * Get imageTime
     *
     * @return \DateTime 
     */
    public function getImageTime()
    {
        return $this->imageTime;
    }

    /**
     * Set imageSize
     *
     * @param string $imageSize
     * @return Image
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    /**
     * Get imageSize
     *
     * @return string 
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * Set postInfo
     *
     * @param \StoreBundle\Entity\Post $postInfo
     * @return Image
     */
    public function setPostInfo(\StoreBundle\Entity\Post $postInfo = null)
    {
        $this->postInfo = $postInfo;

        return $this;
    }

    /**
     * Get postInfo
     *
     * @return \StoreBundle\Entity\Post 
     */
    public function getPostInfo()
    {
        return $this->postInfo;
    }

    /**
     * Set imageStatus
     *
     * @param integer $imageStatus
     * @return Image
     */
    public function setImageStatus($imageStatus)
    {
        $this->imageStatus = $imageStatus;

        return $this;
    }

    /**
     * Get imageStatus
     *
     * @return integer 
     */
    public function getImageStatus()
    {
        return $this->imageStatus;
    }
}
