<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Led extends CI_Controller {
    public function __construct() {
        parent::__construct();
       
         $this->load->model(
                array('Leads_Model','User_model','dash_model','enquiry_model','report_model','Ticket_Model','rule_model')
                );
        $this->load->library('email');
		$this->load->library('pagination');
        $this->load->library('user_agent');
        if (empty($this->session->user_id)) {
            redirect('login');
        }
    }
   	public function index() { 
        $this->session->unset_userdata('enquiry_filters_sess');
        $process_id = $this->session->userdata('process');
        if (user_role('70') == true) {}  
         if(!empty($this->session->enq_type)){
			$this->session->unset_userdata('enq_type',$this->session->enq_type);
		}		
        $this->load->model('Datasource_model'); 
        $data['title'] = display('lead_list');
		$data['user_list'] = $this->User_model->companey_users();
        $data['products'] = $this->dash_model->get_user_product_list();        
        $data['sourse'] = $this->report_model->all_source();
		$data['datasourse'] = $this->report_model->all_datasource();
        $data['drops'] 		= $this->enquiry_model->get_drop_list();			 
		$data['all_stage_lists'] = $this->Leads_Model->get_leadstage_list_byprocess1($this->session->process,array(1,2,3));
		
		if(!empty($_GET) && !empty($_GET['desposition'])){
            $desp = $this->db->where('stg_id',$_GET['desposition'])->get('lead_stage')->row();        
			$data['desp'] = $desp;			
			$this->session->set_userdata('enquiry_filters_sess',array('stage'=>$_GET['desposition']));
		}
		
		$data['lead_score'] = $this->enquiry_model->get_leadscore_list();	
		$data['created_bylist'] = $this->User_model->read();	
		$data['data_type'] = 2;
		$data['state_list'] = $this->enquiry_model->get_user_state_list();
        $data['city_list'] = $this->enquiry_model->get_user_city_list();
		$data['dfields']  = $this->enquiry_model-> getformfield();
		$data['subsource_list'] = $this->Datasource_model->subsourcelist();	
		$data['filterData'] = $this->Ticket_Model->get_filterData(1);
		$data['lead_score'] = $this->Leads_Model->get_leadscore_list();
		$data['aging_rule'] = $this->rule_model->get_rules(array(11));
		$this->load->model('Branch_model');
        $data['branch_lists']=$this->Branch_model->all_sales_branch();
		$data['region_lists']=$this->Branch_model->all_sales_region();
		$data['area_lists']=$this->Branch_model->all_sales_area();
		$data['dept_lists']=$this->User_model->all_sales_dept();		
        $data['content'] = $this->load->view('enquiry_n', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function get_leadstage_list_byprocess(){
    	$id = $this->input->post('id');
        
    	$res = $this->Leads_Model->get_leadstage_list_byprocess1($id);
    	
    	$radio='';
    	if($res){
    		foreach ($res as $result) {
    			$radio .= "<input type='radio' value='".$result->stg_id."' id='".$result->lead_stage_name."' name='lead_stages'><label>".$result->lead_stage_name. "</label>";
    		
    		}
    	}
        
    	 echo $radio;
    	 exit();
    }
    
	   	public function stages_of_enq(){
	      $data['all_enquery_num'] = $this->Leads_Model->all_leadss()->num_rows();
          $data['all_drop_num'] = $this->Leads_Model->all_drop_lead()->num_rows();
          $data['all_active_num'] = $this->Leads_Model->all_Active_lead('0', '*')->num_rows();
          $data['all_today_update_num'] = $this->Leads_Model->all_Updated_today()->num_rows();
          $data['all_creaed_today_num'] = $this->Leads_Model->all_created_today()->num_rows();
	      echo json_encode($data);
		}
		
		public function lead_by_stage($satge='',$record='') {			
	    $recordPerPage =30;
		if($record != 0){
			$record = ($record-1) * $recordPerPage;
		}  
		$data['all_active'] = $this->Leads_Model->lead_by_stage('0', '*',$satge);
		/*echo "<pre>";
		echo $this->db->last_query();
		echo "</pre>";*/
        $recordCount = $data['all_active']->num_rows();
		$empRecord = $this->Leads_Model->lead_by_stage($record,$recordPerPage,$satge);
      	$config['base_url'] = base_url().'led/lead_by_stage/'.$satge;
      	$config['use_page_numbers'] = TRUE;
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['total_rows'] = $recordCount;
		$config['per_page'] = $recordPerPage;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['empData'] = $empRecord->result_array();
	 	echo json_encode($data);
	}
	
 
}
