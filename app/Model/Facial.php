<?php

/**
 * Facial
 *
 * PHP version 5
 *
 * @Facial Model
 *
 */
class Facial extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Facial';

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
                    'message' => 'Facial is already exist.'
                )
            )
        )
    );

    /*
     * get all Facial list
     */

    function get_Facial_list() {
        $services = $this->find('list', array('conditions' => array('Facial.status' => Configure::read('App.Status.active'))));
        return $services;
    }

    public function getFacial($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('Facial.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('Facial.name ASC')
        ));
        return $data;
    }

    public function getSubFacial($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('Facial.status' => Configure::read('App.Status.active'), 'Facial.parent_id' => $id),
            'order' => array('Facial.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_services_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('Facial.status' => Configure::read('App.Status.active')), 'fields' => array('Facial.name', 'Facial.id')));
        return $data;
    }	
}