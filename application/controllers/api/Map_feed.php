<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Map_feed extends REST_Controller {
  function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('form_validation');
    $this->load->helper('date');
  }
  
  public function user_waypoints_post(){
    $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
    $this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
    $this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
    if ($this->form_validation->run() == TRUE) {      
      $uid = $this->input->post('user_id');      
      
      $where = " uid=$uid AND DATE(created_date)=CURDATE()";
      $this->db->where($where);    
      $res_row  = $this->db->get('map_location_feed')->row_array();      
      
      $latitude   = (float)$this->input->post('latitude');
      $longitude  = (float)$this->input->post('longitude');
      
      $new_waypoint = array($latitude,$longitude);
      if(!empty($res_row)){
        $waypoints  = json_decode($res_row['waypoints'],true);        
        array_push($waypoints, $new_waypoint);
        $update_array = array(        
          'waypoints'  => json_encode($waypoints)
        );      
        $this->db->where('id',$res_row['id']);
        $this->db->update('map_location_feed',$update_array);
      }else{      
        $insert_array = array(
          'uid'       => $uid,
          'waypoints'  => json_encode(array($new_waypoint))
        );      
        $this->db->insert('map_location_feed',$insert_array);
      }
      $this->set_response([
                'status' => true,
                'message' =>'Feed accepted'
                 ], REST_Controller::HTTP_OK);
    } else {
      $this->set_response([
            'status' => false,
            'message' => str_replace(array("\n", "\r"), ' ', strip_tags(validation_errors()))  
             ], REST_Controller::HTTP_OK);
    }
  }
public function mark_attendance_post(){
    $this->form_validation->set_rules('user_id','User id','required');
    $this->form_validation->set_rules('punchout','Punchout','required');
    if ($this->form_validation->run() == TRUE) {
      
      $user_id  = $this->input->post('user_id');
      $punchout = $this->input->post('punchout');
      $insert_array    =  array('uid'=>$user_id,'check_in_time'=>Date("Y-m-d H:i:s"));
      $insert_array2    =  array('uid'=>$user_id,'check_out_time'=>Date("Y-m-d H:i:s"));
      $where = " uid=$user_id AND DATE(check_in_time)=CURDATE()";
      $this->db->where($where);
      $res_row  = $this->db->order_by('id','desc')->get('tbl_attendance')->row_array();      
      //if(empty($res_row)){
      if($punchout == 'in'){
        $where = " uid=$user_id AND DATE(check_in_time)=CURDATE()";
        $this->db->where($where);
        $this->db->update('tbl_attendance',$insert_array2);
        $att_arr  = array('message'=>'Mark In attendance successfully');
        $this->db->insert('tbl_attendance',$insert_array);
        $this->set_response([
                    'status' => true,
                    'att_data' =>$att_arr
                     ], REST_Controller::HTTP_OK);
      }else if($punchout == 'out'){
        $att_arr  = array('message'=>'Mark Out attendance successfully');
        $where = " uid=$user_id";//AND DATE(check_in_time)=CURDATE() AND id = ". $res_row['id']
        $this->db->where($where);
        $this->db->update('tbl_attendance',$insert_array2);
        $this->set_response([
                    'status' => true,
                    'att_data' =>$att_arr
                     ], REST_Controller::HTTP_OK);      
      }else{
        $att_arr  = array('message'=>'Fields are required');
        $this->set_response([
              'status' => false,
              'message' =>$att_arr   
               ], REST_Controller::HTTP_OK); 
      }
    } else {
        $att_arr  = array('message'=>'Fields are required');
      $this->set_response([
            'status' => false,
            'message' =>$att_arr   
             ], REST_Controller::HTTP_OK); 
    }
  }
 public function check_attendance_status_post(){
  	$this->form_validation->set_rules('user_id','User id','required');
    if ($this->form_validation->run() == TRUE) {      
      
      $user_id  = $this->input->post('user_id');
      //$where = " uid=$user_id AND DATE(check_in_time)=CURDATE() AND  check_out_time = '0000-00-00 00:00:00' order by id desc limit 1";
      $where = " uid=$user_id";      
      $this->db->where($where);   
      $res_row  = $this->db->order_by('id','desc')->limit(1,0)->get('tbl_attendance')->row_array();
     // echo $this->db->last_query();die;      
      if(!empty($res_row)){
        if($res_row['check_in_time'] != '0000-00-00 00:00:00' && $res_row['check_out_time'] != '0000-00-00 00:00:00'){
          $att_arr  = array('message'=>'out');
          $this->set_response([
                    'status' => true,
                    'att_data' =>$att_arr
                     ], REST_Controller::HTTP_OK);
        }else{
          $att_arr  = array('message'=>'in');
          $this->set_response([
                    'status' => true,
                    'att_data' =>$att_arr
                     ], REST_Controller::HTTP_OK);
        }
      }else{
       	$att_arr  = array('message'=>'out');
        $this->set_response([
                    'status' => true,
                    'att_data' =>$att_arr
                     ], REST_Controller::HTTP_OK);
      }
    } else {
        $att_arr  = array('message'=>'Fields are required');
      $this->set_response([
            'status' => false,
            'message' =>$att_arr   
             ], REST_Controller::HTTP_OK); 
    }
  }
  
}
