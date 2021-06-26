<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model {

    private $table = "tbl_country";

    public  function get_city_by_state_id($state_id) {

        //$state_id = $this->input->post('state_id');

        return $data['country'] = $this->location_model->get_city_byid($state_id);

        /*echo '<option value="" style="display:none">---Select City---</option>';

        foreach ($data['country'] as $r) {

             echo '<option value="' . $r->id . '">' . $r->city . '</option>';

        }*/

    }

    public function all_city_bystate($id){

        $this->db->select('*');
        $this->db->from('city');
        $this->db->where('state_id',$id);
        return $this->db->get()->result();

    }

    public function get_state($country) {

        return $this->db->select("*")
                        ->from("state")
                        ->where('state.country_id', $country)
                        ->where('comp_id',$this->session->userdata('companey_id'))
                        ->get()
                        ->result();
    }
	
	public function block_list(){
		
		
		return array();
	}
	

    public function all_city_state($state_id) {

        return $this->db->select('*')->from('city')->where('state_id', $state_id)->where('comp_id',$this->session->userdata('companey_id'))->get()->result();
    }

     public function productcountry() {
        $company=$this->session->userdata('companey_id');
        return $this->db->select("tbl_product_country.*,tbl_typeofproduct.name as typroname,tbl_brand.name as brandname")
                        ->from("tbl_product_country")
                        ->join('tbl_typeofproduct','tbl_typeofproduct.id=tbl_product_country.typeofpro','left')
                        ->join('tbl_brand','tbl_brand.id=tbl_product_country.brand','left')
                        ->where('tbl_product_country.comp_id', $company)
                        ->get()
                        ->result();
    }

   public function get_company_list($process_id = "",$tid=0){
    // print_r($process_id);
    $compid=$this->session->companey_id;
    if(is_array($process_id)){
        $id = implode(",", $process_id);
    }
    else{
        $id = $process_id;
    }
    $where = " FIND_IN_SET('".$id."',process_id) AND company_id = {$compid} AND status=1";
    if ($tid) {
        $where .= " AND form_id=$tid"; 
    }
    $this->db->select("tbl_input.*,input_types.title as type");
    $this->db->from('tbl_input');
    $this->db->join('input_types','tbl_input.input_type=input_types.id','LEFT');
    $this->db->where($where);
    $this->db->order_by('tbl_input.fld_order','asc');
    return $this->db->get()->result_array();
   }  


    public function get_company_list1($process_id = ""){ // get basic field by process
        // print_r($process_id);
        $compid=$this->session->companey_id;
        $this->db->select("enquiry_fileds_basic.*,basic_fields.title,input_types.title as type");
        $this->db->from('enquiry_fileds_basic');
        $this->db->where('basic_fields.field_for','0');
        $where = '';
        if($process_id){
            $where .= " FIND_IN_SET($process_id,process_id) AND";
        }
        $where .= "  comp_id = {$compid} AND status=1";
        // $this->db->where('FIND_IN_SET(process_id)',$process_id);
        // $this->db->where('comp_id',);
        $this->db->where($where);
        $this->db->join('basic_fields','enquiry_fileds_basic.field_id=basic_fields.id','LEFT');
        $this->db->join('input_types','basic_fields.type=input_types.id','LEFT');
        $this->db->order_by('enquiry_fileds_basic.fld_order','ASC');

        return $this->db->get()->result_array();
   }

    public function get_company_list1_ticket($process_id = ""){ // get basic field by process
        // print_r($process_id);
        
        $compid=$this->session->companey_id;

        $this->db->select("ticket_fileds_basic.*,basic_fields.title,input_types.title as type");
        $this->db->from('ticket_fileds_basic');
        $this->db->where('basic_fields.field_for','2');
        $where = '';
        if($process_id){
            $where .= " FIND_IN_SET($process_id,process_id) AND";
        }
        $where .= "  comp_id = {$compid} AND status=1";
        // $this->db->where('FIND_IN_SET(process_id)',$process_id);
        // $this->db->where('comp_id',);
        $this->db->where($where);
        $this->db->join('basic_fields','ticket_fileds_basic.field_id=basic_fields.id','LEFT');
        $this->db->join('input_types','basic_fields.type=input_types.id','LEFT');
    
        $this->db->order_by('ticket_fileds_basic.fld_order','ASC');

        return $this->db->get()->result_array();
   }


   public function get_company_list_api($proccessno, $compno = ""){	
         
		$qry = "SELECT * FROM tbl_input WHERE status=1 AND company_id={$compno} AND FIND_IN_SET($proccessno,process_id) ORDER BY input_id ASC";
	
		$resarr = $this->db->query($qry)->result_array();
		
		if(!empty($resarr)){
			foreach($resarr as $ind => $res){
				
				$processarr =  explode(",", $res['process_id']);
				
				if(in_array($proccessno, $processarr)){
					
					
				}else{
					unset($resarr[$ind]);
				}
				
			}
		}
		return $resarr;
   }

    public function create($data = []) {
        return $this->db->insert($this->table, $data);
    }

    public function create_region($data = []) {
        return $this->db->insert('tbl_region', $data);
    }

    public function create_tretory($data = []) {
        return $this->db->insert('tbl_territory', $data);
    }

    public function create_state($data = []) {
        return $this->db->insert('state', $data);
    }

    public function create_city($data = []) {
        return $this->db->insert('city', $data);
    }

    public function country() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("tbl_country")
						->where('comp_id', $company)
                        ->get()
                        ->result();
    }

