<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Client extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->library('aws');
        $this->load->library('upload');
        $this->lang->load("activitylogmsg","english");;        
        $this->load->model(
                array('Branch_model','Ticket_Model','Leads_Model','common_model','enquiry_model', 'dashboard_model', 'Task_Model', 'User_model', 'location_model', 'Message_models','Institute_model','Datasource_model','Taskstatus_model','dash_model','Center_model','SubSource_model','Kyc_model','Education_model','SocialProfile_model','Closefemily_model','form_model','report_model','Configuration_Model','Doctor_model','rule_model','message_models')
                );
        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
    public function index() {
        $this->session->unset_userdata('enquiry_filters_sess');        
        if (user_role('80') == true) {}  
         if(!empty($this->session->enq_type)){
            $this->session->unset_userdata('enq_type',$this->session->enq_type);
        }       
        $this->load->model('Datasource_model');         
        $data['title'] = display('client_list');
        $data['user_list'] = $this->User_model->companey_users();
        $data['products'] = $this->dash_model->get_user_product_list();        
        $data['drops']      = $this->enquiry_model->get_drop_list();        
        $data['lead_score'] = $this->enquiry_model->get_leadscore_list(); 
        $data['created_bylist'] = $this->User_model->read();
        $data['sourse'] = $this->report_model->all_source();
        $data['datasourse'] = $this->report_model->all_datasource(); 
        $data['dfields']  = $this->enquiry_model-> getformfield();       
        
        if(!empty($_GET) && !empty($_GET['desposition'])){
            $desp = $this->db->where('stg_id',$_GET['desposition'])->get('lead_stage')->row();        
            $data['desp'] = $desp;          
            $this->session->set_userdata('enquiry_filters_sess',array('stage'=>$_GET['desposition']));
        }
        
        $data['subsource_list'] = $this->Datasource_model->subsourcelist();     
        $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');                  
        if (!empty($enquiry_separation) && !empty($_GET['stage'])) {                    
            $enquiry_separation = json_decode($enquiry_separation,true);
            $stage    =   $_GET['stage'];
            $data['title'] = $enquiry_separation[$stage]['title'];
            $data['data_type'] = $stage;
        }else{
            $data['title'] = display('Client');
            $data['data_type'] = 3;
        }
        $data['tags'] = $this->enquiry_model->get_tags();
        $data['all_stage_lists'] = $this->Leads_Model->get_leadstage_list_byprocess1($this->session->process,array(1,2,3));
        $data['filterData'] = $this->Ticket_Model->get_filterData(1); 
        $data['aging_rule'] = $this->rule_model->get_rules(array(11));
        $this->load->model('Branch_model');
        $data['branch_lists']=$this->Branch_model->all_sales_branch();
        $data['region_lists']=$this->Branch_model->all_sales_region();
        $data['area_lists']=$this->Branch_model->all_sales_area();
        $data['dept_lists']=$this->User_model->all_sales_dept();
        $data['state_list'] = $this->enquiry_model->get_user_state_list();
        $data['city_list'] = $this->enquiry_model->get_user_city_list();        
        $data['content'] = $this->load->view('enquiry_n', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function enquery_detals_by_status($id = '') {
        if ($id > 0 and $id <= 20) {
            $serach_key = '';
        } else {
            $serach_key = explode('_', $id);
        }
        $data['title'] = display('enquiry_list');
        $data['user_list'] = $this->User_model->read();
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        
        if ($id == 1) {
            $data['all_clients'] = $this->Client_Model->all_created_today();
        } elseif ($id == 2) {            
            $data['all_clients'] = $this->Client_Model->all_Updated_today();
        } elseif ($id == 3) {
            $data['all_clients'] = $this->Client_Model->all_Active_clients();
        } elseif ($id == 4) {
            $data['all_clients'] = $this->Client_Model->all_InActive_clients();
        } elseif ($id == 5) {
            $data['all_clients'] = $this->Client_Model->all_clients_Tickets();
        } elseif ($id == 6) {
            $data['all_clients'] = $this->Client_Model->all_clients();
        } elseif ($id == 7) {
            $data['all_clients'] = $this->Client_Model->checked_enquiry();
        } elseif ($id == 8) {
            $data['all_clients'] = $this->Client_Model->unchecked_enquiry();
        } elseif ($id == 9) {
            $data['all_clients'] = $this->Client_Model->scheduled();
        } elseif ($id == 10) {
            $data['all_clients'] = $this->Client_Model->unscheduled();
        } elseif (!empty($serach_key[1]) == 2) {
            $data['all_clients'] = $this->Client_Model->search_data($serach_key[0]);
        } else {
            $data['all_clients'] = $this->Client_Model->all_creaed_today();
        }
        //echo $this->db->last_query();
        $data['customer_types'] = $this->enquiry_model->customers_types();
        $data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
        $data['state_list'] = $this->location_model->state_list();
        $data['drops'] = $this->Leads_Model->get_drop_list();
        $this->load->view('client_list', $data);
        
    }
    public function view($enquiry_id) {
        $this->load->model('Client_Model');
        //print_r($enquiry_id);exit;
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);   
        //$data['state_city_list'] = $this->location_model->get_city_by_state_id($data['details']->enquiry_state_id);
        //$data['state_city_list'] = $this->location_model->ecity_list();
        $compid = $this->session->userdata('companey_id');
        //$data['allleads'] = $this->Leads_Model->get_leadList();
        if (!empty($data['details'])) {
            $lead_code = $data['details']->Enquery_id;
        }        
        //$data['check_status'] = $this->Leads_Model->get_leadListDetailsby_code($lead_code);       
        // $data['all_drop_lead'] = $this->Leads_Model->all_drop_lead();
        // $data['products'] = $this->dash_model->get_user_product_list(); 
        // $data['bank_list'] = $this->dash_model->get_bank_list(); 
        // $data['allcountry_list'] = $this->Taskstatus_model->countrylist();
        // $data['allstate_list'] = $this->Taskstatus_model->statelist();
        // $data['allcity_list'] = $this->Taskstatus_model->citylist();
        // $data['personel_list'] = $this->Taskstatus_model->peronellist($enquiry_id);        
        // $data['kyc_doc_list'] = $this->Kyc_model->kyc_doc_list($lead_code);        
        // $data['education_list'] = $this->Education_model->education_list($lead_code);
        // $data['social_profile_list'] = $this->SocialProfile_model->social_profile_list($lead_code);        
        // $data['close_femily_list'] = $this->Closefemily_model->close_femily_list($lead_code);
        // $data['all_country_list'] = $this->location_model->country();
        // $data['all_contact_list'] = $this->location_model->contact($enquiry_id);                
        // $data['subsource_list'] = $this->Datasource_model->subsourcelist();
        // $data['drops'] = $this->Leads_Model->get_drop_list();
        // $data['name_prefix'] = $this->enquiry_model->name_prefix_list();
        // $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['enquiry'] = $this->enquiry_model->enquiry_by_id($enquiry_id);
        //$data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $enquiry_code = $data['enquiry']->Enquery_id;
        $phone_id = '91'.$data['enquiry']->phone;        
        $data['recent_tasks'] = $this->Task_Model->get_recent_taskbyID($enquiry_code);        
        $data['comment_details'] = $this->Leads_Model->comment_byId($enquiry_code);        
        $user_role    =   $this->session->user_role;
        //$data['country_list'] = $this->location_model->productcountry();
        //$data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        
        //$data['institute_app_status'] = $this->Institute_model->get_institute_app_status();
        
        // $data['prod_list'] = $this->Doctor_model->product_list($compid); 
        // $data['amc_list'] = $this->Doctor_model->amc_list($compid,$enquiry_id); 
        // $data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        // $data['state_list'] = $this->location_model->estate_list();
        // $data['city_list'] = $this->location_model->ecity_list();
        // $data['product_contry'] = $this->location_model->productcountry();
        // $data['get_message'] = $this->Message_models->get_chat($phone_id);
        //$data['all_stage_lists'] = $this->Leads_Model->find_stage();
        //$data['all_estage_lists'] = $this->Leads_Model->find_estage($enquiry_id);
        $data['all_estage_lists'] = $this->Leads_Model->find_estage($data['details']->product_id,3);
        
        //$data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        //$data['dynamic_field']  = $this->enquiry_model->get_dyn_fld($enquiry_id);
        
        $data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id,$data['details']->product_id,0);
        $this->load->helper('custom_form_helper');
        $data['all_description_lists']    =   $this->Leads_Model->find_description();
        $data['leadid']     = $data['details']->Enquery_id;
        $data['compid']     =  $data['details']->comp_id;
        //$data['ins_list'] = $this->location_model->get_ins_list($data['details']->Enquery_id);
        //$data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id);
		$data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id,base64_decode($this->uri->segment(4)));
        $data['enquiry_id'] = $enquiry_id;
        //$this->enquiry_model->make_enquiry_read($data['details']->Enquery_id);
        
         if (user_access('1000') || user_access('1001') || user_access('1002')) {
            // $data['branch']=$this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();
            // $data['CommercialInfo'] = $this->enquiry_model->getComInfo($enquiry_id);
            //fetch last entry
            // $comm_data=$this->db->where(array('enquiry_id'=>$enquiry_id))->order_by('id',"desc")
            // ->limit(1)->get('commercial_info');
            // $data['commInfoCount']=$comm_data->num_rows();
            // $data['commInfoData']=$comm_data->row();
        } 
        else
         {
        //         $data['CommercialInfo'] =array();
        //      $data['branch'] =array();
        //      $data['commInfoCount']=0;
        //      $data['commInfoData']=array();
        }
        
        //$data['course_list'] = $this->Leads_Model->get_course_list();
        $data['data_type'] = base64_decode($this->uri->segment(4)); 
        $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');                  
        if (!empty($enquiry_separation) && !empty($data['data_type'])) {                    
            $enquiry_separation = json_decode($enquiry_separation,true);
            $stage    =   $data['data_type'];
            $data['title'] = $enquiry_separation[$stage]['title']??'Information';            
        }else{
            $data['title'] =display('client');
        }
        if($this->session->companey_id == 65 && $this->session->user_right == 215){
            $data['created_bylist'] = $this->User_model->readone(147,false);
        }else{
            $data['created_bylist'] = $this->User_model->readone();
        } 
        $this->load->model('Branch_model');
        $data['branch_lists']=$this->Branch_model->all_sales_branch();
        $data['region_lists']=$this->Branch_model->all_sales_region();
        //$data['dept_lists']=$this->User_model->all_sales_dept();  
        $enq['enquiry_id'] = $enquiry_id;
		$enq['all_designation'] = $this->Leads_Model->desi_select();
        $data['all_contact']= $this->Client_Model->getContactWhere("client_id=$enquiry_id")->result();
        //echo $this->db->last_query();
        $data['create_contact_form'] = $this->load->view('contacts/create_contact_form',$enq,true);
        $data['content'] = $this->load->view('enquiry_details1', $data, true);
        $this->enquiry_model->assign_notification_update($enquiry_code);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function contact_form_ajax()
    {   
        $x = array();
        if(!empty($_POST['enq_id']))
        {
            $x['enquiry_id'] = $_POST['enq_id'];
        }
      echo  $this->load->view('contacts/create_contact_form',$x,true);
    }

    public function views() {
        $leadid = $this->uri->segment(3);
        $data['details'] = $this->Client_Model->get_clientid_bycustomerCODE($leadid);
        foreach ($data['details'] as $v) {
            $lead_code = $v->cli_id;
            $Enquery_id = $v->Enquery_id;
        }
        redirect('client/view/' . $lead_code . '/' . $Enquery_id);
    }
    public function update_details() {
        $data['title'] = 'Client Details';
        $clientid = $this->uri->segment(3);
        if (!empty($_POST)) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $address = $this->input->post('address');
            $status = $this->input->post('status');
            $updateDate = date('d-m-Y');
            $this->db->set('cl_mobile', $mobile);
            $this->db->set('cl_email', $email);
            $this->db->set('cl_name', $name);
            $this->db->set('address', $address);
            $this->db->set('cl_status', $status);
            $this->db->set('updated_date', $updateDate);
            $this->db->where('cli_id', $clientid);
            $this->db->update('clients');
            $data['clientDetails'] = $this->Client_Model->clientdetail_by_id($clientid);
            $enquiry_code = $data['clientDetails']->Enquery_id;
            $this->Leads_Model->add_comment_for_events(display("information_updated"), $enquiry_code);
            $this->session->set_flashdata('message', 'Informanation Updated Successfully');
            redirect('client/view/' . $clientid);
        }
    }
    public function create_newcontact() 
    {
        if(user_role('1010')==true){

        }
        
        $this->load->model(array('Enquiry_Model','Client_Model'));
        $clientid = $this->input->post('enquiry_id');
        if (!empty($_POST)) {

            $name = $this->input->post('name');
            $mobile = $this->input->post('mobileno');
            $email = $this->input->post('email');
            $otherdetails = $this->input->post('otherdetails');
			if(!empty($this->input->post('new_designation'))){
				$desi_id    =   $this->Enquiry_Model->create_designation($this->input->post('new_designation'));
				$designation = $desi_id;
			}else{
				$designation = $this->input->post('designation');
			}
            $enq = $this->Enquiry_Model->getEnquiry(array('enquiry_id'=>$clientid));
            $data = array(
                'comp_id'=>$this->session->companey_id,
                'client_id' =>$enq->row()->enquiry_id,
                'c_name' => $name,
                'emailid' => $email,
                'contact_number' => $mobile,
                'designation' => $designation,
                'other_detail' => $otherdetails,
                'decision_maker' => $this->input->post('decision_maker')??0,
            );
            $enquiry_code = $enq->row()->Enquery_id;
            $this->Leads_Model->add_comment_for_events(display("new_contact_detail_added") , $enquiry_code);
            $insert_id = $this->Client_Model->clientContact($data);
            $this->session->set_flashdata('message','Contact Added Successfully');
        }

        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }
    public function delete_contact()
    {
        if(user_role('1011')==true){
            
        }
         $cc_id = $this->input->post('cc_id');
          $this->db->where(array('cc_id'=>$cc_id,'comp_id'=>$this->session->companey_id));
          $this->db->delete('tbl_client_contacts');
    }
	
	public function delete_log()
    {
         $cc_id = $this->input->post('cc_id');
          $this->db->where(array('comm_id'=>$cc_id,'comp_id'=>$this->session->companey_id));
          $this->db->delete('tbl_comment');
    }
	
    public function edit_contact()
    {
        if(user_role('1012')==true){
            
        }
        $data['all_designation'] = $this->Leads_Model->desi_select();
        //print_r($data['all_designation']);exit;
        if($this->input->post('task')=='view')
        {
            $cc_id = $this->input->post('cc_id');
            $this->load->model(array('Client_Model','Enquiry_Model'));
            $res = $this->Client_Model->getContactWhere(array('cc_id'=>$cc_id,'comp_id'=>$this->session->companey_id));
            if(!$res->num_rows())
            {
                echo'NO Result';exit();
            }
        $row = $res->row();
        echo'<div class="row" align="left" >
        <form method="post" action="'.base_url('client/edit_contact/').'" class="form-inner">
        <input type="hidden" name="cc_id" value="'.$row->cc_id.'">
        <input type="hidden" name="client_id" value="'.$row->client_id.'">
            <input type="hidden" name="task" value="save">';

            if(!empty($_POST['direct_create']))
            {
                echo'<div class="form-group col-md-12">
                  <label>Related To</label>
                  <select class="form-control" name="client_id" readonly>';
                  $enquiry_list = $this->Enquiry_Model->all_enqueries();
                    if(!empty($enquiry_list))
                    {
                        foreach ($enquiry_list as $row2)
                        {
                            //echo'<option value="'.$row2->enquiry_id.'" '.($row->client_id==$row2->enquiry_id?'selected':'').'>'.$row2->name.'</option>';
                            if($row->client_id==$row2->enquiry_id)
                                echo'<option value="'.$row->client_id.'">'.$row2->name.'</option>';
                        }
                    }

                  echo'
                  </select>
               </div>';
           }
               echo'<div class="form-group col-md-6">
                  <label>Designation</label>
                  <select class="form-control" name="designation" id="designation">
                        <option value="">---Select Designation----</option>';
                        foreach ($data['all_designation'] as $key => $value) {
                        echo'<option value="'.$value->id.'" '.(($value->id==$row->designation)?"selected":"").'>'.$value->desi_name.'</option>';
                        }
                    echo '</select>
               </div>
               <div class="form-group col-md-6">
                  <label>Name</label>
                  <input class="form-control" name="name" placeholder="Contact Name"  type="text"   value="'.$row->c_name.'" required>
               </div>
               <div class="form-group col-md-6">
                  <label>Contact No.</label>
                  <input class="form-control" name="mobileno" placeholder="Mobile No." maxlength="10"  value="'.$row->contact_number.'" type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Email</label>
                  <input class="form-control" name="email" placeholder="Email"  type="text"  value="'.$row->emailid.'" required>
               </div>
                <div class="form-group col-md-6">
                  <label>Decision Maker</label> &nbsp;
                  <input name="decision_maker" type="checkbox" value="1" '.($row->decision_maker?'checked':'').'>
               </div>
               <div class="form-group col-md-12">
                  <label>Other Details</label>
                  <textarea class="form-control" name="otherdetails" rows="8">'.$row->other_detail.'</textarea>
               </div>
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn" align="center">
                     <input id="signupbtn" type="submit" value="Save" class="btn btn-primary"  name="Save">
                  </div>
               </div>
               <input type="hidden" name="redirect_url" value="'.$this->agent->referrer().'#company_contacts" />
            </form>
            </div>
            ';
        }
        else if($this->input->post('task')=='save')
        {
            //print_r($this->input->post()); exit();
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobileno');
            $email = $this->input->post('email');
            $otherdetails = $this->input->post('otherdetails');
            $cc_id = $this->input->post('cc_id');
            $data = array(
                'c_name' => $name,
                'emailid' => $email,
                'contact_number' => $mobile,
                'designation' => $this->input->post('designation'),
                'decision_maker'=>$this->input->post('decision_maker')??0,
                'other_detail' => $otherdetails
            );
            $enq_no = $this->input->post('client_id');
            $this->db->where(array('cc_id'=>$cc_id,'comp_id'=>$this->session->companey_id,'client_id'=>$this->input->post('client_id')));
            $this->db->update('tbl_client_contacts',$data);
            $cmt_enq_id = $this->db->select('Enquery_id')->where('enquiry_id',$enq_no)->get('enquiry')->row();
            $subject='Contact is update';
            $this->Leads_Model->add_comment_for_events($subject, $cmt_enq_id->Enquery_id);
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        }
    }
    public function contacts()
    {
        if(user_role('1013')==true){
            
        }
        $this->load->model(array('Client_Model','Enquiry_Model'));
        $data['title'] = display('contacts');
        $data['contact_list'] = $this->Client_Model->getContactList();//contacts.*,enquiry.---
        //print_r($data['contact_list']->result_array()); exit();
        $r =$data['company_list'] = $this->Client_Model->getCompanyList()->result();
        //print_r($r);exit();
        //$data['enquiry_list'] = $this->Enquiry_Model->all_enqueries();
        // print_r($data['enquiry_list']);
        // die();
        $data['all_designation'] = $this->Leads_Model->desi_select();
		//echo '<pre>';print_r($data['all_designation']);exit;
		//$data['contact_create_form'] = $this->load->view('contacts/create_contact_form',array(),true);
        $data['contact_create_form'] = $this->load->view('contacts/create_contact_form',$data,true);
        $data['content'] = $this->load->view('enquiry/contacts', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
	
	public function all_type_log()
    {
        $this->load->model(array('Client_Model','Enquiry_Model'));
        $data['title'] = 'Logs';
		$data['created_bylist'] = $this->User_model->read();
		$data['filterData'] = $this->Ticket_Model->get_filterData('log');
        $data['content'] = $this->load->view('enquiry/all_logs', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
	
	public function log_dashboard_count()
	{
		$this->load->model('contacts_datatable_model');
		$this->contacts_datatable_model->_get_datatables_query_log(0);
		$data['all_call'] = $this->db->count_all_results();
//echo $this->db->last_query();die;

		$this->contacts_datatable_model->_get_datatables_query_log(0); 
		$this->db->where('tbl_comment.comment_msg LIKE','%Incoming%');
		$data['all_incoming'] = $this->db->count_all_results();

	

		$this->contacts_datatable_model->_get_datatables_query_log(0);
		$this->db->where('tbl_comment.comment_msg LIKE','%Outgoing%');
		$data['all_outgoing']= $this->db->count_all_results();

		$this->contacts_datatable_model->_get_datatables_query_log(0);
		$this->db->where('enquiry.created_date LIKE', date('Y-m-d')); //anyhow updated
		$data['all_new']=$this->db->count_all_results();


		$this->contacts_datatable_model->_get_datatables_query_log(0);	
		$this->db->where('enquiry.created_date NOT LIKE', date('Y-m-d'));
		$data['all_existing']=$this->db->count_all_results();

		echo json_encode($data);
	}

    public function company_list()
    {
        if(user_role('1060')){}
        $this->load->model(array('Client_Model','Enquiry_Model'));
		if($this->session->companey_id == 65 && $this->session->user_right == 215){
			$data['created_bylist'] = $this->User_model->read(147,false);
		}else{
			$data['created_bylist'] = $this->User_model->read();
		}
		$data['region_lists']=$this->Branch_model->all_sales_region();
		$data['dept_lists']=$this->User_model->all_sales_dept();
		$data['filterData'] = $this->Ticket_Model->get_filterData(1);
        $data['title'] = display('company_list');
        $data['content'] = $this->load->view('enquiry/company_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
	
	public function userwise_company_list()
    {
        if(user_role('1060')){}
        $this->load->model(array('Client_Model','Enquiry_Model'));
        $data['title'] = 'Userwise company list';
        $data['content'] = $this->load->view('enquiry/userwise_company_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function company_details($id)
    {
		//print_r($id);exit;
        $this->load->model(array('Client_Model','enquiry_model'));

        $company =  $this->Client_Model->getCompanyList_enq($id)->row();

        $data['title'] = 'Company Details';
        
    $c =$company;
    
    $deals =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'deals')->result();
   // print_r($c->enq_ids); exit();
    $deals = array_column((array)$deals, 'id');
    $data['specific_deals'] = count($deals)?implode(',', $deals):'-1';
    
    $visits =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'visits')->result();
    $visits = array_column((array)$visits, 'id');
    $data['specific_visits'] = count($visits)? implode(',', $visits):'-1';

    $contacts =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'contacts')->result();

    $contacts = array_column((array)$contacts, 'cc_id');

    $contacts = count($contacts)?implode(',',$contacts):array('-1');
    $data['specific_contacts'] = $contacts;
    // $x =$data['contact_list'] = $this->Client_Model->getContactList($contacts);
    // print_r($x);exit;
    $data['specific_accounts'] = $c->enq_ids;
    $data['dfields']  = $this->enquiry_model->getformfield();
    $data['ticket_dfields'] = $this->enquiry_model->getformfield(2);

    $tickets =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'tickets')->result();
    $tickets = array_column((array)$tickets, 'id');
    $data['specific_tickets'] = count($tickets)? implode(',', $tickets):'-1';
    $data['company_name'] = $company->company_name;

    $data['content'] = $this->load->view('enquiry/company_details', $data, true);
    $this->load->view('layout/main_wrapper', $data);

    }

    public function create_Invoice() {
        $clientid = $this->uri->segment(3);
        if (!empty($_POST)) {
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobileno');
            $email = $this->input->post('email');
            $otherdetails = $this->input->post('otherdetails');
            $data = array(
                'client_id' => $this->uri->segment(3),
                'c_name' => $name,
                'emailid' => $email,
                'contact_number' => $mobile,
                'designation' => $this->input->post('designation'),
                'other_detail' => $otherdetails
            );
            $clientDetails = $this->Client_Model->clientdetail_by_id($clientid);
            $enquiry_code = $clientDetails->Enquery_id;
            $this->Leads_Model->add_comment_for_events(display("new_contact_detail_added") , $enquiry_code);
            $insert_id = $this->Client_Model->clientContact($data);
            $this->session->set_flashdata('message', 'Client Contact Add Successfully');
            redirect('client/view/' . $clientid);
        } else {
            $data['page_title'] = display('Invoice');
            $data['all_item'] = $this->dash_model->item_list();
            $data['content'] = $this->load->view('invoice', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    function get_item_cost($id) {
        $idarr = explode('_', $id);
        $itmeprice = $this->dash_model->item_listbyid($idarr[0]);
        foreach ($itmeprice as $price) {
            $price1 = $price->Unite_p;
        }
        echo $price1;
    }
    public function re_oreder() {
        if (!empty($_POST)) {
            $encode = $this->get_enquery_code();
            $key = $this->input->post('child_id');
            $enq = $this->enquiry_model->enquiry_by_code($key);
            $data = array(
                'comp_id' => $this->session->userdata('companey_id'),
                'Enquery_id' => $encode,
                'email' => $enq->email,
                'phone' => $enq->phone,
                'name_prefix' => $enq->name_prefix,
                'name' => $enq->name,
                'lastname' => $enq->lastname,
                'gender' => $enq->gender,
                'enquiry' => $enq->enquiry,
                'org_name' => $enq->org_name,
                'created_by' => $enq->created_by,
                'city_id' => $enq->city_id,
                'state_id' => $enq->state_id,
                'country_id' => $enq->country_id,
                'region_id' => $enq->region_id,
                'territory_id' => $enq->territory_id,
                'created_date' => date('Y-m-d H:i:s'),
                'enquiry_source' => $enq->enquiry_source,
                'enquiry_subsource' => $enq->enquiry_subsource,
                'product_id' => $enq->product_id,
                'lead_score' => $enq->lead_score,
                'company' => $enq->company,
                'address' => $enq->address,
                'ip_address' => $this->input->ip_address(),
                'status' => 2
            );
            $insert_id = $this->Configuration_Model->web_enquiry($data);
            $enquiry = $this->enquiry_model->enquiry_by_code($encode);
            $adminid = $enquiry->created_by;
            $name = $enquiry->name;
            $email = $enquiry->email;
            $mobile = $enquiry->phone;
            $intrested = $enquiry->enquiry;
            $date = date('d-m-Y H:i:s');
            $lead_score = $this->input->post('lead_score');
            $lead_stage = $this->input->post('lead_stage');
            $comment = $this->input->post('comment');
            $assign_to = $this->session->user_id;
            if (!empty($lead_score)) {
                $lead_score = $this->input->post('lead_score');
            } else {
                $lead_score = '';
            }
            if (!empty($lead_stage)) {
                $lead_stage = $this->input->post('lead_stage');
            } else {
                $lead_stage = '';
            }
            if (!empty($comment)) {
                $comment = $this->input->post('comment');
            } else {
                $comment = '';
            }
            $data = array(
                'adminid' => $adminid,
                'ld_name' => $name,
                'ld_email' => $email,
                'ld_mobile' => $mobile,
                'lead_code' => $enquiry->Enquery_id,
                'city_id' => $enquiry->city_id,
                'state_id' => $enquiry->state_id,
                'ld_created' => $date,
                'ld_for' => $intrested,
                'lead_score' => $lead_score,
                'lead_stage' => $lead_stage,
                'comment' => $comment,
                'ld_status' => '1',
                'child_id' => $key
            );
            $insert_id = $this->Leads_Model->LeadAdd($data);
            if ($lead_stage == 5) {
                $this->Leads_Model->add_comment_for_events( display("circuit_sheet_created") , $enquiry->Enquery_id);
                redirect(base_url() . 'boq-add/' . base64_encode($enquiry->Enquery_id));
            } elseif ($lead_stage == 8) {
                $this->Leads_Model->add_comment_for_events(display("po_attached"), $enquiry->Enquery_id);
                redirect(base_url() . 'enquiry/attach_po/' . base64_encode($enquiry->Enquery_id));
            } else {
                $this->Leads_Model->add_comment_for_events(display("enquiry_moved"), $enquiry->Enquery_id);
                redirect('lead');
            }
        } else {
            echo "<script>alert('Something Went Wrong')</script>";
            redirect('enquiry');
        }
    }

        public function re_oreder1() {
        if (!empty($_POST)) {
            $encode = $this->get_enquery_code();
            $key = $this->input->post('child_id');
            $enq = $this->enquiry_model->enquiry_by_code($key);
            $data = array(
                'comp_id' => $this->session->userdata('companey_id'),
                'Enquery_id' => $encode,
                'email' => $enq->email,
                'phone' => $enq->phone,
                'name_prefix' => $enq->name_prefix,
                'name' => $enq->name,
                'lastname' => $enq->lastname,
                'gender' => $enq->gender,
                'enquiry' => $enq->enquiry,
                'org_name' => $enq->org_name,
                'created_by' => $enq->created_by,
                'city_id' => $enq->city_id,
                'state_id' => $enq->state_id,
                'country_id' => $enq->country_id,
                'region_id' => $enq->region_id,
                'territory_id' => $enq->territory_id,
                'created_date' => date('Y-m-d H:i:s'),
                'enquiry_source' => $enq->enquiry_source,
                'enquiry_subsource' => $this->input->post('proname'),
                'product_id' => $enq->product_id,
                'lead_score' => $enq->lead_score,
                'company' => $enq->company,
                'address' => $enq->address,
                'ip_address' => $this->input->ip_address(),
                'status' => 2
            );
            $data_bank = array(
            'comp_id'  =>$this->session->userdata('companey_id'),
            'bank'     => $this->input->post('bankname'),
            'product'     => $this->input->post('proname'),
            'enq_id'   => $encode,
            'created_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d H:i:s')
            );
            // print_r($data_bank);exit();
            $this->enquiry_model->add_newbankdeal($data_bank);
            $insert_id = $this->Configuration_Model->web_enquiry($data);
            $enquiry = $this->enquiry_model->enquiry_by_code($encode);
            $adminid = $enquiry->created_by;
            $name = $enquiry->name;
            $email = $enquiry->email;
            $mobile = $enquiry->phone;
            $intrested = $enquiry->enquiry;
            $date = date('d-m-Y H:i:s');
            $lead_score = $this->input->post('lead_score');
            $lead_stage = $this->input->post('lead_stage');
            $comment = $this->input->post('comment');
            $assign_to = $this->session->user_id;
            if (!empty($lead_score)) {
                $lead_score = $this->input->post('lead_score');
            } else {
                $lead_score = '';
            }
            if (!empty($lead_stage)) {
                $lead_stage = $this->input->post('lead_stage');
            } else {
                $lead_stage = '';
            }
            if (!empty($comment)) {
                $comment = $this->input->post('comment');
            } else {
                $comment = '';
            }
            $data = array(
                'adminid' => $adminid,
                'ld_name' => $name,
                'ld_email' => $email,
                'ld_mobile' => $mobile,
                'lead_code' => $enquiry->Enquery_id,
                'city_id' => $enquiry->city_id,
                'state_id' => $enquiry->state_id,
                'ld_created' => $date,
                'ld_for' => $intrested,
                'lead_score' => $lead_score,
                'lead_stage' => $lead_stage,
                'comment' => $comment,
                'ld_status' => '1',
                'child_id' => $key
            );
            $insert_id = $this->Leads_Model->LeadAdd($data);
           $this->Leads_Model->add_comment_for_events(display("enquiry_moved"), $enquiry->Enquery_id);
           $this->session->set_flashdata('message', 'New deal added successfully');
            redirect('led');
            
        } else {
            echo "<script>alert('Something Went Wrong')</script>";
            redirect('led');
        }
    }

    public function delete_recorde() {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id');
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $c = $this->Client_Model->clientdetail_by_id($key);
                    $this->db->where('cli_id', $key);
                    $this->db->delete('clients');
                    $this->db->where('Enquiry_id', $c->Enquery_id);
                    $this->db->delete('enquiry');
                    $this->db->where('lead_code', $c->Enquery_id);
                    $this->db->delete('allleads');
                }
                echo "Client Deleted Successfully";
            } else {
                echo display('please_try_again');
            }
        }
    }
    public function get_enquery_code() {
        $code = $this->genret_code();
        $code2 = 'ENQ' . $code;
        $response = $this->enquiry_model->check_existance($code2);
        if ($response) {
            $this->get_enquery_code();
        } else {
            return $code2;
        }
        exit;
    }
    function genret_code() {
        $pass = "";
        $chars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        for ($i = 0; $i < 4; $i++) {
            $pass .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $pass;
    }
    public function updateclient($enquiry_id = null) {  
       
            $res = $this->enquiry_model->get_deal($enquiry_id);
            $name_prefix = $this->input->post('name_prefix');
			//$code_prefix = $this->input->post('code_prefix');
            $firstname = $this->input->post('enquirername');
            $lastname = $this->input->post('lastname');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobileno');
            $other_phone = $this->input->post('other_no[]');
            $lead_source = $this->input->post('lead_source[]');
            $subsource = $this->input->post('subsource');
            $enquiry = $this->input->post('enquiry');
            $en_comments = $this->input->post('en_comments');
            $city_id = $this->input->post('city_id');
            $state_id = $this->input->post('state_id');
            
            $address = $this->input->post('address');
            $pin_code = $this->input->post('pin_code');
            $comp = $this->input->post('company');
            
            $sales_branch = $this->input->post('sales_branch');
            $client_name = $this->input->post('client_name');
			$gender = $this->input->post('gender');
            
            $sales_region = $this->input->post('sales_region');
            $sales_area = $this->input->post('sales_area');
            $client_type = $this->input->post('client_type');
            $business_load = $this->input->post('business_load');
            $industries = $this->input->post('industries');
            $designation = $this->input->post("designation");
			$lead_score = $this->input->post('lead_score');
            $expected_date = $this->input->post("expected_date");
			
			if(!empty($expected_date)){
                $expected_date = date('Y-m-d',strtotime($expected_date));
			}
            if($this->input->post('country_id')){
                 $country_id = implode(',',$this->input->post('country_id'));
            }else{
                $country_id = '';
            }
			
			if(!empty($lead_source)){
               $lead_source =   implode(',', $lead_source);
            }else{
                $lead_source = '';
            }
       
            $enqarr = $this->db->select('*')->where('enquiry_id',$enquiry_id)->get('enquiry')->row();           
            if(!empty($other_phone)){
               $other_phone =   implode(',', $other_phone);
            }else{
                $other_phone = '';
            }
            if (empty($this->input->post('product_id'))) {
                $process_id    =   $this->session->process[0];                
            }else{
                $process_id    =   $this->input->post('product_id');
            }

if(!empty($comp)){
            $company = $this->db->where('company_name',$comp)->get('tbl_company')->row();
              if(!empty($company))
              {
                $company = $company->id;
              }
              else
              {
                $new_company = array(
                                      'company_name'=>$comp,
                                      'comp_id'=>$this->session->companey_id,
                                      'process_id'=>$process_id, 
                                );
                $this->db->insert('tbl_company',$new_company);
                $company = $this->db->insert_id();
              }
}

if(!empty($this->input->post('new_designation'))){
				$desi_id    =   $this->enquiry_model->create_designation($this->input->post('new_designation'));
				$designation = $desi_id;
			}else{
				$designation = $this->input->post('designation');
			}
			
if(!empty($this->input->post('new_industry'))){
				$indus_id    =   $this->enquiry_model->create_industries($this->input->post('new_industry'));
				$industries = $indus_id;
			}else{
				$industries = $this->input->post('industries');
			}

            if($exp_date = $this->input->post('expected_date'))
                $this->db->set('lead_expected_date', $exp_date);

if(!empty($mobile)){
            $this->db->set('phone', $mobile);
}
            $this->db->set('other_phone', $other_phone);
            $this->db->set('country_id', $country_id);
if(!empty($email)){            
            $this->db->set('email', $email);
}
            $this->db->set('name_prefix', $name_prefix);
			//$this->db->set('code_prefix', $code_prefix);
            $this->db->set('name', $firstname);
            $this->db->set('enquiry_source', $lead_source);
            $this->db->set('sub_source', $subsource);
            $this->db->set('address', $address);
            $this->db->set('pin_code', $pin_code);
if(!empty($company)){
            $this->db->set('company', $company);
}
if(!empty($sales_branch)){
            $this->db->set('sales_branch', $sales_branch);
}
if(!empty($client_name)){
            $this->db->set('client_name', $client_name);
}
if(!empty($sales_region)){
            $this->db->set('sales_region', $sales_region);
}
if(!empty($sales_area)){
            $this->db->set('sales_area', $sales_area);
}
            $this->db->set('client_type', $client_type);
            $this->db->set('business_load', $business_load);
            $this->db->set('industries', $industries);
            $this->db->set('designation',$designation);
            $this->db->set('enquiry', $enquiry);
            $this->db->set('lastname', $lastname);
            $this->db->set('state_id', $state_id);
            $this->db->set('city_id', $city_id);
            $this->db->set('enquiry_subsource',$this->input->post('sub_source'));
if(!empty($process_id)){
            $this->db->set('product_id', $process_id); 
}			
            $this->db->set('lead_score', $lead_score);
			$this->db->set('gender', $gender);
			$this->db->set('lead_expected_date', $expected_date);
            $this->db->where('enquiry_id', $enquiry_id);            
            $this->db->update('enquiry');  
            $this->load->model('rule_model');
            $this->rule_model->execute_rules($en_comments,array(1,2));
            
            $type = $enqarr->status;                
            if($type == 1){                 
                $comment_id = $this->Leads_Model->add_comment_for_events(display('enquery_updated'), $en_comments);                    
            }else if($type == 2){                   
                $comment_id  = $this->Leads_Model->add_comment_for_events(display('lead_updated'), $en_comments);                   
            }else if($type == 3){
                $comment_id = $this->Leads_Model->add_comment_for_events(display('client_updated'), $en_comments);
            }else{
                $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');
                if (!empty($enquiry_separation)) {                    
                    
                    $enquiry_separation = json_decode($enquiry_separation,true);                    
                    $title = $enquiry_separation[$type]['title'];                    
                    $comment_msg = $title.' Updated'; 
                    $comment_id = $this->Leads_Model->add_comment_for_events($comment_msg, $en_comments);
                    
                }
            }
            if($this->session->userdata('companey_id')==29){
            
                $bank = $this->input->post('bankname');
                if(!empty($this->input->post('sub_source'))) 
                {
                    $subsrc = $this->input->post('sub_source');
                }else{
                    $subsrc='';
                }
                
                 $res = $this->enquiry_model->get_deal($en_comments);
                 if($res){
                 
                 $array_newdeal = array(
                    'bank'=> $bank,
                    'product' => $subsrc,
                    'updated_by' => $this->session->user_id
                );          
                $this->db->where('enq_id',$en_comments);
                $this->db->update('tbl_newdeal',$array_newdeal);
                 }
                 else{
                     $array_newdeal = array(
                    'comp_id' => $this->session->companey_id,
                    'enq_id'  => $en_comments,
                    'bank'=> $bank,
                    'product' => $subsrc,
                    'created_by' => $this->session->user_id
                );     
                    $this->db->insert('tbl_newdeal',$array_newdeal);
                 }
                 
                
              
            }
            
      
            if(!empty($enqarr)){                
                if(isset($_POST['inputfieldno'])) {                    
                    $inputno   = $this->input->post("inputfieldno", true);
                    $enqinfo   = $this->input->post("enqueryfield", true);
                    $inputtype = $this->input->post("inputtype", true);
                    
                    foreach($inputno as $ind => $val){                        
                        $biarr = array( 
                                        "enq_no"  => $en_comments,
                                        "input"   => $val,
                                        "parent"  => $enquiry_id, 
                                        "fvalue"  => $enqinfo[$ind],
                                        "cmp_no"  => $this->session->companey_id,
                                        "comment_id"  => $comment_id,
                                    );  
                            $this->db->where('enq_no',$en_comments);        
                            $this->db->where('input',$val);        
                            $this->db->where('parent',$enquiry_id);
                            if($this->db->get('extra_enquery')->num_rows()){                                
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$enquiry_id);
                                $this->db->set('fvalue',$enqinfo[$ind]);
                                $this->db->set('comment_id',$comment_id);
                                $this->db->update('extra_enquery');
                            }else{
                                $this->db->insert('extra_enquery',$biarr);
                            }
                    }                    
                }
                 
            }
            if ($this->session->companey_id==29 && $en_comments == 'ENQ188474867063') {
                $prop    =   $this->enquiry_model->get_extra_enquiry_property($en_comments,'paisaexporef',29);
                $data = $this->enquiry_model->get_enquiry_all_data($en_comments);
                $product = $data['product_name'];
                $paisaexpo_form_id = '';
                if (empty($prop['fvalue'])) {                    
                    $data = array('type'=>$product);                    
                    $options = array(
                                    'url'  => 'http://dev.paisaexpo.com/rest/all/V1/api/crm/create',
                                    'data' => $data,
                                    'request_type' => 'POST'
                                );
                    $res = curl($options);   
                    if (!empty($res)) {
                        $res = json_decode($res,true);
                        print_r($res);
                        var_dump($res);
                        echo $res['form_id'];
                        exit();
                        if ($res['form_id']) {
                            $paisaexpo_form_id =  $res['form_id'];                            
                            $this->enquiry_model->set_extra_enquiry_property($en_comments,'paisaexporef',$paisaexpo_form_id,29);
                        }
                    }
                }else{
                    $paisaexpo_form_id    =   $prop['fvalue'];
                }
                $formid  = $paisaexpo_form_id;
                $data = array('params'=>json_encode($data),'product'=>$product,'formId'=>$formid);
                $options = array(
                                'url'  => 'https://dev.paisaexpo.com/rest/all/V1/api/crm/update',
                                'data' => $data,
                                'request_type' => 'POST'
                            );
                $res = curl($options);
                echo "<pre>";
                echo $res;
                echo "</pre>";
                exit();
                
            }
             if (!$this->input->is_ajax_request()) {           
                $this->session->set_flashdata('message', 'Save successfully');
                redirect($this->agent->referrer()); //updateclient
            }else{
                echo json_encode(array('msg'=>'Saved Successfully','status'=>1));
            }
            
    }

    public function update_enquiry_tab($enquiry_id){        
        if($this->session->companey_id=='67'){
        }
        $tid    =   $this->input->post('tid');
        $form_type    =   $this->input->post('form_type');
        $enqarr = $this->db->select('*')->where('enquiry_id',$enquiry_id)->get('enquiry')->row();
        $en_comments = $enqarr->Enquery_id;
        $type = $enqarr->status;
        //For Comment Insert Code here
        $inputnos   = $this->input->post("inputfieldno", true);
        $cmt_text = $this->db->select('forms.title as title')->where('input_id',$inputnos[0])->join('forms','forms.id=tbl_input.form_id')->get('tbl_input')->row();
                
        if($type == 1){                 
           // $comment_id = $this->Leads_Model->add_comment_for_events(display('enquery_updated'), $en_comments);
              $subject=$cmt_text->title.' '.'is update at'.' '.display('enquery'). ' '.'stage';        
              $comment_id = $this->Leads_Model->add_comment_for_events($subject,$en_comments);         
        }else if($type == 2){                   
             //$comment_id = $this->Leads_Model->add_comment_for_events(display('lead_updated'), $en_comments); 
             $subject=$cmt_text->title.' '.'is update at'.' '.display('lead'). ' '.'stage';
             $comment_id = $this->Leads_Model->add_comment_for_events($subject,$en_comments);            
        }else if($type == 3){
             //$comment_id = $this->Leads_Model->add_comment_for_events(display('client_updated'), $en_comments);
             $subject=$cmt_text->title.' '.'is update at'.' '.display('client'). ' '.'stage';
             $comment_id = $this->Leads_Model->add_comment_for_events($subject,$en_comments);
        } 
        else
        {
             $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');
                if (!empty($enquiry_separation)) {                    
                    
                    $enquiry_separation = json_decode($enquiry_separation,true);                    
                    $title = $enquiry_separation[$type]['title'];                    
                    $comment_msg = $title.' Updated'; 
                    $comment_id = $this->Leads_Model->add_comment_for_events($comment_msg, $en_comments,$enqarr->comp_id);
                    
                }
        }
        
        if(!empty($enqarr)){        
            if(isset($_POST['inputfieldno'])) {                    
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);                
                $file_count = 0;                
                $file = !empty($_FILES['enqueryfiles'])?$_FILES['enqueryfiles']:'';                
                foreach($inputno as $ind => $val){
    
                 if ($inputtype[$ind] == 8) {                                                
                        $file_data    =   $this->doupload($file,$file_count);
                        if (!empty($file_data['imageDetailArray']['file_name'])) {
                            $file_path = base_url().'uploads/enq_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];
                            $biarr = array( 
                                            "enq_no"  => $en_comments,
                                            "input"   => $val,
                                            "parent"  => $enquiry_id, 
                                            "fvalue"  => $file_path,
                                            "cmp_no"  => $this->session->companey_id,
                                            "comment_id" => $comment_id
                                        );
                            $this->db->where('enq_no',$en_comments);        
                            $this->db->where('input',$val);        
                            $this->db->where('parent',$enquiry_id);
                            if($this->db->get('extra_enquery')->num_rows()){
                                if ($form_type == 1) {
                                    $this->db->insert('extra_enquery',$biarr);                                       
                                }else{                                    
                                    $this->db->where('enq_no',$en_comments);        
                                    $this->db->where('input',$val);        
                                    $this->db->where('parent',$enquiry_id);
                                    $this->db->set('fvalue',$file_path);
                                    $this->db->set('comment_id',$comment_id);
                                    $this->db->update('extra_enquery');
                                }
                            }else{
                                $this->db->insert('extra_enquery',$biarr);
                            }         
                        }
                        $file_count++;          
                    }else{
                        $biarr = array( "enq_no"  => $en_comments,
                                      "input"   => $val,
                                      "parent"  => $enquiry_id, 
                                      "fvalue"  => $enqinfo[$val],
                                      "cmp_no"  => $this->session->companey_id,
                                      "comment_id" => $comment_id
                                     );                                 
                        $this->db->where('enq_no',$en_comments);        
                        $this->db->where('input',$val);        
                        $this->db->where('parent',$enquiry_id);
                        if($this->db->get('extra_enquery')->num_rows()){  
                            if ($form_type == 1) {
                                $this->db->insert('extra_enquery',$biarr);                                       
                            }else{                                                              
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$enquiry_id);
                                $this->db->set('fvalue',$enqinfo[$val]);
                                $this->db->set('comment_id',$comment_id);
                                $this->db->update('extra_enquery');
                            }
                        }else{
                            $this->db->insert('extra_enquery',$biarr);
                        }
                    }                                      
                } //foreach loop end               
            }            
             
        }
        if (!$this->input->is_ajax_request()) {           
            $this->session->set_flashdata('message', 'Save successfully');
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        }else{
            echo json_encode(array('msg'=>'Saved Successfully','status'=>1));
        }
    }

    public function doupload($file,$key){        
        $upload_path    =   "./uploads/enq_documents/";
        $comp_id        =   $this->session->companey_id; //creare seperate folder for each company
        $upPath         =   $upload_path.$comp_id;
        
        if(!file_exists($upPath)){
            mkdir($upPath, 0777, true);
        }        
        $config = array(
            'upload_path'   => $upPath,            
            'overwrite'     => TRUE,
            'max_size'      => "2048000",
            'overwrite'    => false
        );
        $config['allowed_types'] = '*';

        $this->load->library('upload');
        $this->upload->initialize($config);
        $_FILES['enqueryfiles']['name']      = $file['name'][$key];
        $_FILES['enqueryfiles']['type']      = $file['type'][$key];
        $_FILES['enqueryfiles']['tmp_name']  = $file['tmp_name'][$key];
        $_FILES['enqueryfiles']['error']     = $file['error'][$key];
        $_FILES['enqueryfiles']['size']      = $file['size'][$key];        
        
        if(!$this->upload->do_upload('enqueryfiles')){             
            $data['imageError'] =  $this->upload->display_errors();
        }else{
            $data['imageDetailArray'] = $this->upload->data();        
        }
        return $data;
    }

    public function update_dynamic_query( $user_id=0,$comp_id=0)
    {
        $this->load->model('Enquiry_model');
        
        $this->load->library('user_agent');
   
    $enq_id = $this->input->post('enquiry_id');
    $cmnt_id = $this->input->post('cmnt_id');
    $tid    =   $this->input->post('tid');
    $form_type    =   $this->input->post('form_type');
    $enqarr = $this->db->select('*')->where('enquiry_id',$enq_id)->get('enquiry')->row();
    $en_comments = $enqarr->Enquery_id;
    
    $type = $enqarr->status;

    $user_id = $this->session->user_id??$user_id;
    $comp_id  = $this->input->post('comp_id')??$comp_id; 

    $inputnos   = $this->input->post("inputfieldno", true);
    $cmt_text = $this->db->select('input_label,forms.title as title')->where('input_id',$inputnos[0])->join('forms','forms.id=tbl_input.form_id')->get('tbl_input')->row(); 
    $subject=$cmt_text->title.' '.'Updated';
    //$stage_desc=$cmt_text->input_label.' '.'is Update';

          $this->Leads_Model->add_comment_for_events($subject,$en_comments,'',$user_id);

        if(!empty($enqarr)){        
            if(isset($_POST['inputfieldno'])) {                    
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);                
                $file_count = 0;                
                $file = !empty($_FILES['enqueryfiles'])?$_FILES['enqueryfiles']:'';                
                foreach($inputno as $ind => $val){
  

                 if ($inputtype[$ind] == 8) {            
                        $file_data    =   $this->doupload($file,$file_count,$comp_id);

                        if (!empty($file_data['imageDetailArray']['file_name'])) {
                           // $file_path = base_url().'uploads/ticket_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];
                            $file_path = base_url().'uploads/enq_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];                    
                                    $this->db->where('enq_no',$en_comments);    
                                    $this->db->where('comment_id',$cmnt_id);    
                                    $this->db->where('input',$val);        
                                    $this->db->where('parent',$enq_id);
                                    $this->db->set('fvalue',$file_path);
                                    $this->db->update('extra_enquery');
                             
                        }
                        $file_count++;          
                    }
                    else
                    {
                        
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$enq_id);
                                $this->db->where('comment_id',$cmnt_id); 
                                $this->db->set('fvalue',$enqinfo[$val]);
                                $this->db->update('extra_enquery');
                          
                    }                                      
                } //foreach loop end               
            }            
             
        }
        
        // $this->db->affected_rows();

         //$res = $this->Enquiry_model->update_dynamic_query();
         $this->session->set_flashdata('message', 'Save successfully');

         redirect($_SERVER['HTTP_REFERER']);
    }

