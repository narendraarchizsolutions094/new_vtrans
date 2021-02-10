<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Enquiry_model extends CI_Model {

    private $table = "enquiry";

    public function create($data = [],$comp_id =0) {
		
    $this->db->insert($this->table, $data);
	
		$insid = $this->db->insert_id();
	

    //=====Create default Contact for Enquiry
   if(!empty($_POST['name'])){
           $name = $data['name'];
        $mobile = $data['phone'];
        $email = $data['email'];
        $otherdetails ='';
        $data2 = array(
            'comp_id'=>$data['comp_id'],
            'client_id' =>$insid,
            'c_name' => $name,
            'emailid' => $email,
            'contact_number' => $mobile,
            'designation' => 'Primary',
            'other_detail' =>$otherdetails,
            'decision_maker' => 1,
        );
        $this->db->insert('tbl_client_contacts', $data2);
        }
    //===================================

		if(isset($_POST['inputfieldno'])) {
		$inputno   = $this->input->post("inputfieldno", true);
		$enqinfo   = $this->input->post("enqueryfield", true);
		$inputtype = $this->input->post("inputtype", true);
		
			foreach($inputno as $ind => $val){
				
				$biarr[] = array( 

								"enq_no"  => $data["Enquery_id"],
								  "input"   => $val,
								  "parent"  => $insid, 
								  "fvalue"  => (!empty($enqinfo[$ind])) ? $enqinfo[$ind] : "",
								  "cmp_no"  => $comp_id??$this->session->companey_id,
								 ); 	
			}
		
			if(!empty($biarr)){
				$this->db->insert_batch('extra_enquery', $biarr); 
			}
		}
		return $insid;
    }



  public function enquiry_all_tab_api($companey_id,$enquiry_id)
	{
		//return array($company_id,$ticketno);
		$this->load->model(array('form_model','Enquiry_model','Leads_Model'));
		$this->session->companey_id = $companey_id;
		
		//2 for Ticket Tab 
		$enquiry = $this->getEnquiry(array('enquiry_id'=>$enquiry_id))->row();
		//return $ticket;
		
		if(!empty($enquiry))
		{	
			$process_id = $enquiry->product_id;

		  $tab_list = $this->form_model->get_tabs_list($companey_id,$process_id,0);

  		$tabs = array();

  		$primary_tab=0;
  		$primary = $this->getPrimaryTab();

        if($primary)
            $primary_tab = $primary->id;

       	$basic= $this->location_model->get_company_list1($process_id);

      foreach ($basic as $key => $input)
      {
          switch($input['field_id'])
          { 
            case 1:
            $prefixList = $this->Enquiry_model->name_prefix_list();
            $prefix = array();
            if(!empty($prefixList))
            {
            	foreach ($prefixList as $res)
            	{
            		$prefix[]  = $res->prefix;
            	}
            }
            $basic[$key]['extra_field'][] =array('input_values'=>$prefix,
            									'parameter_name'=>'name_prefix',
            									'current_value'=>$enquiry->name_prefix);

            $basic[$key]['parameter_name'] = 'fname';
            $basic[$key]['current_value'] = $enquiry->name;
            break;

            case 2:
            $basic[$key]['parameter_name'] = 'lastname';
            $basic[$key]['current_value'] = $enquiry->lastname;
            break;

            case 3:
            $values = array(
                            array('key'=>"1",
                                  'value'=>'Male'),
                            array('key'=>"2",
                                  'value'=>'Female'),
                            array('key'=>"3",
                                  'value'=>'Other'),
                          );
           
            $basic[$key]['input_values'] = $values;
            $basic[$key]['parameter_name'] = 'gender';
            $basic[$key]['current_value'] = $enquiry->gender;
            break;

            case 4:
            $basic[$key]['other_phone'] = $enquiry->other_phone;
            $basic[$key]['parameter_name'] = 'mobileno';
            $basic[$key]['current_value'] = $enquiry->phone;
            break;
            case 5:
            $basic[$key]['parameter_name'] = 'email';
            $basic[$key]['current_value'] = $enquiry->email;
            break;
            case 6:
            $basic[$key]['parameter_name'] = 'org_name';
            $basic[$key]['current_value'] = $enquiry->company;
            break;
            case 7:
            $leadsource = $this->Leads_Model->get_leadsource_list();
            $values = array();
            foreach ($leadsource as $res)
            {
              $values[] =  array('key'=>$res->lsid,
                                'value'=> $res->lead_name
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['parameter_name'] = 'enquiry_source';
            $basic[$key]['current_value'] = $enquiry->enquiry_source;
            break;
            case 8:
            $subsource = $this->location_model->productcountry();
            $values = array();
            foreach ($subsource as $res)
            {
              $values[] =  array('key'=>$res->id,
                                'value'=> $res->country_name
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['parameter_name'] = 'product_id';
            $basic[$key]['current_value'] = $enquiry->enquiry_subsource;
            break;

            case 9:
            $state_list = $this->location_model->estate_list();
            $values = array();
            foreach ($state_list as $res)
            {
              $values[] = array('key'=>$res->id,
                                'value'=> $res->state
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['parameter_name'] = 'state_id';
            $basic[$key]['current_value'] = $enquiry->state_id;
            break;

            case 10:
            $city_list = $this->location_model->ecity_list();
            $values = array();
            foreach ($city_list as $res)
            {
              $values[] = array('key'=>$res->id,
              					'state_id'=>$res->state_id,
                                'value'=> $res->city
                              );
            }
            $basic[$key]['input_values'] = $values;
            $basic[$key]['parameter_name'] = 'city';
            $basic[$key]['current_value'] = $enquiry->city_id;
            break;

            case 11:
            $basic[$key]['parameter_name'] = 'address';
            $basic[$key]['current_value'] = $enquiry->address;
            break;

            case 12:
            $basic[$key]['parameter_name'] = 'enquiry';
            $basic[$key]['current_value'] = $enquiry->enquiry;
            break;

            case 14: 
            $basic[$key]['parameter_name'] = 'pin_code';
            $basic[$key]['current_value'] = $enquiry->pin_code;
            break;

            // case 27:
            // $basic[$key]['parameter_name'] = 'remark';
            // break;

            // case 28:
            // $basic[$key]['parameter_name'] = 'tracking_no';
            // break;
            default:
            unset($basic[$key]);
          }

      }

      $dynamic = $this->Enquiry_model->get_dyn_fld($enquiry_id,$primary_tab,0);
      $i=0;
      foreach ($dynamic as $key => $value)
      {
          if(in_array($value['input_type'],array('2','3','4','20')))
          {
             
              $temp  = explode(',', $value['input_values']);
              if(!empty($temp))
              {   $reshape = array();
                  foreach ($temp as $k => $v)
                  {
                    $reshape[] = array('key'=>null,
                                      'value'=>$v);
                  }
                  $dynamic[$key]['input_values'] = $reshape;
              }
          }


          if($value['input_type']=='8')
          {
            $ary =array(
                      array(
                            'key'=>$value['input_id'].'[]',
                            'value' =>'',
                          ),
                      array(
                            'key'=>'inputtype['.$value['input_id'].']',
                            'value' =>'8',
                          ),
            );
                   
          }
          else
          {
            $ary = $value['input_id'];
          }
          $dynamic[$key]['parameter_name'] = $ary;

          // $dynamic[$key]['parameter_name'] = $value['input_id'];


          $dynamic[$key]['current_value'] = $value['fvalue'];
         // $dynamic[$key]['parameter_name'] = array(
         //                                      array('key'=>'enqueryfield['.$value['input_id'].']',
         //                                            'value'=>''),
         //                                      array('key'=>'inputfieldno['.$i.']',
         //                                            'value'=>$value['input_id']),
         //                                      array('key'=>'inputtype['.$i.']',
         //                                            'value'=>$value['input_type']),
         //                                      );
          $i++;
      }

        $tabs[]  = array('tab_id'=>$primary->id,
        					'title'=>$primary->title,
        					'is_query_type'=>$primary->form_type,
        					'is_delete'=>$primary->is_delete,
        					'is_edit'=>$primary->is_edit,
        					'field_list'=>array_merge($basic,$dynamic),
    						);

  //       $match = array(
		// 	'ticket_no' => $ticket->ticketno,
		// 	'tck.client' => $ticket->client,
		// 	'tck.tracking_no' => $ticket->tracking_no,
		// 	'tck.phone' => $ticket->phone, 
		// );

		// $related_tickets = $this->Ticket_Model->all_related_tickets($match);

		// $tabs[]  = array('tab_id'=>null,
  //       					'title'=>'Related Tickets',
  //       					'table'=>$related_tickets??array(),
  //   						);

        
        foreach ($tab_list as $res)
        { 
          
        	if($res['primary_tab']!='1')
        	{
        		$dynamic = $this->Enquiry_model->get_dyn_fld($enquiry_id,$res['id'],0);
        		$heading = array();
            $heading_ids=array();
            $i=0;
        		foreach ($dynamic as $key => $value)
			      {
			          if(in_array($value['input_type'],array('2','3','4','20')))
			          {
			              $temp  = explode(',', $value['input_values']);
			              if(!empty($temp))
			              {   $reshape = array();
			                  foreach ($temp as $k => $v)
			                  {
			                    $reshape[] = array('key'=>null,
			                                      'value'=>$v);
			                  }
			                  $dynamic[$key]['input_values'] = $reshape;
			              }
			          }
			          $heading[] = $value['input_label'];
                $heading_ids[]  = $value['input_id'];

                $dynamic[$key]['parameter_name'] = array(
                              array('key'=>($value['input_type']=='8'?'enqueryfiles['.$value['input_id'].']':'enqueryfield['.$value['input_id'].']'),
                                    'value'=>''),
                              array('key'=>'inputfieldno['.$i.']',
                                    'value'=>$value['input_id']),
                              array('key'=>'inputtype['.$i.']',
                                    'value'=>$value['input_type']),
                              );
                $dynamic[$key]['current_value'] = $value['fvalue'];
              $i++;
			       }


        		$part = array('tab_id'=>$res['id'],
        					'title'=>$res['title'],
        					'is_query_type'=>$res['form_type'],
        					'is_delete'=>$res['is_delete'],
        					'is_edit'=>$res['is_edit'],
        					'field_list'=>$dynamic,
    						);

        		if($res['form_type']==1)
        		{
        			$tid = $res['id'];
    					$comp_id = $companey_id;
    					$enquiry_no = $enquiry->Enquery_id;

					$sql  = "SELECT GROUP_CONCAT(concat(`extra_enquery`.`input`,'#',`extra_enquery`.`fvalue`,'#',`extra_enquery`.`created_date`,'#',`extra_enquery`.`comment_id`) separator ',') as d FROM `extra_enquery` INNER JOIN (select * from tbl_input where form_id=$tid) as tbl_input ON `tbl_input`.`input_id`=`extra_enquery`.`input` where `extra_enquery`.`cmp_no`=$comp_id and `extra_enquery`.`enq_no`='$enquiry_no' GROUP BY `extra_enquery`.`comment_id` ORDER BY `extra_enquery`.`comment_id` DESC";

			             $sql_res = $this->db->query($sql)->result_array(); 
						$data =array();
			             if(!empty($sql_res))
			             {	
			             	
			             	foreach ($sql_res as $key => $value) 
			             	{
			             		$abc = explode(',',$value['d']);
			             		
			             		if(!empty($abc))
			             		{	$sub = array();
			             			foreach ($abc as $k => $v)
			             			{
			             				$x = explode('#', $v);
			             				$sub[] = array(
                                          'input_id'=>$x[0],
                                          'value'=>$x[1],
                                          'updated_at'=>$x[2],
                                          'cmmnt_id'=>$x[3]
                                        );
			             			}
			             		}
			             		$data[] = $sub;
			             	}
			             }
              $part['enquiry_code']=$enquiry_no;
			        $part['table']=array('heading'=>$heading,
			        					              'data'=>$data,
                                      'heading_ids'=> $heading_ids
                                    );
        		}
        		
        		$tabs[] = $part;
        	}
        }

		session_destroy();
		return $tabs;
		}
		else
			return false;
	}

  public function update_dynamic_query($user_id=0,$comp_id=0)
  {
    $this->load->library('user_agent');
   
    $enq_id = $this->input->post('enquiry_id');
    $cmnt_id = $this->input->post('cmnt_id');
    $tid    =   $this->input->post('tid');
    $form_type    =   $this->input->post('form_type');
    $enqarr = $this->db->select('*')->where('enquiry_id',$enq_id)->get('enquiry')->row();
    $en_comments = $enqarr->Enquery_id;
    
    $type = $enqarr->status;

    $user_id = $this->session->user_id??$user_id;
    $comp_id  = $this->input->post('comp_id')??$comp_id;   

          $this->Leads_Model->add_comment_for_events($this->lang->line("information_updated"),$en_comments,'',$user_id);

        if(!empty($enqarr)){        
            if(isset($_POST['inputfieldno'])) {                    
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);                
                $file_count = 0;                
                $file = !empty($_FILES['enqueryfiles'])?$_FILES['enqueryfiles']:'';                
                foreach($inputno as $ind => $val){
  

                 if ($inputtype[$ind] == 8) {                                                
                        $file_data    =   $this->doupload($file,$file_count,$comp_id);

                        if (!empty($file_data['imageDetailArray']['file_name'])) {
                            $file_path = base_url().'uploads/ticket_documents/'.$this->session->companey_id.'/'.$file_data['imageDetailArray']['file_name'];
                                                
                                    $this->db->where('enq_no',$en_comments);    
                                    $this->db->where('comment_id',$cmnt_id);    
                                    $this->db->where('input',$val);        
                                    $this->db->where('parent',$enq_id);
                                    $this->db->set('fvalue',$file_path);
                                    $this->db->update('extra_enquery');
                             
                        }
                        $file_count++;          
                    }
                    else
                    {
                        
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$enq_id);
                                $this->db->where('comment_id',$cmnt_id); 
                                $this->db->set('fvalue',$enqinfo[$val]);
                                $this->db->update('extra_enquery');
                          
                    }                                      
                } //foreach loop end               
            }            
             
        }
        
        return $this->db->affected_rows();
  }

  public function update_enquiry_tab($user_id=0,$comp_id=0)
  {    
        
        $enquiry_id = $this->input->post('enquiry_id');
        $tab_id = $this->input->post('tab_id');

        $tab_data = $this->db->where('id',$tab_id)->get('forms')->row();

        $form_type    =   $tab_data->form_type;

        $enqarr = $this->db->select('*')->where('enquiry_id',$enquiry_id)->get('enquiry')->row();
        $en_comments = $enqarr->Enquery_id;

        $type = $enqarr->status;     

        $msg = $tab_data->title.' Updated';

        if($type == 1){                 
            $comment_id = $this->Leads_Model->add_comment_for_events($msg, $en_comments);                    
        }else if($type == 2){                   
             $comment_id = $this->Leads_Model->add_comment_for_events($msg, $en_comments);                   
        }else if($type == 3){
             $comment_id = $this->Leads_Model->add_comment_for_events($msg, $en_comments);
        }
        
        if(!empty($enqarr)){        
            if(isset($_POST['inputfieldno'])) {                    
                $inputno   = $this->input->post("inputfieldno", true);
                $enqinfo   = $this->input->post("enqueryfield", true);
                $inputtype = $this->input->post("inputtype", true);                
                $file_count = 0;                
                $file = !empty($_FILES['enqueryfiles'])?$_FILES['enqueryfiles']:'';                
                foreach($inputno as $ind => $val){
  

                 if ($inputtype[$ind] == 8) {                                                
                        $file_data    =   $this->doupload($file,$val,$comp_id);

                        if (!empty($file_data['imageDetailArray']['file_name'])) {
                            $file_path = base_url().'uploads/enquiry_documents/'.$comp_id.'/'.$file_data['imageDetailArray']['file_name'];
                            $biarr = array( 
                                            "enq_no"  => $en_comments,
                                            "input"   => $val,
                                            "parent"  => $enquiry_id, 
                                            "fvalue"  => $file_path,
                                            "cmp_no"  => $comp_id,
                                            "comment_id" => $comment_id
                                        );

                            $this->db->where('enq_no',$en_comments);        
                            $this->db->where('input',$val);        
                            $this->db->where('parent',$enquiry_id);
                            if($this->db->get('extra_enquery')->num_rows()){
                                if ($form_type == 1) {
                                    $this->db->insert('extra_enquery',$biarr);                                       
                                }else{                                    
                                    $this->db->where('enq_no',$en_comments);        
                                    $this->db->where('input',$val);        
                                    $this->db->where('parent',$enquiry_id);
                                    $this->db->set('fvalue',$file_path);
                                    $this->db->set('comment_id',$comment_id);
                                    $this->db->update('extra_enquery');
                                }
                            }else{
                                $this->db->insert('extra_enquery',$biarr);
                            }         
                        }
                        $file_count++;          
                    }else{
                        $biarr = array( "enq_no"  => $en_comments,
                                      "input"   => $val,
                                      "parent"  => $enquiry_id, 
                                      "fvalue"  => $enqinfo[$val],
                                      "cmp_no"  => $comp_id,
                                      "comment_id" => $comment_id
                                     );                                 
                        $this->db->where('enq_no',$en_comments);        
                        $this->db->where('input',$val);        
                        $this->db->where('parent',$enquiry_id);
                        if($this->db->get('extra_enquery')->num_rows()){  
                            if ($form_type == 1) {
                                $this->db->insert('extra_enquery',$biarr);                                       
                            }else{                                                              
                                $this->db->where('enq_no',$en_comments);        
                                $this->db->where('input',$val);        
                                $this->db->where('parent',$enquiry_id);
                                $this->db->set('fvalue',$enqinfo[$val]);
                                $this->db->set('comment_id',$comment_id);
                                $this->db->update('extra_enquery');
                            }
                        }else{
                            $this->db->insert('extra_enquery',$biarr);
                        }
                    }                                      
                } //foreach loop end               
            }            
             
        }
        return $this->db->affected_rows();
  }

  public function getPrimaryTab()
	{
		 return  $this->db->select('*')
            ->where(array('form_for'=>0,'primary_tab'=>1))
            ->get('forms')
            ->row();
	}

  public function doupload($file,$key,$comp_id=0){ 
      //print_r($file); exit();
        $upload_path    =   "./uploads/enquiry_documents/";
        $comp_id        =   $this->session->companey_id??$comp_id; //creare seperate folder for each company
        $upPath         =   $upload_path.$comp_id;
        
        if(!file_exists($upPath)){
            mkdir($upPath, 0777, true);
        }        
        $config = array(
            'upload_path'   => $upPath,            
            'overwrite'     => TRUE,
            'max_size'      => "2048000",
            'overwrite'    => false

        );
        $config['allowed_types'] = '*';


        $this->load->library('upload');
        $this->upload->initialize($config);

        $_FILES['enqueryfiles']['name']      = $file['name'][$key];
        $_FILES['enqueryfiles']['type']      = $file['type'][$key];
        $_FILES['enqueryfiles']['tmp_name']  = $file['tmp_name'][$key];
        $_FILES['enqueryfiles']['error']     = $file['error'][$key];
        $_FILES['enqueryfiles']['size']      = $file['size'][$key];        
        
        if(!$this->upload->do_upload('enqueryfiles')){             
            $data['imageError'] =  $this->upload->display_errors();
        }else{
            $data['imageDetailArray'] = $this->upload->data();        
        }
        return $data;
    }

    public function comission_data($enq_code){
	 	$this->db->select('*');
	 	$this->db->where('tbl_comission.Enquiry_code',$enq_code);
	 	return $this->db->get('tbl_comission')->result_array();
	 }

    public function add_newbankdeal($data){

     $this->db->insert('tbl_newdeal',$data);
     return $this->db->insert_id();
      
    }

    public function getEnquiry($where=0)
    {
      
      $process = $this->session->userdata('process');
      
        $this->db->where_in('product_id',$process);

    	if($where)
            $this->db->where($where);
            

    	$this->db->where('comp_id',$this->session->companey_id);
    	return $this->db->get('enquiry');
    }

	public function get_user_productcntry_list(){

		$this->db->select("*");
		$this->db->from("tbl_product_country");
		$this->db->where('comp_id',$this->session->companey_id);
		return $this->db->get()->result();
	}
	public function get_user_state_list(){

		$this->db->select('*');
		$this->db->from('state');
		$this->db->where('comp_id',$this->session->companey_id);
		$this->db->order_by('state','ASC');
		return $this->db->get()->result();
	}
	public function get_user_city_list(){

		$this->db->select('*');
		$this->db->from('city');
		$this->db->where('comp_id',$this->session->companey_id);
		$this->db->order_by('city','ASC');
		return $this->db->get()->result();
	}
	/****************************************csv work*********************************/		
			public function all_list_colmn($pid){
                    $this->db->select('*');
                    $this->db->from('tbl_product');
                    $this->db->where('product_name',$pid);
                    $q= $this->db->get()->row();
				
                    $this->db->select('*');
                    $this->db->from('tbl_input');
					$this->db->where('process_id',$q->sb_id);
					$this->db->order_by('input_id', 'asc');
                    return $this->db->get()->result();
			}
			
		public function update_tblextra($lid,$code,$enq_no) {
        $this->db->set('parent', $lid);
        $this->db->set('enq_no', $enq_no);
        $this->db->where('parent', $code);
        $this->db->update('extra_enquery');
    }
	public function update_tbleqry2($enquiry_id) {
        $this->db->set('status', '3');
        $this->db->where('enquiry_id', $enquiry_id);
        $this->db->update('enquiry2');
    }
/****************************************csv work end*********************************/
	
	public function getformfield($for=0){//for meanas 0=Enquiry , 1= product, 2= ticket
	
		$this->db->select('*');
		$this->db->where('page_id',$for);
        $this->db->where(array("company_id"=> $this->session->companey_id, "status" => 1));
        if(!empty($this->session->process)){
            $this->db->group_start();
            foreach ($this->session->process as $key => $value) {
                if($key==0)
                    $this->db->where('(FIND_IN_SET ('.$value.',process_id) >0)');
                else
                    $this->db->or_where('(FIND_IN_SET ('.$value.',process_id) >0)');
            }
        }
        $this->db->group_end();
		return $this->db->get("tbl_input")->result();
				
	}
	public function getfieldvalue($enqnos = array(),$for=0){ //for means 0=enquiry,1=product,2=ticket	
	
		$this->db->select('*');
			$this->db->where(array("cmp_no"=> $this->session->companey_id));				
		if(!empty($enqnos)) {
			$enqnos = trim($enqnos, ",");	
			$this->db->where_in("parent", $enqnos);
		}

	if($for==0)
	{
		if(isset($_COOKIE["dallowcols"])) {
			
			$dshowall = false;
			$dacolarr  = explode(",", trim($_COOKIE["dallowcols"], ","));
		}

		$resarr = $this->db->get("extra_enquery")->result();		
		$newarr = array();
		if(!empty($resarr)){
			foreach($resarr as $ind => $res){				
				$prnt = $res->parent;
				$newarr[$prnt][$res->input] = $res;	
			}
		}		
	}
	else if($for==1)
	{

	}
	else if($for==2)
	{
		if(isset($_COOKIE["ticket_dallowcols"])) {
			
			$dshowall = false;
			$dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));
		}

		$resarr = $this->db->get("ticket_dynamic_data")->result();		
		$newarr = array();
		if(!empty($resarr)){
			foreach($resarr as $ind => $res){				
				$prnt = $res->parent;
				$newarr[$prnt][$res->input] = $res;	
			}
		}		
	}

		return $newarr;
	}

	public function get_deal($enqid){

      $this->db->select('*');
      $this->db->from('tbl_newdeal');
      $this->db->where('enq_id',$enqid);
      return $this->db->get()->row();
	}
	
	public function get_dyn_fld($enqno = "",$tid=0,$form_for=0){

		$ticketno=0;
		$tckid = 0;
		$process =0;
		if($form_for==2)
		{	$ticketno = $enqno;
			$ticket = $this->db->select('id,client,process_id')
					->where('ticketno',$enqno)
					->get('tbl_ticket');

			if($ticket->num_rows())
			{
				$ticket_row = $ticket->row();
				$enqno = $ticket_row->client;
				$tckid = $ticket_row->id;
				$process = $ticket_row->process_id;

			}
		}  
		
			$this->db->select('product_id,Enquery_id');
		     $this->db->from('enquiry');
		     $this->db->where('enquiry_id',$enqno);
		     $res_id = $this->db->get()->row_array();

		if($form_for==0 or $process==0)
		     $process = $res_id['product_id'];
		
     	// echo $enqno;exit();
        // $process = $this->session->userdata('process');
        // print_r($process);exit();
        $compid = $this->session->userdata('companey_id');
        // $userid = $this->session->userdata('user_id');
        // $id = implode(",", $process);
        // $proc_id = explode(",", $id);
        // // print_r($id);exit();
        $where ='';    

		$this->db->select('othr.*,fld.fld_attributes,fld.input_id,fld.input_type,fld.input_values,fld.input_place,fld.input_label,fld.input_name,input_types.title as type');
		$this->db->from('tbl_input fld');
		$this->db->join('input_types','fld.input_type=input_types.id','LEFT');
    	$where .= " FIND_IN_SET('".$process."',fld.process_id) AND fld.company_id = {$compid} AND fld.status=1 AND fld.form_id=$tid";
		$this->db->where($where);
		/*$enquiry_code = $res_id['Enquery_id'];*/
		//$this->db->where(array('othr.parent' => $enqno));
		//$this->db->join('extra_enquery othr', 'fld.input_id = othr.input', 'left');
		if($form_for==2)
		{
			$this->db->join("( select * from ticket_dynamic_data where parent=$tckid group by ticket_dynamic_data.input) othr", 'fld.input_id = othr.input', 'left');
		}
		else
		{
		$this->db->join("( select * from extra_enquery where parent=$enqno group by extra_enquery.input) othr", 'fld.input_id = othr.input', 'left');
		}
		// $this->db->join('tbl_feedback fdb','fld.input_id = fdb.input','left');
		// $this->db->or_where('fdb.user_id',$userid);
		$this->db->order_by('fld.fld_order','ASC');
		$resarr =  $this->db->get()->result_array();
	    // print_r($resarr);exit();		
		if(empty($resarr)){			
			$this->db->select('*');
			$this->db->from('tbl_input');
			$where = " FIND_IN_SET('".$process."',process_id) AND company_id = {$compid} AND status=1 AND tbl_input.form_id=$tid";
		    $this->db->where($where);
			$this->db->order_by('tbl_input.input_id','ASC');
			$resarr=  $this->db->get()->result_array();
			// print_r($resarr);exit();
		}
		return $resarr;
	}	

	public function get_dyn_fld_by_query($cmnt_id,$enqno = "",$tid=0,$form_for=0){

		$ticketno=0;
		$tckid = 0;
		$process = 0;
		if($form_for==2)
		{	
      $ticketno = $enqno;
			$ticket = $this->db->select('id,client,process_id')
					->where('ticketno',$enqno)
					->get('tbl_ticket');

			if($ticket->num_rows())
			{
				$ticket_row = $ticket->row();
				$enqno = $ticket_row->client;
				$tckid = $ticket_row->id;
				$process = $ticket_row->process_id;
			}
		}
		
    //echo $enqno; exit();
	     $this->db->select('product_id,Enquery_id');
	     $this->db->from('enquiry');
	     $this->db->where('enquiry_id',$enqno);
	     $res_id = $this->db->get()->row_array();

	     if($form_for==0 or $process==0)
	     $process = $res_id['product_id'];
	  

     	// echo $enqno;exit();
        // $process = $this->session->userdata('process');
        // print_r($process);exit();
        $compid = $this->session->userdata('companey_id');
        // $userid = $this->session->userdata('user_id');
        // $id = implode(",", $process);
        // $proc_id = explode(",", $id);
        // // print_r($id);exit();
        $where ='';    

		$this->db->select('othr.*,fld.fld_attributes,fld.input_id,fld.input_type,fld.input_values,fld.input_place,fld.input_label,fld.input_name');
		$this->db->from('tbl_input fld');
    	$where .= " FIND_IN_SET('".$process."',fld.process_id) AND fld.company_id = {$compid} AND fld.status=1 AND fld.form_id=$tid";
		$this->db->where($where);
		/*$enquiry_code = $res_id['Enquery_id'];*/
		//$this->db->where(array('othr.parent' => $enqno));
		//$this->db->join('extra_enquery othr', 'fld.input_id = othr.input', 'left');
		if($form_for==2)
		{
			$this->db->join("( select * from ticket_dynamic_data where parent=$tckid and comment_id=$cmnt_id group by ticket_dynamic_data.input) othr", 'fld.input_id = othr.input', 'left');
		}
		else
		{
		$this->db->join("( select * from extra_enquery where parent=$enqno and comment_id=$cmnt_id group by extra_enquery.input) othr", 'fld.input_id = othr.input', 'left');
		}
		// $this->db->join('tbl_feedback fdb','fld.input_id = fdb.input','left');
		// $this->db->or_where('fdb.user_id',$userid);
		$this->db->order_by('fld.fld_order','ASC');
		$resarr =  $this->db->get()->result_array();
	    // print_r($resarr);exit();		
		if(empty($resarr)){			
			$this->db->select('*');
			$this->db->from('tbl_input');
			$where = " FIND_IN_SET('".$process."',process_id) AND company_id = {$compid} AND status=1 AND tbl_input.form_id=$tid";
		    $this->db->where($where);
			$this->db->order_by('tbl_input.input_id','ASC');
			$resarr=  $this->db->get()->result_array();
			// print_r($resarr);exit();
		}
		return $resarr;
	}	

	public function get_dyn_fld_api($enqno = ""){
     // echo $enqno;exit();

			$this->db->select('*');
			$this->db->where('enquiry_id',$enqno);
			$this->db->from('enquiry');
			$enqarr = $this->db->get()->row();

		$this->db->select('othr.*,fld.input_id,fld.status,fld.input_type,fld.input_values,fld.input_place,fld.input_label');
		$this->db->from('tbl_input fld');
		
		if(!empty($enqarr)){
			
			$this->db->where('fld.company_id', $enqarr->comp_id);
		}
		
	//	$this->db->where('fld.company_id',$this->session->userdata('companey_id'));
		//$this->db->where(array('othr.parent' => $enqno));
		$this->db->join("( select * from extra_enquery where parent=$enqno) othr", 'fld.input_id = othr.input', 'left');

		//$this->db->join('extra_enquery othr', 'fld.input_id = othr.input', 'left');

		/*
		$this->db->select("*");
		$this->db->from('tbl_input');
		$this->db->where('company_id',1);
		$this->db->order_by('input_id asc');*/
		$resarr =  $this->db->get()->result_array();
		// print_r($resarr);exit();
		
		if(empty($resarr)){
			
			$this->db->select('*');
			$this->db->where('enquiry_id',$enqno);
			$this->db->from('enquiry');
			$enqarr = $this->db->get()->row();
			
			$this->db->select('*');
			$this->db->where('company_id', $enqarr->comp_id);
			$this->db->from('tbl_input');
			$resarr=  $this->db->get()->result_array();
		}
		return $resarr;
	}

    public function created_byid($id) {
        return $this->db->select("*")
                        ->from($this->table)
                        ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                        ->where('aasign_to', $id)
                        ->or_where('created_by', $id)
                        ->where('drop_status', '0')
                        ->order_by('enquiry.enquiry_id', 'desc')
                        ->get(); 
    }
    
    public function datasourcelist($id) {
		return $this->db->select('en2.*,ds.datasource_name,prd.product_name')->from('enquiry2 en2')
					->join("tbl_product prd","prd.sb_id = en2.product_id")
					->join("tbl_datasource ds","ds.datasource_id = en2.datasource_id")
        ->where('en2.datasource_id',$id)		
		->get()
        ->result();
	 }
    
	 public function institute_data($enq_code){
	 	$this->db->select('institute_data.*,tbl_institute.institute_name,institute_app_status.title as app_status_title,tbl_crsmaster.course_name as course_name_str');
	 	$this->db->where('institute_data.enquery_code',$enq_code);
	 	$this->db->join('tbl_institute','institute_data.institute_id=tbl_institute.institute_id','left');
	 	$this->db->join('tbl_crsmaster','tbl_crsmaster.id=institute_data.course_id','left');
	 	$this->db->join('institute_app_status','institute_data.app_status=institute_app_status.id','left');
	 	return $this->db->get('institute_data')->result_array();
	 }

    public function name_product_list_byname($name){
    	if($name){
            $this->db->select('*');
            $this->db->from('tbl_product');
            $this->db->where('product_name',$name);
            $this->db->where('comp_id',$this->session->companey_id);
            return $this->db->get()->row();
    	}else{
    		return false;
    	}		    
	}
/******************************************************qualification tab data************************************/	
	public function quali_data($enq){
            $this->db->select('*');
            $this->db->from('tbl_qualification');
            $this->db->where('enq_id',$enq);
            $this->db->where('cmp_no',$this->session->companey_id);
            return $this->db->get()->result();		    
	}
/******************************************************qualification tab data End************************************/
/******************************************************English tab data************************************/	
	public function eng_data($enq){
            $this->db->select('*');
            $this->db->from('tbl_english');
            $this->db->where('enq_id',$enq);
            $this->db->where('cmp_no',$this->session->companey_id);
            return $this->db->get()->result();		    
	}
/******************************************************English tab data End************************************/
	public function enquiry_source_byname($name){
    	if($name){
            $this->db->select('*');
            $this->db->from('lead_source');
            $this->db->where('TRIM(lead_name)',trim($name));
            $this->db->where('comp_id',$this->session->companey_id);
            return $this->db->get()->row();
    	}else{
    		return false;
    	}		    
	}
		
		public function name_services_list_byname($id){	

                    $this->db->select('*');
                    $this->db->from('tbl_product_country');
                    $this->db->where('TRIM(country_name)',trim($id));
                    $this->db->where('comp_id',$this->session->companey_id);
                    return $this->db->get()->row();
		}

    /* -----------------------search read Enquiry---------------------- */

    public function read() {
        /* user roles
          3 = Country Head
          4 = Region Head
          5 = Territory Head
          6 = State Head
          7 = City Head
          8 = User */

        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;

            return $this->db->select("*")
                            ->from($this->table)
                            ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                             ->where('enquiry.status=1 And enquiry.aasign_to ='.$user_id. ' OR  enquiry.created_by ='.$user_id.' AND enquiry.status=1')
                            ->order_by('enquiry.enquiry_id', 'desc')
                            ->get();
       /* }*/
    }

    /* -------End Read Enquiry------------------ */

    public function state_list() {

        $user_id = $this->session->user_id;
        $user_role = $this->session->user_role;
        $region_id = $this->session->region_id;
        $assign_country = $this->session->country_id;
        $assign_region = $this->session->region_id;
        $assign_territory = $this->session->territory_id;
        $assign_state = $this->session->state_id;
        $assign_city = $this->session->city_id;
        $this->db->select("state.*");
        $this->db->from("state");
        $q = $this->db->get();
        return $q->result();
    }

    /* -----------------------search Active Enquiry---------------------- */

   	public function active_enqueries($data_type=1){
       	$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where='';
		$this->db->from("enquiry");
		$where.=" enquiry.status=$data_type";
		$where.=" AND enquiry.drop_status=0";
		$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
		$enquiry_filters_sess    =   $this->session->enquiry_filters_sess;        
		$product_filter = !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
		if(!empty($this->session->process) && empty($product_filter)){    
		    $arr = $this->session->process;   
		    if (is_array($arr)) {	                 	
		        $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
		    }         
		}else if (!empty($this->session->process) && !empty($product_filter)) {
		    $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
		}
		$this->db->where($where);
		return $this->db->count_all_results();
	}

    /* -------End End Enquiry------------------ */
   
	/*----------------------- Today Enquiry----------------------*/


	public function all_today_update($data_type=1){
	 $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where = '';
		$this->db->select('enquiry.Enquery_id');
		$this->db->from("enquiry");		
		$this->db->join('tbl_comment','tbl_comment.lead_id=enquiry.Enquery_id','inner');

		$where.=" enquiry.status=$data_type";
		$where.=" AND tbl_comment.comment_msg NOT LIKE 'Raw Data Assigned'";
		$date=date('Y-m-d');


		$where.=" AND tbl_comment.created_date LIKE '%$date%'";
		$where.=" AND enquiry.drop_status=0";
		$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
		$enquiry_filters_sess    =   $this->session->enquiry_filters_sess;        
		$product_filter = !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
		if(!empty($this->session->process) && empty($product_filter)){    
		    $arr = $this->session->process;   
		    if (is_array($arr)) {	                 	
		        $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
		    }         
		}else if (!empty($this->session->process) && !empty($product_filter)) {
		    $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
		}
		$this->db->where($where);
		$this->db->group_by('tbl_comment.lead_id');
		return $this->db->count_all_results();
	}

	
/*-------Today Enquiry------------------*/

/*-----------------------search Create Today Enquiry----------------------*/


	public function all_creaed_today($data_type=1){
	   	$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where='';
		$this->db->from("enquiry");
		$where.=" enquiry.status=$data_type";
		$date=date('Y-m-d');
		$where.=" AND enquiry.created_date LIKE '%$date%'";
		$where.=" AND enquiry.drop_status=0";
		$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
		$enquiry_filters_sess    =   $this->session->enquiry_filters_sess;        
		$product_filter = !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
		if(!empty($this->session->process) && empty($product_filter)){    
		    $arr = $this->session->process;   
		    if (is_array($arr)) {	                 	
		        $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
		    }         
		}else if (!empty($this->session->process) && !empty($product_filter)) {
		    $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
		}
		$this->db->where($where);
		return $this->db->count_all_results();
	}

	
/*-------End Create Today Enquiry------------------*/



/*-----------------------search  Today Enquiry----------------------*/


	public function all_enquery($data_type=1)
	{
    $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where='';
		$this->db->from("enquiry");
		$where.=" enquiry.status=$data_type";
        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
        $enquiry_filters_sess    =   $this->session->enquiry_filters_sess;        
	    $product_filter = !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
        if(!empty($this->session->process) && empty($product_filter)){    
	        $arr = $this->session->process;   
	        if (is_array($arr)) {	                 	
	            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
	        }         
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
        }
		$this->db->where($where);
	     return $this->db->count_all_results();
	}


	public function all_enquery_count()
	{
	    /* 
	    user roles
	    3 = Country Head
	    4 = Region Head
	    5 = Territory Head
	    6 = State Head
	    7 = City Head 
	    8 = User */
       
       $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);	    
	   $user_id   = $this->session->user_id;
	   $user_role = $this->session->user_role;
	   $region_id = $this->session->region_id;
	   $assign_country = $this->session->country_id;
	   $assign_region = $this->session->region_id;
	   $assign_territory = $this->session->territory_id;
	   $assign_state = $this->session->state_id;
	   $assign_city = $this->session->city_id;	   
       $cpny_id=$this->session->companey_id;
	   
		$where='';		
		$this->db->select("enquiry.drop_status,enquiry.status,enquiry.enquiry_source,enquiry.product_id");
		$this->db->from("enquiry");
     	$where.="enquiry.is_delete=1";
		$where.=" AND enquiry.status=1";
		$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where.=" AND enquiry.comp_id=$cpny_id";		
		$this->db->where($where);	    
	    return $this->db->get(); 
	}

	
/*-------End Create Today Enquiry------------------*/



/*----------------------- all Dropped Enquiry----------------------*/


	public function all_drop($data_type=1){  
    	$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
		$where='';
		$this->db->from("enquiry");
		$where.=" enquiry.status=$data_type";
        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';  
        $enquiry_filters_sess    =   $this->session->enquiry_filters_sess;        
	    $product_filter = !empty($enquiry_filters_sess['product_filter'])?$enquiry_filters_sess['product_filter']:'';
        if(!empty($this->session->process) && empty($product_filter)){    
	        $arr = $this->session->process;   
	        if (is_array($arr)) {	                 	
	            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
	        }         
        }else if (!empty($this->session->process) && !empty($product_filter)) {
            $where.=" AND enquiry.product_id IN (".implode(',', $product_filter).')';            
        }
        $where.=" AND enquiry.drop_status>0";
		$this->db->where($where);
	     return $this->db->count_all_results();   
    }

    
    public function raw_enquery() {
        $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product.product_name,tbl_product_country.country_name,tbl_institute.institute_name");
        $this->db->from("enquiry");
        $this->db->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid');
        $this->db->join('tbl_product', 'tbl_product.sb_id = enquiry.product_id', 'left');
        $this->db->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left');
        $this->db->join('tbl_institute', 'tbl_institute.institute_id = enquiry.institute_id', 'left');
        $this->db->where('enquiry.status','1');
        return $this->db->get();
    }

 
    // All duplicate data
    public function all_duplicate() {

        return $this->db->query('select * from enquiry group by email HAVING COUNT(enquiry_id) > 1')->result();
    }

    public function read_by_id($enquiry_id) {
        return $this->db->select("*")
                        ->from('enquiry')
                        ->where('enquiry_id', $enquiry_id)
                        ->where('is_delete', '1')
                        ->get();
    }

    public function enquiry_by_id($enquiry_id) {
        return $this->db->select("*,enquiry.created_by as enq_created_by,enquiry.city_id as enquiry_city_id,enquiry.state_id as enquiry_state_id,enquiry.created_date,enquiry.status,enquiry.address,tbl_product_country.country_name,tbl_product_country.id as country_id,tbl_product.product_name,tbl_center.center_name,enquiry.lead_created_date")
                        ->from($this->table)
                        ->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left')
                        ->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.created_by', 'left')
                        ->join('tbl_product', 'tbl_product.sb_id=enquiry.product_id', 'left')
                        ->join('tbl_center', 'tbl_center.center_id=enquiry.center_id', 'left')
                        ->join('tbl_datasource', 'tbl_datasource.datasource_id=enquiry.datasource_id', 'left')
                        ->where('enquiry.enquiry_id', $enquiry_id)
                        //->where('enquiry.is_delete', '1')
                        ->get()
                        ->row();
    }

    public function enquiry_by_code($code) {
        $this->db->where('Enquery_id', $code);
        return $this->db->get('enquiry')->row();
    }

    


    /* -----------------------search Start---------------------- */

    public function search_data($serach_key) {
      
        if ($serach_key != '') {
            $user_id = $this->session->user_id;
            $user_role = $this->session->user_role;
            $region_id = $this->session->region_id;
            $assign_country = $this->session->country_id;
            $assign_region = $this->session->region_id;
            $assign_territory = $this->session->territory_id;
            $assign_state = $this->session->state_id;
            $assign_city = $this->session->city_id;

            if ($user_role == 3) {
                return $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name")
                                ->from($this->table)
                                ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                                ->where('enquiry.is_delete', '1')
                                ->where('enquiry.country_id', $assign_country)
                                ->like('enquiry.phone', $serach_key)
                                ->or_like('enquiry.email', $serach_key)
                                ->or_like('enquiry.name', $serach_key)
                                ->order_by('enquiry.enquiry_id', 'DESC')
                                ->limit(10)
                                ->get();
            } else if ($user_role == 4) {
                return $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name,tbl_product_country.country_name,tbl_institute.institute_name")
                                ->from($this->table)
                                ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                                ->join('tbl_product_country', 'tbl_product_country.id=enquiry.country_id', 'left')
                                ->join('tbl_institute', 'tbl_institute.institute_id = enquiry.institute_id', 'left')
                                ->where('enquiry.is_delete', '1')
                                //->where('enquiry.region_id',$assign_region)
                                ->like('enquiry.phone', $serach_key)
                                ->or_like('enquiry.email', $serach_key)
                                ->or_like('enquiry.name', $serach_key)
                                ->order_by('enquiry.enquiry_id', 'DESC')
                                ->limit(10)
                                ->get();
            } else if ($user_role == 5) {
                return $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name")
                                ->from($this->table)
                                ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                                ->where('enquiry.is_delete', '1')
                                ->where('enquiry.territory_id', $assign_territory)
                                ->like('enquiry.phone', $serach_key)
                                ->or_like('enquiry.email', $serach_key)
                                ->or_like('enquiry.name', $serach_key)
                                ->order_by('enquiry.enquiry_id', 'DESC')
                                ->limit(10)
                                ->get();
            } else if ($user_role == 6) {

                return $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name")
                                ->from($this->table)
                                ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                                ->where('enquiry.is_delete', '1')
                                ->where('enquiry.state_id', $assign_state)
                                ->like('enquiry.phone', $serach_key)
                                ->or_like('enquiry.email', $serach_key)
                                ->or_like('enquiry.name', $serach_key)
                                ->order_by('enquiry.enquiry_id', 'DESC')
                                ->limit(10)
                                ->get();
            } else if ($user_role == 7) {

                return $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name")
                                ->from($this->table)
                                ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                                ->where('enquiry.is_delete', '1')
                                ->where('enquiry.city_id', $assign_city)
                                ->like('enquiry.phone', $serach_key)
                                ->or_like('enquiry.email', $serach_key)
                                ->or_like('enquiry.name', $serach_key)
                                ->order_by('enquiry.enquiry_id', 'DESC')
                                ->limit(10)
                                ->get();
            } elseif ($user_role == 8 || $user_role == 9) {
                return $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name")
                                ->from('enquiry')
                                ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                                ->like('enquiry.phone', $serach_key)
                                ->or_like('enquiry.email', $serach_key)
                                ->or_like('enquiry.name', $serach_key)
                                ->where('enquiry.is_delete', 1)
                                ->where('enquiry.aasign_to', $user_id)
                                ->or_where('enquiry.created_by', $user_id)
                                ->order_by('enquiry.enquiry_id', 'DESC')
                                ->limit(10)
                                ->get();
            } else {
                return $this->db->select("enquiry.*,lead_source.icon_url,lead_source.lsid,lead_source.score_count,lead_source.lead_name")
                                ->from($this->table)
                                ->join('lead_source', 'enquiry.enquiry_source = lead_source.lsid')
                                ->where('enquiry.is_delete', '1')
                                ->like('enquiry.phone', $serach_key)
                                ->or_like('enquiry.email', $serach_key)
                                ->or_like('enquiry.name', $serach_key)
                                ->order_by('enquiry.enquiry_id', 'DESC')
                                ->limit(10)
                                ->get();
            }
        }
    }

    /* -------end Search------------------ */
    /* ----------show notification----------- */

    public function assign_enquery($key, $assign_employee, $enquiry_code) {
       
    }

    public function assign_notification_update($code) {
        $this->db->set('assign_status', 1);
        $this->db->where('enq_code', $code);
        $this->db->where('notification_type', 0);
        $this->db->update('tbl_assign_notification');
    }

    public function get_assigned() {
        $query = $this->db->select('*')
                ->from('tbl_assign_notification')
                ->where('assign_status', 0)
                ->where('notification_type', 0)
                ->where('assign_to', $this->session->user_id)
                ->group_by("enq_code")
                ->get();

        if ($query->num_rows() > 0) {

            return $query;
        } else {

            return false;
        }
    }

    /* ---------end notification------------ */

    public function check_exists($enquiry_id) {
        $result = $this->db->select("checked")
                ->from("enquiry")
                ->where('enquiry_id', $enquiry_id)
                ->where('checked', null)
                ->get()
                ->num_rows();

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data = []) {
        return $this->db->where('enquiry_id', $data['enquiry_id'])
                        ->update($this->table, $data);
    }

    public function delete($enquiry_id = null) {
        $this->db->where('enquiry_id', $enquiry_id)
                ->delete($this->table);

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function check_existance($code = null) {
        $this->db->where('Enquery_id', $code);
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        if (!empty($result)) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

    //Get Message Templates...
    public function get_templates($template) {

        return $this->db->select('*')
                        ->from('api_templates')
                        ->where('temp_id', $template)
                        ->get()
                        ->row();
    }

    //Get all customer types..
    public function customers_types() {
        $company=$this->session->userdata('companey_id');
        return $this->db->select('*')
                        ->from('tbl_customer_type')
						->where('comp_id', $company)
                        ->get()
                        ->result();
    }

    //Add customer type in master..
    public function add_customer_type($data) {

        $this->db->insert('tbl_customer_type', $data);
    }

    //Update customer type in master..
    public function update_customer_type($data, $id) {

        return $this->db->where('cus_id', $id)
                        ->update('tbl_customer_type', $data);
    }

    //Delete customer type in master...
    public function delete_customer_types($delete_ids) {

        for ($i = 0; $i < count($delete_ids); $i++) {


            $this->db->where('cus_id', $delete_ids[$i])->delete('tbl_customer_type');
        }
    }

    //Get all channel partner type
    public function channel_partner_type_list() {

        return $this->db->select('*')
                        ->from('tbl_channel_partner')
                        ->get()
                        ->result();
    }

    //Add channel partner type...
    public function add_channel_partner($data) {

        $this->db->insert('tbl_channel_partner', $data);
    }

    //Update channel partner type.....
    public function update_channel_partner($data, $id) {

        return $this->db->where('ch_id', $id)->update('tbl_channel_partner', $data);
    }

    //Delete channel partner type..
    public function delete_channel_partner_type($delete_ids) {

        for ($i = 0; $i < count($delete_ids); $i++) {


            $this->db->where('ch_id', $delete_ids[$i])->delete('tbl_channel_partner');
        }
    }

    //Public function Add name prefix....
    public function name_prefix($data) {

        $this->db->insert('tbl_name_prefix', $data);
    }

    public function name_partner($data) {

        $this->db->insert('tbl_partner_type', $data);
    }

    //Get list of name prefix list..
    public function name_prefix_list() {
        $company=$this->session->userdata('companey_id');
        return $this->db->select('*')
                        ->from('tbl_name_prefix')
						->where('comp_id', $company)
                        ->order_by('np_id', 'asc')
                        ->get()
                        ->result();
    }

    public function name_partner_list() {

        return $this->db->select('*')
                        ->from('tbl_partner_type')
                        ->order_by('type', 'asc')
                        ->get()
                        ->result();
    }

    //Update name prefix..
    public function update_name_prefixes($id, $data) {

        $this->db->where('np_id', $id)->update('tbl_name_prefix', $data);
    }

    public function update_name_partner($id, $data) {

        $this->db->where('p_tid', $id)->update('tbl_partner_type', $data);
    }

    //Delete name prefixes..
    public function delete_name_prefixes($delete_ids) {

        for ($i = 0; $i < count($delete_ids); $i++) {


            $this->db->where('np_id', $delete_ids[$i])->delete('tbl_name_prefix');
        }
    }

    public function delete_name_ptype($delete_ids) {

        for ($i = 0; $i < count($delete_ids); $i++) {


            $this->db->where('p_tid', $delete_ids[$i])->delete('tbl_partner_type');
        }
    }

  
    //Mail templates access..
    public function access_mail_temp() {

        return $this->db->select('*')
                        ->from('api_templates')
                        //->join('mail_template_attachments','mail_template_attachments.templt_id=api_templates.temp_id')
                        ->where('temp_for', 3)
                        ->get()
                        ->result();
    }

    //Mail templates access..for qr
    public function access_mail_temps() {

        return $this->db->select('*')
                        ->from('api_templates')
                        //->join('mail_template_attachments','mail_template_attachments.templt_id=api_templates.temp_id')
                        ->where('temp_for', 3)
                        ->get()
                        ->result();
    }

    public function get_custemertype($lead_code) {

        return $this->db->select('*')
                        ->from('enquiry')
                        ->where('Enquery_id', $lead_code)
                        ->where('enquiry_cust_type', 1)
                        ->get();
    }

    //Mail signature
    public function get_signature() {

        return $this->db->select('*')
                        ->from('mail_signature')
                        ->where('user_id', $this->session->user_id)
                        ->get()
                        ->row();
    }

    //Mail signaturefor qr
    public function get_signatures() {

        return $this->db->select('*')
                        ->from('mail_signature')
                        ->where('user_id', 1)
                        ->get()
                        ->row();
    }

    //Normal mail templates..
    public function normal_mail_template() {
        return $this->db->select('*')
                        ->from('api_templates')
                        ->join('mail_template_attachments', 'mail_template_attachments.templt_id=api_templates.temp_id', 'left')
                        ->where('temp_for', 3)
                        ->where('response_type', 1)
                        ->where('comp_id', $this->session->companey_id)
                        ->get()
                        ->result();
    }
    
    /*********************************************find personel data ajax***************************************************/
    public function getenq_by_phone($phone){
		$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $cpny_id=$this->session->companey_id;
        $phone=str_replace('.', '', $phone);
        $where = "enquiry.is_delete=1";
		$where.=" AND phone=".$phone;
		$where.=" AND comp_id=$cpny_id";
        return $this->db->select('*')
                        ->from('enquiry')
                        ->where($where)
						//->group_by('enquiry.enquiry_id')
                        ->get()
                        ->row();
   }	   
   public function all_states($states) {

        return $this->db->select('*')->from('state')->where('country_id',$states)->get()->result();
    }
    
      public function get_sent_whats_app() {
			  $my_apikey = "CW9FFHPDJGC5RXUWSIC6";
          $api_url = "http://panel.apiwha.com/get_messages.php";
           $api_url .= "?apikey=". urlencode ($my_apikey);
           $api_url .= "&type=OUT";
          /// $api_url .= "&text=". urlencode ($message);
           $curl = curl_init();
           curl_setopt_array($curl, array(
           CURLOPT_URL => "$api_url",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "GET",
        ));
		
		   $response = curl_exec($curl);
		 $array_search=array();
		 if(!empty($response)){
		 foreach(json_decode($response) as $res){
			 if(in_array($res->to,$array_search)){
			 }else{
		 $array_search==array_push($array_search,$res->to);	 
			 }}}
            return $response = $array_search;		 
       }
	   
	    public function get_received_whats_app() {
			  $my_apikey = "CW9FFHPDJGC5RXUWSIC6";
          $api_url = "http://panel.apiwha.com/get_messages.php";
           $api_url .= "?apikey=". urlencode ($my_apikey);
           $api_url .= "&type=IN";
          /// $api_url .= "&text=". urlencode ($message);
           $curl = curl_init();
           curl_setopt_array($curl, array(
           CURLOPT_URL => "$api_url",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "GET",
        ));
		
		   $response = curl_exec($curl);
		 $array_search=array();
/*		 foreach(json_decode($response) as $res){
			 if(in_array($res->from,$array_search)){
			 }else{
		 $array_search==array_push($array_search,$res->from);	 
			 }}
*/            
			foreach(json_decode($response) as $res){
		 		array_push($array_search,$res->from);	 
			 }


			return $array_search;		 
       }

       public function set_received_whats_app_status($received_whats_app){
       		foreach ($received_whats_app as $mobile_no) {       			
       			$wp_mob_num = $mobile_no;
		        if (strlen($mobile_no) == 12 && substr($mobile_no, 0, 2) == "91")
		            $wp_mob_num = substr($mobile_no, 2, 10);

		        $this->db->where('mobile_no',$wp_mob_num);		        	
		        
		        if ($this->db->get('whatsapp_send_log')->num_rows()) {
		        	$update_arr = array(
		        					'status' => 99
		        				);
		        	$this->db->where('mobile_no',$wp_mob_num);
		        	$this->db->update('whatsapp_send_log',$update_arr);	
		        }else{
		        	$update_arr = array(
		        					'status' => 99,
		        					'mobile_no' => $wp_mob_num,		        					
		        				);
		        	$this->db->where('mobile_no',$wp_mob_num);

		        	$this->db->insert('whatsapp_send_log',$update_arr);	
		        }
		        
       		}
       }
	     public function get_drop_list() {
		$this->db->where('comp_id', $this->session->companey_id);
        $query = $this->db->get('tbl_drop');
		
        return $query->result();
    }
	public function get_leadscore_list() {
		$this->db->where('comp_id',$this->session->companey_id);
        $query = $this->db->get('lead_score');
        return $query->result();
    }
	/*----------------------start api-------------------************/
	  public function product_api($company){
		            $this->db->select('*');
					$this->db->from("tbl_product_country");
					$this->db->where('comp_id', $company);
                   return $this->db->get()->result();
                    
	  }
	  public function product_list_api($user_id){		    
                     $this->db->select('process');
                    $this->db->where('pk_i_admin_id',$user_id);                    
                    $user_res	=	$this->db->get('tbl_admin')->row_array();
                    $user_res = explode(',', $user_res['process']);
                    $this->db->select('*');
                    $this->db->from('tbl_product');
                    $this->db->where_in('sb_id',$user_res);                    
                    $this->db->order_by('sb_id','ASC');
                    return $this->db->get()->result();
		}
    public function active_enqueries_api($id,$type,$user_role,$process='',$offset=-1,$limit=-1) 
    { 
        $all_reporting_ids    =   $this->common_model->get_categories($id,$type);
        $this->db->from($this->table);        
        $where  = "";
        $datatype = $type;
        $where .= " enquiry.status=$datatype ";
        $user_id   = $id;
        $user_role = $user_role;

        $where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 
        $where.=" AND enquiry.drop_status=0";		
        if (!empty($process)) 
        {
        	$where.=" AND enquiry.product_id IN (".$process.")";		        	
        }
        $this->db->where($where);

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
        //print_r($_POST);exit();
        if(!empty($_POST['filters']))
        {
            $match_list = array('date_from','date_to','phone');

            $this->db->group_start();
            foreach ($_POST['filters'] as $key => $value)
            {
                  if(in_array($key,$match_list) || $this->db->field_exists($key, 'enquiry'))
                  {
                      if(in_array($key, $match_list))
                      {
                          $fld = 'created_date';
                          if($type=='2')
                            $fld = 'lead_created_date';
                          else if($type=='3')
                            $fld = 'client_created_date';

                          if($key=='date_from')
                            $this->db->where($fld.'>=',$value);

                          if($key=='date_to')
                            $this->db->where($fld.'<=',$value);

                          if($key=='phone')
                            $this->db->where('phone LIKE "%'.$value.'%" OR other_phone LIKE "%'.$value.'%"');
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

       
		$this->db->order_by('enquiry.enquiry_id','DESC');
    //for pagination api

    if($offset!=-1 && $limit!=-1)
    {  
        $this->db->limit($limit,$offset);
    }

		return $query = $this->db->get();
        //return $query->result();
    }
	  public function enquiry_detail_for_api($enquiry_code) {
        return $this->db->select("*,enquiry.created_date,enquiry.comp_id as enq_comp_id,enquiry.address,tbl_product_country.country_name as pcountry_name,tbl_product_country.id as country_id,tbl_product.product_name,tbl_center.center_name,lead_source.lead_name as enquiry_source_name,CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name) as created_by_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name")
                        ->from($this->table)
                        ->join('tbl_product_country', 'tbl_product_country.id=enquiry.enquiry_subsource', 'left')
                        ->join('tbl_admin', 'tbl_admin.pk_i_admin_id=enquiry.created_by', 'left')
                        ->join('tbl_admin as tbl_admin2', 'tbl_admin2.pk_i_admin_id=enquiry.aasign_to', 'left')
                        ->join('tbl_product', 'tbl_product.sb_id=enquiry.product_id', 'left')
                        ->join('tbl_center', 'tbl_center.center_id=enquiry.center_id', 'left')
                        ->join('tbl_datasource', 'tbl_datasource.datasource_id=enquiry.datasource_id', 'left')
						->join('state','state.id=enquiry.state_id','left')
						->join('lead_source','lead_source.lsid=enquiry.enquiry_source','left')
		    //->join('tbl_territory','tbl_territory.territory_id=enquiry.territory_id','left')
		                 ->join('city','city.id=enquiry.city_id','left')
                        ->where('enquiry.Enquery_id', $enquiry_code)
                        ->where('enquiry.is_delete', '1')
						->group_by('enquiry.Enquery_id')
                        ->get()
                        ->row();
	  }
       public function user_list_api($comp) {
        return $this->db->select('*')
                        ->from('tbl_admin')
                        ->where('companey_id',$comp)
                        ->get()
                        ->result();
    }
 
    public function assign_enquery_api($key,$assign_employee,$enquiry_code,$user_id){
                        $this->db->set('aasign_to',$assign_employee);
			            $this->db->set('assign_by',$user_id);
			            $this->db->set('update_date',date('Y-m-d H:i:s'));
			            $this->db->where('Enquery_id',$key);
			            $this->db->update('enquiry');
			            $this->db->set('assign_to',$assign_employee);
			            $this->db->set('assign_by',$user_id);
			            $this->db->set('assign_date',date('Y-m-d H:i:s'));
			            //$this->db->set('enq_id',$key);
			             $this->db->set('enq_code',$enquiry_code);
			            
			             $this->db->set('assign_status',0);
			             $this->db->insert('tbl_assign_notification');

                       }

 public function get_leadsource_list_api($comp) {
		$this->db->where('comp_id', $comp);
        $query = $this->db->get('lead_source');
        return $query->result();
    }
 public function get_leadscore_list_api($comp) {
		$this->db->where('comp_id', $comp);
        $query = $this->db->get('lead_score');
        return $query->result();
    }
 public function get_drop_list_api($comp) {
		$this->db->where('comp_id', $comp);
        $query = $this->db->get('tbl_drop');
        return $query->result();
    }	


      function all_rep($from,$to,$employe,$phone,$email,$address,$createdby,$source,$assign,$datasource,$enq_product,$company){      
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
        $this->db->select('*');
        //$this->db->select('enquiry.name_prefix,enquiry.name,enquiry.lastname,enquiry.phone,enquiry.email,enquiry.gender,lead_source.lead_name,tbl_subsource.subsource_name,lead_description.description,lead_stage.lead_stage_name,enquiry.status as inq_status,enquiry.created_date as inq_created_date, CONCAT(tbl_admin.s_display_name,tbl_admin.last_name) as created_by_name,CONCAT(admin2.s_display_name,admin2.last_name) as assign_to_name,tbl_product.product_name');

        $this->db->from('enquiry');
        
        $where = "enquiry_id > 0";      


        if ($from && $to) {
            $to = str_replace('/', '-', $to);
            $from = str_replace('/', '-', $from);            

            $from = date('Y-m-d H:i:s', strtotime($from));

            $to = date('Y-m-d H:i:s', strtotime($to));            

            $where .= " AND created_date BETWEEN '$from' AND '$to'";
        } else if ($from && !$to) {
            $from = str_replace('/', '-', $from);            

            $from = date('Y-m-d H:i:s', strtotime($from));

            $where .= " AND created_date LIKE '%$from%'";

        } else if (!$from && $to) {            

            $to = str_replace('/', '-', $to);

            $to = date('Y-m-d H:i:s', strtotime($to));
           

            $where .= " AND created_date LIKE '%$to%'";
        }
       if($employe!=''){
			
			$where .= " AND (created_by IN (".implode(',', $employe).')';
			$where .= " OR .aasign_to IN (".implode(',', $employe).'))';  
			
           /* $where .= " AND (enquiry.aasign_to=$employe";
           $where .= " OR enquiry.created_by=$employe)"; */
		   
        }
    
        if($source!=''){
           $where .= " AND enquiry_source IN (".implode(',', $source).')';  
        }
        if($company!=''){
           $where .= " AND company IN (".implode(',', $company).')';
        }
        if($datasource!=''){
           $where .= " AND datasource_id IN (".implode(',', $datasource).')';  
        }
        
        
        if($address!=''){
           $where .= " AND address IN (".implode(',', $address).')';  

        }
        if ($assign != '') {                
            $where .= " AND aasign_to IN (".implode(',', $assign).')';                                           
        }

        if($enq_product!=''){
           $where .= " AND enquiry.product_id IN (".implode(',', $enq_product).')';  
        }

         if($createdby!=''){
           $where .= " AND created_by IN (".implode(',', $createdby).')';  
        }
         if($phone!=''){
           $where .= " AND phone IN (".implode(',', $phone).')';  
        }
         if($email!=''){
           $where .= " AND email IN (".implode(',', $email).')';  
        }

        //$this->db->join("(select q.comm_id as comm_id, q.created_date, q.lead_id from tbl_comment as q  GROUP BY q.comm_id ORDER BY q.comm_id DESC ) as tbl_comment1", 'tbl_comment1.lead_id=enquiry.Enquery_id', 'left');
		$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
		$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 
        
		$this->db->where($where);
        
        $this->db->group_by('enquiry.enquiry_id');
        //$this->db->limit(1);
        
        return   $this->db->get()->result();
        
    }

     public function all_enqueries($status=0) {
$all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
$cpny_id=$this->session->companey_id;
        $where = "";
		$where .= " ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where.=" AND enquiry.comp_id=$cpny_id";

        $process = $this->session->userdata('process');
      
      if($status){
        if(is_array($status))
          $status = implode(',', $status);
        $this->db->where('status IN ('.$status.')');
      }

        return $this->db->select('*')
                        ->from('enquiry')
                        ->where($where)
                        ->where_in('product_id',$process)
						->group_by('enquiry.enquiry_id')
                        ->get()
                        ->result();
                        // print_r($this->db->last_query());
    }
    
    
    // public function all_enqueries_api($userid,$companyid)
    // {       
    //     // print_r($_SESSION);die;
    //     $all_reporting_ids    =   $this->common_model->get_categories($userid);
    //     $cpny_id=$companyid;
    //     $where = "enquiry.is_delete=1";
	// 	$where .= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    // 	$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
    //     $where.=" AND enquiry.comp_id=$cpny_id";

    //     $query = $this->db->query("SELECT status FROM enquiry WHERE $where AND enquiry.status = 1 GROUP BY enquiry.enquiry_id");
    //     $enquiry = $query->num_rows();

    //     $query = $this->db->query("SELECT status FROM enquiry WHERE $where AND enquiry.status = 2 GROUP BY enquiry.enquiry_id");
    //     $lead = $query->num_rows();

    //     $query = $this->db->query("SELECT status FROM enquiry WHERE $where AND enquiry.status = 3 GROUP BY enquiry.enquiry_id");
    //     $client = $query->num_rows();

    //     $query2 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND DATE(created_date) = CURRENT_DATE AND status = 1");
    //     $enq_ct = $query2->num_rows();

    //     $query2 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND DATE(created_date) = CURRENT_DATE AND status = 2");
    //     $lead_ct = $query2->num_rows();

    //     $query2 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND DATE(created_date) = CURRENT_DATE AND status = 3");
    //     $client_ct = $query2->num_rows();

    //     $query3 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND DATE(update_date) = CURRENT_DATE AND status = 1");
    //     $enq_ut = $query3->num_rows();

    //     $query3 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND DATE(update_date) = CURRENT_DATE AND status = 2");
    //     $lead_ut = $query3->num_rows();

    //     $query3 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND DATE(update_date) = CURRENT_DATE AND status = 3");
    //     $client_ut = $query3->num_rows();

    //     $query4 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND drop_status = 1 AND status = 1");
    //     $enq_drp = $query4->num_rows();

    //     $query4 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND drop_status = 1 AND status = 2");
    //     $lead_drp = $query4->num_rows();

    //     $query4 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND drop_status = 1 AND status = 3");
    //     $client_drp = $query4->num_rows();

    //     $query5 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND drop_status = 0 AND status = 1");
    //     $enq_active = $query5->num_rows();

    //     $query5 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND drop_status = 0 AND status = 2");
    //     $lead_active = $query5->num_rows();

    //     $query5 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND drop_status = 0 AND status = 3");
    //     $client_active = $query5->num_rows();

    //     $query6 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND aasign_to = '' AND status = 1");
    //     $enq_assign = $query6->num_rows();

    //     $query6 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND aasign_to = '' AND status = 2");
    //     $lead_assign = $query6->num_rows();

    //     $query6 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND aasign_to = '' AND status = 3");
    //     $client_assign = $query6->num_rows();

    //     $query7 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND lead_score = 1");
    //     $hot = $query7->num_rows();

    //     $query7 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND lead_score = 2");
    //     $warm = $query7->num_rows();

    //     $query7 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND lead_score = 3");
    //     $cold = $query7->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 01 AND status = 1");
    //     $ejan = $query8->num_rows();

    //     $query9 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 01 AND status = 2");
    //     $ljan = $query9->num_rows();

    //     $query10 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 01 AND status = 3");
    //     $cjan = $query10->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 02 AND status = 1");
    //     $efeb = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 02 AND status = 2");
    //     $lfeb = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 02 AND status = 3");
    //     $cfeb = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 03 AND status = 1");
    //     $emar = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 03 AND status = 2");
    //     $lmar = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 03 AND status = 3");
    //     $cmar = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 04 AND status = 1");
    //     $eapr = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 04 AND status = 2");
    //     $lapr = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 04 AND status = 3");
    //     $capr = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 05 AND status = 1");
    //     $emay = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 05 AND status = 2");
    //     $lmay = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 05 AND status = 3");
    //     $cmay = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 06 AND status = 1");
    //     $ejun = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 06 AND status = 2");
    //     $ljun = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 06 AND status = 3");
    //     $cjun = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 07 AND status = 1");
    //     $ejuly = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 07 AND status = 2");
    //     $ljuly = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 07 AND status = 3");
    //     $cjuly = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 08 AND status = 1");
    //     $eaug = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 08 AND status = 2");
    //     $laug = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 08 AND status = 3");
    //     $caug = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 09 AND status = 1");
    //     $esep = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 09 AND status = 2");
    //     $lsep = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 09 AND status = 3");
    //     $csep = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 10 AND status = 1");
    //     $eoct = $query8->num_rows();
        
    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 10 AND status = 2");
    //     $loct = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 10 AND status = 3");
    //     $coct = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 11 AND status = 1");
    //     $enov = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 11 AND status = 2");
    //     $lnov = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 11 AND status = 3");
    //     $cnov = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 12 AND status = 1");
    //     $edec = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 12 AND status = 2");
    //     $ldec = $query8->num_rows();

    //     $query8 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND month(DATE(created_date)) = 12 AND status = 3");
    //     $cdec = $query8->num_rows();

    //     $query12 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND enquiry_source = 3 AND status = 1");
    //     $raw = $query12->num_rows();

    //     $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 1 AND status = 1");
    //     $upe = $query13->num_rows();

    //     $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 1 AND status = 2");
    //     $upl = $query13->num_rows();

    //     $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 1 AND status = 3");
    //     $upc = $query13->num_rows();

    //     $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 2 AND status = 1");
    //     $pbe = $query13->num_rows();

    //     $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 2 AND status = 2");
    //     $pbl = $query13->num_rows();

    //     $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 2 AND status = 3");
    //     $pbc = $query13->num_rows();

    //     $despenqqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_score =  lead_stage.stg_id AND enquiry.status = 1)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

    //     $despenq = $despenqqry->result();

    //     $despleadqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_score =  lead_stage.stg_id AND enquiry.status = 2)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

    //     $desplead = $despleadqry->result();

    //     $despcliqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_score =  lead_stage.stg_id AND enquiry.status = 3)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

    //     $despcli = $despcliqry->result();

    //     $enquiry_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 1)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

    //     $EnquirySrc = $enquiry_src_qry->result();

    //     $lead_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 2)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

    //     $leadSrc = $lead_src_qry->result();

    //     $Client_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 3)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

    //     $ClientSrc = $Client_src_qry->result();


    //     // $query14 = $this->db->query("SELECT enquiry_id FROM `lead_stage` WHERE comp_id = $cpny_id ORDER BY stg_id ASC");
    //     // $pbc = $query14->num_rows(); 

    //     $indiamap= array('upe'=>$upe,'upl'=>$upl,'upc'=>$upc,'pbe'=>$pbe,'pbl'=>$pbl,'pbc'=>$pbc);

    //     $funnelchartAry = array('enquiry'=>$enquiry,'lead'=>$lead,"client"=>$client,'enq_ct'=>$enq_ct,'lead_ct'=>$lead_ct,'client_ct'=>$client_ct,'enq_ut'=>$enq_ut,'lead_ut'=>$lead_ut,'client_ut'=>$client_ut,'enq_drp'=>$enq_drp,'lead_drp'=>$lead_drp,'client_drp'=>$client_drp,'enq_active'=>$enq_active,'lead_active'=>$lead_active,'client_active'=>$client_active,'enq_assign'=>$enq_assign,'lead_assign'=>$lead_assign,'client_assign'=>$client_assign,'hot'=>$hot,'warm'=>$warm,'cold'=>$cold,'ejan'=>$ejan,'ljan'=>$ljan,'cjan'=>$cjan,'efeb'=>$efeb,'lfeb'=>$lfeb,'cfeb'=>$cfeb,'emar'=>$emar,'lmar'=>$lmar,'cmar'=>$cmar,'eapr'=>$eapr,'lapr'=>$lapr,'capr'=>$capr,'emay'=>$emay,'lmay'=>$lmay,'cmay'=>$cmay,'ejun'=>$ejun,'ljun'=>$ljun,'cjun'=>$cjun,'ejuly'=>$ejuly,'ljuly'=>$ljuly,'cjuly'=>$cjuly,'eaug'=>$eaug,'laug'=>$laug,'caug'=>$caug,'esep'=>$esep,'lsep'=>$lsep,'csep'=>$csep,'eoct'=>$eoct,'loct'=>$loct,'coct'=>$coct,'enov'=>$enov,'lnov'=>$lnov,'cnov'=>$cnov,'edec'=>$edec,'ldec'=>$ldec,'cdec'=>$cdec,'raw'=>$raw,'indiamap'=>$indiamap,'desposition_enquiry'=>$despenq,'desposition_lead'=>$desplead,'desposition_client'=>$despcli,'EnquirySrc'=>$EnquirySrc,'leadSrc'=>$leadSrc,'ClientSrc'=>$ClientSrc);

    //     return $funnelchartAry;

    // }

    public function all_enqueries_api($userid,$companyid,$process)
    {   
        $enquiry = $lead = $client = $enq_ct = $lead_ct = $client_ct = $enq_ut = $lead_ut = $client_ut = $enq_drp = $lead_drp = $client_drp = $enq_active = $lead_active = $client_active = $enq_assign = $lead_assign = $client_assign = $hot = $warm = $cold = $ejan = $ljan = $cjan = $efeb = $lfeb = $cfeb = $emar = $lmar = $cmar = $eapr = $lapr = $capr = $emay = $lmay = $cmay = $ejun = $ljun = $cjun = $ejuly = $ljuly = $cjuly = $eaug = $laug = $caug = $esep = $lsep = $csep = $eoct = $loct = $coct = $enov = $lnov = $cnov = $edec = $ldec = $cdec = 0;



        // print_r($_SESSION);die;
        $all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
        //$where = "enquiry.is_delete=1";
		$where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
      $where .=" AND product_id IN ($process)";
        $where.=" AND enquiry.comp_id=$cpny_id";

        $enquiry_separation = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING',$companyid);

        $entities[1] = array(  
                            'title'=>display('enquiry',$companyid),
                            'tab'=>array(
                                        'all'=>0,
                                        'created_today'=>0,
                                        'updated_today'=>0,
                                        'active'=>0,
                                        'dropped'=>0,
                                        'unassigned'=>0,
                                        ),
                            );

        $entities[2] = array(
                            'title'=>display('lead',$companyid),
                            'tab'=>array(
                                        'all'=>0,
                                        'created_today'=>0,
                                        'updated_today'=>0,
                                        'active'=>0,
                                        'dropped'=>0,
                                        'unassigned'=>0,
                                        ),
                            );

        $entities[3] = array(
                            'tab'=>array(
                                        'all'=>0,
                                        'created_today'=>0,
                                        'updated_today'=>0,
                                        'active'=>0,
                                        'dropped'=>0,
                                        'unassigned'=>0,
                                        ),
                            );

        if(!empty($enquiry_separation))
        {
          $enquiry_separation = json_decode($enquiry_separation, true);

          foreach ($enquiry_separation as $key => $value) 
          {
              $entities[$key] = array('title'=>$value['title'],
                                        'tab'=>array(),
                                  );
          }
        }
      
        $query = $this->db->query("SELECT count(enquiry.enquiry_id)counter,enquiry.status FROM enquiry WHERE $where GROUP BY enquiry.status");
        $result = $query->result();

        foreach($result as $key=> $r)
        {
          $entities[$r->status]['tab']['all'] = !empty($r->counter)?$r->counter:0;
        }
        
        $query2 = $this->db->query("SELECT count(enquiry_id)counter,enquiry.status FROM `enquiry` WHERE $where AND DATE(created_date) = CURRENT_DATE GROUP BY enquiry.status");

        $result2 = $query2->result();

        foreach($result2 as $r)
        {
            $entities[$r->status]['tab']['created_today'] = !empty($r->counter)?$r->counter:0;
        }

        $query3 = $this->db->query("SELECT count(enquiry_id)counter,enquiry.status FROM `enquiry` WHERE $where AND DATE(update_date) = CURRENT_DATE GROUP BY enquiry.status");

        $result3 = $query2->result();

        foreach($result3 as $r)
        {
            $entities[$r->status]['tab']['updated_today'] = !empty($r->counter)?$r->counter:0;
        }

        $query4 =  $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason,enquiry.status FROM enquiry left JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND tp.drop_reason IS NULL GROUP BY enquiry.status");
        $result4 = $query4->result();

        foreach($result4 as $r)
        {
            $entities[$r->status]['tab']['active'] = !empty($r->counter)?$r->counter:0;
        }

        $query5 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND drop_status = 1 GROUP BY enquiry.status");

        $result5 = $query5->result();
        foreach($result5 as $r)
        {
            $entities[$r->status]['tab']['dropped'] = !empty($r->counter)?$r->counter:0;
        }

        $query6 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND aasign_to IS NULL GROUP BY enquiry.status");

        $result6 = $query6->result();
        foreach($result6 as $r)
        {
            $entities[$r->status]['tab']['unassigned'] = !empty($r->counter)?$r->counter:0;
        }

        echo json_encode($entities);
        exit();

        $query7 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.lead_score FROM `enquiry` WHERE $where GROUP BY enquiry.lead_score");

        $result7 = $query7->result();
        foreach($result7 as $r)
        {
            if($r->lead_score == 1)
            {
                $hot = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->lead_score == 2)
            {
                $warm = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->lead_score == 3)
            {
                $cold = (!empty($r->counter)) ? $r->counter : 0;
            }
        }


        $query8 = $this->db->query("SELECT count(enquiry_id) counter,month(DATE(created_date)) month  FROM `enquiry` WHERE $where AND status = 1 GROUP BY month(DATE(created_date))");

        $result8 = $query8->result();

        foreach($result8 as $r)
        {
            if($r->month == 1 )
            {
                $ejan = $r->counter;
            }
            if($r->month == 2 )
            {
                $efeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $emar = $r->counter;
            }
            if($r->month == 4 )
            {
                $eapr = $r->counter;
            }
            if($r->month == 5 )
            {
                $emay = $r->counter;
            }
            if($r->month == 6 )
            {
                $ejun = $r->counter;
            }
            if($r->month == 7 )
            {
                $ejuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $eaug = $r->counter;
            }
            if($r->month == 9 )
            {
                $esep = $r->counter;
            }
            if($r->month == 10 )
            {
                $eoct = $r->counter;
            }
            if($r->month == 11 )
            {
                $enov = $r->counter;
            }
            if($r->month == 12 )
            {
                $edec = $r->counter;
            }
        }

        $query9 = $this->db->query("SELECT count(enquiry_id) counter,month(DATE(created_date)) month  FROM `enquiry` WHERE $where AND status = 2 GROUP BY month(DATE(created_date))");

        $result9 = $query9->result();

        foreach($result9 as $r)
        {
            if($r->month == 1 )
            {
                $ljan = $r->counter;
            }
            if($r->month == 2 )
            {
                $lfeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $lmar = $r->counter;
            }
            if($r->month == 4 )
            {
                $lapr = $r->counter;
            }
            if($r->month == 5 )
            {
                $lmay = $r->counter;
            }
            if($r->month == 6 )
            {
                $ljun = $r->counter;
            }
            if($r->month == 7 )
            {
                $ljuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $laug = $r->counter;
            }
            if($r->month == 9 )
            {
                $lsep = $r->counter;
            }
            if($r->month == 10 )
            {
                $loct = $r->counter;
            }
            if($r->month == 11 )
            {
                $lnov = $r->counter;
            }
            if($r->month == 12 )
            {
                $ldec = $r->counter;
            }
        }

        $query10 = $this->db->query("SELECT count(enquiry_id) counter,month(DATE(created_date)) month  FROM `enquiry` WHERE $where AND status = 3 GROUP BY month(DATE(created_date))");

        $result10 = $query10->result();

        foreach($result10 as $r)
        {
            if($r->month == 1 )
            {
                $cjan = $r->counter;
            }
            if($r->month == 2 )
            {
                $cfeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $cmar = $r->counter;
            }
            if($r->month == 4 )
            {
                $capr = $r->counter;
            }
            if($r->month == 5 )
            {
                $cmay = $r->counter;
            }
            if($r->month == 6 )
            {
                $cjun = $r->counter;
            }
            if($r->month == 7 )
            {
                $cjuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $caug = $r->counter;
            }
            if($r->month == 9 )
            {
                $csep = $r->counter;
            }
            if($r->month == 10 )
            {
                $coct = $r->counter;
            }
            if($r->month == 11 )
            {
                $cnov = $r->counter;
            }
            if($r->month == 12 )
            {
                $cdec = $r->counter;
            }
        }

        $query12 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND enquiry_source = 3 AND status = 1");
        $raw = $query12->num_rows();

        $query13 = $this->db->query("SELECT count(enquiry.enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND state_id = 1 GROUP BY enquiry.status");

        $result13 = $query13->result();
        foreach($result13 as $r)
        {
            if($r->status == 1)
            {
                $upl = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $upc = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $cold = (!empty($r->counter)) ? $r->counter : 0;
            }
        }

        $upe = $query13->num_rows();

        $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 1 AND status = 2");
        $upl = $query13->num_rows();

        $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 1 AND status = 3");
        $upc = $query13->num_rows();

        $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 2 AND status = 1");
        $pbe = $query13->num_rows();

        $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 2 AND status = 2");
        $pbl = $query13->num_rows();

        $query13 = $this->db->query("SELECT enquiry_id FROM `enquiry` WHERE $where AND state_id = 2 AND status = 3");
        $pbc = $query13->num_rows();

        $despenqqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_score =  lead_stage.stg_id AND enquiry.status = 1)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

        $despenq = $despenqqry->result();

        $despleadqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_score =  lead_stage.stg_id AND enquiry.status = 2)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

        $desplead = $despleadqry->result();

        $despcliqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_score =  lead_stage.stg_id AND enquiry.status = 3)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

        $despcli = $despcliqry->result();

        $enquiry_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 1)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

        $EnquirySrc = $enquiry_src_qry->result();

        $lead_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 2)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

        $leadSrc = $lead_src_qry->result();

        $Client_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 3)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

        $ClientSrc = $Client_src_qry->result();

        $enquiry_process = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.product_name FROM enquiry  LEFT JOIN tbl_product as tp ON tp.sb_id = enquiry.product_id WHERE $where AND enquiry.status = 1 AND tp.sb_id In ($process) GROUP BY tp.sb_id");
        $enquiry_processWise = $enquiry_process->result();
        
        $lead_process = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.product_name FROM enquiry  LEFT JOIN tbl_product as tp ON tp.sb_id = enquiry.product_id WHERE $where AND enquiry.status = 2 AND tp.sb_id In ($process) GROUP BY tp.sb_id");

        $lead_processWise = $lead_process->result();

        $client_process = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.product_name FROM enquiry  LEFT JOIN tbl_product as tp ON tp.sb_id = enquiry.product_id WHERE $where AND enquiry.status = 3 AND tp.sb_id In ($process) GROUP BY tp.sb_id");

        $client_processWise = $client_process->result();

        $enquiry_drop = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason FROM enquiry  right JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND enquiry.status = 1  AND tp.drop_reason IS NOT NULL GROUP BY tp.drop_reason");
        $enquiry_dropWise = $enquiry_drop->result();
        
        $lead_drop = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason FROM enquiry  LEFT JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND enquiry.status = 2  AND tp.drop_reason IS NOT NULL GROUP BY tp.drop_reason");

        $lead_dropWise = $lead_drop->result();

        $client_drop = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason FROM enquiry  LEFT JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND enquiry.status = 3  AND tp.drop_reason IS NOT NULL GROUP BY tp.drop_reason");

      $client_dropWise = $client_drop->result();

      $enq_count = $this->dashboard_model->countLead(2,$companyid,$userid,$process);
      //echo $this->db->last_query();exit();
      $enq_Sum = $this->dashboard_model->dataLead(2,$companyid,$userid,$process);
     // echo 'Count:'.$enq_count.'<br>value:';print_r($enq_Sum->result());
      //exit();
      $enqTime=$enq_Sum->row()->time;
      if ($enqTime!=0)
      {
        $enq_value = round(($enqTime/$enq_count),2);
      }
      else 
        $enq_value = 0;

      $lead_count = $this->dashboard_model->countLead(3,$companyid,$userid,$process);
      $lead_Sum = $this->dashboard_model->dataLead(3,$companyid,$userid,$process);

      $leadTime=$lead_Sum->row()->time;
      if ($leadTime!=0)
      {
        $lead_value = round(($leadTime/$lead_count),2);
      }
      else 
        $lead_value = 0;

      $followup = array(
                    array(
                      'key'=>display('enquiry',$companyid),
                      'time'=>$this->dashboard_model->secsToStr_api($enq_value),
                    ),
                    array(
                      'key'=>display('lead',$companyid),
                      'time'=> $this->dashboard_model->secsToStr_api($lead_value),
                    ),
                );

    $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING',$companyid);
    if(!empty($enquiry_separation))
    {
        $enquiry_separation = json_decode($enquiry_separation, true);
        foreach ($enquiry_separation as $key => $value) 
        {
                  $ctitle = $enquiry_separation[$key]['title']; 
                  $Count=$this->dashboard_model->countLead($key,$companyid,$userid,$process);
                  $sum=$this->dashboard_model->dataLead($key,$companyid,$userid,$process);
                  $stime= $sum->row()->time;

                  if($stime!=0)
                  {
                    $vvalue = round(($stime/$Count),2);
                  }
                  else 
                    $vvalue = 0;


                 $followup[]  = array(
                                  'key'=>$ctitle,
                                  'time'=>$this->dashboard_model->secsToStr_api($vvalue),
                                );
        }
    }
        // $query14 = $this->db->query("SELECT enquiry_id FROM `lead_stage` WHERE comp_id = $cpny_id ORDER BY stg_id ASC");
        // $pbc = $query14->num_rows(); 
    //print_r($followup);exit();

        $indiamap= array('upe'=>$upe,'upl'=>$upl,'upc'=>$upc,'pbe'=>$pbe,'pbl'=>$pbl,'pbc'=>$pbc);

        $funnelchartAry = array('enquiry'=>$enquiry,'lead'=>$lead,"client"=>$client,'enq_ct'=>$enq_ct,'lead_ct'=>$lead_ct,'client_ct'=>$client_ct,'enq_ut'=>$enq_ut,'lead_ut'=>$lead_ut,'client_ut'=>$client_ut,'enq_drp'=>$enq_drp,'lead_drp'=>$lead_drp,'client_drp'=>$client_drp,'enq_active'=>$enq_active,'lead_active'=>$lead_active,'client_active'=>$client_active,'enq_assign'=>$enq_assign,'lead_assign'=>$lead_assign,'client_assign'=>$client_assign,'hot'=>$hot,'warm'=>$warm,'cold'=>$cold,'ejan'=>$ejan,'ljan'=>$ljan,'cjan'=>$cjan,'efeb'=>$efeb,'lfeb'=>$lfeb,'cfeb'=>$cfeb,'emar'=>$emar,'lmar'=>$lmar,'cmar'=>$cmar,'eapr'=>$eapr,'lapr'=>$lapr,'capr'=>$capr,'emay'=>$emay,'lmay'=>$lmay,'cmay'=>$cmay,'ejun'=>$ejun,'ljun'=>$ljun,'cjun'=>$cjun,'ejuly'=>$ejuly,'ljuly'=>$ljuly,'cjuly'=>$cjuly,'eaug'=>$eaug,'laug'=>$laug,'caug'=>$caug,'esep'=>$esep,'lsep'=>$lsep,'csep'=>$csep,'eoct'=>$eoct,'loct'=>$loct,'coct'=>$coct,'enov'=>$enov,'lnov'=>$lnov,'cnov'=>$cnov,'edec'=>$edec,'ldec'=>$ldec,'cdec'=>$cdec,'raw'=>$raw,'indiamap'=>$indiamap,'desposition_enquiry'=>$despenq,'desposition_lead'=>$desplead,'desposition_client'=>$despcli,'EnquirySrc'=>$EnquirySrc,'leadSrc'=>$leadSrc,'ClientSrc'=>$ClientSrc,'enquiry_processWise'=>$enquiry_processWise,'lead_processWise'=>$lead_processWise,'client_processWise'=>$client_processWise,'enquiry_dropWise'=>$enquiry_dropWise,'lead_dropWise'=>$lead_dropWise,'client_dropWise'=>$client_dropWise);
        $funnelchartAry['followup'] = $followup;
        return $funnelchartAry;
    }

    public function sourceDataChart($userid,$companyid)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        // $where.=" AND enquiry.comp_id=$cpny_id";

        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            $filter=json_decode($_POST['datas']);
            if(!empty($filter->from_date) AND !empty($filter->to_date)){
                $from_date=$filter->from_date;
                $to_date=$filter->to_date;
                $where.=" AND enquiry.created_date >= $to_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($filter->users)){
                $users=$filter->users;
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($filter->state_id)){
                $state_id=$filter->state_id;
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($filter->city_id)){
                $city_id=$filter->city_id;
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        } 
        $enqAyr = array(); 
        $srclst_query = $this->db->query("SELECT lead_name FROM lead_source WHERE lead_source.comp_id = $cpny_id");
        $srclst = $srclst_query->result_array();
      
    	$enquiry_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 1)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

        $EnquirySrc = $enquiry_src_qry->result_array();

        $lead_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 2)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

        $leadSrc = $lead_src_qry->result_array();

        $Client_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 3)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");

        $ClientSrc = $Client_src_qry->result_array();

        $dataAry = array('EnquirySrc'=>$EnquirySrc,'leadSrc'=>$leadSrc,'ClientSrc'=>$ClientSrc,'srclst'=>$srclst);

        return $dataAry;
    }

    public function enquiryLeadClientCount($userid,$companyid)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        // $where.=" AND enquiry.comp_id=$cpny_id";
        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }

        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }          


        $enquiry = $lead = $client = $enq_ct = $lead_ct = $client_ct = $enq_ut = $lead_ut = $client_ut = $enq_drp = $lead_drp = $client_drp = $enq_active = $lead_active = $client_active = $enq_assign = $lead_assign = $client_assign = 0;

        $query = $this->db->query("SELECT count(enquiry.enquiry_id)counter,enquiry.status FROM enquiry WHERE $where GROUP BY enquiry.status");
        $result = $query->result();

        foreach($result as $r)
        {
            if($r->status == 1)
            {
                $enquiry = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $lead = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $client = (!empty($r->counter)) ? $r->counter : 0;
            }
        }

        $query2 = $this->db->query("SELECT count(enquiry_id)counter,enquiry.status FROM `enquiry` WHERE $where AND DATE(created_date) = CURRENT_DATE GROUP BY enquiry.status");

        $result2 = $query2->result();

        foreach($result2 as $r)
        {
            if($r->status == 1)
            {
                $enq_ct = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $lead_ct = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $client_ct = (!empty($r->counter)) ? $r->counter : 0;
            }
        }

        $query3 = $this->db->query("SELECT count(enquiry_id)counter,enquiry.status FROM `enquiry` WHERE $where AND DATE(update_date) = CURRENT_DATE GROUP BY enquiry.status");

        $result3 = $query2->result();

        foreach($result3 as $r)
        {
            if($r->status == 1)
            {
                $enq_ut = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $lead_ut = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $client_ut = (!empty($r->counter)) ? $r->counter : 0;
            }
        }

        $query4 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND drop_status > 0 GROUP BY enquiry.status");
        $result4 = $query4->result();
        foreach($result4 as $r)
        {
            if($r->status == 1)
            {
                $enq_drp = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $lead_drp = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $client_drp = (!empty($r->counter)) ? $r->counter : 0;
            }
        }

        $query5 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND drop_status = 1 GROUP BY enquiry.status");

        $result5 = $query5->result();
        foreach($result5 as $r)
        {
            if($r->status == 1)
            {
                $enq_active = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $lead_active = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $client_active = (!empty($r->counter)) ? $r->counter : 0;
            }
        }

        $query6 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND aasign_to IS NULL GROUP BY enquiry.status");

        $result6 = $query6->result();
        foreach($result6 as $r)
        {
            if($r->status == 1)
            {
                $enq_assign = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $lead_assign = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $client_assign = (!empty($r->counter)) ? $r->counter : 0;
            }
        }

        $dataAry = array('enquiry'=>$enquiry,'lead'=>$lead,'client'=>$client,'enq_ct'=>$enq_ct,'lead_ct'=>$lead_ct,'client_ct'=>$client_ct,'enq_ut'=>$enq_ut,'lead_ut'=>$lead_ut,'enq_drp'=>$enq_drp,'lead_drp'=>$lead_drp,'client_drp'=>$client_drp,'enq_active'=>$enq_active,'lead_active'=>$lead_active,'client_active'=>$client_active,'enq_assign'=>$enq_assign,'lead_assign'=>$lead_assign,'client_assign'=>$client_assign);

        return $dataAry;
    }
    public function Dy_enquiryLeadClientCount($userid,$companyid,$status)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        // $where.=" AND enquiry.comp_id=$cpny_id";
        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }          


        $enquiry = $lead = $client = $enq_ct = $lead_ct = $client_ct = $enq_ut = $lead_ut = $client_ut = $enq_drp = $lead_drp = $client_drp = $enq_active = $lead_active = $client_active = $enq_assign = $lead_assign = $client_assign = 0;

        $query = $this->db->query("SELECT count(enquiry.enquiry_id)counter,enquiry.status FROM enquiry WHERE $where GROUP BY enquiry.status");
        $result = $query->result();
        
        foreach($result as $r)
        {
            if($r->status == $status)
            {
                $enquiry = (!empty($r->counter)) ? $r->counter : 0;
            }
          
        }

        $query2 = $this->db->query("SELECT count(enquiry_id)counter,enquiry.status FROM `enquiry` WHERE $where AND DATE(created_date) = CURRENT_DATE GROUP BY enquiry.status");

        $result2 = $query2->result();

        foreach($result2 as $r)
        {
            if($r->status == $status)
            {
                $enq_ct = (!empty($r->counter)) ? $r->counter : 0;
            }
          
        }

        $query3 = $this->db->query("SELECT count(enquiry_id)counter,enquiry.status FROM `enquiry` WHERE $where AND DATE(update_date) = CURRENT_DATE GROUP BY enquiry.status");

        $result3 = $query2->result();

        foreach($result3 as $r)
        {
            if($r->status == $status)
            {
                $enq_ut = (!empty($r->counter)) ? $r->counter : 0;
            }
           
        }

        $query4 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND drop_status > 0 GROUP BY enquiry.status");
        $result4 = $query4->result();
        foreach($result4 as $r)
        {
            if($r->status == $status)
            {
                $enq_drp = (!empty($r->counter)) ? $r->counter : 0;
            }
           
        }

        $query5 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND drop_status = 1 GROUP BY enquiry.status");

        $result5 = $query5->result();
        foreach($result5 as $r)
        {
            if($r->status == $status)
            {
                $enq_active = (!empty($r->counter)) ? $r->counter : 0;
            }
           
        }

        $query6 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.status FROM `enquiry` WHERE $where AND aasign_to IS NULL GROUP BY enquiry.status");

        $result6 = $query6->result();
        foreach($result6 as $r)
        {
            if($r->status == $status)
            {
                $enq_assign = (!empty($r->counter)) ? $r->counter : 0;
            }
          
        }

        $dataAry = array('enquiry'=>$enquiry,'enq_ct'=>$enq_ct,'enq_ut'=>$enq_ut,'enq_drp'=>$enq_drp,'enq_active'=>$enq_active,'enq_assign'=>$enq_assign);

        return $dataAry;
    }
    public function despositionDataChart($userid,$companyid)
    {	
        // not in use
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';        
        // $where.=" AND enquiry.comp_id=$cpny_id";


        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            $filter=json_decode($_POST['datas']);
            if(!empty($filter->from_date) AND !empty($filter->to_date)){
                $from_date=$filter->from_date;
                $to_date=$filter->to_date;
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($filter->users)){
                $users=$filter->users;
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($filter->state_id)){
                $state_id=$filter->state_id;
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($filter->city_id)){
                $city_id=$filter->city_id;
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        
        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }          
        $enqAyr = array(); 
        $array=[1,2,3];
        $desplst_query = $this->db->query("SELECT lead_stage_name FROM lead_stage WHERE  stage_for IN (".implode(',', $array).") AND lead_stage.comp_id = $cpny_id");
        $desplst = $desplst_query->result_array();
      
    	$despenqqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_stage =  lead_stage.stg_id AND enquiry.status = 1)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

        $despenq = $despenqqry->result_array();

        $despleadqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_stage =  lead_stage.stg_id AND enquiry.status = 2)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

        $desplead = $despleadqry->result_array();

        $despcliqry = $this->db->query("SELECT lead_stage_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_stage =  lead_stage.stg_id AND enquiry.status = 3)counternow FROM lead_stage WHERE lead_stage.comp_id = $cpny_id");

        $despcli = $despcliqry->result_array();

        $dataAry = array('despenq'=>$despenq,'desplead'=>$desplead,'despcli'=>$despcli,'desplst'=>$desplst);

        return $dataAry;
    }
    public function dy2despositionDataChart($userid,$companyid,$status)
    {	
        $data=[];

        // not in use
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';        
        // $where.=" AND enquiry.comp_id=$cpny_id";

        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }

        $enqAyr = array(); 
       
      
    	$despenqqry = $this->db->query("SELECT (SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.lead_stage =  lead_stage.stg_id AND enquiry.status = $status)counternow FROM lead_stage WHERE  lead_stage.comp_id = $cpny_id");

        $despenq = $despenqqry->result_array();

        // $dataAry = array($despenq);
        foreach ($despenq as $key => $value) {
              $data[]=$value['counternow'];
        }
        return $data;
    }
    public function DydropDataChart($userid,$companyid,$status)
    {	
        $data=[];

    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where="enquiry.comp_id=$cpny_id";
        if(!empty($_POST)){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            // 
        if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
            $from_date=$_POST['from_date'];
            $to_date=$_POST['to_date'];
            $where.=" AND enquiry.created_date >= $from_date";
            $where.=" AND enquiry.created_date <=$to_date";
            
        }
        if(!empty($_POST['users'])){
            $users=$_POST['users'];
             $where.=" AND enquiry.created_by=$users";
             $where.=" OR enquiry.aasign_to=$users";
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        if(!empty($_POST['state_id'])){
            $state_id=$_POST['state_id'];
                 $where.=" AND enquiry.state_id=$state_id";
                                    }
        if(!empty($_POST['city_id'])){
            $city_id=$_POST['city_id'];
            $where.=" AND enquiry.city_id=$city_id";
           }
    }else{
        $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
    }
        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }

    	$enquiry_drop = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason FROM enquiry  right JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND enquiry.status = $status  AND tp.drop_reason IS NOT NULL GROUP BY tp.drop_reason");
        $enquiry_dropWise = $enquiry_drop->result_array();
        foreach ($enquiry_dropWise as $key => $value) {
          $data[]=$value['counternow'];
    }
    return $data;
    }

    public function DYprocessWiseChart($userid,$companyid,$process,$status)
    {	
        $enquiry_processWise=[];

        if(user_access(553)){ 
        
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	$where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where.=" AND enquiry.comp_id=$cpny_id";

    	$enquiry_process = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.product_name FROM enquiry  LEFT JOIN tbl_product as tp ON tp.sb_id = enquiry.product_id WHERE $where AND enquiry.status = $status AND tp.sb_id In ($process) GROUP BY tp.sb_id");
        $enquiry_processWise = $enquiry_process->result();
            
        }
      return $enquiry_processWise;

    }

    public function dysourceDataChart($userid,$companyid,$status)
    {	
        $data=[];

    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        // $where.=" AND enquiry.comp_id=$cpny_id";
        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $where.=" AND enquiry.created_date >= $to_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }

        $enqAyr = array(); 
        
    	$enquiry_src_qry = $this->db->query("SELECT lead_name,(SELECT COUNT(enquiry_id) FROM enquiry WHERE $where AND enquiry.enquiry_source =  lead_source.lsid AND enquiry.status = 1)counternow FROM lead_source WHERE lead_source.comp_id = $cpny_id");
        $EnquirySrc = $enquiry_src_qry->result_array();
          foreach ($EnquirySrc as $key => $value) {
            $data[]=$value['counternow'];
      }
      return $data;


    }
   
    public function monthWiseChart($userid,$companyid)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;

    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            $filter=json_decode($_POST['datas']);
            if(!empty($filter->from_date) AND !empty($filter->to_date)){
                $from_date=$filter->from_date;
                $to_date=$filter->to_date;
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($filter->users)){
                $users=$filter->users;
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($filter->state_id)){
                $state_id=$filter->state_id;
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($filter->city_id)){
                $city_id=$filter->city_id;
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        
        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }
        $ejan = $ljan = $cjan = $efeb = $lfeb = $cfeb = $emar = $lmar = $cmar = $eapr = $lapr = $capr = $emay = $lmay = $cmay = $ejun = $ljun = $cjun = $ejuly = $ljuly = $cjuly = $eaug = $laug = $caug = $esep = $lsep = $csep = $eoct = $loct = $coct = $enov = $lnov = $cnov = $edec = $ldec = $cdec = 0;
   if(user_access(60)) {

        $query8 = $this->db->query("SELECT count(enquiry_id) counter,month(DATE(created_date)) month  FROM `enquiry` WHERE $where AND status = 1 GROUP BY month(DATE(created_date))");

        $result8 = $query8->result();
        //print_r($result8);die;

        foreach($result8 as $r)
        {
            if($r->month == 1 )
            {
                $ejan = $r->counter;
            }
            if($r->month == 2 )
            {
                $efeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $emar = $r->counter;
            }
            if($r->month == 4 )
            {
                $eapr = $r->counter;
            }
            if($r->month == 5 )
            {
                $emay = $r->counter;
            }
            if($r->month == 6 )
            {
                $ejun = $r->counter;
            }
            if($r->month == 7 )
            {
                $ejuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $eaug = $r->counter;
            }
            if($r->month == 9 )
            {
                $esep = $r->counter;
            }
            if($r->month == 10 )
            {
                $eoct = $r->counter;
            }
            if($r->month == 11 )
            {
                $enov = $r->counter;
            }
            if($r->month == 12 )
            {
                $edec = $r->counter;
            }
        }
        //echo "string";die;
    }
   if(user_access(70)) {

        $query9 = $this->db->query("SELECT count(enquiry_id) counter,month(DATE(created_date)) month  FROM `enquiry` WHERE $where AND status = 2 GROUP BY month(DATE(created_date))");

        $result9 = $query9->result();

        foreach($result9 as $r)
        {
            if($r->month == 1 )
            {
                $ljan = $r->counter;
            }
            if($r->month == 2 )
            {
                $lfeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $lmar = $r->counter;
            }
            if($r->month == 4 )
            {
                $lapr = $r->counter;
            }
            if($r->month == 5 )
            {
                $lmay = $r->counter;
            }
            if($r->month == 6 )
            {
                $ljun = $r->counter;
            }
            if($r->month == 7 )
            {
                $ljuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $laug = $r->counter;
            }
            if($r->month == 9 )
            {
                $lsep = $r->counter;
            }
            if($r->month == 10 )
            {
                $loct = $r->counter;
            }
            if($r->month == 11 )
            {
                $lnov = $r->counter;
            }
            if($r->month == 12 )
            {
                $ldec = $r->counter;
            }
        }
    }
   if(user_access(80)) { 

        $query10 = $this->db->query("SELECT count(enquiry_id) counter,month(DATE(created_date)) month  FROM `enquiry` WHERE $where AND status = 3 GROUP BY month(DATE(created_date))");

        $result10 = $query10->result();

        foreach($result10 as $r)
        {
            if($r->month == 1 )
            {
                $cjan = $r->counter;
            }
            if($r->month == 2 )
            {
                $cfeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $cmar = $r->counter;
            }
            if($r->month == 4 )
            {
                $capr = $r->counter;
            }
            if($r->month == 5 )
            {
                $cmay = $r->counter;
            }
            if($r->month == 6 )
            {
                $cjun = $r->counter;
            }
            if($r->month == 7 )
            {
                $cjuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $caug = $r->counter;
            }
            if($r->month == 9 )
            {
                $csep = $r->counter;
            }
            if($r->month == 10 )
            {
                $coct = $r->counter;
            }
            if($r->month == 11 )
            {
                $cnov = $r->counter;
            }
            if($r->month == 12 )
            {
                $cdec = $r->counter;
            }
        }
    }
        $dataAry = array('ejan'=>$ejan,'ljan'=>$ljan,'cjan'=>$cjan,'efeb'=>$efeb,'lfeb'=>$lfeb,'cfeb'=>$cfeb,'emar'=>$emar,'lmar'=>$lmar,'cmar'=>$cmar,'eapr'=>$eapr,'lapr'=>$lapr,'capr'=>$capr,'emay'=>$emay,'lmay'=>$lmay,'cmay'=>$cmay,'ejun'=>$ejun,'ljun'=>$ljun,'cjun'=>$cjun,'ejuly'=>$ejuly,'ljuly'=>$ljuly,'cjuly'=>$cjuly,'eaug'=>$eaug,'laug'=>$laug,'caug'=>$caug,'esep'=>$esep,'lsep'=>$lsep,'csep'=>$csep,'eoct'=>$eoct,'loct'=>$loct,'coct'=>$coct,'enov'=>$enov,'lnov'=>$lnov,'cnov'=>$cnov,'edec'=>$edec,'ldec'=>$ldec,'cdec'=>$cdec);
        //print_r($dataAry);die;
        return $dataAry;
    }
    public function DYdropmonthWiseChart($userid,$companyid,$status)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	$where="enquiry.comp_id=$cpny_id";
        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        $ejan = $ljan = $cjan = $efeb = $lfeb = $cfeb = $emar = $lmar = $cmar = $eapr = $lapr = $capr = $emay = $lmay = $cmay = $ejun = $ljun = $cjun = $ejuly = $ljuly = $cjuly = $eaug = $laug = $caug = $esep = $lsep = $csep = $eoct = $loct = $coct = $enov = $lnov = $cnov = $edec = $ldec = $cdec = 0;

        $query8 = $this->db->query("SELECT count(enquiry.enquiry_id)counter, month(DATE(tc.created_date)) month  FROM enquiry  JOIN tbl_comment as tc ON tc.lead_id = enquiry.Enquery_id right JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status  WHERE $where AND enquiry.status = $status AND tc.comment_msg ='Enquiry dropped' AND tp.drop_reason IS NOT NULL GROUP BY  month(DATE(tc.created_date))");
        $result8 = $query8->result();
        //print_r($result8);die;

        foreach($result8 as $r)
        {
            if($r->month == 1 )
            {
                
                $ejan = $r->counter;
            }
            if($r->month == 2 )
            {
                $efeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $emar = $r->counter;
            }
            if($r->month == 4 )
            {
                $eapr = $r->counter;
            }
            if($r->month == 5 )
            {
                $emay = $r->counter;
            }
            if($r->month == 6 )
            {
                $ejun = $r->counter;
            }
            if($r->month == 7 )
            {
                $ejuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $eaug = $r->counter;
            }
            if($r->month == 9 )
            {
                $esep = $r->counter;
            }
            if($r->month == 10 )
            {
                $eoct = $r->counter;
            }
            if($r->month == 11 )
            {
                $enov = $r->counter;
            }
            if($r->month == 12 )
            {
                $edec = $r->counter;
            }
        }
        //echo "string";die;

        $dataAry = array('ejan'=>intval($ejan),'efeb'=>intval($efeb),'emar'=>intval($emar),'eapr'=>intval($eapr),'emay'=>intval($emay),'ejun'=>intval($ejun),'ejuly'=>intval($ejuly),'eaug'=>intval($eaug),'esep'=>intval($esep),'eoct'=>intval($eoct),'enov'=>intval($enov),'edec'=>$edec);
        //print_r($dataAry);die;
        return $dataAry;
    }
    public function DYmonthWiseChart($userid,$companyid,$status)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	$where="enquiry.comp_id=$cpny_id";
        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        $ejan = $ljan = $cjan = $efeb = $lfeb = $cfeb = $emar = $lmar = $cmar = $eapr = $lapr = $capr = $emay = $lmay = $cmay = $ejun = $ljun = $cjun = $ejuly = $ljuly = $cjuly = $eaug = $laug = $caug = $esep = $lsep = $csep = $eoct = $loct = $coct = $enov = $lnov = $cnov = $edec = $ldec = $cdec = 0;

        $query8 = $this->db->query("SELECT count(enquiry_id) counter,month(DATE(created_date)) month  FROM `enquiry` WHERE $where AND status = $status GROUP BY month(DATE(created_date))");

        $result8 = $query8->result();
        //print_r($result8);die;

        foreach($result8 as $r)
        {
            if($r->month == 1 )
            {
                
                $ejan = $r->counter;
            }
            if($r->month == 2 )
            {
                $efeb = $r->counter;
            }
            if($r->month == 3 )
            {
                $emar = $r->counter;
            }
            if($r->month == 4 )
            {
                $eapr = $r->counter;
            }
            if($r->month == 5 )
            {
                $emay = $r->counter;
            }
            if($r->month == 6 )
            {
                $ejun = $r->counter;
            }
            if($r->month == 7 )
            {
                $ejuly = $r->counter;
            }
            if($r->month == 8 )
            {
                $eaug = $r->counter;
            }
            if($r->month == 9 )
            {
                $esep = $r->counter;
            }
            if($r->month == 10 )
            {
                $eoct = $r->counter;
            }
            if($r->month == 11 )
            {
                $enov = $r->counter;
            }
            if($r->month == 12 )
            {
                $edec = $r->counter;
            }
        }
        //echo "string";die;

        $dataAry = array('ejan'=>intval($ejan),'efeb'=>intval($efeb),'emar'=>intval($emar),'eapr'=>intval($eapr),'emay'=>intval($emay),'ejun'=>intval($ejun),'ejuly'=>intval($ejuly),'eaug'=>intval($eaug),'esep'=>intval($esep),'eoct'=>intval($eoct),'enov'=>intval($enov),'edec'=>$edec);
        //print_r($dataAry);die;
        return $dataAry;
    }
    public function DROPmonthWiseChart($userid,$companyid)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	$where="enquiry.comp_id=$cpny_id";
        if($_POST){
            // $filter=json_encode(array(
            //     'from_date'=>$_POST['from_date'],
            //     'to_date'=>$_POST['to_date'],
            //     'users'=>$_POST['users'],
            //     'state_id'=>$_POST['state_id'],
            //     'city_id'=>$_POST['city_id'],
            //                   ));
            if(!empty($_POST['from_date']) AND !empty($_POST['to_date'])){
                $from_date=$_POST['from_date'];
                $to_date=$_POST['to_date'];
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($_POST['users'])){
                $users=$_POST['users'];
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($_POST['state_id'])){
                $state_id=$_POST['state_id'];
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($_POST['city_id'])){
                $city_id=$_POST['city_id'];
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
       
      
    $ejan = $ljan = $cjan = $efeb = $lfeb = $cfeb = $emar = $lmar = $cmar = $eapr = $lapr = $capr = $emay = $lmay = $cmay = $ejun = $ljun = $cjun = $ejuly = $ljuly = $cjuly = $eaug = $laug = $caug = $esep = $lsep = $csep = $eoct = $loct = $coct = $enov = $lnov = $cnov = $edec = $ldec = $cdec = 0;
    if(user_access(60)) {
 
        $query8 = $this->db->query("SELECT count(enquiry.enquiry_id)counter, month(DATE(tc.created_date)) month  FROM enquiry  JOIN tbl_comment as tc ON tc.lead_id = enquiry.Enquery_id right JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status  WHERE $where AND enquiry.status = 1 AND tc.comment_msg ='Enquiry dropped' AND tp.drop_reason IS NOT NULL GROUP BY  month(DATE(tc.created_date))");
        $result8 = $query8->result();
        
         //print_r($result8);die;
 
         foreach($result8 as $r)
         {
             if($r->month == 1 )
             {
                 $ejan = $r->counter;
             }
             if($r->month == 2 )
             {
                 $efeb = $r->counter;
             }
             if($r->month == 3 )
             {
                 $emar = $r->counter;
             }
             if($r->month == 4 )
             {
                 $eapr = $r->counter;
             }
             if($r->month == 5 )
             {
                 $emay = $r->counter;
             }
             if($r->month == 6 )
             {
                 $ejun = $r->counter;
             }
             if($r->month == 7 )
             {
                 $ejuly = $r->counter;
             }
             if($r->month == 8 )
             {
                 $eaug = $r->counter;
             }
             if($r->month == 9 )
             {
                 $esep = $r->counter;
             }
             if($r->month == 10 )
             {
                 $eoct = $r->counter;
             }
             if($r->month == 11 )
             {
                 $enov = $r->counter;
             }
             if($r->month == 12 )
             {
                 $edec = $r->counter;
             }
         }
         //echo "string";die;
     }
    if(user_access(70)) {
 
        $query9 = $this->db->query("SELECT count(enquiry.enquiry_id)counter, month(DATE(tc.created_date)) month  FROM enquiry  JOIN tbl_comment as tc ON tc.lead_id = enquiry.Enquery_id right JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status  WHERE $where AND enquiry.status = 2 AND tc.comment_msg ='Enquiry dropped' AND tp.drop_reason IS NOT NULL GROUP BY  month(DATE(tc.created_date))");
        $result9 = $query9->result();
 
         foreach($result9 as $r)
         {
             if($r->month == 1 )
             {
                 $ljan = $r->counter;
             }
             if($r->month == 2 )
             {
                 $lfeb = $r->counter;
             }
             if($r->month == 3 )
             {
                 $lmar = $r->counter;
             }
             if($r->month == 4 )
             {
                 $lapr = $r->counter;
             }
             if($r->month == 5 )
             {
                 $lmay = $r->counter;
             }
             if($r->month == 6 )
             {
                 $ljun = $r->counter;
             }
             if($r->month == 7 )
             {
                 $ljuly = $r->counter;
             }
             if($r->month == 8 )
             {
                 $laug = $r->counter;
             }
             if($r->month == 9 )
             {
                 $lsep = $r->counter;
             }
             if($r->month == 10 )
             {
                 $loct = $r->counter;
             }
             if($r->month == 11 )
             {
                 $lnov = $r->counter;
             }
             if($r->month == 12 )
             {
                 $ldec = $r->counter;
             }
         }
     }
    if(user_access(80)) { 
 
        $query10 = $this->db->query("SELECT count(enquiry.enquiry_id)counter, month(DATE(tc.created_date)) month  FROM enquiry  JOIN tbl_comment as tc ON tc.lead_id = enquiry.Enquery_id right JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status  WHERE $where AND enquiry.status = 3 AND tc.comment_msg ='Enquiry dropped' AND tp.drop_reason IS NOT NULL GROUP BY  month(DATE(tc.created_date))");
        $result10 = $query10->result();
 
         foreach($result10 as $r)
         {
             if($r->month == 1 )
             {
                 $cjan = $r->counter;
             }
             if($r->month == 2 )
             {
                 $cfeb = $r->counter;
             }
             if($r->month == 3 )
             {
                 $cmar = $r->counter;
             }
             if($r->month == 4 )
             {
                 $capr = $r->counter;
             }
             if($r->month == 5 )
             {
                 $cmay = $r->counter;
             }
             if($r->month == 6 )
             {
                 $cjun = $r->counter;
             }
             if($r->month == 7 )
             {
                 $cjuly = $r->counter;
             }
             if($r->month == 8 )
             {
                 $caug = $r->counter;
             }
             if($r->month == 9 )
             {
                 $csep = $r->counter;
             }
             if($r->month == 10 )
             {
                 $coct = $r->counter;
             }
             if($r->month == 11 )
             {
                 $cnov = $r->counter;
             }
             if($r->month == 12 )
             {
                 $cdec = $r->counter;
             }
         }
     }
         $dataAry = array('ejan'=>$ejan,'ljan'=>$ljan,'cjan'=>$cjan,'efeb'=>$efeb,'lfeb'=>$lfeb,'cfeb'=>$cfeb,'emar'=>$emar,'lmar'=>$lmar,'cmar'=>$cmar,'eapr'=>$eapr,'lapr'=>$lapr,'capr'=>$capr,'emay'=>$emay,'lmay'=>$lmay,'cmay'=>$cmay,'ejun'=>$ejun,'ljun'=>$ljun,'cjun'=>$cjun,'ejuly'=>$ejuly,'ljuly'=>$ljuly,'cjuly'=>$cjuly,'eaug'=>$eaug,'laug'=>$laug,'caug'=>$caug,'esep'=>$esep,'lsep'=>$lsep,'csep'=>$csep,'eoct'=>$eoct,'loct'=>$loct,'coct'=>$coct,'enov'=>$enov,'lnov'=>$lnov,'cnov'=>$cnov,'edec'=>$edec,'ldec'=>$ldec,'cdec'=>$cdec);
         //print_r($dataAry);die;
         return $dataAry;
    }

    


    public function dropDataChart($userid,$companyid)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            $filter=json_decode($_POST['datas']);
            if(!empty($filter->from_date) AND !empty($filter->to_date)){
                $from_date=$filter->from_date;
                $to_date=$filter->to_date;
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($filter->users)){
                $users=$filter->users;
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($filter->state_id)){
                $state_id=$filter->state_id;
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($filter->city_id)){
                $city_id=$filter->city_id;
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        
        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }

        $enqAyr = array(); 
        $droplst_query = $this->db->query("SELECT drop_reason FROM tbl_drop WHERE tbl_drop.comp_id = $cpny_id");
        $droplst = $droplst_query->result_array();
      
    	$enquiry_drop = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason FROM enquiry  right JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND enquiry.status = 1  AND tp.drop_reason IS NOT NULL GROUP BY tp.drop_reason");
        $enquiry_dropWise = $enquiry_drop->result_array();
        
        $lead_drop = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason FROM enquiry  LEFT JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND enquiry.status = 2  AND tp.drop_reason IS NOT NULL GROUP BY tp.drop_reason");
        $lead_dropWise = $lead_drop->result_array();

        $client_drop = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.drop_reason FROM enquiry  LEFT JOIN tbl_drop as tp ON tp.d_id = enquiry.drop_status WHERE $where AND enquiry.status = 3  AND tp.drop_reason IS NOT NULL GROUP BY tp.drop_reason");
        $client_dropWise = $client_drop->result_array();

        $dataAry = array('enquiry_dropWise'=>$enquiry_dropWise,'lead_dropWise'=>$lead_dropWise,'client_dropWise'=>$client_dropWise,'droplst'=>$droplst);

        return $dataAry;
    }

    public function conversionProbabilityChart($userid,$companyid)
    {	
        // not in use
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        // $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            $filter=json_decode($_POST['datas']);
            if(!empty($filter->from_date) AND !empty($filter->to_date)){
                $from_date=$filter->from_date;
                $to_date=$filter->to_date;
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($filter->users)){
                $users=$filter->users;
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($filter->state_id)){
                $state_id=$filter->state_id;
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($filter->city_id)){
                $city_id=$filter->city_id;
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        

        $arr = $this->session->process;           
        if(is_array($arr)){
            $where.=" AND enquiry.product_id IN (".implode(',', $arr).')';
        }
        $hot = $warm = $cold = 0; 
    	$query7 = $this->db->query("SELECT count(enquiry_id) counter,enquiry.lead_score FROM `enquiry` WHERE $where GROUP BY enquiry.lead_score");

        $result7 = $query7->result();
        $res = array();
        foreach($result7 as $r)
        {
            $this->db->select('score_name');
            $this->db->where('comp_id',$cpny_id);
            $this->db->where('sc_id',$r->lead_score);
            $row    =   $this->db->get('lead_score')->row_array();
            
            if(!empty($row['score_name'])){
                $res[] = array(
                    'country' => (!empty($row['score_name'])) ? $row['score_name'] : 'NA',
                    'litres' => (!empty($r->counter)) ? $r->counter : 0,
                );
            }
        }        
        return $res;
    }
   
    public function processWiseChart($userid,$companyid,$process)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	// $where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	// $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        // $where.=" AND enquiry.comp_id=$cpny_id";


        $where="enquiry.comp_id=$cpny_id";
        if($_POST){
            $filter=json_decode($_POST['datas']);
            if(!empty($filter->from_date) AND !empty($filter->to_date)){
                $from_date=$filter->from_date;
                $to_date=$filter->to_date;
                $where.=" AND enquiry.created_date >= $from_date";
                $where.=" AND enquiry.created_date <=$to_date";
                
            }
            if(!empty($filter->users)){
                $users=$filter->users;
                 $where.=" AND enquiry.created_by=$users";
                 $where.=" OR enquiry.aasign_to=$users";
            }else{
                $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
                $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            }
            if(!empty($filter->state_id)){
                $state_id=$filter->state_id;
                     $where.=" AND enquiry.state_id=$state_id";
                                        }
            if(!empty($filter->city_id)){
                $city_id=$filter->city_id;
                $where.=" AND enquiry.city_id=$city_id";
               }
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        }
        $arr1 =$arr2 = $arr3 = array();

       if(user_access(60)) {
        
    	$enquiry_process = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.product_name,tp.sb_id FROM tbl_product as tp  LEFT JOIN enquiry ON tp.sb_id = enquiry.product_id WHERE $where AND enquiry.status = 1 AND tp.sb_id In ($process) GROUP BY tp.sb_id");
        $enquiry_processWise = $enquiry_process->result_array();
        
        if(!empty($enquiry_processWise)){
            foreach($enquiry_processWise as $k=>$v){
                $pid    =   $v['sb_id'];
                $arr1[$pid] = array('name'=>$v['product_name'],'data'=>array((int)$v['counter']));
            }
        }
    }
       if(user_access(70)){
        $lead_process = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.product_name,tp.sb_id FROM tbl_product as tp  LEFT JOIN enquiry ON tp.sb_id = enquiry.product_id WHERE $where AND enquiry.status = 2 AND tp.sb_id In ($process) GROUP BY tp.sb_id");

        $lead_processWise = $lead_process->result_array();
        if(!empty($lead_processWise)){
            foreach($lead_processWise as $k=>$v){
                $pid    =   $v['sb_id'];
                if(!empty($arr1[$pid])){
                    $arr1[$pid]['data'][1] = (int)$v['counter'];
                }else{
                    $arr1[$pid] = array('name'=>$v['product_name'],'data'=>array(0,(int)$v['counter']));                    
                }
            }
        }
    }
 if(user_access(80)){
        $client_process = $this->db->query("SELECT count(enquiry.enquiry_id)counter,tp.product_name,tp.sb_id FROM tbl_product as tp LEFT JOIN enquiry ON tp.sb_id = enquiry.product_id WHERE $where AND enquiry.status = 3 AND tp.sb_id In ($process) GROUP BY tp.sb_id");


        
        $client_processWise = $client_process->result_array();
        if(!empty($client_processWise)){
            foreach($client_processWise as $k=>$v){
                $pid    =   $v['sb_id'];
                if(!empty($arr1[$pid])){
                    $arr1[$pid]['data'][2] = (int)$v['counter'];
                }else{
                    $arr1[$pid] = array('name'=>$v['product_name'],'data'=>array(0,0,(int)$v['counter']));                    
                }
            }
        }
    }
        return array_values($arr1);
    }


    public function enquiryLeadClientChart($userid,$companyid)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
    	$where = "( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	$where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        $where.=" AND enquiry.comp_id=$cpny_id";

    	$query = $this->db->query("SELECT count(enquiry.enquiry_id)counter,enquiry.status FROM enquiry WHERE $where GROUP BY enquiry.status");
        $result = $query->result();
        $enquiry = $lead = $client = 0;
        foreach($result as $r)
        { 
            if($r->status == 1)
            {
                $enquiry = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 2)
            {
                $lead = (!empty($r->counter)) ? $r->counter : 0;
            }
            if($r->status == 3)
            {
                $client = (!empty($r->counter)) ? $r->counter : 0;
            }
            

        }
        $dataAry = array('enquiry'=>$enquiry,'lead'=>$lead,'client'=>$client);
        return $dataAry;
    }
    public function DyenquiryLeadClientChart($userid,$companyid,$status)
    {	
    	$all_reporting_ids    =   $this->common_model->get_categories($userid);
        $cpny_id=$companyid;
        $arr = $this->session->process;  
        $where = '';
        if(is_array($arr)){
            $where="enquiry.product_id IN (".implode(',', $arr).')';
           //$querys = $this->db->where($where);

        } 
        $querys = $this->db->where("enquiry.comp_id",$cpny_id);
        if($_POST){
        $filter=json_decode($_POST['datas']);

        if(!empty($filter->from_date) AND !empty($filter->to_date)){
            $from_date=$filter->from_date;
            $to_date=$filter->to_date;
            $querys = $this->db->where('enquiry.created_date >=', $from_date);
            $querys = $this->db->where('enquiry.created_date <=', $to_date);
        }
        
        if(!empty($filter->users)){
            $users=$filter->users;
            $querys = $this->db->where("enquiry.created_by",$users);
            $querys = $this->db->or_where("enquiry.aasign_to",$users);
           
        }else{
            $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
            $where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
            //$querys = $this->db->where($where);
            
        }
        if(!empty($filter->state_id)){
            $state_id=$filter->state_id;
            $querys = $this->db->where("enquiry.state_id",$state_id);


        }
        if(!empty($filter->city_id)){
            $city_id=$filter->city_id;
            $querys = $this->db->where("enquiry.city_id",$city_id);

        }
    }else{
        $where.= " AND ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
    	$where.= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))';
        //$querys = $this->db->where($where);
    }
        $querys = $this->db->select("enquiry.created_date,enquiry.status,enquiry.aasign_to,enquiry.state_id,enquiry.city_id,enquiry.created_by,enquiry.product_id,count(enquiry.status) as c");
        $this->db->where($where);
        $this->db->group_by("enquiry.status");
        $querys = $this->db->get('enquiry');         
        $result = $querys->result();        
        $enquiry =0;
        foreach($result as $r)
        { 
            if($r->status == $status)
            {
                $enquiry = (!empty($r->c)) ? $r->c : 0;
            }
        }
       //echo $this->db->last_query();
        return $enquiry;
    }

    public function make_enquiry_read($enq_code){
    	$this->db->where('tbl_enqstatus.enquiry_code',$enq_code);
    	$this->db->where('tbl_enqstatus.user_id',$this->session->user_id);    	
    	if($this->db->get('tbl_enqstatus')->num_rows()){
    		$this->db->where('user_id',$this->session->user_id);
    		$this->db->where('enquiry_code',$enq_code);    		
    		$this->db->update('tbl_enqstatus',array('status'=>1));
    	}else{
    		$arr = array('enquiry_code'=>$enq_code,'user_id'=>$this->session->user_id,'status'=>1);
    		$this->db->insert('tbl_enqstatus',$arr);
    	}

    }
	
	public function get_state(){

    	$this->db->select('*');
    	$this->db->from('state');
    	$this->db->where('comp_id',$this->session->companey_id);
    	return $this->db->get()->result();
    }
	public function change_enq_status($enqid){

		return $this->db->where('enquiry_code',$enqid)->where('user_id',$this->session->user_id)->update('tbl_enqstatus',array('status'=>1));

	}

	public function get_enquiry_all_data($enquiry_code,$comp_id=29){	//29 company id is for paisa expo	
		$this->db->select("enquiry.email,enquiry.phone as mobileno,enquiry.other_phone,CONCAT_WS(' ',enquiry.name_prefix,enquiry.name,enquiry.lastname) as name,enquiry.gender,enquiry.gender,enquiry.enquiry as remark,enquiry.org_name as company,lead_source.lead_name as lead_source,lead_stage.lead_stage_name,tbl_subsource.subsource_name,tbl_product_country.country_name as product_name,enquiry.product_id,enquiry.status,enquiry.drop_reason,enquiry.created_date,enquiry.update_date as last_updated_date,CONCAT(tbl_admin.s_display_name,' ',tbl_admin.last_name) as created_by_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_to_name,CONCAT(tbl_admin2.s_display_name,' ',tbl_admin2.last_name) as assign_by_name,lead_description.description,enquiry.lead_discription_reamrk,enquiry.pin_code as pin-code,enquiry.partner_id as referred_by,city.city,state.state");

		$this->db->join('lead_stage','lead_stage.stg_id=enquiry.lead_stage','left');
		$this->db->join('lead_source','lead_source.lsid=enquiry.enquiry_source','left');
		$this->db->join('tbl_subsource','tbl_subsource.subsource_id=enquiry.sub_source','left');		
		$this->db->join('tbl_product_country','tbl_product_country.id=enquiry.enquiry_subsource','left');
		$this->db->join('tbl_admin','tbl_admin.pk_i_admin_id=enquiry.created_by','left');		
		$this->db->join('tbl_admin as tbl_admin2','tbl_admin2.pk_i_admin_id=enquiry.aasign_to','left');	
		$this->db->join('tbl_admin as tbl_admin3','tbl_admin3.pk_i_admin_id=enquiry.assign_by','left');		  
        $this->db->join('lead_description','lead_description.id = enquiry.lead_discription','left');   
        $this->db->join('state','state.id = enquiry.state_id','left');   
        $this->db->join('city','city.id = enquiry.city_id','left');   

		$this->db->where('Enquery_id',$enquiry_code);
		$enq_row	=	$this->db->get('enquiry')->row_array();

		$process_id =	$enq_row['product_id'];
		$this->db->select('input_name,extra_enquery.fvalue');
		$this->db->where('tbl_input.company_id',$comp_id);
		$this->db->where("FIND_IN_SET($process_id,tbl_input.process_id)>",0);
		$this->db->where("tbl_input.status",1);
		$this->db->join("(select * from extra_enquery where enq_no = '$enquiry_code') as extra_enquery",'extra_enquery.input=tbl_input.input_id','left');
		$result	=	$this->db->get('tbl_input')->result_array();
		$data = array();
		if (!empty($result)) {
			foreach ($result as $key => $value) {
				$name	=	$value['input_name'];
				$value	=	$value['fvalue'];
				$data[$name] = $value;
			}
		}
		$data = array_merge($enq_row,$data);
		return $data;
	}

	public function get_extra_enquiry_property($enquiry_code,$input_name,$comp_id){ // for non query type form only
		$this->db->select('tbl_input.input_name,extra_enquery.fvalue,extra_enquery.id');
		$this->db->where('tbl_input.input_name',$input_name);
		$this->db->where('tbl_input.company_id',$comp_id);
		$this->db->where('extra_enquery.enq_no',$enquiry_code);
		$this->db->join('extra_enquery','extra_enquery.input=tbl_input.input_id','inner');
		return $this->db->get('tbl_input')->row_array();
	}

	public function set_extra_enquiry_property($enquiry_code,$input_name,$input_value,$comp_id){ // for non query type form only
		$prop	=	$this->get_extra_enquiry_property($enquiry_code,$input_name,$comp_id);
		if (!empty($prop['id'])) {
			$this->db->where('id',$prop['id']);
			$this->db->set('fvalue',$input_value);
			return $this->db->update('extra_enquery');
		}else{
			$this->db->select('enquiry_id');
			$this->db->where('Enquery_id',$enquiry_code);
			$enq_row	=	$this->db->get('enquiry')->row_array();			
			$this->db->select('input_id');
			$this->db->where('tbl_input.input_name',$input_name);
			$this->db->where('tbl_input.company_id',$comp_id);
			$input_row	=	$this->db->get('tbl_input')->row_array();
			$ins_arr = array(
							'parent' => $enq_row['enquiry_id'],
							'input'	 => $input_row['input_id'],							
							'cmp_no' => $comp_id,							
							'enq_no' => $enquiry_code,
							'fvalue' => $input_value,
						);
			$this->db->insert('extra_enquery',$ins_arr);
		}
	}

	public function is_enquiry_exist($where){
		$this->db->where($where);
		return $this->db->get('enquiry')->row_array();
	}

	public function getuseremail($from,$enquiry_code){
		$domain = get_sys_parameter('imap_host','IMAP');
		$port = get_sys_parameter('imap_port','IMAP');
		$mode = get_sys_parameter('imap_mode','IMAP');
		$user = get_sys_parameter('user','IMAP');
		$password = get_sys_parameter('password','IMAP');
		$host 	  =  "{".$domain.":".$port."/imap/".$mode."}INBOX";
		$user	  = $user;
		$password = $password;	
		$inbox  = imap_open($host,$user ,$password)  or die('Cannot connect: ' . imap_last_error());
		$comp_id = $this->session->companey_id;		
		$this->db->select('created_date,drop_reason as msg_id');
		$this->db->where('lead_id',$enquiry_code);
		$this->db->where('comp_id',$comp_id);
		$this->db->where('coment_type',6);
		$this->db->order_by('comm_id','desc');
		$this->db->limit(1);
		$last_mail	=	$this->db->get('tbl_comment')->row_array();		
		$since = '';
		if (!empty($last_mail)) {
			$since = 'SINCE '.date("d-M-Y", strtotime($last_mail['created_date'])).' ';
		}		
		$emails = imap_search($inbox,$since.'FROM '.$from);
		$mailarr = array();

		if($emails) {
			$output = '';
			foreach($emails as $ind => $email_number) {					
				$header   = imap_headerinfo($inbox, $email_number);
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message  = imap_fetchbody($inbox, $email_number, 1);
				if (!empty($last_mail['msg_id']) && $last_mail['msg_id'] < $header->Msgno) {
					
					$insarr[] = array(
								"comp_id" => $this->session->companey_id,
								"lead_id" => $enquiry_code,
								"remark" => $message,
								"comment_msg"   => (!empty($header->subject)) ? $header->subject : "",
								"created_date"   => date("Y-m-d H:i:s", strtotime($header->date)),
								"coment_type"=>6,
								"drop_reason"=>$header->Msgno
								); 
				}else if (empty($last_mail['msg_id'])) {
					$insarr[] = array(
								"comp_id" => $this->session->companey_id,
								"lead_id" => $enquiry_code,
								"remark" => $message,
								"comment_msg"   => (!empty($header->subject)) ? $header->subject : "",
								"created_date"   => date("Y-m-d H:i:s", strtotime($header->date)),
								"coment_type"=>6,
								"drop_reason"=>$header->Msgno
								); 
				}
			}
		}	


	if(!empty($insarr)){					
		$this->db->insert_batch("tbl_comment", $insarr);
	}
	imap_close($inbox);
    }
    
