<?php

/**
 * UserCategory
 *
 * PHP version 5
 *
 * @UserCategory Model
 *
 */
class UserCategory extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'UserCategory';

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
    

    public function get_category_name($id =null){
        if(!empty($id)){    
            $data = $this->find('first', array('conditions' => array('UserCategory.id' =>$id)));
            if(isset($data['UserCategory']['name']))
                return $data['UserCategory']['name'];
            else
                return '';
        }else{
            return '';
        }    
       
    }

    /*
     * get all UserCategory list
     */

    function get_UserCategory_list() {
        $charities = $this->find('list', array('conditions' => array('UserCategory.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getUserCategory($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('UserCategory.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('UserCategory.name ASC')
        ));
        return $data;
    }

    public function getSubUserCategory($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('UserCategory.status' => Configure::read('App.Status.active'), 'UserCategory.parent_id' => $id),
            'order' => array('UserCategory.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('UserCategory.status' => Configure::read('App.Status.active')), 'fields' => array('UserCategory.name', 'UserCategory.id')));
        return $data;
    }	
}