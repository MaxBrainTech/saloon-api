<?php

/**
 * PhotoFacial
 *
 * PHP version 5
 *
 * @ServiceDetail Model
 *
 */
class PhotoFacial extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'PhotoFacial';

    /**
     * Behaviors used by the Model
     *
     * @public  array
     * @access public
     */
    public $actsAs = array(
        'Multivalidatable'
    );

    /**
     * Custom validation rulesets
     *
     * @public  array
     * @access public
     */
    public $validationSets = array(
        'admin' => array(
            'name' => array(
                'checkWhiteSpaces' => array(
                    'rule' => array('checkWhiteSpace', 'name'),
                    'message' => 'Name should not contain white spaces on left and right side of string.'
                ),
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Name is required'
                ),
                'isUnique' => array(
                    'rule' => array('isUnique'),
                    'message' => 'ServiceDetail is already exist.'
                )
            )
        )
    );

    /*
     * get all ServiceDetail list
     */

    function get_ServiceDetail_list() {
        $services = $this->find('list', array('conditions' => array('ServiceDetail.status' => Configure::read('App.Status.active'))));
        return $services;
    }

    public function getServiceDetail($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('ServiceDetail.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('ServiceDetail.name ASC')
        ));
        return $data;
    }

    public function getSubServiceDetail($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('ServiceDetail.status' => Configure::read('App.Status.active'), 'ServiceDetail.parent_id' => $id),
            'order' => array('ServiceDetail.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_services_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('ServiceDetail.status' => Configure::read('App.Status.active')), 'fields' => array('ServiceDetail.name', 'ServiceDetail.id')));
        return $data;
    }	
}