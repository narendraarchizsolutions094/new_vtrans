<?php
$type="text";
if (user_access(450)) { ?>
  <style type="text/css">
    .mask-number{
      -webkit-text-security: square;
    }
  </style>  
<?php
$type="password";
}else{
$type="text";
}
?>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

<?php
   if ($tid == 1) {      
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
  define('SUB_SOURCE',15);
  ?>
<hr>
<?php $viewpro=$this->uri->segment(2); ?>

<?php echo form_open_multipart('client/updateclient/'.$details->enquiry_id,'class="form-inner tabbed_form" autocomplete="off"') ?>  
<input type="hidden" name="form" value="client">  
<input name="en_comments" type="hidden" value="<?=$details->Enquery_id?>" >    
<div class="row">
   <?php                    
   $process_id = $details->product_id;    
      if(is_active_field(FIRST_NAME,$process_id)){
      ?>
   <div class="form-group col-sm-6 col-md-6 enq-first-name">
      <label><?=display('first_name')?> <i class="text-danger">*</i> </label>
      <div class = "input-group">
         <span class = "input-group-addon" style="padding:0px !important;border:0px !important;width:44%;">
            <select class="form-control" name="name_prefix">
               <?php foreach($name_prefix as $n_prefix){?>
               <option value="<?= $n_prefix->prefix ?>" <?php if($n_prefix->prefix==$details->name_prefix){ echo 'selected';} ?>><?= $n_prefix->prefix ?></option>
               <?php } ?>
            </select>
         </span>
         <input class="form-control" name="enquirername" type="text" value="<?php echo $details->name ?>" placeholder="Enter First Name" style="width:100%;" />
      </div>
   </div>
   <?php }?>
   <?php
      if(is_active_field(LAST_NAME,$process_id)){
      ?>
   <div class="form-group col-sm-6 col-md-6 enq-last-name"> 
      <label><?=display('last_name')?> <!-- <i class="text-danger">*</i> --></label>
      <input class="form-control" value="<?php echo $details->lastname ?>" name="lastname" type="text" placeholder="Last Name" >  
   </div>
   <?php }?>
   <?php  if(is_active_field(MOBILE,$process_id)){  ?>
   <div class="form-group col-sm-6 col-md-6 enq-mobile"> 
      <label><?php echo display('mobile') ?></label>
      <?php    if ($viewpro!='viewpro' && $this->session->companey_id == 76) {   ?>
      <input class="form-control mask-number" name="mobileno" type="<?= $type ?>" maxlength='10' value="<?php echo $details->phone ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" >
      <i class="fa fa-plus" onclick="add_more_phone('add_more_phone')" style="float:right;margin-top:-25px;margin-right:10px;color:red"></i>
      <?php }else{ ?>
         <input class="form-control mask-number" hidden name="mobileno" type="<?= $type ?>" maxlength='10' value="<?php echo $details->phone ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" >
      <input class="form-control mask-number" disabled  type="<?= $type ?>" maxlength='10' value="<?php echo $details->phone ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" >
      <i class="fa fa-plus" onclick="add_more_phone('add_more_phone')" style="float:right;margin-top:-25px;margin-right:10px;color:red"></i>
         <?php } ?>
   </div>
   <?php }?>
   <?php
      if(is_active_field(MOBILE,$process_id)){
      ?>
   <div id="add_more_phone">
      <?php
         if (!empty($details->other_phone)) {
           $other_phones = explode(',', $details->other_phone);
           foreach ($other_phones as $k=>$p) { ?>
      <div class="form-group col-sm-6 col-md-6">
         <label>Other No </label>
         <input class="form-control mask-number"  name="other_no[]" type="text" placeholder="Other Number" value="<?=$p?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
      </div>
      <?php
         }
         }
         ?>
   </div>
   <?php }?>                     
   <?php
      if(is_active_field(EMAIL,$process_id)){
      ?>
   <div class="form-group col-sm-6 col-md-6 enq-email"> 
      <label><?php echo display('email') ?></label>
      <?php    if ($viewpro!='viewpro' && $this->session->companey_id == 76) {   ?>

      <input class="form-control" name="email" type="email" value="<?php echo $details->email ?>">  
      <?php }else{ ?>
         <input class="form-control" name="email" hidden type="email" value="<?php echo $details->email ?>">  
         <input class="form-control" disabled type="email" value="<?php echo $details->email ?>">  

     <?php }  ?>
   </div>
   <?php }?>
   <?php
      if(is_active_field(EMAIL,$process_id)){
      ?>
   <div class="form-group col-sm-6 col-md-6 enq-process">
      <label>Process <i class="text-danger"></i></label>
      <?php    if ($viewpro!='viewpro' && $this->session->companey_id == 76) {   ?>

      <select name="product_id" class="form-control">
         <option value="" style="display:none;">Select</option>
         <?php foreach($products as $product){?>
         <option value="<?=$product->sb_id ?>" <?php if($product->sb_id==$details->product_id){ echo 'selected';}?>><?=$product->product_name ?></option>
         <?php } ?>
      </select>
      <?php }else{ ?>
         <?php foreach($products as $product){?>
            <?php if($product->sb_id==$details->product_id){?>
         <input class="form-control" disabled type="text" value="<?=$product->product_name ?>">  
         <input class="form-control" name="product_id" hidden type="text" value="<?=$product->sb_id ?>"> 

     <?php } } }?>
   </div>
   <?php
      }
     if(is_active_field(GENDER,$process_id)){
     ?>
      <div class="form-group col-sm-6 col-md-6 enq-gender"> 
         <label><?php echo display("gender"); ?><i class="text-danger"></i></label>
          <select name="gender" class="form-control">
            <option value="">---Select---</option>
            <option value="1" <?php if(1==$details->gender){ echo 'selected';}?> ><?php echo display("male"); ?></option>
            <option value="2" <?php if(2==$details->gender){ echo 'selected';}?> ><?php echo display("female"); ?></option>
            <option value="3" <?php if(3==$details->gender){ echo 'selected';}?> ><?php echo display("other"); ?></option>
          </select>                           
      </div>
    
   <?php
    }
                  
        if(is_active_field(PRODUCT_FIELD,$process_id)){
        ?>
   <div class="form-group col-sm-6 col-md-6 enq-product">
      <label>Product</label>
      <select class="form-control" name="sub_source" id="sub_source">
         <option value="" style="display:none;">Select Product</option>
         <?php foreach ($product_contry as $subsource){ ?>
         <option value="<?= $subsource->id?>" <?php if($subsource->id==$details->enquiry_subsource){ echo 'selected';}?>><?= $subsource->country_name?></option>
         <?php } ?>
      </select>
   </div>
   <?php }?>
   <?php
      if(is_active_field(LEAD_SOURCE,$process_id)){
      ?> 
   <div class="form-group   col-sm-4 col-md-6 enq-source">
      <label><?php echo display('lead_source') ?></label>
      <select class="form-control" name="lead_source" id="lead_source" onchange="find_sub1()">
         <option value=""><?php echo display('lead_source') ?></option>
         <?php 
            foreach ($leadsource as $post){?>
         <option value="<?= $post->lsid?>" <?php if($details->enquiry_source==$post->lsid){echo 'selected';}?>><?= $post->lead_name?></option>
         <?php } ?>
      </select>
   </div>
     <?php }
      if(is_active_field(SUB_SOURCE,$process_id)){
      ?> 
   <div class="form-group   col-sm-4 col-md-6 enq-subsource">
      <label><?php echo display('sub_source') ?></label>
      <select class="form-control" name="subsource" id="subsource">
      </select>
   </div>
   <?php }   
    if(is_active_field(ADDRESS_FIELD,$process_id)){
    ?>  
   <div class="form-group col-sm-6 col-md-6 enq-address">
      <label><?php echo display('address') ?> <i class="text-danger">*</i></label>
      <textarea class="form-control" name="address"><?php echo $details->address; ?></textarea>
   </div>
   <?php }   
      if(is_active_field(STATE_FIELD,$process_id)){
      ?>  
   <div class="form-group col-sm-6 col-md-6 enq-state">
      <label>State <i class="text-danger"></i></label>                        
      <select name="state_id" class="form-control" id="fstate">
         <option value="" >Select</option>
         <?php foreach($state_list as $state){
            //echo  $state->id.' '.$details->state_id;
            ?>
         <option value="<?php echo $state->id ?>" <?php if(!empty($state_list)){ if($state->id == $details->enquiry_state_id){echo 'selected';} }?>><?php echo $state->state; ?></option>
         <?php } ?>
      </select>
   </div>
   <?php }?>
   <?php
      if(is_active_field(CITY_FIELD,$process_id)){
      ?>                   
   <div class="form-group col-sm-6 col-md-6 enq-city">
      <label>City <i class="text-danger"></i></label>
      <select name="city_id" class="form-control" id="fcity">
         <option value="" >Select</option>
         <?php
            foreach ($city_list as $value) { ?>
         <option value="<?=$value->id?>" <?php if($details->enquiry_city_id == $value->id) echo "selected = selected";?>><?=$value->city;?></option>
         <?php                           
            }
            ?>
      </select>
   </div>
   <?php }
   if(is_active_field(PIN_CODE,$process_id)){    ?> 
     <div class="form-group col-sm-6 col-md-6 enq-pincode">
        <label><?php echo display('pin_code') ?> <i class="text-danger"></i></label>
        <input class="form-control" value="<?php  echo $details->pin_code;?> " name="pin_code" type="text"  placeholder="Pin Code"> 
     </div>   
     <?php
   }   
      if(is_active_field(COMPANY,$process_id)){
      ?>
   <div class="form-group col-sm-6 col-md-6 enq-company">
      <label><?php echo display('company_name') ?> <i class="text-danger">*</i></label>
      <input class="form-control" name="company" type="company" value="<?php echo $details->company; ?>">
   </div>
   <?php }
      if(is_active_field(REMARK_FIELD,$process_id)){
      ?>  
   <div class="form-group col-sm-6 col-md-6 enq-remark"> 
      <label><?=display('remark')?></label>
      <textarea class="form-control" name="enquiry"><?php echo $details->enquiry; ?></textarea>
   </div>
   <?php } 
   if(is_active_field(PREFERRED_COUNTRY_FIELD,$process_id)){
   ?>
   <div class="form-group col-sm-6 col-md-6 enq-preferred-country">
      <label>Preferred Country <i class="text-danger">*</i></label>
      <?php
         $current_country  = $details->enq_country;             
         $current_country = explode(',',$current_country);                        
         ?>
      <select name="country_id[]" multiple class="form-control">
         <?php foreach($all_country_list as $product){ ?>
         <option value="<?=$product->id_c?>" <?php if(in_array($product->id_c,$current_country)) echo "selected = selected"; ?>><?=$product->country_name ?></option>
         <?php } ?>
      </select>
   </div> 
   <?php  
  } 
  if($this->session->userdata('companey_id')==29){ ?>
    <div class="form-group col-sm-6 col-md-6 enq-bank-applied-with">
    <label>Bank applied with <i class="text-danger"></i></label>
      <input type="text" id="bankname" name="bankname" class="form-control" value="<?=$details->bank?>">
    </div>
  <?php }  
      if(!empty($dynamic_field)) {       
          foreach($dynamic_field as $ind => $fld){
            ?>
<?php if($fld['input_type']==19){ ?>			   
<div class="col-md-12">
<label style="color:#283593;"><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?><i class="text-danger"></i></label>
 <hr>
</div>
<?php }?>
<?php if($fld['input_type']!=19){ ?>			
            <div class="form-group col-md-6 <?=$fld['input_name']?> " >
               <?php if($fld['input_type']==1){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="text" name="enqueryfield[]"  value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>" id="<?=$fld['input_name']?>"  class="form-control">
               <?php }
               if($fld['input_type']==2){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <?php $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
               ?>
               <select class="form-control"  name="enqueryfield[]" id="<?=$fld['input_name']?>">
                  <option value="">Select</option>
                  <?php  foreach($optarr as $key => $val){
                  ?>
                  <option value = "<?php echo $val; ?>" <?php echo (!empty($fld["fvalue"]) and trim($fld["fvalue"]) == trim($val)) ? "selected" : ""; ?>><?php echo $val; ?></option>
                  <?php
                     } 
                  ?>
               </select>
               <?php }
               if($fld['input_type']==20){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <?php $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
               ?>
               <input type="hidden"  name="enqueryfield[]"  id="multi-<?=$fld['input_name']?>"  value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <select class="multiple-select" name='multi[]' multiple onchange="changeSelect(this)" id="<?=$fld['input_name']?>">
                  <?php  foreach($optarr as $key => $val){                  
                    $fvalues  = explode('|', $fld['fvalue']);
                    ?>
                    <option value = "<?php echo $val; ?>" <?php echo (!empty($fld["fvalue"]) and in_array($val, $fvalues)) ? "selected" : ""; ?>><?php echo $val; ?></option>
                  <?php
                     } 
                  ?>
               </select>
               <?php }
               if($fld['input_type']==3){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="radio"  name="enqueryfield[]"  id="<?=$fld['input_name']?>" class="form-control">                         
               <?php }if($fld['input_type']==4){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="checkbox"  name="enqueryfield[]"  id="<?=$fld['input_name']?>" class="form-control">			   
               <?php }if($fld['input_type']==5){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <textarea   name="enqueryfield[]"  <?= $fld['fld_attributes']; ?> class="form-control" placeholder="<?= $fld['input_place']; ?>" ><?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?></textarea>
               <?php }?>
               <?php if($fld['input_type']==6){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="date"  name="enqueryfield[]" class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==7){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="time"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==8){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="hidden" readonly name="enqueryfield[]"  class="form-control"  value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <input type="file"  name="enqueryfiles[]"  class="form-control" >
                <?php 
               if (!empty($fld["fvalue"])) {
                  ?>
                  <a href="<?=$fld['fvalue']?>" target="_blank"><?=basename($fld['fvalue'])?></a>
                  <?php
               }
                }?>                
               <?php if($fld['input_type']==9){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="password"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                  <?php if($fld['input_type']==10){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="color"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==11){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="datetime-local"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==12){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="email"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==13){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="month"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==14){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="number"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==15){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="url"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==16){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="week"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==17){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="search"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==18){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="tel"  name="enqueryfield[]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>              
               <input type="hidden" name= "inputfieldno[]" value = "<?=$fld['input_id']; ?>">
               <input type="hidden" name= "inputtype[]" value = "<?=$fld['input_type']?>">
            </div>
<?php } ?>
   <?php  }   
  }
  ?>
</div>
<?php if($details->status==2 OR $details->status==3 ){ ?>
 <div class="form-group col-sm-6">  
                           <label>Expected Closer Date </label>                  
                           <input class="form-control"   type="text" value="<?php  if($details->lead_expected_date!='0000-00-00 00:00:00' AND $details->lead_expected_date!=NULL ){echo date("F jS, Y", strtotime($details->lead_expected_date));}else{ echo 'N/A';}?>" readonly>                
                        </div><?php }?>
<div class="row"   id="save_button">
   <div class="col-md-12 text-center">                                                      
      <input type="submit" name="submit_only" class="btn btn-primary" value="Save" >
      <input type="submit" name="submit_and_next" class="btn btn-primary" value="Save And Next">
      <input type="hidden" name="go_new_tab">           
   </div>
</div>
   <?php echo form_close(); 
   }else if($form_type == 1){ ?>
          
          <hr>
          <?php
          if ($tid == 48 || $tid==49) { 
            $form_id = base64_encode($tid);
            $ucomp_id = base64_encode($this->session->companey_id);
            $uenquiry_code = base64_encode($details->Enquery_id);
            $uuid = base64_encode($this->session->user_id);
            $f_url = base_url().'public/survery/'.$form_id.'/'.$ucomp_id.'/'.$uenquiry_code.'/'.$uuid;
            ?>
            <a onclick='share_form("<?=$f_url?>","<?=$details->email?>")' href='javascript:void(0)' class="btn btn-primary btn-sm">Share to user</a>
            <br>
            <br>            
          <?php
          }
          if(!empty($dynamic_field)) {
          ?>
          <div style="overflow-y: scroll;">
          <table class="table table-striped table-bordered table-responsive table-sm">
            <thead class="thead-dark">
                <tr>
                  <?php
                    $counter = 0;
                  if(!empty($dynamic_field)) {
                    foreach($dynamic_field as $ind => $fld){ $counter++; ?>

                        <th><?=ucwords($fld["input_label"])?></th>
                    <?php
                    }
                    ?>
                    <th>Created At</th>
                    <?php
                    if($action['delete'] or $action['edit'])
                        {
                        echo'<th>Action</th>';
                        }
                  }
                  ?>
                </tr>              
            </thead>
            <tbody>
              <?php              
                $sql  = "SELECT GROUP_CONCAT(concat(`extra_enquery`.`input`,'#',`extra_enquery`.`fvalue`,'#',`extra_enquery`.`created_date`,'#',`extra_enquery`.`comment_id`) separator ',') as d FROM `extra_enquery` INNER JOIN (select * from tbl_input where form_id=$tid) as tbl_input ON `tbl_input`.`input_id`=`extra_enquery`.`input` where `extra_enquery`.`cmp_no`=$comp_id and `extra_enquery`.`enq_no`='$details->Enquery_id' GROUP BY `extra_enquery`.`comment_id` ORDER BY `extra_enquery`.`comment_id` DESC";
                $res = $this->db->query($sql)->result_array();    
                //print_r($res);die;

                if (!empty($res)) { 
                  foreach ($res as $key => $value) {
                    ?>
                    <tr>
                    <?php
                    $arr  = explode(',', $value['d']);                     
                    if (!empty($arr)) {
                      foreach($dynamic_field as $ind => $fld){ 
                        $d = 'NA';
                        foreach ($arr as $key1 => $value1) {                        
                          $arr1 = explode('#', $value1);                           
                          if (!empty($arr1[1]) && $arr1[0]==$fld['input']) {
                            $d  = $arr1[1];
                            $d  = explode('/',$arr1[1]);
                            if (filter_var($arr1[1], FILTER_VALIDATE_URL)) 
                            {
                              $d = '<a href='.$arr1[1].'>'.end($d).'</a>';
                            }
                            else
                            {
                              $d = end($d);
                              $multi = explode('|', $d);
                              $d = implode(',', $multi);
                            }                              
                            
                            break;
                          }                         
                        } 
                        ?>                        
                        <td><?=$d?></td>                                                           
                      <?php
                      }
                      ?>
                      <td><?=!empty($arr1[2])?$arr1[2]:'NA'?></td> 
                      <?php
                     //print_r($arr1); exit();
                        if($action['delete'] or $action['edit'])
                        {

                       ?>
                      <td>
                        <?=$action['edit']? "<a data-cmnt='".$arr1[3]."' data-tab-id='".$tid."' data-enq-code='".$details->Enquery_id."' data-comp-id='".$comp_id."' data-tab-name='".$tabname."' class='btn btn-primary btn-xs' onclick='edit_dynamic_query(this)'  data-toggle='modal' data-target='#edit_dynamic_query'><i class='fa fa-edit'></i></a> " :''?>

                        <?=$action['delete']? "<a class='btn btn-danger btn-xs' href='".base_url("enquiry/deleteDocument/$arr1[3]/$details->Enquery_id/").base64_encode($tabname)."' onclick='return alert(\'are you sure\')'><i class='fa fa-trash'></i></a> " :''?>
                        
                      </td>                                                 
                      <?php
                      }
                    } ?>                    
                    </tr>
                    <?php
                  }
                }
                else { ?>
                  <tr><td colspan="<?=($counter+2);?>" class="text-center">No Records Found</td></tr>
                <?php } 
              
              ?>              
            </tbody>
          </table>
          </div>
          <?php
        }?>
         <?php echo form_open_multipart('client/update_enquiry_tab/'.$details->enquiry_id,'class="form-inner"') ?>           
         <input name="en_comments" type="hidden" value="<?=$details->Enquery_id?>" >    
         <input name="tid" type="hidden" value="<?=$tid?>" >    
         <input name="form_type" type="hidden" value="<?=$form_type?>" >    
         <div class="row">
         <?php
         if(!empty($dynamic_field)) {       
          foreach($dynamic_field as $ind => $fld){
            $fld_id = $fld['input_id'];
            ?>  
            <?php if($fld['input_type']==19){?>        
            <div class="col-md-12">
            <label style="color:#283593;"><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?><i class="text-danger"></i></label>
             <hr>
            </div>
            <?php }?>
            <?php if($fld['input_type']!=19){ ?>
            <div class="form-group col-md-6 <?=$fld['input_name']?> col-md-6" >     
               <?php if($fld['input_type']==1){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="text" name="enqueryfield[<?=$fld_id?>]" class="form-control">
               <?php }if($fld['input_type']==2){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <?php $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
               ?>
               <select class="form-control"  name="enqueryfield[<?=$fld_id?>]" >
                  <option>Select</option>
                  <?php  foreach($optarr as $key => $val){
                  ?>
                  <option value = "<?php echo $val; ?>"><?php echo $val; ?></option>
                  <?php
                     } 
                  ?>
               </select>
               <?php }if($fld['input_type']==3){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="hidden"  name="enqueryfield[<?=$fld_id?>]" class="form-control">                         
               <?php 
               $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
                  foreach($optarr as $key => $val){
                  ?><label><?=$val?></label>
                  <input type="radio"  id="<?=$fld['input_name']?>" name="enqueryfield[<?=$fld_id?>]" value="<?=$val;?>" class="form-control">
                <?php
                }                               
               }if($fld['input_type']==4){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="hidden"  name="enqueryfield[<?=$fld_id?>]" class="form-control">                         
               <input type="checkbox"  name="enqueryfield[<?=$fld_id?>]"  id="<?= $fld['input_name']?>" class="form-control">                         
               <?php }if($fld['input_type']==5){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <textarea   name="enqueryfield[<?=$fld_id?>]" <?= $fld['fld_attributes']; ?>  class="form-control" placeholder="<?= $fld['input_place']; ?>" ></textarea>
               <?php }?>
               <?php if($fld['input_type']==6){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="date"  name="enqueryfield[<?=$fld_id?>]" class="form-control">
               <?php }?>
               <?php if($fld['input_type']==7){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="time"  name="enqueryfield[<?=$fld_id?>]"  class="form-control">
               <?php }?>
               <?php if($fld['input_type']==8){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="hidden" readonly name="enqueryfield[<?=$fld_id?>]"  class="form-control"  value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">               
               <input type="file"  name="enqueryfiles[]"  class="form-control" >
               <?php 
               if (!empty($fld["fvalue"])) {
                  ?>
                  <!-- <a href="<?=$fld['fvalue']?>" target="_blank"><?=basename($fld['fvalue'])?></a> -->
                  <?php
               }
            }?>
               <?php if($fld['input_type']==9){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="password"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
                  <?php if($fld['input_type']==10){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="color"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
               <?php if($fld['input_type']==11){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="datetime-local"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
                 <?php if($fld['input_type']==12){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="email"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
                 <?php if($fld['input_type']==13){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="month"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
                 <?php if($fld['input_type']==14){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="number"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
                 <?php if($fld['input_type']==15){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="url"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
                 <?php if($fld['input_type']==16){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="week"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
                 <?php if($fld['input_type']==17){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="search"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>
               <?php if($fld['input_type']==18){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="tel"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" >
               <?php }?>  


               <?php
               if($fld['input_type']==20){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <?php $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
               ?>
               <input type="hidden"  name="enqueryfield[<?=$fld_id?>]"  id="multi-<?=$fld['input_name']?>"  value ="">
               <select class="multiple-select" name='multi[]' multiple onchange="changeSelect(this)" id="<?=$fld['input_name']?>">
                  <?php  foreach($optarr as $key => $val){                  
                    $fvalues  = explode(',', $fld['fvalue']);
                    //<?php echo (!empty($fld["fvalue"]) and in_array($val, $fvalues)) ? "selected" : ""; ?>
                    ?>
                    <option value = "<?php echo $val; ?>" ><?php echo $val; ?></option>
                  <?php
                     } 
                  ?>
               </select>
               <?php }
               ?>

               <input type="hidden" name= "inputfieldno[]" value = "<?=$fld['input_id']; ?>">
               <input type="hidden" name= "inputtype[]" value = "<?=$fld['input_type']?>">
            </div>

<?php } ?>
      <?php  }
         } ?>
         </div>
         <div class="row"   id="save_button">
            <div class="col-md-12 text-center">                                                
               <button class="btn btn-primary" type="submit" >Save</button>            
            </div>
         </div>
   <?php
   echo form_close(); 


   }else{ ?>
         <hr>
         <?php echo form_open_multipart('client/update_enquiry_tab/'.$details->enquiry_id,'class="form-inner tabbed_form"') ?>           
         <input name="en_comments" type="hidden" value="<?=$details->Enquery_id?>" >    
         <div class="row">
         <?php
         if(!empty($dynamic_field)) {       
          foreach($dynamic_field as $ind => $fld){
            $fld_id = $fld['input_id'];
            ?>  
<?php if($fld['input_type']==19){?>			   
<div class="col-md-12">
<label style="color:#283593;"><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?><i class="text-danger"></i></label>
 <hr>
</div>
<?php }?>
<?php if($fld['input_type']!=19){ ?>
            <div class="form-group col-md-6 <?=$fld['input_name']?> col-md-6" >			
               <?php if($fld['input_type']==1){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="text" name="enqueryfield[<?=$fld_id?>]"  value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>"  class="form-control">
               <?php }if($fld['input_type']==2){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <?php $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
               ?>
               <select class="form-control"  name="enqueryfield[<?=$fld_id?>]" >
                  <option>Select</option>
                  <?php  foreach($optarr as $key => $val){
                  ?>
                  <option value = "<?php echo $val; ?>" <?php echo (!empty($fld["fvalue"]) and trim($fld["fvalue"]) == trim($val)) ? "selected" : ""; ?>><?php echo $val; ?></option>
                  <?php
                     } 
                  ?>
               </select>
               <?php }if($fld['input_type']==3){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="hidden"  name="enqueryfield[<?=$fld_id?>]" class="form-control">                         
               <?php 
               $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
                  foreach($optarr as $key => $val){
                  ?><label><?=$val?></label>
                  <input type="radio"  id="<?=$fld['input_name']?>" name="enqueryfield[<?=$fld_id?>]" value="<?=$val;?>" class="form-control">
				  <?php
                     }                               
               }if($fld['input_type']==4){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="hidden"  name="enqueryfield[<?=$fld_id?>]" class="form-control">                         
               <input type="checkbox"  name="enqueryfield[<?=$fld_id?>]"  id="<?= $fld['input_name']?>" class="form-control">                         
               <?php }if($fld['input_type']==5){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <textarea   name="enqueryfield[<?=$fld_id?>]"  <?= $fld['fld_attributes']; ?>  class="form-control" placeholder="<?= $fld['input_place']; ?>" ><?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?></textarea>
               <?php }?>
               <?php if($fld['input_type']==6){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="date"  name="enqueryfield[<?=$fld_id?>]" class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==7){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="time"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==8){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="hidden" readonly name="enqueryfield[<?=$fld_id?>]"  class="form-control"  value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">               
               <input type="file"  name="enqueryfiles[]"  class="form-control" >
               <?php 
               if (!empty($fld["fvalue"])) {
                  ?>
                  <a href="<?=$fld['fvalue']?>" target="_blank"><?=basename($fld['fvalue'])?></a>
                  <?php
               }
            }?>
               <?php if($fld['input_type']==9){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="password"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                  <?php if($fld['input_type']==10){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="color"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==11){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="datetime-local"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==12){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="email"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==13){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="month"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==14){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="number"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==15){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="url"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==16){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="week"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
                 <?php if($fld['input_type']==17){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="search"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>
               <?php if($fld['input_type']==18){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <input type="tel"  name="enqueryfield[<?=$fld_id?>]"  class="form-control" value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <?php }?>	
                
               <?php
               if($fld['input_type']==20){?>
               <label><?php echo(!empty($fld["input_label"])) ?  ucwords($fld["input_label"]) : ""; ?></label>
               <?php $optarr = (!empty($fld['input_values'])) ? explode(",",$fld['input_values']) : array(); 
               ?>
               <input type="hidden"  name="enqueryfield[<?=$fld_id?>]"  id="multi-<?=$fld['input_name']?>"  value ="<?php echo  (!empty($fld["fvalue"])) ? $fld["fvalue"] : ""; ?>">
               <select class="multiple-select" name='multi[]' multiple onchange="changeSelect(this)" id="<?=$fld['input_name']?>">
                  <?php  foreach($optarr as $key => $val){                  
                    $fvalues  = explode('|', $fld['fvalue']);
                    ?>
                    <option value = "<?php echo $val; ?>" <?php echo (!empty($fld["fvalue"]) and in_array($val, $fvalues)) ? "selected" : ""; ?>><?php echo $val; ?></option>
                  <?php
                     } 
                  ?>
               </select>
               <?php }
               ?>
               
               <input type="hidden" name= "inputfieldno[]" value = "<?=$fld['input_id']; ?>">
               <input type="hidden" name= "inputtype[]" value = "<?=$fld['input_type']?>">
            </div>
<?php } ?>
      <?php  }
         } ?>
         </div>
         <div class="row" id="save_button">
            <div class="col-md-12 text-center">                                                               
               <input type="submit" name="submit_only" class="btn btn-primary" value="Save" >
               <input type="submit" name="submit_and_next" class="btn btn-primary" value="Save And Next">
               <input type="hidden" name="go_new_tab">
            </div>
         </div>
   <?php
   echo form_close(); 
   }
?>
<script>
  $(document).ready(function(){
  var src_id = $('#lead_source').val();
  var sub_src_id = '<?= $details->sub_source; ?>'  
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>lead/get_subsource_by_source',
        data: {src_id:src_id,sub_src_id:sub_src_id},
        success:function(data){        
          $("#subsource").html(data);
        }    
    });
  });
  function find_sub1(){    
    var src_id = $('#lead_source').val();    
        $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>lead/get_subsource_by_source',
        data: {src_id:src_id},
        success:function(data){        
          $("#subsource").html(data);
        }    
    });
  }
</script>
<script>
  $(function(){
    $('.multiple-select').select2();
  });

  function changeSelect(e){        
    var input_name = e.id;
    var data = $("#"+input_name).val();
    // var z = data.toString();
    //  alert(z);
    data = data.join('|');
    //alert(data);

    $("#multi-"+input_name).val(data);
  }
  
  function share_form(f_url,email){    
    if (confirm('Are you sure ?')) {
      $.ajax({
        url: "<?=base_url().'message/send_sms'?>",
        type:"POST",
        data:{
          mesge_type:3,
          message_name:f_url,
          email_subject:'Survey Form',
          mail:email
        },
        success: function(data){
          alert(data);
        }
      });
    }
  }  
</script>
<?php
if($this->session->companey_id==65 && $tid == 57){
   ?>
   <script type="text/javascript">
      $("select[name='enqueryfield[4478]']").html('<option value="">test</option>')
   </script>
   <?php
}
?>