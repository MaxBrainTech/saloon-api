<?php

/**
 * Admins Controller
 *
 * PHP version 5.4
 *
 */
/*********************************************************************************
1. * Copyright 2014, All rights reserved, For internal use only
*
* FILE:    AdminsController.php
* PROJECT: vocalist.com.sg
* MODULE:  Admin Controller Module
* AUTHOR:  Mahendra Tripathi
* DATE:    10/08/2014
* Description: Admin controller is working for Administrator.
* REVISION HISTORY
* Date: By: Description:
*
*****************************************************************************/



class AdminsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    var $name = 'Admins';
/************************************************************************************************************************************
     * NAME: beforeFilter
     * Description: Before Filter .
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
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
     *********************************************************************************************************************************/ 
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('login');
        $this->loadModel('User');
    }

   /************************************************************************************************************************************
     * NAME: admin_dashboard
     * Description: Manage Administrator dashboard.
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
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
     *********************************************************************************************************************************/ 
    
    
    public function admin_dashboard() {
        $this->set('title_for_layout', __('Dashboard', true));
		
		
		
		$users['user']['tot'] = $this->User->find('count', array('conditions' => array('role_id = 2 ')));
        $users['user']['active'] = $this->User->find('count', array('conditions' => array('User.status = 1', 'role_id = 2')));
        $users['user']['inactive'] = $this->User->find('count', array('conditions' => array('User.status = 0', 'role_id = 2')));
        $this->set(compact('users'));
    }

    /************************************************************************************************************************************
     * NAME: admin_login
     * Description: Manage Administrator login.
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
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
     *********************************************************************************************************************************/ 
    
    public function admin_login(){
			if($this->Auth->login()){
				$this->redirect($this->Auth->redirect());				
			}else{
				if($this->request->is('post')){
					$this->request->data['User']['password'] = "";
					$this->Session->setFlash(__('Invalid username or password, try again'));
				}
			}
			$this->layout = 'admin_login';
    }

     /************************************************************************************************************************************
     * NAME: admin_logout
     * Description: Manage Administrator logout.
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
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
     *********************************************************************************************************************************/ 
    

    public function admin_logout(){
		//$this->Cookie->delete('admin');
		$this->Session->delete('number_of_record');
        $this->redirect($this->Auth->logout());
    }

   /************************************************************************************************************************************
     * NAME: admin_feature
     * Description: Manage Administrator feature.
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
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
     *********************************************************************************************************************************/ 
    
    public function admin_feature($id = null){

        $this->User->id = $id;

        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->User->toggleFeatured($id)) {
            $this->Session->setFlash(__('User\'s featured has been changed'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('User\'s featured was not changed', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

   /************************************************************************************************************************************
     * NAME: admin_feature
     * Description: Manage Administrator feature.
     *
     * Subroutines Called:
     *
     * Parameters: Input / Output
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
     *********************************************************************************************************************************/ 
    
    public function admin_delete($id = null) {
		if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}	
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('Admin deleted successfully'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Admin was not deleted', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

    /*
     * List all admins in admin panel
     */

    public function admin_index($role = 'Admin', $defaultTab = 'All') {
		
		$number_of_record = Configure::read('App.AdminPageLimit');
        if (!isset($this->request->params['named']['page'])) {
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }
	
	
      $filters_without_status = $filters = array('OR'=>array('User.role_id' => array(Configure::read('App.' . ucfirst($role).'.role'),Configure::read('App.SubAdmin.role')))); 
		
        if ($defaultTab != 'All') {
            $filters[] = array('User.status' => array_search($defaultTab, Configure::read('Status')));
        }

        if (!empty($this->request->data)) {
		
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
			
            if (!empty($this->request->data['User']['email'])) {
                $email = Sanitize::escape($this->request->data['User']['email']);
                $this->Session->write('AdminSearch.email', $email);
            }
            if (!empty($this->request->data['User']['username'])) {
                $username = Sanitize::escape($this->request->data['User']['username']);
                $this->Session->write('AdminSearch.username', $username);
            }

            if (isset($this->request->data['User']['status']) && $this->request->data['User']['status'] != '') {
                $status = Sanitize::escape($this->request->data['User']['status']);
                $this->Session->write('AdminSearch.status', $status);
                $defaultTab = Configure::read('Status.' . $status);
            }
        }

        $search_flag = 0;
        $search_status = '';
		if ($this->Session->check('AdminSearch')) {
            $keywords = $this->Session->read('AdminSearch');

            foreach ($keywords as $key => $values) {
                if ($key == 'status') {
                    $search_status = $values;
                    $filters[] = array('User.' . $key => $values);
                }
                if ($key == 'email') {
                    $filters[] = array('User.' . $key => $values);
                    $filters_without_status[] = array('User.' . $key => $values);
                }

                if ($key == 'nick_name') {
                    $filters[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }
                if ($key == 'username') {
                    $filters[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                    $filters_without_status[] = array('User.' . $key . ' LIKE' => "%" . $values . "%");
                }
            }

            $search_flag = 1;
        }
        $this->set(compact('search_flag', 'defaultTab'));
        $filters[] = array('OR'=>array("User.role_id" => array(Configure::read('App.Admin.role'),Configure::read('App.SubAdmin.role'))));
		
		/* if ($this->request->is('ajax')) {
		 } */
     
        $this->paginate = array(
            'User' => array(
                 'limit' => $number_of_record,
                'order' => array('User.created' => 'DESC'),
                'conditions' => $filters
        ));
		


        $data = $this->paginate('User');

        $this->set(compact('data', 'role'));
        $this->set('title_for_layout', __('Admins', true));


        if (isset($this->request->params['named']['page']))
            $this->Session->write('Url.page', $this->request->params['named']['page']);
        if (isset($this->request->params['named']['sort']))
            $this->Session->write('Url.sort', $this->request->params['named']['sort']);
        if (isset($this->request->params['named']['direction']))
            $this->Session->write('Url.direction', $this->request->params['named']['direction']);
        $this->Session->write('Url.type', $role);
        $this->Session->write('Url.defaultTab', $defaultTab);

        if ($this->request->is('ajax')) {
		
            $this->render('ajax/admin_index');
        }else {
            $active = 0;
            $inactive = 0;
            if ($search_status == '' || $search_status == Configure::read('App.Status.active')) {
                $temp = $filters_without_status;
                $temp[] = array('User.status' => 1);
                $active = $this->User->find('count', array('conditions' => $temp));
            }
            if ($search_status == '' || $search_status == Configure::read('App.Status.inactive')) {
                $temp = $filters_without_status;
                $temp[] = array('User.status' => 0);
                $inactive = $this->User->find('count', array('conditions' => $temp));
            }

            $tabs = array('All' => $active + $inactive, 'Active' => $active, 'Inactive' => $inactive);
            $this->set(compact('tabs'));
        }
    }

    /*
     * Add new user
     */

    public function admin_add(){
		if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}		
        
        $this->loadModel('Template');
		
        if ($this->request->is('post')) {

            if (!empty($this->request->data)) {
                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }
                $url = Router::url(array(
                            'controller' => 'users',
                            'action' => 'Clients'
                                ), true);

                $this->User->set($this->request->data);
                $this->User->setValidation('admin');
                if ($this->User->validates()) {
                    $this->request->data['User']['password'] = Security::hash($this->request->data['User']['password2'], null, true);
					$this->request->data['User']['origional_password'] = $this->request->data['User']['password2'];
					 $this->request->data['User']['role_id'] = Configure::read('App.SubAdmin.role');
                    $url = Router::url(array(
                                'controller' => 'users',
                                'action' => 'Clients'
                                    ), true);
                    /* send email confirmation link to user */
                   /*  $AdminRegistration = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_registration')));
                    $email_subject = $AdminRegistration['Template']['subject'];
                    $subject = __('[' . Configure::read('Site.title') . '] ' .
                            $email_subject . '', true);

                    $mailMessage = str_replace(array('{NAME}', '{USERNAME}', '{SITE}', '{ACTIVATION_LINK}'), array($this->request->data['User']['first_name'], $this->request->data['User']['username'], Configure::read('Site.title'), $url), $AdminRegistration['Template']['content']);

                    if ($this->sendMail($this->request->data["User"]["email"], $subject, $mailMessage, array(Configure::read('App.AdminMail') => Configure::read('Site.title')), $AdminRegistration['Template']['id'])) {
                        $this->Session->setFlash(__('User has been saved successfully'), 'admin_flash_success');
                    } else {
                        $this->Session->setFlash('Congrates! You have succesfully registered on ' . Configure::read('Site.title') . '.', 'admin_flash_success');
                    } */

                    if ($this->User->saveAll($this->request->data)) {
                        /* send email confirmation link to user */
                        /* $AdminRegistration = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_registration')));
                        $email_subject = $AdminRegistration['Template']['subject'];
                        $subject = __('[' . Configure::read('Site.title') . '] ' .
                                $email_subject . '', true);

                        $mailMessage = str_replace(array('{NAME}', '{USERNAME}', '{SITE}', '{ACTIVATION_LINK}'), array($this->request->data['User']['first_name'], $this->request->data['User']['username'], Configure::read('Site.title'), $url), $AdminRegistration['Template']['content']);

                        if ($this->sendMail($this->request->data["User"]["email"], $subject, $mailMessage, array(Configure::read('App.AdminMail') => Configure::read('Site.title')), $AdminRegistration['Template']['id'])) {
                            $this->Session->setFlash(__('User has been saved successfully'), 'admin_flash_success');
                        } else {
                            $this->Session->setFlash('Congrates! You have succesfully registered on ' . Configure::read('Site.title') . '.', 'admin_flash_success');
                        } */
                        $this->Session->setFlash(__('Sub Admin has been saved successfully'), 'admin_flash_success');
                        $this->redirect(array('action' => 'index', 'Admin'));
                    } else {
                        $this->Session->setFlash(__('The Sub Admin could not be saved. Please, try again.'), 'admin_flash_error');
                    }
                } else {
                    $this->Session->setFlash('The Sub Admin could not be saved.  Please, correct errors.', 'admin_flash_error');
                }
            }
        }
    }

    /*
     * Add new user
     */

    public function admin_event_mail(){
        if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
        {
            $this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
            $this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
        }       


        
        $this->loadModel('Template');
        /****************** EMAIL NOTIFICATION MESSAGE ********************/
        $from    = Configure::read('App.AdminMail');
        $mail_message = '';
        
        $this->loadModel('Template');
        $registrationMail = $this->Template->find('first', array('conditions' => array('Template.slug' => 'event_email')));
        $email_subject = $registrationMail['Template']['subject'];
        $subject = 'JTSボード' . $email_subject ;
        $mail_message =  $registrationMail['Template']['content'];
        $template = 'default';
        $this->set('message', $mail_message);

        $from = 'JTSBoard  <admin@jtsboard.com>';

        $headers = "From: " .($from) . "\r\n";
        $headers .= "Reply-To: ".($from) . "\r\n";
        $headers .= "Return-Path: ".($from) . "\r\n";;
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

        @mail('mahen.zed123@gmail.com',$subject,$mail_message,$headers);



        
        $template = 'default';
        
        $from = "JTSボード <mailgun@mg.jtsboard.com>";
        // echo $email, $subject, $mail_message, $from, $template;die;
        if($this->sendMail("mahen.zed123@gmail.com", $subject, $mail_message, $from, $template)){
            $responseArr = array('status' => 'success' );
            $jsonEncode = json_encode($responseArr);
        }else{
             $responseArr = array('status' => 'error' );
            $jsonEncode = json_encode($responseArr);
        }
        echo $jsonEncode ;exit();
        /****************** EMAIL NOTIFICATION MESSAGE ********************/
        
        



        /* send email confirmation link to user */
       /* $AdminRegistration = $this->Template->find('first', array('conditions' => array('Template.slug' => 'event_email')));
        $email_subject = $AdminRegistration['Template']['subject'];
        $subject = __('[' . Configure::read('Site.title') . '] ' .$email_subject . '', true);

        $mailMessage = $AdminRegistration['Template']['content'];

        if ($this->sendMail("mahen.zed123@gmail.com", $subject, $mailMessage, array(Configure::read('App.AdminMail') => Configure::read('Site.title')), $AdminRegistration['Template']['id'])) {
            $this->Session->setFlash(__('User has been saved successfully'), 'admin_flash_success');
            echo 'successfully'; 
        } else {
            $this->Session->setFlash('Congrates! You have succesfully registered on ' . Configure::read('Site.title') . '.', 'admin_flash_success');
            echo 'error';
        } */

        /* send email confirmation link to user */
        /* $AdminRegistration = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_registration')));
        $email_subject = $AdminRegistration['Template']['subject'];
        $subject = __('[' . Configure::read('Site.title') . '] ' .
                $email_subject . '', true);

        $mailMessage = str_replace(array('{NAME}', '{USERNAME}', '{SITE}', '{ACTIVATION_LINK}'), array($this->request->data['User']['first_name'], $this->request->data['User']['username'], Configure::read('Site.title'), $url), $AdminRegistration['Template']['content']);

        if ($this->sendMail($this->request->data["User"]["email"], $subject, $mailMessage, array(Configure::read('App.AdminMail') => Configure::read('Site.title')), $AdminRegistration['Template']['id'])) {
            $this->Session->setFlash(__('User has been saved successfully'), 'admin_flash_success');
        } else {
            $this->Session->setFlash('Congrates! You have succesfully registered on ' . Configure::read('Site.title') . '.', 'admin_flash_success');
        } */
          
    }

     public function sendMail($to, $subject, $message, $from, $template_id = null ){
        // echo $message;die;

        $html  = $message;
        $domain = "mg.jtsboard.com";
        $config = array();
        $config['api_key'] = "457b1d1a0372e162d6336f675d1a69c6-de7062c6-a83103f2";
        $config['api_url'] = "https://api.mailgun.net/v3/" . $domain . "/messages";
        $message = array();
        $message['from'] = $from;
        $message['to'] = $to;
        $message['subject'] = $subject;
        $message['html'] = $html;
        // $message = json_encode($message);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $config['api_url']);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "api:{$config['api_key']}");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
        $result = curl_exec($curl);
        // pr($result);die;
        curl_close($curl);
        return $result;

       
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
        $this->User->id = $id;
		$user_data = $this->User->read(array('role_id'), $id);
		$this->set('user_data',$user_data);
		
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            if (!empty($this->request->data)) {
			
                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }
                //validate user data
                $this->User->set($this->request->data);
                $this->User->setValidation('admin');
                if ($this->User->validates()) {
                    if ($this->User->save($this->request->data)) {
                        $this->Session->setFlash(__('The information has been updated successfully', true), 'admin_flash_success');
                        $this->redirect(array('action' => 'index', 'Admin'));
                    } else {
                        $this->Session->setFlash(__('The Admin could not be saved. Please, try again.', true), 'admin_flash_error');
                    }
                } else {
                    $this->Session->setFlash(__('The Admin could not be saved. Please, correct errors.', true), 'admin_flash_error');
                }
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function admin_process() {

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }

        if (!empty($this->request->data)) {
            App::uses('Sanitize', 'Utility');
            $action = Sanitize::escape($this->request->data['User']['pageAction']);

            $ids = $this->request->data['User']['id'];


            if (count($this->request->data) == 0 || $this->request->data['User'] == null) {
                $this->Session->setFlash('No items selected.', 'admin_flash_error');
                $this->redirect($this->referer());
            }

            if ($action == "delete") {
                $this->User->deleteAll(array('User.id' => $ids));

                $this->loadModel('Template');


                $this->Session->setFlash('Admins have been deleted successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "activate") {
                $this->User->updateAll(array('User.status' => Configure::read('App.Status.active')), array('User.id' => $ids));
                /* $this->loadModel('Template');
                foreach ($ids as $id) {
                    $user_info = $this->User->get_users('first', 'User.email, User.first_name,  User.last_name, User.status', array('User.id' => $id));

                    $change_status = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_status_change')));
                    $email_subject = $change_status['Template']['subject'];
                    $subject = __('[' . Configure::read('Site.title') . '] ' .
                            $email_subject . '', true);

                    $mailMessage = str_replace(array('{NAME}', '{STATUS}'), array($user_info['User']['first_name'] . ' ' . $user_info['User']['last_name'], ($user_info['User']['status'] == 1) ? 'Active' : 'InActive'), $change_status['Template']['content']);

                    $this->sendMail($user_info["User"]["email"], $subject, $mailMessage, array(Configure::read('App.AdminMail') => Configure::read('Site.title')), $change_status['Template']['id']);
                } */

                $this->Session->setFlash('Sub Admins have been activated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }

            if ($action == "deactivate") {

                $this->User->updateAll(array('User.status' => Configure::read('App.Status.inactive')), array('User.id' => $ids));
                /* $this->loadModel('Template');

                foreach ($ids as $id) {
                    $user_info = $this->User->get_users('first', 'User.email,User.first_name,User.last_name,User.status', array('User.id' => $id));

                    $change_status = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_status_change')));
                    $email_subject = $change_status['Template']['subject'];
                    $subject = __('[' . Configure::read('Site.title') . '] ' .
                            $email_subject . '', true);

                    $mailMessage = str_replace(array('{NAME}', '{STATUS}'), array($user_info['User']['first_name'] . ' ' . $user_info['User']['last_name'], ($user_info['User']['status'] == 1) ? 'Active' : 'Inactive'), $change_status['Template']['content']);

                    $this->sendMail($user_info["User"]["email"], $subject, $mailMessage, array(Configure::read('App.AdminMail') => Configure::read('Site.title')), $change_status['Template']['id']);
                }
 */
                $this->Session->setFlash('Sub Admins have been deactivated successfully', 'admin_flash_success');
                $this->redirect($this->referer());
            }
        } else {
            $this->redirect(array('controller' => 'admins', 'action' => 'index', 'Admin'));
        }
    }

    /*
     * View existing user
     */

    public function admin_view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        $this->set('user', $this->User->read(null, $id));
    }

    /**
     * toggle status existing user
     */
    public function admin_status($id = null) {
	
	if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        $this->loadModel('Template');
        $this->loadModel('User');
        if ($this->User->toggleStatus($id)) {
            /* $user_info = $this->User->get_users('first', 'User.email, User.first_name, User.last_name, User.status', array('User.id' => $id));

            $change_status = $this->Template->find('first', array('conditions' => array('Template.slug' => 'user_status_change')));
            $email_subject = $change_status['Template']['subject'];
            $subject = __('[' . Configure::read('Site.title') . '] ' .
                    $email_subject . '', true);

            $mailMessage = str_replace(array('{NAME}', '{STATUS}'), array($user_info['User']['first_name'] . ' ' . $user_info['User']['last_name'], ($user_info['User']['status'] == 1) ? 'Active' : 'InActive'), $change_status['Template']['content']);

            $this->sendMail($user_info["User"]["email"], $subject, $mailMessage, array(Configure::read('App.AdminMail') => Configure::read('Site.title')), $change_status['Template']['id']); */

            $this->Session->setFlash(__('Admin\'s status has been changed'), 'admin_flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Admin\'s status was not changed', 'admin_flash_error'));
        $this->redirect($this->referer());
    }

    public function get_users($type, $fields = '*', $cond = array(), $order = 'User.id desc', $limit = 999, $offset = 0) {
        $users = $this->find($type, array('conditions' => array('User.status' => Configure::read('App.Status.active'), $cond), 'fields' => array($fields), 'order' => array($order), 'offset' => $offset, 'limit' => $limit));

        return $users;
    }

    /**
     * Change Password
     */
    public function admin_change_password($id = null) {
	if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.role_id')== Configure::read('App.SubAdmin.role'))
		{
			$this->Session->setFlash(__('You are not authorizatized for this action'), 'admin_flash_error');
			$this->redirect(array('controller' => 'admins', 'action' => 'dashboard')); 
		}
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            if (!empty($this->request->data)) {
                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }


                //validate user data
                $this->User->set($this->request->data);
                $this->User->setValidation('admin_change_password');
                if ($this->User->validates()) {
                    $this->request->data['User']['password'] = Security::hash($this->request->data['User']['new_password'], null, true);
                    $this->request->data['User']['origional_password'] = $this->request->data['User']['new_password'];
                    if ($this->User->saveAll($this->request->data)) {
                        $this->Session->setFlash('Your Password has been changed.', 'admin_flash_success');
                        $this->redirect(array('controller' => 'admins', 'action' => 'index', 'Admin'));
                    } else {
                        $this->Session->setFlash(__('The Password could not be changed. Please, try again.', true), 'admin_flash_error');
                    }
                } else {
                    $this->Session->setFlash(__('The Password could not be changed. Please, correct errors.', true), 'admin_flash_error');
                }
            }
        } else {

            $this->request->data = $this->User->read(null, $id);

            unset($this->request->data['User']['password']);
        }
    }

}

