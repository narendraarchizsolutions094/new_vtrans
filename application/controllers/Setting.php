<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'setting_model'
		));

		/*if ($this->session->userdata('isLogIn') == false 
			|| $this->session->userdata('user_role') != 1 
		){
		    
		    	redirect('login'); 
		}*/
	
	}
 

	public function index()
	{
		$data['title'] = display('application_setting');
		#-------------------------------#
		//check setting table row if not exists then insert a row
		$this->check_setting();
		#-------------------------------#
		$data['languageList'] = $this->languageList(); 
		$data['setting'] = $this->setting_model->read($this->session->companey_id);
		$data['content'] = $this->load->view('setting',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	} 

	public function create()
	{
		$data['title'] = display('application_setting');
		#-------------------------------#
		$this->form_validation->set_rules('title',display('website_title'),'required|max_length[50]');
		$this->form_validation->set_rules('description', display('address') ,'max_length[255]');
		$this->form_validation->set_rules('email',display('email'),'max_length[100]|valid_email');
		$this->form_validation->set_rules('phone',display('phone'),'max_length[20]');
		$this->form_validation->set_rules('language',display('language'),'max_length[250]'); 
		$this->form_validation->set_rules('footer_text',display('footer_text'),'max_length[255]'); 
		$this->form_validation->set_rules('time_zone',display('time_zone'),'required|max_length[100]'); 
		#-------------------------------#
		//logo upload
		$logo = $this->fileupload->do_upload(
			'assets/images/apps/',
			'logo'
		);
		// if logo is uploaded then resize the logo
		if ($logo !== false && $logo != null) {
			// $this->fileupload->do_resize(
			// 	$logo, 
			// 	210,
			// 	48
			// );
		}
		//if logo is not uploaded
		if ($logo === false) {
			$this->session->set_flashdata('exception', display('invalid_logo'));
		}


		//favicon upload
		$favicon = $this->fileupload->do_upload(
			'assets/images/icons/',
			'favicon'
		);
		// if favicon is uploaded then resize the favicon
		if ($favicon !== false && $favicon != null) {
			$this->fileupload->do_resize(
				$favicon, 
				32,
				32
			);
		}
		//if favicon is not uploaded
		if ($favicon === false) {
			$this->session->set_flashdata('exception',  display('invalid_favicon'));
		}		
		#-------------------------------#

		$data['setting'] = (object)$postData = [
			'setting_id'  => $this->input->post('setting_id'),
			'title' 	  => $this->input->post('title'),
			'description' => $this->input->post('description', false),
			'email' 	  => $this->input->post('email'),
			'phone' 	  => $this->input->post('phone'),
			'logo' 	      => (!empty($logo)?$logo:$this->input->post('old_logo')),
			'favicon' 	  => (!empty($favicon)?$favicon:$this->input->post('old_favicon')),
			'language'    => $this->input->post('language'), 
			'time_zone'   => $this->input->post('time_zone'), 
			'site_align'  => $this->input->post('site_align'), 
			'footer_text' => $this->input->post('footer_text', false),
			'comp_id'     => $this->session->companey_id,
			'domain'     => $_SERVER['HTTP_HOST'],
		]; 
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			#if empty $setting_id then insert data
			if (empty($postData['setting_id'])) {
				if ($this->setting_model->create($postData)) {
					#set success message
					$this->session->set_flashdata('message',display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
			} else {
				if ($this->setting_model->update($postData)) {
					#set success message
					$this->session->set_flashdata('message',display('update_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
				} 
			}

			//update session data
			$this->session->set_userdata([
				'title' 	  => $postData['title'],
				'address' 	  => $postData['description'],
				'email' 	  => $postData['email'],
				'phone' 	  => $postData['phone'],
				'logo' 		  => $postData['logo'],
				'favicon' 	  => $postData['favicon'],
				'language'    => $postData['language'], 
				'footer_text' => $postData['footer_text'],
				'time_zone'   => $postData['time_zone'],
			]);

			redirect('setting');

		} else { 
			$data['languageList'] = $this->languageList(); 
			$data['content'] = $this->load->view('setting',$data,true);
			$this->load->view('layout/main_wrapper',$data);
		} 
	}

	public function enquiryDuplicacySetting()
	{	
		$data['title']		= "Enquiry Duplicacy";
		$data['ruledata']	= $this->db->select("*")->from("tbl_new_settings")->where('comp_id',$this->session->companey_id)->get()->result_array();
		$data['content'] 	= $this->load->view('enq_duplicacy_setting',$data,true);
			$this->load->view('layout/main_wrapper',$data);
	}
	public function saveEnquiryRule()
	{	
		//print_r($_POST);die;
		$data = array(
			'comp_id'					=> $this->session->companey_id,
			'duplicacy_status'			=> $this->input->post('allowornot'),
			'field_for_identification'	=> $this->input->post('fields'),
			'status'					=> 1,
		);
		if(!empty($this->input->post('ruleid')))
		{	
			$this->db->where('id',$this->input->post('ruleid'));
			$this->db->update('tbl_new_settings',$data);
			$this->session->set_flashdata("msg","Updated Successfully");
			redirect('setting/enquiryDuplicacySetting');
		}
		else
		{
			$insert = $this->db->insert('tbl_new_settings',$data);
			$this->session->set_flashdata("msg","Inserted Successfully");
			redirect('setting/enquiryDuplicacySetting');
		}
		
		
	}
	public function deleteEnquiryRule($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('tbl_new_settings');
		$this->session->set_flashdata("msg","Deleted Successfully");
		redirect('setting/enquiryDuplicacySetting');
	}
	//check setting table row if not exists then insert a row
	public function check_setting()
	{
		if ($this->db->count_all('setting') == 0) {
			$this->db->insert('setting',[
				'title' => 'Demo Hospital Limited',
				'description' => '123/A, Street, State-12345, Demo',
				'time_zone' => 'Asia/Dhaka',
				'footer_text' => '2016&copy;Copyright',
			]);
		}
	}


    public function languageList()
    { 
        if ($this->db->table_exists("language")) { 

                $fields = $this->db->field_data("language");

                $i = 1;
                foreach ($fields as $field)
                {  
                    if ($i++ > 2)
                    $result[$field->name] = ucfirst($field->name);
                }

                if (!empty($result)) return $result;
 

        } else {
            return false; 
        }
    }
    
    //Change password load view....
    public function change_password(){
        
        $data['page_title'] = 'Change password';
		
		$data['content'] = $this->load->view('change-password',$data,true);
		$this->load->view('layout/main_wrapper',$data);
    }
    
    //change password..
    public function update_password(){
        
        $oldpas    = md5($this->input->post('oldpass'));
        $newpass   = md5($this->input->post('newpass'));
        $confrpass = md5($this->input->post('confirmpass'));
        
        if($newpass!=$confrpass){
            
            $this->session->set_flashdata('error','Confirm password is not matched');
            redirect('setting/change_password');
            exit();
        }
        
        
        if($this->setting_model->update_pass($oldpas,$newpass)==TRUE){
            
            $this->session->set_flashdata('success','Your password has changed successfully...');
            redirect('setting/change_password');
            
        }else{
            
            $this->session->set_flashdata('error','Your old password is not matched...');
            redirect('setting/change_password');
            
        }
        
        
    }

public function addbranch()
{
	
$branch=$this->input->post('branch');
$status=$this->input->post('status');
$branch_id=$this->input->post('branch_id');

if (!empty($branch_id)) {
	if (user_role('d35') == true) {
	}
$count=$this->db->where(array('branch_name'=>$branch,'comp_id'=>$this->session->companey_id))->where_not_in('branch_id',$branch_id)->count_all_results('branch');
    if($count==0){
		$data=['branch_name'=>$branch,'branch_status'=>$status,'updated_at'=>date('Y-m-d H:i:s')];
		$insert=$this->db->where('branch_id',$branch_id)->update('branch',$data);
			$this->session->set_flashdata('message','Branch Updated');
			redirect('setting/branchList');
		}else{
			$this->session->set_flashdata('exception','Branch Already Added');
			redirect('setting/branchList');
		}
}else{
	if (user_role('d36') == true) {
	}
 $count=$this->db->where(array('branch_name'=>$branch,'comp_id'=>$this->session->companey_id))->count_all_results('branch');
if($count==0){
	
$data=['branch_name'=>$branch,'branch_status'=>$status,'created_by'=>$this->session->user_id,'comp_id'=>$this->session->companey_id];
$insert=$this->db->insert('branch',$data);
	$this->session->set_flashdata('message','Branch Added');
	redirect('setting/branchList');
}else{
	$this->session->set_flashdata('exception','Branch Already Added');
	redirect('setting/branchList');
}
}
}

public function addcompetitor()
{	
	$branch=$this->input->post('branch');
	$status=$this->input->post('status');
	$branch_id=$this->input->post('branch_id');

	if (!empty($branch_id)) {
		if (user_role('d35') == true) {
		}
	$count=$this->db->where(array('name'=>$branch,'comp_id'=>$this->session->companey_id))->where_not_in('id',$branch_id)->count_all_results('competitors');
    if($count==0){
		$data=['name'=>$branch,'status'=>$status,'updated_at'=>date('Y-m-d H:i:s')];
		$insert=$this->db->where('id',$branch_id)->update('competitors',$data);
			$this->session->set_flashdata('message','Competitor Updated');
			redirect('setting/competitorList');
		}else{
			$this->session->set_flashdata('exception','Competitor Already Added');
			redirect('setting/competitorList');
		}
	}else{
		if (user_role('d36') == true) {
		}
	$count=$this->db->where(array('name'=>$branch,'comp_id'=>$this->session->companey_id))->count_all_results('competitors');
	if($count==0){
		
	$data=['name'=>$branch,'status'=>$status,'created_by'=>$this->session->user_id,'comp_id'=>$this->session->companey_id];
	$insert=$this->db->insert('competitors',$data);
		$this->session->set_flashdata('message','Competitor Added');
		redirect('setting/competitorList');
	}else{
		$this->session->set_flashdata('exception','Competitor Already Added');
		redirect('setting/competitorList');
	}
	}
}

public function branchList()
{
	if (user_role('d37') == true) {
	}
	$data['page_title'] = 'Branch List';
	$data['branch_list']=$this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();
	$data['content'] = $this->load->view('branch/list',$data,true);
	$this->load->view('layout/main_wrapper',$data);
}
public function competitorList()
{
	if (user_role('d37') == true) {}
	$data['page_title'] = 'Competitor List';
	$data['competitor_list']=$this->db->where('comp_id',$this->session->companey_id)->get('competitors')->result();
	$data['content'] = $this->load->view('competitor/list',$data,true);
	$this->load->view('layout/main_wrapper',$data);
}
public function branch_rateList()
{
	if (user_role('e30') == true) {
	}
	$data['page_title'] = 'Branch Rate List';
	$data['branch_list']=$this->db->select('bb.*,bs.branch_name as bn,branchwise_rate.*')
	->join('branch bb','bb.branch_id=branchwise_rate.booking_branch')
	->join('branch bs','bs.branch_id=branchwise_rate.delivery_branch')
	->get('branchwise_rate')->result();
	$data['content'] = $this->load->view('branch/rate-list',$data,true);
	$this->load->view('layout/main_wrapper',$data);
}
public function addbranch_rate()
{

$bbranch=$this->input->post('bbranch');
$dbranch=$this->input->post('dbranch');
$rate=$this->input->post('rate');
$status=$this->input->post('status');
$id=$this->input->post('rateid');
if ($dbranch==$bbranch) {
	$this->session->set_flashdata('exception','Select Different Delivery Branch');
	redirect('setting/branch_rateList');
}
if(empty($id)){
	if (user_role('d39') == true) {
	}
$count=$this->db->where(array('booking_branch'=>$bbranch,'delivery_branch'=>$dbranch,'comp_id'=>$this->session->companey_id))->count_all_results('branchwise_rate');
if($count==0){
    $data=['booking_branch'=>$bbranch,'rate'=>$rate,'delivery_branch'=>$dbranch,'rate_status'=>$status,'created_by'=>$this->session->user_id,'comp_id'=>$this->session->companey_id];
    $this->db->insert('branchwise_rate',$data);
	$this->session->set_flashdata('message','Branch rate Added');
	redirect('setting/branch_rateList');
}else{
	$this->session->set_flashdata('exception','Rate Already Added');
	redirect('setting/branch_rateList');
}
}else{
	if (user_role('e30') == true) {
	}
		$data=['booking_branch'=>$bbranch,'rate'=>$rate,'delivery_branch'=>$dbranch,'rate_status'=>$status,'comp_id'=>$this->session->companey_id];
    	$this->db->where(array('comp_id'=>$this->session->companey_id,'id'=>$id))->update('branchwise_rate',$data);
		$this->session->set_flashdata('success','Branch rate updated');
		redirect('setting/branch_rateList');
}
}
public function editbranch()
{
	
	$branch_id=$this->input->post('branch_id');
	
	$get=$this->db->where('branch_id',$branch_id)->get('branch');
	if($get->num_rows()==1){
		foreach ($get->result() as $key => $value) {
			$status=$value->branch_status;
	
			echo'<div class="col-md-12">
			<label>Branch Name </label>
			<input type="text" value="'.$value->branch_name.'" name="branch" class="form-control" id="branch">  
		</div> 
		<input name="branch_id" value="'.$branch_id.'"  type="hidden" >
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

public function editcompetitor()
{
	
	$branch_id=$this->input->post('branch_id');
	
	$get=$this->db->where('id',$branch_id)->get('competitors');
	if($get->num_rows()==1){
		foreach ($get->result() as $key => $value) {
			$status=$value->status;
	
			echo'<div class="col-md-12">
			<label>Competitor Name </label>
			<input type="text" value="'.$value->name.'" name="branch" class="form-control" id="branch">  
		</div> 
		<input name="branch_id" value="'.$id.'"  type="hidden" >
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
public function editbranchrate()
{
	if (user_role('e30') == true) {
	}
	$id=$this->uri->segment(3);
	$get=$this->db->where('id',$id)->get('branchwise_rate');
	if($get->num_rows()==1){
        
        $data['page_title'] = 'Edit Branch Rate';
        $data['rate'] = $get->result();
		$data['content'] = $this->load->view('branch/edit-rate',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
	
}
public function branch_delete()
{
	if (user_role('d38') == true) {
	}
	$branch_id=$this->uri->segment(3);
	$get=$this->db->where(array('branch_id'=>$branch_id,'comp_id'=>$this->session->companey_id))->get('branch');
	if($get->num_rows()==1){
		$this->db->where('branch_id',$branch_id)->delete('branch');
		$this->session->set_flashdata('message','Branch Deleted');
	    redirect('setting/branchList');
	}else{
		$this->session->set_flashdata('exception','Branch  not found');
	    redirect('setting/branchList');
	}
	
}
public function branchrate_delete()
{
	if (user_role('e32') == true) {
	}
	$id=$this->uri->segment(3);
	$get=$this->db->where(array('id'=>$id,'comp_id'=>$this->session->companey_id))->get('branchwise_rate');
	if($get->num_rows()==1){
		$this->db->where(array('id'=>$id,'comp_id'=>$this->session->companey_id))->delete('branchwise_rate');
		$this->session->set_flashdata('message','Rate Deleted');
	    redirect('setting/branch_rateList');
	}else{
		$this->session->set_flashdata('exception','Rate  not found');
	    redirect('setting/branch_rateList');
	}
	
}
public function document_templates()
{		
	if (user_role('3143') == true) {
	}
	$data['page_title'] = 'Template List';
	$data['list']=$this->db->where('comp_id',$this->session->companey_id)->get('tbl_docTemplate')->result();
	$data['content'] = $this->load->view('setting/document-templates',$data,true);
	$this->load->view('layout/main_wrapper',$data);
}
public function createdocument_templates()
{		
	if (user_role('e33') == true) {
	}
	$id=$this->uri->segment('3');
	if(!empty($id)){
	$data['docList']=$this->db->where(array('comp_id'=>$this->session->companey_id,'id'=>$id))->get('tbl_docTemplate')->result();
	$data['page_title'] = 'Branch List';
	$data['content'] = $this->load->view('setting/create-document-template',$data,true);
	$this->load->view('layout/main_wrapper',$data);}else{
		$this->session->set_flashdata('exception','Template not found');
	    redirect('setting/document-templates');
	}
}
public function Insert_templates()
{		
	if (user_role('e33') == true) {
	}
	$user_id=$this->session->user_id;
	 $id=$this->input->post('docId');
	 $title=$this->input->post('title');
	$content=$this->input->post('content');
	$count=$this->db->where(array('comp_id'=>$this->session->companey_id,'id'=>$id))->get('tbl_docTemplate');
	if($count->num_rows()==1){
		$data=['title'=>$title,'content'=>$content,'doc_type'=>1,'created_by'=>$user_id,'comp_id'=>$this->session->companey_id];
		$this->db->where(array('comp_id'=>$this->session->companey_id,'id'=>$id))->update('tbl_docTemplate',$data);
		$this->session->set_flashdata('message','Template updated');
	    redirect('setting/document-templates');
	}else{
		$this->session->set_flashdata('exception','Template not found');
	    redirect('setting/document-templates');
	}
}
}
