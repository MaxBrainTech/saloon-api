<?php
/**
 * Webhook Controller
 *
 * PHP version 5.4
 *
 */

class WebhooksController extends AppController {

	/**
     * Webhook Controller
     *
     * @var string
     * @access public
     */

	public $components = array('Stripe.Stripe');

	public function beforeFilter(){
        parent::beforeFilter();
        $this->RequestHandler->addInputType('json', array('json_decode', true));
        $this->Auth->allow('*');
    }

	public function index($id = null) {
        // echo $id;die;

        // Retrieve the request's body and parse it as JSON:
        // $input = @file_get_contents('php://input');
        // $event_json = json_decode($input, true);

        // pr($event_json);die;

        // try {
        //     // Check against Stripe to confirm that the ID is valid
            // $event_id = $event_json->id;
            Stripe::setApiKey("sk_test_NS4bCjK97mkNlwYO0SPVGtaU");
            $event = Stripe_Event::retrieve("evt_1E0NNJBk52NmlLWGQwDX3ec6");
            pr($event);die;
        // }
        // catch (Stripe_InvalidRequestError $e) {
        //     // If the event is invalid, log an error
        //     error_log($e->getMessage(),0);
        // }
        // Do something with $event_json

        http_response_code(200); // PHP 5.4 or greater

    }
}