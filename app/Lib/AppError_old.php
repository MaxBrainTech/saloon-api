<?php
/**
 * AppError
 *
 * PHP version 5
 * 
 */
class AppError extends ErrorHandler
{

/**
* accessDenied
*
* @return void
*/
	function accessDenied()
	{
		$this->set('title_for_layout', 'Staffaway');
		$this->controller->layout = "error";
		$name = array('name' => __('You are not authorized to perform this action', TRUE));
		$this->controller->set($name);
		$this->_outputMessage('denied');
	}
/**
* securityError
*
* @return void
*/
    public function securityError() {
		$this->set('title_for_layout', 'Staffaway');
		$this->controller->set(array(
            'referer' => $this->controller->referer(),
        ));
        $this->_outputMessage('security');
    }
	
	
	function error404($params) {
		$this->controller->layout = "error";
		$this->set('title_for_layout', 'Staffaway');
		parent::error404($params);
    }

}
?>