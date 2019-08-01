<?php
/**
 * Templates Controller
 *
 * PHP version 5.4
 *
 */
class TemplatesController extends AppController {
	/**
     * Controller name
     *
     * @var string
     * @access public
     */
	var	$name	=	'Templates';
	/*
	* beforeFilter
	* @return void
	*/
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login');
        //$this->Auth->allow('getFooterList');
			//$this->loadModel('Template');
			//$this->loadModel('Footer');
			
    }
	
	
	/*
	* List all Templates in admin panel
	*/
    public function admin_index($defaultTab='All') {
		
		$number_of_record = Configure::read('App.AdminPageLimit');
        
		
		$filters_without_status = $filters = array(); 
		
		if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
		
		if(!empty($this->request->data)){			
			$this->Session->delete('AdminSearch');
			$this->Session->delete('Url');
			
			App::uses('Sanitize', 'Utility');
				if (!empty($this->request->data['Number']['number_of_record'])) {
					$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
					$this->Session->write('number_of_record', $number_of_record);
				}
				if ($this->Session->check('number_of_record')) {
					$number_of_record = $this->Session->read('number_of_record');
					$this->request->data['Number']['number_of_record'] = $number_of_record;
				}
				if(!isset($this->request->params['named']['Template'])){
					$this->Session->delete('AdminSearch');
					$this->Session->delete('Url');
				}				
			if(!empty($this->request->data['Template']['title'])){				
				$title = Sanitize::escape($this->request->data['Template']['title']);
				$this->Session->write('AdminSearch.title', $title);				
			}
			
			if(isset($this->request->data['Template']['status']) && $this->request->data['Template']['status']!=''){
				$status = Sanitize::escape($this->request->data['Template']['status']);
				$this->Session->write('AdminSearch.status', $status);	
				$defaultTab = Configure::read('Status.'.$status);
			}	
			
			
		}

		
		$search_flag=0;	
		if($this->Session->check('AdminSearch')){
			$keywords  = $this->Session->read('AdminSearch');
			
			foreach($keywords as $key=>$values){
				if($key == 'status'){
					$filters[] = array('Template.'.$key =>$values);
				}
				else{
				 $filters[] = array('Template.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('Template.'.$key.' LIKE'=>"%".$values."%");
				} 
			}
			
			$search_flag=1;
		}
		$this->set(compact('search_flag','defaultTab'));					
		
		$this->paginate = array(
			'Template'=>array(	
				'limit' => $number_of_record,
				'order'=>array('Template.name'=>'ASC'),
				'conditions'=>$filters,
				'recursive'=>1
			)
		);
		
		$data = $this->paginate('Template');     
		
		
		$this->set(compact('data'));				
		$this->set('title_for_layout',  __('Email Templates', true));	
		
		if(isset($this->request->params['named']['Template']))
			$this->Session->write('Url.Template', $this->request->params['named']['Template']);	
		if(isset($this->request->params['named']['sort']))
			$this->Session->write('Url.sort', $this->request->params['named']['sort']);	
		if(isset($this->request->params['named']['direction']))
			$this->Session->write('Url.direction', $this->request->params['named']['direction']);	
		$this->Session->write('Url.defaultTab', $defaultTab);	
		$this->loadModel('User');
		$users = $this->User->find('list',array('fields'=>array('User.id','User.username'),'conditions'=>array('User.role_id'=>Configure::read('App.User.role'),'User.email != '=>'')));
		
		$this->set('users',$users);
		if($this->request->is('ajax')){
			$this->render('ajax/admin_index');
		}else{
			$temp=$filters_without_status;
			$all = $this->Template->find('count');			
			
			$tabs = array('All'=>$all);
			$this->set(compact('tabs'));
		}
    }
	
	
    /**
	* add existing admin
	*/
    public function admin_add() {
	if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}	
	
			 
         if ($this->request->is('post') || $this->request->is('put')) {
			
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				//validate Template data
				unset($this->request->data['FooterTemplate']['footer_id']);
				$this->Template->set($this->request->data);
				$this->Template->setValidation('admin');
				if ($this->Template->validates()) {
					
					if ($this->Template->saveAll($this->request->data)) {
						
						$this->Session->setFlash(__('The Template information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Template could not be saved. Please, try again.',true), 'admin_flash_error');
					}
			}
			else {
				$this->Session->setFlash(__('The Admin could not be saved. Please, correct errors.', true), 'admin_flash_error');
			}
		  }	
        }
	 }
	
    
    
    
    
    
	/**
	* edit existing admin
	*/
    public function admin_edit($id = null) {
	if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}	
	$this->Template->id = $id;
        if (!$this->Template->exists()) {
            throw new NotFoundException(__('Invalid Template'));
        }
			 
         if ($this->request->is('post') || $this->request->is('put')) {
			
			if(!empty($this->request->data)) {
				if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
					$blackHoleCallback = $this->Security->blackHoleCallback;
					$this->$blackHoleCallback();
				}
				//validate Template data
				unset($this->request->data['FooterTemplate']['footer_id']);
				$this->Template->set($this->request->data);
				$this->Template->setValidation('admin');
				if ($this->Template->validates()) {
					
					if ($this->Template->saveAll($this->request->data)) {
						
						$this->Session->setFlash(__('The Template information has been updated successfully',true), 'admin_flash_success');
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The Template could not be saved. Please, try again.',true), 'admin_flash_error');
					}
			}
			else {
				$this->Session->setFlash(__('The Admin could not be saved. Please, correct errors.', true), 'admin_flash_error');
			}
		  }	
        }
		else {
            $this->request->data = $this->Template->read(null, $id);
        }
    }
	
	function referer($default = NULL, $local = false)
	{
		$defaultTab = $this->Session->read('Url.defaultTab');
		$Template = $this->Session->read('Url.Template');
		$sort = $this->Session->read('Url.sort');
		$direction = $this->Session->read('Url.direction');
		
		return Router::url(array('action'=>'index', $defaultTab,'Template'=>$Template,'sort'=>$sort,'direction'=>$direction),true);
	}
	
	public function admin_display($id){
		$this->layout = 'Emails/html/default';
		$data = $this->Template->read(null,$id);
		$this->set(compact(array('data')));
		$this->render('admin_display');
	}
	
	
	public function admin_send_email()
	{
		
		if(!empty($this->request->data))
		{
			
			$this->loadModel('User');
			$user_id = $this->request->data['Template']['user_id'];
			
			$user_detail = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id ),'fields'=>array('User.email')));
			// Start send mail.
			$to      = $user_detail['User']['email'];
			$from    = Configure::read('App.AdminMail');
			$subject = $this->request->data['Template']['subject'];
			$mailMessage = $this->request->data['Template']['content'];
			$template='default';
			parent::sendMail($to, $subject, $mailMessage, $from,$template);
			$this->Session->setFlash(' Email send successfully ', 'admin_flash_success');
            $this->redirect($this->referer());
			// End send mail email.
		}
	}
	
	
	
	/*
	* List all Templates History in admin panel
	*/
    public function admin_history($defaultTab='All') {
		
		
		$number_of_record = Configure::read('App.AdminPageLimit');
       
		if(!isset($this->request->params['named']['Template'])){
			$this->Session->delete('AdminSearch');
			$this->Session->delete('Url');
		}	
		
		$filters_without_status = $filters = array(); 
		
		
		 if(!empty($this->request->data)){
			 /*  pr($this->request->data);
			die;  */	 
			$this->Session->delete('AdminSearch');
			$this->Session->delete('Url');
			
			App::uses('Sanitize', 'Utility');
			if (!empty($this->request->data['Number']['number_of_record'])) {
				$number_of_record = Sanitize::escape($this->request->data['Number']['number_of_record']);
				$this->Session->write('number_of_record', $number_of_record);
			}
			if ($this->Session->check('number_of_record')) {
				$number_of_record = $this->Session->read('number_of_record');
				$this->request->data['Number']['number_of_record'] = $number_of_record;
			}			
			if(!empty($this->request->data['Template']['name'])){				
				$name = Sanitize::escape($this->request->data['Template']['name']);
				$this->Session->write('AdminSearch.name', $name);				
			}
			
			if(isset($this->request->data['Template']['email']) && $this->request->data['Template']['email']!=''){
				$email = Sanitize::escape($this->request->data['Template']['email']);
				$this->Session->write('AdminSearch.email', $email);	
				//$defaultTab = Configure::read('Status.'.$status);
			}
			if(isset($this->request->data['Template']['slug']) && $this->request->data['Template']['slug']!=''){
				$slug = Sanitize::escape($this->request->data['Template']['slug']);
				$this->Session->write('AdminSearch.slug', $slug);	
				//$defaultTab = Configure::read('Status.'.$status);
			}			
			
			
		}

		
		$search_flag=0;	
		if($this->Session->check('AdminSearch')){
			$keywords  = $this->Session->read('AdminSearch');
			/*  pr($this->Session->read('AdminSearch'));
			die; */
			foreach($keywords as $key=>$values){
				if($key == 'name'){
					$filters[] = array('OR'=>array('User.first_name LIKE'=>"%" .$values. "%",'User.last_name LIKE'=>"%" .$values. "%"));
				}
				elseif($key == 'email'){
					$filters[] = array('User.'.$key =>$values);
				}
				elseif($key == 'slug'){
					$filters[] = array('Template.'.$key =>$values);
				}
				else{
				 $filters[] = array('Template.'.$key.' LIKE'=>"%".$values."%");
				 $filters_without_status = array('Template.'.$key.' LIKE'=>"%".$values."%");
				} 
			}
			/* pr( $filters);
			die; */
			$search_flag=1;
		}
		/* pr( $filters);
		die; */
		$this->set(compact('search_flag','defaultTab'));					
		
		
		$this->loadModel('TemplatesUser');
		$this->TemplatesUser->bindModel(array(
			'belongsTo'=>array(
				'User'=>array(
					'fields'=>array(
						'User.first_name','User.last_name','User.username','User.email','is_email_verified'
					)
				),
				'Template'=>array(
					'fields'=>array(
						'Template.slug'
					)
				)
			)
		),false); 
		$this->paginate = array(
			'TemplatesUser'=>array(	
				 'limit' => $number_of_record,
				'order'=>array('TemplatesUser.id'=>'ASC'),
				'conditions'=>$filters,
				'recursive'=>1
			)
		);
		
		$data = $this->paginate('TemplatesUser'); 
		/*  pr($data);
die;		 */
		
		
		$this->set(compact('data'));				
		$this->set('title_for_layout',  __('Email History Of Users ', true));	
		
		if(isset($this->request->params['named']['Template']))
			$this->Session->write('Url.Template', $this->request->params['named']['Template']);	
		if(isset($this->request->params['named']['sort']))
			$this->Session->write('Url.sort', $this->request->params['named']['sort']);	
		if(isset($this->request->params['named']['direction']))
			$this->Session->write('Url.direction', $this->request->params['named']['direction']);	
		$this->Session->write('Url.defaultTab', $defaultTab);	
		$this->loadModel('User');
		$users = $this->User->find('list',array('fields'=>array('User.id','User.username'),'conditions'=>array('User.role_id'=>Configure::read('App.User.role'))));
		
		$this->set('users',$users);
		if($this->request->is('ajax')){
			$this->render('ajax/admin_history');
		}else{
			$temp=$filters_without_status;
			$all = $this->TemplatesUser->find('count');			
			
			$tabs = array('All'=>$all);
			$this->set(compact('tabs'));
		}
    }
	
	
	/*
     * View existing user
     */

    public function admin_history_view($id = null) {
	
		$this->loadModel('TemplatesUser');
		$this->TemplatesUser->bindModel(array(
			'belongsTo'=>array(
				'User',
				'Template'=>array(
						'fields'=>array(
							'Template.slug'
						)
					)
				)
			)
		,false); 
        $this->TemplatesUser->id = $id;
        if (!$this->TemplatesUser->exists()) {
            throw new NotFoundException(__('Invalid history'));
        }

       
        $data = $this->TemplatesUser->read(null, $id);
        $this->set('title_for_layout', 'Email History');
        $this->set('history', $data);
    }
	
	
	
	 /**
     * delete the history of email
     */
    public function admin_history_delete($id = null) {
		$this->loadModel('TemplatesUser');
		$this->TemplatesUser->id = $id;
        if (!$this->TemplatesUser->exists()) {
            throw new NotFoundException(__('Invalid history'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
		
        if ($this->TemplatesUser->delete()) {

           
            $this->Session->setFlash(__('History deleted successfully'), 'admin_flash_success');
           $this->redirect(array('action'=>'history'));
        }
        $this->Session->setFlash(__('History was not deleted', 'admin_flash_error'));
        $this->redirect(array('action'=>'history'));
    }
	
	
	 public function admin_process() {
	/*  pr($this->request->data);
	 die; */
		$this->loadModel('TemplatesUser');
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }

        if (!empty($this->request->data)) {
            App::uses('Sanitize', 'Utility');
            $action = Sanitize::escape($this->request->data['Template']['pageAction']);

            $ids = $this->request->data['TemplatesUser']['id'];

            if (count($this->request->data) == 0 || $this->request->data['TemplatesUser'] == null) {
                $this->Session->setFlash('No items selected.', 'admin_flash_error');
                $this->redirect($this->referer());
            }

            if ($action == "delete") {
				
				
				$this->TemplatesUser->deleteAll(array('TemplatesUser.id' => $ids)); 
                $this->Session->setFlash('Histories have been deleted successfully', 'admin_flash_success');
                $this->redirect(array('action'=>'history'));
            }

           
            
        } else {
            $this->redirect(array('controller' => 'admins', 'action' => 'index'));
        }
    }
	
	
	
	
	  
	
}