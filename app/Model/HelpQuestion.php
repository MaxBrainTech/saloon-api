<?php

/**
 * HelpQuestion
 *
 * PHP version 5
 *
 * @HelpQuestion Model
 *
 */
class HelpQuestion extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'HelpQuestion';

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
                    'message' => 'HelpQuestion is already exist.'
                ),
            )
        )
    );

    /*
     * get all HelpQuestion list
     */

    function get_HelpQuestion_list() {
        $charities = $this->find('list', array('conditions' => array('HelpQuestion.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getHelpQuestion($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('HelpQuestion.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('HelpQuestion.name ASC')
        ));
        return $data;
    }

    public function getSubHelpQuestion($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('HelpQuestion.status' => Configure::read('App.Status.active'), 'HelpQuestion.parent_id' => $id),
            'order' => array('HelpQuestion.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('HelpQuestion.status' => Configure::read('App.Status.active')), 'fields' => array('HelpQuestion.name', 'HelpQuestion.id')));
        return $data;
    }	
}