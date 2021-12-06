<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Enquiry_datatable_model extends CI_Model {
 
    var $table = 'enquiry';

    var $column_order = array('','enquiry.enquiry_id','enquiry.enquiry_source','','enquiry.company','enquiry.name','enquiry.email','enquiry.phone','enquiry.address','','','enquiry.created_date','enquiry.created_by','create_dept.dept_name','enquiry.aasign_to','assign_dept.dept_name','tbl_datasource.datasource_name','tbl_product.product_name','enquiry.Enquery_id','enquiry.score','enquiry.enquiry'); //set column field database for datatable orderable

    var $column_search = array('enquiry.name_prefix','enquiry.enquiry_id','tbl_company.company_name','enquiry.org_name','enquiry.name','enquiry.lastname','enquiry.email','enquiry.phone','enquiry.address','enquiry.created_date','enquiry.enquiry_source','lead_source.icon_url','lead_source.lsid','lead_source.score_count','lead_source.lead_name','tbl_datasource.datasource_name','tbl_product.product_name',"CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name )","create_dept.dept_name","CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name)","assign_dept.dept_name"); //set column field database for datatable searchable 

    var $order = array(); // default order 
 
    public function __construct()
    {
        parent::__construct();    
        $this->load->model('common_model');
        
        if(!empty($_POST['data_type']) && $_POST['data_type']=='1')
        {
            $this->order = array('enquiry.enquiry_id' => 'desc'); // default order 
        }
        else if(!empty($_POST['data_type']) && $_POST['data_type']=='2')
        {
            $this->order = array('enquiry.lead_created_date' => 'desc'); 
        }
        else if(!empty($_POST['data_type']) && $_POST['data_type']=='3')
        {
           $this->order = array('enquiry.client_created_date' => 'desc'); 
        }
        else
        {
           $this->order = array('enquiry.enquiry_id' => 'desc');
        }

    }
    

    private function _get_datatables_query(){    
       $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
       $this->db->from($this->table);       
       
       $user_id   = $this->session->user_id;
    
        $where='';
        $enquiry_filters_sess   =   $this->session->enquiry_filters_sess;
        $top_filter             =   !empty($enquiry_filters_sess['top_filter'])?$enquiry_filters_sess['top_filter']:'';        
        $from_created           =   !empty($enquiry_filters_sess['from_created'])?$enquiry_filters_sess['from_created']:'';       
        $to_created             =   !empty($enquiry_filters_sess['to_created'])?$enquiry_filters_sess['to_created']:'';
        $source                 =   !empty($enquiry_filters_sess['source'])?$enquiry_filters_sess['source']:'';
        $sub_source             =   !empty($enquiry_filters_sess['subsource'])?$enquiry_filters_sess['subsource']:'';
        $email                  =   !empty($enquiry_filters_sess['email'])?$enquiry_filters_sess['email']:'';
        $employee               =   !empty($enquiry_filters_sess['employee'])?$enquiry_filters_sess['employee']:''; 
        $datasource             =   !empty($enquiry_filters_sess['datasource'])?$enquiry_filters_sess['datasource']:'';
        $company                =   !empty($enquiry_filters_sess['company'])?$enquiry_filters_sess['company']:'';
		$clientname             =   !empty($enquiry_filters_sess['clientname'])?$enquiry_filters_sess['clientname']:'';
        $enq_product            =   !empty($enquiry_filters_sess['enq_product'])?$enquiry_filters_sess['enq_product']:'';
        $phone                  =   !empty($enquiry_filters_sess['phone'])?$enquiry_filters_sess['phone']:'';
        $createdby              =   !empty($enquiry_filters_sess['createdby'])?$enquiry_filters_sess['createdby']:'';
		$createdbydept              =   !empty($enquiry_filters_sess['createdbydept'])?$enquiry_filters_sess['createdbydept']:'';
        $assign                 =   !empty($enquiry_filters_sess['assign'])?$enquiry_filters_sess['assign']:'';
		$assigntodept                 =   !empty($enquiry_filters_sess['assigntodept'])?$enquiry_filters_sess['assigntodept']:'';
        $address                =   !empty($enquiry_filters_sess['address'])?$enquiry_filters_sess['address']:'';
        $product_filter         =   !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
        $assign_filter          =   !empty($enquiry_filters_sess['assign_filter'])?$enquiry_filters_sess['assign_filter']:'';
        $stage                  =   !empty($enquiry_filters_sess['stage'])?$enquiry_filters_sess['stage']:'';
         $productcntry          =   !empty($enquiry_filters_sess['prodcntry'])?$enquiry_filters_sess['prodcntry']:'';
        $state                  =   !empty($enquiry_filters_sess['state'])?$enquiry_filters_sess['state']:'';
        $city                   =   !empty($enquiry_filters_sess['city'])?$enquiry_filters_sess['city']:'';
        
        $filter_user_id         =   !empty($enquiry_filters_sess['user_id'])?$enquiry_filters_sess['user_id']:'';
        
        $tags                   =   !empty($enquiry_filters_sess['tag'])?$enquiry_filters_sess['tag']:'';

        $probability            =   !empty($enquiry_filters_sess['probability'])?$enquiry_filters_sess['probability']:'';
        $aging_rule             =   !empty($enquiry_filters_sess['aging_rule'])?$enquiry_filters_sess['aging_rule']:'';
       
        $visit_wise             =   !empty($enquiry_filters_sess['visit_wise'])?$enquiry_filters_sess['visit_wise']:'';
		
		$list_data             =   !empty($enquiry_filters_sess['list_data'])?$enquiry_filters_sess['list_data']:'';
        
		
		$sales_region = !empty($enquiry_filters_sess['sales_region'])?$enquiry_filters_sess['sales_region']:'';
		$sales_area = !empty($enquiry_filters_sess['sales_area'])?$enquiry_filters_sess['sales_area']:'';
		$sales_branch = !empty($enquiry_filters_sess['sales_branch'])?$enquiry_filters_sess['sales_branch']:'';
		
		$emp_region = !empty($enquiry_filters_sess['emp_region'])?$enquiry_filters_sess['emp_region']:'';
		$emp_area = !empty($enquiry_filters_sess['emp_area'])?$enquiry_filters_sess['emp_area']:'';
		$emp_branch = !empty($enquiry_filters_sess['emp_branch'])?$enquiry_filters_sess['emp_branch']:'';
		
		$client_type = !empty($enquiry_filters_sess['client_type'])?$enquiry_filters_sess['client_type']:'';
		$business_load = !empty($enquiry_filters_sess['business_load'])?$enquiry_filters_sess['business_load']:'';
		$industries = !empty($enquiry_filters_sess['industries'])?$enquiry_filters_sess['industries']:'';
		
		//print_r($list_data);exit;

        $select = "enquiry.sales_branch as sl_branch,enquiry.sales_region as sl_region,enquiry.sales_area as sl_area,tbl_admin2.sales_region as as_region,tbl_admin2.sales_area as as_area,tbl_admin2.sales_branch as as_branch,tbl_admin.sales_region as cr_region,tbl_admin.sales_area as cr_area,tbl_admin.sales_branch as cr_branch,create_dept.dept_name as createbydept,assign_dept.dept_name as assignbydept,lead_stage.lead_stage_name,tbl_company.company_name,enquiry.status,enquiry.name_prefix,enquiry.enquiry_id,tbl_subsource.subsource_name,enquiry.created_by,enquiry.aasign_to,enquiry.Enquery_id,enquiry.score,enquiry.enquiry,enquiry.company,tbl_product_country.country_name,enquiry.org_name,enquiry.name,enquiry.lastname,enquiry.email,enquiry.phone,enquiry.address,enquiry.reference_name,enquiry.created_date,enquiry.enquiry_source,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,lead_stage.lead_stage_name,tbl_datasource.datasource_name,tbl_product.product_name as product_name,CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name) as created_by_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name,lead_score.score_name,lead_score.probability,tbl_company.company_name,enquiry.client_name,visit_tbl.visit_count,enquiry_tags.tag_ids";

        if ($this->session->companey_id != 57) {
            /*$select .= " ,GROUP_CONCAT(concat(tbl_enqstatus1.user_id,'#',tbl_enqstatus1.status) SEPARATOR '_') AS t";        
            $this->db->join('( SELECT tbl_enqstatus.* FROM tbl_enqstatus INNER JOIN enquiry ON enquiry.Enquery_id=tbl_enqstatus.enquiry_code WHERE tbl_enqstatus.user_id = `enquiry`.`created_by` OR tbl_enqstatus.user_id = enquiry.aasign_to ) AS tbl_enqstatus1', 'tbl_enqstatus1.enquiry_code = enquiry.Enquery_id', 'left');      */  
        }

        if($this->session->userdata('companey_id')==29){
            $select.= ",tbl_bank.bank_name";
            $this->db->join('tbl_newdeal ', 'tbl_newdeal.enq_id = enquiry.Enquery_id', 'left');
            $this->db->join('tbl_bank ', 'tbl_bank.id = tbl_newdeal.bank', 'left');
        }
        $data_type = $_POST['data_type'];    
       
        $this->db->select($select);       
        
        $this->db->join('(select count(tbl_visit.enquiry_id) as visit_count,tbl_visit.enquiry_id from tbl_visit group by tbl_visit.enquiry_id) as visit_tbl','visit_tbl.enquiry_id=enquiry.enquiry_id','left');

        if(!empty($visit_wise)){
            if($visit_wise == 1){
                $where .= " visit_tbl.visit_count>0 AND ";
            }else if($visit_wise ==2){
                $where .= " visit_tbl.visit_count is null AND ";
            }
        }         
        $this->db->join('lead_source','enquiry.enquiry_source = lead_source.lsid','left');
        $this->db->join('tbl_product','enquiry.product_id = tbl_product.sb_id','left');
        $this->db->join('lead_stage','lead_stage.stg_id = enquiry.lead_stage','left');   
        $this->db->join('lead_score','lead_score.sc_id = enquiry.lead_score','left');   
        $this->db->join('tbl_product_country','tbl_product_country.id = enquiry.enquiry_subsource','left');
        $this->db->join('tbl_subsource','tbl_subsource.subsource_id = enquiry.sub_source','left');        
        $this->db->join('tbl_datasource','enquiry.datasource_id = tbl_datasource.datasource_id','left');
        $this->db->join('tbl_admin as tbl_admin', 'tbl_admin.pk_i_admin_id = enquiry.created_by', 'left');
        $this->db->join('tbl_admin as tbl_admin2', 'tbl_admin2.pk_i_admin_id = enquiry.aasign_to', 'left'); 
		$this->db->join('tbl_department as create_dept', 'create_dept.id = tbl_admin.dept_name', 'left');
		$this->db->join('tbl_department as assign_dept', 'assign_dept.id = tbl_admin2.dept_name', 'left');
        $this->db->join('tbl_company','tbl_company.id=enquiry.company','left'); 
        $this->db->join('enquiry_tags','enquiry_tags.enq_id=enquiry.enquiry_id','left');        

        $this->db->join('commercial_info','commercial_info.enquiry_id = enquiry.enquiry_id','left');		
        
    // echo $top_filter; exit();
	
//FOR ALL CLIENT DATA LIST FILTER START
	if(!empty($list_data)){
                $where .= " enquiry.status ='".$list_data."' AND ";
        }
//FOR ALL CLIENT DATA LIST FILTER START
        if($top_filter=='all')
        {
            $where.="  (enquiry.status IN ($data_type) ";
			$where.=" OR commercial_info.stage_id='".$data_type."')";
        }
        else if($top_filter=='droped')
        {            
            $where.="  enquiry.drop_status>0";
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";
        }else if($top_filter=='created_today'){
           // $date=date('Y-m-d');
            //  $where.="enquiry.created_date LIKE '%$date%'";
            $where.="  enquiry.drop_status=0";
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";
        }else if($top_filter=='updated_today'){
            // $date=date('Y-m-d');
            // $where.="enquiry.update_date LIKE '%$date%'";        
            $where.="  enquiry.drop_status=0 and enquiry.update_date is not NULL";
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";

            //this->db->where('');

        }else if($top_filter=='active'){            
            $where.="  enquiry.drop_status=0";
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";
        }
        else if($top_filter == 'assigned')
        {   
            $where.="  enquiry.aasign_to is not NULL AND enquiry.drop_status=0" ;
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";

        }
        else if($top_filter == 'unassigned')
        {
            $where.="  enquiry.aasign_to is NULL AND enquiry.drop_status=0";
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";
        }
        else if($top_filter == 'pending')
        {
            $where.="  enquiry.lead_stage=0 AND enquiry.drop_status=0";
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";
        }
        else{                        
            $where.="  enquiry.drop_status=0";
            $where.=" AND (enquiry.status IN ($data_type)";
			$where.=" OR commercial_info.stage_id='".$data_type."')";
        }

        if(isset($enquiry_filters_sess['lead_stages']) && $enquiry_filters_sess['lead_stages'] !=-1){
            $stage  =   $enquiry_filters_sess['lead_stages'];
            $where .= " AND enquiry.lead_stage=$stage";
        }  
        //echo $filter_user_id.'hello';
        if(empty($filter_user_id)){
            $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';          
        }

        if(!empty($this->session->process) && empty($product_filter)){              
            $arr = $this->session->process;           
            if(is_array($arr)){
                $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
            }                       
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
        }
        /* tag filter */
        if(!empty($tags)){
            $where .= " AND FIND_IN_SET($tags,enquiry_tags.tag_ids) > 0 ";
        }
        

         if($data_type=='1')
            $enq_date_fld = 'created_date';
        else if($data_type=='2')
            $enq_date_fld = 'lead_created_date';
        else if($data_type=='3')
            $enq_date_fld = 'client_created_date';
        else 
            $enq_date_fld = 'created_date';

        if(!empty($from_created) && !empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(enquiry.".$enq_date_fld.") >= '".$from_created."' AND DATE(enquiry.".$enq_date_fld.") <= '".$to_created."'";
        }
        if(!empty($from_created) && empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $where .= " AND DATE(enquiry.".$enq_date_fld.") >=  '".$from_created."'";                        
        }
        if(empty($from_created) && !empty($to_created)){            
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(enquiry.".$enq_date_fld.") <=  '".$to_created."'";                                    
        }
		
		if(!empty($createdbydept)){            
           
            $where .= " AND tbl_admin.dept_name =  '".$createdbydept."'";                                    
        }
		
		if(!empty($assigntodept)){            
           
            $where .= " AND tbl_admin2.dept_name =  '".$assigntodept."'";                                    
        }

        if(!empty($aging_rule)){
            $where .= " AND $aging_rule ";                                                
        }
		
		if(!empty($sales_region)){
            $where .= " AND enquiry.sales_region =  '".$sales_region."'";                                                
        }
		
		if(!empty($sales_area)){
            $where .= " AND enquiry.sales_area =  '".$sales_area."'";                                                
        }
		
		if(!empty($sales_branch)){
            $where .= " AND enquiry.sales_branch =  '".$sales_branch."'";                                                
        }
		
		if(!empty($emp_region)){
            $where .= " AND tbl_admin.sales_region =  '".$emp_region."' OR tbl_admin2.sales_region =  '".$emp_region."'";                                                
        }
		
		if(!empty($emp_area)){
            $where .= " AND tbl_admin.sales_area =  '".$emp_area."' OR tbl_admin2.sales_area =  '".$emp_area."'";                                                
        }
		
		if(!empty($emp_branch)){
            //$where .= " AND tbl_admin.sales_branch =  '".$emp_branch."' OR tbl_admin.sales_branch =  '".$emp_branch."'";
            $where .= " AND FIND_IN_SET($emp_branch, tbl_admin.sales_branch) OR FIND_IN_SET($emp_branch, tbl_admin.sales_branch)";			
        }
		
		if(!empty($client_type)){
            $where .= " AND enquiry.client_type LIKE  '%$client_type%'";                                                
        }
		
		if(!empty($business_load)){
            $where .= " AND enquiry.business_load LIKE  '%$business_load%'";                                                
        }
		
		if(!empty($industries)){
            $where .= " AND enquiry.industries LIKE  '%$industries%'";                                                
        }
		
        if(!empty($company)){                    
            //$where .= " AND enquiry.company =  '".$company."'"; 
              $where .= " AND tbl_company.company_name LIKE  '%$company%'";			
        }
		
		if(!empty($clientname)){
              $where .= " AND enquiry.client_name LIKE  '%$clientname%'";			
        }
		
        if(!empty($source)){                       
            //$where .= " AND enquiry.enquiry_source =  '".$source."'";
            $where .= " AND FIND_IN_SET($source,enquiry.enquiry_source) > 0 ";			  
        }

        if(!empty($probability)){                       
            $where .= " AND enquiry.lead_score =  '".$probability."'";                                    
        }
        
        if(!empty($sub_source)){                       
            $where .= " AND enquiry.sub_source =  '".$sub_source."'";                                    
        }

        if(!empty($employee)){          
            $where .= " AND CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) LIKE  '%$employee%' ";
        }
        if(!empty($email)){ 
            $where .= " AND enquiry.email =  '".$email."'";                                    
        }
        if(!empty($datasource)){            
           
            $where .= " AND enquiry.datasource_id =  '".$datasource."'";                                    
        }
         if(!empty($enq_product)){            
           
            $where .= " AND enquiry.product_id =  '".$enq_product."'";                                    
        }

        if(!empty($phone)){            
           
            $where .= " AND enquiry.phone =  '".$phone."'";                                    
        }
        // echo $filter_user_id.'hekk';
        // exit;
        if(!empty($filter_user_id)){
            $where .= " AND (enquiry.created_by =  '".$filter_user_id."'";                                    
            $where .= " OR enquiry.aasign_to =  '".$filter_user_id."')";                                    
        }else{
            if(!empty($createdby)){          
                $where .= " AND enquiry.created_by =  '".$createdby."'";                                    
            }
             if(!empty($assign)){                           
                $where .= " AND enquiry.aasign_to =  '".$assign."'";                                    
            }
        }



        if(!empty($address)){            
           
            $where .= " AND enquiry.address LIKE  '%$address%'";                                    
        }
        if(!empty($stage)){

            $where .= " AND enquiry.lead_stage='".$stage."'"; 

        }
        if(!empty($productcntry)){            
           
            $where .= " AND enquiry.enquiry_subsource='".$productcntry."'";                                    
        }
        if(!empty($state) && empty($city)){

            $where .= " AND enquiry.state_id='".$state."'"; 

        }
          if(empty($state) && !empty($city)){

            $where .= " AND enquiry.city_id='".$city."'"; 

        }
        if(!empty($state) && !empty($city)){
            $where .= " AND enquiry.state_id='".$state."' AND enquiry.city_id='".$city."'"; 
        }

        if(!empty($_POST['specific_list']))
        { 
            $where.=' AND enquiry.enquiry_id IN ('.$_POST['specific_list'].')';
        }
        //echo $where; exit();
        $this->db->where($where);
        //$this->db->group_by('enquiry.Enquery_id');

        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        
        if(!empty($_POST['search']['value'])) // if datatable send POST for search
        {
            $compid = $this->session->companey_id;
            $val = $_POST['search']['value'];
            $this->db->or_where("enquiry.enquiry_id IN (SELECT parent FROM extra_enquery WHERE cmp_no = '$compid' AND fvalue LIKE '%{$val}%')");
            
        }   
    
        if(isset($_POST['order'])) // here order processing
        {
            //print_r($_POST['order']); exit();

            //if(!empty($this->column_order[$_POST['order']['0']['column']]) and $this->column_order[$_POST['order']['0']['column']] < count($this->column_order)){
            if(!empty($this->column_order[$_POST['order']['0']['column']]) and $_POST['order']['0']['column'] < count($this->column_order))    
            {
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']); 
                //echo  $this->column_order[$_POST['order']['0']['column']].' -> '.$_POST['order']['0']['dir'];
                //exit();
            }
            else
            {
                
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            }
            
        } 
        else if(isset($this->order))
        {
            $order = $this->order;

            $this->db->order_by(key($order), $order[key($order)]);
       }
    }
 
    function get_datatables(){
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $data_type = $_POST['data_type'];            
        $this->db->group_by('enquiry.Enquery_id');        
        $query = $this->db->get();
        return $query->result();
        //   $query->result();
        //   echo $this->db->last_query();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();

        // $where = "";
        // $user_id   = $this->session->user_id;
        // $user_role = $this->session->user_role;
        
        // $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        // $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        // $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
        // $this->db->where($where);

        // $data_type = $_POST['data_type'];    
        
        // $this->db->group_by('enquiry.Enquery_id');
        


        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->select('count(enquiry_id) as c');
        $this->db->from($this->table);        
        $where = "";
        $datatype = $_POST['data_type'];
        
        $compid = $this->session->companey_id;
        $where .= " enquiry.status IN ($datatype) AND comp_id=$compid";

        if(!empty($_POST['specific_list']))
        { 
            $where.=' AND enquiry.enquiry_id IN ('.$_POST['specific_list'].')';
        }

        $this->db->where($where);                
        return $this->db->count_all_results();
    }
 
}