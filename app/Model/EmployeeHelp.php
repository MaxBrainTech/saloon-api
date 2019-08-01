<?php

/**
 * EmployeeHelp
 *
 * PHP version 5
 *
 * @EmployeeHelp Model
 *
 */
class EmployeeHelp extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'EmployeeHelp';

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
                    'rule' => array('isUnique',array('user_id','name'),false),
                    // 'rule' => array('isUnique'),
                    'message' => 'EmployeeHelp is already exist.'
                ),
            )
        )
    );

    /*
     * get all EmployeeHelp list
     */

    function get_EmployeeHelp_list() {
        $charities = $this->find('list', array('conditions' => array('EmployeeHelp.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getEmployeeHelp($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('EmployeeHelp.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('EmployeeHelp.name ASC')
        ));
        return $data;
    }

    public function getSubEmployeeHelp($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('EmployeeHelp.status' => Configure::read('App.Status.active'), 'EmployeeHelp.parent_id' => $id),
            'order' => array('EmployeeHelp.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('EmployeeHelp.status' => Configure::read('App.Status.active')), 'fields' => array('EmployeeHelp.name', 'EmployeeHelp.id')));
        return $data;
    }	
}