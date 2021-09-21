<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Attendance extends CI_Controller {
	
	private $limit = 16;
	
	public function __construct() 
	{
		parent::__construct();		
		$this->load->model(array(
			'User_model',
			'attendance_model'			
		));
		if(empty($this->session->user_id)){
		 redirect('login');   
		}
		$this->crumb_user = array();
	}
 	
 	public function att_load_data(){
        $list = $this->attendance_model->get_datatables();
       // echo $this->db->last_query();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $each) {
        
            $no++;
        
            $row = array();
            
            $row[] = $no;
            $row[] = $each->s_display_name.' '.$each->last_name;            
            $row[] = $each->check_in;
            $row[] = $each->check_out;
            
            $time1 = new DateTime($each->check_in);
            $time2 = new DateTime($each->check_out);
            
            $timediff = $time1->diff($time2);
            $time1 = new DateTime($timediff->format('%H:%i:%S'));
            $time2 = new DateTime($each->total);
            $diff        =   $time2->diff($time1);
                                   
            if($each->total){
              $row[] = $diff->format('%H:%i:%S');
            }else{
            	$row[] = '';
            }

            $row[] = $each->total;
            $row[] = '';
            
            $data[] = $row;
            
        }      
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->attendance_model->count_all(),
            "recordsFiltered" => $this->attendance_model->count_filtered(),
            "data" => $data,
        );
        //echo $this->db->last_query();
        echo json_encode($output);
 	}
 	function att_set_filters_session(){
 		$this->session->set_userdata('attendance_filter_date',$_POST['date']);
 	}
	
	
 	public function logs(){
 		$this->load->model('report_model');
 		$data['title'] = 'Attendance Logs';
		$data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));					
		$content = $this->load->view('loginfo/attendance__filter',$data,true);	 		
 		if ($_POST) {			
			$from		=	$this->input->post('att_date_from');
			$to			=	$this->input->post('att_date_to');			
			$employee	=	$this->input->post('employee');			
			$from = new DateTime($from);
			$to	  =	new DateTime($to);
			$to	  =	$to->modify('+1 day');
 			$period = new DatePeriod(
				$from,     
		     	new DateInterval('P1D'),
		     	$to
			);				
			//$period	=	array_reverse($period);
			if (!empty($period)) {
				foreach ($period as $value) {
			        $date = $value->format('Y-m-d');
			        $data['att_date'] = $date;
		 			$data['users'] = $this->attendance_model->attendance_logs($date,$employee);	
					$content .= $this->load->view('loginfo/attendance_logs',$data,true);	
				}
			}
			$data['content']	= $content;					
 		}else{
 			$date = date("Y-m-d"); 			 			
 			$employee = array();
 			$data['att_date'] = $date;
	 		$data['users'] = $this->attendance_model->attendance_logs($date,$employee);	
	 		$data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));	
			$content .= $this->load->view('loginfo/attendance_logs',$data,true);
			$data['content'] = $content;
 		}
		$this->load->view('layout/main_wrapper',$data);
 	}

	 public function get_breadcrumb($user_id){   
		$current_user = $this->session->user_id;
		if($user_id != $current_user){
		  $this->db->select('report_to,sibling_id');
		  $this->db->where('pk_i_admin_id',$user_id);
		  $user_row = $this->db->get('tbl_admin')->row_array();
		  if(empty($user_row['report_to'])){
			$report_to = $user_row['sibling_id'];
		  }else{
			$report_to = $user_row['report_to'];
		  }
		  $this->crumb_user[] = $report_to;
		  $this->get_breadcrumb($report_to);
		}else{
		  //$this->crumb_user[] = $user_id;
		}
		return array_unique($this->crumb_user);
	  }

	  function displayDates($date1, $date2, $format = 'Y-m-d' ) {
		$dates = array();
		$current = strtotime($date1);
		$date2 = strtotime($date2);
		$stepVal = '+1 day';
		while($current <= $date2) {
		   $dates[] = date($format, $current);
		   $current = strtotime($stepVal, $current);
		}
		return array_reverse($dates);
	 } 
	 public function visit_activity($user_id=0){
		if(empty($user_id)){
			$user_id = $this->session->user_id;
	  	}
	  	$crumb  = $this->get_breadcrumb($user_id);    
	  	$data['crumb'] = $crumb;        
	  	$data['current_user'] = $user_id;

	   	$this->load->model('report_model');
	   	$data['title'] = 'Team Visits';
	   	if ($_POST) {	
		  	$from		=	$this->input->post('att_date_from');
		  	$to		=	$this->input->post('att_date_to');
		  
		  	$data['range_arr'] = $this->displayDates($from,$to);		

			$desi		=	$this->input->post('designation');
			$region		=	$this->input->post('region');
		  
		  	$employee	=	$this->input->post('employee');			
				  $date = date("Y-m-d");
				  $data['att_date'] = $date;
				   $data['users'] = $this->attendance_model->myteam_logs($date,$employee,$from,$desi,$region,$user_id,$to);
			  }else{
				  $date = date("Y-m-d"); 			 			
				  $to = date("Y-m-d"); 			 			
				  $employee = array();
				  $data['att_date'] = $date;
				  $data['users'] = $this->attendance_model->myteam_logs($date,$employee,'','','',$user_id,$to);					  
				  $data['content'] = $content;
			  }
			  $content = $this->load->view('loginfo/visit_activity',$data,true);			  
			  $data['content'] = $content;					
		  	$this->load->view('layout/main_wrapper',$data);
	 }

