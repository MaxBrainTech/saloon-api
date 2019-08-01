<?php

/**
 * Ticket
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class Ticket extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Ticket';

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
                    'message' => 'Service is already exist.'
                )
            )
        )
    );

    /*
     * get all service list
     */

    function get_service_list() {
        $charities = $this->find('list', array('conditions' => array('Ticket.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getService($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('Ticket.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('Ticket.name ASC')
        ));
        return $data;
    }

    public function getSubService($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('Ticket.status' => Configure::read('App.Status.active'), 'Ticket.parent_id' => $id),
            'order' => array('Ticket.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('Ticket.status' => Configure::read('App.Status.active')), 'fields' => array('Ticket.name', 'Ticket.id')));
        return $data;
    }	
    public function getTicketName($id) {
        // echo $id;
        $data = $this->find("first", array('conditions' => array('Ticket.status' => Configure::read('App.Status.active'), 'id' => $id),'fields' => array('Ticket.ticket_name')));
        // pr($data);die;
        return $data['Ticket']['ticket_name'];
    }
    public function getTicketNumTimeName($id) {
        // echo $id;
        $data = $this->find("first", array('conditions' => array('Ticket.status' => Configure::read('App.Status.active'), 'id' => $id),'fields' => array('Ticket.ticket_num_time')));
        // pr($data);die;
        return $data['Ticket']['ticket_num_time'];
    }
}