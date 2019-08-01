<?php

/**
 * MainService
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class MainService extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'MainService';

    /**
     * Behaviors used by the Model
     *
     * @public  array
     * @access public
     */
    public $actsAs = array(
        'Multivalidatable'
    );

    
}