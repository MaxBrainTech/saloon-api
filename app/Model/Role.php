<?php

/**
 * Role
 *
 * PHP version 5
 *
 * @role Model
 *
 */
class Role extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Role';

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
                    'message' => 'Role is already exist.'
                )
            )
        )
    );

    /*
     * get all role list
     */

    function get_role_list() {
        $charities = $this->find('list', array('conditions' => array('Role.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getRole($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('Role.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('Role.name ASC')
        ));
        return $data;
    }

    public function getSubRole($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('Role.status' => Configure::read('App.Status.active'), 'Role.parent_id' => $id),
            'order' => array('Role.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('Role.status' => Configure::read('App.Status.active')), 'fields' => array('Role.name', 'Role.id')));
        return $data;
    }	
}