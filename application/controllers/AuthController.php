<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class AuthController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));
		$this->load->library('session');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	// public function index()
	// {
	// 	echo 'd';
	// 	print_r($this->session->userdata('loggedIn'));
	// 	exit;
	// 	if (!$this->ion_auth->logged_in())
	// 	{
	// 		// redirect them to the login page
	// 		//redirect('auth/login', 'refresh');
	// 		return $this->output
	// 					->set_content_type('application/json')
	// 					->set_status_header(200)
	// 					->set_output(json_encode(array(
	// 							'text' => 'Open login modal',
	// 							'type' => 'danger'
	// 					)));
	// 	}

	// 	else
	// 	{
	// 		// set the flash data error message if there is one
	// 		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

	// 		//list the users
	// 		$this->data['users'] = $this->ion_auth->users()->result();
	// 		foreach ($this->data['users'] as $k => $user)
	// 		{
	// 			$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
	// 		}

	// 		$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
	// 	}
	// }
	/**
	 * Create a new user
	 */
	public function register()
	{
		


		$this->data['page_heading'] = $this->lang->line('create_user_heading');

	
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = 'username';
		//$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		//if ($identity_column !== 'email')
		//{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			//$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		//}
		//else
		//{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]|matches[confirm_email]');
		//}

		$this->form_validation->set_rules('confirm_email', $this->lang->line('create_user_validation_confirm_email_label'), 'trim|required|valid_email');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$email = strtolower($this->input->post('email'));
			$identity = strtolower($this->input->post('identity'));//($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name')
			);
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data))
		{
			// check to see if we are creating the user
			// redirect them back to the admin page
			
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			return $this->output
						->set_content_type('application/json')
						->set_status_header(200)
						->set_output(json_encode(array(
								'data' => [],
								'errors' => [],
								'message' => 'User has been successfully created',
								'status' => true,
								'loggedIn' => $this->session->userdata('user_id')
						)));
		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$validation_errors = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$validation_errors = explode('.', strip_tags($validation_errors));
			$errors = array();
			//$validation_errors = array('abc', 'xyz','mno');
			if(count($validation_errors) >0) {
				foreach($validation_errors as $error) {
					$errors[] = trim(preg_replace('/\s\s+/', ' ', $error));
				}
			}
			//print_r($errors);
			$err = array_pop($errors);
			return $this->output
						->set_content_type('application/json')
						->set_status_header(200)
						->set_output(json_encode(array(
								'data' => [],
								'errors' => $errors,
								'message' => '',
								'status' => false
								
						)));
		}
	}
	public function index() {
		echo $this->session->userdata('abc');
	}
	/**
	 * Log the user in
	 */
	public function login()
	{
		
		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		
		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				// $this->session->set_userdata(array('id' => $this->session->userdata('user_id')));
				return $this->output
						->set_content_type('application/json')
						->set_status_header(200)
						->set_output(json_encode(array(
								'loggedIn' => $this->session->userdata('user_id'),
								'errors' => [],
								'message' => 'Successfully logged in',
								'status' => true,
								'formValidation' => true
								
						)));
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$invalidCredentialsErrors = $this->ion_auth->errors();

				return $this->output
						->set_content_type('application/json')
						->set_status_header(200)
						->set_output(json_encode(array(
								'data' => [],
								'errors' => [],
								'message' => $invalidCredentialsErrors,
								'status' => false,
								'formValidation' => true
								
						)));
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$validation_errors = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$validation_errors = explode('.', strip_tags($validation_errors));

				return $this->output
						->set_content_type('application/json')
						->set_status_header(200)
						->set_output(json_encode(array(
								'data' => [],
								'errors' => array(
									'identity' 		=> $validation_errors[0], 
									'password' 		=> $validation_errors[1], 
								),
								'message' => 'aaaa',
								'status' => false,
								'formValidation' => false
								
						)));
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['page_heading'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('/', 'refresh');
	}


	public function test() {
		$this->session->set_userdata('abc', 1);
		echo $this->session->userdata('abc');
		
	}

	public function pjAuthForm() {
		if ($this->ion_auth->logged_in())
		{
		  redirect('account');
		}
		$this->data['page_heading'] = "Login Form";
		$this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/auth/index');
		$this->load->view('frontend/layout/footer');
	}
	public function pjAccount() {

		$this->data['page_heading'] = "My Account";
		$this->load->view('frontend/layout/head', $this->data);
		$this->load->view('frontend/layout/header');
		$this->load->view('frontend/pages/account/index');
		$this->load->view('frontend/layout/footer');
	}


}
