<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Shipx extends REST_Controller {
    function __construct()
    {
        parent::__construct();        
    }
    public function update_customer_post(){ 
        $this->load->model('Leads_Model');

        $shipx_id = $this->input->post('id');
        $req = json_encode($_POST);
        $this->db->set('req',$req);
        $this->db->insert('shipx_customer_update_api_log');

        if(!empty($shipx_id)){            
            $agreement_row = $this->db->where('shipx_id',$shipx_id)->get('tbl_aggriment')->row_array();
            if(!empty($agreement_row)){                
                $enquiry_code = $agreement_row['enq_id'];
                $enq_row = $this->db->where('Enquery_id',$enquiry_code)->get('enquiry')->row_array();
                $enq_id = $enq_row['enquiry_id'];
                $oracle_customer_code = $this->input->post('oracle_customer_code');                
                
                $client_name = $this->input->post('name');                
                $is_updated = false;
                
                if(!empty($oracle_customer_code)){
                    $this->db->where('id',$agreement_row['id'])->set('oracle_customer_code',$oracle_customer_code)->update('tbl_aggriment');
                    $is_updated = true;
                }

                if(!empty($client_name)){
                    $name_arr = explode(' ', $client_name);
                    $fname = $name_arr[0]??'';
                    $lname = $name_arr[1]??'';
                    $this->db->set('name',$fname);
                    $this->db->set('lastname',$lname);
                    $this->db->where('enquiry_id',$enq_id);
                    $this->db->update('enquiry');
                    $is_updated = true;
                }
                $website = $this->input->post('website');            
                if(!empty($website)){
                    update_input_val(4505,$enquiry_code,$website);
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
                $city_row    = $this->db->where('branch_name',$city)->get('branch')->row_array();                
                $city_id = $city_row['branch_id']??'';
                if(!empty($city_id)){
                    $this->db->where('enquiry_id',$enq_id)->set('sales_branch',$city_id)->update('enquiry');
                    $is_updated = true;
                }                
                $state = $this->input->post('state');
                $state_row    = $this->db->where('area_name',$state)->get('sales_area')->row_array();
                $state_id = $state_row['area_id']??'';                
                if(!empty($state_id)){
                    $this->db->where('enquiry_id',$enq_id)->set('sales_area',$state_id)->update('enquiry');
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
                $designation = $this->input->post('designation');                  
                $designation_row    = $this->db->where('desi_name',$designation)->get('tbl_designation')->row_array();                
                if(!empty($designation_row)){
                    $designation_id = $designation_row['id'];
                    $this->db->where('enquiry_id',$enq_id)->set('designation',$designation_id)->update('enquiry');
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
                    'msg' => 'No data available to update'
                     ], REST_Controller::HTTP_OK);
            }
        }else{
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
}