<?php

/**
 * NoteRemainingTicket
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class NoteRemainingTicket extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'NoteRemainingTicket';

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
                    'message' => 'Note Product is already exist.'
                )
            )
        )
    );

    /*
     * get all service list
     */

    function get_service_list() {
        $charities = $this->find('list', array('conditions' => array('NoteRemainingTicket.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getService($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('NoteRemainingTicket.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('NoteRemainingTicket.product_name ASC')
        ));
        return $data;
    }

    
}