<?php
/**
 * Colors Controller
 *
 * PHP version 5.4
 *
 */
class ColorsController extends AppController{
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Colors';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$this->loadModel('Color');
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
			
			if(!empty($this->request->data['Color']['name'])){
				$name = Sanitize::escape($this->request->data['Color']['name']);
				$this->Session->write('AdminSearch.name', $name);
			}
			if(isset($this->request->data['Color']['status']) && $this->request->data['Color']['status']!=''){
				$status = Sanitize::escape($this->request->data['Color']['status']);
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
					$filters[] = array('Color.'.$key =>$values);
				}
				else{
				 $filters[] = array('Color.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('Color.'.$key.' LIKE'=>"%".$values."%");
				}
			}
				
			$search_flag=1;
		}
		$this->set(compact('search_flag','defaultTab'));

		//$filters[] = array('Color.role_id'=>Configure::read('App.Role.Admin'));
		$this->paginate = array(
				'Color'=>array(	
					'limit'=>$number_of_record, 
					'order'=>array('Color.id'=>'DESC'),
					'conditions'=>$filters,
					'recursive'=>1
					)
		);

		$data = $this->paginate('Color');
		//$parents = $this->Color->parentsList();

		$this->set(compact('data'));
		$this->set('title_for_layout',  __('Colors', true));

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
			$temp[] = array('Color.status'=>1);
			$active = $this->Color->find('count',array('conditions'=>$temp));
				
			$temp=$filters_without_status;
			$temp[] = array('Color.status'=>0);
			$inactive = $this->Color->find('count',array('conditions'=>$temp));
				
				
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
			$this->request->data['Color']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Color']['name']));
				$this->Color->set($this->request->data);
				$this->Color->setValidation('admin');
				if ($this->Color->validates()) {
					if ($this->Color->save($this->request->data)) {
						$this->Session->setFlash(__('Color has been added successfully'), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Color could not be added. Please, try again.'), 'admin_flash_error');
					}
				}
				else {
					$this->Session->setFlash('The Color could not be added.  Please, correct errors.', 'admin_flash_error');
				}
			}
		}
	}

	/**
	 * edit existing color
	 */
	/**
	 * edit existing admin
	 */
	public function admin_edit($id = null){

		$this->Color->id = $id;
		if (!$this->Color->exists()) {
			throw new NotFoundException(__('Invalid Color'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
				
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$this->request->data['Color']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Color']['name']));
				//validate Color data
				$this->Color->set($this->request->data);
				$this->Color->setValidation('admin');
				if ($this->Color->validates()) {
					if ($this->Color->save($this->request->data)) 
					{
						$this->Session->setFlash(__('The Color information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} 
					else 
					{
						$this->Session->setFlash(__('The Color could not be saved. Please, try again.',true), 'admin_flash_error');
					}
				}
				else 
				{
					$this->Session->setFlash(__('The Color could not be saved. Please, correct errors.', true), 'admin_flash_error');
				}
			}
		}
		else {
			$this->request->data = $this->Color->read(null, $id);
		}
	}
	
	public function admin_delete($id = null) {
		$this->Color->id = $id;
		if (!$this->Color->exists()) {
			throw new NotFoundException(__('Invalid color'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
		if ($this->Color->delete()) {
			$this->Session->setFlash(__('Color deleted successfully'), 'admin_flash_success');
			$this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Color was not deleted', 'admin_flash_error'));
		$this->redirect($this->referer());
	}

	/**
	 * toggle status existing user
	 */
	public function admin_status($id = null) {
		$this->Color->id = $id;
		if (!$this->Color->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}

		if ($this->Color->toggleStatus($id)) {
			$this->Session->setFlash(__('Color status has been changed'), 'admin_flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Color status was not changed', 'admin_flash_error'));
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
			$action = Sanitize::escape($this->request->data['Color']['pageAction']);

			$ids = $this->request->data['Color']['id'];
				
			if (count($this->request->data) == 0 || $this->request->data['Color'] == null) {
				$this->Session->setFlash('No items selected.', 'admin_flash_error');
				$this->redirect($this->referer());
			}
				
			if($action == "delete"){
				$this->Color->deleteAll(array('Color.id'=>$ids));
				$this->Session->setFlash('Colors have been deleted successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "activate"){
				$this->Color->updateAll(array('Color.status'=>Configure::read('App.Status.active')),array('Color.id'=>$ids));
					

				$this->Session->setFlash('Colors have been activated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "deactivate"){
				$this->Color->updateAll(array('Color.status'=>Configure::read('App.Status.inactive')),array('Color.id'=>$ids));

				$this->Session->setFlash('Colors have been deactivated successfully', 'admin_flash_success');
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
			if(!empty($this->request->data['Color']['csvfile']['name'])){
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$savedColors = $this->Color->find('list',array('fields'=>array('Color.id','Color.ein'),'conditions'=>array('Color.ein !=' => 'NULL')));
				/*CSV FILE UPLOADING CODE*/
				$error = array();
				$path_info = pathinfo($this->request->data['Color']['csvfile']['name']);
				if($path_info['extension'] !== 'csv'){
					$error[] = 'File must be with CSV extension.';
				}
				if($this->request->data['Color']['csvfile']['size'] > (10*1024*1024)){
					$error[] = 'File must be less than 10MB.';
				}
				if(!empty($error)){
					$this->Color->validationErrors['csvfile'] = $error;
					$this->Session->setFlash(__('Color CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
				}else{
					if(!is_dir(WWW_ROOT.'/uploads/colorCSV')){
						mkdir(WWW_ROOT.'/uploads/colorCSV',0777);
					}
					$fileName = 'colorCSV.'.$path_info['extension'];
					$filePathWithName = WWW_ROOT.'/uploads/colorCSV/'.$fileName;
					if(move_uploaded_file($this->request->data['Color']['csvfile']['tmp_name'],$filePathWithName)){
						/*READ THE CSV FILE*/
						$file = fopen($filePathWithName,"r");
						if($file !== false){
							while(!feof($file)){
								$rows[] = fgetcsv($file);
							}
							$count = count($rows);
							foreach($rows as $key => $row){
								if($key !== 0 && $key !== ($count - 1)){
									if(!in_array($row[0],$savedColors)){
										$color['Color'][$key]['ein'] = $row[0];
										$color['Color'][$key]['name'] = $row[1];
										$color['Color'][$key]['alias']= strtolower(preg_replace('/\s+/', '-', $row[1]));
									}
								}
							}
						}
						fclose($file);
						if(isset($color) && !empty($color)){
							if($this->Color->saveAll($color['Color'])){
								$this->Session->setFlash(__('Color CSV file has uploaded successfully.'), 'admin_flash_success');
								$this->redirect(array('action' => 'index'));	
							}else{
								$this->Session->setFlash(__('Color CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
							}
						}else{
							$this->Session->setFlash(__('Color CSV file has no new color.'), 'admin_flash_error');
							$this->redirect(array('action' => 'index'));
						}
					}else{
						$this->Session->setFlash(__('Color CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
					}
				}
			}else{
				$this->Session->setFlash('Fill the form by select the csv file.', 'admin_flash_error');
			}
		}
	}
}?>