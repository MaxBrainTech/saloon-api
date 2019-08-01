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
class WebServicesv10Controller extends AppController{
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
       // App::import('Vendor', 'Google', array('file' => 'Google' . DS . 'autoload.php'));
    
        
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
        $user['User']['name'] = isset($decoded['name']) ? $decoded['name'] : '';
        //$user['User']['company_name'] = isset($decoded['company_name']) ? $decoded['company_name'] : '';
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
        $user['User']['lunch_time_start'] = isset($decoded['lunch_time_start']) ? $decoded['lunch_time_start'] : '';
        $user['User']['lunch_time_end'] = isset($decoded['lunch_time_end']) ? $decoded['lunch_time_end'] : '';

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
     * NAME: get_customer
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
    
    
      function get_customer($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Customer");
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $i=0;
        if(!empty($user_id)){
             $this->Customer->bindModel(array('belongsTo' => array('Salon')));
             $data = $this->Customer->find('first',array('conditions'=> array( 'Customer.id'=>$id)));
            if(empty($data)){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'お客様は存在しません。', 'msg1'=> 'Customer does not exist.'));
            }else{
                $customerData['Customer'] = $data['Customer'];
                $customerData['Customer']['salon_name'] = $data['Salon']['salon_name'];
                $customerData['Customer']['service_name'] = $this->get_service_name($data['Customer']['service_id']);
                $jsonEncode = json_encode($customerData);
            }
        }else{
            $customerData[$i]['Customer']['msg1'] = 'お客様は存在しません。';
             $customerData[$i]['Customer']['msg'] = 'Customer does not exist.';
            $customerData[$i]['Customer']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_customer";
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
                                                    array( 'Customer.user_id'=>$user_id, 'Customer.status'=>Configure::read('App.Status.active')),
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
     * NAME: customer_search
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
    
    
      function customer_search($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Salon");
        $this->loadModel("Customer");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $keyword = isset($decoded['keyword']) ? $decoded['keyword'] : '';
        $start_date = isset($decoded['start_date']) ? $decoded['start_date'] : '';
        $end_date = isset($decoded['end_date']) ? $decoded['end_date'] : '';
        $sort_by = isset($decoded['sort_by']) ? $decoded['sort_by'] : '';
        $i=0;
        if(!empty($user_id) ){

            $conditions["Customer.user_id"] = $user_id;
           // $conditions["Customer.status"] = Configure::read('App.Status.active');
            if(!empty($end_date) && !empty($start_date)){
                $conditions["Customer.modified BETWEEN ? and ?"] = array($start_date, $end_date);
            }
            
            $conditions["OR"]['Customer.name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.first_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.last_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.kana_first_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.kana_last_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.kana_first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.kana_last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.kana_first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.kana_last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.kana_last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.tel LIKE'] = "%".$keyword."%";
           // print_r($conditions);die;

            $this->Customer->bindModel(array('hasMany' => array('NoteService','NoteProduct', 'CustomerHistory' )));
            $data = $this->Customer->find('all',array(
                        'conditions' => $conditions,
	                    'order' => array('Customer.modified' => 'DESC')
                    ));
           // print_r($data);die;
            if($sort_by == '1' || $sort_by == '2' || $sort_by == '3' || $sort_by == '4'){ 
                $allCustomerData = $customershortData= array(); 
                if($sort_by == '1' || $sort_by == '2'){
                    foreach ($data as $customerKey => $customerValue) {

                        $serviceTotalPrice = $productTotalPrice = $grandTotalPrice =  0;
                        if(isset($customerValue['NoteService'])){
                            foreach ($customerValue['NoteService'] as $serviceKey => $serviceValue) {
                               $servicePrice = $this->priceChangeInt($serviceValue['service_price']); 
                               $serviceTotalPrice = ($serviceTotalPrice + $servicePrice);

                              // $customerAnalysisData['NoteService'][]['service_total_price'] =$serviceTotalPrice;
                            }
                        }
                        if(isset($customerValue['NoteProduct'])){
                            foreach ($customerValue['NoteProduct'] as $productKey => $productValue) {
                               $productPrice = $this->priceChangeInt($productValue['sale_price']); 
                               $productTotalPrice = ($productTotalPrice + $productPrice);
                            }
                        }
                        $grandTotalPrice = ($serviceTotalPrice + $productTotalPrice);
                        $customershortData[$customerValue['Customer']['id']] = $grandTotalPrice;
                        $date = date('M jS Y g:i A', strtotime($customerValue['Customer']['modified']));
                        $allCustomerData[$customerValue['Customer']['id']]['id'] = $customerValue['Customer']['id'];
                        $allCustomerData[$customerValue['Customer']['id']]['name'] = $customerValue['Customer']['last_name']." ".$customerValue['Customer']['first_name'];
                        $allCustomerData[$customerValue['Customer']['id']]['date'] = $date;
                    }
                    
                }    
                if($sort_by == '3' || $sort_by == '4'){

                    foreach ($data as $customerKey => $customerValue) {
                        $countmostComingCustomer = 0;
                        if(isset($customerValue['CustomerHistory'])){

                            $countmostComingCustomer = count($customerValue['CustomerHistory']);
                            $customershortData[$customerValue['Customer']['id']] = $countmostComingCustomer;
                            $date = date('M jS Y g:i A', strtotime($customerValue['Customer']['modified']));
                            $customer_id = $customerValue['Customer']['id']; 
                            $allCustomerData[$customer_id]['id'] = $customerValue['Customer']['id'];
                            $allCustomerData[$customer_id]['name'] = $customerValue['Customer']['last_name']." ".$customerValue['Customer']['first_name'];
                            $allCustomerData[$customer_id]['date'] = $date;
                        }
                    }    
                    
                }
               
                if($sort_by == '1' || $sort_by == '3' ){
                    arsort($customershortData);
                }else if($sort_by == '2' || $sort_by == '4'){
                    asort($customershortData);
                }
                
                $i= 0;
                foreach ($customershortData as $key => $customerShortValue){ 
                    $customerData['Customer'][$i]['id'] = $allCustomerData[$key]['id'];
                    $customerData['Customer'][$i]['name'] = $allCustomerData[$key]['name'];
                    $customerData['Customer'][$i]['date'] = $allCustomerData[$key]['date'];
                    $i++;
                }
            }else{
                if(!empty($data)){
                $i= 0;
                    foreach ($data as $key => $value){ 

                        $customerData['Customer'][$i]['id'] = $value['Customer']['id'];
                        $date = date('M jS Y g:i A', strtotime($value['Customer']['modified']));
                        $customerData['Customer'][$i]['name'] = $value['Customer']['last_name']." ".$value['Customer']['first_name'];
                        $customerData['Customer'][$i]['date'] = $date;
                        $i++;
                    }
                }else{
                    $customerData[$i]['Customer'] = array();
                }
            }    
            $jsonEncode = json_encode($customerData);
           
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
     * NAME: customer_suggestion
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
    
    
      function customer_suggestion($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Salon");
        $this->loadModel("Customer");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $keyword = isset($decoded['keyword']) ? $decoded['keyword'] : '';
        $i=0;
        if(!empty($user_id) ){

            $conditions["Customer.user_id"] = $user_id;
           // $conditions["Customer.status"] = Configure::read('App.Status.active');
            
            $conditions["OR"]['Customer.name'] = $keyword;
            $conditions["OR"]['Customer.first_name'] = $keyword;
            $conditions["OR"]['Customer.last_name'] = $keyword;
            $conditions["OR"]['Customer.kana_first_name'] = $keyword;
            $conditions["OR"]['Customer.kana_last_name'] = $keyword;
            $conditions["OR"]['Customer.name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.first_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.last_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.kana_first_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.kana_last_name LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.kana_first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['lower(Customer.kana_last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.kana_first_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.kana_last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['upper(Customer.kana_last_name) LIKE'] = "%".$keyword."%";
            $conditions["OR"]['Customer.tel LIKE'] = "%".$keyword."%";




            $data = $this->Customer->find('all',array(
                        'conditions' => $conditions,
	                       /* array(
							    "Customer.user_id" => $user_id,
							    'Customer.status'=>Configure::read('App.Status.active'),
							    "OR" => array(
							        array('lower(Customer.name) LIKE' => "%".$keyword."%"),
							        array('lower(Customer.first_name) LIKE' => "%".$keyword."%"),
							        array('lower(Customer.last_name) LIKE' => "%".$keyword."%"),
							        array('lower(Customer.kana_first_name) LIKE' => "%".$keyword."%"),
							        array('lower(Customer.kana_last_name) LIKE' => "%".$keyword."%"),
							        array('Customer.tel LIKE' => "%".$keyword."%"),
							        array('Customer.modified ' => date('Y-m-d', strtotime($keyword)) )
							    )
							), */
                        'order' => array('Customer.modified' => 'DESC')
                    ));

            //$log = $this->Customer->getDataSource()->getLog(false, false);
            //print_r($log);die;


        	if(!empty($data)){
            
            foreach ($data as $key => $value) {

                $customerData['Customer'][$i]['id'] = $value['Customer']['id'];
                $customerData['Customer'][$i]['name'] = $value['Customer']['name'];
               // $customerData['Customer'][$i] = $value['Customer'];
               // $customerData['Customer'][$i]['salon_name'] = $value['Salon']['salon_name'];
             //   $customerData['Customer'][$i]['service_name'] = $this->get_service_name($value['Customer']['service_id']);
                $i++;
            }

	        }else{
	        	$customerData[$i]['Customer'] = array();
	           // $customerData[$i]['Customer']['msg1'] = 'No Record Found.';
	           // $customerData[$i]['Customer']['status'] = 'error';
	        }
        	$jsonEncode = json_encode($customerData);
           
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
            $data = $this->Customer->find('first',array('conditions'=> array( 'Customer.id'=>$customer_id, 'Customer.status'=>Configure::read('App.Status.active')),'order' => array('Customer.id' => 'DESC') ));
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
            $data = $this->Customer->find('first',array('conditions'=>  array( 'Customer.id'=>$customer_id, 'Customer.status'=>Configure::read('App.Status.active') )));
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
                    $this->loadModel("Facial");
                    $service = $this->Facial->find('first',array('conditions'=>  array('Facial.id'=>$customer_service_id)));
                    $allData['Service'] =  $service['Facial'];
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
                                                          'Customer.status'=>Configure::read('App.Status.active')
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
        $data = $this->Service->find('all', array('conditions' => array('Service.status' =>Configure::read('App.Status.active'))));
        $jsonEncode = json_encode($data);
        $log = $this->Service->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "service_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


/**************************************************************************
     * NAME: service_color_list
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
    
    
     function service_color_list(){
        
        $this->loadModel("Service");
        $this->loadModel("ServiceColor");
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';

        $serviceColorData = array();
        if(!empty($user_id)){
            $this->ServiceColor->bindModel(array('belongsTo' => array('Service','Color')));
            $allServiceColorData = $this->ServiceColor->find('all', array('conditions' => array('ServiceColor.user_id' => $user_id), 'order' =>array('ServiceColor.service_id' => 'asc')));
            if(isset($allServiceColorData[0]['ServiceColor']) && !empty($allServiceColorData[0]['ServiceColor'])){
                foreach ($allServiceColorData as $key => $value) {
                   $serviceColorData['ServiceColor'][$key]['id'] = $value['ServiceColor']['id'];
                   $serviceColorData['ServiceColor'][$key]['service_id'] = $value['ServiceColor']['service_id'];
                   $serviceColorData['ServiceColor'][$key]['service_name'] = $value['Service']['name'];
                   $serviceColorData['ServiceColor'][$key]['color_code'] = $value['Color']['color_code'];
                }

            }else{
                 $data = $this->Service->find('all');
                  foreach ($data as $key => $value) {
                    $serviceColorData['ServiceColor'][$key]['id'] = $value['Service']['id'];
                    $serviceColorData['ServiceColor'][$key]['service_id'] = $value['Service']['id'];
                    $serviceColorData['ServiceColor'][$key]['service_name'] = $value['Service']['name'];
                    $serviceColorData['ServiceColor'][$key]['color_code'] = $value['Service']['color_code'];
                 }
            }
        }else{
            $data = $this->Service->find('all');
            foreach ($data as $key => $value) {
                $serviceColorData['ServiceColor'][$key]['id'] = $value['Service']['id'];
                $serviceColorData['ServiceColor'][$key]['service_id'] = $value['Service']['id'];
                $serviceColorData['ServiceColor'][$key]['service_name'] = $value['Service']['name'];
                $serviceColorData['ServiceColor'][$key]['color_code'] = $value['Service']['color_code'];
             }
        }


       
        $jsonEncode = json_encode($serviceColorData);
        $log = $this->Service->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "service_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }



    /**************************************************************************
     * NAME: color_list
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
    
    
     function color_list(){
        
        $this->loadModel("Color");
        $data = $this->Color->find('all');
        $colorData = array();
        foreach ($data as $key => $value) {
           $colorData['Color'][$key]['id'] = $value['Color']['id'];
           $colorData['Color'][$key]['color_name'] = $value['Color']['color_name'];
           $colorData['Color'][$key]['color_code'] = $value['Color']['color_code'];
        }
        $jsonEncode = json_encode($colorData);
        echo  $jsonEncode;exit();
    }

     /**************************************************************************
     * NAME: add_service_color
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
    
    
     function add_service_color(){
        
        $this->loadModel("ServiceColor");
         $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $responseArr = array();
        
        $user_id = $serviceColor['ServiceColor']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $service_id = $serviceColor['ServiceColor']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
       // $serviceColor['ServiceColor']['service_name'] = isset($decoded['service_name']) ? $decoded['service_name'] : '';
        $serviceColor['ServiceColor']['color_id'] = isset($decoded['color_id']) ? $decoded['color_id'] : '';
       // $serviceColor['ServiceColor']['color_name'] = isset($decoded['color_name']) ? $decoded['color_name'] : '';
        //$serviceColor['ServiceColor']['color_code'] = isset($decoded['color_code']) ? $decoded['color_code'] : '';

        $ServiceColorData = $this->ServiceColor->find('first',array('conditions'=> array( 'ServiceColor.user_id'=>$user_id, 'ServiceColor.service_id'=>$service_id)));
        if(isset($ServiceColorData['ServiceColor']['id'])){
            $serviceColor['ServiceColor']['id'] = $ServiceColorData['ServiceColor']['id'];
        }


        if($this->ServiceColor->saveAll($serviceColor)){
              
            $service_color_id = $this->ServiceColor->id;
            $responseArr = array('service_color_id' => $service_color_id, 'user_id' => $user_id, 'status' => 'success', 'msg' => 'サービスの色を正常に追加。', 'msg1' => 'Service Color add successfully.' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }

        echo  $jsonEncode;exit();
    }

    /**************************************************************************
     * NAME: add_reservation_status
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
    
    
     function add_reservation_status(){
        
        $this->loadModel("Reservation");
         $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $responseArr = array();
        
       // $reservationStatus['Reservation']['id']  = isset($decoded['id']) ? $decoded['id'] : '';
       // $user_id = $reservationStatus['Reservation']['user_id']  = isset($decoded['user_id']) ? $decoded['user_id'] : '';
       // $reservationStatus['Reservation']['employee_id']  = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $status =  isset($decoded['status']) ? $decoded['status'] : '';
        $reservation_id =  $this->Reservation->id = isset($decoded['id']) ? $decoded['id'] : '';
        if($this->Reservation->saveField('status', $status)){
            
            $responseArr = array('reservation_id' => $reservation_id, 'ongoing' => $status, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }

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
            $customerDetail = $this->Customer->find('first', array('conditions' => array('Customer.tel' => $tel, 'Customer.status'=>Configure::read('App.Status.active')), 'order' => array('Customer.id' => 'DESC')));
            
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
            $customerDetail = $this->Customer->find('first', array('conditions' => array('Customer.tel' => $tel, 'Customer.status'=>Configure::read('App.Status.active')), 'order' => array('Customer.id' => 'DESC')));
            
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
     * NAME: facial_service           
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

     function facial_service($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("Customer");
        $this->loadModel("Facial");

        $facial['Facial']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $facial['Facial']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $facial['Facial']['status'] =isset($decoded['status']) ? $decoded['status'] : '';
        $facial['Facial']['status_text'] = isset($decoded['status_text']) ? $decoded['status_text'] : '';
        $facial['Facial']['allegy'] = isset($decoded['allegy']) ? $decoded['allegy'] : '';
        $facial['Facial']['allegy_text'] = isset($decoded['allegy_text']) ? $decoded['allegy_text'] : '';
        $facial['Facial']['medicine'] = isset($decoded['medicine']) ? $decoded['medicine'] : '';
        $facial['Facial']['medicine_text'] = isset($decoded['medicine_text']) ? $decoded['medicine_text'] : '';
        $facial['Facial']['hospital'] = isset($decoded['hospital']) ? $decoded['hospital'] : '';
        $facial['Facial']['hospital_text'] = isset($decoded['hospital_text']) ? $decoded['hospital_text'] : '';
        $facial['Facial']['medical_history'] = isset($decoded['medical_history']) ? $decoded['medical_history'] : '';
        $facial['Facial']['medical_history_text'] = isset($decoded['medical_history_text']) ? $decoded['medical_history_text'] : '';
        $facial['Facial']['period'] = isset($decoded['period']) ? $decoded['period'] : '';
        $facial['Facial']['experience_of_birth'] = isset($decoded['experience_of_birth']) ? $decoded['experience_of_birth'] : '';
        $facial['Facial']['bowel_movement'] = isset($decoded['bowel_movement']) ? $decoded['bowel_movement'] : '';
        $facial['Facial']['sleep_start_time'] = isset($decoded['sleep_start_time']) ? $decoded['sleep_start_time'] : '';
        $facial['Facial']['sleep_time_avg'] = isset($decoded['sleep_time_avg']) ? $decoded['sleep_time_avg'] : '';
        $facial['Facial']['concern'] = isset($decoded['concern']) ? $decoded['concern'] : '';
        $facial['Facial']['concern_extra'] = isset($decoded['concern_extra']) ? $decoded['concern_extra'] : '';
        $facial['Facial']['concern_date'] = isset($decoded['concern_date']) ? $decoded['concern_date'] : '';
        $facial['Facial']['itchy'] = isset($decoded['itchy']) ? $decoded['itchy'] : '';
        $facial['Facial']['itchy_text'] = isset($decoded['itchy_text']) ? $decoded['itchy_text'] : '';
        $facial['Facial']['cosmetic_name'] = isset($decoded['cosmetic_name']) ? $decoded['cosmetic_name'] : '';
        

        if($this->Facial->saveAll($facial)){
            $facial_id = $this->Facial->id;
            $customer_id = $decoded['customer_id'];
            $responseArr = array('customer_id' => $customer_id, 'facial_id' => $facial_id, 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);

            $customerData['Customer']['id'] = $customer_id;
            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Facial->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "facial_service";
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

    function get_service_id($name = ''){
        $this->loadModel("Service");
        if(!empty($name)){
            $data = $this->Service->find('first',array('conditions'=> array('Service.name'=>$name )));
            if(isset($data['Service']['id'])){
                $employee_name = $data['Service']['id'];
            }else{
                $employee_name = '0';
            }
            return  $employee_name;
        }else{
            return '0';
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
                $i =0;
                $product_price = isset($decoded['product_price']) ? $decoded['product_price'] : '';

                foreach ($product_price as $productKey => $productValue) {
                   $productArray[$i]['payment_type'] = $productValue['payment_type'];
                   $productArray[$i]['product_name'] = $productValue['product_name'];
                   $productArray[$i]['purchase_price'] = $productValue['purchase_price'];
                   $productArray[$i]['sales_price'] = $productValue['sales_price'];
                   $productArray[$i]['quantity'] = $productValue['quantity'];
                   $productArray[$i]['staff'] = $productValue['staff'];
                    $i++;
                }
                $customerHistory['CustomerHistory']['product_price'] = json_encode($productArray);
                
            }else{
                $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                $customer_id  = $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $service_price = isset($decoded['service_price']) ? $decoded['service_price'] : '';
                $product_price = isset($decoded['product_price']) ? $decoded['product_price'] : '';
                $customerHistory['CustomerHistory']['service_price'] = json_encode($service_price);
                $customerHistory['CustomerHistory']['product_price'] = json_encode($product_price);
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
            $product_price = isset($decoded['product_price']) ? $decoded['product_price'] : '';
            $customerHistory['CustomerHistory']['product_price'] = json_encode($product_price);
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
     * NAME: add_note
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

    function add_note($test_data = null){
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
        //$service_status = isset($decoded['service_status']) ? $decoded['service_status'] : '';
        
        if(!empty($employee_id ) && $employee_id  != 'null'){
            $emp_name = $this->get_employee_name($employee_id);
            $emp_image =    $this->get_employee_image($employee_id);

        }else{
             $userExist=$this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
             if(isset($userExist['User']['user_emp_code']) && !empty($userExist['User']['user_emp_code'])){
                $empExist=$this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$userExist['User']['user_emp_code']))); 
                if(isset($empExist['Employee']['id']) && !empty($empExist['Employee']['id'])){
                    $emp_name = $this->get_employee_name($empExist['Employee']['id']);
                    $emp_image =    $this->get_employee_image($empExist['Employee']['id']);
                }else{
                    $emp_name = $userExist['User']['user_emp_code'];
                    $emp_image =    '';
                }

             }else{
                $emp_name = $userExist['User']['user_emp_code'];
                $emp_image =    '';
             }  
        }
        $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date, 'CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));

        /********Start Note Images add********/
        $noteImages = array();
        $note_image = isset($decoded['note_image']) ? $decoded['note_image'] : '';
        
        if(isset($note_image) && !empty($note_image)){
            if(isset($customerAnalysisData['CustomerHistory']['id']) && !empty($customerAnalysisData['CustomerHistory']['id'])){
                $noteImages['NoteImage']['customer_history_id'] = $customerAnalysisData['CustomerHistory']['id'];
               
            }else{
                $customerHistory =array();
                $customerHistory['CustomerHistory']['user_id'] = $user_id;
                $customerHistory['CustomerHistory']['customer_id'] =  isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $customerHistory['CustomerHistory']['date'] =  isset($decoded['date']) ? $decoded['date'] : '';
                $this->CustomerHistory->saveAll($customerHistory);
                $id = $this->CustomerHistory->id;
            }
            $noteImages['NoteImage']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
            $noteImages['NoteImage']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
            $emp_id = $noteImages['NoteImage']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
            $noteImages['NoteImage']['employee_name'] = $emp_name;
            $noteImages['NoteImage']['employee_image'] = $emp_image;
            //$noteImages['NoteImage']['customer_history_id'] = $customer_analysis_id;
            $noteImages['NoteImage']['image'] = $note_image;
            if($this->NoteImage->saveAll($noteImages)){
                 $note_image_id = $this->NoteImage->id; 

                /********Customer Modified date update ********/
                $customerData['Customer']['id'] = $customer_id;
                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
                $responseArr = array('note_image_id' => $note_image_id, 'msg'=>'顧客情報が正常に追加されました。',  'msg1'=>'Note image  successfully added.', 'status' => 'success' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error' );
                $jsonEncode = json_encode($responseArr);
            } 
            
        }



        /********Start Note Text add********/
        $customerHistory = array();
        $note_text = isset($decoded['note_text']) ? $decoded['note_text'] : '';
        if(isset($note_text) && !empty($note_text)){
            
            if(isset($customerAnalysisData['CustomerHistory']['id']) && !empty($customerAnalysisData['CustomerHistory']['id'])){
                $customerHistory['CustomerHistory']['id'] = $customerAnalysisData['CustomerHistory']['id'];
               
            }else{

                $customerHistory =array();
                $date = isset($decoded['date']) ? $decoded['date'] : '';
                $customerHistory['CustomerHistory']['user_id'] =  $user_id;
                $customerHistory['CustomerHistory']['customer_id'] =  isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $customerHistory['CustomerHistory']['date'] =  $date;
                $this->CustomerHistory->saveAll($customerHistory);
                $customerHistory['CustomerHistory']['id'] = $this->CustomerHistory->id;
            }
            $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
            $customer_id  = $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';   
            $customerHistory['CustomerHistory']['date'] = $customerAnalysisData['CustomerHistory']['date'];
            $customerHistory['CustomerHistory']['note_text'] = $note_text;
            if($this->CustomerHistory->saveAll($customerHistory)){

                $customer_analysis_id = $this->CustomerHistory->id; 

                /********Customer Modified date update ********/

                $customerData['Customer']['id'] = $customer_id;
                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
                $responseArr = array('customer_analysis_id' => $customer_analysis_id, 'msg'=>'顧客情報が正常に追加されました。',  'msg1'=>'Note successfully added.', 'status' => 'success' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error' );
                $jsonEncode = json_encode($responseArr);
            }    

        }    
       

         
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_note";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


/**************************************************************************
     * NAME: get_note
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
    
    
    function get_note($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel('CustomerHistory');
        $this->loadModel('NoteService');
        $this->loadModel('NoteProduct');
        $this->loadModel('NoteImage');
        $this->loadModel('Product');
        $this->loadModel('User');
        $this->loadModel('Employee');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $date = isset($decoded['date']) ? $decoded['date'] : '';
        $responseArr  = array();
        if(empty($id)){
            $customerHistory =array();
            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.user_id'=>$user_id,'CustomerHistory.customer_id'=>$customer_id,'CustomerHistory.date'=>$date)));
            if(isset($customerAnalysisData['CustomerHistory']['id'])){
                $id = $customerAnalysisData['CustomerHistory']['id'];
            }else{
                $customerHistory['CustomerHistory']['user_id'] = $user_id;
                $customerHistory['CustomerHistory']['customer_id'] =  isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $customerHistory['CustomerHistory']['date'] =  isset($decoded['date']) ? $decoded['date'] : '';
                $this->CustomerHistory->saveAll($customerHistory);
                $id = $this->CustomerHistory->id;
            }
        }

        if(!empty($id)){

            $this->CustomerHistory->bindModel(array('hasMany' => array('NoteService', 'NoteProduct', 'NoteImage'=> array(
            'className' => 'NoteImage',
            'conditions' => array('NoteImage.delete_image_status' => '0'),
            'dependent' => true
        ))));
            //$this->NoteProduct->bindModel(array('belongsTo' => array('Product')));
            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$id)));
            $note_text = $customerAnalysisData['CustomerHistory']['note_text'];
            $customerAnalysisData['CustomerHistory'] =array();
            $customerAnalysisData['CustomerHistory']['id'] =$id;
            $customerAnalysisData['CustomerHistory']['note_text'] =$note_text;
            $serviceTotalPrice = $productTotalPrice = $grandTotalPrice =  0;
            if(isset($customerAnalysisData['NoteService'])){
                foreach ($customerAnalysisData['NoteService'] as $serviceKey => $serviceValue) {
                   $servicePrice = $this->priceChangeInt($serviceValue['service_price']); 
                   $serviceTotalPrice = ($serviceTotalPrice + $servicePrice);

                  // $customerAnalysisData['NoteService'][]['service_total_price'] =$serviceTotalPrice;
                }
            }
            if(isset($customerAnalysisData['NoteProduct'])){
                foreach ($customerAnalysisData['NoteProduct'] as $productKey => $productValue) {
                   $getProduct=$this->Product->find('first', array('conditions'=>array('Product.id'=>$productValue['product_id']))); 
                   if(isset($getProduct['Product']['product_stock'])){
                        $customerAnalysisData['NoteProduct'][$productKey]['product_stock'] = $getProduct['Product']['product_stock'];
                   }
                   $productPrice = $this->priceChangeInt($productValue['sale_price']); 
                   $productTotalPrice = ($productTotalPrice + $productPrice);
                }
            }
            $grandTotalPrice = ($serviceTotalPrice + $productTotalPrice);

            $customerAnalysisData['CustomerHistory']['service_total_price'] =number_format($serviceTotalPrice).'円';
            $customerAnalysisData['CustomerHistory']['product_total_price'] =number_format($productTotalPrice).'円';
            $customerAnalysisData['CustomerHistory']['grand_total_price'] =number_format($grandTotalPrice).'円';

            $jsonEncode = json_encode($customerAnalysisData);
       
        }else{

            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_note";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

    function priceChangeInt($price = null){
        if($price != null){
            $price = str_replace("円", "", $price);
            $price = str_replace(",", "", $price);
            return (int)$price;
        }else{
            return 0;
        }
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
        }
        $decoded = json_decode($data, true); 
        
        
        $this->loadModel('CustomerHistory');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $responseArr  = array();
        if(!empty($user_id) && !empty($customer_id)){
            $customerAnalysisData=$this->CustomerHistory->find('all', array('conditions'=>array('CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id),'order' => array('CustomerHistory.id' => 'DESC')));




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


    function get_service_data($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        
        
        $this->loadModel('NoteService');
        $this->loadModel('CustomerHistory');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $responseArr  = array();
        if(!empty($user_id)){
            $customerAnalysisData=$this->CustomerHistory->find('all', array('conditions'=>array('CustomerHistory.user_id'=>$user_id),'order' => array('CustomerHistory.id' => 'ASC')));



            foreach ($customerAnalysisData as $key => $value) {
                $service_price = json_decode($value['CustomerHistory']['service_price']);
                foreach ($service_price as $servicekey => $servicevalue) {
                   $noteService['NoteService']['user_id'] = $user_id;
                   $noteService['NoteService']['customer_id'] = $value['CustomerHistory']['customer_id'];
                   $noteService['NoteService']['customer_history_id'] = $value['CustomerHistory']['id'];
                   if(isset($servicevalue->customer_service_id)){
                        $noteService['NoteService']['customer_service_id'] = $servicevalue->customer_service_id;
                   }else{
                        $noteService['NoteService']['customer_service_id'] = '0';
                   }
                   if(isset($servicevalue->service_id)){
                        $noteService['NoteService']['service_id'] = $servicevalue->service_id;
                   }else{
                        $noteService['NoteService']['service_id'] = '0';
                   }
                   

                   if(isset($servicevalue->service)){
                        $noteService['NoteService']['service_name'] = $servicevalue->service;
                   }else{
                        $noteService['NoteService']['service_name'] = '';
                   }


                   if(isset($servicevalue->price)){
                        $noteService['NoteService']['service_price'] = $servicevalue->price;
                   }else{
                        $noteService['NoteService']['service_price'] = '';
                   }
                   
                   if(isset($servicevalue->payment)){
                        $noteService['NoteService']['payment_type'] = $servicevalue->payment;
                   }else{
                        $noteService['NoteService']['payment_type'] = '';
                   }

                   if(isset($servicevalue->employee_name)){
                        $noteService['NoteService']['employee_name'] = $servicevalue->employee_name;
                   }else{
                        $noteService['NoteService']['employee_name'] = '';
                   }
                   if(isset($servicevalue->employee_id)){
                        $noteService['NoteService']['employee_id'] = $servicevalue->employee_id;
                   }else{
                        $noteService['NoteService']['employee_id'] = '0';
                   }
                   //print_r( $noteService);die;
                   $this->NoteService->saveAll($noteService);
                }

              
            }
            $responseArr = array('status' => 'success' );
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
            $responseArr['product_price'] = json_decode($customerAnalysisData['CustomerHistory']['product_price']);
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
     * NAME: get_deleted_images
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
     * NAME: add_note_service
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


     function add_note_service($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        
        
        $this->loadModel('Customer');
        $this->loadModel('CustomerHistory');
        $this->loadModel('NoteService');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $date = isset($decoded['date']) ? $decoded['date'] : '';
        $customer_history_id = isset($decoded['customer_history_id']) ? $decoded['customer_history_id'] : '';
        $customerHistory =array();
        if(empty($customer_history_id)){
            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date, 'CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));
            if(isset($customerAnalysisData['CustomerHistory']['id'])){
                $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];
            }else{
                $customerHistory['CustomerHistory']['user_id'] =  $user_id;
                $customerHistory['CustomerHistory']['customer_id'] =  isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $customerHistory['CustomerHistory']['date'] =  $date;
                $this->CustomerHistory->saveAll($customerHistory);
                $customer_history_id = $this->CustomerHistory->id;
            }
        }
      
        $noteService['NoteService']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $noteService['NoteService']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $noteService['NoteService']['customer_history_id'] = $customer_history_id;
        $noteService['NoteService']['customer_service_id'] = isset($decoded['customer_service_id']) ? $decoded['customer_service_id'] : '0';
        $noteService['NoteService']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $noteService['NoteService']['service_name'] = isset($decoded['service_name']) ? $decoded['service_name'] : '';
        $noteService['NoteService']['status'] = Configure::read('App.Status.active');

        if($this->NoteService->saveAll($noteService)){
            $note_service_id = $this->NoteService->id;
            $responseArr = array('note_service_id' => $note_service_id, 'msg'=>'ノートサービスを正常に追加します。', 'msg1'=>'add note service successfully.', 'status' => 'success' );
           
               $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }   
        
        $log = $this->NoteService->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_note_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    } 

/****************************************************************************************************************************************
     * NAME: edit_note_service
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


     function edit_note_service($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        
        
        $this->loadModel('Customer');
        $this->loadModel('NoteService');
        
        $noteService['NoteService']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        
        $noteService['NoteService']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $noteService['NoteService']['employee_name'] = isset($decoded['employee_name']) ? $decoded['employee_name'] : '';
        $noteService['NoteService']['service_price'] = isset($decoded['service_price']) ? $decoded['service_price'] : '';
        $noteService['NoteService']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $noteService['NoteService']['status'] = Configure::read('App.Status.active');

        if($this->NoteService->saveAll($noteService)){
            $note_service_id = $this->NoteService->id;
            $responseArr = array('note_service_id' => $note_service_id, 'msg'=>'ノートサービスを正常に編集します。', 'msg1'=>'edit note service successfully.', 'status' => 'success' );
           
               $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }   
        
        $log = $this->NoteService->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "edit_note_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    } 

/****************************************************************************************************************************************
     * NAME: delete_note_service
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
    function delete_note_service($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('NoteService');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->NoteService->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => 'メモサービスが正常に削除されました。', 'msg1' => 'Note Service deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => '注意サービスは存在しません。', 'msg1' => 'Note Service deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => '注意サービスは存在しません。', 'msg1' => 'Note Service does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->NoteService->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_note_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 

  


    /****************************************************************************************************************************************
     * NAME: add_note_product
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


     function add_note_product($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        
        
        $this->loadModel('Customer');
        $this->loadModel('NoteProduct');
        $this->loadModel('CustomerHistory');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $date = isset($decoded['date']) ? $decoded['date'] : '';
        $customer_history_id = isset($decoded['customer_history_id']) ? $decoded['customer_history_id'] : '';
        $customerHistory =array();
        if(empty($customer_history_id)){
            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date, 'CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));
            if(isset($customerAnalysisData['CustomerHistory']['id'])){
                $customer_history_id = $customerAnalysisData['CustomerHistory']['id'];
            }else{
                $customerHistory['CustomerHistory']['user_id'] =  $user_id;
                $customerHistory['CustomerHistory']['customer_id'] =  isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                $customerHistory['CustomerHistory']['date'] =  $date;
                $this->CustomerHistory->saveAll($customerHistory);
                $customer_history_id = $this->CustomerHistory->id;
            }
        }
        $noteProduct['NoteProduct']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $noteProduct['NoteProduct']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $noteProduct['NoteProduct']['customer_history_id'] = $customer_history_id;
        $noteProduct['NoteProduct']['product_id'] = isset($decoded['product_id']) ? $decoded['product_id'] : '0';
        $noteProduct['NoteProduct']['product_name'] = isset($decoded['product_name']) ? $decoded['product_name'] : '';
        //$noteProduct['NoteProduct']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        //$noteProduct['NoteProduct']['service_name'] = isset($decoded['service_name']) ? $decoded['service_name'] : '';
        //$noteProduct['NoteProduct']['employee_name'] = isset($decoded['employee_name']) ? $decoded['employee_name'] : '';
        // $noteProduct['NoteProduct']['service_price'] = isset($decoded['service_price']) ? $decoded['service_price'] : '';
       // $noteProduct['NoteProduct']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $noteProduct['NoteProduct']['status'] = Configure::read('App.Status.active');

        if($this->NoteProduct->saveAll($noteProduct)){
            $note_product_id = $this->NoteProduct->id;
            $responseArr = array('note_product_id' => $note_product_id, 'msg'=>'ノート製品を正常に追加します。', 'msg1'=>'add note product successfully.', 'status' => 'success' );
           
               $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }   
        
        $log = $this->NoteProduct->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_note_product";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    } 

        /****************************************************************************************************************************************
     * NAME: edit_note_product
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


     function edit_note_product($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        
        
        $this->loadModel('Customer');
        $this->loadModel('Product');
        $this->loadModel('NoteProduct');
        $this->loadModel('CustomerHistory');
        $id = $noteProduct['NoteProduct']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        $noteProduct['NoteProduct']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $noteProduct['NoteProduct']['employee_name'] = isset($decoded['employee_name']) ? $decoded['employee_name'] : '';
        $noteProduct['NoteProduct']['sale_price'] = isset($decoded['sale_price']) ? $decoded['sale_price'] : '';
        $product_quantity = $noteProduct['NoteProduct']['product_quantity'] = isset($decoded['product_stock']) ? $decoded['product_stock'] : '';
        $noteProduct['NoteProduct']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $noteProduct['NoteProduct']['status'] = Configure::read('App.Status.active');
        if($product_quantity > 0){
            $this->NoteProduct->bindModel(array('belongsTo' => array('Product')));
            $getNoteProduct = $this->NoteProduct->find('first', array('conditions'=>array('NoteProduct.id'=>$id))); 
            if(isset($getNoteProduct['Product']['product_stock'])){
                if($product_quantity > $getNoteProduct['Product']['product_stock']){
                   
                    $responseArr = array('msg' => '製品は在庫切れです。', 'msg1' => 'Product is out of stock.',  'status' => 'error' );
                    $jsonEncode = json_encode($responseArr);
                    echo  $jsonEncode;exit();
                }
            }else{
                $responseArr = array('msg' => '製品は在庫切れです。', 'msg1' => 'Product is out of stock1.',  'status' => 'error' );
                $jsonEncode = json_encode($responseArr);
                echo  $jsonEncode;exit();
            } 
        }else{
            $responseArr = array('msg' => '製品は在庫切れです。', 'msg1' => 'Product is out of stock2.',  'status' => 'error' );
            $jsonEncode = json_encode($responseArr);
            echo  $jsonEncode;exit();
        }
       // print_r($noteProduct);die;
        if($this->NoteProduct->saveAll($noteProduct)){
            $note_product_id = $this->NoteProduct->id;

            if(isset($getNoteProduct['Product']['product_stock'])){
                if($product_quantity <= $getNoteProduct['Product']['product_stock']){
                    $total_stock = ($getNoteProduct['Product']['product_stock'] - $product_quantity);
                    $this->Product->id = $getNoteProduct['Product']['id'];
                    $this->Product->saveField('product_stock' , $total_stock);
                }
            } 
            $responseArr = array('note_product_id' => $note_product_id, 'msg'=>'ノート製品を正常に追加します。', 'msg1'=>'edit note product successfully.', 'status' => 'success' );
           
               $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }   
        
        $log = $this->NoteProduct->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_note_product";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    } 

      /****************************************************************************************************************************************
     * NAME: delete_note_product
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
    function delete_note_product($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('NoteProduct');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->NoteProduct->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => '注製品が正常に削除されました。', 'msg1' => 'Note Product deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => '注製品は存在しません。', 'msg1' => 'Note Product deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => '注製品は存在しません。', 'msg1' => 'Note Product does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->NoteProduct->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_note_product";
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
	            if($emp_role_id != '1' && $role_id =='1'){
	                 $responseArr['msg'] = 'Owner is already added in salon.';
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
        for ($i = 0; $i < 10; $i++) {
            $randstring.= $characters[rand(0, strlen($characters))];
        }
        $this->loadModel("Employee");
        $employeeCodeExist = $this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$randstring)));
        if(isset($employeeCodeExist['Employee']['id']) && !empty($employeeCodeExist['Employee']['id'])){
            $this->RandomString();
        }
        return $randstring;
    }

    function ReservationNumberString()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 1; $i++) {
            $randstring.= @$characters[rand(0, strlen($characters))];
        }

        $characters = '0123456789';
        for ($i = 1; $i < 10; $i++) {
            $randstring.= @$characters[rand(0, strlen($characters))];
        }
        
        $this->loadModel("Reservation");
        $reservationCodeExist = $this->Reservation->find('first', array('conditions'=>array('Reservation.reservation_number'=>$randstring)));
        if(isset($reservationCodeExist['Reservation']['id']) && !empty($reservationCodeExist['Reservation']['id'])){
            $this->ReservationNumberString();
        }
        return $randstring;
    }

     /**************************************************************************
     * NAME: add_employee_lunch_time
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

/*

     function add_employee_lunch_time($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("User");
        $this->loadModel("Employee");
     
        
        $employee['Employee']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        $employee['Employee']['start_lunch_time'] = isset($decoded['start_lunch_time']) ? $decoded['start_lunch_time'] : '';
        $employee['Employee']['end_lunch_time'] =isset($decoded['end_lunch_time']) ? $decoded['end_lunch_time'] : '';
        $responseArr =array();
        if($this->Employee->saveAll($employee)){
            $employee_id = $this->Employee->id;
            $responseArr['employee_id'] = $employee_id;
            $responseArr['status'] = 'success';
            $responseArr['msg'] = 'Employee lunch time add succesEully.'; 
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
   
        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_employee_lunch_time";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    } */



     function add_employee_lunch_time($test_data =null){

        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel("User");
        $this->loadModel("Employee");
     
        
        $employee['Employee']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        $employee['Employee']['lunch_time'] = isset($decoded['lunch_time']) ? $decoded['lunch_time'] : '';
        $responseArr =array();
        if($this->Employee->saveAll($employee)){
            $employee_id = $this->Employee->id;
            $responseArr['employee_id'] = $employee_id;
            $responseArr['status'] = 'success';
            $responseArr['msg'] = 'Employee lunch time add succesEully.'; 
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
   
        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_employee_lunch_time";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

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


 /****************************************************************************************************************************************
     * NAME: deactive_employee
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
    function deactive_employee($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('Employee');
        $this->Employee->id = $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->Employee->saveField('status' , Configure::read('App.Status.inactive') )){
                $responseArr = array('status' => 'success', 'msg' => 'Employee deactived successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Employee deactived error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Employee does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Employee->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "deactive_employee";
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
                        $attendance['Attendance']['lunch_time'] = $employeeCode['Employee']['lunch_time'];
                        $attendance['Attendance']['start_lunch_time'] = $employeeCode['Employee']['start_lunch_time'];
                        $attendance['Attendance']['end_lunch_time'] = $employeeCode['Employee']['end_lunch_time'];
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
                        $attendance['Attendance']['lunch_time'] = $employeeCode['Employee']['lunch_time'];
                        $attendance['Attendance']['start_lunch_time'] = $employeeCode['Employee']['start_lunch_time'];
                        $attendance['Attendance']['end_lunch_time'] = $employeeCode['Employee']['end_lunch_time'];
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
    
    
      function attendance_info_old8($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Employee");
        $this->loadModel("Attendance");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $employee_id = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $i=0;
        if(!empty($user_id) && !empty($employee_id)){
            $data = $this->Attendance->find('all',array('conditions'=>
                                                    array( 'Attendance.user_id'=>$user_id, 'Attendance.employee_id'=>$employee_id),
                                                    'order' => array('Attendance.date' => 'DESC')
                                                    ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'please enter valid employee code.'));
            }else{

                if(!empty($data)){
                    $getEmployeeData = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$employee_id)));
                    $emp_start_lunch_time = $getEmployeeData['Employee']['start_lunch_time'];
                    $emp_end_lunch_time = $getEmployeeData['Employee']['end_lunch_time'];

                    $start_lunch_time = strtotime($emp_start_lunch_time);
                    $end_lunch_time = strtotime($emp_end_lunch_time);

                    $interval_lunch_time =  $end_lunch_time - $start_lunch_time;
                  
                    if(!empty($interval_lunch_time)){
                        $hours_lunch_time   = floor(($interval_lunch_time) / 3600);
                        $minutes_lunch_time = floor(($interval_lunch_time - ($hours_lunch_time * 3600))/60);
                        if(!empty($hours_lunch_time))
                            $lunch_time = $hours_lunch_time." Hours ".$minutes_lunch_time." Minutes";
                        else
                            $lunch_time = $minutes_lunch_time." Minutes";
                        $interval_lunch_time = $interval_lunch_time/60;
                        
                    }
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

                            $att_start_lunch_time = strtotime($value['Attendance']['start_lunch_time']);
                            $att_end_lunch_time = strtotime($value['Attendance']['end_lunch_time']);

                            $att_interval_lunch_time =  $att_start_lunch_time - $att_end_lunch_time;
                            if($att_interval_lunch_time > 0)
                                $interval_lunch_time = $att_interval_lunch_time/60;


                            $start = new DateTime($value['Attendance']['checkin_time']);
                            $end = new DateTime($value['Attendance']['checkout_time']);
                           
                            $interval = $start->diff($end);
                            $duration =  $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
                            $endCheckInTime = date("H:i",strtotime($customerData['Attendance'][$i]['checkin_time']));
                            $endCheckOutTime = date("H:i",strtotime($customerData['Attendance'][$i]['checkout_time']));
                            $to_time = strtotime($customerData['Attendance'][$i]['checkin_time']);
                            $from_time = strtotime($customerData['Attendance'][$i]['checkout_time']);
                            $total_time = round(abs($to_time - $from_time) / 60,2);
                            $total_hours   = floor(($total_time) / 60);
                            $total_minutes =  floor(($total_time - ($total_hours * 60)));
                            $duration =  $total_hours." Hours ".$total_minutes." Minutes";

                            if(!empty($interval_lunch_time)){

                                if(($start_lunch_time < strtotime($endCheckOutTime)) && ($end_lunch_time > strtotime($endCheckInTime))) {
                                    $working_time = $total_time - $interval_lunch_time;
                                    $working_hours   = floor(($working_time) / 60);
                                    $working_minutes =  floor(($working_time - ($working_hours * 60)));
                                    $customerData['Attendance'][$i]['working_hour'] = $working_hours." Hours ".$working_minutes." Minutes";
                                    $customerData['Attendance'][$i]['lunch_hour'] = $lunch_time;
                                   
                                }else{

                                    $customerData['Attendance'][$i]['working_hour'] = $duration;
                                    $customerData['Attendance'][$i]['lunch_hour'] = "";
                                }
                                

                            }else{
                                $customerData['Attendance'][$i]['lunch_hour'] = "";
                                $customerData['Attendance'][$i]['working_hour'] = $duration;
                            }    

                                
                            $customerData['Attendance'][$i]['total_hour'] = $duration;
                        }else{
                            $customerData['Attendance'][$i]['working_hour'] = "";
                            $customerData['Attendance'][$i]['lunch_hour'] = "";
                            $customerData['Attendance'][$i]['total_hour'] = '';
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



    function attendance_info($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Employee");
        $this->loadModel("Attendance");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $employee_id = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $i=0;
        if(!empty($user_id) && !empty($employee_id)){
            $data = $this->Attendance->find('all',array('conditions'=>
                                                    array( 'Attendance.user_id'=>$user_id, 'Attendance.employee_id'=>$employee_id),
                                                    'order' => array('Attendance.date' => 'DESC')
                                                    ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'please enter valid employee code.'));
            }else{

                if(!empty($data)){
                    $getEmployeeData = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$employee_id)));
                    $emp_lunch_time = $getEmployeeData['Employee']['lunch_time'];
                   /* $emp_start_lunch_time = $getEmployeeData['Employee']['start_lunch_time'];
                    $emp_end_lunch_time = $getEmployeeData['Employee']['end_lunch_time'];

                    $start_lunch_time = strtotime($emp_start_lunch_time);
                    $end_lunch_time = strtotime($emp_end_lunch_time);

                    $interval_lunch_time =  $end_lunch_time - $start_lunch_time;
                  
                    if(!empty($interval_lunch_time)){
                        $hours_lunch_time   = floor(($interval_lunch_time) / 3600);
                        $minutes_lunch_time = floor(($interval_lunch_time - ($hours_lunch_time * 3600))/60);
                        if(!empty($hours_lunch_time))
                            $lunch_time = $hours_lunch_time." Hours ".$minutes_lunch_time." Minutes";
                        else
                            $lunch_time = $minutes_lunch_time." Minutes";
                        $interval_lunch_time = $interval_lunch_time/60;
                        
                    } */
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
/*
                            $att_start_lunch_time = strtotime($value['Attendance']['start_lunch_time']);
                            $att_end_lunch_time = strtotime($value['Attendance']['end_lunch_time']);

                            $att_interval_lunch_time =  $value['Attendance']['lunch_time'];

*/
                            $att_interval_lunch_time =  $value['Attendance']['lunch_time'];

                            if($att_interval_lunch_time > 0)
                                $interval_lunch_time = $att_interval_lunch_time;
                            else
                                $interval_lunch_time = $emp_lunch_time;


                            $start = new DateTime($value['Attendance']['checkin_time']);
                            $end = new DateTime($value['Attendance']['checkout_time']);
                           
                            $interval = $start->diff($end);
                            $duration =  $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
                            $endCheckInTime = date("H:i",strtotime($customerData['Attendance'][$i]['checkin_time']));
                            $endCheckOutTime = date("H:i",strtotime($customerData['Attendance'][$i]['checkout_time']));
                            $to_time = strtotime($customerData['Attendance'][$i]['checkin_time']);
                            $from_time = strtotime($customerData['Attendance'][$i]['checkout_time']);
                            $total_time = round(abs($to_time - $from_time) / 60,2);
                            $total_hours   = floor(($total_time) / 60);
                            $total_minutes =  floor(($total_time - ($total_hours * 60)));
                            $duration =  $total_hours." Hours ".$total_minutes." Minutes";

                            if(!empty($interval_lunch_time)){

                                $working_time = $total_time - $interval_lunch_time;
                                if($working_time > 0){
                                    $working_hours   = floor(($working_time) / 60);
                                    $working_minutes =  floor(($working_time - ($working_hours * 60)));

                                    $lunch_hours   = floor(($interval_lunch_time) / 60);
                                    $lunch_minutes =  floor(($interval_lunch_time));

                                    $customerData['Attendance'][$i]['working_hour'] = $working_hours." Hours ".$working_minutes." Minutes";
                                  //  $customerData['Attendance'][$i]['lunch_hour'] = $lunch_hours." Hours ".$lunch_minutes." Minutes";
                                    $customerData['Attendance'][$i]['lunch_hour'] = $interval_lunch_time." Minutes";
                                
                                }else{
                                    $customerData['Attendance'][$i]['working_hour'] = "0 Hours 0 Minutes";
                                    $customerData['Attendance'][$i]['lunch_hour'] = "0 Hours 0 Minutes";
                                }

                            }else{
                                $customerData['Attendance'][$i]['lunch_hour'] = "";
                                $customerData['Attendance'][$i]['working_hour'] = $duration;
                            }    

                                
                            $customerData['Attendance'][$i]['total_hour'] = $duration;
                        }else{
                            $customerData['Attendance'][$i]['working_hour'] = "";
                            $customerData['Attendance'][$i]['lunch_hour'] = "";
                            $customerData['Attendance'][$i]['total_hour'] = '';
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
     * Description: Register a user with .t
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
    function monthly_attendance_old($test_data = null){
        
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
            $employeeCode = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$employee_id),'fields'=>array('Employee.emp_code','Employee.id','Employee.start_lunch_time','Employee.end_lunch_time')));
            $emp_start_lunch_time = $employeeCode['Employee']['start_lunch_time'];
            $emp_end_lunch_time = $employeeCode['Employee']['end_lunch_time'];  
            $start_lunch_time = strtotime($emp_start_lunch_time);
            $end_lunch_time = strtotime($emp_end_lunch_time);
            $interval_lunch_time =  $end_lunch_time - $start_lunch_time; 
            if(!empty($interval_lunch_time)){
                $hours_lunch_time   = floor(($interval_lunch_time) / 3600);
                $minutes_lunch_time = floor(($interval_lunch_time - ($hours_lunch_time * 3600))/60);
            } 
            if(isset($employeeCode['Employee']['id']) && !empty($employeeCode['Employee']['id'])){
            	$presentDays = $this->Attendance->find('count', array('conditions'=> array( 'Attendance.employee_id'=>$employee_id, 'Attendance.date >= ' => $startDate,'Attendance.date < ' => $endDate)));

                $data = $this->Attendance->find('all',array('conditions'=> array( 'Attendance.user_id'=>$user_id, 'Attendance.employee_id'=>$employee_id, 'Attendance.date >= ' => $startDate,'Attendance.date <= ' => $endDate),
                                                    'order' => array('Attendance.date' => 'DESC')));
                $attendanceRecord = array();
                 if(!empty($data)){
                   $i = 0;
                   $total_time = '';

                    foreach ($data as $key => $value) {

                        if($value['Attendance']['checkin_time']=='NULL' || $value['Attendance']['checkin_time']=='0000-00-00 00:00:00' ||  empty($value['Attendance']['checkin_time']) ){
                            $customerData['Attendance']['checkin_time'] = '';
                        }else{
                            $checkInDateTime = $value['Attendance']['checkin_time'];
                            $checkInTime = date('h:i A', strtotime($checkInDateTime));
                            $customerData['Attendance']['checkin_time'] = $checkInTime;
                        }
                        if($value['Attendance']['checkout_time']=='NULL' || $value['Attendance']['checkout_time']=='0000-00-00 00:00:00' || empty($value['Attendance']['checkout_time']) ){
                            $customerData['Attendance']['checkout_time'] = '';
                        }else{
                            $checkuotDateTime = $value['Attendance']['checkout_time'];
                            $checkOutTime = date('h:i A', strtotime($checkuotDateTime));
                            $customerData['Attendance']['checkout_time'] = $checkOutTime;
                        }
                        $att_start_lunch_time = strtotime($value['Attendance']['start_lunch_time']);
                        $att_end_lunch_time = strtotime($value['Attendance']['end_lunch_time']);

                        $att_interval_lunch_time =  $att_start_lunch_time - $att_end_lunch_time;
                        if($att_interval_lunch_time > 0){
                            $interval_lunch_time = $att_interval_lunch_time/60;
                            $start_lunch_time = $att_start_lunch_time;
                            $end_lunch_time = $att_end_lunch_time;

                        }

                        if(!empty($interval_lunch_time)){
                            $hours_lunch_time   = floor(($interval_lunch_time) / 60);
                            $minutes_lunch_time = floor(($interval_lunch_time - ($hours_lunch_time * 3600))/60);
                        } 


                        if(!empty($customerData['Attendance']['checkin_time']) && !empty($customerData['Attendance']['checkout_time'])){
                            $endCheckInTime = date("H:i:s",strtotime($value['Attendance']['checkin_time']));
                            $endCheckOutTime = date("H:i:s",strtotime($value['Attendance']['checkout_time']));
                             
                            $to_time = strtotime($value['Attendance']['checkin_time']);
                            $from_time = strtotime($value['Attendance']['checkout_time']);
                            if(!empty($interval_lunch_time) && ($start_lunch_time < strtotime($endCheckOutTime)) && ($end_lunch_time > strtotime($endCheckInTime))) {
                                $total_time +=  $from_time - $to_time;
                                $i++;
                            }else{
                                $total_time += $from_time - $to_time ;
                            }

                        

                        }
                    }
                    $interval_lunch_time = $interval_lunch_time*$i;
                    if($interval_lunch_time > 0){
                        $interval_lunch_time_hours   = floor(($interval_lunch_time) / 3600);
                         if($interval_lunch_time_hours >= 1){
                             $interval_lunch_time_minutes = floor(($interval_lunch_time - ($interval_lunch_time_hours * 3600))/60);
                             $attendanceRecord['Attendance']['lunch_hour'] = $interval_lunch_time_hours." Hours ".$interval_lunch_time_minutes." Minutes";
                         }else{
                            $interval_lunch_time_minutes = floor(($interval_lunch_time)/60);
                            $attendanceRecord['Attendance']['lunch_hour'] = $interval_lunch_time_minutes." Minutes";
                        } 
                    }else{
                        $attendanceRecord['Attendance']['lunch_hour'] = "";
                    }       
                    $working_time = $total_time - $interval_lunch_time;
                    $working_time_hours   = floor(($working_time) / 3600);
                    if($working_time_hours >= 1){
                         $working_time_minutes = floor(($working_time - ($working_time_hours * 3600))/60);
                         $attendanceRecord['Attendance']['working_hour'] = $working_time_hours." Hours ".$working_time_minutes." Minutes";
                    }else{
                        $working_time_minutes = floor(($working_time)/60);
                        $attendanceRecord['Attendance']['working_hour'] = $working_time_minutes." Minutes";
                    }
                   

                    $total_time_hours   = floor(($total_time) / 3600);
                    if($total_time_hours >= 1){
                        $total_time_minutes = floor(($total_time - ($total_time_hours * 3600))/60);
                        $attendanceRecord['Attendance']['total_hour'] = $total_time_hours." Hours ".$total_time_minutes." Minutes";

                    }else{
                        $total_time_minutes = floor(($total_time)/60);
                        $attendanceRecord['Attendance']['total_hour'] = $total_time_minutes." Minutes";

                    }

                }else{
                    $attendanceRecord['Attendance']['working_hour'] = "";
                    $attendanceRecord['Attendance']['lunch_hour'] = "";
                    $attendanceRecord['Attendance']['total_hour'] = "";
                }



            	$apsentDays =  $totalDays - ($presentDays + $holidayDays + $weekEndDays );
            	
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
            $employeeCode = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$employee_id),'fields'=>array('Employee.emp_code','Employee.id','Employee.lunch_time','Employee.start_lunch_time','Employee.end_lunch_time')));
            $emp_lunch_time = $employeeCode['Employee']['lunch_time'];
            $emp_start_lunch_time = $employeeCode['Employee']['start_lunch_time'];
            $emp_end_lunch_time = $employeeCode['Employee']['end_lunch_time'];  
            $start_lunch_time = strtotime($emp_start_lunch_time);
            $end_lunch_time = strtotime($emp_end_lunch_time);
            $interval_lunch_time =  $end_lunch_time - $start_lunch_time; 
            if(!empty($interval_lunch_time)){
                $hours_lunch_time   = floor(($interval_lunch_time) / 3600);
                $minutes_lunch_time = floor(($interval_lunch_time - ($hours_lunch_time * 3600))/60);
            } 
            if(isset($employeeCode['Employee']['id']) && !empty($employeeCode['Employee']['id'])){
                $presentDays = $this->Attendance->find('count', array('conditions'=> array( 'Attendance.employee_id'=>$employee_id, 'Attendance.date >= ' => $startDate,'Attendance.date < ' => $endDate)));

                $data = $this->Attendance->find('all',array('conditions'=> array( 'Attendance.user_id'=>$user_id, 'Attendance.employee_id'=>$employee_id, 'Attendance.date >= ' => $startDate,'Attendance.date <= ' => $endDate),
                                                    'order' => array('Attendance.date' => 'DESC')));
                $attendanceRecord = array();
                $i = $p = 0;
                $total_time = 0;
                 if(!empty($data)){
                   foreach ($data as $key => $value) {
                        $interval_lunch_time = strtotime($value['Attendance']['lunch_time']);

                        if($value['Attendance']['checkin_time']=='NULL' || $value['Attendance']['checkin_time']=='0000-00-00 00:00:00' ||  empty($value['Attendance']['checkin_time']) ){
                            $customerData['Attendance']['checkin_time'] = '';
                        }else{
                            $checkInDateTime = $value['Attendance']['checkin_time'];
                            $checkInTime = date('h:i A', strtotime($checkInDateTime));
                            $customerData['Attendance']['checkin_time'] = $checkInTime;
                        }
                        if($value['Attendance']['checkout_time']=='NULL' || $value['Attendance']['checkout_time']=='0000-00-00 00:00:00' || empty($value['Attendance']['checkout_time']) ){
                            $customerData['Attendance']['checkout_time'] = '';
                        }else{
                            $checkuotDateTime = $value['Attendance']['checkout_time'];
                            $checkOutTime = date('h:i A', strtotime($checkuotDateTime));
                            $customerData['Attendance']['checkout_time'] = $checkOutTime;
                        }
                        if($interval_lunch_time == 0){
                            $interval_lunch_time = $emp_lunch_time;
                        }
                        if(!empty($customerData['Attendance']['checkin_time']) && !empty($customerData['Attendance']['checkout_time'])){

                            $checkin_time = strtotime($value['Attendance']['checkin_time']);
                            $checkout_time = strtotime($value['Attendance']['checkout_time']);
                            $total_time = $total_time + ($checkout_time - $checkin_time);
                            //echo 'total_time : '.$total_time.'<br />';
                            if(!empty($interval_lunch_time)) {
                                $i++;
                            }
                            $p++;
                        }
                    }
                    
                    $interval_lunch_time = $interval_lunch_time*$i*60;
                    if($interval_lunch_time > 0){
                        $interval_lunch_time_hours   = floor(($interval_lunch_time) / 3600);
                         if($interval_lunch_time_hours >= 1){
                             $interval_lunch_time_minutes = floor(($interval_lunch_time - ($interval_lunch_time_hours * 3600))/60);
                             $attendanceRecord['Attendance']['lunch_hour'] = $interval_lunch_time_hours." Hours ".$interval_lunch_time_minutes." Minutes";
                         }else{
                            $interval_lunch_time_minutes = floor(($interval_lunch_time)/60);
                            $attendanceRecord['Attendance']['lunch_hour'] = $interval_lunch_time_minutes." Minutes";
                        } 
                    }else{
                        $attendanceRecord['Attendance']['lunch_hour'] = "0 Minutes";
                    }       
                    $working_time = $total_time - $interval_lunch_time;
                    $working_time_hours   = floor(($working_time) / 3600);
                    if($working_time_hours >= 1){
                         $working_time_minutes = floor(($working_time - ($working_time_hours * 3600))/60);
                         $attendanceRecord['Attendance']['working_hour'] = $working_time_hours." Hours ".$working_time_minutes." Minutes";
                    }else{
                        if($working_time > 0){
                            $working_time_minutes = floor(($working_time)/60);
                            $attendanceRecord['Attendance']['working_hour'] = $working_time_minutes." Minutes";
                        }else{
                            $attendanceRecord['Attendance']['working_hour'] = "0 Minutes";
                        }
                    }
                   

                    $total_time_hours   = floor(($total_time) / 3600);
                    if($total_time_hours >= 1){
                        $total_time_minutes = floor(($total_time - ($total_time_hours * 3600))/60);
                        $attendanceRecord['Attendance']['total_hour'] = $total_time_hours." Hours ".$total_time_minutes." Minutes";

                    }else{
                        if($total_time  > 0 ){
                            $total_time_minutes = floor(($total_time)/60);
                            $attendanceRecord['Attendance']['total_hour'] = $total_time_minutes." Minutes";
                        }else{
                            $attendanceRecord['Attendance']['total_hour'] = "0 Minutes";
                        }    

                    }

                }else{
                    $attendanceRecord['Attendance']['working_hour'] = "0 Minutes";
                    $attendanceRecord['Attendance']['lunch_hour'] = "0 Minutes";
                    $attendanceRecord['Attendance']['total_hour'] = "0 Minutes";
                }



                $apsentDays =  $totalDays - ($presentDays + $holidayDays + $weekEndDays );
                
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
        $device_token = isset($decoded['device_token']) ? $decoded['device_token'] : '';
        //$recordData['RecordData']['name'] = "device_token";
        //$recordData['RecordData']['query'] = $device_token;
        //$this->RecordData->saveAll($recordData);
        
        //$this->send_notification_for_iphone($device_token, 'test push notitifation.');
        
        if(!empty($emp_code)){
            $employeeCode = $this->Employee->find('first', array('conditions'=>array('Employee.emp_code'=>$emp_code, 'Employee.status' =>Configure::read('App.Status.active')),'fields'=>array('Employee.emp_code','Employee.user_id','Employee.name','Employee.image','Employee.role_title','Employee.role_id','Employee.id')));
           // print_r($employeeCode );die;
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

/********************************Reservation Section***********************************************/

 /**************************************************************************
     * NAME: add_reservation
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
    
    
    function add_reservation(){
        
        $this->loadModel("Reservation");
        $data = file_get_contents("php://input");
        
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }
        if(empty($data)){
            $data = json_encode($_GET);
        } 
        $decoded = json_decode($data, true);
       // print_r($decoded);die;
        if(isset($decoded['id']) && !empty($decoded['id'])){
            $reservation['Reservation']['id'] = isset($decoded['id']) ? strtolower($decoded['id']) : '';
        }else{
            $reservation['Reservation']['reservation_number'] = $this->ReservationNumberString();
        }  
        $reservation['Reservation']['user_id'] = isset($decoded['user_id']) ? strtolower($decoded['user_id']) : '';
        //$reservation['Reservation']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $reservation['Reservation']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        
        //$reservation['Reservation']['reservation_number'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $reservation['Reservation']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $reservation['Reservation']['employee_ids'] = isset($decoded['employee_ids']) ? $decoded['employee_ids'] : '';
        $reservation['Reservation']['all_day'] = isset($decoded['all_day']) ? $decoded['all_day'] : '';
        $start_date = isset($decoded['start_date']) ? $decoded['start_date'] : '';
        $reservation['Reservation']['start_date'] = date("Y-m-d H:i:s", strtotime($start_date));
        $end_date = isset($decoded['end_date']) ? $decoded['end_date'] : '';
        $reservation['Reservation']['end_date'] = date("Y-m-d H:i:s", strtotime($end_date));
        $extra_start_date = isset($decoded['extra_start_date']) ? $decoded['extra_start_date'] : '';
        $reservation['Reservation']['extra_start_date'] = date("Y-m-d", strtotime($extra_start_date));
        $extra_end_date = isset($decoded['extra_end_date']) ? $decoded['extra_end_date'] : '';
        $reservation['Reservation']['extra_end_date'] = date("Y-m-d", strtotime($extra_end_date));
        $reservation['Reservation']['start_time'] = isset($decoded['start_time']) ? $decoded['start_time'] : '';
        $reservation['Reservation']['end_time'] = isset($decoded['end_time']) ? $decoded['end_time'] : '';
        $reservation['Reservation']['channel'] = isset($decoded['channel']) ? $decoded['channel'] : '';
        $reservation['Reservation']['note'] = isset($decoded['note']) ? $decoded['note'] : '';
        $reservation['Reservation']['payment_total'] = isset($decoded['price']) ? $decoded['price'] : '';
        $reservation['Reservation']['status'] = Configure::read('App.Status.active');
        //print_r($reservation);die;
        if($this->Reservation->saveAll($reservation)){
            $reservation_id = $this->Reservation->id; 
            $responseArr['reservation_id'] = $reservation_id;
            $responseArr['status'] = 'success';
           
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Reservation->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_reservation";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }

    /**************************************************************************
     * NAME: cancel_reservation
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
    
    
    function cancel_reservation(){
        
        $this->loadModel("Reservation");
        $data = file_get_contents("php://input");
        
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }
        if(empty($data)){
            $data = json_encode($_GET);
        } 
        $decoded = json_decode($data, true);
        $reservation_id = $reservation['Reservation']['id'] = isset($decoded['id']) ? strtolower($decoded['id']) : '';
        if(!empty($reservation_id )){
            $reservation['Reservation']['status'] = '2';
            if($this->Reservation->saveAll($reservation)){
                $reservation_id = $this->Reservation->id; 
                $responseArr['reservation_id'] = $reservation_id;
                $responseArr['status'] = 'success';
                $responseArr['msg'] = 'Reservation Cancelation successfully.';
               
                $jsonEncode = json_encode($responseArr);
                
            }else{
                $responseArr = array('status' => 'errors' );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'errorss' );
            $jsonEncode = json_encode($responseArr);
        }
       
        $log = $this->Reservation->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "cancel_reservation";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }



    /**************************************************************************
     * NAME: get_reservation_old
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
    
    
    function get_reservation_old(){
        
        $this->loadModel("Reservation");
        $data = file_get_contents("php://input");
        
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }
        $decoded = json_decode($data, true);
        
        $user_id = isset($decoded['user_id']) ? strtolower($decoded['user_id']) : '';
        $this->Reservation->bindModel(array('belongsTo' => array('Customer')));
        $reservationData = $this->Reservation->find('all',array('conditions'=>array( 'Reservation.user_id'=>$user_id)));
        print_r($reservationData);die;       

       
        if($this->Reservation->saveAll($reservation)){
            $reservation_id = $this->Reservation->id; 
            $responseArr['reservation_id'] = $reservation_id;
            $responseArr['status'] = 'success';
           
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Reservation->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_reservation";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }

    /**************************************************************************
     * NAME: cutomer_migration
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
    
    
    function customer_marge(){
        
        $this->loadModel("Customer");
        $this->loadModel("Esthe");
        $this->loadModel("Eyelush");
        $this->loadModel("HairRemoval");
        $this->loadModel("Body");
        $this->loadModel("Facial");
        $this->loadModel("CustomerHistory");
        $this->loadModel("NoteImage");
        $this->loadModel("Reservation");
        $data = file_get_contents("php://input");
       
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }

        $decoded = json_decode($data, true);
        
        $customer_ids = isset($decoded['customer_ids']) ? $decoded['customer_ids'] : '';
        
        //$customer_ids = array('32','29', '45', '30');
       // echo json_encode($customer_ids);die;	
       // $customer_ids = array('32');
      //echo $customer_ids;die;
       if(!empty($customer_ids)){
            $customer_ids = explode(",", $customer_ids);
	       	$count_customer_ids = count($customer_ids);
	       	$max_customer_id = max($customer_ids);
	       //	echo $max_customer_id;die;
	        if($count_customer_ids != 1){
	        	foreach ($customer_ids as  $value) {
		        	if($max_customer_id != $value){
		        		$this->Customer->updateAll(array('Customer.status' => 0),array('Customer.id' => $value));
		        		$this->Esthe->updateAll(array('Esthe.customer_id' => $max_customer_id),array('Esthe.customer_id' => $value));
		        		$this->Eyelush->updateAll(array('Eyelush.customer_id' => $max_customer_id),array('Eyelush.customer_id' => $value));
		        		$this->HairRemoval->updateAll(array('HairRemoval.customer_id' => $max_customer_id),array('HairRemoval.customer_id' => $value));
		        		$this->Body->updateAll(array('Body.customer_id' => $max_customer_id),array('Body.customer_id' => $value));
		        		$this->Facial->updateAll(array('Facial.customer_id' => $max_customer_id),array('Facial.customer_id' => $value));
		        		$this->CustomerHistory->updateAll(array('CustomerHistory.customer_id' => $max_customer_id),array('CustomerHistory.customer_id' => $value));
		        		$this->NoteImage->updateAll(array('NoteImage.customer_id' => $max_customer_id),array('NoteImage.customer_id' => $value));
		        		$this->Reservation->updateAll(array('Reservation.customer_id' => $max_customer_id),array('Reservation.customer_id' => $value));

		        	}	
		        }
	        }

	        $data = $this->Customer->find('first',array('conditions'=>array( 'Customer.id'=>$max_customer_id, 'Customer.status'=>Configure::read('App.Status.active'))));

	        $jsonEncode = json_encode($data);

       }else{
            $responseArr['Customer']['status'] = Configure::read('App.Status.inactive');
            $jsonEncode = json_encode($responseArr);
        }
        
        
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_service";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }




/**************************************************************************
     * NAME: get_reservation_ipad
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
    
    
      function get_reservation_ipad($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    
        
        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Customer");
        $this->loadModel("Employee");
        $this->loadModel("ServiceColor");
        $this->loadModel("Reservation");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $i =0;
        if(!empty($user_id)){
            $userData = $this->User->find('first',array('conditions'=>array( 'User.id'=>$user_id)));
            if(!$userData){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'User does not exist.'));
            }else{
                $this->Reservation->bindModel(array('belongsTo' => array('Customer', 'Service')));
                $reservationDataFind = $this->Reservation->find('all',array('conditions'=>array( 'Reservation.user_id'=>$user_id)));
                $reservationData = array();
                if(!empty($reservationDataFind)){
                    foreach ($reservationDataFind as $key => $value) {
                        $reservationData['Reservation'][$key]['id']= $value['Reservation']['id'];
                        $reservationData['Reservation'][$key]['customer_name']= $value['Reservation']['name'];
                        $reservationData['Reservation'][$key]['all_day']= $value['Reservation']['all_day'];
                        if($value['Reservation']['all_day'] =='1'){
                             $reservationData['Reservation'][$key]['start_date']= $value['Reservation']['start_date'];
                        }else{
                            $reservationData['Reservation'][$key]['start_date']= $value['Reservation']['start_date'];
                            $reservationData['Reservation'][$key]['start_time']= $value['Reservation']['start_time'];
                            $reservationData['Reservation'][$key]['end_time']= $value['Reservation']['end_time'];
                        }
                        $reservationData['Reservation'][$key]['note']= $value['Reservation']['note'];
                        $reservationData['Reservation'][$key]['channel']= $value['Reservation']['channel'];
                        if($value['Reservation']['payment_total']!='null')
                            $reservationData['Reservation'][$key]['price']= $value['Reservation']['payment_total'];
                        else
                            $reservationData['Reservation'][$key]['price']= '';

                        $allEmp = array();
                        $employee_ids = $value['Reservation']['employee_ids'];
                        if(!empty($employee_ids)){
                            $employee_ids =  explode(",", $employee_ids);
                            $allEmp = $this->Employee->find('all' ,array('conditions'=>array('Employee.id'=>$employee_ids)));
                           // print_r($allEmp);die;
                        }
                        $service_id = $value['Reservation']['service_id'];
                        if(!empty($service_id)){
                            $serviceColor = $this->ServiceColor->find('first' ,array('conditions'=>array('ServiceColor.service_id'=>$service_id, 'ServiceColor.user_id' => $user_id)));
                            //print_r($serviceColor);die;
                        }
                        $reservationData['Reservation'][$key]['service_id']= $value['Reservation']['service_id'];
                        $reservationData['Reservation'][$key]['service_name']= $value['Service']['name'];
                            if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData['Reservation'][$key]['service_color']= $serviceColor['ServiceColor']['color_code'];
                            }
                            $reservationData['Reservation'][$key]['customer_id']= $value['Customer']['id'];
                            $reservationData['Reservation'][$key]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                   
                            
                            if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData['Reservation'][$key]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData['Reservation'][$key]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData['Reservation'][$key]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                }
                            }

                        $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                        if(empty($last_visit))
                            $last_visit =  isset($value['Reservation']['modified']) ? $value['Reservation']['modified'] : '';
                        $reservationData['Reservation'][$key]['last_visit']= $last_visit;
                        $reservationData['Reservation'][$key]['ongoing']= $value['Reservation']['status'];
                         

                    }


                }else{
                    $reservationData['Reservation'][$i]['msg'] = 'No Record Found.';
                    $reservationData['Reservation'][$i]['status'] = 'error';
                }
                $jsonEncode = json_encode($reservationData);
            }
        }else{
            $reservationData['Reservation'][$i]['msg'] = 'User does not exist.';
            $reservationData['Reservation'][$i]['status'] = 'error';
            $jsonEncode = json_encode($reservationData);
        }
        $log = $this->Reservation->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "reservation_calendar_ipad";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


    /**************************************************************************
     * NAME: get_reservation
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
    
    
      function calender_reservation($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Customer");
        $this->loadModel("ServiceColor");
        $this->loadModel("Reservation");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $i =0;
        if(!empty($user_id)){
            $userData = $this->User->find('first',array('conditions'=>array( 'User.id'=>$user_id)));
            if(!$userData){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'User does not exist.'));
            }else{
                $this->Reservation->bindModel(array('belongsTo' => array('Customer')));
                $reservationDataFind = $this->Reservation->find('all',array('conditions'=>array( 'Reservation.user_id'=>$user_id, 'Reservation.status'=>Configure::read('App.Status.active')),  'order' => array('Reservation.start_date' => 'DESC')));
               
              //  print_r($reservationDataFind);die;
                $reservationData = array();
                $date = '';
                $i= 0;
                if(!empty($reservationDataFind)){
                    foreach ($reservationDataFind as $key => $value) {
                       if($date != $value['Reservation']['start_date']){
                             $i= 0;
                             $date = $value['Reservation']['start_date'];
                        } 
                       if(!empty($value['Reservation']['service_id'])){
                            $this->ServiceColor->bindModel(array('belongsTo' => array('Color')));
                            $serviceColor = $this->ServiceColor->find('first' ,array('conditions'=>array('ServiceColor.service_id'=>$value['Reservation']['service_id'], 'ServiceColor.user_id' => $user_id)));
                            if(isset($serviceColor['ServiceColor']['color_code']) && !empty($serviceColor['ServiceColor']['color_code']))
                            $reservationData[$date][$i]['color']= $serviceColor['Color']['color_code'];
                            //print_r($serviceColor);die;
                        }
                       
                        
                        $reservationData[$date][$i]['id']= $value['Reservation']['id'];
                        $reservationData[$date][$i]['label']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                        
                        $i++;              
                   
                    }
                   // $reservationData[$date]['count']= $i;
               }else{
                    $reservationData['Reservation'][$i]['msg'] = 'No Record Found.';
                    $reservationData['Reservation'][$i]['status'] = 'error';
                }
                $jsonEncode = json_encode($reservationData);
            }
        }else{
            $reservationData['Reservation'][$i]['msg'] = 'User does not exist.';
            $reservationData['Reservation'][$i]['status'] = 'error';
            $jsonEncode = json_encode($reservationData);
        }
        $log = $this->Reservation->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "calender_reservation";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

/**************************************************************************
     * NAME: get_date_reservation
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
    
    
      function get_date_reservation($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Customer");
        $this->loadModel("Employee");
        $this->loadModel("ServiceColor");
        $this->loadModel("Reservation");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $date = isset($decoded['date']) ? $decoded['date'] : '';
        $i =0;
        if(!empty($user_id)){
            $userData = $this->User->find('first',array('conditions'=>array( 'User.id'=>$user_id)));
            if(!$userData){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'User does not exist.'));
            }else{
                $this->Reservation->bindModel(array('belongsTo' => array('Customer', 'Service')));
                $reservationDataFind = $this->Reservation->find('all',array('conditions'=>array( 'Reservation.start_date'=>$date, 'Reservation.user_id'=>$user_id, 'Reservation.status'=>Configure::read('App.Status.active'))));
                //echo "<pre>";
                //print_r($reservationDataFind);die;
                $reservationData = array();
                $all_day = $morning = $afternoon = $evening = 0; 
                if(!empty($reservationDataFind)){
                    foreach ($reservationDataFind as $key => $value) {
                       // print_r($value);die;
                        $start_time = strtotime($value['Reservation']['start_time']);
                        $morning_start_time = strtotime('00:00:00');
                        $morning_end_time = strtotime('12:00:00');
                        $afternoon_start_time = strtotime('12:00:00');
                        $afternoon_end_time = strtotime('18:00:00');
                        $evening_start_time = strtotime('18:00:00');
                        $evening_end_time = strtotime('23:59:59');
                       
                        
                        
                        $allEmp = array();
                        $employee_ids = $value['Reservation']['employee_ids'];
                        if(!empty($employee_ids)){
                            $employee_ids =  explode(",", $employee_ids);
                            $allEmp = $this->Employee->find('all' ,array('conditions'=>array('Employee.id'=>$employee_ids)));
                           // print_r($allEmp);die;
                        }
                        $service_id = $value['Reservation']['service_id'];
                        if(!empty($service_id)){
                            $serviceColor = $this->ServiceColor->find('first' ,array('conditions'=>array('ServiceColor.service_id'=>$service_id, 'ServiceColor.user_id' => $user_id)));
                            //print_r($serviceColor);die;
                        }
                        if($value['Reservation']['all_day']== '1'){
                            $sectionType ='all_day';
                            $reservationData[$sectionType][$all_day]['id']= $value['Reservation']['id'];
                            $reservationData[$sectionType][$all_day]['user_id']= $value['Reservation']['user_id'];
                            $reservationData[$sectionType][$all_day]['customer_id']= $value['Reservation']['customer_id'];
                            $reservationData[$sectionType][$all_day]['service_id']= $value['Reservation']['service_id'];
                            $reservationData[$sectionType][$all_day]['service_name']= $value['Service']['name'];
                            if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$sectionType][$all_day]['service_color']= $serviceColor['ServiceColor']['color_code'];
                            }
                            $reservationData[$sectionType][$all_day]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                            $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                            if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                            $reservationData[$sectionType][$all_day]['last_visit']= $last_visit;
                            
                            if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$sectionType][$all_day]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$sectionType][$all_day]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$sectionType][$all_day]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                    $reservationData[$sectionType][$all_day]['Employee'][$empKey]['image']= $empValue['Employee']['image'];
                                }
                            }

                            $reservationData[$sectionType][$all_day]['start_date']= $value['Reservation']['start_date'];
                            $reservationData[$sectionType][$all_day]['end_date']= $value['Reservation']['end_date'];
                            $reservationData[$sectionType][$all_day]['extra_start_date']= $value['Reservation']['extra_start_date'];
                            $reservationData[$sectionType][$all_day]['extra_end_date']= $value['Reservation']['extra_end_date'];
                            $reservationData[$sectionType][$all_day]['start_time']= $value['Reservation']['start_time'];
                            $reservationData[$sectionType][$all_day]['end_time']= $value['Reservation']['end_time'];
                            $reservationData[$sectionType][$all_day]['price']= $value['Reservation']['payment_total'];
                            $reservationData[$sectionType][$all_day]['all_day']= $value['Reservation']['all_day'];
                            $reservationData[$sectionType][$all_day]['note']= $value['Reservation']['note'];
                            if($value['Reservation']['channel'] != 'null')
                                $reservationData[$sectionType][$all_day]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$sectionType][$all_day]['channel']= '';
                            $all_day++;
                        }elseif ($morning_end_time > $start_time && $morning_start_time <= $start_time ){
                             $sectionType ='morning';
                             $reservationData[$sectionType][$morning]['id']= $value['Reservation']['id'];
                             $reservationData[$sectionType][$morning]['user_id']= $value['Reservation']['user_id'];
                             $reservationData[$sectionType][$morning]['extra_start_date']= $value['Reservation']['extra_start_date'];
                            $reservationData[$sectionType][$morning]['extra_end_date']= $value['Reservation']['extra_end_date'];

                             $reservationData[$sectionType][$morning]['customer_id']= $value['Reservation']['customer_id'];
                             $reservationData[$sectionType][$morning]['service_id']= $value['Reservation']['service_id'];
                             $reservationData[$sectionType][$morning]['service_name']= $value['Service']['name'];
                             if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$sectionType][$morning]['service_color']= $serviceColor['ServiceColor']['color_code'];
                             }
                             $reservationData[$sectionType][$morning]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                             $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                             if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                             $reservationData[$sectionType][$morning]['last_visit']= $last_visit;
                             if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$sectionType][$morning]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$sectionType][$morning]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$sectionType][$morning]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                    $reservationData[$sectionType][$morning]['Employee'][$empKey]['image']= $empValue['Employee']['image'];
                                }
                             }

                             $reservationData[$sectionType][$morning]['start_date']= $value['Reservation']['start_date'];
                             $reservationData[$sectionType][$morning]['end_date']= $value['Reservation']['end_date'];
                             $reservationData[$sectionType][$morning]['start_time']= $value['Reservation']['start_time'];
                             $reservationData[$sectionType][$morning]['end_time']= $value['Reservation']['end_time'];
                             $reservationData[$sectionType][$morning]['all_day']= $value['Reservation']['all_day'];
                           
                             $reservationData[$sectionType][$morning]['note']= $value['Reservation']['note'];
                             $reservationData[$sectionType][$morning]['price']= $value['Reservation']['payment_total'];
                              if($value['Reservation']['channel'] != 'null')
                                $reservationData[$sectionType][$morning]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$sectionType][$morning]['channel']= '';
                             $morning++;

                        }elseif($afternoon_end_time > $start_time && $afternoon_start_time <= $start_time){
                             $sectionType ='afternoon';
                             $reservationData[$sectionType][$afternoon]['id']= $value['Reservation']['id'];
                             $reservationData[$sectionType][$afternoon]['user_id']= $value['Reservation']['user_id'];
                             $reservationData[$sectionType][$afternoon]['extra_start_date']= $value['Reservation']['extra_start_date'];
                            $reservationData[$sectionType][$afternoon]['extra_end_date']= $value['Reservation']['extra_end_date'];
                            
                             $reservationData[$sectionType][$afternoon]['customer_id']= $value['Reservation']['customer_id'];
                             $reservationData[$sectionType][$afternoon]['service_id']= $value['Reservation']['service_id'];
                             $reservationData[$sectionType][$afternoon]['service_name']= $value['Service']['name'];
                             if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$sectionType][$afternoon]['service_color']= $serviceColor['ServiceColor']['color_code'];
                             }
                             $reservationData[$sectionType][$afternoon]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                             $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                             if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                             $reservationData[$sectionType][$afternoon]['last_visit']= $last_visit;
                             if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$sectionType][$afternoon]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$sectionType][$afternoon]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$sectionType][$afternoon]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                    $reservationData[$sectionType][$afternoon]['Employee'][$empKey]['image']= $empValue['Employee']['image'];
                                }
                             }

                             $reservationData[$sectionType][$afternoon]['start_date']= $value['Reservation']['start_date'];
                             $reservationData[$sectionType][$afternoon]['end_date']= $value['Reservation']['end_date'];
                             $reservationData[$sectionType][$afternoon]['start_time']= $value['Reservation']['start_time'];
                             $reservationData[$sectionType][$afternoon]['end_time']= $value['Reservation']['end_time'];
                             $reservationData[$sectionType][$afternoon]['note']= $value['Reservation']['note'];
                             $reservationData[$sectionType][$afternoon]['all_day']= $value['Reservation']['all_day'];
                             $reservationData[$sectionType][$afternoon]['price']= $value['Reservation']['payment_total'];
                              if($value['Reservation']['channel'] != 'null')
                                $reservationData[$sectionType][$afternoon]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$sectionType][$afternoon]['channel']= '';
                             $afternoon++;

                        }elseif($evening_end_time > $start_time && $evening_start_time <= $start_time){
                             $sectionType ='evening';
                             $reservationData[$sectionType][$evening]['id']= $value['Reservation']['id'];
                             $reservationData[$sectionType][$evening]['user_id']= $value['Reservation']['user_id'];
                             $reservationData[$sectionType][$evening]['extra_start_date']= $value['Reservation']['extra_start_date'];
                            $reservationData[$sectionType][$evening]['extra_end_date']= $value['Reservation']['extra_end_date'];
                             $reservationData[$sectionType][$evening]['customer_id']= $value['Reservation']['customer_id'];
                             $reservationData[$sectionType][$evening]['service_id']= $value['Reservation']['service_id'];
                             $reservationData[$sectionType][$evening]['service_name']= $value['Service']['name'];
                             if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$sectionType][$evening]['service_color']= $serviceColor['ServiceColor']['color_code'];
                             }
                             $reservationData[$sectionType][$evening]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                             $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                             if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                             $reservationData[$sectionType][$evening]['last_visit']= $last_visit;
                             if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$sectionType][$evening]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$sectionType][$evening]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$sectionType][$evening]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                    $reservationData[$sectionType][$evening]['Employee'][$empKey]['image']= $empValue['Employee']['image'];
                                }
                             }

                             $reservationData[$sectionType][$evening]['start_date']= $value['Reservation']['start_date'];
                             $reservationData[$sectionType][$evening]['end_date']= $value['Reservation']['end_date'];
                             $reservationData[$sectionType][$evening]['start_time']= $value['Reservation']['start_time'];
                             $reservationData[$sectionType][$evening]['end_time']= $value['Reservation']['end_time'];
                             $reservationData[$sectionType][$evening]['note']= $value['Reservation']['note'];
                             $reservationData[$sectionType][$evening]['all_day']= $value['Reservation']['all_day'];
                             $reservationData[$sectionType][$evening]['price']= $value['Reservation']['payment_total'];
                             if($value['Reservation']['channel'] != 'null')
                                $reservationData[$sectionType][$evening]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$sectionType][$evening]['channel']= '';
                             $evening++;

                        } 
                                 
                   
                    }



                }else{
                    $reservationData['Reservation'] = array();
                   // $reservationData['Reservation'][$i]['status'] = 'error';
                }
                $jsonEncode = json_encode($reservationData);
            }
        }else{
            //$reservationData['Reservation'][$i]['msg'] = 'User does not exist.';
            //$reservationData['Reservation'][$i]['status'] = 'error';
            
            $reservationData['Reservation'] = array();
            $jsonEncode = json_encode($reservationData);
        }
        $log = $this->Reservation->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "reservation_calendar_ipad";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }



    /**************************************************************************
     * NAME: get_reservation
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
    
    
      function get_reservation($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("User");
        $this->loadModel("Customer");
        $this->loadModel("Employee");
        $this->loadModel("ServiceColor");
        $this->loadModel("Reservation");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $i =0;
        if(!empty($user_id)){
            $userData = $this->User->find('first',array('conditions'=>array( 'User.id'=>$user_id)));
            if(!$userData){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'User does not exist.'));
            }else{
                $this->Reservation->bindModel(array('belongsTo' => array('Customer', 'Service')));
                $reservationDataFind = $this->Reservation->find('all',array('conditions'=>array( 'Reservation.user_id'=>$user_id, 'Reservation.status'=>Configure::read('App.Status.active')),  'order' => array('Reservation.start_date' => 'DESC')));
               
              //  print_r($reservationDataFind);die;
                $reservationData = array();
                $date = '';
                $i= 0;
                if(!empty($reservationDataFind)){
                    foreach ($reservationDataFind as $key => $value) {
                       // print_r($value);die;
                        $start_time = strtotime($value['Reservation']['start_time']);
                        $morning_start_time = strtotime('06:00:00');
                        $morning_end_time = strtotime('12:00:00');
                        $afternoon_start_time = strtotime('12:00:00');
                        $afternoon_end_time = strtotime('18:00:00');
                        $evening_start_time = strtotime('18:00:00');
                        $evening_end_time = strtotime('23:59:59');
                       
						if($date != $value['Reservation']['start_date']){
							 $i= 0;
							 $date = $value['Reservation']['start_date'];
						} 
                        $all_day = $morning = $afternoon = $evening = 0; 
                        $allEmp = array();
                        $employee_ids = $value['Reservation']['employee_ids'];
                        if(!empty($employee_ids)){
                            $employee_ids =  explode(",", $employee_ids);
                            $allEmp = $this->Employee->find('all' ,array('conditions'=>array('Employee.id'=>$employee_ids)));
                           // print_r($allEmp);die;
                        }
                        $service_id = $value['Reservation']['service_id'];
                        if(!empty($service_id)){
                            $serviceColor = $this->ServiceColor->find('first' ,array('conditions'=>array('ServiceColor.service_id'=>$service_id, 'ServiceColor.user_id' => $user_id)));
                            //print_r($serviceColor);die;
                        }
                        if($value['Reservation']['all_day']== '1'){
                            $sectionType ='all_day';
                            $reservationData[$date][$i][$sectionType][$all_day]['id']= $value['Reservation']['id'];
                            $reservationData[$date][$i][$sectionType][$all_day]['user_id']= $value['Reservation']['user_id'];
                            $reservationData[$date][$i][$sectionType][$all_day]['customer_id']= $value['Reservation']['customer_id'];
                            $reservationData[$date][$i][$sectionType][$all_day]['service_id']= $value['Reservation']['service_id'];
                            $reservationData[$date][$i][$sectionType][$all_day]['service_name']= $value['Service']['name'];
                            if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$date][$i][$sectionType][$all_day]['service_color']= $serviceColor['ServiceColor']['color_code'];
                            }
                            $reservationData[$date][$i][$sectionType][$all_day]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                            $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                            if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                            $reservationData[$date][$i][$sectionType][$all_day]['last_visit']= $last_visit;
                            
                            if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$date][$i][$sectionType][$all_day]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$date][$i][$sectionType][$all_day]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$date][$i][$sectionType][$all_day]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                }
                            }

                            $reservationData[$date][$i][$sectionType][$all_day]['start_date']= $value['Reservation']['start_date'];
                            $reservationData[$date][$i][$sectionType][$all_day]['end_date']= $value['Reservation']['end_date'];
                            $reservationData[$date][$i][$sectionType][$all_day]['start_time']= $value['Reservation']['start_time'];
                            $reservationData[$date][$i][$sectionType][$all_day]['end_time']= $value['Reservation']['end_time'];
                            $reservationData[$date][$i][$sectionType][$all_day]['note']= $value['Reservation']['note'];
                            if($value['Reservation']['channel'] != 'null')
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= '';
                            $all_day++;
                        }elseif ($morning_end_time > $start_time && $morning_start_time <= $start_time ){
                             $sectionType ='morning';
                             $reservationData[$date][$i][$sectionType][$morning]['id']= $value['Reservation']['id'];
                             $reservationData[$date][$i][$sectionType][$morning]['user_id']= $value['Reservation']['user_id'];
                             $reservationData[$date][$i][$sectionType][$morning]['customer_id']= $value['Reservation']['customer_id'];
                             $reservationData[$date][$i][$sectionType][$morning]['service_id']= $value['Reservation']['service_id'];
                             $reservationData[$date][$i][$sectionType][$all_day]['service_name']= $value['Service']['name'];
                             if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$date][$i][$sectionType][$all_day]['service_color']= $serviceColor['ServiceColor']['color_code'];
                             }
                             $reservationData[$date][$i][$sectionType][$morning]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                             $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                             if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                             $reservationData[$date][$i][$sectionType][$morning]['last_visit']= $last_visit;
                             if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$date][$i][$sectionType][$morning]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$date][$i][$sectionType][$morning]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$date][$i][$sectionType][$morning]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                }
                             }

                             $reservationData[$date][$i][$sectionType][$morning]['start_date']= $value['Reservation']['start_date'];
                             $reservationData[$date][$i][$sectionType][$morning]['end_date']= $value['Reservation']['end_date'];
                             $reservationData[$date][$i][$sectionType][$morning]['start_time']= $value['Reservation']['start_time'];
                             $reservationData[$date][$i][$sectionType][$morning]['end_time']= $value['Reservation']['end_time'];
                             $reservationData[$date][$i][$sectionType][$morning]['note']= $value['Reservation']['note'];
                              if($value['Reservation']['channel'] != 'null')
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= '';
                             $morning++;

                        }elseif($afternoon_end_time > $start_time && $afternoon_start_time <= $start_time){
                             $sectionType ='afternoon';
                             $reservationData[$date][$i][$sectionType][$afternoon]['id']= $value['Reservation']['id'];
                             $reservationData[$date][$i][$sectionType][$afternoon]['user_id']= $value['Reservation']['user_id'];
                             $reservationData[$date][$i][$sectionType][$afternoon]['customer_id']= $value['Reservation']['customer_id'];
                             $reservationData[$date][$i][$sectionType][$afternoon]['service_id']= $value['Reservation']['service_id'];
                             $reservationData[$date][$i][$sectionType][$all_day]['service_name']= $value['Service']['name'];
                             if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$date][$i][$sectionType][$all_day]['service_color']= $serviceColor['ServiceColor']['color_code'];
                             }
                             $reservationData[$date][$i][$sectionType][$afternoon]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                             $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                             if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                             $reservationData[$date][$i][$sectionType][$afternoon]['last_visit']= $last_visit;
                             if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$date][$i][$sectionType][$afternoon]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$date][$i][$sectionType][$afternoon]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$date][$i][$sectionType][$afternoon]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                }
                             }

                             $reservationData[$date][$i][$sectionType][$afternoon]['start_date']= $value['Reservation']['start_date'];
                             $reservationData[$date][$i][$sectionType][$afternoon]['end_date']= $value['Reservation']['end_date'];
                             $reservationData[$date][$i][$sectionType][$afternoon]['start_time']= $value['Reservation']['start_time'];
                             $reservationData[$date][$i][$sectionType][$afternoon]['end_time']= $value['Reservation']['end_time'];
                             $reservationData[$date][$i][$sectionType][$afternoon]['note']= $value['Reservation']['note'];
                              if($value['Reservation']['channel'] != 'null')
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= '';
                             $afternoon++;

                        }elseif($evening_end_time > $start_time && $evening_start_time <= $start_time){
                             $sectionType ='evening';
                             $reservationData[$date][$i][$sectionType][$evening]['id']= $value['Reservation']['id'];
                             $reservationData[$date][$i][$sectionType][$evening]['user_id']= $value['Reservation']['user_id'];
                             $reservationData[$date][$i][$sectionType][$evening]['customer_id']= $value['Reservation']['customer_id'];
                             $reservationData[$date][$i][$sectionType][$evening]['service_id']= $value['Reservation']['service_id'];
                             $reservationData[$date][$i][$sectionType][$all_day]['service_name']= $value['Service']['name'];
                             if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData[$date][$i][$sectionType][$all_day]['service_color']= $serviceColor['ServiceColor']['color_code'];
                             }
                             $reservationData[$date][$i][$sectionType][$evening]['customer_name']= $value['Customer']['first_name'].' '.$value['Customer']['last_name'];
                             $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                             if(empty($last_visit))
                                $last_visit =  'First Time Coming';

                             $reservationData[$date][$i][$sectionType][$evening]['last_visit']= $last_visit;
                             if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData[$date][$i][$sectionType][$evening]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData[$date][$i][$sectionType][$evening]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData[$date][$i][$sectionType][$evening]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                }
                             }

                             $reservationData[$date][$i][$sectionType][$evening]['start_date']= $value['Reservation']['start_date'];
                             $reservationData[$date][$i][$sectionType][$evening]['end_date']= $value['Reservation']['end_date'];
                             $reservationData[$date][$i][$sectionType][$evening]['start_time']= $value['Reservation']['start_time'];
                             $reservationData[$date][$i][$sectionType][$evening]['end_time']= $value['Reservation']['end_time'];
                             $reservationData[$date][$i][$sectionType][$evening]['note']= $value['Reservation']['note'];
                             if($value['Reservation']['channel'] != 'null')
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= $value['Reservation']['channel'];
                            else
                                $reservationData[$date][$i][$sectionType][$evening]['channel']= '';
                             $evening++;

                        } 
                         $i++;             	
                   
                    }



                }else{
                    $reservationData['Reservation'][$i]['msg'] = 'No Record Found.';
                    $reservationData['Reservation'][$i]['status'] = 'error';
                }
                $jsonEncode = json_encode($reservationData);
            }
        }else{
            $reservationData['Reservation'][$i]['msg'] = 'User does not exist.';
            $reservationData['Reservation'][$i]['status'] = 'error';
            $jsonEncode = json_encode($reservationData);
        }
        $log = $this->Reservation->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "reservation_calendar_ipad";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


/*****************************Product Section *********************************/


 /**************************************************************************
     * NAME: add_reservation
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
    
    
    function add_product(){
        
        $this->loadModel("Product");
        $data = file_get_contents("php://input");
        
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }
        if(empty($data)){
            $data = json_encode($_GET);
        } 
        $decoded = json_decode($data, true);

        $product_name = $decoded['product_name'];
        $productExist = $this->Product->find('first', array('conditions'=>array('Product.product_name'=>$product_name)));
        
        if($productExist){
            $responseArr = array('msg' => 'ダクト名は既に存在します', 'msg1' => 'Product name is already exist.',  'status' => 'error' );
            $jsonEncode = json_encode($responseArr);
            return $jsonEncode;
        }
        if(isset($decoded['id']) && !empty($decoded['id'])){
            $product['Product']['id'] = isset($decoded['id']) ? strtolower($decoded['id']) : '';
        } 
        $product['Product']['user_id'] = isset($decoded['user_id']) ? strtolower($decoded['user_id']) : '';
        $product['Product']['product_name'] = isset($decoded['product_name']) ? $decoded['product_name'] : '';
        $product['Product']['product_stock'] = isset($decoded['product_stock']) ? $decoded['product_stock'] : '';
        $product['Product']['product_sale_price'] = isset($decoded['product_sale_price']) ? $decoded['product_sale_price'] : '';
        $product['Product']['product_purchase_price'] = isset($decoded['product_purchase_price']) ? $decoded['product_purchase_price'] : '';
        $product['Product']['product_sale_quantity'] = isset($decoded['product_sale_quantity']) ? $decoded['product_sale_quantity'] : '';
        $product['Product']['product_purchase_quantity'] = isset($decoded['product_purchase_quantity']) ? $decoded['product_purchase_quantity'] : '';
        $product['Product']['status'] = Configure::read('App.Status.active');
        
       
        if($this->Product->saveAll($product)){
        	$responseArr['product_id'] = $this->Product->id; 
            $responseArr['status'] = 'success';
          
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Product->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "add_product";
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
    
    
      function product_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $this->loadModel("Product");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        
        if(!empty($user_id)){
            $data = $this->Product->find('all',array('conditions'=>
                                                    array( 'Product.user_id'=>$user_id, 'Product.status'=>Configure::read('App.Status.active')),
                                                    'order' => array('Product.modified' => 'DESC')
                                                    ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'ユーザーは存在しません。', 'msg1'=> 'User does not exist.'));
            }else{

                if(!empty($data)){
                    $i=0;
                    foreach ($data as $key => $value) {
					  $customerData['Product'][$i] = $value['Product'];
                        $i++;
                    }

                }else{
                	$customerData[$i]['Product']['msg'] = 'レコードが見つかりませんでした。';
                    $customerData[$i]['Product']['msg1'] = 'No Record Found.';
                    $customerData[$i]['Product']['status'] = 'error';
                }
                $jsonEncode = json_encode($customerData);
            }
        }else{
            $customerData[$i]['Product']['msg1'] = '商品は存在しません.';
             $customerData[$i]['Product']['msg'] = 'Product does not exist.';
            $customerData[$i]['Product']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Product->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "product_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

/****************************************************************************************************************************************
     * NAME: delete_product
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
    function delete_product($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('Product');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->Product->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => 'Product deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Product deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Product does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Product->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_product";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 





    /**************************All Sell Section***********************************/


/**************************************************************************
     * NAME: get_today_sell
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
    
    
    function get_today_sell($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel('CustomerHistory');
        $this->loadModel('NoteService');
        $this->loadModel('NoteProduct');
        $this->loadModel('NoteImage');
        $this->loadModel('Product');
        $this->loadModel('User');
        $this->loadModel('Employee');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $today_date = isset($decoded['today_date']) ? $decoded['today_date'] : '';
        $todaySell  = $responseArr  = array();

        if(!empty($user_id)){
            $this->CustomerHistory->bindModel(array('hasMany' => array('NoteService', 'NoteProduct')));
            $customerAnalysisData=$this->CustomerHistory->find('all', array('conditions'=>array('CustomerHistory.user_id'=>$user_id, 'CustomerHistory.date'=>$today_date)));
            //print_r($customerAnalysisData);die;
            $serviceCashTotalPrice = $serviceCardTotalPrice =  $serviceTotalPrice =  0;
            $productCashTotalPrice =  $productCardTotalPrice =  $productTotalPrice =  0;
            $nailServiceTotalPrice = $nailServicePrice = $estheServicePrice = $estheServiceTotalPrice = $eyelashServicePrice =  $eyelashServiceTotalPrice =  $bodyServicePrice =  $bodyServiceTotalPrice =  $hairremoveServicePrice =  $hairremoveServiceTotalPrice = $facialServicePrice = $facialServiceTotalPrice =  0;
            $sellCashPrice = $sellCardPrice = $sellTotalPrice = $emp = 0;
            $customerArray =$employeeArray =array();
            setlocale(LC_MONETARY,"JPY");
            foreach ($customerAnalysisData as $customerAnalysisKey => $customerAnalysisValue) {
                if(isset($customerAnalysisValue['NoteService'])){
                    foreach ($customerAnalysisValue['NoteService'] as $serviceKey => $serviceValue) {
                        if($serviceValue['payment_type'] == '現金'){
                            if(($serviceValue['service_id'] != 0)){
                                $serviceCashPrice = $this->priceChangeInt($serviceValue['service_price']);
                                $serviceCashTotalPrice = ($serviceCashTotalPrice + $serviceCashPrice);
                            }
                        } 
                        if($serviceValue['payment_type'] == 'カード'){
                            if(($serviceValue['service_id'] != 0)){
                                $serviceCardPrice = $this->priceChangeInt($serviceValue['service_price']);
                                $serviceCardTotalPrice = ($serviceCardTotalPrice + $serviceCardPrice);
                            }
                        } 
                        if(($serviceValue['payment_type'] == 'カード') || ($serviceValue['payment_type'] == '現金')){  

                            if(($serviceValue['service_id'] == '1') || ($serviceValue['service_id'] == 1)){
                                $nailServicePrice = $this->priceChangeInt($serviceValue['service_price']);
                                $nailServiceTotalPrice = ($nailServiceTotalPrice + $nailServicePrice);
                            }
                            if(($serviceValue['service_id'] == '2') || ($serviceValue['service_id'] == 2)){
                                $estheServicePrice = $this->priceChangeInt($serviceValue['service_price']);
                                $estheServiceTotalPrice = ($estheServiceTotalPrice + $estheServicePrice);
                            }
                            if(($serviceValue['service_id'] == '3') || ($serviceValue['service_id'] == 3)){
                                $eyelashServicePrice = $this->priceChangeInt($serviceValue['service_price']);
                                $eyelashServiceTotalPrice = ($eyelashServiceTotalPrice + $eyelashServicePrice);
                            }
                            if(($serviceValue['service_id'] == '4') || ($serviceValue['service_id'] == 4)){
                                $bodyServicePrice = $this->priceChangeInt($serviceValue['service_price']);
                                $bodyServiceTotalPrice = ($bodyServiceTotalPrice + $bodyServicePrice);
                            }
                            if(($serviceValue['service_id'] == '5') || ($serviceValue['service_id'] == 5)){
                                $hairremoveServicePrice = $this->priceChangeInt($serviceValue['service_price']);
                                $hairremoveServiceTotalPrice = ($hairremoveServiceTotalPrice + $hairremoveServicePrice);
                            }
                            if(($serviceValue['service_id'] == '6') || ($serviceValue['service_id'] == 6)){
                                $facialServicePrice = $this->priceChangeInt($serviceValue['service_price']);
                                $facialServiceTotalPrice = ($facialServiceTotalPrice + $facialServicePrice);
                            }
                        }

                        if(isset($serviceValue['employee_id']) && ($serviceValue['employee_id']!=0)){
                            $employeeArray[$emp]['id'] = $serviceValue['employee_id'];
                            $employeeArray[$emp]['price'] = $this->priceChangeInt($serviceValue['service_price']);
                            array_push($customerArray,$serviceValue['customer_id']);
                            $employeeArray[$emp]['customer'] = $customerArray;
                            $emp++;
                        }



                    }
                   // $serviceTotalPrice = ($serviceCashTotalPrice + $serviceCardTotalPrice);
                }
                if(isset($customerAnalysisValue['NoteProduct'])){
                    foreach ($customerAnalysisValue['NoteProduct'] as $productKey => $productValue) {
                        if($productValue['payment_type'] == '現金'){
                            $productCashPrice = $this->priceChangeInt($productValue['sale_price']);
                            $productCashTotalPrice = ($productCashTotalPrice + $productCashPrice);
                        } 
                        if($productValue['payment_type'] == 'カード'){
                            $productCardPrice = $this->priceChangeInt($productValue['sale_price']);
                            $productCardTotalPrice = ($productCardTotalPrice + $productCardPrice);
                        }
                        if(isset($productValue['employee_id']) && ($productValue['employee_id']!=0)){
                            $employeeArray[$emp]['id'] = $productValue['employee_id'];
                            $employeeArray[$emp]['price'] = $this->priceChangeInt($productValue['sale_price']);
                            array_push($customerArray,$serviceValue['customer_id']);
                            $employeeArray[$emp]['customer'] = $customerArray;
                            $emp++;
                        }
                    }
                    $productTotalPrice = ($productCashTotalPrice + $productCardTotalPrice);
                }
             }
             //print_r($employeeArray);die;
             if(!empty($employeeArray)){
                $m =0;
                foreach ($employeeArray as $employeeKey => $employeeValue) {
                    $todaySell['Staff'][$m]['staff_name'] =$this->get_employee_name($employeeValue['id']);
                    $todaySell['Staff'][$m]['total_sell'] =number_format($employeeValue['price']).'円';
                    $countCustomer = count($employeeValue['customer']);
                    $todaySell['Staff'][$m]['customer_count'] =(string)$countCustomer;
                    $m++;
                }

             }
               

            $sellCashPrice = ($serviceCashTotalPrice + $productCashTotalPrice);
            $sellCardPrice = ($serviceCardTotalPrice + $productCardTotalPrice);

            $sellTotalPrice = ($sellCashPrice + $sellCardPrice);

            $todaySell['TodaySell']['total_cash_price'] =number_format($sellCashPrice).'円';//(string)$sellCashPrice;
            $todaySell['TodaySell']['total_card_price'] =number_format($sellCardPrice).'円';//(string)$sellCardPrice;
            $todaySell['TodaySell']['product_sell'] =number_format($productTotalPrice).'円';//(string)$productTotalPrice;
            $todaySell['TodaySell']['total_sell'] =number_format($sellTotalPrice).'円';//(string)$sellTotalPrice;


            $todaySell['Service'][0]['service_name'] =$this->get_service_name(1);
            $todaySell['Service'][0]['total_sell'] =number_format($nailServiceTotalPrice).'円';
            $todaySell['Service'][1]['service_name'] =$this->get_service_name(2);
            $todaySell['Service'][1]['total_sell'] =number_format($estheServiceTotalPrice).'円';
            $todaySell['Service'][2]['service_name'] =$this->get_service_name(3);
            $todaySell['Service'][2]['total_sell'] =number_format($eyelashServiceTotalPrice).'円';
            $todaySell['Service'][3]['service_name'] =$this->get_service_name(4);
            $todaySell['Service'][3]['total_sell'] =number_format($bodyServiceTotalPrice).'円';
            $todaySell['Service'][4]['service_name'] =$this->get_service_name(5);
            $todaySell['Service'][4]['total_sell'] =number_format($hairremoveServiceTotalPrice).'円';
            $todaySell['Service'][5]['service_name'] =$this->get_service_name(5);
            $todaySell['Service'][5]['total_sell'] =number_format($facialServiceTotalPrice).'円';

            $jsonEncode = json_encode($todaySell);
       
        }else{

            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->CustomerHistory->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_today_sell";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

    
    function list_messages($userId) {
        define('PROJECT_LIBS', dirname(dirname(__FILE__)));
        require(PROJECT_LIBS. '/Vendor/autoload.php');    
 
       
        $client = new Google_Client();

        $client->setApplicationName('Jts Board');
        $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);

        $credentials =  PROJECT_LIBS.'/Vendor/client_secret_428382209403-kejkirln30v996j2qm3dg86u22oecria.apps.googleusercontent.com.json';
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');
         

        $this->loadModel('Service');
        $this->loadModel('Customer');
        $this->loadModel('Reservation');
        
        // Load previously authorized credentials from a file.
        $credentialsPath = 'token.json';
       // print_r($credentialsPath);die;
        if (file_exists($credentialsPath)) {
           $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            $authCode = '4/XwBEJfT3zxWqaBJ7Jbqto-DTHbD_8U_7zaTwwzcvl8tD8HZTSIgBUHFwWJ_Aiax7Vf-1EhfCgvKeKSPQkrA1JPU';

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        
        $client->setAccessToken($accessToken);
       // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
       
        $service = new Google_Service_Gmail($client);
        $opt_param = array();
        $opt_param['labelIds'] = array('Label_2');
        
        $labelsResponse = $service->users_labels->listUsersLabels($userId);
        
        $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);  
        
        $messages =array();
       
        if ($messagesResponse->getMessages()) {
            $messages = array_merge($messages, $messagesResponse->getMessages());
            $pageToken = $messagesResponse->getNextPageToken();
        }
        
        $i =$j=0;
        $reservation = array();
        $user_id= '33';
        $servicelist = $this->Service->find('all', array('fields' => array('Service.id', 'Service.name')));
        $addCustomer =array();
        foreach ($messages as $message) {
            $messageData = $service->users_messages->get($userId, $message->getId());
            $decodedBody= quoted_printable_decode($this->base64url_decode($messageData->payload->body->data));

            $reservation_number = $this->get_string_between($decodedBody, '予約番号', '■氏名');
            $name = $this->get_string_between($decodedBody, '氏名', '■来店日時');
            $reservation_datetime = $this->get_string_between($decodedBody, '来店日時', '■指名スタッフ');
            $s = explode('　', $reservation_datetime);
            if(isset($s[1]) && !empty($s[1])){
            	$reservation_datetime = $s[1];	
            }else{
            	$reservation_datetime = $s[0];
            }	
            $reservation_datetime = 'start'.$reservation_datetime.'end';
          	$y = $this->get_string_between($reservation_datetime, 'start', '年');
            $y = trim($y); 
            $m = $this->get_string_between($reservation_datetime, '年', '月');
            $d = $this->get_string_between($reservation_datetime, '月', '日');
            $reservation_time  = $this->get_string_between($reservation_datetime, '）', 'end');
            $reservation_date = $y.'-'.$m.'-'.$d;
           

            $designatd_staff = $this->get_string_between($decodedBody, '指名スタッフ', '■メニュー');
            $menu = $this->get_string_between($decodedBody, 'メニュー', '（');
            $duration = $this->get_string_between($decodedBody, '所要時間目安：', '時間');
            $coupon = $this->get_string_between($decodedBody, 'ご利用クーポン', '■合計金額');
            $reservation_total = $this->get_string_between($decodedBody, '予約時合計金額', '円');
            $point_use = $this->get_string_between($decodedBody, '今回の利用ポイント', 'ポイント');
            $payment_total = $this->get_string_between($decodedBody, 'お支払い予定金額　', '円');
            $image = $this->get_string_between($decodedBody, 'なりたいイメージ', '■ご要望・ご相談');
            $question = $this->get_string_between($decodedBody, '質問：', '回答：');
            $answer = $this->get_string_between($decodedBody, '回答：', 'PC版SALON');
            



            $services =array();
            $s = 0;
            foreach ($servicelist as $key => $value) {
                 if(strpos($decodedBody, $value['Service']['name'])){
                    $services[$s]['id'] =$value['Service']['id'];
                    $services[$s]['name'] =$value['Service']['name'];
                    $s++;
                }
            }
            
           
            if(!empty($point_use)){
                if (strpos($point_use, '利用なし') !== false) {
                   $point_use = '';
                }
            }  
            
            if(empty($point_use) && empty($payment_total)){
                $payment_total = $reservation_total;
            }

            if(!empty($coupon) && empty($payment_total) && empty($reservation_total)){
            	$answer = $this->get_string_between($decodedBody, '￥', '→');
            	//$answer = $this->get_string_between($decodedBody, '回答：', 'PC版SALON');
               
                $arrTotal = explode('￥', $coupon);
                if(isset($arrTotal[2]) && !empty($arrTotal[2])){
                	$reservation_total = substr($arrTotal[2],0,4);
                	$payment_total = str_replace('→', '', $arrTotal[1]);

                }
            }

            $cusromer_id = '';
            
            $reservationData = $this->Reservation->find('first', array('conditions'=> array( 'Reservation.reservation_number'=>$reservation_number), 'fields' =>array('Reservation.reservation_number')));
            if(!isset($reservationData['Reservation']['reservation_number']) && empty($reservationData['Reservation']['reservation_number'])){

                if(!empty($name)){
                    $nick_name = $this->get_string_between($name, '（', '）');
                    $fullName = explode(' ', $nick_name);
                    if(isset($fullName[1]) && !empty($fullName[1])){
                        $first_name = trim($fullName[1]);
                        $last_name = trim($fullName[0]);
                    }else{
                        $first_name = trim($nick_name);
                        $last_name ='';
                    }

                     $kana_name = $this->get_string_between($decodedBody, '氏名', '（');
                     $kana_name_arr = explode(' ', $kana_name);
                     if(isset($kana_name_arr[1]) && !empty($kana_name_arr[1])){
                        $kana_first_name = trim($kana_name_arr[1]);
                        $kana_last_name = trim($kana_name_arr[0]);
                     }else{
                        $kana_first_name = trim($kana_name);
                        $kana_last_name ='';
                     }



                    $full_name = $first_name." ".$last_name;
                    $condition = array('Customer.user_id' => $user_id,
                                        'OR' => array(
                                            array(
                                                'Customer.first_name' => $first_name,
                                                'Customer.last_name' => $last_name
                                            ),
                                            'Customer.name' => $full_name
                                        ));

                    $customerData = $this->Customer->find('first', array('conditions'=> $condition, 'Customer.status'=>Configure::read('App.Status.active')));
                    if(isset($customerData['customer']['id']) && !empty($customerData['customer']['id'])){
                        $cusromer_id = $customerData['customer']['id'];
                        if(empty($customerData['customer']['last_visited']) || ($customerData['customer']['last_visited'] =='null'))
                            $customerData['customer']['last_visited'] = $customerData['customer']['modified'];
                        $reservation[$i]['Reservation']['last_visited'] = $customerData['customer']['last_visited'];
                    }else{
                        //$addCustomer =array();
                        $addCustomer[$j]['Customer']['user_id'] = $user_id;
                        $addCustomer[$j]['Customer']['service_id'] = '1';
                        $addCustomer[$j]['Customer']['name'] = $full_name;
                        $addCustomer[$j]['Customer']['first_name'] = $first_name;
                        $addCustomer[$j]['Customer']['last_name'] = $last_name;
                        $addCustomer[$j]['Customer']['kana_first_name'] = $kana_first_name;
                        $addCustomer[$j]['Customer']['kana_last_name'] = $kana_last_name;
                        $j++;
                       
                        $cusromer_id = $this->Customer->id;
                        $reservation[$i]['Reservation']['last_visited'] = $reservation_datetime;
                    }

                }

                $reservation[$i]['Reservation']['user_id'] = $user_id;
                $reservation[$i]['Reservation']['customer_id'] = $cusromer_id;
                $reservation[$i]['Reservation']['reservation_number'] = $reservation_number;
                $reservation[$i]['Reservation']['name'] = $full_name;
                $reservation[$i]['Reservation']['channel'] = '';
                $reservation[$i]['Reservation']['reservation_date'] = $reservation_date;
                $reservation[$i]['Reservation']['reservation_time'] = $reservation_time;
                $reservation[$i]['Reservation']['designatd_staff'] = $designatd_staff;
                if(!empty($menu)){
                    $reservation[$i]['Reservation']['menu'] = '1';
                    $reservation[$i]['Reservation']['menu_text'] = $menu;
                }else{
                    $reservation[$i]['Reservation']['menu'] = '0';
                    $reservation[$i]['Reservation']['menu_text'] = '';
                }
                $reservation[$i]['Reservation']['estimation_time'] = $duration;
               
                $reservation[$i]['Reservation']['services'] =  json_encode($services);
                $reservation[$i]['Reservation']['coupon'] =  $coupon;
                $reservation[$i]['Reservation']['reservation_total'] = $reservation_total;
                $reservation[$i]['Reservation']['point_use'] = $point_use;
                $reservation[$i]['Reservation']['payment_total'] = $payment_total;
                $reservation[$i]['Reservation']['msg'] = $decodedBody;
                $i++;
            }
            
        }
        if(isset($addCustomer['Customer']) && !empty($addCustomer))
         $this->Customer->saveAll($addCustomer);
        
        $this->Reservation->saveAll($reservation);
            
       
        echo 'successfully done.';die;

        
    }








    protected function base64url_decode($data) { 
            return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 

    }

    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }






    /************************ Push Notification ***********************/

    public function send_notification_for_iphone($deviceToken = null, $message = null){
           // $deviceToken = "6e6cb6d2d057235fbf0232308ee29311e8f8153184ed1a0";
            //$message = "Jai Hind !!!";
            
          // phpinfo();die;
            $message = array('aps'=>array('alert'=>$message));
            
            
            // Put your private key's passphrase here:
            $passphrase = '';

            // Put your alert message here:
            
            $base_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR;



            $apnsHost = 'gateway.push.apple.com';
            $apnsCert = $base_path.'/pushpem/ck.pem';
            $passphrase = '123456';
            $apnsPort = 2195;
            $streamContext = stream_context_create();
            stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
            stream_context_set_option($streamContext, 'ssl', 'passphrase', $passphrase);
            $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
            $payload['aps'] = array('alert' => 'Oh hai!', 'badge' => 1, 'sound' => 'default');
            $output = json_encode($payload);
            //$deviceToken = pack('H*', str_replace(' ', '', $deviceToken))
           // $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($message)) . $message;
            $apnsMessage = chr(0) . chr(0) . chr(32) . $deviceToken . chr(0) . chr(strlen($output)) . $output;
            fwrite($apns, $apnsMessage);
            socket_close($apns);
            fclose($apns);


/*

            $payload['aps'] = array('alert' => 'Oh hai!', 'badge' => 1, 'sound' => 'default');
            $output = json_encode($payload);
            $deviceToken = pack('H*', str_replace(' ', '', $deviceToken))
            $apnsMessage = chr(0) . chr(0) . chr(32) . $deviceToken . chr(0) . chr(strlen($output)) . $output;
            fwrite($apns, $apnsMessage);
            socket_close($apns);
            fclose($apns);






            //echo $base_path;die;
              //ck_dev.pem
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert',$base_path.'/ck.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            
            //gateway.sandbox.push.apple.com
            // Open a connection to the APNS server
            
            $fp = stream_socket_client(
                'ssl://gateway.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

            if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);
                
            //echo 'Connected to APNS' . PHP_EOL;

            
            $message['aps']['sound']        =   'default';
            
            $body = json_encode($message);
            
            $message = $body;
        
            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($message)) . $message;
     
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            // Close the connection to the server
            fclose($fp);
            */
    }

    function sendAPNSmessage($devicetokens=null, $type=null, $body=null) {


        $url = 'https://gateway.push.apple.com:2195';
        $base_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR;
        $tCert =  $base_path.'/pushpem/ck.pem';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSLCERT, $tCert);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, '123456');
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"device_tokens": ["6e6cb6d2d057235fbf0232308ee29311e8f8153184ed1a0"], "aps": {"alert": "test message one!"}}');

        $curl_scraped_page = curl_exec($ch);
        echo $curl_scraped_page;
         echo "success";
        print_r($curl_scraped_page);die;

        die;
        
        // Provide the Host Information.
        $tHost = 'gateway.push.apple.com';
        $tPort = 2196;
        // Provide the Certificate and Key Data.
        $base_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR;
        $tCert =  $base_path.'/pushpem/ck.pem';
        // Provide the Private Key Passphrase (alternatively you can keep this secrete 
        // and enter the key manually on the terminal -> remove relevant line from code).
        // Replace XXXXX with your Passphrase
        $tPassphrase = '123456';
        // Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).
        // Replace this token with the token of the iOS device that is to receive the notification.
        $tToken = '6e6cb6d2d057235fbf0232308ee29311e8f8153184ed1a0';
        // The message that is to appear on the dialog.
        $tAlert = 'You have a LiveCode APNS Message';
        // The Badge Number for the Application Icon (integer >=0).
        $tBadge = 8;
        // Audible Notification Option.
        $tSound = 'default';
        // The content that is returned by the LiveCode "pushNotificationReceived" message.
        $tPayload = 'APNS Message Handled by LiveCode';
        // Create the message content that is to be sent to the device.
        $tBody['aps'] = array (
            'alert' => $tAlert,
            'badge' => $tBadge,
            'sound' => $tSound,
            );
        $tBody ['payload'] = $tPayload;
        // Encode the body to JSON.
        $tBody = json_encode ($tBody);
        // Create the Socket Stream.
        $tContext = stream_context_create ();
        stream_context_set_option ($tContext, 'ssl', 'local_cert', $tCert);
        // Remove this line if you would like to enter the Private Key Passphrase manually.
        stream_context_set_option ($tContext, 'ssl', 'passphrase', $tPassphrase);
        // Open the Connection to the APNS Server.
        $tSocket = stream_socket_client ('ssl://'.$tHost.':'.$tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
        // Check if we were able to open a socket.
       // if (!$tSocket)
          //  exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);
        // Build the Binary Notification.






        $tMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $tToken) .  pack ('n', strlen ($tBody)) . $tBody;
        // Send the Notification to the Server.
        $tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
        if ($tResult)
            echo 'Delivered Message to APNS' . PHP_EOL;
        else
            echo 'Could not Deliver Message to APNS' . PHP_EOL;
        // Close the Connection to the Server.
        fclose ($tSocket);

    

    /*   
     $deviceToken = '6e6cb6d2d057235fbf0232308ee29311e8f8153184ed1a0';
     $passphrase = '123456';
     $message = 'My first push notification!';
     $base_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR;

     ////////////////////////////////////////////////////////////////////////////////
     $apnsCert = $base_path.'/pushpem/ck.pem';
     $ctx = stream_context_create();
     stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
     stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
     var_dump($ctx);die;
    // xdebug_break();
     // Open a connection to the APNS server
     $fp = stream_socket_client(
          'ssl://gateway.push.apple.com:2195', $err,
          $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

     if (!$fp)
          exit("Failed to connect: $err $errstr" . PHP_EOL);

     echo 'Connected to APNS' . PHP_EOL;

     // Create the payload body
     $body['aps'] = array(
          'alert' => $message,
          'sound' => 'default'
          );

     // Encode the payload as JSON
     $payload = json_encode($body);

     // Build the binary notification
     $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

     // Send it to the server
     $result = fwrite($fp, $msg, strlen($msg));

     if (!$result)
          echo 'Message not delivered' . PHP_EOL;
     else
          echo 'Message successfully delivered' . PHP_EOL;

     // Close the connection to the server
     fclose($fp);

     */


}
    


    


    
}
