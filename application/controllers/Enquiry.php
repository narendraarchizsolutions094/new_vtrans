<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Enquiry extends CI_Controller
{
    public function __construct()
    { 
        parent::__construct();
        $this->load->model(
            array('Leads_Model', 'setting_model', 'website/home_model', 'schedule_model', 'enquiry_model', 'dashboard_model', 'Task_Model', 'User_model', 'location_model', 'Message_models', 'Institute_model', 'Datasource_model', 'Taskstatus_model', 'dash_model', 'Center_model', 'SubSource_model', 'Kyc_model', 'Education_model', 'SocialProfile_model', 'Closefemily_model', 'form_model', 'Doctor_model','message_models')
        );
        $this->load->library('email');
        $this->load->library('user_agent');
        $this->lang->load("activitylogmsg", "english"); 
        $apiarr = explode("/", $_SERVER['REQUEST_URI']);
        if (in_array("viewapi", $apiarr)) {
        } else if (in_array("viewapi", $apiarr)) {
        } else if (in_array("re_login", $apiarr)) {
        } else {
            if (empty($this->session->user_id)) {
                redirect('login');
            }
        }
    }
    public function current_time(){
        echo "time is " . date("Y-m-d h:i:sa");
    }

    public function add_enquery_comission($enq_code) 
    {
        $enq_code = base64_decode($enq_code);
        $this->form_validation->set_rules('amtdisb', 'Amount Disbursed', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $amtdisb       =   $this->input->post('amtdisb');
            $comission    =   $this->input->post('comission');
            $dateofpay     =   $this->input->post('dateofpay');
            $tds          =   $this->input->post('tds');
            $amtpaid          =   $this->input->post('amtpaid');
            $payoutper         =   $this->input->post('payoutper');
            $month         =   $this->input->post('month');

            $amt_data = array(
                'Enquiry_code'  => $enq_code,
                'amt_disb'      => $amtdisb,
                'comission'     => $comission,
                'date_of_payment'    => $dateofpay,
                'tds'           => $tds,
                'amt_paid'      => $amtpaid,
                'payout_per'    => $payoutper,
                'month'         => $month,
                'created_by'    => $this->session->userdata('user_id'),
            );
            if ($this->input->post('enq_com_id')) {
                $this->db->where('id', $this->input->post('enq_com_id'));
                $ins    =   $this->db->update('tbl_comission', $amt_data);
                $msg = 'updated successfully';
            } else {
                $ins    =   $this->db->insert('tbl_comission', $amt_data);
                $msg = ' added successfully';
            }
            if ($ins) {
                echo json_encode(array('status' => true, 'msg' => $msg));
            } else {
                echo json_encode(array('status' => false, 'msg' => 'Something went wrong!'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => validation_errors()));
        }
    }
    public function get_update_enquery_comission_content()
    {
        $id            =   $this->input->post('id');
        $Enquiry_id    =   $this->input->post('Enquiry_id');
        $this->db->where('id', $id);
        $data['comission_data']    =   $this->db->get('tbl_comission')->row_array();
        $data['Enquiry_id'] =   $Enquiry_id;
        $data['details']    =   $this->enquiry_model->enquiry_by_code($Enquiry_id);
        $content    =   $this->load->view('comission_modal_content', $data, true);
        echo $content;
    }
    public function move_to_client()
    {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id');
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
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $enq = $this->enquiry_model->enquiry_by_id($key);
                    // print_r($enq);
                    $data = array(
                        // 'adminid' => $enq->created_by,
                        'ld_name' => $enq->name,
                        'ld_email' => $enq->email,
                        'ld_mobile' => $enq->phone,
                        'lead_code' => $enq->Enquery_id,
                        // 'city_id' => $enq->city_id,
                        // 'state_id' => $enq->state_id,
                        // 'country_id' => $enq->country_id,
                        // 'region_id' => $enq->region_id,
                        // 'territory_id' => $enq->territory_id,
                        'ld_created' => $date,
                        'ld_for' => $enq->enquiry,
                        'lead_score' => $lead_score,
                        'lead_stage' => 1,
                        'comment' => $comment,
                        'ld_status' => '1'
                    );
                    $this->db->set('status', 3);
                    $this->db->set('client_created_date',date('Y-m-d h:i:s'));
                    $this->db->where('enquiry_id', $key);
                    $this->db->update('enquiry');
                    $this->load->model('rule_model');
                    $this->rule_model->execute_rules($enq->Enquery_id, array(1, 2, 3, 6, 7));
                    $this->Leads_Model->add_comment_for_events(display("move_to_client"), $enq->Enquery_id);
                
                    $insert_id = $this->Leads_Model->LeadAdd($data);
                    //insert follow up counter (3 is for client )
                    $this->enquiry_model->insetFollowupTime($key, 3, $enq->lead_created_date, date('Y-m-d H:i:s'));

                    if ($this->session->companey_id == 76 || ($this->session->companey_id == 57 && $enq->product_id == 122)) {
                        $user_right = '';
                        if ($enq->product_id == 168) {
                            $user_right = 180;
                        } else if ($enq->product_id == 169) {
                            $user_right = 186;
                        }
                        $report_to = '';
                        if ($this->session->companey_id == 57) {
                            if (!empty($enq->email) || !empty($enq->phone)) {
                                $user_exist = $this->dashboard_model->check_user_by_mail_phone(array('email' => $enq->email, 'phone' => $enq->phone));
                            }
                            $user_right = 200;
                            $report_to = $enq->enq_created_by;
                        }
                        $ucid    =   $this->session->companey_id;
                        $postData = array(
                            's_display_name'  =>    $enq->name,
                            'last_name'       =>    $enq->lastname,
                            's_user_email'    =>    $enq->email,
                            's_phoneno'       =>    $enq->phone,
                            'city_id'         =>    $enq->enquiry_city_id,
                            'state_id'        =>    $enq->enquiry_state_id,
                            'companey_id'     =>    $ucid,
                            'b_status'        =>    1,
                            'user_permissions' =>    $user_right,
                            'user_roles'      =>    $user_right,
                            'user_type'       =>    $user_right,
                            's_password'      =>    md5(12345678),
                            'report_to'       =>    $report_to
                        );
                        if (!empty($user_exist->pk_i_admin_id)) {
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
                        
                        $message = 'Email - ' . $enq->email . '<br>Password - 12345678';
                        $subject = 'Login Details';
                        if ($this->session->companey_id == 57 && $user_id) {
                            $this->db->where('temp_id', 125);
                            $this->db->where('comp_id', 57);
                            $temp_row    =   $this->db->get('api_templates')->row_array();
                            if (!empty($temp_row)) {
                                $subject = $temp_row['mail_subject'];
                                $message = str_replace("@{email}", $enq->email, $temp_row['template_content']);
                                $message = str_replace("@{password}", '12345678', $message);
                            }
                            
                            $this->Message_models->send_email($enq->email, $subject, $message);
                            $this->db->where('temp_id', 124);
                            $this->db->where('comp_id', 57);
                            $temp_row    =   $this->db->get('api_templates')->row_array();
                            if (!empty($temp_row)) {
                                $message = str_replace("@{email}", $enq->email, $temp_row['template_content']);
                                $message = str_replace("@{password}", '12345678', $message);
                            }   
                            $this->Message_models->smssend($enq->phone, $message);
                        }
                        // $msg .=    " And user created successfully";
                    }
                } 
                echo 1;
            } else {
                echo "Please Check Enquiry";
            }
        } else {
            echo "Something Went Wrong";
        }
    }
    public function assign_rowdata()
    {
        if (!empty($_POST)) {
            $id = $this->input->post('datasource_name');
            $limit = '*';
            $move_enquiry = $this->enquiry_model->datasourcelist($id);
            $assign_employee = $this->input->post('assign_employee');
            $this->db->select('*');
            $this->db->from('tbl_admin');
            $this->db->where('pk_i_admin_id', $assign_employee);
            $c_id = $this->db->get()->row();
            $postData = array();
            $change_status = array();
            $commentData = array();
            //print_r($move_enquiry);
            $adt = date("Y-m-d H:i:s");
            $ld_updt_by = $this->session->user_id;
            /*echo count($move_enquiry);
            exit();*/
            if (!empty($move_enquiry)) {
                $sendarr = array();
                foreach ($move_enquiry as $res) {
                    $postData = array();
                    $enquiry_code = $res->Enquery_id;
                    $this->db->where('phone', $res->phone);
                    if (!empty($res->product_id)) {
                        $this->db->where('product_id', $res->product_id);
                    }
                    $this->db->where('email', $res->email);
                    $this->db->where('comp_id', $c_id->companey_id);
                    $res_phone = $this->db->get('enquiry')->result();
                    if (!empty($res_phone)) {
                    } else {
                        $encode = $this->get_enquery_code();
                        // echo "id ".$encode;	                      
                        $postData = array(
                            'Enquery_id' => $encode,
                            'comp_id' => $c_id->companey_id,
                            'email' => $res->email,
                            'phone' => $res->phone,
                            'name_prefix' => $res->name_prefix,
                            'name' => $res->name,
                            'lastname' => $res->lastname,
                            'enquiry' =>  $res->enquiry,
                            'enquiry_source' =>  $res->enquiry_source,							
							'company' => $res->company,
                            'sales_branch' => $res->sales_branch,
                            'client_name' => $res->client_name,
							'designation' => $res->designation,
                            'gender' => $res->gender,
                            'enquiry_source' => $res->enquiry_source,
                            'product_id' => $res->product_id,
							'country_id' => $res->country_id,
							'region_id' => $res->region_id,
							'territory_id' => $res->territory_id,
                            'state_id' => $res->state_id,
                            'city_id' => $res->city_id,
                            'address' => $res->address,
                            'client_type' => $res->client_type,
                            'business_load' => $res->business_load,
                            'industries' => $res->industries,
                            'checked' => 0,
                            'datasource_id' => $res->datasource_id,
                            'created_by' =>  $res->created_by,
                            'created_date' =>  $res->created_date,
                            'aasign_to' => $assign_employee,
                            'assign_by' => $this->session->user_id,
                            'status' => 1
                        );
                        $commentData = array(
                            'lead_id'         => $encode,
                            'created_date'    => $adt,
                            'comment_msg'    => 'Raw Data Assigned',
                            'created_by'    => $ld_updt_by
                        );
                        $sendarr[] = array(
                            "camp_name" => $res->product_name,
                            "mobile"    => $res->phone
                        );
                    }
                    $change_status    =   array(
                        'status' => 3,
                        'phone' => $res->phone
                    );
                    if (!empty($postData)) {
                        $this->enquiry_model->update_tbleqry2($res->enquiry_id);
                        $this->db->insert('enquiry', $postData);
                        $lid = $this->db->insert_id();
    //Create contact code
        $designation = $res->designation;
        $this->db->select('desi_name');
        $this->db->where('id', $designation);
        $res_des = $this->db->get('tbl_designation')->row();		
		$data2 = array(
            'comp_id'=>$c_id->companey_id??'',
            'client_id' =>$lid??'',
            'c_name' => $res->name??'',
            'emailid' => $res->email??'',
            'contact_number' => $res->phone??'',
            'designation' => $res_des->desi_name??'',
            'other_detail' =>'CSV Upload',
            'decision_maker' => 1,
        );
        
        $this->db->insert('tbl_client_contacts', $data2);						
	//End	
                        $this->enquiry_model->update_tblextra($lid, $res->enquiry_id, $encode);
                        $this->db->insert('tbl_comment', $commentData);
                    }
                }
                if ($this->input->post('automated_call') == 1) {
                    /*print_r($sendarr);
        exit();*/
                    $sendp = $this->curlpost($sendarr);
                }
                //$this->Leads_Model->add_comment_for_events('Row Data Assigned', $encode);
                $this->session->set_flashdata('message', display('save_successfully'));
                redirect(base_url() . 'lead/datasourcelist');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
                redirect(base_url() . 'lead/datasourcelist');
            }
        }
    }

    public function curlpost($sendarr = '')
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://czadmin.c-zentrixcloud.com/apps/addlead_bulk.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($sendarr),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $this->db->insert('czentrix', array('comp_id' => $this->session->companey_id, 'res' => $response, 'created_by' => $this->session->user_id));
    }

    public function index()
    {
        //$this->output->enable_profiler(TRUE);
        if (user_role('60') == true) {
        }
        //$this->benchmark->mark('all_inq_start');    
        $data['all_enquery_num'] = $this->enquiry_model->all_enquery()->num_rows();
        //$this->benchmark->mark('all_inq_end');

        $data['user_list'] = $this->User_model->read();
        $data['title'] = display('enquiry_list');
        //$data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        //$data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        //$data['enquirys'] = $this->enquiry_model->read();
        //$data['state_list'] = $this->location_model->state_list();
        //$data['raw_enquery'] = $this->enquiry_model->raw_enquery();

        $data['all_drop_num'] = $this->enquiry_model->all_drop()->num_rows();
        $data['all_active'] = $this->enquiry_model->active_enqueries('0', '*');
        $data['all_active_num'] = $data['all_active']->num_rows();
        //$data['unassigned'] = $this->enquiry_model->unassigned();
        //$data['all_leads'] = $this->enquiry_model->all_leads();
        //$data['all_user'] = $this->User_model->all_user();
        $data['all_today_update_num'] = $this->enquiry_model->all_today_update()->num_rows();
        $data['all_creaed_today_num'] = $this->enquiry_model->all_creaed_today()->num_rows();
        $data['drops'] = $this->Leads_Model->get_drop_list();
        //$data['checked_enquiry'] = $this->enquiry_model->checked_enquiry();
        //$data['unchecked_enquiry'] = $this->enquiry_model->unchecked_enquiry();
        //$data['scheduled'] = $this->enquiry_model->scheduled();
        //$data['unscheduled'] = $this->enquiry_model->unscheduled();
        //Total duplicate entry...
        //$data['dublicate'] = $this->enquiry_model->all_duplicate();
        //$data['customer_types'] = $this->enquiry_model->customers_types();
        // $data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
 
        $data['content'] = $this->load->view('enquiry', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function send_sms()
    {
        $this->input->post('mesge_type');
        $this->db->where('temp_for', $for);
        $this->db->where('temp_addby', $this->session->companey_id);
        $res = $this->db->get('api_templates');
        $q = $res->result();
        foreach ($q as $value) {
            echo '<option value="' . $value->temp_id . '">' . $value->template_name . '<option>';
        }
    }
    function phone_check($phone)
    {
        if($this->session->companey_id == 90 && (empty($this->input->post('mobileno')) && empty($this->input->post('email')))){
            $this->form_validation->set_message('phone_check', 'Either Mobile no or email field is required');
            return false;
        }
        if($this->session->companey_id ==90 && !$this->input->post('mobileno')){
            return true;
        }
        $product_id    =   $this->input->post('product_id');
        if ($product_id) {
            $query = $this->db->query("select phone from enquiry where product_id=$product_id AND phone=$phone");
            if ($query->num_rows() > 0) {
                $this->form_validation->set_message('phone_check', 'The Mobile no field can not be dublicate in current process');
                return false;
            } else {
                return TRUE;
            }
        } else {
            $comp_id = $this->session->companey_id;
            $query = $this->db->query("select phone from enquiry where comp_id=$comp_id AND phone=$phone");
            if ($query->num_rows() > 0) {
                $this->form_validation->set_message('phone_check', 'The Mobile no field can not be dublicate');
                return false;
            } else {
                return TRUE;
            }
        }
    }
    function email_check($email)
    {
        $product_id    =   $this->input->post('product_id');
        if ($product_id) {
            $query = $this->db->query("select email from enquiry where product_id=$product_id AND email = '$email'");
            if ($query->num_rows() > 0) {
                $this->form_validation->set_message('email_check', 'The Email field can not be dublicate in current process');
                return false;
            } else {
                return TRUE;
            }
        } else {
            $comp_id = $this->session->companey_id;
            $query = $this->db->query("select phone from enquiry where comp_id=$comp_id AND email = '$email' ");
            if ($query->num_rows() > 0) {
                $this->form_validation->set_message('email_check', 'The Email field can not be dublicate');
                return false;
            } else {
                return TRUE;
            }
        }
    }
   

    public function create()
    {
        //print_r($_POST);die;
        $process = $this->session->userdata('process');
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $data['title'] = display('new_enquiry');
    
        // $ruledata   = $this->db->select("*")->from("tbl_new_settings")->where('comp_id',$this->session->companey_id)->get()->row();
        // if($ruledata->duplicacy_status == 0)
        // { 
        //     if($ruledata->field_for_identification == 'email')
        //     {
        //         $this->form_validation->set_rules('email', display('email'), 'xss_clean|required|is_unique[enquiry.email]', array('is_unique'=>'Email already exist'));
        //     }
        //     elseif ($ruledata->field_for_identification == 'phone')
        //     {
        //        $this->form_validation->set_rules('mobileno', display('mobileno'), 'max_length[20]|callback_phone_check|required', array('phone_check' => 'Duplicate Entry for phone'));
        //     }
        //     else
        //     {
        //         $this->form_validation->set_rules('email', display('email'), 'xss_clean|required|is_unique[enquiry.email]', array('is_unique'=>'Email already exist'));
        //         $this->form_validation->set_rules('mobileno', display('mobileno'), 'max_length[20]|callback_phone_check|required', array('phone_check' => 'Duplicate Entry for phone'));
        //     }
        // }
        if($this->session->companey_id == 90){
            $this->form_validation->set_rules('mobileno', display('mobileno'), 'max_length[20]|callback_phone_check');
        }else{
            $this->form_validation->set_rules('mobileno', display('mobileno'), 'required|max_length[20]');
        }
        if (!empty($this->input->post('email'))) {
            $this->form_validation->set_rules('email', display('email'), 'trim');
        }
        $enquiry_date = $this->input->post('enquiry_date');
        if ($enquiry_date != '') {
            $enquiry_date = date('d/m/Y');
        } else {
            $enquiry_date = date('d/m/Y');
        }
        $city_id = $this->db->select("*")
            ->from("city")
            ->where('id', $this->input->post('city_id'))
            ->get();
        $other_phone = $this->input->post('other_no[]');
        if ($this->form_validation->run() === true) {
           
            if (empty($this->input->post('product_id'))) {
                $process_id    =   $this->session->process[0];
            } else {
                $process_id    =   $this->input->post('product_id');
            }
            $name = $this->input->post('enquirername');
            $name_w_prefix = $name;
            if ($this->session->companey_id == '83') {
                $daynamic = $this->input->post('enqueryfield[4400]', true);
                $input_id = $this->input->post('inputfieldno', true);
                foreach ($input_id as $key => $value) {
                    if ($value == '4400') {
                        $branch_code = $daynamic[$key];
                    }
                }
                
                $branch = strtoupper($branch_code);
                $first = substr("$branch", 0, 3);
                $dt = date('d');
                $mt = date('m');
                $yt = date('y');
                $second = $dt . '' . $mt . '' . $yt;
                $third = mt_rand(10000, 99999);
                $encode = $first . '' . $second . '' . $third;
            } else {
                $encode = $this->get_enquery_code();
            }

            $encode = $this->get_enquery_code();
            if (!empty($other_phone)) {
                $other_phone =   implode(',', $other_phone);
            } else {
                $other_phone = '';
            }
            $status=1;
            if(!empty($this->input->post('status'))){
                $status=$this->input->post('status');
            }else{
                 $status=1;
            }

            //===Sales region/area
            $sales_area= '';
            $sales_region = '';
            $sales_branch = $this->input->post('sales_branch')??'';
            if(!empty($sales_branch))
            {
                $d = $this->db->where('branch_id',$sales_branch)->get('branch')->row();
                if(!empty($d))
                {
                    $sales_area = $d->area_id;
                    $sales_region = $d->region_id;
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
				$industry = $indus_id;
			}else{
				$industry = $this->input->post('industries');
			}
			//print_r($designation);exit;
//For asign to jitesh gautam			
			if($this->input->post('lead_source') == 129 || $this->input->post('lead_source') == 135){
                $created_by = '2173';
            }else{
                $created_by = $this->session->user_id;
            }
			
//End

//For asign to according to sales branch
$post_br = $this->input->post('sales_branch');
if(!empty($post_br)){
$usr_br = $this->User_model->all_emp_list_assign($post_br);
if(empty($usr_br)){
$usr_br = $this->User_model->all_emp_list_assign_others($post_br);
}
if(!empty($usr_br)){
$usr_ttl = count($usr_br);
if($usr_ttl > 1){	
	$usr_id = $usr_br[0]->pk_i_admin_id;
	$reparr = $this->db->select('report_to')->where('pk_i_admin_id',$usr_id)->get('tbl_admin')->row();
	$assign_to = $reparr->report_to??'';
}else{
	$usr_id = $usr_br[0]->pk_i_admin_id;
	$assign_to = $usr_id??'';	
}
}else{
	$assign_to = '';
}	
}else{
	$assign_to = '';
}
//print_r($assign_to);exit;
//End         
            $postData = [
                'Enquery_id' => $encode,
                'comp_id' => $this->session->userdata('companey_id'),
                'user_role' => $this->session->user_role,
                'email' => $this->input->post('email', true),
                'phone' => $this->input->post('mobileno', true),
                'other_phone' => $other_phone,
                'name_prefix' => $this->input->post('name_prefix', true),
                'name' => $name_w_prefix,
                'lastname' => $this->input->post('lastname'),
                'gender' => $this->input->post('gender'),
                'reference_type' => $this->input->post('reference_type'),
                'reference_name' => $this->input->post('reference_name'),
                'enquiry' => $this->input->post('enquiry', true),
                'enquiry_source' => $this->input->post('lead_source'),
                'enquiry_subsource' => $this->input->post('sub_source'),
                'sub_source'=> $this->input->post('subsource'), //lead subsource
                'company' => $this->input->post('company'),
				'sales_branch' => $this->input->post('sales_branch'),
				'client_name' => $this->input->post('client_name'),
                'designation' => $designation,
                'address' => $this->input->post('address'),
                'pin_code' => $this->input->post('pin_code'),
                'checked' => 0,
                'product_id' => $process_id,
                'institute_id' => $this->input->post('institute_id'),
                'datasource_id' => $this->input->post('datasource_id'),
                'center_id' => $this->input->post('center_id'),
                'ip_address' => $this->input->ip_address(),
                'created_by' => $created_by,
				'aasign_to' => $assign_to,
                'city_id' => !empty($city_id->row()->id) ? $city_id->row()->id : '',
                'state_id' => !empty($city_id->row()->state_id) ? $city_id->row()->state_id : '',
                'country_id'  => !empty($city_id->row()->country_id) ? $city_id->row()->country_id : '',
                'region_id'  => !empty($city_id->row()->region_id) ? $city_id->row()->region_id : '',
                'territory_id'  => !empty($city_id->row()->territory_id) ? $city_id->row()->territory_id : '',
                'sales_region' => $sales_region,
                'sales_area' => $sales_area,
                'client_type'=>$this->input->post('client_type'),
                'business_load'=>$this->input->post('business_load'),
                'industries'=>$industry,
                'status' => $status
            ];
         //echo '<pre>';print_r($postData);exit;   
            $insert_id    =   $this->enquiry_model->create($postData);
            if ($this->input->post('apply_with')) {
                $course_apply = $this->Institute_model->readRowcrs($this->input->post('apply_with'));
                $institute_data = array(
                    'institute_id'      => $course_apply->institute_id,
                    'course_id'         => $course_apply->crs_id,
                    'p_lvl'             => $course_apply->level_id,
                    'p_disc'            => $course_apply->discipline_id,
                    'p_length'          => $course_apply->length_id,
                    't_fee'             => $course_apply->tuition_fees,
                    'ol_fee'            => '',
                    'enquery_code'      => $encode,
                    'application_url'   => '',
                    'major'             => '',
                    'user_name'         => '',
                    'password'          => '',
                    'app_status'        => 1,
                    'app_fee'           => '',
                    'transcript'        => '',
                    'lors'              => '',
                    'sop'               => '',
                    'cv'                => '',
                    'gre_gmt'           => '',
                    'toefl'             => '',
                    'remark'            => '',
                    'followup_comment'  => '',
                    'ref_no'            => '',
                    'courier_status'    => '',
                    'created_by'        => $this->session->user_id
                );
                $ins    =   $this->db->insert('institute_data', $institute_data);
            }
            $this->load->model('rule_model');
            $this->rule_model->execute_rules($encode, array(1, 2, 3, 6, 7));
            if ($insert_id) {
                $this->Leads_Model->add_comment_for_events(display("enquery_create"), $encode);
                if ($this->input->is_ajax_request()) {
                    echo json_encode(array('status' => 'success'));
                } else {
                     if($this->input->post('red')=='visits'){
                        $this->session->set_flashdata('message', 'Your '.display('enquiry').' has been  Successfully created');
                        redirect(base_url('client/visits'));
                     }else{
                        $this->session->set_flashdata('message', 'Your '.display('enquiry').' has been  Successfully created');
                        redirect(base_url() . 'enquiry/view/' . $insert_id);
                     }

                   
                }
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'fail', 'error' => validation_errors()));
                exit();
            }
            $this->load->model('Dash_model', 'dash_model');
            $data['name_prefix'] = $this->enquiry_model->name_prefix_list();
            $user_role    =   $this->session->user_role;
            $data['products'] = $this->dash_model->get_user_product_list();
            $data['product_contry'] = $this->location_model->productcountry();
            $data['institute_list'] = $this->Institute_model->institutelist();
            $data['datasource_list'] = $this->Datasource_model->datasourcelist();
            $data['datasource_lists'] = $this->Datasource_model->datasourcelist2();
            $data['subsource_list'] = $this->Datasource_model->subsourcelist();
            $data['center_list'] = $this->Center_model->all_center();
            $data['state_list'] = $this->location_model->estate_list();
            $data['city_list'] = $this->location_model->ecity_list();
            $data['country_list'] = $this->location_model->ecountry_list();
            // print_r($data['company_list']);exit();
            $data['process_id'] = 0;
            if (is_array($process)) {
                if (count($process) == 1) {
                    $data['invalid_process'] = 0;
                    $data['process_id'] = $process[0];
                } else {
                    $data['invalid_process'] = 1;
                }
            } else {
                $data['invalid_process'] = 1;
            }
            if (!(user_access(230) || user_access(231) || user_access(232) || user_access(233) || user_access(234) || user_access(235) || user_access(236))) {
                $data['invalid_process'] = 0;
            }
            $primary_tab =0;
            $tabs = $this->db->select('id')
                            ->where(array('form_for'=>0,'primary_tab'=>1))
                            ->get('forms')
                            ->row();
            if($tabs)
                $primary_tab = $tabs->id;
            $data['primary_tab']= $primary_tab;
            $data['content'] = $this->load->view('add-equiry1', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function apply_to_course()
    {
        $crs_id          =   $this->input->post('id');
        $enquiry_code    =   $this->input->post('enquiry_code');
        $status = 0;
        if ($crs_id && $enquiry_code) {
            $course_apply = $this->Institute_model->readRowcrs($crs_id);
            $institute_data = array(
                'institute_id'      => $course_apply->institute_id,
                'course_id'         => $course_apply->crs_id,
                'p_lvl'             => $course_apply->level_id,
                'p_disc'            => $course_apply->discipline_id,
                'p_length'          => $course_apply->length_id,
                't_fee'             => $course_apply->tuition_fees,
                'ol_fee'            => '',
                'enquery_code'      => $enquiry_code,
                'application_url'   => '',
                'major'             => '',
                'user_name'         => '',
                'password'          => '',
                'app_status'        => 1,
                'app_fee'           => '',
                'transcript'        => '',
                'lors'              => '',
                'sop'               => '',
                'cv'                => '',
                'gre_gmt'           => '',
                'toefl'             => '',
                'remark'            => '',
                'followup_comment'  => '',
                'ref_no'            => '',
                'courier_status'    => '',
                'created_by'        => $this->session->user_id
            );
            $ins    =   $this->db->insert('institute_data', $institute_data);
            if ($ins) {
                $status = 1;
            }
        }
        echo $status;
    }
    public function create2()
    {
        $process = $this->session->userdata('process');
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $data['title'] = display('new_enquiry');
        $this->form_validation->set_rules('mobileno', display('mobileno'), 'max_length[20]|required', array('is_unique' => 'Duplicate   entry for phone'));
        $enquiry_date = $this->input->post('enquiry_date');
        if ($enquiry_date != '') {
            $enquiry_date = date('d/m/Y');
        } else {
            $enquiry_date = date('d/m/Y');
        }
        $city_id = $this->db->select("*")
            ->from("city")
            ->where('id', $this->input->post('city_id'))
            ->get();
        $other_phone = $this->input->post('other_no[]');
        if ($this->form_validation->run() === true) {
            $name = $this->input->post('enquirername');
            $name_w_prefix = $name;
            $encode = $this->get_enquery_code();
            if (!empty($other_phone)) {
                $other_phone =   implode(',', $other_phone);
            } else {
                $other_phone = '';
            }
            $postData = [
                'Enquery_id' => $encode,
                'user_role' => $this->session->user_role,
                'comp_id' => $this->session->userdata('companey_id'),
                'email' => $this->input->post('email', true),
                'phone' => $this->input->post('mobileno', true),
                'other_phone' => $other_phone,
                'name_prefix' => $this->input->post('name_prefix', true),
                'name' => $name_w_prefix,
                'lastname' => $this->input->post('lastname'),
                'gender' => $this->input->post('gender'),
                'reference_type' => $this->input->post('reference_type'),
                'reference_name' => $this->input->post('reference_name'),
                'enquiry' => $this->input->post('enquiry', true),
                'enquiry_source' => $this->input->post('lead_source'),
                'enquiry_subsource' => $this->input->post('sub_source'),
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'checked' => 0,
                'product_id' => $this->input->post('product_id'),
                'institute_id' => $this->input->post('institute_id'),
                'datasource_id' => $this->input->post('datasource_id'),
                'center_id' => $this->input->post('center_id'),
                'ip_address' => $this->input->ip_address(),
                'created_by' => $this->session->user_id,
                'city_id' => $city_id->row()->id,
                'state_id' => $city_id->row()->state_id,
                'country_id'  => $city_id->row()->country_id,
                'region_id'  => $city_id->row()->region_id,
                'territory_id'  => $city_id->row()->territory_id,
                'status' => 1
            ];
            if ($this->enquiry_model->create($postData)) {
                $coment_type = 1;
                $lead_id = $this->input->post('unique_no');
                $stage_id = $this->input->post('lead_stage');
                $stage_date = date("d-m-Y", strtotime($this->input->post('c_date')));
                $stage_time = date("H:i:s", strtotime($this->input->post('c_time')));
                $stage_desc = $this->input->post('lead_description');
                $stage_remark = $this->input->post('conversation');
                $contact_person = $this->input->post('contact_person1');
                $mobileno = $this->input->post('mobileno1');
                $email = $this->input->post('email1');
                $designation = $this->input->post('designation1');
                $this->db->set('lead_stage', $stage_id);
                $this->db->set('lead_discription', $stage_desc);
                $this->db->set('lead_discription_reamrk', $stage_remark);
                $this->db->where('enquiry_id', $insert_id);
                $this->db->update('enquiry');
                $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
                $this->Leads_Model->add_comment_for_events_stage('Stage Updated', $encode, $stage_id, $stage_desc, $stage_remark, $coment_type);
                if ($stage_desc == 'updt') {
                    $tid    =   $this->input->post('latest_task_id');
                    $this->db->set('task_date', $stage_date);
                    $this->db->set('task_time', $stage_time);
                    $this->db->set('task_remark', $stage_remark);
                    $this->db->where('resp_id', $tid);
                    $this->db->update('query_response');
                } else {
                    if (!empty($this->input->post('c_date'))) {
                        $this->Leads_Model->add_comment_for_events_popup($stage_remark, $stage_date, $contact_person, $mobileno, $email, $designation, $stage_time, $encode);
                    }
                }
                $this->Leads_Model->add_comment_for_events(display("enquery_create"), $encode);
                $this->session->set_flashdata('message', 'Your '.display('enquiry').' has been  Successfully created');
                redirect(base_url() . 'enquiry');
            }
        } else {
            $this->load->model('Dash_model', 'dash_model');
            $user_role    =   $this->session->user_role;
            $data['company_list'] = $this->location_model->get_company_list($process);
            $data['products'] = $this->dash_model->get_user_product_list();
            $data['stagelist_withoutpro'] = $this->Leads_Model->get_leadstage_withoutprocess();
            $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
            $data['content'] = $this->load->view('add-enqform', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function create1()
    {
        $process = $this->session->userdata('process');
        // print_r($process);
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $data['title'] = display('new_enquiry');
        $this->form_validation->set_rules('mobileno', display('mobileno'), 'max_length[20]|callback_phone_check|required', array('phone_check' => 'Duplicate entry for phone'));
        $enquiry_date = $this->input->post('enquiry_date');
        if ($enquiry_date != '') {
            $enquiry_date = date('d/m/Y');
        } else {
            $enquiry_date = date('d/m/Y');
        }
        $city_id = $this->db->select("*")
            ->from("city")
            ->where('id', $this->input->post('city_id'))
            ->get();
        $other_phone = $this->input->post('other_no[]');
        if ($this->form_validation->run() === true) {
            if (empty($this->input->post('product_id'))) {
                $process_id    =   $this->session->process[0];
            } else {
                $process_id    =   $this->input->post('product_id');
            }
            $name = $this->input->post('enquirername');
            $name_w_prefix = $name;
            $encode = $this->get_enquery_code();
            if (!empty($other_phone)) {
                $other_phone =   implode(',', $other_phone);
            } else {
                $other_phone = '';
            }
            $postData = [
                'Enquery_id' => $encode,
                'comp_id' => $this->session->userdata('companey_id'),
                'user_role' => $this->session->user_role,
                'email' => $this->input->post('email', true),
                'phone' => $this->input->post('mobileno', true),
                'other_phone' => $other_phone,
                'name_prefix' => $this->input->post('name_prefix', true),
                'name' => $name_w_prefix,
                'lastname' => $this->input->post('lastname'),
                'gender' => $this->input->post('gender'),
                'reference_type' => $this->input->post('reference_type'),
                'reference_name' => $this->input->post('reference_name'),
                'enquiry' => $this->input->post('enquiry', true),
                'enquiry_source' => $this->input->post('lead_source'),
                'enquiry_subsource' => $this->input->post('sub_source'),
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'checked' => 0,
                'product_id' => $process_id,
                'institute_id' => $this->input->post('institute_id'),
                'datasource_id' => $this->input->post('datasource_id'),
                'center_id' => $this->input->post('center_id'),
                'ip_address' => $this->input->ip_address(),
                'created_by' => $this->session->user_id,
                'city_id' => $city_id->row()->id,
                'state_id' => $city_id->row()->state_id,
                'country_id'  => $city_id->row()->country_id,
                'region_id'  => $city_id->row()->region_id,
                'territory_id'  => $city_id->row()->territory_id,
                //'created_date' =>$enquiry_date, 
                'status' => 1
            ];
            if ($this->enquiry_model->create($postData)) {
                $insert_id = $this->db->insert_id();
                $this->Leads_Model->add_comment_for_events(display("enquery_create"), $encode);
                $this->session->set_flashdata('message', 'Your '.display('enquiry').' has been  Successfully created');
                redirect(base_url() . 'enquiry/view/' . $insert_id);
            }
        } else {
            $this->load->model('Dash_model', 'dash_model');
            $data['name_prefix'] = $this->enquiry_model->name_prefix_list();
            $user_role    =   $this->session->user_role;
            $data['products'] = $this->dash_model->get_user_product_list();
            $data['product_contry'] = $this->location_model->productcountry();
            $data['institute_list'] = $this->Institute_model->institutelist();
            $data['datasource_list'] = $this->Datasource_model->datasourcelist();
            $data['datasource_lists'] = $this->Datasource_model->datasourcelist2();
            $data['subsource_list'] = $this->Datasource_model->subsourcelist();
            $data['center_list'] = $this->Center_model->all_center();
            $data['state_list'] = $this->location_model->estate_list();
            $data['city_list'] = $this->location_model->ecity_list();
            $data['country_list'] = $this->location_model->ecountry_list();
            // print_r($data['company_list']);exit();
            $data['content'] = $this->load->view('add-equiry', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    
    function get_sub_byid()
    {
        $sub_id = $this->input->post('lead_source');
        $data['sub'] = $this->Datasource_model->get_sub_byid($sub_id);
        echo '<option value="" style="display:none">---Select subsource---</option>';
        foreach ($data['sub'] as $r) {
            echo '<option value="' . $r->subsource_id . '">' . $r->subsource_name . '</option>';
        }
    }
    function get_sub_byid1()
    {
        $sub_id = $this->input->post('sid');
        $data['sub'] = $this->Datasource_model->get_sub_byid($sub_id);
        echo '<option value="" style="display:none">---Select subsource---</option>';
        foreach ($data['sub'] as $r) {
            echo '<option value="' . $r->subsource_id . '">' . $r->subsource_name . '</option>';
        }
    }
    public function add_invoice($enquiry_id = null)
    {
        $data['page_title'] = 'Add Inovice';
        $data['title'] = 'Add Inovice';
        $data['enquiry_id'] = base64_decode($enquiry_id);
        $data['state_list'] = $this->location_model->state_list();
        $data['content'] = $this->load->view('invoice_add', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function autoDial()
    {
        $allenquiry = $this->input->post('enquiry_id[]');
        $enq = implode(",", $allenquiry);
        $res = $this->db->query("SELECT aasign_to,phone FROM `enquiry` WHERE enquiry_id IN ( $enq )");
        $phoneArr = $res->result_array();
        foreach ($phoneArr as $key => $value) {
            $assignto = $value['aasign_to'];
            if (!empty($assignto)) {
                $this->db->select('telephony_agent_id');
                $this->db->from('tbl_admin');
                $this->db->where('pk_i_admin_id', $assignto);
                $user = $this->db->get()->row();
                $user_id = $user->telephony_agent_id;
                $public_ivr_id = $user->public_ivr_id;
            } else {
                $user_id = $this->session->telephony_agent_id;
                $this->db->where('telephony_agent_id', $user_id);
                $res = $this->db->get('tbl_admin')->row();
                if (!empty($res)) {
                    $public_ivr_id = $res->public_ivr_id;
                }
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://obd-api.myoperator.co/obd-api-v1",
                CURLOPT_RETURNTRANSFER => true,  CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '{  "company_id": "5f1545a391ac6734", 
          "secret_token": "ff0bda40cbdb92a4f1eb7851817de3510a175345a16c59a9d98618a559019f73", 
          "type": "1", 
            "user_id": "' . $user_id . '",
            "number": "+91' . $value['phone'] . '",   
            "public_ivr_id":"' . $public_ivr_id . '", 
            "reference_id": "",  
            "region": "",
            "caller_id": "",  
            "group": ""   }',
                CURLOPT_HTTPHEADER => array(
                    "x-api-key:oomfKA3I2K6TCJYistHyb7sDf0l0F6c8AZro5DJh",
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            print_r($response);
        }
    }
    public function assign_enquiry()
    {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id[]');
       
            $assign_employee = $this->input->post('assign_employee');
            $user = $this->User_model->read_by_id($assign_employee);
            $notification_data = array();
            $assign_data = array();
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $data['enquiry'] = $this->enquiry_model->enquiry_by_id($key);
                    $enquiry_code = $data['enquiry']->Enquery_id;
                    $assign_data[] = array(
                        'aasign_to' => $assign_employee,
                        'assign_by' => $this->session->user_id,
                        'update_date' => date('Y-m-d H:i:s'),
                        'enquiry_id' => $key
                    );
                    $notification_data[] = array(
                        'assign_to' => $assign_employee,
                        'assign_by' => $this->session->user_id,
                        'assign_date' => date('Y-m-d H:i:s'),
                        'enq_id' => $key,
                        'enq_code' => $enquiry_code,
                        'assign_status' => 0
                    );
                    $this->Leads_Model->add_comment_for_events(display("enquery_assigned"), $enquiry_code);

                    $this->db->set('comp_id',$this->session->companey_id);
                    $this->db->set('query_id',$enquiry_code);
                    $this->db->set('noti_read',0);
                    $this->db->set('contact_person',$data['enquiry']->name.' '.$data['enquiry']->lastname);
                    $this->db->set('mobile',$data['enquiry']->phone);
                    $this->db->set('email',$data['enquiry']->email); 
                    $this->db->set('task_date',date('d-m-Y'));
                    $this->db->set('task_time',date('H:i:s'));
                    $this->db->set('create_by',$this->session->user_id);
                    $this->db->set('task_type','0');
                    $this->db->set('subject',display('enquiry').' Assigned');
                    $this->db->insert('query_response');
                }
                $this->db->update_batch('enquiry', $assign_data, 'enquiry_id');
                $this->db->insert_batch('tbl_assign_notification', $notification_data);
                echo display('save_successfully');
            } else {
                echo display('please_try_again');
            }
        }
    }
    public function get_assigned()
    {
        $res = $this->enquiry_model->get_assigned();
        $resultSet = array();
        if ($res) {
            $resultSet = $res->result();
        }
        echo json_encode($resultSet);
    }
    public function enquery_detals_by_status($id = '')
    {
        $data['user_list'] = $this->User_model->read();
        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['all_user'] = $this->User_model->all_user();
        if ($id == 1) {
            $data['all_active'] = $this->enquiry_model->all_creaed_today();
        } elseif ($id == 2) {
            $data['all_active'] = $this->enquiry_model->all_today_update();
        } elseif ($id == 3) {
            $data['all_active'] = $this->enquiry_model->active_enqueries();
        } elseif ($id == 4) {
            //$data['all_active'] = $this->enquiry_model->all_leads();
        } elseif ($id == 5) {
            $data['all_active'] = $this->enquiry_model->all_drop();
        } elseif ($id == 6) {
            $data['all_active'] = $this->enquiry_model->all_enquery();
        } elseif ($id == 7) {
            //$data['all_active'] = $this->enquiry_model->checked_enquiry();
        } elseif ($id == 8) {
            //$data['all_active'] = $this->enquiry_model->unchecked_enquiry();
        } elseif ($id == 9) {
            //$data['all_active'] = $this->enquiry_model->scheduled();
        } elseif ($id == 10) {
            //$data['all_active'] = $this->enquiry_model->unscheduled();
        } elseif ($id == 11) {
            //$data['dublicate'] = $this->enquiry_model->all_duplicate();
        } elseif (!empty($serach_key[1]) == 2) {
            //$data['all_active'] = $this->enquiry_model->search_data($serach_key[0]);
        } else {
            $data['all_active'] = $this->enquiry_model->all_creaed_today();
        }
        $data['get_sent_whats_app'] = $this->enquiry_model->get_sent_whats_app();
        $data['get_received_whats_app'] = $this->enquiry_model->get_received_whats_app();
        $data['state_list'] = $this->location_model->state_list();
        $data['city_list'] = $this->location_model->city_list();
        $data['drops'] = $this->Leads_Model->get_drop_list();
        $this->load->view('enquiry_list', $data);
    }
   
    public function view($enquiry_id = null)
    {
        
        $compid = $this->session->userdata('companey_id');
        $this->load->model('Client_Model');
        if (user_role('61') == true) {
        }
        $data['title'] = display('information');
        
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
		
       $data['data_type']  = $data['details']->status;
       $data['region_name'] = 0; 
       
    //    if(!empty($data['details']))
    //    {
    //         $dd = $this->db->where('region_id',$data['details']->region_id)->get('tbl_region')->row();
    //         if(!empty($dd))
    //         {
    //             $data['region_name'] = $dd->region_name;
    //         }
    //     }
        // if (!empty($data['details'])) {
        //     $lead_code = $data['details']->Enquery_id;
        // }
        //$data['check_status'] = $this->Leads_Model->get_leadListDetailsby_code($lead_code);
        //$data['all_drop_lead'] = $this->Leads_Model->all_drop_lead();
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
        // // $data['all_contact_list'] = $this->location_model->contact($enquiry_id);
        // 
       // $data['all_contact_list'] = $this->Client_Model->getContactWhere(array('comp_id'=>$this->session->companey_id,'client_id'=>$enquiry_id))->result();

        //$data['subsource_list'] = $this->Datasource_model->subsourcelist();
        //$data['drops'] = $this->Leads_Model->get_drop_list();
        //$data['name_prefix'] = $this->enquiry_model->name_prefix_list();
        //$data['leadsource'] = $this->Leads_Model->get_leadsource_list();
        $data['enquiry'] = $this->enquiry_model->enquiry_by_id($enquiry_id);
        //$data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $enquiry_code = $data['enquiry']->Enquery_id;
        $phone_id = '91' . $data['enquiry']->phone;
        $data['recent_tasks'] = $this->Task_Model->get_recent_taskbyID($enquiry_code);
        $user_role    =   $this->session->user_role;
        //$data['country_list'] = $this->location_model->productcountry();
        //$data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        //$data['course_list'] = $this->Leads_Model->get_course_list();
        //$data['institute_app_status'] = $this->Institute_model->get_institute_app_status();
      //  $data['prod_list'] = $this->Doctor_model->product_list($compid);
        //$data['amc_list'] = $this->Doctor_model->amc_list($compid, $enquiry_id);
        //$data['comission_data'] = $this->enquiry_model->comission_data($data['details']->Enquery_id);

        //$data['login_user_id'] = $this->user_model->get_user_by_email($data['details']->email);
        // if (!empty($data['login_user_id']->pk_i_admin_id)) {
        //     $data['login_details'] = $this->Leads_Model->logdata_select($data['login_user_id']->pk_i_admin_id);
        // }
        //$data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        // $data['state_list'] = $this->location_model->estate_list();
        // $data['city_list'] = $this->location_model->ecity_list();
        //$data['product_contry'] = $this->location_model->productcountry();
        //$data['get_message'] = $this->Message_models->get_chat($phone_id);
       // $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        $data['all_estage_lists'] = $this->Leads_Model->find_estage($data['details']->product_id, 1);
        //print_r( $data['all_estage_lists']);
        //$data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        //$data['dynamic_field']  = $this->enquiry_model->get_dyn_fld($enquiry_id);
        //$data['ins_list'] = $this->location_model->get_ins_list($data['details']->Enquery_id);
        //$data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id);
		//$data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id,base64_decode($this->uri->segment(4)));
        $data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id, $data['details']->product_id,0); //0 for Sales Tab 
        //print_r($data['tab_list']); exit;
        $this->load->helper('custom_form_helper');
        $data['leadid']     = $data['details']->Enquery_id;
        $data['enquiry_id'] = $enquiry_id;
        $data['compid']     =  $data['details']->comp_id;
        //$data['all_description_lists']    =   $this->Leads_Model->find_description();
        //if (user_access('1000') || user_access('1001') || user_access('1002')) {
           // $data['branch']=$this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();
            //$data['CommercialInfo'] = $this->enquiry_model->getComInfo($enquiry_id);
            //fetch last entry
            //$comm_data=$this->db->where(array('enquiry_id'=>$enquiry_id))->order_by('id',"desc")
            //->limit(1)->get('commercial_info');
            //$data['commInfoCount']=$comm_data->num_rows();
           // $data['commInfoData']=$comm_data->row();
        // } 
        // else
        // {   
            //  $data['CommercialInfo'] =array();
            //  $data['branch'] =array();
            // $data['commInfoCount']=0;
            // $data['commInfoData']=array();
        //}
        if($this->session->companey_id == 65 && $this->session->user_right == 215){
			$data['created_bylist'] = $this->User_model->readone(147,false);
		}else{
			$data['created_bylist'] = $this->User_model->readone();
		}
			
        //$data['all_designation'] = $this->Leads_Model->desi_select();		
        //$this->enquiry_model->make_enquiry_read($data['details']->Enquery_id);

        $data['all_contact']= $this->Client_Model->getContactList()->result();

        //echo"<pre>";print_r($data);die;
		$this->load->model('Branch_model');
		$data['branch_lists']=$this->Branch_model->all_sales_branch();
		$data['region_lists']=$this->Branch_model->all_sales_region();
        $enq['enquiry_id'] = $enquiry_id;
		$enq['all_designation'] = $this->Leads_Model->desi_select();
        $data['create_contact_form'] = $this->load->view('contacts/create_contact_form',$enq,true);
        $data['data_type'] = base64_decode($this->uri->segment(4));
        $data['create_contact_form'] = $this->load->view('contacts/create_contact_form',array(),true);
        $data['content'] = $this->load->view('enquiry_details1', $data, true);
        //$this->enquiry_model->assign_notification_update($enquiry_code);
        $this->load->view('layout/main_wrapper', $data);
    }
 
    function activityTimeline()
    {
        $enqid = $this->input->post('id');
        $data['enquiry'] = $this->enquiry_model->enquiry_by_id($enqid);
        $enquiry_code = $data['enquiry']->Enquery_id;
     
        $email_imap    =   get_sys_parameter('email_in_timeline', 'IMAP');
        if (!empty($data['enquiry']->email) && $email_imap) {
            $this->enquiry_model->getuseremail($data['enquiry']->email, $enquiry_code);
        }
        $comment_details = $this->Leads_Model->comment_byId($enquiry_code);
        $countdassigned=0;
        $html = '<ul class="cbp_tmtimeline" >';
        foreach ($comment_details as $comments) {
            //fetching assigned user start
            $assigned_user = $comments->assigned_user;
        if ($assigned_user != NULL ) {
           $dassigned= $this->db->select('pk_i_admin_id,s_display_name,last_name')->where(array('pk_i_admin_id'=>$assigned_user))->get('tbl_admin');
          $countdassigned=$dassigned->num_rows();
           if($countdassigned==1){
            $asdata=$dassigned->row();
           $userFName=$asdata->s_display_name;
           $userlName=$asdata->last_name;
           }
        }
            //fetching assigned user end
            if ($comments->comment_msg == 'Stage Updated') {
         $html .= '<li>
                   <div class="cbp_tmicon cbp_tmicon-phone" style="background:#cb4335;"></div>
                   <div class="cbp_tmlabel"  style="background:#95a5a6;">
                    <span style="font-weight:900;font-size:13px;">' . ucfirst($comments->comment_msg) . '</span></br>';
                if ($comments->comment_msg == 'Stage Updated') {
                    $html .=  '<span style="font-weight:900;font-size:12px;"> ' . ucfirst($comments->lead_stage_name) . '</span>';
                    if(trim($comments->description))
                    $html.='</br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->description) . '</span>';
                     if(trim($comments->remark))
                    $html.='</br>
                    <span style="font-size:10px;">Remarks: <strong> ' . ucfirst($comments->remark) . '</strong></span>';

                }
                $html .= '<div style="font-size:11px; margin-top:5px;">
                    Updated By : <strong>' . ucfirst($comments->comment_created_by . ' ' . $comments->lastname) . '</strong>
                        <div align="right" style="margin-top:8px;">
                        <span class="fa fa-clock-o"></span> ' . date("j-M-Y h:i:s a", strtotime($comments->ddate)) . '
                        </div>
                    </div>
                  </div>
                </li>';
            } else if ($comments->comment_msg == 'Enquiry moved') {
                $html .= '    <li>
                  <div class="cbp_tmicon cbp_tmicon-phone"  style="background:#148f77;"></div>
                  <div class="cbp_tmlabel"  style="background:#95a5a6;">
                    <span style="font-weight:900;font-size:13px;">' . ucfirst($comments->comment_msg) . '</span></br>';
                if ($comments->comment_msg == 'Stage Updated') {
                    $html .= '<span style="font-weight:900;font-size:12px;">' . ucfirst($comments->lead_stage_name) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->description) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->remark) . ' </span>';
                }
                $html . '<div style="font-size:11px; margin-top:5px">
                        Updated By : <strong>' . ucfirst($comments->comment_created_by . ' ' . $comments->lastname) . '</strong>
                        <div align="right" style="margin-top:8px;">
                        <span class="fa fa-clock-o"></span> ' . date("j-M-Y h:i:s a", strtotime($comments->ddate)) . '
                        </div>
                    </div>
                  </div>
                </li>';
            } else if ($comments->comment_msg == 'Move to '.display('lead').'') {
                $html .= '<li>
                  <div class="cbp_tmicon cbp_tmicon-phone"  style="background:#2980b9;"></div>
                  <div class="cbp_tmlabel"  style="background:#95a5a6;">
                  <span style="font-weight:900;font-size:13px;">' . ucfirst($comments->comment_msg) . ' </span></br>';
                if ($comments->comment_msg == 'Stage Updated') {
                    $html .= '<span style="font-weight:900;font-size:12px;">' . ucfirst($comments->lead_stage_name) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->description) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->remark) . ' </span>';
                }
                $html .= '<div style="font-size:11px; margin-top:5px">
                    Updated By : <strong>' . ucfirst($comments->comment_created_by . ' ' . $comments->lastname) . '</strong>
                        <div align="right" style="margin-top:8px;">
                        <span class="fa fa-clock-o"></span> ' . date("j-M-Y h:i:s a", strtotime($comments->ddate)) . '
                        </div>
                    </div>
                  </div>
                </li>';
            } else if ($comments->comment_msg == display('enquery_create')) {
                $html .= '   <li>
                  <div class="cbp_tmicon cbp_tmicon-phone"  style="background:#d68910;"></div>
                  <div class="cbp_tmlabel"  style="background:#95a5a6;">
                    <span style="font-weight:900;font-size:13px;">' . ucfirst($comments->comment_msg) . ' </span></br>';
                if ($comments->comment_msg == 'Stage Updated') {
                    $html .= '<span style="font-weight:900;font-size:12px;">' . ucfirst($comments->lead_stage_name) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->description) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->remark) . ' </span>';
                }
                $html .= '<div style="font-size:11px; margin-top:5px">
                    Updated By : <strong>' . ucfirst($comments->comment_created_by . ' ' . $comments->lastname) . '</strong>
                        <div align="right" style="margin-top:8px;">
                        <span class="fa fa-clock-o"></span> ' . date("j-M-Y h:i:s a", strtotime($comments->ddate)) . '
                        </div>
                    </div>
                  </div>
                </li>';
            } else if ($comments->comment_msg == 'Enquiry dropped' || $comments->comment_msg == 'Lead dropped' || $comments->comment_msg == 'Client dropped') {
                $html .= '<li>
                  <div class="cbp_tmicon cbp_tmicon-phone"  style="background:#d68910;"></div>
                  <div class="cbp_tmlabel"  style="background:#95a5a6;">
                    <span style="font-weight:900;font-size:13px;">' . ucfirst($comments->comment_msg) . ' </span></br>
                    
                    <span style="font-size:12px;">Reason:- </span><span style="font-size:11px;">';
                if (!empty($comments->drop_status)) {
                    $html .= '' . get_drop_status_name($comments->drop_status) . '</span>
                    <br>';
                }
                $html .= '<span style="font-size:12px;">Remark:- </spna><span style="font-size:11px;">';
                if (!empty($comments->drop_reason)) {
                    $html .= '' . $comments->drop_reason . '</span>';
                }

                if ($comments->comment_msg == 'Stage Updated') {
                    $html .= '<span style="font-weight:900;font-size:12px;">' . ucfirst($comments->lead_stage_name) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->description) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->remark) . ' </span>';
                }
                $html .= '<div style="font-size:11px; margin-top:5px">
                    Updated By : <strong>' . ucfirst($comments->comment_created_by . ' ' . $comments->lastname) . '</strong>
                        <div align="right" style="margin-top:8px;">
                        <span class="fa fa-clock-o"></span> ' . date("j-M-Y h:i:s a", strtotime($comments->ddate)) . '
                        </div>
                    </div>
                  </div>
                </li>';
            } else {
                $js_call = '';
                if(in_array($comments->comment_msg,array('Send Mail','Send SMS','Send Whatsapp')))
                {
                    $js_call = 'onclick="getTimelinestatus('.$comments->comm_id.');" data-target="#timelineshow_d"';
                }
                
                $html .= '  <li>
                    <div class="cbp_tmicon cbp_tmicon-phone"  style=""></div>
                    <div class="cbp_tmlabel"  '.$js_call.' style="background:#95a5a6;" data-toggle="modal" >
                    <span style="font-weight:900;font-size:13px;">' . ucfirst($comments->comment_msg) . ' </span></br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->remark) . '</span>';
                if ($comments->comment_msg == 'Stage Updated') {
                    $html .= '<span style="font-weight:900;font-size:12px;">' . ucfirst($comments->lead_stage_name) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->description) . ' </span>
                    </br>
                    <span style="font-weight:900;font-size:10px;">' . ucfirst($comments->description) . ' </span>';
                }
                if ($assigned_user != NULL) {
                    if($countdassigned==1){
                    if ($comments->comment_msg == display('enquery_assigned') OR $comments->comment_msg == display('lead_assigned') OR $comments->comment_msg == display('client_assigned') ) {
                    $html .= '<br>Assigned To: '.$userFName.' '.$userlName.' ';
                    }
                }
                }
                if ($comments->coment_type == 6) {
                    $html .= '<p>' . date("j-M-Y h:i:s a", strtotime($comments->ddate)) . ' <br>
                      <small>Email from user</small>
                      </p>
                  </div>
                </li>';
                } else {
                    $html .= '<div style="font-size:11px; margin-top:5px;">
                    Updated By : <strong>' . ucfirst($comments->comment_created_by . ' ' . $comments->lastname) . '</strong>
                        <div align="right" style="margin-top:8px;">
                        <span class="fa fa-clock-o"></span> ' . date("j-M-Y h:i:s a", strtotime($comments->ddate)) . '
                        </div>
                    </div>
                  </div>
                </li>';
                }
            }
        }
        $html .= '</ul>';
        echo $html;
    }
    function deleteDocument($cmmnt_id, $enqcode, $tabname)
    {
        // echo "$cmmnt_id";die;
        $dataAry = $this->db->select('fvalue')->from('extra_enquery')->where("comment_id", $cmmnt_id)->get()->result_array();
        $this->db->where("comment_id", $cmmnt_id);
        $this->db->delete('extra_enquery');
        $tabname = base64_decode($tabname);
        if ($this->db->affected_rows() > 0) {
            if ($tabname == "Documents") {
                foreach ($dataAry as $k) {   //echo "ddd".$k['fvalue'];die;
                    //echo"<pre>";print_r($k);die;
                    unlink($k['fvalue']);
                }
            }
            $this->Leads_Model->add_comment_for_events("$tabname Deleted ", $enqcode);
        }
        redirect($this->agent->referrer());
    }
	
	public function timeline_access()
    {

            $pk_id = $this->input->post('pk_id');
			$status = $this->input->post('status');
            $this->db->set('timline_access', $status);
            $this->db->where('pk_i_admin_id', $pk_id);
            $this->db->update('tbl_admin');
			if ( $this->db->affected_rows() > 0 )
            {
				$data = array(
                    'timline_sts'  => $status, 
                );
				$this->session->set_userdata($data);
                echo '1';
            }

    }

    public function mview($enquiry_id)
    {

        $usrno = $this->input->post("user_id", true);
        $enqno = $this->input->post("enquiry_id", true);
        if (user_role('63') == true) {
        }
        $data['title'] = display('information');
        if (!empty($_POST)) {
            $name = $this->input->post('enquirername');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobileno');
            $lead_source = $this->input->post('lead_source');
            $enquiry = $this->input->post('enquiry');
            $en_comments = $this->input->post('enqCode');
            $company = $this->input->post('company');
            $address = $this->input->post('address');
            $name_prefix = $this->input->post('name_prefix');
            $this->db->set('country_id', $this->input->post('country_id'));
            $this->db->set('product_id', $this->input->post('product_id'));
            $this->db->set('institute_id', $this->input->post('institute_id'));
            $this->db->set('datasource_id', $this->input->post('datasource_id'));
            $this->db->set('phone', $mobile);
            $this->db->set('enquiry_subsource', $this->input->post('sub_source'));
            $this->db->set('email', $email);
            $this->db->set('company', $company);
            $this->db->set('address', $address);
            $this->db->set('name_prefix', $name_prefix);
            $this->db->set('name', $name);
            $this->db->set('enquiry_source', $lead_source);
            $this->db->set('enquiry', $enquiry);
            $this->db->set('coment_type', 1);
            $this->db->set('lastname', $this->input->post('lastname'));
            $this->db->where('Enquery_id', $enquiry_id);
            $this->db->update('enquiry');
            $this->Leads_Model->add_comment_for_events(display('enquiry').' Updated', $en_comments);
            $this->session->set_flashdata('message', 'Save successfully');
            redirect('enquiry/view2/' . $enquiry_id);
        }


        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id2($enquiry_id);
        //$enqcode  = (!empty($data['details']))? $data['details']->Enquery_id : "";  
        $data['state_city_list'] = $this->location_model->get_city_by_state_id($data['details']->enquiry_state_id);
        $data['allleads'] = $this->Leads_Model->get_leadList();
        if (!empty($data['details'])) {
            $lead_code  = $data['details']->Enquery_id;
            $enquiry_id = $data['details']->enquiry_id;
        }
        $data['check_status'] = $this->Leads_Model->get_leadListDetailsby_code($lead_code);
        $data['all_drop_lead'] = $this->Leads_Model->all_drop_lead();
        $data['products'] = $this->dash_model->get_user_product_list();
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
        //  $data['lead_stages'] = $this->Leads_Model->get_leadstage_list();
        //   $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
        $enquiry_code = $data['enquiry']->Enquery_id;
        $phone_id = '91' . $data['enquiry']->phone;
        $data['recent_tasks'] = $this->Task_Model->get_recent_taskbyID($enquiry_code);
        //    $data['comment_details'] = $this->Leads_Model->comment_byId($enquiry_code);        
        $user_role    =   $this->session->user_role;
        $data['country_list'] = $this->location_model->productcountry();
        $data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        $data['institute_app_status'] = $this->Institute_model->get_institute_app_status();

        $data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        $data['state_list'] = $this->location_model->estate_list();
        $data['city_list'] = $this->location_model->ecity_list();
        $data['product_contry'] = $this->location_model->productcountry();
        $data['get_message'] = $this->Message_models->get_chat($phone_id);
        $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        $data['all_estage_lists'] = $this->Leads_Model->find_estage($enquiry_id);
        $data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        $data['dynamic_field']  = $this->enquiry_model->get_dyn_fld_api($enquiry_id);
        // print_r($data['dynamic_field']);exit();
        /*var_dump($data['institute_data']);
        exit();*/
        // $data['content'] = $this->load->view('menquiry_details', $data, true);
        $this->enquiry_model->assign_notification_update($enquiry_code);
        $this->load->view('menquiry_details', $data);
    }
    public function add_enquery_institute($enq_code)
    {
        $enq_code = base64_decode($enq_code);
        /*     echo "<pre>";
        print_r($_POST);
        echo "</pre>";
Array
(
    [application_url] => app
    [major] => major
    [username] => username
    [app_fee] => app fee
    [transcript] => transcript
    [lors] => lors
    [sop] => sop
    [cv] => cv
    [gre_gmt] => gre
    [tofel_ielts_pts] => tofel
    [remark] => remark
    [followup_comment] => followup
    [reference_no] => refeeq
    [courier_status] => courer status
)*/
        /*$this->form_validation->set_rules('application_url','Application Url','trim|required');
        $this->form_validation->set_rules('major','Major','trim|required');
        $this->form_validation->set_rules('username','User Name','trim|required');
        $this->form_validation->set_rules('password','Password','trim|required');
        $this->form_validation->set_rules('app_fee','App Fee','trim|required');
        $this->form_validation->set_rules('transcript','Transcript','trim|required');
        $this->form_validation->set_rules('lors','Lors','trim|required');
        $this->form_validation->set_rules('sop','Sop','trim|required');
        $this->form_validation->set_rules('cv','CV','trim|required');
        $this->form_validation->set_rules('gre_gmt','GRE/GMT','trim|required');
        $this->form_validation->set_rules('tofel_ielts_pts','TOFEL/IELTS/PTS','trim|required');
        $this->form_validation->set_rules('remark','Remark','trim');
        $this->form_validation->set_rules('followup_comment','Followup Comment','trim');
        $this->form_validation->set_rules('reference_no','Reference No','trim');
        $this->form_validation->set_rules('courier_status','Courier Status','trim');
        $this->form_validation->set_rules('app_status','App Status','trim|required');
		*/
        $this->form_validation->set_rules('institute_id', 'Institute', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $institute_id       =   $this->input->post('institute_id');
            $course_id          =   $this->input->post('app_course');
            $p_lvl              =   $this->input->post('p_lvl');
            $p_disc              =   $this->input->post('p_disc');
            $p_length           =   $this->input->post('p_length');
            $t_fee              =   $this->input->post('t_fee');
            $ol_fee             =   $this->input->post('ol_fee');
            $application_url    =   $this->input->post('application_url');
            $major              =   $this->input->post('major');
            $username           =   $this->input->post('username');
            $password           =   $this->input->post('password');
            $app_fee            =   $this->input->post('app_fee');
            $transcript         =   $this->input->post('transcript');
            $lors               =   $this->input->post('lors');
            $sop                =   $this->input->post('sop');
            $cv                 =   $this->input->post('cv');
            $gre_gmt            =   $this->input->post('gre_gmt');
            $tofel_ielts_pts    =   $this->input->post('tofel_ielts_pts');
            $remark             =   $this->input->post('remark');
            $followup_comment   =   $this->input->post('followup_comment');
            $reference_no       =   $this->input->post('reference_no');
            $courier_status     =   $this->input->post('courier_status');
            $app_status         =   $this->input->post('app_status');

            $institute_data = array(
                'institute_id'      => $institute_id,
                'course_id'         => $course_id,
                'p_lvl'             => $p_lvl,
                'p_disc'             => $p_disc,
                'p_length'          => $p_length,
                't_fee'             => $t_fee,
                'ol_fee'            => $ol_fee,
                'enquery_code'      => $enq_code,
                'application_url'   => $application_url,
                'major'             => $major,
                'user_name'         => $username,
                'password'          => $password,
                'app_status'        => $app_status,
                'app_fee'           => $app_fee,
                'transcript'        => $transcript,
                'lors'              => $lors,
                'sop'               => $sop,
                'cv'                => $cv,
                'gre_gmt'           => $gre_gmt,
                'toefl'             => $tofel_ielts_pts,
                'remark'            => $remark,
                'followup_comment'  => $followup_comment,
                'ref_no'            => $reference_no,
                'courier_status'    => $courier_status,
                'created_by'        => $this->session->user_id
            );
            if ($this->input->post('enq_institute_id')) {
                $this->db->where('id', $this->input->post('enq_institute_id'));
                $ins    =   $this->db->update('institute_data', $institute_data);
                $msg = 'Institute updated successfully';
            } else {
                $ins    =   $this->db->insert('institute_data', $institute_data);
                $msg = 'Institute added successfully';
            }
            if ($ins) {
                echo json_encode(array('status' => true, 'msg' => $msg));
            } else {
                echo json_encode(array('status' => false, 'msg' => 'Something went wrong!'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => validation_errors()));
        }
    }
    public function delete($enquiry_id = null)
    {
        if (user_role('12') == true) {
        }
        if ($this->enquiry_model->delete($enquiry_id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('enquiry');
    }
    public function move_to_lead()
    {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id');
            $date = date('d-m-Y H:i:s');
            
            $lead_score = $this->input->post('lead_score');
            $lead_stage = $this->input->post('lead_stage');
            $expected_date = $this->input->post('expected_date');
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

            if (!empty($expected_date)) {
                $expected_date = $this->input->post('expected_date');
            } else {
                $expected_date = '';
            }
            
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $enq = $this->enquiry_model->enquiry_by_id($key);
                    $data = array(
                        // 'adminid' => $enq->created_by,
                        'ld_name' => $enq->name,
                        'ld_email' => $enq->email,
                        'ld_mobile' => $enq->phone,
                        'lead_code' => $enq->Enquery_id,
                        // 'city_id' => $enq->city_id,
                        // 'state_id' => $enq->state_id,
                        // 'country_id' => $enq->country_id,
                        // 'region_id' => $enq->region_id,
                        // 'territory_id' => $enq->territory_id,
                        'ld_created' => $date,
                        'ld_for' => $enq->enquiry,
                        'lead_score' => $lead_score,
                        'lead_stage' => 1,
                        'comment' => $comment,
                        'ld_status' => '1',
                        
                    );
                    $this->db->set('status', 2);
                    $this->db->set('lead_created_date',date('Y-m-d H:i:s'));
                    $this->db->set('lead_expected_date',$expected_date);
                    $this->db->where('enquiry_id', $key);
                    $this->db->update('enquiry');
                    
                    $this->load->model('rule_model');
                    $this->rule_model->execute_rules($enq->Enquery_id, array(1, 2, 3, 6, 7));
                    
                    
                    $this->Leads_Model->add_comment_for_events(display("move_to_lead"), $enq->Enquery_id);
                    $insert_id = $this->Leads_Model->LeadAdd($data);
                    //insert follow up counter (2 is for lead )
                    $this->enquiry_model->insetFollowupTime($key, 2, $enq->created_date, date('Y-m-d H:i:s'));
                }
                echo 1;
            } else {
                echo "Please Check Enquiry";
            }
        } else {
            echo "Something Went Wrong";
        }
    }
    public function move_to_lead_details()
    {
        if (!empty($_POST)) {
            $move_enquiry = $this->input->post('enquiry_id');
            $enquiry = $this->enquiry_model->read_by_id($move_enquiry);
            $lead_score = $this->input->post('lead_score');
            $lead_stage = $this->input->post('move_lead_stage');
            $lead_discription = $this->input->post('lead_description');
            $comment = $this->input->post('comment');
            $expected_date = $this->input->post('expected_date');
            $assign_employee = $this->input->post('assign_employee');

            if(!empty($expected_date))
                //$expected_date = date('Y-m-d h:i:s',strtotime($expected_date));
			    $expected_date = date('Y-m-d',strtotime($expected_date));

            if (!empty($lead_score)) {
                $lead_score = $this->input->post('lead_score');
            }
            if (!empty($lead_stage)) {
                $lead_stage = $this->input->post('move_lead_stage');
            }
            if (!empty($comment)) {
                $comment = $this->input->post('comment');
            }
            if ((!empty($move_enquiry)) && $this->session->companey_id == '67') {
                $this->db->select('*');
                $this->db->from('enquiry');
                $this->db->where('enquiry_id', $move_enquiry);
                $q = $this->db->get()->row();
                $pass = '12345678';
                $assign_data = array(
                    's_username' => $q->name_prefix,
                    's_password' => md5($pass),
                    's_display_name' => $q->name,
                    'last_name' => $q->lastname,
                    'date_of_birth' => '',
                    'joining_date' => $q->created_date,
                    'state_id' => $q->state_id,
                    'city_id' => $q->city_id,
                    'territory_name' => $q->territory_id,
                    'country' => $q->country_id,
                    'region' => $q->region_id,
                    'companey_id' => $q->comp_id,
                    's_user_email' => $q->email,
                    's_phoneno' => $q->phone,
                    'b_status' => '1',
                    'process' => $q->product_id,
                    'user_permissions' => '151',
                    'user_type' => '0'
                );
                $this->db->insert('tbl_admin', $assign_data);
            }
            $this->db->set('lead_score', $lead_score);
            $this->db->set('lead_stage', $lead_stage);
            $this->db->set('lead_discription', $lead_discription);
            $this->db->set('lead_comment', $comment);
            $this->db->set('lead_expected_date', $expected_date);
            $this->db->set('lead_drop_status', 0);
            $this->db->set('lead_created_date', date('Y-m-d H:i:s'));
            $this->db->set('status', 2);
            $this->db->set('update_date', date('Y-m-d H:i:s'));
            if ((!empty($assign_employee)) AND $assign_employee!=0) {
                $this->db->set('aasign_to', $assign_employee);
                $this->db->set('assign_by',$this->session->user_id);
                }
            $this->db->where('enquiry_id', $move_enquiry);
            $this->db->update('enquiry');
           // echo $this->db->last_query(); exit();
            $this->load->model('rule_model');
            $this->rule_model->execute_rules($enquiry->row()->Enquery_id, array(1, 2, 3, 6, 7));

            $this->Leads_Model->add_comment_for_events(display("move_to_lead"), $enquiry->row()->Enquery_id);
            if ((!empty($assign_employee)) AND $assign_employee!=0) {
            $this->Leads_Model->add_comment_for_events(display("enquery_assign"), $enquiry->row()->Enquery_id);
            }
            //insert follow up counter (2 is for lead )
            $this->enquiry_model->insetFollowupTime($move_enquiry,2,$enquiry->row()->created_date,date('Y-m-d H:i:s'));
            $message=display('enquiry').' Convert to '.display('lead').' Successfully';
            $this->session->set_flashdata('message',$message);
            redirect('enquiry');
        } else {
            echo "<script>alert('Something Went Wrong')</script>";
            redirect('enquiry');
        }
    }
    public function active_enquery($id)
    {
        $this->db->set('drop_status', 0);
        $this->db->where('enquiry_id', $id);
        $this->db->update('enquiry');
        $data['enquiry'] = $this->enquiry_model->enquiry_by_id($id);
        $enquiry_code = $data['enquiry']->Enquery_id;
        $enquiryid  = $id;
        if ($data['enquiry']->status == 1) {
            $url = 'enquiry/view/' . $enquiryid;
            $comment = display('enquiry') . ' Activated';
        } else if ($data['enquiry']->status == 2) {
            $url = 'lead/lead_details/' . $enquiryid;
            $comment = display('lead') . ' Activated';
        } else if ($data['enquiry']->status == 3) {
            $url = 'client/view/' . $enquiryid;
            $comment = display('Client') . ' Activated';
        } else {
            $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
            if (!empty($enquiry_separation)) {
                $enquiry_separation = json_decode($enquiry_separation, true);
                $stage    =   $data['enquiry']->status;
                $title = $enquiry_separation[$stage]['title'];
                $url = 'client/view/' . $enquiryid . '?stage=' . $stage;
                $comment = $title . ' Activated';
            }
        }
        $this->Leads_Model->add_comment_for_events($comment, $enquiry_code);
        $this->session->set_flashdata('message', $comment . " Successfully");
        redirect($url, 'refresh');
        /*
        $this->Leads_Model->add_comment_for_events('Active Enquiry', $enquiry_code);
        $this->session->set_flashdata('message', "Activated Successfully");
        redirect('enquiry/view/' . $id);*/
    }
    public function drop_enquiry()
    {
        $data['title'] = 'Drop Reasons';
        $enquiryid = $this->uri->segment(3);
        if (!empty($_POST)) {
            $reason = $this->input->post('reason');
            $drop_status = $this->input->post('drop_status');
            $this->db->set('drop_status', $drop_status);
            $this->db->set('drop_reason', $reason);
            $this->db->set('update_date', date('Y-m-d H:i:s'));
            $this->db->where('enquiry_id', $enquiryid);
            $this->db->update('enquiry');
            $data['enquiry'] = $this->enquiry_model->enquiry_by_id($enquiryid);
            $enquiry_code = $data['enquiry']->Enquery_id;
            if ($data['enquiry']->status == 1) {
                $url = 'enquiry/view/' . $enquiryid;
                $comment = 'Enquiry dropped';
            } else if ($data['enquiry']->status == 2) {
                $url = 'lead/lead_details/' . $enquiryid;
                $comment = 'Lead dropped';
            } else if ($data['enquiry']->status == 3) {
                $url = 'client/view/' . $enquiryid;
                $comment = display('Client') . ' dropped';
            } else {
                $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                if (!empty($enquiry_separation)) {
                    $enquiry_separation = json_decode($enquiry_separation, true);
                    $stage    =   $data['enquiry']->status;
                    $title = $enquiry_separation[$stage]['title'];
                    $url = 'client/view/' . $enquiryid . '?stage=' . $stage;
                    $comment = $title . ' dropped';
                }
            }
            $this->Leads_Model->add_comment_for_events1($comment, $enquiry_code, $reason, $drop_status); // to insert drop status and reason
            $this->session->set_flashdata('message', "Dropped Successfully");
            //echo $url; exit();
            redirect($url, 'refresh');
        }
    }
    public function drop_enquiries()
    {
        if (!empty($_POST)) {
            $reason = $this->input->post('reason');
            $drop_status = $this->input->post('drop_status');
            $move_enquiry = $this->input->post('enquiry_id');
            if (!empty($move_enquiry)) {
                foreach ($move_enquiry as $key) {
                    $this->db->set('drop_status', $drop_status);
                    $this->db->set('drop_reason', $reason);
                    $this->db->set('update_date', date('Y-m-d H:i:s'));
                    $this->db->where('enquiry_id', $key);
                    $this->db->update('enquiry');
                    $data['enquiry'] = $this->enquiry_model->enquiry_by_id($key);
                    $enquiry_code = $data['enquiry']->Enquery_id;
                    $this->Leads_Model->add_comment_for_events(display("enquery_dropped"), $enquiry_code);
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
                    $this->db->where('enquiry_id', $key);
                    //$this->db->where('status!=', 2);
                    $this->db->where('comp_id', $this->session->companey_id);
                    $this->db->delete('enquiry');
                }
                $this->session->set_flashdata('message', "Enquiry Deleted Successfully");
                redirect(base_url() . 'enquiry');
            } else {
                echo display('please_try_again');
            }
        }
    }

    function upload_enquiry()
    {
        ini_set('max_execution_time', '-1');
        $filename = "enquiry_" . date('d-m-Y_H_i_s');
        $config = array(
            'upload_path' => $_SERVER["DOCUMENT_ROOT"] . "/assets/enquiry",
            'allowed_types' => "text/plain|text/csv|csv",
            'remove_spaces' => TRUE,
            'file_name' => $filename
        );

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (empty($this->input->post('datasource_name'))) {
            $this->session->set_flashdata('exception', "Data Source empty");
            redirect(base_url() . 'lead/datasourcelist');
        } else {
            $datasource_name = $this->input->post('datasource_name');
        }
        if ($this->upload->do_upload('img_file')) {
            $upload = $this->upload->data();
            $json['success'] = 1;
            $filePath = $config['upload_path'] . '/' . $upload['file_name'];
            $file = $filePath;
            $handle = fopen($file, "r");
            $c = 0;
            $count = 0;
            $record = 0;
            $failed_record = 0;
            $i = 0;
            $dat_array = array();
            while (($filesop = fgetcsv($handle, 2000, ",")) !== false) {
                $dat_array = array();
                $count++;
                if ($count == 1) {
                } else {
                    if (!empty($filesop[15]) && !empty($this->location_model->get_city_by_name($filesop[15]))) {
                        $res = $this->location_model->get_city_by_name($filesop[15]);
                        $country_id = !empty($res->country_id) ? $res->country_id : '';
                        $region_id = !empty($res->region_id) ? $res->region_id : '';
                        $territory_id = !empty($res->territory_id) ? $res->territory_id : '';
                        $state_id = !empty($res->state_id) ? $res->state_id : '';
                        $city_id = !empty($res->cid) ? $res->cid : '';
                    } else {
                        $country_id = '';
                        $region_id = '';
                        $territory_id = '';
                        $state_id = '';
                        $city_id = '';
                    }
                    $product_name = '';
                    $product_row    =   !empty($filesop[13]) ? $this->enquiry_model->name_product_list_byname($filesop[13]) : '';   // process                  
                    if (!empty($product_row)) {
                        $sb_id =  $product_row->sb_id;
                    }
                    if (!empty($sb_id)) {
                        $product_name = $sb_id;
                    } else {
                        $product_name = '';
                    }
                    $enquiry_source = !empty($filesop[12]) ? $this->enquiry_model->enquiry_source_byname($filesop[12]) : '';       //     source         

                    $enquiry_source_id = '';
                    if (!empty($enquiry_source)) {
                        $enquiry_source_id =  $enquiry_source->lsid;
                    }

                    if (!empty($filesop[0])) {
                        $zero = $filesop[0];
                    } else {
                        $zero = '';
                    } // Mobile No
					
                    if (!empty($filesop[1])) {
                        $one = $filesop[1];
                    } else {
                        $one = '';
                    } // Other_number
					
                    if (!empty($filesop[2])) {
                        $two = $filesop[2];
                    } else {
                        $two = '';
                    } // Email Address
					
    $this->db->where('company_name', $filesop[3]);
    $this->db->where('comp_id', $this->session->companey_id);
    $id = $this->db->get('tbl_company');
    $id1 = $id->num_rows();
if ($id1 > 0) {
    $company_id = $id->row()->id;
} else {
    $this->db->set('company_name', $filesop[3]);
	$this->db->set('process_id', $product_name);
    $this->db->set('comp_id', $this->session->userdata('companey_id'));
    $this->db->insert('tbl_company');
    $company_id = $this->db->insert_id();
}
                    if (!empty($company_id)) {
                        $three = $company_id;
                    } else {
                        $three = '';
                    } // Company name

$this->db->where('branch_name', $filesop[4]);
$this->db->where('comp_id', $this->session->companey_id);
$id2 = $this->db->get('branch');
$id3 = $id2->num_rows();
if ($id3 > 0) {
    $branch_id = $id2->row()->branch_id;
}					
                    if (!empty($branch_id)) {
                        $four = $branch_id;
                    } else {
                        $four = 0;
                    } //Sales Branch
					
                    if (!empty($filesop[5])) {
                        $five = $filesop[5];
                    } else {
                        $five = '';
                    } //Client Name
					
                    if (!empty($filesop[6])) {
                        $six = $filesop[6];
                    } else {
                        $six = '';
                    } // Contact

                    if (!empty($filesop[7])) {
                        $seven = $filesop[7];
                    } else {
                        $seven = '';
                    } // Name prefixed

                    if (!empty($filesop[8])) {
                        $eaight = $filesop[8];
                    } else {
                        $eaight = '';
                    } // First Name
					
					if (!empty($filesop[9])) {
                        $nine = $filesop[9];
                    } else {
                        $nine = '';
                    } // Last Name

    $this->db->where('desi_name', $filesop[10]);
    $this->db->where('comp_id', $this->session->companey_id);
    $id4 = $this->db->get('tbl_designation');
    $id5 = $id4->num_rows();
if ($id5 > 0) {
    $designation_id = $id4->row()->id;
} else {
    $this->db->set('desi_name', $filesop[10]);
	$this->db->set('status', '1');
	$this->db->set('created_by', $this->session->userdata('user_id'));
    $this->db->set('comp_id', $this->session->userdata('companey_id'));
    $this->db->insert('tbl_designation');
    $designation_id = $this->db->insert_id();
}
					
					if (!empty($designation_id)) {
                        $ten = $designation_id;
                    } else {
                        $ten = '';
                    } // Designation
					
					if (!empty($filesop[11])) {
if($filesop[11]=='Male'){
   $gender = '1'; 
}else if($filesop[11]=='Female'){
   $gender = '2'; 
}else{
   $gender = '3'; 
}
                        $eleven = $gender;
                    } else {
                        $eleven = '';
                    } // Gender
					
					if (!empty($enquiry_source_id)) {
                        $twelve = $enquiry_source_id;
                    } else {
                        $twelve = '';
                    } // Lead Source
					
					if (!empty($product_name)) {
                        $therteen = $product_name;
                    } else {
                        $therteen = '';
                    } // Process
					
					if (!empty($state_id)) {
                        $forteen = $state_id;
                    } else {
                        $forteen = '';
                    } // State
					
					if (!empty($city_id)) {
                        $fifteen = $city_id;
                    } else {
                        $fifteen = '';
                    } // City
					
					if (!empty($filesop[16])) {
                        $sixteen = $filesop[16];
                    } else {
                        $sixteen = '';
                    } // Address
					
					if (!empty($filesop[17])) {
                        $seventeen = $filesop[17];
                    } else {
                        $seventeen = '';
                    } // Client Type
					
					if (!empty($filesop[18])) {
                        $eighteen = $filesop[18];
                    } else {
                        $eighteen = '';
                    } // Type Of Load / Business
					
					if (!empty($filesop[19])) {
                        $nineteen = $filesop[19];
                    } else {
                        $nineteen = '';
                    } // Industries
					
					if (!empty($filesop[20])) {
                        $twenty = $filesop[20];
                    } else {
                        $twenty = '';
                    } // Remark
					
                    $phone = $zero;
                    $this->db->where('phone', $phone);
                    $this->db->where('comp_id', $this->session->companey_id);
                    if (!empty($therteen)) {
                        $this->db->where('product_id', $therteen);
                    }
                    $res_phone = $this->db->get('enquiry2')->num_rows();                    
                    if ($res_phone == 0) {
                        $dat_array = array(
                            'Enquery_id' => 'as',
							'comp_id' => $this->session->companey_id,
							'datasource_id' => $datasource_name,
                            'phone' => $zero,
                            'other_no' => $one,
                            'email' => $two,
                            'company' => $three,
                            'sales_branch' => $four,
                            'client_name' => $five,
                            'name_prefix' => $seven,
                            'name' => $eaight,
                            'lastname' => $nine,
                            'designation' => $ten,
                            'gender' => $eleven,
                            'enquiry_source' => $twelve,
                            'product_id' => $therteen,
							'country_id' => $country_id,
							'region_id' => $region_id,
							'territory_id' => $territory_id,
                            'state_id' => $forteen,
                            'city_id' => $fifteen,
                            'address' => $sixteen,
                            'client_type' => $seventeen,
                            'business_load' => $eighteen,
                            'industries' => $nineteen,
                            'enquiry' => $twenty,
							'created_by' => $this->session->user_id,
                            'status' => 1,
                        );
                        $record++;
                    } else {
                        $failed_record++;
                    }
                    if(!empty($dat_array)) {
                        $this->db->insert('enquiry2', $dat_array);
                        $l_id = $this->db->insert_id();
                        //print_r($l_id);exit;
                        /**************************************daynamic fields inserts****************************/
                        if (!empty($filesop[13])) {
                            $colmn_data    =   $this->enquiry_model->all_list_colmn($filesop[13]);
                            if (!empty($colmn_data)) {
                                $j = 21;
                                foreach ($colmn_data as $cdata) {
                                    $column_id =  $cdata->input_id;
                                    $biarr = array(
                                        "enq_no"  => "",
                                        "input"   => $column_id,
                                        "parent"  => $l_id,
                                        "fvalue"  => !empty($filesop[$j])?$filesop[$j]:'',
                                        "cmp_no"  => $this->session->companey_id,
                                    );
                                    // print_r($biarr);exit;
                                    $this->db->insert('extra_enquery', $biarr);
                                    $j++;
                                }
                            }
                        }

                        /**************************************daynamic fields inserts End****************************/
                    }
                }
                //echo $i;
                $i++;
            }

            if ($record > 0) {
                $res = 'Record(' . $record . ') inserted';
            } else {
                $res = 'No Unique record Found !';
            }
            if ($failed_record) {
                $res .= ' (' . $failed_record . ') duplicate record ';
            }
            unlink($filePath);
            $this->session->set_flashdata('message', "File Uploaded successfully." . $res);
            redirect(base_url() . 'lead/datasourcelist');
        } else {
            $this->session->set_flashdata('exception', $this->upload->display_errors());
            redirect(base_url() . 'lead/datasourcelist');
        }
    }
    public function search_comment_and_task($date = '', $id = '')
    {
        if (!empty($date)) {
            $details = '';
            $details1 = '';
            $task_start = date('d-m-Y', strtotime($date));
            $task_id = $id;
            $data['recent_tasks'] = $this->Task_Model->search_taskby_date($task_start, $task_id);
            $data['search_task'] = $this->Task_Model->search_task($task_start, $task_id);
            foreach ($data['search_task'] as $comment) {
                $details .= '<div class="list-group" id="comment_div">
                            <a class="list-group-item list-group-item-action flex-column align-items-start">
                               <div class="d-flex w-100 justify-content-between">
                                  <p class="mb-1">' . $comment->comment_msg . '</p>
                                  <small><b>' . date("j-M-Y h:i:s a", strtotime($comment->created_date)) . '</b></small>
                               </div>
                            </a>
                         </div>';
            }
            foreach ($data['recent_tasks'] as $task) {
                $details1 = '<div class="list-group" >
   <div class="col-md-12 list-group-item list-group-item-action flex-column align-items-start" style="margin-top:10px;">
      <div class="d-flex w-100 justify-content-between">
         <div class="col-md-12"><b>Name :</b>' . $task->contact_person . '</div>
         <div class="col-md-12"><b>Mobile No :</b>' . $task->mobile . '</div>
         <div class="col-md-12"><b>Email :</b>' . $task->email . '</div>
         <div class="col-md-12"><b>Designation :</b>' . $task->designation . '</div>
         <div class="col-md-12"><b>Conversaion  :</b>' . $task->conversation . '</div>
         <div class="col-md-12"><b>' . date("j-M-Y h:i:s a", strtotime($task->nxt_date)) . '</b></div>
         <div class="col-md-12">
            <i class="fa fa-pencil color-success" style="float:right;" data-toggle="modal" data-target="#task_redit' . $task->resp_id . '"></i>
         </div>
      </div>
   </div>
   <div id="task_redit' . $task->resp_id . '" class="modal fade in" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
               <h4 class="modal-title">Edit Task</h4>
            </div>
            <div class="modal-body">
               <form action="lead/enquiry_response_updatetask" method="post">
                  <div class="profile-edit">
                     <div class="form-group col-sm-6">
                        <label>Actual Meet Date</label>
                        <input class="form-control date" name="meeting_date" value="' . date("d-m-Y h:i:s a", strtotime($task->upd_date)) . '" type="text" placeholder="yyyy-mm-dd" readonly>
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Name</label>
                        <input type="text" class="form-control" name="contact_person" value="' . $task->contact_person . '" placeholder="Contact Person Name">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Mobile No</label>
                        <input type="text" class="form-control" name="mobileno" value="' . $task->mobile . '">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Email</label>
                        <input type="text" class="form-control" name="email" value="' . $task->email . '">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Designation</label>
                        <input type="text" class="form-control" name="designation" value="' . $task->designation . '">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Conversaion Details</label>
                        <textarea class="form-control" name="conversation">' . $task->conversation . '
                        </textarea>
                     </div>
                     <div class="form-group">
                        <input type="hidden" name="enq_code"  value="' . $task->resp_id . '" >
                        <input type="hidden" name="task_type" value="1">
                        <input type="submit" name="update" class="btn btn-primary"  value="' . display('update') . '" >
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>';
            }
            //  echo json_encode($d); 
            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode(array('details' => $details, 'details1' => $details1)));
        } else {
            $details = '';
            $details1 = '';
            $task_start = $this->input->post('task_start');
            $task_id = $this->input->post('task_id');
            $task_end = $this->input->post('task_end');
            $data['recent_tasks'] = $this->Task_Model->search_task_btw_date($task_start, $task_id, $task_end);
            $data['search_task'] = $this->Task_Model->search_btw_task($task_start, $task_id, $task_end);
            foreach ($data['search_task'] as $comment) {
                $details .= '<div class="list-group" id="comment_div">
                            <a class="list-group-item list-group-item-action flex-column align-items-start">
                               <div class="d-flex w-100 justify-content-between">
                                  <p class="mb-1">' . $comment->comment_msg . '</p>
                                  <small><b>' . date("j-M-Y h:i:s a", strtotime($comment->created_date)) . '</b></small>
                               </div>
                            </a>
                         </div>';
            }
            foreach ($data['recent_tasks'] as $task) {
                $details1 = '<div class="list-group" >
   <div class="col-md-12 list-group-item list-group-item-action flex-column align-items-start" style="margin-top:10px;">
      <div class="d-flex w-100 justify-content-between">
         <div class="col-md-12"><b>Name :</b>' . $task->contact_person . '</div>
         <div class="col-md-12"><b>Mobile No :</b>' . $task->mobile . '</div>
         <div class="col-md-12"><b>Email :</b>' . $task->email . '</div>
         <div class="col-md-12"><b>Designation :</b>' . $task->designation . '</div>
         <div class="col-md-12"><b>Conversaion  :</b>' . $task->conversation . '</div>
         <div class="col-md-12"><b>' . date("j-M-Y h:i:s a", strtotime($task->nxt_date)) . '</b></div>
         <div class="col-md-12">
            <i class="fa fa-pencil color-success" style="float:right;" data-toggle="modal" data-target="#task_redit' . $task->resp_id . '"></i>
         </div>
      </div>
   </div>
   <div id="task_redit' . $task->resp_id . '" class="modal fade in" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
               <h4 class="modal-title">Edit Task</h4>
            </div>
            <div class="modal-body">
               <form action="lead/enquiry_response_updatetask" method="post">
                  <div class="profile-edit">
                     <div class="form-group col-sm-6">
                        <label>Actual Meet Date</label>
                        <input class="form-control date" name="meeting_date" value="' . date("d-m-Y h:i:s a", strtotime($task->upd_date)) . '" type="text" placeholder="yyyy-mm-dd" readonly>
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Name</label>
                        <input type="text" class="form-control" name="contact_person" value="' . $task->contact_person . '" placeholder="Contact Person Name">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Mobile No</label>
                        <input type="text" class="form-control" name="mobileno" value="' . $task->mobile . '">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Email</label>
                        <input type="text" class="form-control" name="email" value="' . $task->email . '">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Contact Person Designation</label>
                        <input type="text" class="form-control" name="designation" value="' . $task->designation . '">
                     </div>
                     <div class="form-group col-sm-6">
                        <label>Conversaion Details</label>
                        <textarea class="form-control" name="conversation">' . $task->conversation . '
                        </textarea>
                     </div>
                     <div class="form-group">
                        <input type="hidden" name="enq_code"  value="' . $task->resp_id . '" >
                        <input type="hidden" name="task_type" value="1">
                        <input type="submit" name="update" class="btn btn-primary"  value="' . display('update') . '" >
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>';
            }
            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode(array('details' => $details, 'details1' => $details1)));
        }
    }
    public function get_enquery_code()
    {
        $code = $this->genret_code();
        $code2 = 'ENQ' . $code;
        $response = $this->enquiry_model->check_existance($code2);
        if ($response) {
            $this->get_enquery_code();
        } else {
            return $code2;
            //exit;
        }
        //exit;
    }
    function genret_code()
    {
        $pass = "";
        $chars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        for ($i = 0; $i < 12; $i++) {
            $pass .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $pass;
    }
    //Get Message Templates enquiry_model
    public function msg_templates()
    {
        $template = $this->input->post('tmpl_id');
        echo json_encode($this->enquiry_model->get_templates($template));
    }
    //Insert customer type in table..
    public function add_customer_types()
    {
        if (user_role('b35') == true) {
        }
        $customer_type = $this->input->post('input_cus_type');
        $is_active = $this->input->post('status');
        $created_on = date('d-m-Y');
        $added_by = $this->session->user_id;
        $data = array(
            'customer_type' => $customer_type,
            'comp_id' => $this->session->userdata('companey_id'),
            'is_active' => $is_active,
            'added_on' => $created_on,
            'added_by' => $added_by
        );
        $this->enquiry_model->add_customer_type($data);
        $this->session->set_flashdata('message', 'Customer type added successfully');
        return redirect('lead/load_customer_channel_mater');
    }
    public function edit_customer_types()
    {
        if (user_role('b36') == true) {
        }
        $customer_type = $this->input->post('input_cus_type');
        $is_active = $this->input->post('status');
        $id = $this->input->post('row_id');
        $updated_on = date('d-m-Y');
        $updated_by = $this->session->user_id;
        $data = array(
            'customer_type' => $customer_type,
            'is_active' => $is_active,
            'updated_on' => $updated_on,
            'updated_by' => $updated_by
        );
        $this->enquiry_model->update_customer_type($data, $id);
        $this->session->set_flashdata('message', 'Customer type updated successfully');
        return redirect('lead/load_customer_channel_mater');
    }
    public function delete_customer_type()
    {
        if (user_role('b38') == true) {
        }
        $delete_ids = $this->input->post('favorite');
        $this->enquiry_model->delete_customer_types($delete_ids);
    }
    public function add_channel_partner_types()
    {
        $channel_partner = $this->input->post('channel_partner');
        $is_active = $this->input->post('status');
        $created_on = date('d-m-Y');
        $added_by = $this->session->user_id;
        $data = array(
            'channel_partner_type' => $channel_partner,
            'is_active' => $is_active,
            'added_on' => $created_on,
            'added_by' => $added_by
        );
        $this->enquiry_model->add_channel_partner($data);
        $this->session->set_flashdata('message', 'Channel partner added successfully');
        return redirect('lead/load_customer_channel_mater');
    }
    public function update_channel_partner()
    {
        $input_cus_type = $this->input->post('input_cus_type');
        $is_active = $this->input->post('status');
        $id = $this->input->post('row_id');
        $updated_on = date('d-m-Y');
        $updated_by = $this->session->user_id;
        $data = array(
            'channel_partner_type' => $input_cus_type,
            'is_active' => $is_active,
            'updated_on' => $updated_on,
            'updated_by' => $updated_by
        );
        $this->enquiry_model->update_channel_partner($data, $id);
        $this->session->set_flashdata('message', 'Channel partner updated successfully');
        return redirect('lead/load_customer_channel_mater');
    }
    public function delete_channel_partner()
    {
        $delete_ids = $this->input->post('favorite');
        $this->enquiry_model->delete_channel_partner_type($delete_ids);
    }
    public function add_name_prefix()
    {
        $name_prefix = $this->input->post('name_prefix');
        $is_active = $this->input->post('status');
        $created_on = date('d-m-Y');
        $added_by = $this->session->user_id;
        $data = array(
            'prefix' => $name_prefix,
            'comp_id' => $this->session->userdata('companey_id'),
            'is_active' => $is_active,
            'added_on' => $created_on,
            'added_by' => $added_by
        );
        $this->enquiry_model->name_prefix($data);
        $this->session->set_flashdata('message', 'Name Prefix added successfully');
        return redirect('lead/load_customer_channel_mater');
    }
    public function add_partner_type()
    {
        $name_prefix = $this->input->post('name_type');
        $created_on = date('d-m-Y');
        $added_by = $this->session->user_id;
        $data = array(
            'type' => $name_prefix,
            'date' => $created_on,
            'added_by' => $added_by
        );
        $this->enquiry_model->name_partner($data);
        $this->session->set_flashdata('message', 'Partner Type added successfully');
        return redirect('Enquiry/load_customer_channel_mater');
    }
    public function update_name_prefix()
    {
        $name_prefix = $this->input->post('name-prefix');
        $is_active = $this->input->post('status');
        $id = $this->input->post('row_id');
        $updated_on = date('d-m-Y');
        $updated_by = $this->session->user_id;
        $data = array(
            'prefix' => $name_prefix,
            'is_active' => $is_active,
            'updated_on' => $updated_on,
            'updated_by' => $updated_by
        );
        $this->enquiry_model->update_name_prefixes($id, $data);
        $this->session->set_flashdata('message', 'Name prefix updated successfully');
        return redirect('lead/load_customer_channel_mater');
    }
    public function update_name_partner()
    {
        $name_prefix = $this->input->post('name-type');
        $id = $this->input->post('row_id');
        $updated_on = date('d-m-Y');
        $updated_by = $this->session->user_id;
        $data = array(
            'type' => $name_prefix,
            'updated_on' => $updated_on,
            'updated_by' => $updated_by
        );
        $this->enquiry_model->update_name_partner($id, $data);
        $this->session->set_flashdata('message', 'Partner Type updated successfully');
        return redirect('Enquiry/load_customer_channel_mater');
    }
    public function delete_name_prefix()
    {
        $delete_ids = $this->input->post('favorite');
        $this->enquiry_model->delete_name_prefixes($delete_ids);
    }
    public function delete_name_partners()
    {
        $delete_ids = $this->input->post('favorite');
        $this->enquiry_model->delete_name_ptype($delete_ids);
    }
    /******************************************************personel tab data ajax**********************************************/
    public function select_state_by_con()
    {
        $states = $this->input->post('enq_state');
        echo json_encode($this->enquiry_model->all_states($states));
        // echo $diesc;
    }

    // enquiry datatable
    public function enquiry_load()
    {
        $this->load->model('enquiry_datatable_model');
        $list = $this->enquiry_datatable_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        foreach ($list as $each) {
            $no++;
            $row = array();
            $row[] = '<input onclick="event.stopPropagation();" type="checkbox" name="enquiry_id[]" class="checkbox1" value="<?php echo $each->enquiry_id; ?>">';
            $row[] = $i;
            $row[] = $each->icon_url;
            $row[] = $each->company;
            $row[] = $each->name_prefix . " " . $each->name . " " . $each->lastname;
            $row[] = $each->email;
            $row[] = $each->phone;
            $row[] = $each->address;
            $row[] = $each->created_date;
            $row[] = $each->created_by;
            $row[] = $each->aasign_to;
            $row[] = $each->datasource_name;

            $data[] = $row;
            $i++;
        }
        /*[0] => stdClass Object
        (
            [enquiry_id] => 3859
            [Enquery_id] => ENQ080009806847
            [email] => demo@mgail.com
            [phone] => 8965457812
            [name_prefix] => Mr.
            [name] => DEMO DATA
            [lastname] => Test
            [gender] => 1
            [reference_type] => 
            [reference_name] => 
            [enquiry] => Noida
            [org_name] => 
            [enquiry_source] => 0
            [enquiry_subsource] => 
            [ip_address] => 14.143.74.69
            [status] => 1
            [drop_status] => 0
            [drop_reason] => 
            [country_id] => 1
            [product_id] => 3
            [institute_id] => 
            [center_id] => 
            [datasource_id] => 
            [created_date] => 2019-11-12 11:49:27
            [created_by] => 107
            [update_date] => 
            [aasign_to] => 
            [assign_by] => 
            [checked] => 0
            [checked_by] => 
            [user_role] => 3
            [lead_score] => 
            [lead_stage] => 
            [lead_discription] => 
            [lead_discription_reamrk] => 
            [lead_comment] => 
            [lead_expected_date] => 
            [lead_created_date] => 
            [lead_updated_date] => 
            [lead_drop_status] => 0
            [lead_drop_reason] => 
            [client_drop_status] => 0
            [client_drop_reason] => 
            [company] =>  test
            [address] =>  Noida
            [city_id] => 1102
            [state_id] => 2
            [territory_id] => 2
            [region_id] => 1
            [is_delete] => 1
            [whatsapp_sent_status] => 
            [whatsapp_sent_mobile_no] => 
            [whatsapp_msg] => 
            [icon_url] => 
            [lsid] => 
            [score_count] => 
            [lead_name] => 
            [datasource_name] => 
        )*/
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->enquiry_datatable_model->count_all(),
            "recordsFiltered" => $this->enquiry_datatable_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function delete_institute()
    {
        $this->db->where('id', $this->input->post('inst_id'));
        if ($this->db->delete('institute_data')) {
            echo json_encode(array('status' => true, 'msg' => 'Successfully Deleted'));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Something went wrong'));
        }
    }
    public function delete_comission()
    {
        $this->db->where('id', $this->input->post('id'));
        if ($this->db->delete('tbl_comission')) {
            echo json_encode(array('status' => true, 'msg' => 'Successfully Deleted'));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Something went wrong'));
        }
    }
    public function get_update_enquery_institute_content()
    {
        $id    =   $this->input->post('id');
        $Enquiry_id    =   $this->input->post('Enquiry_id');
        $this->db->where('id', $id);
        $data['institute_data']    =   $this->db->get('institute_data')->row_array();
        $data['Enquiry_id'] =   $Enquiry_id;
        $data['details']    =   $this->enquiry_model->enquiry_by_code($Enquiry_id);
        $data['institute_app_status'] = $this->Institute_model->get_institute_app_status();
        if ($this->session->companey_id == '67') {
            //$data['qualification_data'] = $this->enquiry_model->quali_data($data['details']->Enquery_id);
            //$data['english_data'] = $this->enquiry_model->eng_data($data['details']->Enquery_id);
            $data['discipline'] = $this->location_model->find_discipline();
            $data['level'] = $this->location_model->find_level();
            $data['length'] = $this->location_model->find_length();
        }
        $data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->country_id);
        $content    =   $this->load->view('institute_modal_content', $data, true);
        echo $content;
    }

    public function re_login($id, $process)
    {
        $user_id = $id;
        $check_user = $this->dashboard_model->check_user_enquiry($user_id);
        $city_id = $this->db->select("*")
            ->from("city")
            ->where('id', $check_user->row()->city_id)
            ->get();
        $setting = $this->setting_model->read();
        $data['title'] = (!empty($setting->title) ? $setting->title : null);
        $data['logo'] = (!empty($setting->logo) ? $setting->logo : null);
        $data['favicon'] = (!empty($setting->favicon) ? $setting->favicon : null);
        $data['footer_text'] = (!empty($setting->footer_text) ? $setting->footer_text : null);
        $data = $this->session->set_userdata([
            'isLogIn' => true,
            'user_id' => $check_user->row()->pk_i_admin_id,
            'companey_id' => $check_user->row()->companey_id,
            'email' => $check_user->row()->email,
            'designation' => $check_user->row()->designation,
            'phone' => $check_user->row()->s_phoneno,
            'fullname' => $check_user->row()->s_display_name . '&nbsp;' . $check_user->row()->last_name,
            'country_id' => 0,
            'region_id' => 0,
            'territory_id' => 0,
            'state_id' => 0,
            'city_id' => 0,
            /*'user_role' => $check_user->row()->user_roles,
                    'user_type' => $check_user->row()->user_type,*/
            'user_right' => $check_user->row()->user_permissions,
            'picture' => $check_user->row()->picture,
            'modules' => $check_user->row()->modules,
            'title' => (!empty($setting->title) ? $setting->title : null),
            'address' => (!empty($setting->description) ? $setting->description : null),
            'logo' => (!empty($setting->logo) ? $setting->logo : null),
            'favicon' => (!empty($setting->favicon) ? $setting->favicon : null),
            'footer_text' => (!empty($setting->footer_text) ? $setting->footer_text : null),
            'process' => $process,
            'telephony_agent_id' => $check_user->row()->telephony_agent_id
        ]);
        if (!empty($check_user->result())) {
            redirect(base_url() . 'enquiry/create_from.html');
        } else {
            $array = array('error' => 'Invalid Username or Password');
            $this->set_response([
                'status' => false,
                'message' => $array
            ], REST_Controller::HTTP_OK);
        }
    }
    /***************************************************************student edit profile******************************************/
    public function viewpro($enquiry_id = null)
    {
        $compid = $this->session->userdata('companey_id');
        $data['title'] = display('information');
        if(empty($enquiry_id)){
            $this->session->set_flashdata('message', 'User Profile not Found');
            redirect('dashboard/user_profile');
        }
        if (!empty($_POST)) {
            $name = $this->input->post('enquirername');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobileno');
            $lead_source = $this->input->post('lead_source');
            $enquiry = $this->input->post('enquiry');
            $en_comments = $this->input->post('enqCode');
            $company = $this->input->post('company');
            $address = $this->input->post('address');
            $name_prefix = $this->input->post('name_prefix');
            $this->db->set('country_id', $this->input->post('country_id'));
            $this->db->set('product_id', $this->input->post('product_id'));
            $this->db->set('institute_id', $this->input->post('institute_id'));
            $this->db->set('datasource_id', $this->input->post('datasource_id'));
            $this->db->set('phone', $mobile);
            $this->db->set('enquiry_subsource', $this->input->post('sub_source'));
            $this->db->set('email', $email);
            $this->db->set('company', $company);
            $this->db->set('address', $address);
            $this->db->set('name_prefix', $name_prefix);
            $this->db->set('name', $name);
            $this->db->set('enquiry_source', $lead_source);
            $this->db->set('enquiry', $enquiry);
            $this->db->set('coment_type', 1);
            $this->db->set('lastname', $this->input->post('lastname'));
            $this->db->where('enquiry_id', $enquiry_id);
            $this->db->update('enquiry');
            $this->Leads_Model->add_comment_for_events(display("enquiry_updated"), $en_comments);
            $this->session->set_flashdata('message', 'Save successfully');
            redirect('enquiry/view/' . $enquiry_id);
        }


        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
        $data['ins_list'] = $this->location_model->stu_ins_list();
        $data['vid_list'] = $this->schedule_model->vid_list();
        $data['course_list'] = $this->Institute_model->courselist();
        $data['institute_list'] = $this->Institute_model->institutelist();
        // $compid = $data['details']->comp_id;
        // print_r($data['vid_list']);exit();
        //$data['state_city_list'] = $this->location_model->get_city_by_state_id($data['details']->enquiry_state_id);
        //$data['state_city_list'] = $this->location_model->ecity_list();
        $stu_phone = $this->session->userdata('phone');
        $data['student_Details'] = $this->home_model->studentdetail($stu_phone);
        $studetails = $this->home_model->studentdetail($stu_phone);
        $en_id = $studetails['Enquery_id'];
        $comp_id = $studetails['comp_id'];
        //print_r($data['student_Details']);exit;
        $data['invoice_details'] = $this->home_model->invoicedetail($en_id);
        $data['agrrem_doc'] = $this->home_model->aggr_doc($en_id);
        $data['schdl_list'] = $this->schedule_model->get_schedule_list();
        $data['allleads'] = $this->Leads_Model->get_leadList();
        if (!empty($data['details'])) {
            $lead_code = $data['details']->Enquery_id;
        }
        $data['check_status'] = $this->Leads_Model->get_leadListDetailsby_code($lead_code);
        $data['all_drop_lead'] = $this->Leads_Model->all_drop_lead();
        $data['products'] = $this->dash_model->get_user_product_list();
        $this->enquiry_model->change_enq_status($data['details']->Enquery_id);

        // print_r($data['amc_list']);exit();
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
        $phone_id = '91' . $data['enquiry']->phone;
        $data['recent_tasks'] = $this->Task_Model->get_recent_taskbyID($enquiry_code);
        $data['comment_details'] = $this->Leads_Model->comment_byId($enquiry_code);
        $user_role    =   $this->session->user_role;
        $data['country_list'] = $this->location_model->productcountry();
        $data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        $data['institute_app_status'] = $this->Institute_model->get_institute_app_status();

        $data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        $data['state_list'] = $this->location_model->estate_list();
        $data['city_list'] = $this->location_model->ecity_list();
        $data['product_contry'] = $this->location_model->productcountry();
        $data['get_message'] = $this->Message_models->get_chat($phone_id);
        $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        $data['all_estage_lists'] = $this->Leads_Model->find_estage($enquiry_id);
        $data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        $data['comission_data'] = $this->enquiry_model->comission_data($data['details']->Enquery_id);
        $data['dynamic_field']  = $this->enquiry_model->get_dyn_fld($enquiry_id);
        $data['ins_list'] = $this->location_model->get_ins_list($data['details']->Enquery_id);
        $data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id);
        $data['prod_list'] = $this->Doctor_model->product_list($compid);
        $data['amc_list'] = $this->Doctor_model->amc_list($compid, $enquiry_id);
        $data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id, $data['details']->product_id);
        $this->load->helper('custom_form_helper');
        $data['discipline'] = $this->location_model->find_discipline();
        $data['level'] = $this->location_model->find_level();
        $data['length'] = $this->location_model->find_length();
        $data['enquiry_id'] = $enquiry_id;
        $data['all_description_lists']    =   $this->Leads_Model->find_description();
        $data['compid']     =  $data['details']->comp_id;
        $data['content'] = $this->load->view('enq_proedit', $data, true);
        $this->enquiry_model->assign_notification_update($enquiry_code);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function related_enquiry()
    {
        $phone = $this->input->post('phone');
        $data['enquiry_id'] = $this->input->post('enquiry_id');
        $this->db->where('phone', $phone);
        $this->db->where('comp_id', $this->session->companey_id);
        $data['result'] = $this->db->get('enquiry')->result_array();
        $this->load->view('enquiry/related_enquiry', $data, 'true');
    }

    function enq_redirect($id)
    {
        $this->db->select('status');
        $this->db->where('enquiry_id', $id);
        $r  =   $this->db->get('enquiry')->row_array();
        if ($r['status']  == 1) {
            $url = 'enquiry/view/' . $id;
        } else if ($r['status']  == 2) {
            $url = 'lead/lead_details/' . $id;
        } else if ($r['status']  == 3) {
            $url = 'client/view/' . $id;
        }
        redirect($url, 'refresh');
    }

  public function edit_query_data()
    {
      if($post = $this->input->post())
      {
        if($post['task']=='view')
        {
          $ci =& get_instance();
          $tid = $post['tab_id'];
          $comp_id = $post['comp_id'];
          $enquiry_id = $post['enq_code'];
          $tabname= $post['tabname'];
          $cmnt_id = $post['cmnt_id'];
          $ci->load->model('enquiry_model');
            $ci->load->model('Ticket_Model');
            $ci->load->model('location_model');
            $ci->load->model('leads_model');
          
            $data['tid'] = $tid;
            $data['comp_id'] = $comp_id;
            $data['cmnt_id'] = $cmnt_id;
            $ci->db->select('*,input_types.title as input_type_title');     
            $ci->db->where('tbl_input.form_id',$tid);       
            $ci->db->where('tbl_input.company_id',$comp_id);        
            $ci->db->join('input_types','input_types.id=tbl_input.input_type');       
            $data['form_fields']  = $ci->db->get('tbl_input')->result_array();
           
            $enq= $this->db->where('Enquery_id',$enquiry_id)->get('enquiry')->row();
             $data['details']= $ci->Leads_Model->get_leadListDetailsby_id($enq->enquiry_id);
               //print_r($data['details']); exit();
               //1 for ticket form
              $data['dynamic_field']=$ci->enquiry_model->get_dyn_fld_by_query($cmnt_id,$enq->enquiry_id,$tid,0);
               //print_r($data['dynamic_field']); exit();
               $data['products'] =array();
               $data['product_contry']=array();
               $data['leadsource']=array();
            $ci->db->select('form_type,form_for,is_delete,is_edit');
            $ci->db->where('id',$tid);        
            $r    =      $ci->db->get('forms')->row_array();
            $data['form_type'] = $r['form_type'];
          
            $data['form_for'] = $r['form_for'];
            $data['action'] = array('delete'=>$r['is_delete'],'edit'=>$r['is_edit']);
            $data['state_list']   = $ci->location_model->estate_list();
            $data['city_list']      = $ci->location_model->ecity_list();
            $data['all_country_list']   = $ci->location_model->country();
            $data['name_prefix']    = $ci->enquiry_model->name_prefix_list();
            $data['tabname'] = $tabname;       
          
            $ci->load->view('enquiry/edit_dynamic_query_data',$data);
        }
        else if($post['task']=='save')
        {
        }
      }
    }


    /****************************************************student edit profile********************************************/
    // public function create_from() {
    // 	$userno     = $this->session->user_id;
    // 	$proccessno = $this->session->process;
    //        $data['leadsource'] = $this->Leads_Model->get_leadsource_list();
    //        $data['lead_score'] = $this->Leads_Model->get_leadscore_list();
    // 	$data["userno"]     = $userno;
    // 	$data["proccessno"]     = $proccessno;
    //        // print_r($proccessno);exit();
    //        $data['title'] = display('new_enquiry');
    //        $this->form_validation->set_rules('mobileno', display('mobileno'), 'max_length[20]|required', array('is_unique' => 'Duplicate   entry for phone'));
    //        $enquiry_date = $this->input->post('enquiry_date');
    //        if($enquiry_date !=''){
    //          $enquiry_date = date('Y-m-d', strtotime($enquiry_date));
    //        }else{
    //          $enquiry_date = date('Y-m-d', strtotime($enquiry_date));
    //        } 
    //       $city_id= $this->db->select("*")
    // 		->from("city")
    // 		->where('id',$this->input->post('city_id'))
    // 		->get();
    //        $other_phone = $this->input->post('other_no[]');
    // 	$usrarr = $this->db->select("*")
    // 						 ->where("pk_i_admin_id", $userno)
    // 						 ->from("tbl_admin")
    // 						 ->get()
    // 						 ->row();
    //        if ($this->form_validation->run() === true) {
    //            $name = $this->input->post('enquirername');
    //            $name_w_prefix = $name;
    //            $encode = $this->get_enquery_code();
    //            if(!empty($other_phone)){
    //               $other_phone =   implode(',', $other_phone);
    //            }else{
    //                $other_phone = '';
    //            }
    //            $postData = [
    //                'Enquery_id' => $encode,
    //                'user_role' => $this->session->user_role,
    //                'email' => $this->input->post('email', true),
    //                'phone' => $this->input->post('mobileno', true),
    // 			'comp_id'    => $usrarr->companey_id,
    //                'other_phone'=> $other_phone,
    //                'name_prefix' => $this->input->post('name_prefix', true),
    //                'name' => $name_w_prefix,
    //                'lastname' => $this->input->post('lastname'),
    //                'gender' => $this->input->post('gender'),
    //                'reference_type' => $this->input->post('reference_type'),
    //                'reference_name' => $this->input->post('reference_name'),
    //                'enquiry' => $this->input->post('enquiry', true),
    //                'enquiry_source' => $this->input->post('lead_source'),
    //                'enquiry_subsource' => $this->input->post('sub_source'),
    //                'company' => $this->input->post('company'),
    //                'address' => $this->input->post('address'),
    //                'checked' => 0,
    //                'product_id' => $this->input->post('product_id'),
    //                'institute_id' => $this->input->post('institute_id'),
    //                'datasource_id' => $this->input->post('datasource_id'),
    //                'center_id' => $this->input->post('center_id'),
    //                'ip_address' => $this->input->ip_address(),
    //                'created_by' => $this->session->user_id,
    //                'city_id' => $city_id->row()->id,
    // 			'state_id' => $city_id->row()->state_id,
    // 			'country_id'  =>$city_id->row()->country_id,
    //                'region_id'  =>$city_id->row()->region_id,
    //                'territory_id'  =>$city_id->row()->territory_id,
    //                'created_date' =>$enquiry_date, 
    //                'status' => 1
    //            ];
    //            if ($this->enquiry_model->create($postData)) {
    //                $insert_id = $this->db->insert_id();
    //                $this->Leads_Model->add_comment_for_events(display("enquery_create"), $encode);				
    //                echo '<br><br>Your Enquiry has been  Successfully created';
    //            }
    //        } else {

    // 		if(!empty($usrarr)){
    // 			$compno = $usrarr->companey_id;
    // 		}else{
    // 			$compno = "";
    // 		}


    //            $this->load->model('Dash_model', 'dash_model');
    //            $data['name_prefix'] = $this->enquiry_model->name_prefix_list();
    //           // $user_role    =   $this->session->user_role;
    //            $data['products'] = $this->dash_model->get_user_product_list();
    //            $data['product_contry'] = $this->location_model->productcountry();
    //            $data['institute_list'] = $this->Institute_model->institutelist();
    //            $data['datasource_list'] = $this->Datasource_model->datasourcelist();
    //            $data['datasource_lists'] = $this->Datasource_model->datasourcelist2();
    //            $data['subsource_list'] = $this->Datasource_model->subsourcelist();
    //            $data['center_list'] = $this->Center_model->all_center();
    //            $data['state_list'] = $this->location_model->estate_list();
    //            $data['city_list'] = $this->location_model->ecity_list();
    //            $data['country_list'] = $this->location_model->ecountry_list();
    //            $data['company_list'] = $this->location_model-> get_company_list_api($proccessno, $compno );
    // 	//	echo $this->db->last_query();
    //            $this->load->view('create_newenq', $data);
    //        }
    //    }
    /*public function zip_extract(){         
             $zip = new ZipArchive;
             $filename = 'aws.zip';
             $res = $zip->open("third_party/".$filename);
             if ($res === TRUE) {
               // Unzip path
               $extractpath = "third_party/aws/";
               // Extract file
               $zip->extractTo($extractpath); 
               $zip->close();
               echo "success";
               
             } else { 
                echo "error"; 
             }
                
        }*/
    public function get_enquiry_by_code($enquiry_code)
    {
        $data    =   $this->enquiry_model->enquiry_by_code($enquiry_code);
        if (!empty($data)) {
            echo json_encode($data);
        } else {
            echo 0;
        }
    }
    public function get_institute_tab_content($enquiry_id)
    {
        $data = array();
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
        $data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        $data['course_list'] = $this->Leads_Model->get_course_list();
        $data['institute_app_status'] = $this->Institute_model->get_institute_app_status();
        $data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        //echo $this->db->last_query();
        $data['ins_list'] = $this->location_model->get_ins_list($data['details']->Enquery_id);
        if ($this->session->companey_id == '67') {
            $data['discipline'] = $this->location_model->find_discipline();
            $data['level'] = $this->location_model->find_level();
            $data['length'] = $this->location_model->find_length();
        }
        echo $this->load->view('enquiry/institute_tab_content', $data, true);
    }
public function timelinePopup()
{
    $data='Stage Update';
    $subject='';
    $tempname='';
    $created_at='';
    $timelineId=$this->input->post('timelineId');
    $log=$this->db->select('msg_logs.*')->where('timelineId',$timelineId)->get('msg_logs');
    // print_r($log->result());
    if($log->num_rows()==1){
        foreach ($log->result() as $key => $value) {
           $subject='Subject: '.$value->subject;
           $created_at='Created At: '.$value->created_at;
           $data='<b>Message:</b>'.$value->msg;
           $tempname='';
           if($value->msg_type==0 OR $value->msg_type==2){
            $tempname=  'Template Name: '.$this->Message_models->tempName($value->temp_id);
           }
        }
    }
    echo json_encode(array('subject'=>$subject,'created_at'=>$created_at,'msg'=>$data,'tempname'=>$tempname));
    // }else{
    //     echo json_encode(array('msg'=>'Stage Updated'));
    // }
}
public function EnqtimelinePopup()
{
    $details1='';
    $timelineId=$this->input->post('timelineId');
    $log=$this->db->where('comm_id',$timelineId)->get('tbl_comment');
    // print_r($log->result());
    if($log->num_rows()==1){
        $message='';
        foreach ($log->result() as $key => $value) {
            if(!empty($value->msg)){ 
                $message=json_decode($value->msg);
                $message=$message->message;
            } 
            $remark=$value->remark;
            if(empty($value->remark) OR $value->remark==' '){ 
               
                $remark=$value->comment_msg;
            } 
            $details1 = '
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title" id="timlineTitle">'.$remark.'</h4>
               </div>
               <div class="row" style="padding: 50px;">
               <div id="timelinesdata">'.$message.'</div>
               </div>
               <div class="modal-footer">
                 <div class="row">
                   <div class="col-md-4">
                   <h4  id="timeline-cratedate">'.$value->created_date.'</h4>
                   </div>
                   <div class="col-md-4">
                   </div>
                   <div class="col-md-4">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   </div>
                 </div>
               </div>
          ';
        }
    }
echo  $details1;
}
 public function insertCommercialInfo()
 {
    //print_r($_POST); exit();
    $enquiry_id=$this->input->post('enquiry_id');
    $infoid=$this->input->post('infoid');

    if(empty($infoid)){

        $delivery_branch=$this->input->post('delivery_branch');
        $discount=$this->input->post('discount')??0;
        $potential_tonnage=$this->input->post('potential_tonnage')??0;   
        $expected_tonnage=$this->input->post('expected_tonnage')??0;

        //print_r($delivery_branch); exit();
        $del_count = count($delivery_branch);
        foreach ($delivery_branch as $delivery_branch)
        {
            $comp_id=$this->session->companey_id;
            $type=$this->input->post('type');
            $booking_type=$this->input->post('booking_type');
            $business_type=$this->input->post('business_type');
            $booking_branch=$this->input->post('booking_branch');
            if($del_count>1)
            {
                $rate = 0;
                $getrate= $this->db->where(array('booking_branch'=>$booking_branch,'delivery_branch'=>$delivery_branch))->get('branchwise_rate')->row();
                if(!empty($getrate) && !empty($getrate->rate))
                    $rate = $getrate->rate;

                $x = $rate * $potential_tonnage * 1000;
                $y = $rate * $expected_tonnage * 1000;

                $potential_amount = round($x - (( $x * $discount )/100),2);
                $expected_amount = round($y - (( $y * $discount )/100),2);


            }
            else
            {
                $rate = $this->input->post('rate');
                $potential_amount=$this->input->post('potential_amount');
                $expected_amount=$this->input->post('expected_amount');
            }



            $insurance=$this->input->post('insurance');
            

            $paymode=$this->input->post('paymode');
                    
            $vehicle_type=$this->input->post('vehicle_type');
            $capacity=$this->input->post('capacity');
            $invoice_value=$this->input->post('invoice_value');
            $ftlpotential_amount=$this->input->post('ftlpotential_amount');
            $ftlexpected_amount=$this->input->post('ftlexpected_amount');
            $invoice_value=$this->input->post('invoice_value');
            $url=base_url('enquiry/view/'.$enquiry_id.'');
            if($booking_type==0){
             $data=[ 'enquiry_id'=>$enquiry_id,
                    'branch_type'=>$type,
                    'booking_type'=>$booking_type,
                    'business_type'=>$business_type,
                    'booking_branch'=>$booking_branch,
                    'delivery_branch'=>$delivery_branch,
                    'rate'=>$rate,
                    'discount'=>$discount,
                    'insurance'=>$insurance,
                    'paymode'=>$paymode,
                    'potential_tonnage'=>$potential_tonnage,
                    'potential_amount'=>$potential_amount,
                    'expected_tonnage'=>$expected_tonnage,
                    'expected_amount'=>$expected_amount,
                    'createdby'=>$this->session->userdata('user_id'),
                    'comp_id'=>$comp_id
                  ];
                }elseif($booking_type==1){
                    $data=[ 'enquiry_id'=>$enquiry_id,
                    'branch_type'=>$type,
                    'booking_type'=>$booking_type,
                    'business_type'=>$business_type,
                    'booking_branch'=>$booking_branch,
                    'delivery_branch'=>$delivery_branch,
                    'insurance'=>$insurance,
                    'paymode'=>$paymode,
                    'potential_amount'=>$ftlpotential_amount,
                    'expected_amount'=>$ftlexpected_amount,
                    'vehicle_type'=>$vehicle_type,
                    'carrying_capacity'=>$capacity,
                    'invoice_value'=>$invoice_value,
                    'createdby'=>$this->session->userdata('user_id'),
                    'comp_id'=>$comp_id
                  ]; 
                 
                }
                $insert=$this->enquiry_model->insertComInfo($data);
        }

        //exit();

        if($insert){
        $this->session->set_flashdata('message', 'Commercial information inserted successfully');
        
        }else{
        $this->session->set_flashdata('error', 'Error while submiting data ');
        
        }
        if($this->input->post('redirect_url')){
            redirect($this->input->post('redirect_url')); //updateclient                
        }else{
            redirect($this->agent->referrer()); //updateclient
        }
    }else{
        $comp_id=$this->session->companey_id;
        $type=$this->input->post('type_update');
        $booking_type=$this->input->post('booking_type');
        $business_type=$this->input->post('business_type');
        $booking_branch=$this->input->post('booking_branch_update');
        $delivery_branch=$this->input->post('delivery_branch_update');
        $insurance=$this->input->post('insurance');
        $rate=$this->input->post('rate');
        $discount=$this->input->post('discount');
        $paymode=$this->input->post('paymode');
        $potential_tonnage=$this->input->post('potential_tonnage');
        $potential_amount=$this->input->post('potential_amount');
        $expected_tonnage=$this->input->post('expected_tonnage');
        $expected_amount=$this->input->post('expected_amount');
        $vehicle_type=$this->input->post('vehicle_type');
        $capacity=$this->input->post('capacity');
        $invoice_value=$this->input->post('invoice_value');
        $ftlpaymode=$this->input->post('ftlpaymode');
        $ftlpotential_amount=$this->input->post('ftlpotential_amount');
        $ftlexpected_amount=$this->input->post('ftlexpected_amount');
        $invoice_value=$this->input->post('invoice_value');
        $url=base_url('enquiry/view/'.$enquiry_id.'#COMMERCIAL_INFORMATION');
        if($booking_type==0){
         $data=[ 
                'branch_type'=>$type,
                'booking_type'=>$booking_type,
                'business_type'=>$business_type,
                'booking_branch'=>$booking_branch,
                'delivery_branch'=>$delivery_branch,
                'rate'=>$rate,
                'discount'=>$discount,
                'insurance'=>$insurance,
                'paymode'=>$paymode,
                'potential_tonnage'=>$potential_tonnage,
                'potential_amount'=>$potential_amount,
                'expected_tonnage'=>$expected_tonnage,
                'expected_amount'=>$expected_amount,
              ];
            }elseif($booking_type==1){
                $data=[
                'branch_type'=>$type,
                'booking_type'=>$booking_type,
                'business_type'=>$business_type,
                'booking_branch'=>$booking_branch,
                'delivery_branch'=>$delivery_branch,
                'insurance'=>$insurance,
                'paymode'=>$ftlpaymode,
                'potential_amount'=>$ftlpotential_amount,
                'expected_amount'=>$ftlexpected_amount,
                'vehicle_type'=>$vehicle_type,
                'carrying_capacity'=>$capacity,
                'invoice_value'=>$invoice_value,
              ]; 
             
            }
              $insert=$this->db->where(array('comp_id'=>$comp_id,'id'=>$infoid))->update('commercial_info',$data);
            if($insert){
            $this->session->set_flashdata('message', 'Commercial information Updated successfully');
            // redirect($url);
            redirect($this->agent->referrer()); //updateclient
            }else{
            $this->session->set_flashdata('error', 'Error while submiting data ');
            redirect($url);
            } 
    }
 }

 public function update_info_status()
 {
    $commerical_id = $this->input->post('id');
    $status = $this->input->post('status');
     $this->db->where('id',$commerical_id);
     $this->db->where('comp_id',$this->session->companey_id);
     $this->db->set('status',$status);
     $this->db->set('updation_date',date('Y-m-d H:i:s'));
     $res = $this->db->update('commercial_info');

    $en =  $this->db->where('id',$commerical_id)->get('commercial_info')->row();
    $endata = $this->db->select('Enquery_id')->where('enquiry_id',$en->enquiry_id)->get('enquiry')->row();

     if($res)
     {
        $this->load->model('Leads_Model');
        $this->Leads_Model->add_comment_for_events('Commercial Info Status Updated',$endata->Enquery_id);
        $notimsg = '';
        if($status == 1){
            $notimsg = 'Deal Approved';
        }else if($status == 2){
            $notimsg = 'Deal Rejected';
        }
        if(!empty($notimsg)){
            $this->Leads_Model->add_comment_for_events_popup('',date('d-m-Y'),'','','','',date('H:i:s'),$endata->Enquery_id,0,$notimsg,1,'',$en->createdby);
           
            $user_row = $this->user_model->read_by_id($en->createdby);
            if(!empty($user_row)){
                $this->message_models->smssend($user_row->s_phoneno, $notimsg);                
                $this->message_models->sendwhatsapp($user_row->s_phoneno, $notimsg);
                $this->message_models->send_email($user_row->s_user_email, 'Deal Notification', $notimsg);
            }
            
        }
     }
     echo '1';
 }
    public function get_rate()
    {
      $bbranch=$this->input->post('booking_branch');
      $dbranch=$this->input->post('delivery_branch');
      $data=['rate'=>0];
     $getrate= $this->db->where(array('booking_branch'=>$bbranch,'delivery_branch'=>$dbranch))->get('branchwise_rate');
     if($getrate->num_rows()==1){
         $rates=$getrate->row();
        $data=['rate'=>(int)$rates->rate];
     }
     echo json_encode($data);
    }
    public function editinfo()
    {
        // if(user_role('1002')){}

         $id=$this->uri->segment('3');
        $comp_id=$this->session->companey_id;
      $count=$this->db->where(array('id'=>$id,'comp_id'=>$comp_id))->get('commercial_info');
      //check exist of not
      if($count->num_rows()==1){
        $data['page_title'] = 'Edit Commercial Info';
        $data['info'] = $count->result();
        // if ($this->session->companey_id == 65) {
            $data['branch']=$this->db->where('comp_id',$comp_id)->get('branch')->result();
        // } 
	   echo $this->load->view('enquiry/edit-info',$data,true);
    }
       }
       public function deleteInfo()
       {
            if(user_role('1001')){}
        $id=$this->uri->segment('3');
        $enquiry_id=$this->uri->segment(4);
        $comp_id=$this->session->companey_id;
          $count=$this->db->where(array('id'=>$id,'comp_id'=>$comp_id))->get('commercial_info');
          $url=base_url('enquiry/view/'.$enquiry_id.'');
          //check exist of not
          if($count->num_rows()==1){
         $insert= $this->db->where(array('id'=>$id,'comp_id'=>$comp_id))->delete('commercial_info');
                $this->db->where(array('deal_id'=>$id))->delete('deal_data');
          if($insert){
            $this->session->set_flashdata('message', 'Commercial information Deleted successfully');
            redirect($url);
            }else{
            $this->session->set_flashdata('error', 'Error while submiting data ');
            redirect($url);
            } 
        }
       }
       public function sendAgreement()
       {
           
       }
       public function genAgreement()
       {
        $this->load->library('pdf');
        // $download=$this->input->post('download');
        $enquiry_id=$this->input->post('enquiry_id');
        $agg_date=$this->input->post('agg_date');
        $data['enquiry_id']=$enquiry_id;
        $this->db->where('comp_id', $this->session->companey_id);
        $data['enquiry'] = $this->db->where('enquiry_id',$enquiry_id)->get('enquiry')->result();
        $pdfFilePath1 = $_SERVER['DOCUMENT_ROOT']."/uploads/quotations/CAF-FORM.pdf";
        $message = 'Dear Sir/Madam,<br> Please find the Agreement attachment below.';
        $email_subject = 'V-Trans Agreement';
        $move_enquiry = $this->input->post('enquiry_id');
        $this->db->where('comp_id',$this->session->companey_id);
        $this->db->where('status',1);
        $email_row	=	$this->db->get('email_integration')->row_array();                        
        if(empty($email_row)){
                echo "Email is not configured";
                die();
        }else{            
            $config['smtp_auth']    = true;
            $config['protocol']     = $email_row['protocol'];
            $config['smtp_host']    = $email_row['smtp_host'];
            $config['smtp_port']    = $email_row['smtp_port'];
            $config['smtp_timeout'] = '7';
            $config['smtp_user']    = $email_row['smtp_user'];
            $config['smtp_pass']    = $email_row['smtp_pass'];
            $config['charset']      = 'utf-8';
            $config['mailtype']     = 'html'; // or html
            $config['newline']      = "\r\n";        
        }
        $this->load->library('email');
        $enq = $this->enquiry_model->enquiry_by_id($move_enquiry);
        $this->email->initialize($config);
        $this->email->from($email_row['smtp_user']);
        $to=$enq->email;
        $this->email->to($to);
        $this->email->subject($email_subject); 
        $this->email->message($message); 
        $this->email->set_mailtype('html');
        $this->email->attach($pdfFilePath1);
        if($this->email->send()){
            echo "Mail sent successfully";
            $data=['enq_id'=>$move_enquiry,'comp_id'=>$this->session->companey_id,'agg_date'=>$agg_date,'created_by'=>$this->session->user_id,'created_date'=>date('Y-m-d H:i:s')];
        }else{
            echo $this->email->print_debugger();
            echo "Something went wrong";			                	
        }
       }
 
    public function add_visit()
    {   
        if(user_role('1020')==true)
        {

        }
        $this->load->model(array('Client_Model','Enquiry_model'));
        if($post = $this->input->post())
        {
			$m_purpose=$this->input->post('m_purpose');
			$start_loc=$this->input->post('start_loc');
			$end_loc=$this->input->post('end_loc');
			$mannual_km=$this->input->post('mannual_km');
            $visit_type=$this->input->post('type');
            $visit_time=$this->input->post('visit_time');
            $visit_date=$this->input->post('visit_date');
            if($visit_type==1){
               $visit_time=date('H:i');
               $visit_date=date('Y-m-d');
            }else{
                $visit_date    =   $this->input->post('visit_date');
                $visit_date = date("Y-m-d",strtotime($visit_date));
            }
			
//For Finding user rate 			
$user_data = $this->db->get_where('tbl_admin',array('pk_i_admin_id' => $this->session->user_id))->row_array();
$rate_data = $this->db->get_where('discount_matrix',array('id' => $user_data['discount_id']))->row_array();
   if(!empty($rate_data)){
      $rate = $rate_data['rate_km'];
   }else{
      $rate = 10;
   }
//End

            //print_r($_POST); 
            $data = array('enquiry_id'=>$this->input->post('enq_id'),
                            'contact_id'=>$this->input->post('contact_id'),
                            'visit_date'=>$visit_date,
                            'visit_time'=>$visit_time,
							'm_purpose'=>$m_purpose,
							'start_location'=>$start_loc,
							'end_location'=>$end_loc,
							'manual_distence'=>$mannual_km,
							'user_rate'=>$rate,
                            'comp_id'=>$this->session->companey_id,
                            'user_id'=>$this->session->user_id,
                        );
			//echo '<pre>';print_r($data);exit;
            $res = $this->Enquiry_model->getEnquiry(array('Enquery_id'=>$data['enquiry_id']))->row();
            
            $mobileno = $res->phone;
            $email = $res->email;
            //$stage_time = $this->input->post('next_visit_time');
            $enq_code  = $res->Enquery_id;
            $data['enquiry_id'] = $res->enquiry_id;
            //print_r($data);exit;
            $notification_id = '';
            if($visit_type==2)
            {
                $notification_id = $this->input->post('visit_notification_id');
            }
        //   print_r($_POST);
        //   echo $visit_date;
            $this->Leads_Model->add_comment_for_events_popup('Visit',$visit_date, '', $mobileno, $email, '', $visit_time, $enq_code, $notification_id, 'Visit -'.$m_purpose,1,3);

           $vis_ids = $this->Client_Model->add_visit($data);
		   
//For insert expence data
$comp_id = $this->session->companey_id;
$user_id = $this->session->user_id;
$exp_data=['visit_id'=>$vis_ids,'type'=>1,'amount'=>($mannual_km)*$rate,'expense'=>0,'comp_id'=>$comp_id,'created_by'=>$user_id];
$this->db->insert('tbl_expense',$exp_data);
//End

         $contact=  $this->db->where('cc_id',$data['contact_id'])->get('tbl_client_contacts')->row();
         $cname ='';
         if(!empty($contact))
            $cname = $contact->c_name;
            $this->db->set('remark','Visit Created for '.$cname);

            $this->Leads_Model->add_comment_for_events('Visit Added',$res->Enquery_id);

            $this->session->set_flashdata('message','Visit Saved Successfully');            
            if($this->input->post('redirect_url')){
                redirect($this->input->post('redirect_url')); //updateclient                
            }else{
                redirect($this->agent->referrer()); //updateclient
            }
        }
    }
    public function delete_visit(){
        if(user_role('1021')==true)
        {
            
        }
        $this->Leads_Model->add_comment_for_events('Visit Deleted', $this->input->post('enq_code'));
        $id    =   $this->input->post('vid',true);
        $this->db->where('id',$id);
        $this->db->where('comp_id',$this->session->companey_id);
        echo $this->db->delete('tbl_visit');
    }
    public function twopoints_on_earth($latitudeFrom, $longitudeFrom,$latitudeTo,$longitudeTo) 
    { 
    $long1 = deg2rad($longitudeFrom); 
    $long2 = deg2rad($longitudeTo); 
    $lat1 = deg2rad($latitudeFrom); 
    $lat2 = deg2rad($latitudeTo); 
    //Haversine Formula 
    $dlong = $long2 - $long1; 
    $dlati = $lat2 - $lat1; 
    $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2); 
    $res = 2 * asin(sqrt($val)); 
    $radius = 3958.756; 
    return ($res*$radius); 
    } 
    public  function points_on_earth($p1,$p2,$l1,$l2)
    {  
        $inmiles=$this->twopoints_on_earth( $p1, $p2, $l1,  $l2); 
         return  $inmiles * 1.60934;
     }
    public function abs_diff($v1, $v2) {
        $diff = $v1 - $v2;
        return $diff < 0 ? (-1) * $diff : $diff;
    }
    public function notify_rmanager()
    {
       $remarks= $this->input->post('remarks');
       $visit_id= $this->input->post('visit_id');
       $user_id=$this->session->user_id;
      $report_touser= $this->db->select('report_to,pk_i_admin_id')->where('pk_i_admin_id',$user_id)->get('tbl_admin')->row();
       $report_to=$report_touser->report_to;
                $ins_arr = array(
                    'comp_id'        =>  $this->session->companey_id,
                    'subject'        =>  'Need for approval',
                    'task_type'      =>  18,
                    'task_status'      =>  2,
                    'query_id'       =>  $visit_id,
                    'task_remark'    =>  $remarks,
                      'task_date'    =>   date("d-m-Y"),
                    'task_time'        => date("H:i:s"),
                    'related_to' => $report_to,
                    'create_by'      =>  $this->session->user_id
                );
                $this->db->insert('query_response',$ins_arr);
                $this->session->set_flashdata('message', 'Request successfully submitted');
                // redirect($url);
                redirect($this->agent->referrer()); 
    }
    
    public function visit_load_data()
    {
         
        $this->load->model('visit_datatable_model');
        $result = $this->visit_datatable_model->getRows($_POST);
		//print_r($result);exit;
        //echo $this->db->last_query(); exit();
        // print_r($this->db->last_query());
        $colsall  = true;
        $cols = array();
        if(!empty($_POST['allow_cols']))
        {
            $cols  = explode(',',$_POST['allow_cols']);
            $colsall = false;
        }
        //print_r($cols); exit();
        $count_minus=0;
        $data = array();
        $ix=1;
        $visit_expSum_s =0;
        $visit_otexpSum_s =0;
        $total_expSum_s =0;
        foreach ($result as $res)
        {
			//For update old rate after chenge in rate
			/*
			                             $this->db->select('rate_km');
										$this->db->from('discount_matrix');
                                        $this->db->join('tbl_admin','tbl_admin.discount_id=discount_matrix.id','left'); 
                                        $this->db->where('tbl_admin.pk_i_admin_id',$res->user_id);										
                                      $rate = $this->db->get()->row();

			if(!empty($rate->rate_km)){
            $this->db->set('user_rate',$rate->rate_km);
            $this->db->where('id', $res->vids);
            $this->db->update('tbl_visit');
			
			$totalexp=($res->actualDistance)*$rate->rate_km;
			
			$this->db->set('amount',$totalexp);
            $this->db->where('visit_id', $res->vids);
            $this->db->update('tbl_expense');
			}
		     */
		    //End	
$en_title = $this->db->select('title')->from('enquiry_status')->where('status_id',$res->enq_type)->get()->row();
		
            $visit_totalexp= $this->db->where(array('tbl_expense.visit_id'=> $res->vids))->count_all_results('tbl_expense');
            $visit_reject= $this->db->where(array('tbl_expense.visit_id'=> $res->vids,'approve_status' => 1))->count_all_results('tbl_expense');
            $visit_approve= $this->db->where(array('tbl_expense.visit_id'=> $res->vids,'approve_status' => 2))->count_all_results('tbl_expense');
            $visit_pending= $this->db->where(array('tbl_expense.visit_id'=> $res->vids,'approve_status' => 0))->count_all_results('tbl_expense');
            $expstatus='N/A';
            if($visit_totalexp!=0)
            {
                    if($visit_reject==$visit_totalexp){
                        $expstatus='Rejected ';
                    }elseif($visit_approve==$visit_totalexp){
                        $expstatus='Approved';
                    }elseif($visit_pending==$visit_totalexp){
                        // $expstatus='Pending';
                        $expstatus='Pending';
                    }elseif($visit_reject!=0 AND $visit_approve!=0 OR $visit_pending!=0){
                        $expstatus='Partial';
                    }         
             }
            if(!empty($_POST['expensetype']))
            {
                        $type = $_POST['expensetype'];
                        if($type=='1' && $expstatus!='Approved')
                        {
                            $count_minus++;
                                continue;
                        }
                        if($type=='2' and $expstatus!='Pending')
                        {
                            $count_minus++;
                                continue;
                        }
                        if($type=='3' and $expstatus!='Rejected')
                        {
                          $count_minus++;
                                continue;
                        }
                        if($type=='4' and $expstatus!='Partial')
                        {
                           $count_minus++;
                                continue;
                        }
            }

             /* $visit_expSum=round(abs($res->visit_expSum));
             $visit_otexpSum=round(abs($res->visit_otexpSum));
             $total_expSum=round(abs($res->visit_expSum+$res->visit_otexpSum)); */
// Manual only if not empty

            if(!empty($res->visit_expSumM)){
                $visit_expSum=round(abs($res->visit_expSumM));
            }else{
                $visit_expSum=round(abs($res->visit_expSum));
            }
             
             if(!empty($res->visit_otexpSumM)){
                $visit_otexpSum=round(abs($res->visit_otexpSumM));
             }else{
                $visit_otexpSum=round(abs($res->visit_otexpSum));
             }
             
             $total_expSum=round(abs($visit_otexpSum+$visit_expSum));
			 
//End
             $percentChange=0;
            /*$km_rate = $this->user_model->get_user_meta($res->user_id,array('km_rate'));
            if(!empty($km_rate['km_rate'])){$rate= $km_rate['km_rate'];}else{
              $rate=0;;
              } */
			  
			  if(!empty($res->rate_km)){
                $rate = $res->rate_km;
                }else{
                    $rate = 0;
                }
				
            $totalpay=($res->actualDistance)*$rate;
            $idealamt=($res->idealDistance)*$rate;
         if($idealamt > 0 && $totalpay > 0){
         $dif= $this->abs_diff($idealamt,$totalpay);
              $percentChange = (($totalpay - $idealamt) / $idealamt)*100;
                }
           
            $sub = array();
            //$time = $res->visit_time=='00:00:00'?null:date("g:i A", strtotime($res->visit_time));
			
//open up line remove below $time 2 line when apk on play store
			$time = explode(' ',$res->created_at);
			$time = date("g:i A", strtotime($time[1]));
			
            $first = '';
            if($this->session->user_id!=$res->user_id)
            $first = '<input  type="checkbox" name="approve[]" class="checkbox1"  value="'.$res->vids.'"> ';
            $sub[] = $first.' '.$ix++;

            if($colsall || in_array(1,$cols))
                $sub[] = $res->visit_date!='0000-00-00'?date("d-m-Y", strtotime($res->visit_date)):'NA';

            if($colsall || in_array(2,$cols))
                $sub[] = $time??'NA';
			
			if($colsall || in_array(13,$cols))
                $sub[] = $res->m_purpose??'NA';

            if(!empty($_POST['view_all']))
            {
                if($res->enq_type=='1')
                    $url = base_url('enquiry/view/').$res->enquiry_id;
                else if($res->enq_type=='2')
                    $url = base_url('lead/lead_details/').$res->enquiry_id;
                else
                    $url = base_url('client/view/').$res->enquiry_id;

                    if($colsall || in_array(3,$cols))
                    $sub[] = '<a href="'.$url.'">'.$res->name.'</a>'??'NA';
            }
            if($colsall || in_array(10,$cols)){
                $sub[] = $res->company_name??'NA';            
            }
            if($colsall || in_array(14,$cols)){
                $sub[] = $res->client_name??'NA';            
            }
             if($colsall || in_array(15,$cols)){
                $sub[] = $res->contact_person??'NA';            
            }
            if($colsall || in_array(16,$cols)){
                $sub[] = empty($res->start_location)?'NA':$res->start_location;            
            }
             if($colsall || in_array(17,$cols)){
                $sub[] = empty($res->end_location)?'NA':$res->end_location;            
            }
            if($colsall || in_array(4,$cols))
            $sub[] =$res->idealDistance.' Km';
        
        if($colsall || in_array(5,$cols))
            $sub[] =$res->actualDistance.' Km';
		
		if($colsall || in_array(30,$cols))
            $sub[] =empty($res->manual_distence)?'0 KM':$res->manual_distence.' Km';
		
            if($colsall || in_array(6,$cols))
                $sub[] = $res->rating!=''?$res->rating:'NA';


        if($colsall || in_array(28,$cols)){
            $sub[] = $res->remarks;
        }
		
		if($colsall || in_array(29,$cols))
            $sub[] = ucwords($res->emp_region)??'NA';
		
        if($colsall || in_array(7,$cols)){
            $sub[] = $res->employee;
        }
        
                
        if($colsall || in_array(11,$cols))
        $sub[] = '<span class="diff">'.round(abs($percentChange)).'</span>';

        if($colsall || in_array(8,$cols)){
            $sub[] = round(abs($visit_expSum));
        }
        if($colsall || in_array(18,$cols)){         
            $sub[] = round(abs($visit_otexpSum));
        }
        if($colsall || in_array(19,$cols)){         
            $sub[] = round(abs($total_expSum));
        }
        if($colsall || in_array(20,$cols)){         
            $sub[] = '<span class="expstatus">'.$expstatus.'<span>';
        }
                
        if($colsall || in_array(21,$cols)){  
            $sub[] = $res->region_name??'NA';                
        }
        if($colsall || in_array(22,$cols)){  
            $sub[] = $res->branch_name??'NA';                
        }
        if($colsall || in_array(23,$cols)){  
            $sub[] = $res->area_name??'NA'; 
        }
        if($colsall || in_array(24,$cols)){                 
            $sub[] = $en_title->title??'NA';                
        }
        if($colsall || in_array(25,$cols)){                         
            if(!empty($res->start_time) && !empty($res->end_time)){
                $datetime1 = new DateTime($res->start_time);
                $datetime2 = new DateTime($res->end_time);
                $interval = $datetime1->diff($datetime2);
                $sub[] =  $interval->format('%Y-%m-%d %H:%i:%s');
            }else{
                $sub[] = 'NA';    
            }
        }

        if($colsall || in_array(26,$cols)){                 
            $sub[] = $res->city??'NA';    
        }
        if($colsall || in_array(27,$cols)){                 
            $sub[] = $rate??'NA'; 
        }   

            if($colsall || in_array(9,$cols))
            {
               $act  = "<a class='btn btn-xs btn-primary' href='".base_url('visits/visit_details/'.$res->vids.'/')."' ><i class='fa fa-map-marker'></i></a> ";

             $act.= "<a class='btn btn-xs btn-warning checkvisit'   data-toggle='modal' data-target='#add_expense' onclick='checkvisit(".$res->vids.")' id='checkvisit' ><i class='fa fa-plus'></i></a>";

                  $sub[] = $act;
            }


            $data[] =$sub;
            
            $visit_expSum_s += $visit_expSum;
            $visit_otexpSum_s +=$visit_otexpSum;
            $total_expSum_s += $total_expSum;
            
        }
    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$this->visit_datatable_model->countAll(),
            "recordsFiltered" => $this->visit_datatable_model->countFiltered($_POST)-$count_minus,
            "data" => $data,
            "totalotherExpense" => $visit_otexpSum_s,
            "totaltravelExp" =>$visit_expSum_s,
            "totalExpense" =>$total_expSum_s,
        );
		//print_r($output);exit;
        echo json_encode($output);
    }

    public function deals_load_data()
    {
		//print_r($_POST['curr_stg']);exit;
        $this->load->model('Deals_datatable_model');
        $result = $this->Deals_datatable_model->getRows($_POST);
        //echo count($result); exit();
        //echo $this->db->last_query(); exit();

        $colsall  = true;
        $cols = array();
        if(!empty($_POST['allow_cols']))
        {
            $cols  = explode(',',$_POST['allow_cols']);
            $colsall = false;
        }

        $data = array();
        foreach ($result as $value)
        {
            $sub = array();

            $sub[] = $value->id;
			
			if($colsall || in_array(24,$cols))
            $sub[] =$value->quatation_number??'NA';
		
		     if($colsall || in_array(25,$cols))
            $sub[] =(int)(($value->qotation_amt*100))/100??'NA';

             if(!empty($_POST['view_all']))
             {
                if($value->enq_type=='1')
                    $url = base_url('enquiry/view/').$value->enquiry_id;
                else if($value->enq_type=='2')
                    $url = base_url('lead/lead_details/').$value->enquiry_id.'#COMMERCIAL_INFORMATION';
                else if($value->enq_type=='3')
                    $url = base_url('client/view/').$value->enquiry_id.'#COMMERCIAL_INFORMATION';
                else
                    $url = base_url('client/view/').$value->enquiry_id.'#COMMERCIAL_INFORMATION';

                if($colsall || in_array(1,$cols))
                    $sub[] = '<a href="'.$url.'">'.$value->name.'</a>'??'NA';
            }			
			

            if($colsall || in_array(21,$cols))
            $sub[] =$value->company_name??'NA';  

            if($colsall || in_array(22,$cols))
            $sub[] =$value->client_name??'NA';            

            if($colsall || in_array(3,$cols))
            $sub[] =strtoupper(($value->booking_type))??'NA';
            if($colsall || in_array(4,$cols))
            $sub[] = ucwords($value->business_type).'ward'??'NA';
		
		    if($colsall || in_array(27,$cols))
            $sub[] = ucwords($value->region_name)??'NA';
		
		    if($colsall || in_array(26,$cols))
            $sub[] = ucwords($value->cre_to)??'NA';
            
            if($colsall || in_array(18,$cols)){    
                $creation_ago = !empty($value->creation_date)?'<span class="badge">'.get_time_ago(strtotime($value->creation_date)).'</span>':'';
                $created_column = !empty($value->creation_date)?date('d-M-Y H:i:s A',strtotime($value->creation_date)):'NA';                
                $sub[]= $created_column.' '.$creation_ago;
            }
            if($colsall || in_array(23,$cols)){    
                $pending = '';
                if(!empty($value->updation_date)){
                    $date1 = new DateTime(date('Y-m-d', strtotime($value->updation_date)));
                    $date2 = new DateTime(date('Y-m-d'));
                    $diff = $date1->diff($date2)->days;
                // echo $diff;
                    if((int)$diff>=5){
                        $pending = '<span class="badge badge-danger">Pending</span>';
                    }
                }
                //echo $pending;
                $updated_column = !empty($value->updation_date)?get_time_ago(strtotime($value->updation_date)):'NA';
                $sub[] = $updated_column.' '.$pending;
            }

            $stts = $value->status;

            if($colsall || in_array(19,$cols))
            {
			if($value->original=='1'){
                $this->db->where('deal_id',$value->id);
                $this->db->where('request_from_uid',$this->session->user_id);
                $req_log = $this->db->get('deal_approval_history')->row_array();
                if($value->edited=='1' && ($value->approval=='pending' || $value->approval == '')){                
                    if(!empty($req_log)){
                        if($req_log['status'] == ''){
                            $sub[]  ='<a href="'.base_url('client/ask_deal_approval/'.$value->id).'" class="sfa" onclick="return confirm(\'Send For Approval\')">
                            <label class="label label-warning text-black">Send For Approval</label>
                            </a>'; 
                        }else if($req_log['status'] == 'pending'){
                            $sub[] ='<label class="label label-primary">Waiting for approval</label>
                            ';
                        }
                    }else{                     
                        $this->db->where('deal_id',$value->id);
                        $this->db->where('request_to_uid',$this->session->user_id);
                        $req_log2 = $this->db->get('deal_approval_history')->row_array();
                        if(!empty($req_log2)){
                            if($req_log2['status'] != ''){
                                $opt  ='<select onchange="location.href=\''.base_url('client/deal_action/'.$value->id.'/').'\'+this.value">';
                                $opt.='<option value="">Action</option>
                                    <option value="approve">Approve</option>
                                    <option value="reject">Reject</option>
                                    <option value="resend">Send for Approval</option>
                                ';
                                $opt.='</select>';
                                $sub[] = $opt;
                            }else if($req_log2['status'] == ''){
                                $sub[] ='<label class="label label-danger">Edited</label>';
                            }
                        }else{
                            $sub[] ='<label class="label label-danger">Edited</label>';
                        }
                    }
                }else{
                    $sub[] = '<select onchange="update_info_status('.$value->id.',this.value)">
                        <option value="0" '.($value->status==0?'selected':'').'>Pending</option>
                        <option value="1" '.($value->status==1?'selected':'').'>Done</option>
                        <option value="2" '.($value->status==2?'selected':'').'>Deferred</option>
                        </select>';    
                }
                // if($value->edited=='1' && $value->approval==''){
                //     if($req_log['status'] == ''){
                //        $sub[]  ='<a href="'.base_url('client/ask_deal_approval/'.$value->id).'" onclick="return confirm(\'Send For Approval\')">
                //             <label class="label label-warning text-black">Send For Approval</label>
                //             </a>';
                //     }
                //     else
                //     {
                //         $sub[] ='<label class="label label-danger">Edited</label>';
                //     }
                    
                // }
                // else if($value->edited=='1' && $value->approval=='pending')
                // {
                //     if($value->createdby==$this->session->user_id)
                //     {
                //         $sub[] ='<label class="label label-primary">Waiting for approval</label>
                //         ';
                //     }
                //     else
                //     {
                //         $opt  ='<select onchange="location.href=\''.base_url('client/deal_action/'.$value->id.'/').'\'+this.value">';
                //             $opt.='<option value="">Action</option>
                //                     <option value="approve">Approve</option>
                //                     <option value="reject">Reject</option>
                //                     <option value="resend">Send for Approval</option>
                //                 ';
                //         $opt.='</select>';
                //          $sub[] = $opt;
                //     }
                // }
                // else
                // {
                // $sub[] = '<select onchange="update_info_status('.$value->id.',this.value)">
                //         <option value="0" '.($value->status==0?'selected':'').'>Pending</option>
                //         <option value="1" '.($value->status==1?'selected':'').'>Done</option>
                //         <option value="2" '.($value->status==2?'selected':'').'>Deferred</option>
                //         </select>';
                // }
            
            }else{
			$sub[] ='None';	
			}
			}
            $part2 = "";
            if(user_access('1002') && $value->original=='1')
            {
				if(!empty($_POST['curr_stg'])){
				$current_stg = base64_encode($_POST['curr_stg']);
				}else{
					$current_stg ='';
				}
				if($value->status!='1' && $value->approval!='approve'){
                $part2.= "
                <a  class='btn btn-xs  btn-primary' href='".base_url('client/edit_commercial_info/').$value->id.'/'.$current_stg."' ><i class='fa fa-edit'></i></a>";
				}
            }
            
            if(user_access('1001'))
            {
            $part2.="<a class='btn btn-xs btn-danger' onclick='return confirm(\"Are you sure ?\")' href='".base_url('enquiry/deleteInfo/' . $value->id . '/'.$value->enquiry_id.'/')."'><i class='fa fa-trash'></i></a>";
            }

            $part2.="<a class='btn btn-primary btn-xs' onclick='quotation_pdf(".$value->id.")' style='cursor: pointer;'><i class='fa fa-download'></i></a>

           
            ";
            if($colsall || in_array(20,$cols))
            $sub[] ='<div class="btn-group">'.$part2.'</div>';
            $data[] =$sub;
        }
    
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" =>$this->Deals_datatable_model->countAll(),
            "recordsFiltered" => $this->Deals_datatable_model->countFiltered($_POST),
            "data" => $data,
        );
        echo json_encode($output);
    }


   
    public function competitor_list(){
        $this->db->where('comp_id',65);
        $this->db->where('status',0);
        $result = $this->db->get('competitors')->result_array();
        if(!empty($result)){ ?>
            <option value="">--- Select --- </option>
            <?php
            foreach ($result as $key => $value) {
                ?>
                <option value="<?=$value['name']?>"><?=$value['name']?></option>
                <?php
            }
        }
    }

    public function enq_page($enq_id,$datatype='0')
    {
		if(empty($datatype)){
      $res=  $this->db->select('status')->where('enquiry_id',$enq_id)->get('enquiry')->row_array();
        if($res['status']=='1')
            redirect(base_url('enquiry/view/'.$enq_id));
        else if($res['status']=='2')
             redirect(base_url('lead/lead_details/'.$enq_id));
         else 
             redirect(base_url('client/view/'.$enq_id));
    }else{
		if($datatype=='1')
            redirect(base_url('enquiry/view/'.$enq_id.'/'.$datatype));
        else if($datatype=='2')
             redirect(base_url('lead/lead_details/'.$enq_id.'/'.$datatype));
         else 
             redirect(base_url('client/view/'.$enq_id.'/'.$datatype));		
	}
	}

    public function enq_code_by_id($enq_code=0)
    {  
        if(!empty($enq_code))
        {
            $res =  $this->db->where('enquiry_id',$enq_code)->get('enquiry')->row();
            if(!empty($res))
            {
                echo $res->Enquery_id;
            }
        }

    }

    public function suggest_company()
    {
        $key = $this->input->post('search');
		$key = ltrim($key);
        $this->load->model('Client_Model');
        $company_id = $this->session->companey_id;
        $user_id = -1; //$this->session->user_id;
        $process = $this->session->process;
        $where = 'comp.company_name LIKE "%'.$key.'%"';
        $res = $this->Client_Model->getCompanyList(0,$where,$company_id,$user_id,$process,'data',10,0)->result_array();
       // echo $this->db->last_query();exit();
        $abc = array_column($res,'company_name');
        echo json_encode($abc);
    }
    public function mark_tag(){        
        $this->form_validation->set_rules('enquiry_id[]','Data','required');
        $this->form_validation->set_rules('tags[]','Tags','required');
        
        if($this->form_validation->run() == true){
            $enq = $this->input->post('enquiry_id[]');
            $tags = implode(',',$this->input->post('tags[]'));

            foreach ($enq as $key => $value) {
                if($this->db->where('enq_id',$value)->count_all_results('enquiry_tags')){
                    $this->db->where('comp_id',$this->session->companey_id);
                    $this->db->where('enq_id',$value);
                    $this->db->set('tag_ids',$tags);
                    $this->db->update('enquiry_tags');
                }else{
                    $this->db->insert('enquiry_tags',array('comp_id'=>$this->session->companey_id,'enq_id'=>$value,'tag_ids'=>$tags));
                }
            }
            echo json_encode(array('status'=>true,'msg' =>'Tag marked successfully'));
        }else{
            echo json_encode(array('status'=>false,'msg' =>validation_errors()));
        }

    }

    public function lead_summary_pie($enquiry_code,$enquiry_id){
        $data = array();
        $this->db->where('comp_id',$this->session->companey_id);
        $this->db->where('enquiry_id',$enquiry_id);        
        $visit_count = $this->db->count_all_results('tbl_visit');


        
        $this->db->where('query_id',$enquiry_code);        
        $task_count = $this->db->count_all_results('query_response');

        $this->db->where('comp_id',$this->session->companey_id);
        $this->db->where('enquiry_id',$enquiry_id);        
        $deals_count = $this->db->count_all_results('commercial_info');

        $data['feed']  = json_encode(array(array('Visit',$visit_count),array('Deals',$deals_count),array('Task',$task_count)));

        $this->load->view('enquiry/lead_summary_pie',$data);
    }
	
	function get_exist_alert()
    {    
        $type = $this->input->post('type');
        $parameter = $this->input->post('parameter');
		$company = $this->session->companey_id;
	
        $this->db->select('enquiry_id,name_prefix,name,lastname,status,phone,email');
		if($parameter=='mobile'){
		$this->db->where('phone',$type);
		}
		if($parameter=='email'){
		$this->db->where('email',$type);
		}
		$this->db->where('comp_id',$company);
        $res=$this->db->get('enquiry');
        $enq_id=$res->row();
		
		$this->db->select('client_id,c_name,contact_number,emailid');
		if($parameter=='mobile'){
		$this->db->where('contact_number',$type);
		}
		if($parameter=='email'){
		$this->db->where('emailid',$type);
		}
		$this->db->where('comp_id',$company);
        $res=$this->db->get('tbl_client_contacts');
        $contact_id=$res->row();
		
        if(!empty($enq_id->enquiry_id)){
			if($enq_id->status==1){
				$url = 'enquiry/view/'.$enq_id->enquiry_id.'/'.base64_encode($enq_id->status);
			}else if($enq_id->status==2){
				$url = 'lead/lead_details/'.$enq_id->enquiry_id.'/'.base64_encode($enq_id->status);
			}else{
				$url = 'client/view/'.$enq_id->enquiry_id.'/'.base64_encode($enq_id->status);
			}
            $html = '<table class="table table-bordered table-hover">
			            <tr>
                            <th>Name</th>
							<th>Email</th>
							<th>Phone</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>'.$enq_id->name_prefix.' '.$enq_id->name.' '.$enq_id->lastname.'</td>
							<td>'.$enq_id->email.'</td>
							<td>'.$enq_id->phone.'</td>
                            <td><a href="'.base_url($url).'">View Lead</a></td>
                        </tr>
                    </table>';
			echo $html;
        }else if(!empty($contact_id->client_id)){
			$url = 'client/contacts';
			$html = '<table class="table table-bordered table-hover">
			            <tr>
                            <th>Name</th>
							<th>Email</th>
							<th>Phone</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>'.$contact_id->c_name.'</td>
							<td>'.$contact_id->emailid.'</td>
							<td>'.$contact_id->contact_number.'</td>
                            <td><a href="'.base_url($url).'" target="_blank">View Contact</a></td>
                        </tr>
                    </table>';
			echo $html;
		}else{
			echo 0;
		}			
    }

    public function import_company_data(){
        $arr = array(" AQUATIC FORMULATIONS I LTD "," GARON DEHYDRATES PRIVATE LIMI"," INTOUCH LEATHER HOUSE"," JIVANLAL CORPORATION"," LANCO SOLAR ENERGY PVT. LTD."," MODERN SALES CORPORATION"," MOSIL LUBRICANTS PVT. LTD."," NIRLEP APPLIANCES PVT LTD"," P T INVENT INDIA PVT LTD"," S CRANE ENGINEERING WORKS"," TEXMACO RAIL AND ENGG LTD"," VOBO","20 CUBE WAREHOUSING AND DISTRIBUTION PVT LTD","20 MICRONS LIMITED","20 MICRONS NANO MINERALS LIMITED","20CUBE WAREHOUSING AND DISTRIBUTION PRIVATE LIMITE","24X7 LOGISTICS PRIVATE LIMITED","2A COLOR EXPLORER","2L ENGINEERS","3A MARKETING","3B FILMS PRIVATE LIMITED","3F INDUSTRIES LIMITED","3I LABELS INDIA PRIVATE LIMITED","3M ELECTRO AND COMMUNICATION PRIVATE LIMITED","3M INDIA LIMITED","3R GARMENTS","4M TECHNOSOL PVT LTD","5S MEDIPACK PRIVATE LIMITED","A","A A AROMAS","A B BROTHERS","A B M WOOD DECOR PRIVATE LIMITED","A C TRADING COMPANY","A CHOKSEY CHEMICAL INDUSTRIES","A CLASS FASTNERS","A G AROMATICS PRIVATE LIMITED","A HARTRODT (INDIA) PRIVATE LIMITED","A K AUTO INDUSTRIES","A K ENTERPRISES","A K JAIN SALES & MARKETING PRIVATE LIMITED","A P L APOLLO TUBES LIMITED","A R INDUSTRIES","A SAJ AGRICARE PRIVATE LIMITED","A SCHULMAN PLASTICS INDIA PRIV","A T E ENTERPRISES PRIVATE LIMITED","A. V. THOMAS & CO. LTD","A.A. ENTERPRISES","A.D CRAFT.COM","A.G INDUSTRIES","A.K KHAN & COMPANY","A.K.TRADERS","A.R.K.CHEMICALS","A.V. FORGINGS","A.V.ENTERPRISES","A.V.STEEL FORGINGS PVT.LTD.","A.W. FABER- CASTELL (INDIA) PV","A-1 FENCE PRODUCTS COMPANY PRIVATE LIMITED","AA INDUSTRIES","AAAAA","AAACORP EXIM INDIA PRIVATE LIMITED","AAAKA PLASTICS","AABHA CONTRACEPTIVES PVT LTD","AADARSH PVT.LTD.","AADI KNIT FAB","AADI PLASTIC INDUSTRIES PRIVATE LIMITED","AADINATH POLYFAB PVT LTD","AAF INDIA PVT LTD","AAGAM MEDICARE","AAK KAMANI PVT LTD","AAKAR PAINTS","AAKASH INDUSTRIES","AAKASH PACKAGING","AAKASH POLYFILMS LTD","AAKASH YOG HEALTH PRODUCTS PVT LTD","AAKSH BEVERAGES PRIVATE LIMITED","AARADHAN METALS","AARATRIKA APPARELS PRIVATE LIMITED","AARAV FRAGRANCES  & FLAVORS ","AARCH PROTECTION CHEMICALS PRIVATE LIMITED","AARIKA CHROMA INDUSTRIES PRIVATE LIMITED","AARKAY FOODS PRODUCTS LTD","AARNEEL TECHNOCRAFTS PRIVATE LIMITED","AAROHI STERILANT","AARON HELMETS PVT LTD","AARTI DRUGS LTD","AARTI INDUSTRIES LTD","AARVEE ENTERPRISES ","AARYA PACKAGINGS","AARYAN ASSOCIATES","AARYAN ENGINEERING PVT LTD","AASHIYANA FOODSTUFFS","AASTRID LIFE SCIENCES P LTD","AATCO FOODS INDIA PRIVATE LIMITED","AAVEES LAMINATION","AAVID THERMALLOY INDIA PRIVATE","AAYU INTERNATIONAL","AAYUSHI PHARMACEUTICALS","AB MAURI INDIA PRIVATE LIMITED","ABAD CHEMICALS","ABB INDIA LTD","ABBOTT HEALTHCARE PRIVATE LIMI","ABC BEARINGS LTD","ABC CHEMICALS EXPORTS P.LTD","ABC TECHNOLOGIES","ABELIN POLYMERS","ABHAYA EXPORTS PVT LTD ","ABHIDEEP CHEMICALS PVT. LTD.","ABHIJEET FERROTECH LIMITED","ABHIMAANI PUBLICATIONS LIMITED","ABHINAV ELECTRICAL INDUSTRIES","ABHISHRI PACKAGING PVT. LTD.","ABHYUDAY INDUSTRIES","ABI AGENCIES","ABI SHOWTECH (INDIA) LIMITED","ABIRAMI SOAP WORKS LLP","ABN IMPEX PVT LTD","ABODE BIOTEC INDIA PRIVATE LIMITED","ABR INDUSTRIES ","ABRO TECHNOLOGIES PVT. LTD","ABSORTECH INDIA PVT LTD","ABT CORPORATION","ACACIA INTERNATIONAL","ACC LIMITED","ACCELERTED FREEZE DRYING CO LTD","ACCENT INDUSTRIES LIMITED","ACCESS SOLAR LTD","ACCESS WAREHOUSING P LTD","ACCORD ELECTROPOWER PVT. LTD.","ACCRA PAC INDIA PVT LTD","ACCUPACK ENGINEERING P LTD","ACCURA INDUSTRIAL X-RAY & IMAGING","ACCURA SHELVING SYSTEMS PRIVATE LIMITED","ACCURATE INDUSTRIAL PRODUCTS","ACCURUB TECHNOLOGIES","ACE BRASS INDUSTRIES","ACE BRIGHT INDIA PHARMA PVT LT","ACE ENTERPRISES","ACE HYGIENE PRODUCTS PVT LTD","ACE INDUSTRIES","ACE KREAMERS","ACE MANUFACTURING AND MARKETING","ACI INDUSTRIAL ORGANIC PVT LTD","ACID INDIA LIMITED","ACME FLUID SYSTEMS","ACME HOLDING","ACME PROCESS SYSTEMS PRIVATE LIMITED","ACME THERAPEUTICS INDIA PRIVATE LIMITED","ACMECHEM LIMITED.","ACO SYSTEMS AND SOLUTIONS PRIVATE LIMITED","ACRON PLAST PVT LTD","ACRYCOL MINERALS LTD","ACRYSIL LTD","ACRYSIL STEEL LTD.","ACTION CONSTRUCTION EQUIPMENT ","ACTIVE CARBON INDIA PVT LTD","ACTIVE SPECIALITIES","ACTON LABS PRIVATE LIMITED","ADA HABER INDUSTRIAL CORPORATION","ADAMA INDIA PRIVATE LIMITED","ADANI FOOD PRODUCTS PVT LTD","ADANI INFRASTRUCTURE MANAGEMENT SERVICES LIMITED","ADANI PHARMACHEM PRIVATE LIMIT","ADANI PORTS AND SPECIAL ECONOM","ADANI POWER MAHARASHTRA LTD","ADANI WILMER LTD ( OLEO CHEM U","ADARSH FIBRES PRIVATE LIMITED","ADARSHA SPECIALITY CHEMICALS","ADD CHEM INDUSTRIES","ADDING MACHINES (I) PVT. LTD.","ADDPOL CHEMSPECIALITIES PVT LT","ADD-SHOP PROMOTIONS LIMITED","ADD-SHOP-E-RETAIL LIMITED","ADDTECH PACKAGING PVT LTD","ADDZOL OIL (INDIA) PRIVATE LIMITED","ADEPT INTERIORS PVT LTD","ADESHWAR MARKETING","ADF FOODS (INDIA) LIMITED","ADHISH INDUSTRIES","ADI FINECHEM LIMITED","ADICO INTERNATIONAL","ADILEC SYSTEMS","ADINATH GUM INDUSTRIES","ADINATH INDUSTRIES","ADISAN LABORATORIES PRIVATE LIMITED","ADISUN BONDING TECHNOLGIES LLP","ADITHYA AUTOMOTIVE APPLICATIONS PRIVATE LIMITED","ADITHYA ENGINEERING WORKS","ADITHYYA ENTERPRISES","ADITI FOODS INDIA PVT LTD.","ADITYA ","ADITYA BIRLA NUVO LTD","ADITYA CHEMICALS","ADITYA FLEXIPACK PRIVATE LIMITED","ADITYA INDUSTRIES","ADITYA POWER CONTROLS","ADITYA PRECITECH PVT LTD","ADITYA TECH MECH","ADITYA TECHNO FAB ENGINEERING","ADJAVIS VENTURE LTD","ADM AGRO INDUSTRIES PVT.LTD","ADMACH SYSTEMS PRIVATE LIMITED","ADMARK POLYCOATS PRIVATE LIMIT","ADMECH EQUIPMENTS INDIA PRIVATE LIMITED","ADONIS LABORATORIES PRIVATE LI","ADOR FONTECH LIMITED","ADOR WELDING LIMITED","ADORN ENTERPRISES LTD.","ADORN WIRELINK PVT LTD","ADSORBTECH ENGINEERS PVT LTD","ADVANCE ADDMINE PRIVATE LTD","ADVANCE AGRISEARCH LTD","ADVANCE CABLE TECHNOLOGIES (P)","ADVANCE CHEMICALS PVT LTD","ADVANCE COOLING TOWERS PVT.LTD","ADVANCE ENGINEERING","ADVANCE GRP COOLING TOWERS PVT","ADVANCE INDUSTRIES","ADVANCE MOLECULES","ADVANCE MULTISERV PVT LTD","ADVANCE PAINTS PVT LTD","ADVANCE SYNTEX LIMITED","ADVANCE VALVES PRIVATE LIMITED","ADVANCED BIO-AGRO TECH","ADVANCED ENZYME TECHNOLOGIES L","ADVANCED NUTRIENTS INDIA PRIVA","ADVANCED RESINS PRIVATE LIMITE","ADVANCED SPORT TECHNOLOGIES LLP","ADVENT CHEMBIO PVT LTD ","ADWAIT ENGINEERS","ADYAR ANANDA BHAVAN SWEETS IND","AELEA COMMODITIES PRIVATE LIMITED","AEP COMPANY","AEPCOM FASTENERS PRIVATE LIMITED","AERO FIBRE PRIVATE LIMITED","AERO INDUSTRIES","AERO WALK INTERNATIONAL (I) PV","AEROLITE INDUSTRIES","AEROMECH EQUIPMENTS PRIVATE LIMITED","AERON COMPOSITE PVT LTD.","AETHER INDUSTRIES LIMITED","AFCONS INFRASTRUCTURE LTD.","AFF AROMATICS PRIVATE LIMITED","AFFCIL INDUSTRRIES","AFFORD DIGITAL INKS","AFFORDABLE ROBOTIC & AUTOMATION LIMITED","AFFY PHARMA PRIVATE LIMITED","AFTON CHEMICAL HYDERABAD PVT L","AGAMYA PACKAGING SOLUTIONS PRIVATE LIMITED","AGAPE WORLD PRIVATE LIMITED","AGAPPE DIAGNOSTICS LTD","AGARAM AGROVET","AGARWAL LIFESCIENCE PVT LTD","AGARWAL MULTI PRINTS","AGARWAL RUBBER LTD","AGARWAL SILICON CARBON & CHEMI","AGASTYA CORPORATION","AGASTYA NUTRIFOOD INDUSTRIES LLP","AGILITY LOGISTICS PRIVATE LIMITED","AGLOWMED LIMITED","AGM BIOTECH PVT LTD ","AGNI CONTROLS","AGNI FIBER BOARDS PVT. LTD","AGNI POWER & ELECTRONICS PVT.LTD.","AGP YARN","AGRA ENGINEERING CO","AGRASEN ENGINEERING INDUSTRIES PRIVATE LIMITED","AGRAWAL GRAPHITE INDUSTRIES","AGRAWAL SNACKS FOOD INDIA LLP","AGRIPLAST TECH INDIA PRIVATE LIMITED","AGRO INTERNATIONAL","AGRO LIFE SCIENCE CORPORATION","AGRO TECH FOODS LIMITED","AGROCEL INDUSTRIES PVT. LTD.","AGROHA COLOURTECH PVT LTD.","AGROLINE","AGRON REMEDIES PVT LTD","AHINSA POLY FILMS PRIVATE LIMITED","AHMEDABAD AGENCY","AIDED REC MANAGEMENT PRIVATE LIMITED","AIMCO PESTICIDES LTD","AIMS IMPEX PVT LTD","AIPL ZORRO PRIVATE LIMITED","AIR INDIA LTD","AIR WATER INDIA PRIVATE LIMITED","AIRCONE AIR COOLER","AIREN COPPER PVT LTD","AIREN METALS PVT LTD","AIROIL FLAREGAS PRIVATE LIMITE","AIRPORTS AUTHORITY OF INDIA","AIRTECH PRIVATE LIMITED","AIRYSOLE FOOTWEAR PVT LTD","AJANTA CHEMICAL INDUSTRIES","AJANTA MARKETING","AJAX FIORI ENGINEERING","AJAY BIO TECH INDIA LTD","AJAY ORGANICS PVT. LTD.","AJAY SYSCON PRIVATE LTD","AJEET SEEDS PRIVATE LIMITED","AJEX ENTERPRISE","AJMERA ORGANICS ","AJMERA PHARMASURE LIMITED","AK INDUSTRIES","AKAR AUTO INDUSTRIES LIMITED","AKAR TOOLS LIMITED","AKASH  DYES & INTERMEDIATES","AKASH DEEP ENTERPRISES","AKASH MULTI TRADE ASSOCIATES","AKASH ORGANICS","AKAY CONSUMER CARE LLP","AKAY INDUSTRIES PRIVATE LIMITE","AKEMI TECHONOGY INDIA PVT LTD","AKG EXTRUSIONS PRIVATE LIMITED","AKG INDIA PVT LIMITED","AKRITI PRINTERS","AKRO ADHESIVE TAPES INDUSTRY","AKRUTI PAPER PRODUCTS","AKRY ORGANICS PVT LTD","AKSHAR AGENCIES ELECTRADE PVT LTD","AKSHAR BRASS INDUSTRIES","AKSHAR CHEM (INDIA) LTD","AKSHAR PRECISSIN TUBES PVT LTD","AKSHAR TRADING COMPANY","AKSHAY PATRA","AKZO NOBEL INDIA LTD","AL CAN EXPORTS PVT LTD","AL NUAIM","ALA CHEMICALS PVT LTD","ALAINA HEALTCHARE PVT LTD","ALBATROSS FINE CHEM PVT. LTD.","ALCHEMIC GASES & CHEMICALS ","ALCHIMICA INDIA PRIVATE LIMITED","ALCLAD FABRICATION PVT. LTD.","ALCON ELECTRONICS PVT LTD","ALCOP INDUSTRIES","ALCRAFT","ALEX CHEMICAL & INDUSTRIES","ALEX ELECTRICALS","ALEX MACHINE TOOLS ","ALF ENGINEERING PVT LTD","ALFA CARPETING COMPANY ","ALFA CHEMO PLAST PVT LTD ","ALFA PIGMENT AND CHEMICALS PVT. LTD.","ALFA PUMPS PRIVATE LIMITED","ALFA RADIATORS","ALGO FLUID SYSTEMS PRIVATE LIMITED","ALGO HYDRO TECH","ALIF CLOTHING LLP","ALIVE WELLNESS","ALIVIRA ANIMAL HEALTH LTD","ALKA FORGINGS","ALKEM LABRATOREIS LTD","ALKINE INDIA PRIVATE LIMITED ","ALKON PLASTICS PRIVATE LIMITED","ALKRAFT THERMO TECHNOLOGIES P.LTD","ALL CARE FARMA FOOD PRODUCTS","ALL INDIA DRUGS","ALL SERVICE GLOBAL PVT LTD","ALL TIME PLASTICS PVT. LTD.","ALLANA SONS P LTD","ALLAND & SAYAJI LLP","ALLCHEM LIFESCIENCE PVT.LTD.","ALLEGRO SPECIALITY CHEMICALS PRIVATE LIMITED","ALLEN HOMOEO AND HERBAL PRODUCTS LIMITED","ALLEN LABORATORIES LTD","ALLENA AUTO INDUSTRIES PVT.LTD","ALLIANCE ENGINEERING","ALLIANCE FIBRES LTD.","ALLIED ENTERPRISE","ALLIED ICD SERVICES LTD","ALLIED NATUTAL PRODUCT","ALLIED POWER SOLUTIONS","ALLIED PRODUCTS","ALLIED REFRACTORY PRODUCTS IND","ALLIPO CHEMICALS","ALLIUM FOODS PRIVATE LIMITED","ALLMARC INDUSTRIES PVT LTD","ALLTECH BIOTECHNOLOGY PRIVATE LIMITED","ALLTECH TECHNO CAST PVT LTD","ALMECH ENTERPRISES","ALMIGHTY AUTO ANCILLARY P LTD","ALOK INDUSTRIES LIMITED","ALOK MASTER BATCHES PVT LTD","ALOK WATER PROOFING COM","ALOKE ALLOYS","ALOM POLY EXTRUSIONS LTD","ALOP MEDI SCIENCES (P) LTD.","ALP AEROFLEX INDIA PRIVATE LIMITED","ALP NISHIKAWA COMPANY LIMITED","ALPHA CHEMIE","ALPHA ENTERPRISE","ALPHA HELICAL PUMPS (P) LTD","ALPHA PACKAGING PVT. LTD.","ALPHA PAINTS PVT LTD","ALPHA TRADERS","ALPHA VECTOR INDIA PVT LTD","ALPHALOOP SOLAR LLP","ALPHAMED FORMULATIONS PRIVATE LIMITED","ALPHARUB TRADING AND MANUFACTU","ALPINO HEALTH FOODS PRIVATE LIMITED","ALPS CHEMICALS PVT.LTD","ALSTOM T & D INDIA LTD","ALSTONE INDUSTERIES PVT LTD","ALTEK METAL CASTERS","ALTIUS SPORTS & LEISURE PVT LT","ALTRET INDUSTRIES PRIVATE LIMI","ALUDECOR LAMINATION PVT. LTD.","ALUFIT INDIA PVT.LTD","ALUMINA CHEMICALS & CASTABLES","ALUMINUM POWDER COMPANY LTD","ALUTECH FOUNDRY INDIA PRIVATE LIMITED","ALUTECH PACKAGING PVT LTD","ALVIN CARAMEL COLOURS INDIA PR","ALVIO PHARMACEUTICALS PRIVATE LIMITED","AM WORLD","AMA PRIVATE LIMITED","AMAL CARGO","AMALGAMATIONS REPCO LTD","AMAN AVIATION & AEROSPACE SOLUTIONS PVT LTD","AMAN UDYOG","AMANTA HEALTH CARE LIMITED","AMAR BIOLIFE","AMAR IMPEX ","AMAR PLASTICS","AMAR SPRING","AMAR TEA PRIVATE LIMITED","AMARA RAJA BATTERIES LIMITED","AMARJTOHI SPINNING MILLS LTD","AMARTARA PVT.LTD.","AMARYLLIS HEALTHCARE PVT LTD","AMAZING RESEARCH LABORATORIES LIMITED","AMAZON DISTRIBUTORES PRIVATE LIMITED","AMAZON PAPYRUS CHEMICALS PVT LTD","AMBANI ORGANICS PVT LTD","AMBAR SEEDS","AMBAR UTPADAN & RESEARCH CENTR","AMBE","AMBE PHYTOEXTRACTS PVT LTD","AMBER AROMATICS","AMBER CHEMICALS","AMBERNATH POLYTEX P LTD","AMBERTEX SEKHSARIA EXPORTS","AMBICA CORPORATION LTD","AMBICA DHATU PVT LTD","AMBICA ENTERPRISE -RAK","AMBICA STEEL PRODUCTS","AMBICA WALL PAPER","AMBUJA CEMENTS LIMITED","AMBUJA INTERMEDIATES LIMITED","AMBUJA SOLVEX PVT LTD","AMCO BATTERIES LIMITED","AMCOR FLEXIBLES INDIA PRIVATE ","AMEE PRODUCTS","AMEENJI RUBBER PVT LTD","AMEERA GROUP","AMEET METAPLAST PRIVATE LIMITED","AMEET POLYFILMS PVT LTD","AMENSCO MEDICAL TECHNOLOGIES PRIVATE LIMITED","AMERCO PACKAGING SOLUTIONS PRIVATE LIMITED","AMERICAN PRECOAT SPECIALITY PRIVATE LIMITED","AMERICAN SHARING","AMERICHEM POLYMERS INDIA PRIVATE LIMITED","AMER-SIL KETEX PVT. LTD.","AMEYA ENGINEERING WORKS","AMFICO AGENCIES PVT LTD","AMI CHEMCIALS","AMIJAL CHEMICAL","AMISHI DRUGS & CHEMICALS PVT L","AMIT ADDITIVES","AMIT CAPACITORS LIMITED","AMIT CELLULOSE PRODUCTS ","AMIT HYDROCOLLOIDS","AMIT PETROLUBES PVT LTD","AMITA CHEMICAL INDUSTRIES","AMITASHA AGENCY","AMITY THERMOSETS PVT. LTD.","AMIYA COMMERCE AND CONSTRUCTIO","AMJEY CHEM TRADERS PVT LTD","AMMANN APOLLO INDIA PRIVATE LI","AMMRI INTERIORS","AMOLI ORGANICS PVT LTD","AMP SPECIALITY PRODUCTS PRIVATE LIMITED","AMPACET SPECIALITY PRODUCTS PV","AMPHI PLASTO","AMPLE PAPER CONTAINERS PVT.LTD.","AMPO VALVES INDIA PRIVATE LIMITED","AMPS ENGINEERING & EQUIPMENTS PRIVATE LIMITED","AMRITRAS INDIA","AMRUT INTERNATIONAL","AMRUTANJAN HEALTH CARE","AMSAR PVT LTD","AMTECH ELECTRONICS INDIA LIMITED","AMTECH INVESTMENT CASTING P.LT","AMTEK AUTO LTD","AMUL FEED PVT. LTD.","AMYLODEX PRIVATE LIMITED","AMZONE INTERNATIONAL PVT LTD","ANABOND","ANABOND HS BUTYL PVT LTD","ANABOND LIMITED-ORG CHENNAI-ELICHUR","ANADCO SPORTING CORPORATION","ANAN DRUGS & CHEMICALS LTD.","ANAND ARC LTD","ANAND CHEMICALS","ANAND ENGINEERS PVT LTD","ANAND ENTERPRISES","ANAND INTERNATIONAL","ANAND LINERS (INDIA) PVT.LTD.","ANAND MOULD STEELS PRIVATE LIM","ANAND SYNTHETIC","ANANT AUTOMOTIVE COMPONENTS","ANANT PHARMA DISTRIBUTORS","ANANTA RUBBER LLP","ANAR CHEMICALS LLP","ANCHEMCO ANAND LLP","ANCHOR ELECTRICAL","ANCHOR HEALTH & BEAUTY CARE PV","ANCHOR PHARMA PVT LTD","ANCORA FOODS LLP","ANDERSON GREENWOOD CROSBY SANM","ANDHRA & TELANGANA REGION POD MOVEMENT A/C","ANDHRA ENTERPRISES","ANDRITZ SEPARATION AND PUMP TECHNOLOGIES INDIA PVT","ANEST IWATA MOTHERSON PVT LTD","ANGEL CREATIONS","ANGEL MAGNETICS INDIA PVT LTD","ANGEL TURNOMATIC","ANGELS ALUMINIUM & CORPORATION","ANGELS PHARMA INDIA PRIVATE LIMITED","ANIL MANTRA LOGISTIX PVT LTD","ANILKUMAR SURESHKUMAR & CO","ANIPRA CHEMICALS PVT LTD","ANISH CHEMICALS","ANISHA AUTO COMPONENT","ANJALI ENGINEERING WORKS","ANJANEYA CHEMICALS","ANJANI UDYOG PVT. LTD","ANJU DYE CHEM","ANKIT PULPS & BOARDS PRIVATE LIMITED","ANKITA AGRO & FOOD PROCESSING PRIVATE LIMITED","ANKUR CHEMFOOD LIMITED","ANKUR DRUGS","ANKUR IMPEX","ANMOL CHEMICALS","ANMOL CHLORO CHEM (GUJARAT)","ANMOL INNOVATIVE ELECTRICAL","ANMOL POLYMERS PVT LTD","ANMOL SAFETY PRODUCTS PVT LTD","ANONDITA HEALTHCARE","ANPAM ENGINEERING","ANS STEEL TUBES LTD","ANSONS ELECTRO MACHANICAL WORK","ANSUYA SURGICALS LIMITED","ANSUYAA MEDICARE","ANSYSCO ANAND LLP","ANTARES FORGE PRIVATE LIMITED","ANTEX PHARMA PRIVATE LIMITED","ANTHEA AROMATICS PVT LTD","ANTHEM BIOSCIENCES PVT LTD","ANTHEM CELLUTIONS INDIA LTD","ANTICORROSIVE EQUIPMENT PVT LT","ANUBHA INDUSTRIES PVT LTD","ANUBHAV UDYOG","ANUP INDUSTRIES PRIVATE LIMITED","ANUPAM COLOURS & CHEMICALS IND","ANUPTOOLS INDUSTRIE","ANYA COMPOSITES PRIVATE LIMITED","AONE AGRO PRODUCTS PRIVATE LIMITED","A-ONE PAINTING TOOLS","AOS PRODUCTS PRIVATE LIMITED","APAR INDUSTRIES LIMITED","APARNA PAPER PROCESSING INDUST","APC NUTRIENTS PRIVATE LIMITED","APCOTEX INDUSTRIES LIMITED","APCOTEX SOLUTIONS INDIA PVT. L","APEEJAY TEA LTD","APEX BUILDSYS LIMITED","APEX LABORATORIES PRIVATE LIMITED","APEX PACKING PRODUCTS PRIVATE LIMITED","APEX TECHNO PLAST","APEX TECHNO POLYMER PRIVATE LIMITED","APEX TECHNOCAST","APIDOR ABRASIVE PRODUCTS PRIVATE LIMITED","APL LOGISTICS (INDIA) PRIVATE LIMITED","APLS AUTOMOTIVE INDUSTRIES PRI","APOGEE HEALTHCARE PVT LTD","APOLLO FIEGE INTEGRATED LOGIST","APOLLO INDUSTRIES","APOLLO METALEX PVT. LTD.","APOLLO PAINTS PVT LTD","APOLLO POLYVINYL PVT. LTD.","APOLLO SOYUZ ELECTRICALS ","APOLLO SUPPLY CHAIN PRIVATE LIMITED","APOLLO TECHNOFORGE PVT.LTD.","APOLLO TRICOAT TUBES LIMITED","APOLLO TYRES LTD","APPAREL AND LEATHER TECHNICS","APPASAMY OCULAR DEVICES (P) LTD","APPEX DYESTUFF INDUSTRIES","APPL GOR PLASTICS INDIA PRIVATE LIMITED","APPL INDUSTRIES PVT LTD  ","APPLIED COMMUNICATIONS & CONTR","APPOLO INDUSTRIES","APT POWER ENGINEERING LIMITED","APT TOOLS & MACHINERY INDIA PRIVATE LIMITED","APTCO (INDIA) PRIVATE LIMITED","APTE AND APTE ORGANIC COATING PVT LTD","APU PACKAGING INDIA PVT LTD.","APURVA ENTERPRISE","APURVA INDIA LTD","APURVI INDUSTRIES","AQF INGREDIENTS LLP","AQSA POLYPACK PRIVATE LIMITED","AQSEPTENCE GROUP (INDIA) PRIVATE LIMITED","AQUA ALLOYS PRIVATE LIMITED","AQUA EXCEL","AQUAGRI GREENTECH PRIVATE LIMITED","AQUAGRI PROCESSING PRIVATE LIM","AQUAPHARM CHEMICALS PVT LTD","AQUASUB ENGINEERING","AQUENT ADVANCE MATERIAL TECHNOLOGIES PVT LTD","ARABIAN PETROLEUM LIMITED","ARADHYA PUMPS","ARADHYA STEEL PVT LTD","ARAMATIC ENGINEERING PRIVATE L","ARAV ENGINEERS","ARAVALLY PROCESSED AGROTECH PV","A-RAVI RAG INDUSTRIES ","ARAVIND LABORATORIES","ARB BEARINGS LTD","ARBITAL SOLUTIONS PRIVATE LIMITED","ARBOREAL BIOINNOVATIONS PRIVATE LIMITED","ARBUDA AGROCHEMICALS PRIVATE LIMITED","ARBUDA PALSTO CHEM PVT LTD","ARC INSULATIONS & INSULATORS P","ARC LAMINATES","ARCELORMITTAL NIPPON STEEL INDIA LIMITED","ARCH PROTECTION CHEMICALS PRIVATE LIMITED","ARCHANAA BIO BAGZ","ARCHIDPLY INDUSTRIES LTD.","ARCHIT ORGANOSYS LIMITED","ARCHROMA INDIA PVT LTD","ARCOY INDIA PRIVATE LIMITED","ARCOY INDUSTRIES","ARCTIC SOLUTION ","ARD POLY PACKS (P) LTD.","AREE ENGINEERING TOOLS","ARENE LIFE SCIENCES LIMITED","AREVA LOGISTICS PVT LTD","AREVIN FINE CHEMICAL PVT LTD","ARHAM MEDISALES LLP","ARHAM PUMPS","ARHAM STEEL","ARIES COLORCHEM PVT. LTD.","ARIES DYE CHEM INDUSTRIES","ARIES ELECTRONIC INDUSTRIES PRIVATE LIMITED","ARIES ORGANICS PVT.LTD","ARIHANT CHEMICALS","ARIHANT ENGINEERS","ARIHANT FLEXIPACK","ARIHANT HI TECH INDUSTRIES","ARIHANT INNOCHEM PRIVATE LIMITED","ARIHANT MATTRESS PRIVATE LIMITED","ARIHANT PACKERS","ARIHANT POLYPLAST PVT LTD.","ARIHANT PRECISION SCREWS","ARIHANT REMEDIES","ARIHANT TECHNO PACK PVT. LTD.","ARISTO PHARMACEUTICAL","ARJUN CHEMICALS PVT LTD","ARK 3PL INDIA LLP","ARK CHEMICALS PVT LTD","ARK DIAGNOSTIC SYSTEM PVT LTD","ARK GOLDEN INDIA PVT. LTD.","ARK LOGISTICS","ARKEL ELECTRONIC INDIA PRIVATE LIMITED","ARKEMA CHEMICALS INDIA PRIVATE LIMITED","ARKRAY HEALTHCARE PVT.LTD","ARMANIA AGRO FOODS","AROMA DE FRANCE","AROMA ENGINEERING INDIA PVT LTD","AROMA REMEDIES","AROMA WORLD","AROMAX TRADING PVT LTD","AROMENTIS LABORATORIES PRIVATE LIMITED","ARON UNIVERSAL LTD","ARORA AROMATICS PRIVATE LIMITED","ARORA FRUITS AND PICKLES INDIA PVT. LTD.","AROZEN PHARMA","ARPAN ENTERPRISE","ARPAN POLY PLAST (P) LTD.","ARPITA AGRO PRODUCTS (P) LTD","ARROW ENGINEERS","ARROW GREENTECH LTD.","ARROW PUBLICATIONS PRIVATE LIMITED","ARROW TECHNOCAST (GUJ) PVT LTD","ARROW TEXTILES LIMITED","ARSWAN CORPORATION","ART BEADS PVT. LTD","ARTEK SURFIN CHEMICAL LIMITED","ARTH METALLURGICALS PVT LTD","ARTI CHEMICAL INDUSTRIES","ARTLIFE WELLNESS PRODUCTS PRIV","ARUDRA ENGINEERS PVT LTD","ARUN & CO","ARUN ENTERPRISES","ARUN MENS WEAR SERVICE","ARUN OIL TRADE","ARUN PLASTO MOULDERS (INDIA) P","ARUN POLY BAGS","ARUNA CHEMICAL INDUSTRIES","ARUNA TRADE COMBINES","ARUNACHAL MOTORS (P) PTD. (KAR","ARUNAYA ORGANICS PVT LTD","ARVIND ENVISOL LIMITED","ARVIND FOOTWEAR PRIVATE LIMITED","ARVIND LIMITED","ARVIND PD COMPOSITES PRIVATE LIMITED","ARVY INTERNATIONAL","ARYA POLYMERS","ARYA VAIDYA SALA","ARYAJAN VENTURES PVT LTD","ARYAN CARE PRIVATE LIMITED","ARYAN EXPORTERS PRIVATE LIMITED","ARYAN LUBRICANTS","ARYAN PACKAGING INDUSTRIES","ARYAN PRECISIONS PVT LTD","ARYSTA LIFESCIENCE INDIA LIMIT","ASAHI MODI MATERIALS PVT. LTD.","ASANJO HARDWARE","ASB INTERNATIONAL PVT LTD.","ASCENT FINECHEM PVT.LTD.","ASCENT PHARMA","ASCOT PHARMA CHEM PVT LTD","ASHA EXPORT","ASHA PENN COLOR PVT LTD","ASHAPURA AROMAS PRIVATE LIMITED","ASHAPURA INTERNATIONAL LIMITED","ASHAPURA LOGISTICS","ASHAPURA MINECHEM LTD","ASHAPURA PERFOCLAY LTD","ASHAPURA STEEL","ASHAPURA WIRE PRODUCT","ASHAR LOCKER (INDIA) PVT LTD","ASHAZON CORPORATION COMPANY","ASHIMA DYECOT PVT. LTD","ASHIMA LIMITED","ASHIRVAD PIPES P LTD","ASHIRWAD GROUP","ASHISH ENTERPRISES","ASHISH INDUSTRIES","ASHISH LIFE SCIENCE PVT LTD","ASHISH PROTEINS & FOOD (P) LTD","ASHLAND INDIA PRIVATE LIMITED","ASHOK CONSUMER GOODS LLP","ASHOK SALES COMPANY PVT LTD","ASHOKA ENTERPRISES","ASHOKA INTERNATIONAL","ASHTADHATU FERRO METALS PRIVATE LIMITED","ASHTAVINAYAK CORPORATION","ASHTAVINAYAK INDUSTRIES","ASHTVINAYAK SHOTS INDUSTRIES","ASHU ORGANICS (I) PVT LTD","ASHWANI METALS PVT. LTD.","ASHWATHY PLAST","ASHWIN AUTO-CAST PVT LTD","ASHWIN FASTENERS PVT. LTD","ASHWINI HOMEO","ASHWINI KORPORATION","ASIA BOOK HOUSE","ASIA CHEMICALS PRIVATE LIMITED","ASIA PULP & PAPERS PVT LTD ","ASIAN BRASS TECHNOCRATS","ASIAN FLEXI PACK INDIA PRIVATE LIMITED","ASIAN GRANITO INDIA LTD","ASIAN PAINTS INDUSTRIES","ASIAN PAINTS PPG PVT LIMITED","ASIAN PPG INDUSTRIES LTD.","ASIAN PRE-LAM INDUSTRIES ","ASIAN SEALING PRODUCTS PRIVATE LIMITED","ASIAN TECHNOCAST","ASIAN WORLDWIDE SERVICES (I) P","ASIANARC ELECTRODES PVT LTD ","ASIATECH COATINGS PRIVATE LIMITED","ASIATIC COLOUR CHEM","ASIATIC DRUGS & PHARMACUTICALS","ASK AUTOMOTIVE PVT. LTD.","ASK CHEMICALS INDIA PRIVATE LI","ASL INDUSTRIES LIMITED","ASMON WIRE INDUSTRIES","ASP SEALING PRODUCTS LTD","ASPEN WELLNESS","ASPRAN INDIA","ASQUARE FOOD & BEVERAGES PRIVATE LIMITED","ASSESS BUILDCHEM PRIVATE LIMITED","ASSOCIATED BATTERY PRODUCTS ","ASSOCIATED CABLES PRIVATE LIMITED","ASSOCIATED CONTAINERS AND BARR","ASSOCIATED ELECTROCHEMICALS PV","ASSOCIATED FINE CHEM PVT LTD","ASSOCIATED INDUSTRIAL FURNACES PVT LTD","ASSOCIATED LOGISTIC SERVICES","ASSOCIATED POWER TECH PRIVATE LIMITED","ASSOCIATED RUBBER CHEMICALS PVT LTD.","ASSOMAC MACHINES LTD","ASSURGEN PHARMA PRIVATE LIMITED","ASTEC LIFESCIENCES LIMITED.","ASTHA POLYMERS PRIVATE LIMITED","ASTIK DYESTUFF PVT LTD.","ASTRA CHEMTECH PVT LTD -BOISAR ","ASTRA COATINGS LIMITED","ASTRA CONCRETE PRODUCTS","ASTRA SPECIALTY COMPOUNDS INDIA PRIVATE LIMITED","ASTRAL LIMITED","ASTRAMEDIX LIFE SCIENCE PVT LTD","ASTRON PACKAGING LTD","ASTRON POLYMERS","ASTRON ZIRCON PVT.LTD","ASV MULTICHEMIE PRIVATE LIMITED","ASWANI INDUSTRIES PRIVATE LIMITED","ASWINI HOMEO & AYURVEDIC PRODU","ATA FIREIGHT LINE INDIA PVT LT","ATASH ABRASIVE","ATC TIRES PVT LTD","ATCO ATMOSPHERIC AND SPECIALITY GASES PVT LTD","ATCO CONTROL I PVT LTD","ATFC ADITYATECHNO FAB PRIVATE LIMITED","ATHOM TRENDZ PVT LTD","ATIT ENGINEERING INDUSTRIES","ATLANTIC BIOMEDICAL PVT LTD","ATLANTIC CARE CHEMICALS PRIVATE LIMITED","ATLANTIC FOOTCARE PRIVATE LIMITED","ATLAS ENGINEERING","ATNT LABORATORIES","ATOP AUTO LLP","ATOTECH INDIA PVT LTD","ATUL AUTO LTD.","ATUL ENTERPRISES","ATUL LTD.","AUDIA PLASTICS INDIA LLP","AUDITOR TEST","AUM ENTERPRISES-TLJ","AUMGENE BIOSCIENCES PVT. LTD.","AUMNI TRANSMISSION INDUSTRY PRIVATE LIMITED","AURA NUTRACEUTICALS LTD","AURA PERSONAL PRODUCTS PVT LTD","AURA POLY FLEX","AURAYA HEALTHCARE","AURO POWER SYSTEMS","AUROBINDO PHARMA LTD","AUROCHEM LABORATORIES","AUROCHEM PHARMACAUTICALS ","AURZ PHARMACEUTICAL PRIVATE LIMITED","AUSHADHI WELLNESS PRIVATE LIMITED","AUSRALIAN FOODS INDIA PVT.LTD.","AUSTIN FOODS AND BEVERAGES PRIVATE LTD","AUSTRO LABS LIMITED","AUTO CENTER","AUTO DIECASTING COMPANY","AUTO PLANT SYSTEM INDIA PVT. LTD","AUTO TECH POLYMERS INDIA P LTD","AUTOFIT PRIVATE LIMITED","AUTOMAT INDUSTRIES PVT LTD","AUTOMAT IRRIGATION PVT LTD","AUTOMATIC ELECTRIC LIMITED","AUTOMATION ZONE","AUTOMOTIVE TEXTILE SOLUTIONS L","AUTOMOTIVE VALVES PVT. LTD.","AUTOTECH NONWOVENS PVT. LTD.","AUTOVEA METAL PLAST","AUXICHEM ","AV ORGANICS LLP","AV PRAKRITII INTERNATIONAL PVT LTD","AVAANTE INTERNATIONAL CO.","AVAIDS TECHNOVATORS PVT. LTD.","AVANTOR PERFORMANCE MATERIALS ","AVDHOOT ENTERPRISES","AVDHOOT PIGMENTS PVT. LTD.","AVESTA PHARMA PVT LTD","AVG WIRE SCREENS PRIVATE LIMITED","AVI GLOBAL PLAST PVT. LTD.","AVI WORLDWIDE PRIVATE LIMITED","AVIK PHARMACEUTICALS LTD","AVI-OIL INDIA (P) LTD","AVITECH NUTRITION PVT LTD","AVN SAIGAL PLASTIC INDUSTRIES","AVODEN PRIVATE LIMITED","AVON FLAVOURS","AVON HYDRAULICS ENG.PRIVATE LIMITED","AVON PLASTIC INDUSTRIES PVT LT","AVR VALVES PVT. LTD","AVSL INDUSTRIES LIMITED","AVT MCCORMICK INGREDIENTS PRIVATE LIMITED","AVTAR ENTERPRISES","AVVASHYA CCI LOGISTICS PRIVATE LIMITED","AV-VISION EQUIPMENTS (INDIA) PRIVATE LIMITED","AVYUKTA FASHIONS","AWISHKAR ASSOCIATES","AXABULL INDUSTRIES PRIVATE LIMITED","AXABULL LUBRICANT OIL","AXACUS PHARMA PRIVATE LIMITED","AXALTA COATING SYSTEMS INDIA P","AXIS ELECTRICAL COMPONENTS IND","AXIS IMPEX","AXIS POLYFILMS PRIVATE LIMITED","AYEMS ENGINEERS PVT LTD","AYUGEN PHARMA PVT. LTD.","AYURVET LTD","AYURWIN PHARMA PVT.LTD.","AYUSH FLEXIPACK PVT LTD","AYUSIDDH HEALTHCARE PVT LTD","AZAFRAN INNOVACION LTD","AZZURA PHARMACONUTRITION PVT. ","B - TEX OINTMENT MFG CO","B & A PACKAGING INDIA LIMITED","B K AGRAWAL MERCHANTS PVT LTD","B K MARKETING","B P CHEMICALS","B S K FOOD AND AGRO PRODUCTS","B V BIO CORP PRIVATE LIMITED","B. P. CHEMICALS","B. S. CHEM INDUSTRIES","B.K.GANDHI","B.K.INDUSTRIES","B.R. ENGINEERING WORKS PRIVATE LIMITED","B.S.ENTERPRISES","BAADER SCHUIZ LABORATORIES","BABA INDUSTRIES","BACCAROSE PERFUMES & BEAUTY PV","BACFO PHARMACEUTICALS INDIA LTD","BADRIVISHAL CHEMICALS AND PHARMACEUTICALS","BADVE ENGINEERING LIMITED","BAERLOCHER INDIA ADDITIVES PVT","BAFNA INDUSTRIES","BAGLA POLIFILMS LTD","BAGRRYS INDIA LIMITED","BAHADUR SINGH WORKS PRIVATE LIMITED","BAIJNATH LANDSCAPS","BAINITE MACHINES PVT LTD","BAJAJ APPLIANCES LTD","BAJAJ ELECTRICALS LTD","BAJAJ HEALTHCARE LIMITED","BAJAJ POLYBLENDS PRIVATE LIMITED","BAJRANG BALI INDUSTRIES","BAKSON DRUGS & PHARMACEUTICALS PVT LTD","BAKUL AROMATICS AND CHEMICALS","BAKUL CASTING PVT. LTD.","BALA INDUSTRIES","BALAJEE INTERNATIONAL","BALAJI ACTION BUILDWELL","BALAJI ADVANCED COMPOSITES","BALAJI AGARABATTI COMPANY","BALAJI AIRVENT SYSTEMS PRIVATE LIMITED","BALAJI AMINES LIMITED","BALAJI ASSOCIATES","BALAJI DISTRIBUTORS","BALAJI ENTERPRISE","BALAJI FORMALIN PVT LTD","BALAJI INDUSTRIES","BALAJI POLYMERS","BALAJI SPECIALITY CHEMICALS PVT LTD ","BALAJI SUPER SPANDEX","BALAJI WIRE HARNESS COMPANY","BALARK METALS PRIVATE LIMITED","BALIGA LIGHTING EQUIPMENTS PVT LTD","BALKRISHNA INDUSTRIES LIMITED","BALMER LAWRIE VAN LEER LIMITED","BALSARA ENGINEERING PRODUCTS L","BAMBINO AGRO INDUSTRIES LIMITED","BAN LABS (P) LTD.","BANACO OVERSEAS PRIVATE LIMITED","BANARASWALA METAL CRAFTS PRIVATE LIMITED","BANCO ALUMINIUM LTD.","BANCO GASKETS INDIA LIMITED","BANCO PRODUICTS INDIA LTD","BANDO(INDIA) PVT. LTD.","BANI AUTO INDUSTRIES","BANKIM PUNCH SYSTEMS P LTD","BANKIM TEXTILES & CHEMICALS","BANSAL BROTHERS","BANSAL TRADING COMPANY","BANSWARA GARMENTS","BAPS SWAMINARAYAN HERBAL CARE","BAPUJI SURGICALS","BARODA AGRO CHEMICALS LTD","BARODA BUSHINGS AND INSULATORS LLP","BARODA HI-TECH ALLOYS (P) LTD.","BARODA PHARMACEUTICAL INDUSTRI","BARODA POLYFORM PVT LTD","BARODA SURGICAL (INDIA) PVT. L","BASANT PRODUCT INDIA","BASANT RUBBER FACTORY LTD","BASANTAR BREWERIES PVT. LTD.","BASELL POLYOLEFINS INDIA PVT L","BASF","BASF COLORS & EFFECTS INDIA PRIVATE LIMITED","BASF INDIA LTD","BASIC PHARMA LIFESCINCE PVT LT","BASIL PROMPT VINYL PRIVATE LIMITED","BATA INDIA LTD","BATHLA ALUMINIUM PVT LTD","BATLIBOI LIMITED","BAUER EQUIPMENT INDIA PVT LTD.","BAUMER TECHNOLOGIES INDIA PRIV","BAWA POLYMERS","BAXOM HEALTHCARE PRIVATE LIMITED","BAYCHEM FABRICS PVT LTD","BAYER CROPSCIENCE LTD.","BAYER MATERIALSCIENCE PVT LTD","BBSI SYSTEMS PRIVATE LIMITED","BDH INDUSTRIES LTD","BDI INDIA PRIVATE LIMITED","BDN FASTENERS INDIA PRIVATE LIMITED","BEC CHEMICALS PRIVATE LIMITED","BEC FERTILIZERS LTD","BECTA LABORATORIES","BECTOCHEM LEODIGE PROCESS TECH","BEDMUTHA INDUSTRIES LTD.","BEE CHEMS","BEETA TRADERS AND AGENCIES","BEICO INDUSTRIES PVT LTD ","BELL AND VIBHAVA","BELLA PACIFIC PAPER PRIVATE LIMITED","BELLS INSULATIONS PVT LTD","BELLWAY CONSULTING","BENATTON PHARMACEUTICAL PRIVATE LIMITED","BENCO THERMAL TECHNOLOGIES PVT LTD","BENDETTO KITCHENS PVT LTD","BENGAL CHEMICAL&PHARMACEUTICAL","BENTOLI AGRINUTRITION INDIA PRIVATE LIMITED","BENZO PRODUCTS INDIA PVT LTD","BERGER BECKER COATING PRIVATE LIMITED","BERGER PAINTS INDIA LTD","BERGER ROCK PAINTS PRIVATE LIMITED","BERICAP INDIA PRIVATE LIMITED","BERONIKA INTERNATIONAL PRIVATE LIMITED","BERRY ALLOYS LIMITED","BERRYS HEALTHCARE PRIVATE LIMITED","BEST ELASTOMERS PVT LTD","BEST ENGINEERS PUMPS PVT. LTD.","BEST UNITED INDIA CONFORTS PVT. LTD","BEST VALUE CHEM PVT LTD","BESTSELLER FASHION INDIA PVT. LTD","BESTSELLER RETAIL INDIA PVT. LTD","BESTSELLER WHOLESALE INDIA PVT. LTD","BEURER INDIA PRIVATE LIMITED","BEVA SILICONES PRIVATE LIMITED","BGM POLICY INNOVATIONS PVT.LTD","BHAGWAT WIRE INDUSTRIES","BHAGWATI POLYWEAVE PVT LTD","BHAGWATI SPHEROCAST PVT.LTD.","BHAGYA INDUSTRIES","BHAGYADEEP CABLES PVT LTD","BHAIRAVI CARGO AND LOGISTICS PRIVATE LIMITED","BHAIRAVNATH EXIM PVT LTD","BHAIYA ENTERPRISES LLP","BHAKTI CORPORATION","BHANDARI FOILS & TUBES LTD.","BHANSALI ENGINEERING POLYMERS ","BHANU HEALTHCARE PVT LTD.","BHANUSHALI AGROTECH","BHARAJ MACHINERIES PVT. LTD","BHARAT AGENCIES","BHARAT BARREL AND DRUM MANUFAC","BHARAT BIJLEE LIMITED","BHARAT CERTIS AGRISCIENCE LIMITED","BHARAT CHEMICAL PRODUCTS","BHARAT COLOR CHEM","BHARAT DYE CHEM","BHARAT ELECTRONIC LIMITED ","BHARAT GLASS & CROCKERIES","BHARAT GRAM UDYOG","BHARAT HEAVY ELECTRICALS LTD","BHARAT INDUSTRIAL CORPORATION PRIVATE LIMITED","BHARAT PETROCHEMICAL","BHARAT PLASTICS - UMA","BHARAT PRECISION INDUSTRIES","BHARAT RUBBER WORKS PVT. LTD.","BHARTI HYGIENECARE PVT LTD","BHARTIA PETRO","BHARTIYA VALVES PRIVATE LIMITED","BHASIN INDUSTRIES ","BHASKAR INDUSTRIES PVT. LTD.","BHAVANI INDUSTRIES","BHAVANISHANKAR INPEX","BHAVITRON POWERTEC PRIVATE LIMITED","BHAVNA STRIPS PROCESSORS PVT L","BHIDE & SONS PVT LTD","BHIDURI PACKAGING","BHIKSHU FABRICS","BHILAI ENGG. CORPORATION LTD.","BHOLE INTERMEDIATES","BHOOMI CREATION","BHOOMI SALES CORPORATION","BHOPAL GLUES & CHEMICALS PVT. ","BHUJ POLYMERS PVT.LTD","BHUMI BRASS & ALLOY","BHUMIJA COLOURANTS PVT LTD","BHUSHAN STEEL LIMITED","BHUTORIA REFRIGERATION PVT LTD","BIANCO TEXTILES SOLUTIONS","BIC CELLO (INDIA) PRIVATE LIMITED","BIG MISHRA PEDHA","BIG MISHRA PEDHA PRIVATE LIMITED","BIG MISHRA PRAYAGG PAN MASALA","BIHANI MANUFACTURING PVT LTD","BIJAL AGENCIES","BIKAJI FOODS INTERNATIONAL LTD","BIKANER POLYMERS PVT. LTD.","BIKANERWALA FOODS PVT LTD","BILISH INDIA LIFESCINCE PVT LT","BILT GRAPHIC PAPER PRODUCTS LIMITED","BIMAL CORPORATION","BINDAL EXPORTS LTD","BINNY POLYFLEX PVT LTD","BINNY WADS PVT. LTD","BIOCON LIMITED","BIOCON LIMITED DTA MFG ","BIOFI MEDICAL HEALTHCARE INDIA PRIVATE LIMITED","BIOGENETIC DRUGS PVT. LTD.","BIOGREEN TECHNOCHEM PRIVATE LIMITED","BIOLABS AND LIFE SCIENCES LLP","BIOMIN SINGAPORE PTE LTD","BION PLASTICS","BIOSTERILE CORPORATION","BIPICO INDUSTRIES TOOLS PVT LT","BIRLA CELLULOSIC","BIRLA CENTURY","BIRLA COPPER","BISLERI INTERNATIONAL PVT LTD","BITCHEM ASPHALT TECHNOLOGIES LIMITED","BITUMAG INDUSTRIES PRIVATE LIMITED","BIZCRAFT","BIZCRAFT SOLUTIONS PRIVATE LIMITED","BIZERBA INDIA PRIVATE LIMITED","BK GROUP","BKM INDUSTRIES LIMITED","BKS ENGINEERS","BLA ENGINEERING PRIVATE LIMITE","BLA PACKAGING INDUSTRIES PVT LTD","BLA UDYOG PRIVATE LIMITED","BLACK ROSE INDUSTRIES LIMITED","BLACKT ELECTROTECH","BLAUMANN INDUSTRIES PVT. LTD.","BLEACH SHINE CORPORATION","BLEND COLOURS PVT LTD","BLISSTERING ELECTRONICS PRIVATE LIMITED","BLOOM PACKAGING PRIVATE LIMITED","BLOSSOM INDUSTRIES LIMITED","BLOSSOM INNERS PVT. LTD.","BLOSSOM PRINT & PACK","BLU DIAMOND INC","BLUE BAGS","BLUE CIRCLE ORGANICS PVT LTD","BLUE CROSS LABORATORIES PRIVAT","BLUE MOUNT TEXTILES","BLUE SHINE INDUSTRIES","BLUEBELL POLYMERS PVT LTD","BLUELINE POWER PRODUCTS PVT. LTD.","BLUETECH IMPEX PRIVATE LIMITED","BLUEWUD CONCEPTS PVT LTD","BLURHINO SECURE STORAGE PRIVATE LIMITED","BMG CHEMICALS PVT. LTD.","BMS INDUSTRIES LTD","BMSS STEEL INDUSTRIES PVT. LTD","BMV FRAGRANCES PVT. LTD.","BNG COATING INDIA P LTD ","BNK STONES","BOB INDUSTRIES","BODAL CHEMICALS LTD","BOHRA MARKETING","BOLAS AGRO PVT LTD","BOMBAY AEROSOL","BOMBAY CHEMICAL EQUIPMENTS","BOMBAY ENGINEERING SYNDICATE","BOMBAY FIRE SAFETY","BOMBAY FLUID SYSTEM COMPONENTS","BOMBAY PLASTIC HOUSE","BOMBAY RAYON FASHION LTD","BOMBAY SALES CORPORATION","BOMBAY WELL PRINT INKS PVT LTD","BONAGERI CROPSCIENCE PVT LTD","BONDED TEXTILES PVT LTD","BONENG TRANSMISSION INDIA PVT.","BONFIGLIOLI TRANSMISSIONS PVT ","BONKERZ LIFESTYLE INC","BONY POLYMERS PVT LTD","BOOKS CORPORATION OF INDIA","BOON ADAM ENTRANCE TECHNOLOGY ","BORAX MORARJI LIMITED","BORKAR PACKAGING PRIVATE LIMITED","BOROSIL LIMITED","BOSCH LIMITED","BOSCH REXROTH INDIA P LIMITED","BOSMARK ENTERPRISES","BOSS APPLIANCES LLP","BOSTIK INDIA PRIVATE LIMITED","BOTTOMLINE ENTERPRISES","BOULTON TRADING CORPORATION","BOUTIQUE FASHIONS PRIVATE LIMITED","BPCS PACIFIC SPECIALITY","BPW TRAILER SYSTEMS INDIA PRIVATE LIMITED","BR ENTERPRISES","BRACO ELECTRICALS INDIA PVT LIMITED","BRAHM ARPAN ORGANIC PVT LTD","BRAJ INORGANICS PRIVATE LIMITED","BRAKES INDIA PRIVATE LIMITED","BRAKES INDIA PRIVATE LTD","BRANOPAC INDIA PVT LTD","BRASS","BRASSTECH ENGINEERING PVT. LTD","BRAWN BIOTECH LIMITED","BRAY CONTROLS INDIA PVT LTD","BRENNTAG INGREDIENTS PVT LTD","BRIDGE CHEM ","BRIGHT ENGINEERING","BRIGHT EXIM PVT LTD","BRIGHT PACKAGING PVT LTD","BRIGHT SALES CORPORATION","BRIGHTEX PHARMACHEM PRIVATE LIMITED","BRIGHTWAY GLOVES PRIVATE LIMITED","BRIJ FOOTCARE PRIVATE LIMITED","BRISK SURGICALS COTTON LIMITED","BRISLOY METALS INDIA PRIVATE LIMITED","BRITACEL SILICONES LIMITED","BRITANNIA INDUSTRIES LIMITED","BRITE PROOFINGS","BRITOMATICS INDIA PRIVATE LIMITED","BRU SPECIALITY CHEMICALS PRIVATE LIMITED","BRUDERER PRESSES INDIA PVT LTD","BRUGAROLAS INDIA PRIVATE LIMITED","BRUHATHI  AUTOTECH PRIVATE LIMITED","BRY AIR ASIA PRIVATE LIMITED","BS INTERNATIONAL ","BS&B SAFETY SYSTEMS (INDIA) LIMITED","BSMR COMMERCIAL PRIVATE LIMITED","BTL EPC LIMITED","BUILD CORE CHEMICALS","BUILDROCK ENGINEERING PRIVATE LIMITED","BULL AGRO IMPLEMENTS","BUNGE INDIA P LTD","BUNTROCK INTERNATIONAL PVT LTD","BUSCH VACUUM INDIA PVT LTD","BUSINESS STANDARD PRIVATE LIMITED","BUSY GROUP","BUTTERFLY GANDHIMATHI APPLIANC","BUZIL ROSSARI PVT LTD","BVM OVERSEAS LIMITED","BWF TEC INDIA PRIVATE LIMITED","BYHK ENTERPRISE","C AND B AROMAS LLP","C C L PRODUCTS (INDIA) LIMITED","C H JAVA & COMPANY","C J S SPECIALTY CHEMICALS","C K ZIPPER PRIVATE LIMITED","C M ABRASIVES PVT LTD","C M INDUSTRIES","C S COMPONENTS PVT.LTD.","C. BHOGILAL SOUTH END LLP ","C. P. MILK AND FOOD PRODUCTS PVT LTD","C.B. ELECTRICS INDIA PRIVATE LIMITED","C.J. SHAH & CO.","CABLE GLAND INCORPORATION","CABOT SANMAR LTD","CADENZA AYURCARE","CADENZA HEALTHCARE","CADILA HEALTHCARE LTD","CADILA PHARMACEUTICAL LTD","CADIZ PHARMACEUTICALS PVT. LTD","CAIR EUROMATIC AUTOMATION PRIVATE LIMITED","CALCO POLY TECHNIK PVT. LTD.","CALCUTTA FIT & FORGE","CALCUTTA KNIT WEAR","CALIBER PLASTECH PVT LTD","CALIBRE CHEMICALS PVT LTD","CALPRO FOOD ESSENTIALS PVT LTD","CAMBAY TECHNOPACK PVT LTD","CAMBRO NILKAMAL PVT LTD","CAMEX LTD.","CAMLIN FINE SCIENCES LIMITED","CAMPHOR & ALLIED PRODUCTS LTD","CAN GROUP OF INDUSTRIES","CANADIAN SPECIALITY VINYLS","CANARA GOODS TRANSPORT","CANBERRA CHEMICALS","CANI MERCHANDIZING PVT LTD","CANPAC TRENDS PRIVATE LIMITED","CANTON LABORATORIES PVT. LTD.","CAP AND SEAL INDORE PRIVAT LIMITED","CAPARO ENGINEERING INDIA LTD.","CAPE ELECTRIC PRIVATE LIMITED","CAPITAL COLOURS & ADDITIVES INDUSTRIES PRIVATE LIM","CAPITAL FOODS PRIVATE LTD","CAPITAL REFRACTORIES LTD","CAPREFINDIA PVT. LTD.","CAPTAIN POLYPLAST LTD.","CAPTAIN TRACTORS PVT.LTD","CARBOLINE (INDIA) PRIVATE LIMITED","CARBORUNDUM UNIVERSAL LTD","CARDOLITE SPECIALITY CHEMICAL ","CARE DETERGENTS PVT LTD","CARENOW MEDICAL PVT LTD","CARGO SOLUTIONS LOGISTICS PVT ","CARL BECHEM LUBRICANTS ","CARMEL INDUSTRIES LLP","CARNATION CREATIONS PRIVATE LIMITED","CAROL INDIA PRIVATE LIMITED","CAROL PETROLEUM PVT LTD","CARRYFAST SUPPLY CHAIN SOLUTION LLP","CASCY FORGE PRODUCTS","CASTEC ENGINEERS ","CASTROL INDIA LIMITED","CASTWEL INDUSTRIES","CATASYNTH SPECIALITY CHEMICALS PRIVATE LIMITED","CAVENTIS PHARMA PVT. LTD","CAVINKARE PVT LTD ","CBBM SOLUTIONS PRIVATE LIMITED","CBF COMPONENTS PVT. LTD.","CBS TECHNOLOGIES PVT LTD ","CCM METAL TECH PRIVATE LIMITED","CD PHARMACEUTICALS","CEASEFIRE INDUSTRIES PVT LTD.","CEAT LIMITED","CEAT SPECIALITY TYRES LTD","CEC FLAVOURS AND FRAGRANCES PRIVATE LIMITED","CEE KAY RUBBERS","CEECONS PROCESS TECHNOLOGIES","CELEBRITY BIOPHARMA LIMITED","CELEBRITY BREWERIES PRIVATE LIMITED","CELLFAST PACKING SOLUTION","CEMSEAL INFRAAID PRIVATE LIMITED","CENGAGE LEARNING INDIA PVT LTD","CENOSPHERE INDIA PVT. LTD.","CENTAUR PHARMACEUTICALS PVT LT","CENTRAL INDIA POLYSACK PRIVATE LIMITED","CENTRAL OIL INDUSTRIES","CENTROIID PLASTOPACK INDUSTRIE","CENTURY EXTRUSIONS LTD","CERADECOR INDIA LIMITED","CERAFLUX INDIA PVT LTD","CERATIZIT INDIA PVT LTD","CESARE BONETTI INDIA PVT LTD","CET ENVIRO PVT LTD","CETCO LINING TECHNOLOGIES INDI","CETEX PETROCHEMICALS LIMITED","CEVA POLCHEM PRIVATE LIMITED","CHAFEKAR PRESS TOOLS","CHAITANYA AGRO BIO-TECH PVT.LTD.","CHAITANYA INDUSTRIES","CHAKAACHAK CLEAN INDIA PVT LTD","CHAKRA ENTERPRISES","CHAMP INDUSTRES","CHAMPION ADVANCED MATERIALS PRIVATE LIMITED","CHAMPION JOINTINGS PVT LTD","CHAMPION PAPER INDUSTRY","CHAMPION SEALS (INDIA) PRIVATE LIMITED","CHAMPS ENGINEERING","CHAMUNDI EXPLOSIVES PRIVATE LTD.","CHANDAN AND SHAH TRADING LLP","CHANDAN ENTERPRISE","CHANDAN STEEL LTD","CHANDIMATA IRON INDUSTRIES","CHANDRA PRESSURE VESSELS & EVA","CHANDRESH CABLE LTD","CHANDRESH MARKETING PVT LTD","CHARMS CHEM PVT LTD","CHAROEN POKPHAND SEEDS (I)PVT ","CHARUTAR COSMETICS PRIVATE LIMITED","CHASSIS BARKES INTERNATIONAL","CHATTISGARH JUTE INDUSTRIES","CHAVO OVERSEAS","CHAWLA FIRE PROTECTION ENGINEERS PVT LTD","CHEM SELL","CHEM STONE","CHEM VERSE CONSULTANTS (I) PVT","CHEMBAZER INDUSTRIES","CHEMBOND BIOSCIENCE LTD","CHEMBOND CALVATIS INDUSTRIAL H","CHEMBOND CHEMICALS LTD","CHEMBOND CLEAN WATER TECHNOLOG","CHEMBOND DISTRIBUTION LTD","CHEMBOND MATERIAL TECHNOLOGIES PRIVATE LIMITED","CHEMBOND POLYMERS AND MATERIALS LIMITED","CHEMBOND WATER TECHNOLOGIES LIMITED","CHEMCLOTEX CORPORATION","CHEMCOAT ENTERPRISE","CHEMECA DRUGS PRIVATE LIMITED","CHEMETALL INDIA PVT. LTD","CHEMEX  ORGANOCHEM PVT LTD","CHEMEX CHEMICALS","CHEMEXON SOLUTIONS","CHEMI KLEEN INDIA PVT LTD","CHEMI LUBES","CHEMI TECH CONSTRUCTIONS PRIVATE LIMITED","CHEMICAL PROCESS EQUIPMENT","CHEMICAL PROCESS PIPING PRIVATE LIMITED","CHEMICO CHEMICALS PVT LTD","CHEMIE SYNTH (I) LTD","CHEMIESYNTH VAPI LIMITED","CHEMILAC PAINTS PRIVATE LIMITED","CHEMIN ENVIRO SYSTEMS PRIVATE LIMITED","CHEMINOVA INDIA LTD","CHEMINOX ENTERPRISE","CHEMLINE INDIA LIMITED","CHEMO GRAPHIC INTERNATIONAL","CHEMOLEUMS INDIA PVT LTD","CHEMOSYN LIMITED","CHEMPHAR ENTERPRISES","CHEMPLAST SANMAR LIMITED","CHEMPURE TECHNOLOGIES PVT LTD","CHEMSPEC CHEMICALS PVT LTD","CHEMTECH AGENCIES","CHEMTECH SURFACE FINISHING PRIVATE LIMITED","CHEMTEX ENTERPRISES","CHEMTEX SPECIALITY LIMITED","CHENG HUA ENGINEERING (INDIA) ","CHEP INDIA PRIVATE LIMITED","CHETAK CATERERS LLP","CHETAK MANUFACTURING COMPANY","CHHABRIA & SONS ","CHHAJED FOODS PVT.LTD","CHHAPERIA ELECTRO COMPONENTS PVT LTD.","CHHAYA INDUSTRIES","CHINTAN CORPORATION","CHIPCO BONDING SYSTEMS (INDIA)","CHIRAG ENTERPRISE","CHIRAG WAARE HOUSE ","CHIRANJILAL SPINNERS PRIVATE LIMITED","CHIRIPAL INDUSTRIES LTD","CHLORINA ORGANIC INDUSTRIES","CHOICE OF CHOICE","CHOKHAWALA DISTRIBUTORS","CHOKSEY CHEMICALS INDUSTRIES","CHOTTEE FOODS","CHOUDHARI ELECTRIC HOUSE","CHOUDHARY & COMPANY","CHOWDHARY FASHIONS PVT LTD","CHOWGULE ABP COATING (INDIA) P","CHOWGULE CONSTRUCTION CHEMICAL","CHRIST NISHOTECH WATER SYSTEMS","CHROMAFLO TECHNOLOGIES INDIA P","CHROMAPRINT INDIA PRIVATE LIMITED","CHRYSO INDIA PVT LTD","CHT(INDIA)PVT LTD","CICB -CHEMICON PVT LTD","CIIF LUBRICANTS PVT. LTD.","CIPLA LTD","CIRRUS GRAPHICS PRIVATE LIMITED","CITADEL BUILDING SYSTEM PRIVATE LIMITED","CITI TRADERS","CITY ADVERTISERS","CITY LUBRICANTS PVT LIMITED","CITY TRANSPORT","CJS SPECIALTY CHEMICALS PRIVATE LIMITED","CLAIRON FILTERS","CLARIANT CHEMICALS (I) LTD.","CLARIANT IGL SPECIALTY CHEMICALS PRIVATE LIMITED","CLARIANT INDIA LIMITED ","CLARION CASEIN LTD","CLARION ORGANICS LIMITED","CLARTECH ENGINEERS PRIVATE LIMITED","CLASSIC APPARELS","CLASSIC CHEMICALS","CLASSIC ENTERPRISE","CLASSIC POLYMERS & RESINS ","CLASSIC PRODUCTS PVT. LTD","CLASSIC SOLVENTS PRIVATE LIMITED","CLEAN COATS PVT. LTD","CLEAR CUT ABRASIVES INDIA PRIVATE LTD","CLEAR EDGE FILTRATION INDIA PRIVATE LIMITED","CLEARSEP TECHNOLOGIES I PVT LTD","CLENSTA INTERNATIONAL PRIVATE LIMITED","CLICKSENSE","CLIDE INTERNATIONAL PVT LTD","CLIMAX TRADING COMPANY","CLOVER FORGING & MACHINING PRIVATE LIMITED","CLYDE BERGEMANN INDIA PRIVATE ","CMC COMMUTATOR PRIVATE LIMITED","CMC TEXTILE PRIVATE LIMITED","CMJ BREWERIES PRIVATE LIMITED","CNK POLYMER PRODUCTS PVT LTD","CNS ASSOCIATES","CNS ENTERPRISES","COATALL FILMS PRIVATE LIMITED","COATING & COATING I PVT LTD","COCKTAIL DECOR PRIVATE LIMITED","COIM INDIA PVT LTD","COIMBATORE POTTERIES & REFRACTORIES","COIR LAND","COLDMAN LOGISTICS PRIVATE LIMITED","COLGATE PALMOLIVE (INDIA) LTD ","COLOR PLUS INDUSTRIES","COLORBAND DYESTUFF PVT LTD","COLORJET INDIA LIMITED","COLOSPERSE DYES & INTERMEDIATE","COLOUR STAR IMPEX","COLOURFLEX LAMINATORS LTD","COLOURTECH PRODUCTS PVT LTD","COLOURTEX INDUSTRIES PVT LTD","COLOURZONE CHEM TECH","COMET BRASS PRODUCT","COMET COLOR CHEM INDUSTRIES","COMMUNICATIONS TEST DESIGN INDIA PRIVATE LTD","COMPLETE COMFORT SOLUTIONS PRIVATE LIMITED","COMPUTER ENGINEERS","COMPUTER GRAPHICS PRIVATE LIMITED","CONA INDUSTRIES UNIT- III & IV","CONCEPT HOME TEXTILES PVT LTD","CONCEPT MARKETING & SERVICES","CONCEPTRENEUR VENTURES PRIVATE LIMITED.","CONCEPTS HYGIENE PVT LTD","CONCORD ENTERPRISES","CONCRETE ADDITIVES & CHEMICALS","CONDOR FOOTWEAR INDIA LTD ","CONFIDENT ENGINEERING","CONNECT CARGO PVT  LTD","CONNECTWELL INDUSTRIES ","CONNELL.BROS.CO (IND) PVT LTD","CONREPAIR INDIA PRIVATE LIMITED","CONSOLIDATED METAL FINISHING PVT LTD","CONSTRUCTION SPECIALTIES INTER","CONTACARE OPHTHALMICS AND DIAG","CONTECH INSTRUMENT LTD","CONTINENTAL ","CONTINENTAL PIPE & FITTINGS CO","CONTROL CARE AUTOMATION PVT. LTD.","CONTROLWELL INDIA PVT LTD","CONVERTECH EQUIPMENT PRIVATE L","CONVERTEX GASES PRIVATE LIMITE","COOL TECH ENTERPRISES","COOLDECK AQUA SOLUTIONS PVT. L","COOPER CORPORATION PRIVATE LIMITED","COPPERNIX METALS","CORAL LABOROTORIES LTD","CORAL PETROPRODUCTS UNIT-II","CORAPLAST INDUSTRIES","CORE DRILLING CHEMICALS","CORE NUTRILIFE LLP","COREL PHARMA CHEM","CORI ENGINEERS PRIVATE LIMITED","CORISE HEALTH CARE PRIVATE LIMITED","COROB INDIA PVT. LTD.","COROMANDEL ENGINEERING - CHE","COROMANDEL INTERNATIONAL LIMITED","CORONA ECOGEN (DELETE)","CORONA REMEDIES PVT. LTD.","CORROCARE INDUSTRIES","CORROGARD CHEMICALS","COSMAX PHARMA LLP","COSMEDEN PERSONAL CARE","COSMELLA INFINITY PRIVATE LIMITED","COSMIC PRINT SYSTEM","COSMO FILMS LIMITED","COSMO HERBALS LIMITED","COSMO PLAST","COSMO SPECIALITY CHEMICALS PRIVATE LIMITED","COSMOS LUBE TECHNIQUE PRIVATE LIMITED","COSMOS TECHNOCAST PVT.LTD.","COSMOS TWISTERS PVT. LTD.","COSSMIC PRODUCTS PVT LTD ","COSTER INDIA PACKAGING PVT LTD","COTMAC INDUSTRIAL TRADING PRIV","COUNTY DEVELOPERS PRIVATE LIMI","COVESTRO (INDIA) PRIVATE LIMIT","CPL ELECTRICAL INDIA PVT. LTD","CRAFTSMAN AUTOMATION LIMITED","CRANE PROCESS FLOW TECHNOLOGIE","CREATIVE  PLAST","CREATIVE AROMATICS (SPECIALITI","CREATIVE CASTINGS LTD","CREATIVE COILS (INDIA)","CREATIVE CORPRATION","CREATIVE EDUACATIONAL AIDS PVT","CREATIVE GARMENTS","CREATIVE GRAPHICS","CREATIVE HEALTH CARE PVT LTD","CREATIVE INDUSTRIES","CREATIVE PRINTERS PVT LTD","CREATIVE STYLO PACKS PRIVATE L","CRESCENT FLEXI PACK","CREST COMPOSITES & PLASTICS PV","CREST SPECIALITY RESINS PRIVATE LIMITED","CRESTA PAINTS INDIA PRIVATE LIMITED","CRIKLE TECHNOLOGIES PRIVATE LIMITED","CRODA INDIA COMPANY PVT LTD","CROMPTON GREAVES CONSUMER ELECTRICALS LIMITED","CROMPTON GREAVES LIMITED","CROMSEN IMPORTERS","CROPNOSYS (INDIA) PRIVATE LIMITED","CROWN CHEMICALS PRIVATE LIMITE","CROWN CLOSURE PVT LTD","CRUST N CRUMB FOOD INNOVATIONS INDA LIMITED","CRYSTAL CROP PROTECTION","CRYSTAL KNITTERS PRIVATE LIMITED","CRYSTAL WELDING & POWER TOOLS LLP","CS FINE INTERCHEM PRIVATE LIMITED","CS SPECIALITY CHEMICALS PVT. L","CTM INDIA LIMITED","CTM TECHNICAL TEXTILES LTD","CUDDLE UP DIET PRODUCTS PVT LTD","CUPS AND MOULDS LLP","CURE TECH CIVIL SERVICES","CURETECH","CURETECH SKINCARE","CUTCH OIL & ALLIED INDUSTRIES ","CUTTING EDGE TECHNOLOGIES","CU-V-KAR GENETIC MEDICINES PVT","CYBERNETIK TECHNOLOGIES PVT LTD","CZAR INTERNATIONAL","D & S ENTERPRISES","D B ELECTRICALS PRIVATE LIMITED","D C ENGINEERS PRIVATE LIMITED","D D PACKAGING","D D SOLUTIONS","D K STEEL","D PARIKH ENGINEERING WORKS","D R INTERNATIONAL PRIVATE LIMITED","D V POLYMERS INDIA PRIVATE LTD","D. A. STUART INDIA PRIVATE LIMITED","D. K. ENTERPRISE","D.A ENTERPRISES","D.A.STUART INDIA PVT LTD","D.D. POLYMERS","D.D.SHAH FRAGRANCES PVT. LTD.","D.J. BROTHERS","D.K. PHARMACHEM PVT LTD","D.M.S. INDUSTRIES","D.R.& SONS","D.S.ALLOYD PVT. LTD.","DABUR INDIA LTD","DACHSER INDIA PRIVATE LIMITED","DADA ASSOCIATES","DADAJEE DHAKJEE PVT.LTD","DAHANU RUBBER GLOVES MGF. CO.","DAHANUKAR ENGINEERING","DAHANUKAR MACHINE TOOLS","DAI ICHI KARKARIA LTD","DAIKI ALUMINIUM INDUSTRY INDIA PRIVATE LIMITED","DAKSHIN FOUNDRY PVT LTD","DALFO FLEXIPACK PRIVATE LIMITED","DALMIA CEMENT (BHARAT) LIMITED","DALMIA LIFE CARE PRIVATE LIMITED","DALMIA REFRACTORIES LIMITED","DAMAN POLYTHREAD LTD","DAN ALUFORM CONSULTANCY","DANISCO NUTRITION AND BIOSCIENCES INDIA PVT LTD","DANOPHARM CHEMICALS PRIVATE LIMITED","DANTAL HYDRAULIC PVT LTD.","DANVITA (INDIA) PVT. LTD.","DAOMING REFLECTIVE MATERIAL INDIA PVT LTD","DARAMIC BATTERY SEPARATOR ","DARPAN ELECTRICALS","DARRAN","DARSHAN CHEMICALS","DAS DRUG CENTRE","DATALINK INDUSTRIAL CORPORATION","DATTA INDUSTRIES","DAVANAGERE WIRE ROPE INDUSTRY PVT LTD","DAWER PROPACK PVT. LTD.","DAZZLEWORTH CHEMICALS","DB DAZZLE MODULAR SYSTEMS","DB ENGINEERING SOLUTIONS LLP","DB SCHENKER INDIA PVT. LTD.","DCC PRINT VISION LLP","DCM SHRIRAM LTD.","DCW LTD","DDP SPECIALITY PRODUCTS INDIA PRIVATE LIMITED","DE NEERS TOOLS LLP","DEACTIVATED >150","DEBNATH CORPORATION","DECAP CLOSURES PVT LTD","DECCAN CANS & PRINTERS PVT LTD","DECCAN ENTERPRISES PVT LTD","DECCAN RECYCLERS (P) LTD","DECENT METAL PRODUCTS","DECIMIN CONTROL SYSTEMS PRIVATE LIMITED","DECORA KITCHEN INTERIORS PRIVATE LIMITED","DECORATIVE PLYWOOD AND HARDWAR","DEE DEVELOPMENT ENGINEERS LTD","DEE TECH ALUCAN CO","DEE TEE INDUSTRIES LTD PLASTIC AND PACKING DIV","DEEAAR LABORATORIES","DEEP COLOURS","DEEP INDUSTRIES","DEEP PLAST INDUSTRIES","DEEP TYRES","DEEPAK CELLULOSE PVT LTD","DEEPAK ENTERPRISES","DEEPAK FASTENERS LTD.","DEEPAK INDUSTRIES LIMITED","DEEPAK NARROW FABRICS","DEEPAK NITRITE LIMITED","DEEPAK NOVOCHEM TECHNOLOGIES LIMITED","DEEPAK STEEL ( INDIA )","DEEPALI UNITED MFG PVT LTD","DEEPSON INDUSTRIAL CORPORATION","DEEPWELD AGENCIES","DEERFOS INDIA PVT LTD","DEHLVI AMBAR HERBALS PRIVATE LIMITED","DEL PD PUMPS & GEARS PVT. LTD.","DELCURE LIFE SCIENCES LTD","DELICATE FOODS","DELICIA FOODS","DELIGHT CHROME CHEMICALS PVT L","DELITE PLASTICS MOULDING INDUSTRIES","DELPHI TVS TECHNOLOGIES LIMITED","DELTA ELECTRONIC ( INDIA )","DELTA FINOCHEM PRIVATE LIMITED","DELTA GROUP","DELTA MANUFACTURING LIMITED","DELTA SCIENTIFIC","DELVAL FLOW CONTROLS PRIVATE LIMITED","DEMECH CHEMICAL PRODUCTS PRIVATE LIMITED","DENAJEE HEALTH CARE PRODUCTS","DENIM COLOURCHEM PVT LTD","DENISH CHEM LAB LTD","DENTAL PRODUCTS OF INDIA","DENZONG ALBREW PVT LTD","DEON TAPES INDUSTRIES PRIVATE LIMITED","DEORA WIRES N MACHINES PVT LTD","DESAI BROTHERS LTD","DESAI ENTERPRISE","DESAI FOODS PVT LTD","DESAI IMPEX PVT LTD","DESAI METALINKS PVT. LTD.","DESAI OVERSEAS PRIVATE LIMITED","DESANA POLY PLASTICS INDUSTRIES","DESH WIRE PRODUCTS PVT. LTD.","DESICCANT ROTORS INTERNATIONAL","DESMI INDIA LLP","DEUGRO PROJECTS (INDIA) PRIVATE LIMITED","DEV ABRASIVE","DEV DYE CHEM INDUSTRIES","DEV ENTERPRISES","DEV FASHIONS","DEV RAJ RANGWALA","DEVA PESTICIDES LIMITED","DEVAGIRI ASSOCIATES","DEVANSHI DYE STUFF","DEVARSHI ENTERPRISES","DEVARSONS INDUSTRIES PVT LTD","DEVASHISH INFRASTRUCTURE PRIVATE LIMITED","DEVASHISH POLYMERS PVT. LTD.","DEVASHREE INDUSTRIES","DEVEURO PAPER PRODUCTS LLP","DEVI POLYMERS P LTD","DEVICHAND PAPER INDUSTRIES ","DEVILKALA INDUSTRIES ","DEVRAJ ENGINEERS","DEVRAJ RANGWALA","DEVRAT OVERSEAS","DEVSONS ENGINEERS","DEVU TOOLS PVT LTD","DEVVA INDUSTRIES","DEVYANI FOOD INDUSTRIES LTD.","DEWAS METAL SECTION LTD","DEXTRA INDIA ","DFX LOGISTICS","DHABRIYA POLYWOOD LIMITED","DHALKOT INDUSTRIES (CHEMICAL D","DHANANJAY INDUSTRIAL ENGINEER PVT LTD","DHANASHREE ENTERPRISES","DHANLAXMI CHEMICALS","DHANPAT RAI WALAITI RAM OSWAL","DHANUKA LABORATORIES LTD","DHANVANTARI HEALTH CARE","DHANVANTARY BIOSCIENCE (OPC) PRIVATE LIMITED","DHANVI AGRI GENETICS PRIVATE LIMITED","DHANWANTARI DISTRIBUTORS PRIVATE LIMITED","DHARA PETROCHEMICALS PVT LTD","DHARAMPAL PREMCHAND LTD","DHARAMRAJ CHIKKI & KAJUPURI","DHARIYAL POLYMERS PVT. LTD","DHARMAJ CROP GUARD LIMITED","DHARMANANDAN EXPORT PRIVATE LI","DHARNIISS TRADERS","DHARPAT CASTING PVT. LTD.","DHARWAD MISHRA PEDHA & FOOD PROCESSING INDUSTRY","DHASH PV TECHNOLOGIES PVT LTD","DHIREN PLASTIC INDUSTRIES","DHRUV CHEM INDUSTRIES","DHUKKA ENTERPRISES","DIACH CHEMICALS & PIGMENTS PRIVATE LIMITED","DIAMINES AND CHEMICALS LTD","DIAMOND AGARBATTI & CO - RJJ","DIAMOND POLYMERS","DIC INDIA LIMITED","DIESEL LOCO MODERNISATION WORK","DIFFUSION ENGINEERING LTD","DIGJAM LIMITED","DIGVIJAY DRY FRUITS-MAP","DIMETRICS CHEMICALS PRIVATE LIMITED","DIMPLE POLYMERS","DINAMIC OIL INDIA PVT LTD.","DINESH TEXTILE MILLS","DINESHCHANDRA R AGRAWAL INFRACON PRIVATE LIMITED","DINSHAWS DAIRY FOODS PVT LTD","DISA INDIA LIMITED","DISHA SUPER GLUES","DISHA WIRES & CABLES PVT LTD.","DISHMAN PHARMACEUTICAL & CHEMI","DIVINE ENGINEERING SERVICE","DIVINE THERMAL WRAP PVT LTD","DIVINE TITANIUM PRIVATE LIMITED","DIVJYOT CHEMICALS PRIVATE LIMITED","DIVYA GLOBAL PRIVATE LIMITED","DIVYA PREM ENTERPRISES","DIVYA TEXTILES","DIWAN MUNDHRA BROS PRIVATE LIMITED","DIXCY TEXTILES PRIVATE LIMITED","DIXON TECHNOLOGIES (INDIA) LIMITED","DKI APPERAL PVT LTD ","DKR IMPEX PVT LTD","DKSH INDIA PRIVATE LIMITED","DLS ENTERPRISES","DMAAK AUTOMATIVE PRIVATE LIMITED","DME (INDIA) PRIVATE LIMITED","DNR CORPORATION","DNV FOOD PRODUCTS PRIVATE LIMITED","DOBERSUN PRODUCTS PVT LTD","DOCBEL SCALES","DOCTOR AUXILIARY PVT.LTD.","DOCTORS LIFE SCIENCES INDIA LIMITED","DODIA ENGINEERS","DOKSUN POWER PVT. LTD.","DOLLAR INDUSTRIES LIMITED","DOLPHIN POLYFILL PRIVATE LIMITED","DON CONSTRUCTION CHEMICALS INDIA LTD","DONEAR INDUSTRIES LTD","DOOSAN BOBCAT INDIA PRIVATE LI","DORF KETAL CHEMICALS INDIA PVT","DORF KETAL SPECIALITY CATALYST","DORMER TOOLS INDIA PRIVATE LIMITED","DOT PROPACK INDUSTRIES PRIVATE LIMITED","DOVE PHARMACEUTICALS","DOVER INDIA PRIVATE LIMITED","DOW AGRO SCINECES ","DOW CHEMICAL INTERNATIONAL PRI","DOW CORNING INDIA PRIVATE LIMI","DR PLASTO TECH PRIVATE LIMITED","DR. SABHARWAL WOUND CARE","DR.KHAN INDUSTRIAL CONSULTANTS","DRAKT PHARMACEUTICAL PVT. LTD.","DREAM WORKS","DRIPLEX WATER ENGINEERING PRIV","DRISHTI METALS CO.","DRISHTI POLYTECH","DRIVE INDIA SOLUTIONS LTD","DRM  LUBRICANTS INTERNATIONAL ","DRT LIFESCIENCES LLP","DRT-ANTHEA AROMA CHEMICALS PVT","DRUMSEALS INDUSTRIES","DRY AIR INDIA PRIVATE LIMITED","DRYCHEM SOLUTIONS PRIVATE LIMITED","DRYTECH PROCESSES (I) PVT LTD","DS SPICECO PRIVATE LIMITED","DSM NUTRITIONAL PRODUCTS INDIA","DST CHEMICALS INDIA PRIVATE LIMITED","DTC TECHNOLOGIES PRIVATE LIMITED","DUBOND PRODUCTS INDIA PRIVATE ","DUCOL ORGANICS AND COLOURS ","DUFLON INDUSTRIES PRIVATE LTD ","DUGAR POLYMERS LIMITED","DUHEE ALLOY STEEL PROCESSORS","DUKE FASHIONS INDIA LIMITED","DUKE PLASTO TECHNIQUE PVT LTD","DUNUNG INDUSTRIES PRIVATE LIMI","DUPLAST","DUPLAST NEW","DURAKLEAN SOLUTIONS","DURALABEL GRAPHICS PVT LTD","DURA-LINE INDIA PRIVATE LIMITE","DURATUFF POLYPACKS PRIVATE LIMITED","DURGA POLYPACK","DURO CHEM SUGAR CHEMICALS LIMI","DURO PIPES PRIVATE LIMITED","DURO SHOX PVT LTD","DUZONE INDIA CHEMICALS","DVB TECHNOLOGIES PVT LTD","DWD PHARMACEUTICALS LTD","DY AUTO INIDA PVT LTD","DYCRON COLOUR CHEM PRIVATE LIMITED","DYNA FILTERS PRIVATE LIMITED","DYNAMIC AIRFREIGHT PVT LTD","DYNAMIC INDUSTRIES LTD","DYNAMIC TECNO MEDICALS P LTD","DYNAMIC TEXTILE MILLS","DYNAMIC VALVES PVT LTD","DYNASTY MODULAR FURNITURES PRIVATE LIMITED","DYNATRON PRIVATE LIMITED","DYNEAMIC PRODUCTS LTD.","DYNOSOUR INTERNATIONAL","DYSTAR INDIA PRIVATE LIMITED","EAGLEBURGMANN INDIA PRIVATE LIMITED","EANNOL AUTOMOTIVE INDIA PRIVATE LIMITED","EAPEN JOSEPH AND CO","EARTH AUTO TECH","EARTH POLYMERS INDUSTRIES","EARTHY FOODS PRIVATE LIMITED","EASECHEM FOOTWEAR PRIVATE LIMITED","EAST COAST PAINTS PRIVATE LIMITED","EAST INDIA BEARING CO. PVT LTD","EAST WEST PHARMA","EAST WEST SEEDS INDIA PRIVATE LIMITED","EASTCORP POLYMERS PRIVATE LIMITED","EASTERN BEARINGS P.LTD.","EASTERN CARGO CARRIERS INDIA PVT LTD","EASTERN CONDIMENTS PVT LIMITED","EASTERN PETROLEUM PVT.LTD","EASTMAN AUTO & POWER LTD.","EASTMEN CHEMICALS","EASY FLUX POLYMERS PRIVATE LIMITED","EAU CHEMICAL MFG PVT LTD","EBACO INDIA P LTD","EBCO PVT LTD ","EBULLIENT PACKAGING PVT. LTD.","EBUTOR DISTRIBUTION PRIVATE LIMITED","EC INDUSTRIES","ECO PEB SOLUTIONS","ECO PEB STEEL STRUCTURE INDIA PVT LTD","ECO POLYCHEM PVT LTD","ECO TECHNOLOGY & PROJECTS","ECOKRIN HYGIENE PRIVATE LTD","ECOLINE EXIM PVT LTD","ECONOMODE FOOD EQUIPMENT","ECONOMY PROCESS SOLUTIONS PRIV","ECONOMY REFRIGERATION PRIVATE LIMITED","ECOPLAST LIMITED","ECOSI ENERGY PRIVATE LIMITED","ECOTRAIL PERSONAL CARE PVT LTD","EDELMANN PACKGING INDIA PVT LT","EDICON PNEUMATIC TOOL CO P LTD","EDTHALA POLYMERS PVT LTD","EELGRASS ENGINEERING SERVICES PRIVATE LIMITED","EFFCO FINISHES & TECHNOLOGIES PVT LTD","EFFICIENT INNOVATION","EFFTRONICS SYSTEMS PVT LTD","EFRA PROJECTS LLP","EFTEC INDIA PRIVATE LIMITED","EGK FOODS PRIVATE LIMITED","EHOME MAKER SOLUTIONS INDIA PRIVATE LIMITED","EICHER GOODEARTH PRIVATE LIMIT","EIMCO ELECON I LIMITED","EIMCO ELECON INDIA LIMITED","EIRICH INDIA PVT LTD","EK TEK PHARMA","EKBOTE'S LOGS AND LUMBERS(P)LT","EKOL PAINT INDUSTRIES","ELANTAS BECK INDIA LTD.","ELASTREX POYMERS PVT LTD","ELAVIN CHEMTECH PRIVATE LIMITED","ELECON ENGINEERING COMPANY LIM","ELECON PERIPHERALS LIMITED","ELECTRIC INSTRUMENTATION","ELECTRICAL LINES","ELECTRICAL SOLUTIONS","ELECTRO ALLIED PRODUCTS","ELECTRO CRIMP CONTACTS INDIA PRIVATE LIMITED","ELECTRO MAGNETIC INDUSTRIES","ELECTRO PNEUMATICS & HYDRAULICS INDIA PVT LTD","ELECTRO TRADE CORPORATION","ELECTROCHEM TECHNOLOGIES INDIA PRIVATE LIMITED","ELECTROCOATING AND INSULATION TECHNOLOGIES PVT. LT","ELECTROMAG DEVICES PVT LTD","ELECTROMECH INDUSTRIES","ELECTRON ONLINE STUDIO PRIVATE LIMITED","ELECTRONET EQUIPMENTS PRIVATE LIMITED","ELECTROSPARK ENCLOSURES PRIVATE LIMITED","ELECTROSTEEL CASTINGS LIMITED","ELEGANT OFFSET PRINTERS PRIVAT","ELEKTRA COATINGS","ELEMENTS","ELEPHANT STRONG DISTRIBUTORS","ELERO MOTORS & CONTROLS PRIVATE LIMITED","ELETE BIOTECH PRIVATE LIMITED","ELETTROMIL INDIA PRIVATE LIMIT","ELF ELECTROLUMECH INDIA PVT LTD ","ELGI EQUIPMENTS LIMITED","ELI LILLY AND COMPANY (INDIA) ","ELICA PB INDIA PRIVATE LIMITED","ELITE CHEMICALS","ELITE INDUSTRIES","ELITE ROADWAYS ","ELITE THERMAL ENGINEERS PRIVATE LIMITED","ELIXA TECHNOLOGIES PRIVATE LIMITED","ELIXIR FOODS & BEVERAGE PVT LTD","ELIXIR LIFE CARE PRIVATE LIMITED","ELKAY CHEMICALS PRIVATE LIMITE","ELKEM SOUTH ASIA PVT LTD","ELLE ELECTRICALS PVT LTD","ELLEYS INDUSTRIES INDIA PVT. LTD.","ELLSWORTH ADHESIVES I PVT LTD","ELMECH ENGINEERS ","ELOF HANSSON INDIA PRIVATE LIMITED","ELOFIC INDUSTRIES LIMITED","ELSON PACKAGING INDUSTRIES PVT","ELSTER-INSTROMET INDIA PRIVATE LIMITED","ELTETE INDIA TRANSPORT PACKAGI","ELVI BARDAHL INDIA PRIVATE LIMITED","ELWA ENTERPRISES","EMAMI - ALL INDIA","EMAMI AGROTECH LIMITED","EMAMI LIMITED","EMARK ENERGISERS PRIVATE LIMITED","EMBASSY BIOGENIC","EMBASSY SILICONES ","EMBEE CORPORATION","EMCO DYESTUFF PVT LTD","EMCO PRECIMA ENGINEERING PVT LTD","EMERSON INDUSTRIAL AUTOMATION ","EMERSSON PROCESS ","EMERY FOODS PRIVATE LIMITED","EMINENCE EQUIPMENTS PRIVATE LIMITED","EMIRATES LUBE INDIA PRIVATE LIMITED","EMMENNAR PHARMA PRIVATE LIMITE","EMPIRE HOME APPLIANCES LTD","EMPIRE SPARE PARTS & LUBRICANTS","EMPIRE SPICES AND FOODS LTD","EMRAD PROJECTS PVT LTD","EMULSICHEM LUBRICANTS PRIVATE LIMITED","ENACO SAFETY PRODUCTS PRIVATE LIMITED","ENCON (INDIA)","ENCON COOLING TOWERS PVT LTD","ENCON ENGINEERS","ENCON FAN INDIA PRIVATE LIMITED","ENCON FRP FAN PRIVATE LIMITED","ENCON INTERNATIONAL","ENCRAFT INDIA PVT.LTD","ENDICO POWER TOOLS (INDIA)","ENDOC LIFECARE PVT LTD.","ENERGO PRODUCTS LIMITED","ENERGY ENGINEERING SERVICES","ENERGYPACK BOILERS PVT. LTD.","ENFROS TECHNOLOGIES PRIVATE LIMITED","ENGENIUS METALS PVT LTD","ENGINEERING SERVICE ENTERPRISE","ENGINEERS & ENGINEERS ELECTRICALS PVT.LTD.","ENKAY CONTAINERS","ENKAY SOFT","ENOPECK SEALS INDUSTRIES","ENPOSSIBILITIES PRIVATE LIMITED","ENSEMBLE FITOUT AND FIXTURES PRIVATE LIMITED","ENSISCO SECURITY SEALS INDUSTR","ENTERO HEALTHCARE SOLUTIONS PRIVATE LIMITED","ENTILA TRADE LINK","ENTOD PHARMACEUTICALS LTD","ENTREMONDE POLYCOATERS LIMITED","ENVIRO SOLID RESILIENT TYRES","ENVIRON SPECIALITY CHEMICALS P","ENVIROS INDIA PRIVATE LIMITED","ENVYPACK INDUSTRIES LLP","EON ELECTRIC LTD","EPC INDUSTRIE LIMITED","EPOXY TERMINAL AND EQUIPMENT PRIVATE LIMITED","EPP COMPOSTIES PVT LTD","EPSILON CARBON PRIVATE LIMITED","EPSILON ELECTRONIC EQUIPMENT ANDCOMPONENTS PRIVATE","EQUITY LOGISTICS","ERNST PHARMACIA","ERUM EXPORT PRIVATE LIMITED","ESAAR INTERNATIONAL","ESAB","ESAB INDIA LTD-CHE","ESAPLLING PRIVATE LIMITED","ESDEE PAINTS LTD","ESHA INC","ESKAG PHARMA PVT. LTD.","ESKAY IODINE PVT LTD","ESPEE INDUSTRIES","ESS ELL ENTERPRISES","ESSA GARMENTS PRIVATE LIMITED","ESSAE TERAOKA PVT.  LTD.","ESSAR INTERNATIONAL","ESSAR STEEL MARKETING LIMITED","ESSEN MULTIPACK LTD","ESSEN PRODUCTS INDIA LTD","ESSKAY MACHINERY PRIVATE LIMITED","ESSKAY PRECISION ENGINEERING","ESSON FURNISHINGS PVT LTD","EST TOOL STEEL PRIVATE LIMITED","ESTEEM AUTO PVT LTD","ESTEEM EXPORTS","ESTEEM INDUSTRIES PRIVATE LIMI","ESTEEM LABELS PRIVATE LIMITED","ESTELLE CHEMICALS PVT LTD","ESTER INDIA","ESTER INDUSTRIES LIMITED","ESTERKOTE PRIVATE LIMITED","ESWARI ELECTRICALS PVT LTD","ETA TECHNOLOGY PRIVATE LIMITED","ETERNIS ENTERPRISES","ETERNIS FINE CHEMICALS LIMITED","ETERNIS HYGIENE PRODUCTS","ETHIALL REMEDIES","ETHICARE REMEDIES","ETHICS INFINITY PRIVATE LIMITED","ETHINEXT PHARMA","EUKPRO BIOTECH PRIVATE LIMITED","EU-MEDICAMENTS","EUREKA CHEMICALS","EUREKA FABRICATORS PVT LTD.","EURO INDIA CYLINDERS LTD","EURO LABS","EUROASIA TRANS CONTINANTAL","EUROCAO FOODS INDIA PVT LTD","EUROCOUSTIC PRODUCTS LIMITED","EUROFINE CHEMICALS","EUROINDIA TRADING","EUROMIN CHEMICALS","EUROPA BIKES","EUROPA BIOCARE PVT LTD","EUROPACK","EUROPEAN TEXTILE CHEMICAL CORPORATION","EUROPET PRODUCTS PRIVATE LIMITED","EUROTECK ENVIRONMENTAL PRIVATE LIMITED","EUTORIIA ARCHEITECTURAL","EVELINE INTERNATIONAL","EVER BRIGHT PLASTIC PVT. LTD.","EVER GREEN FOOD MFG.CO","EVER LIGHT INTERNATIONAL","EVEREADY INDUSTRIES  INDIA LTD","EVEREST ALUMINIUM PVT. LTD.,","EVEREST BLOWERS PRIVATE LIMITE","EVEREST COMPOSITES PVT.LTD.","EVEREST FOOD PRODUCTS PRIVATE LIMITED","EVEREST INDUSTRIES LIMITED","EVEREST INTERMEDIATES","EVEREST KANTO CYLINDER LTD","EVEREST ORGANICS LIMITED","EVEREST TRANSMISSION","EVERGREEN FOOD INDUSTRIES","EVERGREEN INDUSTRIES","EVERGREEN TECHNOLOGIES PVT LTD","EVERLAST COMPOSITES LLP","EVERSENDAI CONTRUCTION PVT LTD","EVERSHINE APPLIANCE PVT LTD","EVIALIS INDIA LIMITED","EVONIK CATALYSTS INDIA PRIVATE","EVONIK INDIA PVT. LTD","EWAC ALLOYS LIMITED","EXCEL ABRASIVES PRIVATE LIMITE","EXCEL ADHESIVE TAPES PRIVATE LIMITED","EXCEL CABLE (INDIA)","EXCEL CARBON EVIENCE PVT LTD","EXCEL CHEM TECH","EXCEL CORPORATION","EXCEL CRAFT MACHINERIES PVT LT","EXCEL CROP CARE LTD","EXCEL FORMULATIONS","EXCEL GAS & EQUIPMENTS PVT. LT","EXCEL INDIA PROTECTIVE PAINTS ","EXCEL INDUSTRIES","EXCEL INDUSTRIES LIMITED","EXCEL LOGISTICS","EXCEL MARKETING CORPORATION","EXCEL PACK PVT.LTD","EXCEL POLYMER INDUSTRIES","EXCEL TUBE & CONES","EXCELENCIA INDUSTRIES","EXCELLARE HEALTHCARE PVT LTD","EXCELLENCE CORPORATION","EXCELLENCE ORGANISATION PVT LTD","EXCELPACK INCORPORATION","EXCESS HOLOGRAPHICS PRIVATE LIMITED","EXCIPIENTS HOUSE","EXCIPIENTS HOUSE LLP","EXEL CABLE (INDIA)","EXEMED PHARMACEUTICALS","EXIDE INDUSTRIES LTD","EXOTIC MASHUROOMS ","EXPANDED POLYMER SYSTEMS ","EXPLORE ","EXPLORE DISTRIBUTORS","EXPO FINE CHEMICAL","EXPO FREIGHT PVT LTD","EXPO SALES INDIA PVT. LTD.","EXPRESS LOGISTICS & SOLUTION","EXTINCT FIRE ENGINEERS PVT LTD","EYE GLOBE INDUSTRIES","EYON IMEX","F J RUBBER PRODUCTS PRIVATE LI","FABTECH TECHNOLOGIES INTERNATI","FAB-TECH WORKS & CONSTRUCTION ","FABVISION LIGHTS","FAG BEARINGS INDIA LIMITED","FAIRMACS SHIPPING AND TRANSPOR","FAITH INDUSTRIES LIMITED","FAITH INNOVATIONS","FAIZ TRADERS","FALCON AGENCIES PRIVATE LIMITED","FALCON GARDEN TOOLS PVT LTD","FANUC INDIA PRIVATE LIMITED","FAST TECH FASTNERS","FASTS PRINTS AND PACKAGING","FCG HI-TECH PVT.LTD.","FCG POWER INDUSTRIES PVT. LTD.","FDC LIMITED","FEATHERLITE OFFICE SYSTEMS","FECPI INDIA PRIVATE LIMITED","FEDERAL ENGINEERS ","FEED WALE","FEEL GOOD INDIA","FELIX PHARMA LABS","FEMICK INDUSTRIES","FENASIA LIMITED","FENGYUAN INDIA PRIVATE LIMITED","FERM INDIA","FERNS N PETALS PRIVATE LIMITED","FEROLITE JOINTINGS LIMITED","FERRETERRO HOISTS PRIVATE LIMI","FERRETERRO INDIA PVT LTD","FERRETERRO TOOLS LLP","FERRO CHEM INDUSTRIES","FESTO INDIA PVT LTD","FF AGRO TECHNOLOGIES PVT LTD","FFFFFFFFFFFFFF","FGFGFG","FIBERTEK COMMUNICATION PVT LTD","FIBOX INDIA PRIVATE LIMITED","FIBREZONE INDIA","FIBRO GRATS PRIVATE LIMITED","FIDUCIA ESSENTIAL OIL AND BIOTECH INDUSTRIES","FIGO IMPEX","FILATEX VCT PVT LTD","FILATEX VECHUKUNNEL (P) LTD","FILEX SYSTEM PVT LTD","FILTECH","FILTECH INDIA","FILTROWIN INDUSTRIES","FINAR LIMITED","FINE EQUIPMENTS INDIA PVT  LTD","FINE FRAGRANCES PVT LTD","FINE TECH CORPORATION PVT LTD","FINE TECHNOLOGIES INDIA PVT LT","FINE THREAD FORM INDUSTRIES","FINEOTEX CHEMICAL LTD","FINO FOODS PRIVATE LIMITED","FINOLEX CABLES LIMITED","FINOLEX PLASSON INDUSTRIES PRIVATE LIMITED","FINOR PIPLAJ CHEMICALS LTD.","FINRAY BIOTECH INC","FINX TRADING ( INDIA ) LLP","FIRE AND PERSONAL SAFETY ENTERPRISES","FIRE CLOSURE AND SAFETY SOLUTION","FIRE SAFETY DEVICES PVT.LTD","FIRE STONE INDUSTRIES","FIREFLY FIRE PUMPS PRIVATE LIMITED","FIREPRO SYSTEMS PRIVATE LIMITED","FIRMENICH AROMATICS (INDIA) PV","FIRSTCHEM INDUSTIRES","FIRSTCHOICE READYMIX","FISHFA BIOGENICS","FIT WEL INDUSTRIES LLP","FITPACK TEXTILES MILLS LTD.","FITTECH INDUSTRIES PRIVATE LTD","FITZROY RESOURCES PRIVATE LIMITED","FIVE STAR DEHYDRATION PVT LTD","FIVEBRO INTERNATIONAL PVT LTD","FIVEBROS FORGINGS PVT. LTD.","FIVES CAIL KCP LTD","FIVESTAR BRASS INDIA LIMITED","FIXATTI INDIA PRIVATE LIMITED","FIXDERMA INDIA PRIVATE LIMITED","FJM CYLINDERS PRIVATE LIMITED","FLAMEBLITZZ","FLAMERING MACHINES & TOOLS","FLAMINGO PHARMACEUTICALS LIMIT","FLARE LUMINARIES PRIVATE LIMITED","FLASH FORGE PRIVATE LIMITED","FLEETGUARD FILTERS PRIVATE LIMITED","FLEXI BOND INDUSTRIES","FLEXI PACK","FLEXI SHINE POLYBLENDS LLP","FLEXIBLE ABRASIVES PRIVATE LIMITED","FLEXIBOND INDUSTRIES PRIVATE LIMITED","FLEXICARE MEDICAL (INDIA) PRIVATE LIMITED","FLEXICONS LIMITED","FLEXIFOIL PACKAGING  PVT  LTD","FLEXILIS PRIVATE LIMITED","FLEXO ART","FLEXO FOAM PVT LIMITED","FLEXPRO ELECTRICALS PVT LTD","FLEXY STEEL","FLING GROUP INDIA PVT LTD","FLINT GROUP (I) PVT. LTD.","FLOETER INDIA RETORT POUCHES P","FLOMETALLIC INDIA PVT. LTD.","FLORA ESSENTIAL OILS","FLORESSENCE PERFUMES PVT LTD","FLORISH PHARMA","FLOTEK INTERNATIONAL PVT LTD","FLOURIDES AND CHEMICALS","FLOWCHEM INDUSTRIES","FLOWCRETE INDIA PRIVATE LIMITED","FLOWJET VALVES PVT LTD","FLOWSERVE SANMAR PRIVATE LIMITED","FLUERON INKS PVT LTD","FLUID DYNAMICS PVT LTD","FLUIDLINE SYSTEMS & CONTROLS PVT LTD","FLUIDLINE VALVES INTERNATIONAL","FLUIDOMAT LIMITED","FLUOROLINED EQUIPMENT PVT LTD","FLYJAC LOGISTICS PVT.LTD -MYLB","FLYJACK","FM PBW BEARINGS PVT. LTD","FMC INDIA PRIVATE LIMITED","FOAM HOME PVT LTD ","FOAM SPECIALTIES INDIA PRIVATE LIMITED","FOAMTECH ANTIFIRE COMPANY","FOCUS TEXTILES","FOGGERS FARM SOLUTIONS","FOGGERS INDIA PVT LTD","FONTECH FOUNDRY (P) LTD.","FOOD SERVICE INDIA PVT LTD","FOOD SOL INDIA","FOOD SOLUTION (INDIA) LTD","FOODS AND INNS LIMITED","FOOT ART INDUSTRIES","FORACE INDUSTRIES","FORACE POLYMERS P.LTD","FORBES AND COMPANY LIMITED","FORBO SIEGLING MOVEMENT SYSTEMS INDIA PRIVATE LIMI","FORECH INDIA PVT LTD","FORECH MINING & CONSTRUCTION I","FOREX FASTNERS PVT. LTD","FORGEPRO INDIA PVT. LTD.","FORIN CONTAINER LINE","FORMACATORS","FORMULATED POLYMERS LIMITED","FORTIS CABLES PRIVATE LIMITED","FORTUNE ACCESSORIES","FORTUNE STRETCH PACK.","FORUM ENTERPRISES","FORWARD PRECISION ENGINEERS PR","FOSROC CHEMICALS (INDIA) PVT L","FOURESS ENGINEERING I LTD","FOUR-P INTERNATIONAL PRIVATE LIMITED","FOURRTS (IND) LAB. PVT. LTD","FOURTH DIMENSION INDIA PRIVATE LIMITED","FPI AUTO PARTS INDIA PRIVATE LIMITED","FRAGRANCE WORLD ","FRANCO INDIAN REMEDIES PVT LTD","FRANK EDUCATIONAL AIDS PRIVATE LIMITED","FRASCOLD INDIA PVT.LTD.","FREEDOM CHEMTECH LLP","FREEDOM ENTERPRISES","FREIGHT SYSTEMS (INDIA) PVT.LT","FRESCO PRINTPACK PVT LTD","FRESENIUS KABI INDIA PVT. LTD.","FREUDENBERG FILTRATION TECHNOLOGIES INDIA PVT. LTD","FREUDENBERG PERFORMANCE MATERIALS INDIA PVT LTD.","FREUDENBERG-NOK PRIVATE LIMITED","FRICK INDIA LTD","FRIGORIFICO ALLANA PRIVATE LIMITED","FROMM PACKAGING SYSTEM ","FRONTIER AGENCIES (P) LTD","FRONTIER ALLOY STEELS LIMITED","FRONTIER TECHNOLOGIES PVT. LTD","FROSTY BOY INDIA PRIVATE LIMITED","FRUTIGER INDIA PRIVATE LIMITED","FS COMPRESSORS INDIA PRIVATE LIMITED","FUCHS LUBRICANTS (IND) PVT LTD","FUJI ELECTRIC CONSUL NEOWATT PRIVATE LIMITED","FUJIFILM SERICOL INDIA PRIVATE LIMITED","FUJITEC INDIA PRIVATE LIMITED","FUJIYAMA POWER SYSTEMS PRIVATE LIMITED","FULETRA SNACKS PRIVATE LIMITED","FULHAM (INDIA) PRIVATE LIMITED","FUNVENTION LEARNING PVT. LTD.","FURN BAMBU PRIVATE LIMITED","FURTADOS MUSIC INDIA PRIVATE LIMITED","FURUS PACKAGING PVT. LTD. ","FUSION POWER SYSTEMS","FUSO GLASS INDIA PVT LTD","FUTURA CERAMICS (P) LTD.","FUTURA KITCHEN SINKS INDIA PVT","FUTURE BRAKE COMPONENTS","FUTURE CONSUMER  LIMITED","FUTURE CONSUMER ENTERPRISES ( ","FYNSEA LINES & LOGISTICS PVT","G B EQUIPMENT SYSTEMS LIMITED","G G AUTOMOTIVE GEARS PVT LTD","G H INDUCTION INDIA PVT.LTD","G H INDUSTRIES","G K RICKSHOW","G M BIOCHEM PRIVATE LIMITED","G M ENGINEERING PVT. LTD.","G M EXPORTS INDIA","G M MODULAR PRIVATE LIMITED","G M W ENGINEERS PVT LTD","G NINE MODULAR PRIVATE LIMITED","G R TRADERS","G S WIRE NETTING INDUSTRIES","G. B. SPRINGS PVT.LTD","G. K. FOUNDERS PRIVATE LIMITED","G. M. PEN INTERNATIONAL","G.B. INTERNATIONAL","G.K.ENTERPRISES PVT.LTD.","G.M.DALUI & SONS PRIVATE LIMITED","G.R.SALES","GABA CARE PRIVATE LIMITED","GADRE MARINE EXPORT PRIVATE LIMITED","GAEA ENGINEERS & CONTRACTORS PVT. LTD.","GAI AUTO INDUSTRIES","GAIAGEN TECHNOLOGIES PRIVATE LIMITED","GAIT NAURISH INDIA LTD","GAJALI COSMETICS ","GAJANAN PRINT SOLUTIONS","GAJANAND ENGINEERING WORKS","GAJANAND FOODS PVT LTD","GAJINDRA PLASTICS","GAL ALUMINIUM EXTRUSION PRIVATE LIMITED","GALA","GALA GLOBAL PRODUCTS LTD","GALA PRECISION ENGG PVT LTD","GALATA CHEMICALS INDIA PRIVATE LIMITED","GALAXY BEARINGS LIMITED","GALAXY INDUSTRIES","GALAXY MOTORS","GALAXY ROLLERS COMPANY PRIVATE LIMITED","GALAXY SIVTEK PRIVATE LIMITED","GALAXY SURFACTANTS LTD.","GALAXY9 ENTERPRISE","GALENTIC PHARMA INDIA PVT LIMI","GALIO GRAPHICS (REGD)","GALIPOGLU HIDROMAS INDIA MANUFACTURING PRIVATE LIM","GALVA DECORE PARTS PRIVATE LIMITED","GALVANISERS INDIA","GANAPATHY INDUSTRIES","GANDHAR OIL REFINARY INDIA PVT","GANDHI CHEMICALS","GANDHI CORPORATION","GANDHI SPRINGS PVT LTD.","GANESH BENZOPLAST LTD","GANESH ENGG. IND","GANESH GOURI INDUSTRIES","GANESH INDUSTRIES","GANESH POLYCHEM LIMITED","GANGA ENTERPRISE","GANGA PAPERS INDIA LIMITED","GANGA RASAYANIE PVT LTD","GANGAR OPTICIANS PRIVATE LIMITED","GANGES INTERNATIONALE PRIVATE LIMITED","GANGOTRI INORGANIC P .LTD","GANPATI FOOD PROCESSORS","GANPATI INDUSTRIES","GANSONS LIMITED","GARDEN SILK MILLS LTD","GARGI HUTTENES ALBERTUS","GARGI INDUSTRIES","GARIMA GLOBAL PVT.LTD.","GARLICO INDUSTRIES LTD.","GARMENTS GROUP","GARTECH EQUIPMENTS PVT LTD","GARUDA ENGINEERING TECHNOLOGIE","GARUDA POLYFLEX FOODS PRIVATE LIMITED","GARUNA HEALTHCARE PRIVATE LIMITED","GARWARE TECHNICAL FIBRES LIMITED","GASTEK ENGINEERING (P) LTD","GAURAV INDUSTRIES PVT LTD","GAURI ARTS","GAUTAM TECHNOCAST","GAYATRI DYE CHEM","GAYATRI DYES & CHEMICALS","GAYATRI INDUSTRIES","GAYATRI METAL PRODUCTS","GAYATRI PAINTS","GAYATRISHAKTI PAPER AND BOARD","GAZELLE PROMOTIONS","GB KORE ARC PRIVATE LIMITED","GE INDIA INDUSTRIAL PRIVATE LIMITED","GECO TRADING & CORPORATION","GEE BEE FERRO AND POWER PRIVAT","GEECO ENERCON PRIVATE LIMITED","GEEKEN SEATING COLLECTION PVT. LTD.","GEEP INDUSTRIES (I) PRIVATE LIMITED","GEEPAS INTERNATIONAL PVT LTD ","GEETA PACKAGING INDUSTRIES","GEETHA COIR MILLS","GEISSEL INDIA PRIVATE LIMITED","GEM CORPOCHEM PRIVATE LIMITED","GEM PAINTS PRIVATE LIMITED","GEMINI EXPORT ","GEMINI INDUSTRIES","GEMINI INTERNATIONAL PVT LTD","GEMINI POWER HYDRAULICS PRIVATE LIMITED","GEMINY KNITWEARS","GENERAL AUTO ELECTRIC CORPORATION","GENERAL MOTORS TECHNICAL","GENERICA INDIA LIMITED","GENESIS BIOSCIENCES ","GENESIS IMAGING PRIVATE LIMITED","GENESIS SOLUTIONS ( INDIA )","GENETIX BIOTECH ASIA PVT. LTD","GENO PHARMACEUTICALS PVT. LTD.","GENSET INDIA PVT LTD","GENUINE FILTERS AND FABRICS","GENUS ELECTROTECH LIMITED","GEO SOURCE","GEOLIFE AGRITECH INDIA P LTD","GEORG FISCHER PIPING SYSTEMS PRIVATE LIMITED","GEPIL INFRASTRUCTURE PRIVATE L","GEZE INDIA PRIVATE LIMITED","GG CABLES AND WIRES INDIA PRIVATE LIMITED","GG ORGANICS CARE PRIVATE LIMITED","GH INDIA AUTO PARTS PVT LTD","GH NUTRITION","GHARADA CHEMICALS LIMITED","GHARDA CHEMICALS LTD","GHAZIABAD FORGINGS PVT. LTD.","GHAZIABAD ISPAT UDYOG LIMITED","GHCL LIMITED.- VRL","GHIYA EXTRUSIONS PVT.LTD","GIAAN INTERNATIONAL","GINNI FILAMENTS LIMITED","GIRDHARILAL AGARWAL & CO","GIRI TEXTILES PRIVATE LIMITED","GIRIRAJ FOILS PRIVATE LIMITED","GIRIRAJ SPECIALITY PRIVATE LIMITED","GIRIVARYA NON-WOVEN FABRICS PV","GIRNAR ALLOYS PRIVATE LIMITED","GIRNAR FOOD AND BEVERASES PVT LTD","GIRSON EXPO CHEM","GITA REFRACTORIES PVT LTD","GITS FOOD PRODUCTS PRIVATE LIMITED","GK RICKSHAW PRIVATE LIMITED","GK SONS ENGINEERING ENTERPRISES PRIVATE LIMITED","GKD INDIA LIMITED","GLANZ WINDOWS PRIVATE LIMITED","GLASS TEF ENGINEERING","GLASSTECH INDUSTRIES INDIA PRIVATE LIMITED","GLAXOSMITHKLINE CONSUMER HEALT","GLEENAND INDIA","GLITTER METALS PRIVATE LIMITED","GLOBAL 360 SUPPLY CHAIN SOLUTI","GLOBAL ALUMINIUM PVT LTD","GLOBAL BRAND RESOURCES PRIVATE LIMITED","GLOBAL BRASS & ALLOY (INDIA)","GLOBAL CALCIUM PVT LTD","GLOBAL CNC AUTOMATION","GLOBAL COLD CHAIN SOLUTION INDIA PVT LTD","GLOBAL CONVEYOR SYSTEMS PRIVATE LIMITED","GLOBAL CUPS & CONSUMABLES PVT ","GLOBAL DRILLING FLUIDS &CHEMICALS LIMIT","GLOBAL ELECTRODES PVT.LTD.","GLOBAL EXIM DESTINATION","GLOBAL GROCERIES","GLOBAL IMPEX CO","GLOBAL INDUSTRIES","GLOBAL INX","GLOBAL LOGISTICS SOLUTION","GLOBAL MOTION SOLUTIONS PRIVATE LIMITED","GLOBAL PACKAGING","GLOBAL PAINTS","GLOBAL POWERSOURCE (INDIA) PRIVATE LTD","GLOBAL SAGA LESCHACO PVT LTD","GLOBAL TECH (INDIA) PVT LTD","GLOBAL TELE TRADE SHOPPING","GLOBE COTYARN PVT LTD","GLOBE EXPRESS SERVICES","GLOBE SCOTT MOTORS PVT LTD","GLOBE TOOLS INCORPORATION","GLOBELA PHARMA PRIVATE LIMITED","GLOBION INDIA PVT LTD","GLUCO CHEM INDUSTRIES","GMM PFAUDLER LIMITED","GMMCO LTD","GMNC TECHNOLOGIES PVT LTD","GMW PRIVATE LIMITED","GNFC LTD.","GO VEG GO GREEN CLUB PRIVATE LIMITED","GOA ANTIBIOTICS AND PHAR MACEU","GOA ART PRINTERS","GOA PAINTS AND ALLIED PRODUCTS PRIVATE LIMITED","GOC PETROCHEMICALS PRIVATE LIMITED","GODAWARI POWER AND ISPAT LIMITED","GODFREY PHILIPS INDIA LIMITED","GODHAVARI BIO REFINARIES LTD","GODREJ & BOYCE MFG CO LTD","GODREJ AGROVET LIMITED","GODREJ AND BOYCE MANUFACTURING","GODREJ INDUSTRIES LTD","GOEL STEEL COMPANY","GOGIA FRAGRANCES PVT. LTD.","GOKUL AGRO RESOURCES LIMITED","GOKUL OVERSEAS","GOKUL REFOILS & SOLVENT LTD","GOLD COIN PLASTICS","GOLD SEAL AVON POLYMERS PVT LT","GOLD STAR POWDERS PRIVATE LIMITED","GOLDCOIN POLYPLAST","GOLDEN CASHEW PRODUCTS P LTD","GOLDEN DYECHEM","GOLDEN ELECTRONIC CONTROLS INDIA PVT LTD","GOLDENROCK PACKAGING","GOLDI SOLAR PVT LTD.","GOLDI XXXX","GOLDJYOTI POLYMERS LLP","GOLDSUN AUTO PVT LTD","GOMA ENGINEERING PVT. LTD.","GONA INDUSTRIES","GOOD MARK AGENCIES PVT LTD","GOODIE INTERNATIONAL PVT LTD ","GOODLUCK ENGINEERING COMPANY /","GOODRICH CARBOHYDRATES LIMITED","GOODRICH CEREALS","GOODRICH GASKET PRIVATE LIMITED","GOODWIN PUMPS INDIA PVT LTD","GOPAL CONSUMER WORLD","GOPAL CORPORATES LLP","GOPAL KRISHNA BRASS PRODUCTS","GOPAL LIFE SCIENCE UNIT II","GOPALDAS VIRSAMRM AND CO. ","GOPALDAS VISRAM & COMPANY LIMITED","GOPANI ENTERPRISE","GOPANI PRODUCT SYSTEMS","GOPI PAPER MART (I) PVT LTD","GOPICHAND ENTERPRISES","GOPINATH CHEM TECH I LTD","GORADIA INDUSTRIES","GORAN PHARMA PRIVATE LIMITED","GOUTAM TRADING CO.","GOVIND CABLE INDUSTRIES","GOVIND METAL INDUSTRIES","GOVT MEDICAL COLLEGE HOSPITAL","GOYAL KNITFAB PRIVATE LIMITED","GOYAL TECHONOCHEM PVT LTD","GOYAM STEEL INDUSTRIES","GP PETROLEUMS LIMITED","GRACE INFRASTRUCTURE PVT LTD","GRAHAA PRIYA ENTERPRISES","GRAIN MILLING COMPANY LIMITED","GRAINSPAN NUTRIENTS PRIVATE LIMITED","GRAM NIRMAN SAMAJ","GRAND PAINTS AND COATINGS","GRAND POLYCOATS COMPANY PVT LT","GRANULA MASTERBATCHES INDIA PVT LTD","GRANULES INDIA LIMITED","GRAPHITE INDIA LTD","GRASIM INDUSTRIES","GRASIM INDUSTRIES LIMITED,UNIT INDIAN RAYON","GRASPER GLOBAL PVT LTD","GRASSE INTERNATIONAL","GRAUER AND WEIL INDIA LTD","GRAVITA INDIA LIMITED","GRAVITECH INC","GRAVITY CAST P LTD","GRB DAIRY FOODS PVT LTD","GREAT WHITE GLOBAL PVT.LTD","GREATECH TELECOM TECHNOLOGIES PVT. LTD.","GREATOO INDIA PRIVATE LIMITED","GREAVES COTTON LIMITED","GREEN APPLE PHARMA","GREEN CHEF APPLIANCES LTD","GREEN GENE ENVIRO PROTECTION AND INFRASTRUCTURE","GREEN LAB INDUSTRIES PRIVATE LIMITED","GREEN PACKAGING INDUSTRIES PVT. LTD.","GREEN PINE INDUSTRIES","GREENAGE INDUSTRIES","GREENFIELD HI TECH AGROTECHNOLOGIES","GREENFIELD RESOURCES PVT.LTD","GREENGLOBE FUEL SOLUTIONS","GREENHEART FLOORS PRIVATE LIMITED","GREENHECK INDIA PVT LTD","GREENLIGHT PLANET INDIA PRIVATE LIMITED","GREENMAX HEALTHCARE","GREENOVAT ORGANICS PVT LTD","GREENPLY INDUSTRIES LIMITED.","GREETINGS KNIT WEARS","GRIFFIN MEDIQUIP LLP","GRIND MASTER MACHINES PRIVATE LIMITED","GRINDING AND DISPERSION TECHNOLOGIES","GRINDWELL NORTON LTD","GRIP STRAPPING TECHNOLOGIES PV","GROBEST FEEDS CORPORATION INDI","GROUP PHARMACEUTICALS LIMITED","GROVER LIGHT SOURCE PVT. LTD.","GROWING OVERSEAS PVT LTD","GRP AUTO (INDIA) PVT LTD","GRP LIMITED","GS CALTEX INDIA PRIVATE LIMITED","GSE FILTER PRIVATE LIMITED","GSP CROP SCIENCE PVT. LTD.","GTC INDUSTRIES","GTN ENTERPRISES LIMITED","GTN TEXTILES LTD","GTPL BROADBAND PVT. LTD","GTZ(INDIA) PRIVATE LIMITED","GUARANTEE SEEDS PRIVATE LIMITED","GUDDI PLASTCON PVT LTD","GUFIC BIOSCIENCES LIMITED","GUGAN ENGINEERING COMPANY","GUJARAT AGRO INDUSTRIES CORPOR","GUJARAT AGROCHEM PVT. LTD.","GUJARAT ALLOYS CAST PVT.LTD.","GUJARAT ALUMINIUM EXTRUSIONS PVT LTD","GUJARAT AMBUJA EXPORTS LTD","GUJARAT APOLLO INDUSTRIES LTD","GUJARAT COOPERATIVE MILK MARKE","GUJARAT COPPER ALLOYS LTD.","GUJARAT DYESTUFF INDUSTRIES","GUJARAT ENTERPRISE","GUJARAT ENVIRO PROTECTION","GUJARAT FLOTEX PVT LTD","GUJARAT FLUOROCHEMICALS LIMITE","GUJARAT GUARDIAN LTD.","GUJARAT INDUSTRIES POWER CO. L","GUJARAT INSECTICIDES LIMITED","GUJARAT INTRUX LTD","GUJARAT MULTI GAS BASE CHEMICA","GUJARAT NARMADA VALLEY FERTILI","GUJARAT ORGANICS LTD.","GUJARAT OXIDES","GUJARAT PACKAGING INDUSTRIES","GUJARAT PARAFINS PVT LTD","GUJARAT PERSALTS PVT LTD","GUJARAT POLYFILMS PRIVATE LIMITED","GUJARAT PRECISION CAST PVT. LT","GUJARAT PRINT PACK PUBLICATION","GUJARAT STATE FERTILIZERS & CH","GUJARAT TERCE LABORATORIES LTD","GUJARATMITRA (PVT) LIMITED","GULBRANDSEN TECHNOLOGIES INDIA","GULF OIL LUBRICANTS INDIA LTD","GULKAS PHARMA PVT LTD","GUMMI METALL TECHNIK (INDIA) PRIVATE LIMITED","GUMPRO DRILLING FLUIDS PVT LTD","GUMTREE TRAPS PRIVATE LIMITED","GUNJAN PAINTS LTD","GURJAR GRAVURES PVT.LTD","GURU DAVE INDUSTRIES","GURU LAMINATORS","GURU PACCAGIUM","GURU RAJENDRA METALLOYS (I) PV","GURUJI ENTERPRISE","GUSTORA FOODS PVT LTD","GWA AGROTECH AND FERTILIZERS PRIVATE LIMITED","GWALIA SWEETS PVT LTD","GYM CRAFT","H & R JOHNSON (INDIA)","H B CORPORATION","H D ENTERPRISES","H K ENTERPRISE","H P INTERNATIONAL","H.B. FULLER INDIA ADHESIVES PR","H.JASVANTRAO ENINEERING","H.K. PARADISE","H.R. AGENCY","H.SYED GULAB OLD USED CLOTH MERCHANT","H2O BATH FITTING INDUSTRIES","H2O ENGINEERING","HABASIT INDIA PRIVATE LIMITED","HABER WATER TECHNOLOGIES LLP","HAJEE A.P.BAVA CO. CONSTRUCTIO","HAJEE ENTERPRISES","HAJEE TRADERS","HALFEN MOMENT INDIA PRIVATE LIMITED","HALLEYS BLUE STEELS PRIVATE LIMITED","HALOGENS","HAMILTON HOUSEWARES PRIVATE LIMITED","HAMLAI INDUSTRIES PRIVATE LIMITED","HAMMOND POWER SOLUTIONS PRIVATE LIMITED","HANDLOOM CLOTH","HANDLOOM GROUP","HANGER SOLUTIONS PVT. LTD.","HANS REFRATECH","HANSA CHEMICALS","HANSEM BUILDING SYSTEMS INDIA PRIVATE LIMITED","HANSON PAINT & COATING","HANUCHEM LABORATORIES","HAPPYMATE FOODS LIMITED","HARDCASTLE PETROFER PVT LTD","HARDIK PACKAGINGS PRIVATE LIMITED","HARE KRISHNA EXPORTS","HARE KRISHNA INDUSTRIES","HARESH STEEL & PIPES","HARI DARSHAN SEVASHRAM PVT LTD","HARI OM SALES CORPORATION","HARIHAR ORGANICS PVT LTD","HARIND CHEMICAL AND PHARMACEUTICALS PVT LTD","HARIOM INNOVATION AND SALES LL","HARIRAM INDUSTRIES","HARITA-NTI LIMITED","HARITSONS MINTECH PRIVATE LIMITED","HARLEQUIN PAINTS PVT LTD","HARMAN SURGICALS PRIVATE LIMITED","HARMONY ORGANICS PVT LTD","HARRA PACK","HARRA POLYPACK","HARRIS & MENUK CHEMICALS PVT LTD.","HARSH CLEAN DHAN PRIVATE LIMIT","HARSH INDUSTRIES","HARSH KUMAR AND SONS","HARSH MARKETING","HARSHA ABAKUS SOLAR PRIVATE LIMITED","HARSHA ENGINEERS LIMITED","HARSHAD FIXTURES & DISPLAY SYSTEMS","HARTEX RUBBER PVT LTD","HASBRO MARKETING","HASHMITHA ENTERPRISE","HATSUN AGRO PRODUCT LIMITED","HAVELLS INDIA LTD","HAWA VALVES  INDIA PVT LTD ","HAWKINS COOKERS LTD","HAZEL MERCANTILE LTD.","HAZIRA REFRACTORY WORKS PVT LT","HBL POWER SYSTEMS LIMITED","HD FIRE PROTECT PRIVATE LIMITE","HD MICRONS LIMITED","HEALERS ASSOCIATES","HEALTH LINE PRIVATE LIMITED","HEALWELL PHARMACEUTICALS PVT. ","HEAT AND CONTROL (SOUTH ASIA) PRIVATE LIMITED","HEAT TRANSFER EQUIPMENTS PRIVATE LIMITED","HEAVY METAL & TUBES LTD","HEBEI PIPE FITTINGS","HECTOR BEVERAGES PRIVATE LIMITED","HEET STEEL","HELAX HEALTH CARE PVT. LTD.","HELIOS INFRAPRO PRIVATE LIMITED","HELIOS PACKAGING PRIVATE LIMITED","HELIOS PHARMACEUTICALS","HELLMANN WORLDWIDE LOGISTICS I","HELUKABEL INDIA PRIVATE LIMITED","HEM CORPORATION PRIVATE LIMITED","HEM INDUSTRIES","HEM KANT ENTERPRISES PRIVATE LIMITED","HEM METAL INDUSTRIES","HEMA AGENCIES","HEMANI INDUSTRIES LTD.","HEMANT ENTERPRIES","HEMAY INFRASTRUCTURE PRIVATE LIMITED","HEMPEL PAINTS INDIA PVT LTD","HENDO INDUSTRIES","HENKEL ANAND INDIA PRIVATE LIM","HENKEL GROUP","HENNA INDUSTRIES PVT LTD","HERAEUS TECHNOLOGIES INDIA PRIVATE LIMITED","HERALD PUBLICATIONS PVT. LTD","HERAMBH INDUSTRIES","HERANBA INDUSTRIES LTD","HERB AND SHRUB AYURVEDA PRIVATE LIMITED","HERB ELEMENTZ NATURECEUTICALS PRIVATE LIMITED","HERBAL APS PVT. LTD.","HERBAL ISOLATES (P) LTD.","HERBS DE OLIVIA","HERCULES HOIST LIMITED","HERCULES PIGMENTS PVT LTD","HERCULES STRUCTURAL SYSTEMS PR","HERMES GLOBETRADE","HERO ECO TECH LIMITED","HERO PRODUCTS INDIA PRIVATE LIMITED","HESOLT LUBRICANTS PRIVATE LIMITED","HETAL IMPEX","HETERO HEALTHCARE LIMITED","HEUBACH COLOUR PRIVATE LIMITED","HEUBACH TOYO COLOUR PRIVATE LI","HEXAGON MIDCO INDIA PVT LTD","HEXAGON NUTRITION PVT LTD ","HEXXA GEO SYSTEMS INTEGRATORS PRIVATE LIMITED","HI FAB ENGINEERS PRIVATE LIMITED","HI FI INDUSTRIES","HI STYLE PRODUCTS","HI TECH MOULDS","HI TECH SURGICAL SYSTEMS","HI-BOND BEARINGS PVT. LTD.","HICARE SERVICES PVT LTD","HICON PRODUCTS INDIA PVT LTD","HIGGS HEALTHCARE","HIGH PERFORMANCE PRODUCTS INDU","HIGHTECH SINTERED PRODUCTS PVT LTD","HIKAL LTD.","HIL LIMITED","HILDEN PACKAGING MACHINES PVT ","HILITE INDUSTRIES PVT. LTD.","HILLER DECANTERS INDIA PVT LTD","HILTON PHARMACHEM","HILUX AUTO ELECTRIC PVT LTD","HIMACHAL POLYOLEFINS LTD.","HIMALAYA CHEMICALS","HIMALAYA COMMUNICATIONS LIMITED","HIMALAYA MEDITEK PVT LTD","HIMALAYAN HORSE CARE PRODUCTS PRIVATE LIMITED","HIMALAYAN MEDICARE PRIVATE LIMITED","HIMANSHU MACHINE TOOLS","HIMATSINGKA LINENS","HIMEDIA LABORATORIES PVT LTD","HIMGIRI COOLING TOWER","HINA DYE CHEM INDUSTRIES","HIND UDYOG PVT LTD","HINDALCO INDUSTRIES LTD.","HINDUSTAN ABRASIVES","HINDUSTAN ADHESIVES LIMITED","HINDUSTAN AYURVED LTD.","HINDUSTAN BAKELITE CO.","HINDUSTAN CARGO LIMITED       ","HINDUSTAN CHEMICALS COMPANY","HINDUSTAN COCOCOLA BEVERAGES","HINDUSTAN COLAS PRIVATE LTD","HINDUSTAN COMPOSITES LTD","HINDUSTAN EQUIPMENTS PRIVATE LIMITED","HINDUSTAN FOODS LIMITED","HINDUSTAN HARDY SPICER LIMITED","HINDUSTAN INDUSTRIES","HINDUSTAN LABORATORIES","HINDUSTAN MAGNETS AND ELECTRONICS COMPANY","HINDUSTAN OIL DISTRIBUTORS","HINDUSTAN PAPER PRODUCTS","HINDUSTAN PENCILS","HINDUSTAN PHOSPHATES PVT. LTD.","HINDUSTAN PLATINUM PVT LTD","HINDUSTAN UNILEVER LIMITED","HINDUSTHAN BIRI MFG","HINDUSTHAN CHEMICALS COMPANY","HIRA PRINT SOLUTIONS PVT LTD","HI-SHINE INKS PVT LTD","HITACHI ASTEMO BRAKE SYSTEMS INDIA PRIVATE LIMITED","HITECH ABRASIVES LIMITED","HI-TECH ABRASIVES LIMITED","HITECH CORPORATION LIMITED","HI-TECH ELASTOMERS LIMITED","HITECH ELECTROCOMPONENTS PRIVATE LIMITED","HITECH ENGINEERS","HI-TECH FORGINSGS (BANGALORE) PVT. LTD.","HI-TECH INVESTMENT CASTINGS PRIVATE LIMITED","HI-TECH METAL FORMINGS (INDIA)","HI-TECH MOULDS","HITECH PRINT SYSTEMS LIMITED","HITEK FINE CHEMCIALS PVT LTD","HITEMP POLYMERS PVT LTD","HITESH MECHANICALS","HK WENTWORTH (INDIA) PRIVATE LIMITED","HLE ENGINEERS PRIVATE LIMITED","HLE GLASCOAT LIMITED","HLL LIFECARE LTD","HM CLAUSE INDIA PRIVATE LIMITED","HMM INFRA LIMITED","HOFFMANN QUALITY TOOLS INDIA PRIVATE LIMITED","HOGANAS INDIA PRIVATE LIMITED","HOLISOL LOGISTICS PVT LTD","HOLLOWAY CONTAINERS","HOLY FAITH INTERNATIONAL PVT L","HOLY LAND MARKETING PVT LTD","HOME APPLIANCE CO","HOMEMADE BAKERS INDIA LTD","HONAVAR ELECTRODE PVT LTD","HONDA SIEL POWER PRODUCTS LTD","HONEY SUGAR PRODUCT","HONICEL INDIA PRIVATE LIMITED","HONOUR LAB LIMITED","HOOGHLY EXTRUSIONS LIMITED","HORIZON","HORIZON POLYMERS ENGG. PVT.LTD","HORNERXPRESS INDIA PVT. LTD.","HORSE POWER FEED AND SUPPLEMENTS LLP","HOSCH EQUIPMENT INDIA LIMITED","HP ADHESIVES LIMITED","HP COMPOSITES LLP","HP VALVES FITTINGS INDIA PVT.LTD","HPCL MITTAL PIPELINES LIMITED","HPL ADDITIVES LIMITED","HPL ELECTRIC & POWER LTD","HPP INDUSTRIES PVT LTD","HRIDAY CONSULTANCY","HRS PROCESS SYSTEMS LIMITED","HSIL LIMITED","HSM FOODS INTERNATIONAL PVT.LT","HSP TECHNO LOGIES","HTL LTD","HUBER GROUP INDIA PVT. LTD.","HUES AND TINTS","HUGHES & HUGHES CHEM LTD","HUHTAMAKI PPL LIMITED","HUMI AIR SYSTEMS PVT LTD","HUNTSMAN ADVANCED MATERIALS SOLUTIONS PVT LTD","HUNTSMAN ADVANCED(PIDILITE ADHESIVES PVT LTD)","HUNTSMAN INTERNATIONAL ","HY TECH ENGINEERS PRIVATE LIMITED","HYDAC (INDIA) PRIVATE LIMITED","HYDERABAD FOOD PRODUCTS PVT LT","HYDRO CARBONS & CHEMICALS","HYDROCARBON SOLUTIONS (INDIA) PRIVATE LIMITED","HYDRO-CARE ENGINEERS","HYDRODYNE INDIA PVT LTD","HYDRODYNE TEIKOKU (INDIA) PVT. LTD","HYDROKRIMP A.C PRIVATE LIMITED","HYDROLINES - INDIA DHARWAD","HYDROMATERIALS PRIVATE LIMITED","HYDROPACK INDIA PVT. LTD.","HYDROPNEUMATICS PRIVATE LIMITED","HYFLYER INNOVATIONS LLP","HYGIENE INDIA","HYGIENIC RESEARCH INSTITUTE PVT LTD","HYKON INDIA LIMITED","HYLOC HYDROTECHNIC PVT LTD","HYOSUNG CORPORATION INDIA PVT LTD","HYOSUNG INDIA PRIVATE LIMITED","HYPER MARKET SERVICES","HY-TECH FLUID POWER PRIVATE LIMITED","HYVA INDIA PVT LTD","I O L CHEMICALS AND PHARMACEUTICALS LTD","I P INTEGRATED SERVICES PRIVATE LIMITED","I T C LIMITED","I.M.V.INDIA PRIVATE LIMITED","ICA PIDILITE","ICE BURG TECHNOCAST PVT. LTD","ICI HEALTHCARE PVT LTD","ICO CARE","ICONICS ALUPLAST INDIA PRIVATE LIMITED","IDEAL WRAPPERS","IDILITE INDUSTRIES LTD-CORP KEY SURAT SEZ","IDMC LIMITED","IDS COMPOSITES","IFB INDUSTRIES LIMITED","IFF INDIA LTD","IFFCO EBAZAR LIMITED","IFFCO KISAN SANCHAR LTD","IFGL REFRACTORIES LIMITED","IFTEX OIL & CHEMICALS LTD","IGP ENGINEERS PVT LTD","IHEAL PHARMACEUTICAL","IHSEDU AGROCHEM PVT LTD","IIGM PRIVATE LIMITED","IKE ELECTRIC PRIVATE LIMITED","IKTA AROMATICS LTD.","IMAGE INDUSTRIES (INDIA) PRIVATE LIMITED","IMCD INDIA PRIVATE LIMITED","IMERYS CARBONATES INDIA LIMITED","IMERYS STEELCASTING INDIA PVT.","IMEXSU ABRASIVE PRIVATE LIMITE","IMI ABRASIVES PVT. LTD.","IMIS PHARMACEUTICALS P LTD","IMOSYS ENGINEERING COMPANY PRIVATE LTD","IMPACT INTERIOR SYSTEMS","IMPEL SERVICES PVT LTD","IMPERIAL AUTO INDUSTRIES LTD","IMPERIAL COLOURS","IMPEX ENGINEERING & EQUIPMENT ","IMPRESS APPAREL MACHINES PRIVATE LIMITED","IMPULSE CHEMICAL & SURFOCANT","IMPULSE MARKETING","INAMILLAKHAN HIMMATKHAN ","INAPEX PVT LTD","INARCO PRIVATE LIMITED.-BVR","INCO MECHEL PVT LTD","INDAGRO FOODS PRIVATE LTD","INDAPUR DAIRY AND MILK PRODUCT","INDAUTO FILTERS","INDCOAT SHOE COMPONENTS LTD.","INDEX SYSTEM","INDIA ABROAD SERVICES","INDIA BIBLE LITERATURE TRUST","INDIA GLYCOLS LIMITED","INDIA HANDLOOM","INDIA NETS","INDIA PESTICIDES LIMITED","INDIA PISTONS LTD","INDIA SEAH PRECISION METAL PVT LTD.","INDIA VALVES & AUTOMATION","INDIAN ADDITIVES LIMITED","INDIAN CABLES & ELECTRICALS PR","INDIAN CHEMICALS CORPORATION","INDIAN CHEMICALS INDUSTRIES","INDIAN EXTRUSIONS PVT LTD","INDIAN FARMERS FERTILISER COOP","INDIAN HERBS SPECIALITIES PVT ","INDIAN INSULATION & ENGINEERING","INDIAN OIL CORPORATION LTD","INDIAN PRODUCTS (P) LTD","INDIAN SILKS","INDIAN STEEL CORPORATION LIMIT","INDIAN TELEPHONE & ELECTRIC CO","INDIAN TONERS AND DEVELOPERS L","INDIAN VALVE PVT LTD","INDIANA INTERNATIONAL CORP. FL","INDICA INDUSTRIES PVT LTD","INDIES GLOBAL FOODS PVT. LTD.","INDIGENOUS RET","INDIGO PAINTS PVT LTD","INDITECH VALVES PRIVATE LIMITED","INDKUS BIOTECH INDIA","INDKUS NEXA","INDO AMINES LIMITED","INDO ARYAN BEVERAGES PRIVATE LIMITED","INDO BORAX & CHEMICALS LTD","INDO COLCHEM LTD","INDO COUNT INDUSTRIES LTD","INDO GERMAN BREAKS PVT LTD","INDO GLOBAL CHEMICALS","INDO HIMALIYAN HERBS INC","INDO MEDIX","INDO PHYTO CHEMICALS PVT LTD.","INDO RAMA SYNTHETICS (INDIA) L","INDO REAGENS POLYMER ADDITIVES PRIVATE LIMITED","INDO SWISS CHEMICALS LIMITED","INDO VACUUM TECHNOLOGIES PVT LTD.","INDO-AMERICAN HYBRID SEEDS","INDOBLAST EXIM PRIVATE LIMITED","INDOBLAST INDUSTRIES","INDOCEM COLOURS PRIVATE LIMITED","INDO-CHEM LABORATORIES","INDOCHEM TECHNOLOGIES","INDOCO REMEDIES LTD ","INDOFIL INDUSTRIES LIMITED","INDOKEM LIMITED","INDOKOTE INDUSTRIES PVT LTD","INDORAMA INDIA PRIVATE LIMITED","INDORAMA VENTURES OXIDES ANKLESHWAR PRIVATE LIMITE","INDORE BIOTECH INPUTS AND RESEARCH PRIVATE LIMITED","INDORE COMPOSITE PVT LTD","INDORE GEL PRIVATE LIMITED","INDOSHELL MOULD LIMITED","INDRAS AGENCIES PVT LTD","INDRAYANI SALES PVT. LTD","INDU IONPURE INDIA PRIVATE LIMITED","INDU SPORTS","INDUBEN KHAKHARAWALA AND CO.","INDUCTOTHERM INDIA PVT. LTD","INDUSTRIAL AIDS","INDUSTRIAL ASSOCIATE","INDUSTRIAL ATOMIZER CO","INDUSTRIAL BOILERS LIMITED","INDUSTRIAL COMMERCIAL CORPORAT","INDUSTRIAL DEVICES (INDIA)PVT ","INDUSTRIAL FABRICATORS","INDUSTRIAL FORGE & ENGINEERING","INDUSTRIAL GLASS COMPANY","INDUSTRIAL PRODUCTS MFG. COMPANY","INDUSTRIAL RESINS & CHEMICALS","INDUSTRIAL SOLVENTS & CHEMICAL","INDUSTRY CHOICE","INDWELL PHARMA","INEOS COMPOSITES INDIA LLP","INFICOLD INDIA PRIVATE LIMITED","INFINA","INFINITA BIOTECH PRIVATE LIMITED","INFINITE AQUA PRIVATE LIMITED","INFINITY ADVERTISING SERVICES PVT LTD","INFINITY LOGISTICS SOLUTION","INFINITY SWITCH TECHNOLOGIES P","INFLUX HEALTHCARE LTD ","INFLUX HEALTHTECH PRIVATE LIMITED","INFOPOWER TECHNOLOGIES LTD.","INGERSOLL RAND INDIA LTD","INGERSOLL-RAND TECHNOLOGIES AND SERVICES PVT LTD","INGLOBE EXPORTS","INGRIDIA LLP","INJECT CARE PARENTERALS PVT LT","INK POINT","INLOGSYS TECHNO PRIVATE LIMITED","INNO-NU CHEM PVT LTD","INNOREX PHARMACEUTICALS PVT LT","INNOVATIVE AND INNOVATORS PRIVATE LIMITED","INNOVATIVE ENG. PRODUCTS PVT.","INNOVATIVE FLEXOTECH PVT LTD","INNOVATIVE FOODS PRODUCTS","INNOVATIVE TYRES & TUBES LTD","INNOVISION HYGIENE","INOVA CAST P. LTD.","INOVANCE TECHNOLOGY PRIVATE LIMITED","INOVATIVE CAD CHEM SERVICES","INOVATIVE TECHNOCAST P. LTD","INOVENTIVE FILAMENTS PRIVATE LIMITED","INOX AIR PRODUCTS PVT LTD","INOX INDIA LTD","INSAT PHARMA","INSECTICIDES (INDIA) LIMITED","INSPIRON ENGIEERING PVT LTD","INSTA EXHIBITIONS PVT LTD","INSTA POWER CONTROL AND EQUIPMENTS PRIVATE LIMITED","INTECH INDIA PRIVATE LIMITED","INTECH ORGANICS LTD","INTEG ELECTRONICS","INTEGRAL PROCESS CONTROLS INDI","INTEGRATED COATING & SEED TECHNOLOGY INDIA PVT LTD","INTEGRATED FIRE PROTECTION PVT","INTEGRATED LABORATORIES (P) LTD","INTEGRATED PESTICIDES PVT.LTD","INTEGRATED TRADING COMPANY","INTEGRO ENGINEERS PVT LTD","INTEK TAPES PVT LTD","INTELECON PVT.LTD.","INTELLOZENE","INTER FILMS INDIA PVT. LTD.","INTER MARKET (INDIA) PVT LTD","INTER STEELS","INTER TRADE LINK'S","INTERCLEAN SOLUTIONS PRIVATE LIMITED","INTERCRAFT TRADING PVT LTD","INTERGROW BRANDS PRIVATE LIMITED","INTERMEDIATES & CHEMICALS","INTERMETAL ENGINEERS INDIA PVT LTD","INTERNATIONAL COMBUSTION INDIA LIMITED","INTERNATIONAL FLAVOURS","INTERNATIONAL INDUSTRIAL SPRIN","INTERNATIONAL PAPER APPM LIMIT","INTERPLASTICA PRIVATE LIMITED","INTERPLEX INDIA PVT.LTD.","INTERPRO BULK PACKAGING PRIVATE LIMITED","INTEX SOLUTIONS","INTEX TECHNOLOGIES INDIA LIMITED","INTHREE ACCESS SERVICES PRIVATE LIMITED","INTIME FIRE APPLIANCES PVT LTD","INTOLCAST PRIVATE LTD","INTOUCH INDUSTRIES","INTRA ELECTRONICS","INTRA LIFE","INTRACIN PHARMACEUTICALS PVT.L","INTREDEN EXIM PRIVATE LIMITED","INTRI CAST P LTD","INTRON LIFE SCIENCES","INVENTA CLEANTEC PVT.LTD.","INVENTAA LED LIGHTS PRIVATE LIMITED","INVESTMENT & PRECISION CASTING","INWARD BILL FOR CASHEW","INYANTRA TECHNOLOGIES PVT LTD","IP CLEANING INDIA PVT LTD","IP SOFTCOM (INDIA ) PVT LTD.","IPC HEALTHCARE PRIVATE LIMITED","IPCA LABORATORIES LTD","IPM ENGINEERING LIMITED","IPSCOM RETURNABLE PRIVATE LIMITED","IRANI ABRASIVES","I-RETAILERS PRIVATE LIMITED","IRICH PRIVATE LIMITED","IRIS LIFE SOLUTIONS PVT LTD","IRONBUILDSYSTEMS PRIVATE LIMITED","IRRH SPECIALTY CHEMICALS INDIA","ISAGRO (ASIA) AGROCHEMICALS PRIVATE LIMITED","ISBIR MEWAR BULK BAG PRIVATE LIMITED","ISCON SURGICALS LIMITED","ISHA ENGINEERING & CO","ISHAN DYES & CHEMICAL LTD","ISHOO CHEMICALS","ISIT OFFICE SPACE SOLUTIONS","ISO THERM PUF PANEL PRIVATE LIMITED","ISOE PRINTPACK INDUSTRIES PVT LTD","ISPA PHARMACEUTICALS (P) LTD.","ITALMATCH CHEMICALS INDIA PRIVATE LIMITED","ITD CEMENTATION INDIA LIMITED","ITDL IMAGETEC LIMITED","ITEC MEASURES PVT. LTD.","I-TECH PLAST (INDIA) PVT LTD","ITRON INDIA PVT LTD","ITW INDIA PRIVATE LIMITED","IVAX PAPER CHEMICALS LTD","IVP LTD","IWL INDIA LIMITED","J B ASSOCIATES","J B FRAGRANCES AND FLAVOURS","J B MANUFACTURING CO","J C GRAPHICS PVT LIMITED","J D TRADERS","J G HOSIERY PRIVATE LIMITED","J J PLASTALLOY PVT.LTD.","J K CHEMICAL","J K ENGINEERING & TECHNOLOGY","J K FILES INDIA LTD","J K INDUSTRIES","J K MALT PRODUCTS PVT LTD","J K PAPER LIMITED","J K PLAST","J K TALABOT LIMITED","J K TRADING CO","J M B INDUSTRIES","J M INDUSTRIES","J M STEEL & ALLOYS","J N ROBOTIC AUTOMATION PVT LTD","J P ENTERPRISES","J P EXTRUSIONTECH LTD","J P POLYMERS PVT LTD","J R TAPE PRODUCTS PRIVATE LIMITED","J S ENTERPRISE","J S PARTNERS","J V EXPORTS","J. D. SPECIALITY CHEMICALS","J.B. SPARK","J.D.S. CASTING PVT.LTD.","J.J GANDHI CHEMICALS PVT LTD","J.J TRADERS","J.J. PHARMACEUTICALS","J.K. ENGINEERING CORPORATION","J.K. FENNER (INDIA) LTD","J.K. MEDICAL SYSTEMS PVT LTD","J.M. HOSIERY & COMPANY LIMITED","J.S.AUTO CAST FOUNDRY INDIA (P) LTD","JAB EQUIPMENTS PRIVATE LIMITED","JACKGON CO.","JACKSON CHEMICAL INDUSTRIES","JACQUARD FABRICS INDIA PVT. LTD.","JADAVJI & SONS","JADE ELEVATOR COMPONENT","JADHAO LAYLAND PRIVATE LIMITED","JAFERBHOY SALEBHOY & CO","JAG RATTAN DAAN SINGH & CO.","JAGA","JAGAN AUTOMOTIVES","JAGANLAMP S AUTOMOTIVES PRIVATE LIMITED","JAGANNATH EXTRUSION INDIA LTD.","JAGANNATH POLYMERS PVT. LTD.","JAGANNATH TEXTILE COMPANY LIMI","JAGDISH BRASS PRODUCTS","JAGDISH PRECISION CAST PVT.LTD","JAGDISH TECHNOCAST PVT LTD","JAGLANKS INDUSTRIES","JAGMOHAN PLA MACH PRIVATE LIMITED","JAI AGENCIES","JAI AUTO PVT LTD","JAI BALAJI CONTROL GEARS PRIVATE LIMITED","JAI BHAWANI INDUSTRIES","JAI INGREDIENTS PRIVATE LIMITE","JAI SHREE BALAJI INDUSTRIES","JAIBAN ORGANICS","JAIDEEP INDIA PRIVATE LIMITED - UNIT - 2","JAIDEEP PLASTICS","JAIKO INDUSTRIES","JAIN ELECTROPLAST PVT. LTD.","JAIN FARM FRESH FOODS LIMITED","JAIN INDUSTRIES","JAINSON CABLES INDIA PVT LTD","JAINSONS INTERNATIONAL","JAIPUR GLASS & POTTERIES ( A UNIT OF VIJAY SOLVEX ","JAIRAM STRAP PRIVATE LIMITED","JAISHNI PACKS (P) LIMITED","JAJOO HYGIENE PRIVATE LIMITED","JAJOO SURGICAL PVT.LTD.","JAKAP METIND PVT LTD","JAKSON ENGINEERS LIMITED","JAL EXTRUSION PRIVATE LIMITED","JALAK METAL INDUSTRIES","JALAN WIRE PVT LTD","JALARAM APPLIANCES POLYMERS","JALARAM INDUSTRIES","JALARAM PLASTIC INDUSTRIES","JALARAM POLYMERS PRIVATE LIMIT","JAMES WALKER INMARCO INDUSTRIES PRIVATE LIMITED","JAMNA AUTO INDUSTRIES LIMITED","JAMNAGAR METAL INDUSTRIES","JAN MARKETING","JANA & CO.","JANATHA AGRO PRODUCTS","JANATICS","JANATICS INDIA PVT LTD","JANGRA ENGG. WORKS","JAQUAR AND COMPANY PRIVATE LIM","JAS FORWARDING WORLDWIDE PRIVA","JASCO PAPER PRODUCTS","JASH ENGINEERING LTD SEZ UNIT","JAY AMBE MINERALS","JAY BHARAT MARUTI LTD","JAY CHEMICAL INDUSTRIES LIMITE","JAY CHIKKI AND SNACKS PRIVATE ","JAY DINESH CHEMICALS","JAY ENGINEERING INDUSTRY","JAY EXTRUSION","JAY FASTENER","JAY INSTRUMENTS & SYSTEMS PVT ","JAY INTERNATIONAL","JAY JALARAM INDUSTRIES","JAY K. FRP PRIVATE LIMITED","JAY METAL TECH","JAY PLAST","JAY PLASTIC COMPANY","JAY RENEWABLE ENERGY PVT LTD","JAY SWITCHES INDIA PVT LTD","JAY SYNTEX","JAY WATER MANAGEMENT PVT LTD","JAY WOOD INDUSTRY","JAYACHANDRAN ALLOYS PVT LTD","JAYALAKSHMI POLY PACKS PVT LTD","JAYAM INDUSTRIES","JAYANT AGRO ORGANICS LTD","JAYANT PRINTERY LLP","JAYANTILAL PREMCHAND","JAYASHREE ENTERPRISES","JAYASHREE POLYMERS PRIVATE LIMITED","JAYASHREE TUBESTECH COMPONENT ","JAYCO SAFETY PRODUCTS PVT LTD","JAYEM TRADE PRIVATE LIMITED","JAYESH TRADEX PVT. LTD.","JAYGARMENTS INDIA PRIVATE LIMITED","JAYHIND POLYMERS","JAYKAL EXPORTS PRIVATE LIMITED","JAYPEE INDIA LTD","JAYPEE MARKETING A UNIT OF JAYPEE WOOLLEN","JAYPEE PROJECTS LIMITED","JAYSHREE AROMATICS PVT. LTD.","JAYSHREE ENTERPRISE","JAYSWAL NECO INDUSTRISE LTD","JAYSYNTH DYESTUFF INDIA LTD","JAYVEER CHEMICAL","JBA CONCRETE SOLOUTIONS PVT LT","JBL SAKS PVT LTD","JBM AUTO LTD","JBM AUTO SYSTEM PVT LTD","JBM INDUSTRIES LTD","JBS PAC & CONTROL","JC VALVULAS INDIA PRIVATE LIMITED","JDH CORPORATION","JDS CASTING PVT. LTD.","JEAN MUELLER INDIA PRIVATE LIMITED","JECIEM ORGANO CHEMICALS P LTD","JEENA & COMPANY","JEEVEE ENTERPRISES","JEEYA METAL","JEF TECHNO SOLUTIONS PVT LTD","JELL PHARMACEUTICAL PVT. LTD.","JENDAMARK INDIA PRIVATE LIMITED","JENSON & NICHOLSON PAINTS","JESONS INDUSTRIES","JESONS TECHNO POLYMERS LLP","JESPCO","JET TECH PRIVATE LIMITED","JEWEL PACKAGING PVT. LTD.","JEWEL PAPERS PVT. LTD.","JFK TRANSPORTERS PRIVATE LIMITED","JHINAL INDUSTRIAL CORPORATION","JIGAR INDUSTRIES","JIMCAP ELECTRONICS PRIVATE LIMITED","JIN MATA FOOD PROCESSORS LLP","JINDAL FITTINGS LIMITED","JINDAL HYDRO PROJECTS INC","JINDAL POLY FILMS LIMITED","JINDAL SAW LTD","JINDAL STAINLESS STEELWAY LTD","JINDAL WORLDWIDE LTD","JINDO CHEMICAL SOLUTIONS PVT.LTD.","JITSAN ENTERPRISES","JIVO WELLNESS PVT. LTD.","JIVRAJ TEA LTD","JIYA ENGINEERS","JJ RETL FIXTURES PRIVATE LIM","JJD ENTERPRISES","JK","JK AGRI GENETICS LIMITED","JK ELECTRIC ENGINEERS PRIVATE LIMITED","JK ENGINEERING & TECHNOLOGY","JKC GENERAL TRADING COMPANY","JKEW FORGINGS LTD","JKM FERROTECH LIMITED","JKW CHEMICALS PRIVATE LIMITED","JMA FOOD PRODUCTS PVT LTD","JMJ FOODLINK","JMT AUTO LIMITED","JMT INDIA INC","JMT STEEL","JOGLEKAR REFRACTORIES PVT .LTD","JOHNSON & JOHNSON PVT LTD","JOHNSON CONTROLS-HITACHI AIR CONDITIONING INDIA LI","JOHNSON LIFTS PRIVATE LIMITED","JOHNSON MATTHEY CHEMICALS IND ","JOIN LEADER CHEMTECH PVT LTD","JONSON INK","JOSTS ENGINEERING COMPANY LIMITED","JOTUN INDIA PVT LTD","JOVE SYNTHOCHEM PRIVATE LIMITE","JOYO PLASTICS","JOYRATH PROJECTS PVT LTD","JPB CHEMICAL INDUSTRIES .PVT.L","JPD PRECISION FASTENERS PVT LTD","JPS FASHIONS PVT.LTD.","JR POLYMERS","JRS PHARMA & GUJARAT MICROWAX","JSG INNOTECH PRIVATE LIMITED","JSL LIFESTYLE LTD","JST INDUSTRIES","JSW ENERGY LIMITED","JSW PAINTS PRIVATE LIMITED","JSW SEVERFEILDS STRUCTURES LTD","JSW STEEL COATED PRODUCTS LTD","JSW VALLABH TINPLATE PVT. LTD","JU AGRI SCIENCES PRIVATE LIMITED","JUBILANT AGRI AND CONSUMER PRODUCTS LTD.","JUBILANT CONSUMER PVT LTD","JUBILANT GENERTICS LIMITED","JUBILANT INGREVIA LIMITED","JUBILANT LIFE SCIENCES LIMITED","JUPITAR RUBBER PVT LTD","JUPITER ENGINEERING CO.","JUPITER LAMINATORS PRIVATE LIMITED","JUPITER LOGISTICS PVT LTD","JUPITER PETROTEC PRODUCTS","JUPITER RUBBER (P) LIMITED","JURCHEN TECHNOLOGY ( INDIA ) PVT LTD","JUWI INDIA","JV HEALTHCARE","JWL COLD STORE PVT.LTD.","JYOTI CAPSULATIONS PRIVATE LIMITED","JYOTI CHEMICALS","JYOTI CNC AUTOMATION PRIVATE L","JYOTI DISPLAY PVT LTD","JYOTI TRANSPET PRIVATE LIMITED","JYOTIRLING ENTERPRISES","K ASHOK AND CO","K B AJMERA & CO","K BACHARAM AND CO","K C HEALTHCARE  ","K K NAG PRIVATE LIMITED","K M INDUSTRIES","K P CHEMICAL","K P GRANITE WORKS","K P R MILL LIMITED","K PATEL INTERNATIONAL","K R M ELECTRICALS","K S AGRO CHEMICALS","K S SURGICAL PRIVATE LIMITED","K T INTERNATIONAL","K V AERO CHEM PVT LTD","K V AROMATICS PRIVATE LIMITED","K. G. AUTO INDUSTRIES","K.B. POLYCHEM (INDIA) PRIVATE ","K.G. ENTERPRISES","K.K.INDIA PETROLEUM SPECIALITIES PVT LTD.","K.P.LOGISTIC","K.PATEL CHEMO PHARMA PVT. LTD.","K.PATEL DYECHEM INDUSTRIES PVT","K.PATEL GROUP","K.PATEL INTERNATIONAL","K.PATEL PHYTO EXTRACTIONS PVT ","K.S. RUBBER INDUSTRIES","K10 CABLES","KABIR FOODS PVT LTD","KABRA EXTRUSIONTECHNIK LIMITED","KABSONS GAS EQUIPMENT PRIVATE LIMITED","KADAM EXPORT PVT LTD","KAHAN PACKAGING","KAILASH CHEMICALS","KAIRA DISTRICT CO-OPERATIVE MILK PRODUCERS UNION L","KAIRALI AYURVEDIC PRODUCTS PRIVATE LIMITED","KAISER APPLIANCES","KAIYUAN WELDING & CUTTING AUTO","KAIZEN FINE CHEMICALS","KAJAL DULHAN MEHANDI CENTRE","KAJARIA PIPES","KAKARLA MOULDS AND DIES PRIVATE LIMITED","KALAPI PRINTING PRESS","KALAS SEEDS PRIVATE LIMITED","KALASANSKRUTI IMPORT EXPORT PRIVATE LIMITED","KALPA POWER PRIVATE LIMITED","KALPAR ENGINEERS PVT. LTD.","KALPATARU POWER TRANSMISSION L","KALPATHARU AGENCIES","KALPATHRU CHEMICALS","KALPENA INDUSTRIES LTD.","KALPSUTRA GUJARAT - HLL","KALSON HYDROMATIC MACHINE TOOL","KALYANI ELECTRONICS","KALYANI POLYMERS PVT LTD","KAM AVIDA ENVIRO ENGINEERS PRIVATE LIMITED","KAM FASTNERS","KAMAKSHI FLEXIPRINTS PVT. LTD","KAMAL AUTO CORP","KAMAL AUTOMOBILES","KAMAL SHAFT PRIVATE LIMITED","KAMALA GROUP","KAMANI FOODS PRIVATE LIMITED","KAMDHENU PESTICIDES","KAMLAAMRUT PHARMACEUTICAL LLP","KAMLESH METAL INDIA ","KAMSONS CHEMICALS PRIVATE LIMITED","KAMSONS POLYMERS PVT LTD","KAMTRESS AUTOMATION SYSTEMS P","KANAD CHEMICLAS PVT LTD","KANADIA FYR FYTER PVT LTD","KANAM INDUSTRIES","KANAM LATEX INDUSTRIES PVT LTD","KANDUI INDUSTRIES PVT LTD","KANERI ENTERPRISES","KANERIA PLAST PRIVATE LIMITED","KANGARU POLYMERS PRIVATE LIMITED","KANGAYAM COIR CLUSTER PRIVATE LIMITED","KANHA PLASTICS PVT LTD","KANIKA DIGITAL PRINTS PRIVATE","KANKRIYA AGENCIES PVT LTD","KANKRIYA ENTERPRISES PVT LTD.","KANKU ENTERPRISE","KANMECH PRIVATE LIMITED","KANORIA CHEMICALS & INDUSTRIES","KANSAI NEROLAC PAINTS LTD.","KANSARA SCIENTIFIC OPC PRIVATE LIMITED","KANSONS OVERSEAS LIMITED","KANTA ENTERPRISES PVT.LTD.","KANTI BEVERAGES PRIVATE LIMITE","KANTI SWEETS","KANYAKA PARAMESHWARI ENGINEERI","KAOLIN TECHNIQUES PVT.LTD","KAPCI COATINGS INIDA PRIVATE LIMITED","KAPEEL FOUNDERS","KAPILA HEALTH CARE","KAPILANSH DHATU UDYOG LTD","KAPOOR COTSYAN","KAPOOR HERBAL PRODUCTS","KAPOOR IMAGING PRIVATE LIMITED","KAPSUN RESOURCES CORPORATION","KARA EXPRESS PRIVATE LIMITED","KARAD PROJECTS & MOTORS LTD.","KARAM INDUSTRIES","KARAM SAFETY PRIVATE LIMITED","KARAMTARA ENGINEERING PVT LTD","KARANDIKAR CASHEEL PVT LTD ","KARANI TECHTEX PRINT PRIVATE LIMITED","KARIWALA INDUSTRIES LIMITED","KARJEN INDUSTRIES","KARNANI PHARMACEAUTICALS PVT LTD","KARNATAKA ANTIBIOTICS & PHARMA","KARNATAKA AROMAS","KARNATAKA CO OPERATIVE MILK  PRODUCERS FDRN LTD","KARNATAKA CONVEYORS & SYSTEM PVT LTD","KARNATAKA STATE DRUGSLOGISTICS & WAREHOUSING SOCIE","KAROMAS AROMATICS ","KARSHATI","KARTHIK CONVERTER","KARTIKAYS INTERNATIONAL","KARUR BALE BOOKING (MOSQUITO N","KASCO SPECIAL STEELS","KASH MEDICARE PVT LTD","KASHMIR CHEMICALS","KASTURI INDUSTRIES","KASTWEL FOUNDRIES","KASUMA AUTO ENG.PVT LTD.","KATS ORGANICS PRIVATE LIMITED","KAUSHIK INDUSTRIES","KAVERI POLYMERS PVT.LTD","KAVYA INTERNATIONAL","KAY INTERNATIONAL PRIVATE LIMITED","KAY KAY INDUSTRIES","KAY PEE INDUSTRIES","KAY PIGMENTS","KAY SONS INDIA PVT LTD","KAYNES TECHNOLOGY INDIA PRIVATE LIMITED","KAYZER FLEXIBLES PVT. LTD.","KDAC CHEM PVT LTD","KDC ENTERPRISES","KE TECHNICAL TEXTILES PVT LTD","KEC INTERNATIONAL LTD","KEEP SAFE","KEERTHI BANGALORE PVT LTD","KEHEMS TECHNOLOGIES PRIVATE LIMITED","KEI INDUSTRIES LIMITED","KEJRIWAL BEE CARE INDIA PRIVATE LIMITED","KEMCO CORPORATION","KEMIN INDUSTRIES","KEMIT CHEMICALS PVT. LTD","KEMSTAR ASSOCIATES","KEMSTAR PROCESS SOLUTIONS","KEN LIFESTYLES PRIVATE LIMITED","KEN POLYMERS","KENT RO SYSTEM LTD ","KERAKOLL INDIA PRIVATE LIMITED","KERALA AYURVEDA LTD","KERALA COIRON INDUSTRIES","KERN-LIEBERS INDIA PRIVATE LIMITED","KERRY INDEV LOGISTICS PRIVATE LIMITED","KERRY INGREDIENTS I PVT LTD","KESARIA RUBBER INDUSTRIES PRIVATE LIMITED","KESHAR EMULSION PVT LTD","KESHAV HICHEM PVT LTD","KESHAV OIL","KESHAVA MEDI DEVICES PVT. LTD.","KETAN BRASS INDUSTRIES","KETAN GROUP","KETSAAL REATAILS LLP","KETTY APPARELS INDIA PVT. LTD.","KEVA FRAGRANCES PVT LTD","KEVA INDUSTRIES","KEVAL EXPORTS PVT LTD","KEY PRODUCTS PVT LTD","KEYA FOODS INTERNATIONAL PVT.LTD","K-FLEX INDIA PRIVATE LIMITED","KGN LIGHTS","KGR ENTERPRISES","KHADI NATURAL HEALTHCARE","KHANNA POLYRIB PVT LTD","KHARAGPUR METAL REFORMING INDU","KHEMKA REFRACTORIES PVT LTD","KHERA CHEMICAL INDUSTRIES","KHERANI PAPER MILLS PVT LTD","KHIMJI FLOW EQUIPMENTS (P) LTD","KHODIYAR CARE","KHOSLA PROFIL PRIVATE LTD","KHS MACHINERY PVT LTD","KHUSHBOO MASALA","KHUSIVA MART LLP","KHYATI ADVISORY SERVICE LTD ","KI OILFIELD CHEMICALS PRIVATE ","KIDS ZONE ENTERPRISES","KIE ENGINEERING PVT LTD","KILN & MACHINERIES","KIMBERLY APPAREL PVT LTD","KIMIA BIOSCIENCES LTD","KIMO ELECTRONICS PVT LTD IPTDINVISION","KIMPLAS PIPING SYSTEMS LIMITED","KINETIC POLYMERS","KING METAL WORKS","KINGFA SCIENCE & TECHNOLOGY (INDIA) LIMITED","KINJAL CHEMICALS","KINTETSU WORLD EXPRESS (INDIA) PRIVATE LIMITED","KIPRA ELECTRICALS PVT. LTD.","KIRAN INFRA TECH","KIRIT BROTHERS","KIRLOSKAR EBARA PUMPS LIMITED","KIRON HYDRAULIC NEEDS PVT.LTD","KIRON SERVEPRENEURS PVT LTD","KISAAN ENGINEERING WORKS PVT L","KISAN MOULDING LTD","KISAN PIPES & PROFILES PVT LTD","KISANKRAFT LIMITED","KISANKRAFT MACHINE TOOLS PVT.","KISHAN AUTOPARTS PVT LTD.","KISHAN MARINE SERVICES LLP","KISHORE BATTERY CORPORATION","KISHORE PHARMA PRODUCTS PVT LTD","KITCHEN GADGET","KITCHEN KRAFTS INDUSTRIES","KITCHEN XPRESS OVERSEAS LTD","KITEC INDUSTRIES (I) PVT. LTD.","KITTY INDUSTRIES PVT LTD","KIWIS PROMO PRODUCT","KJT INDUSTRIES","KK HOUSEHOLD PRIVATE LIMITED","KK INDIA PETROLEUM SPECIALITIES PVT LTD","KK KOMPOUNDING TECH GIANT LIMITED","KKALPANA INDUSTRIES INDIA LTD.","KKROWTEN INDIA ENTERPRISES PVT LTD","KLASSIC LABELS","KLAUS UNION ENGINEERING INDIA PRIVATE LIMITED","KLAXON SYSTEMS & SOFTWARE PVT ","KLEIBERIT ADHESIVES I PVT LTD","KLEMMEN ENGG CORPORATION","KLIPCO PVT LTD","KLJ ORGANIC LTD","KLJ PLASTICIZERS LTD ","KLJ RESOURCES LIMITED","KLM LABORATORIES PRIVATE LIMITED","KLOECKNER DESMA MACHINERY PVT ","K-LON TENKO POLYMERS PVT LTD","KLUBER LUBRICATION INDIA PVT LTD","KLYDE TECHNIK","KMS MANUFACTURING COMPANY","KMV PROJECTS LTD","KNACK PACKAGING PVT LTD","KNACK POLYMERS","KNACK TECHNOPACK","KNAUF GYPSUM INDIA PRIVATE LIMITED","KNOLL CHEM INDUSTRIES","KNOLL HEALTHCARE PRIVATE LTD","KNOWELL CORPORATION","KNOWELL ENTERPRISES PRIVATE LIMITED","KOBELCO COMPRESSORS INDIA PVT ","KOBELCO CONSTRUCTION EQUIPMENT","KOBELCO WELDING INDIA PRIVATE LIMITED","KOCH CHEMICAL TECHNOLOGY GROUP","KODIXODEL PRIVATE LIMITED","KOEL COLOURS PVT LTD","KOHINOOR DEL CHEM","KOHINOOR POLYMER PRODUCTS","KOHINOOR ROPES PRIVATE LIMITED","KOHLER INDIA CORPORATION PVT L","KOHLER POWER INDIA PVT. LTD.","KOHLI MOTOR CO","KOHNLE HEAT TREATING SYSTEMS PRIVATE LIMITED","KOKOPELLI AGRO LLP","KOKUYO CAMLIN LTD","KOLAR CHIKKABALLPUR MILK UNION LIMITED- MEGA  DAIR","KOMAL TRADING CORPORATION","KOMARLA FEEDS AND FOODS PVT LI","KOMODO","KONDAPALLY FORGINGS PVT LTD","KONIKA INDUSTRIES","KONIKA INTIMA PRIVATE LIMITED","KONITA INDUSTRIES PRIVATE LIMITED","KONKAN SPECIALITY POLYPRODUCTS","KORES PRINTER TECHNOLOGY PVT L","KORES( INDIA) LIMITED","KORIN OPTOELECTRONICS LLP","KORRUN INDIA PRIVATE LIMITED","KOSAN INDUSTRIES PVT. LTD.","KOSO INDIA PRIVATE LIMITED","KOSOL ENERGIE PVT LTD","KOTHARI AGRITECH PRIVATE LIMITED ","KOTHARI FERMENTATION & BIOCHEM","KOTHARI METALS LIMITED","KOTHARI PETROCHEMICALS LTD","KOTHARI UNIFORMS PVT. LTD.","KOVAX ABRASIVES SOUTH ASIA PRIVATE LIMITED","KOWA INDIA PRIVATE LIMITED","KOYAS PERFUMERY WORKS","KPCL INDUSTRIES","KPL INTERNATIONAL LTD","KQ SEATS PRIVATE LIMITED","KRANTI INDUSTRIES","KRAYCOL STATIONERY PRIVATE LIMITED.","KREDENCE PERFORMANCE MATERIALS","KRINSHA INDUSTRIES","KRIPTION POWDER PAINTS PRIVATE LIMITED","KRIS AUTOMATED PACKAGING SYSTE","KRISFO INFOTECH SOLUTIONS PRIVATE LIMITED","KRISH FLEXIPACKS PVT LTD","KRISH INDUSTRIES","KRISHI RASAYAN EXPORTS PVT LTD","KRISHNA AGRIBUSINESS DEVELOPMENT PRIVATE LIMITED","KRISHNA ALLIED INDUSTRIES PRIVATE LIMTED","KRISHNA ANTIOXIDANTS PRIVATE LIMITED","KRISHNA ART","KRISHNA CHEMICALS","KRISHNA CONCHEM PRODUCT PVT. L","KRISHNA COPPER PRIVATE LIMITED","KRISHNA ENGINEERING INDUSTRIES","KRISHNA ENTERPRISE","KRISHNA FOILS","KRISHNA INDUSTRIES","KRISHNA LAMICOAT PVT LTD","KRISHNA TEXTILES","KRISHNA TRADING CO.","KRISHNA VALLEY AGROTECH LLP","KRISHNAVENI CARBON PRODUCTS PRIVATE LIMITED","KRISOW PERFORMANCE FLUIDS INC","KRITHA CONVERTORS PRIVATE LIMITED","KRONOX LAB SCIENCES LIMITED","KRSNA TRASNMISSION HARDWARE MA","KRUPA CHATONS MANUFACTURING PV","KRUSHI AGRO TEX","KRUTI ENTERPRISE","KRUTIKA AGRO PRODUCE PVT LTD","KRYSTAL ENGINEERING","KSB LIMITED","KSB PUMPS LTD","KSOLARE ENERGY PRIVATE LIMITED","KSR ENGINEERS","KSR FREIGHT FORWARDERS PRIVATE LIMITED","KTD KAMAT TEA DEPOT LLP","KTG STORES","KTNS MINERALS ","KUBIK INDIA PRIVATE LIMITED","KUEHNE NAGEL PRIVATE LIMITED","KUFNER TEXTILE INDIA PVT LTD","KULIN CORPORATION","KULKARNI POWER TOOLS LIMITED","KULODAY TECHNOPACK PVT.LTD","KUMAR INDIA","KUMAR INDUSTRIES","KUMAR ORGANIC PRODUCTS LTD","KUMAR PAINTS & INSULATE INDUSTRIES","KUMAR PRINTERS PRIVATE LIMITED","KUMAR ROTOFLEX PVT LTD","KUMAR TOYS","KUMBHA CHEMICALS PRIVATE LIMITED","KUNAL PRINTING AND PACKAGING","KUND KUND POLYMERS","KUNDAN DYES & INTERMIDIATES","KUNDAN INDUSTRIES LIMITED","KUNHAR PERIPHERALS PVT LTD","KUNTALBIJ ENGINEERS PVT LTD","KUPSA COATINGS PRIVATE LIMITED","KURIAN ABRAHAM PRIVATE LIMITED","KURLON ENTERPRISES LTD.","KUSA CHEMICALS PVT LTD","KUSALAVA INTERNATIONAL LTD.","KUSHAL FERRO ALLOYS PVT LTD","KUSHAL KARYASHALA","KUSHAL KARYASHALA PVT. LTD.","KUSUM ENGINEERING WORKS","KUSUM INDUSTRIES PRIVATE LIMITED","KUSUMGAR CORPORATES PRIVATE LI","KUTCH CHEMICAL INDUSTRIES LTD","KUTCH MEDISURGE INDUSTRIES","KUTZ INDUSTRIES LTD","KUVAM TECHNOLOGIES PRIVATE LIMITED","KV FIRE CHEMICALS (INDIA) PRIV","KVB PROCESSORS PVT LTD ","KWALITEX HEALTHCARE PVT LTD","KWALITY CHEMICAL INDUSTRIES","KWALITY FEEDS LIMITED","KWALITY FOODS","KWENCHRS BEVERAGE PRIVATE LIMITED","L G BALAKRISHNAN & BROS LTD","L L M APPLIANCES PRIVATE LIMITED","L LILADHAR & CO","L N CHEMICAL INDUSTRIES ","L R ACTIVE PVT LTD","L S M FASTNERS","L S ORGANICS","L&T VALVES LIMITED","L.P.S.BOSSARD PVT. LTD.","LA PLASTPACKS PVT LTD","LA SOVEREIGN BICYCLES PVT LTD","LABORT FINE CHEM PVT LTD","LABTOP INSTRUMENTS PRIVATE LIMITED","LACTOSE (INDIA) LIMITED","LADHURAM TOSHNIWAL & SONS ELEC","LAGREENS INDIA PRIVATE LIMITED","LAKE CHEMICALS PRIVATE LIMITED","LAKELAND CHEMICALS INDIA LTD","LAKHANI FOOTWEAR PVT LTD","LAKSHMANAN ISOLA PVT LTD","LAKSHMI CHAITANYA ALLOYS","LAKSHMI ELECTRICAL DRIVES LIMITED","LAKSHMI RUBBERS","LAL BABA SEAMLESS TUBES PVT LT","LALBABA INDUSTRIAL CORPORATION PVT LTD","LALBABA PROJECTS PRIVATE LIMITED","LALIT MACHINERY STORE","LALLUBHAI AMICHAND LIMITED","LALSHAH VENTURES PRIVATE LIMITED","LAMBERTI HYDROCOLLOIDS PVT.LTD","LAMBODHARA TEXTILES LIMITED","LAMER POWER & INFRASTRUCTURE P","LAMINA SUSPENSION PRODUCTS LIMITED","LANCO SOLAR ENERGY PVT LTD","LANCO SOLAR PVT LTD","LANDMARK CRAFTS FASTENERS","LANDMARK CRAFTS PRIVATE LIMITED","LANXESS INDIA PVT.LTD.- CORP KEY","LAPLAST PLASTICS LLP","LAPP INDIA PVT LTD","LARSEN AND TOUBRO LIMITED","LASENOR CHARBHUJA FOODS PVT LT","LASENOR INDIA PVT. LTD.","LASER POWER & INFRA PRIVATE LIMITED","LASTMILE LOGISTICS","LAVANIKAS ENTERPRISES","LAVANYA ENTERPRISES","LAVIOSA INDIA PRIVATE LIMITED","LAVISH FABRICS PVT. LTD.","LAVITANO INDIA PRIVATE LIMITED","LAXACHEM ORGANICS PRIVATE LIMITED","LAXMI BRUSH COMPANY","LAXMI ENTERPRISES","LAXMI HYDRAULICS PVT LTD","LAXMI INDUSTRIES","LAXMI MASALA CO","LAXMI ORGANIC INDUSTRIES LIMIT","LAXMI POLYMERS","LAXMI STAINALLOYS PVT LTD","LAXMINARAIN VISAMBHARNATH","LAXSHE INDUSTRIES","LAZER INDIA PRIVATE LIMITED","LC INDUSTRIES","LE SHARK GLOPAL LLP","LEADERSHIP BOULEVARD PRIVATE LIMITED","LEAF AND NUTS PRIVATE LIMITED","LEAK PROOF PUMPS INDIA PVT LTD","LEAP INDIA PVT. LTD.","LEATHERCRAFT LIFESTYLE PRIVATE LIMITED","LEBEN LABORATORIES PVT. LTD.","LEBEN LIFE SCIENCE PVT LTD","LECHLER INDIA PVT LTD","LEDERLE PHARMA LTD","LEDVANCE PRIVATE LIMITED","LEE VEDLA EXPORTERS & IMPORTERS","LEEL ELECTRICALS LIMITED","LEFJORD INC","LEHRY INSTRUMENTATION AND VALVES PVT. LTD.","LEIGHTON INDIA CONTRACTORS PVT","LENORA GLOVE PVT. LTD.","LENSKART SOLUTIONS PVT LTD","LEO CORPORATION","LEO FRAGRANCES","LEO INDUSTRIES ","LEOCH BATTERIES INDIA PVT LTD","LEONARD HOLDINGS & TRADING PVT LTD","LEONARDO","LEONID CHEMICALS P LTD","LEOS ELEVATOR COMPONENTS MARKETING PRIVATE LIMITED","LESCHACO INDIA PRIVATE LIMITED","LEVANA PRODUCTS PRIVATE LIMITE","LEVRAM LIFESCIENCES PVT. LTD.","LEWENS LABS PRIVATE LIMITED","LEXI PEN PVT LTD","LG POLYMERS INDIA PRIVATE LIMITED","LIBERTY WORLD WIDE MANUFACTURING AND MARKETING PRI","LIBOX CHEM. (INDIA) PVT. LTD.","LIC OF INDIA DIVISIONAL OFFICE","LIFE CARE LOGISTIC PVT LTD","LIFE PHARMA CHEM","LIFE-FUN BIKE STUDIO PRIVATE LIMITED","LIFESTAR PHARMA PRIVATE LIMITE","LIFESTYLE FOODS PRIVATE LIMITED","LIFT ARTS","LIGHT & SHADE ELECTRICALS PVT ","LINCOLN ELECTRIC COMPANY","LINEAGE POWER PRIVATE LIMITED","LINEN CHEM PRIVATE LIMITED","LINERS INDIA LTD.","LINIT EXPORTS PRIVATE LIMITED ","LINK COMPOSITES PRIVATE LIMITED","LINTAS OFFICE SUPPLIERS","LINTAS PACKAGING PVT. LTD.","LIOLI CERAMICA PRIVATE LIMITED","LION RUBBER INDUSTRIES PVT LTD","LION TAPES PVT LTD.","LIPI INTERNATIONAL PVT LTD","LITMUS ORGANICS PVT. LTD.","LITTLES ORIENTAL BALM & PHARMACEUTICALS LTD","LOAD STAR SOLID TYRES PVT LTD","LOBA CHEMIE PVT LTD","LOGIS-TECH INDIA PRIVATE LIMITED","LOGISTIC INTEGRATORS (I) PVT. ","LOHITHA LIFE SCIENCES PVT LTD","LONA INDUSTRIES LIMITED","LONESTAR INDUSTRIES","LONG HAUL TECHNOPRISE","LONSEN KIRI CHEMICAL INDUSTRIE","LOPAN INDUSTRIES","LORD INDIA PRIVATE LIMITED","LOREAL INDIA PRIVATE LIMITED","LOTUS CHOCOLATE COMPANY LIMITED","LOTUS LOGISTICS","LOTUS POLYMERS INDUSTRIES","LOTUSSHIELDERS(INDIA)P RIVATELIMITED","LOUTE WAREHOUSING UNIT PRIVATE LIMITED","LOVELY ENTERPRISES","LOXIM INDUSTRIES LIMITED","LOYAL EQUIPMENTS LTD","LOYAL TEXTILE MILLS LTD","LP (INDIA) LOGISTICS PVT LTD","LP LOGISCIENCE LLP","LR ACTIVE PRIVATE LIMITED","LT FOODS LTD","LUBERR PRIVATE LIMITED","LUBGRAF PRODUCTS","LUBGRAF SYNOILS PVT LTD","LUBI INDUSTRIES LLP","LUBRIZOL INDIA PVT LTD ","LUBZ CORPORATION INDIA PVT LTD","LUCAS INDIAN SERVICE LTD","LUCID COLLOIDS LTD.","LUCRO","LUCY ELECTRIC","LUFTEK ENGINEERING PRIVATE LIMITED","LUK PLASTCON LIMITED","LUMAN INDUSTRIES LTD","LUMAX AUTO TECHNOLOGIES LTD","LUMINOUS POWER TECHNOLOGIES PL","LUMIS BIOTECH PRIVATE LIMITED","LUMISENSE OPTOELECTRONICS PRIVATE LIMITED","LUNA TECHNOLOGIES PRIVATE LIMITED","LUNAR MOTORS PVT.LTD.","LUPIN LTD","LUTHRA INDUSTRIAL CORPORATION","LUTHRA MACHINE TOOLS","LUX FLAVOURS","LUX INDUSTRIES LIMITED","M & B ENGINEERING LIMITED","M B MARKETING","M B SUGARS & PHARMACEUTICALS L","M C BAUCHEMIE I PVT LTD ","M J BROTHERS AND INDUSTRIES","M J ENGINEERING","M K & SONS ENGINEERING PVT LTD","M K AUTO CLUTCH COMPANY","M M AQUA TECHNOLOGIES LIMITED","M M ENVIRO PROJECTS PVT LTD","M M FABRICS SOURCING LLP","M M FORGINGS LTD","M M TRADERS","M N CHEMICALS LTD","M N ENGINEERING","M P ENGINEERING CONSTRUCTION I PVT LTD","M P INTEGRATED ROOFING PRIVATE LIMITED","M P RAJYA SEHKARI JAN AUSHADHI VIPANAN SANGH MARYA","M R LOGISTICS INDIA PVT LTD","M R SPECIALITY CHEMICALS PVT. ","M S BUREAU OF TEXTBOOK PRODUCT","M S PATEL & CO","M WIRE TRADE ","M.B EXPORTS LTD","M.G.M. RUBBER COMPANY","M.J. CHEMICAL INDUSTRIES","M.M POLYMERS","M.M. POONJIAJI SPICES LTD. ","M.S.C. CHEMICALS","M/S H.D.WIRES PVT. LTD. ( GALVANIZING DIV)","M/S K L S R INFRATECH LIMITED","M/S S.S. COMBINES","M/S TOMOEGAWA AURA INDIA PRIVATE LIMITED","MA TECH","MAA PET PVT LTD","MAA VAISHNODEVI TRADERS","MAASS FLANGE INDIA PRIVATE LIMITED","MAA-TEX SPECIALITY","MAAX AEROSOL TECHNOLOGY PRIVATE LIMITED","MAAX LUBRICATION PVT LTD","MAAX SOLUTIONS INC","MAC INDUSTRY","MAC SEAL AND RUBBER COMPONENTS INDIA PVT LTD","MACEDON VINIMAY PVT LTD","MACHIN FABRIK INDUSTRIES PVT L","MACHINE TREE","MACMILLAN PUBLISHERS INDIA PRI","MACO GROUP","MACO PVT LTD.","MACPOWER CNC MACHINES LIMITED","MACPOWER SWITCHGEAR PRIVATE LIMITED","MACRO ENTERPRISES","MACRO METALS PVT LTD","MACRO POLYMERS PRIVATE LIMITED","MACROTHERM INDUSTRIES","MACSON COLOR CHEM PRIVATE LIMITED","MACSON PRODUCTS","MACSUR PHARMAA INDIA PRIVATE LIMITED","MADAN UDYOG PRIVATE LIMITED","MADHAV INDUSTRIES","MADHU JAYANTI INTERNATIONAL PRIVATE LIMITED","MADHU SILICA PVT LTD","MADHURAM INDUSTRIES","MADHUS GARAGE EQUIPMENTS PVT L","MADRAS ENGINEERING INDUSTRIES P.LTD","MADRAS FLOURINE PRIVATE LTD","MADURA COATS PRIVATE LIMITED","MADURA INDUSTRIAL TEXTILES LTD","MAFATLAL INDUSTRIES LIMITED","MAGANA ELECTRO CASTINGS LTD","MAGEBA BRIDGE PRODUCTS PRIVATE LIMITED","MAGIC FOODS INDIA PRIVATE LIMITED","MAGMA CARE PRIVATE LIMITED","MAGNA CHEMICAL MANUFACTURERS","MAGNA COLOURS (INDA ) PVT LTD","MAGNESIT POWDER-RETAIL","MAGNET LABS PRIVATE LIMITED","MAGNIFY ELECTRIC INDUSTRIES","MAGNUS LUBRICANTS PRIVATE LIMITED","MAGOD LASER MACHINING PVT.LTD","MAGOTTUAX INDUSTRIES","MAGPPIE LIVING PRIVATE LIMITED","MAHA CLASSIC COATINGS","MAHA SEER HOTELS AND RESORTS PRIVATE LIMITED","MAHABAL AUTO ANCILLARIES PRIVA","MAHADEV TRADERS","MAHALAXMI ALUGRILL","MAHALAXMI METALS & ALLOYS","MAHARAJA AGARBATTI AGENCY","MAHARAJA AGENCIES PRIVATE LIMITED","MAHARAJA PLASTIC","MAHARAJA SHREE UMED MILLS LTD","MAHARANI INNOVATIVE ","MAHARASHTRA EASTERN GRID POWER","MAHARASHTRA PLASTIC & INDUSTRI","MAHARSHEE GEOMEMBRANE (INDIA) PRIVATE LIMITED","MAHARSHI LABELS PVT.LTD.","MAHATEK ENGINEERS","MAHAVEER  ENTERPRISES","MAHAVEER ENTERPRISES","MAHAVEER MEDICARE","MAHAVEER PHARMA","MAHAVIR CHEMICALS","MAHAVIR EXPOCHEM LTD","MAHAVIR PLASTIC IND.","MAHAVIR SUBMERSIBLES PVT LTD","MAHAVIR SYNTHESIS PVT. LTD.","MAHAVIR TRADERS","MAHAVIR TRADING CO.","MAHESH RAJ CHEMICALS PVT LTD","MAHESHWARI FASTENERS & BRIGHT ","MAHESHWARI MOTORS","MAHIKA PACKAGING INDIA LIMITED","MAHINDER GUPTA AND CO.","MAHINDRA & MAHINDRA LTD","MAHINDRA AGRI SOLUTIONS LTD","MAHINDRA CIE AUTOMOTIVE LIMITE","MAHINDRA DEFENCE NAVAL SYSTEMS LIMITED","MAHINDRA EPC IRRIGATION LIMITED","MAHINDRA GEARS & TRANSMISSIONS","MAHINDRA LOGISTICS","MAHUR FASTENERS","MAILAM INDIA LTD","MAINI CONSTRUCTION EQUIPMENTS ","MAINI SCAFFOLD SYSTEMS","MAIYAS BEVERAGES AND FOOD P LT","MAKARJYOTHI AUTOMOTIVE MATERIALS (INDIA) PVT. LTD.","MAKHARIA MACHINERIES PVT. LTD.","MAKHIJA SPINNING MILLS PRIVATE LIMITED","MAKIN LABORATORIES PVT. LTD.","MAKITA POWER TOOLS INDIA PVT LTD","MAKWANA TEXTILE","MALDEEP CATALYSTS PVT. LTD.","MALHOTRA BOOK DEPOT.","MALLCOM INDIA LTD","MALTEX FASHIONS PRIVATE LIMITED","MAN PROJECTS LTD ","MANABAY INTERTRADE PRIVATE LIMITED","MANAGE AIR PRODUCTS (I) PVT. L","MANAKAMNA COMPOSITES PVT LTD","MANAKSIA COATED METALS AND IND","MANAKSIA STEELS LIMITED","MANALI PETROCHEMICALS ","MANATEC ELECTRONICS PVT LTD","MANAV ENERGY PVT LTD","MANAV RUBBER MACHINERY PVT. LTD","MANCARE HEALTH PVT. LTD.","MANE INDIA PVT LTD","MANEK MINERALS","MANGAL ELECTRICAL INDUST.PVT. LTD.","MANGALAM ALLOYS LIMITED-CHH","MANGALAM DRUGS & ORGANICS LTD","MANGALAM INTERMEDIATES","MANGALAM ORGANICS LIMITED","MANGALCHAND BHANWARLAL AGENCIES","MANGIRISH ENTERPRISES","MANGLA ENGINEERING LIMITED","MANIBHADRA LOGISTICS","MANIKANDAN DUNAMIS PRIVATE LIM","MANIPAL TECHNOLOGIES LTD","MANIPAL UTILITY PACKAGING SOLUTION PVT LTD","MANISH ELECTRICALS","MANISHA AGRO SCIENCES","MANISHA INDUSTRIES","MANJILAS FOOD TECH PVT LTD","MANJUSHREE TECHNOPACK LIMITED","MANKIND PHARMA LIMITED","MANOHAR VALVES","MANOJ BROTHERS","MANORAMA INDUSTRIES LIMITED","MANORAMA ROPES INDIA PRIVATE LIMITED","MANULI PSI HYDRAULICS PVT LTD.","MAPEI CONSTRUCTION PRODUCTS IN","MAPLE IMPEX","MAPPLE STAINLESS PROCESSING (P) LTD.","MARBLE LUNGIYAN","MARBLE MAGIK CORPORATION","MARC EXPORTS","MARC INDIA LIMITED","MARC LABORATORIES LIMITED","MARCO CHEM","MARCUS OILS & CHEMICALS PVT. LTD.","MARDIA ALUMINIUM","MARGO BIOCONTROLS PVT LTD","MARICO LTD","MARION BIOTECH PVT LTD","MARIYA HEALTH CARE PVT LTD","MARK INDUSTRIES","MARQ SOLUTIONS","MARS INTERNATIONAL INDIA P LTD","MARS PETROCHEM PVT LTD","MARS THERAPEUTICS & CHEMICALS ","MARSHAL LABORATORIES","MARSHAL POLYPACK","MARSHALL WIRE INDUSTRIES","MARTIN & BROWN BIO-SCIENCES","MARTIN AND BROWN BIO SCIENCES","MARTOPEARL ALLOYS PVT LTD","MARUDHAR INDUSTRIES LTD UNIT I","MARUDHARA POLYPACK PRIVATE LIMITED","MARUTHI PLASTICS AND PACKAGING CHENNAI (P) LIMITED","MARUTI  BITUMEN PVT LTD","MARUTI AGARBATTI AGENCY","MARUTI ARCHITECTURAL PRODUCTS PRIVATE LIMITED","MARUTI CASTINGS","MARUTI CLEARING AGENCIES","MARUTI DYESTUFF","MARUTI ENTERPRISE","MARUTI INDUSTRIES","MARUTI MULTICHEM PVT. LTD.","MARUTI PETROCHEM","MARUTI RUB-PLAST PRIVATE LIMITED","MARUTI TECHNO RUBBER PVT LTD","MARVEL FRAGRANCES COMPANY","MARVEL VINYLS LIMITED","MASCASTS","MASCOT HEALTH SERIES PVT LTD","MASK POLYMERS INSULATORS PVT LTD","MASK SEALS PRIVATE LIMITED","MASP FUNKTIONING","MASS POLYMERS","MASTER ( INDIA ) BREWING COMPANY","MASTER BUILDERS SOLUTIONS INDIA PRIVATE LIMITED","MASTER FLUID SOLUTIONS ( INDIA ) PVT LTD.","MASTER LEVI SERVICES INDIA P L","MASTER MARKETING","MASTER SALES","MASTERPLAST INDIA PRIVATE LIMITED","MASTERPRO FACILITY SERVICES PRIVATE LIMITED","MASTURLAL FABRICHEM PVT LTD","MATANGI CHEMICAL","MATANGI GROUP","MATANGI INDUSTRIES LLP","MATANGI INTERNATIONAL","MATERIAL MOVELL INDIA PVT LTD","MATLINKS INDIA","MATRIX FINE SCIENCES PRIVATE LIMITED","MATRIX POLYTECH PVT LTD.","MATRIXONE BRANDS PRIVATE LIMITED","MATXIN LABS PRIVATE LIMITED","MAULI AUTOMATIONS INDIA PRIVATE LIMITED","MAULIKEM PRODUCTS PVT LTD","MAURYA INDUSTRIES","MAWANA FOODS PVT LTD","MAX CHEM PHARMACEUTICALS PVT. ","MAX PURE WATER SYSTEM PVT. LTD","MAXCHEM PHARMACEUTICALS PRIVATE LIMITED","MAXFORD HEALTHCARE","MAXIMA BOILERS PVT.LTD","MAXMED LIFE SCIENCES PRIVATE L","MAXPRIDE (INDIA) RUBBER PRIVAT","MAXTHERM BOILERS PVT LTD","MAXWAX MULTIPRODUCTS LLP","MAXWELL INDUSTRIES","MAXX FARMACIA (INDIA) LLP","MAY & BAKER PHARMACEUTICALS LI","MAYA APPLIANCES PRIVATE LIMITED","MAYAS FRAGRANCE SPECIALTIES","MAYCH DISTRIBUTION","MAYUR CHEMICALS","MAYUR LEATHER PRODUCTS LTD","MAYUR MARKETING","MAYUR STRAPS & PACKAGING INDUS","MAYURA STEELS PRIVATE LIMITED","MAYURESH ENGINEERING WORKS PVT","MAYURI KUMKUM","MAZDA COLOUR LIMITED","MAZDA LTD","MB METALLIC BELLOWS PVT  LTD","MB PLASTIC INDUSTRIES","MBE COAL & MINERAL TECHNOLOGY ","MBK TEXTILE ENGINEERS PVT. LTD","MCBS PVT LTD","MCCOY PERFORMANCE SILICONES PR","MCCOY SILICONES LTD.","MCLUBE ASIA PRIVATE LIMITED","MCNROE CONSUMER PRODUCTS PVT. LTD.","MCPP INDIA PRIVATE LIMITED","M-CUBE PROJECTS PVT LTD ","MEC TECHNOLOGY","MECH N TECH","MECHEMCO RESINS PVT LTD ","MECHMANN ENGINEERING PVT LTD ","MECO TRONICS","MECORDS INDIA PRIVATE LIMITED","MEDI ACCESS SOLUTIONS PVT LTD","MEDI PLUS (INDIA) LIMITED","MEDI SALES INDIA PRIVATE LIMITED","MEDIBLUE HEALTH CARE PRIVATE L","MEDICO REMEDIES LTD","MEDIGATES","MEDIKHOJ INTERNATIONAL","MEDIPOL PHARMACEUTICAL INDIA PVT LTD","MEDISALES LOGISTICS","MEDISYNTH CHEM P LTD ","MEDITRON","MEDIWIN LABORATORIES","MEDLEY PHARMACEATUCALS LTD","MEDOZ PHARMACEUTICALS PRIVATE LIMITED","MEDTECH LIFE PRIVATE LIMITED","MEEKA MACHINERY PVT LTD","MEENAKSHI INDIA LIMITED","MEENAXY PHARMA PVT LTD","MEERA DISTRIBUTION COMPANY","MEERA IMPEX","MEESAN LOGISTICS PRIVATE LIMITED","MEGA ENGINEERING PRIVATE LIMITED","MEGA INTERNATIONAL","MEGAEXPRESS FREIGHT SERVICES PRIVATE LIMITED","MEGAMORPH MARKETING PRIVATE LIMITED","MEGATREND FABCON PRIVATE LIMITED","MEGHA AGROTECH PVT LTD","MEGHA ENGINEERING AND INFRASTR","MEGHA INDUSTRIES","MEGHA INTERNATIONAL ","MEGHA ROTO-TECH PVT.LTD","MEGHACHEM INDUSTRIES","MEGHAFINE PHARMA PVT LTD","MEGHDOOT PACKAGING (UTTARANCHAL)","MEGHMANI INDUSTRIES LTD.","MEGHMANI LLP","MEGHMANI ORGANICS LTD.","MEHADIA ENTERPRISES PVT. LTD.","MEHALA MACHINES INDIA LIMITED","MEHALA SALES CORPORATION","MEHER ADVANCED MATERIALS PVT LTD","MEHER FASHIONS PRIVATE LIMITED","MEHER FILAMENTS","MEHER INTERNATIONAL","MEHLER ENGINEERED PRODUCTS","MEHTA FABRICS AND YARNS LLP","MEHTA INDUSTRIAL CORPORATION","MEHTA METAL INDUSTRIES","MEHTA PETRO REFINERIES LIMITED","MEHTA SANGHVI & CO","MEHTA TUBES LIMITED","MEHUL COLOURS & MASTER BATCHES PVT LTD","MEHUL ELECRTO INSULATING INDUSTRIES","MEHUL ENTERPRISES","MEHUL SANITARY CORPORATION","MEKUBA PETRO PRODUCTS","MELDIMAA SADHIMAA SONS PRIVATE LIMITED","MELLCON ENGINEERS PVT LTD","MELOG SPECIALITY CHEMICALS PVT","MELTING POT CONCEPTS PRIVATE LIMITED","MELZER CHEMICALS PRIVATE LIMITED","MEMBRANE FILTERS INDIA PVT LTD","MENAL ENGINEERS","MENON PISTON RINGS PVT LTD ","MENON PISTONS LIMITED","MENRIK BIOMERGE PRIVATE LIMITED","MEPRO PHARMACEUTICALS P.LTD","MEPROMAX LIFESCIENCES PVT LTD","MERAKISAN PRIVATE LIMITED","MERCK LIFE SCIENCE PRIVATE LIM","MERCK LIMITED","MERCURY FABRICS PVT.LTD.","MERGE MECHANO PVT LTD","MERIDIAN ENTERPRISE PVT. LTD.","MERINO INDUSTRIES LIMITED","MERIT POLY PLAST","MERIT POLYMERS","MERIT TRAC SERVICES PVT LTD","MERMAID SWIMMING POOLS","MERO ASIA ","MERRITO POLYMERS (INDIA) PVT L","MERSEN INDIA PVT LTD","MERWANS CONFECTIONERS P LTD","MESO CARBON INDIA PVT LTD","MET FLOW CAST P LTD","META CHEM PAINTS AND ADHESIVES PVT LTD.","METAFORGE ENGINEERING (INDIA) PVT LTD","METAHELIX LIFE SCIENCES LIMITED","METAL CARE ALLOYS PVT. LTD.","METAL CHEM","METAL CRAFTS INDUSTRIES","METAL FLOW LUBRICANTS & SOLUTI","METAL FORGINGS PVT LTD","METAL GEMS","METAL INDUSTRIES","METAL KRAFT FORMING INDUSTRIES","METALCHEM","METALKARMA ENGINEERING TECHNOLOGIES PVT LTD","METALKRAFT FORMING INDUSTRIES ","METALLIC BELLOWS (INDIA) PVT L","METALLICA INDUSTRIES","METALLOIDS STEEL INDUSTRIES","METALS UNITED ALLOYS AND FUSION PRODUCTS LIMITED","METALUBE PRIVATE LIMITED","METCON COATINGS & CHEMICALS ","METCRAFT ENGINEERING","METHODEX SYSTEMS PRIVATE LIMITED","METRO AROCHEM","METRO CHEMICAL WORKS PVT.LTD","METRO ENGINEERING WORKS","METRO ENTERPRISES ","METRO INDUSTRIAL FABRICS","METRO INSULATIONS","METRO INTERNATIONAL","METRO TEXTILES","METRO TYRES LTD","METROCHEM API PRIVATE LIMITED","METRON ALLOYS","METROPOLITAN EXIMCHEM PVT LTD","MEX LIFECARE LLP","MEXIM ADHESIVE TAPES PVT LTD","MGK FOOD & NATURALS PRIVATE LIMITED","MGM VARVEL POWER TRANSMISSION","MGM-VARVEL POWER TRANSMISSION PVT LTD","MICA MOLD","MICAS ORGANICS LIMITED","MICO STEEL & ENGG. CO","MICON ENGINEERS (HUBLI)","MICON VALVES (INDIA) PVT LTD ","MICRO COILS AND REFRIGERATION PRIVATE LIMITED","MICRO INKS PRIVATE LIMITED","MICRO MELT P. LTD.","MICRO PNEUMATICS PVT LTD","MICRO POLYIONICS PVT.LTD.","MICRO WELDS INDIA","MICROCIL MANUFACTURERS","MICRO-CRAFT","MICROFINISH PUMPS PVT LTD","MICROFINISH VALVES PVT LTD","MICROMOLE IONICS PVT.LTD.","MICROSPHERES (INDIA)","MICRO-TECH CNC PVT LTD.","MICROTECH ROLLERS PVT LTD","MICROTROL STERILISATION SERV","MIDAS HYGIENE INDUSTRIES PVT. LTD","MIDMARK (INDIA) PVT. LTD.","MIHAMA INDIA PVT LTD","MIKHAIL CORPORATION","MIKLENS BIO PRIVATE LIMITED","MIKU AGENCIES","MILAN LABORATORIES (INDIA) PRI","MILESTONE INDUSTRIES","MILESTONE PRESERVATIVES PVT.LTD.","MILLENIUMINDIA AUTO PRIVATE LIMITED","MILLENNIUM ENTERPRISE","MILLENNIUM IMPEX PVT LTD","MILLIKEN CHEMICASL & TEXTILES ","MINAXI FOIL PVT LTD","MINAXI SALES CORPORATION","MINDA AUTOMOTIVE SOLUTIONS LIM","MINDA EMER TECHNOLOGIES LTD","MINDA INDUSTRIES LIMITED","MINDA STONERIDGE INSTRUMENTS LIMITED","MINDA TTE DAPS PRIVATE LIMITED","MINDARIKA PRIVATE LIMITED","MINERAL PROCESS EQUIPMENT","MINERAL SALES AGENCY","MINEX METALLURGICALS CO PVT LT","MINI ELECTRONIC INDUSTRIES PVT LTD","MINIT ENGINEERS INDIA (P) LTD","MINITEK .","MINITEK ..","MINITEK CORP","MINOX METAL PRIVATE LTD","MINSUN INFRAA PRROJECTS PRIVATE LIMITED","MIPALLOY","MIRACLE CABLES ( INDIA ) P.LTD","MIRACLE PRINT PACK PRIVATE LIMITED","MIRANDA AUTOMATION PVT LIMITED","MIRANDA FEW TOOLS PVT. LTD.","MIRANDA TOOLS","MIRRA & MIRRA INDUSTRIES","MISSION PHARMA LOGISTICS PVT.L","MIST RESSONANCE ENGINEERING PVT LTD","MISTPOFFER PERFUMETICS LLP","MISTRY INTERIOURS","MIT LUBE ","MITA FASTNERS PVT LTD","MITCHELL FAIR & WHITE PVT LTD","MITRAS TECHNOCRAFTS PVT LTD","MITSUBISHI CHEMICAL INDIA PVT ","MITSUBISHI ELECTRIC INDIA PRIVATE LIMITED","MITSUCHEM PLAST LTD","MITSUI CHEMICALS INDIA PVT LTD","MITTAL ELECTRONICS","MITTAL ENTERPRISES","MIVEN MAYFRAN CONVEYORS","MKS HEALTHCARE LLP","MKS PHARMA LIMITED","MKT HERBOTECH LLP","ML INTERIORS EXTERIORS","MLA INDUSTRIES","MM LED","MMC PHARMACEUTICALS LIMITED","MMG HEALTHCARE","MMP INDUSTRIES LTD","MNS FOODS PRIVATE LIMITED","MOD E TACH ENGINEERING P LTD","MOD E TECH ENGINEERING P LTD","MODEPRO INDIA PRIVATE LIMITED ","MODEPRO INDIA PVT LTD ","MODERN CHEMICALS & PLASTICS","MODERN COMMUNICATION & BROADC","MODERN EQUIPMENT COMPANY","MODERN FLEXIPACK","MODERN PAPER","MODERN PUBLISHERS","MODERN SALE CORPORATION","MODHERA CHEMICALS PVT LIMITED","MODI CHEM PLAST MATERIAL PVT LTD ","MODI HITECH INDIA LTD","MODI NATURALS LTD","MODISON METAL LTD","MODTECH MATERIAL HANDLING PROJ","MODULE INDUSTRIES LLP","MOENUS TEXTILES PVT LTD","MOHAN IMPEX","MOHAN MEAKIN LIMITED","MOHAN MUTHA POLYTECH PRIVATE LIMITED","MOHAN SPINTEX INDIA LIMITED","MOHANI TEA LEAVES P LTD","MOHANLAL & BROTHERS","MOHATA FABRICS","MOHD MUSTAFA MOHD YUSUF","MOHINDER AGENCIES","MOHINDRA HOSIERY INDUSTRIES","MOHINI FOOD PRODUCT","MOHINI HEALTH & HYGIENE LIMITED","MOHIT ENTERPRISES","MOHIT POLYTECH PRIVATE LIMITED","MOKSH AGARBATTI CO.","MOLECULAR GENE TECHNOLOGIES","MOLIKULE TECHNOLOGIES PRIVATE LIMITED","MOLY CHEM ","MOLYCHEM","MOMAYA STEELS","MOMENTIVE PERFORMANCE MATERIAL","MONACHEM ADDITIVES PVT. LTD","MONACHEM SPECIALITIES LLP","MONDELEZ INDIA FOODS PVT LTD","MONEY PACKERS","MONEYPACKERS INDIA PRIVATE LIMITED","MONGIA TOOL TECH PRIVATE LIMITED","MONGINIS FOOD","MONISHA ENTERPRISES","MONOCHEM GRAPHICS PVT. LTD.","MONOPOL COLORS INDIA PRIVATE LIMITED","MONSHER INDIA SAFETY EQUIPMENTS PRIVATE LIMITED","MONTAGE GLOBAL PRIVATE LIMITED","MONTEX FORGE INDUSTRIES","MONTEX GLASS FIBRE IND PVT LTD","MOPSHOP DISTRIBUTION PRIVATE LIMITED","MOR MEDICS SCIENCE & TECHNOLOGIES","MORARJEE TEXTILES LIMITED","MORARKA ORGANIC FOODS LTD","MORAYA GLOBAL LTD ","MORCHEM PRIVATE LIMITED","MORESCO HM&LUB INDIA PRIVATE LIMITED","MORGANITE CRUCIBLE INDIA LTD","MOSIL LUB","MOSIL LUBRICANTS PVT.LTD.","MOTHER DAIRY FRUIT & VEGETABLE","MOTHERSON MOLDS AND DIECASTING LIMITED","MOTHERSON SINTERMETALTECHNOLGY LTD","MOTHERTOUCH BABY PRODUCTS LLP","MOTI INDUSTRIES","MOTIKANT ENTERPRISES","MOTIVES LIFESTYLES","MOTO TECHNOLOGY PRIVATE LIMITED","MOULD INJECTION TECHNOLOGY PVT","MOULDSPEX","MOUNT EVEREST BREWERIES LTD","MOXCARE PRODUCTS INC.","MOZZBY TECHNOLOGIES LLP","M-PAC","MPD INDUSTRIES PRIVATE LIMITED","MPI PAPERS PVT.LTD.","MPM PRIVATE LIMITED","MPM-DURRANS REFRACOAT PRIVATE LIMITED","MPR REFRACTORIES LIMITED","MPS PRECISION ENGINEERING PRIVATE LIMITED","MR CHEMIE INDIA PRIVATE LIMITED","MRIDUL STEEL COMPANY","MRINMOYEE SUPPLY PVT. LTD.","MRK HEALTHCARE PVT LTD","MRK PACKAGING","MS AGARWAL FOUNDRIES PRIVATE LIMITED","MSL DRIVELINE SYSTEMS LIMITED","MSN INTERMEDIATES LTD","MSS FILTRATION ENGINEERING P L","MTCL TRANSPORT","MTR FOODS PRIVATE LIMITED","MUDKAVI PHARMA ASSOCIATES","MUKTA AGENCIES","MUKTA INDUSTRIES","MUKTA INTERNATIONAL","MUKTHA LABORATORIES PRIVATE LIMITED","MUKUND OVERSEAS","MULAGADA VARU FOODS & BEVARAGES","MULTANI PHARMACEUTICALS LTD.","MULTI CAR TECH PRIVATE LIMITED","MULTI ORGANICS PVT. LTD.","MULTI PACK","MULTIBASES (INDIA) LTD","MULTIFILMS PLASTICS PRIVATE LIMITED","MULTIPLE SPECIAL STEEL PVT LTD","MULTITECH CONCEPTS","MULTITECH PRODUCTS PRIVATE LIMITED","MULTIVAC LARAON INDIA PVT LTD","MUNDHRA CHEMICALS PVT.LTD.","MUNDRA INTERNATIONAL CONTAINER","MUNICH CHEMICALS","MURASIT BAUCHEMIE GOA PRIVATE LIMITED","MURUGAPPA MORGAN THERMARL CERAMICS LIMITED","MUSCLEPRO NUTRITION PRIVATE LIMITED","MUSKAAN INDUSTIRES","MUTTREJA INDUSTRIES","MUTUAL CHEMICALS PVT. LTD.","MVL MEDISYNTH PRIVATE LIMITED","MVM INDUSTRIES","MX SYSTEM INTERNATIONAL PVT. LTD","MY BRANCH INFRASTRUCTURE PRIVATE LIMITED","MY BRANCH SERVICES PRIVATE LIMITED","MY PHARMA","MYK ARMENT PVT. LTD","MYK LATICRETE INDIA PRIVATE LIMITED","MYSORE BANGLORE AGARBATTI CENTER","MYSORE PAINTS AND VARNISH LIMITED","MYSORE POLYMERS & RUBBER PRODU","MYSORE SCENTS COMPANY","MYVI FOODS PRIVATE LIMITED","N B ENTREPRENEURS","N H POLYMERS","N K BROTHERS","N K FIRE & SAFETY","N K GOSSAIN PRINTING PRESS PRIVATE LIMITED","N M PATEL & CO.","N R AGARWAL INDUSTRIES LTD","N R AROMAS","N R OILS","N. GANDHI & CO.","N. J. ECO-BUILD PRIVATE LIMITED","N. RANGA RAO & SONS PVT LTD","N. V. TECHNOCAST PRIVATED LIMITED","N2S TECHNOLOGIES PRIVATE LIMITED","NA","NAAPTOL ONLINE SHOPPING PRIVATE LIMITED","NAARI PHARMA PRIVATE LIMITED","NACH ENGINEERING PVT LTD","NADI AIRTECHNICS PRIVATE LIMITED","NAFED","NAGA LIMITED","NAGARJUNA HERBAL CONCENTRATES","NAGARWALA ENGINEERING CO ","NAGESH KNIT & WEAVE","NAGMAN INSTRUMENTS & ELECTRONI","NAGREEKA INDCON PRODUCTS PRIVATE LIMITED","NAKODA DAIRY PRIVATE LIMITED","NAKODA GROUP OF INDUSTRIES LIM","NAKODA INDUSTRIES","NALCO METAL PRODUCTS LTD","NALCO WATER INDIA LIMITED","NAMO ENTERPRISES","NAMOO IMPEX","NAMOSRI VENTURES PRIVATE LIMIT","NAMRATHA AGENCIES","NANA UDYOG","NANDA INDUSTRIES","NANDAN IMPEX PVT LTD","NANDAN PETROCHEM LIMITED.","NANDI CHEMLABS","NANDRATAN FOUNDRY & ENG WORKS PVT LTD","NANDU CHEMICALS PRIVATE LIMITE","NANO MAG TECHNOLOGIES PVT LTD","NANSON FOUNDRY","NANZ MED SCIENCE PHARMA PVT.LTD.","NARA LOGISTICS","NARANG PLASTICS PVT. LTD ","NARAYAN INDUSTRIES","NARAYANA HRUDAYALAYA LIMITED","NARAYANI FLAVOURS AND CHEMICALS P LTD","NARBADA TAXTILES PVT.LTD.","NARENDRA FORWARDERS PVT LTD","NARENDRA PACKAGING PVT LTD","NARMADA COLOURS PVT LTD","NARMADA ENTERPRISES","NARSI & ASSOCIATES ","NARSINGH MULTI TRADELINK PRIVATE LIMITED","NATCO PHARMA LTD","NATH TITANATES PRIVATE LIMITED","NATHAN EXIM CORPORATION (CHOKW","NATIONAL AUTOPLAST","NATIONAL CHIKKI","NATIONAL ENGINEERING CORPORATION","NATIONAL EXPORTS INDUSTRIES","NATIONAL INDUSTRIES","NATIONAL KUMKUM FACTORY","NATIONAL LOGISTICS","NATIONAL PLASTIC TECHNOLOGIES ","NATIONAL POLYMERS CO.","NATIONAL SPECIALITIES PRODUCTS","NATIONAL TEXTILE CORPORATION L","NATIONAL TOY PRODUCT","NATIONAL VINYL INDUSTRIES","NATRAIL SERVICES","NATUR TEC INDIA PRIVATE LIMITED","NATURAL AND ESSENTIAL OILS PVT LTD","NATURAL AROMA PRODUCTS PVT. LTD.","NATURAL AROMATICS","NATURAL HERBS AND FORMULATION PRIVATE LIMITED","NATURAL REMEDIES P LTD","NATURE HOME CARE PRODUCTS","NATURE PURE WELLNESS PVT. LTD","NATUREGEN TECHNOLOGIES PVT LTD","NATURES MANIA","NATURE'S ORGANICS","NATUREX INDIA PRIVATE LIMITED","NATURO FOOD & FRUIT PRODUCTS P","NAUHARI FOOD PRODUCTS PVT LTD","NAVAGEN PRODUCTS PVT LTD","NAVAMI ENGINEERING PVT.LTD","NAVAMI INDUSTRIES PVT. LTD.","NAVBHARAT ELECTRONICS CO","NAVBHRAT INDUSTRIES","NAVEENA INDUSTRIES","NAVEENA PACKAGINGS","NAVHARI FOOD PRODUCTS PRIVATE LIMITED","NAVIN FLUORINE INTERNATIONAL LIMITED","NAVITAS GREEN SOLUTIONS PVT LTD","NAVJYOTI METALS","NAVKAR ENTERPRISES","NAVKAR SUGARS","NAVKETAN PHARMA PVT. LTD.","NAVLIGHT INCORPORATE","NAVNEET EDUCATION LIMITED","NAVNEET ELECTRICAL","NAVRATAN SPECIALTY CHEMICALS ","NAVRIT MARKETING PVT LTD","NAVYUG CHEMICALS PVT LTD","NAVYUG PHARMACHEM PVT LTD.","NAYA PLAST & METAL WORKS","NAYAKEM ORGANICS PVT LTD","NAYASA SUPERPLAST","NAYASHA HOME WARE","NBR COOLING SYSTEMS PRIVATE LIMITED","NCGB ENGINEERING COMPANY PRIVA","NCL INDUSTRIES LTD","NECO HEAVY ENGINEERING AND CAS","NECTAR CROP SCIENCES PRIVATE L","NECTAR MEDIPHARMA PVT. LTD.","NEEDLE EYE PLASTIC INDUSTRIES PVT LTD","NEEDLE INDUSTRIES INDIA PVT LT","NEEL AUTO PRIVATE LIMITED","NEEL INDUSTRIAL SYSTEMS LTD","NEEL METAL PRODUCTS LIMITED","NEEL WIRE INDUSTRIES","NEELAM AQUA & SPECIALITY CHEM PRIVATE LIMITED","NEELIKON FOOD DYES AND CHEM.","NEETA ENTERPRISES","NEETA LOGISTICS","NEHA PLASTOWARE","NEKKS INDUSTRIES PVT.LTD.","NELES INDIA PRIVATE LIMITED","NEMI PHARMA CHEM","NEO","NEO CARBONS PRIVATE LIMITED","NEO FOODS P LTD","NEO PACK PLAST INDIA PVT LTD","NEO RUBBER PRODUCTS ","NEO STRUCTO CONSTRUCTION PRIVATE LIMITED","NEO WHEELS LTD","NEOCHEM INDUSTRIES","NEOCHEM TECHNOLOGIES PRIVATE LIMITED","NEOGEN CHEMICALS LIMITED - SEZ","NEOLOGIC ENGINEERS PRIVATE LIMITED","NEOMEDIISPECS PRIVATE LIMITED","NEOSPARK DRUGS & CHEMICALS PRI","NEOTISS LIMITED","NEOTRONIKS PRIVATE LIMITED","NEPTUNE ORTHOPAEDICS","NEPTUNE TEXTILES MILLLS PVT LTD","NERI LIGHTING (INDIA) PRIVATE LIMITED","NEROFIX PRIVATE LIMITED","NEROKEM SPECIALITY PRODUCTS","NESCO LIMITED (INDABRATOR DIV.)","NESTOR CONVERTERS PRIVATE LIMITED","NETMATRIX CROP CARE LIMITED","NETSURF COMMUNICATIONS PRIVATE LIMITED","NETWORK TECHLAB INDIA PRIVATE LIMITED","NEUMATICA TECHNOLOGIES PVT LTD","NEURON ENERGY","NEUTRAL GLASS & ALLIED INDUSTR","NEVA GARMENTS LTD","NEW (INDIA) IMAGING INDUSTRIES PVT LTD","NEW AGE FIRE FIGHTING CO. LTD.","NEW AGE FORMULATION","NEW ALLENBERRY WORKS","NEW BHARAT FIRE PROTECTION SYS","NEW BHARAT SCRAP CO","NEW COLTAR CHEMICALS MFG.CO","NEW INDIA ELECTRICALS LIMITED","NEW LIFE LABORATORIES PVT LIMITED","NEW WORLD PAINTS PVT LTD","NEWAGE ENGINEERS ","NEWAGE INDUSTRIES","NEWAY PAINTS & BUILDING MATERIALS (I) PVT LTD","NEWLY WEDS FOODS INDIA PVT LTD","NEWNIK LIFECARE PVT LTD","NEWSTECH INDIA PVT LTD","NEWTEC CABLES","NEWTRONIC LIFECARE EQUIPMENT PRIVATE LIMITED","NEXION INTERNATIONAL PVT LTD","NEXON PAINTS PRIVATE LIMITED","NEXRISE PUBLICATIONS PRIVATE LIMITED","NEXT GEN","NEXTECH ENGINEERING","NEXTG APEX INDIA PRIVATE LIMITED","NEXTGEN PRINTERS PRIVATE LIMITED","NEXTILE MARBOSYS PRIVATE LIMITED","NEXUS IMPEX","NEXXUS","NF FORGINGS PVT LTD","NGL FINE CHEM LIMITED","NGL FINE-CHEM LIMITED.. ","NHB BALL & ROLLER LIMITED","NHC FOODS LIMITED","NICCO ENGINEERING SERVICES LTD","NICE CHEMICALS PVT LTD","NICE LOGISTICS","NICHINO CHEMICAL INDIA PRIVATE LIMITED ","NICHINO INDIA PRIVATE LIMITED","NICO EXTRUSIONS LIMITED","NICO INDUSTRIAL SOLUTIONS","NIDEC INDUSTRIAL AUTOMATION PV","NIDHI SULPHONATES","NIDO MACHINERIES PVT LTD.","NIF MECHANICAL WORKS PVT LTD","NIGAM ENTERPRISES","NIHAL IMPORT","NIHON PARKERIZING INDIA PVT LT","NIKEE ENTERPRISES","NIKHIL ADHESIVES LTD","NIKHIL FURNITURES","NIKNAM CHEMICALS PVT LTD","NIKOO PRECISION CAST PVT. LTD.","NIKSHE MULTIPRODUCTS PRIVATE L","NIKUL ENGINEERING PRIVATE LIMITED","NILKAMAL","NILKAMAL BITO STORAGE SYSTEMS ","NILKAMAL LIMITED","NILONS ENTERPRISES PRIVATE LIMITED","NILSAN NISHOTECH SYSTEMS P LTD","NILU LAMINATES","NIMBUS PIPES LIMITED","NINA PERCEPT PVT LTD","NINESKY TECHNOLOGY & INDUSTRIAL PVT. LTD","NIPB INDUSTRIAL BRUSHES (I) PRIVATE LIMITED","NIPL ENGINEERING PRIVATE LIMITED","NIPMAN FASTENER INDUSTRIES P.L","NIPPON EXPRESS (INDIA) PRIVATE LIMITED","NIPPON PAINT (INDIA) PRIVATE","NIPPON PAPER FOODPAC PVT. LTD.","NIPRA INDUSTRIES PVT.LTD.","NIPRO MEDICAL (INDIA) PRIVATE LIMITED","NIRBHAY RASAYAN PVT.LTD.","NIRMA LTD.","NIRMAL AGENCIES","NIRMAL DURGS AND SURGICALS","NIRMAL INDUSTRIAL CONTROLS","NIRMAL TRADERS","NIRMALA MONOFIL PVT LTD","NIRMALA POLYROPES INDIA PRIVATE LIMITED","NIRUPAM ENTERPRISE","NIRVAN INDUSTRIES","NISH TECHNO PROJECTS PRIVATE LIMITED","NISHANT AROMAS","NISHANT MOULDINGS PVT. LTD.","NISHANT ORGANICS PRIVATE LIMITED","NISHI ENTERPRISES ","NISHI MEDCARE","NISSAN CLEAN INDIA PVT. LTD.","NISWIN ENTERPRISES","NITCO LIMITED","NITIKA PHARMACEUTICAL SPECIALI","NITIN ALLOYS GLOBAL LIMITED","NITIN CASTINGS LIMITED","NITIN DYE CHEM PVT LTD","NITON VALUE INDUSTRIES PVT LTD","NITTA GELATIN INDIA LIMITED","NITYA ELECTROCONTROLS PRIVATE LIMITED","NIX POLYMERS","NOBLETEX INDUSTRIES LTD.","NOCIL LIMITED","NOIDA ELECTRONICS","NONE","NOREX FLAVOURS PRIVATE LIMITED","NORMET INDIA PRIVATE LIMITED","NORTH WEST INDUSTRIES PVT LTD","NORTHERN ALLOYS BHAVNAGAR LIMI","NORTHLAND RUBBER MILLS","NORTON GASKETS PRIVATE LIMITED","NOTIONAL SPECIALITIES PRODUCTS","NOVA CHEMICALS","NOVA IRON AND STEEL LIMITED","NOVA LOGISTICS SOLUTIONS","NOVA PUBLICATIONS","NOVA TECHNOCAST P LTD","NOVAPHENE SPECIALITIES PRIVATE LIMITED","NOVATIC COATINGS PRIVATE LIMITED","NOVEL TISSUES PVT LTD","NOVODIT ENTERPRISES","NOVOZYMES","NOVOZYMES SOUTH ASIA PRIVATE LIMITED","NPF POLYFILMS PVT. LTD.","NPL BLUESKY AUTOMOTIVE PRIVATE","NPL BLUESKY AUTOMOTIVE PVT LTD","NPR AUTO PARTS MFG INDIA PVT L","NRB INDUSTRIAL BEARINGS LIMITED","NRG ENTERPRISES","NSF PHARMA PRIVATE LIMITED","NSL TEXTILES LIMITED","NSP FILTERS","NSPIRA MANAGEMENT SERVICES PVT LTD","NSVP INDUCTION CASTING PVT LTD","NTC INDUSTRIES LTD.","NUBIOLA INDIA LTD","NU-CORK PRODUCTS PVT LTD. ","NUEVO POLYMERS PVT. LTD.","NURAY CHEMICALS PRIVATE LIMITED","NUTAN CHEMICALS","NUTRA CARE INTERNATIONAL","NUTRITI INGREDIENTS PVT LTD","NU-WAY HEATRANSFER PVT. LTD.","NV LABELS","OASIS CONTRIVER PRIVATE LIMITED","OASIS ENTERPRISES","OASIS FINECHEM","OBA SPECIALITY CHEMICALS","OBLUM ELECTRICAL","OCL INDIA LIMITED","OCTOGEN TRADELINKS INDIA PVT L","ODYSSEY ORGANICS PRIVATE LIMITED","OEC RECORDS MANAGEMENT COMPANY","OERLIKON FRICTION SYSTEMS","OFFSHORE PETROLUBES (OPC) PRIVATE LIMITED","OHM PACKAGING PVT LTD","OIL & CHEMICALS-RETAIL","OILTECH LUBRICANTS PRIVATE LIMITED","OKAY FERN PRECISION CASTING PRIVATE LIMITED","OLAM AGRO INDIA LTD","OLEO BUFFERS INDIA PRIVATE LIMITED","OLYMPIC OVERSEAS","OLYMPIC SYNTHETIC SACKS PVT LT","OM BALAJI INORGO MEDE PVT LTD","OM BIOMEDIC PVT.LTD","OM CERAMICS","OM CHEMICALS","OM DISTRIBUTORS","OM FLEX INDIA","OM GALAXY PRECISION ","OM INTERNATIONAL COURIER & CAR","OM OIL & FLOUR MILLS LIMITED","OM OPTEL INDUSTRIES PRIVATE LIMITED","OM PHARMACEUTICALS LIMITED","OM POLYMERS","OM SAI INDUSTRIES","OM SIDDH VINAYAK IMPEX PVT.LTD","OM TITANATES","OM TRADING COMPANY","OMEGA APPLIANCES PRIVATE LIMITED","OMEGA ICEHILL PVT LTD.","OMEGA KEMIX P LTD","OMEGA LABORATORIES","OMEGA PRODUCTS","OMKAR AGENCY","OMKAR FINE ORGANICS PVT LTD.","OMKAR INDUSTRIES","OMKAR MARKETING","OMME ELECTROMECH PRIVATE LIMITED","OMNI TECH ENGINEERING","OMNOVA SOLUTIONS INDIA PVT. LT","OMSAI PRINTS","OMTECH CHEMICALS INDUSTRIES","ONE CLICK INNOVATIONS PRIVATE LIMITED","ONE CLICK PACKAGING","ONE DREAM FOODS","ONKAR ENGINE & GENERATOR PRIVATE LIMITED","ONKAR EXIM PRIVATE LIMITED","ONLY MAT","ONLY RETAIL PVT. LTD","ONS IMPEX TRADERS PRIVATE LIMITED","OORJA ON MOVE INFRA PVT. LTD","OPAQUE CERAMICS .PVT.LTD","OPTIMA LIFE SCIENCES PRIVATE L","OPTOMECH ENGINEERS PVT LTD","ORAFOL INDIA PRIVATE LIMITED","ORANGE BUSINESS SERVICES INDIA","ORANGE O TEC PVT LTD","ORANGE POWER T&D EQUIPMENT PVT","ORANGE PUMPS AND MOTORSS","ORBINOX INDIA PVT LTD","ORBIT BEARINGS INDIA PRIVATE LIMITED","ORBIT COATINGS PVT LTD","ORBIT ELECTRODOMESTICS (INDIA) PVT LTD","ORBIT PHARMA LABORATORIES","ORBIT PUMPS & SYSTEMS PVT.LTD.","ORBITAL SYSTEMS (BOMBAY) PRIVATE LIMITED","ORCHARD BRANDS PRIVATE LIMITED","ORCHEV PHARMA PRIVATE LIMITED","OREN HYDROCARBONS PVT LTD","ORGAEARTH CLEANSOL PRIVATE LIMITED","ORGANIC AGRO FOODS","ORGANIC COATING LIMITED","ORGANIC INDUSTRIES PVT. LTD.","ORGATMA ORGANIC SCIENCE PRIVATE LIMITED","ORICON ENTERPRISES LTD","ORIENT ABBRASIVES LIMITED","ORIENT ABRATECH PVT LTD","ORIENT BELL LTD","ORIENT ELECTRIC LIMITED","ORIENT LINKS P LTD","ORIENT PRESS LIMITED","ORIENT RUBBER PVT LTD","ORIENT SYNTEX ( PROP. APM INDU","ORIENTAL ","ORIENTAL AROMATICS LTD","ORIENTAL CARBON & CHEMICALS LI","ORIENTAL CONTAINERS LIMITED","ORIENTAL ELECTRICAL COMPONENTS PVT.LTD.","ORIENTAL LOTUS HOTEL SUPPLIES ","ORIENTAL VENEER PRODUCTS LIMITED","ORIGAMI","ORIGAMI CELLULO PRIVATE LIMITE","ORIGIN FORMULATION PRIVATE LIMITED","ORIGIN LOGITICS PVT LTD","ORIO SHANGHAI COLOURS PVT LTD","ORION APPAREL TRIMS PVT LTD","ORIONE HYDRAULICS PVT LTD","ORIX PACKAGING PRIVATE LIMITED","ORKA BEAN BAGS","ORNET INTERMEDIATES PVT. LTD.","OROCHEM INDIA PRIVATE LIMITED","ORPAK SYSTEMS INDIA PRIVATE LIMITED","OSAKA RUBBER PRIVATE LIMITED","OSAW INDUSTRIAL PRODUCTS PVT L","OSBORN LIPPERT (INDIA) PRIVATE LIMITED","OSHO FLEXIBLES LIMITED","OSHO INDUSTRIES LIMITED","OSRAM LIGHTING PVT LTD","OSTRICH MOBILITY INSTRUMENTS PVT.LTD.","OSWAL EXTRUSION LTD","OSWAL INDUSTRIES LTD. UNIT-III","OSWAL MACHINERY LIMITED","OVERSEAS POLYMERS PVT LTD","OZONE ENTERPRISES","OZONE POLYFORM PVT. LTD.","P & P METALLOYS PVT LTD","P & R AGROFOODS PRIVATE LIMITE","P AND C ANIMAL HEALTH LLP","P C B DYEING PRIVATE LIMITED","P CHHOTALAL MANUFACTURERS & EX","P D NAVKAR BIOCHEM PVT LTD","P I INDUSTRIES LIMITED","P J MARGO ","P L INDUSTRIES","P P BAFNA VENTURES PRIVATE LIMITED","P P I PUMPS PVT LTD","P P PATEL AND COMPANY","P R FASTENERS PRIVATE LIMITED","P S TEA INDUSTRIES","P T INVENT INDIA PVT LTD","P.I. INDUSTRIES LTD.","P.M STEEL (P) LTD","P.M. GARMENT EXPORTS PVT. LTD.","P.P.I SYSTEMS","P.YESH INDUSTRIES","P2 POWER SOLUTIONS PVT LTD","PAAKHI APPERALS","PAATRAM TABLEWARE LLP","PAB ORGANICS PVT. LTD","PACE POWER SYSTEMS PRIVATE LIMITED","PACIFIC DIGITAL","PACIFIC HARISH INDUSTRIES LTD","PACIFIC INDUSTRIES","PACIFIC PRODUCTS AND SOLUTIONS","PACIFIC TECHNOPRODUCTS INDIA PRIVATE LIMITED","PACK PRINT INDUSTRIES IND. PVT","PACK TECH MATERIALS PVT LTD","PACKAGING INDIA PVT LTD","PACKKOID INDUSTRIES PVT LTD","PACKTECH MATERIAL PVT LTD ","PADAMCHAND MILAPCHAND JAIN","PADARSH PHARMACUETICALS P LTD","PADMA BRUSH COMPANY","PADMAJA LABORATORIES PRIVATE L","PADMANABH ALLOYS & POLYMERS LT","PADMINI ENTERPRISES","PADMINI INDUSTRIES LIMITED","PAGARIYA FOOD PRODCUTS P. LTD","PAHARPUR-3P","PAI CRISTAL INDIA PVT LTD","PAL SHELLCAST PVT LTD","PAL TRADING COMPANY","PALADIN PAINTS & CHEMICALS PVT.LTD","PALAK PLASTIC PVT LTD","PALASH INCENSE HOUSE","PALI HILLS BREWERIES PRIVATE LIMITED","PALLAV CHEMICALS & SOLVENTS PRIVATE LIMITED","PALOMA HYGIENZ","PALOMA TURNING CO. PVT. LTD.","PAMPA SERVICES","PAN DRUGS LIMITED","PANACEA BIOTEC LTD","PANAMA PETROCHEM LTD.","PANASONIC ENERGY INDIA CO.LTD.","PANASONIC LIFE SOLUTIONS INDIA PVT LTD","PANCHVAKTRAM ENGINEERING PRIVATE LIMITED","PANCHVATI VALVES AND FLANGES PRIVATE LIMITED","PANDORA CERA WORLD","PANDROL RAHEE TECHNOLOGIES PVT","PANKAJ ENTERPRICES","PANKAJ INTERNATIONAL","PANNA CHEMICAL & SOLVENTS PVT LTD","PANOLI INTERMEDIATES (INDIA) PRIVATE LIMITED","PAONA CHEMPRO PRIVATE LIMITED ","PAPER & LABEL PRODUCTS INDIA PVT. LTD.","PAPERGRID INDUSTRIES","PAPPAD-RETAIL","PAR COMPOUNDS","PAR DRUGS AND CHEMICALS LTD.","PARA PRODUCTS PVT LTD.","PARADIGM COATINGS PVT  LTD","PARADISE APIARY FARM","PARADISE PACKAGING PVT LTD","PARAG COPY GRAPH PVT.LTD.","PARAG DYE STUFF","PARAG MILK FOODS LTD.","PARAG PERFUMES","PARAGAON TAPES","PARAGON INDUSTRIES","PARAGON POLYMER PRODUCTS PRIVATE LIMITED ","PARAM CORPORATION PRIVATE LIMITED","PARAM SUKH ENTERPRISES","PARAMONE INDUSTRIES PRIVATE LIMITED","PARAMOUNT ART PRINTS PVT. LTD.","PARAMOUNT CONDUCTORS LTD","PARAMOUNT COSMETICS INDIA LTD","PARAMOUNT MINERALS AND CHEMICALS","PARAMOUNT POLISH PROCESSORS PL","PARAMTRONICS - MUM","PARAS AGRO PLAST PVT LTD","PARAS METALLURGICALS","PARAS MOTOR MFG CO","PARAS ORGANICS PVT LTD ","PARAS OXIDE PRIVATE LIMITED","PARASON MACHINERY INDIA PRIVATE LIMITED","PARDES QUICK FOODS & DEHYDRATION PRIVATE LIMITED","PARDESH DEHYDRATION COMPANY","PAREKH INTEGRATED SERVICES PVT LTD","PAREXGROUP CONSTRUCTION","PARIJAT INDUSTRIES INDIA PRIVATE LIMITED","PARISHRAM HOME APPLIANCE","PARIXIT IRRIGATION LIMITED","PARJANYA PAPER PRODUCTS","PARKSONS PACKAGING LTD.","PARLE INTERNATIONAL PVT LTD","PARLE PRODUCT PVT. LTD.","PARMESHWAR BRASS PRODUCTS","PARNAMI PRODUCTS","PARRY ENTERPRISES INDIA LTD TUFLEX INDIA DIV","PARSHVA KITCHENAID CO","PARSHVA TECHNO PRODUCTS","PARSHWA ENTERPRISES","PARSHWANATH COLOURCHEM","PARSHWNATH DYECHEM INDUSTRIES ","PARTH METALS","PARTS TRADING CORPORATION","PARUL EXPORT","PASAND BIOTECH","PASAND DRILLING FLUID TECHNOLO","PASAND SOAP & CHEMICAL INDUSTR","PASAND SPECIALITY CHEMICAL","PASCAL INDUSTRIAL EQUIPMENTS MFG AND CONSULTANCY P","PASHUPATI LAMINATORS PVT LTD","PASHWADEEP PETROCHEM","PASSION BEKERS PVT LTD","PASSION INDULGE PVT. LTD.","PASUPATI INDUSTRIES","PASUPATY SPRINGS PVT LTD","PATANJALI PARIVAHAN PRIVATE LI","PATCO PRECISION COMPONENTS PRIVATE LIMITED","PATCOS COSMETICS INDIA PVT LTD","PATEL BRASS WORKS PVT.LTD.","PATEL CHEM SPECIALITIES PVT.LT","PATEL STRAP INDUSTRIES","PATEL TRADING CORPORATION","PATELMECH INDIA","PATERSONS LUBRICANT INDIA PRIVATE LTD","PATHOZYME PLAST","PATIL RAIL INFRASTRUCTURE PRIV","PATKAR EXTRUSIONS PVT. LTD.","PATRONAGE FILTEX PRIVATE LIMITED.","PATSPIN INDIA LIMITED","PAUL & CO.","PAUL SALES PRIVATE LIMITED","PAXAL PAPER & CARDS","PAXCHEM LIMITED ","PAYAL ENGINEERS","PCI PEST CONTROL PRIVATE LIMITED","PCK BUDERUS INDIA SPECIAL STEELS PVT LTD.","PCMX HYGIENE PRODUCTS (P)LTD","PCP CHEMICALS PVT LTD","PEARL GLOBAL INDUSTRIES LIMITED","PEARL INTERNATIONAL","PEARL POLYMERS","PEARL THERMOPLAST PRIVATE LIMITED","PECOX INTERNATIONAL","PEDDINGTON LUBRICANTS & COATINGS","PEEKAY AGENCY PVT LTD","PELICAN AUTOMOBILE SPARES","PELICAN EARTHMOVING SPARES CO","PELICAN POLY AND PALLETS PVT LTD","PELICAN ROTOFLEX PVT LTD","PELL TECH HEALTHCARE PRIVATE LIMITED","PELLAGIC FOOD INGREDIENTS PRIVATE LIMITED","PELSTRA MANUFACTURING CO","PENDEN CEMENT AUTHORITY LIMITE","PENGG USHA MARTIN WIRES PVT. L","PENNAR INDUSTRIES LIMITED","PENSOL INDUSTRIES LIMITED","PENTA FREIGHT PVT LTD","PENTAGAON PLASTICS PVT LTD","PEP CEE PACK INDUSTRIES","PEPS INDUSTRIES PVT. LTD.","PERFECT COLOURANTS & PLASTICS ","PERFECT PACKAGING","PERFECT TURNERS","PERFECTO  TAPES  LTD","PERFECTO TAPES LIMITED","PERFECTT SERVICES (MADRAS)","PERFO CHEM (INDIA) PVT LTD","PERFORMANCE PRODUCTS AND SERVICES","PERFUNOVA INTERNATIONAL LTD","PERI (INDIA) PVT LTD","PERMA CONSTRUCTION AIDS PRIVATE LIMITED","PERMESHWAR CREATION PVT LTD","PERMESHWAR FASHION IMPEX PVT. ","PERMIONICS GLOBAL TECHNOLOGIES LLP","PERMIONICS MEMBRANES PVT LTD","PEST CONTROL PRIVATE LIMITED","PEST KARE (INDIA) PRIVATE LIMITED","PETLAD MAHAL AROGYA MANDAL PHA","PETRO VALVES PVT LTD","PETRONA INDUSTRIES","PETRONAS LUBRICANTS","PETRONET LNG LTD","PEW ENGINEERING PVT. LTD.","PFIZER LIMITED ","PG INFRASTRUCTURE","PHARM PRODUCTS PRIVATE LIMITED","PHARMA ENGINEERS","PHARMA FOOD","PHARMA IMPEX LABORATORIES PVT LTD","PHARMA SYNTH FORMULATIONS LTD","PHARMA TRADE","PHARMACEUTICAL INSTITUTE OF IN","PHARMACHEM","PHARMALAB INDIA PVT LTD","PHARMANZA INDIA PVT. LTD.","PHARMATECH PROCESS EQUIPMENTS","PHARMAX INDIA PRIVATE LIMITED","PHARMAZELL VIZAG PRIVATE LIMITED","PHARMED LIMITED","PHBB VALVES PRIVATE LIMITED","PHENO ORGANIC LIMITED","PHILODEN AGROCHEM PVT. LTD","PHINE KEMIKALS INDIA","PHOENIX CHEMICALS","PHOENIX CONVEYOR BELT INDIA PRIVATE LIMITED","PHOENIX FLEXIBLES PVT.LTD.","PHOENIX INDUSTRIES PVT LTD","PHOENIX MECANO INDIA PRIVATE LIMITED","PHOENIX SURGICALS INDIA PVT LT","PHOENIX UDYOG PVT LTD","PHYCOLINC TECNOLOGIES PVT LTD","PHYTO MARKETING PRIVATE LIMITED","PI INDUSTRIES LIMITED","PIAGGIO VEHICLES PRIVATE LIMIT","PICCOLO MOSAIC LIMITED","PIDILITE INDIA LTD-SECONDARY MOVEMENT","PIDILITE INDUSTRIES ","PILANI ENVIROTECH PRIVATE LIMITED","PILANI UDYOG","PILLAR CHEMICALS PVT LTD","PILOT INDUSTRIES LTD.","PINNACLE ELECTRODES PVT LTD","PINNACLE GENERATORS","PINNACLE INNOVATORY SERVICES PVT LTD","PINTU OIL","PIOMA INDUSTRIES","PIONEER ADHESIVES PRIVATE LIMITED","PIONEER CROPSCIENCE & ALLIED INDUSTRIES","PIONEER ENTERPRISES (INDIA) PRIVATE LIMITED","PIONEER HYGIENE INDUSTRIES LLP","PIONEER JELLICE INDIA PRIVATE LIMITED","PIONEER KITCHENWARE","PIONEER NUTS AND BOLTS  PVT LT","PIONEER POLYFILMS(A UNIT OF PIONEERHYGIENE INDLLP)","PIPAL SOLARTHERM PVT LTD","PIRAMAL GLASS PRIVATE LIMITED","PIYANSHU CHEMICALS PVT.LTD.","PKN MOULDERS PRIVATE LIMITED","PKP COMPONENTS","PLANET POWER TOOLS PVT. LTD.","PLANET VYAPAAR PVT.LTD","PLASTENE INDIA LTD.","PLASTIBLENDS INDIA LTD","PLASTIC & LEATHER CLOTH STORE","PLATINUM INDUSTRIES LLP","PLATINUM INDUSTRIES PRIVATE LIMITED","PLATINUM LOGISTICS","PLENTEOUS PHARMACEUTICALS LTD","PLG INTERNATIONAL","PLUSS ADVANCED TECHNOLOGIES PV","PMA AQUACARE PRIVATE LIMITED","PMC RUBBER CHEMICALS INDIA PRI","P-MET HIGH-TECH.CO.P.LTD","PMS CREATIONS","POCL ENTERPRISES LIMITED","PODDAR PIGMENTS LIMITED","PODDAR PIGMENTS LTD","POLARIS CABLES & WIRES PVT LTD","POLIGOF-MICRO HYGIENE (INDIA) PRIVATE LIMITED","POLMANN INDIA LIMITED","POLY PROFILES INDIA","POLYCAB WIRES PVT LTD","POLYCARE WOOD COATINGS","POLYCROMAX COMPOUNDS","POLYE RUBB INDUSTRIES","POLYENE GENERAL INDUSTRIES PVT. LTD.","POLYFIBERE INDUSTRIES PVT LTD.","POLYGEL INDUSTRIES LIMITED","POLYHOSE INDIA PVT LTD ","POLYHOSE TOFLE PVT. LTD.","POLYHYDRON SYSTEMS PRIVATE LIMITED","POLYLINK POLYMERS INDIA LTD","POLYMA COMPANY","POLYMER INDUSTRIES INDIA LIMITED","POLYPLEX CORPORATION LIMITED","POLYSIL IRRIGATION SYSTEMS PVT LTD","POLYTRANS LEMICOATS PVT LTD","PON PURE CHEMICAL INDIA PVT LT","POOJA DYE CHEM INDUSTRIES","POOJA ENGINEERING CO","POOJA PLASTIC INDUSTRIES","POOJA TECHNOCAST","POOJAN CHEMICALS","POOMKUDY AGENCIES (P) LTD","POOMKUDY ZAAN PRIVATE LIMITED","POONA FINE CHEM P LTD ","POP GUJARAT","POPAT RAJA & SONS","POPULAR ENGINEERING CORPORATION","POROCEL INDIA LIMITED","PORUS LABORATORIES PVT LTD","PORWAL AUTO COMPONENTS LIMITED","PORWAL INDUSTRIES","PORWAL TAR PRODUCTS (P) LTD","POSEIDON BIOTECH","POSEIDON ENTERPRISES","POSITECH SOLUTIONS WORLDWIDE","POWER BUILD PRIVATE LIMITED","POWER CONTROL ELECTRO SYSTEMS PRIVATE LIMITED","POWER FLEX CABLES","POWER GRID CORPORATION OF INDIA LTD","POWER INDUSTRIES","POWER MATIC PRODUCTS","POWER PLAZZO PRIVATE LIMITED","POWER TAP MECH ELEC INDUSTRIES","POWERBAND IND. PVT. LTD.","POWERICA LTD","POWERJET APPLIANCES","POWERMAX RUBBER FACTORY","PPG ASIAN PAINTS PVT LTD","PPI SYSTEMS","PRABHA ENGINEERING PVT LTD","PRABHAT DAIRY PVT LTD ","PRABHU POLY COLOR LIMITED","PRACHIN CHEMICAL","PRADEEP ENTERPRISES","PRADIP ENGINEERING","PRAGAASHRI SYSTEMS","PRAGALBH OVERSEAS","PRAGATI CHEMICALS LTD.","PRAGATI ELECTRICALS PVT LTD","PRAGATI ELECTROCOM (P) LTD","PRAGATI ENGINEERING BELGAUM PRIVATE LIMITED","PRAGATI GLASS PVT. LTD.","PRAGATI POLYMERS","PRAJAKT CHEMICAL","PRAKASH CHEMICALS PVT LTD","PRAKASH FLEXIBLES PVT LTD","PRAKRUTHI HEALTH PRODUCTS","PRANAV BITEK AGROTECH PRIVATE LIMITED","PRANERA SERVICES AND SOLUTIONS PRIVATE LIMITED","PRANIC ENTERPRISES","PRAPBHAKAR PRINTPACK PVT LTD","PRAPULLA POLYMERS","PRARAS BIO SCIENCES PRIVATE LIMITED","PRASAD GROUP","PRASAD INTERNATIONAL  PVT. LTD","PRASHANT CASTECH PVT.LTD","PRASHANT CASTINGS PVT. LTD","PRASHANT ENTERPRISE","PRASHANT PACKAGING INDUSTRIES","PRASHANTH PROJECTS LIMITED","PRASOL CHEMICALS PRIVATE LIMITED","PRATAP TEXCHEM PRIVATE LIMITED","PRATHAM COMPOSITE SOLUTIONS","PRATHAM SHIPPING & LOGISTICS PRIVATE LIMITED","PRATHAMESH TECHNOLOGY AND INDUSTRIES LLP","PRATHAPSINH & CO ","PRATHMESH DYE CHEM PVT.LTD.","PRATIK ALLOYS PVT LTD","PRAVEEN AROMA PRIVATE LIMITED","PRAVESHA INDUSTRIES PRIVATE LIMITED","PRAVIS KRUSHI RASAYAN","PRAYAG POLYTECH PVT LTD.","PRAYAS ENGINEERING LIMITED","PRAZISION DEEP DRAW PRIVATE LIMITED","PRECIA MOLEN INDIA PRIVATE LIMITED","PRECISE CAST","PRECISE CHEMIPHARMA PVT LTD","PRECISE ELECTRICALS","PRECISE VACCUM SYSTEMS PRIVATE LIMITED","PRECISION ADHESIVE TAPES PRIVATE LIMITED","PRECISION AUTOMATION AND ROBOTICS IND IA LTD.","PRECISION COMPONENTS","PRECISION DEEP DRAW","PRECISION ENGINEERING & FABRICATION","PRECISION FOILS PVT LTD","PRECISION GASIFICATION SERVICES PRIVATE LIMITED","PRECISION GLOBAL SPRINGS PRIVATE LIMITED","PRECISION METALS","PRECISION PARTS","PRECISION WIRES INDIA LTD","PRECITEX EQUIPMENTS PVT.LTD.","PREEMA PACKAGING","PREETHI KITCHEN APPLIANCES PVT","PREFECT PACKAGING PVT LTD","PREM DYECHEM IND. P. LTD ","PREM INDUSTRIES","PREMCO GLOBAL LIMITED","PREMIER AGRO NUTRIETS","PREMIER ALLOYS & CHEMICALS PRIVATE LIMITED","PREMIER POLYFILM LTD","PREMIER POLYMER INDUSTRIES","PREMIER SILKS","PREMIER SOLVENTS PVT LTD","PREMIUM LAMINATORS PVT LTD.","PREMIUM POLYMERS LTD","PREMIUM SPECIALITY PAINTS PRIVATE LIMITED","PREMKRISHNA ASSOCIATES","PREMSONS BAZAAR  ","PREMSONS PLASTICS PRIVATE LIMITED","PRESFIELD INDUSTRIES","PRESIDENT CLOTHING COMPANY","PRESIDENT ENGINEERING WORKS","PRESS GEL INSULATIONS PVT LTD","PRESSEN WERK","PRESSGEL INSULATIONS PRIVATE LIMITED","PRESSMACH ENGINEERS PRIVATE LI","PRESSMACH INFRASTRUCTURE PVT LTD","PRESTAR INFRASTRUCTURE PROJECTS LTD","PRESTIGE BAKELITE MOULDERS","PRESTIGE INTERIO CONCEPTS PVT ","PRESTIGE PURSUITS ( P ) LTD","PRESTON INDIA P LTD","PREVAIL CASTING PVT.LTD.","PRICE PUMPS PRIVATE LIMITED","PRIDE FURNISHING SERVICE PVT LTD","PRIJAI HEAT EXCHANGERS PVT LTD","PRIMA ABRASIVES","PRIMA CHEMICALS","PRIMA PLASTICS LIMITED","PRIMA VETCARE PVT LTD.","PRIME AGRO FOOD PRODUCTS.","PRIME BULK","PRIME CABLE INDUSTRIES PRIVATE LIMITED","PRIME CHEM INDIA","PRIME GRAPHITE PRIVATE LIMITED","PRIME INDUSTRIES - NASIK","PRIME METAL","PRIME PAPYRUS PRODUCTS PRIVATE LIMITED","PRIME PROCESSORS","PRIME PROGRESSION ICOM (INDIA)","PRIME STEEL","PRIME TELE EXTRUSIONS LTD","PRIME VULCAN SYSTEMS","PRIME WIRE PVT LTD","PRIMESEAL STRAPS PVT. LTD.","PRIMEWEAR HYGINE(INDIA) PRODUCT LTD","PRIMUS GLOVES PRIVATE LIMITED","PRINCE CARE PHARMA P. LTD","PRINCE CORP PRIVATE LTD","PRINCE PIPES AND FITTINGS LTD","PRINCE SUPPLICO","PRINT CITI","PRINT IT INDIA PRIVATE LIMITED","PRINT N PACK PVT. LTD.","PRINT VISION","PRINTANIA OFFSET PVT LTD","PRINTEC INDUSTREIS","PRINTEK WAYS","PRINTERS DEN PACKAGING","PRINTWELL OFFSET","PRISM JOHNSON LIMITED","PRISMATIC ENGINEERING PRIVATE LIMITED","PRISTINE CARE PRODUCTS PVT LTD","PRISTINE COMMERCIALS PVT LTD","PRISTINE PAINTS ","PRITHVI INNER WEARS","PRITI INDUSTRIES","PRIVI ORGANICS INDIA LIMITED","PRIYA CHEMICALS","PROCESSO PLAST ENTERPRISE PVT.LTD","PROCON ENGINEERS","PROCONNECT SUPPLY CHAIN SOLUTIONS LIMITED","PROFAB ENGINEERS PVT LTD","PROFLO SYSTEMS PRIVATE LIMITED","PROGRESSIVE LAMINATIONS","PROK DEVICES PRIVATE LIMITED","PROKLEAN TECHNOLOGIES PVT LTD","PROMAC ENGINEERING INDUSTRIES LTD","PROMAS ENGINEERS P LTD","PROMEDA CHEMOTRADE (OPC) PRIVATE LIMITED","PROMENS INDIA PVT LTD.","PROMINANCE WINDOW SYSTEMS","PROMINENT SCIENTIFIC & ENGINEE","PROMISE ELECTRICAL INDUSTRIES","PROMI-VAC PUMPS, PLANTS & SYST","PROMOT ELECTRIC","PROMPT EQUIPMENTS PRIVATE LIMITED","PROOFEX PACKAGING PVT. LTD.","PROPEL INDUSTRIES PRIVATE LIMITED","PROPUS INC","PROPYLON PRODUCTS","PROSEAL CLOSURES LTD","PROSEM TECHNOLOGY INDIA PVT. LTD.","PROSYS SERVICES PVT. LIMITED","PROTECH APPLIANCES PVT LTD","PRO-TECH SALES","PRO-TECHKT HEALTH AND SAFETY PRIVATE LIMITED","PROTEGO INDIA PRIVATE LIMITED.","PROTEK KORCHEM PRIVATE LIMITED","PROTOCHEM INDUSTRIES PVT LTD","PROVET PHARMA PVT LTD","PROVIK INDUSTRIES","PROVIMI ANIMAL NUTRITION INDIA","PRUDENCE PHARMACHEM","PS AUTO PVT LTD","PSAMIRCO TRADING PRIVATE LIMITED","PSK PHARMA PVT LTD","PTC INDUSTRIES LTD","PUJA MARKETING","PUKHRAJ ADDITIVES ","PUKHRAJ ENGINEERING & CHEMICAL","PUNJABI GHASITARAM HALWAI PVT LTD","PURAB PRINTERS","PURAV INDUSTRIES","PURE CHEMICALS CO","PURE TONERS AND DEVELOPERS PRIVATE LIMITED","PUREBITE PET FOODS PVT LTD.","PURITY POLYTUBES PRIVATE LIMITED","PUROHIT STEEL INDIA PVT LTD","PUROLITE INDUSTRIES","PURPLE EXIM ENTERPRISE","PUSHKAR INSULATION AND PACKAGI","PUSHKAR PHARMA","PUSHP ENTERPRISES","PUSHP SONS FIBROL PVT.LTD","PUSHPANJALI INDUSTRIES PRIVATE LIMITED","PUSHPSONS FIBROL P LTD","PUTZMEISTER CONCRETE MACHINES PRIVATE LIMITED","PUTZMEISTER INDIA PRIVATE LIMITED","PUZZOLANA MACHINERY FABRICATOR","PVC CONVERTERS INDIA PVT. LTD.","PVC PUMPS PRIVATE LIMITED","PYROTECH WORK SPACE SOLUTION P","PYROTEK INDIA PRIVATE LIMITED","Q & S LOGISTICS","Q E D KARES PACKERS PRIVATE LI","Q GREEN TECHCON PVT. LTD.","QREX FLEX PRIVATE LIMITED","QUAKER CHEMICAL INDIA PRIVATE ","QUALIKEMS FINE CHEM PVT. LTD.","QUALISURE PACKAGING LLP","QUALITY INDUSTRIES","QUALITY POWER ELECTRICAL EQUIPMENTS PVT LTD","QUALITY PROFILES PRIVATE LIMITED","QUALITY TOOLS & BEARING CENTRE","QUANTUM KNITS","QUEBEC PETROLEUM RESOURCES LTD.","QUESTA AGENCIES","QUICK FOODS CO","QUICKWAYS LOGISTICS","QUINTESSENCE FRAGRANCES P LTD ","QUTONE CERAMIC PRIVATE LIMITED","R AND R TEXTILE","R C PLASTO TANKS AND PIPES PRIVATE LIMITED","R CUBE MARKETING","R G AGENCIES","R G POLYMERS","R K BRASS INDUSTRIES","R K FEED EQUIPMENTS","R K LIGHTING PVT. LTD.","R K SYNTHESIS LIMITED","R L FINE CHEM","R M S CORPORATION","R M TRADING COMPANY","R N LABORATORIES","R P FANCY YARNS","R P INDUSTRIES","R P PRODUCTS PHARMA EQUIPMENT ","R P SHAH & SONS","R R CABEL GROUP","R R INDUSTRIES","R R KABEL LIMITED","R R PACKAGING","R S FOILS PRIVATE LIMITED","R S M AUTOKAST LIMITED","R V CORPORATION","R V ENTERPRISE","R V R MARKETING","R&R CONSULTING","R.B.AGARWALLA & CO.","R.D.BROTHERS","R.K. POLYMER","R.K.DEHYDRATION","R.K.SYNTHESIS LTD","R.M.G. PRODUCTS","R.N. SURESH TOOLS CAR.","R.NAGARDAS & CO.","R.P.ENGINEERING WORKS","RA CHEM PHARMA LTD","RA CHEMICALS","RAAG TECHNOLOGIES AND SERVICES","RAAJRATNA ELECTRODES PVT. LTD.","RAAJRATNA METAL INDUSTRIES LTD","RAAJRATNA STAINLESS PRIVATE LIMITED","RAAJRATNA VENTURES LTD","RAAMKAY CROP SCIENCE SOLUTIONS","RAAYA ENTERPRISES","RABWIN INDUSTRIES PRIVATE LIMITED","RACHNA PLASTISIZER","RADCOLEX INDIA PVT LTD","RADCOM PACKAGING PRIVATE LIMITED","RADDISUN PHARMA CHEM INDUSTRIES LTD","RADDISUN PHARMACEUTICALS","RADDISUN PHARMACHEM INDUSTRIES PRIVATE LIMITED","RADEO ENGINEERED COMPONENTS LLP","RADHA MADHAV CORPORATION LTD","RADHAKRISHNA FOODLAND PVT. LTD","RADHALAKSHMI METALLURGICALS","RADHASWAMI INDRUSTRIES","RADHE INSTRUMENTATION PRIVATE LIMITED","RADHIKA OPTO ELECTRONICS PVT.L","RADIALS INTERNATIONAL","RADIANT INDUS CHEM PRIVATE LIMITED","RADIANT METALS AND ALLOYS PVT LTD","RADIANT POWER PROJECTS","RADICI PLASTICS INDIA PRIVATE ","RADICO","RADIO FOODS AND BEVERAGES LLP","RAGHAV INDUSTRIAL PRODUCTS","RAGHAV LIFESTYLE PRODUCTS UNIT-II","RAGHAVENDRA AUTOMATION","RAGHAVENDRA AUTOMATION PRIVATE LIMITED","RAGI IMPEX PVT LTD ","RAHEE TRACKTECHNOLOGIES PRIVATE LIMITED","RAHIL FOAM PVT LTD","RAHSHREE SHOES","RAHUL CABLES PRIVATE LIMITED","RAHUL PHARMA","RAHUL SUGAR PRODUCTS","RAHUL TRADERS","RAINBOW EXPOCHEM","RAINBOW HEALTHCARE PRODUCTS","RAINCHEM INDIA PVT LTD","RAINCHEM SALES CORPORATION","RAJ AALUMINIUM","RAJ PACKAGING","RAJ PETRO SPECIALITIES P. LTD","RAJ PIONEER LABORATORIES (INDI","RAJ PLASTIC INDUSTRIES","RAJ PLASTIC PRODUCTS","RAJ POLYMERS","RAJ TRADING COMPANY","RAJA INDUSTRIES","RAJA SLAT PVT LTD","RAJAN TECHNOCAST P LTD.","RAJASTHAN MARBLES","RAJAT VINYLS PVT LTD","RAJDA INDUSTRIES & EXPORTS PRIVATE LIMITED","RAJDEEP ENERGIES PRIVATE LIMITED","RAJDEEP INDUSTRIAL PRODUCTS PRIVATE LIMITED","RAJDHANI FLOUR MILLS LTD","RAJESH BRASS PART INDUSTRIES","RAJESH ENTERPRISES","RAJESHWARI","RAJGURU INDUSTRIES","RAJHANS METALS PVT LTD","RAJIV PLASTIC INDUSTRIES","RAJIV PLASTICS PRIVATE LIMITED","RAJLAXMI INDUSTRIES","RAJMUDRA PACKAGING","RAJNI PAPER PRODUCTS","RAJOO ENGINEERS LIMITED.","RAJPIONEER LABORATORIES (INDIA","RAJPUTANA STAINLESS LIMITED","RAJRATAN INDUSTRIES PRIVATE LIMITED","RAJRATNA HANDLING SYSTEMS","RAJSHA CHEMICALS PRIVATE LIMIT","RAJSHREE SHOES","RAKESH ENGINEERING WORKS","RAKHUMAI ENTERPRISES","RAKUL PET CARE PRIVATE LIMITED","RALLIS INDIA LIMITED","RALSON INDIA LIMITED","RAM CORRUGATED BOXES PVT LTD","RAM ENTERPRISE","RAM FASHION EXPORTS PVT LTD","RAM PRASAD TRADERS","RAM RATNA ELECTRICALS LIMITED","RAM RATNA INTERNATIONAL","RAMA CYLINDERS PVT LTD","RAMA INDUSTRIES","RAMAN & WEIL PVT. LTD.","RAMCHANDRA ENGINEERING WORKS","RAMCO INDUSTRIES LIMITED","RAMDEV CHEMICAL INDUSTRIES","RAMDEV FOOD PRODUCTS PVT. LTD.","RAMESH BRASS PRODUCTS","RAMESHCHANDRA BHIKHALAL & COMPANY","RAMKRIPA AGRO FOODS (P) LTD.","RAMKY ENVIRO ENGINEERS LIMITED","RAMNIKLAL S GOSALIA & CO","RAMRATNA INTERNATIONAL","RAMS FITTINGS","RAMYA ALLOYS & STEEL PVT LTD","RAMYA DISPOSABLES LLP.","RAMYA IMPEX PRIVATE LIMITED","RAMYAA ELECTRO GEAR PRIVATE LIMITED","RAN CHEMICALS PVT LTD","RANDWIN EXIM PVT LTD","RANE BRAKE LINING LTD","RANE DIECAST LIMITED","RANE ELASTOMER PROCESSORS","RANE ENGINE VALVE LIMITED","RANE MADRAS LIMITED","RANEKA INDUSTRIES LIMITED","RANEMARG FLAVOURS AND FRAGRANCES PRIVATE LIMITED","RANG RASAYAN LTD","RANGSANGAM INC","RANK ADDITIVES PVT LTD","RANK ENTERPRISES","RANK METALLURGICAL SERVICES ","RANKA PAPER CONVERTERS","RANPARIA ENGINEERING PVT.LTD","RANQ REMEDIES PRIVATE LIMITED","RAPID COAT DIVISON","RAPID COAT SALES","RAPID ENGINEERING COMPANY PRIV","RAPID HARDWARE & STORAGE SYSTE","RAPID TECHNOLOGY SOLUTIONS","RAREX GLOBAL LLP","RASHMI MARKETING","RASHMI METALIKS LIMITED","RASHTRIYA METAL INDUSTRIES LTD","RASI ELECTRODES LIMITED","RASIKA MOTORS PRIVATE LIMITED","RASINO HERBS PRIVATE LIMITED","RASNA PRIVATE LIMITED","RATHI ABRASIVES","RATHI DYE CHEM PRIVATE LIMITED","RATHI TRANSPOWER PRIVATE LIMITED","RATHNA PACKAGING INDIA PRIVATE LIMITED","RATIONAL BUSINESS CORPORATION ","RATNA HOME PRODUCTS PVT. LTD ","RATNAMANI HEALTHCARE PVT LTD","RATNESH RAJ CHEMICALS","RAVAGO MANUFACTURING INDIA PRIVATE LIMITED","RAVAGO SHAH POLYMERS PVT.LTD.","RAVESHIA COLOURS PVT LTD","RAVESHIA PIGMENTS LTD","RAVI BRASS INDUSTRIES","RAVI DYEWARE CO.PVT.LTD.","RAVI FOODS PRIVATE LIMITED","RAVI PAPER","RAVICAB CABLES PRIVATE LIMITED","RAVIKIRAN CHEMICAL PVT LTD","RAWJI FINE FRAGRANCES PRIVATE LIMITED","RAWSTOCK GLOBAL","RAY ENTERPRISES","RAYMOND APPAREL LTD","RAYONNANT NATURAL CARE EXCELLENCE PRIVATE LIMITED","RAYZON GLOBAL LLP","RBP TECHNOLOGY INDIA PVT LTD","RCUBE MARKETING","REACH LOGISTICS PVT LTD","REAGHAN FASHION PVT LTD","REAL INNERSPRING TECHNOLOGIES PVT LTD","REAL SOYA ENTERPRISES","RECAZ CHEMICALS (INDIA) PRIVATE LIMITED","RECKITT BENCKISER INDIA LTD","RECKON LIFESTYLE","RECKON ORGANICS PVT LTD","RECLINERS INDIA PVT LTD","RECO TRANSFORMERS PVT LTD","RECONDO COOLING TOWERS PRIVATE LIMITED","RECORDERS AND MEDICARE SYSTEMS PVT LTD","RECOVA PHARMA EXPO PVT LTD","RECRAFT PROCESSING PRIVATE LIMITED","RED FORD BRASS INDUSTRIES","RED SUN DYE CHEM","REDHERRING PLAST INDIA PRIVATE LIMITED","REDROSE SYNTHETICS PVT. LTD.","REENA ORGANICS PRIVATE LIMITED","REETHA TEX IMPORT EXPORT","REFEX INDUSTRIES LIMITED","REFNOL RESINS & CHEMICALS LTD.","REFRACAST METALLURGICALS PVT L","REFRAHOLD","REFRATECHNIK (INDIA) PVT. LTD.","REFTEKE TRADING","REGAL CASTORS PVT. LTD.","REGAL WOVEN SACKS PVT LTD ","REGENCY CYCLES PVT LTD","REGENT LIGHTING ASIA PVT. LTD.","REGENT PAINTS PVT LTD","REGENT RICH CAPACITORS PVT LTD","REICHHOLD INDIA PRIVATE LIMITED","REID BRAIDS (INDIA)","REINOL OBSTFELD INDIA","REITZ INDIA LIMITED","RELAXO FOOTWAERS LTD.","RELIABLE ENGINEERING INDUSTRIES","RELIABLE RESOURCES","RELIANCE INDUSTRIES LIMITED","RELIANCE PACKIGING INDUSTRIES","RELIANCE RETAIL LTD","RELSOL","REMCO MATTRESS","REMI ELEKTRONITCHNIK LTD","REMI SALES & ENGG LTD","REMSONS IND LTD","RENA KUTZ KITCHENWARES PVT. LTD.","RENEWSYS INDIA PRIVATE LIMITED","RENO MERCANTILE PRIVATE LIMITED","RENUKA PLASTI CRAFTS PVT LTD","REPOSE MATTRESS PRIVATE LIMITED","REPRO INDIA LTD.","REPROMACHINES","RESEARCH LAB CHEMICAL CORPORATION","RESEARCH LAB FINE CHEM INDUSTRIES","RESGUARDO INDUSTRIES PRIVATE LIMITED","RESIL CHEMICALS PVT LTD","RESINOVA CHEMIE LTD","RESINS & PLASTICS LTD.","RESISTOFLEX DYNAMICS PVT LTD","RESISTOFLEX PVT LTD","RESITEX","RESONANCE SPECIALTIES LTD.","RESTORT CHEMICALS PVT LTD","RETAIL AGARBATHI ","RETAIL AGRO EQUIPMENT   - KSY","RETAIL ALUMINIUM POWDER","RETAIL BANDAGE CLOTH MARKETING","RETAIL BUSINESS AGRICULTURAL IMPLEMENTS","RETAIL BUSINESS KUMKUM","RETAIL BUSINESS- SEWING MACHINE","RETAIL CARDAMOM","RETAIL CASHEW","RETAIL CLOTH CONSIGNMENTS","RETAIL DETAILZ INDIA PVT LTD","RETAIL FISHNET BOOKING","RETAIL HANDICRAFTS","RETAIL PAPER TRADERS","RETAIL PAPPAD BOOKING","RETAIL PRINTING GOODS","RETAIL RUBBERBAND CUSTOMER-KOTTAYAM","RETAIL SEWING MACHINE","RETAIL TEXTILES ","RETAIL-CHANNA LEAVES","RETEX INC","RETTENMAIER INDIA PVT LTD","REUTTER INNOVATIVE CLOSURE TECHNOLOGIES PRIVATE LI","REVAA ENGINEERING","REX POWER SOLUTIONS PVT.LTD.","REXELLO EINGIEERING PRODUCTS P","REXNORD ELECTRONICS & CONTROLS","REYCOR INDIA SERVICES","REYNDERS LABEL PRINTING INDIA ","REYNOLD INDIA PVT. LTD.","RHENUS CONTRACT LOGISTICS","RHINE LABORATORIES","RHINO INFRA EQUIPMENTS","RHINO SERVICE PRIVATE LIMITED","RHODIA SPECIALTY CHEMICALS IND","RHYDBURG PHARMACEUTICALS LTD","RIBO INDUSTRIES PVT LTD","RICE LAKE WEIGHING SYSTEMS","RICH FEEL HEALTH & BEAUTY PVT ","RICHA INDUSTRIES LIMITED","RICHARDS PRINTING","RICHBOND PAPER PRODUCTS","RICHCORE LIFESCIENCES PVT LTD","RICHTER THEMIS MEDICARE INDIA ","RICKLAY AUTOMOTIVE","RIDDHI ENGINEERING COMANY-ASLB","RIDHI PETROCHEM PVT. LTD.","RIMOX MARKETING","RINAC INDIA LTD","RINGSPANN POWER TRANSMISSION I","RISHABH ENTERPRISES","RISHABH INDUSTRIES","RISHABH MARKETING","RISHI FABRICS LIMITED","RISHI FIBC SOLUTIONS PVT LTD","RISHI PACKAGING","RISHIROOP LIMITED","RISHMA AGENCIES","RISING SUN AROMAS & SPIRITUALS","RISO INDIA PVT LTD","RITA RUBBER WORKS","RITE EQUIPMENTS PVT LTD","RITE WATER SOLUTIONS (INDIA) PRIVATE LIMITED","RITTAL INDIA PVT LTD","RITU APPARELS","RIVAA INTERNATIONAL","RIVAA SALES CORPORATION","RIVIPAC POLYMERS PVT. LTD.","RIVPRA FORMULATION PVT. LTD.","RJP TECHNOLOGIES PRIVATE LIMITED","RK PLASTICS","RM CONVERTERS PRIVATE LIMITED","RMG POLYVINYL INDIA LTD","RMR BRANDS","RMS INDUSTRIES","RND LABORATORIES PRIVATE LIMITED","ROAD MASTER AUTOTECH PRIVATE LIMITED","ROBERTET GOLDFIELD (INDIA) PRI","ROCA BATHROOM PRODUCTS PVT.LTD","ROCHAK AGRO FOOD PRODUCTS PRIVATE LIMITED","ROCHEM INDIA PVT LTD ","ROCHEM SEPARATION SYSTEMS INDIA PRIVATE LIMITED","ROCK CRUSHER","ROCKSTAR FASHIONS INDIA PRIVATE LIMITED","ROCKWOOL (INDIA) LTD","ROECHLING ENGINEERING PLASTICS","ROHA DYECHEM PRIVATE LIMITED","ROHAN DYES & INTERMEDIATES LTD","ROHAN PIGMENTS PVT LTD","ROHIT ENGINEERS","ROHIT MARKETING","ROKANI & SONS","ROLEX ANCILLAY INDUSTRIS ","ROLEX INDUSTRIES ","ROLEX METERS PRIVATE LIMITED","ROLEX RINGS PVT.LTD","ROLLERS INDIA","ROLLIFLEX CABLES PVT. LTD.","RONAK FIRE INDIA PRIVATE LIMITED","RONCH POLYMERS PRIVATE LIMITED","RONSON INDUSTRIAL ENGINEERS PV","RONUK CHEMICALS PVT LTD","ROOPS INDUSTRIES","ROOTS AUTO PRODUCTS PRIVATE LIMITED","ROQUETTE RIDDHI SIDDHI PVT LTD","ROSELIN LEATHERS PVT.LTD","ROSHAN FOODS","ROSHAN FRUITS INDIA PRIVATE LTD","ROSHMA PET PLAST","ROSHNEE PAPAD PRODUCTS LLP","ROSLER SURFACETECH PRIVATE LIMITED","ROSS PROCESS EQUIPMENT PRIVATE LIMITED","ROSSARI BIOTECH LIMITED","ROSSINI INDIA PRINTING ROLLERS PRIVATE LIMITED","ROTECH","ROTEX MANUFACTURERS &ENGINEERS","ROTO PUMPS LIMITED","ROTO SCREENTECH PVT LTD","ROTOCAST INDUSTRIES LTD.","ROTOLOK VALVES PRIVATE LIMITED","ROTOMAC INDUSTRIES PVT LTD","ROTOTON POLYPACK PVT. LTD.","ROXY CHEMICAL & PLASTIC INDUSTRIES","ROYAL ARC ELECTRODES LTD","ROYAL CASTOR PRODUCTS LTD","ROYAL CHEMICALS","ROYAL ELASTICS","ROYAL EMBROIDERY THREADS PRIVA","ROYAL FABRICS","ROYAL FASTNERS","ROYAL FOOD COMPANY","ROYAL INVERTERS & BATTERIES","ROYAL LUBRICANTS","ROYAL SPECTRUM PVT LTD","ROYAL STAINLESS STEEL CONTAINE","ROYAL SYNTHETICS","ROYAL SYNTHETICS- PRASANTA KUNDU","ROYAL TRADERS","ROYAL TUBE COROPORATOIN","ROYALE RUBBER TECH (INDIA) INDUSTRIES","RPJ TEXTILES LTD","RPM FOOD PRODUCTS PVT LTD","RPT EVERGREEN TECHNOLOGIES LLP","RS ENTERPRISES IND PVT LTD","RSA FORGE","RSA INDUSTRIES & RAN CHEMICALS","RSG CONSUMER PRODUCTS","RSM AUTOCAST LTD","RSPL LIMITED","RUACH MEDICAL TECHNOLOGY PRIVATE LIMITED","RUBAL INGREDIENTS","RUBAMIN PRIVATE LIMITED","RUBBER KING TYRE PVT.LTD.","RUBFILA INTERNATIONAL LTD","RUBY BIOPHARMA PRIVATE LIMITED","RUBY ENTERPRISES","RUBY PAINTS PRIVATE LIMITED","RUBYCON ELECTRICAL & ELECTRONIC INDUSTRIES","RUCHI PACKAGING PRIVATE LIMITED","RUCHI SOYA INDUSTRIES LIMITED","RUDANI ENTERPRISES","RUDKAV INTERNATIONAL PVT. LTD","RUDOLF ATUL CHEMICALS LIMITED","RUDRA INDUSTRIES","RUGS & CARPET","RUKHHUMAI ENTERPRISES","RUKMA PLASTICS","RUMA NATURALS LLP","RUNGTA MANUFACTURING PVT LTD","RUPA & COMPANY LTD","RUPAM FOOD MAKERS PRIVATE LIMITED","RUPAM IMPEX","RUPAREL FOODS PVT. LTD.","RUPAREL PLASTICS PVT. LTD.","RUPAREL POLYSTRAP PVT LTD.","RUSAN PHARMA LTD","RUSHABH ENTERPRISES","RUSHABH INVESTMENT PRIVATE LIMITED","RUSHAIL PHARMADIN PVT LTD","RUSKIN TITUS INDIA PVT LTD","RUSSELL FINEX SIEVES & FILTERS","RUST FREE GLOBE PRIVATE LIMITED","RUVEN HEALTHCARE PRIVATE LIMITED","RYDER SHIPPING LINES PVT LTD","S AND T ENGINEERS PRIVATE LIMITED","S AND T MACHINERY PRIVATE LIMITED","S AND T PLASTIC MACHINES PRIVATE LIMITED","S AND T WELCARE EQUIPMENTS PVT.LTD","S B CHEMICALS","S B FLUOROCHEM PVT LTD","S C J PLASTICS LTD","S CHAND AND COMPANY LTD","S CHEMS AND ALLIED PRODUCERS PRIVATE LIMITED","S D ENGINEER","S D FINE CHEM LTD","S G PHYTO PHARMA PVT LTD","S H KELKAR AND COMPANY LTD","S K FOODS","S K HOME CARE PRODUCT","S K INDUSTRIES","S K INTERNATIONAL ","S K PACKAGE MACHINE","S K TRADERS","S KANT HEALTHCARE PVT LTD","S KRISHNAN","S M ENTERPRISES","S M POLYMERS","S M SYSTEMS PVT LTD","S NARENDRA KUMAR & CO","S P IMPEX","S P INDUSTRIES","S R ENTERPRISES","S R I MARBO PVT LTD","S S CLOTHING MILLS","S S FRAGRANCES","S S N R PROJECTS PRIVATE LIMITED ","S S P ENTERPRISES","S S POLYMER","S T C MARKETING & SERVICES","S V C INFOTECH","S&S POWER SWITCHGEAR EQUIPMENT LIMITED","S. CHANDANMULL MUTHA","S. I. INTER PACK","S. K OIL INCORPORATION","S. KUMAR MULTI PRODUCTS PVT LT","S. KUMARS NATIONWIDE LTD.","S. L. FASTENERS  PRIVATE LIMITED","S.A.M. ENTERPRISE","S.B. PACKAGINGS PRIVATE LIMITED","S.B.C. EXPORTS LIMITED","S.D. ENTERPRISES","S.D.FORGING WORKS","S.K HOME CARE PRODUCT","S.K. EDUCATIONS PRIVATE LIMITED","S.K. OLD CLOTH SUPPLIER","S.K.COURIER","S.M.ENTERPRISES","S.M.PRECISION ENGG. WORKS","S.NARENDRAKUMAR & CO.","S.R.CHEMICAL","S.R.FASTNERS PVT.LTD.","S.S ENTERPRISES - SANGHVI MACHINE TOOLS","S.S PACKAGING","S.S. SALES","S.S.ENGINEERING ENTREPRENEURS","S.S.ENTERPRISES","S.S.P. PACKAGING  INDUSTRIES P","S.S.RUBBERS","S.V INDUSTRIES","S.V. EXPORTS","S.V.POLYMERS","SAAHAS ZERO WASTE MANAGEMENT P","SAAN GLOBAL LIMITED","SAARA CREATIONS","SAATVIK GREEN ENERGY PRIVATE LIMITED","SABAR ENTERPRISES","SABARI CHEMICALS PVT LTD","SABROSO SNACKS PRIVATE LIMITED","SACHIN INDSUTRIES LTD","SADAFULI ENTERPRISES","SAERTEX INDIA PRIVATE LIMITED","SAFAL FOOD PRODUCTS PRIVATE LIMITED","SAFARI INDUSTRIES INDIA LIMITE","SAFDARJANG MOTORS PRIVATE LIMITED","SAFE INSULATION TECHNOLOGIES P","SAFE LIFTERS PRIVATE LIMITED","SAFE PRO FIRE SERVICES PRIVATE","SAFECON LIFESCIENCES","SAFEGUARD CONTRACEPTIVE PRIVATE LIMITED","SAFEPACK INDUSTRIES LTD","SAFEWORLD SYSTEMS PVT. LTD.","SAFEX CHEMICALS (INDIA) LIMITED","SAFEX FIRE SERVICES LTD .","SAFEX INDUSTRIES LTD","SAFFAIRE INDUSTRIAL SAWS (I) P","SAFFIRE FASHIONS.","SAFFRON INDUSTRIES","SAFFROSHINE ORGANICS PRIVATE LIMITED","SAFVOLT SWITCHGEARS PRIVATE LIMITED","SAGAR DRUGS & PHARMACEUTICALS ","SAGAR ENGINEERING","SAGAR INDUSTRIES","SAGAR ISPAT INDIA PVT LTD","SAGAR PHARMA GENERICS","SAGAR PUMPS AND SPARES","SAGAR RUBBER PRODUCTS PVT. LTD","SAGAR SPECIALITY CHEMICALS PVT LTD","SAGAR SPRINGS PVT.LTD.","SAGARBHANGA FOUNDRY & ENGINEER","SAHAJANAND CHEMICAL INDUSTRIES","SAHAJANAND INDUSTRIES","SAHAJANAND PAPAD MANUFACTURER","SAHAKAR STRAPS","SAHARA INDUSTRY","SAHASRA FANA PARASNATH JAIN TEMPLE AND ITS SADHARA","SAHIB EQUPIMENTS PVT LTD","SAHIBA LTD","SAHIL INDUSTRIES","SAHIL PACKAGING","SAHIL TECH INDIA LIMITED","SAHYADRI CHEM","SAHYADRI STARCH AND INDUSTRIES","SAHYOG ENTERPRISE ","SAI CHEM","SAI CORPORATION","SAI DEEPA ROCK DRILLS PRIVATE LIMITED","SAI ENTERPRISE","SAI INTERNATIONAL TRADERS","SAI KRISHNAA  ENTERPRISES","SAI KRUPA ENGINEERING","SAI PRIMUS LIFEBIOTECH PRIVATE LIMITED","SAI PRINT O PACK","SAI SHARADA MARKETING SERVICES","SAI SHRADDHA ENTERPRISES","SAI SUPER PACK","SAI TIRUMALA PAPERS PVT LTD","SAI VINAYKA FOODS","SAIBABA SURFACTANTS PVT LTD","SAICHEM ORGANICS PVT LTD","SAINEST TUBES PVT LTD","SAINI ELECTRICAL & ENGINEERING","SAINOR LABORATORIES PRIVATE LIMITED","SAINT-GOBAIN INDIA PVT.LTD.","SAIRAJ PRINT HOUSE","SAISUKRTHKAR SUPPLEMENTS PVT LTD","SAIT NAGJEE PURUSHOTHAM AND COMPANY PRIVATE LIMITE","SAIVEER KRISHI VIKAS KENDRA","SAJJAN INDIA LIMITED","SAK INDUSTRIES PRIVATE LIMITED","SAKAR ELECTRICALS AND ELECTRON","SAKATA INX INDIA PVT LTD","SAKHI ENGINEERS PVT LTD ","SAKSHAM IMPEX PVT. LTD","SAKSHI CHEM SCIENCES PRIVATE LIMITED","SAKTHI ACCUMULATORS PRIVATE LIMITED","SAKTHI AUTO ANCILLARY PRIVATE LIMITED","SALASAR ALLOY AND STEEL INDUSTRIES PRIVATE LIMITED","SALASAR FEBRICATIONS","SALICYLATES & CHEMICALS PVT LT","SALTS AND CHEMICALS PVT LTD","SALUD CARE INDIA PVT LIMITED","SALUS NUTRACEUTICALS","SALUS PHARMACEUTICALS","SALZER MAGNET WIRES LTD","SALZGITTER HYDRAULICS PVT LTD","SAM FINECHEM LTD.","SAM GAS PROJECTS PRIVATE LIMITED","SAMARPAN INDUSTRIES","SAMARTH ENTERPRISES","SAMARTH INDUSTRIES","SAMARTH POLYCOATS PVT LTD","SAMAY AGRO PRODUCTS","SAMAY POLYPLAST PRIVATE LIMITED","SAMBHAAV METAL INDIA","SAMESKY CONFECTIONERIES INDIA","SAMI LABS LIMITED","SAMIR CERAMICS PVT LTD","SAMIR TECH CHEM PVT LTD","SAMITAN ELECTROPOWERS PRIVATE LIMITED","SAMPAT ALLUMINIUM PVT. LTD.","SAMPRE NUTRITIONS LTD","SAMRAT GEMS IMPEX P LTD","SAMRAT SALES SYNDICATE","SAMRUDDHI INDUSTRIES LIMITED","SAMSON CNO INDUSTRIES","SANDER MESON INDIA PVT LTD","SANDHYA ORGANIC CHEMICALS PVT ","SANDIP CHEMICALS","SANDORI CASTING PVT LTD.","SANEESA CHEMICALS & EQUIPMENTS","SANEMI PLASTICS","SANFIELD INDIA LTD","SANGHAVI ABRASIVES","SANGHAVI INDUSTRIES PVT LTD","SANGHVI FORGING & ENGINEERING ","SANGINITA CHEMICALS  LTD","SANGIR PLASTICS PRIVATE LIMITED","SANGOLA AGRO PRODUCTS","SANJANA ABRASIVE INDUSTRIES","SANJAY STAINLESS STEEL WORKS","SANJAY TOOLS AND ADHESIVES","SANJIVANI CASTING P.LTD.","SANKALAP ORGANICS PVT LTD","SANKALP HEALTHCARE AND ALLIED ","SANKALP SAFETY SOLUTIONS LLP","SANKATMOCHAN DURGA ENTERPRISES","SANKET INDUSTRIES","SANKHLA INDUSTRIES","SANKHLA VINYL PRIVATE LIMITED","SANKHUBABA INTERNATIONAL","SANKUR PHARMACEUTICALS PRIVATE LIMITED","SANMAR MATRIX METALS LIMITED","SANMED HEALTH CARE PRIVATE LIMITED","SANMED SPECIALITIES PRIVATE LIMITED","SANMIT INFRA LTD","SANOFI INDIA LIMITED","SANRAAJ HOSES PVT LTD","SANRHEA TECHNICAL TEXTILES LTD","SANSKRITI COMPOSITES PRIVATE LIMITED","SANTOSHI BARRIER FILM INDIA PRIVATE LIMITED","SANTOSHI BARRIER INDIA PVT LTD","SANTRAM ENGINEERS PVT. LTD.","SANYAM EXPORTS","SANYOG ENTERPRISES PVT LTD","SAP GLOBAL LOGISTICS","SAP INDUSTRIES","SAP PARTS PRIVATE LIMITED","SAP SHIPPING &FORWARDING LLP","SAP SWISS SOLUTIONS PVT. LTD","SAPA EXTRUSION INDIA PVT LTD","SAPA PRECISION TUBLING PUNE PV","SAPANA POLYWEAVE PVT LTD","SAPHIRE BLUE INDUSTRIES PRIVATE LIMITED","SAPNA INDUSTRIES","SAPPHIRE TEX CHEM","SAR AUTO PRODUCTS LIMITED","SARA EXPORTS LIMITED","SARA HEALTH CARE","SARA SAE PRIVATE LIMITED","SARACHEM (INDIA) PVT LTD","SARAL CHEM","SARAL MINERALS AND CHEMICALS I","SARALIFE HEALTHCARE","SARASWATI CHEMICAL CORPORATION","SARASWATI TRADING CO.","SARATECH EQUIPMENTS","SARATHI INTERNATIONAL INC","SARDA  ENTERPRISES","SARDA DAIRY AND FOOD PRODUCTS LIMITED","SARDA DISTRIBUTORS PRIVATE LIMITED","SARDA INDUSTRIAL ENTERPRISES","SARDA METALS & ALLOYS LTD","SAREX CHEMICAL","SARNA CHEMICAL PVT. LTD.","SAROJ ART PRINTERS","SAROVAR TUBE INDUSTRIES","SAROVARAM INDUSTRIES LLP","SARTHAK METALS LIMITED","SARTHI CHEM PVT. LTD.","SARU AIKOH CHEMICALS LTD.","SARVAIYA CHEMICALS INDUSTRIES ","SARVAM SAFETY EQIPMENT (P) LTD","SARVODAYA CARPETS","SARVODAYA INDUSTRIES","SARVOTHAM URJA PVT LTD","SASCO STEEL PRIVATE LIMITED","SASHANKA AGRO TECH PVT LTD","SASHI DHAR SPARES PVT.LTD.","SASTASUNDAR HEALTHBUDDY LIMITED","SAT TRADING COMPANY","SATGURU AGRO RESOURCES PRIVATE LIMITED","SATGURU OILS PRIVATE LIMITED","SATHYA SOLUTIONS PVT LTD","SATISFACTION PRODUCTS PRIVATE LIMITED","SATISH STEEL WORKS","SATOL CHEMICALS","SATRA CHEMICAL INDUSTRIES","SATYAM COMPOSITES PRIVATE LIMITED","SATYAM INDUSTRIES","SATYANARAYAN RUBBER & PLASTIC INDUSTRIES","SATYAY TECHNOCAST P.LTD.","SATYENDRA FINE CHEM","SATYOM ENTERPRISES PVT. LTD.","SAURADIP CHEMICALS IND","SAURAS EXPORTS PVT LTD ","SAURAV CHEMICALS LTD","SAVATTA FOOD PRODUCTS","SAVERA","SAVERA AUTO COMPS PRIVATE LIMITED","SAVERA PRESS COMPS PRIVATE LIMITED","SAVITA OIL TECHNOLOGIES LTD","SAVITA POLYMERS LTD","SAVOIR FAIRE MFG CO.P.LTD","SAVORIT LIMITED","SAWALIYA FOOD PRODUCTS PVT LTD","SAWAN ENGINEERS PRIVATE LIMITE","SAWANT FILTECH PRIVATE LIMITED","SAYAJI INDUSTRIES LIMITED","SAYEGH PAINTS FACTORIES INDIA PRIVATE LIMITED","SAYOG CORPORATION","SB FABCARE PRIVATE LIMITED","SBCT INDUSTRIES LLP","SBEE CABLES INDIA LIMITED","SBL PVT.LTD","SBR AUTO COMPONENTS LIMITED","SBRO TAPES PRIVATE LIMITED","SBS CHEMICALS","SBS ENGINEERING CONCERN","SCALEBAN EQUIPMENTS PRIVATE LIMITED","SCANSTRANS INDIA PVT. LTD.","SCENARIO POWERTECH PVT. LTD","SCHENKER INDIA PRIVATE LIMITED","SCHEVARAN LABORATORIES PRIVATE LIMITED","SCHNELL ENERGY EQUIPMENTS PVT ","SCHOELLER INDIA INDUSTRIES PRIVATE LIMITED","SCHOLLE IPN INDIA PACKAGING PVT LTD","SCHOTT KAISHA PVT.LTD","SCHULKE INDIA PVT LTD","SCHUTZ CARBONS ELECTRODES PVT ","SCHWABE INCOAT","SCHWING STETTER (INDIA) PVT LTD","SCIENTIFIC BRAIN NUTRACEUTICAL PVT LTD","SCJ COLOURS","SCOPE INGREDIENTS PVT LTD","SCORPIO ASSOCIATES","SCORPION CONTAINERS PVT LTD","SCORPION INC","SCREENS INDIA","SCS CARGO PVT LTD","SDA INDUSTRIES","SDHOLE PHARMA PRIVATE LIMITED","SDI HERBO-CHEM PVT. LTD.","SDN LOGISTICS","SDP INDUSTRIES PRIVATE LIMITED","SDS RAMCIDES CROPSCIENCE PRIVA","SE FORGE LIMITED","SEABORNE COMMODITIES INT PVT L","SEALANT AND GASKET INDIA PRIVATE LIMITED","SECURAMAX VALVES AND CONTROL SYSTEMS PRIVATE LTD","SECURE POLYMERS PVT.LTD.","SECURENET CABLES & CONNECTORS PRIVATE LIMITED","SEEBACH FILTER SOLUTIONS INDIA PRIVATE LIMITED","SEEL SMITH","SEEMA INDUSTRIES","SEIGER SPINTECH EQUIPMENTS PVT","SELVOK PHARMACEUTICAL CO","SELWEL ENTERPRISES PVT LTD.","SENAPATHY SYMONS INSULATIONS P","SENAPATHY WHITELEY PRIVATE LIM","SENDKA BELTING & CONVEYORS","SENDURA FORGE PVT LTD.","SENOR METAL P LTD","SENSAROM FOODS PVT.LTD","SEQUENT SCIENTIFIC LIMITED","SERICARE","SERVEL INDIA PRIVATE LIMITED","SERWEL ELECTRONICS PVT LTD","SERWELL MEDI EQUIP (P) LTD","SETCO AUTOMOTIVE LIMITED","SETCO CHEMICALS INDIA PVT LTD","SETHNESS-ROQUETTE INDIA LTD","SETNER","SEVEN SEAS PAINTS PVT. LTD.","SEWING SYSTEMS PVT LTD","SGM MAGNETICS INDIA PRIVATE LIMITED","SGR (777) FOODS PVT LTD","SGV FOILS PVT LTD","SH HARYANA WIRES LTD","SH. HARYANA WIRES LTD","SHAH BHOGILAL JETHALAL & BROTH","SHAH C J WORLD LLP","SHAH ENGINEERING","SHAH INDUSTRIES","SHAH METAL & ALLOYS","SHAH PAPER MILLS LTD.","SHAH PULP & PAPER MILLS LTD","SHAH STONE MACHINES PVT LTD ","SHAH TOOLS CENTRE","SHAHI EXPORTS PVT LTD","SHAILESH & CO","SHAKAMBARI FOOD PRODUCTS","SHAKAMBARI POLYMERS PVT. LTD.","SHAKO FLEXIPACK PVT LTD","SHAKTHI TECH MANUFACTURING IND","SHAKTI ADHESIVE","SHAKTI CORDS PRIVATE LTD","SHAKTI FORGE INDUSTRIES PRIVATE LIMITED","SHAKTI HORMANN PRIVATE LIMITED","SHAKTI POLYWEAVE PVT.LTD.","SHAKUN POLYMERS PRIVATE LIMITED","SHAKUNT ENTERPRISES PVT. LTD.","SHAKUNTAL SALES","SHAKUNTALA AGARBATHI COMPANY","SHALIBHADRA DISTRIBUTORS","SHALIBHADRA INTERMEDIATES PV","SHALIMAR PAINTS LTD","SHALIMAR PELLET FEEDS LTD.","SHALIMAR SEAL AND TAR PRODUCTS PRIVATE LIMITED","SHALINA LABORATORIES PVT. LTD.","SHAMLAX METACHEM PVT LTD","SHANAY IMPEX","SHANDILYA DISTRIBUTORS","SHANGHAI COLOUR CHEM","SHANKAR NUTRICON PRIVATE LIMITED","SHANKAR PACKAGING LTD","SHANKAR SOYA PRODUCT","SHANKESH FOODS AND EXPORTS PRIVATE LIMITED","SHANTA G FOODS (P) LIMITED","SHANTHI GEARS LIMITED","SHANTI INORGO CHEM (GUJ.)PVT.L","SHANTI INSTRUMENTS PVT LTD","SHANTI PATRA PLASTICS PVT.LTD","SHANTI POLYPLAST","SHANTI SHIPPING SERVICES PRIVATE LIMITED","SHANTI SHREE MARKETING","SHANTI SNACKS PRIVATE LIMITED","SHANTOK INTERNATIONAL","SHAPOTOOLS","SHARAD TRADING COMPANY","SHARDA ENGINEERS & CONSTRUCTIONS","SHARIFA AGROTECH AND FOOD PROCESSING PVT LTD","SHARON BIO-MEDICINE LIMITED ","SHARP BATTERIES & ALLIED INDIA LTD","SHARP STATIONERY PRODUCTS","SHARPWIRE INDUSTRIES (INDIA) PRIVATE LIMITED","SHASHI INDUSTRIES","SHASHWAT CABLES  PVT LTD","SHASHWAT SOAPS PRIVATE LIMITED","SHASUN CHEMICALS AND DRUGS LTD","SHAUN FILAMENTS","SHAYBURG VALVES PRIVATE LIMITE","SHAYOK POULTRY FEEDS","SHAYRI METALS","SHAZ PACKAGING LLP","SHEELPE ENTERPRISE PVT LTD","SHEENLAC PAINTS LTD","SHEETALKUMAR PADAMCHAND JAIN","SHEFA AGRICARE TECHNOLOGIES PRIVATE LIMITED","SHEFA HEALTH CARE PVT LTD-TALO","SHEKAR BOOK MANUFACTURERS","SHERPA FRUIT PRODUCTS","SHERWIN WILLIAMS COATINGS PVT LTD","SHETH BROTHERS","SHETH INFRABUILD LTD","SHETH PET AND POLYMERS PVT LTD","SHIDIMO INTERAUX PVT LTD","SHIDO PHARMA","SHIEMVOLTECH PRIVATE LIMITED","SHILPA AGENCIES","SHILPI & ASSOCIATES","SHILPI DESIGNERS","SHILPY TRADING","SHIMMERS HERBALS & CHEMICALS P","SHINAF ENTERPRISES","SHIRAISHI CALCIUM (INDIA) PRIVATE LIMITED","SHIRDI INDUSTRIES LTD.","SHITAL INDUSTRIES PVT LTD","SHIV CHEM INDUSTRIES","SHIV CHEMICALS","SHIV SHAKTI ALCHEMY","SHIV SHAKTI METAL INDUSTRIES","SHIVA BIOCHEM INDUSTRIES","SHIVA CREATIONS","SHIVA GENSETS PRIVATE LIMITED","SHIVA INDUSTRIES","SHIVA PERFORMANCE MATERIALS PRIVATE LIMITED","SHIVA PHARMACHEM","SHIVA SHAKTI BOARDS","SHIVA TECHNIQUES SURFACES PRIV","SHIVA TEXYARN LTD","SHIVAADITYA INFRACON","SHIVAJI CANE PROCESSORS LIMITED","SHIVALIK PAPER AND PACKAGING","SHIVAM AUTOTECH LIMITED","SHIVAM COATING INDUSTRIES","SHIVAM ELASTIC & TAPE PVT. LTD","SHIVAM OILS & PROTEINS INDUSTR","SHIVAM TOOLS & STEEL","SHIVAM UDYOG","SHIVANI DETERGENT PVT LTD","SHIVANI MEDIPRO PRIVATE LIMITED","SHIVEN YARN PRIVATE LIMITED","SHIVOM INDUSTRIES","SHIVSAHAJ ENGINEERING & PLASTICS PRIVATE LIMITED","SHLOK ENTERPRISES","SHOBHIT HANDLOOM","SHOES MARKET","SHOPCLIENTS CONSULTANCY SERVICES PRIVATE LIMITED","SHRADDHA ASSOCIATES ( GUJ ) PV","SHRADHA ENTERPRISES","SHREE ABHAY CRANES PRIVATE LIMITED","SHREE ABHAY HOISTS & ENGINEERI","SHREE AMBA INDUSTRIES","SHREE AROMATICS","SHREE BAJRANG SALES PVT LTD","SHREE BALAJI PLASTIC","SHREE BALAJI PLASTOCHEM PRIVATE LIMITEDS","SHREE BANKEY BEHARI LAL FLAVORS","SHREE CERAMIC FIBERS PVT LTD","SHREE CHEMICAL INDUSTRIES","SHREE CHLORATES","SHREE DHOOTAPAPESHWAR LIMITED","SHREE ELECTROTECH & SERVICES","SHREE ENGINEERING WORKS","SHREE ENTERPRISES","SHREE EVID SONS","SHREE EXTRUSIONS LTD","SHREE GAJANAN PAPER & BROADS P","SHREE GANESH KRUPA ENTERPRISES","SHREE HANS ALLOYS LTD","SHREE HANSH DECORATIVE PVT LTD","SHREE INDUSTRIAL ABSORTMENT PV","SHREE INDUSTRIAL MACHINERIES","SHREE JEE LABORATORY PVT LTD","SHREE JEES HARDWARE","SHREE JI CORPORATION","SHREE K D K ENTERPRISE","SHREE KAILASH KHANIJ UDYOG","SHREE KARTHIKEYAN AGENCIES ","SHREE KARTIKEY VANIJYA PVT. LTD","SHREE KRISHNA CABLES","SHREE KRISHNA POOJA BHANDAR","SHREE KRISHNA SULPHATE PVT LTD","SHREE KRISHNAKESHAV LABORATORI","SHREE LAXMI AYAT & NIRYAT PVT ","SHREE MAA MULTICHEM PRIVATE LI","SHREE MAHALAXMI TRADING","SHREE MAHAVIR METAL CRAFT PVT.","SHREE METAL SYNDICATE","SHREE MOMAI ENGINEERING WORKS","SHREE NAKODA REFRIGERATION","SHREE NASIK PANCHAVATI PANJRAPOLE","SHREE NIDHI MARKETING PVT LTD","SHREE PADMAVATI ENGINEERS(I)PVT.LTD.","SHREE PHARMA","SHREE PRAYAG AIR CONTROLS PVT.LTD.","SHREE PUSHKAR CHEMICALS AND FERTILISERS LTD","SHREE RADHE INDUSTRIES","SHREE RAJESHWARI SPECIALTY CHEM PVT. LTD","SHREE RAM SALES CORPORATION","SHREE RAMA MULTI TECH LTD","SHREE RAVI TRADING AND MANUFACTURING PVT LTD -SGTN","SHREE RAVIRAJ STEEL TRADERS ","SHREE RUBBER ENGINEERING PRODUCTS","SHREE RUBBERPLAST COMPANY PRIVATE LIMITED","SHREE SHAKTI ENTERPRISES PRIVA","SHREE SHAKTI INFRATECH","SHREE SHIV LOGISTICS","SHREE SHUBH INDUSTRIES","SHREE SHYAM TRADING","SHREE SIDHI VINAYAK ENTERPRISES","SHREE SIMANDAR IND ","SHREE SUPPLIERS","SHREE SWAMI SAMARTH AGENCIES","SHREE TEXTILE","SHREE TIRUPATI ENTERPRISES","SHREE TUBE MFG. CO. PVT. LTD","SHREE TULJA ENTERPRISES","SHREE UDYOG","SHREE VALLABH CHEMICAL","SHREE VARDHAMAN INDUSTRIES ","SHREE VENUS ENERGY SYSTEM PVT LTD","SHREE VINAYAKA TEXTILES","SHREEJI AGARBATTI WORKS","SHREEJI BAPA DYE CHEM","SHREEJI COMPONENTS","SHREEJI ENGINEERS","SHREEJI ENTERPRISE","SHREEJI GHANSHYAM INDUSTRIES","SHREEJI IMPEX","SHREEJI WOODCRAFT PRIVATE LIMITED","SHREEM ELECTRIC LIMITED","SHREEM INCORPORATION","SHREEMANGAL METALS","SHREENATH CHEMICAL INDUSTRIES","SHREENATHJI INDUSTRIES","SHRENIK & COMPANY","SHREYA INTERNATIONAL","SHREYA MICRO PLAST PVT.LTD.","SHREYANS CREATION GLOBAL LIMITED","SHRI BAJRANG POWER AND ISPAT LIMITED","SHRI BALAJI INDUSTRIAL PRODUCTS LTD","SHRI BALAJI VALVE COMPONENTS PRIVATE LIMITED","SHRI DAKSHINESHWARI MAA POLYFABS LIMITED","SHRI DINESH MILLS LTD","SHRI GANESHA GLOBAL GULAL PRIVATE LIMITED","SHRI HARI DYES & CHEMICALS","SHRI JAGDAMBA POLYMERS LTD","SHRI KRISHNA BRASS PRODUCTS","SHRI KRISHNA FABRICS","SHRI MADHAV CHEMIE","SHRI MEERA LABS PRIVATE LIMITED","SHRI NAVKAR METALS LTD","SHRI PARASNATH ENTERPRISES","SHRI PLASTO PACKERS PVT. LTD.","SHRI RAJA INDUSTRIES","SHRI RAM INTERNATIONAL","SHRI RAM MILL BOARD","SHRI RAM SALES CORPORATION","SHRI RAM WIRE PRODUCTS PRIVATE LIMITED","SHRI SAI TRAVELS SERVICES","SHRI SAINATH MARKETING","SHRI SARA POLYMERS PRIVATE LIMITED","SHRI TIRUMALA TRADERS","SHRI VINAYAK CHEMEX (I)PVT LTD","SHRI WARANA SAHAKARI DUDHUTAPADAK PRAKRIYA SANGH L","SHRIJEE ENTERPRISES","SHRIJI POLYMERS INDIA LTD","SHRIJI SOLUTIONS","SHRINATH ROTO PACK PRIVATE LIMITED","SHRINIVAS SUGANDHALAYA","SHRINIWAS ENGINEERING AUTO COMPONENTS PRIVATE LIMI","SHRIRAM INDUSTRIES ","SHRIRAM PISTONS & RINGS LIMITED","SHRIRAM VALUE SERVICES","SHRUSTHI PLASTICS PVT LTD","SHRUTI ENTERPRISE","SHRUTI INTERNATIONAL","SHUBH CHEM INDUSTRIES","SHUBHADA POLYMERS PRODUCTS ","SHUBHAM ENTERPRISE","SHUBHAM INTERNATIONAL","SHUBHAM POLYSPIN PVT. LTD","SHUBHAM REFRACTORIES","SHUBHAM TEX-O-PACK PVT LTD","SHWETA NET","SHWETHAS HYGIENE PRODUCTS","SHYAM AGENCY PRIVATE LIMITED","SHYAM CABLE INDUSTRIES","SHYAM CHEMICALS PVT. LTD.","SHYAM ENTERPRISES","SHYAM HERBAL INDUSTRIES","SHYAM PHARMA","SHYAM STEEL INDUSTRIES LIMITED","SI GROUP - INDIA PRIVATE LIMITED","SI GROUP INDIA P LTD","SIALCA INDUSTRIES","SIDDHANT KNITFAB","SIDDHANT WOVEN ELASTIC LLP","SIDDHARTH APPARELS","SIDDHARTH ENGINEERING CO","SIDDHARTH EXTRUSIONS PRIVATE L","SIDDHARTH GREASE & LUBES PRIVATE LIMITED","SIDDHARTH HEAVY INDUSTRIES PRI","SIDDHEE PRODUCTS PRIVATE LIMIT","SIDDHI CAST PRIVATE LIMITED","SIDDHI ENGINEERS","SIDDHI FORGING INDUSTRIES","SIDDHIKA COATINGS LIMITED","SIDHAANT LIFE SCIENCES PRIVATE","SIDHO PHARMA","SIDMARK SALES ENTERPRISE PVT.","SIEGWERK INDIA PVT LTD","SIEMENS LTD","SIENA ENGINEERING PRIVATE LIMITED","SIFLON DRUGS AND PHARMACEUTICALS PVT LTD","SIGMA INDUSTRIES","SIGMA SOLVENTS & PHARMACEUTICA","SIGMA TRADELINK","SIGNATURE INTERNATIONAL FOODS INDIA PRIVATE LIMITE","SIGNET CHEMICAL CORPORATION","SIGNET DENIM PRIVATE LIMITED","SIGNITY RF SOLUTIONS","SIGNODE INDIA LIMITED","SIGNOTRON (INDIA) PVT LTD","SIGNOVA PHARMA PVT LTD","SIKA INDIA PVT LTD ","SIL FOOD INDIA PRIVATE LIMITED","SILICA CERAMICA PVT LTD","SILICA GEL INDUSTRY","SILICON PRODUCTS (P) ASSOCIATE","SILOXANE AGGRANDIZE INNOVATIVE","SILVASSA PLASTICS","SILVER ARROW LOGISTICS SOLUTIONS PRIVATE LIMITED","SILVER ARROW WORLDWIDE SOLUTIONS","SILVER ENGG. CO.","SILVER ENTERPRISES","SILVER LINING EXIM SERVICES","SILVER MULLER RUBBER LTD.","SILVER PACK INDUSTRIES","SIMALIN CHEMICAL INDUSTRIES PV","SIMBER TRADING CO","SIMEM CONSTRUCTION & ENVIRONMENTAL ENGINEERING PRI","SIMERO VITRIFIED PVT LTD","SIMIT ENTERPRISE","SIMPLEX ENGINEERING & FOUNDRY ","SIMPLEX INFRASTRUCTURES LIMITE","SIMPOLO VITRIFIED PRIVATE LIMITED","SIMPSON & COMPANY LIMITED","SIMRAN INDUSTRIES PVT LTD","SIMTA CLEAR COATS PRIVATE LIMITED","SINBIOMEDIX INC","SINCO COMMUNICATION INDIA PRIV","SINDURI BIOTEC","SINGAPORE SOURCING & TECHNOLOG","SINGH PLASTICISERS & RESINS (I","SINGHAL COMMODITIES PVT LTD","SINOCHEM INDIA COMPANY PVT LTD","SINTEX INDUSTRIES LTD","SINTEX-BAPL LTD","SINTRON POLYMERS PVT LTD","SIRCA PAINTS INDIA LIMITED","SIRIUS CONTROLS PVT LTD","SISCO RESEARCH GROUP","SISKON ENGINEERING SERVICES","SISKON VENTURES PRIVATE LIMITED","SIVAGURUNATHAN INDUSTRIES","SIYARAM IMPEX PVT LTD.","SIYARAM PACKAGING PVT LTD","SIZER METALS PRIVATE LIMITED ","SJLT SPINNING MILLS PRIVATE LIMITED","SJLT TEXTILES PRIVATE LIMITED","SK SYSTEMS PRIVATE LIMITED","SKANAM INTERLABELS INDUSTRIES PRIVATE LIMITED ","SKAPS INDUSTRIES INDIA PRIVATE LIMITED-MUNDRA SEZ ","SKENAM INTERLABELS INDUSTRIES PRIVATE LIMITED","SKF INDIA LIMITED","SKFF (INDIA) PRIVATE LIMITED","SKI PLASTOWARE PVT LTD","SKILLS LIFE SCIENCES PRIVATE LIMITED","SKY METAL ALLOYS","SKYLITE INNOVATIONS PRIVATE LIMITED","SKYRISE CRADLE ASSOCIATES","SKYTECH CAPS AND CLOSURES","SL SQUARE FURNITURE PRIVATE LIMITED","SLANEY HEALTHCARE PRIVATE LIMITED","SLIMLITES ELECTRICALS PVT LTD","SM TISSUES PRIVATE LIMITED","SMARCO FOUNDRY CHEM","SMARCO INDUSTRIES PVT.LTD.","SMART LABORATORIES PVT LTD","SMART LINERS (INDIA) PVT LTD","SMART VALUE PRODUCTS AND SERVI","SMARTCHEM TECHNOLOGIES LIMITED","SMARTFOODZ LIMITED","SMB ENGINEERS P LTD ","SME ENTERPRISES","SMILAX LABORATORIES LIMITED","SMILEY MONROE RUBBER & ALLIED PRODUCTS (INDIA)PVT.","SMISEN CONTROLS PRIVATE LIMITED","SMIT & ZOON INDIA PRIVATE LIMITED","SMITH INDUSTRIAL CORP PVT LTD","SMITHA ENTERPRISES","SMITHERS OASIS INDIA PVT LTD","SMITHS & FOUNDERS (INDIA) LIMITED","SMK ENTERPRISE","SMK PETROCHEMICALS INDIA PRIVA","SMRUTHI ORGANICS LIMITED","SMS ELECTRICALS PVT LTD","SMS INDIA PRIVATE LIMITED","SNAB GRAPHIX (INDIA) PVT. LTD","SNAM ABRASIVES PRIVATE LIMITED","SNAM ALLOYS PRIVATE LIMITED","SNAPPLE APPLIANCES","SND PRO SPECIALITIES","SNEHAA PLASTO","SNF (INDIA) PVT LTD","SODIUM METAL PRIVATE LIMITED-N","SOFTECH PHARMA PRIVATE LIMITED","SOHAM HOUSEWARES","SOHAM INTERNATIONAL","SOHAM PRECIMEK INDIA","SOHAN DYE-CHEM PVT. LTD.","SOHAN INDUSTRIES PVT LTD","SOHAN LAL GUPTA","SOL BRAND SOLUTIONS PVT LTD","SOL CABLES","SOL INNOCAB PRIVATE LIMITED","SOL LIFESTYLE PVT. LTD.","SOLACE HUGIANIO PRIVATE LIMITED","SOLAR CHEMFERTS PVT LTD","SOLAR TECHNOCAST PVT.LTD.","SOLARA ACTIVE PHARMA SCIENCES LIMITED","SOLARIS CHEMTECH INDUSTRIES LI","SOLARIS STEEL PRIVATE LIMITED","SOLATIUM PHARMACEUTICALS","SOLCON ENGINEERS PRIVATE LIMITED","SOLENIS CHEMICALS INDIA PRIVAT","SOLIDUS HI-TECH PRODUCTS","SOLIFLEX PACKAGING PVT LTD","SOLITAIRE PHARMACIA PVT. LTD","SOLITEX CHEM","SOLON INDIA PRIVATE LIMITED","SOLTEX PETRO PRODUCTS LTD","SOLUMIKS HERBACEUTICALS LIMITED","SOLUNARIS PRIVATE LIMITED","SOLUNARIS PVT LTD","SOLUTIA CHEMICALS INDIA PVT LTD","SOLVAY SPECIALITIES INDIA PRIVATE LIMITED","SOM SHIVA (IMPEX) LTD","SOMA PUF METAL PRIVATE LIMITED","SOMU AND COMPANY","SOMU SOLVENTS PVT LTD","SONA BRUSH PRODUCTS","SONA PUMPS","SONA VETS PVT LTD","SONAL BRASS INDUSTRIES","SONAL INDUSTRIES","SONALI ENERGEES PVT.LTD.","SONARG PLASTICS PVT. LTD.","SONAROME PRIVATE LIMITED","SONATAPES PRIVATE LIMITED","SONI POLYMERS PVT LTD","SONI STEEL AND APPLIANCES PVT LTD","SONIC BIOCHEM EXTRACTIONS LIMITED","SONIC CHEMICALS","SONIC POWER SYSTEMS","SONYA INSULATORS","SOOTHE HEALTHCARE PRIVATE LIMI","SOPAN O&M CO PVT. LTD.","SOPAN PROCESS TECHNOLOGIES PRIVATE LIMITED","SOPREMA PRIVATE LIMITED","SORBE BIOTECHNOLOGY (INDIA) PR","SOUBHAGYA CONFECTIONERY PVT LTD","SOUJANYA COLOR PVT. LTD.","SOUND CRAFT","SOUNDARARAJA MILLS LTD","SOUTH ASSAM CARRIERS","SOUTH INDIA AGENCIES","SOUTHERN AGRO ENGINE PVT LTD","SOUTHERN FERRO STEELS LIMITED","SOUTHERN INDIA AQUATEK","SOUTHERN INDIA AQUCULTURE","SOUVENIR INTERNATIONAL","SPAC AROMAS","SPACE AGE PLASTIC INDUSTRIES","SPACE TELEINFRA PRIVATE LIMITE","SPACEAGE STORAGE CONCEPTS PRIVTE LIMITED","SPACEWOOD OFFICE SOLUTIONS PRIVATE LIMITED","SPACO CORPORATION","SPAN ASSOCIATES","SPAN CHEMICALS","SPAN FILTRATION SYSTEMS PRIVATE LIMITED","SPAN OVERSEAS","SPANS HEALTHCARE","SPARCONN LINE SCIENCES","SPARK FIBRES ","SPARK MARKETING","SPARKLE METAL WORKS PRIVATE LIMITED","SPARKLET ENGINEERS PVT LTD","SPARSH BIOTECH PVT LTD","SPARSH CARE PRODUCTS","SPARTAN ELECTRICALS","SPEAR LOGISTICS","SPEARE PET PVT LTD","SPECIAL RATE- NORTH","SPECIALITY INGREDIENTS","SPECIALITY PACKAGING","SPECIALTY POLYFILMS (INDIA) PVT LTD","SPECTRA CHEMICALS","SPECTRA DYES & CHEMICALS","SPECTROCHEM PVT LTD","SPECTRUM PETROMAC PRIVATE LIMITED","SPECTRUM SCIENTIFIC PVT LTD","SPEED A WAY PVT LTD","SPEED INTERNATIONAL INDIA PVT LTD ","SPEEDWELL ABRASIVES PVT. LTD.","SPEEDWELL TECHNOLOGIES PVT LTD","SPENTA GASKET & SEAL","SPERRY ENGINEERING PVT LTD","SPHERE INTERNATIONAL","SPICA ELASTIC PRIVATE LIMITED","SPICA NARROW FABRIC LLP","SPICON SPIZE","SPIKE INTERNATIONAL","SPIRAL FLEX INDUSTRIES","SPIRAX SARCO INDIA PRIVATE LIMITED","SPIRE INDIA","SPIROTECH HEAT EXCHANGERS PRIV","SPJ SOLAR TECHNOLOGY PRIVATE L","SPLASH JET IND. PVT LTD","SPLENDOUR PIPES & FITTINGS","SPM CONTROLS","SPORT GOODS MARKET","SPORTINGTOOLS RELISH PRIVATE LTD","SPOTON COATINGS PRIVATE LIMITED","SPRAYTEC INDIA LTD.","SPRING AIR BEDDING COMPANY IND","SPRING DYNAMICS PVT LTD","SPRING FASTNERS","SPRING INDIA","SPRINGFEEL POLYURETHANE FOAMS PRIVATE LIMITED","SPRINGLEAR FOAMS PRIVATE LIMITED","SPX FLOW TECHNOLOGY INDIA PVT LTD","SQUARE 1 PACKAGING & POLYMERS","SQUARE ONE DECOR","SRA SOLUTIONS","SRB INTERNATIONAL PRIVATE LIMITED","SREE ATREYA ENTERPRISES","SREE DHANAM AUTOMATION PRIVATE LIMITED","SREE KARPAGAMBAL MILLS LTD","SREE RAYALASEEMA ALKALIES & AL","SREE SAKTHI ENGG COMPANY","SREE SHAKTHI EQUIPMENT COMPANY","SREE VAISHNAVI ENTERPRISES","SREE VMR AGENCIES","SREELAKSHMI ENTERPRISES","SRF LIMITED","SRFS TELEINFRA","SRG POWER CONTROL SYSTEM","SRI ANUSHAM RUBBER INDUSTRIES ","SRI BALAJI ENTERPRISES","SRI BALAJI METAL INDUSTRIES","SRI BALAJI MICRO PULVERISING MILL","SRI BALAJI WIRE INDUSTRIES","SRI CHAKRA POLY PLAST INDIA PRIVATE LIMITED","SRI CHAKRA TRADING CORPORATION","SRI DHANALAXMI INDUSTRIES","SRI GOMUKI TEX CHEM PRIVATE LIMITED","SRI HARI LABS","SRI HARI PACKAGING INDUSTRIES PVT LTD","SRI HARIHARAN TRADERS","SRI KANNIKA PARAMESWARI STORES","SRI KUMAR AGENCIES","SRI KUMARAN TEX","SRI LAKSHMI ENTERPRISES","SRI LAKSHMI HANDLOOMS","SRI MUKTA AGENCY","SRI MULTIMAG PRIVATE LIMITED","SRI MURUGAR SPINNING MILL","SRI RAMBALAJI CHEMICALS","SRI RAMKARTHIC POLYMERS PRIVATE LIMITED","SRI SAI AGRO SYNDICATE","SRI SAI SHARADA TEXTILES","SRI SELVAKUMAR MILLS (P) LTD","SRI SHANDAR SNACKS PVT LTD","SRI SRI AYURVEDA TRUST","SRI TECHNO ENGINEERING COMPANY","SRI TOOLS INDUSTRIES","SRI VARSHA FOOD PRODUCTS INDIA LIMITED","SRI VASAVI ADHESIVE TAPES PVT ","SRI VEERA TEX","SRIRAM ENGIEERS","SRIVARI ASSOCIATES","SRIVEDA SATTVA PRIVATE LIMITED","SRIVILAS HYDROTECH PRIVATE LIMITED","SRIYANSH KNITTERS","SRM EXOFLEX PVT. LTD.","SRP AUTOMATION PRIVATE LIMITED","SRP PHARMA FOIL PRINTING","SRS INDUSTRIES","SS","SS SUPPLY CHAIN SOLUTIONS PRIVATE LIMITED","SS ULTRATECH","SSEC FOUNDRY EQIPMENT PVT LTD","SSF PLASTICS INDIA PVT LTD","SSPL POLYMERS PRIVATE LIMITED","SSV GROUP","SSV TECHNOCRATES.","SSV VALVES","ST MARYS RUBBERS PVT LTD","STACUS SOLUTION PRIVATE LIMITED","STALLEN SOUTH ASIA PRIVATE LIMITED","STALLION ENTERPRISE","STALLONE OVERSEAS","STANDARD ALLOYS INDUSTRIES","STANDARD CAPACITORS PRIVATE LIMITED","STANDARD ENGINEERS","STANDARD GREASES & SPECIALITIE","STANDARD INC","STANDARD LABORATORIES","STANDARD LOGISTICS","STANDARD PRESS (INDIA) PRIVATE LIMITED","STANDARD SOAP INDUSTRIES","STANLEY ENGINEERED FASTENING","STANLEY SAFETY PRODUCTS PVT LTD","STANLUBES & SPECIALITIES","STANPACK INDIA LTD","STANVAC CHEMICALS INDIA LTD","STANVAC MED LIMITED","STANVAC PAINTS LIMITED","STANVAC PRIME PRIVATE LIMITED","STAR CYLINDERS","STAR DYES & INTERMEDIATES","STAR EARTH MINERALS PVT LTD","STAR FLUOROPOLYMERS","STAR LABELS","STAR RISING ENERGY PVT. LTD.","STAR TESTING SYSTEMS - SAK","STAR9 REFRATECH","STARLIT EXPORTS","STAROL MARKETING PRIVATE LIMIT","STARSHINE MANUFACTURING CO PVT","STASH-PRO PAPERS","STATE ENTERPRISES","STATFIELD EQUIPMENTS PRIVATE LIMITED","STATUS MEDICAL EQUIPMENTS","STC INDIA PRIVATE LIMITED","STECOL INTERNATIONAL PRIVATE LIMITED","STEEL & INDUSTRIAL FORGINGS LTD.","STEEL AUTHORITY OF INDIA LTD","STEEL CON","STEEL SHEET PILING SOLUTIONS INDIA","STEEL SMITH","STEEL STRONG VALVES (I) PVT LT","STEELCAST LIMITED","STEELCO GUJARAT LTD.","STEELCON IMPEX PVT.LTD.","STEEL-O-FAB ENGINEERS","STEELS STRONG VALVES ","STEER ENGINEERING PVT LTD","STELLENCE PHARMSCIENCE PVT LTD","STERICON PHARMA PVT LTD","STERILE INDIA PRIVATE LIMITED","STERIMED","STERIMED MEDICAL DEVICES PVT. LTD.","STERIPAC ASIA","STERLING ABRASIVES  LIMITED","STERLING AUXILIARIES PVT. LTD.","STERLING FREIGHT PVT LTD","STERLING LAB","STERLING PRINT HOUSE PVT.LTD.","STERLING SOLID TYRES PVT LTD ","STERLING TAPES LIMITED","STERLITE LUBRICANTS PVT LTD","STERLITE TECHNOLOGIES LTD.","STERN INGREDIENTS INDIA PRIVATE LIMITED","STEVE PIERRE FERNANDES","STEWOLS INDIA (P) LTD.","STI APPAREL AUTOMATION PVT LTD","STITCHKRAFT","STONE TOOLS & CHEMICALS PVT","STONEFIELD FLAVOURS PRIVATE LIMITED","STOOSA","STOPAK INDIA PVT LTD","STORIA FOODS & BEVERAGES PRIVATE LIMITED","STOVEKRAFT PRIVATE LIMITED","STP INFRACARE PRIVATE LIMITED","STP LIMITED","STRASSENBURG PHARMACEUTICALS LIMITED","STRATA GEOSYSTEMS INDIA PVT LT","STRATACHEM SPECIALITIES PRIVATE LIMITED","STRECH BANDS(GUJARAT )PVT. LTD.","STRIDES SHASUN LTD","STRIPCO SPRING PVT LTD","STYLAM INDUSTRIES LIMITED","SUBAM PAPERS PRIVATE LIMITED","SUBHAM POLES PROJECTS (P) LTD.","SUBHASRI PIGMENTS PVT. LTD.","SUBHODHAN ENGINEERS PUNE PVT LTD","SUBLIME FOODS LIMITED","SUCCESS UDYOG PRIVATE LIMITED","SUDANA PRINTERS","SUDARSHAN CHEMICAL INDUSTRIES LIMITED","SUDARSHAN FARM CHEMICALS INDIA PRIVATE LIMITED","SUD-CHEMIE INDIA PVT LTD","SUDEEP PHARMA PRIVATE LIMITED","SUDESH CHEMICALS PVT. LTD.","SUDHA GUPTA","SUDHAMRUT SPICES","SUDHARSHAN CHEMICAL INDUSTRIAL LIMITED ","SUDHIR SWITCHGEARS PVT LTD","SUGAM CHEMICALS","SUGUNA FOODS PRIVATE LIMITED","SUHAVI PHARMA","SUJAL DYE CHEM PVT. LTD.","SUJAN IMPEX PVT. LTD.","SUJAS LEATHER CLOTH MFG.CO.PVT","SUKETU ORGANICS PVT LTD","SUKHDATA FOODS PVT LTD","SUKRUT ELECTRIC COMPANY PRIVATE LIMITED","SULFAST CHEMICALS INDUSTRIES.","SULOCHANA COTTON SPINNING MILL","SULPHER MILL LTD.","SULUX PHOSPHATES LIMITED","SULZER PUMPS I LTD ","SUMAN ENGINEERING","SUMAN STEEL","SUMANGAL CASTING PRIVATE LIMIT","SUMANGAL FORGING P LTD","SUMECH ENGINEERING AND TECHNOL","SUMERU TRADELINK PVT. LTD.","SUMIL CHEMICAL INDUSTRIES  PVT","SUMILAX SMT TECHNOLOGIES PRIVATE LIMITED","SUMIP COMPOSITES PVT LTD.","SUMITOMO CHEMICAL INDIA LIMITED","SUMITOMO CORPORATION INDIA PVT","SUMITRON EXPORTS PRIVATE LIMITED","SUMO LIFE CARE PVT LTD","SUMUKHA HITECH PRODUCTS INDUST","SUN CHEMICALS","SUN COLORANTS PVT LTD","SUN HOME CARE PRODUCTS","SUN INDUSTRIES","SUN PHARMA LABORATRIES LTD.","SUN PHARMACEUTICALS PVT. LTD.","SUN SHINE FOOD PRODUCTS","SUN TRUST ALUMINIUM PVT LTD.","SUNANDA SPECIALITY COATINGS P ","SUNBEAM APPLIANCES","SUNCARE PHARMACEAUTICALS PVT. LTD","SUNCHEM CORPORATION","SUNCHEM INDUSTRIES","SUNCORE ENGINEERING INDIA","SUNDARAM BRAKE LININGS LTD","SUNDARAM INDUSTRIES PVT  LTD","SUNDARAM MOTORS","SUNFLAG CHEMICALS PVT LTD","SUNFLAG IRON & STEEL COMPANY L","SUNFORD HEALTHCARE PVT LTD","SUNFRESH AGRO INDUSTRIES PRIVATE LIMITED","SUNGOV ENGINEERING PRIVATE LIMITED","SUNIL TWISTER","SUNISH DYE CHEM","SUNITOMO CHEMICAL INDIA PVT LT","SUNKID ELECTRO SYSTEMS","SUNLARGE FILAMENTS PRIVATE LIMITED","SUNLARGE INDUSTRIES PRIVATE LIMITED","SUNLIFE SCIENCES PVT LTD","SUNLINK PHOTOVOLTAIC PRIVATE LIMITED","SUNLITE ECOCHEM","SUNLITE FOOT CARE COMPANY","SUNNY ENGINEERING INDUSTRIES","SUNNY ENT","SUNNY ENTERPRISES","SUNNY IRON AND STEEL PROCESSORS PRIVATE LIMITED","SUNNY PRODUCT","SUNPURE TECHNOLOGIES  PVT LTD","SUNREST LIFESCIENCE PVT LTD","SUNRISE COPIERS PVT LTD","SUNRISE CORRUGATED IND PVT LTD","SUNRISE ENTERPRISES","SUNRISE GLASS INDUSTRIES","SUNRISE MARKETING","SUNRISE SPINNERS","SUNSHELL POWER","SUNSHIELD CHEMICALS LTD.","SUNSHINE INDIA INC.","SUNSHINE INDUSTRIES","SUNSHINE INDUSTRIES/RANJEETSINGH GULATI","SUNSHINE MARKETING","SUNSHINE ORGANIC PRODUCTS","SUNSHINE ORGANICS PVT LTD ","SUNSHINE PAP TECH PRIVATE LIMITED ","SUNSHINE WORLDWIDE","SUNSPHERE PRIVATE LIMITED","SUNSTAR SALES AND DISTRIBUTION PRIVATE LIMITED","SUNTARA COSMETICS PRIVATE LIMITED","SUNTECH AUTOMOBILE PRODUCTS","SUNTECH INDUSTRIES","SUNTECK HI TECH PRIVATE LIMITED","SUNTRON SYSTEMS","SUNWAYS (INDIA) PVT LTD","SUNWAYS INDIA PVT LTD","SUNWAYS LABORATORIES PVT LTD","SUNWAYS ROHTO PHARMACEUTICAL P","SUPACK INDUSTRIES PVT LTD","SUPER BOND ADHESIVES PVT.LTD.","SUPER CHEM PLAST","SUPER ENTERPRISES","SUPER HOZE INDUSTRIES PRIVATE ","SUPER IMPEX","SUPER MARKETING","SUPER SHEAR LINE","SUPER SULPHATES","SUPER TRANSPORTS (P) LTD","SUPER URECOAT INDUSTRIES","SUPERBOND ADHESIVE INDUSTRIES","SUPERFLO FILTERS PVT LTD","SUPERINDIA (FRP) PRIVATE LIMITED","SUPERMAX FASTENERS","SUPERMAX PERSONAL CARE PVT LTD","SUPERON SCHWESSTECHNIK INDIA L","SUPERPACKS BUSINESS SOLUTIONS LLP","SUPERSONIC DISTRIBUTION SERVIC","SUPRABHA PROTECTIVE PRODUCTS P","SUPRAVENI CHEMICALS P LTD","SUPREET CHEMICALS PVT. LTD.","SUPREME AGENCIES","SUPREME BITUCHEM INDIA PRIVATE LIMITED","SUPREME CHEMICAL INDUSTRIES","SUPREME INDUSTRIAL FASTENERS","SUPREME INDUSTRIES LTD - TLG","SUPREME NUTRI GRAIN PRIVATE LIMITED","SUPREME OFFSHORE CONSTRUCTIONS","SUPREME PETROCHEM LIMITED","SUPREME ROLLS AND SHEARS PVT L","SUPREME SURFACTANTS (P) LTD.","SUPREME TRADELINES","SUPREME TREON PRIVATE LIMITED","SUPRIYA LIFESCIENCE LIMITED","SUPRIYA PACKAGING PRIVATE LIMITED","SUPRIYA PACKERS","SURABHI PACKAGING INDUSTRIES","SURAIYA PVT.LTD.","SURAJ LTD","SURAKSHA PRODUCTS PVT. LTD","SURANA ENTERPRISES","SURCOAT PAINTS PVT.LTD.","SURECON FASTENING AND ENGINEERING PVT LTD","SURELOCK PLASTICS PRIVATE LIMI","SURENDRA ELASTOMERS PRIVATE LIMITED","SURENDRA ELASTOMERS PVT.LTD.","SURESEAL PVT LTD","SURESH SYNTHEICS","SURFA COATS INDIA PVT LTD","SURFACE PREPARATION SOLUTIONS & TECHNOLOGIES P LTD","SURFIS KLENZ (INDIA) PVT LTD","SURGIPLUS","SURINDERA CYCLES PVT LTD","SURJAN TRADING PRIVATE LIMITED","SURU INTERNATIONAL PVT.LTD.","SURUCHI SPICES PVT LTD","SURVIVAL TECHNOLOGIES PVT.LTD.","SURYA COATS PVT LTD","SURYA EXIM LTD","SURYA FASTENERS PVT LTD","SURYA GLOBAL STEEL TUBES LTD.","SURYA POLYFILMS","SURYA ROSHNI LTD","SURYA TEXTECH","SURYAA STEELS","SURYALAKSHMI COTTON MILLS LTD","SURYAM ENTERPRISES","SUSHAIL INDUSTRIES","SUSHEEL ENGINEERING CORPORATION","SUSTAINABLE SCIENCE PVT. LTD.","SU-VASTIKA SYSTEMS PRIVATE LIMITED","SUVERA FLUID POWER PRIVATE LIMITED","SUVOCHEM INDUSTRIES PVT LTD","SUYAASH PHARMACEUTICALS","SUYASH IMPEX PVT LTD","SUYESH HOMOEO PHARMACY","SUYOG ELECTRICALS LIMITED","SUYOG RUBBER (INDIA)PVT LTD","SUZLON ENERGY LTD","SVA LOGISTICS PVT LTD","SVA RIKKON LUBES PRIVATE LIMITED","SVAM TOYAL PACKAGING INDUSTRIES PRIVATE LIMITED  ","SVC INFOTECH INDIA PRIVATE LIMITED","SVE CASTINGS PVT. LTD","SVOJAS ASSOCIATES","SVP LIFE SCIENCES","SVP PACKING INDUSTRY PVT LTD","SW METAFORM PRIVATE LIMITED","SWADEV CHEMICALS","SWAJIT ABRASIVES PRIVATE LIMITED","SWAL CORPORATION LTD","SWAMINARAYAN AKSHARPITH SAHITY","SWAN ALLUMINIUMS PVT LTD","SWAPNA PRINTING WORKS PVT. LTD","SWAPNA TRADING CO.","SWARAJ AUTOMOTIVES LIMITED","SWARAJ INDUSTRIAL & DOMESTIC APPLIANCES PVT LTD","SWARNA OIL SERVICES","SWAROOP CHEMICALS","SWASAN CHEMICALS PRIVATE LIMIT","SWASTIK ENGG & VALVES MFG","SWASTIK HOUSEWARE","SWASTIK INDUSTRIES ","SWASTIK LUBRICANTS PRIVATE LIMITED","SWASTIK NX ","SWASTIK OIL PRODUCTS MFG COMPA","SWASTIK TRADING CORPORATION (BOMBAY) PROP NAVDEEP ","SWASTIK WIRE INDUSTRIES","SWATI INTERIOR CONCEPTS PRIVATE LIMITED","SWD INDUSTRIES","SWEET CONFECTIONERY PRIVATE LIMITED","SWEET INDUSTRIES INDIA PRIVATE LIMITED","SWETA ENTERPRISES","SWISS BIO HERBALS","SWISS BIOLAB","SWISS BIOTECH","SWISS BIOTECH PARENTRALS","SWITCH O MATICS INDIA PVT LTD","SWITCHGEAR AND CONTROL","SYMBIOSIS AGRO MANAGEMENT PRIVATE LIMITED","SYMBIOTEC PHARMALAB PVT. LTD.","SYMEGA FOOD INGREDIENTS LTD","SYMRISE PRIVATE LIMITED","SYNCO INDUSTRIES LTD.","SYNCOM HEALTHCARE LTD","SYNDICATE TRANSLINES","SYNERGY (INDIA) MARKETING PRIVATE LIMITED","SYNERGY BAXIS ENTERPRISES PVT LTD","SYNERGY GREEN INDUSTRIES PRIVA","SYNERGY INDUSTRIAL SERVICES PRIVATE LIMITED","SYNERGY POLY ADDITIVES PVT LTD","SYNERGY POLYMERS(INDIA) PRIVAT","SYNKROMAX BIOTECH PRIVATE LIMITED","SYNOKEM PHARMACEUTICALS LTD","SYNPOL PRODUCTS PVT.LTD","SYNTHETIC & POLYMERS PVT LTD","SYNTHETIC MOULDERS LIMITED","SYNTHETIC PACKERS PVT LTD","SYNTHITE INDUSTRIES PRIVATE LIMITED","SYNTHOCHEM PVT. LTD.","SYNTHOKEM LABS PRIVATE LIMITED","SYNTHROMA LABORATORIES","SYNTRON INDUSTRIES PVT.LTD","SYNTURAL CHEMICALS LLP","SYSKA LED LIGHTS PRIVATE LIMITED","SYSTA MET INDIA PRIVATE LIMITE","SYSTEMAIR INDIA PVT LTD","SYSTEMATIC GROUP OF COMPANIES","SZDFBHHJVGHJVGBJH","T CON FOOD PRODUCTS","T G INTERLINIG PVT LTD","T STANES COMPANY LIMITED","T V PLASTICS","T V PRECISION TOOLS","T. S. MANUFACTURING CO","T.C COMMUNICATION PVT LTD","T.K. INDUSTRIES","T.K. METAL WORKS","T.L.DISTRIBUTORS PRIVATE LIMITED","TADE POWERTECH PRIVATE LIMITED","TAG CORPORATION","TAGROS CHEMICALS INDIA PVT LTD","TAINWALA PERSONAL CARE PRODUCT","TAIYO PET PRODUCTS PRIVATE LIMITED","TAKEMOTO YOHKI INDIA PRIVATE LIMITED","TALIN MODULAR OFF FURNITURE","TAMBOLI CASTING LTD","TAMIL NADU NEWS PRINT AND PAPE","TAMILNADU NEWSPRINT AND PAPER LIMITED - UNIT 2","TANVIKA POLYMERS PVT.LTD.","TAPRATH ELASTOMERS LLP","TAPRATH IMPEX","TAPRATH POLYMERS PVT LTD","TARA PAINTS & CHEMICALS","TARA POLYMER TECHNOLOGIES","TARAK CHEMICALS LTD","TARUS FORWARDERS P LTD","TASHKENT OIL COMPANY PVT LTD","TASTY BITE EATABLES LIMITED","TATA CERAMICS LTD","TATA CHEMICALS LIMITED","TATA COFFEE LIMITED","TATA FICOSA AUTOMOTIVE SYSTEMS PRIVATE LIMITED","TATA GLOBAL BEVERAGES LIMITED","TATA INTERNATIONAL LTD","TATA MOTORS LIMITED","TATA POWER SOLAR SYSTEMS LIMITED","TATA SKY LIMITED","TATA STEEL DOWNSTREAM PRODUCTS LIMITED","TAURLUBE PETROCHEMICALS PRIVATE LIMITED","TAURUS CHEMICALS PRIVATE LIMITED","TAURUS FORWARDERS P LTD","TAYLOR GLASSWARE PRIVATE LIMITED","TB KAWASHIMA AUTOMOTIVE TEXTILE ( INDIA ) PVT LTD","TCPL PACKAGING LTD","TEA BOOKING RETAIL","TEAM LABEL INDIA PRIVATE LIMITED","TEAM LEADER LOGISTICS PVT. LTD","TEAMONE LOGISTICS SOLUTIONS PRIVATE LIMITED","TEAMTHAI-ASHIQUE CHEMICALS & COSMETICS","TECHFLOW ENTERPRISES PVT LTD","TECHNICAL DRYING SERVICES (ASI","TECHNICAL TRADE LINKS","TECHNO AUTO PRODUCTS","TECHNO CHEMICALS","TECHNO COLOURS CORPORATION","TECHNO DESIGNS","TECHNO ENGINEERING INDUSTRIES","TECHNO FABRICS","TECHNO FLEX CABLES","TECHNO IMAGING SOLUTIONS","TECHNO INDUSTRIES LTD.","TECHNO KLG LLP","TECHNO PACKS","TECHNO PRODUCTS DELVELOPMENT PRIVATE LTD.","TECHNO WAXCHEM PRIVATE LTD","TECHNOCRAFT INDUSTRIES I.LTD.","TECHNOFOUR","TECHNOSALES MULTIMEDIA TECHNOLOGIES PVT LTD","TECHNOSYS EQUIPMENTS PRIVATE L","TECHNOVA IMGING SYTESM PVT LTD","TECHNOVA TAPES (INDIA) PVT. LTD.","TECHNOVAA PLASTIC INDUSTRIES P","TECKNOTROVE SYSTEMS (I) PVT. L","TECKNOWELD ALLOYS (INDIA )PVT ","TECKRAFT INTERIORS PRIVATE LIMITED","TECNIK FLUID CONTROLS","TECNIK VALVES PRIVATE LIMITED","TECTONICS","TECTYL OIL AND CHEMICALS INDIA","TECUMSEH PRODUCTS INDIA PRIVAT","TEEKAY TUBES PVT LTD","TEEMAGE BUILDERS PVT LTD","TEENA LABS LIMITED","TEGA INDUSTRIES (SEZ) LTD.","TEGA INDUSTRIES LTD.","TEJASWI PLASTIC PRIVATE LIMITED","TEK GRAFIX","TEKNI PLEX INDIA PRIVATE LIMIT","TEKNOMEC TECHNOLOGIES PVT LTD.","TEKNOVACE COATINGS","TEKROL SPECIALITIES PVT LTD","TEKSI SHAH","TELECOM NETWORK SOLUTIONS PVT ","TELEFLEX MEDICAL PVT LTD","TELEMATRIX ENGINEERS & CONSULTANTS","TEMPEL PRECISION METAL PRODUCTS INDIA PVT LTD","TEMPLE PACKAGING PVT. LTD.","TEMPSENS INSTRUMENTS INDIA PVT","TENORA CHEM PVT LTD ","TERACOM (FRP) PRIVATE LIMITED","TERRAM GEOSYNTHETICS PVT LTD","TERYAIR EQUIPMENT PVT LTD","TESSITURA MONTI INDIA PRIVATE LIMITED","TEST CELSIUS HEALTH CARE PRIVATE LIMITED","TEX YEAR INDUSTRIAL ADHESIVE ","TEXEL PRESS COMP.","TEXLLENCE CNC PRIVATE LIMITED","TEXMO PRECISION CASTINGS","TEXPLAS INDIA PVT LTD","TEXVENTURES LLP","TFI FILTRATION (INDIA) PVT.LTD","THACKER INCORPORATION","THAKKAR TRADING COMPANY","THE ANUP ENGINEERING LIMITED","THE ARASAN ALUMINIUM INDUSTRIES(P) LTD.,","THE BOMBAY SEEDS SUPPLY CO","THE CENTRAL COIR MILLS","THE CLOTHING COMPANY","THE DHARAMSI MORARJI CHEMICAL CO LTD","THE GLOBAL FASHION","THE GLOBE RADIO COMPANY","THE HIMALAYA DRUG COMPANY","THE INDIAN ELECTRIC CO.","THE INDIAN EXPRESS PVT LTD","THE KAY-CEE AGENCIES","THE KERALA COIRMATS & MATTINGS COOP SOC LTD NO 346","THE KERALA MINERALS & METALS","THE KERALA STATE HOMOEOPATHIC CO-OPERATIVE PHARMAC","THE METAL POWDER COMPANY LTD","THE ORIENT LITHO PRESS","THE ORIENTAL CHEMICAL WORKS PV","THE PHARMACEUTICALS CORPN (IM)","THE SLIPPER FACTORY LLP","THE SPUNPIPE AND CONSTRCUTION CO BARODA PVT LTD","THE SUKHJIT AGRO INDUSTRIES","THE SUPREME INDUSTRIES LIMITED","THE SUPREME INDUSTRIES LTD ROTOMOULDING DIVISON","THE SWASTIK PHARMACEUTICALS","THE TOTGARS CO-OP SALES SOCIETY LTD","THE TRAVANCORE COCHIN CHEMICALS LTD","THE TYRE SHOPPE","THE VBCL STORE","THE WEST INDIA POWER EQUIPMENT","THE WESTERN INDIA GENUINE GHEE CO PVT LTD","THEMA NUTRIMENT AND PACKAGING PRIVATE LIMITED","THEOCOM FOOD PRODUCTS PRIVATE LIMITED","THEOGEN PRIVATE LIMITED","THERMAL INSTRUMENT INDIA PRIVATE LIMITED","THERMAX LTD.","THERMO CABLES LTD","THERMO FISHER SCIENTIFIC INDIA","THERMO HOUSE WARES PRIVATE LTD","THERMOCHEM PROCESSES PRIVATE LIMITED","THERMOPADS PVT.LTD","THERMOSYSTEMS PVT.LTD","THERMOTECH ENGINEERING AND SERVICES PVT LTD","THERM-X INDUSTRIAL EQUIPMENTS PRIVATE LIMITED","THEYSOHN EXTRUSIONSTECHNIK (I) PRIVATE LIMITED","THINQ PHARMA-CRO LIMITED","THIRUMALAI CHEMICALS LTD","THOMAS SOCTT (INDIA) LIMITED","THREADS INDIA LIMITED","THURS ORGANICS PRIVATE LIMITED","THURSDAY CHEMICALS","THYSSENKRUPP ELECTRICAL STEEL INDIA PVT LTD","TIBRIWAL PLASTICS PRIVATE LIMITED","TIDAN FORGING PVT. LTD.","TIDC INDIA LTD","TIDE INDUSTRIES","TIDE WATER OIL CO.(INDIA) LTD","TIFFANY FOODS","TIKI TAR DANOSA INDIA PRIVATE LIMITED","TIKI TAR INDUSTRIES ( BARODA ) LIMITED","TILDA LOGISTICS AND CRANE SERV","TIME TECHNOPLAST LTD.","TIMES FIBREFILL PVT LTD","TIMKEN INDIA LIMITED","TIONG WOON PROJECT & CONTRACTI","TIPCO INDUSTRIES LTD.","TIRATH TECHNOLOGIES","TIRTH AGRO TECHNOLOGY PVT.LTD","TIRUMALA MILK PRODUCT PVT LTD","TIRUPATI COLOUR PENS PVT. LTD.","TIRUPATI ENTERPRISES","TIRUPATI PLASTOMATICS PVT LTD","TIRUPATI SALES CORPORATIONS","TIRUPATI SPECIALTY GRAPHITE PRIVATE LIMITED","TIRUPATI SPRINKLERS PRIVATE LIMITED","TIRUPITI/ELBE/AJ TECH GROUP","TIT BIT FOODS INDIA PVT LTD. ","TITAN BIOTECH LIMITED","TITAN CRUSHING MACHINERY PRIVA","TITAN PAINTS & CHEMICALS LIMIT","TLT ENGINEERING INDIA PVT. LTD","TMC TRANSFORMERS INDIA PRIVATE LIMITED","TMPL MACHINES","TMVT INDUSTRIES PVT. LTD","TOKYO PLAST INTERNATIONAL LTD","TOLSHI INDIA PRIVATE LIMITED","TOM HERMES PRODUCTIONS","TOOL HOLDERS PVT LTD","TOOLS N WHEELS","TOP INDIA LOGISTICS","TORAY INDUSTRIES INDIA PRIVATE LIMITED","TORREL COSMETICS LIMITED","TOSHIBA MACHINE (CHENNAI) PRIVATE LIMITED","TOSHIBA TRANSMISSION & DISTRIB","TOSHNIWAL HYVAC PVT LTD","TOSHNIWAL INDUSTRIES PRIVATE LIMITED","TOSHNIWAL INSTRUMENTS (MADRAS) PRIVATE LIMITED","TOSOH INDIA PVT LTD","TOTAL HEAT TREATMENTS SOLUTIONS PRIVATE LTD","TOTAL OIL INDIA PVT. LTD.","TOTAL PRINT SOLUTIONS PRIVATE LIMITED","TOTAL SEA LAND LOGISTICS PRIVA","TOTALE GLOBAL PVT LTD","TOUCHPOINT CONSULTANCY PVT LTD","TOUGH CASTING PRIVATE LIMITED","TOUGH COLOR RESINS PVT. LTD","TOYKRAFT ENTERPRISES","TOYO INK ARETS INDIA PRIVATE LIMITED","TOYO INK INDIA PRIVATE LIMITED","TOZAI SAFETY PRIVATE LIMITED","TPAC PACKAGING INDIA PRIVATE LIMITED","TPC PACKAGING PRIVATE LIMITED","TPI INDIA LIMITED","TPRG FRAGRANCES PVT. LTD.","TRACTEL TIRFOR INDIA PRIVATE LIMITED.","TRACTORS AND FARM EQUIPMENT LIMITED","TRADE LINK","TRADELINKS","TRADEZO","TRAFALGAR HOUSE","TRAK WIRE LINK INDUSTRIES","TRANS ORGANICS INDIA PVT LTD","TRANSFAR CHEMICAL INDIA PVT LTD","TRANSTECH CORPORATION","TRANTER INDIA PRIVATE LIMITED","TRAVANCORE COCOTUFT PVT. LTD.","TREMCO INDIA PVT LTD","TRETA AGRO PRIVATE LIMITED","TRIBENI TECHNOCOM LIMITED","TRIBHUVAN POLYMERS PVT. LTD.","TRIBO INDUSTRIES","TRICE CHEMICALS","TRICON ENERGY INDIA PRIVATE LIMITED","TRICON POLYMERS PVT LTD","TRIDENT AUTOMOBILES PVT LTD","TRIDENT BANDRA KURLA","TRIDENT PNEUMATICS (P) LTD","TRIDENT RUBBER PVT LTD","TRIDEV PIPE & FITTING","TRIGAL HOMEWARES INDIA PVTLTD","TRIGLOBAL BIOSCIENCE PVT LTD","TRILOK FOOD INDIA","TRIMAT HOUSEWARE","TRIMURTI ENGINEERING TOOLS PRIVATE LIMITED","TRIMURTI PRODUCTS","TRIMURTI STAMPINGS","TRINITY CYCLES INDIA PRIVATE LIMITED","TRINITY PACKAGING CO. PVT.LTD","TRIO ELEVATORS CO INDIA LTD.","TRIOMECH ENGG PVT LTD","TRIONES EXIM PRIVATE LIMITED","TRISHA FLEXIPACK","TRISHA POLYPLAST PRIVATE LIMITED","TRISHUL SACCHARIN PRIVATE LIMITED","TRISIS VENTURES","TRISTAR CHEMICALS","TRISTAR INFRATECH ENTERPRISES","TRITON VALVES LTD","TRIVENI ENTERPRISES","TRIVENI GLOBAL PRIVATE LIMITED","TRIVENI POLYMERS PVT LTD","TROPICAL INDUSTIES INTERNATION","TROPILITE FOODS PVT LTD.","TROUW NUTRITION INDIA PRIVATE LIMITED","TROY CHEMICAL INDIA PVT. LTD.","TRU VISION POLYMERS","TRUBLU TECHNOLOGIES PVT  LTD","TRUE MEDITECH SOLUTION","TRUEVALUE MARKETING SERVICES PVT LTD","TRUSKIN GLOVES PRIVATE LIMITED","TRUSTED TRADING CORPORATION","TRUSTIN TAPE PRIVATE LIMITED - SEZ","TSB TUBES & TANK","TTK HEALTH CARE LIMITED","TTK PRESTIGE LIMITED","TUBACEX PRAKASH INDIA PVT. LTD","TUBE INVESTMENTS OF INDIA LTD","TUFROPES PVT LTD","TUFWUD DOORS AND ACCESSORIES PRIVATE LIMITED","TULA ENGINEERING PRIVATE LIMITED","TULIP CASTING PVT LTD","TULIP DIAGNOSTICS PRIVATE LIMITED","TULSYAN NEC LTD","TURAKHIA TEXTILES PVT LTD","TURBO BEARINGS PVT.LTD","TURBO CAST (INDIA) PVT.LTD","TURBO LIGHTS","TURKHIA AGENCY","TURNMAX MACHINE TOOLS","TUSHACO PUMPS","TVS AUTOMOBILE SOLUTIONS LIMITED","TVS LOGISTICS SERVICES LIMITED","TVS SRICHAKRA LIMITED","TWININGS PVT. LTD.","TYKHE AUTOMOTIVES","TYRE EXPERTS LLP","TZAR INDUSTRIES PRIVATE LIMITED","U D CAPS PRIVATE LIMITED","U G I ENGINEERING WORKS PVT. LTD.","U.B. DET SURF INDUSTRIES","U.P VIKAS CORPORATION","UB EQUIPMENTS PRIVATE LIMITED","UB STAINLESS LIMITED","UBIK SOLUTIONS PRIVATE LIMITED","UCHITHA GRAPHIC PRINTERS PVT. LTD.","UCS CUTTING SOLUTIONS LLP","UDAY PYROCABLES PVT LTD","UFLEX LIMITED ( CHEMICAL DIVIS","UFS LOGISTICS PVT LTD","UI VR PRIVATE LIMITED","UJAAS ENERGY LIMITED","UJJWAL OVERSEAS INDIA PRIVATE LIMITED","UJWAL PHARMA PVT LTD","ULTIMA SEARCH","ULTIMATE ALLOYS PVT LTD","ULTIMATE CHEM ( INDIA) PVT.LTD","ULTIMATE PACKAGING","ULTRA FILTECH","ULTRA FILTER INDIA PVT LTD","ULTRA FOIL","ULTRA TOOLS","ULTRACAB (INDIA) LIMITED","ULTRAFINE MINERAL & ADMIXTURES PRIVATE LIMITED","ULTRAKRETE CONSTRUCITON CHEMICALS PRIVATE LIMITED","ULTRAMARINE & PIGMENTS LTD","ULTRAPACK INCORPORATION","ULWAL PHARMA PVT LTD","UMA DYECHEM INDUSTRIES","UMA POLYMERS LIMITED","UMA PUF PANEL","UMANG BOARDS LTD","UMANG FABRICS","UMANG SYNTHETICS","UMAX PACKAGING LTD","UMIYA FLEXIFOAM PVT LTD","UNI KLINGER LIMITED","UNI TECH AUTOMATION PVT LTD","UNI WORLD LOGISTICS PRIVATE LIMITED","UNIBAIT FEEDS PRIVATE LIMITED","UNIBIC FOODS INDIA PVT LTD","UNICARE EMERGENCY EQUIPMENT","UNICARE TECHNOLOGIES PRIVATE LIMITED","UNICHARM INDIA PRIVATE LIMITED","UNICORN VALVES","UNICORP UTILITIES STRAINERS PV","UNIEXCEL AGENCIES AND SERVICES","UNIFLOW CONTROL INSTRUMENTS PV","UNIFLOW CONTROLS PRIVATE LIMITED","UNIK BAZAR LIMITED","UNILAB CHEMICALS ","UNILEX COLOURS AND CHEMICALS LTD","UNILINK MARKETING LLP","UNION SUPPLY AGENTS","UNIPOLYMERS INDIA PVT LTD","UNIPRODUCTS (INDIA) LTD","UNIQUE ORGANICS LTD","UNIQUE POWER TECHNOLOGIES","UNIQUE RUBBER & CHEMICAL","UNIQUE STAR ALLIANCE TOOLS MANUFACTURING PRIVATE L","UNIQUE STRUCTURES & TOWERS LTD","UNIQUE SURFACE COATING & PAINTS","UNISERT MACHINES (INDIA) PVT.LTD.","UNISHA ENTERPRISE PVT LTD","UNISYNTH CHEMICALS","UNITECH ENGINEERING PVT.LTD.","UNITED BRASS INDUSTRIES","UNITED BREWERIES LIMITED","UNITED DESCALER PRIVATE LIMITE","UNITED ENGINEERING COMPANY","UNITED FOODS","UNITED MULTICHEM LLP","UNITED PHOSPHOROUS LTD","UNITED TEXTILES","UNITOP CHEMICALS PVT.LTD.","UNITY CONTROLS PVT LTD","UNITY DYE CHEM PRIVATE LIMITED","UNITY TRADERS","UNIVENTIS MEDICARE LIMITED","UNIVENTURE INDUSTRIES PRIVATE LIMITED","UNIVERSAL AGRO CHEMICAL INDUSTRIES","UNIVERSAL CABLES LTD","UNIVERSAL CARTONS SOLUTIONS PVT LTD","UNIVERSAL CORPORATION LIMITED","UNIVERSAL CORROSION PREVENTION INDIA","UNIVERSAL MASTERBATCH LLP","UNIVERSAL PETROLEUM","UNIVERSAL PRECISION SCREW","UNIVERSAL SPECIALITY CHEMICALS","UNIVERSAL STAMPINGS","UNIVERSAL STARCH CHEM ALLIED LIMITED","UNIVERSAL TECHNO CAST","UNIVERSAL WORLD WIDE EXPRESS","UNIVERSALL MARKETING CORPORATION","UNIWAY ENGINEERS PVT LTD","UNIWORLD LOGISTICS PRIVATE LIMITED","UNOVEL INDUSTRIES PRIVATE LIMITED","UP TO UP REGIONAL PROJECT","UPL .LTD","UPPER INDIA INORGANIC INDUSTRIES LTD","UQAB HOISTING & MARINE INDUSTR","URBAN HOUSE INTERIORS","URVI BRASS PRODUCTS","US PRINTOGRAPHICS","USG BORAL BUILDING PRODUCTS ","USHA FIRE SAFETY EQUIPMENTS PRIVATE LIMITED","USHA INTERNATIONAL LTD","USHA LUBES PVT LTD","USHA WELDS LTD.","USHA YARNS LIMITED","USHANTI COLOUR CHEM LIMITED","UTILITY CONTRACTORS","UTPAN CHEMPRO ","UTSAH ENGINEERING PVT LTD","UTTARANCHAL INDIA","UV CHEM (INDIA) PRIVATE LIMITED","V G ENGINEERS PVT LTD","V GUARD INDUSTRIES LIMITED","V J STEEL & ALLOYS","V K C FOOTSTEPS INDIA PVT LTD","V K ENTERPRISE","V K INDUSTRIES","V M CLASSIC PETS PVT LTD","V MASS TRADING COM","V MILAK ENTERPRISES ","V P ENTERPRISES","V TRANS (INDIA) LIMITED (POD MOVEMENT)","V V TITANIUM PIGMENTS","V.C.CHEMICALS PVT LTD","V.I.P INDUSTRIES LTD","V.K.INDUSTRIAL CORPORATION","V.K.S.COMBINES","V.N.MEHTA","V2 POLYMERS","VAATA SMART LTD","VAC BUILDCARE PRIVATE LIMITED","VACHANN POWER SOLUTIONS ","VAG VALVES INDIA PRIVATE LIMITED","VAGADIYA ENGINEERING WORKS","VAGMI CHEMICALS PRIVATE LIMITED","VAIBHAV ENTERPRISES","VAIBHAV PLASTO PRINTING AND PA","VAIBHAVLAXMI EXPORTS PRIVATE L","VAIBHAVLAXMI INDUSTRIES","VAIDYARATNAM OUSHADHASALA PVT LTD","VAISHALI AUTO INDUSTRIES","VAJRA POWER LUBRICANTS PRIVATE LIMITED","VAJRA RUBBER PRODUCTS PRIVATE LIMITED","VAL MET ENGINEERING PRIVATE LIMITED","VALENS TRADESERV PRIVATE LIMITED","VALLABHADAS KANJI LTD","VALSPAR INDIA COATING CORPORAT","VALTECH INDUSTRIES","VALUE BEVERAGE","VALUE INGREDIENTS PRIVATE LIMITED","VALUE LUBRICANTS INDIA PRIVATE LIMITED","VALUE REFRIGERANTS PVT LTD","VALVE TECH INDUSTRIES.","VALVOLINE CUMMINS PRIVATE LIMITED","VAMA OIL PRIVATE LTD","VAMDOTE ENTERPRISES","VANDAN AGARBATTI","VAP CHEM","VAPCON MANUFACTURING ENGINEERS","VAPI CARE PHARMA PVT LTD","VAPI PRODUCTS INDUSTRIES PVT L","VAPO MINERALS PVT LTD","VARAD KNITS","VARADA POLYMERS","VARADHASTA PLASTICS & PKG P LT","VARAHI LIMITED","VARALAKSHMI STARCH INDUSTRIES PVT.LTD","VARDHAMAN ORGANICS PVT LTD","VARDHAYINI PLASTICS","VARDHMAN ACRYLICS LTD.","VARDHMAN CHEMICALS","VARDHMAN CLOTHING COMPANY","VARDHMAN HEALTHCARE","VARDHMAN POLYTEX LTD","VARDHMAN STAMPINGS PVT LTD","VARIANT TEXTILES LLP","VARIDHI HYGIENE PRODUCTS PRIVATE LIMITED","VARIETY MARKETING","VARIETY PLYWOODS","VARNADA INDUSTRIES PRIVATE LIMITED","VARNITEK COATINGS","VARSHA CABLES PVT LTD","VARSHA LABS","VARSHA MULTITECH","VARSHA PRINTING INK MFG CO","VARUN AGRO PROCESSING FOODS PRIVATE LIMITED","VARUN INTERNATIONAL","VARUN SELECTIONS AND VEER ENTERPRISE","VASANT CHEMICALS PVT LTD","VASANT COLOUR & CHEMICALS","VASHI ELECTRICALS PVT LTD","VASISTA LIFE SCIENCES PRIVATE LIMITED","VASU CHEMICALS","VASU HEALTHCARE PVT LTD","VASUDEVAY ENTERPRISE","VASUDHA PHARMA CHEM LTD (R & D)","VASUDHA PHARMA CHEM LTD. TRADING","VAV LIPIDS PRIVATE LIMITED","VBC FERRO ALLOYS LIMITED","VCARE HERBAL CONCEPTS PVT. LTD.","VDH CHEM TECH PRIVATE LIMITED","VE COMMERCIAL VEHICLES LTD.","VECTRA ADVANCE ENGG. P. LTD.","VECTUS INDUSTRIES LIMITED","VEDA EXCOM PRIVATE LTD","VEDANT DYESTUFF & INTERMEDIATE","VEDANT EQUIP SALES AND SERVICES PRIVATE LIMITED","VEDANT POLYTECHNIK","VEDEKA","VEDIC PETROCHEMICAL PRIVATE LIMITED","VEE EXCEL DRUGS AND PHARMACEUTICALS PVT LTD","VEE KAY ENGINEERS","VEE TEE AUTO MANUFACTURING COMPANY PRIVATE LIMITED","VEEBA FOOD SERVICES PVT LTD","VEEKAY VIKRAM AND CO. LLP","VEELINE MEDIA LIMITED","VEERA FRAGRANCES PRIVATE LIMITED","VEERAL ADDITIVES PRIVATE LIMITED","VEESHNA FLEXITUFF LLP","VEEYOR POLYMERS PVT. LTD.","VEGA INDUSTRIES PRIVATE LIMITED","VEKTRA ENGINEERING PVT. LTD.","VELAN VALVES INDIA PVT LTD","VELANKANI ELECTRONICS PRIVATE LIMITED","VELKAN ENGINEERING PVT LTD","VELKAST FOUNDRIES","VELLOILS LUBRICANT & PETROCHEM","VELOCITY ROLLERS PRIVATE LIMITED","VEL-VIN PAPER PRODUCTS","VENKATARAMANA FOOD SPECIALITIE","VENKATESHWARA B V BIOCORP PRIVATE LIMITED","VENKATESWARA SPRINGS & STEEL (","VENKY’S (INDIA) LTD","VENTRI BIOLOGICALS VACCINE DIV","VENUS AGRO SHED NET","VENUS ENTERPRISE","VENUS HOME APPLIANCES PVT LTD","VENUS SAFETY & HEALTH P.LTD. ","VENUS TAPES","VENUS WIRE INDUSTRIES PRIVATE ","VERDANT LIFE SCIENCES PVT LTD","VERDEEN CHEMICALS PVT LTD","VERITAZ HEALTHCARE LIMITED","VERMA LOGISTICS PRIVATE LIMITE","VERO MODA RETAIL PVT LTD","VESPER PHARMACEUTICALS - PNY ","VESUVIUS INDIA LTD","VET BIOTECH PRIVATE LIMITED","VETADD NUTRIENTS PRIVATE LIMITED","VETENZA ANIMAL HEALTHCARE PRIVATE LIMITED","VETERAN PHARMA","VETO SWITCHGEARS AND CABLES LIMITED","VETOQUINOL INDIA ANIMAL HEALTH PRIVATE LIMITED","V-GUARD INDUSTRIES LIMITED","VIAL SEAL INDUSTRIES","VIBCHEM INDIA","VIBHAVA CHEMICALS","VIBHAVA INDUSTRIES","VIBHAVA MARKETING CORPORATION ","VIBHUTI ENTERPRISES","VIBRANT COLORTECH PRIVATE LIMI","VICHARE EXPRESS & LOGISTICS","VICHI AGRO PRODUCTS PVT LTD","VICO FORGE PVT LTD","VICTOR AGENCIES","VICTOR IMPORTS","VICTORA AUTO PVT LTD","VICTORY DYE CHEM","VIDARBHA SALES","VIDEO SHACK","VIDHATA PLASTICS INDIA PVT LTD","VIDHI SPECIALTY FOOD INGREDIENTS LIMITED","VIDHYAMINERALS AND PROCESSORS","VIDUSHI WIRES PVT LTD","VIDYA HERBS PRIVATE LIMITED","VIDYUT INSULATION PVT. LTD.","VIGA NUTRIBRANDS PRIVATE LIMITED","VIGNESH LIFE SCIENCES PVT LTD","VIGNESH PERSONAL CARE PRIVATE LIMITED","VIHAAN FODDERTECH","VIJAY ANAND SPECIALITY PAPERS PRIVATE LIMITED","VIJAY CHEMICAL INDUSTRIES ","VIJAY COPOLYMERS","VIJAY ELECTRODES AND WIRES PRIVATE LIMITED","VIJAY ENGINEERING & MACHINERY ","VIJAY ENTERPRISES PVT LTD.","VIJAY LATEX PRODUCTS PVT. LTD.","VIJAY SABRE SAFETY PRIVATE LIMITED","VIJAY SPRINGS INDIA PRIVATE LIMITED","VIJAY TRANSMISSION PVT LTD","VIJAY TRANSTECH PRIVATE LIMITE","VIJAY VALLABH HOSIERY FACTORY","VIJAYA SAI DIP MOULDINGS","VIJAYA SHREE IMPEX","VIJAYALAKSHMI FORGINGS","VIJAYNEHA POLYMERS PRIVATE LIMITED","VIJAYSHRI PACKAGING LTD","VIKAMSHI FABRICS PVT. LTD.","VIKARSH NANO TECHNOLOGY AND ALLOYS PRIVATE LIMITED","VIKARSH STAMPINGS INDIA PRIVATE LIMITED","VIKAS LABORATORIES PVT. LTD. ","VIKASH ALUMINIUM EXTRUSION PVT","VIKEN TAPE PRIVATE LIMITED","VIKRAM THERMO (INDIA) LTD","VIKRANT EXTRUSIONS","VILCO LABORATORIES PVT.LTD.","VIMAL AGRO PRODUCTS PVT LTD.","VIMAL INTERTRADE PVT LTD","VIMAL LIFESCIENCES PRIVATE LIM","VIMAL PAINTS","VIMAX CROP SCIENCE LIMITED","VINAMAX ORAGANICS PVT LTD","VINATI ORGANICS LIMITED","VINAY CONSTRUCTION","VINAYAK ELECTROMECH P LTD","VINAYAK PHARMACEUTICALS","VINAYAK STEEL IMPEX","VINAYAK WIRE PRODUCTS PVT LTD","VINCOPLAS PRIVATE LIMITED","VINDARVIND HYGINE PRODUCTS PRIVATE LIMITED","VINI COSMETICS PVT LTD","VINIPUL INORGANICS PVT LTD","VINNI CHEMICALS PVT LTD","VINOD COOKWARE","VINTEX RUBBER INDUSTRIES","VINYOFLEX LIMITED","VIP CLOTHING LIMITED","VIP INDUSTRIES LIMITED","VIPPY INDUSTRIES LIMITED","VIPRA","VIPUL FASTENERS PVT LTD","VIPUL ORGANICS LTD","VIR POLYTECH PVT LTD","VIRA ASSOCIATES","VIRAJ ENGINEERING CO","VIRAJ ENTERPRISE","VIRAJ INDSUTRIES","VIRAJ TECHNOCAST PRIVATE LIMIT","VIRAL ENTERPRISE","VIRAL INDUSTRIES PVT LTD","VIRAMI ALLOYS PRIVATE LIMITED ","VIRAT SPECIAL STEELS PRIVATE LIMITED","VIRUPAKSHA ORGANICS LIMITED","VISA LOGISTICS PVT LTD","VISAKHA FOODS PVT LTD","VISCON RUBBER PRIVATE LIMITED","VISHAKHA MOULDINGS PVT LTD","VISHAKHA POLYFAB PVT LTD","VISHAKHA SYNTHETICS","VISHAL BEVERAGES PVT LTD","VISHAL CONTAINERS LTD","VISHAL LABORATORIES","VISHAL OLEOCHEM-PLG ","VISHAL PERSONAL CARE PVT LTD","VISHAL POLY FIBERS PVT. LTD.","VISHAL PRECISION PRODUCTS PVT LTD","VISHESH EXIM INDIA PRIVATE LIMITED","VISHNU FORGE INDUSTRIES LTD","VISHNU PRESSINGS PRIVATE LIMITED","VISHVA VISHAL ENGINEERING LTD.","VISHWA KARMA MACHINE TOOLS","VISHWAJYOT PACKAGING","VISION ENGINEERING","VISION INTERNATIONAL","VISION PACKAGING","VISION PRODUCTS PRIVATE LIMITED","VISSCO HEALTHCARE PRIVATE LIMITED","VISSCO REHABILITATION AIDS PRIVATE LIMITED","VISTARA INDUSTRIES (I) PRIVATE LIMITED","VISWAAT CHEMICALS LTD","VITAL ELECTRONICS & MANUFACTUR","VITAL FLAVOURS & FRAGRANCES","VITAL LABORATORIES PVT LTD","VIVAAN HERBALS AND HEALTH CARE","VIVEK INFOCOM","VIVEK POLYMER (INDIA)","VIVIMED LABS LIMITED","VIVSUN ENGINEERING INDUSTRIES ","VIWA DRYMIX PRIVATE LIMITED","VKC FOOTPRINTS GLOBAL PRIVATE LIMITED","VKC FOOTSTEPS (INDIA) PVT. LTD","VKL FLAVOURS PRIVATE LIMITED","VKL SEASONING PRIVATE LIMITED","VN ROOFING & CLADDING PVT. LTD","VNKC AGROCOM PVT LTD","VNS INDUSTRIES PVT.LTD","VOBO","VOLTA FASHIONS PRIVATE LIMITED","VOLTAS LIMITED","VOLUBIT LUBRICANT INDUSTRIES","VORA PACKAGING PVT LTD - BOXES","VOV INTERNATIONAL","VOX BUILDING PRODUCTS PRIVATE LIMITED","VOXTUR BIO LIMITED","VP ENGINEERS","VR FOUNDRIES","VRAJ ENGINEERING","VRAJ PLASTIC","VRAJDEEP PRODUCTS PRIVATE LIMITED","VRINDA ENGINEERS PRIVATE LIMITED","VRPL HEALTHCARE PRIVATE LTD","VRS FOODS LIMITED","VSH NDT SOLUTIONS","VSL INKS & ALLIED PRODUCTS","VSR LAMINATES PVT LTD","VSV FASHION LLP","VTC 3PL SERVICES PRIVATE LIMITED","V-TORK CONTROLS","VUBS CORPORATION","VULCANIS LLP","VVF (INDIA) LIMITED","VYANKATESH METALS AND ALLOYS PRIVATE LIMITED","VYANKATESH TECHNOFAB PRIVATE LIMITED","VYANKATESH UDYOG (I) PVT LTD","W H TARGETT INDIA LIMITED","WAAREE ENERGIES LIMITED","WACKER METROARK CHEMICALS PVT ","WAGGY WAGS INC","WAKEFIT INNOVATIONS PRIVATE LIMITED","WALCHANDNAGAR INDUSTRIES LIMITED","WALLACE PHARMACEUTICALS PVT LT","WALLNUT BUILDING SOLUTIONS INDIA PRIVATE LIMITED","WALMARK MEDITECH PRIVATE LIMITED","WALSON INDUSTRIAL SUPPLIES","WAM INDIA PRIVATE LIMITED","WANBURY LIMITED","WASTE VENTURES INDIA PRIVATE LIMITED","WATER MASS SYSTEMS PRIVATE LIMITED","WATERHEALTH INDIA PRIVATE LIMITED","WATERTEC (INDIA) PRIVATE LIMITED","WAVE DISTILLERIES AND BREWERIES LIMITED","WAXOILS PVT LTD","WEELLOW","WEENER EMPIRE PLASTICS PVT.LTD","WEIR BDK VALVES","WELBURN CANDLES PRIVATE LIMITED","WELCO GARMENT MACHINERY PVT. LTD.","WELCO HEALTHCARE","WELCOME CROP SCIENCE PVT LTD","WELDYNAMICS","WELL CAST FOUNDERY -RAK","WELLCHEM HEALTHCARE LLP","WELLCON ANIMAL HEALTH PRIVATE LIMITED","WELLGEN MEDICARE LLP","WELLKNOWN POLYESTERS LIMITED","WELLMACK PLASTIC PVT LTD","WELLTECH ELEVATOR DOOR","WELMECH ENGINEERING COMPANY PVT LTD","WELSET PLAST EXTRUCTION PVT LT","WELSET POLYPACK PRIVATE LIMITED","WELSPUN CORP LTD.","WELSPUN INDIA LTD","WERNER ELECTRIC PRIVATE LIMITED","WERNER FINELY PRIVATE LIMITED","WESMAN THERMAL ENGINEERING PROCESSES PRIVATE LTD","WEST BENGAL CHEMICAL INDUSTRIE","WEST BENGAL WASTE MANAGEMENT L","WEST COAST OPTICABLE LIMITED","WEST COAST PIGMENT CORPORATION","WEST COATS OPTILINKS","WESTERN CABLEX ENGINEERING PVT","WESTERN DRUGS LIMITED","WESTERN FLOCK PVT LTD","WESTERN INDIA OIL COMPANY","WESTERN INDIA PAINT & COLOUR CO PVT LTD.","WESTERN INDIA STEEL COMPANY PRIVATE LIMITED","WESTERN REFRIGERATION PVT. LTD","WESTROCK INDIA PRIVATE LIMITED","WHEEL FLEXIBLE PACKAGING PRIVATE LIMITED","WHITE IMPACT","WHITFORD INDIA PVT LTD","WIL-HAVEN CONTAINER LINE PVT L","WILLETT COMMUNICATIONS","WILLOWOOD CROP SCIENCES PRIVATE LIMITED","WILO MATHER AND PLATT PUMPS PR","WIM PLAST LTD","WIN MEDICARE PVT. LIMITED","WIND WORLD (INDIA) LIMITED","WINDSON AUTOLINK","WINDSON CHEMICAL PRIVATE LIMITED","WINDSOR MACHINES LTD","WINDSTON SPRINGS PVT. LIMITED","WINFINITH MARKETING PRIVATE LIMITED","WINGS BIOTECH LLP","WINGS PHARMACUTICAL PVT. LTD.","WINSOME BREWERIES LTD","WINSOME TEXTILE INDUSTRIES LIM","WINTEX APPAREL LTD","WIPRO ENTERPRISES (P) LTD.","WIRELUX CABLES PRIVATE LIMITED","WIRES AND FABRIKS (S.A) LTD.","WISCON PHARMACEUTICALS PVT.LTD","WITMANS INDUSTRIES","WITT INDIA PVT LTD","WITTMANN BATTENFELD INDIA PVT LTD","WIZ CARE","WOKNSTOVE FOODWORKS PVT LTD","WOLLAQUE VENTILATION & CONDITIONING (P)LTD","WOOD CRAFT CLOCKS PRIVATE LIMITED","WOODEN HANDICRAFT","WORK STORE LIMITED","WORLD ALLIANCE","WORLD PACK AUTOMATION SYSTEMS PRIVATE LIMITED","WOVEN GOLD ACRYLIC INDIA PVT L","WPIL LIMITED","WRELTT INDIA PRIVATE LIMITED","WRIGLEY INDIA PRIVATE LIMITED","WRITEFINE PRODUCTS PRIVATE LIM","WRONG","WRONG CREATED","WUDHOUSE DESIGNS PVT LTD","XCHEM POLYMERS INDIA PRIVATE LIMITED","XENON PHARMA PRIVATE LIMITED","XL ASSOCIATES ","XMOLD POLYMERS PRIVATE LIMITED","XOMAX SANMAR LIMITED","XOTIK FRUJUS PRIVATE LIMITED","XPRO INDIA LTD","XTATON RESOURCES","XTRA PRECISION SCREWS PRIVATE LIMITED","XTREME MEDIA PRIVATE LIMITED","XZZZX","Y -COOK INDIA PVT LTD","YAHSKA POLYMERS PVT.LTD","YALE SYNTHLUBE INDUSTRIES PRIVATE LIMITED","YAMIR PACKAGING PVT LTD","YAMUNA LUBRICANTS PVT LTD","YANTRA HARVEST ENERGY PVT LTD","YARN BAG-RETAIL","YASH ENTERPRISES","YASH FORGINGS PVT LTD","YASH HANDLOOM","YASH METALICS PVT LTD","YASH SEALS PRIVATE LIMITED","YASH TRADING CORPORATION","YASHO INDUSTRIES LIMITED","YELLOW TAPES","YELURI FORMULATIONS PVT LTD","YESHESH INDUSTRIES","YESKOLUBE INDIA PRIVATE LIMITED","YEYO INTERNATIONAL","YOGDAIINEE FOOD PROCESSING PRIVATE LTD","YOGESH ENTERPRISES PVT LTD","YOGESHWAR AGENCY","YOGESHWAR LAMINATION","YOGESHWAR POLYMERS","YOGI CAB INSULATION PVT. LTD.","YOJANA INTERMEDIATES PRVIATE LIMITED","YOKOGAWA INDIA LIMITED","YOKOSTONE CONTINENTAL LTD","YOUNG BUHMWOO INDIA COMPANY PRIVATE LIMITED","YOUNGMAN INDIA PRIVATE LIMITED","YOURS MEDICARE PRIVATE LIMITED","YOYO CHEMICALS","YUG DECOR LIMITED","YUG IMPEX","YUG INTERNATIONAL PRIVATE LIMI","YUSEN LOGISTICS (INDIA) PRIVATE LIMITED","YUVRAAJ HYGIENE PRODUCTS LIMITED","Z","ZACFA CHEMICALS","ZAMIRA LIFE SCIENCES INDIA PVT","ZAVENIR DAUBERT INDIA PVT. LTD","ZAVENIR KLUTHE INDIA PRIVATE L","ZAZEN PHARMA PVT. LTD","ZEAL ASSOCIATES","ZEAL ENGINEERS","ZEAL MEDICAL PVT LTD","ZEBION INFOTECH PVT LTD","ZED VALVES CO. PVT. LTD.","ZEE LABORATORIES","ZEN CHEMICALS PVT LTD","ZEN EXIM PVT LTD","ZEN LINEN INTERNATIONAL PVT LTD","ZENATIX SOLUTIONS PRIVATE LIMI","ZENEX ANIMAL HEALTH INDIA PRIVATE LIMITED","ZENITH IMAGE TECH PRIVATE LIMI","ZENITH METALIK ALLOYS LIMITED","ZENITH WIRE INDUSTRIES","ZENIUM CABLES LTD","ZENO TRADING & PRAMOTIONS PVT. LTD","ZENOVATE AUTO ENGINEERING LLP","ZEOLITES & ALLIED PRODUCTS PVT","ZEON INTERNATIONAL","ZEPHYR CHEMICAL INDUSTRIES","ZEPHYR PARFUMO","ZERICO LIFESCIENCES PRIVATE LIMITED","ZERO LEVEL INSTALLATIONS PRIVATE LIMITED","ZIGMA FASHION PRIVATE LIMITED","ZIRCAR REFRACTORIES LIMITED","ZIRCON TECHNOLOGIES INDIA LTD","ZIRCONIUM CHEMICALS PVT. LTD.","ZOTA HEALTH CARE LIMITED","ZXPRC (INDIA) PRIVATE LIMITED","ZYDEX INDUSTRIES PVT. LTD","ZYDUS ANIMAL HEALTH AND INVESTMENTS LIMITED","ZYDUS TAKEDA HEALTHCARE P LTD ","ZYREX ENTERPRISES");
        $i = 0;
        $k = array();
        foreach ($arr as $v){
            $this->db->where('company_name',$v);
            $row_arr = $this->db->get('tbl_company')->row_array();            
            if(!empty($row_arr)){
                $i++;
            }else{
                $k[] = array(
                        'company_name'  => $v,
                        'process_id'    => 141, 
                        'comp_id'       => 65                   
                    );                
            }
        }
        echo "<pre>";
        print_r($k);
        echo "</pre>";
        echo count($k);
        echo $i;

        $this->db->insert_batch('tbl_company',$k);

    }
}
