<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller {
	public function __construct(){   
		parent::__construct();		
		$this->lang->load("activitylogmsg","english");
	}
	public function signup_content(){		
		$data['c']	=	$this->input->post('c');
		$this->load->view('signup',$data);	 	
	}
	public function signup(){
        $this->form_validation->set_rules('name', display('email'), 'required');
        $this->form_validation->set_rules('mobile', display('mobile'), 'required|is_unique[tbl_admin.s_phoneno]', array('is_unique' => 'Mobile no already exist'));
        
        $this->form_validation->set_rules('email', display('email'), 'required|is_unique[tbl_admin.s_user_email]', array('is_unique' => 'Email address already exist'));
        $this->form_validation->set_rules('password',display('password'),"trim|required");
        $this->form_validation->set_rules('confirm_password',display('confirm_password'),"trim|required|matches[password]");
        $name	=	$this->input->post('name');
        $mobile	=	$this->input->post('mobile');
        $email	=	$this->input->post('email');
        $password =	$this->input->post('password');
        if (base64_decode($this->input->post('c')) == 57) { // lalantop
        	$user_comp_id = 57;
        	$user_permissions = 201;        	
        	$user_roles = 201;
        	$user_type = 201;
        	$user_process = '';
        	$enq_process = 124;
        	$enq_created_by = 273;
        }else{ // space international
        	$user_comp_id = 67;
        	$user_permissions = 151;
        	$user_roles = 151;
        	$user_type = 151;
        	$user_process = 146;
        	$enq_process = 146;
        	$enq_created_by = 295;
        }
		$postData = array(
						's_display_name'  =>	$name,
						's_user_email'    =>	$email,
						's_phoneno'       =>	$mobile,
						'companey_id' 	  =>	$user_comp_id,
						'b_status' 	  	  =>	1,
						'user_permissions'=>    $user_permissions,
						'user_roles'	  =>    $user_roles,
						'user_type'	  	  =>    $user_type,
						'process'     	  =>	$user_process,
						's_password'	  =>    md5($password)
					);
		if ($this->form_validation->run()) {
			$this->db->trans_start(); # Starting Transaction			
			$this->load->model('user_model');
			$this->load->model('enquiry_model');
			$user_id	=	$this->user_model->create($postData);
			$enq_code   = 	get_enquery_code();
			$name 		= explode(' ', $name);
			$fname   = !empty($name[0])?$name[0]:'';
			$lname   = !empty($name[1])?$name[1]:'';
			$enq_data 	= 	array(
								'Enquery_id' => $enq_code,
								'name'		 => $fname,
								'lastname'	 => $lname,
								'email'      => $email,
								'phone'      => $mobile,
								'comp_id'    => $user_comp_id,
								'status'     => 1,
								'product_id' => $enq_process,
								'created_by' => $enq_created_by								
							);
			$insert_id = $this->enquiry_model->create($enq_data);			
			if ($insert_id) {
				$this->load->model('Leads_Model');                                				                
                $this->Leads_Model->add_comment_for_events_stage_api(display("enquery_create"),$enq_code,'','','',$enq_created_by,'');
                if ($user_comp_id == 67) {
					$stage_id = 218; // payment done 			
	                $this->Leads_Model->add_comment_for_events_stage_api('Stage Updated',$enq_code,$stage_id,'','',$enq_created_by,'');                	
                }
            }
			$this->user_model->set_user_meta($user_id,array('payment_status'=>0));
			$this->db->trans_complete(); # Completing transaction
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				$res = array('status'=>0,'message'=>'Something went wrong!');
			}else{
				if ($user_id) {
					$res = array('status'=>1,'message'=>"Account created successfully please <a href=".base_url('login').">login</a>");
				}else{
					$res = array('status'=>0,'message'=>'Something went wrong!');
				}
			}
		}else{
			$res = array('status'=>0,'message'=>validation_errors());
		}
		echo json_encode($res);
	}
}
