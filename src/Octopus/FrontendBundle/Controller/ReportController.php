<?php

namespace Octopus\FrontendBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;

use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ReportController extends Controller
{
	const RESULTS_PER_PAGE = 100;
	
	private $columns = array(
			'timestamp'		=> array(
					'name' 			  => "Date & Time", 
					'checked'		  => false,
					'filter_selected' => false
					),
			'responseTime'  => array(
					'name'			  => "Response time",
					'checked'		  => false,
					'filter_selected' => false,
					'type'			  => "int",
					),
			'clientAddress' => array(
					'name' 			  => "Client address",
					'checked' 		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			'result'		=> array(
					'name'			  => "Proxy result status",
					'checked' 		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			'statusCode'	=> array(
					'name' 			  => "HTTP Status code",
					'checked'		  => false,
					'filter_selected' => false,
					'type'			  => "int",
					),
			'size'			=> array(
					'name' 			  => "Request size",
					'checked' 		  => false,
					'filter_selected' => false,
					'type'			  => "int",
					),
			'requestMethod' => array(
					'name' 			  => "Request HTTP Method",
					'checked' 		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			'uri'			=> array(
					'name'			  => "Web address",
					'checked'		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			'user'			=> array(
					'name' 			  => "Authenticated user",
					'checked' 		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			'peeringCode'	=> array(
					'name'			  => "Peering code",
					'checked' 		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			'peeringHost'	=> array(
					'name'			  => "Peering host",
					'checked' 		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			'contentType'	=> array(
					'name'			  => "Content type",
					'checked'		  => false,
					'filter_selected' => false,
					'type'			  => "string",
					),
			); 
	
	/**
	 * @Route("/", name="_report")
	 * @Template()
	 */
	public function indexAction()
	{
		return array();
	}
	
	/**
	 * @Route("/two-dimensional", name="_report_2d")
	 * @Template()
	 */
	public function twoDimensionalAction(Request $request)
	{
		// ###
		// Date & Time range
		$timestampRangeFrom = $request->query->get('timestamp_range_from');
		$timestampRangeTo	= $request->query->get('timestamp_range_to');
		
		if ($timestampRangeFrom == null || empty($timestampRangeFrom)) {
			$timestampRangeFrom	= new DateTime('today 00:00:00');
		} else {
			$timestampRangeFrom = new DateTime($timestampRangeFrom);
		}
		if ($timestampRangeTo == null || empty($timestampRangeTo)) {
			$timestampRangeTo = new DateTime('today 23:59:59');
		} else {
			$timestampRangeTo = new DateTime($timestampRangeTo);
		}
		// ###
		
		// ###
		// Show Columns
		$selectedColumns	= $request->query->get('show_columns', array('timestamp', 'clientAddress', 'uri'));
		$sqlSelectedColumns = array();
		
		foreach ($selectedColumns as $column)
		{
			$this->columns[$column]['checked'] = true;
			$sqlSelectedColumns[] = "r." . $column;
		}
		// ##

		// ###
		// Column filter
		$filterColumn 									 = $request->query->get('filter_column', 'uri');
		$filterText	  									 = $request->query->get('filter_text');
		$this->columns[$filterColumn]['filter_selected'] = true;
		// ###
		
		// Get data source
		$repository = $this->getDoctrine()->getRepository('Octopus\FrontendBundle\Entity\SquidAccessLogRequest');
		
		// ###
		// Get total results
		$query = $repository->createQueryBuilder('r')
			->select('count(r.timestamp)')
			->where('r.timestamp BETWEEN :timestamp_range_from AND :timestamp_range_to')
			->setParameter('timestamp_range_from', $timestampRangeFrom)
			->setParameter('timestamp_range_to',   $timestampRangeTo);
		
		if (!empty($filterText))
		{
			if ($this->columns[$filterColumn]['type'] == 'string')
			{
				$query = $query
				->andWhere('LOWER(r.'.$filterColumn.') LIKE :filter_text')
				->setParameter('filter_text', '%'.strtolower($filterText).'%');
			}
			elseif ($this->columns[$filterColumn]['type'] == 'int')
			{
				$query = $query
				->andWhere('r.'.$filterColumn.' = :filter_text')
				->setParameter('filter_text', (int)$filterText);
			}
		}
		
		$query = $query
		->getQuery();
		
		$pagination = array();
		$pagination['total']	  		= (int)$query->getSingleScalarResult();
		$pagination['page_count'] 		= (int) (1 + ($pagination['total'] / self::RESULTS_PER_PAGE));
		$pagination['actual_page']		= $request->query->get('page', '1');
		$pagination['page_previous']	= $pagination['actual_page'] - 1;
		$pagination['page_next']		= $pagination['actual_page'] + 1;
		$pagination['results_per_page'] = self::RESULTS_PER_PAGE;
		// ###
		
		// ###
		// Get data with pagination
		$pagination['first_result'] = self::RESULTS_PER_PAGE * ($pagination['actual_page'] - 1);
		
		$query = $repository->createQueryBuilder('r')
			->select($sqlSelectedColumns)
			->where('r.timestamp BETWEEN :timestamp_range_from AND :timestamp_range_to')
			->setParameter('timestamp_range_from', $timestampRangeFrom)
			->setParameter('timestamp_range_to',   $timestampRangeTo);
		
		if (!empty($filterText))
		{
			if ($this->columns[$filterColumn]['type'] == 'string')
			{
				$query = $query
					->andWhere('LOWER(r.'.$filterColumn.') LIKE :filter_text')
					->setParameter('filter_text', '%'.strtolower($filterText).'%');
			}
			elseif ($this->columns[$filterColumn]['type'] == 'int')
			{
				$query = $query
				->andWhere('r.'.$filterColumn.' = :filter_text')
				->setParameter('filter_text', (int)$filterText);
			}
		}
		
		$query = $query
			->orderBy('r.timestamp', 'ASC')
			->setFirstResult($pagination['first_result'])
			->setMaxResults(self::RESULTS_PER_PAGE)
			->getQuery();
		$result = $query->getResult();
		// ###
		
//  		echo "<pre>"; var_dump($pagination); echo "</pre>"; //die(); //DEBUG
		
		return array(
				'requests'					=> $result,
				'request_count'				=> count($result),
				'columns' 					=> $this->columns,
				'form_timestamp_range_from' => $timestampRangeFrom,
				'form_timestamp_range_to'	=> $timestampRangeTo,
				'form_filter_text'			=> $filterText,
				'pagination'				=> $pagination
				);
	}
	
	/**
	 * @Route("/three-dimensional", name="_report_3d")
	 * @Template()
	 */
	public function threeDimensionalAction(Request $request)
	{
		// ###
		// Date & Time range
		$timestampRangeFrom = $request->query->get('timestamp_range_from');
		$timestampRangeTo	= $request->query->get('timestamp_range_to');
		
		if ($timestampRangeFrom == null || empty($timestampRangeFrom)) {
			$timestampRangeFrom	= new DateTime('today 00:00:00');
		} else {
			$timestampRangeFrom = new DateTime($timestampRangeFrom);
		}
		if ($timestampRangeTo == null || empty($timestampRangeTo)) {
			$timestampRangeTo = new DateTime('today 23:59:59');
		} else {
			$timestampRangeTo = new DateTime($timestampRangeTo);
		}
		// ###
		
		// ###
		// Show Columns
		$selectedColumns	= $request->query->get('show_columns', array('timestamp', 'clientAddress', 'uri'));
		$sqlSelectedColumns = array();
		
		foreach ($selectedColumns as $column)
		{
			$this->columns[$column]['checked'] = true;
			$sqlSelectedColumns[] = "r." . $column;
		}
		// ##

		// ###
		// Column filter
		$filterColumn 									 = $request->query->get('filter_column', 'uri');
		$filterText	  									 = $request->query->get('filter_text');
		$this->columns[$filterColumn]['filter_selected'] = true;
		// ###
		
		// Get data source
		$repository = $this->getDoctrine()->getRepository('Octopus\FrontendBundle\Entity\SquidAccessLogRequest');
		
		// ###
		// Get total results
		$query = $repository->createQueryBuilder('r')
			->select('count(r.timestamp)')
			->where('r.timestamp BETWEEN :timestamp_range_from AND :timestamp_range_to')
			->setParameter('timestamp_range_from', $timestampRangeFrom)
			->setParameter('timestamp_range_to',   $timestampRangeTo);
		
		if (!empty($filterText))
		{
			if ($this->columns[$filterColumn]['type'] == 'string')
			{
				$query = $query
				->andWhere('LOWER(r.'.$filterColumn.') LIKE :filter_text')
				->setParameter('filter_text', '%'.strtolower($filterText).'%');
			}
			elseif ($this->columns[$filterColumn]['type'] == 'int')
			{
				$query = $query
				->andWhere('r.'.$filterColumn.' = :filter_text')
				->setParameter('filter_text', (int)$filterText);
			}
		}
		
		$query = $query
		->getQuery();
		
		$pagination = array();
		$pagination['total']	  		= (int)$query->getSingleScalarResult();
		$pagination['page_count'] 		= (int) (1 + ($pagination['total'] / self::RESULTS_PER_PAGE));
		$pagination['actual_page']		= $request->query->get('page', '1');
		$pagination['page_previous']	= $pagination['actual_page'] - 1;
		$pagination['page_next']		= $pagination['actual_page'] + 1;
		$pagination['results_per_page'] = self::RESULTS_PER_PAGE;
		// ###
		
		// ###
		// Get data with pagination
		$pagination['first_result'] = self::RESULTS_PER_PAGE * ($pagination['actual_page'] - 1);
		
		$query = $repository->createQueryBuilder('r')
			->select($sqlSelectedColumns)
			->where('r.timestamp BETWEEN :timestamp_range_from AND :timestamp_range_to')
			->setParameter('timestamp_range_from', $timestampRangeFrom)
			->setParameter('timestamp_range_to',   $timestampRangeTo);
		
		if (!empty($filterText))
		{
			if ($this->columns[$filterColumn]['type'] == 'string')
			{
				$query = $query
					->andWhere('LOWER(r.'.$filterColumn.') LIKE :filter_text')
					->setParameter('filter_text', '%'.strtolower($filterText).'%');
			}
			elseif ($this->columns[$filterColumn]['type'] == 'int')
			{
				$query = $query
				->andWhere('r.'.$filterColumn.' = :filter_text')
				->setParameter('filter_text', (int)$filterText);
			}
		}
		
		$query = $query
			->orderBy('r.timestamp', 'ASC')
			->setFirstResult($pagination['first_result'])
			->setMaxResults(self::RESULTS_PER_PAGE)
			->getQuery();
		$result = $query->getResult();
		// ###
		
//  		echo "<pre>"; var_dump($pagination); echo "</pre>"; //die(); //DEBUG
		
		return array(
				'requests'					=> $result,
				'request_count'				=> count($result),
				'columns' 					=> $this->columns,
				'form_timestamp_range_from' => $timestampRangeFrom,
				'form_timestamp_range_to'	=> $timestampRangeTo,
				'form_filter_text'			=> $filterText,
				'pagination'				=> $pagination
				);
	}
}