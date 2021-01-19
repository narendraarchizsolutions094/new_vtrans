<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configurations extends CI_Controller {
    public function __construct()
	{
		parent::__construct();
		
		$this->load->library('user_agent');
            //$this->load->helper('url');
		
		$this->load->model(array(
			'Configuration_Model','enquiry_model','Message_models','User_model','dash_model','location_model'
		));
		$this->load->library('email'); 
		$this->load->library('user_agent');
	}
    
         
	public function index()
	{	
        if (user_role('514') == true) {}
        
        if(empty($this->session->user_id)){
		 redirect('login');   
		}
		$data['nav1']='nav6';
		$aid = 9;		
		$data['title'] = 'Website Integration'  ;	
		$data['web_intergrationlist'] = $this->Configuration_Model->get_integration_list($aid);
	    $data['user_list'] = $this->User_model->read();
		$data['content'] = $this->load->view('website',$data,true);
		//$this->load->view('leads',$data);
		$this->load->view('layout/main_wrapper',$data);
	}
	
	
	public function qr_code()
	{
        if (user_role('516') == true) {}
        $aid = 10;
		$data['title'] = 'QR-Code Integration'  ;	
		$data['web_intergrationlist'] = $this->Configuration_Model->get_integration_list($aid);
	    $data['user_list'] = $this->User_model->read();
	    $data['user_listss'] = $this->User_model->all_lists();
		$data['content'] = $this->load->view('qr_code',$data,true);
	
		$this->load->view('layout/main_wrapper',$data);
	}
	
	
        public function createwebsiteintegration()
        {
            if (user_role('512') == true) {
            } 
        if(!empty($_POST)){
        
        $integration_name = $this->input->post('integration_name');
        $source_name = $this->input->post('source_name');
        $assign = $this->input->post('assign');
        $createdate = date('d-m-Y h:i:s a');
        $crtdby = $this->session->user_id;
        
        $data = array(
        'integration_name' => $integration_name,
        'source_name' => $source_name,
        'comp_id' => $this->session->companey_id,
        'assign_by' => $assign,
        'created_date' => $createdate,
        'status' => 1,
        'web_created_by'=> $crtdby,
        'integration_type' => 9
        
        
        );
        
        $insert_id = $this->Configuration_Model->website_integrate($data);
        $lastID = $this->db->insert_id();
        $captureLINK = base_url()."configurations/website_form/".base64_encode($lastID);
        
          
            $this->db->set('capture_link',$captureLINK);
            $this->db->where('wid',$lastID);
            $this->db->update('website_integration');
        
        $this->session->set_flashdata('SUCCESSMSG',base_url()."configurations/website_form/???".base64_encode($source_name)."/???".base64_encode($assign)."/???9"."/???".base64_encode($crtdby));
        //$this->session->set_flashdata('SUCCESSMSG','Website Integrate Added Successfully');
        redirect('configurations');
        
        }
        }
        
        
           
        public function website_form(){
        
            if(!empty($_POST)){                          
                // if($)

                $qr_code_id    =   $this->input->post('qr_code_id');  
                $wid    =   $this->input->post('wid');  
            //   print_r($_POST);
            //   die();

               if($wid!='Mg=='){
                $this->form_validation->set_rules('e_email','Email','required|max_length[100]|valid_email');
                }   
        		$this->form_validation->set_rules('e_mobile',display('mobileno')  ,'max_length[20]|required');            
                if ($this->form_validation->run() === false) {
                    $this->session->set_flashdata('SUCCESSMSG',validation_errors());
                    redirect($this->agent->referrer());
            }
            $crtdby = $this->input->post('create_dby');
            
            $this->db->where('wid',$qr_code_id);
            $qr_row    =   $this->db->get('website_integration')->row_array();
            if($qr_row){
 
            $compid = $qr_row['comp_id'];
            $assign = $qr_row['assign_by'];
            }
 
            $encode=$this->get_enquery_code();
            $createdate = date('d-m-Y h:i:s a');
            $name1=$this->input->post('e_name');
            if($wid=='Mg=='){
                
                $data = array(
                    'Enquery_id'  => $encode,
                    'comp_id'     => $compid,
                    'phone' 	  => $this->input->post('e_mobile'),
                    // 'name' 		  => $this->input->post('e_name'),
                    'aasign_to'   => $assign,
                    'other_phone' =>$this->input->post('other_no'),
                    'enquiry' 	  => $this->input->post('e_requirements'),
                    'product_id'  => $this->input->post('product_id'),
                    'created_by'  => $crtdby,
    				'country_id'  => $this->input->post('country_id'),
                    'created_date'=> date('Y-m-d H:i:s'),
                    'qr_code_id'  =>  $qr_code_id,
                    'enquiry_source' => 10,
                    'ip_address' => $this->input->ip_address(),
                    'status'     => 1
            );
                // $insert_id = $this->Configuration_Model->web_enquiry($data);
                 $this->db->insert('enquiry', $data); 
                $insid = $this->db->insert_id();
                if(isset($_POST['inputfieldno'])) {
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);
                    foreach($inputno as $ind => $val){
                        $biarr[] = array( 
                                        "enq_no"  => $data["Enquery_id"],
                                          "input"   => $val,
                                          "parent"  => $insid, 
                                          "fvalue"  => (!empty($enqinfo[$ind])) ? $enqinfo[$ind] : "",
                                          "cmp_no"  => $compid,
                                         ); 	
                    }
                
                    if(!empty($biarr)){
                        $this->db->insert_batch('extra_enquery', $biarr); 
                    }
                }
                $this->session->set_flashdata('popup','Your Enquiry has been sent Successfully');
                 redirect($this->agent->referrer());
            }else{
            $data = array(
                    'Enquery_id'  => $encode,
                    'comp_id'     => $compid,
                    'email' 	  => $this->input->post('e_email'),
                    'phone' 	  => $this->input->post('e_mobile'),
                    'name' 		  => $this->input->post('e_name'),
                    'aasign_to'   => $assign,
                    'enquiry' 	  => $this->input->post('e_requirements'),
                    'product_id'  => $this->input->post('product_id'),
                    'created_by'  => $crtdby,
    				'country_id'  => $this->input->post('country_id'),
                    'created_date'=> date('Y-m-d H:i:s'),
                    'qr_code_id'  =>  $qr_code_id,
                    'enquiry_source' => 10,
                    'ip_address' => $this->input->ip_address(),
                    'status'     => 1
            );
            $insert_id = $this->Configuration_Model->web_enquiry($data);
            $this->session->set_flashdata('popup','Your Enquiry has been sent Successfully');
             redirect($this->agent->referrer());
            }
        }
            $data['title'] = 'Capture';	
            /*$data['state_list']=$this->enquiry_model->state_list();*/
            /*$data['customer_types'] = $this->enquiry_model->customers_types();*/
            //$data['channel_p_type'] = $this->enquiry_model->channel_partner_type_list();
            $wid = $this->uri->segment(3);
            $data['wid']=$wid;
            $wid = base64_decode($wid);     
            $data['products'] = $this->dash_model->product_list_byqr_comp($wid);
            // print_r($data['products']);exit();
            $data['country_list']=$this->location_model->country();            
        
           
            // echo $wid ;exit();      
            $this->db->where('wid',$wid);            
            $data['qr_row']    =   $this->db->get('website_integration')->row_array();
            // print_r($data);
            $this->load->view('web_integrationform',$data);
        }   
        
        
        
        
        //////////////// QR CODE //////////////////////////
        
        public function create_qr_code(){
            if (user_role('516') == true) {
            }
            if(!empty($_POST)){            
                $integration_name = $this->input->post('integration_name');
                $source_name = $this->input->post('source_name');
                $type = $this->input->post('qr_code_type');
                $assign = $this->input->post('assign');
                $crtdby = $this->session->user_id;
                $createdate = date('d-m-Y h:i:s a');                
                
                $data = array(
                    'integration_name' => $integration_name,
                    'source_name' => $source_name,
                    'assign_by' => $assign,
                    'created_date' => $createdate,
                    'status' => 1,
                    'web_created_by'=> $this->session->user_id,
                    'comp_id'=> $this->session->companey_id,
                    'integration_type' => 10,
                    'type'=>$type
                );   
                $insert_id = $this->Configuration_Model->website_integrate($data);
                $lastID = $this->db->insert_id();                
                $captureLINK = base_url()."configurations/website_form/".base64_encode($lastID);
                $this->db->set('capture_link',$captureLINK);
                $this->db->where('wid',$lastID);
                $this->db->update('website_integration');                    
                $this->session->set_flashdata('message','Created Successfully');             
                redirect('configurations/qr_code');            
            }
        }
        
        
        
