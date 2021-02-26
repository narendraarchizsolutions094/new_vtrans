  <?php  foreach ($rate as $key => $value) {?>

  <div  class="panel panel-default thumbnail">

<div class="panel-body panel-form">

                    <div class="row ">

                         <div class="col-md-12">  <a class="btn btn-success" href="<?php echo base_url("setting/branch_ratelist") ?>"> <i class="fa fa-list"></i> Rate List </a> </div>
                        <div class="col-lg-10 ">
                        <form action="<?=base_url().'setting/addbranch_rate'?>" enctype="multipart/form-data" method='post'>
<br>
<br>
                <div class="row form-group">
                <div class="col-md-4">
                <label>Booking <?=ucwords($value->type)?> </label>
                </div>
                <div class="col-md-6">
                  <input type="hidden" name="bbranch" value="<?=$value->booking_branch?>">
                  <input disabled class="form-control" name="" value="<?=$value->from?>">
               <!--  <select name="bbranch" class="form-control">
                 <?php
                $branch= $this->db->where('comp_id',$this->session->companey_id)->get('branch')->result();
                 foreach ($branch as $key => $valueb) { ?>
                    <option value="<?= $valueb->branch_id ?>" <?php if($valueb->branch_id==$value->booking_branch){echo'selected';} ?>><?= $valueb->branch_name ?></option>
                   <?php
                 } ?>
                 </select> -->
            </div> 
                </div>
                <div class="row form-group">

            <div class="col-md-4">
                <label>Delivery <?=ucwords($value->type)?>  </label>
            </div>
                <div class="col-md-6">  
                  <input type="hidden" name="dbranch" value="<?=$value->delivery_branch?>">
                  <input disabled class="form-control" name="" value="<?=$value->to?>">
             <!-- <select name="dbranch" class="form-control">
             <?php foreach ($branch as $key => $values) { ?>
                    <option value="<?= $values->branch_id ?>" <?php if($values->branch_id==$value->delivery_branch){echo'selected';} ?>><?= $values->branch_name ?></option>
                   <?php
                 } ?>
             </select> -->
            </div>
                </div>
                <div class="row form-group">

            
            <div class="col-md-4">
                <label>Rate </label>
            </div>
            <div class="col-md-6">

              <input name="rateid" class="form-control" required value="<?= $value->id ?>" hidden>
              <input name="rate_type" class="form-control" required value="<?= $value->type ?>" hidden>
              <input name="rate" class="form-control" required value="<?= $value->rate ?>" >
            </div>
                </div>
                <div class="row form-group">

            <div class="col-md-4"> 
                <label>Status </label>
            </div>
            <div class="col-md-6">

                <div class="form-check">
                <label class="radio-inline">
                  <input type="radio" name="status" value="0" <?php if($value->rate_status==0){echo'checked';} ?>>Active</label>
                <label class="radio-inline">
                  <input type="radio" name="status" value="1" <?php if($value->rate_status==1){echo'checked';} ?>>Inactive</label>
              </div>
            </div>  
                </div>        
          </div>
          <div class="form-group row">
                                <div class="col-sm-offset-3 col-sm-6">
                                    <div class="ui buttons">
                                        <button type="reset" class="ui button">Reset</button>
                                        <div class="or"></div>
                                        <button class="ui positive button">Save</button>
                                    </div>
                                </div>
                            </div>

        </form>

                <?php } ?>         