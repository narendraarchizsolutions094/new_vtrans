<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Enq extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
 
		$this->load->model(
			array('enquiry_model', 'User_model', 'dash_model', 'common_model', 'report_model', 'Leads_Model','Ticket_Model')
		);
		$this->load->library('email');
		$this->load->library('pagination');
		$this->load->library('user_agent');
		$this->lang->load("activitylogmsg", "english");
		if (empty($this->session->user_id)) {
			redirect('login');
		}
	}
	// public function test(){
	// 	print_r($this->session->process);
	// }
	public function index($all = '')
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
		
		if(!empty($_GET) && !empty($_GET['desposition'])){
            $desp = $this->db->where('stg_id',$_GET['desposition'])->get('lead_stage')->row();        
			$data['desp'] = $desp;			
			$this->session->set_userdata('enquiry_filters_sess',array('stage'=>$_GET['desposition']));
		}		
		$data['title'] = display('enquiry_list');
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
		$data['lead_score'] = $this->Leads_Model->get_leadscore_list();
		//print_r($data['filterData']); exit();
		$data['content'] = $this->load->view('enquiry_n', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}	
	public function chk()
	{
		//print_r($this->session->enquiry_filters_sess);
	}

	public function enq_load_data()
	{
		$this->load->model('enquiry_datatable_model');
		$list = $this->enquiry_datatable_model->get_datatables();
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
		foreach ($list as $each) {
			$no++;
			$row = array();
			$row[] = "<input onclick='event.stopPropagation();'' type='checkbox' name='enquiry_id[]'' class='checkbox1' value=" . $each->enquiry_id . ">";
			if ($_POST['data_type'] == 1) {
				$url = base_url('enquiry/view/') . $each->enquiry_id;
			} else if ($_POST['data_type'] == 2) {
				$url = base_url('lead/lead_details/') . $each->enquiry_id;
			} else if ($_POST['data_type'] == 3) {
				$url = base_url('client/view/') . $each->enquiry_id;
			} else {
				$url = base_url('client/view/') . $each->enquiry_id . '?stage=' . $_POST['data_type'];
			}
			$row[] = '<a href="' . $url . '">' . $no/*$each->enquiry_id*/ . '</a>';
			if ($showall == true or in_array(1, $acolarr)) {
				$row[] = (!empty($each->lead_name)) ? ucwords($each->lead_name) : "NA";
			}
			if ($showall == true or in_array(16, $acolarr)) {
				$row[] = (!empty($each->subsource_name)) ? ucwords($each->subsource_name) : "NA";
			}
			if ($showall == true or in_array(2, $acolarr)) {
				$row[] = (!empty(trim($each->company))) ? ucwords($each->company) : "NA";
			}
			if ($showall == true or in_array(3, $acolarr)) {
				$row[] = '<a href="' . $url . '">' . $each->name_prefix . " " . $each->name . " " . $each->lastname . '</a>';
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
				$row[] = (!empty($each->score)) ? $each->score : "NA";
			}
			if ($showall == true or in_array(19, $acolarr)) {
				$row[] = (!empty($each->enquiry)) ? $each->enquiry : "NA";
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
			"recordsFiltered" => $c,
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
		$this->common_query_short_dashboard();
		$data['all_enquery_num'] = $this->db->count_all_results();


		$this->common_query_short_dashboard();
		$this->db->where('enquiry.drop_status>0');
		$data['all_drop_num'] = $this->db->count_all_results();


		$this->common_query_short_dashboard();
		$this->db->where(' enquiry.drop_status=0');
		$data['all_active_num']= $this->db->count_all_results();

		$this->common_query_short_dashboard();
		$this->db->where('enquiry.update_date is not NULL'); //anyhow updated
		$data['all_update_num']=$this->db->count_all_results();


		$this->common_query_short_dashboard();
	
		$this->db->where('enquiry.lead_stage',0);
		//now check empty dispositon
		$data['all_no_activity_num']=$this->db->count_all_results();

		$this->common_query_short_dashboard();
		//$date=date('Y-m-d');
		$this->db->where('enquiry.aasign_to is not NULL ');
		$data['all_assigned_num']=$this->db->count_all_results();

		$this->common_query_short_dashboard();
		//$date=date('Y-m-d');
		$this->db->where('enquiry.aasign_to is NULL ');
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
	public function common_query_short_dashboard()
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
	
		$where.="  enquiry.status=$data_type";
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
}
