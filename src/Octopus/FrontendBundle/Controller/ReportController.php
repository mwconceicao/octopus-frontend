<?php

namespace Octopus\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ReportController extends Controller
{
	/**
	 * @Route("/", name="_report")
	 * @Template()
	 */
	public function indexAction()
	{
		//return $this->render('OctopusFrontendBundle:Home:index.html.twig');
		return array();
	}
	
	/**
	 * @Route("/two-dimensional", name="_report_2d")
	 * @Template()
	 */
	public function TwoDimensionalAction()
	{
		
	}
	
	/**
	 * @Route("/three-dimensional", name="_report_3d")
	 * @Template()
	 */
	public function ThreeDimensionalAction()
	{
		
	}
}