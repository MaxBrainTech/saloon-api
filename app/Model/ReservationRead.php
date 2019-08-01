<?php

/**
 * ReservationRead
 *
 * PHP version 5
 *
 * @ReservationRead Model
 *
 */
class ReservationRead extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'ReservationRead';

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
                    'message' => 'ReservationRead is already exist.'
                ),
            )
        )
    );

    /*
     * get all ReservationRead list
     */

    function get_ReservationRead_list() {
        $charities = $this->find('list', array('conditions' => array('ReservationRead.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getReservationRead($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('ReservationRead.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('ReservationRead.name ASC')
        ));
        return $data;
    }

    public function getSubReservationRead($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('ReservationRead.status' => Configure::read('App.Status.active'), 'ReservationRead.parent_id' => $id),
            'order' => array('ReservationRead.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('ReservationRead.status' => Configure::read('App.Status.active')), 'fields' => array('ReservationRead.name', 'ReservationRead.id')));
        return $data;
    }	
}