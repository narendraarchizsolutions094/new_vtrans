<div class="row">
    <div class="panel panel-default pt-2">
        <div class="panel-heading no-print"
            style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
            <div class="row">
                <div class="col-md-12">
                    <a href="<?=base_url('ticket/index')?>" class="btn btn-success"> <i class="fa fa-list"></i> <?=display('ticket')?>
                        List
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-3"></div>
            <div class="col-md-6 panel-default panel-body" style="border:1px solid #f7f7f7">
                <?php echo form_open_multipart(base_url("ticket/upload"),array('id'=>'ticket-upload-form')); ?>
                    <div class="row">                        
                        <div class="form-group col-sm-12">
                            <label>Upload CSV File</label>
                            <input type="file" name="img_file" class="form-control" accept=".csv">
                        </div>
                    </div>                   
                    <div class="col-md-12">
                        <a class="" href="javascript:void(0)>" onclick="allcsv()">Download Sample</a>
                        <!-- <label> For Download sample Please Select Process <i class="text-danger"></i></label>
                        <select name="product_id" id="pid" onchange="allcsv()" class="form-control">
                            <option value="">Select</option>
                            <?php 
                            // if(!empty($process)){
                            //     foreach($process as $proc){?>
                            //     <option value="<?=$proc->sb_id ?>"><?=$proc->product_name ?></option>
                                 <?php 
                            //     } 
                            // }
                            ?>
                        </select> -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <br>
                            <button class="btn btn-success" type="submit" id="assign">Upload</button>
                            <img src='<?= base_url('assets/images/loader.gif'); ?>' width='60px' height='60px' id="loader"
                                style="display: none;">
                        </div>           
                    </div>         
                </div>
            </form>
        </div>
    </div>
</div>
<script>
 function allcsv(){
    var pd=199;	 
    window.location.href='<?php echo base_url();?>lead/createcsv/'+pd+'/'+2;
   }
</script>
