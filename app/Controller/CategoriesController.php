<?php
/**
 * Categories Controller
 *
 * PHP version 5.4
 *
 */
class CategoriesController extends AppController{
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Categories';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$this->loadModel('Category');
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
			
			if(!empty($this->request->data['Category']['name'])){
				$name = Sanitize::escape($this->request->data['Category']['name']);
				$this->Session->write('AdminSearch.name', $name);
			}
			if(isset($this->request->data['Category']['status']) && $this->request->data['Category']['status']!=''){
				$status = Sanitize::escape($this->request->data['Category']['status']);
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
					$filters[] = array('Category.'.$key =>$values);
				}
				else{
				 $filters[] = array('Category.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('Category.'.$key.' LIKE'=>"%".$values."%");
				}
			}
				
			$search_flag=1;
		}
		$this->set(compact('search_flag','defaultTab'));

		//$filters[] = array('Category.role_id'=>Configure::read('App.Role.Admin'));
		$this->paginate = array(
				'Category'=>array(	
					'limit'=>$number_of_record, 
					'order'=>array('Category.id'=>'DESC'),
					'conditions'=>$filters,
					'recursive'=>1
					)
		);

		$data = $this->paginate('Category');
		//$parents = $this->Category->parentsList();

		$this->set(compact('data'));
		$this->set('title_for_layout',  __('Categories', true));

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
			$temp[] = array('Category.status'=>1);
			$active = $this->Category->find('count',array('conditions'=>$temp));
				
			$temp=$filters_without_status;
			$temp[] = array('Category.status'=>0);
			$inactive = $this->Category->find('count',array('conditions'=>$temp));
				
				
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
			$this->request->data['Category']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Category']['name']));
				$this->Category->set($this->request->data);
				$this->Category->setValidation('admin');
				if ($this->Category->validates()) {
					if ($this->Category->save($this->request->data)) {
						$this->Session->setFlash(__('Category has been added successfully'), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Category could not be added. Please, try again.'), 'admin_flash_error');
					}
				}
				else {
					$this->Session->setFlash('The Category could not be added.  Please, correct errors.', 'admin_flash_error');
				}
			}
			
		}
		$parent_category = $this->Category->find('all', array('conditions'=>array('Category.parent_id'=>0), 'fields' =>array('Category.id', 'Category.name')));
		$category_list[0] = 'Select Category';
		foreach ($parent_category as $key => $value) {
			$category_list[$value['Category']['id']] = $value['Category']['name'];
		}
		$this->set(compact('category_list'));
	}

	/**
	 * edit existing category
	 */
	/**
	 * edit existing admin
	 */
	public function admin_edit($id = null){

		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid Category'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
				
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$this->request->data['Category']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Category']['name']));
				//validate Category data
				$this->Category->set($this->request->data);
				$this->Category->setValidation('admin');
				if ($this->Category->validates()) {
					if ($this->Category->save($this->request->data)) 
					{
						$this->Session->setFlash(__('The Category information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} 
					else 
					{
						$this->Session->setFlash(__('The Category could not be saved. Please, try again.',true), 'admin_flash_error');
					}
				}
				else 
				{
					$this->Session->setFlash(__('The Category could not be saved. Please, correct errors.', true), 'admin_flash_error');
				}
			}
		}else {
			$this->request->data = $this->Category->read(null, $id);
		}
		$parent_category = $this->Category->find('all', array('conditions'=>array('Category.parent_id'=>0), 'fields' =>array('Category.id', 'Category.name')));
		$category_list[0] = 'Select Category';
		foreach ($parent_category as $key => $value) {
			$category_list[$value['Category']['id']] = $value['Category']['name'];
		}
		$this->set(compact('category_list'));
	}
	
	public function admin_delete($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
		if ($this->Category->delete()) {
			$this->Session->setFlash(__('Category deleted successfully'), 'admin_flash_success');
			$this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Category was not deleted', 'admin_flash_error'));
		$this->redirect($this->referer());
	}

	/**
	 * toggle status existing user
	 */
	public function admin_status($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}

		if ($this->Category->toggleStatus($id)) {
			$this->Session->setFlash(__('Category status has been changed'), 'admin_flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Category status was not changed', 'admin_flash_error'));
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
			$action = Sanitize::escape($this->request->data['Category']['pageAction']);

			$ids = $this->request->data['Category']['id'];
				
			if (count($this->request->data) == 0 || $this->request->data['Category'] == null) {
				$this->Session->setFlash('No items selected.', 'admin_flash_error');
				$this->redirect($this->referer());
			}
				
			if($action == "delete"){
				$this->Category->deleteAll(array('Category.id'=>$ids));
				$this->Session->setFlash('Categories have been deleted successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "activate"){
				$this->Category->updateAll(array('Category.status'=>Configure::read('App.Status.active')),array('Category.id'=>$ids));
					

				$this->Session->setFlash('Categories have been activated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "deactivate"){
				$this->Category->updateAll(array('Category.status'=>Configure::read('App.Status.inactive')),array('Category.id'=>$ids));

				$this->Session->setFlash('Categories have been deactivated successfully', 'admin_flash_success');
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
			if(!empty($this->request->data['Category']['csvfile']['name'])){
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$savedCategories = $this->Category->find('list',array('fields'=>array('Category.id','Category.ein'),'conditions'=>array('Category.ein !=' => 'NULL')));
				/*CSV FILE UPLOADING CODE*/
				$error = array();
				$path_info = pathinfo($this->request->data['Category']['csvfile']['name']);
				if($path_info['extension'] !== 'csv'){
					$error[] = 'File must be with CSV extension.';
				}
				if($this->request->data['Category']['csvfile']['size'] > (10*1024*1024)){
					$error[] = 'File must be less than 10MB.';
				}
				if(!empty($error)){
					$this->Category->validationErrors['csvfile'] = $error;
					$this->Session->setFlash(__('Category CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
				}else{
					if(!is_dir(WWW_ROOT.'/uploads/categoryCSV')){
						mkdir(WWW_ROOT.'/uploads/categoryCSV',0777);
					}
					$fileName = 'categoryCSV.'.$path_info['extension'];
					$filePathWithName = WWW_ROOT.'/uploads/categoryCSV/'.$fileName;
					if(move_uploaded_file($this->request->data['Category']['csvfile']['tmp_name'],$filePathWithName)){
						/*READ THE CSV FILE*/
						$file = fopen($filePathWithName,"r");
						if($file !== false){
							while(!feof($file)){
								$rows[] = fgetcsv($file);
							}
							$count = count($rows);
							foreach($rows as $key => $row){
								if($key !== 0 && $key !== ($count - 1)){
									if(!in_array($row[0],$savedCategories)){
										$category['Category'][$key]['ein'] = $row[0];
										$category['Category'][$key]['name'] = $row[1];
										$category['Category'][$key]['alias']= strtolower(preg_replace('/\s+/', '-', $row[1]));
									}
								}
							}
						}
						fclose($file);
						if(isset($category) && !empty($category)){
							if($this->Category->saveAll($category['Category'])){
								$this->Session->setFlash(__('Category CSV file has uploaded successfully.'), 'admin_flash_success');
								$this->redirect(array('action' => 'index'));	
							}else{
								$this->Session->setFlash(__('Category CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
							}
						}else{
							$this->Session->setFlash(__('Category CSV file has no new category.'), 'admin_flash_error');
							$this->redirect(array('action' => 'index'));
						}
					}else{
						$this->Session->setFlash(__('Category CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
					}
				}
			}else{
				$this->Session->setFlash('Fill the form by select the csv file.', 'admin_flash_error');
			}
		}
	}
}?>