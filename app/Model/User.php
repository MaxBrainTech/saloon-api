<?php

/**
 * User
 *
 * PHP version 5

 *
 */
class User extends AppModel {

    /**
     * Model name
     *
     * @var string
     * @access public
     */
    var $name = 'User';

    /**
     * Behaviors used by the Model
     *
     * @var array
     * @access public
     */
    var $actsAs = array(
        'Multivalidatable'
    );

    /**
     * Custom validation rulesets
     *
     * @var array
     * @access public
     */
    var $validationSets = array(
      'admin' => array(
    		'username' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Username is required'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Username already exists.'
                ) ,
                'ripTags' => array(
                    'rule' => array('ripTags', 'username'),
                    'message' => 'Html & script tag are not allow.'
                ),
                'minlength' => array(
                    'rule' => array('minLength', 5),
                    'message' => 'Username must be atleast 5 characters long.'
                ),
                'maxlength' => array(
                    'rule' => array('maxLength', 25),
                    'message' => 'Username no long from 25 charcter.'
                ),
                'check_string' => array(
                    'rule' => array('check_string'),
                    'message' => 'Please start from string.'
                ), 'checkWhiteSpaces' => array(
                    'rule' => array('checkWhiteSpace', 'username'),
                    'message' => 'No white spaces on left and right side of string.'
                )
            ),
    		 'email' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Email is required'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Email already exists.'
                ),
                'email' => array(
                    'rule' => 'email',
                    'message' => 'Invalid Email.'
                ),
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'email'),
                    'message' => 'Email should not have white space at both ends'
                )
            ),
            
            'password2' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Password is required'
                ),
                'minlength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'Password must be atleast 6 characters long.'
                ),
                  'maxlength' => array(
                  'rule'	=> 	array('maxlength', 20),
                  'message'	=>	'Password no long from 20 charcter.'
                  ) ,
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'password2'),
                    'message' => 'Password should not have white space at both ends'
                )
            ),
            'confirm_password' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Confirm Password is required'
                ),
                'identicalFieldValues' => array(
                    'rule' => array('identicalFieldValues', 'password2'),
                    'message' => 'Password does not match.'
                ),
                  'maxlength' => array(
                  'rule'	=> 	array('maxlength', 20),
                  'message'	=>	'Password no long from 20 charcter.'
                  ) 
            ),
           
             'name' => array(
				'maxlength' => array(
                    'rule' => array('maxLength', 25),
                    'message' => 'First Name no long from 25 charcter.'
                ),
                 'ripTags' => array(
                    'rule' => array('ripTags', 'name'),
                    'message' => 'Html & script tag are not allow.'
                ),
            ),
	    ),
		'reset_password'	=>	array(
				'password'=>array(
					'R1'=>array(
						'rule'=>'notEmpty',
						'message' => 'Password is required.'
					)
				),
				'password2'=>array(
					'identicalFieldValues' => array(
						'rule' => array('identicalFieldValues', 'password' ),
						'message' => 'Passwords does not match.'
					),
					'R2'=>array(
						'rule'=>'notEmpty',
						'message' => 'Confirm password is required.'
					)
				)
		),
			
        'admin_change_password' => array(
            'new_password' => array(                
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'New password is required.'
                ),
                'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'Passwords must be at least 6 characters long.'
                ),
              'maxlength' => array(
                    'rule' => array('maxLength', 20),
                    'message' => 'Password no long from 20 charcter.'
                ),
            ),
            'confirm_password' => array(                
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'confirm password is required.'
                ),
                'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'confirm password must be at least 6 characters long.'
                ),
                'match_password' => array(
                    'rule' => 'adminConfirmPassword',
                    'message' => 'confirm password should be same as new password.'
                ),
              'maxlength' => array(
                    'rule' => array('maxLength', 20),
                    'message' => 'Confirm Password no long from 20 charcter.'
                ),
                        
            )
        ),
        'change_password' => array(
            'password' => array(                
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'New password is required.'
                ),
				'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'Passwords must be at least 6 characters long.'
                ),
              'maxlength' => array(
                    'rule' => array('maxLength', 20),
                    'message' => 'Password no long from 20 charcter.'
                ),
            ),
            'confirm_password' => array(                
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'confirm password is required.'
                ),
				'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'confirm password must be at least 6 characters long.'
                ),
				'match_password' => array(
                    'rule' => 'userConfirmPassword',
                    'message' => 'confirm password should be same as new password.'
                ),
              'maxlength' => array(
                    'rule' => array('maxLength', 20),
                    'message' => 'Confirm Password no long from 20 charcter.'
                ),
                		
            )
        ),
		'forgot_password' => array(
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
                    'message' => 'Email should not have white space at both ends'
                )
            )
        ),
        'login' => array(
         	'login_email' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Email is required'
                ),
                'login_email' => array(
                    'rule' => 'email',
                    'message' => 'Invalid Email.'
                ),
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'login_email'),
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
                  'rule'	=> 	array('maxlength', 15),
                  'message'	=>	'Password no long from 15 charcter.'
                  ),
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'password'),
                    'message' => 'Password should not have white space at both ends'
                )
            )
       ),
       'register' => array(
         	
            'email' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Email Address is required'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Email Address already exists.'
                ),
                'email' => array(
                    'rule' => 'email',
                    'message' => 'Invalid Email Address.'
                ),
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'email'),
                    'message' => 'Email Address should not have white space at both ends'
                )
            ),
            'subscription_plan_id' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Subscription Plan is required'
                )
            ),
            'name' => array(
                  'maxlength' => array(
                  'rule'	=> 	array('maxlength', 25),
                  'message'	=>	'First name no long from 25 charcter.'
                  ) ,
                  'ripTags' => array(
                    'rule' => array('ripTags', 'name'),
                    'message' => 'Html & script tag are not allow.'
                ),
				'checkWhiteSpaces' => array(
                    'rule' => array('checkWhiteSpace', 'name'),
                    'message' => 'No white spaces on left and right side of string.'
                )
                ),
            'password2' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Password is required'
                ),
                'minlength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'Password must be atleast 6 characters long.'
                ),
                  'maxlength' => array(
                  'rule'	=> 	array('maxlength', 15),
                  'message'	=>	'Password no long from 15 charcter.'
                  ),
                'checkWhiteSpace' => array(
                    'rule' => array('checkWhiteSpace', 'password2'),
                    'message' => 'Password should not have white space at both ends'
                )
            ),
            'confirm_password' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Confirm Password is required'
                ),
                'identicalFieldValues' => array(
                    'rule' => array('identicalFieldValues', 'password2'),
                    'message' => 'Password does not match.'
                )
            ),
           
            
        ),
		'register_mobile_app' => array(
            'username' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Username is required'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'username already exists.'
                )
            ),
            'email' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Email is required'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Email Id already exists.'
                ),
                'email' => array(
                    'rule' => 'email',
                    'message' => 'Invalid Email.'
                )
            ),
            'display_name' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Display Name is required'
                ),
				'checkWhiteSpaces' => array(
					'rule' => array('checkWhiteSpace', 'display_name'),
					'message' => 'No white spaces on left and right side of string.'
				),
				'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'name is less than 6 characters long'
                )
            ),
            'gender' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Gender is required.'
                )
            ),
            'password' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Gender is required.'
                ),
				'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => 'password is less than 6 characters long'
                )
            ),
        ),

        'registration' => array(
        'name' => array(
            'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => 'Name is required.'
            ),
        'maxLength' => array(
                'rule' => array('maxLength', 25),
                'message' => 'password is less than 25 characters long'
            )
        ),
        'salon_name' => array(
            'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => 'Salon Name is required.'
            )
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Email Address is required'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Email Address already exists.'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Invalid Email Address.'
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
            'checkWhiteSpace' => array(
                'rule' => array('checkWhiteSpace', 'password'),
                'message' => 'Password should not have white space at both ends'
            )
        ),
        'confirm_password' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Confirm Password is required'
            ),
            'identicalFieldValues' => array(
                'rule' => array('identicalFieldValues', 'password'),
                'message' => 'Password does not match.'
            )
        ),
        'employee_pin_number' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Employee Pin Number is required'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Employee Pin Number should be Number'
            )
        ),
        'customer_pin_number' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Customer Pin Number is required'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Customer Pin Number should be Number'
            )
        ),
        'zip_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Zip Code is required'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Zip Code should be Number'
            )
        ),
        'prefecture' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Prefecture is required'
            )
        ),
        'city' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'City is required'
            )
        ),
        'address1' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Address 1 is required'
            )
        ),
        'address2' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Address 2 is required'
            )
        ),
        'tel' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Phone Number is required'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Phone Number should be Number'
            )
        ),
        'website' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Website is required'
            ),
            'url' => array(
                'rule' => 'url',
                'message' => 'Website should be URL (www.website-name.com)'
            )
        ),
        'employee_number' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Employee Number is required'
            )
        ),
        'advertisement' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Advertisement is required'
            )
        ),
        'avr_customer' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'AVR Customer is required'
            )
        ),
    ),
		
    );
	
	public function savePicture($data)
	{
	//pr($data);die('test');
		if(!empty($data['profile_image']['name']))
		{
			$file = $data['profile_image'];
			if($file['type'] == 'image/jpeg' || $file['type'] == 'image/png' || $file['type'] == 'image/gif' || $file['type'] == 'image/jpg' )
			{
				if($file['size'] <= 2097152)
				{
					return true;
				}
				else
				{
					return false;
				}

			}
			else
			{
				return false;
			}
		}
		else
		{
			return true;
		}
	}
	
	public function save_cover_image($data)
	{
	//pr($data);die('test');
		if(!empty($data['profile_cover_image']['name']))
		{
			$file = $data['profile_cover_image'];
			if($file['type'] == 'image/jpeg' || $file['type'] == 'image/png' || $file['type'] == 'image/gif' || $file['type'] == 'image/jpg' )
			{
				if($file['size'] <= 2097152)
				{
					return true;
				}
				else
				{
					return false;
				}

			}
			else
			{
				return false;
			}
		}
		else
		{
			return true;
		}
	}
	public function checkbirthday($data)
	{
		//pr($data);die('octal');
		
		if(!empty($data['dob']))
		{
			return true;
		}
		else
		{
			return true;
		}
	}
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        $parameters = compact('conditions');
        $this->recursive = $recursive;
        $count = $this->find('count', array_merge($parameters, $extra));

        if (isset($extra['group'])) {

            $count = $this->getAffectedRows();
        }
        return $count;
    }

    public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
        if (empty($order)) {
            $order = array($extra['passit']['sort'] => $extra['passit']['direction']);
        }
        if (isset($extra['group'])) {
            $group = $extra['group'];
        }
        if (isset($extra['joins'])) {
            $joins = $extra['joins'];
        }
        return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'joins'));
    }

    /* check for identical values in field */

    function identicalFieldValues($field = array(), $compare_field = null) {
        foreach ($field as $key => $value) {
            $v1 = $value;
            $v2 = $this->data[$this->name][$compare_field];

            if ($v1 !== $v2) {
                return false;
            } else {
                continue;
            }
        }
        return true;
    }
	
	## Function Password Match ##
	function PasswordMatch($data){
		//pr($this->data);die;
		$op =  Security::hash($this->data['User']['oldpassword'], null, true);
		
		//echo $this->data['User']['hidden_password'];
		//echo "<br>".$this->data['User']['hidden_password'];die;
			//echo $this->data['User']['hidden_password']."<br>".$op;die;
		
		if ($this->data['User']['hidden_password'] != $op)
		{
			//$this->data['User']['hidden_password'];
			//die("RAM");
			return false;   
		}
		return true;   
	}
	
	
    /* check confirm password */
    function adminConfirmPassword() {
        if (!empty($this->data['User']['new_password'])) {
            if ($this->data['User']['new_password'] != $this->data['User']['confirm_password']) 			{
                return false;
            } else {
                return true;
            }
        }
    }

    function userConfirmPassword() {
        if (!empty($this->data['User']['password'])) {
            if ($this->data['User']['password'] != $this->data['User']['confirm_password'])             {
                return false;
            } else {
                return true;
            }
        }
    }
	
    /* check confirm password */
    function confirmPassword() {
        if (!empty($this->data['User']['user_password'])) {
            if ($this->data['User']['user_password'] != $this->data['User']['confirm_password']) 			{
                return false;
            } else {
                return true;
            }
        }
    }
    /* check existing email */

    function checkEmail($data = null, $field = null){
        if (!empty($field)) {
            if (!empty($this->data[$this->name][$field])) {
                if ($this->hasAny(array('User.email' => $this->data[$this->name][$field], 'User.status' => Configure::read('App.Status.active')))) {
                    return true;
                } elseif ($this->hasAny(array('User.username' => $this->data[$this->name][$field], 'User.status' => Configure::read('App.Status.active')))) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }


    function beforeValidate($options = array()){
        foreach ($this->hasAndBelongsToMany as $k => $v) {
            if (isset($this->data[$k][$k])) {
                $this->data[$this->alias][$k] = $this->data[$k][$k];
            }
        }
    }

    function check_string($field = array()){
		//pr($field);die;
        $user = $field['username'];
        $value = substr($user, 0, 1);

        if (preg_match('/[A-Za-z]$/', $value) == true) {
            return true;
        } else {
            return false;
        }
        return true;
    }

    public function get_users($type, $fields = '*', $cond = array(), $order = 'User.id desc', $limit = 999, $offset = 0) {
        $users = $this->find($type, array('conditions' => array($cond), 'fields' => array($fields), 'order' => array($order), 'offset' => $offset, 'limit' => $limit));

        return $users;
    }

    		
	function apple_push_notification($device_token = null, $msg = null, $requestType = null, $sound_status = null){
			//$device_token = "5b5b552588706d7a8ac96c34241293ecff2099228753a963fdb59d1e4159a3fe";		
			App::import('Vendor', 'push', array('file' => 'push/urbanairship.php'));
			$APP_MASTER_SECRET  = "Gu-TyT06Qn2q7twy9pCa-g";
			$APP_KEY 			= "t_uXfVmtTdKg3uM4zk1sRA";	
			$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
			$token = $device_token;
			$message = array('aps'=>array('alert'=>$msg));
			$arr=$airship->push($message, $token, array(), array('testTag'), $requestType, $sound_status);
	}
		
	function android_push_notification($device_tokens = array(), $msg = null){
			
			App::import('Vendor', 'send_notification', array('file' => 'gcm_server_php/GCM.php'));
			$gcm = new GCM();
			$result = $gcm->send_notification($device_tokens, $msg);
	}
	public function ripTags($data = null, $field = null) { 
	   
	    $string = preg_match_all ('/<[^>]*>/', trim($data[$field]), $data[$field]); 
	    if($string){
	    	return false;
	    }else{
	    	return true;
	    }
	    
	}
}

