<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {
	public function __construct() {}

	public function getBanner()	{

	  $this->db->where('status', 1);
	  $query = $this->db->get('banners');
	  if($query->num_rows() > 0){
	  	return $query->result();
	  }
	  else {
	  	return false;
	  }
        
	}

	public function getCategories()
	{
	  $this->db->where('on_deleted', 0);
	  $this->db->where('popular', 1);
	  $query = $this->db->get('job_categories');
	  if($query->num_rows() > 0){
	  	return $query->result();
	  }
	  else {
	  	return false;
	  }
	}

}





