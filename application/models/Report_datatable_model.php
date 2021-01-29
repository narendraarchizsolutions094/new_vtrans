<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_datatable_model extends CI_Model {    
    public function __construct(){
        parent::__construct();
        $this->load->model('common_model');		
    }
    var $table = 'enquiry'; 
    public function _get_datatables_query(){
        $employe = $this->session->userdata('employe1');
        if ($this->session->hier_wise && $employe) {
           $uid = $employe[0];
           $employe    =    $this->common_model->get_categories($uid);                  
        }else{
    	   $all_reporting_ids    =    $this->common_model->get_categories($this->session->user_id);      
        }
        $from = $this->session->userdata('from1');
        $to= $this->session->userdata('to1');
        
        $updated_from = $this->session->userdata('updated_from1');
        $updated_to = $this->session->userdata('updated_to1');

        $phone = $this->session->userdata('phone1');
        $country = $this->session->userdata('country1');
        $institute = $this->session->userdata('institute1');
        $center = $this->session->userdata('center1');
        $source = $this->session->userdata('source1');
        $subsource = $this->session->userdata('subsource1');
        $datasource = $this->session->userdata('datasource1');
        $state = $this->session->userdata('state1');
        $lead_source = $this->session->userdata('lead_source1');
        
        $sub_disposition = $this->session->userdata('sub_disposition');

        $lead_subsource = $this->session->userdata('lead_subsource1');
        $enq_product = $this->session->userdata('enq_product1');
        $drop_status = $this->session->userdata('drop_status1');
        $productlst = $this->session->userdata('productlst');
        $all = $this->session->userdata('all1');
        $companey_id= $this->session->userdata('companey_id');  
        if($all){
            $select = 'enquiry.enquiry_id,enquiry.Enquery_id,enquiry.name_prefix,enquiry.name,enquiry.lastname,enquiry.phone,enquiry.update_date,enquiry.email,enquiry.gender,enquiry.enquiry as enq_remark,tbl_drop.drop_reason as drop_status,enquiry.drop_reason,enquiry.status,lead_source.lead_name,tbl_subsource.subsource_name,lead_description.description,enquiry.lead_discription_reamrk,lead_score.score_name as lead_score,enquiry.status as inq_status,enquiry.created_date as inq_created_date, CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,CONCAT(admin2.s_display_name,admin2.last_name) as assign_to_name,tbl_product.product_name,lead_stage2.lead_stage_name as followup_name,tbl_product_country.country_name as enq_product_name,state.state as state_name,city.city as city_name';
            }else{
                $select = 'enquiry.enquiry_id,enquiry.Enquery_id,enquiry.name_prefix,enquiry.name,enquiry.lastname,enquiry.phone,enquiry.update_date,enquiry.email,enquiry.gender,enquiry.enquiry as enq_remark,tbl_drop.drop_reason as drop_status,enquiry.drop_reason,enquiry.status,lead_source.lead_name,tbl_subsource.subsource_name,lead_description.description,lead_score.score_name as lead_score,enquiry.lead_discription_reamrk,lead_stage.lead_stage_name as followup_name,enquiry.status,enquiry.created_date as inq_created_date,tbl_product.product_name, CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,CONCAT(admin2.s_display_name,admin2.last_name) as assign_to_name,tbl_product_country.country_name as enq_product_name,state.state as state_name,city.city as city_name';
            }
            $select .= ',enquiry.company';
            $this->db->select($select);                    
            $where = "enquiry.enquiry_id > 0";      
            if ($from && $to) {
                $to = str_replace('/', '-', $to);
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $to = date('Y-m-d', strtotime($to));            
                $where .= " AND Date(enquiry.created_date) >= '$from' AND Date(enquiry.created_date) <= '$to'";
            } else if ($from && !$to) {
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $where .= " AND Date(enquiry.created_date) LIKE '%$from%'";
            } else if (!$from && $to) {            
                $to = str_replace('/', '-', $to);
                $to = date('Y-m-d', strtotime($to));
                $where .= " AND Date(enquiry.created_date) LIKE '%$to%'";
            }            

           if($employe!=''){	            		
    			$where .= " AND ( enquiry.created_by IN (".implode(',', $employe).')';
    			$where .= " OR enquiry.aasign_to IN (".implode(',', $employe).'))';  		  
            }else{
    			$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    			$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
    		}    
            if($source!=''){
               $where .= " AND enquiry.enquiry_source IN (".implode(',', $source).')';  
            }
            if($subsource!=''){
               $where .= " AND enquiry.sub_source IN (".implode(',', $subsource).')';
            }
            if($datasource!=''){
               $where .= " AND enquiry.datasource_id IN (".implode(',', $datasource).')';  
            }
            if($state!=''){
               $where .= " AND enquiry.status IN (".implode(',', $state).')';  
            }
            if ($lead_source != '') {                
                $where .= " AND enquiry.lead_stage IN (".implode(',', $lead_source).')';
            }
            if ($sub_disposition != '') {                
                $where .= " AND enquiry.lead_discription IN (".implode(',', $sub_disposition).')';
            }
            if($productlst!=''){
               $where .= " AND enquiry.enquiry_subsource IN (".implode(',', $productlst).')';  
            }
            if($enq_product!=''){
               $where .= " AND enquiry.product_id IN (".implode(',', $enq_product).')';  
            }
            if($all!='' || $updated_from || $updated_to){
                if ($updated_from && $updated_to) {
                    $updated_to = str_replace('/', '-', $updated_to);
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $updated_to = date('Y-m-d', strtotime($updated_to));            
                    $where .= " AND Date(tbl_comment.created_date) >= '$updated_from' AND Date(tbl_comment.created_date) <= '$updated_to'";
                } else if ($updated_from && !$updated_to) {
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $where .= " AND Date(tbl_comment.created_date) LIKE '%$updated_from%'";
                } else if (!$updated_from && $updated_to) {            
                    $updated_to = str_replace('/', '-', $updated_to);           
                     $updated_to = date('Y-m-d', strtotime($updated_to));
                    $where .= " AND Date(tbl_comment.created_date) LIKE '%$updated_to%'";
                }                
                $this->db->join('tbl_comment','tbl_comment.lead_id=enquiry.Enquery_id','inner');              
                if($all!=''){
                    $where.= "AND comment_msg='Stage Updated'";
                    $this->db->join('lead_stage as lead_stage2','lead_stage2.stg_id=tbl_comment.stage_id','left'); 
                }
            }
            if($drop_status!=''){            
                if(!empty($drop_status[0])){
                    if($drop_status[0]=='dropped'){
                        $where .= " AND enquiry.drop_status>0 ";
                    }
                }
                if(!empty($drop_status[1])){
                    if($drop_status[1]=='active'){
                        $where .= " AND enquiry.status=1";
                    }
                }
                if(!empty($drop_status[0])=='dropped' && !empty($drop_status[1])=='active'){
                    $where .= " AND enquiry.drop_status>0 AND enquiry.status=1";
                }
            }
            if($this->session->companey_id==''){
                $comp_id=$companey_id;
            }else{
                $comp_id=$this->session->companey_id;

            }   
            
            $this->db->join('tbl_product_country','tbl_product_country.id=enquiry.enquiry_subsource','left');   
            $this->db->join('lead_source','lead_source.lsid=enquiry.enquiry_source','left');            
            $this->db->join('tbl_product','tbl_product.sb_id=enquiry.product_id','left');   
            $this->db->join("(select * from tbl_subsource where comp_id=$comp_id) as tbl_subsource",'tbl_subsource.subsource_id=enquiry.sub_source','left');        
            
            $this->db->join('tbl_datasource','tbl_datasource.datasource_id=enquiry.datasource_id','left');
            $this->db->join('state','state.id=enquiry.state_id','left');
            $this->db->join('city','city.id=enquiry.city_id','left');
            $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left');
            $this->db->join('tbl_admin as admin2','admin2.pk_i_admin_id=enquiry.aasign_to','left');      
            $this->db->join('lead_stage','lead_stage.stg_id=enquiry.lead_stage','left');        
            $this->db->join('lead_description','lead_description.id=enquiry.lead_discription','left');
            $this->db->join('tbl_drop','tbl_drop.d_id=enquiry.drop_status','left');
            $this->db->join('lead_score','lead_score.sc_id=enquiry.lead_score','left');
            $this->db->where($where);        
            if(!$all){
                $this->db->group_by('enquiry.enquiry_id');        
            }else{
                $this->db->order_by('enquiry.enquiry_id,tbl_comment.created_date','DESC');                    
            }        
    }
    function get_datatables(){   	        
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get($this->table);
        return $query->result();
    }
    function count_filtered(){
        $this->_get_datatables_query();
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function count_all(){
        $employe = $this->session->userdata('employe1');
        if ($this->session->hier_wise && $employe) {
           $uid = $employe[0];
           $all_reporting_ids    =    $this->common_model->get_categories($uid);                  
        }else{
    	   $all_reporting_ids    =    $this->common_model->get_categories($this->session->user_id);      
        }
        //$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $this->db->from($this->table);        
        $where = "";                        
        $where .= " (enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';          
        $this->db->where($where);
        return $this->db->count_all_results();
    }


    function report_analitics($for){
        $employe = $this->session->userdata('employe1');
        $user_id = $this->session->userdata('user_id');

        if ($this->session->hier_wise && $employe) {
           $uid = $employe[0];
           $employe    =    $this->common_model->get_categories($uid);                  
        }else{
           if($this->session->user_id==''){  $user_id=$user_id;  }else{  $user_id=$this->session->user_id;  }  
    	   $all_reporting_ids    =    $this->common_model->get_categories($user_id);      
        }
        $from = $this->session->userdata('from1');
        $to= $this->session->userdata('to1');
        
        $updated_from = $this->session->userdata('updated_from1');
        $updated_to = $this->session->userdata('updated_to1');

        $phone = $this->session->userdata('phone1');
        $country = $this->session->userdata('country1');
        $institute = $this->session->userdata('institute1');
        $center = $this->session->userdata('center1');
        $source = $this->session->userdata('source1');
        $subsource = $this->session->userdata('subsource1');
        $datasource = $this->session->userdata('datasource1');
        $state = $this->session->userdata('state1');
        $lead_source = $this->session->userdata('lead_source1');
        
        $sub_disposition = $this->session->userdata('sub_disposition');

        $lead_subsource = $this->session->userdata('lead_subsource1');
        $enq_product = $this->session->userdata('enq_product1');
        $drop_status = $this->session->userdata('drop_status1');
        $productlst = $this->session->userdata('productlst');
        $companey_id = $this->session->userdata('companey_id');
        $group_by = '';
        $from_table    =   'enquiry';
        if($for == 'source_chart'){
            //$select = 'enquiry.enquiry_id,enquiry.Enquery_id,enquiry.name_prefix,enquiry.name,enquiry.lastname,enquiry.phone,enquiry.update_date,enquiry.email,enquiry.gender,enquiry.enquiry as enq_remark,tbl_drop.drop_reason as drop_status,enquiry.drop_reason,enquiry.status,lead_source.lead_name,tbl_subsource.subsource_name,lead_description.description,lead_score.score_name as lead_score,enquiry.lead_discription_reamrk,lead_stage.lead_stage_name as followup_name,enquiry.status,enquiry.created_date as inq_created_date,tbl_product.product_name, CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,CONCAT(admin2.s_display_name,admin2.last_name) as assign_to_name,tbl_product_country.country_name as enq_product_name,state.state as state_name,city.city as city_name';
            
            $select = 'count(enquiry.enquiry_id) as count,lead_source.lead_name as title';
            $group_by = 'enquiry.enquiry_source';
            //$from_table = 'lead_source';

        }else if($for == 'process_chart'){
            $select = 'count(enquiry.enquiry_id) as count,tbl_product.product_name as title';
            $group_by = 'enquiry.product_id';
            //$from_table = 'lead_source';
        }else if($for == 'stage_chart'){
            $select = 'count(enquiry.enquiry_id) as count,lead_stage.lead_stage_name as title';
            $group_by = 'enquiry.lead_stage';
        }else if($for == 'user_chart'){
            $select = 'count(enquiry.enquiry_id) as count,CONCAT(admin2.s_display_name,admin2.last_name) as title';
            $group_by = 'enquiry.aasign_to';
        }else if($for == 'product_chart'){
            $select = 'count(enquiry.enquiry_id) as count,tbl_product_country.country_name as title';
            $group_by = 'enquiry.enquiry_subsource';
        }else if($for == 'status_chart'){
            $select = 'count(enquiry.enquiry_id) as count,enquiry.status as title';
            $group_by = 'enquiry.status';
        }else{
            $select = 'count(enquiry.enquiry_id) as count,lead_source.lead_name as title';
            $group_by = 'enquiry.enquiry_source';
        }            
            $this->db->select($select);                    
            $where = "enquiry.enquiry_id > 0";      
            if ($from && $to) {
                $to = str_replace('/', '-', $to);
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $to = date('Y-m-d', strtotime($to));            
                $where .= " AND Date(enquiry.created_date) >= '$from' AND Date(enquiry.created_date) <= '$to'";
            } else if ($from && !$to) {
                $from = str_replace('/', '-', $from);            
                $from = date('Y-m-d', strtotime($from));
                $where .= " AND Date(enquiry.created_date) LIKE '%$from%'";
            } else if (!$from && $to) {            
                $to = str_replace('/', '-', $to);
                $to = date('Y-m-d', strtotime($to));
                $where .= " AND Date(enquiry.created_date) LIKE '%$to%'";
            }            

           if($employe!=''){	            		
    			$where .= " AND ( enquiry.created_by IN (".implode(',', $employe).')';
    			$where .= " OR enquiry.aasign_to IN (".implode(',', $employe).'))';  		  
            }else{
    			$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    			$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
    		}    
            if($source!=''){
               $where .= " AND enquiry.enquiry_source IN (".implode(',', $source).')';  
            }
            if($subsource!=''){
               $where .= " AND enquiry.sub_source IN (".implode(',', $subsource).')';
            }
            if($datasource!=''){
               $where .= " AND enquiry.datasource_id IN (".implode(',', $datasource).')';  
            }
            if($state!=''){
               $where .= " AND enquiry.status IN (".implode(',', $state).')';  
            }
            if ($lead_source != '') {                
                $where .= " AND enquiry.lead_stage IN (".implode(',', $lead_source).')';
            }
            if ($sub_disposition != '') {                
                $where .= " AND enquiry.lead_discription IN (".implode(',', $sub_disposition).')';
            }
            if($productlst!=''){
               $where .= " AND enquiry.enquiry_subsource IN (".implode(',', $productlst).')';  
            }
            if($enq_product!=''){
               $where .= " AND enquiry.product_id IN (".implode(',', $enq_product).')';  
            }
            if($updated_from || $updated_to){
                if ($updated_from && $updated_to) {
                    $updated_to = str_replace('/', '-', $updated_to);
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $updated_to = date('Y-m-d', strtotime($updated_to));            
                    $where .= " AND Date(tbl_comment.created_date) >= '$updated_from' AND Date(tbl_comment.created_date) <= '$updated_to'";
                } else if ($updated_from && !$updated_to) {
                    $updated_from = str_replace('/', '-', $updated_from);            
                    $updated_from = date('Y-m-d', strtotime($updated_from));
                    $where .= " AND Date(tbl_comment.created_date) LIKE '%$updated_from%'";
                } else if (!$updated_from && $updated_to) {            
                    $updated_to = str_replace('/', '-', $updated_to);           
                     $updated_to = date('Y-m-d', strtotime($updated_to));
                    $where .= " AND Date(tbl_comment.created_date) LIKE '%$updated_to%'";
                }                
                $this->db->join('tbl_comment','tbl_comment.lead_id=enquiry.Enquery_id','inner');              
                if($all!=''){
                    $where.= "AND comment_msg='Stage Updated'";
                    $this->db->join('lead_stage as lead_stage2','lead_stage2.stg_id=tbl_comment.stage_id','left'); 
                }
            }
            if($drop_status!=''){            
                if(!empty($drop_status[0])){
                    if($drop_status[0]=='dropped'){
                        $where .= " AND enquiry.drop_status>0 ";
                    }
                }
                if(!empty($drop_status[1])){
                    if($drop_status[1]=='active'){
                        $where .= " AND enquiry.status=1";
                    }
                }
                if(!empty($drop_status[0])=='dropped' && !empty($drop_status[1])=='active'){
                    $where .= " AND enquiry.drop_status>0 AND enquiry.status=1";
                }
            }
             if($this->session->companey_id==''){
                $comp_id=$companey_id;
            }else{
                $comp_id=$this->session->companey_id;

            }           
            $this->db->join('tbl_product_country','tbl_product_country.id=enquiry.enquiry_subsource','left');   
            $this->db->join('lead_source','lead_source.lsid=enquiry.enquiry_source','left');            
            $this->db->join('tbl_product','tbl_product.sb_id=enquiry.product_id','left');   
            $this->db->join("(select * from tbl_subsource where comp_id=$comp_id) 
            as tbl_subsource",'tbl_subsource.subsource_id=enquiry.sub_source','left');        
            
            $this->db->join('tbl_datasource','tbl_datasource.datasource_id=enquiry.datasource_id','left');
            $this->db->join('state','state.id=enquiry.state_id','left');
            $this->db->join('city','city.id=enquiry.city_id','left');
            $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left');
            $this->db->join('tbl_admin as admin2','admin2.pk_i_admin_id=enquiry.aasign_to','left');      
            $this->db->join('lead_stage','lead_stage.stg_id=enquiry.lead_stage','left');        
            $this->db->join('lead_description','lead_description.id=enquiry.lead_discription','left');
            $this->db->join('tbl_drop','tbl_drop.d_id=enquiry.drop_status','left');
            $this->db->join('lead_score','lead_score.sc_id=enquiry.lead_score','left');
            $this->db->where($where);    
            
            if(!empty($group_by)){
                $this->db->group_by($group_by);
            }                           
            $result    =   $this->db->get($from_table)->result_array();
            $res = array();
            if(!empty($result)){
                foreach($result as $key=>$value){
                    $title =  $value['title']??'NA';
                    $res[] = array($title,(int)$value['count']);
                }
            }
            return $res;
    }
    
} 