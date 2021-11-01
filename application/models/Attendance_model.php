<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Attendance_model extends CI_Model {
	public function __construct(){
        parent::__construct();    
    }
 
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
	/* public function myteam_logs($att_date,$employee,$from='',$desi='',$region='',$user_id=0,$to='',$comp_id=''){				
		
		if(empty($employee)){
			$this->db->select('pk_i_admin_id');
			if($user_id){
				$this->db->where('report_to',$user_id);				
			}else{
				$this->db->where('report_to',$this->session->user_id);
				$user_id = $this->session->user_id;
			}
    		$emp_res = $this->db->get('tbl_admin')->result_array();


			$employee = array();
			if(!empty($emp_res)){
				foreach($emp_res as $key=>$emp){
					$employee[] = $emp['pk_i_admin_id'];
				}
			}else{
				$urow  = $this->db->select('sibling_id')->where('pk_i_admin_id',$user_id)->get('tbl_admin')->row_array();
				if(!empty($urow['sibling_id'])){

					$report_to = $urow['sibling_id'];
					$this->db->where('report_to',$report_to);
					$emp_res = $this->db->get('tbl_admin')->result_array();
					foreach($emp_res as $key=>$emp){
						$employee[] = $emp['pk_i_admin_id'];
					}

				}

			}
			$employee[] = $user_id;
        }

		if(empty($employee)){
			return false;
		}
		

		$this->db->select("tbl_user_role.user_role,curr_location.waypoints as l_end,COUNT(DISTINCT(deal_info.id)) as t_deal,COUNT(DISTINCT(tbl_vis.id)) as t_vis,COUNT(DISTINCT(enquiry.enquiry_id)) as t_enq,tbl_admin.designation,sales_region.name as sale_region,tbl_admin.pk_i_admin_id,tbl_admin.employee_id,tbl_admin.s_display_name,tbl_admin.last_name,GROUP_CONCAT(CONCAT('(',tbl_attendance.id,',',tbl_attendance.uid,',',tbl_attendance.check_in_time,',',tbl_attendance.check_out_time,',',TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time),')') separator ',') as attendance_row,MIN(tbl_attendance.check_in_time) as check_in,MAX(tbl_attendance.check_out_time) as check_out,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tbl_attendance.check_out_time,tbl_attendance.check_in_time)))) as total,MIN(tblchkin_time.check_in_time) as new_check_in");
		$this->db->from('tbl_admin');
		$this->db->join('sales_region','sales_region.region_id = tbl_admin.sales_region','left');
		$this->db->join('tbl_user_role','tbl_user_role.use_id = tbl_admin.user_permissions','left');
		
		if(!empty($from) && !empty($to)) {
			
			$filter_date = $from;
			$filter_to = $to;

			$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND (DATE(tbl_attendance.check_in_time) >= "'.$filter_date.'" AND DATE(tbl_attendance.check_in_time) <= "'.$filter_to.'") ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');
 //new line for check in time           
$this->db->join('(select check_in_time,uid,id from tbl_attendance where (DATE(tbl_attendance.check_in_time) >= "'.$filter_date.'" AND DATE(tbl_attendance.check_in_time) <= "'.$filter_to.'") ORDER BY tbl_attendance.id asc) as tblchkin_time','tblchkin_time.uid = tbl_admin.pk_i_admin_id','left');
			
            $this->db->join('(select enquiry_id,created_by from enquiry where  (DATE(enquiry.created_date) >= "'.$filter_date.'" AND DATE(enquiry.created_date) <= "'.$filter_to.'") ORDER BY enquiry.enquiry_id asc) as enquiry','enquiry.created_by = tbl_admin.pk_i_admin_id','left');

            $this->db->join('(select id,user_id from tbl_visit where  (STR_TO_DATE(tbl_visit.created_at,"%Y-%m-%d") >= "'.$filter_date.'" AND STR_TO_DATE(tbl_visit.created_at,"%Y-%m-%d") <= "'.$filter_to.'") ORDER BY tbl_visit.id asc) as tbl_vis','tbl_vis.user_id = tbl_admin.pk_i_admin_id','left');

            $this->db->join('(select id,createdby from commercial_info where (DATE(commercial_info.creation_date) >= "'.$filter_date.'" AND DATE(commercial_info.creation_date) <= "'.$filter_to.'") ORDER BY commercial_info.id asc) as deal_info','deal_info.createdby = tbl_admin.pk_i_admin_id','left');

            $this->db->join('(select waypoints,uid from map_location_feed where map_location_feed.created_date!="0000-00-00 00:00:00" AND (DATE(map_location_feed.created_date) >= "'.$filter_date.'" AND DATE(map_location_feed.created_date) <= "'.$filter_to.'") ORDER BY map_location_feed.id asc) as curr_location','curr_location.uid = tbl_admin.pk_i_admin_id','left');			

		}else if (!empty($from)) {
			
			$filter_date = $from;
			$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND DATE(tbl_attendance.check_in_time) = "'.$filter_date.'" ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');
//new line for check in time            
			$this->db->join('(select check_in_time,uid,id from tbl_attendance where DATE(tbl_attendance.check_in_time) = "'.$filter_date.'" ORDER BY tbl_attendance.id asc) as tblchkin_time','tblchkin_time.uid = tbl_admin.pk_i_admin_id','left');

			$this->db->join('(select enquiry_id,created_by from enquiry where  DATE(enquiry.created_date) = "'.$filter_date.'" ORDER BY enquiry.enquiry_id asc) as enquiry','enquiry.created_by = tbl_admin.pk_i_admin_id','left');
            $this->db->join('(select id,user_id from tbl_visit where   STR_TO_DATE(tbl_visit.created_at,"%Y-%m-%d") = "'.$filter_date.'" ORDER BY tbl_visit.id asc) as tbl_vis','tbl_vis.user_id = tbl_admin.pk_i_admin_id','left');
            $this->db->join('(select id,createdby from commercial_info where DATE(commercial_info.creation_date) = "'.$filter_date.'" ORDER BY commercial_info.id asc) as deal_info','deal_info.createdby = tbl_admin.pk_i_admin_id','left');
            $this->db->join('(select waypoints,uid from map_location_feed where map_location_feed.created_date!="0000-00-00 00:00:00" AND DATE(map_location_feed.created_date) = "'.$filter_date.'" ORDER BY map_location_feed.id asc) as curr_location','curr_location.uid = tbl_admin.pk_i_admin_id','left');			

		}else{
			$this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND DATE(tbl_attendance.check_in_time) = CURDATE() ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');	
//new line for check in time
            $this->db->join('(select check_in_time,uid,id from tbl_attendance where DATE(tbl_attendance.check_in_time) = CURDATE() ORDER BY tbl_attendance.id asc) as tblchkin_time','tblchkin_time.uid = tbl_admin.pk_i_admin_id','left');			

            $this->db->join('(select enquiry_id,created_by from enquiry where DATE(enquiry.created_date) = CURDATE() ORDER BY enquiry.enquiry_id asc) as enquiry','enquiry.created_by = tbl_admin.pk_i_admin_id','left');
            $this->db->join('(select id,user_id from tbl_visit where STR_TO_DATE(tbl_visit.created_at,"%Y-%m-%d") = CURDATE() ORDER BY tbl_visit.id asc) as tbl_vis','tbl_vis.user_id = tbl_admin.pk_i_admin_id','left');
            $this->db->join('(select id,createdby from commercial_info where  DATE(commercial_info.creation_date) = CURDATE() ORDER BY commercial_info.id asc) as deal_info','deal_info.createdby = tbl_admin.pk_i_admin_id','left');
            $this->db->join('(select waypoints,uid from map_location_feed where map_location_feed.created_date!="0000-00-00 00:00:00" AND DATE(map_location_feed.created_date) = CURDATE() ORDER BY map_location_feed.id asc) as curr_location','curr_location.uid = tbl_admin.pk_i_admin_id','left');			
		}
		if (!empty($desi)) {
			$this->db->where('tbl_user_role.use_id',$desi);
		}
		if (!empty($region)) {
			$this->db->where('sales_region.region_id',$region);
		}
		$this->db->where('tbl_admin.b_status',1);
		if(!empty($comp_id)){
			$this->db->where('tbl_admin.companey_id', $comp_id);		
		}else{
			$this->db->where('tbl_admin.companey_id', $this->session->companey_id);		
		}
		if (!empty($employee)) {
			$this->db->where_in('tbl_admin.pk_i_admin_id', $employee);		
		}else{
            $this->db->where_in('tbl_admin.pk_i_admin_id', $employee);
        }
		$this->db->group_by('tbl_admin.pk_i_admin_id');
		return  $this->db->get()->result();     
		 //echo $this->db->last_query()."<br><br>";
		 //exit;
	} */
	
	public function myteam_logs($employee,$from='',$desi='',$region='',$user_id=0,$to='',$comp_id=''){				
		
		if(empty($employee)){
			$this->db->select('pk_i_admin_id');
			if($user_id){
				$this->db->where('report_to',$user_id);				
			}else{
				$this->db->where('report_to',$this->session->user_id);
				$user_id = $this->session->user_id;
			}
    		$emp_res = $this->db->get('tbl_admin')->result_array();


			$employee = array();
			if(!empty($emp_res)){
				foreach($emp_res as $key=>$emp){
					$employee[] = $emp['pk_i_admin_id'];
				}
			}else{
				$urow  = $this->db->select('sibling_id')->where('pk_i_admin_id',$user_id)->get('tbl_admin')->row_array();
				if(!empty($urow['sibling_id'])){

					$report_to = $urow['sibling_id'];
					$this->db->where('report_to',$report_to);
					$emp_res = $this->db->get('tbl_admin')->result_array();
					foreach($emp_res as $key=>$emp){
						$employee[] = $emp['pk_i_admin_id'];
					}

				}

			}
			$employee[] = $user_id;
        }

		if(empty($employee)){
			return false;
		}
		
		$filter_date = $from;
	    $filter_to = $to;
		
		$this->db->select("tbl_user_role.user_role,
		curr_location.waypoints as l_end,
		COUNT(DISTINCT(deal_info.id)) as t_deal,
		COUNT(DISTINCT(tbl_vis.id)) as t_vis,
		COUNT(DISTINCT(enquiry.enquiry_id)) as t_enq,
		tbl_admin.designation,
		sales_region.name as sale_region,
		tbl_admin.pk_i_admin_id,
		tbl_admin.employee_id,
		tbl_admin.s_display_name,
		tbl_admin.last_name,
		MIN(tbl_attendance.check_in_time) as check_in,
		MAX(tbl_attendance.check_out_time) as check_out,
		MIN(tblchkin_time.check_in_time) as new_check_in");
		$this->db->from('tbl_admin');
		$this->db->join('sales_region','sales_region.region_id = tbl_admin.sales_region','left');
		$this->db->join('tbl_user_role','tbl_user_role.use_id = tbl_admin.user_permissions','left');
        $this->db->join('(select * from tbl_attendance where tbl_attendance.check_out_time!="0000-00-00 00:00:00" AND (DATE(tbl_attendance.check_in_time) >= "'.$filter_date.'" AND DATE(tbl_attendance.check_in_time) <= "'.$filter_to.'") ORDER BY tbl_attendance.id asc) as tbl_attendance','tbl_attendance.uid = tbl_admin.pk_i_admin_id','left');
        $this->db->join('(select check_in_time,uid,id from tbl_attendance where (DATE(tbl_attendance.check_in_time) >= "'.$filter_date.'" AND DATE(tbl_attendance.check_in_time) <= "'.$filter_to.'") ORDER BY tbl_attendance.id asc) as tblchkin_time','tblchkin_time.uid = tbl_admin.pk_i_admin_id','left');
		$this->db->join('(select enquiry_id,created_by from enquiry where  (DATE(enquiry.created_date) >= "'.$filter_date.'" AND DATE(enquiry.created_date) <= "'.$filter_to.'") ORDER BY enquiry.enquiry_id asc) as enquiry','enquiry.created_by = tbl_admin.pk_i_admin_id','left');
        $this->db->join('(select id,user_id from tbl_visit where  (STR_TO_DATE(tbl_visit.created_at,"%Y-%m-%d") >= "'.$filter_date.'" AND STR_TO_DATE(tbl_visit.created_at,"%Y-%m-%d") <= "'.$filter_to.'") ORDER BY tbl_visit.id asc) as tbl_vis','tbl_vis.user_id = tbl_admin.pk_i_admin_id','left');
        $this->db->join('(select id,createdby from commercial_info where (DATE(commercial_info.creation_date) >= "'.$filter_date.'" AND DATE(commercial_info.creation_date) <= "'.$filter_to.'") ORDER BY commercial_info.id asc) as deal_info','deal_info.createdby = tbl_admin.pk_i_admin_id','left');
        $this->db->join('(select waypoints,uid from map_location_feed where map_location_feed.created_date!="0000-00-00 00:00:00" AND (DATE(map_location_feed.created_date) >= "'.$filter_date.'" AND DATE(map_location_feed.created_date) <= "'.$filter_to.'") ORDER BY map_location_feed.id asc) as curr_location','curr_location.uid = tbl_admin.pk_i_admin_id','left');			

		if (!empty($desi)) {
			$this->db->where('tbl_user_role.use_id',$desi);
		}
		if (!empty($region)) {
			$this->db->where('sales_region.region_id',$region);
		}
		
		$this->db->where('tbl_admin.b_status',1);
		
		if(!empty($comp_id)){
			$this->db->where('tbl_admin.companey_id', $comp_id);		
		}else{
			$this->db->where('tbl_admin.companey_id', $this->session->companey_id);		
		}
		if (!empty($employee)) {
			$this->db->where_in('tbl_admin.pk_i_admin_id', $employee);		
		}else{
            $this->db->where_in('tbl_admin.pk_i_admin_id', $employee);
        }
		$this->db->group_by('tbl_admin.pk_i_admin_id');
		return  $this->db->get()->result();
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
