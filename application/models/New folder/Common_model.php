<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_model {

    public function get_all($table, $condition = NULL, $limit = NULL, $offset = NULL){
        $this->db->select('*');
        $this->db->from($table);
        if($condition != NULL){
            $this->db->where($condition);
        }
        if($limit != NULL && $offset != NULL){
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }
    public function get_specific($table, $condition = NULL){
        $this->db->select('*');
        $this->db->from($table);
        if($condition != NULL){
            $this->db->where($condition);
        }
        return $this->db->get()->result();
    }
    public function get_chat($rec_id, $send_id){
        return $this->db->query("select * from chat where (sender_id = '".$send_id."' AND receiver_id = '".$rec_id."') OR (sender_id = '".$rec_id."' AND receiver_id = '".$send_id."') order by id ASC")->result();
    }
	/* 
        Using for get single row from table
        **passing the four parameter's in this function ($from, $whare, $select, $join)
            1.first parameter is table name, whare you want to fatch
            2.second is whare clause, this is the array like array('id' => 1) 
            3.third is select, what you want select field like 'id,name,email'
            4.last is join, The tables you want to join 
                like array('table' => 'table1', 'on' => 'tbl1.id = tbl2.tbl1_id', 'type' => 'inner') 
    */
    public function select_row($from, $where=array(), $select='', $join=array()){
        if($select){
            $this->db->select($select);
        }else{
            $this->db->select('*');
        }
        $this->db->from($from);
        if(!empty($join)){
            foreach($join as $qry){
                $this->db->join($qry['table'], $qry['on'], $qry['type']);
            }
        } 
        if(!empty($where)){
            $this->db->where($where);
        }  
        return $this->db->get()->row_array();
    }
    /* 
        Using for get multiple row 
        **passing the 9th parameter's in this function ($from, $whare, $select, $order_by, $mode, $join, $limit, $offset, $group_by) ,
            1.1st parameter is table name, whare you want to fatch ,
            2.2nd is whare clause, this is the array like array('id' => 1,..) ,
            3.3rd is select, what you want select field like 'id,name,email,...' ,
            4,5.4th is order by and 5th is mode like 'name, email,..','asc' (order by name,email asc) ,     6.6th is join, The tables you want to join 
                like array('table' => 'table1', 'on' => 'tbl1.id = tbl2.tbl1_id', 'type' => 'inner'),.. ,
            7,8.7th is limit and 8th is offset like '10','0' (limit 10 offset 0) ,
            9.9th is group by The fields you want to group on like ('name,email,...') .
    */
    public function select($from, $where=array(), $select='', $order_by='', $mode='', $join=array(), $limit='', $offset=0, $group_by = ''){
        if($select){
            $this->db->select($select);
        }else{
            $this->db->select('*');
        }
        $this->db->from($from);
        if(!empty($join)){
            foreach($join as $qry){
                $this->db->join($qry['table'], $qry['on'], $qry['type']);
            }
        }
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }      
        if($order_by && $mode){
            $this->db->order_by($order_by, $mode);
        } else {
            $this->db->order_by($order_by);
        }
        if($limit){
            $this->db->limit($limit, $offset);
        }
        //echo $this->db->last_query(); exit;
        return $this->db->get()->result_array();
    }
    /* 
        Insert
        ** two parameter table name and insert data
            table: 'table_name',
            insert data: array('name' => 'santanu', 'email' => 'santanuadak0@gmail.com')
    */
    public function insert($table, $data = array()){
        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /* 
        Update 
        ** three parameter table name, whare clause and update data
            table name: 'table',
            where: array('id' => 1),
            update data: array('name' => 'santanu')
    */
    public function update($table, $where=array(), $data=array()){
        $this->db->where($where);  
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }
    /* 
        Delete 
        ** two parameter table name and where clause
            table: 'table_name',
            where: array('id' => 2, 'id' => 3)
    */
    public function delete($table, $where=array()){
        $this->db->where($where);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }
    
    public function change_status($table, $where){
        $this->db->select('status');        
        $this->db->from($table);
        $this->db->where($where);                
        $curnt_status = $this->db->get()->row_array();
        $status = $curnt_status['status']?0:1;
        $this->db->where($where);  
        $this->db->update($table, array('status' => $status));
        if($this->db->affected_rows()){
            return $status;
        }
    }
    /**
        * -----------------------------------------
        * @Description : send email to user from admin
        * ----------------------------------------
        * @param       : to(string/to email)
        * @param       : subject(string/email subject)
        * @param       : param(array/data for email template)
        * @param       : template(string/email template)
        * @return      : affected row(int)
        * 
    */
    public function send_email($to,$from,$cc,$reply_to,$subject = '',$message,$attach='',$param = array(),$template){
        $config['protocol']        = 'sendmail';
        $config['mailpath']        = '/usr/sbin/sendmail';
        $config['charset']         = 'utf-8';
        $config['wordwrap']        = TRUE;
        $config['mailtype']        = 'html';
        $this->email->initialize($config);
        $this->email->from($from, 'Adult Lounge');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->reply_to($reply_to);
        $this->email->subject($subject);
        if(!empty($message)){
            $this->email->message($message);
        }else{
            $email_body = $this->load->view('email_templates/'.$template, $param ,TRUE);
            $this->email->message($email_body);
        }
        if(!empty($attach)){
            $this->email->attach('fontend/' . $attach);	
        }
        $status     = $this->email->send();
        //echo $this->email->print_debugger();
        return $status;
    }
}
