<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lead extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->helper('date'); 
        $this->load->model(
            array('Leads_Model', 'common_model', 'enquiry_model', 'dashboard_model', 'Task_Model', 'User_model', 'location_model', 'Message_models', 'Institute_model', 'Datasource_model', 'Taskstatus_model', 'dash_model', 'Center_model', 'SubSource_model', 'Kyc_model', 'Education_model', 'SocialProfile_model', 'Closefemily_model', 'Doctor_model', 'form_model', 'warehouse_model')
        );
        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
	
	///////////////// Department SECTION START////////////////////
    public function department() {
        $data['nav1'] = 'nav2';
        if (!empty($_POST)) {

            $dept_name = $this->input->post('dept_name');

            $data = array(
                'dept_name'   => $dept_name,
                'comp_id' => $this->session->userdata('companey_id'),
                'created_by' => $this->session->userdata('user_id'),
                'status' => '1'
            );

            $insert_id = $this->Leads_Model->dept_add($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Department Add Successfully');
            redirect('lead/department');
        }


        $data['all_department'] = $this->Leads_Model->dept_select();
        $data['title'] = 'Department master';
        $data['content'] = $this->load->view('setting/department_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
	
/////////////////////////////move to stage with deals/////////////////////
public function get_all_stage_deals() {
        $details='';
        $current_stg = $this->input->post('current_stg');
        $enq_id = $this->input->post('enq_id');
        $next_stg = $this->input->post('next_stg');		

        $all_deals_lists = $this->Leads_Model->dealstagelist($enq_id,$current_stg,$next_stg); 
		//print_r($all_deals_lists);exit;
        $details .= '
		            <input type="hidden" value="'.$current_stg.'" name="current_stg">
					<input type="hidden" value="'.$enq_id.'" name="enq_id">
					<input type="hidden" value="'.$next_stg.'" name="next_stg">';
					if(empty($all_deals_lists)){
					$details .= '<span style="color:red;">No Any Deal With Status Done! </span>';
					}
		            $details .= '<div class="form-group col-md-10">                 
                        <select class="form-control" id="deal_id" name="deal_id[]" multiple required>';                    
                              foreach ($all_deals_lists as $deals) { 
                         $details .= '<option value="'.$deals->id.'">'.$deals->quatation_number.'</option>';
                               }                                         
                    $details .=    '</select>
                    </div>';
                    
        echo $details;
    }
/////////////////////////////////move to stage with deals/////////////////////////////////////

    public function update_dept() {
        if (!empty($_POST)) {
            $dept_name = $this->input->post('dept_name');
            $dept_id = $this->input->post('dept_id');            
            $this->db->set('dept_name', $dept_name);
            $this->db->where('id', $dept_id);
            $this->db->update('tbl_department');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/department');
        }
    }

    public function delete_department($dept_id = null) {
        if ($this->Leads_Model->delete_dept($dept_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/department');
    }

    ///////////////// Department SECTION END////////////////////
	
	///////////////// Designation SECTION START////////////////////
    public function designation() {
        $data['nav1'] = 'nav2';
        if (!empty($_POST)) {

            $desi_name = $this->input->post('desi_name');

            $data = array(
                'desi_name'   => $desi_name,
                'comp_id' => $this->session->userdata('companey_id'),
                'created_by' => $this->session->userdata('user_id'),
                'status' => '1'
            );

            $insert_id = $this->Leads_Model->desi_add($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Designation Add Successfully');
            redirect('lead/designation');
        }


        $data['all_designation'] = $this->Leads_Model->desi_select();
        $data['title'] = 'Designation master';
        $data['content'] = $this->load->view('setting/designation_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function update_desi() {
        if (!empty($_POST)) {
            $desi_name = $this->input->post('desi_name');
            $desi_id = $this->input->post('desi_id');            
            $this->db->set('desi_name', $desi_name);
            $this->db->where('id', $desi_id);
            $this->db->update('tbl_designation');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/designation');
        }
    }

    public function delete_designation($desi_id = null) {
        if ($this->Leads_Model->delete_desi($desi_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/designation');
    }

    ///////////////// Designation SECTION END////////////////////
	
	///////////////// Industries SECTION START////////////////////
    public function industries() {
        $data['nav1'] = 'nav2';
        if (!empty($_POST)) {

            $indus_name = $this->input->post('indus_name');

            $data = array(
                'indus_name'   => $indus_name,
                'comp_id' => $this->session->userdata('companey_id'),
                'created_by' => $this->session->userdata('user_id'),
                'status' => '1'
            );

            $insert_id = $this->Leads_Model->indus_add($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Industries Add Successfully');
            redirect('lead/industries');
        }


        $data['all_industries'] = $this->Leads_Model->indus_select();
        $data['title'] = 'Industries master';
        $data['content'] = $this->load->view('setting/industries_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function update_industries() {
        if (!empty($_POST)) {
            $indus_name = $this->input->post('indus_name');
            $indus_id = $this->input->post('indus_id');            
            $this->db->set('indus_name', $indus_name);
            $this->db->where('id', $indus_id);
            $this->db->update('tbl_industries');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/industries');
        }
    }

    public function delete_industries($indus_id = null) {
        if ($this->Leads_Model->delete_indus($indus_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/industries');
    }

    ///////////////// Industries SECTION END////////////////////
	
    public function view_datasource_data($did)
    {
        
        $data['title'] = 'Datasource raw data';
        $data['datasource_id'] = $did;
        $data['raw_data']    =   $this->db->select('enquiry2.*,tbl_product.product_name')->from('enquiry2')
            ->join('tbl_product', 'tbl_product.sb_id=enquiry2.product_id', 'left')
            ->where('enquiry2.comp_id', $this->session->companey_id)
            ->where('enquiry2.datasource_id', $did)
            ->where('enquiry2.status!=', 3)
            ->get()->result_array();
        $data['content'] = $this->load->view('datasource/raw_data_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_raw_data()
    {

        $datasource_id    =   $this->input->post('datasource_id');
        $enq_id    =   $this->input->post('enq_id');
        if ($datasource_id) {
            $this->db->where_in('enquiry_id', $enq_id);
            $this->db->where('datasource_id', $datasource_id);
            $this->db->where('comp_id', $this->session->companey_id);
            $this->db->delete('enquiry2');
        }
        $this->session->set_flashdata('message', 'Records Deleted Successfully');
        redirect('lead/datasourcelist');
    }
    public function select_app_by_ins()
    {
        $course = $this->input->post('c_course');
        $lvl = $this->input->post('c_lvl');
        $length = $this->input->post('c_length');
        $disc = $this->input->post('c_disc');
        echo json_encode($this->Leads_Model->all_course($course, $lvl, $length, $disc));
        // echo $diesc;
    }
    public function index()
    {
        $aid = $this->session->userdata('user_id');
        $data['title'] = display('lead_list');
        $data['all_leadss'] = $this->Leads_Model->all_leadss();
        $data['user_list'] = $this->User_model->read();
        $data['all_user'] = $this->User_model->all_user();
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        $data['enquirys'] = $this->enquiry_model->read();
        $data['state_list'] = $this->location_model->state_list();
        $data['all_enquery'] = $this->Leads_Model->all_leadss();
        $data['all_drop'] = $this->Leads_Model->all_drop_lead();
        $data['all_active'] = $this->Leads_Model->all_Active_lead();
        $data['unassigned'] = $this->enquiry_model->unassigned();
        $data['all_leads'] = $this->Leads_Model->all_lead_toClient();
        $data['all_today_update'] = $this->Leads_Model->all_Updated_today();
        $data['all_creaed_today'] = $this->Leads_Model->all_created_today();
        $data['drops'] = $this->Leads_Model->get_drop_list();
        //$data['checked_enquiry'] = $this->enquiry_model->checked_enquiry();
        // $data['unchecked_enquiry'] = $this->enquiry_model->unchecked_enquiry();
        // $data['scheduled'] = $this->enquiry_model->scheduled();
        // $data['unscheduled'] = $this->enquiry_model->unscheduled();
        $data['customer_types'] = $this->enquiry_model->customers_types();
        $data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
        $data['content'] = $this->load->view('leads', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function enquery_detals_by_status($id = '')
    {
        if ($id > 0 and $id <= 20) {
            $serach_key = '';
        } else {
            $serach_key = explode('_', $id);
        }
        $data['title'] = display('lead_list');
        $data['all_user'] = $this->User_model->all_user();
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        if ($id == 1) {
            $data['all_active'] = $this->Leads_Model->all_created_today();
            /*echo "<pre>";
            print_r($data['all_active']->result_array());*/
        } elseif ($id == 2) {
            $data['all_active'] = $this->Leads_Model->all_Updated_today();
        } elseif ($id == 3) {
            $data['all_active'] = $this->Leads_Model->all_Active_lead();
        } elseif ($id == 4) {
            $data['all_active'] = $this->Leads_Model->all_lead_toClient();
        } elseif ($id == 5) {
            $data['all_active'] = $this->Leads_Model->all_drop_lead();
            /*     echo "<pre>";
            print_r($data['all_active']->result_array());
            echo $this->db->last_query();
            echo "</pre>";*/
        } elseif ($id == 6) {
            $data['all_active'] = $this->Leads_Model->all_leadss();
        } elseif ($id == 7) {
            //  $data['all_active'] = $this->enquiry_model->checked_enquiry();
        } elseif ($id == 8) {
            //  $data['all_active'] = $this->enquiry_model->unchecked_enquiry();
        } elseif ($id == 9) {
            // $data['all_active'] = $this->enquiry_model->scheduled();
        } elseif ($id == 10) {
            //  $data['all_active'] = $this->enquiry_model->unscheduled();
        } elseif (!empty($serach_key[1]) == 2) {
            $data['all_active'] = $this->enquiry_model->search_data($serach_key[0]);
        } else {
            $data['all_active'] = $this->Leads_Model->all_Active_lead();
        }
        $data['state_list'] = $this->location_model->state_list();
        $data['drops'] = $this->Leads_Model->get_drop_list();
        $this->load->view('lead_list', $data);
    }
    public function lead_details($enquiry_id = null)
    {
        
        $this->load->model('Client_Model');
        $data['title'] = display('Lead Details');
        $compid = $this->session->userdata('companey_id');
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);

        $data['allleads'] = $this->Leads_Model->get_leadList();
        if (!empty($data['details'])) {
            $lead_code = $data['details']->Enquery_id;
        }
        // $data['check_status'] = $this->Leads_Model->get_leadListDetailsby_code($lead_code);
        // $data['all_drop_lead'] = $this->Leads_Model->all_drop_lead();
        // $data['products'] = $this->dash_model->get_user_product_list();
        // $data['prod_list'] = $this->Doctor_model->product_list($compid);
        // $data['amc_list'] = $this->Doctor_model->amc_list($compid, $enquiry_id);
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
        $phone_id = '91' . $data['enquiry']->phone;
        $data['recent_tasks'] = $this->Task_Model->get_recent_taskbyID($enquiry_code);
        $data['comment_details'] = $this->Leads_Model->comment_byId($enquiry_code);
        $user_role    =   $this->session->user_role;
        // $data['country_list'] = $this->location_model->productcountry();
        // $data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        // $data['institute_app_status'] = $this->Institute_model->get_institute_app_status();

        // $data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        // $data['state_list'] = $this->location_model->estate_list();
        // $data['city_list'] = $this->location_model->ecity_list();
        // $data['product_contry'] = $this->location_model->productcountry();
        // $data['get_message'] = $this->Message_models->get_chat($phone_id);
        // $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        //$data['all_estage_lists'] = $this->Leads_Model->find_estage($enquiry_id);
        $data['all_estage_lists'] = $this->Leads_Model->find_estage($data['details']->product_id, 2);

        $data['all_description_lists']    =   $this->Leads_Model->find_description();
        // $data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        // $data['dynamic_field']  = $this->enquiry_model->get_dyn_fld($enquiry_id);
        // $data['ins_list'] = $this->location_model->get_ins_list($data['details']->Enquery_id);
        $data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id,base64_decode($this->uri->segment(4)));
        $data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id, $data['details']->product_id,0);
        $this->load->helper('custom_form_helper');
        $data['leadid']     = $data['details']->Enquery_id;
        $data['compid']     =  $data['details']->comp_id;
        $data['enquiry_id'] = $enquiry_id;
        
        // if(user_access('1000') || user_access('1001') || user_access('1002'))
        // {
        //     $data['branch']=$this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();
        //     $data['CommercialInfo'] = $this->enquiry_model->getComInfo($enquiry_id);
          
        //     $comm_data=$this->db->where(array('enquiry_id'=>$enquiry_id))->order_by('id',"desc")
        //     ->limit(1)->get('commercial_info');
        //     $data['commInfoCount']=$comm_data->num_rows();
        //     $data['commInfoData']=$comm_data->row();
        // } 
        // else
        // {    $data['CommercialInfo'] =array();
        //      $data['branch'] =array();
        //     $data['commInfoCount']=0;
        //     $data['commInfoData']=array();
        // }

//        $data['course_list'] = $this->Leads_Model->get_course_list();
  //      $this->enquiry_model->make_enquiry_read($data['details']->Enquery_id);
		$this->load->model('Branch_model');
		$data['branch_lists']=$this->Branch_model->all_sales_branch();
		$data['region_lists']=$this->Branch_model->all_sales_region();
        $enq['enquiry_id'] = $enquiry_id;
		$enq['all_designation'] = $this->Leads_Model->desi_select();
        $data['all_contact']= $this->Client_Model->getContactList()->result();
        $data['create_contact_form'] = $this->load->view('contacts/create_contact_form',$enq,true);
        $data['data_type'] = base64_decode($this->uri->segment(4));
        $data['content'] = $this->load->view('enquiry_details1', $data, true);
        $this->enquiry_model->assign_notification_update($enquiry_code);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function get_last_task_by_code()
    {
        $enq_code    =   $this->input->post('enq_code');
        $this->db->select('resp_id,task_time,DATE_FORMAT(STR_TO_DATE(task_date,"%d-%m-%Y"), "%Y-%m-%d") AS task_date,task_remark');
        $this->db->where('query_id', $enq_code);
        $this->db->order_by('resp_id', 'DESC');
        $this->db->limit(1);
        echo json_encode($this->db->get('query_response')->row_array());
        //echo $this->db->last_query();
    }
    public function select_des_by_stage()
    {
        $diesc = $this->input->post('lead_stage');
        echo json_encode($this->Leads_Model->all_description($diesc));
        //print_r($this->Leads_Model->all_description($diesc));
        // echo $diesc;
    }
    public function lead_detail()
    {
        $leadid = $this->uri->segment(3);
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_code($leadid);
        $lead_code = $data['details']->lid;
        redirect('lead/lead_details/' . $lead_code);
    }

    public function add_comment()
    {
        $CI = &get_instance();
        if (!empty($_POST)) {
            
            $ld_updt_by = $this->session->userdata('user_id');
            $lead_id = $this->input->post('lid');
            $conversation = trim($this->input->post('conversation'));
            $coment_type = trim($this->input->post('coment_type'));
            $adt = date("Y-m-d H:i:s");
            $msg = $conversation;
            $this->db->set('lead_id', $lead_id);
            $this->db->set('created_date', $adt);
            $this->db->set('coment_type ', $coment_type);
            $this->db->set('comment_msg', $conversation);
            $this->db->set('created_by', $ld_updt_by);
            $this->db->insert('tbl_comment');
            $this->db->set('update_date', $adt);
            $this->db->where('Enquery_id', $lead_id);
            $this->db->update('enquiry');
            $this->session->set_flashdata('message', ' added successfully');            
             
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        } else {
            redirect('lead');
        }
    }
    public function enquiry_response_task()
    {
        if (!empty($_POST)) {
            $ld_updt_by = $this->session->userdata('user_id');
            $lead_id = $this->input->post('enq_code');
            $task_type = $this->input->post('task_type');
            $meeting_date = $this->input->post('meeting_date');
            $contact_person = $this->input->post('contact_person');
            $mobileno = $this->input->post('mobileno');
            $email = $this->input->post('email');
            $designation = $this->input->post('designation');
            $conversation = trim($this->input->post('conversation'));
            $subject = $this->input->post('subject');
            $task_status = $this->input->post('task_status');
            $task_date = date("d-m-Y", strtotime($this->input->post('task_date')));
            $task_time = date("H:i:s", strtotime($this->input->post('task_time')));
            $task_remark = $this->input->post('task_remark');
            $cdate2 = str_replace('/', '-', $meeting_date);
            //$adt = date("d-m-Y h:i:s a");
            if (!empty($this->input->post('subject'))) {
                $this->db->set('query_id', $this->input->post('subject'));
            }
            $this->db->set('query_id', $lead_id);
            $this->db->set('subject', $subject);
            //$this->db->set('upd_date', $adt); //Created Date
            //$this->db->set('nxt_date', $cdate2);
            $this->db->set('contact_person', $contact_person);
            $this->db->set('mobile', $mobileno);
            $this->db->set('email', $email);
            $this->db->set('designation', $designation);
            // $this->db->set('org_name', $org_name);
            $this->db->set('conversation', $conversation);
            $this->db->set('task_type', $task_type);
            $this->db->set('task_status', $task_status);
            $this->db->set('task_date', $task_date);
            $this->db->set('task_time', $task_time);
            $this->db->set('comp_id', $this->session->companey_id);
            $this->db->set('task_remark', $task_remark);
            $this->db->set('notification_id', $this->input->post('notification_id'));
            $this->db->set('create_by', $this->session->user_id);
            $this->db->insert('query_response');
            $this->Leads_Model->add_comment_for_events('Task Added ', $lead_id);
            $this->session->set_flashdata('message', 'Task Added Successfully');
             
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        } else {
            redirect('lead');
        }
    }
    public function enquiry_response_updatetask()
    {
        if (!empty($_POST)) {
            $ld_updt_by = $this->session->userdata('user_id');
            $lead_id = $this->input->post('enq_code');
            $task_type = $this->input->post('task_type');
            $meeting_date = $this->input->post('meeting_date');
            $contact_person = $this->input->post('contact_person');
            $mobileno = $this->input->post('mobileno');
            $email = $this->input->post('email');
            $designation = $this->input->post('designation');
            $conversation = trim($this->input->post('task_remark'));
            $subject = $this->input->post('subject');
            $task_date = date("d-m-Y", strtotime($this->input->post('task_date')));
            $task_time = date("H:i:s", strtotime($this->input->post('task_time')));
            $task_remark = $this->input->post('task_remark');
            $task_status = $this->input->post('task_status');
            //$cdate2 = str_replace('/', '-', $meeting_date);
            $adt = date("d-m-Y h:i:s a");
            //$this->db->set('upd_date', $adt);
            //$this->db->set('nxt_date', $cdate2);
            $this->db->set('contact_person', $contact_person);
            $this->db->set('mobile', $mobileno);
            $this->db->set('task_status', $task_status);
            $this->db->set('task_date', $task_date);
            $this->db->set('task_time', $task_time);
            $this->db->set('task_remark', $task_remark);
            $this->db->set('subject', $subject);
            $this->db->set('email', $email);
            $this->db->set('designation', $designation);
            $this->db->set('conversation', $conversation);
            $this->db->set('task_type', $task_type);
            $this->db->where('resp_id', $lead_id);
            $this->db->set('create_by', $this->session->user_id);
            $this->db->update('query_response');
            $task_enquiry_code = $this->input->post('task_enquiry_code');
            $this->Leads_Model->add_comment_for_events_stage('Task Updated Successfully', $task_enquiry_code, 0, 0, $subject . '<br>' . $conversation, 1);
            $this->session->set_flashdata('message', 'Task Updated Successfully');
            redirect($this->agent->referrer());
        } else {
            redirect('lead');
        }
    }
    ///////////////// STAGE ////////////////////
    public function stage()
    {

        $data['nav1'] = 'nav2';
        if (!empty($_POST)) {
           if (user_role('32') == true) {
        }
            $lead_stage_name = $this->input->post('stage_name');
            $process = $this->input->post('process');
            $stage_for = $this->input->post('stage_for');
            
            if (!empty($process)) {
                $process    =   implode(',', $process);
            }else{
                $this->session->set_flashdata('SUCCESSMSG', 'Process is requried');
                redirect('lead/stage');
            }
            if (!empty($stage_for)) {
                $stage_for    =   implode(',', $stage_for);
            }else{
            $this->session->set_flashdata('exception', display('please_try_again'));
                redirect('lead/stage');
            }
            $data = array(
                'lead_stage_name' => $lead_stage_name,
                'process_id'   => $process,
                'stage_for'   => $stage_for,
                'comp_id' => $this->session->userdata('companey_id')
            );
            $insert_id = $this->Leads_Model->lead_stageadd($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Lead Stage Add Successfully');
            redirect('lead/stage');
        }
        if (user_role('31') == true) {
        }
        $data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        // echo $this->db->last_query();
        // print_r($data['lead_stages']);exit();
        // print_r($this->session->userdata('companey_id'));exit();
        $data['products'] = $this->dash_model->get_user_product_list();
        // print_r($data['products']);exit();
        $data['title'] = 'Lead Stage';
        $data['content'] = $this->load->view('lead_stage', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_stage($stage = null)
    {
        if (user_role('34') == true) {
        }
        if ($this->Leads_Model->delete_stage($stage)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/stage');
    }
    public function update_stage()
    {
        if (user_role('33') == true) {
        }
        if (!empty($_POST)) {
            $stage_name = $this->input->post('stage_name');
            $stage_id = $this->input->post('stage_id');
            $process = $this->input->post('process');
            $stage_for = $this->input->post('stage_for');
            if (!empty($process)) {
                $process    =   implode(',', $process);
            }
            if (!empty($stage_for)) {
                $stage_for    =   implode(',', $stage_for);
            }
            $this->db->set('lead_stage_name', $stage_name);
            $this->db->set('process_id', $process);
            $this->db->set('stage_for', $stage_for);
            $this->db->where('stg_id', $stage_id);
            $this->db->update('lead_stage');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/stage');
        }
    }

    public function update_description()
    {
        /*echo "<pre>";
        print_r($_POST);*/
        //exit();
        $en_id = $this->uri->segment(3);
        $action = $this->input->post('url');
        if (!empty($_POST)) {
            if ($action === 'enquiry') {
                $coment_type = 1;
            }
            if ($action === 'lead') {
                $coment_type = 2;
            }
            if ($action === 'client') {
                $coment_type = 3;
            }
            $lead_id = $this->input->post('unique_no');
            $stage_id = $this->input->post('lead_stage');
            $stage_date = date("d-m-Y", strtotime($this->input->post('c_date')));
            //echo $stage_date;
            $stage_time = date("H:i:s", strtotime($this->input->post('c_time')));
            $stage_desc = $this->input->post('lead_description');
            $stage_remark = $this->input->post('conversation');
            $contact_person = $this->input->post('contact_person1');
            $mobileno = $this->input->post('mobileno1');
            $email = $this->input->post('email1');
            $designation = $this->input->post('designation1');
            $enq_code = $this->input->post('enq_code1');

            $this->db->set('lead_stage', $stage_id);
            $this->db->set('lead_discription', $stage_desc);
            $this->db->set('lead_discription_reamrk', $stage_remark);
            $this->db->where('enquiry_id', $en_id);
            $this->db->update('enquiry');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            $this->Leads_Model->add_comment_for_events_stage('Stage Updated', $lead_id, $stage_id, $stage_desc, $stage_remark, $coment_type);
            $notification_id = $this->input->post('dis_notification_id');
            $dis_subject = $this->input->post('dis_subject');
            if ($stage_desc == 'updt') {
                $tid    =   $this->input->post('latest_task_id');
                $this->db->set('task_date', $stage_date);
                $this->db->set('task_time', $stage_time);
                $this->db->set('subject', $dis_subject);
                $this->db->set('task_remark', $stage_remark);
                $this->db->set('notification_id', $notification_id);
                $this->db->where('resp_id', $tid);
                $this->db->update('query_response');
            } else {
                if (!empty($this->input->post('c_date'))) {
                    $this->Leads_Model->add_comment_for_events_popup($stage_remark, $stage_date, $contact_person, $mobileno, $email, $designation, $stage_time, $enq_code, $notification_id, $dis_subject,1,2);
                }
            }
            $this->load->model('rule_model');
            $this->rule_model->execute_rules($enq_code, array(1, 2));
            //print_r($coment_type);exit;
            if ($coment_type == 1) {
                redirect('enquiry/view/' . $en_id);
            } else if ($coment_type == 2) {
                redirect('lead/lead_details/' . $en_id);
            } else if ($coment_type == 3) {
                redirect('client/view/' . $en_id);
            }
        }
    }
    public function delete_score($score = null)
    {
        if (user_role('a32') == true) {
        }
        if ($this->Leads_Model->delete_score($score)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/lead_score');
    }
    public function update_score()
    {
        if (user_role('a31') == true) {
        }
        if (!empty($_POST)) {
            $score_name = $this->input->post('score_name');
            $score_rate = $this->input->post('score_rate');
            
            $score_from = $this->input->post('score_from');
            $score_to = $this->input->post('score_to');
            
            $score_id = $this->input->post('score_id');
            $this->db->set('score_name', $score_name);
            $this->db->set('probability', $score_rate);
            $this->db->set('score_from', $score_from);
            $this->db->set('score_to', $score_to);
            $this->db->where('sc_id', $score_id);
            $this->db->update('lead_score');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/lead_score');
        }
    }
    public function delete_source($source = null)
    {
        if (user_role('a36') == true) {
        }
        if ($this->Leads_Model->delete_source($source)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/lead_source');
    }
    public function update_source()
    {
        if (!empty($_POST)) {
            if (user_role('a34') == true) {
            }
            $source_id = $this->input->post('source_id');
            $source_name = $this->input->post('source_name');
            $this->db->set('lead_name', $source_name);
            $this->db->where('lsid', $source_id);
            $this->db->update('lead_source');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/lead_source');
        }
    }
    public function delete_dropReason($drop = null)
    {
        if (user_role('b34') == true) {
        }
        if ($this->Leads_Model->delete_dropReason($drop)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/add_drop');
    }
    public function update_drop()
    {
        if (!empty($_POST)) {
            if (user_role('b32') == true) {
            }
            $drop_id = $this->input->post('drop_id');
            $reason = $this->input->post('reason');
            $this->db->set('drop_reason', $reason);
            $this->db->where('d_id', $drop_id);
            $this->db->update('tbl_drop');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/add_drop');
        }
    }
    public function delete_ctype($ctype = null)
    {
        if ($this->Leads_Model->delete_ctype($ctype)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/customer_type');
    }
    public function update_cusType()
    {
        if (!empty($_POST)) {
            $cid = $this->input->post('cid');
            $c_type = $this->input->post('c_type');
            $c_typename = $this->input->post('c_typename');
            $this->db->set('c_typename', $c_typename);
            $this->db->set('c_type', $c_type);
            $this->db->where('cid', $cid);
            $this->db->update('customer_type');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/customer_type');
        }
    }
    /////////////////////////////////////////////////

    public function lead_source()
    {
        
       
        $data['nav1'] = 'nav2';
        if (!empty($_POST)) {
            if (user_role('a33') == true) {
            }
            $lead_source_name = $this->input->post('source_name');
            $data = array(
                'lead_name' => $lead_source_name,
                'comp_id' => $this->session->userdata('companey_id')
            );
            $insert_id = $this->Leads_Model->lead_sourceadd($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Lead Source Add Successfully');
            redirect('lead/lead_source');
        }
        if (user_role('a35') == true) {
        }
        $data['lead_source'] = $this->Leads_Model->get_leadsource_list();
        $data['content'] = $this->load->view('lead_source', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function lead_score()
    {
       
        $data['nav1'] = 'nav2';
        if (!empty($_POST)) {
            if (user_role('a30') == true) {
            }
            $score_name = $this->input->post('score_name');
            $score_rate = $this->input->post('score_rate');
            
            $score_from = $this->input->post('score_from');
            $score_to = $this->input->post('score_to');
            
            $data = array(
                'score_name' => $score_name,
                'comp_id' => $this->session->userdata('companey_id'),
                'probability' => $score_rate,
                'score_from' => $score_from,
                'score_to' => $score_to
            );
            $insert_id = $this->Leads_Model->lead_scoreadd($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Lead Score Add Successfully');
            redirect('lead/lead_score');
        }
        if (user_role('39') == true) {
        }
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $data['title'] = 'Lead Probability';
        $data['content'] = $this->load->view('lead_score', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function customer_type()
    {
        if (!empty($_POST)) {
            $c_type = $this->input->post('c_type');
            $c_typename = $this->input->post('c_typename');
            $data = array(
                'c_typename' => $c_typename,
                'c_type' => $c_type
            );
            $insert_id = $this->Leads_Model->add_customerType($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Customer Type Add Successfully');
            redirect('lead/customer_type');
        }
        $data['title'] = 'Customer Type';
        $data['c_type'] = $this->Leads_Model->get_customerType_list();
        $data['content'] = $this->load->view('customer_type', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    /////////////////// Move To Client ////////////////////////////
    public function move_to_lead()
    {
        if (!empty($_POST)) {
            $move_lead = $this->input->post('lead_status');
            foreach ($move_lead as $key) {
                $this->db->set('lead_stage', 'Account');
                $this->db->where('lid', $key);
                $this->db->update('allleads');
                //////////////// Insert Lead Data Into Client Table ///////////////////////
                $lead = $this->Leads_Model->get_lead_for_account($key);
                $name = $lead->ld_name;
                $email = $lead->ld_email;
                $mobile = $lead->ld_mobile;
                $address = $lead->address;
                $data = array(
                    'cl_name' => $name,
                    'city_id' => $lead->city_id,
                    'customer_code' => $lead->lead_code,
                    'create_by' => $lead->adminid,
                    'updated_by' => $this->session->user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'cl_email' => $email,
                    'cl_mobile' => $mobile,
                    'address' => $address,
                    'cl_status' => '1'
                );
                $insert_id = $this->Leads_Model->ClientMove($data);
                //////////////////////////////////////////////////////////////////////////
            }
            //echo 'Transfer Lead Successfully';
            redirect('lead');
        } else {
            echo "<script>alert('Something Went Wrong')</script>";
            redirect('lead');
        }
    }
    public function convert_to_lead()
    {
        $enquiry_id = $this->uri->segment('3');
        $lead = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
         //print_r($lead); exit;
        $leadSataus = $lead->status;
           $Enquery_id = $lead->Enquery_id;
          $stage = $lead->status;
        $next_stage = $stage + 1;
        if ($leadSataus >= 2) {
            //$this->Leads_Model->ClientMove($data);
            $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
            if (!empty($enquiry_separation) and $leadSataus  >= 3) {
                $enquiry_separation = json_decode($enquiry_separation, true);
                if ($leadSataus != 3) {
                    foreach ($enquiry_separation as $key => $value) {
                        // print_r($enquiry_separation);
                        if ($stage == $key) {
                            $ctitle = $enquiry_separation[$key]['title'];
                            $ccomment = 'Converted to ' . $ctitle;
                        }
                    }
                } else  {
                    $ccomment = 'Converted to '.display('client');
                }
                // get data from comment and insert into follow up table.
                $getComment = $this->db->where(array('comment_msg' => $ccomment, 'lead_id' => $Enquery_id))->get('tbl_comment');
                if ($getComment->num_rows() == 1) {
                    $fetchComment = $getComment->row();
                    $leadCreatedate = $fetchComment->created_date;
                    //insert follow up counter 
                    $this->enquiry_model->insetFollowupTime($enquiry_id, $next_stage, $leadCreatedate, date('Y-m-d H:i:s'));
                }
                $title = $enquiry_separation[$next_stage]['title'];
                $url = 'client/index/?stage=' . $next_stage;
                $comment = 'Converted to ' . $title;
                $this->db->set('status', $next_stage);
            } else {
                $url = 'led/index';
                $comment = 'Converted to '.display('Client');
                //echo $comment ; exit();
                //insert follow up counter (3 is for client )
                if(empty($lead->lead_created_date))$lead->lead_created_date=$lead->created_date;
                $this->enquiry_model->insetFollowupTime($enquiry_id, 3, $lead->lead_created_date, date('Y-m-d H:i:s'));
                $this->db->set('status', 3);
            }
            $this->db->set('client_created_date',date('Y-m-d h:i:s'));
            $this->db->set('created_date', date('Y-m-d H:i:s'));
            $this->db->set('update_date', '');
            $this->db->where('enquiry_id', $enquiry_id);
            $this->db->update('enquiry');
            $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
            $lead_code = $data['enquiry']->Enquery_id;
            $this->Leads_Model->add_comment_for_events($comment, $lead_code);
            $msg = $comment . ' Successfully';
            $this->load->model('rule_model');
            $this->rule_model->execute_rules($lead_code, array(1, 2, 3, 6, 7));
            if ($lead->status == 2 && $this->session->companey_id == 76 || ($this->session->companey_id == 57 && $data['enquiry']->product_id == 122)) {
                $user_right = '';
                if ($data['enquiry']->product_id == 168) {
                    $user_right = 180;
                } else if ($data['enquiry']->product_id == 169) {
                    $user_right = 186;
                }
                $report_to = '';
                if ($this->session->companey_id == 57) {
                    if (!empty($data['enquiry']->email) || !empty($data['enquiry']->phone)) {
                        $user_exist = $this->dashboard_model->check_user_by_mail_phone(array('email' => $data['enquiry']->email, 'phone' => $data['enquiry']->phone));
                    }
                    $user_right = 200;
                    $report_to = $data['enquiry']->enq_created_by;
                }
                $ucid    =   $this->session->companey_id;
                $postData = array(
                    's_display_name'  =>    $data['enquiry']->name,
                    'last_name'       =>    $data['enquiry']->lastname,
                    's_user_email'    =>    $data['enquiry']->email,
                    's_phoneno'       =>    $data['enquiry']->phone,
                    'city_id'         =>    $data['enquiry']->enquiry_city_id,
                    'state_id'        =>    $data['enquiry']->enquiry_state_id,
                    'companey_id'     =>    $ucid,
                    'b_status'        =>    1,
                    'user_permissions' =>    $user_right,
                    'user_roles'      =>    $user_right,
                    'user_type'       =>    $user_right,
                    's_password'      =>    md5(12345678),
                    'report_to'       =>    $report_to
                );
                if (!empty($user_exist)) {
                    $this->db->where('tbl_admin.companey_id', 57);
                    $this->db->where('tbl_admin.pk_i_admin_id', $user_exist->pk_i_admin_id);
                    if ($this->db->update('tbl_admin', array('user_permissions' => 200, 'user_roles' => 200, 'user_type' => 200))) {
                        $user_id = $user_exist->pk_i_admin_id;
                    } else {
                        $user_id = '';
                    }
                } else {
                    $user_id    =   $this->user_model->create($postData);
                }
                $message = 'Email - ' . $data['enquiry']->email . '<br>Password - 12345678';
                $subject = 'Login Details';
                if ($this->session->companey_id == 57 && $user_id) {
                    $this->db->where('temp_id', 125);
                    $this->db->where('comp_id', 57);
                    $temp_row    =   $this->db->get('api_templates')->row_array();
                    if (!empty($temp_row)) {
                        $subject = $temp_row['mail_subject'];
                        $message = str_replace("@{email}", $data['enquiry']->email, $temp_row['template_content']);
                        $message = str_replace("@{password}", '12345678', $message);
                    }
                    $this->Message_models->send_email($data['enquiry']->email, $subject, $message);
                    $this->db->where('temp_id', 124);
                    $this->db->where('comp_id', 57);
                    $temp_row    =   $this->db->get('api_templates')->row_array();
                    if (!empty($temp_row)) {
                        $message = str_replace("@{email}", $data['enquiry']->email, $temp_row['template_content']);
                        $message = str_replace("@{password}", '12345678', $message);
                    }
                    $this->Message_models->smssend($data['enquiry']->phone, $message);
                    $msg .=    " And user created successfully";
                } else {
                    if($this->session->companey_id == 76 && $user_id){
                        $msg .=    " And user created";
                    }else{
                        $msg .=    " And user already exist";
                    }
                }
            }
            //$mail_access = $this->enquiry_model->access_mail_temp(); //access mail template..
            //$signature = $this->enquiry_model->get_signature();
            /*     $to = 'glahsnigam@gmail.com';
            $from_email = 'info@archizcrm.com';            
                $phone = '91' . $lead->phone;
                $message = "Congratulations and Welcome on board as Authorized Channel Partner.";
                //$this->Message_models->smssend($phone, $message);
                //$this->Message_models->sendwhatsapp($phone, $message);
                if(isset($mail_access)){
                foreach ($mail_access as $rows) {
                    if (trim($rows->response_type) == 2 && trim($rows->auto_mail_for) == 3) {
                        $img = "<img src='" . base_url($signature->logo) . "' width='100px' height='100px' onerror='this.style.display=" . 'none' . "'>";
                        if (strpos($rows->template_content, '@') == true) {
                            $msg = str_replace('@name', $name1, str_replace('@org', $org, str_replace('@phone', $this->session->phone, str_replace('@desg', $this->session->designation, str_replace('@user', $this->session->fullname, $rows->template_content)))));
                        } else {
                            $msg = $rows->template_content;
                        }
                        $this->email->from($from_email, 'Osum');
                        $this->email->to($to);
                        $this->email->subject($rows->mail_subject);
                        $this->email->message($msg);
                        $this->email->set_mailtype('html');
                        //$this->email->send();
                    }
                }
        }*/
            $this->session->set_flashdata('message', $msg);
            redirect($url);
        } else {
            $this->session->set_flashdata('message', 'Please Complete all Stages');
            redirect($this->agent->referrer()); //updateclient

        }
    }
    public function any_convert_to_any()
    {
        //$stage = $this->uri->segment('3');
        //$enquiry_id = $this->uri->segment('4');
        //$next_stage = $this->uri->segment('5');
		$stage = $this->input->post('current_stg');
        $enquiry_id = $this->input->post('enq_id');
        $next_stage = $this->input->post('next_stg');
		$deal_id = $this->input->post('deal_id');
        $lead = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
         //print_r($lead); exit;
        $leadSataus =$next_stage;
        $Enquery_id = $lead->Enquery_id;
        //   $stage = $lead->status;
        // $next_stage = $stage + 1;
    if($next_stage == '3'){
		$this->db->where('enquiry_id', $enquiry_id);
		$this->db->where('status', '0');
        $query = $this->db->get('commercial_info');
        $all_row = $query->num_rows();
		
        if(!empty($all_row)){
			$all_row = $all_row;
		}else{
			$all_row = '';
		}
	}else if($next_stage == '4'){
		$this->db->where('enquiry_id', $enquiry_id);
		$this->db->where('status', '1');
        $query = $this->db->get('commercial_info');
        $all_row_done = $query->num_rows();
		
		if(!empty($all_row_done)){
			$all_row_done = $all_row_done;
		}else{
			$all_row_done = '';
		}
	}else{
		$all_row = '1';
		$all_row_done = '1';
	}

        if(empty($all_row) && $next_stage == '3'){
        	$msg = 'Before The Moving In Negotiation Stage Need To Create The A Deal First!';
		    $this->session->set_flashdata('exception', $msg);
            $url = 'led/index';			
            redirect($url);			
		}else if(empty($all_row_done) && $next_stage == '4'){
			$msg = 'Before The Moving In Closure Stage Need To Deal Done First!';
		    $this->session->set_flashdata('exception', $msg);
            $url = 'client/index';			
            redirect($url);
		}else{
        if ($next_stage > 2) {
            //$this->Leads_Model->ClientMove($data);
            $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
            if ($leadSataus  > 3) {
				
                $enquiry_separation = json_decode($enquiry_separation, true);
                if ($leadSataus != 3) {
                    foreach ($enquiry_separation as $key => $value) {
                        // print_r($enquiry_separation);
                        if ($stage == $key) {
                            $ctitle = $enquiry_separation[$key]['title'];
                            $ccomment = 'Converted to ' . $ctitle;
                        }
                    }
                } else  {
                    $ccomment = 'Converted to clients';
                }
                // get data from comment and insert into follow up table.
                $getComment = $this->db->where(array('comment_msg' => $ccomment, 'lead_id' => $Enquery_id))->get('tbl_comment');
                if ($getComment->num_rows() == 1) {
                    $fetchComment = $getComment->row();
                    $leadCreatedate = $fetchComment->created_date;
                    //insert follow up counter 
                    $this->enquiry_model->insetFollowupTime($enquiry_id, $next_stage, $leadCreatedate, date('Y-m-d H:i:s'));
                 }
				 
				 //////////////////update stage in deal table Start///////////////////
				foreach($deal_id as $key=>$value){
			        $this->db->set('stage_id',$next_stage);
                    $this->db->where('id', $value);
                    $this->db->update('commercial_info');
                    $this->enquiry_model->save_goal_log(array('deal_id'=>$value,'stage'=>$next_stage,'uid'=>$this->session->user_id));
		        }
				 //////////////////update stage in deal table End///////////////////
				 
                $title = $enquiry_separation[$next_stage]['title'];
                $url = 'client/index/?stage=' . $next_stage;
                $comment = 'Converted to ' . $title;
                $this->db->set('status', $next_stage);
            } else {
                $url = 'led/index';
                $comment = 'Converted to '.display('Client');
                //echo $comment ; exit();
                //insert follow up counter (3 is for client )
				
				//////////////////update stage in deal table Start///////////////////
				foreach($deal_id as $key=>$value){
			        $this->db->set('stage_id',$next_stage);
                    $this->db->where('id', $value);
                    $this->db->update('commercial_info');
                    $this->enquiry_model->save_goal_log(array('deal_id'=>$value,'stage'=>$next_stage,'uid'=>$this->session->user_id));
		        }
				 //////////////////update stage in deal table End///////////////////
				
                if(empty($lead->lead_created_date))$lead->lead_created_date=$lead->created_date;
                $this->enquiry_model->insetFollowupTime($enquiry_id, 3, $lead->lead_created_date, date('Y-m-d H:i:s'));
                $this->db->set('status', 3);
            }
            $this->db->set('client_created_date',date('Y-m-d h:i:s'));
            $this->db->set('created_date', date('Y-m-d H:i:s'));
            $this->db->set('update_date', '');
            $this->db->where('enquiry_id', $enquiry_id);
            $this->db->update('enquiry');
            $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
            $lead_code = $data['enquiry']->Enquery_id;
            $this->Leads_Model->add_comment_for_events($comment, $lead_code);
            $msg = $comment . ' Successfully';
            $this->load->model('rule_model');
            $this->rule_model->execute_rules($lead_code, array(1, 2, 3, 6, 7));
            $this->session->set_flashdata('message', $msg);
            redirect($url);
        }else{
            redirect();
        }
		}
    }
    public function add_drop()
    {
        $data['title'] = 'Drop Reasons';
        $data['nav1'] = 'nav2';
        #------------------------------# 
        $leadid = $this->uri->segment(3);
        //////////////////////////////////////////////////////
        if (!empty($_POST)) {
            if (user_role('b31') == true) {
            }
            $reason = $this->input->post('reason');
            $data = array(
                'drop_reason' => $reason,
                'comp_id' => $this->session->userdata('companey_id')
            );
            $insert_id = $this->Leads_Model->add_dropType($data);
            redirect('lead/add_drop');
        }
        //////////////////////////////////////////////////////
        if (user_role('b33') == true) {
        }
        $data['drops'] = $this->Leads_Model->get_drop_list();
        $data['content'] = $this->load->view('drop', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function drop_lead()
    {
        
        $data['title'] = 'Drop Reasons';
        $leadid = $this->uri->segment(3);
        // print_r($this->input->post('drop_status'));exit;
        if (!empty($_POST)) {
            if (user_role('b31') == true) {
            }
            $reason = $this->input->post('reason');
            $drop_status = $this->input->post('drop_status');
            $this->db->set('drop_status', $drop_status);
            $this->db->set('drop_reason', $reason);
            $this->db->where('enquiry_id', $leadid);
            $this->db->update('enquiry');
            $this->Leads_Model->add_comment_for_events('Dropped Leads', $leadid);
            redirect('led/index');
        }
        if (user_role('b33') == true) {
        }
        $data['drops'] = $this->Leads_Model->get_drop_list();
        $data['content'] = $this->load->view('drop', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function post_po()
    {
        if (!empty($_POST)) {
            $picture = $this->fileupload->do_upload(
                'assets/po_order/',
                'img_file'
            );
            //if picture is not uploaded
            if ($picture === false) {
                $this->session->set_flashdata('exception', display('Fail Uploading Po order'));
                redirect($this->agent->referrer());
            } else {
                $this->db->set('customer_id ', $this->input->post('lead_id_name'));
                $this->db->set('po_document_img ', $picture);
                $this->db->set('created_date ', date('Y-m-d h:s:i'));
                $this->db->set('created_by', $this->session->user_id);
                $this->db->insert('tbl_po');
                $this->db->set('lead_stage', 9);
                $this->db->where('lead_code', $this->input->post('lead_id_name'));
                $this->db->update('allleads');
                $this->session->set_flashdata('message', display('Uploaded Successfully'));
                redirect('lead');
                $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_id($this->input->post('lead_id_name'));
                $lead_code = $data['enquiry']->lead_code;
                $this->Leads_Model->add_comment_for_events('Converted to '.display('client'), $lead_code);
            }
        }
    }
    public function network_digram()
    {
        if (!empty($_POST)) {
            $picture = $this->fileupload->do_upload(
                'assets/network_img/',
                'img_file'
            );
            //if picture is not uploaded
            //  print_r($picture);exit();
            if ($picture === false) {
                $this->session->set_flashdata('message', display('Fail Uploading Layout Sheet  order'));
                redirect($this->agent->referrer());
            } else {
                if (!empty($picture)) {
                    $this->db->set('customer_id ', $this->input->post('lead_id_name'));
                    $this->db->set('netoek_document_img ', $picture);
                    $this->db->set('created_date ', date('Y-m-d h:s:i'));
                    $this->db->set('created_by', $this->session->user_id);
                    $this->db->insert('tbl_network');
                    $this->db->set('lead_stage', 4);
                    $this->db->where('lead_code', $this->input->post('lead_id_name'));
                    $this->db->update('allleads');
                    $this->session->set_flashdata('message', 'Uploaded Successfully');
                    $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_id($this->input->post('lead_id_name'));
                    $lead_code = base64_encode($this->input->post('lead_id_name'));
                    $this->Leads_Model->add_comment_for_events('Layout Sheet Added', $lead_code);
                    $this->session->set_flashdata('message', display('Layout Sheet Uploaded successfully'));
                    redirect('lead');
                } else {
                    $this->session->set_flashdata('message', display('Fail Uploading Layout Sheet order'));
                    redirect($this->agent->referrer());
                }
            }
        }
        redirect($this->agent->referrer());
    }
    public function invoice_add()
    {
        if (!empty($_POST)) {
            $picture = $this->fileupload->do_upload(
                'assets/inovice_details/',
                'img_file'
            );
            //if picture is not uploaded
            if ($picture === false) {
                $this->session->set_flashdata('exception', display('Fail Uploading Po order'));
                redirect($this->agent->referrer());
            } else {
                $this->db->set('customer_id ', $this->input->post('lead_id_name'));
                $this->db->set('netoek_document_img ', $picture);
                $this->db->set('created_date ', date('Y-m-d h:s:i'));
                $this->db->set('created_by', $this->session->user_id);
                $this->db->insert('tbl_network');
                $this->db->set('lead_stage', 4);
                $this->db->where('lid', $this->input->post('lead_id_name'));
                $this->db->update('allleads');
                $this->session->set_flashdata('message', display('Uploaded Successfully'));
                $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_id($this->input->post('lead_id_name'));
                $lead_code = $data['enquiry']->lead_code;
                $this->Leads_Model->add_comment_for_events('Network Diagram Added', $lead_code);
            }
        }
    }
    public function active_lead($id)
    {

        $this->db->set('drop_status', 0);
        $this->db->where('enquiry_id', $id);
        $this->db->update('enquiry');
        $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_ledsonly($id);
        $lead_code = $data['enquiry']->Enquery_id;
        $this->Leads_Model->add_comment_for_events(display('lead').' actived ', $lead_code);
        $this->session->set_flashdata('message', "Activated Successfully");
        redirect('lead/lead_details/' . $id);
    }
    /////////////////////////////////////////////////////
    public function assign_lead()
    {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id');
            $assign_employee = $this->input->post('assign_employee');
            $user = $this->User_model->read_by_id($assign_employee);
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $this->db->set('aasign_to', $assign_employee);
                    $this->db->set('assign_by', $this->session->user_id);
                    $this->db->set('lead_updated_date', date('Y-m-d H:i:s'));
                    $this->db->where('enquiry_id', $key);
                    $this->db->update('enquiry');
                    $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($key);
                    $lead_code = $data['details']->Enquery_id;
                    $this->Leads_Model->add_comment_for_events(display('lead_assigned'), $lead_code);
                }
                echo display('save_successfully');
            } else {
                echo display('please_try_again');
            }
        }
    }
    public function assign_presales()
    {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id');
            $assign_employee = $this->input->post('assign_presales');
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    //  $this->db->set('assign_to',$assign_employee);
                    $this->db->set('assign_for_boq', $assign_employee);
                    $this->db->set('update_date', date('Y-m-d H:i:s'));
                    $this->db->where('lid', $key);
                    $this->db->update('allleads');
                    $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_ledsonly($key);
                    $lead_code = $data['enquiry']->lead_code;
                    $this->Leads_Model->add_comment_for_events(display('lead').' Assigned To Pre-Salses', $lead_code);
                }
                echo '1';
            } else {
                echo display('please_try_again');
            }
        }
    }
    public function drop_leadss()
    {
        if (!empty($_POST)) {
            $reason = $this->input->post('reason_details');
            $drop_status = $this->input->post('drop_status');
            $move_enquiry = $this->input->post('enquiry_id');
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {             
                    $this->db->set('drop_status', $drop_status);
                    $this->db->set('drop_reason', $reason);
                    $this->db->where('enquiry_id', $key);
                    $this->db->update('enquiry');
                    $data['enquiry'] = $this->Leads_Model->get_leadListDetailsby_ledsonly($key);
                    $lead_code = $data['enquiry']->Enquery_id;
                    $this->Leads_Model->add_comment_for_events('Dropped '.display('lead'), $lead_code);
                }
                echo '1';
            } else {
                echo display('please_try_again');
            }
        }
    }
    public function delete_recorde()
    {
        if (!empty($_POST)) {

            $move_enquiry = $this->input->post('enquiry_id');

            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $this->db->where('lid', $key);
                    $this->db->where('lead_stage!=', 'Account');
                    $this->db->delete('allleads');
                }
                $this->session->set_flashdata('message', "Enquiry Deleted Successfully");
                redirect(base_url() . 'lead');
            } else {
                echo display('please_try_again');
            }
        }
    }
    function productcountry()
    {
        if (user_role('c35') == true) {
        }
        $data['title'] = 'Product List';
        $data['country'] = $this->location_model->productcountry();
        // echo "<pre>";
        // print_r($data['country']);exit();
        $data['content'] = $this->load->view('location/product_country_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function addproductcountry()
    {
        
        $data['title'] = 'Add Product';
        if (empty($this->input->post('user_id'))) {
            $this->form_validation->set_rules('country_name', display('country_name'), 'required|max_length[200]');
        } else {
            $this->form_validation->set_rules('country_name', display('country_name'), 'required|max_length[200]');
        }
        $data['formdata'] = (object) $postData = [
            'id' => $this->input->post('id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'country_name' => $this->input->post('country_name', true),
            'price' => $this->input->post('price', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_date' => date('Y-m-d'),
        ];
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('id'))) {
                if (user_role('c33') == true) {
                }
                if ($this->location_model->addproductcountry($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('lead/productcountry');
            } else {
                if (user_role('c34') == true) {
                }
                if ($this->location_model->updateProductCountry($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('lead/productcountry');
            }
        } else {
            if (user_role('c33') == true) {
            }
            $data['typeofpro_list'] = $this->warehouse_model->typeofproduct_list();
            $data['brand_list'] = $this->warehouse_model->brand_list();
            $data['content'] = $this->load->view('location/product_country_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function addproduct()
    {
        $data['title'] = 'Add Product';
        if (empty($this->input->post('user_id'))) {
            $this->form_validation->set_rules('proname', display('name'), 'required|max_length[50]');
        } else {
            $this->form_validation->set_rules('proname', display('name'), 'required|max_length[50]');
        }
        $data['formdata'] = (object) $postData = [
            'id' => $this->input->post('id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'country_name' => $this->input->post('proname', true),
            'skuid' => $this->input->post('skuid', true),
            'typeofpro' => $this->input->post('top', true),
            'brand' => $this->input->post('brand', true),
            'price' => $this->input->post('price', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_date' => date('Y-m-d'),
        ];
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('id'))) {
                if (user_role('c33') == true) {
                }
                if ($this->location_model->addproductcountry($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('lead/productcountry');
            } else {
                if (user_role('c34') == true) {
                }
                if ($this->location_model->updateProductCountry($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('lead/productcountry');
            }
        } else {
            if (user_role('c35') == true) {
            }
            $data['typeofpro_list'] = $this->warehouse_model->typeofproduct_list();
            $data['brand_list'] = $this->warehouse_model->brand_list();
            $data['content'] = $this->load->view('location/product_country_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function editproductcountry($param_id = null)
    {
        if (user_role('c34') == true) {
        }
        $data['title'] = 'Edit Product';
        $data['formdata'] = $this->location_model->readProductCountry($param_id);
        $data['content'] = $this->load->view('location/product_country_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function editproductcountry1($param_id = null)
    {
        if (user_role('c34') == true) {
        }
        $data['title'] = 'Edit Product';
        $data['formdata'] = $this->location_model->readProductCountry($param_id);
        $data['typeofpro_list'] = $this->warehouse_model->typeofproduct_list();
        $data['brand_list'] = $this->warehouse_model->brand_list();
        $data['content'] = $this->load->view('location/product_country_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function deleteproductcountry($paramId = null)
    {
        if (user_role('c36') == true) {
        }
        if ($this->location_model->deleteproductcountry($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/productcountry');
    }

    public function institutelist()
    {
        if (user_role('f31') == true) {
        }
        $data['title'] = display('institute_list');
        $data['institute_list'] = $this->Institute_model->institutelist();
        $data['content'] = $this->load->view('institute/institute_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function courselist()
    {
        if (user_role('f39') == true) {
        }
        $data['title']          = display('course_list');
        $this->load->library('pagination');
        $config                 = array();
        $config["base_url"]     = base_url() . "lead/courselist";
        $config["total_rows"]   = $this->Institute_model->courselist('', '', 'count');
        $config["per_page"]     = 10;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"]          = $this->pagination->create_links();
        $data['course_list']    = $this->Institute_model->courselist($config["per_page"], $page);
        $data['courses']        = $this->Institute_model->findcourse();
        $data['discipline']     = $this->location_model->find_discipline();
        $data['level']          = $this->location_model->find_level();
        $data['length']         = $this->location_model->find_length();
        $data['content']        = $this->load->view('institute/course_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function upload_course()
    {
        $comp_id = $this->session->companey_id;
        $user_id = $this->session->user_id;
        $filename = $_FILES["course_file"]["tmp_name"];
        $i = 0;
        $c = 0;
        if ($_FILES["course_file"]["size"] > 0) {
            $file = fopen($filename, "r");
            while (($courseData = fgetcsv($file, 10000, ",")) !== FALSE) {
                if ($i) {
                    $course_name    = $courseData[0];
                    $rating         = $courseData[1];
                    $course_ielet   = $courseData[2];
                    $discipline     = $courseData[3];
                    $level          = $courseData[4];
                    $length         = $courseData[5];
                    $institute      = $courseData[6];
                    $tuition_fees   = $courseData[7];
                    $description    = $courseData[8];
                    $status         = $courseData[9];
                    $this->db->select('id');
                    $this->db->where('course_name', $course_name);
                    $this->db->where('comp_id', $comp_id);
                    $course_row    =   $this->db->get('tbl_crsmaster')->row_array();
                    if (!empty($course_row)) {
                        $course_id = $course_row['id'];
                    } else {
                        $this->db->insert('tbl_crsmaster', array('course_name' => $course_name, 'status' => 1, 'comp_id' => $comp_id, 'created_by' => $user_id));
                        $course_id = $this->db->insert_id();
                    }
                    $this->db->select('id');
                    $this->db->where('discipline', $discipline);
                    $this->db->where('comp_id', $comp_id);
                    $discipline_row    =   $this->db->get('tbl_discipline')->row_array();
                    if (!empty($discipline_row)) {
                        $discipline_id = $discipline_row['id'];
                    } else {
                        $this->db->insert('tbl_discipline', array('comp_id' => $comp_id, 'discipline' => $discipline, 'status' => 1, 'created_by' => $user_id));
                        $discipline_id   =  $this->db->insert_id();
                    }
                    $this->db->select('id');
                    $this->db->where('level', $level);
                    $this->db->where('comp_id', $comp_id);
                    $level_row    =   $this->db->get('tbl_levels')->row_array();
                    if (!empty($level_row)) {
                        $level_id = $level_row['id'];
                    } else {
                        $this->db->insert('tbl_levels', array('comp_id' => $comp_id, 'level' => $level, 'status' => 1, 'created_by' => $user_id));
                        $level_id   =  $this->db->insert_id();
                    }
                    $this->db->select('id');
                    $this->db->where('length', $length);
                    $this->db->where('comp_id', $comp_id);
                    $length_row    =   $this->db->get('tbl_length')->row_array();
                    if (!empty($length_row)) {
                        $length_id = $length_row['id'];
                    } else {
                        $this->db->insert('tbl_length', array('comp_id' => $comp_id, 'length' => $length, 'status' => 1, 'created_by' => $user_id));
                        $length_id   =  $this->db->insert_id();
                    }
                    $this->db->select('institute_id');
                    $this->db->where('institute_name', $institute);
                    $this->db->where('comp_id', $comp_id);
                    $institute_row    =   $this->db->get('tbl_institute')->row_array();
                    if (!empty($institute_row)) {
                        $institute_id = $institute_row['institute_id'];
                    } else {
                        $this->db->insert('tbl_institute', array('comp_id' => $comp_id, 'institute_name' => $institute, 'status' => 1, 'created_by' => $user_id));
                        $institute_id   =  $this->db->get()->insert_id();
                    }
                    $crs_data = array(
                        'comp_id'       => $comp_id,
                        'institute_id'  => $institute_id,
                        'length_id'     => $length_id,
                        'level_id'      => $level_id,
                        'discipline_id' => $discipline_id,
                        'course_name'   => $course_id,
                        'course_ielts'  => $course_ielet,
                        'tuition_fees'  => $tuition_fees,
                        'created_by'    => $user_id,
                        'status'        => $status,
                        'course_rating' => $rating,
                        'course_discription'    => $description
                    );
                    if ($this->db->insert('tbl_course', $crs_data)) {
                        $c++;
                    }
                }
                $i++;
            }
            fclose($file);
        }
        $this->session->set_flashdata('message', $c . ' Data inserted successfully.');
        redirect('lead/courselist');
    }
    public function crslist()
    {
        if (user_role('f35') == true) {
        }
        $data['title'] = display('course_master');
        $this->load->library('pagination');
        $config = array();
        $config["base_url"] = base_url() . "lead/crslist";
        $config["total_rows"] = $this->Institute_model->crslist('', '', 'count');
        $config["per_page"] = 10;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['course_list'] = $this->Institute_model->crslist($config["per_page"], $page);
        $data['content'] = $this->load->view('institute/crs_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function sub_course()
    {
        if (user_role('g33') == true) {
        }
        $data['title'] = display('sub_course');
        $this->load->library('pagination');
        $config = array();
        $config["base_url"] = base_url() . "lead/sub_course";
        $config["total_rows"] = $this->Institute_model->sub_course('', '', 'count');
        $config["per_page"] = 10;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['course_list'] = $this->Institute_model->sub_course($config["per_page"], $page);
        $data['cource'] = $this->Institute_model->crsmstrlist();
        $data['content'] = $this->load->view('institute/sub_crs_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function vidlist()
    {
        if (user_role('g37') == true) {
        }
        $data['title'] = display('vedio_list');
        $data['vid_list'] = $this->Institute_model->vidlist();
        $data['content'] = $this->load->view('institute/vid_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    /**************************************** Discipline*****************************************/
    public function discipline()
    {
        if (user_role('30') == true) {
        }
        $data['title'] = display('program_discipline');
        $data['discipline_list'] = $this->Institute_model->disciplinelist();
        $data['content'] = $this->load->view('institute/discipline_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function add_discipline()
    {
        $data['title'] = display('add_discipline');
        $data['discipline'] = '';
        $this->form_validation->set_rules('discipline_name', display('program_discipline'), 'required');
        $data['discipline'] = (object) $postData = [
            'id' => $this->input->post('discipline_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'discipline' => $this->input->post('discipline_name', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'create_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('discipline_id'))) {
                if (user_role('30') == true) {
                }
                if ($this->Institute_model->insertRowdisci($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('30') == true) {
                }
                if ($this->Institute_model->updateRowdisci($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/discipline');
        } else {
            $data['content'] = $this->load->view('institute/discipline_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_discipline($id = null)
    {
        if (user_role('30') == true) {
        }
        $data['title'] = display('update_discipline');
        $data['discipline'] = $this->Institute_model->readRowdisci($id);
        $data['content'] = $this->load->view('institute/discipline_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_discipline($paramId = null)
    {
        if (user_role('30') == true) {
        }
        if ($this->Institute_model->deletediscipline($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/discipline');
    }
    /**************************************** Discipline End*****************************************/
    /**************************************** Level *****************************************/
    public function level()
    {
        if (user_role('30') == true) {
        }
        $data['title'] = display('program_level');
        $data['level_list'] = $this->Institute_model->levellist();
        /*echo $this->db->last_query();
        print_r($data['institute_list']);*/
        $data['content'] = $this->load->view('institute/level_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function add_level()
    {
        $data['title'] = display('add_level');
        $data['level'] = '';
        $this->form_validation->set_rules('level_name', display('program_level'), 'required');
        $data['level'] = (object) $postData = [
            'id' => $this->input->post('level_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'level' => $this->input->post('level_name', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'create_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            // print_r($postData);exit;
            if (empty($this->input->post('level_id'))) {
                if (user_role('30') == true) {
                }
                if ($this->Institute_model->insertRowlvl($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('30') == true) {
                }
                if ($this->Institute_model->updateRowlvl($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/level');
        } else {
            $data['content'] = $this->load->view('institute/level_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_level($id = null)
    {
        if (user_role('30') == true) {
        }
        $data['title'] = display('update_level');
        $data['level'] = $this->Institute_model->readRowlvl($id);
        $data['content'] = $this->load->view('institute/level_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_level($paramId = null)
    {
        if (user_role('30') == true) {
        }
        if ($this->Institute_model->deletelevel($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/level');
    }
    /**************************************** Lavel End*****************************************/
    /**************************************** Length *****************************************/
    public function length()
    {
        if (user_role('30') == true) {
        }
        $data['title'] = display('program_length');
        $data['length_list'] = $this->Institute_model->lengthlist();
        /*echo $this->db->last_query();
        print_r($data['institute_list']);*/
        $data['level'] = $this->Institute_model->levellist();
        $data['content'] = $this->load->view('institute/length_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function add_length()
    {
        $data['title'] = display('add_length');
        $data['level'] = '';
        $this->form_validation->set_rules('length_name', display('program_length'), 'required');
        $data['length'] = (object) $postData = [
            'id' => $this->input->post('length_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'level_id' => $this->input->post('level_id', true),
            'length' => $this->input->post('length_name', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            // print_r($postData);exit;
            if (empty($this->input->post('length_id'))) {
                if (user_role('30') == true) {
                }
                if ($this->Institute_model->insertRowlgh($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('31') == true) {
                }
                if ($this->Institute_model->updateRowlgh($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/length');
        } else {
            $data['level'] = $this->Institute_model->levellist();
            $data['content'] = $this->load->view('institute/length_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_length($id = null)
    {
        if (user_role('31') == true) {
        }
        $data['title'] = display('update_length');
        $data['length'] = $this->Institute_model->readRowlgh($id);
        $data['level'] = $this->Institute_model->levellist();
        $data['content'] = $this->load->view('institute/length_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_length($paramId = null)
    {
        if (user_role('32') == true) {
        }
        if ($this->Institute_model->deletelength($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/length');
    }
    public function select_length_lvl()
    {
        $diesc = $this->input->post('lead_stage');
        echo json_encode($this->location_model->all_length($diesc));
        // echo $diesc;
    }
    /**************************************** Length End*****************************************/
    public function add_institute()
    {
        $data['title'] = display('add_institute');
        $data['institute'] = '';
        $this->form_validation->set_rules('institute_name', display('institute_name'), 'required');
        $this->form_validation->set_rules('country_id', display('country_name'), 'required');
        if (!empty($_FILES['profile_image']['name'])) {
            //$this->load->library("aws");
            $_FILES['userfile']['name'] = $_FILES['profile_image']['name'];
            $_FILES['userfile']['type'] = $_FILES['profile_image']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['profile_image']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['profile_image']['error'];
            $_FILES['userfile']['size'] = $_FILES['profile_image']['size'];
            $image = $_FILES['userfile']['name'];
            $path =  "uploads/" . $image;
            $ret = move_uploaded_file($_FILES['userfile']['tmp_name'], $path);
            //if($ret){
            //    $this->aws->upload($path);
            //}
        } else {
            $path = $this->input->post('profile_images', true);
        }
        if (!empty($_FILES['agreement_doc']['name'])) {
            $_FILES['userfile']['name'] = $_FILES['agreement_doc']['name'];
            $_FILES['userfile']['type'] = $_FILES['agreement_doc']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['agreement_doc']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['agreement_doc']['error'];
            $_FILES['userfile']['size'] = $_FILES['agreement_doc']['size'];
            $image1 = $_FILES['userfile']['name'];
            $path1 =  "uploads/" . $image1;
            $ret1 = move_uploaded_file($_FILES['userfile']['tmp_name'], $path1);
            //if($ret1){
            //    $this->aws->upload($path1);
            //}
        } else {
            $path1 = $this->input->post('agreement_docs', true);
        }
        $data['institute'] = (object) $postData = [
            'institute_id' => $this->input->post('institute_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'institute_name' => $this->input->post('institute_name', true),
            'contact_name' => $this->input->post('contact_name', true),
            'contact_number' => $this->input->post('contact_number', true),
            'address' => $this->input->post('address', true),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
            'profile_image' => $path,
            'agreement_comision' => $this->input->post('agreement_comision', true),
            'agreement_doc' => $path1,
            'from_date' => $this->input->post('from_date', true),
            'to_date' => $this->input->post('to_date', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'updated_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            // print_r($postData);exit;
            if (empty($this->input->post('institute_id'))) {
                if (user_role('e39') == true) {
                }
                if ($this->Institute_model->insertRow($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('f30') == true) {
                }
                if ($this->Institute_model->updateRow($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/institutelist');
        } else {
            if (user_role('f31') == true) {
            }
            $data['country'] = $this->location_model->country();
            $data['content'] = $this->load->view('institute/institute_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_institute($institute_id = null)
    {
        if (user_role('f30') == true) {
        }
        $data['title'] = display('update_institute');
        $data['institute'] = $this->Institute_model->readRow($institute_id);
        $data['country'] = $this->location_model->country();
        $data['content'] = $this->load->view('institute/institute_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_institute($paramId = null)
    {
        if (user_role('f32') == true) {
        }
        if ($this->Institute_model->deleteInstitute($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/institutelist');
    }
    /*    public function datasourcelist() {
        if (user_role('20') == true) {}
        $data['title'] = display('datasource_list');
        $data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['content'] = $this->load->view('datasource/datasource_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }*/
    public function datasourcelist()
    {
        if (user_role('A61') == true) {
        }
        $data['title'] = display('datasource_list');
        $data['all_user'] = $this->User_model->all_user();
        $data['products'] = $this->dash_model->get_user_product_list();
        $data['datasource_list'] = $this->Datasource_model->datasourcelist2();
        $data['datasource_list2'] = $this->Datasource_model->datasourcelist();
        $data['content'] = $this->load->view('datasource/datasource_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    /********************************************create CSV file*********************************/
    public function createcsv($pd,$for=0)
    {
        $comp_id = $this->session->userdata('companey_id');
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="Upload_sample.csv"');
        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');
        // create a file pointer connected to the output stream
        $file = fopen('php://output', 'w');
        $input = array();
        $this->db->select('*');
        $this->db->from('tbl_input');
        $this->db->where('page_id', $for);        
        $this->db->where('status', 1);        
        $this->db->where("(process_id=$pd AND company_id=$comp_id)");                
        $this->db->order_by('form_id', 'asc');
        $q = $this->db->get()->result();        
        if($for == 0){
            $static = array('Mobile No', 'Other_number', 'Email Address', 'Company name', 'Sales Branch', 'Client Name', 'Contact', 'Name prefixed', 'First Name', 'Last Name', 'Designation', 'Gender', 'Lead Source', 'Process', 'State', 'City','Address','Client Type','Type Of Load / Business','Industries','Remark');
        }else if($for == 4){
            $static = array(display('tracking_no'),'Name','Phone','Email','GC Date','Bkg Branch','Bkg Region','Delivery Branch','Dly Type','Pay Mode','Charged Weight','No Of Articles','Actual Weight','Consignor Name','Consignor Tel No','Consignor Mobile No','Consignee Name','Consignee Tel No','Consignee Mobile No','Current Status','Vehicle No');
        }else{
            $static = array(display('tracking_no'),'Name','Mobile No','Email');
        }
        
        if (!empty($q)) {
            foreach ($q as $value) {
                $daynamic[] = $value->input_label;
            }
            $allcoulmn = array_merge($static, $daynamic);
            // send the column headers
            fputcsv($file, $allcoulmn);
        } else {
            fputcsv($file, $static);
        }
        exit();
    }
    /**************************end CSV create*********************/
    public function add_datasource()
    {
       
        $data['title'] = display('add_datasource');
        $this->form_validation->set_rules('datasource_name', display('datasource_name'), 'required');
        $data['datasource'] = (object) $postData = [
            'datasource_id' => $this->input->post('datasource_id', true),
            'datasource_name' => $this->input->post('datasource_name', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'comp_id' => $this->session->userdata('companey_id'),
            'updated_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('datasource_id'))) {
                
                if ($this->Datasource_model->insertRow($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
               
                if ($this->Datasource_model->updateRow($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/datasourcelist');
        } else {
            $data['country'] = $this->location_model->productcountry();
            $data['content'] = $this->load->view('datasource/datasource_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_datasource($datasource_id = null)
    {
       
        $data['title'] = display('update_datasource');
        $data['datasource'] = $this->Datasource_model->readRow($datasource_id);
        $data['country'] = $this->location_model->productcountry();
        $data['content'] = $this->load->view('datasource/datasource_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_datasource($paramId = null)
    {
       
        if ($this->Datasource_model->deleteDatasource($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/datasourcelist');
    }
    public function taskstatuslist()
    {
        if (user_role('c39') == true) {
        }
        $data['title'] = display('taskstatus_list');
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        $data['content'] = $this->load->view('taskstatus/taskstatus_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function add_taskstatus()
    {
        
        $data['title'] = display('add_taskstatus');
        $this->form_validation->set_rules('taskstatus_name', display('taskstatus_name'), 'required');
        $data['formdata'] = (object) $postData = [
            'taskstatus_id' => $this->input->post('taskstatus_id', true),
            'taskstatus_name' => $this->input->post('taskstatus_name', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'comp_id' => $this->session->userdata('companey_id'),
            'updated_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('taskstatus_id'))) {
                if (user_role('c37') == true) {
                }
                if ($this->Taskstatus_model->insertRow($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('c38') == true) {
                }
                if ($this->Taskstatus_model->updateRow($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/taskstatuslist');
        } else {
            if (user_role('c39') == true) {
            }
            $data['content'] = $this->load->view('taskstatus/taskstatus_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_taskstatus($taskstatus_id = null)
    {
        if (user_role('c38') == true) {
        }
        $data['title'] = display('update_taskstatus');
        $data['formdata'] = $this->Taskstatus_model->readRow($taskstatus_id);
        $data['content'] = $this->load->view('taskstatus/taskstatus_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_taskstatus($paramId = null)
    {
        if (user_role('d30') == true) {
        }
        if ($this->Taskstatus_model->deleteTaskstatus($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/taskstatuslist');
    }
    public function centerlist()
    {
        if (user_role('d33') == true) {
        }
        $data['title'] = display('center_list');
        $data['center_list'] = $this->Center_model->centerlist();
        $data['content'] = $this->load->view('center/center_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function add_center()
    {
        $data['title'] = display('add_center');
        $data['center'] = '';
        $this->form_validation->set_rules('center_name', display('center_name'), 'required');
        $this->form_validation->set_rules('country_id', display('country_name'), 'required');
        $data['center'] = (object) $postData = [
            'center_id' => $this->input->post('center_id', true),
            'comp_id' => $this->session->companey_id,
            'center_name' => $this->input->post('center_name', true),
            'contact_name' => $this->input->post('contact_name', true),
            'contact_number' => $this->input->post('contact_number', true),
            'address' => $this->input->post('address', true),
            'country_id' => $this->input->post('country_id', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'updated_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('center_id'))) {
                if (user_role('d31') == true) {
                }
                if ($this->Center_model->insertRow($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('d32') == true) {
                }
                if ($this->Center_model->updateRow($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            
            redirect('lead/centerlist');
        } else {
            if (user_role('d33') == true) {
            }
            $data['country'] = $this->location_model->country();
            $data['content'] = $this->load->view('center/center_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_center($center_id = null)
    {
        if (user_role('d32') == true) {
        }
        $data['title'] = display('update_center');
        $data['center'] = $this->Center_model->readRow($center_id);
        $data['country'] = $this->location_model->country();
        $data['content'] = $this->load->view('center/center_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_center($paramId = null)
    {
        if (user_role('d34') == true) {
        }
        if ($this->Center_model->deleteCenter($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/centerlist');
    }
    public function subsourcelist()
    {
        if (user_role('a39') == true) {
        }
        $data['title'] = display('subsource_list');
        $data['subsource_list'] = $this->SubSource_model->subsourcelist();
        $data['content'] = $this->load->view('subsource/subsource_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function get_subsource_by_source()
    {
        $id = $this->input->post('src_id');
        $subid = $this->input->post('sub_src_id');
        $res = $this->SubSource_model->get_subsource($id);
        $opt = "<option>---Select Subsource---</option>";
        foreach ($res as $result) {
            if ($subid == $result['subsource_id']) {
                $opt .= "<option value='" . $result['subsource_id'] . "' selected>" . ucwords($result['subsource_name']) . "</option>";
            } else {
                $opt .= "<option value='" . $result['subsource_id'] . "'>" . ucwords($result['subsource_name']) . "</option>";
            }
        }
        echo $opt;
    }
	
	public function get_compname_by_id()
    {
        $id = $this->input->post('sales_branch');
        $res = $this->SubSource_model->get_company_name($id);       
        echo $res->branch_name;
    }
	
    public function description()
    {
        if (user_role('35') == true) {
        }
        $data['title'] = display('Description_list');
        $data['description_list'] = $this->SubSource_model->descriptionlist();
        //print_r($data['description_list']); exit();
        $data['content'] = $this->load->view('lead_description', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function add_subsource()
    {

        $data['title'] = display('add_subsource');
        $data['subsource'] = '';
        $this->form_validation->set_rules('subsource_name', display('subsource_name'), 'required');
        $this->form_validation->set_rules('lead_source_id', display('lead_source'), 'required');
        $data['subsource'] = (object) $postData = [
            'subsource_id' => $this->input->post('subsource_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'subsource_name' => $this->input->post('subsource_name', true),
            'lead_source_id' => $this->input->post('lead_source_id', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'updated_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('subsource_id'))) {
                if (user_role('a37') == true) {
                }
                if ($this->SubSource_model->insertRow($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('a38') == true) {
                }
                if ($this->SubSource_model->updateRow($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/subsourcelist');
        } else {
            if (user_role('a37') == true) {
            }
            $data['lead_source_list'] = $this->SubSource_model->all_lead_source();
            $data['content'] = $this->load->view('subsource/subsource_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }

    public function add_description()
    {
        
        $data['title'] = display('add_description');
        $data['description'] = '';
        $this->form_validation->set_rules('description_name', display('description_name'), 'required');
        $this->form_validation->set_rules('lead_stage_id[]', display('lead_stage_id'), 'required');
        $stage_list = '';
        if($_POST)
        {
            $stage_list = implode(',', $this->input->post('lead_stage_id', true));
        }
        $data['description'] = (object) $postData = [
            'id' => $this->input->post('description_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'lead_stage_id' => $stage_list,
            'description' => $this->input->post('description_name', true),
            'status' => $this->input->post('status', true),
            'created_by' => $this->session->userdata('user_id'),
            'updated_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        //print_r($postData);exit;
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('description_id'))) {
                if (user_role('36') == true) {
                }
                if ($this->SubSource_model->insertRowdeis($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('37') == true) {
                }
            
                if ($this->SubSource_model->updateRowdes($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/description');
        } else {
            if (user_role('36') == true) {
            }
        
            $data['lead_description_list'] = $this->SubSource_model->all_stage_list();
            $data['content'] = $this->load->view('description_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function get_process_bystage()
    {
        $id = $this->input->post('id');
        $this->db->select('tbl_product.product_name');
        $this->db->from('tbl_product');
        $this->db->join('lead_stage', 'tbl_product.sb_id=lead_stage.process_id');
        $this->db->where('lead_stage.stg_id', $id);
        $this->db->where('lead_stage.comp_id', $this->session->companey_id);
        $res = $this->db->get()->row();
        // print_r($res);exit();
        echo json_encode($res);
    }
    public function edit_subsource($subsource_id = null)
    {
        if (user_role('a38') == true) {
        }
        $data['title'] = display('update_subsource');
        $data['subsource'] = $this->SubSource_model->readRow($subsource_id);
        $data['lead_source_list'] = $this->SubSource_model->all_lead_source();
        $data['content'] = $this->load->view('subsource/subsource_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function edit_discription($description_id = null)
    {
        if (user_role('37') == true) {
        }
        $data['title'] = display('update_deiscription');
        $data['description'] = $this->SubSource_model->readRowdes($description_id);
        $data['lead_description_list'] = $this->SubSource_model->all_stage_des();
        //print_r( $data['description'] ); exit();
        $data['content'] = $this->load->view('description_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_subsource($paramId = null)
    {
        if (user_role('b30') == true) {
        }
        if ($this->SubSource_model->deleteSubsource($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/subsourcelist');
    }
    public function delete_description($paramId = null)
    {
        if (user_role('38') == true) {
        }
        if ($this->SubSource_model->delete_description($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/description');
    }
    public function addnewkyc()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $doc_file = $this->fileupload->do_upload(
                'assets/kyc/',
                'doc_file'
            );
            $unique_number = $this->input->post('unique_number', true);
            $enquiry_id = $this->input->post('kyc_enquiry_id', true);
            $postData = [
                'unique_number' => $unique_number,
                'doc_name' => $this->input->post('doc_name', true),
                'doc_number' => $this->input->post('doc_number', true),
                'doc_validity' => $this->input->post('doc_validity', true),
                'doc_file' => $doc_file,
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d'),
                'updated_date' => date('Y-m-d')
            ];
            if (user_role('20') == true) {
            }
            if ($this->Kyc_model->insertRow($postData)) {
                $this->session->set_flashdata('message', 'KYC document added successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }

    public function addnewwork()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $unique_number = $this->input->post('work_unique_number', true);
            $enquiry_id = $this->input->post('work_enquiry_id', true);
            $postData = [
                'unique_number' => $unique_number,
                'company' => $this->input->post('company', true),
                'designation' => $this->input->post('designation', true),
                'start_date' => $this->input->post('start_date', true),
                'end_date' => $this->input->post('end_date', true),
                'current_ctc' => $this->input->post('current_ctc', true),
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d'),
                'updated_date' => date('Y-m-d')
            ];
            if (user_role('20') == true) {
            }
            if ($this->Workhistory_model->insertRow($postData)) {
                $this->session->set_flashdata('message', 'Work history added successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }
    public function addnewedu()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $unique_number = $this->input->post('edu_unique_number', true);
            $enquiry_id = $this->input->post('edu_enquiry_id', true);
            $postData = [
                'unique_number' => $unique_number,
                'title' => $this->input->post('title', true),
                'university' => $this->input->post('university', true),
                'passing_year' => $this->input->post('passing_year', true),
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d'),
                'updated_date' => date('Y-m-d')
            ];
            if (user_role('20') == true) {
            }
            if ($this->Education_model->insertRow($postData)) {
                $this->session->set_flashdata('message', 'Education added successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }          
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }
    public function addnewsprof()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $unique_number = $this->input->post('sprof_unique_number', true);
            $enquiry_id = $this->input->post('sprof_enquiry_id', true);
            $postData = [
                'unique_number' => $unique_number,
                'title' => $this->input->post('title', true),
                'profile' => $this->input->post('profile', true),
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d'),
                'updated_date' => date('Y-m-d')
            ];
            if (user_role('20') == true) {
            }
            if ($this->SocialProfile_model->insertRow($postData)) {
                $this->session->set_flashdata('message', 'Social profile added successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
         
        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }
    public function addnewtravel()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $unique_number = $this->input->post('travel_unique_number', true);
            $enquiry_id = $this->input->post('travel_enquiry_id', true);
            $postData = [
                'unique_number' => $unique_number,
                'country_id' => $this->input->post('country_id', true),
                'travel_date' => $this->input->post('travel_date', true),
                'visa_type' => $this->input->post('visa_type', true),
                'dfrom_date' => $this->input->post('dfrom_date', true),
                'dto_date' => $this->input->post('dto_date', true),
                'is_rejected' => $this->input->post('is_rejected', true),
                'reject_reason' => $this->input->post('reject_reason', true),
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d'),
                'updated_date' => date('Y-m-d'),
                'status' => 1
            ];
            if (user_role('20') == true) {
            }
            if ($this->Travelhistory_model->insertRow($postData)) {
                $this->session->set_flashdata('message', 'Travel history added successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
         
        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }
    public function addnewmember()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $unique_number = $this->input->post('mem_unique_number', true);
            $enquiry_id = $this->input->post('mem_enquiry_id', true);
            $postData = [
                'unique_number' => $unique_number,
                'name' => $this->input->post('name', true),
                'contact_number' => $this->input->post('contact_number', true),
                'contact_email' => $this->input->post('contact_email', true),
                'country_id' => $this->input->post('country_id', true),
                'relationship' => $this->input->post('relationship', true),
                'visa_status' => $this->input->post('visa_status', true),
                'they_help' => $this->input->post('they_help', true),
                'created_by' => $this->session->userdata('user_id'),
                'updated_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d'),
                'updated_date' => date('Y-m-d'),
                'status' => 1
            ];
            if (user_role('20') == true) {
            }
            if ($this->Closefemily_model->insertRow($postData)) {
                $this->session->set_flashdata('message', 'Member added successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
         
        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }
    /*****************************************************************notification start********************************************************************/
    function alertList()
    {
        $this->db->select('*');
        $this->db->where('create_by', $this->session->user_id);
        $this->db->where('status', '0');
        $alertData = $this->db->get('query_response')->result();
        if (!empty($alertData)) {
            $myalertdate = array();
            $myalertmsg = array();
            if (!empty($alertData)) {
                foreach ($alertData as $alert) {
                    $myalertdate[] = $alert->task_date;
                    $myalerttime = $alert->task_time;
                    $myalertmsg[] = $alert->task_remark;
                    $myalertperson = $alert->contact_person;
                    $myalertmobile = $alert->mobile;
                    $myalertid = $alert->resp_id;
                }
            }
            $ttt = explode(' ', $myalerttime);
            $t = $ttt[0];
            $date = date('d-m-Y H:i:s');
            $d = explode(' ', $date);
            $dd = $d[0];
            $tt = $d[1];
        }
        if ((!empty($alertData)) && in_array($dd, $myalertdate) && (strtotime($tt) == strtotime($t))) {
            $status = 1;
            $popup = '
<h4 style="color:red;">' . $myalertperson . '</h4>
<h5 style="color:red;">' . $myalertmobile . '</h5>
                <p style="color:#000;">' . $alert->task_remark . '</p>
                </div>
            ';
            $status_id = '<span  onclick="hide(' . $myalertid . ')"  style="height:0px;width:0px;float:right;margin-top:-30px;"><i class="fa fa-times-circle-o" aria-hidden="true" style="height:20px;width:20px;color:red;margin-left:-5px;"></i>
</span>';
            echo json_encode(array('status1' => $status, 'status_data' => $popup, 'status_id' => $status_id));
        } else {
            echo 0;
        }
    }
    public function alertstatus()
    {
        $this->db->set('status', 1);
        $this->db->where('resp_id', $this->uri->segment(3));
        $this->db->update('query_response');
    }
    /*****************************************************************notification end********************************************************************/
    function lead_search() // route created for this function
    {
        $filter = (!empty($_GET["search"])) ? trim($_GET["search"]) : "";
		//print_r($filter);exit;
        if (empty($filter)) {
            redirect(base_url());
        }else{
            $global_search    =   get_sys_parameter('master_search_global', 'COMPANY_SETTING'); // get master search setting
            $comp_id = $this->session->companey_id;
    
            if (!empty($filter)) {
    
                $qpart = " (enq.client_name LIKE '%{$filter}%' OR cmpny.company_name LIKE '%{$filter}%' OR enq.Enquery_id LIKE '%{$filter}%' OR enq.email LIKE '%{$filter}%' OR enq.phone LIKE '%{$filter}%' OR enq.name LIKE '%{$filter}%' OR enq.lastname LIKE '%{$filter}%' OR CONCAT(enq.name,' ',enq.lastname) LIKE '%{$filter}%' OR CONCAT(enq.name_prefix,' ',enq.name,' ',enq.lastname) LIKE '%{$filter}%' OR usr.s_display_name LiKE '%{$filter}%' OR usr.last_name LiKE '%{$filter}%' OR asgn.s_display_name LiKE '%{$filter}%' OR asgn.last_name LiKE '%{$filter}%' OR tbl_client_contacts.c_name LiKE '%{$filter}%' OR  tbl_client_contacts.contact_number LiKE '%{$filter}%') AND";
            } else {
                $qpart = "";
            }
    
            $retuser   = $this->common_model->get_categories($this->session->user_id);
            $impuser   = implode(",", $retuser);
    
            $qry    = "SELECT  cmpny.company_name,enq.*, concat(usr.s_display_name,' ' , usr.last_name) as username,  concat(asgn.s_display_name,' ' , asgn.last_name) as asignuser  FROM enquiry enq
                                                LEFT JOIN tbl_admin usr ON usr.pk_i_admin_id = enq.created_by 
                                                LEFT JOIN tbl_client_contacts ON tbl_client_contacts.client_id = enq.enquiry_id 
                                                LEFT JOIN tbl_admin asgn ON asgn.pk_i_admin_id = enq.aasign_to
                                                LEFT JOIN tbl_company cmpny ON cmpny.id = enq.company												
                                                WHERE $qpart  ";
            if ($global_search) {
                $qry .= "enq.comp_id=$comp_id";
            } else {
                $qry .= "(enq.created_by  IN ($impuser) OR enq.aasign_to  IN ($impuser))";
            }
        $data["result"] = $this->db->query($qry)->result();
        $this->load->model('Branch_model');
        $data["filter"]  = $filter;
        $data['branch_lists']=$this->Branch_model->all_sales_branch();
        $data['region_lists']=$this->Branch_model->all_sales_region();
        $data['area_lists']=$this->Branch_model->all_sales_area();
        $data['dept_lists']=$this->User_model->all_sales_dept();
        $data['created_bylist'] = $this->User_model->read();
        $data['title']   = "Lead Search";
        $data['content'] = $this->load->view('lead_search', $data, true);
        $this->load->view('layout/main_wrapper', $data);
        }
        
    }
    function get_number_details()
    {
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $number = $this->input->post("number");
        $company_id = $this->session->companey_id;
        $where = '';
        $this->db->select("enquiry.name_prefix,enquiry.enquiry_id,enquiry.company,enquiry.created_by,enquiry.drop_status,enquiry.status,enquiry.aasign_to,enquiry.name,enquiry.lastname,enquiry.email,enquiry.phone,enquiry.address,DATE_FORMAT(enquiry.created_date,'%d-%m-%Y') as created_date,enquiry.enquiry_source,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_datasource.datasource_name,tbl_product.product_name as product_name,tbl_admin.s_display_name as member_name,tbl_admin.last_name as lname,t2.s_display_name as assign_to_name,t2.last_name as assign_lname");
        $this->db->from("enquiry");
        $this->db->join('tbl_product', 'enquiry.product_id = tbl_product.sb_id', 'left');
        $this->db->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid', 'left');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id = tbl_datasource.datasource_id', 'left');
        $this->db->join('tbl_admin', 'enquiry.created_by=tbl_admin.pk_i_admin_id', 'left');
        $this->db->join('tbl_admin t2', 'enquiry.aasign_to=t2.pk_i_admin_id', 'left');
        $where .= "enquiry.phone=" . $number;
        $where .= " AND  enquiry.created_by IN (" . implode(',', $all_reporting_ids) . ")";
        $this->db->where($where);
        $result = $this->db->get()->result();
        // echo $this->db->last_query();exit;
        echo json_encode($result);
    }

    //Switch box master....
    public function product_list()
    {
        if (user_role('c31') == true) {
        }
        $data['title'] = 'Process';
        $data['nav1'] = 'nav1';
        $data['get_users'] = $this->dash_model->area_list();
        $data['product_list'] = $this->dash_model->all_process_list();
        $data['content'] = $this->load->view('product-list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    //Master setup for Customer type or channel partner..
    public function load_customer_channel_mater()
    {
        if (user_role('b37') == true) {
        }
        $data['page_title'] = 'Customer type';
        $data['nav1'] = 'nav2';
        $data['customer_types'] = $this->enquiry_model->customers_types();
        $data['name_prefix'] = $this->enquiry_model->name_prefix_list();
        $data['content'] = $this->load->view('master_enquiry_type', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function select_state_by_city()
    {
        $city = $this->input->post('city_id');
        echo json_encode($this->Leads_Model->all_csc($city));
    }
    public function get_lead_stage()
    {
        $id = $this->input->post('id');
        $res = $this->Leads_Model->get_leadstage_name($id);
        $opt = "<option>---Select Stage---</option>";
        foreach ($res as $result) {
            $opt .= "<option value='" . $result['stg_id'] . "'>" . ucwords($result['lead_stage_name']) . "</option>";
        }
        echo $opt;
        // exit();
    }

    public function delete_tag($id){
        $this->db->where('id',$id);
        $this->db->delete('tags');
        $this->session->set_flashdata('message', 'Tag Deleted Successfully');
        redirect('lead/tags');
    }
    public function tags(){               
        if (!empty($_POST)) {            
            $title = $this->input->post('title');
            $data = array(
                'title' => $title,
                'comp_id' => $this->session->userdata('companey_id'),
                'color' => $this->input->post('color'),
                'created_by' => $this->session->user_id,
            );
            $insert_id = $this->db->insert('tags',$data);
            $this->session->set_flashdata('message', 'Tag Added Successfully');
            redirect('lead/tags');
        }
        $data['title'] = 'Tags List';
        $data['tags'] = $this->enquiry_model->get_tags();
        $data['content'] = $this->load->view('tags_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }

    public function add_course()
    {
        $data['title'] = display('add_course');
        $data['institute'] = '';
        $this->form_validation->set_rules('course_name', display('Course_name'), 'required');
        $this->form_validation->set_rules('institute_id', display('institute_name'), 'required');
        if (!empty($_FILES['course_image']['name'])) {
            //$this->load->library("aws");
            $_FILES['userfile']['name'] = $_FILES['course_image']['name'];
            $_FILES['userfile']['type'] = $_FILES['course_image']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['course_image']['tmp_name'];
            $_FILES['userfile']['error'] = $_FILES['course_image']['error'];
            $_FILES['userfile']['size'] = $_FILES['course_image']['size'];
            $image = $_FILES['userfile']['name'];
            $path =  "uploads/" . $image;
            $ret = move_uploaded_file($_FILES['userfile']['tmp_name'], $path);
            //if($ret){
            //    $this->aws->upload($path);
            //}
        } else {
            $path = $this->input->post('course_images', true);
        }
        $data['course'] = (object) $postData = [
            'crs_id' => $this->input->post('crs_id', true),
            'institute_id' => $this->input->post('institute_id', true),
            'course_name' => $this->input->post('course_name', true),
            'course_ielts' => $this->input->post('course_ielts', true),
            'course_image' => $path,
            'course_rating' => $this->input->post('course_rating', true),
            'course_discription' => $this->input->post('course_discription', true),
            'tuition_fees' => $this->input->post('tuition_fees', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'discipline_id' => $this->input->post('discipline', true),
            'level_id' => $this->input->post('level', true),
            'length_id' => $this->input->post('length', true),
            'created_by' => $this->session->userdata('user_id'),
            'updated_by' => $this->session->userdata('user_id'),
            'status' => $this->input->post('status', true),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        //print_r($postData);exit;
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('crs_id'))) {
                if (user_role('30') == true) {
                }
                if ($this->Institute_model->insertRowcrs($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('30') == true) {
                }
                if ($this->Institute_model->updateRowcrs($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/courselist');
        } else {
            $data['courses'] = $this->Institute_model->findcourse();
            $data['institute'] = $this->Institute_model->findinstitute();
            $data['discipline'] = $this->location_model->find_discipline();
            $data['level'] = $this->location_model->find_level();
            $data['length'] = $this->location_model->find_length();
            $data['content'] = $this->load->view('institute/course_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function add_crs()
    {

        $data['title'] = display('add_course');
        $data['institute'] = '';
        $this->form_validation->set_rules('course_name', display('Course_name'), 'required');
        $data['crs'] = (object) $postData = [
            'id' => $this->input->post('crs_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'course_name' => $this->input->post('course_name', true),
            'created_by' => $this->session->userdata('user_id'),
            'status' => $this->input->post('status', true),
            'created_date' => date('Y-m-d'),
            'updated_date' => date('Y-m-d')
        ];
        //print_r($postData);exit;
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('crs_id'))) {
                if (user_role('f37') == true) {
                }
                if ($this->Institute_model->insertcrs($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('f38') == true) {
                }
                if ($this->Institute_model->updatecrs($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/crslist');
        } else {
            if (user_role('f39') == true) {
            }
            $data['content'] = $this->load->view('institute/crs_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function add_sub_crs()
    {
       
        $data['title'] = display('add_sub_crs');
        $data['institute'] = '';
        $this->form_validation->set_rules('sub_course', display('Sub Course'), 'required');
        $data['subcrs'] = (object) $postData = [
            'id' => $this->input->post('sub_crs_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'sub_course' => $this->input->post('sub_course', true),
            'course_name' => $this->input->post('course_name', true),
            'created_by' => $this->session->userdata('user_id'),
            'status' => $this->input->post('status', true),
            'created_date' => date('Y-m-d')
        ];
        //print_r($postData);exit;
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('sub_crs_id'))) {
                if (user_role('g31') == true) {
                }
                if ($this->Institute_model->insertsubcrs($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('g32') == true) {
                }
                if ($this->Institute_model->updatesubcrs($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/sub_course');
        } else {
            if (user_role('g31') == true) {
            }
            $data['cource'] = $this->Institute_model->crsmstrlist();
            $data['content'] = $this->load->view('institute/sub_crs_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function add_video()
    {
        
        $data['title'] = display('add_course');
        $data['institute'] = '';
        $this->form_validation->set_rules('title_name', 'Title Name', 'required');
        $this->form_validation->set_rules('link_name', 'Link Name', 'required');
        $this->form_validation->set_rules('discription_name', 'Description', 'required');
        $data['vid'] = (object) $postData = [
            'id' => $this->input->post('vid_id', true),
            'comp_id' => $this->session->userdata('companey_id'),
            'title' => $this->input->post('title_name', true),
            'link' => $this->input->post('link_name', true),
            'des' => $this->input->post('discription_name', true),
            'meta_key' => $this->input->post('key_name', true),
            'created_by' => $this->session->userdata('user_id'),
            'status' => $this->input->post('status', true),
            'created_date' => date('Y-m-d')
        ];
        //print_r($postData);exit;
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('vid_id'))) {
                if (user_role('g35') == true) {
                }
                if ($this->Institute_model->insertvid($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if (user_role('g36') == true) {
                }
                if ($this->Institute_model->updatevid($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect('lead/vidlist');
        } else {
            if (user_role('g35') == true) {
            }
            $data['title'] = display('add_vid');
            $data['content'] = $this->load->view('institute/vid_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit_course($crs_id = null)
    {
        if (user_role('f38') == true) {
        }
        $data['title'] = display('update_course');
        $data['courses'] = $this->Institute_model->findcourse();
        $data['discipline'] = $this->location_model->find_discipline();
        $data['level'] = $this->location_model->find_level();
        $data['length'] = $this->location_model->find_length();
        $data['course'] = $this->Institute_model->readRowcrs($crs_id);
        $data['institute'] = $this->Institute_model->findinstitute();
        $data['content'] = $this->load->view('institute/course_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function edit_crs($crs_id = null)
    {
        if (user_role('f34') == true) {
        }
        $data['title'] = display('update_course');
        $data['crs'] = $this->Institute_model->readcrs($crs_id);
        $data['content'] = $this->load->view('institute/crs_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function edit_sub_crs($crs_id = null)
    {
        if (user_role('g32') == true) {
        }
        $data['title'] = display('update_Sub_course');
        $data['subcrs'] = $this->Institute_model->readsubcrs($crs_id);
        $data['cource'] = $this->Institute_model->crsmstrlist();
        $data['content'] = $this->load->view('institute/sub_crs_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function edit_vid($vid_id = null)
    {
        if (user_role('g36') == true) {
        }
        $data['title'] = display('update_vedio');
        $data['vid'] = $this->Institute_model->readvid($vid_id);
        $data['content'] = $this->load->view('institute/vid_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_course($paramId = null)
    {
        if (user_role('g30') == true) {
        }
        if ($this->Institute_model->deletecourse($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/courselist');
    }
    public function delete_crs($paramId = null)
    {
        if (user_role('f36') == true) {
        }
        if ($this->Institute_model->deletecrs($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/crslist');
    }
    public function delete_sub_crs($paramId = null)
    {
        if (user_role('g34') == true) {
        }
        if ($this->Institute_model->deletesubcrs($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/sub_course');
    }
    public function delete_vid($paramId = null)
    {
        if (user_role('3204') == true) {
        }
        if ($this->Institute_model->deletevid($paramId)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/vidlist');
    }
    ///////////////// FAQ SECTION START////////////////////
    public function faq()
    {

        $data['nav1'] = 'nav2';
        if (!empty($_POST)) {
            if (user_role('g39') == true) {
            }
            $faq_title = $this->input->post('faq_title');
            $faq_dscptn = $this->input->post('faq_dscptn');
            $data = array(
                'que_type'   => $faq_title,
                'answer'   => $faq_dscptn,
                'comp_id' => $this->session->userdata('companey_id'),
                'created_by' => $this->session->userdata('user_id'),
                'status' => '1'
            );
            $insert_id = $this->Leads_Model->faq_add($data);
            $this->session->set_flashdata('SUCCESSMSG', 'Lead Stage Add Successfully');
            redirect('lead/faq');
        }
        if (user_role('h31') == true) {
        }
        $data['all_faq'] = $this->Leads_Model->faq_select();
        $data['title'] = 'FAQ';
        $data['content'] = $this->load->view('faq/faq_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete_faq($faq_id = null)
    {
        if (user_role('h32') == true) {
        }
        if ($this->Leads_Model->delete_faq($faq_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('lead/faq');
    }
    public function update_faq()
    {
        if (!empty($_POST)) {
            if (user_role('h30') == true) {
            }
            $faq_title = $this->input->post('faq_title');
            $faq_dscptn = $this->input->post('faq_dscptn');
            $faq_id = $this->input->post('faq_id');
            $this->db->set('que_type', $faq_title);
            $this->db->set('answer', $faq_dscptn);
            $this->db->where('id', $faq_id);
            $this->db->update('tbl_faq');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('lead/faq');
        }
    }
    ///////////////// FAQ SECTION END////////////////////
}
