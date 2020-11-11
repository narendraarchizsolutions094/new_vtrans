<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



class Ticket_Model extends CI_Model
{



	public function TickectAdd($data)

	{

		$this->db->insert('ticket', $data);
	}

	/*	public function save($companey_id='',$user_id='')
    	{		
			$cdate = explode("/", $this->input->post("complaindate", true));			
			$ndate = (!empty($cdate[2])) ? $cdate[2]."-".$cdate[0]."-".$cdate[1] : date("Y-m-d"); 			
			$arr = array(
				"message"    => ($this->input->post("remark", true)) ? $this->input->post("remark", true) : '' ,
				"category"   => $this->input->post("relatedto", true),
				"other"      => ($_POST["relatedto"] == "Others") ? $this->input->post("otherrel", true ) : "",
				"product"	 => ($this->input->post("product", true)) ? $this->input->post("product", true) : "",
				"sourse"     => ($this->input->post("source", true)) ? $this->input->post("source", true) : "",
				"complaint_type" => $this->input->post("complaint_type", true),
				"coml_date"	 => $ndate,
				"last_update"=> date("Y-m-d h:i:s"),
				"priority"	 => ($this->input->post("priority", true)) ? $this->input->post("priority", true) : "", 
			);			
			if(!empty($_FILES["attachment"]["name"]))
			{				
				$retdata =  $this->do_upload();			
				if(!empty($retdata["upload_data"]["file_name"])){					
					$arr["attachment"] = $retdata["upload_data"]["file_name"];
				}	
			}			
			if(isset($_POST["ticketno"]))
			{	
				$this->db->where("ticketno", $this->input->post("ticketno", true));
				$this->db->update("tbl_ticket", $arr);				
				if($this->db->affected_rows()){
					return $_POST["ticketno"];					
				}else{
					return false;
				}
			}
			else 
			{				
				$arr["name"]   		= ($this->input->post("name", true)) ? $this->input->post("name", true) : "";
				$arr["email"]  		= ($this->input->post("email", true)) ? $this->input->post("email", true) : "";
				$arr["send_date"]  	= date("Y-m-d h:i:s");
				$arr["client"]     	= ($this->input->post("client", true)) ? $this->input->post("client", true) : "";
				$arr["company"]	 	= $companey_id ;
				$arr["category"]    = $this->input->post("relatedto", true);
				$arr["added_by"] 	= $user_id ;
				$arr["complaint_type"] = $this->input->post("complaint_type", true);
				$arr["ticketno"] 	= "";
				$arr["status"]   	= 0;
				$this->db->insert("tbl_ticket", $arr);				
				$insid = $this->db->insert_id();				
				$tckno = "TCK".$insid.strtotime(date("y-m-d h:i:s"));				
				$updarr = array("ticketno" => $tckno);
				$this->db->where("id", $insid);
				$this->db->update("tbl_ticket", $updarr);				
				if(!empty($insid))
				{					
					$insarr = array("tck_id" 	=> $insid,
									"parent" 	=> 0,
									'comp_id'	=> $this->session->companey_id,
									"subj"   	=> "Ticked Created",
									"msg"    	=> ($this->input->post("remark", true)) ? $this->input->post("remark", true) : '' ,
									"attacment" => "",
									"status"  	=> 0,
									"send_date" =>	date("Y-m-d h:i:s"),
									"client"   	=> ($this->input->post("client", true)) ? $this->input->post("client", true) : '',
									"added_by" 	=> $user_id,									
									);
					if ($this->db->insert("tbl_ticket_conv", $insarr)) {
						return $tckno;
					}else{
						return false;
					}
				}
				else
				{
					$this->session->set_flashdata('message', 'Failed to add ticket');
					return false;
          
				}	
			}	
		}*/