///////////////////////////////////PORTAL INTEGRATION//////////////////////////////
        
        	
	public function indiamart()
	{
	    $aid = 1;
		$data['title'] = 'IndiaMart Integration'  ;	
		$data['web_intergrationlist'] = $this->Configuration_Model->get_portalintegration_list($aid);
	    $data['user_list'] = $this->User_model->read();
		$data['content'] = $this->load->view('indiamart',$data,true);
	
		$this->load->view('layout/main_wrapper',$data);
	}
	
	
	public function tradeindia()
	{
	    $aid = 2;
		$data['title'] = 'Tradeindia Integration'  ;	
		$data['web_intergrationlist'] = $this->Configuration_Model->get_portalintegration_list($aid);
	    $data['user_list'] = $this->User_model->read();
		$data['content'] = $this->load->view('tradeindia',$data,true);
	
		$this->load->view('layout/main_wrapper',$data);
	}
	
	public function justdial()
	{
	    $aid = 3;
		$data['title'] = 'Justdial Integration'  ;	
		$data['web_intergrationlist'] = $this->Configuration_Model->get_portalintegration_list($aid);
	    $data['user_list'] = $this->User_model->read();
		$data['content'] = $this->load->view('justdial',$data,true);
	
		$this->load->view('layout/main_wrapper',$data);
	}
