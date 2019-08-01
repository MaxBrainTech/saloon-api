<?php

/**
 * Salon 
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class Salon extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Salon';

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
                    'message' => 'Salon is already exist.'
                )
            )
        )
    );

    /*
     * get all service list
     */

    function get_service_list() {
        $charities = $this->find('list', array('conditions' => array('Salon.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

}