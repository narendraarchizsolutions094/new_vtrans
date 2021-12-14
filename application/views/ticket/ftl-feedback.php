  <style type="text/css">
  /*TAG STYLE START*/
.tag {
  background: #eee;
  border-radius: 3px 0 0 3px;
  color: red;
  display: inline-block;
  height: 17px;
  line-height: 17px;
  padding: 0 10px 0 19px;
  position: relative;  
  text-decoration: none;
  -webkit-transition: color 0.2s;
  font-size: xx-small !important;  
}

.tag::before {
  background: #fff;
  border-radius: 10px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
  content: '';
  height: 6px;
  left: 10px;
  position: absolute;
  width: 6px;
  top: 6px;
}

.tag::after {
  background: #fff;
  border-bottom: 8px solid transparent;
  border-left: 10px solid #eee;
  border-top: 9px solid transparent;
  content: '';
  position: absolute;
  right: 0;
  top: 0;
}

.tag:hover {
  background-color: crimson;
  color: white;
}

.tag:hover::after {
   border-left-color: crimson; 
}
/*TAG STYLE END*/


.col-half-offset{
  margin-left:2.166667%;
}
.enq_form_filters{
  width: 0px;
}
#active_class{
  font-size: 12px;
  padding: 5px 19px;
}
.lead_stage_filter{
  padding: 6px;
  background-color: #e6e9ed;
  cursor: pointer;
}
.lead_stage_filter:active{  
  background-color: #20a8d8;  
}
.lead_stage_filter:hover{  
  background-color: #20a8d8;  
}
.border_bottom_active > label{
  cursor: pointer;
}
.nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
    color: white;
    background-color: #37a000;
}

.nav-pills > li > a {
    border-radius: 5px;
    padding: 10px;
    color: #000;
    font-weight: 600;
}

.nav-pills > li > a:hover {
    color: #000;
    background-color: transparent;
}
              .dropdown-header {
    padding: 8px 20px;
    background: #e4e7ea;
    border-bottom: 1px solid #c8ced3;
}

.dropdown-header {
    display: block;
    padding: 0 1.5rem;
    margin-bottom: 0;
   
    color: #73818f;
    white-space: nowrap;
}
input[name=top_filter]{
  visibility: hidden;
}

input[name=lead_stages]{
  visibility: hidden;
}

.dropdown_css {
  left:auto!important;
  right: 0 ! important;
}
.dropdown_css a,.dropdown_css a h4{
  width:100%;text-align:left! important;
  border-bottom: 1px solid #c8ced3! important;
}

.border_bottom{
  border-bottom:2px solid #E4E5E6;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
  padding-bottom:14px;
}  
.border_bottom:hover{
  border-bottom:2px solid #20A8D8;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
}

.border_bottom_active{
  border-bottom:2px solid #20A8D8;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
} 

.filter-dropdown-menu li{
  padding-left: 6px;
}

.filter-dropdown-menu li{
  padding-left: 6px;
}
@media screen and (max-width: 900px) {
  #active_class{
    display: none;
  }
}

.short_dashboard button{
  margin:4px;
}
.hide_countings{
   display:none !important;    
 }
  </style>

    <div class="row">
			<div class="col-md-12"> 
					<div class="panel-heading no-print" style ="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
						<div class="row">
							<div class="btn-group"> 
				                <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>
				            </div>
							<div class="btn-group">
							<form id="search_form" method="POST">
                                <input type="text"  class="form-control" id="msearch" name="msearch" style="padding-top:0px;">
                                <button type = "button" onclick="master_search_form();" class="btn btn-danger pull-right" 
								style="margin-right: -50px;margin-top: -34px;"><i class="fa fa-search" aria-hidden="true"></i></button>								
							</form>
				            </div>
							<div class="col-md-4 col-sm-4 col-xs-4 pull-right" >  
					          <div style="float: right;">   


		<div class="btn-group dropdown-filter">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filter by <span class="caret"></span>
              </button>              
              <ul class="filter-dropdown-menu dropdown-menu">   
                    <li>
                      <label>
                      <input type="checkbox" value="date" id="datecheckbox" name="filter_checkbox"> Date </label>
                    </li>    

                    <li>
                      <label>
                      <input type="checkbox" value="created_by" id="createdbycheckbox" name="filter_checkbox"> Created By</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="assign_to" id="assigncheckbox" name="filter_checkbox"> Assign To</label>
                    </li>
                   
                   <li>
                      <label>
                      <input type="checkbox" value="assign_by" id="assign_bycheckbox" name="filter_checkbox"> Assign By</label>
                    </li>
					
					<li>
                      <label>
                      <input type="checkbox" value="problam" id="problamcheckbox" name="filter_checkbox"> Customer Feedback</label>
                    </li>
					
					<li>
                      <label>
                      <input type="checkbox" value="region" id="regioncheckbox" name="filter_checkbox"> Sales region</label>
                    </li>
					
					<li>
                      <label>
                      <input type="checkbox" value="area" id="areacheckbox" name="filter_checkbox"> Sales area</label>
                    </li>
					
					<li>
                      <label>
                      <input type="checkbox" value="branch" id="branchcheckbox" name="filter_checkbox"> Sales Branch</label>
                    </li>
					
                    <li>
                      <label>
                      <input type="checkbox" value="status" id="statuscheckbox" name="filter_checkbox"> Status</label>
                    </li> 
                    <li class="text-center">
                      <a href="javascript:void(0)" class="btn btn-sm btn-primary " id='save_advance_filters' title="Save Filters Settings"><i class="fa fa-save"></i></a>
                    </li>                   
                </ul>                
            </div>
					            <div class="btn-group" role="group" aria-label="Button group">
					              <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Actions">
					                <i class="fa fa-sliders"></i>
					              </a>  
					            <div class="dropdown-menu dropdown_css" style="max-height: 400px;overflow: auto; left: unset; right: 0!important;">
                      <?php if(user_access(313)) { ?>
                            <a class="btn" data-toggle="modal" data-target="#AssignSelected" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #ccc; width: 100%; text-align: left"><?php echo display('assign_selected'); ?></a>                                        
                      <?php } 
                      if(user_access(312))
                      {                      
                      ?>
                            <a class="btn" data-toggle="modal" data-target="#DeleteSelected" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #ccc; width: 100%; text-align: left"><?php echo display('delete'); ?></a>
                      <?php
                      }
                      ?>
                            <a class="btn" data-toggle="modal" data-target="#table-col-conf" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #ccc; width: 100%; text-align: left"><?php echo display('table_config'); ?></a>                         
                            <a href="<?=base_url();?>ticket/upload_feedback" class="btn" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #ccc; width: 100%; text-align: left"><?php echo 'Import FTL FeedBack'; ?></a>
                          
					            </div>                                         
					          </div>  
					        </div>       
					      </div>
						</div>
					</div>


          <div style="float:right;">
            <a class='btn btn-xs  btn-primary' href='javascript:void(0)' id='show_quick_counts' title='Show Quick Dashboard'><i class='fa fa-bar-chart'></i></a>
          </div>
					<div class="row">
						<div class="">
							<div class="panel-body">
							<!-- Filter Panel Start -->