	public function save($companey_id = '', $user_id = '')
	{
		$cid = '';
		//print_r($_POST); 
		if (!empty($companey_id) && !empty($user_id) && $_POST['client'] == '') {
			if (isset($_SESSION['process']) && count($_SESSION['process']) == 1) {
				$encode = get_enquery_code();
				$postData = array(
					'Enquery_id' 	=> $encode,
					'comp_id' 		=> $this->session->companey_id,
					'email' 		=> $this->input->post("email", true),
					'phone' 		=> $this->input->post("phone", true),
					'name' 			=> $this->input->post("name", true),
					'checked' 		=> 0,
					'product_id' 	=>  $_SESSION['process'][0],
					'created_date' 	=>  date("Y-m-d H:i:s"),
					'status' 		=> 1,
					'created_by' 	=> $this->session->user_id,
					'phone'			=> $this->input->post('phone'),
				);
				//print_r($postData);
				$this->db->insert('enquiry', $postData);
				$cid = $this->db->insert_id();
			} else {
				//echo $this->session->userdata('process');
				// 	echo count($_SESSION['process']);

				$this->session->set_flashdata('message', 'Please Select Atmost 1 process while creating a Ticket.');
				return false;
			}
		}

		$cdate = explode("/", $this->input->post("complaindate", true));
		$ndate = (!empty($cdate[2])) ? $cdate[2] . "-" . $cdate[0] . "-" . $cdate[1] : date("Y-m-d");
		$arr = array(
			"message"    => ($this->input->post("remark", true)) ? $this->input->post("remark", true) : '',
			"category"   => $this->input->post("relatedto", true),
			"other"      => ($_POST["relatedto"] == "Others") ? $this->input->post("otherrel", true) : "",
			"product"	 => ($this->input->post("product", true)) ? $this->input->post("product", true) : "",
			"sourse"     => ($this->input->post("source", true)) ? $this->input->post("source", true) : "",
			"complaint_type" => $this->input->post("complaint_type", true),
			//"coml_date"	 => $ndate,
			"last_update" => date("Y-m-d h:i:s"),
			"priority"	 => ($this->input->post("priority", true)) ? $this->input->post("priority", true) : "",
			"issue"	 => ($this->input->post("issue", true)) ? $this->input->post("issue", true) : "",
			"tracking_no"   => ($this->input->post("tracking_no", true)) ? $this->input->post("tracking_no", true) : "",
			"referred_by"   => ($this->input->post("referred_by", true)) ? $this->input->post("referred_by", true) : "",

		);
		if (empty($_FILES["attachment"]["name"]) && $_FILES["attachment"]["size"][0]>0) {	
			$retdata =  $this->do_upload();
			//print_r($retdata);			
			if (!empty($retdata))
			{	
				if(isset($_POST["ticketno"]))
				{	
					$old_ticket =  $this->db->where(array('id'=>$_POST['ticketno']))->get('tbl_ticket')->row();
	
					if(!empty($old_ticket->attachment))
					{	//echo'sdf';
						$new_res = json_decode($old_ticket->attachment);
						$retdata = array_merge($new_res,$retdata);
					}
				}
				//print_r($retdata); exit();
				$arr["attachment"] = json_encode($retdata);
			}
		}

		if (isset($_POST["ticketno"])) {
			$arr["name"]   		= ($this->input->post("name", true)) ? $this->input->post("name", true) : "";
			$arr["email"]  		= ($this->input->post("email", true)) ? $this->input->post("email", true) : "";
			$arr["client"]     	= ($this->input->post("client", true)) ? $this->input->post("client", true) : "";
			$arr["phone"]     	= ($this->input->post("phone", true)) ? $this->input->post("phone", true) : "";
			$this->db->where("id", $this->input->post("ticketno", true));
			$this->db->update("tbl_ticket", $arr);
			if ($this->db->affected_rows()) {
				return $_POST["ticketno"];
			} else {
				return false;
			}
		} else {
			$arr["name"]   		= ($this->input->post("name", true)) ? $this->input->post("name", true) : "";
			$arr["email"]  		= ($this->input->post("email", true)) ? $this->input->post("email", true) : "";
			$arr["phone"]  		= ($this->input->post("phone", true)) ? $this->input->post("phone", true) : "";
			$arr["send_date"]  	= date("Y-m-d H:i:s");
			$arr["client"]     	= ($this->input->post("client", true)) ? $this->input->post("client", true) : $cid;
			$arr["company"]	 	= $companey_id;
			$arr["category"]    = $this->input->post("relatedto", true);
			$arr["added_by"] 	= $user_id;
			$arr["complaint_type"] = $this->input->post("complaint_type", true);
			$arr["ticketno"] 	= "";
			$arr["status"]   	= 0;
			// echo $arr['attachment'];
			// exit();
			$this->db->insert("tbl_ticket", $arr);
			$insid = $this->db->insert_id();
			$tckno = "TCK" . $insid . strtotime(date("y-m-d h:i:s"));
			$updarr = array("ticketno" => $tckno);
			$this->db->where("id", $insid);
			$this->db->update("tbl_ticket", $updarr);
			if (!empty($insid)) {
				$insarr = array(
					"tck_id" 	=> $insid,
					"parent" 	=> 0,
					'comp_id'	=> ($this->session->companey_id != '') ? $this->session->companey_id : $companey_id,
					"subj"   	=> "Ticked Created",
					"msg"    	=> ($this->input->post("remark", true)) ? $this->input->post("remark", true) : '',
					"attacment" => "",
					"status"  	=> 0,
					"send_date" =>	date("Y-m-d H:i:s"),
					"client"   	=> ($this->input->post("client", true)) ? $this->input->post("client", true) : $cid,
					"added_by" 	=> $user_id,
				);
				if ($this->db->insert("tbl_ticket_conv", $insarr)) {
					return $tckno;
				} else {
					return false;
				}
			} else {
				$this->session->set_flashdata('message', 'Failed to add ticket');
				return false;
			}
		}
	}

