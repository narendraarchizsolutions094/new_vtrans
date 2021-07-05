<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts_datatable_model extends CI_Model{
    
    function __construct() {
  
        $this->table = 'tbl_client_contacts';
        // Set orderable column fields
        $this->column_order = array('','enquiry.name','enquiry.company','contacts.designation','contacts.c_name','contacts.contact_number','contacts.emailid','contacts.decision_maker','contacts.other_detail','contacts.created_at');
        $this->column_order1 = array('','enquiry.name','enquiry.company','contacts.designation','contacts.c_name','contacts.contact_number','contacts.emailid','contacts.decision_maker','contacts.other_detail','contacts.created_at');
        // Set searchable column fields

        $this->column_search = array('enquiry.name','comp.company_name','contacts.designation','contacts.c_name','contacts.contact_number','contacts.emailid','contacts.other_detail');
        $this->column_search1 = array('enquiry.client_name');
        
        // Set default order
        $this->order = array('sr_no' => 'desc');
        $this->load->model('common_model');
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
	
	public function getRows_log($postData){

        $this->_get_datatables_query_log($postData);
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

        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        $this->db->from($this->table);
        $this->db->join('enquiry','enquiry.enquiry_id=tbl_client_contacts.client_id','left');
        $this->db->where("enquiry.comp_id",$this->session->companey_id);
        $where="";
        $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        // if(!empty($_POST['specific_list']))
        // {
        //     $where.="AND tbl_visit.id IN (".$_POST['specific_list'].") ";   
        // }
        $this->db->where($where);
        return $this->db->count_all_results();
    }
	
	public function countAll_log(){

        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        $this->db->from('tbl_comment');
        $this->db->join('enquiry','enquiry.Enquery_id=tbl_comment.lead_id','left');
        $this->db->where("enquiry.comp_id",$this->session->companey_id);
        $where="";
        $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        // if(!empty($_POST['specific_list']))
        // {
        //     $where.="AND tbl_visit.id IN (".$_POST['specific_list'].") ";   
        // }
        $this->db->where($where);
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
	
	public function countFiltered_log($postData){
        $this->_get_datatables_query_log($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    public function _get_datatables_query($postData){

        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        // $this->db->select($this->table.'.*,enquiry.name,enquiry.status as enq_type,enquiry.Enquery_id');
        // $this->db->from($this->table);
        // $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
        // $this->db->where("tbl_visit.comp_id",$this->session->companey_id);

        // $where="";
        // $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        // $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  


$where1='';
        $where = 'enquiry.comp_id='.$this->session->companey_id;
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $where1 .= " ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where1 .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';          
        // if($where)
        //     $this->db->where($where);

		/* $this->db->select('company');
		$this->db->from('enquiry');
		$this->db->where($where1);
		$res = $this->db->get()->result();
$id_array=array();
foreach($res as $val){
	$id_array[] = $val->company;
} */

        $this->db->select('contacts.*,enquiry.company,comp.company_name,enquiry.enquiry_id,concat_ws(" ",name_prefix,name,lastname) as enq_name,enquiry.status,comp.company_name,desg.desi_name,enquiry.client_name');
        $this->db->from('tbl_client_contacts contacts');
        $this->db->join('enquiry','enquiry.enquiry_id=contacts.client_id','inner');
        $this->db->join('tbl_company comp','comp.id=enquiry.company','left');
        $this->db->join('tbl_designation desg','desg.id=contacts.designation','left');
        // $this->db->order_by('contacts.cc_id desc');
        // return $this->db->get();
		
       // $this->db->where_in('enquiry.company',array_unique($id_array));

        $and =1;
        // if(!empty($_POST['from_date']))
        // {
        //     $where.="and visit_date >= '".$_POST['from_date']."'";
        //     $and =1;
        // }

        // if(!empty($_POST['to_date']))
        // {   
        //     if($and)
        //         $where.=" and ";

        //     $where.=" visit_date <= '".$_POST['to_date']."'";
        //     $and =1;
        // }

        // if(!empty($_POST['from_time']))
        // {   
        //     if($and)
        //         $where.=" and ";

        //     $where.=" visit_time >= '".$_POST['from_time']."'";
        //     $and =1;
        // }

        // if(!empty($_POST['to_time']))
        // {   
        //     if($and)
        //         $where.=" and ";

        //     $where.=" visit_time <= '".$_POST['to_time']."'";
        //     $and =1;
        // }


        if(!empty($_POST['enquiry_id']))
        {   
            if($and)
                $where.=" and ";

            $where.=" contacts.client_id = '".$_POST['enquiry_id']."'";
            $and =1;
        }

        // if(!empty($_POST['rating']))
        // {   
        //     if($and)
        //         $where.=" and ";

        //     $where.=" tbl_visit.rating LIKE '%".$_POST['rating']."%'";
        //     $and =1;
        // }

        if(!empty($_POST['specific_list']))
        {
            if($and)
                $where.=" and ";

            $where.=" ( contacts.cc_id IN (".$_POST['specific_list'].") ) ";
            $and =1;
        }

        if($where!='')
        $this->db->where($where);
 
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
	
	public function _get_datatables_query_log($postData){

        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

$where1='';
$log_filters_sess   =   $this->session->log_filters_sess;
        $top_filter             =   !empty($log_filters_sess['top_filter'])?$log_filters_sess['top_filter']:'';        
        $from_created           =   !empty($log_filters_sess['from_created'])?$log_filters_sess['from_created']:'';       
        $to_created             =   !empty($log_filters_sess['to_created'])?$log_filters_sess['to_created']:'';
        $createdby              =   !empty($log_filters_sess['createdby'])?$log_filters_sess['createdby']:'';
        $calltype               =   !empty($log_filters_sess['calltype'])?$log_filters_sess['calltype']:'';
        $clientname             =   !empty($log_filters_sess['clientname'])?$log_filters_sess['clientname']:'';
		$callstatus             =   !empty($log_filters_sess['callstatus'])?$log_filters_sess['callstatus']:'';
//print_r($clientname);exit;
        $where = 'enquiry.comp_id='.$this->session->companey_id;
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $where1 .= " ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where1 .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';          


        $this->db->select('enquiry.lead_discription_reamrk,lead_stage.lead_stage_name,lead_description.description,enquiry.created_date as tag_date,concat_ws(" ",tbl_admin.s_display_name,tbl_admin.last_name) as create_name,enquiry.status,tbl_comment.call_timestamp as created_date,tbl_comment.comm_id,enquiry.enquiry_id,enquiry.phone,tbl_comment.comment_msg,tbl_comment.remark,comp.company_name,concat_ws(" ",enquiry.name_prefix,enquiry.name,enquiry.lastname) as enq_name,enquiry.client_name');
        $this->db->from('tbl_comment');
        $this->db->join('enquiry','enquiry.Enquery_id=tbl_comment.lead_id','left');
		$this->db->join('lead_stage','lead_stage.stg_id=enquiry.lead_stage','left');
		$this->db->join('lead_description','lead_description.id=enquiry.lead_discription','left');
		$this->db->join('tbl_company comp','comp.id=enquiry.company','left');
		$this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_comment.created_by','left');
        $where.=" and ";
        $where.="tbl_comment.coment_type = '5'";
        if($where!='')
			
		if($top_filter=='all')
        {
            $where.=" AND tbl_comment.coment_type = '5'";
        }
        else if($top_filter=='new')
        {            
            $today = date('Y-m-d');          
            $where .= " AND enquiry.created_date LIKE  '%$today%'";
			
        }else if($top_filter=='existing'){
			
            $today = date('Y-m-d');          
            $where .= " AND enquiry.created_date NOT LIKE  '%$today%'";
			
        }else if($top_filter=='incoming'){
			$calltype = 'incoming';
            $where .= " AND tbl_comment.comment_msg LIKE  '%$calltype%'";
			
        }else if($top_filter=='outgoing'){
            
            $calltype = 'outgoing';
            $where .= " AND tbl_comment.comment_msg LIKE  '%$calltype%'";
			
        }
		
		$log_date_fld = 'created_date';
		if(!empty($from_created) && !empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(tbl_comment.".$log_date_fld.") >= '".$from_created."' AND DATE(tbl_comment.".$log_date_fld.") <= '".$to_created."'";
        }
        if(!empty($from_created) && empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $where .= " AND DATE(tbl_comment.".$log_date_fld.") >=  '".$from_created."'";                        
        }
        if(empty($from_created) && !empty($to_created)){            
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(tbl_comment.".$log_date_fld.") <=  '".$to_created."'";                                    
        }
		if(!empty($createdby)){            
           
            $where .= " AND tbl_comment.created_by =  '".$createdby."'";                                    
        }
		if(!empty($calltype)){            
           
            $where .= " AND tbl_comment.comment_msg LIKE  '%$calltype%'";                                    
        }
		if(!empty($clientname)){            
           
            $where .= " AND enquiry.client_name LIKE  '%$clientname%'";                                    
        }
		if(!empty($callstatus==1)){
            $today = date('Y-m-d');          
            $where .= " AND enquiry.created_date LIKE  '%$today%'";                                    
        }else if(!empty($callstatus==2)){
			$today = date('Y-m-d');          
            $where .= " AND enquiry.created_date NOT LIKE  '%$today%'";
		}
        $this->db->where($where);
 
        $i = 0;
        // loop searchable columns 
        foreach($this->column_search1 as $item){
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
                if(count($this->column_search1) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
        $this->db->order_by('tag_date', "desc");
        if(isset($postData['order'])){
            $this->db->order_by($this->column_order1[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

}