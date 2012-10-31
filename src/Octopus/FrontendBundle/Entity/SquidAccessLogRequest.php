<?php

namespace Octopus\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="squid_access_log_request")
 */
class SquidAccessLogRequest
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $timestamp;
	
	/**
	 * @ORM\Column(name="response_time", type="integer")
	 */
	private $responseTime;
	
	/**
	 * @ORM\Column(name="client_address", type="string", length=255)
	 */
	private $clientAddress;
	
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $result;
	
	/**
	 * @ORM\Column(name="status_code", type="integer")
	 */
	private $statusCode;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $size;
	
	/**
	 * @ORM\Column(name="request_method", type="string", length=10)
	 */
	private $requestMethod;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $uri;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $user;
	
	/**
	 * @ORM\Column(name="peering_code", type="string", length=100)
	 */
	private $peeringCode;
	
	/**
	 * @ORM\Column(name="peering_host", type="string", length=15)
	 */
	private $peeringHost;
	
	/**
	 * @ORM\Column(name="content_type", type="string", length=255)
	 */
	private $contentType;
	
		

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
     * Set timestamp
     *
     * @param timestamp $timestamp
     * @return SquidAccessLogRequest
     */
    public function setTimestamp(\timestamp $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Get timestamp
     *
     * @return timestamp 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set responseTime
     *
     * @param integer $responseTime
     * @return SquidAccessLogRequest
     */
    public function setResponseTime($responseTime)
    {
        $this->responseTime = $responseTime;
        return $this;
    }

    /**
     * Get responseTime
     *
     * @return integer 
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * Set clientAddress
     *
     * @param string $clientAddress
     * @return SquidAccessLogRequest
     */
    public function setClientAddress($clientAddress)
    {
        $this->clientAddress = $clientAddress;
        return $this;
    }

    /**
     * Get clientAddress
     *
     * @return string 
     */
    public function getClientAddress()
    {
        return $this->clientAddress;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return SquidAccessLogRequest
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set statusCode
     *
     * @param integer $statusCode
     * @return SquidAccessLogRequest
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Get statusCode
     *
     * @return integer 
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return SquidAccessLogRequest
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set requestMethod
     *
     * @param string $requestMethod
     * @return SquidAccessLogRequest
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * Get requestMethod
     *
     * @return string 
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Set uri
     *
     * @param text $uri
     * @return SquidAccessLogRequest
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get uri
     *
     * @return text 
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return SquidAccessLogRequest
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set peeringCode
     *
     * @param string $peeringCode
     * @return SquidAccessLogRequest
     */
    public function setPeeringCode($peeringCode)
    {
        $this->peeringCode = $peeringCode;
        return $this;
    }

    /**
     * Get peeringCode
     *
     * @return string 
     */
    public function getPeeringCode()
    {
        return $this->peeringCode;
    }

    /**
     * Set peeringHost
     *
     * @param string $peeringHost
     * @return SquidAccessLogRequest
     */
    public function setPeeringHost($peeringHost)
    {
        $this->peeringHost = $peeringHost;
        return $this;
    }

    /**
     * Get peeringHost
     *
     * @return string 
     */
    public function getPeeringHost()
    {
        return $this->peeringHost;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     * @return SquidAccessLogRequest
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * Get contentType
     *
     * @return string 
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}