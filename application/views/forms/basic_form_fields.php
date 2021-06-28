<?php
  define('FIRST_NAME',1);
  define('LAST_NAME',2);
  define('GENDER',3);
  define('MOBILE',4);
  define('EMAIL',5);
  define('COMPANY',6);
  define('LEAD_SOURCE',7);
  define('PRODUCT_FIELD',8);
  define('STATE_FIELD',9);
  define('CITY_FIELD',10);
  define('ADDRESS_FIELD',11);  
  define('REMARK_FIELD',12);  
  define('PREFERRED_COUNTRY_FIELD',13);  
  define('PIN_CODE',14); 
  define('SUB_SOURCE',51); 
  

                    if(!empty($company_list)){
                      foreach($company_list as $companylist){
                      if($companylist['field_id']==FIRST_NAME){?>
                    
                     <div class="form-group col-sm-4 col-md-4 enq-first-name">
                        <label> <?php echo display("first_name"); ?> <?php if($this->session->companey_id==65){echo'<i class="text-danger">*</i>';}?>  </label>
                        <div class = "input-group" >
                           <span class="input-group-addon" style="padding:0px!important;border:0px!important;width:30%;">
                              <select class="form-control" name="name_prefix">
                                 <?php foreach($name_prefix as $n_prefix){?>
                                 <option value="<?= $n_prefix->prefix ?>"><?= $n_prefix->prefix ?></option>
                                 <?php } ?>
                              </select>
                           </span>
                           <input class="form-control" name="enquirername" type="text" value="<?php  echo set_value('enquirername');?>" placeholder="Enter First Name" style="width:130%;" <?php if($this->session->companey_id==65){echo'required';}?>/>
                        </div>
                     </div>
                     <?php
                   }
                   ?>
                    <?php
                    if($companylist['field_id']==LAST_NAME){
                    ?>
                     <div class="form-group col-sm-4 col-md-4 enq-last-name"> 
                        <label><?php echo display("last_name"); ?> <i class="text-danger"></i></label>
                        <input class="form-control" value="<?php  echo set_value('lastname');?>" name="lastname" type="text" placeholder="Last Name">  
                     </div>

                     <div class="form-group col-sm-4 col-md-4"> 
                        <label><?php echo display("designation"); ?> <i class="text-danger">*</i></label>
                        <select class="form-control" name="designation">
						<option value="">Select Designation</option>
                          <?php
                          $desg=  $this->db->where('comp_id',$this->session->companey_id)->get('tbl_designation')->result();
                            if(!empty($desg))
                            {
                              foreach ($desg as $key => $value)
                              {
                                echo'<option value="'.$value->id.'">'.$value->desi_name.'</option>';
                              }
                            }
                          ?>
                        </select>
						<i class="fa fa-plus" onclick="showDivAttid('1')" style="color:red"></i>
                     </div>
					 
					 <div class="form-group col-sm-4 col-md-4" id="new_designation" style="display:none;"> 
                        <label><?php echo "New Designation"; ?> <i class="text-danger"></i></label>
                        <input class="form-control" name="new_designation" type="text" placeholder="Designation Name">
                        <i class="fa fa-times" onclick="showDivAttid('0')" style="color:red"></i>						
                     </div>

                     <?php
                   }
                   ?>
                   <?php
                    if($companylist['field_id']==GENDER){
                    ?>
                     <div class="form-group col-sm-4 col-md-4 enq-gender"> 
                        <label><?php echo display("gender"); ?><i class="text-danger"></i></label>
                         <select name="gender" class="form-control">
                           <option value="">---Select---</option>
                           <option value="1"><?php echo display("male"); ?></option>
                           <option value="2"><?php echo display("female"); ?></option>
                           <option value="3"><?php echo display("other"); ?></option>
                         </select>                           
                     </div>
                   
                  <?php
                   } 
                   ?>
                   <?php
                    if($companylist['field_id']==MOBILE){
                    ?>
                     <?php
                          $required = 'required';
                          if($this->session->companey_id == 90){
                            $required = ''; 
                          }?>

                     <div class="form-group col-sm-4 col-md-4 enq-mobile"> 
                        <label><?php echo display('mobile') ?> 
                        <?php
                          if($this->session->companey_id != 90){ ?>
                        <i class="text-danger">*</i></label>
                        <?php
                          }?>

                        <input class="form-control" value="<?php if(!empty($_GET['phone'])){echo $_GET['phone']; }else{ echo set_value('mobileno')?set_value('mobileno'):($this->input->get('phone')?$this->input->get('phone'):'');}?>" name="mobileno" onchange="exist_alert(this.value,'mobile')" type="text" maxlength='10' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Enter Mobile Number" <?=$required?> required>
                        <i class="fa fa-plus" onclick="add_more_phone('add_more_phone')" style="float:right;margin-top:-25px;margin-right:10px;color:red"></i>
                     </div>
                     <div id="add_more_phone">
                          <div class="form-group col-sm-4 col-md-4">
                             <label>Other No </label>
                             <input class="form-control"  name="other_no[]" type="text" placeholder="Other Number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                          </div>                          
                       </div>
                  <?php
                   }
                   ?>
                     <?php
                    if($companylist['field_id']==EMAIL){
                    ?>
                     <div class="form-group col-sm-4 col-md-4 enq-email"> 
                        <label><?php echo display('email') ?><i class="text-danger">*</i></label>
                        <input class="form-control" value="<?php  echo set_value('email');?> " name="email" onchange="exist_alert(this.value,'email')" type="email"  placeholder="Enter Email" required>  
                     </div>                     
                     <?php
                   }
                   ?>
                   <?php
                    if($companylist['field_id']==COMPANY){
                    ?>
                     <div class="form-group col-sm-4 col-md-4">
                        <label><?php echo display('company_name') ?> <i class="text-danger">*</i></label>
                        <input class="form-control" value="<?php  echo set_value('company');?> " name="company" id="company_list" type="text"  placeholder="Enter Company" onblur="find_company_id(this.value)" required> 
                     </div>
					 
					    <div class="form-group col-md-4">
                            <label class="control-label" for="sales_branch"><?=display('sales_branch')?><i class="text-danger">*</i></label> 									
                            <select class="form-control" name="sales_branch" id="sales_branch" onchange="clientname()">
                                    <?php  if (!empty($branch_lists)) {
                                        foreach ($branch_lists as $key => $value) { ?>
                                            <option value="<?= $value->branch_id;?>" <?php if($value->branch_id == $this->session->branch_name){ echo "selected";} ?>><?= $value->branch_name;?></option>
                                    <?php
                                        }
                                        } ?>
                            </select>
                        </div>
					  
					           <div class="form-group col-sm-4 col-md-4">
                        <label><?php echo 'Client Name'; ?> <i class="text-danger">*</i></label>
                        <input class="form-control" value="<?php  echo set_value('client_name');?> " name="client_name" type="text" id="client_name"  placeholder="Enter Client Name" readonly>  
                     </div>

                     <div class="form-group col-md-4">
                            <label class="control-label">Contact <i class="text-danger"></i></label>                  
                            <select class="form-control" id="contact_id" name="contact_id" onchange="set_contact(this.value)">
                            </select>
                      </div>
                   
                     <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==LEAD_SOURCE){
                    ?>      
                              
                     <div class="form-group col-sm-4 col-md-4 enq-source">
                        <label><?php echo display('lead_source') ?> <i class="text-danger">*</i></label>
                        <select class="form-control" name="lead_source" id="lead_source" onchange="find_sub()" required>
                           <option value="" style="display:none;">---Select---</option>
                           <?php foreach ($leadsource as $post){ ?>
                           <option value="<?= $post->lsid?>"><?= $post->lead_name?></option>
                           <?php } ?>
                        </select>
                     </div>
                    <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==SUB_SOURCE){
                    ?>      
                              
                     <div class="form-group col-sm-4 col-md-4 enq-subsource">
                        <label><?php echo display('sub_source') ?> <i class="text-danger"></i></label>
                        <select class="form-control" name="subsource" id="subsource">

                        </select>
                     </div>
                    <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==PRODUCT_FIELD){
                    ?>                
                     <div class="form-group col-sm-4 col-md-4 enq-product">
                        <label><?php echo display("product"); ?></label>
                        <select class="form-control" name="sub_source" id="sub_source">
                           <option value="" style="display:none;">---Select---</option>
                           <?php foreach ($product_contry as $subsource){ ?>
                           <option value="<?= $subsource->id?>"><?= $subsource->country_name?></option>
                           <?php } ?>
                        </select>
                     </div>
                      <?php
                   }                   
                    if($companylist['field_id']==ADDRESS_FIELD){
                    ?>                                     
                     <div class="form-group col-sm-4 col-md-4 enq-address">
                        <label><?php echo display('address') ?> <i class="text-danger"></i></label>
                        <textarea class="form-control" name="address" placeholder="Enter Address"><?php  echo set_value('address');?></textarea> 
                     </div>
                   

                    <div class="form-group col-md-4">
                              <label class="control-label" for="client_type"><?php echo  'Client Type';?><i class="text-danger">*</i></label>                   
                          <select class="form-control" name="client_type" id="client_type" required>
                                  <option value="">--Select Client Type--</option>
                          <option value="MSME">MSME</option>
                                  <option value="Pvt. Ltd."> Pvt. Ltd.</option>
                                  <option value="Public Ltd"> Public Ltd</option>
                                  <option value="Partnership" > Partnership</option>
                                  <option value="Multinational"> Multinational</option>
                                  <option value="Proprietorship">  Proprietorship</option>
                          </select>
                      </div>
                    
                    <div class="form-group col-md-4">
                              <label class="control-label" for="business_load"><?php echo 'Type Of Load / Business';?><i class="text-danger">*</i></label>                  
                          <select class="form-control" name="business_load" id="business_load" required>
                                  <option value="">--Select Load/Business--</option>
                          <option value="FTL" >FTL</option>
                                  <option value="LTL/Sundry"> LTL / Sundry</option>
                          </select>
                      </div>
                    
                    <div class="form-group col-md-4">
                              <label class="control-label" for="industries"><?php echo 'Industries';?><i class="text-danger">*</i></label>                  
                          <select class="form-control" name="industries" id="industries">
                                <option value="">--Select industry--</option>
                        <?php
                            $indus=  $this->db->where('comp_id',$this->session->companey_id)->get('tbl_industries')->result();
                            if(!empty($indus))
                            {
                              foreach ($indus as $key => $value)
                              {
                                echo'<option value="'.$value->id.'">'.$value->indus_name.'</option>';
                              }
                            }
                        ?>
                          </select>
                      <i class="fa fa-plus" onclick="showDivAttidind('1')" style="color:red"></i>
                     </div>
					 
					 <div class="form-group col-sm-4 col-md-4" id="new_industry" style="display:none;"> 
                        <label><?php echo "New industry"; ?> <i class="text-danger"></i></label>
                        <input class="form-control" name="new_industry" type="text" placeholder="industry Name">
                        <i class="fa fa-times" onclick="showDivAttidind('0')" style="color:red"></i>						
                     </div>

                     <?php 
                   }                    
                    if($companylist['field_id']==STATE_FIELD){
                    ?>                
                     <div class="form-group col-sm-4 col-md-4 enq-state">
                        <label> <?php echo display("state"); ?> <i class="text-danger">*</i></label>
                        <select name="state_id" class="" id="fstate" required>
                           <option value="" style="display:none;">---Select---</option>
                           <?php foreach($state_list as $state){?>
                           <option value="<?php echo $state->id ?>"><?php echo $state->state; ?></option>
                           <?php } ?>
                        </select>
                     </div>                   
                       <?php
                   }
                   ?>  
                    <?php
                    if($companylist['field_id']==CITY_FIELD){
                    ?>             
                                             
                      <div class="form-group col-sm-4 col-md-4 enq-city">
                        <label><?php echo display("city"); ?> <i class="text-danger">*</i></label>
                        <select name="city_id" class="" id="fcity" required>
                           <option value="" style="display:none;">---Select---</option>
                            <?php foreach ($city_list as $city){ ?>
                           <option value="<?= $city->id?>"><?= $city->city?></option>
                        <?php } ?>
                        </select>
                     </div>
                       <?php
                   }
                  if($companylist['field_id']==PIN_CODE){
                    ?>
                     <div class="form-group col-sm-4 col-md-4 enq-pincode">
                        <label><?php echo display('pin_code') ?> <i class="text-danger"></i></label>
                        <input class="form-control" value="<?php  echo set_value('pin_code');?> " name="pin_code" type="text"  placeholder="Pin Code"> 
                     </div>
                   
                     <?php
                   }
                   
                   if($companylist['field_id']==REMARK_FIELD){
                    ?>                                     
                     <div class="form-group col-sm-4 col-md-4 enq-remark"> 
                        <label><?=display('remark')?></label>
                        <textarea class="form-control" name="enquiry"></textarea>
                     </div>
                     <?php 
                   }
                  }}
                   ?> 