/***********************My Team Start***************************/	
	public function myteam($user_id=0){
		if(empty($user_id)){
      		$user_id = $this->session->user_id;
    	}
    	$crumb  = $this->get_breadcrumb($user_id);
    	$data['crumb'] = $crumb;
    	$data['current_user'] = $user_id;
 		$this->load->model('report_model');
 		$data['title'] = 'Team Visits';
		$data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));
        $data['user_roles'] = $this->db->select('use_id,user_role')->where(array('comp_id'=>$this->session->userdata('companey_id')))->get('tbl_user_role')->result();
        $data['user_region'] = $this->db->select('region_id,name')->where(array('comp_id'=>$this->session->userdata('companey_id')))->get('sales_region')->result();		
		$desi		=	$this->input->post('designation');
		$region		=	$this->input->post('region');
		$from	= $data['from']	=	$this->input->post('att_date_from')??date('Y-m-d');
		$to	= $data['to']	=	$this->input->post('att_date_to')??date('Y-m-d');
		if(!empty($_GET['fdate']) && !empty($_GET['tdate'])){
			$from	= $data['from']	= $_GET['fdate'];
			$to	= $data['to']	= $_GET['tdate'];
		}		
		$content = $this->load->view('loginfo/visits__filter',$data,true);
 		if ($_POST || $from || $to) {
			$data['range_arr'] = $range_arr = $this->displayDates($from,$to);
			$employee	=	$this->input->post('employee');			
			        $date = date("Y-m-d");
			        $data['att_date'] = $date;
		 			$data['users'] = $this->attendance_model->myteam_logs($date,$employee,$from,$desi,$region,$user_id,$to);
                    $data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));					
				}else{
					$date = date("Y-m-d"); 			 			
					$to = date("Y-m-d"); 			 			
					$employee = array();
					$data['att_date'] = $date;
					$data['users'] = $this->attendance_model->myteam_logs($date,$employee,'','','',$user_id,$to);	
					$data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));	
					$data['content'] = $content;
				}
				$data['i'] = 0;
				$visit_activity = '';
				if(!empty($range_arr)){
					$data['is_end'] = end($range_arr);
					$i = 0;
					foreach($range_arr as $rdate){
						$data['i'] = $i;
						$data['current_date'] = $rdate;						
						$data['users_activity'] = $this->attendance_model->myteam_logs($rdate,$user_id,$rdate,'',$region,$user_id);
						$visit_activity .= $this->load->view('loginfo/visit_activity',$data,true);
						$i++;
					}
				}else{
					$data['is_end'] = date('Y-m-d');
					$data['current_date'] = $rdate = date('Y-m-d');						
					$data['users_activity'] = $this->attendance_model->myteam_logs($rdate,$user_id,$rdate,'',$region,$user_id);
					$visit_activity .= $this->load->view('loginfo/visit_activity',$data,true);
				}
				$data['visit_activity'] = $visit_activity;
				$content .= $this->load->view('loginfo/visit_logs',$data,true);
				$data['content'] = $content;					
			$this->load->view('layout/main_wrapper',$data);
 	}
