<?php
/**
 * Application controller
 *
 * This file is the base controller of all other controllers
 *
 * PHP version 5
 *
 * @category Controllers
 * @version  1.0
 */
App::uses('CakeEmail', 'Network/Email');

class AppController extends Controller {
    /**
     * Components
     *
     * @var array
     * @access public
     */
	 
    var $preResData   = array();
	

    var $components = array(
        'Auth',
        'Session',
        'Security',
        'Upload',
        'Cookie',
        'RequestHandler',
        'Email',
    );    
	
	/**
     * Helpers
     *
     * @var array
     * @access public
     */
    var $helpers = array(
        'Html',
        'Form',
        'Session',
        'Text',
        'Js' => array('Jquery'),
        'Layout',
        'Time',
        'ExPaginator',
        'Admin',
        'General'
    );

    /**
     * Models
     *
     * @var array
     * @access public
     */
    var $uses = array();



    /**
     * beforeFilter
     *
     * @return void
     */
    public function beforeFilter() {
    	/* Set client ip */
		$clientIP = $this->get_client_ip();
		$this->Cookie->write('clientIP',$clientIP, $encrypt=false, 3600);
        
        /* Define array for element */
        $this->Security->blackHoleCallback = '__securityError';
        $this->disableCache();
        $this->loadModel('Setting');
		self::get_all_settings();
        
        if (isset($this->request->params['admin'])){
            $this->layout = 'admin'; 			
            $this->Auth->userModel = 'User';
            $this->Auth->authenticate = array('Form' => array('scope' => array('OR'=>array('User.role_id' => array(Configure::read('App.Admin.role'), Configure::read('App.Transcription.role')), 'User.is_admin'=>1),'AND'=>array('User.status' => Configure::read('App.Status.active')))));
			
            $this->Auth->loginError = "Login failed. Invalid username or password";

            $this->Auth->loginAction = array('admin' => true, 'controller' => 'admins', 'action' => 'login');
            $this->Auth->loginRedirect = array('admin' => true, 'controller' => 'admins', 'action' => 'dashboard');
            $this->Auth->authError = 'You must login to view this information.';
            $this->Auth->autoRedirect = true;
            $this->Auth->allow('admin_login');
			$admin_data = self::get_admin_user_data();
			//$this->Cookie->delete('admin');
			//$this->redirect($this->Auth->logout());
			//pr($admin_data);die;
			$this->set(compact('admin_data'));
        }else{
        	$this->Auth->userModel = 'User';
            $this->Auth->authenticate = array('Form' => array('scope' => array('User.status' => Configure::read('App.Status.active'))));
			
            $this->Auth->loginError = "Login failed. Invalid username or password";

            $this->Auth->loginAction = array('controller' => 'pages', 'action' => 'home');
            $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'my_shop');
            $this->Auth->authError = 'You must login to view this information.';
            $this->Auth->autoRedirect = true;
            $this->Auth->allow('login');
			//$userInfo = self::get_user_data();
			//$this->set(compact('userInfo'));
        }
        if ($this->RequestHandler->isAjax()){
            $this->layout = 'ajax';
            $this->autoRender = false;
            $this->Security->validatePost = false;
            Configure::write('debug', 2);
        }
       // pr($this->request);die;
		//echo $this->Cookie->read('clientIP');die;
		if (!empty($this->request->data))
		{
            array_walk_recursive($this->request->data,create_function('&$value, &$key', '$value = trim($value);') );
			return true;
        }
        
    }
	
	
	public function get_all_settings()
	{
		$this->loadModel('Setting');
		$settings=$this->Setting->find('all',array('fields'=>array('Setting.value')));	
		Configure::write('Site.title',$settings[0]['Setting']['value']);
		Configure::write('App.SiteName',$settings[0]['Setting']['value']);
		Configure::write('App.AdminMail',$settings[1]['Setting']['value']);
		
	}
		
		
    /**
     * isAuthorized
     *
     * @return void
     */

    function isAuthorized() {
        if (isset($this->params['admin'])){
            if ($this->Auth->user()) {
                    $this->Auth->allow('admin_login', 'admin_logout', 'admin_dashboard', 'admin_edit', 'admin_play', 'admin_play_video');
            }
        } else {
            return true;
        }
    }


    /**
     * blackHoleCallback for SecurityComponent
     *
     * @return void
     */
    public function __securityError() {
        
    }
	function __copy_directory( $source, $destination )
	{
		if ( is_dir( $source ) )
		{
			@mkdir( $destination );
			$directory = dir( $source );
			while ( FALSE !== ( $readdirectory = $directory->read() ) )
			{
				if ( $readdirectory == '.' || $readdirectory == '..' )
				{
					continue;
				}
				$PathDir = $source . '/' . $readdirectory;
				if ( is_dir( $PathDir ) )
				{
					$this->__copy_directory( $PathDir, $destination . '/' . $readdirectory );
					continue;
				}
				copy( $PathDir, $destination . '/' . $readdirectory );
			}
			$directory->close();
		}
		else
		{
			copy( $source, $destination );
		}
	}
	
    public function beforeRender(){
        //$this->_configureErrorLayout();
		$this->response->disableCache();
        if ($this->Auth->user()) {
            if ($this->Auth->user('role_id') == 2) {
                $this->loadModel('User');
                $this->User->id = $this->Auth->user('id');
                $timestamp = time();
                $this->User->saveField('last_activity', $timestamp);
                //$this->displaySqlDump();die;
            }
        }
        if ($this->name == 'CakeError') {
            //$this->layout = 'error';
        }
		
    }

    /**
     * sendMail
     *
     * @return	void
     * @access	private
     */
	
	public function sendMaile($to, $subject, $message, $from, $template_id = null ){
        if(($_SERVER['HTTP_HOST'] == '192.168.1.156')||($_SERVER['HTTP_HOST'] == 'localhost')){			
			$this->EmailService->to = $to;
			$this->EmailService->from = $from;
			$this->EmailService->subject = $subject;
			$this->EmailService->sendAs = 'HTML';
			$this->EmailService->delivery = 'aws_ses';
			//$this->EmailService->_aws_ses();
			//pr($this->EmailService);
			die;
      /*       $email = new CakeEmail('aws');
            //$email = new CakeEmail('gmail');
        } elseif ($_SERVER['HTTP_HOST'] == '64.15.136.251:8080') {
            $email = new CakeEmail('gmail');
        } else {
            $email = new CakeEmail();
        }
		$cc = Configure::read("App.AdminCCMail");		
        $email->template('default', 'default')
                ->emailFormat('html')
                ->from($from)
                ->to($to)
				->cc($cc)
                ->subject($subject);
		//$email->		
        if ($email->send($message))
            return true; */
        return false;
    }
	}

	public function __sendMail($to, $subject, $message, $from, $template, $smtp = 1) {
		
        $this->Email->to = $to;
        $this->Email->from = 'ripudaman.octal@gmail.com';
        $this->Email->subject = $subject;
        $this->Email->sendAs = 'html';
        $this->Email->template = $template;
        $this->Email->layout = 'default';
        $this->set('data_array', $message);
        if ($smtp == 1) {
            $this->Email->delivery = 'smtp';
            $this->Email->smtpOptions = array(
                'port' => 25,
                'timeout' => 30,
                'host' => 'localhost',
                'username' => 'ripudaman.octal@gmail.com',
                'password' => 'octal@123#'
            );
		}
        //$this->Email->delivery = 'debug';
		//unset($this->helpers['ExPaginator']);
		//die('ds');
        if ($this->Email->send()) {
			echo time();die;
        	return true;
           
        } 
		else 
		{
			die('dsjh');
            return true;
        }
    }


 	public function sendMail($to, $subject, $message, $from, $template = null,$templateLayout='default',$data=array()) {
        
		$email = new CakeEmail();
        $email->template($template,$templateLayout)
                ->emailFormat('html')
                ->from($from)
                ->to($to)
                ->subject($subject)
				->viewVars(compact('data'));
				
		
        if ($email->send($message))
			return true;
        return false;
        /*
		$email = new CakeEmail('default');
		$default = array(
                'port' => 25,
                'timeout' => 30,
                'host' => 'localhost',
                'username' => 'mahen.zed123@gmail.com',
                'password' => 'mahen@123'
            );

		
        $email->subject("Testing code");
        $email->from("admin@jtsboard.com");
        $email->to("mahenktripathi@hotmail.com");
        $email->send("Hello Purna");
        return true;
        */
    }
	
	public function rrmdir($dir) {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file))
                $this->rrmdir($file);
            else
                unlink($file);
        }

        rmdir($dir);
    }

    public function displaySqlDump(){
		if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
			return false;
		}
		$noLogs = !isset($logs);
		if ($noLogs):
			$sources = ConnectionManager::sourceList();
			$logs = array();
			foreach ($sources as $source):
				$db =& ConnectionManager::getDataSource($source);
				echo "<pre>";
				$log = $db->getLog(false, false);
				debug($log);
				die;
				if (!$db->isInterfaceSupported('getLog')):
					continue;
				endif;
				$logs[$source] = $db->getLog();
			endforeach;
		endif;

		if ($noLogs || isset($_forced_from_dbo_)):
			foreach ($logs as $source => $logInfo):
				$text = $logInfo['count'] > 1 ? 'queries' : 'query';
				printf(
					'<table class="cake-sql-log" id="cakeSqlLog_%s" summary="Cake SQL Log" cellspacing="0" border = "0">',
					preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))
				);
				printf('<caption>(%s) %s %s took %s ms</caption>', $source, $logInfo['count'], $text, $logInfo['time']);
			?>
			<thead>
				<tr><th>Nr</th><th>Query</th><th>Error</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>
			</thead>
			<tbody>
			<?php
				foreach ($logInfo['log'] as $k => $i) :
					echo "<tr><td>" . ($k + 1) . "</td><td>" . h($i['query']) . "</td><td>{$i['error']}</td><td style = \"text-align: right\">{$i['affected']}</td><td style = \"text-align: right\">{$i['numRows']}</td><td style = \"text-align: right\">{$i['took']}</td></tr>\n";
				endforeach;
			?>
			</tbody></table>
			<?php 
			endforeach;
		else:
			echo '<p>Encountered unexpected $logs cannot generate SQL log</p>';
		endif;	
	}

	
	
	function get_admin_user_data()
	{
		$user_id = $this->Session->read('Auth.User.id');
		$this->loadModel('User');
		$admin_data = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id,'OR'=>array('User.role_id'=>array(Configure::read('App.Admin.role'),Configure::read('App.Transcription.role'))))));
		
		return $admin_data;
	}
	
	function get_user_data($user_id = null)
	{
		if(empty($user_id)){
			$user_id = $this->Session->read('Auth.User.id');
		}
		$this->loadModel('User');
		$this->User->Behaviors->load('Containable');
		
		$userInfo = $this->User->find('first',array(
		'conditions'=>array('User.id'=>$user_id, 'User.status'=>1)));
		//pr($userInfo);die;
		return $userInfo;
	}
	
	function getURLContent($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $url);
		$contents = curl_exec($ch);
		curl_close($ch);
		return $contents;
	}

    /**
     * Only meant to be used in case user is not logged in and page is available only to private
     */
    public function is_ajax() {
        if ($this->RequestHandler->isAjax()) {
            return true;
        } else {
            $this->redirect($this->referer());
        }
    }

    
	// Function to get the client IP address
	public function get_client_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

}
