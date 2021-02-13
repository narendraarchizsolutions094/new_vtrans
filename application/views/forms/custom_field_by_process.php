<?php 
if(!empty($company_list))
{?>				   
   <?php foreach($company_list as $companylist){?>                        
      <div class="form-group col-sm-4 <?= $companylist['input_name']?>">
                     
                       <?php if($companylist['input_type']==1){?>
                        
                        <label> <?= ucwords($companylist['input_label'])?> <?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?> </label>
                       
                             <input type="text" name="enqueryfield[]" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?> class="form-control" value="<?=!empty($res[$companylist['input_id']])?$res[$companylist['input_id']]:''?>">
                          
                     
                  <?php }if($companylist['input_type']==2){?>

                        <label><?= ucwords($companylist['input_label'])?> <?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
                     <select class="form-control"  name="enqueryfield[]"  id="<?= $companylist['input_name']?>"  <?php if($companylist['label_required']==1){echo "required";}?>> 
                        <option value="">Select</option>
						<?php $optarr = (!empty($companylist['input_values'])) ? explode(",",$companylist['input_values']) : array(); 
						foreach($optarr as $key => $val){
							
							?><option value = "<?php echo $val ?>" <?=(!empty($res[$companylist['input_id']]) && $res[$companylist['input_id']]==$val)?'selected':''?>><?php echo $val ?></option><?php
						}	
						
						?>
                     </select> 

                  <?php }if($companylist['input_type']==3){?>

                        <label><?= ucwords($companylist['input_label'])?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
                         <input type="radio"  name="enqueryfield[]"  id="<?= $companylist['input_name']?>" class="form-control"  <?php if($companylist['label_required']==1){echo "required";}?>>                         

                   
                    <?php }if($companylist['input_type']==4){?>
                      
                     
                        <label><?= ucwords($companylist['input_label'])?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
                         <input type="checkbox"  name="enqueryfield[][]"  id="<?= $companylist['input_name']?>" class="form-control" <?php if($companylist['label_required']==1){echo "required";}?>>                         
                     
                    <?php }
                    if($companylist['input_type']==5){ ?>
                     
                   <label><?= ucwords($companylist['input_label'])?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
                   <textarea   name="enqueryfield[]"   id="<?= $companylist['input_name']?>" class="form-control" placeholder="<?= $companylist['input_place']; ?>" <?php if($companylist['label_required']==1){echo "required";}?>></textarea>
                     

                     <?php }


                     if($companylist['input_type']==6){?>
                     
                        <label> <?= ucwords($companylist['input_label'])?> <?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
                       
                             <input type="date" name="enqueryfield[]" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?> class="form-control">
                          
                     
                   
                  <?php }

                  if($companylist['input_type']==7){?>
                    
                     
                        <label> <?= ucwords($companylist['input_label'])?> <?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?> </label>
                       
                             <input type="time" name="enqueryfield[]" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?> class="form-control">
                          
                     
                   
                  <?php }

                  if($companylist['input_type']==8){?>
                    
                     
                        <label> <?= ucwords($companylist['input_label'])?> <?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?> </label>
                       
                             <input type="file" name="enqueryfiles[]" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?> class="form-control">
                          
                     
                   
                  <?php }



                if($companylist['input_type']==9){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?> <?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?> </label>
               <input type="password"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
                  <?php if($companylist['input_type']==10){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?> <?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?> </label>
               <input type="color"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
               <?php if($companylist['input_type']==11){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="datetime-local"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
                 <?php if($companylist['input_type']==12){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="email"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
                 <?php if($companylist['input_type']==13){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="month"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
                 <?php if($companylist['input_type']==14){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="number"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
                 <?php if($companylist['input_type']==15){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="url"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
                 <?php if($companylist['input_type']==16){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="week"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
                 <?php if($companylist['input_type']==17){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="search"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }?>
               <?php if($companylist['input_type']==18){?>
               <label><?php echo(!empty($companylist["input_label"])) ?  ucwords($companylist["input_label"]) : ""; ?><?php if($companylist['label_required']==1){echo '<i class="text-danger">*</i>';}?></label>
               <input type="tel"  name="enqueryfield[]"  class="form-control" id="<?= $companylist['input_name']?>" placeholder="<?= $companylist['input_place']; ?>"  <?php if($companylist['label_required']==1){echo "required";}?>>
               <?php }
					 ?>
          </div>
           <input type="hidden" name= "inputfieldno[]" value = "<?php echo $companylist['input_id'];  ?>">
					 <input type="hidden" name= "inputtype[]" value = "<?php echo $companylist['input_type'];  ?>">
				
					 <?php
					 }?>      
			   <?php } ?>
