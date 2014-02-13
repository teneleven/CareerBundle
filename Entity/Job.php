<?php

namespace Teneleven\Bundle\CareerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 */
class Job
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $contact;

    /**
     * @var boolean
     */
    private $isPublished;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \DateTime
     */
    private $expirationDate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $replies;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $reportTo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->replies = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @return Job
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
     * Set releaseDate
     *
     * @param \DateTime $releaseDate
     *
     * @return Job
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get releaseDate
     *
     * @return \DateTime 
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Job
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
     * Set location
     *
     * @param string $location
     *
     * @return Job
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Job
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     *
     * @return Job
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean 
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Job
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Job
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Job
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return Job
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime 
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $reportsTo;


    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Job
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set reportsTo
     *
     * @param string $reportsTo
     *
     * @return Job
     */
    public function setReportsTo($reportsTo)
    {
        $this->reportsTo = $reportsTo;

        return $this;
    }

    /**
     * Get reportsTo
     *
     * @return string 
     */
    public function getReportsTo()
    {
        return $this->reportsTo;
    }

    /**
     * Add replies
     *
     * @param \Teneleven\Bundle\CareerBundle\Entity\Reply $replies
     *
     * @return Job
     */
    public function addReply(\Teneleven\Bundle\CareerBundle\Entity\Reply $replies)
    {
        $this->replies[] = $replies;

        return $this;
    }

    /**
     * Remove replies
     *
     * @param \Teneleven\Bundle\CareerBundle\Entity\Reply $replies
     */
    public function removeReply(\Teneleven\Bundle\CareerBundle\Entity\Reply $replies)
    {
        $this->replies->removeElement($replies);
    }

    /**
     * Get replies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * Create Reply linked to this Job
     * 
     * @return  \Teneleven\Bundle\CareerBundle\Entity\Reply $reply
     */
    public function createNewReply()
    {
        $reply = new \Teneleven\Bundle\CareerBundle\Entity\Reply;

        $reply->setJob($this);

        return $reply;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Job
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
     * Set reportTo
     *
     * @param string $reportTo
     *
     * @return Job
     */
    public function setReportTo($reportTo)
    {
        $this->reportTo = $reportTo;

        return $this;
    }

    /**
     * Get reportTo
     *
     * @return string 
     */
    public function getReportTo()
    {
        return $this->reportTo;
    }
}
