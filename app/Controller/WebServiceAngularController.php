<?php

/*********************************************************************************
1. * Copyright 2017, All rights reserved, For internal use only
*
* FILE:    WebServiceAngularController.php
* PROJECT: vocalist.com.sg
* MODULE:  Native app websservices(Android and iPhone)
* AUTHOR:  Mahendra Tripathi
* DATE:    19/06/2018

* Notes:
*

* REVISION HISTORY
* Date: By: Description:
* /var/www/html/
*****************************************************************************/



class WebServiceAngularController extends AppController{
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

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');
 
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

        // $shell = new ShellDispatcher();
        // $output = $shell->run(['cake', 'input']);
 
        // if (0 === $output) {
        //     $this->Flash->success('Success from shell command.');
        // } else {
        //     $this->Flash->error('Failure from shell command.');
        // }
       
        // now();die;
        // echo$date = date('m/d/Y h:i:s a', time());die;
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_POST);
        }    
        /*      
          $data = '{"email":"mahen.zed123@gmail.com", "password" : "123456"}';
         */
        //echo $data;
        $decoded = json_decode($data, true);
        $responseArr =array();
        // $email = $decoded['email'];
        // $password = $decoded['password'];
        $email = isset($decoded['email']) ? $decoded['email'] : '';
        $password = isset($decoded['password']) ? $decoded['password'] : '';
        $device_token = isset($decoded['device_token']) ? $decoded['device_token'] : '';
       
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
                 
                $this->User->id = $data['User']['id'];
                $this->User->saveField('device_token' , $device_token );
                // $this->User->saveField('device_type' , $device_type );
                $subscription_current_period_end = isset($data['User']['stripe_payment_status']) ? date('Y-m-d', strtotime($data['User']['stripe_payment_status'])) : '';
                $current_date = date('Y-m-d');
                if($subscription_current_period_end <= $current_date){
                    $responseArr = array('user_id' => $data['User']['id'], 'stripe_payment_status' => $data['User']['stripe_payment_status'], 'msg1' => 'Login Sccuessfully.', 'msg' => 'ログインが成功しました。', 'employee_id'=>$employee_id, 'employee_name'=>$employee_name,  'employee_image'=>$employee_image, 'employee_pin_number'=>$employee_pin_number,  'customer_pin_number'=>$customer_pin_number, 'is_admin' => strval($data['User']['is_admin']), 'status' => 'success' );

                }else{
                     $responseArr = array('user_id' => $data['User']['id'], 'stripe_payment_status' => '0', 'msg1' => 'Login Sccuessfully.', 'msg' => 'ログインが成功しました。', 'employee_id'=>$employee_id,  'employee_name'=>$employee_name,  'employee_image'=>$employee_image, 'employee_pin_number'=>$employee_pin_number,  'customer_pin_number'=>$customer_pin_number, 'is_admin' => strval($data['User']['is_admin']), 'is_form' => '2', 'status' => 'success' );
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
     * NAME: check_sales_user_code
     * Description: Check Sales User Code .
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
     * Author: MANMOHAN SAHU
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/

    function check_sales_user_code($test_data = null){

        /* Load Model Start */
        $this->loadModel("SaleUser");
        /* Load Model End */

        $data = file_get_contents('php://input');

        if(empty($data)){
           $data = json_encode($_POST);
       }

       $decoded = json_decode($data, true);

       /** Sales Code Exist Ckeck Start**/
       $unique_sales_code = (isset($decoded['unique_sales_code']) && !empty($decoded['unique_sales_code'])) ? $decoded['unique_sales_code'] : '';
       $saleCodeExist = $this->SaleUser->find('first', array('conditions'=>array('SaleUser.unique_sales_code'=>$unique_sales_code)));
       
       if($saleCodeExist){
           $responseArr = array('unique_sales_code' => $unique_sales_code, 'status' => 'success', 'msg' => 'サービスの色を正常に追加。', 'msg1' => 'This sales unique code found successfully.');
           $jsonEncode = json_encode($responseArr);
           return $jsonEncode;
       }else{
   			$responseArr = array('unique_sales_code' => $unique_sales_code, 'status' => 'error', 'msg' => 'このメールは既に存在します。', 'msg1' => 'This sales unique code not exist.');
           	$jsonEncode = json_encode($responseArr);
           	return $jsonEncode;
       }
       /** Sales Code Exist Ckeck End**/

       echo  $jsonEncode;exit();
    }



    /****************************************************************************************************************************************
     * NAME: add_sales_user
     * Description: Add Sales User New .
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
     * Author: MANMOHAN SAHU
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/

    function add_sales_user($test_data = null){

        /* Load Model Start */
        $this->loadModel("SaleUser");
        /* Load Model End */

        $data = file_get_contents('php://input');
         

        if(empty($data)){
           $data = json_encode($_POST);
        }

       /* Request log Start*/
       // $recordData['RecordData']['name'] = "add_sales_user";
       // $recordData['RecordData']['query'] = json_encode($data);
       // $this->RecordData->saveAll($recordData);
       /*Request log End*/

       $decoded = json_decode($data, true);
        // pr($decoded);die;
       /** Sales Email Exist Ckeck User Table and Sale_User both Start**/

       $email = (isset($decoded['email']) && !empty($decoded['email'])) ? $decoded['email'] : '';
       $saleEmailExistInUserTable = $this->User->find('first', array('conditions'=>array('User.email'=>$email)));
       $saleEmailExistInSaleUserTable = $this->SaleUser->find('first', array('conditions'=>array('SaleUser.email'=>$email)));
       
       if($saleEmailExistInUserTable){
           $responseArr = array('email' => $email, 'status' => 'error', 'msg' => 'このメールは既に存在します。', 'msg1' => 'This email is already exist.');
           $jsonEncode = json_encode($responseArr);
           return $jsonEncode;
       }elseif($saleEmailExistInSaleUserTable){
       		$responseArr = array('email' => $email, 'status' => 'error', 'msg' => 'このメールは既に存在します。', 'msg1' => 'This email is already exist.');
	       	$jsonEncode = json_encode($responseArr);
	       	return $jsonEncode;
       }

        
        // echo $imageName;die;

       /** Sales Email Exist Ckeck User Table and Sale_User both End**/

       /** driving licence image upload start **/
        $imageDataFront = $decoded['dl_front_img'];
        $imageDataBack = $decoded['dl_back_img'];
        
        if(isset($imageDataFront) && isset($imageDataBack) && !empty($imageDataFront) && !empty($imageDataBack)){
          list($type, $imageDataFront) = explode(';', $imageDataFront);
          list(, $imageDataFront)      = explode(',', $imageDataFront);

          $imageDataFront = base64_decode($imageDataFront);
          $imageNameFront = md5(time()*rand()).'.jpg';
          file_put_contents(WWW_ROOT ."uploads/dl/front". DS.$imageNameFront, $imageDataFront);


          list($type, $imageDataBack) = explode(';', $imageDataBack);
          list(, $imageDataBack)      = explode(',', $imageDataBack);

          $imageDataBack = base64_decode($imageDataBack);
          $imageNameBack = md5(time()*rand()).'.jpg';
          
          
          file_put_contents(WWW_ROOT ."uploads/dl/back". DS.$imageNameBack, $imageDataBack);
        }else{
          $responseArr = array('status' => 'error', 'msg' => 'このメールは既に存在します。', 'msg1' => 'Please choose driving licence(front and back side) image.');
          $jsonEncode = json_encode($responseArr);
          return $jsonEncode;
        }

        

        
        
        
       // if((isset($_FILES['dl_front_img']) && !empty($_FILES['dl_front_img'])) && (isset($_FILES['dl_back_img']) && !empty($_FILES['dl_back_img']))){
        
       //      $dl_front_path_info = pathinfo($_FILES['dl_front_img']['name']);
       //      $dl_back_path_info = pathinfo($_FILES['dl_back_img']['name']);

       //      if(in_array($dl_front_path_info['extension'], array('jpg', 'jpeg', 'png', 'gif')) && in_array($dl_back_path_info['extension'], array('jpg', 'jpeg', 'png', 'gif'))){

       //          $dl_front_newName = md5(time()*rand()).'.'.$dl_front_path_info['extension'];
       //          $dl_back_newName = md5(time()*rand()).'.'.$dl_back_path_info['extension'];

                
                
       //          $front_dl = $this->Upload->upload($_FILES['dl_front_img'], WWW_ROOT . DL_FRONT_DIR, $dl_front_newName, '', array('png', 'jpg', 'jpeg', 'gif'));
       //          $dl_front_img = $this->Upload->result;
       //           // Back DL image upload 
       //          $back_dl = $this->Upload->upload($_FILES['dl_back_img'], WWW_ROOT . DL_BACK_DIR, $dl_back_newName, '', array('png', 'jpg', 'jpeg', 'gif'));
       //          $dl_back_img = $this->Upload->result;
                
       //      }else{
       //          $responseArr = array('email'=>$email, 'master_sales_code'=>$decoded['master_sales_code'], 'dl_front_extension'=>$dl_front_path_info['extension'], 'dl_back_extension'=>$dl_back_path_info['extension'] ,'status' => 'error', 'msg'=>'顧客情報が正常に追加されました。', 'msg1'=>'DL(front & back image) type should be (jpg, jpeg, png, gif).');
       //          echo $jsonEncode = json_encode($responseArr);
       //          exit();
       //      }
       //  }else{
       //      $responseArr = array('email'=>$email, 'master_sales_code'=>$decoded['master_sales_code'], 'status' => 'error', 'msg'=>'Please choose driving licence image.');
       //      echo $jsonEncode = json_encode($responseArr);
       //  	exit();
       //  } 

        /** driving licence image upload end **/
        
		$sale_users['SaleUser']['name'] = (isset($decoded['name']) && !empty($decoded['name'])) ? $decoded['name'] : '';
		$sale_users['SaleUser']['kana'] = (isset($decoded['kana']) && !empty($decoded['kana'])) ? $decoded['kana'] : '';
		$sale_users['SaleUser']['kanji'] = (isset($decoded['kanji']) && !empty($decoded['kanji'])) ? $decoded['kanji'] : '';
		$sale_users['SaleUser']['email'] = $email;
		$password = (isset($decoded['password']) && !empty($decoded['password'])) ? $decoded['password'] : '';
		$sale_users['SaleUser']['password'] = Security::hash($password, null, true);
		$sale_users['SaleUser']['tel'] = (isset($decoded['tel']) && !empty($decoded['tel'])) ? $decoded['tel'] : '';
		$sale_users['SaleUser']['dob'] = (isset($decoded['dob']) && !empty($decoded['dob'])) ? str_replace(array('年', '月'), '-', $decoded['dob']) : '';
		$sale_users['SaleUser']['zip_code'] = (isset($decoded['zip_code']) && !empty($decoded['zip_code'])) ? $decoded['zip_code'] : '';
		$sale_users['SaleUser']['address'] = (isset($decoded['address']) && !empty($decoded['address'])) ? $decoded['address'] : '';
		$sale_users['SaleUser']['unique_sales_code'] = $this->JTSRendomCustomerCodeString();
		$sale_users['SaleUser']['master_sales_code'] = (isset($decoded['master_sales_code']) && !empty($decoded['master_sales_code'])) ? $decoded['master_sales_code'] : '';
		$sale_users['SaleUser']['dl_front_img'] = (isset($imageNameFront) && !empty($imageNameFront)) ? $imageNameFront : '';
		$sale_users['SaleUser']['dl_back_img'] = (isset($imageNameBack) && !empty($imageNameBack)) ? $imageNameBack : '';
		$sale_users['SaleUser']['bank_name'] = (isset($decoded['bank_name']) && !empty($decoded['bank_name'])) ? $decoded['bank_name'] : '';
		$sale_users['SaleUser']['bank_name_kana'] = (isset($decoded['bank_name_kana']) && !empty($decoded['bank_name_kana'])) ? $decoded['bank_name_kana'] : '';
		$sale_users['SaleUser']['bank_number'] = (isset($decoded['bank_number']) && !empty($decoded['bank_number'])) ? $decoded['bank_number'] : '';
		$sale_users['SaleUser']['branch'] = (isset($decoded['branch']) && !empty($decoded['branch'])) ? $decoded['branch'] : '';
		$sale_users['SaleUser']['branch_kana'] = (isset($decoded['branch_kana']) && !empty($decoded['branch_kana'])) ? $decoded['branch_kana'] : '';
		$sale_users['SaleUser']['branch_code'] = (isset($decoded['branch_code']) && !empty($decoded['branch_code'])) ? $decoded['branch_code'] : '';
		$sale_users['SaleUser']['what_kind_of_bank'] = (isset($decoded['what_kind_of_bank']) && !empty($decoded['what_kind_of_bank'])) ? $decoded['what_kind_of_bank'] : '';
		$sale_users['SaleUser']['account_number'] = (isset($decoded['account_number']) && !empty($decoded['account_number'])) ? $decoded['account_number'] : '';
		$sale_users['SaleUser']['account_holder_name_kana'] = (isset($decoded['account_holder_name_kana']) && !empty($decoded['account_holder_name_kana'])) ? $decoded['account_holder_name_kana'] : '';
		$sale_users['SaleUser']['account_holder_name'] = (isset($decoded['account_holder_name']) && !empty($decoded['account_holder_name'])) ? $decoded['account_holder_name'] : '';
		$sale_users['SaleUser']['status'] = Configure::read('App.Status.active');
       // pr($sale_users['SaleUser']);die;
       if($this->SaleUser->saveAll($sale_users)){

        /****************** EMAIL NOTIFICATION MESSAGE START********************/
            $sales_user_id = $this->SaleUser->id;
            // $to      = $this->User->email;
            $from    = Configure::read('App.AdminMail');
            $mail_message = '';
            
            $this->loadModel('Template');
            $registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'sales_user_registration')));
            $email_subject = $registrationMail['Template']['subject'];
            $subject = __('「JTSボード」 ' . $email_subject . '', true);

            $mail_message = str_replace(array('{NAME}', '{EMAIL}','{PASSWORD}','{SALES_PERSON_CODE}','{MASTER_SALES_PERSON_CODE}', '{SITE}'), array($sale_users['SaleUser']['kana'], $email, $password,$sale_users['SaleUser']['unique_sales_code'],$sale_users['SaleUser']['master_sales_code'], Configure::read('App.SITENAME')), $registrationMail['Template']['content']);
            $template = 'default';
            $this->set('message', $mail_message);

            $from = 'JTSBoard <mailgun@mg.jtsboard.com>';

            $this->sendMail($email, $subject, $mail_message, $from, $template);
          
        /****************** EMAIL NOTIFICATION MESSAGE END********************/

        /****************** ADMIN EMAIL NOTIFICATION MESSAGE START********************/

            $email = "manny.sahu@gmail.com";
            $subject = "Sales User Registration";
            $mail_message = "Sales User Kana Name : ". $sale_users['SaleUser']['kana'];
            $template = 'default';

            $from = 'JTSBoard <mailgun@mg.jtsboard.com>';

            $this->sendMail($email, $subject, $mail_message, $from, $template);

        /****************** ADMIN EMAIL NOTIFICATION MESSAGE END********************/


        $responseArr = array('user_id' => $sales_user_id, 'unique_sales_code'=>$sale_users['SaleUser']['unique_sales_code'], 'master_sales_code'=>$sale_users['SaleUser']['master_sales_code'], 'status' => 'success', 'msg' => 'サービスの色を正常に追加。', 'msg1' => 'Sales User Registartion successfully.' );
        $jsonEncode = json_encode($responseArr);
       }else{
			$responseArr = array('status' => 'error' );
			$jsonEncode = json_encode($responseArr);
       }

       echo  $jsonEncode;exit();
    }

   	/** Sales User Code Generate function Start **/

    function JTSRendomCustomerCodeString(){
       $randstring = 'JTSB';
       $randstring .= mt_rand(100000, 999999);
       $this->loadModel("SaleUser");
       $customerCodeExist = $this->SaleUser->find('first', array('conditions'=>array('SaleUser.unique_sales_code'=>$randstring)));
       if(isset($customerCodeExist['SaleUser']['id']) && !empty($customerCodeExist['SaleUser']['id'])){
           $this->JTSRendomCustomerCodeString();
       }
       return $randstring;
   }

   /** Sales User Code Generate function End **/


   /****************************************************************************************************************************************
     * NAME: contact_form
     * Description: Contact Form .
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
     * Author: MANMOHAN SAHU
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/
     //send mail 
     public function sendMail1($to, $subject, $message, $from, $template_id = null ){
        // echo $message;die;

        $html  = $message;
        $domain = "mg.jtsboard.com";
        $config = array();
        $config['api_key'] = "457b1d1a0372e162d6336f675d1a69c6-de7062c6-a83103f2";
        $config['api_url'] = "https://api.mailgun.net/v3/" . $domain . "/messages";
        $message = array();
        $message['from'] = $from;
        $message['to'] = $to;
        $message['subject'] = $subject;
        $message['html'] = $html;
        // $message = json_encode($message);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $config['api_url']);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "api:{$config['api_key']}");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
        $result = curl_exec($curl);
        // pr($result);die;
        curl_close($curl);
        return $result;

       
    }


    function contact_form($test_data = null){

        /* Load Model Start */
        $this->loadModel("Contact");
        /* Load Model End */

        $data = file_get_contents('php://input');

        if(empty($data)){
           $data = json_encode($_POST);
        }
        $decoded = json_decode($data, true);

        $userContact['Contact']['name'] = (isset($decoded['name']) && !empty($decoded['name'])) ? $decoded['name'] : '';
        $userContact['Contact']['email'] = (isset($decoded['email']) && !empty($decoded['email'])) ? $decoded['email'] : '';
        $userContact['Contact']['phone'] = (isset($decoded['phone']) && !empty($decoded['phone'])) ? $decoded['phone'] : '';
        $userContact['Contact']['subject'] = (isset($decoded['subject']) && !empty($decoded['subject'])) ? $decoded['subject'] : '';
        $userContact['Contact']['message'] = (isset($decoded['message']) && !empty($decoded['message'])) ? $decoded['message'] : '';
        if($this->Contact->saveAll($userContact)){

          
          //$this->sendMail($to, $subject, $mail_message, $from, $template);

          // mail sent for contact form
          //   $domain = "jts.jtsboard.com";
          //   $email = "bhagyashree.zed@gmail.com";
          //   $subject = "Contact form submission";
          //   $mail_message = "Your details have been submitted successfully";
          //   $template = 'default';
          //   $config['api_key'] = "457b1d1a0372e162d6336f675d1a69c6-de7062c6-a83103f2";
          // $config['api_url'] = "https://api.mailgun.net/v3/" . $domain . "/messages";
            
          //   $from = 'do-not-reply@jtsboard.com';

          //   $this->sendMail($email, $subject, $mail_message, $from, $template);
                // $to = 'bhagyashree.zed@gmail.com';
                // // $from = Configure::read('App.AdminMail');
                // // $from = "JTSボード <mailgun@mg.jtsboard.com>";
                // $from = "do-not-reply@jtsboard.com";
                
                // $template='default';
                // $this->set('message', $mail_message);
                // $template='default';
                // if($this->sendMail1($to, $subject, $mail_message, $from, $template))
                // {
                //   $responseArr = array('msg' => 'あなたのメールアドレスからアカウントを有効にしてください。', 'msg1' => 'Please check your mail account for reset password.', 'status' => 'success' );
                //   $jsonEncode = json_encode($responseArr);
                // }
          //$emailId = "info@zedinternational.net";
            $emailId = "bhagyashree.zed@gmail.com";
            $from    = Configure::read('App.AdminMail');
            $mail_message = '';
            
            $this->loadModel('Template');
            $registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'event_registration')));
            $email_subject = $registrationMail['Template']['subject'];
            $subject = __('JTSBoard ' . $email_subject . '', true);

            $mail_message = str_replace(array('{NAME}','{EMAIL}','{PHONE}','{SUBJECT}','{MESSAGE}','{SITE}'), array($userContact['Contact']['name'], $userContact['Contact']['email'],$userContact['Contact']['phone'],$userContact['Contact']['subject'],$userContact['Contact']['message'], Configure::read('App.SITENAME')), $registrationMail['Template']['content']);
            echo "testing purpose"; die;
            $template = 'default';
            $this->set('message', $mail_message);

            $from = 'JTSBoard <do-not-reply@jtsboard.com>';

            $this->sendMail($emailId, $subject, $mail_message, $from, $template);
          
            $responseArr = array('status' => 'success', 'msg' => 'サービスの色を正常に追加。', 'msg1' => 'Contact form submitteddddd successfully.' );


        }else{
          $responseArr = array('msg'=>'Contact form submit error.', 'status' => 'error');
          $jsonEncode = json_encode($responseArr);
        }
        echo  $jsonEncode;exit();
      }


            /****************************************************************************************************************************************
     * NAME: event_form
     * Description: Event Form .
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
     * Author: MANMOHAN SAHU
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *************************************************************************************************************************************/

    function event_form($test_data = null){

        /* Load Model Start */
        $this->loadModel("Event");
        /* Load Model End */

        $data = file_get_contents('php://input');

        if(empty($data)){
           $data = json_encode($_POST);
        }
        $decoded = json_decode($data, true);

        $email = (isset($decoded['email']) && !empty($decoded['email'])) ? $decoded['email'] : '';
        $salon_name = (isset($decoded['salon_name']) && !empty($decoded['salon_name'])) ? $decoded['salon_name'] : '';
        $phone = (isset($decoded['tel']) && !empty($decoded['tel'])) ? $decoded['tel'] : '';
        $participant_one = (isset($decoded['participant_one']) && !empty($decoded['participant_one'])) ? $decoded['participant_one'] : '';
        $participant_two = (isset($decoded['participant_two']) && !empty($decoded['participant_two'])) ? $decoded['participant_two'] : '';
        $participant_three = (isset($decoded['participant_three']) && !empty($decoded['participant_three'])) ? $decoded['participant_three'] : '';
        $address = (isset($decoded['address']) && !empty($decoded['address'])) ? $decoded['address'] : '';
        // pr($decoded);die;
        $userEvent['Event']['salon_name'] = $salon_name;
        $userEvent['Event']['participant_one'] = $participant_one;
        $userEvent['Event']['participant_two'] = $participant_two;
        $userEvent['Event']['participant_three'] = $participant_three;
        $userEvent['Event']['tel'] = $phone;
        $userEvent['Event']['email'] = $email;
        $userEvent['Event']['address'] = $address;
        if($this->Event->saveAll($userEvent)){

          /****************** EMAIL NOTIFICATION MESSAGE START********************/
            // $sales_user_id = $this->SaleUser->id;
            // $to      = $this->User->email;

            $participants = $participant_one." ".$participant_two." ".$participant_three;

            $emailId = "info@zedinternational.net";
            //$emailId = "bhagyashree.zed@gmail.com";
            $from    = Configure::read('App.AdminMail');
            $mail_message = '';
            
            $this->loadModel('Template');
            $registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'event_registration')));
            $email_subject = $registrationMail['Template']['subject'];
            $subject = __('JTSBoard ' . $email_subject . '', true);

            $mail_message = str_replace(array('{NAME}','{EMAIL}','{PHONE}','{ADDRESS}','{PARTICIPANTS}','{SITE}'), array($salon_name, $email,$phone,$address,$participants, Configure::read('App.SITENAME')), $registrationMail['Template']['content']);
            $template = 'default';
            $this->set('message', $mail_message);

            $from = 'JTSBoard <do-not-reply@jtsboard.com>';

            $this->sendMail($emailId, $subject, $mail_message, $from, $template);
          
        /****************** EMAIL NOTIFICATION MESSAGE END********************/

          $responseArr = array('status' => 'success', 'msg' => 'サービスの色を正常に追加。', 'msg1' => 'Event form submit successfully.' );
          $jsonEncode = json_encode($responseArr);
        }else{
          $responseArr = array('msg'=>'Event form submit error.', 'status' => 'error');
          $jsonEncode = json_encode($responseArr);
        }
        echo  $jsonEncode;exit();
      }

