<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Task extends REST_Controller {
    function __construct()
    {
        parent::__construct();
           $this->load->database();
           $this->load->library('form_validation');
           $this->load->helper('date');
		$this->load->model(array(
		'Task_Model','User_model','enquiry_model','Leads_Model','Client_Model'
		)); 
  }
  public function get_tasks_post(){            
    $this->form_validation->set_rules('user_id','User Id','required');
    
    $this->form_validation->set_message('required', 'Invalid %s');
      $offset = $this->input->post('offset')??0;
      $limit = $this->input->post('limit')??10;

    if ($this->form_validation->run() == true) {
        $user_id  = $this->input->post('user_id');  

          $total = $this->Task_Model->get_tasks_by_user_id($user_id,'count');
          $data = $this->Task_Model->get_tasks_by_user_id($user_id,'data',$limit,$offset);
            $res= array();    
            $res['offset'] = $offset;
            $res['limit'] = $limit;
            $res['total'] = $total;
            $res['list'] = $data;

          $this->set_response([
                'status' => TRUE,
                'data' =>$res
                 ], REST_Controller::HTTP_OK);

    }else{
        $this->set_response([
            'status' => false,
            'message' => str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))  
             ], REST_Controller::HTTP_OK);
    }
  }
  public function get_task_user_list_post(){
    $this->form_validation->set_rules('user_id','User Id','required');    
    $this->form_validation->set_message('required', 'Invalid %s');
    
    if ($this->form_validation->run() == true) {
      
      $user_id  = $this->input->post('user_id');  
      
      $user_row = $this->User_model->get_user_by_id($user_id);
      if ($user_row['user_roles'] == 2) {
        $user_list = $this->User_model->read_user_list($user_row);        
        
        if (empty($user_list)) {
            $this->set_response([
                      'status' => true,
                      'users' =>array(array('error'=>'No record found!'))
                       ], REST_Controller::HTTP_OK);        
          
        }else{
          $this->set_response([
                      'status' => true,
                      'users' =>$user_list
                       ], REST_Controller::HTTP_OK);        
        }
      }else{
            $this->set_response([
                      'status' => true,
                      'users' =>array(array('error'=>'User Id is not admin!'))
                       ], REST_Controller::HTTP_OK);        
        
      }
    }else{
      $this->set_response([
          'status' => false,
          'users' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
           ], REST_Controller::HTTP_OK);
    }
  }
  public function view_post()
  {
	  
    $this->form_validation->set_rules('task_id','Task Id','required');    
    $this->form_validation->set_message('required', 'Invalid %s');
	
    if ($this->form_validation->run() == true){
      $task_id  = $this->input->post('task_id');
	  $this->db->select("resp.*,enq.enquiry,enq.status as enqtype,stg.lead_stage_name,ldscr.description, concat(enq.name_prefix,' ',enq.name,' ',enq.lastname) as username,enq.email as enqmail,enq.phone as enqphone,stts.taskstatus_name");	
      $this->db->where('resp.resp_id',$task_id);
  	  $this->db->from('query_response resp');
  	  $this->db->join('enquiry enq', 'enq.Enquery_id = resp.query_id', 'Left');
  	  $this->db->join('lead_stage stg', 'stg.stg_id = enq.lead_stage', 'left');
      $this->db->join('lead_description ldscr', 'ldscr.id = enq.lead_discription', 'left');
      $this->db->join('tbl_taskstatus stts','stts.taskstatus_id=resp.task_status','left');
		  $task_row  = $this->db->get()->row();

        if(!empty($task_row))
        {
            $task_array = array(
              'id'                        => $task_row->resp_id,
              'enquery_code'              => $task_row->query_id,
              'subject'                   => $task_row->subject,
              'contact_person'            => $task_row->username,
              'mobile'                    => $task_row->enqphone,
              'email'                     => $task_row->enqmail,
              'designation'               => $task_row->designation,
              'org_name'                  => $task_row->org_name,
              'conversation'              => $task_row->task_remark,
              'task_date'                 => $task_row->task_date,
              'task_time'                 => $task_row->task_time,
         		  'task_name'			  => $task_row->subject,
        		  'end_date'				  => $task_row->nxt_date,
        		  'stage_name'				  => $task_row->lead_stage_name,		
        		  'stage_description'		  => $task_row->description,
                 'task_type'             => $task_row->task_type				  
                );
                if($task_row->enqtype == 1){
                  $task_array['related_to'] = 'Lead';
                }else if($task_row->enqtype == 2){
                  $task_array['related_to'] = 'Approach';
                }else if($task_row->enqtype == 3){
                  $task_array['related_to'] = 'Negotiation';
                }else if($task_row->enqtype == 4){
                  $task_array['related_to'] = 'Closure';
                }else if($task_row->enqtype == 5){
                  $task_array['related_to'] = 'Order';
                }else if($task_row->enqtype == 6){
                  $task_array['related_to'] = 'Future opportunities';
                }else{
        			$task_array['related_to'] = '';
        		      }
              // if($task_row->task_status == 0){
              //   $task_array['task_status'] = 'Pending';
              // }else if($task_row->task_status == 1){
              //   $task_array['task_status'] = 'Completed';
              // }else{
      			// $task_array['task_status'] = 'Pending';
              $task_array['task_status'] = $task_row->taskstatus_name;
			  $task_array['task_type'] = $task_row->task_type;
        }
        else
        {
          $task_array = array();
        }
        if(!empty($task_array))
        {
          $this->set_response([
                      'status' => true,
                      'task' =>$task_array
                       ], REST_Controller::HTTP_OK);
        }else{
          $this->set_response([
              'status' => false,
              'task' => array('error'=>'Invalid Task id')
               ], REST_Controller::HTTP_OK);
        }
    }else{
        $this->set_response([
            'status' => false,
            'task' => array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors())))
             ], REST_Controller::HTTP_OK);
    }
  
  }
  public function get_organizations_post(){            
    $this->form_validation->set_rules('user_id','User Id','required');
    
    $this->form_validation->set_message('required', 'Invalid %s');
    if ($this->form_validation->run() == true) {
      $user_id  = $this->input->post('user_id');   
      $user_role = $this->User_model->read_by_id($user_id); 
      
      $org_list = array();
      $active_enquiry = array();
      if(!empty($user_role)){
        
        $user_role=$user_role->user_roles;
        $this->load->model('enquiry_model');
        
        $active_enquiry = $this->enquiry_model->active_enqueries_api($user_id,$user_role)->result();
      }
      foreach ($active_enquiry as $key => $value) {
          $org_list[] = array('id'=>$value->Enquery_id,'name'=>$value->org_name);
      }

      $this->set_response([
                  'status' => true,
                  'org_list' =>$org_list
                   ], REST_Controller::HTTP_OK);
    }else{
        $this->set_response([
            'status' => false,
            'message' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))              
             ], REST_Controller::HTTP_OK);
    }
  }

