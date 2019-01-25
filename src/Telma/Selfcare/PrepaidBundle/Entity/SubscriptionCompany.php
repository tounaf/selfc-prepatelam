<?php

namespace Telma\Selfcare\PrepaidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubscriptionCompany
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Telma\Selfcare\PrepaidBundle\Repository\SubscriptionCompanyRepository")
 */
class SubscriptionCompany
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
     * @ORM\Column(name="subscription", type="string", length=255, nullable=false)
     */
    private $subscription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="attachement_date", type="datetime")
     */
    private $attachementDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="detachement_date", type="datetime", nullable=true)
     */
    private $detachementDate;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    public function __construct()
    {
        $this->attachementDate = new \DateTime();
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
     * Set subscription
     *
     * @param string $subscription
     *
     * @return Subscription_company
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return string
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set attachementDate
     *
     * @param \DateTime $attachementDate
     *
     * @return Subscription_company
     */
    public function setAttachementDate($attachementDate)
    {
        $this->attachementDate = $attachementDate;

        return $this;
    }

    /**
     * Get attachementDate
     *
     * @return \DateTime
     */
    public function getAttachementDate()
    {
        return $this->attachementDate;
    }

    /**
     * Set detachementDate
     *
     * @param \DateTime $detachementDate
     *
     * @return Subscription_company
     */
    public function setDetachementDate($detachementDate)
    {
        $this->detachementDate = $detachementDate;

        return $this;
    }

    /**
     * Get detachementDate
     *
     * @return \DateTime
     */
    public function getDetachementDate()
    {
        return $this->detachementDate;
    }

    /**
     * @return \Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param \Company $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function __toString()
    {
        return (string) $this->getCompany();
    }

}