	public function do_upload()
	{
		$config['upload_path']          = './uploads/ticket/';
		$config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|GIF|gif|png|PNG|pdf|PDF';
		$config['max_size']             = 5000;

		$this->load->library('upload', $config);
		
		$files = $_FILES;
		unset($_FILES);

		$done  = array();

		for( $i=0; $i< sizeof($files['attachment']['name']); $i++ )
		{
			$_FILES['img']['name'] = $files['attachment']['name'][$i];
			$_FILES['img']['type'] = $files['attachment']['type'][$i];
			$_FILES['img']['tmp_name'] = $files['attachment']['tmp_name'][$i];
			$_FILES['img']['error'] = $files['attachment']['error'][$i];
			$_FILES['img']['size'] = $files['attachment']['size'][$i];
			
			if (!$this->upload->do_upload('img'))
			{
				$this->session->set_flashdata('error',$this->upload->display_errors());
			} 
			else 
			{
				$done[] = $this->upload->data()['file_name'];
			}
		}
		return $done;
	}


	public function ticket_status($where= 0)
	{
		if($where)
			$this->db->where($where);
		return $this->db->get('tbl_ticket_status');
	}

	public function saveconv($tckno, $subjects, $msg, $client, $user_id, $stage = 0, $sub_stage = 0,$ticket_status=0)
	{
		$ticket_status = $this->input->post('ticket_status')??$ticket_status;
		//echo $ticket_status; exit();
		$insarr = array(
			"tck_id" => $tckno,
			"comp_id" => $this->session->companey_id,
			"parent" => 0,
			"subj"   => $subjects,
			"msg"    => $msg,
			"attacment" => "",
			"status"  => 0,
			"ticket_status"=>$ticket_status,
			"stage"  => $stage,
			"sub_stage"  => $sub_stage,
			"client"   => $client,
			"added_by" => $user_id,
		);
		$ret = $this->db->insert("tbl_ticket_conv", $insarr);
		if ($ret) {
			$this->session->set_flashdata('message', 'Successfully saved');
			if ($stage) {
				$this->db->set('tbl_ticket.ticket_stage', $stage);
			}
			if ($sub_stage) {
				$this->db->set('tbl_ticket.ticket_substage', $sub_stage);
			}
			if($this->input->post('ticket_status'))
			{
				$this->db->set('tbl_ticket.ticket_status',$ticket_status);
			}
			if ($stage || $sub_stage || $ticket_status) {
				$this->db->where('tbl_ticket.company', $this->session->companey_id);
				$this->db->where('tbl_ticket.id', $tckno);
				$this->db->update('tbl_ticket');
			}
		} else {
			$this->session->set_flashdata('message', 'Failed to save');
		}
		//echo $this->db->last_query();				
	}

