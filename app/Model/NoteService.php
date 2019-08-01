<?php

/**
 * NoteService
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class NoteService extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'NoteService';

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
                    'message' => 'Note Service is already exist.'
                )
            )
        )
    );

    /*
     * get all service list
     */

    function get_service_list() {
        $charities = $this->find('list', array('conditions' => array('NoteService.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getNoteService($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('NoteService.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('NoteService.name ASC')
        ));
        return $data;
    }

   
}