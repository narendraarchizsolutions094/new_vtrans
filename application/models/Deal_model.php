<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Deal_model extends CI_Model {

    //  Functions for deal graphs start
    public function deal_status_feed($filter=array()) {
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    
        $this->db->select('count(status) as c,status');             
        $this->db->where($where);
        $this->db->group_by('status');
        $result = $this->db->get('commercial_info')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$value){
                if($value['status'] == 0){  
                    $res[] = array('Pending',(int)$value['c']);
                }elseif($value['status'] == 1){
                    $res[] = array('Done',(int)$value['c']);
                }elseif($value['status'] == 2){
                    $res[] = array('Deferred',(int)$value['c']);
                }
            }
        }
        return $res;
    }
    public function booking_type_feed($filter=array()){
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    
        $this->db->select('count(business_type) as c,business_type');
        $this->db->where($where);
        $this->db->group_by('business_type');
        $result = $this->db->get('commercial_info')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$value){
                if($value['business_type'] == 'in'){  
                    $res[] = array('Inward',(int)$value['c']);
                }elseif($value['business_type'] == 'out'){
                    $res[] = array('Outward',(int)$value['c']);
                }
            }
        }
        return $res;
    }
    public function product_feed($filter=array()){        
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    

        $this->db->select('count(booking_type) as c,booking_type');
        $this->db->where($where);
        $this->db->group_by('booking_type');
        $result = $this->db->get('commercial_info')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$value){
                if($value['booking_type'] == 'ftl'){  
                    $res[] = array('FTL',(int)$value['c']);
                }elseif($value['booking_type'] == 'sundry'){
                    $res[] = array('Sundry',(int)$value['c']);
                }
            }
        }
        return $res;
    }
    public function approaval_status_feed(){
        $res = array();
        return $res;
    }

    public function country_wise_feed($filter=array()){
        $res = array(array('Domestic',0),array('SAARC',0));

        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    
        $this->db->select('count(booking_type) as c');        
        $this->db->where($where);
        $this->db->where('FIND_IN_SET("domestic",deal_type)>',0);
        $result1 = $this->db->get('commercial_info')->row_array();
        
        if(!empty($result1['c'])){
            $res[0][1] = (int)$result1['c'];
        }
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $this->db->select('count(booking_type) as c');        
        $this->db->where($where);
        $this->db->where('FIND_IN_SET("saarc",deal_type)>',0);
        $result2 = $this->db->get('commercial_info')->row_array();
        
        if(!empty($result2['c'])){
            $res[1][1] = (int)$result2['c'];
        }

        return $res;
    }
    public function region_wise_feed($filter=array()){
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    
        $this->db->select('count(enquiry.sales_region) as c,sales_region.name');
        $this->db->where($where);
        $this->db->join('commercial_info','commercial_info.enquiry_id=enquiry.enquiry_id');
        $this->db->join('sales_region','sales_region.region_id=enquiry.sales_region');
        $this->db->group_by('enquiry.sales_region');
        $result = $this->db->get('enquiry')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$value){                
                $res[] = array($value['name'],(int)$value['c']);                
            }
        }
        return $res;
    }
    public function branch_wise_feed($filter=array()){
        $res = array();
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    

        $this->db->select('count(enquiry.sales_branch) as c,branch.branch_name as name');
        $this->db->where($where);
        $this->db->join('commercial_info','commercial_info.enquiry_id=enquiry.enquiry_id');
        $this->db->join('branch','branch.branch_id=enquiry.sales_branch');
        $this->db->group_by('enquiry.sales_branch');
        $result = $this->db->get('enquiry')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$value){                
                $res[] = array($value['name'],(int)$value['c']);                
            }
        }
        return $res;
    }
    
    public function area_wise_feed($filter=array()){        
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    

        $this->db->select('count(enquiry.sales_area) as c,sales_area.area_name as name');
        $this->db->where($where);
        $this->db->join('commercial_info','commercial_info.enquiry_id=enquiry.enquiry_id');
        $this->db->join('sales_area','sales_area.area_id=enquiry.sales_area');
        $this->db->group_by('enquiry.sales_area');
        $result = $this->db->get('enquiry')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$value){                
                $res[] = array($value['name'],(int)$value['c']);                
            }
        }
        return $res;
    }

    public function weight_wise_feed($filter=array()){        
                
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    

        $this->db->select('sum(deal_data.expected_tonnage) as c,concat_ws(" ",tbl_admin.s_display_name,tbl_admin.last_name) as employee');
        $this->db->where($where);
        $this->db->join('deal_data','deal_data.deal_id=commercial_info.id');
        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=commercial_info.createdby');
        $this->db->group_by('commercial_info.createdby');
        $result = $this->db->get('commercial_info')->result_array();
        if (!empty($result)){
            foreach($result as $key=>$value){
                $res[] = array($value['employee'],(int)$value['c']);                
            }
        }
        return $res;
    }

    public function freight_wise_feed($filter=array()){
                
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') ';
        if(!empty($filter['from_date']) && !empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) >= '".$from_created."' AND DATE(commercial_info.creation_date) <= '".$to_created."'";
        }
        if(!empty($filter['from_date']) && empty($filter['to_date'])){
            $from_created = date("Y-m-d",strtotime($filter['from_date']));
            $where .= " AND DATE(commercial_info.creation_date) >=  '".$from_created."'";                        
        }
        if(empty($filter['from_date']) && !empty($filter['to_date'])){            
            $to_created = date("Y-m-d",strtotime($filter['to_date']));
            $where .= " AND DATE(commercial_info.creation_date) <=  '".$to_created."'";                                    
        }
        $where .= " AND commercial_info.original = 1";                                    

        $this->db->select('sum(deal_data.expected_amount) as c,concat_ws(" ",tbl_admin.s_display_name,tbl_admin.last_name) as employee');
        $this->db->where($where);
        $this->db->join('deal_data','deal_data.deal_id=commercial_info.id');
        $this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=commercial_info.createdby');
        $this->db->group_by('commercial_info.createdby');
        $result = $this->db->get('commercial_info')->result_array();
        if (!empty($result)){
            foreach($result as $key=>$value){
                $res[] = array($value['employee'],((int)$value['c'])/100000);                
            }
        }
        return $res;
    }
    public function deal_month_wise_feed($filter=array()){
        $in  = $this->get_business_type_feed('in',$filter);
        $out = $this->get_business_type_feed('out',$filter);
        
        $domestic   = $this->get_deal_type_feed('domestic',$filter);
        $saarc      = $this->get_deal_type_feed('saarc',$filter);

        $ftl   = $this->get_booking_type_feed('ftl',$filter);
        $sandary = $this->get_booking_type_feed('sundry',$filter);

        $weight = $this->get_weight_feed($filter);
        $freight = $this->get_frieght_type_feed($filter);

        $res = array($in,$out,$domestic,$saarc,$ftl,$sandary,$weight,$freight);

        return $res;      
    }
    //  Functions for deal graphs end

    public function get_business_type_feed($type,$filter){
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') AND commercial_info.original = 1 ';
        $month_arr = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $this->db->select("count(id) as c,month(creation_date) as m");
        $this->db->where('year(creation_date)',date("Y"));
        
        $this->db->where($where);
        $this->db->where('FIND_IN_SET(business_type,"'.$type.'")>',0);
        $this->db->group_by('month(creation_date)');
        $data = $this->db->get('commercial_info')->result_array();
        //print_r($data);
        $month_res = array();
        if(!empty($data)){
            foreach($month_arr as $month){
                $flag = 0;
                $dflag = 0;
                foreach($data as $key=>$value){
                    if($value['m'] == $month){
                        $dflag = $value['c'];
                        break;
                    }
                }
                if(!$dflag){
                    $month_res[] = 0;
                }else{
                    $month_res[] = (int)$dflag;
                }
            }
        }else{
            $month_res = [0,0,0,0,0,0,0,0,0,0,0,0];
        }
       // print_r($month_res);
       if($type == 'in'){
           return array('name'=>'Inward','data'=>$month_res);
       }else{
            return array('name'=>'Outward','data'=>$month_res);
       }
    }
    public function get_deal_type_feed($type,$filter){
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') AND commercial_info.original = 1 ';
        $month_arr = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $this->db->select("count(id) as c,month(creation_date) as m");
        $this->db->where('year(creation_date)',date("Y"));
        $this->db->where($where);
        $this->db->where('FIND_IN_SET(deal_type,"'.$type.'")>',0);
        $this->db->group_by('month(creation_date)');
        $data = $this->db->get('commercial_info')->result_array();
        //print_r($data);
        $month_res = array();
        if(!empty($data)){
            foreach($month_arr as $month){
                $flag = 0;
                $dflag = 0;
                foreach($data as $key=>$value){
                    if($value['m'] == $month){
                        $dflag = $value['c'];
                        break;
                    }
                }
                if(!$dflag){
                    $month_res[] = 0;
                }else{
                    $month_res[] = (int)$dflag;
                }
            }
        }else{
            $month_res = [0,0,0,0,0,0,0,0,0,0,0,0];
        }      
       
        if($type == 'domestic'){
            return array('name'=>'Domestic','data'=>$month_res);
        }else{
            return array('name'=>'SAARC','data'=>$month_res);
        }
    }
    public function get_booking_type_feed($type,$filter){
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') AND commercial_info.original = 1 ';
        $month_arr = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $this->db->select("count(id) as c,month(creation_date) as m");
        $this->db->where('year(creation_date)',date("Y"));
        $this->db->where($where);
        $this->db->where('FIND_IN_SET(booking_type,"'.$type.'")>',0);
        $this->db->group_by('month(creation_date)');
        $data = $this->db->get('commercial_info')->result_array();
        //print_r($data);
        $month_res = array();
        if(!empty($data)){
            foreach($month_arr as $month){
                $flag = 0;
                $dflag = 0;
                foreach($data as $key=>$value){
                    if($value['m'] == $month){
                        $dflag = $value['c'];
                        break;
                    }
                }
                if(!$dflag){
                    $month_res[] = 0;
                }else{
                    $month_res[] = (int)$dflag;
                }
            }
        }else{
            $month_res = [0,0,0,0,0,0,0,0,0,0,0,0];
        }
       // print_r($month_res);
       
        if($type == 'ftl'){
            return array('name'=>'FTL','data'=>$month_res);
        }else{
            return array('name'=>'Sandary','data'=>$month_res);
        }
    }
    public function get_weight_feed($filter){
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') AND commercial_info.original = 1 ';
        $month_arr = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $this->db->select("sum(expected_tonnage) as c,month(creation_date) as m");
        $this->db->where('year(creation_date)',date("Y"));
        $this->db->group_by('month(creation_date)');
        $this->db->where($where);
        $data = $this->db->get('commercial_info')->result_array();
        //print_r($data);
        $month_res = array();
        if(!empty($data)){
            foreach($month_arr as $month){
                $flag = 0;
                $dflag = 0;
                foreach($data as $key=>$value){
                    if($value['m'] == $month){
                        $dflag = $value['c'];
                        break;
                    }
                }
                if(!$dflag){
                    $month_res[] = 0;
                }else{
                    $month_res[] = (int)$dflag;
                }
            }
        }else{
            $month_res = [0,0,0,0,0,0,0,0,0,0,0,0];
        }
       // print_r($month_res);
       
        return array('name'=>'Weight (in tons)','data'=>$month_res,"type"=>'spline');
    }
    public function get_frieght_type_feed($filter){
        if(!empty($filter['employee'])){
            $all_reporting_ids    =   $this->common_model->get_categories($filter['employee']);
        }else{
            $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        }
        $res = array();
        $where = " commercial_info.createdby IN (".implode(',', $all_reporting_ids).') AND commercial_info.original = 1 ';
        $month_arr = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $this->db->select("sum(expected_amount) as c,month(creation_date) as m");
        $this->db->where('year(creation_date)',date("Y"));
        $this->db->where($where);
        $this->db->group_by('month(creation_date)');
        $data = $this->db->get('commercial_info')->result_array();
        //print_r($data);
        $month_res = array();
        if(!empty($data)){
            foreach($month_arr as $month){
                $flag = 0;
                $dflag = 0;
                foreach($data as $key=>$value){
                    if($value['m'] == $month){
                        $dflag = $value['c'];
                        break;
                    }
                }
                if(!$dflag){
                    $month_res[] = 0;
                }else{
                    $month_res[] = ((int)$dflag)/100000;
                }
            }
        }else{
            $month_res = [0,0,0,0,0,0,0,0,0,0,0,0];
        }
       // print_r($month_res);
       
        return array('name'=>'Freight (in lacks)','data'=>$month_res,"type"=>'spline');
    }
}