	function updatestatus()
	{

		$updarr = array(
			"category" 	=> $this->input->post("issue", true),
			"solution" => $this->input->post("solution", true),
			"status"    => $this->input->post("status", true),
			"review"    => $this->input->post("review", true)
		);
		//print_r($updarr); exit();
		//echo $this->input->post("ticketno", true); exit();
		$this->db->where("id", $this->input->post("ticketno", true));
		$this->db->update("tbl_ticket", $updarr);
		$ret = $this->db->affected_rows();
		if ($ret) {

			$this->session->set_flashdata('message', 'Successfully added ticket');
		} else {
			$this->session->set_flashdata('message', 'Failed to add ticket');
		}
	}

	function getissues()
	{

		return $this->db->select("*")->where("cmp", $this->session->companey_id)->get("tck_mstr")->result();
	}

	// function of getting ticket source
	function getSource($companyid)
	{
		return $this->db->select("s_id,source_name")->where("comp_id", $companyid)->get("tbl_ticket_source")->result();
	}

	function getIssuesByCompnyID($companyid)
	{

		return $this->db->select("title")->where("cmp", $companyid)->get("tck_mstr")->result();
	}


	function getconv($conv)
	{
		$compid = $this->session->companey_id;
		return $this->db->select("cnv.*,lead_stage.lead_stage_name,lead_description.description as sub_stage,concat(admin.s_display_name,' ',admin.last_name) as updated_by,status.status_name,concat(user.s_display_name,' ',user.last_name) as assignedTo")
			->where("cnv.tck_id", $conv)
			->where("cnv.comp_id", $compid)
			->from("tbl_ticket_conv cnv")
			->join("lead_stage", 'lead_stage.stg_id=cnv.stage', 'left')
			->join("lead_description", 'lead_description.id=cnv.sub_stage', 'left')
			->join("tbl_admin as admin","admin.pk_i_admin_id=cnv.added_by")
			->join("tbl_admin as user","user.pk_i_admin_id=cnv.assignedTo",'left')
			->join("tbl_ticket_status status","cnv.ticket_status = status.id","LEFT")
			->order_by("cnv.id DESC")
			->get()
			->result();
	}
	public function getall()
	{
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where = '';
		$where .= "( tck.added_by IN (" . implode(',', $all_reporting_ids) . ')';
		$where .= " OR tck.assign_to IN (" . implode(',', $all_reporting_ids) . '))';

		return $this->db->select("tck.*,enq.gender,prd.country_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname , COUNT(cnv.id) as tconv, cnv.msg, tbl_admin.s_display_name,tbl_admin.last_name")
			->where($where)
			->where("tck.company", $this->session->companey_id)
			->from("tbl_ticket tck")
			->join("tbl_ticket_conv cnv", "cnv.tck_id = tck.id", "LEFT")
			->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT")
			->join("tbl_admin", "tbl_admin.pk_i_admin_id = tck.assign_to", "LEFT")
			->join("tbl_product_country prd", "prd.id = tck.product", "LEFT")
			->order_by("tck.id DESC")
			->group_by("tck.id")
			->get()
			->result();
			//echo $this->db->last_query(); exit();
	}

	public function getTicketListByCompnyID($companyid, $userid)
	{
		$all_reporting_ids    =   $this->common_model->get_categories($userid);
		$where = '';
		$where .= "( tck.added_by IN (" . implode(',', $all_reporting_ids) . ')';
		$where .= " OR tck.assign_to IN (" . implode(',', $all_reporting_ids) . '))';
		return $this->db->select("tck.*,enq.gender,prd.country_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname , COUNT(cnv.id) as tconv, cnv.msg")
			->where($where)
			->where("tck.company", $companyid)

			->from("tbl_ticket tck")
			->join("tbl_ticket_conv cnv", "cnv.tck_id = tck.id", "LEFT")
			->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT")
			->join("tbl_product_country prd", "prd.id = tck.product", "LEFT")
			->order_by("tck.id DESC")
			->group_by("tck.id")
			->get()
			->result();
	}

