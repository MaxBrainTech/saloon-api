<?php

/**
 * Employees Controller
 *
 * PHP version 5.4
 *
 */
class EmployeesController extends AppController{

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Employees';
    public $components = array(
        'General', 'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('Employee');

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
        $this->loadModel('Employee');
    }

    public function beforeFilter(){
    	
        parent::beforeFilter();
        $this->loadModel('Employees');
		$this->Auth->allow('start_access', 'reset_password', 'thankx', 'reset_password_change','login', 'subscription', 'email_confirm', 'register', 'activate', 'success', 'fbconnect', 'forgot_password','get_password', 'password_changed', 'linked_connect', 'save_linkedin_data', 'tw_connect', 'tw_response', 'glogin', 'save_google_info','social_login', 'tlogin', 'save_cover_photo', 'getTwitterData', 'fb_data', 'fb_logout', 'social_join_mail', 'home', 'checkunique', 'checklogin', 'test_mail', 'get_affilates', 'list_attendance', 'employee_home', 'customer_list', 'services_list', 'add_attendance');
        date_default_timezone_set("Asia/Tokyo");
    }

   



    /*
     * List all Employees in admin panel
     */

    public function admin_index($defaultTab = 'All'){	

    	
		$befor_one_week_date = date("Y-m-d",strtotime("-1 week"));
		$last_month = date("m",strtotime("-1 month"));
		$last_6_month = date('Y-m-d', strtotime('today - 6 month'));
		$last_year =  date("Y",strtotime("-1 year"));
		
	
		$number_of_record = Configure::read('App.AdminPageLimit');
        
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
       $filters = array();
        if ($defaultTab != 'All'){
            $filters[] = array('Employee.status' => array_search($defaultTab, Configure::read('Status')));
        }

        if (!empty($this->request->data)){
		
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');

            App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
            if (!empty($this->request->data['Employee']['email'])) {
                $email = Sanitize::escape($this->request->data['Employee']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            
            if (!empty($this->request->data['Employee']['alternate_email'])) {
                $alternate_email = Sanitize::escape($this->request->data['Employee']['alternate_email']);
                $this->Session->write('AdminSearch.alternate_email', $alternate_email);
            }
        	if (!empty($this->request->data['Employee']['subscription_plan_id'])) {
                $subscription_plan_id = Sanitize::escape($this->request->data['Employee']['subscription_plan_id']);
                $this->Session->write('AdminSearch.subscription_plan_id', $subscription_plan_id);
            }
			if (isset($this->request->data['Employee']['status']) && $this->request->data['Employee']['status'] != ''){
                $status = Sanitize::escape($this->request->data['Employee']['status']);
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
                    $filters[] = array('Employee.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('Employee.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }		
            $search_flag = 1;
        }
        $this->Employee->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
        
        $this->set(compact('search_flag', 'defaultTab'));

        $this->paginate = array(
            'Employee' => array(
                'limit' => $number_of_record,
                'order' => array('Employee.id' => 'DESC'),
                'conditions' => $filters
             )
        );
		
    	$data = $this->paginate('Employee');
       // echo "<pre>";
        //print_r($data);die;
        $this->set(compact('data'));
        $this->set('title_for_layout', __('Employees', true));


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
                $temp[] = array('Employee.status' => 1);
                $active = $this->Employee->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp[] = array('Employee.status' => 0);
                $inactive = $this->Employee->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }
    }

	
    /*
     * View existing Employee
     */

    public function admin_view($id = null){
    	$this->Employee->id = $id;
        if(!$this->Employee->exists()){
            throw new NotFoundException(__('Invalid Employee'));
        }
        $this->Employee->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
       
        $this->Employee->recursive = 3;
        $data = $this->Employee->read(null, $id);
      //  echo "<pre>";
       // print_r($data);die;
        $this->set('Employee', $data);
        
    }

     /*
     * View existing Employee
     */

    public function admin_service_details($customer_id = null, $service_id = null ){
      
        if($service_id == 2){
            $this->loadModel("Esthe");
            $data = $this->Esthe->find('first',array('conditions'=>array('Esthe.customer_id'=>$customer_id)));
            $service_name = "Esthe";
             // $log = $this->Esthe->getDataSource()->getLog(false, false);
           // debug($log);
        }elseif($service_id ==3){
            $this->loadModel("Eyelush");
            $data = $this->Eyelush->find('first', array('conditions' => array('Eyelush.customer_id' => $customer_id)));
            $service_name = "Eyelush";

        }elseif ($service_id ==4) {
            $this->loadModel("Body");
            $data = $this->Body->find('first', array('conditions' => array('Body.customer_id' => $customer_id)));
            $service_name = "Body";
        }elseif ($service_id ==5) {
            $this->loadModel("HairRemoval");
            $data = $this->HairRemoval->find('first', array('conditions' => array('HairRemoval.customer_id' => $customer_id)));
            $service_name = "HairRemoval";
        }   
        //echo "<pre>";
        //print_r($data);die;
        $this->set('title_for_layout', __('Services', true));
        $this->set('data', $data);
        $this->set('service_name', $service_name);
        
    }

    /*
     * add Employee
     */
    public function admin_add(){
		if($this->Session->check('Auth.Employee.id') && $this->Session->read('Auth.Employee.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
		/** Load Template,SubscriptionPlan Model   */
        $this->loadModel('Template');
        $this->loadModel('SubscriptionPlan');
       
        if ($this->request->is('post')) {

            if (!empty($this->request->data)) {

                /* unset Employee skill 0 position value if exist */

                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }

                $this->Employee->set($this->request->data['Employee']);
                $this->Employee->setValidation('admin');

                $this->request->data['Employee']['password'] = Security::hash($this->request->data['Employee']['password2'], null, true);
				 $this->request->data['Employee']['origional_password'] = $this->request->data['Employee']['password2'];

                $this->Employee->create();

                $this->request->data['Employee']['role_id'] = Configure::read('App.Role.Employee');
               // $file = $this->request->data['Employee']['profile_image'];
                unset($this->request->data['Employee']['profile_image']);
				
                if ($this->Employee->saveAll($this->request->data)) {
                    #Employee image upload
                    $EmployeeId = $this->Employee->id;
                    /* $upload = $this->General->imageUpload($EmployeeId, 'Employee', $file, $file['tmp_name'], '');
                      $this->Employee->saveField('profile_image', $upload); */
                    //pr($_FILES);
                    if (!empty($file) && $file['tmp_name'] != '' && $file['size'] > 0) {
                        $rules = array('size' => array(Employee_THUMB_WIDTH, Employee_THUMB_HEIGHT), 'type' => 'resizecrop');
                        $tinyrules = array('size' => array(Employee_TINY_WIDTH, Employee_TINY_HEIGHT), 'type' => 'resizecrop');
                        $thumb1 = array('size' => array(Employee_THUMB_WIDTH1, Employee_THUMB_HEIGHT1), 'type' => 'resizecrop');
                        $back = array('size' => array(Employee_LARGE_WIDTH, Employee_LARGE_HEIGHT), 'type' => 'resizecrop');
                        // Upload the image using the Upload component
                        $path_info = pathinfo($file['name']);
                        $file['name'] = $path_info['filename'] . "_" . time() . "." . $path_info['extension'];
                        $tinyResult = $this->Upload->upload($file, WWW_ROOT . Employee_TINY_DIR
                                . DS, '', $tinyrules);
                        $result = $this->Upload->upload($file, WWW_ROOT . Employee_THUMB_DIR
                                . DS, '', $rules);
                        $result = $this->Upload->upload($file, WWW_ROOT . Employee_THUMB1_DIR
                                . DS, '', $thumb1);
                        $result = $this->Upload->upload($file, WWW_ROOT . Employee_LARGE_DIR
                                . DS, '', $back);
                        $res1 = $this->Upload->upload($file, WWW_ROOT . Employee_ORIGINAL_DIR . DS, '');
                        if (!empty($this->Upload->result) && empty($this->Upload->errors)) {
                            $this->Employee->updateAll(array('Employee.profile_image' => "'" . $this->Upload->result . "'"), array('Employee.id' => $EmployeeId));
                        } else {
                            $responseData["error"] = $this->Upload->errors[0];
                        }
                    } else {
                        $responseArray["error"] = "Image is empty or Size is Zero.";
                    }

              
					 $this->Session->setFlash(__('Employee has been saved successfully'), 'admin_flash_success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The Employee could not be saved. Please, try again.'), 'admin_flash_error');
                }
            }
        }
         /**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
    	$this->set(compact('subscription_plans'));
    }

    /*
     * edit existing Employee
     */
    public function admin_edit($id = null) {
		
		$this->Employee->id = $id;
		
        $imageInfo = $this->Employee->find('first', array('conditions' => array('Employee.id' => $id), 'fields' => array('Employee.profile_image','Employee.fb_id','Employee.id','Employeename')));
        if (!$this->Employee->exists()) {
            throw new NotFoundException(__('Invalid Employee'));
        }
        
        /** Load Template,SubscriptionPlan Model   */
        $this->loadModel('SubscriptionPlan');
        
		if($this->request->is('post') || $this->request->is('put')) {
		
			if(!empty($this->request->data)) {
					if(!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
						$blackHoleCallback = $this->Security->blackHoleCallback;
						$this->$blackHoleCallback();
					}
					$this->Employee->set($this->request->data['Employee']);
					$this->Employee->setValidation('admin');
					if ($this->Employee->validates()) {
						$this->Employee->create();
						
						//$this->request->data['Employee']['role_id'] = 2;
						if(!empty($this->request->data['Employee']['profile_image']['tmp_name']))
						{						
							$file = $this->request->data['Employee']['profile_image'];
						}
						else
						{
							$file = '';
						}
						
						unset($this->request->data['Employee']['profile_image']);	
						if(!empty($this->request->data['Employee']['full_address'])){
							$addArr = explode(',',$this->request->data['Employee']['full_address']);
							
							$this->request->data['Employee']['city']=$addArr[0];
							$this->request->data['Employee']['state']=$addArr[1];
							$this->request->data['Employee']['country']=$addArr[2];
						}	
						//pr($this->request->data);die;
						if ($this->Employee->saveAll($this->request->data)) {
					
							$this->Session->setFlash(__('The Employee information has been updated successfully', true), 'admin_flash_success');
							$this->redirect(array('action' => 'index'));
						} 
						else 
						{

							$this->Session->setFlash(__('The Employee could not be saved. Please, try again.', true), 'admin_flash_error');
						}
					}
					else 
					{	
						
						$this->Session->setFlash(__('The Employee could not be saved. Please, try again.', true), 'admin_flash_error');
					}
            }
        } 
		else 
		{
            $this->request->data = $this->Employee->read(null, $id);
            unset($this->request->data['Employee']['password']);
        }
		
        if (!empty($imageInfo['Employee']['profile_image'])){
            $image = $imageInfo['Employee']['profile_image'];
        }
		else 
		{
            $image = "no_image.png";
        }
        /**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
    	$this->set(compact('image','imageInfo','subscription_plans'));
    }

    /*
     * change Employee password by admin
     */
    
    public function admin_change_password($id = null) {
		if($this->Session->check('Auth.Employee.id') && $this->Session->read('Auth.Employee.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->Employee->id = $id;
        if (!$this->Employee->exists()) {
            throw new NotFoundException(__('Invalid Employee'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            if (!empty($this->request->data)) {
                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }


                //validate Employee data
                $this->Employee->set($this->request->data);
                $this->Employee->setValidation('admin_change_password');
                if ($this->Employee->validates()) {
                    $new_password = $this->request->data['Employee']['new_password'];
                    $this->request->data['Employee']['password'] = Security::hash($this->request->data['Employee']['new_password'], null, true);
                    $this->request->data['Employee']['origional_password'] = $this->request->data['Employee']['new_password'];
                    if ($this->Employee->saveAll($this->request->data)) {
                       
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

            $this->request->data = $this->Employee->read(null, $id);

            unset($this->request->data['Employee']['password']);
        }
    }

    /*
     * delete existing Employee
     */
    public function admin_delete($id = null){
        $Employee_id = $this->Employee->id = $id;

        if (!$this->Employee->exists()){
            throw new NotFoundException(__('Invalid Employee'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
	
		$Employee_data = $this->Employee->find('first',array('conditions'=>array('Employee.id'=>$id)));
		//die;
        if ($this->Employee->deleteAll(array('Employee.id'=>$id))) {

            $this->Session->setFlash(__('Employee deleted successfully'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Employee was not deleted', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

    /*
     * toggle Employee status
     */
    
    public function admin_status($id = null) {
		if($this->Session->check('Auth.Employee.id') && $this->Session->read('Auth.Employee.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->Employee->id = $id;
        if (!$this->Employee->exists()) {
            throw new NotFoundException(__('Invalid Employee'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        $this->loadModel('Template');
        $this->loadModel('Employee');
        if ($this->Employee->toggleStatus($id)) {
            $Employee_info = $this->Employee->get_Employees('first', 'Employee.email,Employee.first_name,Employee.last_name,Employee.status', array('Employee.id' => $id));

            $this->Session->setFlash(__('Employee\'s status has been changed'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Employee\'s status was not changed', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

      
     /*
     * change status and delete Employees 
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
            $action = Sanitize::escape($this->request->data['Employee']['pageAction']);

            $ids = $this->request->data['Employee']['id'];

            if (count($this->request->data) == 0 || $this->request->data['Employee'] == null) {
                $this->Session->setFlash('No items selected.', 'admin_flash_error');
                $this->redirect($this->referer());
            }

            if ($action == "delete") {
		
				$this->Employee->deleteAll(array('Employee.id' => $ids)); 
                $this->Session->setFlash('Employees have been deleted successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "activate") {
                $this->Employee->updateAll(array('Employee.status' => Configure::read('App.Status.active')), array('Employee.id' => $ids));
                $this->Session->setFlash('Employees have been activated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "deactivate") {
                $this->Employee->updateAll(array('Employee.status' => Configure::read('App.Status.inactive')), array('Employee.id' => $ids));
                $this->Session->setFlash('Employees have been deactivated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }
        } else {
            $this->redirect(array('controller' => 'admins', 'action' => 'index'));
        }
    }

	/*
     * reset Employee password 
     */
    
    public function reset_password($id=0)
	{
		$this->loadModel('Template');
		$this->loadModel('Employee');
		$this->layout = false;
		if($this->request->is('post'))
		{
			if(!empty($this->request->data))
			{
				$this->Employee->set($this->request->data);
				$this->Employee->validates();
				$this->Employee->setValidation('reset_password');
				if($this->Employee->validates())
				{
					$Employee_id = base64_decode(base64_decode(base64_decode($id)));
					$Employee_data =  $this->Employee->find('first',array('conditions'=>array("Employee.id"=>$Employee_id,"Employee.role_id = "=>Configure::read('App.Employee.role'))));
					
					
					if(isset($Employee_data) && !empty($Employee_data))
					{
						$this->Employee->id = $Employee_data['Employee']['id'];
						$new_password = Security::hash($this->request->data['Employee']['password'], null, true);
						if($this->Employee->saveField('password',$new_password))
						{
							$password = $this->request->data['Employee']['password'];
							$this->Employee->saveField('origional_password',$this->request->data['Employee']['password']);
							unset($this->request->data['Employee']['password']);
							unset($this->request->data['Employee']['password2']);	
							/*******Reset Password mail code here***/
							$mailMessage='';
							$password_confirm = $this->Template->find('first', array('conditions' => array('Template.slug' => 'forgot_password_confirm')));
							
							$email_subject = $password_confirm['Template']['subject'];
							$subject = '['.Configure::read('Site.title').']'. $email_subject;
							
							$mailMessage = str_replace(array('{NAME}','{PASSWORD}'), array($Employee_data['Employee']['Employeename'],$password), $password_confirm['Template']['content']);
							
							if(parent::sendMail($Employee_data['Employee']['email'],$subject,$mailMessage,array(Configure::read('App.AdminMail')=>Configure::read('Site.title')),'general'))
							{
								
							// Save the data in templates_Employees table start //
								$this->loadModel('TemplatesEmployee');
								$templates_Employees['TemplatesEmployee']['Employee_id'] = $Employee_data['Employee']['id'];
								$templates_Employees['TemplatesEmployee']['template_id'] = $password_confirm['Template']['id'];
								$this->TemplatesEmployee->save($templates_Employees);
							// End //
							$this->Session->setFlash('Password change sucessfully.','front_flash_good');
							$this->redirect(array('controller'=>'Employees','action'=>'thankx'));
						 
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
     * change reset Employee password
     */
	
	public function reset_password_change($id=0)
	{
		$this->loadModel('Template');
		$this->loadModel('Employee');
		$this->layout = false;
		if($this->request->is('post'))
		{
			if(!empty($this->request->data))
			{	
				
				$this->Employee->set($this->request->data);
				$this->Employee->validates();
				$this->Employee->setValidation('reset_password');
				if($this->Employee->validates())
				{
					$Employee_id = base64_decode(base64_decode(base64_decode($id)));
					$Employee_data =  $this->Employee->find('first',array('conditions'=>array("Employee.id"=>$Employee_id,"Employee.role_id = "=>Configure::read('App.Employee.role'))));
					
					
					if(isset($Employee_data) && !empty($Employee_data))
					{
						$this->Employee->id = $Employee_data['Employee']['id'];
						$new_password = Security::hash($this->request->data['Employee']['password'], null, true);
						if($this->Employee->saveField('password',$new_password))
						{
							$password = $this->request->data['Employee']['password'];
							$this->Employee->saveField('origional_password',$this->request->data['Employee']['password']);
							unset($this->request->data['Employee']['password']);
							unset($this->request->data['Employee']['password2']);	
							/*******Reset Password mail code here***/
							$mailMessage='';
							$password_confirm = $this->Template->find('first', array('conditions' => array('Template.slug' => 'reset_password_confirm')));
							
							$email_subject = $password_confirm['Template']['subject'];
							$subject = '['.Configure::read('Site.title').']'. $email_subject;
							
							$mailMessage = str_replace(array('{NAME}','{PASSWORD}'), array($Employee_data['Employee']['Employeename'],$password), $password_confirm['Template']['content']);
							
							if(parent::sendMail($Employee_data['Employee']['email'],$subject,$mailMessage,array(Configure::read('App.AdminMail')=>Configure::read('Site.title')),'general'))
							{
								
							// Save the data in templates_Employees table start //
								$this->loadModel('TemplatesEmployee');
								$templates_Employees['TemplatesEmployee']['Employee_id'] = $Employee_data['Employee']['id'];
								$templates_Employees['TemplatesEmployee']['template_id'] = $password_confirm['Template']['id'];
								$this->TemplatesEmployee->save($templates_Employees);
							// End //
							$this->Session->setFlash('Password change sucessfully.','front_flash_good');
							$this->redirect(array('controller'=>'employees','action'=>'thankx'));
						 
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
     * Employee registration
     */
	public function register()
	{
			if($this->Auth->Employee())
			{
					//pr($this->Auth->Employee());die('In');
					$this->redirect($this->Auth->redirect());
			}
		
			if ($this->request->is('post')) 
			{
				
				if (!empty($this->request->data)) 
				{
					
					$this->Employee->set($this->request->data['Employee']);
					$this->Employee->setValidation('register');
								
					$verification_code = substr(md5(uniqid()), 0, 20);
					$this->request->data['Employee']['verification_code'] = $verification_code;
					$this->request->data['Employee']['status'] = '0';
					//echo $this->request->data['Employee']['subscription_plan_id'];die;
					
					
					if($this->Employee->validates()) 
					{
						//pr($this->request->data);die;
						/* Employee plan detail*/
						$this->loadModel('SubscriptionPlan');
						$subscription_plans_id = $this->request->data['Employee']['subscription_plan_id'];
						$subscription_plans = $this->SubscriptionPlan->find('first',array('conditions'=>array('SubscriptionPlan.id'=>$subscription_plans_id)));
						if($subscription_plans['SubscriptionPlan']['plan_type']==1){
							$duration = $subscription_plans['SubscriptionPlan']['plan_duration'];
							$expireDate = date("Y-m-d H:i:s", strtotime("+".$duration." months"));
							$this->request->data['Employee']['service_expire_date'] = $expireDate;
						}else if($subscription_plans['SubscriptionPlan']['plan_type']==2){
							$duration = $subscription_plans['SubscriptionPlan']['plan_duration'];
							$expireDate = date("Y-m-d H:i:s", strtotime("+".$duration." years"));
							$this->request->data['Employee']['service_expire_date'] = $expireDate;
						}
						$this->request->data['Employee']['password'] = Security::hash($this->request->data['Employee']['password2'], null, true);
						$this->request->data['Employee']['ip'] = $this->RequestHandler->getClientIp();
						$enter_password = $this->request->data['Employee']['password2'];
						$password = $this->request->data['Employee']['password'];
						$name = $this->request->data['Employee']['first_name']." ".$this->request->data['Employee']['last_name'];
						if($this->Employee->saveAll($this->request->data)){
						if($this->request->data['Employee']['subscription_plan_id']==1){
							
								
								/*************** EMAIL NOTIFICATION MESSAGE ****************/	
									$this->Employee->saveField('status', '1');
									$to      = $this->request->data['Employee']['email'];
									$from    = Configure::read('App.AdminMail');
									$mail_message = '';
									$this->loadModel('Template');
									$registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'Employee_registration')));
									$email_subject = $registrationMail['Template']['subject'];
									$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
									$activationCode = $this->request->data['Employee']['verification_code'];
									$activation_url = Router::url(array(
													'controller' => 'Employees',
													'action' => 'email_confirm',
													base64_encode($this->request->data['Employee']['email']),
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
									$this->Session->setFlash(__('The Employee has been registered successfully.', true), 'flash_success');
									$this->redirect(array('controller' => 'Employees', 'action' => 'register'));
								
							
							}else{
								//pr($this->Employee->id);die;
								$this->Employee->saveField('status', '0');
								$this->Session->write('Employee',$this->request->data['Employee']);
								#pr($this->Session->read('Employee'));die;
								$this->Cookie->write('Employee',$this->request->data['Employee'],$encrypt=false,3600);
								#pr($this->Cookie->read('Employee'));die;
								if(!empty($this->request->data['Employee']['subscription_plan_id'])){
									$this->testPaypalGetExpress();
								}else{
									$this->Session->setFlash(__('Please select subscription plan, try again', 'flash_error'));
								}
							
							}
						}				 
						else 
						{
							$this->Session->setFlash(__('The Employee could not be registerd. Please, try again.', 'flash_error'));
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
     * Employee activation
     * Check email confirm by email
     * @param var $email - base64 encoded email
     */
    function email_confirm($email, $act_id) {


        
        $user = $this->Employee->find('first', array('conditions' => array('Employee.email' => base64_decode($email), 'Employee.verification_code' => $act_id)));
     //   pr($user);die;
        $today = strtotime('now');
        #  $new_date = $today + 30 * 24 * 60 * 60;

        if (!empty($user) && count($user)) {
            $this->Employee->updateAll(array('status' => Configure::read('App.Status.active')), array('Employee.id' => $user["Employee"]["id"]));
            $this->Session->setFlash('Active your account.please login.', 'flash_success');
        } else {
            $this->Session->setFlash('This email is not register.', 'flash_error');
        }
        $this->redirect(array('controller' => 'pages', 'action' => 'home', 'varify_customer_email'));
    }
	
	
/*
     * Employee profile
     */
    public function my_profile()
	{
		
		$this->layout = "welcome";
		//pr($this->Auth->Employee());die;
		if(!$this->Auth->Employee())
		{
			$this->redirect($this->Auth->redirect());
		}
		/* load model */
		$this->loadModel('Subcription');
		
		$this->Employee->bindModel(array('belongsTo'=>array('SubscriptionPlan'=>array('className'=>'SubscriptionPlan', 'foreignKey'=>'subscription_plan_id'))));
		$Employee_id = $this->Auth->Employee('id');
		$Employee = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$Employee_id)));
		$this->set(compact('Employee'));
		
	}
	
	
	 
	/*
     * Employee logout
     */ 
	public function logout(){
		/* $this->Session->delete('access_token');
		$this->Session->delete('Facebook.Employee');
		$this->Session->delete('Twitter.Employee');
		$this->Session->delete('GooglePlus.Employee');
		$this->Session->delete('LinkedIn.Employee');
		$this->Session->delete('LinkedIn.referer');
		$this->Session->delete('Google.referer');
		$this->Cookie->delete('Employee');
		unset($_SESSION['oauth']['linkedin']); */
        $this->redirect($this->Auth->logout());
    }

  

    
		
	function activate($email = null, $verification_code = null)
	{
	    $this->layout	= 'default';
		if ($email == null || $verification_code == null) 
		{
			$this->Session->setFlash(__('Error_Message',true), 'admin_flash_bad');
            $this->redirect(array('controller' => 'Employees', 'action' => 'login'));
        }
		$email = base64_decode($email);
	
		if ($this->Employee->hasAny(array(
									'Employee.email' => $email,
									'Employee.verification_code' => $verification_code,
									//'Employee.status' => 0
									)
									))
		{
			$Employee = $this->Employee->findByEmail($email);
			//activation date code
			$this->Employee->updateAll(array('Employee.modified'=>"'".date('Y-m-d H-i-s')."'"));
		//activation date code close	
			$this->Employee->id = $Employee['Employee']['id'];
			$this->Employee->saveField('status', 1);
			$this->Employee->saveField('is_email_verified', 1);
			$this->Employee->saveField('verification_code', substr(md5(uniqid()), 0, 20));
			
			$to      = $email;			
			$from    = Configure::read('App.AdminMail');
			$mail_message = '';
			$this->loadModel('Template');
			$notificationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'verify_email')));
			$email_subject = $notificationMail['Template']['subject'];
			$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
			$login_url = Router::url(array(
									'controller' => 'Employees',
									'action' => 'register'
									), true);
		
			$login_link	=	'<a href="'.$login_url.'">Click Here To Login</a>';
			$mail_message = str_replace(array('{NAME}','{SITE}','{LOGIN_LINK}'), array($Employee['Employee']['first_name'].''.$Employee['Employee']['last_name'],Configure::read('App.SITENAME'),$login_link), $notificationMail['Template']['content']);
			
			$template = 'default';
			
			$this->set('message', $mail_message);						
			parent::sendMail($to, $subject, $mail_message, $from, $template);
			//$this->Session->setFlash(__('Your Email is verified'));
			$this->redirect(array('controller' => 'Employees', 'action' => 'success'));
		}
		else
		{
			$this->Session->setFlash(__('Verification Failed'));		
            $this->redirect(array('controller' => 'Employees', 'action' => 'login'));
		}
	}
	
	function success() {
	      $this->layout	= 'default';
        /* if ($this->Auth->Employee()){
            $this->redirect(array('controller' => 'programs', 'action' => 'my_program'));
        } */
        $this->set("title_for_layout",__('Success',true));
    }
	
	function update_profile($id = null)
	{	
		$id = $this->Auth->Employee('id');
		$this->Employee->id = $id;
		$this->loadModel('EmployeeImage');	
		$this->Employee->bindModel(array('hasMany'=>array('EmployeeImage')),false);
        $imageInfo = $this->Employee->find('first', array('conditions' => array('Employee.id' => $id), 'fields' => array('Employee.profile_image','Employee.profile_cover_image','Employee.fb_id','Employee.id','Employee.twitter_id','Employee.linkdin_id','Employee.social_media_image_url','Employeename')));
        if (!$this->Employee->exists()){
			$this->redirect(array('controller'=>'Employees', 'action'=>'logout'));
            throw new NotFoundException(__('Invalid Employee'));
        }
		$EmployeeData = $this->Employee->read(null, $id);
		
		if((empty($EmployeeData['Employee']['email']))||(empty($EmployeeData['Employee']['first_name']))||($EmployeeData['Employee']['gender']==0)||(empty($EmployeeData['Employee']['dob']))||(empty($EmployeeData['Employee']['Employeename']))){
					$AllFields = "";
					if(empty($EmployeeData['Employee']['email'])){
						$AllFields = "email";
					}	
            $this->redirect(array('controller' => 'Employees', 'action' => 'save_missing_fields'));
		}
		
		if($this->request->is('post') || $this->request->is('put')){
			if(!empty($this->request->data)) 
			{
					if(!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) 
					{
						$blackHoleCallback = $this->Security->blackHoleCallback;
						$this->$blackHoleCallback();
					}
					$this->Employee->set($this->request->data['Employee']);
					$this->Employee->setValidation('update_profile');
					
					if ($this->Employee->validates()) {
						$this->Employee->create();
						
						if(!empty($this->request->data['Employee']['profile_image']['tmp_name']))
						{						
							$file = $this->request->data['Employee']['profile_image'];
						}
						else
						{
							$file = '';
						}
						
						
						if(isset($this->request->data['EmployeeImage']['extra_image'][0]['tmp_name']) && empty($this->request->data['EmployeeImage']['extra_image'][0]['tmp_name']))
						{
							unset($this->request->data['EmployeeImage']);
						}	
						//pr($this->request->data);die;
						$images	= array();					
						$postExtraImage = 0;
						if (!empty($this->request->data['EmployeeImage']['extra_image'])) {
							$images = $this->request->data['EmployeeImage']['extra_image'];
							$postExtraImage = count($images);
						}
						unset($this->request->data['Employee']['profile_image']);	
							
						if(!empty($this->request->data['Employee']['full_address'])){
							$addArr = explode(',',$this->request->data['Employee']['full_address']);				
							$this->request->data['Employee']['city']=$addArr[0];
							$this->request->data['Employee']['state']=$addArr[1];
							$this->request->data['Employee']['country']=$addArr[2];
						}
						if ($this->Employee->save($this->request->data)){
														
							$this->Session->setFlash(__('The Employee information has been updated successfully', true), 'admin_flash_success');
							$this->redirect(array('action'=>'update_profile'));
						}
						else 
						{

							$this->Session->setFlash(__('The Employee could not be saved. Please, try again.', true), 'admin_flash_error');
						}
					}
					else 
					{	
						//pr($this->Employee->validationErrors);
						$this->Session->setFlash(__('The Employee could not be saved. Please, try again.', true), 'admin_flash_error');
					}
            }
        }
		else 
		{
            $this->request->data = $this->Employee->read(null, $id);
			if(empty($this->request->data)){
				$this->redirect(array('controller'=>'Employees', 'action'=>'logout'));			
			}
			
			//pr($this->request->data['Employee']);die;
			$cookie = array();
			$EmployeeCookie = $this->Cookie->read('Employee');
            //if(empty($EmployeeCookie)){
				$cookie = base64_encode(serialize($this->request->data['Employee']));
				//pr($cookie);die;
				$this->Cookie->write('Employee', $cookie, true, '+1 years');
				//pr($cookie);die;
			//}
			//die("TESR");
            unset($this->request->data['Employee']['password']);
        }
		
        if (!empty($imageInfo['Employee']['profile_image'])){
            $image = $imageInfo['Employee']['profile_image'];
        }
		else 
		{
            $image = "no_image.png";
        }
		
		$extraimageInfo = $this->EmployeeImage->find('all', array('fields' => array('EmployeeImage.image','EmployeeImage.id','EmployeeImage.image_type'),'conditions'=>array('EmployeeImage.Employee_id'=>$id)));
		$totExtraImage = count($extraimageInfo);
		
        $this->set(compact('id','image','imageInfo','EmployeeData','extraimageInfo','totExtraImage'));
	}

    
    function subscription($Employee_id){
        $this->loadModel('Employee');
    	$Employee =  $this->Employee->find('first',array('conditions'=>array("Employee.id"=>$Employee_id,"Employee.role_id = "=>Configure::read('App.Employee.role'))));
        //pr($Employee);die;
        $this->set(compact('Employee'));
        $this->layout = "welcome";
    
    
    }
    
    
	function forgot_password()
	{
		if($this->Auth->Employee())
		{
			$this->redirect(array('controller'=>'Employees', 'action' => 'update_profile'));
		}
		//echo "<pre>";pr($this->request->data);die;
		if(!empty($this->request->data))
		{
			//$this->loadModel('Employee');
			$this->Employee->set($this->request->data);
			$this->Employee->setValidation('forgot_password');
			if($this->Employee->validates($this->request->data))
			{
				$EmployeeDetail	= $this->Employee->find("first", array('conditions' => array('Employee.email' => $this->request->data["Employee"]["email"] ,'Employee.status' => 1, 'Employee.role_id' => 2)));
				
				if(!empty($EmployeeDetail))
				{
					$this->Employee->id	=	$EmployeeDetail['Employee']['id'];
					$verification_code = substr(md5(uniqid()), 0, 20);
					$EmployeeDetail['Employee']['verification_code'] = $verification_code;
					if($this->Employee->save($EmployeeDetail))
					{
						$activation_url = Router::url(array(
								'controller' => 'Employees',
								'action' => 'get_password',
								base64_encode($EmployeeDetail['Employee']['email']),
								$verification_code
								), true);
						$this->loadModel('Template');
						$forgetPassMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'forgot_password')));
						$subject = $forgetPassMail['Template']['subject'];
						$activation_link	=	' <a href="'.$activation_url.'" target="_blank" shape="rect">Change Password</a>';
							
						$mail_message = str_replace(array('{NAME}', "{ACTIVATION_LINK}"), array($EmployeeDetail['Employee']['display_name'], $activation_link), $forgetPassMail['Template']['content']);
						//die('test');
						$to = $EmployeeDetail['Employee']['email'];
						$from = Configure::read('App.AdminMail');
						$template='default';
						$this->set('message', $mail_message);						 
						$template='default';
						//echo $to."<br>".$subject."<br>".$mail_message."<br>".$from."<br>".$template;die('testing');
						parent::sendMail($to, $subject, $mail_message, $from, $template);	
						$this->Session->setFlash(__('A link has been sent, Please check your inbox'), 'flash_success');
							$this->redirect(array('controller'=>'Employees', 'action' => 'forgot_password'));			
					}
					else
					{
						$this->Session->setFlash(__('Email address not found in our record.', 'flash_error'));
					}
					$this->redirect(array('controller'=>'Employees','action'=>'forgot_password'));
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
		$EmployeeDetail	= $this->Employee->find("first", array('conditions' => array('Employee.email' => $email)));
		if($this->Employee->hasAny(array(
									'Employee.email' => $email,
									'Employee.verification_code' => $verification_code
								)
		))
		{
			if(!empty($this->request->data))
			{
				//pr($this->request->data);die('est');
				$this->Employee->set($this->request->data);
				$this->Employee->setValidation('change_password');
				if($this->Employee->validates($this->request->data))
				{
						$this->request->data['Employee']['id'] = $EmployeeDetail['Employee']['id'];
						$this->request->data['Employee']['password'] = Security::hash($this->request->data['Employee']['password2'], null, true);
						$verification_code = substr(md5(time()), 0, 20);
						$this->request->data['Employee']['verification_code'] = $verification_code;
						
						unset($this->request->data['Employee']['email']);
						if($this->Employee->saveAll($this->request->data))
						{
							$this->redirect(array('action' => 'password_changed'));
						}
				}
			}
			else
			{
				$this->request->data = $this->Employee->findByEmail($email);
			}
		}
		else
		{
			$this->Session->setFlash(__('Invalid Action.'));			
            //$this->redirect(array('controller' => 'Employees', 'action' => 'forgot_password'));
		}		
		$this->set(compact('email', 'verification_code'));
	}
	
	function password_changed()
	{
		$this->set('pageHeading', __('Password changed',true));	
	}
	
	function change_password($id = null){
	
		$this->pageTitle = __('Change Password', true);
		if($this->Auth->Employee())
		{
			if(!empty($this->request->data)){
			$data = $this->Employee->findById(array('id' => $this->Auth->Employee('id')));
			
				$this->request->data['Employee']['id'] = $this->Auth->Employee('id');
				$this->Employee->set($this->request->data);
				$this->Employee->setValidation('mobile_change_password');					
				if($this->Employee->validates())
				{
					//die('octal');
					$new_password = $this->request->data['Employee']['newpassword2'];
					$this->request->data['Employee']['password'] = Security::hash($this->request->data['Employee']['newpassword2'], null, true);
					
					//pr($this->request->data);die('octal');
					
					if($this->Employee->save($this->request->data))
					{					
						$this->Session->setFlash(__('Password updated successfully',true),'flash_good');
						$this->redirect(array('controller' => 'Employees', 'action' => 'update_profile' ));
					} 
					else 
					{
						//$this->Session->setFlash('Error: Password has not been Changed');
						$this->Session->setFlash(__('Please correct the errors listed below.',true), 'flash_error');
						$this->redirect(array('controller' => 'Employees', 'action' => 'change_password' ));
					}
				}
			}
		}
		else
		{
			//$this->Session->setFlash('Error: Invalid Operation');
			$this->Session->setFlash(__('Please correct the errors listed below.',true), 'flash_error');
			$this->redirect(array('controller' => 'Employees', 'action' => 'change_password'));			
		}
		if($this->Auth->Employee('id')!=null)
		{
			$this->request->data = $this->Employee->findById(array('id' => $this->Auth->Employee('id')));
			//pr($this->request->data);die;
			//unset($this->request->data['Employee']['id']);			
			$this->set('profiledata', $this->request->data);	
		}
		$this->set('pageHeading', __('Change Password', true));
	}
	
	function admin_get_Employee_list(){
		$this->layout = 'ajax';
		$q=$_POST['search'];
		$data = $this->Employee->find('list', array('fields'=>array('id', 'Employeename'), 'conditions'=>array("Employee.Employeename like '%$q%'")));
		//pr($data);die;
		if($this->request->is('ajax')){
			$this->set(compact('data', 'q'));
            $this->render('admin_get_Employee_list');
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
				$password = Security::hash($this->request->data['Employee']['password2'], null, true);
				$Employee_data = $this->Employee->find('first', array('conditions'=>array('Employee.Employeename'=>$this->request->data['Employee']['Employeename'], 'Employee.password'=>$password)));
				if(!empty($Employee_data)){
					$this->Session->write('Auth.Employee', $Employee_data['Employee']);
					$this->Auth->_loggedIn = true;					
					//$this->redirect(array('controller' => 'Employees', 'action' => 'my_account'));
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
			$this->Employee->set($this->request->data['Employee']);
			$this->Employee->setValidation('register');
			if($this->Employee->validates()) 
			{
				exit;
			}else{
				$all_errors = $this->Employee->validationErrors;
				$errorMsgArr = array();
				$errorKey = array();
				$strValid = "";
				$count = 0;
				foreach($all_errors as $key=>$value){
					$errorKey[$key] = explode("_", $key);
					$errorKey[$key] = array_map('ucfirst', $errorKey[$key]);
					$errorKey[$key] = implode("", $errorKey[$key]);
					
					//$errorMsgArr["Employee".$errorKey[$key]] = $value[0];
					if($count>0){
						$strValid = $strValid."__"."Employee".$errorKey[$key]."=".$value[0];
					}else{
						$strValid = "Employee".$errorKey[$key]."=".$value[0];
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
		$this->Employee->bindModel(array('belongsTo'=>array('SubscriptionPlan'=>array('className'=>'SubscriptionPlan', 'foreignKey'=>'subscription_plan_id'))));

	
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
        $filters_without_status = $filters = array('Employee.role_id' => Configure::read('App.Role.Employee'));

        if ($defaultTab != 'All'){
            $filters[] = array('Employee.status' => array_search($defaultTab, Configure::read('Status')));
        }
        
		/**get offers details */
		$this->loadModel('Offer');
		$offer_details = $this->Offer->find('first',array('conditions'=>array('Offer.id'=>$id)));
		$offer_name = $offer_details['Offer']['name'];
		$offer_subject = $offer_details['Offer']['subject'];
		/**get offers details */
		$Employee_email = "";
		$Employee_alter_email = "";
		if (!empty($this->request->data)){
		
		    //pr($this->request->data);die;
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');

            App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
            if (!empty($this->request->data['Employee']['email'])) {
                $email = Sanitize::escape($this->request->data['Employee']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            
            if (!empty($this->request->data['Employee']['alternate_email'])) {
                $alternate_email = Sanitize::escape($this->request->data['Employee']['alternate_email']);
                $this->Session->write('AdminSearch.alternate_email', $alternate_email);
            }
        	if (!empty($this->request->data['Employee']['subscription_plan_id'])) {
                $subscription_plan_id = Sanitize::escape($this->request->data['Employee']['subscription_plan_id']);
                $this->Session->write('AdminSearch.subscription_plan_id', $subscription_plan_id);
            }
			if (isset($this->request->data['Employee']['status']) && $this->request->data['Employee']['status'] != ''){
                $status = Sanitize::escape($this->request->data['Employee']['status']);
                $this->Session->write('AdminSearch.status', $status);
                $defaultTab = Configure::read('Status.' . $status);
            }
			$Employee_email = $this->request->data['Employee']['email'];
			$Employee_alter_email = $this->request->data['Employee']['alternate_email'];
			
		
        } else{
			$this->request->data = $this->Offer->findById($id);
			$this->request->data['Employee']['content'] = $this->request->data['Offer']['content'];
		}

        $search_flag = 0;
        $search_status = '';
        if ($this->Session->check('AdminSearch')) {
            $keywords = $this->Session->read('AdminSearch');

            foreach ($keywords as $key => $values) {
                if ($key == 'status') {
                    $search_status = $values;
                    $filters[] = array('Employee.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('Employee.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }		
            $search_flag = 1;
        }
        
        $this->set(compact('search_flag', 'defaultTab'));

        #pr($filters); die;

        $this->paginate = array(
            'Employee' => array(
                'limit' => $number_of_record,
                'order' => array('Employee.id' => 'DESC'),
                'conditions' => $filters
            )
        );
		/**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
        #pr($this->paginate);
        $data = $this->paginate('Employee');
        //pr($offer_details); die;
        $this->set(compact('data', 'subscription_plans','offer_details','id'));
        $this->set('title_for_layout', __('Employees', true));


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
                $temp[] = array('Employee.status' => 1);
                $active = $this->Employee->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp = $filters_without_status;
                $temp[] = array('Employee.status' => 0);
                $inactive = $this->Employee->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }
		$this->request->data = $this->Offer->findById($id);
		$this->request->data['Employee']['content'] = $this->request->data['Offer']['content'];
		
		//if(isset($this->request->data)){
		$this->request->data['Employee']['email'] = $Employee_email;
		$this->request->data['Employee']['alternate_email'] = $Employee_alter_email;
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
				if (empty($this->request->data['Employee']['content'])){
					$this->Session->setFlash(__('Message box did not allow blank.'), 'admin_flash_error');
					$this->redirect($this->referer());
				}
				$ids = array();
				foreach ($this->request->data['Employee']['id'] AS $value) {
					if ($value != 0) {
						$ids[] = $value;
					}
				}
				if (count($ids) == 0) {
					$this->Session->setFlash(__('No Employee selected.'), 'admin_flash_error');
					$this->redirect($this->referer());
				}
				//pr($ids);die;
				$allData = $this->Employee->find('all', array('fields'=>array('Employee.id','Employee.first_name','Employee.last_name','Employee.email'),'conditions'=>array('Employee.id'=>$ids)));
				$to = array();
				foreach($allData as $data){
					$to[] = $data['Employee']['email'];
				}
				$from    = Configure::read('App.AdminMail');
				$mail_message = '';
				$this->loadModel('Template');
				$offerMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'offer_mail')));
				$email_subject = $offerMail['Template']['subject'];
				$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
				$main_message = $this->request->data['Employee']['content'];
				$mail_message = str_replace(array('{NAME}','{SUBJECT}','{MAILMESSAGE}','{DISCOUNT}','{PROMOCODE}','{STARTDATE}','{ENDDATE}'), array($offer_name, $offer_subject, $main_message,$offer_discount,$offer_promo_code,$offer_start_date,$offer_end_date), $offerMail['Template']['content']);
				$template = 'default';
				$this->set('message', $mail_message);
				//pr($mail_message);die;
				parent::sendMail($to, $subject, $mail_message, $from, $template);
				$this->Session->setFlash('Email send successfully ', 'admin_flash_success');
				$this->redirect(array('controller' => 'Employees', 'action' => 'send_offer_email',$id));
				//-----send offer mail ----//
						
        } else{
			$this->request->data = $this->Offer->findById($id);
			$this->request->data['Employee']['content'] = $this->request->data['Offer']['content'];
		}
    //$this->redirect(array('controller' => 'Employees', 'action' => 'send_offer_email',$id));
    }
	
    
   
    
     /*
     * List all AFFILATE  in admin panel
     */
 public function admin_affilate($Employee_id, $defaultTab = 'All'){	
		//pr($this->params);die;
    	/** Load Template,SubscriptionPlan Model   */
        $this->loadModel('SubscriptionPlan');
        $this->loadModel('EmployeeAffilate');
    	$this->Employee->id =$Employee_id;
		$Employee = $this->Employee->read(null, $Employee_id);
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
        if (!empty($this->request->data)){
		
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }

		if ($this->Session->check('number_of_record')) {
				$number_of_record = $this->Session->read('number_of_record');
				$this->request->data['Number']['number_of_record'] = $number_of_record;
		}
        $search_flag = 0;
        $search_status = '';
      
        $this->set(compact('search_flag', 'defaultTab'));
		
		$number_of_record = Configure::read('App.AdminPageLimit');
        
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
      
        if ($Employee_id != ''){
            $filters[] = array('EmployeeAffilate.Employee_id' => $Employee_id);
        }

        $this->set(compact('search_flag', 'defaultTab'));

        #pr($filters); die;

        $this->paginate = array(
            'EmployeeAffilate' => array(
                'limit' => $number_of_record,
                'order' => array('EmployeeAffilate.id' => 'DESC'),
                'conditions' => $filters
            )
        );
	    if ($this->request->is('ajax')) {
	            $this->render('ajax/admin_index');
	        } else {
	            $active = 0;
	            $inactive = 0;
	            if ($search_status == '' || $search_status == Configure::read('App.Status.active')) {
	                $temp[] = array('Employee.status' => 1);
	                $active = $this->Employee->find('count', array('conditions' => $temp));
	            }
	            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
	                $temp[] = array('Employee.status' => 0);
	                $inactive = $this->Employee->find('count', array('conditions' => $temp));
	            }
	
	            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
	            $this->set(compact('tabs'));
	        }
    	$data = $this->paginate('EmployeeAffilate');
        $this->set(compact('data','Employee'));
        $this->set('title_for_layout', __('Affilates by  '.$Employee['Employee']['first_name']." ".$Employee['Employee']['last_name'], true));
 }
    
 
 /**
	 * Send notifications to Employees if their payment is not received
	 */
	public function admin_notification($id = null) {
		$this->loadModel('EmployeeAffilate');
		$this->EmployeeAffilate->id = $id;
		if (!$this->EmployeeAffilate->exists()) {
			throw new NotFoundException(__('Invalid Affilate'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
	
		$this->loadModel('Employee');
		$this->loadModel('Template');
		$this->EmployeeAffilate->bindModel(array('belongsTo'=>array('Employee')),false);
		
		$Employee_id = $this->EmployeeAffilate->field('Employee_id');
		$EmployeeAffilate = $this->EmployeeAffilate->find('first', array('conditions'=>array('EmployeeAffilate.id'=>$id),'fields'=>array('EmployeeAffilate.name','EmployeeAffilate.amount','EmployeeAffilate.referral_ip','EmployeeAffilate.status', 'Employee.first_name','Employee.last_name', 'Employee.email',  'Employee.id' )));
		$to = $EmployeeAffilate['Employee']['email'];
		
		$from    = Configure::read('App.AdminMail');
		$mail_message = '';
		$registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'affilate_notification')));
		$email_subject = $registrationMail['Template']['subject'];
		$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
		$affilate_url = Configure::read('App.SiteUrl');

		$payment_link	=	'<a href="'.$affilate_url.'">Affilate Please Click Here</a>';
		$status = Configure::read('affilate_status.'.$EmployeeAffilate['EmployeeAffilate']['status']);
		$mail_message = str_replace(array('{NAME}','{STATUS}','{PAYMENT_LINK}'), array($EmployeeAffilate['EmployeeAffilate']['name'], $EmployeeAffilate['EmployeeAffilate']['status'],$payment_link,  $payment_link), $registrationMail['Template']['content']);
		$template = 'default';
		$this->set('message', $mail_message);
		parent::sendMail($to, $subject, $mail_message, $from, $template);
		$this->Session->setFlash(__('Affilate notification has been sent.'), 'admin_flash_success');
		$this->redirect(array('action' => 'affilate', $EmployeeAffilate['Employee']['id'] ));
	
		
	}
	
 
 
 
	function test_mail(){
				$from    = Configure::read('App.AdminMail');
				$subject = "TEST SUBJECT BY KK";
				$mail_message = "TESTING MAIL";
				$template = 'default';
				$this->set('message', $mail_message);
				//pr($mail_message);die;
				parent::sendMail("krishna@octalsoftware.com", $subject, $mail_message, $from, $template);
				die("TEST");
	}
	
	function get_affilates($affilateLink = null){
		echo $affilateData = base64_decode($affilateLink);
		//Entry in DB when affilate link 
		// Create Cookiee
		
		die();
	}


/*******************************************Employee Section Front***********************************************************/
     /*
     * List all Employees in user panel
     */

    public function employee_list($defaultTab = 'All'){ 
        // print_r($this->Auth->User('id'));die;
        $user_id = $this->Auth->User('id');
        $count = $this->Employee->find('count', array('conditions' => array('Employee.user_id' => $user_id)));
    
        
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
       $filters = array();
        if ($defaultTab != 'All'){
            $filters[] = array('Employee.status' => array_search($defaultTab, Configure::read('Status')));
        }

        $search_flag = 0;
        $search_status = '';
        if ($this->Session->check('EmployeeSearch')) {
            $keywords = $this->Session->read('EmployeeSearch');

            foreach ($keywords as $key => $values) {
                if ($key == 'status') {
                    $search_status = $values;
                    $filters[] = array('Employee.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('Employee.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Employee.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }       
            $search_flag = 1;
        }
        //$this->Employee->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
        
        $this->set(compact('search_flag', 'defaultTab'));
        $this->Employee->bindModel(array('hasOne'=>array('Attendance'=>array('className'=>'Attendance', 'foreignKey'=>'employee_id', 'conditions' => array('Attendance.date' => date('Y-m-d'))))));
       
        $this->paginate = array(
            'Employee' => array(
                'order' => array('Employee.modified' => 'DESC'),
                'limit' => $count,
                'conditions' => $filters
             )
        );
        
        $data = $this->paginate('Employee');
        // echo "<pre>";
        // print_r($data);die;
        $this->set(compact('data'));
        $this->set('title_for_layout', __('Employees', true));


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
                $temp[] = array('Employee.status' => 1);
                $active = $this->Employee->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp[] = array('Employee.status' => 0);
                $inactive = $this->Employee->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }

        $this->layout = "dashboard";
    }

    /*
     * delete existing Employee
     */
    public function delete($id = null){
        // echo "Hello form delete Employee";die;
        $this->Employee->id = $id;

        if (!$this->Employee->exists()){
            throw new NotFoundException(__('Invalid Employee'));
        }
      
        if ($this->Employee->deleteAll(array('Employee.id'=>$id))) {

            $this->Session->setFlash(__('Employee deleted successfully'), 'flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Employee was not deleted', 'flash_error'));
        $this->redirect($this->referer());
    }

    /*
     * View existing Employee
     */

    public function view($id = null){
        $this->Employee->id = $id;
        if(!$this->Employee->exists()){
            throw new NotFoundException(__('Invalid Employee'));
        } 
        $data = $this->Employee->read(null, $id);
       // echo "<pre>";
       // print_r($data);die;
        $this->set('Employee', $data);
        $this->layout = "dashboard";
    }

    /*
     * edit existing Employee
     */
    public function edit($id = null) {
        $this->layout = "dashboard";
        
        $this->Employee->id = $id;
        
        if (!$this->Employee->exists()) {
            throw new NotFoundException(__('Invalid Employee'));
        }
        $image = $this->Employee->read(array('image'), $id);
        if($this->request->is('post') || $this->request->is('put')) {
        
            if(!empty($this->request->data)) {
                 // print_r($this->request->data);die;
                    $this->Employee->set($this->request->data['Employee']);
                    $this->Employee->setValidation('admin');
                    if ($this->Employee->validates()) {
                        $this->Employee->create();
                        if(!empty($this->request->data['Employee']['image']['name'])){
                            if($this->request->data['Employee']['image']['size'] <  6291456){
                                $path_info = pathinfo($this->request->data['Employee']['image']['name']);
                                $this->request->data['Employee']['image']['name'] = $path_info['filename']."_".time().".".$path_info['extension'];

                                $res3 = $this->Upload->upload($this->request->data['Employee']['image'], WWW_ROOT ."uploads/note_image/original". DS, '', '', array('png', 'jpg', 'jpeg', 'gif'));

                                $this->request->data['Employee']['image']= $this->Upload->result;
                                if(WWW_ROOT ."uploads/note_image/original". DS.$image['Employee']['image'])
                                unlink(WWW_ROOT ."uploads/note_image/original". DS.$image['Employee']['image']);
                             }   
                        }else{
                            $this->request->data['Employee']['image'] = $image['Employee']['image'];
                        }
                        if ($this->Employee->saveAll($this->request->data)) {
                    
                            $this->Session->setFlash(__('The Employee information has been updated successfully', true), 'flash_success');
                            // print_r($this->request->data);die;
                            $this->redirect(array('action' => 'employee_list'));
                        } 
                        else 
                        {

                            $this->Session->setFlash(__('The Employee could not be saved. Please, try again.', true), 'flash_error');
                        }
                    }
                    else 
                    {   
                        
                        $this->Session->setFlash(__('The Employee could not be saved. Please, try again.', true), 'flash_error');
                    }
            }
        } 
        else 
        {
            $this->request->data = $this->Employee->read(null, $id);
        }
         // print_r($this->request->data);die;
    }

    /*
     * add new Employee
     */
    public function add(){
        // echo $this->RandomString();die;
// echo "Hello from add Employee";die;
        if ($this->request->is('post')) {

            if (!empty($this->request->data)) {
                /* unset user skill 0 position value if exist */

                $this->Employee->set($this->request->data['Employee']);
                $this->Employee->setValidation('admin');
                //$this->request->data['Employee']['emp_code'] = $this->RandomString();
                if(!empty($this->request->data['Employee']['image']['name'])){
                    if($this->request->data['Employee']['image']['size'] <  6291456){
                        $path_info = pathinfo($this->request->data['Employee']['image']['name']);
                        $this->request->data['Employee']['image']['name'] = $path_info['filename']."_".time().".".$path_info['extension'];

                        $res3 = $this->Upload->upload($this->request->data['Employee']['image'], WWW_ROOT ."uploads/note_image/original". DS, '', '', array('png', 'jpg', 'jpeg', 'gif'));

                        $this->request->data['Employee']['image']= $this->Upload->result;
                     }   
                }
                
                if ($this->Employee->saveAll($this->request->data)) {
                    $userId = $this->Employee->id;
                    
                    $this->Session->setFlash(__('Employee has been saved successfully'), 'flash_success');
                    $this->redirect(array('action' => 'employee_list'));
                } else {
                    $this->Session->setFlash(__('The Employee could not be saved. Please, try again.'), 'flash_error');
                }
            }
        }else{
            $this->request->data['Employee']['emp_code'] = $this->RandomString();
        }
        $this->set('title_for_layout', __('Employees', true));
        $this->layout = "dashboard";
    }

     function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 6; $i++) {
            $randstring.= $characters[rand(0, strlen($characters))];
        }
        $this->loadModel("Employee");
        $employeeCodeExist = $this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$randstring)));
        if(isset($employeeCodeExist['Employee']['id']) && !empty($employeeCodeExist['Employee']['id'])){
            $this->RandomString();
        }
        return $randstring;
    }


    /************************Add Staff Attendance************************/

    function add_attendance($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Employee");
        $this->loadModel("Attendance");
    // print_r($decoded);die;       
        $emp_code = isset($decoded['emp_code']) ? $decoded['emp_code'] : '';
        $emp_time = date('Y-m-d H:i:s');
        $date = isset($decoded['date']) ? $decoded['date'] : date('Y-m-d');
        if(!empty($emp_code)){
            $employeeCode = $this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$emp_code)));
            if(isset($employeeCode['Employee']['id']) && !empty($employeeCode['Employee']['id'])){
                $attendanceTouchId = $this->Attendance->find('first', array('conditions'=>array('Attendance.emp_code'=>$emp_code, 'Attendance.status'=>1, 'Attendance.date'=>$date),'fields'=>array('Attendance.emp_code')));
                if(isset($attendanceTouchId['Attendance']['emp_code']) && !empty($attendanceTouchId['Attendance']['emp_code'])){
                    $responseArr = array('status' => 'success', 'attendance_status' => '3',  'msg' => 'あなたはすでに今日チェックアウトしました。' );
                    $jsonEncode = json_encode($responseArr);
                }else{

                    $attendanceTouchIdcheckIn = $this->Attendance->find('first', array('conditions'=>array('Attendance.emp_code'=>$emp_code, 'Attendance.status'=>0, 'Attendance.date'=>$date)));
                    $employee_id = $employeeCode['Employee']['id'];
                    if(isset($attendanceTouchIdcheckIn['Attendance']['emp_code']) && !empty($attendanceTouchIdcheckIn['Attendance']['emp_code'])){
                        $attendance['Attendance']['id'] =$attendanceTouchIdcheckIn['Attendance']['id'];
                        $attendance['Attendance']['user_id'] =isset($decoded['user_id']) ? $decoded['user_id'] : '';
                        $attendance['Attendance']['date'] =$date;
                        $attendance['Attendance']['emp_code'] =$emp_code;
                        $attendance['Attendance']['employee_id'] =$employee_id;
                        $attendance['Attendance']['checkout_time'] =$emp_time;
                        $attendance['Attendance']['lunch_time'] = $employeeCode['Employee']['lunch_time'];
                        $attendance['Attendance']['start_lunch_time'] = $employeeCode['Employee']['start_lunch_time'];
                        $attendance['Attendance']['end_lunch_time'] = $employeeCode['Employee']['end_lunch_time'];
                        $attendance['Attendance']['status'] =1;
                        if($this->Attendance->saveAll($attendance)){
                            $attendance_id = $this->Attendance->id;
                            $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';;
                            $responseArr = array('user_id' => $user_id, 'attendance_id' => $attendance_id, 'status' => 'success', 'msg' =>'Employee Checked Out Successfully' );
                            $jsonEncode = json_encode($responseArr);
                            
                        }else{
                            $responseArr = array('status' => 'error' );
                            $jsonEncode = json_encode($responseArr);
                        }
                    
                    }else{
                        $attendance['Attendance']['user_id'] =isset($decoded['user_id']) ? $decoded['user_id'] : '';
                        $attendance['Attendance']['date'] =$date;
                        $attendance['Attendance']['emp_code'] =$emp_code;
                        $attendance['Attendance']['employee_id'] =$employee_id;
                        $attendance['Attendance']['checkin_time'] =$emp_time;
                        $attendance['Attendance']['checkout_time'] ='';
                        $attendance['Attendance']['lunch_time'] = $employeeCode['Employee']['lunch_time'];
                        $attendance['Attendance']['start_lunch_time'] = $employeeCode['Employee']['start_lunch_time'];
                        $attendance['Attendance']['end_lunch_time'] = $employeeCode['Employee']['end_lunch_time'];
                        $attendance['Attendance']['status'] =0;
                        if($this->Attendance->saveAll($attendance)){
                            $attendance_id = $this->Attendance->id;
                            $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';;
                            $responseArr = array('user_id' => $user_id, 'attendance_id' => $attendance_id, 'status' => 'success', 'msg' =>'Employee Checked In Successfully' );
                            $jsonEncode = json_encode($responseArr);
                            
                       }else{
                            $responseArr = array('status' => 'error' );
                            $jsonEncode = json_encode($responseArr);
                        }
                    }

                }

            }else{
                $responseArr = array('status' => 'error',  'msg' => 'Employee does not exist.' );
                $jsonEncode = json_encode($responseArr);

            }

        }else{
            $responseArr = array('status' => 'error',  'msg' => 'Employee does not exist.' );
            $jsonEncode = json_encode($responseArr);
        }
        echo  $jsonEncode;exit();

    }

    /*
     * Employee login
     */
    public function login() {
        
        $this->layout = "user_login";       
        if($this->request->is('post')) {
            
            if (!empty($this->request->data)) {
                // print_r($this->request->data);die;
                $this->Employee->set($this->request->data['Employee']);
                $this->Employee->setValidation('employee_login');
                
                if($this->Employee->validates()) {
                     $find_by_emp_code = $this->Employee->find('first', array('conditions' => array('emp_code' => $this->request->data['Employee']['emp_code'], 'status'=>1)));
                     // print_r($find_by_emp_code);die;
                     if (isset($find_by_emp_code['Employee']['emp_code']) && !empty($find_by_emp_code['Employee']['emp_code'])) {
                          $this->Session->write('employee', $find_by_emp_code);
                          $this->redirect(array('controller'=>'employees','action'=>'employee_home'));        
                    }
                    else 
                    {
                        $this->Session->setFlash(__('Invalid Employee Code, try again', 'flash_error'));
                    }
                }else{
                    $this->Session->setFlash(__('Invalid Employee Code, try again', 'flash_error'));
                }
            }
        }       
    }

    /*
    * List Attendance under Employee panel
    *
    */

    function employee_home(){
        // print_r($this->Session->read('employee'));
        // die;
        // print_r($this->Session->read('employee.Employee.emp_code'));
        // die;
        $this->loadModel('Attendance');
        $emp_code = $this->Session->read('employee.Employee.emp_code');
        $count = $this->Attendance->find('count', array('conditions' => array('Attendance.emp_code' => $emp_code)));
        
        $sdate = date('Y-m-d', strtotime("-30 days"));
        $edate = date("Y-m-d");

        // echo $sdate;die;

        $filters = array('Attendance.emp_code' => $emp_code, array('date(Attendance.date) BETWEEN ? AND ?' => array($sdate,$edate)));

        $this->paginate = array(
            'Attendance' => array(
                'order' => array('Attendance.modified' => 'DESC'),
                'limit' => $count,
                'conditions' => $filters
             )
        );
        

          


        $data = $this->paginate('Attendance');
        $this->set(compact('data'));
        $this->set('title_for_layout', __('Attendance', true));

        $today = $this->Attendance->find('first', array( 'conditions' => array('Attendance.date' => date('Y-m-d'),'Attendance.emp_code' => $emp_code)));
        $status = '';
        if(!empty($today)){
            $status = $today['Attendance']['status'];
        }
        $this->set(compact('status'));
        $this->layout = "employee_dashboard";
    }


    /*
     * List all Customers in Employee panel
     */

    public function customer_list($defaultTab = 'All'){ 
        // print_r($this->Session->read('employee.Employee.user_id'));
        // die;
        $this->loadModel('Customer');
        $user_id = $this->Session->read('employee.Employee.user_id');
        $count = $this->Customer->find('count', array('conditions' => array('Customer.user_id' => $user_id)));
        // echo $count;die; 
    
        $number_of_record = Configure::read('App.AdminPageLimit');
        
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
        $filters = array('Customer.user_id' => $user_id);
        
        $this->Customer->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
        
        $this->set(compact('search_flag', 'defaultTab'));

        $this->paginate = array(
            'Customer' => array(
                'order' => array('Customer.modified' => 'DESC'),
                'limit' => $count,
                'conditions' => $filters
             )
        );
        $this->loadModel('Service');
        $this->loadModel('CustomerForm');
        $filters = array('CustomerForm.user_id' => $user_id);
        $this->CustomerForm->bindModel(array('belongsTo' => array('Service')));
        $serviceData = $this->CustomerForm->find("all", array("conditions"=>$filters, "fields"=>array("Service.id","Service.name")));
         // pr($serviceData);die;
        foreach ($serviceData as $serviceKey => $serviceValue) {
            $serviceList[$serviceValue["Service"]["id"]] = $serviceValue["Service"]["name"];
        }
         // pr($serviceList);die;
        $data = $this->paginate('Customer');
       // echo "<pre>";
        //print_r($data);die;
        $this->set(compact('data','serviceList'));
        $this->set('title_for_layout', __('Customers', true));

        $this->Session->write('Url.defaultTab', $defaultTab);
       
        $active = 0;
        $inactive = 0;
        
        $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
        $this->set(compact('tabs'));

        $this->layout = "employee_dashboard";
    }


    public function services_list(){
        echo "This page is under construction";die;
        $this->layout = "employee_dashboard";
    }

}