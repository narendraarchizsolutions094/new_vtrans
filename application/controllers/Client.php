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
                array('Ticket_Model','Leads_Model','common_model','enquiry_model', 'dashboard_model', 'Task_Model', 'User_model', 'location_model', 'Message_models','Institute_model','Datasource_model','Taskstatus_model','dash_model','Center_model','SubSource_model','Kyc_model','Education_model','SocialProfile_model','Closefemily_model','form_model','report_model','Configuration_Model','Doctor_model','rule_model')
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
        $data['all_stage_lists'] = $this->Leads_Model->get_leadstage_list_byprocess1($this->session->process,array(1,2,3));
        $data['filterData'] = $this->Ticket_Model->get_filterData(1); 
        $data['aging_rule'] = $this->rule_model->get_rules(array(11));		       
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
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);   
        //$data['state_city_list'] = $this->location_model->get_city_by_state_id($data['details']->enquiry_state_id);
        //$data['state_city_list'] = $this->location_model->ecity_list();
        $compid = $this->session->userdata('companey_id');
        $data['allleads'] = $this->Leads_Model->get_leadList();
        if (!empty($data['details'])) {
            $lead_code = $data['details']->Enquery_id;
        }        
        $data['check_status'] = $this->Leads_Model->get_leadListDetailsby_code($lead_code);       
        $data['all_drop_lead'] = $this->Leads_Model->all_drop_lead();
        $data['products'] = $this->dash_model->get_user_product_list(); 
        $data['bank_list'] = $this->dash_model->get_bank_list(); 
        $data['allcountry_list'] = $this->Taskstatus_model->countrylist();
        $data['allstate_list'] = $this->Taskstatus_model->statelist();
        $data['allcity_list'] = $this->Taskstatus_model->citylist();
        $data['personel_list'] = $this->Taskstatus_model->peronellist($enquiry_id);        
        $data['kyc_doc_list'] = $this->Kyc_model->kyc_doc_list($lead_code);        
        $data['education_list'] = $this->Education_model->education_list($lead_code);
        $data['social_profile_list'] = $this->SocialProfile_model->social_profile_list($lead_code);        
        $data['close_femily_list'] = $this->Closefemily_model->close_femily_list($lead_code);
        $data['all_country_list'] = $this->location_model->country();
        $data['all_contact_list'] = $this->location_model->contact($enquiry_id);                
        $data['subsource_list'] = $this->Datasource_model->subsourcelist();
        $data['drops'] = $this->Leads_Model->get_drop_list();
        $data['name_prefix'] = $this->enquiry_model->name_prefix_list();
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['enquiry'] = $this->enquiry_model->enquiry_by_id($enquiry_id);
        $data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $enquiry_code = $data['enquiry']->Enquery_id;
        $phone_id = '91'.$data['enquiry']->phone;        
        $data['recent_tasks'] = $this->Task_Model->get_recent_taskbyID($enquiry_code);        
        $data['comment_details'] = $this->Leads_Model->comment_byId($enquiry_code);        
        $user_role    =   $this->session->user_role;
        $data['country_list'] = $this->location_model->productcountry();
        $data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        
        $data['institute_app_status'] = $this->Institute_model->get_institute_app_status();
        
          $data['prod_list'] = $this->Doctor_model->product_list($compid); 
        $data['amc_list'] = $this->Doctor_model->amc_list($compid,$enquiry_id); 
        $data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        $data['state_list'] = $this->location_model->estate_list();
        $data['city_list'] = $this->location_model->ecity_list();
        $data['product_contry'] = $this->location_model->productcountry();
        $data['get_message'] = $this->Message_models->get_chat($phone_id);
        $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        //$data['all_estage_lists'] = $this->Leads_Model->find_estage($enquiry_id);
        $data['all_estage_lists'] = $this->Leads_Model->find_estage($data['details']->product_id,3);
        
        $data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        $data['dynamic_field']  = $this->enquiry_model->get_dyn_fld($enquiry_id);
        
        $data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id,$data['details']->product_id,0);
        $this->load->helper('custom_form_helper');
        $data['all_description_lists']    =   $this->Leads_Model->find_description();
        $data['leadid']     = $data['details']->Enquery_id;
        $data['compid']     =  $data['details']->comp_id;
        $data['ins_list'] = $this->location_model->get_ins_list($data['details']->Enquery_id);
		$data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id);
        $data['enquiry_id'] = $enquiry_id;
        $this->enquiry_model->make_enquiry_read($data['details']->Enquery_id);
        if ($this->session->companey_id=='67') { 
		$data['qualification_data'] = $this->enquiry_model->quali_data($data['details']->Enquery_id);
		$data['english_data'] = $this->enquiry_model->eng_data($data['details']->Enquery_id);
		}
        if ($this->session->companey_id=='67') { 
            $data['discipline'] = $this->location_model->find_discipline();
            $data['level'] = $this->location_model->find_level();
            $data['length'] = $this->location_model->find_length();
        }

         if (user_access('1000') || user_access('1001') || user_access('1002')) {
            $data['branch']=$this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();
            $data['CommercialInfo'] = $this->enquiry_model->getComInfo($enquiry_id);
            //fetch last entry
            $comm_data=$this->db->where(array('enquiry_id'=>$enquiry_id))->order_by('id',"desc")
            ->limit(1)->get('commercial_info');
            $data['commInfoCount']=$comm_data->num_rows();
            $data['commInfoData']=$comm_data->row();
        } 
        else
        {    $data['CommercialInfo'] =array();
             $data['branch'] =array();
            $data['commInfoCount']=0;
            $data['commInfoData']=array();
        }

		$data['course_list'] = $this->Leads_Model->get_course_list();
        $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');                  
        if (!empty($enquiry_separation) && !empty($_GET['stage'])) {                    
            $enquiry_separation = json_decode($enquiry_separation,true);
            $stage    =   $_GET['stage'];
            $data['title'] = $enquiry_separation[$stage]['title'];            
        }else{
            $data['title'] =display('client');
        }
        if($this->session->companey_id == 65 && $this->session->user_right == 215){
			$data['created_bylist'] = $this->User_model->read(147,false);
		}else{
			$data['created_bylist'] = $this->User_model->read();
		}  
        $data['content'] = $this->load->view('enquiry_details1', $data, true);
        $this->enquiry_model->assign_notification_update($enquiry_code);
        $this->load->view('layout/main_wrapper', $data);
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
            $this->Leads_Model->add_comment_for_events($this->lang->line("information_updated"), $enquiry_code);
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
            $data = array(
                'comp_id'=>$this->session->companey_id,
                'client_id' =>$clientid,
                'c_name' => $name,
                'emailid' => $email,
                'contact_number' => $mobile,
                'designation' => $this->input->post('designation'),
                'other_detail' => $otherdetails,
                'decision_maker' => $this->input->post('decision_maker')??0,
            );
           $enq = $this->Enquiry_Model->getEnquiry(array('enquiry_id'=>$clientid));
            $enquiry_code = $enq->row()->Enquery_id;
            $this->Leads_Model->add_comment_for_events($this->lang->line("new_contact_detail_added") , $enquiry_code);
            $insert_id = $this->Client_Model->clientContact($data);
            $this->session->set_flashdata('message', 'Client Contact Add Successfully');
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
    public function edit_contact()
    {
        if(user_role('1012')==true){
            
        }
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
        echo'<hr><div class="row" align="left" >
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
                  <input class="form-control" name="designation" placeholder="Designation"  type="text" value="'.$row->designation.'" required>
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
            $this->db->where(array('cc_id'=>$cc_id,'comp_id'=>$this->session->companey_id,'client_id'=>$this->input->post('client_id')));
            $this->db->update('tbl_client_contacts',$data);
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
        $data['enquiry_list'] = $this->Enquiry_Model->all_enqueries();
        // print_r($data['enquiry_list']);
        // die();
        $data['content'] = $this->load->view('enquiry/contacts', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function company_list($limit=30,$offset=0,$search=0,$sort='up')
    {
        if(user_role('1060')){}

            $search = empty($search)?'':$search;

        $this->load->model(array('Client_Model','Enquiry_Model'));
        $data['title'] = display('company_list');
       // $data['company_list'] = $this->Client_Model->getCompanyList()->result();
        $data['company_count'] = $this->Client_Model->getCompanyList($search,$this->session->companey_id,$this->session->user_id,$this->session->process,'count');

        $c =$data['company_list'] = $this->Client_Model->getCompanyList($search,$this->session->companey_id,$this->session->user_id,$this->session->process,'data',$limit,$offset,$sort)->result();
        //print_r($c);
        $data['limit'] =$limit;
        $data['offset'] = $offset;
        $data['search'] = $search;
        $data['sort']  =$sort;;
       //  $data['enquiry_list'] = $this->Enquiry_Model->all_enqueries();
        $data['content'] = $this->load->view('enquiry/company_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function company_details($company_name)
    {
        $this->load->model(array('Client_Model','enquiry_model'));
        $company = base64_decode($company_name);
        $data['title'] = 'Company Details';

    $c =$data['comp'] = $this->Client_Model->getCompanyList($company)->row();

    $deals =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'deals')->result();
   // print_r($c->enq_ids); exit();
    $deals = array_column((array)$deals, 'id');
    $data['specific_deals'] = count($deals)?implode(',', $deals):'-1';
    
    $visits =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'visits')->result();
    $visits = array_column((array)$visits, 'id');
    $data['specific_visits'] = count($visits)? implode(',', $visits):'-1';

    $contacts =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'contacts')->result();
    $contacts = array_column((array)$visits, 'cc_id');
    $contacts = count($contacts)?$contacts:array('-1');
    $data['contact_list'] = $this->Client_Model->getContactList($contacts);


    $data['specific_accounts'] = $c->enq_ids;
    $data['dfields']  = $this->enquiry_model->getformfield();
    $data['ticket_dfields'] = $this->enquiry_model->getformfield(2);

    $tickets =   $this->Client_Model->getCompanyData(explode(',',$c->enq_ids),'tickets')->result();
    $tickets = array_column((array)$tickets, 'id');
    $data['specific_tickets'] = count($tickets)? implode(',', $tickets):'-1';
    $data['company_name'] = $company;

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
            $this->Leads_Model->add_comment_for_events($this->lang->line("new_contact_detail_added") , $enquiry_code);
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
                $this->Leads_Model->add_comment_for_events( $this->lang->line("circuit_sheet_created") , $enquiry->Enquery_id);
                redirect(base_url() . 'boq-add/' . base64_encode($enquiry->Enquery_id));
            } elseif ($lead_stage == 8) {
                $this->Leads_Model->add_comment_for_events($this->lang->line("po_attached"), $enquiry->Enquery_id);
                redirect(base_url() . 'enquiry/attach_po/' . base64_encode($enquiry->Enquery_id));
            } else {
                $this->Leads_Model->add_comment_for_events($this->lang->line("enquiry_moved"), $enquiry->Enquery_id);
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
           $this->Leads_Model->add_comment_for_events($this->lang->line("enquiry_moved"), $enquiry->Enquery_id);
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
            $firstname = $this->input->post('enquirername');
            $lastname = $this->input->post('lastname');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobileno');
            $other_phone = $this->input->post('other_no[]');
            $lead_source = $this->input->post('lead_source');
            $subsource = $this->input->post('subsource');
            $enquiry = $this->input->post('enquiry');
            $en_comments = $this->input->post('en_comments');
            $city_id = $this->input->post('city_id');
            $state_id = $this->input->post('state_id');
            
            $address = $this->input->post('address');
            $pin_code = $this->input->post('pin_code');
            $company = $this->input->post('company');
            
            if($this->input->post('country_id')){
                 $country_id = implode(',',$this->input->post('country_id'));
            }else{
                $country_id = '';
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
            $this->db->set('phone', $mobile);
            $this->db->set('other_phone', $other_phone);
            $this->db->set('country_id', $country_id);            
            $this->db->set('email', $email);
            $this->db->set('name_prefix', $name_prefix);
            $this->db->set('name', $firstname);
            $this->db->set('enquiry_source', $lead_source);
            $this->db->set('sub_source', $subsource);
            $this->db->set('address', $address);
            $this->db->set('pin_code', $pin_code);
            $this->db->set('company', $company);
            $this->db->set('enquiry', $enquiry);
            $this->db->set('lastname', $lastname);
            $this->db->set('state_id', $state_id);
            $this->db->set('city_id', $city_id);
			$this->db->set('enquiry_subsource',$this->input->post('sub_source'));
            $this->db->set('product_id', $process_id);			
            $this->db->where('enquiry_id', $enquiry_id);            
            $this->db->update('enquiry');  
            $this->load->model('rule_model');
            $this->rule_model->execute_rules($en_comments,array(1,2));
            
            $type = $enqarr->status;                
            if($type == 1){                 
                $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('enquery_updated'), $en_comments);                    
            }else if($type == 2){                   
                $comment_id  = $this->Leads_Model->add_comment_for_events($this->lang->line('lead_updated'), $en_comments);                   
            }else if($type == 3){
                $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('client_updated'), $en_comments);
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
        if($type == 1){                 
            $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('enquery_updated'), $en_comments);                    
        }else if($type == 2){                   
             $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('lead_updated'), $en_comments);                   
        }else if($type == 3){
             $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('client_updated'), $en_comments);
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

    public function update_dynamic_query()
    {
        $this->load->model('Enquiry_model');

         $res = $this->Enquiry_model->update_dynamic_query();
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
           $this->Leads_Model->add_comment_for_events( $this->lang->line('Personel Details Inserted') , $unique_number);
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
             $this->Leads_Model->add_comment_for_events($this->lang->line("personel_details_updated") , $unique_number);
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
                    $this->Leads_Model->add_comment_for_events($this->lang->line('client_assigned'), $enquiry_code);
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
//     $ag_id = $this->input->post('ide');
//     $ddata =  $this->db->where('id',$ag_id)->get('tbl_aggriment')->row();

//     $this->db->from('enquiry');
//                     $this->db->where('enquiry_id',$ddata->enquiry_id);
//                     $q= $this->db->get()->row();
//     $enq_id =$q->Enquery_id;
//     $phone =$q->phone;
    
//     $this->db->where('s_phoneno',$phone);
//     $this->db->from('tbl_admin');
//                     $q1= $this->db->get()->row();
   
// if(!empty($q1)){
//     $noti_id =$q1->pk_i_admin_id;
//    }else{
// 	$noti_id = $this->session->user_id;
//    }	
// if(!empty($_FILES['file']['name'])){
// 				$this->load->library("aws");
// 				$_FILES['userfile']['name']= $_FILES['file']['name'];
// 				$_FILES['userfile']['type']= $_FILES['file']['type'];
// 				$_FILES['userfile']['tmp_name']= $_FILES['file']['tmp_name'];
// 				$_FILES['userfile']['error']= $_FILES['file']['error'];
// 				$_FILES['userfile']['size']= $_FILES['file']['size'];    
				
// 				$image=$_FILES['userfile']['name'];
// 				$path=  "uploads/agrmnt/".$image;
//         		$ret = move_uploaded_file($_FILES['userfile']['tmp_name'] ,$path);
// 			if($ret){
//                 $this->aws->upload("",$path);	
//                 								}
//                 $this->db->set('file',$path);
//                 $this->db->where('enq_id', $enq_id);
//                 $this->db->update('tbl_aggriment');	
// 			}
// 			$assign_data_noti[]=array('create_by'=> $noti_id,
//                         'subject'=>'Agrrement Uploded',
//                         'query_id'=>$enq_id,
//                         'task_date'=>date('d-m-Y'),
//                         'task_time'=>date('H:i:s')
//                         );
//            $this->db->insert_batch('query_response',$assign_data_noti);
//            $this->load->library('user_agent');
           
//            if($this->input->post('redirect_url')){
//             redirect($this->input->post('redirect_url')); //updateclient                
//            }else{
//              redirect($this->agent->referrer()); //updateclient
//            }
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
			if($ret){										$this->aws->upload("",$path);									}
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
       // print_r($data['contact_list']->result_array()); exit();
        $data['all_enquiry'] = $this->Enquiry_Model->all_enqueries('1,2,3');
        $data['company_list'] = $this->Client_Model->getCompanyList()->result();
        $data['content'] = $this->load->view('enquiry/visits', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function visit_details()
    {
         if(user_role('1020') || user_role('1021') || user_role('1022')){
        }
        $id=$this->uri->segment('3');
    	$visitdata= $this->db->where('visit_id',$id)->join('tbl_visit','tbl_visit.id=visit_details.visit_id')->get('visit_details');
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
    public function report(){
        if(user_role('1020') || user_role('1021') || user_role('1022')){
        }
        $this->load->model('report_model');
        $data['title'] = 'Visit Report';
       $data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));					
       $content = ''; 		
        if ($_POST) {			
           $from		=	date("Y-m-d", strtotime($this->input->post('date_from')));
           $to		=	date("Y-m-d", strtotime($this->input->post('date_to')));
            $employee	=	$this->input->post('employee');			
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
    public function visit_expense()
    { 


    }
   public function add_expense()
   {
                $visit_id= $this->input->post('visit_id');
                $vd_id= $this->input->post('id');
                $finalfilename='';
                $uid=$this->session->user_id;
                $comp_id=$this->session->companey_id;
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
                        $data=['type'=>2,
                               'amount'=>$amount,
                               'visit_id'=>$visit_id,
                               'created_by'=>$uid,
                               'expense'=>$expense,
                               'file'=>$finalfilename,
                               'comp_id'=>$comp_id,
                               ];
                    $this->db->insert('tbl_expense',$data);

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
        print_r($_POST['exp_ids']);
    foreach ($_POST['exp_ids'] as $key => $value) {
        // echo $value;
        $data=['uid'=>$user_id,'remarks'=>$_POST['remarks'],'approve_status'=>$_POST['status']];
        $this->db->where(array('comp_id'=>$comp_id,'visit_id'=>$value))->update('tbl_expense',$data);
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
    foreach ($_POST['exp_ids'] as $key => $value) {
        // echo $value;
        $data=['uid'=>$user_id,'remarks'=>$_POST['remarks'],'approve_status'=>$_POST['status']];
        // print_r($data);
        $this->db->where(array('comp_id'=>$comp_id,'id'=>$visit_id))->update('tbl_expense',$data);
    }
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
       
        $data['all_enquiry'] = $this->Enquiry_Model->all_enqueries('2,3');

        $data['branch']=$this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();

        $data['company_list'] = $this->Client_Model->getCompanyList()->result();
        //fetch last entry
        $comm_data=$this->db->where(array('comp_id'=>$this->session->companey_id))->order_by('id',"desc")
        ->limit(1)->get('commercial_info');
        $data['commInfoCount']=$comm_data->num_rows();
        $data['commInfoData']=$comm_data->row();

        $data['content'] = $this->load->view('enquiry/deals', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function short_dashboard_count_deals()
    {
        //print_r($_POST); exit();
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

            $where.=" (info.creation_date >='".$_POST['date_from']."' and info.creation_date <='".$_POST['date_to']."' ) ";
            $and =1;
        }
        else if(!empty($_POST['date_from']))
        {
             if($and)
                $where.=" and ";

            $where.=" (info.creation_date >='".$_POST['date_from']."' ) ";
            $and =1;
        }
        else if(!empty($_POST['date_to']))
        {
              if($and)
                $where.=" and ";

            $where.=" (info.creation_date <='".$_POST['date_to']."' ) ";
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
        //print_r($_POST); exit(); 
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
        $i=1;
        foreach ($result as $res)
        {
            $sub = array();

            $sub[] = $i++;

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
                    $sub[] = '<a href="'.$url.'">'.$res->enq_name.'</a>'??'NA';
            }
           
            if($colsall || in_array(2,$cols))
                $sub[] = trim($res->company)??'NA';

            if($colsall || in_array(3,$cols))
                $sub[] = trim($res->designation)??'NA';
            
            if($colsall || in_array(4,$cols))
                $sub[] = trim($res->c_name)??'NA';

            if($colsall || in_array(5,$cols))
                $sub[] = trim($res->contact_number)?$res->contact_number:'NA';

            if($colsall || in_array(6,$cols))
                $sub[] = $res->emailid??'NA';

            if($colsall || in_array(7,$cols))
                $sub[] = $res->decision_maker?'Yes':'No';

            if($colsall || in_array(8,$cols))
            $sub[] = trim($res->other_detail)?$res->other_detail:'NA';

            if($colsall || in_array(9,$cols))
                $sub[] = $res->created_at??'NA';

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
            }
            $data[] =$sub;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$this->contacts_datatable_model->countAll(),
            "recordsFiltered" => $this->contacts_datatable_model->countFiltered($_POST),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function company_load_data()
    {   //not in used
        
        //print_r($_POST); exit(); 
        // $this->load->model('company_datatable_model');
        // $result = $this->company_datatable_model->getRows($_POST);
        // //echo $this->db->last_query(); exit();
        // //print_r($result); exit();
        // $colsall  = true;
        // $cols = array();
        // // if(!empty($_POST['allow_cols']))
        // // {
        // //     $cols  = explode(',',$_POST['allow_cols']);
        // //     $colsall = false;
        // // }
        // //print_r($cols); exit();
        // $data = array();
        // $i=1;
        // foreach ($result as $res)
        // {
        //     $sub = array();

        //     $sub[] = $i++;
           
        //     if($colsall || in_array(2,$cols))
        //         $sub[] = trim($res->company)??'NA';

        //     if($colsall || in_array(2,$cols))
        //         $sub[] = trim($res->contacts_num)??'NA';

        //     $data[] =$sub;
        // }

        // $output = array(
        //     "draw" => $_POST['draw'],
        //     "recordsTotal" =>$this->company_datatable_model->countAll(),
        //     "recordsFiltered" => $this->company_datatable_model->countFiltered($_POST),
        //     "data" => $data,
        // );
        // echo json_encode($output);
    }


    public function commercial_info($enquiry_id)
    {
        $this->load->model(array('Client_Model','Leads_Model','Branch_model'));

        $data['title'] = 'Add Deal';
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
        $data['branch'] = $this->Branch_model->branch_list()->result();
        
        $dis= $this->db->select('d.discount')
                                        ->from('discount_matrix d')
                                        ->join('tbl_admin a','a.discount_id=d.id','left')
                                        ->where('a.pk_i_admin_id='.$this->session->user_id)
                                        ->get()->row();
        $data['max_discount'] = !empty($dis)?$dis->discount:100;
        $data['content'] = $this->load->view('enquiry/add_deals', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function gen_table()
    {
        if(!empty($_POST))
        {
            $this->load->model('Branch_model');

            $booking_type = $this->input->post('booking_type');
            $business_type = $this->input->post('business_type');
            $bbranch = $this->input->post('bbranch');
            $dbranch = $this->input->post('dbranch');
            $btype = $this->input->post('btype');
            $dtype = $this->input->post('dtype');
            $enquiry_id = $this->input->post('enq_for');
            $deal_id= $this->input->post('deal_id')??0;
            $deal_data = $this->Branch_model->get_deal($deal_id);
            if($btype=='zone')
            {
                $x = implode(',',$bbranch);
                //echo $x;exit();
                $fetch_list  = $this->Branch_model->common_list(" branch.zone IN ($x) ")->result();
                $bbranch = array_column($fetch_list,'branch_id');
                //print_r($bbranch);exit();
            }

            if($dtype=='zone')
            {
                $x = implode(',',$dbranch);
                //echo $x;exit();
                $fetch_list  = $this->Branch_model->common_list(" branch.zone IN ($x) ")->result();
                $dbranch = array_column($fetch_list,'branch_id');
                //print_r($bbranch);exit();
            }

            if(empty($bbranch))
                $bbranch = array();
            if(empty($dbranch))
                $dbranch = array();  

            $query = $this->Branch_model->from_to_table($bbranch,$dbranch)->result();
            if(!empty($query))
            {
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
                            'Consignor',//15
                            'NA',//16
                            '15',//17
                            '25',//18
                            '',//19
                            '750',//20
                            '',//21
                            '',//22
                    );
                    $oc[19] = array(array('from'=>'','to'=>'','charge'=>'','unit'=>'per_kg'));
                    $oc[20] = array(array('from'=>'','to'=>'','charge'=>'','unit'=>'per_kg'));
                    $oc['rate_type'] = 'KG';
                    if(!empty($deal_data))
                    {
                        $oc =(array)json_decode($deal_data->other_charges);
                        if(empty($oc[22]))
                            $oc[22]='';
                    }
                    
                    

                echo'
                <form id="data_table">
                <input name="booking_type" type="hidden" value="'.$booking_type.'">
                <input name="business_type" type="hidden" value="'.$business_type.'">
                <input name="btype" type="hidden" value="'.$btype.'">
                <input name="dtype" type="hidden" value="'.$dtype.'">
                <input name="bbranch" type="hidden" value="'.implode($bbranch).'">
                <input name="dbranch" type="hidden" value="'.implode($dbranch).'">
                <input name="enquiry_id" type="hidden" value="'.$enquiry_id.'">
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
                    {echo'<th style="width:100px;"> Rate/<select name="oc[rate_type]" class="exclude_select2" style=" background:#d9edf7; border:0px;">
                                    <option '.($oc['rate_type']=='KG'?'selected':'').'>KG</option>
                                    <option '.($oc['rate_type']=='Box'?'selected':'').'>Box</option>
                                </select></th>
                        <th style="width:95px">Discount <label class="badge pull-right" onclick="rep_discount()">R</label></th>
                        <th style="width:98px">Paymode <label class="badge pull-right" onclick="rep_paymode()">R</label></th>
                        <th style="width:100px">Insurance <label class="badge pull-right" onclick="rep_insurance()">R</label></th>
                        <th>Expected Tonnage <label class="badge pull-right" onclick="rep_eton()">R</label></th>
                        <th>Expected Amount</th>
                        <th>Potential Tonnage <label class="badge pull-right" onclick="rep_pton()">R</label></th>
                        <th>Potential Amount</th>';
                    }
                    else
                    {
                        echo'<th>Vehicle Type <label class="badge pull-right" onclick="rep_vtype()">R</label></th>
                            <th>Carrying Capacity<label class="badge pull-right" onclick="rep_capacity()">R</label></th>
                            <th>Invoice Value <label class="badge pull-right" onclick="rep_invoice()">R</label></th>';
                        $vehicles = $this->Branch_model->get_vehicles()->result();
                    }
                    echo'</thead>
                    <tbody>
                ';
                
                $i=1;
                foreach ($query as $key => $row)
                {
                    $rate=$row->rate;
                    $discount=0;
                    $paymode='';
                    $insurance='';
                    $eton=0;
                    $eamnt=0;
                    $pton=0;
                    $pamnt=0;
                    $vid=0;
                    $chk = $this->db->where('deal_id',$deal_id)
                                        ->where('booking_branch',$row->booking_branch)
                                        ->where('delivery_branch',$row->delivery_branch)
                                        ->get('deal_data')->row();
                    if(!empty($chk))
                    {
                        $rate= $chk->rate;
                        $discount=$chk->discount;
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

                    echo'<tr>
                            <td>'.$i++.'</td>
                            <td><input type="hidden" name="bid['.$row->id.']" value="'.$row->booking_branch.'">'.$row->bbranch.'<br>
                                <small>('.ucwords($row->btype).')</small>
                            </td>
                            <td><input type="hidden" name="did['.$row->id.']" value="'.$row->delivery_branch.'">'.$row->dbranch.'<br>
                                <small>('.ucwords($row->dtype).')</small>
                            </td>';
                    if($booking_type=='sundry')
                    {
                        echo'<td><input type="number" name="rate['.$row->id.']" data-id="'.$row->id.'" value="'.$row->rate.'"></td>
                            <td><input type="number" class="discount_ip" name="discount['.$row->id.']" data-id="'.$row->id.'" value="'.$discount.'"></td>
                            <td><select name="paymode['.$row->id.']" data-id="'.$row->id.'"  class="paymode_ip">
                                <option value="paid" '.($paymode=='paid'?'selected':'').'>Paid</option>
                                <option value="topay" '.($paymode=='topay'?'selected':'').'>To-Pay</option>
                                <option value="tbb" '.($paymode=='tbb'?'selected':'').'>TBB</option>
                                </select>
                            </td>
                            <td><select name="insurance['.$row->id.']" data-id="'.$row->id.'"  class="insurance_ip">
                                <option value="carrier" '.($insurance=='carrier'?'selected':'').'>Carrier</option>
                                <option value="owner" '.($insurance=='owner'?'selected':'').'>Owner risk</option>
                                </select>
                            </td>
                            <td><input type="number" name="eton['.$row->id.']" data-id="'.$row->id.'" value="'.$eton.'" class="eton_ip"></td>
                            <td><input type="text" name="eamnt['.$row->id.']" data-id="'.$row->id.'" value="'.$eamnt.'" readonly></td>
                            <td><input type="number" name="pton['.$row->id.']" data-id="'.$row->id.'" value="'.$pton.'" class="pton_ip"></td>
                            <td><input type="text" name="pamnt['.$row->id.']" data-id="'.$row->id.'" value="'.$pamnt.'" readonly></td>';
                    }
                    else
                    {

                        echo'<td>
                        <select name="vtype['.$row->id.']" class="vtype_ip">';
                        foreach($vehicles as $ve => $vehicle)
                            echo'<option value="'.$vehicle->vehicle_type_id.'" '.($vid==$vehicle->vehicle_type_id?'selected':'').'>'.$vehicle->type_name.'</option>';
                        echo'
                        </select></td>
                        <td><input name="capacity['.$row->id.']" type="number" class="capacity_ip" value="'.$capacity.'"></td>
                        <td><input name="invoice['.$row->id.']" type="number" class="invoice_ip" value="'.$invoice.'"></td>
                        
                        ';

                    }
                    
                    echo'</tr>';
                }
                echo'</tbody>
                </table>
                </div>
                </div>
                <div id="oc-box" style="padding:0px 10px;">
                 <div class="toggle-btn"  data-view="show"><i class="fa fa-chevron-up"></i></div>
                 <label>Other Charges:</label>
                 <div class="t_box">
                    <table class="table table-bordered table-dark">
                    <thead>
                        <tr>
                            <th align="center">Name of Charges</th>
                            <th align="center">Amount (Rs.)</th>
                            <th align="center">Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>GC Charges</td>
                            <td><input name="oc[1]" value="'.$oc[1].'"></td>
                            <td>In Rs. Per GC</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr>
                            <td>Minimum Chargeable Wt</td>
                            <td><input name="oc[2]" value="'.$oc[2].'"></td>
                            <td>KGs, Whichever is Higher</td>
                        </tr>
                        <tr>
                            <td>Minimum Freight Value</td>
                            <td><input name="oc[3]" value="'.$oc[3].'"></td>
                            <td>In Rs.</td>
                        </tr>
                        <tr>
                            <td>CFT factor</td>
                            <td><input name="oc[4]" value="'.$oc[4].'"></td>
                            <td>KG.</td>
                        </tr>
                        <tr>
                            <td>Hamali Charges</td>
                            <td><input name="oc[5]" value="'.$oc[5].'"></td>
                            <td>Per Kg.</td>
                        </tr>';
                    }
                    echo'<tr>
                            <td>FOV Charges (owner risk)</td>
                            <td><input name="oc[6]" value="'.$oc[6].'"></td>
                            <td>% of Invoice Value</td>
                        </tr>
                        <tr>
                            <td>FOV Charges (Carrier risk)</td>
                            <td><input name="oc[7]" value="'.$oc[7].'"></td>
                            <td>% of Invoice Value</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr>
                            <td>AOC Charges</td>
                            <td><input name="oc[8]" value="'.$oc[8].'"></td>
                            <td>% of Total Freight</td>
                        </tr>
                        <tr>
                            <td>COD/DOD Charges</td>
                            <td><input name="oc[9]" value="'.$oc[9].'"></td>
                            <td>Per GC</td>
                        </tr>
                        <tr>
                            <td>DACC Charges</td>
                            <td><input name="oc[10]" value="'.$oc[10].'"></td>
                            <td>Per GC</td>
                        </tr>';
                    }
                    echo'<tr>
                            <td>Other (Please Specify)</td>
                            <td><input name="oc[11]" value="'.$oc[11].'"></td>
                            <td>At Actual</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr>
                            <td colspan="3" style="font-weight:bold">CR Charges to be Paid By Consignor <input type="radio" name="oc[12]" value="Consignor" '.($oc[12]=='Consignor'?'checked':'').'>   Consignee  <input type="radio" name="oc[12]" value="Consignee" '.($oc[12]=='Consignee'?'checked':'').'> </td>
                        </tr>
                        <tr>
                            <td>Demurrage charges</td>
                            <td><input name="oc[13]" value="'.$oc[13].'"></td>
                            <td>Per KG on Per day basis</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight:bold">Demurrage Charges to be Paid By Consignor <input type="radio" name="oc[14]" value="Consignor" '.($oc[14]=='Consignor'?'checked':'').'>   Consignee  <input type="radio" name="oc[14]" value="Consignee" '.($oc[14]=='Consignee'?'checked':'').'> </td>
                        </tr>';
                    }
                    echo'<tr>
                            <td>Loading/Unloading Charges/Union Charges</td>
                            <td><select name="oc[15]">
                                <option '.($oc[15]=='Consignee'?'selected':'').'>Consignee</option>
                                <option '.($oc[15]=='Consignor'?'selected':'').'>Consignor</option>
                                </select></td>
                            <td>Per Kg / Box</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                    echo'<tr>
                            <td>GI Charges</td>
                            <td><input name="oc[16]" value="'.$oc[16].'"></td>
                            <td>In Rs. per GC</td>
                        </tr>
                        <tr>
                            <td>Dynamic Fuel Surcharge in %</td>
                            <td><input name="oc[17]" value="'.$oc[17].'"></td>
                            <td>% of basic freight</td>
                        </tr>';
                    }
                    echo'<tr>
                            <td>E-way bill charge</td>
                            <td><input name="oc[18]" value="'.$oc[18].'"></td>
                            <td>In Rs. Per GC</td>
                        </tr>';
                    if($booking_type=='sundry')
                    {
                        $_door = (array)$oc[19];
                        $_first = (array)$oc[19][0];
                        
                    echo'<tr>
                            <td>Door Collection Charges</td>
                            <td id="door_box">
                                <button type="button" onclick="add_door()" class="btn btn-xs btn-primary pull-right" style="max-width:8%;display:inline-block">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div id="door_sample">
                                    <input name="oc[19][from][]" style="width:20%" placeholder="From" value="'.$_first['from'].'">
                                        &#8211;
                                    <input name="oc[19][to][]" style="width:20%" placeholder="To" value="'.$_first['to'].'">
                                    <input name="oc[19][charge][]" value="'.$_first['charge'].'" style="width:20%" placeholder="Charge">
                                    <div class="door_unit_sel" style="width:28%; display:inline-block">
                                        <select name="oc[19][unit][]">
                                            <option value="per_kg" '.($_first['unit']=='per_kg'?'selected':'').'>per KG</option>
                                            <option value="per_gc" '.($_first['unit']=='per_gc'?'selected':'').'>per GC</option>
                                            <option value="per_trip" '.($_first['unit']=='per_trip'?'selected':'').'>per Trips</option>
                                        </select>
                                    </div>
                                </div>';

                            foreach ($_door as $key =>$door_row)
                            {
                                $door_row = (array)$_door[$key];
                                if($key==0)continue;
                                echo'<div>
                                    <input name="oc[19][from][]" style="width:20%" placeholder="From" value="'.$door_row['from'].'">
                                        &#8211;
                                    <input name="oc[19][to][]" style="width:20%" placeholder="To" value="'.$door_row['to'].'">
                                    <input name="oc[19][charge][]" value="'.$door_row['charge'].'" style="width:20%" placeholder="Charge">
                                    <div style="width:28%; display:inline-block">
                                        <select name="oc[19][unit][]">
                                            <option value="per_kg" '.($door_row['unit']=='per_kg'?'selected':'').'>per KG</option>
                                            <option value="per_gc" '.($door_row['unit']=='per_gc'?'selected':'').'>per GC</option>
                                            <option value="per_trip" '.($door_row['unit']=='per_trip'?'selected':'').'>per Trips</option>
                                        </select>
                                    </div>
                                    <button type="button" onclick="remove_door(this)" class="btn btn-xs btn-danger pull-right" style="max-width:8%;display:inline-block">
                                    <i class="fa fa-times"></i>
                                </button>
                                </div>';
                            }
                    $_door = (array)$oc[20];
                    $_first = (array)$oc[20][0];
                    echo'</td>
                            <td>Upto 3 MT and above free</td>
                        </tr>
                        <tr>
                            <td>Last Mile  Delivery charges</td>
                            <td id="mile_box">

                            <button type="button" onclick="add_mile_del()" class="btn btn-xs btn-primary pull-right" style="max-width:8%;display:inline-block">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div id="mile_sample">
                                    <input name="oc[20][from][]" style="width:20%" placeholder="From" value="'.$_first['from'].'">
                                        &#8211;
                                    <input name="oc[20][to][]" style="width:20%" placeholder="To" value="'.$_first['to'].'">
                                    <input name="oc[20][charge][]" value="'.$_first['charge'].'" style="width:20%" placeholder="Charge">
                                    <div class="mile_unit_sel" style="width:28%; display:inline-block">
                                        <select name="oc[20][unit][]">
                                            <option value="per_kg" '.($_first['unit']=='per_kg'?'selected':'').'>per KG</option>
                                            <option value="per_gc" '.($_first['unit']=='per_gc'?'selected':'').'>per GC</option>
                                            <option value="per_trip" '.($_first['unit']=='per_trip'?'selected':'').'>per Trips</option>
                                        </select>
                                    </div>
                                </div>';

                            foreach ($_door as $key =>$door_row)
                            {
                                $door_row = (array)$_door[$key];
                                if($key==0)continue;
                                echo'<div>
                                    <input name="oc[19][from][]" style="width:20%" placeholder="From" value="'.$door_row['from'].'">
                                        &#8211;
                                    <input name="oc[19][to][]" style="width:20%" placeholder="To" value="'.$door_row['to'].'">
                                    <input name="oc[19][charge][]" value="'.$door_row['charge'].'" style="width:20%" placeholder="Charge">
                                    <div style="width:28%; display:inline-block">
                                        <select name="oc[19][unit][]">
                                            <option value="per_kg" '.($door_row['unit']=='per_kg'?'selected':'').'>per KG</option>
                                            <option value="per_gc" '.($door_row['unit']=='per_gc'?'selected':'').'>per GC</option>
                                            <option value="per_trip" '.($door_row['unit']=='per_trip'?'selected':'').'>per Trips</option>
                                        </select>
                                    </div>
                                    <button type="button" onclick="remove_door(this)" class="btn btn-xs btn-danger pull-right" style="max-width:8%;display:inline-block">
                                    <i class="fa fa-times"></i>
                                </button>
                                </div>';
                            }


                echo'</td>
                            <td>Upto 3 MT and above free</td>
                        </tr>
                        <tr>
                            <td>Re Delivery charges</td>
                            <td colspan="2">Rs. 1200 per GC or actual expense whichever is higher</td>
                        </tr>
                        <tr>
                            <td>ODA Charges</td>
                            <td colspan="2">
                                <div style="width:49%; display:inline-block;">
                                    <input id="oda_value" name="oc[22]" value="'.$oc[22].'" placeholder="Charge">
                                </div>
                                <div style="width:49%; display:inline-block;">
                                    <input id="oda_distance" type="number" style="width:49%" placeholder="Distance ( In KM )" onkeyup="oda_cal()">
                                     <input id="oda_weight" type="number" style="width:49%" placeholder="Weight ( In KG )" onkeyup="oda_cal()">
                                </div>
                            </td>
                        </tr>';
                    }
                echo'</tbody>
                    </table>';
                if($booking_type=='sundry')
                {
                echo'<p>The average fuel price at the time of signing the contract is Rs <input type="number" name="oc[21]" value="'.$oc[21].'" style="width:60px!important;">. per Ltr.
                    </p>';
                }
            echo'</div>
                </div>
                <div style="padding:15px;">
                <button class="btn btn-success pull-right" type="submit"><i class="fa fa-save"></i> Save</button>
                </div>
                </form>
                ';
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
        $this->load->model('Branch_model');

        if($this->input->post('booking_type')=='sundry')
        {
            $data = array();
            $door = $_POST['oc'][19];

            foreach ($door['from'] as $key => $val)
            {
                $data[$key]['from'] = $door['from'][$key];
                $data[$key]['to'] = $door['to'][$key];
                $data[$key]['charge'] = $door['charge'][$key];
                $data[$key]['unit'] = $door['unit'][$key]; 
            }
            $_POST['oc'][19] = $data;
            
            $data = array();
            $door = $_POST['oc'][20];

            foreach ($door['from'] as $key => $val)
            {
                $data[$key]['from'] = $door['from'][$key];
                $data[$key]['to'] = $door['to'][$key];
                $data[$key]['charge'] = $door['charge'][$key];
                $data[$key]['unit'] = $door['unit'][$key]; 
            }
        $_POST['oc'][20] = $data;
        }

        $oc = json_encode($this->input->post('oc'));
        $deal_id = $this->input->post('info_id');
        $deal = array(
                    'enquiry_id'=>$this->input->post('enquiry_id'),
                    'booking_type'=>$this->input->post('booking_type'),
                    'business_type'=>$this->input->post('business_type'),
                    'btype'=>$this->input->post('btype'),
                    'dtype'=>$this->input->post('dtype'),
                    'createdby'=>$this->session->user_id,
                    'comp_id'=>$this->session->companey_id,
                    'other_charges'=>$oc,
                    'status'=>'0',

                    );

        if(!empty($deal_id))
        {
            unset($deal['status']);
            $this->db->update('commercial_info',$deal);
            $this->db->where('deal_id',$deal_id)->delete('deal_data');
        }
        else
        {
            $deal_id = $this->Branch_model->add_deal($deal);
        }
        
        if($deal['booking_type']=='sundry')
            $hook = $this->input->post('rate');
        else
            $hook = $this->input->post('vtype');  

        foreach($hook as $link_id => $rate)
        {
            $data  = array(
                        'deal_id'=>$deal_id,
                        'booking_branch'=>$this->input->post('bid['.$link_id.']'),
                        'delivery_branch'=>$this->input->post('did['.$link_id.']'),
                        'rate'=>$this->input->post('rate['.$link_id.']')??'',
                        'discount'=>$this->input->post('discount['.$link_id.']')??'',
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
            $this->Branch_model->add_deal_data($data);
        }

        echo'1';
    }

    public function edit_commercial_info($deal_id)
    {
         $this->load->model(array('Client_Model','Leads_Model','Branch_model'));

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

    public function create_agreement_pdf()
    {   
        $this->load->model(array('Branch_model','Leads_Model'));
        $user = $this->db->where('pk_i_admin_id',$this->session->user_id)->get('tbl_admin')->row();
        $deal_id = $this->input->post('deal_id');
        $zone = $this->input->post('zone_id');


    $agr =  $this->db->select('id as ref_no')->limit(1)->get('tbl_aggriment')->row();
    $ref_no = !empty($agr->ref_no)?$agr->ref_no+1:1;

        $_POST['ref_no'] = $ref_no;
        $_POST['edit'] = 1;
        $_POST['checkss'] = array();
        $deal   =    $this->Branch_model->get_deal($deal_id);
        $oc =(array) json_decode($deal->other_charges);

        $deal_data = $this->Branch_model->get_deal_data($deal_id);
        $bank = $this->Branch_model->bank_by_zone($zone);

        $enq = $this->db->select('e.*,r.region_name')
                        ->from('enquiry e')
                        ->join('tbl_region r','r.region_id=e.region_id','left')
                        ->where('e.enquiry_id',$deal->enquiry_id)
                        ->get()->row();



        $dynamic = $this->db->select('fvalue')
                                ->from('extra_enquery')
                                ->where('parent',$deal->enquiry_id)
                                ->where('input','4482')//id of dynaic field of dynamic field in v-trans 
                                ->get()->row();
        $enq_designation = !empty($dynamic)?$dynamic->fvalue:'';

        $_POST['customer_name'] = $enq->name_prefix.' '.$enq->name.' '.$enq->lastname;
        $_POST['region_name'] = $enq->region_name;
$_POST['ip'][46] =  $_POST['ms'] = $enq->company;
$_POST['ip'][47] = $_POST['reg_add'] = $enq->address;
        $_POST['account_number1'] = $bank->account_no;
        $_POST['ifsc1'] = $bank->ifsc;
        $_POST['branch1'] = $bank->bank_branch;

 $_POST['ip'][88] = $_POST['ip'][40] =   $_POST['ip'][10] = $_POST['name1'] = $user->s_display_name.' '.$user->last_name;
$_POST['ip'][90] =    $_POST['ip'][42] =   $_POST['ip'][12] = $_POST['designation1'] = $user->designation;


$_POST['ip'][89] =$_POST['ip'][51] =  $_POST['ip'][41] = $_POST['ip'][11] = $_POST['name2'] = $enq->name_prefix.' '.$enq->name.' '.$enq->lastname;
$_POST['ip'][91] =    $_POST['ip'][43] = $_POST['ip'][13] = $_POST['designation2'] = $enq_designation;

        $_POST['ip'][15] = $enq->name_prefix.' '.$enq->name.' '.$enq->lastname;
        $_POST['ip'][14] = $deal->booking_type;

        $_POST['ip'][16] = $oc['1'];
        $_POST['ip'][17] = $oc['2'];
        $_POST['ip'][18] = $oc['3'];
        $_POST['ip'][19] = $oc['4'];
        $_POST['ip'][20] = $oc['5'];
        $_POST['ip'][21] = $oc['6'];
        $_POST['ip'][22] = $oc['7'];
        $_POST['ip'][23] = $oc['8'];
        $_POST['ip'][24] = $oc['9'];
        $_POST['ip'][25] = $oc['17'];
        $_POST['ip'][26] = $oc['10'];
        $_POST['ip'][27] = $oc['18'];
        // $_POST['ip'][28] = $oc['13'];
        // $_POST['ip'][29] = $oc['14'];
        $_POST['ip'][30] = !empty($oc['11'])?$oc['11']:' ';
        $_POST['ip'][31] = $oc[14];
        $_POST['ip'][36] = $oc[12];
        $_POST['ip'][39] = $oc[16];
        
        $_POST['ip'][45] = $oc[21];

        $_POST['ip'][50] = $enq->email;

    

        echo $this->load->view('aggrement/input-vtrans',array(),TRUE);
        
    }

    function new_vtrans()
    {
        $this->load->view('aggrement/new-input-vtrans');
    }
}