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
                            'enquiry_subsource' =>  $res->enquiry_subsource,
                            'address' =>  $res->address,
                            'company' =>  $res->org_name,
                            'checked' => 0,
                            'product_id' =>  $res->product_id,
                            'institute_id' => $res->institute_id,
                            'datasource_id' => $res->datasource_id,
                            'ip_address' =>  $res->ip_address,
                            'created_by' =>  $res->created_by,
                            'city_id' =>  $res->city_id,
                            'country_id'  => $res->country_id,
                            'state_id'  => $res->state_id,
                            'territory_id' => $res->territory_id,
                            'region_id' => $res->region_id,
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
                'designation' => $this->input->post('designation'),
                'address' => $this->input->post('address'),
                'pin_code' => $this->input->post('pin_code'),
                'checked' => 0,
                'product_id' => $process_id,
                'institute_id' => $this->input->post('institute_id'),
                'datasource_id' => $this->input->post('datasource_id'),
                'center_id' => $this->input->post('center_id'),
                'ip_address' => $this->input->ip_address(),
                'created_by' => $this->session->user_id,
                'city_id' => !empty($city_id->row()->id) ? $city_id->row()->id : '',
                'state_id' => !empty($city_id->row()->state_id) ? $city_id->row()->state_id : '',
                'country_id'  => !empty($city_id->row()->country_id) ? $city_id->row()->country_id : '',
                'region_id'  => !empty($city_id->row()->region_id) ? $city_id->row()->region_id : '',
                'territory_id'  => !empty($city_id->row()->territory_id) ? $city_id->row()->territory_id : '',
                'sales_region' => $sales_region,
                'sales_area' => $sales_area,
                'client_type'=>$this->input->post('client_type'),
                'business_load'=>$this->input->post('business_load'),
                'industries'=>$this->input->post('industries'),
                'status' => $status
            ];
            
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
        if (!empty($_POST)) {
            $name = $this->input->post('enquirername');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobileno');
            $lead_source = $this->input->post('lead_source');
            $enquiry = $this->input->post('enquiry');
            $en_comments = $this->input->post('enqCode');
            $company = $this->input->post('company');
            $address = $this->input->post('address');
            $pin_code = $this->input->post('pin_code');
            $name_prefix = $this->input->post('name_prefix');
            $this->db->set('country_id', $this->input->post('country_id'));
            $this->db->set('product_id', $this->input->post('product_id'));
            $this->db->set('institute_id', $this->input->post('institute_id'));
            $this->db->set('datasource_id', $this->input->post('datasource_id'));
            $this->db->set('phone', $mobile);
            $this->db->set('enquiry_subsource', $this->input->post('sub_source'));
            $this->db->set('email', $email);
            $this->db->set('company', $company);
			$this->db->set('sales_branch', $sales_branch);
			$this->db->set('client_name', $client_name);
            $this->db->set('address', $address);
            $this->db->set('pin_code', $pin_code);
            $this->db->set('name_prefix', $name_prefix);
            $this->db->set('name', $name);
            $this->db->set('enquiry_source', $lead_source);
            $this->db->set('enquiry', $enquiry);
            $this->db->set('coment_type', 1);
            $this->db->set('lastname', $this->input->post('lastname'));
            $this->db->where('enquiry_id', $enquiry_id);
            $this->db->update('enquiry');
            $this->Leads_Model->add_comment_for_events(display('enquiry').' Updated', $en_comments);
            $this->session->set_flashdata('message', 'Save successfully');
            redirect('enquiry/view/' . $enquiry_id);
        }
        
        $data['details'] = $this->Leads_Model->get_leadListDetailsby_id($enquiry_id);
		//print_r($data['details']);exit;
        $data['data_type']  = $data['details']->status;
       $data['region_name'] = 0; 
       if(!empty($data['details']))
       {
            $dd = $this->db->where('region_id',$data['details']->region_id)->get('tbl_region')->row();
            if(!empty($dd))
            {
                $data['region_name'] = $dd->region_name;
            }
        }
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
        // $data['all_contact_list'] = $this->location_model->contact($enquiry_id);
        $data['all_contact_list'] = $this->Client_Model->getContactWhere(array('comp_id'=>$this->session->companey_id,'client_id'=>$enquiry_id))->result();

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
        $user_role    =   $this->session->user_role;
        $data['country_list'] = $this->location_model->productcountry();
        $data['institute_list'] = $this->Institute_model->institutelist_by_country($data['details']->enq_country);
        $data['course_list'] = $this->Leads_Model->get_course_list();
        $data['institute_app_status'] = $this->Institute_model->get_institute_app_status();
        $data['prod_list'] = $this->Doctor_model->product_list($compid);
        $data['amc_list'] = $this->Doctor_model->amc_list($compid, $enquiry_id);
        $data['comission_data'] = $this->enquiry_model->comission_data($data['details']->Enquery_id);

        $data['login_user_id'] = $this->user_model->get_user_by_email($data['details']->email);
        if (!empty($data['login_user_id']->pk_i_admin_id)) {
            $data['login_details'] = $this->Leads_Model->logdata_select($data['login_user_id']->pk_i_admin_id);
        }
        $data['datasource_list'] = $this->Datasource_model->datasourcelist();
        $data['taskstatus_list'] = $this->Taskstatus_model->taskstatuslist();
        $data['state_list'] = $this->location_model->estate_list();
        $data['city_list'] = $this->location_model->ecity_list();
        $data['product_contry'] = $this->location_model->productcountry();
        $data['get_message'] = $this->Message_models->get_chat($phone_id);
        $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        $data['all_estage_lists'] = $this->Leads_Model->find_estage($data['details']->product_id, 1);
        //print_r( $data['all_estage_lists']);
        $data['institute_data'] = $this->enquiry_model->institute_data($data['details']->Enquery_id);
        $data['dynamic_field']  = $this->enquiry_model->get_dyn_fld($enquiry_id);
        $data['ins_list'] = $this->location_model->get_ins_list($data['details']->Enquery_id);
        $data['aggrement_list'] = $this->location_model->get_agg_list($data['details']->Enquery_id);
        $data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id, $data['details']->product_id,0); //0 for Sales Tab 
        //print_r($data['tab_list']); exit;
        $this->load->helper('custom_form_helper');
        $data['leadid']     = $data['details']->Enquery_id;
        $data['enquiry_id'] = $enquiry_id;
        $data['compid']     =  $data['details']->comp_id;
        $data['all_description_lists']    =   $this->Leads_Model->find_description();
        if ($this->session->companey_id == '67') {
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
        if($this->session->companey_id == 65 && $this->session->user_right == 215){
			$data['created_bylist'] = $this->User_model->read(147,false);
		}else{
			$data['created_bylist'] = $this->User_model->read();
		} 
        $data['all_designation'] = $this->Leads_Model->desi_select();		
        $this->enquiry_model->make_enquiry_read($data['details']->Enquery_id);

        $data['all_contact']= $this->Client_Model->getContactList()->result();

        //echo"<pre>";print_r($data);die;
		$this->load->model('Branch_model');
		$data['branch_lists']=$this->Branch_model->all_sales_branch();
		$data['region_lists']=$this->Branch_model->all_sales_region();
        $enq['enquiry_id'] = $enquiry_id;
        $data['create_contact_form'] = $this->load->view('contacts/create_contact_form',$enq,true);
        $data['data_type'] = base64_decode($this->uri->segment(4));
        $data['create_contact_form'] = $this->load->view('contacts/create_contact_form',array(),true);
        $data['content'] = $this->load->view('enquiry_details1', $data, true);
        $this->enquiry_model->assign_notification_update($enquiry_code);
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
                $expected_date = date('Y-m-d h:i:s',strtotime($expected_date));

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
                    if (!empty($filesop[8]) && !empty($this->location_model->get_city_by_name($filesop[8]))) {
                        $res = $this->location_model->get_city_by_name($filesop[8]);
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
                    $product_row    =   !empty($filesop[10]) ? $this->enquiry_model->name_product_list_byname($filesop[10]) : '';   // process                  
                    if (!empty($product_row)) {
                        $sb_id =  $product_row->sb_id;
                    }
                    if (!empty($sb_id)) {
                        $product_name = $sb_id;
                    } else {
                        $product_name = '';
                    }
                    $enquiry_source = !empty($filesop[11]) ? $this->enquiry_model->enquiry_source_byname($filesop[11]) : '';       //     source         

                    $enquiry_source_id = '';
                    if (!empty($enquiry_source)) {
                        $enquiry_source_id =  $enquiry_source->lsid;
                    }
                    $service_row    =   !empty($filesop[14]) ? $this->enquiry_model->name_services_list_byname($filesop[14]) : '';
                    if (!empty($service_row)) {
                        $ser_id =  $service_row->id;
                    }
                    if (!empty($ser_id)) {
                        $service_name = $ser_id;
                    } else {
                        $service_name = '';
                    }
                    if (!empty($filesop[0])) {
                        $zero = $filesop[0];
                    } else {
                        $zero = '';
                    } // Company name
                    if (!empty($filesop[1])) {
                        $one = $filesop[1];
                    } else {
                        $one = '';
                    } // Name prefixed
                    if (!empty($filesop[2])) {
                        $two = $filesop[2];
                    } else {
                        $two = '';
                    } // First Name
                    if (!empty($filesop[3])) {
                        $three = $filesop[3];
                    } else {
                        $three = '';
                    } //Last Name
                    if (!empty($filesop[4])) {
                        $phone_no = $filesop[4];
                    } else {
                        $phone_no = 0;
                    } //Mobile No
                    if (!empty($filesop[5])) {
                        $five = $filesop[5];
                    } else {
                        $five = '';
                    } //other_number
                    if (!empty($filesop[6])) {
                        $six = $filesop[6];
                    } else {
                        $six = '';
                    } // Email Address
                    // 7 state
                    // 8 city
                    if (!empty($filesop[9])) {
                        $nine = $filesop[9];
                    } else {
                        $nine = '';
                    } // address
                    //10 process
                    //11 source
                    // 12 datasource
                    //if(!empty($filesop[11])){$eleven=$filesop[11];}else{$eleven='';}
                    if (!empty($filesop[13])) {
                        $therteen = $filesop[13];
                    } else {
                        $therteen = '';
                    } // remark
                    // $product_country_id = '';                    
                    /*if($fourteen){
                        $this->db->select('id');    
                        $this->db->where('TRIM(country_name)',$fourteen);
                        $product_contry_row    =   $this->db->get('tbl_product_country')->row_array();                        
                        if(!empty($product_contry_row)){
                            $product_country_id    =  $product_contry_row['id'];
                        }
                    }
                    */
                    //$phone=preg_match('/^[0-9]{10}+$/', $phone_no);                    
                    //$phone=preg_replace('/[^0-9]/i', '',$phone_no);
                    $phone = $phone_no;
                    $this->db->where('phone', $phone);
                    $this->db->where('comp_id', $this->session->companey_id);
                    if (!empty($product_name)) {
                        $this->db->where('product_id', $product_name);
                    }
                    $res_phone = $this->db->get('enquiry2')->num_rows();
                    //echo $this->db->last_query().'<br>';
                    //$encode=$this->get_enquery_code();                    
                    if ($res_phone == 0) {
                        $dat_array = array(
                            'Enquery_id' => 'as',
                            'email' => $six,
                            'comp_id' => $this->session->companey_id,
                            'phone' => $phone_no,
                            'other_no' => $five,
                            'org_name' => $zero,
                            'name_prefix' => $one,
                            'name' => $two,
                            'lastname' => $three,
                            'enquiry' => $therteen,
                            'product_id' => $product_name,
                            'enquiry_subsource' => $service_name,
                            'datasource_id' => $datasource_name,
                            'enquiry_source' => $enquiry_source_id,
                            'address' => trim($nine),
                            'user_role' => $this->session->user_role,
                            'status' => 1,
                            'country_id' => $country_id,
                            'region_id' => $region_id,
                            'territory_id' => $territory_id,
                            'state_id' => $state_id,
                            'city_id' => $city_id,
                            'created_by' => $this->session->user_id,
                            //'created_date'=>date('Y-m-d')
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
                        if (!empty($filesop[10])) {
                            $colmn_data    =   $this->enquiry_model->all_list_colmn($filesop[10]);
                            if (!empty($colmn_data)) {
                                $j = 15;
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
            //print_r($_POST); 
            $data = array('enquiry_id'=>$this->input->post('enq_id'),
                            'contact_id'=>$this->input->post('contact_id'),
                            'visit_date'=>$visit_date,
                            'visit_time'=>$visit_time,
							'm_purpose'=>$m_purpose,
                            'comp_id'=>$this->session->companey_id,
                            'user_id'=>$this->session->user_id,
                        );
            $res = $this->Enquiry_model->getEnquiry(array('Enquery_id'=>$data['enquiry_id']))->row();
            
            $mobileno = $res->phone;
            $email = $res->email;
            //$stage_time = $this->input->post('next_visit_time');
            $enq_code  = $res->Enquery_id;
            $data['enquiry_id'] = $res->enquiry_id;
            
            $notification_id = '';
            if($visit_type==2)
            {
                $notification_id = $this->input->post('visit_notification_id');
            }
        //   print_r($_POST);
        //   echo $visit_date;
            $this->Leads_Model->add_comment_for_events_popup('Visit',$visit_date, '', $mobileno, $email, '', $visit_time, $enq_code, $notification_id, 'Visit -'.$m_purpose,1,3);

            $this->Client_Model->add_visit($data);
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

             $visit_expSum=round(abs($res->visit_expSum));
             $visit_otexpSum=round(abs($res->visit_otexpSum));
             $total_expSum=round(abs($res->visit_expSum+$res->visit_otexpSum));
             $percentChange=0;
            $km_rate = $this->user_model->get_user_meta($res->user_id,array('km_rate'));
            if(!empty($km_rate['km_rate'])){$rate= $km_rate['km_rate'];}else{
              $rate=0;;
              }
            $totalpay=($res->actualDistance)*$rate;
            $idealamt=($res->idealDistance)*$rate;
         if($idealamt > 0 && $totalpay > 0){
         $dif= $this->abs_diff($idealamt,$totalpay);
              $percentChange = (($totalpay - $idealamt) / $idealamt)*100;
                }
           
            $sub = array();
            $time = $res->visit_time=='00:00:00'?null:date("g:i A", strtotime($res->visit_time));
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
            if($colsall || in_array(6,$cols))
                $sub[] = $res->rating!=''?$res->rating:'NA';


        if($colsall || in_array(7,$cols)){
            $sub[] = $res->employee;
        }
            

                
                if($colsall || in_array(11,$cols))
                $sub[] = '<span class="diff">'.round(abs($percentChange)).'</span>';

        if($colsall || in_array(8,$cols)){
            $sub[] = round(abs($res->visit_expSum));
        }
        if($colsall || in_array(18,$cols)){         
            $sub[] = round(abs($res->visit_otexpSum));
        }
        if($colsall || in_array(19,$cols)){         
            $sub[] = round(abs($res->visit_expSum+$res->visit_otexpSum));
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
            $sub[] = $res->enquiry_status_title??'NA';                
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
            $sub[] = $res->city_name??'NA';    
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

            $sub[] = !empty($value->creation_date)?date('d-M-Y H:i:s A',strtotime($value->creation_date)):'NA';

            $stts = $value->status;

            if($colsall || in_array(19,$cols))
            {
                if($value->edited=='1' && $value->approval=='')
                {
                    if($value->createdby==$this->session->user_id)
                    {
                       $sub[]  ='<a href="'.base_url('client/ask_deal_approval/'.$value->id).'" onclick="return confirm(\'Send For Approval\')">
                            <label class="label label-warning text-black">Send For Approval</label>
                            </a>';
                    }
                    else
                    {
                        $sub[] ='<label class="label label-danger">Edited</label>';
                    }
                    
                }
                else if($value->edited=='1' && $value->approval=='pending')
                {
                    if($value->createdby==$this->session->user_id)
                    {
                        $sub[] ='<label class="label label-primary">Waiting for approval</label>
                        ';
                    }
                    else
                    {
                        $opt  ='<select onchange="location.href=\''.base_url('client/deal_action/'.$value->id.'/').'\'+this.value">';
                            $opt.='<option value="">Action</option>
                                    <option value="approve">Approve</option>
                                    <option value"reject">Reject</option>
                                    <option value="resend">Send for Approval</option>
                                ';
                        $opt.='</select>';
                         $sub[] = $opt;
                    }
                }
                else
                {
                $sub[] = '<select onchange="update_info_status('.$value->id.',this.value)">
                        <option value="0" '.($value->status==0?'selected':'').'>Pending</option>
                        <option value="1" '.($value->status==1?'selected':'').'>Done</option>
                        <option value="2" '.($value->status==2?'selected':'').'>Deferred</option>
                        </select>';
                }
            
            }
            $part2 = "";
            if(user_access('1002'))
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
        $this->load->model('Client_Model');
        $company_id = $this->session->companey_id;
        $user_id = -1; //$this->session->user_id;
        $process = $this->session->process;
        $where = 'tbl_company.company_name LIKE "%'.$key.'%" ';
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
}
