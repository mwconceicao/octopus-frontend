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
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	protected $email;
	
	/**
	 * @ORM\Column(type="string", length=32)
	 */
	protected $password;
	
	/**
	 * @ORM\Column(name="given_name", type="string", length=255)
	 */
	protected $givenName;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $surname;
	
	/**
	 * @ORM\Column(name="is_admin", type="boolean")
	 */
	protected $isAdmin;
	
	/**
	 * @inheritDoc
	 */
	public function getUsername()
	{
		return $this->email;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getPassword()
	{
		return $this->password;
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
