<?php

/**
 * Users Controller
 *
 * PHP version 5.4
 *
 */
class UsersController extends AppController{

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Users';
    public $components = array(
         'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('User');

    /*
     * beforeFilter
     * @return void
     */

    function beforeRender() {
    	
        $model = Inflector::singularize($this->name);
        foreach ($this->{$model}->hasAndBelongsToMany as $k => $v) {
            if (isset($this->{$model}->validationErrors[$k])) {
                $this->{$model}->{$k}->validationErrors[$k] = $this->{$model}->validationErrors[$k];
            }
        }
    }

    public function beforeFilter(){
    	
        parent::beforeFilter();
        $this->loadModel('User');
		$this->Auth->allow('start_access', 'reset_password', 'thankx', 'reset_password_change','login', 'subscription', 'email_confirm', 'register', 'activate', 'success', 'fbconnect', 'forgot_password','get_password', 'password_changed', 'linked_connect', 'save_linkedin_data', 'tw_connect', 'tw_response', 'glogin', 'save_google_info','social_login', 'tlogin', 'save_cover_photo', 'getTwitterData', 'fb_data', 'fb_logout', 'social_join_mail', 'home', 'checkunique', 'checklogin', 'test_mail', 'get_affilates', 'test_mail');
    }

    /*
     * List all users in admin panel
     */

    public function admin_export_users() {
    // It's OK to use containable or recursive in the export data
  /*  $this->User->contain(array(
        'State' => array(
            'Country'
        )
    ));
    */
    $data = $this->User->find('all');
    //print_r($data );die;
    $this->Export->exportCsv($data, 'users.csv');
    // a CSV file called myExport.csv will be downloaded by the browser.
}
   

    public function admin_index($defaultTab = 'All'){	

    	
		$number_of_record = Configure::read('App.AdminPageLimit');
        
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
        $filters_without_status = $filters = array('User.role_id' => Configure::read('App.Role.User'));

        if ($defaultTab != 'All'){
            $filters[] = array('User.status' => array_search($defaultTab, Configure::read('Status')));
        }

        if (!empty($this->request->data)){
		
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');

            App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
            if (!empty($this->request->data['User']['email'])) {
                $email = Sanitize::escape($this->request->data['User']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            
            if (!empty($this->request->data['User']['alternate_email'])) {
                $alternate_email = Sanitize::escape($this->request->data['User']['alternate_email']);
                $this->Session->write('AdminSearch.alternate_email', $alternate_email);
            }
        	if (!empty($this->request->data['User']['subscription_plan_id'])) {
                $subscription_plan_id = Sanitize::escape($this->request->data['User']['subscription_plan_id']);
                $this->Session->write('AdminSearch.subscription_plan_id', $subscription_plan_id);
            }
			if (isset($this->request->data['User']['status']) && $this->request->data['User']['status'] != ''){
                $status = Sanitize::escape($this->request->data['User']['status']);
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
        if ($this->Session->check('AdminSearch')) {
            $keywords = $this->Session->read('AdminSearch');

            foreach ($keywords as $key => $values) {
                if ($key == 'status') {
                    $search_status = $values;
                    $filters[] = array('User.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('User.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }		
            $search_flag = 1;
        }
        
        $this->set(compact('search_flag', 'defaultTab'));

        $this->paginate = array(
            'User' => array(
                'limit' => $number_of_record,
                'order' => array('User.id' => 'DESC'),
                'conditions' => $filters
            )
        );
		
    	/**get all Subscription Plans */
		$data = $this->paginate('User');
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

        if ($this->request->is('ajax')) {
            $this->render('ajax/admin_index');
        } else {
            $active = 0;
            $inactive = 0;
            if ($search_status == '' || $search_status == Configure::read('App.Status.active')) {
                $temp = $filters_without_status;
                $temp[] = array('User.status' => 1);
                $active = $this->User->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp = $filters_without_status;
                $temp[] = array('User.status' => 0);
                $inactive = $this->User->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }
    }

    /*
     * Dashboard
     */

    public function admin_dashboard() {

    }
	  /*
     * User location search
     */
	public function admin_location_search() {		
		
		if($this->request->is('ajax')) { 		
			$this->layout = false;
			$this->autoRender = false;	
			$this->loadModel('Postalcode');							
			
			$results = $this->Postalcode->find('all', array('conditions'=>array("Postalcode.placename LIKE '%".$_POST['query']."%'","Postalcode.status"=>1),'fields'=>array('Postalcode.country_name','Postalcode.admin1_state','Postalcode.placename'),'limit'=>20));
					
			$input_id = $_POST['rand'];
							
			foreach ($results As $k=>$v) 
			{	
				$str = strtoupper($v['Postalcode']['placename'].' '.$v['Postalcode']['admin1_state'].' '.$v['Postalcode']['country_name']);
				$start = strpos($str,strtoupper($_POST['query'])); 
				$end   = similar_text($str,strtoupper($_POST['query'])); 
				$last = substr($str,$end,strlen($str));
				$first = substr($str,$start,$end);			
				$final = '<span style="font-weight:bold;">'.$first.'</span>'.$last;
				$final2 = $first.$last;
				
				echo "<li onclick=\"set_".$input_id."('".$final2."')\"><a href='javascript:void(0);' style='color:#000;' style='font-weight:bold;'>".$final."</a></li>";
			} 
		}
		die();
	} 
    /*
     * View existing user
     */

    public function admin_view($id = null){
    	/** Load Template,SubscriptionPlan Model   */
        $this->User->id = $id;
        if(!$this->User->exists()){
            throw new NotFoundException(__('Invalid user'));
        }
		$data = $this->User->read(null, $id);
		 $this->set('user', $data);
        
    }

    /*
     * add User
     */
    public function admin_add(){
		if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
		/** Load Template,SubscriptionPlan Model   */
        //$this->loadModel('Template');
        
        if ($this->request->is('post')) {

            if (!empty($this->request->data)) {

                /* unset user skill 0 position value if exist */

                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }

                $this->User->set($this->request->data['User']);
                $this->User->setValidation('admin');

                $this->request->data['User']['password'] = Security::hash($this->request->data['User']['password2'], null, true);
				 $this->request->data['User']['origional_password'] = $this->request->data['User']['password2'];

                $this->User->create();

                $this->request->data['User']['role_id'] = Configure::read('App.Role.User');
                
				
                if ($this->User->saveAll($this->request->data)) {
                    $userId = $this->User->id;
                    
					$this->Session->setFlash(__('User has been saved successfully'), 'admin_flash_success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The User could not be saved. Please, try again.'), 'admin_flash_error');
                }
            }
        }
        $this->set('title_for_layout', __('Customers', true));
    	
    }

    /*
     * edit existing user
     */
    public function admin_edit($id = null) {
		
		$this->User->id = $id;
		
        if (!$this->User->exists()) {
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
					$this->User->set($this->request->data['User']);
					$this->User->setValidation('admin');
					if ($this->User->validates()) {
						$this->User->create();
						
						if ($this->User->saveAll($this->request->data)) {
					
							$this->Session->setFlash(__('The User information has been updated successfully', true), 'admin_flash_success');
							$this->redirect(array('action' => 'index'));
						} 
						else 
						{

							$this->Session->setFlash(__('The User could not be saved. Please, try again.', true), 'admin_flash_error');
						}
					}
					else 
					{	
						
						$this->Session->setFlash(__('The User could not be saved. Please, try again.', true), 'admin_flash_error');
					}
            }
        } 
		else 
		{
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
	    
        $this->set('title_for_layout', __('Customers', true));
    }

    /*
     * change user password by admin
     */
    
    public function admin_change_password($id = null) {
		
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
		
        if ($this->request->is('post') || $this->request->is('put')) {

            if (!empty($this->request->data)) {
                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }


                //validate user data
                $this->User->set($this->request->data);
                $this->User->setValidation('admin_change_password');
                if ($this->User->validates()) {
                    $new_password = $this->request->data['User']['new_password'];
                    $this->request->data['User']['password'] = Security::hash($this->request->data['User']['new_password'], null, true);
                    $this->request->data['User']['origional_password'] = $this->request->data['User']['new_password'];
                    if ($this->User->saveAll($this->request->data)) {
                       
						 $this->Session->setFlash(' Password has been changed successfully', 'admin_flash_success');
                        $this->redirect($this->referer());
                    } else {
                        $this->Session->setFlash(__('The Password could not be changed. Please, try again.', true), 'admin_flash_error');
                    }
                } else {
                    $this->Session->setFlash(__('The Password could not be changed. Please, correct errors.', true), 'admin_flash_error');
                }
            }
        } else {

            $this->request->data = $this->User->read(null, $id);
		    unset($this->request->data['User']['password']);
        }
    }

    /*
     * delete existing user
     */
    public function admin_delete($id = null){
        $user_id = $this->User->id = $id;

        if (!$this->User->exists()){
            throw new NotFoundException(__('Invalid user'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
	
		$user_data = $this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		//die;
        if ($this->User->deleteAll(array('User.id'=>$id))) {

            $this->Session->setFlash(__('User deleted successfully'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('User was not deleted', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

    /*
     * toggle user status
     */
    
    public function admin_status($id = null) {
		if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        $this->loadModel('Template');
        $this->loadModel('User');
        if ($this->User->toggleStatus($id)) {
            $user_info = $this->User->get_users('first', 'User.email,User.first_name,User.last_name,User.status', array('User.id' => $id));

            $this->Session->setFlash(__('User\'s status has been changed'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('User\'s status was not changed', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

      
     /*
     * change status and delete users 
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
            $action = Sanitize::escape($this->request->data['User']['pageAction']);

            $ids = $this->request->data['User']['id'];

            if (count($this->request->data) == 0 || $this->request->data['User'] == null) {
                $this->Session->setFlash('No items selected.', 'admin_flash_error');
                $this->redirect($this->referer());
            }

            if ($action == "delete") {
		
				$this->User->deleteAll(array('User.id' => $ids)); 
                $this->Session->setFlash('Users have been deleted successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "activate") {
                $this->User->updateAll(array('User.status' => Configure::read('App.Status.active')), array('User.id' => $ids));
                $this->Session->setFlash('Users have been activated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "deactivate") {
                $this->User->updateAll(array('User.status' => Configure::read('App.Status.inactive')), array('User.id' => $ids));
                $this->Session->setFlash('Users have been deactivated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }
        } else {
            $this->redirect(array('controller' => 'admins', 'action' => 'index'));
        }
    }

	/*
     * reset user password 
     */
    
    public function reset_password($id=0)
	{
		$this->loadModel('Template');
		$this->loadModel('User');
		$this->layout = false;
		if($this->request->is('post'))
		{
			if(!empty($this->request->data))
			{
				$this->User->set($this->request->data);
				$this->User->validates();
				$this->User->setValidation('reset_password');
				if($this->User->validates())
				{
					$user_id = base64_decode(base64_decode(base64_decode($id)));
					$user_data =  $this->User->find('first',array('conditions'=>array("User.id"=>$user_id,"User.role_id = "=>Configure::read('App.User.role'))));
					
					
					if(isset($user_data) && !empty($user_data))
					{
						$this->User->id = $user_data['User']['id'];
						$new_password = Security::hash($this->request->data['User']['password'], null, true);
						if($this->User->saveField('password',$new_password))
						{
							$password = $this->request->data['User']['password'];
							$this->User->saveField('origional_password',$this->request->data['User']['password']);
							unset($this->request->data['User']['password']);
							unset($this->request->data['User']['password2']);	
							/*******Reset Password mail code here***/
							$mailMessage='';
							$password_confirm = $this->Template->find('first', array('conditions' => array('Template.slug' => 'forgot_password_confirm')));
							
							$email_subject = $password_confirm['Template']['subject'];
							$subject = '['.Configure::read('Site.title').']'. $email_subject;
							
							$mailMessage = str_replace(array('{NAME}','{PASSWORD}'), array($user_data['User']['username'],$password), $password_confirm['Template']['content']);
							
							if(parent::sendMail($user_data['User']['email'],$subject,$mailMessage,array(Configure::read('App.AdminMail')=>Configure::read('Site.title')),'general'))
							{
								
							// Save the data in templates_users table start //
								$this->loadModel('TemplatesUser');
								$templates_users['TemplatesUser']['user_id'] = $user_data['User']['id'];
								$templates_users['TemplatesUser']['template_id'] = $password_confirm['Template']['id'];
								$this->TemplatesUser->save($templates_users);
							// End //
							$this->Session->setFlash('Password change sucessfully.','front_flash_good');
							$this->redirect(array('controller'=>'users','action'=>'thankx'));
						 
							}
							
							
						}
					}
					else
					{
						$this->Session->setFlash('invalid url.', 'front_flash_bad');
					}
				}
			}
		}	
		$this->set(compact('id'));
	}
	
	
	
	/*
     * change reset user password
     */
	
	public function reset_password_change($id=0)
	{
		$this->loadModel('Template');
		$this->loadModel('User');
		$this->layout = false;
		if($this->request->is('post'))
		{
			if(!empty($this->request->data))
			{	
				
				$this->User->set($this->request->data);
				$this->User->validates();
				$this->User->setValidation('reset_password');
				if($this->User->validates())
				{
					$user_id = base64_decode(base64_decode(base64_decode($id)));
					$user_data =  $this->User->find('first',array('conditions'=>array("User.id"=>$user_id,"User.role_id = "=>Configure::read('App.User.role'))));
					
					
					if(isset($user_data) && !empty($user_data))
					{
						$this->User->id = $user_data['User']['id'];
						$new_password = Security::hash($this->request->data['User']['password'], null, true);
						if($this->User->saveField('password',$new_password))
						{
							$password = $this->request->data['User']['password'];
							$this->User->saveField('origional_password',$this->request->data['User']['password']);
							unset($this->request->data['User']['password']);
							unset($this->request->data['User']['password2']);	
							/*******Reset Password mail code here***/
							$mailMessage='';
							$password_confirm = $this->Template->find('first', array('conditions' => array('Template.slug' => 'reset_password_confirm')));
							
							$email_subject = $password_confirm['Template']['subject'];
							$subject = '['.Configure::read('Site.title').']'. $email_subject;
							
							$mailMessage = str_replace(array('{NAME}','{PASSWORD}'), array($user_data['User']['username'],$password), $password_confirm['Template']['content']);
							
							if(parent::sendMail($user_data['User']['email'],$subject,$mailMessage,array(Configure::read('App.AdminMail')=>Configure::read('Site.title')),'general'))
							{
								
							// Save the data in templates_users table start //
								$this->loadModel('TemplatesUser');
								$templates_users['TemplatesUser']['user_id'] = $user_data['User']['id'];
								$templates_users['TemplatesUser']['template_id'] = $password_confirm['Template']['id'];
								$this->TemplatesUser->save($templates_users);
							// End //
							$this->Session->setFlash('Password change sucessfully.','front_flash_good');
							$this->redirect(array('controller'=>'users','action'=>'thankx'));
						 
							}
							
							
						}
					}
					else
					{
						$this->Session->setFlash('invalid url.', 'front_flash_bad');
					}
				}
			}
		}	
		$this->set(compact('id'));
	}
	
	
	/*
     * thanx
     */
	public function thankx()
	{		
		$this->layout = false;
	}
	/*
     * user login
     */
	public function login() {
		
		if($this->Auth->user())
		{
				//pr($this->Auth->user());die('In');
				$this->redirect($this->Auth->redirect());
		}
					
		if($this->request->is('post')) {
			
			if (!empty($this->request->data)) {
				
				$this->User->set($this->request->data['User']);
				$this->User->setValidation('login');
				
				//pr($this->User->validates());die;
				
				if($this->User->validates()) {
					$this->request->data['User']['email'] = $this->request->data['User']['login_email'];
					unset($this->request->data['User']['login_email']);
					App::Import('Utility', 'Validation');
					if( isset($this->request->data['User']['email']) && Validation::email($this->request->data['User']['email'])) {
						$this->Auth->authenticate['Form'] = array('fields' =>array('username' => 'email'));
					}
					 $find_by_email = $this->User->find('first', array('conditions' => array('email' => $this->request->data['User']['email'], 'status'=>1), 'fields' => array('id', 'email', 'role_id', 'paypal_id', 'subscription_plan_id', 'is_email_verified', 'payment_status', 'status')));
            		 if (!empty($find_by_email)) {
            		/*
					if (!empty($find_by_email)) {
	                if ($find_by_email['User']['is_email_verified'] == 0) {
	                    $this->Session->write('App.ReSendMail', $find_by_email['User']['email']);
	                    $url = Router::url(array(
	                                'controller' => 'users',
	                                'action' => 'reSendMail'
	                                    ), true
	                    );
	                    $url = "<a href='" . $url . "'>Re-Send Email</a>";
	                    $this->Session->setFlash(__('Please confirm email address.We have sent a mail for email verification please click on confirmation link.' . $url . 'verification.'), 'flash_error');
	                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
	                } else {
	                  if ($this->Auth->login()) {
							$this->Session->write('User', $this->Auth->user());
							$this->User->id = $this->Auth->user('id');
							$date = date('d/m/Y');
							//echo $date;die;
							if ($this->User->id) {
							    $this->User->saveField('last_login', $date);
							}
							//$this->redirect($this->Auth->redirect());                        	
	                        	
                            if (empty($this->request->data['User']['remember'])) {
                                $this->Cookie->delete('User');
                            } else {
                                $cookie = array();
                                $cookie['email'] = $this->request->data['User']['email'];
                                $cookie['token'] = $this->request->data['User']['password'];
                                $this->Cookie->write('User', $cookie, true, '+2 weeks');
                            }
                            unset($this->request->data['User']['remember']);
                            $this->Session->setFlash(__('You have successfully logged in.'), 'flash_success');
                           
	                
	            } else {
	                if ($this->request->is('post')) {
	                    $this->request->data['User']['username'] = $username;
	
	                    $this->Session->setFlash(__('Invalid username or password, try again'), 'flash_error');
	                }
	            }*/
            	if(!$this->Auth->login()) {
					
						$this->Session->setFlash(__('Invalid email or password, try again'));
					} else {
						$this->Session->write('User', $this->Auth->user());
						$this->User->id = $this->Auth->user('id');
						$date = date('d/m/Y');
						//echo $date;die;
						if ($this->User->id) {
						    $this->User->saveField('last_login', $date);
						}
						$this->redirect($this->Auth->redirect());
					}
				}
				else 
				{
					$this->Session->setFlash(__('Invalid email or password, try again', 'flash_error'));
				}
				}else{
					$this->Session->setFlash(__('Invalid email or password, try again', 'flash_error'));
				}
			}
		}
		
		/* * Load Template,SubscriptionPlan Model   */
        $this->loadModel('SubscriptionPlan');
        /**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
    	$this->set(compact('subscription_plans'));
    	//pr($this->Auth->user());die('tessst');
	}
	
	/*
     * user registration
     */
	public function register()
	{
			if($this->Auth->user())
			{
					//pr($this->Auth->user());die('In');
					$this->redirect($this->Auth->redirect());
			}
		
			if ($this->request->is('post')) 
			{
				
				if (!empty($this->request->data)) 
				{
					
					$this->User->set($this->request->data['User']);
					$this->User->setValidation('register');
								
					$verification_code = substr(md5(uniqid()), 0, 20);
					$this->request->data['User']['verification_code'] = $verification_code;
					$this->request->data['User']['status'] = '0';
					//echo $this->request->data['User']['subscription_plan_id'];die;
					
					
					if($this->User->validates()) 
					{
						//pr($this->request->data);die;
						/* user plan detail*/
						$this->loadModel('SubscriptionPlan');
						$subscription_plans_id = $this->request->data['User']['subscription_plan_id'];
						$subscription_plans = $this->SubscriptionPlan->find('first',array('conditions'=>array('SubscriptionPlan.id'=>$subscription_plans_id)));
						if($subscription_plans['SubscriptionPlan']['plan_type']==1){
							$duration = $subscription_plans['SubscriptionPlan']['plan_duration'];
							$expireDate = date("Y-m-d H:i:s", strtotime("+".$duration." months"));
							$this->request->data['User']['service_expire_date'] = $expireDate;
						}else if($subscription_plans['SubscriptionPlan']['plan_type']==2){
							$duration = $subscription_plans['SubscriptionPlan']['plan_duration'];
							$expireDate = date("Y-m-d H:i:s", strtotime("+".$duration." years"));
							$this->request->data['User']['service_expire_date'] = $expireDate;
						}
						$this->request->data['User']['password'] = Security::hash($this->request->data['User']['password2'], null, true);
						$this->request->data['User']['ip'] = $this->RequestHandler->getClientIp();
						$enter_password = $this->request->data['User']['password2'];
						$password = $this->request->data['User']['password'];
						$name = $this->request->data['User']['first_name']." ".$this->request->data['User']['last_name'];
						if($this->User->saveAll($this->request->data)){
						if($this->request->data['User']['subscription_plan_id']==1){
							
								
								/*************** EMAIL NOTIFICATION MESSAGE ****************/	
									$this->User->saveField('status', '1');
									$to      = $this->request->data['User']['email'];
									$from    = Configure::read('App.AdminMail');
									$mail_message = '';
									$this->loadModel('Template');
									$registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_registration')));
									$email_subject = $registrationMail['Template']['subject'];
									$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
									$activationCode = $this->request->data['User']['verification_code'];
									$activation_url = Router::url(array(
													'controller' => 'users',
													'action' => 'email_confirm',
													base64_encode($this->request->data['User']['email']),
													$verification_code,
													), true);
					
									$activation_link	=	'<a href="'.$activation_url.'">click here</a>';
									//echo $activation_link;die;
									$mail_message = str_replace(array('{Email}','{PASSWORD}','{ACTIVATION_LINK}', '{activation_code}', '{NAME}',  '{SITE}'), array($to, $enter_password,$activation_link, $activationCode, $name, Configure::read('App.SITENAME')), $name, $registrationMail['Template']['content']);
									$template = 'default';
									$this->set('message', $mail_message);
									//echo $mail_message;die;
									//echo $to."_".$subject."_".$mail_message."_".$from."_".$template;die;
									parent::sendMail($to, $subject, $mail_message, $from, $template);
									/****************** EMAIL NOTIFICATION MESSAGE ********************/
									$this->Session->setFlash(__('The user has been registered successfully.', true), 'flash_success');
									$this->redirect(array('controller' => 'users', 'action' => 'register'));
								
							
							}else{
								//pr($this->User->id);die;
								$this->User->saveField('status', '0');
								$this->Session->write('User',$this->request->data['User']);
								#pr($this->Session->read('User'));die;
								$this->Cookie->write('User',$this->request->data['User'],$encrypt=false,3600);
								#pr($this->Cookie->read('User'));die;
								if(!empty($this->request->data['User']['subscription_plan_id'])){
									$this->testPaypalGetExpress();
								}else{
									$this->Session->setFlash(__('Please select subscription plan, try again', 'flash_error'));
								}
							
							}
						}				 
						else 
						{
							$this->Session->setFlash(__('The user could not be registerd. Please, try again.', 'flash_error'));
						}
					}
					else 
					{
						$this->Session->setFlash(__('Please correct error listed below, try again', 'flash_error'));
					}
				}
			}
		/* * Load Template,SubscriptionPlan Model   */
        $this->loadModel('SubscriptionPlan');
        /**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
    	$this->set(compact('subscription_plans'));
       
			
		
	}
	  /**
     * user activation
     * Check email confirm by email
     * @param var $email - base64 encoded email
     */
    function email_confirm($email, $act_id) {
        
        $user = $this->User->find('first', array('conditions' => array('User.email' => base64_decode($email), 'User.verification_code' => $act_id)));
     //   pr($user);die;
        $today = strtotime('now');
        #  $new_date = $today + 30 * 24 * 60 * 60;

        if (!empty($user) && count($user)) {
            $this->User->updateAll(array('status' => Configure::read('App.Status.active')), array('User.id' => $user["User"]["id"]));
            $this->Session->setFlash('Active your account.please login.', 'flash_success');
        } else {
            $this->Session->setFlash('This email is not register.', 'flash_error');
        }
        $this->redirect(array('controller' => 'pages', 'action' => 'home', 'varify_email'));
    }
	
	
/*
     * user profile
     */
    public function my_profile()
	{
		
		$this->layout = "welcome";
		//pr($this->Auth->user());die;
		if(!$this->Auth->user())
		{
			$this->redirect($this->Auth->redirect());
		}
		/* load model */
		$this->loadModel('Subcription');
		
		$this->User->bindModel(array('belongsTo'=>array('SubscriptionPlan'=>array('className'=>'SubscriptionPlan', 'foreignKey'=>'subscription_plan_id'))));
		$user_id = $this->Auth->user('id');
		$user = $this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
		$this->set(compact('user'));
		
	}
	
	
	
	
	
	/*
     * user account
     
    public function my_account()
	{
		//pr($this->Auth->user());die;
        if(!$this->Auth->user())
		{
			//pr($this->Auth->user());die('In');
			$this->redirect($this->Auth->redirect());
			
        }
		$id = $this->Auth->user('id');
		$this->loadModel('Feed');
		$filters = array();
		$filters[] = array('Feed.status'=>Configure::read('App.Status.active'));
		$filters[] = array('Feed.user_id'=>$id);
		$this->paginate = array(
			'Feed'=>array(
					'limit'=>2,
					'order'=>array('Feed.name'=>'ASC'),
					'conditions'=>$filters,
				)
		);
		$user_feeds = $this->paginate('Feed');
		$this->set(compact('user_feeds'));
		
        if ($this->request->is('ajax')){
            $this->render('ajax/my_broadcast');
			//exit;
        }
    }*/
	
	/*public function login(){	
    	
    	//pr($this->Auth->login());die;
		$userCookie = $this->Cookie->read('User');		
		if($this->Auth->login()){
            $this->redirect($this->Auth->redirect());
        }else{
            if($this->request->is('post')){
				$this->request->data['User']['password'] = "";
                $this->Session->setFlash(__('Invalid username or password, try again'));
				$this->redirect(array('controller' => 'users', 'action' => 'register'));
            }
        }
    }*/
	
	 
	/*
     * user logout
     */ 
	public function logout(){
		/* $this->Session->delete('access_token');
		$this->Session->delete('Facebook.User');
		$this->Session->delete('Twitter.User');
		$this->Session->delete('GooglePlus.User');
		$this->Session->delete('LinkedIn.User');
		$this->Session->delete('LinkedIn.referer');
		$this->Session->delete('Google.referer');
		$this->Cookie->delete('User');
		unset($_SESSION['oauth']['linkedin']); */
        $this->redirect($this->Auth->logout());
    }

    
    /**
     * @test: recurring payment paypal using express checkout
     */
    public function testPaypalGetExpress() {
	//pr($this->request->data);die;

        if (!empty($this->request->data)) {
            App::import('Vendor', 'paypal/samples/PPBootStrap');
            $logger = new PPLoggingManager('SetExpressCheckout');
            $returnUrl = Router::url(array('controller' => 'users', 'action' => 'testRecurring'), true);
           	$cancelUrl = Router::url(array('controller' => 'users', 'action' => 'payment' ), true);
           	$currencyCode = 'USD';
        	$SubscriptionPlan = $this->SubscriptionPlan->find('first',array('conditions'=>array('SubscriptionPlan.id'=>$this->request->data['User']['subscription_plan_id'])));
			//pr($SubscriptionPlan);die;
			$price= $SubscriptionPlan['SubscriptionPlan']['plan_price'];
			$this->Session->write("User.amount",$price);
			
            $paymentDetails = new PaymentDetailsType();

            $trans_amount = round($price, 2);
            $itemAmount = new BasicAmountType($currencyCode, $trans_amount);
            $itemTotalValue = $trans_amount;
            $itemDetails = new PaymentDetailsItemType();
            $itemDetails->Name = $SubscriptionPlan['SubscriptionPlan']['plan_name'];
            $this->Session->write('PayPal.plan_type', $SubscriptionPlan['SubscriptionPlan']['plan_name']);
            //'Basic';
            //$_REQUEST['itemName'][$i];
            $itemDetails->Amount = $trans_amount;
//                '100.00';
            //$itemAmount;
            $itemDetails->Quantity = 1;
            //$_REQUEST['itemQuantity'][$i];
//            $itemDetails->ItemCategory = $_REQUEST['itemCategory'][$i];
            $itemDetails->ItemCategory = 'Physical';
            //'Digital' Physical;
            //$_REQUEST['itemCategory'][$i];
//            $itemDetails->Tax = new BasicAmountType($currencyCode, $_REQUEST['itemSalesTax'][$i]);	

            $paymentDetails->PaymentDetailsItem[0] = $itemDetails;
//        }

            $orderTotalValue = /* $shippingTotal->value + $handlingTotal->value +
                      $insuranceTotal->value + */$itemTotalValue; //+ $taxTotalValue;
//        $paymentDetails->ShipToAddress = $address;
            $this->Session->write('Paypal.totalAmount', $orderTotalValue);
            $this->Session->write('Paypal.amount', $price);
            $this->Session->write('Paypal.coupon_discount', 5);
            $paymentDetails->ItemTotal = new BasicAmountType($currencyCode, $itemTotalValue);
            $paymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);
//        $paymentDetails->TaxTotal = new BasicAmountType($currencyCode, $taxTotalValue);
            $paymentDetails->PaymentAction = 'Sale';
            //$_REQUEST['paymentType'];
//        $paymentDetails->HandlingTotal = $handlingTotal;
//        $paymentDetails->InsuranceTotal = $insuranceTotal;
//        $paymentDetails->ShippingTotal = $shippingTotal;
            //IPN Listner Url
//        if(isset($_REQUEST['notifyURL']))
//        {
//            $paymentDetails->NotifyURL = $ipnNotificationUrl;
            //$_REQUEST['notifyURL'];
//        }

            $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
            $setECReqDetails->PaymentDetails[0] = $paymentDetails;
            $setECReqDetails->CancelURL = $cancelUrl;
            $setECReqDetails->ReturnURL = $returnUrl;

            // Shipping details
            $setECReqDetails->NoShipping = 1;
            //$_REQUEST['noShipping'];
            $setECReqDetails->AddressOverride = 0;
            //$_REQUEST['addressOverride'];
            $setECReqDetails->ReqConfirmShipping = 0;
            //$_REQUEST['reqConfirmShipping'];
            // Billing agreement
//            None
           $billingAgreementDetails = new BillingAgreementDetailsType(/* $_REQUEST['billingType'] */'RecurringPayments');
          

            $billingAgreementDetails->BillingAgreementDescription = 'test'; //$_REQUEST['billingAgreementText'];
            $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);

            // Display options
//        $setECReqDetails->cppheaderimage = $_REQUEST['cppheaderimage'];
//        $setECReqDetails->cppheaderbordercolor = $_REQUEST['cppheaderbordercolor'];
            $setECReqDetails->cppheaderbackcolor = '000000';
            //$_REQUEST['cppheaderbackcolor'];
            $setECReqDetails->cpppayflowcolor = 'DDA627';
            //$_REQUEST['cpppayflowcolor'];
            $setECReqDetails->cppcartbordercolor = 'FFFFFF';
            //$_REQUEST['cppcartbordercolor'];
            $setECReqDetails->cpplogoimage = 'http://67.205.96.105:8080/voicemail/img/logo.png';
            //$_REQUEST['cpplogoimage'];
//        $setECReqDetails->PageStyle = $_REQUEST['pageStyle'];
            $setECReqDetails->BrandName = 'Vocalist';
            //$_REQUEST['brandName'];
            // Advanced options
//        $setECReqDetails->AllowNote = $_REQUEST['allowNote'];

            $setECReqType = new SetExpressCheckoutRequestType();
            $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
            $setECReq = new SetExpressCheckoutReq();
            $setECReq->SetExpressCheckoutRequest = $setECReqType;

            $paypalService = new PayPalAPIInterfaceServiceService();
            try {
                /* wrap API method calls on the service object with a try catch */
                $setECResponse = $paypalService->SetExpressCheckout($setECReq);
            } catch (Exception $ex) {

                if (isset($ex)) {

                    $ex_message = $ex->getMessage();
                    $ex_type = get_class($ex);

                    if ($ex instanceof PPConnectionException) {
                        $ex_detailed_message = "Error connecting to " . $ex->getUrl();
                    } else if ($ex instanceof PPMissingCredentialException || $ex instanceof PPInvalidCredentialException) {
                        $ex_detailed_message = $ex->errorMessage();
                    } else if ($ex instanceof PPConfigurationException) {
                        $ex_detailed_message = "Invalid configuration. Please check your configuration file";
                    }
                }
                $this->Session->setFlash($ex_detailed_message, 'flash_error');
                $this->redirect(array('controller' => 'users', 'action' => 'register'));
//            $this->Session->setFlash
                // include_once("../Error.php");
                //exit;
            }
            if (isset($setECResponse)) {
                echo "<table>";
                echo "<tr><td>Ack :</td><td><div id='Ack'>$setECResponse->Ack</div> </td></tr>";
                echo "<tr><td>Token :</td><td><div id='Token'>$setECResponse->Token</div> </td></tr>";
                echo "</table>";
              // pr($setECResponse);die;
                if ($setECResponse->Ack == 'Success') {
                    $token = $setECResponse->Token;
                    // Redirect to paypal.com here
                    $payPalURL = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . $token;
                    $this->redirect($payPalURL);
                    echo" <a href=$payPalURL><b>* Redirect to PayPal to login </b></a><br>";
                } else {
//                $this->Session->setFlash($payPalURL, $ex_detailed_message)
                    $this->Session->setFlash('Please fill the form again , There is an error on paypal.' . $setECResponse->Errors[0]->LongMessage, 'flash_error');
                    $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
                }
            }
        } else {
            $this->redirect(array('controller' => 'users', 'action' => 'register'));
        }
    }

    /**
     * To Create Recurring Payments Profile
     * @test:test_adap
     */
    public function testRecurring() {
//        die;
        /* echo 'Return Payment Successful' . '<br>';

          echo 'token : ' . $this->request->query['token'] . '<br>';
          //
          echo 'PayerID : ' . $this->request->query['PayerID'];*/
		
        $this->loadModel('Transaction');

        App::import('Vendor', 'paypal/samples/PPBootStrap');

        $logger = new PPLoggingManager('CreateRecurringPaymentsProfile');

        $currencyCode = "USD";
		if ($this->Session->check('Paypal.totalAmount')) {
            $totalAmount = $this->Session->read('Paypal.totalAmount');
        } else {
            $this->Session->setFlash('Please try again.Your Profile could not be created', 'flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'register'));
        }
//        die;

        $RPProfileDetails = new RecurringPaymentsProfileDetailsType();
        $RPProfileDetails->SubscriberName = $this->Auth->user('first_name') . ' ' . $this->Auth->user('last_name');
//                'abc';
        //$_REQUEST['subscriberName'];

        $dtime = new DateTime();

        $dtime->modify('+30 days');

        $RPProfileDetails->BillingStartDate = $dtime->format(DATE_ATOM);
        //'2013-10-18T07:30:38+00:00';
        //$_REQUEST['billingStartDate'];

        $activationDetails = new ActivationDetailsType();
        $activationDetails->InitialAmount = new BasicAmountType($currencyCode, $totalAmount);
        //ContinueOnFailure CancelOnFailure
        $activationDetails->FailedInitialAmountAction = 'CancelOnFailure';
        //$_REQUEST['failedInitialAmountAction']; 


        $paymentBillingPeriod = new BillingPeriodDetailsType();
        $paymentBillingPeriod->BillingFrequency = 1;
//                $_REQUEST['billingFrequency'];
        $paymentBillingPeriod->BillingPeriod = 'Month';
        //$_REQUEST['billingPeriod'];
        $paymentBillingPeriod->TotalBillingCycles = 0;
        //$_REQUEST['totalBillingCycles'];



        $trans['Transaction']['total_amount'] = $totalAmount;
//echo $totalAmount; die;
        $paymentBillingPeriod->Amount = new BasicAmountType($currencyCode, $totalAmount/* $_REQUEST['paymentAmount'] */);
//        $paymentBillingPeriod->ShippingAmount = new BasicAmountType($currencyCode, $_REQUEST['paymentShippingAmount']);
//        $paymentBillingPeriod->TaxAmount = new BasicAmountType($currencyCode, $_REQUEST['paymentTaxAmount']);

        $scheduleDetails = new ScheduleDetailsType();
        $scheduleDetails->Description = 'test';
        //$_REQUEST['profileDescription'];
        $scheduleDetails->ActivationDetails = $activationDetails;

        $scheduleDetails->PaymentPeriod = $paymentBillingPeriod;
        /* if($_REQUEST['maxFailedPayments'] != "") {
          $scheduleDetails->MaxFailedPayments =  $_REQUEST['maxFailedPayments'];
          }
          if($_REQUEST['autoBillOutstandingAmount'] != "") {
          $scheduleDetails->AutoBillOutstandingAmount = $_REQUEST['autoBillOutstandingAmount'];
          }
         */
        $createRPProfileRequestDetail = new CreateRecurringPaymentsProfileRequestDetailsType();
        $createRPProfileRequestDetail->Token = $this->request->query['token'];

        $createRPProfileRequestDetail->ScheduleDetails = $scheduleDetails;
        $createRPProfileRequestDetail->RecurringPaymentsProfileDetails = $RPProfileDetails;
        $createRPProfileRequest = new CreateRecurringPaymentsProfileRequestType();
        $createRPProfileRequest->CreateRecurringPaymentsProfileRequestDetails = $createRPProfileRequestDetail;


        $createRPProfileReq = new CreateRecurringPaymentsProfileReq();
        $createRPProfileReq->CreateRecurringPaymentsProfileRequest = $createRPProfileRequest;

        $paypalService = new PayPalAPIInterfaceServiceService();

        try {
            /* wrap API method calls on the service object with a try catch */
            $createRPProfileResponse = $paypalService->CreateRecurringPaymentsProfile($createRPProfileReq);
        } catch (Exception $ex) {
//            include_once("../Error.php");

            if (isset($ex)) {

                $ex_message = $ex->getMessage();
                $ex_type = get_class($ex);

                if ($ex instanceof PPConnectionException) {
                    $ex_detailed_message = "Error connecting to " . $ex->getUrl();
                } else if ($ex instanceof PPMissingCredentialException || $ex instanceof PPInvalidCredentialException) {
                    $ex_detailed_message = $ex->errorMessage();
                } else if ($ex instanceof PPConfigurationException) {
                    $ex_detailed_message = "Invalid configuration. Please check your configuration file";
                }
            }
            die;
        }
        if (isset($createRPProfileResponse)) {
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>$createRPProfileResponse->Ack</div> </td></tr>";
            echo "<tr><td>ProfileID :</td><td><div id='ProfileID'>" . $createRPProfileResponse->CreateRecurringPaymentsProfileResponseDetails->ProfileID . "</div> </td></tr>";
            echo "</table>";
                       pr($createRPProfileResponse); die;
            if ($createRPProfileResponse->Ack == "Success" || $createRPProfileResponse->Ack == "SuccessWithWarning") {
                $trans['Transaction']['paypal_profile_id'] = $createRPProfileResponse->CreateRecurringPaymentsProfileResponseDetails->ProfileID;
                $trans['Transaction']['amount'] = $this->Session->read('Paypal.amount');
                $trans['Transaction']['coupon_discount'] = $this->Session->read('Paypal.coupon_discount');
                $trans['Transaction']['user_id'] = $this->Session->read('Business.User.id');
                $this->loadModel('User');
                $userArray = $this->User->findById($this->Session->read('Business.User.id'));

                $trans['Transaction']['country_id'] = $userArray['User']['country_id'];
                $trans['Transaction']['state_id'] = $userArray['User']['state_id'];
                $trans['Transaction']['city_id'] = $userArray['User']['city_id'];
//                $trans['Transaction']['country_id'] = $this->Session->read('Business.User.id');
                //$this->Auth->user('id');
                $trans['Transaction']['plantype'] = $this->Session->read('PayPal.plan_type');
                $trans['Transaction']['subscription_plan_id'] = $this->Session->read('Paypal.subscription_plan_id');
                $trans['Transaction']['additional_feature_id'] = $this->Session->read('Paypal.additional_feature_id');
//                echo $this->Session->read('Paypal.additional_feature_id');die;
                if ($this->Session->read('Paypal.additional_feature_id') == 1 || $this->Session->read('Paypal.additional_feature_id') == 2 || $this->Session->read('Paypal.additional_feature_id') == 3) {
                    $this->loadModel('User');
                    if (!empty($userArray['User']['id'])) {
                        $this->User->id = $userArray['User']['id'];
                        $this->User->saveField('category_sponsor_id', $this->Session->read('Paypal.additional_feature_id'));
                    }
                }
                $trans['Transaction']['payment_type'] = Configure::read('App.Payment.Type.Paypal');
                $trans['Transaction']['type'] = 2;
//                $trans['Transaction']['paypal_profile_id'] = Configure::read('App.Payment.Type.Paypal');

                $this->Transaction->set($trans);
                $this->Transaction->save();

                if (!empty($trans['Transaction']['additional_feature_id']) && empty($trans['Transaction']['subscription_plan_id'])) {
                    $this->loadModel('AdditionalFeatureUser');
                    $feat = array();
                    $feat['AdditionalFeatureUser']['user_id'] = $this->Session->read('Business.User.id');
                    $feat['AdditionalFeatureUser']['additional_feature_id'] = $trans['Transaction']['additional_feature_id'];
                    $this->AdditionalFeatureUser->set($feat);
                    $this->AdditionalFeatureUser->save();
                }

                $this->Session->setFlash('Your paypal payment profile is successfully created.', 'flash_success');
                $this->redirect(array('controller' => 'users', 'action' => 'stepFour'));
            } else {
                $this->Session->setFlash('Please try again.', 'flash_error');
                $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
            }
            $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
            die;
        } else {
//            pr($createRPProfileResponse);
            $this->Session->setFlash('Please try again.', 'flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
        }

//        die;
    }

    /**
     * To Create Recurring Payments Profile
     * @test:test_adap
     */
    public function testGetExpress() {
//        die;
        /* echo 'Return Payment Successful' . '<br>';

          echo 'token : ' . $this->request->query['token'] . '<br>';
          //
          echo 'PayerID : ' . $this->request->query['PayerID']; 
         echo 'getExpress'; pr($this->request); die;*/
        $this->loadModel('Transaction');

        App::import('Vendor', 'paypal/samples/PPBootStrap');


        $logger = new PPLoggingManager('GetExpressCheckout');

        $token = $this->request->query['token'];

        $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);

        $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
        $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

        $paypalService = new PayPalAPIInterfaceServiceService();

//    $totalAmount = $this->Session->read('Paypal.totalAmount');
        $trans['Transaction']['total_amount'] = $this->Session->read('Paypal.totalAmount');
        try {
            /* wrap API method calls on the service object with a try catch */
            $getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
        } catch (Exception $ex) {
//            include_once("../Error.php");

            if (isset($ex)) {

                $ex_message = $ex->getMessage();
                $ex_type = get_class($ex);

                if ($ex instanceof PPConnectionException) {
                    $ex_detailed_message = "Error connecting to " . $ex->getUrl();
                } else if ($ex instanceof PPMissingCredentialException || $ex instanceof PPInvalidCredentialException) {
                    $ex_detailed_message = $ex->errorMessage();
                } else if ($ex instanceof PPConfigurationException) {
                    $ex_detailed_message = "Invalid configuration. Please check your configuration file";
                }
            }
            die;
        }
        if (isset($getECResponse)) {
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>" . $getECResponse->Ack . "</div> </td></tr>";
            echo "<tr><td>Token :</td><td><div id='Token'>" . $getECResponse->GetExpressCheckoutDetailsResponseDetails->Token . "</div></td></tr>";
            echo "<tr><td>PayerID :</td><td><div id='PayerID'>" . $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID . "</div></td></tr>";
            echo "<tr><td>PayerStatus :</td><td><div id='PayerStatus'>" . $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerStatus . "</div></td></tr>";
            echo "</table>";
            /*
              echo '<pre>';
              print_r($getECResponse);
              echo '</pre>'; */

//            pr($getECResponse);
//            die;


            /* if ($this->Session->check('Paypal.totalAmount')) {
              $totalAmount = $this->Session->read('Paypal.totalAmount');
              } else {
              $this->Session->setFlash('Please try again.Your Profile could not be created', 'flash_error');
              $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
              } */
            if ($getECResponse->Ack == "Success" || $getECResponse->Ack == "SuccessWithWarning") {
                $trans['Transaction']['paypal_profile_id'] = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;
                $trans['Transaction']['amount'] = $this->Session->read('Paypal.amount');
                $trans['Transaction']['coupon_discount'] = $this->Session->read('Paypal.coupon_discount');
                $trans['Transaction']['user_id'] = $this->Session->read('Business.User.id');
                $this->loadModel('User');
                $userArray = $this->User->findById($this->Session->read('Business.User.id'));

                $trans['Transaction']['country_id'] = $userArray['User']['country_id'];
                $trans['Transaction']['state_id'] = $userArray['User']['state_id'];
                $trans['Transaction']['city_id'] = $userArray['User']['city_id'];
                //$this->Auth->user('id');
                $trans['Transaction']['plantype'] = $this->Session->read('PayPal.plan_type');
                $trans['Transaction']['subscription_plan_id'] = $this->Session->read('Paypal.subscription_plan_id');
//                echo $this->Session->read('Paypal.additional_feature_id');
//                die;
                $trans['Transaction']['additional_feature_id'] = $this->Session->read('Paypal.additional_feature_id');
                if ($this->Session->read('Paypal.additional_feature_id') == 1 || $this->Session->read('Paypal.additional_feature_id') == 2 || $this->Session->read('Paypal.additional_feature_id') == 3) {
                    $this->loadModel('User');
                    if (!empty($userArray['User']['id'])) {
                        $this->User->id = $userArray['User']['id'];
                        $this->User->saveField('category_sponsor_id', $this->Session->read('Paypal.additional_feature_id'));
                    }
                }
                $trans['Transaction']['payment_type'] = Configure::read('App.Payment.Type.Paypal');
                $trans['Transaction']['type'] = 1;
//                $trans['Transaction']['paypal_profile_id'] = Configure::read('App.Payment.Type.Paypal');

                $this->Transaction->set($trans);
                $this->Transaction->save();

                if (!empty($trans['Transaction']['additional_feature_id']) && empty($trans['Transaction']['subscription_plan_id']) && $trans['Transaction']['additional_feature_id'] == Configure::read('App.AdditionalPlan.CustomAds')) {

                    if ($this->Session->check('Additions.Custom.Ads.Id')) {
                        $this->loadModel('Ad');
                        $this->Ad->id = $this->Session->read('Additions.Custom.Ads.Id');
                        $this->Ad->saveField('view', 1000);
                    }

                    $this->loadModel('AdditionalFeatureUser');
                    $feat = array();
                    $feat['AdditionalFeatureUser']['user_id'] = $this->Session->read('Business.User.id');
                    $feat['AdditionalFeatureUser']['additional_feature_id'] = $trans['Transaction']['additional_feature_id'];
                    $this->AdditionalFeatureUser->set($feat);
                    $this->AdditionalFeatureUser->save();
                }

                $this->Session->setFlash('Your paypal payment profile is successfully created.', 'flash_success');
                $this->redirect(array('controller' => 'users', 'action' => 'stepFour'));
            } else {
                $this->Session->setFlash('Please try again.', 'flash_error');
                $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
            }
            $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
            die;
        } else {
//            pr($createRPProfileResponse);
            $this->Session->setFlash('Please try again.', 'flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
        }

//        die;
    }

    /**
     * @test:payment
     */
    public function payment() {
        $this->Session->setFlash('You cancelled the payment. You can try again.', 'flash_error');
        $this->redirect(array('controller' => 'users', 'action' => 'stepOne'));
        /* echo 'Cancelled Payment';
          pr($this->request);
          die; */
    }

    /**
     * @test:test_ipn
     */
    public function test_ipn() {
        echo 'ipn payment';
        pr($this->request);
        die;
    }
    
    
    
    
    
    
    
    
    
	
		
	/*
     * p
     */
	public function payment_standard_adaptive($product_array){

		$this->Cookie->write('sender_email',$this->request->data['Transaction']['email'],$encrypt=false,3600);
		$this->pay_payment();
	}
	
	public function payment_recurring_adaptive(){

			
						
		App::import('Vendor','php_nvp_sdk/Lib/CallerService');
		App::import('Vendor','php_nvp_sdk/Samples/Common/NVP_SampleConstants');
		try {
				$returnURL = Configure::read('App.SiteUrl').'/users/preapproval_detail';
				$cancelURL = Configure::read('App.SiteUrl');
			//	echo $returnURL; echo $cancelURL;
			$UserDetail = $this->Session->read("User");
			/* load subscription plan model */ 
			$this->loadModel("SubscriptionPlan");
			$this->loadModel("Offer");
			//$offer = $this->Offer->find('first',array('conditions'=>array('Offer.promo_code'=>$UserDetail['promo_code'])));
			$SubscriptionPlan = $this->SubscriptionPlan->find('first',array('conditions'=>array('SubscriptionPlan.id'=>$UserDetail['subscription_plan_id'])));
			//pr($SubscriptionPlan);die;
			$price= $SubscriptionPlan['SubscriptionPlan']['plan_price'];
			$this->Session->write("User.amount",$price);
			
				
				
				
				
				
				
				$day				=		'';	
				$str_time 			= 		new DateTime();
				$end_time 			= 		new DateTime();
				
				if($SubscriptionPlan['SubscriptionPlan']['plan_type']==1){
					$Period			=		'MONTHLY';
					$day			=		30;
				}	
				
				if($SubscriptionPlan['SubscriptionPlan']['plan_type']==2){
					$Period			=		'ANNUALLY';
					$day			=		365;
				}
				$duration			=		$SubscriptionPlan['SubscriptionPlan']['plan_duration'];
				$end_date			=		$day*($duration);
				$start_date			=		$str_time->format(DATE_ATOM);
				$end_date			=		$end_time->format(DATE_ATOM);
			//	$price				=		$product_array['Product']['price']+($product_array['Product']['rebill_price']*($duration - 1));
				
		//	$this->Cookie->write('sender_email',$this->request->data['Payment']['email'],$encrypt=false,3600);					
				
				$feesPayer		=		'PRIMARYRECEIVER';
				$request_array = array (
					Preapproval::$cancelUrl => $cancelURL,
					Preapproval::$returnUrl => $returnURL,
					Preapproval::$currencyCode => Configure::read('App.CurrencyCode'),
					Preapproval::$startingDate =>$start_date,
					Preapproval::$endingDate => $end_date,
					Preapproval::$maxNumberOfPayments => $duration,
					Preapproval::$maxTotalAmountOfAllPayments => $price,
									
					Preapproval::$feesPayer => $feesPayer,
					
					Preapproval::$paymentPeriod => $Period,
					Preapproval::$displayMaxTotalAmount => 'true',
					/* Preapproval::$ipnNotificationUrl =>  $_REQUEST['ipnNotificationUrl'] , */
					Preapproval::$requestEnvelope_senderEmail =>  $this->request->data['Transaction']['email'],
					RequestEnvelope::$requestEnvelopeErrorLanguage => 'en_US'
				);
				
				//pr($request_array);die;
				$nvpStr=http_build_query($request_array, '', '&');
				
				$resArray=hash_call("AdaptivePayments/Preapproval",$nvpStr);
				// pr($resArray);die;	
				$ack = strtoupper($resArray['responseEnvelope.ack']);

				if($ack=="SUCCESS"){
					$this->Cookie->write('preapprovalKey',$resArray['preapprovalKey'],$encrypt=false,3600);
					$payPalURL = PAYPAL_REDIRECT_URL.'_ap-preapproval&preapprovalkey='.$resArray['preapprovalKey'];
					$this->redirect($payPalURL);
				}else{
					$this->Session->setFlash(__('There is might be some errors, please try again........'),'flash_error');
				}
				
		}
		catch(Exception $ex) {
			$this->Session->setFlash(__('There is might be some errors, please try again.........'),'flash_error');
		
		}
				
	}

	
	public function preapproval_detail(){
		
		$product_array=$this->Cookie->read('product_array');
		
		App::import('Vendor','php_nvp_sdk/Lib/CallerService');
		App::import('Vendor','php_nvp_sdk/Samples/Common/NVP_SampleConstants');
		
		try {
			
			$preapprovalKey = $this->Cookie->read('preapprovalKey');
			
			$request_array= array (
				PreapprovalDetail::$preapprovalKey=> $preapprovalKey,
				RequestEnvelope::$requestEnvelopeErrorLanguage => 'en_US'
			);
			
			$nvpStr=http_build_query($request_array, '', '&');
		    $resArray=hash_call("AdaptivePayments/PreapprovalDetails",$nvpStr);
			//pr($resArray);die;
			$ack = strtoupper($resArray["responseEnvelope.ack"]);
			
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING"){
				
				$this->loadModel('UserSite');
				$this->loadModel('TransactionPreapproval');
				$this->loadModel('Hoplink');
				//$product_array=$this->Session->read('product_array');
				
				
				$usrsite = $this->UserSite->findByUserId($product_array['Product']['user_id']);
				$track_id = $this->Cookie->read('track_id'.$usrsite['UserSite']['id']);
				$hop=$this->Hoplink->find('first',array('conditions'=>array('Hoplink.track_id'=>$track_id)));
				
				/*data*/
				
				$data_approval['TransactionPreapproval']['affiliate_id']=$hop['Hoplink']['affiliate_id'];
				$data_approval['TransactionPreapproval']['seller_id']=$product_array['Product']['user_id'];				
				$data_approval['TransactionPreapproval']['track_id']=$track_id;			
				$data_approval['TransactionPreapproval']['no_of_payments']=$resArray['maxNumberOfPayments'];				
				$data_approval['TransactionPreapproval']['total_amount']=$resArray['maxTotalAmountOfAllPayments'];
				$data_approval['TransactionPreapproval']['period']=$resArray['paymentPeriod'];
				$data_approval['TransactionPreapproval']['buyer_email']=$resArray['senderEmail'];
				$data_approval['TransactionPreapproval']['start_date']=$resArray['startingDate'];
				$data_approval['TransactionPreapproval']['end_date']=$resArray['endingDate'];
				$data_approval['TransactionPreapproval']['product_id']=$product_array['Product']['id'];
				$data_approval['TransactionPreapproval']['fees_payer']=$resArray['feesPayer'];
				$data_approval['TransactionPreapproval']['preapproval_key']=$preapprovalKey;
				$data_approval['TransactionPreapproval']['created']=date('Y-m-d H:i:s');
				$trans_approve	=	$this->TransactionPreapproval->find('first',array('conditions'=>array('preapproval_key'=>$preapprovalKey)));
				if(empty($trans_approve)){
					$this->TransactionPreapproval->save($data_approval);				
					$this->pay_payment($preapprovalKey);
				}else{
					//$this->Session->setFlash(__('Transaction has been completed Successfully...'),'flash_success');
					$this->redirect($product_array['Product']['thank_you_page']);
					//$this->redirect(Configure::read('App.SiteUrl'));
				}
			}else{
				$this->Session->setFlash(__('There is might be some errors, please try again.......'),'flash_error');
				$file 				= 		fopen("test.txt","a");
				fwrite($file,'---------------'.PHP_EOL);
				fwrite($file,json_encode($resArray).PHP_EOL);
				fwrite($file,'---------------'.PHP_EOL);
				$this->redirect(Configure::read('App.SiteUrl'));
				//$this->redirect('http://'.$product_array['Product']['item'].'.'.$product_array['Seller']['nick_name'].'.pay.clickdom.com/');
			}
		}
		catch(Exception $ex) {
			$this->Session->setFlash(__('There is might be some errors, please try againo'.$ex->getMessage()),'flash_error');
			$this->redirect(Configure::read('App.SiteUrl'));
			//$this->redirect('http://'.$product_array['Product']['item'].'.'.$product_array['Seller']['nick_name'].'.pay.clickdom.com/');
		}
		
		die;
	}
	
	
	
	public function payment1($item_id=null, $seller_nick=null){
		
		if(empty($seller_nick) || empty($item_id)){
			$this->redirect(Configure::read('App.DomainSiteUrl'));
		}
		
		$this->Cookie->delete('preapprovalKey');
		$this->Cookie->delete('product_array');
		$this->Cookie->delete('seller_id');
		$this->Cookie->delete('sender_email');
		$this->Cookie->delete('buyer_email');
		$this->Cookie->delete('buyer_name');
		$this->Cookie->delete('User_payKey');
		
		$this->loadModel('Hoplink');
		$this->loadModel('Transaction');
		$this->loadModel('Setting');
		$this->loadModel('User');
		$this->loadModel('Week');
		$this->loadModel('UserSite');
		
		
		$this->User->bindModel(
			array( 'belongsTo' => array( 'Frequency' )),false
		);
		$seller_array = $this->User->find('first',array('conditions'=>array('User.nick_name'=>$seller_nick),'fields'=>array('id')));
		$this->Product->bindModel(
			array(
				'belongsTo'=>array(
					'Seller'=>array(
						'className'=>'User',
						'foreignKey'=>'user_id',
						'fields'=>array('Seller.id','Seller.paypal_email','Seller.paypal_api_signature','Seller.paypal_api_password','Seller.paypal_api_username')
					)
				)
			),false
		);
		$this->set(compact('seller_nick','item_id'));
		$product_array = $this->Product->find('first',array('conditions'=>array('Product.item'=>$item_id,'Product.user_id'=>$seller_array['User']['id'])));
		// if product is rejected/ not approved/pending approval/approval requested from admin then show a special page
		if(!empty($product_array)){
			if($product_array['Product']['status'] != 2){
				$this->redirect(array('controller' => 'products','action' => 'temporary_removed',base64_encode($product_array['Product']['id'])));
			}
		}
		
		
		$usrsite = $this->UserSite->findByUserId($product_array['Product']['user_id']);
		
		// code start to track affiliate default
		$track_id = $this->Cookie->read('track_id'.$usrsite['UserSite']['id']);
		if(!empty($track_id)){
			// if already set cookie is not related to same product then delete that
			$hoplink 		= 		$this->Hoplink->find('first',array('conditions'=>array('Hoplink.track_id'=>$track_id, 'Hoplink.product_id'=>$product_array['Product']['id'])));
			if(empty($hoplink)){
				$track_id		=		'';
				$this->Cookie->delete('track_id'.$usrsite['UserSite']['id']);
			}
		}
		if(empty($track_id)){
			$hoplink 		= 		$this->Hoplink->find('first',array('conditions'=>array('Hoplink.affiliate_id'=>1, 'Hoplink.product_id'=>$product_array['Product']['id'])));
			{
				if(empty($hoplink)){
					$hoplink['Hoplink']['track_id']		=	uniqid();
					$hoplink['Hoplink']['affiliate_id'] = 	1;
					$hoplink['Hoplink']['seller_id'] 	= 	$product_array['Product']['user_id'];
					$hoplink['Hoplink']['user_site_id'] = 	$product_array['Product']['user_site_id'];
					$hoplink['Hoplink']['product_id'] 	= 	$product_array['Product']['id'];
					$this->Hoplink->save($hoplink);					
				}
				$this->Cookie->write('track_id'.$hoplink['Hoplink']['user_site_id'], $hoplink['Hoplink']['track_id'], $encrypt = false , 3600*24*60);
				
			}
		}	
		$track_id = $this->Cookie->read('track_id'.$usrsite['UserSite']['id']);
		// code end to track affiliate default		
		
		
		
		$hop=$this->Hoplink->find('first',array('conditions'=>array('Hoplink.track_id'=>$track_id)));
		$affiliate_det = $this->User->findById($hop['Hoplink']['affiliate_id']);
		//pr($hop);
		$affiliate_nick = $affiliate_det['User']['nick_name'];
		// echo $affiliate_nick ;
		$this->set(compact('affiliate_nick'));
		if(empty($product_array)){		
			
			$this->redirect(Configure::read('App.DomainSiteUrl'));
		}
		
		$this->Cookie->write('product_array',$product_array['Product']['id'],$encrypt=false,3600);
		$this->Cookie->write('seller_id',$seller_array['User']['id'],$encrypt=false,3600);
		
		if($this->request->is('post') || $this->request->is('put')) {
			if(!empty($this->request->data)){
				
				$this->Transaction->set($this->request->data);
				$this->Transaction->setValidation('Payment');
					
				if ($this->Transaction->validates()) 
				{	
				
							
					$this->Cookie->write('buyer_name',$this->request->data['Transaction']['buyer_name'],$encrypt=false,3600);
					if($this->request->data['Transaction']['payment_method']==1){ 
						if($product_array['Product']['is_recurring']==1){
							$this->payment_recurring_adaptive($product_array);	
						}else{			
							$this->payment_standard_adaptive($product_array);
						}
					}else{
							// cash/bank transfer
							$this->Cookie->write('buyer_email',$this->request->data['Transaction']['email'],$encrypt=false,3600);							
							$this->pay_cash($this->request->data['Transaction']['payment_method']);
					}
				}
			}
		}
		$this->loadModel('Country');
		$countries = $this->Country->find('list',array('conditions'=>array('Country.status'=>Configure::read('App.Status.active')),'order'=>array('name'=>'ASC'),'fields'=>array('iso2','name')));
		$this->loadModel('ProductsToPaymentMethod');
		$this->ProductsToPaymentMethod->bindModel(
				array(
					'belongsTo'=>array(
						'PaymentMethod'=>array(
							'className'=>'PaymentMethod',
							'foreignKey'=>'payment_method_id',
							'conditions'   => array('PaymentMethod.status' => 1)							
						)
					)
				),false
		);	
		//pr($product_array['Product']);die;
		$paymentMethods		=	$this->ProductsToPaymentMethod->find('all',array('conditions'=>array('ProductsToPaymentMethod.product_id'=>$product_array['Product']),'order'=>'ProductsToPaymentMethod.payment_method_id ASC'));
		$this->set(compact('countries','product_array','paymentMethods'));
		
		
	}
	
	
	
	
	public function pay_payment($pre_key=null){
	
		App::import('Vendor','php_nvp_sdk/Lib/CallerService');
		App::import('Vendor','php_nvp_sdk/Samples/Common/NVP_SampleConstants');
		
		$this->loadModel('UserSite');
		$this->loadModel('User');
		$this->loadModel('Transaction');
		$this->loadModel('Week');
		$this->loadModel('Hoplink');
		
		$this->Hoplink->bindModel(
				array(
					'belongsTo'=>array(
						'Affiliate'=>array(
							'className'=>'User',
							'foreignKey'=>'affiliate_id',
							'fields'=>array('Affiliate.id','Affiliate.paypal_email')
						)
					)
				),false
		);		
		
		$product_array				=		$this->product_info($this->Cookie->read('product_array'));
		$usrsite 					= 		$this->UserSite->findByUserId($product_array['Product']['user_id']);	
		$track_id 					= 		$this->Cookie->read('track_id'.$usrsite['UserSite']['id']);
		$hop						=		$this->Hoplink->find('first',array('conditions'=>array('Hoplink.track_id'=>$track_id)));			
		try {
		
					
					
			$product_price				=			$product_array['Product']['price'];
			$product_price				=			str_replace(',','',$product_price);
			$affiliate_commision		=			$product_array['Product']['commission'];
			$affiliate_revenue 			= 			($affiliate_commision / 100) * $product_price ;
			$affiliate_revenue			=			round($affiliate_revenue,2);
			$admin_revenue 				= 			$this->getAdminFees($product_price);			
			$seller_revenue 			= 			$product_price;
			// first seller will get all the payment then he will pay affiliate_revenue and admin_revenue and keep his part 
			
											
			if(!empty($pre_key)){
				$preapprovalKey=$pre_key;   // pre-approval key in case of recurring payment
			}else{
				$preapprovalKey='';
			}
			
			$senderEmail  			= 			$this->Cookie->read('sender_email');
			$buyerName  			= 			$this->Cookie->read('buyer_name');
			$action_type			=			"PAY";
			$feesPayer				=			'PRIMARYRECEIVER';
			$admin_affiliate		=			0;
			if($admin_revenue == 0){
				$primary_receiver		=			array('true','false');
				$receiver_emails		=			array($product_array['Seller']['paypal_email'],$hop['Affiliate']['paypal_email']);
				$receiver_amount		=			array($seller_revenue,$affiliate_revenue);
			}else{			
				$primary_receiver		=			array('true','false','false');
				$receiver_emails		=			array($product_array['Seller']['paypal_email'],$hop['Affiliate']['paypal_email'],Configure::read('App.paypal_email'));
				$receiver_amount		=			array($seller_revenue,$affiliate_revenue,$admin_revenue);
				
				if($hop['Affiliate']['paypal_email']==Configure::read('App.paypal_email')){
					// in case of if admin default paypal is same to admin's paypal email then give total
					//commission to one account only
					$primary_receiver		=			array('true','false');
					$receiver_emails		=			array($product_array['Seller']['paypal_email'],$hop['Affiliate']['paypal_email']);
					$receiver_amount		=			array($seller_revenue,$affiliate_revenue + $admin_revenue);
					$admin_affiliate		=			1;
				}
			}
			
			if(in_array($_SERVER['HTTP_HOST'],Configure::read('App.Development_Ips'))){
				$returnURL 		= 	Router::url(array(
									'controller' => 'products',
										'action' => 'test_adap'
									), true);
				$cancelURL 		= 	Router::url(array(
										'controller' => 'products',
										'action' => 'payment'
									), true);
				$ipnNotificationUrl	=		Router::url(array(
												'controller'=>'products',
												'action'=>'test_ipn'
													),true);
			}else{
				
				$returnURL = Configure::read('App.DomainSiteUrl').'/products/test_adap';
				$cancelURL = Configure::read('App.DomainSiteUrl').'/products/payment';
				$ipnNotificationUrl=Configure::read('App.DomainSiteUrl').'/products/test_ipn';
			}	
			$paypal_track_id		=	time();
			$request_array= array(
				Pay::$actionType => $action_type,
				Pay::$cancelUrl  => $cancelURL,
				Pay::$returnUrl=>   $returnURL,
				Pay::$ipnNotificationUrl=>   $ipnNotificationUrl,
				Pay::$currencyCode  => Configure::read('App.CurrencyCode'),
				Pay::$clientDetails_deviceId  => DEVICE_ID,
				Pay::$clientDetails_ipAddress  => '127.0.0.1',
				Pay::$clientDetails_applicationId =>APPLICATION_ID,
				RequestEnvelope::$requestEnvelopeErrorLanguage => 'en_US',
				Pay::$memo => 'This Is The Memo | '.$paypal_track_id,
				Pay::$trackingId =>$paypal_track_id,
				Pay::$feesPayer => $feesPayer
			);
						
			$i = 0;
			$j = 0;
			$k = 0;
			//pr($receiver_emails);die;
			foreach ($receiver_emails as $value)
			{
				$request_array[Pay::$receiverEmail[$i]] = $value;
				$i++;
			}
			
			foreach ($receiver_amount as $value)
			{
				$request_array[Pay::$receiverAmount[$j]] = $value;
				$j++;
			}
			
			foreach ($primary_receiver as $value)
			{
				$request_array[Pay::$primaryReceiver[$k]] = $value;
				$k++;
			}			

			if($preapprovalKey!= "")
			{
				$request_array[Pay::$preapprovalKey] = $preapprovalKey;
			}
			
			if($senderEmail!= "")
			{
				$request_array[Pay::$senderEmail]  = $senderEmail;
			}		
			$nvpStr		=			http_build_query($request_array, '', '&');					
			$resArray	=			hash_call('AdaptivePayments/Pay',$nvpStr);
			$ack 		= 			strtoupper($resArray['responseEnvelope.ack']);
			//echo "<pre>";print_r($resArray);die;
			if($ack=="SUCCESS"){	
					
				$this->loadModel('UserSite');
				$this->loadModel('User');
				$this->loadModel('Transaction');
				$this->loadModel('Week');
				$this->loadModel('Hoplink');				
				
				$seller_revenue		=		0;
				$affiliate_revenue	=		0;
				$admin_revenue		=		0;
			
				if(isset($receiver_amount[0])){ // seller
					$total_amount		=	$receiver_amount[0];
				}
				
				if(isset($receiver_amount[1])){ // affiliate
					$affiliate_revenue	=		$receiver_amount[1];
				}
				
				if(isset($receiver_amount[2])){ // admin
					$admin_revenue		=		$receiver_amount[2];
					
				}
				$this->request->data['Transaction']								=		array();
				$this->request->data['Transaction']['affiliate_trans_id']		=		'';
				$this->request->data['Transaction']['admin_trans_id']			=		'';
				$this->request->data['Transaction']['seller_revenue']			=		$total_amount - ($affiliate_revenue + $admin_revenue);
				$this->request->data['Transaction']['affiliate_revenue']		=		$affiliate_revenue;
				$this->request->data['Transaction']['admin_revenue']			=		$admin_revenue;			
				$this->request->data['Transaction']['amount']					=		$total_amount;		
				$this->request->data['Transaction']['product_id']				=		$product_array['Product']['id'];
				$this->request->data['Transaction']['seller_id']				=		$product_array['Product']['user_id'];
				$this->request->data['Transaction']['affiliate_id']				=		$hop['Hoplink']['affiliate_id'];
				$this->request->data['Transaction']['track_id']					=		$hop['Hoplink']['track_id'];
				$this->request->data['Transaction']['hoplink_id']				=		$hop['Hoplink']['id'];
				$this->request->data['Transaction']['item']						=		$product_array['Product']['item'];
				$this->request->data['Transaction']['status']					=		1;			
				$this->request->data['Transaction']['paypal_status']			=		'PENDING';
				$this->request->data['Transaction']['pay_key']					=		$resArray['payKey'];
				$this->request->data['Transaction']['preapproval_key']			=		$this->Cookie->read('preapprovalKey');
				$this->request->data['Transaction']['payment_method']			=		1;
				$this->request->data['Transaction']['is_paid']					=		1;
				$this->request->data['Transaction']['affiliate_is_paid']		=		1;
				$this->request->data['Transaction']['paypal_track_id']			=		$paypal_track_id;	
				$this->request->data['Transaction']['buyer_name']				=		$buyerName;
				if($this->Transaction->saveAll($this->request->data['Transaction'])){
			
					//pr(parent::DisplayQuery('Transaction'));
					$paykey  = 		$resArray['payKey'];	
					$this->Cookie->write('User_payKey',$paykey,$encrypt=false,3600);
					if(!empty($pre_key)){
						
						$payPalURL = $returnURL;
											
					}else{
						$payPalURL = PAYPAL_REDIRECT_URL.'_ap-payment&paykey='.$paykey;
					}
					$this->redirect($payPalURL);			
				}else{
					$this->Session->setFlash(__('There is might be some errors, please try again...'),'flash_error');
				}
			
			}
			else
			{
				$this->Session->setFlash($resArray['error(0).message'],'flash_error');
				$this->redirect(array('controller'=>'products','action'=>'payment'));
			}
			
		}
		catch(Exception $ex) {
			$this->Session->setFlash($ex->getMessage(),'flash_error');
			$this->Session->setFlash(__('There is might be some errors, please try again....'),'flash_error');
			
		}
	}
	
	public function test_adap($pay_key = 0){
	
		//echo 12;die;
		App::import('Vendor','php_nvp_sdk/Lib/CallerService');
		App::import('Vendor','php_nvp_sdk/Samples/Common/NVP_SampleConstants');
		if($pay_key != 0){
			$payKey		=	$pay_key;
			// this is for IPN URL ( test_ipn )
		}
		$payKey 			= 		$this->Cookie->read('User_payKey');
		$request_array = array (
			PaymentDetails::$payKey => $payKey,
			RequestEnvelope::$requestEnvelopeErrorLanguage=> 'en_US'
		);
		
		$nvpStr				=		http_build_query($request_array, '', '&');
		$resArray			=		hash_call("AdaptivePayments/PaymentDetails",$nvpStr);		
		$ack 				= 		strtoupper($resArray["responseEnvelope.ack"]);
		
		if($ack=="SUCCESS"){
			
			$this->loadModel('Transaction');
			$this->loadModel('UserSite');
			$this->loadModel('User');
			$this->loadModel('Week');
			$this->loadModel('Hoplink');
			$transactionDetails	=	$this->Transaction->find('first',array('conditions'=>array('pay_key'=>$resArray['payKey'],'paypal_track_id'=>$resArray['trackingId'])));
			if(isset($transactionDetails['Transaction']) && !empty($transactionDetails['Transaction'])){
			
				$product_array		=		$this->product_info($transactionDetails['Transaction']['product_id']);
				$this->request->data['Transaction']['id']	=	$transactionDetails['Transaction']['id'];
				$seller_revenue		=		0;
				$affiliate_revenue	=		0;
				$admin_revenue		=		0;
				
				if(isset($resArray['paymentInfoList.paymentInfo(0).receiver.amount'])){ // seller
					$total_amount		=	$resArray['paymentInfoList.paymentInfo(0).receiver.amount'];
				}
				
				if(isset($resArray['paymentInfoList.paymentInfo(1).receiver.amount'])){ // affiliate
					$affiliate_revenue	=		$resArray['paymentInfoList.paymentInfo(1).receiver.amount'];
				}
				
				if(isset($resArray['paymentInfoList.paymentInfo(2).receiver.amount'])){ // admin
					$admin_revenue		=		$resArray['paymentInfoList.paymentInfo(2).receiver.amount'];
					
				}
				$this->request->data['Transaction']['trans_id']=$resArray['paymentInfoList.paymentInfo(0).transactionId'];
				if(isset($resArray['paymentInfoList.paymentInfo(1).transactionId'])){
					$this->request->data['Transaction']['affiliate_trans_id']=$resArray['paymentInfoList.paymentInfo(1).transactionId'];
				}
				if(isset($resArray['paymentInfoList.paymentInfo(2).transactionId'])){
					$this->request->data['Transaction']['admin_trans_id']=$resArray['paymentInfoList.paymentInfo(2).transactionId'];
				}			
				
				$this->request->data['Transaction']['paypal_status']			=		$resArray['status'];				
				$this->request->data['Transaction']['action_type']				=		$resArray['actionType'];
				$this->request->data['Transaction']['fees_payer']				=		$resArray['feesPayer'];
				$this->request->data['Transaction']['buyer_email']				=		$resArray['senderEmail'];
				$this->request->data['Transaction']['email']					=		$resArray['senderEmail'];
				$this->request->data['Transaction']['is_paid']					=		2; // mark as paid
				$this->request->data['Transaction']['affiliate_is_paid']		=		2;
				$this->request->data['Transaction']['paid_date']				=		date('Y-m-d H:i:s');
				$this->request->data['Transaction']['affiliate_paid_date']		=		date('Y-m-d H:i:s');
				$this->request->data['Transaction']['payment_method']			=		1;	
							
				if($this->Transaction->saveAll($this->request->data['Transaction'])){			
					$this->buy_global_action($this->Transaction->id);
					//$this->Session->setFlash(__('Transaction has been completed Successfully'),'flash_success');					 	
					$this->redirect($product_array['Product']['thank_you_page']);
				}else{ 
					$this->Session->setFlash(__('There is might be some errors, please try again.'),'flash_error');
				}
				
			}
		}else{
			$this->Session->setFlash(__('There is might be some errors, please try again..'),'flash_error');
		}		
		 $this->redirect(Configure::read('App.SiteUrl'));
		//pr($resArray);die;
		

	}
	
	
	
	
	
	
	
	public function registerOld()
	{	
		$this->set('title_for_layout', __('Register'));	
		$userCookie = $this->Cookie->read('User');		
		if($this->Auth->login()){
			if($this->Cookie->read('User')){
				$this->request->data = $this->Cookie->read('User');
			}
				
			$cookie = array();
			if(empty($userCookie)){
				$cookie = base64_encode(serialize($this->Session->read('Auth.User')));
				$this->Cookie->write('User',  $cookie, true, '+1 years');
			}				
			$this->redirect($this->Auth->redirect());
			
		}elseif(!empty($userCookie))
		{
			$val = unserialize(base64_decode($this->Cookie->read('User')));
			$userData = $this->User->findById($val['id']);
			if(!empty($userData)){
				$this->Auth->login($val);
				$this->redirect($this->Auth->redirect());			
			}
		}else{
		if ($this->request->is('post')) 
			{
				if (!empty($this->request->data)) 
				{
					$this->User->set($this->request->data['User']);
					$this->User->setValidation('register');
					
					$name 	  = $this->request->data['User']['display_name'];				
					$verification_code = substr(md5(uniqid()), 0, 20);
					$this->request->data['User']['verification_code'] = $verification_code;
					$this->request->data['User']['status'] = '0';
					
					if($this->User->validates()) 
					{	
						$this->request->data['User']['password'] = Security::hash($this->request->data['User']['password1'], null, true);
						$this->request->data['User']['username'] = $this->request->data['User']['username1'];
						$this->request->data['User']['ip'] = $this->RequestHandler->getClientIp();
						$enter_password = $this->request->data['User']['password1'];
						$username = $this->request->data['User']['username1'];
						$password = $this->request->data['User']['password'];
						
						if($this->User->saveAll($this->request->data))
						{
						/****************** EMAIL NOTIFICATION MESSAGE ********************/	
						
							$to      = $this->request->data['User']['email'];
							$from    = Configure::read('App.AdminMail');
							$mail_message = '';
							$this->loadModel('Template');
							$registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_registration')));
							$email_subject = $registrationMail['Template']['subject'];
							$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
							$activationCode = $this->request->data['User']['verification_code'];
							$activation_url = Router::url(array(
											'controller' => 'users',
											'action' => 'activate',
											base64_encode($this->request->data['User']['email']),
											$verification_code,
											), true);
			
							$activation_link	=	'<a href="'.$activation_url.'">'.$activation_url.'</a>';
							
							$mail_message = str_replace(array('{USERNAME}','{PASSWORD}','{ACTIVATION_LINK}', '{activation_code}', '{NAME}', '{SITE}'), array($username, $enter_password,$activation_link, $activationCode, $name, Configure::read('App.SITENAME')), $registrationMail['Template']['content']);
							//echo "<br>";
							//echo "<pre>";pr($this->request->data);die('octal');
							//die();
							$template = 'default';
							$this->set('message', $mail_message);
							//echo $mail_message;die;
							//echo $to."_".$subject."_".$mail_message."_".$from."_".$template;die;
							parent::sendMail($to, $subject, $mail_message, $from, $template);
							/****************** EMAIL NOTIFICATION MESSAGE ********************/
							$this->Session->setFlash(__('The user has been registered successfully. A verification link has been sent to your email account. You will be able to login after successful verification.', true), 'flash_success');
							$this->redirect(array('controller' => 'users', 'action' => 'register'));
						}				 
						else 
						{
							$this->Session->setFlash(__('The user could not be registerd. Please, try again.', 'flash_error'));
						}
					}
					else 
					{
						$this->Session->setFlash(__('Please correct error listed below, try again', 'flash_error'));
					}
				}
			}
			
		}
	}
		
	function activate($email = null, $verification_code = null)
	{
	    $this->layout	= 'default';
		if ($email == null || $verification_code == null) 
		{
			$this->Session->setFlash(__('Error_Message',true), 'admin_flash_bad');
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
		$email = base64_decode($email);
	
		if ($this->User->hasAny(array(
									'User.email' => $email,
									'User.verification_code' => $verification_code,
									//'User.status' => 0
									)
									))
		{
			$user = $this->User->findByEmail($email);
			//activation date code
			$this->User->updateAll(array('User.modified'=>"'".date('Y-m-d H-i-s')."'"));
		//activation date code close	
			$this->User->id = $user['User']['id'];
			$this->User->saveField('status', 1);
			$this->User->saveField('is_email_verified', 1);
			$this->User->saveField('verification_code', substr(md5(uniqid()), 0, 20));
			
			$to      = $email;			
			$from    = Configure::read('App.AdminMail');
			$mail_message = '';
			$this->loadModel('Template');
			$notificationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'verify_email')));
			$email_subject = $notificationMail['Template']['subject'];
			$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
			$login_url = Router::url(array(
									'controller' => 'users',
									'action' => 'register'
									), true);
		
			$login_link	=	'<a href="'.$login_url.'">Click Here To Login</a>';
			$mail_message = str_replace(array('{NAME}','{SITE}','{LOGIN_LINK}'), array($user['User']['first_name'].''.$user['User']['last_name'],Configure::read('App.SITENAME'),$login_link), $notificationMail['Template']['content']);
			
			$template = 'default';
			
			$this->set('message', $mail_message);						
			parent::sendMail($to, $subject, $mail_message, $from, $template);
			//$this->Session->setFlash(__('Your Email is verified'));
			$this->redirect(array('controller' => 'users', 'action' => 'success'));
		}
		else
		{
			$this->Session->setFlash(__('Verification Failed'));		
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
	}
	
	function success() {
	      $this->layout	= 'default';
        /* if ($this->Auth->user()){
            $this->redirect(array('controller' => 'programs', 'action' => 'my_program'));
        } */
        $this->set("title_for_layout",__('Success',true));
    }
	
	function update_profile($id = null)
	{	
		$id = $this->Auth->User('id');
		$this->User->id = $id;
		$this->loadModel('UserImage');	
		$this->User->bindModel(array('hasMany'=>array('UserImage')),false);
        $imageInfo = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('User.profile_image','User.profile_cover_image','User.fb_id','User.id','User.twitter_id','User.linkdin_id','User.social_media_image_url','username')));
        if (!$this->User->exists()){
			$this->redirect(array('controller'=>'users', 'action'=>'logout'));
            throw new NotFoundException(__('Invalid user'));
        }
		$userData = $this->User->read(null, $id);
		
		if((empty($userData['User']['email']))||(empty($userData['User']['first_name']))||($userData['User']['gender']==0)||(empty($userData['User']['dob']))||(empty($userData['User']['username']))){
					$AllFields = "";
					if(empty($userData['User']['email'])){
						$AllFields = "email";
					}	
            $this->redirect(array('controller' => 'users', 'action' => 'save_missing_fields'));
		}
		
		if($this->request->is('post') || $this->request->is('put')){
			if(!empty($this->request->data)) 
			{
					if(!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) 
					{
						$blackHoleCallback = $this->Security->blackHoleCallback;
						$this->$blackHoleCallback();
					}
					$this->User->set($this->request->data['User']);
					$this->User->setValidation('update_profile');
					
					if ($this->User->validates()) {
						$this->User->create();
						
						if(!empty($this->request->data['User']['profile_image']['tmp_name']))
						{						
							$file = $this->request->data['User']['profile_image'];
						}
						else
						{
							$file = '';
						}
						
						
						if(isset($this->request->data['UserImage']['extra_image'][0]['tmp_name']) && empty($this->request->data['UserImage']['extra_image'][0]['tmp_name']))
						{
							unset($this->request->data['UserImage']);
						}	
						//pr($this->request->data);die;
						$images	= array();					
						$postExtraImage = 0;
						if (!empty($this->request->data['UserImage']['extra_image'])) {
							$images = $this->request->data['UserImage']['extra_image'];
							$postExtraImage = count($images);
						}
						unset($this->request->data['User']['profile_image']);	
							
						if(!empty($this->request->data['User']['full_address'])){
							$addArr = explode(',',$this->request->data['User']['full_address']);				
							$this->request->data['User']['city']=$addArr[0];
							$this->request->data['User']['state']=$addArr[1];
							$this->request->data['User']['country']=$addArr[2];
						}
						if ($this->User->save($this->request->data)){
														
							$this->Session->setFlash(__('The User information has been updated successfully', true), 'admin_flash_success');
							$this->redirect(array('action'=>'update_profile'));
						}
						else 
						{

							$this->Session->setFlash(__('The User could not be saved. Please, try again.', true), 'admin_flash_error');
						}
					}
					else 
					{	
						//pr($this->User->validationErrors);
						$this->Session->setFlash(__('The User could not be saved. Please, try again.', true), 'admin_flash_error');
					}
            }
        }
		else 
		{
            $this->request->data = $this->User->read(null, $id);
			if(empty($this->request->data)){
				$this->redirect(array('controller'=>'users', 'action'=>'logout'));			
			}
			
			//pr($this->request->data['User']);die;
			$cookie = array();
			$userCookie = $this->Cookie->read('User');
            //if(empty($userCookie)){
				$cookie = base64_encode(serialize($this->request->data['User']));
				//pr($cookie);die;
				$this->Cookie->write('User', $cookie, true, '+1 years');
				//pr($cookie);die;
			//}
			//die("TESR");
            unset($this->request->data['User']['password']);
        }
		
        if (!empty($imageInfo['User']['profile_image'])){
            $image = $imageInfo['User']['profile_image'];
        }
		else 
		{
            $image = "no_image.png";
        }
		
		$extraimageInfo = $this->UserImage->find('all', array('fields' => array('UserImage.image','UserImage.id','UserImage.image_type'),'conditions'=>array('UserImage.user_id'=>$id)));
		$totExtraImage = count($extraimageInfo);
		
        $this->set(compact('id','image','imageInfo','userData','extraimageInfo','totExtraImage'));
	}

    
    function subscription($user_id){
        $this->loadModel('User');
    	$user =  $this->User->find('first',array('conditions'=>array("User.id"=>$user_id,"User.role_id = "=>Configure::read('App.User.role'))));
        //pr($user);die;
        $this->set(compact('user'));
        $this->layout = "welcome";
    
    
    }
    
    
	function forgot_password()
	{
		if($this->Auth->user())
		{
			$this->redirect(array('controller'=>'users', 'action' => 'update_profile'));
		}
		//echo "<pre>";pr($this->request->data);die;
		if(!empty($this->request->data))
		{
			//$this->loadModel('User');
			$this->User->set($this->request->data);
			$this->User->setValidation('forgot_password');
			if($this->User->validates($this->request->data))
			{
				$userDetail	= $this->User->find("first", array('conditions' => array('User.email' => $this->request->data["User"]["email"] ,'User.status' => 1, 'User.role_id' => 2)));
				
				if(!empty($userDetail))
				{
					$this->User->id	=	$userDetail['User']['id'];
					$verification_code = substr(md5(uniqid()), 0, 20);
					$userDetail['User']['verification_code'] = $verification_code;
					if($this->User->save($userDetail))
					{
						$activation_url = Router::url(array(
								'controller' => 'users',
								'action' => 'get_password',
								base64_encode($userDetail['User']['email']),
								$verification_code
								), true);
						$this->loadModel('Template');
						$forgetPassMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'forgot_password')));
						$subject = $forgetPassMail['Template']['subject'];
						$activation_link	=	' <a href="'.$activation_url.'" target="_blank" shape="rect">Change Password</a>';
							
						$mail_message = str_replace(array('{NAME}', "{ACTIVATION_LINK}"), array($userDetail['User']['display_name'], $activation_link), $forgetPassMail['Template']['content']);
						//die('test');
						$to = $userDetail['User']['email'];
						$from = Configure::read('App.AdminMail');
						$template='default';
						$this->set('message', $mail_message);						 
						$template='default';
						//echo $to."<br>".$subject."<br>".$mail_message."<br>".$from."<br>".$template;die('testing');
						parent::sendMail($to, $subject, $mail_message, $from, $template);	
						$this->Session->setFlash(__('A link has been sent, Please check your inbox'), 'flash_success');
							$this->redirect(array('controller'=>'users', 'action' => 'forgot_password'));			
					}
					else
					{
						$this->Session->setFlash(__('Email address not found in our record.', 'flash_error'));
					}
					$this->redirect(array('controller'=>'users','action'=>'forgot_password'));
				}
				else
				{
					$this->Session->setFlash(__('Email address not found in our record.', true), 'flash_error');
				}
			}
		}
	}
	
	function get_password($email = null, $verification_code = null)
	{
		$email = base64_decode($email);
		//echo $email;
		//echo "<br>";
		//echo $verification_code;die;
		//pr($this->request->data);die('fsfds');
		$userDetail	= $this->User->find("first", array('conditions' => array('User.email' => $email)));
		if($this->User->hasAny(array(
									'User.email' => $email,
									'User.verification_code' => $verification_code
								)
		))
		{
			if(!empty($this->request->data))
			{
				//pr($this->request->data);die('est');
				$this->User->set($this->request->data);
				$this->User->setValidation('change_password');
				if($this->User->validates($this->request->data))
				{
						$this->request->data['User']['id'] = $userDetail['User']['id'];
						$this->request->data['User']['password'] = Security::hash($this->request->data['User']['password2'], null, true);
						$verification_code = substr(md5(time()), 0, 20);
						$this->request->data['User']['verification_code'] = $verification_code;
						
						unset($this->request->data['User']['email']);
						if($this->User->saveAll($this->request->data))
						{
							$this->redirect(array('action' => 'password_changed'));
						}
				}
			}
			else
			{
				$this->request->data = $this->User->findByEmail($email);
			}
		}
		else
		{
			$this->Session->setFlash(__('Invalid Action.'));			
            //$this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
		}		
		$this->set(compact('email', 'verification_code'));
	}
	
	function password_changed()
	{
		$this->set('pageHeading', __('Password changed',true));	
	}
	
	function change_password($id = null){
	
		$this->pageTitle = __('Change Password', true);
		if($this->Auth->user())
		{
			if(!empty($this->request->data)){
			$data = $this->User->findById(array('id' => $this->Auth->user('id')));
			
				$this->request->data['User']['id'] = $this->Auth->user('id');
				$this->User->set($this->request->data);
				$this->User->setValidation('mobile_change_password');					
				if($this->User->validates())
				{
					//die('octal');
					$new_password = $this->request->data['User']['newpassword2'];
					$this->request->data['User']['password'] = Security::hash($this->request->data['User']['newpassword2'], null, true);
					
					//pr($this->request->data);die('octal');
					
					if($this->User->save($this->request->data))
					{					
						$this->Session->setFlash(__('Password updated successfully',true),'flash_good');
						$this->redirect(array('controller' => 'users', 'action' => 'update_profile' ));
					} 
					else 
					{
						//$this->Session->setFlash('Error: Password has not been Changed');
						$this->Session->setFlash(__('Please correct the errors listed below.',true), 'flash_error');
						$this->redirect(array('controller' => 'users', 'action' => 'change_password' ));
					}
				}
			}
		}
		else
		{
			//$this->Session->setFlash('Error: Invalid Operation');
			$this->Session->setFlash(__('Please correct the errors listed below.',true), 'flash_error');
			$this->redirect(array('controller' => 'users', 'action' => 'change_password'));			
		}
		if($this->Auth->user('id')!=null)
		{
			$this->request->data = $this->User->findById(array('id' => $this->Auth->user('id')));
			//pr($this->request->data);die;
			//unset($this->request->data['User']['id']);			
			$this->set('profiledata', $this->request->data);	
		}
		$this->set('pageHeading', __('Change Password', true));
	}
	
	function admin_get_user_list(){
		$this->layout = 'ajax';
		$q=$_POST['search'];
		$data = $this->User->find('list', array('fields'=>array('id', 'username'), 'conditions'=>array("User.username like '%$q%'")));
		//pr($data);die;
		if($this->request->is('ajax')){
			$this->set(compact('data', 'q'));
            $this->render('admin_get_user_list');
        }
	}
	
	function home(){
		$this->loadModel("SubscriptionPlan");
		$SubscriptionPlanList = $this->SubscriptionPlan->find('list',array('fields'=>array("SubscriptionPlan.plan_name")));
		$this->set(compact('SubscriptionPlanList'));
		#pr($SubscriptionPlanList);die;
		$this->layout = "welcome";
	}
	
	function ratethis(){
		$this->layout = "welcome";
		$rateArray = array();
		$this->loadModel('Feed');
		$this->loadModel('FeedRating');
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = $this->FeedRating->find('first', array('conditions'=>array('FeedRating.feed_id'=>$_REQUEST['feed_id'], 'FeedRating.ip'=>$ip)));
		//pr($data);die;
		if(!empty($data)){
			echo "AR";die;
		}
		$rateArray['FeedRating']['feed_id'] = $_REQUEST['feed_id'];
		$rateArray['FeedRating']['rate'] = $_REQUEST['rate'];
		$rateArray['FeedRating']['ip'] = $ip;
		if($this->FeedRating->save($rateArray)){
			if($_REQUEST['rate']==1){
				//echo 1;
				$this->Feed->updateAll(array('Feed.pos'=>'pos+1'),array('Feed.id'=>$_REQUEST['feed_id']));
			}else{
				//echo 0;
				$this->Feed->updateAll(array('Feed.pos'=>'neg+1'),array('Feed.id'=>$_REQUEST['feed_id']));
			}
		}
			$feedData = $this->Feed->find('first', array('fields'=>array('pos', 'neg'), 'conditions'=>array('Feed.id'=>$_REQUEST['feed_id'])));
			echo $feedData['Feed']['pos']."__".$feedData['Feed']['neg'];
		die;
	}
	
	function checklogin(){
		$this->layout = false;
		if (!empty($this->request->data)) 
		{	//pr($this->request->data);die;
				$password = Security::hash($this->request->data['User']['password2'], null, true);
				$user_data = $this->User->find('first', array('conditions'=>array('User.username'=>$this->request->data['User']['username'], 'User.password'=>$password)));
				if(!empty($user_data)){
					$this->Session->write('Auth.User', $user_data['User']);
					$this->Auth->_loggedIn = true;					
					//$this->redirect(array('controller' => 'users', 'action' => 'my_account'));
					echo 1;
				}else{
					echo 0;
				}
				exit;
		}
	}
	
	function checkunique(){
		$this->layout = false;
		if (!empty($this->request->data)) 
		{
			$this->User->set($this->request->data['User']);
			$this->User->setValidation('register');
			if($this->User->validates()) 
			{
				exit;
			}else{
				$all_errors = $this->User->validationErrors;
				$errorMsgArr = array();
				$errorKey = array();
				$strValid = "";
				$count = 0;
				foreach($all_errors as $key=>$value){
					$errorKey[$key] = explode("_", $key);
					$errorKey[$key] = array_map('ucfirst', $errorKey[$key]);
					$errorKey[$key] = implode("", $errorKey[$key]);
					
					//$errorMsgArr["User".$errorKey[$key]] = $value[0];
					if($count>0){
						$strValid = $strValid."__"."User".$errorKey[$key]."=".$value[0];
					}else{
						$strValid = "User".$errorKey[$key]."=".$value[0];
					}
					$count++;
				}
				//pr($errorMsgArr);
				echo $strValid;
				exit;
				//die("ERROR");
			}
		}
	}
	
	 /*
     * send offer mail
     */

    public function admin_send_offer_email($id = null){
        $defaultTab = 'All';
    	/** Load Template,SubscriptionPlan Model   */
        $this->loadModel('SubscriptionPlan');
    	
		$befor_one_week_date = date("Y-m-d",strtotime("-1 week"));
		$last_month = date("m",strtotime("-1 month"));
		$last_6_month = date('Y-m-d', strtotime('today - 6 month'));
		$last_year =  date("Y",strtotime("-1 year"));
		$this->User->bindModel(array('belongsTo'=>array('SubscriptionPlan'=>array('className'=>'SubscriptionPlan', 'foreignKey'=>'subscription_plan_id'))));

	
		$number_of_record = Configure::read('App.AdminPageLimit');
        App::uses('Sanitize', 'Utility');
		if (!empty($this->request->data['Number']['number_of_record'])) {
			$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
			$this->Session->write('number_of_record', $number_of_record);
		}
			
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
        $filters_without_status = $filters = array('User.role_id' => Configure::read('App.Role.User'));

        if ($defaultTab != 'All'){
            $filters[] = array('User.status' => array_search($defaultTab, Configure::read('Status')));
        }
        
		/**get offers details */
		$this->loadModel('Offer');
		$offer_details = $this->Offer->find('first',array('conditions'=>array('Offer.id'=>$id)));
		$offer_name = $offer_details['Offer']['name'];
		$offer_subject = $offer_details['Offer']['subject'];
		/**get offers details */
		$user_email = "";
		$user_alter_email = "";
		if (!empty($this->request->data)){
		
		    //pr($this->request->data);die;
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');

            App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
            if (!empty($this->request->data['User']['email'])) {
                $email = Sanitize::escape($this->request->data['User']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            
            if (!empty($this->request->data['User']['alternate_email'])) {
                $alternate_email = Sanitize::escape($this->request->data['User']['alternate_email']);
                $this->Session->write('AdminSearch.alternate_email', $alternate_email);
            }
        	if (!empty($this->request->data['User']['subscription_plan_id'])) {
                $subscription_plan_id = Sanitize::escape($this->request->data['User']['subscription_plan_id']);
                $this->Session->write('AdminSearch.subscription_plan_id', $subscription_plan_id);
            }
			if (isset($this->request->data['User']['status']) && $this->request->data['User']['status'] != ''){
                $status = Sanitize::escape($this->request->data['User']['status']);
                $this->Session->write('AdminSearch.status', $status);
                $defaultTab = Configure::read('Status.' . $status);
            }
			$user_email = $this->request->data['User']['email'];
			$user_alter_email = $this->request->data['User']['alternate_email'];
			
		
        } else{
			$this->request->data = $this->Offer->findById($id);
			$this->request->data['User']['content'] = $this->request->data['Offer']['content'];
		}

        $search_flag = 0;
        $search_status = '';
        if ($this->Session->check('AdminSearch')) {
            $keywords = $this->Session->read('AdminSearch');

            foreach ($keywords as $key => $values) {
                if ($key == 'status') {
                    $search_status = $values;
                    $filters[] = array('User.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('User.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }		
            $search_flag = 1;
        }
        
        $this->set(compact('search_flag', 'defaultTab'));

        #pr($filters); die;

        $this->paginate = array(
            'User' => array(
                'limit' => $number_of_record,
                'order' => array('User.id' => 'DESC'),
                'conditions' => $filters
            )
        );
		/**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
        #pr($this->paginate);
        $data = $this->paginate('User');
        //pr($offer_details); die;
        $this->set(compact('data', 'subscription_plans','offer_details','id'));
        $this->set('title_for_layout', __('Customers', true));


        if (isset($this->request->params['named']['page']))
            $this->Session->write('Url.page', $this->request->params['named']['page']);
        if (isset($this->request->params['named']['sort']))
            $this->Session->write('Url.sort', $this->request->params['named']['sort']);
        if (isset($this->request->params['named']['direction']))
            $this->Session->write('Url.direction', $this->request->params['named']['direction']);
        $this->Session->write('Url.type', '');
        $this->Session->write('Url.defaultTab', $defaultTab);

        if ($this->request->is('ajax')) {
            $this->render('ajax/admin_index');
        } else {
            $active = 0;
            $inactive = 0;
            if ($search_status == '' || $search_status == Configure::read('App.Status.active')) {
                $temp = $filters_without_status;
                $temp[] = array('User.status' => 1);
                $active = $this->User->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp = $filters_without_status;
                $temp[] = array('User.status' => 0);
                $inactive = $this->User->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }
		$this->request->data = $this->Offer->findById($id);
		$this->request->data['User']['content'] = $this->request->data['Offer']['content'];
		
		//if(isset($this->request->data)){
		$this->request->data['User']['email'] = $user_email;
		$this->request->data['User']['alternate_email'] = $user_alter_email;
		//}
		if ($this->Session->check('number_of_record')) {
				$number_of_record = $this->Session->read('number_of_record');
				$this->request->data['Number']['number_of_record'] = $number_of_record;
		}		
    }
	
	 /*
     * send mail
     */

    public function admin_offer_email($id = null){
        
    	$this->layout=	false;
    	$this->autorender	=	false;
		/**get offers details */
		$this->loadModel('Offer');
		$offer_details = $this->Offer->find('first',array('conditions'=>array('Offer.id'=>$id)));
		$offer_name = $offer_details['Offer']['name'];
		$offer_subject = $offer_details['Offer']['subject'];
		$offer_discount = $offer_details['Offer']['discount'];
		$offer_promo_code = $offer_details['Offer']['promo_code'];
		$offer_start_date = date('M j, Y', strtotime($offer_details['Offer']['start_date']));
		$offer_end_date = date('M j, Y', strtotime($offer_details['Offer']['end_date']));;
		/**get offers details */
		if (!empty($this->request->data)){
		
		    //-----send offer mail ----//
				if (empty($this->request->data['User']['content'])){
					$this->Session->setFlash(__('Message box did not allow blank.'), 'admin_flash_error');
					$this->redirect($this->referer());
				}
				$ids = array();
				foreach ($this->request->data['User']['id'] AS $value) {
					if ($value != 0) {
						$ids[] = $value;
					}
				}
				if (count($ids) == 0) {
					$this->Session->setFlash(__('No User selected.'), 'admin_flash_error');
					$this->redirect($this->referer());
				}
				//pr($ids);die;
				$allData = $this->User->find('all', array('fields'=>array('User.id','User.first_name','User.last_name','User.email'),'conditions'=>array('User.id'=>$ids)));
				$to = array();
				foreach($allData as $data){
					$to[] = $data['User']['email'];
				}
				$from    = Configure::read('App.AdminMail');
				$mail_message = '';
				$this->loadModel('Template');
				$offerMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'offer_mail')));
				$email_subject = $offerMail['Template']['subject'];
				$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
				$main_message = $this->request->data['User']['content'];
				$mail_message = str_replace(array('{NAME}','{SUBJECT}','{MAILMESSAGE}','{DISCOUNT}','{PROMOCODE}','{STARTDATE}','{ENDDATE}'), array($offer_name, $offer_subject, $main_message,$offer_discount,$offer_promo_code,$offer_start_date,$offer_end_date), $offerMail['Template']['content']);
				$template = 'default';
				$this->set('message', $mail_message);
				//pr($mail_message);die;
				parent::sendMail($to, $subject, $mail_message, $from, $template);
				$this->Session->setFlash('Email send successfully ', 'admin_flash_success');
				$this->redirect(array('controller' => 'users', 'action' => 'send_offer_email',$id));
				//-----send offer mail ----//
						
        } else{
			$this->request->data = $this->Offer->findById($id);
			$this->request->data['User']['content'] = $this->request->data['Offer']['content'];
		}
    //$this->redirect(array('controller' => 'users', 'action' => 'send_offer_email',$id));
    }
	
    
   
    
 
 /**
	 * Send notifications to users if their payment is not received
	 */
	public function admin_notification($id = null) {
		$this->loadModel('UserAffilate');
		$this->UserAffilate->id = $id;
		if (!$this->UserAffilate->exists()) {
			throw new NotFoundException(__('Invalid Affilate'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
	
		$this->loadModel('User');
		$this->loadModel('Template');
		$this->UserAffilate->bindModel(array('belongsTo'=>array('User')),false);
		
		$user_id = $this->UserAffilate->field('user_id');
		$userAffilate = $this->UserAffilate->find('first', array('conditions'=>array('UserAffilate.id'=>$id),'fields'=>array('UserAffilate.name','UserAffilate.amount','UserAffilate.referral_ip','UserAffilate.status', 'User.first_name','User.last_name', 'User.email',  'User.id' )));
		$to = $userAffilate['User']['email'];
		
		$from    = Configure::read('App.AdminMail');
		$mail_message = '';
		$registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'affilate_notification')));
		$email_subject = $registrationMail['Template']['subject'];
		$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
		$affilate_url = Configure::read('App.SiteUrl');

		$payment_link	=	'<a href="'.$affilate_url.'">Affilate Please Click Here</a>';
		$status = Configure::read('affilate_status.'.$userAffilate['UserAffilate']['status']);
		$mail_message = str_replace(array('{NAME}','{STATUS}','{PAYMENT_LINK}'), array($userAffilate['UserAffilate']['name'], $userAffilate['UserAffilate']['status'],$payment_link,  $payment_link), $registrationMail['Template']['content']);
		$template = 'default';
		$this->set('message', $mail_message);
		parent::sendMail($to, $subject, $mail_message, $from, $template);
		$this->Session->setFlash(__('Affilate notification has been sent.'), 'admin_flash_success');
		$this->redirect(array('action' => 'affilate', $userAffilate['User']['id'] ));
	
		
	}
	
 
 
 
	function test_mail(){
				$from    = Configure::read('App.AdminMail');
				$subject = "TEST SUBJECT BY KK";
				$mail_message = "TESTING MAIL";
				$template = 'default';
				$this->set('message', $mail_message);
				//pr($mail_message);die;
				parent::sendMail("mahen.zed123@gmail.com", $subject, $mail_message, $from, $template);
				die("TEST");
	}
	
	function get_affilates($affilateLink = null){
		echo $affilateData = base64_decode($affilateLink);
		//Entry in DB when affilate link 
		// Create Cookiee
		
		die();
	}
	
}