<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visit_datatable_model extends CI_Model{
    
    function __construct() {
  
        $this->table = 'tbl_visit';
        // Set orderable column fields
        $this->column_order = array('tbl_visit.id','visit_date','visit_time','rating','visit_otexpSum','enquiry.name','enquiry.company','visit_expSum');

        // Set searchable column fields

        $this->column_search = array('travelled','travelled_type','next_location');

        // $this->column_search = array('tck.ticketno','tck.id','tck.category','tck.name','tck.email','tck.product','tck.message','tck.issue','tck.solution','tck.sourse','tck.ticket_stage','tck.review','tck.status','tck.priority','tck.complaint_type','tck.coml_date','tck.last_update','tck.send_date','tck.client','tck.assign_to','tck.company','tck.added_by','enq.phone','enq.gender','prd.country_name');
        
        // Set default order
        $this->order = array('created_at' => 'desc');
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

        $this->db->from($this->table);
        $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
        $this->db->join('visit_details','visit_details.visit_id=tbl_visit.id','left');

        $this->db->where("enquiry.comp_id",$this->session->companey_id);
        $where="";
        $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        if(!empty($_POST['specific_list']))
        {
            $where.="AND tbl_visit.id IN (".$_POST['specific_list'].") ";   
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

        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        // print_r($_POST);
        $this->db->select($this->table.'.*,tbl_visit.created_at,enquiry.name,enquiry.status as enq_type,enquiry.Enquery_id,enquiry.company, tbl_visit.id as vids,');
        $this->db->select('(select sum(amount) from tbl_expense where tbl_expense.visit_id = tbl_visit.id AND type="2") as visit_otexpSum');
        $this->db->select('(select sum(amount) from tbl_expense where tbl_expense.visit_id = tbl_visit.id AND type="1") as visit_expSum');
        // $this->db->select('(select COUNT(id) from tbl_expense where tbl_expense.visit_id = tbl_visit.id) as visit_totalexp');
        // $this->db->select('(select COUNT(id) from tbl_expense where  tbl_expense.approve_status = "1"  AND  tbl_expense.id = tbl_visit.id) as visit_reject');
        // $this->db->select('(select COUNT(id) from tbl_expense where  tbl_expense.approve_status = "2"  AND  tbl_expense.id = tbl_visit.id) as visit_approve');
        $this->db->from($this->table);
        $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
        // $this->db->join('visit_details','visit_details.visit_id=tbl_visit.id','left');
        $this->db->where("tbl_visit.comp_id",$this->session->companey_id);
        $this->db->order_by("tbl_visit.created_at",'DESC');
        $where="";

        $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
        $and =1;
        
        if(!empty($_POST['from_date']))
        {
            $where.=" AND tbl_visit.visit_date >= '".$_POST['from_date']."'";
            $and =1;
        }

        if(!empty($_POST['to_date']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.visit_date <= '".$_POST['to_date']."'";
            $and =1;
        }

        if(!empty($_POST['from_time']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.visit_time >= '".$_POST['from_time']."'";
            $and =1;
        }

        if(!empty($_POST['to_time']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.visit_time <= '".$_POST['to_time']."'";
            $and =1;
        }


        if(!empty($_POST['enquiry_id']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.enquiry_id = '".$_POST['enquiry_id']."'";
            $and =1;
        }
        if(!empty($_POST['createdby']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.user_id = '".$_POST['createdby']."'";
            $and =1;
        }
        if(!empty($_POST['company']))
        {   
            if($and)
                $where.=" and ";

            $where.=" enquiry.company = '".$_POST['company']."'";
            $and =1;
        }
        if(!empty($_POST['rating']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.rating LIKE '%".$_POST['rating']."%'";
            $and =1;
        }

        if(!empty($_POST['specific_list']))
        {
            if($and)
                $where.=" and ";

            $where.=" ( tbl_visit.id IN (".$_POST['specific_list'].") ) ";
            $and =1;
        }
        if(!empty($_POST['expensetype'])){
                $type=$_POST['expensetype'];
               
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

}