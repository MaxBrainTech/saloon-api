<?php

/**
 * Customers Controller
 *
 * PHP version 5.4
 *
 */
class CustomersController extends AppController{

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Customers';
    public $components = array(
        'General', 'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('Customer');

    /*
     * beforeFilter
     * @return void
     */

    function beforeRender() {
    	
        $model = Inflector::singularize($this->name);
      
    }

    public function beforeFilter(){
    	
        parent::beforeFilter();
        $this->loadModel('Customers');
		$this->Auth->allow('start_access', 'reset_password', 'thankx', 'reset_password_change','login', 'subscription', 'email_confirm', 'register', 'activate', 'success', 'fbconnect', 'forgot_password','get_password', 'password_changed', 'linked_connect', 'save_linkedin_data', 'tw_connect', 'tw_response', 'glogin', 'save_google_info','social_login', 'tlogin', 'save_cover_photo', 'getTwitterData', 'fb_data', 'fb_logout', 'social_join_mail', 'home', 'checkunique', 'checklogin', 'test_mail', 'get_affilates', 'get_service_form', 'save_service_form');
    }

   



    /*
     * List all Customers in admin panel
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
            $filters[] = array('Customer.status' => array_search($defaultTab, Configure::read('Status')));
        }

        if (!empty($this->request->data)){
		
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');

            App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
            if (!empty($this->request->data['Customer']['email'])) {
                $email = Sanitize::escape($this->request->data['Customer']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            
            if (!empty($this->request->data['Customer']['alternate_email'])) {
                $alternate_email = Sanitize::escape($this->request->data['Customer']['alternate_email']);
                $this->Session->write('AdminSearch.alternate_email', $alternate_email);
            }
        	if (!empty($this->request->data['Customer']['subscription_plan_id'])) {
                $subscription_plan_id = Sanitize::escape($this->request->data['Customer']['subscription_plan_id']);
                $this->Session->write('AdminSearch.subscription_plan_id', $subscription_plan_id);
            }
			if (isset($this->request->data['Customer']['status']) && $this->request->data['Customer']['status'] != ''){
                $status = Sanitize::escape($this->request->data['Customer']['status']);
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
                    $filters[] = array('Customer.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('Customer.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }		
            $search_flag = 1;
        }
        $this->Customer->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
        
        $this->set(compact('search_flag', 'defaultTab'));

        $this->paginate = array(
            'Customer' => array(
                'limit' => $number_of_record,
                'order' => array('Customer.id' => 'DESC'),
                'conditions' => $filters
             )
        );
		
    	$data = $this->paginate('Customer');
       // echo "<pre>";
        //print_r($data);die;
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
                $temp[] = array('Customer.status' => 1);
                $active = $this->Customer->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp[] = array('Customer.status' => 0);
                $inactive = $this->Customer->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }
    }

	
    /*
     * View existing Customer
     */

    public function admin_view($id = null){
    	$this->Customer->id = $id;
        if(!$this->Customer->exists()){
            throw new NotFoundException(__('Invalid Customer'));
        }
        $this->Customer->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
       
        $this->Customer->recursive = 3;
        $data = $this->Customer->read(null, $id);
      //  echo "<pre>";
       // print_r($data);die;
        $this->set('Customer', $data);
        
    }

     /*
     * View existing Customer
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
     * add Customer
     */
    public function admin_add(){
		if($this->Session->check('Auth.Customer.id') && $this->Session->read('Auth.Customer.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
		/** Load Template,SubscriptionPlan Model   */
        $this->loadModel('Template');
        $this->loadModel('SubscriptionPlan');
       
        if ($this->request->is('post')) {

            if (!empty($this->request->data)) {

                /* unset Customer skill 0 position value if exist */

                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }

                $this->Customer->set($this->request->data['Customer']);
                $this->Customer->setValidation('admin');

                $this->request->data['Customer']['password'] = Security::hash($this->request->data['Customer']['password2'], null, true);
				 $this->request->data['Customer']['origional_password'] = $this->request->data['Customer']['password2'];

                $this->Customer->create();

                $this->request->data['Customer']['role_id'] = Configure::read('App.Role.Customer');
               // $file = $this->request->data['Customer']['profile_image'];
                unset($this->request->data['Customer']['profile_image']);
				
                if ($this->Customer->saveAll($this->request->data)) {
                    #Customer image upload
                    $CustomerId = $this->Customer->id;
                    /* $upload = $this->General->imageUpload($CustomerId, 'Customer', $file, $file['tmp_name'], '');
                      $this->Customer->saveField('profile_image', $upload); */
                    //pr($_FILES);
                    if (!empty($file) && $file['tmp_name'] != '' && $file['size'] > 0) {
                        $rules = array('size' => array(Customer_THUMB_WIDTH, Customer_THUMB_HEIGHT), 'type' => 'resizecrop');
                        $tinyrules = array('size' => array(Customer_TINY_WIDTH, Customer_TINY_HEIGHT), 'type' => 'resizecrop');
                        $thumb1 = array('size' => array(Customer_THUMB_WIDTH1, Customer_THUMB_HEIGHT1), 'type' => 'resizecrop');
                        $back = array('size' => array(Customer_LARGE_WIDTH, Customer_LARGE_HEIGHT), 'type' => 'resizecrop');
                        // Upload the image using the Upload component
                        $path_info = pathinfo($file['name']);
                        $file['name'] = $path_info['filename'] . "_" . time() . "." . $path_info['extension'];
                        $tinyResult = $this->Upload->upload($file, WWW_ROOT . Customer_TINY_DIR
                                . DS, '', $tinyrules);
                        $result = $this->Upload->upload($file, WWW_ROOT . Customer_THUMB_DIR
                                . DS, '', $rules);
                        $result = $this->Upload->upload($file, WWW_ROOT . Customer_THUMB1_DIR
                                . DS, '', $thumb1);
                        $result = $this->Upload->upload($file, WWW_ROOT . Customer_LARGE_DIR
                                . DS, '', $back);
                        $res1 = $this->Upload->upload($file, WWW_ROOT . Customer_ORIGINAL_DIR . DS, '');
                        if (!empty($this->Upload->result) && empty($this->Upload->errors)) {
                            $this->Customer->updateAll(array('Customer.profile_image' => "'" . $this->Upload->result . "'"), array('Customer.id' => $CustomerId));
                        } else {
                            $responseData["error"] = $this->Upload->errors[0];
                        }
                    } else {
                        $responseArray["error"] = "Image is empty or Size is Zero.";
                    }

              
					 $this->Session->setFlash(__('Customer has been saved successfully'), 'admin_flash_success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The Customer could not be saved. Please, try again.'), 'admin_flash_error');
                }
            }
        }
         /**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
    	$this->set(compact('subscription_plans'));
    }

    /*
     * edit existing Customer
     */
    public function admin_edit($id = null) {
		
		$this->Customer->id = $id;
		
        $imageInfo = $this->Customer->find('first', array('conditions' => array('Customer.id' => $id), 'fields' => array('Customer.profile_image','Customer.fb_id','Customer.id','Customername')));
        if (!$this->Customer->exists()) {
            throw new NotFoundException(__('Invalid Customer'));
        }
        
        /** Load Template,SubscriptionPlan Model   */
        $this->loadModel('SubscriptionPlan');
        
		if($this->request->is('post') || $this->request->is('put')) {
		
			if(!empty($this->request->data)) {
					if(!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
						$blackHoleCallback = $this->Security->blackHoleCallback;
						$this->$blackHoleCallback();
					}
					$this->Customer->set($this->request->data['Customer']);
					$this->Customer->setValidation('admin');
					if ($this->Customer->validates()) {
						$this->Customer->create();
						
						//$this->request->data['Customer']['role_id'] = 2;
						if(!empty($this->request->data['Customer']['profile_image']['tmp_name']))
						{						
							$file = $this->request->data['Customer']['profile_image'];
						}
						else
						{
							$file = '';
						}
						
						unset($this->request->data['Customer']['profile_image']);	
						if(!empty($this->request->data['Customer']['full_address'])){
							$addArr = explode(',',$this->request->data['Customer']['full_address']);
							
							$this->request->data['Customer']['city']=$addArr[0];
							$this->request->data['Customer']['state']=$addArr[1];
							$this->request->data['Customer']['country']=$addArr[2];
						}	
						//pr($this->request->data);die;
						if ($this->Customer->saveAll($this->request->data)) {
					
							$this->Session->setFlash(__('The Customer information has been updated successfully', true), 'admin_flash_success');
							$this->redirect(array('action' => 'index'));
						} 
						else 
						{

							$this->Session->setFlash(__('The Customer could not be saved. Please, try again.', true), 'admin_flash_error');
						}
					}
					else 
					{	
						
						$this->Session->setFlash(__('The Customer could not be saved. Please, try again.', true), 'admin_flash_error');
					}
            }
        } 
		else 
		{
            $this->request->data = $this->Customer->read(null, $id);
            unset($this->request->data['Customer']['password']);
        }
		
        if (!empty($imageInfo['Customer']['profile_image'])){
            $image = $imageInfo['Customer']['profile_image'];
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
     * change Customer password by admin
     */
    
    public function admin_change_password($id = null) {
		if($this->Session->check('Auth.Customer.id') && $this->Session->read('Auth.Customer.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->Customer->id = $id;
        if (!$this->Customer->exists()) {
            throw new NotFoundException(__('Invalid Customer'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            if (!empty($this->request->data)) {
                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }


                //validate Customer data
                $this->Customer->set($this->request->data);
                $this->Customer->setValidation('admin_change_password');
                if ($this->Customer->validates()) {
                    $new_password = $this->request->data['Customer']['new_password'];
                    $this->request->data['Customer']['password'] = Security::hash($this->request->data['Customer']['new_password'], null, true);
                    $this->request->data['Customer']['origional_password'] = $this->request->data['Customer']['new_password'];
                    if ($this->Customer->saveAll($this->request->data)) {
                       
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

            $this->request->data = $this->Customer->read(null, $id);

            unset($this->request->data['Customer']['password']);
        }
    }

    /*
     * delete existing Customer
     */
    public function admin_delete($id = null){
        $Customer_id = $this->Customer->id = $id;

        if (!$this->Customer->exists()){
            throw new NotFoundException(__('Invalid Customer'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
	
		$Customer_data = $this->Customer->find('first',array('conditions'=>array('Customer.id'=>$id)));
		//die;
        if ($this->Customer->deleteAll(array('Customer.id'=>$id))) {

            $this->Session->setFlash(__('Customer deleted successfully'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Customer was not deleted', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

    /*
     * toggle Customer status
     */
    
    public function admin_status($id = null) {
		if($this->Session->check('Auth.Customer.id') && $this->Session->read('Auth.Customer.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->Customer->id = $id;
        if (!$this->Customer->exists()) {
            throw new NotFoundException(__('Invalid Customer'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        $this->loadModel('Template');
        $this->loadModel('Customer');
        if ($this->Customer->toggleStatus($id)) {
            $Customer_info = $this->Customer->get_Customers('first', 'Customer.email,Customer.first_name,Customer.last_name,Customer.status', array('Customer.id' => $id));

            $this->Session->setFlash(__('Customer\'s status has been changed'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Customer\'s status was not changed', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

      
     /*
     * change status and delete Customers 
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
            $action = Sanitize::escape($this->request->data['Customer']['pageAction']);

            $ids = $this->request->data['Customer']['id'];

            if (count($this->request->data) == 0 || $this->request->data['Customer'] == null) {
                $this->Session->setFlash('No items selected.', 'admin_flash_error');
                $this->redirect($this->referer());
            }

            if ($action == "delete") {
		
				$this->Customer->deleteAll(array('Customer.id' => $ids)); 
                $this->Session->setFlash('Customers have been deleted successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "activate") {
                $this->Customer->updateAll(array('Customer.status' => Configure::read('App.Status.active')), array('Customer.id' => $ids));
                $this->Session->setFlash('Customers have been activated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "deactivate") {
                $this->Customer->updateAll(array('Customer.status' => Configure::read('App.Status.inactive')), array('Customer.id' => $ids));
                $this->Session->setFlash('Customers have been deactivated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }
        } else {
            $this->redirect(array('controller' => 'admins', 'action' => 'index'));
        }
    }

	/*
     * reset Customer password 
     */
    
    public function reset_password($id=0)
	{
		$this->loadModel('Template');
		$this->loadModel('Customer');
		$this->layout = false;
		if($this->request->is('post'))
		{
			if(!empty($this->request->data))
			{
				$this->Customer->set($this->request->data);
				$this->Customer->validates();
				$this->Customer->setValidation('reset_password');
				if($this->Customer->validates())
				{
					$Customer_id = base64_decode(base64_decode(base64_decode($id)));
					$Customer_data =  $this->Customer->find('first',array('conditions'=>array("Customer.id"=>$Customer_id,"Customer.role_id = "=>Configure::read('App.Customer.role'))));
					
					
					if(isset($Customer_data) && !empty($Customer_data))
					{
						$this->Customer->id = $Customer_data['Customer']['id'];
						$new_password = Security::hash($this->request->data['Customer']['password'], null, true);
						if($this->Customer->saveField('password',$new_password))
						{
							$password = $this->request->data['Customer']['password'];
							$this->Customer->saveField('origional_password',$this->request->data['Customer']['password']);
							unset($this->request->data['Customer']['password']);
							unset($this->request->data['Customer']['password2']);	
							/*******Reset Password mail code here***/
							$mailMessage='';
							$password_confirm = $this->Template->find('first', array('conditions' => array('Template.slug' => 'forgot_password_confirm')));
							
							$email_subject = $password_confirm['Template']['subject'];
							$subject = '['.Configure::read('Site.title').']'. $email_subject;
							
							$mailMessage = str_replace(array('{NAME}','{PASSWORD}'), array($Customer_data['Customer']['Customername'],$password), $password_confirm['Template']['content']);
							
							if(parent::sendMail($Customer_data['Customer']['email'],$subject,$mailMessage,array(Configure::read('App.AdminMail')=>Configure::read('Site.title')),'general'))
							{
								
							// Save the data in templates_Customers table start //
								$this->loadModel('TemplatesCustomer');
								$templates_Customers['TemplatesCustomer']['Customer_id'] = $Customer_data['Customer']['id'];
								$templates_Customers['TemplatesCustomer']['template_id'] = $password_confirm['Template']['id'];
								$this->TemplatesCustomer->save($templates_Customers);
							// End //
							$this->Session->setFlash('Password change sucessfully.','front_flash_good');
							$this->redirect(array('controller'=>'Customers','action'=>'thankx'));
						 
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
     * change reset Customer password
     */
	
	public function reset_password_change($id=0)
	{
		$this->loadModel('Template');
		$this->loadModel('Customer');
		$this->layout = false;
		if($this->request->is('post'))
		{
			if(!empty($this->request->data))
			{	
				
				$this->Customer->set($this->request->data);
				$this->Customer->validates();
				$this->Customer->setValidation('reset_password');
				if($this->Customer->validates())
				{
					$Customer_id = base64_decode(base64_decode(base64_decode($id)));
					$Customer_data =  $this->Customer->find('first',array('conditions'=>array("Customer.id"=>$Customer_id,"Customer.role_id = "=>Configure::read('App.Customer.role'))));
					
					
					if(isset($Customer_data) && !empty($Customer_data))
					{
						$this->Customer->id = $Customer_data['Customer']['id'];
						$new_password = Security::hash($this->request->data['Customer']['password'], null, true);
						if($this->Customer->saveField('password',$new_password))
						{
							$password = $this->request->data['Customer']['password'];
							$this->Customer->saveField('origional_password',$this->request->data['Customer']['password']);
							unset($this->request->data['Customer']['password']);
							unset($this->request->data['Customer']['password2']);	
							/*******Reset Password mail code here***/
							$mailMessage='';
							$password_confirm = $this->Template->find('first', array('conditions' => array('Template.slug' => 'reset_password_confirm')));
							
							$email_subject = $password_confirm['Template']['subject'];
							$subject = '['.Configure::read('Site.title').']'. $email_subject;
							
							$mailMessage = str_replace(array('{NAME}','{PASSWORD}'), array($Customer_data['Customer']['Customername'],$password), $password_confirm['Template']['content']);
							
							if(parent::sendMail($Customer_data['Customer']['email'],$subject,$mailMessage,array(Configure::read('App.AdminMail')=>Configure::read('Site.title')),'general'))
							{
								
							// Save the data in templates_Customers table start //
								$this->loadModel('TemplatesCustomer');
								$templates_Customers['TemplatesCustomer']['Customer_id'] = $Customer_data['Customer']['id'];
								$templates_Customers['TemplatesCustomer']['template_id'] = $password_confirm['Template']['id'];
								$this->TemplatesCustomer->save($templates_Customers);
							// End //
							$this->Session->setFlash('Password change sucessfully.','front_flash_good');
							$this->redirect(array('controller'=>'Customers','action'=>'thankx'));
						 
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
     * Customer login
     */
	public function login() {
		
		if($this->Auth->Customer())
		{
				//pr($this->Auth->Customer());die('In');
				$this->redirect($this->Auth->redirect());
		}
					
		if($this->request->is('post')) {
			
			if (!empty($this->request->data)) {
				
				$this->Customer->set($this->request->data['Customer']);
				$this->Customer->setValidation('login');
				
				//pr($this->Customer->validates());die;
				
				if($this->Customer->validates()) {
					$this->request->data['Customer']['email'] = $this->request->data['Customer']['login_email'];
					unset($this->request->data['Customer']['login_email']);
					App::Import('Utility', 'Validation');
					if( isset($this->request->data['Customer']['email']) && Validation::email($this->request->data['Customer']['email'])) {
						$this->Auth->authenticate['Form'] = array('fields' =>array('Customername' => 'email'));
					}
					 $find_by_email = $this->Customer->find('first', array('conditions' => array('email' => $this->request->data['Customer']['email'], 'status'=>1), 'fields' => array('id', 'email', 'role_id', 'paypal_id', 'subscription_plan_id', 'is_email_verified', 'payment_status', 'status')));
            		 if (!empty($find_by_email)) {
            		
            	if(!$this->Auth->login()) {
					
						$this->Session->setFlash(__('Invalid email or password, try again'));
					} else {
						$this->Session->write('Customer', $this->Auth->Customer());
						$this->Customer->id = $this->Auth->Customer('id');
						$date = date('d/m/Y');
						//echo $date;die;
						if ($this->Customer->id) {
						    $this->Customer->saveField('last_login', $date);
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
    	//pr($this->Auth->Customer());die('tessst');
	}
	
	/*
     * Customer registration
     */
	public function register()
	{
			if($this->Auth->Customer())
			{
					//pr($this->Auth->Customer());die('In');
					$this->redirect($this->Auth->redirect());
			}
		
			if ($this->request->is('post')) 
			{
				
				if (!empty($this->request->data)) 
				{
					
					$this->Customer->set($this->request->data['Customer']);
					$this->Customer->setValidation('register');
								
					$verification_code = substr(md5(uniqid()), 0, 20);
					$this->request->data['Customer']['verification_code'] = $verification_code;
					$this->request->data['Customer']['status'] = '0';
					//echo $this->request->data['Customer']['subscription_plan_id'];die;
					
					
					if($this->Customer->validates()) 
					{
						//pr($this->request->data);die;
						/* Customer plan detail*/
						$this->loadModel('SubscriptionPlan');
						$subscription_plans_id = $this->request->data['Customer']['subscription_plan_id'];
						$subscription_plans = $this->SubscriptionPlan->find('first',array('conditions'=>array('SubscriptionPlan.id'=>$subscription_plans_id)));
						if($subscription_plans['SubscriptionPlan']['plan_type']==1){
							$duration = $subscription_plans['SubscriptionPlan']['plan_duration'];
							$expireDate = date("Y-m-d H:i:s", strtotime("+".$duration." months"));
							$this->request->data['Customer']['service_expire_date'] = $expireDate;
						}else if($subscription_plans['SubscriptionPlan']['plan_type']==2){
							$duration = $subscription_plans['SubscriptionPlan']['plan_duration'];
							$expireDate = date("Y-m-d H:i:s", strtotime("+".$duration." years"));
							$this->request->data['Customer']['service_expire_date'] = $expireDate;
						}
						$this->request->data['Customer']['password'] = Security::hash($this->request->data['Customer']['password2'], null, true);
						$this->request->data['Customer']['ip'] = $this->RequestHandler->getClientIp();
						$enter_password = $this->request->data['Customer']['password2'];
						$password = $this->request->data['Customer']['password'];
						$name = $this->request->data['Customer']['first_name']." ".$this->request->data['Customer']['last_name'];
						if($this->Customer->saveAll($this->request->data)){
						if($this->request->data['Customer']['subscription_plan_id']==1){
							
								
								/*************** EMAIL NOTIFICATION MESSAGE ****************/	
									$this->Customer->saveField('status', '1');
									$to      = $this->request->data['Customer']['email'];
									$from    = Configure::read('App.AdminMail');
									$mail_message = '';
									$this->loadModel('Template');
									$registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'Customer_registration')));
									$email_subject = $registrationMail['Template']['subject'];
									$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
									$activationCode = $this->request->data['Customer']['verification_code'];
									$activation_url = Router::url(array(
													'controller' => 'Customers',
													'action' => 'email_confirm',
													base64_encode($this->request->data['Customer']['email']),
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
									$this->Session->setFlash(__('The Customer has been registered successfully.', true), 'flash_success');
									$this->redirect(array('controller' => 'Customers', 'action' => 'register'));
								
							
							}else{
								//pr($this->Customer->id);die;
								$this->Customer->saveField('status', '0');
								$this->Session->write('Customer',$this->request->data['Customer']);
								#pr($this->Session->read('Customer'));die;
								$this->Cookie->write('Customer',$this->request->data['Customer'],$encrypt=false,3600);
								#pr($this->Cookie->read('Customer'));die;
								if(!empty($this->request->data['Customer']['subscription_plan_id'])){
									$this->testPaypalGetExpress();
								}else{
									$this->Session->setFlash(__('Please select subscription plan, try again', 'flash_error'));
								}
							
							}
						}				 
						else 
						{
							$this->Session->setFlash(__('The Customer could not be registerd. Please, try again.', 'flash_error'));
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
     * Customer activation
     * Check email confirm by email
     * @param var $email - base64 encoded email
     */
    function email_confirm($email, $act_id) {


        
        $user = $this->Customer->find('first', array('conditions' => array('Customer.email' => base64_decode($email), 'Customer.verification_code' => $act_id)));
     //   pr($user);die;
        $today = strtotime('now');
        #  $new_date = $today + 30 * 24 * 60 * 60;

        if (!empty($user) && count($user)) {
            $this->Customer->updateAll(array('status' => Configure::read('App.Status.active')), array('Customer.id' => $user["Customer"]["id"]));
            $this->Session->setFlash('Active your account.please login.', 'flash_success');
        } else {
            $this->Session->setFlash('This email is not register.', 'flash_error');
        }
        $this->redirect(array('controller' => 'pages', 'action' => 'home', 'varify_customer_email'));
    }
	
	
/*
     * Customer profile
     */
    public function my_profile()
	{
		
		$this->layout = "welcome";
		//pr($this->Auth->Customer());die;
		if(!$this->Auth->Customer())
		{
			$this->redirect($this->Auth->redirect());
		}
		/* load model */
		$this->loadModel('Subcription');
		
		$this->Customer->bindModel(array('belongsTo'=>array('SubscriptionPlan'=>array('className'=>'SubscriptionPlan', 'foreignKey'=>'subscription_plan_id'))));
		$Customer_id = $this->Auth->Customer('id');
		$Customer = $this->Customer->find('first', array('conditions'=>array('Customer.id'=>$Customer_id)));
		$this->set(compact('Customer'));
		
	}
	
	
	 
	/*
     * Customer logout
     */ 
	public function logout(){
		/* $this->Session->delete('access_token');
		$this->Session->delete('Facebook.Customer');
		$this->Session->delete('Twitter.Customer');
		$this->Session->delete('GooglePlus.Customer');
		$this->Session->delete('LinkedIn.Customer');
		$this->Session->delete('LinkedIn.referer');
		$this->Session->delete('Google.referer');
		$this->Cookie->delete('Customer');
		unset($_SESSION['oauth']['linkedin']); */
        $this->redirect($this->Auth->logout());
    }

  

    
		
	function activate($email = null, $verification_code = null)
	{
	    $this->layout	= 'default';
		if ($email == null || $verification_code == null) 
		{
			$this->Session->setFlash(__('Error_Message',true), 'admin_flash_bad');
            $this->redirect(array('controller' => 'Customers', 'action' => 'login'));
        }
		$email = base64_decode($email);
	
		if ($this->Customer->hasAny(array(
									'Customer.email' => $email,
									'Customer.verification_code' => $verification_code,
									//'Customer.status' => 0
									)
									))
		{
			$Customer = $this->Customer->findByEmail($email);
			//activation date code
			$this->Customer->updateAll(array('Customer.modified'=>"'".date('Y-m-d H-i-s')."'"));
		//activation date code close	
			$this->Customer->id = $Customer['Customer']['id'];
			$this->Customer->saveField('status', 1);
			$this->Customer->saveField('is_email_verified', 1);
			$this->Customer->saveField('verification_code', substr(md5(uniqid()), 0, 20));
			
			$to      = $email;			
			$from    = Configure::read('App.AdminMail');
			$mail_message = '';
			$this->loadModel('Template');
			$notificationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'verify_email')));
			$email_subject = $notificationMail['Template']['subject'];
			$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
			$login_url = Router::url(array(
									'controller' => 'Customers',
									'action' => 'register'
									), true);
		
			$login_link	=	'<a href="'.$login_url.'">Click Here To Login</a>';
			$mail_message = str_replace(array('{NAME}','{SITE}','{LOGIN_LINK}'), array($Customer['Customer']['first_name'].''.$Customer['Customer']['last_name'],Configure::read('App.SITENAME'),$login_link), $notificationMail['Template']['content']);
			
			$template = 'default';
			
			$this->set('message', $mail_message);						
			parent::sendMail($to, $subject, $mail_message, $from, $template);
			//$this->Session->setFlash(__('Your Email is verified'));
			$this->redirect(array('controller' => 'Customers', 'action' => 'success'));
		}
		else
		{
			$this->Session->setFlash(__('Verification Failed'));		
            $this->redirect(array('controller' => 'Customers', 'action' => 'login'));
		}
	}
	
	function success() {
	      $this->layout	= 'default';
        /* if ($this->Auth->Customer()){
            $this->redirect(array('controller' => 'programs', 'action' => 'my_program'));
        } */
        $this->set("title_for_layout",__('Success',true));
    }
	
	function update_profile($id = null)
	{	
		$id = $this->Auth->Customer('id');
		$this->Customer->id = $id;
		$this->loadModel('CustomerImage');	
		$this->Customer->bindModel(array('hasMany'=>array('CustomerImage')),false);
        $imageInfo = $this->Customer->find('first', array('conditions' => array('Customer.id' => $id), 'fields' => array('Customer.profile_image','Customer.profile_cover_image','Customer.fb_id','Customer.id','Customer.twitter_id','Customer.linkdin_id','Customer.social_media_image_url','Customername')));
        if (!$this->Customer->exists()){
			$this->redirect(array('controller'=>'Customers', 'action'=>'logout'));
            throw new NotFoundException(__('Invalid Customer'));
        }
		$CustomerData = $this->Customer->read(null, $id);
		
		if((empty($CustomerData['Customer']['email']))||(empty($CustomerData['Customer']['first_name']))||($CustomerData['Customer']['gender']==0)||(empty($CustomerData['Customer']['dob']))||(empty($CustomerData['Customer']['Customername']))){
					$AllFields = "";
					if(empty($CustomerData['Customer']['email'])){
						$AllFields = "email";
					}	
            $this->redirect(array('controller' => 'Customers', 'action' => 'save_missing_fields'));
		}
		
		if($this->request->is('post') || $this->request->is('put')){
			if(!empty($this->request->data)) 
			{
					if(!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) 
					{
						$blackHoleCallback = $this->Security->blackHoleCallback;
						$this->$blackHoleCallback();
					}
					$this->Customer->set($this->request->data['Customer']);
					$this->Customer->setValidation('update_profile');
					
					if ($this->Customer->validates()) {
						$this->Customer->create();
						
						if(!empty($this->request->data['Customer']['profile_image']['tmp_name']))
						{						
							$file = $this->request->data['Customer']['profile_image'];
						}
						else
						{
							$file = '';
						}
						
						
						if(isset($this->request->data['CustomerImage']['extra_image'][0]['tmp_name']) && empty($this->request->data['CustomerImage']['extra_image'][0]['tmp_name']))
						{
							unset($this->request->data['CustomerImage']);
						}	
						//pr($this->request->data);die;
						$images	= array();					
						$postExtraImage = 0;
						if (!empty($this->request->data['CustomerImage']['extra_image'])) {
							$images = $this->request->data['CustomerImage']['extra_image'];
							$postExtraImage = count($images);
						}
						unset($this->request->data['Customer']['profile_image']);	
							
						if(!empty($this->request->data['Customer']['full_address'])){
							$addArr = explode(',',$this->request->data['Customer']['full_address']);				
							$this->request->data['Customer']['city']=$addArr[0];
							$this->request->data['Customer']['state']=$addArr[1];
							$this->request->data['Customer']['country']=$addArr[2];
						}
						if ($this->Customer->save($this->request->data)){
														
							$this->Session->setFlash(__('The Customer information has been updated successfully', true), 'admin_flash_success');
							$this->redirect(array('action'=>'update_profile'));
						}
						else 
						{

							$this->Session->setFlash(__('The Customer could not be saved. Please, try again.', true), 'admin_flash_error');
						}
					}
					else 
					{	
						//pr($this->Customer->validationErrors);
						$this->Session->setFlash(__('The Customer could not be saved. Please, try again.', true), 'admin_flash_error');
					}
            }
        }
		else 
		{
            $this->request->data = $this->Customer->read(null, $id);
			if(empty($this->request->data)){
				$this->redirect(array('controller'=>'Customers', 'action'=>'logout'));			
			}
			
			//pr($this->request->data['Customer']);die;
			$cookie = array();
			$CustomerCookie = $this->Cookie->read('Customer');
            //if(empty($CustomerCookie)){
				$cookie = base64_encode(serialize($this->request->data['Customer']));
				//pr($cookie);die;
				$this->Cookie->write('Customer', $cookie, true, '+1 years');
				//pr($cookie);die;
			//}
			//die("TESR");
            unset($this->request->data['Customer']['password']);
        }
		
        if (!empty($imageInfo['Customer']['profile_image'])){
            $image = $imageInfo['Customer']['profile_image'];
        }
		else 
		{
            $image = "no_image.png";
        }
		
		$extraimageInfo = $this->CustomerImage->find('all', array('fields' => array('CustomerImage.image','CustomerImage.id','CustomerImage.image_type'),'conditions'=>array('CustomerImage.Customer_id'=>$id)));
		$totExtraImage = count($extraimageInfo);
		
        $this->set(compact('id','image','imageInfo','CustomerData','extraimageInfo','totExtraImage'));
	}

    
    function subscription($Customer_id){
        $this->loadModel('Customer');
    	$Customer =  $this->Customer->find('first',array('conditions'=>array("Customer.id"=>$Customer_id,"Customer.role_id = "=>Configure::read('App.Customer.role'))));
        //pr($Customer);die;
        $this->set(compact('Customer'));
        $this->layout = "welcome";
    
    
    }
    
    
	function forgot_password()
	{
		if($this->Auth->Customer())
		{
			$this->redirect(array('controller'=>'Customers', 'action' => 'update_profile'));
		}
		//echo "<pre>";pr($this->request->data);die;
		if(!empty($this->request->data))
		{
			//$this->loadModel('Customer');
			$this->Customer->set($this->request->data);
			$this->Customer->setValidation('forgot_password');
			if($this->Customer->validates($this->request->data))
			{
				$CustomerDetail	= $this->Customer->find("first", array('conditions' => array('Customer.email' => $this->request->data["Customer"]["email"] ,'Customer.status' => 1, 'Customer.role_id' => 2)));
				
				if(!empty($CustomerDetail))
				{
					$this->Customer->id	=	$CustomerDetail['Customer']['id'];
					$verification_code = substr(md5(uniqid()), 0, 20);
					$CustomerDetail['Customer']['verification_code'] = $verification_code;
					if($this->Customer->save($CustomerDetail))
					{
						$activation_url = Router::url(array(
								'controller' => 'Customers',
								'action' => 'get_password',
								base64_encode($CustomerDetail['Customer']['email']),
								$verification_code
								), true);
						$this->loadModel('Template');
						$forgetPassMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'forgot_password')));
						$subject = $forgetPassMail['Template']['subject'];
						$activation_link	=	' <a href="'.$activation_url.'" target="_blank" shape="rect">Change Password</a>';
							
						$mail_message = str_replace(array('{NAME}', "{ACTIVATION_LINK}"), array($CustomerDetail['Customer']['display_name'], $activation_link), $forgetPassMail['Template']['content']);
						//die('test');
						$to = $CustomerDetail['Customer']['email'];
						$from = Configure::read('App.AdminMail');
						$template='default';
						$this->set('message', $mail_message);						 
						$template='default';
						//echo $to."<br>".$subject."<br>".$mail_message."<br>".$from."<br>".$template;die('testing');
						parent::sendMail($to, $subject, $mail_message, $from, $template);	
						$this->Session->setFlash(__('A link has been sent, Please check your inbox'), 'flash_success');
							$this->redirect(array('controller'=>'Customers', 'action' => 'forgot_password'));			
					}
					else
					{
						$this->Session->setFlash(__('Email address not found in our record.', 'flash_error'));
					}
					$this->redirect(array('controller'=>'Customers','action'=>'forgot_password'));
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
		$CustomerDetail	= $this->Customer->find("first", array('conditions' => array('Customer.email' => $email)));
		if($this->Customer->hasAny(array(
									'Customer.email' => $email,
									'Customer.verification_code' => $verification_code
								)
		))
		{
			if(!empty($this->request->data))
			{
				//pr($this->request->data);die('est');
				$this->Customer->set($this->request->data);
				$this->Customer->setValidation('change_password');
				if($this->Customer->validates($this->request->data))
				{
						$this->request->data['Customer']['id'] = $CustomerDetail['Customer']['id'];
						$this->request->data['Customer']['password'] = Security::hash($this->request->data['Customer']['password2'], null, true);
						$verification_code = substr(md5(time()), 0, 20);
						$this->request->data['Customer']['verification_code'] = $verification_code;
						
						unset($this->request->data['Customer']['email']);
						if($this->Customer->saveAll($this->request->data))
						{
							$this->redirect(array('action' => 'password_changed'));
						}
				}
			}
			else
			{
				$this->request->data = $this->Customer->findByEmail($email);
			}
		}
		else
		{
			$this->Session->setFlash(__('Invalid Action.'));			
            //$this->redirect(array('controller' => 'Customers', 'action' => 'forgot_password'));
		}		
		$this->set(compact('email', 'verification_code'));
	}
	
	function password_changed()
	{
		$this->set('pageHeading', __('Password changed',true));	
	}
	
	function change_password($id = null){
	
		$this->pageTitle = __('Change Password', true);
		if($this->Auth->Customer())
		{
			if(!empty($this->request->data)){
			$data = $this->Customer->findById(array('id' => $this->Auth->Customer('id')));
			
				$this->request->data['Customer']['id'] = $this->Auth->Customer('id');
				$this->Customer->set($this->request->data);
				$this->Customer->setValidation('mobile_change_password');					
				if($this->Customer->validates())
				{
					//die('octal');
					$new_password = $this->request->data['Customer']['newpassword2'];
					$this->request->data['Customer']['password'] = Security::hash($this->request->data['Customer']['newpassword2'], null, true);
					
					//pr($this->request->data);die('octal');
					
					if($this->Customer->save($this->request->data))
					{					
						$this->Session->setFlash(__('Password updated successfully',true),'flash_good');
						$this->redirect(array('controller' => 'Customers', 'action' => 'update_profile' ));
					} 
					else 
					{
						//$this->Session->setFlash('Error: Password has not been Changed');
						$this->Session->setFlash(__('Please correct the errors listed below.',true), 'flash_error');
						$this->redirect(array('controller' => 'Customers', 'action' => 'change_password' ));
					}
				}
			}
		}
		else
		{
			//$this->Session->setFlash('Error: Invalid Operation');
			$this->Session->setFlash(__('Please correct the errors listed below.',true), 'flash_error');
			$this->redirect(array('controller' => 'Customers', 'action' => 'change_password'));			
		}
		if($this->Auth->Customer('id')!=null)
		{
			$this->request->data = $this->Customer->findById(array('id' => $this->Auth->Customer('id')));
			//pr($this->request->data);die;
			//unset($this->request->data['Customer']['id']);			
			$this->set('profiledata', $this->request->data);	
		}
		$this->set('pageHeading', __('Change Password', true));
	}
	
	function admin_get_Customer_list(){
		$this->layout = 'ajax';
		$q=$_POST['search'];
		$data = $this->Customer->find('list', array('fields'=>array('id', 'Customername'), 'conditions'=>array("Customer.Customername like '%$q%'")));
		//pr($data);die;
		if($this->request->is('ajax')){
			$this->set(compact('data', 'q'));
            $this->render('admin_get_Customer_list');
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
				$password = Security::hash($this->request->data['Customer']['password2'], null, true);
				$Customer_data = $this->Customer->find('first', array('conditions'=>array('Customer.Customername'=>$this->request->data['Customer']['Customername'], 'Customer.password'=>$password)));
				if(!empty($Customer_data)){
					$this->Session->write('Auth.Customer', $Customer_data['Customer']);
					$this->Auth->_loggedIn = true;					
					//$this->redirect(array('controller' => 'Customers', 'action' => 'my_account'));
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
			$this->Customer->set($this->request->data['Customer']);
			$this->Customer->setValidation('register');
			if($this->Customer->validates()) 
			{
				exit;
			}else{
				$all_errors = $this->Customer->validationErrors;
				$errorMsgArr = array();
				$errorKey = array();
				$strValid = "";
				$count = 0;
				foreach($all_errors as $key=>$value){
					$errorKey[$key] = explode("_", $key);
					$errorKey[$key] = array_map('ucfirst', $errorKey[$key]);
					$errorKey[$key] = implode("", $errorKey[$key]);
					
					//$errorMsgArr["Customer".$errorKey[$key]] = $value[0];
					if($count>0){
						$strValid = $strValid."__"."Customer".$errorKey[$key]."=".$value[0];
					}else{
						$strValid = "Customer".$errorKey[$key]."=".$value[0];
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
		$this->Customer->bindModel(array('belongsTo'=>array('SubscriptionPlan'=>array('className'=>'SubscriptionPlan', 'foreignKey'=>'subscription_plan_id'))));

	
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
        $filters_without_status = $filters = array('Customer.role_id' => Configure::read('App.Role.Customer'));

        if ($defaultTab != 'All'){
            $filters[] = array('Customer.status' => array_search($defaultTab, Configure::read('Status')));
        }
        
		/**get offers details */
		$this->loadModel('Offer');
		$offer_details = $this->Offer->find('first',array('conditions'=>array('Offer.id'=>$id)));
		$offer_name = $offer_details['Offer']['name'];
		$offer_subject = $offer_details['Offer']['subject'];
		/**get offers details */
		$Customer_email = "";
		$Customer_alter_email = "";
		if (!empty($this->request->data)){
		
		    //pr($this->request->data);die;
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');

            App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			
            if (!empty($this->request->data['Customer']['email'])) {
                $email = Sanitize::escape($this->request->data['Customer']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            
            if (!empty($this->request->data['Customer']['alternate_email'])) {
                $alternate_email = Sanitize::escape($this->request->data['Customer']['alternate_email']);
                $this->Session->write('AdminSearch.alternate_email', $alternate_email);
            }
        	if (!empty($this->request->data['Customer']['subscription_plan_id'])) {
                $subscription_plan_id = Sanitize::escape($this->request->data['Customer']['subscription_plan_id']);
                $this->Session->write('AdminSearch.subscription_plan_id', $subscription_plan_id);
            }
			if (isset($this->request->data['Customer']['status']) && $this->request->data['Customer']['status'] != ''){
                $status = Sanitize::escape($this->request->data['Customer']['status']);
                $this->Session->write('AdminSearch.status', $status);
                $defaultTab = Configure::read('Status.' . $status);
            }
			$Customer_email = $this->request->data['Customer']['email'];
			$Customer_alter_email = $this->request->data['Customer']['alternate_email'];
			
		
        } else{
			$this->request->data = $this->Offer->findById($id);
			$this->request->data['Customer']['content'] = $this->request->data['Offer']['content'];
		}

        $search_flag = 0;
        $search_status = '';
        if ($this->Session->check('AdminSearch')) {
            $keywords = $this->Session->read('AdminSearch');

            foreach ($keywords as $key => $values) {
                if ($key == 'status') {
                    $search_status = $values;
                    $filters[] = array('Customer.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('Customer.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }		
            $search_flag = 1;
        }
        
        $this->set(compact('search_flag', 'defaultTab'));

        #pr($filters); die;

        $this->paginate = array(
            'Customer' => array(
                'limit' => $number_of_record,
                'order' => array('Customer.id' => 'DESC'),
                'conditions' => $filters
            )
        );
		/**get all Subscription Plans */
		$subscription_plans = $this->SubscriptionPlan->find('list',array('fields'=>array('plan_name')));  
        #pr($this->paginate);
        $data = $this->paginate('Customer');
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
                $temp[] = array('Customer.status' => 1);
                $active = $this->Customer->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp = $filters_without_status;
                $temp[] = array('Customer.status' => 0);
                $inactive = $this->Customer->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }
		$this->request->data = $this->Offer->findById($id);
		$this->request->data['Customer']['content'] = $this->request->data['Offer']['content'];
		
		//if(isset($this->request->data)){
		$this->request->data['Customer']['email'] = $Customer_email;
		$this->request->data['Customer']['alternate_email'] = $Customer_alter_email;
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
				if (empty($this->request->data['Customer']['content'])){
					$this->Session->setFlash(__('Message box did not allow blank.'), 'admin_flash_error');
					$this->redirect($this->referer());
				}
				$ids = array();
				foreach ($this->request->data['Customer']['id'] AS $value) {
					if ($value != 0) {
						$ids[] = $value;
					}
				}
				if (count($ids) == 0) {
					$this->Session->setFlash(__('No Customer selected.'), 'admin_flash_error');
					$this->redirect($this->referer());
				}
				//pr($ids);die;
				$allData = $this->Customer->find('all', array('fields'=>array('Customer.id','Customer.first_name','Customer.last_name','Customer.email'),'conditions'=>array('Customer.id'=>$ids)));
				$to = array();
				foreach($allData as $data){
					$to[] = $data['Customer']['email'];
				}
				$from    = Configure::read('App.AdminMail');
				$mail_message = '';
				$this->loadModel('Template');
				$offerMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'offer_mail')));
				$email_subject = $offerMail['Template']['subject'];
				$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
				$main_message = $this->request->data['Customer']['content'];
				$mail_message = str_replace(array('{NAME}','{SUBJECT}','{MAILMESSAGE}','{DISCOUNT}','{PROMOCODE}','{STARTDATE}','{ENDDATE}'), array($offer_name, $offer_subject, $main_message,$offer_discount,$offer_promo_code,$offer_start_date,$offer_end_date), $offerMail['Template']['content']);
				$template = 'default';
				$this->set('message', $mail_message);
				//pr($mail_message);die;
				parent::sendMail($to, $subject, $mail_message, $from, $template);
				$this->Session->setFlash('Email send successfully ', 'admin_flash_success');
				$this->redirect(array('controller' => 'Customers', 'action' => 'send_offer_email',$id));
				//-----send offer mail ----//
						
        } else{
			$this->request->data = $this->Offer->findById($id);
			$this->request->data['Customer']['content'] = $this->request->data['Offer']['content'];
		}
    //$this->redirect(array('controller' => 'Customers', 'action' => 'send_offer_email',$id));
    }
	
    
   
    
     /*
     * List all AFFILATE  in admin panel
     */
 public function admin_affilate($Customer_id, $defaultTab = 'All'){	
		//pr($this->params);die;
    	/** Load Template,SubscriptionPlan Model   */
        $this->loadModel('SubscriptionPlan');
        $this->loadModel('CustomerAffilate');
    	$this->Customer->id =$Customer_id;
		$Customer = $this->Customer->read(null, $Customer_id);
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
      
        if ($Customer_id != ''){
            $filters[] = array('CustomerAffilate.Customer_id' => $Customer_id);
        }

        $this->set(compact('search_flag', 'defaultTab'));

        #pr($filters); die;

        $this->paginate = array(
            'CustomerAffilate' => array(
                'limit' => $number_of_record,
                'order' => array('CustomerAffilate.id' => 'DESC'),
                'conditions' => $filters
            )
        );
	    if ($this->request->is('ajax')) {
	            $this->render('ajax/admin_index');
	        } else {
	            $active = 0;
	            $inactive = 0;
	            if ($search_status == '' || $search_status == Configure::read('App.Status.active')) {
	                $temp[] = array('Customer.status' => 1);
	                $active = $this->Customer->find('count', array('conditions' => $temp));
	            }
	            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
	                $temp[] = array('Customer.status' => 0);
	                $inactive = $this->Customer->find('count', array('conditions' => $temp));
	            }
	
	            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
	            $this->set(compact('tabs'));
	        }
    	$data = $this->paginate('CustomerAffilate');
        $this->set(compact('data','Customer'));
        $this->set('title_for_layout', __('Affilates by  '.$Customer['Customer']['first_name']." ".$Customer['Customer']['last_name'], true));
 }
    
 
 /**
	 * Send notifications to Customers if their payment is not received
	 */
	public function admin_notification($id = null) {
		$this->loadModel('CustomerAffilate');
		$this->CustomerAffilate->id = $id;
		if (!$this->CustomerAffilate->exists()) {
			throw new NotFoundException(__('Invalid Affilate'));
		}
		if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
			$blackHoleCallback = $this->Security->blackHoleCallback;
			$this->$blackHoleCallback();
		}
	
		$this->loadModel('Customer');
		$this->loadModel('Template');
		$this->CustomerAffilate->bindModel(array('belongsTo'=>array('Customer')),false);
		
		$Customer_id = $this->CustomerAffilate->field('Customer_id');
		$CustomerAffilate = $this->CustomerAffilate->find('first', array('conditions'=>array('CustomerAffilate.id'=>$id),'fields'=>array('CustomerAffilate.name','CustomerAffilate.amount','CustomerAffilate.referral_ip','CustomerAffilate.status', 'Customer.first_name','Customer.last_name', 'Customer.email',  'Customer.id' )));
		$to = $CustomerAffilate['Customer']['email'];
		
		$from    = Configure::read('App.AdminMail');
		$mail_message = '';
		$registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'affilate_notification')));
		$email_subject = $registrationMail['Template']['subject'];
		$subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
		$affilate_url = Configure::read('App.SiteUrl');

		$payment_link	=	'<a href="'.$affilate_url.'">Affilate Please Click Here</a>';
		$status = Configure::read('affilate_status.'.$CustomerAffilate['CustomerAffilate']['status']);
		$mail_message = str_replace(array('{NAME}','{STATUS}','{PAYMENT_LINK}'), array($CustomerAffilate['CustomerAffilate']['name'], $CustomerAffilate['CustomerAffilate']['status'],$payment_link,  $payment_link), $registrationMail['Template']['content']);
		$template = 'default';
		$this->set('message', $mail_message);
		parent::sendMail($to, $subject, $mail_message, $from, $template);
		$this->Session->setFlash(__('Affilate notification has been sent.'), 'admin_flash_success');
		$this->redirect(array('action' => 'affilate', $CustomerAffilate['Customer']['id'] ));
	
		
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


/*******************************************Customer Section Front***********************************************************/
     /*
     * List all Customers in user panel
     */

    public function customer_list($defaultTab = 'All'){ 
        $user_id = $this->Auth->User('id');
        $count = $this->Customer->find('count', array('conditions' => array('Customer.user_id' => $user_id)));
        // echo $count;die; 
        // die;
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
            $filters[] = array('Customer.status' => array_search($defaultTab, Configure::read('Status')));
        }

        $search_flag = 0;
        $search_status = '';
        if ($this->Session->check('CustomerSearch')) {
            $keywords = $this->Session->read('CustomerSearch');

            foreach ($keywords as $key => $values) {
                if ($key == 'status') {
                    $search_status = $values;
                    $filters[] = array('Customer.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('Customer.' . $key. ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }

                if ($key == 'alternate_email') {
                    $filters[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'subscription_plan_id') {
                    $filters[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('Customer.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }       
            $search_flag = 1;
        }
        $this->Customer->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
        
        $this->set(compact('search_flag', 'defaultTab'));

        $this->paginate = array(
            'Customer' => array(
                'order' => array('Customer.modified' => 'DESC'),
                'limit' => $count,
                'conditions' => $filters
             )
        );
        
        $data = $this->paginate('Customer');
       // echo "<pre>";
        //print_r($data);die;
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
                $temp[] = array('Customer.status' => 1);
                $active = $this->Customer->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')){
                $temp[] = array('Customer.status' => 0);
                $inactive = $this->Customer->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }

        $this->layout = "dashboard";
    }

    /*
     * delete existing Customer
     */
    public function delete($id = null){
        // echo "Hello form delete Customer";die;
        $Customer_id = $this->Customer->id = $id;

        if (!$this->Customer->exists()){
            throw new NotFoundException(__('Invalid Customer'));
        }
    
        if ($this->Customer->deleteAll(array('Customer.id'=>$id))) {

            $this->Session->setFlash(__('Customer deleted successfully'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Customer was not deleted', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

    /*
     * View existing Customer
     */

    public function view($id = null){
        $this->loadModel('Service');
        $this->Customer->id = $id;
        if(!$this->Customer->exists()){
            throw new NotFoundException(__('Invalid Customer'));
        }
        $this->Customer->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
       
        $this->Customer->recursive = 3;
        $data = $this->Customer->read(null, $id);
       // echo "<pre>";
       // print_r($data);die;
        $this->set('Customer', $data);
        $this->layout = "dashboard";
    }

    /*
     * edit existing Customer
     */
    public function edit($id = null) {
        $this->loadModel('Service');
        $this->layout = "dashboard";
        
        $this->Customer->id = $id;
        
        if (!$this->Customer->exists()) {
            throw new NotFoundException(__('Invalid Customer'));
        }
        
        if($this->request->is('post') || $this->request->is('put')) {
        
            if(!empty($this->request->data)) {
                 // print_r($this->request->data);die;
                    $this->Customer->set($this->request->data['Customer']);
                    $this->Customer->setValidation('admin');
                    if ($this->Customer->validates()) {
                        $this->Customer->create();
                       
                        if ($this->Customer->saveAll($this->request->data)) {
                    
                            $this->Session->setFlash(__('The Customer information has been updated successfully', true), 'admin_flash_success');
                            // print_r($this->request->data);die;
                            $this->redirect(array('action' => 'customer_list'));
                        } 
                        else 
                        {

                            $this->Session->setFlash(__('The Customer could not be saved. Please, try again.', true), 'admin_flash_error');
                        }
                    }
                    else 
                    {   
                        
                        $this->Session->setFlash(__('The Customer could not be saved. Please, try again.', true), 'admin_flash_error');
                    }
            }
        } 
        else 
        {
            $this->request->data = $this->Customer->read(null, $id);
        }
        $user_id = $this->Auth->User('id');
        $service_data = $this->Service->find('all',array('fields'=> array('Service.id', 'Service.name'),'conditions' => array('Service.user_id' => $user_id)));
        $service_list[0] = 'Select Service';
        foreach ($service_data as $key => $value) {
            $service_list[$value['Service']['id']] = $value['Service']['name'];
        }
        $this->set(compact('service_list'));
         // print_r($this->request->data);die;
    }

    /*
     * add new Customer
     */
    public function add(){
        $this->loadModel('Service');

        if ($this->request->is('post')) {

            if (!empty($this->request->data)) {
                /* unset user skill 0 position value if exist */

                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }

                $this->Customer->set($this->request->data['Customer']);
                $this->Customer->setValidation('admin');
                
                if ($this->Customer->saveAll($this->request->data)) {
                    $userId = $this->Customer->id;
                    
                    $this->Session->setFlash(__('Customer has been saved successfully'), 'admin_flash_success');
                    $this->redirect(array('action' => 'customer_list'));
                } else {
                    $this->Session->setFlash(__('The Customer could not be saved. Please, try again.'), 'admin_flash_error');
                }
            }
        }
        $user_id = $this->Auth->User('id');
        $service_data = $this->Service->find('all', array('conditions' => array('Service.user_id' => $user_id)));
        // $service_data = $this->Service->find('all',array('fields'=> array('Service.id', 'Service.name')));
        $service_list[0] = 'Select Service';
        foreach ($service_data as $key => $value) {
            $service_list[$value['Service']['id']] = $value['Service']['name'];
        }
        $this->set(compact('service_list'));
        $this->set('title_for_layout', __('Customers', true));
        $this->layout = "dashboard";
    }

     public function get_service_form($user_id = null, $service_id =null,  $customer_id =null,  $reservation_id =null, $date = null){
        $this->loadModel('User');
        $this->loadModel('Service');
        $this->loadModel('CustomerForm');
        $this->loadModel('CustomerHistory');
       

         // print_r($this->request->data);
         // die;
        $this->layout = "app_dashboard";  
        if($user_id != null && $service_id != null){
           
        }else{
            $user_id = $this->request->data['CustomerForm']['user_id'];
            $service_id = $this->request->data['CustomerForm']['service_id'];
            $customer_id = $this->request->data['CustomerForm']['customer_id'];
            $date = isset($this->request->data['date']) ? $this->request->data['date'] : '';
            if($date == '') {$date = date('Y-m-d');}
        }  
      
       $this->request->data = $this->User->read(null, $user_id);
        $formData = $this->CustomerForm->find('first', array('conditions'=>array('CustomerForm.user_id'=>$user_id,'CustomerForm.service_id'=>$service_id)));
        $formData["CustomerForm"]["customer_id"] = $customer_id;
        $formData["CustomerForm"]["reservation_id"] = $reservation_id;

        $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date, 'CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));
        if(isset($customerAnalysisData['CustomerHistory']['id']) && !empty($customerAnalysisData['CustomerHistory']['id'])){
                $formData['CustomerForm']['customer_history_id'] = $customerAnalysisData['CustomerHistory']['id'];
         }else{
            $customerHistory =array();
            $customerHistory['CustomerHistory']['user_id'] = $user_id;
            $customerHistory['CustomerHistory']['customer_id'] =  $customer_id;
            $customerHistory['CustomerHistory']['date'] =  $date;
            $this->CustomerHistory->saveAll($customerHistory);
             $formData['CustomerForm']['customer_history_id'] = $this->CustomerHistory->id;
        }
        $this->set(compact('formData'));
        // pr($formData);die;

    }


    public function save_service_form(){
        $this->loadModel('Service');
        $this->loadModel('CustomerForm');
        $this->loadModel('ServiceDetail');
        $this->loadModel('NoteService');
        // print_r($this->request->data);die;
          if ($this->request->is('post')) {
            unset($this->request->data['_Token']);
            if (!empty($this->request->data)) {
                $data = $this->request->data;

                if(isset($this->request->data['CustomerForm']['form_json_data']) && !empty($this->request->data['CustomerForm']['form_json_data'])){
                    $ServiceDetailJson = isset($this->request->data['CustomerForm']['form_json_data']) ? json_decode($this->request->data['CustomerForm']['form_json_data']) : '';
                    $jsonArr =array();
                    // echo "<pre>";
                    // $ServiceDetailJsons = (array)$ServiceDetailJson;
                    // pr($ServiceDetailJsons);die;
                    foreach ($ServiceDetailJson as $jsonKey => $jsonValue) {
                    	if(($jsonValue->type != 'header') && isset($jsonValue->name) && !empty($jsonValue->name)){
                    		$keylabel = str_replace('<br>', '', $jsonValue->label);
                            $jsonArr[$keylabel] = $jsonValue->name;
                    	}
                           
                    }
                     // pr($this->request->data);
                     // pr($jsonArr);die;
                    
                    $user_id = isset($this->request->data['CustomerForm']['user_id']) ? $this->request->data['CustomerForm']['user_id'] : '';
                    $service_id =isset($this->request->data['CustomerForm']['service_id']) ? $this->request->data['CustomerForm']['service_id'] : '';
                    $customer_history_id =isset($this->request->data['CustomerForm']['customer_history_id']) ? $this->request->data['CustomerForm']['customer_history_id'] : '';
                    $reservation_id =isset($this->request->data['CustomerForm']['reservation_id']) ? $this->request->data['CustomerForm']['reservation_id'] : '';
                    $customer_id = isset($this->request->data['CustomerForm']['customer_id']) ? $this->request->data['CustomerForm']['customer_id'] : '';    
                    $noteService['NoteService']['user_id'] = $user_id ;
                    $noteService['NoteService']['service_id'] = $service_id ;
                    $noteService['NoteService']['customer_id'] = $customer_id ;
                    $noteService['NoteService']['service_name'] = $this->get_service_name($service_id) ;
                    $noteService['NoteService']['customer_history_id'] = $customer_history_id ;
                    $noteService['NoteService']['status'] = Configure::read('App.Status.active') ;
                    if($this->NoteService->saveAll($noteService)){
                    	$note_service_id = $this->NoteService->id;
                    }else{
                    	$note_service_id = '0';
                    }
                    $i= 0;
                    $ServiceDetail = array();
                    foreach ($data as $reqKey => $reqValue) {
                       if($reqKey !='CustomerForm'){
                            $ServiceDetail[$i]['ServiceDetail']['f_name'] = $reqKey;
                            $ServiceDetail[$i]['ServiceDetail']['f_value'] = $reqValue;
                            $checkArr['key'] = $reqKey;
                            $result=array_intersect($jsonArr,$checkArr);
                            if(!empty($result)){
                                foreach ($result as $key => $value) {
                                   $key = str_replace('<br>', '', $key);
                                   $ServiceDetail[$i]['ServiceDetail']['f_label'] = trim($key);
                                }
                            }
                            $ServiceDetail[$i]['ServiceDetail']['user_id'] = $user_id ;
                            $ServiceDetail[$i]['ServiceDetail']['service_id'] = $service_id ;
                            $ServiceDetail[$i]['ServiceDetail']['customer_id'] = $customer_id ;
                            $ServiceDetail[$i]['ServiceDetail']['form_date'] = date('Y-m-d') ;
                            $ServiceDetail[$i]['ServiceDetail']['customer_history_id'] =  $customer_history_id;
                            $ServiceDetail[$i]['ServiceDetail']['reservation_id'] =  $reservation_id;
                            $ServiceDetail[$i]['ServiceDetail']['note_service_id'] =  $note_service_id;
                            $i++;
           
                       }
                    }
                    // pr($ServiceDetail);die;
                    if(!empty($ServiceDetail)){
                        if ($this->ServiceDetail->saveAll($ServiceDetail)) {
                            date_default_timezone_set("Asia/Tokyo");
                            $customerData['Customer']['id'] = $customer_id;
                            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                            $this->Customer->saveAll($customerData);
                            
                            $this->Session->setFlash(__('Customer service  information has been saved successfully'), 'flash_success');
                            $this->redirect(array('controller' => 'employees', 'action' => 'customer_list'));
                        } else {
                            $this->Session->setFlash(__('Customer service  information could not be saved. Please, try again.'), 'flash_error');
                        }
                    }else{
                        $this->Session->setFlash(__('Customer service  information has been saved successfully'), 'flash_success');
                        $this->redirect(array('controller' => 'employees', 'action' => 'customer_list'));
                    }    
                }else{
                    $this->Session->setFlash(__('Customer service  information could not be saved.'), 'flash_error');
                }    
            }
        }else{
            $this->Session->setFlash(__('Please Enter Valid Values'), 'flash_error');
            $this->redirect('get_service_form');
        }
    }

    public function get_service_name($id = ''){
        $this->loadModel("Service");
        if(!empty($id)){
            $data = $this->Service->find('first',array('conditions'=> array('Service.id'=>$id )));
            if(isset($data['Service']['name'])){
                $employee_name = $data['Service']['name'];
            }else{
                $employee_name = 'No Service';
            }
            return  $employee_name;
        }else{
            return 'No Service';
        }   
    }

    public function view_info($service_id = null){
        // print_r( $service_id );die;
         // $this->loadModel('Service');
         $this->loadModel('ServiceDetail');
         $user_id = $this->Auth->User('id');
        // print_r($user_id);die;
        $count = $this->ServiceDetail->find('count', array('conditions' => array('ServiceDetail.user_id' => $user_id, 'ServiceDetail.service_id' => $service_id )));
        // echo $count;die; 
        
       $filters = array('ServiceDetail.user_id' => $user_id, 'ServiceDetail.service_id' => $service_id);
        

        $this->paginate = array(
            'ServiceDetail' => array(
                'order' => array('ServiceDetail.id' => 'ASC'),
                'limit' => $count,
                'conditions' => $filters
             )
        );

         // echo "<pre>";
        $test_data = $this->paginate('ServiceDetail');
         // print_r($test_data);die;
        $servicesData = array();
         $i = -1;
         $c = 0;
        foreach ($test_data as $key => $value) {
            // print_r($value);
            if(isset($value['ServiceDetail']['f_label']) && !empty($value['ServiceDetail']['f_label']) && isset($value['ServiceDetail']['f_value']) && !empty($value['ServiceDetail']['f_value'])){
                $customer_id = $value['ServiceDetail']['customer_id'];
                if(isset($c) && ($customer_id != $c)){
                    $i++;
                    $c = $customer_id;

                }

                $servicesData[$i]['Data'][$value['ServiceDetail']['f_label']] = $value['ServiceDetail']['f_value'];
                
            }
           
            
        }
        // pr($servicesData);die;
// die;
        
        $data = $servicesData;
      // echo "<pre>";
       // print_r($Services_data);die;

        
        $this->set(compact('data'));
        $this->set('title_for_layout', __('Service information', true));

        $this->layout = "dashboard";
    }

}