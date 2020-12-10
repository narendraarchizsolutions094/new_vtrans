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
    
    public function get_categories($user_id){   

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
        
        return array_unique($categories);
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
}
