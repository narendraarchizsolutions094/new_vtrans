<form action="" method="get">
<br>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-2">
        <label>From Date</label>
        <input type="date" name="from_date" class="form-control">
    </div>
    <div class="col-md-2">
        <label>To Date</label>
        <input type="date" name="to_date" class="form-control">      
    </div>
    <div class="col-md-2">
        <label>Employee</label>
        
        <select name="employee" class="form-control">
        <?php            
        if(!empty($user_list)){
            foreach($user_list as $key=>$value){
                ?>
                <option value="<?=$value->pk_i_admin_id?>">
                <?=$value->s_display_name.' '.$value->last_name?>
                </option>
                <?php
            }
        }
        ?>
        </select>
    </div>
    <div class="col-md-2">
    <br>    
    <button type="submit" class='btn btn-primary btn-sm'>Filter</button>
    </div>
</div>
<br>
<br>
</form>
<div class="row">
    <div class="col-md-4">
        <iframe src="<?=$urls['deal_status']?>" width="100%"  height="440px"  title="Iframe Example"></iframe>
    </div>
    <div class="col-md-4">
        <iframe src="<?=$urls['booking_type']?>" width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
    <div class="col-md-4">
        <iframe src="<?=$urls['product_feed']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
    <!-- <div class="col-md-4">
        <iframe src="<?=$urls['approaval_status']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div> -->    
    <div class="col-md-4">
        <iframe src="<?=$urls['country_wise']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
    <div class="col-md-4">
        <iframe src="<?=$urls['region_wise']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
    <div class="col-md-4">
        <iframe src="<?=$urls['branch_wise']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
    <div class="col-md-4">
        <iframe src="<?=$urls['area_wise']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
    <div class="col-md-4">
        <iframe src="<?=$urls['waight_wise']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
    <div class="col-md-4">
        <iframe src="<?=$urls['freight_wise']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
</div>