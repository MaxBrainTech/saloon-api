<?php

/**
 * Product
 *
 * PHP version 5
 *
 * @service Model
 *
 */
class Product extends AppModel {

    /**
     * Model name
     *
     * @public  string
     * @access public
     */
    public $name = 'Product';

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
                    'message' => 'Service is already exist.'
                )
            )
        )
    );

    /*
     * get all service list
     */

    function get_service_list() {
        $charities = $this->find('list', array('conditions' => array('Product.status' => Configure::read('App.Status.active'))));
        return $charities;
    }

    public function getService($id, $type) {
        $data = $this->find($type, array(
            'conditions' => array('Product.status' => Configure::read('App.Status.active'), 'id' => $id),
            'order' => array('Product.name ASC')
        ));
        return $data;
    }

    public function getSubService($id, $type){
        $data = $this->find($type, array(
            'conditions' => array('Product.status' => Configure::read('App.Status.active'), 'Product.parent_id' => $id),
            'order' => array('Product.name ASC'),
            'fields' => array('parent_id')
        ));
        return $data;
    }

    function get_parent_charities_front($type_for = 0) {
        $data = $this->find('all', array('conditions' => array('Product.status' => Configure::read('App.Status.active')), 'fields' => array('Product.name', 'Product.id')));
        return $data;
    }	
    public function getProductName($id) {
        // echo $id;
        $data = $this->find("first", array('conditions' => array('Product.status' => Configure::read('App.Status.active'), 'id' => $id),'fields' => array('Product.product_name')));
        // pr($data);die;
        return $data['Product']['product_name'];
    }
}