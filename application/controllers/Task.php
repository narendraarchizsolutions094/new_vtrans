<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Task extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->model(array(
            'Task_Model', 'Leads_Model', 'Client_Model', 'enquiry_model', 'User_model','Taskstatus_model'
        ));
        if(empty($this->session->user_id)){
         redirect('login');   
        }
    }
    public function phpinfo(){
        echo phpinfo();
    } 
    public function index() {
        if (user_role('90') == true) {}  
        $this->session->unset_userdata('filter_user_id');
        $aid = $this->session->userdata('user_id');        
        $data['title'] = 'Task';
        $data['recent_tasks'] = '';       
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();         
        $data['user_list'] = $this->User_model->read();
        $data['content'] = $this->load->view('task/tasks', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function get_update_task_content(){
        $id    =   $this->input->post('id');
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();                 
        $data['task']    =   $this->Task_Model->get_task($id);
        $content    = $this->load->view('task/update_task_modal_content',$data,true);
        echo $content;
    }
    public function get_calandar_feed($enq_id=0){      
        $user_id = $this->input->post('user_id');
        $this->session->set_userdata('filter_user_id',$user_id);
        $start = $this->input->post('start');
        $end = $this->input->post('end');
        $for = $_GET['for']??1;
        if($for == 2){
            $cal_feed    =   $this->Task_Model->get_task_calandar_feed_ticket($start,$end,$user_id,$enq_id);                
        }else{
            $filter = $this->input->post('filter');
            $cal_feed    =   $this->Task_Model->get_task_calandar_feed($start,$end,$user_id,$enq_id,$filter);                            
        }
        $feed = array();        
        foreach ($cal_feed as $task){
            $task_date1   =  date_create($task->task_date);
            $color = '#0073b7';
            if($task->task_type == 1){
                $color = '#0073b7';
            }else if($task->task_type == 2){
                $color = '#ff0000';
            }else if($task->task_type == 3){
                $color = '#40ff00';
            }
            $borderColor = "#0073b7";
            if($task->task_for == 2){
                $borderColor = "#ffff00";
            }

            if(!empty($task_date1)){
                $title = $task->subject;
                if($task->task_for == 1 || $task->task_for == 0){
                $this->db->where('Enquery_id',$task->query_id);
                $enquiry_row   =    $this->db->get('enquiry')->row_array();            
                 $name = $enquiry_row['name_prefix'].''.$enquiry_row['name'].''.$enquiry_row['lastname'];
                 if ($name == '') {
                     $name = $task->mobile;
                     if (!$name) {
                        $name = 'NA';
                     }
                }
                }else{
                    $this->db->where('ticketno',$task->query_id);
                    $ticket_row   =    $this->db->get('tbl_ticket')->row_array();  
                    $name = $ticket_row['name']??$task->subject;
                }
               $dt = date_format($task_date1,'Y-m-d'); 
               $feed[] = array(
                   'title' =>   $title??$name,
                   'start' =>   $dt,
                   'backgroundColor' =>$color,
                   'url'    =>  '',
                   'borderColor'    =>  $borderColor
               );               
            }
        }
        echo json_encode($feed);
     
    }
     
    public function task_load(){
        $this->load->model('task_datatable_model');
        $list = $this->task_datatable_model->get_datatables();
        
        //echo $this->db->last_query();

        $data = array();
        $no = $_POST['start'];        
        $i = 1;        
        foreach ($list as $each) {        
            $no++;        
            $row = array();        
            if(!empty($each->task_date) && $each->task_date!='01-01-1970'){
              $d = strtotime($each->task_date);               
              $nd = date("Y/m/d",$d);               
              $nd = $each->task_date?$nd:'NA';                                 
            }else{
              $nd = 'NA';
              $nt = 'NA';
            }
            $row[] = $nd;
            $row[] = $each->task_time;
            $row[] = $each->subject;
            
            if($each->task_type == 1){
                $row[] = 'Task';
            }else if($each->task_type == 2){
                $row[] = 'Follow Up';
            }else if($each->task_type == 3){
                $row[] = 'Appointment';
            }else{
                $row[] = 'Task';
            }

            $row[] = $each->task_remark;
         if($each->task_for == 2){
            $this->db->where('ticketno',$each->query_id);
            $ticket_row   =    $this->db->get('tbl_ticket')->row_array(); 
            $name = 'NA';
            if(!empty($ticket_row)){
                $url = base_url().'ticket/view/'.$each->query_id;
                $name = $ticket_row['name']??$ticket_row['ticketno'];
            }else{
                $url = "javascript:void(0)";
            }
         }else{
            $this->db->where('Enquery_id',$each->query_id);
            $enquiry_row   =    $this->db->get('enquiry')->row_array();
            if (!empty($enquiry_row)) {                              
              if ($enquiry_row['status'] == 1) {
                 $url = base_url().'enquiry/view/'.$enquiry_row['enquiry_id'];                  
              }else if ($enquiry_row['status'] == 2) {                 
                 $url = base_url().'lead/lead_details/'.$enquiry_row['enquiry_id'];                  
              }else if ($enquiry_row['status'] >= 3) {                 
                 $url = base_url().'client/view/'.$enquiry_row['enquiry_id'].'/'.$enquiry_row['Enquery_id'];                  
              }              
            }else{
              $url = 'javascript:void(0)';
            }
            $name = $enquiry_row['name_prefix'].' '.$enquiry_row['name'].' '.$enquiry_row['lastname'];
        }
            if(empty($name) || !isset($name) || $name=='  '){
                $name = 'NA';
            }
            $row[] = "<a href='".$url."'>".$name.'</a>';
            $row[] = $each->user_name;
                if (user_access(450)) {
                    $row[] = '##########';                    
                }else{
                    $row[] = $each->mobile;
                }            
            $taskStatus = 'Pending';                         
            $taskstatus_list = $this->Taskstatus_model->taskstatuslist();          
            $row[] =  $each->task_status?$each->task_status:$taskStatus;            
                $actions = '<a data-toggle="modal" type="button" title="Add Target" data-target="#task_edit" onclick="get_modal_content('.$each->resp_id.')" ><i class="fa fa-edit btn btn-primary btn-sm"></i></a>';
                if(user_access(92)){ 
                   $actions .=  '<i class="fa fa-trash btn btn-danger btn-sm" onclick="delete_row('.$each->resp_id.')" ></i>';                  
                }
                $row[] = $actions;                
                $data[] = $row;
                $i++;
            }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->task_datatable_model->count_all(),
            "recordsFiltered" => $this->task_datatable_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function tas_byid($aid = '') {
        $data['title'] = 'Task';        
        $data['recent_tasks'] = $this->Task_Model->get_task_level($aid);
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist(); 
        $data['content'] = $this->load->view('task/task_list', $data);
    }
    public function create_task_form(){        
        $data['related_to']    =   $this->enquiry_model->all_enqueries();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist(); 
        $data['company_list'] = $this->Client_Model->getCompanyList()->result();
        $this->load->view('task/create_task_form',$data);
    }
    public function search_comment_and_task($date = '') {
        $details = '';
 
        $date = date_create($date);
        $date = date_format($date,'d-m-Y'); 
        $recent_tasks = $this->Task_Model->search_taskby_id($date);      
        

        $taskstatus_list = $this->Taskstatus_model->taskstatuslist(); 
        $details .= '<style>.paging_simple_numbers{text-align:center!important;}
            </style>
                    <table class="datatable1 table table-striped table-bordered"  cellspacing="0" width="100%">
                       <thead>
                          <tr>
                           <th>Date</th>
                           <th>Time</th>
                           <th>Task</th>
                           <th>Task Type</th>
                           <th>Remark</th>
                           <th>Person Name</th>                           
                           <th>Created By</th>
                           <th>Mobile No </th>
                           <th>Task Status</th>                           
                           <th>Actions</th>                           
                          </tr>
                       </thead>
                       <tbody>
                          ';

        foreach ($recent_tasks as $task) {           
            $taskStatus = 'Pending'; 
            $taskStatus = !empty($task->task_status)?$task->task_status:$taskStatus;

            if(!empty($task->task_for) && $task->task_for == 2){
                $this->db->where('ticketno',$task->query_id);
                $ticket_row   =    $this->db->get('tbl_ticket')->row_array();              
                $name = '';
                if (!empty($ticket_row)) {
                    $name = $ticket_row['name'];
                }
                $url = base_url().'ticket/view/'.$ticket_row['ticketno'];
            }else{
                $this->db->where('Enquery_id',$task->query_id);
                $enquiry_row   =    $this->db->get('enquiry')->row_array();
                $name = '';
                if (!empty($enquiry_row)) {
                    if ($enquiry_row['status'] == 1) {
                        $url = base_url().'enquiry/view/'.$enquiry_row['enquiry_id'];
                    }else if ($enquiry_row['status'] == 2) {
                        $url = base_url().'lead/lead_details/'.$enquiry_row['enquiry_id'];
                    }else if ($enquiry_row['status'] == 3) {
                        $url = base_url().'client/view/'.$enquiry_row['enquiry_id'].'/'.$enquiry_row['Enquery_id'];
                    }
                    $name = $enquiry_row['name_prefix'].' '.$enquiry_row['name'].' '.$enquiry_row['lastname'];
                }else{                  
                      $url = 'javascript:void(0)';
                  }            
            }
            if(empty($name) || $name=='  '){
                $name = 'NA';
            }
            if (user_access(450)) {
                $p = '##########';                    
            }else{
                $p = $task->mobile;
            }
            $task_type = '';
            if($task->task_type == 1){
                $task_type = 'Task';            
            }else if($task->task_type == 2){
                $task_type = 'Follow-Up';            
            }else if($task->task_type == 3){
                $task_type = 'Appointment';
            }
              $details .= '
            <tr>
                <td>' . $task->task_date . '</td>
                <td>' . $task->task_time . '</td>
                <td>'. $task->subject. '</td>                               
                <td>'. $task_type. '</td>
                <td>'. $task->task_remark. '</td>                
                <td><a href="'.$url.'">' . $name . '</a></td>
                <td>'.$task->user_name.'</td>
                <td>' . $p. '</td>
                <td>'.$taskStatus.'</td>
                <td><a data-toggle="modal" type="button" title="Add Target" data-target="#task_edit" onclick="get_modal_content('.$task->resp_id.')" ><i class="fa fa-edit btn btn-primary btn-sm"></i></a>';
            if(user_access(92)){ 
               $details .=  '<i class="fa fa-trash btn btn-danger btn-sm" style="float:right;" onclick="delete_row('.$task->resp_id.')" title="Delete Task"></i>';
              
              }
              $details .= '</td>';
        }
        $details .= '</tbody></table><script> $(".datatable1").DataTable(); </script>';
        echo $details;
    } 
    public function delete_task_row(){
        if (user_access('92') == true) {            
            $id    =   $this->input->post('id');
			$cmt_enq_id = $this->db->select('query_id')->where('resp_id',$id)->get('query_response')->row();
			$subject='Task is deleted';
			$this->Leads_Model->add_comment_for_events($subject, $cmt_enq_id->query_id);
            $this->db->where('resp_id',$id);
            if($this->db->delete('query_response')){
                echo json_encode(array('status'=>true,'msg'=>display('task_delete_msg')));
            }else{
                echo json_encode(array('status'=>false,'msg'=>display('something_went_wrong')));            
            }
        }else{
            echo json_encode(array('status'=>false,'msg'=>display('access_denied')));            
        }                      
    }
    public function save_task(){
       $subject = $this->input->post('subject',true);
       $task_type = $this->input->post('task_type',true);
       $task_date = $this->input->post('task_date',true);
       $task_time = $this->input->post('task_time',true);
       $related_to = $this->input->post('related_to',true);
       $task_status = $this->input->post('task_status',true);
       $task_remark = $this->input->post('task_remark',true);
       
       $task_date = date("d-m-Y", strtotime($task_date));
       $task_time = date("H:i:s", strtotime($task_time));

       $this->form_validation->set_rules('subject','Subject','required|trim');
       $this->form_validation->set_rules('task_type','Task Type','required|trim');
       $this->form_validation->set_rules('task_date','Task Date','required|trim');
       $this->form_validation->set_rules('task_time','Task Time','required|trim');
       $this->form_validation->set_rules('related_to','Related To','required|trim');
       $this->form_validation->set_rules('task_status','Task Status','required|trim');
       $this->form_validation->set_rules('task_remark','Task Remark','required|trim');

       if ($this->form_validation->run()){
           $this->db->where('comp_id',$this->session->companey_id);
           $this->db->where('Enquery_id',$related_to);
           $enq_row  =    $this->db->get('enquiry')->row_array();
           $ins_arr = array(
               'comp_id'        =>  $this->session->companey_id,
               'subject'        =>  $subject,
               'task_type'      =>  $task_type,
               'task_date'      =>  $task_date,
               'task_time'      =>  $task_time,
               'query_id'       =>  $related_to,
               'task_status'    =>  $task_status,
               'mobile'         =>  $enq_row['phone']??0,
               'email'          =>  $enq_row['email']??'',
               'task_remark'    =>  $task_remark,
               'notification_id'=> $this->input->post('task_notification_id'),
               'create_by'      =>  $this->session->user_id
           );
           $this->db->insert('query_response',$ins_arr);
           $this->session->set_flashdata('message','Task Created successfully.');
       }else{
            $this->session->set_flashdata('exception',validation_errors());
       }
       redirect('task/index');
    }
}