public function create_post(){  
      //new code
      $ld_updt_by     = $this->input->post('user_id');
      $lead_id        = $this->input->post('enq_code');      
      $task_type      = $this->input->post('task_type');
      $subject        = $this->input->post('subject');
      $task_time      = $this->input->post('task_time');
      $meeting_date   = $this->input->post('meeting_date');
	    $end_time       = $this->input->post('end_time');
      $end_date       = $this->input->post('end_date');
      $contact_person = $this->input->post('contact_person');
      $mobileno       = $this->input->post('mobileno');
      $email          = $this->input->post('email');
      $designation    = $this->input->post('designation');
      $conversation   = trim($this->input->post('conversation'));
     // $end_date       = $this->input->post('end_date');
     // $end_time       = trim($this->input->post('end_time'));
      $related_to     = $this->input->post('related_to');
      $task_status    = trim($this->input->post('task_status'));
      $update_task    = $this->input->post('update_task');
	  
	  $company_id    = $this->input->post('comp_id');
      
      $this->form_validation->set_rules('enq_code', 'Enquiry Code', 'trim|required');
      
      if($this->form_validation->run() == true){
        if(!empty($lead_id)){
          if(!empty($this->enquiry_model->enquiry_by_code($lead_id))){
            $org_name = $this->enquiry_model->enquiry_by_code($lead_id)->org_name;
          }else{
             $org_name  = '';
          }
        }else{ 
          $org_name =  '';
        } 
        
        $cdate2       = str_replace('/' ,'-', $meeting_date);
        $task_date  = $meeting_date;//nice_date($cdate2, 'd-m-Y');
        $rem_date  = nice_date($cdate2, 'Y-m-d');
		    
        $tsktime=nice_date($task_time, 'H:i:s');
        
        $rem_time=nice_date($task_time, 'H:i');

        $adt          = date("d-m-Y h:i:s a");
         
		    $end_date2       = str_replace('/' ,'-', $end_date);
        $end_time2       = nice_date($end_time, 'h:i:s a');
        $nxt_date1   = $end_date2.' '.$end_time2;		
		 
		 
		 
		 
        $this->db->set('query_id',$lead_id);
        $this->db->set('subject',$subject);
        //$this->db->set('upd_date',$adt);      
        //$this->db->set('nxt_date',$nxt_date1);
        //$end_date = date('Y-m-d',strtotime($end_date));        
       // $this->db->set('task_date',$end_date);
       // $this->db->set('end_time',$end_time);
        $this->db->set('related_to',$related_to);     
        $this->db->set('task_status',$task_status);
        $this->db->set('contact_person',$contact_person);
        $this->db->set('mobile',$mobileno);
        $this->db->set('email',$email);
        $this->db->set('designation',$designation);
        $this->db->set('org_name',$org_name);
        $this->db->set('task_remark',$conversation);
		if(empty($update_task)){
        $this->db->set('task_type',$task_type);
		}
		    $this->db->set('task_date',$task_date);
		    $this->db->set('task_time',$tsktime);
        //echo $update_task;
          $this->load->model('Notification_model'); 
        if(!empty($update_task)){
          $this->db->where('resp_id',$update_task);
          $this->db->update('query_response');

          $this->Leads_Model->add_comment_for_events_stage_api('Task Updated',$lead_id,'','','',$ld_updt_by,'',$company_id);

          $this->db->select('notification_id');
          $this->db->where('resp_id',$update_task);          
          $r  = $this->db->get('query_response')->row_array();
          if($r){
            $res  = $this->Notification_model->update_task_reminder($ld_updt_by,$lead_id,$rem_date,$rem_time,$subject,$r['notification_id']);
            //echo $res;
          }else{
            $res  = $this->Notification_model->add_task_reminder($ld_updt_by,$lead_id,$rem_date,$rem_time,$subject);            
          }
        }else{

          $this->db->set('create_by',$ld_updt_by);
          $this->db->insert('query_response');  
          $tid = $this->db->insert_id();
          $res  = $this->Notification_model->add_task_reminder($ld_updt_by,$lead_id,$rem_date,$rem_time,$subject);

          $this->Leads_Model->add_comment_for_events_stage_api('Task Created',$lead_id,'','','',$ld_updt_by,'',$company_id);

          //print_r($res);
          $res = json_decode($res,true);
          $nid = $res['name']; // notification id
          $this->db->where('resp_id',$tid);
          $this->db->update('query_response',array('notification_id'=>$nid));
        }
        if(!empty($update_task)){
          $this->set_response([
                  'status' => true,
                  'message' => array('error'=>'Task updated successfully')  
                   ], REST_Controller::HTTP_OK);  
        }else{        
          $this->set_response([
                  'status' => true,
                  'message' => array('error'=>'Task created successfully')  
                   ], REST_Controller::HTTP_OK);  
        }
      }
      else {
          $this->set_response([
                'status' => false,
                'message' => array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors())))  
                 ], REST_Controller::HTTP_OK);  
      }
      // new code end
    }
    public function related_list_post(){
      $related_to  = $this->input->post('related_to');
      $user_id= $this->input->post('user_id');
      $res= array();
	   if(!empty($user_id)){
                 $user_role1 = $this->User_model->read_by_id($user_id); 
                    if(!empty($user_role1)){
               $user_role=$user_role1->user_roles;
          $cli = $this->enquiry_model->active_enqueries_api($user_id,$related_to,$user_role);        
          if (!empty($cli)) {          
            foreach ($cli->result() as $value) {
              array_push($res, array('enquery_code'=>$value->Enquery_id,'customer_name'=>$value->name_prefix.''.$value->name.''.$value->lastname));
            }
          }
		}
        $this->set_response([
          'status' => TRUE,
          'list' =>$res
           ], REST_Controller::HTTP_OK);
      } else {
        $this->set_response([
                'status' => false,
                'list' => array(array('error'=>str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))))  
                 ], REST_Controller::HTTP_OK); 
      }
     
    }
    public function related_to_post(){
      $arr  = array(array('name'=>'None','id'=>0),array('name'=>'Enquiry','id'=>1),array('name'=>'Lead','id'=>2),array('name'=>'Client','id'=>3));
      $this->set_response([
                'status' => TRUE,
                'related_to' => $arr,
                 ], REST_Controller::HTTP_OK);
    }
	 public function task_status_post(){
      
      /*$arr  = array(
        array(
          'name'=>'Pending',
          'id'=>0),
        array(
          'name'=>'Completed',
          'id'=>1
        )
      );*/
      $this->load->model('Taskstatus_model');
      $rarr = $this->Taskstatus_model->taskstatuslist($this->input->post('company_id'));                 
      $arr = array();
      if (!empty($rarr)) {
        foreach ($rarr as $key => $value) {
          $arr[] = array('id'=>$value->taskstatus_id,'name'=>$value->taskstatus_name);          
        }
      }
      $this->set_response([
                'status' => TRUE,
                'related_to' => $arr,
                 ], REST_Controller::HTTP_OK);
    }
    public function get_detail_post(){
		
    $enq_code=$this->input->post('enquery_code');
      
	  $data=$this->enquiry_model->enquiry_by_code($enq_code);
      
	  $res = array();
      
	  if (!empty($data)) {
        $res = array('customer_name'=>$data->name_prefix.' '.$data->name.' '.$data->lastname,'phone'=>$data->phone,'email'=>$data->email);        
      	$sts = true;
      }else{
      	$sts = false;
      	$res = array('error'=>'No enquiry found');
      }
      $this->set_response([
        'status' => $sts,
        'details' => $res,
         ], REST_Controller::HTTP_OK);
    }

    public function all_mode_type_post(){
      $res  = array();
      $all = $this->Task_Model->all_mode_type();
      if (!empty($all)) {
        foreach ($all as $key => $value) {
          array_push($res, array('exp_id'=>$value->exp_id,'exp_mode'=>$value->exp_mode,'exp_price_km'=>$value->exp_price_km,'exp_price_houre'=>$value->exp_price_houre));
        }
      }
      $this->set_response([
        'status' => TRUE,
        'modes' => $res,
         ], REST_Controller::HTTP_OK);
    } 
}
