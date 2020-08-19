<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Chat extends CI_Controller {
	public function __construct(){
		parent::__construct();	
		$this->load->model('enquiry_model');	
	}

	public function index($comp_id=0,$created_by=154){
		$data['comp_id'] = $comp_id;
		$data['created_by'] = $created_by;
		if ($comp_id) {
			$this->load->view('chats/chatbot',$data);
		}else{
			echo "Something went wrong with chat!";
		}
	}
	public function agent_chat(){		
		$data['title'] = 'Chat';
		$data['content'] = $this->load->view('chats/agent_chat',$data,true);	
        $this->load->view('layout/main_wrapper', $data);
	}
	public function submit_identity($comp_id,$created_by,$process_id=2){
		
		$name	=	$this->input->post('name');
		$mobile	=	$this->input->post('mobile');
		$email	=	$this->input->post('email');

		$where = " comp_id=".$comp_id;
		$where .= " AND (phone=".$mobile;
		$where .= " OR email='".$email.'\')';

		if ($process_id) {
			$where .= ' AND product_id='.$process_id;
		}
		$row	=	$this->enquiry_model->is_enquiry_exist($where);
		if (!empty($row)) {			
			$res  = $row['Enquery_id'];
			$this->session->set_userdata('user_id',$res);
			$this->session->set_userdata('fullname',$row['name'].' '.$row['lastname']);
			$this->session->set_userdata('companey_id',$row['comp_id']);
		}else{

			$name	=	explode(' ', $name);
			
			$fname  	= !empty($name[0])?$name[0]:'';
			$last_name  = !empty($name[1])?$name[1]:'';

			$curl = curl_init();
			$api_url = base_url()."api/enquiry/create";
			curl_setopt_array($curl, array(
			  	CURLOPT_URL => $api_url,
			  	CURLOPT_RETURNTRANSFER => true,
			  	CURLOPT_ENCODING => "",
			  	CURLOPT_MAXREDIRS => 10,
			  	CURLOPT_TIMEOUT => 0,
			  	CURLOPT_FOLLOWLOCATION => true,
			  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  	CURLOPT_CUSTOMREQUEST => "POST",
			  	CURLOPT_POSTFIELDS => array('fname' => $fname,'lastname' => $last_name,'email' => $email,'mobileno' => $mobile,'company_id' => $comp_id,'process_id' => $process_id,'user_id' => 154),
			  	CURLOPT_HTTPHEADER => array(
			    	"Cookie: ci_session=3ba7d4lq4alv2pgpq3sc8t2ojrh41s04"
			  	),
			));
			$response = curl_exec($curl);		
			curl_close($curl);	

			$row	=	$this->enquiry_model->is_enquiry_exist($where);	
			//echo $this->db->last_query();
			//var_dump($response);
			//var_dump($api_url);
			if (!empty($row)) {			
				$res  = $row['Enquery_id'];
				$this->session->set_userdata('user_id',$res);
				$this->session->set_userdata('fullname',$row['name'].' '.$row['lastname']);
				$this->session->set_userdata('companey_id',$row['comp_id']);
			}

		}
		echo $this->session->user_id;
	}
}