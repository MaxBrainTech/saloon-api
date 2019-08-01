<?php
class PagesController extends AppController {
	public function home() {
		$this->redirect(array('controller'=>'../users', 'action' => 'login'));
	}
}