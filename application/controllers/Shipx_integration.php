<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Shipx_integration extends CI_Controller{
    public function __construct(){ 
        parent::__construct();
        $this->load->model(
            array('Shipx_model')
        );        
    }
    public function auth(){
        $endpoint = 'https://vtrans-staging.shipx.co.in/integration/users/login.json';
        $post_field = '{
            "user": {
                "email": "crmapi.vt@mailinator.com",
                "password": "crmapi"
            }
        }';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$post_field,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);        
        $ins_data = array(
            'endpoint' => $endpoint,
            'req' => $post_field,
            'res' => $response
        );
        $this->db->insert('shipx_auth',$ins_data);
        curl_close($curl);
        echo json_encode($ins_data);
    }
    public function push_data($agreement_id){        
        
        $json = $this->Shipx_model->get_json($agreement_id);
        
        
        // echo "<code><pre>";
        // print_r($json);
        // echo "</pre></code>";
        // exit;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://vtrans-staging.shipx.co.in/integration/companies/manage.json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$json,
            CURLOPT_HTTPHEADER => array(
                'X-ShipX-API-Key: 1leVciAvveJvVv3RtiPDmWDXvASxeDJpQvBJcrJMbnQH3oHyuVCvCo7v1Voz',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response_arr = json_decode($response,true);
        //echo $response; exit;

        $this->db->insert('shipx_push_log',array('req'=>$json,'res'=>$response));

        if(!empty($response_arr['exception']['errorcode'])){ 
            $msg = $response_arr['exception']['message'];
            echo json_encode(array('msg'=>$msg,'status'=>0));
        }else{
            //echo $response;
            $res_arr = json_decode($response,true);
            if(!empty($res_arr['company']['id'])){
                $shipx_id = $response_arr['company']['id'];
                $shipx_facility_id = $response_arr['company']['facilities'][0]['id'];

                $this->db->where('id',$agreement_id);
                $this->db->set('shipx_res',$response);
                
                $this->db->set('shipx_id',$response_arr['company']['id']);
                $this->db->set('shipx_facility_id',$shipx_facility_id);
                
                
                $this->db->set('shipx_push_status',200);
                $this->db->update('tbl_aggriment');

                $agreement_row = $this->db->where('id',$agreement_id)->get('tbl_aggriment')->row_array();

                

                $this->db->where('Enquery_id',$agreement_row['enq_id']);
                $this->db->set('shipx_id',$shipx_id);
                $this->db->set('shipx_facility_id',$shipx_facility_id);
                $this->db->update('enquiry');

                echo json_encode(array('msg'=>'Success','status'=>1));
                
            }else{
                echo json_encode(array('msg'=>'Something went wrong !','status'=>0));
            }
        }
    }
}