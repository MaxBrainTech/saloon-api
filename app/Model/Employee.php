<?php

/**
 * Employee Service
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class Employee extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Employee';

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
                    'message' => 'Customer is already exist.'
                )
            )
        ),
        'employee_login' => array(
            'emp_code' => array(
                'checkWhiteSpaces' => array(
                    'rule' => array('checkWhiteSpace', 'emp_code'),
                    'message' => 'Employee code should not contain white spaces on left and right side of string.'
                ),
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Employee code is required'
                )
            )
        )
    );

    /*
     * get all service list
     */

    function get_service_list() {
        $charities = $this->find('list', array('conditions' => array('Esthe.status' => Configure::read('App.Status.active'))));
        return $charities;
    }
    public function getEmployeeName($id) {
        $data = $this->find("first", array('conditions' => array('Employee.status' => Configure::read('App.Status.active'), 'id' => $id),'fields' => array('Employee.name')));
        return $data['Employee']['name'];
    }

}