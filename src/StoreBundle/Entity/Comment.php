<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use StoreBundle\Entity\Repository\CommentRepository;

/**
 * Comment
 *
 * @ORM\Table(name="comment", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity(repositoryClass="StoreBundle\Entity\Repository\CommentRepository")
 */
class Comment extends CommentRepository
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
     * @ORM\Column(name="log_id", type="bigint", nullable=false, options={"comment": "logId"})
     */
    private $logId;

    /**
     * @var integer
     *
     * @ORM\Column(name="api_user_id", type="integer", nullable=false, options={"comment": "用户id"})
     */
    private $apiUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="api_action", type="string", nullable=false, options={"comment": "create 创建评论 approve 通过评论 spam 标记垃圾评论 delete 删除评论 delete-forever 彻底删除评论", "default": "create"})
     */
    private $apiAction = 'create';

    /**
     * @var integer
     *
     * @ORM\Column(name="api_post_id", type="bigint", nullable=true, options={"comment": "在多说的评论id"})
     */
    private $apiPostId;

    /**
     * @var integer
     *
     * @ORM\Column(name="thread_id", type="bigint", nullable=true, options={"comment": "文章在多说的id"})
     */
    private $threadId;

    /**
     * @var string
     *
     * @ORM\Column(name="thread_key", type="string", nullable=true, options={"comment": "文章原ID（本站）"})
     */
    private $threadKey;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_ip", type="string", nullable=true, options={"comment": "作者ip地址。格式为*.*.*.*"})
     */
    private $commentIP;

    /**
     * @var integer
     *
     * @ORM\Column(name="post_id", type="integer", nullable=true, options={"comment": "文章ID"})
     */
    private $postId;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment": "创建时间"})
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true, options={"comment": "评论消息"})
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true, options={"comment": "状态"})
     */
    private $status = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="bigint", nullable=true, options={"comment": "父id"})
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=true, options={"comment": "类型"})
     */
    private $type = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="agent", type="text", nullable=true, options={"comment": "客户端信息"})
     */
    private $agent;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", fetch="EAGER")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=true)
     */
    private $postInfo;

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
     * Set logId
     *
     * @param integer $logId
     * @return Comment
     */
    public function setLogId($logId)
    {
        $this->logId = $logId;

        return $this;
    }

    /**
     * Get logId
     *
     * @return integer 
     */
    public function getLogId()
    {
        return $this->logId;
    }

    /**
     * Set apiUserId
     *
     * @param integer $apiUserId
     * @return Comment
     */
    public function setApiUserId($apiUserId)
    {
        $this->apiUserId = $apiUserId;

        return $this;
    }

    /**
     * Get apiUserId
     *
     * @return integer 
     */
    public function getApiUserId()
    {
        return $this->apiUserId;
    }

    /**
     * Set apiAction
     *
     * @param string $apiAction
     * @return Comment
     */
    public function setApiAction($apiAction)
    {
        $this->apiAction = $apiAction;

        return $this;
    }

    /**
     * Get apiAction
     *
     * @return string 
     */
    public function getApiAction()
    {
        return $this->apiAction;
    }

    /**
     * Set apiPostId
     *
     * @param integer $apiPostId
     * @return Comment
     */
    public function setApiPostId($apiPostId)
    {
        $this->apiPostId = $apiPostId;

        return $this;
    }

    /**
     * Get apiPostId
     *
     * @return integer 
     */
    public function getApiPostId()
    {
        return $this->apiPostId;
    }

    /**
     * Set threadId
     *
     * @param integer $threadId
     * @return Comment
     */
    public function setThreadId($threadId)
    {
        $this->threadId = $threadId;

        return $this;
    }

    /**
     * Get threadId
     *
     * @return integer 
     */
    public function getThreadId()
    {
        return $this->threadId;
    }

    /**
     * Set threadKey
     *
     * @param string $threadKey
     * @return Comment
     */
    public function setThreadKey($threadKey)
    {
        $this->threadKey = $threadKey;

        return $this;
    }

    /**
     * Get threadKey
     *
     * @return string 
     */
    public function getThreadKey()
    {
        return $this->threadKey;
    }

    /**
     * Set commentIP
     *
     * @param string $commentIP
     * @return Comment
     */
    public function setCommentIP($commentIP)
    {
        $this->commentIP = $commentIP;

        return $this;
    }

    /**
     * Get commentIP
     *
     * @return string 
     */
    public function getCommentIP()
    {
        return $this->commentIP;
    }

    /**
     * Set postId
     *
     * @param integer $postId
     * @return Comment
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Comment
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
     * Set message
     *
     * @param string $message
     * @return Comment
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return Comment
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Comment
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set agent
     *
     * @param string $agent
     * @return Comment
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return string 
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set postInfo
     *
     * @param \StoreBundle\Entity\Post $postInfo
     * @return Comment
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
}