/***********************My Team End***************************/	
	public function deletes($type = ""){		
	}
	
	
	
	public function mark_attendance(){
		if (user_role('200') == true) {}
		$atID 	=	!empty($_POST['atID'])?$_POST['atID']:'';
		
		$user_id	=	$this->session->user_id;		
		
		if(!$atID){
			$insert_arr = array(
							'uid'=>$user_id,
							'check_in_time'=>Date("Y-m-d H:i:s")
							);
			$this->db->insert('tbl_attendance',$insert_arr);
			$insert_id	=	$this->db->insert_id();		
		}else{
			$update_arr = array(							
							'check_out_time'=>Date("Y-m-d H:i:s")
							);
			$this->db->where('id',$atID);
			$insert_id = $this->db->update('tbl_attendance',$update_arr);	
			if($insert_id){
				$insert_id = 'updated';
			}		
		}	
		if($insert_id){			
			echo json_encode(array('id'=>$insert_id,'status'=>1));
		}else{
			echo json_encode(array('id'=>0,'status'=>0));			
		}
	} 
	
	public function activity($page = 1){
		
		$offset = ($page - 1) * $this->limit;
		
		if(isset($_COOKIE['activity'])){
			
			$crdt  =  $_COOKIE['activity'];
			$fltrdate = date("Y-m-d", strtotime($crdt));
			$dtpart = " AND  Date(created_date) = '$fltrdate'";
			
			
		
		}else{
				$dtpart ="";
		}
		
		
		
		$userno = $this->session->user_id;
	//	$qry = "SELECT * FROM tbl_comment WHERE created_by= '$userno' and  created_date between '$dtpart' and '$dtpart 23:59:59'";			  
		$qry   = "SELECT * FROM tbl_comment WHERE created_by= '$userno'".$dtpart." LIMIT {$offset} ,  $this->limit";
		$data['result'] = $this->db->query($qry)->result();
		
		$cqry  = "SELECT count(comm_id) as total FROM tbl_comment WHERE created_by= '$userno'".$dtpart;
		$data['total'] = $this->db->query($cqry)->row()->total;
		$data['page']  = $page;
		$data['title'] = 'Activity List';	
		
		$data['content'] = $this->load->view('loginfo/activity-log',$data,true);
		$this->load->view('layout/main_wrapper',$data);
		
	}
	public function check_attendance_status(){
		$user_id	=	$this->session->user_id;
		$this->db->where('uid',$user_id);
		$this->db->where('check_out_time',"0000-00-00 00:00:00");
		$this->db->order_by('id','DESC');
		$this->db->limit(1);
		$res=$this->db->get('tbl_attendance')->row_array();
		echo json_encode($res);
	}
	public function view($uid){
		$data['title'] = 'Attendance Details';		
		$data['user'] = $this->User_model->read_by_id($uid);
 		$data['all_att_log'] = $this->attendance_model->attendance_logs_by_uid($uid);		
 			
		/*echo "<pre>";
		echo $this->db->last_query().'<br><br><br>';	
		print_r($data['users']);
		echo "</pre>";
		exit();*/
		$data['content'] = $this->load->view('loginfo/attendance_details',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
	public function record_geolocation(){
		$user_id = $this->input->post('user_id');
		$lat = $this->input->post('lat');
		$long = $this->input->post('long');
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => base_url()."api/map_feed/user_waypoints",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('user_id' => $user_id,'latitude' => $lat,'longitude' => $long),
		  CURLOPT_HTTPHEADER => array(
		    "Cookie: ci_session=3ug73ssk599iduba0s40o495e6qjjhhl"
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		echo $response;
	}
	
	/* public function auto_check_out()
	{
		print_r('hi');exit;
	  /* $get_current_date_users = $this->db->get_where('tbl_attendance',array('DATE(check_in_time)' => date('Y-m-d')))->result_array();
	  if(!empty($get_current_date_users)){
		$this->db->where('task_id',0)->update('tbl_attendance',array('check_out_time' => date('Y-m-d H:i:s')));
		// echo $this->db->last_query();die;
		$att_arr  = array('message'=>'Mark Attendance Out Successfully');
		$this->set_response([
					'status' => true,
					'att_data' =>$att_arr
					 ], REST_Controller::HTTP_OK);
	  }else{
		$att_arr  = array('message'=>'No User Found !');
		$this->set_response([
			  'status' => false,
			  'message' =>$att_arr   
			   ], REST_Controller::HTTP_OK); 
	  } */
	} */
}