<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**

	 * Check localhost or not

	 */ 
	
		if ( ! function_exists('get_site_setting_details')){

		function get_site_setting_details(){

			$ci = & get_instance();

			$ci->load->database();

			$sql = "SELECT * FROM  restau_site_settings WHERE  status=1";	      	
		

			$query = $ci->db->query($sql)->result();

			return $query;

		}

	}

	
	if ( ! function_exists('get_country')){

		function get_country(){
			$ci = & get_instance();
			$ci->load->database();
			//$ci->load->library('session');
			$country='';
			/*if(isset($_SESSION['location_country'])){
			$country=$_SESSION['location_country'];
			}else{
				$country='230';
			}*/
			$sql = "SELECT * FROM  tk_countries  order by countryName";	 
			$query = $ci->db->query($sql)->result_array();
			return $query;
			
			
		}
	}
	if ( ! function_exists('get_state')){

		function get_state(){
			$ci = & get_instance();
			$ci->load->database();
			$ci->load->library('session');
			//$country='230';
			$country='';
			$state='';
			if(isset($_SESSION['location_state'])){
			$country=$_SESSION['location_country'];
			$state=$_SESSION['location_state'];
			
			}else{
				$country='230';
				$state='3866';
			}
			$sql = "SELECT * FROM  states where country_id='".$country."' order by name";	      	
			
			$query = $ci->db->query($sql)->result_array();
			return $query;
			
			
		}
	}
	
	if ( ! function_exists('get_city')){

		function get_city(){
			$ci = & get_instance();
			$ci->load->database();
			
			
			$city='';
			
			if(get_cookie('set_country_id')!=''){
			 	$set_country_id = get_cookie('set_country_id');
			 }else{
			 	$set_country_id = '';
			 }
			$sql = "SELECT * FROM  tk_cities where countryID='".$set_country_id."' order by cityName";	      	
			
			$query = $ci->db->query($sql)->result_array();
			return $query;
		}
	}
	
	
	

		if ( ! function_exists('quickLocation')){



		function quickLocation(){

			$ci = & get_instance();

			$ci->load->database();

			$sql = "SELECT * FROM  cities WHERE  status=1";	      	

			

			$query = $ci->db->query($sql)->result();

			return $query;

			

			

		}

	}


	

	if ( ! function_exists('get_site_setting')){



		function get_site_setting($name){

			$ci = & get_instance();

			$ci->load->database();

			$sql = "SELECT * FROM `restau_site_settings` WHERE name='".$name."' and status=1";	      	

			

			$query = $ci->db->query($sql)->result();

			$value = '';

			if(isset($query[0]))

			{

				$value=$query[0]->value;

			}

			return $value;

		}

	}
	function __($key, $return=false, $escape=false)
	{
		return pjUtil::getField($key, $return, $escape);
	}

	

	

	

	

	

	

	

	

	

	
	

	



	



		

		

		