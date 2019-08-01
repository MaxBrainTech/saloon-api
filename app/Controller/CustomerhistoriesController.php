<?php



/**

 * CustomerHistories Controller

 *

 * PHP version 5.4

 *

 */

class CustomerHistoriesController extends AppController{



    /**

     * CustomerHistories Controller

     *

     * @var string

     * @access public

     */

    public $name = 'CustomerHistories';

    public $components = array(

         'Upload'

		//, 'Twitter'

    );

	

    public $helpers = array('General','Autosearch','Js');

    public $uses = array('CustomerHistories');



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

    }



    public function beforeFilter(){

    	

        parent::beforeFilter();

        $this->loadModel('CustomerHistories');

        $this->loadModel('Employees');

        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

            $this->Auth->allow();

            $user_id = $this->Session->read('employee.Employee.user_id');

        



        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            



            $this->Auth->allow('edit', 'start_access', 'reset_password', 'thankx', 'reset_password_change','login', 'subscription', 'email_confirm', 'register', 'activate', 'success', 'fbconnect', 'forgot_password','get_password', 'password_changed', 'linked_connect', 'save_linkedin_data', 'tw_connect', 'tw_response', 'glogin', 'save_google_info','social_login', 'tlogin', 'save_cover_photo', 'getTwitterData', 'fb_data', 'fb_logout', 'social_join_mail', 'home', 'checkunique', 'checklogin', 'test_mail', 'get_affilates', 'test_mail','service_list','registration','payment_info','priceChangeInt');

            $user_id = $this->Auth->User("id");

        }

		

        $this->RequestHandler->addInputType('json', array('json_decode', true));

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



    function get_customer_analysis_dates($id = null){



        $this->loadModel('CustomerHistory');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

            $employee_id = ($this->Session->read('employee.Employee.id') != null) ? $this->Session->read('employee.Employee.id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $emp_code = ($this->Auth->User('emp_code') != null) ? $this->Auth->User("emp_code") : '';

            if(($emp_code !=null) && !empty($emp_code)){



            }else{

                $emp_name = ($this->Auth->User('name') != null) ? $this->Auth->User("name") : '';

                $emp_image = ($this->Auth->User('image') != null) ? $this->Auth->User("image") : '';

            }

            $this->layout = "dashboard";

        }

        

        $customer_id = isset($id) ? $id : '';

        $responseArr  = array();

        if(!empty($user_id) && !empty($customer_id)){

            $customerAnalysisData=$this->CustomerHistory->find('all', array('conditions'=>array('CustomerHistory.user_id'=>$user_id, 'CustomerHistory.customer_id'=>$customer_id),'order' => array('CustomerHistory.date' => 'DESC')));



            foreach ($customerAnalysisData as $key => $value) {

                $responseArr[$key]['id'] = $value['CustomerHistory']['id'];

                $responseArr[$key]['date'] = $value['CustomerHistory']['date'];

            }

         

        }else{

            $this->Session->setFlash(__('The Note could not be saved. Please, try again.', true), 'admin_flash_error');

        }



        $this->set(compact('responseArr','customer_id'));        



    } 



    public function add_note($id = null){

        $this->loadModel('CustomerHistory');

        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $this->layout = "dashboard";

        }

        $date = date('Y-m-d');

        // pr($id);
        // pr($date);

        $todayData['CustomerHistory']['date'] = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.date'=>$date,'CustomerHistory.user_id'=>$user_id)));

        // pr($todayData['CustomerHistory']['date']);die;
        if(isset($todayData['CustomerHistory']['date']) && !empty($todayData['CustomerHistory']['date'])){

            $this->Session->setFlash(__('Today Date is Already Exist.', true), 'admin_flash_error');
            $this->redirect(array('action' => 'get_customer_analysis_dates',$id));

        }else{

            $CustomerHistory['user_id'] = $user_id;
            $CustomerHistory['customer_id'] = $id;
            $CustomerHistory['date'] = $date;

            $this->CustomerHistory->saveAll($CustomerHistory);
            $this->Session->setFlash(__('Today Date is successfully.', true), 'admin_flash_error');
            $this->redirect(array('action' => 'get_customer_analysis_dates',$id));

        }



        // pr($todayData);die;



        

    }





    public function get_note($id = null){



        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteService');

        $this->loadModel('NoteProduct');

        $this->loadModel('NoteTicket');

        $this->loadModel('NoteImage');

        $this->loadModel('Product');

        $this->loadModel('User');

        $this->loadModel('Employee');

        $this->loadModel('NoteRemainingTicket');

        $this->loadModel('Service');

        $this->loadModel('Ticket');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $this->layout = "dashboard";

        }







        $id = isset($id) ? $id: '';



        $customerData = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.user_id'=>$user_id,'CustomerHistory.id' => $id)));



        $customer_id = isset($customerData['CustomerHistory']['customer_id']) ? $customerData['CustomerHistory']['customer_id'] : '';

        $date = isset($customerData['CustomerHistory']['date']) ? $customerData['CustomerHistory']['date'] : '';       

        

        

        $responseArr  = array();



        if(!empty($id)){

       

            $this->CustomerHistory->bindModel(array('hasMany' => array('NoteService', 'NoteProduct', 'NoteTicket', 'NoteImage'=> array(

            'className' => 'NoteImage',

            'conditions' => array('NoteImage.delete_image_status' => '0'),

            'dependent' => true

        ))));

            $customerAnalysisData=$this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$id)));



            $note_text = isset($customerAnalysisData['CustomerHistory']['note_text'])?$customerAnalysisData['CustomerHistory']['note_text']:'';

            $customerAnalysisData['CustomerHistory'] =array();

            $totalNoteTicketAmount = 0;

            $ticketName = '';

            if(!empty($user_id) && !empty($customer_id)){

                $noteTicketData=$this->NoteTicket->find('all', array('conditions'=>array('NoteTicket.user_id'=>$user_id, 'NoteTicket.customer_id'=>$customer_id)));

            // pr($noteTicketData);die;

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

            $grandTotalPrice = ($serviceTotalPrice + $productTotalPrice);



            $customerAnalysisData['CustomerHistory']['service_total_price'] =number_format($serviceTotalPrice).'円';

            $customerAnalysisData['CustomerHistory']['product_total_price'] =number_format($productTotalPrice).'円';

            $customerAnalysisData['CustomerHistory']['grand_total_price'] =number_format($grandTotalPrice).'円';

            $jsonEncode = json_encode($customerAnalysisData);

       

        }else{



            $this->Session->setFlash(__('The Note could not be saved. Please, try again.', true), 'admin_flash_error');

        }

        

        // pr($user_id);die;



        $servicesList = $this->Service->find('list', array('conditions'=>array('Service.user_id'=>$user_id)));



        $employeeList = $this->Employee->find('list',array('conditions'=>

                        array( 'Employee.user_id'=>$user_id, 'Employee.status'=>Configure::read('App.Status.active'), 'Employee.is_technician'=>Configure::read('App.Status.active')),

                        'order' => array('Employee.id' => 'DESC')

                        ));

        

        $noteTicketDataService=$this->NoteTicket->find('list', array('conditions'=>array('NoteTicket.user_id'=>$user_id, 'NoteTicket.customer_id'=>$customer_id), "fields" =>array('NoteTicket.ticket_name')));

        $noteTicketDataService['カード'] ='カード';

        $noteTicketDataService['現金'] ='現金';

        ksort($noteTicketDataService);

        // pr($noteTicketData);die;







        $productList = $this->Product->find('list', array('conditions'=>array('Product.user_id'=>$user_id),"fields" =>array('Product.product_name')));

        $productList['0'] ='Select Product';

        ksort($productList);



        $ticketList = $this->Ticket->find('list', array('conditions'=>array('Ticket.user_id'=>$user_id),"fields" =>array('Ticket.ticket_name'))); 

        $ticketList['0'] ='Select Ticket';

        ksort($ticketList);



        $noteTicketListTicket ['カード'] ='カード';

        $noteTicketListTicket ['現金'] ='現金';

        // pr($noteTicketListTicket);die;



        $productStock = $this->Product->find('first', array('conditions'=>array('Product.user_id'=>$user_id),"fields" =>array('Product.product_stock')));

        // pr($productStock);die;



        $noteTicketDataProduct=$this->NoteTicket->find('all', array('conditions'=>array('NoteTicket.user_id'=>$user_id, 'NoteTicket.customer_id'=>$customer_id)));

        // pr($noteTicketDataProduct);die;

        if(isset($noteTicketDataProduct[0]) && !empty($noteTicketDataProduct[0])){

                foreach ($noteTicketDataProduct as $noteTicketKey => $noteTicketValue) {

                    

                        if(empty($noteTicketValue['NoteTicket']['ticket_num_time']) && ($noteTicketValue['NoteTicket']['ticket_num_time']=='0') && ($noteTicketValue['NoteTicket']['ticket_price']!='0')){

                            $noteTicketListProduct[$noteTicketValue['NoteTicket']['id']] = $noteTicketValue['NoteTicket']['ticket_name'];

                            

                        }   

                }

            }



            // pr($noteTicketListProduct);die;



        $this->set(compact('customerAnalysisData','servicesList','employeeList','noteTicketDataService','productList','noteTicketListProduct','ticketList','noteTicketListTicket'));

        // $this->layout = "dashboard";

    }







    public function add_note_text(){



        $this->loadModel('Customer');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteImage');

        $this->loadModel('User');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

            $employee_id = ($this->Session->read('employee.Employee.id') != null) ? $this->Session->read('employee.Employee.id') : '';

            $emp_name = ($this->Session->read('employee.Employee.name') != null) ? $this->Session->read('employee.Employee.name') : '';

            $emp_image = ($this->Session->read('employee.Employee.image') != null) ? $this->Session->read('employee.Employee.image') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $emp_code = ($this->Auth->User('user_emp_code') != null) ? $this->Auth->User("user_emp_code") : '';

            if(($emp_code !=null) && !empty($emp_code)){

               $empData =  $this->Employee->find("first", array("conditions"=>array("Employee.emp_code"=>$emp_code)));

               if(($empData["Employee"]["id"] != null) && isset($empData["Employee"]["id"])){

                     $employee_id = isset($empData["Employee"]["id"]) ? $empData["Employee"]["id"] : '';

                     $emp_name = isset($empData["Employee"]["name"]) ? $empData["Employee"]["name"] : '';

                     $emp_image = isset($empData["Employee"]["image"]) ? $empData["Employee"]["image"] : '';

               }



            }else{

                $employee_id = "";

                $emp_name = ($this->Auth->User('name') != null) ? $this->Auth->User("name") : '';

                $emp_image = ($this->Auth->User('image') != null) ? $this->Auth->User("image") : '';

            }

            $this->layout = "dashboard";

        }



        $customerHistory = array();

        $note_text = isset($this->request->data['CustomerHistory']['note_text']) ? $this->request->data['CustomerHistory']['note_text'] : '';



        $CustomerHistory_id = isset($this->request->data['CustomerHistory']['customer_id']) ? $this->request->data['CustomerHistory']['customer_id'] : '';



        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$CustomerHistory_id, )));

        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';

      

        if(isset($note_text) && !empty($note_text)){

            

            $noteImages['NoteImage']['user_id'] = $user_id;

            $noteImages['NoteImage']['customer_id'] = isset($customer_id) ? $customer_id : '';

            $emp_id = $noteImages['NoteImage']['employee_id'] = $employee_id;

            $noteImages['NoteImage']['employee_name'] = $emp_name;

            $noteImages['NoteImage']['employee_image'] = $emp_image;

            $noteImages['NoteImage']['customer_history_id'] = isset($CustomerHistory_id) ? $CustomerHistory_id : '';

            $noteImages['NoteImage']['note_text'] = $note_text;

            $noteImages['NoteImage']['note_type'] = '2';

            // pr($noteImages);die;

            if($this->NoteImage->saveAll($noteImages)){

                 $note_image_id = $this->NoteImage->id; 



                /********Customer Modified date update ********/

                $customerData['Customer']['id'] = $customer_id;

                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');

                // pr($customerData);die;

                $this->Customer->saveAll($customerData);

                $responseArr = array('note_image_id' => $note_image_id, 'msg'=>'顧客情報が正常に追加されました。',  'msg1'=>'Comment successfully added.', 'status' => 'success' );

                $this->Session->setFlash(__('Comment saved.', true), 'admin_flash_error');

                $this->redirect(array('action' => 'get_note',$CustomerHistory_id));



            }else{

                $this->Session->setFlash(__('Comment could not be saved. Please, try again.', true), 'admin_flash_error');

                $this->redirect(array('action' => 'get_note',$CustomerHistory_id));

            } 

        }

    }



    public function add_note_image(){    



        $this->loadModel('Customer');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteImage');

        $this->loadModel('User');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

            $employee_id = ($this->Session->read('employee.Employee.id') != null) ? $this->Session->read('employee.Employee.id') : '';

            $emp_name = ($this->Session->read('employee.Employee.name') != null) ? $this->Session->read('employee.Employee.name') : '';

            $emp_image = ($this->Session->read('employee.Employee.image') != null) ? $this->Session->read('employee.Employee.image') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $emp_code = ($this->Auth->User('user_emp_code') != null) ? $this->Auth->User("user_emp_code") : '';

            if(($emp_code !=null) && !empty($emp_code)){

               $empData =  $this->Employee->find("first", array("conditions"=>array("Employee.emp_code"=>$emp_code)));

               if(($empData["Employee"]["id"] != null) && isset($empData["Employee"]["id"])){

                     $employee_id = isset($empData["Employee"]["id"]) ? $empData["Employee"]["id"] : '';

                     $emp_name = isset($empData["Employee"]["name"]) ? $empData["Employee"]["name"] : '';

                     $emp_image = isset($empData["Employee"]["image"]) ? $empData["Employee"]["image"] : '';

               }



            }else{

                $employee_id = "";

                $emp_name = ($this->Auth->User('name') != null) ? $this->Auth->User("name") : '';

                $emp_image = ($this->Auth->User('image') != null) ? $this->Auth->User("image") : '';

            }

            $this->layout = "dashboard";

        }



        $customerHistory = array();



        $CustomerHistory_id = isset($this->request->data['CustomerHistory']['customer_id']) ? $this->request->data['CustomerHistory']['customer_id'] : '';



        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$CustomerHistory_id, )));

        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';



        $note_image = isset($this->request->data['CustomerHistory']['note_image'])?$this->request->data['CustomerHistory']['note_image']:'';



        if(isset($note_image) && !empty($note_image)){

                   

            $path_info = pathinfo($note_image['name']);



            $newName = md5(time()*rand()).'.'.$path_info['extension'];



            $thumbRules = array('size' => array(NOTE_THUMB_WIDTH, NOTE_THUMB_HEIGHT), 'type' => 'resizecrop');

            $thumb = $this->Upload->upload($note_image, WWW_ROOT . NOTE_IMG_THUMB_DIR, $newName, $thumbRules);

            /* medium */

             $mediumRules = array('size' => array(NOTE_MEDIUM_WIDTH, NOTE_MEDIUM_HEIGHT), 'type' => 'resizecrop');

            $medium = $this->Upload->upload($note_image, WWW_ROOT . NOTE_IMG_MEDIUM_DIR, $newName, $mediumRules);



            $verticalRules = array('size' => array(NOTE_VERTICAL_WIDTH, NOTE_VERTICAL_HEIGHT), 'type' => 'resizecrop');

            $vertical = $this->Upload->upload($note_image, WWW_ROOT . NOTE_IMG_VERTICAL_DIR, $newName, $verticalRules);

            

            $res3 = $this->Upload->upload($note_image, WWW_ROOT . NOTE_IMG_ORIGINAL_DIR, $newName, '', array('png', 'jpg', 'jpeg', 'gif'));

            

            if (!empty($this->Upload->result)){



                $noteImages['NoteImage']['user_id'] = $user_id;

                $noteImages['NoteImage']['customer_id'] = isset($customer_id) ? $customer_id : '';

                $emp_id = $noteImages['NoteImage']['employee_id'] = $employee_id;

                $noteImages['NoteImage']['employee_name'] = $emp_name;

                $noteImages['NoteImage']['employee_image'] = $emp_image;

                $noteImages['NoteImage']['customer_history_id'] = isset($CustomerHistory_id) ? $CustomerHistory_id : '';

                $noteImages['NoteImage']['image'] = $this->Upload->result;

                $noteImages['NoteImage']['note_type'] = '1';

                // pr($noteImages);die;

                if($this->NoteImage->saveAll($noteImages)){

                     $note_image_id = $this->NoteImage->id; 



                    /********Customer Modified date update ********/

                    $customerData['Customer']['id'] = $customer_id;

                    $customerData['Customer']['modified'] = date('Y-m-d H:i:s');

                    // pr($customerData);die;

                    $this->Customer->saveAll($customerData);

                    $responseArr = array('note_image_id' => $note_image_id, 'msg'=>'顧客情報が正常に追加されました。',  'msg1'=>'Comment successfully added.', 'status' => 'success' );

                    $this->Session->setFlash(__('Image Upload successfully.', true), 'admin_flash_error');

                    $this->redirect(array('action' => 'get_note',$CustomerHistory_id));



                }else{

                    $this->Session->setFlash(__('Image could not be Upload. Please, try again.', true), 'admin_flash_error');

                    $this->redirect(array('action' => 'get_note',$CustomerHistory_id));

                }

            }else{

                $this->Session->setFlash(__('Image could not be Upload. Please, try again.', true), 'admin_flash_error');

                    $this->redirect(array('action' => 'get_note',$CustomerHistory_id));

            }

        }

    }



    function add_note_service(){

        

        // pr($this->request->data);



        

        $this->loadModel('Customer');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteTicket');

        $this->loadModel('NoteService');

        $this->loadModel('NoteRemainingTicket');

        $this->loadModel('Employee');

        $this->loadModel('Service');

        $this->loadModel('Ticket');





        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';



        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $emp_code = ($this->Auth->User('user_emp_code') != null) ? $this->Auth->User("user_emp_code") : '';

            if(($emp_code !=null) && !empty($emp_code)){

               $empData =  $this->Employee->find("first", array("conditions"=>array("Employee.emp_code"=>$emp_code)));

               if(($empData["Employee"]["id"] != null) && isset($empData["Employee"]["id"])){



               }



            }else{



            }

            $this->layout = "dashboard";

        }



        $customer_history_id = isset($this->request->data['NoteService']['customer_history_id']) ? $this->request->data['NoteService']['customer_history_id'] : '';

        $service_id = isset($this->request->data['NoteService']['service_id']) ? $this->request->data['NoteService']['service_id'] : '';

        $employee_id = isset($this->request->data['NoteService']['employee_id']) ? $this->request->data['NoteService']['employee_id'] : '0';

        $service_price = isset($this->request->data['NoteService']['service_price']) ? $this->request->data['NoteService']['service_price']."円" : '';

        $ticket_id = isset($this->request->data['NoteService']['ticket_id']) ? $this->request->data['NoteService']['ticket_id'] : '0';

        

        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$customer_history_id, )));



        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';

        $service_name = ($this->Service->getServiceName($service_id) !=null)?$this->Service->getServiceName($service_id):'';

        $employee_name = ($this->Employee->getEmployeeName($employee_id) !=null)?$this->Employee->getEmployeeName($employee_id):'';



        // pr(($ticket_id));die;



        if(is_numeric($ticket_id)){

            $ticket_name = $this->NoteTicket->getNoteTicketName($ticket_id);

        }else{

            $ticket_name = $ticket_id;

        }

        // pr($ticket_name);die;

      

        $noteService['NoteService']['user_id'] = $user_id;



        $noteService['NoteService']['customer_id'] = $customer_id;



        $noteService['NoteService']['customer_history_id'] = $customer_history_id;



        $noteService['NoteService']['customer_service_id'] = '0';



        $noteService['NoteService']['employee_id'] = $employee_id;



        $noteService['NoteService']['ticket_id'] = (is_numeric($ticket_id)?$ticket_id:'0');



        $noteService['NoteService']['employee_name'] = $employee_name;



        $noteService['NoteService']['service_price'] = $service_price;



        $noteService['NoteService']['payment_type'] = $ticket_name ? $ticket_name : $ticket_name;



        $service_id = $noteService['NoteService']['service_id'] = $service_id;



        $noteService['NoteService']['service_name'] = $service_name;



        $noteService['NoteService']['status'] = Configure::read('App.Status.active');



        if($this->NoteService->saveAll($noteService)){



            if(!empty(is_numeric($ticket_id))){

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 

                if(($noteTicketData['NoteTicket']['ticket_num_time'] !=0) && ($noteTicketData['NoteTicket']['ticket_num_time'] > 0)){

                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];

                    $ticket_num_time = ($noteTicketData['NoteTicket']['ticket_num_time'] - 1);

                    $this->NoteTicket->saveField('ticket_num_time' , $ticket_num_time );

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = (is_numeric($ticket_id)?$ticket_id:'0');

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

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = (is_numeric($ticket_id)?$ticket_id:'0');

                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';

                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);



                }

            }



            $note_service_id = $this->NoteService->id;

            $customerData['Customer']['id'] = $customer_id;



            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');

            $this->Customer->saveAll($customerData);



            

            $this->Session->setFlash(__('Service Saved successfully.', true), 'admin_flash_error');

            $this->redirect(array('action' => 'get_note',$customer_history_id));

    

        }else{

           $this->Session->setFlash(__('Service could not be Saved. Please, try again.', true), 'admin_flash_error');

                    $this->redirect(array('action' => 'get_note',$customer_history_id));

        }   

        

        $this->Session->setFlash(__('Service could not be Saved. Please, try again.', true), 'admin_flash_error');

        $this->redirect(array('action' => 'get_note',$customer_history_id));



    }



    function delete_note_service($id = null){



        $this->loadModel('NoteService');

        $this->loadModel('NoteTicket');



        $customer_history_data = $this->NoteService->find('first', array('conditions'=>array('NoteService.id'=>$id)));

        // pr($customer_history_data);die;



        $id = isset($id) ? $id : '';





        if(!empty($id)){

            $noteServiceData=$this->NoteService->find('first', array('conditions'=>array('NoteService.id'=>$id)));

            if($this->NoteService->delete($id, true)){



               $noteOldTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$noteServiceData['NoteService']['ticket_id'])));

               if(isset($noteOldTicketData) && !empty($noteOldTicketData)){

                if(isset($noteOldTicketData['NoteTicket']['service_id']) && ($noteOldTicketData['NoteTicket']['service_id'] != 0)){

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

            }

                $this->Session->setFlash(__('Note Service deleted successfully.', true), 'admin_flash_error');

                $this->redirect(array('action' => 'get_note',$customer_history_data['NoteService']['customer_history_id']));

            }else{

                $this->Session->setFlash(__('Note Service deleted error.', true), 'admin_flash_error');

                $this->redirect(array('action' => 'get_note',$customer_history_data['NoteService']['customer_history_id']));

            }

        }else{

            $this->Session->setFlash(__('Note Service does not exist.', true), 'admin_flash_error');

            $this->redirect(array('action' => 'get_note',$customer_history_data['NoteService']['customer_history_id']));

        }

        $this->redirect(array('action' => 'get_note',$customer_history_data['NoteService']['customer_history_id']));

       

    } 



    public function get_product_stock(){

        $this->request->data['id'];

        $this->loadModel('Product');

        $this->loadModel('User');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

            // $employee_id = ($this->Session->read('employee.Employee.id') != null) ? $this->Session->read('employee.Employee.id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            // $emp_code = ($this->Auth->User('emp_code') != null) ? $this->Auth->User("emp_code") : '';

            // if(($emp_code !=null) && !empty($emp_code)){



            // }else{

            //     // $emp_name = ($this->Auth->User('name') != null) ? $this->Auth->User("name") : '';

            //     // $emp_image = ($this->Auth->User('image') != null) ? $this->Auth->User("image") : '';

            // }

            $this->layout = "dashboard";

        }

        $productStock = $this->Product->find('first', array('conditions'=>array('Product.user_id'=>$user_id,'Product.id'=>$this->request->data['id']),"fields" =>array('Product.product_stock')));

         // echo $productStock['Product']['product_stock'];

        // $str = '<option value=0>Select Value</option>';



         // for($i=1; $i<=$productStock['Product']['product_stock'];$i++){

         //    // echo $i;

         //    $str = $str."<option value=".$i.">".$i."</option>";

         // }

        echo $productStock['Product']['product_stock'];

        }





    function add_note_product(){



        // pr($this->request->data);die;

        

        $this->loadModel('Customer');

        $this->loadModel('NoteProduct');

        $this->loadModel('NoteTicket');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteRemainingTicket');

        $this->loadModel('Product');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

           

            $this->layout = "dashboard";

        }



        $customer_history_id = isset($this->request->data['customer_history_id']) ? $this->request->data['customer_history_id'] : '';



        $employee_id = isset($this->request->data['employee_id']) ? $this->request->data['employee_id'] : '0';

        $sale_price = isset($this->request->data['sale_price']) ? $this->request->data['sale_price']."円" : '';

        $ticket_id = isset($this->request->data['ticket_id']) ? $this->request->data['ticket_id'] : '0';

        $product_id = isset($this->request->data['product_id']) ? $this->request->data['product_id'] : '0';

        $product_quantity = isset($this->request->data['product_quantity']) ? $this->request->data['product_quantity'] : '';

        

        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$customer_history_id, )));



        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';

        $product_name = ($this->Product->getProductName($product_id) !=null)?$this->Product->getProductName($product_id):'';

        $employee_name = ($this->Employee->getEmployeeName($employee_id) !=null)?$this->Employee->getEmployeeName($employee_id):'';





        if(is_numeric($ticket_id)){

            $ticket_name = $this->NoteTicket->getNoteTicketName($ticket_id);

        }else{

            $ticket_name = $ticket_id;

        }





        $noteProduct['NoteProduct']['user_id'] = $user_id;



        $noteProduct['NoteProduct']['customer_id'] = $customer_id;



        $noteProduct['NoteProduct']['ticket_id'] = $ticket_id;



        $noteProduct['NoteProduct']['customer_history_id'] = $customer_history_id;



        $noteProduct['NoteProduct']['product_id'] = $product_id;



        $noteProduct['NoteProduct']['product_name'] = $product_name;



        $noteProduct['NoteProduct']['employee_id'] = $employee_id;



        $noteProduct['NoteProduct']['employee_name'] = $employee_name;



        $noteProduct['NoteProduct']['sale_price'] = $sale_price;



        $noteProduct['NoteProduct']['product_quantity'] = $product_quantity;



        $noteProduct['NoteProduct']['payment_type'] = $ticket_name;



        $noteProduct['NoteProduct']['status'] = Configure::read('App.Status.active');



        // pr($noteProduct);die;



        if($this->NoteProduct->saveAll($noteProduct)){

            $note_product_id = $this->NoteProduct->id;

            if(!empty(is_numeric($ticket_id))){

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 

                $sale_price = $this->priceChangeInt($sale_price); 

                $ticket_amount = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 

                if($ticket_amount >= $sale_price ){

                    $ticketAmount = ($ticket_amount - $sale_price) ;

                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];

                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = $ticket_id;

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



            

        }else{

            

        }   

    } 



    function delete_note_product($id = null){

        



        $this->loadModel('NoteProduct');

        $this->loadModel('NoteTicket');



        $customer_history_data = $this->NoteProduct->find('first', array('conditions'=>array('NoteProduct.id'=>$id)));



        $id = isset($id) ? $id : '';

        $noteProductData=$this->NoteProduct->find('first', array('conditions'=>array('NoteProduct.id'=>$id)));

        if(!empty($id)){

            if($this->NoteProduct->delete($id, true)){

               

               $noteOldTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$noteProductData['NoteProduct']['ticket_id'])));

                if(isset($noteOldTicketData) && !empty($noteOldTicketData)){

                    $old_ticket_amount = $this->priceChangeInt($noteOldTicketData['NoteTicket']['ticket_amount']); 

                    $old_sale_price = $this->priceChangeInt($noteProductData['NoteProduct']['sale_price']); 

                    $old_ticket_amount = ($old_ticket_amount + $old_sale_price);

                    $this->NoteTicket->id = $noteOldTicketData['NoteTicket']['id'];

                    $this->NoteTicket->saveField('ticket_amount' , number_format($old_ticket_amount).'円' );

                    

                    $this->Session->setFlash(__('Note Product deleted successfully.', true), 'admin_flash_error');

                    $this->redirect(array('action' => 'get_note',$customer_history_data['NoteProduct']['customer_history_id']));

                    }   

                }else{

                    $this->Session->setFlash(__('Note Product deleted error.', true), 'admin_flash_error');

                    $this->redirect(array('action' => 'get_note',$customer_history_data['NoteProduct']['customer_history_id']));

                }

        }else{

            $this->Session->setFlash(__('Note Product deleted error.', true), 'admin_flash_error');

                $this->redirect(array('action' => 'get_note',$customer_history_data['NoteProduct']['customer_history_id']));

        }

        $this->redirect(array('action' => 'get_note',$customer_history_data['NoteProduct']['customer_history_id']));

    } 





    public function get_ticket_price_amount(){

        $this->request->data['id'];

        $this->loadModel('Ticket');

        $this->loadModel('User');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';



        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';



            $this->layout = "dashboard";

        }



        $ticketData = $this->Ticket->find('first', array('conditions'=>array('Ticket.user_id'=>$user_id,'Ticket.id'=>$this->request->data['id']),"fields" =>array('Ticket.ticket_price','Ticket.ticket_amount')));

          // $ticket_price = substr($ticketData['Ticket']['ticket_price'], 0,-3);



          // $ticket_amount = substr($ticketData['Ticket']['ticket_amount'], 0,-3);



           $result['result1'] =  (substr($ticketData['Ticket']['ticket_price'], 0,-3) !=null)?substr($ticketData['Ticket']['ticket_price'], 0,-3):'0';

           $result['result2'] =  (substr($ticketData['Ticket']['ticket_amount'], 0,-3) !=null)?substr($ticketData['Ticket']['ticket_amount'], 0,-3):'0';

           echo json_encode($result);

    }



    function add_note_ticket(){

        

        

        $this->loadModel('Customer');

        $this->loadModel('Ticket');

        $this->loadModel('NoteTicket');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteRemainingTicket');

        $this->loadModel('Employee');





        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

           

            $this->layout = "dashboard";

        }



        $customer_history_id = isset($this->request->data['customer_history_id']) ? $this->request->data['customer_history_id'] : '';



        $employee_id = isset($this->request->data['employee_id']) ? $this->request->data['employee_id'] : '0';



        $ticket_price = isset($this->request->data['ticket_price']) ? $this->request->data['ticket_price']."円" : '0円';



        $ticket_amount = isset($this->request->data['ticket_amount']) ? $this->request->data['ticket_amount']."円" : '0円';



        $ticket_id = isset($this->request->data['ticket_id']) ? $this->request->data['ticket_id'] : '0';



        $payment_type = isset($this->request->data['payment_type']) ? $this->request->data['payment_type'] : '';

        

        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$customer_history_id, )));



        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';





        $ticket_name = ($this->Ticket->getTicketName($ticket_id) !=null)?$this->Ticket->getTicketName($ticket_id):'';



        $ticket_num_time = ($this->Ticket->getTicketNumTimeName($ticket_id) !=null)?$this->Ticket->getTicketNumTimeName($ticket_id):'0';



        $employee_name = ($this->Employee->getEmployeeName($employee_id) !=null)?$this->Employee->getEmployeeName($employee_id):'';





        



        $noteTicket['NoteTicket']['user_id'] = $user_id;



        $noteTicket['NoteTicket']['customer_id'] = $customer_id;



        $noteTicket['NoteTicket']['customer_history_id'] = $customer_history_id;



        $noteTicket['NoteTicket']['ticket_id'] = $ticket_id;



        $noteTicket['NoteTicket']['ticket_name'] = $ticket_name;



        $noteTicket['NoteTicket']['employee_id'] = $employee_id;



        $noteTicket['NoteTicket']['employee_name'] = $employee_name;



        $noteTicket['NoteTicket']['ticket_price'] = $ticket_price;



        $noteTicket['NoteTicket']['ticket_amount'] = $ticket_amount;



        $noteTicket['NoteTicket']['ticket_num_time'] = $ticket_num_time;



        $noteTicket['NoteTicket']['payment_type'] = $payment_type;



        $noteTicket['NoteTicket']['status'] = Configure::read('App.Status.active');



        // pr($noteTicket);die;



        $ticketData=$this->Ticket->find('first', array('conditions'=>array('Ticket.id'=>$ticket_id)));

        if(isset($ticketData['Ticket']['service_id']) && !empty($ticketData['Ticket']['service_id'])){

            $noteTicket['NoteTicket']['service_id'] = $ticketData['Ticket']['service_id'];

            $noteTicket['NoteTicket']['ticket_num_time'] = $ticketData['Ticket']['ticket_num_time'];

        }

        // print_r($noteTicket);die;

        if($this->NoteTicket->saveAll($noteTicket)){

            $note_ticket_id = $this->NoteTicket->id;

            if(!empty(is_numeric($ticket_id))){

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 

                if(!empty($noteTicketData)){              



                    $ticket_amount = $this->priceChangeInt($ticket_amount); 

                    $ticket_price = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 

                }

                if($ticket_price >= $ticket_amount ){

                    $ticketAmount = ($ticket_price - $ticket_amount) ;

                    $this->NoteTicket->id = isset($noteTicketData['NoteTicket']['id'])?$noteTicketData['NoteTicket']['id']:'';

                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = $ticket_id;

                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';

                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);

                }else{

                    $this->NoteTicket->id = $note_ticket_id;

                    $this->NoteTicket->saveField('ticket_id' , '0' );

                }

            }





           

            if(!empty($customer_id)){

                $customerData['Customer']['id'] = $customer_id;



                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');

                $this->Customer->saveAll($customerData);

            }



            

            

        }else{

            



        }   

        



    }



    function delete_note_ticket($id = null){

        



        $this->loadModel('NoteTicket');

        $this->loadModel('NoteProduct');



        $customer_history_data = $this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$id)));

        // echo $id;

        // pr($customer_history_data);die;

        $id = isset($id) ? $id : '';

        if(!empty($id)){

            if($this->NoteTicket->delete($id, true)){

                $this->Session->setFlash(__('Note Ticket deleted successfully.', true), 'admin_flash_error');

                $this->redirect(array('action' => 'get_note',$customer_history_data['NoteTicket']['customer_history_id']));

            }else{

                $this->Session->setFlash(__('Note Ticket deleted error.', true), 'admin_flash_error');

                $this->redirect(array('action' => 'get_note',$customer_history_data['NoteTicket']['customer_history_id']));

            }

        }else{

            $this->Session->setFlash(__('Note Ticket deleted error.', true), 'admin_flash_error');

            $this->redirect(array('action' => 'get_note',$customer_history_data['NoteTicket']['customer_history_id']));

        }

        $this->redirect(array('action' => 'get_note',$customer_history_data['NoteTicket']['customer_history_id']));

       

    }

    function get_edit_note_serviceData($id = null){

        $this->request->data['id'];

        $this->loadModel('NoteService');



        $NoteServiceData = $this->NoteService->find('first', array('conditions'=>array('NoteService.id'=>$this->request->data['id']))); 

        // print_r($NoteServiceData);   

        echo json_encode($NoteServiceData['NoteService']);    



    }



    function edit_note_service($id = null){







        $this->loadModel('Customer');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteTicket');

        $this->loadModel('NoteService');

        $this->loadModel('NoteRemainingTicket');

        $this->loadModel('Employee');

        $this->loadModel('Service');

        $this->loadModel('Ticket');

        

        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

            

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $emp_code = ($this->Auth->User('user_emp_code') != null) ? $this->Auth->User("user_emp_code") : '';

            if(($emp_code !=null) && !empty($emp_code)){

               $empData =  $this->Employee->find("first", array("conditions"=>array("Employee.emp_code"=>$emp_code)));

               if(($empData["Employee"]["id"] != null) && isset($empData["Employee"]["id"])){



               }



            }else{



            }

            $this->layout = "dashboard";

        }



        $id = isset($this->request->data['NoteService']['NoteServiceId']) ? $this->request->data['NoteService']['NoteServiceId'] : '';



        $customer_history_id = isset($this->request->data['NoteService']['customer_history_id']) ? $this->request->data['NoteService']['customer_history_id'] : '';

        $service_id = isset($this->request->data['NoteService']['service_id']) ? $this->request->data['NoteService']['service_id'] : '';

        $employee_id = isset($this->request->data['NoteService']['employee_id']) ? $this->request->data['NoteService']['employee_id'] : '0';

        $service_price = isset($this->request->data['NoteService']['service_price']) ? $this->request->data['NoteService']['service_price']."円" : '';

        $ticket_id = isset($this->request->data['NoteService']['ticket_id']) ? $this->request->data['NoteService']['ticket_id'] : '0';

        

        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$customer_history_id, )));



        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';

        $service_name = ($this->Service->getServiceName($service_id) !=null)?$this->Service->getServiceName($service_id):'';

        $employee_name = ($this->Employee->getEmployeeName($employee_id) !=null)?$this->Employee->getEmployeeName($employee_id):'';



        // pr(($ticket_id));die;



        if(is_numeric($ticket_id)){

            $ticket_name = $this->NoteTicket->getNoteTicketName($ticket_id);

        }else{

            $ticket_name = $ticket_id;

        }



        $noteService['NoteService']['id'] = $id;



        $noteService['NoteService']['user_id'] = $user_id;



        $noteService['NoteService']['customer_id'] = $customer_id;



        $noteService['NoteService']['customer_history_id'] = $customer_history_id;



        $noteService['NoteService']['customer_service_id'] = '0';



        $noteService['NoteService']['employee_id'] = $employee_id;



        $noteService['NoteService']['ticket_id'] = (is_numeric($ticket_id)?$ticket_id:'0');



        $noteService['NoteService']['employee_name'] = $employee_name;



        $noteService['NoteService']['service_price'] = $service_price;



        $noteService['NoteService']['payment_type'] = $ticket_name ? $ticket_name : $ticket_name;



        $noteService['NoteService']['service_id'] = $service_id;



        $noteService['NoteService']['service_name'] = $service_name;



        $noteService['NoteService']['status'] = Configure::read('App.Status.active');







        $noteServiceData=$this->NoteService->find('first', array('conditions'=>array('NoteService.id'=>$id)));





        if($this->NoteService->saveAll($noteService)){



            if(!empty(is_numeric($ticket_id))){

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 

                if(($noteTicketData['NoteTicket']['ticket_num_time'] !=0) && ($noteTicketData['NoteTicket']['ticket_num_time'] > 0)){

                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];

                    $ticket_num_time = ($noteTicketData['NoteTicket']['ticket_num_time'] - 1);

                    $this->NoteTicket->saveField('ticket_num_time' , $ticket_num_time );

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = (is_numeric($ticket_id)?$ticket_id:'0');

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

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = (is_numeric($ticket_id)?$ticket_id:'0');

                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';

                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);



                }

            }



            $note_service_id = $this->NoteService->id;

            $customerData['Customer']['id'] = $customer_id;



            $customerData['Customer']['modified'] = date('Y-m-d H:i:s');

            $this->Customer->saveAll($customerData);



            

            $this->Session->setFlash(__('Service Saved successfully.', true), 'admin_flash_error');

            $this->redirect(array('action' => 'get_note',$customer_history_id));

    

        }else{

           $this->Session->setFlash(__('Service could not be Saved. Please, try again.', true), 'admin_flash_error');

                    $this->redirect(array('action' => 'get_note',$customer_history_id));

        }   

        

        $this->Session->setFlash(__('Service could not be Saved. Please, try again.', true), 'admin_flash_error');

        $this->redirect(array('action' => 'get_note',$customer_history_id));

    }



    function get_edit_note_productData($id = null){

        $this->request->data['id'];

        $this->loadModel('NoteProduct');

        $this->loadModel('Product');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';



        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

            $this->layout = "dashboard";

        }



        $NoteProductData = $this->NoteProduct->find('first', array('conditions'=>array('NoteProduct.id'=>$this->request->data['id']))); 

        // echo$NoteProductData['NoteProduct']['product_id'];die;

        $productStock = $this->Product->find('first', array('conditions'=>array('Product.user_id'=>$user_id,'Product.id'=>$NoteProductData['NoteProduct']['product_id']),"fields" =>array('Product.product_stock')));



        // $ticketStock = $this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.user_id'=>$user_id,'NoteTicket.ticket_name'=>$NoteProductData['NoteProduct']['payment_type'], 'NoteTicket.customer_id'=>$NoteProductData['NoteProduct']['customer_id'] ),"fields" =>array('NoteTicket.id')));

        // pr($ticketStock);

         // pr($NoteProductData['NoteProduct']['customer_history_id']);die;

        // $str = '';



         // for($i=1; $i<=$productStock['Product']['product_stock'];$i++){

         //    // echo $i; die;

         //    $str = $str."<option value=".$i.">".$i."</option>";

         // }

        // echo $str; 



        // print_r($NoteProductData);die;



         

         $result['str1'] =  $NoteProductData['NoteProduct'];

         $result['str'] =  $productStock['Product']['product_stock'];



        echo json_encode($result);    



    }



    function edit_note_product($id=null){

        

        // pr($this->request->data);die; 



        $this->loadModel('Customer');

        $this->loadModel('NoteProduct');

        $this->loadModel('NoteTicket');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteRemainingTicket');

        $this->loadModel('Product');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

           

            $this->layout = "dashboard";

        }



        $id = isset($this->request->data['NoteProductId']) ? $this->request->data['NoteProductId'] : '';



        $customer_history_id = isset($this->request->data['customer_history_id']) ? $this->request->data['customer_history_id'] : '';



        $employee_id = isset($this->request->data['employee_id']) ? $this->request->data['employee_id'] : '0';

        $sale_price = isset($this->request->data['sale_price']) ? $this->request->data['sale_price']."円" : '';

        $ticket_id = isset($this->request->data['ticket_id']) ? $this->request->data['ticket_id'] : '0';

        $product_id = isset($this->request->data['product_id']) ? $this->request->data['product_id'] : '0';

        $product_quantity = isset($this->request->data['product_quantity']) ? $this->request->data['product_quantity'] : '';

        

        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$customer_history_id, )));



        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';

        $product_name = ($this->Product->getProductName($product_id) !=null)?$this->Product->getProductName($product_id):'';

        $employee_name = ($this->Employee->getEmployeeName($employee_id) !=null)?$this->Employee->getEmployeeName($employee_id):'';





        if(is_numeric($ticket_id)){

            $ticket_name = $this->NoteTicket->getNoteTicketName($ticket_id);

        }else{

            $ticket_name = $ticket_id;

        }



        $noteProduct['NoteProduct']['id'] = $id;



        $noteProduct['NoteProduct']['user_id'] = $user_id;



        $noteProduct['NoteProduct']['customer_id'] = $customer_id;



        $noteProduct['NoteProduct']['ticket_id'] = (is_numeric($ticket_id)?$ticket_id:'0');



        $noteProduct['NoteProduct']['customer_history_id'] = $customer_history_id;



        $noteProduct['NoteProduct']['product_id'] = $product_id;



        $noteProduct['NoteProduct']['product_name'] = $product_name;



        $noteProduct['NoteProduct']['employee_id'] = $employee_id;



        $noteProduct['NoteProduct']['employee_name'] = $employee_name;



        $noteProduct['NoteProduct']['sale_price'] = $sale_price;



        $noteProduct['NoteProduct']['product_quantity'] = $product_quantity;



        $noteProduct['NoteProduct']['payment_type'] = $ticket_name ? $ticket_name : $ticket_name;



        $noteProduct['NoteProduct']['status'] = Configure::read('App.Status.active');



        // pr($noteProduct);die;



        // $this->NoteProduct->bindModel(array('belongsTo' => array('Product')));

        // $getNoteProduct = $this->NoteProduct->find('first', array('conditions'=>array('NoteProduct.id'=>$id))); 



        // // pr($getNoteProduct);die;



        // if(isset($getNoteProduct['Product']['product_stock'])){

        //     if($product_quantity > $getNoteProduct['Product']['product_stock']){

               

        //         // $responseArr = array('msg' => '製品は在庫切れです。', 'msg1' => 'Product is out of stock.',  'status' => 'error' );

        //         // $jsonEncode = json_encode($responseArr);

        //         // echo  $jsonEncode;exit();

        //         alert("Product is out of stock.");

        //         $this->Session->setFlash(__('Product is out of stock.', true), 'admin_flash_error');

        //         $this->redirect(array('action' => 'get_note',$customer_history_id));

        //     }

        // }else{

        //     alert("Product is out of stock.");

        //     $this->Session->setFlash(__('Product is out of stock.', true), 'admin_flash_error');

        //     $this->redirect(array('action' => 'get_note',$customer_history_id));

        // }

        // pr($noteProduct);die;

        if($this->NoteProduct->saveAll($noteProduct)){

            $note_product_id = $this->NoteProduct->id;

            if(!empty(is_numeric($ticket_id))){

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 

                $sale_price = $this->priceChangeInt($sale_price); 

                $ticket_amount = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 

                if($ticket_amount >= $sale_price ){

                    $ticketAmount = ($ticket_amount - $sale_price) ;

                    $this->NoteTicket->id = $noteTicketData['NoteTicket']['id'];

                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = $ticket_id;

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



            

        }else{

            

        }   

    } 



    public function get_edit_ticket_price_amount(){



        $this->request->data['id'];

        $this->loadModel('NoteTicket');

        $this->loadModel('Ticket');

        $this->loadModel('User');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';



        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';



            $this->layout = "dashboard";

        }



        // $ticketData = $this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.user_id'=>$user_id,'NoteTicket.id'=>$this->request->data['id'])));

        $ticketData = $this->Ticket->find('first', array('conditions'=>array('Ticket.user_id'=>$user_id,'Ticket.id'=>$this->request->data['id']),"fields" =>array('Ticket.ticket_price','Ticket.ticket_amount')));

        // pr($ticketData);die;

          // $ticket_price = substr($ticketData['Ticket']['ticket_price'], 0,-3);



          // $ticket_amount = substr($ticketData['Ticket']['ticket_amount'], 0,-3);



           $result['result1'] =  (substr($ticketData['Ticket']['ticket_price'], 0,-3) !=null)?substr($ticketData['Ticket']['ticket_price'], 0,-3):'0';

           $result['result2'] =  (substr($ticketData['Ticket']['ticket_amount'], 0,-3) !=null)?substr($ticketData['Ticket']['ticket_amount'], 0,-3):'0';

           echo json_encode($ticketData['Ticket']);

           // echo json_encode($result);

    }



    public function get_edit_ticketData(){



        $this->request->data['id'];

        $this->loadModel('NoteTicket');

        // $this->loadModel('Ticket');

        $this->loadModel('User');

        $this->loadModel('Employee');



        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";

            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';



        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';



            $this->layout = "dashboard";

        }



        $ticketData = $this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.user_id'=>$user_id,'NoteTicket.id'=>$this->request->data['id'])));

        // $ticketData = $this->Ticket->find('first', array('conditions'=>array('Ticket.user_id'=>$user_id,'Ticket.id'=>$this->request->data['id']),"fields" =>array('Ticket.ticket_price','Ticket.ticket_amount')));

        // pr($ticketData);die;

          // $ticket_price = substr($ticketData['Ticket']['ticket_price'], 0,-3);



          // $ticket_amount = substr($ticketData['Ticket']['ticket_amount'], 0,-3);



           $result['result1'] =  (substr($ticketData['NoteTicket']['ticket_price'], 0,-3) !=null)?substr($ticketData['NoteTicket']['ticket_price'], 0,-3):'0';

           $result['result2'] =  (substr($ticketData['NoteTicket']['ticket_amount'], 0,-3) !=null)?substr($ticketData['NoteTicket']['ticket_amount'], 0,-3):'0';

           echo json_encode($ticketData['NoteTicket']);

           // echo json_encode($result);

    }









    function edit_note_ticket($id = null){

        // pr($this->request->data);die;



        $this->loadModel('Customer');

        $this->loadModel('Ticket');

        $this->loadModel('NoteTicket');

        $this->loadModel('CustomerHistory');

        $this->loadModel('NoteRemainingTicket');

        $this->loadModel('Employee');





        if(($this->Session->read('employee.Employee.user_id') != null) && !empty($this->Session->read('employee.Employee.user_id'))){

           

            $this->layout = "employee_dashboard";



            $user_id = ($this->Session->read('employee.Employee.user_id') != null) ? $this->Session->read('employee.Employee.user_id') : '';

        }elseif(($this->Auth->User("id") !=null) && !empty($this->Auth->User("id"))) {

            

            $user_id = ($this->Auth->User('id') != null) ? $this->Auth->User("id") : '';

           

            $this->layout = "dashboard";

        }



        $id = isset($this->request->data['NoteTicketId']) ? $this->request->data['NoteTicketId'] : '';



        $customer_history_id = isset($this->request->data['customer_history_id']) ? $this->request->data['customer_history_id'] : '';



        $employee_id = isset($this->request->data['employee_id']) ? $this->request->data['employee_id'] : '0';



        $ticket_price = isset($this->request->data['ticket_price']) ? $this->request->data['ticket_price'] : '0円';



        $ticket_amount = isset($this->request->data['ticket_amount']) ? $this->request->data['ticket_amount'] : '0円';



        $ticket_id = isset($this->request->data['ticket_id']) ? $this->request->data['ticket_id'] : '0';



        $payment_type = isset($this->request->data['payment_type']) ? $this->request->data['payment_type'] : '';

        

        $customer_data = $this->CustomerHistory->find('first', array('conditions'=>array('CustomerHistory.id'=>$customer_history_id, )));



        $customer_id = isset($customer_data['CustomerHistory']['customer_id'])?$customer_data['CustomerHistory']['customer_id']:'';





        $ticket_name = ($this->Ticket->getTicketName($ticket_id) !=null)?$this->Ticket->getTicketName($ticket_id):'';



        $ticket_num_time = ($this->Ticket->getTicketNumTimeName($ticket_id) !=null)?$this->Ticket->getTicketNumTimeName($ticket_id):'0';



        $employee_name = ($this->Employee->getEmployeeName($employee_id) !=null)?$this->Employee->getEmployeeName($employee_id):'';





        $noteTicket['NoteTicket']['id'] = $id;



        $noteTicket['NoteTicket']['user_id'] = $user_id;



        $noteTicket['NoteTicket']['customer_id'] = $customer_id;



        $noteTicket['NoteTicket']['customer_history_id'] = $customer_history_id;



        $noteTicket['NoteTicket']['ticket_id'] = $ticket_id;



        $noteTicket['NoteTicket']['ticket_name'] = $ticket_name;



        $noteTicket['NoteTicket']['employee_id'] = $employee_id;



        $noteTicket['NoteTicket']['employee_name'] = $employee_name;



        $noteTicket['NoteTicket']['ticket_price'] = $ticket_price;



        $noteTicket['NoteTicket']['ticket_amount'] = $ticket_amount;



        $noteTicket['NoteTicket']['ticket_num_time'] = $ticket_num_time;



        $noteTicket['NoteTicket']['payment_type'] = $payment_type;



        $noteTicket['NoteTicket']['status'] = Configure::read('App.Status.active');



        // pr($noteTicket);die;



        $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$id)));



        $ticketData=$this->Ticket->find('first', array('conditions'=>array('Ticket.id'=>$ticket_id)));

        if(isset($ticketData['Ticket']['service_id']) && !empty($ticketData['Ticket']['service_id'])){

            $noteTicket['NoteTicket']['service_id'] = $ticketData['Ticket']['service_id'];

            $noteTicket['NoteTicket']['ticket_num_time'] = $ticketData['Ticket']['ticket_num_time'];

        }

        // print_r($noteTicket);die;

        if($this->NoteTicket->saveAll($noteTicket)){

            $note_ticket_id = $this->NoteTicket->id;

            if(!empty(is_numeric($ticket_id))){

                $noteTicketData=$this->NoteTicket->find('first', array('conditions'=>array('NoteTicket.id'=>$ticket_id))); 

                if(!empty($noteTicketData)){              



                    $ticket_amount = $this->priceChangeInt($ticket_amount); 

                    $ticket_price = $this->priceChangeInt($noteTicketData['NoteTicket']['ticket_amount']); 

                }

                if($ticket_price >= $ticket_amount ){

                    $ticketAmount = ($ticket_price - $ticket_amount) ;

                    $this->NoteTicket->id = isset($noteTicketData['NoteTicket']['id'])?$noteTicketData['NoteTicket']['id']:'';

                    $this->NoteTicket->saveField('ticket_amount' , number_format($ticketAmount).'円' );

                    $noteRemainingTicket['NoteRemainingTicket']['user_id'] = $user_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_id'] = $customer_id;

                    $noteRemainingTicket['NoteRemainingTicket']['customer_history_id'] = $customer_history_id ;

                    $noteRemainingTicket['NoteRemainingTicket']['note_ticket_id'] = $ticket_id;

                    $noteRemainingTicket['NoteRemainingTicket']['remaining_amount'] = number_format($ticketAmount).'円';

                    $this->NoteRemainingTicket->saveAll($noteRemainingTicket);

                }else{

                    $this->NoteTicket->id = $note_ticket_id;

                    $this->NoteTicket->saveField('ticket_id' , '0' );

                }

            }





           

            if(!empty($customer_id)){

                $customerData['Customer']['id'] = $customer_id;



                $customerData['Customer']['modified'] = date('Y-m-d H:i:s');

                $this->Customer->saveAll($customerData);

            }



            

            

        }else{

            



        }

    }

}