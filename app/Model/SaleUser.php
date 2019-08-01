<?php

/**
 * SaleUser
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class SaleUser extends AppModel {

    /**
     * SaleUser
     *
     * @public  string
     * @access public
     */
    public $name = 'SaleUser';

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

    var $validationSets = array(
        'login' => array(
            'email' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Email is required'
                ),
                'email' => array(
                    'rule' => 'email',
                    'message' => 'Invalid Email.'
                ),
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'email'),
                    'message' => 'Email Address should not have white space at both ends'
                )
             ),
            'password' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Password is required'
                ),
                'minlength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'Password must be atleast 6 characters long.'
                ),
                'maxlength' => array(
                    'rule'   =>  array('maxlength', 15),
                    'message'    =>  'Password no long from 15 charcter.'
                ),
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'password'),
                    'message' => 'Password should not have white space at both ends'
                )
            )
        ),
    );
}