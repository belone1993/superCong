<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use StoreBundle\Entity\Category;
use StoreBundle\Entity\Repository\PostRepository;

/**
 * Post
 *
 * @ORM\Table(name="post", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity(repositoryClass="StoreBundle\Entity\Repository\PostRepository")
 */
class Post extends PostRepository
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
     * @ORM\Column(name="title", type="string", length=128, nullable=true, options={"comment": "标题"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=128, nullable=true, options={"comment": "图片"})
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true, options={"comment": "简介"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true, options={"comment": "内容"})
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
     * @ORM\Column(name="categoryId", type="integer", nullable=true, options={"comment": "分类ID", "default": 0})
     */
    private $categoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="isMarkdown", type="integer", nullable=true, options={"comment": "是否为Markdown编写"})
     */
    private $isMarkdown = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="readNum", type="integer", nullable=true, options={"comment": "阅读量", "default": 1})
     */
    private $readNum = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="action", type="integer", nullable=false, options={"comment": "属于哪个模块,1为学夫止境,2为慢生活"})
     */
    private $action;

    /**
     * @var integer
     *
     * @ORM\Column(name="oldId", type="integer", nullable=true, options={"comment": "之前的文章ID", "default": 0})
     */
    private $oldId = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", nullable=true, options={"comment": "来源"})
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true, options={"comment": "创建时间"})
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="pushTime", type="datetime", nullable=true, options={"comment": "发布时间"})
     */
    private $pushTime;

    /**
     * @var string
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true, options={"comment": "最后修改时间"})
     */
    private $modified;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"comment": "0为草稿,1为已发布", "default": 0})
     */
    private $status = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="reviews", type="integer", nullable=true, options={"comment": "评论数", "default": 0})
     */
    private $reviews = 0;

    /**
     * @var \StoreBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts", fetch="EAGER")
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @var Image
     *
     * @ORM\OneToMany(targetEntity="Image", fetch="EXTRA_LAZY", mappedBy="postInfo", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @var \StoreBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="StoreBundle\Entity\User", fetch="EXTRA_LAZY", inversedBy="posts")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $author;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->readNum   = 1;
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

    /**
     * Set readNum
     *
     * @param integer $readNum
     * @return Post
     */
    public function setReadNum($readNum)
    {
        $this->readNum = $readNum;

        return $this;
    }

    /**
     * Get readNum
     *
     * @return integer
     */
    public function getReadNum()
    {
        return $this->readNum;
    }

    /**
     * Set action
     *
     * @param integer $action
     * @return Post
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return integer
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set oldId
     *
     * @param integer $oldId
     * @return Post
     */
    public function setOldId($oldId)
    {
        $this->oldId = $oldId;

        return $this;
    }

    /**
     * Get oldId
     *
     * @return integer
     */
    public function getOldId()
    {
        return $this->oldId;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Post
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set category
     *
     * @param \StoreBundle\Entity\Category $category
     * @return Post
     */
    public function setCategory(\StoreBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \StoreBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Post
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

    /**
     * Add images
     *
     * @param \StoreBundle\Entity\Image $images
     * @return Post
     */
    public function addImage(\StoreBundle\Entity\Image $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \StoreBundle\Entity\Image $images
     */
    public function removeImage(\StoreBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }


    /**
     * Set author
     *
     * @param \StoreBundle\Entity\User $author
     * @return Post
     */
    public function setAuthor(\StoreBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \StoreBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set reviews
     *
     * @param integer $reviews
     * @return Post
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * Get reviews
     *
     * @return integer 
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set pushTime
     *
     * @param \DateTime $pushTime
     * @return Post
     */
    public function setPushTime($pushTime)
    {
        $this->pushTime = $pushTime;

        return $this;
    }

    /**
     * Get pushTime
     *
     * @return \DateTime 
     */
    public function getPushTime()
    {
        return $this->pushTime;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return Post
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }
}
