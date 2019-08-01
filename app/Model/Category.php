<?php

/**
 * Category
 *
 * PHP version 5
 *
 * @Category Model
 *
 */
class Category extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Category';

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
     * get all Category list
     */

    function get_Category_list() {
        $charities = $this->find('list', array('conditions' => array('Category.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getCategory($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('Category.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('Category.name ASC')
        ));
        return $data;
    }

    public function getSubCategory($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('Category.status' => Configure::read('App.Status.active'), 'Category.parent_id' => $id),
            'order' => array('Category.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('Category.status' => Configure::read('App.Status.active')), 'fields' => array('Category.name', 'Category.id')));
        return $data;
    }	
}