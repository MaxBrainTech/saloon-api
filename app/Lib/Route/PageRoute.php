<?php
class PageRoute extends CakeRoute { 
    function parse($url) {
        $params = parent::parse($url);		
        if (empty($params)) {
            return false;
        }
        App::uses('Page', 'Model');
        $Page = new Page();
        $count = $Page->find('count', array(
            'conditions' => array('Page.slug LIKE ?' => $params['slug'] .'%'),
            'recursive' => -1
        ));
		
        if ($count) {		
		   return $params;
        }
		
        return false;
    }	
}