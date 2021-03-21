<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Deal_dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();    
        $this->load->model(array('deal_model'));
    }

    public function index() {
        $data['title'] = 'Deal Dashboard';
        $data['urls'] = array(
            'deal_status' => base_url().'deal_dashboard/deal_status_feed',
            'booking_type' => base_url().'deal_dashboard/booking_type_feed',
            'product_feed' => base_url().'deal_dashboard/product_feed',
            //'approaval_status' => base_url().'deal_dashboard/approaval_status_feed',
            'country_wise' => base_url().'deal_dashboard/country_wise_feed',
            'region_wise' => base_url().'deal_dashboard/region_wise_feed',
            'branch_wise' => base_url().'deal_dashboard/branch_wise_feed',
            'waight_wise' => base_url().'deal_dashboard/waight_wise_feed',
            'freight_wise' => base_url().'deal_dashboard/freight_wise_feed'
        );
        // $data['content'] = 
        $this->load->view('graphs/deal/index',$data);
        //$this->load->view('layout/main_wrapper',$data);
    }

    public function dashboard() {
        $data['title'] = 'Deal Dashboard';
        $data['urls'] = array(
            'deal_status' => base_url().'deal_dashboard/deal_status_feed',
            'booking_type' => base_url().'deal_dashboard/booking_type_feed',
            'product_feed' => base_url().'deal_dashboard/product_feed',
            //'approaval_status' => base_url().'deal_dashboard/approaval_status_feed',
            'country_wise' => base_url().'deal_dashboard/country_wise_feed',
            'region_wise' => base_url().'deal_dashboard/region_wise_feed',
            'branch_wise' => base_url().'deal_dashboard/branch_wise_feed',
            'waight_wise' => base_url().'deal_dashboard/waight_wise_feed',
            'freight_wise' => base_url().'deal_dashboard/freight_wise_feed'
        );
        // $data['content'] = 
        $data['content'] = $this->load->view('graphs/deal/index',$data,true);
        $this->load->view('layout/main_wrapper',$data);
    }

    public function deal_status_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->deal_status_feed());       
        $this->load->view('graphs/deal/deal_status',$data);
    }

    public function booking_type_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->booking_type_feed());       
        $this->load->view('graphs/deal/booking_type',$data);
    }

    public function product_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->product_feed());       
        $this->load->view('graphs/deal/product_deal',$data);
    }

    public function approaval_status_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->deal_status_feed());       
        $this->load->view('graphs/deal/approaval_status',$data);
    }
    public function country_wise_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->country_wise_feed());       
        $this->load->view('graphs/deal/country_wise',$data);
    }

    public function region_wise_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->deal_status_feed());       
        $this->load->view('graphs/deal/region_wise',$data);
    }
    

    public function branch_wise_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->deal_status_feed());       
        $this->load->view('graphs/deal/branch_wise',$data);
    }

    public function waight_wise_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->deal_status_feed());       
        $this->load->view('graphs/deal/waight_wise',$data);
    }
    public function freight_wise_feed($filter=array()){
        $data['feed'] = json_encode($this->deal_model->deal_status_feed());       
        $this->load->view('graphs/deal/freight_wise',$data);
    }
}