<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Shipx extends REST_Controller {
    function __construct(){
        parent::__construct();        
    }

    public function update_customer_post(){        
        $this->load->model('Leads_Model');
        $shipx_id = $this->input->post('id'); // shipx id
        $req = json_encode($_POST);
        $this->db->set('req',$req);
        $this->db->insert('shipx_customer_update_api_log');    
        
        $facility_name = $this->input->post('facility_name'); //client name
        $company_group = $this->input->post('name'); // company group name
        
        $company_flag = $this->input->post('company_flag'); // to check vt/vx

        $facility_id = $this->input->post('facility_id');  
        $facility_code = $this->input->post('facility_code'); // do nothing
        
        $username = $this->input->post('username'); // lead name

        if(!empty($shipx_id)){
            if($facility_id){
                $company_flag = $this->input->post('company_flag'); // lead name            
                if($company_flag == 'vt'){
                    
                    $agreement_row = $this->db->where('shipx_id',$shipx_id)->where('shipx_facility_id',$facility_id)->get('tbl_aggriment')->row_array();

                    $enq_row = $this->db->where('shipx_facility_id',$facility_id)->get('enquiry')->row_array();

                    if(!empty($enq_row)){                
                        
                        $enquiry_code = $enq_row['Enquery_id'];                
                        $enq_id = $enq_row['enquiry_id'];

                        $oracle_customer_code = $this->input->post('code'); //oracle customer code
                        
                        $lead_name = $this->input->post('username');                
                        $is_updated = false;
                        
                        if(!empty($oracle_customer_code)){
                            $this->db->where('id',$agreement_row['id'])->set('oracle_customer_code',$oracle_customer_code)->update('tbl_aggriment');
                            $is_updated = true;
                            $this->update_customer_oracle_code_to_vx($shipx_id,$oracle_customer_code);
                        }

                        if(!empty($lead_name)){
                            $name_arr = explode(' ', $lead_name);
                            $fname = $name_arr[0]??'';
                            $lname = $name_arr[1]??'';
                            $this->db->set('name',$fname);
                            $this->db->set('lastname',$lname);
                            $this->db->where('enquiry_id',$enq_id);
                            $this->db->update('enquiry');
                            $is_updated = true;
                        }
                        
                        //$company_type = $this->input->post('company_type');                                
                        //$website = get_val(4505,$lead_row['Enquery_id']);
                        //$pincode = get_val(4536,$lead_row['Enquery_id']);
                        //$company_ref3 = $this->input->post('company_ref3');                
                        //$address1 = $this->input->post('address-line1');
                        //$address2 = $this->input->post('address-line2');
                        //$address3 = $this->input->post('address-line3');
                        
                        $city = $this->input->post('city');                
                        $city_row    = $this->db->where('city',$city)->get('city')->row_array();                
                        $city_id = $city_row['id']??'';
                        if(!empty($city_id)){
                            $this->db->where('enquiry_id',$enq_id)->set('city_id',$city_id)->update('enquiry');
                            $is_updated = true;
                        }                
                        $state = $this->input->post('state');
                        $state_row    = $this->db->where('state',$state)->get('state')->row_array();
                        $state_id = $state_row['id']??'';                
                        if(!empty($state_id)){
                            $this->db->where('enquiry_id',$enq_id)->set('state_id',$state_id)->update('enquiry');
                            $is_updated = true;
                        }
                        $pin = $this->input->post('pin');
                        if(!empty($pin)){
                            $this->db->where('enquiry_id',$enq_id)->set('pin_code',$pin)->update('enquiry');
                            $is_updated = true;                    
                            update_input_val(4536,$enquiry_code,$pin);
                        } 
                        $drop_status = $this->input->post('drop_status');
                        if(!empty($drop_status)){
                            $drop_status = 181;
                            $drop_reason = $this->input->post('drop_reason');
                            $this->db->where('enquiry_id',$enq_id);
                            $this->db->set('drop_status',$drop_status);
                            $this->db->set('drop_reason',$drop_reason);
                            $this->db->update('enquiry');                    
                        }
                        $email = $this->input->post('email');                
                        if(!empty($email)){
                            $this->db->where('enquiry_id',$enq_id)->set('email',$email)->update('enquiry');
                            $is_updated = true;
                        }
                        $mobile = $this->input->post('mobile-number');
                        if(!empty($mobile)){
                            $this->db->where('enquiry_id',$enq_id)->set('phone',$mobile)->update('enquiry');
                            $is_updated = true;
                        }                                
                


                        if($is_updated){
                            $this->Leads_Model->add_comment_for_events_api('Data Updated from Shipx', $enquiry_code,$agreement_row['created_by']);
                            $this->set_response([
                                'status' => true,
                                'msg' => 'Updated Successfully'
                            ], REST_Controller::HTTP_OK);
                        }else{
                            $this->set_response([
                                'status' => true,
                                'msg' => 'Nothing Updated'
                                ], REST_Controller::HTTP_OK);    
                        }
                    }else{
                        $this->set_response([
                            'status' => true,
                            'msg' => 'No data available to update with facility_id'
                            ], REST_Controller::HTTP_OK);
                    }
                }else if($company_flag == 'vx'){


                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://v-xpress.thecrm360.com/vxpress/api/shipx/update_customer',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $_POST,
                    CURLOPT_HTTPHEADER => array(
                        'Cookie: ci_session=fvfc33qkqfcbnn3sghonq42fub6ma859'
                    ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    //echo $response;


                    $this->set_response([
                        'status' => true,
                        'msg' => $response
                        ], REST_Controller::HTTP_OK);
                }else{
                    $this->set_response([
                        'status' => true,
                        'msg' => 'Unknown company flag'
                        ], REST_Controller::HTTP_OK);
                }
            }else{
                $this->set_response([
                    'status' => true,
                    'msg' => 'Facility Id not found'
                     ], REST_Controller::HTTP_OK);       
            }
        }
        else{
            $this->set_response([
                'status' => true,
                'msg' => 'Shipx Id not found'
                 ], REST_Controller::HTTP_OK);
        }

        // name - client name
        // code 
        // type
        // website
        // company-type
        // company-ref3
        // code
        // address-line1
        // address-line2
        // address-line3
        // city
        // pin
        // state
        // name
        // designation
        // email
        // mobile-number        

    }

    public function update_customer_oracle_code_to_vx($shipx_id,$oracle_code){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://v-xpress.thecrm360.com/vxpress/api/shipx/update_customer_oracle_code',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('shipx_id' => $shipx_id,'oracle_customer_code' => $oracle_code),
        CURLOPT_HTTPHEADER => array(
            'Cookie: ci_session=89l0c7ilgvjalvlko7fg4lgjtgl085uc'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
    public function update_customer_oracle_code_post(){
        $oracle_customer_code  = $this->input->post('oracle_customer_code');
        $shipx_id = $this->input->post('shipx_id');
        if(!empty($shipx_id) && !empty($shipx_customer_code)){
            $this->db->where('shipx_id',$shipx_id)->set('oracle_customer_code',$oracle_customer_code)->update('tbl_aggriment');
        }
    }

    public function get_oracle_code_post(){ // not completed
        $shipx_code = $this->input->post('shipx_id');
        $shipx_row = $this->db->where('shipx_id',$shipx_id)->get('tbl_aggriment');
        if(!empty($shipx_row['oracle_customer_code'])){
            echo $shipx_row['oracle_customer_code'];
            $this->set_response([
                'status' => true,
                'msg' => array('oracle_customer_code'=>$shipx_row['oracle_customer_code'],'shipx_id',$shipx_id)
                ], REST_Controller::HTTP_OK);
        }else{
            $this->set_response([
                'status' => false,
                'msg' => array('oracle_customer_code'=>$shipx_row['oracle_customer_code'],'shipx_id',$shipx_id)
                ], REST_Controller::HTTP_OK);
        }
    }

 


}