	public function filterticket($where)
	{

		$this->db->select("tck.*,enq.gender,prd.product_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname , COUNT(cnv.id) as tconv, cnv.msg,stage.lead_stage_name,sub_stage.description");
		$this->db->where("tck.company", $this->session->companey_id);

		if (!empty($where)) {

			$this->db->where($where);
		}

		$this->db->from("tbl_ticket tck");
		$this->db->join("tbl_ticket_conv cnv", "cnv.tck_id = tck.id", "LEFT");
		$this->db->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT");
		$this->db->join("tbl_product prd", "prd.sb_id = tck.product", "LEFT");
		$this->db->join("lead_stage stage", "tck.ticket_stage = stage.stg_id", "LEFT");
		$this->db->join("lead_description sub_stage", "tck.ticket_substage = sub_stage.id", "LEFT");

		$this->db->order_by("tck.id DESC");
		$this->db->group_by("tck.id");

		return	 $this->db->get()
			->result();
	}


	public function all_related_tickets($where)
	{

		$this->db->select("tck.*,enq.gender,prd.product_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname , COUNT(cnv.id) as tconv, cnv.msg");
		$this->db->where("tck.company", $this->session->companey_id);

		$this->db->from("tbl_ticket tck");
		$this->db->join("tbl_ticket_conv cnv", "cnv.tck_id = tck.id", "LEFT");
		$this->db->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT");
		$this->db->join("tbl_product prd", "prd.sb_id = tck.product", "LEFT");
		$ticketno = $where['ticket_no'];
		unset($where['ticket_no']);
		if (!empty($where)) {
			$i = 0;
			foreach ($where as $key => $value) {
				if (!empty($value)) {
					if ($i == 0)
						$this->db->where($key, $value);
					else
						$this->db->or_where($key, $value);
					$i = 1;
				}
			}
		}

		$this->db->group_by("tck.id");
		$this->db->having("tck.ticketno !=", $ticketno);
		$this->db->order_by("tck.id DESC");
		return  $this->db->get()->result();
		//echo $this->db->last_query(); exit();
	}

	public function getproduct()
	{

		return $this->db->select("*")->where(array("comp_id" => $this->session->companey_id, "status" => 1))->get("tbl_product_country")->result();
	}

	public function get($tctno)
	{

		return $this->db->select("tck.*,tck.email as tck_email,tbl_ticket_subject.subject_title,lead_source.lead_name as ticket_source,enq.gender,prd.country_name, concat(enq.name,' ', enq.lastname) as clientname")
			->where("tck.ticketno", $tctno)
			->where("tck.company", $this->session->companey_id)
			->from("tbl_ticket tck")
			->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT")
			->join("tbl_product_country prd", "prd.id = tck.product", "LEFT")
			->join("tbl_ticket_subject", "tbl_ticket_subject.id = tck.category", "LEFT")
			->join("lead_source", "lead_source.lsid = tck.sourse", "LEFT")
			->order_by("tck.id DESC")
			->get()
			->row();
	}
	public function get_issue_list()
	{
		$this->db->where('comp_id', $this->session->companey_id);
		return $this->db->get('tbl_nature_of_complaint')->result();
	}
	public function getallclient()
	{

		if (($this->session->userdata('user_right') == 214)) {

			return $this->db->select("*")->where(array("status" => 3, "phone" => $this->session->phone))->get("enquiry")->result();
		} else {
			return $this->db->select("*")->where(array("status" => 3, "comp_id" => $this->session->companey_id))->get("enquiry")->result();
		}
	}
	public function getclient($client_id)
	{
		return $this->db->select("enquiry.enquiry_id,enquiry.name")->where(array("enquiry_id" => $client_id, "comp_id" => $this->session->companey_id))->get("enquiry")->result();
	}
	public function add_tsub($data)
	{
		$insert = $this->db->insert('tbl_ticket_subject', $data);
		return $insert;
	}

	public function get_sub_list($compid = '')
	{
		if ($compid != '') {
			$this->db->where('comp_id', $compid);
		} else {
			$this->db->where('comp_id', $this->session->userdata('companey_id'));
		}

		$query = $this->db->get('tbl_ticket_subject');
		return $query->result();
	}

