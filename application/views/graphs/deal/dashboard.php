<form action="" method="get">
<br>
<div class="row">
    
    <div class="col-md-1">
    </div>
    
    <div class="col-md-2">
        <label>From Date</label>
        <input type="date" name="from_date" class="form-control" value= <?php if(!empty($_GET['from_date'])){ echo $_GET['from_date']; } ?>>
    </div>
    
    <div class="col-md-2">
        <label>To Date</label>
        <input type="date" name="to_date" class="form-control" value= <?php if(!empty($_GET['to_date'])){ echo $_GET['to_date']; } ?>>      
    </div>

    <?php
    $this->db->where('type',1);
    $region_arr = $this->db->get('sales_region')->result_array();
    ?>
    <div class="col-lg-2">
        <div class="form-group">
            <label>Region</label>                                
            <select name="region" class="form-control">
                <option value="" >--Select--</option>
                <?php
                if(!empty($region_arr)){
                    foreach($region_arr as $key=>$value){
                        ?>                                            
                        <option value="<?=$value['region_id']?>" <?php if(!empty($_GET['region'])){ if($_GET['region']==$value['region_id']){echo'selected';}} ?> ><?=$value['name']?></option>";
                        <?php
                    }
                }
                ?>
            </select>
        </div>
    </div>


    <div class="col-md-2">
        <label>Employee</label>        
        <select name="employee" class="form-control">
            <option value="0"> --- Select --- </option>
            <?php            
            if(!empty($user_list)){
                foreach($user_list as $key=>$value){
                    ?>
                    <option value="<?=$value->pk_i_admin_id?>" <?php if(!empty($_GET['employee']) && $_GET['employee']==$value->pk_i_admin_id){ echo 'selected'; } ?>>
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
    <a href="<?=base_url('deal_dashboard/dashboard')?>" class='btn btn-default btn-sm'>Reset</a>
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
    <div class="col-md-12">
        <iframe src="<?=$urls['deal_month_wise']?>"  width="100%"  height="440px" title="Iframe Example"></iframe>
    </div>
</div>

<script>
    $("select[name='region']").on('change', function(){
        var region = $(this).val();                
        $.ajax({
            url:'<?=base_url('dashboard/get_user_by_region')?>',
            type:'post',
            data:{region_id:region},
            success:function(q){
                $("select[name='employee']").html(q);
            }
        });
    });
</script>