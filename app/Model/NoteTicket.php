<?php

/**
 * NoteTicket
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class NoteTicket extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'NoteTicket';

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
        $charities = $this->find('list', array('conditions' => array('NoteTicket.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getService($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('NoteTicket.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('NoteTicket.product_name ASC')
        ));
        return $data;
    }
    public function getNoteTicketName($id) {
        // echo $id;
        $data = $this->find("first", array('conditions' => array('NoteTicket.status' => Configure::read('App.Status.active'), 'id' => $id),'fields' => array('NoteTicket.ticket_name')));
        // pr($data);die;
        return $data['NoteTicket']['ticket_name'];
    }
    
}