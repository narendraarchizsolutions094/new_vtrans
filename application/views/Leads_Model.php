<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leads_Model extends CI_Model {

    public function get_list_admin($aid) {
        $this->db->select(" * ");
        $this->db->from('allleads');
        $this->db->join('users', 'users.uid = allleads.adminid');
        $this->db->join('lead_source', 'lead_source.lsid = allleads.ld_source');
        $this->db->where('allleads.adminid', $aid);
        $query = $this->db->get();
        return $query->result();
    }
public function all_course($course,$lvl,$length,$disc) {

        $this->db->select("tbl_course.*,tbl_crsmaster.course_name as course_name_str");
        $this->db->from('tbl_course');
        $this->db->where('tbl_course.institute_id',$course);
        $this->db->where('tbl_course.length_id', $length);
        $this->db->where('tbl_course.level_id', $lvl);
        $this->db->where('tbl_course.discipline_id', $disc);
        $this->db->join('tbl_crsmaster','tbl_crsmaster.id=tbl_course.course_name');
        $this->db->where('tbl_course.comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
    public function get_list_superadmin() {
        $this->db->select(" * ");
        $this->db->from('allleads');
        $this->db->join('users', 'users.uid = allleads.adminid');
        $this->db->join('lead_source', 'lead_source.lsid = allleads.ld_source');
        //$this->db->where('allleads.adminid',$aid);
        $this->db->order_by('allleads.lid', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_leadList() {
        return $this->db->select(" * ")
                        ->from("allleads")
                        ->join('tbl_admin', 'tbl_admin.pk_i_admin_id = allleads.lid', 'left')
                        ->join('lead_source', 'lead_source.lsid = allleads.ld_source', 'left')
                        ->join('lead_stage', 'lead_stage.stg_id = allleads.lead_stage', 'left')
                        ->join('lead_score', 'lead_score.sc_id = allleads.lead_score', 'left')
                        ->where('allleads.lead_stage!=', 'Account')
                        ->where('allleads.drop_status', 0)
                        ->get()
                        ->result();
    }

    public function get_leadListDetailsby_id($leadid) {
        return $this->db->select("*,enquiry.created_date,tbl_newdeal.bank,enquiry.country_id as enq_country,enquiry.state_id as enquiry_state_id,enquiry.city_id as enquiry_city_id,enquiry.address,enquiry.status,tbl_product_country.country_name,tbl_product_country.id as country_id,tbl_product.product_name,tbl_center.center_name")
                        ->from('enquiry')
                        ->join('tbl_product_country', 'tbl_product_country.id=enquiry.enquiry_subsource', 'left')
                        ->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.created_by', 'left')
                        ->join('tbl_product', 'tbl_product.sb_id=enquiry.product_id', 'left')
                        ->join('tbl_center', 'tbl_center.center_id=enquiry.center_id', 'left')
                        ->join('tbl_datasource', 'tbl_datasource.datasource_id=enquiry.datasource_id', 'left')
                        ->join('tbl_newdeal','tbl_newdeal.enq_id=enquiry.Enquery_id','left')
                        ->where('enquiry.enquiry_id', $leadid)
                        ->where('enquiry.is_delete', '1')
                        ->get()
                        ->row();
    }
    public function get_leadListDetailsby_id2($leadid) {
        return $this->db->select("*,enquiry.created_date,enquiry.country_id as enq_country,enquiry.state_id as enquiry_state_id,enquiry.city_id as enquiry_city_id,enquiry.address,enquiry.status,tbl_product_country.country_name,tbl_product_country.id as country_id,tbl_product.product_name,tbl_center.center_name")
                        ->from('enquiry')
                        ->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left')
                        ->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.created_by', 'left')
                        ->join('tbl_product', 'tbl_product.sb_id=enquiry.product_id', 'left')
                        ->join('tbl_center', 'tbl_center.center_id=enquiry.center_id', 'left')
                        ->join('tbl_datasource', 'tbl_datasource.datasource_id=enquiry.datasource_id', 'left')
                        ->where('enquiry.Enquery_id', $leadid)
                        ->where('enquiry.is_delete', '1')
                        ->get()
                        ->row();
    }
    

    public function get_leadListDetailsby_ledsonly($leadid) {

        $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product.product_name,tbl_product_country.country_name,tbl_product_country.id as country_id");
        
        $this->db->from('enquiry');
        
        $this->db->join('lead_source', 'lead_source.lsid = enquiry.enquiry_source', 'left');
        
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage', 'left');
        
        $this->db->join('lead_score', 'lead_score.sc_id = enquiry.lead_score', 'left');
        
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        
        $this->db->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left');
        
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.aasign_to', 'left');
        
        $this->db->where('enquiry.enquiry_id', $leadid);
        
        return $this->db->get()->row();

    }

    public function get_leadListDetailsby_code($leadid) {
        
        $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product.product_name,tbl_product_country.country_name,tbl_product_country.id as country_id");
        $this->db->from('enquiry');
        $this->db->join('lead_source', 'lead_source.lsid = enquiry.enquiry_source', 'left');
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage', 'left');
        $this->db->join('lead_score', 'lead_score.sc_id = enquiry.lead_score', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        $this->db->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left');
        $this->db->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.aasign_to', 'left');
        $this->db->where('enquiry.Enquery_id', $leadid);
        return $this->db->get()->row();
    }

    public function get_leadid_byCODE($leadcode) {
        $this->db->select("lid");
        $this->db->from('allleads');
        $this->db->where('lead_code', $leadcode);
        return $query = $this->db->get()->result();
    }

    public function get_lead_for_account($leadid) {
        return $this->db->select(" * ")
                        ->from("allleads")
                        ->where('allleads.lid', $leadid)
                        ->get()
                        ->row();
    }

    public function get_ld_srce_list($ld_source_id) {
        $this->db->select("lead_name");
        $this->db->from('lead_source');
        $this->db->where('lsid', $ld_source_id);
        $query = $this->db->get();
        return $query->result();
    }

    function get_location($id) {
        $this->db->select(" * ");
        $this->db->from('allleads');
        $this->db->join('location_master', 'location_master.lid = allleads.location');
        $this->db->where('allleads.id', $id);
        $query = $this->db->get();
        return $query->result();
    }

function find_stage($key='') {
    $this->load->helper('cookie');                        
    $process_filter = get_cookie('selected_process');
    $process_filter1 = explode(',', $process_filter);
if(count($process_filter1)>'1'){
    $where = "FIND_IN_SET('".$key."', stage_for)";
       
      
        $this->db->select(" * ");
        $this->db->from('lead_stage');
        $this->db->where('comp_id',$this->session->userdata('companey_id'));
        $this->db->where( $where );
        foreach ($process_filter1 as $key => $value) {
        $key = "FIND_IN_SET('".$value."', process_id)";
        $this->db->or_where( $key );
        }
        $query = $this->db->get();
        return $query->result();
}else{
    $where = "FIND_IN_SET('".$key."', stage_for)";
    $where1 = "FIND_IN_SET('".$process_filter."', process_id)";
      
        $this->db->select(" * ");
        $this->db->from('lead_stage');
        $this->db->where('comp_id',$this->session->userdata('companey_id'));
        $this->db->where( $where );
        $this->db->where( $where1 );
        $query = $this->db->get();
        return $query->result();
}
    }

function get_leadstage_name($id) {
        $this->db->select(" * ");
        $this->db->from('lead_stage');
        $this->db->where('comp_id',$this->session->userdata('companey_id'));
        $this->db->where('process_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /*function find_estage($enquiry_id) {
        $this->db->select("*");
        $this->db->from('enquiry');
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage','left');
        $this->db->where('enquiry.enquiry_id', $enquiry_id);
        $query = $this->db->get();
        return $query->result();
    }*/


    function find_estage($product_id,$stage_for='') {
        if (!empty($product_id)) {
            $this->db->select("*");
            $this->db->from('lead_stage');            
            $this->db->where("FIND_IN_SET($product_id,lead_stage.process_id)>",0);
            if ($stage_for) {
                $this->db->where("FIND_IN_SET($stage_for,lead_stage.stage_for)>",0);                
            }
            $query = $this->db->get();            
            $res = $query->result();
        }else{
            $res = false;
        }
        return $res;
    }

    function find_estage1() {
        $this->db->select("*");
        $this->db->from('enquiry');
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage','left');
     
        $query = $this->db->get();
        return $query->result();
    }
    
    function find_comments($enquiry_id) {
        $company=$this->session->userdata('companey_id');
        $this->db->select("*");
        $this->db->from('tbl_comment');
        $this->db->order_by("comm_id","asc");
        $this->db->join('enquiry', 'enquiry.Enquery_id = tbl_comment.lead_id');
        $this->db->where('enquiry.enquiry_id', $enquiry_id);
        $this->db->where('lead_description.comp_id',$company);
        $query = $this->db->get();
        return $query->result();
    }
    
    function find_description() {
        $this->db->select("*");
        $this->db->from('lead_description');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

public function all_description($diesc) {

        $this->db->select("*");
        $this->db->from('lead_description');
        $this->db->where('lead_stage_id',$diesc);
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

public function all_visa_list($diesc) {

        $this->db->select("tbl_visa_type.id as vid,tbl_visa_type.visa_type as vnm");
        $this->db->from('tbl_visa_mapping');
        $this->db->join('tbl_visa_type', 'tbl_visa_type.id = tbl_visa_mapping.visa_type');
        $this->db->where('tbl_visa_mapping.cntry_id',$diesc);
        $this->db->where('tbl_visa_mapping.comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

public function all_class_list($diesc,$diesc1) {

        $this->db->select("*");
        $this->db->from('tbl_visa_mapping');
        $this->db->where('cntry_id',$diesc);
        $this->db->where('visa_type',$diesc1);
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function LeadAdd($data) {
        $this->db->insert('allleads', $data);
    }

    public function get_leadsource_list() {
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $this->db->order_by('order','DESC');
        $query = $this->db->get('lead_source');
        return $query->result();
    }


  public function get_leadsource_list_api($compno) {
        $this->db->where('comp_id', $compno);
        $query = $this->db->get('lead_source');
        return $query->result();
    }

    public function lead_sourceadd($data) {
        $this->db->insert('lead_source', $data);
        return $this->db->insert_id();
    }

    public function lead_sourceedit($data, $lead_id) {
        $this->db->where('lsid', $lead_id);
        $query = $this->db->update('lead_source', $data);
    }

    public function lead_scoreadd($data) {
        $this->db->insert('lead_score', $data);
    }

    public function get_leadscore_list() {
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get('lead_score');
        return $query->result();
    }

    public function get_leadstage_all() {
        $this->db->select('stg_id,lead_stage_name');        
        $this->db->where('comp_id',$this->session->userdata('companey_id'));
        $this->db->order_by("stg_id", "asc");
        $query = $this->db->get('lead_stage');
        return $query->result();
    }

    public function get_leadstage_list() {
        $this->db->select('lead_stage.*,tbl_product.product_name');
        $this->db->join('tbl_product','tbl_product.sb_id=lead_stage.process_id','left');        
        $this->db->where('lead_stage.comp_id',$this->session->userdata('companey_id'));
        $this->db->order_by("lead_stage.stg_id", "asc");
        $query = $this->db->get('lead_stage');
        return $query->result();
    }

    public function get_leadstage_mapping() {
        $this->db->select('*');       
        $this->db->where('comp_id',$this->session->userdata('companey_id'));
        $this->db->order_by("id", "asc");
        $query = $this->db->get('stage_visible');
        return $query->result();
    }
    
    public function get_course_list() {
        $this->db->select('*');
        $this->db->order_by("tbl_course.crs_id", "asc");
        $query = $this->db->get('tbl_course');
        return $query->result();
    }

    public function get_leadstage_list_byprocess() {
   
       $this->db->select('*');
       $this->db->from('lead_stage');
       $this->db->join('tbl_product','tbl_product.sb_id=lead_stage.process_id','inner');

        $this->db->where('lead_stage.comp_id',$this->session->userdata('companey_id'));
        $this->db->order_by("stg_id", "asc");
        return $this->db->get()->result();
    }

    public function get_leadstage_list_byprocess1($process_id) {
        //print_r($process_id);exit();
        // print_r($this->session->userdata('companey_id'));
         if(empty($process_id)){
        
           $id1 = '';
       }
       if(is_array($process_id))
       {
           $id = implode(',',$process_id);
           $id1 = explode(",", $id);
       }
   
    else{
       
         $id1 = $process_id;
       } 
        $this->db->where('comp_id',$this->session->userdata('companey_id'));
        $this->db->where_in("process_id",$id1);
        // $this->db->order_by("stg_id", "asc");
        $query = $this->db->get('lead_stage');
        return $query->result();
    }

    public function get_leadstage_withoutprocess(){

        $this->db->where('comp_id',$this->session->userdata('companey_id'));
        $this->db->where('process_id','0');
        $query = $this->db->get('lead_stage');
        return $query->result();
    }

    public function lead_stageadd($data) {
        $this->db->insert('lead_stage', $data);
    }

    public function lead_stagemapp($data) {
        $this->db->insert('stage_visible', $data);
    }

    public function add_customerType($data) {
        $this->db->insert('customer_type', $data);
    }

    public function get_customerType_list() {
        $query = $this->db->get('customer_type');
        return $query->result();
    }

    public function get_drop_list() {
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get('tbl_drop');
        return $query->result();
    }

    public function add_dropType($data) {
        $this->db->insert('tbl_drop', $data);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    public function get_lead_response($leadid) {
        $this->db->select(" * ");
        $this->db->from('query_responses_update');
        $this->db->where('query_id', $leadid);
        $this->db->order_by('uid', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_query_response($lead_id) {
        $this->db->select(" * ");
        $this->db->from('query_response');
        $this->db->where('query_id', $lead_id);
        $query = $this->db->get();
        return $query->result();
    }

    //////////////////// Move Lead To Client /////////////////////////////////////
    public function ClientMove($data) {
        $this->db->insert('clients', $data);
    }

    ////////////////////////////////////////////////////////////



    public function all_leadss() {
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
       // print_r($user_role);exit;
        $assign_country = $this->session->country_id;
        $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product.product_name,tbl_product_country.country_name,tbl_product_country.id as country_id,,tbl_datasource.datasource_name");
        $this->db->from('enquiry');
        $this->db->join('lead_source', 'lead_source.lsid = enquiry.enquiry_source', 'left');
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage', 'left');
        $this->db->join('lead_score', 'lead_score.sc_id = enquiry.lead_score', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        $this->db->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');


         $date=date('Y-m-d');
            

        $where = '';
        
        $where.=" enquiry.status=2";
        
        

        if($user_role==3){  
            $where.=" AND enquiry.country_id=$assign_country";
        }else if($user_role==4){
           $where.=" AND enquiry.region_id=$assign_region";

         }else if($user_role==5){
        $where.=" AND enquiry.territory_id=$assign_territory";

         }else if($user_role==6){
        $where.=" AND enquiry.state_id=$assign_state";
        
         }else if($user_role==7){
        $where.=" AND enquiry.city_id=$assign_city";
    
         }elseif($user_role==8||$user_role==9){
          
          $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";


          $process = $this->session->process;
          $where.= " AND enquiry.product_id=$process";
         
        }

        $where.=" AND enquiry.status=2";
       

        $this->db->where($where);


        $this->db->order_by('enquiry.enquiry_id', 'desc');

        return $this->db->get();
    

 
    }

    public function all_created_today() {
        
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $assign_country = $this->session->country_id;
        
        $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product.product_name,tbl_product_country.country_name,tbl_product_country.id as country_id,tbl_datasource.datasource_name");
        $this->db->from('enquiry');
        $this->db->join('lead_source', 'lead_source.lsid = enquiry.enquiry_source', 'left');
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage', 'left');
        $this->db->join('lead_score', 'lead_score.sc_id = enquiry.lead_score', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        $this->db->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');
        
        
            $date=date('Y-m-d');
            

            $where = '';
            
            $where.=" enquiry.status=2";
            
            $where.=" AND enquiry.drop_status=0";
            
            
            //$where.=" AND DATE(enquiry.update_date)=date('Y-m-d')";

            

            if($user_role==3){  
                $where.=" AND enquiry.country_id=$assign_country";
            }else if($user_role==4){
               $where.=" AND enquiry.region_id=$assign_region";

             }else if($user_role==5){
            $where.=" AND enquiry.territory_id=$assign_territory";
    
             }else if($user_role==6){
            $where.=" AND enquiry.state_id=$assign_state";
            
             }else if($user_role==7){
            $where.=" AND enquiry.city_id=$assign_city";
        
             }elseif($user_role==8||$user_role==9){
              
              $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";


              $process = $this->session->process;
              $where.= " AND enquiry.product_id=$process";
             }

            $where.=" AND enquiry.status=2";


            $where.=" AND enquiry.created_date LIKE '%$date%'";
           
           //  echo $where;
            $this->db->where($where);


        //$this->db->where('DATE(enquiry.update_date)', date('Y-m-d'));
        
        //$this->db->where('enquiry.lead_drop_status', 0);
        
        //$this->db->where('enquiry.status',2);

        /*if ($user_role == 3) {
            $this->db->where('enquiry.country_id', $assign_country);
        } else if ($user_role == 4) {
            $this->db->where('enquiry.region_id', $assign_region);
        } else if ($user_role == 5) {
            $this->db->where('enquiry.territory_id', $assign_territory);
        } else if ($user_role == 6) {
            $this->db->where('enquiry.state_id', $assign_state);
        } else if ($user_role == 7) {
            $this->db->where('enquiry.city_id', $assign_city);
        } elseif ($user_role == 8 || $user_role == 9) {
            $this->db->where('enquiry.aasign_to', $user_id);
           $this->db->or_where('enquiry.created_by', $user_id);
        }*/


        $this->db->order_by('enquiry.enquiry_id', 'desc');

        return $this->db->get();
    


    }

    public function all_Updated_today() {
        
        $user_id = $this->session->user_id;
        
        $user_role = $this->session->user_role;
        
        $assign_country = $this->session->country_id;

        $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product.product_name,tbl_product_country.country_name,tbl_product_country.id as country_id,tbl_datasource.datasource_name");
        
        $this->db->from('enquiry');
        
        $this->db->join('lead_source', 'lead_source.lsid = enquiry.enquiry_source', 'left');
        
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage', 'left');
        
        $this->db->join('lead_score', 'lead_score.sc_id = enquiry.lead_score', 'left');
        
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        
        $this->db->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');
        
        //$this->db->like('enquiry.lead_updated_date', date('Y-m-d'));

            $date=date('Y-m-d');
            
        



            $where = '';
            
            $where.=" enquiry.status=2";
            
            $where.=" AND enquiry.drop_status=0";
            

            //$where.=" enquiry.lead_stage!=Account";

            

            if($user_role==3){  
                $where.=" AND enquiry.country_id=$assign_country";
            }else if($user_role==4){
               $where.=" AND enquiry.region_id=$assign_region";

             }else if($user_role==5){
            $where.=" AND enquiry.territory_id=$assign_territory";
    
             }else if($user_role==6){
            $where.=" AND enquiry.state_id=$assign_state";
            
             }else if($user_role==7){
            $where.=" AND enquiry.city_id=$assign_city";
        
             }elseif($user_role==8||$user_role==9){
                $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";
              
              $process = $this->session->process;
              $where.= " AND enquiry.product_id=$process";
             }
           //  echo $where;

            $where.=" AND enquiry.status=2";

            $where.=" AND enquiry.update_date LIKE '%$date%'";

            $this->db->where($where);






        //$this->db->where('enquiry.lead_stage!=', 'Account');
        //$this->db->where('enquiry.lead_drop_status', 0);
        //$this->db->where('enquiry.status',2);        

        /*if ($user_role == 3) {
            $this->db->where('enquiry.country_id', $assign_country);
        } else if ($user_role == 4) {
            $this->db->where('enquiry.region_id', $assign_region);
        } else if ($user_role == 5) {
            $this->db->where('enquiry.territory_id', $assign_territory);
        } else if ($user_role == 6) {
            $this->db->where('enquiry.state_id', $assign_state);
        } else if ($user_role == 7) {
            $this->db->where('enquiry.city_id', $assign_city);
        } elseif ($user_role == 8 || $user_role == 9) {
            $this->db->where('enquiry.aasign_to', $user_id);
            $this->db->or_where('enquiry.created_by', $user_id);
        } else {
            
        }*/

        $this->db->order_by(' enquiry.enquiry_id', 'desc');
        
        return $this->db->get();

    }

    

    public function all_Active_lead_count($rowperpage, $rowno){
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        
       $user_id   = $this->session->user_id;
       $user_role = $this->session->user_role;
       $assign_country = $this->session->country_id;
       $assign_region = $this->session->region_id;
       $assign_territory = $this->session->territory_id;
       $assign_state = $this->session->state_id;
       $assign_city = $this->session->city_id;
       $cpny_id=$this->session->companey_id;
       
       $where='';
        $this->db->select("enquiry.lead_score,enquiry.lead_stage");
        $this->db->from('enquiry');            


        $where.="enquiry.is_delete=1";
        $where.=" AND enquiry.status=2";            

        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where.=" AND enquiry.comp_id=$cpny_id";        
        $this->db->where($where);

        $result =   $this->db->get();             
        return $result;
    }

    
     public function lead_by_stage($rowperpage, $rowno, $satge){
   
       $user_id   = $this->session->user_id;
       $user_role = $this->session->user_role;
       $assign_country = $this->session->country_id;
       $assign_region = $this->session->region_id;
       $assign_territory = $this->session->territory_id;
       $assign_state = $this->session->state_id;
       $assign_city = $this->session->city_id;
       $where='';
       

            $this->db->select("enquiry.name_prefix,enquiry.enquiry_id,enquiry.lead_stage,enquiry.lead_score,enquiry.company,enquiry.name,enquiry.lastname,enquiry.email,enquiry.phone,enquiry.address,enquiry.created_date,enquiry.enquiry_source,whatsapp_send_log.status as whatsapp_sent_status,whatsapp_send_log.mobile_no as whatsapp_sent_mobile_no,whatsapp_send_log.msg as whatsapp_msg,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_datasource.datasource_name,tbl_product.product_name as product_name,CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name ) as created_by_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name");

            $this->db->from('enquiry');
            $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage', 'left');
            $this->db->join('lead_score', 'lead_score.sc_id = enquiry.lead_score', 'left');
            $this->db->join('lead_source','enquiry.enquiry_source = lead_source.lsid','left');
            $this->db->join('tbl_product','enquiry.product_id = tbl_product.sb_id','left');
            
            $this->db->join('whatsapp_send_log','enquiry.phone = whatsapp_send_log.mobile_no','left');
            $this->db->join('tbl_datasource','enquiry.datasource_id = tbl_datasource.datasource_id','left');


            $this->db->join('tbl_admin as tbl_admin', 'tbl_admin.pk_i_admin_id = enquiry.created_by', 'left');
            $this->db->join('tbl_admin as tbl_admin2', 'tbl_admin2.pk_i_admin_id = enquiry.aasign_to', 'left');
        
            $where.="enquiry.is_delete=1";
            $where.=" AND enquiry.status=2";
            $where.=" AND enquiry.drop_status=0";


            $where.=" AND enquiry.lead_stage=$satge";
        
            if($user_role==3){  
                $where.=" AND enquiry.country_id=$assign_country";
             }else if($user_role==4){
               $where.=" AND enquiry.region_id=$assign_region";

             }else if($user_role==5){
            $where.=" AND enquiry.territory_id=$assign_territory";
    
             }else if($user_role==6){
            $where.=" AND enquiry.state_id=$assign_state";
            
             }else if($user_role==7){
            $where.=" AND enquiry.city_id=$assign_city";
        
             }elseif($user_role==8||$user_role==9){              
                $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";
                $process = $this->session->process;
                $where.= " AND enquiry.product_id=$process";
              
            }
            $this->db->where($where);
            $this->db->order_by('enquiry.enquiry_id','ASC');
            $this->db->limit($rowno,$rowperpage);  
            $result =   $this->db->get(); 
            return $result;

    }



    public function all_lead_toClient() {
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $assign_country = $this->session->country_id;
        $this->db->select("*");
        $this->db->from('allleads');
        $this->db->join('enquiry', 'enquiry.Enquery_id = allleads.lead_code', 'left');
        $this->db->join('lead_source', 'lead_source.lsid = enquiry.enquiry_source', 'left');
        $this->db->join('lead_stage', 'lead_stage.stg_id = allleads.lead_stage', 'left');
        $this->db->join('lead_score', 'lead_score.sc_id = allleads.lead_score', 'left');
        $this->db->where('allleads.lead_stage', 'Account');
       if ($user_role == 3) {
            $this->db->where('enquiry.country_id', $assign_country);
        } else if ($user_role == 4) {
            $this->db->where('enquiry.region_id', $assign_region);
        } else if ($user_role == 5) {
            $this->db->where('enquiry.territory_id', $assign_territory);
        } else if ($user_role == 6) {
            $this->db->where('enquiry.state_id', $assign_state);
        } else if ($user_role == 7) {
            $this->db->where('enquiry.city_id', $assign_city);
        } elseif ($user_role == 8 || $user_role == 9) {
            $this->db->where('enquiry.aasign_to', $user_id);
            $this->db->or_where('enquiry.created_by', $user_id);
        }
        $this->db->order_by('allleads.lid', 'desc');
        return $this->db->get();
    }

    public function all_drop_lead() {
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $assign_country = $this->session->country_id;
        $where='';
        $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product.product_name,tbl_product_country.country_name,tbl_product_country.id as country_id,tbl_datasource.datasource_name");
        
        $this->db->from('enquiry');
        $this->db->join('lead_source', 'lead_source.lsid = enquiry.enquiry_source', 'left');
        $this->db->join('lead_stage', 'lead_stage.stg_id = enquiry.lead_stage', 'left');
        $this->db->join('lead_score', 'lead_score.sc_id = enquiry.lead_score', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        $this->db->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');

       $where.="enquiry.is_delete=1";

            $where.=" AND enquiry.status=2";
            //$where.=" AND enquiry.lead_drop_status>0";
       
        if ($user_role == 3) {
            $where.=" AND enquiry.country_id=$assign_country";
        } else if ($user_role == 4) {
            $where.=" AND enquiry.region_id=$assign_region";
        } else if ($user_role == 5) {
           $where.=" AND enquiry.territory_id=$assign_territory";
        } else if ($user_role == 6) {
            $where.=" AND enquiry.state_id=$assign_state";
        } else if ($user_role == 7) {
            $where.=" AND enquiry.city_id=$assign_city";
        } elseif ($user_role == 8 || $user_role == 9) {
          $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";
           
          $process = $this->session->process;
          $where.= " AND enquiry.product_id=$process";
       }

        $where.=" AND enquiry.drop_status>0";
        $this->db->where($where);
        return $this->db->get();
    
    }

    public function scheduled() {
        return $this->db->select("*")
                        ->from('allleads')
                        ->join('query_response', 'query_response.query_id = allleads.lead_code')
                        ->where('allleads.is_delete', '1')
                        ->get();
    }

    public function unscheduled() {
        return $this->db->select("*")
                        ->from('allleads')
                        ->where('checked', 1)
                        ->where('is_delete', '1')
                        ->get();
    }

    //////////////////// LEad Stage ///////////////////////
    public function delete_stage($stage = null) {
        $this->db->where('stg_id', $stage)
                ->delete('lead_stage');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_stage_map($stage = null) {
        $this->db->where('id', $stage)
                ->delete('stage_visible');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_score($score = null) {
        $this->db->where('sc_id', $score)
                ->delete('lead_score');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_source($source = null) {
        $this->db->where('lsid', $source)
                ->delete('lead_source');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_dropReason($drop = null) {
        $this->db->where('d_id', $drop)->delete('tbl_drop');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_ctype($ctype = null) {
        $this->db->where('cid', $ctype)->delete('customer_type');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    public function comment_byId($lead_code) {
        $this->db->select('*,tbl_comment.created_date as ddate,tbl_admin.s_display_name as comment_created_by,tbl_admin.last_name as lastname,,tbl_user_role.user_role as role_name');
        $this->db->from('tbl_comment');
        $this->db->where('lead_id', $lead_code);
        $this->db->join('lead_description','lead_description.id=tbl_comment.stage_description','left');
        $this->db->join('lead_stage','lead_stage.stg_id=tbl_comment.stage_id','left');
        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_comment.created_by','left');
        $this->db->join('tbl_user_role','tbl_user_role.use_id=tbl_admin.user_permissions','left');
        $this->db->order_by("comm_id", "desc");
        return $this->db->get()->result();
    }

    public function comment_byId_applicant($lead_code) {
        $this->db->select('stage_id');
        $this->db->from('stage_visible');
        $this->db->where('visible_status', '1');
        $this->db->where("comp_id", $this->session->userdata('companey_id'));
        $stages = $this->db->get()->result();
        $all = array();
        foreach ($stages as $key => $value) {
             $all[] = $value->stage_id;
        }

        $this->db->select('*,stage_visible.lang_name as lead_stage_name,tbl_comment.created_date as ddate,tbl_admin.s_display_name as comment_created_by,tbl_admin.last_name as lastname,tbl_user_role.user_role as role_name');
        $this->db->from('tbl_comment');
        $this->db->where('lead_id', $lead_code);
        $this->db->where('tbl_comment.comment_msg', 'Stage Updated');
        $this->db->where_in("tbl_comment.stage_id",$all);
        $this->db->join('stage_visible','stage_visible.stage_id=tbl_comment.stage_id','left');
        $this->db->join('lead_description','lead_description.id=tbl_comment.stage_description','left');
        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=tbl_comment.created_by','left');
        $this->db->join('tbl_user_role','tbl_user_role.use_id=tbl_admin.user_permissions','left');
        $this->db->order_by("comm_id", "desc");
        return $this->db->get()->result();
    }

    public function comment_byId_limit($lead_code) {
        $this->db->select('*');
        $this->db->from('tbl_comment');
        $this->db->where('lead_id', $lead_code);
        $this->db->order_by("comm_id", "desc");
        $this->db->limit('1');
        return $this->db->get();
    }

    public function add_comment_for_events($conversation, $lead_id,$stage_code=0,$user_id=0,$stage_remark='') {
        
         if(!empty($this->session->userdata('userno'))){
            $ld_updt_by = $this->session->userdata('userno');
         }else{
            $ld_updt_by = $this->session->user_id;
        }
        if (empty($ld_updt_by)) {
            $ld_updt_by = $user_id;
        }
        $adt = date("Y-m-d H:i:s");        
        $this->db->set('lead_id', $lead_id);        
        $this->db->set('created_date', $adt);
        $this->db->set('comment_msg', $conversation);
        $this->db->set('stage_id', $stage_code);
        $this->db->set('created_by', $ld_updt_by);
        $this->db->set('remark', $stage_remark);
        $this->db->insert('tbl_comment');
        return $this->db->insert_id();
    }

    public function add_notifications_for_events($enq_id,$aasign_to_id,$conversation,$stage_remark) {
        
         
        $ld_updt_by = $this->session->user_id;

        $adt = date("d-m-Y"); 
        $atm = date("h:i:s");       
        $this->db->set('query_id', $enq_id);        
        $this->db->set('subject', $conversation);
        $this->db->set('task_remark', $stage_remark);
        $this->db->set('create_by', $ld_updt_by);
        $this->db->set('related_to', $aasign_to_id);
        $this->db->set('task_date', $adt);
        $this->db->set('task_time', $atm);
        $this->db->insert('query_response');
        return $this->db->insert_id();
    }

    // to insert drop status and reason
    public function add_comment_for_events1($conversation, $lead_id,$reason,$drop_status) {        
        $ld_updt_by = $this->session->user_id;        
        $adt = date("Y-m-d H:i:s");        
        $this->db->set('lead_id', $lead_id);
        $this->db->set('comp_id',$this->session->userdata('companey_id'));
        $this->db->set('created_date', $adt);
        $this->db->set('comment_msg', $conversation);
        $this->db->set('drop_status', $drop_status);
        $this->db->set('drop_reason', $reason);
        $this->db->set('created_by', $ld_updt_by);
        $this->db->insert('tbl_comment');
    }
    
   public function add_comment_for_events_stage($conversation, $lead_id,$stage_id,$stage_desc,$stage_remark='',$coment_type) {
        $ld_updt_by = $this->session->user_id;
        $adt = date("Y-m-d H:i:s");
        $this->db->set('comp_id',$this->session->userdata('companey_id'));
        $this->db->set('lead_id', $lead_id);
        $this->db->set('created_date', $adt);
        $this->db->set('comment_msg', $conversation);
        $this->db->set('created_by', $ld_updt_by);
        $this->db->set('coment_type', $coment_type);
        $this->db->set('stage_id', $stage_id);
        $this->db->set('stage_description', $stage_desc);
        $this->db->set('remark', $stage_remark);
        $this->db->insert('tbl_comment');
    }

    public function add_comment_for_events_stage_api($conversation, $lead_id,$stage_id,$stage_desc,$stage_remark,$user_id,$comment_type=0) {
        $ld_updt_by = $user_id;
        $adt = date("Y-m-d H:i:s");
        $this->db->set('lead_id', $lead_id);
        $this->db->set('created_date', $adt);
        $this->db->set('comment_msg', $conversation);
        $this->db->set('created_by', $ld_updt_by);
        $this->db->set('stage_id', $stage_id);
        $this->db->set('stage_description', $stage_desc);
        $this->db->set('remark', $stage_remark);
        $this->db->set('coment_type', $comment_type);
        return $this->db->insert('tbl_comment');
    }
    
    public function add_comment_for_events_popup($stage_remark,$stage_date,$contact_person,$mobileno,$email,$designation,$stage_time,$enq_code,$notification_id=0,$dis_subject='') {
        $ld_updt_by = $this->session->user_id;
        $adt = date("Y-m-d H:i:s");
        $this->db->set('query_id', $enq_code);
        $this->db->set('contact_person', $contact_person);
        $this->db->set('mobile', $mobileno);
        $this->db->set('email', $email);
        $this->db->set('designation', $designation);
        $this->db->set('create_by', $ld_updt_by);
        $this->db->set('task_date', $stage_date);
        $this->db->set('task_time', $stage_time);
        $this->db->set('task_remark', $stage_remark);
        $this->db->set('notification_id', $notification_id);
        $this->db->set('subject', $dis_subject);
        $this->db->insert('query_response');
    }
    public function add_comment_for_events_popup_api($stage_remark,$stage_date,$stage_time,$enq_code,$userno) {
        $ld_updt_by = $userno;
        
        $enqarr = $this->getuserinfo($userno);
   
        $adt = date("Y-m-d H:i:s");
        $this->db->set('query_id', $enq_code);
        $this->db->set('contact_person', $enqarr->cname);
        $this->db->set('mobile', $enqarr->enq_phone);
        $this->db->set('email', $enqarr->enq_email);
        
    //  $this->db->set('comp_id', $this->session->companey_id);
     //   $this->db->set('designation', $designation);
         $this->db->set('related_to', $enqarr->status);
      $this->db->set('updated_date', date("Y-m-d h:i:s"));
     
        $this->db->set('create_by', $ld_updt_by);
        $this->db->set('task_date', $stage_date);
        $this->db->set('task_time', $stage_time);
        $this->db->set('task_remark', $stage_remark);
        $this->db->set('conversation', $stage_remark);
        $this->db->insert('query_response');
        return $this->db->insert_id();

    }
    
    public function getuserinfo($userno =""){
        
        $this->db->select('usr.*, concat(enq.name_prefix," ",enq.name," ",enq.lastname) as cname,enq.email as enq_email,enq.phone as enq_phone,enq.status');
        $this->db-> from('tbl_admin as usr');
        $this->db->where('usr.pk_i_admin_id', $userno);
        $this->db->join('enquiry as enq', 'enq.created_by = usr.pk_i_admin_id', 'LEFT');
        return $this->db->get()->row();
        
        //return $this->db->row();
    }

    public function boq_list_byid($lead_code) {
        $this->db->where('emp_id', $lead_code);
        $this->db->order_by("baq_id", "desc");
        $this->db->limit('1');
        return $this->db->get('tbl_boq');
    }
    public function po_list_by_id($lead_code) {
        $this->db->where('customer_id', $lead_code);
        $this->db->order_by("po_id", "desc");
        $this->db->limit('1');
        return $this->db->get('tbl_po');
    }
    public function network_digram($lead_code) {
        $this->db->where('customer_id', $lead_code);
        $this->db->order_by("po_id", "desc");
        $this->db->limit('1');
        return $this->db->get('tbl_network');
    }
    public function user_by_id_($lead_code) {
        $this->db->where('pk_i_admin_id', $lead_code);
        $this->db->limit('1');
        return $this->db->get('tbl_admin');
    }
    /******************************************************alert popup code*****************************************************/
     /******************************************************start api*****************************************************/
     
    function find_stage_api($comp) {
        $this->db->select(" * ");
        $this->db->from('lead_stage');
        $this->db->where('comp_id',$comp);
        $query = $this->db->get();
        return $query->result();
    }
     public function select_des_by_stage_api($diesc) {
       $this->db->select("*");
        $this->db->from('lead_description');
        $this->db->where('lead_stage_id',$diesc);
        //$this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
     }

     public function select_branch_by_dept_id($dept) {
       $this->db->select("tbl_branch.id,tbl_branch.b_name");
        $this->db->from('tbl_branch_master');
        $this->db->join('tbl_branch', 'tbl_branch.id = tbl_branch_master.branch_name', 'LEFT');
        $this->db->where('tbl_branch_master.branch_dept',$dept);
        $this->db->group_by('tbl_branch_master.branch_name',$dept);
        $query = $this->db->get();
        return $query->result();
     }

     public function select_user_by_deptandbranch($dept='',$branch='') {
       $this->db->select("tbl_admin.pk_i_admin_id,tbl_admin.s_display_name,tbl_admin.last_name,tbl_admin.s_user_email");
        $this->db->from('tbl_admin');
        $this->db->where('sales_branch',$branch);
        $this->db->where('dept_name',$dept);
        $query = $this->db->get();
        return $query->result();
     }
   /******************************************************End api*****************************************************/
   
    public function all_csc($city) {

        $this->db->select("*");
        $this->db->from('city');
        $this->db->where('city.id',$city);
        $this->db->where('city.comp_id', $this->session->userdata('companey_id'));
        $this->db->join('state', 'state.id=city.state_id','left');
        $query = $this->db->get();
        return $query->row();
    }

    /***************************************************Department Master section start************************************/
    public function dept_add($data) {
        $this->db->insert('tbl_department', $data);
    }

    public function dept_select() {
        $this->db->select("*");
        $this->db->from('tbl_department');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_dept($dept_id = null) {
        $this->db->where('id', $dept_id)->delete('tbl_department');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    /***************************************************Department section End************************************/

    /***************************************************Branch Master section start************************************/
    public function branch_add($data) {
        $this->db->insert('tbl_branch', $data);
    }

    public function branch_select() {
        $this->db->select("*");
        $this->db->from('tbl_branch');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_brnh($branch_id = null) {
        $this->db->where('id', $branch_id)->delete('tbl_branch');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    /***************************************************Branch section End************************************/

    /***************************************************Branch Master Mapping section start************************************/
    public function branch_master_add($data) {
        $this->db->insert('tbl_branch_master', $data);
    }

    public function branch_master_select() {
        $this->db->select("*");
        $this->db->from('tbl_branch_master');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_branch($branch_id = null) {
        $this->db->where('id', $branch_id)->delete('tbl_branch_master');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    /***************************************************Branch Master Mpaaping section End************************************/

     /***************************************************url section start************************************/
    public function url_add($data) {
        $this->db->insert('tbl_url', $data);
    }

    public function url_select() {
        $this->db->select("*");
        $this->db->from('tbl_url');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_url($url_id = null) {
        $this->db->where('id', $url_id)->delete('tbl_url');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    /***************************************************url section end************************************/
   
     /***************************************************faq section start************************************/
    public function faq_add($data) {
        $this->db->insert('tbl_faq', $data);
    }
        
    public function faq_select() {
        $this->db->select("*");
        $this->db->from('tbl_faq');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
        
    public function logdata_select($uid='') {
        if(!empty($uid)){
           $user_id = $uid;
        }else{
           $user_id = $this->session->userdata('user_id');  
        }
           $this->db->select("*");
           $this->db->from('login_history');
           $this->db->where('uid', $user_id);
           $this->db->where('lgot_date_time!=', '');
           $query = $this->db->get();
           return $query->result();
    }

    public function last_login_time($uid='') {
        if(!empty($uid)){
           $user_id = $uid;
        }else{
           $user_id = $this->session->userdata('user_id');  
        }
           $this->db->select("lg_date_time");
           $this->db->from('login_history');
           $this->db->where('uid', $user_id);
           $this->db->order_by("id","desc");
           $this->db->limit('1');
           $query = $this->db->get();
           return $query->row();
    }

   

    public function delete_faq($faq_id = null) {
        $this->db->where('id', $faq_id)->delete('tbl_faq');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }   
    /**********************************faq section end*****************************************************/

/**************************************agreement template start*************************************************/
public function agreement_template_add($data) {
        $this->db->insert('tbl_agreement_template', $data);
    }
public function agreement_format_add($data) {
        $this->db->insert('tbl_client_agreement', $data);
    }
    
public function agrtemp_select() {
        $this->db->select("*");
        $this->db->from('tbl_agreement_template');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
    
    public function agrtemp_name() {
        $this->db->select("id,template_name");
        $this->db->from('tbl_agreement_template');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
    
    public function all_content($content) {
        $this->db->select("template_content");
        $this->db->from('tbl_agreement_template');
        $this->db->where('id',$content);
        $query = $this->db->get();
        return $query->row();
    }
/**************************************agreement template end*************************************************/
public function installment_add($data) {
        $this->db->insert('tbl_installment_master', $data);
    }
    
public function installment_select() {
        $this->db->select("*");
        $this->db->from('tbl_installment_master');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

public function gst_add($data) {
        $this->db->insert('tbl_gst', $data);
    }

public function gst_select() {
        $this->db->select("*");
        $this->db->from('tbl_gst');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

/**************************************Language test template end*************************************************/
public function test_language_add($data) {
        $this->db->insert('tbl_test_master', $data);
    }
    
public function test_language_select() {
        $this->db->select("*");
        $this->db->from('tbl_test_master');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
#--------------------------------------------------document tab -------------------------------------#
    /**************************************edu master end*************************************************/
public function edu_add($data) {
        $this->db->insert('tbl_edu_master', $data);
    }
    
public function edu_select() {
        $this->db->select("*");
        $this->db->from('tbl_edu_master');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
#--------------------------------------------------edu Master end-------------------------------------#
    /**************************************visa_type end*************************************************/
public function visa_type_add($data) {
        $this->db->insert('tbl_visa_type', $data);
    }

public function visa_mapping_add($data) {
        $this->db->insert('tbl_visa_mapping', $data);
    }
    
public function visa_type_select() {
        $this->db->select("*");
        $this->db->from('tbl_visa_type');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }

public function visa_mapping_select() {
        $this->db->select("*");
        $this->db->from('tbl_visa_mapping');
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
#--------------------------------------------------visa_type Master-------------------------------------#
public function get_postdoc_list($cntry_id=0){
    $this->db->select("*");
    $this->db->from('tbl_doc_vs_country');
    $this->db->where('country_id',$cntry_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }
public function all_filetype($diesc) {

        $this->db->select("*");
        $this->db->from('tbl_doc_vs_file');
        $this->db->where('stream',$diesc);
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
public function all_filestream($diesc) {

        $this->db->select("*");
        $this->db->from('tbl_doc_vs_sream');
        $this->db->where('document_type',$diesc);
        $this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
public function document_all_add($data) {
        $this->db->insert('tbl_documents', $data);
    }

public function document_add_checklist($data) {
        $this->db->insert('tbl_doccheklist', $data);
    }
public function get_tabdoc_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_documents');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }
#------------------------------------------document tab end---------------------------------------# 
#--------------------------------------------------Family tab start-------------------------------------#
 public function family_all_add($data) {
        $this->db->insert('tbl_family', $data);
    } 
    
public function get_family_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_family');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }
#--------------------------------------------------Family tab end-------------------------------------#

#--------------------------------------------------Qualification tab start-------------------------------------#
public function get_test_list(){
    $this->db->select("*");
    $this->db->from('tbl_test_master');
    $this->db->where('comp_id',$this->session->userdata('companey_id'));
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }

   public function get_member_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_family');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   } 

   public function qualification_all_add($data) {
        $this->db->insert('tbl_qualification', $data);
    }

    public function get_qualification_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_qualification');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }
#--------------------------------------------------Qualification tab end-------------------------------------#
#--------------------------------------------------education tab end-------------------------------------#
   public function get_education_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_education');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }
   public function get_edumaster_list(){
    $this->db->select("*");
    $this->db->from('tbl_edu_master');
    $this->db->where('comp_id',$this->session->userdata('companey_id'));
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }
   public function education_all_add($data) {
        $this->db->insert('tbl_education', $data);
    }
#--------------------------------------------------education tab end-------------------------------------#
#--------------------------------------------------Job tab start-------------------------------------#
   public function job_all_add($data) {
        $this->db->insert('tbl_job_details', $data);
    }

    public function get_job_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_job_details');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }

#--------------------------------------------------Job tab End-------------------------------------#
   #--------------------------------------------------ticket tab start-------------------------------------#
    public function ticket_all_add($data) {
        $this->db->insert('tbl_ticket_tab', $data);
    }

    public function refund_req_sent($data) {
        $this->db->insert('tbl_refund_form_request', $data);
    }

    public function get_ref_req($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_refund_form_request');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->where('status',0);
    $this->db->order_by('id','desc');
    return $this->db->get()->row();
   }

    public function get_tick_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_ticket_tab');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }

#--------------------------------------------------ticket tab End-------------------------------------#

   #--------------------------------------------------Exp tab start-------------------------------------#
   public function exp_all_add($data) {
        $this->db->insert('tbl_experience_details', $data);
    }

    public function get_exp_list($enq_id=0){
    $this->db->select("*");
    $this->db->from('tbl_experience_details');
    $this->db->where('enquiry_id',$enq_id);
    $this->db->order_by('id','asc');
    return $this->db->get()->result();
   }

#--------------------------------------------------Exp tab End-------------------------------------#
#--------------------------------------------------Refund tab start-------------------------------------#
   public function refund_all_add($data) {
        $this->db->insert('tbl_refund', $data);
    }
#--------------------------------------------------Refund tab End-------------------------------------#

}