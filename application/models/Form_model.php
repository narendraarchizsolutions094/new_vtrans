<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Form_model extends CI_Model {    

    public function get_tabs_list($comp_id,$process_id=0,$form_for=-1) {
        $this->db->select('forms.*');
    	$where = " FIND_IN_SET($comp_id,forms.comp_id) > 0";
    	if ($process_id) {
    		$this->db->join('form_process','form_process.form_id=forms.id','left');
    		$where .= " AND FIND_IN_SET($process_id,form_process.process_id) > 0 AND form_process.comp_id=$comp_id";
    	}
        
       if($form_for!=-1)
        {
           $where .= " AND forms.form_for=$form_for";
        }
       // return $where;

        // else{
        //     $where .= " AND forms.form_for IS NULL";            
        // }
    	$this->db->where($where);
    	 return $this->db->get('forms')->result_array();
        // echo $this->db->last_query(); exit();
           
	}
	public function get_input_types(){
		return $this->db->get('input_types')->result_array();
	}
    public function get_all_tabs(){        
        return $this->db->get('forms')->result_array();        
    }
    public function get_field_by_process($process_id,$page_id=0,$tid=0){
        $compid=$this->session->companey_id;
        if(is_array($process_id)){
            $id = implode(",", $process_id);
        }
        else{
            $id = $process_id;
        }
        $where = " FIND_IN_SET('".$id."',process_id) AND company_id = {$compid} AND status=1";
        if ($tid) {
            $where .= " AND form_id=$tid"; 
        }
        $where .= " AND page_id = {$page_id}";
        $this->db->select("*");
        $this->db->from('tbl_input');
        $this->db->where($where);
        $this->db->order_by('tbl_input.form_id','asc');
        $this->db->order_by('tbl_input.fld_order','asc');
        return $this->db->get()->result_array();
    }
}