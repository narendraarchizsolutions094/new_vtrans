<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>

<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

<div class="row">
    <div class="col-md-12">        
        <div class="panel-body">
            <form action="<?=base_url().'attendance/myteam'?>" method="post">
            <div class="row ">
                <div class="col-md-1"></div>
                <div class="col-md-3 ">
                    <label>Filter By Date<i class="text-danger">*</i></label>                    
                    <?php
                    if (set_value('att_date_from')) {
                        $from =   set_value('att_date_from');                     
                        $to =   set_value('att_date_to');                     
                    }else{
                        $from =  date('Y-m-d');
                        $to =  date('Y-m-d');
                    }                                        
                    ?>
                    <input type="date" name="att_date_from" class="form-control" style="width: 80%;padding-top:0px;" value="<?=$from?>" required>
                </div>
                <!--<div class="col-md-3 ">
                    <label>To Date<i class="text-danger">*</i></label>                    
                    <input type="date" name="att_date_to" class="form-control" style="width: 80%;padding-top:0px;" value="<?=$to?>" required>
                </div>-->
                <div class="col-md-3 ">
                  <label for="inputPassword4"><?php echo display("employee"); ?><i class="text-danger">*</i></label>
                  <select data-placeholder="Begin typing a name to filter..." multiple class="form-control chosen-select" name="employee[]" id="employee" required>
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