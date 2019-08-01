<?php
/**
 * Customer Form Controller
 *
 * PHP version 5.4
 *
 */
class CustomersformController extends AppController {

	/**
	 * Customer Form Controller
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Customersform';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login', 'add_form', 'list_form');
		$user_id = $this->Auth->User('id');
	}

	

	public function add_form($user_id = null){
		$this->loadModel('User');
		$this->loadModel('Service');
		$this->loadModel('CustomerForm');
		$form_data = array();
		if($user_id == null)
			$user_id = $this->Auth->User('id');
		
		$serviceIds = $this->CustomerForm->find('list',array('conditions'=>array('CustomerForm.user_id'=> $user_id), 'fields'=>array('CustomerForm.service_id')));
		$serviceListOld = $this->Service->find('list',array('conditions'=>array('Service.user_id'=> $user_id), 'fields'=>array('Service.name',)));
		$serviceList =array();
		foreach ($serviceListOld as $key => $value) {
			if(!in_array($key, $serviceIds)){
				$serviceList[$key] = $value;
			}
		}

		if ($this->request->is('post')) {
			
			$form_data['user_id'] = $user_id;
			$form_data['service_id'] = $this->request->data['service_id'];
			$form_data['form_name'] = $this->request->data['form_name'];
			$form_data['form_text'] = $this->request->data['form_text'];
			// $jsonEncode = json_encode($form_data);
			// return $jsonEncode;
			if($this->CustomerForm->saveAll($form_data)){
				$responseArr = array( 'status' => 'success', 'msg' =>'Service Form Added Successfully' );
                $jsonEncode = json_encode($responseArr);
			}
			else{
                $responseArr = array('status' => 'error' );
                $jsonEncode = json_encode($responseArr);
               
            }
           return $jsonEncode;

		}
		$this->request->data = $this->User->read(null, $user_id);
		$this->set('serviceList', $serviceList);
		$this->set('user_id', $user_id);
		$this->set('title_for_layout', __('Customer Form', true));

        $this->layout = "app_dashboard";
	}

	/*
	 * List all forms in user panel
	 */
	public function list_form() {
		$this->loadModel('Service');
		$this->loadModel('CustomerForm');
		$user_id = $this->Auth->User('id');
		// print_r($user_id);die;
        $count = $this->CustomerForm->find('count', array('conditions' => array('CustomerForm.user_id' => $user_id)));
        // echo $count;die; 
        
       $filters = array('CustomerForm.user_id' => $user_id);
       $this->CustomerForm->bindModel(array('belongsTo'=>array('Service'=>array('className'=>'Service', 'foreignKey'=>'service_id'))));
       // $serviceList = $this->CustomerForm->find("list", array("conditions"=>$filters, "fields"=>array("Service.id", "Service.name")));
        $this->paginate = array(
            'CustomerForm' => array(
                'order' => array('CustomerForm.id' => 'DESC'),
                'limit' => $count,
                'conditions' => $filters
             )
        );
        
        $data = $this->paginate('CustomerForm');
       // echo "<pre>";
        //print_r($data);die;
        $this->set(compact('data'));
        $this->set('title_for_layout', __('My Forms', true));

        $this->layout = "dashboard";
	}

	public function status($id = null){
	// echo $id;die;
		$this->loadModel('CustomerForm');
		if ($this->CustomerForm->toggleStatus($id)) {
			$this->Session->setFlash(__('Form status has been changed'), 'flash_success');
			$this->redirect(array('action' => 'list_form'));
		}
		
	}

	//delete custom form

	public function delete($id = null) {
		$this->loadModel('CustomerForm');
		$this->CustomerForm->id = $id;
		if (!$this->CustomerForm->exists()) {
			throw new NotFoundException(__('Invalid service'));
		}
		
		if ($this->CustomerForm->delete()) {
			$this->Session->setFlash(__('Form deleted successfully'), 'flash_success');
			$this->redirect('list_form');
		}
		$this->Session->setFlash(__('Form was not deleted', 'flash_error'));
		$this->redirect('list_form');
	}
	


}