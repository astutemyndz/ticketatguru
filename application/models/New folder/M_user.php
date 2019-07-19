<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_model {

	public function login($email,$password) {
		
		$this->db->select('*');
        $this->db->from('pils_user');
        $this->db->where(array('email' => $email, 'password'=>$password, 'status' => 1));
        $query = $this->db->get()->result(); 	
		return $query;
		 	
	} 	
	
	public function email_check($email){		
		$this->db->select('*');
        $this->db->from('pils_user');
        $this->db->where(array('email' => $email));
        $query = $this->db->get()->result(); 
		return $query;	
	}	
	public function depart_detail($id){		
		$this->db->select('*');
        $this->db->from('pils_department');
        $this->db->where(array('id' => $id));
        $query = $this->db->get()->result(); 
		return $query;	
	}
	
	public function designation_detail($id){		
		$this->db->select('*');
        $this->db->from('pils_designation');
        $this->db->where(array('id' => $id));
        $query = $this->db->get()->result(); 
		return $query;	
	}	
	
	public function all_department(){		
		$this->db->select('*');
        $this->db->from('pils_department');   
		$this->db->order_by("depart_name", "asc"); 
        $query = $this->db->get()->result(); 
		return $query;	
	}
	
	public function all_designation(){		
		$this->db->select('*');
        $this->db->from('pils_designation');   
		$this->db->order_by("designation_name", "asc"); 
        $query = $this->db->get()->result(); 
		return $query;	
	}	
	
	public function all_user(){		
		$this->db->select('pu.id as id,pu.fname,pu.lname,pu.email,pu.dept_id,pu.designation,pu.status,pu.creation_date,pd.depart_name');
        $this->db->from('pils_user pu');
		$this->db->join('pils_department pd','pu.dept_id=pd.id','left');	
        $this->db->where(array('pu.status' => 1));
		//$this->db->limit(10, 20);
        $query = $this->db->get()->result(); 		
		return $query;	
		
	}
	public function user_detail($id){		
		$this->db->select('pu.id as id,pu.fname,pu.lname,pu.email,pu.password,pu.dept_id,pu.designation,pu.status,pu.creation_date,pd.depart_name');
        $this->db->from('pils_user pu');
		$this->db->join('pils_department pd','pu.dept_id=pd.id','left');	
        $this->db->where(array('pu.id' => $id));
        $query = $this->db->get()->result(); 
		return $query;	
	}	

}
