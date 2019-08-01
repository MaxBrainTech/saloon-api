<?php

/**
 * Expense
 *
 * PHP version 5
 *
 * @Expense Model
 *
 */
class Expense extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Expense';

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
     * get all Expense list
     */

    function get_Expense_list() {
        $charities = $this->find('list', array('conditions' => array('Expense.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getExpense($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('Expense.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('Expense.name ASC')
        ));
        return $data;
    }

    public function getSubExpense($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('Expense.status' => Configure::read('App.Status.active'), 'Expense.parent_id' => $id),
            'order' => array('Expense.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('Expense.status' => Configure::read('App.Status.active')), 'fields' => array('Expense.name', 'Expense.id')));
        return $data;
    }	
}