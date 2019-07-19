<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Common_model extends CI_Model

{

	var $data;	

    function  __construct(){

		parent::__construct();
		$this->load->database();

    }

################################ FETCH DATA ########################################   

	function find_data($table,$return_type='array',$list=NULL,$conditions='',$select='*',$join='',$group_by='',$order_by='',$limit=0,$offset=0,$or_where='',$like='',$or_like='',$array_list_key=NULL)

	{

		

		$result = array();		

		

		$this->db->select($select,false);		

		

		if(!empty($table['alias_name']))

		{

			$table_name = $table['name'].' as '.$table['alias_name'];

		}

		else

		{

			$table_name = $table['name'];

		}

		$this->db->from($table_name);

		

		if(is_array($join))

		{

			for($j=0;$j<count($join);$j++)

			{

				/*if($join[$j]['table_alias']!= '')

				{

					$table_join = $join[$j]['table'].' as '.$join[$j]['table_alias'];

					$table_join_name = $join[$j]['table_alias'];

				}

				else

				{*/

					$table_join = $join[$j]['table'];

					$table_join_name = $join[$j]['table'];

				//}

				

				if(!empty($join[$j]['table_master_alias']))

				{

					$table_master_join = $join[$j]['table_master_alias'];

				}

				else

				{

					$table_master_join = $join[$j]['table_master'];

				}

				if(!empty($join[$j]['and'])){

					$and = $join[$j]['and'];

				}

				else{

					$and = '';

				}

				$this->db->join($table_join,$table_join_name.'.'.$join[$j]['field'].'='.$table_master_join.'.'.$join[$j]['field_table_master'].$and ,$join[$j]['type']);

			}

		}

		

		if($conditions != '')$this->db->where($conditions);	

		if($or_where != '')$this->db->or_where($or_where);

		

		if($like != '')$this->db->like($like);	

		

		if($or_like!='')$this->db->or_like($or_like);	

		

		if(is_array($group_by))

		{

			for($g=0;$g<count($group_by);$g++)

			{

				$this->db->group_by($group_by[$g]);

			}

		}

		

		if(is_array($order_by))

		{

			for($o=0;$o<count($order_by);$o++)

			{

				$this->db->order_by($order_by[$o]['field'],$order_by[$o]['type']);

			}

		}

		

		if($limit != 0)$this->db->limit($limit,$offset);		

		

		$query = $this->db->get();

		

		switch ($return_type) 

		{

			case 'array':

			case '':

				if($query->num_rows() > 0){$result = $query->result();}

				break;

				

			case 'result-array':

				if($query->num_rows() > 0){$result = $query->result_array();}

				break;

					

			case 'row':

				if($query->num_rows() > 0){$result = $query->row();}

				break;

			

			case 'row-array':

				if($query->num_rows() > 0){$result = $query->row_array();}

				break;

				

			case 'list':

				//if($list['top_value'])$list_arr[''] == $list['top_value'];

				if($query->num_rows() > 0)

				{

					foreach ($query->result() as $row)

					{

						$list_arr[$row->$list['key']] = $row->$list['value'];

					}

				}

				$result = $list_arr;

				break;

				

			case 'count':

				$result = $query->num_rows();

				break;

				

			case 'query':

				$result = $query;

				break;

			

			case 'array-list':

				$list = array();

				if($query->num_rows() > 0){

					foreach ($query->result() as $row)

					{

						$list[] = $row->$array_list_key;

					}

				}

				$result = $list;

				break;

		}

		return $result;

    }	

############################## INSERT/UPDATE DATA ##################################	

	function save_data($table,$postdata = array(),$condition=NULL)

	{

		//echo "<pre>";print_r($postdata);exit;

		if(empty($condition))

		{

			$this->db->insert($table['name'],$postdata); 

			return $this->db->insert_id(); 

		}

		else

		{

			$this->db->where($condition);

			$this->db->update($table['name'],$postdata);

			return $this->db->affected_rows();

		}



		//echo $this->db->last_query();        

	}	

	

	

	function insert_data($table,$postdata = array(),$id=0,$conditions='',$batch=0)

	{

		if($id == 0)

		{			

				if($batch==1){ $this->db->insert_batch($table,$postdata);}else{ $this->db->insert($table,$postdata); }

		}

		else

		{

			if($conditions)

			{

				$this->db->where($conditions);

			}

			else

			{

				$this->db->where('id', $id);

			}

			$this->db->update($table,$postdata);

		}

        return $this->db->affected_rows();

	}

	

############################## INSERT BATCH DATA ##################################

//used for inserting a set of data in array format

	function save_batch_data($table,$postdata = array())

	{

		$this->db->insert_batch($table['name'],$postdata);

		if($this->db->affected_rows() > 0) 

		{

			return true;

		}

		else

		{

			return false;

		}

	}	

################################# DELETE DATA ######################################	

	function delete_data($table,$id,$conditions='')

	{

		if($conditions)

		{

			$this->db->where($conditions);

		}

		else

		{

	 	$this->db->where('id',$id);

		}

		$this->db->delete($table['name']);
//echo $this->db->last_query();   exit;
		if($this->db->affected_rows()>0)

		{

			return true;

		}

		else

		{

			return false;

		}

    }



//==========::::: fetch table all values by any condition:::============

	function get_values_all_conditions($table_name=null,$select_arr=null,$condotions=null,$order_by_arr=null,$group_by=null,$limit_arr=null){

		$select_field='*';

		if($select_arr!=null && $select_arr!="" && is_array($select_arr)){

		$select_field=implode(',',$select_arr);

		}

		$this->db->select($select_field)->from($table_name);  // select data & table

		if($condotions!=null && $condotions!=""){

		$this->db->where($condotions);	 // conditions

		}

		if($group_by!=null && $group_by!="" ){

		$this->db->group_by($group_by);	// Group by

		}

		if($order_by_arr!=null && $order_by_arr!="" && is_array($order_by_arr)){

		$this->db->order_by($order_by_arr['order'],$order_by_arr['by']);	 // order select

		}

		if($limit_arr!=null && $limit_arr!="" && is_array($limit_arr)){

		$this->db->limit($limit_arr['limit'],$limit_arr['offset']);	 // limit

		}

		$qry=$this->db->get();

		$result=$qry->result_array();

		

		return $result;

	}



	//========::::: Delete table value :::========

	function data_delete($table=null,$condition_arr=null)

	{

		$return=false;

		if(!empty($condition_arr) && $condition_arr!=null && $condition_arr!="" )

		{

			if($this->db->delete($table,$condition_arr))

			{

				$return=true;	

			}

		}

		return $return;

	}

	//===========::: Update data of table :::==============

	function update_table_val($table=null,$conditions=null,$values=null){

		$this->db->where($conditions);

		$this->db->update($table,$values);

		$return=$this->db->affected_rows();

		return $return;

	}

	//=========::: insert data in table :::==========

	function all_inset_val($table=null,$values=null){

		$this->db->insert($table,$values);

		$return=$this->db->insert_id();

		return $return;

	}

	//========= :::: get counter ::::========

	function get_count_val($table_name=null,$count_attribute=null,$conditions=null)

	{

		$select='*';

		if(trim($count_attribute)!=null && trim($count_attribute)!=""){

			$select=$count_attribute;

		}

		$this->db->select($select);

		$this->db->from($table_name);

		if($conditions!=null){

			$this->db->where($conditions);

		}

		$qry=$this->db->get();

		//echo $this->db->last_query();		

		$result=$qry->num_rows();

		

		return $result;

	}

	//======== ::: Get table value with custm Query :::======

	function get_custm_qry_result($qry=null){

		$result=array();

		if(trim($qry)!="" && trim($qry)!=null){

			$q=$this->db->query($qry);

			$result=$q->result_array();

		}

		return $result;

	}

	//################### get Info ####################//

	public function get_info($select,$table,$condition)

	{

		$this->db->select($select);

		$this->db->from($table);

		$this->db->where($condition);

		return $this->db->get()->row_array();	

	}





   ############################## INSERT/UPDATE DATA ##################################	

	function save_new_data($table,$postdata = array(),$condition=NULL)

	{ 	

		if(empty($condition))

		{				

			//echo "within if";die;

		  $insert = $this->db->insert($table['name'],$postdata);	

			//echo $this->db->last_query(); die;

			return true;

		}

		else

		{

			$this->db->where($condition);

			$this->db->update($table['name'],$postdata);

			//echo $this->db->last_query(); die;

			return $this->db->affected_rows();

		}        

	}



    



}
