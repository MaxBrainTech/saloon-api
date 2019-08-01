<?php

/**
 * SaleUsers Controller
 *
 * PHP version 5.4
 *
 */
class SaleUsersController extends AppController{

    /**
     * SaleUsers Controller
     *
     * @var string
     * @access public
     */
    public $name = 'SaleUsers';
    public $components = array(
         'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('SaleUser');

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
        $this->loadModel('SaleUser');
        $this->Auth->allow('*');
        $this->RequestHandler->addInputType('json', array('json_decode', true));
    }








    /** Sale User Login Function Start **/
    
    public function login() {

        $this->layout = 'sale_user_login';

        /** if session set then goin to login page Start **/
        $email = $this->Session->read('saleuser.SaleUser.email');
        if(isset($email) && !empty($email)){
            $this->redirect(array('controller'=>'sale_users','action'=>'dashboard'));
        }
        /** if session set then goin to login page End **/

        if($this->request->is('post')) {
            
            if (!empty($this->request->data)){

                $this->SaleUser->set($this->request->data['SaleUser']);
                $this->SaleUser->setValidation('login');

                if($this->SaleUser->validates()) {

                    $password = Security::hash($this->request->data['SaleUser']['password'], null, true);

                    $saleUserExist = $this->SaleUser->find('first', array('conditions' => array('email' => $this->request->data['SaleUser']['email'], 'password' => $password, 'roll_id' => "1", 'status'=>Configure::read('App.Status.active'))));

                    if (isset($saleUserExist['SaleUser']['email']) && !empty($saleUserExist['SaleUser']['email'])){
                        $this->Session->write('saleuser', $saleUserExist);
                        $this->redirect(array('controller'=>'sale_users','action'=>'dashboard'));
                    }else{
                        $this->Session->setFlash(__('<div class="alert alert-danger text-center">Worng email or password, try again</div>'));
                    }
                }else{
                    $this->Session->setFlash(__('<div class="alert alert-danger text-center">Invalid email or password, try again</div>'));
                }
            }
        }
    }

    /** Sale User Login Function End **/








    /** Sale User Logout Function Start **/
    
    public function logout() {
        $this->Session->destroy();
        $this->redirect(array('controller'=>'sale_users','action'=>'login'));
    }

    /** Sale User Logout Function End **/







    /** Sale User Dashboard Function Start **/
    
    public function dashboard() {
        
        $this->loadModel('SaleUser');
        $email = $this->Session->read('saleuser.SaleUser.email');

        /** if session not set then goin to login page Start **/
        if(!isset($email) && empty($empty)){
            $this->redirect(array('controller'=>'sale_users','action'=>'login'));
        }
        /** if session not set then goin to login page End **/

        // $saleUserData = $this->SaleUser->find('first', array('conditions' => array('SaleUser.email' => $email)));

        // $this->set(compact('saleUserData'));
        $this->set('title_for_layout', __('Admin Dashboard', true));
        $this->layout = "sale_user_dashboard";

    }

    /** Sale User Dashboard Function End **/

    public function sales_admin_list(){

        $this->loadModel('SaleUser');

        $email = $this->Session->read('saleuser.SaleUser.email');

        /** if session not set then goin to login page Start **/
        if(!isset($email) && empty($empty)){
            $this->redirect(array('controller'=>'sale_users','action'=>'login'));
        }
        /** if session not set then goin to login page End **/

        $saleUserData = $this->SaleUser->find('first', array('conditions' => array('SaleUser.email' => $email,'SaleUser.roll_id' => "1",)));

        $this->set(compact('saleUserData'));

        $this->set('title_for_layout', __('Admin List', true));
        $this->layout = "sale_user_dashboard";
    }

