<?php

namespace Octopus\FrontendBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;

use Octopus\FrontendBundle\Entity\ReportQuery;
use Octopus\UserBundle\Entity\User;
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
		$result = $this->getDoctrine()->getRepository('Octopus\FrontendBundle\Entity\ReportQuery')
			->findBy(array('user'=>$this->get('security.context')->getToken()->getUser()));
		
		return array('savedQueries' => $result);
	}
	
	public function aboutAction()
	{
		return $this->render('OctopusFrontendBundle:Home:about.html.twig');
	}
	
	/**
	 * @param Request $request
	 */
	public function saveReportQueryAction(Request $request)
	{
		$query = $request->query->get('query');
		$name  = $request->query->get('name');
		
		if (!empty($query) && !empty($name))
		{
			$reportQuery = new ReportQuery();
			$reportQuery->setUser($this->get('security.context')->getToken()->getUser());
			$reportQuery->setName($name);
			$reportQuery->setQuery($query);
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($reportQuery);
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl('_welcome'));
	}
	
	/**
	 * @param Request $request
	 */
	public function removeReportQueryAction(Request $request)
	{
		$reportQueryId = $request->query->get('reportQueryId');
		
		$doctrine = $this->getDoctrine();
		
		$em = $doctrine->getEntityManager();
		$repository = $doctrine->getRepository('Octopus\FrontendBundle\Entity\ReportQuery');
		
		$reportQuery = $repository->find($reportQueryId);
		
		$em->remove($reportQuery);
		$em->flush();
		
		return $this->redirect($this->generateUrl('_welcome'));
	}
}