<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_datatable_model extends CI_Model{
    
    function __construct() {
        // Set table name

        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["feedback_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["feedback_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["feedback_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["feedback_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       

       $search_string = array();

        if($showall or in_array(1,$acolarr))
        {
            $search_string[] = "ftl_feedback.tracking_no";
        }
        if($showall or in_array(2,$acolarr))
        {
            $search_string[] = "ftl_feedback.name";
        }
        if($showall or in_array(3,$acolarr))
        {
            $search_string[] = "ftl_feedback.phone";
        }
        if($showall or in_array(4,$acolarr))
        {
            $search_string[] = "ftl_feedback.email";
        }
        if($showall or in_array(5,$acolarr))
        {
            $search_string[] = "ftl_feedback.gc_date";
        }
        if($showall or in_array(6,$acolarr))
        {
            $search_string[] = "branch.branch_name";
        }
        if($showall or in_array(7,$acolarr))
        {
            $search_string[] = "sales_region.name";
        }
        if($showall or in_array(8,$acolarr))
        {
            $search_string[] = "dbranch.branch_name";
        }
        if($showall or in_array(9,$acolarr))
        {
            $search_string[] = "ftl_feedback.dly_type";
        }
        if($showall or in_array(10,$acolarr))
        {
            $search_string[] = "ftl_feedback.pay_mode";
        }
        if($showall or in_array(11,$acolarr))
        {
            $search_string[] = "ftl_feedback.charged_weight";    
        }
    
        if($showall or in_array(12,$acolarr))
        {
            $search_string[] = "ftl_feedback.no_of_articles";    
        }

        if($showall or in_array(13,$acolarr))
        {
            $search_string[] = " ftl_feedback.actual_weight ";    
        }

        if($showall or in_array(14,$acolarr))
        {
            $search_string[] = " ftl_feedback.consignor_name ";    
        }
		if($showall or in_array(15,$acolarr))
        {
            $search_string[] = " ftl_feedback.consignor_tel_no ";    
        }
		if($showall or in_array(16,$acolarr))
        {
            $search_string[] = " ftl_feedback.consignor_mobile_no ";    
        }
		if($showall or in_array(17,$acolarr))
        {
            $search_string[] = " ftl_feedback.consignee_name ";    
        }
		if($showall or in_array(18,$acolarr))
        {
            $search_string[] = " ftl_feedback.consignee_tel_no ";    
        }
		if($showall or in_array(19,$acolarr))
        {
            $search_string[] = " ftl_feedback.consignee_mobile_no ";    
        }
		if($showall or in_array(20,$acolarr))
        {
            $search_string[] = " ftl_feedback.current_status ";    
        }
		if($showall or in_array(21,$acolarr))
        {
            $search_string[] = " ftl_feedback.vehicle_no ";    
        }
		if($showall or in_array(22,$acolarr))
        {
            $search_string[] = " ftl_feedback.added_by ";    
        }
        $this->table = 'ftl_feedback';
        $this->column_order = array('', 'ftl_feedback.fdbk_id','ftl_feedback.tracking_no','ftl_feedback.name','ftl_feedback.phone','ftl_feedback.email','ftl_feedback.gc_date','ftl_feedback.bkg_branch','ftl_feedback.bkg_region','ftl_feedback.delivery_branch','ftl_feedback.dly_type','ftl_feedback.pay_mode','ftl_feedback.charged_weight','ftl_feedback.no_of_articles','ftl_feedback.actual_weight','ftl_feedback.consignor_name','ftl_feedback.consignor_tel_no','ftl_feedback.consignor_mobile_no','ftl_feedback.consignee_name','ftl_feedback.consignee_tel_no','ftl_feedback.consignee_mobile_no','ftl_feedback.current_status','ftl_feedback.vehicle_no','ftl_feedback.added_by');
        $this->column_search = $search_string;
        $this->order = array('ftl_feedback.fdbk_id' => 'desc');
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
           
            $where="(fdbk_id IN (".$_POST['specific_list'].") ) ";
            $this->db->where($where);
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
     */
    public function _get_datatables_query($postData){
        $this->load->model('common_model');
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $comp_id = $this->session->companey_id;
        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["feedback_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["feedback_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["feedback_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["feedback_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       

        $this->db->select('ftl_feedback.*,branch.branch_name,dbranch.branch_name as delbrcnh,tbl_admin.s_display_name,tbl_admin.last_name,sales_region.name as region,feedback_tab.cust_feed');
        $this->db->from($this->table);
        $this->db->join("branch", "branch.branch_id = ftl_feedback.bkg_branch", "LEFT");
		$this->db->join("branch as dbranch", "dbranch.branch_id = ftl_feedback.delivery_branch", "LEFT");
		$this->db->join("sales_region", "sales_region.region_id = ftl_feedback.bkg_region", "LEFT");
        $this->db->join("tbl_admin", "tbl_admin.pk_i_admin_id = ftl_feedback.added_by", "LEFT");
		$this->db->join("feedback_tab", "feedback_tab.gc_no = ftl_feedback.tracking_no", "LEFT");

        $enquiry_filters_sess   =   $this->session->feedback_filters_sess;
            
        $top_filter             =   !empty($enquiry_filters_sess['top_filter'])?$enquiry_filters_sess['top_filter']:'';
        $from_created           =   !empty($enquiry_filters_sess['from_created'])?$enquiry_filters_sess['from_created']:'';       
        $to_created             =   !empty($enquiry_filters_sess['to_created'])?$enquiry_filters_sess['to_created']:'';		
		$createdby              =   !empty($enquiry_filters_sess['createdby'])?$enquiry_filters_sess['createdby']:'';
        $assign                 =   !empty($enquiry_filters_sess['assign'])?$enquiry_filters_sess['assign']:'';
		$feed_status            =   !empty($enquiry_filters_sess['ticket_status'])?$enquiry_filters_sess['ticket_status']:'';
        $assign_by              =   !empty($enquiry_filters_sess['assign_by'])?$enquiry_filters_sess['assign_by']:'';
		$cust_problam           =   !empty($enquiry_filters_sess['cust_problam'])?$enquiry_filters_sess['cust_problam']:'';
//print_r($cust_problam);exit;
         $where = " ftl_feedback.company =  '".$this->session->companey_id."'";
		 $where .= " AND (ftl_feedback.added_by IN (".implode(',', $all_reporting_ids).')';
         $where .= " OR ftl_feedback.assign_to IN (".implode(',', $all_reporting_ids).'))';
        if(!empty($from_created) && !empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(ftl_feedback.created_date) >= '".$from_created."' AND DATE(ftl_feedback.created_date) <= '".$to_created."'";
        }
        if(!empty($from_created) && empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $where .= " AND DATE(ftl_feedback.created_date) >=  '".$from_created."'";                        
        }
        if(empty($from_created) && !empty($to_created)){            
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(ftl_feedback.created_date) <=  '".$to_created."'";                                    
        }
		
		if(!empty($createdby)){            
            $where .= " AND ftl_feedback.added_by =  '".$createdby."'";                              
        }

        if(!empty($assign)){            

            $where .= " AND ftl_feedback.assign_to =  '".$assign."'";                             
        }

        if(!empty($assign_by)){            
		
            $where .= " AND ftl_feedback.assigned_by =  '".$assign_by."'"; 
                            
        }
		
		if(!empty($feed_status)){            

            $where .= " AND ftl_feedback.current_status =  '".$feed_status."'"; 
			
        }
		
		if(!empty($cust_problam)){            

            $where .= " AND feedback_tab.cust_feed =  '".$cust_problam."'"; 
			
        }

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

}
