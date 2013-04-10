<?php

namespace Octopus\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Octopus\UserBundle\Entity\User;

/**
 * Octopus\FrontendBundle\Entity\ReportQuery
 *
 * @ORM\Table(name="report_query")
 * @ORM\Entity
 */
class ReportQuery
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string $name
     * 
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Octopus\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var text $query
     *
     * @ORM\Column(name="query", type="text")
     */
    private $query;


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
     * @return ReportQuery
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
     * Set user
     *
     * @param User $user
     * @return ReportQuery
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set query
     *
     * @param text $query
     * @return ReportQuery
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Get query
     *
     * @return text 
     */
    public function getQuery()
    {
        return $this->query;
    }
}