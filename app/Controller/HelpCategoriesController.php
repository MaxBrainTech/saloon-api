<?php
/**
 * HelpCategories Controller
 *
 * PHP version 5.4
 *
 */
class HelpCategoriesController extends AppController{
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'HelpCategories';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$this->loadModel('HelpCategory');
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
			
			if(!empty($this->request->data['HelpCategory']['name'])){
				$name = Sanitize::escape($this->request->data['HelpCategory']['name']);
				$this->Session->write('AdminSearch.name', $name);
			}
			if(isset($this->request->data['HelpCategory']['status']) && $this->request->data['HelpCategory']['status']!=''){
				$status = Sanitize::escape($this->request->data['HelpCategory']['status']);
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
					$filters[] = array('HelpCategory.'.$key =>$values);
				}
				else{
				 $filters[] = array('HelpCategory.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('HelpCategory.'.$key.' LIKE'=>"%".$values."%");
				}
			}
				
			$search_flag=1;
		}
		$this->set(compact('search_flag','defaultTab'));

		//$filters[] = array('HelpCategory.role_id'=>Configure::read('App.Role.Admin'));
		$this->paginate = array(
				'HelpCategory'=>array(	
					'limit'=>$number_of_record, 
					'order'=>array('HelpCategory.id'=>'DESC'),
					'conditions'=>$filters,
					'recursive'=>1
					)
		);

		$data = $this->paginate('HelpCategory');
		//$parents = $this->Category->parentsList();

		$this->set(compact('data'));
		$this->set('title_for_layout',  __('HelpCategories', true));

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
			$temp[] = array('HelpCategory.status'=>1);
			$active = $this->HelpCategory->find('count',array('conditions'=>$temp));
				
			$temp=$filters_without_status;
			$temp[] = array('HelpCategory.status'=>0);
			$inactive = $this->HelpCategory->find('count',array('conditions'=>$temp));
				
				
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
			$this->request->data['HelpCategory']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['HelpCategory']['name']));
				$this->HelpCategory->set($this->request->data);
				$this->HelpCategory->setValidation('admin');
				if ($this->HelpCategory->validates()) {
					if ($this->HelpCategory->save($this->request->data)) {
						$this->Session->setFlash(__('Help Category has been added successfully'), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Help Category could not be added. Please, try again.'), 'admin_flash_error');
					}
				}
				else {
					$this->Session->setFlash('The Help Category could not be added.  Please, correct errors.', 'admin_flash_error');
				}
			}
			
		}
		$parent_category = $this->HelpCategory->find('all', array('conditions'=>array('HelpCategory.parent_id'=>0), 'fields' =>array('HelpCategory.id', 'HelpCategory.name')));
		$category_list[0] = 'Select Help Category';
		foreach ($parent_category as $key => $value) {
			$category_list[$value['HelpCategory']['id']] = $value['HelpCategory']['name'];
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

		$this->HelpCategory->id = $id;
		if (!$this->HelpCategory->exists()) {
			throw new NotFoundException(__('Invalid Help Category'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
				
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$this->request->data['HelpCategory']['alias']= strtolower(preg_replace('/\s+/', '-', $this->request->data['HelpCategory']['name']));
				//validate HelpCategory data
				$this->HelpCategory->set($this->request->data);
				$this->HelpCategory->setValidation('admin');
				if ($this->HelpCategory->validates()) {
					if ($this->HelpCategory->save($this->request->data)) 
					{
						$this->Session->setFlash(__('The Help Category information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} 
					else 
					{
						$this->Session->setFlash(__('The Help Category could not be saved. Please, try again.',true), 'admin_flash_error');
					}
				}
				else 
				{
					$this->Session->setFlash(__('The Help Category could not be saved. Please, correct errors.', true), 'admin_flash_error');
				}
			}
		}else {
			$this->request->data = $this->HelpCategory->read(null, $id);
		}
		$parent_category = $this->HelpCategory->find('all', array('conditions'=>array('HelpCategory.parent_id'=>0), 'fields' =>array('HelpCategory.id', 'HelpCategory.name')));
		$category_list[0] = 'Select Help Category';
		foreach ($parent_category as $key => $value) {
			$category_list[$value['HelpCategory']['id']] = $value['HelpCategory']['name'];
		}
		$this->set(compact('category_list'));
	}
	
	public function admin_delete($id = null) {
		$this->HelpCategory->id = $id;
		if (!$this->HelpCategory->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
		if ($this->HelpCategory->delete()) {
			$this->Session->setFlash(__('Help Category deleted successfully'), 'admin_flash_success');
			$this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Help Category was not deleted', 'admin_flash_error'));
		$this->redirect($this->referer());
	}

	/**
	 * toggle status existing user
	 */
	public function admin_status($id = null) {
		$this->HelpCategory->id = $id;
		if (!$this->HelpCategory->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}

		if ($this->HelpCategory->toggleStatus($id)) {
			$this->Session->setFlash(__('Help Category status has been changed'), 'admin_flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Help Category status was not changed', 'admin_flash_error'));
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
			$action = Sanitize::escape($this->request->data['HelpCategory']['pageAction']);

			$ids = $this->request->data['HelpCategory']['id'];
				
			if (count($this->request->data) == 0 || $this->request->data['HelpCategory'] == null) {
				$this->Session->setFlash('No items selected.', 'admin_flash_error');
				$this->redirect($this->referer());
			}
				
			if($action == "delete"){
				$this->HelpCategory->deleteAll(array('HelpCategory.id'=>$ids));
				$this->Session->setFlash('Help Categories have been deleted successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "activate"){
				$this->HelpCategory->updateAll(array('HelpCategory.status'=>Configure::read('App.Status.active')),array('HelpCategory.id'=>$ids));
					

				$this->Session->setFlash('Help Categories have been activated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
				
			if($action == "deactivate"){
				$this->HelpCategory->updateAll(array('HelpCategory.status'=>Configure::read('App.Status.inactive')),array('HelpCategory.id'=>$ids));

				$this->Session->setFlash('Help Categories have been deactivated successfully', 'admin_flash_success');
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
			if(!empty($this->request->data['HelpCategory']['csvfile']['name'])){
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				$savedHelpCategories = $this->HelpCategory->find('list',array('fields'=>array('HelpCategory.id','HelpCategory.ein'),'conditions'=>array('HelpCategory.ein !=' => 'NULL')));
				/*CSV FILE UPLOADING CODE*/
				$error = array();
				$path_info = pathinfo($this->request->data['HelpCategory']['csvfile']['name']);
				if($path_info['extension'] !== 'csv'){
					$error[] = 'File must be with CSV extension.';
				}
				if($this->request->data['HelpCategory']['csvfile']['size'] > (10*1024*1024)){
					$error[] = 'File must be less than 10MB.';
				}
				if(!empty($error)){
					$this->HelpCategory->validationErrors['csvfile'] = $error;
					$this->Session->setFlash(__('Help Category CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
				}else{
					if(!is_dir(WWW_ROOT.'/uploads/categoryCSV')){
						mkdir(WWW_ROOT.'/uploads/categoryCSV',0777);
					}
					$fileName = 'categoryCSV.'.$path_info['extension'];
					$filePathWithName = WWW_ROOT.'/uploads/categoryCSV/'.$fileName;
					if(move_uploaded_file($this->request->data['HelpCategory']['csvfile']['tmp_name'],$filePathWithName)){
						/*READ THE CSV FILE*/
						$file = fopen($filePathWithName,"r");
						if($file !== false){
							while(!feof($file)){
								$rows[] = fgetcsv($file);
							}
							$count = count($rows);
							foreach($rows as $key => $row){
								if($key !== 0 && $key !== ($count - 1)){
									if(!in_array($row[0],$savedHelpCategories)){
										$category['HelpCategory'][$key]['ein'] = $row[0];
										$category['HelpCategory'][$key]['name'] = $row[1];
										$category['HelpCategory'][$key]['alias']= strtolower(preg_replace('/\s+/', '-', $row[1]));
									}
								}
							}
						}
						fclose($file);
						if(isset($category) && !empty($category)){
							if($this->HelpCategory->saveAll($category['HelpCategory'])){
								$this->Session->setFlash(__('Help Category CSV file has uploaded successfully.'), 'admin_flash_success');
								$this->redirect(array('action' => 'index'));	
							}else{
								$this->Session->setFlash(__('Help Category CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
							}
						}else{
							$this->Session->setFlash(__('Help Category CSV file has no new category.'), 'admin_flash_error');
							$this->redirect(array('action' => 'index'));
						}
					}else{
						$this->Session->setFlash(__('Help Category CSV file has not uploaded successfully. Please try again.'), 'admin_flash_error');
					}
				}
			}else{
				$this->Session->setFlash('Fill the form by select the csv file.', 'admin_flash_error');
			}
		}
	}
}?>