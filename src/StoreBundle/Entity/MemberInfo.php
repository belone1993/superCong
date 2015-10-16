<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberInfo
 *
 * @ORM\Table(name="member_info", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity
 */
class MemberInfo
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
     * @ORM\Column(name="name", type="string", nullable=true, options={"comment": "作者显示名"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", nullable=true, options={"comment": "url"})
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_url", type="string", nullable=true, options={"comment": "头像url"})
     */
    private $avatarUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="threads", type="integer", nullable=true, options={"comment": "", "default": 0})
     */
    private $threads = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="comments", type="integer", nullable=true, options={"comment": "评论数量", "default": 0})
     */
    private $comments = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="post_votes", type="integer", nullable=true, options={"comment": "post数量", "default": 0})
     */
    private $postVotes = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_id", type="integer", nullable=true, options={"comment": "用户编号"})
     */
    private $memberId;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment": "创建时间"})
     */
    private $createdAt;

    /**
     * @var Member
     *
     * @ORM\OneToOne(targetEntity="Member", inversedBy="memberInfo")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @var ConnectService
     *
     * @ORM\OneToMany(targetEntity="ConnectService", mappedBy="memberInfo")
     */
    private $connectService;

    function __construct()
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
     * Set name
     *
     * @param string $name
     * @return MemberInfo
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
     * Set url
     *
     * @param string $url
     * @return MemberInfo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set avatarUrl
     *
     * @param string $avatarUrl
     * @return MemberInfo
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * Get avatarUrl
     *
     * @return string 
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * Set threads
     *
     * @param integer $threads
     * @return MemberInfo
     */
    public function setThreads($threads)
    {
        $this->threads = $threads;

        return $this;
    }

    /**
     * Get threads
     *
     * @return integer 
     */
    public function getThreads()
    {
        return $this->threads;
    }

    /**
     * Set comments
     *
     * @param integer $comments
     * @return MemberInfo
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return integer 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set postVotes
     *
     * @param integer $postVotes
     * @return MemberInfo
     */
    public function setPostVotes($postVotes)
    {
        $this->postVotes = $postVotes;

        return $this;
    }

    /**
     * Get postVotes
     *
     * @return integer 
     */
    public function getPostVotes()
    {
        return $this->postVotes;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MemberInfo
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
     * Set memberId
     *
     * @param integer $memberId
     * @return MemberInfo
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * Get memberId
     *
     * @return integer 
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * Set member
     *
     * @param \StoreBundle\Entity\Member $member
     * @return MemberInfo
     */
    public function setMember(\StoreBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \StoreBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Add connectService
     *
     * @param \StoreBundle\Entity\ConnectService $connectService
     * @return MemberInfo
     */
    public function addConnectService(\StoreBundle\Entity\ConnectService $connectService)
    {
        $this->connectService[] = $connectService;

        return $this;
    }

    /**
     * Remove connectService
     *
     * @param \StoreBundle\Entity\ConnectService $connectService
     */
    public function removeConnectService(\StoreBundle\Entity\ConnectService $connectService)
    {
        $this->connectService->removeElement($connectService);
    }

    /**
     * Get connectService
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConnectService()
    {
        return $this->connectService;
    }
}
