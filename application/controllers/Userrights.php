<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Userrights extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'User_model'
        ));
     if(empty($this->session->userdata('isLogIn')))
        redirect('login');
    }
    public function index() {
        if (!empty($_GET['user_role'])) {
            $data['title'] = $this->User_model->get_role_name_by_id($_GET['user_role']).' List';            
        }else{
            $data['title'] = "Module Wise User Rights";
        }
        $data['departments'] = $this->User_model->read2();
        //echo $this->db->last_query();
        $data['user_role'] = $this->db->get('all_modules')->result();
        //print_r($data['user_role']);die;
        $data['content'] = $this->load->view('userrights', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    
    public function userrights_edit() {
        $role_id=$this->uri->segment(3);
        // if (!empty($_GET['user_role'])) {
        //     $data['title'] = $this->User_model->get_role_name_by_id($_GET['user_role']).' List';            
        // }else{
        //     $data['title'] = "Module Wise User Rights";
        // }
        $data['departments'] = $this->User_model->read2();
        //echo $this->db->last_query();
        $data['user_role'] = $this->db->get('all_modules')->result();
        $data['userRole'] = $this->User_model->get_user_role($role_id);
        // print_r($user_role->user_permissions);
       $data['user_rights'] = $this->db->get('tbl_user_role')->result_array();
        $data['content'] = $this->load->view('userrights-edit', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
    public function create()
    {   
        if(!empty($_POST))
        {
            if(empty($this->input->post('rightid')))
            {
                $added = $this->db->select('id')->from('modulewise_right')->where('module_id',$this->input->post('module'))->get()->num_rows();
                $data = array(
                    'right_id'      => $this->input->post('module').''.$added,
                    'name'          => $this->input->post('title'),
                    'module_id'     => $this->input->post('module'),
                    'created_at'    => date("Y-m-d H:i:s"),
                    'updated_at'    => date("Y-m-d H:i:s"),
                );
                // $this->User_model->addData('modulewise_right',$data);
                $this->db->insert('modulewise_right',$data);
                $this->session->set_flashdata('message','New right added successfully');
                redirect('Userrights');
            }
        }
        else
        {
            $data['title'] = "Module Wise User Rights";
            $data['user_role'] = $this->db->get('all_modules')->result();
            $data['content'] = $this->load->view('userrights_form', $data, true);
            $this->load->view('layout/main_wrapper', $data);
        }
        
    }
    
}
