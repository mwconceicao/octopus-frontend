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
	public function changePasswordSubmitAction(Request $request)
	{
		$userId = $this->get('security.context')->getToken()->getUser()->getId();
		
		$newPassword         = $request->request->get('new_password');
		$newPasswordConfirm  = $request->request->get('new_password_confirm');
		
		if ($newPassword != $newPasswordConfirm) {
			//return $this->redirect($this->generateUrl('_myaccount_changepassword'));
			return $this->forward('OctopusUserBundle:MyAccount:changePassword', array('msg'=>'Password doesn\'t match.'));
		}
		
		$doctrine = $this->getDoctrine();
		
		$em = $doctrine->getEntityManager();
		$repository = $doctrine->getRepository('Octopus\UserBundle\Entity\User');
		
		$user = $repository->find($userId);
		$user->setPassword($newPassword);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('_welcome'));
	}
}
