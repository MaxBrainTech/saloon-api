<?php
/**
 * AppModel
 *
 * PHP version 5
 * 
 */
App::uses('Model', 'Model'); 
class AppModel extends Model {

	/* validate alpha numeric */
	public function checkAlpha($data = null, $field=null){
		if(preg_match('/^[a-zA-Z_ -]+$/', $data[$field])){			
			return true;
		}
		else{
			return false;
		}
	}
	
	/* validate html and script tag */
	public function ripTags($data = null, $field = null) { 
	   
	    $string = preg_match_all ('/<[^>]*>/', trim($data[$field]), $data[$field]); 
	    if($string){
	    	return false;
	    }else{
	    	return true;
	    }
	    
	}
	/* validate alpha numeric */
	public function checkAlphaNumericDashUnderscore($data = null, $field=null){
		if(preg_match('/^[a-zA-Z0-9_ -]+$/', $data[$field])){			
			return true;
		}
		else{
			return false;
		}
	}
	/* validate alpha numeric */
	public function checkAlphaNumericDashUnderscoreExtra($data = null, $field=null){
		if(preg_match('/^[a-zA-Z0-9._ -]+$/', $data[$field])){			
			return true;
		}
		else{
			return false;
		}
	}
	/* validate alpha numeric comma fullstop*/
	public function checkAlphaNumericDashUnderscoreCommaFullstop($data = null, $field=null){
		if(preg_match('/^[a-zA-Z0-9.,_ -]+$/', $data[$field])){			
			return true;
		}
		else{
			return false;
		}
	}
	/* Validate with left and right white space*/
	public function checkWhiteSpace($data = null, $field=null){		
		if(substr($data[$field], -1, 1) == ' '){
				return false;				
		}   	
		
		if(substr($data[$field], 0, 1) == ' '){
			return false;    	 	
		}	
		return true;		
	}
	public function checkIntegerOrFloat($data = null, $field=null){		
		if(preg_match('/^[0-9.]+$/', $data[$field])){			
			return true;
		}
		else{
			return false;
		}		
	}
	public function checkInteger($data = null, $field=null){		
		if(preg_match('/^[0-9]+$/', $data[$field])){			
			return true;
		}
		else{
			return false;
		}		
	}
	
	/*
	* custom pagination count
	*/
	public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		$this->recursive = $recursive;
		$count = $this->find('count', array_merge($parameters, $extra));

		if (isset($extra['group'])) {

			$count = $this->getAffectedRows();

		}
		return $count;
    }
	
	/*
	* custom pagination
	*/	
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {	
		
		if(empty($order)){
			// great fix!
			$order = array($extra['passit']['sort'] => $extra['passit']['direction']);
		}
		if(isset($extra['group'])){
		  $group = $extra['group'];
		}		
		if(isset($extra['contain'])){
		  $contain = $extra['contain'];
		  $recursive = null;
		}
		
		if(isset($extra['joins'])){
			$joins = $extra['joins'];
		}
		
		return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain'));
	}
	/* unbind all model */
	public function unbindModelAll($model = null){
		$unbind = array();
		foreach ($this->belongsTo as $model=>$info)
		{
			$unbind['belongsTo'][] = $model;
		}
		foreach ($this->hasOne as $model=>$info)
		{
			$unbind['hasOne'][] = $model;
		}
		foreach ($this->hasMany as $model=>$info)
		{
			$unbind['hasMany'][] = $model;
		}
		foreach ($this->hasAndBelongsToMany as $model=>$info)
		{
			$unbind['hasAndBelongsToMany'][] = $model;
		}
		parent::unbindModel($unbind);
	} 
	
	/*change status*/
	function toggleStatus($id = null)
	{
		$this->id = $id;
		$status = $this->field('status');
		$status = $status?0:1;
		return $this->saveField('status',$status);
	}
	
	
	
	/*change verify*/
	function toggleVerify($id = null)
	{
		$this->id = $id;
		$status = $this->field('is_profile_verified');
		$status = $status?0:1;
		$this->saveField('is_profile_verified',$status);
		return $status;
	}
	/*change verify*/
	function toggleVerified($id = null)
	{
		$this->id = $id;
		$status = $this->field('verify');
           
		$status = $status?0:1;
                $this->saveField('verify',$status);
		return $status;
	}
	
	/*change feature*/
	function toggleFeatured($id = null)
	{
		$this->id = $id;
		$status = $this->field('is_featured');
		$status = $status?0:1;
		
		if($status==0){
			$next_date='0000-00-00';
			$type=0;
			
		}else{
			$next_date=date('Y-m-d', strtotime('+1 month'));
			$type=1;
		}
		
		$this->saveField('featured_type',$type);
		$this->saveField('featured_date',$next_date);
		$this->saveField('is_featured',$status);
		return $status;
	}
}