	public function delete_subject($drop = null)
	{
		$this->db->where('id', $drop)->delete('tbl_ticket_subject');
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}
	public function get_ticket_status()
	{
		$this->db->where('company_id', $this->session->companey_id);
		return $this->db->get('tbl_ticket_status')->result();
	}
	public function createddatewise($idate)
	{
		$count = $this->db->where('company', $this->session->companey_id)->like('coml_date', $idate)->count_all_results('tbl_ticket');
		return $count;
	}
	public function getfistDate()
	{
		return $this->db->where('company', $this->session->companey_id)->limit(1)->get('tbl_ticket')->row()->coml_date;
	}
	public function refferedBy()
	{

		return $this->db->where('company_id', $this->session->companey_id)->get('tbl_referred_by')->result();
	}
	public function countrefferedBy($rfid)
	{
		return $this->db->where(array('company' => $this->session->companey_id, 'referred_by' => $rfid))->count_all_results('tbl_ticket');
	}
	public function countPriority($type)
	{
		$data = $this->db->where(array('company' => $this->session->companey_id, 'priority' => $type))->count_all_results('tbl_ticket');
		return $data;
	}
	public function complaint_type($type)
	{
		$data = $this->db->where(array('company' => $this->session->companey_id, 'complaint_type' => $type))->count_all_results('tbl_ticket');
		return $data;
	}
	public function getSourse()
	{
		$stage = $this->db->where(array('comp_id' => $this->session->companey_id))->get('lead_source')->result();
		return $stage;
	}
	public function countTSourse($lsid)
	{
		$count = $this->db->where(array('company' => $this->session->companey_id, 'sourse' => $lsid))->count_all_results('tbl_ticket');
		return $count;
	}
	public function getstage()
	{
		$stage = $this->db->where(array('comp_id' => $this->session->companey_id, 'stage_for' => 4))->get('lead_stage')->result();
		return $stage;
	}
	public function countTstage($stg_id)
	{
		$count = $this->db->where(array('company' => $this->session->companey_id, 'ticket_stage' => $stg_id))->count_all_results('tbl_ticket');
		return $count;
	}
	public function subsource()
	{
		$subsource = $this->db->where(array('comp_id' => $this->session->companey_id))->get('lead_description')->result();
		return $subsource;
	}
	public function countSubsource($stg_id)
	{
		$count = $this->db->where(array('company' => $this->session->companey_id, 'ticket_stage' => $stg_id))->count_all_results('tbl_ticket');
		return $count;
	}
	// tat rule holiday list
	public function get_user_holidays($uid){
		$holidays = array();
		$this->db->where('pk_i_admin_id',$uid);
		$userData=$this->db->get('tbl_admin')->row();
		$state_id=$userData->state_id;
		$city_id =$userData->city_id;
		if($state_id!=0 OR $city_id!=0){
			$holidays = $this->db->where(array('state'=>$state_id,'city'=>$city_id,'status'=>1))->get('holidays')->result_array();
		}		
		$list = array();
		if(!empty($holidays)){
			foreach($holidays as $key=>$value){
				$period = new DatePeriod(
					new DateTime($value['datefrom']),
					new DateInterval('P1D'),
					new DateTime($value['dateto'])
				);
				foreach ($period as $key => $value) {
					$list[] = $value->format('Y-m-d');
				}
			}
		}
		return $list;
	}
	
	public function is_tat_rule_executed($tid,$lid){
		$this->db->where('tbl_ticket_conv.tck_id',$tid);
		$this->db->where('tbl_ticket_conv.lid',$lid);
		if($this->db->get('tbl_ticket_conv')->num_rows()){
			return true;
		}else{
			return false;
		}
	}

	public function insertData($uid,$tid,$lid,$esc_level,$comp_id,$added_by)
	{
		
			//move to user
			$ticket_update = ['assign_to' => $uid,'assigned_by'=>$added_by,'last_esc'=>$esc_level];
			$this->db->where(array('id' => $tid))->update('tbl_ticket', $ticket_update);
			$counta = $this->db->where(array('tck_id' => $tid, 'lid' => $lid))->count_all_results('tbl_ticket_conv');
			if ($counta == 0) {
				//save to assignid 	
				$data_msg = ['comp_id' => $comp_id, 'tck_id' => $tid, 'subj' => 'Ticked Assigned','msg'=>$esc_level, 'lid' => $lid, 'assignedTo' => $uid,'added_by'=>$added_by];
				$this->db->insert('tbl_ticket_conv', $data_msg);
			}
	}
	public function insertNextAssignTime($nextAssignment,$tid)
	{
		$ticket_update = ['nextAssignTime' => $nextAssignment];
		$this->db->where(array('id' => $tid))->update('tbl_ticket', $ticket_update);
	}

}

