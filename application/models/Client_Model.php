<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
class Client_Model extends CI_Model
{
    private $table = "clients";
    public function get_Client_list()
    {
        $query = $this->db->get('enquiry');
        return $query->result();
    }
    
    public function clientdetail_by_id($client_id)
    {
        return $this->db->select("*")
                ->from('enquiry')
                ->join('lead_source', 'lead_source.lsid=enquiry.enquiry_source', 'left')
                ->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.created_by','left')
               // ->join('tbl_product_country', 'tbl_product_country.id = enquiry.country_id', 'left')
                ->where('enquiry.enquiry_id', $client_id)->get()->row();
    }
    
    public function get_clientid_bycustomerCODE($leadcode)
    {
        $this->db->select("*");
        $this->db->from('clients');
        $this->db->where('customer_code', $leadcode);
        return $query = $this->db->get()->result();
    }

    
    public function clientContact_by_id($client_id)
    {
        return $this->db->select(" * ")->from('tbl_client_contacts')->where('client_id', $client_id)->get()->result();
    }
    public function clientadd($data)
    {
        $this->db->insert('clients', $data);
        
    }
    
    
    public function clientContact($data)
    {
        $this->db->insert('tbl_client_contacts', $data);
        return $this->db->insert_id();
    }
    
    public function getContactWhere($where)
    {
        $this->db->where($where);
       return  $this->db->get('tbl_client_contacts');
    }

    ////////////////////////////////////////////
    
    
    public function all_clients()
    {   
        $user_id          = $this->session->user_id;
        $user_role        = $this->session->user_role;
        $assign_country   = $this->session->country_id;
        
        $this->db->select("*,tbl_datasource.datasource_name,tbl_product.product_name as product_name");
        $this->db->from('enquiry');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');

        $where = '';

        $where .= " enquiry.status=3";

        if ($user_role == 3) {
            
            $where .= " AND enquiry.country_id=".$assign_country;

            //$this->db->where('country_id', $assign_country);

        } elseif ($user_role == 8 || $user_role == 9) {
            
            $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";
           

            $process = $this->session->process;
            $where.= " AND enquiry.product_id=$process";



            //$this->db->where('aasign_to', $user_id);
            //$this->db->or_where('created_by', $user_id);

        }

        //$where .= " AND enquiry.client_drop_status=0";
        $where .= " AND enquiry.status=3";

        //$this->db->where('enquiry.status', 3);
        //$this->db->where('client_drop_status', 0);   

        $this->db->where($where);
        
        $this->db->order_by('enquiry.country_id', 'desc');
        return $this->db->get();
    }
    
    
    public function getContactList($specific=array(),$action='data',$comp_id=0,$user_id=0,$limit=-1,$offset=-1)
    {   
        $comp_id = !empty($comp_id)?$comp_id:$this->session->companey_id;
        $user_id = !empty($user_id)?$user_id:$this->session->user_id;
        
        $where = 'enquiry.comp_id='.$comp_id;
        $all_reporting_ids    =   $this->common_model->get_categories($user_id);
        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';          
        if($where)
            $this->db->where($where);
       
        if(!empty($specific))
            $this->db->where_in('cc_id',$specific);

        $this->db->select('contacts.*,enquiry.company,enquiry.enquiry_id,concat_ws(" ",name_prefix,name,lastname) as enq_name');
        $this->db->from('tbl_client_contacts contacts');
        $this->db->join('enquiry','enquiry.enquiry_id=contacts.client_id','inner');
        $this->db->order_by('contacts.cc_id desc');

        if(!empty($_POST['filters']))
        {
            foreach ($_POST['filters'] as $key => $value)
            {
                if($value==''){
                  unset($_POST['filters'][$key]);
                  if(!count($_POST['filters']))
                    unset($_POST['filters']);
                }

            }
        }

        if(!empty($_POST['filters']))
        {

              $match_list = array();

              $this->db->group_start();
              foreach ($_POST['filters'] as $key => $value)
              {
                if(in_array($key,$match_list) || $this->db->field_exists($key, 'tbl_client_contacts'))
                {
                    if(in_array($key, $match_list))
                    {
                        $fld = 'creation_date';
                        // if($type=='2')
                        //   $fld = 'lead_created_date';
                        // else if($type=='3')
                        //   $fld = 'client_created_date';

                        if($key=='date_from')
                          $this->db->where($fld.'>=',$value);

                        if($key=='date_to')
                          $this->db->where($fld.'<=',$value);

                        // if($key=='phone')
                        //   $this->db->where('phone LIKE "%'.$value.'%" OR other_phone LIKE "%'.$value.'%"');
                    }
                    else
                    {
                      if(is_int($value))
                        $this->db->where($key,$value);
                      else
                        $this->db->where($key.' LIKE "%'.$value.'%"');
                    } 
                }
                else
                {
                  $this->db->where('1=1');
                }
              }
              $this->db->group_end();
        }

        if($limit!=-1 and $offset!=-1)
        {
            $this->db->limit($limit,$offset);
        }

        if($action=='count')
           return  $this->db->count_all_results();
        else
            return $this->db->get();
        //echo $this->db->last_query(); exit();
    }

