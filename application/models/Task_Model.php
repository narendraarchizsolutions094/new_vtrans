<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Task_Model extends CI_Model {

    private $table = "clients";

public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');		
    }

    public function get_alltask() {
        $this->db->select(" * ");
        $this->db->from('query_responses_update');
        //$this->db->where('query_id',$leadid);
        $this->db->order_by('uid', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }



    public function get_recent_task() {
        $this->db->select(" * ");
        $this->db->from('query_response');
        //$this->db->where('query_id',$lead_id);
        $this->db->order_by('resp_id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_recent_taskbyID($lead_id) {
        $this->db->select(" * ");
        $this->db->from('query_response');
        $this->db->where('query_id', $lead_id);
        $this->db->order_by('resp_id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function search_taskby_date($task_start, $task_id) {
        $this->db->select(" * ");
        $this->db->from('query_response');
        $this->db->where('query_id', $task_id);
        $this->db->like('nxt_date', $task_start);
        $this->db->order_by('resp_id', 'DESC');
        $query = $this->db->get();
        return $query->result(); 
    }

    public function search_taskby_id($date) {
        $user_id = $this->session->user_id;
        if($this->session->filter_user_id){                                
            $user_id = $this->session->filter_user_id;
        }        
        //$all_reporting_ids    =    $this->common_model->get_categories($user_id);               

        $user_role = $this->session->user_role;
        $this->db->select("query_response.resp_id,query_response.task_for,query_response.task_type,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,tbl_admin.s_display_name as user_name,tbl_taskstatus.taskstatus_name as task_status");
        $this->db->from('query_response');       
        //$this->db->where_in('query_response.create_by',$all_reporting_ids);
        
        if(!empty($_POST['task_for']) && $_POST['task_for'] == 2){
            $where = " (tbl_ticket.added_by=$user_id OR tbl_ticket.assign_to=$user_id) AND query_response.task_date='".$date."'";
            $this->db->join('tbl_ticket', 'tbl_ticket.ticketno=query_response.query_id', 'left');    
        }else{
            $where = " (enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id) AND query_response.task_date='".$date."'";
            $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
        }
        

        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
        $this->db->join('tbl_taskstatus', 'tbl_taskstatus.taskstatus_id=query_response.task_status', 'left');
        //$this->db->where('query_response.task_date',$date);
        if(!empty($_POST['task_for']) && $_POST['task_for'] == 2){
            $where .= " AND query_response.task_for=".$_POST['task_for'];
        }
        $this->db->where($where);
        $this->db->order_by('query_response.resp_id', 'DESC');
        $query = $this->db->get();
        return $query->result();

    }

    public function search_task($task_start, $task_id) {
        $this->db->select(" * ");
        $this->db->from('tbl_comment');
        $this->db->where('lead_id', $task_id);
        $this->db->like('created_date', $task_start);
        $this->db->order_by('comm_id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function search_task_btw_date($task_start, $task_id, $task_end) {
        $this->db->select(" * ");
        $this->db->from('query_response');
        $this->db->where('query_id', $task_id);
        $this->db->where('nxt_date BETWEEN "' . $task_start . ' 00:00:00 am" and "' . $task_end . ' 00:00:00 am"');
        $this->db->order_by('resp_id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function search_btw_task($task_start, $task_id, $task_end) {
        $this->db->select(" * ");
        $this->db->from('tbl_comment');
        $this->db->where('lead_id', $task_id);
        $this->db->where('created_date BETWEEN "' . $task_start . ' 00:00:00" and "' . $task_end . ' 00:00:00"');
        $this->db->order_by('comm_id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_allcoment() {
        $this->db->select(" * ");
        $this->db->from('tbl_comment');
        $this->db->where('created_by', $this->session->user_id);
        $this->db->order_by('comm_id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_task_level($id) {
        $user_role = $this->session->user_role;
       
        $this->db->select("*,tbl_admin.s_display_name as user_name,");
       
        $this->db->from('query_response');
       
       //$this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
       $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');

        if($user_role==3 || $user_role==2){
        }else{
            $this->db->where('query_response.create_by',$id);
        }
        // $this->db->where('query_response.create_by', $id);
        
        $this->db->order_by('query_response.resp_id', 'DESC');
        
        //$this->db->limit(30);
        $query = $this->db->get();

        
        return $query->result();
    }
    public function get_task_calandar_feed($start,$end,$user_id=0,$enq_id=0){
        if (!$user_id) {
		    $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);              
            $user_id = $this->session->user_id;      
        }
        
        $start = date("d-m-Y", strtotime($start));  
        $end = date("d-m-Y", strtotime($end));         

        $this->db->select("*,tbl_admin.s_display_name as user_name,");       
        $this->db->from('query_response');              
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
        
		$this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
        $where = '';
     
		if(empty($enq_id)){
        if($where){
            $where .= " AND (STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y'))";            
        } else{
          
            $where .= " STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y')";
        }
		if(!$user_id){
            $where .= "  AND query_response.create_by IN (".implode(',', $all_reporting_ids).')';
        }else{
			$where .= " AND enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id";
           
        }        

        }else if(!empty($enq_id)){
			if($where){
            $where .= " AND (STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y'))";            
        } else{
            $where .= " STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y')";
        }
            $where .= " AND query_response.query_id='".$enq_id."'";
        }else{
			if($where){
            $where .= " AND (STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y'))";            
        } else{         
            $where .= " STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y')";
        }
		if(!$user_id){
            $where .= "  AND query_response.create_by IN (".implode(',', $all_reporting_ids).')';
        }else{
			$where .= " AND enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id";           
        }
			
			}

        $this->db->where($where);        
        $this->db->order_by('query_response.resp_id', 'DESC');                
        $query = $this->db->get();
        return $query->result();
    }

    public function get_task_calandar_feed_ticket($start,$end,$user_id=0,$enq_id=0){
        if (!$user_id) {
		    $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);              
            $user_id = $this->session->user_id;      
        }
        
        $start = date("d-m-Y", strtotime($start));  
        $end = date("d-m-Y", strtotime($end));         

        $this->db->select("*,tbl_admin.s_display_name as user_name,");       
        $this->db->from('query_response');              
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
		$this->db->join('tbl_ticket', 'tbl_ticket.ticketno=query_response.query_id', 'left');
        $where = '';
      
		if(empty($enq_id)){
        if($where){
            $where .= " AND (STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y'))";            
        } else{
            //$where .= " DATE_FORMAT(query_response.task_date,'%d-%m-%Y') between ".$start." AND `".$end."`";

            $where .= " STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y')";
        }
		if(!$user_id){
            $where .= "  AND query_response.create_by IN (".implode(',', $all_reporting_ids).')';
        }else{
			$where .= " AND tbl_ticket.added_by=$user_id OR tbl_ticket.assign_to=$user_id";
           // $where .= " AND query_response.create_by=$user_id";
        }        

        }else if(!empty($enq_id)){
			if($where){
            $where .= " AND (STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y'))";            
        } else{
            //$where .= " DATE_FORMAT(query_response.task_date,'%d-%m-%Y') between ".$start." AND `".$end."`";

            $where .= " STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y')";
        }
            $where .= " AND query_response.query_id='".$enq_id."'";
        }else{
			if($where){
            $where .= " AND (STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y'))";            
        } else{
            //$where .= " DATE_FORMAT(query_response.task_date,'%d-%m-%Y') between ".$start." AND `".$end."`";

            $where .= " STR_TO_DATE(query_response.task_date,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$start."','%d-%m-%Y') AND  STR_TO_DATE('".$end."','%d-%m-%Y')";
        }
		if(!$user_id){
            $where .= "  AND query_response.create_by IN (".implode(',', $all_reporting_ids).')';
        }else{
			$where .= " AND tbl_ticket.added_by=$user_id OR tbl_ticket.assign_to=$user_id";
           // $where .= " AND query_response.create_by=$user_id";
        }
			
			}

        $this->db->where($where);        
        $this->db->order_by('query_response.resp_id', 'DESC');                
        $query = $this->db->get();
        return $query->result();
    }
    public function get_task_byid() {

        $this->db->select("*");        
        $this->db->from('query_response');        
        $this->db->join('enquiry','enquiry.Enquery_id=query_response.query_id');        
        $user_role = $this->session->user_role;
        if($user_role == 8 || $user_role == 9){
            $this->db->where('query_response.create_by', $this->session->user_id);
        }
        $this->db->order_by('query_response.resp_id', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    //Get organisation list...
    public function organisation_list() {

        return $this->db->select('Enquery_id')
                        ->from('enquiry')
                        ->where('created_by', $this->session->user_id)
                        ->or_where('aasign_to', $this->session->user_id)
                        ->get()
                        ->result();
    }

    public function eventCount($id)
    {
        $user_role = $this->session->user_role;
        $this->db->select("count(task_date) as totalcount,task_date");
        $this->db->from('query_response');
        if($user_role==3 || $user_role==2){
        }else{
            $this->db->where('create_by',$id);
        }
        $this->db->where('create_by', $id);
        $this->db->group_by('task_date');
        $query = $this->db->get();
        return $query->result();

    }
	
	public function get_tasks_by_user_id($created_by,$action='data',$limit=-1,$offset=-1) {
        $this->db->select("resp.org_name,concat(enq.name_prefix, ' ',enq.name, '', enq.lastname) as contact_person, enq.email,enq.phone,resp.task_date,resp.resp_id,resp.comp_id,resp.query_id,resp.task_time,stg.lead_stage_name,ldscr.description , tbl_taskstatus.taskstatus_name as task_status");
        $this->db->from('query_response resp'); 
		$this->db->where('resp.create_by',$created_by);
		$this->db->join('enquiry enq', 'enq.Enquery_id = resp.query_id', 'left');
		$this->db->join('lead_stage stg', 'stg.stg_id = enq.lead_stage', 'left');
        $this->db->join('lead_description ldscr', 'ldscr.id = enq.lead_discription', 'left');
        $this->db->join('tbl_taskstatus', 'tbl_taskstatus.taskstatus_id=resp.task_status', 'left');
        $this->db->order_by('resp.resp_id', 'DESC');
        if($action=='count')
        {
            return $this->db->count_all_results();
        }
        else
        {
            if($limit!=-1 && $offset!=-1)
                $this->db->limit($limit,$offset);

            return $this->db->get()->result();
        }
    }


    // get single task
    public function get_task($id){
        $this->db->where('resp_id',$id);
        return $this->db->get('query_response')->row();
    }
}