public function facebook()
	{
	    $aid = 4;
		$data['title'] = 'facebook Integration'  ;	
		$data['web_intergrationlist'] = $this->Configuration_Model->get_portalintegration_list($aid);
	    $data['user_list'] = $this->User_model->read();
		$data['content'] = $this->load->view('facebook',$data,true);
	
		$this->load->view('layout/main_wrapper',$data);
	}
	public function linkedin()
	{
	    $aid = 5;
		$data['title'] = 'linkedin Integration'  ;	
		$data['web_intergrationlist'] = $this->Configuration_Model->get_portalintegration_list($aid);
	    $data['user_list'] = $this->User_model->read();
		$data['content'] = $this->load->view('linkedin',$data,true);
	
		$this->load->view('layout/main_wrapper',$data);
	}
        
        
        public function createportalintegration()
        {
        if(!empty($_POST)){
        
        $integration_name = $this->input->post('integration_name');
        $source_name = $this->input->post('source_name');
        $assign = $this->input->post('assign');
        $createdate = date('d-m-Y h:i:s a');
        $portal_type = $this->input->post('portal_type');
        $primary_number = $this->input->post('primary_number');
        $p_key = $this->input->post('key');
        $crtdby = $this->session->user_id;
        $p_userid = $this->input->post('userid');
        $p_profileid = $this->input->post('user_profileid');
        
       
        $data = array(
        'p_integration_name' => $integration_name,
        'p_source' => $source_name,
        'p_key' => $p_key,
        'p_primaryno' => $primary_number,
        'p_assignto' => $assign,
        'p_created_date' => $createdate,
        'portal_type'=> $portal_type,
        'p_userid' => $p_userid,
        'p_profileid' => $p_profileid
        );
        
        $insert_id = $this->Configuration_Model->portal_integrate($data);
        
        redirect($this->agent->referrer());
        
        }
        }
        
        public function delete_portalintegration($pintegration = null) 
        { 
        if ($this->Configuration_Model->delete_portalintegration($pintegration)) {
        #set success message
        $this->session->set_flashdata('message',display('delete_successfully'));
        } else {
        #set exception message
        $this->session->set_flashdata('exception',display('please_try_again'));
        }
        redirect($this->agent->referrer());
        }
/////////////////////////////////////////////////////////////////////////////////////////        
        
//////////////////////////////////////////////////////////////
 public function delete_integration($integration = null) 
        { 
            if (user_role('515') == true) {
            }
        if ($this->Configuration_Model->delete_integration($integration)) {
        #set success message
        $this->session->set_flashdata('message',display('delete_successfully'));
        } else {
        #set exception message
        $this->session->set_flashdata('exception',display('please_try_again'));
        }
        redirect($this->agent->referrer());
        }
    
	   public function get_enquery_code()
        {
            $code = $this->genret_code();
           $code2='ENQ'.$code;
            $response = $this->enquiry_model->check_existance($code2);
            if ($response) {
                    $this->get_enquery_code();
               } else {
                   return  $code2;
                   exit;
               }
               exit;
        }
         function genret_code() {
            $pass = "";
            $chars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
            for ($i = 0; $i < 4; $i++) {
                $pass .= $chars[mt_rand(0, count($chars) - 1)];
            }
            return $pass;
        }
	
}
