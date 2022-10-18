<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'department_model',
            'User_model',
            'location_model',
            'Modules_model',
            'doctor_model',
            'enquiry_model'
        ));
        $this->column_search = array('tbl_admin.employee_id',
                                     'tbl_admin.s_display_name',
                                     'tbl_admin.last_name',
                                     'tbl_admin.s_user_email',
                                     'tbl_admin.s_phoneno',
                                     'tbl_admin.start_billing_date',
                                     'user.valid_upto',
                                     'tbl_admin.b_status'
                                     );
        if (empty($this->session->userdata('isLogIn')))
            redirect('login');
    }
	
	public function select_area_by_region() {
        $region = $this->input->post('region');
        echo json_encode($this->User_model->all_area_list($region));

       // echo $diesc;
    }
	
	public function select_employee_by_rab() {
		$dept = $this->input->post('dept');
        $branch = $this->input->post('branch');
		$region = $this->input->post('region');
		$area = $this->input->post('area');
        echo json_encode($this->User_model->all_emp_list($dept,$branch,$region,$area));

       // echo $diesc;
    }
	
	public function select_branch_by_arearegion() {
        $region = $this->input->post('region');
		$area = $this->input->post('area');
        echo json_encode($this->User_model->all_branch_list($region,$area));

       // echo $diesc;
    }
	
	public function select_pkm_by_grade() {
        $desc_id = $this->input->post('desc_id');
        echo json_encode($this->User_model->all_pkr_list($desc_id));

       // echo $diesc;
    }
	
    public function index()
    {
		$this->load->model('Ticket_Model');
        if (!empty($_GET['user_role'])) {
            $data['title'] = $this->User_model->get_role_name_by_id($_GET['user_role']) . ' List';
        } else {
            $data['title'] = display('user_list');
        }
        //$data['departments'] = $this->User_model->read2();
        //echo $this->db->last_query();
		$this->load->model('Branch_model');
		$data['filterData'] = $this->Ticket_Model->get_filterData(1);
		$data['dept_lists']=$this->User_model->all_sales_dept();
		$data['region_lists']=$this->Branch_model->all_sales_region();
        $data['user_role'] = $this->db->get('tbl_user_role')->result();
        $data['content'] = $this->load->view('user', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function departments()
    {
        // print($_GET['search']);
        // die();
       
        if (empty($_GET['user_role'])) {            
            $user_separation  = get_sys_parameter('user_separation','COMPANY_SETTING');
            $sep_arr=array();
            if (!empty($user_separation)) {
                $user_separation = json_decode($user_separation,true);
                foreach ($user_separation as $key => $value) { 
                    $sep_arr[] = $key;
                }
            }            
        }
		
		$enquiry_filters_sess   =   $this->session->enquiry_filters_sess;
        $from_created           =   !empty($enquiry_filters_sess['from_created'])?$enquiry_filters_sess['from_created']:'';       
        $to_created             =   !empty($enquiry_filters_sess['to_created'])?$enquiry_filters_sess['to_created']:'';
        $email                  =   !empty($enquiry_filters_sess['email'])?$enquiry_filters_sess['email']:'';
        $employee               =   !empty($enquiry_filters_sess['employee'])?$enquiry_filters_sess['employee']:''; 
        $phone                  =   !empty($enquiry_filters_sess['phone'])?$enquiry_filters_sess['phone']:'';
        $status                 =   !empty($enquiry_filters_sess['status'])?$enquiry_filters_sess['status']:'';
		$employee_dept          =   !empty($enquiry_filters_sess['createdbydept'])?$enquiry_filters_sess['createdbydept']:'';
		$employee_region        =   !empty($enquiry_filters_sess['sales_region'])?$enquiry_filters_sess['sales_region']:'';
		
        $this->db->select("CONCAT(tadmin.s_display_name, tadmin.last_name) as rep_to,user.valid_upto,user.user_id,tbl_admin.*,tbl_user_role.user_role as user_role_title,sales_region.name as region,sales_area.area_name as area,tbl_admin.sales_branch,tbl_department.dept_name as department,discount_matrix.name as grade");
        $this->db->from('tbl_admin');
        $this->db->join('user', 'user.user_id = tbl_admin.companey_id');
		$this->db->join('tbl_admin tadmin', 'tadmin.pk_i_admin_id = tbl_admin.report_to','left');
		$this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_type', 'left');
        $this->db->join('sales_region', 'sales_region.region_id=tbl_admin.sales_region', 'left');
		$this->db->join('sales_area', 'sales_area.area_id=tbl_admin.sales_area', 'left');
		//$this->db->join('branch', 'branch.branch_id=tbl_admin.sales_branch', 'left');
		$this->db->join('tbl_department', 'tbl_department.id=tbl_admin.dept_name', 'left');
		$this->db->join('discount_matrix', 'discount_matrix.id=tbl_admin.discount_id', 'left');
        if (!empty($sep_arr)) {
            $this->db->where_in("tbl_admin.user_type NOT", $sep_arr);
        }
        if (!empty($_GET['user_role'])) {
            $this->db->where('tbl_admin.user_type', $_GET['user_role']);
        }
		
		if(!empty($phone)){
            $this->db->where('tbl_admin.s_phoneno', $phone);                                  
        }

        if(!empty($from_created) && !empty($to_created)){
            $this->db->where('tbl_admin.dt_create_date >=', date('Y-m-d',strtotime($from_created)));
            $this->db->where('tbl_admin.dt_create_date <=', date('Y-m-d',strtotime($to_created)));                               
        }
        
        if(!empty($email)){
            $this->db->where('tbl_admin.s_user_email', $email);                                  
        }

        if(!empty($employee)){
            $this->db->where('tbl_admin.s_display_name', $employee);                                  
        }
		
		if(!empty($employee_region)){
			$this->db->where('tbl_admin.sales_region', $employee_region);                                                
        }
		
		if(!empty($employee_dept)){            
            $this->db->where('tbl_admin.dept_name', $employee_dept);                                    
        }

        if($status == 1){
            $this->db->where('tbl_admin.b_status', 1);                               
        }

        if($status == 2){
            $this->db->where('tbl_admin.b_status', 0);                               
        }
		
        $this->db->where('tbl_admin.user_type!=', 1);
        $this->db->where('tbl_admin.companey_id', $this->session->companey_id);
        $this->db->limit($_POST['length'], $_POST['start']);
        $i = 0;
        // loop searchable columns 
        foreach($this->column_search as $item){
            // if datatable send POST for search
            if($_POST['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                
                // last loop
                if(count($this->column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
        $userData = $this->db->get();
        $countUsers = $userData->num_rows();
        $data_get = $userData->result();
        $data  = array();
        //$value['product_name'] = '';
        $i = 1;
        foreach ($data_get as $department) {
            $process = '';
            $process_arr = explode(',', $department->process);
            $this->db->select('product_name');
            $this->db->where_in('sb_id', $process_arr);
            $p_res    =   $this->db->get('tbl_product')->result_array();
            if (!empty($p_res)) {
                foreach ($p_res as $key => $value) {
                    $process .= $value['product_name'].', ';
                }
            }
//For branch			
			$branches = '';
            $branches_arr = explode(',', $department->sales_branch);
            $this->db->select('branch_name');
            $this->db->where_in('branch_id', $branches_arr);
            $b_res    =   $this->db->get('branch')->result_array();
            if (!empty($b_res)) {
                foreach ($b_res as $key => $value) {
                    $branches .= $value['branch_name'].', ';
                }
            }
			
            if($department->b_status == 1){ 
                $status= display('active');
            }elseif($department->b_status == 0){
                $status='<a style="color:red">Inactive</a>';
            }
                
            $sub = array();
            $sub[] = '<input name="com_id" hidden value="' . $department->companey_id . '"> <input type="checkbox" value="' . $department->pk_i_admin_id . '" id="checkitem" name="user_ids[]"> ' . $i++ . '';
            $sub[] = '<a   href="' . base_url('user/edit/' . $department->pk_i_admin_id . '') . '">' . $department->employee_id . '</a>';
			$sub[] = $department->used_apk ?? "NA";
            $sub[] = '<a   href="' . base_url('user/edit/' . $department->pk_i_admin_id . '') . '">' . $department->s_display_name . ' ' . $department->last_name . '</a>';
            $sub[] = $department->user_role_title ?? "NA";
			$sub[] = $department->rep_to ?? "NA";
            $sub[] = '<a   href="' . base_url('user/edit/' . $department->pk_i_admin_id . '') . '">' . $department->s_user_email . '</a>';
            $sub[] = $department->s_phoneno ?? "NA";
			$sub[] = $department->designation ?? "NA";
			$sub[] = $department->region ?? "NA";
			$sub[] = $department->area ?? "NA";
			$sub[] = $branches ?? "NA";
			$sub[] = $department->grade ?? "NA";
			$sub[] = $department->department ?? "NA";
            $sub[] = $process ?? "NA";
            $sub[] = $department->start_billing_date ?? "NA";
            $sub[] = $department->valid_upto ?? "NA";
            $sub[] = $department->last_log ?? "NA";
            $sub[] = $department->dt_create_date ?? "NA";
            $sub[] = $status;
            $data[] = $sub;
        }
        if (!empty($sep_arr)) {
            $this->db->where_in("tbl_admin.user_type NOT", $sep_arr);
        }
        if (!empty($_GET['user_role'])) {
            $this->db->where('tbl_admin.user_type', $_GET['user_role']);
        }
		if(!empty($phone)){
            $this->db->where('tbl_admin.s_phoneno', $phone);                                  
        }

        if(!empty($from_created) && !empty($to_created)){
            $this->db->where('tbl_admin.dt_create_date >=', date('Y-m-d',strtotime($from_created)));
            $this->db->where('tbl_admin.dt_create_date <=', date('Y-m-d',strtotime($to_created)));                               
        }
        
        if(!empty($email)){
            $this->db->where('tbl_admin.s_user_email', $email);                                  
        }

        if(!empty($employee)){
            $this->db->where('tbl_admin.s_display_name', $employee);                                  
        }

        if($status == 1){
            $this->db->where('tbl_admin.b_status', 1);                               
        }

        if($status == 2){
            $this->db->where('tbl_admin.b_status', 0);                               
        }
        $iTotalRecords = $this->db->where('tbl_admin.user_type!=', 1)->where('tbl_admin.companey_id', $this->session->companey_id)->count_all_results('tbl_admin');
        //print_r($res);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $iTotalRecords,
            "iTotalRecords" => $iTotalRecords,
            "recordsFiltered" => $countUsers,
            "iTotalDisplayRecords" => $iTotalRecords,
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function inactiveAlluser()
    {
        // print_r($_POST);
        $user_ids = $this->input->post('user_ids');
        $com_id = $this->input->post('com_id');
        foreach ($user_ids as $key => $value) {
            $user_id = $user_ids[$key];
            $data = ['b_status' => 0];
            $this->User_model->ChangeStatus($data, $user_id, $com_id);
        }
        $this->session->set_flashdata('message', "Status updated successfully");
        redirect('user/index');
    }
    function userimport()
    {
        $data['title'] = display('import');
        $data['content'] = $this->load->view('user_import', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    function upload_user()
    {
        if (user_role('120') == true) {


        }
        if (!is_dir('assets/csv')) {
            mkdir('assets/csv', 0777, TRUE);
        }
        $filename = "user" . date('d-m-Y_H_i_s'); //"school_26-07-2018_16_17_51";
        $config = array(
            'upload_path' => "assets/csv",
            'allowed_types' => "text/plain|text/csv|csv",
            'remove_spaces' => TRUE,
            'max_size' => "10000",
            'file_name' => $filename
        );
        $this->load->library('upload', $config);
        // $this->upload->do_upload('file')

        if ($this->upload->do_upload('file')) {
            $upload = $this->upload->data();
            $json['success'] = 1;
            // $upload['file_name'] = "school_26-07-2018_16_17_51.csv";
            $filePath = $config['upload_path'] . '/' . $upload['file_name'];
            $file = $filePath;
            $handle = fopen($file, "r");
            $c = 0;
            $exist = array();
            $ic = 0;

            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) 
            {

                if ($c > 0) {
                    $emp_id     = $filesop[0];
                    $firstname  = $filesop[1];
                    $lastname   = $filesop[2];
                    $email      = $filesop[3];
                    $sec_email  = $filesop[4];
                    $phone      = $filesop[5];
                    $sec_phone  = $filesop[6];
                    $password   = !empty($filesop[7])?md5($filesop[7]):md5('12345678');
                    $dob        = !empty($filesop[8])?date('Y-m-d',strtotime($filesop[8])):'';
                    $join_date  = !empty($filesop[9])?date('Y-m-d',strtotime($filesop[9])):'';
                    $anniversary= $filesop[10];
                    $address    = $filesop[11];
                    $blood_group= $filesop[12];
                    $country_id = getCountryByName($filesop[13]);
                    $region_id  = getRegionByName($country_id,$filesop[14]);
                    $state        = getStateByName($country_id,$region_id,$filesop[15]);
                    $territory    = getTerritoryByName($country_id,$region_id,$state,$filesop[16]);
                    $product      = getProductByName($filesop[17]);
                    $city_id    = getCityByName($country_id,$region_id,$state,$territory,$filesop[18]);
                    $userright  = getUserRight($filesop[19]);
                    $report_to  = getUserByName($filesop[20]);
                    $process    = getProcessBynme($filesop[21]);
                    $designation = $filesop[22];
                    //echo $country_name;exit();
                    if (checkAlreadyExist($email, 'email') == "no" && checkAlreadyExist($phone, 'phone') == "no") {
                        if ($userright != '' && $report_to != '' && $process != '') {
                            $postData = array(
                                'employee_id'           => $emp_id,
                                's_display_name'        => $firstname,
                                'last_name'             => $lastname,
                                's_user_email'          => $email,
                                'second_email'          =>$sec_email,
                                's_phoneno'             => $phone,
                                'second_phone'          =>$sec_phone,
                                'report_to'             => $report_to,
                                'user_type'             => $userright,
                                'user_permissions'      => $userright,
                                'process'               => $process,
                                's_password'            => $password,
                                'companey_id'           => $this->session->companey_id,
                                'date_of_birth'         => $dob,
                                'joining_date'          => $join_date,
                                'anniversary'           => $anniversary,
                                'add_ress'               => $address,
                                'country'                =>$country_id,
                                'region'                 =>$region_id,
                                'state_id'                =>$state,
                                'territory_name'            =>$territory,
                                'products'                  =>$product,
                                'city_id'                   =>$city_id,
                                'designation'               =>$designation,
                            );
                        //print_r($postData); exit();
                        if(empty($postData['s_display_name'])
                            || empty($postData['last_name'])
                            || empty($postData['s_user_email'])
                            || empty($postData['s_phoneno'])
                            || empty($postData['s_password'])
                            || empty($postData['user_type'])
                            )
                        {
                            continue;
                        }

                            $this->db->insert('tbl_admin', $postData);
                            $ic++;
                        }
                    } else {
                        $exist[] = $email;
                    }
                }
                $c++;
            }
            if (!empty($exist)) {
                $this->session->set_flashdata('message', count($exist) . " user skipped and " . $ic . " user added successfully");
            } else {
                $this->session->set_flashdata('message', "" . $ic . " user added successfully");
            }
            unlink(base_url('assets/csv') . $filename);
            redirect(base_url() . 'user');
            // echo '<pre>'; print_r ($discCodeArr); print_r ($stateArr); print_r ($cityArr); print_r ($blockArr); die;
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect(base_url() . 'user');
        }
    }
    public function company()
    {
        $data['title'] = display('Company');
        $data['company'] = $this->User_model->display_company_details();
        $data['user_role'] = $this->db->get('tbl_user_role')->result();
        $data['content'] = $this->load->view('company_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function add_company()
    {
        $data['title'] = display('Company');
        $data['company'] = $this->User_model->display_company_details();
        $data['user_role'] = $this->db->get('tbl_user_role')->result();
        $data['content'] = $this->load->view('company_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function channel_partner()
    {
        $data['title'] = display('user_list');
        $data['page_title'] = 'Channel Partner';
        $data['departments'] = $this->User_model->partner();
        $data['type_list'] = $this->User_model->types_list();
        $data['user_role'] = $this->db->get('tbl_user_role')->result();
        $data['content'] = $this->load->view('partner_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function user_type()
    {
        $data['title'] = display('user_list');
        $data['page_title'] = display('user_function');
        $data['nav1'] = 'nav3';
        $this->db->where('use_id!=', 1);
        $this->db->where('comp_id', $this->session->companey_id);
        $data['lead_score'] = $this->db->get('tbl_user_role')->result();

        $data['content'] = $this->load->view('user_role', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function user_type_add()
    {
        if (!empty($_POST)) {
            $this->load->library('form_validation');
            $user_type = $this->input->post('user_type');
            $permission = $this->input->post('permissions');
            $permissions = implode(',', $permission);
            $this->form_validation->set_rules('user_type', 'user_role', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('FAILMSG', 'User role already exists in the table');
                redirect('userrights');
            } else {
                $data = array(
                    'user_role' => $user_type,
                    'comp_id' => $this->session->companey_id,
                    'created_date' => date('Y-m-d h:s:i'),
                    'created_by' => $this->session->user_id,
                    'user_permissions' => $permissions
                );
                $this->db->insert('tbl_user_role', $data);
                $this->session->set_flashdata('SUCCESSMSG', 'User Role Created Successfully');
                redirect('userrights');
            }
        } else {
            $data['page_title'] = display('user_function');
            $data['content'] = $this->load->view('user_role', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function prepare_hierarchy_nodes($data)
    {
        $nodes = array();
        //$nodes[] = array('id'=>1,'pid'=>0,'name'=>'abc','txt'=>'','isCollapsed'=>false,'img'=>'');
        $this->db->where_in('pk_i_admin_id', $data);
        $user_results    =   $this->db->get('tbl_admin')->result_array();
        if (!empty($user_results)) {
            foreach ($user_results as $key => $value) {
                if ($this->session->user_id == $value['pk_i_admin_id']) {
                    $nodes[] = array(
                        'id'            => $value['pk_i_admin_id'],
                        'pid'           => 0,
                        'name'          => "<a href=" . base_url() . "user/edit/" . $value['pk_i_admin_id'] . ">" . $value['s_display_name'] . "</a>",
                        'txt'           => $value['designation'],
                        'isCollapsed'   => false,
                        'img'           => $value['picture'] ? base_url() . $value['picture'] : ''
                    );
                } else {
                    $nodes[] = array(
                        'id'            => $value['pk_i_admin_id'],
                        'pid'           => $value['report_to'],
                        'name'          => "<a href=" . base_url() . "user/edit/" . $value['pk_i_admin_id'] . ">" . $value['s_display_name'] . "</a>",
                        'txt'           => $value['designation'],
                        'isCollapsed'   => false,
                        'img'           => $value['picture'] ? base_url() . $value['picture'] : ''
                    );
                }
            }
        }
        return $nodes;
    }
    public function user_tree()
    {
        $data['title'] = 'User Hierarchy';
        $this->load->model('common_model');
        $data = $this->common_model->get_categories($this->session->user_id);
        $nodes    =   $this->prepare_hierarchy_nodes($data);
        $data['nodes_data'] = $nodes;
        $data['content'] = $this->load->view('user_hierarchy', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function create()
    {
        if (user_role('120') == true) {}
        $data['page_title'] = display('add_user');
        $this->form_validation->set_rules('Name', display('disolay_name'), 'required');
        $this->form_validation->set_rules('designation', 'designation', 'required');
        $this->form_validation->set_rules('user_type', display('user_type'), 'required');
        //$this->form_validation->set_rules('user_role', display('user_role'), 'required');
        //$this->form_validation->set_rules('modules', display('customer_services'));
        $this->form_validation->set_rules('status', display('status'), 'required');
        //$this->form_validation->set_rules('cname', 'Contact person Name ', 'differs[Name]');

        if (empty($this->input->post('dprt_id'))) {
            $this->form_validation->set_rules('employee_id', display('employee_id'), 'required|is_unique[tbl_admin.employee_id]', array('is_unique' => 'Duplicate Entery For Employee Id '));
            $this->form_validation->set_rules('password', display('password'), 'required|min_length[8]');
           // $this->form_validation->set_rules('email', display('email'), 'required|is_unique[tbl_admin.s_user_email]', array('is_unique' => 'Duplicate Entery For email'));
           // $this->form_validation->set_rules('cell', display('cell'), 'required|is_unique[tbl_admin.s_phoneno]');
		    $this->form_validation->set_rules('email', display('email'), 'required');
            $this->form_validation->set_rules('cell', display('cell'), 'required');
        }

        #-------------------------------#
        if (empty($this->input->post('dprt_id'))) {
            $password = md5($this->input->post('password', true));
        } else {
            $password = $this->input->post('old_pass', true);
        }
        $img = $this->fileupload->do_upload(
            'assets/images/user/',
            'file'
        );
        if ($img !== false && $img != null) {
            $this->fileupload->do_resize(
                $img,
                293,
                350
            );
        }
        if ($this->input->post('user_type')) {
            $permission = $this->input->post('user_type');
        } else {
            $permission = '';
        }
        $inactive = $this->input->post('status', true);
        if ($inactive == "0") {
            $inactive_date = date("Y-m-d h:i:s");
        } else {
            $inactive_date = '';
        }
        $data['department'] = (object) $postData = [
            'pk_i_admin_id' => $this->input->post('dprt_id', true),
            'user_roles' => $this->input->post('user_role', true),
            'user_type' => $this->input->post('user_type', true),
            'employee_id' => $this->input->post('employee_id', true),
            's_user_email' => $this->input->post('email', true),
            's_phoneno' => $this->input->post('cell', true),
            'second_email' => $this->input->post('second_email', true),
            'second_phone' => $this->input->post('second_phone', true),
            's_password' => $password,
            'inactive_date' => $inactive_date,
            's_display_name' => $this->input->post('Name', true),
            'state_id' => $this->input->post('state_id', true),
            'city_id' => $this->input->post('city_name', true),
            'companey_id' => $this->session->userdata('companey_id'),
            'user_permissions' => $permission,
            'last_name' => $this->input->post('last_name', true),
            'b_status' => $this->input->post('status', true),
            'date_of_birth' => $this->input->post('dob', true),
            'joining_date' => $this->input->post('joining_date', true),
            'anniversary' => $this->input->post('anniversary', true),
            'designation' => $this->input->post('designation', true),
            'employee_band' => $this->input->post('employee_band', true),
            'country' => $this->input->post('country'),
            'region' => $this->input->post('region', true),
            'territory_name' => $this->input->post('territory', true),
            'add_ress' => $this->input->post('address', true),
            'contact_pname' => $this->input->post('cname', true),
            'contact_pemail' => $this->input->post('cemail', true),
            'contact_semail' => $this->input->post('csemail', true),
            'contact_phone' => $this->input->post('cphone', true),
            'contact_sphone' => $this->input->post('csphone', true),
			
			'sales_region' => $this->input->post('sales_region', true),
            'sales_area' => $this->input->post('sales_area', true),
			'sales_branch' => !empty($this->input->post('sales_branch', true)) ? implode(',', $this->input->post('sales_branch', true)) : '',
			'dept_name' => $this->input->post('dept_name', true),
           
            'picture' => (!empty($img) ? $img : $this->input->post('new_file')),
            'report_to' => $this->input->post('report_to', true),
            'sibling_id' => $this->input->post('sibling_user', true),
            'telephony_agent_id' => $this->input->post('telephony_agent_id', true),
            'process' => !empty($this->input->post('process', true)) ? implode(',', $this->input->post('process', true)) : '',
            'products' => !empty($this->input->post('product', true)) ? implode(',', $this->input->post('product', true)) : '',
			'discount' => $this->input->post('discount', true),
            'discount_id'=> $this->input->post('discount_id')??0,
        ];
        // print_r($data['department']);
        // die();
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            if($this->session->companey_id==65 OR $this->session->companey_id == 1){
                $this->user_model->set_user_meta($this->input->post('dprt_id'),array(
                    'reporting_location'=>$this->input->post('reporting_location'),
                    'km_rate'=>$this->input->post('km_rate')
                ));
                
               
            }
            #if empty $dprt_id then insert data
            if (empty($this->input->post('dprt_id'))) {
              $insert_id=$this->User_model->create($postData);
                if ($insert_id) {
                      //insert wharsapp api
                    $this->user_model->set_user_meta($insert_id,array(
                        'api_name'=>$this->input->post('api_name'),
                        'api_url' => $this->input->post('api_url'),

                        )

                    );
                    if($this->session->companey_id==65 OR $this->session->companey_id==1 ){
                        $this->user_model->set_user_meta($insert_id,array(
                            'km_rate'=>$this->input->post('km_rate')
                        ));
                    }
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('user');
            } else {
                if ($this->User_model->update($postData)) {
                    //print_r($this->db->last_query());exit;
                    if (!empty($this->session->password_error)) {
                        $this->session->set_flashdata('exception', 'pasword not updated');
                    } else {
                          //insert wharsapp api
                          $this->user_model->set_user_meta($this->input->post('dprt_id'),array(
                            'api_name'=>$this->input->post('api_name'),
                            'api_url' => $this->input->post('api_url')
                            )
                        );
                      
                        $this->session->set_flashdata('message', display('update_successfully'));
                    }
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('user');
            }
        } else {
            $data['state_list'] = $this->location_model->state_list();
            $data['region_list'] = $this->location_model->region_list();
            $data['county_list'] = $this->User_model->display_country();
            $this->db->where('use_id!=', 1);
            $this->db->where('comp_id', $this->session->companey_id);
            $data['user_role'] = $this->db->get('tbl_user_role')->result();
            $data['user_list'] = $this->User_model->read();
            $data['companey_list'] = $this->User_model->companyList();
            $this->load->model('dash_model');
            $data['products'] = $this->dash_model->all_process_list();
            $data['products_list'] = $this->dash_model->all_product_list();
		  
            $data['enq_id'] = 'VT/IN/EI/' . str_pad($this->User_model->get_empid(), 2, '0', STR_PAD_LEFT);

             $this->load->model('Branch_model');
			 $data['region_lists']=$this->Branch_model->all_sales_region();
			 $data['dept_lists']=$this->User_model->all_sales_dept();
             $data['discount_list1']= $this->Branch_model->discount_list1();
			 $data['discount_list']= $this->Branch_model->discount_list();
             //print_r($data['discount_list']);exit();

            $data['content'] = $this->load->view('user_from', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function add_partner()
    {
        if (user_role('130') == true) {
        }
        $data['title'] = display('add_user');
        $data['page_title'] = 'Channel Partner';
        #-------------------------------#
        //	$this->form_validation->set_rules('company_info', display('company_info') ,'required');
        $this->form_validation->set_rules('Name', display('disolay_name'), 'required');
        $this->form_validation->set_rules('cell', display('cell'), 'required|max_length[10]');
        $this->form_validation->set_rules('state_id', display('state_name'), 'required');
        $this->form_validation->set_rules('city_name', display('city_name'), 'required');
        $this->form_validation->set_rules('user_role', display('user_role'), 'required');
        $this->form_validation->set_rules('user_type', display('user_type'), 'required');
        $this->form_validation->set_rules('modules', display('customer_services'));
        $this->form_validation->set_rules('status', display('status'), 'required');
        if (empty($this->input->post('dprt_id'))) {
            $this->form_validation->set_rules('employee_id', display('employee_id'), 'required|is_unique[tbl_admin.employee_id]', array('is_unique' => 'Duplicate Entery For Employee Id '));
            $this->form_validation->set_rules('email', display('email'), 'required|is_unique[tbl_admin.s_user_email]', array('is_unique' => 'Duplicate Entery For email'));
            $this->form_validation->set_rules('password', display('password'), 'required|min_length[8]');
        }
        #-------------------------------#
        if (!empty($this->input->post('modules'))) {
            $modules = implode(",", $this->input->post('modules'));
        } else {
            $modules = '';
        }
        if (empty($this->input->post('dprt_id'))) {
            $password = md5($this->input->post('password', true));
        } else {
            // $this->session->set_flashdata('exception','password not updated');
            $password = $this->input->post('old_pass', true);
        }
        //Upload employee image..
        //picture upload
        $img = $this->fileupload->do_upload(
            'assets/images/user/',
            'file'
        );
        // if picture is uploaded then resize the picture
        if ($img !== false && $img != null) {
            $this->fileupload->do_resize(
                $img,
                293,
                350
            );
        }
        if ($this->input->post('user_type')) {
            $permission = $this->input->post('user_type');
        } else {
            $permission = '';
        }
        $data['department'] = (object) $postData = [
            'pk_i_admin_id' => $this->input->post('dprt_id', true),
            'user_roles' => $this->input->post('user_role', true),
            'user_type' => $this->input->post('user_type', true),
            'employee_id' => $this->input->post('employee_id', true),
            's_user_email' => $this->input->post('email', true),
            's_phoneno' => $this->input->post('cell', true),
            'second_email' => $this->input->post('second_email', true),
            'second_phone' => $this->input->post('second_phone', true),
            's_password' => $password,
            'modules' => $modules,
            's_display_name' => $this->input->post('Name', true),
            'state_id' => $this->input->post('state_id', true),
            'city_id' => $this->input->post('city_name', true),
            'companey_id' => 1,
            'partner_type' => $this->input->post('channel_ptnr_types', true),
            'p_type' => $this->input->post('channel_ptnr_typ', true),
            'contact_pname' => $this->input->post('cname', true),
            'contact_pemail' => $this->input->post('cemail', true),
            'contact_semail' => $this->input->post('csemail', true),
            'contact_phone' => $this->input->post('cphone', true),
            'contact_sphone' => $this->input->post('csphone', true),
            'user_permissions' => $permission,
            'last_name' => $this->input->post('last_name', true),
            'b_status' => $this->input->post('status', true),
            'date_of_birth' => $this->input->post('dob', true),
            'anniversary' => $this->input->post('anniversary', true),
            'orgisation_name' => $this->input->post('org_name', true),
            'employee_band' => $this->input->post('employee_band', true),
            'country' => $this->input->post('country'),
            'region' => $this->input->post('region', true),
            'territory_name' => $this->input->post('territory', true),
            'add_ress' => $this->input->post('address', true),
            'picture' => (!empty($img) ? $img : $this->input->post('new_file')),
            'report_to' => $this->input->post('report_to', true)
        ];
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            #if empty $dprt_id then insert data
            if (empty($this->input->post('dprt_id'))) {
                if ($this->User_model->create($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    #set exception message
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('user/channel_partner');
            } else {
                if ($this->User_model->update($postData)) {
                    #set success message
                    if (!empty($this->session->password_error)) {
                        $this->session->set_flashdata('exception', 'pasword not updated');
                    } else {
                        $this->session->set_flashdata('message', display('update_successfully'));
                    }
                } else {
                    #set exception message
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
                redirect('user/channel_partner');
            }
        } else {
            $data['state_list'] = $this->location_model->state_list();
            $data['region_list'] = $this->location_model->region_list();
            $data['county_list'] = $this->User_model->display_country();
            //  $data['companey_list']= $this->doctor_model->reads();
            $data['department_list'] = $this->Modules_model->modules_list();
            $data['user_role'] = $this->db->get('tbl_user_role')->result();
            $data['user_list'] = $this->User_model->user_list();
            $data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
            $data['type_list'] = $this->User_model->types_list();
            $data['content'] = $this->load->view('partnerfrom', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
    }
    public function password_check($str)
    {
        if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
            return TRUE;
        }
        $this->form_validation->set_message('password_check', 'Password is invalid formate ');
        return FALSE;
    }
    public function partner_edit($id = null)
    {
        $data['title'] = display('edit_user');
        $data['page_title'] = 'Channel Partner';
        #-------------------------------#
        $data['state_list'] = $this->location_model->state_list();
        $data['city_list'] = $this->location_model->city_list();
        $data['region_list'] = $this->location_model->region_list();
        $data['territory_lsit'] = $this->location_model->territory_lsit();
        //		$data['companey_list']= $this->doctor_model->reads();
        $data['user_list'] = $this->User_model->user_list();
        $data['department_list'] = $this->Modules_model->modules_list();
        $data['user_role'] = $this->db->get('tbl_user_role')->result();
        $data['department'] = $this->User_model->read_by_id($id);
        $data['county_list'] = $this->location_model->country();
        $data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
        $data['type_list'] = $this->User_model->types_list();
        $data['content'] = $this->load->view('partnerfrom', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function edit($id = null)
    {
        if (user_role('131') == true) {}
        $data['title'] = display('edit_user');
        #-------------------------------#
        $data['state_list'] = $this->location_model->state_list();
        $data['city_list'] = $this->location_model->city_list();
        $data['region_list'] = $this->location_model->region_list();
        $data['territory_lsit'] = $this->location_model->territory_lsit();
        $data['companey_list'] = $this->User_model->companyList();
        $data['user_list'] = $this->User_model->user_list();
        $data['department_list'] = $this->Modules_model->modules_list();
        $data['user_role'] = $this->User_model->get_user_role_cid();
        $data['department'] = $this->User_model->read_by_id($id);
        $data['county_list'] = $this->location_model->country();
        //echo "<pre>";
        //print_r($data['county_list']);exit();
        $this->load->model('dash_model');
        $data['products_list'] = $this->dash_model->all_product_list();
        $data['products'] = $this->dash_model->all_process_list();
         $this->load->model('Branch_model');
        
        $data['user_meta'] = $this->user_model->get_user_meta($id,array('api_name','api_url'));
        $data['discount_list1']= $this->Branch_model->discount_list1();
		$data['discount_list']= $this->Branch_model->discount_list();
        if($this->session->companey_id == 65){
            
            $data['reporting_locations'] = $this->User_model->get_report_locations();
        }

        $data['region_lists']=$this->Branch_model->all_sales_region();
		$data['area_lists']=$this->Branch_model->all_sales_area();
		$data['branch_lists']=$this->Branch_model->all_sales_branch();
		$data['dept_lists']=$this->User_model->all_sales_dept();
        // print_r($data['user_meta']->api_name);
        // die();
        $data['content'] = $this->load->view('user_from', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function delete($dprt_id = null)
    {
        if ($this->User_model->delete($dprt_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('user');
    }
    public function delete_userrole($user_role = null)
    {
        if (user_role('142') == true) { }
        if ($this->User_model->delete_userrole($user_role)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('user/user_type');
    }
    public function update_role()
    {
        if (!empty($_POST)) {
            $user_type = $this->input->post('user_type');
            $role_id = $this->input->post('role_id');
            $this->db->set('user_role', $user_type);
            $this->db->where('use_id', $role_id);
            $this->db->update('tbl_user_role');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('user/user_type');
        }
    }
    public function update_status()
    {
        if (!empty($_POST)) {
            $status = $this->input->post('someSwitchOption001');
            $role_id = $this->input->post('role_id');
            $this->db->set('status', $status);
            $this->db->where('use_id', $role_id);
            $this->db->update('tbl_user_role');
            $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
            redirect('user/user_type');
        }
    }
    //Define user Permissions..
    public function permissions()
    {
        if (user_role('140') == true) {}
        $data['title'] = display('user_list');
        $this->db->select('id,title');
        $data['modules']    =   $this->db->get('all_modules')->result_array();
        $data['content'] = $this->load->view('user_permissions', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    //Open Edit user role form
    public function edit_user_role($role_id)
    {
        if (user_role('141') == true) {}
        $data['title'] = display('user_list');
        $data['user_role'] = $this->User_model->get_user_role($role_id);
        $this->db->select('id,title');
        $data['modules']    =   $this->db->get('all_modules')->result_array();
        $data['content'] = $this->load->view('edit_user_role', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    //Save updated user role data
    public function update_user_role()
    {
        $id = $this->input->post('role_id');
        $user_role = $this->input->post('user_type');
        $permissions = $this->input->post('permissions');
        $permissions = implode(',', $permissions);
        $data = array(
            'user_role' => $user_role,
            'user_permissions' => $permissions,
            //'parent_right'=>$this->input->post('parent_right')
        );
        $this->User_model->update_user_role($id, $data);
        $this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
        redirect('userrights/userrights_edit/' . $id);
    }

    public function upload_users()
    {
        ini_set('max_execution_time', '-1');
        $filename = "enquiry_" . date('d-m-Y_H_i_s');
        $config = array(
            'upload_path' => $_SERVER["DOCUMENT_ROOT"] . "/assets/enquiry",
            'allowed_types' => "application/vnd.ms-excel|csv|xlsx",
            /*'remove_spaces' => TRUE,*/
            'file_name' => $filename
        );
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('user')) {
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
            $u = 0;
            $dat_array = array();
            while (($filesop = fgetcsv($handle, 2000, ",")) !== false) {
                if ($c) {
                    // $right = 166;                                        
                    /* $post_data    =   array(       
                            'user_permissions'      => $right, 
                            'user_type'             => $right, 
                            's_password'            => md5('12345678'),
                            's_display_name'        => $filesop[2],
                            'last_name'             => '',
                            'designation'           => '',                                                
                            'companey_id'           => 57,
                            's_user_email'          => $filesop[7], 
                            's_phoneno'             => $filesop[6],
                            'process'               => "122",
                            'report_to'             => $filesop[3]   
                          );                
*/
                    $post_data = array('s_phoneno' => $filesop['2']);
                    //$this->db->where('s_user_email',$post_data['s_user_email']);
                    $this->db->where('s_phoneno', $post_data['s_phoneno']);
                    $r    =   $this->db->get('tbl_admin')->row_array();
                    /*echo "<pre>";
            print_r($r['s_user_email']);
            echo "</pre>";*/
                    if (empty($r) && $post_data['s_phoneno']) {
                        //$this->db->insert('tbl_admin',$post_data);
                        $this->db->set('status', 2);
                        $this->db->where('phone', $post_data['s_phoneno']);
                        $this->db->where('status', 3);
                        $this->db->where('comp_id', 57);
                        $this->db->update('enquiry');
                        $i++;
                    } else {
                        /*$this->db->where('s_user_email',$post_data['s_user_email']);
                    $this->db->set('user_permissions',$post_data['user_permissions']);
                    $this->db->set('user_type',$post_data['user_type']);
                    $this->db->update('tbl_admin');*/
                        //$u++;
                    }
                }
                if ($filesop[7]) {
                    $c++;
                }
            }
        }
        echo $this->upload->display_errors();
        echo $i . '<br>';
        echo $c . '<br>';
        echo $u;
        unlink($filePath);
        echo "<pre>";
        print_r($post_data);
        echo "</pre>";
        exit();
        /*$post_data    =   array(       
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
          );*/
        //$this->db->insert('tbl_admin',$post_data);
    }
    public function bulk_upload_user()
    {
        $data['title']  = 'test';
        $data['content'] = $this->load->view('upload_bulk_user', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
}