<form id="feedback_filter">
	<div class="row" id="filter_pannel">
        <div class="col-lg-12">
          
            <div class="panel panel-default">
               
                      <div class="form-row">
                       
                        <div class="form-group col-md-3" id="fromdatefilter">
                          <label for="from-date"><?php echo display("from_date"); ?></label>
                          <input   class="form-control form-date" id="from-date" name="from_created" style="padding-top:0px;" value="<?=$filterData['from_created']=='' || $filterData['from_created']=='0000-00-00'?'':$filterData['from_created'] ?>">
                        </div>
                        <div class="form-group col-md-3" id="todatefilter">
                          <label for="to-date"><?php echo display("to_date"); ?></label>
                          <input   class="form-control form-date" id="to-date" name="to_created" style="padding-top:0px;" value="<?=$filterData['to_created']==''||$filterData['to_created']=='0000-00-00'?'':$filterData['to_created']?>">
                        </div>

                     </div>
                    <!-- <div class="form-row">                       -->
                        
                         <div class="form-group col-md-3" id="createdbyfilter">
                          <label for="">Created By</label>
                         <select name="createdby" class="form-control" id="createdby_reset"> 
                          <option value="">Select</option>
                         <?php 
                          if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>" <?php if($createdbylist->pk_i_admin_id==$filterData['createdby']) {echo 'selected';}?> <?php if(!empty(set_value('createdby'))){if (in_array($product->sb_id,set_value('createdby'))) {echo 'selected';}}?> ><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?>                               
                              </option>
                              <?php }}?>    
                         </select>                       
                        </div>
                         <div class="form-group col-md-3" id="assignfilter">
                          <label for="">Assign To</label>  
                         <select name="assign" class="form-control" id="assign_reset"> 
                          <option value="">Select</option>
                         <?php 
                              if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>" <?php if($createdbylist->pk_i_admin_id==$filterData['assign']) {echo 'selected';}?> <?php if(!empty(set_value('assign'))){if (in_array($product->sb_id,set_value('assign'))) {echo 'selected';}}?>><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?></option>
                              <?php }}?>    
                         </select>                          
                        </div>

                         <div class="form-group col-md-3" id="assign_byfilter">
                          <label for="">Assign By</label>  
                         <select name="assign_by" class="form-control" id="assign_by_reset"> 
                          <option value="">Select</option>
                         <?php 
                              if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>" <?php if($createdbylist->pk_i_admin_id==$filterData['assign_by']) {echo 'selected';}?>  <?php if(!empty(set_value('assign'))){if (in_array($product->sb_id,set_value('assign'))) {echo 'selected';}}?>><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?></option>
                              <?php }}?>    
                         </select>                          
                        </div>

                    <div class="form-group col-md-3" id="problamfilter">
                    <label for="">Customer Feedback</label>  
                    <select name="cust_problam" class="form-control" id="cust_problam_reset"> 
                          <option value="">Select</option>
                         <?php 
                              if (!empty($customer_feed)) {
                              foreach ($customer_feed as $feed) {?>
                              <option value="<?=$feed->id; ?>" <?php if($feed->id==$filterData['cust_problam']) {echo 'selected';}?>><?=$feed->feedback;?> </option>
                              <?php 
                              }
                            }
                        ?>     
                    </select> 
                    </div>

                    <div class="form-group col-md-3" id="regionfilter">
                    <label for="">Sales Region</label>  
                    <select name="sales_region" class="form-control" onchange="find_area();" id="sales_region_reset"> 
                          <option value="">Select</option>
				    <?php
                        if (!empty($region_lists)) {
                        foreach ($region_lists as $key => $value) { ?>
                        <option value="<?= $value->region_id;?>" <?php if($value->region_id==$filterData['sales_region']) {echo 'selected';}?>><?= $value->name;?></option>
                    <?php
                    }
                    }
                    ?>                              
                    </select> 
                    </div>
 
                     <div class="form-group col-md-3" id="areafilter">
                    <label for="">Sales Area</label>  
                    <select name="sales_area" class="form-control" id="filtered_area" onchange="find_branch();"> 
                          <option value="">Select</option>
                    <?php  if (!empty($area_lists)) {
                            foreach ($area_lists as $key => $value) { ?>
                            <option value="<?= $value->area_id;?>" <?php if($value->area_id == $filterData['sales_area']){ echo "selected";} ?>><?= $value->area_name;?></option>
                    <?php
                    }
                    } ?>    
                    </select> 
                    </div>

                    <div class="form-group col-md-3" id="branchfilter">
                    <label for="">Sales Branch</label>  
                    <select name="sales_branch" class="form-control" id="filtered_branch"> 
                          <option value="">Select</option>
                    <?php  if (!empty($branch_lists)) {
                            foreach ($branch_lists as $key => $value) { ?>
                            <option value="<?= $value->branch_id;?>" <?php if($value->branch_id == $filterData['sales_branch']){ echo "selected";} ?>><?= $value->branch_name;?></option>
                    <?php
                    }
                    } ?>       
                    </select> 
                    </div>					
                   
                   <div class="form-group col-md-3" id="statusfilter">
                    <label for="">FTL Status</label>  
                    <select name="ticket_status" class="form-control" id="ticket_status_reset"> 
                          <option value="">Select</option>
                         <?php 
                              if (!empty($ticket_status)) {
                              foreach ($ticket_status as $sub_stage_list) {?>
                              <option value="<?=$sub_stage_list->id?>" <?php if($sub_stage_list->id==$filterData['ticket_status']) {echo 'selected';}?>><?=$sub_stage_list->status_name?> </option>
                              <?php 
                              }
                            }
                        ?>     
                    </select> 
                    </div>
                    <div class="form-group col-md-3">
					<button class="btn btn-warning btn-sm" id="reset_filterbutton" type="button" onclick="feedback_reset_filter();" style="margin: 25px 5px;">Reset</button>
					<button class="btn btn-primary btn-sm" id="find_filterbutton" type="button" style="margin: 25px 5px;">Filter</button>
                    <button class="btn btn-success btn-sm" id="save_filterbutton" type="button" onclick="feedback_save_filter();" style="margin: 25px 5px;">Save</button>        
                        </div>           
                    <!-- </div> -->
          
            </div>
        </div>
    </div>  
    

