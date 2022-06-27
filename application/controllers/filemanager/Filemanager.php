<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Filemanager extends CI_Controller {
    public function __construct(){
        parent::__construct();  
        
        if ($this->session->userdata('isLogIn') == false) 
        redirect('login'); 
    } 
    public function iframe_fun(){ 
        $data['title'] = 'File Manager';
        $this->load->view('filemanager/dialog',$data);
    } 
    public function index(){
        $data['title'] = 'File Manager';
        
        $data['content'] = $this->load->view('filemanager/master_page',$data,true);
        $this->load->view('layout/main_wrapper',$data);
    }
    public function upload(){
        $this->load->view('filemanager/upload');
    }

    public function uploadHandler(){

    }

    public function ajax_calls(){
        $this->load->view('filemanager/ajax_calls');
    }

    public function execute(){
        $this->load->view('filemanager/execute');
    }
}