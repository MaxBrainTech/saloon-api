<?php
/**
 * Stripe Payments Controller for API
 *
 * PHP version 5.4
 *
 */
class StripePaymentsController extends AppController {
	/**
     * Stripe Payments Controller for API
     *
     * @var string
     * @access public
     */

	public $components = array('Stripe.Stripe');

	public function beforeFilter(){
		parent::beforeFilter();
        $this->loadModel('User');
		$this->Auth->allow('index','get_response');
        $this->RequestHandler->addInputType('json', array('json_decode', true));
    }


	public function index($user_id = null) {

		if(!empty($user_id) ){
			$get_user_data = $this->User->find('first', array('conditions' => array('id' => $user_id)));
			// pr($get_user_data['User']);die;
			$name = $get_user_data['User']['salon_name'];
			$email = $get_user_data['User']['email'];
			$image = $get_user_data['User']['image'];
		}else{
			$name = $this->Auth->User('salon_name');
			$email = $this->Auth->User('email');
			$image = $this->Auth->User('image');
			$get_user_data = $this->User->find('first', array('conditions' => array('id' => $email)));
		}

		$stripe_publishable_key = Configure::read('App.PublishableKey');
		if($get_user_data['User']['stripe_payment_status'] == 1){
			
		    $this->redirect(array('controller'=>'../users', 'action' => 'payment_info', $user_id ));
		}else{

		    $this->set(compact('name','email','image','get_user_data', 'user_id','stripe_publishable_key'));
		    $this->set('title_for_layout', __('Payment Page', true));

    		$this->layout = "app_dashboard";
		}
		
		
	}


	public function get_response($user_id = null){
		if(!empty($user_id) ){
			$get_user_data = $this->User->find('first', array('conditions' => array('id' => $user_id)));
			// pr($get_user_data['User']);die;
			$name = $get_user_data['User']['name'];
			$email = $get_user_data['User']['email'];
			$image = $get_user_data['User']['image'];
		}else{
			$name = $this->Auth->User('name');
			$email = $this->Auth->User('email');
			$image = $this->Auth->User('image');
			$get_user_data = $this->User->find('first', array('conditions' => array('id' => $email)));
		}

		$this->set(compact('get_user_data','user_id','name','email','image'));
		$this->layout = "app_dashboard";
	}

	// public function app_payment(){
	// 	echo "hello";die;
	// }
    
    public function stop_plan(){
		$this->loadModel('User');

        $user_id = $this->request->data['stopPayment']['user_id'];
        $customer_id = $this->request->data['stopPayment']['customer_id'];
        // pr($user_id);
        // pr($customer_id);die;
        $subscription = $this->Stripe->subscriptionCancel($customer_id);
        $subscription =  substr($subscription,22);
        $subscription_decode = json_decode($subscription);
        $sub_end_date = gmdate("d-m-Y",$subscription_decode->subscriptions->data[0]->current_period_end);

        $this->User->id = $user_id;                

        $this->User->set(array('subscription_current_period_end'=>$sub_end_date,'stripe_plan_status'=>2));                
        
        if($this->User->save()){
            $this->Session->setFlash(__('<p class="text-center alert alert-success">JTS Board Plan has been Deactivated successfully</p>'), 'admin_flash_success');
            $this->redirect(array('controller'=>'../users', 'action' => 'payment_info',$user_id));
        }else{
            $this->Session->setFlash(__('JTS Board Plan not Deactivated'), 'admin_flash_error');
            $this->redirect(array('controller'=>'../users', 'action' => 'payment_info',$user_id));
        }
	}
}