<?php
  if ($this->session->companey_id==51) {
  ?>

<script type="text/javascript">

    function hide_all_dependent_field(){
      $(".service_related_issue_type").hide();                       
      $(".service_related_issue_sub_type").hide();                       
      $(".detail_of_issue").hide();                       
      $(".error_coming").hide();                       
      $(".dnd_sender_id").hide();                       
      $(".issue_date").hide();                       
      $(".promotional_sms_call_date_for_dnd").hide(); 

      $(".balace_deduction_issue_type").hide();            
      $(".balance_deduction_issue_sub_type").hide();            
      $(".amount_deducted").hide();            
      $(".date_of_deduction").hide();            
      $(".waiver_required").hide();            
      $(".blacklist_consent").hide(); 

      $(".recharge_issue_type").hide();
      $(".recharge_issue_sub_type").hide();
      $(".recharge_denomination").hide();
      $(".mode_of_recharge").hide();
      $(".date_of_recharge").hide(); 


      $(".network_issue_type").hide();
      $(".network_issue_sub_type").hide();
      $(".technology").hide();     

      $(".alt_number").hide();            
      $(".sim_service_issue_type").hide();            
      $(".sim_service_issue_sub_type").hide();            
      $(".date_of_simex").hide();            
      $(".vms_name").hide();     

      
      $(".self_help_issue_type").hide();            
      $(".self_help_issue_sub_type").hide();            
      $(".date_of_problem").hide();

      $(".other-issue-type").hide();
      $(".voc").hide();
    }

    function show_dependent_field(service){
      
      hide_all_dependent_field();

      if (service==103) {
        $(".network_issue_type").show();
        $(".network_issue_sub_type").show();
        $(".technology").show();


      }else if (service==104) {
        $(".recharge_issue_type").show();
        $(".recharge_issue_sub_type").show();
        $(".recharge_denomination").show();
        $(".mode_of_recharge").show();
        $(".date_of_recharge").show(); 

       
      }else if (service==105) {
        $(".balace_deduction_issue_type").show();            
        $(".balance_deduction_issue_sub_type").show();            
        $(".amount_deducted").show();            
        $(".date_of_deduction").show();            
        $(".waiver_required").show();            
        $(".blacklist_consent").show(); 
        
      }else if (service==106) {
        $(".alt_number").show();            
        $(".sim_service_issue_type").show();            
        $(".sim_service_issue_sub_type").show();            
        $(".date_of_simex").show();            
        $(".vms_name").show();   


      }else if (service==107) {
        $(".self_help_issue_type").show();            
        $(".self_help_issue_sub_type").show();            
        $(".date_of_problem").show(); 

      }else if (service==108) {
        $(".service_related_issue_type").show();                       
        $(".service_related_issue_sub_type").show();                       
        $(".detail_of_issue").show();                       
        $(".error_coming").show();                       
        $(".dnd_sender_id").show();                       
        $(".issue_date").show();                       
        $(".promotional_sms_call_date_for_dnd").show(); 
      }
      else if (service==110) {
        $(".other-issue-type").show();
        $(".voc").show();
      }

    }
      
  $("#sub_source").on('change',function(){
    var service  = $("#sub_source").val();
    show_dependent_field(service);
  });

</script>
<?php
}else if($this->session->companey_id == 29){ ?>
  <script type="text/javascript">
      function hide_all_dependent_field(){
        $(".desired-loan-amount").hide();
        $(".net-monthly-income").hide();
        $(".bank-name").hide();
        $(".personal-details").hide();
        

        $(".gross-annual-turnover").hide();
        $(".net-profit-after-tax").hide();
        
        $(".company-name").hide();
        $(".company-type").hide();
        $(".occupation-type").hide();
        $(".credit-card-name").hide();
        

        $(".profession").hide();
        $(".years-in-occupation").hide();
        $(".years-in-occupation").hide();
        $(".annual-income").hide();

      }

      function show_dependent_field(service){        
        hide_all_dependent_field();
        if (service == 83) {
          $(".desired-loan-amount").show();
          $(".net-monthly-income").show();
          $(".bank-name").show();
          $(".personal-details").show();
        
        }else if (service == 84) {
          $(".desired-loan-amount").show();          
          $(".gross-annual-turnover").show();
          $(".net-profit-after-tax").show();
          $(".company-name").show();
          $(".company-type").show();
          $(".bank-name").show();

        }else if (service == 111) {
          $(".occupation-type").show();
          $(".net-monthly-income").show();          
          $(".bank-name").show();
          $(".credit-card-name").show();

        }else if (service == 112) {
          $(".desired-loan-amount").show();          
          $(".profession").show();
          $(".years-in-occupation").show();
          $(".bank-name").show();   
          $(".annual-income").show();
        }        
      }
        
    $("#sub_source").on('change',function(){
      var service  = $("#sub_source").val();
      show_dependent_field(service);
    });  
  </script>
<?php
}
?>
<script>
function find_company_id(key)
{
    $.ajax({
            url:"<?=base_url('client/company_by_name')?>",
            type:'get',
            data:{key:key},
            success:function(q){
              q = q.trim();
              if(q)
              {
                load_contacts(q);
              }

            }
  });
}
function load_contacts(v)
{ 

  $.ajax({
            url:"<?=base_url('client/contact_by')?>",
            type:'get',
            data:{key:v,by:'company'},
            success:function(q){
              $("#contact_id").html('<option value="">Select Contact</option>'+q);
            }
  });
  $("#sales_branch").trigger("change");
}

