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
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'responseTime'  => array(
					'name'			  => "Response time",
					'type'			  => "int",
					'checked'		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'clientAddress' => array(
					'name' 			  => "Client address",
					'type'			  => "string",
					'checked' 		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'result'		=> array(
					'name'			  => "Proxy result status",
					'type'			  => "string",
					'checked' 		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'statusCode'	=> array(
					'name' 			  => "HTTP Status code",
					'type'			  => "int",
					'checked'		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'size'			=> array(
					'name' 			  => "Request size",
					'type'			  => "int",
					'checked' 		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'requestMethod' => array(
					'name' 			  => "Request HTTP Method",
					'type'			  => "string",
					'checked' 		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'uri'			=> array(
					'name'			  => "Web address",
					'type'			  => "string",
					'checked'		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'user'			=> array(
					'name' 			  => "Authenticated user",
					'type'			  => "string",
					'checked' 		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'peeringCode'	=> array(
					'name'			  => "Peering code",
					'type'			  => "string",
					'checked' 		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'peeringHost'	=> array(
					'name'			  => "Peering host",
					'type'			  => "string",
					'checked' 		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
					),
			'contentType'	=> array(
					'name'			  => "Content type",
					'type'			  => "string",
					'checked'		  => false,
					'filter_selected' => false,
					'order_by'		  => false,
					'pivot_selected'  => 0,
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
		
		// ###
		// Order by
		$orderByColumn	= $request->query->get('order_by_column', 'timestamp');
		$orderByType	= $request->query->get('order_by_type',   'ASC');
		$this->columns[$orderByColumn]['order_by'] = true;
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
			->orderBy('r.'.$orderByColumn, $orderByType)
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
				'form_order_by_column'		=> $orderByColumn,
				'form_order_by_type'		=> $orderByType,
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
		$showColumns	 = $request->query->get('show_columns', array('timestamp', 'uri', 'contentType'));
		$selectedColumns = array();
		
		foreach ($showColumns as $column)
		{
			$this->columns[$column]['checked'] = true;
			$selectedColumns[] = "r." . $column;
		}
		// ##

		// ###
		// Column filter
		$filterColumn 									 = $request->query->get('filter_column', 'uri');
		$filterText	  									 = $request->query->get('filter_text');
		$this->columns[$filterColumn]['filter_selected'] = true;
		// ###
		
		// ###
		// Pivot One & Two
		$pivotOne = $request->query->get('pivot_one', 'clientAddress');
		$pivotTwo = $request->query->get('pivot_two', 'statusCode');
		
		$this->columns[$pivotOne]['pivot_selected'] = 1;
		$this->columns[$pivotTwo]['pivot_selected'] = 2;
		
		$sqlSelectedColumns = array_merge(array('r.' . $pivotOne, 'r.' . $pivotTwo), $selectedColumns);
		
		$pivotOneOrder = $request->query->get('pivot_one_order', 'ASC');
		$pivotTwoOrder = $request->query->get('pivot_two_order', 'DESC');
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
			->orderBy('r.' . $pivotOne, $pivotOneOrder)
			->addOrderBy('r.' . $pivotTwo, $pivotTwoOrder)
			->addOrderBy('r.timestamp','ASC')
			->setFirstResult($pagination['first_result'])
			->setMaxResults(self::RESULTS_PER_PAGE)
			->getQuery();
		$result = $query->getResult();
		// ###
		
		$controlPivotOne = array();
		$controlPivotTwo = array();
		foreach ($result as $key => $req)
		{
			if (!isset($controlPivotOne[$req[$pivotOne]]['count']))
				$controlPivotOne[$req[$pivotOne]]['count'] = 1;
			else
				$controlPivotOne[$req[$pivotOne]]['count']++;
			
			if (!isset($controlPivotTwo[$req[$pivotTwo]]['count']))
				$controlPivotTwo[$req[$pivotTwo]]['count'] = 1;
			else
				$controlPivotTwo[$req[$pivotTwo]]['count']++;
		}
		
 		//echo "<pre>"; var_dump($controlPivotTwo); echo "</pre>"; //die(); //DEBUG
		
		return array(
				'requests'					=> $result,
				'request_count'				=> count($result),
				'columns' 					=> $this->columns,
				'form_timestamp_range_from' => $timestampRangeFrom,
				'form_timestamp_range_to'	=> $timestampRangeTo,
				'form_filter_text'			=> $filterText,
				'form_pivot_one'			=> $pivotOne,
				'form_pivot_two'			=> $pivotTwo,
				'form_pivot_one_order'		=> $pivotOneOrder,
				'form_pivot_two_order'		=> $pivotTwoOrder,
				'control_pivot_one'			=> $controlPivotOne,
				'control_pivot_two'			=> $controlPivotTwo,
				'pagination'				=> $pagination
				);
	}
}