<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConnectService
 *
 * @ORM\Table(name="connect_service", options={"collate": "utf8_general_ci", "character": "utf8"})
 * @ORM\Entity
 */
class ConnectService
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
     * @ORM\Column(name="social_id", type="string", nullable=true, options={"comment": "社会id"})
     */
    private $socialId;

    /**
     * @var string
     *
     * @ORM\Column(name="service_name", type="string", nullable=true, options={"comment": "服务名称"})
     */
    private $serviceName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true, options={"comment": "名称"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_url", type="string", nullable=true, options={"comment": "头像地址"})
     */
    private $avatarUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", nullable=true, options={"comment": "个人主页地址"})
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true, options={"comment": "个人描述"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=true, options={"comment": "个人邮箱"})
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_info_id", type="integer", nullable=true, options={"comment": "人员详细信息id"})
     */
    private $memberInfoId;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"comment": "创建时间"})
     */
    private $createdAt;

    /**
     * @var MemberInfo
     *
     * @ORM\ManyToOne(targetEntity="MemberInfo", inversedBy="connectService")
     * @ORM\JoinColumn(name="member_info_id", referencedColumnName="id")
     */
    private $memberInfo;

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
     * Set socialId
     *
     * @param string $socialId
     * @return ConnectService
     */
    public function setSocialId($socialId)
    {
        $this->socialId = $socialId;

        return $this;
    }

    /**
     * Get socialId
     *
     * @return string 
     */
    public function getSocialId()
    {
        return $this->socialId;
    }

    /**
     * Set serviceName
     *
     * @param string $serviceName
     * @return ConnectService
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Get serviceName
     *
     * @return string 
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ConnectService
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
     * Set avatarUrl
     *
     * @param string $avatarUrl
     * @return ConnectService
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
     * Set url
     *
     * @param string $url
     * @return ConnectService
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
     * Set description
     *
     * @param string $description
     * @return ConnectService
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
     * Set email
     *
     * @param string $email
     * @return ConnectService
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ConnectService
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
     * Set memberInfoId
     *
     * @param integer $memberInfoId
     * @return ConnectService
     */
    public function setMemberInfoId($memberInfoId)
    {
        $this->memberInfoId = $memberInfoId;

        return $this;
    }

    /**
     * Get memberInfoId
     *
     * @return integer 
     */
    public function getMemberInfoId()
    {
        return $this->memberInfoId;
    }

    /**
     * Set memberInfo
     *
     * @param \StoreBundle\Entity\MemberInfo $memberInfo
     * @return ConnectService
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