    public function sales_admin_edit($id = null){

        $this->loadModel('SaleUser');

        $this->SaleUser->id = $id;
        
        $email = $this->Session->read('saleuser.SaleUser.email');

        /** if session not set then goin to login page Start **/
        if(!isset($email) && empty($empty)){
            $this->redirect(array('controller'=>'sale_users','action'=>'login'));
        }
        /** if session not set then goin to login page End **/

        if(!empty($this->request->data)) {

            if ($this->SaleUser->save($this->request->data)) {
                    
                $this->Session->setFlash(__('The SaleUser Admin information has been updated successfully', true), 'admin_flash_success');
                $this->redirect(array('action' => 'sales_admin_list'));
            } 
            else 
            {
                $this->Session->setFlash(__('The SaleUser Admin could not be saved. Please, try again.', true), 'admin_flash_error');
            }
        } 
        $this->request->data = $this->SaleUser->read(null, $id);
        // $this->set(compact('saleUserData'));
        $this->set('title_for_layout', __('Admin Edit', true));
        $this->layout = "sale_user_dashboard";
    }


    public function sales_admin_change_password($id = null){
        $this->loadModel('SaleUser');

        
        $email = $this->Session->read('saleuser.SaleUser.email');

        /** if session not set then goin to login page Start **/
        if(!isset($email) && empty($empty)){
            $this->redirect(array('controller'=>'sale_users','action'=>'login'));
        }
        /** if session not set then goin to login page End **/

        if(!empty($this->request->data)) {

            $password = Security::hash($this->request->data['SaleUser']['password'], null, true);
            $this->SaleUser->id = $this->request->data['SaleUser']['id'];
       
            if ($this->SaleUser->saveField("password", $password)) {
                    
                $this->Session->setFlash(__('The SaleUser Admin information has been updated successfully', true), 'admin_flash_success');
                $this->redirect(array('action' => 'sales_admin_list'));
            } 
            else 
            {
                $this->Session->setFlash(__('The SaleUser Admin could not be saved. Please, try again.', true), 'admin_flash_error');
            }
        } 

        $this->set('title_for_layout', __('Admin Password Change', true));
        $this->set('id',$id);
        $this->layout = "sale_user_dashboard";
    }

    public function sales_user_list(){

        $this->loadModel('SaleUser');

        $email = $this->Session->read('saleuser.SaleUser.email');

        /** if session not set then goin to login page Start **/
        if(!isset($email) && empty($empty)){
            $this->redirect(array('controller'=>'sale_users','action'=>'login'));
        }
        /** if session not set then goin to login page End **/

        $saleUserData = $this->SaleUser->find('all', array('conditions' => array('SaleUser.status' => "1", 'SaleUser.roll_id != ' => "1")));

        $this->set(compact('saleUserData'));
        $this->set('title_for_layout', __('Sales user List', true));
        $this->layout = "sale_user_dashboard";
    }

    public function sales_user_list_view($id = null){
        /** Load Model Start **/
         $this->loadModel('SaleUser');
        /** Load Model End **/

        $kitchenData = $this->SaleUser->find('first', array('conditions' => array('SaleUser.id'=>$id,)));
        $data = $kitchenData['SaleUser'];
        $this->set('title_for_layout', __('Sales User Details', true));
        $this->set(compact('data'));
        $this->layout = "sale_user_dashboard";
    }

    public function sales_user_list_delete($id = null){

        /** Load Model Start **/
         $this->loadModel('SaleUser');
        /** Load Model End **/

        $id = isset($id) ? $id : '';
        if(!empty($id)){
            if($this->SaleUser->delete($id, true)){
                $this->Session->setFlash(__('SaleUser deleted successfully'), 'admin_flash_success');
                $this->redirect($this->referer());
            }else{
                $this->Session->setFlash(__('SaleUser was not deleted', 'admin_flash_error'));
                $this->redirect($this->referer());
            }
        }else{
            $this->Session->setFlash(__('SaleUser does not exist.', 'admin_flash_error'));
            $this->redirect($this->referer());
        }

    } 
}