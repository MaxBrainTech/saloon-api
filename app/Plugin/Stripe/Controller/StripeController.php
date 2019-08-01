<?php
/**
 * Stripe Response Controller
 *
 * PHP version 5.4
 *
 */
class StripeController extends AppController {
	/**
     * Stripe Response Controller
     *
     * @var string
     * @access public
     */

	public $components = array('Stripe.Stripe');

	public function beforeFilter(){
		parent::beforeFilter();
        $this->loadModel('User');
		 $this->Auth->allow('index', 'get_response');
        $this->RequestHandler->addInputType('json', array('json_decode', true));
    }


	public function index() {

		$name = $this->Auth->User('salon_name');
		$email = $this->Auth->User('email');
		$get_user_data = $this->User->find('first', array('conditions' => array('email' => $email)));
		$stripe_publishable_key = Configure::read('App.PublishableKey');
		

		if($get_user_data['User']['stripe_payment_status'] == 1){
		    $this->redirect(array('controller'=>'../users', 'action' => 'payment_info'));
		}else{
		    $this->set(compact('name','email','get_user_data','stripe_publishable_key'));
		    $this->set('title_for_layout', __('Payment Page', true));

    		$this->layout = "dashboard";
		}
		
		
	}


	public function get_response(){

			$name = $this->Auth->User('salon_name');
			$email = $this->Auth->User('email');
			$image = $this->Auth->User('image');
			$get_user_data = $this->User->find('first', array('conditions' => array('id' => $email)));
			

		$this->set(compact('get_user_data','name','email','image'));
		$this->layout = "app_dashboard";
	}


}