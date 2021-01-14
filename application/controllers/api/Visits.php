<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Visits extends REST_Controller {
  function __construct() 
  {
      parent::__construct();
      $this->load->library('form_validation');
	  $this->load->model(array('enquiry_model','common_model'));
  }

  	public function visit_list_page_post()
    {
      $user_id= $this->input->post('user_id');
      $process_id= $this->input->post('process_id');
      $company_id = $this->input->post('company_id');
      $offset = $this->input->post('offset')??0;
      $limit = $this->input->post('limit')??10;

      if(strpos(',',$process_id) !== false) 
      {
        $process = implode(',',$process_id);
      }
      else
      {
        $process = $process_id;
      }


       $res= array();
    
        $total = $this->enquiry_model->visit_list_api($company_id,$user_id,$process)->num_rows();

        $data['result'] = $this->enquiry_model->visit_list_api($company_id,$user_id,$process,$limit,$offset);
                  
          if(!empty($data['result']->result()))
          {
            $res= array();
            
            $res['offset'] = $offset;
            $res['limit'] = $limit;
            $res['total'] = $total;
            $res['list'] = $data['result']->result();

            $this->set_response([
                'status' => TRUE,
                'data' =>$res
                 ], REST_Controller::HTTP_OK);
          }   
		else
         {
	    
	        $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
	      }
    }


    public function visit_details_post()
    {
    	$id = $this->input->post('visit_id');

    	$value = $this->db->where('id',$id)->get('tbl_visit')->row();

    	if(!empty($value))
    	{
    		 $this->set_response([
                'status' => TRUE,
                'data' =>$value
                 ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		 $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
    	}
    }

    public function delete_visit_post()
    {
    	$visit_id = $this->input->post('visit_id');
    	$comp_id = $this->input->post('company_id');
    	$enquiry_code = $this->input->post('enquiry_code');
    	$user_id = $this->input->post('user_id');
    	$this->form_validation->set_rules('visit_id','visit_id','required|trim');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_code','enquiry_code','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->db->where(array('id'=>$visit_id,'comp_id'=>$comp_id));
    		$this->db->delete('tbl_visit');

    		if($this->db->affected_rows())
    		{
	    		$this->load->model('Leads_Model');
	        	$this->Leads_Model->add_comment_for_events('Visit Deleted.',$enquiry_code,0,$user_id);

    			$this->set_response([
                  'status' => true,
                  'message' =>'Deleted Successfully.',
               ], REST_Controller::HTTP_OK);
    		}
    		else
    		{
    			$this->set_response([
                  'status' => false,
                  'message' =>'Visit Not found',
               ], REST_Controller::HTTP_OK);
    		}
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		  }

    }

    public function save_visit_post()
    {
    	$visit_id = $this->input->post('visit_id');
    	$comp_id = $this->input->post('company_id');
    	$enquiry_id = $this->input->post('enquiry_id');
    	$user_id = $this->input->post('user_id');

    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_id','enquiry_id','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->load->model(array('Client_Model','Enquiry_model','Leads_Model'));

    		$data = array(
                            'visit_date'=>$this->input->post('visit_date'),
                            'visit_time'=>$this->input->post('visit_time'),
                            'travelled'=>$this->input->post('travelled'),
                            'travelled_type'=>$this->input->post('travelled_type'),
                            'rating'=>$this->input->post('rating'),
                            'next_date'=>$this->input->post('next_visit_date'),
                            'next_location'=>$this->input->post('next_location'),
                            'comp_id'=>$comp_id,
                            'user_id'=>$user_id,
                        );
    		$done = 0;
            $res = $this->db->where(array('enquiry_id'=>$enquiry_id))->get('enquiry')->row();
            if(!empty($res))
            {	
            	if(!empty($visit_id))
	            {
	            	$this->db->where('id',$visit_id)->update('tbl_visit',$data);
	            	$this->Leads_Model->add_comment_for_events('Visit Updated',$res->Enquery_id,0,$user_id);
	            }
	            else
	            {	$data['enquiry_id'] = $enquiry_id;
	            	$this->Client_Model->add_visit($data);
	            	$this->Leads_Model->add_comment_for_events('Visit Added',$res->Enquery_id,0,$user_id);
	            }
	            $done = 1;
            }	

            if($done)
            {
            	
            	$this->set_response([
                  'status' => true,
                  'message' =>'Saved Successfully.',
               ], REST_Controller::HTTP_OK);
			}
            else
            {
				$this->set_response([
                  'status' => FALSE,
                  'message' =>'Unable to Save.',
               ], REST_Controller::HTTP_OK);
            }
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		  }

    }

    // public function for_data_list_post()
    // {

    // 	$this->db->select('tbl_visit.*,enquiry.name');
    //     $this->db->from($this->table);
    //     $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
    //     $this->db->where("tbl_visit.comp_id",$this->session->companey_id);

    //     $where="";
    //     $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    //     $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
    // }
}
