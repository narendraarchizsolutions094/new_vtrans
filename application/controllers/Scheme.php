<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Scheme extends CI_Controller {
public function __construct() {
        parent::__construct();
        $this->load->model(array('warehouse_model','scheme_model','user_model','location_model','common_model') );
        $this->load->library('form_validation');
     //   $this->load->library('file_upload');
        $this->load->helper("url");
      //  $this->load->library("pagination");
      //  $this->load->library('cart');
        
    }
    public function index(){
        $data['pscheme_list'] = $this->scheme_model->get_pro_scheme();
        $data['rg_list'] = $this->scheme_model->get_region_scheme();
        $data['pay_list'] = $this->scheme_model->get_payment_scheme();
     	$data['content'] = $this->load->view('scheme/scheme_list', $data, true);
        $this->load->view('layout/main_wrapper', $data);
    }
  public function  add_scheme(){
	 $this->load->model("product_model");
     $data['brand'] = $this->warehouse_model->brand_list();
     $data['pro_list'] = $this->product_model->productlist();
     //$data['utype_list'] = $this->user_model->usertype_list();
     //$data['region'] = $this->location_model->region_list();
	 
	 	$data['content'] = $this->load->view('scheme/scheme_form', $data, true);
        $this->load->view('layout/main_wrapper', $data);
  }
  public function add(){
	  
	   if(isset($_POST["fromdate"])){
		  
		  $this->updatescheme();
		  redirect(base_url("scheme/add"), "refrersh");
	  }
	  $this->load->model("product_model");
	  $data['brand'] = $this->warehouse_model->brand_list();
      $data['pro_list'] = $this->product_model->productlist();
		$data['content'] = $this->load->view('scheme/add-scheme', $data, true);
        $this->load->view('layout/main_wrapper', $data);
	  
  }	
  public function edit_scheme($cpn = ""){
	  
	  if(isset($_POST["fromdate"])){
		   
		  $this->updatescheme();
	  }
    $this->load->model("product_model");
	  
	   $data['pro_list'] = $this->product_model->productlist();
     //$data['utype_list'] = $this->user_model->usertype_list();
     //$data['region'] = $this->location_model->region_list();
	 //$data['paymode'] = $this->db->select("*")->where("company", $this->session->company)->where("type",3)->get("masters")->result();
	 //$data["alldata"] = $this->warehouse_model->getalldata();  
	 //$data["schemes"] = $this->scheme_model->getSchemeByCoupan($cpn);
	 
	 $data["schm"]    = (!empty($data["schemes"][0])) ? $data["schemes"][0] : ""; 
      $data['content'] = '';
      //$this->load->view('scheme/update-scheme',true,$data);
      $this->load->view('layout/main_wrapper',$data);
  } 
	public function updatescheme(){
		
		    $this->form_validation->set_rules('fromdate', "From Date", 'required');
			$this->form_validation->set_rules('todate', "To Date", 'required');
			
			if($this->form_validation->run()==true){
				
				$fromqty  = $this->input->post('fromqty');
				$toqty    = $this->input->post('toqty');
				$fromdate = $this->input->post('fromdate');
				$todate   = $this->input->post('todate');
				$dis      = $this->input->post('discount');
			
			$i=0;
			$res='';
			
				$schaparr = $this->input->post("schmapply", true);
				$spcval = "";
				
				$prod_typ = $prod_val = $loc_typ = $loc_val = $pay_typ = $pay_val =   $user_type = $user_val = "";
				
	
				
				$allapply = (!empty($_POST["schmapply"])) ? implode(",", $this->input->post("schmapply", true)) : ""; 
				$coupancode = $this->generateRandomString(10);
				if(isset($_POST["applyonqty"])) {
					foreach($fromqty as $i => $frmqty){
					   $data = array(
					   'from_date' => $this->common_model->cleandate('fromdate'),
					   'to_date'   => $this->common_model->cleandate('todate'),
					   'apply_qty' => 1,
					   'from_qty'  => $frmqty,
					   'calc_type' => $this->input->post("calctype", true),
					   'calc_mth'  => $this->input->post("calcmeth", true),	
					   'to_qty'   => $toqty[$i],
					   'discount'  => $dis[$i],
					   'added_by'  => $this->session->user_id,
					   'status'   =>1,
					   'scheme_type' =>1
					   );
					   $res = $this->scheme_model->insert('tbl_scheme',$data);
					   $i++;
					}
				}else{
				   $data = array('from_date' => $this->common_model->cleandate('fromdate'),
								   'to_date'   => $this->common_model->cleandate('todate'),
								   'apply_qty' => 0,
								   'from_qty'  => 0,
								   'scm_apply' => $schap,
								   'scm_field' => $schfld,
								   'scm_specific_flld' => $spcval,
								   'calc_type' => $this->input->post("calctype", true),
								   'calc_mth'  => $this->input->post("calcmeth", true),	
								   'to_qty'    => 0,
								   'discount'  => $dis[0],
								   );
						if(isset($_POST["schemeno"])) {	
							$this->db->where("id", $this->input->post("schemeno", true));
							$this->db->update("tbl_scheme", $data);
							$res  = $this->db->affected_rows();
							$update = true;
						}else{
							$data['coupan']	   = $coupancode;	
							$data['comp_id']   = $this->session->company;
							$data["created_date"] = date("Y-m-d h:i:s");
							$data["status"]    = 1;
							
							$res = $this->scheme_model->insert('tbl_scheme',$data);
							$update = false;
						}
				}
			
			if($res){
				$this->session->set_flashdata("message", "Successfuly Added Schemes");
				if($update == true) {
					redirect(base_url('scheme/add'), "refresh");
				}else{
					redirect(base_url('scheme/update/'.$coupancode), "refresh");
				}
			}
		 }
		 else{
			redirect(base_url('scheme/add_scheme'));
		  }
		
	}
  
  public function get_pro_bybrand(){
        $brand = $this->input->post('brand');
        $data['pro'] = $this->scheme_model->get_pro_bybrand($brand);
        echo '<option value="">Select</option>';
        foreach ($data['pro'] as $r) {
            echo '<option value="' . $r->id . '">' . $r->pro_name . '</option>';
        }
  }

  public function add_product_scheme(){
    $this->form_validation->set_rules('fromdate', "From Date", 'required');
    $this->form_validation->set_rules('todate', "To Date", 'required');
    
    if($this->form_validation->run()==true){
		
		$fromqty  = $this->input->post('fromqty');
		$toqty    = $this->input->post('toqty');
		$fromdate = $this->input->post('fromdate');
		$todate   = $this->input->post('todate');
		$dis      = $this->input->post('discount');
		$brand    = $this->input->post('brand');
		$pro      = $this->input->post('product');
		$i=0;
		$res='';
		
		$schaparr = $this->input->post("schmapply", true);
		$spcval = "";
		
		$coupancode = $this->generateRandomString(10);
		
		
	
		
		if(isset($_POST["applyonqty"])) {
			foreach($fromqty as $i => $frmqty){
			   $data = array(
			   'coupan'	   => $coupancode,	
			   'comp_id'   => $this->session->companey_id,
			   'from_date' => $this->cleandate('fromdate'),
			   'to_date'   => $this->cleandate('todate'),
			   'apply_qty' => 1,
			   'from_qty' => $frmqty,
			   'calc_type' => $this->input->post("calctype", true),
			   'calc_mth'  => $this->input->post("calcmeth", true),	
			   'to_qty'   => $toqty[$i],
			   'discount'  => $dis[$i],
			   'added_by'  => $this->session->user_id,
			   'status'   =>1,
			   'scheme_type' =>1,
			   );
			   $res = $this->scheme_model->insert('tbl_scheme',$data);
			   $i++;
			}
		}else{
					   $data = array(
			   'coupan'	   => $coupancode,	
			   'comp_id'   => $this->session->companey_id,
			   'from_date' => $this->cleandate('fromdate'),
			   'to_date'   => $this->cleandate('todate'),
			   'apply_qty' => 0,
			   'from_qty'  => 0,
			   'calc_type' => $this->input->post("calctype", true),
			   'calc_mth'  => $this->input->post("calcmeth", true),	
			   'to_qty'    => 0,
			   'discount'  => $dis[0],
			   'added_by'  => $this->session->user_id,
			   'status'   =>1,
			   'scheme_type' =>1,
			   );
			   $res = $this->scheme_model->insert('tbl_scheme',$data);
			
		}
	
    if($res){
		$this->session->set_flashdata("message", "Successfuly Added Schemes");
    redirect(base_url('scheme'));
    }
 }
 else{
    redirect(base_url('scheme/add_scheme'));
  }
  }
  	public function cleandate($post){
		
		$pdate = $this->input->post($post, true);
		
		if(empty($pdate)) {
			return NULL;
		}
		$pdate = str_replace("/", "-", $pdate);
		
		$darr  = explode("-", $pdate); 
		
		return date("Y-m-d", strtotime($darr[2]."-".$darr[0]."-".$darr[1]));
		
	}
  function generateRandomString($length = 10) {
	  
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
  public function add_region_scheme(){

    $this->form_validation->set_rules('fromdate', "From Date", 'required');
    $this->form_validation->set_rules('todate', "To Date", 'required');
    
    if($this->form_validation->run()==true){
    $fromqty  = $this->input->post('fromqty');
    $toqty    = $this->input->post('toqty');
    $fromdate = $this->input->post('fromdate');
    $todate   = $this->input->post('todate');
    $dis      = $this->input->post('discount');
    $region    = $this->input->post('region');
    $utype      = $this->input->post('utype');
    $i=0;
    $res='';
    foreach($fromqty as $frmqty){
       $data = array(
       
       'comp_id'    => $this->session->company,
       'from_date'  => $fromdate,
       'to_date'    => $todate,
       'region'     => $region,
       'user_type'  => $utype,
       'from_qty'   => $frmqty,
       'to_qty'     => $toqty[$i],
       'discount'   => $dis[$i],
       'added_by'   => $this->session->user_id,
       'status'     =>1,
       'scheme_type'=>2
       );
       $res = $this->scheme_model->insert('tbl_scheme',$data);
       $i++;
    }
    if($res){
   
    redirect(base_url('scheme'));
    }
 }
 else{
    redirect(base_url('scheme/add_scheme'));
  }
  }

   public function add_payment_scheme(){

    $this->form_validation->set_rules('fromdate', "From Date", 'required');
    $this->form_validation->set_rules('todate', "To Date", 'required');
    
    if($this->form_validation->run()==true){
    $fromqty  = $this->input->post('fromqty');
    $toqty    = $this->input->post('toqty');
    $fromdate = $this->input->post('fromdate');
    $todate   = $this->input->post('todate');
    $dis      = $this->input->post('discount');
    $mop    = $this->input->post('mop');
    $i=0;
    $res='';
    foreach($fromqty as $frmqty){
       $data = array(
       
       'comp_id'    => $this->session->company,
       'from_date'  => $fromdate,
       'to_date'    => $todate,
       'mop'        => $mop,
       'from_qty'   => $frmqty,
       'to_qty'     => $toqty[$i],
       'discount'   => $dis[$i],
       'added_by'   => $this->session->user_id,
       'status'     =>1,
       'scheme_type'=>3
       );
       $res = $this->scheme_model->insert('tbl_scheme',$data);
       $i++;
    }
    if($res){
   
    redirect(base_url('scheme'));
    }
 }
 else{
    redirect(base_url('scheme/add_scheme'));
  }
  }
  public function edit_proscheme($id){
     $data['brand'] = $this->warehouse_model->brand_list();
     $data['pro_list'] = $this->warehouse_model->product_list();
     $data['proscheme'] = $this->scheme_model->getSchemeByCoupan($id);
	 
	 if(empty($data['proscheme'])) show_404();
	 
     $this->load->template('scheme/pro_scheme_update',$data);
  }

  public function update_product_scheme(){
    $this->form_validation->set_rules('fromdate', "From Date", 'required');
    $this->form_validation->set_rules('todate', "To Date", 'required');
    
    if($this->form_validation->run()==true){
       
       $id = $this->input->post('pscheme_id');
        $data = array(
        
        'from_date'=>$this->input->post('fromdate'),
        'to_date'=>$this->input->post('todate'),
        'brand'=>$this->input->post('brand'),
        'product'=>$this->input->post('product'),
        'from_qty'=>$this->input->post('fromqty'),
        'to_qty'=>$this->input->post('toqty'),
        'discount'=>$this->input->post('discount'),
        'status'  =>$this->input->post('status')
        );
      $res = $this->scheme_model->update($data,$id);
      if($res){
  
        $this->session->set_flashdata('message','Updated Successfuly');
        redirect(base_url('scheme'));
      }
    }
    else{
        redirect(base_url('scheme/edit_proscheme'.$id));
    }
  }
  public function delete_scheme($id){
      if ($this->scheme_model->delete($id)) {
            #set success message
            
            $this->session->set_flashdata('message', 'Deleted successfully');
        } else {
            #set exception message
            $this->session->set_flashdata('exception','Please try again');
        }
        redirect(base_url('scheme'));
  }
    public function edit_rescheme($id){
     $data['utype_list'] = $this->user_model->usertype_list();
     $data['region'] = $this->location_model->region_list();
     $data['regscheme'] = $this->scheme_model->get_scheme_byid($id);
     $this->load->template('scheme/reg_scheme_update',$data);
  }
public function update_region_scheme(){
    $this->form_validation->set_rules('fromdate', "From Date", 'required');
    $this->form_validation->set_rules('todate', "To Date", 'required');
    $id = $this->input->post('pscheme_id');
    
    if($this->form_validation->run()==true){
       
        $data = array(
        
        'from_date'=>$this->input->post('fromdate'),
        'to_date'=>$this->input->post('todate'),
        'region'=>$this->input->post('region'),
        'user_type'=>$this->input->post('utype'),
        'from_qty'=>$this->input->post('fromqty'),
        'to_qty'=>$this->input->post('toqty'),
        'discount'=>$this->input->post('discount'),
        'status'  =>$this->input->post('status')
        );
      $res = $this->scheme_model->update($data,$id);
      if($res){
  
        $this->session->set_flashdata('message','Updated Successfuly');
        redirect(base_url('scheme'));
      }
    }
    else{
        redirect(base_url('scheme/edit_rescheme'.$id));
    }
  }
  public function edit_paycheme($id){
     $data['payscheme'] = $this->scheme_model->get_scheme_byid($id);
     $this->load->template('scheme/pay_scheme_update',$data);
  }
  public function update_pay_scheme(){
    $this->form_validation->set_rules('fromdate', "From Date", 'required');
    $this->form_validation->set_rules('todate', "To Date", 'required');
    $id = $this->input->post('pscheme_id');
    
    if($this->form_validation->run()==true){
       
        $data = array(
        
        'from_date'=>$this->input->post('fromdate'),
        'to_date'=>$this->input->post('todate'),
        'mop'=>$this->input->post('mop'),
        'from_qty'=>$this->input->post('fromqty'),
        'to_qty'=>$this->input->post('toqty'),
        'discount'=>$this->input->post('discount'),
        'status'  =>$this->input->post('status')
        );
      $res = $this->scheme_model->update($data,$id);
      if($res){
  
        $this->session->set_flashdata('message','Updated Successfuly');
        redirect(base_url('scheme'));
      }
    }
    else{
        redirect(base_url('scheme/edit_paycheme'.$id));
    }
  }

} ?>