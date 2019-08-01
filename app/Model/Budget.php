<?php

/**
 * Budget
 *
 * PHP version 5
 *
 * @Budget Model
 *
 */
class Budget extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Budget';

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
     * get all Budget list
     */

    function get_Budget_list() {
        $charities = $this->find('list', array('conditions' => array('Budget.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getBudget($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('Budget.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('Budget.name ASC')
        ));
        return $data;
    }

    public function getSubBudget($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('Budget.status' => Configure::read('App.Status.active'), 'Budget.parent_id' => $id),
            'order' => array('Budget.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('Budget.status' => Configure::read('App.Status.active')), 'fields' => array('Budget.name', 'Budget.id')));
        return $data;
    }	
}