<?php

namespace Octopus\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="`user`")
 * @ORM\Entity
 */
class User implements UserInterface
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	private $email;
	
	/**
	 * @ORM\Column(type="string", length=32)
	 */
	private $password;
	
	/**
	 * @ORM\Column(name="given_name", type="string", length=255)
	 */
	private $givenName;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $surname;
	
	/**
	 * @ORM\Column(name="is_admin", type="boolean")
	 */
	private $isAdmin;
	
	/**
	 * Return the user Id
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Return the user given name
	 */
	public function getGivenName()
	{
		return $this->givenName;
	}
	
	/**
	 * Set the given name
	 * @param string $givenName
	 */
	public function setGivenName($givenName)
	{
		$this->givenName = $givenName;
	}
	
	/**
	 * Return the user surname
	 */
	public function getSurname()
	{
		return $this->surname;
	}
	
	/**
	 * Set the surname
	 * @param unknown_type $surname
	 */
	public function setSurname($surname)
	{
		$this->surname = $surname;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getUsername()
	{
		return $this->email;
	}
	
	/**
	 * Set the username
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->email = $username;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getPassword()
	{
		return $this->password;
	}
	
	/**
	 * Set the password
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = md5($password);
	}
	
	/**
	 * Set admin flag
	 * @param unknown_type $isAdmin
	 */
	public function setIsAdmin($isAdmin)
	{
		$this->isAdmin = $isAdmin;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getRoles()
	{
		if ($this->isAdmin)
		{
			return array('ROLE_ADMIN');
		}
		
		return array('ROLE_USER');
	}
	
	/**
	 * @inheritDoc
	 */
	public function getSalt()
	{
		return null;
	}
	
	/**
	 * @inheritDoc
	 */
	public function eraseCredentials()
	{
		
	}
	
	/**
	 * @inheritDoc
	 */
	public function isEqualTo(UserInterface $user)
	{
		return $this->email === $user->getUsername();
	}
}
