<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Ticket extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model(array(
			'Ticket_Model', 'Client_Model', 'User_model', 'Leads_Model', 'Message_models'
		));
	}
	
	public function natureOfComplaintList()
	{
		if(user_role('523')){}

		$data['title'] = "Nature Of Complaint List";
		$data["tickets"] = $this->db->select('*')->from('tbl_nature_of_complaint')->where('comp_id', $this->session->companey_id)->get()->result();
		$data['content'] = $this->load->view('ticket/natureofcomplain-list', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}

	public function addNatureOfComplaint()
	{
		if(user_role('527')){}

		$data['title'] = "Add Nature Of Complaint";
		if (!empty($_POST)) {
			if (empty($this->input->post('complainid'))) {
				//$added = $this->db->select('id')->from('modulewise_right')->where('module_id',$this->input->post('module'))->get()->num_rows();
				$data = array(
					'title'         => $this->input->post('title'),
					'status'     	=> $this->input->post('status'),
					'comp_id'		=> $this->session->companey_id,
					'created_by'	=> $this->session->user_id,
					'created_at'    => date("Y-m-d H:i:s"),
					'updated_at'    => date("Y-m-d H:i:s"),
				);
				$this->db->insert('tbl_nature_of_complaint', $data);
				$this->session->set_flashdata('message', 'Data Added successfully');
				redirect('ticket/natureOfComplaintList');
			} else {
				$data = array(
					'title'          => $this->input->post('title'),
					'status'     => $this->input->post('status'),
					'comp_id'		=> $this->session->companey_id,
					'created_by'	=> $this->session->user_id,
					'created_at'    => date("Y-m-d H:i:s"),
					'updated_at'    => date("Y-m-d H:i:s"),
				);
				$this->db->where('id', $this->input->post('complainid'));
				$this->db->update('tbl_nature_of_complaint', $data);
				$this->session->set_flashdata('message', 'Updated successfully');
				redirect('ticket/natureOfComplaintList');
			}
		} else {
			$data['content'] = $this->load->view('ticket/addNatureof_complaint', $data, true);
			$this->load->view('layout/main_wrapper', $data);
		}
	}
	public function editNatureOfComplaint($id)
	{
		if(user_role('528')){}

		$data['title'] 	= "Edit Nature Of Complaint";
		$data['detail'] = $this->db->select('*')->from('tbl_nature_of_complaint')->where('id', $id)->get()->row();
		$data['content'] = $this->load->view('ticket/addNatureof_complaint', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function deleteNatureOfComplaint($id)
	{
		if(user_role('529')){}

		$this->db->where('id', $id);
		$this->db->delete('tbl_nature_of_complaint');
		$this->session->set_flashdata('message', 'Deleted successfully');
		redirect('ticket/natureOfComplaintList');
	}
	public function index()
	{
	
		$this->load->model('Datasource_model');
		$this->load->model('dash_model');
		$this->load->model('enquiry_model');
		$this->load->model('report_model');
		$this->load->model('Leads_Model');
		if (isset($_SESSION['ticket_filters_sess']))
			unset($_SESSION['ticket_filters_sess']);
		$data['sourse'] = $this->report_model->all_source();
		$data['title'] = "All Ticket";
		//$data["tickets"] = $this->Ticket_Model->getall();
		//print_r($data['tickets']); exit();
		$data['created_bylist'] = $this->User_model->read();
		$data['products'] = $this->dash_model->get_user_product_list();
		$data['prodcntry_list'] = $this->enquiry_model->get_user_productcntry_list();
		$data['problem'] = $this->Ticket_Model->get_sub_list();
		$data['stage'] =  $this->Leads_Model->stage_by_type(4);
		$data['sub_stage'] = $this->Leads_Model->find_description();
		$data['ticket_status'] = $this->Ticket_Model->ticket_status()->result();
		$x =$data['dfields'] = $this->enquiry_model->getformfield(2);
		// echo $this->db->last_query();
		// print_r($x);exit();
		//print_r($data["tickets"]);die;
		$data['issues'] = $this->Ticket_Model->get_issue_list();
		$data['filterData'] = $this->Ticket_Model->get_filterData(2);
		// print_r($data);
		$data['user_list'] = $this->User_model->companey_users();
		$data['content'] = $this->load->view('ticket/list-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}

	public function is_open_ticket($tracking_no){
		$comp_id = $this->session->companey_id;
		$this->db->where('tbl_ticket.ticket_status!=',3);
		$this->db->where('tbl_ticket.company',$comp_id);
		$this->db->where('tbl_ticket.tracking_no',$tracking_no);
		echo $this->db->get('tbl_ticket')->num_rows();
	}
	public function ticket_set_filters_session()
	{
		$this->session->set_userdata('ticket_filters_sess', $_POST);
		//print_r($_SESSION);
	}

	public function ticket_save_filter()
	{
		  $type=$this->uri->segment(3);
		 $user_id=$this->session->user_id;
		$comp_id=$this->session->companey_id;
		// print_r($this->input->post());
		// die();
		//check already exist or not
		$count=$this->db->where(array('user_id'=>$user_id,'comp_id'=>$comp_id,'type'=>$type))->count_all_results('tbl_filterdata');
		
		if($count==0){
			if($type==1){
				$filterData=[
					'from_created' =>$this->input->post('from_created'),
					'to_created' =>$this->input->post('to_created'),
					'source' =>$this->input->post('source'),
					'filter_checkbox' => $this->input->post('filter_checkbox'),
					'subsource' =>$this->input->post('subsource'),
					'email' =>$this->input->post('email'),
					'employee' =>$this->input->post('employee'), 
					'datasource' => $this->input->post('datasource'),
					'company' => $this->input->post('company'),
					'enq_product' => $this->input->post('enq_product'),
					'phone' => $this->input->post('phone'),
					'createdby' => $this->input->post('createdby'),
					'assign' =>$this->input->post('assign'),
					'address' =>$this->input->post('address'),
					'prodcntry' =>$this->input->post('prodcntry'),
					'state' =>$this->input->post('state'),
					'city' =>$this->input->post('city'),
					'stage' =>$this->input->post('stage'),
					'top_filter' =>$this->input->post('top_filter'),
					];
			$data=[
				'user_id'=>$user_id,
				'comp_id'=>$comp_id,
				'type'=>$type,
				'filter_data'=>json_encode($filterData)];
				$this->db->insert('tbl_filterdata',$data);
			echo'inserted';
			}else{
				
			$filterData=[
				'from_created' =>$this->input->post('from_created'),
				'to_created' =>$this->input->post('to_created'),
				'update_from_created' =>$this->input->post('update_from_created'),
				'update_to_created' =>$this->input->post('update_to_created'),
				'source' =>$this->input->post('source'),

				'problem' => $this->input->post('problem'),
				'priority' =>$this->input->post('priority'),
				'issue' =>$this->input->post('issue'),
				'createdby' =>$this->input->post('createdby'), 
				'assign' => $this->input->post('assign'),
				'assign_by' => $this->input->post('assign_by'),
				'prodcntry' => $this->input->post('prodcntry'),
				'stage' => $this->input->post('stage'),
				'sub_stage' => $this->input->post('sub_stage'),
				'ticket_status' =>$this->input->post('ticket_status'),
				];
		$data=[
			'user_id'=>$user_id,
			'comp_id'=>$comp_id,
			'type'=>$type,
			'filter_data'=>json_encode($filterData)];
			$this->db->insert('tbl_filterdata',$data);
			echo'inserted';
		}
			
		}else{
			if($type==1){
				$filterData=[
					'from_created' =>$this->input->post('from_created'),
					'to_created' =>$this->input->post('to_created'),
					'source' =>$this->input->post('source'),
					'filter_checkbox' => $this->input->post('filter_checkbox'),
					'subsource' =>$this->input->post('subsource'),
					'email' =>$this->input->post('email'),
					'employee' =>$this->input->post('employee'), 
					'datasource' => $this->input->post('datasource'),
					'company' => $this->input->post('company'),
					'enq_product' => $this->input->post('enq_product'),
					'phone' => $this->input->post('phone'),
					'createdby' => $this->input->post('createdby'),
					'assign' =>$this->input->post('assign'),
					'address' =>$this->input->post('address'),
					'prodcntry' =>$this->input->post('prodcntry'),
					'state' =>$this->input->post('state'),
					'city' =>$this->input->post('city'),
					'stage' =>$this->input->post('stage'),
					'top_filter' =>$this->input->post('top_filter'),
					];
			$data=[
				'user_id'=>$user_id,
				'comp_id'=>$comp_id,
				'type'=>$type,
				'filter_data'=>json_encode($filterData)];
				$this->db->where(array('user_id'=>$user_id,'comp_id'=>$comp_id,'type'=>$type))->update('tbl_filterdata',$data);
			echo'updated';
			}else{
			$filterData=['from_created' =>$this->input->post('from_created'),
				'to_created' =>$this->input->post('to_created'),
				'update_from_created' =>$this->input->post('update_from_created'),
				'update_to_created' =>$this->input->post('update_to_created'),
				'source' =>$this->input->post('source'),
				'problem' => $this->input->post('problem'),
				'priority' =>$this->input->post('priority'),
				'issue' =>$this->input->post('issue'),
				'createdby' =>$this->input->post('createdby'), 
				'assign' => $this->input->post('assign'),
				'assign_by' => $this->input->post('assign_by'),
				'prodcntry' => $this->input->post('prodcntry'),
				'stage' => $this->input->post('stage'),
				'sub_stage' => $this->input->post('sub_stage'),
				'ticket_status' =>$this->input->post('ticket_status'),
				];
		$data=[
			'user_id'=>$user_id,
			'comp_id'=>$comp_id,
			'type'=>$type,
			'filter_data'=>json_encode($filterData)];
			$this->db->where(array('user_id'=>$user_id,'comp_id'=>$comp_id,'type'=>$type))->update('tbl_filterdata',$data);
			echo'updated';
		}
			
		}

		
	}
	public function autofill()
	{
		if($post = $this->input->post())
		{
			$this->load->model('Enquiry_model');
			$res = $this->Ticket_Model->filterticket(array('tck.'.$post['find_by'] => $post['key']));
			$html = "";
			if($res) 
			{
				$html .= '<table class="table table-bordered">
				<tr>
				' . ($this->session->companey_id == 65 ? '<th>'.display('tracking_no').'</th>' : '') . '
				<th>Ticket Number</th>
				<th>Name</th>
				<th>Ticket Stage</th>
				<th>Status</th>
				<th>Created At</th>
				<th>Action</th>
				</tr>';
				foreach ($res as $row) {
					$status	=	$row->ticket_status_name??'Open';
					$html .= '<tr>
					' . ($this->session->companey_id == 65 ? '<td>' . $row->tracking_no . '</td>' : '') . '
					<td>' . $row->ticketno . '</td>
					<td>' . $row->name . '</td>
					<td>' . (!empty($row->lead_stage_name) ? $row->lead_stage_name : 'NA') . ' <small>' . (!empty($row->description) ? '<br>' . $row->description : '') . '</small></td>
					<td>' . $status . '</td>
					<td>' . date('d-m-Y <br> h:i A', strtotime($row->coml_date)) . '</td>
					<th><a href="' . base_url('ticket/view/' . $row->ticketno) . '"><button class="btn btn-small btn-primary">View</button></a></th>
					</tr>';
				}
				$html .= '</table>';
				$data = array(
				'status' => '1',				
				'html' => $html,
				);
				echo json_encode($data);
			} 
			else
			{
				echo json_encode(array('status' => '0', 'html' => '0'));
			}
		}
		else
		{
			echo json_encode(array('status' => '0', 'html' => '0'));
		}
	}
	public function report_ticket_load_data()
	{
		// $_POST = array('search'=>array('value'=>''),'length'=>10,'start'=>0);
		$this->load->model('report_Ticket_datatable_model');
		$this->load->model('enquiry_model');
		$res = $this->report_Ticket_datatable_model->getRows($_POST);

		//print_r($res); exit();
		$data  = array();
		$dfields = $this->enquiry_model->getformfield(2);
		$acolarr = array();
		$dacolarr = array();
		if (isset($_COOKIE["ticket_allowcols"])) {
			$showall = false;
			$acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));
		} else {
			$showall = true;
		}
		if (isset($_COOKIE["ticket_dallowcols"])) {
			$dshowall = false;
			$dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));
		} else {
			$dshowall = false;
		}
		$fieldval =  $this->enquiry_model->getfieldvalue(0,2); //2 for ticket
		foreach ($res as $point) {
			$sub = array();
			$sub[] = '';
			$sub[] = $point->id;
			if ($showall or in_array(1, $acolarr)) {
				$sub[] =$point->ticketno ;
			}
        if($this->session->comp_id==65)
        {
			if ($showall or in_array(15, $acolarr)) {
				$sub[] = $point->tracking_no == '' ? 'NA' : $point->tracking_no;
			}
		}
			if ($showall or in_array(7, $acolarr)) {
				$sub[] = $point->created_by_name ?? "NA";
			}
			if ($showall or in_array(9, $acolarr)) {
				$sub[] = $point->coml_date ?? 'NA';
			}
			if ($showall or in_array(18, $acolarr)) {
				$sub[] = $point->last_update ?? 'NA';
			}
			if ($showall or in_array(2, $acolarr)) {
				$sub[] = $point->org_name??($point->clientname ?? "NA");
			}
			if ($showall or in_array(3, $acolarr)) {
				$sub[] = $point->email ?? "NA";
			}
			if ($showall or in_array(4, $acolarr)) {
				if (user_access(220) && !empty($point->phone)) {
					$sub[] = $point->phone;
				} else {
					$sub[] = $point->phone ?? "NA";
				}
			}
			//$sub[] = $point->phone??"NA";
			if ($showall or in_array(5, $acolarr)) {
				$sub[] = $point->country_name ?? "NA";
			}
			if ($showall or in_array(6, $acolarr)) {
				$assign_to = $point->assign_to_name ?? "NA";
				$assign_to .=	!empty($point->last_esc)?' (<small style="color:red;">'.$point->last_esc.'</small>)':"";
				$sub[] = $assign_to;
			}
			if ($showall or in_array(17, $acolarr)) {
				$sub[] = $point->assigned_by_name ?? "NA";
			}
			
			if ($showall or in_array(8, $acolarr)) {
				$sub[] = '<span class="label label-' . ($point->priority == 1 ? 'success">Low' : ($point->priority == 2 ? 'warning">Medium' : ($point->priority == 3 ? 'danger">High' :'primary">NA'))) . '</span>';
			}
			
			
			if ($showall or in_array(19, $acolarr)) {
				$sub[] = $point->subject_title ?? 'NA';
			}
			if ($showall or in_array(10, $acolarr)) {
				$sub[] = $point->referred_name ?? 'NA';
			}
			if ($showall or in_array(11, $acolarr)) {
				$sub[] = $point->source_name ?? 'NA';
			}
			if ($showall or in_array(12, $acolarr)) {
				$sub[] = $point->lead_stage_name ?? 'NA';
			}
			if ($showall or in_array(13, $acolarr)) {
				$sub[] = $point->description ?? 'NA';
			}
			if ($showall or in_array(14, $acolarr)) {
				$sub[] = $point->message == '' ? 'NA' : $point->message;
			}
			
			if ($showall or in_array(16, $acolarr)) {
				$sub[] = $point->status_name == '' ? 'Open' : $point->status_name;
			}
			//dynamic fields
			$enqid = $point->id;
			if (!empty($dacolarr) and !empty($dfields)) {
				foreach ($dfields as $ind => $flds) {
					if (in_array($flds->input_id, $dacolarr)) {
						if($flds->input_type==8)
						{	
							if(!empty($fieldval[$enqid][$flds->input_id]->fvalue))
							{
								$x =explode('/', $fieldval[$enqid][$flds->input_id]->fvalue);
								$filename = !empty(end($x))?end($x):'NA';
								$sub[] = '<a href="'.$fieldval[$enqid][$flds->input_id]->fvalue.'" target="_blank">'.$filename.'</a>';
							}else
							{
								$sub[] = "NA";
							}
							
							
						}else
						{
						$sub[] = (!empty($fieldval[$enqid][$flds->input_id])) ? $fieldval[$enqid][$flds->input_id]->fvalue : "NA";
						}
					}
				}
			}
			$followup = empty($this->session->ticket_filters_sess['followup'])?0:1;
			
			if($followup)
			{	//echo 'yes followup'; exit();
				$sub[] = $point->tck_subject??'NA';
				$sub[] = $point->tck_stage??'NA';
				$sub[] = $point->tck_sub_stage??'NA';
				$sub[] = $point->tck_msg??'NA';
			}

			$data[] = $sub;
		}
		//print_r($res);
		$countAll = $this->report_Ticket_datatable_model->countAll();
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $countAll,
			"recordsFiltered" => $countAll,
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function ticket_load_data()
	{
		// $_POST = array('search'=>array('value'=>''),'length'=>10,'start'=>0);
		$this->load->model('Ticket_datatable_model');
		$this->load->model('enquiry_model');
		$res = $this->Ticket_datatable_model->getRows($_POST);

		//print_r($res); exit();
		$data  = array();
		$dfields = $this->enquiry_model->getformfield(2);
		$acolarr = array();
		$dacolarr = array();
		if (isset($_COOKIE["ticket_allowcols"])) {
			$showall = false;
			$acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));
		} else {
			$showall = true;
		}
		if (isset($_COOKIE["ticket_dallowcols"])) {
			$dshowall = false;
			$dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));
		} else {
			$dshowall = false;
		}
		$fieldval =  $this->enquiry_model->getfieldvalue(0,2); //2 for ticket
		foreach ($res as $point) {
			$sub = array();
			$sub[] = '<input type="checkbox" class="checkbox1" onclick="event.stopPropagation();" value="' . $point->id . '">';
			$sub[] = $point->id;
			if ($showall or in_array(1, $acolarr)) {
				$sub[] = '<a href="' . base_url('ticket/view/' . $point->ticketno) . '">' . $point->ticketno . '</a>';
			}
        if($this->session->companey_id==65)
        {
			if ($showall or in_array(15, $acolarr)) {
				$sub[] = $point->tracking_no == '' ? 'NA' : $point->tracking_no;
			}
		}
			if ($showall or in_array(7, $acolarr)) {
				$sub[] = $point->created_by_name ?? "NA";
			}
			if ($showall or in_array(9, $acolarr)) {
				$sub[] = $point->coml_date ?? 'NA';
			}
			if ($showall or in_array(18, $acolarr)) {
				$sub[] = $point->last_update ?? 'NA';
			}
			if ($showall or in_array(2, $acolarr)) {
				$sub[] = $point->org_name??($point->clientname ?? "NA");
			}
			if ($showall or in_array(3, $acolarr)) {
				$sub[] = $point->email ?? "NA";
			}
			if ($showall or in_array(4, $acolarr)) {
				if (user_access(220) && !empty($point->phone)) {
					$sub[] = "<a href='javascript:void(0)' onclick='send_parameters(".$point->phone.")'>" . $point->phone . " <button class='btn btn-xs btn-success'><i class='fa fa-phone' aria-hidden='true'></i></button></a>";
				} else {
					$sub[] = $point->phone ?? "NA";
				}
			}
			//$sub[] = $point->phone??"NA";
			if ($showall or in_array(5, $acolarr)) {
				$sub[] = $point->country_name ?? "NA";
			}
			if ($showall or in_array(6, $acolarr)) {
				$assign_to = $point->assign_to_name ?? "NA";
				$assign_to .=	!empty($point->last_esc)?' (<small style="color:red;">'.$point->last_esc.'</small>)':"";
				$sub[] = $assign_to;
			}
			if ($showall or in_array(17, $acolarr)) {
				$sub[] = $point->assigned_by_name ?? "NA";
			}
			
			if ($showall or in_array(8, $acolarr)) {
				$sub[] = '<span class="label label-' . ($point->priority == 1 ? 'success">Low' : ($point->priority == 2 ? 'warning">Medium' : ($point->priority == 3 ? 'danger">High' :'primary">NA'))) . '</span>';
			}
			
			
			if ($showall or in_array(19, $acolarr)) {
				$sub[] = $point->subject_title ?? 'NA';
			}
			if ($showall or in_array(10, $acolarr)) {
				$sub[] = $point->referred_name ?? 'NA';
			}
			if ($showall or in_array(11, $acolarr)) {
				$sub[] = $point->source_name ?? 'NA';
			}
			if ($showall or in_array(12, $acolarr)) {
				$sub[] = $point->lead_stage_name ?? 'NA';
			}
			if ($showall or in_array(13, $acolarr)) {
				$sub[] = $point->description ?? 'NA';
			}
			if ($showall or in_array(14, $acolarr)) {
				$sub[] = $point->message == '' ? 'NA' : $point->message;
			}
			
			if ($showall or in_array(16, $acolarr)) {
				$sub[] = $point->status_name == '' ? 'Open' : $point->status_name;
			}
			//dynamic fields
			$enqid = $point->id;
			if (!empty($dacolarr) and !empty($dfields)) {
				foreach ($dfields as $ind => $flds) {
					if (in_array($flds->input_id, $dacolarr)) {
						if($flds->input_type==8)
						{	
							if(!empty($fieldval[$enqid][$flds->input_id]->fvalue))
							{
								$x =explode('/', $fieldval[$enqid][$flds->input_id]->fvalue);
								$filename = !empty(end($x))?end($x):'NA';
								$sub[] = '<a href="'.$fieldval[$enqid][$flds->input_id]->fvalue.'" target="_blank">'.$filename.'</a>';
							}else
							{
								$sub[] = "NA";
							}
							
							
						}else
						{
						$sub[] = (!empty($fieldval[$enqid][$flds->input_id])) ? $fieldval[$enqid][$flds->input_id]->fvalue : "NA";
						}
					}
				}
			}
			$followup = empty($this->session->ticket_filters_sess['followup'])?0:1;
			
			if($followup)
			{	//echo 'yes followup'; exit();
				$sub[] = $point->tck_subject??'NA';
				$sub[] = $point->tck_stage??'NA';
				$sub[] = $point->tck_sub_stage??'NA';
				$sub[] = $point->tck_msg??'NA';
			}

			$data[] = $sub;
		}
		//print_r($res);
		$countAll = $this->Ticket_datatable_model->countAll();
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $countAll,
			"recordsFiltered" => $countAll,
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function view_tracking()
	{
		if($this->session->process[0] == 198){
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://www.vxpress.in/external-asset-erp/Tracking_API.php?docket_no='.$_POST['trackingno'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Cookie: PHPSESSID=a30762c8840f37bd19ffd33208bed43a'
			),
			));
			$response = curl_exec($curl);
			curl_close($curl);

			$response = json_decode($response,true);

			if(!empty($response)){
				$response = $response['NewDataSet'];
				$table = $response['Table']??array();
				$table1 = $response['Table1']??array();
				$table2 = $response['Table2']??array();
				$table3 = $response['Table3']??array();

				if(!empty($table)){
					echo "<table class='table table-bordered'>";
					$i = 1;
					foreach($table as $k=>$v){
						if($i==3){
							$i = 1;
						}		
						if($i==1){
							echo '<tr>';
						}			
						echo '<td>'.$k.'</td>';				
						echo '<td>'.$v.'</td>';				
						if($i==2){
							echo '</tr>';
						}
						$i++;
					}
					echo "</table>";
				}				
				if(!empty($table1)){
					echo "<table class='table table-bordered'>";
					$i = 1;
					foreach($table1 as $k=>$v){
						if($i==3){
							$i = 1;
						}		
						if($i==1){
							echo '<tr>';
						}			
						echo '<td>'.$k.'</td>';				
						if(is_array($v)){
							echo '<td>'.json_encode($v).'</td>';				
						}else{
							echo '<td>'.$v.'</td>';				
						}
						if($i==2){
							echo '</tr>';
						}
						$i++;
					}
					echo "</table>";
				}				
				if(!empty($table2)){
					echo "<table class='table table-bordered'>";
					$i = 1;
					foreach($table2 as $k=>$v){
						if($i==3){
							$i = 1;
						}		
						if($i==1){
							echo '<tr>';
						}			
						echo '<td>'.$k.'</td>';				
						if(is_array($v)){
							echo '<td>'.json_encode($v).'</td>';				
						}else{
							echo '<td>'.$v.'</td>';				
						}			
						if($i==2){
							echo '</tr>';
						}
						$i++;
					}
					echo "</table>";
				}				
				if(!empty($table3)){
					foreach($table3 as $key=>$value){
						echo "<table class='table table-bordered'>";
						$i = 1;
						foreach($value as $k=>$v){
							if($i==3){
								$i = 1;
							}		
							if($i==1){
								echo '<tr>';
							}			
							echo '<td>'.$k.'</td>';				
							if(is_array($v)){
								echo '<td>'.json_encode($v).'</td>';				
							}else{
								echo '<td>'.$v.'</td>';				
							}			
							if($i==2){
								echo '</tr>';
							}
							$i++;
						}
						echo "</table>";
					}
				}
			}
			
			?>

			<?php
		}else{
			if ($post = $this->input->post()) {
				$url = "https://thecrm360.com/new_crm/ticket/gc_vtrans_api/" . $post['trackingno'];
				
				if ($post['trackingno']) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);
					curl_close($ch);
					if ($output == '') {
						echo '0';
						exit();
					}
					$a = json_decode($output);
					
					$table  = empty($a->Table) ? '' : $a->Table;
					$table1 = empty($a->Table1) ? '' : $a->Table1;
					$table2 = empty($a->Table2) ? '' : $a->Table2;
					$table3 = empty($a->Table3) ? '' : $a->Table3;
					
					//  echo "<pre>";
					//  print_r($a);
					//  echo "</pre>";
					
					$extra  = empty($a->extra) ? '' : $a->extra;
					//print_r($extra);
					if (!empty($extra) ) {
						$gc_data = (array) $extra->gcDdata;
						?>
						<table class='table table-bordered'>
						<tr><th colspan="4" style="text-align:center;">GC Data
						</td></tr>
							<?php
							$i = 0; 
							foreach($gc_data as $key=>$value){
								if($i==2){
									$i = 0;
								}
								if($i == 0){
									echo '<tr>';
								}
								echo '<td>';
								echo '<b>'.$key.'</b>';
								echo '</td>';
								echo '<td>';
								echo $value;
								echo '</td>';
								if($i == 1){
									echo '</tr>';
								}										
								$i++;							
							}
							
					}
					if (!empty($a->Table)) {
						echo '<table class="table table-bordered">		        		        
					<tr><th>Delivery Location:</th><td  colspan="3">' . (empty($table->DeliveryLocation) ? '' : $table->DeliveryLocation) . '</td></tr>';				 
						if (sizeof((array)$table->EDD))
							echo ' <tr><th>EDD</th><td colspan="3">' . print_r($table->EDD) . '</td></tr>';

						echo '<tr><th>Delivery Date:</th><td>' . (empty($table->DeliveryDate) ? '' : $table->DeliveryDate) . '</td></tr>
					<tr><th>CRNO:</th><td>' . (empty($table->CRNO) ? '' : $table->CRNO) . '</td></tr>
					</table>';
					}
					if (!empty($a->Table1)) {
						echo '<center style="color:red; padding:0px 0px 0px 10px; cursor:pointer;" onclick="$(this).hide(),$(\'.hiddenTrackingDetails\').show();">View More</center>
					<div class="hiddenTrackingDetails" style="display:none;">
					<table class="table table-bordered">
						<tr><th colspan="4" style="text-align:center;">Branch Details</th></tr>
						<tr><th>Branch Name:</th><td>' . (empty($table1->Branch_Name) ? '' : $table1->Branch_Name) . '</td><th>Contact Person:</th><td>' . (empty($table1->Contact_Person) ? '' : $table1->Contact_Person) . '</td></tr>	
						<tr><th>Branch Address:</th><td colspan="3">' . (empty($table1->Address) ? '' : $table1->Address) . '</td></tr>
						<tr><th>City Name:</th><td>' . (empty($table1->City_name) ? '' : $table1->City_name) . '</td><th>Pincode:</th><td>' . (empty($table1->Pin_Code) ? '' : $table1->Pin_Code) . '</td></tr>
						<tr><th>STD Code:</th><td>' . (empty($table1->Std_Code) ? '' : $table1->Std_Code) . '</td><th>Mobile:</th><td>' . (empty($table1->mobileno) ? '' : $table1->mobileno) . '</td></tr>
						<tr><th>Phone No:</th><td>' . (empty($table1->phoneno) ? '' : $table1->phoneno) . '</td><th>Email:</th><td>' . (empty($table1->EMail_Id) ? '' : $table1->EMail_Id) . '</td></tr>
						<tr><th>Latitude:</th><td>' . (empty($table1->Latitude) ? '' : $table1->Latitude) . '</td><th>Longitude:</th><td>' . (empty($table1->Longitude) ? '' : $table1->Longitude) . '</td></tr>
					</table>';
					}
					if (!empty($a->Table2)) {
						echo '<table class="table table-bordered">
						<tr><th colspan="4" style="text-align:center;">Delivery Details</th></tr>
						<tr><th>Branch Name:</th><td>' . (empty($table2->Branch_Name) ? '' : $table2->Branch_Name) . '</td><th>Contact Person:</th><td>' . (empty($table2->Contact_Person) ? '' : $table2->Contact_Person) . '</td></tr>	
						<tr><th>Branch Address:</th><td colspan="3">' . (empty($table2->Address) ? '' : $table2->Address) . '</td></tr>
						<tr><th>City Name:</th><td>' . (empty($table2->City_name) ? '' : $table2->City_name) . '</td><th>Pincode:</th><td>' . (empty($table2->Pin_Code) ? '' : $table2->Pin_Code) . '</td></tr>
						<tr><th>STD Code:</th><td>' . (empty($table2->Std_Code) ? '' : $table2->Std_Code) . '</td><th>Mobile:</th><td>' . (empty($table2->mobileno) ? '' : $table2->mobileno) . '</td></tr>
						<tr><th>Phone No:</th><td>' . (empty($table2->phoneno) ? '' : $table2->phoneno) . '</td><th>Email:</th><td>' . (empty($table2->EMail_Id) ? '' : $table2->EMail_Id) . '</td></tr>
						<tr><th>Latitude:</th><td>' . (empty($table2->Latitude) ? '' : $table2->Latitude) . '</td><th>Longitude:</th><td>' . (empty($table2->Longitude) ? '' : $table2->Longitude) . '</td></tr>
					</table>';
					}
					if (!empty($table3)) {
						echo '<table class="table table-bordered">
					<tr><th colspan="5" style="text-align:center;">Status</th></tr>
					<tr><th>From</th><th>To</th><th>Dep. Date</th><th>Arr. Date</th><th>Status</th></tr>
					';
						if(!empty($table3->From_Station) || !empty($table3->From_Station) || !empty($table3->From_Station) || !empty($table3->From_Station)){
							$table3 = array($table3);						
						}
						foreach ($table3 as $res) {
							echo '<tr>
								<td>' . (!empty($res->From_Station) ? $res->From_Station : '') . '</td>
								<td>' . (!empty($res->To_Station) ? $res->To_Station : '') . '</td>
								<td>' . (!empty($res->Depature_Date) ? $res->Depature_Date : '') . '</td>
								<td>' . (!empty($res->Arrival_Date) ? $res->Arrival_Date : '') . '</td>
								<td>' . (!empty($res->Status_Name) ? $res->Status_Name : '') . '</td>
							</tr>';
						}
						echo '</table>';
					}
					echo '</div>
					';
				}
			}
		}
	}
	public function get_tracking()
	{
		if ($post = $this->input->post()) {
			$url = base_url('ticket/gc_vtrans_api/'.$post['trackingno'].'');
			
			if ($post['trackingno']) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);
				if ($output == '') {
					echo '0';
					exit();
				}
				echo $a = json_decode($output);
				
			}
		}
	}
	public function view1($tckt = "")
	{
		if (isset($_POST["reply"])) {
			$this->db->where('comp_id',$this->session->companey_id);
            $this->db->where('sys_para','usermail_in_cc');
            $this->db->where('type','COMPANY_SETTING');
            $cc_row = $this->db->get('sys_parameters')->row_array(); 
			$cc = '';
            if(!empty($cc_row))
            {
               $this->db->where('pk_i_admin_id',$this->session->user_id);
               $cc_user =  $this->db->get('tbl_admin')->row_array();
               if(!empty($cc_user))
                    $cc = $cc_user['s_user_email'];
            }
			$subject = $this->input->post("subjects", true);
			$message = $this->input->post("reply", true);
			$to = $this->input->post("email", true);
			$this->Message_models->send_email($to,$message,$subject,$this->session->companey_id,$cc);
			$this->Ticket_Model->saveconv();
			redirect(base_url("ticket/view/" . $tckt), "refresh");
		}
		if (isset($_POST["issue"])) {
			$this->Ticket_Model->updatestatus();
			redirect(base_url("ticket/view/" . $tckt), "refresh");
		}
		$data["ticket"] = $this->Ticket_Model->get($tckt);
		$data["conversion"] = $this->Ticket_Model->getconv($data["ticket"]->id);
		if (empty($data["ticket"])) {
			show_404();
		}
		$data['title'] = "View ";
		//$data["problem"] = $this->Ticket_Model->getissues();
		$datexpressiona['problem'] = $this->Ticket_Model->get_sub_list();
		$data['content'] = $this->load->view('ticket/view-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	function view($tckt = "")
	{
		$process_id = 0;
		
		$this->load->model('enquiry_model');
		$this->load->model('form_model');
		$data = array();
		$data["ticket"] = $this->Ticket_Model->get($tckt);
		//print_r($data['ticket']); exit();
    
		if (empty($data['ticket'])) {
			show_404();
		}
		//print_r($data['ticket']);exit();	
		$match = array(
			'ticket_no' => $data['ticket']->ticketno,
			'tck.client' => $data['ticket']->client,
			'tck.tracking_no' => $data['ticket']->tracking_no,
			'tck.phone' => $data['ticket']->phone, 
		);
		$data['related_tickets'] = $this->Ticket_Model->all_related_tickets($match);
		//print_r($data['related_tickets']); exit();
		$data["referred_type"] = $this->Leads_Model->get_referred_by();
		$data['all_description_lists']    =   $this->Leads_Model->find_description();
		//$data["clients"] = $this->Ticket_Model->getallclient();
		$data["problem_for"] = $this->Ticket_Model->getclient($data['ticket']->client);
		//print_r($data['problem_for']); exit();
		$data['ticket_status'] = $this->Ticket_Model->ticket_status()->result();
		$data["product"] = $this->Ticket_Model->getproduct();
		//print_r($data['product']); exit();
		$data["conversion"] = $this->Ticket_Model->getconv($data["ticket"]->id);
		// print_r($data['conversion']); exit(); 
		$data['problem'] = $this->Ticket_Model->get_sub_list();
		$data['prodcntry_list'] = $this->enquiry_model->get_user_productcntry_list();
		//print_r($data['prodcntry_list']); exit();
		$data['issues'] = $this->Ticket_Model->get_issue_list();
		if (!$data["ticket"]->client)
			//show_404();
		$this->load->model('enquiry_model');
		//$data['enquiry'] = $this->enquiry_model->enquiry_by_id($data["ticket"]->client);
		$data['ticket_stages'] = $this->Leads_Model->stage_by_type(4); // 4 = ticket
		$data['leadsource'] = $this->Leads_Model->get_leadsource_list();
		//print_r($data['leadsource']);	
		//print_r($data['ticket_stages']); exit();
		$this->load->model(array('form_model', 'dash_model', 'location_model'));
		$this->load->helper('custom_form_helper');
		$process_id = 0;
		if($data['ticket']->process_id!=0)
			$process_id = $data['ticket']->process_id;
		else
		{
			$enq=$this->enquiry_model->getEnquiry(array('enquiry_id'=>$data['ticket']->client));
			if($enq->num_rows())
			{
				$process_id = $enq->row()->product_id; // Process id
			}
		}
		$data['tab_list'] = $this->form_model->get_tabs_list($this->session->companey_id,$process_id,2); //2 for Ticket Tab 
       // print_r($data['tab_list']); exit;
		
		$primary_tab=0;
		$tabs = $this->db->select('id')
						->where(array('form_for'=>2,'primary_tab'=>1))
						->get('forms')
						->row();
        if($tabs)
            $primary_tab = $tabs->id;
        $data['primary_tab'] = $primary_tab;
		$data['process_id'] =$process_id;
		// $chk_access = $this->db->where('comp_id',$this->session->companey_id)->count_all_results('email_integration');
		// $data['mail_alert_access']= $chk_access;

		$content	 =	$this->load->view('ticket/ticket_disposition', $data, true);
		$content    .=  $this->load->view('ticket/ticket_details', $data, true);
		$content    .=  $this->load->view('ticket/timeline', $data, true);
		$data['content'] = $content;
		$this->load->view('layout/main_wrapper', $data);
	}
	public function ticket_status($rule_ticket_status=0){
		$ticket_status = $this->Ticket_Model->ticket_status()->result();
		if(!empty($ticket_status)){
			foreach($ticket_status as $status)
			{ ?>
<option value="<?=$status->id?>" <?=($status->id==$rule_ticket_status)?'selected':''?>>
    <?php echo $status->status_name; ?></option>
<?php 
			}
		}
	}
	public function update_ticket_tab($tck_id)
	{
		$this->load->library('user_agent');
		
		$tid    =   $this->input->post('tid');
        $form_type    =   $this->input->post('form_type');
        $enqarr = $this->db->select('*')->where('id',$tck_id)->get('tbl_ticket')->row();
        $en_comments = $enqarr->ticketno;
        $type = $enqarr->status;
      
       $comment_id = $this->Ticket_Model->saveconv($tck_id,'Ticket Updated','', $enqarr->client,$this->session->user_id);
       //echo $comment_id; exit();
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
                            $file_path = base_url().'uploads/ticket_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];
                            $biarr = array( 
                                            "enq_no"  => $en_comments,
                                            "input"   => $val,
                                            "parent"  => $tck_id, 
                                            "fvalue"  => $file_path,
                                            "cmp_no"  => $this->session->companey_id,
                                            "comment_id" => $comment_id
                                        );
                            $this->db->where('enq_no',$en_comments);        
                            $this->db->where('input',$val);        
                            $this->db->where('parent',$tck_id);
                            if($this->db->get('ticket_dynamic_data')->num_rows())
                            {
                                if ($form_type == 1) {
                                    $this->db->insert('ticket_dynamic_data',$biarr);                                       
                                }else{                                    
                                    $this->db->where('enq_no',$en_comments);        
                                    $this->db->where('input',$val);        
                                    $this->db->where('parent',$tck_id);
                                    $this->db->set('fvalue',$file_path);
                                    $this->db->set('comment_id',$comment_id);
                                    $this->db->update('ticket_dynamic_data');
                                }
                            }else{
                                $this->db->insert('ticket_dynamic_data',$biarr);	
                            }         
                        }
                        $file_count++;          
                    }else{
                        $biarr = array( "enq_no"  => $en_comments,
                                      "input"   => $val,
                                      "parent"  => $tck_id, 
                                      "fvalue"  => $enqinfo[$val],
                                      "cmp_no"  => $this->session->companey_id,
                                      "comment_id" => $comment_id
                                     );                                 
                        $this->db->where('enq_no',$en_comments);        
                        $this->db->where('input',$val);        
                        $this->db->where('parent',$tck_id);
                        if($this->db->get('ticket_dynamic_data')->num_rows()){  
                            if ($form_type == 1) {
                                $this->db->insert('ticket_dynamic_data',$biarr);                                       
                            }else{                                                              
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$tck_id);
                                $this->db->set('fvalue',$enqinfo[$val]);
                                $this->db->set('comment_id',$comment_id);
                                $this->db->update('ticket_dynamic_data');
                            }
                        }else{
                            $this->db->insert('ticket_dynamic_data',$biarr);
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
	public function update_dynamic_query($tck_id)
	{
		$this->load->library('user_agent');
		$cmnt_id = $this->input->post('cmnt_id');
		// $tid    =   $this->input->post('tid');
  //       $form_type    =   $this->input->post('form_type');
        $enqarr = $this->db->select('*')->where('id',$tck_id)->get('tbl_ticket')->row();
        $en_comments = $enqarr->ticketno;
        $type = $enqarr->status;
        // if($type == 1){                 
        //     $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('enquery_updated'), $en_comments);                    
        // }else if($type == 2){                   
        //      $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('lead_updated'), $en_comments);                   
        // }else if($type == 3){
        //      $comment_id = $this->Leads_Model->add_comment_for_events($this->lang->line('client_updated'), $en_comments);
        // }	
        
       $comment_id = $this->Ticket_Model->saveconv($tck_id,'Details Updated','', $enqarr->client,$this->session->user_id);
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
                            $file_path = base_url().'uploads/ticket_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];
                                                
                                    $this->db->where('enq_no',$en_comments);    
                                    $this->db->where('comment_id',$cmnt_id);    
                                    $this->db->where('input',$val);        
                                    $this->db->where('parent',$tck_id);
                                    $this->db->set('fvalue',$file_path);
                                    $this->db->update('ticket_dynamic_data');
                             
                        }
                        $file_count++;          
                    }
                    else
                    {
                        
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$tck_id);
                                $this->db->where('comment_id',$cmnt_id); 
                                $this->db->set('fvalue',$enqinfo[$val]);
                                $this->db->update('ticket_dynamic_data');
                          
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
        $upload_path    =   "./uploads/ticket_documents/";
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
    public function delete_query_data($cmnt_id,$tckno)
    {
		$this->db->where(array('comment_id'=>$cmnt_id,'enq_no'=>$tckno))->delete('ticket_dynamic_data');
		redirect($this->agent->referrer());
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
    			$enquiry_id = $post['ticket'];
    			$tabname= $post['tabname'];
    			$cmnt_id = $post['cmnt_id'];
    			$ci->load->model('enquiry_model');
        		$ci->load->model('Ticket_Model');
        		$ci->load->model('location_model');
    			
		        $data['tid'] = $tid;
				$data['comp_id'] = $comp_id;
				$data['cmnt_id'] = $cmnt_id;
		 		$ci->db->select('*,input_types.title as input_type_title'); 		
		 		$ci->db->where('tbl_input.form_id',$tid);  			
		 		$ci->db->where('tbl_input.company_id',$comp_id);  			
		 		$ci->db->join('input_types','input_types.id=tbl_input.input_type');  			
		 		$data['form_fields']	= $ci->db->get('tbl_input')->result_array();
		 		$ticketno = $enquiry_id;
	             $data['basic_fields'] =array();
	             $data['details']=$ci->Ticket_Model->get($ticketno);
	             //print_r($data['details']); exit();
	             //1 for ticket form
	            $data['dynamic_field']=$ci->enquiry_model->get_dyn_fld_by_query($cmnt_id,$ticketno,$tid,2);
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
		        $data['state_list'] 	= $ci->location_model->estate_list();
		        $data['city_list'] 			= $ci->location_model->ecity_list();
		        $data['all_country_list'] 	= $ci->location_model->country();
		        $data['name_prefix'] 		= $ci->enquiry_model->name_prefix_list();
		        $data['tabname'] = $tabname;       
        	
        		$ci->load->view('ticket/edit_dynamic_query_data',$data);
    		}
    		else if($post['task']=='save')
    		{
    		}
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

	public function update_date(){
		$this->db->where('company',65);
		//$this->db->limit(1,1000);
		$res = $this->db->get('tbl_ticket')->result_array();

		if(!empty($res)){
			foreach($res as $key=>$r){
				$this->db->where('tck_id',$r['id']);
				$this->db->order_by('id','desc');
				$this->db->limit(1);
				$last_conv	=	$this->db->get('tbl_ticket_conv')->row_array();
				
				
				if(!empty($last_conv)){
					$this->db->where('id',$r['id']);					
					$this->db->set('last_update',$last_conv['send_date']);
					$this->db->update('tbl_ticket');				}
			}
		}


	}
	public function ticket_disposition($ticketno)
	{
		//print_r($_POST); exit();
		$lead_stage	=	$this->input->post('lead_stage');
		$stage_desc	=	$this->input->post('lead_description');
		$stage_remark	=	$this->input->post('conversation');
		$client	=	$this->input->post('client');
		
		$stage_date = date("d-m-Y", strtotime($this->input->post('c_date')));
		$stage_time = date("H:i:s", strtotime($this->input->post('c_time')));
		$user_id = $this->session->user_id;
		$this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
		$this->Ticket_Model->saveconv($ticketno, 'Stage Updated', $stage_remark, $client, $user_id, $lead_stage, $stage_desc);

		$contact_person = '';
		$mobileno = $this->input->post('mobile');
		$email = $this->input->post('email');
		$designation = '';
		$enq_code = $this->input->post('ticketno');
		$notification_id = $this->input->post('dis_notification_id');
		$dis_subject = '';
		$this->Leads_Model->add_comment_for_events_popup($stage_remark, $stage_date, $contact_person, $mobileno, $email, $designation, $stage_time, $enq_code, $notification_id, $dis_subject, 2,2);
		$ticketno	=	$this->input->post('ticketno');
		$this->load->model('rule_model');
		$this->rule_model->execute_rules($ticketno, array(8,10));

		if(user_access('319') && !empty($this->input->post('mail_alert')))
		{
				$this->load->model('Message_models');
				$tckdata = $this->db->where('ticketno',$ticketno)->get('tbl_ticket')->row();
				$subject = ticket_subject($tckdata->ticketno,$tckdata->message);

				$sub_stage_name = $stage_name = $username= '';

				//fetching names
				$sub_stage_name = $this->db->where('id',$stage_desc)->get('lead_description')->row();
				if(!empty($sub_stage_name))
					$sub_stage_name = $sub_stage_name->description;

				$stage_name = $this->db->where('stg_id',$lead_stage)->get('lead_stage')->row();
				if(!empty($stage_name))
					$stage_name = $stage_name->lead_stage_name;

				$username= $this->db->where('pk_i_admin_id',$this->session->user_id)->get('tbl_admin')->row();

				if(!empty($username))
					$username = $username->s_display_name;


				//===================
				$message = "<b><u>Your Ticket has been Updated.</u><b><br>
							<p><b>Stage:</b> ".$stage_name." <p>
							<p><b>Sub Stage:</b> ".$sub_stage_name." <p>";

				if(!empty($stage_remark))
				$message.="<p><b>Remark:</b> ".$stage_remark." <p>";
						
				$message.="<p style='font-size:12px; font-style:italic;'><b>Updated By:</b> ".$username." <p>";
				//echo $message;exit();
				$to = $tckdata->email;
				$_mail_ = $this->Message_models->send_email($to,$subject,$message,$this->session->companey_id);
				if($_mail_)
				{
					$this->session->set_flashdata('message','Email Notification Sent Successfully');
				}
				else
					$this->session->set_flashdata('message','Unable to send notification.');
		}

		redirect('ticket/view/' . $ticketno);
	}
	public function assign_tickets()
	{
		if (user_role('313') == true) {}
		$assign_to_date = date('Y-m-d H:i:s');
		if (!empty($_POST))
		{
			$move_enquiry = $this->input->post('tickets');
			$assign_employee = $this->input->post('epid');
			$notification_data = array();
			$assign_data = array();
			if (!empty($move_enquiry)) {
				foreach ($move_enquiry as $key)
				{
					$this->db->set('assign_to', $assign_employee);
					$this->db->set('assigned_by', $this->session->user_id);
					$this->db->set('assigned_to_date', $assign_to_date);
					$this->db->where('id', $key);
					$this->db->update('tbl_ticket');
				$ticket = 	$this->db->select('*')
											->where('id',$key)
											->get('tbl_ticket')
											->row();
					$this->db->set('comp_id',$this->session->companey_id);
					$this->db->set('query_id',$ticket->ticketno);
					$this->db->set('noti_read',0);
					$this->db->set('contact_person',$ticket->name);
					$this->db->set('mobile',$ticket->phone);
					$this->db->set('email',$ticket->email);	
					$this->db->set('task_date',date('d-m-Y'));
					$this->db->set('task_time',date('H:i:s'));
					$this->db->set('create_by',$this->session->user_id);
					$this->db->set('task_type','17');
					$this->db->set('subject','Ticket Assigned');
					$this->db->insert('query_response');
					$insarr = array(
					"tck_id" 	=> $key,
					"parent" 	=> 0,
					'comp_id'	=> $this->session->companey_id,
					"subj"   	=> "Ticked Assigned",
					"msg"    	=> '',
					"attacment" => "",
					"status"  	=> 0,
					"send_date" =>	date("Y-m-d H:i:s"),
					"client"   	=> 0,
					"added_by" 	=> $this->session->user_id,
					);
					$this->db->insert('tbl_ticket_conv',$insarr);
				}
				echo display('save_successfully');
			} 
			else 
			{
				echo display('please_try_again');
			}
		}
	}
	public function update_ticket($tckt = "")
	{
		if (user_role('311') == true) {}
		//print_r($_POST); exit();
		if (isset($_POST["ticketno"])) {
			//echo $_POST['']
			//$_POST['relatedto'] = $_POST['issue'];
			$this->Ticket_Model->updatestatus();
			//echo $this->session->flashdata('message'); exit();
			//redirect(base_url("ticket/view/".$tckt), "refresh");
			$res = $this->Ticket_Model->save($this->session->companey_id, $this->session->user_id);
	
		if(isset($_POST['inputfieldno']))
		{	//echo 'text'; exit();
			$tic = $this->db->select('id')->where('ticketno',$tckt)->get('tbl_ticket')->row();
			$this->update_ticket_tab($tic->id);
		}
		
	
		if ($res) {
			$this->session->set_flashdata('message', 'Successfully Updated ticket');
			redirect(base_url('ticket/view/' . $tckt), "refresh");
			//echo'in';
		}
		}
		echo 'out';
	}
	public function edit($tckt = "")
	{
		if (user_role('311') == true) {}
		if (isset($_POST["ticketno"])) {
			$res = $this->Ticket_Model->save($this->session->companey_id, $this->session->user_id);
			if ($res) {
				$this->session->set_flashdata('message', 'Successfully Updated ticket');
				redirect(base_url('ticket/edit/' . $tckt), "refresh");
			}
		}
		$data["ticket"] = $this->Ticket_Model->get($tckt);
		if (empty($data["ticket"])) {
			show_404();
		}
		$data['title'] = "Edit ";
		$data["conversion"] = $this->Ticket_Model->getconv($data["ticket"]->id);
		$data["clients"] = $this->Ticket_Model->getallclient();
		$data["product"] = $this->Ticket_Model->getproduct();
		//$data["problem"] = $this->Ticket_Model->getissues();
		$data['problem'] = $this->Ticket_Model->get_sub_list();
		$data['issues'] = $this->Ticket_Model->get_issue_list();
		$data['source'] = $this->Leads_Model->get_leadsource_list();
		//$data["source"] = $this->Ticket_Model->getSource($this->session->companey_id);//getting ticket source list
		$data['content'] = $this->load->view('ticket/edit-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function delete_ticket()
	{
		if (user_role('312') == true) {}
		foreach ($this->input->post('ticket_list') as $key => $value) {
			$this->db->where('id', $value);
			$this->db->delete('tbl_ticket');
			$ret = $this->db->affected_rows();
			$this->db->where('tck_id', $value);
			$this->db->delete('tbl_ticket_conv');
		}
	}
	function tdelete()
	{
		$cnt = $this->input->post("content", true);
		$this->db->where('id', $cnt);
		$this->db->delete('tbl_ticket');
		$ret = $this->db->affected_rows();
		$this->db->where('tck_id', $cnt);
		$this->db->delete('tbl_ticket_conv');
		if ($ret) {
			$stsarr = array(
				"status" => "success",
				"message" => "Successfully deleted"
			);
		} else {
			$stsarr = array(
				"status" => "failed",
				"message" => "Failed to delete"
			);
		}
		die(json_encode($stsarr));
	}
	public function filter()
	{
		$pst = $this->input->post("top_filter", true);
		if ($pst == "created_today") {
			$where =  " tck.send_date = cast((now()) as date)";
		} else if ($pst == "updated_today") {
			$where =  " tck.last_update	 = cast((now()) as date)";
		} else if ($pst == "droped") {
			$where = array(" tck.status" => 3);
		} else if ($pst == "unread") {
			$where  =  "tck.status = 0";
		} else if ($pst == "all") {
			$where = false;
		} else {
			$where = false;
		}
		$tickets =  $this->Ticket_Model->filterticket($where);
		if (!empty($tickets)) {
			foreach ($tickets as $ind => $tck) {
?>
<tr>
    <td><?php echo $ind + 1; ?></td>
    <td><?php echo $tck->ticketno; ?></td>
    <td><?php echo $tck->clientname; ?></td>
    <td><?php echo $tck->email; ?></td>
    <td><?php echo $tck->phone; ?></td>
    <td><?php echo $tck->product_name; ?></td>
    <td><?php echo $tck->category; ?></td>
    <td><?php
		if ($tck->priority == 1)
		{?><span class="badge badge-info">Low</span><?php
		} else if ($tck->priority == 2) {?>
			<span class="badge badge-warning">Medium</span>
			<?php
		} else if ($tck->priority == 2)
		{?><span class="badge badge-danger">High</span><?php
		}?></td>
		<td><?php echo $tck->message; ?></td>
		<td><?php echo date("d, M, Y", strtotime($tck->send_date)); ?></td>
    <td style="min-width:125px;"><a class="btn  btn-success"
            href="<?php echo base_url("ticket/view/" . $tck->ticketno) ?>"><i class="fa fa-eye" aria-hidden="true"></i>
            <a class="btn  btn-default" href="<?php echo base_url("ticket/edit/" . $tck->ticketno) ?>"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
            <a class="btn  btn-danger delete-ticket" data-ticket="<?php echo $tck->id; ?>"
                href="<?php echo base_url("ticket/tdelete") ?>"><i class="fa fa-trash-o"></i></a>
    </td>
</tr>
<?php
			}
		}
	}
	public function getmail()
	{
		$hostname =  '{imappro.zoho.com:993/imap/ssl}INBOX';
		// $username = 'shahnawazbx@gmail.com';
		// $password = 'BuX@76543210';
		$username = 'suraj@archiztechnologies.com';
		$password = 'Archiz321';
		echo "Hello 1";
		/* try to connect */
		$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
		// echo "<pre>";
		// print_r(imap_errors());
		// echo "</pre>";
		// echo imap_last_error();
		// echo "Hello 2";
		/* grab emails */
		$emails = imap_search($inbox, 'ALL');
		/* if emails are returned, cycle through each... */
		if ($emails) {
			/* begin output var */
			$output = '';
			/* put the newest emails on top */
			rsort($emails);
			/* for every email... */
			foreach ($emails as $ind => $email_number) {
				if ($ind > 0) break;
				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox, $email_number, 0);
				$message  = imap_fetchbody($inbox, $email_number, 1);
				// echo "<pre>";
				// print_r($message);
				// echo "</pre>";
				$output.="<div style='border:1px solid black; padding:10px;'>";
				$output.=" Subject: ".$overview[0]->subject."<br>";
				$output.=" Date: ".$overview[0]->date."<br>";
				$output .= 'Name:  ' . $overview[0]->from . '</br>';

				$output .= 'MSG ID:  ' . $overview[0]->message_id . '</br>';
				// $output .= 'Reference: '.$overview[0]->references.'<br>';
				$output .= 'UID: '.$overview[0]->uid.'<br>';
				$output.="<hr>".quoted_printable_decode($message).'<br>';
				$output.="</div>";
			}
			echo $output;
		}
		imap_close($inbox);
	}

	public function auto_add_config()
	{	

		user_role('318');

		$data['title'] = "Ticket by Mail ";
		$company_id = $this->session->companey_id??0;
		$process = $this->session->process[0]??0;

		$this->load->model(array('User_model','dash_model','Apiintegration_Model'));
		$res = $data['row'] = $this->Ticket_Model->get_auto_mail_config();
		//print_r($res);exit();
		 //$this->db->where('comp_id',$this->session->companey_id)->get('ticket_email_config')->row();
		$data['tmp_list']  = $this->db->where('comp_id',$company_id)
									->where('FIND_IN_SET('.$process.',process) > 0 ')
									->where('FIND_IN_SET(3,temp_for) > 0')
									->get('api_templates')->result();
		//echo $this->db->last_query();exit();
		$data['user_list'] = $this->User_model->user_list();
		$data['process_list'] = $this->dash_model->all_process_list();
		//print_r($res);exit();
		if($this->input->post())
		{
			$next_hit = date('Y-m-d H:i:s',(time() + (60*$this->input->post('fetch_time'))));
			$data = array('hostname'=>$this->input->post('hostname'),
							'username' => $this->input->post('username'),
							'password' => $this->input->post('password'),
							'comp_id'=> $this->session->companey_id,
							'belongs_to'=>$this->input->post('belongs_to'),
							'process_id'=>$this->session->process[0],
							'template'=>$this->input->post('temp_id'),
							'fetch_time'=>$this->input->post('fetch_time'),
							'status'=>$this->input->post('status'),
							'next_hit'=>$next_hit,
							);
			if(!empty($res))
			{
				$this->db->where('comp_id',$company_id)->update('ticket_email_config',$data);
			}else
			{
				$this->db->insert('ticket_email_config',$data);
			}
			
			$this->session->set_flashdata('SUCCESSMSG','Email Configuration Saved Successfully.');
			redirect(base_url('ticket/'.__FUNCTION__));
		}

		$this->db->where('comp_id', $this->session->companey_id);
        $this->db->where('status', 1);
        $data['email_integration'] = $this->db->count_all_results('email_integration');

		$data['content'] = $this->load->view('ticket/ticket-mail-config', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}

	public function tracking_no_check($tn){
		$comp_id = $this->session->companey_id;
		$this->db->where('company',$comp_id);
		$this->db->where('tracking_no',$tn);
		$this->db->where('ticket_status!=',3);
		if($this->db->get('tbl_ticket')->num_rows()){
			$this->form_validation->set_message('tracking_no_check', 'Ticket with this '.display('tracking_no').' is already open.');
			return false;
		}else{
			return true;
		}
	}
	public function add()
	{
		if (user_role('310') == true) {}
		$this->load->model('Enquiry_model');
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('phone','Mobile No','required');
		// $this->form_validation->set_rules('email','Email','required');
		if($this->session->companey_id == 65 && $this->input->post('tracking_no')){
			$this->form_validation->set_rules('tracking_no', display('tracking_no'), 'required|callback_tracking_no_check', array('tracking_no_check' => 'Ticket with this '.display('tracking_no').' is already open.'));
		}
		if ($this->form_validation->run()==TRUE) {
			$_POST['relatedto'] =  !empty($_POST['relatedto'])?$_POST['relatedto']:'';
			if(!empty($_POST['mail_alert']))
			{
			$_POST['remark'] =  !empty($_POST['remark'])?$_POST['remark']:'Problem Not Defined';
			}
			

			$res = $this->Ticket_Model->save($this->session->companey_id, $this->session->user_id);
			
			if ($res) 
			{
				$tck_id =  $this->db->select('id')
								->where('ticketno',$res)
								->get('tbl_ticket')->row()->id;
				
				$comment_id = $this->db->select('id')
								->where('tck_id',$tck_id)
								->get('tbl_ticket_conv')->row()->id;
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
                            $file_path = base_url().'uploads/ticket_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];
                            $biarr = array( 
                                            "enq_no"  => $res,
                                            "input"   => $val,
                                            "parent"  => $tck_id, 
                                            "fvalue"  => $file_path,
                                            "cmp_no"  => $this->session->companey_id,
                                            "comment_id" => $comment_id
                                        );         
                                $this->db->insert('ticket_dynamic_data',$biarr);	        
                        }
                        $file_count++;          
                    }else{
                        $biarr = array( "enq_no"  => $res,
                                      "input"   => $val,
                                      "parent"  => $tck_id, 
                                      "fvalue"  => $enqinfo[$ind]??'',
                                      "cmp_no"  => $this->session->companey_id,
                                      "comment_id" => $comment_id
                                     );                                 
                       
                            $this->db->insert('ticket_dynamic_data',$biarr);
                    }                                      
                } //foreach loop end               
            }    
				$this->load->model('rule_model');
				$this->rule_model->execute_rules($res, array(9));
				$response="Ticket Added Successfully";
				if(user_access('319'))
				{
				$mail_config = $this->Ticket_Model->get_auto_mail_config();
				if(!empty($mail_config) && !empty($_POST['email']))
				{	
					if(empty($_POST['remark']))
					{
						if(!empty($_POST['relatedto']))
						{
							$tck_subj = $this->db->where('id',$_POST['relatedto'])->get('tbl_ticket_subject')->row();
							if(!empty($tck_subj))
								$subj = $tck_subj->subject_title;

						}
					}
					else
						$subj = $_POST['remark'];

					$mail_subject= ticket_subject($res,$subj);

					$template = '';
					$tmp = $this->db->where('temp_id',$mail_config->template)
									->where('comp_id',$this->session->companey_id)
									->where('FIND_IN_SET(3,temp_for)>0')
									->get('api_templates')->row();
					if(!empty($tmp))
					$template= $tmp->template_content;

					$mail_msg = $template;
					$search  = array('@ticketno'=>$res,
									'@sender'=>$_POST['name'],
									'@subject'=>$_POST['remark']	,
								);
					
					foreach ($search as $key => $value) {
					$mail_msg =	str_replace($key, $value, $mail_msg);
					} 

					if($this->Message_models->send_email($_POST['email'],$mail_subject,$mail_msg))
					{
						$response .= '<br>Email Send Successfully.<br>';
					}

				}
				else
				{
					if(empty($mail_config))
						$response.='<font color="red">Unable to send mail because SMTP details are not configured.</font>';
				}
				}
				$this->session->set_flashdata('message', $response);
				redirect(base_url('ticket/view/' . $res));
			}
		}
		$process = $this->session->userdata('process');
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
		$data['title'] = "Add Ticket";
		$primary_tab=0;
		$tabs = $this->db->select('id')
						->where(array('form_for'=>2,'primary_tab'=>1))
						->get('forms')
						->row();
        if($tabs)
            $primary_tab = $tabs->id;
        $data['primary_tab'] = $primary_tab;
		//$data["source"] = $this->Ticket_Model->getSource($this->session->companey_id);//getting ticket source list
        // $chk_access = $this->db->where('comp_id',$this->session->companey_id)->count_all_results('email_integration');
        // $data['mail_alert_access']= $chk_access;

		$data['content'] = $this->load->view('ticket/add-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function view_previous_ticket()
	{
		if ($post = $this->input->post()) {
			$no = $post['tracking_no'];
			$res = $this->Ticket_Model->filterticket(array('tracking_no' => $no));
			if ($res) {
				echo '<table class="table table-bordered">
				<tr>
				' . ($this->session->companey_id == 65 ? '<th>'.display('tracking_no').'</th>' : '') . '
				<th>Ticket Number</th>
				<th>Name</th>
				<th>Ticket Stage</th>
				<th>Status</th>
				<th>Created At</th>
				<th>Action</th>
				</tr>';
				foreach ($res as $row)
				{
					$status =	$row->ticket_status_name??'Open';
				echo'<tr>
					'.($this->session->companey_id==65?'<td>'.(empty($row->tracking_no)?'NA':$row->tracking_no).'</td>':'').'
					<td>'.$row->ticketno.'</td>
					<td>'.$row->name.'</td>
					<td>'.(!empty($row->lead_stage_name)?$row->lead_stage_name:'NA').' <small>'.(!empty($row->description)?'<br>'.$row->description:'').'</small></td>
					<td>'.$status.'</td>
					<td>'.date('d-m-Y <br> h:i A',strtotime($row->coml_date)).'</td>
					<th><a href="'.base_url('ticket/view/'.$row->ticketno).'"><button class="btn btn-small btn-primary">View</button></a></th>
					</tr>';
				}
				echo '</table>';
			} else {
				echo '0';
			}
		}
	}
	public function loadinfo()
	{
		$usr = $this->input->post("clientno", true);
		$user = $this->db->select("*")->where("enquiry_id", $usr)->get("enquiry")->row();
		if (!empty($user)) {
			$jarr = array(
				"name"   => $user->name_prefix . " " . $user->name . " " . $user->lastname,
				"email"  => $user->email,
				"phone"  => $user->phone
			);
			die(json_encode($jarr));
		}
	}
	public function referred_by($id = 0)
	{
		if(user_role('521')){}

		$data['nav1'] = 'nav2';
		$data['title']    = display('Lead Details');
		$data['header'] = ($id ? ' Edit ' : ' Add ') . 'Referred By';
		$data['table'] = $this->Leads_Model->get_referred_by();
		if ($id) {
			$data['data'] = $this->Leads_Model->get_referred_by(array('id' => $id));
			if(user_role('5211')){}
		}
		if ($_POST) {

			if ($id) {
				if(user_role('5211')){}
			}else 
			{
				if(user_role('5210')){}
			}	

			$_POST['company_id'] = $this->session->companey_id;
			$_POST['created_by'] = $this->session->userdata('user_id');
			$this->Leads_Model->save_referred_by($_POST, $id);
			redirect(base_url('ticket/referred_by/' . $id));
		}
		$data['content']  = $this->load->view('add_referred_by', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function delete_referred_by($id)
	{
		if(user_role('5212')){}
		$this->Leads_Model->delete_referred_by($id);
		redirect(base_url('ticket/referred_by'));
	}
	public function remove_attachment($ticketno,$delete_key)
	{
		$res = $this->Ticket_Model->get($ticketno);
		if(!empty($res->attachment))
		{
			$att = json_decode($res->attachment);
			
			$del = $att[$delete_key];
			unset($att[$delete_key]);
			$att = json_encode(array_values($att));
			//json_encode($att); exit();
 			//print_r($att); exit();
 			if($del!='' && unlink(('uploads/ticket/'.$del)))
 			{
 				$this->db->set('attachment',$att);
 				$this->db->where('ticketno',$res->ticketno)->update('tbl_ticket');
 				redirect(site_url('ticket/view/'.$res->ticketno));
 			}
 			else
 			{
 				$this->session->set_flashdata('error','Unable to delete File');
 			}
		}
		
	}
	public function loadamc()
	{
		$prodno = $this->input->post("product", true);
		$enqno  = $this->input->post("client", true);
		$amcarr = $this->db->select("*")->where(array("product_name" => $prodno, "enq_id" => $enqno))->get("tbl_amc")->row();
		$amc = array();
		if (!empty($amcarr)) {
			$amcarr = array(
				"status" 	  => "found",
				"from_date"   => date("d, M Y ", strtotime($amcarr->amc_fromdate)),
				"to_date"     => date("d, M Y ", strtotime($amcarr->amc_todate))
			);
		} else {
			$amcarr = array("status" => "Not Found");
		}
		die(json_encode($amcarr));
	}
	public function loadoldticket($prd = "")
	{
		$data['oldticket'] = $this->db->select("tck.*,sour.lead_name as source_name,subj.subject_title as issue_name")
			->from("tbl_ticket as tck")
			->join("lead_source as sour", "tck.sourse=sour.lsid", "left")
			->join("tbl_ticket_subject as subj", "tck.issue=subj.id", "left")
			->where("tck.product", $prd)
			->where("tck.company", $this->session->companey_id)
			->get()->result();
		$this->load->view("ticket/page/tck-table", $data);
	}
	public function addproblems($prblm = "")
	{
		if(user_role('522')){}

		$this->saveticket();
		if (empty($prblm)) {
			$data['title'] = "Add Problems";
			$data["problem"] = $this->Ticket_Model->getissues();
			$data['content'] = $this->load->view("ticket/page/problem-list", $data, true);
		} else {
			$data["eproblem"] = $this->db->select("*")->where("cmp", $this->session->companey_id)->where("id", $prblm)->get("tck_mstr")->row();
			$data['content'] = $this->load->view("ticket/page/problem-list", $data, true);
		}
		$this->load->view('layout/main_wrapper', $data);
	}
	public function saveticket()
	{
		if (isset($_POST["problem"])) {
			if (isset($_POST["problemno"])) {
				$updarr = array("title" 	=> $this->input->post("problem"),);
				$prblm  = $this->input->post("problemno", true);
				$this->db->where("id", $prblm);
				$this->db->update("tck_mstr", $updarr);
				$this->session->set_flashdata('message', 'Successfully Updated Problem');
				redirect(base_url("ticket/addproblems.html/"), "refresh");
			} else {
				$insarr = array(
					"title" 	=> $this->input->post("problem"),
					"cmp"   	=> $this->session->companey_id,
					"added_by"	=> $this->session->user_id
				);
				$this->db->insert("tck_mstr", $insarr);
				redirect(base_url("ticket/addproblems.html"), "refresh");
				$this->session->set_flashdata('message', 'Successfully added Problem');
			}
		}
	}
	public function add_subject()
	{
		if(user_role('522')){}

		$data['title'] = display('ticket_problem_master');
		$data['nav1'] = 'nav2';
		#------------------------------# 
		$leadid = $this->uri->segment(3);
		if (!empty($_POST)) {

			if(user_role('524')){}

			$reason = $this->input->post('subject');
			$data = array(
				'subject_title' => $reason,
				'process_id' => implode(',',$this->input->post('process')),
				'comp_id' => $this->session->userdata('companey_id')
			);
			$insert_id = $this->Ticket_Model->add_tsub($data);
			redirect('ticket/add_subject');
		}
		$data['subject'] = $this->Ticket_Model->get_sub_list();
		$this->load->model('dash_model');
		$data['products'] = $this->dash_model->get_user_product_list();
		$data['content'] = $this->load->view('ticket_subject', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function update_subject()
	{
		if(user_role('525')){}

		if (!empty($_POST)) {
			$drop_id = $this->input->post('drop_id');
			$reason = $this->input->post('subject');
			$this->db->set('subject_title', $reason);
			$this->db->set('process_id',implode(',',$this->input->post('process')));			
			$this->db->where('id', $drop_id);
			$this->db->update('tbl_ticket_subject');
			$this->session->set_flashdata('SUCCESSMSG', 'Update Successfully');
			redirect('Ticket/add_subject');
		}
	}
	public function delete_subject($drop = null)
	{
		if(user_role('526')){}

		if ($this->Ticket_Model->delete_subject($drop)) {
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('Ticket/add_subject');
	}
	public function gc_vtrans_api($gc_no)
	{
		$url = "http://203.112.143.175/VTWS/Service.asmx?wsdl";
		$soapclient = new SoapClient($url, array('UserName' => 'vtransweb', 'Password' => 'vt@2016'));
		$response = $soapclient->__soapCall('GetTrackNTraceData', array('parameters' => array('UserName' => 'vtransweb', 'Password' => 'vt@2016', 'Gc_No' => $gc_no)));
		$xml = $response->GetTrackNTraceDataResult->any;
		$response = simplexml_load_string($xml);
		$ns = $response->getNamespaces(true);		
		$res = array();
		if (!empty($response->NewDataSet))
		{
			$res = (array) $response->NewDataSet;
		}
		$gc_extra	=	$this->get_gc_extra_data($gc_no);				
		$res['extra'] = $gc_extra;
		//array_push($res,array('extra'=>$gc_extra));
		echo json_encode($res);
	}

	public function get_gc_extra_data($gc_no){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'http://203.112.143.175/GCTracking/api/GCTracking/GetGCDetails?GCNO='.$gc_no,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
		'tokenid: 48673A8B-EE3E-498F-A0BE-FD7AE9BAC538-b654fbe2-c353-428a-9832-a36defdae100',
		'clientid: 10000001',
		'Content-Length:0'
		),
		));
		$response = curl_exec($curl);
		$response = json_decode($response,true);
		// echo "<pre>";
		// print_r($response);
		// echo "</pre>";
		curl_close($curl);
		$res = array();
		if(!empty($response['gccrossingDetails']) && !empty($response['gcDdata'])){			
			return $response;
		}
	}

	public function Dashboard()
	{
		if (user_access(310)) {
			if($_POST){
              $data['fromdate']=$this->input->post('fromdate');
			  $data['todate']=$this->input->post('todate');
			  $data['type']='datewise';
			}else{
				$data['fromdate']='all';
				$data['todate']='all';
			}
			$data['title'] = 'Ticket Dashboard';
			$data['subject'] = $this->Ticket_Model->get_sub_list();
			$data['content'] = $this->load->view('ticket/dashboard', $data, true);
			$this->load->view('layout/main_wrapper', $data);
		} else {
			redirect('dashboard');
		}
	}
	public function createddatewise()
	{
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		if($fromdate=='all'){
		$get = $this->Ticket_Model->getfistDate();
			$date = date('Y-m-d', strtotime($get));
			$date2 = date('Y-m-d');
			$begin = new DateTime($date);
			$end   = new DateTime($date2);
		}else{
			 $begin = new DateTime(date('Y-m-d', strtotime($fromdate)));
			 $end   = new DateTime(date('Y-m-d', strtotime($todate)));
		     }
		
		$data = [];
			
			for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
				$idate = $i->format("Y-m-d");
				$count_1 = $this->Ticket_Model->createddatewise(1,$idate);				
				$count_2 = $this->Ticket_Model->createddatewise(2,$idate);				
				$data[] = [
							'date'  => $idate,
							'visits'=>$count_1,
							'hits'=>$count_2,
						  ];
			}
		echo json_encode($data);
	}
	public function createddatewise1()
	{
		$get = $this->Ticket_Model->getfistDate();
		$data = [];
		if (!empty($get)) {
			$date = date('Y-m-d', strtotime($get));
			$date2 = date('Y-m-d');
			$begin = new DateTime($date);
			$end   = new DateTime($date2);
			for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
				$idate = $i->format("Y-m-d");
			  $idate;
			}
		}
		// print_r($data);
		echo json_encode($data);
	}
	public function referred_byJson()
	{
		$data = array();
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		$refData = $this->Ticket_Model->refferedBy();
		foreach ($refData as $key => $value) {
			$count = $this->Ticket_Model->countrefferedBy($value->id,$fromdate,$todate);
			$data[] = ['name' => $value->name, 'value' => $count];
		}
		echo json_encode($data);
	}
	public function data_priority_wiseJson()
	{

		$fromdate=$this->uri->segment(3);
		$todate=$this->uri->segment(4);
		$comp_id= $this->session->userdata('comp_id');
		$user_id= $this->session->userdata('user_id_id');
		$sdata=$this->session->userdata('ticket_filters_sess');
		$process=$this->session->process_id_id;
			//   print_r($sdata['priority']);
			  if(empty($sdata['priority'])){
				$low = $this->Ticket_Model->report_countPriority(1,$fromdate,$todate,$process,$user_id,$comp_id);
		$medium = $this->Ticket_Model->report_countPriority(2,$fromdate,$todate,$process,$user_id,$comp_id);
		$high = $this->Ticket_Model->report_countPriority(3,$fromdate,$todate,$process,$user_id,$comp_id);
		$data[] = ['name' => 'High', 'value' => $high];
		$data[] = ['name' => 'Medium', 'value' => $medium];
		$data[] = ['name' => 'Low', 'value' => $low];
		echo json_encode($data);
			  }else{
				  if($sdata['priority']==1){
					$low = $this->Ticket_Model->report_countPriority(1,$fromdate,$todate,$process,$user_id,$comp_id);
		$data[] = ['name' => 'Low', 'value' => $low];
		echo json_encode($data);
				  }
				  if($sdata['priority']==2){
					$medium = $this->Ticket_Model->report_countPriority(2,$fromdate,$todate,$process,$user_id,$comp_id);
		$data[] = ['name' => 'Medium', 'value' => $medium];
		echo json_encode($data);
				  }
				  if($sdata['priority']==3){
					$high = $this->Ticket_Model->report_countPriority(3,$fromdate,$todate,$process,$user_id,$comp_id);
		$data[] = ['name' => 'High', 'value' => $high];
		echo json_encode($data);
				  }
			  }
		
	}
	public function priority_wiseJson()
	{
		
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		$low = $this->Ticket_Model->countPriority(1,$fromdate,$todate);
		$medium = $this->Ticket_Model->countPriority(2,$fromdate,$todate);
		$high = $this->Ticket_Model->countPriority(3,$fromdate,$todate);
		$data[] = ['name' => 'High', 'value' => $high];
		$data[] = ['name' => 'Medium', 'value' => $medium];
		$data[] = ['name' => 'Low', 'value' => $low];
		echo json_encode($data);
	}
	public function complaint_typeJson()
	{
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		$complaint = $this->Ticket_Model->complaint_type(1,$fromdate,$todate);
		$query = $this->Ticket_Model->complaint_type(2,$fromdate,$todate);
		$data[] = ['name' => 'Complaint', 'value' => $complaint];
		$data[] = ['name' => 'Query', 'value' => $query];
		echo json_encode($data);
	}
	public function source_typeJson()
	{
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		$getSourse = $this->Ticket_Model->getSourse(1);
		$data=[];
		foreach ($getSourse as $key => $value) {
			$count = $this->Ticket_Model->countTSourse($value->lsid,$fromdate,$todate);
			$data[] = ['name' => $value->lead_name, 'value' => $count];
		}
		echo json_encode($data);
	}
	public function stage_typeJson()
	{
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		$process = $this->session->process[0]??0;
		$data=[];

		$getSourse = $this->Leads_Model->find_estage($process,4);
		// print_r($getSourse);
		// die();
		foreach ($getSourse as $key => $value) {
			$count = $this->Ticket_Model->countTstage($value->stg_id,$fromdate,$todate);
			$data[] = ['name' => $value->lead_stage_name, 'value' => $count];
		}
		echo json_encode($data);
	}
	public function subsource_typeJson()
	{
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		//substage wise 
		$data=[];
		$subsource = $this->Ticket_Model->subsource();
		foreach ($subsource as $key => $value) {
			$count = $this->Ticket_Model->countSubsource($value->id,$fromdate,$todate);
			$data[] = ['name' => $value->description, 'value' => $count];
		}
		echo json_encode($data);
	}
	public function product_ticketJson()
	{
		$fromdate=$this->uri->segment(3);
	    $todate=$this->uri->segment(4);
		$data=[];
		//fetch products
		$products=$this->db->where('comp_id',$this->session->companey_id)->get('tbl_product_country')->result();
		foreach ($products as $key => $value) {
		$count = $this->Ticket_Model->countproduct_ticket($value->id,$fromdate,$todate);
		$data[] = ['name' => $value->country_name, 'value' => $count];
		}
		echo json_encode($data);
	}
	public function autoticketAssign()
	{
		$fetchrules = $this->db->where(array('comp_id' => $this->session->companey_id, 'type' => 5))->order_by("id", "ASC")->get('leadrules')->result();
		foreach ($fetchrules as $key => $value) {
			$data = json_decode($value->rule_json);
			$stageId = $data->rules[0]->value;
			$substageId = $data->rules[1]->value;
			$rule_action = json_decode($value->rule_action);
			$esc_hr = $rule_action->esc_hr;
			$assign_to = $rule_action->assign_to;
			$leadtitle = $value->title;
			$lid = $value->id;
			$fetchTicket = $this->db->where(array('company' => $this->session->companey_id, 'ticket_stage' => $stageId))->get('tbl_ticket')->result();
			foreach ($fetchTicket as $key => $value2) {
				if ($value2->ticket_substage != NULL) {
					$subsource = $this->db->where(array('comp_id' => $this->session->companey_id, 'id' => $value2->ticket_substage))->get('lead_description')->row();
					if ($subsource->id != $substageId) {
						$coml_date = $value2->coml_date;
						$currentDate = date('Y-m-d H:i:s');
						$currentD = date('Y-m-d H:i');
						$time1 = strtotime($coml_date);
						$time2 = strtotime($currentDate);
						$hourTime = round(($time2 - $time1) / 60, 1);
						$tid = $value2->id;
						$nextAssignTimeF=$value2->nextAssignTime;
						if ($hourTime >= $esc_hr) {
							// user check
							// check office time
							
							$inTime='10:00';
							$outTime='18:00';
							$currentTime = date('H:i');
							$todayIntime=date('Y-m-d '.$inTime);
							$nextAssignment = date('Y-m-d H:i',strtotime($todayIntime . "+1 days"));
							if ($nextAssignTimeF <= $currentD OR $nextAssignTimeF!=NULL) {
								if ($currentTime >= $outTime ) {
									//if out is grater then check the holiday exist or not.
									//FETCH STATE AND CITY  from user table
									$userData=$this->db->get('tbl_admin')->row();
									$state_id=$userData->state_id;
									$city_id =$userData->city_id;
									if($state_id!=0 OR $city_id!=0){
										$gettholiday=$this->db->where(array('state'=>$state_id,'city'=>$city_id))
															  ->where('t_deadline >=',$nextAssignment)
															  ->where('t_deadline <=',$nextAssignment)
															  ->get('holidays');
										if ($gettholiday->num_rows()==1) {
												$getHoliday=$gettholiday->row();
												$dateFrom=$getHoliday->datefrom;
												$dateTo=$getHoliday->dateto;
												$days=$dateTo-$dateFrom;
												if($days==0){ $days=1; }
										        echo'Added next assignmnet time ';
												$nextAssignment = date('Y-m-d H:i',strtotime($todayIntime . "+".$days." days"));
												$this->Ticket_Model->insertNextAssignTime($assign_to,$nextAssignment,$tid);
										}else{
										//change next assign time and exit
										echo'Added next assignmnet time ';
										$this->Ticket_Model->insertNextAssignTime($assign_to,$nextAssignment,$tid);
										}
									}else{
										$todayIntime=date('Y-m-d '.$inTime);
										$nextAssignment = date('Y-m-d H:i',strtotime($todayIntime . "+1 days"));
										echo'Add next assignmnet time ';
										$this->Ticket_Model->insertNextAssignTime($assign_to,$nextAssignment,$tid);
									}
								 	}else{
										echo'Assign ticket to user';
										$this->Ticket_Model->insertData($assign_to, $tid, $lid);
									}
									}
							}
						}
					}
				}
			}
			//subsatge
		}
		// tat rule code start
		public function tat_run($comp_id){			
			echo date('Y-m-d H:i:s').'<br>';
			$fetchrules = $this->db->where(array('comp_id' => $comp_id, 'type' => 5,'status'=>1))->order_by("id", "ASC")->get('leadrules')->result();			
			if(!empty($fetchrules)){
				foreach ($fetchrules as $key => $value) {
					$rule_action = json_decode($value->rule_action);
					$esc_hr = $rule_action->esc_hr;
					$assign_to = $rule_action->assign_to;
					$rule_title = $value->title; 
					$lid = $value->id;					
					$this->db->where($value->rule_sql);					
					$tickets	=	$this->db->get('tbl_ticket')->result_array();					
					
					// echo '<pre>';
					// print_r($tickets); 
					// echo '</pre>';
					//echo $lid.' '.$esc_hr.' '.$rule_title.'<br>';
					
					if(!empty($tickets)){ 
						foreach($tickets as $tck){
							$this->db->select('GROUP_CONCAT(id) as rules');
							$this->db->where(array('rule_json'=>$value->rule_json,'comp_id'=>$comp_id));
							$this->db->where('id<',$lid);
							$r	= $this->db->get('leadrules')->row_array();						
							$r = $r['rules']??'';
							
							$t1 = $this->Ticket_Model->is_tat_rule_executed($tck['id'],$lid); // must not executed
							$t2 = $this->Ticket_Model->is_tat_rule_executed($tck['id'],$r); //must executed
							
							//echo 't1 - '.$t1.' t2 - '.$t2.' '.$rule_title.'<br>';
							if(!$t1 && ($t2||empty($r))){								
								$this->db->where('comp_id',$comp_id);
								$this->db->where('tck_id',$tck['id']);
								$this->db->order_by('id','desc');
								$last_act = $this->db->get('tbl_ticket_conv')->row_array();
								$d = $last_act['send_date']??$tck['coml_date'];								
								$currentDate = date('Y-m-d H:i:s');
								$bh	=	$this->isBusinessHr(new DateTime($currentDate));	
								if($bh){
									$created_date	=	$this->currect_created_date($d,$assign_to);								
									$working_hrs	=	$this->get_working_hours($created_date,$currentDate,$assign_to);
									echo $d.' '.$tck['id'].' '.$working_hrs.' '.$esc_hr.'<br>';
									if($working_hrs >= $esc_hr){
										$this->Ticket_Model->insertData($assign_to,$tck['id'],$lid,$rule_title,$comp_id,286);										
										echo $this->db->last_query();
										echo '<br>'.$rule_title.'<br>';
									}
								}
							}
						}
					}
				}
			}
		}
		public function currect_created_date($d,$uid){
			$is_bus_hr	=	$this->isBusinessHr(new DateTime($d));			
			if($is_bus_hr){
				$timeObject = new DateTime($d);
				$timestamp = $timeObject->getTimeStamp();
				$date1 = date('Y-m-d', $timestamp);					
				$time1 = date('H:i:s', $timestamp);								
				$is_working_day	=	$this->is_working_day($date1,$uid);				
				if($is_working_day){
					return $d;
				}else{
					$next_date = date('Y-m-d', strtotime($date1 .' +1 day'));					
					$next_date = $next_date.' 10:00:00';
					return $this->currect_created_date($next_date,$uid);					
				}
			}else{
				$wdate =	$this->get_working_date($d);			
				return $this->currect_created_date($wdate,$uid);				
			}
		}
		function is_working_day($d,$user){
			$hlist	=	$this->Ticket_Model->get_user_holidays($user);
			if(in_array($d,$hlist)){
				return false;
			}else{
				return true;
			}
		}
		
		function get_working_date($d){
						
			$timeObject = new DateTime($d);
			$timestamp = $timeObject->getTimeStamp();
			$act_time = date('H:i', $timestamp);			
			$act_date = date('Y-m-d', $timestamp);			
			if($act_time < '10:00'){
				$next_time = $act_date.' 10:00:00';
				
			}else if($act_time > '06:00') {
				$next_time = '';
				$next_date = date('Y-m-d', strtotime($act_date .' +1 day'));
				$next_time .= $next_date.' 10:00:00';
			}
			
			return $next_time;
		}
		function isBusinessHr($timeObject=0) {		
			$status = FALSE;
			$storeSchedule = [
				'Mon' => ['10:00 AM' => '06:00 PM'],				
				'Tue' => ['10:00 AM' => '06:00 PM'],
				'Wed' => ['10:00 AM' => '06:00 PM'],
				'Thu' => ['10:00 AM' => '06:00 PM'],
				'Fri' => ['10:00 AM' => '06:00 PM'],
				'Sat' => ['10:00 AM' => '06:00 PM']				
			];
		
			if(empty($timeObject)){
				$timeObject = new DateTime();
				$timestamp = $timeObject->getTimeStamp();
				$currentTime = $timeObject->setTimestamp($timestamp)->format('H:i A');
			}else{
				$timestamp = $timeObject->getTimeStamp();
				$currentTime = $timeObject->setTimestamp($timestamp)->format('H:i A');
			}
			
			// echo $currentTime.'<br>';
			 //echo date('D', $timestamp);
			// loop through time ranges for current day
			if(!empty($storeSchedule[date('D', $timestamp)])){				
				foreach ($storeSchedule[date('D', $timestamp)] as $startTime => $endTime) {		
					// create time objects from start/end times and format as string (24hr AM/PM)
					$startTime = DateTime::createFromFormat('h:i A', $startTime)->format('H:i A');
					$endTime = DateTime::createFromFormat('h:i A', $endTime)->format('H:i A');		
					// check if current time is within the range
					if (($startTime <= $currentTime) && ($currentTime <= $endTime)) {
						$status = TRUE;
						break;
					}
				}
			}
			return $status;
		}
		function get_working_hours($from,$to,$uid)
		{
			// timestamps
			$from_timestamp = strtotime($from);
			$to_timestamp = strtotime($to);
			// work day seconds
			$workday_start_hour = 10;
			$workday_end_hour = 18;
			$workday_seconds = ($workday_end_hour - $workday_start_hour)*3600;
			// work days beetwen dates, minus 1 day
			$from_date = date('Y-m-d',$from_timestamp);
			$to_date = date('Y-m-d',$to_timestamp);
			$workdays_number = count($this->get_workdays($from_date,$to_date,$uid))-1;
			$workdays_number = $workdays_number<0 ? 0 : $workdays_number;
			
			// echo $workdays_number.'<br>';
			// start and end time
			$start_time_in_seconds = date("H",$from_timestamp)*3600+date("i",$from_timestamp)*60;
			$end_time_in_seconds = date("H",$to_timestamp)*3600+date("i",$to_timestamp)*60;
			// final calculations
			$working_hours = ($workdays_number * $workday_seconds + $end_time_in_seconds - $start_time_in_seconds) / 86400 * 24;
			return $working_hours;
		}
		function get_workdays($from,$to,$uid) 
		{
			// arrays
			$days_array = array();
			$skipdays = array("Sunday");
			$skipdates = $this->get_holidays($uid);
			// other variables
			$i = 0;
			$current = $from;
			if($current == $to) // same dates
			{
				$timestamp = strtotime($from);
				if (!in_array(date("l", $timestamp), $skipdays)&&!in_array(date("Y-m-d", $timestamp), $skipdates)) {
					$days_array[] = date("Y-m-d",$timestamp);
				}
			}
			elseif($current < $to) // different dates
			{
				while ($current < $to) {
					$timestamp = strtotime($from." +".$i." day");
					if (!in_array(date("l", $timestamp), $skipdays)&&!in_array(date("Y-m-d", $timestamp), $skipdates)) {
						$days_array[] = date("Y-m-d",$timestamp);
					}
					$current = date("Y-m-d",$timestamp);
					$i++;
				}
			}
			return $days_array;
		}
		function get_holidays($uid) 
		{
			// arrays			
			$days_array = $this->Ticket_Model->get_user_holidays($uid);;
			// You have to put there your source of holidays and make them as array...
			// For example, database in Codeigniter:
			// $days_array = $this->my_model->get_holidays_array();
			return $days_array;
		}
		// tat code end
		
			public function list_short_details()
			{
				$data['created_today'] = $this->Ticket_Model->createdTodayCount();
				$data['updated_today'] = $this->Ticket_Model->updatedTodayCount();
				$data['closed_today']   = $this->Ticket_Model->closedTodayCount();
				$data['all_today']     = $this->Ticket_Model->allTodayCount();
				echo json_encode($data);
			}
			public function short_dashboard()
			{
				
				$this->common_query_short_dashboard('created');
				//$this->db->where('tck.added_by',$this->session->user_id);
        		$data['created'] = $this->db->count_all_results();
        		
        		$this->common_query_short_dashboard('assigned');
				//$this->db->where('tck.assign_to',$this->session->user_id);
				$data['assigned'] = $this->db->count_all_results();
				//echo $this->db->last_query(); exit();
				$this->common_query_short_dashboard();
				$this->db->where('tck.last_update != tck.coml_date');
				$data['updated'] = $this->db->count_all_results();
				
				//echo $this->db->last_query(); exit();

				$this->common_query_short_dashboard();
				$this->db->where('tck.ticket_status','3');
				$data['closed'] = $this->db->count_all_results();
				
				$this->common_query_short_dashboard();
				$this->db->where('tck.last_update = tck.coml_date');
				$data['pending'] = $this->db->count_all_results();
				//echo $this->db->last_query(); exit();
				$this->common_query_short_dashboard();
				$data['total']  = $this->db->count_all_results();
				$this->common_query_short_dashboard();
				 $this->db->get();
				 $res = $this->db->last_query();
				$num = $this->db->query("SELECT count(*) as num from tbl_ticket_conv inner join ($res) chk on chk.id= tbl_ticket_conv.tck_id");
				$data['activity'] = $num->row()->num;
				echo json_encode($data);
			}
		public function common_query_short_dashboard($para='')
		{
			$comp_id = $this->session->companey_id;
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["ticket_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["ticket_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       
        $sel_string = array();
        $sel_string[] = "tck.*"; 
        if($showall or in_array(2,$acolarr))
        {
            $sel_string[] = " concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname ";
        }
        if($showall or in_array(5,$acolarr))
        {
            $sel_string[] = " prd.country_name ";
        }
        if($showall or in_array(6,$acolarr))
        {
            $sel_string[] = " concat(for_assign.s_display_name,' ',for_assign.last_name) as assign_to_name  ";
        }
        if($showall or in_array(7,$acolarr))
        {
            $sel_string[] = " concat(for_created.s_display_name,' ',for_created.last_name) as created_by_name ";
        }
        if($showall or in_array(10,$acolarr))
        {
            $sel_string[] = " ref.name as referred_name ";
        }
        if($showall or in_array(11,$acolarr))
        {
            $sel_string[] = " source.lead_name as source_name ";
        }
        if($showall or in_array(12,$acolarr))
        {
            $sel_string[] = " stage.lead_stage_name ";
        }
        if($showall or in_array(13,$acolarr))
        {
            $sel_string[] = " sub_stage.description ";
        }
        if($showall or in_array(16,$acolarr))
        {
            $sel_string[] = " status.status_name ";    
        }
        if($showall or in_array(17,$acolarr))
        {
            $sel_string[] = " concat(assign_by.s_display_name,' ',assign_by.last_name) as assigned_by_name";    
        }
        
        $select = implode(',', $sel_string);
        $this->db->select($select);
        $this->db->from("tbl_ticket  tck");
		
		if($showall or count(array_intersect(array(2,4),$acolarr))>0)
        {
            $this->db->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT");
        }
        
        if($showall or in_array(5, $acolarr))
        {
            $this->db->join("tbl_product_country prd", "prd.id = tck.product", "LEFT");
        }
        if($showall or in_array(6,$acolarr))
        {
            $this->db->join("tbl_admin as for_assign", "for_assign.pk_i_admin_id = tck.assign_to", "LEFT");
        }
         
        if($showall or in_array(7, $acolarr))
        {
            $this->db->join("tbl_admin as for_created", "for_created.pk_i_admin_id = tck.added_by", "LEFT");
        }
        
        if($showall or in_array(10, $acolarr))
        {
         $this->db->join("tbl_referred_by ref","tck.referred_by=ref.id","LEFT");
        }
        if($showall or in_array(11, $acolarr))
        {
         $this->db->join("lead_source source","tck.sourse=source.lsid","LEFT");
        }
       
        if($showall or in_array(12, $acolarr))
        {
         $this->db->join("lead_stage stage","tck.ticket_stage=stage.stg_id","LEFT");
        } 
        
        if($showall or in_array(13, $acolarr))
        {
         $this->db->join("lead_description sub_stage","tck.ticket_substage=sub_stage.id","LEFT");
        } 
        if($showall or in_array(16, $acolarr))
        {
         $this->db->join("tbl_ticket_status status","tck.ticket_status=status.id","LEFT");
        } 
        if($showall or in_array(17, $acolarr))
        {
         $this->db->join("tbl_admin assign_by","tck.assigned_by=assign_by.pk_i_admin_id","LEFT");
        } 
         $this->db->where("tck.company",$this->session->companey_id);
         $this->db->group_by("tck.id");
			$enquiry_filters_sess   =   $this->session->ticket_filters_sess;
            
	        $top_filter             =   !empty($enquiry_filters_sess['top_filter'])?$enquiry_filters_sess['top_filter']:'';
	        $from_created           =   !empty($enquiry_filters_sess['from_created'])?$enquiry_filters_sess['from_created']:'';       
	        $to_created             =   !empty($enquiry_filters_sess['to_created'])?$enquiry_filters_sess['to_created']:'';
		   
			$updated_from_created           =   !empty($enquiry_filters_sess['update_from_created'])?$enquiry_filters_sess['update_from_created']:'';       
			$updated_to_created             =   !empty($enquiry_filters_sess['update_to_created'])?$enquiry_filters_sess['update_to_created']:'';
		  
			$source                 =   !empty($enquiry_filters_sess['source'])?$enquiry_filters_sess['source']:'';
	       
	        $createdby              =   !empty($enquiry_filters_sess['createdby'])?$enquiry_filters_sess['createdby']:'';
	        $assign                 =   !empty($enquiry_filters_sess['assign'])?$enquiry_filters_sess['assign']:'';
	      
	        $problem                 =   !empty($enquiry_filters_sess['problem'])?$enquiry_filters_sess['problem']:'';
	        $priority                 =   !empty($enquiry_filters_sess['priority'])?$enquiry_filters_sess['priority']:'';
	        $issue                 =   !empty($enquiry_filters_sess['issue'])?$enquiry_filters_sess['issue']:'';
	        $productcntry          =   !empty($enquiry_filters_sess['prodcntry'])?$enquiry_filters_sess['prodcntry']:'';
	        $stage          =   !empty($enquiry_filters_sess['stage'])?$enquiry_filters_sess['stage']:'';
	        $sub_stage          =   !empty($enquiry_filters_sess['sub_stage'])?$enquiry_filters_sess['sub_stage']:'';
	        $ticket_status          =   !empty($enquiry_filters_sess['ticket_status'])?$enquiry_filters_sess['ticket_status']:'';
	         $assign_by          =   !empty($enquiry_filters_sess['assign_by'])?$enquiry_filters_sess['assign_by']:'';
	        $where='';
			$CHK = 0;
	         if(!empty($from_created) && !empty($to_created)){
	            $from_created = date("Y-m-d",strtotime($from_created));
	            $to_created = date("Y-m-d",strtotime($to_created));
	            $where .= " (DATE(tck.coml_date) >= '".$from_created."' AND DATE(tck.coml_date) <= '".$to_created."') OR (DATE(tck.last_update) >= '".$from_created."' AND DATE(tck.last_update) <= '".$to_created."')";
	            $CHK = 1;
	        }
	        if(!empty($from_created) && empty($to_created)){
	            $from_created = date("Y-m-d",strtotime($from_created));
	            $where .= " DATE(tck.coml_date) >=  '".$from_created."' OR DATE(tck.last_update) >=  '".$from_created."'  "; 
	            $CHK = 1;                           
	        }
	        if(empty($from_created) && !empty($to_created)){            
	            $to_created = date("Y-m-d",strtotime($to_created));
	            $where .= " DATE(tck.coml_date) <=  '".$to_created."' OR DATE(tck.last_update) <=  '".$to_created."'"; 
	            $CHK = 1;                                  
			}
			
			
			if(!empty($updated_from_created) && !empty($updated_to_created)){
				if($CHK)
                $where .= 'AND';
				$updated_from_created = date("Y-m-d",strtotime($updated_from_created));
				$updated_to_created = date("Y-m-d",strtotime($updated_to_created));
				$where .= " (DATE(tck_conv.send_date) >= '".$updated_from_created."' AND DATE(tck_conv.send_date) <= '".$updated_to_created."') ";
				$CHK = 1;
				$this->db->join("(select * from tbl_ticket_conv where comp_id=$comp_id AND subj!='Ticked Created') as tck_conv","tck_conv.tck_id=tck.id","LEFT");
				
			}
	
			if(!empty($updated_from_created) && empty($updated_to_created)){
				if($CHK)
                	$where .= 'AND';
				$updated_from_created = date("Y-m-d",strtotime($updated_from_created));
				$where .= " DATE(tck_conv.send_date) >=  '".$updated_from_created."'"; 
				$this->db->join("(select * from tbl_ticket_conv where comp_id=$comp_id AND subj!='Ticked Created') as tck_conv","tck_conv.tck_id=tck.id","LEFT");
	
				$CHK = 1;                           
			}
			if(empty($updated_from_created) && !empty($updated_to_created)){            
				if($CHK)
                	$where .= 'AND';
				$updated_to_created = date("Y-m-d",strtotime($updated_to_created));
				$where .= " DATE(tck_conv.send_date) <=  '".$updated_to_created."'"; 
				$this->db->join("(select * from tbl_ticket_conv where comp_id=$comp_id AND subj!='Ticked Created') as tck_conv","tck_conv.tck_id=tck.id","LEFT");
			  
	
				$CHK = 1;                                  
			}
	        if(!empty($productcntry)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.product =  '".$productcntry."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($createdby)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.added_by =  '".$createdby."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($assign)){    
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.assign_to =  '".$assign."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($assign_by)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.assigned_by =  '".$assign_by."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($source)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.sourse =  '".$source."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($problem)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.category =  '".$problem."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($priority)){           
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.priority =  '".$priority."'"; 
	            $CHK =1;                             
	        }
	         if(!empty($issue)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.category =  '".$issue."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($stage)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.ticket_stage =  '".$stage."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($sub_stage)){        
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.ticket_substage =  '".$sub_stage."'"; 
	            $CHK =1;                             
	        }
	        if(!empty($ticket_status)){            
	            if($CHK)
	                $where .= 'AND';
	            $where .= " tck.ticket_status =  '".$ticket_status."'"; 
	            $CHK =1;                             
			}
			// if($CHK)
	        //     $where .= 'AND';
	        // $where .= " ( tck.added_by IN (".implode(',', $all_reporting_ids).')';
	        // $CHK=1;
			
			// if($CHK){
	        //     $where .= ' OR ';
	        //     $CHK =1;
			// }
			
			// $where .= " tck.assign_to IN (".implode(',', $all_reporting_ids).'))';
			if($para=='created'){
				if($CHK)
					$where .= ' AND ';                      
				
				$where .= " tck.added_by IN (".implode(',', $all_reporting_ids).')';
				$CHK=1;
			}else if($para=='assigned'){
				if($CHK)
					$where .= ' AND ';                      
	
				$where .= " tck.assign_to IN (".implode(',', $all_reporting_ids).')';
	
				$CHK=1;
			}else{
				if($CHK)
					$where .= ' AND '; 
				$where .= " ( tck.added_by IN (".implode(',', $all_reporting_ids).')';
				$CHK=1;
		
				if($CHK){
					$where .= ' OR ';
					$CHK =1;
				}
				$where .= " tck.assign_to IN (".implode(',', $all_reporting_ids).'))';
			}
			if(!empty($this->session->process)){
				$this->db->where_in('tck.process_id',$this->session->process);
			}
			$this->db->where($where);
			
		}
		public function has_close_authority($created_by){
			$cuid = $this->session->user_id;
			$res = 0;
			if($cuid == $created_by){
				$res = 1;
			}else{
				$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
				if(in_array($created_by,$all_reporting_ids)){
					$res = 1;
				}
			}
			echo $res;
		}

		public function change_ticket_status($tid){
			$status	=	$this->input->post('ticket_status');
			$comp_id = $this->session->companey_id;
			if($status){
				$this->db->where('id',$tid);
				$this->db->where('company',$comp_id);
				$this->db->set('ticket_status',$status);
				if($this->db->update('tbl_ticket')){
					$comment_id = $this->Ticket_Model->saveconv($tid,'Ticket Status Changed','', $this->input->post('client'),$this->session->user_id,0,0,$status,$comp_id);
					$ticketno = $this->input->post('ticketno');
					$this->load->model('rule_model');
					$this->rule_model->execute_rules($ticketno, array(3,6,7));
				}
			}
		}

		public function chk()
		{
			if(!empty($this->session->userdata()))
				print_r($this->session->userdata());
		}
}