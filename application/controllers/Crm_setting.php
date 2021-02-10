<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Crm_setting extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'Crm_setting_model','dash_model'
        ));
		
    }
	
	public function index(){ 
		 
		$data= array();
		$data['title'] = display('Crm Setting');
    $company_id=  $this->session->companey_id;
    $data['product_list']=$this->dash_model->all_process_list();
    $data['user_list'] = $this->Crm_setting_model->user_list($company_id);
    // echo "<pre>";
    // print_r($data['product_list']);die;
		$data['content'] = $this->load->view('crm_setting',$data, true);
    $this->load->view('layout/main_wrapper', $data);
		
	}
	public function payment_setting()
	{
		
		     
          $para="payment";
          $payment_type = $this->input->post('payment_type', TRUE);
          $api_key = $this->input->post('api_key', TRUE);
          $secretkey = $this->input->post('secretkey', TRUE);
          $amount = $this->input->post('amount', TRUE);
          $keyid = $this->input->post('keyid', TRUE);
          $keyseprate = $this->input->post('keyseprate', TRUE);
          $id = $this->input->post('id')?$this->input->post('id'):0;
            $data     = array(
                'payment_type' => $payment_type,
                'api_key' => $api_key,
                'secretkey' => $secretkey,
                'amount' => $amount,
                'keyid' => $keyid,
                'keyseprate' => $keyseprate,
            );
          $payment_value = json_encode($data);
          $payment_type = $this->input->post('payment_type', TRUE);
          $payment = array(
          	'sys_para' => $para,
          	'sys_value' =>$payment_value,
          	'type'=>$title,
          	 "status"       => 1,
			"comp_id"      => $this->session->companey_id
		 );
         set_sys_parameter($para ,$payment_value,'PAYMENT_GETWAY');
          $this->session->set_flashdata('message', display('save_successfully'));
          redirect('crm_setting');
	}
	public function stage()
	{
		$title = 'STAGE';
    $para="portal_stage";
    $portal    = $this->input->post('portal', TRUE);
		 $portals = array(
          	'sys_para' => $para,
          	'sys_value' =>$portal,
          	'type'=>$title,
          	 "status"       => 1,
			"comp_id"      => $this->session->companey_id
		 );
		 set_sys_parameter($para ,$portal,'STAGE');
      $this->session->set_flashdata('message', display('save_successfully'));
      redirect('crm_setting');
	}

	public function processrights()
	{
		
    $para = 'process_rights';
		$process    = $this->input->post('process', TRUE);
		$user_right    = $this->input->post('user_right', TRUE);
		 $data     = array(
                'process' => $process,
                'user_right' => $user_right
            );
          $right = json_encode($data);
          $processrights = array(
          	'sys_para' => $para,
          	'sys_value' =>$right,
          	'type'=>$title,
          	 "status"       => 1,
			"comp_id"      => $this->session->companey_id
		 );
     set_sys_parameter($para ,$right,'RIGHT_OF_PORTAL');
      $this->session->set_flashdata('message', display('save_successfully'));
           
            redirect('crm_setting');
	}
	public function enquiry_setting()
	{
	
    $para = "enquiry";
		$prefix    = $this->input->post('prefix', TRUE);
		$postfix    = $this->input->post('postfix', TRUE);
		$digit = $this->input->post('digit',TRUE);
    $data     = array(
                'prefix' => $prefix,
                'postfix' => $postfix,
                'digit' => $digit
            );
          $rights = json_encode($data);
          $enquirysetting = array(
            'sys_para' => $para,
            'sys_value' =>$rights,
            'type'=>$title,
             "status"       => 1,
      "comp_id"      => $this->session->companey_id
     );
    set_sys_parameter($para ,$rights,'ENQUIRY_SETTING'); 
     $this->session->set_flashdata('message', display('save_successfully'));
          
            redirect('crm_setting');
	}
  public function duplicates()
  {
   
    $para = "dublicate";
    $email    = $this->input->post('email', TRUE);
    $phone    = $this->input->post('phone', TRUE);
    $company_name = $this->input->post('company_name',TRUE);
    $optradio = $this->input->post('optradio',TRUE);
    
    $data     = array(
                'email' => $email,
                'phone' => $phone,
                'optradio' =>$optradio,
                'company_name' => $company_name
            );
     $duplicate = json_encode($data);
          $duplicates = array(
            'sys_para' => $para,
            'sys_value' =>$duplicate,
            'type'=>$title,
             "status"       => 1,
      "comp_id"      => $this->session->companey_id
     );
       set_sys_parameter($para ,$duplicate,'DUBLICATES');  
      $this->session->set_flashdata('message', display('save_successfully'));
       
          redirect('crm_setting');
  }
	
	
	
}