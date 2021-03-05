<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Contacts extends REST_Controller {
  function __construct() 
  {
      parent::__construct();
      $this->load->library('form_validation');
	  $this->load->model(array('Common_model','Client_Model'));
  }

  public function contact_by_post()
  {
      $this->form_validation->set_rules('by','by','required');
      $this->form_validation->set_rules('key','key','required');
      $this->form_validation->set_rules('comp_id','comp_id','required');
     

      if($this->form_validation->run())
      {
        $by = $this->input->post('by');
        $key  = $this->input->post('key');
        $comp_id = $this->input->post('comp_id');
        // $enquiry_id = $this->input->post('enquiry_id');
        if($by=='account')
        {
            $res =  $this->db->where('client_id',$key)->where('comp_id',$comp_id)->get('tbl_client_contacts');
        }
        else if($by=='company')
        {
            $res =  $this->db->select('con.*')
                                ->from('tbl_client_contacts con')
                            ->join('enquiry','enquiry.enquiry_id=con.client_id','left')
                            ->where('enquiry.company='.$key)
                            ->where('con.comp_id',$comp_id)->get();
        }
        $res = $res->result();
        if(!empty($res))
        {
            $this->set_response([
            'status' => true,
            'data' =>$res,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
          $this->set_response([
            'status' => false,
            'msg' =>'No Data',
            ], REST_Controller::HTTP_OK);
        }
      }
      else
      {
         $this->set_response([
            'status' => false,
            'msg' =>'Key or By is Empty',
            ], REST_Controller::HTTP_OK);
      }
  }

  	public function contacts_list_page_post()
    {
      $user_id= $this->input->post('user_id');
      $company_id = $this->input->post('company_id');
      $offset = $this->input->post('offset')??0;
      $limit = $this->input->post('limit')??10;

       $res= array();
    
        $total = $this->Client_Model->getContactList(array(),'count',$company_id,$user_id);

        $data['result'] = $this->Client_Model->getContactList(array(),'data',$company_id,$user_id,$limit,$offset);
                  
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


    public function contact_details_post()
    {
    	$id = $this->input->post('contact_id');

    	$value = $this->db->where('cc_id',$id)->get('tbl_client_contacts')->row();

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

    public function delete_contact_post()
    {
    	$cc_id = $this->input->post('contact_id');
    	$comp_id = $this->input->post('company_id');
    	$enquiry_id = $this->input->post('enquiry_id');
    	$user_id = $this->input->post('user_id');
    	$this->form_validation->set_rules('contact_id','contact_id','required|trim');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_id','enquiry_id','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->db->where(array('cc_id'=>$cc_id,'comp_id'=>$comp_id,'client_id'=>$enquiry_id));
    		$this->db->delete('tbl_client_contacts');

    		if($this->db->affected_rows())
    		{
          $res=  $this->db->select('Enquery_id')->where('enquiry_id',$enquiry_id)->get('enquiry')->row();
          if(!empty($res))
          {
          $this->load->model('Leads_Model');
          $this->Leads_Model->add_comment_for_events('Contact Deleted.',$res->Enquery_id,0,$user_id);
          }


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

    public function save_contact_post()
    {
    	$cc_id = $this->input->post('contact_id');
    	$comp_id = $this->input->post('company_id');
    	$enquiry_id = $this->input->post('enquiry_id');
    	$user_id = $this->input->post('user_id');

      $name = $this->input->post('name')??'';
      $mobile = $this->input->post('mobileno')??'';
      $email = $this->input->post('email')??'';
      $otherdetails = $this->input->post('otherdetails')??'';
      $designation = $this->input->post('designation')??'';
      $decision_maker =$this->input->post('decision_maker')??0;
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_id','enquiry_id','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->load->model(array('Leads_Model'));

    		$data = array(
                'c_name' => $name,
                'emailid' => $email,
                'contact_number' => $mobile,
                'designation' => $designation,
                'other_detail' => $otherdetails,
                'decision_maker' => $decision_maker,
            );
    		$done = 0;
            $res = $this->db->where(array('enquiry_id'=>$enquiry_id))->get('enquiry')->row();
            if(!empty($res))
            {	
            	if(!empty($cc_id))
	            {
	            	$this->db->where('cc_id',$cc_id)->update('tbl_client_contacts',$data);
	            	$this->Leads_Model->add_comment_for_events('Contact Updated',$res->Enquery_id,0,$user_id);
	            }
	            else
	            {	
                $data['comp_id'] = $comp_id;
                $data['client_id'] = $enquiry_id;
                $this->db->insert('tbl_client_contacts',$data);
	            	$this->Leads_Model->add_comment_for_events('Contact Added',$res->Enquery_id,0,$user_id);
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

    public function for_data_list_post()
    {
    	$comp_id = $this->input->post('company_id');
    	$user_id = $this->input->post('user_id');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');
        if($this->form_validation->run()==true)
        {

        	$all_reporting_ids  = $this->Common_model->get_categories($user_id);

	    	$this->db->select('tbl_visit.enquiry_id,CONCAT(COALESCE(enquiry.name,"")," ",COALESCE(enquiry.lastname,"")) as name');
	        $this->db->from('tbl_visit');
	        $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
	        $this->db->where("tbl_visit.comp_id",$comp_id);

	        $where="";
	        $where .= "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
	        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
	        $this->db->where($where);
	        $res = $this->db->get()->result();

         	if(!empty($res))
            {
            	
            	$this->set_response([
                  'status' => true,
                  'data' =>$res,
               ], REST_Controller::HTTP_OK);
			}
            else
            {
				$this->set_response([
                  'status' => FALSE,
                  'message' =>'No data.',
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

    public function rating_list_post()
    {
    	$comp_id = $this->input->post('company_id');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	if($this->form_validation->run()==true)
    	{
    		$data = array('1 Star','2 Star','3 Star','4 Star','5 Star');
    		$this->set_response([
                  'status' => true,
                  'data' =>$data,
               ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		$this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
    	}
    }
}