<style>
  .wd-14{
    width:13.2%;
    display:inline-block;
  }
</style>
<div class="row text-center short_dashboard hide_countings" id='active_class' style="padding-bottom: 15px">    
    <div class="wd-14">
      <div  class="col-12 border_bottom" >
          <p style="margin-top: 2vh;font-weight:bold;">
            <input id='short_dashboard_ticket_created' value="created" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="short_dashboard_ticket_created">&nbsp;&nbsp;<?php echo display('short_dashboard_ticket_created'); ?></label>            
            <span class="badge badge-pill badge-warning created_self"><i class="fa fa-spinner fa-spin"></i></span>
          </p>
      </div>
    </div>

    <div class="wd-14">
      <div  class="col-12 border_bottom" >
          <p style="margin-top: 2vh;font-weight:bold;">
            <input id='short_dashboard_ticket_assigned' value="assigned" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="short_dashboard_ticket_assigned">&nbsp;&nbsp;<?php echo display('short_dashboard_ticket_assigned'); ?></label>            
            <span class="badge badge-pill badge-info assigned"><i class="fa fa-spinner fa-spin"></i></span>
          </p>
      </div>
    </div>

    <div class="wd-14">
      <div  class="col-12 border_bottom" >
          <p style="margin-top: 2vh;font-weight:bold;">
            <input id='short_dashboard_ticket_updated' value="updated" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="short_dashboard_ticket_updated">&nbsp;&nbsp;<?php echo display('short_dashboard_ticket_updated'); ?></label>            
            <span class="badge badge-pill badge-success updated"><i class="fa fa-spinner fa-spin"></i></span>
          </p>
      </div>
    </div>

    <div class="wd-14">
      <div  class="col-12 border_bottom" >
          <p style="margin-top: 2vh;font-weight:bold;">
            <input id='short_dashboard_ticket_followup' value="total_activity" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="short_dashboard_ticket_followup">&nbsp;&nbsp;<?php echo display('short_dashboard_ticket_followup'); ?></label>            
            <span class="badge badge-pill badge-info total_activity"><i class="fa fa-spinner fa-spin"></i></span>
          </p>
      </div>
    </div>

    <div class="wd-14">
      <div  class="col-12 border_bottom" >
          <p style="margin-top: 2vh;font-weight:bold;">
            <input id='short_dashboard_ticket_closed' value="closed" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="short_dashboard_ticket_closed">&nbsp;&nbsp;<?php echo display('short_dashboard_ticket_closed'); ?></label>            
            <span class="badge badge-pill badge-primary closed"><i class="fa fa-spinner fa-spin"></i></span>
          </p>
      </div>
    </div>

    <div class="wd-14">
      <div  class="col-12 border_bottom" >
          <p style="margin-top: 2vh;font-weight:bold;">
            <input id='short_dashboard_ticket_pending' value="pending" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="short_dashboard_ticket_pending">&nbsp;&nbsp;<?php echo display('short_dashboard_ticket_pending'); ?></label>            
            <span class="badge badge-pill badge-danger pending"><i class="fa fa-spinner fa-spin"></i></span>
          </p>
      </div>
    </div>

    <div class="wd-14">
      <div  class="col-12 border_bottom" >
          <p style="margin-top: 2vh;font-weight:bold;">
            <input id='short_dashboard_ticket_all' value="total" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="short_dashboard_ticket_all">&nbsp;&nbsp;<?php echo display('short_dashboard_ticket_all'); ?></label>            
            <span class="badge badge-pill badge-warrning total"><i class="fa fa-spinner fa-spin"></i></span>
          </p>
      </div>
    </div>   
