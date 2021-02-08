<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_datatable_model extends CI_Model{
    
    function __construct() {
  
        $this->table = 'enquiry';
        // Set orderable column fields
        $this->column_order = array('','enq.company');

        // Set searchable column fields

        $this->column_search = array('enq.company');

        // $this->column_search = array('tck.ticketno','tck.id','tck.category','tck.name','tck.email','tck.product','tck.message','tck.issue','tck.solution','tck.sourse','tck.ticket_stage','tck.review','tck.status','tck.priority','tck.complaint_type','tck.coml_date','tck.last_update','tck.send_date','tck.client','tck.assign_to','tck.company','tck.added_by','enq.phone','enq.gender','prd.country_name');
        
        // Set default order
        $this->order = array('enq.company' => 'desc');

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
        $this->db->from($this->table.' enq');
        $this->db->where("enq.comp_id",$this->session->companey_id);
        $where="";
        $where .= "( enq.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 

        $this->db->where($where);
        return $this->db->count_all_results();
    }
    
    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->count_all_results();
        return $query;
    }
    
    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    public function _get_datatables_query($postData){


        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        $this->db->select('DISTINCT(company),GROUP_CONCAT(enquiry_id,",") as enq_ids,(SELECT count(*) from tbl_client_contacts where client_id IN (enq_ids) ) as contacts_num');
        $this->db->from($this->table);
        $this->db->where('enquiry.company IS NOT NULL and CHAR_LENGTH(REPLACE(`enquiry`.company, " ", ""))>0');

        $where="";
        $where .= "( created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR aasign_to IN (".implode(',', $all_reporting_ids).'))';   
        $and =1;

        //echo $where; exit();
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