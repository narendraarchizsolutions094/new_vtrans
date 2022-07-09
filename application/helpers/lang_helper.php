<?php 

defined('BASEPATH') OR exit('No direct script access allowed');



if (!function_exists('display')) {



    function display($text = null,$comp_id=0)
    {		
        $ci =& get_instance();
		$cmpno = !empty($comp_id)?$comp_id:$ci->session->companey_id;		
        $ci->load->database();
        $table  = 'language';
        $phrase = 'phrase';
        $setting_table = 'setting';
        $default_lang  = 'english';
        //set language  
		if(!empty($cmpno) and $cmpno > 0){			
			$qry = "SELECT * FROM $setting_table WHERE comp_id = '$cmpno' OR comp_id = '0' ORDER BY comp_id DESC";
			$data = $ci->db->query($qry)->row();     		
		}else{			
			$data = $ci->db->get($setting_table)->row();			
		}
        if (!empty($data->language)) {
            $language = $data->language; 
        } else {
            $language = $default_lang; 
        } 
        if (!empty($text)) {
            if ($ci->db->table_exists($table)) { 
                if ($ci->db->field_exists($phrase, $table)) { 
                    if ($ci->db->field_exists($language, $table)) {
						if(!empty($cmpno) and $cmpno > 0){							
							$ordby = "desc";							
						}else{
							$ordby = "asc";
						}
                        
                        if(!empty($ci->session->process[0]) && $ci->session->process[0] == 199)
                        {
                            $process_id = $ci->session->process[0];
                            $qry = "SELECT $language FROM $table WHERE $phrase = '$text' AND (FIND_IN_SET('$process_id',process_id)) > 0";
                            $row = $ci->db->query($qry)->row();  
                        }
                        if(empty($row)){
                            $qry = "SELECT $language FROM $table WHERE $phrase = '$text' AND (comp_id = '0' OR comp_id = '$cmpno') AND (process_id='' OR process_id IS NULL OR process_id = 0) ORDER BY comp_id $ordby";
                            $row = $ci->db->query($qry)->row();	  
                        }
                        //echo $ci->db->last_query();                        

                        if (!empty($row->$language)) {
                            return $row->$language;
                        } else {
                            return $text;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }            
        } else {
            return false;
        }  
    }
     function user_role($text = null){  
        $ci =& get_instance();
        $ci->load->database();       
        
        if($ci->session->companey_id == 57 && ($ci->session->app_type == 'buyer' || $ci->session->app_type == 'seller')){            
            if($ci->session->app_type == 'buyer'){
                $user_right = 201;
            }elseif($ci->session->app_type == 'seller'){
                $user_right = 200;
            }
            $panel_menu = $ci->db->select("tbl_user_role.user_permissions")
             ->where('tbl_user_role.use_id',$user_right)
             //->join('tbl_user_role','tbl_user_role.use_id=tbl_admin.user_permissions')
             ->get('tbl_user_role')
             ->row();
        }else{            
            $panel_menu = $ci->db->select("tbl_user_role.user_permissions")
             ->where('pk_i_admin_id',$ci->session->user_id)
             ->join('tbl_user_role','tbl_user_role.use_id=tbl_admin.user_permissions')
             ->get('tbl_admin')
             ->row();
        }


        if (!empty($panel_menu->user_permissions)) {
                $module=explode(',',$panel_menu->user_permissions);
            if (in_array($text,$module)){
                return true;
            }else{
                redirect('restrected');   
            }
        } else {
           redirect('restrected'); 
        } 
    }
    function user_access($text = null)
    {  
        $ci =& get_instance();
        if(empty($ci->session->permission_ids)){
            $ci->load->database();
            $panel_menu = $ci->db->select("tbl_user_role.user_permissions")
                ->where('pk_i_admin_id',$ci->session->user_id)
                ->join('tbl_user_role','tbl_user_role.use_id=tbl_admin.user_permissions')
                ->get('tbl_admin')
                ->row();
            $user_permissions = $panel_menu->user_permissions;
        }else{
            $user_permissions = $ci->session->permission_ids;
        }
        if (!empty($user_permissions)) {
             $module=explode(',',$user_permissions);
            if (in_array($text,$module)){
                return true;
            } else{
                return false;  
            }
        } else {
            return false; 
        } 
    }
	
	function get_val($id,$id2){
		$ci =& get_instance();
        $ci->load->database();	
		$ci->db->select("fvalue");
		$ci->db->from("extra_enquery");
		$ci->db->where("extra_enquery.input",$id);
		$ci->db->where("extra_enquery.enq_no",$id2);
		$res = $ci->db->get()->row();
		if(!empty($res)){
			return $res->fvalue;
		}	
	}

    function update_input_val($id,$id2,$input_val){
		$ci =& get_instance();
        $ci->load->database();	
		$ci->db->where("extra_enquery.input",$id);
		$ci->db->where("extra_enquery.enq_no",$id2);
		$ci->db->set("extra_enquery.fvalue",$input_val);
		$res = $ci->db->update("extra_enquery");		
			return $res;
		}	
	}
    
    function generate_invoice_id(){
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('invoice_code');
        $ci->db->where('comp_id',$ci->session->companey_id);
        $ci->db->order_by('id','desc');
        $ci->db->limit(1);
        $row_arr    =   $ci->db->get('invoice')->row_array();
    
        // print_r($row_arr);        
        // exit();
    }

    function set_sys_parameter($sys_para,$sys_value,$type){
        $ci =& get_instance();
        $ci->load->database();

        $ci->db->where('comp_id',$ci->session->companey_id);
        $ci->db->where('sys_para',$sys_para);
        $ci->db->where('type',$type);
        if($ci->db->get('sys_parameters')->num_rows()){
            $ci->db->set('sys_value',$sys_value);
            $ci->db->where('comp_id',$ci->session->companey_id);
            $ci->db->where('sys_para',$sys_para);
            $ci->db->where('type',$type);
            $ci->db->update('sys_parameters');
        }else{            
            $inser_arr = array(
                    'comp_id'   =>  $ci->session->companey_id,
                    'sys_para'  =>  $sys_para,
                    'sys_value' =>  $sys_value,
                    'type'  =>  $type,
                    'created_by'=>  $ci->session->user_id
                );
            $ci->db->insert('sys_parameters',$inser_arr);
        }
    } 

    function get_sys_parameter($sys_para,$type,$comp_id=0){
        $ci =& get_instance();
        $ci->load->database();
        $comp_id = $ci->session->companey_id??$comp_id;
        $ci->db->where('comp_id',$comp_id);
        $ci->db->where('sys_para',$sys_para);
        $ci->db->where('type',$type);
        $row_array = $ci->db->get('sys_parameters')->row_array();        
        if(!empty($row_array)){
            return $row_array['sys_value'];
        }else{
            return false;
        }
    }


    function get_process_name($id) {
    $ci = & get_instance();
    $ci->load->database();
    return $ci->db->get_where('tbl_product', array('sb_id'    => $id))->row_array() ['product_name'];

}