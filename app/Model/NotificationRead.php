<?php

/**
 * NotificationRead
 *
 * PHP version 5
 *
 * @NotificationRead Model
 *
 */
class NotificationRead extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'NotificationRead';

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
                    'message' => 'NotificationRead is already exist.'
                ),
            )
        )
    );

    /*
     * get all NotificationRead list
     */

    function get_NotificationRead_list() {
        $charities = $this->find('list', array('conditions' => array('NotificationRead.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getNotificationRead($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('NotificationRead.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('NotificationRead.name ASC')
        ));
        return $data;
    }

    public function getSubNotificationRead($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('NotificationRead.status' => Configure::read('App.Status.active'), 'NotificationRead.parent_id' => $id),
            'order' => array('NotificationRead.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('NotificationRead.status' => Configure::read('App.Status.active')), 'fields' => array('NotificationRead.name', 'NotificationRead.id')));
        return $data;
    }	
}