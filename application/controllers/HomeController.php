<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HomeController extends App_Controller {

    protected 	$option_arr 			= array();
    protected 	$optionArr 				= 'option_arr';
    protected 	$locale_arr 			= 'locale_arr';
    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        
      // $this->load->library('session');
    }
   
    public function index()	{

        
		// $data =  array();
		// $data['title'] = 'Ticket at Guru';
		// $table['name'] = 'tk_cbs_events';
		// $select = 'tk_cbs_events.*,tk_cbs_multi_lang.content,tk_cbs_shows.*';
        // $condition = array('tk_cbs_events.status' => 'T','tk_cbs_multi_lang.field' => 'title','tk_cbs_multi_lang.source' => 'data','tk_cbs_multi_lang.model' => 'pjEvent','tk_cbs_shows.event_id!=' => '');

        
        
        // $join[0] = array('table'=>'tk_cbs_multi_lang','field'=>'foreign_id','table_master'=>'tk_cbs_events','field_table_master'=>'id','type'=>'left');
        // $join[1] = array('table'=>'tk_cbs_shows','field'=>'event_id','table_master'=>'tk_cbs_events','field_table_master'=>'id','type'=>'left');
        // $group_by[0] = 'tk_cbs_events.id';
        // $order_by[0] = array('field'=>'tk_cbs_shows.date_time','type'=>'ASC');
        // $data['event_lists'] = $this->Common_model->find_data($table,'array','',$condition,$select,$join,$group_by,$order_by);


       
       
        
       
    }

	public function ajaxcity(){ 
		$country_id = $this->input->post('country_id');
		$this->db->select('*');
		$this->db->from('tk_cities');
		$this->db->where(array('countryID' => $country_id));
		$this->db->order_by("cityName", "asc"); 	
		$data['ajax_city'] = $this->db->get()->result();
		$this->load->view('frontend/pages/ajax_city',$data);
	}
	public function location(){
		if(!empty($this->input->post())){
			$country=$this->input->post('country_list');
			$city=$this->input->post('city');
		}
		
        $country_cookie= array(
           'name'   => 'set_country_id',
           'value'  => $country,
           'expire' => '3600',
       );
       $this->input->set_cookie($country_cookie);
       
        $city_cookie= array(
           'name'   => 'set_city_id',
           'value'  => $city,
           'expire' => '3600',
       );
       $this->input->set_cookie($city_cookie);
			/*$this->session->set_userdata('location_country',$country);
			$this->session->set_userdata('location_city',$city);	*/
		redirect($_SERVER['HTTP_REFERER']);
	}
    
}
