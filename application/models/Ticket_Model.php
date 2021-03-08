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
   public function get_filterData($type)
   {
	   $comp_id=$this->session->companey_id;
	   $user_id=$this->session->user_id;
		$values=$this->db->where(array('user_id'=>$user_id,'comp_id'=>$comp_id,'type'=>$type))->get('tbl_filterdata');
		$filter=$values->row();
		 $pdata=[];
		
		if(!empty($filter)){
			
		$value=json_decode($filter->filter_data);
		if($type==1){
			$pdata=[
				'from_created' =>$value->from_created??NULL,
					'to_created' =>$value->to_created??NULL,
					'source' =>$value->source??NULL,
					'filter_checkbox' => $value->filter_checkbox??NULL,
					'subsource' =>$value->subsource??NULL,
					'email' =>$value->email??NULL,
					'employee' =>$value->employee??NULL, 
					'datasource' => $value->datasource??NULL,
					'company' =>$value->company??NULL,
					'enq_product' => $value->enq_product??NULL,
					'phone' =>$value->phone??NULL,
					'createdby' =>$value->createdby??NULL,
					'assign' =>$value->assign??NULL,
					'address' =>$value->address??NULL,
					'prodcntry' =>$value->prodcntry??NULL,
					'state' =>$value->state??NULL,
					'city' =>$value->city??NULL,
					'stage' =>$value->stage??NULL,
					'top_filter' =>$value->top_filter??NULL,
					
			];
		}else{
			$pdata=[
					'from_created' =>$value->from_created??NULL,
					'to_created' =>$value->to_created??NULL,
					'update_from_created' =>$value->update_from_created??NULL,
					'update_to_created' =>$value->update_to_created??NULL,
					'source' =>$value->source??NULL,
					'problem' =>$value->problem??NULL,
					'priority' =>$value->priority??NULL,
					'issue' =>$value->issue??NULL,
					'createdby' =>$value->createdby??NULL,
					'assign' =>$value->assign??NULL,
					'assign_by' =>$value->assign_by??NULL,
					'prodcntry' =>$value->prodcntry??NULL,
					'stage' =>$value->stage??NULL,
					'sub_stage' =>$value->sub_stage??NULL,
					'ticket_status' =>$value->ticket_status??NULL,
		];
	}
	}else{
		if($type==1){
			$pdata=[ 'from_created' =>'', 'to_created' =>'', 'source' =>'','filter_checkbox' =>'','subsource' =>'','email' =>'','employee' =>'','datasource' => '','company' =>'','enq_product' => '','phone' =>'','createdby' =>'','assign' =>'','address' =>'','prodcntry' =>'','state' =>'','city' =>'','stage' =>'','top_filter' =>''];
		}else{
			$pdata=[ 'from_created' =>'', 'to_created' =>'','update_from_created' =>'','update_to_created' =>'','source' =>'','problem' =>'','priority' =>'','issue' =>'','createdby' =>'','assign' =>'', 'assign_by' =>'','prodcntry' =>'', 'stage' =>'', 'sub_stage' =>'', 'ticket_status' =>''];
	       }
	}
	return $pdata;

   }
	public function save($companey_id = '', $user_id = '')
	{
		$cid = '';
		//print_r($_POST); 
		if (!empty($companey_id) && !empty($user_id) && !empty($_POST['client_new']) && empty($_POST['client'])) {
			if (isset($_SESSION['process']) && count($_SESSION['process']) == 1) {
				$encode = get_enquery_code();
				$postData = array(
					'Enquery_id' 	=> $encode,
					'comp_id' 		=> $this->session->companey_id,
					'email' 		=> $this->input->post("email", true)??'',
					'phone' 		=> $this->input->post("phone", true),
					'name' 			=> $this->input->post("name", true),
					'company'		=> $this->input->post("client_new", true),
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
			"other"      => (!empty($_POST["relatedto"])  && $_POST["relatedto"] == "Others") ? $this->input->post("otherrel", true) : "",
			"product"	 => ($this->input->post("product", true)) ? $this->input->post("product", true) : "",
			"sourse"     => ($this->input->post("source", true)) ? $this->input->post("source", true) : "",
			"complaint_type" => $this->input->post("complaint_type", true),
			//"coml_date"	 => $ndate,
			//"last_update" => date("Y-m-d h:i:s"),
			"priority"	 => ($this->input->post("priority", true)) ? $this->input->post("priority", true) : "",
			"issue"	 => ($this->input->post("issue", true)) ? $this->input->post("issue", true) : "",
			"tracking_no"   => ($this->input->post("tracking_no", true)) ? $this->input->post("tracking_no", true) : "",
			"referred_by"   => ($this->input->post("referred_by", true)) ? $this->input->post("referred_by", true) : "",

		);
		if (!empty($_FILES["attachment"]["name"]) && $_FILES["attachment"]["size"][0] > 0) {
			$retdata =  $this->do_upload();
			// print_r($retdata);			
			// exit();
			if (!empty($retdata)) {
				if (isset($_POST["ticketno"])) {
					$old_ticket =  $this->db->where(array('id' => $_POST['ticketno']))->get('tbl_ticket')->row();

					if (!empty($old_ticket->attachment)) {	//echo'sdf';
						$new_res = json_decode($old_ticket->attachment);
						$retdata = array_merge($new_res, $retdata);
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
				$this->saveconv($_POST["ticketno"],display('ticket').' Updated', '',$arr["client"],$user_id);
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
			$arr["ticket_status"] = $this->input->post("ticket_status", true);
			$arr['process_id'] =  $_SESSION['process'][0];
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
					"subj"   	=> display('ticket')." Created",
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

		for ($i = 0; $i < sizeof($files['attachment']['name']); $i++) {
			$_FILES['img']['name'] = $files['attachment']['name'][$i];
			$_FILES['img']['type'] = $files['attachment']['type'][$i];
			$_FILES['img']['tmp_name'] = $files['attachment']['tmp_name'][$i];
			$_FILES['img']['error'] = $files['attachment']['error'][$i];
			$_FILES['img']['size'] = $files['attachment']['size'][$i];

			if (!$this->upload->do_upload('img')) {
				$this->session->set_flashdata('error', $this->upload->display_errors());
			} else {
				$done[] = $this->upload->data()['file_name'];
			}
		}
		return $done;
	}


	public function ticket_status($where = 0)
	{
		if ($where)
			$this->db->where($where);

		$this->db->where('company_id',$this->session->companey_id);
		return $this->db->get('tbl_ticket_status');
	}

	public function saveconv($tckno, $subjects, $msg, $client, $user_id, $stage = 0, $sub_stage = 0, $ticket_status = 0,$comp_id=0)
	{
		//echo $comp_id; exit();
		$ticket_status = $this->input->post('ticket_status') ?? $ticket_status;
		//echo $ticket_status; exit();
		$insarr = array(
			"tck_id" => $tckno,
			"comp_id" => $this->session->companey_id??$comp_id,
			"parent" => 0,
			"subj"   => $subjects,
			"msg"    => $msg,
			"attacment" => "",
			"status"  => 0,
			"ticket_status" => $ticket_status,
			"stage"  => $stage,
			"sub_stage"  => $sub_stage,
			"client"   => $client,
			"added_by" => $user_id,
		);
		$ret = $this->db->insert("tbl_ticket_conv", $insarr);
		$last_id = $this->db->insert_id();
		if ($ret) {
			$this->session->set_flashdata('message', 'Successfully saved');
			if ($stage) {
				$this->db->set('tbl_ticket.ticket_stage', $stage);
			}
			if ($sub_stage) {
				$this->db->set('tbl_ticket.ticket_substage', $sub_stage);
			}
			if ($ticket_status) {
				$this->db->set('tbl_ticket.ticket_status', $ticket_status);
			}
			if ($stage || $sub_stage || $ticket_status) {
				$this->db->set('last_update',date('Y-m-d H:i:s'));
				$this->db->where('tbl_ticket.company', $this->session->companey_id??$comp_id);
				$this->db->where('tbl_ticket.id', $tckno);
				$this->db->update('tbl_ticket');
			}
			return $last_id;
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
			->join("tbl_admin as admin", "admin.pk_i_admin_id=cnv.added_by")
			->join("tbl_admin as user", "user.pk_i_admin_id=cnv.assignedTo", 'left')
			->join("tbl_ticket_status status", "cnv.ticket_status = status.id", "LEFT")
			->order_by("cnv.id DESC")
			->get()
			->result();
			//echo $this->db->last_query(); exit();
	}
	public function getall()
	{
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where = '';
		$where .= "( tck.added_by IN (" . implode(',', $all_reporting_ids) . ')';
		$where .= " OR tck.assign_to IN (" . implode(',', $all_reporting_ids) . '))';

		return $this->db->select("tck.*,enq.gender,prd.country_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname ,tbl_admin.s_display_name,tbl_admin.last_name")
			->where($where)
			->where("tck.company", $this->session->companey_id)
			->from("tbl_ticket tck")
			->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT")
			->join("tbl_admin", "tbl_admin.pk_i_admin_id = tck.assign_to", "LEFT")
			->join("tbl_product_country prd", "prd.id = tck.product", "LEFT")
			->order_by("tck.id DESC")
			->group_by("tck.id")
			->get()
			->result();
		//echo $this->db->last_query(); exit();
	}

	public function getTicketListByCompnyID($action='data',$companyid=0,$userid=0,$process=0,$offset=-1,$limit=-1)
	{
		$all_reporting_ids    =   $this->common_model->get_categories($userid);
		$where = '';
		$where .= "( tck.added_by IN (" . implode(',', $all_reporting_ids) . ')';
		$where .= " OR tck.assign_to IN (" . implode(',', $all_reporting_ids) . '))';
		//tck.*
		// $this->db->select("tck.*,enq.gender,prd.country_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname , (SELECT COUNT(*) from tbl_ticket_conv as t2 where t2.tck_id=tck.id) as tconv")
		$this->db->select("tck.id,tck.ticketno,tck.name,tck.company,tck.phone,tck.email,tck.category,tck.status,prd.country_name")
			->where($where)
			->where("tck.company", $companyid)
			->from("tbl_ticket tck")
			// ->join("tbl_ticket_conv cnv", "cnv.tck_id = tck.id", "LEFT")
			->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT")
			->join("tbl_product_country prd", "prd.id = tck.product", "LEFT")
			->order_by("tck.id DESC");

			if(!empty($process))
			{
				$plist = '';
				if(is_array($process))
					$plist= implode(',', $process);
				else
					$plist = $process;
				$this->db->where(" tck.process_id IN (".$plist.")");
			}

		if(!empty($_POST['filters']))
        {
            foreach ($_POST['filters'] as $key => $value)
            {
                if($value==''){
                  unset($_POST['filters'][$key]);
                  if(!count($_POST['filters']))
                    unset($_POST['filters']);
                }

            }
        }

		if(!empty($_POST['filters']))
        {

            $match_list = array('date_from','date_to','update_from','update_to','phone');

            $this->db->group_start();
            foreach ($_POST['filters'] as $key => $value)
            {
              if(in_array($key,$match_list) || $this->db->field_exists($key, 'tbl_ticket'))
              {
                  if(in_array($key, $match_list))
                  {
                      $fld = 'tck.coml_date';
                      // if($type=='2')
                      //   $fld = 'lead_created_date';
                      // else if($type=='3')
                      //   $fld = 'client_created_date';

                      if($key=='date_from')
                        $this->db->where($fld.'>=',$value);

                      if($key=='date_to')
                        $this->db->where($fld.'<=',$value);

                    if($key=='update_from')
                        $this->db->where('last_update>=',$value);

                      if($key=='update_to')
                        $this->db->where('last_update<=',$value);


                      if($key=='phone')
                        $this->db->where('phone LIKE "%'.$value.'%"');
                  }
                  else
                  {
                    if(is_int($value))
                      $this->db->where('tck.'.$key,$value);
                    else
                      $this->db->where('tck.'.$key.' LIKE "%'.$value.'%"');
                  } 
              }
              else
              {
                $this->db->where('1=1');
              }
            }
            $this->db->group_end();
        }



		if($offset!=-1 && $limit!=-1)
	    {  
	        $this->db->limit($limit,$offset);
	    }
	    if($action=='count')
			return $this->db->count_all_results();
		else
			return $this->db->get()->result();
	}

	public function getPrimaryTab()
	{
		 return  $this->db->select('*')
            ->where(array('form_for'=>2,'primary_tab'=>1))
            ->get('forms')
            ->row();
	}

	public function filterticket($where)
	{
		$this->db->select("tck.*,enq.gender,prd.product_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname , stage.lead_stage_name,sub_stage.description,tbl_ticket_status.status_name as ticket_status_name");
		$this->db->where("tck.company", $this->session->companey_id);
		if (!empty($where)) {
			$this->db->where($where);
		}
		$this->db->from("tbl_ticket tck");
		$this->db->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT");
		$this->db->join("tbl_product prd", "prd.sb_id = tck.product", "LEFT");
		$this->db->join("lead_stage stage", "tck.ticket_stage = stage.stg_id", "LEFT");
		$this->db->join("lead_description sub_stage", "tck.ticket_substage = sub_stage.id", "LEFT");
		$this->db->join("tbl_ticket_status", "tbl_ticket_status.id = tck.ticket_status", "LEFT");
		$this->db->order_by("tck.id DESC");
		$this->db->group_by("tck.id");
		$this->db->limit(5);
		return	 $this->db->get()
			->result();
	}


	public function all_related_tickets($where)
	{

		$this->db->select("tck.*,enq.gender,prd.product_name, concat(enq.name_prefix,' ' , enq.name,' ', enq.lastname) as clientname , tbl_ticket_status.status_name as ticket_status_name");
		$this->db->where("tck.company", $this->session->companey_id);

		$this->db->from("tbl_ticket tck");
		$this->db->join("enquiry enq", "enq.enquiry_id = tck.client", "LEFT");
		$this->db->join("tbl_product prd", "prd.sb_id = tck.product", "LEFT");
		$this->db->join("tbl_ticket_status", "tbl_ticket_status.id = tck.ticket_status", "LEFT");
		
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

		return $this->db->select("*")->where(array("comp_id" => $this->session->companey_id, "status" => 1))->order_by('id','DESC')->get("tbl_product_country")->result();
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

	public function get_sub_list($compid = '',$process='')
	{
		if ($compid != '') {
			$this->db->where('comp_id', $compid);
		} else {
			$this->db->where('comp_id', $this->session->userdata('companey_id'));
		}
		if(!empty($process)){
			$this->db->where('FIND_IN_SET('.$process.',process_id)>',0);
		}

		$query = $this->db->get('tbl_ticket_subject');
		return $query->result();
		//echo $this->db->last_query();
		//return 
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
	public function createddatewise($type,$idate,$process='',$user_id='',$comp_id='')
	{
		$process	=	$this->session->process[0]??$process;		
		$user_id    =   $this->session->user_id??$user_id;
		$comp_id    =   $this->session->companey_id??$comp_id;
		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		
		$count = $this->db->where('process_id IN ('.$process.')')->where(array('company'=>$comp_id,'complaint_type'=>$type))->like('coml_date', $idate)->count_all_results('tbl_ticket');
		return $count;
	}
	
	public function getfistDate($comp_id='')
	{
		$comp_id    =   $this->session->companey_id??$comp_id;
		return $this->db->where('company', $comp_id)->limit(1)->get('tbl_ticket')->row()->coml_date;
	}

	public function refferedBy($comp_id='')
	{
		$comp_id    =   $this->session->companey_id??$comp_id;
		return $this->db->where('company_id', $comp_id)->get('tbl_referred_by')->result();
	}
	public function countrefferedBy($rfid,$fromdate,$todate,$process='',$user_id='',$comp_id='')
	{
		$process	=	$this->session->process[0]??$process;	
		$user_id  	=  $this->session->user_id??$user_id;
		$comp_id = $this->session->companey_id??$comp_id;
		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		

		$data= $this->db->where('process_id IN ('.$process.')')->where(array('company' => $comp_id, 'referred_by' => $rfid));

		if($fromdate!='all'){
					
			$data=$this->db->where('last_update >=', $fromdate);
			$data=$this->db->where('last_update <=', $todate);
		}
	                 	// $where .= " DATE(tck.coml_date) <=  '".$to_created."' OR DATE(tck.last_update) <=  '".$to_created."'"; 
						 $data= $this->db->count_all_results('tbl_ticket');
						return $data;
	}
	public function countPriority($type,$fromdate,$todate,$process='',$user_id='',$comp_id='')
	{
		$process	=	$this->session->process[0]??$process;	
		$user_id  	=  $this->session->user_id??$user_id;
		$comp_id = $this->session->companey_id??$comp_id;	
		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		
		$data = $this->db->where('process_id IN ('.$process.')')->where(array('company' => $comp_id, 'priority' => $type));
		
		if($fromdate!='all'){
					
			$data=$this->db->where('last_update >=', $fromdate);
			$data=$this->db->where('last_update <=', $todate);
	                     	}
		$data = $this->db->count_all_results('tbl_ticket');
		return $data;
	}
	public function report_countPriority($type,$fromdate,$todate,$process='',$user_id='',$comp_id='')
	{
		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';
		 $process=$this->session->userdata('process');
		$this->db->where($where);
		if(empty($process) AND $process==''){
			$data = $this->db->where(array('company' => $comp_id, 'priority' => $type));
		}else{
			$data = $this->db->where('process_id IN ('.$process.')')->where(array('company' => $comp_id, 'priority' => $type));
		     }
			$data=$this->db->where('last_update =', $fromdate);
		$data = $this->db->count_all_results('tbl_ticket');

		return $data;
	}
	
	public function complaint_type($type,$fromdate,$todate,$process='',$user_id='',$comp_id='')
	{
		$process	=	$this->session->process[0]??$process;	
		$user_id  	=  $this->session->user_id??$user_id;
		$comp_id = $this->session->companey_id??$comp_id;	

		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		

		$data = $this->db->where('process_id IN ('.$process.')')->where(array('company' => $comp_id, 'complaint_type' => $type));
		if($fromdate!='all'){
					
			$data=$this->db->where('last_update >=', $fromdate);
			$data=$this->db->where('last_update <=', $todate);
	                     	}
						 $data = $this->db->count_all_results('tbl_ticket');
		return $data;
	}
	public function getSourse($comp_id='')
	{
		$comp_id = $this->session->companey_id??$comp_id;
		$stage = $this->db->where(array('comp_id' => $comp_id))->get('lead_source')->result();
		return $stage;
	}
	public function countTSourse($lsid,$fromdate,$todate,$process='',$user_id='',$comp_id='')
	{
		$process	=	$this->session->process[0]??$process;	
		$user_id  	=  $this->session->user_id??$user_id;
		$comp_id = $this->session->companey_id??$comp_id;	

		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		

		$count = $this->db->where('process_id IN ('.$process.')')->where(array('company' => $comp_id, 'sourse' => $lsid));
		if($fromdate!='all'){
			$count=$this->db->where('last_update >=', $fromdate);
			$count=$this->db->where('last_update <=', $todate);
	                     	}
							 $count=$this->db->count_all_results('tbl_ticket');
		return $count;
	}
	public function getstage()
	{
		$stage = $this->db->where(array('comp_id' => $this->session->companey_id, 'stage_for' => 4))->get('lead_stage')->result();
		return $stage;
	}

	public function countTstage($stg_id,$fromdate,$todate,$process='',$user_id='',$comp_id='')
	{
		$process	=	$this->session->process[0]??$process;	
		$user_id  	=  $this->session->user_id??$user_id;
		$comp_id = $this->session->companey_id??$comp_id;	

		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		

		$count = $this->db->where('process_id IN ('.$process.')')->where(array('company' => $comp_id, 'ticket_stage' => $stg_id));
		if($fromdate!='all'){
			$count=$this->db->where('last_update >=', $fromdate);
			$count=$this->db->where('last_update <=', $todate);
	                     	}
							 $count=$this->db->count_all_results('tbl_ticket');
		return $count;
	}
	public function subsource()
	{
		$process	=	$this->session->process[0];

		$this->db->join('lead_stage','lead_stage.stg_id=lead_description.lead_stage_id');
		$this->db->where("FIND_IN_SET($process,lead_stage.process_id)>",0);
		$this->db->where("FIND_IN_SET(4,lead_stage.stage_for)>",0);
		$subsource = $this->db->where(array('lead_stage.comp_id' => $this->session->companey_id))->get('lead_description')->result();
		return $subsource;
	}
	public function send_subsource()
	{
		$process	=	$this->session->userdata('process')[0];	
		$comp_id = $this->session->userdata('companey_id');

		$this->db->join('lead_stage','lead_stage.stg_id=lead_description.lead_stage_id');
		$this->db->where("FIND_IN_SET($process,lead_stage.process_id)>",0);
		$this->db->where("FIND_IN_SET(4,lead_stage.stage_for)>",0);
		$subsource = $this->db->where(array('lead_stage.comp_id' => $comp_id))->get('lead_description')->result();
		return $subsource;
	}

	public function countSubsource($stg_id,$fromdate,$todate)
	{
		$process	=	$this->session->process[0];		
		$all_reporting_ids  = $this->common_model->get_categories($this->session->user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);

		$count = $this->db->where(array('tbl_ticket.process_id' => $process,'tbl_ticket.company' => $this->session->companey_id, 'tbl_ticket.ticket_substage' => $stg_id));
		
		if($fromdate!='all'){
			$count=$this->db->where('last_update >=', $fromdate);
			$count=$this->db->where('last_update <=', $todate);
							 }
							$count= $this->db->count_all_results('tbl_ticket');
		return $count;
	}
	public function send_countSubsource($stg_id,$fromdate,$todate)
	{
		$process	=	$this->session->userdata('process')[0];	
		$comp_id = $this->session->userdata('companey_id');
		$user_id = $this->session->userdata('user_id');
	
		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);

		$count = $this->db->where(array('tbl_ticket.process_id' => $process,'tbl_ticket.company' => $comp_id, 'tbl_ticket.ticket_substage' => $stg_id));
		
		if($fromdate!='all'){
			$count=$this->db->where('date(coml_date) >=', $fromdate);
			$count=$this->db->where('date(coml_date) <=', $todate);
							 }
							$count= $this->db->count_all_results('tbl_ticket');
		return $count;
	}
	public function countproduct_ticket($id,$fromdate,$todate,$process='',$user_id='',$comp_id='')
	{
		$process	=	$this->session->process[0]??$process;	
		$user_id  	=  $this->session->user_id??$user_id;
		$comp_id = $this->session->companey_id??$comp_id;		
		$all_reporting_ids  = $this->common_model->get_categories($user_id);
		
		$where = " ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
		$this->db->where($where);
		

		$count= $this->db->where('process_id IN ('.$process.')')->where(array('product'=>$id,'company'=>$comp_id));
		if($fromdate!='all'){
			$count=$this->db->where('last_update >=', $fromdate);
			$count=$this->db->where('last_update <=', $todate);
							 }
			$count=$this->db->count_all_results('tbl_ticket');
		return $count;

	}
	// tat rule holiday list
	public function get_user_holidays($uid)
	{
		$holidays = array();
		$this->db->where('pk_i_admin_id', $uid);
		$userData = $this->db->get('tbl_admin')->row();
		$state_id = $userData->state_id;
		$city_id = $userData->city_id;
		if ($state_id != 0 or $city_id != 0) {
			$holidays = $this->db->where(array('state' => $state_id, 'city' => $city_id, 'status' => 1))->get('holidays')->result_array();
		}
		$list = array();
		if (!empty($holidays)) {
			foreach ($holidays as $key => $value) {
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

	public function is_tat_rule_executed($tid, $lid)
	{
		if(!empty($lid)){
			$lid = explode(',',$lid);
			$i = 0;			
			foreach($lid as $l){
				$this->db->where('tbl_ticket_conv.tck_id', $tid);
				$this->db->where('tbl_ticket_conv.lid', $l);
				if ($this->db->get('tbl_ticket_conv')->num_rows()) {
					$i++;
				}
			}			
			if(count($lid) == $i){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function insertData($uid, $tid, $lid, $esc_level, $comp_id, $added_by)
	{
		$assign_to_date = date('Y-m-d H:i:s');
		//move to user
		$ticket_update = ['assign_to' => $uid,'assigned_by'=>$added_by,'last_esc'=>$esc_level,'assigned_to_date'=>$assign_to_date];
		$this->db->where(array('id' => $tid))->update('tbl_ticket', $ticket_update);
		$counta = $this->db->where(array('tck_id' => $tid, 'lid' => $lid))->count_all_results('tbl_ticket_conv');
		if ($counta == 0) {
			//save to assignid 	
			$data_msg = ['comp_id' => $comp_id, 'tck_id' => $tid, 'subj' => 'Ticked Assigned','msg'=>$esc_level, 'lid' => $lid, 'assignedTo' => $uid,'added_by'=>$added_by];
			$this->db->insert('tbl_ticket_conv', $data_msg);
		}
	}
	public function insertNextAssignTime($nextAssignment, $tid)
	{
		$ticket_update = ['nextAssignTime' => $nextAssignment];
		$this->db->where(array('id' => $tid))->update('tbl_ticket', $ticket_update);
	}
	public function moveTicketToEnq($id, $enqStatus, $rule_title, $rule_status, $user_id, $stage, $assignto, $process,$source)
	{
		$fetchticket = $this->db->where('id', $id)->get('tbl_ticket');
		if ($fetchticket->num_rows() == 1) {
			foreach ($fetchticket->result() as $key => $value) {
				if ($value->client == 0) {	
					$encode = $this->get_enquery_code();
					$postData = [
						'Enquery_id' => $encode,
						'comp_id' => $this->session->userdata('companey_id'),
						'user_role' => $this->session->user_role,
						'email' => $value->email,
						'phone' => $value->phone,
						'name' => $value->name,
						'enquiry' => 'Enquiry Created by Rule',
						'checked' => 0,
						'ip_address' => $this->input->ip_address(),
						'created_by' => $this->session->user_id,
						'status' => $stage,
						'aasign_to' => $assignto,
						'assign_by' => $user_id,
						'rule_executed' => $rule_title,
						'product_id' => $process,
						'enquiry_source'=>$source
					];
					//
					$this->db->insert('enquiry', $postData);
					 $insert_id = $this->db->insert_id();
					// add model here
					$this->Leads_Model->add_comment_for_events_stage('Enquiry Created', $encode, $stage, 'Enquiry Created by ' . $rule_title . ' Rule', '', 1);
					// add timeline
					$assign_comment = [
						'lead_id' => $encode,
						'comp_id' => $this->session->userdata('companey_id'),
						'comment_msg' => 'Enquiry Assigned',
						'created_by' => $user_id,
						'coment_type' => 0,
						'remark' => 'Enquiry Assigned by ' . $rule_title . ' Rule',
						'assigned_user' => $assignto
					                   ];
					$this->db->insert('tbl_comment', $assign_comment);
					// update @ ticket model
					$this->db->where('id', $id)->set('client', $insert_id)->update('tbl_ticket');
				}
			}
		}
	}

	public function get_enquery_code()
	{
		$this->load->model('enquiry_model');

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


	public function ticket_all_tab_api($companey_id,$ticketno)
	{
		//return array($company_id,$ticketno);
		$this->load->model(array('form_model','Enquiry_model','Leads_Model'));
		$this->session->companey_id = $companey_id;
		
		//2 for Ticket Tab 
		$ticket = $this->get($ticketno);
		//return $ticket;
		$process_id = 0;
		if(!empty($ticket))
		{	
			if($ticket->process_id!=0)
				$process_id = $ticket->process_id;
			else
			{
				$enq=$this->Enquiry_model->getEnquiry(array('enquiry_id'=>$ticket->client));
				if($enq->num_rows())
				{
					$process_id = $enq->row()->product_id; // Process id
				}
			}


		$tab_list = $this->form_model->get_tabs_list($companey_id,$process_id,2);

		$tabs = array();

		$primary_tab=0;
		$primary = $this->getPrimaryTab();

        if($primary)
            $primary_tab = $primary->id;

       	$basic= $this->location_model->get_company_list1_ticket($process_id);

       foreach ($basic as $key => $input)
       {
          switch($input['field_id'])
          { 
            case 15:
            $basic[$key]['input_values'] = array(
                                              array('key'=>'1',
                                                    'value'=>'Is Complaint'),
                                              array('key'=>'2',
                                                    'value'=>'Is Query'),
                                            );
            $basic[$key]['current_value'] = $ticket->complaint_type;
			$basic[$key]['parameter_name'] = 'complaint_type';            
            break;

            case 16:
            $referred_by = $this->Ticket_Model->refferedBy();
           	$values = array();
            foreach ($referred_by as $res)
            {
              $values[] = array('key'=>$res->id,
                                'value'=>$res->name);
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['current_value'] = $ticket->referred_by;
            $basic[$key]['parameter_name'] = 'referred_by';
            break;

            case 17:
            $clients = $this->Enquiry_model->getEnquiry()->result();
            $values = array();
            foreach ($clients as $res)
            {
             
              $values[] =  array('key'=>$res->enquiry_id,
                                'value'=> $res->name." ".$res->lastname
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['current_value'] = $ticket->client;
            $basic[$key]['parameter_name'] = 'client';
            break;

            case 18:
            $basic[$key]['input_values'] = '';
            $basic[$key]['current_value'] = $ticket->name;
            $basic[$key]['parameter_name'] = 'name';
            break;

            case 19:
           
            $basic[$key]['input_values'] = '';
            $basic[$key]['current_value'] = $ticket->phone;
            $basic[$key]['parameter_name'] = 'phone';
            break;

            case 20:
           
            $basic[$key]['input_values'] = '';
            $basic[$key]['current_value'] = $ticket->email;
            $basic[$key]['parameter_name'] = 'email';
            break;

            case 21:
            $products = $this->Ticket_Model->getproduct();
            $values = array();
            foreach ($products as $res)
            {
              $values[] =  array('key'=>$res->id,
                                'value'=> $res->country_name
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['current_value'] = $ticket->product;
            $basic[$key]['parameter_name'] = 'product';
            break;

            case 22:
            $problems = $this->Ticket_Model->get_sub_list();
            $values = array();
            foreach ($problems as $res)
            {
              $values[] = array('key'=>$res->id,
                                'value'=> $res->subject_title
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['current_value'] = $ticket->category;
            $basic[$key]['parameter_name'] = 'relatedto';
            break;

            case 23:
            $natures = $this->Ticket_Model->get_issue_list();
             $values = array();
            foreach ($natures as $res)
            {
              $values[] = array('key'=>$res->id,
                                'value'=> $res->title
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['current_value'] = $ticket->issue;
            $basic[$key]['parameter_name'] = 'issue';
            break;

            case 24:
             $values = array(
                              array('key'=>'1',
                                    'value'=>'Low'),

                              array('key'=>'2',
                                    'value'=>'Medium'),
                              array('key'=>'3',
                                    'value'=>'High')
                            );
            $basic[$key]['input_values'] = $values;
            $basic[$key]['current_value'] = $ticket->priority;
            $basic[$key]['parameter_name'] = 'priority';
            break;

            case 25:
            $source = $this->Leads_Model->get_leadsource_list();
            $values = array();
            foreach ($source as $res)
            {
              $values[] = array('key'=>$res->lsid,
                                  'value'=>$res->lead_name
                                );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['current_value'] = $ticket->sourse;
            $basic[$key]['parameter_name'] = 'source';
            break;

            case 26:
            $reshape = array();
            if($ticket->attachment!='')
            {
            	foreach (json_decode($ticket->attachment) as $key2 => $file)
            	{
            		$reshape[] = array('key'=>null,
            							'value'=>$file,
            						);
            	}
            }
            $basic[$key]['input_values'] = $reshape;
            $basic[$key]['current_value'] = null;
            $basic[$key]['parameter_name'] = 'attachment[]';
            break;

            case 27:
            $basic[$key]['input_values'] = '';
            $basic[$key]['current_value'] = $ticket->message;
            $basic[$key]['parameter_name'] = 'remark';
            break;

            case 28:
            $basic[$key]['input_values'] = '';
            $basic[$key]['current_value'] = $ticket->tracking_no;
            $basic[$key]['parameter_name'] = 'tracking_no';
            break;

          }

      }

      $dynamic = $this->Enquiry_model->get_dyn_fld($ticketno,$primary_tab,2);
      $i=0;
      foreach ($dynamic as $key => $value)
      {
          if(in_array($value['input_type'],array('2','3','4','20')))
          {
             
              $temp  = explode(',', $value['input_values']);
              if(!empty($temp))
              {   $reshape = array();
                  foreach ($temp as $k => $v)
                  {
                    $reshape[] = array('key'=>null,
                                      'value'=>$v);
                  }
                  $dynamic[$key]['input_values'] = $reshape;
              }
          }
          $dynamic[$key]['parameter_name'] = array(
                              array('key'=>($value['input_type']=='8'?'enqueryfiles['.$value['input_id'].']':'enqueryfield['.$value['input_id'].']'),
                                    'value'=>''),
                              array('key'=>'inputfieldno['.$i.']',
                                    'value'=>$value['input_id']),
                              array('key'=>'inputtype['.$i.']',
                                    'value'=>$value['input_type']),
                              );
         $dynamic[$key]['current_value'] = $value['fvalue'];
          $i++;
      }

        $tabs[]  = array('tab_id'=>$primary->id,
        					'title'=>$primary->title,
        					'is_query_type'=>$primary->is_query_type,
        					'is_delete'=>$primary->is_delete,
        					'is_edit'=>$primary->is_edit,
        					'field_list'=>array_merge($basic,$dynamic),
    						);

        $match = array(
			'ticket_no' => $ticket->ticketno,
			'tck.client' => $ticket->client,
			'tck.tracking_no' => $ticket->tracking_no,
			'tck.phone' => $ticket->phone, 
		);



		$related_tickets = $this->Ticket_Model->all_related_tickets($match);

		$heading = array();

		if($this->session->companey_id=='65')
			$heading[] = display('tracking_no');

			$heading[] = 'Ticket Number';
			$heading[] = 'Name';
			$heading[] = 'Type';
			$heading[] = 'Status';

		$tabs[]  = array('tab_id'=>null,
        					'title'=>'Related Tickets',
        					'related_table'=>array("heading"=>$heading,
        									"data"=>$related_tickets,
        									),
    						);


        foreach ($tab_list as $res)
        {
        	if($res['primary_tab']!='1')
        	{
        		$dynamic = $this->Enquiry_model->get_dyn_fld($ticketno,$res['id'],2);
        		$heading = array();
        		$heading_ids = array();
        		$i=0;
        		foreach ($dynamic as $key => $value)
			     {
			          if(in_array($value['input_type'],array('2','3','4','20')))
			          {
			             
			              $temp  = explode(',', $value['input_values']);
			              if(!empty($temp))
			              {   $reshape = array();
			                  foreach ($temp as $k => $v)
			                  {
			                    $reshape[] = array('key'=>null,
			                                      'value'=>$v);
			                  }
			                  $dynamic[$key]['input_values'] = $reshape;
			              }
			          }
			          $heading[] = $value['input_label'];
			          $heading_ids[]  = $value['input_id'];
			          $dynamic[$key]['parameter_name'] = array(
                              array('key'=>($value['input_type']=='8'?'enqueryfiles['.$value['input_id'].']':'enqueryfield['.$value['input_id'].']'),
                                    'value'=>''),
                              array('key'=>'inputfieldno['.$i.']',
                                    'value'=>$value['input_id']),
                              array('key'=>'inputtype['.$i.']',
                                    'value'=>$value['input_type']),
                              );
                $dynamic[$key]['current_value'] = $value['fvalue'];
         				$dynamic[$key]['current_value'] = $value['fvalue'];
         		$i++;
			    }


        		$part = array('tab_id'=>$res['id'],
        					'title'=>$res['title'],
        					'is_query_type'=>$res['form_type'],
        					'is_delete'=>$res['is_delete'],
        					'is_edit'=>$res['is_edit'],
        					'field_list'=>$dynamic,
    						);

        		if($res['form_type']==1)
        		{
        			$tid = $res['id'];
					$comp_id = $companey_id;
					$ticketno = $ticketno;

					$sql  = "SELECT GROUP_CONCAT(concat(`ticket_dynamic_data`.`input`,'#',`ticket_dynamic_data`.`fvalue`,'#',`ticket_dynamic_data`.`created_date`,'#',`ticket_dynamic_data`.`comment_id`) separator ',') as d FROM `ticket_dynamic_data` INNER JOIN (select * from tbl_input where form_id=$tid) as tbl_input ON `tbl_input`.`input_id`=`ticket_dynamic_data`.`input` where `ticket_dynamic_data`.`cmp_no`=$comp_id and `ticket_dynamic_data`.`enq_no`='$ticketno' GROUP BY `ticket_dynamic_data`.`comment_id` ORDER BY `ticket_dynamic_data`.`comment_id` DESC";

			             $sql_res = $this->db->query($sql)->result_array(); 
						$data =array();
			             if(!empty($sql_res))
			             {	
			             	foreach ($sql_res as $key => $value) 
			             	{
			             		$abc = explode(',',$value['d']);
			             		
			             		if(!empty($abc))
			             		{	$sub = array();
			             			foreach ($abc as $k => $v)
			             			{
			             				$x = explode('#', $v);
			             				$sub[] = array(
                                          'input_id'=>$x[0],
                                          'value'=>$x[1],
                                          'updated_at'=>$x[2],
                                          'cmmnt_id'=>$x[3]
                                        );
			             			}
			             		}
			             		$data[] = $sub;
			             	}
			             } 
			        $part['table']=array('heading'=>$heading,
			        					'data'=>$data,
                                      	'heading_ids'=> $heading_ids
                                    );
        		}
        		
        		$tabs[] = $part;
        	}
        }

		session_destroy();
		return $tabs;
		}
		else
			return false;
	}


	public function update_ticket_tab($user_id=0,$comp_id=0)
  	{    
        
        $enquiry_id = $this->input->post('ticketno');
        $tab_id = $this->input->post('tab_id');

        $tab_data = $this->db->where('id',$tab_id)->get('forms')->row();

        $form_type    =   $tab_data->form_type;

        $enqarr = $this->db->select('*')->where('ticketno',$enquiry_id)->get('tbl_ticket')->row();
        $en_comments = $enqarr->ticketno;

        $msg = $tab_data->title.' Updated';


      $comment_id = $this->Ticket_Model->saveconv($enqarr->id,$msg,'',0,$user_id,0,0,0,$comp_id);

        if(!empty($enqarr)){        
            if(isset($_POST['inputfieldno'])) {                    
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);                
                $file_count = 0;                
                $file = !empty($_FILES['enqueryfiles'])?$_FILES['enqueryfiles']:'';                
                foreach($inputno as $ind => $val){
  

                 if ($inputtype[$ind] == 8) {                                                
                        $file_data    =   $this->doupload($file,$file_count,$comp_id);

                        if (!empty($file_data['imageDetailArray']['file_name'])) {
                            $file_path = base_url().'uploads/ticket_documents/'.$comp_id.'/'.$file_data['imageDetailArray']['file_name'];
                            $biarr = array( 
                                            "enq_no"  => $en_comments,
                                            "input"   => $val,
                                            "parent"  => $enqarr->id, 
                                            "fvalue"  => $file_path,
                                            "cmp_no"  => $comp_id,
                                            "comment_id" => $comment_id
                                        );

                            $this->db->where('enq_no',$en_comments);        
                            $this->db->where('input',$val);        
                            $this->db->where('parent',$enqarr->id);
                            if($this->db->get('ticket_dynamic_data')->num_rows()){
                                if ($form_type == 1) {
                                    $this->db->insert('ticket_dynamic_data',$biarr);                                       
                                }else{                                    
                                    $this->db->where('enq_no',$en_comments);        
                                    $this->db->where('input',$val);        
                                    $this->db->where('parent',$enqarr->id);
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
                                      "parent"  => $enqarr->id, 
                                      "fvalue"  => $enqinfo[$val],
                                      "cmp_no"  => $comp_id,
                                      "comment_id" => $comment_id
                                     );                                 
                        $this->db->where('enq_no',$en_comments);        
                        $this->db->where('input',$val);        
                        $this->db->where('parent',$enqarr->id);
                        if($this->db->get('ticket_dynamic_data')->num_rows()){  
                            if ($form_type == 1) {
                                $this->db->insert('ticket_dynamic_data',$biarr);                                       
                            }else{                                                              
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$enqarr->id);
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
        return $this->db->affected_rows();
  }

	public function update_dynamic_query($user_id=0,$comp_id=0)
  	{
    	$ticketno = $this->input->post('ticketno');
    	$cmnt_id = $this->input->post('cmnt_id');
		$tid    =   $this->input->post('tid');
        $form_type    =   $this->input->post('form_type');
        $enqarr = $this->db->select('*')->where('ticketno',$ticketno)->get('tbl_ticket')->row();
        if(empty($enqarr))
        	return 0;
        $en_comments = $enqarr->ticketno;

        $tck_id = $enqarr->id;
        $type = $enqarr->status;
        $user_id = $this->session->user_id??$user_id;
        $comp_id = $this->session->companey_id??$comp_id;
    
       $comment_id = $this->Ticket_Model->saveconv($tck_id,'Details Updated','', $enqarr->client,$user_id,0,0,0,$comp_id);

        if(!empty($enqarr)){        
            if(isset($_POST['inputfieldno'])) {                    
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);                
                $file_count = 0;                
                $file = !empty($_FILES['enqueryfiles'])?$_FILES['enqueryfiles']:'';                
                foreach($inputno as $ind => $val){
	

                 if ($inputtype[$ind] == 8) {                                                
                        $file_data    =   $this->doupload($file,$file_count,$comp_id);

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
        	
        return $this->db->affected_rows();
  		}
  }

  		function doupload($file,$key,$comp_id=0){        
        $upload_path    =   "./uploads/ticket_documents/";
        $comp_id        =   $this->session->companey_id??$comp_id; //creare seperate folder for each company
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

	public function createdTodayCount()
	{
		$all_reporting_ids  = $this->common_model->get_categories($this->session->user_id);
		
		$where='';
		$this->db->from("tbl_ticket");
		$date=date('Y-m-d');
		$where.=" tbl_ticket.coml_date LIKE '%$date%'";
        $where .= " AND ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
        $ticekt_filters_sess    =   $this->session->ticket_filters_sess;        
	    $product_filter = !empty($ticekt_filters_sess['product_filter'])?$ticekt_filters_sess['product_filter']:'';
        if(!empty($this->session->process) && empty($product_filter)){    
	        $arr = $this->session->process;   
	        if (is_array($arr)) {	                 	
	            $where.=" AND tbl_ticket.process_id IN (".implode(',', $arr).')';
	        }         
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND tbl_ticket.product_id IN (".implode(',', $product_filter).')';            
        }
		$this->db->where($where);
		return $this->db->count_all_results();
	}
	public function updatedTodayCount(){
		 $all_reporting_ids  = $this->common_model->get_categories($this->session->user_id);
		
		$where='';
		$this->db->from("tbl_ticket");
		$date=date('Y-m-d');
		$where.=" tbl_ticket.last_update LIKE '%$date%'";
        $where .= " AND ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
        $ticekt_filters_sess    =   $this->session->ticket_filters_sess;        
	    $product_filter = !empty($ticekt_filters_sess['product_filter'])?$ticekt_filters_sess['product_filter']:'';
        if(!empty($this->session->process) && empty($product_filter)){    
	        $arr = $this->session->process;   
	        if (is_array($arr)) {	                 	
	            $where.=" AND tbl_ticket.process_id IN (".implode(',', $arr).')';
	        }         
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND tbl_ticket.product_id IN (".implode(',', $product_filter).')';            
        }
		$this->db->where($where);
		return $this->db->count_all_results();

	}

	public function closedTodayCount(){

		$all_reporting_ids  = $this->common_model->get_categories($this->session->user_id);
		
		$where='';
		$this->db->from("tbl_ticket");
		$date=date('Y-m-d');
		$where.=" tbl_ticket.last_update LIKE '%$date%' and tbl_ticket.status=3";
        $where .= " AND ( tbl_ticket.added_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR tbl_ticket.assign_to IN (".implode(',', $all_reporting_ids).'))';  
        $ticekt_filters_sess    =   $this->session->ticket_filters_sess;        
	    $product_filter = !empty($ticekt_filters_sess['product_filter'])?$ticekt_filters_sess['product_filter']:'';
        if(!empty($this->session->process) && empty($product_filter)){    
	        $arr = $this->session->process;   
	        if (is_array($arr)) {	                 	
	            $where.=" AND tbl_ticket.process_id IN (".implode(',', $arr).')';
	        }         
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND tbl_ticket.product_id IN (".implode(',', $product_filter).')';            
        }
		$this->db->where($where);
		return $this->db->count_all_results();
		return 0;
	}

	public function allTodayCount(){
		
			$a = $this->closedTodayCount();
			$b = $this->updatedTodayCount();
			$c = $this->createdTodayCount();


		return ($a+$b+$c);
	}


	public function TicketDashboardAPI($user_id,$comp_id,$process,$date_from,$date_to)
	{

		$this->load->model(array('common_model','Ticket_Model','Leads_Model'));

		if(empty($date_from))
		{
		$get = $this->Ticket_Model->getfistDate($comp_id);
			$date = date('Y-m-d', strtotime($get));
			if(empty($date_to))
				$date2 = date('Y-m-d');
			else 
				$date2 = $date_to;
			$begin = new DateTime($date);
			$end   = new DateTime($date2);
		}else{

			if(empty($date_to))
				$date2 = date('Y-m-d');
			else 
				$date2 = $date_to;

			 $begin = new DateTime(date('Y-m-d', strtotime($date_from)));
			 $end   = new DateTime(date('Y-m-d', strtotime($date2)));
		     }
		// echo $begin.'<br>'.$end;exit();

		$data = [];
			
			for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
				$idate = $i->format("Y-m-d");
				$count_1 = $this->Ticket_Model->createddatewise(1,$idate,$process,$user_id,$comp_id);				
				$count_2 = $this->Ticket_Model->createddatewise(2,$idate,$process,$user_id,$comp_id);				
				$data[] = [
							'date'  => $idate,
							'complaint'=>$count_1,
							'query'=>$count_2,
						  ];
			}

		$final['datewise']= $data;
		

		$data =array();
		$refData = $this->Ticket_Model->refferedBy($comp_id);
		$datefrom = $date_from==0 ?'all':$date_from;
		//echo $datefrom; exit();
		foreach ($refData as $key => $value) {
			$count = $this->Ticket_Model->countrefferedBy($value->id,$datefrom,$date_to,$process,$user_id,$comp_id);
			$data[] = ['name' => $value->name, 'value' => $count];
		}

		$final['referred_by'] = $data;

		$data =array();
		$low = $this->Ticket_Model->countPriority(1,$datefrom,$date_to,$process,$user_id,$comp_id);
		$medium = $this->Ticket_Model->countPriority(2,$datefrom,$date_to,$process,$user_id,$comp_id);
		$high = $this->Ticket_Model->countPriority(3,$datefrom,$date_to,$process,$user_id,$comp_id);
		$data[] = ['name' => 'High', 'value' => $high];
		$data[] = ['name' => 'Medium', 'value' => $medium];
		$data[] = ['name' => 'Low', 'value' => $low];

		$final['priority_wise'] = $data;

		$data= array();
		$complaint = $this->Ticket_Model->complaint_type(1,$datefrom,$date_to,$process,$user_id,$comp_id);
		$query = $this->Ticket_Model->complaint_type(2,$datefrom,$date_to,$process,$user_id,$comp_id);
		$data[] = ['name' => 'Complaint', 'value' => $complaint];
		$data[] = ['name' => 'Query', 'value' => $query];

		$final['type_wise'] = $data;

		$data=[];

		$all_process = explode(',', $process);

		$getSourse = $this->Leads_Model->get_leadstage_list_byprocess1($all_process,4,$comp_id);
		
		foreach ($getSourse as $key => $value) {
			$count = $this->Ticket_Model->countTstage($value->stg_id,$datefrom,$date_to,$process,$user_id,$comp_id);
			$data[] = ['name' => $value->lead_stage_name, 'value' => $count];
		}
		$final['stage_wise'] = $data;

		$getSourse = $this->Ticket_Model->getSourse($comp_id);
		$data=[];
		foreach ($getSourse as $key => $value) {
			$count = $this->Ticket_Model->countTSourse($value->lsid,$datefrom,$date_to,$process,$user_id,$comp_id);
			$data[] = ['name' => $value->lead_name, 'value' => $count];
		}
		$final['source_wise'] = $data;


		$data =array();
		
		$products=$this->db->where('comp_id',$comp_id)->get('tbl_product_country')->result();
		foreach ($products as $key => $value) {
		$count = $this->Ticket_Model->countproduct_ticket($value->id,$datefrom,$date_to,$process,$user_id,$comp_id);
		$data[] = ['name' => $value->country_name, 'value' => $count];
		}
		$final['product_wise'] = $data;
		return $final; 

	}

	public function get_auto_mail_config()
	{
		$this->db->where('comp_id',$this->session->companey_id);
		$this->db->where('process_id',($this->session->process[0]??0));
		return $this->db->get('ticket_email_config')->row();
	}
}