public function insetFollowupTime($enquiry_id,$stageType,$oldTime,$newTime)
{
// get the type of stage
//  $time=$oldTime-$newTime;
// if(!empty($oldTime) AND !empty($newTime) AND $oldTime!=NULL AND $oldTime!='0000-00-00 00:00:00'){
 $datetime1 = strtotime($oldTime);
 $datetime2 = strtotime($newTime);
  $secs = $datetime2 - $datetime1; // == return sec in difference
  $minutes = floor($secs/60);
// old enq time and stage
  $data=['enq_id'=>$enquiry_id,'time'=>$minutes,'type'=>$stageType,'date1'=>$oldTime,'date2'=>$newTime,'comp_id' => $this->session->userdata('companey_id'),];
  $this->db->insert('tbl_followupAvgtime',$data);
// }
}
public function secsToStr($minutes)
{
    $d = floor ($minutes / 1440);
    $h = floor (($minutes - $d * 1440) / 60);
    $m = $minutes - ($d * 1440) - ($h * 60);
     $m = round($m);
    if($d!=0){
        echo  $d.' days '.$h.' hours '.$m.' minutes ';
    }elseif($h!=0){
        echo $h.' hours '.$m.' minutes ';
    }else{
        echo $m.' minutes ';
    }
    
}

