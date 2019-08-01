<?php
/**
 * Settings Controller
 *
 * PHP version 5.4
 *
 */
class SettingsController extends AppController {
	/**
     * Settings name
     *
     * @var string
     * @access public
     */
	var	$name	=	'Settings';
	var	$uses	=	array('Setting','Setting');
	var $helpers = array('Mailto','Html');
	/*
	* beforeFilter
	* @return void
	*/
    public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$this->loadModel('Page');
    }
	
	
	/**
	* edit existing user
	*/
    public function admin_index() {
		
         if ($this->request->is('post') || $this->request->is('put')) {
			
			if(!empty($this->request->data)) {
				
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				
				//validate user data
				$this->Setting->set($this->request->data);
				$this->Setting->setValidation('admin');
				if ($this->Setting->validates()) {
					
					if ($this->Setting->saveAll($this->request->data)) {
						$this->Session->setFlash(__('The Settings has been updated successfully',true), 'admin_flash_success');
						$this->redirect($this->referer());
					} else {
						$data = $this->Setting->find('all', array('fields'=>array('id', 'label', 'description'),  'conditions' => array('NOT' => array('Setting.name' => array('front_video', 'banner_speed', 'featured_staff_speed')))));
						
						for($i=0; $i<count($data); $i++){
							$this->request->data[$i]['Setting']['label'] = $data[$i]['Setting']['label'];
							$this->request->data[$i]['Setting']['description'] = $data[$i]['Setting']['description'];
						}
						
						$this->Session->setFlash(__('The Settings could not be updated. Please, try again.',true), 'admin_flash_error');
					}
			}else {
				$data = $this->Setting->find('all', array('fields'=>array('id', 'label', 'description'),  'conditions' => array('NOT' => array('Setting.name' => array('front_video', 'banner_speed', 'featured_staff_speed')))));
						
				for($i=0; $i<count($data); $i++){
					$this->request->data[$i]['Setting']['label'] = $data[$i]['Setting']['label'];
					$this->request->data[$i]['Setting']['description'] = $data[$i]['Setting']['description'];
				}
				$this->Session->setFlash(__('The Settings could not be updated. Please, correct errors.', true), 'admin_flash_error');
			}
		  }	
        }
		else {
				
            $this->request->data = $this->Setting->find('all', array(
        'conditions' => array('NOT' => array('Setting.name' => array('front_video', 'banner_speed', 'featured_staff_speed')))));

				//pr($this->request->data);die;
        }
		 
    }
	
	public function admin_home(){
	
		if ($this->request->is('post') || $this->request->is('put')) {
			
			if(!empty($this->request->data)) {
				//pr($this->request->data);
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				
				//validate user data
				$this->Setting->set($this->request->data);
				$this->Setting->setValidation('admin');
				if ($this->Setting->validates()) {
					
					if ($this->Setting->saveAll($this->request->data)) {
						$this->Session->setFlash(__('The Home Page Settings has been updated successfully',true), 'admin_flash_success');
						$this->redirect($this->referer());
					} else {
						//pr($this->Setting); die;
						$data = $this->Setting->find('all', array('fields'=>array('id', 'label', 'description'),'conditions' => array('or' => array('Setting.name' => array('front_video', 'banner_speed', 'featured_staff_speed')))));
						
						for($i=0; $i<count($data); $i++){
							$this->request->data[$i]['Setting']['label'] = $data[$i]['Setting']['label'];
							$this->request->data[$i]['Setting']['description'] = $data[$i]['Setting']['description'];
						}
						
						$this->Session->setFlash(__('The Settings could not be updated. Please, try again.',true), 'admin_flash_error');
					}
			}
			else {
			//pr($this);
				$data = $this->Setting->find('all', array('fields'=>array('id', 'label', 'description'),'conditions' => array('or' => array('Setting.name' => array('front_video', 'banner_speed', 'featured_staff_speed')))));
						
				for($i=0; $i<count($data); $i++){
					$this->request->data[$i]['Setting']['label'] = $data[$i]['Setting']['label'];
					$this->request->data[$i]['Setting']['description'] = $data[$i]['Setting']['description'];
				}
				$this->Session->setFlash(__('The Settings could not be updated. Please, correct errors.', true), 'admin_flash_error');
			}
		  }	
        }
		else {
				
         $this->request->data = $this->Setting->find('all', array(
        'conditions' => array('or' => array('Setting.name' => array('front_video', 'banner_speed', 'featured_staff_speed')))));
				//pr($this->request->data);die;
        }
		
	}
	
	
	/*delete feedbacks*/
	 public function admin_delete($id = null) {
		  $this->Setting->id = $id;
		  if (!$this->Setting->exists()) {
				throw new NotFoundException(__('Invalid Contact'));
		  }
			if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->Setting->delete()) {
            $this->Session->setFlash(__('Setting deleted successfully'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Setting was not deleted', 'admin_flash_error'));
        $this->redirect($this->referer());
    }
	 
	/*delete selected process*/
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
			$action = Sanitize::escape($this->request->data['Setting']['pageAction']);	  
						
			$ids = $this->request->data['Setting']['id'];
			
			if (count($this->request->data) == 0 || $this->request->data['Setting'] == null) {
				$this->Session->setFlash('No items selected.', 'admin_flash_error');
				$this->redirect($this->referer());
			}
			
			if($action == "delete"){
				$this->Setting->deleteAll(array('Setting.id'=>$ids));
				$this->Session->setFlash('Setting have been deleted successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
			if($action == "activate"){
				$this->Setting->updateAll(array('Setting.status'=>Configure::read('App.Status.active')),array('Setting.id'=>$ids));
			
				
				$this->Session->setFlash('Setting have been activated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
			
			if($action == "deactivate"){
				$this->Setting->updateAll(array('Setting.status'=>Configure::read('App.Status.inactive')),array('Setting.id'=>$ids));
				
				$this->Session->setFlash('Setting have been deactivated successfully', 'admin_flash_success');
				$this->redirect($this->referer());
			}
		}
		else{
			$this->redirect(array('controller'=>'Settings', 'action'=>'index'));
		}
	}
	/**
	* edit existing Setting
	*/
    public function admin_edit($id = null) {
        $this->Setting->id = $id;
        if (!$this->Setting->exists()) {
            throw new NotFoundException(__('Invalid Setting'));
        }
		
         if ($this->request->is('post') || $this->request->is('put')) {
			
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				//validate Setting data
				$this->Setting->set($this->request->data);
				$this->Setting->setValidation('admin');
				if ($this->Setting->validates()) {
					if ($this->Setting->save($this->request->data)) {
						$this->Session->setFlash(__('The Setting information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Setting could not be saved. Please, try again.',true), 'admin_flash_error');
					}
				}
				else {
					$this->Setting->bindModel(
						array('belongsTo' => array(
									'Setting' => array(
										'fields'=>array('Setting.name')
									)
						)
						),false
					);
						$sections=$this->Setting->Find('list');
						$this->set(compact('sections'));
						$this->Session->setFlash(__('The Setting could not be saved. Please, correct errors.', true), 'admin_flash_error');
			}
		  }	
        }
		else {
			$this->request->data = $this->Setting->read(null, $id);
        }
    }
	/**
	* toggle status existing Setting
	*/
    public function admin_status($id = null) {
		
		  $this->Setting->id = $id;
		  if (!$this->Setting->exists()) {
				throw new NotFoundException(__('Invalid Setting'));
		  }
			if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
		  
        if ($this->Setting->toggleStatus($id)) {
            $this->Session->setFlash(__('Setting\'s status has been changed'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Setting\'s status was not changed', 'admin_flash_error'));
        $this->redirect($this->referer());
    } /*
	* Add new Setting
	*/	
    public function admin_add() {
        if ($this->request->is('post')) {
			//pr($this->request->data);die;
			//check empty
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				
				//validate user data
				
				$this->Setting->set($this->request->data);
				$this->Setting->setValidation('admin');
				if ($this->Setting->validates()) {		
						if ($this->Setting->saveAll($this->request->data)) {
						$this->Session->setFlash(__('Setting has been saved successfully'), 'admin_flash_success');
						$this->redirect(array('action'=>'index'));
					} else {
						$this->Session->setFlash(__('The Setting could not be saved. Please, try again.'), 'admin_flash_error');
					}
				}
				else {
				$this->Session->setFlash('The Setting could not be saved.  Please, correct errors.', 'admin_flash_error');
				}	
			}
		}
				$this->Setting->bindModel(
							array('belongsTo' => array(
										'Setting' => array(
											'fields'=>array('Setting.name')
										)
								)
							),false
						);
				$sections=$this->Setting->Find('list');
				$this->set(compact('sections'));
		
    }
	
	public function dashboard_settings(){
		/* $filters = array(
			'Brief.user_id'=>$this->Auth->user('id')
		);
		$this->paginate = array(
			'Brief'=>array(	
				'limit'=>Configure::read('App.PageLimit'), 
				'order'=>array('Brief.created'=>'DESC'),
				'conditions'=>$filters,
				'recursive'=>-1
			)
		);
		$briefs = $this->paginate('Brief');
		
		$this->set(compact('briefs')); */
		if($this->request->is('ajax')){
			$this->render('ajax/dashboard_briefs');
		}
	}
}