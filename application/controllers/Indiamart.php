<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Indiamart extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }	
	public function get_enquiry($comp_id){
		$this->db->where('comp_id',$comp_id);
		$res	=	$this->db->get('indiamart_api')->row_array();
		//$endpoint = Start_Time/@start/End_Time/@end/
		if (!empty($res)) {
			$endpoint = $res['endpoint'];
			$end_time = date("d-m-Y H:i:s");
			$timestamp = strtotime($end_time) - ($res['api_call_interval']*60);
			$start_time = date('d-m-Y H:i:s', $timestamp);
			$endpoint = $endpoint.'Start_Time/'.$start_time.'/'.'End_Time/'.$end_time.'/';
		}
		$endpoint = str_replace(' ', '%20', $endpoint);				
		/*echo $endpoint;
		exit();*/
		$data	=	$this->get_contents_curl($endpoint);
		$this->db->insert('api_responses',array('comp_id'=>$comp_id,'res'=>$data,'type'=>'indiamart','endpoint'=>$endpoint));		
		if ($data) {
			$data	=	json_decode($data,true);			
			if (!empty($data)) {
				$enq_arr = array();
				foreach ($data as $key => $res) {					
					if (!empty($res['MOB'])) {
						$name		=	!empty($res['SENDERNAME'])?$res['SENDERNAME']:'';
						$email		=	!empty($res['SENDEREMAIL'])?$res['SENDEREMAIL']:'';
						$mob		=	!empty($res['MOB'])?$res['MOB']:'';
						$other_phone =	!empty($res['MOBILE_ALT'])?$res['MOBILE_ALT']:'';						
						$company 	=	!empty($res['GLUSR_USR_COMPANYNAME'])?$res['GLUSR_USR_COMPANYNAME']:'';
						$address 	=	!empty($res['ENQ_ADDRESS'])?$res['ENQ_ADDRESS']:'';
						$city		=	!empty($res['ENQ_CITY'])?$res['ENQ_CITY']:'';
						$state		=	!empty($res['ENQ_STATE'])?$res['ENQ_STATE']:'';
						$country_iso=	!empty($res['COUNTRY_ISO'])?$res['COUNTRY_ISO']:'';
						$product	=	!empty($res['PRODUCT_NAME'])?$res['PRODUCT_NAME']:'';
						$remark		=	!empty($res['ENQ_MESSAGE'])?$res['ENQ_MESSAGE']:'';						
						$encode = $this->get_enquery_code();
						if ($comp_id == 53) {
							$enquiry_source = 91;
							$created_by = 262;
							$product_id = 107;
						}else if ($comp_id == 85) {
							$enquiry_source = 225;
							$created_by = 537;
							$product_id = 186;
						}else if($comp_id == 65){
							$enquiry_source = 135;
							$created_by = 2175;
							$product_id = 141;
						}
						$enq_arr[] = array(
                            'Enquery_id' => $encode,							
							'comp_id'=>$comp_id,
							'name' =>$name,
							'email' =>$email,
							'phone' =>$mob,
							'other_phone'=>$other_phone,
							'company'=>$company,
							'address'=>$address,
							'enquiry'=>$remark,
							'enquiry_source'=>$enquiry_source,
							'created_by'=>$created_by,
							'product_id'=>$product_id	
						);
						$mob = substr($mob,-10);
						$curl = curl_init();						
						$api_url = base_url()."api/enquiry/create";
						curl_setopt_array($curl, array(
							CURLOPT_URL => $api_url,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_SSL_VERIFYPEER =>false, // line added beacuse of SSL Error 60
							CURLOPT_CUSTOMREQUEST => "POST",
							CURLOPT_POSTFIELDS => array(
								'fname' => $name,
								'lastname' => '',
								'email' => $email,
								'mobileno' => $mob,
								'other_phone'=>$other_phone,
								'company_id' => $comp_id,
								'org_name'=>$company,
								'address'=>$address,
								'enquiry'=>$remark,
								'enquiry_source'=>$enquiry_source,
								'process_id' => $product_id,
								'user_id' => $created_by
							),
							CURLOPT_HTTPHEADER => array(
								"Cookie: ci_session=3ba7d4lq4alv2pgpq3sc8t2ojrh41s04"
							),
						));
						$response = curl_exec($curl);
						if(curl_error($curl))
							echo curl_error($curl);
						curl_close($curl);	
					}
										
				}
			
				if (!empty($enq_arr)) {
					//$this->db->insert_batch('enquiry', $enq_arr); 
				}			
			}
		}
	}
	public function get_enquery_code() {
		$this->load->model('enquiry_model');
        $code = $this->genret_code();
        $code2 = 'ENQ' . $code;
        $response = $this->enquiry_model->check_existance($code2);        
        if ($response) {            
            $this->get_enquery_code();
        } else {            
            return $code2;            
            //exit;
        }
        //exit;
    }
    function genret_code() {
        $pass = "";
        $chars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        for ($i = 0; $i < 12; $i++) {
            $pass .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $pass;
    }
	function get_contents_curl($url) {			
		
		//$url = 'https://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/9003440677/GLUSR_MOBILE_KEY/MTU4NzM3NDAzNS42MjEyIzkwMzY1ODg=/Start_Time/15-04-20%2006:13:17/End_Time/21-04-20%2009:13:17/';
		$url = str_replace(' ', '', $url);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));
		$data = curl_exec($curl);
	    $info = curl_getinfo($curl);
	    if(curl_errno($curl)) {
	        throw new Exception('Curl error: ' . curl_error($curl));
	    }
	    curl_close($curl);
	    if ($data === FALSE) {
	        throw new Exception("curl_exec returned FALSE. Info follows:\n" . print_r($info, TRUE));
	    }
	    return $data;
	}
	public function test($comp_id=1){
		$this->db->where('id',147);
		$r	=	$this->db->get('api_responses')->row_array();
		$data	=	$r['res'];
		//$this->db->insert('api_responses',array('comp_id'=>$comp_id,'res'=>$data,'type'=>'indiamart','endpoint'=>$endpoint));		
		if ($data) {
			$data	=	json_decode($data,true);			
			if (!empty($data)) {
				$enq_arr = array();
				foreach ($data as $key => $res) {					
					if (!empty($res['MOB'])) {
						$name		=	!empty($res['SENDERNAME'])?$res['SENDERNAME']:'';
						$email		=	!empty($res['SENDEREMAIL'])?$res['SENDEREMAIL']:'';
						$mob		=	!empty($res['MOB'])?$res['MOB']:'';
						$other_phone =	!empty($res['MOBILE_ALT'])?$res['MOBILE_ALT']:'';						
						$company 	=	!empty($res['GLUSR_USR_COMPANYNAME'])?$res['GLUSR_USR_COMPANYNAME']:'';
						$address 	=	!empty($res['ENQ_ADDRESS'])?$res['ENQ_ADDRESS']:'';
						$city		=	!empty($res['ENQ_CITY'])?$res['ENQ_CITY']:'';
						$state		=	!empty($res['ENQ_STATE'])?$res['ENQ_STATE']:'';
						$country_iso=	!empty($res['COUNTRY_ISO'])?$res['COUNTRY_ISO']:'';
						$product	=	!empty($res['PRODUCT_NAME'])?$res['PRODUCT_NAME']:'';
						$remark		=	!empty($res['ENQ_MESSAGE'])?$res['ENQ_MESSAGE']:'';						
						$encode = $this->get_enquery_code();
						$enq_arr[] = array(
                            'Enquery_id' => $encode,							
							'comp_id'=>$comp_id,
							'name' =>$name,
							'email' =>$email,
							'phone' =>$mob,
							'other_phone'=>$other_phone,
							'company'=>$company,
							'address'=>$address,
							'enquiry'=>$remark,
							'enquiry_source'=>91,
							'created_by'=>262,
							'product_id'=>107	
						);
					}
										
				}
				echo "<pre>";
				print_r($enq_arr);
				echo "</pre>";
				if (!empty($enq_arr)) {
					//$this->db->insert_batch('enquiry', $enq_arr); 
				}
			}
		}
	}


}
