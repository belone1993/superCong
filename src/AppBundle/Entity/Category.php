<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use StoreBundle\Entity\Post;

/**
 * Category
 *
 * @ORM\Table(name="category", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CategoryRepository")
 */
class Category extends CategoryRepository
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
     * @ORM\Column(name="categoryName", type="string", length=64, options={"comment": "分类名称"})
     */
    private $categoryName;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoryNum", type="integer", nullable=true, options={"comment": "分类文章数量", "default": 0})
     */
    private $categoryNum;

    /**
     * @var Post
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category", mappedBy="category", fetch="EXTRA_LAZY", orphanRemoval=true)
     */
    private $posts;


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
     * Set categoryName
     *
     * @param string $categoryName
     * @return Category
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string 
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set categoryNum
     *
     * @param integer $categoryNum
     * @return Category
     */
    public function setCategoryNum($categoryNum)
    {
        $this->categoryNum = $categoryNum;

        return $this;
    }

    /**
     * Get categoryNum
     *
     * @return integer 
     */
    public function getCategoryNum()
    {
        return $this->categoryNum;
    }
}
