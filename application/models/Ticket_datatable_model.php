<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ticket_datatable_model extends CI_Model{
    
    function __construct() {
        // Set table name

        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["ticket_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["ticket_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       

       $search_string = array();

        if($showall or in_array(1,$acolarr))
        {
            $search_string[] = "tck.ticketno";
        }
        if($showall or in_array(3,$acolarr))
        {
            $search_string[] = "tck.email";
        }
        if($showall or in_array(4,$acolarr))
        {
            $search_string[] = "tck.phone";
        }
        if($showall or in_array(5,$acolarr))
        {
            $search_string[] = "prd.country_name";
        }
        if($showall or in_array(8,$acolarr))
        {
            $search_string[] = "tck.priority";
        }
        if($showall or in_array(9,$acolarr))
        {
            $search_string[] = "tck.coml_date";
        }
        if($showall or in_array(10,$acolarr))
        {
            $search_string[] = "ref.name";
        }
        if($showall or in_array(11,$acolarr))
        {
            $search_string[] = "source.lead_name";
        }
        if($showall or in_array(12,$acolarr))
        {
            $search_string[] = "stage.lead_stage_name";
        }
        if($showall or in_array(13,$acolarr))
        {
            $search_string[] = "sub_stage.description";
        }
        if($showall or in_array(14,$acolarr))
        {
            $search_string[] = "tck.message";    
        }
    
        if($showall or in_array(15,$acolarr))
        {
            $search_string[] = "tck.tracking_no";    
        }

        if($showall or in_array(16,$acolarr))
        {
            $search_string[] = " status.status_name ";    
        }

        if($showall or in_array(17,$acolarr))
        {
            $search_string[] = " assign_by.s_display_name ";    
        }
        $this->table = 'tbl_ticket';
        // Set orderable column fields
        $this->column_order = array('', 'tck.id','tck.ticketno','tck.client','tck.email','prd.country_name','tck.assign_to','tck.added_by','tck.priority','tck.coml_date','ref.name','source.lead_name','stage.lead_stage_name','sub_stage.description','tck.message','tck.tracking_no');
        // Set searchable column fields
       

        $this->column_search = $search_string;

        // $this->column_search = array('tck.ticketno','tck.id','tck.category','tck.name','tck.email','tck.product','tck.message','tck.issue','tck.solution','tck.sourse','tck.ticket_stage','tck.review','tck.status','tck.priority','tck.complaint_type','tck.coml_date','tck.last_update','tck.send_date','tck.client','tck.assign_to','tck.company','tck.added_by','enq.phone','enq.gender','prd.country_name');
        
        // Set default order
        $this->order = array('tck.id' => 'desc');
    }
    
    /*
     * Fetch members data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData){
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
     
    /*
     * Count all records
     */
    public function countAll(){

        $this->db->from($this->table);
        if(!empty($_POST['specific_list']))
        {//echo 'infolist'.$_POST['specific_list']; exit();
           
            $where="(id IN (".$_POST['specific_list'].") ) ";
            $this->db->where($where);
        }

        if(!empty($this->session->process)){              
        $arr = $this->session->process;           
            if(is_array($arr)){
                $this->db->where_in("tbl_ticket.process_id",$arr);
            }    
        }
        $this->db->where('company',$this->session->companey_id);
        return $this->db->count_all_results();
    }
    
    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     
    public function _get_datatables_query($postData){
        $this->load->model('common_model');
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $comp_id = $this->session->companey_id;
        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["ticket_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["ticket_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       

        $sel_string = array();
        $sel_string[] = "tck.*"; 

        // if($showall or in_array(1,$acolarr))
        // {
        //     $sel_string[] = " tck.ticketno ";
        // }
        if($showall or in_array(2,$acolarr))
        {
            $sel_string[] = " concat_ws(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname,enq.company as org_name ";
        }
        // if($showall or in_array(3,$acolarr))
        // {
        //     $sel_string[] = " tck.email ";
        // }

        if($showall or in_array(5,$acolarr))
        {
            $sel_string[] = " prd.country_name ";
        }
        if($showall or in_array(6,$acolarr))
        {
            $sel_string[] = " concat(for_assign.s_display_name,' ',for_assign.last_name) as assign_to_name  ";
            $sel_string[] = "tck.last_esc";
        }
        if($showall or in_array(7,$acolarr))
        {
            $sel_string[] = " concat(for_created.s_display_name,' ',for_created.last_name) as created_by_name ";
        }
        // if($showall or in_array(8,$acolarr))
        // {
        //     $sel_string[] = " tck.priority ";
        // }
        // if($showall or in_array(9,$acolarr))
        // {
        //     $sel_string[] = " tck.coml_date ";
        // }
        if($showall or in_array(10,$acolarr))
        {
            $sel_string[] = " ref.name as referred_name ";
        }
        if($showall or in_array(11,$acolarr))
        {
            $sel_string[] = " source.lead_name as source_name ";
        }
        if($showall or in_array(12,$acolarr))
        {
            $sel_string[] = " stage.lead_stage_name ";
        }
        if($showall or in_array(13,$acolarr))
        {
            $sel_string[] = " sub_stage.description ";
        }
        if($showall or in_array(16,$acolarr))
        {
            $sel_string[] = " status.status_name ";    
        }

        if($showall or in_array(17,$acolarr))
        {
            $sel_string[] = " concat(assign_by.s_display_name,' ',assign_by.last_name) as assigned_by_name";    
        }
        if($showall or in_array(19,$acolarr))
        {
            $sel_string[] = " tbl_ticket_subject.subject_title";    
        }
        
        $followup = 0;
        if(!empty($_SESSION['ticket_filters_sess']))
        {
            if(isset($_SESSION['ticket_filters_sess']['followup']) && $_SESSION['ticket_filters_sess']['followup']=='1')
              $followup = 1;
        }

        if($followup)
        {
            $sel_string[] = " conv.subj as tck_subject,for_conv.lead_stage_name as tck_stage,for_conv_desc.description as tck_sub_stage,conv.msg as tck_msg";
        }

        $select = implode(',', $sel_string);

        $this->db->select($select);
        $this->db->from($this->table." tck");

        
        //->join("tbl_ticket_conv cnv", "cnv.tck_id = tck.id", "LEFT")
        if($showall or count(array_intersect(array(2,4),$acolarr))>0)
        {
            $this->db->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT");
        }
        
        if($showall or in_array(5, $acolarr))
        {
            $this->db->join("tbl_product_country prd", "prd.id = tck.product", "LEFT");
        }

        if($showall or in_array(19, $acolarr))
        {
            $this->db->join("tbl_ticket_subject", "tbl_ticket_subject.id = tck.category", "LEFT");
        }

        if($showall or in_array(6,$acolarr))
        {
            $this->db->join("tbl_admin as for_assign", "for_assign.pk_i_admin_id = tck.assign_to", "LEFT");
        }
         
        if($showall or in_array(7, $acolarr))
        {
            $this->db->join("tbl_admin as for_created", "for_created.pk_i_admin_id = tck.added_by", "LEFT");
        }
        
        if($showall or in_array(10, $acolarr))
        {
         $this->db->join("tbl_referred_by ref","tck.referred_by=ref.id","LEFT");
        }

        if($showall or in_array(11, $acolarr))
        {
         $this->db->join("lead_source source","tck.sourse=source.lsid","LEFT");
        }
       
        if($showall or in_array(12, $acolarr))
        {
         $this->db->join("lead_stage stage","tck.ticket_stage=stage.stg_id","LEFT");
        } 
        
        if($showall or in_array(13, $acolarr))
        {
         $this->db->join("lead_description sub_stage","tck.ticket_substage=sub_stage.id","LEFT");
        } 

        if($showall or in_array(16, $acolarr))
        {
         $this->db->join("tbl_ticket_status status","tck.ticket_status=status.id","LEFT");
        } 

        

        if($showall or in_array(17, $acolarr))
        {
         $this->db->join("tbl_admin assign_by","tck.assigned_by=assign_by.pk_i_admin_id","LEFT");
        } 

        if($followup)
        {
           $this->db->join("tbl_ticket_conv conv","tck.id=conv.tck_id","inner");
           $this->db->join('lead_stage for_conv','conv.stage=for_conv.stg_id','left');
           $this->db->join('lead_description for_conv_desc','conv.sub_stage=for_conv_desc.id','left');
        }

         $this->db->where("tck.company",$this->session->companey_id);

         if(!empty($this->session->process)){              
            $arr = $this->session->process;           
            if(is_array($arr)){
                $this->db->where_in("tck.process_id",$arr);
            }                       
        }

        //$this->db->group_by("tck.id");




    // if(isset($this->session->ticket_filters_sess))
    // {
        $enquiry_filters_sess   =   $this->session->ticket_filters_sess;
            
        $top_filter             =   !empty($enquiry_filters_sess['top_filter'])?$enquiry_filters_sess['top_filter']:'';


        $from_created           =   !empty($enquiry_filters_sess['from_created'])?$enquiry_filters_sess['from_created']:'';       
        
        
        $to_created             =   !empty($enquiry_filters_sess['to_created'])?$enquiry_filters_sess['to_created']:'';
        
        
        $updated_from_created           =   !empty($enquiry_filters_sess['update_from_created'])?$enquiry_filters_sess['update_from_created']:'';       
        $updated_to_created             =   !empty($enquiry_filters_sess['update_to_created'])?$enquiry_filters_sess['update_to_created']:'';
        
        $source                 =   !empty($enquiry_filters_sess['source'])?$enquiry_filters_sess['source']:'';
       
        $createdby              =   !empty($enquiry_filters_sess['createdby'])?$enquiry_filters_sess['createdby']:'';
        $assign                 =   !empty($enquiry_filters_sess['assign'])?$enquiry_filters_sess['assign']:'';
      
        $problem                 =   !empty($enquiry_filters_sess['problem'])?$enquiry_filters_sess['problem']:'';

        $priority                 =   !empty($enquiry_filters_sess['priority'])?$enquiry_filters_sess['priority']:'';

        $issue                 =   !empty($enquiry_filters_sess['issue'])?$enquiry_filters_sess['issue']:'';

        $productcntry          =   !empty($enquiry_filters_sess['prodcntry'])?$enquiry_filters_sess['prodcntry']:'';

        $stage          =   !empty($enquiry_filters_sess['stage'])?$enquiry_filters_sess['stage']:'';
        $sub_stage          =   !empty($enquiry_filters_sess['sub_stage'])?$enquiry_filters_sess['sub_stage']:'';

        $ticket_status          =   !empty($enquiry_filters_sess['ticket_status'])?$enquiry_filters_sess['ticket_status']:'';

         $assign_by          =   !empty($enquiry_filters_sess['assign_by'])?$enquiry_filters_sess['assign_by']:'';
         $ticket_type          =   !empty($enquiry_filters_sess['ticket_type'])?$enquiry_filters_sess['ticket_type']:'';


        $where='';
$CHK = 0;

         if(!empty($from_created) && !empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " (DATE(tck.coml_date) >= '".$from_created."' AND DATE(tck.coml_date) <= '".$to_created."')";
            $CHK = 1;
        }

        if(!empty($from_created) && empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $where .= " DATE(tck.coml_date) >=  '".$from_created."'"; 
            $CHK = 1;                           
        }
        
        if(empty($from_created) && !empty($to_created)){            
            
            $to_created = date("Y-m-d",strtotime($to_created));

            $where .=  " DATE(tck.coml_date) <=  '".$to_created."'"; 
            $CHK = 1;                                 
        }


        if(!empty($ticket_type)){
            if($CHK)
                $where .= ' AND ';
            $where .= " tck.complaint_type=".$ticket_type;
            $CHK = 1;          
        }




        if(!empty($updated_from_created) && !empty($updated_to_created)){
            if($CHK)
                $where .= ' AND ';
            $updated_from_created = date("Y-m-d",strtotime($updated_from_created));
            $updated_to_created = date("Y-m-d",strtotime($updated_to_created));
            $where .= " (DATE(tck_conv.send_date) >= '".$updated_from_created."' AND DATE(tck_conv.send_date) <= '".$updated_to_created."') ";
            $CHK = 1;
            $this->db->join("(select * from tbl_ticket_conv where comp_id=$comp_id AND subj!='Ticked Created') as tck_conv","tck_conv.tck_id=tck.id","LEFT");
            
        }

        if(!empty($updated_from_created) && empty($updated_to_created)){
            if($CHK)
                $where .= ' AND ';
            $updated_from_created = date("Y-m-d",strtotime($updated_from_created));
            $where .= " DATE(tck_conv.send_date) >=  '".$updated_from_created."'"; 
            $this->db->join("(select * from tbl_ticket_conv where comp_id=$comp_id AND subj!='Ticked Created') as tck_conv","tck_conv.tck_id=tck.id","LEFT");

            $CHK = 1;                           
        }
        if(empty($updated_from_created) && !empty($updated_to_created)){            
            if($CHK)
                $where .= ' AND ';
            $updated_to_created = date("Y-m-d",strtotime($updated_to_created));
            $where .= " DATE(tck_conv.send_date) <=  '".$updated_to_created."'"; 
            $this->db->join("(select * from tbl_ticket_conv where comp_id=$comp_id AND subj!='Ticked Created') as tck_conv","tck_conv.tck_id=tck.id","LEFT");
          

            $CHK = 1;                                  
        }



        if(!empty($productcntry)){            
           // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.product =  '".$productcntry."'"; 
            $CHK =1;                             
        }

        if(!empty($createdby)){            
           // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.added_by =  '".$createdby."'"; 
            $CHK =1;                             
        }

        if(!empty($assign)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.assign_to =  '".$assign."'"; 
            $CHK =1;                             
        }

        if(!empty($assign_by)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.assigned_by =  '".$assign_by."'"; 
            $CHK =1;                             
        }

        if(!empty($source)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.sourse =  '".$source."'"; 
            $CHK =1;                             
        }

        if(!empty($problem)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.category =  '".$problem."'"; 
            $CHK =1;                             
        }

        if(!empty($priority)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.priority =  '".$priority."'"; 
            $CHK =1;                             
        }

         if(!empty($issue)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.issue =  '".$issue."'"; 
            $CHK =1;                             
        }


        if(!empty($stage)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.ticket_stage =  '".$stage."'"; 
            $CHK =1;                             
        }


        if(!empty($sub_stage)){            
                   // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.ticket_substage =  '".$sub_stage."'"; 
            $CHK =1;                             
        }

        if(!empty($ticket_status)){            
                           // $to_created = date("Y-m-d",strtotime($to_created));
            if($CHK)
                $where .= ' AND ';

            $where .= " tck.ticket_status =  '".$ticket_status."'"; 
            $CHK =1;                              
        }
        $uid    =   $this->session->user_id;
        if($top_filter=='total'){            

        }elseif($top_filter=='created'){            
        }elseif($top_filter=='assigned'){             
        }elseif($top_filter=='updated'){  
         if($CHK)
                $where .= ' AND ';          
            $where.="  tck.last_update!=tck.coml_date";
            $CHK=1;
        }elseif($top_filter=='total_activity'){              
        }elseif($top_filter=='closed'){  
            if($CHK)
                $where .= ' AND ';          
            $where.="  tck.ticket_status=3";
            $CHK=1;
        }elseif($top_filter=='pending'){  
            if($CHK)
                $where .= ' AND ';          
            $where.="  tck.last_update = tck.coml_date";            
            $CHK=1;
        }

       
        
        if($top_filter=='created'){
            if($CHK)
                $where .= ' AND ';                      
            
            $where .= " tck.added_by IN (".implode(',', $all_reporting_ids).')';
            $CHK=1;
        }else if($top_filter=='assigned'){
            if($CHK)
                $where .= ' AND ';                      

            $where .= " tck.assign_to IN (".implode(',', $all_reporting_ids).')';

            $CHK=1;
        }else{
            if($CHK)
                $where .= ' AND ';  
            $where .= " ( tck.added_by IN (".implode(',', $all_reporting_ids).')';
            $CHK=1;
    
            if($CHK){
                $where .= ' OR ';
                $CHK =1;
            }
            $where .= " tck.assign_to IN (".implode(',', $all_reporting_ids).'))';
        }


        if(!empty($_POST['specific_list']))
        {//echo 'infolist'.$_POST['specific_list']; exit();
           
            $where.=" AND ( tck.id IN (".$_POST['specific_list'].") ) ";
            $and =1;
        }

        $this->db->where($where);
        //$this->db->group_by('tck.id');
    //}

        $i = 0;
        // loop searchable columns 
        foreach($this->column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($this->column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
        
        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

}
