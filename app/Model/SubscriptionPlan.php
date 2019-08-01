<?php

/**
 * SubscriptionPlan
 *
 * PHP version 5
 *
 * @SubscriptionPlan Model
 *
 */
class SubscriptionPlan extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'SubscriptionPlan';

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
                )
            )
        )
    );

    /*
     * get all SubscriptionPlan list
     */

    function get_SubscriptionPlan_list() {
        $charities = $this->find('list', array('conditions' => array('SubscriptionPlan.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getSubscriptionPlan($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('SubscriptionPlan.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('SubscriptionPlan.name ASC')
        ));
        return $data;
    }

    public function getSubSubscriptionPlan($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('SubscriptionPlan.status' => Configure::read('App.Status.active'), 'SubscriptionPlan.parent_id' => $id),
            'order' => array('SubscriptionPlan.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('SubscriptionPlan.status' => Configure::read('App.Status.active')), 'fields' => array('SubscriptionPlan.name', 'SubscriptionPlan.id')));
        return $data;
    }	
}