    public function getCompanyList($id =0,$where=array(),$comp_id=0,$user_id=0,$process=0,$action='data',$limit=-1,$offset=-1,$sort=-1)
    {

        $process = !empty($process)?$process:$this->session->process;
        $comp_id = !empty($comp_id)?$comp_id:$this->session->companey_id;

        $user_id  = empty($user_id)?$this->session->user_id:$user_id;

        if(!empty($user_id))
        {
            $all_reporting_ids    =   $this->common_model->get_categories($user_id);
        }

        $this->db->select('comp.*,GROUP_CONCAT(enq.enquiry_id) enq_ids');
        $this->db->from('tbl_company comp')
                        ->join('enquiry enq','enq.company=comp.id','left')
                        ->group_by('comp.id');
        

        $where="comp.comp_id=".$comp_id;

        if(!empty($user_id))
        {
            $where .= " AND ( enq.created_by IN (".implode(',', $all_reporting_ids).')';
            $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
        }

        // if($id)
        //     $where.= "AND comp.id =".$id;

        if(!empty($where))
            $this->db->where($where);


        if(is_array($process))
            $this->db->where_in('comp.process_id',$process);
        else 
            $this->db->where(' comp.process_id IN ('.$process.') ');

        if(!empty($id))
        {
            $this->db->where('comp.id',$id);
        }

        // if($sort!=-1)
        // {
        //     if($sort=='up')
        //         $this->db->order_by('comp.company','DESC');
        //     else 
        //         $this->db->order_by('comp.company','ASC');
        // }

        if($action=='count')
         return  $this->db->count_all_results();
        if($limit!=-1 and $offset!=-1)
            $this->db->limit($limit,$offset);
        
        $res =  $this->db->get();

        return $res;
        //echo $this->db->last_query(); exit();
    }

    public function getCompanyData($enquires=array(),$datatype)
    {
        if(!is_array($enquires))
            $enquires=array($enquires);

       if($datatype=='deals')
       {
            $this->db->select('*');
            $this->db->where_in('enquiry_id',$enquires);
          return  $this->db->get('commercial_info');
       }
       else if($datatype=='contacts')
       {
            $this->db->select('*');
            $this->db->where_in('client_id',$enquires);
          return  $this->db->get('tbl_client_contacts');
       }
       else if($datatype=='tickets')
       {
            $this->db->select('*');
            $this->db->where_in('client',$enquires);
            $this->db->where_in('process_id',$this->session->process);
          return  $this->db->get('tbl_ticket');
       }
       else if($datatype=='visits')
       {
            $this->db->select('*');
            $this->db->where_in('enquiry_id',$enquires);
          return  $this->db->get('tbl_visit');
       }

    }