</div>
</form>
 <?php 
        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["feedback_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["feedback_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["feedback_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["feedback_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       
 ?>

							<!-- Filter Panel End -->
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-12">
									<table id="feedback_table" class=" table table-striped table-bordered" style="width:100%;">
										<thead>
										<th class="noExport sorting_disabled">
                    <input type='checkbox' class="checked_all1" value="check all" >
                     </th>
											<th>S.No.</th>
                      <?=($showall or in_array(1,$acolarr))?'<th>FTL Feedback</th>':''?>
                      <?=($showall or in_array(2,$acolarr))?'<th>Name</th>':''?>
                      <?=($showall or in_array(3,$acolarr))?'<th>Phone</th>':''?>
                      <?=($showall or in_array(4,$acolarr))?'<th>Email</th>':''?>                      
					  <?=($showall or in_array(5,$acolarr))?'<th>GC Date</th>':''?>
					  <?=($showall or in_array(6,$acolarr))?'<th>Bkg Branch</th>':''?>
					  <?=($showall or in_array(7,$acolarr))?'<th>Bkg Region</th>':''?>
					  <?=($showall or in_array(8,$acolarr))?'<th>Delivery Branch</th>':''?>
					  <?=($showall or in_array(9,$acolarr))?'<th>Dly Type</th>':''?>
                      <?=($showall or in_array(10,$acolarr))?'<th>Pay Mode</th>':''?>
                      <?=($showall or in_array(11,$acolarr))?'<th>Charged Weight</th>':''?>
                      <?=($showall or in_array(12,$acolarr))?'<th>No Of Articles</th>':''?>
					  <?=($showall or in_array(13,$acolarr))?'<th>Actual Weight</th>':''?>
                      <?=($showall or in_array(14,$acolarr))?'<th>Consignor Name</th>':''?>
                      <?=($showall or in_array(15,$acolarr))?'<th>Consignor Tel No</th>':''?>
                      <?=($showall or in_array(16,$acolarr))?'<th>Consignor Mobile No</th>':''?>
                      <?=($showall or in_array(17,$acolarr))?'<th>Consignee Name</th>':''?>
                      <?=($showall or in_array(18,$acolarr))?'<th>Consignee Tel No</th>':''?>
					  <?=($showall or in_array(19,$acolarr))?'<th>Consignee Mobile No</th>':''?>
					  <?=($showall or in_array(20,$acolarr))?'<th>Current Status</th>':''?>
					  <?=($showall or in_array(21,$acolarr))?'<th>Vehicle No</th>':''?>
					  <?=($showall or in_array(22,$acolarr))?'<th>Added By</th>':''?>
					  <!--<?=($showall or in_array(23,$acolarr))?'<th>How Are The Services?</th>':''?>
					  <?=($showall or in_array(24,$acolarr))?'<th>Is This First FTL</th>':''?>
                      <?=($showall or in_array(25,$acolarr))?'<th>Other Locations Where FTL Service Is Required</th>':''?>
                      <?=($showall or in_array(26,$acolarr))?'<th>If Using Any Other Transporter</th>':''?>
                      <?=($showall or in_array(27,$acolarr))?'<th>If Yes Please Specify Name</th>':''?>
                      <?=($showall or in_array(28,$acolarr))?'<th>Remarks On Improvement Required</th>':''?>
                      <?=($showall or in_array(29,$acolarr))?'<th>Next FTL Booking Expected</th>':''?>
					  <?=($showall or in_array(30,$acolarr))?'<th>Customer Feedback</th>':''?>
					  <?=($showall or in_array(31,$acolarr))?'<th>Action Taken</th>':''?>
					  <?=($showall or in_array(32,$acolarr))?'<th>Response By</th>':''?>
					  <?=($showall or in_array(33,$acolarr))?'<th>Response Remark</th>':''?>-->

										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							</form>
								<!--<?php echo form_close(); ?>-->
						</div>
					</div>
				</div>
			</div>
		</div>


 <div id="AssignSelected" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FTL Feedback Assignment</h4>
      </div>
      <div class="modal-body">
      
                <div class="row">
                  
            
            <div class="form-group col-md-12">  
            <label>Select Employee</label> 
            <div id="imgBack"></div>
            <select class="form-control"  name="assign_employee" id="emply">                    
            <?php foreach ($user_list as $user) { 
                            
                          if (!empty($user->user_permissions)) {
                            $module=explode(',',$user->user_permissions);
                          }                           
                            
                            ?>
                            <option value="<?php echo $user->pk_i_admin_id; ?>">
                              <?=$user->s_display_name ?>&nbsp;<?=$user->last_name; ?>                                
                            </option>
                            <?php 
                          //}
                        } ?>                                                      
            </select> 
            </div>
            
          <input type="hidden" value="" class="enquiry_id_input" >
          
            <div class="form-group col-sm-12">        
            <button class="btn btn-success" type="button" onclick="assign_feedback();">Assign</button>        
            </div>
    
                </div>          
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div id="DeleteSelected" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FTL Feedback</h4>
      </div>
      <div class="modal-body">
          <i class="fa fa-question-circle" style="font-size:100px;"></i><br><h1>Are you sure, you want to permanently delete selected record?</h1>
        </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="delete_recorde()">Ok</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
    </div>

  </div>
</div>

<script type="text/javascript">
   $("#show_quick_counts").on('click',function(){
        $(this).hide();
        $("#active_class").removeClass('hide_countings');
        $("select[name='ticket_status']").change();
        update_short_dashboard();
      });

</script>

<script>
	$(document).on("click", ".delete-ticket", function(e){
		e.preventDefault();
		var r = confirm("Are you sure to delete");
		if (r == true) {
		} else {
		  return false;
		}
		$.ajax({
			url  	: $(this).attr("href"),
			type 	: "post",
			data 	: {content : $(this).data("ticket")},
			success : function(resp){
					var jresp = JSON.parse(resp);
					if(jresp.status == "success"){
						location.reload();
					}else{						
					}
			}
		});
	});
</script>
<script>
function reset_input(){
$('input:checkbox').removeAttr('checked');
}

$('.checked_all1').on('change', function() {     
    $('.checkbox1').prop('checked', $(this).prop("checked"));    
}); 
</script>
<script>
function assign_feedback(){
  if($('.checkbox1:checked').size() > 1000){
    // alert('You can not assign more that 1000 enquiry at once');
    Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'You can not assign more that 1000 enquiry at once',
  showConfirmButton: false,
  timer: 1000
}); 
  }else{
      var p_url = '<?php echo base_url();?>ticket/assign_feedback';
      var re_url = '<?php echo base_url();?>ticket/ftlfeedback'; 
		var epid = $("#emply").val();	  

    var x = $(".checkbox1:checked");
    var Arr = new Array();
    $(x).each(function(k,v){
      Arr.push($(this).val());
    });

  $.ajax({
    type: 'POST',
    url: p_url,
    data: {tickets:Arr,epid:epid},  //+ "&epid="+epid+"",
    beforeSend: function(){
                 $("#imgBack").html('uploading').show();
    },
    success:function(data){
         alert(data);
         //document.getElementById('testdata').innerHTML =data;
          window.location.href=re_url;
    }});
  } 
}
</script>

