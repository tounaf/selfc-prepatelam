<?php

namespace Telma\Selfcare\PrepaidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Activity
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
     * @var \DateTime
     *
     * @ORM\Column(name="activity_date", type="datetime")
     */
    private $activityDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activity_status", type="boolean")
     */
    private $activityStatus;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var Action
     *
     * @ORM\ManyToOne(targetEntity="Action")
     * @ORM\JoinColumn(name="action_id", referencedColumnName="id")
     */
    private $actionId;

    public function __construct()
    {
        $this->activityDate = new \DateTime();
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
     * Set activityDate
     *
     * @param \DateTime $activityDate
     *
     * @return Activity
     */
    public function setActivityDate($activityDate)
    {
        $this->activityDate = $activityDate;

        return $this;
    }

    /**
     * Get activityDate
     *
     * @return \DateTime
     */
    public function getActivityDate()
    {
        return $this->activityDate;
    }

    /**
     * Set activityStatus
     *
     * @param boolean $activityStatus
     *
     * @return Activity
     */
    public function setActivityStatus($activityStatus)
    {
        $this->activityStatus = $activityStatus;

        return $this;
    }

    /**
     * Get activityStatus
     *
     * @return boolean
     */
    public function getActivityStatus()
    {
        return $this->activityStatus;
    }

    /**
     * Set userId
     *
     * @param \User $userId
     *
     * @return Company
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \User
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set actionId
     *
     * @param \Action $actionId
     *
     * @return Company
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;

        return $this;
    }

    /**
     * Get actionId
     *
     * @return \Action
     */
    public function getActionId()
    {
        return $this->actionId;
    }

}

