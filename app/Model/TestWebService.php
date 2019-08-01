<?php

/**
 * Filter
 *
 * PHP version 5
 *
 * @category Model 
 * 
 */
class TestWebService extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'TestWebService';

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
            'title' => array(
                'checkWhiteSpaces' => array(
                    'rule' => array('checkWhiteSpace', 'title'),
                    'message' => 'No white spaces on left and right side of string.'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Title already exists.'
                ),
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Title is required'
                )
            )
        )
    );

    function check_string($field = array()) {
        $user = $field['title'];
        $value = substr($user, 0, 1);

        if (preg_match('/[A-Za-z]$/', $value) == true) {
            return true;
        } else {
            return false;
        }
        return true;
    }

    public function checkWhiteSpace($data = null, $field = null) {
        if (substr($data[$field], -1, 1) == ' ') {
            return false;
        }

        if (substr($data[$field], 0, 1) == ' ') {
            return false;
        }
        return true;
    }

}