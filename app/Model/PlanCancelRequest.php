<?php

/**
 * PlanCancelRequest
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class PlanCancelRequest extends AppModel {

    /**
     * PlanCancelRequest
     *
     * @public  string
     * @access public
     */
    public $name = 'PlanCancelRequest';

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


}