<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Attendance_model extends CI_Model {
	public function attendance_logs($att_date,$employee){	
        if (empty($employee)) {
            $this->load->model('common_model');
            $employee    =   $this->common_model->get_categories($this->session->user_id);
        }
		$user_id   = $this->session->user_id;
		$this->db->select("tbl_admin.designation,sales_region.name as sale_region,tbl_admin.pk_i_admin_id,tbl_admin.employee_id,tbl_admin.s_display_name,tbl_admin.last_name,GROUP_CONCAT(CONCAT('(',tbl_attendance.id,',',tbl_attendance.uid,',',tbl_attendance.check_in_time,',',tbl_attendance.check_out_time,',',TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time),')') separator ',') as attendance_row,MIN(tbl_attendance.check_in_time) as check_in,MAX(tbl_attendance.check_out_time) as check_out,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time)))) as total");
		$this->db->from('tbl_admin');		
		if ($att_date) {
			$filter_date = $att_date;
			$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND DATE(tbl_attendance.check_in_time) = "'.$filter_date.'" ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');		
		}else{
			$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND DATE(tbl_attendance.check_in_time) = CURDATE() ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');					
		}
		$this->db->join('sales_region','sales_region.region_id = tbl_admin.sales_region','left');
		//$this->db->join('tbl_designation','tbl_designation.id = tbl_admin.designation','left');
		$this->db->where('tbl_admin.companey_id', $this->session->companey_id);		
		if (!empty($employee)) {
			$this->db->where_in('tbl_admin.pk_i_admin_id', $employee);		
		}else{
            $this->db->where_in('tbl_admin.pk_i_admin_id', $employee);
        }
		$this->db->group_by('tbl_admin.pk_i_admin_id');
		return $this->db->get()->result();     
	}
	
/***********************My team Visit Start*********************/	
	public function myteam_logs($att_date,$employee,$from=''){	
        if (empty($employee)) {
            $this->load->model('common_model');
            $employee    =   $this->common_model->get_categories($this->session->user_id);
        }
		$user_id   = $this->session->user_id;
		$this->db->select("COUNT(DISTINCT(enquiry.enquiry_id)) as t_enq,tbl_admin.designation,sales_region.name as sale_region,tbl_admin.pk_i_admin_id,tbl_admin.employee_id,tbl_admin.s_display_name,tbl_admin.last_name,GROUP_CONCAT(CONCAT('(',tbl_attendance.id,',',tbl_attendance.uid,',',tbl_attendance.check_in_time,',',tbl_attendance.check_out_time,',',TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time),')') separator ',') as attendance_row,MIN(tbl_attendance.check_in_time) as check_in,MAX(tbl_attendance.check_out_time) as check_out,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time)))) as total");
		//$this->db->select("tbl_visit.visit_date,tbl_visit.visit_time,tbl_admin.designation,sales_region.name as sale_region,tbl_admin.pk_i_admin_id,tbl_admin.employee_id,tbl_admin.s_display_name,tbl_admin.last_name");
		$this->db->from('tbl_admin');
		//$this->db->join('tbl_visit','tbl_visit.user_id = tbl_admin.pk_i_admin_id','left');
		$this->db->join('sales_region','sales_region.region_id = tbl_admin.sales_region','left');
		/* if (!empty($from && $to)) {
			$this->db->where('tbl_visit.visit_date >= ', $from);
            $this->db->where('tbl_visit.visit_date <= ', $to);		
		}else{
			$filter_date = $att_date;
			$this->db->where('tbl_visit.visit_date',$filter_date);
		} */
		if (!empty($from)) {
			$filter_date = $from;
			$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND DATE(tbl_attendance.check_in_time) = "'.$filter_date.'" ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');
            $this->db->join('(select enquiry_id,created_by from enquiry where enquiry.created_date!="0000-00-00 00:00:00" AND DATE(enquiry.created_date) = "'.$filter_date.'" ORDER BY enquiry.enquiry_id asc) as enquiry','enquiry.created_by = tbl_admin.pk_i_admin_id','left');			
		}else{
			$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND DATE(tbl_attendance.check_in_time) = CURDATE() ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');	
            $this->db->join('(select enquiry_id,created_by from enquiry where enquiry.created_date!="0000-00-00 00:00:00" AND DATE(enquiry.created_date) = CURDATE() ORDER BY enquiry.enquiry_id asc) as enquiry','enquiry.created_by = tbl_admin.pk_i_admin_id','left');				
		}
		$this->db->where('tbl_admin.companey_id', $this->session->companey_id);		
		if (!empty($employee)) {
			$this->db->where_in('tbl_admin.pk_i_admin_id', $employee);		
		}else{
            $this->db->where_in('tbl_admin.pk_i_admin_id', $employee);
        }
		$this->db->group_by('tbl_admin.pk_i_admin_id');
		return $this->db->get()->result();     
	}
/***********************My team Visit End*********************/	

	public function attendance_logs_by_uid($uid){
		$this->db->select("tbl_attendance.*,TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time) as dif");
		$this->db->where('uid',$uid);
		$this->db->where('check_out_time!=','0000-00-00 00:00:00');
		$this->db->order_by('tbl_attendance.id','DESC');
		return $this->db->get('tbl_attendance')->result_array();
	}
	var $table = 'tbl_attendance';
    var $column_order = array("","tbl_admin.s_display_name","tbl_attendance.check_in_time","tbl_attendance.check_out_time","SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time))))","",""); //set column field database for datatable orderable
    var $column_search = array("","tbl_admin.s_display_name","tbl_attendance.check_in_time","tbl_attendance.check_out_time","SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time))))","",""); //set column field database for datatable searchable 
    var $order = array('tbl_attendance.id' => 'desc'); // default order 
 
    public function __construct()
    {
        parent::__construct();    
    }
 
    private function _get_datatables_query()
    {
         
       $user_id   = $this->session->user_id;
		$user_role = $this->session->user_role;
		$region_id = $this->session->region_id;
		$assign_country = $this->session->country_id;
		$assign_region = $this->session->region_id;
		$assign_territory = $this->session->territory_id;
		$assign_state = $this->session->state_id;
		$assign_city = $this->session->city_id;	   
	        
		$this->db->select("tbl_admin.pk_i_admin_id,tbl_admin.employee_id,tbl_admin.s_display_name,tbl_admin.last_name,GROUP_CONCAT(CONCAT('(',tbl_attendance.id,',',tbl_attendance.uid,',',tbl_attendance.check_in_time,',',tbl_attendance.check_out_time,',',TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time),')') separator ',') as attendance_row,MIN(tbl_attendance.check_in_time) as check_in,MAX(tbl_attendance.check_out_time) as check_out,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time)))) as total");
		$this->db->from('tbl_admin');
		
		$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND DATE(tbl_attendance.check_in_time) = CURDATE() ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');		
		
		if($user_role==3){	
			$this->db->where('tbl_admin.user_roles>=',3);  
		}else if($user_role==4){
			$this->db->where('tbl_admin.user_roles>=',4); 
		}else if($user_role==5){
			$this->db->where('tbl_admin.user_roles>=',5);   
		}else if($user_role==6){
			$this->db->where('tbl_admin.user_roles>=',6);     
		}else if($user_role==7){
		  	$this->db->where('tbl_admin.user_roles>=',7); 
		}elseif($user_role==8||$user_role==9){
		  	$this->db->where('tbl_admin.user_roles>=',8); 
		}
		$this->db->group_by('tbl_admin.pk_i_admin_id');        
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
        
        return $this->db->count_all_results();
    }
}
