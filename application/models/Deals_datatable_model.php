<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deals_datatable_model extends CI_Model{
    
    function __construct() {
  
        $this->table = 'commercial_info';
        // Set orderable column fields
        $this->column_order = array('info.id','enq.name','info.booking_type','info.business_type','info.creation_date','info.status','');

        // Set searchable column fields

        $this->column_search = array('enq.name','info.creation_date');

        // $this->column_search = array('tck.ticketno','tck.id','tck.category','tck.name','tck.email','tck.product','tck.message','tck.issue','tck.solution','tck.sourse','tck.ticket_stage','tck.review','tck.status','tck.priority','tck.complaint_type','tck.coml_date','tck.last_update','tck.send_date','tck.client','tck.assign_to','tck.company','tck.added_by','enq.phone','enq.gender','prd.country_name');
        
        // Set default order
        $this->order = array('info.id' => 'DESC');

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
     
    /*
     * Count all records
     */
    public function countAll(){
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $this->db->from($this->table.' info');
        $this->db->join('enquiry enq','enq.enquiry_id=info.enquiry_id','left');
        $this->db->where("enq.comp_id",$this->session->companey_id);
        $where="info.original=1 AND ";
        $where .= "( enq.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 

        if(!empty($_POST['specific_list']))
        {//echo 'infolist'.$_POST['specific_list']; exit();
           
            $where.="AND ( info.id IN (".$_POST['specific_list'].") ) ";
            $and =1;
        }
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
    
    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    public function _get_datatables_query($postData){

          /* $this->db->select('status');
          $this->db->from('enquiry');
          $this->db->where('enquiry_id',$_POST['enq_for']);
		  $this->db->where('stage_id',$_POST['curr_stg']);
        $current_stage = $this->db->get()->row(); */
//print_r($_POST['curr_stg']);exit;
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        $this->db->select('info.*,enq.name,sales_region.name as region_name,enq.Enquery_id,enq.status as enq_type,enq.client_name,comp.company_name,(SELECT SUM(expected_amount) FROM deal_data WHERE deal_data.deal_id = info.id) as qotation_amt,CONCAT(tbl_admin.s_display_name, tbl_admin.last_name) as cre_to,');
        $this->db->from($this->table.' info');
        $this->db->join('enquiry enq','enq.enquiry_id=info.enquiry_id','left');
        $this->db->join('tbl_company comp','enq.company=comp.id','left');
		$this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=info.createdby','left');
		$this->db->join('sales_region','sales_region.region_id=tbl_admin.sales_region','left');
        $this->db->where("info.comp_id",$this->session->companey_id);

       // $where="info.original=1 AND";
	    $where="(info.original=1 OR info.original=0) AND";
        if(!empty($this->input->post('emp_filter'))){
            $filter_emp = $this->input->post('emp_filter');
            $where .= "( enq.created_by = $filter_emp";
            $where .= " OR enq.aasign_to = $filter_emp)";   
        }else{
            $where .= "( enq.created_by IN (".implode(',', $all_reporting_ids).')';
            $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))';   
        }
        $and =1;
		
		if(!empty($_POST['curr_stg']))
        {   
            if($and)
                $where.=" and ";

            $where.=" (info.stage_id ='".$_POST['curr_stg']."' ) ";
            $and =1;
        }
      
        if(!empty($_POST['date_from']) && !empty($_POST['date_to']))
        {   
            if($and)
                $where.=" and ";

            $where.=" (info.creation_date >='".$_POST['date_from']."' and info.creation_date <='".$_POST['date_to']."' ) ";
            $and =1;
        }
        else if(!empty($_POST['date_from']))
        {
             if($and)
                $where.=" and ";

            $where.=" (info.creation_date >='".$_POST['date_from']."' ) ";
            $and =1;
        }
        else if(!empty($_POST['date_to']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.creation_date <='".$_POST['date_to']."' ) ";
            $and =1;
        }

        if(!empty($_POST['enq_for']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.enquiry_id ='".$_POST['enq_for']."' ) ";
            $and =1;
        }

        if(!empty($_POST['company']))
        {
              if($and)
                $where.=" and ";

            $where.=" (enq.company ='".$_POST['company']."' ) ";
            $and =1;
        }

        if(!empty($_POST['booking_type']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.booking_type ='".$_POST['booking_type']."' ) ";
            $and =1;
        }
		
		if(!empty($_POST['region_type']))
        {
              if($and)
                $where.=" and ";

            $where.=" (tbl_admin.sales_region ='".$_POST['region_type']."' ) ";
            $and =1;
        }

        if(!empty($_POST['booking_branch']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.booking_branch ='".$_POST['booking_branch']."' ) ";
            $and =1;
        }

        if(!empty($_POST['delivery_branch']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.delivery_branch ='".$_POST['delivery_branch']."' ) ";
            $and =1;
        }

        // if(!empty($_POST['paymode']))
        // {
        //       if($and)
        //         $where.=" and ";

        //     $where.=" (info.paymode ='".$_POST['paymode']."' ) ";
        //     $and =1;
        // }

        if(!empty($_POST['p_amnt_from']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.potential_amount >= '".$_POST['p_amnt_from']."' ) ";
            $and =1;
        }

        if(!empty($_POST['p_amnt_to']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.potential_amount <= '".$_POST['p_amnt_to']."' ) ";
            $and =1;
        }
        //.echo $where;exit();
		//print_r($_POST['top_filter']);exit;
        if(!empty($_POST['top_filter']))
        {   
            if($and && $_POST['top_filter']!='all')
                $where.=" and ";
            if($_POST['top_filter']=='all')
            {

            }
            else if($_POST['top_filter']=='done')
            {
                 $where.=" info.status = 1";
                 $and =1;
            }
            else if($_POST['top_filter']=='pending')
            {
                $where.=" info.status = 0";
                 $and =1;
            }
            else if ($_POST['top_filter']=='deferred')
            {
                $where.=" info.status = 2";
                 $and =1;
            }
			else if ($_POST['top_filter']=='active')
            {
                $where.=" info.original = 1";
                 $and =1;
            }
            
        }

        
        if(!empty($_POST['specific_list']))
        {//echo 'infolist'.$_POST['specific_list']; exit();
            if($and)
                $where.=" and ";

            $where.=" ( info.id IN (".$_POST['specific_list'].") ) ";
            $and =1;
        }

        //echo $where; exit();
        if($where!='')
        $this->db->where($where);
        $this->db->order_by("id", "desc");
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