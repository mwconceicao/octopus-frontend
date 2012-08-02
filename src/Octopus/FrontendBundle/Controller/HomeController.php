<?php

namespace Octopus\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
	/**
	 * @Template()
	 */
	public function indexAction()
	{
		//return $this->render('OctopusFrontendBundle:Home:index.html.twig');
		return array();
	}
	
	public function aboutAction()
	{
		return $this->render('OctopusFrontendBundle:Home:about.html.twig');
	}
}