<script type='text/javascript'>
$(window).load(function(){
  //stage_counter();
$(".border_bottom").click(function() {
    $('.border_bottom_active').removeClass('border_bottom_active');
    $(this).addClass("border_bottom_active");
});

});  
</script>

<script>
  
$(document).ready(function(){
   $("#save_advance_filters").on('click',function(e){
    e.preventDefault();
    var arr = Array();  
    $("input[name='filter_checkbox']:checked").each(function(){
      arr.push($(this).val());
    });        
    setCookie('feedback_filter_setting',arr,365);    
    Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Your custom filters saved successfully.',
  showConfirmButton: false,
  timer: 1000
});  
    // alert('Your custom filters saved successfully.');
  }) 



var enq_filters  = getCookie('feedback_filter_setting');
if (enq_filters=='') {
    $('#filter_pannel').hide();
    $('#save_filterbutton').hide();

}else{
  $('#filter_pannel').show();
  $('#save_filterbutton').show();

}
if (!enq_filters.includes('date')) {
  $('#fromdatefilter').hide();
  $('#todatefilter').hide();
}else{
  $("input[value='date']").prop('checked', true);
}


if (!enq_filters.includes('created_by')) {
  $('#createdbyfilter').hide();
}else{
  $("input[value='created_by']").prop('checked', true);
}

if (!enq_filters.includes('assign_to')) {
  $('#assignfilter').hide();
}else{
  $("input[value='assign_to']").prop('checked', true);
}

if (!enq_filters.includes('assign_by')) {
  $('#assign_byfilter').hide();
}else{
  $("input[value='assign_by']").prop('checked', true);
}

if (!enq_filters.includes('problam')) {
  $('#problamfilter').hide();

}else{
  $("input[value='problam']").prop('checked', true);
}

if (!enq_filters.includes('region')) {
  $('#regionfilter').hide();

}else{
  $("input[value='region']").prop('checked', true);
}

if (!enq_filters.includes('area')) {
  $('#areafilter').hide();

}else{
  $("input[value='area']").prop('checked', true);
}

if (!enq_filters.includes('branch')) {
  $('#branchfilter').hide();

}else{
  $("input[value='branch']").prop('checked', true);
}

if (!enq_filters.includes('status')) {
  $('#statusfilter').hide();

}else{
  $("input[value='status']").prop('checked', true);
}

