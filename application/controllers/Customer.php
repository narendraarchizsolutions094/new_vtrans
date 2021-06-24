<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Customer extends CI_Controller
{
    public function __construct()
    { 
        parent::__construct();
        $this->load->model(array(
            'doctor_model',
            'department_model',
            'Modules_model',
            'User_model',
            'dash_model'
        ));

        if ($this->session->user_right != 1) {
            redirect('', 'refresh');
        }
    }
    public function index()
    {
        $data['page_title'] = display('doctor_list');
        $data['doctors'] = $this->doctor_model->read();
        $data['content'] = $this->load->view('doctor', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function logged_in_as_user()
    {
        $user_id = $this->uri->segment('3');
        $userd = $this->dashboard_model->check_userByID($user_id);
        if ($userd->num_rows() === 1) {
            $userdata = $userd->row();
            $check_user = $this->dashboard_model->check_userByMAIL($userdata->email);
            if ($check_user->num_rows() === 1) {
                //logout old user
                $this->session->unset_userdata([
                    'menu'                  => '',
                    'isLogIn'               => false,
                    'user_id'               => '',
                    'companey_id'           => '',
                    'email'                 => '',
                    'designation'           => '',
                    'phone'                 => '',
                    'fullname'              => '',
                    'expiry_date'           => '',
                ]);
                // $this->rememberme->deleteCookie();
                // ends

                $city_row = $this->db->select("*")
                    ->from("city")
                    ->where('id', $check_user->row()->city_id)
                    ->get();
                $user_data = $check_user->row();
                $this->session->set_userdata('user_id', $user_data->pk_i_admin_id);
                if (user_access(230) || user_access(231) || user_access(232) || user_access(233) || user_access(234) || user_access(235) || user_access(236)) {
                    $arr = explode(',', $user_data->process);
                    $this->session->set_userdata('companey_id', $user_data->companey_id);
                    $process_filter =   get_cookie('selected_process');
                    if (!empty($process_filter)) {
                        $process_filter = explode(',', $process_filter);
                        $process_filter = array_intersect($process_filter, $arr);
                        if (empty($process_filter)) {
                            $this->session->set_userdata('process', $arr);
                        } else {
                            $this->session->set_userdata('process', $process_filter);
                        }
                    } else {
                        $process_filter = array();
                        $this->session->set_userdata('process', $arr);
                    }
                    $c = implode(',', $this->session->process);
                    set_cookie('selected_process', $c, '31536000');
                }
                $location_arr = array();
                if (!empty($city_row->row_array())) {
                    $location_arr = $city_row->row_array();
                }
                if (0) {
                    $user_process = explode(',', $user_data->process);
                    $this->db->select('sb_id,product_name');
                    $this->db->where('comp_id', $this->session->companey_id);
                    $this->db->where_in('sb_id', $user_process);
                    $process_arr     =   $this->db->get('tbl_product')->result_array();
                    $process = [];
                    $process_html = '';
                    if (!empty($process_arr)) {
                        if (user_access(270)) {
                            $process_html .= "<select class='form-control text-center' name='user_process[]' multiple id='process_elem'>";
                        } else {
                            $process_html .= "<select class='form-control text-center' name='user_process[]' id='process_elem'>";
                        }
                        foreach ($process_arr as $value) {
                            $process_html .= "<option value='" . $value['sb_id'] . "'>" . $value['product_name'] . "</option>";
                            $process[$value['sb_id']] = $value['product_name'];
                        }
                        $process_html .= "</select>";
                        $res = array('status' => true, 'message' => 'Successfully Logged In', 'process' => $process_html);
                    } else {
                        $res = array('status' => false, 'message' => 'You are not in any process. Please contact your admin!', 'process' => $process_html);
                    }
                } else {
                    if ($user_data->user_permissions == 151) {
                        $menu = 1;
                    } else {
                        $menu = 2;
                    }
                    $login_token= random_string('alnum', 30);

                    $data = $this->session->set_userdata([
                        'menu'                  => $menu,
                        'isLogIn'               => true,
                        'user_id'               => $user_data->pk_i_admin_id,
                        'companey_id'           => $user_data->companey_id,
                        //'process'               => $check_user->row()->process,
                        'email'                 => $user_data->s_user_email,
                        'designation'           => $user_data->designation,
                        'phone'                 => $user_data->s_phoneno,
                        'fullname'              => $user_data->s_display_name . ' ' . $user_data->last_name,
                        'country_id'            => !empty($location_arr) ? $location_arr['country_id'] : '',
                        'region_id'             => !empty($location_arr) ? $location_arr['region_id'] : '',
                        'territory_id'          => !empty($location_arr) ? $location_arr['territory_id'] : '',
                        'state_id'              => !empty($location_arr) ? $location_arr['state_id'] : '',
                        'city_id'               => $user_data->city_id,
                        'user_right'            => $user_data->user_permissions,
                        'picture'               => $user_data->picture,
                        'modules'               => $user_data->modules,
                        'title'                 => (!empty($setting->title) ? $setting->title : null),
                        'address'               => (!empty($setting->description) ? $setting->description : null),
                        'logo'                  => (!empty($setting->logo) ? $setting->logo : null),
                        'favicon'               => (!empty($setting->favicon) ? $setting->favicon : null),
                        'footer_text'           => (!empty($setting->footer_text) ? $setting->footer_text : null),
                        'telephony_agent_id'    => $user_data->telephony_agent_id,
                        'telephony_token'       => $user_data->telephony_token,
                        'expiry_date'           => strtotime($user_data->valid_upto),
                        'availability'    => $user_data->availability,
                        'login_token'=>$login_token,

                    ]);
                    $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
                    
                    $user_role_row = $this->db->select('user_permissions')->where('use_id',$user_data->user_permissions)->get('tbl_user_role')->row_array();
                    $user_permissions = $user_role_row['user_permissions'];                    
                    $this->session->set_userdata('permission_ids',$user_permissions);
                            
                    $this->session->set_userdata('user_tree',$all_reporting_ids);
                    if(user_access(133))
                    {
                    $this->user_model->updateLoginToken($user_data->pk_i_admin_id,$login_token);
                    }
                    //$this->user_model->add_login_history();
                    $res = array('status' => true, 'message' => 'Successfully Logged In');
                    redirect('dashboard/home');
                }
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
            redirect('login');
        }
    }
    public function update_formfields()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $active = $_POST['active'];
            $proc = $_POST['proc'];
            // print_r($proc);
            $user_id = $this->session->userdata('com_id');
            // print_r($user_id);
            if ($active == 1) {
                $status = 1;
            }
            if ($active == 0) {
                $status = 0;
            }
            // print_r($status);exit();
            $proc1 = implode(',', $proc);
            $arr = array(
                'process_id' => $proc1,
                'status' => $status
            );
            // print_r($arr);
            $res = $this->User_model->update_inputfileds($arr, $id, $user_id);
            if ($res) {
                $this->session->set_flashdata('message', "Updated Successfuly");
                // redirect('customer/edit/'.$user_id);
                echo 1;
            } else {
                echo 0;
            }
        }
    }
    public function create()
    {

        $data['page_title'] = display('add_doctor');
        $this->form_validation->set_rules('firstname', display('first_name'), 'required|max_length[50]');
        $this->form_validation->set_rules('lastname', display('last_name'), 'required|max_length[50]');
        if ($this->input->post('user_id', true) == null) {
            $this->form_validation->set_rules('email', display('email'), 'required|max_length[50]|valid_email|is_unique[user.email]');
            $this->form_validation->set_rules('password', display('password'), 'required|max_length[32]|md5');
        }
        $this->form_validation->set_rules('a_name', display('customer_account_name'), 'max_length[50]');
        $this->form_validation->set_rules('a_account_number', display('customer_account_number'), 'max_length[50]');
        $this->form_validation->set_rules('a_ifsc', display('customer_ifsc'), 'max_length[50]');
        $this->form_validation->set_rules('a_branch', display('customer_account_branch'), 'max_length[150]');
        $this->form_validation->set_rules('a_companyname', display('customer_company_name'), 'max_length[100]');
        $this->form_validation->set_rules('a_companyaddress', display('Company_address'), 'max_length[250]');
        $this->form_validation->set_rules('modules', display('customer_services'));
        $this->form_validation->set_rules('phone', display('phone'), 'max_length[20]');
        $this->form_validation->set_rules('mobile', display('mobile'), 'required|max_length[20]');
        $this->form_validation->set_rules('blood_group', display('blood_group'), 'max_length[10]');
        $this->form_validation->set_rules('sex', display('sex'), 'required|max_length[10]');
        $this->form_validation->set_rules('date_of_birth', display('date_of_birth'), 'max_length[10]');
        $this->form_validation->set_rules('address', display('address'), 'required|max_length[255]');
        $this->form_validation->set_rules('status', display('status'), 'required');
        $this->form_validation->set_rules('a_validupto', 'Valid Upto', 'required');
        $this->form_validation->set_rules('a_accounttype', 'Account Type', 'required');
        #-------------------------------#
        //picture upload
        $picture = $this->fileupload->do_upload(
            'assets/images/doctor/',
            'picture'
        );
        // if picture is uploaded then resize the picture
        if ($picture !== false && $picture != null) {
            $this->fileupload->do_resize(
                $picture,
                293,
                350
            );
        }
        //if picture is not uploaded
        if ($picture === false) {
            $this->session->set_flashdata('exception', display('invalid_picture'));
        }
        #-------------------------------# 
        if (!empty($this->input->post('modules'))) {
            $modules = implode(",", $this->input->post('modules'));
        } else {
            $modules = '';
        }
        //when create a user
        if ($this->input->post('user_id', true) == null) {
            $data['doctor'] = (object) $postData = [
                'user_id' => $this->input->post('user_id', true),
                'firstname' => $this->input->post('firstname', true),
                'lastname' => $this->input->post('lastname', true),
                'email' => $this->input->post('email', true),
                'password' => md5($this->input->post('password', true)),
                'user_role' => 2,
                'company_rights' => '10,11,12,13,30,31,32,33,60,61,62,63,70,71,72,73,80,81,82,83,90,91,92,93,120,121,122,123,130,131,132,133,140,141,142,143',
                'designation' => $this->input->post('designation', true),
                'department_id' => $this->input->post('department_id', true),
                'address' => $this->input->post('address', true),
                'phone' => $this->input->post('phone', true),
                'mobile' => $this->input->post('mobile', true),
                'short_biography' => $this->input->post('short_biography', true),
                'pictures' => (!empty($picture) ? $picture : $this->input->post('old_picture')),
                'a_name' => $this->input->post('a_name', true),
                'a_account_number' => $this->input->post('a_account_number', true),
                'a_ifsc' => $this->input->post('a_ifsc', true),
                'a_branch' => $this->input->post('a_branch', true),
                'a_companyname' => $this->input->post('a_companyname', true),
                'a_companyaddress' => $this->input->post('a_companyaddress', true),
                'modules' => $modules,
                'date_of_birth' => date('Y-m-d', strtotime(($this->input->post('date_of_birth', true) != null) ? $this->input->post('date_of_birth', true) : date('Y-m-d'))),
                'sex' => $this->input->post('sex', true),
                'blood_group' => $this->input->post('blood_group', true),
                'degree' => $this->input->post('degree', true),
                'created_by' => $this->session->userdata('user_id'),
                'create_date' => date('Y-m-d'),
                'account_type' => ($this->input->post("a_accounttype")) ? $this->input->post("a_accounttype") : "",
                'valid_upto' => ($this->input->post("a_validupto")) ? date('Y-m-d', strtotime($this->input->post("a_validupto"))) : "",
                'status' => $this->input->post('status', true),
            ];
        } else { //update a user
            $data['doctor'] = (object) $postData = [
                'user_id' => $this->input->post('user_id', true),
                'firstname' => $this->input->post('firstname', true),
                'lastname' => $this->input->post('lastname', true),
                'designation' => $this->input->post('designation', true),
                'department_id' => $this->input->post('department_id', true),
                'address' => $this->input->post('address', true),
                'phone' => $this->input->post('phone', true),
                'mobile' => $this->input->post('mobile', true),
                'short_biography' => $this->input->post('short_biography', true),
                'pictures' => (!empty($picture) ? $picture : $this->input->post('old_picture')),
                'a_name' => $this->input->post('a_name', true),
                'a_account_number' => $this->input->post('a_account_number', true),
                'a_ifsc' => $this->input->post('a_ifsc', true),
                'a_branch' => $this->input->post('a_branch', true),
                'a_companyname' => $this->input->post('a_companyname', true),
                'a_companyaddress' => $this->input->post('a_companyaddress', true),
                'modules' => $modules,
                'date_of_birth' => date('Y-m-d', strtotime($this->input->post('date_of_birth', true))),
                'sex' => $this->input->post('sex', true),
                'blood_group' => $this->input->post('blood_group', true),
                'degree' => $this->input->post('degree', true),
                'created_by' => $this->session->userdata('user_id'),
                'account_type' => ($this->input->post("a_accounttype")) ? $this->input->post("a_accounttype") : "",
                'valid_upto' => ($this->input->post("a_validupto")) ? date('Y-m-d', strtotime($this->input->post("a_validupto"))) : "",
                'create_date' => date('Y-m-d'),
                'status' => $this->input->post('status', true),
            ];
        }
        if ($this->form_validation->run() === true) {
            if (empty($postData['user_id'])) {
                $companey_id    =   $this->doctor_model->create($postData);
                $companey_insert_id = $this->db->insert_id();
                $tbl_user_role = array(
                    'comp_id' => $companey_insert_id,
                    'user_role' => 'Admin',
                    'user_permissions' => '10,11,12,13,30,31,32,33,60,61,62,63,70,71,72,73,80,81,82,83,90,91,92,93,120,121,122,123,130,131,132,133,140,141,142,143',
                    'status' => '1'
                );
                $user_right    =   $this->db->insert('tbl_user_role', $tbl_user_role);
                $right_insert_id = $this->db->insert_id();

                if ($companey_id) {
                    $post_data = $postData;
                    $post_data['companey_id'] = $companey_insert_id;
                    $post_data['user_permissions'] = $right_insert_id;
                    $this->create_user($post_data);
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if ($postData['user_id'] == $this->session->userdata('user_id')) {
                    $this->session->set_userdata([
                        'pictures' => $postData['picture'],
                        'fullname' => $postData['firstname'] . ' ' . $postData['lastname']
                    ]);
                }
                redirect('customer/create');
            } else {
                $permissions = $this->input->post('permissions');
                $permissions_str = implode(',', $permissions);
                $postData['company_rights'] = $permissions_str;
                
                if ($this->doctor_model->update($postData)) {
                    $id = $this->input->post('role_id');
                    $user_role = $this->input->post('user_type');

                    if (in_array(230, $permissions) || in_array(231, $permissions) || in_array(232, $permissions) || in_array(233, $permissions) || in_array(234, $permissions) || in_array(235, $permissions) || in_array(236, $permissions)) {
                        $this->db->where('comp_id', $this->input->post('user_id', true));
                        $c_product    =    $this->db->get('tbl_product')->num_rows();
                        if ($c_product == 0) {
                            $product_name = 'Demo Process';
                            $main_fun_name = '';
                            $process_data = array(
                                'product_name' => $product_name,
                                'comp_id' => $this->input->post('user_id', true),
                                'main_fun' => $main_fun_name,
                                'status' => 1,
                                'added_by'  => 1,
                                'added_on'  => date('d-m-Y')
                            );
                            $this->load->model('dash_model');
                            $this->dash_model->add_product($process_data);
                            $process_id    =    $this->db->insert_id();
                            $this->db->where('user_permissions', $id);
                            $this->db->update('tbl_admin', array('process' => $process_id));
                        }
                    }

                    $data = array(
                        'user_role' => $user_role,
                        'user_permissions' => $permissions_str
                    );
                    $this->User_model->update_user_role($id, $data);

                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                if ($postData['user_id'] == $this->session->userdata('user_id')) {
                    $this->session->set_userdata([
                        'pictures' => $postData['picture'],
                        'fullname' => $postData['firstname'] . ' ' . $postData['lastname']
                    ]);
                }
                redirect('customer/edit/' . $postData['user_id']);
            }
        } else {
            $data['department_list'] = $this->Modules_model->modules_list();
            $data['content'] = $this->load->view('company_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function create_user($post_data)
    {
        /*echo "<pre>";
        print_r($post_data);
        echo "</pre>";
        exit();*/
        $post_data    =   array(
            'user_permissions'      => $post_data['user_permissions'],
            's_password'            => $post_data['password'],
            's_display_name'        => $post_data['firstname'],
            'last_name'             => $post_data['lastname'],
            'date_of_birth'         => $post_data['date_of_birth'],
            'joining_date'          => $post_data['create_date'],
            'designation'           => $post_data['designation'],
            'employee_band'         => $post_data['blood_group'],
            'orgisation_name'       => $post_data['a_companyname'],
            'companey_id'           => $post_data['companey_id'],
            's_user_email'          => $post_data['email'],
            's_phoneno'             => $post_data['mobile'],
            'process'               => NULL
        );
        $this->db->insert('tbl_admin', $post_data);
    }
    public function profile($user_id = null)
    {
        if (!empty($_POST)) {
            if (!empty($this->input->post('rights'))) {
                $rights = implode(",", $this->input->post('rights'));
            } else {
                $rights = '';
            }
            $data['doctor'] = (object) $postData = [
                'user_id' => $user_id,
                'form_edit_rights' => $rights
            ];
            if ($this->doctor_model->update($postData)) {
                $this->session->set_flashdata('message', display('save_successfully'));
                redirect('customer/profile/' . $user_id);
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
                redirect('customer/profile/' . $user_id);
            }
        } else {
            $data['page_title'] = display('doctor_profile');
            $data['id_userid'] = $user_id;
            $data['user'] = $this->doctor_model->read_by_id($user_id);
            $data['content'] = $this->load->view('customer_profile', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function edit($user_id = null)
    {
        $user_role = $this->session->userdata('user_role');
        $checkcompany = $this->doctor_model->geCompanybyId($user_id);
        if ($checkcompany == 1) {
            if ($user_role == 1 && $this->session->userdata('user_id') == $user_id)
                $data['page_title'] = display('edit_profile');
            elseif ($user_role == 2)
                $data['page_title'] = display('edit_profile');
            else
                $data['page_title'] = display('edit_customer');
            $data['department_list'] = $this->Modules_model->modules_list();
            $data['doctor'] = $this->doctor_model->read_by_id($user_id);
            $data['users'] = $this->doctor_model->geCompanyUsers($user_id);
            // print_r($data['users']);
            // die();
            $this->db->select('user_permissions');
            $this->db->where('s_phoneno', $data['doctor']->mobile);
            $user_row    =   $this->db->get('tbl_admin')->result_array();
            $this->db->select('id,title');
            $this->db->where('status', 1);
            $data['modules']    =   $this->db->get('all_modules')->result_array();
            if (count($user_row) > 1) {
                echo "Error: there are multiple mobile of this company";
                exit();
            }
            $this->load->model('User_model');
            /*echo $user_row[0]['user_permissions'];
             exit();*/
            
            $user_role = $this->User_model->get_user_role($user_row[0]['user_permissions']);            
            
            if(!empty($data['doctor']->company_rights)){
                $user_role->user_permissions = $data['doctor']->company_rights;
            }
            $data['user_role'] = $user_role;
            $data['user_right_content'] = $this->load->view('company_right', $data, true);
            $data['comp_id'] = $user_id;
            $data['content'] = $this->load->view('doctor_form', $data, true);
            $data['content'].= $this->load->view('forms/doctor_form_javascript', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
            redirect('customer');
        }
    }
    public function displayusers()
    {
        $companyId=$this->uri->segment('3');
       $users= $this->db->where(array('companey_id' => $companyId))->join('user','user.user_id=tbl_admin.companey_id')->get('tbl_admin')->result();
         if (!empty($users)) { 
             $sl = 1;
             foreach ($users as $user) {
                  if ($user->b_status==1) { $status= "Active"; }else{   $status= "Inactive";   }
             echo '<tr>
                    <td style="width:20px;">'.$sl.'</td>
                    <td><img src="'.base_url($user->picture).'" alt="" width="65" height="50"/></td>
                    <td>'.$user->s_display_name.'</td>
                    <td>'.$user->s_user_email.'</td>
                    <td>'.date('Y-m-d',strtotime($user->dt_create_date)).'</td>
                    <td>'.$user->start_billing_date.'</td>
                    <td>'.date('Y-m-d',strtotime($user->valid_upto)).'</td>
                    <td>'.$user->last_log.'</td>
                    <td>'.$status.'</td>
                    <td>
                     <a    class="btn btn-xs btn-primary usermodal" id="usermodal" data-toggle="modal" data-target="#empModal" data-userid="'.$user->pk_i_admin_id .'" onclick="myFunction('.$user->pk_i_admin_id .')"><i class="fa fa-edit"></i></a> 
                    </td>
                </tr>';
             $sl++;  }
        }
    }
    public function userModal()
    {
        $uid=$this->input->get('proid');
        $users= $this->db->where(array('pk_i_admin_id'=>$uid))->get('tbl_admin')->result();
        if (!empty($users)) { 
             foreach ($users as $user) {
        echo' <form  method="post"  action="'.base_url('customer/update-billing-date').'">
                  <input type="hidden" name="user_id" value="'.$user->pk_i_admin_id.'">
                  <input type="hidden" name="company_id" value="'. $user->companey_id.'">
                  <input type="hidden" name="path" value="'.base_url('customer/edit/'.$user->companey_id.'').'">
                     <div class="form-row">                
                        <div class="form-group col-md-6 col-md-offset-2">
                            <label for="stdate">Billing Start Date<i class="text-danger">*</i></label>
                              <input id="stdate" class="form-control form-date"  value="'.$user->start_billing_date.'" name="start_billing_date" required="">
                        </div>
                     
                    </div>
                    <div class="row">
                      <div class="col-md-2 col-md-offset-5">
                        <button type="submit" class="btn btn-success">Update</button>                                    
                      </div>
                    </div>
                </form>'; 
         }
        } 
    }
    public function update_billing_date()
    {
        $user_id = $this->input->post('user_id');
        $companyId = $this->input->post('company_id');
        $path = $this->input->post('path');
        $start_billing_date = $this->input->post('start_billing_date');
        // $valid_upto = $this->input->post('valid_upto');
        $this->form_validation->set_rules('user_id', display('user_id'), 'required');
        $this->form_validation->set_rules('company_id', display('company_id'), 'required');
        $this->form_validation->set_rules('start_billing_date', display('start_billing_date'), 'required');
        // $this->form_validation->set_rules('valid_upto', display('valid_upto'), 'required');
        if ($this->form_validation->run() === true) {
               $checkcompany = $this->doctor_model->checkCompany_userbyId($companyId, $user_id);
               //check company and user_id exist or not
            if ($checkcompany == 1) {
                $data = ['start_billing_date' => $start_billing_date];
                $update = $this->doctor_model->updateUser($companyId, $user_id, $data);
                if ($update) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
        } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect($path);
    }
    public function delete($user_id = null)
    {
        if ($this->doctor_model->delete($user_id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('customer');
    }
}
