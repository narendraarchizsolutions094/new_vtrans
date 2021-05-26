<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ticket_Report_datatable_model extends CI_Model {    
    public function __construct(){
        parent::__construct();
        $this->load->model('common_model');		
    }
    var $table = 'tbl_ticket'; 
    function report_analitics($for){

if($for == 'region_chart'){
	$fromdate = $this->input->post('from_created');
        $todate= $this->input->post('to_created');

		$process	=	$this->session->userdata('process')[0];	
		$user_id  	=  $this->session->userdata('user_id');
		$comp_id = $this->session->userdata('companey_id');
		
		$all_reporting_ids  = $this->common_model->get_categories($user_id);

		$this->db->select('sales_region.name as title,count(tbl_ticket.category) as count');
		$this->db->from('tbl_ticket');
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		
		$this->db->join('tbl_ticket_subject','tbl_ticket.category=tbl_ticket_subject.id');
		$this->db->join('branch','branch.branch_name=tbl_ticket_subject.subject_title');
		$this->db->join('sales_region','sales_region.region_id=branch.region_id');
		$this->db->where('tbl_ticket.process_id IN ('.$process.')');
		$this->db->where('tbl_ticket.company',$comp_id);
		if($fromdate!='all'){
			$this->db->where('date(tbl_ticket.coml_date) >=', $fromdate);
			$this->db->where('date(tbl_ticket.coml_date) <=', $todate);
		}
		$this->db->group_by('sales_region.region_id');
		$result = $this->db->get()->result_array();
		//print_r($result);exit;
            $res = array();
            if(!empty($result)){
                foreach($result as $key=>$value){
                    if($value['title']){
                        $title =  $value['title']??'NA';
                        $res[] = array($title,(int)$value['count']);
                    }
                }
            }
            return $res;
}else{	
        
        $user_id = $this->session->userdata('user_id');       
          
           $all_reporting_ids    =    $this->common_model->get_categories($user_id);    
        
           
        $from = $this->input->post('from_created');
        $to= $this->input->post('to_created');
        
        $updated_from = $this->input->post('update_from_created');
        $updated_to = $this->input->post('update_to_created');
        $process_id = $this->input->post('process_id');
        $source = $this->input->post('source');
        $problem = $this->input->post('problem');
        $priority = $this->input->post('priority');
        $issue = $this->input->post('issue');
        $createdby = $this->input->post('createdby');
        $assign = $this->input->post('assign');
        $prodcntry = $this->input->post('prodcntry');
        $stage = $this->input->post('stage');
        $sub_stage = $this->input->post('sub_stage');
        $ticket_status = $this->input->post('ticket_status');

        $companey_id = $this->session->userdata('comp_id');
        $group_by = '';
        $from_table    =   'tbl_ticket';
        if($for == 'source_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,lead_source.lead_name as title';
            $group_by = 'tbl_ticket.sourse';
        }else if($for == 'process_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,tbl_product.product_name as title';
            $group_by = 'tbl_ticket.process_id';
        }else if($for == 'stage_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,lead_stage.lead_stage_name as title';
            $group_by = 'tbl_ticket.ticket_stage';            
        }else if($for == 'sub_stage_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,lead_description.description as title';
            $group_by = 'tbl_ticket.ticket_substage';            
        }else if($for == 'user_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as title';
            $group_by = 'tbl_ticket.added_by';
        }else if($for == 'product_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,tbl_product_country.country_name as title';
            $group_by = 'tbl_ticket.product';
        }else if($for == 'status_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,tbl_ticket.status as title';
            $group_by = 'tbl_ticket.ticket_status';
        }else if($for == 'priority_chart'){
            $select = 'count(tbl_ticket.ticketno) as count,tbl_ticket.status as title';
            $group_by = 'tbl_ticket.ticket_status';
        }else{
            $select = 'count(tbl_ticket.ticketno) as count,lead_source.lead_name as title';
            $group_by = 'tbl_ticket.sourse';
              }            
            $this->db->select($select);   
            if($this->session->companey_id==''){
                $comp_id=$companey_id;
            }else{
                $comp_id=$this->session->companey_id;

            }                   
            $where = " tbl_ticket.company=".$comp_id."";      
            if ($from && $to) {
                $to = str_replace('/', '-', $to);
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $to = date('Y-m-d', strtotime($to));            
                $where .= " AND Date(tbl_ticket.coml_date) >= '$from' AND Date(tbl_ticket.coml_date) <= '$to'";
            } else if ($from && !$to) {
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $where .= " AND Date(tbl_ticket.coml_date) LIKE '%$from%'";
            } else if (!$from && $to) {            
                $to = str_replace('/', '-', $to);
                $to = date('Y-m-d', strtotime($to));
                $where .= " AND Date(tbl_ticket.coml_date) LIKE '%$to%'";
            }            
           if($createdby!=''){	            		
    			$where .= " AND ( tbl_ticket.added_by =".$createdby.")";
                // $where .= " OR tbl_ticket.assign_to  =".$assign."";  
                		  
            }else{
    			if($for == 'user_wise'){
                    $where .= " AND  tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
                }else{
                    $where .= " AND ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
                    $where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))'; 
                }
            }  
            if($assign!=''){	            		
                $where .= " AND tbl_ticket.assign_to  =".$assign."";  
            }  
            if($source!=''){
               $where .= " AND tbl_ticket.sourse =".$source."";  
            }
            
            if ($stage != '') {                
                $where .= " AND tbl_ticket.ticket_stage =".$stage."";
            }
            if ($sub_stage != '') {                
                $where .= " AND tbl_ticket.ticket_substage =".$sub_stage."";
            }
           
            if($process_id!=''){
            //    $where .= " AND tbl_ticket.process_id =".$process_id."";  
            // print_r($process_id);
            // die();
               $where .= " AND tbl_ticket.process_id IN (".$process_id. ")";  

            }
            if($problem!=''){
                $where .= " AND tbl_ticket.issue =".$problem."";
                     }
            // if($prodcntry!=''){
            //     $where .= " AND tbl_ticket.product IN (".implode(',', $prodcntry).')';  
            //  }
            if($updated_from || $updated_to){
                if ($updated_from && $updated_to) {
                    $updated_to = str_replace('/', '-', $updated_to);
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $updated_to = date('Y-m-d', strtotime($updated_to));            
                    $where .= " AND Date(tbl_ticket_conv.send_date) >= '$updated_from' AND Date(tbl_comment.send_date) <= '$updated_to'";
                } else if ($updated_from && !$updated_to) {
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $where .= " AND Date(tbl_ticket_conv.send_date) LIKE '%$updated_from%'";
                } else if (!$updated_from && $updated_to) {            
                    $updated_to = str_replace('/', '-', $updated_to);           
                     $updated_to = date('Y-m-d', strtotime($updated_to));
                    $where .= " AND Date(tbl_ticket_conv.send_date) LIKE '%$updated_to%'";
                }                
                $this->db->join('tbl_ticket_conv','tbl_ticket_conv.tck_id=tbl_ticket.id','inner');              
              
            }
            if($ticket_status!=''){            
                        $where .= " AND enquiry.ticket_status=".$ticket_status."";
            }
                     
            $this->db->join('tbl_product_country','tbl_product_country.id=tbl_ticket.product','left');   
            $this->db->join('lead_source','lead_source.lsid=tbl_ticket.sourse','left');            
            $this->db->join('tbl_product','tbl_product.sb_id=tbl_ticket.process_id','left');   
            $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_ticket.added_by','left');
            $this->db->join('tbl_admin as admin2','admin2.pk_i_admin_id=tbl_ticket.assign_to','left');      
            $this->db->join('lead_stage','lead_stage.stg_id=tbl_ticket.ticket_stage','left');        
            $this->db->join('lead_description','lead_description.id=tbl_ticket.ticket_substage','left');        
            
            // $this->db->where('tbl_ticket',$comp_id);    
            $this->db->where($where);
            if(!empty($group_by)){
                $this->db->group_by($group_by);
            }                           
            $result    =   $this->db->get($from_table)->result_array();
            // echo $this->db->last_query();
            // exit();
            $res = array();
            if(!empty($result)){
                foreach($result as $key=>$value){
                    if($value['title']){
                        $title =  $value['title']??'NA';
                        $res[] = array($title,(int)$value['count']);
                    }
                }
            }
            return $res;
}
    }
    function priorityreport_analitics($for,$priority1){
        $user_id = $this->session->userdata('user_id');

           if($this->session->user_id==''){  $user_id=$user_id;  }else{  $user_id=$this->session->user_id;  }  
           $all_reporting_ids    =    $this->common_model->get_categories($user_id);    
        
         
        $from = $this->input->post('from_created');
        $to= $this->input->post('to_created');
        
        $updated_from = $this->input->post('update_from_created');
        $updated_to = $this->input->post('update_to_created');
        $process_id = $this->input->post('process_id');
        $source = $this->input->post('source');
        $problem = $this->input->post('problem');
        $priority = $this->input->post('priority');
        $issue = $this->input->post('issue');
        $createdby = $this->input->post('createdby');
        $assign = $this->input->post('assign');
        $prodcntry = $this->input->post('prodcntry');
        $stage = $this->input->post('stage');
        $sub_stage = $this->input->post('sub_stage');
        $ticket_status = $this->input->post('ticket_status');
        $assigned_by=$this->input->post('assign_by');
        $companey_id = $this->session->userdata('companey_id');
        $group_by = '';
        $from_table    =   'tbl_ticket';
            $select = 'count(tbl_ticket.ticketno) as count,tbl_ticket.priority as title';
            $group_by = 'tbl_ticket.priority';
            $this->db->select($select);   
            if($this->session->companey_id==''){
                $comp_id=$companey_id;
            }else{
                $comp_id=$this->session->companey_id;
            }                   
            $where = " tbl_ticket.company=".$comp_id."";      
            if ($from && $to) {
                $to = str_replace('/', '-', $to);
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $to = date('Y-m-d', strtotime($to));            
                $where .= " AND Date(tbl_ticket.coml_date) >= '$from' AND Date(tbl_ticket.coml_date) <= '$to'";
            } else if ($from && !$to) {
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $where .= " AND Date(tbl_ticket.coml_date) LIKE '%$from%'";
            } else if (!$from && $to) {            
                $to = str_replace('/', '-', $to);
                $to = date('Y-m-d', strtotime($to));
                $where .= " AND Date(tbl_ticket.coml_date) LIKE '%$to%'";
            }            
            if($createdby!=''){	            		
    			$where .= " AND ( tbl_ticket.added_by =".$createdby.")";
                		  
            }else{
    			
                $where .= " AND ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
    			$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))'; 
            }  
            if($assign!=''){	            		
                $where .= " AND tbl_ticket.assign_to  =".$assign."";  
            }    
            if($source!=''){
               $where .= " AND tbl_ticket.sourse =".$source."";  
            }
            
            if ($stage != '') {                
                $where .= " AND tbl_ticket.ticket_stage =".$stage."";
            }
            if ($sub_stage != '') {                
                $where .= " AND tbl_ticket.ticket_substage =".$sub_stage."";
            }
            if ($priority != '') {                
                $where .= " AND tbl_ticket.priority =".$priority."";
                }else{ 
                $where .= " AND tbl_ticket.priority =".$priority1."";
                }
            if($process_id!=''){
               $where .= " AND tbl_ticket.process_id IN (".$process_id. ")";  
                }
                if($assigned_by!=''){
                $where .= " AND tbl_ticket.assigned_by =".$assigned_by."";
                     }
                     if($problem!=''){
                        $where .= " AND tbl_ticket.issue =".$problem."";
                             }
                             
                
            if($updated_from || $updated_to){
                if ($updated_from && $updated_to) {
                    $updated_to = str_replace('/', '-', $updated_to);
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $updated_to = date('Y-m-d', strtotime($updated_to));            
                    $where .= " AND Date(tbl_ticket_conv.send_date) >= '$updated_from' AND Date(tbl_comment.send_date) <= '$updated_to'";
                } else if ($updated_from && !$updated_to) {
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $where .= " AND Date(tbl_ticket_conv.send_date) LIKE '%$updated_from%'";
                } else if (!$updated_from && $updated_to) {            
                    $updated_to = str_replace('/', '-', $updated_to);           
                     $updated_to = date('Y-m-d', strtotime($updated_to));
                    $where .= " AND Date(tbl_ticket_conv.send_date) LIKE '%$updated_to%'";
                }                
                $this->db->join('tbl_ticket_conv','tbl_ticket_conv.tck_id=tbl_ticket.id','inner');              
              
            }
            if($ticket_status!=''){            
                        $where .= " AND enquiry.ticket_status=".$ticket_status."";
            }
                     
            $this->db->join('tbl_product_country','tbl_product_country.id=tbl_ticket.product','left');   
            $this->db->join('lead_source','lead_source.lsid=tbl_ticket.sourse','left');            
            $this->db->join('tbl_product','tbl_product.sb_id=tbl_ticket.process_id','left');   
            $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_ticket.added_by','left');
            $this->db->join('tbl_admin as admin2','admin2.pk_i_admin_id=tbl_ticket.assign_to','left');      
            $this->db->join('lead_stage','lead_stage.stg_id=tbl_ticket.ticket_stage','left');        
            $this->db->join('tbl_ticket_subject','tbl_ticket_subject.id=tbl_ticket.issue','left');        
            // $this->db->where('tbl_ticket',$comp_id);    
            $this->db->where($where);    
            
            if(!empty($group_by)){
                $this->db->group_by($group_by);
            }                           
            $result    =   $this->db->get($from_table)->row();
            // echo $this->db->last_query();
            // exit();
            $res = array();
            if(!empty($result)){
                    $res = (int)$result->count;
            }
            return $res;
    }
	
	function report_employee_wise($fromdate,$todate,$x='',$for=''){        
        
        $user_id = $this->session->userdata('user_id');       
          
           $all_reporting_ids    =    $this->common_model->get_categories($user_id);    
        
           
        $from = $fromdate;
        $to= $todate;
        
        $updated_from = $this->input->post('update_from_created');
        $updated_to = $this->input->post('update_to_created');
        $process_id = $this->input->post('process_id');
        $source = $this->input->post('source');
        $problem = $this->input->post('problem');
        $priority = $this->input->post('priority');
        $issue = $this->input->post('issue');
        $createdby = $this->input->post('createdby');
        $assign = $this->input->post('assign');
        $prodcntry = $this->input->post('prodcntry');
        $stage = $this->input->post('stage');
        $sub_stage = $this->input->post('sub_stage');
        $ticket_status = $this->input->post('ticket_status');

        $companey_id = $this->session->userdata('comp_id');
        $group_by = '';
        $from_table    =   'tbl_ticket';
        if($for == ''){
            $select = 'count(tbl_ticket.ticketno) as count,CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as title,tbl_admin.pk_i_admin_id as user_id,sales_region.name as region';
            $group_by = 'tbl_ticket.added_by';
        }else{
            $select = 'count(tbl_ticket.ticketno) as count';
			$this->db->where('tbl_ticket.ticket_stage',$x);
			$this->db->where('tbl_ticket.added_by',$for);
            $group_by = 'tbl_ticket.added_by';
        }            
            $this->db->select($select);   
            if($this->session->companey_id==''){
                $comp_id=$companey_id;
            }else{
                $comp_id=$this->session->companey_id;

            }                   
            $where = " tbl_ticket.company=".$comp_id."";      
            if ($from && $to) {
                $to = str_replace('/', '-', $to);
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $to = date('Y-m-d', strtotime($to));            
                $where .= " AND Date(tbl_ticket.coml_date) >= '$from' AND Date(tbl_ticket.coml_date) <= '$to'";
            } else if ($from && !$to) {
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $where .= " AND Date(tbl_ticket.coml_date) LIKE '%$from%'";
            } else if (!$from && $to) {            
                $to = str_replace('/', '-', $to);
                $to = date('Y-m-d', strtotime($to));
                $where .= " AND Date(tbl_ticket.coml_date) LIKE '%$to%'";
            }            
           if($createdby!=''){	            		
    			$where .= " AND ( tbl_ticket.added_by =".$createdby.")";
                // $where .= " OR tbl_ticket.assign_to  =".$assign."";  
                		  
            }else{
    			if($for == ''){
                    $where .= " AND  tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
                }else{
                    $where .= " AND ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
                    $where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))'; 
                }
            }  
            if($assign!=''){	            		
                $where .= " AND tbl_ticket.assign_to  =".$assign."";  
            }  
            if($source!=''){
               $where .= " AND tbl_ticket.sourse =".$source."";  
            }
            
            if ($stage != '') {                
                $where .= " AND tbl_ticket.ticket_stage =".$stage."";
            }
            if ($sub_stage != '') {                
                $where .= " AND tbl_ticket.ticket_substage =".$sub_stage."";
            }
           
            if($process_id!=''){
            //    $where .= " AND tbl_ticket.process_id =".$process_id."";  
            // print_r($process_id);
            // die();
               $where .= " AND tbl_ticket.process_id IN (".$process_id. ")";  

            }
            if($problem!=''){
                $where .= " AND tbl_ticket.issue =".$problem."";
                     }
            // if($prodcntry!=''){
            //     $where .= " AND tbl_ticket.product IN (".implode(',', $prodcntry).')';  
            //  }
            if($updated_from || $updated_to){
                if ($updated_from && $updated_to) {
                    $updated_to = str_replace('/', '-', $updated_to);
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $updated_to = date('Y-m-d', strtotime($updated_to));            
                    $where .= " AND Date(tbl_ticket_conv.send_date) >= '$updated_from' AND Date(tbl_comment.send_date) <= '$updated_to'";
                } else if ($updated_from && !$updated_to) {
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $where .= " AND Date(tbl_ticket_conv.send_date) LIKE '%$updated_from%'";
                } else if (!$updated_from && $updated_to) {            
                    $updated_to = str_replace('/', '-', $updated_to);           
                     $updated_to = date('Y-m-d', strtotime($updated_to));
                    $where .= " AND Date(tbl_ticket_conv.send_date) LIKE '%$updated_to%'";
                }                
                $this->db->join('tbl_ticket_conv','tbl_ticket_conv.tck_id=tbl_ticket.id','inner');              
              
            }
            if($ticket_status!=''){            
                        $where .= " AND enquiry.ticket_status=".$ticket_status."";
            }
                     
            $this->db->join('tbl_product_country','tbl_product_country.id=tbl_ticket.product','left');   
            $this->db->join('lead_source','lead_source.lsid=tbl_ticket.sourse','left');            
            $this->db->join('tbl_product','tbl_product.sb_id=tbl_ticket.process_id','left');   
            $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_ticket.added_by','left');
            $this->db->join('tbl_admin as admin2','admin2.pk_i_admin_id=tbl_ticket.assign_to','left');      
            $this->db->join('lead_stage','lead_stage.stg_id=tbl_ticket.ticket_stage','left');        
            $this->db->join('lead_description','lead_description.id=tbl_ticket.ticket_substage','left');
            $this->db->join('sales_region','sales_region.region_id=tbl_admin.sales_region','left');			
            
            // $this->db->where('tbl_ticket',$comp_id);    
            $this->db->where($where);
            if(!empty($group_by)){
                $this->db->group_by($group_by);
            }                           
            $result    =   $this->db->get($from_table)->result_array();
            // echo $this->db->last_query();
            // exit();
            $res = array();
		if($for == ''){
            if(!empty($result)){
                foreach($result as $key=>$value){
                    if($value['title']){
                        $title =  $value['title']??'NA';
						$region =  $value['region']??'NA';
						$user_id =  $value['user_id']??'NA';
                        $res[] = array($title,$region,(int)$value['count'],$user_id);
                    }
                }
            }
        }else{
            if(!empty($result)){
                foreach($result as $key=>$value){
                        $res[] = $value['count'];
                }
            }
        }
            return $res;
    }
    
} 