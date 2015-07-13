<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity
 */
class Post
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
     * @ORM\Column(name="title", type="string", length=128, nullable=false, options={"comment": "标题"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=128, nullable=false, options={"comment": "图片"})
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false, options={"comment": "简介"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false, options={"comment": "内容"})
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="authorId", type="integer", nullable=false, options={"comment": "作者"})
     */
    private $authorId;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoryId", type="integer", nullable=false, options={"comment": "分类ID"})
     */
    private $categoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="isMarkdown", type="integer", nullable=false, options={"comment": "是否为Markdown编写"})
     */
    private $isMarkdown;

    /**
     * @var string
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false, options={"comment": "创建时间"})
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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
     * @return Post
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
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
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
     * Set description
     *
     * @param string $description
     * @return Post
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
     * Set image
     *
     * @param string $image
     * @return Post
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set authorId
     *
     * @param integer $authorId
     * @return Post
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get authorId
     *
     * @return integer 
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     * @return Post
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set isMarkdown
     *
     * @param integer $isMarkdown
     * @return Post
     */
    public function setIsMarkdown($isMarkdown)
    {
        $this->isMarkdown = $isMarkdown;

        return $this;
    }

    /**
     * Get isMarkdown
     *
     * @return integer 
     */
    public function getIsMarkdown()
    {
        return $this->isMarkdown;
    }
}
