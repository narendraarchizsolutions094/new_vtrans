<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SubSource_model extends CI_Model {
    private $table = "tbl_subsource";
    public function insertRow($data = []) {
        return $this->db->insert($this->table, $data);
    }
    
    public function insertRowdeis($data = []) {
        return $this->db->insert('lead_description', $data);
    }
    
    public function updateRow($data = []) {
        return $this->db->where('subsource_id', $data['subsource_id'])
                        ->update($this->table, $data);
    }
    
    public function updateRowdes($data = []) {
        return $this->db->where('id', $data['id'])
                        ->update('lead_description', $data);
    }
    
    public function readRow($subsource_id = null) {
        return $this->db->select("*")
                        ->from($this->table)
                        ->where('subsource_id', $subsource_id)
                        ->get()
                        ->row();
    }
    
    public function readRowdes($description_id = null) {
        return $this->db->select("*")
                        ->from('lead_description')
                        ->where('id', $description_id)
                        ->get()
                        ->row();
    }

    public function subsourcelist() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("tbl_subsource.*,lead_source.lead_name")
                        ->from("tbl_subsource")
                        ->join('lead_source', 'lead_source.lsid = tbl_subsource.lead_source_id')
						->where('tbl_subsource.comp_id', $company)
                        ->get()
                        ->result();
    }

    public function get_subsource($id){

      $company=$this->session->userdata('companey_id');

      $this->db->select('subsource_id,subsource_name');
      $this->db->from('tbl_subsource');
      $this->db->where('lead_source_id',$id);
      $this->db->where('comp_id',$company);
      return $this->db->get()->result_array();
    }
	
	public function get_company_name($id){
      $this->db->select('branch_name');
      $this->db->from('branch');
      $this->db->where('branch_id',$id);
      return $this->db->get()->row();
    }
    
    public function descriptionlist() {
		$company=$this->session->userdata('companey_id');
        $userid =$this->session->userdata('user_id');
      
        return $this->db->select("lead_description.*,GROUP_CONCAT(lead_stage.lead_stage_name) as stage_list,tbl_product.product_name")
                        ->from("lead_description")
                        ->join('lead_stage', 'FIND_IN_SET(lead_stage.stg_id,lead_description.lead_stage_id)>0','left')
                        ->join('tbl_product','tbl_product.sb_id=lead_stage.process_id','left')
						->where('lead_description.comp_id', $company)
                        ->group_by('lead_description.id')
                        ->get()
                        ->result();

        //echo $this->db->last_query(); exit();
    }
    /*ruko batata hu ok sir ye query puri theekchl jati hai bs using hta do to */
    public function all_lead_source() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("lead_source")
						->where('comp_id', $company)
                        ->get()
                        ->result();
    }
    
    public function all_stage_des() {
        $company=$this->session->userdata('companey_id');
        return $this->db->select('*')->from('lead_stage')->where('comp_id',$company)->get()->result();
    }
    
    public function all_stage_list() {
        $company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("lead_stage")
						->where('comp_id', $company)
                        ->get()
                        ->result();
    }
    
    public function findRows($subsource_id) {
        return $this->db->select('*')
                        ->from($this->table)
                        ->where('subsource_id', $subsource_id)
                        ->get()
                        ->result();
    }
    
    public function deleteSubSource($paramId = null)
	{
		$this->db->where('subsource_id',$paramId)->delete('tbl_subsource');
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete_description($paramId = null)
	{
		$this->db->where('id',$paramId)->delete('lead_description');
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}
}
