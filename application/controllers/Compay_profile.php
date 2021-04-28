<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Compay_profile extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
public function company_upload_data(){
	
        $this->db->select('enquiry_id,company');
		$this->db->from('enquiry');	
        $this->db->where('company!=','');		
        $cname = $this->db->get()->result();
		foreach($cname as $key=>$value){
			if (is_numeric($value->company)) {
                
			} else {
				$this->db->select('id');
		        $this->db->from('tbl_company');	
                $this->db->where("(company_name LIKE '%".$value->company."%')", NULL, FALSE);		
                $cid = $this->db->get()->row();
				
				if(empty($cid->id)){				
				$key = array( 
                                "company_name"  => $value->company,
                                "process_id"   => '141',
                                "comp_id"  => $this->session->companey_id
                                );
								
                $this->db->insert('tbl_company',$key);
				$last_id = $this->db->insert_id();
				}else{
				$last_id = $cid->id;
				}
				
                $this->db->set('company',$last_id);
				$this->db->where('enquiry_id',$value->enquiry_id);
                $this->db->update('enquiry');
				 
			}
		}
       
    }
}