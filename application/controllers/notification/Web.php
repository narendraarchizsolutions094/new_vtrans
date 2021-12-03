<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Web extends CI_Controller{
    
    /**
     * Send to a single device
     */
    public function get_pop_reminder_content(){
    	$notification_id	=	$this->input->post('notication_id');
    	$enq_id	=	$this->input->post('enq_id');
    	$this->db->where('notification_id',$notification_id);
      $res	=	$this->db->get('query_response')->row_array();
      $task_type = 'Task';
      if($res['task_type'] == 1){
        $task_type = 'Task';
      }else if($res['task_type'] == 2){
        $task_type = 'Follow Up ';
      }else if($res['task_type'] == 3){
        $task_type = 'Appointment';
      }
      $html = '';
      $html = $task_type.'<br>';
    	if(!empty($res)){
            if($res['task_type']!=17)
            {
              if($res['task_for'] == 2){
                $this->db->where('ticketno',$enq_id);
                $enq_res    =   $this->db->get('tbl_ticket')->row_array(); 
                $url  = base_url().'ticket/view/'.$enq_res['ticketno'];

                $html .= '<b>Subject :'.$res['subject'].'</b><br>'.$res['task_remark'].'<br><a href="'.$url.'"><b>'.$enq_res['name'].'</b></a><br>';

              }else{
                    $this->db->select('enquiry_id,name_prefix,name,lastname,status');
                    $this->db->where('Enquery_id',$enq_id);
                    $enq_res	=	$this->db->get('enquiry')->row_array();    	                    
                    if ($enq_res['status'] == 1) {
                      $url  = base_url().'enquiry/view/'.$enq_res['enquiry_id'];
                    }else if($enq_res['status'] == 2) {
                      $url  = base_url().'lead/lead_details/'.$enq_res['enquiry_id'];
                    }else if($enq_res['status'] >= 3) {
                      $url  = base_url().'client/view/'.$enq_res['enquiry_id'];
                    }else{
                      $url  = 'javascript:void(0)';
                    }
                    $html .= '<b>Subject :'.$res['subject'].'</b><br>'.$res['task_remark'].'<br><a href="'.$url.'"><b>'.$enq_res['name_prefix'].' '.$enq_res['name'].' '.$enq_res['lastname'].'</b></a><br>';
              }
        }elseif($res['task_type']==50)
        {
           $html .= '<b>'.$res['task_remark'].'</b>';
        }
        else
        {
            $this->db->select('*');
            $this->db->where('ticketno',$enq_id);
            $enq_res    =   $this->db->get('tbl_ticket')->row_array(); 
            $url  = base_url().'ticket/view/'.$enq_res['ticketno'];
           
           $html .= '<b>Subject :'.$enq_res['message'].'</b><br>'.$res['task_remark'].'<br><a href="'.$url.'"><b>'.$enq_res['name'].'</b></a><br>';
        }
    	}
    	echo $html;
    }
    public function notification_redirect($enq_id){
        $this->db->select('enquiry_id,status');
        $this->db->where('Enquery_id',$enq_id);
        $enq_res    =   $this->db->get('enquiry')->row_array();                 
        if ($enq_res['status'] == 1) {
          $url  = base_url().'enquiry/view/'.$enq_res['enquiry_id'];
        }else if($enq_res['status'] == 2) {
          $url  = base_url().'lead/lead_details/'.$enq_res['enquiry_id'];
        }else if($enq_res['status'] >= 3) {
          $url  = base_url().'client/view/'.$enq_res['enquiry_id'];
        }else{
          $url  = 'javascript:void(0)';
        }
        redirect($url,'refresh');
    }
    
    public function get_bell_notification_content()
    {   
        $limit = ($this->input->post('limit')) ? $this->input->post('limit') : 20;
        $load = $this->input->post('loaddata');
        $dyn_part = get_dynamic_partition();
        $this->db->from("query_response","PARTITION($dyn_part)");		        
        $user_id = $this->session->user_id;              
        $this->db->select("query_response.related_to,query_response.notification_id,query_response.resp_id,query_response.task_type,query_response.noti_read,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as user_name,enquiry.enquiry_id,enquiry.status as enq_status,ticket.assign_to,ticket.assigned_by,ticket.name as ticket_name,tbl_company.company_name as company,enquiry.client_name");      
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
        
        $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
        $this->db->join('tbl_company', 'tbl_company.id=enquiry.company', 'left');
        $this->db->join('tbl_ticket ticket', 'ticket.ticketno=query_response.query_id', 'left');
        $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');

        $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR ticket.assign_to=$user_id OR ticket.assigned_by=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";
        $this->db->where($where);
        $this->db->limit($limit);
       	$data['res']	=	$this->db->get()->result_array();
        $data['limit']  = ($limit + 20);
        if($load!='')
        { 
            echo json_encode(array('html'=>$this->load->view('notifications/bell_notification',$data,true)));
        }
        else
        {
            echo $this->load->view('notifications/bell_notification',$data,true);
        }
    	
    }
    public function mark_as_read(){
        $id    =   $this->input->post('id');
        $this->db->where('resp_id',$id);
        $this->db->set('noti_read',1);
        $this->db->update('query_response');
    }
    public function mark_as_unread(){
        $id    =   $this->input->post('id');
        $this->db->where('resp_id',$id);
        $this->db->set('noti_read',0);
        $this->db->update('query_response');
    }
    public function count_bell_notification(){
        //echo 0;
         $dyn_part = get_dynamic_partition();
         $this->db->from("query_response","PARTITION($dyn_part)");		        
         //$this->db->from('query_response');              
         $user_id = $this->session->user_id;              
         $this->db->select("query_response.related_to,query_response.resp_id,query_response.noti_read,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,tbl_admin.s_display_name as user_name");      
         $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'inner');
         $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
         $this->db->join('tbl_visit visit', 'visit.id=query_response.query_id', 'left');

         $where = " ((enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id OR visit.user_id=$user_id) OR query_response.create_by=$user_id OR query_response.related_to=$user_id)  AND query_response.noti_read=0 AND CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) <= NOW() ORDER BY CONCAT(str_to_date(task_date,'%d-%m-%Y'),' ',task_time) DESC";
         $this->db->where($where);
         echo $this->db->get()->num_rows();
         //echo $this->db->last_query();
    }
}

