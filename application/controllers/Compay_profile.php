<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Compay_profile extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
public function company_upload_data($filter=array()){
	
        $this->db->select('enquiry_id,company');
		$this->db->from('enquiry');	
        $this->db->where('company!=','');		
        $cname = $this->db->get()->result();
		foreach($cname as $key=>$value){
			if (is_numeric($value->company)) {
                
			} else {
				$key = array( 
                                "company_name"  => $value->company,
                                "process_id"   => '141',
                                "comp_id"  => $this->session->companey_id
                                );
								
                $this->db->insert('tbl_company',$key);
				$last_id = $this->db->insert_id();
				
                $this->db->set('company',$last_id);
				$this->db->where('enquiry_id',$value->enquiry_id);
                $this->db->update('enquiry');
				 
			}
		}
       
    }
}