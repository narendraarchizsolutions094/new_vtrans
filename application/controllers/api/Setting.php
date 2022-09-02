<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Setting extends REST_Controller {
    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function get_all_labels_post(){
        $comp_id = $this->input->post('comp_id');
        $this->db->where('comp_id',$comp_id);
        $this->db->or_where('comp_id',0);
        $lang    =  $this->db->get('language')->result_array();
  
        $this->set_response([
            'status' => false,
            'message' => $lang  
           ], REST_Controller::HTTP_OK);  
    }
	
	public function get_app_labels_post(){
        $comp_id = $this->input->post('comp_id');
        $this->db->where('comp_id',$comp_id);
		$this->db->where('for_app','1');
        $lang    =  $this->db->get('language')->result_array();
        if(!empty($lang)){
            
  
        $this->set_response([
            'status' => true,
            'message' => $lang  
           ], REST_Controller::HTTP_OK);
            
        }else{
            $this->set_response([
                'status' => false,
                'message' => "No Data Found",  
               ], REST_Controller::HTTP_OK);
        } 
    }
	
	public function get_apk_post(){
        $comp_id = $this->input->post('comp_id');
        $this->db->where('comp_id',$comp_id);
		$this->db->where('latest_app','1');
        $apk    =  $this->db->get('tbl_apk_version')->row();

        $this->set_response([
            'status' => true,
            "version_name" => $apk->version_name,
            "version_code" => $apk->version_code,
            "apk_url" => $apk->apk_url, 
           ], REST_Controller::HTTP_OK);  
    }
	
	public function profile_apk_post(){
        $apk = $this->input->post('apk');
		$user_id = $this->input->post('user_id');

if(!empty($apk && $user_id)){
	
	    $this->db->set('used_apk',$apk);
		$this->db->where('pk_i_admin_id',$user_id);
        $this->db->update('tbl_admin');
		
        $this->set_response([
            'status' => true,
            "msg" => 'Apk file updated', 
           ], REST_Controller::HTTP_OK); 
}else{
	    $this->set_response([
            'status' => false,
            'message' => 'Something went wrong!',  
           ], REST_Controller::HTTP_OK);
}	
    }

   
} 