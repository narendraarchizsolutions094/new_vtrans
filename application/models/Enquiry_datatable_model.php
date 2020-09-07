<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Enquiry_datatable_model extends CI_Model {
 
    var $table = 'enquiry';

    var $column_order = array('','enquiry.enquiry_id','lead_source.lead_name', 'enquiry.company','enquiry.name','enquiry.enquiry_source','enquiry.email','enquiry.phone','enquiry.address','enquiry.created_date','enquiry.created_by','enquiry.aasign_to','tbl_datasource.datasource_name'); //set column field database for datatable orderable

    var $column_search = array('enquiry.name_prefix','enquiry.enquiry_id','enquiry.company','enquiry.org_name','enquiry.name','enquiry.lastname','enquiry.email','enquiry.phone','enquiry.address','enquiry.created_date','enquiry.enquiry_source','lead_source.icon_url','lead_source.lsid','lead_source.score_count','lead_source.lead_name','tbl_datasource.datasource_name','tbl_product.product_name',"CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name )","CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name)"); //set column field database for datatable searchable 

    var $order = array('enquiry.enquiry_id' => 'desc'); // default order 
 
    public function __construct()
    {
        parent::__construct();    
        $this->load->model('common_model');
    }
 
    private function _get_datatables_query(){    
       $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
       $this->db->from($this->table);       
       
       $user_id   = $this->session->user_id;
       /*$user_role = $this->session->user_role;
       $assign_country = $this->session->country_id;
       $assign_region = $this->session->region_id;
       $assign_territory = $this->session->territory_id;
       $assign_state = $this->session->state_id;
       $assign_city = $this->session->city_id;*/       
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
        $enq_product            =   !empty($enquiry_filters_sess['enq_product'])?$enquiry_filters_sess['enq_product']:'';
        $phone                  =   !empty($enquiry_filters_sess['phone'])?$enquiry_filters_sess['phone']:'';
        $createdby              =   !empty($enquiry_filters_sess['createdby'])?$enquiry_filters_sess['createdby']:'';
        $assign                 =   !empty($enquiry_filters_sess['assign'])?$enquiry_filters_sess['assign']:'';
        $address                =   !empty($enquiry_filters_sess['address'])?$enquiry_filters_sess['address']:'';
        $product_filter         =   !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
        $assign_filter          =   !empty($enquiry_filters_sess['assign_filter'])?$enquiry_filters_sess['assign_filter']:'';
        $stage                  =   !empty($enquiry_filters_sess['stage'])?$enquiry_filters_sess['stage']:'';
         $productcntry          =   !empty($enquiry_filters_sess['prodcntry'])?$enquiry_filters_sess['prodcntry']:'';
        $state                  =   !empty($enquiry_filters_sess['state'])?$enquiry_filters_sess['state']:'';
        $city                   =   !empty($enquiry_filters_sess['city'])?$enquiry_filters_sess['city']:'';

        $select = "enquiry.name_prefix,enquiry.enquiry_id,tbl_subsource.subsource_name,enquiry.created_by,enquiry.aasign_to,enquiry.Enquery_id,enquiry.score,enquiry.enquiry,enquiry.company,tbl_product_country.country_name,enquiry.org_name,enquiry.name,enquiry.lastname,enquiry.email,enquiry.phone,enquiry.address,enquiry.reference_name,enquiry.created_date,enquiry.enquiry_source,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,lead_stage.lead_stage_name,tbl_datasource.datasource_name,tbl_product.product_name as product_name,CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name) as created_by_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name";

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
        $this->db->join('lead_source','enquiry.enquiry_source = lead_source.lsid','left');
        $this->db->join('tbl_product','enquiry.product_id = tbl_product.sb_id','left');
        $this->db->join('lead_stage','lead_stage.stg_id = enquiry.lead_stage','left');   
        $this->db->join('tbl_product_country','tbl_product_country.id = enquiry.enquiry_subsource','left');
        $this->db->join('tbl_subsource','tbl_subsource.subsource_id = enquiry.sub_source','left');        
        $this->db->join('tbl_datasource','enquiry.datasource_id = tbl_datasource.datasource_id','left');
        $this->db->join('tbl_admin as tbl_admin', 'tbl_admin.pk_i_admin_id = enquiry.created_by', 'left');
        $this->db->join('tbl_admin as tbl_admin2', 'tbl_admin2.pk_i_admin_id = enquiry.aasign_to', 'left');        
        
        

        if($top_filter=='all'){            
            $where.="  enquiry.status=$data_type";
        }elseif($top_filter=='droped'){            
            $where.="  enquiry.status=$data_type";
            $where.=" AND enquiry.drop_status>0";
        }elseif($top_filter=='created_today'){
            $date=date('Y-m-d');
            $where.="enquiry.created_date LIKE '%$date%'";
            $where.=" AND enquiry.status=$data_type";
            $where.=" AND enquiry.drop_status=0";
        }elseif($top_filter=='updated_today'){
            $date=date('Y-m-d');
            $where.="enquiry.update_date LIKE '%$date%'";        
            $where.=" AND enquiry.status=$data_type";
            $where.=" AND enquiry.drop_status=0";
        }elseif($top_filter=='active'){            
            $where.="  enquiry.status=$data_type";
            $where.=" AND enquiry.drop_status=0";
        }else{                        
            $where.="  enquiry.status=$data_type";
            $where.=" AND enquiry.drop_status=0";
        }                   
        if(isset($enquiry_filters_sess['lead_stages']) && $enquiry_filters_sess['lead_stages'] !=-1){
            $stage  =   $enquiry_filters_sess['lead_stages'];
            $where .= " AND enquiry.lead_stage=$stage";
        }  
        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';          

        if(!empty($this->session->process) && empty($product_filter)){              
            $arr = $this->session->process;           
            if(is_array($arr)){
                $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
            }                       
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
        }
        if(!empty($from_created) && !empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(enquiry.created_date) >= '".$from_created."' AND DATE(enquiry.created_date) <= '".$to_created."'";
        }
        if(!empty($from_created) && empty($to_created)){
            $from_created = date("Y-m-d",strtotime($from_created));
            $where .= " AND DATE(enquiry.created_date) >=  '".$from_created."'";                        
        }
        if(empty($from_created) && !empty($to_created)){            
            $to_created = date("Y-m-d",strtotime($to_created));
            $where .= " AND DATE(enquiry.created_date) <=  '".$to_created."'";                                    
        }
        if(!empty($company)){                    
            $where .= " AND enquiry.company =  '".$company."'";                                    
        }
        if(!empty($source)){                       
            $where .= " AND enquiry.enquiry_source =  '".$source."'";                                    
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
        if(!empty($createdby)){            
           
            $where .= " AND enquiry.created_by =  '".$createdby."'";                                    
        }
         if(!empty($assign)){            
           
            $where .= " AND enquiry.aasign_to =  '".$assign."'";                                    
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
            if(!empty($this->column_order[$_POST['order']['0']['column']]) and $this->column_order[$_POST['order']['0']['column']] < count($this->column_order)){
                
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
            }else{
                
                //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
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
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();

        $where = "";
        $user_id   = $this->session->user_id;
        $user_role = $this->session->user_role;
        
        /*$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
        $this->db->where($where);*/

        $data_type = $_POST['data_type'];    
        
        $this->db->group_by('enquiry.Enquery_id');
        


        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        $this->db->from($this->table);        
        $where = "";
        $datatype = $_POST['data_type'];
        $where .= " enquiry.status=$datatype ";
        $user_id   = $this->session->user_id;
        $user_role = $this->session->user_role;

        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  

        $enquiry_filters_sess    =   $this->session->enquiry_filters_sess;        
        $product_filter = !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';

        if(!empty($this->session->process) && empty($product_filter)){   
        $arr = $this->session->process; 
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }            
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
        }


        $this->db->where($where);
        $data_type = $_POST['data_type'];    
        
        $this->db->group_by('enquiry.Enquery_id');
        

        return $this->db->count_all_results();
    }
 
}