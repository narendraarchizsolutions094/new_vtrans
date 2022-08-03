<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'department_model',
			'User_model',
			'location_model',
			'Modules_model',
			'doctor_model',
			'enquiry_model'
			
		));
	}
 
	public function index()
	{
		$data['title'] = display('user_list');
		#-------------------------------#
		$data['departments'] = $this->User_model->reads();
		$data['user_role'] = $this->db->get('tbl_user_role')->result();
		$data['content'] = $this->load->view('user',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	} 
     	public function channel_partner()
	{
		$data['title'] = display('user_list');
		$data['page_title'] = 'Channel Partner';
		#-------------------------------#
		$data['departments'] = $this->User_model->partner();
		$data['type_list'] = $this->User_model->types_list();
		$data['user_role'] = $this->db->get('tbl_user_role')->result();
		$data['content'] = $this->load->view('partner_list',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
     
	public function user_type()
	{
		$data['title'] = display('user_list');
		$data['page_title'] = display('user_function');
		 $data['nav1']='nav3';
		#-------------------------------#
		$data['lead_score'] = $this->db->get('tbl_user_role')->result();
		$data['content'] = $this->load->view('user_role',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
     
    public function user_type_add()
       {
		  if(!empty($_POST)){
		      
		        $this->load->library('form_validation');
		        
                $user_type   = $this->input->post('user_type');
                $permission = $this->input->post('permissions');
                
                
                
                $permissions = implode(',',$permission);
                
                $this->form_validation->set_rules('user_type', 'user_role','required|is_unique[tbl_user_role.user_role]');
                
                if($this->form_validation->run() == FALSE){
                    
                    
                    $this->session->set_flashdata('FAILMSG','User role already exists in the table');
                    redirect('user/permissions');
                    
                }else{
                    
                     $data = array(
                            'user_role' => $user_type,
                            'created_date' => date('Y-m-d h:s:i'),
                            'created_by' => $this->session->user_id,
                            'user_permissions' =>$permissions
    						);
    
                    $this->db->insert('tbl_user_role',$data);
                    
                    $this->session->set_flashdata('SUCCESSMSG','Service Modules Assigned Successfully');
                    redirect('user/permissions');
                }
			} else{
			  	$data['page_title'] =  display('user_function') ;
                $data['content'] = $this->load->view('user_role',$data,true);
		$this->load->view('layout/main_wrapper',$data);
		   }
            
		
       }
     
     
     
     
 	public function create()
	{ exit;
	  /*  if(user_role('130')==true){
		}*/
		$data['title'] = display('add_user');
		#-------------------------------#
	//	$this->form_validation->set_rules('company_info', display('company_info') ,'required');
		$this->form_validation->set_rules('Name', display('disolay_name'),'required');
		$this->form_validation->set_rules('cell', display('cell') ,'required|max_length[10]');
		$this->form_validation->set_rules('state_id', display('state_name') ,'required');
		$this->form_validation->set_rules('city_name', display('city_name') ,'required');
		$this->form_validation->set_rules('user_role', display('user_role') ,'required');
		$this->form_validation->set_rules('user_type', display('user_type') ,'required');
	    $this->form_validation->set_rules('modules', display('customer_services'));
		$this->form_validation->set_rules('status', display('status') ,'required');
		$this->form_validation->set_rules('cname', 'Contact person Name ', 'differs[Name]');
			if (empty($this->input->post('dprt_id'))) {
	    		$this->form_validation->set_rules('employee_id', display('employee_id') ,'required|is_unique[tbl_admin.employee_id]',array('is_unique'=>'Duplicate Entery For Employee Id '));
	        	$this->form_validation->set_rules('email', display('email') ,'required|is_unique[tbl_admin.s_user_email]',array('is_unique'=>'Duplicate Entery For email'));
		        $this->form_validation->set_rules('password', display('password'),'required|min_length[8]');
	    	    
	    	}
	
		#-------------------------------#
			if(!empty($this->input->post('modules'))){
		$modules=implode(",",$this->input->post('modules'));
		}else{$modules='';}
		if (empty($this->input->post('dprt_id'))) {
		  $password=  md5($this->input->post('password',true));
		}else{
		        // $this->session->set_flashdata('exception','password not updated');
		    $password= $this->input->post('old_pass',true);}
		
		//Upload employee image..
	
         //picture upload
        $img = $this->fileupload->do_upload(
            'assets/images/user/',
            'file'
        );
        // if picture is uploaded then resize the picture
        if ($img !== false && $img != null) {
            $this->fileupload->do_resize(
                $img, 293, 350
            );
        }
        
        if($this->input->post('user_type'))
         {   
            $permission = $this->input->post('user_type');
        }else{
            
            $permission ='';
        }
		
		$data['department'] = (object)$postData = [
			'pk_i_admin_id' 	  => $this->input->post('dprt_id',true),
			'user_roles' 		  => $this->input->post('user_role',true),
          	'user_type' 		  => $this->input->post('user_type',true),
          	'employee_id' 		  => $this->input->post('employee_id',true),
			's_user_email' 		  => $this->input->post('email',true),
			's_phoneno' 		  => $this->input->post('cell',true),
			'second_email' 		  => $this->input->post('second_email',true),
			'second_phone' 		  => $this->input->post('second_phone',true),
			
			's_password' =>$password,
			'modules'      => $modules,
			's_display_name'      => $this->input->post('Name',true),
			'state_id'      => $this->input->post('state_id',true),
			'city_id' 	  => $this->input->post('city_name',true),
			'companey_id' 		  => 1,
			//add new feilds..
			'user_permissions'=>$permission,
			'last_name'=>$this->input->post('last_name',true),
			'b_status'=>$this->input->post('status',true),
			'date_of_birth'=>$this->input->post('dob',true),
			'anniversary'=>$this->input->post('anniversary',true),
			'designation'=>$this->input->post('designation',true),
			'employee_band'=>$this->input->post('employee_band',true),
			'country'=>$this->input->post('country'),
			'region'=>$this->input->post('region',true),
			'territory_name'=>$this->input->post('territory',true),
			'add_ress'=>$this->input->post('address',true),
			
			'contact_pname'=>$this->input->post('cname',true),
			'contact_pemail'=>$this->input->post('cemail',true),
			'contact_semail'=>$this->input->post('csemail',true),
			'contact_phone'=>$this->input->post('cphone',true),
			'contact_sphone'=>$this->input->post('csphone',true),
			
			
			
			'picture'=>(!empty($img)?$img:$this->input->post('new_file')),
			'report_to'=>$this->input->post('report_to',true)
		]; 
		#-------------------------------#
		if ($this->form_validation->run() === true) {
			#if empty $dprt_id then insert data
			if (empty($this->input->post('dprt_id'))) {
				if ($this->User_model->create($postData)) {
					#set success message
					$this->session->set_flashdata('message', display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
				redirect('user');
			} else {
				if ($this->User_model->update($postData)) {
					#set success message
					if(!empty($this->session->password_error)){
					$this->session->set_flashdata('exception', 'pasword not updated');
					}else{
					 	$this->session->set_flashdata('message', display('update_successfully'));   
					}
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
				redirect('user');
			}
		} else {
		     $data['state_list']=$this->location_model->state_list();
		     $data['region_list']=$this->location_model->region_list();
		     $data['county_list']=$this->User_model->display_country();
		 //    $data['companey_list']= $this->doctor_model->reads();
		     $data['department_list'] = $this->Modules_model->modules_list();
		     $data['user_role'] = $this->db->get('tbl_user_role')->result();
		     $data['user_list'] = $this->User_model->user_list();
		     $data['enq_id']='OS/IN/EI/'.str_pad($this->User_model->get_empid(),2, '0', STR_PAD_LEFT);
			 $data['content'] = $this->load->view('user_from',$data,true);
			 $this->load->view('layout/main_wrapper',$data);
		} 
	}
	
	
	
		public function add_partner()
	{ 
	    if(user_role('130')==true){
		}
		$data['title'] = display('add_user');
		$data['page_title'] = 'Channel Partner';
		#-------------------------------#
	//	$this->form_validation->set_rules('company_info', display('company_info') ,'required');
		$this->form_validation->set_rules('Name', display('disolay_name'),'required');
		$this->form_validation->set_rules('cell', display('cell') ,'required|max_length[10]');
		$this->form_validation->set_rules('state_id', display('state_name') ,'required');
		$this->form_validation->set_rules('city_name', display('city_name') ,'required');
		$this->form_validation->set_rules('user_role', display('user_role') ,'required');
		$this->form_validation->set_rules('user_type', display('user_type') ,'required');
	    $this->form_validation->set_rules('modules', display('customer_services'));
		$this->form_validation->set_rules('status', display('status') ,'required');
			if (empty($this->input->post('dprt_id'))) {
	    		$this->form_validation->set_rules('employee_id', display('employee_id') ,'required|is_unique[tbl_admin.employee_id]',array('is_unique'=>'Duplicate Entery For Employee Id '));
	        	$this->form_validation->set_rules('email', display('email') ,'required|is_unique[tbl_admin.s_user_email]',array('is_unique'=>'Duplicate Entery For email'));
		        $this->form_validation->set_rules('password', display('password'),'required|min_length[8]');
	    	    
	    	}
	
		#-------------------------------#
			if(!empty($this->input->post('modules'))){
		$modules=implode(",",$this->input->post('modules'));
		}else{$modules='';}
		if (empty($this->input->post('dprt_id'))) {
		  $password=  md5($this->input->post('password',true));
		}else{
		        // $this->session->set_flashdata('exception','password not updated');
		    $password= $this->input->post('old_pass',true);}
		
		//Upload employee image..
	
         //picture upload
        $img = $this->fileupload->do_upload(
            'assets/images/user/',
            'file'
        );
        // if picture is uploaded then resize the picture
        if ($img !== false && $img != null) {
            $this->fileupload->do_resize(
                $img, 293, 350
            );
        }
        
        if($this->input->post('user_type'))
         {   
            $permission = $this->input->post('user_type');
        }else{
            
            $permission ='';
        }
		
		$data['department'] = (object)$postData = [
			'pk_i_admin_id' 	  => $this->input->post('dprt_id',true),
			'user_roles' 		  => $this->input->post('user_role',true),
          	'user_type' 		  => $this->input->post('user_type',true),
          	'employee_id' 		  => $this->input->post('employee_id',true),
			's_user_email' 		  => $this->input->post('email',true),
			's_phoneno' 		  => $this->input->post('cell',true),
				'second_email' 		  => $this->input->post('second_email',true),
			'second_phone' 		  => $this->input->post('second_phone',true),
			's_password' =>$password,
			'modules'      => $modules,
			's_display_name'      => $this->input->post('Name',true),
			'state_id'      => $this->input->post('state_id',true),
			'city_id' 	  => $this->input->post('city_name',true),
			'companey_id' 		  => 1,
			'partner_type' => $this->input->post('channel_ptnr_types',true),
			'p_type' => $this->input->post('channel_ptnr_typ',true),
			'contact_pname'=>$this->input->post('cname',true),
			'contact_pemail'=>$this->input->post('cemail',true),
			'contact_semail'=>$this->input->post('csemail',true),
			'contact_phone'=>$this->input->post('cphone',true),
			'contact_sphone'=>$this->input->post('csphone',true),
			'user_permissions'=>$permission,
			'last_name'=>$this->input->post('last_name',true),
			'b_status'=>$this->input->post('status',true),
			'date_of_birth'=>$this->input->post('dob',true),
			'anniversary'=>$this->input->post('anniversary',true),
			'orgisation_name'=>$this->input->post('org_name',true),
			'employee_band'=>$this->input->post('employee_band',true),
			'country'=>$this->input->post('country'),
			'region'=>$this->input->post('region',true),
			'territory_name'=>$this->input->post('territory',true),
			'add_ress'=>$this->input->post('address',true),
			'picture'=>(!empty($img)?$img:$this->input->post('new_file')),
			'report_to'=>$this->input->post('report_to',true)
		]; 
		#-------------------------------#
		if ($this->form_validation->run() === true) {
			#if empty $dprt_id then insert data
			if (empty($this->input->post('dprt_id'))) {
				if ($this->User_model->create($postData)) {
					#set success message
					$this->session->set_flashdata('message', display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
				redirect('user/channel_partner');
			} else {
				if ($this->User_model->update($postData)) {
					#set success message
					if(!empty($this->session->password_error)){
					$this->session->set_flashdata('exception', 'pasword not updated');
					}else{
					 	$this->session->set_flashdata('message', display('update_successfully'));   
					}
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
				redirect('user/channel_partner');
			}
		} else {
		     $data['state_list']=$this->location_model->state_list();
		     $data['region_list']=$this->location_model->region_list();
		     $data['county_list']=$this->User_model->display_country();
		   //  $data['companey_list']= $this->doctor_model->reads();
		     $data['department_list'] = $this->Modules_model->modules_list();
		     $data['user_role'] = $this->db->get('tbl_user_role')->result();
		     $data['user_list'] = $this->User_model->user_list();
		   	$data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
		   	$data['type_list'] = $this->User_model->types_list();
			 $data['content'] = $this->load->view('partnerfrom',$data,true);
			 $this->load->view('layout/main_wrapper',$data);
		} 
	}
	
	
	
	public function password_check($str)
{
   if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
       
     return TRUE;
   }
    $this->form_validation->set_message('password_check', 'Password is invalid formate ');
   return FALSE;
}
	public function partner_edit($id = null) 
	{
		$data['title'] = display('edit_user');
		$data['page_title'] = 'Channel Partner';
		#-------------------------------#
		$data['state_list']=$this->location_model->state_list();
		$data['city_list'] = $this->location_model->city_list();
		$data['region_list']=$this->location_model->region_list();
		$data['territory_lsit']=$this->location_model->territory_lsit();
//		$data['companey_list']= $this->doctor_model->reads();
		$data['user_list'] = $this->User_model->user_list();
		$data['department_list'] = $this->Modules_model->modules_list();
		$data['user_role'] = $this->db->get('tbl_user_role')->result();
		$data['department'] = $this->User_model->read_by_id($id);
		$data['county_list'] = $this->location_model->country();
		$data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
		$data['type_list'] = $this->User_model->types_list();
		$data['content'] = $this->load->view('partnerfrom',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
 
 	public function edit($id = null) 
	{
		$data['title'] = display('edit_user');
		#-------------------------------#
		$data['state_list']=$this->location_model->state_list();
		$data['city_list'] = $this->location_model->city_list();
		$data['region_list']=$this->location_model->region_list();
		$data['territory_lsit']=$this->location_model->territory_lsit();
	//	$data['companey_list']= $this->doctor_model->reads();
		$data['user_list'] = $this->User_model->user_list();
		$data['department_list'] = $this->Modules_model->modules_list();
		$data['user_role'] = $this->db->get('tbl_user_role')->result();
		$data['department'] = $this->User_model->read_by_id($id);
		$data['county_list'] = $this->location_model->country();
		//echo "<pre>";
		//print_r($data['county_list']);exit();
		$data['content'] = $this->load->view('user_from',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
 
 
	public function delete($dprt_id = null) 
	{
		if ($this->User_model->delete($dprt_id)) {
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('user');
	}
	
	public function delete_userrole($user_role = null) 
	{ 
		if ($this->User_model->delete_userrole($user_role)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('user/user_type');
	}
	
	public function update_role()
    {  
    
    if(!empty($_POST)){
    $user_type = $this->input->post('user_type');
    $role_id = $this->input->post('role_id');
    
    $this->db->set('user_role',$user_type);
    $this->db->where('use_id',$role_id);
    $this->db->update('tbl_user_role');
    $this->session->set_flashdata('SUCCESSMSG','Update Successfully');
    redirect('user/user_type');
    }
    }
    
    public function update_status()
    {  
    
    if(!empty($_POST)){
    $status = $this->input->post('someSwitchOption001');
    $role_id = $this->input->post('role_id');
    
    $this->db->set('status',$status);
    $this->db->where('use_id',$role_id);
    $this->db->update('tbl_user_role');
    $this->session->set_flashdata('SUCCESSMSG','Update Successfully');
    redirect('user/user_type');
    }
    }
    
    //Define user Permissions..
    public function permissions(){
        
        $data['title'] = display('user_list');
		$data['content'] = $this->load->view('user_permissions',$data,true);
		$this->load->view('layout/main_wrapper',$data);
        
    }
    
    //Open Edit user role form
    public function edit_user_role($role_id){
        
        $data['title'] = display('user_list');
        
        $data['user_role']=$this->User_model->get_user_role($role_id);
        
		$data['content'] = $this->load->view('edit_user_role',$data,true);
		$this->load->view('layout/main_wrapper',$data);
    }
    
    //Save updated user role data
    public function update_user_role(){
        
        $id = $this->input->post('role_id');
        
        $user_role   = $this->input->post('user_type');
        $permissions = $this->input->post('permissions');
        
        $permissions = implode(',',$permissions);
        
        $data = array(
            
            'user_role'        =>$user_role,
            'user_permissions' =>$permissions
            
        );
        
        $this->User_model->update_user_role($id,$data);
        
        $this->session->set_flashdata('SUCCESSMSG','Update Successfully');
        redirect('user/edit_user_role/'.$id);
    }
    

	public function editlocation()
	{
		
		$location_id=$this->input->post('location_id');
		
		$get=$this->db->where('id',$location_id)->get('reporting_location');
		if($get->num_rows()==1){
			foreach ($get->result() as $key => $value) {
				$status=$value->status;
		
				echo'<div class="col-md-12">
				<label>Location Name </label>
				<input type="text" value="'.$value->title.'" name="reploc" class="form-control" id="location">  
			</div> 
			<input name="loc_id" value="'.$location_id.'"  type="hidden" >
			<div class="col-md-12">
				<label>Status </label>
				<div class="form-check">
				<label class="radio-inline">
				<input type="radio" name="status" value="0" ';if($status==0){echo'checked';}
				echo '>Active</label>
				<label class="radio-inline">
				<input type="radio" name="status" value="1" ';if($status==1){echo'checked';}
				echo '>Inactive</label>
				</div>
			</div> ';
			}
		}
		
	}
	
	function reportingLocation_delete(){
		if (user_role('e38') == true) {
		}
		$loc_id=$this->uri->segment('3');
		$count=$this->db->where('id',$loc_id)->count_all_results('reporting_location');
		if($count==0){
			$this->db->where('id',$loc_id)->delete('reporting_location');
			$this->session->set_flashdata('success','Reporting Location Deleted');
			redirect('users/reportingList');
		}else{
			$this->session->set_flashdata('success','Reporting Location not Found');
			redirect('users/reportingList');	
		}
	}

	public function addReportingLocation(){
	
		$reploc=$this->input->post('reploc');
		$status=$this->input->post('status');
		$loc_id=$this->input->post('loc_id');
		if (!empty($loc_id)) {
			if (user_role('e36') == true) {
			}
			$count=$this->db->where('title',$reploc)->where_not_in('id',$loc_id)->count_all_results('reporting_location');
			if($count==0){
				$data=['title'=>$reploc,'status'=>$status,'updated_at'=>date('Y-m-d H:i:s')];
				$insert=$this->db->where('id',$loc_id)->update('reporting_location',$data);
					$this->session->set_flashdata('success','Reporting Location Added');
					redirect('users/reportingList');
				}else{
					$this->session->set_flashdata('error','Reporting Location Already Added');
					redirect('users/reportingList');
				}
		}else{		
			if (user_role('e35') == true) {
			}	
			$count=$this->db->where('title',$reploc)->count_all_results('reporting_location');
			if($count==0){
				$data=['title'=>$reploc,'status'=>$status,'created_by'=>$this->session->user_id,'comp_id'=>$this->session->companey_id];
				$insert=$this->db->insert('reporting_location',$data);
					$this->session->set_flashdata('success','Reporting Location Added');
					redirect('users/reportingList');
			}else{
				$this->session->set_flashdata('error','Reporting Location Already Added');
				redirect('users/reportingList');
			}
		}
	}
	public function reportingList()
	{
		if (user_role('e37') == true) {
		}
		$data['title'] = 'Reporting List';
		$data['reporting_location']=$this->db->where('comp_id',65)->get('reporting_location')->result();
		$data['content'] = $this->load->view('user/reporting_locations',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}  
}