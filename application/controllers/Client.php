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
                array('Leads_Model','common_model','enquiry_model', 'dashboard_model', 'Task_Model', 'User_model', 'location_model', 'Message_models','Institute_model','Datasource_model','Taskstatus_model','dash_model','Center_model','SubSource_model','Kyc_model','Education_model','SocialProfile_model','Closefemily_model','form_model','report_model','Configuration_Model','Doctor_model')
                );
/*'dashboard_model', 'Installation_Model', 'Message_models','Institute_model','Datasource_model','Taskstatus_model','Center_model','SubSource_model','Kyc_model','Education_model','SocialProfile_model','Closefemily_model'*/

        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
    public function index1() {
        $aid = $this->session->userdata('user_id');
        $data['title'] = display('client_list');
        $data['user_list'] = $this->User_model->read();
        $data['clients'] = $this->Client_Model->get_Client_list();

        $data['all_clients'] = $this->Client_Model->all_clients();
        
        $data['all_created_today'] = $this->Client_Model->all_created_today();
        
        $data['all_Updated_today'] = $this->Client_Model->all_Updated_today();
        
        $data['all_Active_clients'] = $this->Client_Model->all_Active_clients();
        
        $data['all_InActive_clients'] = $this->Client_Model->all_InActive_clients();
        
        $data['all_clients_Tickets'] = $this->Client_Model->all_clients_Tickets();
        
        $data['state_list'] = $this->location_model->state_list();
        
        $data['customer_types'] = $this->enquiry_model->customers_types();
       
        
        $data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
        
        
        //echo '<pre>';print_r($data);die;
        $data['content'] = $this->load->view('clients', $data, true);
        $this->load->view('layout/main_wrapper', $data);
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
        $data['created_bylist'] = $this->User_model->user_list();
        $data['sourse'] = $this->report_model->all_source();
        $data['datasourse'] = $this->report_model->all_datasource(); 
	    $data['dfields']  = $this->enquiry_model-> getformfield();		 
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

        $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        
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
			
			/*
            echo "<pre>";

            print_r($data['all_clients']->result_array());
            */


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
        
        $data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id,$data['details']->product_id);
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
		$data['course_list'] = $this->Leads_Model->get_course_list();

        $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');                  
        if (!empty($enquiry_separation) && !empty($_GET['stage'])) {                    
            $enquiry_separation = json_decode($enquiry_separation,true);
            $stage    =   $_GET['stage'];
            $data['title'] = $enquiry_separation[$stage]['title'];            
        }else{
            $data['title'] = 'Client';        
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
        $this->load->model('Client_Model');
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
                'other_detail' => $otherdetails
            );
           // $clientDetails = $this->Client_Model->clientdetail_by_id($clientid);
            $enquiry_code = $this->input->post('enquiry_code');
            $this->Leads_Model->add_comment_for_events($this->lang->line("new_contact_detail_added") , $enquiry_code);
            $insert_id = $this->Client_Model->clientContact($data);
            $this->session->set_flashdata('message', 'Client Contact Add Successfully');
            redirect($this->agent->referrer());
        }
    }

    public function delete_contact()
    {
         $cc_id = $this->input->post('cc_id');
          $this->db->where(array('cc_id'=>$cc_id,'comp_id'=>$this->session->companey_id));
          $this->db->delete('tbl_client_contacts');
    }

    public function edit_contact()
    {
        if($this->input->post('task')=='view')
        {
            $cc_id = $this->input->post('cc_id');
            $this->load->model('Client_Model');

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
            <input type="hidden" name="task" value="save">
               <div class="form-group col-md-6">
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
               <div class="form-group col-md-12">
                  <label>Other Details</label>
                  <textarea class="form-control" name="otherdetails" rows="8">'.$row->other_detail.'</textarea>
               </div>
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Save" class="btn btn-primary"  name="Save">
                  </div>
               </div>
            </form>
            </div>';
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
                'other_detail' => $otherdetails
            );
            $this->db->where(array('cc_id'=>$cc_id,'comp_id'=>$this->session->companey_id,'client_id'=>$this->input->post('client_id')));
            $this->db->update('tbl_client_contacts',$data);
             redirect($this->agent->referrer());
        }
    }

    public function contacts()
    {
        $this->load->model('Client_Model');
        $data['title'] = display('Contacts');
        $data['contact_list'] = $this->Client_Model->getContactList();//contacts.*,enquiry.---
       // print_r($data['contact_list']->result_array()); exit();
        $data['content'] = $this->load->view('enquiry/contacts', $data, true);
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
            
            /*echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            exit();*/
            
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
            redirect($this->agent->referrer()); //updateclient
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

        // echo $enqid;exit();

         // $path = './uploads/amc_po/';
         //    if(!file_exists($path))
         //    {
         //      mkdir($path);
         //    }

         //     $config['upload_path']   = $path; 
         //    $config['allowed_types'] = 'jpeg|jpg|png|pdf'; 
         //    $config['max_size']      = 3486000; 
         //    $config['encrypt_name'] = true; 

         //    $this->load->library('upload', $config);

        // if ( !$this->upload->do_upload('po')) {

        //     // echo "string";exit();
        //    // $this->session->set_flashdata('message', "Upload Failed");
        //    $error = array('error' => $this->upload->display_errors());
        //     $this->session->set_flashdata('message',$error['error']);
        //    redirect(base_url('client/view/'.$enqid), 'refresh');
        //  }
        //  else{

        //     $fileData = $this->upload->data();
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
            redirect(base_url('enquiry/view/'.$enqid), 'refresh');
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
            redirect($this->agent->referrer()); //updateclient
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
    $enquiry_id = $this->input->post('ide');
    $this->db->from('enquiry');
                    $this->db->where('enquiry_id',$enquiry_id);
                    $q= $this->db->get()->row();
    $enq_id =$q->Enquery_id;
    $phone =$q->phone;
    
                    $this->db->where('s_phoneno',$phone);
    $this->db->from('tbl_admin');
                    $q1= $this->db->get()->row();
   $noti_id =$q1->pk_i_admin_id;
if(!empty($noti_id)){
	$noti_id = $noti_id;
   }else{
	$noti_id = $this->session->user_id;
   }	
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

            $this->db->set('file',$path);
            $this->db->where('enq_id', $enq_id);
            $this->db->update('tbl_aggriment');	
			}
			$assign_data_noti[]=array('create_by'=> $noti_id,
                        'subject'=>'Agrrement Uploded',
                        'query_id'=>$enq_id,
                        'task_date'=>date('d-m-Y'),
                        'task_time'=>date('H:i:s')
                        );
           $this->db->insert_batch('query_response',$assign_data_noti);
           $this->load->library('user_agent');
redirect($this->agent->referrer());
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

    /**************************Grenerate aggriment**************************/
    public function generate_aggrement() {
        $pdf_name = $this->input->post('agg_frmt');
        if($pdf_name=='BAA'){
            $data['title'] = 'Bangalore-Australia-Agreement';
            $this->load->helpers('dompdf');
            $viewfile = $this->load->view('aggrement/Bangalore-Australia-Agreement', $data, TRUE);
            pdf_create($viewfile,'Bangalore-Australia-Agreement'.$this->session->user_id);
            redirect($this->agent->referrer());
        }elseif($pdf_name=='vtrans'){
            $data['title'] = 'vtrans-vtrans';
            $this->load->helpers('dompdf');
            $viewfile = $this->load->view('aggrement/vtrans-Agreement', $data, TRUE);
            pdf_create($viewfile,'vtrans-Agreement'.$this->session->user_id);
            redirect($this->agent->referrer());
        }
    }
    /***********************end Generate aggriment*****************************/
}
