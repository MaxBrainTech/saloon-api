<?php
/*********************************************************************************
1. * Copyright 2017, All rights reserved, For internal use only
*
* FILE:    WebServicesController.php
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
class WebServicesv1Controller extends AppController{
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
                                                              ),'fields'=>array('User.id','User.email','User.company_name','User.is_admin')
                                                        ));
                
            if(isset($data['User']['id']) && !empty($data['User']['id'])){
                $this->User->id = $data['User']['id'];
                $this->loadModel('Salon');
                $SalonData = $this->Salon->find('first',array('conditions'=>  array('Salon.user_id'=>$data['User']['id']),'fields'=>array('Salon.user_id','Salon.id') ));
                if(isset($SalonData['Salon']['user_id']) && !empty($SalonData['Salon']['user_id'])){
                    $responseArr = array('user_id' => $data['User']['id'], 'salon_id' => $SalonData['Salon']['id'], 'msg' => 'Login Sccuessfully.', 'is_admin' => strval($data['User']['is_admin']), 'is_form' => '3', 'status' => 'success' );
                }elseif(isset($data['User']['company_name']) && !empty($data['User']['company_name'])){
                    $responseArr = array('user_id' => $data['User']['id'], 'msg' => 'Login Sccuessfully.', 'is_admin' => strval($data['User']['is_admin']), 'is_form' => '2', 'status' => 'success' );
                }else{
                    $responseArr = array('user_id' => $data['User']['id'], 'msg' => 'Login Sccuessfully.', 'is_admin' => strval($data['User']['is_admin']), 'is_form' => '1', 'status' => 'success' );
                }
                $jsonEncode = json_encode($responseArr);
             
            }else{
                $data = $this->User->find('first',array('conditions'=>  array('User.email'=>$email,'User.password'=>Security::hash($password, null, true),),'fields'=>array('User.id','User.email')));
                if(isset($data['User']['id']) && !empty($data['User']['id'])){
                    $responseArr = array('msg' => 'Please active account from your email.', 'status' => 'error' );
                    $jsonEncode = json_encode($responseArr);
                }else{

                    $responseArr = array('msg' => 'Email are not exist.', 'status' => 'error' );
                    $jsonEncode = json_encode($responseArr);
                }
            }
        }else{

            $responseArr = array('msg' => 'Please Enter email and password.', 'status' => 'error' );
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
            $responseArr = array('msg' => 'This email is already exist.', 'status' => 'error' );
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
                                                    'order' => array('Customer.id' => 'DESC')
                                                    ));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'please enter valid user id.'));
            }else{

                if(!empty($data)){
                    
                    foreach ($data as $key => $value) {

                        $customerData['Customer'][$i] = $value['Customer'];
                        $customerData['Customer'][$i]['salon_name'] = $value['Salon']['salon_name'];
                        $customerData['Customer'][$i]['service_name'] = $this->get_service_name($value['Customer']['service_id']);
                        $i++;
                    }

                }else{
                    $customerData[$i]['Customer']['msg'] = 'No Record Found.';
                    $customerData[$i]['Customer']['status'] = 'error';
                }
                $jsonEncode = json_encode($customerData);
            }
        }else{
            $customerData[$i]['Customer']['msg'] = 'Please add user id.';
            $customerData[$i]['Customer']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }
        $log = $this->Customer->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "customer_list";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
    }

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
                $jsonEncod = json_encode(array('status'=>'success', 'msg'=> $tel.' Customer coming on first time'));
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
            $customerData[$i]['Customer']['msg'] = 'Please add user id.';
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
        if(!empty($customer_id)){
            $data = $this->Customer->find('first',array('conditions'=>  array( 'Customer.id'=>$customer_id )));
            if(!$data){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'please enter valid solan id.'));
            }else{
                $service_id = $data['Customer']['service_id'];
               
                if($service_id =='2'){
                    $this->loadModel("Esthe");
                    $service = $this->Esthe->find('first',array('conditions'=>  array('Esthe.customer_id'=>$customer_id)));
                    if(!empty($service['Esthe']['how_to_maintain'])){
                      $service['Esthe']['how_to_maintain'] = json_decode($service['Esthe']['how_to_maintain']);
                    }
                    $allData['Service'] =  $service['Esthe'];
                }elseif($service_id == '3'){
                    $this->loadModel("Eyelush");
                    $service = $this->Eyelush->find('first',array('conditions'=>  array('Eyelush.customer_id'=>$customer_id)));
                    $allData['Service'] =  $service['Eyelush'];
                }elseif($service_id == '4' ){
                    $this->loadModel("Body");
                    $service = $this->Body->find('first',array('conditions'=>  array('Body.customer_id'=>$customer_id)));
                    $allData['Service'] =  $service['Body'];
                }elseif ($service_id == '5') {
                    $this->loadModel("HairRemoval");
                    $service = $this->HairRemoval->find('first',array('conditions'=>  array('HairRemoval.customer_id'=>$customer_id)));
                    $allData['Service'] =  $service['HairRemoval'];
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
            $customerData[$i]['Customer']['msg'] = 'Please add customer id.';
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
                $this->Customer->id = $customerDetail['Customer']['id'];
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
                $service_name = $data['Service']['name'];
            }else{
                $service_name = 'No Service';
            }
            return  $service_name;
        }else{
            return 'No Service';
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
        
        $this->loadModel('CustomerHistory');
        if(isset($decoded['id']) && !empty($decoded['id'])){
            $customerAnalysisExist=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$decoded['id'])));
            if(isset($customerAnalysisExist['CustomerHistory']['id']) && !empty($customerAnalysisExist['CustomerHistory']['id'])){
                $customerHistory['CustomerHistory']['id'] = $customerAnalysisExist['CustomerHistory']['id'];
            } 
        }
        $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        $service_price = isset($decoded['service_price']) ? $decoded['service_price'] : '';
        $customerHistory['CustomerHistory']['service_price'] = json_encode($service_price);
        $customerHistory['CustomerHistory']['date'] = isset($decoded['date']) ? $decoded['date'] : '';
        $customerHistory['CustomerHistory']['note_text'] = isset($decoded['note_text']) ? $decoded['note_text'] : '';
        $customerHistory['CustomerHistory']['note_image'] = isset($decoded['note_image']) ? $decoded['note_image'] : '';

        
        if($this->CustomerHistory->saveAll($customerHistory)){
            $customer_analysis_id = $this->CustomerHistory->id;
            $responseArr = array('customer_analysis_id' => $customer_analysis_id, 'msg'=>'顧客情報が正常に追加されました。', 'status' => 'success' );
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
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
        $responseArr = array();
        
        $this->loadModel('CustomerHistory');
        if(isset($decoded['id']) && !empty($decoded['id'])){
            $customerAnalysisExist=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$decoded['id'])));
            if(isset($customerAnalysisExist['CustomerHistory']['id']) && !empty($customerAnalysisExist['CustomerHistory']['id'])){
                $customerHistory['CustomerHistory']['id'] = $customerAnalysisExist['CustomerHistory']['id'];
            } 
        }
        if(isset($decoded['user_id']) && !empty($decoded['user_id']))
        $customerHistory['CustomerHistory']['user_id'] = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        if(isset($decoded['customer_id']) && !empty($decoded['customer_id']))
        $customerHistory['CustomerHistory']['customer_id'] = isset($decoded['customer_id']) ? $decoded['customer_id'] : '';
        if(isset($decoded['service_price']) && !empty($decoded['service_price'])){
            $service_price = isset($decoded['service_price']) ? $decoded['service_price'] : '';
            $customerHistory['CustomerHistory']['service_price'] = json_encode($service_price);
        }

        if(isset($decoded['date']) && !empty($decoded['date']))
        $customerHistory['CustomerHistory']['date'] = isset($decoded['date']) ? $decoded['date'] : '';
      
        
        if($this->CustomerHistory->saveAll($customerHistory)){
            $customer_analysis_id = $this->CustomerHistory->id;
            $responseArr = array('customer_analysis_id' => $customer_analysis_id, 'msg'=>'日にちを変更しました。', 'status' => 'success' );
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
                $responseArr = array('status' => 'success', 'msg' => 'Customer date deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Customer date deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Customer date id undefind.'  );
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
/*
                    $profileData = array();
                    $this->loadModel('User');
                    $data = $this->User->find('first', array('fields'=>array('User.id', 'User.profile_image'), 'conditions'=>array('User.id'=>$_POST['user_id'])));
                    @unlink(WWW_ROOT . PROFILE_PICS . DS . "original" . DS . $data['User']['profile_image']);
                    @unlink(WWW_ROOT . PROFILE_PICS . DS . "thumb" . DS . $data['User']['profile_image']);
                    @unlink(WWW_ROOT . PROFILE_PICS . DS . "large" . DS . $data['User']['profile_image']);

                    $profileData['User']['id'] = $_POST['user_id'];
                    $profileData['User']['profile_image'] = $userImage;
                    $this->User->save($profileData);*/
                    //echo WWW_ROOT . MOBILE_VDIR . DS.$data['User']['profile_image'];die;
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
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        $responseArr  = array();
        if(!empty($id)){

            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$id)));
            //print_r($customerAnalysisData);die;
            $responseArr['id'] = $customerAnalysisData['CustomerHistory']['id'];
            $responseArr['note_text'] = $customerAnalysisData['CustomerHistory']['note_text'];
            $responseArr['note_image'] = $customerAnalysisData['CustomerHistory']['note_image'];
            $responseArr['service_price'] = json_decode($customerAnalysisData['CustomerHistory']['service_price']);
            if(isset($customerAnalysisData['CustomerHistory']['note_image']) && !empty($customerAnalysisData['CustomerHistory']['note_image'])){
                $responseArr['note_image'] = SITE_URL."/uploads/note_image/original/".$customerAnalysisData['CustomerHistory']['note_image'];
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






 
    
}
