<?php

/**
 * Reservation Service
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class Reservation extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Reservation';

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
        )
    );

    /*
     * get all service list
     */

    function get_reservation_list() {
        $reservations = $this->find('list', array('conditions' => array('Reservation.status' => Configure::read('App.Status.active'))));
        return $reservations;
    }

}