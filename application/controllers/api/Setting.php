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
        $this->db->or_where('comp_id',0);
        $lang    =  $this->db->get('language')->result_array();
  
        $this->set_response([
            'status' => false,
            'message' => $lang  
           ], REST_Controller::HTTP_OK);  
    }

   
} 