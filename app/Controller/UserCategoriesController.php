<?php
/**
 * UserCategories Controller
 *
 * PHP version 5.4
 *
 */
class UserCategoriesController extends AppController {

	/**
	 * Customer Form Controller
	 *
	 * @var string
	 * @access public
	 */
	var	$name	=	'UserCategories';
	/*
	 * beforeFilter
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login');
		$user_id = $this->Auth->User('id');
	}


    function get_main_categories(){
        
        $user_id = $this->Auth->User('id');
        $user_id = isset($user_id) ? $user_id : ''; 
        $this->loadModel("UserCategory");
        $categoryArray =array();
        $i =0;
        if(!empty($user_id)){ 
            $conditions["UserCategory.user_id"] = $user_id;
            $conditions["UserCategory.parent_id"] = Configure::read('App.Status.inactive');
            $conditions["UserCategory.status"] = Configure::read('App.Status.active');
            $data = $this->UserCategory->find('all', array('conditions' => $conditions));
            
            foreach ($data  as $key => $value) {
                $category_id = $categoryArray['UserCategory'][$i]['id'] = $value['UserCategory']['id'];
                $categoryArray['UserCategory'][$i]['name'] = $value['UserCategory']['name'];
                $categoryArray['UserCategory'][$i]['japanese_name'] = $value['UserCategory']['japanese_name'];
                $categoryArray['UserCategory'][$i]['image'] = $value['UserCategory']['image'];
                $i++;
            }
        }else{
            $categoryArray['UserCategory'][$i]['msg'] = 'please enter user detail.';
            $categoryArray['UserCategory'][$i]['status'] = 'error';
        }    
        // echo '<pre>';
        // print_r($categoryArray);die;

        $this->layout = 'dashboard';

        $this->set(compact('categoryArray'));
    }

    
    function get_all_categories(){
        
        $user_id = $this->Auth->User('id');
        $user_id = isset($user_id) ? $user_id : ''; 
        $this->loadModel("UserCategory");
        $categoryArray =array();
        $i =0;
        if(!empty($user_id)){ 
            $conditions["UserCategory.user_id"] = $user_id;
            $conditions["UserCategory.parent_id"] = Configure::read('App.Status.inactive');
            $conditions["UserCategory.status"] = Configure::read('App.Status.active');
            $data = $this->UserCategory->find('all', array('conditions' => $conditions));
            
            foreach ($data  as $key => $value) {
                $category_id = $categoryArray['UserCategory'][$i]['id'] = $value['UserCategory']['id'];
                $categoryArray['UserCategory'][$i]['name'] = $value['UserCategory']['name'];
                $categoryArray['UserCategory'][$i]['japanese_name'] = $value['UserCategory']['japanese_name'];
                $categoryArray['UserCategory'][$i]['image'] = $value['UserCategory']['image'];
                $subCategoryData = $this->UserCategory->find('all', array('conditions' => array('UserCategory.user_id' =>$user_id,'UserCategory.parent_id' =>$category_id, 'UserCategory.status' =>Configure::read('App.Status.active'))));
                
                if(!empty($subCategoryData)){

                    $j =0;
                    foreach ($subCategoryData  as $subCategorykey => $subCategoryvalue) {
                        $categoryArray['UserCategory'][$i]['SubCategory'][$j]['id'] = $subCategoryvalue['UserCategory']['id'];
                        $categoryArray['UserCategory'][$i]['SubCategory'][$j]['name'] = $subCategoryvalue['UserCategory']['name'];
                        $categoryArray['UserCategory'][$i]['SubCategory'][$j]['japanese_name'] = $subCategoryvalue['UserCategory']['japanese_name'];
                        $categoryArray['UserCategory'][$i]['SubCategory'][$j]['image'] = $subCategoryvalue['UserCategory']['image'];
                            

                        $j++;
                    }
                }   

                $i++;
            }
        }else{
            $categoryArray['UserCategory'][$i]['msg'] = 'please enter user detail.';
            $categoryArray['UserCategory'][$i]['status'] = 'error';
        }    
        echo '<pre>';
        print_r($categoryArray);die;
    }


    public function get_sub_categories($category_id = null){
        $user_id = $this->Auth->User('id');
        $user_id = isset($user_id) ? $user_id : ''; 
        $this->loadModel("UserCategory");

        $conditions["UserCategory.id"] = $category_id;
        $main_category = $this->UserCategory->find('first', array('conditions' => $conditions));
        // print_r($data);die;
        // $subCategoryData = array();
        if(!empty($user_id)){
             $subCategoryData = $this->UserCategory->find('all', array('conditions' => array('UserCategory.user_id' =>$user_id,'UserCategory.parent_id' =>$category_id, 'UserCategory.status' =>Configure::read('App.Status.active')),'order'=>array('UserCategory.id' => 'DESC')));

            $j =0;
            foreach ($subCategoryData  as $subCategorykey => $subCategoryvalue) {
                $categoryArray['SubCategory'][$j]['id'] = $subCategoryvalue['UserCategory']['id'];
                $categoryArray['SubCategory'][$j]['name'] = $subCategoryvalue['UserCategory']['name'];
                $categoryArray['SubCategory'][$j]['japanese_name'] = $subCategoryvalue['UserCategory']['japanese_name'];
                $categoryArray['SubCategory'][$j]['image'] = $subCategoryvalue['UserCategory']['image'];
                    

                $j++;
            }
            // $categoryArray = array_reverse($categoryArray);
        }else{
            $categoryArray['msg'] = 'please enter user detail.';
            $categoryArray['status'] = 'error';
        }
        $categoryArray['main_category'] = $main_category;



        // echo '<pre>';
        // print_r($categoryArray);die;
        $this->layout = 'dashboard';

        $this->set(compact('categoryArray'));
    }


    function add_sub_category(){
        // print_r($this->request->data);die;
        $this->loadModel("Category");
        $this->loadModel("UserCategory");
        $user_id = $this->Auth->User('id');
        if(!empty($user_id)){
            if($this->request->is('post') ){
                if($this->request->data['sub_category_name'] != ''){
                    $userSubCategoryData =array();
                    $userSubCategoryData['UserCategory']['user_id'] = $user_id;
                    $userSubCategoryData['UserCategory']['parent_id'] = $this->request->data['parent_id'];
                    $userSubCategoryData['UserCategory']['name'] = $this->request->data['sub_category_name'];
                    $userSubCategoryData['UserCategory']['japanese_name'] = $this->request->data['sub_category_name'];
                    // $userSubCategoryData['UserCategory']['image'] = '';
                    // $userSubCategoryData['UserCategory']['status'] = $subCategoryvalue['Category']['status'];
                    // $this->UserCategory->saveAll($userSubCategoryData);

                    if($this->UserCategory->saveAll($userSubCategoryData)){
                        $responseArr['status'] = 'success';
                        $responseArr['msg'] = 'User Category added successfully.';                    
                    }else{
                        $responseArr['status'] = 'error';
                        $responseArr['msg'] = 'Error in saving data.'; 
                    }
                }else{

                    $responseArr['status'] = 'error';
                    $responseArr['msg'] = 'Sub Category could not be blank.'; 
                }
                
            }else{
                $responseArr['msg'] = 'please enter user detail.';
                $responseArr['status'] = 'error';
            }
        }else{
            $responseArr['msg'] = 'please enter user detail.';
            $responseArr['status'] = 'error';
        }

        $jsonEncode = json_encode($responseArr);
        echo $jsonEncode;
    }


    function edit_sub_category(){
        // print_r($this->request->data);die;
        $this->loadModel("Category");
        $this->loadModel("UserCategory");

        $user_id = $this->Auth->User('id');
        if(!empty($user_id)){
            if($this->request->is('post') ){
                if($this->request->data['sub_category_name'] != ''){ 
                    $userSubCategoryData =array();
                    $userSubCategoryData['UserCategory']['id'] = $this->request->data['edit_id'];
                   
                    // $userSubCategoryData['UserCategory']['user_id'] = $user_id;
                    // $userSubCategoryData['UserCategory']['parent_id'] = $this->request->data['parent_id'];
                    $userSubCategoryData['UserCategory']['name'] = $this->request->data['sub_category_name'];
                    $userSubCategoryData['UserCategory']['japanese_name'] = $this->request->data['sub_category_name'];
                    // $userSubCategoryData['UserCategory']['image'] = '';
                    // $userSubCategoryData['UserCategory']['status'] = $subCategoryvalue['Category']['status'];
                    // $this->UserCategory->saveAll($userSubCategoryData);

                    if($this->UserCategory->saveAll($userSubCategoryData)){
                        $responseArr['status'] = 'success';
                        $responseArr['msg'] = 'User Category Updated successfully.';                    
                    }else{
                        $responseArr['status'] = 'error';
                        $responseArr['msg'] = 'Error in updating data.'; 
                    }
                }else{

                    $responseArr['status'] = 'error';
                    $responseArr['msg'] = 'Sub Category could not be blank.'; 
                }
                
            }else{
                $responseArr['msg'] = 'please enter user detail.';
                $responseArr['status'] = 'error';
            }
        }else{
            $responseArr['msg'] = 'please enter user detail.';
            $responseArr['status'] = 'error';
        }

        $jsonEncode = json_encode($responseArr);
        echo $jsonEncode;
    }


    function add_user_category($testData = null){
          
        // $data = file_get_contents('php://input');
    
        // if(empty($data)){
        //     $data = json_encode($_GET);
        // }    

        $decoded = json_decode($data, true); 
        $this->loadModel("Category");
        $this->loadModel("UserCategory");
        $user_id = isset($decoded['user_id']) ? $decoded['user_id'] : '';
        $UserCategory  = array();
        if(!empty($user_id)){
            $data = $this->Category->find('all',array('conditions'=>array('Category.parent_id' =>Configure::read('App.Status.inactive'),  'Category.status'=>Configure::read('App.Status.active'))));
            
            
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $userCategoryData = array();
                    $category_id = $value['Category']['id'];
                    $userCategoryData['UserCategory']['user_id'] = $user_id;
                    $userCategoryData['UserCategory']['parent_id'] = $value['Category']['parent_id'];
                    $userCategoryData['UserCategory']['name'] = $value['Category']['name'];
                    $userCategoryData['UserCategory']['japanese_name'] = $value['Category']['japanese_name'];
                    $userCategoryData['UserCategory']['image'] = $value['Category']['image'];
                    $userCategoryData['UserCategory']['status'] = $value['Category']['status'];
                    $this->UserCategory->saveAll($userCategoryData);
                    $user_category_id = $this->UserCategory->id;
                    $subCategoryData = $this->Category->find('all', array('conditions' => array('Category.parent_id' =>$category_id, 'Category.status' =>Configure::read('App.Status.active'))));
                    if(!empty($subCategoryData)){
                        $userSubCategoryData =array();
                        $i =0;
                        foreach ($subCategoryData  as $subCategorykey => $subCategoryvalue) {

                            $userSubCategoryData[$i]['UserCategory']['user_id'] = $user_id;
                            $userSubCategoryData[$i]['UserCategory']['parent_id'] = $user_category_id;
                            $userSubCategoryData[$i]['UserCategory']['name'] = $subCategoryvalue['Category']['name'];
                            $userSubCategoryData[$i]['UserCategory']['japanese_name'] = $subCategoryvalue['Category']['japanese_name'];
                            $userSubCategoryData[$i]['UserCategory']['image'] = $subCategoryvalue['Category']['image'];
                            $userSubCategoryData[$i]['UserCategory']['status'] = $subCategoryvalue['Category']['status'];

                            $i++;
                        }
                        $this->UserCategory->saveAll($userSubCategoryData);
                    }   
                }
                
                $UserCategory['UserCategory']['msg'] = 'Categories have added successfully.';
                $UserCategory['UserCategory']['status'] = 'success';
            }else{
                $UserCategory['UserCategory']['msg'] = 'No Record Found.';
                $UserCategory['UserCategory']['status'] = 'error';
            }
             // $jsonEncode = json_encode($UserCategory);
            
        }else{
            $UserCategory['UserCategory']['msg'] = 'please enter user detail.';
            $UserCategory['UserCategory']['status'] = 'error';
            // $jsonEncode = json_encode($UserCategory);
        }
    } 


    function delete_category($parent_id = null, $id = null){
        
        $this->loadModel('UserCategory');
        $id = isset($id) ? $id : '';
        $user_id = $this->Auth->User('id');
        $this->UserCategory->bindModel(
                            array('hasMany' => array(
                                'Expense' => array(
                                    'className' => 'Expense',
                                    'conditions' => array('Expense.user_id'=>$user_id),
                                    'order' =>array('Expense.modified' =>'DESC')
                                  
                                )),
                                'hasOne' => array(
                                'Budget' => array(
                                    'className' => 'Budget',
                                    'conditions' => array('Budget.user_id' => $user_id),
                                    'order' =>array('Budget.modified' =>'DESC')
                              
                                )

                )));
        if(!empty($id)){
            if($this->UserCategory->delete($id, true)){
                $responseArr = array('status' => 'success', 'msg' => 'Category deleted successfully.' );
                $this->redirect(array('action' => 'get_sub_categories',$parent_id));
                // $jsonEncode = json_encode($responseArr);
            }else{
                $responseArr = array('status' => 'error', 'msg' => 'Category deleted error.'  );
                $this->redirect(array('action' => 'get_sub_categories',$parent_id));
                // $jsonEncode = json_encode($responseArr);
            }
        }else{
            $responseArr = array('status' => 'error', 'msg' => 'Category does not exist.'  );
            $this->redirect(array('action' => 'get_sub_categories',$parent_id));
            // $jsonEncode = json_encode($responseArr);
        }
       
    }

}
