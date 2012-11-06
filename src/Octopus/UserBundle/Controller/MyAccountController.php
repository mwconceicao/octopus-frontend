<?php

namespace Octopus\UserBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;

use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MyAccountController extends Controller
{
	/**
	 * @Route("/", name="_myaccount_index")
	 * @Template()
	 */
	public function indexAction()
	{
		return array();
	}
	
	/**
	 * @Route("/change-password", name="_myaccount_changepassword")
	 * @Template()
	 */
	public function changePasswordAction()
	{
		return array();
	}
	
	/**
	 * @Route("/change-password-submit", name="_myaccount_changepassword_submit")
	 * 
	 */
	public function changePasswordSubmitAction()
	{
		return array();
	}
}
