<?php
class Shipx_model extends CI_model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	public function get_json($agreement_id){
        $agreement_row = $this->db->select('*')->from('tbl_aggriment')->where('id',$agreement_id)->get()->row_array();
        
        $lead_id = $agreement_row['enq_id'];

        $pdf_json = $agreement_row['pdf_json'];
        if(!empty($pdf_json)){
            $pdf_json = json_decode($pdf_json, true);
        }
        ///print_r($pdf_json);
        $pan = $company_type = $payment_terms = $tin = $gstin  = '';
        if(!empty($pdf_json['ip'][53])){
            $company_type = $pdf_json['ip'][53];
        }
        if(!empty($pdf_json['ip'][86])){
            $payment_terms = $pdf_json['ip'][86];
        }
        if(!empty($pdf_json['ip'][79])){
            $tin = $pdf_json['ip'][79];
        }

        if(!empty($pdf_json['ip'][76])){
            $gstin = $pdf_json['ip'][76];
        }

        if(!empty($pdf_json['ip'][77])){
            $pan = $pdf_json['ip'][77];
        }

        $this->db->select('*');
        $this->db->from('enquiry');
        $this->db->where('enquiry.Enquery_id',$lead_id);
        $lead_row = $this->db->get()->row_array();
        //print_r($lead_row);
        $city_name = $state_name = $company_group_name = '';

        if(!empty($lead_row['state_id'])){
            $state_row = $this->db->select('state')->where('id',$lead_row['state_id'])->get('state')->row_array();
            if(!empty($state_row['state'])){
                $state_name = $state_row['state'];
            }
        }
        if(!empty($lead_row['city_id'])){
            $city_row = $this->db->select('city')->where('id',$lead_row['city_id'])->get('city')->row_array();
            if(!empty($city_row['city'])){
                $city_name = $city_row['city'];
            }
        }
        if(!empty($lead_row['company'])){
            $company_row = $this->db->select('company_name')->where('id',$lead_row['company'])->get('tbl_company')->row_array();
            if(!empty($company_row['company_name'])){
                $company_group_name = $company_row['company_name'];
            }
        }
        $user_row = $this->db->select('*')->where('pk_i_admin_id',$this->session->user_id)->get('tbl_admin')->row_array();
        $username  = $lead_row['client_name'];
        $user_designation = '';//$user_row['designation'];
        $user_email = $lead_row['email'];
        $user_mobile = $lead_row['phone'];

        $website = get_val(4505,$lead_row['Enquery_id']);
        $pincode = get_val(4536,$lead_row['Enquery_id']);
        $sales_branch = '';
        if(!empty($lead_row['sales_branch'])){
            $branch_row = $this->db->select('branch_name')->where('branch_id',$lead_row['sales_branch'])->get('branch')->row_array();
            if(!empty($branch_row['branch_name'])){
                $sales_branch = $branch_row['branch_name'];
            }
        }

        $client_name = $lead_row['client_name'];
       // var_dump($lead_row);
        $company_type = 'CONTRACTUAL';
        $payment_terms = 'Immediate';
        $sales_branch = '';
        $oracle_code = '';
        if(!empty($agreement_row['oracle_customer_code'])){
            $oracle_code = $agreement_row['oracle_customer_code'];
        }else{
            $company_data_vt = $this->get_oracle_code_api($company_group_name);
            if(!empty($company_data_vt['oracle_code'])){
                $oracle_code = $company_data_vt['oracle_code']??'';
            }else{
                $conpany_group_id = $lead_row['company'];
                $this->db->where('shipx_id>',0);
                $enq_shipx_rows = $this->db->where('company',$conpany_group_id)->get('enquiry')->row_array();

                if(!empty($enq_shipx_rows['shipx_id']) && $enq_shipx_rows['shipx_id'] > 0){
                    $enq_shipx_id_n = $enq_shipx_rows['shipx_id'];
                    $agreement_row_n = $this->db->select('*')->from('tbl_aggriment')->where('shipx_id',$enq_shipx_id_n)->get()->row_array();
                    if(!empty($agreement_row_n['oracle_customer_code'])){
                        $oracle_code = $agreement_row_n['oracle_customer_code'];
                    }
                }
            }
        }        

        $shipx_arr = array(
                        'company'=>array(
                                'name'=>$company_group_name,
                                'code'=>$oracle_code??'',//oracle code
                                'type'=>'Shipper',
                                'website'=>$website??'',
                                'company_type'=>$company_type??'',
                                'payment_terms'=>$payment_terms??'',                               
                                'facilities_attributes'=>array(
                                    array(
                                            'name' => $client_name,	
                                            'code' => 'VT'.$lead_row['Enquery_id'],//optional                                            
                                            'city' => $city_name??'',
                                            'pin' => $pincode??'',
                                            'state' => $state_name??'',
                                            'payment_terms' => $payment_terms??'',                                          
                                            'facility_ref3' => $user_row['employee_id'],//optional
                                            'is_invoice_facility' => "true",
                                            'is_ship_from_address' => "true",
                                            'is_ship_to_address' => "true",
                                            'facility_category' =>'BRANCH',
                                            'phone_number' =>$lead_row['phone']??'',
                                            'is_sez' =>"false",                
                                            'users_attributes' => array(
                                                array(
                                                    'name'    => $lead_row['name_prefix'].' '.$lead_row['name'].' '.$lead_row['lastname'],
                                                    'designation' => $user_designation??'',
                                                    'email' => $user_email??'',	
                                                    'mobile_number' => $user_mobile??''
                                                )	
                                            ),                    
                                            'tin' => $pan??'',
                                            'gst_no' => $gstin??''
                                    )
                                ),
                                'additional_ref' => array(                                  
                                    'vt' => 'YES'
                                ),
                                "holds_list" => array(
                                    "hold" => array(
                                        array(
                                            "hold_type"=> "onboarding",
                                            "hold_state"=> true
                                        )
                                    )
                                ),
                                'company_group' => array(
                                    'name'	=> '',
                                    'description' => '',//optional
                                    'create_new_company_group'	=> ''
                                    )
                            ));
        
        if(!empty($lead_row['shipx_id']) && $lead_row['shipx_id'] >0){
            $shipx_arr['company']['id'] = $lead_row['shipx_id'];
        }else{
            $conpany_group_id = $lead_row['company'];
            $this->db->where('shipx_id>',0);
            $enq_shipx_rows = $this->db->where('company',$conpany_group_id)->get('enquiry')->row_array();

            if(!empty($enq_shipx_rows['shipx_id']) && $enq_shipx_rows['shipx_id'] > 0){
                $enq_shipx_id = $enq_shipx_rows['shipx_id'];
                $shipx_arr['company']['id'] = $enq_shipx_id;
            }

        }
        $shipx_json = json_encode($shipx_arr);
        return $shipx_json;
    }


    public function get_oracle_code_api($companay){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://v-xpress.thecrm360.com/vxpress/api/enquiry/create_account',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('company_name' => $companay),
        CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=pg4go6pss7dghkd6r1bivs58vvnveca3'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl); 
        $res_arr = array();
        if(!empty($response)){
            $res_arr = json_decode($response, true);
        }
        return $res_arr;
    }
}