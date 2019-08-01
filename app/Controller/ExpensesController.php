<?php
/**
 * UserCategories Controller
 *
 * PHP version 5.4
 *
 */
class ExpensesController extends AppController {

	/**
	 * Customer Form Controller
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'Expenses';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$user_id = $this->Auth->User('id');
	}


    
        
    
    function add_expense(){

        // print_r($this->request->data);die;
        
        $this->loadModel("UserCategory");
        $this->loadModel("Expense");
        
        $user_id = $this->Auth->User('id');
        $decoded = $this->request->data;

       
        if(isset($decoded['id']) && !empty($decoded['id'])){
            $expense['Expense']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        }
        if(isset($decoded['due_date']) && !empty($decoded['due_date'])){
            $month_date =  date( 'Y-m', strtotime($decoded['due_date']) );
        }else{
            $month_date = '';
        }
        $expense['Expense']['user_id'] = $user_id;
        $user_category_id = $expense['Expense']['user_category_id'] = isset($decoded['category_id']) ? $decoded['category_id'] : '';
        $expense['Expense']['price'] = isset($decoded['price']) ? $decoded['price'] : '';
        $expense['Expense']['due_date'] = isset($decoded['due_date']) ? $decoded['due_date'] : '';
        $expense['Expense']['month_date'] = $month_date;
        $expense['Expense']['is_fixed'] = isset($decoded['is_fixed']) ? $decoded['is_fixed'] : '0';
        $expense['Expense']['note'] = isset($decoded['note']) ? $decoded['note'] : '';
        $expense['Expense']['payment_type'] = isset($decoded['payment_type']) ? $decoded['payment_type'] : '';
        $expense['Expense']['status'] = Configure::read('App.Status.active');
        
       
        if($this->Expense->saveAll($expense)){

            $responseArr['expense_id'] = $this->Expense->id; 
            // $userCategoryData['UserCategory']['id'] = $user_category_id;
            // $userCategoryData['UserCategory']['modified'] = date('Y-m-d H:i:s');
            // $this->UserCategory->saveAll($userCategoryData); 
            $responseArr['status'] = 'success';
            $responseArr['msg'] = 'Expense added successfully.';
            // $this->redirect(array('action' => 'manual_expense_list'));
            // $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
        }
        

    }

/**************************************************************************
     * NAME: paid_expense
     * Description: All Service List .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example : {}
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
    
    
    function paid_expense(){
        
        $this->loadModel("Expense");
        $data = file_get_contents("php://input");
        
        if(isset($test_data)&&(!empty($test_data))){
            $testData = base64_decode($test_data);
            $data = $testData;
        }
        if(empty($data)){
            $data = json_encode($_GET);
        } 
        $decoded = json_decode($data, true);
        $is_expire = 0;
        $id = $this->Expense->id = $expense['Expense']['id'] = isset($decoded['id']) ? $decoded['id'] : '';
        $current_date = isset($decoded['date']) ? $decoded['date'] : '';
        $data = $this->Expense->find('first', array('conditions'=>array('Expense.id'=>$id)));
        if(isset($data ['Expense']['due_date'])){
            if(strtotime($current_date) > strtotime($data ['Expense']['due_date'])){
                $is_expire = 1;
            }else{
                $is_expire = 0;
            }
        }else{
            $is_expire = 0; 
        }   
        $expense['Expense']['is_paid'] =  '1';
        $expense['Expense']['is_expire'] = $is_expire;
       if( $this->Expense->saveAll($expense)){
            $responseArr['expense_id'] = $this->Expense->id; 
            $responseArr['status'] = 'success';
            $responseArr['msg'] = 'Expense paid successfully.';
          
            $jsonEncode = json_encode($responseArr);
            
        }else{
            $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Expense->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "paid_expense";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();

    }


    function fixed_expense_list($testData = null){
          
        $data = file_get_contents('php://input');
    
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        $user_id = $this->Auth->User('id');

        $decoded = json_decode($data, true); 
        $this->loadModel("Expense");
        $UserCategory = $this->loadModel("UserCategory");
        // $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $is_fixed = '1';//isset($decoded['is_fixed']) ? $decoded['is_fixed'] : '1';
        $date = date('Y-m-d') ;//isset($decoded['date']) ? $decoded['date'] : '0';
        if(!empty($date)){
            $month_date =  date('Y-m', strtotime($date) );
        }else{
            $month_date = '';
        }
        if(!empty($user_id)){
            $this->Expense->bindModel(array('belongsTo' => array('UserCategory')));
            $data = $this->Expense->find('all',array('conditions'=> array( 'Expense.user_id'=>$user_id, 'Expense.is_fixed'=>$is_fixed,  'Expense.month_date' =>$month_date), 'order' => array('Expense.user_category_id' => 'DESC')));
            
            $i=0;
            if(!empty($data)){
                $old_category_id = $totalExpense = $totalBudget = 0;
                foreach ($data as $key => $value) {
                    $category_id = $value['Expense']['user_category_id'];
                    $parent_id = $value['UserCategory']['parent_id'];
                    
                    $expenseData['Expense'][$i]['id'] = $value['Expense']['id'];
                    $expenseData['Expense'][$i]['name'] = $value['UserCategory']['name'];
                    $expenseData['Expense'][$i]['japanese_name'] = $value['UserCategory']['japanese_name'];;
                    $expenseData['Expense'][$i]['parent_category_name'] =  $this->UserCategory->get_category_name($parent_id);
                    $expenseData['Expense'][$i]['image'] = $this->get_category_image($parent_id);
                    $expenseData['Expense'][$i]['parent_category_japanese_name'] = $this->get_category_japanese_name($parent_id);
                    $expenseData['Expense'][$i]['due_date'] = date('dS', strtotime($value['Expense']['due_date']));
                    $expenseData['Expense'][$i]['note'] = $value['Expense']['note'];
                    $expenseData['Expense'][$i]['month_date'] = $value['Expense']['month_date'];
                    $expenseData['Expense'][$i]['payment_type'] = $value['Expense']['payment_type'];
                    $expenseData['Expense'][$i]['price'] = $value['Expense']['price'];
                    $expenseData['Expense'][$i]['is_paid'] = $value['Expense']['is_paid'];
                    $expenseData['Expense'][$i]['is_expire'] = $value['Expense']['is_expire'];
                    $i++;
                    
                }
               
            }else{
                $expenseData[$i]['Expense']['msg'] = 'レコードが見つかりませんでした。';
                $expenseData[$i]['Expense']['msg1'] = 'No Record Found.';
                $expenseData[$i]['Expense']['status'] = 'error';
            }
            // $jsonEncode = json_encode($expenseData);
           
        }else{
            $expenseData[$i]['Expense']['msg1'] = '商品は存在しません.';
            $expenseData[$i]['Expense']['msg'] = 'Expense does not exist.';
            $expenseData[$i]['Expense']['status'] = 'error';
            // $jsonEncode = json_encode($expenseData);
        }
        return $expenseData;
        // $log = $this->Expense->getDataSource()->getLog(false, false);
        // $recordData['RecordData']['name'] = "expense_list";
        // $recordData['RecordData']['query'] = json_encode($log);
        // $this->RecordData->saveAll($recordData);
        // echo  $jsonEncode;exit();
    }

    
    
      function manual_expense_list($testData = null){
          
        // $data = file_get_contents('php://input');
    
        // if(empty($data)){
        //     $data = json_encode($_GET);
        // }    

        $user_id = $this->Auth->User('id');

        // $decoded = json_decode($data, true); 
        $UserCategory = $this->loadModel("UserCategory");
        $this->loadModel("Budget");
        $this->loadModel("Expense");
        $user_id = $this->Auth->User('id');
        $is_fixed = '0';
        $date = date('Y-m-d') ;//isset($decoded['date']) ? $decoded['date'] : '0';
        if(!empty($date)){
            $month_date =  date('Y-m', strtotime($date) );
        }else{
            $month_date = '';
        }
        if(!empty($user_id)){
           
           $this->UserCategory->bindModel(
                            array('hasMany' => array(
                                'Expense' => array(
                                    'className' => 'Expense',
                                    'conditions' => array('Expense.user_id'=>$user_id, 'Expense.month_date'=>$month_date),
                                    'order' =>array('Expense.modified' =>'DESC')
                                  
                                )),
                                'hasOne' => array(
                                'Budget' => array(
                                    'className' => 'Budget',
                                    'conditions' => array('Budget.user_id' => $user_id, 'Budget.month_date' => $month_date),
                                    'order' =>array('Budget.modified' =>'DESC')
                              
                                )

                )));
            $data = $this->UserCategory->find('all',array('conditions'=> array( 'UserCategory.user_id'=>$user_id), 'order' =>array('UserCategory.modified'=>'DESC')));

                $i=0;
                if(!empty($data)){
                    $totalExpense = $totalBudget = 0;
                    foreach ($data as $key => $value) {

                        if(isset($value['Expense'][0]['user_category_id']) || isset($value['Expense']['user_category_id']) || isset($value['Budget']['user_category_id'])){

                            $parent_id = $value['UserCategory']['parent_id'];
                           
                            $expenseData['Expense'][$i]['name'] = $value['UserCategory']['name'];
                            $expenseData['Expense'][$i]['japanese_name'] = $value['UserCategory']['japanese_name'];
                            $expenseData['Expense'][$i]['parent_category_name'] = $this->UserCategory->get_category_name($parent_id);
                          //  $expenseData['Expense'][$i]['parent_category_japanese_name'] = $this->get_category_japanese_name($parent_id);
                            $expensePrice = $left_budget = 0;

                            foreach ($value['Expense'] as $expenseKey => $expenseValue) {
                                if(isset($expenseValue['price'])){
                                    $price = $this->priceChangeInt($expenseValue['price']);
                                    $expensePrice = ($expensePrice + $price);
                                }
                                $expenseData['Expense'][$i]['date'] = date('Y-m-d', strtotime($expenseValue['modified'])); 
                            }
                            $totalExpense = ($totalExpense + $expensePrice);
                            
                            $expenseData['Expense'][$i]['price'] = number_format($expensePrice).'円';
                            if(isset($value['Budget']['budget']) && !empty($value['Budget']['budget'])){
                                $expenseData['Expense'][$i]['budget'] = $value['Budget']['budget'];
                                $budget = $this->priceChangeInt($value['Budget']['budget']);
                                $left_budget = ($budget - $expensePrice);
                                $expenseData['Expense'][$i]['left_budget'] = number_format($left_budget).'円';
                                $totalBudget = ($totalBudget + $budget);
                                $expenseData['Expense'][$i]['date'] = date('Y-m-d', strtotime($value['Budget']['modified']));
                            }else{
                                $expenseData['Expense'][$i]['budget'] = number_format(0).'円';
                                $expenseData['Expense'][$i]['left_budget'] = number_format(0).'円';
                            }
                            $i++;
                        }
                    }
                    
                    $totalleftBudget = ($totalBudget - $totalExpense);
                    $expenseData['TotalExpense']['total_expense'] = number_format($totalExpense).'円'; 
                    $expenseData['TotalExpense']['total_budget'] = number_format($totalBudget).'円'; 
                    $expenseData['TotalExpense']['total_left_budget'] = number_format($totalleftBudget).'円'; 
                }else{
                    $expenseData[$i]['Expense']['msg'] = 'レコードが見つかりませんでした。';
                    $expenseData[$i]['Expense']['msg1'] = 'No Record Found.';
                    $expenseData[$i]['Expense']['status'] = 'error';
                }
                // $jsonEncode = json_encode($expenseData);
        }


        $this->loadModel("UserCategory");
        $conditions["UserCategory.user_id"] = $user_id;
        $conditions["UserCategory.parent_id"] = Configure::read('App.Status.inactive');
        $conditions["UserCategory.status"] = Configure::read('App.Status.active');
        // $conditions["OR"]['UserCategory.name LIKE'] = "%".$keyword."%";
        // $conditions["OR"]['UserCategory.japanese_name LIKE'] = "%".$keyword."%";
        $expenseData['user_category'] = $this->UserCategory->find('list', array('conditions' => $conditions));

        $expenseData['payment_type'] = Configure::read('App.Payment.Type');

        $expenseData['fixed_expense_list'] = $this->fixed_expense_list();

        $this->layout = 'dashboard';

        $this->set(compact('expenseData'));
// echo "<pre>";
//         print_r($expenseData);die;
       
        
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
    

/****************************************************************************************************************************************
     * NAME: delete_expense
     * Description: Register a user with .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
     Example :  
     
     
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
     *************************************************************************************************************************************/
    function delete_expense($test_data = null){
        
        $data = file_get_contents('php://input');
        
        if(empty($data)){
            $data = json_encode($_GET);
        }    

        if(isset($test_data)&&(!empty($test_data))){
            $data = json_encode($test_data);
        }
        $decoded = json_decode($data, true); 
        $this->loadModel('Expense');
        $id = isset($decoded['id']) ? $decoded['id'] : '';
        if(!empty($id)){
            if($this->Expense->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => 'Expense deleted successfully.' );
                $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Expense deleted error.'  );
                $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Expense does not exist.'  );
            $jsonEncode = json_encode($responseArr);
        }
        $log = $this->Expense->getDataSource()->getLog(false, false);
        $recordData['RecordData']['name'] = "delete_expense";
        $recordData['RecordData']['query'] = json_encode($log);
        $this->RecordData->saveAll($recordData);
        echo  $jsonEncode;exit();
       
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

    
    function get_category_japanese_name($id =null){
        $this->loadModel("UserCategory");
        if(!empty($id)){    
            $data = $this->UserCategory->find('first', array('conditions' => array('UserCategory.id' =>$id)));
            if(isset($data['UserCategory']['japanese_name']))
                return $data['UserCategory']['japanese_name'];
            else
                return '';
        }else{
            return '';
        }    
       
    }



}
