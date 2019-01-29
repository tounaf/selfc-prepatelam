<?php

namespace Telma\Selfcare\PrepaidBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;
use Telma\Selfcare\PrepaidBundle\Util;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Users
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Telma\Selfcare\PrepaidBundle\Repository\UserRepository")
 * @AttributeOverrides({
 *      @AttributeOverride(name="username",
 *          column=@ORM\Column(
 *              name     = "username",
 *              nullable = true,
 *          )
 *      ),
 *      @AttributeOverride(name="usernameCanonical",
 *          column=@ORM\Column(
 *              name     = "usernameCanonical",
 *              nullable = true,
 *          )
 *      )
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid_from", type="boolean", nullable=true)
     */
    private $validFrom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_valid", type="boolean", nullable=true)
     */
    private $isValid = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_admin", type="boolean", nullable=true)
     */
    private $isAdmin;

    /**
     * @ORM\ManyToMany(targetEntity="Company", cascade={"persist","remove","merge"})
     *
     */
    private $companies;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
//        $this->companies = new ArrayCollection();

    }

    public function addCompany(Company $companies)
    {
        $this->companies[] = $companies;

        return $this;
    }

    public function removeCompany(Company $companies)
    {
        $this->companies->removeElement($companies);
    }

    /**Get companies
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set validFrom
     *
     * @param boolean $validFrom
     *
     * @return User
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * Get validFrom
     *
     * @return boolean
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * Set isValid
     *
     * @param boolean $isValid
     *
     * @return User
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return boolean
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @ORM\PrePersist
     */
    function setEnabledUser()
    {
        $this->setEnabled(true);
    }

    /**
     * @ORM\PreUpdate
     */
    function updateEnabledUser()
    {
        $this->setEnabled($this->isValid);
    }



    public function __toString()
    {
        return (string) $this->getCompanies();
    }
}

