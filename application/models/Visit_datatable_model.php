<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visit_datatable_model extends CI_Model{
    
    function __construct() {
  
        $this->table = 'tbl_visit';
        // Set orderable column fields
        $this->column_order = array('tbl_visit.id','visit_date','visit_time','rating','enquiry.name','enquiry.company','visit_expSum','visit_otexpSum','tbl_visit.user_id','tbl_visit.actualDistance','tbl_visit.actualDistance','tbl_visit.id','tbl_visit.id','tbl_visit.id');

        // Set searchable column fields

        $this->column_search = array('travelled','travelled_type','next_location','tbl_company.company_name','enquiry.name');

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
        $this->db->select($this->table.'.*,tbl_visit.created_at,concat_ws(" ",tbl_admin.s_display_name,tbl_admin.last_name) as employee,enquiry.name,enquiry.status as enq_type,enquiry.Enquery_id,enquiry.company, tbl_visit.id as vids,tbl_company.company_name,enquiry.client_name,contact.c_name as contact_person,sales_region.name as region_name,branch.branch_name as branch_name,sales_area.area_name as area_name,enquiry_status.title as enquiry_status_title,city.city');
        $this->db->select('(SELECT sum(amount) from tbl_expense  where tbl_expense.visit_id = tbl_visit.id AND tbl_expense.type="2") as visit_otexpSum');
        $this->db->select('(select sum(amount) from tbl_expense where tbl_expense.visit_id = tbl_visit.id AND tbl_expense.type="1" AND tbl_expense.approve_status = "2" ) as visit_expSum');
        $this->db->from($this->table);
        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_visit.user_id','left');
        $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
        $this->db->join('branch','branch.branch_id=enquiry.sales_branch','left');
       $this->db->join('enquiry_status','enquiry.status=enquiry_status.status_id','left');
       $this->db->join('city','enquiry.city_id=city.id','left');
        $this->db->join('sales_region','sales_region.region_id=enquiry.sales_region','left');
        $this->db->join('sales_area','sales_area.area_id=enquiry.sales_area','left');
        $this->db->join('tbl_company','tbl_company.id=enquiry.company','left');
        $this->db->join('tbl_client_contacts contact','contact.cc_id=tbl_visit.contact_id','left');

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
        if(!empty($_POST['contact']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.contact_id = '".$_POST['contact']."'";
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
    public function userwisevisits($filter=array()){
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);       
        $this->db->select('count(tbl_visit.user_id) as c,tbl_admin.s_display_name as employee,sales_region.name as region_name,branch.branch_name as branch_name,sales_area.area_name as area_name');
        $this->db->from('tbl_visit');
        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_visit.user_id');        
        $this->db->join('branch','branch.branch_id=tbl_admin.sales_branch','left');
        $this->db->join('sales_region','sales_region.region_id=tbl_admin.sales_region','left');
        $this->db->join('sales_area','sales_area.area_id=tbl_admin.sales_area','left');                
        $where="";
        $where .= "tbl_admin.pk_i_admin_id IN (".implode(',', $all_reporting_ids).")";
        $and =1;
        if(!empty($filter['from_date']))
        {
            $where.=" AND tbl_visit.visit_date >= '".$filter['from_date']."'";
            $and =1;
        }

        if(!empty($filter['to_date']))
        {   
            if($and)
                $where.=" and ";

            $where.=" tbl_visit.visit_date <= '".$filter['to_date']."'";
            $and =1;
        }
        $this->db->where($where);        
        $this->db->group_by('tbl_visit.user_id');
        return $this->db->get()->result_array();
       
    }

}