public function updateclientpersonel() {  
                $unique_number = $this->input->post('unique_number');
            if(empty($unique_number)){
        $data = array(   
            'unique_number' => $this->uri->segment(3),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'marital_status' => $this->input->post('marital_status'),
            'last_comm' => $this->input->post('last_comm'),
            'mode_of_comm' => $this->input->post('mode_of_comm'),
            'remark' => $this->input->post('remark'),
            'mother_tongue' => $this->input->post('mother_tongue'),
            'other_language' => $this->input->post('other_language'),
            'corres_add_line1' => $this->input->post('corres_add_line1'),
            'corres_add_line2' => $this->input->post('corres_add_line2'),
            'corres_add_line3' => $this->input->post('corres_add_line3'),
            'corres_country_id' => $this->input->post('corres_country_id'),
            'corres_state_id' => $this->input->post('corres_state_id'),
            'corres_district_id' => $this->input->post('corres_district_id'),
            'corres_pincode' => $this->input->post('corres_pincode'),
            'corres_landmark' => $this->input->post('corres_landmark'),
            'perm_add_line1' => $this->input->post('perm_add_line1'),
            'perm_add_line2' => $this->input->post('perm_add_line2'),
            'perm_add_line3' => $this->input->post('perm_add_line3'),
            'perm_country_id' => $this->input->post('perm_country_id'),
            'perm_state_id' => $this->input->post('perm_state_id'),
            'perm_district_id' => $this->input->post('perm_district_id'),
            'perm_pincode' => $this->input->post('perm_pincode'),
            'perm_landmark' => $this->input->post('perm_landmark'),
            'created_by' => $this->session->user_id
           ); 
           $this->Taskstatus_model->insertpersonel($data);
           $this->Leads_Model->add_comment_for_events( display('Personel Details Inserted') , $unique_number);
            $this->session->set_flashdata('message', 'Save successfully');
           }else{
                $data = array(   
            'unique_number' => $this->input->post('unique_number'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'marital_status' => $this->input->post('marital_status'),
            'last_comm' => $this->input->post('last_comm'),
            'mode_of_comm' => $this->input->post('mode_of_comm'),
            'remark' => $this->input->post('remark'),
            'mother_tongue' => $this->input->post('mother_tongue'),
            'other_language' => $this->input->post('other_language'),
            'corres_add_line1' => $this->input->post('corres_add_line1'),
            'corres_add_line2' => $this->input->post('corres_add_line2'),
            'corres_add_line3' => $this->input->post('corres_add_line3'),
            'corres_country_id' => $this->input->post('corres_country_id'),
            'corres_state_id' => $this->input->post('corres_state_id'),
            'corres_district_id' => $this->input->post('corres_district_id'),
            'corres_pincode' => $this->input->post('corres_pincode'),
            'corres_landmark' => $this->input->post('corres_landmark'),
            'perm_add_line1' => $this->input->post('perm_add_line1'),
            'perm_add_line2' => $this->input->post('perm_add_line2'),
            'perm_add_line3' => $this->input->post('perm_add_line3'),
            'perm_country_id' => $this->input->post('perm_country_id'),
            'perm_state_id' => $this->input->post('perm_state_id'),
            'perm_district_id' => $this->input->post('perm_district_id'),
            'perm_pincode' => $this->input->post('perm_pincode'),
            'perm_landmark' => $this->input->post('perm_landmark'),
            'created_by' => $this->session->user_id
           ); 
             $this->Taskstatus_model->updatepersonel($data); 
             $this->Leads_Model->add_comment_for_events(display("personel_details_updated") , $unique_number);
           }  
            $this->session->set_flashdata('message', 'Save successfully');
            redirect($this->agent->referrer()); //updateclient
    }

    public function assign_enquiry() {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id[]');
           // echo json_encode($move_enquiry);
            $assign_employee = $this->input->post('assign_employee');
            $user = $this->User_model->read_by_id($assign_employee);
            $notification_data=array();$assign_data=array();
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $data['enquiry'] = $this->enquiry_model->enquiry_by_id($key);
                    $enquiry_code = $data['enquiry']->Enquery_id;
                   // $this->enquiry_model->assign_enquery($key, $assign_employee, $enquiry_code);
                    $assign_data[]=array('aasign_to'=> $assign_employee,
                        'assign_by'=>$this->session->user_id,
                        'update_date'=>date('Y-m-d H:i:s'),
                        'enquiry_id'=>$key);
                    $notification_data[]=array('assign_to'=>$assign_employee,
                        'assign_by'=> $this->session->user_id,
                        'assign_date'=>date('Y-m-d H:i:s'),
                        'enq_id'=> $key,
                        'enq_code'=>$enquiry_code,
                        'assign_status'=> 0);
                    $this->Leads_Model->add_comment_for_events(display('client_assigned'), $enquiry_code);
                    
                    $noti_msg = display('client_assigned');
                    $this->common_model->send_fcm($noti_msg,$noti_msg,$assign_employee);
                }
                $this->db->update_batch('enquiry',$assign_data,'enquiry_id');
                $this->db->insert_batch('tbl_assign_notification',$notification_data);
                echo display('save_successfully');
            } else {
                echo display('please_try_again');
            }
        }
    }
     public function add_amc(){
        $enqid = $this->input->post('enqid');
    
            $arr = array(
           
                 'enq_id'       =>  $enqid,
                 'comp_id'      => $this->session->userdata('companey_id'),
                 'product_name' => $this->input->post('productlist'), 
                 'amc_fromdate' => $this->input->post('fromdate'),  
                 'amc_todate'   =>   $this->input->post('todate'), 
                );
            // print_r($arr);exit();
            $result = $this->Doctor_model->add_amc($arr);
        if($result){
         $this->session->set_flashdata('message', "Added Successfuly");
            
           }
        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }
    
    /***********************************qualification Tab **************************************/
