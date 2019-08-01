<?php
// Define global $CONFIG array - don't change this!
	global $CONFIG;
	$CONFIG = array();
	$CONFIG['connect'] = new stdClass();

// Set account details for sending party
	$CONFIG['connect']->user = 'sendinguser';    // User portion of JID
	$CONFIG['connect']->host = '192.168.1.67';    // Host portion of JID
	$CONFIG['connect']->resc = 'wellconnected';// Resource portion of JID
	$CONFIG['connect']->pass = '123456';    // Password for user
	$CONFIG['cachedir'] = '/tmp/';
	$CONFIG['receive'] = 'testinguser';
	
	
	
?>