    public function all_created_today()
    {
        $user_id          = $this->session->user_id;
        $user_role        = $this->session->user_role;
        $assign_country   = $this->session->country_id;   
        $this->db->select("*,tbl_datasource.datasource_name,tbl_product.product_name as product_name");
        $this->db->from('enquiry');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');


        $where = '';

        $where .= " enquiry.status=3";
        
        $where .= " AND enquiry.client_drop_status=0";   

        if ($user_role == 3) {
            $where .= " AND enquiry.country_id =".$assign_country;
            //$this->db->where('enquiry.country_id', $assign_country);
        }elseif ($user_role == 8 || $user_role == 9) {

            $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";
           

            $process = $this->session->process;
            $where.= " AND enquiry.product_id=$process";

            //$this->db->where('aasign_to', $user_id);
            //$this->db->or_where('created_by', $user_id);
        }
        

        //$this->db->where('status', 3);
        //$this->db->where('client_drop_status', 0);   
        
        //$this->db->like('enquiry.created_date', date('Y-m-d'));
        $where .= " AND enquiry.status=3";
        
        $date=date('Y-m-d');
            
        $where.=" AND enquiry.created_date LIKE '%$date%'";


        $this->db->where($where);

        $this->db->order_by('country_id', 'desc');

        return $this->db->get();        
    }
    
    
    public function all_Updated_today()
    {
        $user_id          = $this->session->user_id;
        $user_role        = $this->session->user_role;
        $assign_country   = $this->session->country_id;   
        $this->db->select("*,tbl_datasource.datasource_name,tbl_product.product_name as product_name");

        $this->db->from('enquiry');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        
        $where = ''; 

        $where .= " enquiry.status=3";

        $where .= " AND enquiry.client_drop_status=0";

        if ($user_role == 3) {
            $where .= " AND enquiry.country_id=".$assign_country;
            
            //$this->db->where('country_id', $assign_country);

        } elseif ($user_role == 8 || $user_role == 9) {
            
            $where.=" AND (enquiry.aasign_to=$user_id OR (enquiry.created_by=$user_id AND  enquiry.aasign_to IS NULL))";
          

            $process = $this->session->process;
            $where.= " AND enquiry.product_id=$process";

            //$this->db->or_where('enquiry.created_by', $user_id);

        }
        
        
        //$where .= " AND enquiry.client_drop_status=0";

        //$this->db->where('status', 3);

        //$this->db->where('client_drop_status', 0);
        $date=date('Y-m-d');
            
        $where.=" AND enquiry.update_date LIKE '%$date%'";

        //$this->db->like('enquiry.update_date', date('Y-m-d'));
        
        $where .= " AND enquiry.status=3";        


        $this->db->where($where);
        $this->db->order_by('enquiry.enquiry_id', 'desc');
        return $this->db->get();        
    }
    
    
    public function all_Active_clients()
    {
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);

        $user_id          = $this->session->user_id;
        $user_role        = $this->session->user_role;
        $assign_country   = $this->session->country_id;
        $cpny_id=$this->session->companey_id;		
        
        $this->db->select("*,tbl_datasource.datasource_name,tbl_product.product_name as product_name");
        
        $this->db->from('enquiry');
        
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');

        
        $where = '';
        
        $where .= " enquiry.status=3";
        $where .= " AND enquiry.client_drop_status=0";        

        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where.=" AND enquiry.comp_id=$cpny_id";		
        $this->db->where($where);

        $this->db->order_by('enquiry_id', 'desc');        

        return $this->db->get();
    }
 
    public function all_InActive_clients()
    {
        $user_id          = $this->session->user_id;
        $user_role        = $this->session->user_role;
        $region_id        = $this->session->region_id;
        $assign_country   = $this->session->country_id;
        $this->db->select("*,tbl_datasource.datasource_name,tbl_product.product_name as product_name");
        $this->db->from('enquiry');
        $this->db->join('tbl_datasource', 'enquiry.datasource_id=tbl_datasource.datasource_id', 'left');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        
        
        $where = '';

        $where .= "enquiry.status=3";

        if ($user_role == 3) {
            $where .= "enquiry.country_id=".$assign_country;       
            //$this->db->where('country_id', $assign_country);
        
        } else if ($user_role == 8 || $user_role == 9) {
            $where .= " AND enquiry.aasign_to=".$user_id;       

            //$this->db->where('aasign_to', $user_id);
            
            $where .= " OR enquiry.created_by=".$user_id;       

            //$this->db->or_where('created_by', $user_id);
        }
        $where .= " AND enquiry.status=3";
        $where .= " AND enquiry.client_drop_status=1";       
        

        //$this->db->where('status', 3);
        //$this->db->where('client_drop_status', 1);        
        $this->db->order_by('enquiry.enquiry_id', 'desc');
        return $this->db->get();
    }    
    public function all_clients_Tickets()
    {        
        $user_id          = $this->session->user_id;
        $user_role        = $this->session->user_role;
        $region_id        = $this->session->region_id;
        $assign_country   = $this->session->country_id;
        $assign_region    = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state     = $this->session->state_id;
        $assign_city      = $this->session->city_id;

        $this->db->select("*");
        $this->db->from('enquiry');
        $this->db->join('ticket', 'ticket.cl_id = enquiry.country_id', 'left');
        $this->db->where('ticket.cl_id = enquiry.country_id');
        
        if ($user_role == 3) {
            $this->db->where('enquiry.country_id', $assign_country);
        }elseif ($user_role == 8 || $user_role == 9) {
            $this->db->where('enquiry.aasign_to', $user_id);
         //   $this->db->or_where('enquiry.created_by', $user_id);
        }
        $this->db->where('enquiry.status', 3);
        $this->db->where('enquiry.client_drop_status', 0);
        $this->db->order_by('enquiry.enquiry_id', 'desc');
        return $this->db->get();        
    }

    public function add_visit($data)
    {
        $this->db->insert('tbl_visit',$data);
        return $this->db->insert_id();
    }
}