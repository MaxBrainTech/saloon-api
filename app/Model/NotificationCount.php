<?php

/**
 * NotificationCount
 *
 * PHP version 5
 *
 * @NotificationCount Model
 *
 */
class NotificationCount extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'NotificationCount';

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
                    'message' => 'NotificationCount is already exist.'
                ),
            )
        )
    );

    /*
     * get all NotificationCount list
     */

    function get_NotificationCount_list() {
        $charities = $this->find('list', array('conditions' => array('NotificationCount.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getNotificationCount($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('NotificationCount.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('NotificationCount.name ASC')
        ));
        return $data;
    }

    public function getSubNotificationCount($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('NotificationCount.status' => Configure::read('App.Status.active'), 'NotificationCount.parent_id' => $id),
            'order' => array('NotificationCount.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('NotificationCount.status' => Configure::read('App.Status.active')), 'fields' => array('NotificationCount.name', 'NotificationCount.id')));
        return $data;
    }	
}