public function contact($lead_code) {
        return $this->db->select("*")
                        ->from('tbl_client_contacts')
                        ->where('client_id', $lead_code)
                        ->get()
                        ->result();
    }
    
     public function get_city_by_name($name) {
        return $this->db->select("*,city.id as cid")
                        ->from("city")
                        ->join('tbl_country', 'tbl_country.id_c = city.country_id')
                        ->join('tbl_region', 'tbl_region.region_id = city.region_id')
                        ->join('tbl_territory', 'tbl_territory.territory_id = city.territory_id')
                        ->join('state', 'state.id = city.state_id')
						->where('city.city',$name)
                        ->get()
                        ->row();
    }
 
    public function region_list() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("tbl_region")
                        ->join('tbl_country', 'tbl_country.id_c = tbl_region.country_id ')
						->where('tbl_region.comp_id',$company)
                        ->get()
                        ->result();
    }

    public function get_region_byid($id) {
        return $this->db->select("*")
                        ->from("tbl_region")
                        ->join('tbl_country', 'tbl_country.id_c = tbl_region.country_id ')
                        ->where('tbl_country.id_c', $id)
                        ->where('tbl_region.comp_id',$this->session->userdata('companey_id'))
                        ->get()
                        ->result();
    }

    public function get_region_byname($id, $id1) {
        return $this->db->select("*")
                        ->from("tbl_region")
                        ->where('country_id', $id1)
                        ->where('region_name', $id)
                        ->get()
                        ->result();
    }

    public function get_tretory_byid($id, $id2) {
        return $this->db->select("*")
                        ->from("tbl_territory")
                        ->join('tbl_country', 'tbl_country.id_c = tbl_territory.country_id')
                        ->join('tbl_region', 'tbl_region.region_id = tbl_territory.region_id')
                        ->where('tbl_country.id_c', $id)
                        ->where('tbl_region.region_id', $id2)
                        ->get()
                        ->result();
    }

    public function get_territory_byname($id1, $id2, $id3) {
        return $this->db->select("*")
                        ->from("tbl_territory")
                        ->where('territory_name', $id1)
                        ->where('region_id', $id2)
                        ->where('country_id', $id3)
                        ->get()
                        ->result();
    }

    public function get_state_byid($id, $id2) {
        return $this->db->select("*")
                        ->from("state")
                        ->where('country_id', $id)
                        ->where('region_id', $id2)
                        ->get()
                        ->result();
    }

    public function get_state_byname($id2, $id3, $id4) {
        return $this->db->select("*")
                        ->from("state")
                        ->where('region_id', $id2)
                        ->where('country_id', $id3)
                        ->where('state', $id4)
                        ->get()
                        ->result();
    }

    public function get_city_byid($id5) {
        $this->db->select("*");
        $this->db->from("city");
        if($id5){
            $this->db->where('state_id', $id5);
        }
        return $this->db->get()->result();
    }

    public function get_city_byname($id1, $id2, $id3, $id4, $id5) {
        return $this->db->select("*")
                        ->from("city")
                        ->where('territory_id', $id1)
                        ->where('region_id', $id2)
                        ->where('country_id', $id3)
                        ->where('state_id', $id4)
                        ->where('city', $id5)
                        ->get()
                        ->result();
    }

    public function territory_lsit() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("tbl_territory.territory_id,tbl_territory.comp_id,tbl_territory.country_id,tbl_territory.region_id,tbl_territory.status as tstatus,tbl_territory.territory_name,tbl_country.*,tbl_region.*")
                        ->from("tbl_territory")
                        ->join('tbl_country', 'tbl_country.id_c = tbl_territory.country_id')
                        ->join('tbl_region', 'tbl_region.region_id = tbl_territory.region_id')
						->where('tbl_territory.comp_id',$company)
                        ->get()
                        ->result();
    }

    public function state_list() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*,state.status as state_status")
                        ->from("state")
                        ->join('tbl_country', 'tbl_country.id_c = state.country_id')
                        ->join('tbl_region', 'tbl_region.region_id = state.region_id')
                        //->join('tbl_territory','tbl_territory.territory_id = state.territory_id')
						->where('state.comp_id', $company)
                        ->get()
                        ->result();
    }
    
    public function estate_list() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("state")
						->where('state.comp_id', $company)
                        ->get()
                        ->result();
    }

      public function estate_list_api($compno='') {
          if(!empty($compno)){
              $company=$compno;
          }else{
              $company=$this->session->userdata('companey_id');
          }
        return $this->db->select("*")
                        ->from("state")
                        ->where('state.comp_id', $company)
                        ->get()
                        ->result();
    }

     public function ecity_list() {
		 $company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("city")
						->where('city.comp_id', $company)
                        ->order_by('city','asc')
                        ->get()
                        ->result();
    }

       public function ecity_list_api($compno) {
         $company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("city")
                        ->where('city.comp_id', $compno)
                        ->get()
                        ->result();
    }


     public function ecountry_list() {
        return $this->db->select("*")
                        ->from("tbl_country")
                        ->get()
                        ->result();
    }

    public function city_list() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("city.city,city.status,city.id,city.state_id,state.state,tbl_country.country_name,tbl_region.region_name,tbl_region.region_id,tbl_territory.territory_name,tbl_territory.territory_id")
                        ->from("city")
                        ->join('tbl_country', 'tbl_country.id_c = city.country_id')
                        ->join('tbl_region', 'tbl_region.region_id = city.region_id')
                        ->join('tbl_territory', 'tbl_territory.territory_id = city.territory_id')
                        ->join('state', 'state.id = city.state_id')
						->where('city.comp_id',$company)
                        ->get()
                        ->result();
    }

    public function read_by_id($user_id = null) {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from($this->table)
                        ->where('id_c', $user_id)
						->where('comp_id', $company)
                        ->get()
                        ->row();
    }

    public function read_by_region($user_id = null) {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from('tbl_region')
                        ->where('region_id', $user_id)
						->where('comp_id', $company)
                        ->get()
                        ->row();
    }

    public function read_by_territory($user_id = null) {
        return $this->db->select("*")
                        ->from('tbl_territory')
                        ->where('territory_id', $user_id)
                        ->get()
                        ->row();
    }

    public function read_by_state($user_id = null) {
        return $this->db->select("*")
                        ->from('state')
                        ->where('id', $user_id)
                        ->get()
                        ->row();
    }

    public function read_by_city($user_id = null) {
        return $this->db->select("*")
                        ->from('city')
                        ->where('id', $user_id)
                        ->get()
                        ->row();
    }

    public function update($data = []) {
        return $this->db->where('id_c', $data['id_c'])
                        ->update($this->table, $data);
    }

    public function update_region($data = []) {

        return $this->db->where('region_id', $data['region_id'])
                        ->update('tbl_region', $data);
    }

    public function update_tretory($data = []) {

        return $this->db->where('territory_id', $data['territory_id'])
                        ->update('tbl_territory', $data);
    }

    public function update_state($data = []) {

        return $this->db->where('id', $data['id'])
                        ->update('state', $data);
    }

    public function update_city($data = []) {

        return $this->db->where('id', $data['id'])
                        ->update('city', $data);
    }

    public function delete($user_id = null) {
        $this->db->where('id_c', $user_id)->delete($this->table);

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_region($user_id = null) {
        $this->db->where('region_id', $user_id)->delete('tbl_region');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_territory($user_id = null) {
        $this->db->where('territory_id', $user_id)->delete('tbl_territory');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_state($user_id = null) {
        $this->db->where('id', $user_id)->delete('state');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_city($user_id = null) {
        $this->db->where('id', $user_id)->delete('city');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    //Get states...
    public function all_states() {

        return $this->db->select('*')->from('state')->get()->result();
    }

    public function all_statess($region) {

        return $this->db->select('*')->from('state')->where('region_id', $region)->where('comp_id',$this->session->userdata('companey_id'))->get()->result();
    }

    //Get Territory by state
    public function all_territory($state_id) {

        return $this->db->select('*')->from('tbl_territory')->where('state_id', $state_id)->where('comp_id',$this->session->userdata('companey_id'))->get()->result();
    }

    //Get city by Territory..
    public function all_city($territory_id) {

        return $this->db->select('*')->from('city')->where('territory_id', $territory_id)->where('comp_id',$this->session->userdata('companey_id'))->get()->result();
    }

    public function doctor_list() {
        $result = $this->db->select("*")
                ->from($this->table)
                ->where('user_role', 2)
                ->where('status', 1)
                ->get()
                ->result();

        $list[''] = display('select_doctor');
        if (!empty($result)) {
            foreach ($result as $value) {
                $list[$value->user_id] = $value->firstname . ' ' . $value->lastname;
            }
            return $list;
        } else {
            return false;
        }
    }

    //Get region based on country..
    public function get_region($country) {

        return $this->db->select("*")
                        ->from("tbl_region")
                        ->join('tbl_country', 'tbl_country.id_c = tbl_region.country_id ')
                        ->where('tbl_region.country_id', $country)
                        ->where('comp_id',$this->session->userdata('companey_id'))
                        ->get()
                        ->result();
    }

    //Get territory base on region..
    public function get_find_territory($region_id) {

        return $this->db->select('*')
                        ->from('tbl_territory')
                        ->where('region_id', $region_id)
                        ->get()
                        ->result();
    }

    // Find sate based on territory..
    public function Find_state($territory_id) {

        return $this->db->select('*')
                        ->from('tbl_territory')
                        ->join('state', 'state.id=tbl_territory.state_id')
                        ->where('tbl_territory.territory_id', $territory_id)
                        ->get()
                        ->result();
    }

    


public function productcountry_api($compno) {
        $company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("tbl_product_country")
                        ->where('comp_id', $compno)
                        ->get()
                        ->result();
    }

    public function products() {
        $company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("tbl_product_country")
                        ->where('comp_id', $company)
                        ->get()
                        ->result_array();
    }

    public function addproductcountry($data = []) {
        return $this->db->insert("tbl_product_country", $data);
    }

    public function readProductCountry($param_id = null) {
        return $this->db->select("*")
                        ->from('tbl_product_country')
                        ->where('id', $param_id)
                        ->get()
                        ->row();
    }

    public function updateProductCountry($data = []) {

        return $this->db->where('id', $data['id'])
                        ->update('tbl_product_country', $data);
    }

    public function deleteproductcountry($paramid = null) {
        $this->db->where('id', $paramid)->delete('tbl_product_country');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
	
	/***---------------------start api--------------------------***/
	  public function state_list_api() {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("state")
                        ->join('tbl_country', 'tbl_country.id_c = state.country_id')
                        ->join('tbl_region', 'tbl_region.region_id = state.region_id')
                        //->join('tbl_territory','tbl_territory.territory_id = state.territory_id')
						->where('state.comp_id', $this->input->post('company_id'))
                        ->get()
                        ->result();
    }
/***---------------------start api--------------------------***/
/*************************************payment list *******************************/
public function get_ins_list($process_id=0){
    $this->db->select("*");
    $this->db->from('tbl_payment');
    //$this->db->where('cmp_no',$comp_id);
    $this->db->where('enq_id',$process_id);
    $this->db->order_by('tbl_payment.id asc');
    return $this->db->get()->result();
   }
   /*************************************payment list End*******************************/
   /*************************************aggriment list *******************************/
   public function get_agg_list($process_id=0,$deal_id=0){
    $this->db->select("tbl_aggriment.*,commercial_info.quatation_number");
    $this->db->from('tbl_aggriment');
	$this->db->join('commercial_info', 'commercial_info.id = tbl_aggriment.deal_id','left');
    $this->db->where('commercial_info.stage_id',$deal_id);
    $this->db->where('tbl_aggriment.enq_id',$process_id);
    $this->db->order_by('tbl_aggriment.id asc');
    return $this->db->get()->result();
   }
	 public function get_same($smae_id)
    {
        return $this->db->select('*')
                        ->from('enquiry')
                        ->where('enquiry_id',$smae_id)
                        ->get()
                        ->row();
    }
 /*****************************aggriment list end ************************************/
 
 public function get_same_data($smae_id)
    {
        return $this->db->select('*')
                        ->from('enquiry')
                        ->where('phone',$smae_id)
                        ->get()
                        ->row();
    }
 
 /***************************************************student dash*******************************************/
public function stu_ins_list()
    {
        $this->db->select("*");
        $this->db->from('tbl_institute');
		$this->db->where('comp_id', $this->session->userdata('companey_id'));
        $this->db->order_by('institute_id','asc');
        return $query = $this->db->get()->result();
    }
	
public function stu_crs_list()
    {
        $this->db->select("*");
        $this->db->from('tbl_course');
		$this->db->where('comp_id', $this->session->userdata('companey_id'));
        $this->db->order_by('crs_id','asc');
        return $query = $this->db->get()->result();
    }

public function get_wislist($user_id)
    {
        $this->db->select("*,tbl_wishlist.id as wid,tbl_crsmaster.course_name as course_name_str");
        $this->db->from('tbl_wishlist');
        $this->db->join('tbl_crsmaster', 'tbl_crsmaster.id = tbl_wishlist.crs_id');
		$this->db->join('tbl_course', 'tbl_course.crs_id = tbl_wishlist.crs_id');
		$this->db->join('tbl_institute', 'tbl_institute.institute_id = tbl_wishlist.uni_id');
		$this->db->where('tbl_wishlist.comp_id', $this->session->userdata('companey_id'));
		$this->db->where('tbl_wishlist.stu_id', $user_id);
        $this->db->order_by('tbl_wishlist.id','asc');
        return $query = $this->db->get()->result();
    }
	
public function get_history($user_id)
    {
        $this->db->select("*");
        $this->db->from('tbl_apnt');
		$this->db->join('tbl_course', 'tbl_course.crs_id = tbl_apnt.crs');
		$this->db->join('tbl_institute', 'tbl_institute.institute_id = tbl_apnt.uni');
		$this->db->where('tbl_apnt.patid', $user_id);
        $this->db->order_by('tbl_apnt.id','asc');
        return $query = $this->db->get()->result();
    }
	
	public function input_list() {

        return $this->db->select("input_id,input_label,input_type,form_id")
                        ->from("tbl_input")
                        ->where('company_id',$this->session->userdata('companey_id'))
                        ->get()
                        ->result();
    }
	
	public function dynamic_list($enq_id) {

        return $this->db->select("input,fvalue")
                        ->from("extra_enquery")
                        ->where('cmp_no',$this->session->userdata('companey_id'))
						->where('enq_no',$enq_id)
                        ->get()
                        ->result();
    }
	
	public function institute_data($enq_id) {

        return $this->db->select("*,tbl_crsmaster.course_name as course_name_str")
                        ->from("institute_data")
                        ->where('enquery_code',$enq_id)
						->join('tbl_crsmaster','tbl_crsmaster.id=institute_data.course_id','left')
                        ->get()
                        ->result();
    }
	
	public function get_qualification_tab($enq_id)
    {
        $this->db->select("*");
        $this->db->from('tbl_input');
		$this->db->join('extra_enquery', 'extra_enquery.input = tbl_input.input_id');
		$this->db->where('extra_enquery.enq_no', $enq_id);
		//$this->db->where('tbl_input.form_id', 2);
		$this->db->where('tbl_input.company_id',$this->session->userdata('companey_id'));
        $this->db->order_by('extra_enquery.id','asc');
        return $query = $this->db->get()->result();
    }
/*****************************************************student dash end************************************/

public function find_discipline() {

        return $this->db->select("*")
                        ->from("tbl_discipline")
                        ->where('comp_id',$this->session->userdata('companey_id'))
                        ->get()
                        ->result();
    }
	
	public function find_level() {

        return $this->db->select("*")
                        ->from("tbl_levels")
                        ->where('comp_id',$this->session->companey_id)
                        ->get()
                        ->result();
    }
	
	public function find_length() {

        return $this->db->select("*")
                        ->from("tbl_length")
                        ->where('comp_id',$this->session->companey_id)
                        ->get()
                        ->result();
    }
	
	public function all_length($diesc) {
        $this->db->select("*");
        $this->db->from('tbl_length');
		$this->db->where('level_id',$diesc);
		$this->db->where('comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
	
	public function all_state($state) {

        $this->db->select("*");
        $this->db->from('state');
		$this->db->where('state.country_id',$state);
		$this->db->where('state.comp_id', $this->session->userdata('companey_id'));
        $query = $this->db->get();
        return $query->result();
    }
	
	 public function all_institute($ctnry,$sta,$lvl,$lgth,$disc) {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*")
                        ->from("tbl_institute")
                        ->join('tbl_course', 'tbl_course.institute_id = tbl_institute.institute_id','left')
						->where('tbl_course.length_id', $lgth)
						->where('tbl_course.level_id', $lvl)
						->where('tbl_course.discipline_id', $disc)
						->where('tbl_institute.country_id', $ctnry)
						->where('tbl_institute.state_id', $sta)
						->where('tbl_institute.comp_id', $company)
                        ->get()
                        ->result();
    }
	
	public function all_course($ctnry,$sta,$lvl,$lgth,$disc,$ins) {
		$company=$this->session->userdata('companey_id');
        return $this->db->select("*,tbl_crsmaster.course_name as cname")
                        ->from("tbl_course")
                        ->join('tbl_institute', 'tbl_institute.institute_id = tbl_course.institute_id','left')
						->join('tbl_crsmaster', 'tbl_crsmaster.id = tbl_course.course_name','left')
						->where('tbl_course.length_id', $lgth)
						->where('tbl_course.level_id', $lvl)
						->where('tbl_course.discipline_id', $disc)
						->where('tbl_institute.country_id', $ctnry)
						->where('tbl_institute.state_id', $sta)
						->where('tbl_course.institute_id', $ins)
						->where('tbl_institute.comp_id', $company)
                        ->get()
                        ->result();
    }
	
	public function ins_details($inid) {

        return $this->db->select("institute_id,profile_image,institute_name,ins_desc")
                        ->from("tbl_institute")
                        ->where('institute_id',$inid)
                        ->get()
                        ->result();
    }
	
	public function crs_details($crs) {

        return $this->db->select("tbl_course.course_ielts,tbl_levels.level,tbl_discipline.discipline,tbl_length.length,tbl_crsmaster.course_name")
                        ->from("tbl_course")
						->join('tbl_levels', 'tbl_levels.id = tbl_course.level_id','left')
						->join('tbl_discipline', 'tbl_discipline.id = tbl_course.discipline_id','left')
						->join('tbl_length', 'tbl_length.id = tbl_course.length_id','left')
						->join('tbl_crsmaster', 'tbl_crsmaster.id = tbl_course.course_name','left')
                        ->where('tbl_course.crs_id',$crs)
                        ->get()
                        ->result();
    }

    public function get_city_name_by_id($cid){
        $this->db->select('city');
        $this->db->where('id',$cid);
        return $this->db->get('city')->row_array();
    }
    
    public function get_state_name_by_id($sid){
        $this->db->select('state');
        $this->db->where('id',$sid);
        return $this->db->get('state')->row_array();
    }

}
