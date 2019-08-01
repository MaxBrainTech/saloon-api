<?php

/**
 * Helps Controller
 *
 * PHP version 5.4
 *
 */
class HelpsController extends AppController{

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Helps';
    public $components = array(
         'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('HelpQuestion');

    /*
     * beforeFilter
     * @return void
     */


    public function beforeFilter(){
    	
        parent::beforeFilter();
       $this->loadModel('HelpQuestion');
       $this->Auth->allow('app_edit');
        $this->RequestHandler->addInputType('json', array('json_decode', true));
    }

   
    public function admin_index($defaultTab = 'All'){	

    	
		$number_of_record = Configure::read('App.AdminPageLimit');
        
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
        
        if ($defaultTab != 'All'){
            $filters[] = array('HelpQuestion.status' => array_search($defaultTab, Configure::read('Status')));
        }

        if (!empty($this->request->data)){
		
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');

            App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
            if (!empty($this->request->data['HelpQuestion']['email'])) {
                $email = Sanitize::escape($this->request->data['HelpQuestion']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            
            if (!empty($this->request->data['HelpQuestion']['alternate_email'])) {
                $alternate_email = Sanitize::escape($this->request->data['HelpQuestion']['alternate_email']);
                $this->Session->write('AdminSearch.alternate_email', $alternate_email);
            }
        	if (!empty($this->request->data['HelpQuestion']['subscription_plan_id'])) {
                $subscription_plan_id = Sanitize::escape($this->request->data['HelpQuestion']['subscription_plan_id']);
                $this->Session->write('AdminSearch.subscription_plan_id', $subscription_plan_id);
            }
			if (isset($this->request->data['HelpQuestion']['status']) && $this->request->data['HelpQuestion']['status'] != ''){
                $status = Sanitize::escape($this->request->data['HelpQuestion']['status']);
                $this->Session->write('AdminSearch.status', $status);
                $defaultTab = Configure::read('Status.' . $status);
            }
			
        }

		if ($this->Session->check('number_of_record')) {
				$number_of_record = $this->Session->read('number_of_record');
				$this->request->data['Number']['number_of_record'] = $number_of_record;
		}
        $search_flag = 0;
        $search_status = '';
       
        
        $this->set(compact('search_flag', 'defaultTab'));

        $this->paginate = array(
            'HelpQuestion' => array(
                'limit' => $number_of_record,
                'order' => array('HelpQuestion.id' => 'DESC')
            )
        );
		
    	/**get all Subscription Plans */
		$data = $this->paginate('HelpQuestion');
        $this->set(compact('data'));
        $this->set('title_for_layout', __('Customers', true));


        if (isset($this->request->params['named']['page']))
            $this->Session->write('Url.page', $this->request->params['named']['page']);
        if (isset($this->request->params['named']['sort']))
            $this->Session->write('Url.sort', $this->request->params['named']['sort']);
        if (isset($this->request->params['named']['direction']))
            $this->Session->write('Url.direction', $this->request->params['named']['direction']);
        $this->Session->write('Url.type', '');
        $this->Session->write('Url.defaultTab', $defaultTab);

       
    }

	
    /*
     * View existing help_question
     */

    public function admin_view($id = null){
    	/** Load Template,SubscriptionPlan Model   */
        $this->HelpQuestion->id = $id;
        if(!$this->HelpQuestion->exists()){
            throw new NotFoundException(__('Invalid help question'));
        }
		$data = $this->HelpQuestion->read(null, $id);
		 $this->set('help_question', $data);
        
    }

    /*
     * add HelpQuestion
     */
    public function admin_add(){
		if($this->Session->check('Auth.HelpQuestion.id') && $this->Session->read('Auth.HelpQuestion.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
		/** Load Template,SubscriptionPlan Model   */
        //$this->loadModel('Template');
        
        if ($this->request->is('post')) {

            if (!empty($this->request->data)) {

                /* unset help_question skill 0 position value if exist */

                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }

                $this->HelpQuestion->set($this->request->data['HelpQuestion']);
                $this->HelpQuestion->setValidation('admin');

                $this->request->data['HelpQuestion']['password'] = Security::hash($this->request->data['HelpQuestion']['password2'], null, true);
				 $this->request->data['HelpQuestion']['origional_password'] = $this->request->data['HelpQuestion']['password2'];

                $this->HelpQuestion->create();

                $this->request->data['HelpQuestion']['role_id'] = Configure::read('App.Role.HelpQuestion');
                
				
                if ($this->HelpQuestion->saveAll($this->request->data)) {
                    $help_question_id = $this->HelpQuestion->id;
                    
					$this->Session->setFlash(__('HelpQuestion has been saved successfully'), 'admin_flash_success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The HelpQuestion could not be saved. Please, try again.'), 'admin_flash_error');
                }
            }
        }
        $this->set('title_for_layout', __('Customers', true));
    	
    }

    /*
     * edit existing help_question
     */
    public function admin_edit($id = null) {
		
		$this->HelpQuestion->id = $id;
		
        if (!$this->HelpQuestion->exists()) {
            throw new NotFoundException(__('Invalid Customer'));
        }

        if($this->request->is('post') || $this->request->is('put')) {
		
			if(!empty($this->request->data)) {
                //echo "<pre>";
                //print_r($this->request->data);die;
					if(!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
						$blackHoleCallback = $this->Security->blackHoleCallback;
						$this->$blackHoleCallback();
					}
					$this->HelpQuestion->set($this->request->data['HelpQuestion']);
					$this->HelpQuestion->setValidation('admin');
					if ($this->HelpQuestion->validates()) {
						$this->HelpQuestion->create();
						
						if ($this->HelpQuestion->saveAll($this->request->data)) {
					
							$this->Session->setFlash(__('The HelpQuestion information has been updated successfully', true), 'admin_flash_success');
							$this->redirect(array('action' => 'index'));
						} 
						else 
						{

							$this->Session->setFlash(__('The HelpQuestion could not be saved. Please, try again.', true), 'admin_flash_error');
						}
					}
					else 
					{	
						
						$this->Session->setFlash(__('The HelpQuestion could not be saved. Please, try again.', true), 'admin_flash_error');
					}
            }
        } 
		else 
		{
            $this->request->data = $this->HelpQuestion->read(null, $id);
            unset($this->request->data['HelpQuestion']['password']);
        }
	    
        $this->set('title_for_layout', __('Customers', true));
    }

   
    /*
     * delete existing help_question
     */
    public function admin_delete($id = null){
        $help_question_id = $this->HelpQuestion->id = $id;

        if (!$this->HelpQuestion->exists()){
            throw new NotFoundException(__('Invalid help question'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
	
		$help_question_data = $this->HelpQuestion->find('first',array('conditions'=>array('HelpQuestion.id'=>$id)));
		//die;
        if ($this->HelpQuestion->deleteAll(array('HelpQuestion.id'=>$id))) {

            $this->Session->setFlash(__('HelpQuestion deleted successfully'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('HelpQuestion was not deleted', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

    /*
     * toggle help question status
     */
    
    public function admin_status($id = null) {
		if($this->Session->check('Auth.HelpQuestion.id') && $this->Session->read('Auth.HelpQuestion.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->HelpQuestion->id = $id;
        if (!$this->HelpQuestion->exists()) {
            throw new NotFoundException(__('Invalid help question'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        $this->loadModel('Template');
        $this->loadModel('HelpQuestion');
        if ($this->HelpQuestion->toggleStatus($id)) {
            $help_question_info = $this->HelpQuestion->get_helps('first', 'HelpQuestion.email,HelpQuestion.first_name,HelpQuestion.last_name,HelpQuestion.status', array('HelpQuestion.id' => $id));

            $this->Session->setFlash(__('Help Question\'s status has been changed'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Help Question\'s status was not changed', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

      
     /*
     * change status and delete helps 
     */
    public function admin_process() {

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }

        if (!empty($this->request->data)) {
            App::uses('Sanitize', 'Utility');
            $action = Sanitize::escape($this->request->data['HelpQuestion']['pageAction']);

            $ids = $this->request->data['HelpQuestion']['id'];

            if (count($this->request->data) == 0 || $this->request->data['HelpQuestion'] == null) {
                $this->Session->setFlash('No items selected.', 'admin_flash_error');
                $this->redirect($this->referer());
            }

            if ($action == "delete") {
		
				$this->HelpQuestion->deleteAll(array('HelpQuestion.id' => $ids)); 
                $this->Session->setFlash('Helps have been deleted successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "activate") {
                $this->HelpQuestion->updateAll(array('HelpQuestion.status' => Configure::read('App.Status.active')), array('HelpQuestion.id' => $ids));
                $this->Session->setFlash('Helps have been activated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "deactivate") {
                $this->HelpQuestion->updateAll(array('HelpQuestion.status' => Configure::read('App.Status.inactive')), array('HelpQuestion.id' => $ids));
                $this->Session->setFlash('Helps have been deactivated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }
        } else {
            $this->redirect(array('controller' => 'admins', 'action' => 'index'));
        }
    }

	
}