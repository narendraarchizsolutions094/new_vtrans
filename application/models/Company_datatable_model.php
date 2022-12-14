<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_datatable_model extends CI_Model{
    
    function __construct() {
  
        $this->table = 'tbl_company';
        // Set orderable column fields
        $this->column_order = array('','comp.id','tbl_admin.s_display_name','sales_region.name','tbl_department.dept_name');

        // Set searchable column fields

        $this->column_search = array('comp.company_name','tbl_admin.s_display_name','tbl_admin.last_name','sales_region.name','tbl_department.dept_name');

        // $this->column_search = array('tck.ticketno','tck.id','tck.category','tck.name','tck.email','tck.product','tck.message','tck.issue','tck.solution','tck.sourse','tck.ticket_stage','tck.review','tck.status','tck.priority','tck.complaint_type','tck.coml_date','tck.last_update','tck.send_date','tck.client','tck.assign_to','tck.company','tck.added_by','enq.phone','enq.gender','prd.country_name');
        
        // Set default order
        $this->order = array('comp.id' => 'desc');

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
	
	public function userwise_getRows($postData){

        $this->userwise_get_datatables_query($postData);
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
        $this->db->select('comp.*,enq.enquiry_id');
        $this->db->from($this->table.' comp')
                    ->join('enquiry enq','enq.company=comp.id','left')
                    ->group_by('comp.id');
        $this->db->where("comp.comp_id",$this->session->companey_id);
        $where="";
        $where .= "( enq.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 

        $this->db->where($where);
        return $this->db->count_all_results();
    }
	
	public function userwise_countAll(){

        $this->db->select('enquiry.enquiry_id');
        $this->db->from('enquiry')
                    ->join('tbl_company comp','comp.id=enquiry.company','left')
                    ->group_by('enquiry.enquiry_id');
        $this->db->where("enquiry.comp_id",$this->session->companey_id);
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
	
	public function userwise_countFiltered($postData){
        $this->userwise_get_datatables_query($postData);
        $query = $this->db->count_all_results();
        return $query;
    }
    
    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    public function _get_datatables_query($postData){


        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		
		$enquiry_filters_sess   =   $this->session->enquiry_filters_sess;
        $from_created           =   !empty($enquiry_filters_sess['from_created'])?$enquiry_filters_sess['from_created']:'';       
        $to_created             =   !empty($enquiry_filters_sess['to_created'])?$enquiry_filters_sess['to_created']:'';
        $createdby                  =   !empty($enquiry_filters_sess['createdby'])?$enquiry_filters_sess['createdby']:'';
        $sales_region               =   !empty($enquiry_filters_sess['sales_region'])?$enquiry_filters_sess['sales_region']:''; 
        $department                  =   !empty($enquiry_filters_sess['department'])?$enquiry_filters_sess['department']:'';

        $this->db->select('comp.*,GROUP_CONCAT(enq.enquiry_id) enq_ids,tbl_admin.s_display_name,tbl_admin.last_name,sales_region.name,tbl_department.dept_name');
        $this->db->from($this->table.' as comp')
                    ->join('enquiry enq','enq.company=comp.id','left')
					->join('tbl_admin','tbl_admin.pk_i_admin_id=enq.created_by','left')
					->join('sales_region','sales_region.region_id=tbl_admin.sales_region','left')
					->join('tbl_department','tbl_department.id=tbl_admin.dept_name','left')
                    ->where('comp.process_id',$this->session->process[0])
                    ->group_by('comp.id');

        $where="comp.comp_id=".$this->session->companey_id;
        $where .= " AND ( enq.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))';
/*****************filters start******************/
if(!empty($from_created) && !empty($to_created)){
    $this->db->where('comp.created_at >=', date('Y-m-d',strtotime($from_created)));
    $this->db->where('comp.created_at <=', date('Y-m-d',strtotime($to_created)));                               
}
        
if(!empty($createdby)){
    $this->db->where('enq.created_by', $createdby);                                  
}

if(!empty($sales_region)){
    $this->db->where('tbl_admin.sales_region', $sales_region);                                  
}
if(!empty($department)){
    $this->db->where('tbl_admin.dept_name', $department);                                  
}		
/*****************filters start******************/     
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
	
	public function userwise_get_datatables_query($postData){


        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);


        $this->db->select('comp.id,comp.company_name,comp.created_at,tbl_admin.s_display_name,tbl_admin.last_name,sales_region.name as region,enquiry.client_name,lead_source.lead_name');
        $this->db->from('enquiry')
                    ->join('tbl_company comp','comp.id=enquiry.company','left')
					->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left')
					->join('sales_region','sales_region.region_id=tbl_admin.sales_region','left')
					->join('lead_source','lead_source.lsid=enquiry.enquiry_source','left')
                    ->where('enquiry.comp_id',$this->session->companey_id)
                    ->group_by('enquiry.enquiry_id');

 
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