function set_contact(v)
{
    $.ajax({
            url:"<?=base_url('client/get_contact_by_id')?>",
            type:'get',
            data:{id:v},
            success:function(q){
                q = q.trim();
                q = JSON.parse(q);
                console.log(q);
                if(q.status==1)
                {   q=q.data;
                  
                    $("input[name=enquirername]").val(q.c_name);
                    $("select[name=designation]").val(q.designation).trigger('change');
                    $("input[name=mobileno]").val(q.contact_number);
                    $("input[name=email]").val(q.emailid);
                }
            }
  });
}

$("#sales_branch").trigger("change");
function clientname() {
      var company = $('#company_list').val();
	  var branch_id = $('#sales_branch').val();
//alert(company);

        $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>lead/get_compname_by_id',
        data: {sales_branch:branch_id},

        success:function(data){
       // alert(data);
		c_name = company+data;
		  $("#client_name").val(c_name); 
          //$("#client_name").value = data;
        }    
    });
	  }
</script>
<script>
    function showDivAttid(x){

        if(x==1) {

            document.getElementById("new_designation").style.display = 'block';
        }
        else
        {
            document.getElementById("new_designation").style.display = 'none';
        }
    }
	
	function showDivAttidind(x){

        if(x==1) {

            document.getElementById("new_industry").style.display = 'block';
        }
        else
        {
            document.getElementById("new_industry").style.display = 'none';
        }
    }

</script>
<script>
  
  function find_sub(){

    // alert('dadad');

    var src_id = $('#lead_source').val();

    // alert(src_id);


        $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>lead/get_subsource_by_source',
        data: {src_id:src_id},

        success:function(data){
        
          $("#subsource").html(data);
        }    
    });
  }
  
function exist_alert(type,parameter){         
     $.ajax({
          url: "<?php echo base_url().'enquiry/get_exist_alert'?>",
          type: 'POST',
		  data: {type:type,parameter:parameter},
          
          success: function(content) {
if(content!=0){			  
Swal.fire(
  'Already exist details....',
  content
)
}
          }
      });
    
}
</script>