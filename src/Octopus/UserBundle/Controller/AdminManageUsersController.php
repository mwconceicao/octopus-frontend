<?php

namespace Octopus\UserBundle\Controller;

use Octopus\UserBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminManageUsersController extends Controller
{
	/**
	 * @Route("/", name="_admin_manage_users_index")
	 * @Template()
	 */
	public function indexAction()
	{
		$repository = $this->getDoctrine()->getRepository('Octopus\UserBundle\Entity\User');
		$results 	= $repository->findAll();
		
		return array('users' => $results);
	}
	
	/**
	 * @Route("/create", name="_admin_manage_users_create")
	 * @Template()
	 */
	public function createAction()
	{
		return array();
	}
	
	/**
	 * @Route("/create-submit", name="_admin_manage_users_create_submit")
	 * @Template()
	 */
	public function createSubmitAction(Request $request)
	{
		$givenName = $request->request->get('given_name');
		$surname   = $request->request->get('surname');
		$email     = $request->request->get('email');
		$password  = $request->request->get('password');
		$isAdmin   = $request->request->get('is_admin');
		
		if ($isAdmin == "on") {
			$isAdmin = true;
		} else {
			$isAdmin = false;
		}
		
		$user = new User();
		$user->setGivenName($givenName);
		$user->setSurname($surname);
		$user->setUsername($email);
		$user->setPassword($password);
		$user->setIsAdmin($isAdmin);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($user);
		$em->flush();
		
		return $this->redirect($this->generateUrl('_admin_manage_users_index'));
	}
	
	/**
	 * @Route("/delete", name="_admin_manage_users_delete")
	 * @Template()
	 */
	public function deleteAction(Request $request)
	{
		$userId = $request->query->get('user_id');
		
		$doctrine = $this->getDoctrine();
		
		$em = $doctrine->getEntityManager();		
		$repository = $doctrine->getRepository('Octopus\UserBundle\Entity\User');
		
		$user = $repository->find($userId);
		
		$em->remove($user);
		$em->flush();
		
		return $this->redirect($this->generateUrl('_admin_manage_users_index'));
	}
	
	/**
	 * @Route("/edit", name="_admin_manage_users_edit")
	 * @Template()
	 */
	public function editAction(Request $request)
	{
		$userId = $request->query->get('user_id');
		
		$repository = $this->getDoctrine()->getRepository('Octopus\UserBundle\Entity\User');
		
		$user = $repository->find($userId);
		
		return array('user' => $user);
	}
	
	/**
	 * @Route("/edit-submit", name="_admin_manage_users_edit_submit")
	 * @Template()
	 */
	public function editSubmitAction(Request $request)
	{
		$userId    = $request->request->get('user_id'); 
		$givenName = $request->request->get('given_name');
		$surname   = $request->request->get('surname');
		$email     = $request->request->get('email');
		$password  = $request->request->get('password');
		$isAdmin   = $request->request->get('is_admin');
		
		if ($isAdmin == "on") {
			$isAdmin = true;
		} else {
			$isAdmin = false;
		}
		
		$doctrine = $this->getDoctrine();
		
		$em = $doctrine->getEntityManager();
		$repository = $doctrine->getRepository('Octopus\UserBundle\Entity\User');
		
		$user = $repository->find($userId);
		
		$user->setGivenName($givenName);
		$user->setSurname($surname);
		$user->setUsername($email);
		$user->setIsAdmin($isAdmin);
		
		if (!empty($password)) {
			$user->setPassword($password);
		}
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('_admin_manage_users_index'));
	}
}
