<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_model {

    public function __construct() {
        parent::__construct();
    }

    public function get_module($data) {
        $q = $this->db->get_where('pils_modules', $data);
        return $q->result_array();
    }

    public function get_parent($parent) {
        $q = '';
        while ($parent > 0) {
            $q = $this->db->get_where('pils_modules', array('id' => $parent))->result();
            $parent = $q[0]->parent_id;
        }
        return $q;
    }

    function insert_module($data) {
        $this->db->insert('pils_modules', $data);
        return $this->db->insert_id();
    }

    public function get_product($data) {
        $q = $this->db->get_where('pils_product', $data);
        return $q->result_array();
    }

    public function get_brand($data) {
        
        $this->db->select('*');
        $this->db->from('pils_brand');
        $this->db->where($data);
        $this->db->order_by("id", "desc");
        $query = $this->db->get(); 
        return $query->result_array();
    }

    public function get_searched_product($keyup) {

        $query = $this->db->query("select * from pils_product where status = 1 and name like '%$keyup%' or slug like '%$keyup%'");

        return $query->result_array();
    }

    public function get_searched_product_exact($keyup) {

        $query = $this->db->query("select id from pils_product where status = 1 and name = '$keyup'");

        return $query->result_array();
    }

    public function get_searched_brand($keyup) {

        $query = $this->db->query("select * from pils_brand where status = 1 and name like '%$keyup%' or slug like '%$keyup%'");

        return $query->result_array();
    }

    function insert_brand($data) {
        $this->db->insert('pils_brand', $data);
        return $this->db->insert_id();
    }

    public function get_distributors($data) {
        $this->db->select('*');
        // $this->db->select('pd.id as pd_id, pd.comp_name as pd_name');
        $this->db->from('pils_distributor_brand as pdb');
        $this->db->join('pils_distributor as pd', 'pdb.distributor_id=pd.id', 'left');
        $this->db->where($data);

        $q = $this->db->get();
        return $q->result_array();
    }

    function insert_purchase_request($data) {
        $this->db->insert('pils_purchase_request', $data);
        return $this->db->insert_id();
    }

    function insert_purchase_quotation_request($data) {
        $this->db->insert('pils_purchase_quotation_request', $data);
        return $this->db->insert_id();
    }

    public function get_product_price($data) {
        $q = $this->db->get_where('pils_product_price', $data);
        return $q->result_array();
    }

    public function get_distributor($data) {
        $q = $this->db->get_where('pils_distributor', $data);
        return $q->result_array();
    }

    public function get_quotation_request() {
        $this->db->select('ppr.id as ppr_id, ppqr.id as ppqr_id, ppr.product_name as ppr_product_name, ppqr.quotation_requested_quantity as ppqr_product_quantity, ppqr.brand_id as ppqr_brand_id, ppqr.distributor_id as ppqr_distributor_id, ppqr.quotation_creation_date as ppqr_quotation_creation_date, ppqr.feedback_status as ppqr_feedback_status, ppqr.purchase_request_status as ppqr_purchase_request_status');
        $this->db->from('pils_purchase_quotation_request as ppqr');
        $this->db->join('pils_purchase_request as ppr', 'ppqr.purchase_request_id=ppr.id', 'left');
        $q = $this->db->get();
        return $q->result_array();
    }

    public function get_quotation_request_sorted($id) {
        $this->db->select('ppr.id as ppr_id, ppqr.id as ppqr_id, ppr.product_name as ppr_product_name, ppqr.quotation_requested_quantity as ppqr_product_quantity, ppqr.brand_id as ppqr_brand_id, ppqr.distributor_id as ppqr_distributor_id, ppqr.quotation_creation_date as ppqr_quotation_creation_date, ppqr.feedback_status as ppqr_feedback_status, ppqr.purchase_request_status as ppqr_purchase_request_status, ppqr.quotation_created_by as ppqr_quotation_created_by');
        $this->db->from('pils_purchase_quotation_request as ppqr');
        $this->db->join('pils_purchase_request as ppr', 'ppqr.purchase_request_id=ppr.id', 'left');
        $this->db->where('ppqr.quotation_created_by', $id);
        $q = $this->db->get();
        return $q->result_array();
    }

    public function update_purchase_request($wdata, $data) {
        $this->db->where($wdata);
        $this->db->update('pils_purchase_request', $data);
        return $this->db->affected_rows();
    }

    public function update_purchase_quotation_request($wdata, $data) {
        $this->db->where($wdata);
        $this->db->update('pils_purchase_quotation_request', $data);
        return $this->db->affected_rows();
    }

    public function get_purchase_request() {
        $this->db->select('ppr.id as ppr_id,'
                . ' ppqr.id as ppqr_id,'
                . ' ppr.product_name as ppr_product_name,'
                . ' ppqr.quotation_requested_quantity as ppqr_product_quantity,'
                . ' ppqr.brand_id as ppqr_brand_id,'
                . ' ppqr.distributor_id as ppqr_distributor_id,'
                . ' ppqr.quotation_creation_date as ppqr_quotation_creation_date,'
                . ' ppqr.feedback_status as ppqr_feedback_status,'
                . ' ppqr.purchase_request_status as ppqr_purchase_request_status,'
                . ' ppqr.finalized_price as ppqr_finalized_price,'
                . ' ppqr.suggestion_status as ppqr_suggestion_status,'
                . ' ppqr.request_approve_status as ppqr_approval_status,'
                . ' ppqr.quotation_created_by as ppqr_quotation_created_by');
        $this->db->where('ppqr.purchase_request_status', 1);
        $this->db->from('pils_purchase_quotation_request as ppqr');
        $this->db->join('pils_purchase_request as ppr', 'ppqr.purchase_request_id=ppr.id', 'left');

        $q = $this->db->get();
        return $q->result_array();
    }

    public function get_purchase_request_executive_groupwise() {
        $this->db->select('ppr.id as ppr_id,'
                . ' ppqr.id as ppqr_id,'
                . ' ppr.product_name as ppr_product_name,'
                . ' ppqr.quotation_requested_quantity as ppqr_product_quantity,'
                . ' ppqr.brand_id as ppqr_brand_id,'
                . ' ppqr.distributor_id as ppqr_distributor_id,'
                . ' ppqr.quotation_creation_date as ppqr_quotation_creation_date,'
                . ' ppqr.feedback_status as ppqr_feedback_status,'
                . ' ppqr.purchase_request_status as ppqr_purchase_request_status,'
                . ' ppqr.finalized_price as ppqr_finalized_price,'
                . ' ppqr.suggestion_status as ppqr_suggestion_status,'
                . ' ppqr.request_approve_status as ppqr_approval_status,'
                . ' ppqr.quotation_created_by as ppqr_quotation_created_by');
        $this->db->group_by('ppqr_quotation_created_by');
        $this->db->where('ppqr.purchase_request_status', 1);
        $this->db->from('pils_purchase_quotation_request as ppqr');
        $this->db->join('pils_purchase_request as ppr', 'ppqr.purchase_request_id=ppr.id', 'left');

        $q = $this->db->get();
        return $q->result_array();
    }

    public function get_purchase_request_executive_wise($id) {
        $this->db->select('ppr.id as ppr_id,'
                . ' ppqr.id as ppqr_id,'
                . ' ppr.product_name as ppr_product_name,'
                . ' ppqr.quotation_requested_quantity as ppqr_product_quantity,'
                . ' ppqr.brand_id as ppqr_brand_id,'
                . ' ppqr.distributor_id as ppqr_distributor_id,'
                . ' ppqr.quotation_creation_date as ppqr_quotation_creation_date,'
                . ' ppqr.feedback_status as ppqr_feedback_status,'
                . ' ppqr.purchase_request_status as ppqr_purchase_request_status,'
                . ' ppqr.finalized_price as ppqr_finalized_price,'
                . ' ppqr.suggestion_status as ppqr_suggestion_status,'
                . ' ppqr.request_approve_status as ppqr_approval_status,'
                . ' ppqr.quotation_created_by as ppqr_quotation_created_by');
        $this->db->where('ppqr.purchase_request_status', 1);
        $this->db->where('ppqr.quotation_created_by', $id);
        $this->db->from('pils_purchase_quotation_request as ppqr');
        $this->db->join('pils_purchase_request as ppr', 'ppqr.purchase_request_id=ppr.id', 'left');

        $q = $this->db->get();
        return $q->result_array();
    }

    public function get_quotation($data) {
        $q = $this->db->get_where('pils_purchase_quotation_request', $data);
        return $q->result_array();
    }

    function insert_purchase_order($data) {
        $this->db->insert('pils_purchase_temporary', $data);
        return $this->db->insert_id();
    }

    function insert_purchase_order_final($data) {
        $this->db->insert('pils_purchase_order', $data);
        return $this->db->insert_id();
    }

    function insert_purchase_order_details($data) {
        $this->db->insert('pils_purchase_details', $data);
        return $this->db->insert_id();
    }

    public function update_purchase_order_final($wdata, $data) {
        $this->db->where($wdata);
        $this->db->update('pils_purchase_order', $data);
        return $this->db->affected_rows();
    }

    public function update_purchase_order($wdata, $data) {
        $this->db->where($wdata);
        $this->db->update('pils_purchase_temporary', $data);
        return $this->db->affected_rows();
    }

    public function get_purchase_order() {
        $q = $this->db->get('pils_purchase_temporary');
        return $q->result_array();
    }

    public function get_purchase_order_with_distributor($data) {
        $q = $this->db->get_where('pils_purchase_temporary', $data);
        return $q->result_array();
    }

    public function get_purchase_temp($data) {
        $q = $this->db->get_where('pils_purchase_temporary', $data);
        return $q->result_array();
    }

    public function get_user($data) {
        $q = $this->db->get_where('pils_user', $data);
        return $q->result_array();
    }

    public function get_purchase_order_distributorwise() {

        $this->db->where('status', 0);
        $this->db->group_by('distributor_id');
        $q = $this->db->get('pils_purchase_temporary');
        return $q->result_array();
    }

    public function get_purchase_order_list($data) {
        $q = $this->db->get_where('pils_purchase_order', $data);
        return $q->result_array();
    }

    public function get_purchase_order_details($data) {
        $q = $this->db->get_where('pils_purchase_details', $data);
        return $q->result_array();
    }

    public function update_brand($wdata, $data) {
        $this->db->where($wdata);
        $this->db->update('pils_brand', $data);
        return $this->db->affected_rows();
    }

    public function update_purchase_order_details($wdata, $data) {
        $this->db->where($wdata);
        $this->db->update('pils_purchase_details', $data);
        return $this->db->affected_rows();
    }

    public function get_product_power($data) {
        $q = $this->db->get_where('pils_product_price', $data);
        return $q->result_array();
    }

    public function get_power($data) {
        $q = $this->db->get_where('pils_power', $data);
        return $q->result_array();
    }

    public function get_product_type($data) {
        $q = $this->db->get_where('pils_product_type', $data);
        return $q->result_array();
    }

    public function get_product_container($data) {
        $q = $this->db->get_where('pils_product_price', $data);
        return $q->result_array();
    }

    public function get_container($data) {
        $q = $this->db->get_where('pils_container', $data);
        return $q->result_array();
    }

}
