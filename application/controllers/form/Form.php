<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Form extends CI_Controller {
	public function __construct()
	{
		parent::__construct();		
	
		 $this->load->model(
                array('Leads_Model','setting_model' ,'enquiry_model', 'dashboard_model', 'Task_Model', 'User_model', 'location_model','Message_models','Institute_model','Datasource_model','Taskstatus_model','dash_model','Center_model','SubSource_model','Kyc_model','Education_model','SocialProfile_model','Closefemily_model','form_model','doctor_model')
                );
		if(empty($this->session->user_id)){
		 redirect('login');   
		}
	} 	
 	
 	public function enquiry_extra_field($comp_id,$tab_id=1,$form_for=0){ // add extra fileds to enquiry form 		
 		$data['title'] = 'Enquiry Form'; 		
 		$data['comp_id'] = $comp_id;
 		$data['tab_id'] = $tab_id; 		
 		//echo $form_for;
 		$data['tab_list'] = $this->form_model->get_tabs_list($comp_id,0,$form_for);  
 		$data['field_for']  = $form_for;					
 		//echo $data['tab_list']; exit();
 		if($form_for==2)
 			echo $this->load->view('forms/custom_ticket_form',$data,true);
 		else
 			echo $this->load->view('forms/custom_enquiry_form',$data,true);				
	}
	public function get_tab_fields($tid,$comp_id,$for=0){		 		
		$data['tid'] = $tid;
		$data['comp_id'] = $comp_id;
 		$this->db->select('*,input_types.title as input_type_title'); 		
 		$this->db->where('tbl_input.form_id',$tid);  			
 		//$this->db->where('tbl_input.page_id',0); 			
 		$this->db->where('tbl_input.company_id',$comp_id);  			
 		$this->db->order_by('tbl_input.fld_order','ASC');  			
 		$this->db->join('input_types','input_types.id=tbl_input.input_type');  			
 		$data['form_fields']	=	$this->db->get('tbl_input')->result_array(); 
 		$data['basic_fields']	= $this->User_model->get_input_basic_fields($comp_id,$for); 
 		//print_r($data['basic_fields']); exit();
 		if(empty($data['basic_fields']))
 		{
 	 			$this->db->select('id');
	 			$basic_fields	=	$this->db->where('field_for',$for)->get('basic_fields')->result_array();
	 			foreach ($basic_fields as $key => $value)
				{
					if($for==2)
					{
						$this->db->insert('ticket_fileds_basic',array('comp_id'=>$comp_id,'field_id'=>$value['id'])); 
					}else
					{
						$this->db->insert('enquiry_fileds_basic',array('comp_id'=>$comp_id,'field_id'=>$value['id'])); 
					}
	 								
	 			}
 		}
 		$this->db->where('comp_id',$comp_id);
		$this->db->where('form_id',$tid);
		$data['form_process_row']	=	$this->db->get('form_process')->row_array();		         		
 		$data['products'] = $this->dash_model->get_user_product_list_bycompany($comp_id);
 		$data['input_types'] = $this->form_model->get_input_types();
 		$data['tab_details'] = $this->db->get_where('forms',array('id'=>$tid))->row_array();
 		$data['field_for']  = $for;
 		echo $this->load->view('forms/field_by_tab',$data,true);		
	}
	/*public function enquiry_save_custom_field($comp_id){	
    	$this->form_validation->set_rules('label_name','Label Name','required|trim');
    	$this->form_validation->set_rules('label_type','Label Type','required|trim');
    	$this->form_validation->set_rules('input_name','Input Name','required|trim');
    	if ($this->form_validation->run() == TRUE) {
			$label_name = $this->input->post('label_name');
            $label_rep = $this->input->post('label_rep');			// label name
			$input_type = $this->input->post('label_type'); // input type
			$input_values = $this->input->post('label_value');
			$input_function = $this->input->post('label_function');
			$input_place = $this->input->post('label_place'); // input placeholder
			$input_name = $this->input->post('input_name');
			$input_required = $this->input->post('required_type');
			
			$input_readonly = $this->input->post('readonly');
			$input_disabled = $this->input->post('disabled');
			$form_id = $this->input->post('form_id');

			$process_list = $this->input->post('process_list');		
			
			$process_list1 = implode(',', $process_list);
			$insert_arr	=	array(		
							'input_place' => $input_place,
							'input_label' => $label_name,
							'rep_label' => $label_rep,
							'input_values' => $input_values,
							'input_name' => $input_name,
							'input_type' => $input_type,
							'function' => $input_function,
							'label_required' => $input_required,
							'readonly' => $input_readonly,
							'disabled' => $input_disabled,
							'page_id' => '0',
							'form_id' => $form_id,
							'company_id' => $comp_id,
							'process_id' => $process_list1
						);
			if($this->db->insert('tbl_input',$insert_arr)){
            	$this->session->set_flashdata('SUCCESSMSG',validation_errors());
			}
    	} else {
            $this->session->set_flashdata('SUCCESSMSG',validation_errors());
    	}
    	//echo $this->db->last_query();		
		redirect('customer/edit/'.$comp_id,'refresh');				
	}*/
	public function enquiry_save_custom_field($comp_id){	
    	$this->form_validation->set_rules('label_name','Label Name','required|trim');
    	$this->form_validation->set_rules('label_type','Label Type','required|trim');
    	$this->form_validation->set_rules('input_name','Input Name','required|trim');
    	if ($this->form_validation->run() == TRUE) {
			$label_name = $this->input->post('label_name'); // label name
			$input_type = $this->input->post('label_type'); // input type
			$input_values = $this->input->post('label_value');
			$input_function = $this->input->post('label_function');
			$input_place = $this->input->post('label_place'); // input placeholder
			$input_name = $this->input->post('input_name');
			$input_required = $this->input->post('required_type');
			
			$input_readonly = $this->input->post('readonly');
			$input_disabled = $this->input->post('disabled');
			$form_id = $this->input->post('form_id');
			$page_id	=	$this->input->post('page_id');
			// print_r($this->input->post()); exit();
			$process_list = $this->input->post('process_list');		
			if (!empty($process_list)) {
				$process_list1 = implode(',', $process_list);
			}else{
				$process_list1 = '';
			}
			$insert_arr	=	array(		
							'input_place' => $input_place,
							'input_label' => $label_name,
							'input_values' => $input_values,
							'input_name' => $input_name,
							'input_type' => $input_type,
							'function' => $input_function,
							'label_required' => $input_required,
							'readonly' => $input_readonly,
							'disabled' => $input_disabled,
							'page_id' => '0',
							'form_id' => $form_id,
							'company_id' => $comp_id,
							'process_id' => $process_list1,
							'page_id' => $page_id,							
						);
			if($this->input->post('fld_id')){
				$upd_arr = array(		
							'input_place' => $input_place,
							'input_label' => $label_name,
							'input_values' => $input_values,							
							'input_type' => $input_type,
							'function' => $input_function,
							'label_required' => $input_required,
							'readonly' => $input_readonly,
							'disabled' => $input_disabled,
						);
				$this->db->where('input_id',$this->input->post('fld_id'));
				$this->db->update('tbl_input',$upd_arr);           	
			}else{
				$this->db->insert('tbl_input',$insert_arr);
			}
    	} else {
            $this->session->set_flashdata('SUCCESSMSG',validation_errors());
    	}
    	//echo $this->db->last_query();		
		redirect('customer/edit/'.$comp_id,'refresh');								
	}
	public function get_custom_field_by_process(){
		$process_id	= 	$this->input->post('process_id');
		$field_for  = 	$this->input->post('field_for')??0;
		$tid = $this->input->post('primary_tab')??0;

		$data['company_list'] = $this->location_model->get_company_list($process_id,$tid);
		echo $this->load->view('forms/custom_field_by_process',$data,true);
	}
	
	public function get_basic_field_by_process(){		
		/*print_r($_SESSION);
		exit();*/
		$process_id = 0;
		if($this->session->process && count($this->session->process) == 1){
			$process_id = $this->session->process[0];
		}
		
		$this->load->model(
					array('location_model',
							'Enquiry_model',
							'Ticket_Model',
							'Leads_Model'
					));
	   
        
	    $data['name_prefix'] = $this->Enquiry_model->name_prefix_list();
	    
	    if($this->input->post('field_for')==2)
	    {
    		$data['source'] = $this->Leads_Model->get_leadsource_list();
			$data["clients"] = $this->Enquiry_model->getEnquiry()->result();
			$data["product"] = $this->Ticket_Model->getproduct();
			$data["referred_type"] = $this->Leads_Model->get_referred_by();
			$data['problem'] = $this->Ticket_Model->get_sub_list($this->session->companey_id,$this->session->process[0]);
			$data['issues'] = $this->Ticket_Model->get_issue_list();
	    	$data['company_list'] = $this->location_model->get_company_list1_ticket($process_id);
	    	echo $this->load->view('forms/ticket_basic_form_fields',$data,true);
	    }
	    else
	    {
			$data['leadsource'] = $this->Leads_Model->get_leadsource_list();
	        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();            
	        $data['product_contry'] = $this->location_model->productcountry();
	        $data['institute_list'] = $this->Institute_model->institutelist();
	        $data['datasource_list'] = $this->Datasource_model->datasourcelist();
	        $data['datasource_lists'] = $this->Datasource_model->datasourcelist2();
	        $data['subsource_list'] = $this->Datasource_model->subsourcelist();
	        $data['center_list'] = $this->Center_model->all_center();
	        $data['state_list'] = $this->location_model->estate_list();
	        $data['city_list'] = $this->location_model->ecity_list();
	        $data['country_list'] = $this->location_model->ecountry_list();
	    	$data['company_list'] = $this->location_model->get_company_list1($process_id);
			$this->load->model('Branch_model');
			$data['branch_lists']=$this->Branch_model->all_sales_branch();
	    	echo $this->load->view('forms/basic_form_fields',$data,true);
	    }
		
	
	}
	public function get_custom_field_in_basic()
	{
		$this->load->helper('custom_form_helper');
		$tid = $this->input->post('primary_tab');
		$comp_id = $this->session->companey_id;
		$ticketno = $this->input->post('ticketno');
		$tabname = '';
		$form_for = 2;
		echo tab_content($tid,$comp_id,$ticketno,$tabname,$form_for);
	}
	public function get_basic_field_by_process_update($tckt)
	{
		$this->load->model(
					array('location_model',
							'Enquiry_model',
							'Ticket_Model',
							'Leads_Model'
					));
		$process_id = $this->input->post('process_id');
		$data["ticket"] = $this->Ticket_Model->get($tckt);
		//print_r($data['ticket']); exit();
		$data['source'] = $this->Leads_Model->get_leadsource_list();
		$data["clients"] = $this->Enquiry_model->getEnquiry()->result();
		$data["product"] = $this->Ticket_Model->getproduct();
		$data["referred_type"] = $this->Leads_Model->get_referred_by();
		$data['problem'] = $this->Ticket_Model->get_sub_list($this->session->companey_id,$data['ticket']->process_id);
		$data['issues'] = $this->Ticket_Model->get_issue_list();
    	$data['company_list'] = $this->location_model->get_company_list1_ticket($process_id);
    	echo $this->load->view('forms/ticket_basic_form_fields_update',$data,true);
		
	}

	public function get_customview_field_by_process(){		
		$process_id	=	$this->input->post('process_id');
		$data['company_list'] = $this->location_model->get_company_list2($process_id);
		echo $this->load->view('forms/get_dynamics_fields_inview',$data,true);
	}
	public function get_basicview_field_by_process(){		
		$process_id	=	$this->input->post('process_id');		
		$this->load->model('location_model');
		$data['leadsource'] = $this->Leads_Model->get_leadsource_list();
		$data['lead_score'] = $this->Leads_Model->get_leadscore_list();
		$data['product_contry'] = $this->location_model->productcountry();
		$data['institute_list'] = $this->Institute_model->institutelist();
		$data['datasource_list'] = $this->Datasource_model->datasourcelist();
		$data['datasource_lists'] = $this->Datasource_model->datasourcelist2();
		$data['subsource_list'] = $this->Datasource_model->subsourcelist();
		$data['center_list'] = $this->Center_model->all_center();
		$data['state_list'] = $this->location_model->estate_list();
		$data['city_list'] = $this->location_model->ecity_list();
		$data['country_list'] = $this->location_model->ecountry_list();
		$data['name_prefix'] = $this->enquiry_model->name_prefix_list();
		$data['company_list'] = $this->location_model->get_company_list1($process_id);
		echo $this->load->view('forms/get_basicfields_inview',$data,true);
	}
	public function change_basic_enquiry_field_status(){
		$field_id = $this->input->post('id');		
		$status = $this->input->post('status');
		$comp_id = $this->input->post('comp_id');
		$for = $this->input->post('field_for')??0;
		$this->db->where('field_id',$field_id);
	    $this->db->where('comp_id',$comp_id);
	    $arr = array('status'=>$status);
	    if($for==2)
	    {
			$res = $this->db->update('ticket_fileds_basic',$arr);	
	    }
	    else
	    {
	    	$res = $this->db->update('enquiry_fileds_basic',$arr);	
	    }
	    if($res){
        	echo 1;
	    }
	    else{
	    	echo 0;
	    }
	}
	public function change_extra_enquiry_field_status(){
		$field_id = $this->input->post('id');		
		$status = $this->input->post('status');
		$comp_id = $this->input->post('comp_id');
		$this->db->where('input_id',$field_id);
	    $this->db->where('company_id',$comp_id);
	    $arr = array('status'=>$status);
	    $res = $this->db->update('tbl_input',$arr);	
	    if($res){
        	echo 1;
	    }
	    else{
	    	echo 0;
	    }	
	}
	public function save_form_row(){
		if($this->input->post('basic') != 'false'){
			$this->db->where('comp_id',$this->input->post('comp_id'));
			$this->db->where('field_id',$this->input->post('id'));
			$process_ids	=	$this->input->post('process_ids');
			if(!empty($process_ids)){
				$process_ids = implode(',', $process_ids);
			}
			$this->db->set('process_id',$process_ids);
			if($this->input->post('field_for')==0)
				$res	=	$this->db->update('enquiry_fileds_basic');
			else if($this->input->post('field_for')==2)
				$res	=	$this->db->update('ticket_fileds_basic');
		}else{
			$this->db->where('company_id',$this->input->post('comp_id'));
			$this->db->where('input_id',$this->input->post('id'));
			$process_ids	=	$this->input->post('process_ids');
			if(!empty($process_ids)){
				$process_ids = implode(',', $process_ids);
			}
			$this->db->set('process_id',$process_ids);
			$res	=	$this->db->update('tbl_input');
		}
		echo $res;		
	}
	public function save_form_rules(){
		echo "<pre>";
		print_r($_POST);
		$form_data	=	$this->input->post('form_data');
		parse_str($form_data,$form_data1);
		print_r($form_data1);
	}
	public function assign_process_in_tab(){
		$comp_id=$this->input->post('comp_id');
		$tid=$this->input->post('tid');
		$process_ids=$this->input->post('process_ids');
		if (!empty($process_ids)) {
			$process_ids = implode(',', $process_ids);
		}
		$this->db->where('comp_id',$comp_id);
		$this->db->where('form_id',$tid);
		if($this->db->get('form_process')->num_rows()){
			$this->db->where('comp_id',$comp_id);
			$this->db->where('form_id',$tid);
			$this->db->update('form_process',array('comp_id'=>$comp_id,'process_id'=>$process_ids));			
		}else{
			$this->db->insert('form_process',array('comp_id'=>$comp_id,'form_id'=>$tid,'process_id'=>$process_ids));
		}
	}
	public function tabs(){
		$data['title'] = 'Enquiry Tabs';		
		$data['company_list'] = $this->doctor_model->read();
		
		/*echo "<pre>";
		print_r($data['company_list']);
		echo "</pre>";*/
		$data['tab_list'] = $this->form_model->get_all_tabs(); 	
		$data['content'] = $this->load->view('forms/tab_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
	}
	public function delete_tab($tab_id)
	{	
		$this->db->where('id',$tab_id)->delete('forms');
		$this->session->set_flashdata('SUCCESSMSG','Delete Successfully');
		redirect(base_url('form/form/tabs'));
	}
	public function create_tab(){
		//print_r($_POST);die;
		$tab_name		= $this->input->post('tab_name');
		$tab_type       = $this->input->post('tab_type');
		$comp_ids 		= $this->input->post('comp_ids');
		$isqueryform 	= 0;
		$edit 			= 0;
		$delete 		= 0;
		$isqueryform 	= $this->input->post('isqueryform')??0;
		if($isqueryform == 1)
		{
			$edit 	= $this->input->post('edit')??0;
			$delete = $this->input->post('delete')??0;
		}
		if (!empty($comp_ids)) {
			$comp_ids = implode(',', $comp_ids);
		}
		$data = array('title'=>$tab_name,'comp_id'=>$comp_ids,'form_type'=>$isqueryform,'is_edit'=>$edit,'is_delete'=>$delete,'form_for'=>$tab_type);
		
		if ($this->input->post('tab_id')) {
			$this->db->where('id',$this->input->post('tab_id'));
			$this->db->update('forms',$data);		
		}else{
			$this->db->insert('forms',$data);
		}		
		redirect('form/form/tabs','refresh');
	}
	public function get_edit_tab_content(){		
		$data['company_list'] = $this->doctor_model->read();
		$tab_id	=	$this->input->post('tab_id');
		$this->db->where('id',$tab_id);
		$data['tab_row'] 	=	$this->db->get('forms')->row_array();
		echo $this->load->view('forms/edit_tab_content',$data,true);
	}
	public function edit_tab_field(){
		$fid	=	$this->input->post('id');
		$data['fid'] = $fid;
		$this->db->where('input_id',$fid);
		$data['field_row']	=	$this->db->get('tbl_input')->row_array();
 		$data['input_types'] = $this->form_model->get_input_types();		
		$data['comp_id'] = $this->input->post('comp_id');
		echo $this->load->view('forms/edit_tab_field',$data,true);		
	}
	public function reorder_field(){
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		$comp_id	=	$this->input->post('comp_id');
		$fid		=	$this->input->post('fid');
		$type	 	= 	$this->input->post('type');
		$fld_order 	=  	$this->input->post('fld_order');
		$field_for  =   $this->input->post('field_for');
		echo $field_for;
		if ($type == 'extra') {
			$this->db->where('input_id',$fid);
			$this->db->set('fld_order',$fld_order);
			$this->db->update('tbl_input');
		}else if ($type == 'basic') {
			$this->db->where('field_id',$fid);
			$this->db->where('comp_id',$comp_id);
			$this->db->set('fld_order',$fld_order);
			if($field_for=='2')
				$this->db->update('ticket_fileds_basic');
			else
				$this->db->update('enquiry_fileds_basic');
		}
		echo $this->db->last_query();
	}
	public function product_form(){		
		$data['title'] = 'Product Fields';
		$data['comp_id'] = $this->session->companey_id; 		
 		$this->db->select('*,input_types.title as input_type_title'); 		
 		$this->db->where('tbl_input.page_id',1);  			 
 		$this->db->where('tbl_input.company_id',$data['comp_id']);  			
 		$this->db->order_by('tbl_input.fld_order','ASC');  			
 		$this->db->join('input_types','input_types.id=tbl_input.input_type');  			
 		$data['form_fields']	=	$this->db->get('tbl_input')->result_array(); 
 		$data['form_process_row']	=	array();		         		
 		$data['category'] = $this->db->get_where('tbl_category',array('comp_id'=>$data['comp_id']))->result();
 		$data['input_types'] = $this->form_model->get_input_types();
 		$data['tab_details'] = array(); 		 
 		$data['content']	=	$this->load->view('forms/product_form',$data,true);		
 		$this->load->view('layout/main_wrapper',$data);
	}
	public function get_product_field_content($cat,$pid=0){
		$cat_id	=	$cat;		
		$data['company_list'] = $this->form_model->get_field_by_process($cat,1);		
		/*
		echo "<pre>";
		print_r($data['company_list']);
		echo "</pre>";
		*/
		$res = array();
		if (!empty($pid)) {
			$comp_id = $this->session->companey_id;
			$this->db->from('product_fields');
			$this->db->where('product_id',$pid);
			$this->db->where('parent',$pid);
			$this->db->where('cmp_no',$comp_id);
			$form_data = $this->db->get()->result_array();
			if (!empty($form_data)) {
				foreach ($form_data as $key => $value) {
					$fid = $value['input'];
					$res[$fid] = $value['fvalue'];
				}
			}
			$data['res'] = $res;			
			//print_r($data['res']);
		}
		echo $this->load->view('forms/custom_field_by_process',$data,true);		
	}
}