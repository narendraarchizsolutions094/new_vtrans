<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/cylinder.js"></script>
<script src="https://code.highcharts.com/modules/funnel3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
.card-graph{
    min-height:250px;
    max-height:400px;
    border:1px solid;    
    margin:2px;
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}
@media (min-width: 992px){
    .card-graph {
        width:32%;
    }
}
.hide_graph{
   display:none !important;    
 }
</style>
<!------ Filter Div ---------->
<div class="row" id="filter_pannel">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading no-print">
                <div class="btn-group">
                    <a class="btn btn-primary" href="<?php echo base_url("report/index") ?>"> <i class="fa fa-list"></i>
                        <?php echo display('reports_list') ?> </a>

                    <?php if(user_access(430)) { if(!empty($this->session->telephony_token)){  ?>
                    <a class="btn btn-success" href="<?php echo base_url("call_report/index") ?>"
                        style="margin-left: 5 px !important ;"> <i class="fa fa-list"></i>
                        <?php echo display('telephone_call_reports') ?> </a>
                    <?php } }?>
                    <?php if(user_access(123)) { ?>
                    <a class="btn btn-success" href="<?php echo base_url("report/ticket_report") ?>"
                        style="margin-left: 5 px !important ;"> <i class="fa fa-list"></i>
                        <?php echo 'Ticket Report' ?> </a>
                    <?php }?>
                    <?php if(user_access(1025)) { ?>
                    <a class="btn btn-primary" href="<?php echo base_url("client/user_wise_visit") ?>"
                        style="margin-left: 5 px !important ;"> <i class="fa fa-list"></i>
                        <?php echo 'User Wise Visit Report' ?> </a>
                    <?php }?>                    
                </div> 
            </div>
            <div class="panel-body">
                <div class="widget-title">
                    <h3><?=$title?></h3>
                </div>
                <hr>
                <form method="post" class="lead-form" id="filter_and_save_form"
                    action="<?php echo base_url('Report/view_details') ?>">
