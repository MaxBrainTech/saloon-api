<?php
/**
 * UserCategories Controller
 *
 * PHP version 5.4
 *
 */
class BudgetsController extends AppController {

	/**
	 * Customer Form Controller
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Budgets';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$user_id = $this->Auth->User('id');
	}


      function add_budget($testData = null){
          
        $UserCategory = $this->loadModel("UserCategory");
        $category_name = $this->UserCategory->get_category_name($this->request->data['category_id']);
        $this->loadModel("Budget");
        $this->loadModel("UserCategory");
        $categoryBudgetData = $categoryBudget = array();
        $user_id = $this->Auth->User('id'); 
        $date = isset($this->request->data['date']) ? $this->request->data['date'] : ''; 
        if(!empty($date)){
            $month_date =  date( 'Y-m', strtotime( $date ) );
        }else{
            $month_date = '';
        }
        if($this->request->is('post') ){
            // $conditions = array('Budget.user_id' =>$user_id, 'Budget.month_date'=>$month_date);
            // $this->Budget->deleteAll($conditions);

            $categoryBudgetData =  array();
            $categoryBudgetData['Budget']['user_id'] = $user_id;
            $categoryBudgetData['Budget']['user_category_id'] = $this->request->data['category_id'];
            $categoryBudgetData['Budget']['category_name'] = $category_name;
            $categoryBudgetData['Budget']['month_date'] = $month_date;
            $categoryBudgetData['Budget']['budget'] = $this->request->data['price'];
            $categoryBudgetData['Budget']['status'] = Configure::read('App.Status.active');
            $this->Budget->saveAll($categoryBudgetData);

            // $userCategoryData['UserCategory']['id'] = $value['user_category_id'];
            // $userCategoryData['UserCategory']['modified'] = date('Y-m-d H:i:s');
            // $this->UserCategory->saveAll($userCategoryData); 
           
            $categoryBudget['msg'] = 'Category budget have added successfully.';
            $categoryBudget['status'] = 'success';
        }else{
            $categoryBudget['msg'] = 'please enter user detail.';
            $categoryBudget['status'] = 'error';
        }
       print_r($categoryBudget);
        
    } 

     /**************************************************************************
     * NAME: budget_list
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {"$user_id" : 205}
     * Returns:
     *
     * Globals:
     *
     * Design Document Reference:
     *
     * Author: Mahendra Tripathi
     *
     * Assumptions and Limitation: - Client requirements not fixed and already changed many times.
     *
     * Exception Processing:
     *
     * REVISION HISTORY
     * Date: By: Description:
     *
     *********************************************************************/
    
    
      function budget_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    
        
        $decoded = json_decode($data, true); 
        $this->loadModel("Budget");
        $this->loadModel("UserCategory");
        $categoryBudget = array();
        $user_id = $this->Auth->User('id');// isset($decoded['user_id']) ? $decoded['user_id'] : ''; 
        $date = date( 'Y-m-d' ) ;//isset($decoded['date']) ? $decoded['date'] : '';

        $conditions["UserCategory.user_id"] = $user_id;
        $conditions["UserCategory.parent_id"] = Configure::read('App.Status.inactive');
        $conditions["UserCategory.status"] = Configure::read('App.Status.active');
        $user_category = $this->UserCategory->find('list', array('conditions' => $conditions));

        //echo '<pre>';
        //print_r($category_budget);die;
        if(!empty($date)){
            $month_date =  date( 'Y-m', strtotime( $date ) );
        }else{
            $month_date = '';
        }
        $this->Budget->bindModel(array('belongsTo' => array('UserCategory')));
        $conditions = array('Budget.user_id' =>$user_id, 'Budget.month_date'=>$month_date, 'Budget.status' => Configure::read('App.Status.active'));
        $data = $this->Budget->find('all', array('conditions' => $conditions));
        $i =0;
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $categoryBudget['Budget'][$i]['id'] = $value['Budget']['id'];
                $categoryBudget['Budget'][$i]['user_category_id'] = $value['Budget']['user_category_id'];
               $categoryBudget['Budget'][$i]['category_name'] = $value['Budget']['category_name'];
                // $categoryBudget['Budget'][$i]['category_name'] = $value['UserCategory']['japanese_name'];
                $categoryBudget['Budget'][$i]['budget'] = $value['Budget']['budget'];
                $categoryBudget['Budget'][$i]['budget'] = $value['Budget']['budget'];
                $categoryBudget['Budget'][$i]['image'] = $this->get_category_image($value['UserCategory']['parent_id']);
                $i++;
            }
        }else{
            $categoryBudget['Budget']= array();
            $categoryBudget['status'] = 'error';
        }
        // pr($categoryBudget);die;

        $categoryBudget['user_category'] = $user_category;

        $this->layout = 'dashboard';

        $this->set(compact('categoryBudget'));
        
    } 


    function delete_budget($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('Budget');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->Budget->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => 'Category Budget deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Category Budget deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Category Budget does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Budget->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_budget";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
    } 


    function get_category_image($id =null){
        $this->loadModel("UserCategory");
        if(!empty($id)){    
            $data = $this->UserCategory->find('first', array('conditions' => array('UserCategory.id' =>$id)));
            if(isset($data['UserCategory']['image']))
                return $data['UserCategory']['image'];
            else
                return '';
        }else{
            return '';
        }    
       
    }


    public function get_sub_categories(){
        $category_id = $this->request->data['id'];
        $user_id = $this->Auth->User('id');
        $this->loadModel("UserCategory");
        $subCategoryData = $this->UserCategory->find('list', array('conditions' => array('UserCategory.user_id' =>$user_id,'UserCategory.parent_id' =>$category_id, 'UserCategory.status' =>Configure::read('App.Status.active'))));
        $res = "<option>Select Sub-category</option>";
        foreach ($subCategoryData as $key => $value) {
            $res = $res .  "<option value=$key>$value</option>";;
        }
        echo $res;
        
    }


}
