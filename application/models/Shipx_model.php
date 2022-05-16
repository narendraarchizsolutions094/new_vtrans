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
        $company_type = $payment_terms = $tin = $gstin  = '';
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
        $user_row = $this->db->select('designation')->where('pk_i_admin_id',$this->session->user_id)->get('tbl_admin')->row_array();
        $username  = $this->session->fullname;
        $user_designation = $user_row['designation'];
        $user_email = $this->session->email;
        $user_mobile = $this->session->phone;

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
        $company_type = '';
        $payment_terms = '';
        $sales_branch = '';
        $shipx_arr = array(
                        'company'=>array(
                                'name'=>$client_name??'',
                                'code'=>'',//oracle code
                                'type'=>'shipper',
                                'website'=>$website??'',
                                'company-type'=>$company_type??'',
                                'payment-terms'=>$payment_terms??'',
                                'company-ref1'=>'',//optional
                                'company-ref2'=>'',//optional
                                'company-ref3'=>'',	//optional
                                'facilities-attributes'=>array(
                                    'facility' =>	array(
                                            'Name' => $lead_row['name_prefix'].' '.$lead_row['name'].' '.$lead_row['lastname'],	
                                            'code' => '',//optional
                                            'address-line1'	=>'',//optional
                                            'address-line2'	=>'',//optional
                                            'address-line3' =>'',//optional
                                            'city' => $city_name??'',
                                            'pin' => $pincode??'',
                                            'state' => $state_name??'',
                                            'payment_terms' => $payment_terms??'',
                                            'facility-ref1' => '',//optional
                                            'facility-ref2' => '',//optional
                                            'facility-ref3' => '',//optional
                                            'is-invoice-facility' => true,
                                            'is-ship-from-address' => true,
                                            'is-ship-to-address' => true,
                                            'facility-category' =>$sales_branch??'',
                                            'phone-number' =>$lead_row['phone']??'',
                                            'is-sez' =>false,                
                                            'users-attributes' => array(
                                                'user' => array(
                                                    'name'    => $username??'',
                                                    'designation' => $user_designation??'',
                                                    'email' => $user_email??'',	
                                                    'mobile-number' => $user_mobile??''
                                                )	
                                            ),                    
                                            'tin' => $tin??'',
                                            'gst-no' => $gstin??''
                                    )
                                ),	
                                'company-group' => array(
                                    'name'	=> $company_group_name,
                                    'description' => '',//optional
                                    'create_new_company_group'	=> 'N'
                                    )
                            ));
        $shipx_json = json_encode($shipx_arr);
        return $shipx_json;
    }
}