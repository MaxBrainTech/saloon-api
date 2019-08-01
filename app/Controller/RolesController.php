<?php
/**
 * Roles Controller
 *
 * PHP version 5.4
 *
 */
class RolesController extends AppController{
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Roles';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$this->loadModel('Role');
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
			
			if(!empty($this->request->data['Role']['name'])){
				$name = Sanitize::escape($this->request->data['Role']['name']);
				$this->Session->write('AdminSearch.name', $name);
			}
			if(isset($this->request->data['Role']['status']) && $this->request->data['Role']['status']!=''){
				$status = Sanitize::escape($this->request->data['Role']['status']);
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
					$filters[] = array('Role.'.$key =>$values);
				}
				else{
				 $filters[] = array('Role.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('Role.'.$key.' LIKE'=>"%".$values."%");
				}
			}
				
			$search_flag=1;
		}
		$this->set(compact('search_flag','defaultTab'));

		//$filters[] = array('Role.role_id'=>Configure::read('App.Role.Admin'));
		$this->paginate = array(
				'Role'=>array(	
					'limit'=>$number_of_record, 
					'order'=>array('Role.id'=>'DESC'),
					'conditions'=>$filters,
					'recursive'=>1
					)
		);

		$data = $this->paginate('Role');
		//$parents = $this->Role->parentsList();

		$this->set(compact('data'));
		$this->set('title_for_layout',  __('Roles', true));

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
			$temp[] = array('Role.status'=>1);
			$active = $this->Role->find('count',array('conditions'=>$temp));
				
			$temp=$filters_without_status;
			$temp[] = array('Role.status'=>0);
			$inactive = $this->Role->find('count',array('conditions'=>$temp));
				
				
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
			$this->request->data['Role']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Role']['name']));
				$this->Role->set($this->request->data);
				$this->Role->setValidation('admin');
				if ($this->Role->validates()) {
					if ($this->Role->save($this->request->data)) {
						$this->Session->setFlash(__('Role has been added successfully'), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Role could not be added. Please, try again.'), 'admin_flash_error');
					}
				}
				else {
					$this->Session->setFlash('The Role could not be added.  Please, correct errors.', 'admin_flash_error');
				}
			}
		}
	}

	/**
	 * edit existing role
	 */
	/**
	 * edit existing admin
	 */
	public function admin_edit($id = null){

		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid Role'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
				
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$this->request->data['Role']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Role']['name']));
				//validate Role data
				$this->Role->set($this->request->data);
				$this->Role->setValidation('admin');
				if ($this->Role->validates()) {
					if ($this->Role->save($this->request->data)) 
					{
						$this->Session->setFlash(__('The Role information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} 
					else 
					{
						$this->Session->setFlash(__('The Role could not be saved. Please, try again.',true), 'admin_flash_error');
					}
				}
				else 
				{
					$this->Session->setFlash(__('The Role could not be saved. Please, correct errors.', true), 'admin_flash_error');
				}
			}
		}
		else {
			$this->request->data = $this->Role->read(null, $id);
		}
	}
	
	public function admin_delete($id = null) {
		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid role'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
		if ($this->Role->delete()) {
			$this->Session->setFlash(__('Role deleted successfully'), 'admin_flash_success');
			$this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Role was not deleted', 'admin_flash_error'));
		$this->redirect($this->referer());
	}

	/**
	 * toggle status existing user
	 */
	public function admin_status($id = null) {
		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}

		if ($this->Role->toggleStatus($id)) {
			$this->Session->setFlash(__('Role status has been changed'), 'admin_flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Role status was not changed', 'admin_flash_error'));
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
			$action = Sanitize::escape($this->request->data['Role']['pageAction']);

			$ids = $this->request->data['Role']['id'];
				
			if (count($this->request->data) == 0 || $this->request->data['Role'] == null) {
				$this->Session->setFlash('No items selected.', 'admin_flash_error');
				$this->redirect($this->referer());
			}
				
			if($action == "delete"){
				$this->Role->deleteAll(array('Role.id'=>$ids));
				$this->Session->setFlash('Roles have been deleted successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "activate"){
				$this->Role->updateAll(array('Role.status'=>Configure::read('App.Status.active')),array('Role.id'=>$ids));
					

				$this->Session->setFlash('Roles have been activated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "deactivate"){
				$this->Role->updateAll(array('Role.status'=>Configure::read('App.Status.inactive')),array('Role.id'=>$ids));

				$this->Session->setFlash('Roles have been deactivated successfully', 'admin_flash_success');
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
			if(!empty($this->request->data['Role']['csvfile']['name'])){
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$savedRoles = $this->Role->find('list',array('fields'=>array('Role.id','Role.ein'),'conditions'=>array('Role.ein !=' => 'NULL')));
				/*CSV FILE UPLOADING CODE*/
				$error = array();
				$path_info = pathinfo($this->request->data['Role']['csvfile']['name']);
				if($path_info['extension'] !== 'csv'){
					$error[] = 'File must be with CSV extension.';
				}
				if($this->request->data['Role']['csvfile']['size'] > (10*1024*1024)){
					$error[] = 'File must be less than 10MB.';
				}
				if(!empty($error)){
					$this->Role->validationErrors['csvfile'] = $error;
					$this->Session->setFlash(__('Role CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
				}else{
					if(!is_dir(WWW_ROOT.'/uploads/roleCSV')){
						mkdir(WWW_ROOT.'/uploads/roleCSV',0777);
					}
					$fileName = 'roleCSV.'.$path_info['extension'];
					$filePathWithName = WWW_ROOT.'/uploads/roleCSV/'.$fileName;
					if(move_uploaded_file($this->request->data['Role']['csvfile']['tmp_name'],$filePathWithName)){
						/*READ THE CSV FILE*/
						$file = fopen($filePathWithName,"r");
						if($file !== false){
							while(!feof($file)){
								$rows[] = fgetcsv($file);
							}
							$count = count($rows);
							foreach($rows as $key => $row){
								if($key !== 0 && $key !== ($count - 1)){
									if(!in_array($row[0],$savedRoles)){
										$role['Role'][$key]['ein'] = $row[0];
										$role['Role'][$key]['name'] = $row[1];
										$role['Role'][$key]['alias']= strtolower(preg_replace('/\s+/', '-', $row[1]));
									}
								}
							}
						}
						fclose($file);
						if(isset($role) && !empty($role)){
							if($this->Role->saveAll($role['Role'])){
								$this->Session->setFlash(__('Role CSV file has uploaded successfully.'), 'admin_flash_success');
								$this->redirect(array('action' => 'index'));	
							}else{
								$this->Session->setFlash(__('Role CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
							}
						}else{
							$this->Session->setFlash(__('Role CSV file has no new role.'), 'admin_flash_error');
							$this->redirect(array('action' => 'index'));
						}
					}else{
						$this->Session->setFlash(__('Role CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
					}
				}
			}else{
				$this->Session->setFlash('Fill the form by select the csv file.', 'admin_flash_error');
			}
		}
	}
}?>