$('#buttongroup').hide();

 $('input[name="filter_checkbox"]').click(function(){  
  if($('#datecheckbox').is(":checked")||$('#createdbycheckbox').is(":checked")||$('#assigncheckbox').is(":checked")||
  $('#assign_bycheckbox').is(":checked")||$('#problamcheckbox').is(":checked")||$('#statuscheckbox').is(":checked")||
  $('#regioncheckbox').is(":checked")||$('#areacheckbox').is(":checked")||$('#branchcheckbox').is(":checked")){
    $('#save_filterbutton').show();
    $('#filter_pannel').show();

  }else{
    $('#save_filterbutton').hide();
    $('#filter_pannel').hide();


  }
 
        if($('#datecheckbox').is(":checked")){
         $('#fromdatefilter').show();
         $('#todatefilter').show();
         $('#save_filterbutton').show();
         $("#buttongroup").show();
        }
        else{
           $('#fromdatefilter').hide();
           $('#todatefilter').hide();
           $("#buttongroup").hide();
        }
      
        if($('#createdbycheckbox').is(":checked")){
         $('#save_filterbutton').show();

          $('#createdbyfilter').show();
        }
        else{
          $('#createdbyfilter').hide();
        }
		
        if($('#assigncheckbox').is(":checked")){
         $('#save_filterbutton').show();

          $('#assignfilter').show();
        }
        else{
          $('#assignfilter').hide();
        }
		
        if($('#assign_bycheckbox').is(":checked")){
         $('#save_filterbutton').show();

          $('#assign_byfilter').show();
        }
        else{
          $('#assign_byfilter').hide();
        }
		
		if($('#problamcheckbox').is(":checked")){
         $('#save_filterbutton').show();
          $('#problamfilter').show();
        }
        else{
          $('#problamfilter').hide();
        }
		
		if($('#regioncheckbox').is(":checked")){
         $('#save_filterbutton').show();
          $('#regionfilter').show();
        }
        else{
          $('#regionfilter').hide();
        }
		
		if($('#areacheckbox').is(":checked")){
         $('#save_filterbutton').show();
          $('#areafilter').show();
        }
        else{
          $('#areafilter').hide();
        }
		
		if($('#branchcheckbox').is(":checked")){
         $('#save_filterbutton').show();
          $('#branchfilter').show();
        }
        else{
          $('#branchfilter').hide();
        }

        if($('#statuscheckbox').is(":checked")){
         $('#save_filterbutton').show();
          $('#statusfilter').show();
        }
        else{
          $('#statusfilter').hide();
        }
    });
})

$(document).ready(function(){
 
  var count=0;
  var checkboxes = document.getElementsByName('product_filter[]');
  var id = [];
  // loop over them all
  for (var i=0; i<checkboxes.length; i++) {     
     if (checkboxes[i].checked) {
        id.push(checkboxes[i].value);
        count++;
     }
  }
  if(count>1){
   $("#enq-create").hide();
  } 
  else{
    $("#enq-create").show();
  }  
});

$(document).ready(function() {
  var table = $('#feedback_table').DataTable({         
          "processing": true,
          "scrollX": true,
          "scrollY": 520,
          "pagingType": "simple",
          "bInfo": false,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "columnDefs": [{ "orderable": false, "targets": 0 }],
          "order": [[ 1, "desc" ]],
          "ajax": {
              "url": "<?=base_url().'Ticket/feedback_load_data'?>",
              "type": "POST",
              //"dataType":"html",
              //success:function(q){ //alert(q); //document.write(q);},
              error:function(u,v,w)
              {
                alert(w);
              }
              },
              <?php if(user_access(317)) { ?>
        // "lengthMenu": [[30, 60, 90, -1], [30, 60, 90, "All"]], 
       // dom: "<'row text-center'<'col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>tp", 
        dom: "<'row text-center'<'text-left col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>rtip",
		buttons: [  
            {extend: 'copy', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'csv', title: 'list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'excel', title: 'list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn', title: 'exportTitle',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'pdf', title: 'list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'print', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }} 
             ] ,  <?php  } ?>               
            drawCallback: function (settings) {   
              var api = this.api();
            var $table = $(api.table().node());  
              console.log(settings);               
              console.log(table);               
                var info = table.page.info();
                returned_rows = table.rows().count();
                if(returned_rows == 0 || returned_rows < info.length){
                  $('#ticket_table_next').addClass('disabled');
                }
                $('#ticket_table_previous').after('<li><a class="btn btn-secondary btn-sm" style="padding: 4px;line-height: 2;" href="javascript:void(0)">'+info.page+'</a></li>');
            }
         });

//CHANGE DUE TO RESET BUTTON
    /* $('#feedback_filter').change(function() {

        var form_data = $("#feedback_filter").serialize();       
       // alert(form_data);
        $.ajax({
        url: '<?=base_url()?>ticket/feedback_set_filters_session',
        type: 'post',
        data: form_data,
        success: function(responseData){
         // document.write(responseData);
          $('#feedback_table').DataTable().ajax.reload();
          //stage_counter(); 
          if(!$("#active_class").hasClass('hide_countings')){
           return update_short_dashboard(); 
          }
           }
        });
    }); */
//END
    $('#find_filterbutton').click(function() {

        var form_data = $("#feedback_filter").serialize();       
       // alert(form_data);
        $.ajax({
        url: '<?=base_url()?>ticket/feedback_set_filters_session',
        type: 'post',
        data: form_data,
        success: function(responseData){
         // document.write(responseData);
          $('#feedback_table').DataTable().ajax.reload();
          //stage_counter(); 
          if(!$("#active_class").hasClass('hide_countings')){
           return update_short_dashboard(); 
          }
           }
        });
    });
    if(!$("#active_class").hasClass('hide_countings')){
      update_short_dashboard(); 
    }
});

function update_short_dashboard()
{  
   $.ajax({
      url: "<?=base_url().'ticket/short_dashboard_feedback/'?>",
      type: 'get',
      dataType: 'json',
      success: function(responseData){
     //alert(responseData);
        $(".created_self").html(responseData.created);      
        $(".assigned").html(responseData.assigned);        
        $(".updated").html(responseData.updated);
        $(".total_activity").html(responseData.activity);
       $(".closed").html(responseData.closed);
       $(".pending").html(responseData.pending);
       $(".total").html(responseData.total);     
      },
      error:function(u,v,w)
      {
        alert(w);
      }
    });
}

