<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table(name="member", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity(repositoryClass="StoreBundle\Entity\Repository\MemberRepository")
 */
class Member
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
     * @var integer
     *
     * @ORM\Column(name="author_id", type="bigint", nullable=true, options={"comment": "作者在多说的id。0表示匿名用户", "default": 0})
     */
    private $authorId = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="author_key", type="integer", nullable=true, options={"comment": "作者在网站中对应的id"})
     */
    private $authorKey;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true, options={"comment": "作者显示名"})
     */
    private $authorName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=true, options={"comment": "作者邮箱"})
     */
    private $authorEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", nullable=true, options={"comment": "作者url"})
     */
    private $authorUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment": "生成时间"})
     */
    private $createdAt;

    /**
     * @var MemberInfo
     *
     * @ORM\OneToOne(targetEntity="MemberInfo", mappedBy="member")
     */
    private $memberInfo;


    function __construct()
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
     * Set authorId
     *
     * @param integer $authorId
     * @return Member
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
     * Set authorKey
     *
     * @param integer $authorKey
     * @return Member
     */
    public function setAuthorKey($authorKey)
    {
        $this->authorKey = $authorKey;

        return $this;
    }

    /**
     * Get authorKey
     *
     * @return integer 
     */
    public function getAuthorKey()
    {
        return $this->authorKey;
    }

    /**
     * Set authorName
     *
     * @param string $authorName
     * @return Member
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     * @return Member
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Get authorEmail
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Member
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
     * Set authorUrl
     *
     * @param string $authorUrl
     * @return Member
     */
    public function setAuthorUrl($authorUrl)
    {
        $this->authorUrl = $authorUrl;

        return $this;
    }

    /**
     * Get authorUrl
     *
     * @return string 
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * Set memberInfo
     *
     * @param \StoreBundle\Entity\MemberInfo $memberInfo
     * @return Member
     */
    public function setMemberInfo(\StoreBundle\Entity\MemberInfo $memberInfo = null)
    {
        $this->memberInfo = $memberInfo;

        return $this;
    }

    /**
     * Get memberInfo
     *
     * @return \StoreBundle\Entity\MemberInfo 
     */
    public function getMemberInfo()
    {
        return $this->memberInfo;
    }
}
