<?php
/*********************************************************************************
1. * Copyright 2017, All rights reserved, For internal use only
*
* FILE:    WebServicesv3Controller.php
* PROJECT: vocalist.com.sg
* MODULE:  Native app websservices(Android and iPhone)
* AUTHOR:  Mahendra Tripathi
* DATE:    19/06/2018

* Notes:
*

* REVISION HISTORY
* Date: By: Description:
*
*****************************************************************************/
class WebServicesv6Controller extends AppController{
    const AppApiHeader = 'ApiKey';
    const AppUserTokenHeader = 'Oauth-Token';
    public $AppToken   = null;
    public $UserId     = null;
    public $CustomerId = null;
    
    var $name = 'WebsService';
    var $components = array('Upload');
    var $helpers = array('Time');

    
    
   /************************************************************************************************************************************
     * NAME: beforeFilter
     * Description: Before Filter .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************************************************************************/ 
    
    
    public function beforeFilter(){
        parent::beforeFilter();
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("RecordData");
        $this->Auth->allow('*');
        $this->RequestHandler->addInputType('json', array('json_decode', true));
     
        
    }

    /************************************************************************************************************************************
     * 1 .NAME: login
     * Description: Login a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
      Example : { "email":"mahen@mailinator.com", "password":"123456" }
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************************************************************************/
    
