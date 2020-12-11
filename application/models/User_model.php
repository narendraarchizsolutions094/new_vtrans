<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
    private $table = 'tbl_admin'; 

    public function create($data = []) {         
        $insert_id = 0;
        $where = '';
        if(!empty($data['s_user_email'])){
            $where = "s_user_email='".$data['s_user_email'].'\'';
        }
        if(!empty($data['s_phoneno'])){
            if(!empty($where)){
                $where .= " OR";
            }
            $where .= " s_phoneno='".$data['s_phoneno'].'\'';
        }
        $this->db->where($where);
        $user_row    =   $this->db->get('tbl_admin')->row_array();
        if (empty($user_row)) {            
            $this->db->insert($this->table, $data);
            $insert_id = $this->db->insert_id();
        }
        return $insert_id;
    }
    public function add_login_history(){
        $arr    =   array( 
                    "uid"   => $this->session->user_id,                    
                    "comp_id"     => $this->session->companey_id,
                 );
        $this->db->insert('login_history',$arr);
    }
    public function companyList() {
        $this->db->select("user_id,a_companyname");
        $this->db->from('user');
        $this->db->where('status', 1);
  
        return $this->db->get()->result(); 
    }

    public function get_report_locations(){
        
        $this->db->where('comp_id',$this->session->companey_id);
        return $this->db->get('reporting_location')->result_array();
    }

    

    public function read($user_right='') {        
        $this->load->model('common_model');
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);                      
        $this->db->select("*");
        $this->db->from($this->table); 
        // $this->db->join('user', 'user.user_id = tbl_admin.companey_id');
        $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_permissions', 'left');
        $where = "  tbl_admin.pk_i_admin_id IN (".implode(',', $all_reporting_ids).')';                
        $where .= "  AND tbl_admin.b_status=1";                                
        if (!empty($user_right)) {
            $where .= "  AND tbl_admin.user_permissions='".$user_right."'";                                            
        }

        $exclude = array();
        if($this->session->companey_id == 57){
            $exclude = array(200,201);
        }
        if(!empty($exclude) && empty($user_right)){
            foreach($exclude as $rid){
                $where .= "  AND tbl_admin.user_permissions!='".$rid."'";                                            
            }
        }
        $this->db->where($where);
        return $this->db->get()->result();
    }


    public function companey_users() {                
        $user_id = $this->session->user_id;
        $this->db->select("*");
        $this->db->from($this->table);        
        $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_permissions', 'left');        
        $this->db->where('tbl_admin.companey_id',$this->session->companey_id); 
        $this->db->where('tbl_admin.b_status',1);                                                
        return $this->db->get()->result();
    }
	
	public function user_read() {
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;
        $this->db->select("*");
        $this->db->from($this->table);
        //$this->db->join('user', 'user.user_id = tbl_admin.companey_id');
        $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_permissions', 'left');
		$this->db->where('tbl_admin.companey_id',$this->session->companey_id); 
        $this->db->where('tbl_admin.b_status=', 1);
        return $this->db->get()->result();
    }


    public function read2() {
        
        if (empty($_GET['user_role'])) {            
            $user_separation  = get_sys_parameter('user_separation','COMPANY_SETTING');
            $sep_arr=array();
            if (!empty($user_separation)) {
                $user_separation = json_decode($user_separation,true);
                foreach ($user_separation as $key => $value) { 
                    $sep_arr[] = $key;
                }
            }            
        }
        
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;
        $this->db->select("user.valid_upto,user.user_id,tbl_admin.*,tbl_user_role.*");
        $this->db->from($this->table);     
        $this->db->join('user', 'user.user_id = tbl_admin.companey_id');
        $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_type', 'left');
        if (!empty($sep_arr)) {
            $this->db->where_in("tbl_admin.user_type NOT",$sep_arr);            
        }
        if (!empty($_GET['user_role'])) {
            $this->db->where('tbl_admin.user_type', $_GET['user_role']);            
        }
        $this->db->where('tbl_admin.user_type!=', 1);
        $this->db->where('tbl_admin.companey_id',$this->session->companey_id);  
        return $this->db->get()->result();
    }

    public function get_role_name_by_id($id){
        $this->db->select('user_role');
        $this->db->where('comp_id',$this->session->companey_id);
        $this->db->where('use_id',$id);
        $res    =   $this->db->get('tbl_user_role')->row_array();
        if (!empty($res)) {
            return $res['user_role'];
        }else{
            return false;
        }
    }
    public function reads() {
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->join('user', 'user.user_id = tbl_admin.companey_id');
        $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_permissions', 'left');
        $this->db->where('tbl_admin.user_roles!=', 9);
        //	$this->db->where('tbl_admin.b_status',1);
        if ($user_role == 3) {
            $this->db->where('tbl_admin.user_roles>=', 3);
        } else if ($user_role == 4) {
            $this->db->where('tbl_admin.user_roles>=', 4);
        } else if ($user_role == 5) {
            $this->db->where('tbl_admin.user_roles>=', 5);
        } else if ($user_role == 6) {
            $this->db->where('tbl_admin.user_roles>=', 6);
        } else if ($user_role == 7) {
            $this->db->where('tbl_admin.user_roles>=', 7);
        } elseif ($user_role == 8 || $user_role == 9) {
            $this->db->where('tbl_admin.user_roles>=', 8);
        }
        return $this->db->get()->result();
    }

    public function display_company_details() {
        $this->db->select("*");
        $this->db->from('users');
        $this->db->join('tbl_user_role', 'tbl_user_role.use_id=users.user_permissions', 'left');
        return $this->db->get()->result();
    }

    public function partner() {
        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;


        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->join('user', 'user.user_id = tbl_admin.companey_id');
        $this->db->join('tbl_channel_partner', 'tbl_admin.partner_type = tbl_channel_partner.ch_id', 'left');
        $this->db->join('tbl_user_role', 'tbl_user_role.use_id=tbl_admin.user_permissions', 'left');
        $this->db->where('tbl_admin.user_roles', 9);
        //	$this->db->where('tbl_admin.b_status',1);
        return $this->db->get()->result();
    }

    public function all_user() {
        $this->db->select("*");
        $this->db->from($this->table);
        return $this->db->get()->result();
    }

    public function read_by_id($id = null) {
        return $this->db->select("*")
                        ->from($this->table)
                        ->where('pk_i_admin_id', $id)
                        ->get()
                        ->row();
    }

    public function all_lists() {
        $this->db->select("*");
        $this->db->from('tbl_admin');
        $this->db->where('b_status', 1);
        return $this->db->get()->result();
    }

    public function update($data = []) {
        return $this->db->where('pk_i_admin_id', $data['pk_i_admin_id'])
                        ->update($this->table, $data);
    }

    public function delete($dprt_id = null) {
        $this->db->where('pk_i_admin_id', $dprt_id)
                ->delete($this->table);

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function department_list() {
        $result = $this->db->select("*")
                ->from($this->table)
                ->where('status', 1)
                ->get()
                ->result();

        $list[''] = display('select_department');
        if (!empty($result)) {
            foreach ($result as $value) {
                $list[$value->dprt_id] = $value->name;
            }
            return $list;
        } else {
            return false;
        }
    }

    public function delete_userrole($user_role = null) {
        if(empty($this->session->userdata('isLogIn')))
        redirect('login');
        $this->db->where('use_id', $user_role)
                ->delete('tbl_user_role');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    //Select user to update..
    public function get_user_role($role_id) {

        return $this->db->select('*')
                        ->from('tbl_user_role')
                        ->where('use_id', $role_id)
                        ->get()
                        ->row();
    }
	
	public function get_user_role_cid() {
$company=$this->session->userdata('companey_id');
        return $this->db->select('*')
                        ->from('tbl_user_role')
                        ->where('comp_id', $company)
                        ->get()
                        ->result();
    }

    //Update User Role..
    public function update_user_role($id, $data) {

        return $this->db->where('use_id', $id)
                        ->update('tbl_user_role', $data);
    }

    //Display country list in user create form
    public function display_country() {

        return $this->db->select('*')
                        ->from('tbl_country')
                        ->where('comp_id',$this->session->userdata('companey_id'))
                        ->get()
                        ->result();
    }

    public function get_empid() {

        return ($this->db->select('pk_i_admin_id')->order_by('pk_i_admin_id', "desc")->limit(1)->get('tbl_admin')->row()->pk_i_admin_id) + 1;
    }

    //get user list
    public function user_list() {
        return $this->db->select('*')
                        ->from('tbl_admin')
                        ->where('companey_id',$this->session->companey_id)
                        ->get()
                        ->result();
    }

    public function types_list() {
        return $this->db->select('*')
                        ->from('tbl_partner_type')
                        ->get()
                        ->result();
    }

    public function update_inputfileds($arr,$id,$user_id){
    
       return $this->db->where('input_id', $id)
                        ->where('company_id',$user_id)
                        ->update('tbl_input', $arr);
    }


    public function get_input_basic_fields($comp_id,$field_for=0){
        if($field_for==2)
        {

            return $this->db->select('basic_fields.id,basic_fields.type,basic_fields.title,ticket_fileds_basic.status,ticket_fileds_basic.process_id,input_types.title as type_title')
                        ->from('basic_fields')
                        ->join('ticket_fileds_basic','ticket_fileds_basic.field_id=basic_fields.id','left')
                        ->join('input_types','input_types.id=basic_fields.type','inner')
                        ->where('basic_fields.field_for',$field_for)
                        ->group_by('basic_fields.id')

                        ->where('ticket_fileds_basic.comp_id',$comp_id)
                        ->order_by('ticket_fileds_basic.fld_order','ASC')
                        ->get()                        
                        ->result_array();
        }
        else
        {
            return $this->db->select('basic_fields.id,basic_fields.type,basic_fields.title,enquiry_fileds_basic.status,enquiry_fileds_basic.process_id,input_types.title as type_title')
                        ->from('basic_fields')
                        ->join('enquiry_fileds_basic','enquiry_fileds_basic.field_id=basic_fields.id','left')
                        ->join('input_types','input_types.id=basic_fields.type','inner')
                        ->where('basic_fields.field_for',$field_for)
                        ->group_by('basic_fields.id')

                        ->where('enquiry_fileds_basic.comp_id',$comp_id)
                        ->order_by('enquiry_fileds_basic.fld_order','ASC')
                        ->get()                        
                        ->result_array();
        }
    }

    /**
    * this method will get the user meta
    * @parm first parameter is user id integer, and second parameter array is data which you want to get of a user
    */
    public function get_user_meta($uid,$meta_key){
        if (!empty($meta_key) && !empty($uid)) {                        
            $this->db->select('parameter,value');
            $this->db->where('uid',$uid);        
            $this->db->where_in('parameter',$meta_key);
            $result    =   $this->db->get('user_meta')->result_array();
            $data = array();
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $parm    =   $value['parameter'];
                    $data[$parm]   = $value['value'];
                }
                return $data;   
            }else{
                return false;
            }
        }else{
            return false;
        }
    } 
    /**
    * this method will get the user meta
    * @parm first parameter is user id integer, and second parameter array is data which you want to set of a user
    */
    public function set_user_meta($uid,$meta_key){
        if (!empty($meta_key) && !empty($uid)) {
            foreach ($meta_key as $key=>$value) {
                $this->db->where('parameter',$key);
                $this->db->where('uid',$uid);
                $this->db->from('user_meta');
                if($this->db->count_all_results()){
                    $this->db->where('uid',$uid);
                    $this->db->where('parameter',$key);
                    $this->db->set('value',$value);
                    $this->db->update('user_meta');
                }else{
                    $ins_arr = array(
                                'uid'      => $uid,
                                'parameter'=> $key,
                                'value'    => $value,
                                );
                    $this->db->insert('user_meta',$ins_arr);
                }
            }
        }else{
            return false;
        }
    }


    
    
    public function get_user_by_email($email){
        if(!empty($email)){
            $where = "s_user_email='".$email."' OR s_phoneno='".$email.'\'';
            return $this->db->where($where)->get('tbl_admin')->row();            
        }else{
            return false;
        }
    }
    public function get_user_by_phone($phone){
        return $this->db->where('s_phoneno',$phone)->get('tbl_admin')->row();
    }

//update user data
public function ChangeStatus($data,$user_id,$com_id)
{
   $update= $this->db->where(array('pk_i_admin_id'=>$user_id,'companey_id'=>$com_id));
    $update=$this->db->update('tbl_admin',$data);
    return $update;

}


}

