<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Task_datatable_model extends CI_Model {
 
    var $table = 'query_response';


    //var $column_order = array('query_response.query_id','query_response.upd_date', 'query_response.task_remark','admin.user_name','query_response.mobile'); //set column field database for datatable orderable
    
    var $column_order = array(
    'query_response.task_date',
    'query_response.task_time',
    'query_response.subject',
    'query_response.task_remark',
    'enquiry.name',
    'tbl_admin.s_display_name',
    'query_response.mobile',
    'tbl_taskstatus.taskstatus_name',
    ); //set column field database for datatable orderable

    //var $column_search = array('query_response.query_id','query_response.upd_date', 'query_response.task_remark','admin.user_name','query_response.mobile'); //set column field database for datatable searchable 
    var $column_search = array(
    'query_response.task_date',
    'query_response.task_time',
    'query_response.subject',
    'query_response.task_remark',
    'tbl_admin.s_display_name',
    'query_response.mobile',
    "CONCAT(enquiry.name_prefix,' ',enquiry.name,' ',enquiry.lastname)",
    'tbl_taskstatus.taskstatus_name'
    ); //set column field database for datatable searchable 

    var $order = array('query_response.resp_id' => 'desc'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');		 
    }
 
    private function _get_datatables_query(){
        // $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $this->db->from($this->table);		
        $user_role = $this->session->user_role; 
        $user_id = $this->session->user_id;      
        $where = '';
        $this->db->select("query_response.resp_id,query_response.task_for,query_response.task_type,query_response.query_id,query_response.upd_date,query_response.task_date,query_response.task_time,query_response.task_remark,query_response.subject,query_response.task_status,query_response.mobile,tbl_admin.s_display_name as user_name,tbl_taskstatus.taskstatus_name as task_status,");
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
        if(!empty($_POST['task_for']) && $_POST['task_for'] == 2){
            $this->db->join('tbl_ticket', 'tbl_ticket.ticketno=query_response.query_id', 'left');
        }else{
            $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
        }
        $this->db->join('tbl_taskstatus', 'tbl_taskstatus.taskstatus_id=query_response.task_status', 'left');
        $filter_user_id = $this->session->filter_user_id;        
        if(!empty($_POST['task_for']) && $_POST['task_for'] == 2){
            if($this->session->filter_user_id){
                $where = "( tbl_ticket.added_by=$filter_user_id OR tbl_ticket.assign_to=$filter_user_id)";
            }else{
                $where .= " (tbl_ticket.added_by=$user_id OR tbl_ticket.assign_to=$user_id)";
            }
        }else{
            if($this->session->filter_user_id){
                $where = "( enquiry.created_by=$filter_user_id OR enquiry.aasign_to=$filter_user_id)";
            }else{
                $where .= " (enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id)";
            }
        }
        if(!empty($_POST['task_for']) && $_POST['task_for'] == 2){
            $where .= " AND query_response.task_for=2";
        }
        $this->db->where($where);
        $i = 0;     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {  
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    } 
    function get_datatables()
    {
        $this->_get_datatables_query();

        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();

        /*echo $this->db->last_query();
        exit();*/
        return $query->result();

        /*echo "<pre>";
        print_r($query->result());*/
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=query_response.create_by', 'left');
        $this->db->join('enquiry', 'enquiry.Enquery_id=query_response.query_id', 'left');
        $filter_user_id = $this->session->filter_user_id;
        $user_id = $this->session->user_id;  
        $where='';
        if($this->session->filter_user_id){                                
            $where = " enquiry.created_by=$filter_user_id OR enquiry.aasign_to=$filter_user_id";
        }else{
            $where .= " enquiry.created_by=$user_id OR enquiry.aasign_to=$user_id";          
        }

        $this->db->where($where);
        return $this->db->count_all_results();
    }
 
}