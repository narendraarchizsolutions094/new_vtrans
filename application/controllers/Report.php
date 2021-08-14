<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'report_model',
      'doctor_model',
      'representative_model',
      'user_model',
      'Leads_Model',
      'dash_model',
      'location_model',
	  'ticket_report_datatable_model'
    ));
    $this->load->library('pagination');
  }

  public function index()
  {
    if (user_role('120') == true) {
    }
    $data['title'] = display('reports_list');
    if ($this->session->companey_id == 65 && $this->session->user_right == 215) {
      $data['created_bylist'] = $this->user_model->read(147, false);
    } else {
      $data['created_bylist'] = $this->user_model->read();
    }
    $data['reports'] = $this->report_model->get_all_reports(1);
    $data['content'] = $this->load->view('reports/index', $data, true);
    $this->load->view('layout/main_wrapper', $data);
  }
  public function ticket_reports()
  {
    if (user_role('120') == true) {
    }
    $data['title'] = 'Ticket Reports List';
    if ($this->session->companey_id == 65 && $this->session->user_right == 215) {
      //$data['created_bylist'] = $this->user_model->read(147, false);
    } else {
    }
    $data['created_bylist'] = $this->user_model->read();
    $data['reports'] = $this->report_model->get_all_reports(2);
    $data['content'] = $this->load->view('reports/ticket_index', $data, true);
    $this->load->view('layout/main_wrapper', $data);
  }

  public function set_schedule()
  {
    $id = $this->input->post('id');
    $users = $this->input->post('users');
    $path = $this->input->post('path');
    $getschedule = $this->db->where(array('id' => $id))->get('reports');
    if ($getschedule->num_rows() == 1) {
      $data = $getschedule->row();
      $users = implode(',', $users);
      $data = ['schedule_status' => 1, 'schedule_date' => date('Y-m-d'), 'mail_users' => $users];
      $this->db->where('id', $id)->update('reports', $data);
    }
    $this->session->set_flashdata('message', 'Schedule Updated ');
    redirect($this->agent->referrer());
  }

  public function view($id)
  {
    if (user_role('120') == true) {
    }
    $data['rid'] = $id;
    $this->session->set_userdata('reportid', $id);
    $this->db->where('id', $id);
    $report_row =   $this->db->where('type', 1)->get('reports')->row_array();

    $filters = json_decode($report_row['filters'], true);
    $from = $this->session->set_userdata('fromdt', $this->input->post("from"));
    $to =  $this->session->set_userdata('todt', $this->input->post("to"));
    $data['title'] = 'View Report';
    $data['filters'] = $filters;
    $data['report_columns'] = $filters['report_columns'];
    $data["fieldsval"]        = $this->report_model->getdynfielsval();
    $data["dfields"] = $this->report_model->get_dynfields("");

    $data['content'] = $this->load->view('reports/report_view', $data, true);
    $this->load->view('layout/main_wrapper', $data);
  }
  
  public function deal_view($id)
  {

    $data['rid'] = $id;
    $this->session->set_userdata('reportid', $id);
    $this->db->where('id', $id);
    $report_row =   $this->db->where('type', 8)->get('reports')->row_array();

    $filters = json_decode($report_row['filters'], true);
    $from = $this->session->set_userdata('fromdt', $this->input->post("from"));
    $to =  $this->session->set_userdata('todt', $this->input->post("to"));
    $data['title'] = 'View Report';
    $data['filters'] = $filters;
    $data['report_columns'] = $filters['report_columns'];

    $data['content'] = $this->load->view('reports/deal_report_view', $data, true);
    $this->load->view('layout/main_wrapper', $data);
  }
  public function old_send_sales_view($id)
  {
   // $this->session->sess_destroy();
   session_unset();   
    // if (user_role('120') == true) {}
    $todays = date('Y-m-d');
    $cids = $this->uri->segment(2);
    $sch_date = $this->uri->segment(3);
    $cidss  = str_replace(array('-', '_', '~'), array('+', '/', '='), $cids);
    $sch_dates  = str_replace(array('-', '_', '~'), array('+', '/', '='), $sch_date);
    $schid = $this->encryption->decrypt($cidss);
    $sch_dates = $this->encryption->decrypt($sch_dates);
    $id = $schid;
    $data['rid'] = $schid;
    $this->session->set_userdata('reportid', $id);
    $this->db->where('id', $id);
    $report_row =   $this->db->get('reports')->row_array();
    if ($report_row['type'] == 1) {
      $report_row['type'];
      $datetime1 = new DateTime($sch_dates);
      $datetime2 = new DateTime($todays);
      $difference = $datetime1->diff($datetime2);
      $diffdate = $difference->d;

      $filters = json_decode($report_row['filters'], true);

      // $from = $this->session->set_userdata('fromdt',$this->input->post("from"));
      // $to =  $this->session->set_userdata('todt',$this->input->post("to"));                                         
      $data['title'] = 'View Report';
      $data['filters'] = $filters;
      $data['report_columns'] = $filters['report_columns'];
      $_POST = $filters;
      $comp_id = $report_row['comp_id'];
      $user_id = $report_row['created_by'];

      if ($this->input->post('from_exp') == '') {
        $from = '';
        $from = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
      } else {
        // $dfrom= $this->input->post('from_exp');
        $from = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
        // $from = date("d/m/Y", strtotime($dfrom));

      }
      if ($this->input->post('to_exp') == '') {
        $to = '';
        $to = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
      } else {
        // $tto= $this->input->post('to_exp');
        // $to = date("d/m/Y", strtotime($tto)); 
        $to = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
      }
      if ($this->input->post('updated_from_exp') == '') {
        $updated_from = '';
      } else {
        $updated_from = '';
        //  $updated_dfrom= $this->input->post('updated_from_exp');
        //  $updated_from = date("d/m/Y", strtotime($updated_dfrom));
      }

      if ($this->input->post('updated_to_exp') == '') {
        $updated_to = '';
      } else {
        $updated_to = '';
        //  $updated_tto= $this->input->post('updated_to_exp');
        //  $updated_to = date("d/m/Y", strtotime($updated_tto)); 
      }
      if ($this->input->post('employee') == '') {
        $employe = '';
      } else {
        $employe = $this->input->post('employee');
      }
      if ($this->input->post('phone') == '') {
        $phone = '';
      } else {
        $phone = $this->input->post('phone');
      }
      if ($this->input->post('country') == '') {
        $country = '';
      } else {
        $country = $this->input->post('country');
      }
      if ($this->input->post('institute') == '') {
        $institute = '';
      } else {
        $institute = $this->input->post('institute');
      }
      if ($this->input->post('center') == '') {
        $center = '';
      } else {
        $center = $this->input->post('center');
      }
      if ($this->input->post('source') == '') {
        $source = '';
      } else {
        $source = $this->input->post('source');
      }
      if ($this->input->post('subsource') == '') {
        $subsource = '';
      } else {
        $subsource = $this->input->post('subsource');
      }
      if ($this->input->post('datasource') == '') {
        $datasource = '';
      } else {
        $datasource = $this->input->post('datasource');
      }
      if ($this->input->post('state') == '') {
        $state = '';
      } else {
        $state = $this->input->post('state');
      }
      if ($this->input->post('lead_source') == '') { // disposition
        $lead_source = '';
      } else {
        $lead_source = $this->input->post('lead_source');
      }

      if ($this->input->post('lead_subsource') == '') {
        $lead_subsource = '';
      } else {
        $lead_subsource = $this->input->post('lead_subsource');
      }
      if ($this->input->post('sub_disposition') == '') {
        $sub_disposition = '';
      } else {
        $sub_disposition = $this->input->post('sub_disposition');
      }


      if ($this->input->post('enq_product') == '') {
        $enq_product = '';
      } else {
        $enq_product = $this->input->post('enq_product');
      }
      if ($this->input->post('productlst') == '') {
        $productlst = '';
      } else {
        $productlst = $this->input->post('productlst');
      }
      if ($this->input->post('drop_status') == '') {
        $drop_status = '';
      } else {
        $drop_status = $this->input->post('drop_status');
      }
      if ($this->input->post('hier_wise') == '') {
        $hier_wise = '';
      } else {
        $hier_wise = $this->input->post('hier_wise');
      }
      if ($this->input->post('Enquiry_Id') == '') {
        $Enquiry_Id = '';
      } else {
        $Enquiry_Id = $this->input->post('Enquiry_Id');
      }
      $data['post_report_columns'] = $this->input->post('report_columns');
      $post_report_columns = $this->input->post('report_columns');
      if ($this->input->post('all') == '') {
        $all = '';
      } else {
        $all = $this->input->post('all');
      }
      $data_arr = array(
        'from1'           =>  $from,
        'to1'             =>  $to,
        'updated_from1'   =>  $updated_from,
        'updated_to1'     =>  $updated_to,
        'employe1'        =>  $employe,
        'phone1'          =>  $phone,
        'country1'        =>  $country,
        'institute1'      =>  $institute,
        'center1'         =>  $center,
        'source1'         =>  $source,
        'subsource1'      =>  $subsource,
        'datasource1'     =>  $datasource,
        'state1'          =>  $state,
        'lead_source1'    =>  $lead_source,
        'lead_subsource1' =>  $lead_subsource,
        'sub_disposition' =>  $sub_disposition,
        'enq_product1'    =>  $enq_product,
        'drop_status1'    =>  $drop_status,
        'all1'            =>  $all,
        'post_report_columns' => $post_report_columns,
        'productlst' => $productlst,
        'hier_wise' => $hier_wise,
        'companey_id' => $comp_id,
        'user_id' => $user_id,
      );
      $this->session->set_userdata($data_arr);
      $this->load->view('reports/send_sales_view', $data);
    } else {
      $filters = json_decode($report_row['filters'], true);
      $data['title'] = 'View Report ';
      $data['filters'] = $filters;
      $comp_id = $report_row['comp_id'];
      $user_id = $report_row['created_by'];
      $this->session->set_userdata('companey_id', $comp_id);
      $this->session->set_userdata('user_id', $user_id);
      if(!empty($filters['process_id'])){
      $this->session->set_userdata('process',array($filters['process_id']));
      }
      $data['filters'] = json_decode($report_row['filters'], true);
      $cdate = date('Y-m-d', strtotime('-1 day', strtotime($todays)));
      $data['fromdate'] = $cdate;
      $data['todate'] = $cdate;
      $from = $this->session->set_userdata('fromdt', $cdate);
      $to =  $this->session->set_userdata('todt', $cdate);
      $data['title'] = 'View '.display('ticket').' Report';      
      $this->session->set_userdata('ticket_filters_sess', $data['filters']);
	  $data['ticket_users']  = $this->ticket_report_datatable_model->report_employee_wise($data['fromdate'],$data['todate'],'','');
	  //print_r($data['ticket_users']);exit;
      $data['ticket_stages'] = $this->Leads_Model->stage_by_type(4); // 4 = ticket
      $this->load->view('reports/send_ticket_views', $data);
    }
  }



  public function send_sales_view($id)
  {
    //$this->output->enable_profiler(TRUE);

   // $this->session->sess_destroy();
   session_unset();   
    // if (user_role('120') == true) {}
    $todays = date('Y-m-d');
    // $todays = '2021-02-16';

    $cids = $this->uri->segment(2);
    // echo $this->encryption->encrypt($cids);die;
    // echo $cids.'<br>';
    $sch_date = $this->uri->segment(3);
    $cidss  = str_replace(array('-', '_', '~'), array('+', '/', '='), $cids);
    $sch_dates  = str_replace(array('-', '_', '~'), array('+', '/', '='), $sch_date);
    $schid = $this->encryption->decrypt($cidss);
    $sch_dates = $this->encryption->decrypt($sch_dates);
   // $sch_dates = "2021-02-15";
    //echo $sch_dates;die;
    // $encrypted_string = $this->encryption->encrypt($schid);
    
    // $schid  = str_replace(array('+', '/', '='), array('-', '_', '~'),  $encrypted_string);
    // echo $schid;die;
    $id = $schid;
    $data['rid'] = $schid;
    $this->session->set_userdata('reportid', $id);
    $this->db->where('id',$id);
    $report_row =   $this->db->get('reports')->row_array();
    // echo $id;
    //  print_r($report_row);die; 
    if ($report_row['type'] == '1') {
       //echo "sdfsd"; exit;
      $report_row['type'];
      $datetime1 = new DateTime($sch_dates);
      $datetime2 = new DateTime($todays);
      $difference = $datetime1->diff($datetime2);
      $diffdate = $difference->d;

      $filters = json_decode($report_row['filters'], true);

      // $from = $this->session->set_userdata('fromdt',$this->input->post("from"));
      // $to =  $this->session->set_userdata('todt',$this->input->post("to"));                                         
      $data['title'] = 'View Report';
      $data['filters'] = $filters;
      $data['report_columns'] = $filters['report_columns'];
      $_POST = $filters;
      $comp_id = $report_row['comp_id'];
      $user_id = $report_row['created_by'];

      if ($this->input->post('from_exp') == '') {
        $from = '';
        $from = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
      } else {
        // $dfrom= $this->input->post('from_exp');
        $from = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
        // $from = date("d/m/Y", strtotime($dfrom));

      }
      if ($this->input->post('to_exp') == '') {
        $to = '';
        $to = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
      } else {
        // $tto= $this->input->post('to_exp');
        // $to = date("d/m/Y", strtotime($tto)); 
        $to = date('d/m/Y', strtotime('-1 day', strtotime($todays)));
      }
      if ($this->input->post('updated_from_exp') == '') {
        $updated_from = '';
      } else {
        $updated_from = '';
        //  $updated_dfrom= $this->input->post('updated_from_exp');
        //  $updated_from = date("d/m/Y", strtotime($updated_dfrom));
      }

      if ($this->input->post('updated_to_exp') == '') {
        $updated_to = '';
      } else {
        $updated_to = '';
        //  $updated_tto= $this->input->post('updated_to_exp');
        //  $updated_to = date("d/m/Y", strtotime($updated_tto)); 
      }
      if ($this->input->post('employee') == '') {
        $employe = '';
      } else {
        $employe = $this->input->post('employee');
      }
      if ($this->input->post('phone') == '') {
        $phone = '';
      } else {
        $phone = $this->input->post('phone');
      }
      if ($this->input->post('country') == '') {
        $country = '';
      } else {
        $country = $this->input->post('country');
      }
      if ($this->input->post('institute') == '') {
        $institute = '';
      } else {
        $institute = $this->input->post('institute');
      }
      if ($this->input->post('center') == '') {
        $center = '';
      } else {
        $center = $this->input->post('center');
      }
      if ($this->input->post('source') == '') {
        $source = '';
      } else {
        $source = $this->input->post('source');
      }
      if ($this->input->post('subsource') == '') {
        $subsource = '';
      } else {
        $subsource = $this->input->post('subsource');
      }
      if ($this->input->post('datasource') == '') {
        $datasource = '';
      } else {
        $datasource = $this->input->post('datasource');
      }
      if ($this->input->post('state') == '') {
        $state = '';
      } else {
        $state = $this->input->post('state');
      }
      if ($this->input->post('lead_source') == '') { // disposition
        $lead_source = '';
      } else {
        $lead_source = $this->input->post('lead_source');
      }

      if ($this->input->post('lead_subsource') == '') {
        $lead_subsource = '';
      } else {
        $lead_subsource = $this->input->post('lead_subsource');
      }
      if ($this->input->post('sub_disposition') == '') {
        $sub_disposition = '';
      } else {
        $sub_disposition = $this->input->post('sub_disposition');
      }


      if ($this->input->post('enq_product') == '') {
        $enq_product = '';
      } else {
        $enq_product = $this->input->post('enq_product');
      }
      if ($this->input->post('productlst') == '') {
        $productlst = '';
      } else {
        $productlst = $this->input->post('productlst');
      }
      if ($this->input->post('drop_status') == '') {
        $drop_status = '';
      } else {
        $drop_status = $this->input->post('drop_status');
      }
      if ($this->input->post('hier_wise') == '') {
        $hier_wise = '';
      } else {
        $hier_wise = $this->input->post('hier_wise');
      }
      if ($this->input->post('Enquiry_Id') == '') {
        $Enquiry_Id = '';
      } else {
        $Enquiry_Id = $this->input->post('Enquiry_Id');
      }
      $data['post_report_columns'] = $this->input->post('report_columns');
      $post_report_columns = $this->input->post('report_columns');
      if ($this->input->post('all') == '') {
        $all = '';
      } else {
        $all = $this->input->post('all');
      }
      $data_arr = array(
        'from1'           =>  $from,
        'to1'             =>  $to,
        'updated_from1'   =>  $updated_from,
        'updated_to1'     =>  $updated_to,
        'employe1'        =>  $employe,
        'phone1'          =>  $phone,
        'country1'        =>  $country,
        'institute1'      =>  $institute,
        'center1'         =>  $center,
        'source1'         =>  $source,
        'subsource1'      =>  $subsource,
        'datasource1'     =>  $datasource,
        'state1'          =>  $state,
        'lead_source1'    =>  $lead_source,
        'lead_subsource1' =>  $lead_subsource,
        'sub_disposition' =>  $sub_disposition,
        'enq_product1'    =>  $enq_product,
        'drop_status1'    =>  $drop_status,
        'all1'            =>  $all,
        'post_report_columns' => $post_report_columns,
        'productlst' => $productlst,
        'hier_wise' => $hier_wise,
        'companey_id' => $comp_id,
        'user_id' => $user_id,
      );
      $this->session->set_userdata($data_arr);
      $data['call_data'] = $this->all_call_log_report_filterdata();
      $data['signings_data'] = $this->all_signings_report_filterdata();
      $data['prospect_data'] = $this->all_prospect_report_filterdata();
      $data['visit_data'] = $this->all_visit_report_filterdata();
      $data['nad_count'] = $this->db->get_where('enquiry',array('status' => 1))->num_rows();
      $data['prospect_count'] = $this->db->get_where('enquiry',array('status' => 2))->num_rows();
      $data['approach_count'] = $this->db->get_where('enquiry',array('status' => 3))->num_rows();
      $data['negotiations_count'] = $this->db->get_where('enquiry',array('status' => 4))->num_rows();
      $data['closure_count'] = $this->db->get_where('enquiry',array('status' => 5))->num_rows();
      $data['order_count'] = $this->db->get_where('enquiry',array('status' => 6))->num_rows();
      $data['future_count'] = $this->db->get_where('enquiry',array('status' => 7))->num_rows();

      //$data['new_data'] = $this->get_last_month_data();
      $this->load->view('reports/send_sales_view', $data);
      
    } else {
      $filters = json_decode($report_row['filters'], true);
      $data['title'] = 'View Report ';
      $data['filters'] = $filters;
      $comp_id = $report_row['comp_id'];
      $user_id = $report_row['created_by'];
      $this->session->set_userdata('companey_id', $comp_id);
      $this->session->set_userdata('user_id', $user_id);
      if(!empty($filters['process_id'])){
      $this->session->set_userdata('process',array($filters['process_id']));
      }
      $data['filters'] = json_decode($report_row['filters'], true);
      $cdate = date('Y-m-d', strtotime('-1 day', strtotime($todays)));
      $data['fromdate'] = $cdate;
      $data['todate'] = $cdate;
      $from = $this->session->set_userdata('fromdt', $cdate);
      $to =  $this->session->set_userdata('todt', $cdate);
      $data['title'] = 'View '.display('ticket').' Report';      
      $this->session->set_userdata('ticket_filters_sess', $data['filters']);
	    $data['ticket_users']  = $this->ticket_report_datatable_model->report_employee_wise($data['fromdate'],$data['todate'],'','');
	  //print_r($data['ticket_users']);exit;
      $data['ticket_stages'] = $this->Leads_Model->stage_by_type(4); // 4 = ticket
      $this->load->view('reports/send_ticket_views', $data);
    }
  }

  public function get_last_month_data(){
    //$this->output->enable_profiler(TRUE);
    $date1 = date('Y-m-d', strtotime('today - 30 days'));
    $date2 = date('Y-m-d', strtotime('today - 1 days'));
    $sales_region = $this->db->get_where('sales_region')->result_array();

    $html = ' <div class="widget-title text-center">Last 30 Days Report '.date('d-m-Y',strtotime($date1)).' From To '.date('d-m-Y',strtotime($date2)).'</div><hr>
                <table id="example1" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align:center;font-size:8px;">Region</th>
                            <th style="text-align:center;font-size:8px;">Sales Persons</th>';
    for ($i=1; $i <= 30; $i++) {
      $date_new = date('Y-m-d', strtotime($date1 . ' -1 day'));
      $date = date('Y-m-d', strtotime($date_new . ' +'.$i.' day'));
      $html .= '<th style="text-align:center;font-size:8px;" colspan="3" style="text-align:center;">'.date('d-m-Y',strtotime($date)).'</th>';
    }
    $html .= '<th style="text-align:center;font-size:8px;" style="text-align:center;">-</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                <th style="text-align:center;font-size:8px;"></th>
                <th style="text-align:center;font-size:8px;"></th>
                <th id="sales_region" style="text-align:center;font-size:8px;display:none;">'.count($sales_region).'</th>';
    for ($y=1; $y <= 30; $y++) { 
      $html .='<th style="text-align:center;font-size:8px;background:#F9B1A5;">Visits</th>
              <th style="text-align:center;font-size:8px;background:#F2F751;">NAD</th>
              <th style="text-align:center;font-size:8px;background:#51F797;">New Signings</th>';
    }
    $html .='<th style="text-align:center;font-size:8px;background:#AFFF33;">Grand Total</th></tr>';

    $total_users = array();
    $s = 1;
    $grand_total = array();
    foreach($sales_region as $key => $region){
      $get_user = $this->db->where(array('dept_name' => 1,'b_status'=>1,'sales_region' => $region['region_id']))->from('tbl_admin')->count_all_results();
      array_push($total_users,$get_user);
      $html .='<tr>
                 <th style="text-align:center;font-size:8px;">'.$region['name'].'</th>
                 <th style="text-align:center;font-size:8px;">'.$get_user.'</th>';

      $total_data = array(); 
      $total = 0;
      for ($z=1; $z <=30 ; $z++) {

        $date_new = date('Y-m-d', strtotime($date1 . ' -1 day'));
        $date = date('Y-m-d', strtotime($date_new . ' +'.$z.' day'));
        $get_visit = $this->db->query("SELECT tbl_visit.* FROM `tbl_visit` INNER JOIN enquiry ON tbl_visit.enquiry_id= enquiry.enquiry_id WHERE enquiry.sales_region=".$region['region_id']." AND DATE(tbl_visit.created_at)=".'"'.$date.'"')->result_array();
        $get_nad = $this->db->where(array('sales_region' => $region['region_id'],'DATE(created_date)' => $date,'status' => 1))->from('enquiry')->count_all_results();
        $get_sinings = $this->db->where(array('sales_region' => $region['region_id'],'DATE(created_date)' => $date,'status' => 5))->from('enquiry')->count_all_results();

        if(count($get_visit) > 0){
          $visit_color = "background:#F9B1A5";
        }else{
          $visit_color = "";
        }

        if($get_nad > 0){
          $nad_color = "background:#F2F751";
        }else{
          $nad_color = "";
        }

        if($get_sinings > 0){
          $sinings_color = "background:#51F797";
        }else{
          $sinings_color = "";
        }

        $total = (count($get_visit)+$get_nad+$get_sinings);
        array_push($total_data,$total);

        $html .='<th id="visit'.$s.'_'.$z.'" style="text-align:center;font-size:8px;'.$visit_color.'">'.count($get_visit).'</th>
                <th  id="nad'.$s.'_'.$z.'" style="text-align:center;font-size:8px;'.$nad_color.'">'.$get_nad.'</th>
                <th  id="sinings'.$s.'_'.$z.'" style="text-align:center;font-size:8px;'.$sinings_color.'">'.$get_sinings.'</th>';
      }
      array_push($total_data,$get_user);
      $html .='<th style="text-align:center;font-size:8px;background:#AFFF33;">'.array_sum($total_data).'</th></tr>';
      array_push($grand_total,array_sum($total_data));
      $s++;
    }
  
    $html .='<tr style="background:#AFFF33;"><th style="text-align:center;font-size:8px;">Grand Total</th> <th style="text-align:center;font-size:8px;">'.array_sum($total_users).'</th>';

        $c = 0;
        for ($x=1; $x <= 30; $x++) {
          $html .='<th id="res_visit'.$x.'" style="text-align:center;font-size:8px;"></th>
                  <th id="res_nad'.$x.'" style="text-align:center;font-size:8px;"></th>
                  <th id="res_signings'.$x.'" style="text-align:center;font-size:8px;"></th>';
          $c++;
        }

    $html .='<th style="text-align:center;font-size:8px;background:#AFFF33;">'.array_sum($grand_total).'</th></tr>';
    $html .='</tbody>
          </table>';

    echo $html;
  }


  // function array_flatten($array) { 
  //   if (!is_array($array)) { 
  //     return FALSE; 
  //   } 
  //   $result = array(); 
  //   foreach ($array as $key => $value) { 
  //     if (is_array($value)) { 
  //       $result = array_merge($result, $this->array_flatten($value)); 
  //     } 
  //     else { 
  //       $result[$key] = $value; 
  //     } 
  //   } 
  //   return $result; 
  // } 

  public function get_last_month_avg_data(){
    //$this->output->enable_profiler(TRUE);

    $date1 = date('Y-m-d', strtotime('today - 30 days'));
    $date2 = date('Y-m-d', strtotime('today - 1 days'));
    $sales_region = $this->db->get_where('sales_region')->result_array();

    $html = ' <div class="widget-title text-center">Last 30 Days Avg. Report '.date('d-m-Y',strtotime($date1)).' From To '.date('d-m-Y',strtotime($date2)).'</div><hr>
                <table id="example1" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align:center;font-size:8px;">Region</th>
                            <th style="text-align:center;font-size:8px;">Sales Persons</th>';
    for ($i=1; $i <= 30; $i++) {
      $date_new = date('Y-m-d', strtotime($date1 . ' -1 day'));
      $date = date('Y-m-d', strtotime($date_new . ' +'.$i.' day'));
      $html .= '<th style="text-align:center;font-size:8px;" colspan="3" style="text-align:center;">'.date('d-m-Y',strtotime($date)).'</th>';
    }
    $html .= '<th style="text-align:center;font-size:8px;" style="text-align:center;">-</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                <th style="text-align:center;font-size:8px;"></th>
                <th style="text-align:center;font-size:8px;"></th>
                <th id="avg_sales_region" style="text-align:center;font-size:8px;display:none;">'.count($sales_region).'</th>';
    for ($y=1; $y <= 30; $y++) { 
      $html .='<th style="text-align:center;font-size:8px;background:#F9B1A5;">Visits</th>
              <th style="text-align:center;font-size:8px;background:#F2F751;">NAD</th>
              <th style="text-align:center;font-size:8px;background:#51F797;">New Signings</th>';
    }
    $html .='<th style="text-align:center;font-size:8px;background:#AFFF33;">Grand Total</th></tr>';

    $total_users = array();
    $s = 1;
    $grand_total = array();
    foreach($sales_region as $key => $region){
      $get_user = $this->db->where(array('dept_name' => 1,'b_status'=>1,'sales_region' => $region['region_id']))->from('tbl_admin')->count_all_results();
      array_push($total_users,$get_user);
      $html .='<tr>
                 <th style="text-align:center;font-size:8px;">'.$region['name'].'</th>
                 <th style="text-align:center;font-size:8px;">'.$get_user.'</th>';

      
      $total_data = array(); 
      $total = 0;
      for ($z=1; $z <=30 ; $z++) {
        
        $date_new = date('Y-m-d', strtotime($date1 . ' -1 day'));
        $date = date('Y-m-d', strtotime($date_new . ' +'.$z.' day'));
        $get_visit = $this->db->query("SELECT tbl_visit.* FROM `tbl_visit` INNER JOIN enquiry ON tbl_visit.enquiry_id= enquiry.enquiry_id WHERE enquiry.sales_region=".$region['region_id']." AND DATE(tbl_visit.created_at)=".'"'.$date.'"')->result_array();
        $get_nad = $this->db->where(array('sales_region' => $region['region_id'],'DATE(created_date)' => $date,'status' => 1))->from('enquiry')->count_all_results();
        $get_sinings = $this->db->where(array('sales_region' => $region['region_id'],'DATE(created_date)' => $date,'status' => 5))->from('enquiry')->count_all_results();
        
        if(count($get_visit) > 0){
          $visit_color = "background:#F9B1A5";
        }else{
          $visit_color = "";
        }

        if($get_nad > 0){
          $nad_color = "background:#F2F751";
        }else{
          $nad_color = "";
        }

        if($get_sinings > 0){
          $sinings_color = "background:#51F797";
        }else{
          $sinings_color = "";
        }
        
        $total = (count($get_visit)+$get_nad+$get_sinings);
        array_push($total_data,$total);

        $html .='<th id="avg_visit'.$s.'_'.$z.'" style="text-align:center;font-size:8px;'.$visit_color.'">'.count($get_visit).'</th>
                <th  id="avg_nad'.$s.'_'.$z.'" style="text-align:center;font-size:8px;'.$nad_color.'">'.$get_nad.'</th>
                <th  id="avg_sinings'.$s.'_'.$z.'" style="text-align:center;font-size:8px;'.$sinings_color.'">'.$get_sinings.'</th>';
      }

      array_push($total_data,$get_user);
      $html .='<th style="text-align:center;font-size:8px;background:#AFFF33;">'.array_sum($total_data).'</th></tr>';
      array_push($grand_total,array_sum($total_data));
      $html .='</tr>';
      $s++;
    }
  
    $html .='<tr style="background:#AFFF33;"><th style="text-align:center;font-size:8px;">Grand Total</th> <th style="text-align:center;font-size:8px;">'.array_sum($total_users).'</th>';

        $c = 0;
        for ($x=1; $x <= 30; $x++) {
          $html .='<th id="avg_res_visit'.$x.'" style="text-align:center;font-size:8px;"></th>
                  <th id="avg_res_nad'.$x.'" style="text-align:center;font-size:8px;"></th>
                  <th id="avg_res_signings'.$x.'" style="text-align:center;font-size:8px;"></th>';
          $c++;
        }

    $html .='<th style="text-align:center;font-size:8px;background:#AFFF33;">'.array_sum($grand_total).'</th></tr>';
    $html .='</tbody>
          </table>';

    echo $html;
  }


  public function analaytics_mail()
  {
    
    //fetch all schedule data
    //$this->db->where('type',1);
    $variable = $this->db->where('schedule_status', 1)->get('reports')->result();
    foreach ($variable as $key => $value) {
      $schid = $value->id;
      $schdate = date('Y-m-d');
      $type = $value->type;
      //count data's
      $filters = json_decode($value->filters, true);
      // print_r($filters);
      $data['title'] = 'View Report';
      $data['filters'] = $filters;
      $comp_id = $value->comp_id;
      $users_id = $value->mail_users;
      $user_id = $value->created_by;
      // low priority
      $users_id = explode(',', $users_id);
      $rdate = date('Y-m-d', strtotime('-1 day'));

      if ($type == 1) {

          // $subject = 'Daily CRM Report';//'Sales Report : ' . date("F jS, Y", strtotime($schdate));
     
          // if(!empty($filters['enq_product'])){
          //   $this->db->where_in('enquiry.product_id',$filters['enq_product']);
          // }

          // $data['created'] =  $this->db->where(array('comp_id' => $comp_id, 'Date(created_date)' => $rdate))->count_all_results('enquiry');
          // //echo 'created '.$this->db->last_query();
     
          // if(!empty($filters['enq_product'])){
          //   $this->db->where_in('enquiry.product_id',$filters['enq_product']);
          // }
          // $data['assigned'] =   $this->db->where(array('enquiry.comp_id' => $comp_id, 'Date(tbl_comment.created_date)' => $rdate, 'tbl_comment.comment_msg' => 'Enquiry Assigned'))
          // ->join('tbl_comment', 'tbl_comment.lead_id=enquiry.Enquery_id')->count_all_results('enquiry');
     
          // //echo '<br>assigned '.$this->db->last_query();

          // if(!empty($filters['enq_product'])){
          //   $this->db->where_in('enquiry.product_id',$filters['enq_product']);
          // }
          // $data['updated'] =   $this->db->where(array('comp_id' => $comp_id, 'Date(update_date)' => $rdate))->count_all_results('enquiry');
     
          // //echo '<br>updated '.$this->db->last_query();

          // if(!empty($filters['enq_product'])){
          //   $this->db->where_in('enquiry.product_id',$filters['enq_product']);
          // }
          // $data['followups'] =   $this->db->where(array('tbl_comment.comp_id' => $comp_id, 'Date(tbl_comment.created_date)' => $rdate))->join('enquiry','enquiry.Enquery_id=tbl_comment.lead_id')->count_all_results('tbl_comment');
      
          // //echo '<br>followuos '.$this->db->last_query();

          // if(!empty($filters['enq_product'])){
          //   $this->db->where_in('enquiry.product_id',$filters['enq_product']);
          // }
          // $data['all_closed'] =   $this->db->where(array('comp_id' => $comp_id, 'status' => 3, 'Date(update_date)' => $rdate))->count_all_results('enquiry');
     
          // //echo '<br>Closed '.$this->db->last_query();

          // if(!empty($filters['enq_product'])){
          //   $this->db->where_in('enquiry.product_id',$filters['enq_product']);
          // }
          // $data['pending'] =   $this->db->where(array('comp_id' => $comp_id, 'created_date = update_date', 'Date(update_date)' => $rdate))->count_all_results('enquiry');

          //echo '<br>Pending '.$this->db->last_query();



          $subject = 'Sales Report : ' . date("F jS, Y", strtotime($schdate));
          $data['created'] =  $this->db->where(array('comp_id' => $comp_id, 'Date(created_date)' => $rdate))->count_all_results('enquiry');
          $data['assigned'] =   $this->db->where(array('enquiry.comp_id' => $comp_id, 'Date(tbl_comment.created_date)' => $rdate, 'tbl_comment.comment_msg' => 'Enquiry Assigned'))
            ->join('tbl_comment', 'tbl_comment.lead_id=enquiry.Enquery_id')->count_all_results('enquiry');
          $data['updated'] =   $this->db->where(array('comp_id' => $comp_id, 'Date(update_date)' => $rdate))->count_all_results('enquiry');
          $data['followups'] =   $this->db->where(array('comp_id' => $comp_id, 'Date(created_date)' => $rdate))->count_all_results('tbl_comment');
          $data['all_closed'] =   $this->db->where(array('comp_id' => $comp_id, 'status' => 3, 'Date(update_date)' => $rdate))->count_all_results('enquiry');
          $data['pending'] =   $this->db->where(array('comp_id' => $comp_id, 'created_date = update_date', 'Date(update_date)' => $rdate))->count_all_results('enquiry');
          $todays = date('Y-m-d');
          
          //$data['get_today_call'] = $this->db->get_where('tbl_comment',array('coment_type' => 5,'date(created_date)'=>$rdate))->num_rows();
          $data['get_today_call'] = $this->db->get_where('tbl_visit',array('date(created_at)'=>$rdate))->num_rows();
          
          $data['get_today_order'] = $this->db->get_where('enquiry',array('status' => 5))->num_rows();
          
          $data['get_today_nad'] = $this->db->get_where('enquiry',array('status' => 1,'date(created_date)'=>$rdate))->num_rows();
          
          $data['get_today_prospect'] = $this->db->get_where('enquiry',array('status' => 2,'date(created_date)'=>$rdate))->num_rows();
          $data['get_today_approach'] = $this->db->get_where('enquiry',array('status' => 3,'date(created_date)'=>$rdate))->num_rows();
          $data['get_today_negociation'] = $this->db->get_where('enquiry',array('status' => 4,'date(created_date)'=>$rdate))->num_rows();
          $data['get_today_order'] = $this->db->get_where('enquiry',array('status' => 6,'date(created_date)'=>$rdate))->num_rows();
          $data['get_today_fo'] = $this->db->get_where('enquiry',array('status' => 5,'date(created_date)'=>$rdate))->num_rows();
  
      } else {
        
        $subject = 'Daily CRM Report';//'Ticket Report : ' . date("F jS, Y", strtotime($schdate));

        $data['created'] =  $this->db->where(array('company' => $comp_id, 'Date(coml_date)' => $rdate,'process_id'=>$filters['process_id']))->count_all_results('tbl_ticket');
        
        $data['assigned'] =   $this->db->where(array('tbl_ticket.company' => $comp_id, 'Date(tbl_ticket_conv.send_date)' => $rdate, 'tbl_ticket_conv.subj' => 'Ticked Assigned','tbl_ticket.process_id'=>$filters['process_id']))
          ->join('tbl_ticket_conv', 'tbl_ticket_conv.tck_id=tbl_ticket.id')->count_all_results('tbl_ticket');
        
        $data['updated'] =   $this->db->where(array('company' => $comp_id, 'Date(last_update)' => $rdate,'tbl_ticket.process_id'=>$filters['process_id']))->count_all_results('tbl_ticket');
        
        $data['followups'] =   $this->db->where(array('comp_id' => $comp_id, 'Date(tbl_ticket_conv.send_date)' => $rdate,'tbl_ticket.process_id'=>$filters['process_id']))->join('tbl_ticket', 'tbl_ticket_conv.tck_id=tbl_ticket.id')->count_all_results('tbl_ticket_conv');
        
        $data['all_closed'] =   $this->db->where(array('company' => $comp_id, 'ticket_status' => 3, 'Date(last_update)' => $rdate,'tbl_ticket.process_id'=>$filters['process_id']))->count_all_results('tbl_ticket');
        
        $data['pending'] =   $this->db->where(array('company' => $comp_id, 'last_update = coml_date', 'Date(last_update)' => $rdate,'tbl_ticket.process_id'=>$filters['process_id']))->count_all_results('tbl_ticket');

      }
      $encrypted_string = $this->encryption->encrypt($schid);
      $schid  = str_replace(array('+', '/', '='), array('-', '_', '~'),  $encrypted_string);
      $datest = $this->encryption->encrypt($schdate);
      $schdate  = str_replace(array('+', '/', '='), array('-', '_', '~'),  $datest);
      $data['links'] = base_url('report-view/' . $schid . '/' . $schdate . '/');;
      $this->db->where('comp_id', $comp_id);
      $this->db->where('status', 1);
      $email_row  =  $this->db->get('email_integration')->row_array();
      if (empty($email_row)) {
        echo "Email is not configured";
        exit();
      } else {

        $config['smtp_auth']    = true;
        $config['protocol']     = $email_row['protocol'];
        $config['smtp_host']    = $email_row['smtp_host'];
        $config['smtp_port']    = $email_row['smtp_port'];
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = $email_row['smtp_user'];
        $config['smtp_pass']    = $email_row['smtp_pass'];
        $config['charset']      = 'utf-8';
        $config['mailtype']     = 'html'; // or html
        $config['newline']      = "\r\n";

        //    $config['smtp_auth']    = true;
        // $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://smtp.zoho.com';
        // $config['smtp_port']    = 465;
        // $config['smtp_timeout'] = '7';
        // $config['smtp_user']    = 'suraj@archiztechnologies.com';
        // $config['smtp_pass']    = 'Archiz321';
        // $config['charset']      = 'utf-8';
        // $config['mailtype']     = 'html'; // or html
        // $config['newline']      = "\r\n";
        $this->email->initialize($config);
        $from = $config['smtp_user'];
        //$config['validation']   = TRUE; // bool whether to validate email or not  
       
        foreach ($users_id as $value_id) {
          $userdata = $this->db->select('s_user_email,pk_i_admin_id,s_display_name')->where(array('pk_i_admin_id' => $value_id, 'b_status' => 1))->get('tbl_admin')->row();

          if(!empty($userdata)){
            $to = $userdata->s_user_email;            
            $data['userName']=$userdata->s_display_name;
            if($type == 1){
              $view_load = $this->load->view('mail-temps/report-mail', $data, true);
            }else{
              $view_load = $this->load->view('mail-temps/ticket-mail-temp', $data, true);
            }
            //$view_load = $this->load->view('mail-temps/report-mail', $data, true);
            //echo $view_load;
            $this->email->set_newline("\r\n");
            $this->email->clear(TRUE);
            $this->email->from($from);
            $this->email->to($to);
            $cc = 'dheeraj@archizsolutions.com';
            $this->email->cc($cc);
            $this->email->subject($subject);
            $this->email->message($view_load);
            if ($this->email->send()) {
            //if (1) {
              //echo $view_load;
              //exit;
              $insert_array = array(
                'email_to'=>$to,
                'cc'=>$cc,
                'content'=>'Status : success Report_id: '.$value->id.' Report Title : '.$value->name.' Type'.$value->type,                
              );
              echo 'Your Email has successfully been sent.';
            } else {
              $error = $this->email->print_debugger();
              $insert_array = array(
                'email_to'=>$to,
                'cc'=>$cc,
                'content'=>json_encode($config).' Status : success Report_id: '.$value->id.' Report Title : '.$value->name.' Type'.$value->type.' error : '.$error,                
              );
              //show_error($error);
              echo "error<br>";
            }
            $this->db->insert('report_email_log',$insert_array);
          }else{
            echo 'User is inactive';
          }

        }
      }
    }
  }
  //ticket 


  public function ticket_report_view($id)
  {
    if (user_role('120') == true) {
    }
    $data['rid'] = $id;
    $this->session->set_userdata('ticket_reportid', $id);
    $this->db->where('id', $id);
    $report_row =   $this->db->where('type', 2)->get('reports')->row_array(); 
    $data['filters'] = json_decode($report_row['filters'], true);
    // print_r($filters);
    // die();
    $from = $this->session->set_userdata('fromdt', $this->input->post("from"));
    $to =  $this->session->set_userdata('todt', $this->input->post("to"));
    $data['title'] = 'View Ticket Report';
    $this->session->set_userdata('ticket_filters_sess', $data['filters']);

    $data['content'] = $this->load->view('reports/ticket_report_view', $data, true);
    $this->load->view('layout/main_wrapper', $data);
  }
  public function report_view_data()
  {
    if (user_role('120') == true) {
    }
    $fieldsval  = $this->report_model->getdynfielsval();
    $dfields    = $this->report_model->get_dynfields("");
    $this->load->model('report_datatable_model');
    $no = $_POST['start'];
    $this->db->where('id', $this->session->userdata('reportid'));
    $report_row =   $this->db->get('reports')->row_array();
    $filters = json_decode($report_row['filters'], true);
    $filters1 = $filters;
    $report_columns = $filters1['report_columns'];

    $data['from'] = '';
    $data['to'] = '';

    $from = $this->session->userdata('fromdt');
    $to =  $this->session->userdata('todt');

    if ($from && $to) {
      $from = date("d/m/Y", strtotime($from));
      $to = date("d/m/Y", strtotime($to));
    } else {
      if (empty($filters['from_exp'])) {
        $from = '';
      } else {
        $dfrom = $filters['from_exp'];
        $from = date("d/m/Y", strtotime($dfrom));
        $from1 = $filters['from_exp'];
        $data['from'] = $from1;
      }
      if (empty($filters['to_exp'])) {
        $to = '';
      } else {
        $tto = $filters['to_exp'];
        $to = date("d/m/Y", strtotime($tto));
        $to1 = $filters['to_exp'];
        $data['to'] = $to1;
      }
    }
    $updated_from = $this->session->userdata('updated_from1');
    $updated_to =  $this->session->userdata('updated_to1');

    if ($updated_from && $updated_to) {
      $updated_from = date("d/m/Y", strtotime($updated_from));
      $updated_to = date("d/m/Y", strtotime($updated_to));
    } else {
      if (empty($filters['updated_from_exp'])) {
        $updated_from = '';
      } else {
        $updated_dfrom = $filters['updated_from_exp'];
        $updated_from = date("d/m/Y", strtotime($updated_dfrom));
        $updated_from1 = $filters['updated_from_exp'];
        $data['updated_from'] = $updated_from1;
      }
      if (empty($filters['updated_to_exp'])) {
        $updated_to = '';
      } else {
        $updated_tto = $filters['updated_to_exp'];
        $updated_to = date("d/m/Y", strtotime($updated_tto));
        $updated_to1 = $filters['updated_to_exp'];
        $data['updated_to'] = $updated_to1;
      }
    }
    if (empty($filters['employee'])) {
      $employe = '';
    } else {
      $employe = $filters['employee'];
    }
    if (empty($filters['phone'])) {
      $phone = '';
    } else {
      $phone = $filters['phone'];
    }
    if (empty($filters['country'])) {
      $country = '';
    } else {
      $country = $filters['country'];
    }
    if (empty($filters['institute'])) {
      $institute = '';
    } else {
      $institute = $filters['institute'];
    }
    if (empty($filters['center'])) {
      $center = '';
    } else {
      $center = $filters['center'];
    }
    if (empty($filters['source'])) {
      $source = '';
    } else {
      $source = $filters['source'];
    }
    if (empty($filters['subsource'])) {
      $subsource = '';
    } else {
      $subsource = $filters['source'];
    }
    if (empty($filters['datasource'])) {
      $datasource = '';
    } else {
      $datasource = $filters['datasource'];
    }
    if (empty($filters['state'])) {
      $state = '';
    } else {
      $state = $filters['state'];
    }
    if (empty($filters['lead_source'])) {
      $lead_source = '';
    } else {
      $lead_source = $filters['lead_source'];
    }
    if (empty($filters['lead_subsource'])) {
      $lead_subsource = '';
    } else {
      $lead_subsource = $filters['lead_subsource'];
    }
    if (empty($filters['enq_product'])) {
      $enq_product = '';
    } else {
      $enq_product = $filters['enq_product'];
    }
    if (empty($filters['drop_status'])) {
      $drop_status = '';
    } else {
      $drop_status = $filters['drop_status'];
    }
    if (empty($filters['all'])) { // follow up report
      $all = '';
    } else {
      $all = $filters['all'];
    }
    $rep_details = $this->report_datatable_model->get_datatables($from, $to, $updated_from, $updated_to, $employe, $phone, $country, $institute, $center, $source, $subsource, $datasource, $state, $lead_source, $lead_subsource, $enq_product, $drop_status, $all);
    $i = 1;
    $data = array();
    foreach ($rep_details as  $repdetails) {

      $no++;
      $row = array();

      if (in_array('S.No', $report_columns)) {
        $row[] = $i++;
      }
      if (in_array('Name', $report_columns)) {
        $row[] = $repdetails->name_prefix . " " . $repdetails->name . " " . $repdetails->lastname;
      }
      if (in_array('Phone', $report_columns)) {
        if (user_access(450)) {
          $row[] = '##########';
        } else {
          $row[] = $repdetails->phone;
        }
      }
      if (in_array('Email', $report_columns)) {
        $row[] = $repdetails->email;
      }
      if (in_array('Created By', $report_columns)) {
        $row[] = $repdetails->created_by_name;
      }
      if (in_array('Assign To', $report_columns)) {
        $row[] = (!empty($repdetails->assign_to_name)) ? $repdetails->assign_to_name : 'NA';
      }
      if (in_array('Gender', $report_columns)) {
        if ($repdetails->gender == 1) {
          $gender = 'Male';
        } else if ($repdetails->gender == 2) {
          $gender = 'Female';
        } else {
          $gender = 'Other';
        }
        $row[] = $gender;
      }
      if (in_array('Source', $report_columns)) {
        $row[] = (!empty($repdetails->lead_name)) ? $repdetails->lead_name : 'NA';
      }
      if (in_array('Subsource', $report_columns)) {
        $row[] = (!empty($repdetails->subsource_name)) ? $repdetails->subsource_name : 'NA';
      }
      if (in_array('Disposition', $report_columns)) {
        $row[] = (!empty($repdetails->followup_name)) ? $repdetails->followup_name : 'NA';
      }
      if (in_array('Lead Description', $report_columns)) {
        $row[] = (!empty($repdetails->description)) ? $repdetails->description : "NA";
      }
      if (in_array('Disposition Remark', $report_columns)) {
        $row[] = (!empty($repdetails->lead_discription_reamrk)) ? $repdetails->lead_discription_reamrk : "NA";
      }
      if (in_array('Drop Reason', $report_columns)) {
        $row[] = (!empty($repdetails->drop_status)) ? $repdetails->drop_status : "NA";
      }
      if (in_array('Drop Comment', $report_columns)) {
        $row[] = (!empty($repdetails->drop_reason)) ? $repdetails->drop_reason : "NA";
      }
      if (in_array('Conversion Probability', $report_columns)) {
        $row[] = (!empty($repdetails->lead_score)) ? $repdetails->lead_score : "NA";
      }
      if (in_array('Remark', $report_columns)) {
        $row[] = (!empty($repdetails->enq_remark)) ? $repdetails->enq_remark : "NA";
      }

      if (in_array('Status', $report_columns)) {
        // if ($repdetails->status == 1) {
        //   $status = 'Enquiry';
        // } else if ($repdetails->status == 2) {
        //   $status = 'Lead';
        // } else {
        //   $status = 'Client';
        // }
        $row[] = $repdetails->status_title;
      }
      if (in_array('DOE', $report_columns)) {
        $row[] = $repdetails->inq_created_date;
      }
      if (in_array('Process', $report_columns)) {
        $row[] =  (!empty($repdetails->product_name)) ? $repdetails->product_name : 'NA';
      }
      if (in_array('Updated Date', $report_columns)) {
        $row[] = $repdetails->update_date;
      }
      if (in_array('State', $report_columns)) {
        $row[] = (!empty($repdetails->state_name)) ? $repdetails->state_name : 'NA';
      }
      if (in_array('City', $report_columns)) {
        $row[] = (!empty($repdetails->city_name)) ? $repdetails->city_name : 'NA';
      }

      if (in_array('Sales Region', $report_columns)) {
        $row[] = (!empty($repdetails->region_name)) ? $repdetails->region_name : 'NA';
      }
      if (in_array('Sales Area', $report_columns)) {
        $row[] = (!empty($repdetails->area_name)) ? $repdetails->area_name : 'NA';
      }
      if (in_array('Sales Branch', $report_columns)) {
        $row[] = (!empty($repdetails->branch_name)) ? $repdetails->branch_name : 'NA';
      }


      if (in_array('Company Name', $report_columns)) {
        $row[] = (!empty($repdetails->company)) ? $repdetails->company : 'NA';
      }
      if (in_array('Product', $report_columns)) {
        $row[] = (!empty($repdetails->enq_product_name)) ? $repdetails->enq_product_name : 'NA';
      }
      if (in_array('Enquiry Id', $report_columns)) {
        $row[] = (!empty($repdetails->Enquery_id)) ? $repdetails->Enquery_id : 'NA';
      }
      if (!empty($dfields)) {
        foreach ($dfields as $ind => $dfld) {
          if (in_array(trim($dfld['input_label']), $report_columns)) {
            if (!empty($fieldsval)) {
              if (!empty($fieldsval[$repdetails->enquiry_id])) {
                if (!empty($fieldsval[$repdetails->enquiry_id][$dfld['input_label']])) {
                  $row[] = $fieldsval[$repdetails->enquiry_id][$dfld['input_label']]->fvalue;
                } else {
                  $row[] = "NA";
                }
              } else {
                $row[] = "NA";
              }
            } else {
              $row[] =  "NA";
            }
          }
        }
      }
      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->report_datatable_model->count_all(),
      "recordsFiltered" => $this->report_datatable_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }
  
  public function deal_report_view_data()
  {

    $this->load->model('report_datatable_model');
    $no = $_POST['start'];
    $this->db->where('id', $this->session->userdata('reportid'));
    $report_row =   $this->db->get('reports')->row_array();
    $filters = json_decode($report_row['filters'], true);
    $filters1 = $filters;
    $report_columns = $filters1['report_columns'];

    $data['from'] = '';
    $data['to'] = '';

    $from = $this->session->userdata('fromdt');
    $to =  $this->session->userdata('todt');

    if ($from && $to) {
      $from = date("d/m/Y", strtotime($from));
      $to = date("d/m/Y", strtotime($to));
    } else {
      if (empty($filters['from_exp'])) {
        $from = '';
      } else {
        $dfrom = $filters['from_exp'];
        $from = date("d/m/Y", strtotime($dfrom));
        $from1 = $filters['from_exp'];
        $data['from'] = $from1;
      }
      if (empty($filters['to_exp'])) {
        $to = '';
      } else {
        $tto = $filters['to_exp'];
        $to = date("d/m/Y", strtotime($tto));
        $to1 = $filters['to_exp'];
        $data['to'] = $to1;
      }
    }
    $updated_from = $this->session->userdata('updated_from1');
    $updated_to =  $this->session->userdata('updated_to1');

    if ($updated_from && $updated_to) {
      $updated_from = date("d/m/Y", strtotime($updated_from));
      $updated_to = date("d/m/Y", strtotime($updated_to));
    } else {
      if (empty($filters['updated_from_exp'])) {
        $updated_from = '';
      } else {
        $updated_dfrom = $filters['updated_from_exp'];
        $updated_from = date("d/m/Y", strtotime($updated_dfrom));
        $updated_from1 = $filters['updated_from_exp'];
        $data['updated_from'] = $updated_from1;
      }
      if (empty($filters['updated_to_exp'])) {
        $updated_to = '';
      } else {
        $updated_tto = $filters['updated_to_exp'];
        $updated_to = date("d/m/Y", strtotime($updated_tto));
        $updated_to1 = $filters['updated_to_exp'];
        $data['updated_to'] = $updated_to1;
      }
    }
    if (empty($filters['employee'])) {
      $employe = '';
    } else {
      $employe = $filters['employee'];
    }

    if (empty($filters['state'])) {
      $state = '';
    } else {
      $state = $filters['state'];
    }
    
    if (empty($filters['all'])) { // follow up report
      $all = '';
    } else {
      $all = $filters['all'];
    }
    $deal_details = $this->report_datatable_model->deal_get_datatables($from, $to, $updated_from, $updated_to, $employe, $all);
    $i = 1;
    $data = array();
    foreach ($deal_details as  $dealdetails) {

      $no++;
      $row = array();

      if (in_array('S.No', $this->session->userdata('post_report_columns'))) {
        $row[] = $i++;
      }
      if (in_array('Quatation No', $this->session->userdata('post_report_columns'))) {
		$row[] = (!empty($dealdetails->quatation_number)) ? $dealdetails->quatation_number : 'NA';
      }
	  
	  if (in_array('Quatation Amt', $this->session->userdata('post_report_columns'))) {
		$row[] = (!empty($dealdetails->qotation_amount)) ? (int)(($dealdetails->qotation_amount*100))/100 : 'NA';
      }
	  
	  if (in_array('Name', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->deal_name)) ? $dealdetails->deal_name : 'NA';
      }
	  
	  if (in_array('Client Name', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->client_name)) ? $dealdetails->client_name : 'NA';
      }
	  
	  if (in_array('Business Type', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->business_type)) ? $dealdetails->business_type : 'NA';
      }
	  
	  if (in_array('Booking Type', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->booking_type)) ? $dealdetails->booking_type : 'NA';
      }
	  
	  if (in_array('Deal Type', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->deal_type)) ? $dealdetails->deal_type : 'NA';
      }
	  
	  if (in_array('Deal Insurance', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->insurance)) ? $dealdetails->insurance : 'NA';
      }

      if (in_array('Deal Stage', $this->session->userdata('post_report_columns'))) {
         if ($dealdetails->stage_id == 1) {
           $stage = 'Lead';
         } else if ($dealdetails->stage_id == 2) {
           $stage = 'Approach';
         } else if ($dealdetails->stage_id == 3){
           $stage = 'Negotiation';
         }else if ($dealdetails->stage_id == 4){
           $stage = 'Closer';
         }else if ($dealdetails->stage_id == 5){
           $stage = 'Order';
         }else if ($dealdetails->stage_id == 6){
           $stage = 'Future Oppotunities';
         }
        $row[] = (!empty($stage)) ? $stage : 'NA';
      }
	  
	  if (in_array('Deal Status', $this->session->userdata('post_report_columns'))) {
		  if ($dealdetails->status == 1) {
           $status = 'Done';
         } else {
           $status = 'Pending';
         }
        $row[] = (!empty($status)) ? $status : 'NA';
      }
	  
	  if (in_array('Created By', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->createdby)) ? $dealdetails->createdby : 'NA';
      }
	  
	  if (in_array('Created Date', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->creation_date)) ? $dealdetails->creation_date : 'NA';
      }
	  
	  if (in_array('Updated Date', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->updation_date)) ? $dealdetails->updation_date : 'NA';
      }
	  
	  if (in_array('Edit Remark', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->edit_remark)) ? $dealdetails->edit_remark : 'NA';
      }
	  
      $data[] = $row;
    } 
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->report_datatable_model->count_all_deal(),
      "recordsFiltered" => $this->report_datatable_model->count_filtered_deal(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function delete_report_row()
  {
    $id = $this->input->post('id');
    $this->db->where('id', $id);
    $this->db->delete('reports');
    echo 1;
  }
  public function view_details()
  {
    if ($this->input->post('from_exp') == '') {
      $from = '';
    } else {
      $dfrom = $this->input->post('from_exp');
      $from = date("d/m/Y", strtotime($dfrom));
    }

    if ($this->input->post('to_exp') == '') {
      $to = '';
    } else {
      $tto = $this->input->post('to_exp');
      $to = date("d/m/Y", strtotime($tto));
    }

    if ($this->input->post('updated_from_exp') == '') {
      $updated_from = '';
    } else {
      $updated_dfrom = $this->input->post('updated_from_exp');
      $updated_from = date("d/m/Y", strtotime($updated_dfrom));
    }

    if ($this->input->post('updated_to_exp') == '') {
      $updated_to = '';
    } else {
      $updated_tto = $this->input->post('updated_to_exp');
      $updated_to = date("d/m/Y", strtotime($updated_tto));
    }
    if ($this->input->post('employee') == '') {
      $employe = '';
    } else {
      $employe = $this->input->post('employee');
    }
    if ($this->input->post('phone') == '') {
      $phone = '';
    } else {
      $phone = $this->input->post('phone');
    }
    if ($this->input->post('country') == '') {
      $country = '';
    } else {
      $country = $this->input->post('country');
    }
    if ($this->input->post('institute') == '') {
      $institute = '';
    } else {
      $institute = $this->input->post('institute');
    }
    if ($this->input->post('center') == '') {
      $center = '';
    } else {
      $center = $this->input->post('center');
    }
    if ($this->input->post('source') == '') {
      $source = '';
    } else {
      $source = $this->input->post('source');
    }
    if ($this->input->post('subsource') == '') {
      $subsource = '';
    } else {
      $subsource = $this->input->post('subsource');
    }
    if ($this->input->post('datasource') == '') {
      $datasource = '';
    } else {
      $datasource = $this->input->post('datasource');
    }
    if ($this->input->post('state') == '') {
      $state = '';
    } else {
      $state = $this->input->post('state');
    }
    if ($this->input->post('lead_source') == '') { // disposition
      $lead_source = '';
    } else {
      $lead_source = $this->input->post('lead_source');
    }

    if ($this->input->post('lead_subsource') == '') {
      $lead_subsource = '';
    } else {
      $lead_subsource = $this->input->post('lead_subsource');
    }
    if ($this->input->post('sub_disposition') == '') {
      $sub_disposition = '';
    } else {
      $sub_disposition = $this->input->post('sub_disposition');
    }


    if ($this->input->post('enq_product') == '') {
      $enq_product = '';
    } else {
      $enq_product = $this->input->post('enq_product');
    }
    if ($this->input->post('productlst') == '') {
      $productlst = '';
    } else {
      $productlst = $this->input->post('productlst');
    }
    if ($this->input->post('drop_status') == '') {
      $drop_status = '';
    } else {
      $drop_status = $this->input->post('drop_status');
    }
    if ($this->input->post('hier_wise') == '') {
      $hier_wise = '';
    } else {
      $hier_wise = $this->input->post('hier_wise');
    }
    if ($this->input->post('Enquiry_Id') == '') {
      $Enquiry_Id = '';
    } else {
      $Enquiry_Id = $this->input->post('Enquiry_Id');
    }


    if ($this->input->post('region') == '') {
      $region = '';
    } else {
      $region = $this->input->post('region');
    }

    if ($this->input->post('area') == '') {
      $area = '';
    } else {
      $area = $this->input->post('area');
    }


    if ($this->input->post('branch') == '') {
      $branch = '';
    } else {
      $branch = $this->input->post('branch');
    }



    $data['post_report_columns'] = $this->input->post('report_columns');
    // print_r($data['post_report_columns']);
    // die();
    $post_report_columns = $this->input->post('report_columns');
    if ($this->input->post('all') == '') { // follow up report
      $all = '';
    } else {
      $all = $this->input->post('all');
    }
    $data_arr = array(
      'from1'           =>  $from,
      'to1'             =>  $to,
      'updated_from1'   =>  $updated_from,
      'updated_to1'     =>  $updated_to,
      'employe1'        =>  $employe,
      'phone1'          =>  $phone,
      'country1'        =>  $country,
      'institute1'      =>  $institute,
      'center1'         =>  $center,
      'source1'         =>  $source,
      'subsource1'      =>  $subsource,
      'datasource1'     =>  $datasource,
      'state1'          =>  $state,
      'lead_source1'    =>  $lead_source,
      'lead_subsource1' =>  $lead_subsource,
      'sub_disposition' =>  $sub_disposition,
      'enq_product1'    =>  $enq_product,
      'drop_status1'    =>  $drop_status,
      'all1'            =>  $all,
      'post_report_columns' => $post_report_columns,
      'productlst'  => $productlst,
      'hier_wise'   => $hier_wise,
      'area'        => $area,
      'branch'      => $branch,
      'region'      => $region
    );
    $this->session->set_userdata($data_arr);

    $data['title'] = 'Report';
    $data['all_stage_lists'] = $this->Leads_Model->find_stage();
    $data['all_sub_stage_lists'] = $this->Leads_Model->find_description();
    $data['sourse'] = $this->report_model->all_source();
    $data['subsourse'] = $this->report_model->all_subsource();
    $data['datasourse'] = $this->report_model->all_datasource();

    $data['datasourse'] = $this->report_model->all_datasource();
    $this->load->model('User_model');
    //$data['employee'] =$this->User_model->companey_users();
    $data['employee'] = $this->User_model->read();
    $data['process'] = $this->dash_model->product_list();
    $data["dfields"] = $this->report_model->get_dynfields();
    $this->load->model('Branch_model');
    $data['region_list']=$this->Branch_model->sales_region_list()->result();
    $data['area_list']=$this->Branch_model->sales_area_list()->result();
    $data['branch_list']=$this->Branch_model->branch_list()->result();
    
    $data["fieldsval"]        = $this->report_model->getdynfielsval();
    $data['products'] = $this->location_model->productcountry();
    // print_r($data["fieldsval"]);
    // die();
    $data['content'] = $this->load->view('all_report', $data, true);
    $this->load->view('layout/main_wrapper', $data);
  }
  
  public function deal_report_panel()
  {
    if ($this->input->post('from_exp') == '') {
      $from = '';
    } else {
      $dfrom = $this->input->post('from_exp');
      $from = date("d/m/Y", strtotime($dfrom));
    }

    if ($this->input->post('to_exp') == '') {
      $to = '';
    } else {
      $tto = $this->input->post('to_exp');
      $to = date("d/m/Y", strtotime($tto));
    }

    if ($this->input->post('updated_from_exp') == '') {
      $updated_from = '';
    } else {
      $updated_dfrom = $this->input->post('updated_from_exp');
      $updated_from = date("d/m/Y", strtotime($updated_dfrom));
    }
	
    if ($this->input->post('updated_to_exp') == '') {
      $updated_to = '';
    } else {
      $updated_tto = $this->input->post('updated_to_exp');
      $updated_to = date("d/m/Y", strtotime($updated_tto));
    }
	
    if ($this->input->post('employee') == '') {
      $employe = '';
    } else {
      $employe = $this->input->post('employee');
    }
	
    if ($this->input->post('state') == '') {
      $state = '';
    } else {
      $state = $this->input->post('state');
    }

    $data['post_report_columns'] = $this->input->post('report_columns');
    // print_r($data['post_report_columns']);
    // die();
    $post_report_columns = $this->input->post('report_columns');
    if ($this->input->post('all') == '') {
      $all = '';
    } else {
      $all = $this->input->post('all');
    }
    $data_arr = array(
      'from1'           =>  $from,
      'to1'             =>  $to,
      'updated_from1'   =>  $updated_from,
      'updated_to1'     =>  $updated_to,
      'employe1'        =>  $employe,
      'state1'          =>  $state,
	  'post_report_columns' => $post_report_columns
    );
    $this->session->set_userdata($data_arr);

    $data['title'] = 'Deal report';
    $this->load->model('User_model');
    $data['employee'] = $this->User_model->read();
    $data['content'] = $this->load->view('deal_report', $data, true);
    $this->load->view('layout/main_wrapper', $data);
  }
  public function all_call_log_report_filterdata()
  {
    $todays = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($todays)));
    $this_month = date('m');

    $monday = strtotime("last monday");
    $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
    $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
    $this_week_sd = date("Y-m-d",$monday);
    $this_week_ed = date("Y-m-d",$sunday);

    $previous_week = strtotime("-1 week +1 day");
    $start_week = strtotime("last sunday midnight",$previous_week);
    $end_week = strtotime("next saturday",$start_week);
    $start_week = date("Y-m-d",$start_week);
    $end_week = date("Y-m-d",$end_week);
    // echo $start_week.' '.$end_week ;

    $this_month_sd = date('Y-m-01');
    $this_month_ed  = date('Y-m-t');

    $last_month_sd = Date("Y-m-d", strtotime("first day of previous month"));
    $last_month_ed = Date("Y-m-d", strtotime("last day of previous month"));

    $lastMonth = date("m", strtotime("first day of previous month"));

    $get_all_call = $this->db->where(array('coment_type' => 5))->from('tbl_comment')->count_all_results();
    $get_today_call = $this->db->where(array('coment_type' => 5,'date(created_date)' => $todays))->from('tbl_comment')->count_all_results();
    $get_yesterday_call = $this->db->where(array('coment_type' => 5,'date(created_date)' => $yesterday))->from('tbl_comment')->count_all_results();
    $this->db->where('MONTH(created_date)', $this_month);
    $get_this_month_call = $this->db->where(array('coment_type' => 5))->from('tbl_comment')->count_all_results();    
    $get_last_month_call = $this->db->where(array('coment_type' => 5,'MONTH(created_date)' => $lastMonth))->from('tbl_comment')->count_all_results();
    $this->db->where('date(created_date) >=', $this_week_sd);
    $this->db->where('date(created_date) <=', $this_week_ed);
    $get_this_week_call = $this->db->where(array('coment_type' => 5))->from('tbl_comment')->count_all_results();

    $this->db->where('date(created_date) >=', $start_week);
    $this->db->where('date(created_date) <=', $end_week);
    $get_last_week_call = $this->db->where(array('coment_type' => 5))->from('tbl_comment')->count_all_results();

    $all_users = $this->db->where(array('dept_name' => 1,'b_status'=>1))->from('tbl_admin')->count_all_results();

    $this->db->where('date(created_date) >=', $this_week_sd);
    $this->db->where('date(created_date) <=', $this_week_ed);
    $av_daily_call_this_week = $this->db->where(array('coment_type' => 5))->from('tbl_comment')->count_all_results();

    $this->db->where('date(created_date) >=', $this_month_sd);
    $this->db->where('date(created_date) <=', $this_month_ed);
    $av_daily_call_this_month = $this->db->where(array('coment_type' => 5))->from('tbl_comment')->count_all_results();;
    

    $this->db->where('date(created_date) >=', $last_month_sd);
    $this->db->where('date(created_date) <=', $last_month_ed);
    $av_daily_call_last_month = $this->db->where(array('coment_type' => 5))->from('tbl_comment')->count_all_results();;

    $av_daily_call_today_data = $get_today_call/$all_users;
    $av_daily_call_per_yesterday_data = $get_yesterday_call/$all_users;
    $av_daily_call_this_week_data = $av_daily_call_this_week/$all_users;
    $av_daily_call_last_week_data = $get_last_week_call/$all_users;
    $av_daily_call_this_month_data = $av_daily_call_this_month/$all_users;
    $av_daily_call_last_month_data = $av_daily_call_last_month/$all_users;
    $av_daily_call_total_data = $get_all_call/$all_users;


    $av_daily_call_per_person_today = $get_today_call/$all_users;
    $av_daily_call_per_person_yesterday = $get_yesterday_call/$all_users;
    $av_daily_call_per_person_this_week = $get_this_week_call/$all_users;
    $av_daily_call_per_person_last_week = $get_last_week_call/$all_users;
    $av_daily_call_per_person_this_month = $get_this_month_call/$all_users;
    $av_daily_call_per_person_last_month = $get_last_month_call/$all_users;
    $av_daily_call_per_person_total = $get_all_call/$all_users;

    $getData = array(
      'all_call'                            => $get_all_call,
      'today_call'                          => $get_today_call,
      'this_week'                           => $get_this_week_call,
      'last_week'                           => $get_last_week_call,
      'yesterday_call'                      => $get_yesterday_call,
      'this_month_call'                     => $get_this_month_call,
      'last_month_call'                     => $get_last_month_call,
      'av_daily_call_per_person_today'      => ($av_daily_call_per_person_today>=0)?$av_daily_call_per_person_today:0,
      'av_daily_call_per_person_yesterday'  => ($av_daily_call_per_person_yesterday>=0)?$av_daily_call_per_person_yesterday:0,
      'av_daily_call_per_person_this_week'  => ($av_daily_call_per_person_this_week>=0)?$av_daily_call_per_person_this_week:0,
      'av_daily_call_per_person_last_week'  => ($av_daily_call_per_person_last_week>=0)?$av_daily_call_per_person_last_week:0,
      'av_daily_call_per_person_this_month' => ($av_daily_call_per_person_this_month>=0)?$av_daily_call_per_person_this_month:0,
      'av_daily_call_per_person_last_month' => ($av_daily_call_per_person_last_month>=0)?$av_daily_call_per_person_last_month:0,
      'av_daily_call_per_person_total'      => ($av_daily_call_per_person_total>=0)?$av_daily_call_per_person_total:0,
      'av_daily_call_today_data'            => ($av_daily_call_today_data>=0)?$av_daily_call_today_data:0,
      'av_daily_call_per_yesterday_data'    => ($av_daily_call_per_yesterday_data>=0)?$av_daily_call_per_yesterday_data:0,
      'av_daily_call_this_week_data'        => ($av_daily_call_this_week_data>=0)?$av_daily_call_this_week_data:0,
      'av_daily_call_last_week_data'        => ($av_daily_call_last_week_data>=0)?$av_daily_call_last_week_data:0,
      'av_daily_call_this_month_data'       => ($av_daily_call_this_month_data>=0)?$av_daily_call_this_month_data:0,
      'av_daily_call_last_month_data'       => ($av_daily_call_last_month_data>=0)?$av_daily_call_last_month_data:0,
      'av_daily_call_total_data'            => ($av_daily_call_total_data>=0)?$av_daily_call_total_data:0,
    );

    return $getData;
  }

  public function all_prospect_report_filterdata()
  {
    $todays = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($todays)));
    $this_month = date('m');

    $monday = strtotime("last monday");
    $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
    $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
    $this_week_sd = date("Y-m-d",$monday);
    $this_week_ed = date("Y-m-d",$sunday);

    $previous_week = strtotime("-1 week +1 day");
    $start_week = strtotime("last sunday midnight",$previous_week);
    $end_week = strtotime("next saturday",$start_week);
    $start_week = date("Y-m-d",$start_week);
    $end_week = date("Y-m-d",$end_week);
    // echo $start_week.' '.$end_week ;

    $this_month_sd = date('Y-m-01');
    $this_month_ed  = date('Y-m-t');

    $last_month_sd = Date("Y-m-d", strtotime("first day of previous month"));
    $last_month_ed = Date("Y-m-d", strtotime("last day of previous month"));

    $lastMonth = date("m", strtotime("first day of previous month"));

    $get_all_call = $this->db->where(array('status' => 1))->from('enquiry')->count_all_results();
    $get_today_call = $this->db->where(array('status' => 1,'created_date' => $todays))->from('enquiry')->count_all_results();
    $get_yesterday_call = $this->db->where(array('status' => 1,'created_date' => $yesterday))->from('enquiry')->count_all_results();
    $this->db->where('MONTH(created_date)', $this_month);
    $get_this_month_call = $this->db->where(array('status' => 1))->from('enquiry')->count_all_results();

    $get_last_month_call = $this->db->where(array('status' => 1,'created_date' => $lastMonth))->from('enquiry')->count_all_results();
    $this->db->where('date(created_date) >=', $this_week_sd);
    $this->db->where('date(created_date) <=', $this_week_ed);
    $get_this_week_call = $this->db->where(array('status' => 1))->from('enquiry')->count_all_results();

    $this->db->where('date(created_date) >=', $start_week);
    $this->db->where('date(created_date) <=', $end_week);
    $get_last_week_call = $this->db->where(array('status' => 1))->from('enquiry')->count_all_results();

    $all_users = $this->db->where(array('dept_name' => 1,'b_status'=>1))->from('tbl_admin')->count_all_results();

    $this->db->where('date(created_date) >=', $this_week_sd);
    $this->db->where('date(created_date) <=', $this_week_ed);
    $av_daily_call_this_week = $this->db->where(array('status' => 1))->from('enquiry')->count_all_results();

    $this->db->where('date(created_date) >=', $this_month_sd);
    $this->db->where('date(created_date) <=', $this_month_ed);
    $av_daily_call_this_month = $this->db->where(array('status' => 1))->from('enquiry')->count_all_results();

    $this->db->where('date(created_date) >=', $last_month_sd);
    $this->db->where('date(created_date) <=', $last_month_ed);
    $av_daily_call_last_month = $this->db->where(array('status' => 1))->from('enquiry')->count_all_results();

    $av_daily_call_today_data = $get_today_call/$all_users;
    $av_daily_call_per_yesterday_data = $get_yesterday_call/$all_users;
    $av_daily_call_this_week_data = $av_daily_call_this_week/$all_users;
    $av_daily_call_last_week_data = $get_last_week_call/$all_users;
    $av_daily_call_this_month_data = $av_daily_call_this_month/$all_users;
    $av_daily_call_last_month_data = $av_daily_call_last_month/$all_users;
    $av_daily_call_total_data = $get_all_call/$all_users;


    $av_daily_call_per_person_today = $get_today_call/$all_users;
    $av_daily_call_per_person_yesterday = $get_yesterday_call/$all_users;
    $av_daily_call_per_person_this_week = $get_this_week_call/$all_users;
    $av_daily_call_per_person_last_week = $get_last_week_call/$all_users;
    $av_daily_call_per_person_this_month = $get_this_month_call/$all_users;
    $av_daily_call_per_person_last_month = $get_last_month_call/$all_users;
    $av_daily_call_per_person_total = $get_all_call/$all_users;

    $getData = array(
      'all_call'                            => $get_all_call,
      'today_call'                          => $get_today_call,
      'this_week'                           => $get_this_week_call,
      'last_week'                           => $get_last_week_call,
      'yesterday_call'                      => $get_yesterday_call,
      'this_month_call'                     => $get_this_month_call,
      'last_month_call'                     => $get_last_month_call,
      'av_daily_call_per_person_today'      => ($av_daily_call_per_person_today>=0)?$av_daily_call_per_person_today:0,
      'av_daily_call_per_person_yesterday'  => ($av_daily_call_per_person_yesterday>=0)?$av_daily_call_per_person_yesterday:0,
      'av_daily_call_per_person_this_week'  => ($av_daily_call_per_person_this_week>=0)?$av_daily_call_per_person_this_week:0,
      'av_daily_call_per_person_last_week'  => ($av_daily_call_per_person_last_week>=0)?$av_daily_call_per_person_last_week:0,
      'av_daily_call_per_person_this_month' => ($av_daily_call_per_person_this_month>=0)?$av_daily_call_per_person_this_month:0,
      'av_daily_call_per_person_last_month' => ($av_daily_call_per_person_last_month>=0)?$av_daily_call_per_person_last_month:0,
      'av_daily_call_per_person_total'      => ($av_daily_call_per_person_total>=0)?$av_daily_call_per_person_total:0,
      'av_daily_call_today_data'            => ($av_daily_call_today_data>=0)?$av_daily_call_today_data:0,
      'av_daily_call_per_yesterday_data'    => ($av_daily_call_per_yesterday_data>=0)?$av_daily_call_per_yesterday_data:0,
      'av_daily_call_this_week_data'        => ($av_daily_call_this_week_data>=0)?$av_daily_call_this_week_data:0,
      'av_daily_call_last_week_data'        => ($av_daily_call_last_week_data>=0)?$av_daily_call_last_week_data:0,
      'av_daily_call_this_month_data'       => ($av_daily_call_this_month_data>=0)?$av_daily_call_this_month_data:0,
      'av_daily_call_last_month_data'       => ($av_daily_call_last_month_data>=0)?$av_daily_call_last_month_data:0,
      'av_daily_call_total_data'            => ($av_daily_call_total_data>=0)?$av_daily_call_total_data:0,
    );

    return $getData;
  }

  public function all_visit_report_filterdata()
  {
    $todays = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($todays)));
    $this_month = date('m');

    $monday = strtotime("last monday");
    $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
    $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
    $this_week_sd = date("Y-m-d",$monday);
    $this_week_ed = date("Y-m-d",$sunday);

    $previous_week = strtotime("-1 week +1 day");
    $start_week = strtotime("last sunday midnight",$previous_week);
    $end_week = strtotime("next saturday",$start_week);
    $start_week = date("Y-m-d",$start_week);
    $end_week = date("Y-m-d",$end_week);
    // echo $start_week.' '.$end_week ;

    $this_month_sd = date('Y-m-01');
    $this_month_ed  = date('Y-m-t');

    $last_month_sd = Date("Y-m-d", strtotime("first day of previous month"));
    $last_month_ed = Date("Y-m-d", strtotime("last day of previous month"));

    $lastMonth = date("m", strtotime("first day of previous month"));

    $get_all_call = $this->db->from('tbl_visit')->count_all_results();

    $get_today_call = $this->db->where(array('date(created_at)'=>$todays))->from('tbl_visit')->count_all_results();

    $get_yesterday_call = $this->db->where(array('date(created_at)'=>$yesterday))->from('tbl_visit')->count_all_results();
    
    $get_this_month_call = $this->db->where('MONTH(created_at)', $this_month)->from('tbl_visit')->count_all_results();
    $get_last_month_call = $this->db->where(array('MONTH(created_at)' => $lastMonth))->from('tbl_visit')->count_all_results();
    
    
    $get_this_week_call = $this->db->where('date(created_at)>=', $this_week_sd)->where('date(created_at) <=', $this_week_ed)->from('tbl_visit')->count_all_results();

    $get_last_week_call = $this->db->where('date(created_at) >=', $start_week)->where('created_at <=', $end_week)->from('tbl_visit')->count_all_results();

    $all_users = $this->db->where('b_status',1)->from('tbl_admin')->count_all_results();

    $this->db->where('created_at >=', $this_week_sd);
    $this->db->where('created_at <=', $this_week_ed);
    $av_daily_call_this_week = $this->db->from('tbl_visit')->count_all_results();

    $this->db->where('created_at >=', $this_month_sd);
    $this->db->where('created_at <=', $this_month_ed);
    $av_daily_call_this_month = $this->db->from('tbl_visit')->count_all_results();

    $this->db->where('created_at >=', $last_month_sd);
    $this->db->where('created_at <=', $last_month_ed);
    $av_daily_call_last_month = $this->db->from('tbl_visit')->count_all_results();

    $av_daily_call_today_data = $get_today_call/$all_users;
    $av_daily_call_per_yesterday_data = $get_yesterday_call/$all_users;
    $av_daily_call_this_week_data = $av_daily_call_this_week/$all_users;
    $av_daily_call_last_week_data = $get_last_week_call/$all_users;
    $av_daily_call_this_month_data = $av_daily_call_this_month/$all_users;
    $av_daily_call_last_month_data = $av_daily_call_last_month/$all_users;
    $av_daily_call_total_data = $get_all_call/$all_users;


    $av_daily_call_per_person_today = $get_today_call/$all_users;
    $av_daily_call_per_person_yesterday = $get_yesterday_call/$all_users;
    $av_daily_call_per_person_this_week = $get_this_week_call/$all_users;
    $av_daily_call_per_person_last_week = $get_last_week_call/$all_users;
    $av_daily_call_per_person_this_month = $get_this_month_call/$all_users;
    $av_daily_call_per_person_last_month = $get_last_month_call/$all_users;
    $av_daily_call_per_person_total = $get_all_call/$all_users;

    $getData = array(
      'all_call'                            => $get_all_call,
      'today_call'                          => $get_today_call,
      'this_week'                           => $get_this_week_call,
      'last_week'                           => $get_last_week_call,
      'yesterday_call'                      => $get_yesterday_call,
      'this_month_call'                     => $get_this_month_call,
      'last_month_call'                     => $get_last_month_call,
      'av_daily_call_per_person_today'      => ($av_daily_call_per_person_today>=0)?$av_daily_call_per_person_today:0,
      'av_daily_call_per_person_yesterday'  => ($av_daily_call_per_person_yesterday>=0)?$av_daily_call_per_person_yesterday:0,
      'av_daily_call_per_person_this_week'  => ($av_daily_call_per_person_this_week>=0)?$av_daily_call_per_person_this_week:0,
      'av_daily_call_per_person_last_week'  => ($av_daily_call_per_person_last_week>=0)?$av_daily_call_per_person_last_week:0,
      'av_daily_call_per_person_this_month' => ($av_daily_call_per_person_this_month>=0)?$av_daily_call_per_person_this_month:0,
      'av_daily_call_per_person_last_month' => ($av_daily_call_per_person_last_month>=0)?$av_daily_call_per_person_last_month:0,
      'av_daily_call_per_person_total'      => ($av_daily_call_per_person_total>=0)?$av_daily_call_per_person_total:0,
      'av_daily_call_today_data'            => ($av_daily_call_today_data>=0)?$av_daily_call_today_data:0,
      'av_daily_call_per_yesterday_data'    => ($av_daily_call_per_yesterday_data>=0)?$av_daily_call_per_yesterday_data:0,
      'av_daily_call_this_week_data'        => ($av_daily_call_this_week_data>=0)?$av_daily_call_this_week_data:0,
      'av_daily_call_last_week_data'        => ($av_daily_call_last_week_data>=0)?$av_daily_call_last_week_data:0,
      'av_daily_call_this_month_data'       => ($av_daily_call_this_month_data>=0)?$av_daily_call_this_month_data:0,
      'av_daily_call_last_month_data'       => ($av_daily_call_last_month_data>=0)?$av_daily_call_last_month_data:0,
      'av_daily_call_total_data'            => ($av_daily_call_total_data>=0)?$av_daily_call_total_data:0,
    );

    return $getData;
  }

  public function all_signings_report_filterdata()
  {
    $todays = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($todays)));
    $this_month = date('m');

    $monday = strtotime("last monday");
    $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
    $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
    $this_week_sd = date("Y-m-d",$monday);
    $this_week_ed = date("Y-m-d",$sunday);

    $previous_week = strtotime("-1 week +1 day");
    $start_week = strtotime("last sunday midnight",$previous_week);
    $end_week = strtotime("next saturday",$start_week);
    $start_week = date("Y-m-d",$start_week);
    $end_week = date("Y-m-d",$end_week);
    // echo $start_week.' '.$end_week ;

    $this_month_sd = date('Y-m-01');
    $this_month_ed  = date('Y-m-t');

    $last_month_sd = Date("Y-m-d", strtotime("first day of previous month"));
    $last_month_ed = Date("Y-m-d", strtotime("last day of previous month"));

    $lastMonth = date("m", strtotime("first day of previous month"));

    $get_all_call = $this->db->where('status',5)->from('enquiry')->count_all_results();
    $get_today_call = $this->db->where(array('status' => 5,'date(created_date)' => $todays))->from('enquiry')->count_all_results();
    $get_yesterday_call = $this->db->where(array('status' => 5,'date(created_date)' => $yesterday))->from('enquiry')->count_all_results();
    $this->db->where('MONTH(created_date)', $this_month);
    $get_this_month_call = $this->db->where(array('status' => 5))->from('enquiry')->count_all_results();
    $get_last_month_call = $this->db->where(array('status' => 5,'MONTH(created_date)' => $lastMonth))->from('enquiry')->count_all_results();
    $this->db->where('date(created_date) >=', $this_week_sd);
    $this->db->where('date(created_date) <=', $this_week_ed);
    $get_this_week_call = $this->db->where(array('status' => 5))->from('enquiry')->count_all_results();

    $this->db->where('date(created_date) >=', $start_week);
    $this->db->where('date(created_date) <=', $end_week);
    $get_last_week_call = $this->db->where(array('status' => 5))->from('enquiry')->count_all_results();

    $all_users = $this->db->where(array('dept_name' => 1,'b_status'=>1))->from('tbl_admin')->count_all_results();

    $this->db->where('date(created_date) >=', $this_week_sd);
    $this->db->where('date(created_date) <=', $this_week_ed);
    $av_daily_call_this_week = $this->db->where(array('status' => 5))->from('enquiry')->count_all_results();

    $this->db->where('date(created_date) >=', $this_month_sd);
    $this->db->where('date(created_date) <=', $this_month_ed);
    $av_daily_call_this_month = $this->db->where(array('status' => 5))->from('enquiry')->count_all_results();

    $this->db->where('date(created_date) >=', $last_month_sd);
    $this->db->where('date(created_date) <=', $last_month_ed);
    $av_daily_call_last_month = $this->db->where(array('status' => 5))->from('enquiry')->count_all_results();

    $av_daily_call_today_data = $get_today_call/$all_users;
    $av_daily_call_per_yesterday_data = $get_yesterday_call/$all_users;
    $av_daily_call_this_week_data = $av_daily_call_this_week/$all_users;
    $av_daily_call_last_week_data = $get_last_week_call/$all_users;
    $av_daily_call_this_month_data = $av_daily_call_this_month/$all_users;
    $av_daily_call_last_month_data = $av_daily_call_last_month/$all_users;
    $av_daily_call_total_data = $get_all_call/$all_users;


    $av_daily_call_per_person_today = $get_today_call/$all_users;
    $av_daily_call_per_person_yesterday = $get_yesterday_call/$all_users;
    $av_daily_call_per_person_this_week = $get_this_week_call/$all_users;
    $av_daily_call_per_person_last_week = $get_last_week_call/$all_users;
    $av_daily_call_per_person_this_month = $get_this_month_call/$all_users;
    $av_daily_call_per_person_last_month = $get_last_month_call/$all_users;
    $av_daily_call_per_person_total = $get_all_call/$all_users;

    $getData = array(
      'all_call'                            => $get_all_call,
      'today_call'                          => $get_today_call,
      'this_week'                           => $get_this_week_call,
      'last_week'                           => $get_last_week_call,
      'yesterday_call'                      => $get_yesterday_call,
      'this_month_call'                     => $get_this_month_call,
      'last_month_call'                     => $get_last_month_call,
      'av_daily_call_per_person_today'      => ($av_daily_call_per_person_today>=0)?$av_daily_call_per_person_today:0,
      'av_daily_call_per_person_yesterday'  => ($av_daily_call_per_person_yesterday>=0)?$av_daily_call_per_person_yesterday:0,
      'av_daily_call_per_person_this_week'  => ($av_daily_call_per_person_this_week>=0)?$av_daily_call_per_person_this_week:0,
      'av_daily_call_per_person_last_week'  => ($av_daily_call_per_person_last_week>=0)?$av_daily_call_per_person_last_week:0,
      'av_daily_call_per_person_this_month' => ($av_daily_call_per_person_this_month>=0)?$av_daily_call_per_person_this_month:0,
      'av_daily_call_per_person_last_month' => ($av_daily_call_per_person_last_month>=0)?$av_daily_call_per_person_last_month:0,
      'av_daily_call_per_person_total'      => ($av_daily_call_per_person_total>=0)?$av_daily_call_per_person_total:0,
      'av_daily_call_today_data'            => ($av_daily_call_today_data>=0)?$av_daily_call_today_data:0,
      'av_daily_call_per_yesterday_data'    => ($av_daily_call_per_yesterday_data>=0)?$av_daily_call_per_yesterday_data:0,
      'av_daily_call_this_week_data'        => ($av_daily_call_this_week_data>=0)?$av_daily_call_this_week_data:0,
      'av_daily_call_last_week_data'        => ($av_daily_call_last_week_data>=0)?$av_daily_call_last_week_data:0,
      'av_daily_call_this_month_data'       => ($av_daily_call_this_month_data>=0)?$av_daily_call_this_month_data:0,
      'av_daily_call_last_month_data'       => ($av_daily_call_last_month_data>=0)?$av_daily_call_last_month_data:0,
      'av_daily_call_total_data'            => ($av_daily_call_total_data>=0)?$av_daily_call_total_data:0,
    );

    return $getData;
  }

  public function all_report_filterdata()
  {
    $dfields    = $this->report_model->get_dynfields();
    $fieldsval  = $this->report_model->getdynfielsval();
    $this->load->model('report_datatable_model');
    $no = $_POST['start'];
    $from = $this->session->userdata('from1');
    $to = $this->session->userdata('to1');
    $employe = $this->session->userdata('employe1');
    $phone = $this->session->userdata('phone1');
    $country = $this->session->userdata('country1');
    $institute = $this->session->userdata('institute1');
    $center = $this->session->userdata('center1');
    $source = $this->session->userdata('source1');
    $subsource = $this->session->userdata('subsource1');
    $datasource = $this->session->userdata('datasource1');
    $state = $this->session->userdata('state1');
    $lead_source = $this->session->userdata('lead_source1');
    $lead_subsource = $this->session->userdata('lead_subsource1');
    $enq_product = $this->session->userdata('enq_product1');
    $drop_status = $this->session->userdata('drop_status1');
    $all = $this->session->userdata('all1');
    $productlst = $this->session->userdata('productlst');
    $Enquiry_Id = $this->session->userdata('Enquiry_Id');
    $area = $this->session->userdata('area');
    $branch = $this->session->userdata('branch');
    $region = $this->session->userdata('region');
    $rep_details = $this->report_datatable_model->get_datatables();
    //echo $this->db->last_query();
    $i = 1;
    $data = array();
    foreach ($rep_details as  $repdetails) {
		
    $department = $this->db->select('fvalue')->from('extra_enquery')->where('parent',$repdetails->enquiry_id)->where('input','4452')->get()->row();
	$country_code = $this->db->select('fvalue')->from('extra_enquery')->where('parent',$repdetails->enquiry_id)->where('input','4453')->get()->row();
	$std_code = $this->db->select('fvalue')->from('extra_enquery')->where('parent',$repdetails->enquiry_id)->where('input','4454')->get()->row();
	$website = $this->db->select('fvalue')->from('extra_enquery')->where('parent',$repdetails->enquiry_id)->where('input','4505')->get()->row();
	$pincode = $this->db->select('fvalue')->from('extra_enquery')->where('parent',$repdetails->enquiry_id)->where('input','4536')->get()->row();

      $no++;
      $row = array();

      if (in_array('S.No', $this->session->userdata('post_report_columns'))) {
        $row[] = $i++;
      }
      if (in_array('Name', $this->session->userdata('post_report_columns'))) {
        $row[] = $repdetails->name_prefix . " " . $repdetails->name . " " . $repdetails->lastname;
      }
      if (in_array('Phone', $this->session->userdata('post_report_columns'))) {
        if (user_access(450)) {
          $row[] = '##########';
        } else {
          $row[] = $repdetails->phone;
        }
      }
      if (in_array('Email', $this->session->userdata('post_report_columns'))) {
        $row[] = $repdetails->email;
      }
      if (in_array('Created By', $this->session->userdata('post_report_columns'))) {
        $row[] = $repdetails->created_by_name;
      }
      if (in_array('Assign To', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->assign_to_name)) ? $repdetails->assign_to_name : 'NA';
      }
      if (in_array('Gender', $this->session->userdata('post_report_columns'))) {
        if ($repdetails->gender == 1) {
          $gender = 'Male';
        } else if ($repdetails->gender == 2) {
          $gender = 'Female';
        } else {
          $gender = 'Other';
        }
        $row[] = $gender;
      }
      if (in_array('Source', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->lead_name)) ? $repdetails->lead_name : 'NA';
      }
      if (in_array('Subsource', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->subsource_name)) ? $repdetails->subsource_name : 'NA';
      }
      if (in_array('Disposition', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->followup_name)) ? $repdetails->followup_name : 'NA';
      }
      if (in_array('Lead Description', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->description)) ? $repdetails->description : "NA";
      }

      if (in_array('Disposition Remark', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->lead_discription_reamrk)) ? $repdetails->lead_discription_reamrk : "NA";
      }
      if (in_array('Drop Reason', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->drop_status)) ? $repdetails->drop_status : "NA";
      }
      if (in_array('Drop Comment', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->drop_reason)) ? $repdetails->drop_reason : "NA";
      }
      if (in_array('Conversion Probability', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->lead_score)) ? $repdetails->lead_score : "NA";
      }
      if (in_array('Remark', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->enq_remark)) ? $repdetails->enq_remark : "NA";
      }

      if (in_array('Status', $this->session->userdata('post_report_columns'))) {
        // if ($repdetails->status == 1) {
        //   $status = 'Enquiry';
        // } else if ($repdetails->status == 2) {
        //   $status = 'Lead';
        // } else {
        //   $status = 'Client';
        // }
        $row[] = $repdetails->status_title;
      }
      if (in_array('DOE', $this->session->userdata('post_report_columns'))) {
        $row[] = $repdetails->inq_created_date;
      }
      if (in_array('Process', $this->session->userdata('post_report_columns'))) {
        $row[] =  (!empty($repdetails->product_name)) ? $repdetails->product_name : 'NA';
      }
      if (in_array('Updated Date', $this->session->userdata('post_report_columns'))) {
        $row[] = $repdetails->update_date;
      }
      if (in_array('State', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->state_name)) ? $repdetails->state_name : 'NA';
      }
      if (in_array('City', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->city_name)) ? $repdetails->city_name : 'NA';
      }
      if (in_array('Sales Region', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->region_name)) ? $repdetails->region_name : 'NA';
      }
      if (in_array('Sales Area', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->area_name)) ? $repdetails->area_name : 'NA';
      }
      if (in_array('Sales Branch', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->branch_name)) ? $repdetails->branch_name : 'NA';
      }
      if (in_array('Company Name', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->company)) ? $repdetails->company : 'NA';
      }
      if (in_array('Product', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->enq_product_name)) ? $repdetails->enq_product_name : 'NA';
      }
      if (in_array('Enquiry Id', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->Enquery_id)) ? $repdetails->Enquery_id : 'NA';
      }
	  if (in_array('Client Name', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->client_name)) ? $repdetails->client_name : 'NA';
      }
	  if (in_array('Expected Closer Date', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->lead_expected_date)) ? $repdetails->lead_expected_date : 'NA';
      }
	  if (in_array('Conversion Probability', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->lscore_name)) ? $repdetails->lscore_name : 'NA';
      }
	  if (in_array('Designation', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->desi_name)) ? $repdetails->desi_name : 'NA';
      }
	  if (in_array('Address', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->address)) ? $repdetails->address : 'NA';
      }
	  if (in_array('Client Type', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->client_type)) ? $repdetails->client_type : 'NA';
      }
	  if (in_array('Type Of Load / Business', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->business_load)) ? $repdetails->business_load : 'NA';
      }
	  if (in_array('Industries', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($repdetails->industries)) ? $repdetails->industries : 'NA';
      }
	  if (in_array('Department', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($department->fvalue)) ? $department->fvalue : 'NA';
      }
	  if (in_array('Country Code', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($country_code->fvalue)) ? $country_code->fvalue : 'NA';
      }
	  if (in_array('STD Code', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($std_code->fvalue)) ? $std_code->fvalue : 'NA';
      }
	  if (in_array('Website', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($website->fvalue)) ? $website->fvalue : 'NA';
      }
	  if (in_array('Pincode', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($pincode->fvalue)) ? $pincode->fvalue : 'NA';
      }
      if (!empty($dfields)) {
        foreach ($dfields as $ind => $dfld) {
          if (in_array(trim($dfld['input_label']), $this->session->userdata('post_report_columns'))) {

            if (!empty($fieldsval)) {
              if (!empty($fieldsval[$repdetails->enquiry_id])) {
                if (!empty($fieldsval[$repdetails->enquiry_id][$dfld['input_label']])) {
                  $row[] = $fieldsval[$repdetails->enquiry_id][$dfld['input_label']]->fvalue;
                } else {
                  $row[] = "NA";
                }
              } else {
                $row[] = "NA";
              }
            } else {
              $row[] =  "NA";
            }
          }
        }
      }
      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->report_datatable_model->count_all(),
      "recordsFiltered" => $this->report_datatable_model->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }
  
  public function deal_report_filterdata()
  {
    $this->load->model('report_datatable_model');
    $no = $_POST['start'];
    $from = $this->session->userdata('from1');
    $to = $this->session->userdata('to1');
	$u_from = $this->session->userdata('updated_from1');
    $u_to = $this->session->userdata('updated_to1');
    $employe = $this->session->userdata('employe1');
	$state = $this->session->userdata('state1');
    $all = $this->session->userdata('all1');
    $deal_details = $this->report_datatable_model->deal_get_datatables();
   // echo $this->db->last_query();
    $i = 1;
    $data = array();
    foreach ($deal_details as  $dealdetails) {

      $no++;
      $row = array();

      if (in_array('S.No', $this->session->userdata('post_report_columns'))) {
        $row[] = $i++;
      }
      if (in_array('Quatation No', $this->session->userdata('post_report_columns'))) {
		$row[] = (!empty($dealdetails->quatation_number)) ? $dealdetails->quatation_number : 'NA';
      }
	  
	  if (in_array('Quatation Amt', $this->session->userdata('post_report_columns'))) {
		$row[] = (!empty($dealdetails->qotation_amount)) ? (int)(($dealdetails->qotation_amount*100))/100 : 'NA';
      }
	  
	  if (in_array('Name', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->deal_name)) ? $dealdetails->deal_name : 'NA';
      }
	  
	  if (in_array('Client Name', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->client_name)) ? $dealdetails->client_name : 'NA';
      }
	  
	  if (in_array('Business Type', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->business_type)) ? $dealdetails->business_type : 'NA';
      }
	  
	  if (in_array('Booking Type', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->booking_type)) ? $dealdetails->booking_type : 'NA';
      }
	  
	  if (in_array('Deal Type', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->deal_type)) ? $dealdetails->deal_type : 'NA';
      }
	  
	  if (in_array('Deal Insurance', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->insurance)) ? $dealdetails->insurance : 'NA';
      }

      if (in_array('Deal Stage', $this->session->userdata('post_report_columns'))) {
         if ($dealdetails->stage_id == 1) {
           $stage = 'Lead';
         } else if ($dealdetails->stage_id == 2) {
           $stage = 'Approach';
         } else if ($dealdetails->stage_id == 3){
           $stage = 'Negotiation';
         }else if ($dealdetails->stage_id == 4){
           $stage = 'Closer';
         }else if ($dealdetails->stage_id == 5){
           $stage = 'Order';
         }else if ($dealdetails->stage_id == 6){
           $stage = 'Future Oppotunities';
         }
        $row[] = (!empty($stage)) ? $stage : 'NA';
      }
	  
	  if (in_array('Deal Status', $this->session->userdata('post_report_columns'))) {
		  if ($dealdetails->status == 1) {
           $status = 'Done';
         } else {
           $status = 'Pending';
         }
        $row[] = (!empty($status)) ? $status : 'NA';
      }
	  
	  if (in_array('Created By', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->createdby)) ? $dealdetails->createdby : 'NA';
      }
	  
	  if (in_array('Created Date', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->creation_date)) ? $dealdetails->creation_date : 'NA';
      }
	  
	  if (in_array('Updated Date', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->updation_date)) ? $dealdetails->updation_date : 'NA';
      }
	  
	  if (in_array('Edit Remark', $this->session->userdata('post_report_columns'))) {
        $row[] = (!empty($dealdetails->edit_remark)) ? $dealdetails->edit_remark : 'NA';
      }
	  
      $data[] = $row;
    } 
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->report_datatable_model->count_all_deal(),
      "recordsFiltered" => $this->report_datatable_model->count_filtered_deal(),
      "data" => $data,
    );
    echo json_encode($output);
  }
 
    public function create_report(){
      parse_str($_POST['filters'], $filters);
      $report_name    = $this->input->post('report_name');
      $type    = $this->input->post('type');
      $this->form_validation->set_rules('report_name','Report Name','required|trim');
      if ($this->form_validation->run() == TRUE) {
          $insert_array = array(
                          'name'      =>  $report_name,
                          'comp_id'   =>  $this->session->companey_id,
                          'filters'   =>  json_encode($filters),
                          'type'=>        $type,
                          'created_by'=>  $this->session->user_id
                          );
          if($this->db->insert('reports',$insert_array)){
              echo json_encode(array('status'=>true,'msg'=>'Report Saved Successfully'));
          }else{
              echo json_encode(array('status'=>false,'msg'=>'Something went wrong!'));
          }           
      } else {
          echo json_encode(array('status'=>false,'msg'=>validation_errors()));            
      }
      
  }

  public function all_reports() {
        $data['title'] = 'Report';
        $data['countries'] = $this->report_model->all_country();
        $data['institute'] = $this->report_model->all_institute();
        $data['center'] = $this->report_model->all_center();
        $data['sourse'] = $this->report_model->all_source();
        $data['subsourse'] = $this->report_model->all_subsource();
        $data['datasourse'] = $this->report_model->all_datasource();
        $data['all_stage_lists'] = $this->Leads_Model->find_stage();
        $data['products'] = $this->dash_model->product_list();        
        $data['employee'] = $this->report_model->all_company_employee($this->session->userdata('companey_id'));        
        $data['content'] = $this->load->view('all_report', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    
    //Dashboard statitics reports for enquiry..
    public function enquiry_statitics_report() {
        echo json_encode($this->report_model->enquiry_statitics_data());
    }
    //Dashboard statitics reports for Leads..
    public function lead_statitics_report() {
        echo json_encode($this->report_model->lead_statitics_data());
    }
    public function lead_opportunity() {
        echo json_encode($this->report_model->lead_opportunities_status());
    }
    public function client_opportunities() {
        echo json_encode($this->report_model->client_opportunity_status());
    }
    public function all_source() {
        echo json_encode($this->report_model->enquiry_source_data());
    }
    public function funnel_reports() {
        echo json_encode($this->report_model->funnel_report());
    }

    public function ticket_report()
    {
      if(user_role(122)){}
        $this->load->model(array('Ticket_Model','Datasource_model','dash_model','enquiry_model','report_model','Leads_Model','User_model'));
    
        if (isset($_SESSION['ticket_filters_sess']) && empty($_POST))
          unset($_SESSION['ticket_filters_sess']);
        $data['sourse'] = $this->report_model->all_source();
        $data['title'] = "Ticket Report";
        $data['created_bylist'] = $this->User_model->read();
        $data['products'] = $this->dash_model->get_user_product_list();
        $data['prodcntry_list'] = $this->enquiry_model->get_user_productcntry_list();
        $data['problem'] = $this->Ticket_Model->get_sub_list();        
        $data['stage'] =  $this->Leads_Model->stage_by_type(4);
        $data['sub_stage'] = $this->Leads_Model->find_description();
        $data['ticket_status'] = $this->Ticket_Model->ticket_status()->result();        
        $data['dfields'] = $this->enquiry_model->getformfield(2);        
        $data['issues'] = $this->Ticket_Model->get_issue_list();     

       // $list =  $this->db->select('input_id')->where(array('process_id'=>$this->session->process[0],'company_id'=>$this->session->companey_id,'status'=>'1'))->get('tbl_input')->result();
       // $list = array_column($list, 'input_id');
       // // $list2 = array();
       // // //print_r($list); exit();
       // // if(!empty($_COOKIE['ticket_dallowcols']))
       // //  $list2 = explode(',', $_COOKIE['ticket_dallowcols']);

       // // $common = array_intersect($list,$list2);
       // //  p
       // // setcookie('ticket_dallowcols',implode(',', $common),86400*30,'/');

       // $data['table_config_list'] = $list;
       if(!empty($this->session->ticket_filters_sess['export_only']) && $this->session->ticket_filters_sess['export_only'] == 1){         
          redirect('ticket/ticket_load_data');
       }
        $data['content'] = $this->load->view('reports/ticket_report', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
	
	  public function deal_report()
    {
		
    $data['title'] = 'Deal report list';
    if ($this->session->companey_id == 65 && $this->session->user_right == 215) {
      $data['created_bylist'] = $this->user_model->read(147, false);
    } else {
      $data['created_bylist'] = $this->user_model->read();
    }
    $data['reports'] = $this->report_model->get_all_reports('8');
    $data['content'] = $this->load->view('reports/deal_index', $data, true);
    $this->load->view('layout/main_wrapper', $data);
	
    }

    public function report_analitics($for){
      $this->load->model('report_datatable_model');
      $result  = $this->report_datatable_model->report_analitics($for);      
      echo json_encode($result);      
    }
    public function ticket_report_analitics($for){
      // die();
    
      $this->load->model('ticket_report_datatable_model');
      $result  = $this->ticket_report_datatable_model->report_analitics($for);      
      echo json_encode($result);  
    }

    public function prticket_report_analitics(){
      // die();
      $this->load->model('ticket_report_datatable_model');
      $priority1=$this->input->post('priority');
      if(!empty($priority1)){
      if($priority1==1){
      $result  = $this->ticket_report_datatable_model->priorityreport_analitics('priority_chart',1);
      echo json_encode(array(array('name'=>'Low','y'=>$result)));
      }
      if($priority1==2){
      $result  = $this->ticket_report_datatable_model->priorityreport_analitics('priority_chart',2);
      echo json_encode(array(array('name'=>'Medium','y'=>$result)));
      }
      if($priority1==3){
        $result  = $this->ticket_report_datatable_model->priorityreport_analitics('priority_chart',3);
        echo json_encode(array(array('name'=>'High','y'=>$result)));
      }
      }else{
      $result1  = $this->ticket_report_datatable_model->priorityreport_analitics('priority_chart',1);
      $result2  = $this->ticket_report_datatable_model->priorityreport_analitics('priority_chart',2);
      $result3  = $this->ticket_report_datatable_model->priorityreport_analitics('priority_chart',3);
      echo json_encode(array(array('name'=>'Low','y'=>$result1),array('name'=>'Medium','y'=>$result2),array('name'=>'High','y'=>$result3)));  
     }
    }
    public function report_analitics_pipeline($for){
      $this->load->model('report_datatable_model');
      $result  = $this->report_datatable_model->report_analitics($for);      
      $res = array();

      if(!empty($result)){
        foreach($result as $value){          
          $title = $this->get_sale_pipeline_name_byId($value[0]);          
          array_push($res,array($title,$value[1]));          
        }
        $result = $res;
      }
      echo json_encode($result);      
    }

    function get_sale_pipeline_name_byId($id){      
      if($id == 1){
        $name = display('enquiry');
      }else if($id == 2){
        $name = display('lead');
      }else if($id == 3){
        $name = display('client');
      }else{
        $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
        if (!empty($enquiry_separation)) {
          $enquiry_separation = json_decode($enquiry_separation, true);
          $name = $enquiry_separation[$id]['title'];     
        }else{
          $name = 'NA';
        }
      }
    return $name;

    }
  
}
