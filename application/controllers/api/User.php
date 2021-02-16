<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class User extends REST_Controller 
{
    function __construct() 
    {
    	parent::__construct();
    	$this->load->model('user_model');
    }

    public function getUser_post()
    {
    	$this->form_validation->set_rules('user_id','user_id', 'trim|required');
    	$this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    	// $this->form_validation->set_rules('process_id','process_id','trim|required',array('required'=>'You have note provided %s'));
    	
    	if($this->form_validation->run()==true)
    	{	
    		$this->load->model(array('location_model','Modules_model'));

    		$company_id   = $this->input->post('company_id');
    		//$process_id   = $this->input->post('process_id');
	        $user_id    =   $this->input->post('user_id');

	       	// $backup =  $this->session->userdata()??'';

	        // $this->session->companey_id = $company_id;
	        // $this->session->user_id = $user_id;

	       	// $data['user_data'] =  $this->user_model->read_by_id($user_id);

	       	// $data['state_list'] = $this->location_model->state_list();
         //    $data['city_list'] = $this->location_model->city_list();
         //    $data['region_list'] = $this->location_model->region_list();
         //    $data['territory_lsit'] = $this->location_model->territory_lsit();
            //$data['user_list'] = $this->user_model->user_list();
           // $data['department_list'] = $this->Modules_model->modules_list();
            //$data['user_role'] = $this->db->get('tbl_user_role')->result();
            //$data['county_list'] = $this->location_model->country();

        
        $data =   $this->db->select('pk_i_admin_id,s_display_name,last_name,s_user_email,s_phoneno,picture')
                        ->from('tbl_admin')
                        ->where('pk_i_admin_id',$user_id)
                        ->where('companey_id',$company_id)
                        ->get()->row();

            if(!empty($data))
            {
                $data->picture = base_url('/').$data->picture;
                $this->set_response([
                'status' => TRUE,
                'data' => $data
            ], REST_Controller::HTTP_OK);
            }
            else{
                $this->set_response([
                'status' => FALSE,
                'message' => 'No user found'
            ], REST_Controller::HTTP_OK);
            }
    		
  		} 
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		}
    }

    
    public function updateUser_post()
    {
        
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
    	$this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));

    	if($this->form_validation->run()==true)
    	{	
    		$user_id    =   $this->input->post('user_id');
			$company_id    =   $this->input->post('comp_id');

	       	$backup =  $this->session->userdata()??'';

	       	$this->session->companey_id = $company_id;
	        $this->session->user_id = $user_id;

    		if ($this->session->user_id == 9) 
    		{
	            $org = $this->input->post('org_name');
	            $designation = '';
	        } else {
	            $org = '';
	            $designation = $this->input->post('designation');
	        }

    		$postData = [
            'pk_i_admin_id' => $this->input->post('dprt_id', true),
            //'user_roles' => $this->input->post('user_role', true),
            //'user_type' => $this->input->post('user_type', true),
            'employee_id' => $this->input->post('employee_id', true),
            's_user_email' => $this->input->post('email', true),
            's_phoneno' => $this->input->post('cell', true),
            'second_email' => $this->input->post('second_email', true),
            'second_phone' => $this->input->post('second_phone', true),
            // 's_password' => $password,
            // 'modules' => $modules,
            's_display_name' => $this->input->post('Name', true),
            'state_id' => $this->input->post('state_id', true),
            'city_id' => $this->input->post('city_name', true),
            //'companey_id' => 1,
            // 'orgisation_name' => $org,
            //'user_permissions' => $permission,
            'last_name' => $this->input->post('last_name', true),
            'b_status' => $this->input->post('status', true),
            'date_of_birth' => $this->input->post('dob', true),
            'anniversary' => $this->input->post('anniversary', true),
            'contact_pname' => $this->input->post('cname', true),
            'contact_pemail' => $this->input->post('cemail', true),
            'contact_semail' => $this->input->post('csemail', true),
            'contact_phone' => $this->input->post('cphone', true),
            'contact_sphone' => $this->input->post('csphone', true),
            'designation' => $designation,
            'employee_band' => $this->input->post('employee_band', true),
            'country' => $this->input->post('country'),
            'region' => $this->input->post('region', true),
            'territory_name' => $this->input->post('territory', true),
            'add_ress' => $this->input->post('address', true),
        ];
			$this->session->set_userdata($backup);
	        //print_r($postData);
	        if($this->user_model->update($postData))
	        {
	        	$this->set_response([
                'status' => TRUE,
                'data' => 'Profile Updated Successfully.'
            	], REST_Controller::HTTP_OK);
	        }
	        else
	        {
				$this->set_response([
                'status' => TRUE,
                'data' => 'Unable to Update.'
            	], REST_Controller::HTTP_OK);
	        }
  		} 
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		}
    }

    public function updateUserImage_post()
    {
		 $this->form_validation->set_rules('user_id','user_id', 'trim|required');

    	if($this->form_validation->run()==true)
    	{	
    		 	$path = 'assets/images/user/';

	            if(!file_exists($path))
	            {
	              mkdir($path);
	            }
	            
		        $config = array(
		        'upload_path' => $path,
		        'allowed_types' => "gif|jpg|png|jpeg",        
		        'max_size' => "2048000",
		        'encrypt_name' => true
		        );
		        $this->load->library('upload');
		        $this->upload->initialize($config);
		      
	     	if($this->upload->do_upload('profile_image'))
	      	{
	      		$imageDetailArray = $this->upload->data();
        		$img =  $path.$imageDetailArray['file_name'];

        		$postData = array('pk_i_admin_id' => $this->input->post('dprt_id', true),
        							'picture'=>$img,
        					);
        		
        		$this->user_model->update($postData);

		      	$this->set_response([
	            'status' => TRUE,
	            'data' => 'Profile Image Updated Successfully.'
	        	], REST_Controller::HTTP_OK);
	      	}
		    else
			{
                $reason = $this->upload->display_errors();
					$this->set_response([
	                'status' => FALSE,
	                'data' => strip_tags($reason),
	            	], REST_Controller::HTTP_OK);
			}   		 
    	} 
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		}
    }
}