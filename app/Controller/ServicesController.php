<?php
/**
 * Services Controller
 *
 * PHP version 5.4
 *
 */
class ServicesController extends AppController{
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Services';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$this->loadModel('Service');
		$user_id = $this->Auth->User('id');
	}
	/*
	 * List all admin users in admin panel
	 */
	public function admin_index($defaultTab='All') {
		if(!isset($this->request->params['named']['page'])){
			$this->Session->delete('AdminSearch');
			$this->Session->delete('Url');
		}

		$filters_without_status = $filters = array();
		$number_of_record = Configure::read('App.AdminPageLimit');
		
		if(!empty($this->request->data)){
			$this->Session->delete('AdminSearch');
			$this->Session->delete('Url');
				
			App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
			if(!empty($this->request->data['Service']['name'])){
				$name = Sanitize::escape($this->request->data['Service']['name']);
				$this->Session->write('AdminSearch.name', $name);
			}
			if(isset($this->request->data['Service']['status']) && $this->request->data['Service']['status']!=''){
				$status = Sanitize::escape($this->request->data['Service']['status']);
				$this->Session->write('AdminSearch.status', $status);
				$defaultTab = Configure::read('Status.'.$status);
			}
				
				
		}

		if ($this->Session->check('number_of_record')) {
				$number_of_record = $this->Session->read('number_of_record');
				$this->request->data['Number']['number_of_record'] = $number_of_record;
			}
		$search_flag=0;
		if($this->Session->check('AdminSearch')){
			$keywords  = $this->Session->read('AdminSearch');
				
			foreach($keywords as $key=>$values){
				if($key == 'status'){
					$filters[] = array('Service.'.$key =>$values);
				}
				else{
				 $filters[] = array('Service.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('Service.'.$key.' LIKE'=>"%".$values."%");
				}
			}
				
			$search_flag=1;
		}
		$this->set(compact('search_flag','defaultTab'));

		//$filters[] = array('Service.role_id'=>Configure::read('App.Role.Admin'));
		$this->paginate = array(
				'Service'=>array(	
					'limit'=>$number_of_record, 
					'order'=>array('Service.id'=>'DESC'),
					'conditions'=>$filters,
					'recursive'=>1
					)
		);

		$data = $this->paginate('Service');
		//$parents = $this->Service->parentsList();

		$this->set(compact('data'));
		$this->set('title_for_layout',  __('Services', true));

		if(isset($this->request->params['named']['page']))
		$this->Session->write('Url.page', $this->request->params['named']['page']);
		if(isset($this->request->params['named']['sort']))
		$this->Session->write('Url.sort', $this->request->params['named']['sort']);
		if(isset($this->request->params['named']['direction']))
		$this->Session->write('Url.direction', $this->request->params['named']['direction']);
		$this->Session->write('Url.defaultTab', $defaultTab);

		if($this->request->is('ajax')){
			$this->render('ajax/admin_index');
		}else{
			$temp=$filters_without_status;
			$temp[] = array('Service.status'=>1);
			$active = $this->Service->find('count',array('conditions'=>$temp));
				
			$temp=$filters_without_status;
			$temp[] = array('Service.status'=>0);
			$inactive = $this->Service->find('count',array('conditions'=>$temp));
				
				
			$tabs = array('All'=>$active+$inactive, 'Active'=>$active,'Inactive'=>$inactive);
			$this->set(compact('tabs'));
		}
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			//check empty
			if(!empty($this->request->data))
			{
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				//validate user data
				// pr($this->request->data); die;
			$this->request->data['Service']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Service']['name']));
				$this->Service->set($this->request->data);
				$this->Service->setValidation('admin');
				if ($this->Service->validates()) {
					if ($this->Service->save($this->request->data)) {
						$this->Session->setFlash(__('Service has been added successfully'), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Service could not be added. Please, try again.'), 'admin_flash_error');
					}
				}
				else {
					$this->Session->setFlash('The Service could not be added.  Please, correct errors.', 'admin_flash_error');
				}
			}
		}
	}

	/**
	 * edit existing service
	 */
	/**
	 * edit existing admin
	 */
	public function admin_edit($id = null){

		$this->Service->id = $id;
		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Invalid Service'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
				
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$this->request->data['Service']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Service']['name']));
				//validate Service data
				$this->Service->set($this->request->data);
				$this->Service->setValidation('admin');
				if ($this->Service->validates()) {
					if ($this->Service->save($this->request->data)) 
					{
						$this->Session->setFlash(__('The Service information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} 
					else 
					{
						$this->Session->setFlash(__('The Service could not be saved. Please, try again.',true), 'admin_flash_error');
					}
				}
				else 
				{
					$this->Session->setFlash(__('The Service could not be saved. Please, correct errors.', true), 'admin_flash_error');
				}
			}
		}
		else {
			$this->request->data = $this->Service->read(null, $id);
		}
	}
	
	public function admin_delete($id = null) {
		$this->Service->id = $id;
		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Invalid service'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
		if ($this->Service->delete()) {
			$this->Session->setFlash(__('Service deleted successfully'), 'admin_flash_success');
			$this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Service was not deleted', 'admin_flash_error'));
		$this->redirect($this->referer());
	}

	/**
	 * toggle status existing user
	 */
	public function admin_status($id = null) {
		$this->Service->id = $id;
		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}

		if ($this->Service->toggleStatus($id)) {
			$this->Session->setFlash(__('Service status has been changed'), 'admin_flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Service status was not changed', 'admin_flash_error'));
		$this->redirect(array('action' => 'index'));
	}

	/*activate, deactivate and delete process*/
	public function admin_process(){
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}

		if(!empty($this->request->data)){
			App::uses('Sanitize', 'Utility');
			$action = Sanitize::escape($this->request->data['Service']['pageAction']);

			$ids = $this->request->data['Service']['id'];
				
			if (count($this->request->data) == 0 || $this->request->data['Service'] == null) {
				$this->Session->setFlash('No items selected.', 'admin_flash_error');
				$this->redirect($this->referer());
			}
				
			if($action == "delete"){
				$this->Service->deleteAll(array('Service.id'=>$ids));
				$this->Session->setFlash('Services have been deleted successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "activate"){
				$this->Service->updateAll(array('Service.status'=>Configure::read('App.Status.active')),array('Service.id'=>$ids));
					

				$this->Session->setFlash('Services have been activated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "deactivate"){
				$this->Service->updateAll(array('Service.status'=>Configure::read('App.Status.inactive')),array('Service.id'=>$ids));

				$this->Session->setFlash('Services have been deactivated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
		}
		else{
			$this->redirect(array('action'=>'index'));
		}
	}

	function referer($default = NULL, $local = false)
	{
		$defaultTab = $this->Session->read('Url.defaultTab');
		$page = $this->Session->read('Url.page');
		$sort = $this->Session->read('Url.sort');
		$direction = $this->Session->read('Url.direction');

		return Router::url(array('action'=>'index', $defaultTab,'page'=>$page,'sort'=>$sort,'direction'=>$direction),true);
	}
	
	/*FOR UPLOADING THE CAHRITY CSV FILE*/
	public function admin_uploadCsv(){
		if ($this->request->is('post')){
			if(!empty($this->request->data['Service']['csvfile']['name'])){
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$savedServices = $this->Service->find('list',array('fields'=>array('Service.id','Service.ein'),'conditions'=>array('Service.ein !=' => 'NULL')));
				/*CSV FILE UPLOADING CODE*/
				$error = array();
				$path_info = pathinfo($this->request->data['Service']['csvfile']['name']);
				if($path_info['extension'] !== 'csv'){
					$error[] = 'File must be with CSV extension.';
				}
				if($this->request->data['Service']['csvfile']['size'] > (10*1024*1024)){
					$error[] = 'File must be less than 10MB.';
				}
				if(!empty($error)){
					$this->Service->validationErrors['csvfile'] = $error;
					$this->Session->setFlash(__('Service CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
				}else{
					if(!is_dir(WWW_ROOT.'/uploads/serviceCSV')){
						mkdir(WWW_ROOT.'/uploads/serviceCSV',0777);
					}
					$fileName = 'serviceCSV.'.$path_info['extension'];
					$filePathWithName = WWW_ROOT.'/uploads/serviceCSV/'.$fileName;
					if(move_uploaded_file($this->request->data['Service']['csvfile']['tmp_name'],$filePathWithName)){
						/*READ THE CSV FILE*/
						$file = fopen($filePathWithName,"r");
						if($file !== false){
							while(!feof($file)){
								$rows[] = fgetcsv($file);
							}
							$count = count($rows);
							foreach($rows as $key => $row){
								if($key !== 0 && $key !== ($count - 1)){
									if(!in_array($row[0],$savedServices)){
										$service['Service'][$key]['ein'] = $row[0];
										$service['Service'][$key]['name'] = $row[1];
										$service['Service'][$key]['alias']= strtolower(preg_replace('/\s+/', '-', $row[1]));
									}
								}
							}
						}
						fclose($file);
						if(isset($service) && !empty($service)){
							if($this->Service->saveAll($service['Service'])){
								$this->Session->setFlash(__('Service CSV file has uploaded successfully.'), 'admin_flash_success');
								$this->redirect(array('action' => 'index'));	
							}else{
								$this->Session->setFlash(__('Service CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
							}
						}else{
							$this->Session->setFlash(__('Service CSV file has no new service.'), 'admin_flash_error');
							$this->redirect(array('action' => 'index'));
						}
					}else{
						$this->Session->setFlash(__('Service CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
					}
				}
			}else{
				$this->Session->setFlash('Fill the form by select the csv file.', 'admin_flash_error');
			}
		}
	}

/*
	 * List all services in user panel
	 */
	public function service_list($defaultTab='All') {

		$user_id = $this->Auth->User('id');
		// print_r($user_id);die;
        $count = $this->Service->find('count', array('conditions' => array('Service.user_id' => $user_id)));
        // echo $count;die; 
        
       $filters = array('Service.user_id' => $user_id);
        

        $this->paginate = array(
            'Service' => array(
                'order' => array('Service.id' => 'DESC'),
                'limit' => $count,
                'conditions' => $filters
             )
        );
        
        $data = $this->paginate('Service');
       // echo "<pre>";
        //print_r($data);die;
        $this->set(compact('data'));
        $this->set('title_for_layout', __('My Services', true));

        $this->layout = "dashboard";
	}

	public function add() {
		$this->layout = "dashboard";
		if ($this->request->is('post')) {
			//check empty
			if(!empty($this->request->data))
			{
				$this->request->data["Service"]["user_id"] = $this->Auth->User('id');

				$this->Service->set($this->request->data);
				$this->Service->setValidation('admin');
				if ($this->Service->validates()) {
					if ($this->Service->save($this->request->data)) {
						$this->Session->setFlash(__('Service has been added successfully'), 'flash_success');
						$this->redirect(array('action' => 'service_list'));
					} else {
						$this->Session->setFlash(__('The Service could not be added. Please, try again.'), 'flash_error');
					}
				}
				else {
					$this->Session->setFlash('The Service could not be added.  Please, correct errors.', 'flash_error');
				}
			}
		}
		$this->set('user_id', $this->Auth->User('id'));
	}

	/**
	 * edit existing service
	 */
	
	public function edit($id = null){

		$this->Service->id = $id;
		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Invalid Service'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
				
			if(!empty($this->request->data)) {
				// print_r($this->request->data);die;
				$this->request->data["Service"]["user_id"] = $this->Auth->User('id');
				$this->Service->set($this->request->data);
				$this->Service->setValidation('admin');
				if ($this->Service->validates()) {
					if ($this->Service->save($this->request->data)) 
					{
						$this->Session->setFlash(__('The Service information has been updated successfully',true), 'flash_success');
						$this->redirect('service_list');
					} 
					else 
					{
						$this->Session->setFlash(__('The Service could not be saved. Please, try again.',true), 'flash_error');
					}
				}
				else 
				{
					$this->Session->setFlash(__('The Service could not be saved. Please, correct errors.', true), 'flash_error');
				}
			}
		}
		else {
			$this->request->data = $this->Service->read(null, $id);
		}
		$this->layout = "dashboard";
	}
	
	public function delete($id = null) {
		$this->Service->id = $id;
		if (!$this->Service->exists()) {
			throw new NotFoundException(__('Invalid service'));
		}
		
		if ($this->Service->delete()) {
			$this->Session->setFlash(__('Service deleted successfully'), 'admin_flash_success');
			$this->redirect('list');
		}
		$this->Session->setFlash(__('Service was not deleted', 'flash_error'));
		$this->redirect('service_list');
	}

	public function status($id = null){
	// echo $id;die;
		if ($this->Service->toggleStatus($id)) {
			$this->Session->setFlash(__('Service status has been changed'), 'flash_success');
			$this->redirect(array('action' => 'service_list'));
		}
		
	}
	





}?>