public function getComInfo($enquiry_id)
{
	 
      $CommercialInfo=$this->db->select('*')
      ->where('enquiry_id',$enquiry_id)
      ->order_by('id','desc')
      ->get('commercial_info')->result();
      return $CommercialInfo;
}

public function insertComInfo($data)
{
   $insert= $this->db->insert('commercial_info',$data);

   // $forecast = ['enquiry_id'=>$data['enquiry_id'],
   //              'deal_value'=>$data['potential_amount'],
   //              'won_value'=>$data['expected_amount'],
   //              'created_by'=>$data['createdby'],
   //              'comp_id' =>$this->session->companey_id,
   //            ]; 
   //  $this->db->insert('tbl_forecast_data',$forecast);
   return $insert;
}


  public function deal_list_api($company_id,$user_id,$process,$limit=-1,$offset=-1)
  {
       $all_reporting_ids    =   $this->common_model->get_categories($user_id);
       $where = '';
        $where .= " ( enq.created_by IN (".implode(',', $all_reporting_ids).')';
        $where .= " OR enq.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 
        $where.=" AND enq.drop_status=0 and enq.product_id IN (".$process.")";

        $this->db->select('info.*,enq.name,enq.Enquery_id,enq.status as enq_type, book.branch_name as booking_branch_name, deliver.branch_name as delivery_branch_name');
        $this->db->from ('commercial_info info');
        $this->db->join('enquiry enq','enq.enquiry_id=info.enquiry_id','left');
        $this->db->join('branch book','book.branch_id=info.booking_branch','left');
        $this->db->join('branch deliver','deliver.branch_id=info.delivery_branch','left');
        $this->db->where("info.comp_id",$company_id);
        $this->db->where($where);

        //echo $where;exit();

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

              $match_list = array('date_from','date_to','phone');

              $this->db->group_start();
              foreach ($_POST['filters'] as $key => $value)
              {
                if(in_array($key,$match_list) || $this->db->field_exists($key, 'commercial_info'))
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

          //for pagination api

          if($offset!=-1 && $limit!=-1)
          {  
              $this->db->limit($limit,$offset);
          }


        return $this->db->get();

  }


  public function visit_list_api($company_id,$user_id,$process,$limit=-1,$offset=-1)
  {
       $all_reporting_ids    =   $this->common_model->get_categories($user_id);
       $where = '';
        // $where .= " ( enquiry.created_by IN (".implode(',', $all_reporting_ids).')';
        // $where .= " OR enquiry.aasign_to IN (".implode(',', $all_reporting_ids).'))'; 
        $where=" enquiry.drop_status=0 and enquiry.product_id IN (".$process.")";

        $this->db->select('tbl_visit.*,enquiry.name,enquiry.status as enq_type,enquiry.Enquery_id,enquiry.company');
        $this->db->from('tbl_visit');
        $this->db->join('enquiry','enquiry.enquiry_id=tbl_visit.enquiry_id','left');
        // $this->db->join('visit_details','visit_details.visit_id=tbl_visit.id','left');
        $this->db->where("tbl_visit.comp_id",$company_id);
        $this->db->where("tbl_visit.user_id",$user_id);
        $this->db->where($where);


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
              $match_list = array('date_from','date_to','phone');

              $this->db->group_start();
              foreach ($_POST['filters'] as $key => $value)
              {
                if(in_array($key,$match_list) || $this->db->field_exists($key, 'tbl_visit'))
                {
                    if(in_array($key, $match_list))
                    {
                        $fld = 'visit_date';
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

          //for pagination api

          if($offset!=-1 && $limit!=-1)
          {  
              $this->db->limit($limit,$offset);
          }


        return $this->db->get();

  }


}