function delete_recorde(){
  var x = $(".checkbox1:checked");
  if(x.length > 0)
  {   
      var Arr = new Array();
      $(x).each(function(k,v){
        Arr.push($(this).val());
      });
      $.ajax({
        url:'<?=base_url().'ticket/delete_feedback'?>',
        type:'post',
        data:{ticket_list:Arr},
        success:function(q)
        {
          $("#DeleteSelected").find('button[data-dismiss=modal]').click();
           $('#feedback_table').DataTable().ajax.reload();
        }
      });
  }else{
    alert("0 Record Selected.");
  }
 
}

</script>

<!--------------------TABLE COLOUMN CONFIG----------------------------------------------->
<div id="table-col-conf" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="width: 96%;">
 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Table Column Configuration</h4>
      </div>
      <div class="modal-body">         
           <div class="row">
             <div class="col-md-3">
                <label class=""><input type="checkbox" id="selectall" onclick="select_all()">&nbsp;Select All</label>
             </div>
           </div>
        <hr>
          <div class="row">
          <div class = "col-md-4">             
          <label class=""><input type="checkbox" class="choose-col" id="choose-col" value = "1" <?php echo ($showall == true or in_array(1, $acolarr)) ? "checked" : ""; ?>> FTL Feedback</label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "2"  <?php echo ($showall == true or in_array(2, $acolarr)) ? "checked" : ""; ?>> Name</label> 
          </div>
          <div class = "col-md-4">  
          <label  class=""><input type="checkbox" class="choose-col"  value = "3"  <?php echo ($showall == true or in_array(3, $acolarr)) ? "checked" : ""; ?>> Phone</label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "4"  <?php echo ($showall == true or in_array(4, $acolarr)) ? "checked" : ""; ?>> Email </label>
          </div>
          
          
          
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "5"  <?php echo ($showall == true or in_array(5, $acolarr)) ? "checked" : ""; ?>> GC Date </label>
              </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "6"  <?php echo ($showall == true or in_array(6, $acolarr)) ? "checked" : ""; ?>> Bkg Branch </label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "7"  <?php echo ($showall == true or in_array(7, $acolarr)) ? "checked" : ""; ?>> Bkg Region </label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "8"  <?php echo ($showall == true or in_array(8, $acolarr)) ? "checked" : ""; ?>> Delivery Branch </label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "9"  <?php echo ($showall == true or in_array(9, $acolarr)) ? "checked" : ""; ?>> Dly Type </label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "10"  <?php echo ($showall == true or in_array(10, $acolarr)) ? "checked" : ""; ?>> Pay Mode </label>  &nbsp;
          </div>


          <div class = "col-md-4">  
          
              <label class=""><input type="checkbox" class="choose-col"  value = "11"  <?php echo ($showall == true or in_array(11, $acolarr)) ? "checked" : ""; ?>> Charged Weight </label> &nbsp;
          </div>
          <div class = "col-md-4">  
          
              <label class=""><input type="checkbox" class="choose-col"  value = "12"  <?php echo ($showall == true or in_array(12, $acolarr)) ? "checked" : ""; ?>> No Of Articles </label> &nbsp;
          </div>

        <div class = "col-md-4">  
          
           <label  class=""><input type="checkbox" class="choose-col"  value = "13"  <?php echo ($showall == true or in_array(13, $acolarr)) ? "checked" : ""; ?>> Actual Weight </label>  &nbsp; 
         </div>

         <div class = "col-md-4">  
          
               <label class=""><input type="checkbox" class="choose-col"  value = "14"  <?php echo ($showall == true or in_array(14, $acolarr)) ? "checked" : ""; ?>> Consignor Name </label>  &nbsp; 
           </div>
          
         <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "15"  <?php echo ($showall == true or in_array(15, $acolarr)) ? "checked" : ""; ?>> Consignor Tel No </label>  &nbsp;
          </div>

          <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "16"  <?php echo ($showall == true or in_array(16, $acolarr)) ? "checked" : ""; ?>> Consignor Mobile No </label>  &nbsp;
          </div>

           <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "17"  <?php echo ($showall == true or in_array(17, $acolarr)) ? "checked" : ""; ?>> Consignee Name </label>  &nbsp;
          </div>

          <?php
          if($this->session->companey_id==65)
          {
          ?>
           <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "18"  <?php echo ($showall == true or in_array(18, $acolarr)) ? "checked" : ""; ?>> Consignee Tel No </label>  &nbsp;
          </div>

          <?php
        }
        ?>
         <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "19"  <?php echo ($showall == true or in_array(19, $acolarr)) ? "checked" : ""; ?>> Consignee Mobile No </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "20"  <?php echo ($showall == true or in_array(20, $acolarr)) ? "checked" : ""; ?>> Current Status </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "21"  <?php echo ($showall == true or in_array(21, $acolarr)) ? "checked" : ""; ?>> Vehicle No </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "22"  <?php echo ($showall == true or in_array(22, $acolarr)) ? "checked" : ""; ?>> Added By </label>  &nbsp;
          </div>
		  
		  <!--<div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "23"  <?php echo ($showall == true or in_array(23, $acolarr)) ? "checked" : ""; ?>> How Are The Services? </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "24"  <?php echo ($showall == true or in_array(24, $acolarr)) ? "checked" : ""; ?>> Is This First FTL </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "25"  <?php echo ($showall == true or in_array(25, $acolarr)) ? "checked" : ""; ?>> Other Locations Where FTL Service Is Required </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "26"  <?php echo ($showall == true or in_array(26, $acolarr)) ? "checked" : ""; ?>> If Using Any Other Transporter </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "27"  <?php echo ($showall == true or in_array(27, $acolarr)) ? "checked" : ""; ?>> If Yes Please Specify Name </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "28"  <?php echo ($showall == true or in_array(28, $acolarr)) ? "checked" : ""; ?>> Remarks On Improvement Required </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "29"  <?php echo ($showall == true or in_array(29, $acolarr)) ? "checked" : ""; ?>> Next FTL Booking Expected </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "30"  <?php echo ($showall == true or in_array(30, $acolarr)) ? "checked" : ""; ?>> Customer Feedback </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "31"  <?php echo ($showall == true or in_array(31, $acolarr)) ? "checked" : ""; ?>> Action Taken </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "32"  <?php echo ($showall == true or in_array(32, $acolarr)) ? "checked" : ""; ?>> Response By </label>  &nbsp;
          </div>
		  
		  <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "33"  <?php echo ($showall == true or in_array(33, $acolarr)) ? "checked" : ""; ?>> Response Remark </label>  &nbsp;
          </div>-->
                
              <div class="col-12" style="padding: 0px;">
                <div class="row">              
                  <div class="col-12" style="text-align:center;">                                                
                               
                  </div>
                </div>                                   
              </div> 
                  
         
      </div>
      <div class="modal-footer">
        <button class="btn btn-success set-col-table" type="button">Save</button> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
  function select_all(){

var select_all = document.getElementById("selectall"); //select all checkbox
var checkboxes = document.getElementsByClassName("choose-col"); //checkbox items

//select all checkboxes
select_all.addEventListener("change", function(e){
  for (i = 0; i < checkboxes.length; i++) { 
    checkboxes[i].checked = select_all.checked;
  }
});


for (var i = 0; i < checkboxes.length; i++) {
  checkboxes[i].addEventListener('change', function(e){ //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){
      select_all.checked = false;
    }
    //check "select all" if all checkbox items are checked
    if(document.querySelectorAll('.choose-col:checked').length == checkboxes.length){
      select_all.checked = true;
    }
  });
}

}

