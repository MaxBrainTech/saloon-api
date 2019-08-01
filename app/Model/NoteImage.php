<?php

/**
 * NoteImage
 *
 * PHP version 5
 *
 * @note_image Model
 *
 */
class NoteImage extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'NoteImage';

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
                    'message' => 'NoteImage is already exist.'
                )
            )
        )
    );

    /*
     * get all note_image list
     */

    function get_note_image_list() {
        $charities = $this->find('list', array('conditions' => array('NoteImage.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getNoteImage($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('NoteImage.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('NoteImage.name ASC')
        ));
        return $data;
    }

    public function getSubNoteImage($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('NoteImage.status' => Configure::read('App.Status.active'), 'NoteImage.parent_id' => $id),
            'order' => array('NoteImage.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('NoteImage.status' => Configure::read('App.Status.active')), 'fields' => array('NoteImage.name', 'NoteImage.id')));
        return $data;
    }	
}