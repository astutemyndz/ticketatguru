<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Name: Common Library
 * Author: Rakesh Maity
 */

class Common_lib {
    private $CI;
    private $row = array();
    private $query;
    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->database();
    }

    public function get($table, $id, $object = false) {
        $this->CI->db->select('*');
        $this->CI->db->from($table);
        $this->CI->db->where('id', $id);
        $this->query = $this->CI->db->get();
        if($object) {
            return $this->query->row();    
        }
        return $this->query->row_array();
    }
}