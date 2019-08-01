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
class WebServicesv22Controller extends AppController{
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
        $this->loadModel("User");
        $this->loadModel("RecordData");
        $this->Auth->allow('*');
        $this->RequestHandler->addInputType('json', array('json_decode', true));
       // App::import('Vendor', 'Google', array('file' => 'Google' . DS . 'autoload.php'));
        date_default_timezone_set("Asia/Tokyo");

       /* $allUser =  $this->User->find('all');
        if(isset($allUser[0]['User']) && !empty($allUser[0]['User'])){
            foreach ($allUser as $userKey => $userValue) {
                 if(isset($userValue['User']['cash_box']) && !empty($userValue['User']['cash_box']) && ($userValue['User']['cash_box'] !='null')) {
                    if($userValue['User']['cash_box_date'] != date(Y-m-d)){
                        $this->User->id = $userValue['User']['id'];
                        $this->User->saveField('cash_box' , '' );
                    }
                 }   
            }
        }*/
    
        
    }

    /************************************************************************************************************************************
     * 1 NAME: login
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
       
        // now();die;
        // echo$date = date('m/d/Y h:i:s a', time());die;
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

                 $employee_id = '';
                 $employee_name = isset($data['User']['name']) ? $data['User']['name'] : ''; 
                 $employee_image = isset($data['User']['image']) ? $data['User']['image'] : ''; 
                 
                 /*if(isset($user_emp_code) && !empty($user_emp_code) && $user_emp_code!='null'){
                    $userEmpId = $this->Employee->find('first',array('conditions'=>  array('Employee.emp_code'=>$user_emp_code),'fields'=>array('Employee.id','Employee.name','Employee.image')));
                    $employee_id = isset($userEmpId['Employee']['id']) ? $userEmpId['Employee']['id'] : ''; 
                    $employee_name = isset($userEmpId['Employee']['name']) ? $userEmpId['Employee']['name'] : ''; 
                    $employee_image = isset($userEmpId['Employee']['image']) ? $userEmpId['Employee']['image'] : ''; 

                 }else{
                    $employee_id = '';
                    $employee_name = isset($data['User']['name']) ? $data['User']['name'] : ''; 
                    $employee_image = ''; 
                 }  */

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
        $this->loadModel("Category");
        $this->loadModel("UserCategory");
       
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
            $i=0;
            $userCategoryData = array();
            if(!empty($user_id)){
                $data = $this->Category->find('all',array('conditions'=>array( 'Category.status'=>Configure::read('App.Status.active')),'order' => array('Category.id' => 'DESC')));
                if(!empty($data)){
                    foreach ($data as $key => $value) {

                        $userCategoryData[$i]['UserCategory']['user_id'] = $user_id;
                        $userCategoryData[$i]['UserCategory']['parent_id'] = $value['Category']['parent_id'];
                        $userCategoryData[$i]['UserCategory']['name'] = $value['Category']['name'];
                        $userCategoryData[$i]['UserCategory']['japanese_name'] = $value['Category']['japanese_name'];
                        $userCategoryData[$i]['UserCategory']['image'] = $value['Category']['image'];
                        $userCategoryData[$i]['UserCategory']['status'] = $value['Category']['status'];
                        $i++;
                    }
                    $this->UserCategory->saveAll($userCategoryData);
                }
            }       

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
        $cash_box = $user['User']['cash_box'] = isset($decoded['cash_box']) ? $decoded['cash_box'] : '';

        if(!empty($cash_box)){
            $user['User']['cash_box_date'] = date('Y-m-d');
        }
        
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
                                                    array( 'Customer.user_id'=>$user_id, 'Customer.status !='=>2),
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
            }elseif(!empty($start_date) && empty($end_date)){
                $start = date( 'Y-m-d H:i:s', strtotime( $start_date ) );    
                $end = date( 'Y-m-d H:i:s', (strtotime($start_date) + 86400) );    
                $conditions["Customer.modified BETWEEN ? and ?"] = array($start, $end);
            }elseif(!empty($end_date) && empty($start_date)){
                $start = date( 'Y-m-d H:i:s', strtotime( $end_date ) );    
                $end = date( 'Y-m-d H:i:s',(strtotime($end_date) + 86400) );    
                $conditions["Customer.modified BETWEEN ? and ?"] = array($start, $end);
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




            $data = $this->Customer->find('all',array('conditions' => $conditions, 'order' => array('Customer.modified' => 'DESC') ));

            if(!empty($data)){
            
            foreach ($data as $key => $value) {

                $customerData['Customer'][$i]['id'] = $value['Customer']['id'];
                $customerData['Customer'][$i]['name'] = $value['Customer']['last_name']." ".$value['Customer']['first_name'];
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
            $this->Customer->bindModel(array('hasMany' => array('Esthe','Eyelush', 'Body', 'HairRemoval', 'PhotoFacial', 'ServiceDetail' =>array('order' =>array('ServiceDetail.modified' =>'DESC'))  )));
            $data = $this->Customer->find('first',array('conditions'=> array( 'Customer.id'=>$customer_id),'order' => array('Customer.id' => 'DESC') ));
            // pr($data);die;
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
                        $serviceData['Service'][$i]['service_status'] = '0';
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
                        $serviceData['Service'][$i]['service_status'] = '0';
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
                        $serviceData['Service'][$i]['service_status'] = '0';
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
                        $serviceData['Service'][$i]['service_status'] = '0';
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
                        $serviceData['Service'][$i]['service_status'] = '0';
                        $i++;
                    }
                }

                if(isset($data['ServiceDetail']) && !empty($data['ServiceDetail'])){
                     $serviceId = 0;
                     $createdNewDate = date('Y-m-d', strtotime('1979-02-02'));
                    foreach ($data['ServiceDetail'] as $serviceDetailkey => $serviceDetailValue) {
                        $createdDate = date('Y-m-d', strtotime($serviceDetailValue['created']));
                        if(($serviceDetailValue['service_id'] != $serviceId) || ($createdDate != $createdNewDate) ){
                            $serviceId = $serviceDetailValue['service_id'];
                            $createdNewDate = date('Y-m-d', strtotime($serviceDetailValue['created']));
                            $serviceNewData = $this->Service->find('first',array('conditions'=> array( 'Service.id'=>$serviceId)));
                            $serviceData['Service'][$i]['service_id'] = $serviceId;
                            $serviceData['Service'][$i]['customer_id'] = $customer_id ;
                            $serviceData['Service'][$i]['customer_service_id'] = $serviceDetailValue['id'];
                            $serviceData['Service'][$i]['service_name'] = $serviceNewData['Service']['name'];
                            $serviceData['Service'][$i]['date'] = $serviceDetailValue['created'];
                            $serviceData['Service'][$i]['created_date'] = $serviceDetailValue['form_date'];
                            $serviceData['Service'][$i]['service_status'] = '1';
                            $i++;
                            
                        }
                        
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
     * NAME: form_service_detail
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


     function form_service_detail($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        
        $this->loadModel("ServiceDetail");
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $service_id = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $created_date = isset($decoded['created_date']) ? $decoded['created_date'] : '';
        if(!empty($customer_id) &&  !empty($created_date) && !empty($service_id) ){
            $data = $this->ServiceDetail->find('all',array('conditions'=>  array( 'ServiceDetail.customer_id'=>$customer_id ,'ServiceDetail.service_id'=>$service_id ,'ServiceDetail.form_date'=>$created_date )));
            // pr($data);die;
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'Customer does not exist.'));
            }else{
                $allData['Service']['id'] =  $data[0]['ServiceDetail']['id'];
                $allData['Service']['user_id'] =  $data[0]['ServiceDetail']['user_id'];
                $allData['Service']['service_id'] =  $data[0]['ServiceDetail']['service_id'];
                $allData['Service']['customer_id'] =  $data[0]['ServiceDetail']['customer_id'];
                $i = 0;
                foreach ($data as $serviceKey => $serviceValue) {
                     $allData['Service']['form_data'][$i]['label'] = $serviceValue['ServiceDetail']['f_label'];
                     $allData['Service']['form_data'][$i]['value'] =  $serviceValue['ServiceDetail']['f_value'];
                     $i++;
                }

               
                $jsonEncode =  json_encode($allData);
            }
        }else{
            $customerData[$i]['Customer']['msg'] = 'お客様は存在しません。';
            $customerData[$i]['Customer']['msg1'] = 'Please add customer id.';
            $customerData[$i]['Customer']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->ServiceDetail->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "form_service_detail";
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
                                                          'Customer.tel'=>$tel
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
       /* echo $activation_url = Router::url(array(
                                                    'controller' => 'users',
                                                    'action' => 'get_password',
                                                    base64_encode('shweta@yopmail.com'),
                                                    '122cc34307b05941b8d2'
                                                    ), true);die;*/
        $data = file_get_contents('php://input');
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $responseArr = array();
        $email = isset($decoded['email']) ? strtolower($decoded['email']) : '';
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
                $mail_message = str_replace(array('{NAME}','{EMAIL}', "{ACTIVATION_LINK}"), array($userDetail['User']['name'],$userDetail['User']['email'], $activation_link), $forgetPassMail['Template']['content']);
                
                $to = $userDetail['User']['email'];
                $from = Configure::read('App.AdminMail');
                
                $template='default';
                $this->set('message', $mail_message);
                $template='default';
                
                if(parent::sendMail($to, $subject, $mail_message, $from, $template)){
                    $responseArr = array('msg' => 'あなたのメールアドレスからアカウントを有効にしてください。', 'msg1' => 'Please check your mail account for reset password.', 'status' => 'success' );
                    $jsonEncode = json_encode($responseArr);
                    return $jsonEncode;
                
                }else{
                     $responseArr = array('msg1' => 'Email does not exist.', 'msg' => '電子メールは存在しません。', 'status' => 'error' );
                   
                     $jsonEncode = json_encode($responseArr);
                    return $jsonEncode;
                }
            }else{
                 $responseArr = array('msg1' => 'Email does not exist.', 'msg' => '電子メールは存在しません。', 'status' => 'error' );
                   
                 $jsonEncode = json_encode($responseArr);
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
        $serviceArray =array();
        $i =0;
        foreach ($data  as $key => $value) {
            $serviceArray['Service'][$i]['id'] = $value['Service']['id'];
            $serviceArray['Service'][$i]['name'] = $value['Service']['name'];
            $serviceArray['Service'][$i]['color_code'] = $value['Service']['color_code'];
            $serviceArray['Service'][$i]['status'] = $value['Service']['status'];
            $serviceArray['Service'][$i]['created'] = $value['Service']['created'];
            $i++;
        }
        //echo '<pre>';
        //print_r($serviceArray);die;
        $jsonEncode = json_encode($serviceArray);
        $log = $this->Service->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "service_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }



    /**************************************************************************
     * NAME: form_service_list
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
    
    
     function form_service_list(){
        $data = file_get_contents('php://input');
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $user_id = isset($decoded['user_id']) ? strtolower($decoded['user_id']) : '';
        $this->loadModel("Service");
        $this->loadModel("CustomerForm");

        $serviceIds = $this->CustomerForm->find('list',array('conditions'=>array('CustomerForm.user_id'=> $user_id), 'fields'=>array('CustomerForm.service_id')));
        $data = $this->Service->find('all', array('conditions' => array('Service.user_id'=> $user_id, 'Service.status' =>Configure::read('App.Status.active'))));
        $serviceArray['Service'] =array();
        $i =0;
        foreach ($data  as $key => $value) {
            if(in_array($value['Service']['id'], $serviceIds)){
                $serviceArray['Service'][$i]['id'] = $value['Service']['id'];
                $serviceArray['Service'][$i]['name'] = $value['Service']['name'];
                $serviceArray['Service'][$i]['color_code'] = $value['Service']['color_code'];
                $serviceArray['Service'][$i]['form_status'] = '1';
                $serviceArray['Service'][$i]['created'] = $value['Service']['created'];
               
            }else{
                $serviceArray['Service'][$i]['id'] = $value['Service']['id'];
                $serviceArray['Service'][$i]['name'] = $value['Service']['name'];
                $serviceArray['Service'][$i]['color_code'] = $value['Service']['color_code'];
                $serviceArray['Service'][$i]['form_status'] = '0';
                $serviceArray['Service'][$i]['created'] = $value['Service']['created'];
                
            }  
            $i++;  
        }
        //echo '<pre>';
        //print_r($serviceArray);die;
        $jsonEncode = json_encode($serviceArray);
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
        $recordData['RecordData']['name'] = "service_color_list";
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
        
        $this->loadModel("Service");
        $this->loadModel("Color");
        $this->loadModel("ServiceColor");
         $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 
        $responseArr = array();
        
        $user_id = $serviceColor['ServiceColor']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $service_id = $serviceColor['ServiceColor']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $color_id = $serviceColor['ServiceColor']['color_id'] = isset($decoded['color_id']) ? $decoded['color_id'] : '';

        $ServiceColorData = $this->ServiceColor->find('first',array('conditions'=> array( 'ServiceColor.user_id'=>$user_id, 'ServiceColor.service_id'=>$service_id)));
        if(isset($ServiceColorData['ServiceColor']['id'])){
            $serviceData = $this->Service->find('first',array('conditions'=> array( 'Service.id'=>$color_id)));
            $colorData = $this->Color->find('first',array('conditions'=> array( 'Color.id'=>$color_id)));
            $serviceColor['ServiceColor']['color_name'] = isset($colorData['Color']['color_name']) ? $colorData['Color']['color_name'] : '';
            $serviceColor['ServiceColor']['color_code'] = isset($colorData['Color']['color_code']) ? $colorData['Color']['color_code'] : '';
            $serviceColor['ServiceColor']['service_name'] = isset($serviceData['Service']['name']) ? $serviceData['Service']['name'] : '';
            $serviceColor['ServiceColor']['id'] = $ServiceColorData['ServiceColor']['id'];
        }
        //print_r($serviceColor);die;

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
     * NAME: service_detail_list
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
             $emp_name = $userExist['User']['name'];
             $emp_image = $userExist['User']['image'];
             /*if(isset($userExist['User']['user_emp_code']) && !empty($userExist['User']['user_emp_code'])){
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
             }  */
        }
        $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date, 'CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id)));

        /********Start Note Images add********/
        $noteImages = array();
        $note_image = isset($decoded['note_image']) ? $decoded['note_image'] : '';
        $note_type = isset($decoded['note_type']) ? $decoded['note_type'] : '';
        
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
            $noteImages['NoteImage']['note_type'] = $note_type;
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

        $note_text = isset($decoded['note_text']) ? $decoded['note_text'] : '';
        

        if(isset($note_text) && !empty($note_text)){
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
            $noteImages['NoteImage']['note_text'] = $note_text;
            $noteImages['NoteImage']['note_type'] = $note_type;
            if($this->NoteImage->saveAll($noteImages)){
                 $note_image_id = $this->NoteImage->id; 

                /********Customer Modified date update ********/
                $customerData['Customer']['id'] = $customer_id;
                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
                $responseArr = array('note_image_id' => $note_image_id, 'msg'=>'顧客情報が正常に追加されました。',  'msg1'=>'Note   successfully added.', 'status' => 'success' );
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
        $this->loadModel('NoteTicket');
        $this->loadModel('NoteImage');
        $this->loadModel('Product');
        $this->loadModel('User');
        $this->loadModel('Employee');
        $this->loadModel('NoteRemainingTicket');
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
            // $this->NoteImage->bindModel(array('belongsTo' => array('Employee')));
       
            $this->CustomerHistory->bindModel(array('hasMany' => array('NoteService', 'NoteProduct', 'NoteTicket', 'NoteImage'=> array(
            'className' => 'NoteImage',
            'conditions' => array('NoteImage.delete_image_status' => '0'),
            'dependent' => true
        ))));
            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$id)));
           // echo '<pre>';
           // print_r($customerAnalysisData);die;

            $note_text = $customerAnalysisData['CustomerHistory']['note_text'];
            $customerAnalysisData['CustomerHistory'] =array();
            $totalNoteTicketAmount = 0;
            $ticketName = '';
            if(!empty($user_id) && !empty($customer_id)){
                $noteTicketData=$this->NoteTicket->find('all', array('conditions'=>array('NoteTicket.user_id'=>$user_id, 'NoteTicket.customer_id'=>$customer_id)));
            // print_r($noteTicketData);die;
                if(isset($noteTicketData[0]) && !empty($noteTicketData[0])){
                    foreach ($noteTicketData as $noteTicketKey => $noteTicketValue) {
                        $NoteRemainingTicketData= array();
                        $noteTicketAmount = $this->priceChangeInt($noteTicketValue['NoteTicket']['ticket_amount']); 
                        $NoteRemainingTicketData=$this->NoteRemainingTicket->find('first', array('conditions'=>array('NoteRemainingTicket.user_id'=>$user_id, 'NoteRemainingTicket.customer_id'=>$customer_id, 'NoteRemainingTicket.customer_history_id'=>$id, 'NoteRemainingTicket.note_ticket_id'=>$noteTicketValue['NoteTicket']['id']), 'order' => array('NoteRemainingTicket.id' => 'DESC')));

                        $customerAnalysisData['TicketList'][$noteTicketKey]['id'] = $noteTicketValue['NoteTicket']['id'];
                        $customerAnalysisData['TicketList'][$noteTicketKey]['name'] = $noteTicketValue['NoteTicket']['ticket_name'];
                        if(isset($NoteRemainingTicketData['NoteRemainingTicket']['remaining_amount']) && !empty($NoteRemainingTicketData['NoteRemainingTicket']['remaining_amount'])){

                            $customerAnalysisData['TicketList'][$noteTicketKey]['ticket_amount'] = $NoteRemainingTicketData['NoteRemainingTicket']['remaining_amount'];
                        }else{
                            if(isset($noteTicketValue['NoteTicket']['ticket_amount']) && !empty($noteTicketValue['NoteTicket']['ticket_amount'])){
                                $customerAnalysisData['TicketList'][$noteTicketKey]['ticket_amount'] = $noteTicketValue['NoteTicket']['ticket_amount'];
                            }else{
                                $customerAnalysisData['TicketList'][$noteTicketKey]['ticket_amount'] = $noteTicketValue['NoteTicket']['ticket_num_time'];
                            }    


                        }
                        /*if($noteTicketValue['NoteTicket']['id'] == '67'){
                            echo '<pre>';
                            print_r($noteTicketValue);
                            print_r($NoteRemainingTicketData);
                            print_r($customerAnalysisData);die;
                        }*/
                     
                    }
                }
            }

            $customerAnalysisData['CustomerHistory']['id'] =$id;
            $customerAnalysisData['CustomerHistory']['note_text'] =$note_text;
            if($totalNoteTicketAmount > 0){
                $customerAnalysisData['CustomerHistory']['is_ticket'] = '1';
                $customerAnalysisData['CustomerHistory']['ticket_amount'] = number_format($totalNoteTicketAmount).'円';
                $customerAnalysisData['CustomerHistory']['ticket_name'] = $ticketName;
            }else{
                $customerAnalysisData['CustomerHistory']['is_ticket'] = '0';
                $customerAnalysisData['CustomerHistory']['ticket_amount'] = number_format($totalNoteTicketAmount).'円';
                $customerAnalysisData['CustomerHistory']['ticket_name'] = $ticketName;
            }
            
            $serviceTotalPrice = $productTotalPrice = $grandTotalPrice =  0;
            if(isset($customerAnalysisData['NoteService'])){
                foreach ($customerAnalysisData['NoteService'] as $serviceKey => $serviceValue) {
                   $servicePrice = $this->priceChangeInt($serviceValue['service_price']); 
                   $serviceTotalPrice = ($serviceTotalPrice + $servicePrice);
                   if(isset($serviceValue['ticket_id']) && ($serviceValue['ticket_id'] > 0 )){
                        $getNoteTicket=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$serviceValue['ticket_id']))); 
                        if(isset($getNoteTicket['NoteTicket']['ticket_amount']))
                            $customerAnalysisData['NoteService'][$serviceKey]['ticket_amount'] = $getNoteTicket['NoteTicket']['ticket_amount'];
                   }

                }
            }
            if(isset($customerAnalysisData['NoteProduct'])){
                foreach ($customerAnalysisData['NoteProduct'] as $productKey => $productValue) {
                   $getProduct=$this->Product->find('first', array('conditions'=>array('Product.id'=>$productValue['product_id']))); 
                   if(isset($productValue['ticket_id']) && ($productValue['ticket_id'] > 0 )){
                        $getNoteTicket=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$productValue['ticket_id']))); 
                        if(isset($getNoteTicket['NoteTicket']['ticket_amount']))
                            $customerAnalysisData['NoteProduct'][$productKey]['ticket_amount'] = $getNoteTicket['NoteTicket']['ticket_amount'];
                   }
                   if(isset($getProduct['Product']['product_stock'])){
                        $customerAnalysisData['NoteProduct'][$productKey]['product_stock'] = $getProduct['Product']['product_stock'];
                   }
                   
                   $productPrice = $this->priceChangeInt($productValue['sale_price']); 
                   $productTotalPrice = ($productTotalPrice + $productPrice);
                }
            }
            if(isset($customerAnalysisData['NoteImage'])){

                foreach ($customerAnalysisData['NoteImage'] as $imageKey => $imageValue) {

                    if(isset($imageValue['employee_id']) && !empty($imageValue['employee_id'])){ 
                       $getImageEmp = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$imageValue['employee_id']))); 
                       
                       $customerAnalysisData['NoteImage'][$imageKey]['employee_name'] = isset($getImageEmp['Employee']['name']) ? $getImageEmp['Employee']['name'] : '';
                       $customerAnalysisData['NoteImage'][$imageKey]['employee_image'] = isset($getImageEmp['Employee']['name']) ? $getImageEmp['Employee']['image'] : '';
                    }
                    if(isset($imageValue['deleted_employee_id']) && !empty($imageValue['deleted_employee_id'])){ 
                       $getImageDelEmp = $this->Employee->find('first', array('conditions'=>array('Employee.id'=>$imageValue['deleted_employee_id']))); 
                       
                       $customerAnalysisData['NoteImage'][$imageKey]['deleted_employee_name'] = isset($getImageDelEmp['Employee']['name']) ? $getImageDelEmp['Employee']['name'] : '';
                       $customerAnalysisData['NoteImage'][$imageKey]['deleted_employee_image'] = isset($getImageDelEmp['Employee']['name']) ? $getImageDelEmp['Employee']['image'] : '';
                    }
                }

            }
            //echo '<pre>';
            //print_r($customerAnalysisData);die;
            $grandTotalPrice = ($serviceTotalPrice + $productTotalPrice);

            $customerAnalysisData['CustomerHistory']['service_total_price'] =number_format($serviceTotalPrice).'円';
            $customerAnalysisData['CustomerHistory']['product_total_price'] =number_format($productTotalPrice).'円';
            $customerAnalysisData['CustomerHistory']['grand_total_price'] =number_format($grandTotalPrice).'円';
            //echo '<pre>';
            //print_r($customerAnalysisData);die;
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

