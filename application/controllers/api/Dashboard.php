<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Dashboard extends REST_Controller {
    function __construct(){
        parent::__construct();
    }
    public function dashboard_post()
    {   
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $process_id =  $this->input->post('process_id');//can be multiple

        $process = 0;
        if(!empty($process_id))
        {
            if(is_array($process_id))
                $process = implode(',',$process_id);
            else 
                $process = $process_id;
        }

        $this->load->model('enquiry_model');
        $funneldata = $this->enquiry_model->all_enqueries_api($user_id,$company_id,$process);
        $this->set_response([
            'status' => TRUE,            
            'funneldata' => $funneldata
        ], REST_Controller::HTTP_OK);        
    }
    public function ticket_dashboard_post()
    {
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $process =  $this->input->post('process');//can be multiple
        $date_from = $this->input->post('date_from')??0;
        $date_to = $this->input->post('date_to')??0;
        
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->load->model('Ticket_Model');
            //print_r($_POST); exit();    
          $res =  $this->Ticket_Model->TicketDashboardAPI($user_id,$company_id,$process,$date_from,$date_to);
            //$res=0;
           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Unable to Find'
            ], REST_Controller::HTTP_OK); 
           }

        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function getSubSourceList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('tbl_subsource')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }


    public function getDataSourceList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('tbl_datasource')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function getProbabilityList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('lead_score')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function getIssueList_post()
    {
        $company_id = $this->input->post('company_id');
        //$process =  $this->input->post('process');//can be multiple

        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        //$this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->db->where('comp_id',$company_id);

            $res = $this->db->get('tbl_nature_of_complaint')->result();

           if(!empty($res))
           {
             $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
           }
           else
           {
             $this->set_response([
                'status' => FALSE,            
                'message' => 'Not Data'
            ], REST_Controller::HTTP_OK); 
           }
        }
        else
        {
                $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }
    }

    public function unique_company_list_post()
    {
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $process =  $this->input->post('process');//can be multiple
        $key = $this->input->post('key')??'';
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->load->model('Client_Model');
    
            $res = $this->Client_Model->getCompanyList($key,$company_id,$user_id,$process)->result();

            $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }

    }   

    public function unique_company_list_page_post()
    {
        $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $process =  $this->input->post('process');//can be multiple
        $key = $this->input->post('key')??'';
        $limit = $this->input->post('limit')??10;
        $offset = $this->input->post('offset')??0;
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        $this->form_validation->set_rules('process','process', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $this->load->model('Client_Model');
    
            $total = $this->Client_Model->getCompanyList($key,$company_id,$user_id,$process,'count');
            $data = $this->Client_Model->getCompanyList($key,$company_id,$user_id,$process,'data',$limit,$offset)->result();

            $res['offset'] = $offset;
            $res['limit'] = $limit;
            $res['total'] = $total;
            $res['list'] = $data;
            
            $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }

    }   
    public function other_stages_post()
    {
        $comp_id = $this->input->post('comp_id');
        $this->db->where('comp_id',$comp_id);
        $this->db->or_where('comp_id',0);
        $lang    =  $this->db->get('language')->result_array();
        $base_url='https://thecrm360.com/new_crm/assets/images/icons/';
         $user_id = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $this->form_validation->set_rules('user_id','user_id', 'trim|required');
        $this->form_validation->set_rules('company_id','company_id', 'trim|required');
        if($this->form_validation->run()==true)
        {
            $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING',$company_id);
            //featch 
            $dydata[]=['key'=>1,'title'=>'enquiry','icon'=>$base_url.'enquiry.jpeg' ];                        
            $dydata[]=['key'=>2,'title'=>'lead','icon'=>$base_url.'lead.jpeg' ];                        
            $dydata[]=['key'=>3,'title'=>'client','icon'=>$base_url.'client.jpeg' ];                        

            if (!empty($enquiry_separation)) {
                $enquiry_separation = json_decode($enquiry_separation, true);
                    foreach ($enquiry_separation as $key => $value) {
                       if($key==4){ $img='orders.jpeg'; }else{
                        $img='fo.jpeg';
                       }
                    $data[]=['key'=>$key,'title'=>$value['title'],'icon'=>$base_url.$img ];                        
                    }
                }              

            $res['basic_menu'] =$dydata;
            $res['dynamic_menu'] =$data;
            
            $this->set_response([
                'status' => TRUE,            
                'data' => $res
            ], REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,            
                'message' => strip_tags(validation_errors()),
            ], REST_Controller::HTTP_OK); 
        }

    }  
}