<?php
/**
 * Calenders Controller
 *
 * PHP version 5.4
 *
 */
class CalendersController extends AppController {

	/**
	 * Customer Form Controller
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Calenders';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$user_id = $this->Auth->User('id');
	}


	function get_reservation($testData = null){
        
        $this->loadModel("User");
        $this->loadModel("Customer");
        $this->loadModel("Employee");
        $this->loadModel("ServiceColor");
        $this->loadModel("Reservation");
        $user_id = $this->Auth->User('id');
        $i =0;
        if(!empty($user_id)){
            $userData = $this->User->find('first',array('conditions'=>array( 'User.id'=>$user_id)));
            if(!$userData){
                $jsonEncode =  json_encode(array('status'=>'error', 'msg'=> 'User does not exist.'));
            }else{
                $this->Reservation->bindModel(array('belongsTo' => array('Customer', 'Service')));
                $reservationDataFind = $this->Reservation->find('all',array('conditions'=>array( 'Reservation.user_id'=>$user_id, 'Reservation.start_date <=' => date('Y-m-d'), 'Reservation.end_date >=' => date('Y-m-d')),  'order' => array('Reservation.start_date' => 'DESC', 'Reservation.start_time' => 'ASC')));
                $reservationData = array();
                // pr($reservationDataFind);die;
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
                        $reservationData['Reservation'][$key]['end_date']= $value['Reservation']['end_date'];
                        $reservationData['Reservation'][$key]['extra_start_date']= $value['Reservation']['extra_start_date'];
                        $reservationData['Reservation'][$key]['extra_end_date']= $value['Reservation']['extra_end_date'];
                        $reservationData['Reservation'][$key]['is_event']= $value['Reservation']['is_event'];
                        $reservationData['Reservation'][$key]['reservation_type']= $value['Reservation']['reservation_type'];
                            if(!empty($serviceColor['ServiceColor']['color_code'])){
                                $reservationData['Reservation'][$key]['service_color']= $serviceColor['ServiceColor']['color_code'];
                            }
                            $reservationData['Reservation'][$key]['customer_id']= $value['Customer']['id'];
                            
                            $reservationData['Reservation'][$key]['customer_name']= $value['Customer']['last_name'].' '.$value['Customer']['first_name'];
                            $reservationData['Reservation'][$key]['tel']= isset($value['Customer']['tel']) ? $value['Customer']['tel'] : '';
                            $reservationData['Reservation'][$key]['customer_status']= $value['Customer']['status'];
                            
                            if(!empty($allEmp)){
                                foreach ($allEmp as $empKey => $empValue) {
                                    $reservationData['Reservation'][$key]['Employee'][$empKey]['id']= $empValue['Employee']['id'];
                                    $reservationData['Reservation'][$key]['Employee'][$empKey]['name']= $empValue['Employee']['name'];
                                    $reservationData['Reservation'][$key]['Employee'][$empKey]['emp_code']= $empValue['Employee']['emp_code'];
                                    $reservationData['Reservation'][$key]['Employee'][$empKey]['image']= $empValue['Employee']['image'];
                                }
                            }

                        $last_visit =  isset($value['Customer']['last_visit']) ? $value['Customer']['last_visit'] : $value['Customer']['modified'];
                        if(empty($last_visit))
                            $last_visit =  isset($value['Reservation']['modified']) ? $value['Reservation']['modified'] : '';
                        $reservationData['Reservation'][$key]['last_visit']= date('Y-m-d', strtotime($last_visit));
                        $reservationData['Reservation'][$key]['ongoing']= $value['Reservation']['status'];
                        $reservationData['Reservation'][$key]['reservation_type']= $value['Reservation']['reservation_type'];
                        $reservationData['Reservation'][$key]['event_name']= $value['Reservation']['event_name'];
                        $reservationData['Reservation'][$key]['staff_name']= $value['Reservation']['staff_name'];
                        $reservationData['Reservation'][$key]['is_gmail']= $value['Reservation']['is_gmail'];

                    }


                }else{
                    $reservationData['Reservation'] = array();
                    
                }
                // $jsonEncode = json_encode($reservationData);
            }
        }else{
             $reservationData['Reservation'] = array();
            // $jsonEncode = json_encode($reservationData);
        }
        
        // echo "<pre>";
        // print_r($reservationData);die;
        $this->set(compact('reservationData'));
        $this->layout = 'dashboard';
    }

	
    /**
     * Date range
     *
     * @param $first
     * @param $last
     * @param string $step
     * @param string $format
     * @return array
     */
    function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
        $dates = [];
        $current = strtotime( $first );
        $last = strtotime( $last );

