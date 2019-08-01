<?php

/**

 * Stripe Response Controller

 *

 * PHP version 5.4

 *

 */



class GetResponseController extends AppController {



	/**

     * Stripe Response Controller

     *

     * @var string

     * @access public

     */



	public $components = array('Stripe.Stripe');

	public function beforeFilter(){

			    	

        parent::beforeFilter();

        $this->RequestHandler->addInputType('json', array('json_decode', true));
        $this->Auth->allow('index', 'get_response');

    }



	public function index($user_id = null) {



		/** Load Model **/

		$this->loadModel("StripeCustomerDetails");

		$this->loadModel("StripeCardDetails");

		$this->loadModel("StripePlanDetails");

		$this->loadModel("StripeSubscriptionDetails");

		$this->loadModel("StripeTransactionDetails");

		$this->loadModel("User");


		if(!empty($user_id) ){
			$get_user_data = $this->User->find('first', array('conditions' => array('id' => $user_id)));
			$name = $get_user_data['User']['name'];
			$email = $get_user_data['User']['email'];
			$user_image = $get_user_data['User']['image'];
		}else{
			$user_id = (isset($this->request->data['user_id'])?($this->request->data['user_id']):'');
			$name = (isset($this->request->data['name'])?($this->request->data['name']):'');
			$email = (isset($this->request->data['email'])?($this->request->data['email']):'');
			
		}

		$token = (isset($this->request->data['stripeToken'])?($this->request->data['stripeToken']):'');

		$plan_currency = (isset($this->request->data['plan_currency'])?($this->request->data['plan_currency']):'');

		$plan_id = (isset($this->request->data['plan_id'])?($this->request->data['plan_id']):'');

		$plan_amount = (isset($this->request->data['plan_amount'])?($this->request->data['plan_amount']):'');


		
		if(empty($token)){

			$this->redirect(array('controller'=>'../users', 'action' => 'payment_info',$user_id));

			}else{


			/*** create customer in stripe Account ***/


			$data = array(

				'source' => $token,

				'description' => 'JTS Board Customer Description',

				'email' => $email

			);

			$customer_result = $this->Stripe->customerCreate($data);
            
            // pr($customer_result);die;
    
			if(isset($customer_result['stripe_id']) && $customer_result['stripe_id'] !=null){
				$customer_id = $customer_result['stripe_id'];
			}else{
				// pr($customer_result);die;
				// $this->Session->write('CustomerError', $customer_result);
				$this->Session->setFlash(__('<p class="text-center alert alert-danger">'.$customer_result.'</p>'), 'admin_flash_success');
				$this->redirect(array('controller' => '/stripe_payments', 'action' => 'index',$user_id)); 
			}



			/*** create charge in stripe Account ***/



			$data = array(

				'amount' => $plan_amount,

				'description' => 'JTS Board Charge Description',

				'currency' => $plan_currency,

				'stripeCustomer' => $customer_result['stripe_id']

			);

			$charge = $this->Stripe->charge($data);
            // pr($charge);die;
			if(isset($charge['stripe_id']) && $charge['stripe_id'] !=null){
				$transactionId = $charge['stripe_id'];
			}else{
				
				// $this->Session->write('ChargeError', $charge);
				$this->Session->setFlash(__('<p class="text-center alert alert-danger">'.$charge.'</p>'), 'admin_flash_success');
				$this->redirect(array('controller' => '/stripe_payments', 'action' => 'index',$user_id)); 
			}
		 	

		 	
	        /*** Create Subscription in stripe Account ***/

			 $data = array(

				'customer' => $customer_id,

				'items' => [

				    [

				      "plan" => $plan_id,

				    ],

				  ]

			);

			$subscription = $this->Stripe->subscriptionCreate($data);
            
            // pr($subscription);die;

			if(isset($subscription['stripe_id']) && $subscription['stripe_id'] !=null){

			}else{
				// pr($subscription);die;
				// $this->Session->write('SubscriptionError', $subscription);
				$this->Session->setFlash(__('<p class="text-center alert alert-danger">'.$subscription.'</p>'), 'admin_flash_success');
				$this->redirect(array('controller' => '/stripe_payments', 'action' => 'index',$user_id)); 
			}
		 	



			/*** Create Invoice in stripe Account ***/

			 $data = array(

				'customer' => $customer_id

			);

			$invoice = $this->Stripe->invoiceCreate($data);

			$invoive = json_decode($invoice->__toJSON());

			$invoice_id = $invoice->data[0]->id;







			/*** Retrive Data From Server ***/



			$customer = $this->Stripe->customerRetrieve($customer_id);

			$charge = $this->Stripe->chargeRetrieve($transactionId);

			$invoice = $this->Stripe->invoiceRetrieve($invoice_id);



			$invoice_decode = json_decode($invoice->__toJSON());

			$customer =  substr($customer,22);

			$customer_decode = json_decode($customer);

			$charge = substr($charge,20);

			$charge_decode = json_decode($charge);





			/*** Get Invoice URL and PDF from Stripe to customer***/



			$invoice_data = [

				'invoice_url' => $invoice_decode->hosted_invoice_url,

				'invoice_pdf' => $invoice_decode->invoice_pdf

			];




			/*** Get Customer Details and Put into Database ***/



			$customer_data =[

				'user_id'=>$user_id,

				'customer_id'=>$customer_decode->id,

				'name'=>$name,

				'email'=>$customer_decode->email,

				'customer_created'=>gmdate("d-m-Y",$customer_decode->created)

			];

			$this->StripeCustomerDetails->save($customer_data);



			/*** Get Card Details and Put into Database ***/



			$card_data = [

				'user_id'=>$user_id,

				'card_id'=>$customer_decode->sources->data[0]->id,

				'customer_id'=>$customer_decode->sources->data[0]->customer,

				'card_brand'=>$customer_decode->sources->data[0]->brand,

				'card_country'=>$customer_decode->sources->data[0]->country,

				'card_exp_month'=>$customer_decode->sources->data[0]->exp_month,

				'card_exp_year'=>$customer_decode->sources->data[0]->exp_year,

				'fingerprint'=>$customer_decode->sources->data[0]->fingerprint,

				'funding'=>$customer_decode->sources->data[0]->funding,

				'card_last4_digit'=>$customer_decode->sources->data[0]->last4

			];



			$this->StripeCardDetails->save($card_data);

			

			/*** Get Subscription Details and Put into Database ***/



		 	$subscription_data = [

		 		'user_id'=>$user_id,

				'customer_id'=>$customer_decode->subscriptions->data[0]->customer,

				'subscription_id'=>$customer_decode->subscriptions->data[0]->id,

				'subscription_billing'=>$customer_decode->subscriptions->data[0]->billing,

				'subscription_billing_cycle_anchor'=>gmdate("d-m-Y",$customer_decode->subscriptions->data[0]->billing_cycle_anchor),

				'subscription_created'=>gmdate("d-m-Y",$customer_decode->subscriptions->data[0]->created),

				'subscription_current_period_end'=>gmdate("d-m-Y",$customer_decode->subscriptions->data[0]->current_period_end),

				'subscription_current_period_start'=>gmdate("d-m-Y",$customer_decode->subscriptions->data[0]->current_period_start)

			];

			$this->StripeSubscriptionDetails->save($subscription_data);



			/*** Get Plan Details and Put into Database ***/



		 	$plan_data = [

		 		'user_id'=>$user_id,

				'plan_id'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->id,

				'plan_amount'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->amount,

				'plan_billing_scheme'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->amount,

				'plan_created'=>gmdate("d-m-Y",$customer_decode->subscriptions->data[0]->items->data[0]->plan->created),

				'plan_currency'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->currency,

				'plan_interval'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->interval,

				'plan_nickname'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->nickname,

				'plan_product_id'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->product,

				'plan_trial_period_days'=>$customer_decode->subscriptions->data[0]->items->data[0]->plan->trial_period_days

			];

			$this->StripePlanDetails->save($plan_data);



			/*** Get Transaction Details and Put into Database ***/



			$transaction_data = [

				'transaction_id'=>$charge_decode->id,

				'user_id'=>$user_id,

				'transaction_amount'=>$charge_decode->amount,

				'transaction_amount_refunded'=>$charge_decode->amount_refunded,

				'transaction_balance_transaction'=>$charge_decode->balance_transaction,

				'transaction_created'=>gmdate("d-m-Y",$charge_decode->created),

				'transaction_currency'=>$charge_decode->currency,

				'description'=>$charge_decode->description,

				'transaction_seller_message'=>$charge_decode->outcome->seller_message,

				'transaction_type'=>$charge_decode->outcome->type,

				'transaction_status'=>$charge_decode->status,

				'invoice_url'=>$invoice_decode->hosted_invoice_url,

				'invoice_pdf'=>$invoice_decode->invoice_pdf

			];

			$this->StripeTransactionDetails->save($transaction_data);



			$success_status = ($charge_decode->status == 'succeeded'?'1':'0');



			// print_r($success_status);die;

			$subscription_end_date = gmdate("Y-m-d",$customer_decode->subscriptions->data[0]->current_period_end);

			$get_user_data = $this->User->find('first', array('conditions' => array('email' => $email)));

			// pr($get_user_data['User']['id']);die;

			$this->User->id = $get_user_data['User']['id'];                

			$this->User->set(array('stripe_payment_status'=>$success_status,'subscription_current_period_end'=>$subscription_end_date,'subscription_period_end'=>$subscription_end_date,'stripe_plan_status'=>1));                

			$this->User->save();



			$this->set(compact('customer_data','transaction_data','invoice_data','customer_decode','name','user_image','user_id'));

			$this->set('title_for_layout', __('Payment Success Page', true));

	    	$this->layout = "app_dashboard";

	    	$url = array( 'controller' => '../users', 'action' => 'payment_success', $user_id);
			$this->set( 'url', $url );
			$this->header( "refresh:5; url='".Router::url( $url )."'" );
		}

	}

}