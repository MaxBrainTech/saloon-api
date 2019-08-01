<?php
/**
 * Setting
 *
 * PHP version 5
 *
 * @category Model 
 * 
 */
class Setting extends AppModel{
	/**
	 * Model name
	 *
	 * @public  string
	 * @access public
	 */
	public  $name = 'Setting';
	
	/**
	 * Behaviors used by the Model
	 *
	 * @public  array
	 * @access public
	 */
    public  $actsAs = array(        
        'Multivalidatable'
    );	
	
	
	 /**
	 * Custom validation rulesets
	 *
	 * @public  array
	 * @access public
	 */	
	public  $validationSets = array(
		'admin'	=>	array(		
			'value'=>array(
				'notEmpty' => array(
					'rule' 		=> 'notEmpty',
					'message' 	=>	'This field can not be left blank.'
				)
			)		
		)	
	);	
	
	public function get($name){
		//return $this->findByName($name,'value')['Setting']['value'];
	}
	
	
	
}