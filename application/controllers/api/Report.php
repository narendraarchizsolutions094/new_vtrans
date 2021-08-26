<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Report extends REST_Controller {
    function __construct()  { 
        parent::__construct();        
    }
    public function team_report_post(){
        $user_id = $this->input->post('user_id');
        $this->load->model('attendance_model');        
        $date = $this->input->post('from');
        $to   = $this->input->post('to');
        if($date && $to){
        }else{
            $date = date('Y-m-d');
            $to   = date('Y-m-d');
        }        
        $employee = array();
        $comp_id = $this->input->post('comp_id');
        $users = $this->attendance_model->myteam_logs($date,$employee,'','','',$user_id,$to,$comp_id);
        //echo $this->db->last_query();
        $res_arr = array();
        if(!empty($users)){
            foreach($users as $key => $value){
                $res_arr[] = array(
                    'user_id'       => $value->pk_i_admin_id,
                    'user_role'     => $value->user_role,
                    'deal_count'    => $value->t_deal,
                    'enq_count'     => $value->t_enq,
                    'visit_count'   => $value->t_vis,
                    'designation'   => $value->designation,
                    'sales_region'  => $value->sale_region,
                    'employee_id'   => $value->employee_id,
                    'employee_name' => $value->s_display_name.' '.$value->last_name,
                );
            }
        }
        $this->set_response([
            'status' => true,
            'data' =>$res_arr
             ], REST_Controller::HTTP_OK);         
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
    public function visit_activity_post(){
        $user_id = $this->input->post('user_id');
        $this->load->model('attendance_model');        
        $comp_id = $this->input->post('comp_id');
        $date = $this->input->post('from');
        $to   = $this->input->post('to');
        if($date && $to){
        }else{
            $date = date('Y-m-d');
            $to   = date('Y-m-d');
        }        
        $range_arr = $this->displayDates($date,$to);	
        if(!empty($range_arr)){                        
            foreach($range_arr as $rdate){
                $data['current_date'] = $rdate;						
                $res = $this->attendance_model->myteam_logs($rdate,$user_id,$rdate,'','',$user_id,$rdate,$comp_id);                
                if(!empty($res)){
                    $res_arr[] = $res[0];
                }
            }
        }else{
            $data['is_end'] = date('Y-m-d'); 
            $data['current_date'] = $rdate = date('Y-m-d');						
            $res = $this->attendance_model->myteam_logs($rdate,$user_id,$rdate,'','',$user_id,$rdate,$comp_id);
            if(!empty($res)){
                $res_arr[] = $res[0];
            }            
        }
        $res_array = array();
        if(!empty($res_arr)){
            foreach($res_arr as $key => $value){
                $res_array[] = array(
                    'user_id'       => $value->pk_i_admin_id,
                    'designation'   => $value->designation,
                    'sale_region'   => $value->sale_region,
                    'employee_id'   => $value->employee_id,
                    'employee_name' => $value->s_display_name.' '.$value->last_name,                    
                    'attendance_row'=> $value->attendance_row,
                    'check_in'      => $value->check_in,
                    'check_in'      => $value->check_in,
                    'total'         => $value->total,
                    'waypoints'     => $value->l_end,
                    'user_role'     => $value->user_role
                );
            }
        }
        //print_r($res_arr);


        $this->set_response([
            'status' => true,
            'data' =>$res_array
             ], REST_Controller::HTTP_OK);         
    }

    public function visit_live_post(){                
        $id=$this->input->post('user_id');
        if($this->input->post('curr_date')){
            $curr_date = $this->input->post('curr_date');
            $where = " uid=$id AND DATE(created_date)='$curr_date'";
        }else{
            $where = " uid=$id AND DATE(created_date)=CURDATE()";
        }
        $this->db->select('id,uid');
        $this->db->where($where);    
        $res_rowsss  = $this->db->get('map_location_feed')->row_array();
        if(!empty($res_rowsss['id'])){
			$data['title'] = 'Visit Map';
			$data['att_id'] = $res_rowsss['id'];
            $content = $this->load->view('loginfo/live_map', $data, true);
			echo json_encode(array('status'=>true,'data'=>$content));
        }else{
            echo json_encode(array('status'=>false,'msg'=>validation_errors()));
        }
        //echo $this->db->last_query();
       
    }


}