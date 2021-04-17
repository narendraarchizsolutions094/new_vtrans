<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_model extends CI_Model {
    
  public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');		
    }
	
     function all_rep($from,$to,$employe,$phone,$country,$institute,$center,$source,$subsource,$datasource,$state,$lead_source,$lead_subsource,$enq_product,$drop_status,$all=''){      
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        
        if($all){
            $select = 'enquiry.enquiry_id,enquiry.name_prefix,enquiry.name,enquiry.lastname,enquiry.phone,enquiry.update_date,enquiry.email,enquiry.gender,enquiry.drop_status,enquiry.status,enquiry.address as enq_address,enquiry.enquiry as enq_remark,lead_source.lead_name,tbl_subsource.subsource_name,tbl_comment.created_date,tbl_comment.remark,enquiry.status as inq_status,enquiry.created_date as inq_created_date, CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,CONCAT(admin2.s_display_name,admin2.last_name) as assign_to_name,tbl_product.product_name,lead_stage2.lead_stage_name as followup_name';
        }else{
            $select = 'enquiry.enquiry_id,enquiry.name_prefix,enquiry.name,enquiry.lastname,enquiry.phone,enquiry.update_date,enquiry.email,enquiry.gender,enquiry.drop_status,enquiry.status,enquiry.address as enq_address,enquiry.enquiry as enq_remark,lead_source.lead_name,tbl_subsource.subsource_name,lead_stage.lead_stage_name as followup_name,enquiry.status as inq_status,enquiry.created_date as inq_created_date, CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,CONCAT(admin2.s_display_name,admin2.last_name) as assign_to_name,tbl_product.product_name';
        }

        $this->db->select($select);
        //$this->db->select('enquiry.name_prefix,enquiry.name,enquiry.lastname,enquiry.phone,enquiry.email,enquiry.gender,lead_source.lead_name,tbl_subsource.subsource_name,lead_description.description,lead_stage.lead_stage_name,enquiry.status as inq_status,enquiry.created_date as inq_created_date, CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,CONCAT(admin2.s_display_name,admin2.last_name) as assign_to_name,tbl_product.product_name');

        $this->db->from('enquiry');
        
        $where = "enquiry.enquiry_id > 0";      


        if (!empty($from) && !empty($to)) {
            $to = str_replace('/', '-', $to);
            $from = str_replace('/', '-', $from);            

            $from = date('Y-m-d', strtotime($from));

            $to = date('Y-m-d', strtotime($to));            

            $where .= " AND Date(enquiry.created_date) >= '$from' AND Date(enquiry.created_date) <= '$to'";
        } else if (!empty($from) && empty($to)) {
            $from = str_replace('/', '-', $from);            

            $from = date('Y-m-d H:i:s', strtotime($from));

            // $where .= " AND enquiry.created_date LIKE '%$from%'";

             // $from_created = date("Y-m-d",strtotime($from_created));
            $where .= " AND DATE(enquiry.created_date) >=  '".$from."'";  

        } else if (empty($from) && !empty($to)) {            

            $to = str_replace('/', '-', $to);

            $to = date('Y-m-d H:i:s', strtotime($to));
           

            // $where .= " AND enquiry.created_date LIKE '%$to%'";
              $where .= " AND DATE(enquiry.created_date) <=  '".$to."'";  

        }
       if($employe!=''){
			
			$where .= " AND ( enquiry.created_by IN (".implode(',', $employe).')';
			$where .= " OR enquiry.aasign_to IN (".implode(',', $employe).'))';  
			
           /* $where .= " AND (enquiry.aasign_to=$employe";
           $where .= " OR enquiry.created_by=$employe)"; */
		   
        }else{
			$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
			$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
		}
    /*
        if($phone!=''){
           $where .= " AND enquiry.phone=$phone";  
        }
        if($country!=''){
           $where .= " AND enquiry.country_id=$country";  
        }
        if($institute!=''){
           $where .= " AND enquiry.institute_id=$institute";  
        }
        if($center!=''){
           $where .= " AND enquiry.center_id=$center";  
        }
*/      
        if($source!=''){
           $where .= " AND enquiry.enquiry_source IN (".implode(',', $source).')';  
        }
        if($subsource!=''){
           $where .= " AND enquiry.enquiry_subsource IN (".implode(',', $subsource).')';
        }
        if($datasource!=''){
           $where .= " AND enquiry.datasource_id IN (".implode(',', $datasource).')';  
        }
        
        
        if($state!=''){
           $where .= " AND enquiry.status IN (".implode(',', $state).')';  

        }
        if ($lead_source != '') {                
            $where .= " AND enquiry.lead_stage IN (".implode(',', $lead_source).')';                                           
        }

        if($enq_product!=''){
           $where .= " AND enquiry.product_id IN (".implode(',', $enq_product).')';  
        }

        if($all!=''){
            $where.= "AND comment_msg='Stage Updated'";
            $this->db->join('tbl_comment','tbl_comment.lead_id=enquiry.Enquery_id','inner');              
            $this->db->join('lead_stage as lead_stage2','lead_stage2.stg_id=tbl_comment.stage_id','left'); 
        }
       
       if($drop_status!=''){
             // print_r($drop_status);
        // var_dump($drop_status);
            if(!empty($drop_status[0])){
            if($drop_status[0]=='dropped'){
               
             // echo "string";exit();
             $where .= " AND enquiry.drop_status>0 ";
            }
        }
        if(!empty($drop_status[1])){
            if($drop_status[1]=='active'){
                $where .= " AND enquiry.status=1";

            }
        }
            if(!empty($drop_status[0])=='dropped' && !empty($drop_status[1])=='active'){

            $where .= " AND enquiry.drop_status>0 AND enquiry.status=1";

            }

        }

        
        
        //$this->db->join('tbl_center','tbl_center.center_id=enquiry.center_id','left');
        
        $this->db->join('lead_source','lead_source.lsid=enquiry.enquiry_source','left');
        $this->db->join('tbl_product','tbl_product.sb_id=enquiry.product_id','left');
        
        $this->db->join('tbl_subsource','tbl_subsource.subsource_id=enquiry.enquiry_subsource','left');
        
        $this->db->join('tbl_datasource','tbl_datasource.datasource_id=enquiry.datasource_id','left');

        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left');
        $this->db->join('tbl_admin as admin2','admin2.pk_i_admin_id=enquiry.aasign_to','left');
        
        //$this->db->join('tbl_institute','tbl_institute.institute_id=enquiry.institute_id','left');
        
        //$this->db->join('tbl_product_country','tbl_product_country.id=enquiry.country_id','left');
        
        $this->db->join('lead_stage','lead_stage.stg_id=enquiry.lead_stage','left');
        
        //$this->db->join('lead_description','lead_description.id=tbl_comment.stage_description','left');

        //$this->db->join("(select q.comm_id as comm_id, q.created_date, q.lead_id from tbl_comment as q  GROUP BY q.comm_id ORDER BY q.comm_id DESC ) as tbl_comment1", 'tbl_comment1.lead_id=enquiry.Enquery_id', 'left');
		$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 
        
		$this->db->where($where);
        //echo $all.'jj';
        if(!$all){
            $this->db->group_by('enquiry.enquiry_id');        
        }else{
            $this->db->order_by('enquiry.enquiry_id,tbl_comment.created_date','DESC');        
            //$this->db->order_by('enquiry.enquiry_id','DESC');        
        }        
        //$this->db->limit(1);

        
        return   $this->db->get()->result();
        
    }
    

    
    
    public function get_enquiry_report_data($data) {
        $curr_date = date("Y-m-d h:i:s");
        $this->db->select('*,enquiry.created_date');
        $this->db->from('enquiry');
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.created_by', 'left');
        $this->db->join('tbl_country', 'tbl_country.id_c=enquiry.country_id', 'left');
        $this->db->join('lead_source', 'lead_source.lsid=enquiry.enquiry_source', 'left');
        $this->db->join('tbl_territory', 'tbl_territory.territory_id=enquiry.territory_id', 'left');
        $this->db->join('tbl_region', 'tbl_region.region_id=enquiry.region_id', 'left');
        $this->db->join('state', 'state.id=enquiry.state_id', 'left');
        $this->db->join('city', 'city.id=enquiry.city_id', 'left');
        if ($data['from_date'] != null && $data['todata'] != null) {
            $this->db->where('enquiry.created_date >= ', $data['from_date']);
            $this->db->where('enquiry.created_date <= ', $data['todata']);
        }
        if ($data['employee'] != null) {
            $this->db->where('enquiry.created_by', $data['employee']);
        }
        if ($data['email'] != null) {
            $this->db->where('enquiry.email', $data['email']);
        }
        if ($data['phone'] != null) {
            $this->db->where('enquiry.phone', $data['phone']);
        }
        if ($data['country'] != null) {
            $this->db->where('enquiry.country_id', $data['country']);
        }
        if ($data['region'] != null) {
            $this->db->where('enquiry.region_id', $data['region']);
        }
        if ($data['territory'] != null) {
            $this->db->where('enquiry.territory_id', $data['territory']);
        }
        if ($data['state'] != null) {
            $this->db->where('enquiry.state_id', $data['state']);
        }
        if ($data['city'] != null) {
            $this->db->where('enquiry.city_id', $data['city']);
        }
        if ($data['organisational_name'] != null) {
            $this->db->where('enquiry.org_name', $data['organisational_name']);
        }
        if ($data['source'] != null) {
            $this->db->where('enquiry.enquiry_source', $data['source']);
        }
        if ($data['name'] != null) {
            $this->db->where('enquiry.name', $data['name']);
        }
        if ($data['pincode'] != null) {
            $this->db->where('enquiry.pin_code', $data['pincode']);
        }

        if ($data['address'] != null) {

            $this->db->where('enquiry.address', $data['address']);
        }

        if ($data['opportunity_size'] != null) {

            $this->db->where('enquiry.op_size', $data['opportunity_size']);
        }

        if ($data['enquiry_type'] != null) {

            $this->db->where('enquiry.enquiry_cust_type', $data['enquiry_type']);
        }

        if ($data['status'] != null) {

            if ($data['status'] == 1) {//Drop
                $this->db->where('enquiry.drop_status!=', 0);
            } else if ($data['status'] == 2) {//Active
                $this->db->where('enquiry.status', 1);
            } else if ($data['status'] == 3) {//Lead
                $this->db->where('enquiry.status', 2);
            } else if ($data['status'] == 4) { //created today
                $this->db->where('enquiry.created_date', $curr_date);
            } else if ($data['status'] == 5) {//Updated Today
                $this->db->where('enquiry.update_date', $curr_date);
            } else if ($data['status'] == 6) {//Assigned
                $this->db->where('enquiry.aasign_to!=', 0);
            } else if ($data['status'] == 7) {//Unassigned
                $this->db->where('enquiry.aasign_to', 0);
            }
        }
        $result = $this->db->get()->result();
        return $result;
    }

    public function leads_filter_data($data) {
        $this->db->select('*');
        $this->db->from('enquiry');
        $this->db->join('tbl_country', 'tbl_country.id_c=enquiry.country_id', 'left');
        $this->db->join('lead_source', 'lead_source.lsid=allleads.ld_source', 'left');
        $this->db->join('lead_stage', 'lead_stage.stg_id=allleads.lead_stage', 'left');
        $this->db->join('enquiry', 'enquiry.Enquery_id=allleads.lead_code', 'left');

        $this->db->join('tbl_territory', 'tbl_territory.territory_id=enquiry.territory_id', 'left');
        $this->db->join('tbl_region', 'tbl_region.region_id=enquiry.region_id', 'left');
        $this->db->join('state', 'state.id=enquiry.state_id', 'left');
        $this->db->join('city', 'city.id=enquiry.city_id', 'left');


        if ($data['from_date'] != null && $data['todate'] != null) {

            $this->db->where('allleads.ld_created >= ', $data['from_date']);
            $this->db->where('allleads.ld_created <= ', $data['todate']);
        }

        if ($data['employee'] != null) {

            $this->db->where('allleads.adminid', $data['employee']);
        }

        if ($data['email'] != null) {

            $this->db->where('allleads.ld_email', $data['email']);
        }

        if ($data['phone'] != null) {

            $this->db->where('allleads.ld_mobile', $data['phone']);
        }

        if ($data['country'] != null) {

            $this->db->where('allleads.country_id', $data['country']);
        }

        if ($data['region'] != null) {

            $this->db->where('allleads.region_id', $data['region']);
        }

        if ($data['territory'] != null) {

            $this->db->where('allleads.territory_id', $data['territory']);
        }

        if ($data['state'] != null) {

            $this->db->where('allleads.state_id', $data['state']);
        }

        if ($data['city'] != null) {

            $this->db->where('allleads.city_id', $data['city']);
        }

        if ($data['organisational_name'] != null) {

            $this->db->where('enquiry.org_name', $data['organisational_name']);
        }

        if ($data['source'] != null) {

            $this->db->where('allleads.ld_source', $data['source']);
        }

        if ($data['lead_stage'] != null) {

            $this->db->where('allleads.lead_stage', $data['lead_stage']);
        }

        if ($data['name'] != null) {

            $this->db->where('allleads.ld_name', $data['name']);
        }

        if ($data['lead_score'] != null) {

            $this->db->where('allleads.lead_score', $data['lead_score']);
        }

        if ($data['address'] != null) {

            $this->db->where('allleads.address', $data['address']);
        }

        return $this->db->get()->result();
    }

    //Filter client 
    public function filter_client_data($data) {

        $this->db->select('*,clients.created_date');

        $this->db->from('clients');
        $this->db->join('tbl_country', 'tbl_country.id_c=clients.country_id', 'left');
        $this->db->join('enquiry', 'enquiry.Enquery_id=clients.customer_code', 'left');

        $this->db->join('tbl_territory', 'tbl_territory.territory_id=enquiry.territory_id', 'left');
        $this->db->join('tbl_region', 'tbl_region.region_id=enquiry.region_id', 'left');
        $this->db->join('state', 'state.id=enquiry.state_id', 'left');
        $this->db->join('city', 'city.id=enquiry.city_id', 'left');

        $date1 = date_create($data['from_date']);
        $from_date = date_format($date1, "d-m-Y H:i:s");

        $date2 = date_create($data['todate']);
        $todate = date_format($date2, "d-m-Y H:i:s");


        if ($data['from_date'] != null && $data['todate'] != null) {

            $this->db->where('clients.created_date >= ', $from_date);
            $this->db->where('clients.created_date <= ', $todate);
        }

        if ($data['employee'] != null) {

            $this->db->where('clients.create_by', $data['employee']);
        }

        if ($data['email'] != null) {

            $this->db->where('clients.cl_email', $data['email']);
        }

        if ($data['phone'] != null) {

            $this->db->where('clients.cl_mobile', $data['phone']);
        }

        if ($data['country'] != null) {

            $this->db->where('clients.country_id', $data['country']);
        }

        if ($data['region'] != null) {

            $this->db->where('clients.region_id', $data['region']);
        }

        if ($data['territory'] != null) {

            $this->db->where('clients.territory_id', $data['territory']);
        }

        if ($data['state'] != null) {

            $this->db->where('clients.state_id', $data['state']);
        }

        if ($data['city'] != null) {

            $this->db->where('clients.city_id', $data['city']);
        }

        if ($data['organisational_name'] != null) {

            $this->db->where('enquiry.org_name', $data['organisational_name']);
        }

        if ($data['employee_id'] != null) {

            $this->db->where('clients.customer_code', $data['employee_id']);
        }

        if ($data['pincode'] != null) {

            $this->db->where('enquiry.pin_code', $data['pincode']);
        }

        if ($data['address'] != null) {

            $this->db->where('clients.address', $data['address']);
        }



        return $this->db->get()->result();
    }

    //Filter Installation data....
    public function installation_data($data) {

        $this->db->select('*');

        $this->db->from('site_audit_report');
        $this->db->join('clients', 'clients.cli_id=site_audit_report.client_id');
        $this->db->join('readiness_check_list', 'readiness_check_list.client_id=clients.cli_id');
        $this->db->join('tbl_country', 'tbl_country.id_c=clients.country_id', 'left');
        $this->db->join('enquiry', 'enquiry.Enquery_id=clients.customer_code', 'left');

        $this->db->join('tbl_territory', 'tbl_territory.territory_id=enquiry.territory_id', 'left');
        $this->db->join('tbl_region', 'tbl_region.region_id=enquiry.region_id', 'left');
        $this->db->join('state', 'state.id=enquiry.state_id', 'left');
        $this->db->join('city', 'city.id=enquiry.city_id', 'left');

        $date1 = date_create($data['from_date']);
        $from_date = date_format($date1, "d-m-Y H:i:s");

        $date2 = date_create($data['todate']);
        $todate = date_format($date2, "d-m-Y H:i:s");


        if ($data['from_date'] != null && $data['todate'] != null) {

            $this->db->where('clients.created_date >= ', $from_date);
            $this->db->where('clients.created_date <= ', $todate);
        }

        if ($data['employee'] != null) {

            $this->db->where('clients.create_by', $data['employee']);
        }

        if ($data['email'] != null) {

            $this->db->where('clients.cl_email', $data['email']);
        }

        if ($data['phone'] != null) {

            $this->db->where('clients.cl_mobile', $data['phone']);
        }

        if ($data['country'] != null) {

            $this->db->where('clients.country_id', $data['country']);
        }

        if ($data['region'] != null) {

            $this->db->where('clients.region_id', $data['region']);
        }

        if ($data['territory'] != null) {

            $this->db->where('clients.territory_id', $data['territory']);
        }

        if ($data['state'] != null) {

            $this->db->where('clients.state_id', $data['state']);
        }

        if ($data['city'] != null) {

            $this->db->where('clients.city_id', $data['city']);
        }

        if ($data['organisational_name'] != null) {

            $this->db->where('enquiry.org_name', $data['organisational_name']);
        }

        if ($data['employee_id'] != null) {

            $this->db->where('clients.customer_code', $data['employee_id']);
        }

        if ($data['pincode'] != null) {

            $this->db->where('enquiry.pin_code', $data['pincode']);
        }

        if ($data['address'] != null) {

            $this->db->where('clients.address', $data['address']);
        }

        if ($data['site_audit'] != null) {

            if ($data['site_audit'] == 'sent') {

                $this->db->where('site_audit_report.is_send', 1);
            } elseif ($data['site_audit'] == 'receive') {

                $this->db->where('site_audit_report.is_approved', 2);
            }
        }

        if ($data['site_readiness'] != null) {

            if ($data['site_readiness'] == 'sent') {

                $this->db->where('readiness_check_list.is_confirm', 1);
            } elseif ($data['site_readiness'] == 'receive') {

                $this->db->where('readiness_check_list.is_confirm', 1);
            }
        }

        if ($data['status'] != null) {

            if ($data['status'] == 1) {

                $this->db->where('site_audit_report.is_approved', 1);
            } else {

                $this->db->where('site_audit_report.is_approved', 0);
            }
        }


        return $this->db->get()->result();
    }

    //Get channel partner lists...
    public function channel_partners_list() {

        return $this->db->select('*')
                        ->from('tbl_admin')
                        ->where('user_roles', 9)
                        ->get()
                        ->result();
    }

    //Get Filtered data of channel partner
    public function channel_partner_data($data) {

        $this->db->select('*');

        $this->db->from('enquiry');
        $this->db->join('tbl_country', 'tbl_country.id_c=enquiry.country_id');
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.created_by', 'left');
        $this->db->join('lead_source', 'lead_source.lsid=enquiry.enquiry_source');


        if ($data['from_date'] != null && $data['todate'] != null) {

            $this->db->where('enquiry.created_date >= ', $data['from_date']);
            $this->db->where('enquiry.created_date <= ', $data['todate']);
        }

        if ($data['channel_partner'] != null) {

            $this->db->where('enquiry.created_by', $data['channel_partner']);
        }

        if ($data['email'] != null) {

            $this->db->where('enquiry.email', $data['email']);
        }

        if ($data['phone'] != null) {

            $this->db->where('enquiry.phone', $data['phone']);
        }

        if ($data['country'] != null) {

            $this->db->where('enquiry.country_id', $data['country']);
        }

        if ($data['region'] != null) {

            $this->db->where('enquiry.region_id', $data['region']);
        }

        if ($data['territory'] != null) {

            $this->db->where('enquiry.territory_id', $data['territory']);
        }

        if ($data['state'] != null) {

            $this->db->where('enquiry.state_id', $data['state']);
        }

        if ($data['city'] != null) {

            $this->db->where('enquiry.city_id', $data['city']);
        }

        if ($data['organisational_name'] != null) {

            $this->db->where('enquiry.org_name', $data['organisational_name']);
        }

        if ($data['source'] != null) {

            $this->db->where('enquiry.enquiry_source', $data['source']);
        }


        return $this->db->get()->result();
    }

    //Dashboard statitics reports..
    public function enquiry_statitics_data() {

        $query = $this->db->query('SELECT enquiry.enquiry_id,city.city,state.state,tbl_country.country_name, COUNT(enquiry.state_id) as total FROM enquiry left join state on state.id=enquiry.state_id left join city on city.id=enquiry.city_id left join tbl_country on tbl_country.id_c=enquiry.country_id GROUP BY enquiry.state_id HAVING COUNT(enquiry.state_id) >= 1');
        return $query->result();
    }

    //Dashboard statitics lead report 
    public function lead_statitics_data() {
        $cold = 0;
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;
        $stage = $this->db->select('*')->from('lead_stage')->get()->result();
        $stage = $this->db->query('select lead_stage_name from lead_stage')->result();
        $source = $this->db->query('select lead_name from lead_source')->result();
        $lead_score = $this->db->query('select * from lead_score limit 3')->result();

        if ($user_role == 3 || $user_role == 1 || $user_role == 2) {
            /// forlead-----------------------
            $cold = $this->db->query('select SUM(enquiry.op_size) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();
            $warm = $this->db->query('select SUM(enquiry.op_size) as warm FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();
            $hot = $this->db->query('select SUM(enquiry.op_size) as hot FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();
            $total_revnue_lead = $this->db->query('select SUM(enquiry.op_size) as total_revnue_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code  and allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();
            $hotid = $this->db->query('SELECT count(enquiry_id) as hotid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();
            $warmid = $this->db->query('SELECT count(enquiry_id)as warmid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();
            $coldid = $this->db->query('select count(enquiry_id) as coldid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();
            $total_lead = $this->db->query('SELECT COUNT(enquiry_id) AS total_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and  allleads.drop_status=0 and allleads.lead_stage!="Account"')->row();

            $circuit_sheet = $this->db->query('select tbl_boq.circuit_sheet,count(tbl_boq.circuit_sheet)as circuit_sheet,count(tbl_boq.baq_number) as boq from tbl_boq left join allleads on tbl_boq.emp_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $po_receive = $this->db->query('select tbl_po.po_document_img, count(tbl_po.po_id) as po from tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $stage_data = $this->db->query('SELECT allleads.lead_stage,lead_stage.lead_stage_name,COUNT(allleads.lead_stage) as total FROM lead_stage left join allleads on lead_stage.stg_id=allleads.lead_stage  where allleads.lead_stage IS NOT NULL and allleads.country_id=' . $assign_country . ' GROUP BY allleads.lead_stage HAVING COUNT(allleads.lead_stage) >= 1')->result();
            //Total PI per month..
            $pi = $this->db->query('SELECT MONTH(created_date) month, COUNT(*) total FROM tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code WHERE YEAR(tbl_po.created_date)=YEAR(now()) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(tbl_po.created_date)')->result();
            $pi_send = $this->db->query('SELECT MONTH(ld_created) month, COUNT(lead_code) total FROM allleads WHERE YEAR(ld_created)=YEAR(now()) and lead_code NOT IN(select customer_id from tbl_po) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(ld_created)')->result();

            $leads = $this->db->query('SELECT count(allleads.lid) as total,SUM(enquiry.op_size) as total_value,SUM(lead_score=2) as worm,SUM(lead_score=3) as hot,SUM(lead_score=1) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->result();

            $enquiry = $this->db->query('SELECT count(enquiry_id) as total, SUM(op_size) as total_value, SUM(drop_status = 0) AS active, SUM(drop_status > 0) AS dead  FROM enquiry where country_id=' . $assign_country)->result();
            //   print_r($leads);echo '<br>';
            $enquiry_revenue_active = $this->db->query('select SUM(op_size) as total from enquiry where enquiry.country_id=' . $assign_country)->row();
            $enquiry_revenue_dead = $this->db->query('select SUM(op_size) as total from enquiry where is_delete=0 and enquiry.country_id=' . $assign_country)->row();
        } else if ($user_role == 4) {

            $cold = $this->db->query('select SUM(enquiry.enquiry_id) as cold FROM enquiry lead_score=1 and lead_drop_status=0 and lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $warm = $this->db->query('select SUM(enquiry.enquiry_id) as cold FROM enquiry lead_score=2 and lead_drop_status=0 and lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $hot = $this->db->query('select SUM(enquiry.enquiry_id) as cold FROM enquiry lead_score=3 and lead_drop_status=0 and lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $total_revnue_lead = $this->db->query('select SUM(enquiry.enquiry_id) as cold FROM enquiry lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $hotid = $this->db->query('SELECT count(enquiry_id) as hotid WHERE lead_score=3 and lead_drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();
            $warmid = $this->db->query('SELECT count(enquiry_id) as hotid WHERE lead_score=2 and lead_drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $coldid = $this->db->query('SELECT count(enquiry_id) as hotid WHERE lead_score=1 and lead_drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $total_lead = $this->db->query('SELECT count(enquiry_id) as hotid WHERE lead_drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            //	 print_r($coldid);echo '<br>';



            $circuit_sheet = $this->db->query('select tbl_boq.circuit_sheet,count(tbl_boq.circuit_sheet)as circuit_sheet,count(tbl_boq.baq_number) as boq from tbl_boq left join allleads on tbl_boq.emp_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $po_receive = $this->db->query('select tbl_po.po_document_img, count(tbl_po.po_id) as po from tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $stage_data = $this->db->query('SELECT allleads.lead_stage,lead_stage.lead_stage_name,COUNT(allleads.lead_stage) as total FROM lead_stage left join allleads on lead_stage.stg_id=allleads.lead_stage  where allleads.lead_stage IS NOT NULL and allleads.country_id=' . $assign_country . ' GROUP BY allleads.lead_stage HAVING COUNT(allleads.lead_stage) >= 1')->result();


            //Total PI per month..
            $pi = $this->db->query('SELECT MONTH(created_date) month, COUNT(*) total FROM tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code WHERE YEAR(tbl_po.created_date)=YEAR(now()) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(tbl_po.created_date)')->result();
            $pi_send = $this->db->query('SELECT MONTH(ld_created) month, COUNT(lead_code) total FROM allleads WHERE YEAR(ld_created)=YEAR(now()) and lead_code NOT IN(select customer_id from tbl_po) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(ld_created)')->result();

            $leads = $this->db->query('SELECT count(allleads.lid) as total,SUM(enquiry.op_size) as total_value,SUM(lead_score=2) as worm,SUM(lead_score=3) as hot,SUM(lead_score=1) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->result();

            $enquiry = $this->db->query('SELECT count(enquiry_id) as total, SUM(op_size) as total_value, SUM(drop_status = 0) AS active, SUM(drop_status > 0) AS dead  FROM enquiry where country_id=' . $assign_country)->result();
            //   print_r($leads);echo '<br>';
            $enquiry_revenue_active = $this->db->query('select SUM(op_size) as total from enquiry where enquiry.country_id=' . $assign_country)->row();
            $enquiry_revenue_dead = $this->db->query('select SUM(op_size) as total from enquiry where is_delete=0 and enquiry.country_id=' . $assign_country)->row();
        } else if ($user_role == 5) {

            $cold = $this->db->query('select SUM(enquiry.op_size) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $warm = $this->db->query('select SUM(enquiry.op_size) as warm FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $hot = $this->db->query('select SUM(enquiry.op_size) as hot FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $total_revnue_lead = $this->db->query('select SUM(enquiry.op_size) as total_revnue_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code  and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();


            $hotid = $this->db->query('SELECT count(enquiry_id) as hotid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();
            $warmid = $this->db->query('SELECT count(enquiry_id)as warmid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $coldid = $this->db->query('select count(enquiry_id) as coldid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $total_lead = $this->db->query('SELECT COUNT(enquiry_id) AS total_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and  allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            //	 print_r($coldid);echo '<br>';



            $circuit_sheet = $this->db->query('select tbl_boq.circuit_sheet,count(tbl_boq.circuit_sheet)as circuit_sheet,count(tbl_boq.baq_number) as boq from tbl_boq left join allleads on tbl_boq.emp_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $po_receive = $this->db->query('select tbl_po.po_document_img, count(tbl_po.po_id) as po from tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $stage_data = $this->db->query('SELECT allleads.lead_stage,lead_stage.lead_stage_name,COUNT(allleads.lead_stage) as total FROM lead_stage left join allleads on lead_stage.stg_id=allleads.lead_stage  where allleads.lead_stage IS NOT NULL and allleads.country_id=' . $assign_country . ' GROUP BY allleads.lead_stage HAVING COUNT(allleads.lead_stage) >= 1')->result();


            //Total PI per month..
            $pi = $this->db->query('SELECT MONTH(created_date) month, COUNT(*) total FROM tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code WHERE YEAR(tbl_po.created_date)=YEAR(now()) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(tbl_po.created_date)')->result();
            $pi_send = $this->db->query('SELECT MONTH(ld_created) month, COUNT(lead_code) total FROM allleads WHERE YEAR(ld_created)=YEAR(now()) and lead_code NOT IN(select customer_id from tbl_po) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(ld_created)')->result();

            $leads = $this->db->query('SELECT count(allleads.lid) as total,SUM(enquiry.op_size) as total_value,SUM(lead_score=2) as worm,SUM(lead_score=3) as hot,SUM(lead_score=1) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->result();

            $enquiry = $this->db->query('SELECT count(enquiry_id) as total, SUM(op_size) as total_value, SUM(drop_status = 0) AS active, SUM(drop_status > 0) AS dead  FROM enquiry where country_id=' . $assign_country)->result();
            //   print_r($leads);echo '<br>';
            $enquiry_revenue_active = $this->db->query('select SUM(op_size) as total from enquiry where enquiry.country_id=' . $assign_country)->row();
            $enquiry_revenue_dead = $this->db->query('select SUM(op_size) as total from enquiry where is_delete=0 and enquiry.country_id=' . $assign_country)->row();
        } else if ($user_role == 6) {

            $cold = $this->db->query('select SUM(enquiry.op_size) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $warm = $this->db->query('select SUM(enquiry.op_size) as warm FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $hot = $this->db->query('select SUM(enquiry.op_size) as hot FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $total_revnue_lead = $this->db->query('select SUM(enquiry.op_size) as total_revnue_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code  and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();


            $hotid = $this->db->query('SELECT count(enquiry_id) as hotid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();
            $warmid = $this->db->query('SELECT count(enquiry_id)as warmid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $coldid = $this->db->query('select count(enquiry_id) as coldid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            $total_lead = $this->db->query('SELECT COUNT(enquiry_id) AS total_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and  allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->row();

            //	 print_r($coldid);echo '<br>';



            $circuit_sheet = $this->db->query('select tbl_boq.circuit_sheet,count(tbl_boq.circuit_sheet)as circuit_sheet,count(tbl_boq.baq_number) as boq from tbl_boq left join allleads on tbl_boq.emp_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $po_receive = $this->db->query('select tbl_po.po_document_img, count(tbl_po.po_id) as po from tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $stage_data = $this->db->query('SELECT allleads.lead_stage,lead_stage.lead_stage_name,COUNT(allleads.lead_stage) as total FROM lead_stage left join allleads on lead_stage.stg_id=allleads.lead_stage  where allleads.lead_stage IS NOT NULL and allleads.country_id=' . $assign_country . ' GROUP BY allleads.lead_stage HAVING COUNT(allleads.lead_stage) >= 1')->result();


            //Total PI per month..
            $pi = $this->db->query('SELECT MONTH(created_date) month, COUNT(*) total FROM tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code WHERE YEAR(tbl_po.created_date)=YEAR(now()) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(tbl_po.created_date)')->result();
            $pi_send = $this->db->query('SELECT MONTH(ld_created) month, COUNT(lead_code) total FROM allleads WHERE YEAR(ld_created)=YEAR(now()) and lead_code NOT IN(select customer_id from tbl_po) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(ld_created)')->result();

            $leads = $this->db->query('SELECT count(allleads.lid) as total,SUM(enquiry.op_size) as total_value,SUM(lead_score=2) as worm,SUM(lead_score=3) as hot,SUM(lead_score=1) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.country_id=' . $assign_country)->result();

            $enquiry = $this->db->query('SELECT count(enquiry_id) as total, SUM(op_size) as total_value, SUM(drop_status = 0) AS active, SUM(drop_status > 0) AS dead  FROM enquiry where country_id=' . $assign_country)->result();
            //   print_r($leads);echo '<br>';
            $enquiry_revenue_active = $this->db->query('select SUM(op_size) as total from enquiry where enquiry.country_id=' . $assign_country)->row();
            $enquiry_revenue_dead = $this->db->query('select SUM(op_size) as total from enquiry where is_delete=0 and enquiry.country_id=' . $assign_country)->row();
        } else if ($user_role == 7) {
            $cold = $this->db->query('select SUM(enquiry.op_size) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $warm = $this->db->query('select SUM(enquiry.op_size) as warm FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $hot = $this->db->query('select SUM(enquiry.op_size) as hot FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $total_revnue_lead = $this->db->query('select SUM(enquiry.op_size) as total_revnue_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code  and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();


            $hotid = $this->db->query('SELECT count(enquiry_id) as hotid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();
            $warmid = $this->db->query('SELECT count(enquiry_id)as warmid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $coldid = $this->db->query('select count(enquiry_id) as coldid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $total_lead = $this->db->query('SELECT COUNT(enquiry_id) AS total_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and  allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            //	 print_r($coldid);echo '<br>';



            $circuit_sheet = $this->db->query('select tbl_boq.circuit_sheet,count(tbl_boq.circuit_sheet)as circuit_sheet,count(tbl_boq.baq_number) as boq from tbl_boq left join allleads on tbl_boq.emp_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $po_receive = $this->db->query('select tbl_po.po_document_img, count(tbl_po.po_id) as po from tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code and allleads.country_id=' . $assign_country)->row();

            $stage_data = $this->db->query('SELECT allleads.lead_stage,lead_stage.lead_stage_name,COUNT(allleads.lead_stage) as total FROM lead_stage left join allleads on lead_stage.stg_id=allleads.lead_stage  where allleads.lead_stage IS NOT NULL and allleads.country_id=' . $assign_country . ' GROUP BY allleads.lead_stage HAVING COUNT(allleads.lead_stage) >= 1')->result();


            //Total PI per month..
            $pi = $this->db->query('SELECT MONTH(created_date) month, COUNT(*) total FROM tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code WHERE YEAR(tbl_po.created_date)=YEAR(now()) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(tbl_po.created_date)')->result();
            $pi_send = $this->db->query('SELECT MONTH(ld_created) month, COUNT(lead_code) total FROM allleads WHERE YEAR(ld_created)=YEAR(now()) and lead_code NOT IN(select customer_id from tbl_po) and allleads.country_id=' . $assign_country . ' GROUP BY MONTH(ld_created)')->result();

            $leads = $this->db->query('SELECT count(allleads.lid) as total,SUM(enquiry.op_size) as total_value,SUM(lead_score=2) as worm,SUM(lead_score=3) as hot,SUM(lead_score=1) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->result();

            $enquiry = $this->db->query('SELECT count(enquiry_id) as total, SUM(op_size) as total_value, SUM(drop_status = 0) AS active, SUM(drop_status > 0) AS dead  FROM enquiry where country_id=' . $assign_country)->result();
            //   print_r($leads);echo '<br>';
            $enquiry_revenue_active = $this->db->query('select SUM(op_size) as total from enquiry where enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();
            $enquiry_revenue_dead = $this->db->query('select SUM(op_size) as total from enquiry where is_delete=0 and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();
        } elseif ($user_role == 8 || $user_role == 9) {

            $cold = $this->db->query('select SUM(enquiry.op_size) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $warm = $this->db->query('select SUM(enquiry.op_size) as warm FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $hot = $this->db->query('select SUM(enquiry.op_size) as hot FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $total_revnue_lead = $this->db->query('select SUM(enquiry.op_size) as total_revnue_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code  and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();


            $hotid = $this->db->query('SELECT count(enquiry_id) as hotid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=3 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();
            $warmid = $this->db->query('SELECT count(enquiry_id)as warmid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=2 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $coldid = $this->db->query('select count(enquiry_id) as coldid FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.lead_score=1 and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            $total_lead = $this->db->query('SELECT COUNT(enquiry_id) AS total_lead FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and  allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();

            //	 print_r($coldid);echo '<br>';



            $circuit_sheet = $this->db->query('select tbl_boq.circuit_sheet,count(tbl_boq.circuit_sheet)as circuit_sheet,count(tbl_boq.baq_number) as boq from tbl_boq left join allleads on tbl_boq.emp_id=allleads.lead_code')->row();

            $po_receive = $this->db->query('select tbl_po.po_document_img, count(tbl_po.po_id) as po from tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code')->row();

            $stage_data = $this->db->query('SELECT allleads.lead_stage,lead_stage.lead_stage_name,COUNT(allleads.lead_stage) as total FROM lead_stage left join allleads on lead_stage.stg_id=allleads.lead_stage  where allleads.lead_stage IS NOT NULL GROUP BY allleads.lead_stage HAVING COUNT(allleads.lead_stage) >= 1')->result();


            //Total PI per month..
            $pi = $this->db->query('SELECT MONTH(created_date) month, COUNT(*) total FROM tbl_po left join allleads on tbl_po.customer_id=allleads.lead_code WHERE YEAR(tbl_po.created_date)=YEAR(now()) GROUP BY MONTH(tbl_po.created_date)')->result();
            $pi_send = $this->db->query('SELECT MONTH(ld_created) month, COUNT(lead_code) total FROM allleads WHERE YEAR(ld_created)=YEAR(now()) and lead_code NOT IN(select customer_id from tbl_po) GROUP BY MONTH(ld_created)')->result();

            $leads = $this->db->query('SELECT count(allleads.lid) as total,SUM(enquiry.op_size) as total_value,SUM(lead_score=2) as worm,SUM(lead_score=3) as hot,SUM(lead_score=1) as cold FROM allleads JOIN enquiry ON enquiry.Enquery_id = allleads.lead_code and allleads.drop_status=0 and allleads.lead_stage!="Account" and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->result();

            $enquiry = $this->db->query('SELECT count(enquiry_id) as total, SUM(op_size) as total_value, SUM(drop_status = 0) AS active, SUM(drop_status > 0) AS dead  FROM enquiry')->result();
            //   print_r($leads);echo '<br>';
            $enquiry_revenue_active = $this->db->query('select SUM(op_size) as total from enquiry where enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();
            $enquiry_revenue_dead = $this->db->query('select SUM(op_size) as total from enquiry where is_delete=0 and enquiry.created_by=' . $user_id . ' or enquiry.aasign_to=' . $user_id)->row();
        }


        return array('cold' => $cold, 'warm' => $warm, 'hot' => $hot, 'coldid' => $coldid, 'warmid' => $warmid, 'hotid' => $hotid, 'stage' => $stage, 'circuit_sheet' => $circuit_sheet, 'po' => $po_receive, 'pi' => $pi, 'pi_send' => $pi_send, 'enquiry' => $enquiry, 'active' => $enquiry_revenue_active, 'dead' => $enquiry_revenue_dead, 'stage_data' => $stage_data, 'source' => $source, 'total_revnue_lead' => $total_revnue_lead, 'total_lead' => $total_lead);
    }

    public function lead_opportunities_status() {
        return array('hot' => isset($hot) ? $hot : 0, 'revenue' => isset($total_revenue) ? $total_revenue : 0, 'warm' => isset($warm) ? $warm : 0, 'cold' => isset($cold) ? $cold : 0, 'total_lead' => isset($total_lead) ? $total_lead : 0);
    }

    public function client_opportunity_status() {

        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;

        if ($user_role == 3 || $user_role == 1 || $user_role == 2) {
            $total_client = $this->db->query('select count(*) as total from clients where cl_status=1')->row();

            $comp_installation = $this->db->query('select count(site_audit_report.a_id) as total from site_audit_report left join clients on site_audit_report.customer_code=clients.customer_code where site_audit_report.is_approved=1')->row();

            $installation = $this->db->query('select count(clients.cli_id) as total from clients left join site_audit_report on site_audit_report.customer_code=clients.customer_code')->row();

            $installation_pendding['total'] = $installation->total - $comp_installation->total;

            $client_revenue = $this->db->query('select SUM(tbl_itemlist.Unite_p) as total from tbl_boq left join tabl_cicuit_item on tbl_boq.baq_id=tabl_cicuit_item.boq_id left join tbl_itemlist on tabl_cicuit_item.item_id=tbl_itemlist.itemlist_id left join clients on tbl_boq.emp_id=clients.customer_code where clients.cl_status=1')->row();

            $installation_rev = $this->db->query('select SUM(tbl_itemlist.Unite_p) as total from tbl_itemlist join tabl_cicuit_item on tabl_cicuit_item.item_id=tbl_itemlist.itemlist_id join tbl_boq on tbl_boq.baq_id=tabl_cicuit_item.boq_id join site_audit_report on site_audit_report.customer_code=tbl_boq.emp_id where site_audit_report.is_approved=1')->row();

            $rev_penddings['total'] = $client_revenue->total - $installation_rev->total;
            $client_by_country = $this->db->query('select  count(clients.state_id) as total,state.state from clients join state on clients.state_id =state.id where clients.cl_status=1 group by clients.state_id HAVING COUNT(clients.state_id) >= 1')->result();

            $actvieticket_value = $this->db->query('select SUM(tbl_itemlist.Unite_p) as total from tbl_itemlist join tabl_cicuit_item on tabl_cicuit_item.item_id=tbl_itemlist.itemlist_id join tbl_boq on tbl_boq.baq_id=tabl_cicuit_item.boq_id join clients on clients.customer_code=tbl_boq.emp_id join (SELECT DISTINCT cl_id,ticket_status FROM ticket) as ticket on clients.cli_id=ticket.cl_id where ticket.ticket_status!=4 ')->row();
            $actvieticket = $this->db->query('select count(tid) as total from ticket where ticket_status!=4')->row();

            $total_magnet1 = $this->db->query('select SUM(tbl_itemlist.Unite_p) as total  from tbl_itemlist join tabl_cicuit_item on tabl_cicuit_item.item_id=tbl_itemlist.itemlist_id join tbl_boq on tbl_boq.baq_id=tabl_cicuit_item.boq_id  join enquiry on enquiry.Enquery_id =tbl_boq.emp_id join  clients on clients.customer_code=enquiry.Enquery_id  where enquiry.channel_partnr_type=1')->row();

            $total_tyconn2 = $this->db->query('select SUM(tbl_itemlist.Unite_p) as total  from tbl_itemlist join tabl_cicuit_item on tabl_cicuit_item.item_id=tbl_itemlist.itemlist_id join tbl_boq on tbl_boq.baq_id=tabl_cicuit_item.boq_id  join enquiry on enquiry.Enquery_id =tbl_boq.emp_id join  clients on clients.customer_code=enquiry.Enquery_id  where enquiry.channel_partnr_type=2')->row();

            $total_ranger3 = $this->db->query('select SUM(tbl_itemlist.Unite_p) as total  from tbl_itemlist join tabl_cicuit_item on tabl_cicuit_item.item_id=tbl_itemlist.itemlist_id join tbl_boq on tbl_boq.baq_id=tabl_cicuit_item.boq_id  join enquiry on enquiry.Enquery_id =tbl_boq.emp_id join  clients on clients.customer_code=enquiry.Enquery_id  where enquiry.channel_partnr_type=3')->row();
            $total_magnet = $this->db->query('select count(clients.cli_id) as total from clients join enquiry on enquiry.Enquery_id =clients.customer_code where enquiry.channel_partnr_type=1')->row();
            $total_tycon = $this->db->query('select count(clients.cli_id) as total from clients join enquiry on enquiry.Enquery_id =clients.customer_code where enquiry.channel_partnr_type=2')->row();
            $total_ranger = $this->db->query('select count(clients.cli_id) as total from clients join enquiry on enquiry.Enquery_id =clients.customer_code where enquiry.channel_partnr_type=3')->row();
        }
        return array('total_magnet' => $total_magnet, 'total_tycon' => $total_tycon, 'total_ranger' => $total_ranger, 'total_ranger3' => $total_ranger3, 'total_magnet1' => $total_magnet1, 'total_tyconn2' => $total_tyconn2, 'clients' => $total_client, 'actvieticket' => $actvieticket, 'actvieticket_value' => $actvieticket_value, 'installation' => $comp_installation, 'pendding' => $installation_pendding, 'total_revenue' => $client_revenue, 'inst_rev' => $installation_rev, 'inst_pen' => $rev_penddings, 'states' => $client_by_country);
    }
    
    public function enquiry_source_data() {
        $source = $this->db->select('lead_name')->from('lead_source')->get()->result();
        return array('source' => $source);
    }

    //Funnel chart Report..
    public function funnel_report() {

        $total_enquery = $this->db->query('Select count(enquiry_id)as total from enquiry where is_delete=1')->result();

        $total_lead = $this->db->query('SELECT COUNT(*) AS total FROM enquiry where lead_drop_status=0 and lead_stage!=0')->result();

        $total_clients = $this->db->query('select count(cli_id)as total from clients where cl_status=1')->result();

        $total_installation = $this->db->query('select count(a_id)as total from site_audit_report where is_approved=1')->result();
        return array('enquiry' => $total_enquery, 'lead' => $total_lead, 'clients' => $total_clients, 'installations' => $total_installation);
    }
    
    public function all_country(){
		$company=$this->session->userdata('companey_id');
        $this->db->select('*');
        $this->db->from('tbl_product_country');
		$this->db->where('comp_id', $company);
        return $query = $this->db->get()->result();
}

    public function all_institute(){
		$company=$this->session->userdata('companey_id');
        $this->db->select('*');
        $this->db->from('tbl_institute');
		$this->db->where('comp_id', $company);
        return $query = $this->db->get()->result();
}
    public function all_center(){
		$company=$this->session->userdata('companey_id');
        $this->db->select('*');
        $this->db->from('tbl_center');
		$this->db->where('comp_id', $company);
        return $query = $this->db->get()->result();
}

  public function all_source(){
	    $company=$this->session->userdata('companey_id');
        $this->db->select('*');
        $this->db->from('lead_source');
		$this->db->where('comp_id', $company);
        return $query = $this->db->get()->result();
}

  public function all_subsource(){
	    $company=$this->session->userdata('companey_id');
        $this->db->select('*');
        $this->db->from('tbl_subsource');
		$this->db->where('comp_id', $company);
        return $query = $this->db->get()->result();
}

  public function all_datasource(){
	    $company=$this->session->userdata('companey_id');
        $this->db->select('*');
        $this->db->from('tbl_datasource');
		$this->db->where('comp_id', $company);
        return $query = $this->db->get()->result();
}

    public function all_employee(){
		$company=$this->session->userdata('companey_id');
        $this->db->select('*');
        $this->db->from('tbl_admin');
		$this->db->where('companey_id', $company);
        return $query = $this->db->get()->result();
    }

    public function all_company_employee($company_id)
    {   $this->load->model('common_model');
        $users    =   $this->common_model->get_categories($this->session->user_id);
        $this->db->select('pk_i_admin_id,s_display_name,last_name,companey_id,b_status');
        $this->db->from('tbl_admin');
        $this->db->where('companey_id',$company_id);
        $this->db->where('b_status',1);
        
        $this->db->group_start();
        $user_ids_chunk = array_chunk($users,100);
        foreach($user_ids_chunk as $id){
            $this->db->or_where_in('pk_i_admin_id', $id);
        }
        $this->db->group_end();
        //$this->db->where_in('pk_i_admin_id',$users);        
        return $query = $this->db->get()->result();
    }
    public function get_all_reports($type){
        $this->db->select("reports.*,CONCAT_WS(' ',tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name");
        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=reports.created_by','inner');
        $this->db->where('comp_id',$this->session->companey_id);
        $this->db->where('type',$type);
        $this->db->where('reports.created_by',$this->session->user_id);
        return $this->db->get('reports')->result_array();
    }
	
	public function get_dynfields($pross = "",$for=0){
		
		$this->db->select("*");
        $this->db->where('company_id',$this->session->companey_id);
        //$this->db->where('page_id',$for);
		$this->db->where("tbl_input.status", 1);
		$this->db->where("tbl_input.input_type!=", 19);
        /*if(!empty($pross)){
			$this->db->where("process_id", $pross);
		}*/
        if(!empty($this->session->process[0])){
            $process_id = $this->session->process[0];
			$this->db->where("FIND_IN_SET($process_id,process_id)>",0);

        }
		
        return $this->db->get('tbl_input')->result_array();
		
	}
	public function getdynfielsval(){
	    //$filter_date= date('Y-m-d H:i:s', strtotime("-3 days"));
		$this->db->select("flds.*,inp.input_label");
        $this->db->where("flds.cmp_no", $this->session->companey_id);
		$this->db->where("inp.status", 1);
		//$this->db->where("flds.created_date>", $filter_date);
		$this->db->from("extra_enquery flds");
		$this->db->join("tbl_input inp", "inp.input_id = flds.input");
		$fldarr = $this->db->get()->result();
		$newarr = array();
		foreach($fldarr as $ind => $fld){		
			$newarr[$fld->parent][$fld->input_label] = $fld;
		}
		return $newarr;
	}
	
}
