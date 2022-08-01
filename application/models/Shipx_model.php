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
        $shipx_arr = array(
                        'company'=>array(
                                'name'=>$company_group_name,
                                'code'=>'',//oracle code
                                'type'=>'Shipper',
                                'website'=>$website??'',
                                'company_type'=>$company_type??'',
                                'payment_terms'=>$payment_terms??'',
                                'company_ref1'=>'',//optional
                                'company_ref2'=>'',//optional
                                'company_ref3'=>'',	//optional
                                'facilities_attributes'=>array(
                                    array(
                                            'name' => $client_name,	
                                            'code' => 'VT'.$lead_row['Enquery_id'],//optional
                                            'address_line1'	=>'',//optional
                                            'address_line2'	=>'',//optional
                                            'address_line3' =>'',//optional
                                            'city' => $city_name??'',
                                            'pin' => $pincode??'',
                                            'state' => $state_name??'',
                                            'payment_terms' => $payment_terms??'',
                                            'facility_ref1' => '',//optional
                                            'facility_ref2' => '',//optional
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
                                    'third_party_booking_allowed' => '',
                                    'vt' => 'YES',
                                    'vx' => '',
                                    'vlogis' => ''
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
}