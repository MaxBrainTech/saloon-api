<?php
/**
 * Templates Controller
 *
 * PHP version 5.4
 *
 */
class PagesController extends AppController{
	/**
     * Controller name
     *
     * @var string
     * @access public
     */
	var	$name	=	'Pages';
	
	public $helpers = array('General','Html');
	/*
	* beforeFilter
	* @return void
	*/
    public function beforeFilter() {
    	
        parent::beforeFilter();
        $this->Auth->allow('index','home','view','listing','contact_us','assign_permission','read_doc');
    }
	
	/*
	 * List all Pages in admin panel
	 */
	public function admin_index($defaultTab='All') {

		if(!isset($this->request->params['named']['Page'])){
			$this->Session->delete('AdminSearch');
			$this->Session->delete('Url');
		}
		//pr($this->Session->read('AdminSearch'));die;
		$filters_without_status = $filters = array();
		if($defaultTab!='All'){
			$filters[] = array('Page.status'=>array_search($defaultTab, Configure::read('Status')));
		}
		if(!empty($this->request->data)){
			$this->Session->delete('AdminSearch');
			$this->Session->delete('Url');
			App::uses('Sanitize', 'Utility');
			if(!empty($this->request->data['Page']['title'])){
				$title = Sanitize::escape($this->request->data['Page']['title']);
				$this->Session->write('AdminSearch.title', $title);
			}
				
			if(isset($this->request->data['Page']['status']) && $this->request->data['Page']['status']!=''){
				$status = Sanitize::escape($this->request->data['Page']['status']);
				$this->Session->write('AdminSearch.status', $status);
				$defaultTab = Configure::read('Status.'.$status);
			}			
		}


		$search_flag=0;	$search_status='';
		if($this->Session->check('AdminSearch')){
			$keywords  = $this->Session->read('AdminSearch');
				
			foreach($keywords as $key=>$values){
				if($key == 'status'){
					$search_status=$values;
					$filters[] = array('Page.'.$key =>$values);
						
				}
				else{
				 $filters[] = array('Page.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('Page.'.$key.' LIKE'=>"%".$values."%");
				}
			}
				
			$search_flag=1;
		}

		$this->set(compact('search_flag','defaultTab'));

		$this->paginate = array(
			'Page'=>array(	
				'limit'=>5, 
				'order'=>array('Page.title'=>'ASC'),
				'conditions'=>$filters,
				'recursive'=>1
			)
		);

		$data = $this->paginate('Page');
		$all = count($data);

		$this->set(compact('data'));
		$this->set('title_for_layout',  __('Pages', true));

		if(isset($this->request->params['named']['Page']))
		$this->Session->write('Url.Page', $this->request->params['named']['Page']);
		if(isset($this->request->params['named']['sort']))
		$this->Session->write('Url.sort', $this->request->params['named']['sort']);
		if(isset($this->request->params['named']['direction']))
		$this->Session->write('Url.direction', $this->request->params['named']['direction']);
		$this->Session->write('Url.defaultTab', $defaultTab);

		if($this->request->is('ajax')){
				
			$this->render('ajax/admin_index');
		}else{

			/*$active=0;$inactive=0;
			 if($search_status=='' || $search_status==Configure::read('App.Status.active')){
				$temp=$filters_without_status;
				$temp[] = array('Page.status'=>Configure::read('App.Status.active'));
				$active = $this->Page->find('count',array('conditions'=>$temp));
				}
					
				if($search_status=='' || $search_status==Configure::read('App.Status.inactive')){
				$temp=$filters_without_status;
				$temp[] = array('Page.status'=>Configure::read('App.Status.inactive'));
				$inactive = $this->Page->find('count',array('conditions'=>$temp));
				} */
			if($search_flag==0)
			$all = $this->Page->find('count');
				
			$tabs = array('All'=>$all);
			$this->set(compact('tabs'));
		}
	}
	/**
	 * Add static page
	 */
	public function admin_add() {
	 	
		
		if ($this->request->is('post')) {
		
			$this->request->data['Page']['slug']= strtolower(preg_replace('/\s+/', '-', $this->request->data['Page']['title']));
			$this->Page->create(); 
			$this->Page->set($this->request->data);
			$this->Page->setValidation('admin');
			if ($this->Page->validates()) {			
					
				if ($this->Page->save($this->request->data)) {
					$this->Session->setFlash(__('The page has been saved',true), 'admin_flash_success');
					$this->redirect(array('action' => 'index'));
				
				} else {
					$this->Session->setFlash(__('The Page could not be saved. Please, try again.',true), 'admin_flash_success');					
					$this->redirect(array('action' => 'index'));
				}
			}else{
					$this->Session->setFlash(__('The Page could not be saved. Please, correct errors.', true), 'admin_flash_error');
			}	
		}
		
	}
	
	/**
	 * edit existing admin
	 */
	public function admin_edit($id = null){

		$this->Page->id = $id;
		if (!$this->Page->exists()) {
			throw new NotFoundException(__('Invalid Page'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
				
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				//validate Page data
				$this->Page->set($this->request->data);
				$this->Page->setValidation('admin');
				if ($this->Page->validates()) {
					if ($this->Page->save($this->request->data)) 
					{
						$this->Session->setFlash(__('The Page information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} 
					else 
					{
						$this->Session->setFlash(__('The Page could not be saved. Please, try again.',true), 'admin_flash_error');
					}
				}
				else 
				{
					$this->Session->setFlash(__('The Page could not be saved. Please, correct errors.', true), 'admin_flash_error');
				}
			}
		}
		else {
			$this->request->data = $this->Page->read(null, $id);
		}
	}
	
	
	function index(){
		//$this->layout = "home";
		$this->layout = "welcome";
	}
	function home($varify = null){

		$msg = '';
		if($varify == 'varify_email'){
			$msg = 'Login your account from app.';

		}elseif($varify == 'varify_customer_email'){
			$msg = 'JTS Board Customer varifed successfully.';

		}
		$this->layout = "home";
		$this->set('msg',$msg);
		$this->set('title_for_layout','HOME');
	}
	 /**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view() {
		$this->layout	= 'page_dashboard';
		$slug = $this->params['slug'];
		$pages	=	$this->Page->find('first', array('conditions' => array(
												'Page.slug' => $slug, 
												'Page.status' => Configure::read('App.Status.active')
						)));
		//pr($pages);die;
		$this->set('title_for_layout', Configure::read('site.name').' '. $pages['Page']['meta_title']);
		$this->set('description_for_layout', $pages['Page']['meta_keywords']);
		$this->set('keywords_for_layout', $pages['Page']['meta_description']);
		$this->set('pages', $pages);
	}
	
	public function contact_us(){
      $this->layout = 'default';     
      $this->loadModel('Template');      
      if(!empty($this->request->data)){     
         
         if ($this->request->is('post') || $this->request->is('put')){
            if(!empty($this->request->data)) {
               
               $this->Page->set($this->request->data);         
               $this->Page->setValidation('contact_us');            
               if ($this->Page->validates()) {
                        
                        $to       	  = Configure::read('App.AdminMail');
                        $from    	  = $this->request->data['Page']['email'];
                        $mail_message = '';
						$emailTemplate = $this->Template->find('first', array('conditions' => array('Template.slug' => 'contact_us')));                       
                        $email_subject 	= $emailTemplate['Template']['subject'];
                        $subject 		=  $this->request->data['Page']['subject'];
                        $template 		=  '';
                        $mail_message = str_replace(array('{PNAME}','{EMAIL}', '{MESSAGE}' , '{SITE_URL}'), array($this->request->data['Page']['name'],$this->request->data['Page']['email'], $this->request->data['Page']['message'],Configure::read('App.SiteUrl')), $emailTemplate['Template']['content']);	
						
						$template = 'default';
						$this->set('message', $mail_message);
						//pr($mail_message);die;
                        parent::sendMail($to, $subject, $mail_message, $from, $template);
                      
                        $this->Session->setFlash(__('Thank you for contacting us.',true), 'flash_success');
                        $this->redirect(array('controller'=>'pages', 'action' => 'contact_us'));
               
               
               }else {
             
                  $this->Session->setFlash(__('Please correct the errors listed below.',true), 'flash_error');
               }
            
            
            }
         
         } 
      
      }
   
   }
   
   public function jsonTest(){
	
	$data = '{
  "data": {
    "profile_details": {
      "user_id": "91",
      "username": "patty123",
      "password": "$2y$14$Y8x5pag0WSVil71MMSdWo.TGe2uyUZnvNCzPsX4rLvL7tWAPO/rJm",
      "name": "pattyu",
      "email": "patty@mailinator.com",
      "alternate_email": "",
      "phone": "",
      "dob": "1986-02-14",
      "gender": "male",
      "profile_pic_path": "",
      "profile_pic_type": "0",
      "profile_cover_path": "",
      "profile_cover_type": "0",
      "interests": [],
      "experience": [],
      "education": [],
      "alternate_phone": "",
      "website": "",
      "facebook_link": "",
      "twitter_link": "",
      "google_plus_link": "",
      "linkedIn_link": "",
      "skype": "",
      "account_source": "0",
      "account_status": "0",
      "updated_on": "1397905047",
      "coins": "396",
      "oauth_token": "7e7a07b39dfb7b8cf7e4933700f9f9d7",
      "user_images": [],
      "is_completed_profile": true
    },
    "settings": {
      "location_type": "0",
      "country_id": "0",
      "state_id": "0",
      "city_id": "0",
      "latitude": "0",
      "longitude": "0",
      "radius": "250",
      "category_id": "0",
      "sub_category_id": "0",
      "category_name": "",
      "sub_category_name": "",
      "country": "",
      "country_code": "",
      "state": "",
      "city": ""
    }
  }
}';$decoded = json_decode($data, true);
		pr($decoded);die;
		
		}
		
    public function read_doc($test = null, $request_action = null) {
        $this->layout = false;
        if ($test == 'test') {
            $this->redirect(array('controller' => 'web_services', 'action' => 'read_doc', $test, $request_action));
        }
        $this->LoadModel("TestWebService");
        $dataIndex = $this->TestWebService->find('list', array('fields' => array('id', 'title'), 'conditions' => array('TestWebService.status' => 1), 'order' => 'id desc'));
        $data = $this->TestWebService->find('all', array('conditions' => array('TestWebService.status' => 1), 'order' => 'modified desc'));

        $this->set(compact('data', 'dataIndex'));
    }

	function assign_permission($checkCurrent = null){
		$this->layout = false;
		//echo WWW_ROOT . TEMP_THUMB1_DIR . DS;die;
		//chmod($value, 0777);
		//echo fileperms(WWW_ROOT . TESTPRM);die;
		//echo substr(sprintf('%o', fileperms(WWW_ROOT . TESTPRM)), -4);
		//die;
		$images_folder_array = array(
			WWW_ROOT . UPLOAD_FOLDER,
			
			WWW_ROOT . USER_ADSTEMPPATH,
			WWW_ROOT . TEMP_THUMB1_DIR,
			WWW_ROOT . TEMP_TINY_DIR,
			WWW_ROOT . TEMP_THUMB_DIR,
			WWW_ROOT . TEMP_LARGE_DIR,
			WWW_ROOT . TEMP_ORIGINAL_DIR,
			
			WWW_ROOT . USER_EXTRATEMPPATH,
			WWW_ROOT . USER_EXTRATEMP_THUMB1_DIR,
			WWW_ROOT . USER_EXTRATEMP_TINY_DIR,
			WWW_ROOT . USER_EXTRATEMP_THUMB_DIR,
			WWW_ROOT . USER_EXTRATEMP_LARGE_DIR,
			WWW_ROOT . USER_EXTRATEMP_ORIGINAL_DIR,
			
			WWW_ROOT . USER_DIR,
			WWW_ROOT . USER_THUMB1_DIR,
			WWW_ROOT . USER_TINY_DIR,
			WWW_ROOT . USER_THUMB_DIR,
			WWW_ROOT . USER_LARGE_DIR,
			WWW_ROOT . USER_ORIGINAL_DIR,
			
			WWW_ROOT . USER_COVER_THUMB1_DIR,
			WWW_ROOT . USER_COVER_TINY_DIR,
			WWW_ROOT . USER_COVER_THUMB_DIR,
			WWW_ROOT . USER_COVER_LARGE_DIR,
			WWW_ROOT . USER_COVER_ORIGINAL_DIR,
			
			WWW_ROOT . ADS_DIR,
			WWW_ROOT . ADS_THUMB1_DIR,
			WWW_ROOT . ADS_TINY_DIR,
			WWW_ROOT . ADS_THUMB_DIR,
			WWW_ROOT . ADS_LARGE_DIR,
			WWW_ROOT . ADS_ORIGINAL_DIR,
			
			WWW_ROOT . USERADS_DIR,
			WWW_ROOT . USERADS_THUMB1_DIR,
			WWW_ROOT . USERADS_TINY_DIR,
			WWW_ROOT . USERADS_THUMB_DIR,
			WWW_ROOT . USERADS_LARGE_DIR,
			WWW_ROOT . USERADS_ORIGINAL_DIR,
			
			WWW_ROOT . USER_EXTRAIMAGE_DIR,
			WWW_ROOT . USER_EXTRAIMAGE_THUMB1_DIR,
			WWW_ROOT . USER_EXTRAIMAGE_TINY_DIR,
			WWW_ROOT . USER_EXTRAIMAGE_THUMB_DIR,
			WWW_ROOT . USER_EXTRAIMAGE_LARGE_DIR,
			WWW_ROOT . USER_EXTRAIMAGE_ORIGINAL_DIR,
			
			WWW_ROOT . USERADSTEMP_MAINPROFILEDIR,
			WWW_ROOT . USERADSTEMP_MAINIMAGE_THUMB1_DIR,
			WWW_ROOT . USERADSTEMP_MAINIMAGE_TINY_DIR,
			WWW_ROOT . USERADSTEMP_MAINIMAGE_THUMB_DIR,
			WWW_ROOT . USERADSTEMP_MAINIMAGE_LARGE_DIR,
			WWW_ROOT . USERADSTEMP_MAINIMAGE_ORIGINAL_DIR,
		);
		
		//pr($images_folder_array);die;
		foreach($images_folder_array as $key=>$value){
			if($checkCurrent == 'check'){
				echo $value." - ";
				echo substr(sprintf('%o', fileperms($value)), -4)."<br>";
			}else{
				if(chmod($value, 0777)){
					echo "Assigned: ".$value." - ";
					echo substr(sprintf('%o', fileperms($value)), -4)."<br>";
				}else{
					echo "Couldn't do it.";
				}
			}
		}
		die;
	}
	
}