<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();    
        $this->load->library('encrypt');//load this library. 
    }
    
    public function index(){
        
        if(isset($_COOKIE[LOGIN_COOKIE_NAME]) && !empty($_COOKIE[LOGIN_COOKIE_NAME])){
            redirect(base_url().'user/dashboard');
        }
        
        $data = array();
        $data['return_to'] = $this->input->get('return_to', TRUE);
        $this->load->view('frontend/login', $data);
    }
    
    public function signup(){
        
        if(isset($_COOKIE[LOGIN_COOKIE_NAME]) && !empty($_COOKIE[LOGIN_COOKIE_NAME])){
            redirect(base_url().'user/dashboard');
        }
                
        $data = array();                
        $this->load->view('frontend/signup', $data);
    }
    
    public function doRegister(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {            
            $sf_user_f_name = stripslashes($this->input->post('sf_user_f_name'));
            $sf_user_l_name = stripslashes($this->input->post('sf_user_l_name'));
            $sf_user_email = $this->input->post('sf_user_email');
            $sf_user_password = $this->input->post('sf_user_password');
            $sf_user_c_password = $this->input->post('sf_user_c_password');
            $user_full_name = ucfirst($sf_user_f_name).' '.ucfirst($sf_user_l_name);
                
            if (!filter_var($sf_user_email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success'=> false, 'message'=> 'Enter valid Email address']); die();
            }
            if($sf_user_password !== $sf_user_c_password){
                echo json_encode(['success'=> false, 'message'=> 'Password and confirm password do not same']); die();
            }            
            $check_email = $this->cm->select_row('users', ['LOWER(email)'=> strtolower($sf_user_email), 'login_type'=> 0], 'id');
            if(!empty($check_email)){
                echo json_encode(['success'=> false, 'message'=> 'The email address you have entered is already registered']); die();
            }
            
            $in_array = [
                'name' => $user_full_name,
                'email' => $sf_user_email,
                'login_password' => md5($sf_user_c_password),
                'login_type' => 0,
                'status' => 1,
                'account_verified' => 'No',
                'ip_address' => $this->get_client_ip(),
                'browser' => ($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:''
            ];
            $insert_user = $this->cm->insert('users', $in_array);
            if( !$insert_user){
                echo json_encode(['success'=> false, 'message'=> 'Something went wrong. Please try again']); die();
            }
            
            //Set User data in cookie
            $json = json_encode(['id'=> $insert_user, 'email'=> $sf_user_email]);
            setcookie(LOGIN_COOKIE_NAME, $json, time() + (30*24*60*60), "/");
            
            //Confirmation email sent to user
            $salt = "eUAKnpRQWJuaKXYY1NvwNq7bMaURg==";
            $active_link = base_url().'user/confirmation?token='.base64_encode($insert_user.'/'.$salt);
            $message = '<p>Hey <b>'.$sf_user_f_name.'</b></p>';
            $message .= "<p>Thanks for registering at Shorolafashion!</p>";
            $message .= "<p>You have to almost done your profile Please verify your account</p>";
            $message .= "<p>Please click the link below to activate your
                            account and get started.
                            We're excited for you!</p>";
            $message .= '<p><a href="'.$active_link.'">ACTIVATE MY ACCOUNT</a></p>';            
            $this->cm->send_email($sf_user_email, siteSettingsData()['site_sender_email'],'','', 'Welcome to Shorolafashion', $message,'','','');
            echo json_encode(['success'=> true, 'message'=> 'Successfully registered']); die();
        }
    }
    
    public function doLogin(){
        if($this->input->post()){
            
            $sf_user = addslashes($this->input->post('sf_user'));
            $sf_user_pass = addslashes($this->input->post('sf_user_pass'));
            if (!filter_var($sf_user, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('message', 'Enter valid Email address');
                redirect(base_url().'user/login?return_to='.urlencode($this->input->post('return_to', TRUE)));
            } else {
                $exist_user_data = $this->cm->select_row('users', [
                    'email'=> $sf_user,
                    'status'=> 1,
                    'login_type'=> 0], '');
                if(!empty($exist_user_data)){
                    if($exist_user_data['login_password'] === md5($sf_user_pass)){
                        //Set User data in cookie
                        $json = json_encode(['id'=> $exist_user_data['id'], 'email'=> $sf_user]);
                        setcookie(LOGIN_COOKIE_NAME, $json, time() + (30*24*60*60), "/");
                        if($this->input->post('return_to', TRUE)){
                            redirect($this->input->post('return_to', TRUE));
                        } else {
                            redirect(base_url().'profile/personal-info');
                        }
                    } else {
                        $this->session->set_flashdata('username', $sf_user);
                        $this->session->set_flashdata('message', 'Your password is incorrect!');
                        redirect(base_url().'user/login?return_to='.urlencode($this->input->post('return_to', TRUE)));
                    }
                } else {
                    $this->session->set_flashdata('message', 'Email or password is incorrect!');
                    redirect(base_url().'user/login?return_to='.urlencode($this->input->post('return_to', TRUE)));
                }
            }
        }
    }
    
    function get_client_ip()
    {
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