</script>

<script type="text/javascript">

  $(document).on("click", ".set-col-table", function(e){
    
    e.preventDefault();
    if($(".choose-col:checked").length == 0 && $(".dchoose-col:checked").length == 0 ){
      
      return false;
    }
    var chkval = "";
    $(".choose-col:checked").each(function(){
      
      chkval += $(this).val()+",";
    });
    var dchkval = "";
    $(".dchoose-col:checked").each(function(){
      
      dchkval += $(this).val()+",";
    });
    
    document.cookie = "feedback_allowcols="+chkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
    document.cookie = "feedback_dallowcols="+dchkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
   // alert("C");
    location.reload();    
  });

  function feedback_save_filter(){
var form_data = $("#feedback_filter").serialize();       
// alert(form_data);
$.ajax({
url: '<?=base_url()?>ticket/feedback_save_filter/4',
type: 'post',
data: form_data,
success: function(responseData){
  Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'filted data saved',
  showConfirmButton: false,
  timer: 500
});


}
});
  }
  
function feedback_reset_filter(){
$('input[name=from_created').val('');
$('input[name=to_created').val('');
$('#createdby_reset').val(null).trigger("change");
$('#assign_reset').val(null).trigger("change");
$('#assign_by_reset').val(null).trigger("change");
$('#cust_problam_reset').val(null).trigger("change");
$('#sales_region_reset').val(null).trigger("change");
$('#filtered_area').val(null).trigger("change");
$('#filtered_branch').val(null).trigger("change");
$('#ticket_status_reset').val(null).trigger("change");

var form_data = $("#feedback_filter").serialize();       

$.ajax({
url: '<?=base_url()?>ticket/feedback_save_filter/4',
type: 'post',
data: form_data,
success: function(responseData){
  Swal.fire({
  position: 'top-end',
  icon: 'warning',
  title: 'filted data Reset',
  showConfirmButton: false,
  timer: 500
});
}
});
$('#find_filterbutton').click();
  }

jQuery(function($){ //on document.ready
        $('.form-date').datepicker({ dateFormat: 'yy-mm-dd' });
      });
</script>

<script>
function master_search_form(){
    var url =  '<?php echo base_url();?>ticket/find_ftldetails';
      $.ajax({
         type: "POST",
         url: url,
         data: $('#search_form').serialize(),		 // serializes the form's elements.
         success: function(data)
         {			 
Swal.fire({
  width: 600,
  icon: 'success',
  title: data,
  showConfirmButton: true,
});
         }
       });
  };
</script>
<script>
function find_area() { 

            var reg_id = $("select[name='sales_region']").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>user/select_area_by_region',
            data: {region:reg_id},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';;
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].area_id)+'">'+(obj[i].area_name)+'</option>';
                }
                
                $("#filtered_area").html(html);
                
            }           
            });
}

 function find_branch() { 

            var reg_id = $("select[name='sales_region']").val();
			var area_id = $("select[name='sales_area']").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>user/select_branch_by_arearegion',
            data: {region:reg_id,area:area_id},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';;
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].branch_id)+'">'+(obj[i].branch_name)+'</option>';
                }
                
                $("#filtered_branch").html(html);
                
            }           
            });
}
</script>

<!--   Table Config -->

<!-- jquery-ui js -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script> 
<!-- DataTables JavaScript -->
<script src="<?php echo base_url("assets/datatables/js/dataTables.min.js") ?>"></script>  
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>