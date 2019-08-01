<?php
/**
 * Template
 *
 * PHP version 5
 *
 * @category Model 
 * 
 */
class Template extends AppModel{
	/**
	 * Model name
	 *
	 * @public  string
	 * @access public
	 */
	public  $name = 'Template';
	
	
	
	
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
			'name'=>array(
				'isUnique'	=>	array(
					'rule'	=>	'isUnique',
					'message'	=>	'Template Name already exists.'
				),	
				'checkWhiteSpaces'	=> array(
					'rule'	=> 	array('checkWhiteSpace', 'name'),
					'message' =>'Template Name should not contain white spaces on left and right side of string.'					
				),
				'notEmpty' => array(
					'rule' 		=> 'notEmpty',
					'message' 	=>	'Template Name is required'
				)
			),
			'subject'=>array(
				'isUnique'	=>	array(
					'rule'	=>	'isUnique',
					'message'	=>	'Email Subject already exists.'
				),	
				'checkWhiteSpaces'	=> array(
					'rule'	=> 	array('checkWhiteSpace', 'subject'),
					'message' =>'Email Subject Name should not contain white spaces on left and right side of string.'					
				),
				'notEmpty' => array(
					'rule' 		=> 'notEmpty',
					'message' 	=>	'Email Subject Name is required'
				)
			),
			'content'=>array(
				'notEmpty' => array(
					'rule' 		=> 'notEmpty',
					'message' 	=>	'Template Content is required'
				)
			)		
		)	
	);
	
	
	
}