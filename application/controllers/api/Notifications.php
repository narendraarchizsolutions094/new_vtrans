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
          /* $this->db->from('query_response');                        
          $this->db->select("query_response.resp_id,query_response.noti_read,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,tbl_admin.s_display_name as user_name,");      
          $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
          $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');

          $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND query_response.noti_read=0 AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";          
        
          $this->db->where($where);
          $msg =  $this->db->get()->num_rows(); */
		  
          $dyn_part = get_dynamic_partition();
          
          $this->db->select("query_response.resp_id");
          $dyn_part = str_replace("'", "", $dyn_part);

          $this->db->from("query_response PARTITION($dyn_part)");		        	                       
		  $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'inner');
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
          $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');
          $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND query_response.noti_read=0 AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";          
          
          $this->db->where($where);
          $msg =  $this->db->get()->num_rows();		          
          if($this->input->post('xyz') == 'k'){
              
            // echo $dyn_part.' ';
            // echo $this->db->last_query();
          }  
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
    /* public function get_bell_notification_content_post(){      
        $user_id  = $this->input->post('user_id');
        $page = $this->input->post('nos') ? $this->input->post('nos') : 0;
        $per_page = '10';        
        $this->form_validation->set_rules('user_id', 'User Id', 'required');                   
        if ($this->form_validation->run() == TRUE) {     
          $dyn_part = get_dynamic_partition();
          
          //$this->db->from("query_response","PARTITION($dyn_part)");		

          $dyn_part = str_replace("'", "", $dyn_part);
          $this->db->from("query_response PARTITION($dyn_part)");	

          $this->db->select("query_response.resp_id,query_response.notification_id,query_response.noti_read,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as user_name,enquiry.enquiry_id,enquiry.status as enq_status,tbl_company.company_name as company,enquiry.client_name");      
          //$this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');

          $this->db->join('tbl_company', 'tbl_company.id=enquiry.company', 'left');
          $this->db->join('tbl_ticket ticket', 'ticket.ticketno=query_response.query_id', 'left');
          $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');
  
          $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR ticket.assign_to=$user_id OR ticket.assigned_by=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";

          $this->db->where($where);
		  $this->db->limit($per_page, $page);
          $res  = $this->db->get()->result_array();


//For total records 

        $dyn_part = get_dynamic_partition();
        $this->db->from("query_response","PARTITION($dyn_part)");		
          $this->db->select("query_response.resp_id");
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
          $this->db->join('tbl_ticket ticket', 'ticket.ticketno=query_response.query_id', 'left');
          $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');
  
          $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR ticket.assign_to=$user_id OR ticket.assigned_by=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";

          $this->db->where($where);
          $restotal  = $this->db->get()->result_array();

//End		  

          $total =count($restotal);
          $all =$res;
          $read=array();
          $unread=array();
          $today=array();
          foreach ($res as $value)
          {
              if($value['noti_read'])
                  $read[] = $value;
              if(!$value['noti_read'])
                  $unread[] = $value;
              if($value['task_date']==date("d-m-Y"))
                  $today[] = $value;
          }

          $data = array('all'=>$all,
                        'read'=>$read,
                        'unread'=>$unread,
                        'today' =>$today,
						'total' =>$total,
						'offset' =>$page,
                        );

          $this->set_response([
                  'status' => TRUE,
                  'data' => $data
              ], REST_Controller::HTTP_OK);
        } else {
          $this->set_response([
                'status' => false,
                'message' =>strip_tags(validation_errors())
             ], REST_Controller::HTTP_OK);
        }                
    } */	
	  public function get_bell_notification_content_post(){
        $user_id  = $this->input->post('user_id');
        $page = $this->input->post('nos') ? $this->input->post('nos') : 0;
        $per_page = '10';
        $status  = $this->input->post('status');
        $this->form_validation->set_rules('user_id', 'User Id', 'required');
        if ($this->form_validation->run() == TRUE) {
          $dyn_part = get_dynamic_partition();
          //$this->db->from("query_response","PARTITION($dyn_part)");
          $dyn_part = str_replace("'", "", $dyn_part);
          //var_dump($dyn_part); 
          $this->db->from("query_response PARTITION($dyn_part)");
          $this->db->select("query_response.resp_id,query_response.notification_id,query_response.noti_read,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as user_name,enquiry.enquiry_id,enquiry.status as enq_status,tbl_company.company_name as company,enquiry.client_name");
          //$this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
          $this->db->join('tbl_company', 'tbl_company.id=enquiry.company', 'left');
          $this->db->join('tbl_ticket ticket', 'ticket.ticketno=query_response.query_id', 'left');
          $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');
          $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR ticket.assign_to=$user_id OR ticket.assigned_by=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";
          $this->db->where($where);
		      $this->db->limit($per_page, $page);
          $res  = $this->db->get()->result_array();

          //echo $this->db->last_query();

          //For total records
          //$dyn_part = get_dynamic_partition();
          //$this->db->from("query_response","PARTITION($dyn_part)");
          $this->db->from("query_response PARTITION($dyn_part)");
          $this->db->select("query_response.resp_id");
          $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
          $this->db->join('tbl_ticket ticket', 'ticket.ticketno=query_response.query_id', 'left');
          $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');
          $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR ticket.assign_to=$user_id OR ticket.assigned_by=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW()";
          $restotal  = $this->db->where($where)->count_all_results();
          //$restotal  = $this->db->get()->result_array();
          //echo $this->db->last_query();
          //End
          //echo $restotal;
          $total =$restotal;
          $all =$res;
          $read=array();
          $unread=array();
          $today=array();
          foreach ($res as $value)
          {
              if($value['noti_read'])
                  $read[] = $value;
              if(!$value['noti_read'])
                  $unread[] = $value;
              if($value['task_date']==date("d-m-Y"))
                  $today[] = $value;
          }

          if($status == 1){
            $data = array(
                'all'   => $all,
                'read'  => $read,
                'unread'=> $unread,
                'today' => $today,
                'total' => $total,
                'offset'=> $page,
            );
          }else if($status == 2){
            $data = array(
                // 'all'   => $all,
                'read'  => $read,
                // 'unread'=> $unread,
                // 'today' => $today,
                'total' => $total,
                'offset'=> $page,
            );
          }else if($status == 3){
            $data = array(
                // 'all'   => $all,
                // 'read'  => $read,
                'unread'=> $unread,
                // 'today' => $today,
                'total' => $total,
                'offset'=> $page,
            );
          }else if($status == 4){
            $data = array(
                // 'all'   => $all,
                // 'read'  => $read,
                // 'unread'=> $unread,
                'today' => $today,
                'total' => $total,
                'offset'=> $page,
            );
          }

          $this->set_response([
                  'status' => TRUE,
                  'data' => $data
              ], REST_Controller::HTTP_OK);
        } else {
          $this->set_response([
                'status' => false,
                'message' =>strip_tags(validation_errors())
             ], REST_Controller::HTTP_OK);
        }                
    }

    public function mark_as_read_post()
    {
       $this->form_validation->set_rules('resp_id', 'resp_id', 'required');    
      

      if($this->form_validation->run())
      {
        $id   = $this->input->post('resp_id');
        $this->db->where('resp_id',$id);
        $this->db->set('noti_read',1);
        $this->db->update('query_response');

        $this->set_response([
                  'status' => TRUE,
                  'data' => 'Done',
              ], REST_Controller::HTTP_OK);
        } else {
          $this->set_response([
                'status' => false,
                'message' =>strip_tags(validation_errors())
             ], REST_Controller::HTTP_OK);
        }     

    }
    public function mark_as_unread_post()
    {

      $this->form_validation->set_rules('resp_id', 'resp_id', 'required');    
      
      if($this->form_validation->run())
      {
        $id    =   $this->input->post('resp_id');
        
        $this->db->where('resp_id',$id);
        $this->db->set('noti_read',0);
        $this->db->update('query_response');
          $this->set_response([
                  'status' => TRUE,
                  'data' => 'Done',
              ], REST_Controller::HTTP_OK);
        } else {
          $this->set_response([
                'status' => false,
                'message' =>strip_tags(validation_errors())
             ], REST_Controller::HTTP_OK);
        }     
    }
}
