<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Notifications extends REST_Controller {
    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function count_bell_notification_post(){
        $user_id  = $this->input->post('user_id');        
        $this->form_validation->set_rules('user_id', 'User Id', 'required');                   
        if ($this->form_validation->run() == TRUE) {
          $this->db->from('query_response');                        
          $this->db->select("query_response.resp_id,query_response.noti_read,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,tbl_admin.s_display_name as user_name,");      
          $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
          $where = " (enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id)  AND query_response.noti_read=0 AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";
          $this->db->where($where);
          $msg =  $this->db->get()->num_rows();          
          $this->set_response([
                'status' => TRUE,
                'message' => $msg
            ], REST_Controller::HTTP_OK);
        } else {
          $this->set_response([
                'status' => false,
                'message' =>strip_tags(validation_errors())
             ], REST_Controller::HTTP_OK);
        }
    }
    public function get_bell_notification_content_post(){      
        $user_id  = $this->input->post('user_id');        
        $this->form_validation->set_rules('user_id', 'User Id', 'required');                   
        if ($this->form_validation->run() == TRUE) {     
          $this->db->from('query_response');       
          $this->db->select("query_response.resp_id,query_response.noti_read,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as user_name,enquiry.enquiry_id,enquiry.status as enq_status");      
          $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
          $where = " (enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id)  AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";
          $this->db->where($where);
          $res  = $this->db->get()->result_array();                
          $this->set_response([
                  'status' => TRUE,
                  'message' => $res
              ], REST_Controller::HTTP_OK);
        } else {
          $this->set_response([
                'status' => false,
                'message' =>strip_tags(validation_errors())
             ], REST_Controller::HTTP_OK);
        }                
    }
}