<input name="type" id="type" value="1" hidden>
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-3">
                            <label for="inputEmail4"><?php echo display("from_date"); ?></label>
                            <input  class="form-control form-dates" id="from-date"
                                value="<?php if (!empty(set_value('from_exp'))) {echo set_value('from_exp');}?>"
                                name="from_exp" style="padding-top:0px;" type="date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("to_date"); ?></label>
                            <input   class="form-control form-dates" id="to-date"
                                value="<?php if (!empty(set_value('to_exp'))) {echo set_value('to_exp');}?>"
                                name="to_exp" style="padding-top:0px;" type="date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputEmail4"><?php echo display("update_from_date"); ?></label>
                            <input  class="form-control form-dates" id="from-date"
                                value="<?php if (!empty(set_value('updated_from_exp'))) {echo set_value('updated_from_exp');}?>"
                                name="updated_from_exp" style="padding-top:0px;" type="date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("update_to_date"); ?></label>
                            <input  class="form-control form-dates" id="to-date"
                                value="<?php if (!empty(set_value('updated_to_exp'))) {echo set_value('updated_to_exp');}?>"
                                name="updated_to_exp" style="padding-top:0px;" type="date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("employee"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="employee[]" id="employee">


                                <?php foreach ($employee as $user) {?>
                                <option value="<?=$user->pk_i_admin_id?>"
                                    <?php if(!empty(set_value('employee'))){if (in_array($user->pk_i_admin_id,set_value('employee'))) {echo 'selected';}}?>>
                                    <?=$user->s_display_name . " " . $user->last_name;?> -
                                    <?=$user->s_user_email?$user->s_user_email:$user->s_phoneno;?></option>
                                <?php }?>
                            </select>
                            <input type="checkbox" name="hier_wise" value="1"
                                <?php if (!empty(set_value('hier_wise')) && set_value('hier_wise')==1) {echo 'checked';}?>>
                            Hierarchy wise
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("source"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="source[]" id="source">

                                <?php foreach ($sourse as $row) {?>
                                <option value="<?=$row->lsid?>"
                                    <?php if(!empty(set_value('source'))){if (in_array($row->lsid,set_value('source'))) {echo 'selected';}}?>>
                                    <?=$row->lead_name?></option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("subsource"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="subsource[]" id="subsource">

                                <?php foreach ($subsourse as $row) {?>
                                <option value="<?=$row->subsource_id?>"
                                    <?php if(!empty(set_value('subsource'))){ if (in_array($row->subsource_id,set_value('subsource'))) {echo 'selected';}}?>>
                                    <?=$row->subsource_name?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("datasource"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="datasource[]" id="datasource">

                                <?php foreach ($datasourse as $row) {?>
                                <option value="<?=$row->datasource_id?>"
                                    <?php if(!empty(set_value('datasource'))){if (in_array($row->datasource_id,set_value('datasource'))) {echo 'selected';}}?>>
                                    <?=$row->datasource_name?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4"><?php echo display("status"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="state[]" id="state"
                                >

                                <option value="1"
                                    <?php if(!empty(set_value('state'))){ if (in_array('1',set_value('state'))) {echo 'selected';}}?>>
                                    <?=display('enquiry')?></option>
                                <option value="2"
                                    <?php if(!empty(set_value('state'))){ if (in_array('2',set_value('state'))) {echo 'selected';}}?>>
                                    <?=display('lead')?></option>
                                <option value="3"
                                    <?php if(!empty(set_value('state'))){ if (in_array('3',set_value('state'))) {echo 'selected';}}?>>
                                    <?=display('client')?></option>
                                    <?php
        $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');

    if (!empty($enquiry_separation)) {
$enquiry_separation = json_decode($enquiry_separation, true);
    foreach ($enquiry_separation as $key => $value) {
            $ctitle = $enquiry_separation[$key]['title']; 
           
           ?>
           <option value="<?=$key?>" <?php if(!empty(set_value('state'))){ if (in_array($key,set_value('state'))) {echo 'selected';}}?>><?= $ctitle ?></option>
        <?php }
        } ?>         
        </select>
                        </div>
                        <div class="form-group col-md-3 lead_subsource_class">
                            <label for="lead_source">Disposition</label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="lead_source[]" id="lead_source">


                                <?php 
                            if (!empty($all_stage_lists)) {
                              foreach ($all_stage_lists as $stage) {?>
                                <option value="<?=$stage->stg_id?>"
                                    <?php if(!empty(set_value('lead_source'))){if (in_array($stage->stg_id,set_value('lead_source'))) {echo 'selected';}}?>>
                                    <?=$stage->lead_stage_name;?></option>
                                <?php }
                            }?>
                            </select>
                        </div>
                        <div class="form-group col-md-3 lead_subsource_class">
                            <label for="lead_source">Lead Description</label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="sub_disposition[]">
                                <?php 
                              if (!empty($all_sub_stage_lists)) {
                                foreach ($all_sub_stage_lists as $stage) { ?>
                                <option value="<?=$stage->id?>"
                                    <?php if(!empty(set_value('sub_disposition'))){if (in_array($stage->id,set_value('sub_disposition'))) {echo 'selected';}}?>>
                                    <?=$stage->description;?></option>
                                <?php }
                                }
                              ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row col-md-12">

                        <?php
                        $report_columns = 
                                        array(
                                              'S.No',
                                              'Name',
                                              'Phone',
                                              'Email',
                                              'Created By',
                                              'Assign To',
                                              'Gender',
                                              'Source',
                                              'Subsource',
                                              'Lead Description',
                                              'Status',
                                              'DOE',
                                              'Process',
                                              'Updated Date',
                                              'Disposition',
                                              'State',
                                              'City',
                                              'Product',
                                              'Enquiry Id',
											  'Client Name',
											  'Expected Closer Date',
											  'Conversion Probability',
											  'Designation',
											  'Address',
											  'Client Type',
											  'Type Of Load / Business',
											  'Industries',
											  'Department',
											  'Country Code',
											  'STD Code',
											  'Website',
											  'Pincode'
                                            );
                           // $this->session->set_userdata($report_columns);             
                        ?>
                        <div class="form-group col-md-12">

                            <label for="enq_product"><?php echo display("report_columns"); ?><label class="required"
                                    style="color:red">*</label></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple class="form-control chosen-select"
                                name="report_columns[]"  >

                                <option
                                    <?php if(!empty(set_value('report_columns'))){ if (in_array('S.No',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>S.No</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Name',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Name</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Phone',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Phone</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Email',set_value('report_columns'))) {echo 'selected';}}?>
                                    selected>Email</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Created By',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Created By</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Assign To',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Assign To</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Gender',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Gender</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Source',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Source</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Subsource',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Subsource</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Disposition',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Disposition</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Lead Description',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Lead Description</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Disposition Remark',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Disposition Remark</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Drop Reason',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Drop Reason</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Drop Comment',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Drop Comment</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Conversion Probability',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Conversion Probability</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Remark',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Remark</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Status',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Status</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('DOE',set_value('report_columns'))) {echo 'selected';}}?>>
                                    DOE</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Process',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Process</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Updated Date',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Updated Date</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('State',set_value('report_columns'))) {echo 'selected';}}?>>
                                    State</option>
                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('City',set_value('report_columns'))) {echo 'selected';}}?>>
                                    City</option>

                                    <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Sales Region',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Sales Region</option>
                                    <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Sales Area',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Sales Area</option>
                                    <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Sales Branch',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Sales Branch</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Company Name',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Company Name</option>

                                <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Product',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Product</option>
                                    <option
                                    <?php if(!empty(set_value('report_columns'))){if (in_array('Enquiry Id',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Enquiry Id</option>
									
								<option <?php if(!empty(set_value('report_columns'))){if (in_array('Client Name',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Client Name</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Expected Closer Date',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Expected Closer Date</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Conversion Probability',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Conversion Probability</option> 
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Designation',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Designation</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Address',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Address</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Client Type',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Client Type</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Type Of Load / Business',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Type Of Load / Business</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Industries',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Industries</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Department',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Department</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Country Code',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Country Code</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('STD Code',set_value('report_columns'))) {echo 'selected';}}?>>
                                    STD Code</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Website',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Website</option>
                                <option <?php if(!empty(set_value('report_columns'))){if (in_array('Pincode',set_value('report_columns'))) {echo 'selected';}}?>>
                                    Pincode</option>
                                    

                                <?php if(!empty($dfields)) { 
								foreach($dfields as $dind => $df){									
									?><option <?php if(!empty(set_value('report_columns'))){if (in_array(trim($df['input_label']),set_value('report_columns'))) {echo 'selected';}}?>>
                                    <?php if(!empty($df['rep_label'])){ echo $df['rep_label'];}else{ echo $df['input_label'];} ?>
                                </option><?php
								}	
							  }
							  ?>
                            </select>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-3">
                            <label>Dropped</label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="drop_status[]">

                                <option value="dropped"
                                    <?php if(!empty(set_value('drop_status'))){ if (in_array('dropped',set_value('drop_status'))) {echo 'selected';}}?>>
                                    Dropped</option>
                                <option value="active"
                                    <?php if(!empty(set_value('drop_status'))){ if (in_array('active',set_value('drop_status'))) {echo 'selected';}}?>>
                                    Active</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="enq_product"><?php echo display("proccess"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="enq_product[]" id="enq_product">

                                <?php 
                              if (!empty($process)) {
                              foreach ($process as $product) {?>
                                <option value="<?=$product->sb_id;?>"
                                    <?php if(!empty(set_value('enq_product'))){if (in_array($product->sb_id,set_value('enq_product'))) {echo 'selected';}}?>>
                                    <?=$product->product_name;?></option>
                                <?php }}?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="productlst"><?php echo display("product"); ?></label>
                            <select data-placeholder="Begin typing a name to filter..." multiple
                                class="form-control chosen-select" name="productlst[]" id="productlst">

                                <?php 
                              if (!empty($products)) {
                              foreach ($products as $product) {?>
                                <option value="<?=$product->id;?>"
                                    <?php if(!empty(set_value('productlst'))){if (in_array($product->id,set_value('productlst'))) {echo 'selected';}}?>>
                                    <?=$product->country_name;?></option>
                                <?php }}?>
                            </select>
                        </div>

                        
                        <div class='col-md-3' id="regionfilter" >
                            <div class="form-group">
                            <label>Region</label> 
                            <select class="form-control v_filter"  name="region">
                                    <option value="">Select</option>
                                    <?php
                                    if(!empty($region_list)){
                                    foreach($region_list as $key=>$value){
                                        ?>
                                        <option value="<?=$value->region_id?>" <?php if(!empty(set_value('region'))){if ($value->region_id==set_value('region')) {echo 'selected';}}?>><?=$value->name?></option>
                                        <?php
                                    }
                                    }
                                    ?>                
                            </select>
                            </div>
                        </div>

                        <div class='col-md-3' id="areafilter" >
                            <div class="form-group">
                            <label>Area</label>
                            <select class="form-control v_filter" name="area">
                                    <option value="">Select</option>
                                    <?php
                                    if(!empty($area_list)){
                                    foreach($area_list as $key=>$value){
                                        ?>
                                        <option value="<?=$value->area_id?>" <?php if(!empty(set_value('area'))){if ($value->area_id==set_value('area')) {echo 'selected';}}?>><?=$value->area_name?></option>
                                        <?php
                                    }
                                    }
                                    ?>
                            </select>
                            </div>
                        </div>

                        <div class='col-md-3' id="branchfilter" >
                            <div class="form-group">
                            <label>Branch</label>
                            <select class="form-control v_filter" name="branch">
                                    <option value="">Select</option>
                                    <?php
                                    if(!empty($branch_list)){
                                    foreach($branch_list as $key=>$value){
                                        ?>
                                        <option value="<?=$value->branch_id?>" <?php if(!empty(set_value('branch'))){if ($value->branch_id==set_value('branch')) {echo 'selected';}}?>><?=$value->branch_name?></option>
                                        <?php
                                    }
                                    }
                                    ?>
                            </select>
                            </div>
                        </div>

                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-4">
                            <label for="enq_product">Follow Up Report</label>
                            <input type="checkbox" name="all" value="all" <?php if(!empty(set_value('all'))){?> checked
                                <?php }?>>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success"
                                id="filter_report"><?php echo display("filter"); ?></button>
                            <button type="submit" class="btn btn-primary"
                                id="filter_and_save"><?php echo display("filter_and_save"); ?></button>
                            <input type=button class="btn btn-warning"
                                onClick="location.href='<?php echo base_url('report/view_details'); ?>'" value='Reset'>
                        </div>
                    </div>
                    <br>
                </form>
                <?php
                if(!empty($post_report_columns)){ ?>
                <div style="float:right;">
                    <a class='btn btn-xs  btn-primary' href='javascript:void(0)' id='show_analytics' title='Show report analytics'><i class='fa fa-bar-chart'></i></a>
                </div>
                <?php
                }
                ?>
                <div class='show_graphs hide_graph'>
                    <div class='row'>
                        
                        <div class='col-md-4 card-graph'>
                            <div id='source_chart' >
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='process_chart' >
                            </div>                
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='stage_chart' >
                            </div>                        
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4 card-graph'>
                            <div id='status_chart' >
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='user_chart' >
                            </div>
                        </div>
                        <div class='col-md-4 card-graph'>
                            <div id='product_chart' >
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-md-12 table-responsive" id="showResult">
                    <table id="example" class=" table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <?php
                                  if (!empty($post_report_columns)) {
                                    foreach ($post_report_columns as $value) { ?>
                                <th><?=ucfirst($value)?></th>
                                <?php
                                    }
                                  } 
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!---------------------------->
<script type="text/javascript">
    $("#show_analytics").on('click',function(){
        $(this).hide();
        $(".show_graphs").removeClass('hide_graph');
        show_report_graph();
    });
    function show_report_graph(){
        if(!$(".show_graphs").hasClass('hide_graph')){    
            generate_pie_graph('source_chart','Source Wise');
            generate_pie_graph('process_chart','Process Wise');
            generate_pie_graph('stage_chart','Sales Stage Wise');
            generate_pie_graph('user_chart','Employee Wise Assigned Data');    
            generate_pie_graph('product_chart','Product/Service Wise');
            funnel_chart('status_chart','Sales Pipeline Wise');
        }    
    }
    function generate_pie_graph(elm,title){
        var url = "<?=base_url().'report/report_analitics/'?>"+elm;
        $.ajax({
            url: url,
            type: 'POST',                    
            success: function(result) {      
                result = JSON.parse(result);

                Highcharts.chart(elm, {            
                    chart: {
                        type: 'pie',
                            options3d: {
                                enabled: true,
                                alpha: 45                        
                            },
                        margin: [0, 0, 0, 0],
                        spacingTop: 0,
                        spacingBottom: 0,
                        spacingLeft: 0,
                        spacingRight: 0
                    },
                    title: {
                            text: ''
                        },
                    exporting: {
                        buttons: {
                            contextButton: {
                                menuItems: ["viewFullscreen", "printChart", "downloadPNG"]
                            }
                        }
                    },
                    subtitle: {
                        text: title
                    },
                    plotOptions: {            
                        pie: {
                            //size:'40%',
                            // dataLabels: {
                            //     enabled: false
                            // },
                            innerSize: 50,
                            depth: 45
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Count',                
                        data: result
                    }]
                });
            }
        });
    }

function funnel_chart(elm,title){
    var url = "<?=base_url().'report/report_analitics_pipeline/'?>"+elm;
        $.ajax({
            url: url,
            type: 'POST',                    
            success: function(result) {      
                result = JSON.parse(result);
                Highcharts.chart(elm, {
                chart: {
                    type: 'funnel3d',
                    options3d: {
                        enabled: true,
                        alpha: 10,
                        depth: 50,
                        viewDistance: 50
                    },
                    margin: [0, 0, 0, 0],
                    spacingTop: 0,
                    spacingBottom: 0,
                    spacingLeft: 0,
                    spacingRight: 0
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: title
                },
                exporting: {
                    buttons: {
                        contextButton: {
                            menuItems: ["viewFullscreen", "printChart", "downloadPNG"]
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> ({point.y:,.0f})',
                        allowOverlap: false,
                        y: 10
                    },
                    neckWidth: '30%',
                    neckHeight: '25%',
                    width: '80%',
                    height: '80%'
                    }
                },
                series: [{
                    name: 'Count',
                    data: result
                }]
                });
            }
        });
}

$("#filter_and_save_form").on('submit', function(e) {
    //alert($("input[name='hier_wise']").is(":checked"));
    if ($("input[name='hier_wise']").is(":checked") && $("#employee").select2('data').length != 1) {
        alert("please select one employee for hierarchy wise report");
        e.preventDefault();
    }
});

$(document).ready(function() {
    //$("#selected-col").select2();
    $(".chosen-select").select2();
});

$(document).ready(function() {
    var d = new Date($.now());
    var report_name = 'Report_' + d.getDate() + "-" + (d.getMonth() + 1) + "-" + d.getFullYear() + " " + d
        .getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
    $('#example').DataTable({
        "processing": true,
        "scrollX": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 30, 50, 100, 500, 1000, -1],
            [10, 30, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "<?=base_url().'report/all_report_filterdata'?>",
            "type": "POST",
        },
        "columnDefs": [{
            "orderable": false,
            "targets": 0
        }],
        "order": [
            [1, "desc"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'copyHtml5',
                title: report_name
            },
            {
                extend: 'csvHtml5',
                title: report_name
            },
            {
                extend: 'excelHtml5',
                title: report_name
            }
        ]
    });




    $("#filter_and_save").on("click", function(e) {
        // alert( $("#filter_and_save_form").serialize());
        e.preventDefault();
        var title = window.prompt("Enter Report Name");
        if (title) {
            var url = "<?=base_url().'report/create_report'?>";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    'filters': $("#filter_and_save_form").serialize(),
                    'report_name': title,
                    'type': 1
                },
                success: function(result) {
                    result = JSON.parse(result);
                    if (result.status) {
                        $("#filter_and_save_form").submit();
                    } else {
                        alert(result.msg);
                    }
                }
            });
        } else {
            alert("Report not saved");
        }
    });   

});
jQuery(function($){ //on document.ready
        $('.form-dates').datepicker({ dateFormat: 'yy-mm-dd' });
      })
</script>