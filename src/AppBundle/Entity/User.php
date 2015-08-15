<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/21
 * Time: 上午10:58
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use StoreBundle\Entity\Post;

/**
 * @ORM\Table(name="user", indexes={@ORM\Index(name="username", columns={"username"})}, options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="email", length=255, nullable=false, unique=true, options={"comment": "邮箱"})
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="username", length=255, nullable=false, unique=true, options={"comment": "用户名"})
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="password", length=255, nullable=false, unique=false, options={"comment": "密码"})
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", name="createAt", options={"comment": "创建时间"})
     */
    protected $createAt;

    /**
     * @var Post
     *
     * @ORM\OneToMany(targetEntity="StoreBundle\Entity\Post", mappedBy="author")
     */
    private $posts;

    public function __construct()
    {
        $this->createAt = new \DateTime();
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     * @return User
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime 
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Add posts
     *
     * @param \StoreBundle\Entity\Post $posts
     * @return User
     */
    public function addPost(\StoreBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \StoreBundle\Entity\Post $posts
     */
    public function removePost(\StoreBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