/**************************************************************************
     * NAME: get_ticket_list
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
    
    
    function get_ticket_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    
        $jsonEncode = '';
        $decoded = json_decode($data, true); 
        $this->loadModel('NoteTicket');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $ticketData = $responseArr  = array();
        if(!empty($user_id) && !empty($customer_id)){
            $noteTicketData=$this->NoteTicket->find('all', array('conditions'=>array('NoteTicket.user_id'=>$user_id, 'NoteTicket.customer_id'=>$customer_id)));
            //echo '<pre>';
            //print_r($noteTicketData);die;
            if(isset($noteTicketData[0]) && !empty($noteTicketData[0])){
                $i = 0;
                foreach ($noteTicketData as $noteTicketKey => $noteTicketValue) {
                        $ticketData['TicketList'][$i]['id'] = $noteTicketValue['NoteTicket']['id'];
                        $ticketData['TicketList'][$i]['name'] = $noteTicketValue['NoteTicket']['ticket_name'];
                        $ticketData['TicketList'][$i]['ticket_amount'] = $noteTicketValue['NoteTicket']['ticket_amount'];
                        $i++;
                }
                $jsonEncode = json_encode($ticketData);   
            }
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }

        $log = $this->NoteTicket->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_ticket_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

/**************************************************************************
     * NAME: get_ticket_by_ticket
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
    
    
    function get_ticket_by_ticket($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel('NoteTicket');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $ticket_amount = isset($decoded['ticket_amount']) ? $decoded['ticket_amount'] : '0';
        $responseArr  = $ticketData = array();
        if(!empty($user_id) && !empty($customer_id) && !empty($ticket_amount)){
            $noteTicketData=$this->NoteTicket->find('all', array('conditions'=>array('NoteTicket.user_id'=>$user_id, 'NoteTicket.customer_id'=>$customer_id)));
            //echo '<pre>';
            //print_r($noteTicketData);die;
            if(isset($noteTicketData[0]) && !empty($noteTicketData[0])){
                $i = 0;
                foreach ($noteTicketData as $noteTicketKey => $noteTicketValue) {
                    $noteTicketAmount = $this->priceChangeInt($noteTicketValue['NoteTicket']['ticket_amount']); 
                    $ticket_amount = $this->priceChangeInt($ticket_amount); 
                    if(($noteTicketAmount > 0) && ($noteTicketAmount > $ticket_amount)){
                        $ticketData['TicketList'][$i]['id'] = $noteTicketValue['NoteTicket']['id'];
                        $ticketData['TicketList'][$i]['name'] = $noteTicketValue['NoteTicket']['ticket_name'];
                        $ticketData['TicketList'][$i]['ticket_amount'] = $noteTicketValue['NoteTicket']['ticket_amount'];
                        $i++;
                    }

                }
                $jsonEncode = json_encode($ticketData);   
            }
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }

        $log = $this->NoteTicket->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_ticket_by_ticket";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }


/**************************************************************************
     * NAME: get_payment_method
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
    
    
    function get_payment_method($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $decoded = json_decode($data, true); 

        $this->loadModel('NoteTicket');
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customer_id = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $service_id = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $noteTicketList['TicketList'] = $responseArr  = array();
        if(!empty($user_id) && !empty($customer_id)){
            $conditions["NoteTicket.user_id"] = $user_id;
            $conditions["NoteTicket.customer_id"] = $customer_id;
            $noteTicketData=$this->NoteTicket->find('all', array('conditions'=>$conditions));
           // echo "<pre>";
            //print_r($noteTicketData);die;
           
            if(isset($noteTicketData[0]) && !empty($noteTicketData[0])){
                foreach ($noteTicketData as $noteTicketKey => $noteTicketValue) {
                    if(!empty($service_id)){
                        $noteTicketList['TicketList'][$noteTicketKey]['id'] = $noteTicketValue['NoteTicket']['id'];
                        $noteTicketList['TicketList'][$noteTicketKey]['name'] = $noteTicketValue['NoteTicket']['ticket_name'];
                    }else{
                        if(empty($noteTicketValue['NoteTicket']['ticket_num_time']) && ($noteTicketValue['NoteTicket']['ticket_num_time']=='0')){
                            $noteTicketList['TicketList'][$noteTicketKey]['id'] = $noteTicketValue['NoteTicket']['id'];
                            $noteTicketList['TicketList'][$noteTicketKey]['name'] = $noteTicketValue['NoteTicket']['ticket_name'];
                        }
                    }    
                }
            }

            $jsonEncode = json_encode($noteTicketList);
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
       
        $log = $this->NoteTicket->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "get_payment_method";
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
            $customerAnalysisData=$this->CustomerHistory->find('all', array('conditions'=>array('CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id),'order' => array('CustomerHistory.date' => 'DESC')));

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
        $recordData['RecordData']['name'] = "get_service_data";
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
            
               
                $path_info = pathinfo($_FILES['note_image']['name']);

                // $ext  = strtolower(trim(substr($file, strrpos($file, ".") + 1, strlen($file))));
                $newName = md5(time()*rand()).'.'.$path_info['extension'];

                $thumbRules = array('size' => array(NOTE_THUMB_WIDTH, NOTE_THUMB_HEIGHT), 'type' => 'resizecrop');
                $thumb = $this->Upload->upload($_FILES['note_image'], WWW_ROOT . NOTE_IMG_THUMB_DIR, $newName, $thumbRules);
                /* medium */
                 $mediumRules = array('size' => array(NOTE_MEDIUM_WIDTH, NOTE_MEDIUM_HEIGHT), 'type' => 'resizecrop');
                $medium = $this->Upload->upload($_FILES['note_image'], WWW_ROOT . NOTE_IMG_MEDIUM_DIR, $newName, $mediumRules);

                $verticalRules = array('size' => array(NOTE_VERTICAL_WIDTH, NOTE_VERTICAL_HEIGHT), 'type' => 'resizecrop');
                $vertical = $this->Upload->upload($_FILES['note_image'], WWW_ROOT . NOTE_IMG_VERTICAL_DIR, $newName, $verticalRules);
                
                $res3 = $this->Upload->upload($_FILES['note_image'], WWW_ROOT . NOTE_IMG_ORIGINAL_DIR, $newName, '', array('png', 'jpg', 'jpeg', 'gif'));


                /*$path_info = pathinfo($_FILES['note_image']['name']);

                $_FILES['note_image']['name'] = $path_info['filename']."_".time().".".$path_info['extension'];
                $res3 = $this->Upload->upload($_FILES['note_image'], WWW_ROOT . CUSTOMER_NOTE_IMAGE . DS ."original". DS, '', '', array('png', 'jpg', 'jpeg', 'gif'));*/
                
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
        $this->loadModel('NoteTicket');
        $this->loadModel('NoteService');
        $this->loadModel('NoteRemainingTicket');
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
        $noteService['NoteService']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '0';
        $ticket_id = $noteService['NoteService']['ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '0';
        $noteService['NoteService']['employee_name'] = isset($decoded['employee_name']) ? $decoded['employee_name'] : '';
        $service_price = $noteService['NoteService']['service_price'] = isset($decoded['service_price']) ? $decoded['service_price'] : '';
        $noteService['NoteService']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $noteService['NoteService']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $noteService['NoteService']['service_name'] = isset($decoded['service_name']) ? $decoded['service_name'] : '';
        $noteService['NoteService']['status'] = Configure::read('App.Status.active');

        if($this->NoteService->saveAll($noteService)){

            if(!empty($ticket_id)){
                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 
                if(($noteTicketData['NoteTicket']['ticket_num_time'] !=0) && ($noteTicketData['NoteTicket']['ticket_num_time'] > 0)){
                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];
                    $ticket_num_time = ($noteTicketData['NoteTicket']['ticket_num_time'] - 1);
                    $this->NoteTicket->saveField('ticket_num_time' , $ticket_num_time );
                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;
                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = $ticket_num_time;
                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);
                }else{
                    $service_price = $this->priceChangeInt($service_price); 
                    $ticketAmount = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 
                    if($ticketAmount >= $service_price ){
                        $ticketAmount = ($ticketAmount - $service_price) ;
                        $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];
                        $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );
                    }
                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;
                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';
                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);

                }
            }

            $note_service_id = $this->NoteService->id;
            $customerData['Customer']['id'] = $customer_id;

            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
            $this->Customer->saveAll($customerData);

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
        $this->loadModel('NoteTicket');
        $this->loadModel('NoteRemainingTicket');
        
        $id = $noteService['NoteService']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        $noteService['NoteService']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '';
        $ticket_id = $noteService['NoteService']['ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '0';
        $noteService['NoteService']['service_name'] = isset($decoded['service_name']) ? $decoded['service_name'] : '';
       
        $noteService['NoteService']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $noteService['NoteService']['employee_name'] = isset($decoded['employee_name']) ? $decoded['employee_name'] : '';
        $service_price = $noteService['NoteService']['service_price'] = isset($decoded['service_price']) ? $decoded['service_price'] : '';
        $noteService['NoteService']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $noteService['NoteService']['status'] = Configure::read('App.Status.active');

        $noteServiceData=$this->NoteService->find('first', array('conditions'=>array('NoteService.id'=>$id)));

        


        if($this->NoteService->saveAll($noteService)){
            $note_service_id = $this->NoteService->id;

            if(!empty($ticket_id)){
                
                $noteOldTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$noteServiceData['NoteService']['ticket_id'])));

                if(isset($noteOldTicketData['NoteTicket']['service_id']) && ($noteOldTicketData['NoteTicket']['service_id'] !=0)){
                    $this->NoteTicket->id = $noteOldTicketData['NoteTicket']['id'];
                    $ticket_num_time = ($noteOldTicketData['NoteTicket']['ticket_num_time'] + 1);
                    $this->NoteTicket->saveField('ticket_num_time' , $ticket_num_time );
                }else{
                    $ticket_amount = $this->priceChangeInt($noteOldTicketData['NoteTicket']['ticket_amount']); 
                    $service_price = $this->priceChangeInt($noteServiceData['NoteService']['service_price']); 
                    $ticket_amount = ($ticket_amount + $service_price);
                    $this->NoteTicket->id = $noteOldTicketData['NoteTicket']['id'];
                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticket_amount).'円' );
                }

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 
                if(isset($noteOldTicketData['NoteTicket']['service_id']) && ($noteOldTicketData['NoteTicket']['service_id'] !=0)){
                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];
                    $ticket_num_time = ($noteTicketData['NoteTicket']['ticket_num_time'] - 1);
                    $this->NoteTicket->saveField('ticket_num_time' , $ticket_num_time );
                    $noteRemainingTicketData=$this->NoteRemainingTicket->find('first', array('conditions'=>array('NoteRemainingTicket.note_ticket_id'=>$ticket_id,'NoteRemainingTicket.customer_history_id'=>$noteServiceData['NoteService']['customer_history_id'])));
                    if(isset($noteRemainingTicketData['NoteRemainingTicket']['id']))
                        $noteRemainingTicket['NoteRemainingTicket']['id'] = $noteRemainingTicketData['NoteRemainingTicket']['id'];
                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $noteServiceData['NoteService']['customer_history_id'];
                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = $ticket_num_time;
                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);

                }else{
                    $service_price = $this->priceChangeInt($service_price); 
                    $ticketAmount = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 
                    if($ticketAmount >= $service_price ){
                        $ticketAmount = ($ticketAmount - $service_price) ;
                        $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];
                        $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );
                    }
                    $noteRemainingTicketData=$this->NoteRemainingTicket->find('first', array('conditions'=>array('NoteRemainingTicket.note_ticket_id'=>$ticket_id,'NoteRemainingTicket.customer_history_id'=>$noteServiceData['NoteService']['customer_history_id'])));
                    if(isset($noteRemainingTicketData['NoteRemainingTicket']['id']))
                        $noteRemainingTicket['NoteRemainingTicket']['id'] = $noteRemainingTicketData['NoteRemainingTicket']['id'];
                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $noteServiceData['NoteService']['customer_history_id'];
                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';
                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);
                }
            }

            $noteServiceData=$this->NoteService->find('first', array('conditions'=>array('NoteService.id'=>$note_service_id)));
            if(isset($noteServiceData['NoteService']['customer_id'])){
                $customerData['Customer']['id'] = $noteServiceData['NoteService']['customer_id'];

                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
            }    

            $responseArr = array('note_service_id' => $note_service_id, 'msg'=>'編集が完了しました。', 'msg1'=>'edit note service successfully.', 'status' => 'success' );
           
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
        $this->loadModel('NoteTicket');
        $id = isset($decoded['id']) ? $decoded['id'] : '';


        if(!empty($id)){
            $noteServiceData=$this->NoteService->find('first', array('conditions'=>array('NoteService.id'=>$id)));
            if($this->NoteService->delete($id, true)){

               $noteOldTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$noteServiceData['NoteService']['ticket_id'])));

                if(isset($noteOldTicketData['NoteTicket']['service_id']) && ($noteOldTicketData['NoteTicket']['service_id'] !=0)){
                    $this->NoteTicket->id = $noteOldTicketData['NoteTicket']['id'];
                    $ticket_num_time = ($noteOldTicketData['NoteTicket']['ticket_num_time'] + 1);
                    $this->NoteTicket->saveField('ticket_num_time' , $ticket_num_time );
                }else{
                    $ticket_amount = $this->priceChangeInt($noteOldTicketData['NoteTicket']['ticket_amount']); 
                    $service_price = $this->priceChangeInt($noteServiceData['NoteService']['service_price']); 
                    $ticket_amount = ($ticket_amount + $service_price);
                    $this->NoteTicket->id = $noteOldTicketData['NoteTicket']['id'];
                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticket_amount).'円' );
                }

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
        $this->loadModel('NoteTicket');
        $this->loadModel('CustomerHistory');
        $this->loadModel('NoteRemainingTicket');
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
        $ticket_id = $noteProduct['NoteProduct']['ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '0';
        $noteProduct['NoteProduct']['customer_history_id'] = $customer_history_id;
        $noteProduct['NoteProduct']['product_id'] = isset($decoded['product_id']) ? $decoded['product_id'] : '0';
        $noteProduct['NoteProduct']['product_name'] = isset($decoded['product_name']) ? $decoded['product_name'] : '';
        $noteProduct['NoteProduct']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $noteProduct['NoteProduct']['employee_name'] = isset($decoded['employee_name']) ? $decoded['employee_name'] : '';
        $sale_price = $noteProduct['NoteProduct']['sale_price'] = isset($decoded['sale_price']) ? $decoded['sale_price'] : '';
        $product_quantity = $noteProduct['NoteProduct']['product_quantity'] = isset($decoded['product_stock']) ? $decoded['product_stock'] : '';
        $noteProduct['NoteProduct']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $noteProduct['NoteProduct']['status'] = Configure::read('App.Status.active');

        if($this->NoteProduct->saveAll($noteProduct)){
            $note_product_id = $this->NoteProduct->id;
            if(!empty($ticket_id)){
                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 
                $sale_price = $this->priceChangeInt($sale_price); 
                $ticket_amount = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 
                if($ticket_amount >= $sale_price ){
                    $ticketAmount = ($ticket_amount - $sale_price) ;
                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];
                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );
                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;
                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';
                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);
                }else{
                    $this->NoteProduct->id = $note_product_id;
                    $this->NoteProduct->saveField('ticket_id' , '0' );
                }
            }
            if(!empty($customer_id)){
                $customerData['Customer']['id'] = $customer_id;

                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
            }

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
        $this->loadModel('NoteTicket');
        $this->loadModel('NoteRemainingTicket');
        $this->loadModel('CustomerHistory');
        $id = $noteProduct['NoteProduct']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        $noteProductData=$this->NoteProduct->find('first', array('conditions'=>array('NoteProduct.id'=>$id)));
           
        $noteProduct['NoteProduct']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $noteProduct['NoteProduct']['product_id'] = isset($decoded['product_id']) ? $decoded['product_id'] : '0';
        $ticket_id = $noteProduct['NoteProduct']['ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '0';
        $noteProduct['NoteProduct']['product_name'] = isset($decoded['product_name']) ? $decoded['product_name'] : '';
        $noteProduct['NoteProduct']['employee_id'] = isset($decoded['employee_id']) ? $decoded['employee_id'] : '';
        $noteProduct['NoteProduct']['employee_name'] = isset($decoded['employee_name']) ? $decoded['employee_name'] : '';
        $sale_price = $noteProduct['NoteProduct']['sale_price'] = isset($decoded['sale_price']) ? $decoded['sale_price'] : '';
        $product_quantity = $noteProduct['NoteProduct']['product_quantity'] = isset($decoded['product_stock']) ? $decoded['product_stock'] : '';
        $noteProduct['NoteProduct']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $noteProduct['NoteProduct']['status'] = Configure::read('App.Status.active');
        
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
       
       // print_r($noteProduct);die;
        if($this->NoteProduct->saveAll($noteProduct)){
            $note_product_id = $this->NoteProduct->id;

            $noteNoteProduct=$this->NoteProduct->find('first', array('conditions'=>array('NoteProduct.id'=>$note_product_id)));
            if(isset($noteNoteProduct['NoteProduct']['customer_id'])){
                $customerData['Customer']['id'] = $noteNoteProduct['NoteProduct']['customer_id'];

                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                $this->Customer->saveAll($customerData);
            } 

            if(isset($getNoteProduct['Product']['product_stock'])){
                if($product_quantity <= $getNoteProduct['Product']['product_stock']){
                    $total_stock = ($getNoteProduct['Product']['product_stock'] - $product_quantity);
                    $this->Product->id = $getNoteProduct['Product']['id'];
                    $this->Product->saveField('product_stock' , $total_stock);
                }
            } 
            if(!empty($ticket_id)){
                
                $noteOldTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$noteProductData['NoteProduct']['ticket_id'])));
       
                $old_ticket_amount = $this->priceChangeInt($noteOldTicketData['NoteTicket']['ticket_amount']); 
                $old_sale_price = $this->priceChangeInt($noteProductData['NoteProduct']['sale_price']); 
                $old_ticket_amount = ($old_ticket_amount + $old_sale_price);
                $this->NoteTicket->id = $noteOldTicketData['NoteTicket']['id'];
                $this->NoteTicket->saveField('ticket_amount' , number_format($old_ticket_amount).'円' );
                

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 
                $sale_price = $this->priceChangeInt($sale_price); 
                $ticket_amount = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 
                if($ticket_amount >= $sale_price ){
                    $ticketAmount = ($ticket_amount - $sale_price) ;
                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];
                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );
                    $noteRemainingTicketData=$this->NoteRemainingTicket->find('first', array('conditions'=>array('NoteRemainingTicket.note_ticket_id'=>$ticket_id,'NoteRemainingTicket.customer_history_id'=>$getNoteProduct['NoteProduct']['customer_history_id'])));
                    if(isset($noteRemainingTicketData['NoteRemainingTicket']['id']))
                        $noteRemainingTicket['NoteRemainingTicket']['id'] = $noteRemainingTicketData['NoteRemainingTicket']['id'];
                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $getNoteProduct['NoteProduct']['customer_history_id'];
                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = isset($decoded['ticket_id']) ? $decoded['ticket_id'] : '';
                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';
                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);
                }else{
                    $this->NoteProduct->id = $note_product_id;
                    $this->NoteProduct->saveField('ticket_id' , '0' );
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
    function delete_note_product($