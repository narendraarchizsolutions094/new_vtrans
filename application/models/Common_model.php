<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

    /* User reporting funcitons start */

    private $list = array();

    function fetch_recursive($tree){

        foreach($tree as $k => $v){
            
            $this->list[] = $v->pk_i_admin_id;

            $this->fetch_recursive($v->sub);

        }
    
        return $this->list;
    
    }
    public function get_right_ids($user_right){               
        $query    =   $this->db->query(
                        "SELECT GROUP_CONCAT(lv SEPARATOR ',') as rids FROM (
                            SELECT @pv:=(SELECT GROUP_CONCAT(use_id SEPARATOR ',') FROM tbl_user_role 
                            WHERE FIND_IN_SET(parent_right, @pv)) AS lv FROM tbl_user_role 
                            JOIN
                            (SELECT @pv:=$user_right) tmp
                            ) a;"                        
                        );
        $res    =   $query->row_array();
        $arr = array();
        if (!empty($res['rids'])) {
            $res = $res['rids'];
            $arr = explode(',', $res);            
        }
        array_push($arr, $user_right);        
        return $arr;
    }

    public function get_user_ids($uid){               
        $query    =   $this->db->query(
                        "SELECT GROUP_CONCAT(lv SEPARATOR ',') as uids FROM (
                            SELECT @pv:=(SELECT GROUP_CONCAT(pk_i_admin_id SEPARATOR ',') FROM tbl_admin 
                            WHERE FIND_IN_SET(report_to, @pv)) AS lv FROM tbl_admin 
                            JOIN
                            (SELECT @pv:=$uid) tmp
                            ) a;"                        
                        );
        $res    =   $query->row_array();
        $arr = array();
        if (!empty($res['uids'])) {
            $res = $res['uids'];
            $arr = explode(',', $res);            
        }
        array_push($arr, $uid);        
        return $arr;
    }
    
    public function get_categories($user_id){  
        if(!empty($this->session->user_tree)){
            return $this->session->user_tree;
        }else{
            $this->db->select('pk_i_admin_id,sibling_id');
            $user_row  = $this->db->where('pk_i_admin_id',$user_id)->get('tbl_admin')->row_array();
            $sibling_id = 0;
            if(!empty($user_row['sibling_id'])){
                $user_id = $user_row['sibling_id'];
                $sibling_id = $user_row['sibling_id'];
            }
            
            $this->list = array();
            $categories = array();
            $this->db->select('pk_i_admin_id');
            $this->db->from('tbl_admin');
            $this->db->where('report_to',$user_id);
            $parent = $this->db->get();       
            
            $categories = $parent->result();
            
            $i=0;
            foreach($categories as $p_cat){
                $categories[$i]->sub = $this->sub_categories($p_cat->pk_i_admin_id);
                $i++;
            }
            
            $categories    =   $this->fetch_recursive($categories);
            
            array_push($categories, $user_id);
            if($sibling_id){
                array_push($categories, $sibling_id);
            }
            
            return array_unique($categories);
        }         
    }


    public function tree_get_categories($user_id){   

        $categories = array();
        $this->db->select('pk_i_admin_id');
        $this->db->from('tbl_admin');
        $this->db->where('report_to',$user_id);
        $parent = $this->db->get();       

        $categories = $parent->result();

        $i=0;
        foreach($categories as $p_cat){
            $categories[$i]->sub = $this->sub_categories($p_cat->pk_i_admin_id);
            $i++;
        }
        
        //$categories    =   $this->fetch_recursive($categories);

        //array_push($categories, $user_id);
        
        return $categories;
    }

    public function sub_categories($id){
        $this->db->select('pk_i_admin_id');
        $this->db->from('tbl_admin');
        $this->db->where('report_to', $id);
        $child = $this->db->get();
        $categories = $child->result();
        $i=0;
        foreach($categories as $p_cat){
            $categories[$i]->sub = $this->sub_categories($p_cat->pk_i_admin_id);
            $i++;
        }
        return $categories;       
    }


    /* User reporting functions end */

    public function get_user_product_list(){
        $this->db->select('process');
        $this->db->where('pk_i_admin_id',$this->session->user_id);
        $user_process   =   $this->db->get('tbl_admin')->row_array();
        // print_r($user_process);exit();
        if(!empty($user_process)){
            $user_process = $user_process['process'];
            $user_process   =   explode(',', $user_process);
        }else{
            $user_process = array();
        }
        $company=$this->session->userdata('companey_id');
        // echo $company;
        $this->db->select('*');
        $this->db->from('tbl_product');         
        $this->db->where_in('sb_id',$user_process);
        $this->db->where('comp_id', $company);
        $this->db->order_by('sb_id','ASC');
        return  $this->db->get()->result();
        // print_r($res);exit();
    }

    public function get_process_name_by_id($id){
        $this->db->select('product_name as process_name');
        $this->db->where('sb_id',$id);
        $row = $this->db->get('tbl_product')->row_array();
        return $row['process_name']??false;
    }

    public function getUsers($main_uid,$comp_id)
    {
        $all_ids = $this->get_categories($main_uid);

        $this->db->where('pk_i_admin_id IN ('.implode(',',$all_ids).')');
        $this->db->where('b_status',1);
        $res = $this->db->where('companey_id',$comp_id)->get('tbl_admin')->result();
        return $res;
    }
}
