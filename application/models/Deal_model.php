<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Deal_model extends CI_Model {

    //  Functions for deal graphs start
    public function deal_status_feed(){
        $res = array();
        $this->db->select('count(status) as c,status');
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
    public function booking_type_feed(){
        $res = array();
        $this->db->select('count(business_type) as c,business_type');
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
    public function product_feed(){        
        $res = array();
        $this->db->select('count(booking_type) as c,booking_type');
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

    public function country_wise_feed(){
        $res = array(array('Domestic',0),array('SAARC',0));
        $this->db->select('count(booking_type) as c');        
        $this->db->where('FIND_IN_SET("domestic",deal_type)>',0);
        $result1 = $this->db->get('commercial_info')->row_array();
        
        if(!empty($result1['c'])){
            $res[0][1] = (int)$result1['c'];
        }
        
        $this->db->select('count(booking_type) as c');        
        $this->db->where('FIND_IN_SET("saarc",deal_type)>',0);
        $result2 = $this->db->get('commercial_info')->row_array();
        
        if(!empty($result2['c'])){
            $res[1][1] = (int)$result2['c'];
        }

        return $res;
    }
    public function region_wise_feed(){
        $res = array();
        return $res;
    }
    public function branch_wise_feed(){
        $res = array();
        return $res;
    }
    //  Functions for deal graphs end
}