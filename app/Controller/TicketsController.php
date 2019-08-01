<?php

/**
 * Ticket Controller
 *
 * PHP version 5.4
 *
 */
class TicketsController extends AppController{

    /**
     * Ticket Controller
     *
     * @var string
     * @access public
     */
    public $name = 'Tickets';
    public $components = array(
        'General', 'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('Ticket');

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
        $this->loadModel('Ticket');
    }

    public function beforeFilter(){
    	
        parent::beforeFilter();
        $this->loadModel('Ticket');
		$this->Auth->allow('add_ticket','ticket_list','delete_ticket','edit_ticket');
        date_default_timezone_set("Asia/Tokyo");
    }


    /*************************Ticket List*********************************/

    function ticket_list(){

        $this->loadModel("Ticket");
        $user_id = isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : '';
        
        if(!empty($user_id)){
            $data = $this->Ticket->find('all',array('conditions'=>array( 'Ticket.user_id'=>$user_id), 'order' => array('Ticket.modified' => 'DESC')));
            // pr($data);die;
            if(!$data){
                $this->Session->setFlash(__('This Ticket not Exist', 'flash_error'));
            }else{
                if(!empty($data)){
                    $i=0;
                    foreach ($data as $key => $value) {
                      $customerData['Ticket'][$i] = $value['Ticket'];
                        $i++;
                    }

                }else{
                    $customerData[$i]['Ticket']['msg'] = 'レコードが見つかりませんでした。';
                    $customerData[$i]['Ticket']['msg1'] = 'No Record Found.';
                    $customerData[$i]['Ticket']['status'] = 'error';

                }
            }
        }else{
            $customerData[$i]['Ticket']['msg1'] = '商品は存在しません.';
             $customerData[$i]['Ticket']['msg'] = 'Ticket does not exist.';
            $customerData[$i]['Ticket']['status'] = 'error';
            
        }
        // pr($customerData);die;
        $this->set(compact('customerData'));
        $this->layout = "dashboard";
    }

    /*************************Add Ticket*********************************/

    function add_ticket(){
        
        $this->loadModel("Ticket");
        $this->loadModel("Service");
        if(!empty($this->request->data)){

            $this->Ticket->set($this->request->data['Ticket']);
            $this->Ticket->setValidation('add_ticket');

            $id = (isset($this->request->data['Ticket']['id']) ? $this->request->data['Ticket']['id'] : '');
            $user_id = (isset($this->request->data['Ticket']['user_id']) ? $this->request->data['Ticket']['user_id'] : '');
            $ticket_name = (isset($this->request->data['Ticket']['ticket_name']) ? $this->request->data['Ticket']['ticket_name'] : '');
            $ticket_price = (isset($this->request->data['Ticket']['ticket_price']) ? $this->request->data['Ticket']['ticket_price'].'円' : '0円');
            $ticket_amount = (!empty($this->request->data['Ticket']['ticket_amount']) ? $this->request->data['Ticket']['ticket_amount'].'円' : '0円');
            $ticket_num_time = (isset($this->request->data['Ticket']['ticket_num_time']) ? $this->request->data['Ticket']['ticket_num_time'] : '');
            // pr($ticket_amount);die;
       
            $ticketExist = $this->Ticket->find('first', array('conditions'=>array('Ticket.ticket_name'=>$ticket_name)));
               
            if($ticketExist){

                $this->Session->setFlash(__('This Ticket already Exist', 'flash_error'));
            }
            

            $ticket['Ticket']['user_id'] = isset($user_id) ? strtolower($user_id) : '';
            // $ticket['Ticket']['service_id'] = 0;
            $ticket['Ticket']['ticket_name'] = isset($ticket_name) ? $ticket_name : '';
            $ticket['Ticket']['ticket_price'] = isset($ticket_price) ? $ticket_price : '';
            $ticket['Ticket']['ticket_amount'] = isset($ticket_amount) ? $ticket_amount : '';
            $ticket['Ticket']['ticket_num_time'] = isset($ticket_num_time) ? $ticket_num_time : '';
            $ticket['Ticket']['status'] = 1;
        
        
       
            if($this->Ticket->saveAll($ticket)){
                $this->Session->setFlash(__('The Ticket information has been updated successfully', true), 'admin_flash_success');
                $this->redirect(array('action' => 'ticket_list'));
                
            }else{
                $this->Session->setFlash(__('The Ticket could not be saved. Please, try again.', true), 'admin_flash_error');
            }
        }
        $this->set('title_for_layout', __('Ticket', true));
        $this->layout = "dashboard";
    }

    /*************************Edit Ticket*********************************/

    function edit_ticket($id = null){
        
        $this->loadModel("Ticket");
        // pr($this->request->data);die;
        if(!empty($this->request->data)){

            $this->Ticket->set($this->request->data['Ticket']);
            $this->Ticket->setValidation('add_ticket');

           $id = (isset($this->request->data['Ticket']['id']) ? $this->request->data['Ticket']['id'] : '');
            $user_id = (isset($this->request->data['Ticket']['user_id']) ? $this->request->data['Ticket']['user_id'] : '');
            $ticket_name = (isset($this->request->data['Ticket']['ticket_name']) ? $this->request->data['Ticket']['ticket_name'] : '');
            $ticket_price = (isset($this->request->data['Ticket']['ticket_price']) ? $this->request->data['Ticket']['ticket_price'].'円' : '');
            $ticket_amount = (!empty($this->request->data['Ticket']['ticket_amount']) ? $this->request->data['Ticket']['ticket_amount'].'円' : '0円');
            $ticket_num_time = (isset($this->request->data['Ticket']['ticket_num_time']) ? $this->request->data['Ticket']['ticket_num_time'] : '');

        
            $ticket['Ticket']['id'] = isset($id) ? strtolower($id) : '';
            $ticket['Ticket']['user_id'] = isset($user_id) ? strtolower($user_id) : '';
            $ticket['Ticket']['ticket_name'] = isset($ticket_name) ? $ticket_name : '';
            $ticket['Ticket']['ticket_price'] = isset($ticket_price) ? $ticket_price : '';
            $ticket['Ticket']['ticket_amount'] = isset($ticket_amount) ? $ticket_amount : '';
            $ticket['Ticket']['ticket_num_time'] = isset($ticket_num_time) ? $ticket_num_time : '';
            $ticket['Ticket']['status'] = 1;
            
            // pr($ticket);die;
           
            if($this->Ticket->saveAll($ticket)){
                $this->Session->setFlash(__('The Ticket information has been updated successfully', true), 'admin_flash_success');
                $this->redirect(array('action' => 'ticket_list'));
                
            }else{
                $this->Session->setFlash(__('The Ticket could not be saved. Please, try again.', true), 'admin_flash_error');
            }
        }else{
            $this->request->data = $this->Ticket->read(null, $id);
        }
        $this->set('title_for_layout', __('Ticket', true));
        $this->layout = "dashboard";
    }

    /*************************Delete Ticket*********************************/

    function delete_ticket($id = null){
        $this->loadModel('Ticket');
        $id = isset($id) ? $id : '';
        if(!empty($id)){
            if($this->Ticket->delete($id, true)){
                $this->Session->setFlash(__('Ticket deleted successfully'), 'flash_success');
                $this->redirect($this->referer());
            }else{
                $this->Session->setFlash(__('Ticket was not deleted', 'flash_error'));
                $this->redirect($this->referer());
            }
        }else{
            $this->Session->setFlash(__('Ticket does not exist.', 'flash_error'));
            $this->redirect($this->referer());
        }
    }
}