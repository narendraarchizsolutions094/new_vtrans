<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<style type="text/css">
    
.custom-form-control
{
    border-radius: 15px;
    box-shadow: none;
    border: 1px solid #e4e5e7;
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none
}
</style>
<?php 
    if(!empty($nav1)){}else{$nav1='';}
?>
<div class="row form_align" style="">
    <!--  form area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail"> 
            <div class="panel-heading no-print">
                <div class="btn-group"> 
                    <a class="btn btn-primary" href="<?php echo base_url("user/index") ?>"> <i class="fa fa-list"></i>  <?php echo display('user_list') ?> </a>  
                </div>
            </div>
            <div class="panel-body panel-form">
                <div class="row">				
                    <div class="col-md-12">
                    
                       <?php echo form_open_multipart('user/create',array('class'=>"form-inner",'id'=>'user_form')) ?>
                            <?php echo form_hidden('dprt_id',$department->pk_i_admin_id) ?>  
                              <ul class="nav nav-tabs">
              									<li class="active"><a data-toggle="tab" href="#prsnl-info" id="prsnl-info-tab"><?php echo display('personal_info'); ?></a></li>
              									<li><a data-toggle="tab" href="#acnt-details"  id="act-det-info">Contact Person Details</a></li>
                                                  <li><a data-toggle="tab" href="#comp-details" id="add-cmp-det"><?php echo display('account_details') ?></a></li>
                                                 <li><a data-toggle="tab" href="#user_whatsapp">Setup Whatsapp API</a></li>
                                                  
                                              </ul>
                                              
				<div class="tab-content">
									<br/>
					<div id="prsnl-info" class="tab-pane fade in active">
                     <div class="form-row">                                
                              
                                <?php if(!empty($department->picture)){?>
                                 <div class="col-sm-4" align="center"> 
                                <img alt="Picture" src="<?php echo (!empty($department->picture)?base_url($department->picture):base_url("assets/images/no-img.png")) ?>" id="picture" width="150" height="150">
                                  <input class="form-control" name="file" type="file" id="file" value="<?= $department->picture ?>" onchange="document.getElementById('picture').src = window.URL.createObjectURL(this.files[0])">
                                    <input type="hidden" name="new_file" value="<?= $department->picture ?>" class="form-control" >
                            </div>
                                <?php }else{ ?>
                                <div class="col-sm-4" align="center"> 
                                <img alt="Picture" src="<?php echo (!empty($department->picture)?base_url($department->picture):base_url("assets/images/no-img.png")) ?>" id="picture" width="150" height="150">
                                  
                                  <input class="form-control" name="file" type="file" id="file" value="<?= $department->picture ?>" onchange="document.getElementById('picture').src = window.URL.createObjectURL(this.files[0])">
                                    <input type="hidden" name="new_file" value="<?= $department->picture ?>" class="form-control" >
                            </div>                                
                                <?php } ?>                                
                                <div class="form-group col-md-4">
                                        <label for="description" ><?php echo display('employee_id') ?>  <i class="text-danger">*</i></label>
                                     <input type="text" id="name" class="form-control br_25  m-0 icon_left_input" name="employee_id" value="<?php  if(!empty($enq_id)){ echo $enq_id; }else{echo $department->employee_id;} ?>" placeholder="<?php echo display('employee_id') ?>" required>
                                 </div>
                                 
                                  <div class="form-group col-md-4">
                                    <label>Designation<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="designation" id="designation" value="<?= $department->designation ?>" placeholde="Designation" required>
                                    
                                </div>
                                 
                                 <div class="form-group  col-md-4">
                                        <label for="description" class="col-form-label">First Name  <i class="text-danger">*</i></label>
                                         <input type="text" id="name" class="form-control br_25  m-0 icon_left_input" name="Name" value="<?php echo $department->s_display_name;?>" placeholder="First Name" required >
                                 </div>                                 
                                 <div class="form-group  col-md-4">
                                        <label for="description" class="col-form-label">Last Name  <i class="text-danger">*</i></label>
                                         <input type="text" id="last_name" class="form-control br_25  m-0 icon_left_input" name="last_name" value="<?php echo $department->last_name;?>" placeholder="Last Name">
                                 </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Primary Email <i class="text-danger">*</i></label>
                                     <input type="email" id="emailid" class="form-control br_25  m-0 icon_left_input" value="<?php echo $department->s_user_email; ?>"  placeholder="Primary Email" name="email" required>
                                </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Secondary Email</label>
                                   <input type="email" id="emailid" class="form-control br_25  m-0 icon_left_input" value="<?php echo $department->second_email; ?>"  placeholder="Secondary Email" name="second_email">
                                </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Primary Phone Number <i class="text-danger">*</i></label>
                               <input type="text" id="mobile" class="form-control br_25  m-0 icon_left_input" maxlength="12"  placeholder="Primary Phone Number" value="<?php echo $department->s_phoneno; ?>" name="cell" required>
                               </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Secondary Phone Number</label>
                               <input type="text" id="mobile" class="form-control br_25  m-0 icon_left_input" maxlength="10"  placeholder="Secondary Phone Number" value="<?php echo $department->second_phone; ?>" name="second_phone">
                               </div>
                                <?php if (empty($department->pk_i_admin_id)) { ?>
                                <div class="form-group col-md-4">
                                     <label for="description"><?php echo display('password') ?>  <i class="text-danger">*</i></label>
                                     <input type="password" id="password" class="form-control br_25  m-0 icon_left_input" name="password" placeholder="Password" required>
                                </div>
                                 <?php 	}?>
                            </div>
                            <div class="form-row">                                
                                <div class="form-group col-md-4">
                                    <label for="description">Date of birth </label>
                                    <input  class="form-control form-date" name="dob" id="dob" value="<?php echo $department->date_of_birth;?>" >                                    
                                </div>                                
                                <div class="form-group col-md-4">
                                    <label for="description">Joining Date</label>
                                    <input  class="form-control form-date" name="joining_date" id="joining_date" value="<?php echo $department->joining_date;?>" >                                    
                                </div>                                
                                 <div class="form-group col-md-4">
                                    <label for="description">Anniversary </label>
                                    <input name="anniversary" id="anniversary"   class="form-control form-date"  value="<?php echo $department->anniversary;?>">
                                    
                                </div>
                                
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Address</label>
                                    <textarea class="custom-form-control" id="address" rows="3" name="address"><?php echo $department->add_ress;?></textarea>
                                </div>
                                
                            </div>
                            
                            <div class="form-row">
                                
                               
                                
                                <div class="form-group col-md-4">
                                    <label>Blood Group </label>
                                    <select class="form-control" name="employee_band" id="employee_band">
                                        <option value="" style="display:none">---Select---</option>
                                        <option value="O +" <?php if($department->employee_band=='O +'){echo"selected";} ?>>O +</option>
                                        <option value="O -" <?php if($department->employee_band=='O -'){echo"selected";} ?>>O -</option>
                                        <option value="B +" <?php if($department->employee_band=='B +'){echo"selected";} ?>>B +</option>
                                        <option value="B -" <?php if($department->employee_band=='B -'){echo"selected";} ?>>B -</option>
                                        <option value="A +" <?php if($department->employee_band=='A +'){echo"selected";} ?>>A +</option>
                                        <option value="A -" <?php if($department->employee_band=='A -'){echo"selected";} ?>>A -</option>
                                        <option value="AB +" <?php if($department->employee_band=='AB +'){echo"selected";} ?>>AB +</option>
                                         <option value="AB -" <?php if($department->employee_band=='AB -'){echo"selected";} ?>>AB -</option>
                                    </select>
                                    
                                </div>
                                
                                <div class="form-group col-md-4">
                                     <label><?php echo display('country_name')?> </label>
                                     <select class="form-control" name="country" id="country">
                                       <option value="" >---Select country---</option>
                                  <?php  foreach($county_list as $c){ ?>
                                    <option value="<?php echo $c->id_c; ?>"  <?php if ($c->id_c==$department->country) {
                                       echo'selected';
                                    } ?>><?php echo $c->country_name; ?></option>
                                  <?php }?>
                                   </select>
                                    
                                </div>
                                
                            </div>
                            
                            <div class="form-row">
                                
                                  <div class="form-group col-md-4">
                                    <label><?php echo display('region_list')?> </label>
                                     <select class="form-control" name="region" id="region" >
                                          <option value="" >---Select region---</option>
                                            <?php foreach($region_list as $region){
                                            if($region->region_id==$department->region){ ?>                     
                                                <option value="<?=$region->region_id ?>" selected><?=$region->region_name ?></option>                                                
                                            <?php }else{?>
                                                <option value="<?=$region->region_id ?>" ><?=$region->region_name ?></option>
                                           <?php  } }?>                                            
                                     </select>                                    
                                </div>       
                                <div class="form-group col-md-4">                                    
                                    <label><?php echo display('state_name')?> </label>                                <select class="form-control state_id" name="state_id" id="state_id"> 
                                          <option value="" >---Select state---</option>
                                       <?php foreach($state_list as $state){?>                                       
                                            <option value="<?= $state->id?>" <?php if($state->id==$department->state_id){echo 'selected';} ?>><?=$state->state ?></option>                                       
                                       <?php } ?>                            
                                   </select>                                   
                                </div>                                
                                <div class="form-group col-md-4">
                                       <label  class="col-form-label"><?php echo display('territory_name')?> </label>
                                       <select class="form-control territory" name="territory" id="territory">
                                          <option value="" >---Select territory---</option>
                                       <?php 
                                       
                                       if (!empty($territory_lsit)) {
                                        // print_r($territory_lsit);
                                        // die(); 
                                       foreach($territory_lsit as $territory){?>                          
                                            <option value="<?= $territory->territory_id ?>" <?php if($territory->territory_id==$department->territory_name){echo 'selected';} ?>><?=$territory->territory_name ?></option>  
                                       <?php } 
                                       }
                                       ?>                                       
                                   </select>                          
                                </div> 
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="process">Product</label>                                   
                                    <select data-placeholder="Begin typing a name to filter..." multiple class="form-control chosen-select" name="product[]">
                                        <option value=""></option>
										<?php foreach($products_list as $val) { ?>
                                        <option value="<?= $val->id ?>" <?php $cid=explode(',',$department->products); foreach($cid as $cc){if ($cc == $val->id)echo 'selected'; }?>><?php echo $val->country_name; ?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-row">
                                   <div class="form-group col-md-4">
                                     <label><?php echo display('city_name')?> </label>
                                    <select class="form-control city_name" name="city_name" id="city_name" >
                                             <option value="" >---Select city---</option>
                                                                       <?php 
                                                                       if (!empty($city_list)) {
                                                                           # code...
                                                                       foreach($city_list as $city){?>                                       
                                            <option value="<?= $city->id?>" <?php if($city->id==$department->city_id){echo 'selected';} ?>><?= $city->city ?></option>
                                        
                                        <?php } 
                                                                       }
                                        ?>
                                  
                                   </select>
                                    
                                </div>
                                
                                 <div class="form-group col-md-4">
                                     <label><?php echo display('user_function') ?><i class="text-danger">*</i></label>
                                     <select name='user_type' class="form-control" required>
                						<option value='' style="display:none;">---Select User Type----</option>
                						<?php foreach($user_role as $r){?>
                						 <option value="<?php echo $r->use_id; ?>" <?php if($department->user_type==$r->use_id){echo "selected";}?>><?php echo $r->user_role; ?></option>
                                       <?php } ?>
            						</select>
                                </div>
                                  <?php
                                  if (user_access(220)) { ?>
                                    <div class="form-row col-md-4">
                                      <label><?php echo display('telephony_agent_id');?></label>
                                      <input type="test" name="telephony_agent_id" value="<?php echo $department->telephony_agent_id;?>" class="form-control">
                                    </div>                                                                                                                    
                                  <?php
                                  }
                                  ?>
                                
                                <div class="form-group col-md-4" style="visibility: hidden;">
                                    <label><?php echo display('user_level');?> <i class="text-danger">*</i></label>
                                    <select name='user_role' class="form-control" >
                						<option value='' style="display:none;">---Select User Type---</option>
                						<option value='2' <?php if($department->user_roles==2){echo "selected";}?>><?php echo display('admin_level');?></option>
                                        <option value='3' <?php if($department->user_roles==3){echo "selected";}?>><?php echo display('country_level');?></option>
                                        <option value='4' <?php if($department->user_roles==4){echo "selected";}?>><?php echo display('region_level');?></option>
                                        <option value='5' <?php if($department->user_roles==5){echo "selected";}?>><?php echo display('state_level');?></option>
                                        <option value='6' <?php if($department->user_roles==6){echo "selected";}?>><?php echo display('territory_level');?> </option>
                                        <option value='7' <?php if($department->user_roles==7){echo "selected";}?>><?php echo display('city_level');?></option>
                                        <option value='8' <?php if($department->user_roles==8){echo "selected";}?>><?php echo display('user_level');?></option>
            						</select>
                                </div>
                                
                                
                            </div>
                            
                    <div class="form-row">
                                
                                <div class="form-group col-md-4">
                                    
                                    <label>Report to</label>
                                    <select class="form-control" name="report_to">
                                        <option value="" style="display:none;">---Select---</option>
                                        <?php foreach($user_list as $user){?>
                                        
                                            <option value="<?= $user->pk_i_admin_id ?>" <?php if($user->pk_i_admin_id==$department->report_to){echo 'selected';}?>><?= $user->s_display_name." ".$user->last_name ?></option>
                                        
                                        <?php } ?>
                                    </select>
                                    
                                </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="process">Process <i class="text-danger">*</i></label> 									
                                    <select class="form-control" name="process[]" multiple required >
                                        <?php
                                        if (!empty($products)) {
                                            foreach ($products as $key => $value) { ?>
                                                <option value="<?=$value->sb_id?>" <?php if(!empty($department->process) && in_array($value->sb_id, explode(',', $department->process))) echo "selected"; ?>><?=$value->product_name?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        
                            <?php
                            if($this->session->companey_id == 65 or 1){    
                            ?>
                             <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="reporting_location"><?=display('reporting_location')?></label> 									
                                    <select class="form-control" name="reporting_location">
                                        <?php
                                        $rep_loc = $this->user_model->get_user_meta($department->pk_i_admin_id,array('reporting_location'));;
                                        if (!empty($reporting_locations)) {
                                            foreach ($reporting_locations as $key => $value) { ?>
                                                <option value="<?=$value['id']?>" <?php if(!empty($rep_loc) && $rep_loc['reporting_location'] == $value['id']) echo "selected"; ?>><?=$value['title']?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Discount Allowed</label>                  
                                    <select class="form-control" name="discount_id">
                                      <?php
                                      if(!empty($discount_list))
                                      {
                                        foreach ($discount_list as $key => $value) {
                                         echo'<option value="'.$value->id.'" '.($value->id==$department->discount_id?'selected':'').'>'.$value->name.'</option>';
                                        }
                                      }
                                      ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Per Km Rate </label>   
                                    <?php
                                     $km_rate = $this->user_model->get_user_meta($department->pk_i_admin_id,array('km_rate'));
                                     
                                    ?>               
                                      <input type="number" class="form-control" name="km_rate" value="<?php if(!empty($km_rate['km_rate'])){echo $km_rate['km_rate'];}else{
                                          echo'0';
                                      } ?>">
                                </div>
                            </div> 
                            <?php
                            }
                            ?>
							
                                <?php if (!empty($department->pk_i_admin_id)) { ?>
                                
                                <input type="hidden" id="password" class="form-control br_25  m-0 icon_left_input" name="old_pass" placeholder="Password"  value="<?php echo $department->s_password; ?>" >
                                       
                                 <?php 	}?>							
						    <div class="row">
								  <div class="col-md-12 text-center"> <a href="#act-det-info" class="ui positive button nxt-tab-btn">  Next </a></div>
						    </div>	
                        </div>							
					</div><!--	TAB END -->
					<div id="acnt-details" class="tab-pane fade">
										     
                            <div class="form-group col-md-12">
                                     <h1>Contact Person Details</h1>
                                         
                                </div>
                                 
                                 
                                  <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Contact Person</label>
                                     <input type="text"  class="form-control br_25  m-0 icon_left_input" value="<?php echo $department->contact_pname; ?>"  placeholder="Contact Person" name="cname">
                                </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Primary Email</label>
                                     <input type="email" class="form-control br_25  m-0 icon_left_input" value="<?php echo $department->contact_pemail; ?>"  placeholder="Primary Email" name="cemail">
                                </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Secondary Email</label>
                                   <input type="email"  class="form-control br_25  m-0 icon_left_input" value="<?php echo $department->contact_semail; ?>"  placeholder="Secondary Email" name="csemail">
                                </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Primary Phone Number</label>
                                     <input type="text"   maxlength="10" class="form-control br_25  m-0 icon_left_input" value="<?php echo $department->contact_phone; ?>"  placeholder="Primary Phone Number" name="cphone">
                                </div>
                                 <div class="form-group col-md-4">
                                    <label for="exampleFormControlTextarea1">Secondary Phone Number</label>
                                     <input type="text"  maxlength="10"  class="form-control br_25  m-0 icon_left_input" value="<?php echo $department->contact_sphone; ?>"  placeholder="Secondary Phone Number" name="csphone">
                                </div>
                                
                            
                               
							
							
								<div class="row">
									<div class="col-md-12 text-center">
										<div class="ui buttons">
											<button href="#prsnl-info-tab" class="ui button nxt-tab-btn">  Prev </button>
											<div class="or"></div>
											<button href="#add-cmp-det"  class="ui positive  nxt-tab-btn button"><?php echo display('next') ?></button>
										</div>
									</div>
								</div>	
					</div>
					<div id="comp-details" class="tab-pane fade">
								
                            
							 <div class="form-group row">
                            
                                  <label for="specialist" class="col-xs-9 col-form-label "><h1>Account Details</h1></label>
                               
                            </div>
                            <div class="form-group row">
                                <label for="a_name" class="col-xs-3 col-form-label">Account name</label>
                                <div class="col-xs-9">
                                    <input name="a_name" type="text" class="form-control" id="firstname" placeholder="Account name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a_account_number" class="col-xs-3 col-form-label">Account Number</label>
                                <div class="col-xs-9">
                                    <input name="a_account_number" type="text" class="form-control" id="firstname" placeholder="Account Number" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a_ifsc" class="col-xs-3 col-form-label">IFSC Code</label>
                                <div class="col-xs-9">
                                    <input name="a_ifsc" type="text" class="form-control" id="firstname" placeholder="IFSC Code" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a_branch" class="col-xs-3 col-form-label">Branch Name </label>
                                <div class="col-xs-9">
                                    <input name="a_branch" type="text" class="form-control" id="firstname" placeholder="Branch Name" value="">
                                </div>
                            </div>
                            <?php
                            if(user_access('131'))
                            {
                              ?>
                            <div class="form-group row">
                                 <label class="col-xs-3 col-form-label"><?php echo display('status') ?></label>
                                 <div class="col-xs-9">
                                    <label class="radio-inline"><input type="radio" name="status" value="1" <?php if($department->b_status==1){echo 'checked';}?>><?php echo display('active') ?></label>
                                    <label class="radio-inline"><input type="radio" name="status" value="0" <?php if($department->b_status==0){echo 'checked';}?> ><?php echo display('inactive') ?></label>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                            <div class="form-group row">
                                <div class="col-sm-offset-3 col-sm-6">
                                    <div class="ui buttons">
										<button href="#act-det-info" class="ui button nxt-tab-btn">  Prev </button>
                                        <div class="or"></div>
                                        <button class="ui positive button" type="submit" id="submit_btn"><?php echo display('save') ?></button>
                                    </div>
                                </div>
                            </div>
							
                    </div>
                    <div id="user_whatsapp" class="tab-pane fade">
								
                            
							 <div class="form-group row">
                                  <div class="row">
                <div class="form-group   col-sm-6">
                    <label>API Name</label>
                <input class="form-control" name="api_name" type="text"  value="<?php if(!empty($user_meta['api_name'])){ echo $user_meta['api_name'];}?>"> 
                
                </div>  
            
                <div class="form-group col-sm-6"> 
                  <label>HTTP API</label>
                  <input class="form-control" name="api_url" type="text"  value="<?php if(!empty($user_meta['api_url'])){ echo $user_meta['api_url'];}?>">  
                </div> 
                
              </div>
                                  
                            </div>
                           
                            
                            <div class="form-group row">
                                <div class="col-sm-offset-3 col-sm-6">
                                    <div class="ui buttons">
										<button href="#add-cmp-det" class="ui button nxt-tab-btn">  Prev </button>
                                        <div class="or"></div>
                                        <button class="ui positive button" type="submit" id="submit_btn"><?php echo display('save') ?></button>
                                    </div>
                                </div>
                            </div>
							
					</div>
				</div>
				</form>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#new_pass, #c_pass').on('keyup', function() {
    if ($('#new_pass').val() == $('#c_pass').val()) {
        $('#password_error').html('Matching').css('color', 'green');
    } else
        $('#password_error').html('Not Matching').css('color', 'red');
});
function find_teretory() {
    $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>location/get_tretory_byid',
            data: $('#territory').serialize()
        })
        .done(function(data) {
            if (data != '') {
                document.getElementById('territory_id').innerHTML = data;
            } else {
                document.getElementById('territory_id').innerHTML = '';
            }
        })
        .fail(function() {
        });
}
function find_state() {
    $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>location/get_state_byid',
            data: $('#territory').serialize()
        })
        .done(function(data) {
            if (data != '') {
                document.getElementById('state_id').innerHTML = data;
            } else {
                document.getElementById('state_id').innerHTML = '';
            }
        })
        .fail(function() {
        });
}
function find_city() {
    $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>location/get_city_byid',
            data: $('#territory').serialize()
        })
        .done(function(data) {
            if (data != '') {
                document.getElementById('city_name').innerHTML = data;
            } else {
                document.getElementById('city_name').innerHTML = '';
            }
        })
        .fail(function() {
        });
}
$(function() {
    $("#country").change(function() {
        var country = $(this).val();
        var html = '';
        $.ajax({
            url: '<?php echo base_url();?>Location/find_region',
            type: 'POST',
            data: {
                country: country
            },
            success: function(data) {
                var obj = JSON.parse(data);
                html += '<option value="">---Select Region---</option>';
                for (var i = 0; i < (obj.length); i++) {
                    html += '<option value="' + obj[i].region_id + '">' + obj[i].region_name + '</option>';
                }
                $("#region").html(html);
                $(".state_id").prop('selectedIndex',0);
                $('.city_name').prop('selectedIndex',0);
                $('.territory').prop('selectedIndex',0);
            }

        });
    });

    //Get region 
    $("#region").change(function() {
        var region_id = $(this).val();
        var html1 = '';
        $.ajax({
            url: '<?php echo base_url();?>Location/select_state_by_region',
            type: 'POST',
            data: {
                region_id: region_id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                if (obj.lenght == 0) {
                    console.log('NOT data found');
                } else {
                    html1 += '<option value="" >---Select State---</option>';
                    for (var i = 0; i < (obj.length); i++) {
                        html1 += '<option value="' + obj[i].id + '">' + obj[i].state + '</option>';
                    }
                    $(".state_id").html(html1);
                $('.city_name').prop('selectedIndex',0);
                $('.territory').prop('selectedIndex',0);

                }

            }

        });
    });
    //Get State according to region..

    //Find state base on territory
    $(".state_id").change(function() {
        var state_id = $(this).val();
        var html = '';
        $.ajax({
            url: '<?php echo base_url();?>Location/select_territory_by_state',
            type: 'POST',
            data: {
                state_id: state_id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                html += '<option value="" >---Select Territory---</option>';
                for (var i = 0; i < (obj.length); i++) {
                    html += '<option value="' + obj[i].territory_id + '">' + obj[i].territory_name + '</option>';
                }
                $('.territory').html(html);
                $('.city_name').prop('selectedIndex',0);
            }
        });
    });
    //Find City based on territory....
    $(".territory").change(function() {
        var territory_id = $(this).val();
        var html = '';
        $.ajax({
            url: '<?php echo base_url();?>Location/select_city',
            type: 'POST',
            data: {
                territory_id: territory_id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                html += '<option value="" >---Select City---</option>';
                for (var i = 0; i < (obj.length); i++) {
                    html += '<option value="' + obj[i].id + '">' + obj[i].city + '</option>';
                }
                $('.city_name').html(html);
            }
        });
    });
});   
$(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
})
$("#submit_btn").on('click',function(e){
  
  var emp_id  = $("input[name='employee_id']").val();
  var designation  = $("input[name='designation']").val();
  var fname  = $("input[name='Name']").val();
  var lname  = $("input[name='last_name']").val();
  var email  = $("input[name='email']").val();
  var mobile  = $("input[name='cell']").val();
  var pass  = $("input[name='password']").val();
  var user_type  = $("select[name='user_type']").val();
  var msg = '';
  
  if (!emp_id) {
    msg += '<b>Employee Id is required.</b><br>';
  }
  if (!designation) {
    msg += '<b>Designation is required.</b><br>';    
  }  
  if (!fname) {
    msg += '<b>First Name is required.</b><br>';    
  }
  if (!lname) {
    msg += '<b>Last Name is required.</b><br>';    
  }
  if (!email) {
    msg += '<b>Email is required.</b><br>';    
  }
  if (!mobile) {
    msg += '<b>Mobile No is required.</b><br>';    
  }
  var editt = $("input[name='dprt_id']").val();
  if (!pass && editt=='') {
    msg += '<b>Password is required.</b><br>';    
  }
  if (!user_type) {
    msg += '<b>User Right is required</b>.<br>';    
  }
 
  if (msg) {
    e.preventDefault();
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      html: msg    
    });
  }
});
</script>                              
