<?php

/**
 * Holiday Controller
 *
 * PHP version 5.4
 *
 */
class HolidaysController extends AppController{

    /**
     * Holiday Controller
     *
     * @var string
     * @access public
     */
    public $name = 'Holidays';
    public $components = array(
        'General', 'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('Holiday');

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
        $this->loadModel('Holiday');
    }

    public function beforeFilter(){
    	
        parent::beforeFilter();
        $this->loadModel('Holiday');
		$this->Auth->allow('add_holiday','holiday_list','delete_holiday','edit_holiday');
        date_default_timezone_set("Asia/Tokyo");
    }


    /*************************Holiday List*********************************/

    function holiday_list(){

        $this->loadModel("Holiday");
        $user_id = isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : '';
        
        if(!empty($user_id)){
            $data = $this->Holiday->find('all',array('conditions'=>array( 'Holiday.user_id'=>$user_id), 'order' => array('Holiday.modified' => 'DESC')));
            if(!$data){
                // $this->Session->setFlash(__('This Holiday not Exist', true),'flash_error');
            }else{
                if(!empty($data)){
                    $i=0;
                    foreach ($data as $key => $value) {
                      $customerData['Holiday'][$i] = $value['Holiday'];
                        $i++;
                    }

                }else{
                    // $customerData[$i]['Holiday']['msg'] = 'レコードが見つかりませんでした。';
                    // $customerData[$i]['Holiday']['msg1'] = 'No Record Found.';
                    // $customerData[$i]['Holiday']['status'] = 'error';

                }
            }
        }else{
            // $customerData[$i]['Holiday']['msg1'] = '商品は存在しません.';
            //  $customerData[$i]['Holiday']['msg'] = 'Holiday does not exist.';
            // $customerData[$i]['Holiday']['status'] = 'error';
            
        }
        // pr($customerData);die;
        $this->set(compact('customerData'));
        $this->layout = "dashboard";
    }

    /*************************Add Holiday*********************************/

    function add_holiday(){
        
        $this->loadModel("Holiday");
        if(!empty($this->request->data)){

            $this->Holiday->set($this->request->data['Holiday']);
            $this->Holiday->setValidation('add_holiday');

            $id = (isset($this->request->data['Holiday']['id']) ? $this->request->data['Holiday']['id'] : '');
            $user_id = (isset($this->request->data['Holiday']['user_id']) ? $this->request->data['Holiday']['user_id'] : '');
            $title = (isset($this->request->data['Holiday']['title']) ? $this->request->data['Holiday']['title'] : '');
            $date = (isset($this->request->data['Holiday']['date']) ? $this->request->data['Holiday']['date'] : '');
       
            $holidayExist = $this->Holiday->find('first', array('conditions'=>array('Holiday.title'=>$title)));
            
            if($holidayExist){
                $this->Session->setFlash(__('This Holiday already Exist', true),'flash_error');
            }
            
            $holiday['Holiday']['user_id'] =isset($user_id) ? $user_id : '';
            $holiday['Holiday']['title'] =isset($title) ? $title : '';
            $holiday['Holiday']['date'] =isset($date) ? $date : '';
        
       
            if($this->Holiday->saveAll($holiday)){
                $this->Session->setFlash(__('The Holiday information has been updated successfully', true), 'flash_success');
                $this->redirect(array('action' => 'holiday_list'));
                
            }else{
                $this->Session->setFlash(__('The Holiday could not be saved. Please, try again.', true), 'flash_error');
            }
        }
        $this->set('title_for_layout', __('Holiday', true));
        $this->layout = "dashboard";
    }

    /*************************Edit Holiday*********************************/

    function edit_holiday($id = null){
        
        $this->loadModel("Holiday");
        if(!empty($this->request->data)){

            $this->Holiday->set($this->request->data['Holiday']);
            $this->Holiday->setValidation('add_holiday');

            $id = (isset($this->request->data['Holiday']['id']) ? $this->request->data['Holiday']['id'] : '');
            $user_id = (isset($this->request->data['Holiday']['user_id']) ? $this->request->data['Holiday']['user_id'] : '');
            $title = (isset($this->request->data['Holiday']['title']) ? $this->request->data['Holiday']['title'] : '');
            $date = (isset($this->request->data['Holiday']['date']) ? $this->request->data['Holiday']['date'] : '');

        
            $holiday['Holiday']['id'] = isset($id) ? strtolower($id) : '';
            $holiday['Holiday']['user_id'] =isset($user_id) ? $user_id : '';
            $holiday['Holiday']['title'] =isset($title) ? $title : '';
            $holiday['Holiday']['date'] =isset($date) ? $date : '';
            
           
            if($this->Holiday->saveAll($holiday)){
                $this->Session->setFlash(__('The Holiday information has been updated successfully', true), 'flash_success');
                $this->redirect(array('action' => 'holiday_list'));
                
            }else{
                $this->Session->setFlash(__('The Holiday could not be saved. Please, try again.', true), 'flash_error');
            }
        }else{
            $this->request->data = $this->Holiday->read(null, $id);
        }
        $this->set('title_for_layout', __('Holiday', true));
        $this->layout = "dashboard";
    }

    /*************************Delete Holiday*********************************/

    function delete_holiday($id = null){
        $this->loadModel('Holiday');
        $id = isset($id) ? $id : '';
        if(!empty($id)){
            if($this->Holiday->delete($id, true)){
                $this->Session->setFlash(__('Holiday deleted successfully', true), 'flash_success');
                $this->redirect($this->referer());
            }else{
                $this->Session->setFlash(__('Holiday was not deleted', true), 'flash_error');
                $this->redirect($this->referer());
            }
        }else{
            $this->Session->setFlash(__('Holiday does not exist.', true),'flash_error');
            $this->redirect($this->referer());
        }
    }
}