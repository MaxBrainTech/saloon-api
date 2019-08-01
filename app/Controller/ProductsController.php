<?php

/**
 * Product Controller
 *
 * PHP version 5.4
 *
 */
class ProductsController extends AppController{

    /**
     * Product Controller
     *
     * @var string
     * @access public
     */
    public $name = 'Products';
    public $components = array(
        'General', 'Upload'
		//, 'Twitter'
    );
	
    public $helpers = array('General','Autosearch','Js');
    public $uses = array('Product');

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
        $this->loadModel('Product');
    }

    public function beforeFilter(){
    	
        parent::beforeFilter();
        $this->loadModel('Product');
		$this->Auth->allow('add_product','product_list','delete_product','edit_product');
        date_default_timezone_set("Asia/Tokyo");
    }


    /*************************Product List*********************************/

    function product_list(){

        $this->loadModel("Product");
        $user_id = isset($_SESSION['User']['id']) ? $_SESSION['User']['id'] : '';
        
        if(!empty($user_id)){
            $data = $this->Product->find('all',array('conditions'=>array( 'Product.user_id'=>$user_id), 'order' => array('Product.modified' => 'DESC')));
            if(!$data){
                $this->Session->setFlash(__('This Product not Exist', 'flash_error'));
            }else{
                if(!empty($data)){
                    $i=0;
                    foreach ($data as $key => $value) {
                      $customerData['Product'][$i] = $value['Product'];
                        $i++;
                    }

                }else{
                    $customerData[$i]['Product']['msg'] = 'レコードが見つかりませんでした。';
                    $customerData[$i]['Product']['msg1'] = 'No Record Found.';
                    $customerData[$i]['Product']['status'] = 'error';

                }
            }
        }else{
            $customerData[$i]['Product']['msg1'] = '商品は存在しません.';
            $customerData[$i]['Product']['msg'] = 'Product does not exist.';
            $customerData[$i]['Product']['status'] = 'error';
            
        }
        $this->set(compact('customerData'));
        $this->layout = "dashboard";
    }

    /*************************Add Product*********************************/

    function add_product(){
        
        $this->loadModel("Product");
        if(!empty($this->request->data)){

            $this->Product->set($this->request->data['Product']);
            $this->Product->setValidation('add_product');

            $id = (isset($this->request->data['Product']['id']) ? $this->request->data['Product']['id'] : '');
            $user_id = (isset($this->request->data['Product']['user_id']) ? $this->request->data['Product']['user_id'] : '');
            $product_name = (isset($this->request->data['Product']['product_name']) ? $this->request->data['Product']['product_name'] : '');
            $product_purchase_price = (isset($this->request->data['Product']['product_purchase_price']) ? $this->request->data['Product']['product_purchase_price'].'円' : '');
            $product_stock = (isset($this->request->data['Product']['product_stock']) ? $this->request->data['Product']['product_stock'] : '');
            $product_sale_price = (isset($this->request->data['Product']['product_sale_price']) ? $this->request->data['Product']['product_sale_price'].'円' : '');

       
            $productExist = $this->Product->find('first', array('conditions'=>array('Product.product_name'=>$product_name)));
            
            if($productExist){
                $this->Session->setFlash(__('This Product already Exist', 'flash_error'));
            }
   
            $product['Product']['user_id'] = isset($user_id) ? strtolower($user_id) : '';
            $product['Product']['product_name'] = isset($product_name) ? $product_name : '';
            $product['Product']['product_stock'] = isset($product_stock) ? $product_stock : '';
            $product['Product']['product_sale_price'] = isset($product_sale_price) ? $product_sale_price : '';
            $product['Product']['product_purchase_price'] = isset($product_purchase_price) ? $product_purchase_price : '';
            $product['Product']['product_sale_quantity'] = isset($product_sale_quantity) ? $product_sale_quantity : '';
            $product['Product']['product_purchase_quantity'] = isset($product_purchase_quantity) ? $product_purchase_quantity : '';
            $product['Product']['status'] = 1;
        
       
            if($this->Product->saveAll($product)){
                $this->Session->setFlash(__('The Product information has been updated successfully', true), 'admin_flash_success');
                $this->redirect(array('action' => 'product_list'));
                
            }else{
                $this->Session->setFlash(__('The Product could not be saved. Please, try again.', true), 'admin_flash_error');
            }
        }
        $this->set('title_for_layout', __('Product', true));
        $this->layout = "dashboard";
    }

    /*************************Edit Product*********************************/

    function edit_product($id = null){
        
        $this->loadModel("Product");
        if(!empty($this->request->data)){

            $this->Product->set($this->request->data['Product']);
            $this->Product->setValidation('add_product');

            $id = (isset($this->request->data['Product']['id']) ? $this->request->data['Product']['id'] : '');
            $user_id = (isset($this->request->data['Product']['user_id']) ? $this->request->data['Product']['user_id'] : '');
            $product_name = (isset($this->request->data['Product']['product_name']) ? $this->request->data['Product']['product_name'] : '');
            $product_purchase_price = (isset($this->request->data['Product']['product_purchase_price']) ? $this->request->data['Product']['product_purchase_price'].'円' : '');
            $product_stock = (isset($this->request->data['Product']['product_stock']) ? $this->request->data['Product']['product_stock'] : '');
            $product_sale_price = (isset($this->request->data['Product']['product_sale_price']) ? $this->request->data['Product']['product_sale_price'].'円' : '');

        
            $product['Product']['id'] = isset($id) ? strtolower($id) : '';
            $product['Product']['user_id'] = isset($user_id) ? strtolower($user_id) : '';
            $product['Product']['product_name'] = isset($product_name) ? $product_name : '';
            $product['Product']['product_stock'] = isset($product_stock) ? $product_stock : '';
            $product['Product']['product_sale_price'] = isset($product_sale_price) ? $product_sale_price : '';
            $product['Product']['product_purchase_price'] = isset($product_purchase_price) ? $product_purchase_price : '';
            $product['Product']['product_sale_quantity'] = isset($product_sale_quantity) ? $product_sale_quantity : '';
            $product['Product']['product_purchase_quantity'] = isset($product_purchase_quantity) ? $product_purchase_quantity : '';
            $product['Product']['status'] = 1;
            
           
            if($this->Product->saveAll($product)){
                $this->Session->setFlash(__('The Product information has been updated successfully', true), 'admin_flash_success');
                $this->redirect(array('action' => 'product_list'));
                
            }else{
                $this->Session->setFlash(__('The Product could not be saved. Please, try again.', true), 'admin_flash_error');
            }
        }else{
            $this->request->data = $this->Product->read(null, $id);
        }
        $this->set('title_for_layout', __('Product', true));
        $this->layout = "dashboard";
    }

    /*************************Delete Product*********************************/

    function delete_product($id = null){
        $this->loadModel('Product');
        $id = isset($id) ? $id : '';
        if(!empty($id)){
            if($this->Product->delete($id, true)){
                $this->Session->setFlash(__('Product deleted successfully'), 'flash_success');
                $this->redirect($this->referer());
            }else{
                $this->Session->setFlash(__('Product was not deleted', 'flash_error'));
                $this->redirect($this->referer());
            }
        }else{
            $this->Session->setFlash(__('Product does not exist.', 'flash_error'));
            $this->redirect($this->referer());
        }
    }
}