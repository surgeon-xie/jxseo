<?php

require FCPATH.'dayrui/core/D_Form.php';

class Form_email extends D_Form {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->_post();
	}

}