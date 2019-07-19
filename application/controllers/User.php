<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        if(isset($_COOKIE[LOGIN_COOKIE_NAME]) && !empty($_COOKIE[LOGIN_COOKIE_NAME])){            
            $cookie = stripslashes($_COOKIE[LOGIN_COOKIE_NAME]);
            $savedCartArray = json_decode($cookie, true);
            $this->userId = $savedCartArray['id'];                                     
        } else {            
            redirect(base_url().'user/login?return_to='.urlencode(current_url()));
        }  
        
        $this->load->library('encrypt');//load this library.
    }
    
    /*
        User personal information
    */
    public function index(){
        
        $data = array();
        $data['title'] = "Personal information | shorolafashion.com";  
        $data['edit_user_data'] = $this->cm->select_row('users', ['id'=> $this->userId], 'id,name,email,phone,gender,account_verified');
        $this->load->view('frontend/profile/personal_info',$data);
    }
    /*
        Update User Information
    */
    public function update_user_information(){
        
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {            
            $u_f_name = $this->input->post('u_f_name');
            $u_l_name = $this->input->post('u_l_name');
            $u_email_address = $this->input->post('u_email_address');
            $u_old_email = $this->input->post('u_old_email');
            $u_phone_number = $this->input->post('u_phone_number');
            $u_old_phone = $this->input->post('u_old_phone');
            
            if(!$u_phone_number){
                echo json_encode(['success'=> false, 'message'=> "Please provide your mobile no."]); die();
            }
            if(strlen($u_phone_number) !== 10){
                echo json_encode(['success'=> false, 'message'=> "Please provide 10 disit mobile no."]); die();
            }
            //update personal information
            $this->cm->update('users', ['id'=> $this->userId], [
                'name' => $u_f_name.' '.$u_l_name,
                'phone' => $u_phone_number,
                'gender' => $this->input->post('u_gender')
            ]);
            //if user have change there email address
            if($u_email_address){
                if (!filter_var($u_email_address, FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(['success'=> false, 'message'=> "Enter valid Email address"]); die();
                }
                if($u_email_address != $u_old_email){
                    $email_status = $this->cm->select_row('users', ['id !='=> $this->userId, 'email' => $u_email_address], 'id');
                    if(!empty($email_status)){
                        echo json_encode(['success'=> false, 'message'=> "The email address you have entered is already registered"]); die();
                    } else {
                        $email_update = $this->cm->update('users', ['id'=> $this->userId], [
                            'email' => $u_email_address,
                            'account_verified' => 'No'
                        ]);
                        if(!$email_update){
                            echo json_encode(['success'=> false, 'message'=> "Faild to update email address"]); die();
                        }
                        
                        //Confirmation email sent to user
                        $salt = "eUAKnpRQWJuaKXYY1NvwNq7bMaURg==";
                        $active_link = base_url().'user/confirmation?token='.base64_encode($email_status['id'].'/'.$salt);
                        $message = '<p>Hey <b>'.$u_f_name.'</b></p>';
                        $message .= "<p>Please click the link below to verify your email and get started.
                                        We're excited for you!</p>";
                        $message .= '<p><a href="'.$active_link.'">ACTIVATE MY ACCOUNT</a></p>';            
                        $this->cm->send_email($u_email_address, siteSettingsData()['site_sender_email'],'','', 'Verify your email', $message,'','','');
                        echo json_encode(['success'=> true, 'message'=> "Successfully update, please verify your email"]); die();
                    }
                }
            }
            
            echo json_encode(['success'=> true, 'message'=> "Successfully update Personal Information"]); die();            
        }
    }
    /*
        User Wishlist
    */
    public function my_wishlist(){
        //redirect to activation page if user not verified there profile
        if(GetUserDetailsById($this->userId)['account_verified'] == 'No'){
            redirect(base_url().'profile/activation');
        }
        
        $data = array();
        $data['title'] = "My wishlist | shorolafashion.com";  
        
        $join[] = ['table' => 'products p', 'on' => 'w.product_id = p.id', 'type' => 'inner'];
        $join[] = ['table' => 'product_brands pb', 'on' => 'p.brand_id = pb.id', 'type' => 'left'];
        $join[] = ['table' => 'categories c', 'on' => 'p.cat_id = c.id', 'type' => 'inner'];
        $join[] = ['table' => 'sub_categories sc', 'on' => 'p.sub_cat_id = sc.id', 'type' => 'inner'];

        $data['product_wishlist'] = $this->cm->select('product_wishlist w', ['w.user_id'=> $this->userId], 'w.id,w.product_id,w.created_at,p.name,p.slug,p.primary_image,p.set_discount,p.discount_percentage,p.sell_price,pb.name brand_name,c.slug category,sc.slug sub_category', 'w.id', 'desc', $join);
        $this->load->view('frontend/profile/wishlist',$data);
    }
    /*
        Manage delivery address
    */
    public function manage_address(){
        //redirect to activation page if user not verified there profile
        if(GetUserDetailsById($this->userId)['account_verified'] == 'No'){
            redirect(base_url().'profile/activation');
        }
        
        $data = array();
        $data['title'] = "Manage address | shorolafashion.com"; 
        
        $join[] = ['table' => 'user_countries uc', 'on' => 'a.country_id = uc.id', 'type' => 'inner'];
        $join[] = ['table' => 'user_states us', 'on' => 'a.state_id = us.id', 'type' => 'left'];
        $join[] = ['table' => 'user_cities uci', 'on' => 'a.city_id = uci.id', 'type' => 'left'];
        $data['address_data'] = $this->cm->select('user_addresses a', ['a.user_id'=> $this->userId], 'a.*,uc.name country_name, uc.sortname, us.name state_name,uci.name city_name', 'a.id', 'desc', $join);
        $this->load->view('frontend/profile/manage_address',$data);
    }
    /*
        Add new address
    */
    public function add_new_address(){
        //redirect to activation page if user not verified there profile
        if(GetUserDetailsById($this->userId)['account_verified'] == 'No'){
            redirect(base_url().'profile/activation');
        }
        
        $data = array();
        $data['title'] = "Add new address | shorolafashion.com";  
        $data['mode'] = 'Add';
        $data['country_data'] = $this->cm->select('user_countries', ['status'=> 1], '', 'name', 'asc');
        $this->load->view('frontend/profile/add_address',$data);
    }
    /*
        Edit address
    */
    public function edit_address(){
        //redirect to activation page if user not verified there profile
        if(GetUserDetailsById($this->userId)['account_verified'] == 'No'){
            redirect(base_url().'profile/activation');
        }
        
        $data = array();
        $data['title'] = "Edit address | shorolafashion.com";  
        $data['mode'] = 'Edit';
        $token = $this->input->get('token', TRUE);
        if(!$token){
            redirect(base_url().'profile/manage-address');
        }
        $edit_id = explode('/',base64_decode($token))[0];
        $data['edit_id'] = $edit_id;
        
        $data['address_data'] = $this->cm->select_row('user_addresses', ['id'=> $edit_id], '', []);
        $data['country_data'] = $this->cm->select('user_countries', ['status'=> 1], '', 'name', 'asc');
        $data['state_data'] = $this->cm->select('user_states', ['status'=> 1, 'country_id'=> $data['address_data']['country_id']], '', 'name', 'asc');
        $data['city_data'] = $this->cm->select('user_cities', ['status'=> 1, 'state_id'=> $data['address_data']['state_id']], '', 'name', 'asc');
        $this->load->view('frontend/profile/add_address',$data);
    }
    /*
        Submit Delivery Address Form Data
    */
    public function receive_delivery_form_data(){
        
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $add_mode = $this->input->post('add_mode');
            $add_name = $this->input->post('add_name');
            $add_phone = $this->input->post('add_phone');
            $add_pincode = $this->input->post('add_pincode');
            $add_locality = $this->input->post('add_locality');
            $add_address = $this->input->post('add_address');
            $add_country = $this->input->post('add_country');
            $add_state = $this->input->post('add_state');
            $add_city = $this->input->post('add_city');
            $add_address_type = $this->input->post('add_address_type');
            
            if(!$add_phone){
                echo json_encode(['success'=> false, 'message'=> "Please provide your mobile no."]); die();
            }
            if(strlen($add_phone) !== 10){
                echo json_encode(['success'=> false, 'message'=> "Please provide 10 disit mobile no."]); die();
            }
            if(!$add_pincode){
                echo json_encode(['success'=> false, 'message'=> "Please provide your pincode"]); die();
            }
            if(!$add_country){
                echo json_encode(['success'=> false, 'message'=> "Please provide your country"]); die();
            }
            
            if($add_mode == 'Add' && $this->input->post('add_id') == ''){
                $in_address = [
                    'user_id' => $this->userId,
                    'name' => addslashes(ucwords($add_name)),
                    'phone' => $add_phone,
                    'pincode' => $add_pincode,
                    'address' => addslashes(ucfirst($add_address)),
                    'landmark' => addslashes(ucfirst($add_locality)),
                    'address_type' => $add_address_type,
                    'country_id' => $add_country,
                    'state_id' => $add_state,
                    'city_id' => $add_city
                ];
                $insert_status = $this->cm->insert('user_addresses', $in_address);
                if(!$insert_status){
                    echo json_encode(['success'=> false, 'message'=> "Faild to added address"]); die();
                }
                echo json_encode(['success'=> true, 'message'=> "Successfully added address"]); die();
            }
            
            if($add_mode == 'Edit' && $this->input->post('add_id') !== ''){
                $up_address = [
                    'name' => addslashes(ucwords($add_name)),
                    'phone' => $add_phone,
                    'pincode' => $add_pincode,
                    'address' => addslashes(ucfirst($add_address)),
                    'landmark' => addslashes(ucfirst($add_locality)),
                    'address_type' => $add_address_type,
                    'country_id' => $add_country,
                    'state_id' => $add_state,
                    'city_id' => $add_city
                ];
                $update_status = $this->cm->update('user_addresses', ['id'=> $this->input->post('add_id')], $up_address);
                if(!$update_status){
                    echo json_encode(['success'=> false, 'message'=> "Faild to update address"]); die();
                }
                echo json_encode(['success'=> true, 'message'=> "Successfully update address"]); die();
            }           
        }
    }
    /*
        Delete Delivery Address
    */
    public function delete_address(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {  
            $address_id = $this->input->post('address_id');
            if($address_id){
                $delete_status = $this->cm->delete('user_addresses', ['id'=> $address_id, 'user_id'=> $this->userId]);
                if($delete_status){
                    echo json_encode(['success'=> true, 'message'=> "Successfully delete address"]); die();
                }
            }
        }
    }
    /*
        My Orders
    */
    public function my_orders(){
        //redirect to activation page if user not verified there profile
        if(GetUserDetailsById($this->userId)['account_verified'] == 'No'){
            redirect(base_url().'profile/activation');
        }
        
        $data = array();
        $data['title'] = "My orders | shorolafashion.com";  
        
        $order_list = array();
        $in_orders = $this->cm->select('user_orders', ['user_id'=> $this->userId], '', 'id', 'desc');
        if($in_orders){
            //echo '<pre>';
            //print_r($in_orders);
            foreach($in_orders as $order){
                $in_order_details = $this->db->query('SELECT od.*,p.name,p.slug,pb.name brand,p.description,p.primary_image,c.slug category,sc.slug sub_category
                    FROM user_order_details od
                    join products p on od.product_id = p.id
                    join product_brands pb on p.brand_id = pb.id
                    join categories c on p.cat_id = c.id
                    left join sub_categories sc on p.sub_cat_id = sc.id
                    where od.order_id = '.$order['id'])->result_array();
                
                $order_dtls_list = array();
                if($in_order_details){
                    //print_r($in_order_details);
                    foreach($in_order_details as $ord_dtls){
                        
                        /*$ord_log = $this->cm->select_row('user_order_logs', ['order_id'=> $order['id'], 'order_details_id'=> $ord_dtls['id'], 'status'=> 3], '');*/
                        $order_dtls_list[] = [
                            'id' => $ord_dtls['id'],
                            'product_id' => $ord_dtls['product_id'],
                            'product' => $ord_dtls['name'],
                            'product_slug' => $ord_dtls['slug'],
                            'product_size' => $ord_dtls['product_size'],
                            'product_color' => $ord_dtls['product_color'],
                            'brand' => $ord_dtls['brand'],
                            'description' => $ord_dtls['description'],
                            'primary_image' => $ord_dtls['primary_image'],
                            'category' => $ord_dtls['category'],
                            'sub_category' => $ord_dtls['sub_category'],
                            'quantity' => $ord_dtls['quantity'],
                            'sell_price' => $ord_dtls['sell_price'],
                            'discount_percentage' => $ord_dtls['discount_percentage'],
                            'discount_price' => $ord_dtls['discount_price'],
                            'status' => $ord_dtls['status']
                        ];
                    }
                }
                //create new order list
                $order_list[] = [
                    'id' => $order['id'],
                    'order_no' => $order['order_no'],
                    'payment_mode' => $order['payment_mode'],
                    'total_amount' => $order['total_amount'],
                    'delivery_address' => $order['delivery_address'],
                    'payment_status' => $order['payment_status'],
                    'order_status' => $order['order_status'],
                    'created_at' => $order['created_at'],
                    'order_details' => $order_dtls_list
                ];
            }
        }
        //print_r($order_list);
        $data['my_orders'] = $order_list;
        $this->load->view('frontend/profile/orders',$data);
    }
    /*
        Cancel order item
    */
    public function cancel_order_item(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {  
            $ord_dtls_id = $this->input->post('ord_dtls_id');
            $order_no = $this->input->post('order_no');
            if($ord_dtls_id && $order_no){
                $id = base64_decode($ord_dtls_id);
                $order_no = base64_decode($order_no);
                //get and check order
                $check_order = $this->cm->select_row('user_order_details', ['id'=> $id], '');
                if($check_order){
                    //update status for order cancel
                    $update_cancel = $this->cm->update('user_order_details', ['id'=> $id], ['status'=> 4]);
                    if(!$update_cancel){
                        echo json_encode(['success'=> false, 'message'=> "Failed to cancel your order, please try again"]); die();
                    }
                    //create order log
                    $this->cm->insert('user_order_logs', [
                        'order_id'=> $check_order['order_id'],
                        'order_details_id'=> $id,
                        'status'=> 4,
                        'remarks'=> '',
                    ]);
                    //create stock log, cancel item qty back to stock 
                    $product = $this->cm->select_row('products', ['id'=> $check_order['product_id']], '');
                    $this->cm->insert('product_stock_log', [
                        'product_id'=> $check_order['product_id'],
                        'previous_stock'=> $product['purchase_quantity'],
                        'new_stock'=> $check_order['quantity'],
                        'current_stock'=> $product['purchase_quantity']+$check_order['quantity'],
                        'status'=> 'IN',
                        'remarks'=> 'Order Cancel with Order ID '.$order_no,
                    ]);
                    //cancel mail sent to admin
                    $a_mail_subject = 'Item Cancelled '.$product['name'].' with Order ID '.$order_no;
                    $a_message = '<p>User has been cancelled this item</p>';
                    $a_message .= "<p></p><p>Order ID: ".$order_no."</p>";
                    $a_message .= "<p>Item: ".$product['name']."</p>";       
                    $a_message .= "<p>Size: ".$check_order['product_size']."</p>";           
                    $a_message .= "<p>Color: ".$check_order['product_color']."</p>"; 
                    $a_message .= "<p>Qty: ".$check_order['quantity']."</p>"; 
                    $this->cm->send_email(siteSettingsData()['site_receiver_email'], siteSettingsData()['site_sender_email'],'','', $a_mail_subject, $a_message,'','','');
                    //cancel mail sent to customer
                    $c_mail_subject = 'Item Cancelled from Order '.$order_no;
                    $c_message = '<p>Item Cancelled Base on your request, 1 item ('.$product['name'].') from your order '.$order_no.' has been cancelled</p>';
                    $c_message .= "<p>Item: ".$product['name']."</p>";       
                    $c_message .= "<p>Size: ".$check_order['product_size']."</p>";           
                    $c_message .= "<p>Color: ".$check_order['product_color']."</p>";
                    $this->cm->send_email(GetUserDetailsById(GetUserId())['email'], siteSettingsData()['site_sender_email'],'','', $c_mail_subject, $c_message,'','','');
                    
                    echo json_encode(['success'=> true, 'message'=> "Successfully cancel your order"]); die();
                } else {
                    echo json_encode(['success'=> false, 'message'=> "Failed to cancel your order, please try again"]); die();
                }
            }
        }
    }
    /*
        Change Password
    */
    public function change_password(){
        //redirect to activation page if user not verified there profile
        if(GetUserDetailsById($this->userId)['account_verified'] == 'No'){
            redirect(base_url().'profile/activation');
        }
        
        $data = array();
        $data['title'] = "Change Password | shorolafashion.com";
        $this->load->view('frontend/profile/change_password',$data);
    }
    public function receive_change_password_form_data(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else { 
            $current_pass = $this->input->post('sf_current_pass');
            $new_pass = $this->input->post('sf_new_pass');
            $confirm_pass = $this->input->post('sf_con_pass');
            if(!$current_pass){
                echo json_encode(['success'=> false, 'message'=> "Please type your current password"]); die();
            }
            if(!$new_pass){
                echo json_encode(['success'=> false, 'message'=> "Please type your new password"]); die();
            }
            if(!$confirm_pass){
                echo json_encode(['success'=> false, 'message'=> "Please retype your new password"]); die();
            }
            if(strlen($new_pass) < 6){
                echo json_encode(['success'=> false, 'message'=> "New password at least 6 characters in length"]); die();
            }
            if($new_pass !== $confirm_pass){
                echo json_encode(['success'=> false, 'message'=> "New password and confirm password do not same"]); die();
            }
            $current_user = $this->cm->select_row('users', ['id'=> $this->userId], 'id,login_password');
            if(md5($current_pass) === $current_user['login_password']){
                if(md5($new_pass) == $current_user['login_password']){
                    echo json_encode(['success'=> false, 'message'=> "Please enter different new password for security reasons"]); die();
                } else {
                    $update_password = $this->cm->update('users', ['id'=> $this->userId], ['login_password'=> md5($new_pass)]);
                    if(!$update_password){
                        echo json_encode(['success'=> false, 'message'=> "Faild to change your password"]); die();
                    }
                    echo json_encode(['success'=> true, 'message'=> "Successfully change your password"]); die();
                }
            } else {
                echo json_encode(['success'=> false, 'message'=> "Your current password is mismatch"]); die();
            }
        }
    }
    /*
        Get State List by Country Id
    */
    public function getStateByCountryId(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {            
            $country_id = $this->input->post('country_id');
            if($country_id){
                $state_option = '<option value="">Select State</option>';
                $state_data = $this->cm->select('user_states', ['status'=> 1, 'country_id'=> $country_id], '', 'name', 'asc');
                if($state_data){
                    foreach($state_data as $data){
                        $state_option .= '<option value="'.$data['id'].'">'.$data['name'].'</option>';
                    }
                }
                echo json_encode(['success'=> true, 'message'=> '', 'html'=> $state_option]);
            }
        }
    }
    /*
        Get City List by State Id
    */
    public function getCityByStateId(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {            
            $state_id = $this->input->post('state_id');
            if($state_id){
                $city_option = '<option value="">Select State</option>';
                $city_data = $this->cm->select('user_cities', ['status'=> 1, 'state_id'=> $state_id], '', 'name', 'asc');
                if($city_data){
                    foreach($city_data as $data){
                        $city_option .= '<option value="'.$data['id'].'">'.$data['name'].'</option>';
                    }
                }
                echo json_encode(['success'=> true, 'message'=> '', 'html'=> $city_option]);
            }
        }
    }
    /*
        Verify user email after signup
    */
    public function profile_confirmation(){
        
        $data = array();
        $token = $this->input->get('token', TRUE);
        if($token){
            //$user_id = $this->encrypt->decode($token);
            $user_id = explode('/',base64_decode($token))[0];
            $check_user = $this->cm->select_row('users', ['id'=> $user_id, 'login_type'=> 0], 'id,account_verified');
            if($check_user){
                if($check_user['account_verified'] == 'No'){
                    $this->cm->update('users', ['id'=> $user_id], ['account_verified'=> 'Yes']);
                    $data['success_status'] = true;
                    $data['message_title'] = "Successfully verified!";                    
                    $data['message'] = "Congratulations! You have successfully verified the email address.
                    If you want to change the email address, you may go to 'My Profile' and change it.";
                    $data['redirect_to'] = base_url().'profile/personal-info';
                    $this->load->view('frontend/error_and_success', $data);
                } else {
                    $data['success_status'] = false;
                    $data['message_title'] = "Failed to verified!";                    
                    $data['message'] = "Invalid confirmation token";
                    $this->load->view('frontend/error_and_success', $data);
                }
            } else {
                $data['success_status'] = false;
                $data['message_title'] = "Failed to verified!";                    
                $data['message'] = "Invalid confirmation token";
                $this->load->view('frontend/error_and_success', $data);
            }
        }
    }
    /*
        Profile logout
    */
    public function logout(){
        unset($_COOKIE[LOGIN_COOKIE_NAME]);
        setcookie(LOGIN_COOKIE_NAME, "", time() - 3600, "/");                
        redirect(base_url().'home');
    }
    /*
        REsend profile activation link
    */    
    public function resend_confirmation(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            //Confirmation email sent to user
            $salt = "eUAKnpRQWJuaKXYY1NvwNq7bMaURg==";
            $active_link = base_url().'user/confirmation?token='.base64_encode($this->userId.'/'.$salt);
            $message = '<p>Hey <b>'.GetUserDetailsById($this->userId)['name'].'</b></p>';
            $message .= "<p>You have to almost done your profile Please verify your account</p>";
            $message .= "<p>Please click the link below to activate your
                            account and get started.
                            We're excited for you!</p>";
            $message .= '<p><a href="'.$active_link.'">ACTIVATE MY ACCOUNT</a></p>';            
            $this->cm->send_email(GetUserDetailsById($this->userId)['email'], siteSettingsData()['site_sender_email'],'','', 'Request resend confirmation', $message,'','','');
            echo json_encode(['success'=> true, 'message'=> 'Successfully resend activation link.']); die();
        }
    }
    /*
        Change & REsend profile activation link
    */
    public function change_email_and_resend_confirmation(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            
            $change_email = $this->input->post('u_email_address');
            if(!$change_email){
                echo json_encode(['success'=> false, 'message'=> 'Please provide your email']); die();
            }
            if (!filter_var($change_email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success'=> false, 'message'=> 'Invalid email format']); die();
            }
            if(GetUserDetailsById($this->userId)['email'] !== $change_email){
                $update_status = $this->cm->update('users', ['id'=> $this->userId], ['email'=> $change_email, 'account_verified'=> 'No']);
                if(!$update_status){
                    echo json_encode(['success'=> false, 'message'=> 'Faild to change your email address']); die();
                }
                
                //Confirmation email sent to user
                $salt = "eUAKnpRQWJuaKXYY1NvwNq7bMaURg==";
                $active_link = base_url().'user/confirmation?token='.base64_encode($this->userId.'/'.$salt);
                $message = '<p>Hey <b>'.GetUserDetailsById($this->userId)['name'].'</b></p>';
                $message .= "<p>You have to almost done your profile Please verify your account</p>";
                $message .= "<p>Please click the link below to activate your
                                account and get started.
                                We're excited for you!</p>";
                $message .= '<p><a href="'.$active_link.'">ACTIVATE MY ACCOUNT</a></p>';            
                $this->cm->send_email(GetUserDetailsById($this->userId)['email'], siteSettingsData()['site_sender_email'],'','', 'Request resend confirmation', $message,'','','');
                echo json_encode(['success'=> true, 'message'=> 'Successfully change email and resend activation link.']); die();
            } else {
                echo json_encode(['success'=> false, 'message'=> 'Faild to change your email address']); die();
            } 
        }
    }
    /*
        Common Profile not Verified message page
    */
    public function common_profile_verified(){
        
        if(GetUserDetailsById($this->userId)['account_verified'] == 'Yes'){
            redirect(base_url().'profile/personal-info');
        }
        
        $data = array();        
        $this->load->view('frontend/profile/profile_verifyed_common', $data);
    }
    
}
