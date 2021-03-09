<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_model extends CI_Model {
    private $table = "enquiry";

	public function all_enquiry($id) {
        $all_reporting_ids    =   $this->common_model->get_categories($id);        
        $this->db->from($this->table);        
        $where = "";                        
        $where .= " ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';                 
        $this->db->where($where);
		return $query = $this->db->get();        
    }

    public function all_enquiry1($id) {
        // $all_reporting_ids    =   $this->common_model->get_categories($id); 
        $this->db->select('extra_enquery.*,enquiry.*,tbl_input.*,state.state as stname ,city.city as ciname,lead_source.lead_name');       
        $this->db->from($this->table);                           
                    
        $this->db->where('enquiry.comp_id',$id);
        $this->db->join('extra_enquery','extra_enquery.enq_no=enquiry.Enquery_id','inner');
        // $this->db->join('(select * from extra_enquery left join tbl_input on tbl_input.input_id=extra_enquery.input where ) as tblin','tblin.input_id=extra_enquery.input','left');
        $this->db->join('tbl_input','tbl_input.input_id=extra_enquery.input','left');
        $this->db->join('city','city.id=enquiry.city_id','left');
        $this->db->join('state','state.id=enquiry.state_id','left');
        $this->db->join('lead_source','lead_source.lsid=enquiry.enquiry_source','left');
        return $query = $this->db->get()->result();        
    }

    public function all_fieldvalue($id){

        $this->db->select('extra_enquery.*,tbl_input.*');       
        $this->db->from('extra_enquery');                           
                    
        $this->db->where('extra_enquery.comp_id',$id);
        $this->db->join('tbl_input','tbl_input.input_id=extra_enquery.input','left');
       
        return $query = $this->db->get()->result(); 
    }

    public function all_enquiry_extra($id) {
        $all_reporting_ids    =   $this->common_model->get_categories($id);
        $this->db->select('extra_enquery.*');
        $this->db->from($this->table);        
        $where = "";                        
        $where .= " ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';                 
        $this->db->where($where);
        $this->db->join('extra_enquery','extra_enquery.enq_no=enquiry.Enquery_id','inner');
		return $query = $this->db->get();        
    }

    public function all_enquiry_extra1($id) {
        // $all_reporting_ids    =   $this->common_model->get_categories($id);
        $this->db->select('extra_enquery.*');
        $this->db->from($this->table);        
        $where = "";                        
        $where .= "enquiry.comp_id=$id";        
        $this->db->where($where);
        $this->db->join('extra_enquery','extra_enquery.enq_no=enquiry.Enquery_id','inner');
        return $query = $this->db->get();        
    }
    
    public function lead_stage($comp_id){
    	$this->db->where('comp_id',$comp_id);    	
    	$result	=	$this->db->get('lead_stage');
    	return $result;
    }
	
	public function lead_description($comp_id){
		$this->db->where('comp_id',$comp_id);    	
    	$result	=	$this->db->get('lead_description');
    	return $result;
	}

	public function lead_source($comp_id){
		$this->db->where('comp_id',$comp_id);    	
    	$result	=	$this->db->get('lead_source');
    	return $result;
	}
	public function lead_sub_source($comp_id){
		$this->db->where('comp_id',$comp_id);    	
    	$result	=	$this->db->get('tbl_subsource');
    	return $result;
	}
	public function products($comp_id){
		$this->db->where('comp_id',$comp_id);    	
    	$result	=	$this->db->get('tbl_product');
    	return $result;	
	}
    public function get_process_list($comp_id){
        $this->db->where('comp_id',$comp_id);
        return $this->db->get('tbl_product');
    }
    public function country($comp_id){
        $this->db->where('comp_id',$comp_id);
        return $this->db->get('tbl_country');   
    }
    public function state($comp_id){
        $this->db->where('comp_id',$comp_id);
        return $this->db->get('state');   
    }
    public function city($comp_id){
        $this->db->where('comp_id',$comp_id);
        return $this->db->get('city');   
    }

    public function get_enquiry_list($compid){

        // $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $where='';

         $select = "enquiry.name_prefix,enquiry.enquiry_id,tbl_subsource.subsource_name,enquiry.created_by,enquiry.aasign_to,enquiry.Enquery_id,enquiry.company,tbl_product_country.country_name,enquiry.org_name,enquiry.name,enquiry.lastname,enquiry.email,enquiry.phone,enquiry.address,enquiry.reference_name,enquiry.created_date,enquiry.enquiry_source,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,lead_stage.lead_stage_name,tbl_datasource.datasource_name,tbl_product.product_name as product_name,CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name) as created_by_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name,GROUP_CONCAT(concat(tbl_enqstatus1.user_id,'#',tbl_enqstatus1.status) SEPARATOR '_') AS t";

        if($this->session->userdata('companey_id')==29){

            $select.= ",tbl_newdeal.*,tbl_bank.*";
            $this->db->join('tbl_newdeal ', 'tbl_newdeal.enq_id = enquiry.Enquery_id', 'left');
            $this->db->join('tbl_bank ', 'tbl_bank.id = tbl_newdeal.bank', 'left');

       }

        $this->db->select($select);        
        
        $this->db->join('lead_source','enquiry.enquiry_source = lead_source.lsid','left');
        $this->db->join('tbl_product','enquiry.product_id = tbl_product.sb_id','left');
        $this->db->join('lead_stage','lead_stage.stg_id = enquiry.lead_stage','left');   
        $this->db->join('tbl_product_country','tbl_product_country.id = enquiry.enquiry_subsource','left');
        $this->db->join('tbl_subsource','tbl_subsource.subsource_id = enquiry.sub_source','left');
         $this->db->join('extra_enquery','extra_enquery.enq_no=enquiry.Enquery_id','inner');
            

        
        //$this->db->join('whatsapp_send_log','enquiry.phone = whatsapp_send_log.mobile_no','left');
        $this->db->join('tbl_datasource','enquiry.datasource_id = tbl_datasource.datasource_id','left');
        //$this->db->join('tbl_enqstatus','tbl_enqstatus.enquiry_code= enquiry.Enquery_id','left');


        $this->db->join('tbl_admin as tbl_admin', 'tbl_admin.pk_i_admin_id = enquiry.created_by', 'left');
        $this->db->join('tbl_admin as tbl_admin2', 'tbl_admin2.pk_i_admin_id = enquiry.aasign_to', 'left');
        
        $this->db->join('( SELECT tbl_enqstatus.* FROM tbl_enqstatus INNER JOIN enquiry ON enquiry.Enquery_id=tbl_enqstatus.enquiry_code WHERE tbl_enqstatus.user_id = `enquiry`.`created_by` OR tbl_enqstatus.user_id = enquiry.aasign_to ) AS tbl_enqstatus1', 'tbl_enqstatus1.enquiry_code = enquiry.Enquery_id', 'left');


         if(!empty($this->session->process) ){  
            
                $arr = $this->session->process;           
                if(is_array($arr)){
                    $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
                }           
            
        }
         $where .= "enquiry.comp_id=$compid";
         // $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
         // $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 

         $this->db->where($where);

         return $this->db->get()->result();

    }

    public function getformfield(){
    
        $this->db->select('*');
        $this->db->where(array("company_id"=> $this->session->companey_id, "status" => 1));
        return $this->db->get("tbl_input")->result();
        
        
    }

        public function getfieldvalue($enqnos = '',$compid=0){   
    
        $this->db->select('*');
        $this->db->where(array("cmp_no"=> $compid));                
        if(!empty($enqnos)) {
            $enqnos = trim($enqnos, ",");   
            $this->db->where_in("parent", $enqnos);
        }
        if(isset($_COOKIE["dallowcols"])) {
            
            $dshowall = false;
            $dacolarr  = explode(",", trim($_COOKIE["dallowcols"], ","));
        
        }
        
        
        $resarr = $this->db->get("extra_enquery")->result();        
        $newarr = array();
        if(!empty($resarr)){
            foreach($resarr as $ind => $res){               
                $prnt = $res->parent;
                $newarr[$prnt][$res->input] = $res; 
            }
        }       
        return $newarr;
    }
}