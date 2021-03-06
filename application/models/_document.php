<?php
/**
 * This class manages document. 
 *
 * @author Al Zziwa <azziwa@newwavetech.co.ug>
 * @version 1.0.0
 * @copyright PSS
 * @created 11/24/2015
 */
class _document extends CI_Model
{
	# advanced search list of documents
	function lists($type, $scope=array('pde'=>'', 'category'=>'', 'phrase'=>'', 'status'=>'', 'offset'=>'0', 'limit'=>NUM_OF_ROWS_PER_PAGE))
	{
		if(!empty($scope['pde'])) $pde = $scope['pde'];
		else if($this->native_session->get('__user_type') == 'pde') $pde = $this->native_session->get('__organization_id');
		
		
		return $this->_query_reader->get_list('get_document_list', array(
			'category_condition'=>(!empty($scope['category'])? " AND category='".$scope['category']."' ": ''),
			
			'phrase_condition'=>(!empty($scope['phrase'])? " AND name LIKE '%".htmlentities($scope['phrase'], ENT_QUOTES)."%' ": ''),
			
			'type_condition'=>(!empty($type)? " AND document_type='".$type."' ": ''),
			
			'status_condition'=>" AND status IN ('".($this->native_session->get('__user_type') == 'admin'? "active','inactive": "active")."') ",
			
			'owner_condition'=>(!empty($pde)? " AND _entered_by_organization='".$pde."' ": ''),
			
			'limit_text'=>" LIMIT ".$scope['offset'].",".$scope['limit']." "
		));
	}
	
	
	
	
	
	# add a document
	function add($data)
	{
		$result = FALSE;
		$reason = '';
		
		# add the document record
		$result = $this->_query_reader->run('add_document_record', array(
				'name'=>htmlentities($data['name'], ENT_QUOTES), 
				'url'=>$data['document'],  
				'size'=>filesize(UPLOAD_DIRECTORY.$data['document']),
				'description'=>(!empty($data['description'])? htmlentities($data['description'], ENT_QUOTES): ''), 
				'document_type'=>$data['document_type'],
				'category'=>$data['document__publicdocumenttypes'], 
				'is_removable'=>'Y',
				'parent_id'=>$this->native_session->get('__organization_id'),
				'parent_type'=>'organization',
				'tracking_number'=>strtok(end(explode('_',$data['document'])), '.'), # the number part in the url
				'user_id'=>$this->native_session->get('__user_id'), 
				'organization_id'=>$this->native_session->get('__organization_id')
		));
		
		# log action
		$this->_logger->add_event(array(
			'user_id'=>$this->native_session->get('__user_id'), 
			'activity_code'=>'add_document', 
			'result'=>($result? 'SUCCESS': 'FAIL'), 
			'log_details'=>"device=".get_user_device()."|browser=".$this->agent->browser(),
			'uri'=>uri_string(),
			'ip_address'=>get_ip_address()
		));
		
		return array('boolean'=>$result, 'reason'=>$reason);
	}
	
	
	
	
	
	
	
	
	# get details of a document
	function details($id)
	{
		return $this->_query_reader->get_row_as_array('get_document_list', array('category_condition'=>'', 'phrase_condition'=>'',
			'type_condition'=>" AND id='".$id."' ", 'status_condition'=>'', 'owner_condition'=>'','limit_text'=>" LIMIT 1 "));
	}
	
	
	
	
	
	
	# update document status
	function update_status($newStatus, $documentIds)
	{
		$msg = '';
		$documents = implode("','",$documentIds);
		$status = array('archive'=>'inactive', 'reactivate'=>'active');
		
		# Remove the document record completely
		if($newStatus == 'delete'){
			foreach($documentIds AS $id) {
				$document = $this->details($id);
				if(!empty($document['url'])) @unlink(UPLOAD_DIRECTORY.$document['url']);
			}
			
			$result = $this->_query_reader->run('delete_document_record', array('document_ids'=>$documents));
		}
		
		# Simply change the status
		else {
			$result = $this->_query_reader->run('update_document_status', array('new_status'=>$status[$newStatus], 'document_ids'=>$documents, 'user_id'=>$this->native_session->get('__user_id') ));
		}
		
		
		# log action
		$this->_logger->add_event(array(
			'user_id'=>$this->native_session->get('__user_id'), 
			'activity_code'=>'document_status_change', 
			'result'=>($result? 'SUCCESS': 'FAIL'), 
			'log_details'=>"newstatus=".$newStatus."|device=".get_user_device()."|browser=".$this->agent->browser(),
			'uri'=>uri_string(),
			'ip_address'=>get_ip_address()
		));
		
		return array('boolean'=>$result, 'reason'=>$msg);
	}
	
	
	
	
	
	
	
	
	# get document statistics
	function statistics($field)
	{
		if($field == 'latest_date') {
			$row = $this->_query_reader->get_row_as_array('get_document_latest_date');
			return !empty($row[$field]) && strpos($row[$field], '0000-00-00') === FALSE? $row[$field]: '';
		}
		
		# if not found in preset fields, return empty string
		return '';
	}
	
	
	
	
	# verify a document that was generated by this system
	function verify($data)
	{
		$document = $this->_query_reader->get_row_as_array('get_'.$data['documenttype__documenttypes'], array('tracking_number'=>$data['trackingnumber']));
		
		return array('boolean'=>(!empty($document) && strtotime('now') < strtotime($document['expiry_date'])? TRUE: FALSE), 'expiry_date'=>(!empty($document)? $document['expiry_date']: ''));
	}
	
}


?>