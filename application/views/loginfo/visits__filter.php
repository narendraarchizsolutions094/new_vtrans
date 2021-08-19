
<div class="row">
    <div class="col-md-12">        
        <div class="panel-body">
            <form action="<?=base_url().'attendance/myteam/'.$current_user?>" method="post">
            <div class="row ">
			<br>
                <div class="col-md-2">
                    <label>Filter By From Date<i class="text-danger">*</i></label>                    
                    <?php
                    if (set_value('att_date_from') || $from || $to) {
                        $from =   !empty($from) ? $from :set_value('att_date_from');                     
                        $to =   !empty($to) ? $to :set_value('att_date_to');                     
                    }else{
                        $from =  date('Y-m-d');
                        $to =  date('Y-m-d');
                    }                                                            
                    ?>
                    <input type="date" name="att_date_from" class="form-control" value="<?=$from?>" required>
                </div>
                <div class="col-md-2">
                    <label>To Date<i class="text-danger">*</i></label>                    
                    <?php
                    if (set_value('att_date_to') || $from || $to) {
                        $from =   !empty($from) ? $from :set_value('att_date_from');                     
                        $to =   !empty($to) ? $to :set_value('att_date_to');                     
                    }else{
                        $from =  date('Y-m-d');
                        $to =  date('Y-m-d');
                    }                        
                    //echo $to;                
                    ?>
                    <input type="date" name="att_date_to" class="form-control" value="<?=$to?>" required>
                </div>

                <!--<div class="col-md-3 ">
                    <label>To Date<i class="text-danger">*</i></label>                    
                    <input type="date" name="att_date_to" class="form-control" value="<?=$to?>" required>
                </div>-->
				<div class="col-md-2">
                  <label for="inputPassword4"><?php echo 'Designation'; ?><i class="text-danger">*</i></label>
                  <select class="form-control chosen-select" name="designation" id="designation">
				  <option value="">--Select Here--</option>
                       <?php foreach ($user_roles as $roles) {?>
                            <option value="<?=$roles->use_id;?>" <?php if(!empty(set_value('designation'))){if ($roles->use_id==set_value('designation')) {echo 'selected';}}?>><?=$roles->user_role;?></option>
                        <?php }?>
                  </select>
                </div>
				<div class="col-md-2">
                  <label for="inputPassword4"><?php echo 'Region'; ?><i class="text-danger">*</i></label>
                  <select class="form-control chosen-select" name="region" id="region">
				  <option value="">--Select Here--</option>
                       <?php foreach ($user_region as $region) {?>
                            <option value="<?=$region->region_id;?>" <?php if(!empty(set_value('region'))){if($region->region_id==set_value('region')) {echo 'selected';}}?>><?=$region->name;?></option>
                        <?php }?>
                  </select>
                </div>
                <div class="col-md-3 ">
                  <label for="inputPassword4"><?php echo display("employee"); ?><i class="text-danger">*</i></label>
                  <select data-placeholder="Begin typing a name to filter..." multiple class="form-control chosen-select" name="employee[]" id="employee">
                       <?php foreach ($employee as $user) {?>
                            <option value="<?=$user->pk_i_admin_id?>" <?php if(!empty(set_value('employee'))){if (in_array($user->pk_i_admin_id,set_value('employee'))) {echo 'selected';}}?>><?=$user->s_display_name . " " . $user->last_name?></option>
                        <?php }?>
                  </select>
                </div>
                <br>
                <input type="submit" name="submit" value="Filter" class="btn btn-primary">
            </div>
            </form>
        </div>
    </div>
</div>