<?php defined('BASEPATH') OR exit('No direct script access allowed');


class AccountController extends App_Controller
{
	protected 	$option_arr 			= array();
	protected 	$optionArr 				= 'option_arr';
	protected 	$locale_arr 			= 'locale_arr';
	public function __construct()
	{
		parent::__construct();
		if(!$this->getSession('loggedIn')) {
			redirect('auth/login');
		}
		
		
	
	}
	public function pjAccountForm() {

		$this->data['title'] = "My Account";
		$this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/account/index');
		$this->load->view('frontend/layout/footer');
	}
}
