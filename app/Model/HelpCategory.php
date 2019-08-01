<?php

/**
 * HelpCategory
 *
 * PHP version 5
 *
 * @HelpCategory Model
 *
 */
class HelpCategory extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'HelpCategory';

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
     * get all HelpCategory list
     */

    function get_HelpCategory_list() {
        $charities = $this->find('list', array('conditions' => array('HelpCategory.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getHelpCategory($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('HelpCategory.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('HelpCategory.name ASC')
        ));
        return $data;
    }

    public function getSubHelpCategory($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('HelpCategory.status' => Configure::read('App.Status.active'), 'HelpCategory.parent_id' => $id),
            'order' => array('HelpCategory.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('HelpCategory.status' => Configure::read('App.Status.active')), 'fields' => array('HelpCategory.name', 'HelpCategory.id')));
        return $data;
    }	
}