public function create_qualification() {  
      $eid=$this->input->post("enquiryid", true);
if(empty($eid)){
                    $this->db->select('*');
                    $this->db->from('enquiry');
                    $this->db->where('enquiry_id',$this->uri->segment(3));
                    $q= $this->db->get()->row();
         $enq_no=$q->Enquery_id;
         $cmp_no=$q->comp_id;
                $biarr[] = array( "enq_id"  => $enq_no,
                                  "xiipassfrom"   => $this->input->post("xiipassfrom", true),
                                  "xiipassto"   => $this->input->post("xiipassto", true),
                                  "xiiper"  => $this->input->post("xiiper", true), 
                                  "xiimb"  => $this->input->post("xiimb", true),
                                  "xiieng"  => $this->input->post("xiieng", true),
                                  "xiistrm"  => $this->input->post("xiistrm", true),
                                  "xiispec"  => $this->input->post("xiispec", true),
                                  "dpassfrom"  => $this->input->post("dpassfrom", true),
                                  "dpassto"  => $this->input->post("dpassto", true),
                                  "dper"  => $this->input->post("dper", true),
                                  "dback"  => $this->input->post("dback", true),
                                  "dtype"  => $this->input->post("dtype", true),
                                  "bpassfrom"  => $this->input->post("bpassfrom", true),
                                  "bpassto"  => $this->input->post("bpassto", true),
                                  "bper"  => $this->input->post("bper", true),
                                  "bback"  => $this->input->post("bback", true),
                                  "btype"  => $this->input->post("btype", true),
                                  "bspec"  => $this->input->post("bspec", true),
                                  "pgpassfrom"  => $this->input->post("pgpassfrom", true),
                                  "pgpassto"  => $this->input->post("pgpassto", true),
                                  "pgper"  => $this->input->post("pgper", true),
                                  "pgback"  => $this->input->post("pgback", true),
                                  "pgmtype"  => $this->input->post("pgmtype", true),
                                  "pgexp"  => $this->input->post("pgexp", true),
                                  "pgjob"  => $this->input->post("pgjob", true),
                                  "created_by" => $this->session->user_id,
                                  "cmp_no"  => $cmp_no,
                                  "created_date"  => date('d/m/Y')
                                 );     
                                 
            if(!empty($biarr)){
                $this->db->insert_batch('tbl_qualification', $biarr); 
            }               
            $this->session->set_flashdata('message', 'Save successfully');
}else{
    
          $this->db->set('xiipassfrom',$this->input->post("xiipassfrom", true));
                                  $this->db->set('xiipassto', $this->input->post("xiipassto", true));
                                  $this->db->set('xiiper', $this->input->post("xiiper", true)); 
                                  $this->db->set('xiimb', $this->input->post("xiimb", true));
                                  $this->db->set('xiieng', $this->input->post("xiieng", true));
                                  $this->db->set('xiistrm', $this->input->post("xiistrm", true));
                                  $this->db->set('xiispec', $this->input->post("xiispec", true));
                                  $this->db->set('dpassfrom', $this->input->post("dpassfrom", true));
                                  $this->db->set('dpassto', $this->input->post("dpassto", true));
                                  $this->db->set('dper', $this->input->post("dper", true));
                                  $this->db->set('dback', $this->input->post("dback", true));
                                  $this->db->set('dtype', $this->input->post("dtype", true));
                                  $this->db->set('bpassfrom', $this->input->post("bpassfrom", true));
                                  $this->db->set('bpassto', $this->input->post("bpassto", true));
                                  $this->db->set('bper', $this->input->post("bper", true));
                                  $this->db->set('bback', $this->input->post("bback", true));
                                  $this->db->set('btype', $this->input->post("btype", true));
                                  $this->db->set('bspec', $this->input->post("bspec", true));
                                  $this->db->set('pgpassfrom', $this->input->post("pgpassfrom", true));
                                  $this->db->set('pgpassto', $this->input->post("pgpassto", true));
                                  $this->db->set('pgper', $this->input->post("pgper", true));
                                  $this->db->set('pgback', $this->input->post("pgback", true));
                                  $this->db->set('pgmtype', $this->input->post("pgmtype", true));
                                  $this->db->set('pgexp', $this->input->post("pgexp", true));
                                  $this->db->set('pgjob', $this->input->post("pgjob", true));
                                  $this->db->set('updated_by',$this->session->user_id);
                                  $this->db->set('updated_date',date('d/m/Y'));
            $this->db->where('enq_id', $eid);
            $this->db->update('tbl_qualification');
            $this->session->set_flashdata('message', 'Updated successfully');
    
}
            redirect($this->agent->referrer()); //updateclient
    }
    /*************************************qualification tab End **********************************/
    /***********************************English Tab **************************************/
        public function create_english() {  
    $eid=$this->input->post("enquiryid", true);
if(empty($eid)){
                    $this->db->select('*');
                    $this->db->from('enquiry');
                    $this->db->where('enquiry_id',$this->uri->segment(3));
                    $q= $this->db->get()->row();
         $enq_no=$q->Enquery_id;
         $cmp_no=$q->comp_id;
                $biarr[] = array( "enq_id"  => $enq_no,
                
                                  "exam_ielts"   => $this->input->post("ielts", true), 
                                  "ieltsappeard"  => $this->input->post("ieltsappeard", true),
                                  "ieltsdate"  => $this->input->post("ieltsdt", true),
                                  "ieltslisten"  => $this->input->post("ieltslisten", true),
                                  "ieltsread"  => $this->input->post("ieltsread", true),
                                  "ieltswrite"  => $this->input->post("ieltswrite", true),
                                  "ieltsspeak"  => $this->input->post("ieltsspeak", true),
                                  "ieltsfinal"  => $this->input->post("ieltsfinal", true),
                                  
                                  "exam_pte"   => $this->input->post("pte", true), 
                                  "pteappeard"  => $this->input->post("pteappeard", true),
                                  "ptedt"  => $this->input->post("ptedt", true),
                                  "ptelisten"  => $this->input->post("ptelisten", true),
                                  "pteread"  => $this->input->post("pteread", true),
                                  "ptewrite"  => $this->input->post("ptewrite", true),
                                  "ptespeak"  => $this->input->post("ptespeak", true),
                                  "ptefinal"  => $this->input->post("ptefinal", true),
                                  
                                  "cmp_no"  => $cmp_no,
                                  "created_by" => $this->session->user_id,
                                  "created_date"  => date('d/m/Y')
                                 );     
                                 
            if(!empty($biarr)){
                $this->db->insert_batch('tbl_english', $biarr); 
            }
                
            $this->session->set_flashdata('message', 'Save successfully');
        }else{
            
                                  $this->db->set('exam_ielts',$this->input->post("ielts", true)); 
                                  $this->db->set('ieltsappeard',$this->input->post("ieltsappeard", true));
                                  $this->db->set('ieltsdate', $this->input->post("ieltsdt", true));
                                  $this->db->set('ieltslisten', $this->input->post("ieltslisten", true));
                                  $this->db->set('ieltsread', $this->input->post("ieltsread", true));
                                  $this->db->set('ieltswrite', $this->input->post("ieltswrite", true));
                                  $this->db->set('ieltsspeak', $this->input->post("ieltsspeak", true));
                                  $this->db->set('ieltsfinal', $this->input->post("ieltsfinal", true));
                                  
                                  $this->db->set('exam_pte', $this->input->post("pte", true)); 
                                  $this->db->set('pteappeard', $this->input->post("pteappeard", true));
                                  $this->db->set('ptedt', $this->input->post("ptedt", true));
                                  $this->db->set('ptelisten', $this->input->post("ptelisten", true));
                                  $this->db->set('pteread', $this->input->post("pteread", true));
                                  $this->db->set('ptewrite', $this->input->post("ptewrite", true));
                                  $this->db->set('ptespeak',$this->input->post("ptespeak", true));
                                  $this->db->set('ptefinal',$this->input->post("ptefinal", true));
                                  $this->db->set('updated_by',$this->session->user_id);
                                  $this->db->set('updated_date',date('d/m/Y'));
            $this->db->where('enq_id', $eid);
            $this->db->update('tbl_english');
            $this->session->set_flashdata('message', 'Updated successfully');
        }
            redirect($this->agent->referrer()); //updateclient
    }
    /*************************************English tab End **********************************/
    
       /***********************************payment tab **************************************/
        public function create_payment() {      
            $this->db->select('*');
            $this->db->from('enquiry');
            $this->db->where('enquiry_id',$this->uri->segment(3));
            $q = $this->db->get()->row();
            $enq_no = $q->Enquery_id;
            $cmp_no = $q->comp_id;
            $pay_type   = $this->input->post("typpay", true);
            $lead_stage   = $this->input->post("lead_stage_pay", true);
            $lead_description   = $this->input->post("lead_description_pay", true);
            $installment  = $this->input->post("modepay", true);
            $pay_date   = $this->input->post("dt", true);
            $amount   = $this->input->post("amt", true);        
            $reg_amount   = $this->input->post("reg_amt", true);
            $stamp_amount   = $this->input->post("stamp_amt", true);
            $biarr[] = array( "enq_id"  => $enq_no,
                              "stage_id"   => $lead_stage,
                              "desc_id"  => $lead_description, 
                              "pay_mode"  => $installment,
                              "pay_type"  => $pay_type,
                              "ins_dt"  => $pay_date,
                              "ins_amt"  => $amount,
                              "reg_amt"  => $reg_amount,
                              "stamp_amt"  => $stamp_amount,
                              "cmp_no"  => $cmp_no,
                              "created_by" => $this->session->user_id,
                              "created_date"  => date('d/m/Y')
                             );
                                 
            if(!empty($biarr)){
                $this->db->insert_batch('tbl_payment', $biarr); 
            }                
            $this->session->set_flashdata('message', 'Save successfully');
            redirect($this->agent->referrer()); //updateclient
    }
    
    public function update_setlled() {
        $recieved_amt   = $this->input->post("recieved_amt", true);
        $recieved_date   = $this->input->post("recieved_date", true);
        $pay_id   = $this->input->post("pay_id", true);     
        $this->db->set('recieved_amt', $recieved_amt);
        $this->db->set('recieved_date', $recieved_date);//if 2 columns
        $this->db->where('id', $pay_id);
        $this->db->update('tbl_payment');
        $this->session->set_flashdata('message', 'Updated successfully');
        redirect($this->agent->referrer());
    }   
    
    /*************************************payment tab End **********************************/
    /*************************************payment tab End **********************************/
    /************************************add aggriment**************************/
    public function create_aggrement() {
        $this->db->select('*');
                    $this->db->from('enquiry');
                    $this->db->where('enquiry_id',$this->uri->segment(3));
                    $q= $this->db->get()->row();
         $enq_no=$q->Enquery_id;
         $cmp_no=$q->comp_id;
        $agg_user   = $this->input->post("agg_user", true);
        $agg_mobile   = $this->input->post("agg_mobile", true);
        $agg_email  = $this->input->post("agg_email", true);
        $agg_adrs   = $this->input->post("agg_adrs", true);
        $agg_date   = $this->input->post("agg_date", true);
                $biarr[] = array( "enq_id"  => $enq_no,
                                  "agg_name"   => $agg_user,
                                  "agg_phone"  => $agg_mobile, 
                                  "agg_email"  => $agg_email,
                                  "agg_adrs"  => $agg_adrs,
                                  "agg_date"  => $agg_date,
                                  "comp_id"  => $cmp_no,
                                  "created_by" => $this->session->user_id,
                                  "created_date"  => date('d/m/Y')
                                 );     
            
        
            if(!empty($biarr)){
                $this->db->insert_batch('tbl_aggriment', $biarr); 
            }
                
            $this->session->set_flashdata('message', 'Save successfully');
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
    }
    public function find_same() {
        $smae_id = $this->input->post('cdata');
        echo json_encode($this->location_model->get_same($smae_id));
    }
    
    public function find_same_data() {
        $smae_id = $this->input->post('cdata');
        echo json_encode($this->location_model->get_same_data($smae_id));
    }
    
    public function upload_aggrement_team() {
    $ag_id = $this->input->post('ide');
    $ddata =  $this->db->where('id',$ag_id)->get('tbl_aggriment')->row();
	
	$this->db->select('quatation_number');
	$this->db->from('commercial_info');
                    $this->db->where('id',$ddata->deal_id);
                    $qutation_no= $this->db->get()->row();


    $this->db->from('enquiry');
                    $this->db->where('Enquery_id',$ddata->enq_id);
                    $q= $this->db->get()->row();

    $enq_id =$q->Enquery_id;
    $phone =$q->phone;
    
    $this->db->where('s_phoneno',$phone);
    $this->db->from('tbl_admin');
                    $q1= $this->db->get()->row();
   
if(!empty($q1)){
    $noti_id =$q1->pk_i_admin_id;
   }else{
    $noti_id = $this->session->user_id;
   }
   $notimsg = 'PO Uploded';
if(!empty($_FILES['file']['name']))
{

                $this->load->library("aws");
                $_FILES['userfile']['name']= $_FILES['file']['name'];
                $_FILES['userfile']['type']= $_FILES['file']['type'];
                $_FILES['userfile']['tmp_name']= $_FILES['file']['tmp_name'];
                $_FILES['userfile']['error']= $_FILES['file']['error'];
                $_FILES['userfile']['size']= $_FILES['file']['size'];    
                
                $image=$_FILES['userfile']['name'];
                $path=  "uploads/agrmnt/".$image;
                $ret = move_uploaded_file($_FILES['file']['tmp_name'] ,$path);

            if($ret){
                $this->aws->upload("",$path);   
            }
            if($this->input->post('agreement_attachment') == 2){
                $this->db->set('signed_agreement',$path);
                $notimsg = 'Signed Agreement Uploded';
            }else{
                $this->db->set('po_file',$path);                
            }
                $this->db->where('id', $ag_id);
                $this->db->update('tbl_aggriment'); 
               
            }
            
            $assign_data_noti[]=array('create_by'=> $noti_id,
                        'subject'=>$notimsg,
                        'query_id'=>$enq_id,
                        'task_date'=>date('d-m-Y'),
                        'task_time'=>date('H:i:s')
                        );
           $this->db->insert_batch('query_response',$assign_data_noti);
		   $this->Leads_Model->add_comment_for_events('Deal '.$qutation_no->quatation_number.' - '.$notimsg,$enq_id,0,$this->session->user_id);
           $this->load->library('user_agent');
           
           if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
           }else{
             redirect($this->agent->referrer()); //updateclient
           }
}
/*******************************************************************************end add aggriment***************************************************/
    public function upload_aggrement_student() {
    $enquiry_id = $this->input->post('ide');
    $this->db->from('tbl_aggriment');
                    $this->db->where('id',$enquiry_id);
                    $q= $this->db->get()->row();
    $enq_id =$q->enq_id;
    $noti_id =$q->created_by;
    
            
if(!empty($_FILES['file']['name'])){
                $this->load->library("aws");
                $_FILES['userfile']['name']= $_FILES['file']['name'];
                $_FILES['userfile']['type']= $_FILES['file']['type'];
                $_FILES['userfile']['tmp_name']= $_FILES['file']['tmp_name'];
                $_FILES['userfile']['error']= $_FILES['file']['error'];
                $_FILES['userfile']['size']= $_FILES['file']['size'];    
                
                $image=$_FILES['userfile']['name'];
                $path=  "uploads/agrmnt/".$image;
                $ret = move_uploaded_file($_FILES['userfile']['tmp_name'] ,$path);
            if($ret){                                       $this->aws->upload("",$path);                                   }
            $this->db->set('sign_file',$path);
            $this->db->set('updated_by',$this->session->user_id);
            $this->db->where('id', $enquiry_id);
            $this->db->update('tbl_aggriment'); 
            }
            $assign_data_noti[]=array('create_by'=> $noti_id,
                        'subject'=>'Agrrement Uploded By Student',
                        'query_id'=>$enq_id,
                        'task_date'=>date('d-m-Y'),
                        'task_time'=>date('H:i:s')
                        );
           $this->db->insert_batch('query_response',$assign_data_noti);
           $this->load->library('user_agent');
    redirect($this->agent->referrer());
    }
    /***********************end add aggriment***********************/

