<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Ticket extends CI_Controller {


    public function __construct()

	{

		parent::__construct();


		$this->load->model(array(

			'Ticket_Model','Client_Model'

		));	

	

	}
	
	public function index(){
		
		$data['title'] = "All Ticket";
		$data["tickets"] = $this->Ticket_Model->getall();
		//print_r($data["tickets"]);die;
		$data['content'] = $this->load->view('ticket/list-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
		
	}
	public function view($tckt = ""){
		
		if(isset($_POST["reply"])){
			
			$this->Ticket_Model->saveconv();
			redirect(base_url("ticket/view/".$tckt), "refresh");
		}
		if(isset($_POST["issue"])){
			
			$this->Ticket_Model->updatestatus();
			redirect(base_url("ticket/view/".$tckt), "refresh");
		}
		
		$data["ticket"] = $this->Ticket_Model->get($tckt);
		$data["conversion"] = $this->Ticket_Model->getconv($data["ticket"]->id);
		if(empty($data["ticket"])){
			show_404();
		}
		
		$data['title'] = "View ";
		$data["problem"] = $this->Ticket_Model->getissues();
		$data['content'] = $this->load->view('ticket/view-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
		
	}
	
	
	
	public function edit($tckt = ""){
		
		if(isset($_POST["product"]))
		{
			
			$res = $this->Ticket_Model->save($this->session->companey_id,$this->session->user_id);
			if($res)
			{
				$this->session->set_flashdata('message', 'Successfully Updated ticket');
            	redirect(base_url('ticket/edit/'.$tckt), "refresh");
			}
		}
		
		$data["ticket"] = $this->Ticket_Model->get($tckt);
		
		if(empty($data["ticket"])){
			show_404();
		}
		
		
		
		$data['title'] = "Edit ";
		$data["conversion"] = $this->Ticket_Model->getconv($data["ticket"]->id);
		$data["clients"] = $this->Ticket_Model->getallclient();
		$data["product"] = $this->Ticket_Model->getproduct();
		$data["problem"] = $this->Ticket_Model->getissues();
		$data["source"] = $this->Ticket_Model->getSource($this->session->companey_id);//getting ticket source list
		$data['content'] = $this->load->view('ticket/edit-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
		
	}
	
	function tdelete(){
		
		$cnt = $this->input->post("content", true);
		
		$this -> db -> where('id', $cnt);
		$this -> db -> delete('tbl_ticket');
		$ret = $this->db->affected_rows();
		$this -> db -> where('tck_id', $cnt);
		$this -> db -> delete('tbl_ticket_conv');
		
		if($ret){
			
			$stsarr = array("status" => "success",
							"message" => "Successfully deleted");
			
		}else{
			$stsarr = array("status" => "failed",
							"message" => "Failed to delete");
			
			
		}
		die(json_encode($stsarr));
	}
	
	public function filter(){
		
		$pst = $this->input->post("top_filter", true);
		
		if($pst == "created_today"){
			
			$where =  " tck.send_date = cast((now()) as date)";
			
		}else if($pst == "updated_today"){
			
			$where =  " tck.last_update	 = cast((now()) as date)";
			
		}else if($pst == "droped"){
			
			$where = array(" tck.status" => 3);	
			
		}else if($pst == "unread"){
			
			$where  =  "tck.status = 0";
			
		} else if($pst == "all"){
			$where = false;
		}else{
			$where = false;
		}
		
		$tickets =  $this->Ticket_Model->filterticket($where);

		
		 if(!empty($tickets)){
			foreach($tickets as $ind => $tck){
				?>
				<tr>
					<td><?php echo $ind + 1; ?></td>
					<td><?php echo $tck->ticketno; ?></td>
					<td><?php echo $tck-> clientname; ?></td>
					<td><?php echo $tck->email ; ?></td>
					<td><?php echo $tck->phone	; ?></td>
					<td><?php echo $tck->	product_name ; ?></td>
					
					<td><?php echo $tck->category ; ?></td>
					<td><?php 
						if($tck->priority == 1){
						?><span class="badge badge-info">Low</span><?php	
						}else if($tck->priority == 2){
						?><span class="badge badge-warning">Medium</span><?php		
						}else if($tck->priority == 2){
							?><span class="badge badge-danger">High</span><?php	
						}
					
					?></td>
					<td><?php echo $tck-> message; ?></td>
					<td><?php echo date("d, M, Y", strtotime($tck->	send_date)); ?></td>
					<td style ="min-width:125px;"><a class="btn  btn-success" href="<?php echo base_url("ticket/view/".$tck->ticketno) ?>"><i class="fa fa-eye" aria-hidden="true"></i>
					<a class="btn  btn-default" href="<?php echo base_url("ticket/edit/".$tck->ticketno) ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					<a class="btn  btn-danger delete-ticket"  data-ticket = "<?php echo $tck->id; ?>" href="<?php echo base_url("ticket/tdelete") ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				
				<?php
				
			}	
		}
		
		
	}
	
	
	public function getmail(){
		
		
	//		'smtp_host' => 'ssl://smtp.googlemail.com',
	//					'smtp_port' => 465,
	//					'smtp_user' => 'admin@digitalanthub.com',
	//					'smtp_pass' => 'Digital@123',
		
		/* connect to gmail */
		
	//	pop.gmail.com

//Requires SSL: Yes

//Port: 995
		
		
		$hostname =  '{imappro.zoho.com:993/imap/ssl}INBOX';
		$username = 'shahnawazbx@gmail.com';
		$password = 'BuX@76543210';
		$username = 'shahnawaz@archizsolutions.com';
		$password = 'Archiz321';
		
	//	$hostname =  '{imap.googlemail.com:993/imap/ssl}INBOX';
	//	$username = 'admin@digitalanthub.com';
	//	$password = 'Digital@123';

		echo "Hello 1";
		
		/* try to connect */
		$inbox = imap_open($hostname,$username ,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		
		echo "<pre>";
			print_r(imap_errors());
		echo "</pre>";
		echo imap_last_error();
		echo "Hello 2";
		/* grab emails */
		$emails = imap_search($inbox,'ALL');

		/* if emails are returned, cycle through each... */
		if($emails) {

			/* begin output var */
			$output = '';

			/* put the newest emails on top */
			rsort($emails);

			/* for every email... */
			foreach($emails as $ind => $email_number) {

				if($ind > 10) break;

				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message  = imap_fetchbody($inbox, $email_number, 1);
				echo "<pre>";
					print_r($message);
				echo "</pre>";
				
				$output.= 'Name:  '.$overview[0]->from.'</br>';
					$output.= 'Email:  '.$overview[0]->message_id.'</br>';



			}

			echo $output;
		} 

		/* close the connection */
		imap_close($inbox);
		
	}
	
	public function add(){
		
		if(isset($_POST["client"])){
			
			$res = $this->Ticket_Model->save($this->session->companey_id,$this->session->user_id);
			if($res)
			{
				$this->session->set_flashdata('message', 'Successfully added ticket');
				//redirect(base_url("ticket/add") , "refresh");
            	redirect(base_url('ticket/index'));
			}
		}
		
		$data['title'] = "Add Ticket";
		$data["clients"] = $this->Ticket_Model->getallclient();
		$data["product"] = $this->Ticket_Model->getproduct();
		$data["problem"] = $this->Ticket_Model->getissues();
		$data["source"] = $this->Ticket_Model->getSource($this->session->companey_id);//getting ticket source list
		$data['content'] = $this->load->view('ticket/add-ticket', $data, true);
		$this->load->view('layout/main_wrapper', $data);
		
	}
	
	public function loadinfo(){
		
		$usr = $this->input->post("clientno", true);
		
		$user = $this->db->select("*")->where("enquiry_id", $usr)->get("enquiry")->row();
		
		if(!empty($user)){
			
			$jarr = array("name"   => $user->name_prefix." ".$user->name." ".$user->lastname, 
						  "email"  => $user->email,
						  "phone"  => $user->phone);
			
			die(json_encode($jarr));
		}
	
	}
	
	public function loadamc(){
		
		$prodno = $this->input->post("product", true);
		
		$enqno  = $this->input->post("client", true);
		
		$amcarr = $this->db->select("*")->where(array("product_name" => $prodno, "enq_id" => $enqno))->get("tbl_amc")->row();
		
		$amc = array();
		if(!empty($amcarr)){
			
			$amcarr = array("status" 	  => "found",
							"from_date"   => date("d, M Y ", strtotime($amcarr->amc_fromdate)),
							"to_date"     => date("d, M Y ", strtotime($amcarr->amc_todate))
							);
		}else{
			$amcarr = array("status" => "Not Found");
		}
		die(json_encode($amcarr));
	}
	
	public function loadoldticket($prd = ""){
		
		$data['oldticket'] = $this->db->select("*")->where("product", $prd)->where("company", $this->session->companey_id)->get("tbl_ticket")->result();
		
		$this->load->view("ticket/page/tck-table",$data);
	}

	public function addproblems($prblm = ""){
		
		$this->saveticket();
		
		if(empty($prblm)) {
			
			$data['title'] = "Add Problems";
			$data["problem"] = $this->Ticket_Model->getissues();
			
			$data['content'] = $this->load->view("ticket/page/problem-list", $data, true);
		}else{
			
			$data["eproblem"] = $this->db->select("*")->where("cmp", $this->session->companey_id)->where("id", $prblm)->get("tck_mstr")->row();
			
			$data['content'] = $this->load->view("ticket/page/problem-list", $data, true);
			
		}
		
		$this->load->view('layout/main_wrapper', $data);
		
	}
	
	public function saveticket(){
		
		if(isset($_POST["problem"])){
			
		
			
			if(isset($_POST["problemno"])){
				
				$updarr = array("title" 	=> $this->input->post("problem"),
									);
				$prblm  = $this->input->post("problemno", true);					
									
				$this->db->where("id", $prblm);
				$this->db->update("tck_mstr", $updarr);	
				$this->session->set_flashdata('message', 'Successfully Updated Problem');
				redirect(base_url("ticket/addproblems.html/"), "refresh");
				
			}else{
				$insarr = array("title" 	=> $this->input->post("problem"),
						"cmp"   	=> $this->session->companey_id,
						"added_by"	=> $this->session->user_id
						);
				$this->db->insert("tck_mstr", $insarr);
				redirect(base_url("ticket/addproblems.html"), "refresh");
				$this->session->set_flashdata('message', 'Successfully added Problem');
			}
			
			
		}
		
	}

}

