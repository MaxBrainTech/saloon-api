<?php
/**
 * Cron Controller
 *
 * PHP version 5.4
 *
 */




class CronSchedulersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout=null;

        $this->RequestHandler->addInputType('json', array('json_decode', true));
        $this->Auth->allow('employee_birthday', 'customer_birthday',  'salon_board_scraper',  'hotpaper_customer_list', 'get_string_between', 'delete_reservation', 'test');
        date_default_timezone_set("Asia/Tokyo");
    }

    


    public function salon_board_scraper($test_data = null){
       
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_REQUEST);
        } 
        $this->loadModel('Service');
        $this->loadModel('Employee');
        $this->loadModel('Customer');
        $this->loadModel('Reservation');
        $this->loadModel('ReservationRead');
        $this->loadModel('ReservationStatusRead');
        $decoded = json_decode($data, true);
        $user_id = isset($decoded['userId']) ? $decoded['userId'] : '';
        $servicelist = $this->Service->find('all', array('conditions' => array('Service.user_id' => $user_id), 'fields' => array('Service.id', 'Service.name')));
        $employeelist = $this->Employee->find('all', array('conditions' => array('Employee.user_id' => $user_id), 'fields' => array('Employee.id', 'Employee.name', 'Employee.service_id')));
        // pr($decoded);die;
        
        $i =0;
        foreach ($decoded as $key => $value) {
            $reservation_number = isset($value['reservation_number']) ? $value['reservation_number'] : '';
            $reservation_route = isset($value['reservation_route']) ? $value['reservation_route'] : '';
            $menu = isset($value['menu']) ? $value['menu'] : '';
            $coupon_name = isset($value['coupon_name']) ? $value['coupon_name'] : '';
            $kana_name = isset($value['kana_name']) ? $value['kana_name'] : '';
            $customer_phone_number = isset($value['customer_phone_number']) ? $value['customer_phone_number'] : '';
            $kanji_name = isset($value['kanji_name']) ? $value['kanji_name'] : '';
            $number_of_visits = isset($value['number_of_visits']) ? $value['number_of_visits'] : '';
            $service_name = isset($value['service_name']) ? $value['service_name'] : '';
            $service_duration = isset($value['service_duration']) ? $value['service_duration'] : '';
            $staff = isset($value['staff']) ? $value['staff'] : '';
           
            $presentation_conditions = isset($value['presentation_conditions']) ? $value['presentation_conditions'] : '';
            $customer_phone_number = isset($value['customer_phone_number']) ? $value['customer_phone_number'] : '';
            $coupon_name = isset($value['coupon_name']) ? $value['coupon_name'] : '';
            $coupon_description = isset($value['coupon_description']) ? $value['coupon_description'] : '';
            $reservation_amount = isset($value['reservation_amount']) ? $value['reservation_amount'] : '0 円';
            $used_points = isset($value['used_points']) ? $value['used_points'] : '0';
            $note = isset($value['note']) ? $value['note'] : '';

            $status = isset($value['status']) ? $value['status'] : '';

            /* reservation date section*/
            $visit_date = isset($value['visit_date']) ? $value['visit_date'] : '';
            if(!empty($visit_date)){
                $reservation_datetime = 'start'.$visit_date.'end';
                $y = $this->get_string_between($reservation_datetime, 'start', '年');
                $y = trim($y); 
                $m = $this->get_string_between($reservation_datetime, '年', '月');
                $d = $this->get_string_between($reservation_datetime, '月', '日');
                $reservation_date = $y.'-'.$m.'-'.$d;
                $reservation_start_time  = $this->get_string_between($reservation_datetime, '）', ' ～');
                $reservation_end_time  = $this->get_string_between($reservation_datetime, ' ～', ' 所要時間');
                $reservation_duration  = $this->get_string_between($reservation_datetime, '[ ', ' ]');
                $reservation_datetime = $reservation_date.' '.$reservation_start_time;
            }else{
                $reservation_date = '';
                $reservation_start_time  = '00 :00';
                $reservation_end_time  = '00 :00';
                $reservation_duration  = '00 :00';
                $reservation_datetime = '';
            }  
            
            /* Service id section*/
            $service_id = '0';
            $cusromer_id = $employee_ids = '';
            foreach ($servicelist as $key => $value) {
                if($service_name ==  $value['Service']['name']){
                    $service_id = $value['Service']['id'];
                }
            }



            
            // $staff = str_replace("　"," ",$staff);
            $staff2 = str_replace("　","",$staff);
            $employee_ids = '';
            

            foreach ($employeelist as $empkey => $empvalue) {
                 if( ($staff ==  $empvalue['Employee']['name']) || ($staff2 ==  $empvalue['Employee']['name'])){
                    if( ($service_id == '0') && isset($empvalue['Employee']['service_id']) && !empty($empvalue['Employee']['service_id']) ){
                        $service_id = $empvalue['Employee']['service_id'];
                    }
                    if(empty($employee_ids)){
                        $employee_ids = $empvalue['Employee']['id'];
                    }else{
                        $employee_ids .= ', '.$empvalue['Employee']['id'];
                    }
                }
            }
            
             /* kana_name section*/
            if(!empty($kana_name)){
                $kana_name_arr = explode(' ', $kana_name);
                if(isset($kana_name_arr[1]) && !empty($kana_name_arr[1])){
                    $kana_first_name = ltrim($kana_name_arr[1]);
                    $kana_last_name = ltrim($kana_name_arr[0]);
                }else{
                    $kana_first_name = ltrim($kana_name);
                    $kana_last_name ='';
                }
                $kana_first_name = str_replace('　', '', $kana_first_name);
                $kana_last_name = str_replace('　', '', $kana_last_name); 
            }else{
                $kana_first_name = '';
                $kana_last_name = ''; 
            }    
            
            /* Customer add section*/
            $cusromer_id = 0;
            if(!empty($kanji_name)){
                $fullName = explode(' ', $kanji_name);
                if(isset($fullName[1]) && !empty($fullName[1])){
                    $first_name = ltrim($fullName[1]);
                    $last_name = ltrim($fullName[0]);
                }else{
                    $first_name = ltrim($kanji_name);
                    $last_name ='';
                }
                $first_name = str_replace('　', '', $first_name);
                $last_name = str_replace('　', '', $last_name);
                if(empty($first_name)){
                    $first_name = $kana_last_name;
                }
                if(empty($last_name)){
                    $last_name = $kana_first_name;
                }

                $full_name = $last_name." ".$first_name;
                $full_name = trim($full_name);
                $condition['Customer.user_id'] =  $user_id;
                $condition['Customer.first_name'] =  $first_name;
                $condition['Customer.last_name'] =  $last_name;
               
                $CustomerData = $this->Customer->find('first', array('conditions'=> $condition));

                
                if(isset($CustomerData['Customer']['id']) && !empty($CustomerData['Customer']['id'])){
                    $cusromer_id = $CustomerData['Customer']['id'];
                    $this->Customer->id = $CustomerData['Customer']['id'];
                    $customerData['Customer']['modified'] = date('Y-m-d H:i:s');
                    // $this->Customer->saveField('status' , 1);
                    if(empty($CustomerData['Customer']['last_visited']) || ($CustomerData['Customer']['last_visited'] =='null'))
                        $CustomerData['Customer']['last_visited'] = $CustomerData['Customer']['modified'];
                    $last_visited = $CustomerData['Customer']['last_visited'];
                    

                }else{
                    
                    $customerData['Customer']['user_id'] = $user_id;
                    $customerData['Customer']['service_id'] = $service_id;
                    $customerData['Customer']['name'] = $full_name;
                    $customerData['Customer']['first_name'] = $first_name;
                    $customerData['Customer']['last_name'] = $last_name;
                    $customerData['Customer']['kana_first_name'] = $kana_first_name;
                    $customerData['Customer']['kana_last_name'] = $kana_last_name;
                    $customerData['Customer']['tel'] = $customer_phone_number;
                    $customerData['Customer']['is_gmail'] = '1';
                    $customerData['Customer']['status'] ='0';
                    $this->Customer->saveAll($customerData); 
                    $cusromer_id = $this->Customer->id;
                    $last_visited = $reservation_datetime;
                   
                }    
            } 
            $reservationData = array();
            $reservationData = $this->Reservation->find('first', array('conditions'=> array( 'Reservation.reservation_number'=>$reservation_number, 'Reservation.user_id'=>$user_id), 'fields' =>array('Reservation.id', 'Reservation.reservation_number')));
            $reservation =array();
           /* Reservation Array Section*/
            if(!empty($reservation_number)){
                if(($status == 'お客様キャンセル' ) ||  ($status == 'サロンキャンセル')){
                    if(isset($reservationData['Reservation']['id']) && !empty($reservationData['Reservation']['id'])){
                        $id = $reservationData['Reservation']['id'];
                        $this->Reservation->delete($id, true);
                    }    
                     
                }elseif( ( (isset($reservationData['Reservation']['id']) ) && ($status != 'お客様キャンセル') ) || (isset($reservationData['Reservation']['id']) && ($status != 'サロンキャンセル') ) ){
                    $reservation['Reservation']['id'] =  isset($reservationData['Reservation']['id']) ? $reservationData['Reservation']['id'] : '';
                    $reservation['Reservation']['last_visited'] = $last_visited;
                    $reservation['Reservation']['reservation_number'] = $reservation_number;
                    $reservation['Reservation']['user_id'] = $user_id;
                    $reservation['Reservation']['service_id'] = $service_id;
                    $reservation['Reservation']['customer_id'] = $cusromer_id;
                    $reservation['Reservation']['employee_ids'] = $employee_ids;
                    $reservation['Reservation']['reservation_type'] = Configure::read('App.Status.active');
                    $reservation['Reservation']['all_day'] = '0';
                    $reservation['Reservation']['start_date'] = $reservation_date;
                    $reservation['Reservation']['end_date'] = $reservation_date;
                    $reservation['Reservation']['extra_start_date'] = $reservation_date;
                    $reservation['Reservation']['extra_end_date'] = $reservation_date;
                    $reservation['Reservation']['used_points'] = $used_points;
                    $reservation['Reservation']['channel'] = 'サロンボード';
                    $reservation['Reservation']['note'] = $note;
                    
                    $reservation['Reservation']['start_time'] = $reservation_start_time;
                    $reservation['Reservation']['end_time'] = $reservation_end_time;
                    $reservation['Reservation']['is_gmail'] = '1';
                    $reservation['Reservation']['salon_board'] = '1';
                    if(!empty($menu)){
                        $reservation['Reservation']['menu'] = '1';
                    }else{
                        $reservation['Reservation']['menu'] = '0';
                        $reservation['Reservation']['menu_text'] = '';
                    }
                    $reservation['Reservation']['reservation_total'] = $reservation_amount;
                    $reservation['Reservation']['payment_total'] = $reservation_amount;
                    $reservation_id = $reservationData['Reservation']['id'];
                    $this->Reservation->saveAll($reservation); 
               }elseif( (!isset($reservationData['Reservation']['id'])  && ($status != 'お客様キャンセル' )) || (!isset($reservationData['Reservation']['id']) && ($status != 'サロンキャンセル')) ){
                    
                    $reservation['Reservation']['last_visited'] = $last_visited;
                    $reservation['Reservation']['reservation_number'] = $reservation_number;
                    $reservation['Reservation']['user_id'] = $user_id;
                    $reservation['Reservation']['service_id'] = $service_id;
                    $reservation['Reservation']['customer_id'] = $cusromer_id;
                    $reservation['Reservation']['employee_ids'] = $employee_ids;
                    $reservation['Reservation']['reservation_type'] = Configure::read('App.Status.active');
                    $reservation['Reservation']['all_day'] = '0';
                    $reservation['Reservation']['start_date'] = $reservation_date;
                    $reservation['Reservation']['end_date'] = $reservation_date;
                    $reservation['Reservation']['extra_start_date'] = $reservation_date;
                    $reservation['Reservation']['extra_end_date'] = $reservation_date;
                    $reservation['Reservation']['used_points'] = $used_points;
                    
                    $reservation['Reservation']['channel'] = 'サロンボード';
                    $reservation['Reservation']['note'] = ' ';
                    
                    $reservation['Reservation']['start_time'] = $reservation_start_time;
                    $reservation['Reservation']['end_time'] = $reservation_end_time;
                    $reservation['Reservation']['is_gmail'] = '1';
                    $reservation['Reservation']['salon_board'] = '1';
                    $reservation['Reservation']['status'] = '1';
                    if(!empty($menu)){
                        $reservation['Reservation']['menu'] = '1';
                        // $reservation['Reservation']['menu_text'] = $menu;
                    }else{
                        $reservation['Reservation']['menu'] = '0';
                        $reservation['Reservation']['menu_text'] = '';
                    }
                    $reservation['Reservation']['reservation_total'] = $reservation_amount;
                    $reservation['Reservation']['payment_total'] = $reservation_amount;
                    $reservation_id = '0';
                    if($this->Reservation->saveAll($reservation)){
                        $reservation_id = $this->Reservation->id; 
                        
                        $employeeData = $this->Employee->find('all', array('conditions'=>array('Employee.user_id'=>$user_id)));
                        foreach ($employeeData as $empKey => $empValue) {
                            $reservationStatusReadData = $reservationStatusData = array();
                            $emps_id = isset($empValue['Employee']['id']) ? $empValue['Employee']['id'] : '';
                            $reservationStatusData['ReservationRead']['user_id'] =  $user_id;
                            $reservationStatusData['ReservationRead']['reservation_id'] =  $reservation_id;
                            $reservationStatusData['ReservationRead']['employee_id'] =  $emps_id;
                            $reservationStatusData['ReservationRead']['date'] =  $reservation_date;
                            $reservationStatusData['ReservationRead']['status'] =  '0';
                            $this->ReservationRead->saveAll($reservationStatusData);


                            $reservationStatusReadData['ReservationStatusRead']['user_id'] =  $user_id;
                            $reservationStatusReadData['ReservationStatusRead']['employee_id'] =  $emps_id;
                            $reservationStatusReadData['ReservationStatusRead']['reservation_id'] =  $reservation_id;
                            $reservationStatusReadData['ReservationStatusRead']['status'] =  '0';
                            $this->ReservationStatusRead->saveAll($reservationStatusReadData);

                        }
                        
                        /* User Notification send*/
                        // $reservationNotificationMessage =  '予約が追加されました';
                        $reservationNotificationMessage =  $last_name.' '.$first_name.' 様が '.$visit_date.' に予約しました';
                        $employeeData = $this->Employee->find('all', array('conditions'=>array('Employee.user_id'=>$user_id)));
                        $deviceTokenArr = array();
                        foreach ($employeeData as $empKey => $empValue) {
                            $emp_id = isset($empValue['Employee']['id']) ? $empValue['Employee']['id'] : '';
                            $empDevicetoken = isset($empValue['Employee']['device_token']) ? $empValue['Employee']['device_token'] : '';
                            $emp_device_type = isset($empValue['Employee']['device_type']) ? $empValue['Employee']['device_type'] : '';
                            if(!empty($empDevicetoken) && !in_array($empDevicetoken, $deviceTokenArr) ){
                                 $this->IOSPushNotification($empDevicetoken, $reservationNotificationMessage, $user_id, $empValue['Employee']['id'],'', $reservation_id,  $emp_device_type, 'employee', 'add_reservation');
                                array_push($deviceTokenArr, $empDevicetoken);
                            }
                        }
                    }  
               }
               
            }   
        }
        echo 'Sccuessfully.';exit();
       
       
        
    }

    public function hotpaper_customer_list($test_data = null){
       
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_REQUEST);
        } 
        $this->loadModel('HotpaperCustomer');
        $decoded = json_decode($data, true);
        // pr($decoded);die;
        $i =0;
        $condition = $customerData = array();
        foreach ($decoded as $key => $value) {

            $customer_code = isset($value['customer_code']) ? $value['customer_code'] : '';
            $condition['HotpaperCustomer.customer_code'] =  $customer_code ;
            $hotpaperCustomerData = $this->HotpaperCustomer->find('first', array('conditions'=> $condition));

            if(!isset($hotpaperCustomerData['HotpaperCustomer']['id']) && ($hotpaperCustomerData['HotpaperCustomer']['id'] != $customer_code)){
                $customerData[$i]['HotpaperCustomer']['user_id'] = isset($value['userId']) ? $value['userId'] : '102';
                $customerData[$i]['HotpaperCustomer']['customer_code'] = isset($value['customer_code']) ? $value['customer_code'] : '';
                $customerData[$i]['HotpaperCustomer']['kanji_name'] = isset($value['kanji_name']) ? $value['kanji_name'] : '';
                $customerData[$i]['HotpaperCustomer']['kana_name'] = isset($value['kana_name']) ? $value['kana_name'] : '';
                $customerData[$i]['HotpaperCustomer']['tel_number'] = isset($value['tel_number']) ? $value['tel_number'] : '';
                $dob = isset($value['dob']) ? $value['dob'] : '';
                $customerData[$i]['HotpaperCustomer']['dob'] = date('Y-m-d', strtotime($dob));
                
                $first_visit_date = isset($value['first_visit_date']) ? $value['first_visit_date'] : '';
                $customerData[$i]['HotpaperCustomer']['first_visit_date'] =date('Y-m-d', strtotime($first_visit_date));
                
                $customerData[$i]['HotpaperCustomer']['gender'] = isset($value['gender']) ? $value['gender'] : '';
                $i++;
            }

            
        }
        if($this->HotpaperCustomer->saveAll($customerData)){
            echo 'Sccuessfully.';exit();
        }else{
            echo 'Error.';exit();
        }    
        
       
    }



    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public function employee_birthday() {
        $this->autoRender = false;
        $this->loadModel('User');
        $this->loadModel('Employee');

        $data = $this->Employee->find('all',array('conditions'=>array('MONTH(Employee.dob)'=>date('m'),'DAY(Employee.dob)'=>date('d'))));
        pr($data);die;
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $user_id = isset($value['Employee']['user_id']) ? $value['Employee']['user_id'] : '';
                $employee_id = isset($value['Employee']['id']) ? $value['Employee']['id'] : '';
                if(!empty($user_id)){

                    $emp_name = $value['Employee']['name'];
                    $empDOBNotificationMessage = '今日は'.$emp_name.' 様の誕生日です！お祝いの言葉を送りましょう';
                    
                     /* User Notification send*/
                    $userData = $this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
                    $devicetoken = isset($userData['User']['device_token']) ? $userData['User']['device_token'] : '';
                    $device_type = isset($userData['User']['device_type']) ? $userData['User']['device_type'] : '';

                    $deviceTokenArr =array();
                    if(!empty($devicetoken) /*&& ($device_type == 'iphone')*/){
                        // $this->IOSPushNotification($devicetoken, $empDOBNotificationMessage, $user_id, '','','', $device_type, 'user', 'employee_birthday') ;
                    }

                    /* User Notification send*/
                    $employeeData = $this->Employee->find('all', array('conditions'=>array('Employee.user_id'=>$user_id), 'order' => array('Employee.modified' => 'DESC')));

                    foreach ($employeeData as $empKey => $empValue) {
                        $empDevicetoken = isset($empValue['Employee']['device_token']) ? $empValue['Employee']['device_token'] : '';
                        $emp_device_type = isset($empValue['Employee']['device_type']) ? $empValue['Employee']['device_type'] : '';
                        $emp_id = isset($empValue['Employee']['id']) ? $empValue['Employee']['id'] : '';

                        if(!empty($empDevicetoken) && !in_array($empDevicetoken, $deviceTokenArr) ){

                            if($emp_device_type == 'Android'){
                                
                                $this->androidPushNotification($empDevicetoken, $empDOBNotificationMessage, $user_id, $empValue['Employee']['id'],'','', $emp_device_type, 'employee', 'employee_birthday', Configure::read('App.Firebase.apikey'));
                            }else{
                                $this->IOSPushNotification($empDevicetoken, $empDOBNotificationMessage, $user_id, $empValue['Employee']['id'],'','', $emp_device_type, 'employee', 'employee_birthday') ;
                            }
                            array_push($deviceTokenArr, $empDevicetoken);
                        }

                   }
                }   
            }
            echo 'successfully';die;
        }    
       
    }

    public function customer_birthday() {
        // Check the action is being invoked by the cron dispatcher 
        // if (!defined('CRON_DISPATCHER')) { $this->redirect('/'); exit(); }
        //no view
        $this->autoRender = false; 
        $this->loadModel('User');
        $this->loadModel('Employee');
        $this->loadModel('Customer');

        $data = $this->Customer->find('all',array('conditions'=>array('MONTH(Customer.dob)'=>date('m'),'DAY(Customer.dob)'=>date('d'))));
        pr($data);die;
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $user_id = isset($value['Customer']['user_id']) ? $value['Customer']['user_id'] : '';
                if(!empty($user_id)){

                    $customer_name = $value['Customer']['last_name'].' '.$value['Customer']['first_name'];
                    $customer_id = $value['Customer']['id'];
                    $empDOBNotificationMessage = '今日は'.$customer_name.' 様の誕生日です！お祝いの言葉を送りましょう';
                    $deviceTokenArr =array();
                     /* User Notification send*/
                    $userData = $this->User->find('first', array('conditions'=>array('User.id'=>$user_id)));
                    $devicetoken = isset($userData['User']['device_token']) ? $userData['User']['device_token'] : '';
                    $device_type = isset($userData['User']['device_type']) ? $userData['User']['device_type'] : '';
                    
                   
                    /* User Notification send*/

                    $employeeData = $this->Employee->find('all', array('conditions'=>array('Employee.user_id'=>$user_id), 'order' => array('Employee.modified' => 'DESC')));
                    foreach ($employeeData as $empKey => $empValue) {
                        $emp_id = isset($empValue['Employee']['id']) ? $empValue['Employee']['id'] : '';
                        $empDevicetoken = isset($empValue['Employee']['device_token']) ? $empValue['Employee']['device_token'] : '';
                        $emp_device_type = isset($empValue['Employee']['device_type']) ? $empValue['Employee']['device_type'] : '';
                        
                        if(!empty($empDevicetoken) && !in_array($empDevicetoken, $deviceTokenArr) ){

                            if($emp_device_type == 'Android'){
                                
                                $this->androidPushNotification($empDevicetoken, $empDOBNotificationMessage, $user_id, $empValue['Employee']['id'],$customer_id,'', $emp_device_type, 'employee', 'customer_birthday', Configure::read('App.Firebase.apikey')) ;
                            }else{
                                $this->IOSPushNotification($empDevicetoken, $empDOBNotificationMessage, $user_id, $empValue['Employee']['id'],$customer_id,'', $emp_device_type, 'employee', 'customer_birthday') ;
                            }
                            array_push($deviceTokenArr, $empDevicetoken);
                        }
                    }
                }   
            }
            die('successfully');
        }    
       
    }


    /************************ Push Notification ***********************/




    public function IOSPushNotification($deviceToken=null,$message=null,$user_id=null,$employee_id=null,$customer_id=null,  $reservation_id=null, $device_type=null, $member_type=null, $notification_type=null) {
          // $deviceToken = $devicetoken;
        // $deviceToken = '6cd9ab42712ee9fcc04aa46cfb936c18ae0470c222d666ddc4ab3278f5ef2d97';
        $this->loadModel('Employee');
        $this->loadModel('Reservation');
        $this->loadModel('CustomerHistory');
        $this->loadModel('PushNotification');
        $this->loadModel('NotificationRead');
        $this->loadModel('NotificationCount');
        $this->loadModel('BadgeCount');

        $notificationCountData = $this->NotificationCount->find('first',array('conditions'=>array('NotificationCount.employee_id'=>$employee_id, 'NotificationCount.user_id'=>$user_id)));
        if(isset($notificationCountData['NotificationCount']['count']) && !empty($notificationCountData['NotificationCount']['count'])){
            $notification_count = $notificationCountData['NotificationCount']['count'];
        }else{
            $notification_count = 0;
        }    
        $badge_count = 0;
        $badgeCountData = $this->BadgeCount->find('first',array('conditions'=>array('BadgeCount.employee_id'=>$employee_id, 'BadgeCount.user_id'=>$user_id)));
        if(isset($badgeCountData['BadgeCount']['count']) && !empty($badgeCountData['BadgeCount']['count'])){
            $badge_count = $badgeCountData['BadgeCount']['count'];
        }
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
        if($notification_type == 'chat'){
            $title = $this->get_employee_name($customer_id);
        }else{
            $title = Configure::read('Site.title');
        }

       
        $body['aps']['alert']['title']  = $title;
        $body['aps']['alert']['body']  = $message;
        $body['aps']['alert']['reservation_id']  = $reservation_id;
        $body['aps']['alert']['notification_type']  = $notification_type;
        $body['aps']['alert']['notification_count']  = (string)($notification_count+1);
        // $body['aps']['badge']  = $notification_count+1;
        $body['aps']['badge']  =  $badge_count+1;
        $body['aps']['sound']  = 'default';   

        if(($notification_type == 'add_reservation') || ($notification_type == 'reservation_status')){
            $reservationData = $this->Reservation->find('first', array('conditions' =>array('Reservation.id'=>$reservation_id)));
            
           $body['aps']['alert']['reservation_date'] = isset($reservationData['Reservation']['start_date']) ? $reservationData['Reservation']['start_date'] : '';
            
        }elseif($notification_type == 'add_note' ){
            $customerHistoryData = $this->CustomerHistory->find('first',array('conditions'=> array( 'CustomerHistory.id'=>$reservation_id), 'fields' =>array('CustomerHistory.date','CustomerHistory.customer_id')));
             $body['aps']['alert']['date'] = ($customerHistoryData['CustomerHistory']['date'] != null) ? $customerHistoryData['CustomerHistory']['date'] : '';
             $body['aps']['alert']['customer_id'] = ($customerHistoryData['CustomerHistory']['customer_id'] != null) ? $customerHistoryData['CustomerHistory']['customer_id'] : '';
        }    



       
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
        }else{

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

            if($this->PushNotification->saveAll($pushNotificationData)){
                $push_notification_id = $this->PushNotification->id;
                $pushNotificationStatusData['NotificationRead']['employee_id'] =  $employee_id;
                $pushNotificationStatusData['NotificationRead']['push_notification_id'] =  $push_notification_id;
                $pushNotificationStatusData['NotificationRead']['status'] =  '0';
                $this->NotificationRead->saveAll($pushNotificationStatusData);

                $notificationCountData = $this->NotificationCount->find('first',array('conditions'=>array('NotificationCount.employee_id'=>$employee_id, 'NotificationCount.user_id'=>$user_id)));
                if(isset($notificationCountData['NotificationCount']['id']) && !empty($notificationCountData['NotificationCount']['id'])){
                    $this->NotificationCount->id = $notificationCountData['NotificationCount']['id'];
                    $count = ($notificationCountData['NotificationCount']['count']+ 1);
                    $this->NotificationCount->saveField('count' ,  $count);
                }else{
                    $notificationCount['NotificationCount']['user_id'] =  $user_id;
                    $notificationCount['NotificationCount']['employee_id'] =  $employee_id;
                    $notificationCount['NotificationCount']['count'] =  1;
                    $this->NotificationCount->saveAll($notificationCount);
                }


                $badgeCountData = $this->BadgeCount->find('first',array('conditions'=>array('BadgeCount.employee_id'=>$employee_id, 'BadgeCount.user_id'=>$user_id)));
                if(isset($badgeCountData['BadgeCount']['id']) && !empty($badgeCountData['BadgeCount']['id'])){
                    $this->BadgeCount->id = $badgeCountData['BadgeCount']['id'];
                    $countBadge = ($badgeCountData['BadgeCount']['count']+ 1);
                    $this->BadgeCount->saveField('count' ,  $countBadge);
                }else{
                    $badgeCount['BadgeCount']['user_id'] =  $user_id;
                    $badgeCount['BadgeCount']['employee_id'] =  $employee_id;
                    $badgeCount['BadgeCount']['count'] =  1;
                    $this->BadgeCount->saveAll($badgeCount);
                }


            }
            return 'Message successfully delivered' . PHP_EOL;
        }
    }


    public function androidPushNotification($deviceToken=null,$message=null,$user_id=null,$employee_id=null,$customer_id=null,  $reservation_id=null, $device_type=null, $member_type=null, $notification_type=null, $firebase_api_key){
        // API access key from Google API's Console
        // define( 'API_ACCESS_KEY', $firebase_api_key);
        $registrationIds =  array($deviceToken);

        /* Load Model */
        $this->loadModel('Employee');
        $this->loadModel('Reservation');
        $this->loadModel('CustomerHistory');
        $this->loadModel('PushNotification');
        $this->loadModel('NotificationRead');
        $this->loadModel('NotificationCount');
        $this->loadModel('BadgeCount');

        /* Start Notification and Badge Count Data*/
        $notificationCountData = $this->NotificationCount->find('first',array('conditions'=>array('NotificationCount.employee_id'=>$employee_id, 'NotificationCount.user_id'=>$user_id)));
        if(isset($notificationCountData['NotificationCount']['count']) && !empty($notificationCountData['NotificationCount']['count'])){
            $notification_count = $notificationCountData['NotificationCount']['count'];
        }else{
            $notification_count = 0;
        }    
        $badge_count = 0;
        $badgeCountData = $this->BadgeCount->find('first',array('conditions'=>array('BadgeCount.employee_id'=>$employee_id, 'BadgeCount.user_id'=>$user_id)));
        if(isset($badgeCountData['BadgeCount']['count']) && !empty($badgeCountData['BadgeCount']['count'])){
            $badge_count = $badgeCountData['BadgeCount']['count'];
        }
        /* End Notification and Badge Count Data*/
        // prep the bundle
        $msg = array
        (
            'message'   => $message,
            'title'     => 'JTSBoard',
            'reservation_id'  => $reservation_id,
            'notification_type' => $notification_type,
            'notification_count' => (string)($notification_count+1),
            'badge'  =>  $badge_count+1,
            'vibrate'   => 1,
            'sound'     => 1
        );

        if(($notification_type == 'add_reservation') || ($notification_type == 'reservation_status')){
            $reservationData = $this->Reservation->find('first', array('conditions' =>array('Reservation.id'=>$reservation_id)));
            
           $msg['reservation_date'] = isset($reservationData['Reservation']['start_date']) ? $reservationData['Reservation']['start_date'] : '';
            
        }elseif($notification_type == 'add_note' ){
            $customerHistoryData = $this->CustomerHistory->find('first',array('conditions'=> array( 'CustomerHistory.id'=>$reservation_id), 'fields' =>array('CustomerHistory.date','CustomerHistory.customer_id')));
             $msg['date'] = ($customerHistoryData['CustomerHistory']['date'] != null) ? $customerHistoryData['CustomerHistory']['date'] : '';
             $msg['customer_id'] = ($customerHistoryData['CustomerHistory']['customer_id'] != null) ? $customerHistoryData['CustomerHistory']['customer_id'] : '';
        }

        $fields = array
        (
            'registration_ids'  => $registrationIds,
            'data'          => $msg
        );
         
        $headers = array
        (
            'Authorization: key=' . Configure::read('App.Firebase.apikey'),
            'Content-Type: application/json'
        );
         
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        $result = json_decode($result);
        // pr($result );die;
        if($result->success == 1){
            
            $pushNotificationData = $pushNotificationStatusData = array();

            $user_id = $pushNotificationData['PushNotification']['user_id'] = ($user_id != null) ? $user_id : '';
            $employee_id = $pushNotificationData['PushNotification']['employee_id'] = ($employee_id != null) ? $employee_id : '';
            $pushNotificationData['PushNotification']['customer_id'] = ($customer_id != null) ? $customer_id : '';
            $pushNotificationData['PushNotification']['reservation_id'] = ($reservation_id != null) ? $reservation_id : '';
            $pushNotificationData['PushNotification']['device_type'] = ($device_type != null) ? $device_type : '';
            $pushNotificationData['PushNotification']['device_token'] = ($deviceToken != null) ? $deviceToken : '';
            $pushNotificationData['PushNotification']['member_type'] = ($member_type != null) ? $member_type : '';
            $pushNotificationData['PushNotification']['notification_type'] = ($notification_type != null) ? $notification_type : '';
            $pushNotificationData['PushNotification']['message'] = ($message != null) ? $message : '';
            $pushNotificationData['PushNotification']['status'] =  '0';

            if($this->PushNotification->saveAll($pushNotificationData)){
                $push_notification_id = $this->PushNotification->id;
                $pushNotificationStatusData['NotificationRead']['employee_id'] =  $employee_id;
                $pushNotificationStatusData['NotificationRead']['push_notification_id'] =  $push_notification_id;
                $pushNotificationStatusData['NotificationRead']['status'] =  '0';
                $this->NotificationRead->saveAll($pushNotificationStatusData);

                $notificationCountData = $this->NotificationCount->find('first',array('conditions'=>array('NotificationCount.employee_id'=>$employee_id, 'NotificationCount.user_id'=>$user_id)));
                if(isset($notificationCountData['NotificationCount']['id']) && !empty($notificationCountData['NotificationCount']['id'])){
                    $this->NotificationCount->id = $notificationCountData['NotificationCount']['id'];
                    $count = ($notificationCountData['NotificationCount']['count']+ 1);
                    $this->NotificationCount->saveField('count' ,  $count);
                }else{
                    $notificationCount['NotificationCount']['user_id'] =  $user_id;
                    $notificationCount['NotificationCount']['employee_id'] =  $employee_id;
                    $notificationCount['NotificationCount']['count'] =  1;
                    $this->NotificationCount->saveAll($notificationCount);
                }


                $badgeCountData = $this->BadgeCount->find('first',array('conditions'=>array('BadgeCount.employee_id'=>$employee_id, 'BadgeCount.user_id'=>$user_id)));
                if(isset($badgeCountData['BadgeCount']['id']) && !empty($badgeCountData['BadgeCount']['id'])){
                    $this->BadgeCount->id = $badgeCountData['BadgeCount']['id'];
                    $countBadge = ($badgeCountData['BadgeCount']['count']+ 1);
                    $this->BadgeCount->saveField('count' ,  $countBadge);
                }else{
                    $badgeCount['BadgeCount']['user_id'] =  $user_id;
                    $badgeCount['BadgeCount']['employee_id'] =  $employee_id;
                    $badgeCount['BadgeCount']['count'] =  1;
                    $this->BadgeCount->saveAll($badgeCount);
                }


            }
            return 'Message successfully delivered' . PHP_EOL;

        }else{
            return 'Message not delivered' . PHP_EOL;
            
        }
       
    }

    function delete_reservation($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('Reservation');
        $id = isset($decoded['reservation_id']) ? $decoded['reservation_id'] : '';
        if(!empty($id)){
            if($this->Reservation->delete($id, true)){
                $jsonEncode = 'successfully.';
            }else{
                $jsonEncode = 'not deleted';
            }
        }else{
           $jsonEncode = 'not found id.';
        }
        echo  $jsonEncode;exit();
       
    } 


    public function test() {
        // Check the action is being invoked by the cron dispatcher 
        // if (!defined('CRON_DISPATCHER')) { $this->redirect('/'); exit(); } 
        $msg = "corn job salon";


        // send email
        if(mail("mahen.zed123@gmail.com","My subject",$msg)){
            echo 'succss';
        }else{
            echo 'erroraa';
        }
        die();
    }


}
  
?>