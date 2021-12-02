<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Enq extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
 
		$this->load->model(
			array('enquiry_model', 'User_model', 'dash_model', 'common_model', 'report_model', 'Leads_Model','Ticket_Model','rule_model')
		);
		$this->load->library('email');
		$this->load->library('pagination');
		$this->load->library('user_agent');
		$this->lang->load("activitylogmsg", "english");
		if (empty($this->session->user_id)) {
			redirect('login');
		}
	}
	public function test(){
		$res = $this->common_model->get_user_ids($this->session->user_id);
		print_r($res);
		echo '<br>'.count($res).'<br>';

		$res = $this->common_model->get_categories($this->session->user_id);
		print_r($res);
		echo '<br>'.count($res);


	}


	public function index($all = '')
	{
		
//Create dumy enquery for all branchs
/* $this->load->model('Branch_model');
$data['branch_lists']=$this->Branch_model->all_sales_branch();
foreach($data['branch_lists'] as $branch){
	$bid = $branch->branch_id;
	$bname = $branch->branch_name;
	$aid = $branch->area_id;
	$rid = $branch->region_id;
	$clientname = 'VTG '.$branch->branch_name;
	$contactname = 'VTGC '.$branch->branch_name;
	$email = 'vtgroup'.$bid.'@vtrans.com';
	$phone = '0000000000'.$bid;
	$phone = substr ($phone, -10);
	
	            $branch = strtoupper($bname);
                $first = substr("$branch", 0, 4);
                $dt = date('d');
                $mt = date('m');
                $yt = date('y');
                $second = $dt . '' . $mt . '' . $yt;
                $third = mt_rand(10000, 99999);
                $encode = $first . '' . $second . '' . $third;
	
	$data = array(
        'comp_id'=>'65',
        'Enquery_id'=>$encode,
		'email'=>$email,
		'phone'=>$phone,
		'name_prefix'=>'Mr.',
		'name'=>'VTRANS GROUP',
		'lastname'=>' ',
		'gender'=>'1',
		'enquiry_source'=>'131',
		'status'=>'1',
		'drop_status'=>'0',
		'country_id'=>'133',
		'product_id'=>'141',
		'created_by'=>'286',
		'company'=>'16313',
		'city_id'=>'4303',
		'state_id'=>'166',
		'territory_id'=>'1892',
		'region_id'=>'115',
		'is_delete'=>'1',
		'client_name'=>$clientname,
		'client_type'=>'Pvt. Ltd.',
		'business_load'=>'FTL',
		'industries'=>'9',
		'sales_branch'=>$bid,
		'sales_region'=>$rid,
		'sales_area'=>$aid,
		'designation'=>'3',
		'dumy_data'=>'1',
    );	
    $this->db->insert('enquiry',$data);
	$insert_id = $this->db->insert_id();
	
	$datac = array(
        'comp_id'=>'65',
        'client_id'=>$insert_id,
		'designation'=>'3',
		'c_name'=>$contactname,
		'contact_number'=>$phone,
		'emailid'=>$email,
		'other_detail'=>'No Deatails',
		'decision_maker'=>'1',
		'dumy_contact'=>'1'
    );	
    $this->db->insert('tbl_client_contacts',$datac);
}  */
//end

//UPDATE CLIENT NAME USING COMPANY GROUP NAME + SALES BRANCH
$enq_data = $this->db->select('enquiry_id,company,sales_branch')->get('enquiry')->result();
foreach($enq_data as $val){
	$ec = $val->company;
	$eb = $val->sales_branch;
	$eid = $val->enquiry_id;	
	if(!empty($ec && $eb)){
	$cdata = $this->db->select('company_name')->where(array('id'=>$ec))->get('tbl_company')->row();
	$bdata = $this->db->select('branch_name')->where(array('branch_id'=>$eb))->get('branch')->row();
	if(!empty($cdata && $bdata)){
	$client_name = $cdata->company_name . " " . $bdata->branch_name;

    $this->db->set('client_name',$client_name);
    $this->db->where('enquiry_id',$eid);
    $this->db->update('enquiry');
	
	}else{
	$client_name = '';
	}
	}else{
	$client_name = ''; 	
	}
	
}
//END
		//$this->output->enable_profiler(TRUE);
		if (user_role('60') == true) {
		}
		$this->load->model('Datasource_model');
		$data['sourse'] = $this->report_model->all_source();
		$data['datasourse'] = $this->report_model->all_datasource();
		$data['lead_score'] = $this->enquiry_model->get_leadscore_list();
		$data['dfields']  = $this->enquiry_model->getformfield();
		//echo $this->db->last_query();exit();
		if($all=='All'){
		$data['data_type'] = '1,2,3,4,5,6,7';
		}else{
		$data['data_type'] = 1;	
		}
		$this->session->unset_userdata('enquiry_filters_sess');
		if (!empty($this->session->enq_type)) {
			$this->session->unset_userdata('enq_type', $this->session->enq_type);
		}
		
		if(!empty($_GET) && !empty($_GET['desposition'])){
            $desp = $this->db->where('stg_id',$_GET['desposition'])->get('lead_stage')->row();        
			$data['desp'] = $desp;			
			$this->session->set_userdata('enquiry_filters_sess',array('stage'=>$_GET['desposition']));
		}		
		if($all == 'All'){
			$data['title'] = 'All Clients';
		}else{
			$data['title'] = display('enquiry_list');
		}
		$data['subsource_list'] = $this->Datasource_model->subsourcelist();
		$data['user_list'] = $this->User_model->companey_users();
		if($this->session->companey_id == 65 && $this->session->user_right == 215){
			$data['created_bylist'] = $this->User_model->read(147,false);
		}else{
			$data['created_bylist'] = $this->User_model->read();
		}
		$data['products'] = $this->dash_model->get_user_product_list();
		$data['drops'] = $this->enquiry_model->get_drop_list();
		$data['all_stage_lists'] = $this->Leads_Model->get_leadstage_list_byprocess1($this->session->process,1);		
		$data['prodcntry_list'] = $this->enquiry_model->get_user_productcntry_list();
		$data['state_list'] = $this->enquiry_model->get_user_state_list();
		$data['city_list'] = $this->enquiry_model->get_user_city_list();
		$data['filterData'] = $this->Ticket_Model->get_filterData(1);


		if(!empty($_GET['from'])){
			$data['filterData']['from_created'] = $_GET['from'];
		}

		if(!empty($_GET['to'])){
			$data['filterData']['to_created'] = $_GET['to'];
		}



		$data['aging_rule'] = $this->rule_model->get_rules(array(11));	

		// $list =  $this->db->select('input_id')->where(array('process_id'=>$this->session->process[0],'company_id'=>$this->session->companey_id,'status'=>'1','page_id'=>'2'))->get('tbl_input')->result();
		// echo $this->db->last_query();
		// print_r($list);exit();
       //$list = array_column($list, 'input_id');

       //$data['table_config_list'] = $list;
        $this->load->model('Branch_model');
		$data['tags'] = $this->enquiry_model->get_tags();
        $data['branch_lists']=$this->Branch_model->all_sales_branch();
		$data['region_lists']=$this->Branch_model->all_sales_region();
		$data['area_lists']=$this->Branch_model->all_sales_area();
		$data['dept_lists']=$this->User_model->all_sales_dept();
		$data['content'] = $this->load->view('enquiry_n', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}	
	public function chk()
	{
		//print_r($this->session->enquiry_filters_sess);
	}

	public function enq_load_data()
	{
		//$this->output->enable_profiler(TRUE);
		//print_r($_POST['data_type']);exit();	
		$this->load->model('enquiry_datatable_model');
		$list = $this->enquiry_datatable_model->get_datatables();

		if($this->session->companey_id == 1){
			//echo $this->db->last_query();
		}
		$dfields = $this->enquiry_model->getformfield(0); //0 for enquiry
		$data = array();
		$no = $_POST['start'];
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
		$fieldval =  $this->enquiry_model->getfieldvalue(); 
		foreach ($list as $each) 
		{
			$no++;
			$row = array();
			$row[] = "<input onclick='event.stopPropagation();'' type='checkbox' name='enquiry_id[]'' class='checkbox1' value=" . $each->enquiry_id . ">";
			if ($_POST['data_type'] == 1) {
				$url = base_url('enquiry/view/') . $each->enquiry_id.'/'.base64_encode($_POST['data_type']);
			} else if ($_POST['data_type'] == 2) {
				$url = base_url('lead/lead_details/') . $each->enquiry_id.'/'.base64_encode($_POST['data_type']);
			} else if ($_POST['data_type'] == 3) {
				$url = base_url('client/view/') . $each->enquiry_id.'/'.base64_encode($_POST['data_type']);
			} else {
				$url = base_url('client/view/') . $each->enquiry_id.'/'.base64_encode($_POST['data_type']);
			}
			$row[] = '<a href="' . $url . '">' . $no/*$each->enquiry_id*/ . '</a>';
			if ($showall == true or in_array(1, $acolarr)) {
				$row[] = (!empty($each->lead_name)) ? ucwords($each->lead_name) : "NA";
			}
			if ($showall == true or in_array(16, $acolarr)) {
				$row[] = (!empty($each->subsource_name)) ? ucwords($each->subsource_name) : "NA";
			}
			if ($showall == true or in_array(2, $acolarr)) {
				$row[] = (!empty(trim($each->company_name))) ? ucwords($each->company_name) : "NA";

				if(!empty($_POST['data_type']) && count(explode(',',$_POST['data_type']))>1)
				{
					$sname ='';
					if($each->status>3)
					{
						$enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
			            if (!empty($enquiry_separation)) {
			                $enquiry_separation = json_decode($enquiry_separation, true);
			                $stage    =   $each->status;
			                $sname = $enquiry_separation[$stage]['title'];
			                // $url = 'client/view/' . $enquiryid . '?stage=' . $stage;
			                // $comment = $title . ' Activated';
			            }

					}
					else
					{
						$st = array('1'=>display('enquiry'),
								'2'=>display('lead'),
								'3'=>display('client'),
								);
						$sname = $st[$each->status];
					
					}

					$row[] = $sname;
				}
				
			}

			if ($showall == true or in_array(21, $acolarr)) {
				$row[] = $each->client_name;
			}

			if ($showall == true or in_array(3, $acolarr)) {
				$thtml = '';
				if(!empty($each->tag_ids)){
					$this->db->select('title,color');
					$this->db->where("id IN(".$each->tag_ids.")");
					$tags = $this->db->get('tags')->result_array();
					if(!empty($tags)){
						foreach ($tags as $key => $value) {
							$thtml .= '<br><a class="badge" href="javascript:void(0)" style="background:'.$value['color'].';padding:4px;">'.$value['title'].'</a>';
						}
					}
				}
				$row[] = '<a href="' . $url . '">' . $each->name_prefix . " " . $each->name . " " . $each->lastname. '</a>'.$thtml;
			}
			if ($showall == true or in_array(4, $acolarr)) {
				$row[] = (!empty($each->email)) ? $each->email : "NA";
			}
			if ($showall == true or in_array(5, $acolarr)) {
				$p = $each->phone;
				if (user_access(450)) {
					$p = '##########';
				}
				$c = $this->session->companey_id;
				if (user_access(220) && $c!=65) {
					$row[] = "<a href='javascript:void(0)' onclick='send_parameters(".$each->phone.")'>" . $p . " <button class='fa fa-phone btn btn-xs btn-success'></button></a>";
				} else {
					$row[] = (!empty($each->phone)) ? '<a  href="tel:' . $p . '">' . $p . '</a>' : "NA";
				}
			}
			if ($showall == true or in_array(6, $acolarr)) {
				$row[] = (!empty(trim($each->address))) ? ucwords($each->address) : "NA";
			}
			if ($showall == true or in_array(7, $acolarr)) {
				$row[] = (!empty($each->product_name)) ? ucwords($each->product_name) : "NA";
			}
			if ($showall == true or in_array(30, $acolarr)) {
				$row[] = (!empty($each->lead_stage_name)) ? ucwords($each->lead_stage_name) : "NA";
			}
			if ($showall == true or in_array(8, $acolarr)) {
				if ($each->lead_stage_name) {
					$option = '<option value="' . $each->lead_stage_name . '">' . ucwords($each->lead_stage_name) . '</option>';
				} else {
					$option = '<option value="0">Select Disposition</option>';
				}
				$row[] = '<select class="form-control change_dispositions" style="height: 11px;width: 60%;font-size: smaller;padding: 4px;" data-id="' . $each->enquiry_id . '" data-stages="'.$each->status .'" >' . $option . '</select>';
			}
			if ($this->session->companey_id == 29) {
				//$row[] = (!empty($each->reference_name)) ? $each->reference_name : "NA";
				if (!empty($each->reference_name)) {
					$this->db->where('TRIM(partner_id)', trim($each->reference_name));
					$this->db->where('comp_id', $this->session->companey_id);
					$ref_row  = $this->db->get('enquiry')->row_array();
					$src = '';
					if ($ref_row['product_id'] == 95) {
						$src = '(Customer)';
					} else if ($ref_row['product_id'] == 91) {
						$src = '(Patner)';
					}
					$row[] = '<a href="' . base_url() . 'enquiry/view/' . $ref_row['enquiry_id'] . '">' . $ref_row['name_prefix'] . ' ' . $ref_row['name'] . ' ' . $ref_row['lastname'] . $src . '</a>';
				} else {
					$row[] = 'NA';
				}
			}
			if ($showall == true or in_array(10, $acolarr)) {
				$row[] = (!empty($each->created_date)) ? $each->created_date : "NA";
			}
			$c = array();
			$c1 = array();
			$d = array();
			if (!empty($each->t)) {
				$b = $each->t;
				$c	=	explode('_', $b);
				if (!empty($c[0])) {
					$u	=	explode('#', $c[0]);
					$d[]	=	$u[0];
					$c1[]	=	$u[1];
				}
				if (!empty($c[1])) {
					$u	=	explode('#', $c[1]);
					$d[]	=	$u[1];
					$c1[]	=	$u[1];
				}
			}
			if ($showall == true or in_array(11, $acolarr)) {
				$a = (!empty($each->created_by_name)) ? ucwords($each->created_by_name) : "NA";
				if (empty($c1[0]) || $c1[0] == 2) {
					$row[] = $a . '<a class="tag">NEW</a>';
				} else {
					$row[] = $a;
				}
			}
			if ($showall == true or in_array(31, $acolarr)) {
				$row[] = (!empty($each->createbydept)) ? ucwords($each->createbydept) : "NA";
			}
			if ($showall == true or in_array(12, $acolarr)) {
				$a = (!empty($each->assign_to_name)) ? ucwords($each->assign_to_name) : "NA";
				if ((empty($c1[1]) || $c1[1] == 2) && !in_array($each->aasign_to, $d)) {
					if ($a != 'NA') {
						$row[] = $a . '<a class="tag">NEW</a>';
					} else {
						$row[] = $a;
					}
				} else {
					$row[] = $a;
				}
			}
			if ($showall == true or in_array(32, $acolarr)) {
				$row[] = (!empty($each->assignbydept)) ? ucwords($each->assignbydept) : "NA";
			}
			if ($showall == true or in_array(13, $acolarr)) {
				$row[] = (!empty($each->datasource_name)) ? ucwords($each->datasource_name) : "NA";
			}
			if ($showall == true or in_array(14, $acolarr)) {
				$row[] = (!empty($each->country_name)) ? ucwords($each->country_name) : "NA";
			}
			if ($this->session->companey_id == 29) {
				if ($showall == true or in_array(15, $acolarr)) {
					$row[] = (!empty($each->bank_name)) ? ucwords($each->bank_name) : "NA";
				}
			}
			if ($showall == true or in_array(17, $acolarr)) {
				$row[] = (!empty($each->Enquery_id)) ? $each->Enquery_id : "NA";
			}
			if ($showall == true or in_array(18, $acolarr)) {
				$sc = (!empty($each->score)) ? $each->score : "NA";				
				
				if(!empty($each->score_name)){
					$row[] = $sc.' <span class="label label-primary">'.$each->score_name.'</span';
				}else{					
					$row[] = $sc;
				}

			}
			if ($showall == true or in_array(19, $acolarr)) {
				$row[] = (!empty($each->enquiry)) ? $each->enquiry : "NA";
			}
			if ($showall == true or in_array(33, $acolarr)) {
				$empreg = $this->db->select('name')->get_where('sales_region', array('region_id' => $each->as_region))->row();
				if(empty($empreg->name)){
				$empreg = $this->db->select('name')->get_where('sales_region', array('region_id' => $each->cr_region))->row();	
				}				
				$row[] = (!empty($empreg->name)) ? $empreg->name : "NA";
			}
			if ($showall == true or in_array(34, $acolarr)) {
				$empara = $this->db->select('area_name')->get_where('sales_area', array('area_id' => $each->as_area))->row();
				if(empty($empara->area_name)){
				$empara = $this->db->select('area_name')->get_where('sales_area', array('area_id' => $each->cr_area))->row();	
				}
				$row[] = (!empty($empara->area_name)) ? $empara->area_name : "NA";
			}
			if ($showall == true or in_array(35, $acolarr)) {
			
			$branches = '';
            $branches_arr = explode(',', $each->as_branch);
            $this->db->select('branch_name');
            $this->db->where_in('branch_id', $branches_arr);
            $b_res    =   $this->db->get('branch')->result_array();
            if (!empty($b_res)) {
                foreach ($b_res as $key => $value) {
                    $branches .= $value['branch_name'].', ';
                }
            }
				//$empbrh = $this->db->select('branch_name')->get_where('branch', array('branch_id' => $each->as_branch))->row();
				if(empty($empbrh->branch_name)){
					
			if(empty($branches)){
            $branches_arr = explode(',', $each->cr_branch);
            $this->db->select('branch_name');
            $this->db->where_in('branch_id', $branches_arr);
            $b_res    =   $this->db->get('branch')->result_array();
            if (!empty($b_res)) {
                foreach ($b_res as $key => $value) {
                    $branches .= $value['branch_name'].', ';
                }
            }	
				}
				//$empbrh = $this->db->select('branch_name')->get_where('branch', array('branch_id' => $each->cr_branch))->row();	
				}
				$row[] = (!empty($branches)) ? $branches : "NA";
			}
			
			if ($showall == true or in_array(36, $acolarr)) {
				$slreg = $this->db->select('name')->get_where('sales_region', array('region_id' => $each->sl_region))->row();				
				$row[] = (!empty($slreg->name)) ? $slreg->name : "NA";
			}
			if ($showall == true or in_array(37, $acolarr)) {
				$slara = $this->db->select('area_name')->get_where('sales_area', array('area_id' => $each->sl_area))->row();
				$row[] = (!empty($slara->area_name)) ? $slara->area_name : "NA";
			}
			if ($showall == true or in_array(38, $acolarr)) {
				$slbrh = $this->db->select('branch_name')->get_where('branch', array('branch_id' => $each->sl_branch))->row();
				$row[] = (!empty($slbrh->branch_name)) ? $slbrh->branch_name : "NA";
			}
			
			if ($showall == true or in_array(20, $acolarr)) {
				$row[] = (!empty($each->visit_count)) ? $each->visit_count : "NA";
			}
			$enqid = $each->enquiry_id;
			if (!empty($dacolarr) and !empty($dfields)) {
				foreach ($dfields as $ind => $flds) {
					if (in_array($flds->input_id, $dacolarr)) {
						$row[] = (!empty($fieldval[$enqid][$flds->input_id])) ? $fieldval[$enqid][$flds->input_id]->fvalue : "NA";
					}
				}
			}
			$data[] = $row;
		}
		$c = $this->enquiry_datatable_model->count_all();

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $c,
			"recordsFiltered" => $this->enquiry_datatable_model->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function stages_of_enq($data_type = 1)
	{
		$data['all_enquery_num'] = $this->enquiry_model->all_enquery($data_type);
		$data['all_drop_num'] = $this->enquiry_model->all_drop($data_type);
		$data['all_active_num'] = $this->enquiry_model->active_enqueries($data_type);
		$data['all_today_update_num'] = $this->enquiry_model->all_today_update($data_type);
		$data['all_creaed_today_num'] = $this->enquiry_model->all_creaed_today($data_type);
		echo json_encode($data);
	}
	public function short_dashboard_count()
	{
		$this->common_query_short_dashboard(0);
		$this->db->group_by('enquiry.Enquery_id');
		$data['all_enquery_num'] = $this->db->count_all_results();


		$this->common_query_short_dashboard(0); 
		$this->db->where('enquiry.drop_status>0');
		$this->db->group_by('enquiry.Enquery_id');
		$data['all_drop_num'] = $this->db->count_all_results();

	

		$this->common_query_short_dashboard();
		$this->db->where(' enquiry.drop_status=0');
		$this->db->group_by('enquiry.Enquery_id');
		$data['all_active_num']= $this->db->count_all_results();

		$this->common_query_short_dashboard();
		$this->db->where('enquiry.update_date is not NULL'); //anyhow updated
		$this->db->group_by('enquiry.Enquery_id');
		$data['all_update_num']=$this->db->count_all_results();


		$this->common_query_short_dashboard();
	
		$this->db->where('enquiry.lead_stage',0);
		//now check empty dispositon
		$this->db->group_by('enquiry.Enquery_id');
		$data['all_no_activity_num']=$this->db->count_all_results();

		$this->common_query_short_dashboard();
		//$date=date('Y-m-d');
		$this->db->where('enquiry.aasign_to is not NULL ');
		$this->db->group_by('enquiry.Enquery_id');
		$data['all_assigned_num']=$this->db->count_all_results();

		$this->common_query_short_dashboard();
		//$date=date('Y-m-d');
		$this->db->where('enquiry.aasign_to is NULL ');
		$this->db->group_by('enquiry.Enquery_id');
		$data['all_unassigned_num']=$this->db->count_all_results();

		echo json_encode($data);
	}
	public function count_stages($data_type = 2)
	{
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$user_id   = $this->session->user_id;
		$user_role = $this->session->user_role;
		$assign_country = $this->session->country_id;
		$assign_region = $this->session->region_id;
		$assign_territory = $this->session->territory_id;
		$assign_state = $this->session->state_id;
		$assign_city = $this->session->city_id;
		$where = '';
		$enquiry_filters_sess    =   $this->session->enquiry_filters_sess;
		$top_filter     = !empty($enquiry_filters_sess['top_filter']) ? $enquiry_filters_sess['top_filter'] : '';

		if ($top_filter == 'all') {
		} elseif ($top_filter == 'droped') {
			$where .= "  enquiry.drop_status>0";
		} elseif ($top_filter == 'created_today') {
			$date = date('Y-m-d');
			$where .= "enquiry.created_date LIKE '%$date%'";
			$where .= " AND enquiry.drop_status=0";
		} elseif ($top_filter == 'updated_today') {
			$date = date('Y-m-d');
			$where .= "  enquiry.update_date LIKE '%$date%'";
			$where .= " AND enquiry.drop_status=0";
		} elseif ($top_filter == 'active') {
			$where .= "  enquiry.drop_status=0";
		} else {
			$where .= "  enquiry.drop_status=0";
		}
		if (!empty($where)) {
			$where .= " AND enquiry.status=2 ";
		} else {
			$where .= " enquiry.status=2 ";
		}

		$where .= " AND ( enquiry.created_by IN (" . implode(',', $all_reporting_ids) . ')';
		$where .= " OR enquiry.aasign_to IN (" . implode(',', $all_reporting_ids) . '))';

		$enquiry_filters_sess    =   $this->session->enquiry_filters_sess;
		$product_filter = !empty($enquiry_filters_sess['product_filter']) ? $enquiry_filters_sess['product_filter'] : '';
		if (!empty($this->session->process) && empty($product_filter)) {
			$arr = $this->session->process;
			if (is_array($arr)) {
				$where .= " AND enquiry.product_id IN (" . implode(',', $arr) . ')';
			}
		} else if (!empty($this->session->process) && !empty($product_filter)) {
			$where .= " AND enquiry.product_id IN (" . implode(',', $product_filter) . ')';
		}
		$this->db->select('lead_stage,count(lead_stage) as c');
		$this->db->from('enquiry');
		$this->db->where($where);
		$this->db->group_by('lead_stage');
		$res = json_encode($this->db->get()->result_array());
		echo $res;
	}
	public function enquiry_set_filters_session()
	{

		$this->session->set_userdata('enquiry_filters_sess', $_POST);
	}
	
	public function log_set_filters_session()
	{

		$this->session->set_userdata('log_filters_sess', $_POST);
	}
	public function set_process_session()
	{
		$this->session->set_userdata('process', $this->input->post('process'));
	}
	
	public function enquiry_disposition($enq)
	{

		$lead_stages = $this->Leads_Model->find_stage();
		// print_r($lead_stages);
		$dis	=	$this->input->post('disposition');
		 $for	=	$this->input->post('stages');
		if($for > 3){ echo $for=3; }
		$option = '<option value="0">Select Disposition</option>';
		if (!empty($lead_stages)) {
			foreach ($lead_stages as $key => $value) {
				if (trim($dis) == trim($value->lead_stage_name)) {
					$option .= "<option selected value='" . $value->lead_stage_name . "'>" . $value->lead_stage_name . "</option>";
				} else {
					$process = explode(',', $value->process_id);    
					$stage = explode(',', $value->stage_for);    
					$extprocess=$this->session->userdata('process');
				     $count_array=count(array_intersect($extprocess,$process));
				if(in_array($for,$stage) AND $count_array!=0){
					$option .= "<option value='" . $value->lead_stage_name . "'>" . $value->lead_stage_name . "</option>";
					}
				}
			}
		}
		echo $option;
	}
	public function enquiry_update_disposition($enq)
	{
		$dis	=	$this->input->post('disposition');
		$this->db->select('stg_id');
		$this->db->where('TRIM(lead_stage_name)', trim($dis));
		$this->db->where('comp_id', $this->session->companey_id);
		$res	=	$this->db->get('lead_stage')->row_array();
		$stage_id = $res['stg_id'];
		$this->db->where('enquiry_id', $enq);
		$this->db->set('lead_stage', $stage_id);
		$this->db->update('enquiry');
		$stage_desc = '';
		$stage_remark = '';
		$this->db->select('status,Enquery_id');
		$this->db->where('enquiry_id', $enq);
		$e_res	=	$this->db->get('enquiry')->row_array();
		$coment_type  = $e_res['status'];
		$enq = $e_res['Enquery_id'];
		$this->Leads_Model->add_comment_for_events_stage('Stage Updated', $enq, $stage_id, $stage_desc, $stage_remark, $coment_type);
	}
	public function report_to_correct()
	{
		$this->db->where('companey_id', 57);
		$res	=	$this->db->get('tbl_admin')->result_array();
		foreach ($res as $key => $value) {
			echo $value['lid'] . ' ' . $value['pk_i_admin_id'] . '<br>';
			$this->db->where('comp_id', 57);
			$this->db->where('created_by', $value['lid']);
			$this->db->set('created_by', $value['pk_i_admin_id']);
			$this->db->update('tbl_comment');
		}
	}
	public function lead_stage_correct()
	{
		$arr = array(1, 2, 3, 4, 11, 13, 15, 16);
		foreach ($arr as $value) {
			if ($value == '1') {
				$a = 208;
			} else if ($value == '2') {
				$a = 209;
			} else if ($value == '3') {
				$a = 210;
			} else if ($value == '4') {
				$a = 211;
			} else if ($value == '11') {
				$a = 212;
			} else if ($value == '13') {
				$a = 213;
			} else if ($value == '15') {
				$a = 214;
			} else if ($value == '16') {
				$a = 215;
			}
			if (!empty($a)) {
				$this->db->where('comp_id', 57);
				$this->db->where('lead_stage', $value);
				$this->db->set('lead_stage', $a);
				$this->db->update('enquiry');
			}
		}
	}
	public function created_date_correct()
	{
		$q	=	$this->db->query("SELECT * FROM `enquiry` WHERE enquiry.created_date is null and enquiry.comp_id !=29");
		$r	=	$q->result_array();
		foreach ($r as $key => $value) {
			$v = $value['Enquery_id'];
			$q1 = $this->db->query("SELECT * FROM `tbl_comment` WHERE tbl_comment.lead_id LIKE '%" . $v . "%' AND tbl_comment.comment_msg LIKE '%Enquiry Created%'");
			$r1	=	$q1->row_array();
			$where = "Enquery_id LIKE '%" . $v . "%' AND enquiry.created_date is null and enquiry.comp_id !=29";
			$this->db->where($where);
			$this->db->set('created_date', $r1['created_date']);
			$this->db->update('enquiry');
		}
	}
	public function common_query_short_dashboard($drop=1)
	{
		$this->load->model('common_model');

		$_POST['search']['value']='';
		$table = 'enquiry';
	    $column_order = array('','enquiry.enquiry_id','lead_source.lead_name', 'enquiry.company','enquiry.name','enquiry.enquiry_source','enquiry.email','enquiry.phone','enquiry.address','enquiry.created_date','enquiry.created_by','enquiry.aasign_to','tbl_datasource.datasource_name'); //set column field database for datatable orderable
	    $column_search = array('enquiry.name_prefix','enquiry.enquiry_id','enquiry.company','enquiry.org_name','enquiry.name','enquiry.lastname','enquiry.email','enquiry.phone','enquiry.address','enquiry.created_date','enquiry.enquiry_source','lead_source.icon_url','lead_source.lsid','lead_source.score_count','lead_source.lead_name','tbl_datasource.datasource_name','tbl_product.product_name',"CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name )","CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name)"); //set column field database for datatable searchable 
	    $order = array('enquiry.enquiry_id' => 'desc'); // default order 
	    $all_reporting_ids  = $this->common_model->get_categories($this->session->user_id);

	       $this->db->from($table);       
	       
	      $user_id   = $this->session->user_id;
	    $where='';
        $enquiry_filters_sess   =   $this->session->enquiry_filters_sess;
        $top_filter             =   !empty($enquiry_filters_sess['top_filter'])?$enquiry_filters_sess['top_filter']:'';        
        $from_created           =   !empty($enquiry_filters_sess['from_created'])?$enquiry_filters_sess['from_created']:'';       
        $to_created             =   !empty($enquiry_filters_sess['to_created'])?$enquiry_filters_sess['to_created']:'';
        $source                 =   !empty($enquiry_filters_sess['source'])?$enquiry_filters_sess['source']:'';
        $sub_source             =   !empty($enquiry_filters_sess['subsource'])?$enquiry_filters_sess['subsource']:'';
        $email                  =   !empty($enquiry_filters_sess['email'])?$enquiry_filters_sess['email']:'';
        $employee               =   !empty($enquiry_filters_sess['employee'])?$enquiry_filters_sess['employee']:''; 
        $datasource             =   !empty($enquiry_filters_sess['datasource'])?$enquiry_filters_sess['datasource']:'';
        $company                =   !empty($enquiry_filters_sess['company'])?$enquiry_filters_sess['company']:'';
        $enq_product            =   !empty($enquiry_filters_sess['enq_product'])?$enquiry_filters_sess['enq_product']:'';
        $phone                  =   !empty($enquiry_filters_sess['phone'])?$enquiry_filters_sess['phone']:'';
        $createdby              =   !empty($enquiry_filters_sess['createdby'])?$enquiry_filters_sess['createdby']:'';
        $assign                 =   !empty($enquiry_filters_sess['assign'])?$enquiry_filters_sess['assign']:'';
        $address                =   !empty($enquiry_filters_sess['address'])?$enquiry_filters_sess['address']:'';
        $product_filter         =   !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
        $assign_filter          =   !empty($enquiry_filters_sess['assign_filter'])?$enquiry_filters_sess['assign_filter']:'';
        $stage                  =   !empty($enquiry_filters_sess['stage'])?$enquiry_filters_sess['stage']:'';
         $productcntry          =   !empty($enquiry_filters_sess['prodcntry'])?$enquiry_filters_sess['prodcntry']:'';
        $state                  =   !empty($enquiry_filters_sess['state'])?$enquiry_filters_sess['state']:'';
        $city                   =   !empty($enquiry_filters_sess['city'])?$enquiry_filters_sess['city']:'';

         $select = "enquiry.name_prefix,enquiry.enquiry_id,tbl_subsource.subsource_name,enquiry.created_by,enquiry.aasign_to,enquiry.Enquery_id,enquiry.score,enquiry.enquiry,enquiry.company,tbl_product_country.country_name,enquiry.org_name,enquiry.name,enquiry.lastname,enquiry.email,enquiry.phone,enquiry.address,enquiry.reference_name,enquiry.created_date,enquiry.enquiry_source,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,lead_stage.lead_stage_name,tbl_datasource.datasource_name,tbl_product.product_name as product_name,CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name) as created_by_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name";

        if($this->session->userdata('companey_id')==29){
            $select.= ",tbl_bank.bank_name";
            $this->db->join('tbl_newdeal ', 'tbl_newdeal.enq_id = enquiry.Enquery_id', 'left');
            $this->db->join('tbl_bank ', 'tbl_bank.id = tbl_newdeal.bank', 'left');
        }
       

        $data_type = $_POST['data_type']; 


        $this->db->select($select);                
        $this->db->join('lead_source','enquiry.enquiry_source = lead_source.lsid','left');
        $this->db->join('tbl_product','enquiry.product_id = tbl_product.sb_id','left');
        $this->db->join('lead_stage','lead_stage.stg_id = enquiry.lead_stage','left');   
        $this->db->join('tbl_product_country','tbl_product_country.id = enquiry.enquiry_subsource','left');
        $this->db->join('tbl_subsource','tbl_subsource.subsource_id = enquiry.sub_source','left');        
        $this->db->join('tbl_datasource','enquiry.datasource_id = tbl_datasource.datasource_id','left');
        $this->db->join('tbl_admin as tbl_admin', 'tbl_admin.pk_i_admin_id = enquiry.created_by', 'left');
        $this->db->join('tbl_admin as tbl_admin2', 'tbl_admin2.pk_i_admin_id = enquiry.aasign_to', 'left');        
	
	    $this->db->join('commercial_info','commercial_info.enquiry_id = enquiry.enquiry_id','left');
		//$where.="  enquiry.status=$data_type";
		$where.="  (enquiry.status IN ($data_type) ";
		$where.=" OR commercial_info.stage_id='".$data_type."')";
		if($drop==1)
			$where.=" AND enquiry.drop_status=0";

        if(isset($enquiry_filters_sess['lead_stages']) && $enquiry_filters_sess['lead_stages'] !=-1){
            $stage  =   $enquiry_filters_sess['lead_stages'];
            $where .= " AND enquiry.lead_stage=$stage";
        }  
        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';          
        if(!empty($this->session->process) && empty($product_filter)){              
            $arr = $this->session->process;           
            if(is_array($arr)){
                $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
            }                       
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
        }

        if($data_type=='1')
        	$enq_date_fld = 'created_date';
        else if($data_type=='2')
        	$enq_date_fld = 'lead_created_date';
        else if($data_type=='3')
        	$enq_date_fld = 'client_created_date';
        else 
        	$enq_date_fld = 'created_date';

        if(!empty($from_created) && !empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(enquiry.".$enq_date_fld.") >= '".$from_created."' AND DATE(enquiry.".$enq_date_fld.") <= '".$to_created."'";
        }
        if(!empty($from_created) && empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $where .= " AND DATE(enquiry.".$enq_date_fld.") >=  '".$from_created."'";                        
        }
        if(empty($from_created) && !empty($to_created)){            
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(enquiry.".$enq_date_fld.") <=  '".$to_created."'";                                    
        }
        if(!empty($company)){                    
            $where .= " AND enquiry.company =  '".$company."'";                                    
        }
        if(!empty($source)){                       
            $where .= " AND enquiry.enquiry_source =  '".$source."'";                                    
        }
        
        if(!empty($sub_source)){                       
            $where .= " AND enquiry.sub_source =  '".$sub_source."'";                                    
        }
        if(!empty($employee)){          
            $where .= " AND CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) LIKE  '%$employee%' ";
        }
        if(!empty($email)){ 
            $where .= " AND enquiry.email =  '".$email."'";                                    
        }
        if(!empty($datasource)){            
           
            $where .= " AND enquiry.datasource_id =  '".$datasource."'";                                    
        }
         if(!empty($enq_product)){            
           
            $where .= " AND enquiry.product_id =  '".$enq_product."'";                                    
        }
        if(!empty($phone)){            
           
            $where .= " AND enquiry.phone =  '".$phone."'";                                    
        }
        if(!empty($createdby)){            
           
            $where .= " AND enquiry.created_by =  '".$createdby."'";                                    
        }
         if(!empty($assign)){            
           
            $where .= " AND enquiry.aasign_to =  '".$assign."'";                                    
        }
        if(!empty($address)){            
           
            $where .= " AND enquiry.address LIKE  '%$address%'";                                    
        }
        if(!empty($stage)){
            $where .= " AND enquiry.lead_stage='".$stage."'"; 
        }
        if(!empty($productcntry)){            
           
            $where .= " AND enquiry.enquiry_subsource='".$productcntry."'";                                    
        }
        if(!empty($state) && empty($city)){
            $where .= " AND enquiry.state_id='".$state."'"; 
        }
          if(empty($state) && !empty($city)){
            $where .= " AND enquiry.city_id='".$city."'"; 
        }
        if(!empty($state) && !empty($city)){
            $where .= " AND enquiry.state_id='".$state."' AND enquiry.city_id='".$city."'"; 
        }
        $this->db->where($where);
    
            
        $i = 0;
     
        foreach ($column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        
            if(!empty($_POST['search']['value'])) // if datatable send POST for search
            {
                $compid = $this->session->companey_id;
                $val = $_POST['search']['value'];
                $this->db->or_where("enquiry.enquiry_id IN (SELECT parent FROM extra_enquery WHERE cmp_no = '$compid' AND fvalue LIKE '%{$val}%')");
                
            }   
        
       
        if(isset($_POST['order'])) // here order processing
        {
            if(!empty($column_order[$_POST['order']['0']['column']]) and $column_order[$_POST['order']['0']['column']] < count($column_order)){
                
                $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
            }else{
                
                //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            }
            
        } 
        else if(isset($order))
        {
            $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
	}

	function drop_tag()
	{
	//        if (!empty($_POST)) {

		$id[] = $this->input->post('id');
		$enq_id = $this->input->post('enq');

		$this->db->select('tag_ids');
		$this->db->from('enquiry_tags');
		$this->db->where('enq_id', $enq_id);
		$res = $this->db->get()->row()->tag_ids;
		$abc = explode(',', $res);
		$result = array_diff($abc, $id);

		$data = implode(",", $result);
	//        print_r();
	//        exit();


		$this->db->where('enq_id', $enq_id);
		$this->db->set('tag_ids', $data);
		$this->db->update('enquiry_tags');



		print_r($this->db->last_query());
		exit();
	//        }


	}
	
function export_enq_all(){
		
$file_name = 'all_client_'.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
     // get data
$select = "
lead_source.lead_name,
tbl_company.company_name,
enquiry.client_name,
CONCAT(enquiry.name,' ',enquiry.lastname) as enname,
enquiry.email,
enquiry.phone,
enquiry.address,
tbl_product.product_name as product_name,
lead_stage.lead_stage_name,
enquiry.created_date,
CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name) as created_by_name,
create_dept.dept_name as createbydept,
CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name,
assign_dept.dept_name as assignbydept,
tbl_datasource.datasource_name,
lead_score.score_name,
enquiry.enquiry,
sales_region.name as as_region,
sales_area.area_name as as_area,
branch.branch_name as as_branch,
enq_sales_region.name as sl_region,
enq_sales_area.area_name as sl_area,
enq_branch.branch_name as sl_branch,
visit_tbl.visit_count,
enquiry.Enquery_id,
tbl_subsource.subsource_name,
extra_dept.enq_dept,
extra_dept1.enq_ccode,
extra_dept2.enq_std,
extra_dept3.enq_competitor,
extra_dept4.enq_sensitive,
extra_dept5.enq_web,
extra_dept6.enq_pin
";

    $this->db->select($select);
	$this->db->from('enquiry');        
    $this->db->join('lead_source','enquiry.enquiry_source = lead_source.lsid','left');
	$this->db->join('tbl_company','tbl_company.id=enquiry.company','left');	
    $this->db->join('tbl_product','enquiry.product_id = tbl_product.sb_id','left');	
    $this->db->join('lead_stage','lead_stage.stg_id = enquiry.lead_stage','left');
	$this->db->join('tbl_admin as tbl_admin', 'tbl_admin.pk_i_admin_id = enquiry.created_by', 'left');
    $this->db->join('tbl_admin as tbl_admin2', 'tbl_admin2.pk_i_admin_id = enquiry.aasign_to', 'left'); 
    $this->db->join('tbl_department as create_dept', 'create_dept.id = tbl_admin.dept_name', 'left');
    $this->db->join('tbl_department as assign_dept', 'assign_dept.id = tbl_admin2.dept_name', 'left');
	$this->db->join('tbl_datasource','enquiry.datasource_id = tbl_datasource.datasource_id','left');
	$this->db->join('lead_score','lead_score.sc_id = enquiry.lead_score','left');
	$this->db->join('tbl_subsource','tbl_subsource.subsource_id = enquiry.sub_source','left');
	$this->db->join('(select count(tbl_visit.enquiry_id) as visit_count,tbl_visit.enquiry_id from tbl_visit group by tbl_visit.enquiry_id) as visit_tbl','visit_tbl.enquiry_id=enquiry.enquiry_id','left');
	
	$this->db->join('sales_region','sales_region.region_id = tbl_admin2.sales_region','left');
	$this->db->join('sales_area','sales_area.area_id = tbl_admin2.sales_area','left');
	$this->db->join('branch','branch.branch_id = tbl_admin2.sales_branch','left');
	
	$this->db->join('sales_region as enq_sales_region','enq_sales_region.region_id = enquiry.sales_region','left');
	$this->db->join('sales_area as enq_sales_area','enq_sales_area.area_id = enquiry.sales_area','left');
	$this->db->join('branch as enq_branch','enq_branch.branch_id = enquiry.sales_branch','left');
		
	$this->db->join('(select fvalue as enq_dept,input,enq_no from extra_enquery where input=4452  group by extra_enquery.enq_no) as extra_dept','extra_dept.enq_no=enquiry.Enquery_id','left');
	$this->db->join('(select fvalue as enq_ccode,input,enq_no from extra_enquery where input=4453  group by extra_enquery.enq_no) as extra_dept1','extra_dept1.enq_no=enquiry.Enquery_id','left');
	$this->db->join('(select fvalue as enq_std,input,enq_no from extra_enquery where input=4454  group by extra_enquery.enq_no) as extra_dept2','extra_dept2.enq_no=enquiry.Enquery_id','left');
	$this->db->join('(select fvalue as enq_competitor,input,enq_no from extra_enquery where input=4478  group by extra_enquery.enq_no) as extra_dept3','extra_dept3.enq_no=enquiry.Enquery_id','left');
	$this->db->join('(select fvalue as enq_sensitive,input,enq_no from extra_enquery where input=4479  group by extra_enquery.enq_no) as extra_dept4','extra_dept4.enq_no=enquiry.Enquery_id','left');
	$this->db->join('(select fvalue as enq_web,input,enq_no from extra_enquery where input=4505  group by extra_enquery.enq_no) as extra_dept5','extra_dept5.enq_no=enquiry.Enquery_id','left');
	$this->db->join('(select fvalue as enq_pin,input,enq_no from extra_enquery where input=4536  group by extra_enquery.enq_no) as extra_dept6','extra_dept6.enq_no=enquiry.Enquery_id','left');
	
	$this->db->where('enquiry.drop_status','0');
$student_data = $this->db->get()->result_array();
	 
     // file creation 
     $file = fopen('php://output', 'w');
 
     $header = array("Source","Company group name","Client Name","Name","Email","Phone","Address","Process","Lead Stage"
	 ,"Create Date","Created By","create by department","Assign To","Assign to department","Data Source","Score","Remark","Employee region",
	 "Employee Area","Employee Branch","Sales region","Sales Area","Sales Branch","No Of Visit","EnquiryId","Sub Source","Department","Country Code",
	 "STD Code","Competitor Name","Customer Sensitive To","Website","Pincode"); 
     fputcsv($file, $header);
     foreach ($student_data as $key => $value)
     {		 
       fputcsv($file, $value);
     }
     fclose($file); 
     exit;
		
}
	
}
