<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Deals extends REST_Controller {
  	function __construct() {
      parent::__construct();
      $this->load->library('form_validation');
	  $this->load->model(array('enquiry_model','common_model','Leads_Model'));
  }

  //================= Only for Deal List==================
   public function stage_deals_list_post()
    {
      $current_stg = $this->input->post('current_stg');
	  $next_stg = $this->input->post('next_stg');
      $enq_id = $this->input->post('enq_id');
	  
	    $enq = $this->db->select('enquiry_id')
                        ->from('enquiry')
                        ->where('Enquery_id',$enq_id)
                        ->get()->row();
        //print_r($enq->enquiry_id);exit;
		$enq_code = $enq->enquiry_id;
        $all_deals_lists = $this->Leads_Model->dealstagelist($enq_code,$current_stg,$next_stg);
		// echo "<pre>";
		// print_r($all_deals_lists);
		// exit();
        if(!empty($all_deals_lists)){
            $res= array();
            foreach($all_deals_lists as $deals)
            {
			  $deal_name = $deals->quatation_number;
			  array_push($res,array('id'=>$deals->id,'name'=>$deal_name,'deal_status'=>$deals->deal_status));
            } 
            $this->set_response([
                'status' => TRUE,
                'data' =>$res
                 ], REST_Controller::HTTP_OK);
        }else{	    
	        $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
	    }
    }
    //================= Only for Deal List==================
    //================= Only for V-trans==================
  	public function deals_list_page_post()
    {
      $user_id= $this->input->post('user_id');
      $process_id= $this->input->post('process_id');
      $company_id = $this->input->post('company_id');
      $offset = $this->input->post('offset')??0;
      $limit = $this->input->post('limit')??10;

      if(strpos(',',$process_id) !== false) 
      {
        $process = implode(',',$process_id);
      }
      else
      {
        $process = $process_id;
      }


       $res= array();
    
        $total = $this->enquiry_model->deal_list_api($company_id,$user_id,$process)->num_rows();
             
              //echo $offset; exit();
        $data['result'] = $this->enquiry_model->deal_list_api($company_id,$user_id,$process,$limit,$offset);
                  
          if(!empty($data['result']->result()))
          {
            $res= array();
            
            $res['offset'] = $offset;
            $res['limit'] = $limit;
            $res['total'] = $total;
            $res['list'] = array();

            
            foreach($data['result']->result() as $value)
            {
              array_push($res['list'],array('id'=>$value->id,'name'=>$value->name,'enquery_id'=>$value->enquiry_id,'booking_type'=>$value->booking_type,'business_type'=>$value->business_type.'ward','deal_type'=>$value->deal_type,'company'=>$value->company_name,'client_name'=>$value->client_name,'status'=>$value->status,'creation_date'=>$value->creation_date,'quotation_url'=>base_url('uploads/quotations/quotations_').$value->id.'.pdf','edited'=>$value->edited));  
            } 
            $this->set_response([
                'status' => TRUE,
                'data' =>$res
                 ], REST_Controller::HTTP_OK);
          }   
		else
         {
	    
	        $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
	      }
    }

    public function deal_details_post()
    {
    	$id = $this->input->post('deal_id');

    	$value = $this->db->select('commercial_info.*,bb.branch_name as bb_name,db.branch_name as db_name')
    		->join('branch as bb','bb.branch_id=commercial_info.booking_branch','left')
    		->join('branch as db','db.branch_id=commercial_info.delivery_branch','left')
    		->where('commercial_info.id',$id)->get('commercial_info')->row();

    	if(!empty($value))
    	{
    		$extra = (array)$value;

    	if($value->branch_type==1){
	        $branch_type='Branch';
	       }elseif($value->branch_type==2){
	        $branch_type='Zone';
	       }elseif($value->branch_type==3){
	        $branch_type='Area wise';
	       }
	       if($value->business_type==0){
	        $business_type='Inward';
	       }elseif($value->business_type==1){
	        $business_type='Outword';
	       }
	       if($value->insurance==0){
	        $insurance='Carrier';
	       }elseif($value->insurance==1){
	        $insurance='Owner Risk';
	       }
	       if($value->booking_type==0){
	        $booking_type='Sundry';
	       }elseif($value->booking_type==1){
	        $booking_type='FTL';
	       }
	       
	       if($value->paymode==1){
	        $paymode='Paid';
	       }elseif($value->paymode==2){
	        $paymode='To-Pay';
	       }elseif($value->paymode==3){
	        $paymode='Tbb';
	       }

	       $extra['branch_type_text'] = $branch_type;
	       $extra['business_type_text'] = $business_type;
	       $extra['insurance_text'] = $insurance;
	       $extra['booking_type_text'] = $booking_type;
	       $extra['paymode_text'] = $paymode;
	       

    		 $this->set_response([
                'status' => TRUE,
                'data' =>$extra
                 ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		 $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
    	}
    }

    public function all_branch_post()
    {
    	$company_id = $this->input->post('company_id');
    	$res = $this->db->where('comp_id',$company_id)->get('branch');
    	
    	if($res->num_rows())
    	{
    		$this->set_response([
                'status' => TRUE,
                'data' =>$res->result(),
                 ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		 $this->set_response([
	          'status' => false,
	          'msg' =>'not found'
	          ], REST_Controller::HTTP_OK);
    	}
    }


    public function branch_wise_rate_post()
    {
    	$company_id = $this->input->post('company_id');
    	$from = $this->input->post('booking_branch');
    	$to  = $this->input->post('delivery_branch');

	    	$res = $this->db->where(array('comp_id'=>$company_id,'booking_branch'=>$from,'delivery_branch'=>$to))->get('branchwise_rate');
	    	
	    	if($res->num_rows())
	    	{
	    		$this->set_response([
	                'status' => TRUE,
	                'data' =>$res->row(),
	                 ], REST_Controller::HTTP_OK);
	    	}
	    	else
	    	{
	    		 $this->set_response([
		          'status' => false,
		          'msg' =>'not found'
		          ], REST_Controller::HTTP_OK);
	    	}
    }

    public function save_deal_post()
    {
    	$enquiry_id=$this->input->post('enquiry_id');
	    $infoid=$this->input->post('infoid');
	    $comp_id = $this->input->post('company_id');
	    $user_id = $this->input->post('user_id');

	    if(empty($infoid)){

	        $delivery_branch=$this->input->post('delivery_branch');

	        if (strpos($delivery_branch, ',') !== false)
	        {
				$delivery_branch = explode(',', $delivery_branch);
			}
			else
			{
				$delivery_branch = array($delivery_branch);
			}
			
	        $discount=$this->input->post('discount')??0;
	        $potential_tonnage=$this->input->post('potential_tonnage')??0;   
	        $expected_tonnage=$this->input->post('expected_tonnage')??0;

	        //print_r($delivery_branch); exit();
	        $del_count = count($delivery_branch);
	        
	        foreach ($delivery_branch as $delivery_branch)
	        {
	            
	            $type=$this->input->post('type');
	            $booking_type=$this->input->post('booking_type');
	            $business_type=$this->input->post('business_type');
	            $booking_branch=$this->input->post('booking_branch');
	            if($del_count>1)
	            {
	                $rate = 0;
	                $getrate= $this->db->where(array('booking_branch'=>$booking_branch,'delivery_branch'=>$delivery_branch))->get('branchwise_rate')->row();
	                if(!empty($getrate) && !empty($getrate->rate))
	                    $rate = $getrate->rate;

	                $x = $rate * $potential_tonnage * 1000;
	                $y = $rate * $expected_tonnage * 1000;

	                $potential_amount = round($x - (( $x * $discount )/100),2);
	                $expected_amount = round($y - (( $y * $discount )/100),2);


	            }
	            else
	            {
	                $rate = $this->input->post('rate');
	                $potential_amount=$this->input->post('potential_amount');
	                $expected_amount=$this->input->post('expected_amount');
	            }



	            $insurance=$this->input->post('insurance');
	            

	            $paymode=$this->input->post('paymode');
	                    
	            $vehicle_type=$this->input->post('vehicle_type');
	            $capacity=$this->input->post('capacity');
	            $invoice_value=$this->input->post('invoice_value');
	            $ftlpotential_amount=$this->input->post('ftlpotential_amount');
	            $ftlexpected_amount=$this->input->post('ftlexpected_amount');
	            $invoice_value=$this->input->post('invoice_value');
	            $url=base_url('enquiry/view/'.$enquiry_id.'');
	            if($booking_type==0){
	             $data=[ 'enquiry_id'=>$enquiry_id,
	                    'branch_type'=>$type,
	                    'booking_type'=>$booking_type,
	                    'business_type'=>$business_type,
	                    'booking_branch'=>$booking_branch,
	                    'delivery_branch'=>$delivery_branch,
	                    'rate'=>$rate,
	                    'discount'=>$discount,
	                    'insurance'=>$insurance,
	                    'paymode'=>$paymode,
	                    'potential_tonnage'=>$potential_tonnage,
	                    'potential_amount'=>$potential_amount,
	                    'expected_tonnage'=>$expected_tonnage,
	                    'expected_amount'=>$expected_amount,
	                    'createdby'=>$user_id,
	                    'comp_id'=>$comp_id
	                  ];
	                }elseif($booking_type==1){
	                    $data=[ 'enquiry_id'=>$enquiry_id,
	                    'branch_type'=>$type,
	                    'booking_type'=>$booking_type,
	                    'business_type'=>$business_type,
	                    'booking_branch'=>$booking_branch,
	                    'delivery_branch'=>$delivery_branch,
	                    'insurance'=>$insurance,
	                    'paymode'=>$paymode,
	                    'potential_amount'=>$ftlpotential_amount,
	                    'expected_amount'=>$ftlexpected_amount,
	                    'vehicle_type'=>$vehicle_type,
	                    'carrying_capacity'=>$capacity,
	                    'invoice_value'=>$invoice_value,
	                    'createdby'=>$user_id,
	                    'comp_id'=>$comp_id
	                  ]; 
	                 
	                }
	               // print_r($data); exit();	
	                $insert=$this->enquiry_model->insertComInfo($data);
	        }

	        //exit();

	        if($insert){
	        $res_status = true;
	        }else{
	       $res_status = false;
	        }

	    }else{
	   
	        $type=$this->input->post('type');
	        $booking_type=$this->input->post('booking_type');
	        $business_type=$this->input->post('business_type');
	        $booking_branch=$this->input->post('booking_branch');
	        $delivery_branch=$this->input->post('delivery_branch');
	        $insurance=$this->input->post('insurance');
	        $rate=$this->input->post('rate');
	        $discount=$this->input->post('discount');
	        $paymode=$this->input->post('paymode');
	        $potential_tonnage=$this->input->post('potential_tonnage');
	        $potential_amount=$this->input->post('potential_amount');
	        $expected_tonnage=$this->input->post('expected_tonnage');
	        $expected_amount=$this->input->post('expected_amount');
	        $vehicle_type=$this->input->post('vehicle_type');
	        $capacity=$this->input->post('capacity');
	        $invoice_value=$this->input->post('invoice_value');
	        $ftlpaymode=$this->input->post('ftlpaymode');
	        $ftlpotential_amount=$this->input->post('ftlpotential_amount');
	        $ftlexpected_amount=$this->input->post('ftlexpected_amount');
	        $invoice_value=$this->input->post('invoice_value');
	        $url=base_url('enquiry/view/'.$enquiry_id.'');
	        if($booking_type==0){
	         $data=[ 
	                'branch_type'=>$type,
	                'booking_type'=>$booking_type,
	                'business_type'=>$business_type,
	                'booking_branch'=>$booking_branch,
	                'delivery_branch'=>$delivery_branch,
	                'rate'=>$rate,
	                'discount'=>$discount,
	                'insurance'=>$insurance,
	                'paymode'=>$paymode,
	                'potential_tonnage'=>$potential_tonnage,
	                'potential_amount'=>$potential_amount,
	                'expected_tonnage'=>$expected_tonnage,
	                'expected_amount'=>$expected_amount,
	              ];
	            }elseif($booking_type==1){
	                $data=[
	                'branch_type'=>$type,
	                'booking_type'=>$booking_type,
	                'business_type'=>$business_type,
	                'booking_branch'=>$booking_branch,
	                'delivery_branch'=>$delivery_branch,
	                'insurance'=>$insurance,
	                'paymode'=>$ftlpaymode,
	                'potential_amount'=>$ftlpotential_amount,
	                'expected_amount'=>$ftlexpected_amount,
	                'vehicle_type'=>$vehicle_type,
	                'carrying_capacity'=>$capacity,
	                'invoice_value'=>$invoice_value,
	              ]; 
	             
	            }
	            $data['updatedby']=$user_id;
	            
	            $insert=$this->db->where(array('comp_id'=>$comp_id,'id'=>$infoid))->update('commercial_info',$data);
	            
	            //echo $this->db->affected_rows();exit();
	            
	            if($insert)
	            {
	          		$res_status = true;
	    
	            }
	            else
	            {
	           		$res_status = false;
	            } 
	    }

	    if($res_status)
    	{
    		$this->set_response([
                'status' => TRUE,
                'msg' =>'Saved Successfully',
                 ], REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		 $this->set_response([
	          'status' => false,
	          'msg' =>'Something went wrong'
	          ], REST_Controller::HTTP_OK);
    	}
    }

    public function deal_status_update_post()
    {
    	$deal_id = $this->input->post('deal_id');
    	$comp_id = $this->input->post('company_id');
    	$user_id = $this->input->post('user_id');
    	$status = $this->input->post('status');

    	$this->form_validation->set_rules('deal_id','deal_id ','required|trim');
    	$this->form_validation->set_rules('company_id','company_id ','required|trim');
    	$this->form_validation->set_rules('user_id','user_id ','required|trim');
    	$this->form_validation->set_rules('status','status ','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->db->where(array('id'=>$deal_id,'comp_id'=>$comp_id))
    				->set(array('status'=>$status,'updatedby'=>$user_id))->update('commercial_info');
    		$res = $this->db->affected_rows();
    		if($res)
    		{
    			$this->set_response([
                  'status' => true,
                  'message' =>'Updated Successfully.',
               ], REST_Controller::HTTP_OK);
    		}
    		else
    		{
    			$this->set_response([
                  'status' => false,
                  'message' =>'No update is made.',
               ], REST_Controller::HTTP_OK);
    		}
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		  }

    }

    public function delete_deal_post()
    {
    	$deal_id = $this->input->post('deal_id');
    	$comp_id = $this->input->post('company_id');
    	$enquiry_code = $this->input->post('enquiry_code');
    	$user_id = $this->input->post('user_id');
    	$this->form_validation->set_rules('deal_id','deal_id','required|trim');
    	$this->form_validation->set_rules('company_id','company_id','required|trim');
    	$this->form_validation->set_rules('enquiry_code','enquiry_code','required|trim');
    	$this->form_validation->set_rules('user_id','user_id','required|trim');

    	if($this->form_validation->run()==true)
    	{
    		$this->db->where(array('id'=>$deal_id,'comp_id'=>$comp_id));
    		$this->db->delete('commercial_info');

    		if($this->db->affected_rows())
    		{
	    		$this->load->model('Leads_Model');
	        	$this->Leads_Model->add_comment_for_events('Commercial Info Deleted.',$enquiry_code,0,$user_id);

    			$this->set_response([
                  'status' => true,
                  'message' =>'Deleted Successfully.',
               ], REST_Controller::HTTP_OK);
    		}
    		else
    		{
    			$this->set_response([
                  'status' => false,
                  'message' =>'Deal Not found',
               ], REST_Controller::HTTP_OK);
    		}
    	}
  		else 
        {		     
  		     $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		  }

    }
    //===========================================================
  public function mail_quotation_post()
  {
  		$this->form_validation->set_rules('info_id','info_id','required');
  		if($this->form_validation->run())
  		{
  			$info_id = $this->input->post('info_id');

  			$url = base_url('dashboard/pdf_gen');
  			$fields = array('info_id'=>$info_id,
  							'email'=>1,
  							'api'=>1,
  							);
  			

  			$ch = curl_init();
			$curlConfig = array(
			    CURLOPT_URL            => base_url('dashboard/pdf_gen'),
			    CURLOPT_POST           => true,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_SSL_VERIFYPEER => false,
			    CURLOPT_POSTFIELDS     => $fields,
			);
			curl_setopt_array($ch, $curlConfig);
			$result = curl_exec($ch);
			

  			if(curl_errno($ch))
  			{
  				curl_close($ch);
			$this->set_response([
	                  'status' => false,
	                  'message' =>'Error: '.curl_errno($ch),
	               ], REST_Controller::HTTP_OK);
  			}
  			else
  			{
  				curl_close($ch);
  				$this->set_response(json_decode($result), REST_Controller::HTTP_OK);
  			}
  		}
  		else
  		{
  			 $this->set_response([
                  'status' => false,
                  'message' =>strip_tags(validation_errors())
               ], REST_Controller::HTTP_OK);
  		}

  }
}
?>