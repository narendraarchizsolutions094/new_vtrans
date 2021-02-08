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
$type = $this->input->post('type');

$zone_id = $this->input->post('zone'); //$type=='zone'?$this->input->post('zone'):0;
$branch_id=$this->input->post('branch_id');

if (!empty($branch_id)) {
	if (user_role('d35') == true) {
	}
$count=$this->db->where(array('branch_name'=>$branch,'comp_id'=>$this->session->companey_id))->where_not_in('branch_id',$branch_id)->count_all_results('branch');
    if($count==0){
		$data=['branch_name'=>$branch,'branch_status'=>$status,'type'=>$type,'zone'=>$zone_id,'updated_at'=>date('Y-m-d H:i:s')];
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
 $count=$this->db->where(array('branch_name'=>$branch,'comp_id'=>$this->session->companey_id,'type'=>$type,'zone'=>$zone_id))->count_all_results('branch');
if($count==0){
	
$data=['branch_name'=>$branch,'type'=>$type,'zone'=>$zone_id,'branch_status'=>$status,'created_by'=>$this->session->user_id,'comp_id'=>$this->session->companey_id];
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
	$this->load->model('Branch_model');
	if (user_role('d37') == true) {
	}
	$data['page_title'] = 'Branch List';
	$data['common_list']=$this->Branch_model->common_list()->result();
	$data['zone_list']=$this->Branch_model->zone_list()->result();
	$data['content'] = $this->load->view('branch/list',$data,true);
	$this->load->view('layout/main_wrapper',$data);
}
public function add_vehicle_type()
{
	if($this->input->post())
	{
		$this->load->model('Branch_model');

		$data = array('comp_id'=>$this->session->companey_id,
						'type_name'=>$this->input->post('type_name'),
						'created_by'=>$this->session->user_id,
						'status'=>$this->input->post('status'),
					);
		$this->Branch_model->add_vehicle_type($data);
		$this->session->set_flashdata('message','Vechile Added Successfully.');
		redirect(site_url('setting/add_vehicle_type'));
	}
	else
	{
		$data['page_title'] = 'Vehicle Type List';
		$data['vehicle_list']=$this->db->where('comp_id',$this->session->companey_id)->get('vehicle')->result();
		$data['content'] = $this->load->view('branch/vehicle',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
}
public function vehicle_delete($id)
{
	$this->load->model('Branch_model');
	$where = array('vehicle_type_id'=>$id);
	$this->Branch_model->delete_vehicle($where);
	$this->session->set_flashdata('message','Vehicle Deleted Successfully');
	redirect(site_url('setting/add_vehicle_type'));
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
	$this->load->model('Branch_model');

	$data['page_title'] = 'Branch Rate List';
	$data['branch'] = $this->Branch_model->common_list()->result();
	$data['branch_list']=$this->db->select('bb.branch_name as from,bb.type as btype,bs.branch_name as to,bs.type as dtype,branchwise_rate.*,z.name as zone_name')
	->join('branch bb','bb.branch_id=branchwise_rate.booking_branch')
	->join('branch bs','bs.branch_id=branchwise_rate.delivery_branch')
	->join('zones z','bs.zone=z.zone_id','left')
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
// if ($dbranch==$bbranch) {
// 	$this->session->set_flashdata('exception','Select Different Delivery Branch');
// 	redirect('setting/branch_rateList');
// }
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
	$this->load->model('Branch_model');
	$branch_id = $this->input->post('branch_id');	
	$get=$this->Branch_model->common_list(array('branch.branch_id'=>$branch_id));
	$zone_list = $this->Branch_model->zone_list()->result();
	//print_r($zone_list);
	if($get->num_rows()==1){
		foreach ($get->result() as $key => $value) 
		{
			$status=$value->branch_status;
	
	echo'<div class="row">
			<div class="form-group">
				<label>Branch Name </label>
				<input type="text" name="branch" class="form-control" value="'.$value->branch_name.'">
			</div>
			<input type="hidden" name="branch_id" value="'.$value->branch_id.'">
			<div class="form-group">
				<label>Type </label>
				<select class="form-control" name="type" onchange="{//if(this.value==\'zone\')$(\'#zone_box2\').show(); else $(\'#zone_box2\').hide();}">
					<option value="branch" '.($value->type=='branch'?'selected':'').'>Branch</option>
					<!--<option value="zone" '.($value->type=='zone'?'selected':'').'>Zone</option>-->
					<option value="area" '.($value->type=='area'?'selected':'').'>Area</option>
				</select>
			</div>
			<div id="zone_box2" class="form-group" style="'.($value->type!='zone'?'display: block;':'').'">
				<label>Select Zone </label>
				<select class="form-control" name="zone">';
				if(!empty($zone_list))
				{
					foreach ($zone_list as $key => $zone)
					{
						echo'<option value="'.$zone->zone_id.'" '.($zone->zone_id==$value->zone?'selected':'').'>'.$zone->name.'</option>';
					}
				}
					
			echo'</select>
			</div>
			<div class="form-group">
				<label>Status </label>
				<div class="form-check" style="width: 100%; padding: 0px 10px;">
					<label class="radio-inline">
					<input type="radio" name="status" value="1" '.($value->branch_status?'checked':'').'>Active</label>
					<label class="radio-inline">
					<input type="radio" name="status" value="0" '.($value->branch_status?'':'checked').'>Inactive</label>
				</div>
			</div>
  		</div>';
		}
	}
	
}
public function load_branchs()
{
	if(!empty($_POST))
	{	
		$this->load->model('Branch_model');
		$key = $this->input->post('key');
		$type = $this->input->post('dtype');
		$sel = explode(',', $this->input->post('sel'))??array();

		if($key=='branch' || $key=='area')
		{
			$res = $this->Branch_model->common_list(array('branch.type'=>$key))->result();
			foreach($res as $key => $val)
			{	
				echo'<option value="'.$val->branch_id.'" '.(in_array($val->branch_id,$sel)?'selected':'').'>'.$val->branch_name.'</option>';
			}
		}
		else if($key=='zone')
		{
			$res = $this->Branch_model->zone_list()->result();
			$data=	$this->db->select('zone_id')
						->from('zones z')
						->join('branch b ','z.zone_id=b.zone','left')
						->where_in('b.branch_id',$sel)
						->get()->result();
			$sel = array_column($data,'zone_id');
			foreach($res as $key => $val)
			{
				echo'<option value="'.$val->zone_id.'" '.(in_array($val->zone_id,$sel)?'selected':'').'>'.$val->name.'</option>';
			}
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

public function edit_vehicle_type()
{ //sleep(4);
	$this->load->model('Branch_model');
	if($this->input->post('task')=='edit')
	{
		$id = $this->input->post('vid');
		$res = $this->Branch_model->get_vehicles($id)->row();
		echo'
		<div class="row" style="text-align:left">
		<input type="hidden" name="vid" value="'.$id.'">
		<input type="hidden" name="task" value="save">
               <div class="form-group">
                  <label>Vehicle Type Name </label>
                  <input type="text" name="type_name" class="form-control" value="'.$res->type_name.'">
                </div>
                <div class="form-group">
                  <label>Status </label>
                  <div class="form-check" style="width: 100%; padding: 0px 10px;">
                    <label class="radio-inline">
                      <input type="radio" name="status" value="1" '.($res->status?'checked':'').'>Active</label>
                    <label class="radio-inline">
                      <input type="radio" name="status" value="0" '.($res->status?'':'checked').'>Inactive</label>
                  </div>
                </div>
          </div>';
	}
	else if($this->input->post('task')=='save')
	{
		$id = $this->input->post('vid');
		$data = array(	'type_name'=>$this->input->post('type_name'),
						'status'=>$this->input->post('status'),
					);
		$this->Branch_model->save_vehicle_type($id,$data);
		$this->session->set_flashdata('message','Saved Successfully');
		redirect(site_url('setting/add_vehicle_type'));
	}
}

public function discount_matrix()
{
	if($this->input->post())
	{
		if($_POST['discount']>100 || $_POST['discount']<0)
				$_POST['discount'] =0;
		$data= array(
				'name'=>$this->input->post('name'),
				'discount'=>$this->input->post('discount'),
				'comp_id'=>$this->session->companey_id,
		);
		$this->db->insert('discount_matrix',$data);
		$this->session->set_flashdata('message','Saved! ');
		redirect(base_url('setting/discount_matrix'));
	}
	else
	{

		$data['title']= 'Discount Matrix';
		$data['list'] = $this->db->where('comp_id',$this->session->companey_id)->get('discount_matrix')->result();
		$data['content'] = $this->load->view('discount_matrix',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
}

public function oda_matrix()
{
	$this->load->model('Branch_model');
	if($this->input->post())
	{
		if($_POST['charge']<0)
				$_POST['charge'] =0;
		$data= array(
				'distance_from'=>$this->input->post('distance_from'),
				'distance_to'=>$this->input->post('distance_to'),
				'weight_from'=>$this->input->post('weight_from'),
				'weight_to'=>$this->input->post('weight_to'),
				'charge'=>$this->input->post('charge'),
				'comp_id'=>$this->session->companey_id,
		);
		$this->db->insert('oda_matrix',$data);
		$this->session->set_flashdata('message','Saved! ');
		redirect(base_url('setting/oda_matrix'));
	}
	else
	{

		$data['title']= 'ODA Matrix';
		$data['list'] = $this->Branch_model->oda_list();
		$data['content'] = $this->load->view('oda_matrix',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
}

public function bank_details()
{
	$this->load->model('Branch_model');
	if($this->input->post())
	{

		$data= array(
				'bank_branch'=>$this->input->post('bank_branch'),
				'account_no'=>$this->input->post('ac_no'),
				'ifsc'=>$this->input->post('ifsc'),
				'zone_id'=>$this->input->post('zone_id'),
				'comp_id'=>$this->session->companey_id,
		);

		$chk = $this->db->where('zone_id',$data['zone_id'])->get('bank_details')->num_rows();
		if($chk)
		{
			$this->session->set_flashdata('exception','Bank Details already Exist for selected zone! ');
		}else
		{
			$this->db->insert('bank_details',$data);
			$this->session->set_flashdata('message','Saved! ');
		}

		redirect(base_url('setting/bank_details'));
	}
	else
	{

		$data['title']= 'Bank Details';
		$data['zone_list']= $zone_list = $this->Branch_model->zone_list()->result();
		$data['bank_list']= $this->Branch_model->bank_list();

		$data['content'] = $this->load->view('bank_details',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
}

public function edit_discount()
{
	if($this->input->post())
	{
		if($this->input->post('task')=='view')
		{
			$this->load->model('Branch_model');
			$list = $this->Branch_model->discount_list($this->input->post('id'));
			$res = $list[0];

			echo'
			<form action="'.base_url('setting/edit_discount').'" method="post">
			<div class="panel-body" style="text-align:left">
				<input type="hidden" name="task" value="save">
				<input type="hidden" name="id" value="'.$this->input->post('id').'">
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" class="form-control" value="'.$res->name.'" required>
				</div>
				<div class="form-group">
					<label>Allowed Discount (%)</label>
					<input type="number" name="discount" class="form-control" value="'.$res->discount.'" onkeyup="{
						if(this.value>100 || this.value <0)
							this.value=0;
						}" required>
				</div>
			</div>
			<div class="">
				<div class="form-group">
						<button type="submit" class="btn btn-primary">Save</button>
						<button type="button" class="btn btn-danger" onclick="Swal.close()">Cancel</button>
					</div>
			</div>
			</form>
			';
		}
		else if($this->input->post('task')=='save')
		{
			unset($_POST['task']);
			if($_POST['discount']>100 || $_POST['discount']<0)
				$_POST['discount'] =0;

			$this->db->where('id',$this->input->post('id'))
						->where('comp_id',$this->session->companey_id)
					->update('discount_matrix',$_POST);
		$this->session->set_flashdata('message','Saved');
			redirect(base_url('setting/discount_matrix'));
		}
	}
}

public function edit_bank()
{
	if($this->input->post())
	{
		if($this->input->post('task')=='view')
		{
			$this->load->model('Branch_model');
			$list = $this->Branch_model->bank_list($this->input->post('id'));
			$zone_list  = $this->Branch_model->zone_list()->result();
			$res = $list[0];

			echo'
			<form action="'.base_url('setting/edit_bank').'" method="post" >
			<div class="panel-body" style="text-align:left">
				<input type="hidden" name="task" value="save">
				<input type="hidden" name="id" value="'.$this->input->post('id').'">
				<div class="form-group">
					<label>Branch Name</label>
					<input type="text" name="bank_branch" value="'.$res->bank_branch.'" class="form-control">
				</div>
				<div class="form-group">
					<label>Collection Account No.</label>
					<input type="text" name="ac_no" value="'.$res->account_no.'" class="form-control">
				</div>
				<div class="form-group">
					<label>IFSC Code</label>
					<input type="text" name="ifsc" value="'.$res->ifsc.'" class="form-control">
				</div>
				<div class="form-group">
					<label>Zone</label>
					<select name="zone_id" class="form-control">';
						if(!empty($zone_list))
						{
							foreach ($zone_list as $key => $zone)
							{
								echo'<option value="'.$zone->zone_id.'" '.($zone->zone_id==$res->zone_id?'selected':'').'>'.$zone->name.'</option>';
							}
						}
			echo'</select>
				</div>
				<div class="form-group">
						<button type="submit" class="btn btn-primary">Save</button>
						<button type="button" class="btn btn-danger" onclick="Swal.close()">Cancel</button>
				</div>
			</div>
			</form>
			';
		}
		else if($this->input->post('task')=='save')
		{
			
			$data= array(
				'bank_branch'=>$this->input->post('bank_branch'),
				'account_no'=>$this->input->post('ac_no'),
				'ifsc'=>$this->input->post('ifsc'),
				'zone_id'=>$this->input->post('zone_id'),
				'comp_id'=>$this->session->companey_id,
			);

			$this->db->where('id',$this->input->post('id'))->update('bank_details',$data);
			$this->session->set_flashdata('message','Saved! ');
			redirect(base_url('setting/bank_details'));
		}
	}
}

public function edit_oda()
{
	if($this->input->post())
	{
		if($this->input->post('task')=='view')
		{
			$this->load->model('Branch_model');
			$list = $this->Branch_model->oda_list($this->input->post('id'));
			$res = $list[0];

			echo'
			<form action="'.base_url('setting/edit_oda').'" method="post"  style="text-align:left">
			<input type="hidden" value="save" name="task">
			<input type="hidden" value="'.$res->id.'" name="id">
			<div class="form-group">
					<label>Distance (In KM)</label>
					<div>
					<div style="width: 49%; display: inline-block;">	
						<input type="text" name="distance_from" class="form-control" placeholder="From" value="'.$res->distance_from.'" required>
					</div>
					<div style="width: 49%; display: inline-block;">
						<input type="text" name="distance_to"  value="'.$res->distance_to.'" class="form-control" placeholder="To" required>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label>Weight (In KG)</label>
					<div>
					<div style="width: 49%; display: inline-block;">	
						<input type="text" name="weight_from" class="form-control" placeholder="From"  value="'.$res->weight_from.'" required>
					</div>
					<div style="width: 49%; display: inline-block;">
						<input type="text" name="weight_to"  value="'.$res->weight_to.'" class="form-control" placeholder="To" required>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label>Charge (Rs.)</label>
					<input type="number" name="charge"  value="'.$res->charge.'" class="form-control" required onkeyup="{
						if(this.value!=\'\' && this.value <0)
							this.value=0;
						}">
				</div>
				<div class="form-group">
						<button class="btn btn-primary">Save</button>
						<button type="button" class="btn btn-danger" onclick="Swal.close()">Close</button>
					</div>
			</form>
			';
		}
		else if($this->input->post('task')=='save')
		{
			unset($_POST['task']);
			if($_POST['charge']=='')
				$_POST['discount'] =0;

			$this->db->where('id',$this->input->post('id'))
						->where('comp_id',$this->session->companey_id)
					->update('oda_matrix',$_POST);
		$this->session->set_flashdata('message','Saved');
			redirect(base_url('setting/oda_matrix'));
		}
	}
}



public function oda_calculate()
{
	if(!empty($this->input->post()))
	{
		$dis = !empty($this->input->post('dis'))?$this->input->post('dis'):0;
		$we = !empty($this->input->post('we'))?$this->input->post('we'):0;
	
	$res =	$this->db->where("distance_from <= $dis and distance_to >= $dis and weight_from <= $we and weight_to >= $we ")->get('oda_matrix')->row();
	$charge = 0;
		if(!empty($res))
		{
			$charge = $res->charge;
		}
		echo $charge;
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

public function competitor_delete()
{
	if (user_role('d38') == true) {
	}
	$branch_id=$this->uri->segment(3);
	$get=$this->db->where(array('id'=>$branch_id,'comp_id'=>$this->session->companey_id))->get('competitors');
	if($get->num_rows()==1){
		$this->db->where('id',$branch_id)->delete('competitors');
		$this->session->set_flashdata('message','Competitor Deleted');
	    redirect('setting/competitorList');
	}else{
		$this->session->set_flashdata('exception','Competitor not found');
	    redirect('setting/competitorList');
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
	if (user_role('e34') == true) {
	}
	$data['page_title'] = 'Template List';
	$data['list']=$this->db->where('comp_id',$this->session->companey_id)->get('tbl_docTemplate')->result();
	$data['content'] = $this->load->view('setting/document-templates',$data,true);
	$this->load->view('layout/main_wrapper',$data);
}

public function expensemaster()
{		
	// if (user_role('e34') == true) {
	// }

	$data['page_title'] = 'Expense Master List';
	$data['list']=$this->db->where(array('comp_id'=>$this->session->companey_id))->get('tbl_expenseMaster')->result();
	$data['content'] = $this->load->view('setting/expense',$data,true);
	$this->load->view('layout/main_wrapper',$data);
}
public function add_expense()
{
	if($_POST){
		$title=$this->input->post('title');
		$status=$this->input->post('status');
		$expense_id=$this->input->post('expense_id');
		if(!empty($expense_id)){
			$comp_id=$this->session->companey_id;
			$data=['title'=>$title,'status'=>$status];
			$this->db->where(array('comp_id'=>$comp_id,'id'=>$expense_id))->update('tbl_expenseMaster',$data);
			$this->session->set_flashdata('message','Expense Updated');
			redirect('setting/expense-master');
		}else{
		$comp_id=$this->session->companey_id;
		$data=['title'=>$title,'status'=>$status,'comp_id'=>$comp_id,'created_by'=>$this->session->user_id];
		$this->db->insert('tbl_expenseMaster',$data);
		$this->session->set_flashdata('message','Expense Added');
	    redirect('setting/expense-master');
		}
	}
}
public function delete_expense()
{

	$id=$this->uri->segment('3');
	if(!empty($id)){
		$comp_id=$this->session->companey_id;
		$this->db->where(array('comp_id'=>$comp_id,'id'=>$id))->delete('tbl_expenseMaster');
		$this->session->set_flashdata('message','Expense Deleted');
	    redirect('setting/expense-master');
	}
}
public function visit_expense_delete()
{
	$id=$this->uri->segment('3');
	if(!empty($id)){
		$comp_id=$this->session->companey_id;
		// $expense=$this->db->where(array('comp_id'=>$comp_id,'id'=>$id))->get('tbl_expense')->row();
		// $file=$expense->file;
		$user_id=$this->session->user_id;
		$this->db->where(array('comp_id'=>$comp_id,'id'=>$id,'created_by'=>$user_id))->delete('tbl_expense');
		$this->session->set_flashdata('message','Expense Deleted');
		redirect($this->agent->referrer());
	}
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