// add customer api for angular
      function add_customer($test_data =null){

        $data = file_get_contents('php://input');
        pr($data); die;
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
        
        $first_name = $customer['Customer']['first_name'] = isset($decoded['first_name']) ? $decoded['first_name'] : '';
        $last_name = $customer['Customer']['last_name'] = isset($decoded['last_name']) ? $decoded['last_name'] : '';
        $customer['Customer']['name'] = $last_name.' '.$first_name;
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
        $customer['Customer']['service_id'] = isset($decoded['service_id']) ? $decoded['service_id'] : '0';
        $verification_code = substr(md5(uniqid()), 0, 20);
        $customer['Customer']['verification_code'] = $verification_code;
        $customer['Customer']['status'] =1;

       /* $customerExist = $this->Customer->find('first', array('conditions'=>array('Customer.first_name'=>$first_name, 'Customer.last_name'=>$last_name)));
        
        if($customerExist){
            $responseArr = array('msg' => '顧客名は既に存在します。', 'msg1' => 'Customer Name is already exist.',  'status' => 'error' );
            $jsonEncode = json_encode($responseArr);
            echo $jsonEncode;
            exit();
        }
       */
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


                   // $this->sendMail($to, 'Customer Email Varify', $mail_message, $from, $template); 
                    
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


                   // $this->sendMail($to, 'Customer Email Varify', $mail_message, $from, $template); 
                    
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





}
