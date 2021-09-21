<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Login extends REST_Controller {
    function __construct()
    {
        parent::__construct();
           $this->load->database();
           $this->load->library('form_validation');
           	
		$this->load->model(array(
			'enquiry_model','Leads_Model','location_model','Task_Model','User_model','Message_models','setting_model','dashboard_model','dash_model'
		));
		
		$this->load->library('email'); 
		
           $this->load->helper('url');
           $this->methods['users_get']['limit'] = 500; 
           $this->methods['users_post']['limit'] = 100; 
           $this->methods['users_delete']['limit'] = 50; 
    }
    public function login_post()
	{
	    $this->form_validation->set_rules('email', display('email'),'required|max_length[50]|trim');
        $this->form_validation->set_rules('password', display('password'),'required|max_length[32]|md5');
        $process    =   $this->input->post('process');
        
        $setting = $this->setting_model->read();
        
        $data['title'] = (!empty($setting->title) ? $setting->title : null);
        $data['logo'] = (!empty($setting->logo) ? $setting->logo : null);
        $data['favicon'] = (!empty($setting->favicon) ? $setting->favicon : null);
        $data['footer_text'] = (!empty($setting->footer_text) ? $setting->footer_text : null);
        $data['user'] = (object) $postData = [
            'email' => $this->input->post('email', true),
            'password' => md5($this->input->post('password', true)),
        ];
		//print_r($postData);exit;
        $this->load->model('dash_model');
         
        $data['products'] = $this->dash_model->product_list();
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            //check user data
            $check_user = $this->dashboard_model->check_user($postData);  
            $active = 1;
            if(!empty($check_user->row()))
            {
                if($check_user->row()->user_permissions!=1 && ($check_user->row()->status == 0 || $check_user->row()->status == null) ){
                $active = 0;
                }
                if ($check_user->num_rows() === 1 && $active) {
                
                    if($this->input->post('mobile_token')){
                        $this->db->where('pk_i_admin_id',$check_user->row()->pk_i_admin_id);
                        $this->db->update('tbl_admin',array('mobile_token'=>$this->input->post('mobile_token')));                    
                    }
                    $perm    =   $this->User_model->get_user_role($check_user->row()->user_permissions);
                    $permission_list = '';
                   
                    $login_token= random_string('alnum', 30);

                    if (!empty($perm)) {
                        $permission_list = $perm->user_permissions;
                        $a=explode(',',$permission_list);
                        //check mobile login right exist or not
                        if(array_search("134",$a)){
                            $this->user_model->updateLoginToken($check_user->row()->pk_i_admin_id,$login_token,1);
                        };
                    }
					if(!empty($check_user->row()->sales_branch)){
					$bdata = $this->db->where(array('branch_id'=>$check_user->row()->sales_branch))->get('branch')->row();
					$branch_id = $bdata->branch_name;
					}else{
					$branch_id = '';	
					}
                    $data=array(
                        'isLogIn'           => true,
                        'user_id'           => $check_user->row()->pk_i_admin_id,
						'user_name'         => $check_user->row()->s_display_name.' '.$check_user->row()->last_name,
                        'companey_id'       => $check_user->row()->companey_id,
                        'email'             => $check_user->row()->s_user_email,
                        'orgisation_name'   => $check_user->row()->orgisation_name,
                        'telephony_id'      => $check_user->row()->telephony_agent_id,
                        'token'             => $check_user->row()->telephony_token,
                        'phone_no'          => $check_user->row()->s_phoneno,
                        'availability'      => $check_user->row()->availability,
						'branch_id'         => $check_user->row()->sales_branch,
						'branch_name'       => $branch_id,
                        'permissions'       => $permission_list,
                        'login_token'       =>$login_token
                    );
                       $this->set_response([
                    'status' => TRUE,
                    'message' =>$data,
                ], REST_Controller::HTTP_OK);
                }
            } else {
                $array=array('error'=>'Invalid Username or Password');
                   $this->set_response([
                'status' => false,
                'message' => $array
            ], REST_Controller::HTTP_OK);
            }
	    
	}
	
	
}

   public function check_login_status_post()
   {
        $user_id= $this->input->post('uid');
        $device_token=$this->input->post('device_token');
        $this->form_validation->set_rules('email', display('user id'),'required');
        $this->form_validation->set_rules('password','Device Token','required');

       $data= $this->user_model->checkLoginToken($user_id,$device_token,1);
        $this->set_response([
            'status' => TRUE,
            'message' =>$data,
        ], REST_Controller::HTTP_OK);
   }
   public function get_process_post()
	{
		$this->load->helper("api");
	    $this->form_validation->set_rules('email', display('email'),'required|max_length[50]|valid_email');
        $this->form_validation->set_rules('password', display('password'),'required|max_length[32]|md5');
        $data['user'] = (object) $postData = [
            'email' => $this->input->post('email', true),
            'password' => md5($this->input->post('password', true)),
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            //check user data
            $check_user = $this->dashboard_model->check_user($postData); 
            if ($check_user->num_rows() === 1) {
			//print_r($check_user->row_array());
		  $userno = $check_user->row()->pk_i_admin_id;
                
				if(is_user_access_api($userno, 230) || is_user_access_api($userno, 231) || is_user_access_api($userno, 232) || is_user_access_api($userno, 233) || is_user_access_api($userno, 234) || is_user_access_api($userno, 235) || is_user_access_api($userno,236)){
                        
                        $user_process = explode(',', $check_user->row()->process);                        
						$userno     = $check_user->row()->pk_i_admin_id;	
                        $this->db->select('sb_id,product_name');
                        $this->db->where('comp_id', $check_user->row()->companey_id);
                        $this->db->where_in('sb_id',$user_process);
                       $process_arr     =   $this->db->get('tbl_product')->result_array();                      
                     
                       $process = [];
                       
                       $process_html = '';
                  
                       if(!empty($process_arr)){
						   
							if(is_user_access_api($userno, 270)){	
								$type = true;
							}else{
								$type = false;
							}
                            foreach ($process_arr as $value) {  

								$process[] = array( 
												"proccess_id" 	 => $value['sb_id'],
												"proccess_name"  => $value['product_name']
												);	
                            }
						
           
                       }else{
							$process = array();
                       }
					}
				
                $data=array(
                    'isProcess'   => (!empty($process)) ? TRUE : FALSE,
                    "process"     => $process,
					"processtype" => $type
                );
                   $this->set_response([
                'status' => TRUE,
                'proccess' =>$process,
				"ismultiple" => $type
            ], REST_Controller::HTTP_OK);
            } else {
                $array=array('error'=>'Invalid Username or Password');
                   $this->set_response([
                'status' => false,
                'message' => $array
            ], REST_Controller::HTTP_OK);
            }
	    
	}
	
	
}
        
      
   public function re_login_post(){
	     $user_id=$this->input->post('user_id');
		   $check_user = $this->dashboard_model->check_user_enquiry($user_id); 
	      $city_id = $this->db->select("*")
                        ->from("city")
                        ->where('id', $check_user->row()->city_id)
                        ->get();
						  $setting = $this->setting_model->read();
        
        $data['title'] = (!empty($setting->title) ? $setting->title : null);
        $data['logo'] = (!empty($setting->logo) ? $setting->logo : null);
        $data['favicon'] = (!empty($setting->favicon) ? $setting->favicon : null);
        $data['footer_text'] = (!empty($setting->footer_text) ? $setting->footer_text : null);
                $data = $this->session->set_userdata([
                     'isLogIn' => true,
                    'user_id' => $check_user->row()->pk_i_admin_id,
                    'companey_id' => $check_user->row()->companey_id,
                    'email' => $check_user->row()->email,
                    'designation' => $check_user->row()->designation,
                    'phone' => $check_user->row()->s_phoneno,
                    'fullname' => $check_user->row()->s_display_name . '&nbsp;' . $check_user->row()->last_name,
                    'country_id' =>0,
                    'region_id' => 0,
                    'territory_id' => 0,
                    'state_id' => 0,
                    'city_id' => 0,
                    /*'user_role' => $check_user->row()->user_roles,
                    'user_type' => $check_user->row()->user_type,*/
                    'user_right' => $check_user->row()->user_permissions,
                    'picture' => $check_user->row()->picture,
                    'modules' => $check_user->row()->modules,
                    'title' => (!empty($setting->title) ? $setting->title : null),
                    'address' => (!empty($setting->description) ? $setting->description : null),
                    'logo' => (!empty($setting->logo) ? $setting->logo : null),
                    'favicon' => (!empty($setting->favicon) ? $setting->favicon : null),
                    'footer_text' => (!empty($setting->footer_text) ? $setting->footer_text : null),
                    'process' => '',
                    'telephony_agent_id' => $check_user->row()->telephony_agent_id
                ]);
			
               if (!empty($check_user->result())) {
                $data=array(
                    'isLogIn'   => true,
                    
                );
            $this->set_response([
                'status' => TRUE,
                'message' =>$data,
            ], REST_Controller::HTTP_OK);
            } else {
                $array=array('error'=>'Invalid Username or Password');
                   $this->set_response([
                'status' => false,
                'message' => $array
            ], REST_Controller::HTTP_OK);
            }			
   }
   
   public function auto_check_out_get()
	{
	   $get_current_date_users = $this->db->get_where('tbl_attendance',array('DATE(check_in_time)' => date('Y-m-d')))->result_array();
	  if(!empty($get_current_date_users)){
		$this->db->where('DATE(check_in_time)',date('Y-m-d'))->where('check_out_time',"0000-00-00 00:00:00")->update('tbl_attendance',array('check_out_time' => date('Y-m-d H:i:s')));
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
	  }
	}
}
