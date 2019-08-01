<?php

/**
 * ReservationStatusRead
 *
 * PHP version 5
 *
 * @ReservationStatusRead Model
 *
 */
class ReservationStatusRead extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'ReservationStatusRead';

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
                    'message' => 'ReservationStatusRead is already exist.'
                ),
            )
        )
    );

    /*
     * get all ReservationStatusRead list
     */

    function get_ReservationStatusRead_list() {
        $charities = $this->find('list', array('conditions' => array('ReservationStatusRead.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getReservationStatusRead($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('ReservationStatusRead.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('ReservationStatusRead.name ASC')
        ));
        return $data;
    }

    public function getSubReservationStatusRead($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('ReservationStatusRead.status' => Configure::read('App.Status.active'), 'ReservationStatusRead.parent_id' => $id),
            'order' => array('ReservationStatusRead.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('ReservationStatusRead.status' => Configure::read('App.Status.active')), 'fields' => array('ReservationStatusRead.name', 'ReservationStatusRead.id')));
        return $data;
    }	
}