<?php

namespace Telma\Selfcare\PrepaidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserCompanyUpdate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Telma\Selfcare\PrepaidBundle\Repository\UserCompanyUpdateRepository")
 */
class UserCompanyUpdate
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
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_update", referencedColumnName="id")
     */
    private $userUpdate;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(name="company_updated",referencedColumnName="id")
     */
    private $companyUdated;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return UserCompanyUpdate
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
     * @return \User
     */
    public function getUserUpdate()
    {
        return $this->userUpdate;
    }

    /**
     * @param \User $user
     */
    public function setUserUpdate($user)
    {
        $this->userUpdate = $user;
    }

    /**
     * @return \Company
     */
    public function getCompanyUpdated()
    {
        return $this->companyUdated;
    }

    /**
     * @param \Company $company
     */
    public function setCompanyUpdated($company)
    {
        $this->companyUdated = $company;
    }



}

