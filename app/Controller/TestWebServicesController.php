<?php
class TestWebServicesController extends AppController{
	
    var $name = 'TestWebServices';
    var $helpers = array('Html', 'Form', 'General');
    var $uses = array('TestWebService');

    /**
     * beforeFilter
     *
     * @return void
     */
    public function beforeFilter(){
		parent::beforeFilter();
    }

    /**
     * List all test_web_services in admin panel
     * @param type $defaultTab
     */
    function admin_index($defaultTab = 'All'){

        if (!isset($this->request->params['named']['page'])){
            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
        }

        $filters = $filters_without_status = array();
        if (!empty($this->request->data)) {

            $this->Session->delete('AdminSearch');
            $this->Session->delete('Url');
            if (!empty($this->data['TestWebService']['title'])) {
                $keytitle = $this->data['TestWebService']['title'];
                $this->Session->write('AdminSearch.title', $keytitle);
            }
        }

        $search_flag = 0;
        $search_status = '';
        if ($this->Session->check('AdminSearch')) {
            $keytitles = $this->Session->read('AdminSearch');

            foreach ($keytitles as $key => $values) {

                if ($key == 'title') {
                    $filters[] = array('TestWebService.title LIKE' => "%" . $values . "%");
                    
                }
            }

            $search_flag = 1;
        }

        $this->set(compact('search_flag', 'defaultTab'));
        $this->paginate = array(
            'TestWebService' => array(
                'limit' => Configure::read('App.AdminPageLimit'),
                'order' => array('TestWebService.id' => 'desc'),
                'conditions' => $filters,
               )
        );


        $data = $this->paginate('TestWebService');

        $this->set(compact('data'));
        $this->set('title_for_layout', __('Test API Management', true));

        if (isset($this->request->params['named']['page']))
            $this->Session->write('Url.page', $this->request->params['named']['page']);
        if (isset($this->request->params['named']['sort']))
            $this->Session->write('Url.sort', $this->request->params['named']['sort']);
        if (isset($this->request->params['named']['direction']))
            $this->Session->write('Url.direction', $this->request->params['named']['direction']);
        $this->Session->write('Url.defaultTab', $defaultTab);

        if ($this->request->is('ajax')) {
            $this->render('ajax/admin_index');
        } else {
         
            $temp = $filters_without_status;
            $inactive = $this->TestWebService->find('count', array('conditions' => $temp));
            $tabs = array('All' => $inactive);
            $this->set(compact('tabs'));
        }
        
    }

    /*
     * Add new test_web_service in admin panel
     */

    function admin_add() {
		$this->set("title_for_layout", __('Add Test API', true));
        if ($this->request->is('post')) {
            //check empty
            if (!empty($this->request->data)) {
                if (!isset($this->request->params['data']['_Token']['key']) || ($this->request->params['data']['_Token']['key'] != $this->request->params['_Token']['key'])) {
                    $blackHoleCallback = $this->Security->blackHoleCallback;
                    $this->$blackHoleCallback();
                }
                //validate user data
                $this->TestWebService->set($this->request->data);
                $this->TestWebService->setValidation('admin');
                if ($this->TestWebService->validates()) {

                    if ($this->TestWebService->save($this->request->data)) {
                        $this->Session->setFlash(__('TestWebService has been added successfully'), 'admin_flash_success');
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash(__('The TestWebService could not be added. Please, try again.'), 'admin_flash_error');
                    }
                } else {
                    $this->Session->setFlash('The TestWebService could not be added.  Please, correct errors.', 'admin_flash_error');
                }
            }
        }
    }

    /*
     * Edit existing test_web_service in admin panel
     */

    function admin_edit($id = null) {

        $this->set("title_for_layout", __('Edit Test API', true));

        if (!$id && empty($this->data)) {
            $this->Session->setFlash('Invalid test_web_service id', 'admin_flash_error');
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {

            // CSRF Protection
            if ($this->params['_Token']['key'] != $this->data['TestWebService']['token_key']) {
                $blackHoleCallback = $this->Security->blackHoleCallback;
                $this->$blackHoleCallback();
            }

            // validate & save data
            $this->TestWebService->setValidation('admin');
            if ($this->TestWebService->validates()) {
                if ($this->TestWebService->save($this->data)) {
                    $this->Session->setFlash(__('TestWebService has been saved', true), 'admin_flash_good');
                    $this->redirect(array('controller' => 'test_web_services', 'action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Please correct the errors listed below.', true), 'admin_flash_error');
                }
            } else {
                $this->Session->setFlash(__('TestWebService could not be saved. Please, try again.', true), 'admin_flash_error');
            }
        } else {
            $this->data = $this->TestWebService->read(null, $id);
        }
    }

    /**
     * Deleting Existing TestWebService
     * @param type $id
     */
    function admin_delete($id = null) {

        if (!$id) {
            $this->Session->setFlash(__('Invalid test_web_service id', true), 'admin_flash_good');
            $this->redirect(array('action' => 'index'));
        }

        if (!isset($this->params['named']['token']) || ($this->params['named']['token'] != $this->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->TestWebService->deleteAll(array('TestWebService.id' => $id))) {
            $this->Session->setFlash(__('TestWebService has been deleted successfully', true), 'admin_flash_good');
            $this->redirect($this->referer());
        }
    }

    function admin_process() {
        if (!empty($this->request->data)) {

            App::uses('Sanitize', 'Utility');
            $action = Sanitize::escape($this->data['TestWebService']['pageAction']);


            $ids = $this->request->data['TestWebService']['id'];
            //pr($ids); die;
            if (count($this->request->data) == 0 || $this->request->data['TestWebService'] == null) {
                $this->Session->setFlash('No items selected.', 'admin_flash_error');
                $this->redirect($this->referer());
            }

            if ($action == "delete") {
                $this->TestWebService->deleteAll(array('TestWebService.id' => $ids));
                $this->Session->setFlash('TestWebServices have been deleted successfully', 'admin_flash_good');
                $this->redirect($this->referer());
            }
        } else {
            $this->redirect(array('controller' => 'test_web_services', 'action' => 'index'));
        }
    }

    /**
     * To change status of 
     * @param type $id
     * @throws NotFoundException
     */
    public function admin_status($id = null) {
        $this->TestWebService->id = $id;

        if (!$this->TestWebService->exists()) {
            throw new NotFoundException(__('Invalid test_web_service'));
        }
        if (!isset($this->request->params['named']['token']) || ($this->request->params['named']['token'] != $this->request->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }

        if ($this->TestWebService->toggleStatus($id)) {
            $this->Session->setFlash(__('TestWebService\'s status has been changed'), 'admin_flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('TestWebService\'s status was not changed', 'admin_flash_error'));
        $this->redirect(array('action' => 'index'));
    }

}