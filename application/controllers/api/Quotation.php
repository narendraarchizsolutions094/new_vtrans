<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Quotation extends REST_Controller {
  function __construct() 
  {
    parent::__construct();
    $this->load->library('form_validation');
	  $this->load->model(array('enquiry_model','common_model','Branch_model'));
  }

  //================= Only for V-trans==================
  	public function zone_list_post()
    {
      $company_id = $this->input->post('company_id');
      $this->form_validation->set_rules('company_id','company_id','required|trim');

      if($this->form_validation->run())
      {
          $res = $this->Branch_model->zone_list(0,array(),$company_id)->result();
          if(!empty($res))
          {
              $this->set_response([
                  'status' => TRUE,
                  'data' =>$res
                   ], REST_Controller::HTTP_OK);
          }
          else
          {
        
            $this->set_response([
              'status' => false,
              'msg' =>'No data found'
              ], REST_Controller::HTTP_OK);
          }
      }
      else
      {
          $this->set_response([
              'status' => false,
              'msg' =>strip_tags(validation_errors())
              ], REST_Controller::HTTP_OK);
      }
    }



    public function vehicle_list_post()
    {
      $company_id = $this->input->post('company_id');
      $this->form_validation->set_rules('company_id','company_id','required|trim');

      if($this->form_validation->run())
      {
          $res = $this->Branch_model->get_vehicles(0,array(),$company_id)->result();
          if(!empty($res))
          {
              $this->set_response([
                  'status' => TRUE,
                  'data' =>$res
                   ], REST_Controller::HTTP_OK);
          }
          else
          {
        
            $this->set_response([
              'status' => false,
              'msg' =>'No data found'
              ], REST_Controller::HTTP_OK);
          }
      }
      else
      {
          $this->set_response([
              'status' => false,
              'msg' =>strip_tags(validation_errors())
              ], REST_Controller::HTTP_OK);
      }
    }

    public function branch_list_post()
    {
      $company_id = $this->input->post('company_id');
      $this->form_validation->set_rules('company_id','company_id','required|trim');

      if($this->form_validation->run())
      {

          $where = array();

          if(!empty($_POST['zone_id']))
            $where['branch.zone'] = $_POST['zone_id'];

          $res = $this->Branch_model->branch_list(0,$where,$company_id)->result();
          if(!empty($res))
          {
              $this->set_response([
                  'status' => TRUE,
                  'data' =>$res
                   ], REST_Controller::HTTP_OK);
          }
          else
          {
        
            $this->set_response([
              'status' => false,
              'msg' =>'No data found'
              ], REST_Controller::HTTP_OK);
          }
      }
      else
      {
          $this->set_response([
              'status' => false,
              'msg' =>strip_tags(validation_errors())
              ], REST_Controller::HTTP_OK);
      }
    }

    public function common_list_post()
    {
      $company_id = $this->input->post('company_id');
      $this->form_validation->set_rules('company_id','company_id','required|trim');

      if($this->form_validation->run())
      {
          $res = $this->Branch_model->common_list(array(),$company_id)->result();
          if(!empty($res))
          {
              $this->set_response([
                  'status' => TRUE,
                  'data' =>$res
                   ], REST_Controller::HTTP_OK);
          }
          else
          {
        
            $this->set_response([
              'status' => false,
              'msg' =>'No data found'
              ], REST_Controller::HTTP_OK);
          }
      }
      else
      {
          $this->set_response([
              'status' => false,
              'msg' =>strip_tags(validation_errors())
              ], REST_Controller::HTTP_OK);
      }
    }

     public function from_to_table_post()
    {
      
      $this->form_validation->set_rules('company_id','company_id','required|trim');
      $this->form_validation->set_rules('from_list','from_list','required|trim');
      $this->form_validation->set_rules('to_list','to_list','required|trim');
      if($this->form_validation->run())
      {
          $company_id = $this->input->post('company_id');
          $from_list = $this->input->post('from_list');
          $to_list = $this->input->post('to_list');

          if(!is_array($from_list))
              $from_list = explode(',', $from_list);

          if(!is_array($to_list))
              $to_list = explode(',', $to_list);

          $res = $this->Branch_model->from_to_table($from_list,$to_list,$company_id)->result();
          if(!empty($res))
          {
              $this->set_response([
                  'status' => TRUE,
                  'data' =>$res
                   ], REST_Controller::HTTP_OK);
          }
          else
          {
        
            $this->set_response([
              'status' => false,
              'msg' =>'No data found'
              ], REST_Controller::HTTP_OK);
          }
      }
      else
      {
          $this->set_response([
              'status' => false,
              'msg' =>strip_tags(validation_errors())
              ], REST_Controller::HTTP_OK);
      }
    }
}