    function login($test_data = null){
       
        
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    
        /*      
          $data = '{"email":"mahen.zed123@gmail.com", "password" : "123456"}';
         */
        //echo $data;
        $decoded = json_decode($data, true);
        $responseArr =array();
        $email = $decoded['email'];
        $password = $decoded['password'];
        if(!empty($email) && !empty($password)){
            $email = strtolower($email);
                /** load model message*/
                $this->loadModel("User");
                $data = $this->User->find('first',array('conditions'=>
                                                        array(
                                                              'User.email'=>$email,
                                                              'User.password'=>Security::hash($password, null, true),
                                                              'User.status'=>1
                                                              )
                                                        ));
                
            if(isset($data['User']['id']) && !empty($data['User']['id'])){
                $this->User->id = $data['User']['id'];
                $this->loadModel('Employee');
                 $employee_pin_number = isset($data['User']['employee_pin_number']) ? $data['User']['employee_pin_number'] : '';
                 $customer_pin_number = isset($data['User']['customer_pin_number']) ? $data['User']['customer_pin_number'] : '';
                 $user_emp_code = isset($data['User']['user_emp_code']) ? $data['User']['user_emp_code'] : '';

                 if(isset($user_emp_code) && !empty($user_emp_code) && $user_emp_code!='null'){
                 	$userEmpId = $this->Employee->find('first',array('conditions'=>  array('Employee.emp_code'=>$user_emp_code),'fields'=>array('Employee.id','Employee.name','Employee.image')));
                 	$employee_id = isset($userEmpId['Employee']['id']) ? $userEmpId['Employee']['id'] : ''; 
                 	$employee_name = isset($userEmpId['Employee']['name']) ? $userEmpId['Employee']['name'] : ''; 
                 	$employee_image = isset($userEmpId['Employee']['image']) ? $userEmpId['Employee']['image'] : ''; 

                 }else{
                 	$employee_id = '';
                 	$employee_name = isset($data['User']['name']) ? $data['User']['name'] : ''; 
                 	$employee_image = ''; 
                 }	

                if(isset($data['User']['company_name']) && !empty($data['User']['company_name'])){
                    $responseArr = array('user_id' => $data['User']['id'], 'msg1' => 'Login Sccuessfully.', 'msg' => 'ログインが成功しました。', 'employee_id'=>$employee_id,  'employee_name'=>$employee_name,  'employee_image'=>$employee_image, 'employee_pin_number'=>$employee_pin_number,  'customer_pin_number'=>$customer_pin_number, 'is_admin' => strval($data['User']['is_admin']), 'is_form' => '2', 'status' => 'success' );
                }else{
                    $responseArr = array('user_id' => $data['User']['id'], 'msg1' => 'Login Sccuessfully.', 'msg' => 'ログインが成功しました。', 'employee_id'=>$employee_id, 'employee_name'=>$employee_name,  'employee_image'=>$employee_image, 'employee_pin_number'=>$employee_pin_number,  'customer_pin_number'=>$customer_pin_number, 'is_admin' => strval($data['User']['is_admin']), 'is_form' => '1', 'status' => 'success' );
                }
                $jsonEncode = json_encode($responseArr);
             
            }else{
                $data = $this->User->find('first',array('conditions'=>  array('User.email'=>$email,'User.password'=>Security::hash($password, null, true),),'fields'=>array('User.id','User.email')));
                if(isset($data['User']['id']) && !empty($data['User']['id'])){
                    $responseArr = array('msg' => 'あなたのメールアドレスからアカウントを有効にしてください。', 'msg1' => 'Please active account from your email.', 'status' => 'error' );
                    $jsonEncode = json_encode($responseArr);
                }else{

                    $responseArr = array('msg1' => 'Email does not exist.', 'msg' => '電子メールは存在しません。', 'status' => 'error' );
                    $jsonEncode = json_encode($responseArr);
                }
            }
        }else{

            $responseArr = array('msg' => 'メールアドレスとパスワードを入力してください。', 'msg1' => 'Please Enter email and password.', 'status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }   
        $log = $this->User->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "login";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
        

    }
    
    
    /****************************************************************************************************************************************
     * 2. NAME: signup
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :
     
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function signup($test_data = null){
        
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        
        $this->loadModel('User');
        $responseArr = array();
        $email = strtolower($decoded['email']);
        $userExist = $this->User->find('first', array('conditions'=>array('User.email'=>$email)));
        
        if($userExist){
            $responseArr = array('msg' => 'このメールは既に存在します。', 'msg1' => 'This email is already exist.',  'status' => 'error' );
            $jsonEncode = json_encode($responseArr);
            return $jsonEncode;
        }
        
        $user['User']['role_id'] = 2;
        $user['User']['is_admin'] = 0;
        $user['User']['email'] = isset($decoded['email']) ? strtolower($decoded['email']) : '';
        $password = isset($decoded['password']) ? $decoded['password'] : '';
        $user['User']['password'] = Security::hash($password, null, true);
        $user['User']['name'] = isset($decoded['name']) ? $decoded['name'] : '';
        $user['User']['company_name'] = isset($decoded['company_name']) ? $decoded['company_name'] : '';
        $user['User']['salon_name'] = isset($decoded['salon_name']) ? $decoded['salon_name'] : '';
        $user['User']['zip_code'] = isset($decoded['zip_code']) ? $decoded['zip_code'] : '';
        $user['User']['city'] = isset($decoded['city']) ? $decoded['city'] : '';
        $user['User']['address1'] = isset($decoded['address1']) ? $decoded['address1'] : '';
        $user['User']['address2'] = isset($decoded['address2']) ? $decoded['address2'] : '';
        $user['User']['prefecture'] = isset($decoded['prefecture']) ? $decoded['prefecture'] : '';
        $user['User']['tel'] = isset($decoded['tel']) ? $decoded['tel'] : '';
        $user['User']['website'] = isset($decoded['website']) ? $decoded['website'] : '';
        $user['User']['employee_number'] = isset($decoded['employee_number']) ? $decoded['employee_number'] : '';
        $user['User']['advertisement'] = isset($decoded['advertisement']) ? $decoded['advertisement'] : '';
        $user['User']['avr_customer'] = isset($decoded['avr_customer']) ? $decoded['avr_customer'] : '';
        $user['User']['has_branch'] = isset($decoded['has_branch']) ? $decoded['has_branch'] : '';
        $user['User']['employee_pin_number'] = isset($decoded['employee_pin_number']) ? $decoded['employee_pin_number'] : '';
        $user['User']['customer_pin_number'] = isset($decoded['customer_pin_number']) ? $decoded['customer_pin_number'] : '';
        


        $user['User']['status'] =0;
        $enter_password = $password;
        $verification_code = substr(md5(uniqid()), 0, 20);
        $user['User']['verification_code'] = $verification_code;
        
        if($this->User->saveAll($user)){
            /****************** EMAIL NOTIFICATION MESSAGE ********************/
            $to      = $this->User->email;
            $from    = Configure::read('App.AdminMail');
            $mail_message = '';
            
            $this->loadModel('Template');
            $registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_registration')));
            $email_subject = $registrationMail['Template']['subject'];
            $subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
            $activationCode = $user['User']['verification_code'];
            $activation_url = Router::url(array(
                                                'controller' => 'users',
                                                'action' => 'email_confirm',
                                                base64_encode($user['User']['email']),
                                                $verification_code,
                                                ), true);

            
            $activation_link    =   '<a href="'.$activation_url.'">Click Here</a>';
            $mail_message = str_replace(array('{NAME}', '{EMAIL}','{PASSWORD}','{ACTIVATION_LINK}', '{activation_code}',  '{SITE}'), array($user['User']['name'], $email, $enter_password,$activation_link, $activationCode, Configure::read('App.SITENAME')), $registrationMail['Template']['content']);
            $template = 'default';
            $this->set('message', $mail_message);

            $from = 'JTSBoard  <admin@jtsboard.com>';

            $headers = "From: " .($from) . "\r\n";
            $headers .= "Reply-To: ".($from) . "\r\n";
            $headers .= "Return-Path: ".($from) . "\r\n";;
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

            @mail($email,$subject,$mail_message,$headers);

/*

            if (mail($email,$subject,$mail_message,$headers)) {
                echo "sent";
            } else {
                echo "failed";
            }
            die;
            */


            //parent::sendMail($email, $subject, $mail_message, $from, $template);
            /****************** EMAIL NOTIFICATION MESSAGE ********************/
            
             
            $user_id = $this->User->id;
            $responseArr = array('user_id' => $user_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->User->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "signup";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

    
    /**************************************************************************
     * 3.  NAME: user_profile
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"user_id" : "205"}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function user_profile($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    
        $decoded = json_decode($data, true); 
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $this->loadModel("User");
        if(!empty($user_id)){
            $data = $this->User->find('first',array('conditions'=> array('User.id'=>$user_id)));
            if(!empty($data)){
                

                if((empty($data['User']['employee_pin_number']) || ($data['User']['employee_pin_number']=='NULL') || ($data['User']['employee_pin_number']=='null') )  ){
                    $data['User']['employee_pin_number'] = '';
                }
                if((empty($data['User']['customer_pin_number']) ||($data['User']['customer_pin_number']=='NULL') || ($data['User']['customer_pin_number']=='null') ) ){
                    $data['User']['customer_pin_number'] = '';
                }
                $jsonEncode = json_encode($data);
            }else {
                $result['status'] = 'error';
                $jsonEncode = json_encode($result);
            }
            
        }else{

            $result['status'] = 'error';
            $jsonEncode = json_encode($result);
        }
        $log = $this->User->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "user_profile";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

    /****************************************************************************************************************************************
     * 2. NAME: edit_profile
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :
     
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function edit_profile($test_data = null){
        
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        
        $this->loadModel('User');
        $responseArr = array();
        
        $user['User']['id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $user['User']['name'] = 
       // $user['User']['company_name'] = isset($decoded['company_name']) ? $decoded['company_name'] : '';
        $user['User']['salon_name'] = isset($decoded['salon_name']) ? $decoded['salon_name'] : '';
        $user['User']['zip_code'] = isset($decoded['zip_code']) ? $decoded['zip_code'] : '';
        $user['User']['city'] = isset($decoded['city']) ? $decoded['city'] : '';
        $user['User']['address1'] = isset($decoded['address1']) ? $decoded['address1'] : '';
        $user['User']['address2'] = isset($decoded['address2']) ? $decoded['address2'] : '';
        $user['User']['prefecture'] = isset($decoded['prefecture']) ? $decoded['prefecture'] : '';
        $user['User']['tel'] = isset($decoded['tel']) ? $decoded['tel'] : '';
        $user['User']['website'] = isset($decoded['website']) ? $decoded['website'] : '';
        $user['User']['employee_number'] = isset($decoded['employee_number']) ? $decoded['employee_number'] : '';
        $user['User']['advertisement'] = isset($decoded['advertisement']) ? $decoded['advertisement'] : '';
        $user['User']['avr_customer'] = isset($decoded['avr_customer']) ? $decoded['avr_customer'] : '';
       // $user['User']['has_branch'] = isset($decoded['has_branch']) ? $decoded['has_branch'] : '';
        $user['User']['employee_pin_number'] = isset($decoded['employee_pin_number']) ? $decoded['employee_pin_number'] : '';
        $user['User']['customer_pin_number'] = isset($decoded['customer_pin_number']) ? $decoded['customer_pin_number'] : '';
        $user['User']['month_start_date'] = isset($decoded['month_start_date']) ? $decoded['month_start_date'] : '';
        $user['User']['weekend'] = isset($decoded['weekend']) ? $decoded['weekend'] : '';

         //print_r($user);die;
        if($this->User->saveAll($user)){
              
            $user_id = $this->User->id;
            $responseArr = array('user_id' => $user_id, 'status' => 'success', 'msg' => 'ユーザープロフィールは正常に編集されます。', 'msg1' => 'User profile edit successfully.' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->User->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "edit_profile";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }




    /**************************************************************************
     * NAME: customer_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function customer_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Salon");
        $this->loadModel("Customer");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $i=0;
        if(!empty($user_id)){
            $this->Customer->bindModel(array('belongsTo' => array('Salon')));
            $data = $this->Customer->find('all',array('conditions'=>
                                                    array( 'Customer.user_id'=>$user_id),
                                                    'order' => array('Customer.modified' => 'DESC')
                                                    ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'ユーザーは存在しません。', 'msg1'=> 'User does not exist.'));
            }else{

                if(!empty($data)){
                    
                    foreach ($data as $key => $value) {

                        $customerData['Customer'][$i] = $value['Customer'];
                        $customerData['Customer'][$i]['salon_name'] = $value['Salon']['salon_name'];
                        $customerData['Customer'][$i]['service_name'] = $this->get_service_name($value['Customer']['service_id']);
                        $i++;
                    }

                }else{
                	$customerData[$i]['Customer']['msg'] = 'レコードが見つかりませんでした。';
                    $customerData[$i]['Customer']['msg1'] = 'No Record Found.';
                    $customerData[$i]['Customer']['status'] = 'error';
                }
                $jsonEncode = json_encode($customerData);
            }
        }else{
            $customerData[$i]['Customer']['msg1'] = 'お客様は存在しません。';
             $customerData[$i]['Customer']['msg'] = 'Customer does not exist.';
            $customerData[$i]['Customer']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "customer_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

    /**************************************************************************
     * NAME: customer_service_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function customer_service_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Service");
        $this->loadModel("Customer");


        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $i=0;
        $serviceData = array();
        if(!empty($customer_id)){
            $this->Customer->bindModel(array('hasMany' => array('Esthe','Eyelush', 'Body', 'HairRemoval', 'PhotoFacial' )));
            $data = $this->Customer->find('first',array('conditions'=> array( 'Customer.id'=>$customer_id),'order' => array('Customer.id' => 'DESC') ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg1'=> 'お客様は存在しません。', 'msg'=> 'Customer does not exist.'));
            }else{
                
                if(isset($data['Esthe']) && !empty($data['Esthe'])){
                    $estheService = $this->Service->find('first',array('conditions'=> array( 'Service.id'=>2)));
                    foreach ($data['Esthe'] as $esthekey => $estheValue) {
                        $serviceData['Service'][$i]['service_id'] = "2";
                        $serviceData['Service'][$i]['customer_service_id'] = $estheService['Service']['name'];
                        $serviceData['Service'][$i]['service_name'] = $this->get_service_name("2");
                        $serviceData['Service'][$i]['date'] = $estheValue['created'];
                        $i++;
                    }
                }

                if(isset($data['Eyelush']) && !empty($data['Eyelush'])){
                    $eyelushService = $this->Service->find('first',array('conditions'=> array( 'Service.id'=>3)));
                    foreach ($data['Eyelush'] as $eyelushkey => $eyelushvalue) {
                        $serviceData['Service'][$i]['service_id'] = "3";
                        $serviceData['Service'][$i]['customer_service_id'] = $eyelushService['Service']['name'];
                        $serviceData['Service'][$i]['service_name'] = $this->get_service_name("3");
                        $serviceData['Service'][$i]['date'] = $eyelushvalue['created'];
                        $i++;
                    }
                }


                if(isset($data['Body']) && !empty($data['Body'])){
                    $bodyService = $this->Service->find('first',array('conditions'=> array( 'Service.id'=>4)));
                    foreach ($data['Body'] as $bodykey => $bodyvalue) {
                        $serviceData['Service'][$i]['service_id'] = "4";
                        $serviceData['Service'][$i]['customer_service_id'] = $bodyvalue['id'];
                        $serviceData['Service'][$i]['service_name'] = $this->get_service_name("4");
                        $serviceData['Service'][$i]['date'] = $bodyvalue['created'];
                        $i++;
                    }
                }

                if(isset($data['HairRemoval']) && !empty($data['HairRemoval'])){
                    $hairRemovalService = $this->Service->find('first',array('conditions'=> array( 'Service.id'=>5)));
                    foreach ($data['HairRemoval'] as $hairRemovalkey => $hairRemovalvalue) {
                        $serviceData['Service'][$i]['service_id'] = "5";
                        $serviceData['Service'][$i]['customer_service_id'] = $hairRemovalvalue['id'];
                        $serviceData['Service'][$i]['service_name'] = $this->get_service_name("5");
                        $serviceData['Service'][$i]['date'] = $hairRemovalvalue['created'];
                        $i++;
                    }
                }

                if(isset($data['PhotoFacial']) && !empty($data['PhotoFacial'])){
                     $photoFacialService = $this->Service->find('first',array('conditions'=> array( 'Service.id'=>6)));
                    foreach ($data['PhotoFacial'] as $photoFacialkey => $photoFacialvalue) {
                         $serviceData['Service'][$i]['service_id'] = "6";
                        $serviceData['Service'][$i]['customer_service_id'] = $photoFacialvalue['id'];
                        $serviceData['Service'][$i]['service_name'] = $this->get_service_name("6");
                        $serviceData['Service'][$i]['date'] = $photoFacialvalue['created'];
                        $i++;
                    }
                }
                $jsonEncode = json_encode($serviceData);
            }
        }else{
            $customerData[$i]['Customer']['msg'] = 'Please enter customer id.';
            $customerData[$i]['Customer']['msg1'] = 'Please enter customer id.';
            $customerData[$i]['Customer']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "customer_service_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }



   
/**************************************************************************
     * NAME: Service Detail
     * Description: Match the apiToken sent by Mobile with existing ApiToken. This function check and authenticate .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : { }
     * Returns: send mail with password return true
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/


     function service_detail($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        
        $this->loadModel("Customer");
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $customer_service_id = isset($decoded['customer_service_id']) ? $decoded['customer_service_id'] : '';
        $service_id = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        if(!empty($customer_id) &&  !empty($customer_service_id) && !empty($service_id) ){
            $data = $this->Customer->find('first',array('conditions'=>  array( 'Customer.id'=>$customer_id )));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'Customer does not exist.'));
            }else{
               
               
                if($service_id =='2'){
                    $this->loadModel("Esthe");
                    $service = $this->Esthe->find('first',array('conditions'=>  array('Esthe.id'=>$customer_service_id)));
                    if(!empty($service['Esthe']['how_to_maintain'])){
                      $service['Esthe']['how_to_maintain'] = json_decode($service['Esthe']['how_to_maintain']);
                    }
                    $allData['Service'] =  $service['Esthe'];
                }elseif($service_id == '3'){
                    $this->loadModel("Eyelush");
                    $service = $this->Eyelush->find('first',array('conditions'=>  array('Eyelush.id'=>$customer_service_id)));
                    $allData['Service'] =  $service['Eyelush'];
                }elseif($service_id == '4' ){
                    $this->loadModel("Body");
                    $service = $this->Body->find('first',array('conditions'=>  array('Body.id'=>$customer_service_id)));
                    $allData['Service'] =  $service['Body'];
                }elseif ($service_id == '5') {
                    $this->loadModel("HairRemoval");
                    $service = $this->HairRemoval->find('first',array('conditions'=>  array('HairRemoval.id'=>$customer_service_id)));
                    $allData['Service'] =  $service['HairRemoval'];
                }elseif ($service_id == '6') {
                    $this->loadModel("PhotoFacial");
                    $service = $this->PhotoFacial->find('first',array('conditions'=>  array('PhotoFacial.id'=>$customer_service_id)));
                    $allData['Service'] =  $service['PhotoFacial'];
                }
                    $allData['Service']['user_id'] =  $data['Customer']['user_id'];
                    $allData['Service']['salon_id'] =  $data['Customer']['salon_id'];
                    $allData['Service']['service_id'] =  $data['Customer']['service_id'];
                    $allData['Service']['name'] =  $data['Customer']['name'];
                    $allData['Service']['kana_first_name'] =  $data['Customer']['kana_first_name'];
                    $allData['Service']['kana_last_name'] =  $data['Customer']['kana_last_name'];
                    $allData['Service']['first_name'] =  $data['Customer']['first_name'];
                    $allData['Service']['last_name'] =  $data['Customer']['last_name'];
                    $allData['Service']['kana'] =  $data['Customer']['kana'];
                    $allData['Service']['email'] =  $data['Customer']['email'];
                    $allData['Service']['gender'] =  $data['Customer']['gender'];
                    $allData['Service']['dob'] =  $data['Customer']['dob'];
                    $allData['Service']['age'] =  $data['Customer']['age'];
                    $allData['Service']['tel'] =  $data['Customer']['tel'];
                    $allData['Service']['zip_code'] =  $data['Customer']['zip_code'];
                    $allData['Service']['prefecture'] =  $data['Customer']['prefecture'];
                    $allData['Service']['city'] =  $data['Customer']['city'];
                    $allData['Service']['address1'] =  $data['Customer']['address1'];
                    $allData['Service']['address2'] =  $data['Customer']['address2'];
                    $allData['Service']['job'] =  $data['Customer']['job'];
                    $allData['Service']['subscription_of_news'] =  $data['Customer']['subscription_of_news'];
                    $allData['Service']['know_about_company'] =  $data['Customer']['know_about_company'];
                    $allData['Service']['how_did_you_come'] =  $data['Customer']['how_did_you_come'];

                $jsonEncode =  json_encode($allData);
            }
        }else{
        	$customerData[$i]['Customer']['msg'] = 'お客様は存在しません。';
            $customerData[$i]['Customer']['msg1'] = 'Please add customer id.';
            $customerData[$i]['Customer']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "service_detail";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }

    /**************************************************************************
     * NAME: customer_history
     * Description: Match the apiToken sent by Mobile with existing ApiToken. This function check and authenticate .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : { }
     * Returns: send mail with password return true
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/


     function customer_history($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Salon");
        $this->loadModel("Customer");
        $tel = isset($decoded['tel']) ? $decoded['tel'] : '';
        if(!empty($tel)){
            $this->Customer->bindModel(array('belongsTo' => array('Salon')));
            $data = $this->Customer->find('all',array('conditions'=>
                                                    array(
                                                          'Customer.tel'=>$tel,
                                                          ),
                                                    'order' => array('Customer.id' => 'DESC'),
                                                    ));
            if(!$data){
                $jsonEncod = json_encode(array('status'=>'success', 'msg'=> $tel.' 最初に顧客が来る。', 'msg1'=> $tel.' Customer coming on first time'));
            }else{

                if(!empty($data)){
                    $i=0;
                    $customerData['Customer']['count'] = count($data);
                    foreach ($data as $key => $value) {

                        $customerData['Customer']['count'] = count($data);
                        $customerData[$i]['Customer']['salon_name'] = $value['Salon']['salon_name'];
                        $customerData[$i]['Customer']['service_name'] = $this->get_service_name($value['Customer']['service_id']);
                        $i++;
                    }

                }else{
                    $customerData[$i]['Customer']['msg'] = 'No Record Found.';
                    $customerData[$i]['Customer']['status'] = 'error';
                }
                $jsonEncod = json_encode($customerData);
            }
        }else{
            $customerData[$i]['Customer']['msg1'] = 'Please add user id.';
            $customerData[$i]['Customer']['msg'] = 'レコードが見つかりませんでした。';
            $customerData[$i]['Customer']['status'] = 'error';
            $jsonEncod = json_encode($customerData);
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "customer_history";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }



    
    /**************************************************************************
     * NAME: Forgot Password
     * Description: Match the apiToken sent by Mobile with existing ApiToken. This function check and authenticate .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : { "email":"john@mailinator.com", "device_token":"FSSHSSG524414GSFFSF5525","device_type":"ios"}
     * Returns: send mail with password return true
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function forgot_password($email = null){
        $email = $_POST['email'];
        $email = strtolower($email);
        $this->loadModel('User');
        $userDetail = $this->User->find('first',array('conditions'=>
                                                      array(
                                                            'User.email'=>$email,'User.status' => 1,'User.role_id' => 2
                                                            )
                                                      ));
        if(!empty($userDetail)){
            $this->User->id =   $userDetail['User']['id'];
            $verification_code = substr(md5(uniqid()), 0, 20);
            if($this->User->saveField('verification_code' , $verification_code ))
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
                $activation_link    =   ' <a href="'.$activation_url.'" target="_blank" shape="rect">Change Password</a>';
                
                $mail_message = str_replace(array('{EMAIL}', "{ACTIVATION_LINK}"), array($userDetail['User']['email'], $activation_link), $forgetPassMail['Template']['content']);
                
                $to = $userDetail['User']['email'];
                $from = Configure::read('App.AdminMail');
                
                $template='default';
                $this->set('message', $mail_message);
                $template='default';
                
                if(parent::sendMail($to, $subject, $mail_message, $from, $template)){
                    $jsonEncode = json_encode('success');
                    return $jsonEncode;
                
                }else{
                
                    $jsonEncode = json_encode('error');
                    return $jsonEncode;
                }
            }else{
                $jsonEncode = json_encode('error');
                return $jsonEncode;
            }
                
            
            
        }
        else
        {
            $jsonEncode = json_encode('error');
            return $jsonEncode;
        }
        
    }


    
    /**************************************************************************
     * NAME: service_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function service_list(){
        
        $this->loadModel("Service");
        $data = $this->Service->find('all');
        $jsonEncode = json_encode($data);
        $log = $this->Service->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "signup";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


    /**************************************************************************
     * NAME: role_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function role_list(){
        
        $this->loadModel("Role");
        $data = $this->Role->find('all');
        $roleData = array();
        if(isset($data[0]['Role']) && !empty($data[0]['Role']) ){
            foreach ($data as $key => $value) {
               $roleData['Role'][$key] = $value['Role'];
            }
        }
        $jsonEncode = json_encode($roleData);
        $log = $this->Role->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "role_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }
    

    /**************************************************************************
     * NAME: add_service
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function add_service(){
        
        $this->loadModel("ServiceDetail");
        $this->loadModel("RecordData");
        $data = file_get_contents("php://input");
        
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }
        $decoded = json_decode($data, true);
        $service['ServiceDetail']['user_id'] = isset($decoded['user_id']) ? strtolower($decoded['user_id']) : '';
        $service['ServiceDetail']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $service['ServiceDetail']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $service['ServiceDetail']['medicine'] = isset($decoded['medicine']) ? $decoded['medicine'] : '';
        $service['ServiceDetail']['hospital'] = isset($decoded['hospital']) ? $decoded['hospital'] : '';
        $service['ServiceDetail']['medical_history'] = isset($decoded['medical_history']) ? $decoded['medical_history'] : '';
        $service['ServiceDetail']['period'] = isset($decoded['period']) ? $decoded['period'] : '';
        $service['ServiceDetail']['sleep_start_time'] = isset($decoded['sleep_start_time']) ? $decoded['sleep_start_time'] : '';
        $service['ServiceDetail']['sleep_time_avg'] = isset($decoded['sleep_time_avg']) ? $decoded['sleep_time_avg'] : '';
        $service['ServiceDetail']['concern'] = isset($decoded['concern']) ? $decoded['concern'] : '';
        $service['ServiceDetail']['concern_extra'] = isset($decoded['concern_extra']) ? $decoded['concern_extra'] : '';
        $service['ServiceDetail']['concern_date'] = isset($decoded['concern_date']) ? $decoded['concern_date'] : '';
        $service['ServiceDetail']['itchy'] = isset($decoded['itchy']) ? $decoded['itchy'] : '';
        $service['ServiceDetail']['cosmetic_name'] = isset($decoded['cosmetic_name']) ? $decoded['cosmetic_name'] : '';
        $service['ServiceDetail']['how_to_maintain'] = isset($decoded['how_to_maintain']) ? $decoded['how_to_maintain'] : '';
        $service['ServiceDetail']['peeling'] = isset($decoded['peeling']) ? $decoded['peeling'] : '';
        $service['ServiceDetail']['treatment'] = isset($decoded['treatment']) ? $decoded['treatment'] : '';
        $service['ServiceDetail']['esthe_experience'] = isset($decoded['esthe_experience']) ? $decoded['esthe_experience'] : '';
        $service['ServiceDetail']['surgery'] = isset($decoded['surgery']) ? $decoded['surgery'] : '';
        $service['ServiceDetail']['contact_lense'] = isset($decoded['contact_lense']) ? $decoded['contact_lense'] : '';
        $service['ServiceDetail']['dry_eye'] = isset($decoded['dry_eye']) ? $decoded['dry_eye'] : '';
        $service['ServiceDetail']['sick_eye'] = isset($decoded['sick_eye']) ? $decoded['sick_eye'] : '';
        $service['ServiceDetail']['congestion'] = isset($decoded['congestion']) ? $decoded['congestion'] : '';
        $service['ServiceDetail']['lasik'] = isset($decoded['lasik']) ? $decoded['lasik'] : '';
        $service['ServiceDetail']['eye_perm'] = isset($decoded['eye_perm']) ? $decoded['eye_perm'] : '';
        $service['ServiceDetail']['agreement'] = isset($decoded['agreement']) ? $decoded['agreement'] : '';
        $service['ServiceDetail']['cleansing'] = isset($decoded['cleansing']) ? $decoded['cleansing'] : '';
        $service['ServiceDetail']['status'] =1;
       
        if($this->ServiceDetail->saveAll($service)){
            $service_detail_id = $this->ServiceDetail->id;
            $jsonEncode = json_encode($service_detail_id);
            
        }else{
            $jsonEncode = json_encode("error");
        }
        $log = $this->ServiceDetail->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }


    /**************************************************************************
     * NAME: customer_check
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/
    
    function customer_check($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Customer");
        $this->loadModel("RecordData");
        
        $tel = $customer['Customer']['tel'] = isset($decoded['tel']) ? $decoded['tel'] : '';
       
       $customerDetail =array('Customer'=>array(''));
        if(!empty($tel)){
            $customerDetail = $this->Customer->find('first', array('conditions' => array('Customer.tel' => $tel), 'order' => array('Customer.id' => 'DESC')));
            
            if(!empty($customerDetail['Customer']['id'])){
                
                $jsonEncode = json_encode($customerDetail);
                 
            }else{
               // $responseArr = array('status' => 'error' );
                $jsonEncode = json_encode(array('Customer' => array() ));
                
            }
            
        }else{
            // $responseArr = array('status' => 'error' );
             $jsonEncode = json_encode(array('Customer' => array() ));
             
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "customer_check";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }





  /**************************************************************************
     * NAME: add_customer
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/
    
    function add_customer($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Customer");
        $this->loadModel("RecordData");
        
        $tel = $customer['Customer']['tel'] = isset($decoded['tel']) ? $decoded['tel'] : '';
        if(isset($decoded['id']) && !empty($decoded['id']))
        $customer['Customer']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        $customer['Customer']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer['Customer']['salon_id'] = isset($decoded['salon_id']) ? $decoded['salon_id'] : '';
        $customer['Customer']['name'] = isset($decoded['name']) ? $decoded['name'] : '';
        $customer['Customer']['first_name'] = isset($decoded['first_name']) ? $decoded['first_name'] : '';
        $customer['Customer']['last_name'] = isset($decoded['last_name']) ? $decoded['last_name'] : '';
        $customer['Customer']['kana'] = isset($decoded['kana']) ? $decoded['kana'] : '';
        $customer['Customer']['kana_first_name'] = isset($decoded['kana_first_name']) ? $decoded['kana_first_name'] : '';
        $customer['Customer']['kana_last_name'] = isset($decoded['kana_last_name']) ? $decoded['kana_last_name'] : '';
        $customer['Customer']['gender'] = isset($decoded['gender']) ? $decoded['gender'] : '';
        $customer['Customer']['age'] = isset($decoded['age']) ? $decoded['age'] : '';
        $customer['Customer']['dob'] = isset($decoded['dob']) ? $decoded['dob'] : '';
        
        $customer['Customer']['zip_code'] = isset($decoded['zip_code']) ? $decoded['zip_code'] : '';
        $customer['Customer']['prefecture'] = isset($decoded['prefecture']) ? $decoded['prefecture'] : '';
        $customer['Customer']['address1'] = isset($decoded['address1']) ? $decoded['address1'] : '';
        $customer['Customer']['address2'] = isset($decoded['address2']) ? $decoded['address2'] : '';
        $customer['Customer']['city'] = isset($decoded['city']) ? $decoded['city'] : '';
        $customer['Customer']['email'] = isset($decoded['email']) ? $decoded['email'] : '';
        $customer['Customer']['subscription_of_news'] = isset($decoded['subscription_of_news']) ? $decoded['subscription_of_news'] : '';
        $customer['Customer']['house_wife'] = isset($decoded['house_wife']) ? $decoded['house_wife'] : '';
        $customer['Customer']['job'] = isset($decoded['job']) ? $decoded['job'] : '';
        $customer['Customer']['know_about_company'] = isset($decoded['know_about_company']) ? $decoded['know_about_company'] : '';
        $customer['Customer']['how_did_you_come'] = isset($decoded['how_did_you_come']) ? $decoded['how_did_you_come'] : '';
        $customer['Customer']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $verification_code = substr(md5(uniqid()), 0, 20);
        $customer['Customer']['verification_code'] = $verification_code;
        $customer['Customer']['status'] =1;
       
        if(!empty($tel)){
            $customerDetail = $this->Customer->find('first', array('conditions' => array('Customer.tel' => $tel), 'order' => array('Customer.id' => 'DESC')));
            
            if(!empty($customerDetail['Customer']['id'])){
                $customer['Customer']['id'] = $customerDetail['Customer']['id'];
                if($this->Customer->saveAll($customer)){

                    $to      = $customer['Customer']['email'];
                    $from    = Configure::read('App.AdminMail');
                    $customer_name = $customer['Customer']['name'];
                    $mail_message = '';
                    
                    $this->loadModel('Template');
                    $registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'verify_email')));
                    $email_subject = $registrationMail['Template']['subject'];
                    $subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
                    $activationCode = $customer['Customer']['verification_code'];
                    $activation_url = Router::url(array(
                                                        'controller' => 'customers',
                                                        'action' => 'email_confirm',
                                                        base64_encode($customer['Customer']['email']),
                                                        $verification_code,
                                                        ), true);

                    
                    $activation_link    =   '<a href="'.$activation_url.'">Click Here</a>';
                    $mail_message = str_replace(array('{NAME}','{ACTIVATION_LINK}', '{activation_code}', '{SITE}'), array($customer_name,$activation_link, $activationCode, Configure::read('App.SITENAME')), $registrationMail['Template']['content']);
                    $template = 'default';

                     $from = 'JTSBoard  <admin@jtsboard.com>';

                    $headers = "From: " .($from) . "\r\n";
                    $headers .= "Reply-To: ".($from) . "\r\n";
                    $headers .= "Return-Path: ".($from) . "\r\n";;
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers .= "X-Priority: 3\r\n";
                    $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

                //    @mail($to,$subject,$mail_message,$headers);


                   // parent::sendMail($to, 'Customer Email Varify', $mail_message, $from, $template); 
                    
                    $customer_id = $this->Customer->id;
                    $service_id = $customer['Customer']['service_id'];
                    $responseArr = array('customer_id' => $customer_id, 'service_id' => $service_id, 'status' => 'success' );
                    $jsonEncode = json_encode($responseArr);
                   
                    
               }else{
                    $responseArr = array('status' => 'error' );
                    $jsonEncode = json_encode($responseArr);
                }

                 
            }else{
               if($this->Customer->saveAll($customer)){

                    $to      = $customer['Customer']['email'];
                    $from    = Configure::read('App.AdminMail');
                    $customer_name = $customer['Customer']['name'];
                    $mail_message = '';
                    
                    $this->loadModel('Template');
                    $registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'verify_email')));
                    $email_subject = $registrationMail['Template']['subject'];
                    $subject = __('[' . Configure::read('Site.title') . '] ' . $email_subject . '', true);
                    $activationCode = $customer['Customer']['verification_code'];
                    $activation_url = Router::url(array(
                                                        'controller' => 'customers',
                                                        'action' => 'email_confirm',
                                                        base64_encode($customer['Customer']['email']),
                                                        $verification_code,
                                                        ), true);

                    
                    $activation_link    =   '<a href="'.$activation_url.'">Click Here</a>';
                    $mail_message = str_replace(array('{NAME}','{ACTIVATION_LINK}', '{activation_code}', '{SITE}'), array($customer_name,$activation_link, $activationCode, Configure::read('App.SITENAME')), $registrationMail['Template']['content']);
                    $template = 'default';

                     $from = 'JTSBoard  <admin@jtsboard.com>';

                    $headers = "From: " .($from) . "\r\n";
                    $headers .= "Reply-To: ".($from) . "\r\n";
                    $headers .= "Return-Path: ".($from) . "\r\n";;
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers .= "X-Priority: 3\r\n";
                    $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

                //    @mail($to,$subject,$mail_message,$headers);


                   // parent::sendMail($to, 'Customer Email Varify', $mail_message, $from, $template); 
                    
                    $customer_id = $this->Customer->id;
                    $service_id = $customer['Customer']['service_id'];
                    $responseArr = array('customer_id' => $customer_id, 'service_id' => $service_id, 'status' => 'success' );
                    $jsonEncode = json_encode($responseArr);
                   
                    
               }else{
                    $responseArr = array('status' => 'error' );
                    $jsonEncode = json_encode($responseArr);
                }
                
            }
            
        }else{
             $responseArr = array('status' => 'error' );
             $jsonEncode = json_encode($responseArr);
             
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_customer";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }



      /**************************************************************************
     * NAME: add_esthe_service
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/



     function add_esthe_service($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

         $this->loadModel("Customer");
         $this->loadModel("Esthe");

        $esthe['Esthe']['customor_id'] = isset($decoded['customor_id']) ? $decoded['customor_id'] : '';
        $esthe['Esthe']['status'] =isset($decoded['status']) ? $decoded['status'] : '';
        $esthe['Esthe']['status_text'] = isset($decoded['status_text']) ? $decoded['status_text'] : '';
        $esthe['Esthe']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $esthe['Esthe']['allegy_text'] = isset($decoded['allegy_text']) ? $decoded['allegy_text'] : '';
        $esthe['Esthe']['medicine'] = isset($decoded['medicine']) ? $decoded['medicine'] : '';
        $esthe['Esthe']['medicine_text'] = isset($decoded['medicine_text']) ? $decoded['medicine_text'] : '';
        $esthe['Esthe']['hospital'] = isset($decoded['hospital']) ? $decoded['hospital'] : '';
        $esthe['Esthe']['hospital_text'] = isset($decoded['hospital_text']) ? $decoded['hospital_text'] : '';
        $esthe['Esthe']['medical_history'] = isset($decoded['medical_history']) ? $decoded['medical_history'] : '';
        $esthe['Esthe']['medical_history_text'] = isset($decoded['medical_history_text']) ? $decoded['medical_history_text'] : '';
        $esthe['Esthe']['period'] = isset($decoded['period']) ? $decoded['period'] : '';
        $esthe['Esthe']['experience_of_birth'] = isset($decoded['experience_of_birth']) ? $decoded['experience_of_birth'] : '';
        $esthe['Esthe']['bowel_movement'] = isset($decoded['bowel_movement']) ? $decoded['bowel_movement'] : '';
        $esthe['Esthe']['sleep_start_time'] = isset($decoded['sleep_start_time']) ? $decoded['sleep_start_time'] : '';
        $esthe['Esthe']['sleep_time_avg'] = isset($decoded['sleep_time_avg']) ? $decoded['sleep_time_avg'] : '';
        $esthe['Esthe']['concern'] = isset($decoded['concern']) ? $decoded['concern'] : '';
        $esthe['Esthe']['concern_extra'] = isset($decoded['concern_extra']) ? $decoded['concern_extra'] : '';
        $esthe['Esthe']['concern_date'] = isset($decoded['concern_date']) ? $decoded['concern_date'] : '';
        $esthe['Esthe']['itchy'] = isset($decoded['itchy']) ? $decoded['itchy'] : '';
        $esthe['Esthe']['itchy_text'] = isset($decoded['itchy_text']) ? $decoded['itchy_text'] : '';
        $esthe['Esthe']['cosmetic_name'] = isset($decoded['cosmetic_name']) ? $decoded['cosmetic_name'] : '';
        $esthe['Esthe']['how_to_maintain'] = isset($decoded['how_to_maintain']) ? $decoded['how_to_maintain'] : '';
        $esthe['Esthe']['peeling'] = isset($decoded['peeling']) ? $decoded['peeling'] : '';
        $esthe['Esthe']['treatment'] = isset($decoded['treatment']) ? $decoded['treatment'] : '';
        $esthe['Esthe']['esthe_experience'] = isset($decoded['esthe_experience']) ? $decoded['esthe_experience'] : '';
        $esthe['Esthe']['esthe_experience_text'] = isset($decoded['esthe_experience_text']) ? $decoded['esthe_experience_text'] : '';
        $esthe['Esthe']['surgery'] = isset($decoded['surgery']) ? $decoded['surgery'] : '';
        $esthe['Esthe']['surgery_text'] = isset($decoded['surgery_text']) ? $decoded['surgery_text'] : '';
        $esthe['Esthe']['contact_lense'] = isset($decoded['contact_lense']) ? $decoded['contact_lense'] : '';
        $esthe['Esthe']['contact_lense_extra'] = isset($decoded['contact_lense_extra']) ? $decoded['contact_lense_extra'] : '';
        
        
       
        if($this->Esthe->saveAll($esthe)){
            $esthe_id = $this->Esthe->id;
            $customer_id = $decoded['customer_id'];

            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData); 


            $responseArr = array('customer_id' => $customer_id, 'esthe_id' => $esthe_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Esthe->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_esthe_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }



 /**************************************************************************
     * NAME: esthe_service
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/

     function esthe_service($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Customer");
        $this->loadModel("Esthe");

        $esthe['Esthe']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $esthe['Esthe']['status'] =isset($decoded['status']) ? $decoded['status'] : '';
        $esthe['Esthe']['status_text'] = isset($decoded['status_text']) ? $decoded['status_text'] : '';
        $esthe['Esthe']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $esthe['Esthe']['allegy_text'] = isset($decoded['allegy_text']) ? $decoded['allegy_text'] : '';
        $esthe['Esthe']['medicine'] = isset($decoded['medicine']) ? $decoded['medicine'] : '';
        $esthe['Esthe']['medicine_text'] = isset($decoded['medicine_text']) ? $decoded['medicine_text'] : '';
        $esthe['Esthe']['hospital'] = isset($decoded['hospital']) ? $decoded['hospital'] : '';
        $esthe['Esthe']['hospital_text'] = isset($decoded['hospital_text']) ? $decoded['hospital_text'] : '';
        $esthe['Esthe']['medical_history'] = isset($decoded['medical_history']) ? $decoded['medical_history'] : '';
        $esthe['Esthe']['medical_history_text'] = isset($decoded['medical_history_text']) ? $decoded['medical_history_text'] : '';
        $esthe['Esthe']['period'] = isset($decoded['period']) ? $decoded['period'] : '';
        $esthe['Esthe']['experience_of_birth'] = isset($decoded['experience_of_birth']) ? $decoded['experience_of_birth'] : '';
        $esthe['Esthe']['bowel_movement'] = isset($decoded['bowel_movement']) ? $decoded['bowel_movement'] : '';
        $esthe['Esthe']['sleep_start_time'] = isset($decoded['sleep_start_time']) ? $decoded['sleep_start_time'] : '';
        $esthe['Esthe']['sleep_time_avg'] = isset($decoded['sleep_time_avg']) ? $decoded['sleep_time_avg'] : '';
        $esthe['Esthe']['concern'] = isset($decoded['concern']) ? $decoded['concern'] : '';
        $esthe['Esthe']['concern_extra'] = isset($decoded['concern_extra']) ? $decoded['concern_extra'] : '';
        $esthe['Esthe']['concern_date'] = isset($decoded['concern_date']) ? $decoded['concern_date'] : '';
        $esthe['Esthe']['itchy'] = isset($decoded['itchy']) ? $decoded['itchy'] : '';
        $esthe['Esthe']['itchy_text'] = isset($decoded['itchy_text']) ? $decoded['itchy_text'] : '';
        $esthe['Esthe']['cosmetic_name'] = isset($decoded['cosmetic_name']) ? $decoded['cosmetic_name'] : '';
        $esthe['Esthe']['how_to_maintain'] = isset($decoded['how_to_maintain']) ? json_encode($decoded['how_to_maintain']) : '';
        $esthe['Esthe']['peeling'] = isset($decoded['peeling']) ? $decoded['peeling'] : '';
        $esthe['Esthe']['treatment'] = isset($decoded['treatment']) ? $decoded['treatment'] : '';
        $esthe['Esthe']['esthe_experience'] = isset($decoded['esthe_experience']) ? $decoded['esthe_experience'] : '';
        $esthe['Esthe']['esthe_experience_text'] = isset($decoded['esthe_experience_text']) ? $decoded['esthe_experience_text'] : '';
        $esthe['Esthe']['surgery'] = isset($decoded['surgery']) ? $decoded['surgery'] : '';
        $esthe['Esthe']['surgery_text'] = isset($decoded['surgery_text']) ? $decoded['surgery_text'] : '';
        $esthe['Esthe']['contact_lense'] = isset($decoded['contact_lense']) ? $decoded['contact_lense'] : '';
        $esthe['Esthe']['contact_lense_extra'] = isset($decoded['contact_lense_extra']) ? $decoded['contact_lense_extra'] : '';
        
        
       
        if($this->Esthe->saveAll($esthe)){
            $esthe_id = $this->Esthe->id;
            $customer_id = $decoded['customer_id'];

            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData); 

            $responseArr = array('customer_id' => $customer_id, 'esthe_id' => $esthe_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);
             
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Esthe->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "esthe_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();


    }


/**************************************************************************
     * NAME: eyelush_service           
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     * 日常生活で、目の乾燥・かすみを感じることはありますか？
     *********************************************************************/

     function eyelush_service($test_data =null){

        $data = file_get_contents('php://input');
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("Customer");
        $this->loadModel("Eyelush");

        $eyelush['Eyelush']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $eyelush['Eyelush']['contact_lense'] = isset($decoded['contact_lense']) ? $decoded['contact_lense'] : '';
        $eyelush['Eyelush']['dry_eye'] =isset($decoded['dry_eye']) ? $decoded['dry_eye'] : '';
        $eyelush['Eyelush']['dry_eye_text'] =isset($decoded['dry_eye_text']) ? $decoded['dry_eye_text'] : '';
        $eyelush['Eyelush']['sick_eye'] = isset($decoded['sick_eye']) ? $decoded['sick_eye'] : '';
        $eyelush['Eyelush']['sick_eye_text'] = isset($decoded['sick_eye_text']) ? $decoded['sick_eye'] : '';
        $eyelush['Eyelush']['congestion'] = isset($decoded['congestion']) ? $decoded['congestion'] : '';
        $eyelush['Eyelush']['congestion_text'] = isset($decoded['congestion_text']) ? $decoded['congestion_text'] : '';
        $eyelush['Eyelush']['surgery'] = isset($decoded['surgery']) ? $decoded['surgery'] : '';
        $eyelush['Eyelush']['surgery_text'] = isset($decoded['surgery_text']) ? $decoded['surgery_text'] : '';
        $eyelush['Eyelush']['lasik'] = isset($decoded['lasik']) ? $decoded['lasik'] : '';
        $eyelush['Eyelush']['lasik_text'] = isset($decoded['lasik_text']) ? $decoded['lasik_text'] : '';
        $eyelush['Eyelush']['eye_perm'] = isset($decoded['eye_perm']) ? $decoded['eye_perm'] : '';
        $eyelush['Eyelush']['eye_perm_text'] = isset($decoded['eye_perm_text']) ? $decoded['eye_perm_text'] : '';
        $eyelush['Eyelush']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $eyelush['Eyelush']['allegy_text'] = isset($decoded['allegy_text']) ? $decoded['allegy_text'] : '';
        $eyelush['Eyelush']['pregnancy'] = isset($decoded['pregnancy']) ? $decoded['pregnancy'] : '';
        $eyelush['Eyelush']['cleansing'] = isset($decoded['cleansing']) ? $decoded['cleansing'] : '';
        $eyelush['Eyelush']['agreement'] = isset($decoded['agreement']) ? $decoded['agreement'] : '';
       
        if($this->Eyelush->saveAll($eyelush)){
            $eyelush_id = $this->Eyelush->id;
            $customer_id = $decoded['customer_id'];

            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData); 

            $responseArr = array('customer_id' => $customer_id, 'eyelush_id' => $eyelush_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Eyelush->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "eyelush_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }

/**************************************************************************
     * NAME: body_service           
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/

     function body_service($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Body");
        $this->loadModel("Customer");

        $body['Body']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $body['Body']['status'] =isset($decoded['status']) ? $decoded['status'] : '';
        $body['Body']['status_text'] = isset($decoded['status_text']) ? $decoded['status_text'] : '';
        $body['Body']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $body['Body']['allegy_text'] = isset($decoded['allegy_text']) ? $decoded['allegy_text'] : '';
        $body['Body']['medicine'] = isset($decoded['medicine']) ? $decoded['medicine'] : '';
        $body['Body']['medicine_text'] = isset($decoded['medicine_text']) ? $decoded['medicine_text'] : '';
        $body['Body']['hospital'] = isset($decoded['hospital']) ? $decoded['hospital'] : '';
        $body['Body']['hospital_text'] = isset($decoded['hospital_text']) ? $decoded['hospital_text'] : '';
        $body['Body']['medical_history'] = isset($decoded['medical_history']) ? $decoded['medical_history'] : '';
        $body['Body']['medical_history_text'] = isset($decoded['medical_history_text']) ? $decoded['medical_history_text'] : '';
        $body['Body']['period'] = isset($decoded['period']) ? $decoded['period'] : '';
        $body['Body']['experience_of_birth'] = isset($decoded['experience_of_birth']) ? $decoded['experience_of_birth'] : '';
        $body['Body']['bowel_movement'] = isset($decoded['bowel_movement']) ? $decoded['bowel_movement'] : '';
        $body['Body']['sleep_start_time'] = isset($decoded['sleep_start_time']) ? $decoded['sleep_start_time'] : '';
        $body['Body']['sleep_time_avg'] = isset($decoded['sleep_time_avg']) ? $decoded['sleep_time_avg'] : '';
    
        if($this->Body->saveAll($body)){
            $body_id = $this->Body->id;
            $customer_id = $decoded['customer_id'];

            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData); 

            $responseArr = array('customer_id' => $customer_id, 'body_id' => $body_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Body->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "body_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }

/**************************************************************************
     * NAME: hairremoval_service           
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/

     function hairremoval_service($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Customer");
        $this->loadModel("HairRemoval");

        $hair_removal['HairRemoval']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $hair_removal['HairRemoval']['status'] =isset($decoded['status']) ? $decoded['status'] : '';
        $hair_removal['HairRemoval']['status_text'] = isset($decoded['status_text']) ? $decoded['status_text'] : '';
        $hair_removal['HairRemoval']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $hair_removal['HairRemoval']['allegy_text'] = isset($decoded['allegy_text']) ? $decoded['allegy_text'] : '';
        $hair_removal['HairRemoval']['medicine'] = isset($decoded['medicine']) ? $decoded['medicine'] : '';
        $hair_removal['HairRemoval']['medicine_text'] = isset($decoded['medicine_text']) ? $decoded['medicine_text'] : '';
        $hair_removal['HairRemoval']['hospital'] = isset($decoded['hospital']) ? $decoded['hospital'] : '';
        $hair_removal['HairRemoval']['hospital_text'] = isset($decoded['hospital_text']) ? $decoded['hospital_text'] : '';
        $hair_removal['HairRemoval']['medical_history'] = isset($decoded['medical_history']) ? $decoded['medical_history'] : '';
        $hair_removal['HairRemoval']['medical_history_text'] = isset($decoded['medical_history_text']) ? $decoded['medical_history_text'] : '';
        $hair_removal['HairRemoval']['concern'] = isset($decoded['concern']) ? $decoded['concern'] : '';
        $hair_removal['HairRemoval']['concern_extra'] = isset($decoded['concern_extra']) ? $decoded['concern_extra'] : '';
        $hair_removal['HairRemoval']['period'] = isset($decoded['period']) ? $decoded['period'] : '';
        $hair_removal['HairRemoval']['esthe_experience'] = isset($decoded['esthe_experience']) ? $decoded['esthe_experience'] : '';
        $hair_removal['HairRemoval']['esthe_experience_text'] = isset($decoded['esthe_experience_text']) ? $decoded['esthe_experience_text'] : '';
        $hair_removal['HairRemoval']['surgery'] = isset($decoded['surgery']) ? $decoded['surgery'] : '';
        $hair_removal['HairRemoval']['surgery_text'] = isset($decoded['surgery_text']) ? $decoded['surgery_text'] : '';
 
        if($this->HairRemoval->saveAll($hair_removal)){
            $hair_removal_id = $this->HairRemoval->id;
            $customer_id = $decoded['customer_id'];
             
            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData);    

            $responseArr = array('customer_id' => $customer_id, 'hair_removal_id' => $hair_removal_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);

            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->HairRemoval->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "hairremoval_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }



    /**************************************************************************
     * NAME: photofacial_service           
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/

     function photofacial_service($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Customer");
        $this->loadModel("PhotoFacial");

        $photo_facial['PhotoFacial']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $photo_facial['PhotoFacial']['status'] =isset($decoded['status']) ? $decoded['status'] : '';
        $photo_facial['PhotoFacial']['status_text'] = isset($decoded['status_text']) ? $decoded['status_text'] : '';
        $photo_facial['HairRemoval']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $photo_facial['PhotoFacial']['allegy_text'] = isset($decoded['allegy_text']) ? $decoded['allegy_text'] : '';
        $photo_facial['PhotoFacial']['medicine'] = isset($decoded['medicine']) ? $decoded['medicine'] : '';
        $photo_facial['PhotoFacial']['medicine_text'] = isset($decoded['medicine_text']) ? $decoded['medicine_text'] : '';
        $photo_facial['PhotoFacial']['hospital'] = isset($decoded['hospital']) ? $decoded['hospital'] : '';
        $photo_facial['PhotoFacial']['hospital_text'] = isset($decoded['hospital_text']) ? $decoded['hospital_text'] : '';
        $photo_facial['PhotoFacial']['medical_history'] = isset($decoded['medical_history']) ? $decoded['medical_history'] : '';
        $photo_facial['PhotoFacial']['medical_history_text'] = isset($decoded['medical_history_text']) ? $decoded['medical_history_text'] : '';
        $photo_facial['PhotoFacial']['concern'] = isset($decoded['concern']) ? $decoded['concern'] : '';
        $photo_facial['PhotoFacial']['concern_extra'] = isset($decoded['concern_extra']) ? $decoded['concern_extra'] : '';
        $photo_facial['PhotoFacial']['period'] = isset($decoded['period']) ? $decoded['period'] : '';
        $photo_facial['PhotoFacial']['esthe_experience'] = isset($decoded['esthe_experience']) ? $decoded['esthe_experience'] : '';
        $photo_facial['PhotoFacial']['esthe_experience_text'] = isset($decoded['esthe_experience_text']) ? $decoded['esthe_experience_text'] : '';
        $photo_facial['PhotoFacial']['surgery'] = isset($decoded['surgery']) ? $decoded['surgery'] : '';
        $photo_facial['PhotoFacial']['surgery_text'] = isset($decoded['surgery_text']) ? $decoded['surgery_text'] : '';
 
        if($this->PhotoFacial->saveAll($photo_facial)){
            $photo_facial_id = $this->PhotoFacial->id;
            $customer_id = $decoded['customer_id'];
            $responseArr = array('customer_id' => $customer_id, 'photo_facial_id' => $photo_facial_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);

            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->PhotoFacial->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "photofacial_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }



    /**************************************************************************
     * NAME: service_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function service_detail_list($user_id = null){
        $data = file_get_contents("php://input");
        
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }
        $decoded = json_decode($data, true);

        if(isset($decoded['user_id'])){
        $this->loadModel("User");
        $this->loadModel("Service");
        $this->loadModel("ServiceDetail");
        $user_id = $decoded['user_id']; 
        $this->ServiceDetail->bindModel(array('belongsTo' => array('Service', 'User')));
            
        $this->ServiceDetail->recursive  =   2;
        $data = $this->ServiceDetail->find('all',array('conditions'=>
                                                array(
                                                      'ServiceDetail.user_id'=>$user_id,
                                                      )
                                                ));
                                               
        }else{

            $data ='error';
        }                                        

        $jsonEncode = json_encode($data);
        $log = $this->ServiceDetail->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "service_detail_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

    function get_salon_name($id = ''){
        $this->loadModel("Salon");
        if(!empty($id)){
            $data = $this->Salon->find('first',array('conditions'=> array('Salon.id'=>$id )));
        }else{
            return 'No Salon';
        }   
    }
    function get_service_name($id = ''){
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

    function get_user_name($id = ''){
        $this->loadModel("User");
        if(!empty($id)){
            $data = $this->User->find('first',array('conditions'=> array('User.id'=>$id )));
            if(isset($data['User']['name'])){
                $user_name = $data['User']['name'];
            }else{

                $user_name = '';
            }
            return  $user_name;
        }else{
            return '';
        }   
    }

    function get_employee_name($id = ''){
        $this->loadModel("Employee");
        if(!empty($id)){
            $data = $this->Employee->find('first',array('conditions'=> array('Employee.id'=>$id )));
            if(isset($data['Employee']['name'])){
                $service_name = $data['Employee']['name'];
            }else{

                $service_name = '';
            }
            return  $service_name;
        }else{
            return '';
        }   
    }
     function get_employee_image($id = ''){
        $this->loadModel("Employee");
        if(!empty($id)){
            $data = $this->Employee->find('first',array('conditions'=> array('Employee.id'=>$id )));
            if(isset($data['Employee']['image'])){
                $service_name = $data['Employee']['image'];
            }else{

                $service_name = '';
            }
            return  $service_name;
        }else{
            return '';
        }   
    }


/* New V1 data 08-08-2018 */

     /****************************************************************************************************************************************
     * NAME: customer_analysis
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function customer_analysis($test_data = null){
        $data = file_get_contents('php://input');
        
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    
        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $responseArr = array();
        
        $this->loadModel('Customer');
        $this->loadModel('CustomerHistory');
        $this->loadModel('NoteImage');
        $this->loadModel('User');
        $this->loadModel('Employee');

        $date = isset($decoded['date']) ? $decoded['date'] : '';
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $employee_id = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $service_status = isset($decoded['service_status']) ? $decoded['service_status'] : '';
        
        if(!empty($employee_id ) && $employee_id  != 'null'){
			$emp_name =	$this->get_employee_name($employee_id);
			$emp_image =	$this->get_employee_image($employee_id);

        }else{
        	 $userExist=$this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
        	 if(isset($userExist['User']['user_emp_code']) && !empty($userExist['User']['user_emp_code'])){
        	 	$empExist=$this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$userExist['User']['user_emp_code'])));	
        	 	if(isset($empExist['Employee']['id']) && !empty($empExist['Employee']['id'])){
	        	 	$emp_name =	$this->get_employee_name($empExist['Employee']['id']);
					$emp_image =	$this->get_employee_image($empExist['Employee']['id']);
				}else{
					$emp_name =	$userExist['User']['user_emp_code'];
					$emp_image =	'';
				}

        	 }else{
        	 	$emp_name =	$userExist['User']['user_emp_code'];
				$emp_image =	'';
        	 }	
        }


        if($service_status == '1'){
            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date, 'CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));
            if(isset($customerAnalysisData['CustomerHistory']['id']) && !empty($customerAnalysisData['CustomerHistory']['id'])){
                $customerHistory['CustomerHistory']['id'] = $customerAnalysisData['CustomerHistory']['id'];
                $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                $customer_id  = $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $serviceData =  json_decode($customerAnalysisData['CustomerHistory']['service_price'], true);
                
                $serviceArray = array();
                $i =0;
                foreach ($serviceData as $serviceKey => $serviceValue) {
                      
                       $serviceArray[$serviceKey]['service'] = $serviceValue['service'];
                       $serviceArray[$serviceKey]['price'] = $serviceValue['price'];
                       $serviceArray[$serviceKey]['payment'] = $serviceValue['payment'];
                       if(isset($serviceValue['service_id']) && !empty($serviceValue['service_id']))
                            $serviceArray[$serviceKey]['service_id'] = $serviceValue['service_id'];
                       if(isset($serviceValue['customer_service_id']) && !empty($serviceValue['customer_service_id']))
                            $serviceArray[$serviceKey]['customer_service_id'] = $serviceValue['customer_service_id'];
                       $i++;
                  
                }

                $service_price = isset($decoded['service_price']) ? $decoded['service_price'] : '';

                foreach ($service_price as $serviceKey => $serviceValue) {
                   $serviceArray[$i]['service'] = $serviceValue['service'];
                   $serviceArray[$i]['price'] = $serviceValue['price'];
                   $serviceArray[$i]['payment'] = $serviceValue['payment'];
                   if(isset($serviceValue['service_id']) && !empty($serviceValue['service_id']))
                            $serviceArray[$i]['service_id'] = $serviceValue['service_id'];
                   if(isset($serviceValue['customer_service_id']) && !empty($serviceValue['customer_service_id']))
                        $serviceArray[$i]['customer_service_id'] = $serviceValue['customer_service_id'];
                }
                $customerHistory['CustomerHistory']['service_price'] = json_encode($serviceArray);
                
            }else{
                $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                $customer_id  = $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $service_price = isset($decoded['service_price']) ? $decoded['service_price'] : '';
                $customerHistory['CustomerHistory']['service_price'] = json_encode($service_price);
                $customerHistory['CustomerHistory']['date'] = isset($decoded['date']) ? $decoded['date'] : '';
                $customerHistory['CustomerHistory']['note_text'] = isset($decoded['note_text']) ? $decoded['note_text'] : '';
                $customerHistory['CustomerHistory']['note_image'] = isset($decoded['note_image']) ? $decoded['note_image'] : '';

            }
            if($this->CustomerHistory->saveAll($customerHistory)){

                $customer_analysis_id = $this->CustomerHistory->id;

                /********Start Note Images add********/
                $noteImages = array();
                $note_image = isset($decoded['note_image']) ? $decoded['note_image'] : '';
                if(isset($note_image) && !empty($note_image)){
                    foreach ($note_image as $key => $value) {
                    
                        $noteImages = array();
                        $noteImages['NoteImage']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                        $noteImages['NoteImage']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                        $emp_id = $noteImages['NoteImage']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
                        $noteImages['NoteImage']['employee_name'] = $emp_name;
                        $noteImages['NoteImage']['employee_image'] = $emp_image;
                        $noteImages['NoteImage']['customer_history_id'] = $customer_analysis_id;
                        $noteImages['NoteImage']['image'] = $value;
                       
                        $this->NoteImage->saveAll($noteImages);

                    }
                }

                /********End Note Images add********/

                /********Customer Modified date update ********/

                $customerData['Customer']['id'] = $customer_id;
                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
                $responseArr = array('customer_analysis_id' => $customer_analysis_id, 'msg'=>'顧客情報が正常に追加されました。',  'msg1'=>'Customer information was successfully added.', 'status' => 'success' );
                $jsonEncode = json_encode($responseArr);
                
            }else{
                $responseArr = array('status' => 'error' );
                $jsonEncode = json_encode($responseArr);
            }


        }else{


            if(isset($decoded['id']) && !empty($decoded['id'])){
                $customerAnalysisExist=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$decoded['id'])));
                if(isset($customerAnalysisExist['CustomerHistory']['id']) && !empty($customerAnalysisExist['CustomerHistory']['id'])){
                    $customerHistory['CustomerHistory']['id'] = $customerAnalysisExist['CustomerHistory']['id'];
                } 
            }
            $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
            $customer_id  = $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
            $service_price = isset($decoded['service_price']) ? $decoded['service_price'] : '';
            $customerHistory['CustomerHistory']['service_price'] = json_encode($service_price);
            $customerHistory['CustomerHistory']['date'] = isset($decoded['date']) ? $decoded['date'] : '';
            $customerHistory['CustomerHistory']['note_text'] = isset($decoded['note_text']) ? $decoded['note_text'] : '';
            

            
            if($this->CustomerHistory->saveAll($customerHistory)){

                $customer_analysis_id = $this->CustomerHistory->id;
                
                /********Start Note Images add********/
                $noteImages = array();
                $note_image = isset($decoded['note_image']) ? $decoded['note_image'] : '';
                if(isset($note_image) && !empty($note_image)){
                    foreach ($note_image as $key => $value) {
                    
                        $noteImages = array();
                        $noteImages['NoteImage']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                        $noteImages['NoteImage']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                        $emp_id = $noteImages['NoteImage']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
                        $noteImages['NoteImage']['employee_name'] = $emp_name;
                        $noteImages['NoteImage']['employee_image'] = $emp_image;
                       
                        $noteImages['NoteImage']['customer_history_id'] = $customer_analysis_id;
                        $noteImages['NoteImage']['image'] = $value;
                        
                        $this->NoteImage->saveAll($noteImages);

                    }
                }

                /********End Note Images add********/

                /********Customer Modified date update ********/
                $customerData['Customer']['id'] = $customer_id;
                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
                $responseArr = array('customer_analysis_id' => $customer_analysis_id, 'msg'=>'顧客情報が正常に追加されました。',  'msg1'=>'Customer information was successfully added.', 'status' => 'success' );
                $jsonEncode = json_encode($responseArr);
                
            }else{
                $responseArr = array('status' => 'error' );
                $jsonEncode = json_encode($responseArr);
            }
        }    
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "customer_analysis";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }
/****************************************************************************************************************************************
     * NAME: edit_customer_analysis_date
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/


    function edit_customer_analysis_date($test_data = null){
        $data = file_get_contents('php://input');
        
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    
        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $responseArr = $customerData = array();
        
        $this->loadModel('CustomerHistory');
        $this->loadModel('Customer');
        $this->loadModel('NoteImage');
        if(isset($decoded['id']) && !empty($decoded['id'])){
            $customerAnalysisExist=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$decoded['id'])));
            if(isset($customerAnalysisExist['CustomerHistory']['id']) && !empty($customerAnalysisExist['CustomerHistory']['id'])){
                $customerHistory['CustomerHistory']['id'] = $customerAnalysisExist['CustomerHistory']['id'];
            } 
        }
        if(isset($decoded['user_id']) && !empty($decoded['user_id']))
        $user_id = $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        if(isset($decoded['customer_id']) && !empty($decoded['customer_id']))
        $customer_id = $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        if(isset($decoded['service_price']) && !empty($decoded['service_price'])){
            $service_price = isset($decoded['service_price']) ? $decoded['service_price'] : '';
            $customerHistory['CustomerHistory']['service_price'] = json_encode($service_price);
        }


        if(isset($decoded['date']) && !empty($decoded['date']))
        $customerHistory['CustomerHistory']['date'] = isset($decoded['date']) ? $decoded['date'] : '';
      
        
        if($this->CustomerHistory->saveAll($customerHistory)){
            $customer_analysis_id = $this->CustomerHistory->id;
            $responseArr = array('customer_analysis_id' => $customer_analysis_id, 'msg'=>'日にちを変更しました。', 'status' => 'success' );
            
             /********Start Note Images add********/
                $noteImages = array();
                $note_image = isset($decoded['note_image']) ? $decoded['note_image'] : '';
                if(isset($note_image['NoteImage']) && !empty($note_image['NoteImage'])){
                    foreach ($note_image as $key => $value) {
                    
                        $noteImages = array();
                        $noteImages['NoteImage']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                        $noteImages['NoteImage']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                        $emp_id = $noteImages['NoteImage']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
                        $noteImages['NoteImage']['employee_name'] = $this->get_employee_name($emp_id);
                        $noteImages['NoteImage']['employee_image'] = $this->get_employee_image($emp_id);
                        if(empty($noteImages['NoteImage']['employee_name'])){
                        	$noteImages['NoteImage']['employee_name'] = $this->get_user_name($decoded['user_id']);
                        }
                        $noteImages['NoteImage']['customer_history_id'] = $customer_analysis_id;
                        $noteImages['NoteImage']['image'] = $value;

                    }
                }

                /********End Note Images add********/

                /********Customer Modified date update ********/


            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData);

            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "edit_customer_analysis_date";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }




     /****************************************************************************************************************************************
     * NAME: get_customer_analysis_dates
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function get_customer_analysis_dates($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        //  $data = $test_data;
        }
        $decoded = json_decode($data, true); 
        
        
        $this->loadModel('CustomerHistory');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $responseArr  = array();
        if(!empty($user_id) && !empty($customer_id)){
            $customerAnalysisData=$this->CustomerHistory->find('all', array('conditions'=>array('CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));
            foreach ($customerAnalysisData as $key => $value) {
                $responseArr[$key]['id'] = $value['CustomerHistory']['id'];
                $responseArr[$key]['date'] = $value['CustomerHistory']['date'];
            }
             $jsonEncode = json_encode($responseArr);
         
        }else{

            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_customer_analysis_dates";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    } 

     /****************************************************************************************************************************************
     * NAME: delete_customer_analysis_dates
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function delete_customer_analysis_dates($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('CustomerHistory');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->CustomerHistory->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => '顧客日付が正常に削除されました。', 'msg1' => 'Customer date deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => '顧客の日付がエラーを削除しました。'  , 'msg1' => 'Customer date deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'お客様の日付は存在しません。' , 'msg1' => 'Customer date does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_customer_analysis_dates";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 

    function upload_note_image(){
        if(!empty($_FILES)){
            
            if($_FILES['note_image']['size'] <  6291456){
            
               
                $path_info = pathinfo($_FILES['note_image']['name']);

                $_FILES['note_image']['name'] = $path_info['filename']."_".time().".".$path_info['extension'];
                $res3 = $this->Upload->upload($_FILES['note_image'], WWW_ROOT . CUSTOMER_NOTE_IMAGE . DS ."original". DS, '', '', array('png', 'jpg', 'jpeg', 'gif'));
                
                if (!empty($this->Upload->result)){
                    //echo $this->Upload->result;
                    $message['image'] = $this->Upload->result;
                    $userImage = $this->Upload->result;
                    $result['response_code'] = 200;
                    $result['response_message_code'] = '';
                    $result['message'] = 'success';
                    $result['response_data'] = array();
                    $result['response_data']['image'] = $userImage;

                    $jsonEncode = json_encode($result);
                    return $jsonEncode;
                }
                else{
                    $result['response_status'] = 0;
                    $result['response_code'] = 400;
                    $result['response_message_code'] = 500;
                    $result['message'] = 'error';
                    $result['response_data'] = array(); 
                    $result['response_data']['image'] = '';             
                    $jsonEncode = json_encode($result);
                    
                    return $jsonEncode;
                }
            }
            else{
                    $result['response_status'] = 0;
                    $result['response_code'] = 400;
                    $result['response_message_code'] = 413;
                    $result['message'] = 'cannot upload image of size more than 8MB.';
                    $result['response_data'] = array();
                    $result['response_data']['image'] = '';
                    $jsonEncode = json_encode($result);
                    return $jsonEncode;
            }
        }
        else{       $result['response_status'] = 0;
                    $result['response_code'] = 400;
                    $result['response_message_code'] = 404;
                    $result['message'] = 'file not found';
                    $result['response_data'] = array();
                    $result['response_data']['image'] = '';
                    $jsonEncode = json_encode($result);
                    return $jsonEncode;
        }

    }

    /**************************************************************************
     * NAME: get_customer_analysis
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function get_customer_analysis($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel('CustomerHistory');
        $this->loadModel('NoteImage');
        $this->loadModel('User');
        $this->loadModel('Employee');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $emp_code = isset($decoded['emp_code']) ? $decoded['emp_code'] : '';
        $responseArr  = array();




        if(!empty($id)){

            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$id)));
            //print_r($customerAnalysisData);die;
            $responseArr['id'] = $customerAnalysisData['CustomerHistory']['id'];
            $responseArr['note_text'] = $customerAnalysisData['CustomerHistory']['note_text'];
            $note_image = $customerAnalysisData['CustomerHistory']['note_image'];
            $responseArr['service_price'] = json_decode($customerAnalysisData['CustomerHistory']['service_price']);
            $i =0;
            
            $noteImages=$this->NoteImage->find('all', array('conditions'=>array('NoteImage.customer_history_id'=>$id, 'NoteImage.delete_image_status'=>0)));
            $userData=$this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
            if(isset($userData['User']['user_emp_code']) && !empty($userData['User']['user_emp_code'])){
            	$employeeData=$this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$userData['User']['user_emp_code'])));
            	$emp_name = $employeeData['Employee']['name'];
            	$emp_image = $employeeData['Employee']['image'];
            }else{
            	$emp_name = $userData['User']['name'];
            	$emp_image = '';
            }
            if(isset($noteImages) && !empty($noteImages)){
                foreach ($noteImages as $key => $value) {
                    $responseArr['note_image'][$i]['id'] = $value['NoteImage']['id'];
                    $responseArr['note_image'][$i]['image'] = $value['NoteImage']['image'];

                    if(!empty($value['NoteImage']['employee_image']) && $value['NoteImage']['employee_image'] !='null'){
                    	$responseArr['note_image'][$i]['posted_by_image'] = $value['NoteImage']['employee_image'];
                    }else{

                    	$responseArr['note_image'][$i]['posted_by_image'] = $emp_image;
                    }	
                    if(!empty($value['NoteImage']['employee_name']) &&  ($value['NoteImage']['employee_name'] !='null')){
                    	$responseArr['note_image'][$i]['posted_by_name'] = $value['NoteImage']['employee_name'];
                    }else{

                    	$responseArr['note_image'][$i]['posted_by_name'] = $emp_name;
                    }
                   
                    $responseArr['note_image'][$i]['employee_id'] =  $value['NoteImage']['employee_id'];
                    $responseArr['note_image'][$i]['posted_by_date'] = date('D, M d',strtotime($value['NoteImage']['created']));
                    $responseArr['note_image'][$i]['delete_status'] = $value['NoteImage']['delete_image_status'];
                    if($value['NoteImage']['delete_image_status']=='1'){
                    	$responseArr['note_image'][$i]['deleted_employee_id'] =  $value['NoteImage']['deleted_employee_id'];
                    	$responseArr['note_image'][$i]['deleted_by_name'] = $value['NoteImage']['deleted_employee_name'];
                    	$responseArr['note_image'][$i]['deleted_by_image'] = $value['NoteImage']['deleted_employee_image'];
                    	$responseArr['note_image'][$i]['deleted_by_date'] = date('D, M d',strtotime($value['NoteImage']['modified']));
                    }
                    $i++;
                }
            }
            $jsonEncode = json_encode($responseArr);
       
        }else{

            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_customer_analysis";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

    /**************************************************************************
     * NAME: get_customer_analysis
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function get_deleted_images($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel('CustomerHistory');
        $this->loadModel('NoteImage');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $responseArr  = array();
        if(!empty($id)){

            $i =0;
            $noteImages=$this->NoteImage->find('all', array('conditions'=>array('NoteImage.customer_history_id'=>$id, 'NoteImage.delete_image_status'=>1)));
           
            if(isset($noteImages) && !empty($noteImages)){
                foreach ($noteImages as $key => $value) {
                    $responseArr['note_image'][$i]['id'] = $value['NoteImage']['id'];
                    $responseArr['note_image'][$i]['image'] = $value['NoteImage']['image'];
                    $responseArr['note_image'][$i]['posted_by_image'] = $value['NoteImage']['employee_image'];
                    $responseArr['note_image'][$i]['posted_by_name'] = $value['NoteImage']['employee_name'];
                    $responseArr['note_image'][$i]['employee_id'] =  $value['NoteImage']['employee_id'];
                    $responseArr['note_image'][$i]['posted_by_date'] = date('D, M d',strtotime($value['NoteImage']['created']));
                    $responseArr['note_image'][$i]['delete_status'] = $value['NoteImage']['delete_image_status'];
                    if($value['NoteImage']['delete_image_status']=='1'){
                    	$responseArr['note_image'][$i]['deleted_employee_id'] =  $value['NoteImage']['deleted_employee_id'];
                    	$responseArr['note_image'][$i]['deleted_by_name'] = $value['NoteImage']['deleted_employee_name'];
                    	$responseArr['note_image'][$i]['deleted_by_image'] = $value['NoteImage']['deleted_employee_image'];
                    	$responseArr['note_image'][$i]['deleted_by_date'] = date('D, M d',strtotime($value['NoteImage']['modified']));
                    }
                    $i++;
                }
            }
            $jsonEncode = json_encode($responseArr);
       
        }else{

            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->NoteImage->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_deleted_images";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


     /**************************************************************************
     * NAME: get_exist_note
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
    function get_exist_note($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel('CustomerHistory');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $date = isset($decoded['date']) ? $decoded['date'] : '';
        $responseArr  = array();
        if(!empty($date) && !empty($customer_id)){

            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date, 'CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));
            if(isset($customerAnalysisData['CustomerHistory']['id']) && !empty($customerAnalysisData['CustomerHistory']['id'])){
                $responseArr['id'] = $customerAnalysisData['CustomerHistory']['id'];
                $responseArr['note_text'] = $customerAnalysisData['CustomerHistory']['note_text'];
                $responseArr['note_image'] = $customerAnalysisData['CustomerHistory']['note_image'];
                $responseArr['service_price'] = json_decode($customerAnalysisData['CustomerHistory']['service_price']);
                $i =0;
                if(isset($customerAnalysisData['CustomerHistory']['note_image']) && !empty($customerAnalysisData['CustomerHistory']['note_image'])){
                    $responseArr['note_image'][$i] = $customerAnalysisData['CustomerHistory']['note_image'];
                    $i++;
                }
                $noteImages=$this->NoteImage->find('all', array('conditions'=>array('NoteImage.customer_history_id'=>$id)));
                if(isset($noteImages) && !empty($noteImages)){
                    foreach ($noteImages as $key => $value) {
                        $responseArr['note_image'][$i] = $value['NoteImage']['image'];
                        $i++;
                    }
                }
            }else{
               $responseArr = array('status' => 'success', 'msg'=> 'No record found.' );     
            }
            $jsonEncode = json_encode($responseArr);
       
        }else{

            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_exist_note";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }



    /****************************************************************************************************************************************
     * NAME: delete_employee
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function delete_note_image($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('NoteImage');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $employee_id = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        if(!empty($id)){
            $noteImages = array();
            $noteImages['NoteImage']['delete_image_status'] =  '1';
            $noteImages['NoteImage']['id'] = $id;
            $noteImages['NoteImage']['deleted_employee_id'] = $employee_id;
            $noteImages['NoteImage']['deleted_employee_name'] = $this->get_employee_name($employee_id);
            $noteImages['NoteImage']['deleted_employee_image'] = $this->get_employee_image($employee_id);
            if(empty($noteImages['NoteImage']['deleted_employee_name'])){
            	$noteImages['NoteImage']['deleted_employee_name'] = $this->get_user_name($user_id);
            }

            if($this->NoteImage->saveAll($noteImages)){
                $responseArr = array('status' => 'success', 'msg' => 'Note Image deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Note Image deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Note Image does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->NoteImage->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_note_image";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 



/************************************ Employee Section ******************************/ 





     /**************************************************************************
     * NAME: add_employee
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/



     function add_employee($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

         $this->loadModel("User");
         $this->loadModel("Employee");
         $employee_id ='';
        if(isset($decoded['id']) && !empty($decoded['id'])){
            $employee_id = $employee['Employee']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        }
        $email = isset($decoded['email']) ? $decoded['email'] : '';
         $employeeExist = $employeeCodeExist= array();
        if(!empty($employee_id)){
            $employeeExist = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$employee_id)));
        }   
        if(isset($employeeExist['Employee']['id']) && !empty($employeeExist['Employee']['id'])){
            $employee['Employee']['id'] = $employeeExist['Employee']['id'];
            $empCode = $employeeExist['Employee']['emp_code'];
            $emp_role_id = $employeeExist['Employee']['role_id'];
           
        }else{
             $empCode = $employee['Employee']['emp_code'] = $this->RandomString();
             $employeeEmailExist = $this->Employee->find('first', array('conditions'=>array('Employee.email'=>$email)));
             $emp_role_id =  isset($decoded['role_id']) ? $decoded['role_id'] : '';
        }
     
        if(isset($employeeEmailExist['Employee']['id']) && !empty($employeeEmailExist['Employee']['id'])){
            $responseArr = array('status' => 'error', 'msg' => 'This Email already used by other emplyee.'  );
            $jsonEncode = json_encode($responseArr);
        }else{

            $responseArr =array();
            $employee['Employee']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
            
            $employee['Employee']['name'] =isset($decoded['name']) ? $decoded['name'] : '';
            $employee['Employee']['email'] =isset($decoded['email']) ? $decoded['email'] : '';
            $employee['Employee']['dob'] = isset($decoded['dob']) ? $decoded['dob'] : '';
            $employee['Employee']['phone'] = isset($decoded['phone']) ? $decoded['phone'] : '';
            $employee['Employee']['joining_date'] = isset($decoded['joining_date']) ? $decoded['joining_date'] : '';
            $employee['Employee']['designation'] = isset($decoded['designation']) ? $decoded['designation'] : '';
            $employee['Employee']['salary'] = isset($decoded['salary']) ? $decoded['salary'] : '';
            $employee['Employee']['address'] = isset($decoded['address']) ? $decoded['address'] : '';
            $employee['Employee']['image'] = isset($decoded['image']) ? $decoded['image'] : '';
            $employee['Employee']['status'] = isset($decoded['status']) ? $decoded['status'] : '1';
            $role_id = $employee['Employee']['role_id'] = isset($decoded['role_id']) ? $decoded['role_id'] : '';
            $employee['Employee']['role_title'] = isset($decoded['role_title']) ? $decoded['role_title'] : '';
            $getUserData = $this->User->find('first', array('conditions'=>array('User.id'=>$decoded['user_id'])));
            if(isset($getUserData['User']['user_emp_code']) && !empty($getUserData['User']['user_emp_code'])){
	            if($role_id =='1'){
	                 $responseArr['msg'] = 'Owner already add as employee.';
	                 $responseArr['status'] = 'error';
	                 $jsonEncode = json_encode($responseArr);
	                 echo  $jsonEncode;exit();
	            }
	            if($emp_role_id == '1' && $role_id != '1'){
	            	$updateUserData = array();
                    $updateUserData['User']['id'] = $user_id;
                    $updateUserData['User']['user_emp_code'] = '';
                    $this->User->saveAll($updateUserData);
	            }

            }
           
            if($this->Employee->saveAll($employee)){
                $employee_id = $this->Employee->id;
                $user_id = $decoded['user_id'];
                if($role_id=='1'){
                    $userData = array();
                    $userData['User']['id'] = $user_id;
                    $userData['User']['user_emp_code'] = $empCode;
                    $this->User->saveAll($userData);
                     $responseArr['msg'] = 'Owner add as employee successfully.';
                
                    

                }

                $responseArr['user_id'] = $user_id;
                $responseArr['employee_id'] = $employee_id;
                $responseArr['status'] = 'success';
               
                $jsonEncode = json_encode($responseArr);
                
            }else{
                $responseArr = array('status' => 'error' );
                $jsonEncode = json_encode($responseArr);
            }
        }


        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_employee";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

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


    /**************************************************************************
     * NAME: employee_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function employee_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("Employee");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $i=0;
        if(!empty($user_id)){
            $data = $this->Employee->find('all',array('conditions'=>
                                                    array( 'Employee.user_id'=>$user_id),
                                                    'order' => array('Employee.id' => 'DESC')
                                                    ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'please enter valid user id.'));
            }else{

                if(!empty($data)){
                    
                    foreach ($data as $key => $value) {
                        $customerData['Employee'][$i] = $value['Employee'];
                        $i++;
                    }

                }else{
                    $customerData[$i]['Employee']['msg'] = 'No Record Found.';
                    $customerData[$i]['Employee']['status'] = 'error';
                }
                $jsonEncode = json_encode($customerData);
            }
        }else{
            $customerData[$i]['Employee']['msg'] = 'No record Found.';
            $customerData[$i]['Employee']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "employee_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

 /****************************************************************************************************************************************
     * NAME: delete_employee
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function delete_employee($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('Employee');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->Employee->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => 'Employee deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Employee deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Employee does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_employee";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 



    /**************************************************************************
     * NAME: add_attendance
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:

     *
     *********************************************************************/



     function add_attendance($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Employee");
        $this->loadModel("Attendance");
         
        $emp_code = isset($decoded['emp_code']) ? $decoded['emp_code'] : '';
        $emp_time = isset($decoded['date_time']) ? $decoded['date_time'] : '';
        $date = isset($decoded['date']) ? $decoded['date'] : '';
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
                        $attendance['Attendance']['status'] =1;
                        if($this->Attendance->saveAll($attendance)){
                            $attendance_id = $this->Attendance->id;
                            $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';;
                            $responseArr = array('user_id' => $user_id, 'attendance_id' => $attendance_id, 'status' => 'success', 'msg' =>'従業員は正常にチェックアウトされます。' );
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
                        $attendance['Attendance']['status'] =0;
                        if($this->Attendance->saveAll($attendance)){
                            $attendance_id = $this->Attendance->id;
                            $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';;
                            $responseArr = array('user_id' => $user_id, 'attendance_id' => $attendance_id, 'status' => 'success', 'msg' =>'従業員のチェックインが正常に完了しました。' );
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
        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_attendance";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }


    /**************************************************************************
     * NAME: attendance_info
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function attendance_info($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("Attendance");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $employee_id = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $i=0;
        if(!empty($user_id) && !empty($employee_id)){
            $data = $this->Attendance->find('all',array('conditions'=>
                                                    array( 'Attendance.user_id'=>$user_id, 'Attendance.employee_id'=>$employee_id),
                                                    'order' => array('Attendance.id' => 'DESC')
                                                    ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'please enter valid employee code.'));
            }else{

                if(!empty($data)){
                    foreach ($data as $key => $value) {
                        $customerData['Attendance'][$i]['date']= date("d M Y", strtotime($value['Attendance']['date']));
                        if($value['Attendance']['checkin_time']=='NULL' || $value['Attendance']['checkin_time']=='0000-00-00 00:00:00' ||  empty($value['Attendance']['checkin_time']) ){
                            $customerData['Attendance'][$i]['checkin_time'] = '';
                        }else{
                            $checkInDateTime = $value['Attendance']['checkin_time'];
                            $checkInTime = date('h:i A', strtotime($checkInDateTime));
                            $customerData['Attendance'][$i]['checkin_time'] = $checkInTime;
                        }
                        if($value['Attendance']['checkout_time']=='NULL' || $value['Attendance']['checkout_time']=='0000-00-00 00:00:00' || empty($value['Attendance']['checkout_time']) ){
                            $customerData['Attendance'][$i]['checkout_time'] = '';
                        }else{
                            $checkuotDateTime = $value['Attendance']['checkout_time'];
                            $checkOutTime = date('h:i A', strtotime($checkuotDateTime));
                            $customerData['Attendance'][$i]['checkout_time'] = $checkOutTime;
                        }
                        
                        if(!empty($customerData['Attendance'][$i]['checkin_time']) && !empty($customerData['Attendance'][$i]['checkout_time'])){
                            $start = new DateTime($value['Attendance']['checkin_time']);
                            $end = new DateTime($value['Attendance']['checkout_time']);
                            $interval = $start->diff($end);
                            $duration =  $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
                            $customerData['Attendance'][$i]['duration'] = $duration;
                        }else{
                            $customerData['Attendance'][$i]['duration'] = '';
                        }
                        

                        $i++;
                    }


                }else{
                    $customerData[$i]['Attendance']['msg'] = 'No Record Found.';
                    $customerData[$i]['Attendance']['status'] = 'error';
                }
                $jsonEncode = json_encode($customerData);
            }
        }else{
            $customerData[$i]['Attendance']['msg'] = 'please enter valid employee code.';
            $customerData[$i]['Attendance']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Attendance->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "employee_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


 /**************************************************************************
     * NAME: attendance_status
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function attendance_status($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Employee");
        $this->loadModel("Attendance");
         
        $emp_code = isset($decoded['emp_code']) ? $decoded['emp_code'] : '';
        $date = isset($decoded['date']) ? $decoded['date'] : '';
        if(!empty($emp_code)){
            $employeeCode = $this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$emp_code),'fields'=>array('Employee.emp_code','Employee.id')));
            if(isset($employeeCode['Employee']['id']) && !empty($employeeCode['Employee']['id'])){
                $attendanceTouchId = $this->Attendance->find('first', array('conditions'=>array('Attendance.emp_code'=>$emp_code, 'Attendance.status'=>1, 'Attendance.date'=>$date),'fields'=>array('Attendance.emp_code')));
                if(isset($attendanceTouchId['Attendance']['emp_code']) && !empty($attendanceTouchId['Attendance']['emp_code'])){
                    $responseArr = array('status' => 'success',  'msg' => 'あなたはすでに今日チェックアウトしました。',  'attendance_status' => '3', 'emp_code' => $emp_code);
                    $jsonEncode = json_encode($responseArr);
                }else{

                    $attendanceTouchIdcheckIn = $this->Attendance->find('first', array('conditions'=>array('Attendance.emp_code'=>$emp_code, 'Attendance.status'=>0, 'Attendance.date'=>$date)));
                  
                    if(isset($attendanceTouchIdcheckIn['Attendance']['emp_code']) && !empty($attendanceTouchIdcheckIn['Attendance']['emp_code'])){
                        $responseArr = array('status' => 'success',  'msg' => 'Check Out as Employee.',  'attendance_status' => '2', 'emp_code' => $emp_code);
                        $jsonEncode = json_encode($responseArr);
                    
                    }else{
                        $responseArr = array('status' => 'success',  'msg' => 'Check In as Employee.',  'attendance_status' => '1', 'emp_code' => $emp_code);
                        $jsonEncode = json_encode($responseArr);
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

        
        $log = $this->Attendance->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "attendance_status";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }



    /**************************************************************************
     * NAME: add_holiday
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function add_holiday($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Holiday");
        $id =isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
        	$holiday['Holiday']['id'] =isset($decoded['id']) ? $decoded['id'] : '';
        }

        $holiday['Holiday']['user_id'] =isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $holiday['Holiday']['title'] =isset($decoded['title']) ? $decoded['title'] : '';
        $holiday['Holiday']['date'] =isset($decoded['date']) ? $decoded['date'] : '';
        if($this->Holiday->saveAll($holiday)){
        	$holiday_id = $this->Holiday->id;
            $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';;
            $responseArr = array('user_id' => $user_id, 'holiday_id' => $holiday_id, 'status' => 'success', 'msg' =>'休日を追加する' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        } 
        

        
        $log = $this->Holiday->getDataSource()->getLog(false, false);
        $recordData['Holiday']['name'] = "add_holiday";
        $recordData['Holiday']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }

     /**************************************************************************
     * NAME: holiday_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function holiday_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("Holiday");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $i=0;
        if(!empty($user_id)){
            $data = $this->Holiday->find('all',array('conditions'=>
                                                    array( 'Holiday.user_id'=>$user_id),
                                                    'order' => array('Holiday.id' => 'DESC')
                                                    ));
          
            $holidayData = array();
            if(!empty($data)){
            	foreach ($data as $key => $value) {
            		$holidayData['Holiday'][$key] = $value['Holiday'];
            		$i++;
            	}
               
            }else{
                $holidayData[$i]['Holiday']['msg'] = 'No Record Found.';
                $holidayData[$i]['Holiday']['status'] = 'error';
            }
         	 $jsonEncode = json_encode($holidayData);
            
        }else{
            $holidayData[$i]['Holiday']['msg'] = 'please enter user detail.';
            $holidayData[$i]['Holiday']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Holiday->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "holiday_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


/****************************************************************************************************************************************
     * NAME: delete_holiday
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function delete_holiday($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('Holiday');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->Holiday->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => 'Holiday deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Holiday deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Holiday id undefind.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Holiday->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_holiday";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 

    /****************************************************************************************************************************************
     * NAME: monthly_attendance
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function monthly_attendance($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 

        $this->loadModel('User');
        $this->loadModel('Holiday');
        $this->loadModel("Employee");
        $this->loadModel("Attendance");
         
        $employee_id = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $startDate = isset($decoded['start_date']) ? $decoded['start_date'] : '';
        $endDate = isset($decoded['end_date']) ? $decoded['end_date'] : '';
        //$month = isset($decoded['month']) ? $decoded['month'] : '';
        //$year = isset($decoded['year']) ? $decoded['year'] : '';

        $monthStartDate = $this->User->find('first', array('conditions'=>array('User.id'=>$user_id),'fields'=>array('User.month_start_date','User.id','User.weekend')));
        if(isset($monthStartDate['User']['month_start_date']) && !empty($monthStartDate['User']['month_start_date'])){
        	$day = $monthStartDate['User']['month_start_date'];
            $weekendDay = $monthStartDate['User']['weekend'];
        }else{
        	$day = 1;	
            $weekendDay = 'Tue';
        }
        /*
        $nextMonthDate = $month + 1;
        $startTime = strtotime($year."-".$month."-".$day);
        $endTime = strtotime($year."-".$nextMonthDate."-".$day); 
        $startDate =date('Y-m-d',$startTime);
        $endDate =  date('Y-m-d',$endTime);

        
       
        $totalDays=cal_days_in_month(CAL_GREGORIAN,$month,$year);

        $now = time(); 
		$your_date = strtotime($year."-".$month."-".$day);
		$datediff = $now - $your_date;
        $totalDays  = round($datediff / (60 * 60 * 24));
        if($totalDays > $allDays){
			$totalDays = $allDays; 
			$endDate =  date('Y-m-d',$now);
            $endTime = time();

		}
        */
        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);

        $datediff = $endTime - $startTime;
        $totalDays  = round($datediff / (60 * 60 * 24));

        $weekEndDays =0;
        for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
            $days = date('N', $i);
            if($days ==$weekendDay){
                $weekEndDays++;
            }

        }



       	$holidayDays = $this->Holiday->find('count', array('conditions'=> array( 'Holiday.user_id'=>$user_id, 'Holiday.date >= ' => $startDate,'Holiday.date <= ' => $endDate)));


       
        if(!empty($employee_id)){
            $employeeCode = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$employee_id),'fields'=>array('Employee.emp_code','Employee.id')));
            if(isset($employeeCode['Employee']['id']) && !empty($employeeCode['Employee']['id'])){
            	$presentDays = $this->Attendance->find('count', array('conditions'=> array( 'Attendance.employee_id'=>$employee_id, 'Attendance.date >= ' => $startDate,'Attendance.date < ' => $endDate)));
            	$apsentDays =  $totalDays - ($presentDays + $holidayDays + $weekEndDays );
            	$attendanceRecord = array();
                if($totalDays > 0){
                    $attendanceRecord['Attendance']['total'] = $totalDays;
                    $attendanceRecord['Attendance']['present'] = $presentDays;
                    $attendanceRecord['Attendance']['apsent'] = $apsentDays;
                    $attendanceRecord['Attendance']['holiday'] = $holidayDays + $weekEndDays;
                
                }else{
                    $attendanceRecord['Attendance']['total'] = "0";
                    $attendanceRecord['Attendance']['present'] = "0";
                    $attendanceRecord['Attendance']['apsent'] = "0";
                    $attendanceRecord['Attendance']['holiday'] = "0";
                }

            	

                $jsonEncode = json_encode($attendanceRecord);

            }else{
                $responseArr = array('status' => 'error',  'msg' => 'Employee does not exist.' );
                $jsonEncode = json_encode($responseArr);

            }

        }else{
            $responseArr = array('status' => 'error',  'msg' => 'Please enter employee does not exist.' );
            $jsonEncode = json_encode($responseArr);
        }
       
        $log = $this->Attendance->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "total_persent";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 



     /****************************************************************************************************************************************
     * NAME: get_user_id
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
    function get_user_id($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 

        $this->loadModel("User");
        $this->loadModel("Employee");
         
        $emp_code = isset($decoded['emp_code']) ? $decoded['emp_code'] : '';
       
        if(!empty($emp_code)){
            $employeeCode = $this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$emp_code),'fields'=>array('Employee.emp_code','Employee.user_id','Employee.name','Employee.image','Employee.role_title','Employee.role_id','Employee.id')));

            if(isset($employeeCode['Employee']['user_id']) && !empty($employeeCode['Employee']['user_id']) ){
                $responseArr['Employee']['status'] = 'success';
                $responseArr['Employee']['msg'] = 'Employee detail is correct.';
                $responseArr['Employee']['employee_id'] = $employeeCode['Employee']['id'];
                $responseArr['Employee']['employee_name'] = $employeeCode['Employee']['name'];
                if(isset($employeeCode['Employee']['image']) && !empty($employeeCode['Employee']['image']) && $employeeCode['Employee']['user_id']!='null')
                	$responseArr['Employee']['employee_image'] = $employeeCode['Employee']['image'];
                else
                	$responseArr['Employee']['employee_image'] = '';
                $responseArr['Employee']['user_id'] = $employeeCode['Employee']['user_id'];
                $responseArr['Employee']['role_id'] = $employeeCode['Employee']['role_id'];
                $userData = $this->User->find('first', array('conditions'=>array('User.id'=>$employeeCode['Employee']['user_id']),'fields'=>array('User.customer_pin_number','User.employee_pin_number','User.id')));

                if(isset($userData['User']['customer_pin_number']) && isset($userData['User']['customer_pin_number'])){
                    $responseArr['Employee']['customer_pin_number'] = $userData['User']['customer_pin_number'];
                    $responseArr['Employee']['employee_pin_number'] = $userData['User']['employee_pin_number'];
                }else{
                    $responseArr['Employee']['customer_pin_number'] = '';
                    $responseArr['Employee']['customer_pin_number'] = '';
                }
                if(!empty($employeeCode['Employee']['role_title']) )
            	   $responseArr['Employee']['role_title'] = $employeeCode['Employee']['role_title'];
                else
                    $responseArr['Employee']['role_title'] = '';
            	$responseArr['Employee']['emp_code'] = $emp_code;
            }else{
                $responseArr['Employee']['status'] = 'error';
                $responseArr['Employee']['msg'] = 'This employee does not exist.';
              
            	
            }
            $jsonEncode = json_encode($responseArr);
      
        }else{
            $responseArr['Employee']['status'] = 'error';
            $responseArr['Employee']['msg'] = 'Please add employee code.';
            $jsonEncode = json_encode($responseArr);
        }
       
        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_user_id";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 






 
    
}
