<?php
/**
 * Cron Controller
 *
 * PHP version 5.4
 *
 */

class CronJobController extends AppController {

	public function beforeFilter() {

	    parent::beforeFilter();
	     $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("RecordData");
        
        $this->RequestHandler->addInputType('json', array('json_decode', true));
       // App::import('Vendor', 'Google', array('file' => 'Google' . DS . 'autoload.php'));
        date_default_timezone_set("Asia/Tokyo");
        $this->Auth->allow('employee_birthday', 'customer_birthday', 'list_messages');

	}

    public function employee_birthday() {
        // Check the action is being invoked by the cron dispatcher 
         if (!defined('CRON_DISPATCHER')) { $this->redirect('/'); exit(); } 

         //no view
        $this->autoRender = false;
        $this->loadModel('User');
        $this->loadModel('Employee');

        $data = $this->Employee->find('all',array('conditions'=>array('MONTH(Employee.dob)'=>date('m'),'DAY(Employee.dob)'=>date('d'))));
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $user_id = isset($value['Employee']['user_id']) ? $value['Employee']['user_id'] : '';
                if(!empty($user_id)){

                    $emp_name = $value['Employee']['name'];
                    $empDOBNotificationMessage = 'Today is '.$emp_name.' employee birthday. Wish him Happy Birthday';
                    
                     /* User Notification send*/
                    $userData = $this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
                    $devicetoken = isset($userData['User']['device_token']) ? $userData['User']['device_token'] : '';
                    $device_type = isset($userData['User']['device_type']) ? $userData['User']['device_type'] : '';
                    
                    if(!empty($devicetoken) /*&& ($device_type == 'iphone')*/){
                        $this->IOSPushNotification($devicetoken, $empDOBNotificationMessage, $user_id, '','','', $device_type, 'user', 'employee_birthday') ;
                    }

                    /* User Notification send*/
                    $employeeData = $this->Employee->find('all', array('conditions'=>array('Employee.user_id'=>$user_id)));

                    foreach ($employeeData as $empKey => $empValue) {
                        $empDevicetoken = isset($empValue['Employee']['device_token']) ? $empValue['Employee']['device_token'] : '';
                        $emp_device_type = isset($empValue['Employee']['device_type']) ? $empValue['Employee']['device_type'] : '';
                        if(!empty($empDevicetoken) /*&& ($emp_device_type == 'iphone')*/){
                            $this->IOSPushNotification($empDevicetoken, $empDOBNotificationMessage, $user_id, $empValue['Employee']['id'],'','', $emp_device_type, 'employee', 'employee_birthday') ;
                        }
                   }
                }   
            }
            echo 'successfully';die;
        }    
       
    }

    public function customer_birthday() {
        // Check the action is being invoked by the cron dispatcher 
        if (!defined('CRON_DISPATCHER')) { $this->redirect('/'); exit(); }
        //no view
        $this->autoRender = false; 
        $this->loadModel('User');
        $this->loadModel('Employee');
        $this->loadModel('Customer');

        $data = $this->Customer->find('all',array('conditions'=>array('MONTH(Customer.dob)'=>date('m'),'DAY(Customer.dob)'=>date('d'))));
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $user_id = isset($value['Customer']['user_id']) ? $value['Customer']['user_id'] : '';
                if(!empty($user_id)){

                    $emp_name = $value['Customer']['last_name'].' '.$value['Customer']['first_name'];
                    $empDOBNotificationMessage = 'Today is '.$emp_name.' customer birthday. Wish him Happy Birthday';
                    
                     /* User Notification send*/
                    $userData = $this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
                    $devicetoken = isset($userData['User']['device_token']) ? $userData['User']['device_token'] : '';
                    $device_type = isset($userData['User']['device_type']) ? $userData['User']['device_type'] : '';
                    
                    if(!empty($devicetoken) /*&& ($device_type == 'iphone')*/){
                        $this->IOSPushNotification($devicetoken, $empDOBNotificationMessage, $user_id, '','','', $device_type, 'user', 'customer_birthday') ;
                    }

                    /* User Notification send*/
                    $employeeData = $this->Employee->find('all', array('conditions'=>array('Employee.user_id'=>$user_id)));

                    foreach ($employeeData as $empKey => $empValue) {
                        $empDevicetoken = isset($empValue['Employee']['device_token']) ? $empValue['Employee']['device_token'] : '';
                        $emp_device_type = isset($empValue['Employee']['device_type']) ? $empValue['Employee']['device_type'] : '';
                        if(!empty($empDevicetoken) /*&& ($emp_device_type == 'iphone')*/){
                            $this->IOSPushNotification($empDevicetoken, $empDOBNotificationMessage, $user_id, $empValue['Employee']['id'],'','', $emp_device_type, 'employee', 'customer_birthday') ;
                        }
                   }
                }   
            }
        }    
       
    }


    public function IOSPushNotification($deviceToken=null,$message=null,$user_id=null,$employee_id=null,$customer_id=null,  $reservation_id=null, $device_type=null, $member_type=null, $notification_type=null) {
        $ctx = stream_context_create();
        $passphrase = '123456';
        // ck.pem is your certificate file
        $base_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR;
        $path_to_cert = $base_path.'/pushpem/ck.pem';
        stream_context_set_option($ctx, 'ssl', 'local_cert', $path_to_cert);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        // Create the payload body
        $body['aps'] = array(
            'alert' => array(
                'title' => Configure::read('Site.title'),
                'body' => $message,
                'reservation_id' => $reservation_id,
                'notification_type' => $notification_type,
             ),
            'sound' => 'default'
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // echo $payload;die;
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        
        // Close the connection to the server
        fclose($fp);
        if (!$result){

            return 'Message not delivered' . PHP_EOL;
        }
        else{

            $this->loadModel('PushNotification');
            $pushNotificationData = array();

            $pushNotificationData['PushNotification']['user_id'] = ($user_id != null) ? $user_id : '';
            $pushNotificationData['PushNotification']['employee_id'] = ($employee_id != null) ? $employee_id : '';
            $pushNotificationData['PushNotification']['customer_id'] = ($customer_id != null) ? $customer_id : '';
            $pushNotificationData['PushNotification']['reservation_id'] = ($reservation_id != null) ? $reservation_id : '';
            $pushNotificationData['PushNotification']['device_type'] = ($device_type != null) ? $device_type : '';
            $pushNotificationData['PushNotification']['device_token'] = ($deviceToken != null) ? $deviceToken : '';
            $pushNotificationData['PushNotification']['member_type'] = ($member_type != null) ? $member_type : '';
            $pushNotificationData['PushNotification']['notification_type'] = ($notification_type != null) ? $notification_type : '';
            $pushNotificationData['PushNotification']['message'] = ($message != null) ? $message : '';
            $pushNotificationData['PushNotification']['status'] =  '0';

            $this->PushNotification->saveAll($pushNotificationData);
            return 'Message successfully delivered' . PHP_EOL;
        }
    }


   


	public function list_messages($userId = null) {


       
	 	// Check the action is being invoked by the cron dispatcher 
		//if (!defined('CRON_DISPATCHER')) { $this->redirect('/'); exit(); } 

		
        define('PROJECT_LIBS', dirname(dirname(__FILE__)));
        require(PROJECT_LIBS. '/Vendor/autoload.php'); 

 
        $userId = 'isso@zedinternational.net';
        $client = new Google_Client();

        $client->setApplicationName('Jts Board');
        $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);



        //$credentials =  PROJECT_LIBS.'/Vendor/client_secret_428382209403-kejkirln30v996j2qm3dg86u22oecria.apps.googleusercontent.com.json';
       // $credentials =  PROJECT_LIBS.'/Vendor/client_secret_317331944692-v10ter7hlgvu04vtosib0avhg58lbsho.apps.googleusercontent.com.json';
        $credentials =  PROJECT_LIBS.'/Vendor/client_secret_317331944692-v10ter7hlgvu04vtosib0avhg58lbsho.apps.googleusercontent.com.json';
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');
       // print_r($client);die;

        

        $this->loadModel('User');
        $this->loadModel('Service');
        $this->loadModel('Employee');
        $this->loadModel('Customer');
        $this->loadModel('Reservation');
        
        // Load previously authorized credentials from a file.
        $credentialsPath = 'token11.json';

       
        // $accessToken = json_decode(file_get_contents($credentialsPath), true);
       // print_r($credentialsPath);die;
         
       if (file_exists($credentialsPath)) {

           $accessToken = json_decode(file_get_contents($credentialsPath), true);
           //print_r($accessToken);die;
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            //$authCode = '4/qwAduBGKd-j-fDldYfhVIkE13xhw6fmGV1bvJGv2jgVfa43DBf37iGdm1Y7siT2vssINYsltHym2ivMyJOJAeIg';
            $authCode = '4/rABrdLMhBEhBgwTYH06SQaRMVqcWFVe7rLUgVs50RNNNwivl1J0MbUEyuxOCztHqjYk7yFD0Zz7TpznmA2f1tj8';

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            print_r($accessToken);die;

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
        $opt_param['maxResults'] = array('100');
        $opt_param['labelIds'] = array('Label_2');
        
        $labelsResponse = $service->users_labels->listUsersLabels($userId);
        
        $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);  
    

        $messages =array();
       
        if ($messagesResponse->getMessages()) {
            $messages = array_merge($messages, $messagesResponse->getMessages());
            $pageToken = $messagesResponse->getNextPageToken();
        }
        //echo '<pre>';
        //print_r($messages);die;

        // $msg = "Cron add successfully.";
 
        // // send email
        // mail("mahenktripathi@gmail.com","cron add",$credentialsPath);
        // echo 'successfully done.';die;

        $i =$j=0;
        $reservation = array();
        $user_id= '102';
        $servicelist = $this->Service->find('all', array('fields' => array('Service.id', 'Service.name')));
        $employeelist = $this->Employee->find('all', array('conditions' => array('Employee.user_id' => $user_id), 'fields' => array('Employee.id', 'Employee.name')));
        $addCustomer =array();
        foreach ($messages as $message) {
            
            $messageData = $service->users_messages->get($userId, $message->getId());
            echo $subject = $messageData->payload->headers[19]['value'];
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
            $note = $this->get_string_between($decodedBody, '■ご要望・ご相談', '■サロンからお客様への質問');
            $question = $this->get_string_between($decodedBody, '質問：', '回答：');
            $answer = $this->get_string_between($decodedBody, '回答：', 'PC版SALON');
            
             //$minutes_to_add = $duration*60;
            $startTime = (strtotime($reservation_time));
            $reservation_start_time = date('H:i', $startTime);
           $reservation_start_date_time =  $reservation_date.' '.$reservation_start_time;
            if(($duration > 0 ) && !empty($reservation_time)){
                $duration = $duration*60*60;
                $endTime = (strtotime($reservation_time) + $duration);
                $reservation_end_time = date('H:i', $endTime);
                $reservation_end_date_time =  $reservation_date.' '.$reservation_end_time;
            }else{
                $reservation_end_time = '00 :00';
                $reservation_end_date_time =  $reservation_date.' 00:00';
            }

            $services =array();
            $s = 0;
            $service_id = '1';
            $employee_ids = '';
            foreach ($servicelist as $key => $value) {
                 if(strpos($decodedBody, $value['Service']['name'])){
                    $service_id = $value['Service']['id'];
                    $services[$s]['id'] =$value['Service']['id'];
                    $services[$s]['name'] =$value['Service']['name'];
                    $s++;
                }
            }

            foreach ($employeelist as $empkey => $empvalue) {
                 if(strpos($designatd_staff, $empvalue['Employee']['name'])){
                    if(empty($employee_ids)){
                        $employee_ids = $empvalue['Employee']['id'];
                    }else{
                        $employee_ids .= ', '.$empvalue['Employee']['id'];
                    }
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
            $new_reservation_date = date('Y-m-d',strtotime($reservation_date));
            $current_date = date('Y-m-d');
            $reservationData = $this->Reservation->find('first', array('conditions'=> array( 'Reservation.reservation_number'=>$reservation_number), 'fields' =>array('Reservation.id', 'Reservation.reservation_number')));
           if(!isset($reservationData['Reservation']['reservation_number']) && empty($reservationData['Reservation']['reservation_number']) && ($new_reservation_date >= $current_date) && !strpos($subject, 'キャンセル連絡')){
                if(!empty($name)){
                    $kanji_name = $this->get_string_between($decodedBody, '氏名', '（');
                    $fullName = explode(' ', $kanji_name);
                    if(isset($fullName[1]) && !empty($fullName[1])){
                        $first_name = ltrim($fullName[1]);
                        $last_name = ltrim($fullName[0]);
                    }else{
                        $first_name = ltrim($nick_name);
                        $last_name ='';
                    }
                     $kana_name = $this->get_string_between($name, '（', '）');
                     $kana_name_arr = explode(' ', $kana_name);
                     if(isset($kana_name_arr[1]) && !empty($kana_name_arr[1])){
                        $kana_first_name = ltrim($kana_name_arr[1]);
                        $kana_last_name = ltrim($kana_name_arr[0]);
                     }else{
                        $kana_first_name = ltrim($kana_name);
                        $kana_last_name ='';
                     }
                     $first_name = str_replace('　', '', $first_name);
                     $last_name = str_replace('　', '', $last_name);
                     $kana_first_name = str_replace('　', '', $kana_first_name);
                     $kana_last_name = str_replace('　', '', $kana_last_name);


                    $full_name = $last_name." ".$first_name;
                    $condition['Customer.user_id'] =  $user_id;
                    $condition['Customer.first_name'] =  $first_name;
                    $condition['Customer.last_name'] =  $last_name;
                   
                    $CustomerData = $this->Customer->find('first', array('conditions'=> $condition));
                    
                    if(isset($CustomerData['Customer']['id']) && !empty($CustomerData['Customer']['id'])){
                        $cusromer_id = $CustomerData['Customer']['id'];
                        if(empty($CustomerData['Customer']['last_visited']) || ($CustomerData['Customer']['last_visited'] =='null'))
                            $CustomerData['Customer']['last_visited'] = $CustomerData['Customer']['modified'];
                        $reservation[$i]['Reservation']['last_visited'] = $CustomerData['Customer']['last_visited'];
                    }else{
                       // $customerData =array();
                      
                        $customerData['Customer']['user_id'] = $user_id;
                        $customerData['Customer']['service_id'] = $service_id;
                        $customerData['Customer']['name'] = $full_name;
                        $customerData['Customer']['first_name'] = $first_name;
                        $customerData['Customer']['last_name'] = $last_name;
                        $customerData['Customer']['kana_first_name'] = $kana_first_name;
                        $customerData['Customer']['kana_last_name'] = $kana_last_name;
                        $customerData['Customer']['is_gmail'] = '1';
                        $customerData['Customer']['status'] =0;
                        $this->Customer->saveAll($customerData); 

                       
                        $cusromer_id = $this->Customer->id;
                        $reservation[$i]['Reservation']['last_visited'] = $reservation_datetime;
                    }
                }
               // echo $cusromer_id;die;
                $reservation[$i]['Reservation']['user_id'] = $user_id;
                $reservation[$i]['Reservation']['service_id'] = $service_id;
                $reservation[$i]['Reservation']['customer_id'] = $cusromer_id;
                $reservation[$i]['Reservation']['employee_ids'] = $employee_ids;
                $reservation[$i]['Reservation']['reservation_number'] = $reservation_number;
                $reservation[$i]['Reservation']['name'] = $full_name;
                $reservation[$i]['Reservation']['channel'] = '';
                $reservation[$i]['Reservation']['note'] = $note;
                $reservation[$i]['Reservation']['reservation_date'] = $reservation_date;
                $reservation[$i]['Reservation']['start_date'] = $reservation_date;
                $reservation[$i]['Reservation']['end_date'] = $reservation_date;
                $reservation[$i]['Reservation']['extra_start_date'] = $reservation_start_date_time;
                $reservation[$i]['Reservation']['extra_end_date'] = $reservation_end_date_time;
                $reservation[$i]['Reservation']['start_time'] = $reservation_time;
                $reservation[$i]['Reservation']['end_time'] = $reservation_end_time;
                $reservation[$i]['Reservation']['reservation_time'] = $reservation_time;
                $reservation[$i]['Reservation']['designatd_staff'] = $designatd_staff;
                $reservation[$i]['Reservation']['payment_total'] = '1';
                $reservation[$i]['Reservation']['is_gmail'] = '1';
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
            }else{
                //echo $subject;
                $this->Reservation->id = $reservationData['Reservation']['id'];
                $this->Reservation->saveField('status' , '4' );
            } 
            
        }


        
        $this->Reservation->saveAll($reservation);

        
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

    public function test_mail() {
        // Check the action is being invoked by the cron dispatcher 
        if (!defined('CRON_DISPATCHER')) { $this->redirect('/'); exit(); } 


        //no view
         $this->autoRender = false;

        $to      = 'mahen.zed12@gmail.com';
        $subject = 'test mail';
        $message = 'hello test';
        
        mail($to, $subject, $message);
        //do stuff...

        // return;
    }

	
}
  
?>