public function desposition()
{
        if (user_role('60') == true) {
        }
        $this->load->model('Datasource_model');
        $data['sourse'] = $this->report_model->all_source();
        $data['datasourse'] = $this->report_model->all_datasource();
        $data['lead_score'] = $this->enquiry_model->get_leadscore_list();
        $data['dfields']  = $this->enquiry_model->getformfield();
        $data['data_type'] = 1;
        $this->session->unset_userdata('enquiry_filters_sess');
        if (!empty($this->session->enq_type)) {
            $this->session->unset_userdata('enq_type', $this->session->enq_type);
        }
        // $process =  0;
        // if(!empty($this->session->process))
        //  $process = implode(',', $this->session->process);
         $desp = $this->db->where('stg_id',$_GET['desposition'])->get('lead_stage')->row();
            $des_title = '';
          if(!empty($desp))
          {
            $des_title = $desp->lead_stage_name;
          }
        $data['title'] = $des_title;
        $data['subsource_list'] = $this->Datasource_model->subsourcelist();
        $data['user_list'] = $this->User_model->companey_users();
        $data['created_bylist'] = $this->User_model->read();
        $data['products'] = $this->dash_model->get_user_product_list();
        $data['drops'] = $this->enquiry_model->get_drop_list();
        $data['all_stage_lists'] = $this->Leads_Model->get_leadstage_list_byprocess1($this->session->process,1);
        $data['prodcntry_list'] = $this->enquiry_model->get_user_productcntry_list();
        $data['state_list'] = $this->enquiry_model->get_user_state_list();
        $data['city_list'] = $this->enquiry_model->get_user_city_list();
        $data['filterData'] = $this->Ticket_Model->get_filterData(1);
 
        $data['content'] = $this->load->view('enquiry_n', $data, true);
        $this->load->view('layout/main_wrapper', $data);
}
public function view_editable_aggrement()
{
    if(user_role('1004')==true){
            
        }
   
  
    if(empty($_POST))
        $_POST = array();
    if(!empty($_POST['agg_frmt']))
    {   $_POST['edit'] = 1;
        $_POST['checkss'] = array();
        if($_POST['agg_frmt']=='vtrans')
        echo $this->load->view('aggrement/input-vtrans',array(),TRUE);
        else
            echo'No Agrrement';
    }
 
}
    /**************************Grenerate aggriment**************************/
    public function generate_aggrement() {
      
         if(user_role('1004')==true){
            
        }
        if(!empty($_POST))
        {   // exit();
            foreach ($_POST as $key => $v)
            {
                if(is_array($v))
                {
                    foreach ($v as $k => $value) 
                    {
                        if(empty($value))
                            $_POST[$key][$k] = ' ';
                    }
                   
                }
                else
                {
                    if(empty($v))
                            $_POST[$key] = ' ';
                }
            }
            
            if(empty($_POST['checkss']))
                $_POST['checkss'] = array();
            if(empty($_POST['ip'][14]))
                    $_POST['ip'][14] = 'none';
          
           $data['rewind']= $this->load->view('aggrement/input-vtrans',$_POST,TRUE);
            $viewfile = $this->load->view('aggrement/final_page',$data,true);
            //echo $viewfile;exit();
            $this->load->library('pdf');
            $this->pdf->create($viewfile,0,'',array(0,0,600,5200));
        }
        exit();
        $pdf_name = $this->input->post('agg_frmt');
        if($pdf_name=='BAA'){
            $data['title'] = 'Bangalore-Australia-Agreement';
            $this->load->helpers('dompdf');
            $viewfile = $this->load->view('aggrement/Bangalore-Australia-Agreement', $data, TRUE);
            pdf_create($viewfile,'Bangalore-Australia-Agreement'.$this->session->user_id);
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        }elseif($pdf_name=='vtrans'){
            $data['title'] = 'vtrans-vtrans';
            $this->load->helpers('dompdf');
            $viewfile = $this->load->view('aggrement/vtrans-Agreement', $data, TRUE);
            pdf_create($viewfile,'vtrans-Agreement'.$this->session->user_id);            
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        }
    }
    /***********************end Generate aggriment*****************************/
    public function visits()
    {
        if(user_role('1020') || user_role('1021') || user_role('1022')){
            
        }
        $this->load->model('Client_Model');
        $this->load->model('Enquiry_Model');
        $data['title'] = display('visit_list');
        if($this->session->companey_id == 65 && $this->session->user_right == 215){
            $data['created_bylist'] = $this->User_model->read(147,false);
        }else{
            $data['created_bylist'] = $this->User_model->read();
        }  
        //print_r($data['contact_list']->result_array()); exit();
        //$data['all_enquiry'] = $this->Enquiry_Model->all_enqueries('1,2,3');
		$data['all_enquiry'] = $this->Enquiry_Model->all_enqueries_clients('1,2,3');
        
        $data['createby_company'] = $this->Client_Model->getCompanyList_visit()->result();
		$data['common_company'] = $this->Client_Model->getcommonCompanyList_visit()->result();
		$data['company_list'] = array_merge($data['createby_company'],$data['common_company']);
		//echo '<pre>';print_r($data['company_list']);exit;
        $this->load->model('Branch_model');
        $data['region_list']=$this->Branch_model->sales_region_list()->result();
        $data['area_list']=$this->Branch_model->sales_area_list()->result();
        $data['branch_list']=$this->Branch_model->branch_list()->result();
		$data['filterData'] = $this->Ticket_Model->get_filterData('vis');
		
		if(!empty($_GET['from'])){
			$data['filterData']['from_date'] = $_GET['from'];
		}

		if(!empty($_GET['to'])){
			$data['filterData']['to_created'] = $_GET['to'];
		}
		
		if(!empty($_GET['employee'])){
			$data['filterData']['createdby'] = $_GET['employee'];
		}
		
        $data['content'] = $this->load->view('enquiry/visits', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function user_wise_visit(){
        $data['title'] = "User Wise Visits";
        $this->load->model('visit_datatable_model');
        $data['visits'] = $this->visit_datatable_model->userwisevisits($_GET);
        $data['content'] = $this->load->view('enquiry/userwisevisits', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function visit_details()
    {
         if(user_role('1020') || user_role('1021') || user_role('1022')){
        }
        $id=$this->uri->segment('3');
		//$visitdata= $this->db->where('id',$id)->join('visit_details','visit_details.visit_id=tbl_visit.id')->get('tbl_visit');
        $visitdata= $this->db->where('id',$id)->get('tbl_visit');
        if($visitdata->num_rows()!=0){
            $data['details'] =$visitdata->row();
            $this->load->model('Client_Model');
            $this->load->model('Enquiry_Model');
            $data['title'] = display('visit_list');
           // print_r($data['contact_list']->result_array()); exit();
            $data['all_enquiry'] = $this->Enquiry_Model->all_enqueries('1,2,3');
            $data['company_list'] = $this->Client_Model->getCompanyList()->result();
            $data['content'] = $this->load->view('enquiry/visit_details', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }else{
            $this->session->set_flashdata('message', 'Travel History not found');

            redirect('client/visits');
        }
       
    }
	
	public function visit_live(){                
        $id=$this->uri->segment('3');
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
    

    
    public function updateVisit_remarks()
    {
        
        if($_POST){
            $travelledtype=$this->input->post('travelledtype');
            $remarks=$this->input->post('remarks');
            $rating=$this->input->post('rating');
            $visit_id=$this->input->post('visit_id');
            $data=['remarks'=>$remarks,'rating'=>$rating,'travelled_type'=>$travelledtype];
            $this->db->where('id',$visit_id)->update('tbl_visit',$data);
             $this->db->last_query();
            $this->session->set_flashdata('message', 'Remark Updated');
            redirect($this->agent->referrer()); 
            
        }
    }

    public function ajax_visit_details($vid=0)
    {
        if(empty($vid))
            $vid = $this->input->post('vid');

        $visittable=$this->db->where(array('visit_id'=>$vid))->get('visit_details')->result();

        if(empty($visittable))
        {
            echo'<center>No data to show</center>';exit();
        }
        echo'<table class="table table-responseive table-stripped">
                <thead >
                <tr>
                <th>S. No</th>
                <th>Travel Start </th>
                <th>Travel End </th>
                <th>In Hours </th>
                <th>Meeting Start </th>
                <th>Meeting End </th>
                <th>In Hours </th>

                </tr>
                </thead>
                <tbody>';
                $i=1;
                $waypoints=[];
                foreach ($visittable as $key => $value) 
                {
                    echo'<tr>
                  <td>'.$i++.'</td>
                <td>';
             
                 if(($value->visit_start)!=NULL)
                 {
                  echo date("F jS, Y, g:i a", strtotime($value->visit_start)); 
                 }
                echo'</td>
                <td>';
                
                if(($value->visit_end)!=NULL )
                { 
                    echo date("F jS, Y, g:i a ", strtotime($value->visit_end)); 
                }
                echo'</td>
                <td>';
                
                if(($value->visit_end!=NULL AND $value->visit_start!=NULL))
                {
                 $minutes= round(abs(strtotime($value->visit_start) - strtotime($value->visit_end))/60);
                  echo $hours = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60); }else{
                    echo'N/A';

                  }
                echo'</td>
                <td>';
                
                if(($value->start_time)!=NULL)
                { 
                    echo date("F jS, Y, g:i a", strtotime($value->start_time));
                } 
            
                echo'</td>
                <td>';
                if(($value->end_time)!=NULL)
                { 
                    echo date("F jS, Y, g:i a", strtotime($value->end_time)); 
                }
                echo'</td>
                <td>';
                if(($value->start_time!=NULL AND $value->end_time!=NULL))
                {
                 $minutes= round(abs(strtotime($value->start_time) - strtotime($value->end_time))/60);
                  echo $hours = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60); 
                }else{
                    echo'N/A';
                  }
                echo'</td>
                </tr>';
        
                //$waypoints[]=$value->way_points;
                //  array_push(, json_decode($value->way_points));  
                // print_r($way_points);
                } 
                // $arr_m=[];
                // foreach ($waypoints as $key => $value) {
                // foreach (json_decode($value) as $key => $values) {
                //    $arr_m[]=$values;
                // }
                // }
                // print_r($arr_m);
                // die();
                //  $totalpoints=count($arr_m);
                // $newpoints=array();
                // // print_r($totalpoints);
                // $cuts=$totalpoints/23;
                // for ($i=0; $i < $totalpoints; $i+=$cuts) { 
                //   array_push($newpoints,$arr_m[$i]);
                // }
                //  $lastKey = key(array_slice($newpoints, -1, 1, true));
                // $firstpoint=$newpoints[0];
                // $secondpoint=$newpoints[$lastKey];

                echo'</tbody>
                </table>';
    }

    public function report(){
        if(user_role('1020') || user_role('1021') || user_role('1022')){
        }
        $this->load->model('report_model');
        $data['title'] = 'Visit Report';
       $data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));                  
       $content = '';       
        if ($_POST) {           
           $from        =   date("Y-m-d", strtotime($this->input->post('date_from')));
           $to      =   date("Y-m-d", strtotime($this->input->post('date_to')));
            $employee   =   $this->input->post('employee');         
           $comp_id=$this->session->companey_id;            
           $data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));  
          $all_reporting_ids    =    $this->common_model->get_categories($this->session->user_id);      
           $where = "(visit_details.comp_id=$comp_id)";    
           $where .= " AND Date(visit_details.visit_start) >= '$from' AND Date(visit_details.visit_start) <= '$to'";
           if($employee=='0'){ 
           $where .= " AND ( visit_details.created_by IN (".implode(',', $all_reporting_ids).') )';
           }else{ $where .= " AND ( visit_details.created_by=$employee)";  }
           $data['reports'] = $this->db->select('tbl_visit.*,enquiry.*,tbl_admin.*,visit_details.*')
                                        ->select('(select sum(amount) from tbl_expense where tbl_expense.approve_status=2 AND tbl_expense.visit_id = tbl_visit.id) as visit_expSum')
                                        ->where($where)
                                       ->join('tbl_admin','tbl_admin.pk_i_admin_id=visit_details.created_by')
                                       ->join('tbl_visit','tbl_visit.id=visit_details.visit_id')
                                       ->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id')
                                       ->get('visit_details')->result();    
          $content .= $this->load->view('enquiry/visit_report',$data,true);
          $data['content'] = $content;

        }else{
            $date = date("Y-m-d");      
            $comp_id=$this->session->companey_id;           
            $employee = array();
            $data['att_date'] = $date;
            $data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id')); 
           $all_reporting_ids    =    $this->common_model->get_categories($this->session->user_id);      
            $where = "(visit_details.comp_id=$comp_id)";      
           $where .= " AND ( visit_details.created_by IN (".implode(',', $all_reporting_ids).') )';
            $data['reports'] = $this->db->select('tbl_visit.*,enquiry.*,tbl_admin.*,visit_details.*')
                                        ->where($where)
                                        ->select('(select sum(amount) from tbl_expense where tbl_expense.approve_status=2 AND tbl_expense.visit_id = tbl_visit.id) as visit_expSum')

                                        ->join('tbl_admin','tbl_admin.pk_i_admin_id=visit_details.created_by')
                                        ->join('tbl_visit','tbl_visit.id=visit_details.visit_id')
                                        ->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id')
                                        
                                        ->get('visit_details')->result();   
                                        // echo $this->db->last_query();
                                        // die();
           $content .= $this->load->view('enquiry/visit_report',$data,true);
           $data['content'] = $content;
        }
       $this->load->view('layout/main_wrapper',$data);
    }
    
   public function add_expense()
   {
                $visit_id= $this->input->post('visit_id');
                $vd_id= $this->input->post('id');
                $finalfilename='';
                $uid=$this->session->user_id;
                $comp_id=$this->session->companey_id;
                // fetch enquiry sing visit id
                $visit=$this->db->where('id',$visit_id)->get('tbl_visit');
               
                if($visit->num_rows()!=0){
                   $vdata= $visit->row(); 
                   $enq_id=$vdata->enquiry_id; 
                }
                else
                {
                    $this->session->set_flashdata('exception','Invalid Visit');
                    redirect($_SERVER['HTTP_REFERER']);
                }
                foreach ($_POST['expense'] as $key =>$value ) {
                        $expense = $_POST['expense'][$key];
                        $amount = $_POST['amount'][$key];
                        if($_FILES['imagefile']['name'][$key]){
                        $file_name =$_FILES['imagefile']['name'][$key];
                        $file_size =$_FILES['imagefile']['size'][$key];
                        $file_tmp  =$_FILES['imagefile']['tmp_name'][$key];
                        $file_type =$_FILES['imagefile']['type'][$key];  
                        $upload_path    =   "assets/images/user/";
                        $finalfilename='expense_'.time().$file_name;
                        move_uploaded_file($file_tmp,$upload_path.$finalfilename);
                        }
                        // visit type =2
						if($expense!='5'){
                        $data=['type'=>2,
                               'amount'=>$amount,
                               'visit_id'=>$visit_id,
                               'created_by'=>$uid,
                               'expense'=>$expense,
                               'file'=>$finalfilename,
                               'comp_id'=>$comp_id,
                               ];
						}else{

   $rate_data = $this->db->get_where('tbl_visit',array('id'=>$visit_id))->row_array();
   if(!empty($rate_data)){
      $rate = $rate_data['user_rate'];
   }else{
      $rate = 10;
   }
   
    $exp_data=['manual_distence'=>$amount];
    $this->db->where(array('id'=>$visit_id))->update('tbl_visit',$exp_data);
   
							$data=['type'=>1,
                               'amount'=>($amount)*$rate,
                               'visit_id'=>$visit_id,
                               'created_by'=>$uid,
                               'expense'=>$expense,
                               'file'=>$finalfilename,
                               'comp_id'=>$comp_id,
                               ];							
						}
                    $this->db->insert('tbl_expense',$data);
                    
                    $this->Leads_Model->add_comment_for_events('Expense Added',$enq_id,0,$uid);
                   
                }

                $this->session->set_flashdata('message', 'Travel Expense Added');
                // redirect('/visits/visit_details/'.$visit_id.'');   
                redirect($this->agent->referrer()); //updateclient


   }
public function update_expense_status()
{
    if($_POST)
    {
        $comp_id=$this->session->companey_id;
        $user_id=$this->session->user_id;
        foreach ($_POST['exp_ids'] as $key => $value) {
        // echo $value;
        $data=['uid'=>$user_id,'remarks'=>$_POST['remarks'],'approve_status'=>$_POST['status']];
        print_r($value);
        $this->db->where(array('comp_id'=>$comp_id,'id'=>$value))->update('tbl_expense',$data);
            }
    }
}
public function visit_expense_status()
{
    if($_POST)
    {
        $comp_id=$this->session->companey_id;
        $user_id=$this->session->user_id;
       // print_r($_POST);exit;
    foreach ($_POST['exp_ids'] as $key => $value) {
        $visit_row = $this->db->select("enquiry.Enquery_id as comment_id,tbl_visit.user_id as visit_creator")
                        ->from("tbl_visit")
                        ->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left')
                        ->where('tbl_visit.id', $value)
                        ->get();
            $comment_id = $visit_row->row()->comment_id;
            $visit_creator = $visit_row->row()->visit_creator;
            if($_POST['status']=='1'){
            $subject = 'Visit Reject'; 
            }else{
            $subject = 'Visit Approve'; 
            }               

        $data=['uid'=>$user_id,'remarks'=>$_POST['remarks'],'approve_status'=>$_POST['status']];
        $this->db->where(array('comp_id'=>$comp_id,'visit_id'=>$value))->update('tbl_expense',$data);
        //timeline code here
        $this->Leads_Model->add_comment_for_events_stage($subject,$comment_id,0,0,$_POST['remarks'],0);
        //Bell botification code here
        $assign_data_noti[]=array(
            'create_by'=> $user_id,
            'related_to'=> $visit_creator,
            'subject'=>$subject,
            'task_remark'=>$_POST['remarks'],
            'query_id'=>$comment_id,
            'task_date'=>date('d-m-Y'),
            'comp_id'=>$this->session->companey_id,
            'task_time'=>date('H:i:s')
        );
        $this->db->insert_batch('query_response',$assign_data_noti);
        if(!empty($visit_creator)){        
            $user_row = $this->user_model->read_by_id($visit_creator);
            if(!empty($user_row)){
                $this->message_models->smssend($user_row->s_phoneno, $subject);                
                $this->message_models->sendwhatsapp($user_row->s_phoneno, $subject);
                $this->message_models->send_email($user_row->s_user_email, 'Visit Notification', $subject);
            }
        }
    }
            }
}

