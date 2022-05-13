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
        
        
        echo "<code><pre>";
        print_r($json);
        echo "</pre></code>";
        exit;
        
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
                'X-ShipX-API-Key: 1leVciAvveJvVv3RtiPDmWDXvASxeDJpQvBJcrJMbnQH3oHyuVCvCo7v1Voz'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;


    }
}