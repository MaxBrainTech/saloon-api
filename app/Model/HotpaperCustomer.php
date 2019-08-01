<?php

/**
 * HotpaperCustomer
 *
 * PHP version 5
 *
 * @HotpaperCustomer Model
 *
 */
class HotpaperCustomer extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'HotpaperCustomer';

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