public function all_update_expense_status()
{
    if($_POST)
    {
        $comp_id=$this->session->companey_id;
        $user_id=$this->session->user_id;
        $visit_id=$this->session->visit_id;
      $createdBy= $this->db->select('user_id,id')->where('id',$visit_id)->get('tbl_visit')->row();
        $createdBy_id=$createdBy->user_id;
    foreach ($_POST['exp_ids'] as $key => $value) {
        // echo $value;
        $data=['uid'=>$user_id,'remarks'=>$_POST['remarks'],'approve_status'=>$_POST['status']];
        // print_r($data);
        $this->db->where(array('comp_id'=>$comp_id,'id'=>$visit_id))->update('tbl_expense',$data);
    }
    $ins_arr = array(
        'comp_id'        =>  $this->session->companey_id,
        'subject'        =>  'Need for approval',
        'task_type'      =>  18,
        'task_status'    =>  2,
        'query_id'       =>  $visit_id,
        'task_remark'    =>  $_POST['remarks'],
          'task_date'    =>   date("d-m-Y"),
        'task_time'      => date("H:i:s"),
        'related_to'     => $createdBy_id,
        'create_by'      =>  $this->session->user_id
    );
    $this->db->insert('query_response',$ins_arr);
    $this->session->set_flashdata('message', 'Request successfully submitted');
            }
}

    public function deals($specific=0)
    { 
        if(user_access('1000') || user_access('1001') || user_access('1002')){            
        }else {
            redirect('restrected');
            exit();
        }
        $this->load->model('Client_Model');
        $this->load->model('Enquiry_Model');
        $data['title'] = 'Deals'; //display('deal_list');       
        $data['all_enquiry'] = $this->Enquiry_Model->all_enqueries('2,3,4,5,6,7,8');
        $data['branch']=$this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();
		$data['region']=$this->db->where('comp_id',$this->session->companey_id)->get('sales_region')->result();
        $data['company_list'] = $this->Client_Model->getCompanyList()->result();
		if($this->session->companey_id == 65 && $this->session->user_right == 215){
            $data['created_bylist'] = $this->User_model->read(147,false);
        }else{
            $data['created_bylist'] = $this->User_model->read();
        }
		$data['filterData'] = $this->Ticket_Model->get_filterData('deal');
		
		if(!empty($_GET['from'])){
			$data['filterData']['d_from_date'] = $_GET['from'];
		}

		if(!empty($_GET['to'])){
			$data['filterData']['d_to_date'] = $_GET['to'];
		}
		
		if(!empty($_GET['employee'])){
			$data['filterData']['createdby'] = $_GET['employee'];
		}
		
        $data['content'] = $this->load->view('enquiry/deals', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
	
    public function short_dashboard_count_deals()
    {
        //print_r($_POST); exit();
		$this->common_query_short_dashboard_deals();
		$this->db->where('info.original=1');
        $data['active_deals_num'] = $this->db->count_all_results();
		
        $this->common_query_short_dashboard_deals();
        $data['all_deals_num'] = $this->db->count_all_results();

        $this->common_query_short_dashboard_deals();
        $this->db->where('info.status=1');
        $data['all_done_num'] = $this->db->count_all_results();

        $this->common_query_short_dashboard_deals();
        $this->db->where('info.status=0');
        $data['all_pending_num'] = $this->db->count_all_results();

        $this->common_query_short_dashboard_deals();
        $this->db->where('info.status=2');
        $data['all_deferred_num'] = $this->db->count_all_results();

        echo json_encode($data);
    }

    public function common_query_short_dashboard_deals()
    {
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        $table = 'commercial_info';
        // Set orderable column fields
        $column_order = array('info.id','enq.name','info.branch_type','info.booking_type','info.business_type','info.creation_date','info.updation_date','info.status');

        // Set searchable column fields

        $column_search = array('enq.name');

    
        
        $order = array('id' => 'desc');

        $this->db->select('info.*,enq.name,enq.Enquery_id,enq.status as enq_type');
        $this->db->from($table.' info');
        $this->db->join('enquiry enq','enq.enquiry_id=info.enquiry_id','left');
        $this->db->where("info.comp_id",$this->session->companey_id);

        $where='';

        
        $where .= "( enq.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 
        
        
        $and =1;

        if(!empty($_POST['date_from']) && !empty($_POST['date_to']))
        {   
            if($and)
                $where.=" and ";

            $where.=" (date(info.creation_date) >='".$_POST['date_from']."' and date(info.creation_date) <='".$_POST['date_to']."' ) ";
            $and =1;
        }
        else if(!empty($_POST['date_from']))
        {
             if($and)
                $where.=" and ";

            $where.=" (date(info.creation_date) >='".$_POST['date_from']."' ) ";
            $and =1;
        }
        else if(!empty($_POST['date_to']))
        {
              if($and)
                $where.=" and ";

            $where.=" (date(info.creation_date) <='".$_POST['date_to']."' ) ";
            $and =1;
        }

        if(!empty($_POST['enq_for']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.enquiry_id ='".$_POST['enq_for']."' ) ";
            $and =1;
        }



          if(!empty($_POST['booking_type']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.booking_type ='".$_POST['booking_type']."' ) ";
            $and =1;
        }

        if(!empty($_POST['booking_branch']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.booking_branch ='".$_POST['booking_branch']."' ) ";
            $and =1;
        }

        if(!empty($_POST['delivery_branch']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.delivery_branch ='".$_POST['delivery_branch']."' ) ";
            $and =1;
        }

        // if(!empty($_POST['paymode']))
        // {
        //       if($and)
        //         $where.=" and ";

        //     $where.=" (info.paymode ='".$_POST['paymode']."' ) ";
        //     $and =1;
        // }

        if(!empty($_POST['p_amnt_from']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.potential_amount >= '".$_POST['p_amnt_from']."' ) ";
            $and =1;
        }

        if(!empty($_POST['p_amnt_to']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.potential_amount <= '".$_POST['p_amnt_to']."' ) ";
            $and =1;
        }


        if(!empty($_POST['top_filter']))
        {   
            if($and && $_POST['top_filter']!='all')
                $where.=" and ";
            if($_POST['top_filter']=='all')
            {

            }
            else if($_POST['top_filter']=='done')
            {
                 $where.=" info.status = 1";
                 $and =1;
            }
            else if($_POST['top_filter']=='pending')
            {
                $where.=" info.status = 0";
                 $and =1;
            }
            else if ($_POST['top_filter']=='deferred')
            {
                $where.=" info.status = 2";
                 $and =1;
            }
            
        }

        if(!empty($_POST['specific_list']))
        {
            if($and)
                $where.=" and ";

            $where.=" ( info.id IN (".$_POST['specific_list'].") ) ";
            $and =1;
        }


        if($where!='')
            $this->db->where($where);
        //echo $where; exit();

        $i = 0;

        if(empty($postData['search']['value']))
            $postData['search']['value']='';

        // loop searchable columns 
        foreach($column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
    }

    public function contacts_load_data()
    { 
        $this->load->model('contacts_datatable_model');
        $result = $this->contacts_datatable_model->getRows($_POST);
        //echo $this->db->last_query(); exit();
        //print_r($result); exit();
        $colsall  = true;
        $cols = array();
        if(!empty($_POST['allow_cols']))
        {
            $cols  = explode(',',$_POST['allow_cols']);
            $colsall = false;
        }
        //print_r($cols); exit();
        $data = array();
        $header = array();
        $i=1;
        foreach ($result as $key => $res)
        {
            $sub = array();

            $sub[] = $i++;
            $header[0] = '#';
            if(!empty($_POST['view_all']))
            {
                if($res->status=='1')
                    $url = base_url('enquiry/view/').$res->enquiry_id;
                else if($res->status=='2')
                    $url = base_url('lead/lead_details/').$res->enquiry_id;
                else if($res->status=='3')
                    $url = base_url('client/view/').$res->enquiry_id;
                else
                    $url = base_url('client/view/').$res->enquiry_id;

                if($colsall || in_array(1,$cols))
                    //$sub[] = '<a href="'.$url.'">'.$res->enq_name.'</a>'??'NA';
                $sub[] = '<a href="'.$url.'">'.$res->client_name.'</a>'??'NA';
                $header[1] = 'Name';
            }
           
            if($colsall || in_array(2,$cols)){
                $sub[] = trim($res->company_name)??'NA';
                $header[2] = 'Company Name';
            }

            if($colsall || in_array(3,$cols)){
                $sub[] = trim($res->desi_name)??'NA';
                $header[3] = 'Designation';
            }
            
            if($colsall || in_array(4,$cols)){
                $sub[] = trim($res->c_name)??'NA';
                $header[4] = 'Contact Name';
            }

            if($colsall || in_array(5,$cols)){
                $sub[] = trim($res->contact_number)?$res->contact_number:'NA';
                $header[5] = 'Contact Number';
            }

            if($colsall || in_array(6,$cols)){
                $sub[] = $res->emailid??'NA';
                $header[6] = 'Email ID';
            }

            if($colsall || in_array(7,$cols))
            {
                $sub[] = $res->decision_maker?'Yes':'No';
                $header[7] = 'Decision Maker';
            }

            if($colsall || in_array(8,$cols))
            {
                $sub[] = trim($res->other_detail)?$res->other_detail:'NA';
                $header[8] = 'Other Details';
            }
            

            if($colsall || in_array(9,$cols)){
                $sub[] = $res->created_at??'NA';
                $header[9] = 'Created At';
            }

            if($colsall || in_array(10,$cols))
            {
                $html = '';
                $html.='<td style="width:50px;">
                                            <div class="btn-group">';
                  if(user_access('1012'))
                  {
                    $html.='<button class="btn btn-warning btn-xs" data-cc-id="'.$res->cc_id.'" onclick="edit_contact(this)">
                      <i class="fa fa-edit"></i>
                    </button>';
                  }
                  if(user_access('1011'))
                  {
                    $html.='<button class="btn btn-danger btn-xs"  data-cc-id="'.$res->cc_id.'" onclick="deleteContact(this)">
                      <i class="fa fa-trash"></i>
                    </button>';
                  }
                $sub[]=$html;
                $header[10] = 'Action';
            }
            $data[] =$sub;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$this->contacts_datatable_model->countAll(),
            "recordsFiltered" => $this->contacts_datatable_model->countFiltered($_POST),
            "data" => $data,
            "headings"=>$header,
        );
        echo json_encode($output);
    }
	
	public function log_load_data()
    { 
        $this->load->model('contacts_datatable_model');
        $result = $this->contacts_datatable_model->getRows_log($_POST);
        $acolarr = $dacolarr = array();
		if (isset($_COOKIE["allowcols"])) {
			$showall = false;
			$acolarr  = explode(",", trim($_COOKIE["allowcols"], ","));
		} else {
			$showall = true;
		}
		if (isset($_COOKIE["dallowcols"])) {
			$dshowall = false;
			$dacolarr  = explode(",", trim($_COOKIE["dallowcols"], ","));
		}
		if (!empty($enqarr) and !empty($dacolarr)) {
		}
        $data = array();
        $header = array();
        $i=1;
        foreach ($result as $key => $res)
        {
            $sub = array();

            $sub[] = $i++;
            $header[0] = '#';
            //if(!empty($_POST['view_all']))
           // {
                if($res->status=='1')
                    $url = base_url('enquiry/view/').$res->enquiry_id;
                else if($res->status=='2')
                    $url = base_url('lead/lead_details/').$res->enquiry_id;
                else if($res->status=='3')
                    $url = base_url('client/view/').$res->enquiry_id;
                else
                    $url = base_url('client/view/').$res->enquiry_id;

                if($showall || in_array(1,$acolarr)){
                $sub[] = '<a href="'.$url.'">'.$res->client_name.'</a>'??'NA';
                $header[1] = 'Client Name';
				}
            //}
           
            if($showall || in_array(2,$acolarr)){
                $sub[] = trim($res->company_name)??'NA';
                $header[2] = 'Company Name';
            }
			
			if($showall || in_array(3,$acolarr)){
                $sub[] = trim($res->enq_name)??'NA';
                $header[3] = 'Customer Name';
            }

            if($showall || in_array(4,$acolarr)){
                $sub[] = trim($res->phone)?$res->phone:'NA';
                $header[4] = 'Customer Mobile';
            }
            
            if($showall || in_array(5,$acolarr)){
                $sub[] = $res->emailid??'NA';
                $header[5] = 'Customer Email ID';
            }
			
			if($showall || in_array(6,$acolarr)){
                $sub[] = trim($res->comment_msg)?$res->comment_msg:'NA';
                $header[6] = 'Call Type';
            }

            if($showall || in_array(7,$acolarr)){
                $sub[] = trim($res->remark)?$res->remark:'NA';
                $header[7] = 'Duration';
            }
			
			if($showall || in_array(8,$acolarr)){
                $sub[] = trim($res->lead_stage_name)?$res->lead_stage_name:'NA';
                $header[8] = 'Purpose';
            }
			
			if($showall || in_array(9,$acolarr)){
                $sub[] = trim($res->description)?$res->description:'NA';
                $header[9] = 'Description';
            }
			
			if($showall || in_array(10,$acolarr)){
                $sub[] = trim($res->lead_discription_reamrk)?$res->lead_discription_reamrk:'NA';
                $header[10] = 'Remarks';
            }

            if($showall || in_array(11,$acolarr)){
                $sub[] = $res->create_name??'NA';
                $header[11] = 'create By';
            }
			
			if($showall || in_array(12,$acolarr)){
				if(stripos($res->tag_date,date('Y-m-d')) !== FALSE){
				    $tag =	'<a class="tag">NEW DATA</a>';
				}else{
					$tag = '<a class="tag">OLD DATA</a>';
				}
				$create_dt = $res->created_date.' '.$tag;
                $sub[] = $create_dt??'NA';
                $header[12] = 'create At';
            }

            if($showall || in_array(13,$acolarr))
            {
                $html = '';
                $html.='<td style="width:50px;">
                                            <div class="btn-group">';
                  if(user_access('log2'))
                  {
                    $html.='<button class="btn btn-danger btn-xs"  data-cc-id="'.$res->comm_id.'" onclick="deletelog(this)">
                      <i class="fa fa-trash"></i>
                    </button>';
                  }
                $sub[]=$html;
                $header[13] = 'Action';
            }
            $data[] =$sub;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$this->contacts_datatable_model->countAll_log(),
            "recordsFiltered" => $this->contacts_datatable_model->countFiltered_log($_POST),
            "data" => $data,
            "headings"=>$header,
        );
        echo json_encode($output);
    }

 
    public function company_load_data()
    {   
        $this->load->model('company_datatable_model');
        $result = $this->company_datatable_model->getRows($_POST);
   
        $colsall  = true;
        $cols = array();
        // if(!empty($_POST['allow_cols']))
        // {
        //     $cols  = explode(',',$_POST['allow_cols']);
        //     $colsall = false;
        // }
        //print_r($cols); exit();
        $data = array();
        $i=1;
        foreach ($result as $res)
        {
            $sub = array();

            $sub[] = $i++;
           
            if($colsall || in_array(2,$cols))
            {

                $sub[] = '<a href="'.(base_url('client/company_details/'.$res->id)).'">'.$res->company_name.'</a>'??'NA';
            }
			
			if($colsall || in_array(2,$cols))//created by
            {
                //$empname = $this->db->select('s_display_name,last_name')->from('enquiry')->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left')->where('enquiry.company',$res->id)->order_by('enquiry.enquiry_id','ASC')->get()->row();
                $sub[] = $res->s_display_name.' '.$res->last_name??'NA';
            }
			
			if($colsall || in_array(2,$cols))//region
            {
                //$empregion = $this->db->select('sales_region.name')->from('enquiry')->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left')->join('sales_region','sales_region.region_id=tbl_admin.sales_region','left')->where('enquiry.company',$res->id)->order_by('enquiry.enquiry_id','ASC')->get()->row();
                $sub[] = $res->name??'NA';
            }
			
			if($colsall || in_array(2,$cols))//department
            {
                //$empdepartment = $this->db->select('tbl_department.dept_name')->from('enquiry')->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left')->join('tbl_department','tbl_department.id=tbl_admin.dept_name','left')->where('enquiry.company',$res->id)->order_by('enquiry.enquiry_id','ASC')->get()->row();
                $sub[] = $res->dept_name??'NA';
            }
			
			if($colsall || in_array(2,$cols))//created by
            {
                $sub[] = $res->created_at??'NA';
            }

            if($colsall || in_array(2,$cols))//contacts
            {
                $contacts = $this->db->where('client_id IN ('.$res->enq_ids.')')->count_all_results('tbl_client_contacts');
                $sub[] = $contacts??'NA';
            }

            if($colsall || in_array(2,$cols))//deals
            {
                $deals = $this->db->where('enquiry_id IN ('.$res->enq_ids.')')->count_all_results('commercial_info');
                $sub[] = $deals??'NA';
            }

            if($colsall || in_array(2,$cols))//contacts
            {
                $visits = $this->db->where('enquiry_id IN ('.$res->enq_ids.')')->count_all_results('tbl_visit');
                $sub[] = $visits??'NA';
            }

            if($colsall || in_array(2,$cols))//contacts
            {
                $tickets = $this->db->where('client IN ('.$res->enq_ids.')')->count_all_results('tbl_ticket');
                $sub[] = $tickets??'NA';
            }

            if($colsall || in_array(2,$cols))//contacts
            {
                $accounts = count(explode(',', $res->enq_ids));
                $sub[] = $accounts??'NA';
            }

            $data[] =$sub;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$this->company_datatable_model->countAll(),
            "recordsFiltered" => $this->company_datatable_model->countFiltered($_POST),
            "data" => $data,
        );
        echo json_encode($output);
    }
	
	 public function userwise_company_load_data()
    {   
        $this->load->model('company_datatable_model');
        $result = $this->company_datatable_model->userwise_getRows($_POST);
 //echo '<pre>'; print_r($result);exit; 
        $colsall  = true;
        $cols = array();
        $data = array();
        $i=1;
        foreach ($result as $res)
        {
            $sub = array();

            $sub[] = $i++;
           
            if($colsall || in_array(2,$cols))
            {

                $sub[] = '<a href="'.(base_url('client/company_details/'.$res->id)).'">'.$res->company_name.'</a>'??'NA';
            }

            if($colsall || in_array(2,$cols))//contacts
            {
                $create_by = $res->s_display_name.' '.$res->last_name;
                $sub[] = $create_by??'NA';
            }

            if($colsall || in_array(2,$cols))//deals
            {
                $user_region = $res->region;
                $sub[] = $user_region??'NA';
            }
			
			if($colsall || in_array(2,$cols))//deals
            {
                $client_name = $res->client_name;
                $sub[] = $client_name??'NA';
            }
			
			if($colsall || in_array(2,$cols))//deals
            {
                $lead_source = $res->lead_name;
                $sub[] = $lead_source??'NA';
            }

            if($colsall || in_array(2,$cols))//contacts
            {
                $create_at = $res->created_at;
                $sub[] = $create_at??'NA';
            }

            $data[] =$sub;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$this->company_datatable_model->userwise_countAll(),
            "recordsFiltered" => $this->company_datatable_model->userwise_countFiltered($_POST),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function commercial_info($enquiry_id,$by=0)
    {
        $keyword = $this->uri->segment(4);
        $this->load->model(array('Client_Model','Leads_Model','Branch_model'));

        $data['title'] = 'Add Deal';
        $en_id = $this->db->select("enquiry_id")->from("enquiry")->where('Enquery_id', $enquiry_id)->get()->row();
        if($keyword!='by_deals'){
            $data_type = base64_decode($keyword);
            $lead_id = $enquiry_id;
            $by = 0;
        }else{
            $lead_id = $en_id->enquiry_id;
            $data_type = '';
            $by = $keyword;
        }
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($lead_id);
        $data['branch'] = $this->Branch_model->branch_list()->result();
        $data['region_list'] = $this->Branch_model->sales_region_list()->result();
        $dis= $this->db->select('d.discount')
                                        ->from('discount_matrix d')
                                        ->join('tbl_admin a','a.discount_id=d.id','left')
                                        ->where('a.pk_i_admin_id='.$this->session->user_id)
                                        ->get()->row();
        $data['max_discount'] = !empty($dis)?$dis->discount:100;
        $data['by'] = $by;
        $data['data_type'] = $data_type;
		//print_r($data['data_type']);exit;
        $data['content'] = $this->load->view('enquiry/add_deals', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function gen_table()
    {
        if(!empty($_POST))
        {  
            $this->load->model('Branch_model');
            $deal_type = $this->input->post('deal_type');

            $deal_type = is_array($deal_type)?$deal_type:array();

            $booking_type = $this->input->post('booking_type');
            $business_type = $this->input->post('business_type');
			$insurance = $this->input->post('insurance');
            $chain = $this->input->post('chain');
            $btype = $this->input->post('btype');
            $dtype = $this->input->post('dtype');
            $enquiry_id = $this->input->post('enq_for');
			$unique_no = $this->input->post('unique_no');
            $deal_id= $this->input->post('deal_id')??0;
            
            $stage_for = $this->input->post('stage_for');
            //print_r($deal_id);exit;
            $deal_data = $this->Branch_model->get_deal($deal_id);
           
            // if($btype=='zone')
            // {
            //     $x = implode(',',$bbranch);
            //     //echo $x;exit();
            //     $fetch_list  = $this->Branch_model->common_list(" branch.zone IN ($x) and branch.type='area' ")->result();

            //     $bbranch = array_column($fetch_list,'branch_id');
            //     //print_r($bbranch);exit();
            // }

            // if($dtype=='zone')
            // {
            //     $x = implode(',',$dbranch);
            //     //echo $x;exit();
            //     $fetch_list  = $this->Branch_model->common_list(" branch.zone IN ($x) and branch.type='area' ")->result();
            //    //echo $this->db->last_query();exit();;

            //     $dbranch = array_column($fetch_list,'branch_id');
            //     //print_r($bbranch);exit();
            // }

            // if(empty($bbranch))
            //     $bbranch = array(0);
            // if(empty($dbranch))
            //     $dbranch = array(0);  
        
            $main_array = array();
            if(!empty($chain))
            {   $i=1;
                foreach ($chain as $key => $r)
                {
                    if(empty($r['key']) || empty($r['val']))
                    {
                        echo'<div class="alert alert-danger">Booking Branch or Delivery Branch is Empty</div>';
                        continue;
                    }

                    if(!empty($r['val']))
                    {   
                        foreach ($r['val'] as $key2 => $value) 
                        {   //echo $btype;exit();
                            if($btype=='area' || $booking_type=='ftl')
                            {
                            $from_branch =  $this->db->select('branch_name from')->where('branch_id',$r['key'])->get('branch')->row();

                            $to_branch =  $this->db->select('branch_name to')->where('branch_id',$value)->get('branch')->row();
                                
                                $main_array[] = (object)array(
                                                    'id'=>$i++,
                                                    'booking_branch'=>$r['key'],
                                                    'from'=>$from_branch->from,
                                                    'delivery_branch'=>$value,
                                                    'to'=>$to_branch->to,
                                                    'rate'=>'',
                                                    'discount'=>'',
                                );
                            }
                            else
                            {
                                $where = array(
                                'rate.booking_branch' => $r['key'],
                                'rate.delivery_branch' => $value,
                                );
                                $row = $this->Branch_model->rate_list($btype,$where)->row();
								//print_r($row);exit;
                                if(!empty($row))
                                    $main_array[] = $row; 
                            }
                            
                        }
                        
                    }
                    
                }
            }
            //print_r($main_array);exit();
            $query = $main_array;
           //echo $this->db->last_query();exit();
            if(!empty($query))
            {   
                $edit_remark = '';
                $oc = array('',//0
                            '100',//1
                            '100',//2
                            '1200',//3
                            '1 CFT =10',//4
                            '0.20 Paise',//5
                            '0.2',//6
                            'NA',//7
                            'NA',//8
                            'NA',//9
                            'NA',//10
                            '',//11
                            'Consignor',//12
                            '0.15 Paise',//13
                            'Consignor',//14
                            '',//15
                            'NA',//16
                            '15',//17
                            '25',//18
                            '',//19
                            '750',//20
                            '',//21
                            '',//22
                    );

                    // $oc[19] =10;//array(array('from'=>'','to'=>'','charge'=>'','unit'=>'per_kg'));
                    // $oc[20] =10; //array(array('from'=>'','to'=>'','charge'=>'','unit'=>'per_kg'));
                    $oc_data = $this->db->get('other_charges')->row_array();               

                    if(!empty($oc_data))
                    {   
                        $extract=  array_values($oc_data);
                        unset($extract[0]);
                        $oc = $extract;
                        $oc[22] = $oc[23] = $oc[25] = '';
                    }
					if(empty($deal_id)){
                        $extract=  array_values($oc_data);
                        unset($extract[0]);
                        $uc = $extract;
                        $uc[22] = $uc[23] = $uc[25] = ''; 							
				        }
                    $oc['rate_type'] = 'KG';
					$uc['rate_type'] = 'KG';
                    if(!empty($deal_data))
                    {
                          $oc =json_decode($deal_data->other_charges,true);
						  $uc =json_decode($deal_data->update_charges,true);
                        //$oc =(array)json_decode($deal_data->other_charges);
                        if(empty($oc[23]))
                            $oc[23]='';
                        $edit_remark = $deal_data->edit_remark;
						if(empty($uc[23]))
                            $uc[23]='';
                        $edit_remark = $deal_data->edit_remark;
                    }
                    
                    

                echo'
                <form id="data_table">
                <input name="deal_type" type="hidden" value="'.implode(',',$deal_type).'">
                <input name="booking_type" type="hidden" value="'.$booking_type.'">
				<input name="unique_no" type="hidden" value="'.$unique_no.'">
                <input name="business_type" type="hidden" value="'.$business_type.'">
				<input name="insurance" type="hidden" value="'.$insurance.'">
                <input name="btype" type="hidden" value="'.$btype.'">
                <input name="dtype" type="hidden" value="'.$dtype.'">
                <input name="edited" type="hidden" value="0">
                ';
               
                foreach ($chain as $ck => $cv)
                {
                    $ar = $cv['val'];
                    if(empty($ar))
                        $ar = array();
                    echo'<input type="hidden" name="chain['.$cv['key'].']" value="'.implode(',',$ar).'">';
                }

                echo'<input name="enquiry_id" type="hidden" value="'.$enquiry_id.'">
                <input name="info_id" type="hidden" value="'.$deal_id.'">
                <div id="data-box"  style="max-height: 800px; min-height:45px; overflow:auto; padding:0px 20px;">
                <div class="toggle-btn" data-view="show"><i class="fa fa-chevron-up"></i></div>
                <label>Booking Table:</label>
                <div class="t_box">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Booking From</th>
                        <th>Delivery To</th>';
                    if($booking_type=='sundry')
                    {
						echo'<th style="width:100px;"> Rate/<select name="oc[rate_type]" class="exclude_select2" style=" background:#d9edf7; border:0px;">
                                    <option '.($oc['rate_type']=='KG'?'selected':'').'>KG</option>
                                    <option '.($oc['rate_type']=='Box'?'selected':'').'>Box</option>
                                </select>
							</th>
                        <th style="width:100px">Discount <label class="badge pull-right" onclick="rep_discount()">R</label></th>
						<th style="width:75px">Final rate</th>';
                    }

                     if($booking_type=='ftl')
                     {
                     echo'<th>Vehicle Type <label class="badge pull-right" onclick="rep_vtype()">R</label></th>
                            <th>Carrying Capacity<label class="badge pull-right" onclick="rep_capacity()">R</label></th>
                           ';
                    }

                    $vehicles = $this->Branch_model->get_vehicles()->result();

                    echo'
                        <!--<th style="width:115px">Insurance <label class="badge pull-right" onclick="rep_insurance()">R</label></th>-->
                        <th style="width:130px">Paymode <label class="badge pull-right" onclick="rep_paymode()">R</label></th>
                        ';


                    if($booking_type=='sundry')
                        echo'<th>Potential Tonnage <label class="badge pull-right" onclick="rep_pton()">R</label></th>';
                     echo'   <th>Potential Amount<br>(<small>In Lakhs</small>)</th>';
                

                 if($booking_type=='sundry')
                    echo'<th>Expected Tonnage <label class="badge pull-right" onclick="rep_eton()">R</label></th>';

                        echo'<th>Expected Amount<br>(<small>In Lakhs</small>)</th>';
                 

                if($booking_type=='ftl')
                    {   
                        echo' <th>Invoice Value <label class="badge pull-right" onclick="rep_invoice()">R</label></th>';
                    }
                    echo'</thead>
                    <tbody>
                ';
                
                $i=1;

//For Booking Table Diffrence Count Start
if(!empty($deal_data->copy_id)){
$old_chk = $this->db->where('deal_id',$deal_data->copy_id)
       ->order_by('id','ASC')
       ->get('deal_data')->result();
}
//For Booking Table Diffrence Count End

                foreach ($query as $key => $row)
                {
                    $rate=$row->rate;
                    $discount=0;
					$final_rate=0;
                    $paymode='';
                    $insurance='';
                    $eton=0;
                    $eamnt=0;
                    $pton=0;
                    $pamnt=0;
                    $vid=0;
                    $invoice=0;
					$capacity=0;
                    $chk = $this->db->where('deal_id',$deal_id)
                                        ->where('booking_branch',$row->booking_branch)
                                        ->where('delivery_branch',$row->delivery_branch)
                                        ->get('deal_data')->row();
                    if(!empty($chk))
                    {
                        $rate= $chk->rate;
                        $discount=$chk->discount;
						$final_rate= $chk->final_rate;
                        $paymode=$chk->paymode;
                        $insurance=$chk->insurance;
                        $eton=$chk->expected_tonnage;
                        $eamnt=$chk->expected_amount;
                        $pton=$chk->potential_tonnage;
                        $pamnt=$chk->potential_amount;
                        $vid=$chk->vehicle_type;
                        $capacity = $chk->carrying_capacity;
                        $invoice = $chk->invoice_value;   
                    }
					
					if(!empty($old_chk))
                    {
						$old_b_branch= $old_chk[$key]->booking_branch??$row->booking_branch;
						$old_d_branch= $old_chk[$key]->delivery_branch??$row->delivery_branch;
                        $old_rate= $old_chk[$key]->rate??$row->rate; 
                        $old_discount=$old_chk[$key]->discount??$discount;
						$old_final_rate= $old_chk[$key]->final_rate??$final_rate;
                        $old_paymode=$old_chk[$key]->paymode??$paymode;
                        $old_insurance=$old_chk[$key]->insurance??$insurance;
                        $old_eton=$old_chk[$key]->expected_tonnage??$eton;
                        $old_eamnt=$old_chk[$key]->expected_amount??$eamnt;
                        $old_pton=$old_chk[$key]->potential_tonnage??$pton;
                        $old_pamnt=$old_chk[$key]->potential_amount??$pamnt;
                        $old_vid=$old_chk[$key]->vehicle_type??$vid;
                        $old_capacity = $old_chk[$key]->carrying_capacity??$capacity;
                        $old_invoice = $old_chk[$key]->invoice_value??$invoice;   
                    }else{
						$old_b_branch= $row->booking_branch;
						$old_d_branch= $row->delivery_branch;
                        $old_rate= $row->rate; 
                        $old_discount=$discount;
						$old_final_rate= $final_rate;
                        $old_paymode=$paymode;
                        $old_insurance=$insurance;
                        $old_eton=$eton;
                        $old_eamnt=$eamnt;
                        $old_pton=$pton;
                        $old_pamnt=$pamnt;
                        $old_vid=$vid;
                        $old_capacity = $capacity;
                        $old_invoice = $invoice;
					}
                    //'.($row->btype=='area'?'<br>'.$row->bzname:'').'

                    echo'<tr>
                            <td>'.$i++.'</td>
                            <td '.(($row->booking_branch!=$old_b_branch)?"style='background:#ffbaba;'":"").'><input type="hidden" name="bid['.$row->id.']" value="'.$row->booking_branch.'">'.$row->from.'</td>
                            <td '.(($row->delivery_branch!=$old_d_branch)?"style='background:#ffbaba;'":"").'><input type="hidden" name="did['.$row->id.']" value="'.$row->delivery_branch.'">'.$row->to.'</td>';
                    if($booking_type=='sundry')
                    {
                        echo'<td '.(($row->rate!=$old_rate)?"style='background:#ffbaba;'":"").'><input type="text" id="rate_'.$row->id.'" name="rate['.$row->id.']" data-id="'.$row->id.'" value="'.$row->rate.'"></td>
                        <td class="disc-box" '.(($discount!=$old_discount)?"style='background:#ffbaba;'":"").'><input type="text" id="discount_'.$row->id.'" class="discount_ip" name="discount['.$row->id.']" data-id="'.$row->id.'" value="'.$discount.'" onchange="final_rate_calculate('.$row->id.');"></td>
						<td '.(($old_final_rate!=$final_rate)?"style='background:#ffbaba;'":"").'><input type="text" id="final_rate_'.$row->id.'" class="final_rate" name="final_rate['.$row->id.']" data-id="'.$row->id.'" value="'.$final_rate.'" onchange="final_discount_calculate('.$row->id.');"></td>';
                    }

                    if($booking_type=='ftl')
                    {
                            echo'<td '.(($vid!=$old_vid)?"style='background:#ffbaba;'":"").'>
                            <select name="vtype['.$row->id.']" class="vtype_ip">';
                            foreach($vehicles as $ve => $vehicle)
                                echo'<option value="'.$vehicle->vehicle_type_id.'" '.($vid==$vehicle->vehicle_type_id?'selected':'').'>'.$vehicle->type_name.'</option>';
                            echo'
                            </select></td>
                            <td '.(($capacity!=$old_capacity)?"style='background:#ffbaba;'":"").'><input name="capacity['.$row->id.']" type="number" class="capacity_ip" value="'.$capacity.'"></td>';
                    }
                        echo'
                             <!--<td>
                             <select name="insurance['.$row->id.']" data-id="'.$row->id.'"  class="insurance_ip">
                                <option value="carrier" '.($insurance=='carrier'?'selected':'').'>Carrier</option>
                                <option value="owner" '.($insurance=='owner'?'selected':'').'>Owner risk</option>
                                </select>
                            </td>-->
                            <td '.(($paymode!=$old_paymode)?"style='background:#ffbaba;'":"").'><select name="paymode['.$row->id.']" data-id="'.$row->id.'"  class="paymode_ip">
                                <option value="paid" '.($paymode=='paid'?'selected':'').'>Paid</option>
                                <option value="topay" '.($paymode=='topay'?'selected':'').'>To-Pay</option>
                                <option value="tbb" '.($paymode=='tbb'?'selected':'').'>TBB</option>
                                <option value="tbb_topay" '.($paymode=='tbb_topay'?'selected':'').'>TBB + To-pay</option>
                                <option value="paid_topay" '.($paymode=='paid_topay'?'selected':'').'>Paid + To-pay</option>
                                <option value="inward" '.($paymode=='inward'?'selected':'').'>Inward</option>
                                </select>
                            </td>';

                 if($booking_type=='sundry')
                    echo' <td '.(($pton!=$old_pton)?"style='background:#ffbaba;'":"").'><input type="number" name="pton['.$row->id.']" data-id="'.$row->id.'" value="'.$pton.'" class="pton_ip"></td>';

                    echo'<td '.(($pamnt!=$old_pamnt)?"style='background:#ffbaba;'":"").'><input type="text" name="pamnt['.$row->id.']" data-id="'.$row->id.'" value="'.$pamnt.'"  '.($booking_type=='sundry'?'readonly':'').'></td>';


                if($booking_type=='sundry')
                    echo'<td '.(($eton!=$old_eton)?"style='background:#ffbaba;'":"").'>
                        <input type="number" name="eton['.$row->id.']" data-id="'.$row->id.'" value="'.$eton.'" class="eton_ip"></td>';

                    echo'<td '.(($eamnt!=$old_eamnt)?"style='background:#ffbaba;'":"").'><input type="text" name="eamnt['.$row->id.']" data-id="'.$row->id.'" value="'.$eamnt.'" '.($booking_type=='sundry'?'readonly':'').'></td>';

                
                if($booking_type=='ftl')
                    echo'<td '.(($invoice!=$old_invoice)?"style='background:#ffbaba;'":"").'><input name="invoice['.$row->id.']" type="number" class="invoice_ip" value="'.$invoice.'"></td>
                        
                        ';

                    echo'</tr>';
                }
                echo'</tbody>
                </table>
                </div>
                </div>
                <div id="oc-box" style="padding:0px 10px;">
                 <div class="toggle-btn"  data-view="show"><i class="fa fa-chevron-up"></i></div>
                 <label>Other Charges: &nbsp; </label>';
                 if(!empty($deal_id))
                 { 
                /* echo'<span id="edit_charge" style="float:right;">
                    <i class="fa fa-edit"></i> Edit
                 </span>'; */
				 echo'<script>
                    $(".disc-box").find("input:not(.exip)").attr("readonly","readonly");
                    </script>';

                 }				 

                
                echo' <div class="t_box">
                    <table class="table table-bordered table-dark">
                    <thead>
                        <tr>
                            <th align="center">Name of Charges</th>
                            <th align="center">Initial Amount (Rs.)</th>';
  if(!empty($deal_id)){echo'<th align="center">Offerd Amount (Rs.)</th>';}
                       echo'<th align="center">Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr '.(($oc[1]!=$uc[1])?"style='background:#ffbaba;'":"").'>
                            <td>GC Charges</td>
                            <td><input name="oc[1]" value="'.$oc[1].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[1]" value="'.$uc[1].'"></td>';}			
                       echo'<td>In Rs. Per GC</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr '.(($oc[2]!=$uc[2])?"style='background:#ffbaba;'":"").'>
                            <td>Minimum Chargeable Wt</td>
                            <td><input name="oc[2]" value="'.$oc[2].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[2]" value="'.$uc[2].'"></td>';}
                       echo'<td>KGs, Whichever is Higher</td>
                        </tr>
                        <tr '.(($oc[3]!=$uc[3])?"style='background:#ffbaba;'":"").'>
                            <td>Minimum Freight Value</td>
                            <td><input name="oc[3]" value="'.$oc[3].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[3]" value="'.$uc[3].'"></td>';}
                        echo'<td>In Rs.</td>
                        </tr>
                        <tr '.(($oc[4]!=$uc[4])?"style='background:#ffbaba;'":"").'>
                            <td>CFT factor</td>
                            <td><input name="oc[4]" value="'.$oc[4].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[4]" value="'.$uc[4].'"></td>';}
                       echo'<td>KG.</td>
                        </tr>
                        <tr '.(($oc[5]!=$uc[5])?"style='background:#ffbaba;'":"").'>
                            <td>Hamali Charges</td>
                            <td><input name="oc[5]" value="'.$oc[5].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[5]" value="'.$uc[5].'"></td>';}
                       echo'<td>Per Kg.</td>
                        </tr>';
                    }
                    echo'<tr '.(($oc[6]!=$uc[6])?"style='background:#ffbaba;'":"").'>
                            <td>FOV Charges (owner risk)</td>
                            <td><input name="oc[6]" value="'.$oc[6].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[6]" value="'.$uc[6].'"></td>';}
                       echo'<td>% of Invoice Value</td>
                        </tr>
                        <tr '.(($oc[7]!=$uc[7])?"style='background:#ffbaba;'":"").'>
                            <td>FOV Charges (Carrier risk)</td>
                            <td><input name="oc[7]" value="'.$oc[7].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[7]" value="'.$uc[7].'"></td>';}
                       echo'<td>% of Invoice Value</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr '.(($oc[8]!=$uc[8])?"style='background:#ffbaba;'":"").'>
                            <td>AOC Charges</td>
                            <td><input name="oc[8]" value="'.$oc[8].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[8]" value="'.$uc[8].'"></td>';}
                       echo'<td>% of Total Freight</td>
                        </tr>
                        <tr '.(($oc[9]!=$uc[9])?"style='background:#ffbaba;'":"").'>
                            <td>COD/DOD Charges</td>
                            <td><input name="oc[9]" value="'.$oc[9].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[9]" value="'.$uc[9].'"></td>';}
                       echo'<td>Per GC</td>
                        </tr>
                        <tr '.(($oc[10]!=$uc[10])?"style='background:#ffbaba;'":"").'>
                            <td>DACC Charges</td>
                            <td><input name="oc[10]" value="'.$oc[10].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[10]" value="'.$uc[10].'"></td>';}
                       echo'<td>Per GC</td>
                        </tr>';
                    }
                    echo'<tr '.(($oc[11]!=$uc[11])?"style='background:#ffbaba;'":"").'>
                            <td>Other (Please Specify)</td>
                            <td><input name="oc[11]" value="'.$oc[11].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[11]" value="'.$uc[11].'"></td>';}
                       echo'<td>At Actual</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr>
                            <td colspan="2" style="font-weight:bold">CR Charges to be Paid By Consignor <input type="radio" name="oc[12]" value="Consignor" '.($oc[12]=='Consignor'?'checked':'').'>   Consignee  <input type="radio" name="oc[12]" value="Consignee" '.($oc[12]=='Consignee'?'checked':'').'> </td>';
  if(!empty($deal_id)){echo'<td colspan="2" style="font-weight:bold">CR Charges to be Paid By Consignor <input type="radio" name="uc[12]" value="Consignor" '.($uc[12]=='Consignor'?'checked':'').'>   Consignee  <input type="radio" name="uc[12]" value="Consignee" '.($uc[12]=='Consignee'?'checked':'').'> </td>';}
                    echo'</tr>
                        <tr '.(($oc[13]!=$uc[13])?"style='background:#ffbaba;'":"").'>
                            <td>Demurrage charges</td>
                            <td><input name="oc[13]" value="'.$oc[13].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[13]" value="'.$uc[13].'"></td>';}
                       echo'<td>Per KG on Per day basis</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight:bold">Demurrage Charges to be Paid By Consignor <input type="radio" name="oc[14]" value="Consignor" '.($oc[14]=='Consignor'?'checked':'').'>   Consignee  <input type="radio" name="oc[14]" value="Consignee" '.($oc[14]=='Consignee'?'checked':'').'> </td>';
  if(!empty($deal_id)){echo'<td colspan="2" style="font-weight:bold">Demurrage Charges to be Paid By Consignor <input type="radio" name="uc[14]" value="Consignor" '.($uc[14]=='Consignor'?'checked':'').'>   Consignee  <input type="radio" name="uc[14]" value="Consignee" '.($uc[14]=='Consignee'?'checked':'').'> </td>';}
                    echo'</tr>';
                    }
                    echo'<tr '.(($oc[15]!=$uc[15])?"style='background:#ffbaba;'":"").'>
                            <td>Loading/Unloading Charges/Union Charges</td>
                            <td><input name="oc[15]" value="'.$oc[15].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[15]" value="'.$uc[15].'"></td>';}
                       echo'<td>Per Kg / Box</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr '.(($oc[16]!=$uc[16])?"style='background:#ffbaba;'":"").'>
                            <td>GI Charges</td>
                            <td><input name="oc[16]" value="'.$oc[16].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[16]" value="'.$uc[16].'"></td>';}
                       echo'<td>In Rs. per GC</td>
                        </tr>
                        <tr '.(($oc[17]!=$uc[17])?"style='background:#ffbaba;'":"").'>
                            <td>Dynamic Fuel Surcharge in %</td>
                            <td><input name="oc[17]" value="'.$oc[17].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[17]" value="'.$uc[17].'"></td>';}
                       echo'<td>% of basic freight</td>
                        </tr>
                        <tr '.(($oc[18]!=$uc[18])?"style='background:#ffbaba;'":"").'>
                            <td>Levy- in %</td>
                            <td><input name="oc[18]" value="'.$oc[18].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[18]" value="'.$uc[18].'"></td>';}
                       echo'<td>% of basic freight</td>
                        </tr>';
                    }
                    echo'<tr '.(($oc[19]!=$uc[19])?"style='background:#ffbaba;'":"").'>
                            <td>E-way bill charge</td>
                            <td><input name="oc[19]" value="'.$oc[19].'" class="no_edit"></td>';
  if(!empty($deal_id)){echo'<td><input name="uc[19]" value="'.$uc[19].'"></td>';}
                       echo'<td>In Rs. Per GC</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {

                    echo'<tr '.(($oc[20]!=$uc[20])?"style='background:#ffbaba;'":"").'>
                            <td>Door Collection Charges</td>
                            <td id="door_box">
                                <input name="oc[20]" value="'.$oc[20].'" class="no_edit">
                            </td>';
  if(!empty($deal_id)){echo'<td><input name="uc[20]" value="'.$uc[20].'"></td>';}
                       echo'<td>Upto 3 MT and above free</td>
                        </tr>
                        <tr '.(($oc[21]!=$uc[21])?"style='background:#ffbaba;'":"").'>
                            <td>Last Mile  Delivery charges</td>
                            <td id="mile_box">
                             <input name="oc[21]" value="'.$oc[21].'" class="no_edit">
                            </td>';
  if(!empty($deal_id)){echo'<td><input name="uc[21]" value="'.$uc[21].'"></td>';}
                       echo'<td>Upto 3 MT and above free</td>
                        </tr>
                        <tr>
                            <td>Re Delivery charges</td>
                            <td colspan="3">Rs. 1200 per GC or actual expense whichever is higher</td>
                        </tr>
                        <tr '.(($oc[22]!=$uc[22])?"style='background:#ffbaba;'":"").'>
                            <td>ODA Charges</td>
                            <td colspan="2">
                                <div style="width:49%; display:inline-block;">
                                    <input id="oda_value" name="oc[22]" value="'.$oc[22].'" placeholder="Charge" class="no_edit">
                                </div>
                                <div style="width:49%; display:inline-block;">
                                    <input id="oda_distance" name="oc[23]" value="'.$oc[23].'" type="number" style="width:40%" placeholder="Distance ( In KM )" onkeyup="oda_cal()" class="exip no_edit">
                                    <input id="oda_weight" name="oc[24]" value="'.$oc[24].'" type="number" style="width:40%" placeholder="Weight ( In KG )" onkeyup="oda_cal()" class="exip no_edit">
									&nbsp;<span onclick="rate_alert()" class="btn btn-primary btn-sm"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                </div>
                            </td>';
	if(!empty($deal_id)){
					   echo'<td colspan="2">
                                <div style="width:49%; display:inline-block;">
                                    <input id="uoda_value" name="uc[22]" value="'.$uc[22].'" placeholder="Charge" >
                                </div>
                                <div style="width:49%; display:inline-block;">
                                    <input id="uoda_distance" name="uc[23]" value="'.$uc[23].'" type="number" style="width:40%" placeholder="Distance ( In KM )" onkeyup="uoda_cal()" class="exip">
                                    <input id="uoda_weight" name="uc[24]" value="'.$uc[24].'" type="number" style="width:40%" placeholder="Weight ( In KG )" onkeyup="uoda_cal()" class="exip">
									&nbsp;<span onclick="rate_alert()" class="btn btn-primary btn-sm"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                </div>
                            </td>';
	}
                        echo'</tr>';
                    }
                echo'</tbody>
                    </table>';
                if($booking_type=='sundry')
                {
					echo'<p '.(($oc[25]!=$uc[25])?"style='background:#ffbaba;'":"").'>The average fuel price at the time of signing the contract is Rs <input type="number" name="oc[25]" value="'.$oc[25].'" class="exip no_edit" style="width: 100px !important;">. per Ltr.';
        
		if(!empty($deal_id)){
                echo'&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;&nbsp;<input type="number" name="uc[25]" value="'.$uc[25].'" class="exip" style="width: 100px !important;">. per Ltr.';
                    
		}
		echo'</p>';
                }
                $d_edited = 0;
                if(!empty($deal_data->edited) && $deal_data->edited=='1')
                    $d_edited= 1;
            echo'</div>

                <div class="edit_remark col-md-12" style="'.($d_edited==1?'':'display:none;').'" >
                    <div class="form-group">
                        <label>Edit Remark</label>
                        <textarea name="edit_remark" class="form-control">'.$edit_remark.'</textarea>
                    </div>
                </div>';
//$deal_status= $this->db->select('edited,approval,id,createdby,status')->where('id', $deal_id)->get('commercial_info')->row();
 $deal_status = '0';
            if($deal_status!='0'){
                if($deal_status->edited=='1' && $deal_status->approval=='pending')
                {
                    if($deal_status->createdby==$this->session->user_id)
                    {
                       echo '<label class="label label-primary">Waiting for approval</label>';
                    }
                    else
                    {
                       echo '<div class="col-md-6"><label>Select Status Here</label>
                              <select name="edit_status">';
                         echo   '<option value="">Action</option>
                                    <option value="approve">Approve</option>
                                    <option value="reject">Reject</option>
                                    <option value="resend">Send for Approval</option>
                                ';
                        echo'</select></div>';
                    }
                }               
            }   
                echo'<input name="current_stage" value="'.$stage_for.'" type="hidden">';
                echo'</div>
                <div style="padding:15px;">
                    <button class="btn btn-success pull-right" type="submit"><i class="fa fa-save"></i> Save</button>';
                echo'</div>
                </form>
                ';
                //if(empty($deal_id))
                    echo'<script>
                        $("#oc-box").find("input:not(.exip)").attr("readonly","readonly");
                        </script>';
            }
            else
            {
                echo'<center><h2 style="color:#696969">No Data Found</h2></center>';
            }
        }
        else
        {
            echo'blank';
        }
    }
    public function save_deal_data()
    {
        $this->load->model(array('Branch_model','Enquiry_model','Leads_Model'));
        $current_user = $this->User_model->read_by_id($this->session->user_id);
        //print_r($this->input->post('current_stage'));exit;
        $deal_id = $this->input->post('info_id');
        $enq_id = $this->input->post('enquiry_id');
        $enq =  $this->Enquiry_model->getEnquiry(array('enquiry_id'=>$enq_id))->row();
		$unique = $this->input->post('unique_no');
		$edit = $this->input->post('edited');
        if($edit==1 && $this->input->post('booking_type')=='sundry')
        {		
		$urate_type = array();$zero = array();
		$urate_type['rate_type'] = $this->input->post('oc[rate_type]');
		$u_post = $this->input->post('uc');
		$zero[] = 'NA';
		$unset = array_merge($zero,$u_post);
		$final = array_merge($urate_type,$unset);
		unset($final[0]);
		$uc = json_encode($final);
		}else{
		$uc = json_encode($this->input->post('uc'));
		}
        $oc = json_encode($this->input->post('oc'));
//For Region		
$r_string = $enq->rnm;		
$r_lastChar = $r_string[-1];
if(is_numeric($r_lastChar)){
	$r_add = $r_lastChar;
}else{
    $r_add = '';
}
//For Area
$a_string = $enq->area_name;		
$a_lastChar = $a_string[-1];
if(is_numeric($a_lastChar)){
	$a_add = $a_lastChar;
}else{
    $a_add = '';
}
//For Branch
$b_string = $enq->branch_name;		
$b_lastChar = $b_string[-1];
if(is_numeric($b_lastChar)){
	$b_add = $b_lastChar;
}else{
    $b_add = '';
}
		
		if(empty($unique)){
		$branch = substr($enq->branch_name,0,2);
		$area   = substr($enq->area_name,0,2);
		$region = substr($enq->rnm,0,2);
		$number = str_pad(rand(0,999), 3, "0", STR_PAD_LEFT);
		$type   = strtoupper($this->input->post('booking_type'));
		$unique_no = strtoupper($region).''.$r_add.''.strtoupper($area).''.$a_add.''.strtoupper($branch).''.$b_add.''.$number.''.$type.'-1.0';
		$uc = $oc;
		}else{
		if($edit==1)
            {
			$next = explode('-',$unique);
			$nexts = $next[1]+1;
			$next_version = $nexts.'.0';
        $unique_no = $next[0].'-'.$next_version;
		$uc = $uc;
        }else{
			$unique_no = $unique;
            $uc = $oc;			
			}		
		}
			
		//print_r($unique_no);exit;
        $deal = array(
                    'enquiry_id'=>$this->input->post('enquiry_id'),
					'quatation_number'=>$unique_no,
                    'deal_type'=>$this->input->post('deal_type'),
                    'booking_type'=>$this->input->post('booking_type'),
                    'business_type'=>$this->input->post('business_type'),
					'insurance'=>$this->input->post('insurance'),
                    'btype'=>$this->input->post('btype'),
                    'dtype'=>$this->input->post('dtype'),                    
                    'comp_id'=>$this->session->companey_id,
                    'other_charges'=>$oc,
					'update_charges'=>$uc,
                    'updation_date'=>date('Y-m-d H:i:s'),
                    'stage_id'=>$this->input->post('current_stage'),
                    'status'=>'0',
                    );

        if(!empty($deal_id))
        {   
            $edit = $this->input->post('edited');
            $remark='';
            if($edit==1)
            {   
                $ddata = $this->Branch_model->get_deal($deal_id);
                $edit_remark = $this->input->post('edit_remark');
                $remark ='Approval Required<br>'.$edit_remark;
                $deal['edit_remark'] = $edit_remark;
                $deal['copy_id'] = $deal_id;
                $deal['edited']=1;
                $deal['approval']='';
                $deal['status']=0;
                $this->db->where('id',$deal_id);
                if(empty($ddata->copy_id))
                {
                    $this->db->update('commercial_info',array('original'=>0)); 
                    file_get_contents(base_url('dashboard/pdf_gen/'.$deal_id));
                }
                else
                {
                    //$deal['copy_id'] = $ddata->copy_id;
                    //$deal['id'] = $deal_id;                    
                    //$this->db->delete('commercial_info');
                }
                //$deal['stage_id']=$this->input->post('current_stage');
                $deal['createdby']=$ddata->createdby;
                $deal_id = $this->Branch_model->add_deal($deal);

                $this->db->where('deal_id',$deal_id);
                $this->db->where('request_from_uid',$this->session->user_id);                
                if($this->db->get('deal_approval_history')->num_rows()){
                    $this->db->where('deal_id',$deal_id);
                    $this->db->where('request_from_uid',$this->session->user_id);                
                    $this->db->set('status','');
                    $this->db->update('deal_approval_history');
                }else{
                    $this->db->insert('deal_approval_history',
                    array(
                        'comp_id' => $this->session->companey_id,
                        'deal_id' => $deal_id,
                        'request_from_uid' => $this->session->user_id,
                        'request_to_uid' => $current_user->report_to,
                        'status' => ''
                        )
                    );
                }
                 $msg = $unique_no.' Deal Updated';
                $this->Leads_Model->add_comment_for_events_stage($msg,$enq->Enquery_id,0,0,$remark,0);
            }
            else
            {
                $this->db->where('id',$deal_id);
                $this->db->update('commercial_info',$deal);
                file_get_contents(base_url('dashboard/pdf_gen/'.$deal_id));
                $this->db->where('deal_id',$deal_id)->delete('deal_data');
				$msg = $unique_no.' Deal Updated';
                $this->Leads_Model->add_comment_for_events_stage($msg,$enq->Enquery_id,0,0,$remark,0);
            }

        }
        else
        {
            //$deal['stage_id']=$this->input->post('current_stage');
            $deal['createdby']=$this->session->user_id;
            $deal_id = $this->Branch_model->add_deal($deal);
            //file_get_contents(base_url('dashboard/pdf_gen/'.$deal_id));
			//$this->db->set('status','3');
			//$this->db->where('enquiry_id',$this->input->post('enquiry_id'));
            //$this->db->update('enquiry');
            //$this->Leads_Model->add_comment_for_events_stage('Deal Moved To Negotiation Successfully.',$enq->Enquery_id,0,0,'',0);
			$msg = $unique_no.' Deal Added';
            $this->Leads_Model->add_comment_for_events_stage($msg,$enq->Enquery_id,0,0,'',0);
        }
        
        if($deal['booking_type']=='sundry')
            $hook = $this->input->post('rate');
        else
            $hook = $this->input->post('vtype');  

        $potential_tonnage = $potential_amount = $expected_tonnage = $expected_amount = 0;
        foreach($hook as $link_id => $rate)
        {
			$booking_branch = $this->input->post('bid['.$link_id.']');
			
	    $this->db->select('area_id,region_id');
        $this->db->from('branch');
        $this->db->where('branch_id',$booking_branch);
        $bbrh = $this->db->get()->row();
		
			$delivery_branch = $this->input->post('did['.$link_id.']');
		
		$this->db->select('area_id,region_id');
        $this->db->from('branch');
        $this->db->where('branch_id',$delivery_branch);
        $dbrh = $this->db->get()->row();
			
            $data  = array(
                        'deal_id'=>$deal_id,
                        'booking_branch'=>$booking_branch,
						'booking_region'=>$bbrh->region_id??'',
                        'booking_area'=>$bbrh->area_id??'',
                        'delivery_branch'=>$delivery_branch,
						'delivery_region'=>$dbrh->region_id??'',
                        'delivery_area'=>$bbrh->area_id??'',
                        'rate'=>$this->input->post('rate['.$link_id.']')??'',
                        'discount'=>$this->input->post('discount['.$link_id.']')??'',
						'final_rate'=>$this->input->post('final_rate['.$link_id.']')??'',
                        'insurance'=>$this->input->post('insurance['.$link_id.']')??'',
                        'paymode'=>$this->input->post('paymode['.$link_id.']')??'',
                        'potential_tonnage'=>$this->input->post('pton['.$link_id.']')??'',
                        'potential_amount'=>$this->input->post('pamnt['.$link_id.']')??'',
                        'expected_tonnage'=>$this->input->post('eton['.$link_id.']')??'',
                        'expected_amount'=>$this->input->post('eamnt['.$link_id.']')??'',
                        'vehicle_type'=>$this->input->post('vtype['.$link_id.']')??'',
                        'carrying_capacity'=>$this->input->post('capacity['.$link_id.']')??'',
                        'invoice_value'=>$this->input->post('invoice['.$link_id.']')??'',
                        'comp_id'=>$this->session->companey_id,
            );
            //print_r($data);
            $potential_tonnage  +=   $this->input->post('pton['.$link_id.']');
            $potential_amount   +=   $this->input->post('pamnt['.$link_id.']');
            $expected_tonnage   +=   $this->input->post('eton['.$link_id.']');
            $expected_amount    +=   $this->input->post('eamnt['.$link_id.']');
            $this->Branch_model->add_deal_data($data);
        }

        $this->db->where('id',$deal_id);
        $this->db->update('commercial_info',
        array(
            'potential_amount'  => $potential_amount,
            'potential_tonnage' => $potential_tonnage,
            'expected_amount'   => $expected_amount,
            'expected_tonnage'  => $expected_tonnage
            )
        );
        file_get_contents(base_url('dashboard/pdf_gen/'.$deal_id));
        echo'1';
    }

    public function edit_commercial_info($deal_id)
    {
        $this->load->model(array('Client_Model','Leads_Model','Branch_model'));
        $keyword = $this->uri->segment(4);
        if(!empty($keyword)){
            $data_type = base64_decode($keyword);
        }else{
            $data_type = '';
        }       
        $data['data_type'] = $data_type;
        $data['title'] = 'Edit Deal';
        $data['deal'] =$deal= $this->Branch_model->get_deal($deal_id);
        $data['deal_data'] = $this->Branch_model->get_deal_data($deal_id);
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($deal->enquiry_id);
        $data['branch'] = $this->Branch_model->branch_list()->result();
        $dis= $this->db->select('d.discount')
                                        ->from('discount_matrix d')
                                        ->join('tbl_admin a','a.discount_id=d.id','left')
                                        ->where('a.pk_i_admin_id='.$this->session->user_id)
                                        ->get()->row();
        $data['max_discount'] = !empty($dis)?$dis->discount:100;
        $data['content'] = $this->load->view('enquiry/edit_deal', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function ask_deal_approval($deal_id)
    {
        $this->load->model(array('User_model','Enquiry_Model','Leads_Model','Branch_model'));
        if(!empty($deal_id))
        {
           $deal = $this->Branch_model->get_deal($deal_id);
           $enq  = $this->Enquiry_Model->getEnquiry(array('enquiry.enquiry_id'=>$deal->enquiry_id))->row();

           $this->db->where('deal_id',$deal_id);
           $this->db->where('request_to_uid',$this->session->user_id);
           $req_log2 = $this->db->get('deal_approval_history')->row_array();
            
           if($deal->createdby==$this->session->user_id || !empty($req_log2))
           {
                $user_id = $deal->createdby;
                $udata = $this->User_model->read_by_id($user_id);
                $current_user = $this->User_model->read_by_id($this->session->user_id);
                
                $this->db->set('related_to',$current_user->report_to);
                $this->Leads_Model->add_comment_for_events_popup($deal->quatation_number.' Deal needs approval, edited by '.$udata->s_display_name.' '.$udata->last_name,date('d-m-Y'),$udata->s_display_name.' '.$udata->last_name,'','','',date('H:i:s'),$enq->Enquery_id,0,'Deal approval',1,0,$current_user->report_to);
              
                $this->db->set('approval','pending');
                $this->db->where('id',$deal->id);
                $this->db->update('commercial_info');
                $this->session->set_flashdata('message','Approval Request Send.');
                
                $this->db->where('deal_id',$deal_id);
                $this->db->where('request_from_uid',$this->session->user_id);                
                if($this->db->get('deal_approval_history')->num_rows()){
                    $this->db->where('deal_id',$deal_id);
                    $this->db->where('request_from_uid',$this->session->user_id);                
                    $this->db->set('status','pending');
                    $this->db->update('deal_approval_history');
                }else{
                    $this->db->insert('deal_approval_history',
                    array(
                        'comp_id' => $this->session->companey_id,
                        'deal_id' => $deal_id,
                        'request_from_uid' => $this->session->user_id,
                        'request_to_uid' => $current_user->report_to,
                        'status' => 'pending'
                        )
                    );
                }
                $this->Leads_Model->add_comment_for_events_stage($deal->quatation_number.' Deal approval request send.',$enq->Enquery_id,0,0,'',0);

                redirect($_SERVER['HTTP_REFERER']);
           }
           else
           {
               //echo $this->db->last_query();
            $this->session->set_userdata('exception','Deal not created by you.');
            redirect($_SERVER['HTTP_REFERER']);

           }
        }
    }

    public function deal_action($deal_id,$action)
    {
        $action = strtolower($action);
        $this->load->model(array('User_model','Enquiry_Model','Leads_Model','Branch_model'));
        if(!empty($deal_id) && !empty($action))
        {

           $deal = $this->Branch_model->get_deal($deal_id);
           $enq  = $this->Enquiry_Model->getEnquiry(array('enquiry.enquiry_id'=>$deal->enquiry_id))->row();
           $udata = $this->User_model->read_by_id($deal->createdby);

           $cdata = $this->User_model->read_by_id($this->session->user_id);

           if($action=='approve' && $deal->createdby!=$this->session->user_id)
           {
                
              $this->Leads_Model->add_comment_for_events_popup($deal->quatation_number.' Deal approved By '.$cdata->s_display_name.' '.$cdata->last_name,date('d-m-Y'),$cdata->s_display_name.' '.$cdata->last_name,'','','',date('H:i:s'),$enq->Enquery_id,0,$deal->quatation_number.' Deal approved',1,0);

              $this->Leads_Model->add_comment_for_events_stage($deal->quatation_number.' Deal approved ',$enq->Enquery_id,0,0,'',0);

                //$this->db->where('id',$deal->copy_id)->delete('commercial_info');

                $this->db->set('approval','done');
                $this->db->set('edited',0);
                $this->db->set('copy_id',NULL);
                $this->db->where('id',$deal->id);
                $this->db->update('commercial_info');

                $this->session->set_flashdata('message','Deal approved.');
                redirect($_SERVER['HTTP_REFERER']);
           }
           else if($action=='reject' && $deal->createdby!=$this->session->user_id)
           {
                
              $this->Leads_Model->add_comment_for_events_popup('Deal Rejected By '.$cdata->s_display_name.' '.$cdata->last_name,date('d-m-Y'),$cdata->s_display_name.' '.$cdata->last_name,'','','',date('H:i:s'),$enq->Enquery_id,0,'Deal Rejected',1,0);

              $this->Leads_Model->add_comment_for_events_stage('Deal Rejected ',$enq->Enquery_id,0,0,'',0);
                $this->db->set('original','1');
                $this->db->where('id',$deal->copy_id);
                $this->db->update('commercial_info');

                $this->db->where('id',$deal->id);
                $this->db->delete('commercial_info');

                $this->session->set_flashdata('message','Deal Rejected.');
                redirect($_SERVER['HTTP_REFERER']);
           }
           else if($action=='resend' && $deal->createdby!=$this->session->user_id)
           {
             
                $upper = $this->User_model->read_by_id($cdata->report_to);
                $this->db->set('related_to',$cdata->report_to);
              $this->Leads_Model->add_comment_for_events_popup('Deal Request passed to '.$upper->s_display_name.' '.$upper->last_name,date('d-m-Y'),$cdata->s_display_name.' '.$cdata->last_name,'','','',date('H:i:s'),$enq->Enquery_id,0,'Send for Approval ',1,0);

              $this->Leads_Model->add_comment_for_events_stage('Deal Request Passed to '.$upper->s_display_name.' '.$upper->last_name,$enq->Enquery_id,0,0,'',0);
                // $this->db->set('original','1');
                // $this->db->where('id',$deal->copy_id);
                // $this->db->update('commercial_info');

                // $this->db->where('id',$deal->id);
                // $this->db->delete('commercial_info');

                $this->session->set_flashdata('message','Send for Approval.');
                redirect($_SERVER['HTTP_REFERER']);
           }
           else
           {echo'ss';
            $this->session->set_userdata('exception','Deal created by you.');
           }
        }
    }


    public function branch_panel_clone($did,$type='branch')
    {
    $this->load->model('Branch_model');
    $region_list = $this->Branch_model->sales_region_list()->result();
    echo'<div class="row" style="padding-bottom: 20px;border-bottom: 1px solid #d6d6d6;">
            <div class="col-lg-6">
                <div class="form-group">';
                if($type=='branch')
                {
                echo'<div class="col-md-6">
                            <label>Region</label>
                            <select data-did="'.$did.'" name="b_region" id="b_reg'.$did.'" class="form-control" onchange="load_areas(this)">
                                <option value="">Select Region</option>';

                            if(!empty($region_list))
                            {
                                foreach ($region_list as $reg)
                                {
                                    echo'<option value="'.$reg->region_id.'">'.$reg->name.'</option>';
                                }
                                
                            }
                        
                    echo'</select>
                    </div>
                     <div class="col-md-6">
                        <label>Area</label>
                        <select class="form-control" name="barea" id="b_area'.$did.'" data-did="'.$did.'"  onchange="load_branch_particular(this)">
                        </select>
                    </div>';
                }
                    echo'<div class="col-md-12">
                        <label>Booking From <font color="red">*</font></label>
                        <select id="bbranch'.$did.'" name="bbranch['.$did.']" class="form-control booking_from" required onchange="generate_table()" data-close-on-select="false">';
                        if($type=='zone')
                        {
                            $zones =   $this->Branch_model->zone_list()->result();
                            
                            foreach ($zones as $key => $value)
                            {
                                echo'<option value="'.$value->zone_id.'">'.$value->name.'</option>';
                            }
                        }
                    echo'</select>
                    </div>
                </div>
            </div>
	
            <div class="col-lg-6">
                <div class="form-group">';
            if($type=='branch')
            {
                echo'<div class="col-md-6">
                        <label>Region</label>
                        <select name="region" id="d_reg'.$did.'" data-did="'.$did.'" class="form-control" onchange="load_areas(this)">
                            <option value="">Select Region</option>';

                            if(!empty($region_list))
                            {
                                foreach ($region_list as $reg)
                                {
                                    echo'<option value="'.$reg->region_id.'">'.$reg->name.'</option>';
                                }
                                
                            }
                    
                echo'</select>
                    </div>
                    <div class="col-md-6">
                        <label>Area</label>
                        <select id="d_area'.$did.'" data-did="'.$did.'" class="form-control" name="area"  onchange="load_branch_particular(this)">
                            
                        </select>
                    </div>
                    <div class="col-md-12">
                        <select id="dbranch_holder'.$did.'" data-did="'.$did.'" onchange="include_branch(this)" multiple></select>
                    </div>
                    ';
                }
                echo'<div class="col-md-12">
                        <label>Delivery To<font color="red">*</font></label>
                        <select class="form-control delivery_to" name="dbranch['.$did.']" id="dbranch'.$did.'" onchange="generate_table()" multiple required data-close-on-select="false">';
                  
                        if($type=='zone')
                        {
                            $zones =   $this->Branch_model->zone_list()->result();
                            foreach ($zones as $key => $value)
                            {
                                echo'<option value="'.$value->zone_id.'">'.$value->name.'</option>';
                            }
                        }
                    echo'</select>
            </div>
        </div>
    </div>
</div>';
    }

    public function account_by_company()
    {
        $this->load->model(array('Enquiry_Model'));
        $comp_id  = $this->input->get('comp_id');
		

            $all_reporting_ids  = $this->common_model->get_categories($this->session->user_id);

            $this->db->select('enquiry.enquiry_id,enquiry.Enquery_id,enquiry.client_name');
            $this->db->from('enquiry');
            $this->db->where("enquiry.company",$comp_id);

            /* if(!empty($this->input->get('escape_lead')))
                $this->db->where('enquiry.status!=1'); */

            $where="";
            $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            $this->db->where($where);
//New
//$ids = array('16313', '16314');//For Local Server
$ids = array('40805', '40807', '40856');
if(in_array($comp_id,$ids)){
			$this->db->or_where('enquiry.company', $comp_id);
}
//End			
            $res = $this->db->get();

        foreach ($res->result() as $key => $value) 
        {
            if($value->client_name!=''){
            echo'<option value="'.$value->Enquery_id.'">'.$value->client_name.'</option>';
            }
        }
    }

    
    public function account_by_company2()
    {
        $this->load->model(array('Enquiry_Model'));
        $comp_id  = $this->input->get('comp_id');

            $all_reporting_ids  = $this->common_model->get_categories($this->session->user_id);

            $this->db->select('enquiry.enquiry_id,enquiry.Enquery_id,enquiry.client_name');
            $this->db->from('enquiry');
            $this->db->where("enquiry.company",$comp_id);

            if(!empty($this->input->get('escape_lead')))
                $this->db->where('enquiry.status!=1');

            $where="";
            $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            $this->db->where($where);
            $res = $this->db->get();

        foreach ($res->result() as $key => $value) 
        {
            if($value->client_name!=''){
            echo'<option value="'.$value->enquiry_id.'">'.$value->client_name.'</option>';
            }
        }
    }

    public function contact_by()
    {
        $by = $this->input->get('by');
        $key  = $this->input->get('key');
        
        if($by=='account')
        {
            $res =  $this->db->where('client_id',$key)->where('comp_id',$this->session->companey_id)->get('tbl_client_contacts');
        }
        else if($by=='company')
        {
            $res =  $this->db->select('con.*')
                                ->from('tbl_client_contacts con')
                            ->join('enquiry','enquiry.enquiry_id=con.client_id','left')
                            ->where('enquiry.company="'.$key.'"')
                            ->where('con.comp_id',$this->session->companey_id)->get();
        }
        if(!empty($res))
        {
             foreach ($res->result() as $key => $value) 
            {
                echo'<option value="'.$value->cc_id.'">'.$value->c_name.'</option>';
            }
        }
       
    }

    public function company_by_name()
    {
        $key = $this->input->get('key');

        $comp_id = $this->session->companey_id;
     $res=   $this->db->where('company_name',$key)->where('comp_id',$comp_id)->get('tbl_company')->row();
    
         if(!empty($res))
         {
          echo $res->id;
         }
         else
            echo 0;
    }

    public function get_contact_by_id()
    {
        $key = $this->input->get('id');

        $comp_id = $this->session->companey_id;
     $res=   $this->db->where('cc_id',$key)->where('comp_id',$comp_id)->get('tbl_client_contacts')->row();
    
         if(!empty($res))
         {
            echo json_encode(array('status'=>1,'data'=>$res));
         }
         else
           echo json_encode(array('status'=>0));
    }

    function prepare_vtrans($enq_code)
    {
        $ag_data = array('name'=>$this->input->post("agg_user", true),
                            'mobile'=>$this->input->post("agg_mobile", true),
                            'email'=>$this->input->post("agg_email", true),
                            'address'=>$this->input->post("agg_adrs", true),
                            'date'=>$this->input->post("agg_date", true),
							'deal'=>$this->input->post("deal_id", true),
                            );
        $ag_encode = base64_encode(json_encode($ag_data));

        $this->load->model(array('Branch_model','Leads_Model','Location_model'));

        $user = $this->db->where('pk_i_admin_id',$this->session->user_id)->get('tbl_admin')->row();

        $deal_id = $this->input->post('deal_id');

        // $zone = $this->input->post('zone_id');
        // $bank = $this->Branch_model->bank_by_zone($zone);

        $agr =  $this->db->select('id as ref_no')->limit(1)->get('tbl_aggriment')->row();
        $ref_no = !empty($agr->ref_no)?$agr->ref_no+1:1;

        $deal   =    $this->Branch_model->get_deal($deal_id);
       // $oc =(array) json_decode($deal->other_charges);
		
		if($deal->status==0 && $deal->approval!='done'){
        $oc =(array) json_decode($deal->other_charges);
	   }else{
		$oc =(array) json_decode($deal->update_charges);
	   }

        $deal_data = $this->Branch_model->get_deal_data($deal_id);
		
///////////////////// Charges Table Implented By Shivam ///////////////////
		
		if($deal->booking_type=='ftl')
    {
        $freight_table = '';
        $area_table='';
        $oda_table='';
        $freight_table .="<table border='1px' width='100%'>
        <thead>
          <tr>
            <th style='background:#00b0f0;'>From</th>
            <th style='background:#00b0f0;'>To</th>
            <th style='background:#00b0f0;'>Vehicle Type</th>
            <th style='background:#00b0f0;'>Freight</th>
          </tr>
        </thead>
        <tbody>
        ";
        foreach ($deal_data as $key => $drow)
        {
            $freight_table.="
            <tr>
              <td>".$drow->bbranch."</td>
              <td>".$drow->dbranch."</td>
              <td>".$drow->vtype_name."</td>
              <td>".$drow->invoice_value."</td>
            </tr>
            ";
        }
        $freight_table.="</tbody></table>";
        $fuel_surcharge='';
      }
      else
      {

          if($deal->btype=='branch' || $deal->btype=='zone')
          {
              if($deal->btype=='branch')
              {
                $query = $this->db->query("SELECT DISTINCT deal.booking_branch as bid ,branch.branch_name as bname from deal_data deal left join branch on branch.branch_id=deal.booking_branch where deal.deal_id=$deal_id");
              }
              else if($deal->btype=='zone')
              {
                 $query = $this->db->query("SELECT DISTINCT deal.booking_branch as bid ,zones.name as bname from deal_data deal left join zones on zones.zone_id=deal.booking_branch where deal.deal_id=$deal_id");
              }
             
              $freight_table ='';

              if(!empty($query))
              {
                $result = $query->result();

              foreach ($result as $key => $rows)
              {
                $freight_table.='
                <table border="1" width="100%">
                      <thead>
                      <tr>
                        <th style="background:#00b0f0;">
                        <div style="display:inline-block;">
                      To<br>
                      From 
                    </div>
                    <div style="display:inline-block; width:50px;">
                      <i class="fa fa-arrow-right"></i><br>
                      <i class="fa fa-arrow-down"></i>
                    </div>
                        </th>';

              if($deal->btype=='branch')
              {
              $cols = $this->db->query("SELECT deal.delivery_branch as did ,branch.branch_name as dname,deal.rate,deal.discount from deal_data deal left join branch on branch.branch_id=deal.delivery_branch where deal.deal_id=$deal_id and deal.booking_branch = ".$rows->bid)->result();
              }
              else if($deal->btype=='zone')
              {
              $cols = $this->db->query("SELECT deal.delivery_branch as did ,zones.name as dname,deal.rate,deal.discount from deal_data deal left join zones on zones.zone_id=deal.delivery_branch where deal.deal_id=$deal_id and deal.booking_branch = ".$rows->bid)->result();
              }

                foreach ($cols as $key2 => $value2)
                {
                    $freight_table.='<th style="background:#00b0f0;">'.$value2->dname.'</th>';
                }
                $freight_table.='</tr>
                <tr>
                <th style="background:#00b0f0;">'.$rows->bname.'</th>';

                foreach ($cols as $key2 => $value2)
                {
                    $r = $value2->rate;
                    $d = $value2->discount;
                    $price = $r*(1-round(($d/100),2));
                    $freight_table.='<td>'.$price.'/'.$oc['rate_type'].'</td>';
                }

              $freight_table.='</tr>

            </tbody></table>';                   
              }
      
            }//ifend
            else
            {
              $freight_table='';
            }
            $area_table='';

          }
          else
          {




          }//type-zone/area

          $oda_query = $this->db->query("SELECT concat(distance_from,'-',distance_to) as dis,concat(weight_from,'-',weight_to) as we,charge,id from oda_matrix GROUP bY dis,we ORDER BY id ASC")->result();
            if(!empty($oda_query))
            {
       
              $oda_row = array_unique(array_column($oda_query, 'dis'));
              $oda_col =  array_unique(array_column($oda_query, 'we'));
               $oda_table = '<table border="1" width="100%">';

                foreach ($oda_row as $key => $value1)
                {
                        if($key==0)
                        {
                          $oda_table.='
                          <thead><tr>
                          <th class="text-center" style="background:#00b0f0;">Distance Range</th>';
                          foreach ($oda_col as $key2 => $value2)
                          {
                            $col = explode('-',$value2);
                           $oda_table.='<th class="text-center" style="background:#00b0f0;">'.$col[0].' To '.$col[1].'<br> KGS</th>';
                          }
                          $oda_table.='</tr></thead>
                          <tbody>';
                        }
                  $oda_table.='<tr>';
                  $row = explode('-',$value1);
                  $oda_table.='<th style="background:#00b0f0;">'.$row[0].' To '.$row[1].' KMS</th>';
                  
                  foreach ($oda_col as $key2 => $value2)
                  {
                      foreach ($oda_query as $key3 => $value3)
                      {
                        if($value1==$value3->dis && $value2==$value3->we)
                          $oda_table.='<td>'.$value3->charge.'</td>';    
                      }
                  }

                  $oda_table.='</tr>';                   
                }
                $oda_table.= '</tbody></table>';

            }
            else
            {
              $oda_table  = '';
            }

            $fuel_data = $this->db->where('comp_id',$this->session->companey_id)->get('fuel_surcharge')->result();
                    $fuel_surcharge='';
                    if(!empty($fuel_data))
                    {
                         $fuel_surcharge .="<table border='1px' width='100%'>
                         <thead>
                                <tr>
                                  <th style='background:#00b0f0;'>Greater Than or<br> Equal To (Rs.)</th>
                                  <th style='background:#00b0f0;'>Less Than Rs.</th>
                                  <th style='background:#00b0f0;'>FSC Applicable (%)</th>
                                 
                                </tr>
                              </thead>
                              <tbody>
                              ";
                              foreach ($fuel_data as $fkey => $frow)
                              {
                                  $fuel_surcharge.="
                                  <tr>
                                    <td>".$frow->greater_than."</td>
                                    <td>".$frow->less_than."</td>
                                    <td>".$frow->fsc."</td>
                                  </tr>
                                  ";
                              }
                              $fuel_surcharge.="</tbody></table>";
                      }

      }
 ///////////////////// Charges Table Implented By Shivam ///////////////////      

        $enq = $this->db->select('e.*,r.region_name,desg.desi_name,tbl_company.company_name')
                        ->from('enquiry e')
                        ->join('tbl_region r','r.region_id=e.region_id','left')
                        ->join('tbl_designation desg','desg.id=e.designation','left')
                        ->join('tbl_company','tbl_company.id=e.company','left')
                        ->where('e.enquiry_id',$deal->enquiry_id)
                        ->get()->row();

        $city = $this->db->where('id',$enq->city_id)->get('city')->row();

        $enq_designation = !empty($enq->desi_name)?$enq->desi_name:'';
        
        for($i=1; $i<150;$i++)
        {
            $input['ip'][$i] = '';
        }


        $input['ip'][0] = $input['ip'][5] = $ref_no;
$input['ip'][50] = $input['ip'][38] = $input['ip'][1] = $enq->name_prefix.' '.$enq->name.' '.$enq->lastname;
        $input['ip'][2] = $enq->region_name;
        $input['ip'][3] = '';
        $input['ip'][4] = '';
        $input['ip'][6] = date('d');
        $input['ip'][7] = date('F');
        $input['ip'][8] = date('y');
        $city = $this->db->select('city')->from('city')->where('id',$enq->city_id)->get()->row();
        $city_name ='';
        if(!empty($city))
            $city_name = $city->city;
        $input['ip'][9] =  $city_name; //$enq->city_id;

    $input['ip'][52] = $input['ip'][47] = $input['ip'][10] = $enq->company_name;
    
        $input['ip'][48]= $input['ip'][11] = $enq->address;
        $input['ip'][12] = date('Y-m-d');

        $input['ip'][16] = 'Yes';

        //Acount details -

        $input['ip'][20]= '';//$bank->account_no;
        $input['ip'][21] = '';//$bank->ifsc;
        $input['ip'][22] = '';//$bank->bank_branch;

        //user Details 

$input['ip'][95] = $input['ip'][94] = $input['ip'][90] =$input['ip'][46] =$input['ip'][41]=$input['ip'][34] = $input['ip'][23] = $user->s_display_name.' '.$user->last_name;
$input['ip'][96] = $input['ip'][91] = $input['ip'][42]=$input['ip'][35] = $input['ip'][24] = $user->designation;

$input['ip'][97] = date('Y-m-d');

$input['ip'][45]= $user->s_user_email;

    ///enq details =

$input['ip'][92] = $input['ip'][43]=$input['ip'][36] = $input['ip'][25] =$enq->name_prefix.' '.$enq->name.' '.$enq->lastname;
$input['ip'][93] = $input['ip'][44]=$input['ip'][37] = $input['ip'][26] =$enq_designation;

$email_list[0] = $input['ip'][49] = $enq->email;
 
        $input['ip'][39] = $deal->booking_type;


$user_list = $this->db->select('CONCAT(s_display_name," ",last_name) emp_name,designation')->from('tbl_admin')->where('companey_id',$this->session->companey_id)->get()->result();

        $raw['ip'] = $input['ip'];
        $raw['email_list'] = $email_list;
        $raw['charges'] = $oc;
        // Industries list

    $this->db->select("tbl_input.*,input_types.title as type");
    $this->db->from('tbl_input');
    $this->db->join('input_types','tbl_input.input_type=input_types.id','LEFT');
    $this->db->where('input_id','305'); //for v-trans dynamic field 4478 
    $industries = $this->db->get()->row_array();

    $this->db->select("tbl_input.*,input_types.title as type");
    $this->db->from('tbl_input');
    $this->db->join('input_types','tbl_input.input_type=input_types.id','LEFT');
    $this->db->where('input_id','287');
    $client_type = $this->db->get()->row_array();

    $this->db->select("tbl_input.*,input_types.title as type");
    $this->db->from('tbl_input');
    $this->db->join('input_types','tbl_input.input_type=input_types.id','LEFT');
    $this->db->where('input_id','4478');
    $compt = $this->db->get()->row_array();
        
		
		
        $raw['industries'] = $industries;
        $raw['client_type'] = $client_type;
        $raw['compt'] = $client_type;
        //print_r($oc);exit();
        $raw['deal_id'] = $deal_id;
        $raw['city_list'] = $this->location_model->city_list();
        $raw['emp_list'] = $user_list;
        //print_r($raw['city_list']);exit();
       $data =  $this->load->view('aggrement/new-input-vtrans',$raw,TRUE);
	   
	    $data = str_replace('@freight_table', $freight_table,$data);
        $data = str_replace('@area_table', $area_table,$data);
        $data = str_replace('@oda_table',$oda_table, $data);
        $data = str_replace('@fuel_surcharge',$fuel_surcharge, $data);

        echo'<!DOCTYPE html>
        <html>
        <head>
            <title>Form</title>
        </head>
        <body align="center" style="background:black;">  
        <div style="width:100%;" align="center">
            <form action="'.base_url('client/attach_agreement_data').'" method="post" enctype="multipart/form-data">
            <input type="hidden" name="ag_data" value="'.$ag_encode.'">
            <input type="hidden" name="enq_code" value="'.$enq_code.'">
            <div style="width:735px; min-height:100%; padding:40px; margin:15px; background:white;">
            '.$data.'
            </div>
            <center><button style="    padding: 15px 25px;
    background: #209ed9;
    color: white;
    margin: 15px;
    border: 0px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 15px;" onclick="">Generate PDF</button></center>
            </form>
        </div>
        </body>
        </html>';

    }

    public function attach_agreement_data()
    {
        // echo "<pre>";
        // print_r($_FILES);
        // print_r($_POST);
        // exit();
        if(empty($_POST)){
            die('Direct Access Not Allowed');
        }
        $this->load->model(array('Branch_model','Leads_Model','Location_model'));
        $deal_id = $this->input->post('deal_id');
        $deal   =    $this->Branch_model->get_deal($deal_id);
       // $oc =(array) json_decode($deal->other_charges);
		if($deal->status==0 && $deal->approval!='done'){
        $oc =(array) json_decode($deal->other_charges);
	   }else{
		$oc =(array) json_decode($deal->update_charges);
	   }

        for($i=110;$i<=115;$i++)
        {   
            $_POST['ip'][$i] = 'No';

            if(!empty($_FILES['ip']['tmp_name'][$i]))
            {
                $fname = $_FILES['ip']['name'][$i];
                $ep = explode('.', $fname);
                $ext = end($ep);
                $nf = 'Doc'.time().rand(1000,9999).'.'.$ext;

                $path = 'uploads/enquiry_documents/'.$this->session->companey_id;

               if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $path= $path.'/'.$nf;
                if(move_uploaded_file($_FILES['ip']['tmp_name'][$i],$path))
                    $_POST['ip'][$i] ='Yes';
//INSERT DOCUMENT CODE
$doc_ary = array(
			'deal_id'=>$deal_id,
            'comp_id'=>$this->session->companey_id,
			'doc_id'=>$i,
            'doc_url'=>$path,
            'created_by'=>$this->session->user_id,
        );
$this->db->insert('tbl_aggriment_docs',$doc_ary);				
            }
        }
		
		$deal_data = $this->Branch_model->get_deal_data($deal_id);
		
///////////////////// Charges Table Implented By Shivam ///////////////////
		
		if($deal->booking_type=='ftl')
    {
        $freight_table = '';
        $area_table='';
        $oda_table='';
        $freight_table .="<table border='1px' width='100%'>
        <thead>
          <tr>
            <th style='background:#00b0f0;'>From</th>
            <th style='background:#00b0f0;'>To</th>
            <th style='background:#00b0f0;'>Vehicle Type</th>
            <th style='background:#00b0f0;'>Freight</th>
          </tr>
        </thead>
        <tbody>
        ";
        foreach ($deal_data as $key => $drow)
        {
            $freight_table.="
            <tr>
              <td>".$drow->bbranch."</td>
              <td>".$drow->dbranch."</td>
              <td>".$drow->vtype_name."</td>
              <td>".$drow->invoice_value."</td>
            </tr>
            ";
        }
        $freight_table.="</tbody></table>";
        $fuel_surcharge='';
      }
      else
      {

          if($deal->btype=='branch' || $deal->btype=='zone')
          {
              if($deal->btype=='branch')
              {
                $query = $this->db->query("SELECT DISTINCT deal.booking_branch as bid ,branch.branch_name as bname from deal_data deal left join branch on branch.branch_id=deal.booking_branch where deal.deal_id=$deal_id");
              }
              else if($deal->btype=='zone')
              {
                 $query = $this->db->query("SELECT DISTINCT deal.booking_branch as bid ,zones.name as bname from deal_data deal left join zones on zones.zone_id=deal.booking_branch where deal.deal_id=$deal_id");
              }
             
              $freight_table ='';

              if(!empty($query))
              {
                $result = $query->result();

              foreach ($result as $key => $rows)
              {
                $freight_table.='
                <table border="1" width="100%">
                      <thead>
                      <tr>
                        <th style="background:#00b0f0;">
                        <div style="display:inline-block;">
                      To<br>
                      From 
                    </div>
                    <div style="display:inline-block; width:50px;">
                      <i class="fa fa-arrow-right"></i><br>
                      <i class="fa fa-arrow-down"></i>
                    </div>
                        </th>';

              if($deal->btype=='branch')
              {
              $cols = $this->db->query("SELECT deal.delivery_branch as did ,branch.branch_name as dname,deal.rate,deal.discount from deal_data deal left join branch on branch.branch_id=deal.delivery_branch where deal.deal_id=$deal_id and deal.booking_branch = ".$rows->bid)->result();
              }
              else if($deal->btype=='zone')
              {
              $cols = $this->db->query("SELECT deal.delivery_branch as did ,zones.name as dname,deal.rate,deal.discount from deal_data deal left join zones on zones.zone_id=deal.delivery_branch where deal.deal_id=$deal_id and deal.booking_branch = ".$rows->bid)->result();
              }

                foreach ($cols as $key2 => $value2)
                {
                    $freight_table.='<th style="background:#00b0f0;">'.$value2->dname.'</th>';
                }
                $freight_table.='</tr>
                <tr>
                <th style="background:#00b0f0;">'.$rows->bname.'</th>';

                foreach ($cols as $key2 => $value2)
                {
                    $r = $value2->rate;
                    $d = $value2->discount;
                    $price = $r*(1-round(($d/100),2));
                    $freight_table.='<td>'.$price.'/'.$oc['rate_type'].'</td>';
                }

              $freight_table.='</tr>

            </tbody></table>';                   
              }
      
            }//ifend
            else
            {
              $freight_table='';
            }
            $area_table='';

          }
          else
          {




          }//type-zone/area

          $oda_query = $this->db->query("SELECT concat(distance_from,'-',distance_to) as dis,concat(weight_from,'-',weight_to) as we,charge,id from oda_matrix GROUP bY dis,we ORDER BY id ASC")->result();
            if(!empty($oda_query))
            {
       
              $oda_row = array_unique(array_column($oda_query, 'dis'));
              $oda_col =  array_unique(array_column($oda_query, 'we'));
               $oda_table = '<table border="1" width="100%">';

                foreach ($oda_row as $key => $value1)
                {
                        if($key==0)
                        {
                          $oda_table.='
                          <thead><tr>
                          <th class="text-center" style="background:#00b0f0;">Distance Range</th>';
                          foreach ($oda_col as $key2 => $value2)
                          {
                            $col = explode('-',$value2);
                           $oda_table.='<th class="text-center" style="background:#00b0f0;">'.$col[0].' To '.$col[1].'<br> KGS</th>';
                          }
                          $oda_table.='</tr></thead>
                          <tbody>';
                        }
                  $oda_table.='<tr>';
                  $row = explode('-',$value1);
                  $oda_table.='<th style="background:#00b0f0;">'.$row[0].' To '.$row[1].' KMS</th>';
                  
                  foreach ($oda_col as $key2 => $value2)
                  {
                      foreach ($oda_query as $key3 => $value3)
                      {
                        if($value1==$value3->dis && $value2==$value3->we)
                          $oda_table.='<td>'.$value3->charge.'</td>';    
                      }
                  }

                  $oda_table.='</tr>';                   
                }
                $oda_table.= '</tbody></table>';

            }
            else
            {
              $oda_table  = '';
            }

            $fuel_data = $this->db->where('comp_id',$this->session->companey_id)->get('fuel_surcharge')->result();
                    $fuel_surcharge='';
                    if(!empty($fuel_data))
                    {
                         $fuel_surcharge .="<table border='1px' width='100%'>
                         <thead>
                                <tr>
                                  <th style='background:#00b0f0;'>Greater Than or<br> Equal To (Rs.)</th>
                                  <th style='background:#00b0f0;'>Less Than Rs.</th>
                                  <th style='background:#00b0f0;'>FSC Applicable (%)</th>
                                 
                                </tr>
                              </thead>
                              <tbody>
                              ";
                              foreach ($fuel_data as $fkey => $frow)
                              {
                                  $fuel_surcharge.="
                                  <tr>
                                    <td>".$frow->greater_than."</td>
                                    <td>".$frow->less_than."</td>
                                    <td>".$frow->fsc."</td>
                                  </tr>
                                  ";
                              }
                              $fuel_surcharge.="</tbody></table>";
                      }

      }
 ///////////////////// Charges Table Implented By Shivam ///////////////////

		
        $data = $_POST;
        $data['oc'] =$oc;
       $this->print_new($data);
    }

    function print_new($data)
    {   
        echo'<center><h2>Please Wait..</h2></center>';
        $input['ip'] = $data['ip'];
        // $input['ip'][24]= ' designation';
        // $input['ip'][87]='dsf';
        $input['email_list'] = $data['email_list'];
        $input['oc'] = $data['oc'];
        $input['gst_specify'] = $data['gst_specify'];
        $this->load->library('pdf');
		
$json = base64_decode($data['ag_data']);
$ag_data = json_decode($json,true);
$deal_id = $ag_data['deal'];
$deal_data = $this->Branch_model->get_deal_data($deal_id);
$deal   =    $this->Branch_model->get_deal($deal_id);		
///////////////////// Charges Table Implented By Shivam ///////////////////
		
		if($deal->booking_type=='ftl')
    {
        $freight_table = '';
        $area_table='';
        $oda_table='';
        $freight_table .="<table border='1px' width='100%'>
        <thead>
          <tr>
            <th style='background:#ECF8FD;'>From</th>
            <th style='background:#ECF8FD;'>To</th>
            <th style='background:#ECF8FD;'>Vehicle Type</th>
            <th style='background:#ECF8FD;'>Freight</th>
          </tr>
        </thead>
        <tbody>
        ";
        foreach ($deal_data as $key => $drow)
        {
            $freight_table.="
            <tr>
              <td>".$drow->bbranch."</td>
              <td>".$drow->dbranch."</td>
              <td>".$drow->vtype_name."</td>
              <td>".$drow->invoice_value."</td>
            </tr>
            ";
        }
        $freight_table.="</tbody></table>";
        $fuel_surcharge='';
      }
      else
      {

          if($deal->btype=='branch' || $deal->btype=='zone')
          {
              if($deal->btype=='branch')
              {
                $query = $this->db->query("SELECT DISTINCT deal.booking_branch as bid ,branch.branch_name as bname from deal_data deal left join branch on branch.branch_id=deal.booking_branch where deal.deal_id=$deal_id");
              }
              else if($deal->btype=='zone')
              {
                 $query = $this->db->query("SELECT DISTINCT deal.booking_branch as bid ,zones.name as bname from deal_data deal left join zones on zones.zone_id=deal.booking_branch where deal.deal_id=$deal_id");
              }
             
              $freight_table ='';

              if(!empty($query))
              {
                $result = $query->result();

              foreach ($result as $key => $rows)
              {
                $freight_table.='
                <table border="1" width="100%">
                      <thead>
                      <tr>
                        <th style="background:#ECF8FD;">
                        <div style="display:inline-block;padding-top:10px;">
                      To<br>
                      From 
                    </div>
                    <div style="display:inline-block; width:50px;">
                      <i class="fa fa-arrow-right"></i><br>
                      <i class="fa fa-arrow-down"></i>
                    </div>
                        </th>';

              if($deal->btype=='branch')
              {
              $cols = $this->db->query("SELECT deal.delivery_branch as did ,branch.branch_name as dname,deal.rate,deal.discount from deal_data deal left join branch on branch.branch_id=deal.delivery_branch where deal.deal_id=$deal_id and deal.booking_branch = ".$rows->bid)->result();
              }
              else if($deal->btype=='zone')
              {
              $cols = $this->db->query("SELECT deal.delivery_branch as did ,zones.name as dname,deal.rate,deal.discount from deal_data deal left join zones on zones.zone_id=deal.delivery_branch where deal.deal_id=$deal_id and deal.booking_branch = ".$rows->bid)->result();
              }

                foreach ($cols as $key2 => $value2)
                {
                    $freight_table.='<th style="background:#ECF8FD;">'.$value2->dname.'</th>';
                }
                $freight_table.='</tr>
                <tr>
                <th style="background:#ECF8FD;">'.$rows->bname.'</th>';

                foreach ($cols as $key2 => $value2)
                {
                    $r = $value2->rate;
                    $d = $value2->discount;
                    $price = $r*(1-round(($d/100),2));
                    $freight_table.='<td>'.$price.'/'.$oc['rate_type'].'</td>';
                }

              $freight_table.='</tr>

            </tbody></table>';                   
              }
      
            }//ifend
            else
            {
              $freight_table='';
            }
            $area_table='';

          }
          else
          {




          }//type-zone/area

          $oda_query = $this->db->query("SELECT concat(distance_from,'-',distance_to) as dis,concat(weight_from,'-',weight_to) as we,charge,id from oda_matrix GROUP bY dis,we ORDER BY id ASC")->result();
            if(!empty($oda_query))
            {
       
              $oda_row = array_unique(array_column($oda_query, 'dis'));
              $oda_col =  array_unique(array_column($oda_query, 'we'));
               $oda_table = '<table border="1" width="100%">';

                foreach ($oda_row as $key => $value1)
                {
                        if($key==0)
                        {
                          $oda_table.='
                          <thead><tr>
                          <th class="text-center" style="background:#ECF8FD;">Distance Range</th>';
                          foreach ($oda_col as $key2 => $value2)
                          {
                            $col = explode('-',$value2);
                           $oda_table.='<th class="text-center" style="background:#ECF8FD;">'.$col[0].' To '.$col[1].'<br> KGS</th>';
                          }
                          $oda_table.='</tr></thead>
                          <tbody>';
                        }
                  $oda_table.='<tr>';
                  $row = explode('-',$value1);
                  $oda_table.='<th style="background:#ECF8FD;">'.$row[0].' To '.$row[1].' KMS</th>';
                  
                  foreach ($oda_col as $key2 => $value2)
                  {
                      foreach ($oda_query as $key3 => $value3)
                      {
                        if($value1==$value3->dis && $value2==$value3->we)
                          $oda_table.='<td>'.$value3->charge.'</td>';    
                      }
                  }

                  $oda_table.='</tr>';                   
                }
                $oda_table.= '</tbody></table>';

            }
            else
            {
              $oda_table  = '';
            }

            $fuel_data = $this->db->where('comp_id',$this->session->companey_id)->get('fuel_surcharge')->result();
                    $fuel_surcharge='';
                    if(!empty($fuel_data))
                    {
                         $fuel_surcharge .="<table border='1px' width='100%'>
                         <thead>
                                <tr>
                                  <th style='background:#ECF8FD;'>Greater Than or<br> Equal To (Rs.)</th>
                                  <th style='background:#ECF8FD;'>Less Than Rs.</th>
                                  <th style='background:#ECF8FD;'>FSC Applicable (%)</th>
                                 
                                </tr>
                              </thead>
                              <tbody>
                              ";
                              foreach ($fuel_data as $fkey => $frow)
                              {
                                  $fuel_surcharge.="
                                  <tr>
                                    <td>".$frow->greater_than."</td>
                                    <td>".$frow->less_than."</td>
                                    <td>".$frow->fsc."</td>
                                  </tr>
                                  ";
                              }
                              $fuel_surcharge.="</tbody></table>";
                      }

      }
 ///////////////////// Charges Table Implented By Shivam ///////////////////


        $res =   $this->load->view('aggrement/print_vtrans',$input,true);
 $res = str_replace('@freight_table', $freight_table,$res);
 //$res = str_replace('@area_table', $area_table,$res);
 //$res = str_replace('@oda_table',$oda_table, $res);
 //$res = str_replace('@fuel_surcharge',$fuel_surcharge, $res);
        // echo $res;
        // exit;
        
        //print_r($ag_data);
        $enq_code = $data['enq_code'];
        $ag_path = 'assets/agreements/Agreement-'.time().rand(1111,9999).'.pdf';
        
        $ary = array(
            'enq_id'=>$enq_code,
			'deal_id'=>$ag_data['deal'],
            'comp_id'=>$this->session->companey_id,
            'agg_name'=>$ag_data['name'],
            'agg_phone'=>$ag_data['mobile'],
            'agg_email'=>$ag_data['email'],
            'agg_adrs'=>$ag_data['address'],
            'file'=>$ag_path,
            'agg_date'=>$ag_data['date'],
            'created_by'=>$this->session->user_id,
            'created_date'=>date('d/m/Y'),
        );
        $this->db->insert('tbl_aggriment',$ary);
        $this->pdf->create($res,0,$ag_path);
        
        //redirect($ag_path);
    }
	
	function get_exist_oda()
    {
		$company = $this->session->companey_id;
	
        $this->db->select('*');
		$this->db->where('comp_id',$company);
        $res=$this->db->get('oda_matrix');
        $oda_data=$res->result();
		
        if(!empty($oda_data)){
			
            echo '<table class="table table-bordered table-hover">
			            <tr>
                            <th>S.No</th>
							<th>Distance</th>
							<th>Weight</th>
                            <th>Charge</th>
                        </tr>';
                foreach($oda_data as $key => $val){
                    $i = $key + 1;					
            echo    '<tr>
			            <td>'.$i.'</td>
                        <td>'.$val->distance_from.' - '.$val->distance_to.' KM</td>
					    <td>'.$val->weight_from.' - '.$val->weight_to.' KG</td>
					    <td>'.$val->charge.'</td>
                    </tr>';
			    }
			echo '</table>';
        }else{
			echo 0;
		}			
    }
	
	function get_aggdoc_list()
    {
		$company = $this->session->companey_id;
	    $dea_id  = $this->input->post('doc_id');
        $this->db->select('*');
		$this->db->where('comp_id',$company);
		$this->db->where('deal_id',$dea_id);
        $res=$this->db->get('tbl_aggriment_docs');
        $doc_data=$res->result();
		
        if(!empty($doc_data)){
			
            echo '<table class="table table-bordered table-hover">
			            <tr>
                            <th>S.No</th>
							<th>Particulars</th>
							<th>File</th>
                            <th>Action</th>
                        </tr>';
                foreach($doc_data as $key => $val){
				if($val->doc_id=='110'){
					$Particulars = 'Copy of Address proof';
				}else if($val->doc_id=='111'){
					$Particulars = 'Copy of Address proof';
				}else if($val->doc_id=='112'){
					$Particulars = 'Copy of GST Registration certificate';
				}else if($val->doc_id=='113'){
					$Particulars = 'Copy of CIN No. / LLPIN';
				}else if($val->doc_id=='114'){
					$Particulars = 'Partnership Deed';
				}else if($val->doc_id=='115'){
					$Particulars = 'MOA & AOA of Company';
				}
                    $i = $key + 1;					
            echo    '<tr>
			            <td>'.$i.'</td>
                        <td>'.$Particulars.'</td>
					    <td><a href="'.base_url($val->doc_url).'" target="_blank" class="btn" data-animation="effect-scale"><i class="fa fa-file" aria-hidden="true"></i></a></td>
					    <td><a href="'.base_url($val->doc_url).'" class="btn" data-animation="effect-scale"  download><i class="fa fa-download" aria-hidden="true"></i></a></td>
                    </tr>';
			    }
			echo '</table>';
        }else{
			echo '<table class="table table-bordered table-hover">
			            <tr>
                            <th>No Data Found!</th>
                        </tr>';
			echo '</table>';
		}			
    }
}