        while( $current <= $last ) {

            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }

        return $dates;
    }  


    function add_reservation($reservation_type_id = null){
        
        $this->loadModel("Customer");
        $this->loadModel("Reservation");
        $user_id = $this->Auth->User('id');
        
        if($this->request->is('post')){ 
        	// echo "<pre>";
        	// print_r($this->request->data);die;
        if(isset($this->request->data['id']) && !empty($this->request->data['id'])){
            $reservation['Reservation']['id'] = isset($this->request->data['id']) ? strtolower($this->request->data['id']) : '';
        }else{
            $reservation['Reservation']['reservation_number'] = $this->ReservationNumberString();
        }  
        $user_id = $reservation['Reservation']['user_id'] = $this->Auth->User('id');
        $reservation_type = $reservation['Reservation']['reservation_type'] = $this->request->data['Reservation']['reservation_type'];
        if(!empty($reservation_type) && ($reservation_type =='1')){
            $customer_id = $reservation['Reservation']['customer_id'] = isset($this->request->data['Reservation']['reservation_type']['customer_id']) ? $this->request->data['Reservation']['reservation_type']['customer_id'] : '';
            $customer_name = $reservation['Reservation']['customer_name'] = $this->request->data['Reservation']['customer_name'];
            if(empty($customer_id)){
                $customer_name_arr = explode(' ', $customer_name);
                if(isset($customer_name_arr[1])){
                    $customer_first_name = $customer_name_arr[1];
                    $customer_last_name = $customer_name_arr[0].' ';
                }else{
                    $customer_first_name = $customer_name_arr[0];
                    $customer_last_name = '';
                }
                $customerData['Customer']['user_id'] = $user_id;
                $customerData['Customer']['service_id'] = $this->request->data['Reservation']['service_id'];
                $customerData['Customer']['name'] = $customer_name;
                $customerData['Customer']['first_name'] = $customer_first_name;
                $customerData['Customer']['last_name'] = $customer_last_name;
                $customerData['Customer']['status'] =0;
                $this->Customer->saveAll($customerData); 
                $reservation['Reservation']['customer_id'] = $this->Customer->id; 
            }
            $service_id = $reservation['Reservation']['service_id'] = $this->request->data['Reservation']['service_id'];
            $customer_name = $reservation['Reservation']['customer_name'] = $this->request->data['Reservation']['customer_name'];
            $reservation['Reservation']['channel'] = $this->request->data['Reservation']['channel'];
            $reservation['Reservation']['payment_total'] = $this->request->data['Reservation']['price'];
        }elseif(!empty($reservation_type) && ($reservation_type =='2')){
            $reservation['Reservation']['event_name'] = isset($this->request->data['Reservation']['event_name']) ? $this->request->data['Reservation']['event_name'] : '';
        }elseif(!empty($reservation_type) && ($reservation_type =='3')){
            $reservation['Reservation']['staff_name'] = isset($this->request->data['Reservation']['staff_name']) ? $this->request->data['Reservation']['staff_name'] : '';
        }
        $employee_ids = '';
        if($this->request->data['Reservation']['employee_ids']){
            $employee_ids = implode(',', $this->request->data['Reservation']['employee_ids']);
        }        
        
        $reservation['Reservation']['employee_ids'] = $employee_ids;
        $reservation['Reservation']['all_day'] = $this->request->data['Reservation']['all_day'];
        $start_date = $this->request->data['Reservation']['start_date'];
        $reservation['Reservation']['start_date'] = date("Y-m-d H:i:s", strtotime($start_date));
        $end_date = $this->request->data['Reservation']['end_date'];
        $reservation['Reservation']['end_date'] = date("Y-m-d H:i:s", strtotime($end_date));
        // $extra_start_date = isset($this->request->data['extra_start_date']) ? $this->request->data['extra_start_date'] : '';
        // $reservation['Reservation']['extra_start_date'] = date("Y-m-d H:i:s", strtotime($extra_start_date));
        // $extra_end_date = isset($this->request->data['extra_end_date']) ? $this->request->data['extra_end_date'] : '';
        // $reservation['Reservation']['extra_end_date'] = date("Y-m-d H:i:s", strtotime($extra_end_date));
        $reservation['Reservation']['start_time'] = isset($this->request->data['Reservation']['start_time'])?$this->request->data['Reservation']['start_time']:'';
        $reservation['Reservation']['end_time'] = isset($this->request->data['Reservation']['end_time'])?$this->request->data['Reservation']['end_time']:'';
        $reservation['Reservation']['note'] = $this->request->data['Reservation']['note'];
        
        $reservation['Reservation']['status'] = Configure::read('App.Status.active');
        // print_r($reservation);die;
        
        if($this->Reservation->saveAll($reservation)){
            $reservation_id = $this->Reservation->id;
            $item = $reservation_id;
            $this->call_script_for_input_reservation_by_scraping($item); 
            $responseArr['reservation_id'] = $reservation_id;
            $responseArr['status'] = 'success';
           	$this->Session->setFlash(__('Information has been saved successfully'), 'flash_success');
            $this->redirect(array('action' => 'get_reservation'));
            // $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            // $jsonEncode = json_encode($responseArr);
        }
    }else{
	    	if($reservation_type_id == '1'){
	    		$this->loadModel('Service');
                // $user_id = $this->Auth->User('id');
        // print_r($user_id);die;
        $service_data = $this->Service->find('all', array('conditions' => array('Service.user_id' => $user_id)));
	    		// $service_data = $this->Service->find('all',array('fields'=> array('Service.id', 'Service.name')));
		        $service_list[0] = 'Select Service';
		        foreach ($service_data as $key => $value) {
		            $service_list[$value['Service']['id']] = $value['Service']['name'];
		        }
		        $this->loadModel('Employee');
		        $employee_list = $this->Employee->find('list',array('conditions'=>
                                                    array( 'Employee.user_id'=>$user_id, 'Employee.status'=>Configure::read('App.Status.active'), 'Employee.is_technician'=>Configure::read('App.Status.active')),
                                                    'order' => array('Employee.id' => 'DESC')
                                                    ));
		        $channel_list = Configure::read('App.Channel');
	    	}elseif($reservation_type_id == '2'){

	    		$this->loadModel('Employee');
		        $employee_list = $this->Employee->find('list',array('conditions'=>
                                                    array( 'Employee.user_id'=>$user_id, 'Employee.status'=>Configure::read('App.Status.active'), 'Employee.is_technician'=>Configure::read('App.Status.active')),
                                                    'order' => array('Employee.id' => 'DESC')
                                                    ));
	    	}elseif($reservation_type_id == '3'){

	    		$this->loadModel('Employee');
		        $employee_list = $this->Employee->find('list',array('conditions'=>
                                                    array( 'Employee.user_id'=>$user_id, 'Employee.status'=>Configure::read('App.Status.active'), 'Employee.is_technician'=>Configure::read('App.Status.active')),
                                                    'order' => array('Employee.id' => 'DESC')
                                                    ));
	    	}
    	}
        $this->set(compact('service_list'));
        $this->set(compact('employee_list'));
        $this->set(compact('channel_list'));
        $this->set('title_for_layout', __('Customers', true));
        $this->layout = 'dashboard';
    }


    function call_script_for_input_reservation_by_scraping($item = ''){
        $command = escapeshellcmd("python E:\\php\\htdocs\\jtsboard\\scraper\\input_scraper.py $item");
        $output = shell_exec($command);
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





    function customer_suggestion($keyword = null){
          
        // $data = file_get_contents('php://input');
    
        // if(empty($data)){
        //     $data = json_encode($_GET);
        // }    

        // $decoded = json_decode($data, true); 
        $this->loadModel("User");
        // $this->loadModel("Salon");
        $this->loadModel("Customer");
        $user_id = $this->Auth->User('id');        
        // $decoded = $this->request->data;
        // print_r($decoded);die;
        // $keyword = isset($decoded['keyword']) ? $decoded['keyword'] : '';
        $i=0;
        if(!empty($user_id) && $keyword != null ){

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

                // $customerData['Customer'][$i]['id'] = ;
                $customerData['Customer'][$value['Customer']['id']] = $value['Customer']['last_name']." ".$value['Customer']['first_name'];
                $customerData['Customer']['value'] = $value['Customer']['last_name']." ".$value['Customer']['first_name'];
               // $customerData['Customer'][$i] = $value['Customer'];
               // $customerData['Customer'][$i]['salon_name'] = $value['Salon']['salon_name'];
             //   $customerData['Customer'][$i]['service_name'] = $this->get_service_name($value['Customer']['service_id']);
                // $i++;
            }

            }else{
                $customerData['Customer'] = array();
               // $customerData[$i]['Customer']['msg1'] = 'No Record Found.';
               // $customerData[$i]['Customer']['status'] = 'error';
            }
            $jsonEncode = json_encode($customerData);
           
        }else{
            $customerData['Customer']['msg1'] = 'お客様は存在しません。';
            $customerData['Customer']['msg'] = 'Customer does not exist.';
            $customerData['Customer']['status'] = 'error';
            $jsonEncode = json_encode($customerData);
        }

        print_r($customerData);
        
    }


    function delete_reservation($id = null){
        $this->loadModel('Reservation');
        $id = isset($id) ? $id : '';
        $user_id = $this->Auth->User('id');
        if(!empty($id)){
            if($this->Reservation->delete($id, true)){
                // $responseArr = array('status' => 'success', 'msg' => 'Reservation deleted successfully.' );
                $this->Session->setFlash(__('Reservation deleted successfully'), 'flash_success');
                $this->redirect(array('action' => 'get_reservation'));
                // $jsonEncode = json_encode($responseArr);
            }else{
                // $responseArr = array('status' => 'error', 'msg' => 'Reservation deleted error.'  );
                $this->Session->setFlash(__('Reservation deleted error.'), 'flash_success');
                $this->redirect(array('action' => 'get_reservation'));
                // $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Reservation does not exist.'  );
            $this->Session->setFlash(__('Reservation does not exist.'), 'flash_success');
            $this->redirect(array('action' => 'get_reservation'));
            // $jsonEncode = json_encode($responseArr);
        }
        
    } 



    function edit_reservation($reservation_type_id = null, $id = null){
        
        $this->loadModel("Customer");
        $this->loadModel("Reservation");
        $user_id = $this->Auth->User('id');
        // echo "<pre>";
        // 	print_r($this->request->data);die;
        if(!empty($this->request->data)){ 

        	// echo "<pre>";
        	// print_r($this->request->data);die;
	        // if(isset($this->request->data['id']) && !empty($this->request->data['id'])){
	            $reservation['Reservation']['id'] = isset($id) ? $id : '';
	        // } 
	        $user_id = $reservation['Reservation']['user_id'] = $this->Auth->User('id');
	        $reservation_type = $reservation['Reservation']['reservation_type'] = $this->request->data['Reservation']['reservation_type'];
        	if(!empty($reservation_type) && ($reservation_type =='1')){
	            // $customer_id = $reservation['Reservation']['customer_id'] = isset($this->request->data['Reservation']['reservation_type']['customer_id']) ? $this->request->data['Reservation']['reservation_type']['customer_id'] : '';
	            // $customer_name = $reservation['Reservation']['customer_name'] = $this->request->data['Reservation']['customer_name'];
            	// if(empty($customer_id)){
             //    	$customer_name_arr = explode(' ', $customer_name);
	            //     if(isset($customer_name_arr[1])){
	            //         $customer_first_name = $customer_name_arr[1];
	            //         $customer_last_name = $customer_name_arr[0].' ';
	            //     }else{
	            //         $customer_first_name = $customer_name_arr[0];
	            //         $customer_last_name = '';
	            //     }
             //        $customerData['Customer']['user_id'] = $user_id;
	            //     $customerData['Customer']['user_id'] = $user_id;
	            //     $customerData['Customer']['service_id'] = $this->request->data['Reservation']['service_id'];
	            //     $customerData['Customer']['name'] = $customer_name;
	            //     $customerData['Customer']['first_name'] = $customer_first_name;
	            //     $customerData['Customer']['last_name'] = $customer_last_name;
	            //     $customerData['Customer']['status'] =0;
	            //     $this->Customer->saveAll($customerData); 
	            //     $reservation['Reservation']['customer_id'] = $this->Customer->id; 
            	// }
	            $service_id = $reservation['Reservation']['service_id'] = $this->request->data['Reservation']['service_id'];
	            // $customer_name = $reservation['Reservation']['customer_name'] = $this->request->data['Reservation']['customer_name'];
	            $reservation['Reservation']['channel'] = $this->request->data['Reservation']['channel'];
	            $reservation['Reservation']['payment_total'] = $this->request->data['Reservation']['price'];
        	}elseif(!empty($reservation_type) && ($reservation_type =='2')){
            	$reservation['Reservation']['event_name'] = isset($this->request->data['Reservation']['event_name']) ? $this->request->data['Reservation']['event_name'] : '';
        	}elseif(!empty($reservation_type) && ($reservation_type =='3')){
            	$reservation['Reservation']['staff_name'] = isset($this->request->data['Reservation']['staff_name']) ? $this->request->data['Reservation']['staff_name'] : '';
        	}

            $employee_ids = '';
            if($this->request->data['Reservation']['employee_ids'] != ''){
                $employee_ids = implode(',', $this->request->data['Reservation']['employee_ids']);
                $reservation['Reservation']['employee_ids'] = $employee_ids;
            }	        
	        
	        $reservation['Reservation']['all_day'] = $this->request->data['Reservation']['all_day'];
	        $start_date = $this->request->data['Reservation']['start_date'];
	        $reservation['Reservation']['start_date'] = date("Y-m-d H:i:s", strtotime($start_date));
	        $end_date = $this->request->data['Reservation']['end_date'];
	        $reservation['Reservation']['end_date'] = date("Y-m-d H:i:s", strtotime($end_date));
	        // $extra_start_date = isset($this->request->data['extra_start_date']) ? $this->request->data['extra_start_date'] : '';
	        // $reservation['Reservation']['extra_start_date'] = date("Y-m-d H:i:s", strtotime($extra_start_date));
	        // $extra_end_date = isset($this->request->data['extra_end_date']) ? $this->request->data['extra_end_date'] : '';
	        // $reservation['Reservation']['extra_end_date'] = date("Y-m-d H:i:s", strtotime($extra_end_date));
	        $reservation['Reservation']['start_time'] = isset($this->request->data['Reservation']['start_time'])?$this->request->data['Reservation']['start_time']:'';
	        $reservation['Reservation']['end_time'] = isset($this->request->data['Reservation']['end_time'])?$this->request->data['Reservation']['end_time']:'';
	        $reservation['Reservation']['note'] = $this->request->data['Reservation']['note'];
	        
	        $reservation['Reservation']['status'] = Configure::read('App.Status.active');
	        // print_r($reservation);die;
        
        	if($this->Reservation->saveAll($reservation)){
	            $reservation_id = $this->Reservation->id; 
	            $responseArr['reservation_id'] = $reservation_id;
	            $responseArr['status'] = 'success';
	           	$this->Session->setFlash(__('Information has been updated successfully'), 'flash_success');
	            $this->redirect(array('action' => 'get_reservation'));
	            // $jsonEncode = json_encode($responseArr);
            
        	}else{
            	$responseArr = array('status' => 'error' );
            	// $jsonEncode = json_encode($responseArr);
        	}
    	}else{
	    	if($reservation_type_id == '1'){
	    		$this->loadModel('Service');
	    		$service_data = $this->Service->find('all',array('fields'=> array('Service.id', 'Service.name')));
		        $service_list[0] = 'Select Service';
		        foreach ($service_data as $key => $value) {
		            $service_list[$value['Service']['id']] = $value['Service']['name'];
		        }
		        $this->loadModel('Employee');
		        $employee_list = $this->Employee->find('list',array('conditions'=>
                                                    array( 'Employee.user_id'=>$user_id, 'Employee.status'=>Configure::read('App.Status.active'), 'Employee.is_technician'=>Configure::read('App.Status.active')),
                                                    'order' => array('Employee.id' => 'DESC')
                                                    ));
		        $channel_list = Configure::read('App.Channel');
	    	}elseif($reservation_type_id == '2'){

	    		$this->loadModel('Employee');
		        $employee_list = $this->Employee->find('list',array('conditions'=>
                                                    array( 'Employee.user_id'=>$user_id, 'Employee.status'=>Configure::read('App.Status.active'), 'Employee.is_technician'=>Configure::read('App.Status.active')),
                                                    'order' => array('Employee.id' => 'DESC')
                                                    ));
	    	}elseif($reservation_type_id == '3'){

	    		$this->loadModel('Employee');
		        $employee_list = $this->Employee->find('list',array('conditions'=>
                                                    array( 'Employee.user_id'=>$user_id, 'Employee.status'=>Configure::read('App.Status.active'), 'Employee.is_technician'=>Configure::read('App.Status.active')),
                                                    'order' => array('Employee.id' => 'DESC')
                                                    ));
	    	}

	    	$this->Reservation->bindModel(array('belongsTo'=>array('Customer')));
	    	$this->request->data = $this->Reservation->read(null, $id);
	    	// $this->request->data['Customer'] = $this->Customer->read(null,$this->request->data['Reservation']['customer_id']);
	    	// print_r($this->request->data);die;
    	}
        $this->set(compact('service_list'));
        $this->set(compact('employee_list'));
        $this->set(compact('channel_list'));
        $this->set('title_for_layout', __('Customers', true));
        $this->layout = 'dashboard';
    }


    function add_reservation_status(){
        
        // print_r($this->request->data);die;
        $this->loadModel("Reservation");
        
        $decoded = $this->request->data; 
        // pr($decoded);die;
        $responseArr = array();
        
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

}