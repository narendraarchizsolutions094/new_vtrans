<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Ticket extends REST_Controller {
  function __construct() 
  {
      parent::__construct();
      $this->load->library('form_validation');
	   $this->load->model(array('location_model','Ticket_Model'));
  }
  //api for providing ticket source Company Wise
  public function getTicketSource_post()
  {      
    $company_id  = $this->input->post('company_id');
    $this->load->model('Ticket_Model');
    $getList  = $this->Ticket_Model->getSource($company_id);
    if(!empty($getList))
    {
      $this->set_response([
      'status' => TRUE,            
      'ticketSourceList' => $getList
      ], REST_Controller::HTTP_OK);   
    }
    else
    {
      $this->set_response([
      'status'  => false,           
      'msg'     => "No ticket source found for company id you had provided"
      ], REST_Controller::HTTP_OK); 
    }
    
  }

  // api for ticket problem list Company Wise
  public function getProblemList_post()
  {      
    $company_id  = $this->input->post('company_id');
    $this->load->model('Ticket_Model');
    $getList  = $this->Ticket_Model->getIssuesByCompnyID($company_id);
    if(!empty($getList))
    {
      $this->set_response([
      'status' => TRUE,            
      'ticketProblemList' => $getList
      ], REST_Controller::HTTP_OK);   
    }
    else
    {
      $this->set_response([
      'status'  => false,           
      'msg'     => "No ticket Problem list found for company id you had provided"
      ], REST_Controller::HTTP_OK); 
    }
  }
  // api for ticket list Company Wise 
  public function getTicketList_post()
  {      
    $company_id   = $this->input->post('company_id');
    $user_id      = $this->input->post('user_id');
    $process_id = $this->input->post('process_id');
    $this->form_validation->set_rules('company_id','Company ID','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('user_id','User ID','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('process_id','Process ID','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $this->load->model('Ticket_Model');
      $getList  = $this->Ticket_Model->getTicketListByCompnyID($company_id,$user_id,$process_id);
      if(!empty($getList))
      {
        $this->set_response([
        'status'      => TRUE,
        'count'       => count($getList),            
        'ticketList'  => $getList,
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No ticket list found for company id you had provided"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    } 
  }
//api for view of create ticket form
  public function createTicketForm_post()
  {
    $this->load->model(array('Enquiry_model','Leads_Model'));
    $company_id   = $this->input->post('company_id');
    $process_id   = $this->input->post('process_id');
// $user_id      = $this->input->post('user_id');
    $session_backup = $this->session->userdata()??'';
      $this->session->process = array($process_id);
      $this->session->companey_id = $company_id;
    $this->form_validation->set_rules('company_id','Company ID','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('process_id','Process ID','trim|required',array('required'=>'You have note provided %s'));

    if($this->form_validation->run() == true)
    {
      $primary_tab= $this->Ticket_Model->getPrimaryTab()->id;
     
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
            $basic[$key]['parameter_name'] = 'client';
            break;
            case 18:
            $basic[$key]['parameter_name'] = 'name';
            break;
            case 19:
            $basic[$key]['parameter_name'] = 'phone';
            break;
            case 20:
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
            $basic[$key]['parameter_name'] = 'source';
            break;
            case 26: 
            $basic[$key]['parameter_name'] = 'attachment[]';
            break;
            case 27:
            $basic[$key]['parameter_name'] = 'remark';
            break;
            case 28:
            $basic[$key]['parameter_name'] = 'tracking_no';
            break;

          }
      }
      $dynamic = $this->location_model->get_company_list($process_id,$primary_tab);
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
          $i++;
      }
      $data = array_merge($basic,$dynamic);      
      session_destroy();
      $this->session->set_userdata($session_backup);
      if(!empty($data))
      {
        $this->set_response([
        'status'      => TRUE,          
        'data'  => $data, 
        
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data Found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    } 
  }

//api for creating new ticket
  public function createTicket_post()
  {      
    $company_id   = $this->input->post('company_id'); //mandatory to pass
    $user_id      = $this->input->post('user_id');    //mandatory to pass
    $process_id   = $this->input->post('process_id');
    $this->form_validation->set_rules('company_id','Company','trim|required');
    $this->form_validation->set_rules('user_id','User','trim|required');
    $this->form_validation->set_rules('process_id','Process','trim|required');
    
    if($this->form_validation->run() == true)
    {
      $session_backup = $this->session->userdata()??'';
      $this->session->process = array($process_id);
      $this->session->companey_id = $company_id;
      $user_id  = $this->input->post('user_id');
      $this->load->model('Ticket_Model');
      $inserted  = $this->Ticket_Model->save($company_id,$user_id);
      //for dynamic fields update
      if($res=$inserted) 
      {
        $tck_id =  $this->db->select('id')
                ->where('ticketno',$res)
                ->get('tbl_ticket')->row()->id;
        
        $comment_id = $this->db->select('id')
                ->where('tck_id',$tck_id)
                ->get('tbl_ticket_conv')->row()->id;
        if(isset($_POST['inputfieldno'])) {                    
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);                
                $file_count = 0;
                $file = !empty($_FILES['enqueryfiles'])?$_FILES['enqueryfiles']:'';                
                foreach($inputno as $ind => $val){
  
                 if ($inputtype[$ind] == 8) {                                                
                        $file_data    =   $this->doupload($file,$file_count);
                        if (!empty($file_data['imageDetailArray']['file_name'])) {
                            $file_path = base_url().'uploads/ticket_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];
                            $biarr = array( 
                                            "enq_no"  => $res,
                                            "input"   => $val,
                                            "parent"  => $tck_id, 
                                            "fvalue"  => $file_path,
                                            "cmp_no"  => $company_id,
                                            "comment_id" => $comment_id
                                        );         
                                $this->db->insert('ticket_dynamic_data',$biarr);          
                        }
                        $file_count++;          
                    }else{
                        $biarr = array( "enq_no"  => $res,
                                      "input"   => $val,
                                      "parent"  => $tck_id, 
                                      "fvalue"  => $enqinfo[$ind]??'',
                                      "cmp_no"  => $company_id,
                                      "comment_id" => $comment_id
                                     );                                 
                       
                            $this->db->insert('ticket_dynamic_data',$biarr);
                    }                                      
                } //foreach loop end               
            }    
            //dynamic end
        }
      //echo "string".$inserted;die;
      session_destroy();
      if(!empty($inserted))
      {
        $this->session->set_userdata($session_backup);
        $this->set_response([
        'status'   => TRUE,            
        'insertId' => $inserted,
        'msg'      => "ticket created successfully"
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "ticket not inserted something went wrong"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $this->set_response([
        'status'  => false,
        'msg'     => "Please Fill All Mandatory Fields"
      ],REST_Controller::HTTP_OK);
    } 
  }

/// api for updating an existing ticket
  public function updateTicket_post()
  {      

    $company_id   = $this->input->post('company_id'); //mandatory to passs
    $user_id      = $this->input->post('user_id'); //mandatory to passs
    $ticketno     = $this->input->post('ticketno'); //mandatory to passs
    $this->form_validation->set_rules('company_id','Company','trim|required');
    $this->form_validation->set_rules('user_id','User','trim|required');
    $this->form_validation->set_rules('ticketno','Ticket','trim|required');
    if($this->form_validation->run() == true)
    {
      $res = $this->db->where(array('ticketno' => $_POST['ticketno']))->get('tbl_ticket')->row();
      $_POST['ticketno']  = $res->id;
      $session_backup = $this->session->userdata()??'';
      //$this->session->process = array($process_id);
      $this->session->companey_id = $company_id;
      $this->session->user_id = $user_id;
      $this->load->model('Ticket_Model');
      $inserted  = $this->Ticket_Model->save($company_id,$user_id);
      if(!empty($inserted))
      {
        session_destroy();
        $this->session->set_userdata($session_backup);
        $this->set_response([
        'status'      => TRUE,            
        'insertId'    => $inserted,
        'msg'         => "Ticket Updated successfully"
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "Ticket not Updated, Something went wrong"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $this->set_response([
        'status'  => false,
        'msg'     => "Please Fill All Mandatory Fields"
      ],REST_Controller::HTTP_OK);
    } 
  }

  public function updateTicketTab_post()
  {      
    $this->load->model('Ticket_Model');
    $comp_id   = $this->input->post('company_id');
    $user_id   = $this->input->post('user_id');
    $enquiry_id   = $this->input->post('ticketno');
    //$form_type  = $this->input->post('is_query_type');
    $tab_id = $this->input->post('tab_id');
    // $form_type = $this->input->post('is_query_type');
    $this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('user_id','user_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('ticketno','enquiry_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('tab_id','tab_id','trim|required',array('required'=>'You have note provided %s'));
    // $this->form_validation->set_rules('is_query_type','is_query_type','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $data  = $this->Ticket_Model->update_ticket_tab($user_id,$comp_id);
      if($data)
      {
        $this->set_response([
        'status'      => TRUE,           
        'data'  => 'Ticket Updated',
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    } 
  }
//api to create ticket subject
  public function addSubject_post()
  {      
    //echo "string;";die;
    $company_id   = $this->input->post('company_id'); //mandatory to passs
    $user_id      = $this->input->post('user_id'); //mandatory to passs
    $subject      = $this->input->post('subject'); //mandatory to passs
    $this->form_validation->set_rules('company_id','Company','trim|required');
    //$this->form_validation->set_rules('user_id','User','trim|required');
    $this->form_validation->set_rules('subject','Subject','trim|required');
    if($this->form_validation->run() == true)
    {
      $this->load->model('Ticket_Model');
      $data = array(
          'subject_title' => $subject,
          'comp_id'       => $company_id
      );
      $inserted  = $this->Ticket_Model->add_tsub($data);
      if(!empty($inserted))
      {
        $this->set_response([
        'status'      => TRUE,            
        'insertId'    => $inserted,
        'msg'         => "Subject Added successfully"
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "Subject not inserted something went wrong"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $this->set_response([
        'status'  => false,
        'msg'     => "Please Fill All Mandatory Fields"
      ],REST_Controller::HTTP_OK);
    } 
  }
//subject list api
  public function subjectList_post()
  {      
    $company_id  = $this->input->post('company_id');
    $this->form_validation->set_rules('company_id','Company','trim|required');
    if($this->form_validation->run() == true)
    {
      $this->load->model('Ticket_Model');
      $getList  = $this->Ticket_Model->get_sub_list($company_id);
      if(!empty($getList))
      {
        $this->set_response([
        'status'      => TRUE,            
        'ticketList'  => $getList
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No ticket list found for company id you had provided"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $this->set_response([
        'status'  => false,
        'msg'     => "Please provide a company id"
      ],REST_Controller::HTTP_OK);
    } 
  }
//delete subject api
  public function deleteSubject_post()
  {      
    $subjectid  = $this->input->post('subjectid');
    $this->form_validation->set_rules('subjectid','Subject ID','trim|required');
    if($this->form_validation->run() == true)
    {
      $this->load->model('Ticket_Model');
      $deletestatus  = $this->Ticket_Model->delete_subject($subjectid);
      if(!empty($getList))
      {
        $this->set_response([
        'status'      => TRUE,            
        'ticketList'  => $deletestatus
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "Subject not deleted"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $this->set_response([
        'status'  => false,
        'msg'     => "Please provide a valid id"
      ],REST_Controller::HTTP_OK);
    } 
  }

 public function getTicketTabs_post()
 {      
    $company_id   = $this->input->post('company_id');
    $ticketno      = $this->input->post('ticketno');
    $this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('ticketno','ticketno','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
     
      $data  = $this->Ticket_Model->ticket_all_tab_api($company_id,$ticketno);
      //session_destroy();
      if(!empty($data))
      {
        $this->set_response([
        'status'      => TRUE,           
        'data'  => $data,
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    } 
  }
  public function getTicketTimeline_post()
  {
    $company_id   = $this->input->post('company_id');
    $ticketno      = $this->input->post('ticketno');
    $this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('ticketno','ticketno','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $this->session->companey_id = $company_id;
     $res= $this->db->select('id')->where(array('ticketno'=>$ticketno))->get('tbl_ticket')->row();
      if(!empty($res))
        $data  = $this->Ticket_Model->getconv($res->id);
    // print_r( $data); exit();
      if(!empty($res) && !empty($data))
      {
        $this->set_response([
        'status'      => TRUE,           
        'data'  => $data,
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    }
  }
  public function getTicketDisposition_post()
  {
    $this->load->model(array('Leads_Model'));
    $company_id   = $this->input->post('company_id');
    $ticketno      = $this->input->post('ticketno');
    $this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('ticketno','ticketno','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $this->session->companey_id = $company_id;
      $ticket= $this->db->select('*')->where(array('ticketno'=>$ticketno))->get('tbl_ticket')->row();
      $stage = $this->Leads_Model->stage_by_type(4);
      $data =array();
      foreach ($stage as $key => $val)
      {
         $sub_stage =  $this->Leads_Model->all_description($val->stg_id);
         $val->sub_stage = $sub_stage;
         $data['available']['stage'][] = $val;
      }
      $data['available']['status'] = $this->Ticket_Model->ticket_status()->result();
      if(!empty($ticket))
      $data['selected'] =array(
                          'stage'=>$ticket->ticket_stage,
                          'sub_stage'=>$ticket->ticket_substage,
                          'status'=>$ticket->ticket_status,
                            );
     
    // print_r( $data); exit();
      if(!empty($data))
      {
        $this->set_response([
        'status'      => TRUE,           
        'data'  => $data,
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    }
  }

  public function saveTicketDisposition_post()
  {
    $company_id   = $this->input->post('company_id');
    $user_id      =$this->input->post('user_id');
    $ticketno      = $this->input->post('ticketno');
    $stage   =$this->input->post('stage')??'';
    $sub_stage = $this->input->post('sub_stage')??'';
    $status = $this->input->post('status')??'';
    $message = $this->input->post('conversation')??'';
    $stage_date  = $this->input->post('date')??'';
    $stage_time = $this->input->post('time')??'';


    $this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('ticketno','ticketno','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('user_id','user_id','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $this->load->model('Leads_Model');
      $session_backup = $this->session->userdata()??'';
      $this->session->companey_id = $company_id;
      $this->session->user_id = $user_id;
      $res=0;
      $tck = $this->Ticket_Model->get($ticketno);
      if(!empty($tck))
      {
        $res = $this->Ticket_Model->saveconv($tck->id, 'Stage Updated', $message,0, $user_id, $stage,$sub_stage,$status); 

        $stage_remark = $message;

        $contact_person = '';
        $mobileno = '';
        $email = '';
        $designation = '';
        $enq_code = $ticketno;
        $notification_id = '';//To be filled after firebase entry
        $dis_subject = '';
        $this->Leads_Model->add_comment_for_events_popup($stage_remark, $stage_date, $contact_person, $mobileno, $email, $designation, $stage_time, $enq_code, $notification_id, $dis_subject, 17);
      }
      
      if($res)
      {
        $this->set_response([
        'status'      => TRUE,           
        'data'  => 'Done',
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    }
  }  
 public function assignTickets_post()
  {
    $company_id   = $this->input->post('company_id');
    $user_id      = $this->input->post('user_id');
    $tickets      = $this->input->post('tickets');
    $emp_id       = $this->input->post('emp_id');
    $this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('tickets[]','tickets','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('user_id','user_id','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $assign_to_date = date('Y-m-d H:i:s');
      $move_enquiry = $tickets;
      $assign_employee = $emp_id;
      $notification_data = array();
      $assign_data = array();
      $last=0;
        if (!empty($move_enquiry)) {
          foreach ($move_enquiry as $key)
          {
            $ticket =   $this->db->select('*')
                        ->where('id',$key)
                        ->get('tbl_ticket')
                        ->row();
            if(!empty($ticket))
            $this->db->set('assign_to', $assign_employee);
            $this->db->set('assigned_by', $user_id);
            $this->db->set('assigned_to_date', $assign_to_date);
            $this->db->where('id', $key);
            $this->db->update('tbl_ticket');
          
            $this->db->set('comp_id',$company_id);
            $this->db->set('query_id',$ticket->ticketno);
            $this->db->set('noti_read',0);
            $this->db->set('contact_person',$ticket->name);
            $this->db->set('mobile',$ticket->phone);
            $this->db->set('email',$ticket->email); 
            $this->db->set('task_date',date('d-m-Y'));
            $this->db->set('task_time',date('H:i:s'));
            $this->db->set('create_by',$user_id);
            $this->db->set('task_type','17');
            $this->db->set('subject','Ticket Assigned');
            $this->db->insert('query_response');

            $insarr = array(
          "tck_id"  => $key,
          "parent"  => 0,
          'comp_id' => $company_id,
          "subj"    => "Ticked Assigned",
          "msg"     => '',
          "attacment" => "",
          "status"    => 0,
          "send_date" =>  date("Y-m-d H:i:s"),
          "client"    => 0,
          "added_by"  => $user_id,
          "assignedTo"=>$emp_id);
          $this->db->insert('tbl_ticket_conv',$insarr);

            $last = $this->db->insert_id();
          }
        }
        
      if($last)
      {
        $this->set_response([
        'status'      => TRUE,           
        'data'  => 'Done',
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    }
  }

  public function deleteTickets_post()
  {
    $company_id   = $this->input->post('company_id');
    $tickets      = $this->input->post('tickets');
    $this->form_validation->set_rules('company_id','company_id','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('tickets[]','tickets','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $ret = 0 ;
      //print_r($_POST);
      foreach ($tickets as $key => $value) 
      {
      
        $this->db->where('id',$value);
        $this->db->delete('tbl_ticket');
        $ret = $this->db->affected_rows();
        
        $this->db->where('tck_id',$value);
        $this->db->delete('tbl_ticket_conv');
      
      }      
      if($ret)
      {
        $this->set_response([
        'status'      => TRUE,           
        'data'  => 'Done',
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No Data found"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,//"Please provide a company id"
      ],REST_Controller::HTTP_OK);
    }
  }

  function updateQueryData_post()
  {
      $cmnt_id   = $this->input->post('cmnt_id');
      $comp_id = $this->input->post('comp_id');
      // $enquiry_code  = $this->input->post('enquiry_id');
      // $tabname =$this->input->post('tabname');
      $user_id = $this->input->post('user_id');
        $this->form_validation->set_rules('cmnt_id','cmnt_id','trim|required',array('required'=>'You have not provided %s'));
        $this->form_validation->set_rules('ticketno','ticketno','trim|required',array('required'=>'You have not provided %s'));
        $this->form_validation->set_rules('tabname','tabname','trim|required',array('required'=>'You have not provided %s'));
        $this->form_validation->set_rules('user_id','user_id','trim|required',array('required'=>'You have not provided %s'));
      if($this->form_validation->run()==true)
      {
        // if($type == 1){                 
        //     $comment_id = $this->Leads_Model->add_comment_for_events(display('enquery_updated'), $en_comments);                    
        // }else if($type == 2){                   
        //      $comment_id = $this->Leads_Model->add_comment_for_events(display('lead_updated'), $en_comments);                   
        // }else if($type == 3){
        //      $comment_id = $this->Leads_Model->add_comment_for_events(display('client_updated'), $en_comments);
        // }  
          $res = $this->Ticket_Model->update_dynamic_query($user_id,$comp_id);
          if($res)
          {
            $this->set_response([
            'status'      => TRUE,           
            'msg'  => 'success',
            ], REST_Controller::HTTP_OK);   
          }
          else
          {
            $this->set_response([
            'status'  => false,           
            'msg'     => "Unable to Update",
            ], REST_Controller::HTTP_OK); 
          }
      }
      else
      {
        $msg = strip_tags(validation_errors());
        $this->set_response([
          'status'  => false,
          'msg'     => $msg,//"Please provide a company id"
        ],REST_Controller::HTTP_OK);
      }
  }
  function deleteQueryData_post()
  {
      $cmnt_id   = $this->input->post('cmnt_id');
      $ticketno  = $this->input->post('ticketno');
      $tabname =$this->input->post('tabname');
      $user_id  =$this->input->post('user_id');
      $comp_id  = $this->input->post('comp_id');
        $this->form_validation->set_rules('cmnt_id','cmnt_id','trim|required',array('required'=>'You have not provided %s'));
        $this->form_validation->set_rules('ticketno','ticketno','trim|required',array('required'=>'You have not provided %s'));
        $this->form_validation->set_rules('tabname','tabname','trim|required',array('required'=>'You have not provided %s'));
      if($this->form_validation->run()==true)
      {
        $tick = $this->db->where('ticketno',$ticketno)->get('tbl_ticket')->row();
        $this->db->where(array('comment_id'=>$cmnt_id,'enq_no'=>$ticketno))->delete('ticket_dynamic_data');
          $res =$this->db->affected_rows(); 
          if($res)
          {
            $this->Ticket_Model->saveconv($tick->id,$tabname.' Data Deleted','',0,$user_id,0,0,0,$comp_id);
            $this->set_response([
            'status'      => TRUE,           
            'msg'  => 'success',
            ], REST_Controller::HTTP_OK);   
          }
          else
          {
            $this->set_response([
            'status'  => false,           
            'msg'     => "Unable to delete",
            ], REST_Controller::HTTP_OK); 
          }
      }
      else
      {
        $msg = strip_tags(validation_errors());
        $this->set_response([
          'status'  => false,
          'msg'     => $msg,//"Please provide a company id"
        ],REST_Controller::HTTP_OK);
      }
  }

  public function sendMessage_post()
  {
    $this->load->model('Message_models'); 
    $ticketno = $this->input->post('ticketno');
    $template_id = $this->input->post('template_id');
    $company_id  = $this->input->post('company_id');
    $user_id = $this->input->post('user_id');
    $this->form_validation->set_rules('ticketno','Ticket No','required');
    $this->form_validation->set_rules('template_id','Template ID','required');
    $this->form_validation->set_rules('user_id','User ID','required');
    $this->form_validation->set_rules('company_id','Company ID','required');
    if($this->form_validation->run() == true){
    
    $this->db->where('pk_i_admin_id',$user_id);
    $user_row  = $this->db->get('tbl_admin')->row_array();
    $this->db->where('temp_id',$template_id);
    $template_row = $this->db->get('api_templates')->row();
    $Templat_subject = $template_row->mail_subject;
    $message_name = $template_row->template_content;
      
          $ticket = $this->db->select('*')->where('ticketno',$ticketno)->get('tbl_ticket')->row();
          //echo $ticketno;
       //print_r($user_row); exit();
          //echo $enq->email;
          if(!empty($ticket->email)){
            $this->db->where('comp_id',$company_id);
            $this->db->where('sys_para','usermail_in_cc');
            $this->db->where('type','COMPANY_SETTING');
            $cc_row = $this->db->get('sys_parameters')->row_array(); 
            $cc = '';
            if(!empty($cc_row))
            {
                $this->db->where('pk_i_admin_id',$user_id);
               $cc_user =  $this->db->get('tbl_admin')->row_array();
               if(!empty($cc_user))
                    $cc = $cc_user['s_user_email'];
            }
                $to = $ticket->email;
                $name1 = $ticket->name;
                //   $find = array('@name',
                //             '@phone',
                //             '@username',
                //             '@userphone',
                //             '@designation',
                //               '@ticket_no',
                //                 '@tracking_no'
                //         );
                // $replace = array(
                //     $enq_row['name'],
                //     $user_row['contact_phone'],
                //     $user_row['s_username'],
                //     $enq_row['phone'],
                //     $user_row['designation'],
                //     $enquiry_code,
                //     $enq_row['tracking_no'],
                //     );
                // $message  =str_replace($find, $replace, $message);
                // $subject  = str_replace($find, $replace, $subject);

              $msg = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$message_name))))));
               $Templat_subject = str_replace('@name',$name1,str_replace('@org',$user_row['orgisation_name'],str_replace('@desg',$user_row['designation'],str_replace('@phone',$user_row['contact_phone'],str_replace('@desg',$user_row['designation'],str_replace('@user',$user_row['s_display_name'].' '.$user_row['last_name'],$Templat_subject))))));
                     
              if($this->Message_models->send_email($to,$msg,$Templat_subject,$company_id,$cc)){
               $msg= 'Email sent successfully';
               $this->set_response([
                      'status' => true,
                      'message' =>$msg
                   ], REST_Controller::HTTP_OK);
             }else{
               $msg= 'Something went wrong!';
               $this->set_response([
                      'status' => false,
                      'message' =>$msg
                   ], REST_Controller::HTTP_OK);
             }
          }else{
               $msg= 'Email does not exist for this Ticket';
               $this->set_response([
                      'status' => false,
                      'message' =>$msg
                   ], REST_Controller::HTTP_OK);
          }
         
      
    }else{
          $error= strip_tags(validation_errors());
         $this->set_response([
                'status' => false,
                'message' =>$error
             ], REST_Controller::HTTP_OK);
    }
  }

  public function getTicketList_page_post()
  {      
    $company_id   = $this->input->post('company_id');
    $user_id      = $this->input->post('user_id');
    $offset = $this->input->post('offset')??0;
    $limit = $this->input->post('limit')??10;
    $process_id = $this->input->post('process_id');

    $this->form_validation->set_rules('company_id','Company ID','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('user_id','User ID','trim|required',array('required'=>'You have note provided %s'));
    $this->form_validation->set_rules('process_id','Process ID','trim|required',array('required'=>'You have note provided %s'));
    if($this->form_validation->run() == true)
    {
      $this->load->model('Ticket_Model');
  
      $total  = $this->Ticket_Model->getTicketListByCompnyID('count',$company_id,$user_id,$process_id);
     // echo $this->db->last_query();
      $res= array();
      $res['offset'] = $offset;
      $res['limit'] = $limit;
      $res['total'] = $total;

      $res['list']  = $this->Ticket_Model->getTicketListByCompnyID('data',$company_id,$user_id,$process_id,$offset,$limit);

      if(!empty($res))
      {
        $this->set_response([
        'status'      => TRUE,           
        'tickets'  => $res,
        ], REST_Controller::HTTP_OK);   
      }
      else
      {
        $this->set_response([
        'status'  => false,           
        'msg'     => "No ticket list found for company id you had provided"
        ], REST_Controller::HTTP_OK); 
      }
    }
    else
    {
      $msg = strip_tags(validation_errors());
      $this->set_response([
        'status'  => false,
        'msg'     => $msg,
      ],REST_Controller::HTTP_OK);
    } 
  }

  public function ticket_status_post()
  { 
      $comp_id =  $this->input->post('company_id');

      $res = $this->db->where('company_id',$comp_id)->get('tbl_ticket_status')->result();
      if($res)
      {
         $this->set_response([
        'status'      => TRUE,           
        'data'  => $res,
        ], REST_Controller::HTTP_OK);
      }
      else
      {
        $msg = 'No data';
        $this->set_response([
          'status'  => false,
          'msg'     => $msg,//"Please provide a company id"
        ],REST_Controller::HTTP_OK);
      } 
  }

}
