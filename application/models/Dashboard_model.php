<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	
	function __construct(){

		parent::__construct();
	}

	public function test() {
	    echo "Test";
    }
}
