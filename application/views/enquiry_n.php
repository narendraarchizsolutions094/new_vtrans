<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> 

<script src="<?=base_url()?>/assets/summernote/summernote-bs4.min.js"></script>
<link href="<?=base_url()?>/assets/summernote/summernote-bs4.css" rel="stylesheet" />

<style>
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
}
.hide_countings{
   display:none !important;    
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
</style>

<form method="post" id="enq_filter" >
<div class="row">
 <div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">  
        <div class="col-md-4 col-sm-4 col-xs-4" > 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>        
          <?php if (user_access(60)==true OR user_access(70)==true OR user_access(80)==true) { ?> 
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" id="enq-create" href="<?php echo base_url()?>enquiry/create?status=1" title="<?php echo display('add_new_enquiry');?>"></a>         
          <?php } ?>          
        </div>
         <div class="col-md-4 col-sm-8 col-xs-8 pull-right" >  
          <div style="float: right;">     
      <?php if(!empty($this->session->telephony_token)){ ?>
              <div class="btn-group dropdown-filter">
                      <?php if($this->session->availability == 1) { ?>
                      <button class="btn btn-success" type="button" data-toggle="modal" data-target="#callbreak" >Available</button>
                    <?php } elseif($this->session->availability == 2) { ?>
                      <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#callbreak" > Not Available</button>
                    <?php } ?>
              </div>  
            <?php } ?> 
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
                      <input type="checkbox" value="emp" id="empcheckbox" name="filter_checkbox"> Name</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="source" id="sourcecheckbox" name="filter_checkbox"> Source</label>
                    </li>                
                    <li>
                      <label>
                      <input type="checkbox" value="subsource" id="subsourcecheckbox" name="filter_checkbox"> Sub Source</label>
                    </li>                
                   <li>
                      <label>
                      <input type="checkbox" value="email" id="emailcheckbox" name="filter_checkbox"> Email</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="phone" id="phonecheckbox" name="filter_checkbox"> Phone</label>
                    </li>
                    <li>
                      <label>
                      <input type="checkbox" value="address" id="addcheckbox" name="filter_checkbox"> Address</label>
                    </li>
                    <li>
                      <label>
                      <input type="checkbox" value="datasource" id="datasrccheckbox" name="filter_checkbox"> Datasource</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox"  value="company" id="companycheckbox" name="filter_checkbox"> Company Name</label>
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
                      <input type="checkbox" value="process" id="proccheckbox" name="filter_checkbox"> Process</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="product" id="prodcheckbox" name="filter_checkbox"> Product</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="state" id="statecheckbox" name="filter_checkbox"> State</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="city" id="citycheckbox" name="filter_checkbox"> City</label>
                    </li> 
                     <li>
                      <label>
                      <input type="checkbox" value="stage" id="stageheckbox" name="filter_checkbox"> Stage</label>
                    </li>
                    <li>
                      <label>
                      <input type="checkbox" value="probability" id="probabilitycheckbox" name="filter_checkbox"> Probability</label>
                    </li> 
                    <?php if(!empty($aging_rule)){ ?>
                    <li>
                      <label>
                      <input type="checkbox" value="aging_rule" id="agingRulecheckbox" name="filter_checkbox"> Aging Rule</label>
                    </li> 
                    <?php
                    }
                    ?>
					<li>
                      <label>
                      <input type="checkbox" value="sales_region" id="regioncheckbox" name="filter_checkbox"> Sales Region</label>
                    </li>
					<li>
                      <label>
                      <input type="checkbox" value="sales_area" id="areacheckbox" name="filter_checkbox"> Sales Area</label>
                    </li>
					<li>
                      <label>
                      <input type="checkbox" value="sales_branch" id="branchcheckbox" name="filter_checkbox"> Sales Branch</label>
                    </li>
					<li>
                      <label>
                      <input type="checkbox" value="client_type" id="ctypecheckbox" name="filter_checkbox"> Client Type</label>
                    </li>
					<li>
                      <label>
                      <input type="checkbox" value="business_load" id="loadcheckbox" name="filter_checkbox"> Load/Business</label>
                    </li>
					<li>
                      <label>
                      <input type="checkbox" value="industries" id="industriescheckbox" name="filter_checkbox"> Industries</label>
                    </li>
                    
                    <li>
                      <label>                    
                        <input type="checkbox" value="visit_wise" id="visit_wisecheckbox" name="filter_checkbox"> Visit Wise
                      </label>
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
            <div class="dropdown-menu dropdown_css" style="max-height: 400px;overflow: auto;">
              <?php
              if ($data_type == 1) { ?>
                <?php if (user_access(67)==true) { ?>                                
                <a class="btn" data-toggle="modal" data-target="#AssignSelected" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #fff;"><?php echo display('assign_selected'); ?></a>
                <?php }
                if (user_access(68)==true) {
                ?>
                <a class="btn" data-toggle="modal" data-target="#genLead" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #fff;"><?php echo display('move_to_lead'); ?> </a>
                <?php } ?>
                <a class="btn" data-toggle="modal" data-target="#dropEnquiry" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('drop_enquiry'); ?></a>  
                <?php 
                if (user_access(65)==true) {?>    
                <a class="btn"  data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('1','Send Whatssp');"  style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('send_whatsapp'); ?> </a>
                <?php }
                  if (user_access(64)==true) {
                  ?>

                <a class="btn " data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('2','Send Sms');" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('send_bulk_sms'); ?></a>               
              <?php
                  }
              }else if ($data_type == 2) { 
                 if (user_access(67)==true) { ?>
                  <a class="btn" data-toggle="modal" data-target="#AssignSelected" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #fff;"><?php echo display('assign_selected'); ?></a>
                  <?php }   if (user_access(77)==true) {  ?>
                  <!-- <a class="btn" data-toggle="modal" data-target="#genclient" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #fff;">Move To <?=display('Client')?> </a> -->
                  <?php } ?>

                  <a class="btn" data-toggle="modal" data-target="#dropEnquiry" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('drop_lead'); ?></a>
                  <?php  if (user_access(75)==true) {  ?>
                  <a class="btn"  data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('1','Send Whatssp');"  style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('send_whatsapp'); ?> </a>
                  <?php }    if (user_access(74)==true) {   ?>
                  <a class="btn " data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('2','Send Sms');" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('send_bulk_sms'); ?></a>
              <?php
                  }
              }else { ?>
                <?php if (user_access(67)==true) { ?>
                  <a class="btn" data-toggle="modal" data-target="#AssignSelected" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #fff;"><?php echo display('assign_selected'); ?></a>
                  <?php } if (user_access(64)==true) {  ?>
                  <a class="btn"  data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('1','Send Whatssp');"  style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('send_whatsapp'); ?> </a>
                  <?php }   if (user_access(65)==true) {   ?>
                  <a class="btn " data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('2','Send Sms');" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('send_bulk_sms'); ?></a>
              <?php
                  }
              }
              ?>
              
              <a class="btn " data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('3','Send Email');" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('send_bulk_email'); ?></a>               

              <?php if(user_access(221)) { if(!empty($this->session->telephony_token)){ ?>
                <a class="btn "  onclick="autoDial()" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('bulk_autodial'); ?></a> 
              <?php } } ?>
              <a class="btn" data-toggle="modal" data-target="#table-col-conf" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('table_config'); ?></a>      
              
              <?php if(user_access('A61')) { ?>
                <a class="btn" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;" href="<?=base_url().'lead/datasourcelist'?>"><?php echo display('datasource_management'); ?></a>      
            <?php
              }
?>           
              <a class="btn" data-toggle="modal" data-target="#deleteselected" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff; display: none;"><?php echo 'Delete Data'; ?></a>                         
            </div>                                         
          </div>  
        </div>       
      </div>
</div>

<?php
$stage_pipeline  = get_sys_parameter('stage_pipeline','COMPANY_SETTING');
if(!empty($stage_pipeline) && $stage_pipeline=='1')
{
?>
<div class="row">
  <div class="col-lg-12 " style="padding-top: 5px;">
    <div id="crumbs">
    <ul>
    <?php
      if(!empty($all_stage_lists))
      {
        foreach ($all_stage_lists as $stage) 
        { 
          echo '<li class="top_pill" data-stage-id="'.$stage->stg_id.'"><a>'.$stage->lead_stage_name.'</a></li>';
        }
                
      }
    ?>  

    </ul></div>
   <style type="text/css">   
#crumbs {
  text-align: center;
}

#crumbs ul {
  margin: 0;
  padding: 0; 
  list-style: none;
  display: inline-block;
  height: 40px!important;
  white-space: nowrap;
  overflow-x: auto;
  overflow-y: hidden;
  width: 100%;
}
#crumbs ul li {
  display: inline-block;
  white-space: nowrap;
}
#crumbs ul li a {
display: block;
    float: left;
    height: 30px;
    background: #F3F5FA;
    text-align: center;
    padding: 6px 10px 0 30px;
    position: relative;
    margin: 0 10px 0 0;
    font-size: 12px;
    text-decoration: none;
    color: #32373c;
    cursor: pointer;
}
#crumbs ul li a:after {
     content: "";
    border-top: 15px solid transparent;
    border-bottom: 15px solid transparent;
    border-left: 15px solid #F3F5FA;
    position: absolute;
    right: -15px;
    top: 0;
    z-index: 1;
}
#crumbs ul li a:before {
    content: "";
    border-top: 15px solid transparent;
    border-bottom: 15px solid transparent;
    border-left: 15px solid #fff;
    position: absolute;
    left: 0px;
    top: 0;
}

/*#crumbs ul li:first-child a {
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
}*/

/*#crumbs ul li:first-child a:before {
  display: none;
}
*/
/*#crumbs ul li:last-child a {
  padding-right: 40px;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}

#crumbs ul li:last-child a:after {
  display: none;
}*/

#crumbs ul li a:hover {
  background: #357DFD;
  border-left-color: #357DFD;
  color: #fff;
}

#crumbs ul li a:hover:after {
  border-left-color: #357DFD;
  color: #fff;
}
#crumbs ul li.top-active a:after
{
  border-left-color: #357DFD;
  color: #fff;
}
#crumbs ul li.top-active a{
  background: #357DFD;
  font-weight: 700;
  color: #fff;
}
   </style>
  
  </div>
</div>

<?php
}
?>
<style type="text/css">
  #filter_pannel .col-md-3
  {
    height: 63px; 
    font-size: 12px!important;
    margin: 0px;
  }
</style>
<!------ Filter Div ---------->
<div class="row" id="filter_pannel" style="margin-top: 15px;">
        <div class="col-lg-12">
            <div class="panel panel-default">
               
                      <div class="form-row">
                       
                        <div class="form-group col-md-3" id="fromdatefilter">
                          <label for="from-date"><?php echo display("from_date"); ?></label>
                          <input   class="form-control form-date" id="from-date" name="from_created" style="padding-top:0px;" value="<?=$filterData['from_created']=='' || $filterData['from_created']=='0000-00-00' ?'':$filterData['from_created'] ?>">
                        </div>
                        <div class="form-group col-md-3" id="todatefilter">
                          <label for="to-date"><?php echo display("to_date"); ?></label>
                          <input   class="form-control form-date" id="to-date" name="to_created" style="padding-top:0px;" value="<?=$filterData['to_created']=='' || $filterData['from_created']=='0000-00-00'?'':$filterData['from_created'] ?>">
                        </div> 
                         <div class="form-group col-md-3" id="sourcefilter">
                          <label for="source"><?php echo display("source"); ?></label>
                          <select class="form-control" name="source" id="source">
                              <option value="" style="display:">---Select Source---</option>
                               <?php foreach ($sourse as $row) {?>
                                
                                 <option value="<?=$row->lsid?>" <?php if($row->lsid==$filterData['source']) {echo 'selected';}?> <?php if(!empty(set_value('source'))){if (in_array($row->lsid,set_value('source'))) {echo 'selected';}}?> ><?=$row->lead_name?></option>
                              <?php }?>

                          </select>
                        </div>

                        <div class="form-group col-md-3" id="subsourcefilter">
                          <label for="subsource"><?php echo display("subsource"); ?></label>
                          <select class="form-control" name="subsource" id="subsource">
                              <option value="" style="display:">---Select Sub Source---</option>
                               <?php foreach ($subsource_list as $row) {?>                                
                                 <option value="<?=$row->subsource_id?>" <?php if(!empty(set_value('subsource'))){if (in_array($row->subsource_id,set_value('subsource'))) {echo 'selected';}}?> <?php if($row->subsource_id==$filterData['subsource']) {echo 'selected';}?>><?=$row->subsource_name?></option>
                              <?php }?>

                          </select>
                        </div>


                        <div class="form-group col-md-3" id="emailfilter">
                          <label for=""><?php echo display("email"); ?></label>
                          <input type="text" name="email" class="form-control" value="<?= $filterData['email'] ?>">
                        </div>
                      </div>
                     <div class="form-row">
                         <div class="form-group col-md-3" id="empfilter">
                          <label for=""><?php echo 'Name'; ?></label>
                          <input type="text" class="form-control chosen-select" name="employee" id="employee" value="<?= $filterData['employee'] ?>">
                             
                        </div>
                        <div class="form-group col-md-3" id="datasourcefilter">
                          <label for=""><?php echo display("datasource"); ?></label>
                          <select  class="form-control" name="datasource" id="datasource">
                              <option value="" style="display:">---Select Datasource---</option>
                               <?php foreach ($datasourse as $row) {?>
                                 <option value="<?=$row->datasource_id?>" <?php if(!empty(set_value('datasource_id'))){if (in_array($row->datasource,set_value('datasource_id'))) {echo 'selected';}}?> <?php if($row->datasource_id==$filterData['datasource']) {echo 'selected';}?>><?=$row->datasource_name?></option>
                              <?php }?>
                          </select>
                        </div>
                        <div class="form-group col-md-3" id="companyfilter">
                          <label for="">Company Name</label>
                          <input type="text" name="company" class="form-control" id="company" value="<?= $filterData['company'] ?>">
                        </div>
                        <div class="form-group col-md-3" id="proccessfilter">
                          <label for="enq_product"><?php echo display("proccess"); ?></label>
                          <select  class="form-control" name="enq_product" id="enq_product" >
                              <option value="">---Select process ---</option>                              
                              <?php 
                              if (!empty($products)) {
                              foreach ($products as $product) {?>
                              <option value="<?=$product->sb_id;?>" <?php if(!empty(set_value('enq_product'))){if (in_array($product->sb_id,set_value('enq_product'))) {echo 'selected';}}?> <?php if($product->sb_id==$filterData['enq_product']) {echo 'selected';}?>><?=$product->product_name;?></option>
                              <?php }}?>                              
                          </select>
                        </div>
                     </div>
                    <div class="form-row">                      
                        <div class="form-group col-md-3" id="phonefilter">
                          <label for="">Phone</label>
                         <input type="text" name="phone" class="form-control" value="<?= $filterData['phone'] ?>">                        
                        </div>
                         <div class="form-group col-md-3" id="createdbyfilter">
                          <label for="">Created By</label>
                         <select name="createdby" class="form-control"> 
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
                         <select name="assign" class="form-control"> 
                          <option value="">Select</option>
                         <?php 
                              if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>" <?php if($createdbylist->pk_i_admin_id==$filterData['assign']) {echo 'selected';}?> <?php if(!empty(set_value('assign'))){if (in_array($product->sb_id,set_value('assign'))) {echo 'selected';}}?>><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?></option>
                              <?php }}?>    
                         </select>                          
                        </div>
                         <div class="form-group col-md-3" id="addfilter">
                          <label for="">Address</label>
                         <input type="text" name="address" class="form-control" value="<?= $filterData['address'] ?>">                        
                        </div>
                    </div>

                    <div class="row">
                    <div class="form-group col-md-3" id="prodfilter">
                    <label for="">Products</label>  
                    <select name="prodcntry" class="form-control"> 
                          <option value="">Select</option>
                         <?php 
                              if (!empty($prodcntry_list)) {
                              foreach ($prodcntry_list as $prodcntrylist) {?>
                              <option value="<?=$prodcntrylist->id;?>"  <?php if($prodcntrylist->id==$filterData['prodcntry']) {echo 'selected';}?> <?php if(!empty(set_value('prodcntry'))){if (in_array($prodcntrylist->id,set_value('prodcntry'))) {echo 'selected';}}?>><?= $prodcntrylist->country_name ?></option>
                              <?php }}?>    
                    </select> 
                    </div> 
                    <div class="form-group col-md-3" id="statefilter">
                    <label for="">State</label>  
                    <select name="state" class="form-control" id="state"> 
                          <option value="">Select</option>
                         <?php 
                              if (!empty($state_list)) {
                              foreach ($state_list as $statelist) {?>
                              <option value="<?=$statelist->id;?>" <?php if($statelist->id==$filterData['state']) {echo 'selected';}?> <?php if(!empty(set_value('state'))){if (in_array($statelist->id,set_value('state'))) {echo 'selected';}}?>><?= $statelist->state ?></option>
                              <?php }}?>    
                    </select> 
                    </div> 
                    <div class="form-group col-md-3" id="cityfilter">
                    <label for="">City</label>  
                    <select name="city" class="form-control" id="city_name"> 
                      <option value="">Select</option>
                           <?php 
                              if (!empty($city_list)) {
                              foreach ($city_list as $citylist) {?>
                              <option value="<?=$citylist->id;?>" <?php if($citylist->id==$filterData['city']) {echo 'selected';}?> <?php if(!empty(set_value('city'))){if (in_array($citylist->id,set_value('city'))) {echo 'selected';}}?>><?= $citylist->city ?></option>
                              <?php }}?>   
                    </select> 
                    </div> 

                    <div class="form-group col-md-3" id="stagefilter">
                        <label for="">Stage</label> 
                        <select name="stage" class="form-control">
                          <option value="">Select</option>
                          <?php foreach ($all_stage_lists as $stage) {  ?>
                              <option value="<?= $stage->stg_id ?>"  <?php if($stage->stg_id==$filterData['city']) {echo 'selected';}?>><?php echo $stage->lead_stage_name; ?></option>
                              <?php } ?>
                        </select>
                      </div>

                      <div class="form-group col-md-3" id="probabilityfilter">
                        <label for="">Probability</label> 
                        <select name="probability" class="form-control">
                          <option value="">Select</option>
                          <?php 
                          if(!empty($lead_score)){
                            foreach ($lead_score as $prob) {  ?>
                              <option value="<?= $prob->sc_id ?>"  <?php if(!empty($filterData['probability']) &&$prob->sc_id==$filterData['probability']) {echo 'selected';}?>><?php echo $prob->score_name; ?></option>
                              <?php } 
                              }
                              ?>
                        </select>
                      </div>

                      <?php 
                      if(!empty($aging_rule)){ ?>
                      <div class="form-group col-md-3" id="agingRulefilter">
                        <label for="">Aging Rule</label> 
                        <select name="aging_rule" class="form-control">
                          <option value="">Select</option>
                          <?php
                            foreach ($aging_rule as $k=>$v) {  ?>
                              <option value="<?=$v['rule_sql']?>" <?php if(!empty($filterData['aging_rule']) && $v['id']==$filterData['aging_rule']) {echo 'selected';}?>><?php echo $v['title']; ?></option>
                              <?php }                             
                              ?>
                        </select>
                      </div>
                      <?php
                      }
                      ?>
                      
					  <div class="form-group col-md-3" id="regionfilter">
                        <label for="">Sales Region</label> 
                        <select name="sales_region" class="form-control">
                          <option value="">Select</option>
                          <?php
                            foreach ($region_lists as $k=>$v) {  ?>
                              <option value="<?=$v->region_id;?>" <?php if(!empty($filterData['sales_region']) && $v->region_id==$filterData['sales_region']) {echo 'selected';}?>><?php echo $v->name; ?></option>
                              <?php }                             
                              ?>
                        </select>
                      </div>
					  
					  <div class="form-group col-md-3" id="areafilter">
                        <label for="">Sales Area</label> 
                        <select name="sales_area" class="form-control">
                          <option value="">Select</option>
                          <?php
                            foreach ($area_lists as $k=>$v) {  ?>
                              <option value="<?=$v->area_id;?>" <?php if(!empty($filterData['sales_area']) && $v->area_id==$filterData['sales_area']) {echo 'selected';}?>><?php echo $v->area_name; ?></option>
                              <?php }                             
                              ?>
                        </select>
                      </div>
					  
					  <div class="form-group col-md-3" id="branchfilter">
                        <label for="">Sales Branch</label> 
                        <select name="sales_branch" class="form-control">
                          <option value="">Select</option>
                          <?php
                            foreach ($branch_lists as $k=>$v) {  ?>
                              <option value="<?=$v->branch_id;?>" <?php if(!empty($filterData['sales_branch']) && $v->branch_id==$filterData['sales_branch']) {echo 'selected';}?>><?php echo $v->branch_name; ?></option>
                              <?php }                             
                              ?>
                        </select>
                      </div>
					  
					  <div class="form-group col-md-3" id="ctypefilter">
 						<label for="">Client Type</label>
                        <select class="form-control" name="client_type">
                            <option value="">--Select Client Type--</option>
				            <option value="MSME" <?php if(!empty($filterData['client_type']) && $filterData['client_type']=='MSME') {echo 'selected';}?>>MSME</option>
                            <option value="Pvt. Ltd." <?php if(!empty($filterData['client_type']) && $filterData['client_type']=='Pvt. Ltd.'){ echo "selected";} ?>> Pvt. Ltd.</option>
                            <option value="Public Ltd" <?php if(!empty($filterData['client_type']) && $filterData['client_type']=='Public Ltd'){ echo "selected";} ?>> Public Ltd</option>
                            <option value="Partnership" <?php if(!empty($filterData['client_type']) && $filterData['client_type']=='Partnership'){ echo "selected";} ?>> Partnership</option>
                            <option value="Multinational" <?php if(!empty($filterData['client_type']) && $filterData['client_type']=='Multinational'){ echo "selected";} ?>> Multinational</option>
                            <option value="Proprietorship" <?php if(!empty($filterData['client_type']) && $filterData['client_type']=='Proprietorship'){ echo "selected";} ?>>  Proprietorship</option>
                        </select>
                      </div>
					  
					  <div class="form-group col-md-3" id="loadfilter">
 						<label for="">Load/Business</label>
                        <select class="form-control" name="business_load">
                            <option value="">--Select business_load--</option>
				            <option value="FTL" <?php if(!empty($filterData['business_load']) && $filterData['business_load']=='FTL') {echo 'selected';}?>>FTL</option>
                            <option value="LTL/Sundry" <?php if(!empty($filterData['business_load']) && $filterData['business_load']=='LTL/Sundry'){ echo "selected";} ?>> LTL/Sundry</option>
                        </select>
                      </div>
					  
					  <div class="form-group col-md-3" id="Industriesfilter">
 						<label for="">Industries</label>
                        <select class="form-control" name="industries">
                            <option value="">--Select Industries--</option>
				            <option value="FMCG" <?php if(!empty($filterData['industries']) && $filterData['industries']=='FMCG') {echo 'selected';}?>>FMCG</option>
                            <option value="Auto &amp; Auto Ancillaries" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Auto & Auto Ancillaries') {echo 'selected';}?>> Auto &amp; Auto Ancillaries</option>
                            <option value="Heavy Engineering" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Heavy Engineering') {echo 'selected';}?>> Heavy Engineering</option>
                            <option value="Retail" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Retail') {echo 'selected';}?>> Retail</option>
                            <option value="E-Commerce" <?php if(!empty($filterData['industries']) && $filterData['industries']=='E-Commerce') {echo 'selected';}?>> E-Commerce</option>
                            <option value="Telecom &amp; IT" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Telecom & IT') {echo 'selected';}?>> Telecom &amp; IT</option>
                            <option value="Clothing" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Clothing') {echo 'selected';}?>> Clothing</option>
                            <option value="Chemicals" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Chemicals') {echo 'selected';}?>> Chemicals</option>
                            <option value="Pharmaceuticals" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Pharmaceuticals') {echo 'selected';}?>> Pharmaceuticals</option>
                            <option value="Others" <?php if(!empty($filterData['industries']) && $filterData['industries']=='Others') {echo 'selected';}?>> Others</option>
                        </select>
                      </div>
                      <div class="form-group col-md-3" id="visit_wisefilter">
 						            <label for="">Visit Wise</label>
                        <select class="form-control" name="visit_wise">
                            <option value="">--Select --</option>
                            <option value="1"> Visited </option>
                            <option value="2"> Non Visited </option>
                        </select>
                      </div>

                      <div class="form-group col-md-3">
                    <button class="btn btn-success" id="save_filterbutton" type="button" onclick="ticket_save_filter();" style="margin: 20px;">Save</button>        
                        </div>  
                    </div>
          
            </div>
        </div>
    </div>   
</form>

<div style="float:right;">
  <a class='btn btn-xs  btn-primary' href='javascript:void(0)' id='show_quick_counts' title='Show Quick Dashboard'><i class='fa fa-bar-chart'></i></a>
</div>
<div class="row row text-center short_dashboard hide_countings" id="active_class">   

        <div class="wd-14" style="">
            <div  class="col-12 border_bottom border_bottom_active" >
                <p style="margin-top: 2vh;font-weight:bold;">
                  <input id='created_today_radio' value="created_today" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="created_today_radio">&nbsp;&nbsp;<?php echo display('created'); ?></label>
                  <span  style="float:right;" class="badge badge-pill badge-primary " id="today_created"><i class="fa fa-spinner fa-spin"></i></span>
                </p>
            </div>
        </div>
        <div class="wd-14" style="">
            <div  class="col-12 border_bottom" >
                <p style="margin-top: 2vh;font-weight:bold;">
                  <input id='assigned_radio' value="assigned" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="assigned_radio">&nbsp;&nbsp;<?php echo display('assigned'); ?></label>
                  <span  style="float:right;" class="badge badge-pill badge-primary " id="assigned"><i class="fa fa-spinner fa-spin"></i></span>
                </p>
            </div>
        </div>
        <div class="wd-14">
              <div class="col-12 border_bottom">
                  <p style="margin-top: 2vh;font-weight:bold;" >
                    <input type="radio" name="top_filter" value="updated_today" class="enq_form_filters" id="updated_today_radio"><i class="fa fa-pencil"></i><label for="updated_today_radio">&nbsp;&nbsp;<?php echo display('updated'); ?></label><span style="float:right;background:#ffc107" class="badge badge-pill badge-warning badge badge-dark " id="today_updated"><i class="fa fa-spinner fa-spin"></i></span>
                  </p>
              </div>
        </div>
            
        <div class="wd-14">
            <div  class="col-12 border_bottom" >
                  <p style="margin-top: 2vh;font-weight:bold;"  title="<?php echo display('active'); ?>"> 
                    <input type="radio" name="top_filter" value="active" checked="checked" class="enq_form_filters" id="active_radio"><i class="fa fa-file" ></i><label for="active_radio">&nbsp;&nbsp;<?php echo display('active'); ?></label><span style="float:right;" class="badge badge-pill badge-primary " id="active_all"><i class="fa fa-spinner fa-spin"></i></span>
                  </p>
              </div>
        </div>
                  
        <div class="wd-14">
              <div  class="col-12 border_bottom" >
                  <p style="margin-top: 2vh;font-weight:bold;"   title="<?php echo display('droped'); ?>">
                      <input type="radio" name="top_filter" value="droped" class="enq_form_filters" id="droped_radio">
                      <i class="fa fa-thumbs-down" ></i><label for="droped_radio">&nbsp;&nbsp;<?php echo display('droped'); ?></label><span style="float:right;background:#E5343D" class="badge badge-danger" id="active_drop"><i class="fa fa-spinner fa-spin"></i></span>                   
                  </p>
              </div>
        </div>
         <div class="wd-14 " style="">
            <div  class="col-12 border_bottom" >
                <p style="margin-top: 2vh;font-weight:bold;">
                  <input id='pending_radio' value="pending" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="pending_radio">&nbsp;&nbsp;<?php echo display('pending_enquiry'); ?></label>
                  <span  style="float:right;" class="badge badge-pill badge-primary " id="pending"><i class="fa fa-spinner fa-spin"></i></span>
                </p>
            </div>
        </div>
        <div class="wd-14">
              <div class="col-12 border_bottom" >

                  <p style="margin-top: 2vh;font-weight:bold;"  title="<?php echo display('total'); ?>">
                    <input type="radio" name="top_filter" value="all" class="enq_form_filters" id="total_active_radio">
                    <i class="fa fa-list"></i><label for="total_active_radio">&nbsp;&nbsp;<?php echo display('total'); ?></label><span style="float:right;background:#000" class="badge badge-pill badge-dark " id="total_active"><i class="fa fa-spinner fa-spin"></i></span>
                  </p>
              </div>
        </div>   
    </div>
</div>

<style type="text/css">
  .wd-14{
    width: 13.2%;
    display: inline-block;
  }

.short_dashboard button{
  margin:4px;
}
.short_dashboard
{
  margin-bottom: 15px;
}
</style>

<form class="form-inner" method="post" id="enquery_assing_from" >  
<div class="card-body">
      <?php 
        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       
      ?>
  <div class="row">
    <div class="col-md-12" >    
            <table id="enq_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
        <thead>
          <tr class="bg-info table_header">
              <th class="noExport">
                <input type='checkbox' class="checked_all1" value="check all" >
              </th>
                  <th>S.N</th>
             <?php if ($showall == true or in_array(1, $acolarr)) {  ?>
                  <th><?php echo display("source"); ?></th>
             <?php } ?>
              <?php if ($showall == true or in_array(16, $acolarr)) {  ?>
                  <th >Sub Source</th>
            <?php } ?>
              <?php if ($showall == true or in_array(2, $acolarr)) {  ?>
                  <th><?php echo display("company_name"); ?></th>
                   <?php } ?>
            <?php if ($showall == true or in_array(21, $acolarr)) {  ?>
                  <th><?php echo display("client_name"); ?></th>
                   <?php } ?>
              <?php if ($showall == true or in_array(3, $acolarr)) {  ?>
            <th>Name</th>
                   <?php } ?>
              <?php if ($showall == true or in_array(4, $acolarr)) {  ?>
            <th>Email </th>
                   <?php } ?>
              <?php if ($showall == true or in_array(5, $acolarr)) {  ?>
            <th>Phone <?=user_access(220)?' (Click to dial)':''?></th>
                   <?php } ?>
              <?php if ($showall == true or in_array(6, $acolarr)) {  ?>
            <th>Address</th>
                   <?php } ?>
              <?php if ($showall == true or in_array(7, $acolarr)) {  ?>
            <th>Process</th>
             <?php } ?>
              <?php if ($showall == true or in_array(8, $acolarr)) {  ?>
                  <th>Disposition</th>
             <?php } ?>                  
              <?php
              if ($this->session->companey_id == 29) {
                echo "<th>Referred By</th>";
              }
              ?>
            <?php if ($showall == true or in_array(10, $acolarr)) {  ?>
                  <th ><?php echo display("create_date"); ?></th>
          <?php } ?>
              <?php if ($showall == true or in_array(11, $acolarr)) {  ?>
                  <th ><?php echo display("created_by"); ?></th>
            <?php } ?>
             <?php if ($showall == true or in_array(12, $acolarr)) {  ?>
                  <th ><?php echo display("assign_to"); ?></th>
                <?php } ?>
             <?php if ($showall == true or in_array(13, $acolarr)) {  ?>
                  <th ><?php echo display("data_source"); ?></th>
            <?php } ?>
             <?php if ($showall == true or in_array(14, $acolarr)) {  ?>
                  <th >Product</th>
            <?php } ?>

            <?php if ($showall == true or in_array(17, $acolarr)) {  ?>
                  <th>EnquiryId</th>
             <?php } ?> 

             <?php if ($showall == true or in_array(18, $acolarr)) {  ?>
                  <th>Score</th>
             <?php } ?> 

               <?php if ($showall == true or in_array(19, $acolarr)) {  ?>
                  <th>Remark</th>
             <?php } ?> 
             <?php if ($showall == true or in_array(20, $acolarr)) {  ?>
                  <th>No Of Visit</th>
             <?php } ?> 
              
            <?php if($this->session->userdata('companey_id')==29) { ?>
            <?php if ($showall == true or in_array(15, $acolarr)) {  ?>
                  <th >Bank</th>
            <?php } }?>
            
             <?php if(!empty($dacolarr) and !empty($dfields)){
              foreach($dfields as $ind => $flds){                
                if(!empty(in_array($flds->input_id, $dacolarr ))){                  
                ?><th><?php echo $flds->input_label; ?></th><?php 
                }
              }
            } ?>
          </tr>
        </thead>
        <tbody>             
        </tbody>
      </table>
    </div>
  </div>
</div>


        
<div id="dropEnquiry" class="modal fade" role="dialog">
  <div class="modal-dialog">
 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Drop <?=display('lead')?></h4>
      </div>
      <div class="modal-body">
                        
                                       
           
              <div class="row">
                
                <div class="form-group col-sm-12">  
                <label>Drop Reason</label>                  
                <select class="form-control"  name="drop_status">                    
                <?php foreach ($drops as $drop) {   ?>
                <option value="<?php echo $drop->d_id; ?>"><?php echo $drop->drop_reason; ?></option>
                <?php } ?>                                             
                </select> 
                </div>
                
                <div class="form-group col-sm-12"> 
                  <label>Drop Reason*</label>
                  <input class="form-control" name="reason" type="text" required="">  
                </div> 
                
              </div>          
              <div class="col-12" style="padding: 0px;">
                <div class="row">              
                  <div class="col-12" style="text-align:center;">                                                
                    <button class="btn btn-success" type="button" onclick="drop_enquiry()">Save</button>            
                  </div>
                </div>                                   
              </div> 
                  
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div id="sendsms" class="modal fade " role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal-titlesms"></h4>
      </div>
      <div class="modal-body">
      <div>
          <div class="form-group col-sm-12">
                <label>Template</label>
                <select class="form-control" name="templates" required id="templates"   onchange="getMessage()">
               
                </select>
                </div>
                <div class="form-group col-sm-12"> 
                  
                  <label><?php echo display('subject') ?></label>
                  <input type="text" name="email_subject" class="form-control" id="email_subject">

                <label><?php echo display('message') ?></label>
                <textarea class="form-control" name="message_name"  rows="10" id="template_message"></textarea>  
                </div>
      </div>
      
       <div class="col-md-12">
                       <input type="hidden"  id="mesge_type" name="mesge_type">                                         
                    <button class="btn btn-success" onclick="send_sms()" type="button">Send</button>            
                 

              </div>
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="genLead" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter info and Move to <?=display('lead')?></h4>
      </div>
      <div class="modal-body">
        
          <div class="row">
            <div class="form-group col-sm-6">  
            <label>Expected Closer Date</label>                  
            <input class="form-control date2"  name="expected_date" type="date">                
            </div>
            
            <div class="form-group col-sm-6">
            <label class="col-form-label">Conversion Probability</label>
            
            <select class="form-control" id="LeadScore2" name="lead_score">                                              
            <?php foreach ($lead_score as $score) {  ?>
                <option value="<?= $score->sc_id?>"><?= $score->score_name?>&nbsp;<?= $score->probability?></option>
                <?php } ?>                       
            </select>
            
            </div>
            
          
            
            
            
            
            <div class="form-group col-sm-6">  
            <label>Add Comment</label>                  
            <input class="form-control" id="LastCommentGen" name="comment" type="text">                
            </div>
          
            <div class="form-group col-sm-12">        
            <button class="btn btn-success" type="button" onclick="moveto_lead();" >Move to <?=display('lead')?></button>        
            </div>
          
     
                    
                    
                </div>
    
        
          
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- 
<div id="genclient" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter info and Move to <?=display('client') ?></h4>
      </div>
      <div class="modal-body">        
          <div class="row">
            <div class="form-group col-sm-6">  
            <label>Expected Closer Date</label>                  
            <input class="form-control date2"  name="expected_date" type="text" readonly>                
            </div>            
            <div class="form-group col-sm-6">
              <label class="col-form-label">Conversion Probability</label>            
              <select class="form-control" id="LeadScore1" name="lead_score">
              <option></option>                                               
              <?php foreach ($lead_score as $score) {  ?>
                  <option value="<?= $score->sc_id?>"><?= $score->score_name?>&nbsp;<?= $score->probability?></option>
                  <?php } ?>                       
              </select>
            </div>            
            <div class="form-group col-sm-6">  
            <label>Add Comment</label>                  
            <input class="form-control" id="LastCommentGen" name="comment" type="text">                
            </div>
          </div>          
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" type="button" onclick="moveto_client();" >Move to <?=display('client')?></button> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->



 <div id="AssignSelected" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=display('enquiry')?> Assignment</h4>
      </div>
      <div class="modal-body">
      
                <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label" for="dept_name"><?=display('department')?></label> 									
                <select class="form-control" name="dept_name" id="dept_name">
					<option value=''>---Select Department----</option>
                        <?php  if (!empty($dept_lists)) {
                        foreach ($dept_lists as $key => $value) { ?>
                    <option value="<?= $value->id;?>" <?php if($value->id == $this->session->dept_name){ echo "selected";} ?>><?= $value->dept_name;?></option>
                        <?php
                            }
                            } ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label class="control-label" for="sale_resions"><?=display('sales_resion')?></label> 									
                <select class="form-control" name="sale_region" onchange="find_area();">
					<option value="">---Select Region---</option>
                    <?php
                        if (!empty($region_lists)) {
                        foreach ($region_lists as $key => $value) { ?>
                    <option value="<?= $value->region_id;?>" <?php if($value->region_id == $this->session->sales_region){ echo "selected";} ?>><?= $value->name;?></option>
                    <?php
                        }
                        }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label class="control-label" for="sale_area"><?=display('sales_area')?></label> 									
                <select class="form-control" name="sale_area" id="filtered_area" onchange="find_branch();">
                    <?php  if (!empty($area_lists)) {
                    foreach ($area_lists as $key => $value) { ?>
                <option value="<?= $value->area_id;?>" <?php if($value->area_id == $this->session->sales_area){ echo "selected";} ?>><?= $value->area_name;?></option>
                    <?php
                    }
                    } ?>
                </select>
            </div>
								
			<div class="form-group col-md-6">
                <label class="control-label" for="sale_branch"><?=display('sales_branch')?></label> 									
                <select class="form-control" name="sale_branch" id="filtered_branch" onchange="find_employee();">
                    <?php  if (!empty($branch_lists)) {
                    foreach ($branch_lists as $key => $value) { ?>
                <option value="<?= $value->branch_id;?>" <?php if($value->branch_id == $this->session->branch_name){ echo "selected";} ?>><?= $value->branch_name;?></option>
                    <?php
                    }
                    } ?>
                </select>
            </div>			
            
            <div class="form-group col-md-12">  
            <label>Select Employee</label> 
            <div id="imgBack"></div>
            <select class="form-control"  name="assign_employee" id="assign_employee">                    
            <?php foreach ($created_bylist as $user) { 
                            
                          if (!empty($user->user_permissions)) {
                            $module=explode(',',$user->user_permissions);
                          }                           
                            
                            ?>
                            <option value="<?php echo $user->pk_i_admin_id; ?>">
                              <?=$user->s_display_name ?>&nbsp;<?=$user->last_name.' - '.$user->s_user_email; ?>                                
                            </option>
                            <?php 
                          //}
                        } ?>                                                      
            </select> 
            </div>
            
          <input type="hidden" value="" class="enquiry_id_input" >
          
            <div class="form-group col-sm-12">        
            <button class="btn btn-success" type="button" onclick="assign_enquiry();">Assign</button>        
            </div>
          
     
                    
                    
                </div>
                

        
          
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
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
          <label class=""><input type="checkbox" class="choose-col" id="choose-col" value = "1" <?php echo ($showall == true or in_array(1, $acolarr)) ? "checked" : ""; ?>> <?php echo display("source"); ?></label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "2"  <?php echo ($showall == true or in_array(2, $acolarr)) ? "checked" : ""; ?>>  Company</label> 
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "21"  <?php echo ($showall == true or in_array(21, $acolarr)) ? "checked" : ""; ?>>  Client Name</label> 
          </div>
          <div class = "col-md-4">  
          <label  class=""><input type="checkbox" class="choose-col"  value = "3"  <?php echo ($showall == true or in_array(3, $acolarr)) ? "checked" : ""; ?>> Name</label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "4"  <?php echo ($showall == true or in_array(4, $acolarr)) ? "checked" : ""; ?>>  Email </label>
          </div>
          
          
          
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "5"  <?php echo ($showall == true or in_array(5, $acolarr)) ? "checked" : ""; ?>>  Phone </label>
              </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "6"  <?php echo ($showall == true or in_array(6, $acolarr)) ? "checked" : ""; ?>>  Address</label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "7"  <?php echo ($showall == true or in_array(7, $acolarr)) ? "checked" : ""; ?>> Process</label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "8"  <?php echo ($showall == true or in_array(8, $acolarr)) ? "checked" : ""; ?>>  Disposition</label>  &nbsp;
          </div>
  
          
                  <?php
                  if ($this->session->companey_id == 29) {
                   ?>
          
          <div class = "col-md-4">  
          
           <label  class=""><input type="checkbox" class="choose-col"  value = "9"  <?php echo ($showall == true or in_array(9, $acolarr)) ? "checked" : ""; ?>> <?php echo "<th>Referred By</th>"; ?></label>  &nbsp; </div><?php
                  }
                  ?>
         
          <div class = "col-md-4">  
          
              <label class=""><input type="checkbox" class="choose-col"  value = "10"  <?php echo ($showall == true or in_array(10, $acolarr)) ? "checked" : ""; ?>>     <?php echo display("create_date"); ?></label> &nbsp;
          </div>
          <div class = "col-md-4">  
          
              <label class=""><input type="checkbox" class="choose-col"  value = "11"  <?php echo ($showall == true or in_array(11, $acolarr)) ? "checked" : ""; ?>>     <?php echo display("created_by"); ?></label>  &nbsp;
          </div>
          <div class = "col-md-4">  
          
              <label class=""><input type="checkbox" class="choose-col"  value = "12"  <?php echo ($showall == true or in_array(12, $acolarr)) ? "checked" : ""; ?>>     <?php echo display("assign_to"); ?></label>  &nbsp;
          </div>
          
          <div class = "col-md-4">  
          
               <label class=""><input type="checkbox" class="choose-col"  value = "13"  <?php echo ($showall == true or in_array(13, $acolarr)) ? "checked" : ""; ?>>   <?php echo display("data_source"); ?></label>  &nbsp; 
           </div>
              <div class = "col-md-4">  
          
               <label class=""><input type="checkbox" class="choose-col"  value = "14"  <?php echo ($showall == true or in_array(14, $acolarr)) ? "checked" : ""; ?>> Product</label>  &nbsp; 
           </div>

          <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "18"  <?php echo ($showall == true or in_array(18, $acolarr)) ? "checked" : ""; ?>>  Score</label>  &nbsp;
          </div>

          <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "19"  <?php echo ($showall == true or in_array(19, $acolarr)) ? "checked" : ""; ?>>  Remark</label>  &nbsp;
          </div>
          <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "20"  <?php echo ($showall == true or in_array(20, $acolarr)) ? "checked" : ""; ?>>  No Of Visit</label>  &nbsp;
          </div>
          <div class = "col-md-4">  
            <label class=""><input type="checkbox" class="choose-col"  value = "17"  <?php echo ($showall == true or in_array(17, $acolarr)) ? "checked" : ""; ?>>EnquiryId</label>  &nbsp;
          </div>

           <?php if($this->session->userdata('companey_id')==29){?>
            <div class = "col-md-4">  
          
               <label class=""><input type="checkbox" class="choose-col"  value = "15"  <?php echo ($showall == true or in_array(15, $acolarr)) ? "checked" : ""; ?>> Bank</label>  &nbsp; 
           </div>
         <?php }?>

            <div class = "col-md-4">  
          
               <label class=""><input type="checkbox" class="choose-col"  value = "16"  <?php echo ($showall == true or in_array(16, $acolarr)) ? "checked" : ""; ?>> Sub Source</label>  &nbsp; 
           </div>
           
          <?php
        
          if(!empty($dfields)) {           
            foreach($dfields as $ind => $fld){              
            ?>
            <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="dchoose-col choose-col"  value = "<?php echo $fld->input_id; ?>"  <?php echo (in_array($fld->input_id, $dacolarr)) ? "checked" : ""; ?>>   <?php echo ucwords($fld->input_label); ?></label>  &nbsp;
          </div>
            <?php   
              
            }?>
             </div>
          <?php } ?>
                
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
$(document).ready(function(){
$("select").select2(); 
});


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
<!---------------------------- DROP Lead -------------------------------->


<?php
if(!empty($_GET['desposition']))
{
  echo'<script>
  $(document).ready(function(){
         setTimeout(call_to_disposition,1000);
    });

  function call_to_disposition()
  {
    var x = $("li[data-stage-id=\''.$_GET['desposition'].'\'");
       try{
        $(x[0]).trigger("click");
        }catch(e){alert(e);}
  }

  </script>';
}
?> 


<div id="deleteselected" class="modal fade" role="dialog">
  <div class="modal-dialog">

     <div class="modal-content">
        
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
</form>

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
    
    document.cookie = "allowcols="+chkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
    document.cookie = "dallowcols="+dchkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
    location.reload();    
  });

  $(document).ready(function() {
       
   var table  = $('#enq_table').DataTable(
        {         
          "processing": true,
          "scrollX": true,
          "scrollY": 520,
          "pagingType": "simple",
          "bInfo": false,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'Enq/enq_load_data'?>",
              "type": "POST",
              "data":function(d){
                d.data_type = "<?=$data_type?>";               
                return d;
              }
              //"data":{'data_type':"<?=$data_type?>"}
          },
        <?php if(user_access(500)) { ?>
          dom: "<'row text-center'<'col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>tp",         
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
        ] ,
        <?php
        }
        ?>
          "columnDefs": [{ "orderable": false, "targets":0 }],
           "order": [[ 1, "desc" ]],
          createdRow: function( row, data, dataIndex ) {            
            var th = $("table>th");            
            l = $("table").find('th').length;
            for(j=1;j<=l;j++){
              h = $("table").find('th:eq('+j+')').html();
              $(row).find('td:eq('+j+')').attr('data-th',h);
            }                       
        },
        drawCallback: function (settings) {
          var api = this.api();
          var $table = $(api.table().node());  
          var info = table.page.info();
          
          returned_rows = table.rows().count();
          
          if(returned_rows == 0 || returned_rows < info.length){
            $('#enq_table_next').addClass('disabled');
          }
          
          $('#enq_table_previous').after('<li><a class="btn btn-secondary btn-sm" style="padding: 4px;line-height: 2;" href="javascript:void(0)">'+info.page+'</a></li>');
        }
      });

  

$(document).on('click',".top_pill",function(){

     var stg_id = $(this).data('stage-id');
     if(!$(this).hasClass('top-active'))
     {
        $(".top_pill").removeClass('top-active');
        $(this).addClass('top-active');
        var form_data = $("#enq_filter").serialize(); 
          form_data+="&stage="+stg_id;
          $.ajax({
            url: '<?=base_url()?>enq/enquiry_set_filters_session',
            type: 'post',
            data: form_data,
            success: function(responseData){
              $('#enq_table').DataTable().ajax.reload();   
             // update_top_filter_counter(); 
          }
        });
        
     }
     else
     {
        $(".top_pill").removeClass('top-active');
        var form_data = $("#enq_filter").serialize(); 
          form_data+="&stage=";
          $.ajax({
            url: '<?=base_url()?>enq/enquiry_set_filters_session',
            type: 'post',
            data: form_data,
            success: function(responseData){
              $('#enq_table').DataTable().ajax.reload();    
               //update_top_filter_counter();
          }
        });
     }
    $('#enq_table').DataTable().ajax.reload();
});

      function process_change_fun(){
        //update_top_filter_counter();
        var count=0;
        var checkboxes = document.getElementsByName('product_filter[]');
        var id = [];
        // loop over them all
        for (var i=0; i<checkboxes.length; i++) {
           // And stick the checked ones onto an array...
           if (checkboxes[i].checked) {
              id.push(checkboxes[i].value);
              count++;
           }
        }

        if(count==1){
          $("#enq-create").show();
        } 
        else{
         $("#enq-create").hide();
        }  
        url = "<?=base_url().'led/get_leadstage_list_byprocess'?>";       
        $.ajax({
            type: "POST",
            url: url,
            data: {
              'id':id
            },
            success: function(data){       
            $(".nav-stage").html(data);  
            $("#nav-process").hide();   
            //stage_counter();               
            }
          });
      }

      $("input[name='product_filter[]']").on('change',function(){    
        $('#enq_table').DataTable().ajax.reload();
        process_change_fun();
      });
      $("#show_quick_counts").on('click',function(){
        $(this).hide();
        $("#active_class").removeClass('hide_countings');    
        update_top_filter_counter();      
      });
      function update_top_filter_counter(){
        //alert("dd");
        $.ajax({
        //url: "<?=base_url().'enq/stages_of_enq/'.$data_type?>",
        url: "<?=base_url().'enq/short_dashboard_count'?>",
        type: 'post',
        data:{data_type:"<?=$data_type?>"},
        dataType: 'json',
        success: function(responseData){
         //alert(JSON.stringify(responseData));
        $('#today_created').html(responseData.all_enquery_num);
        $('#active_all').html(responseData.all_active_num);
        $('#today_updated').html(responseData.all_update_num);
        $('#active_drop').html(responseData.all_drop_num);
        $('#total_active').html(responseData.all_enquery_num);
        $('#pending').html(responseData.all_no_activity_num);
        $('#assigned').html(responseData.all_assigned_num);
        $('#un_assigned').html(responseData.all_unassigned_num);
        
        all_lead_stage_c  = $("input[name='top_filter']:checked").next().next().next().html();

        //console.log(all_lead_stage_c);
        
        $('#lead_stage_-1').text(all_lead_stage_c);     
        },
        error:function(u,v,w)
        {
          alert(w);
        }
    });
      }


      $('#enq_filter').change(function() {
        
        //update_top_filter_counter(); 
        var form_data = $("#enq_filter").serialize();  
        console.log(form_data);
        <?php
          if(!empty($_GET['desposition']))
          {
            echo'form_data+="&stage="+'.$_GET['desposition'].';';
          }
        ?>
        //alert(form_data);

        $.ajax({
        url: '<?=base_url()?>enq/enquiry_set_filters_session',
        type: 'post',
        data: form_data,
        success: function(responseData){
          $('#enq_table').DataTable().ajax.reload();         
         if(!$("#active_class").hasClass('hide_countings')){
           update_top_filter_counter();      
         }
        }
      });
      });
      

  } );
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
  function getTemplates(SMS,type){
     if(type != 'Send Email'){
       $("#email_subject").hide();
      $("#email_subject").prev().hide();
      $("#template_message").summernote('code','');
      $("#template_message").summernote('destroy');
    }else{
      $("#msg_templates_message").html('');
      $("#template_message").summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false                 // set focus to editable area after initializing summernote
      });
      $("#email_subject").show();
      $("#email_subject").prev().show();
    }
  $.ajax({
  type: 'POST',
  url: '<?php echo base_url();?>message/get_templates/0/<?= $data_type ?>/'+SMS,
  })
  .done(function(data){
      // alert(data);
       $('#modal-titlesms').html(type);
       $('#mesge_type').val(SMS);       
       $('#templates').html(data);
      //$("#email_subject").val(data.mail_subject);

  })
  .fail(function() {
      alert( "fail!" );
  });
  }


function  send_sms(){
  if($('.checkbox1:checked').size() > 1000){
    alert('You can not send more than 1000 sms at once');
  }else{
    var sms_type = $("#mesge_type").val();
    //var enquiry_ids = $('#enquery_assing_from').serialize();
    //alert(enquiry_ids);
    //if ("<?=$this->session->companey_id?>" == 81 && sms_type!=1) {
      //url =  '<?php echo base_url();?>message/send_sms_career_ex';
    //}else{
     url =  '<?php echo base_url();?>message/send_sms';
    //}
     $.ajax({
    type: 'POST',
    url: url,
    data: $('#enquery_assing_from').serialize()
    })
    .done(function(data){
        
      alert(data);
      //location.reload();
    })
    .fail(function() {
    alert( "fail!" );
    
    });   
  }
}
</script>
<script>
  function getMessage(){
        
        var tmpl_id = document.getElementById('templates').value;
        
        $.ajax({
            
            url : '<?php echo base_url('enquiry/msg_templates') ?>',
            type: 'POST',
            data: {tmpl_id:tmpl_id},
            success:function(data){
                
                var obj = JSON.parse(data);

                $('#templates option[value='+tmpl_id+']').attr("selected", "selected");

                // $("#template_message").html(obj.template_content);
                // $("#email_subject").val(obj.mail_subject);

                 $("#template_message").summernote('destroy');
                
                  if($("#email_subject").is(':visible'))
                  {
                       $("#template_message").summernote("code", obj.template_content);
                       $("#email_subject").val(obj.mail_subject);
                  }
                  else
                  {
                      $("#template_message").val(obj.template_content);
                      //alert(obj.template_content);
                  }
                  //alert(obj.template_content);
              
            }
            
        });
      
  }   
</script>
 <script>
    /*function getMessage(){
       id=document.getElementById('templates').value;
    $.ajax({
    type: 'POST',
    url: '<?php echo base_url();?>message/getMessage/'+id,
    })
    .done(function(data){
        $("#template_message").html(data);
    })
    .fail(function() {
    alert( "fail!" );

    });
  }*/

function save_enquery(){
     
  $.ajax({
  type: 'POST',
  url: '<?php echo base_url();?>enquiry/create',
  cache: false,
  data: $('#enquery_from').serialize(),
  dataType: 'json',
  success:function(data){
  if(data.status === true ) 
      document.location.href = data.redirect;
  else  
       document.getElementById('success').style.display='none';
     document.getElementById('error').style.display='inline';
     $('#error').html(data.error);
         
  }});
}

function autoDial(){
  if($('.checkbox1:checked').size() > 1000){
    alert('You can not dial more than 1000 <?=display('enquiry')?> at once');
  }else{
    data_type = "<?=$data_type?>";
    data_type = parseInt(data_type);
    //if (data_type == 1) {
      var p_url = '<?php echo base_url();?>enquiry/autoDial';
      //var re_url = '<?php echo base_url();?>enquiry';
    //}
    // else if(data_type == 2){
    //   var p_url = '<?php echo base_url();?>lead/assign_lead';
    //   var re_url = '<?php echo base_url();?>led/index';
    // }else if(data_type == 3){      
    //   var p_url = '<?php echo base_url();?>client/assign_enquiry';
    //   var re_url = '<?php echo base_url();?>client/index';
    // }    

  $.ajax({
    type: 'POST',
    url: p_url,
    data: $('#enquery_assing_from').serialize(),
    beforeSend: function(){
                 $("#imgBack").html('uploading').show();
    },
    success:function(data){
      Swal.fire(
            'success',
            'call scheduled successfully',
            'success'
            );       
    }});
  }
}

function assign_enquiry(){
  if($('.checkbox1:checked').size() > 1000){
    alert('You can not assign more than 1000 <?=display('enquiry')?> at once');
  }else{
    data_type = "<?=$data_type?>";
    data_type = parseInt(data_type);
    if (data_type == 1) {
      var p_url = '<?php echo base_url();?>enquiry/assign_enquiry';
      var re_url = '<?php echo base_url();?>enquiry';
    }else if(data_type == 2){
      var p_url = '<?php echo base_url();?>lead/assign_lead';
      var re_url = '<?php echo base_url();?>led/index';
    }else if(data_type == 3){      
      var p_url = '<?php echo base_url();?>client/assign_enquiry';
      var re_url = '<?php echo base_url();?>client/index';
    }    


  $.ajax({
    type: 'POST',
    url: p_url,
    data: $('#enquery_assing_from').serialize(),
    beforeSend: function(){
                 $("#imgBack").html('uploading').show();
    },
    success:function(data){
         alert(data);         
          window.location.href=re_url;
    }});
  }
}



function moveto_lead(){

  var expected_date = $("input[name=expected_date]").val();
  var lead_score = $("select[name=lead_score]").val();
  var comment = $("input[name=comment]").val();

  if($('.checkbox1:checked').size() > 1000){
    alert('You can not move more than 1000 <?=display('enquiry')?> at once');
  }
  else if(expected_date=='' || lead_score=='' || comment=='')
  {
    alert('Fill all the fields.');
  }
  else{
    var DataString = $('#enquery_assing_from').serialize()+'&expected_date='+expected_date+'&lead_score'+lead_score+'&comment='+comment;
  
  $.ajax({
  type: 'POST',
  url: '<?php echo base_url();?>enquiry/move_to_lead',
  data: DataString,
  success:function(data){
      if(data.trim()==1){
           alert('Successfully Moved in <?=display('lead')?>s'); 
        window.location.href='<?php echo base_url();?>enquiry'
      }else{
       alert(data);
      }
  }});
  }
}

function drop_enquiry(){
if($('.checkbox1:checked').size() > 1000){
    alert('You can not drop more than 1000 <?=display('enquiry')?> at once');
  }else{

     data_type = "<?=$data_type?>";
  data_type = parseInt(data_type);
  if (data_type == 1) {
    var p_url = '<?php echo base_url();?>enquiry/drop_enquiries';
    var re_url = '<?php echo base_url();?>enquiry';
  }else if(data_type == 2){
    var p_url = '<?php echo base_url();?>lead/drop_leadss';
    var re_url = '<?php echo base_url();?>led/index';
  }else if(data_type == 3){
    var p_url = '';
    var re_url = '<?php echo base_url();?>client/index';
  }


  $.ajax({
    type: 'POST',
  url: p_url,
  data: $('#enquery_assing_from').serialize(),
  success:function(data){
          if(data.trim()=='1'){
         alert('Successfully Dropped <?=display('enquiry')?>'); 
        window.location.href=re_url;
      }else{
        alert(data); 
      }
  }});
  }
}



function delete_recorde() {
    $('#enquery_assing_from').attr('action','<?php echo base_url();?>enquiry/delete_recorde')
    $('#enquery_assing_from').submit()
}


</script>

<script>
/*
 $(function () {
   var bindDatePicker = function() {
    $(".date").datetimepicker({
        format:'DD-MM-YYYY hh:mm:ss a',
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
      }
    }).find('input:first').on("blur",function () {
      // check if the date is correct. We can accept dd-mm-yyyy and yyyy-mm-dd.
      // update the format if it's yyyy-mm-dd
      var date = parseDate($(this).val());

      if (! isValidDate(date)) {
        //create date based on momentjs (we have that)
        date = moment().format('YYYY-MM-DD');
      }

      $(this).val(date);
    });
  }
   
   var isValidDate = function(value, format) {
    format = format || false;
    // lets parse the date to the best of our knowledge
    if (format) {
      value = parseDate(value);
    }

    var timestamp = Date.parse(value);

    return isNaN(timestamp) == false;
   }
   
   var parseDate = function(value) {
    var m = value.match(/^(\d{1,2})(\/|-)?(\d{1,2})(\/|-)?(\d{4})$/);
    if (m)
      value = m[5] + '-' + ("00" + m[3]).slice(-2) + '-' + ("00" + m[1]).slice(-2);

    return value;
   }
   
   bindDatePicker();
 });
  
  $(function () {
   var bindDatePicker = function() {
    $(".date2").datetimepicker({
        format:'DD-MM-YYYY',
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
      }
    }).find('input:first').on("blur",function () {
      // check if the date is correct. We can accept dd-mm-yyyy and yyyy-mm-dd.
      // update the format if it's yyyy-mm-dd
      var date = parseDate($(this).val());

      if (! isValidDate(date)) {
        //create date based on momentjs (we have that)
        date = moment().format('YYYY-MM-DD');
      }

      $(this).val(date);
    });
  }
   
   var isValidDate = function(value, format) {
    format = format || false;
    // lets parse the date to the best of our knowledge
    if (format) {
      value = parseDate(value);
    }

    var timestamp = Date.parse(value);

    return isNaN(timestamp) == false;
   }
   
   var parseDate = function(value) {
    var m = value.match(/^(\d{1,2})(\/|-)?(\d{1,2})(\/|-)?(\d{4})$/);
    if (m)
      value = m[5] + '-' + ("00" + m[3]).slice(-2) + '-' + ("00" + m[1]).slice(-2);

    return value;
   }
   
   bindDatePicker();
 });
    */
</script>
<script type='text/javascript'>
$(window).load(function(){  
$("#active_class p").click(function() {
    $('.border_bottom_active').removeClass('border_bottom_active');
    $(this).addClass("border_bottom_active");

    $(this).find('label').trigger('click');
});
});  
</script>
<script type="text/javascript">
  function stage_counter(){     
    lead_stages  = $("input[name='top_filter']:checked"). val();
      $.ajax({
        url: "<?=base_url().'enq/count_stages/'.$data_type.'/'?>",
        type: 'get',
        dataType: 'json',
        success: function(responseData){
          res = responseData;
          filters =   $("input[name='lead_stages']");
          filters.each(function(item,o){
            $("#lead_stage_"+o.value). text(0);
          });
          res.forEach(function(item,index,arr){
            $("#lead_stage_"+item.lead_stage). text(item.c);
          })
          all_lead_stage_c  = $("input[name='top_filter']:checked").next().next().next().html();
          $('#lead_stage_-1').text(all_lead_stage_c);        

          
        }
    });
  }
</script>

 <script>
  
$(document).ready(function(){
   $("#save_advance_filters").on('click',function(e){
    e.preventDefault();
    var arr = Array();  
    $("input[name='filter_checkbox']:checked").each(function(){
      arr.push($(this).val());
    });        
    setCookie('enquiry_filter_setting',arr,365);      
    
    Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Your custom filters saved successfully.',
  showConfirmButton: false,
  timer: 1000
});
  }) 



var enq_filters  = getCookie('enquiry_filter_setting');
if (enq_filters=='') {
    $('#filter_pannel').hide();
    $('#save_filterbutton').hide();

}else{
  $('#filter_pannel').show();
  $('#save_filterbutton').show();

}


if (!enq_filters.includes('probability')) {
  $('#probabilityfilter').hide();  
}else{
  $("input[value='probability']").prop('checked', true);
}



if (!enq_filters.includes('date')) {
  $('#fromdatefilter').hide();
  $('#todatefilter').hide();
}else{
  $("input[value='date']").prop('checked', true);
}

if (!enq_filters.includes('emp')) {
  $('#empfilter').hide();
}else{
  $("input[value='emp']").prop('checked', true);
}

if (!enq_filters.includes('source')) {
  $('#sourcefilter').hide();
}else{
  $("input[value='source']").prop('checked', true);
}

if (!enq_filters.includes('subsource')) {
  $('#subsourcefilter').hide();
}else{
  $("input[value='subsource']").prop('checked', true);
}

if (!enq_filters.includes('phone')) {
  $('#phonefilter').hide();
}else{
  $("input[value='phone']").prop('checked', true);
}

if (!enq_filters.includes('datasource')) {
  $('#datasourcefilter').hide();
}else{
  $("input[value='datasource']").prop('checked', true);
}

if (!enq_filters.includes('email')) {
  $('#emailfilter').hide();
}else{
  $("input[value='email']").prop('checked', true);
}

if (!enq_filters.includes('company')) {
  $('#companyfilter').hide();
}else{
  $("input[value='company']").prop('checked', true);
}

if (!enq_filters.includes('process')) {
  $('#proccessfilter').hide();
}else{
  $("input[value='process']").prop('checked', true);
}
if (!enq_filters.includes('product')) {
  $('#prodfilter').hide();
}else{
  $("input[value='product']").prop('checked', true);
}

if (!enq_filters.includes('address')) {
  $('#addfilter').hide();
}else{
  $("input[value='address']").prop('checked', true);
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
if (!enq_filters.includes('state')) {
  $('#state').hide();
}else{
  $('#statefilter').show();

  $("input[value='state']").prop('checked', true);
}
if (!enq_filters.includes('city')) {
  $('#city').hide();
}else{
  $('#cityfilter').show();

  $("input[value='city']").prop('checked', true);
}
if (!enq_filters.includes('stage')) {
  $('#stage').hide();
}else{
  $('#stagefilter').show();

  $("input[value='stage']").prop('checked', true);
}
if (!enq_filters.includes('aging_rule')) {
  $('#agingRulefilter').hide();
}else{
  $('#agingRulefilter').show();

  $("input[value='aging_rule']").prop('checked', true);
}

if (!enq_filters.includes('visit_wise')) {
  $('#visit_wisefilter').hide();
}else{
  $('#visit_wisefilter').show();
  $("input[value='visit_wise']").prop('checked', true);
}

if (!enq_filters.includes('sales_region')) {
  $('#regionfilter').hide();
}else{
  $('#regionfilter').show();

  $("input[value='sales_region']").prop('checked', true);
}

if (!enq_filters.includes('sales_area')) {
  $('#areafilter').hide();
}else{
  $('#areafilter').show();

  $("input[value='sales_area']").prop('checked', true);
}

if (!enq_filters.includes('sales_branch')) {
  $('#branchfilter').hide();
}else{
  $('#branchfilter').show();

  $("input[value='sales_branch']").prop('checked', true);
}

if (!enq_filters.includes('client_type')) {
  $('#ctypefilter').hide();
}else{
  $('#ctypefilter').show();

  $("input[value='client_type']").prop('checked', true);
}

if (!enq_filters.includes('business_load')) {
  $('#loadfilter').hide();
}else{
  $('#loadfilter').show();

  $("input[value='business_load']").prop('checked', true);
}

if (!enq_filters.includes('industries')) {
  $('#industriesfilter').hide();
}else{
  $('#industriesfilter').show();

  $("input[value='industries']").prop('checked', true);
}

$('input[name="filter_checkbox"]').click(function(){  
  if($('#datecheckbox').is(":checked")||$('#empcheckbox').is(":checked")||$('#sourcecheckbox').is(":checked")||
  $('#subsourcecheckbox').is(":checked")||$('#emailcheckbox').is(":checked")||$('#companycheckbox').is(":checked")||
  $('#phonecheckbox').is(":checked")||$('#assigncheckbox').is(":checked")||$('#addcheckbox').is(":checked")||
  $('#stageheckbox').is(":checked")||$('#prodcheckbox').is(":checked")||$('#statecheckbox').is(":checked")||
  $('#citycheckbox').is(":checked")||$('#datasrccheckbox').is(":checked")||$('#createdbycheckbox').is(":checked")||
  $('#proccheckbox').is(":checked") || $('#regioncheckbox').is(":checked") || $('#areacheckbox').is(":checked") ||
  $('#branchcheckbox').is(":checked") || $('#ctypecheckbox').is(":checked") || $('#loadcheckbox').is(":checked") ||
  $('#industriescheckbox').is(":checked") || $('#vist_wisecheckbox').is(":checked") ||
  $('#agingRulecheckbox').is(":checked")){ 
    $('#save_filterbutton').show();
    $('#filter_pannel').show();          
  }else{
    $('#save_filterbutton').hide();
    $('#filter_pannel').hide();
    

  }
});

$('#buttongroup').hide();

 $('input[name="filter_checkbox"]').click(function(){   
   
  
        if($('#datecheckbox').is(":checked")){
         $('#fromdatefilter').show();
         $('#todatefilter').show();
         $("#buttongroup").show();
        }
        else{
           $('#fromdatefilter').hide();
           $('#todatefilter').hide();
           $("#buttongroup").hide();
        }
         if($('#empcheckbox').is(":checked")){
        $('#empfilter').show();
        $("#buttongroup").show();
        }
        else{
          $('#empfilter').hide();
          $("#buttongroup").hide();
        }
        if($('#sourcecheckbox').is(":checked")){
          $('#sourcefilter').show();
          $("#buttongroup").show();
        }
        else{
          $('#sourcefilter').hide();
          $("#buttongroup").hide();
        }

        if($('#probabilitycheckbox').is(":checked")){
          $('#probabilityfilter').show();
          $("#buttongroup").show();
        }
        else{
          $('#probabilityfilter').hide();
          $("#buttongroup").hide();
        }

        if($('#subsourcecheckbox').is(":checked")){
          $('#subsourcefilter').show();
          $("#buttongroup").show();
        }
        else{
          $('#subsourcefilter').hide();
          $("#buttongroup").hide();
        }



        if($('#emailcheckbox').is(":checked")){
          $('#emailfilter').show();
        }
        else{
          $('#emailfilter').hide();
        }
        if($('#datasrccheckbox').is(":checked")){
          $('#datasourcefilter').show();
        }
        else{
          $('#datasourcefilter').hide();
        }
        if($('#companycheckbox').is(":checked")){
          $('#companyfilter').show();
        }
        else{
          $('#companyfilter').hide();
        }
        if($('#phonecheckbox').is(":checked")){
          $('#phonefilter').show();
        }
        else{
          $('#phonefilter').hide();
        }
        if($('#proccheckbox').is(":checked")){
          $('#proccessfilter').show();
        }
        else{
          $('#proccessfilter').hide();
        }
        if($('#createdbycheckbox').is(":checked")){
          $('#createdbyfilter').show();
        }
        else{
          $('#createdbyfilter').hide();
        }
        if($('#assigncheckbox').is(":checked")){
          $('#assignfilter').show();
        }
        else{
          $('#assignfilter').hide();
        }
        if($('#addcheckbox').is(":checked")){
          $('#addfilter').show();
        }
        else{
          $('#addfilter').hide();
        }

       if($('#stageheckbox').is(":checked")){
          $('#stagefilter').show();
       }
       else{
         $('#stagefilter').hide();
       }
       if($('#prodcheckbox').is(":checked")){
         $('#prodfilter').show();
       }
       else{
         $('#prodfilter').hide();
       }
      if($('#statecheckbox').is(":checked")){
        $('#statefilter').show();
      }
      else{
        $('#statefilter').hide();
      }
     if($('#citycheckbox').is(":checked")){
        $('#cityfilter').show();
      }
      else{
       $('#cityfilter').hide();
      } 

      if($('#agingRulecheckbox').is(":checked")){
        $('#agingRulefilter').show();
      }
      else{
       $('#agingRulefilter').hide();
      }

if($('#regioncheckbox').is(":checked")){
        $('#regionfilter').show();
      }
      else{
       $('#regionfilter').hide();
      }	

if($('#areacheckbox').is(":checked")){
        $('#areafilter').show();
      }
      else{
       $('#areafilter').hide();
      }

if($('#branchcheckbox').is(":checked")){
        $('#branchfilter').show();
      }
      else{
       $('#branchfilter').hide();
      }

if($('#ctypecheckbox').is(":checked")){
        $('#ctypefilter').show();
      }
      else{
       $('#ctypefilter').hide();
      }

if($('#loadcheckbox').is(":checked")){
        $('#loadfilter').show();
      }
      else{
       $('#loadfilter').hide();
      }	

if($('#visit_wisecheckbox').is(":checked")){
        $('#visit_wisefilter').show();
      }
      else{
       $('#vist_wisefilter').hide();
      }	  

            
    });
})

$(document).ready(function(){
  $(".lead_stage_filter").click(function(){
    $(".lead_stage_filter").css("background-color","#e6e9ed");
    $(this).css("background-color","#20a8d8");
  });  
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

$(document).on('mouseover','.change_dispositions',function(e){
  var enq  = $(this).data('id');
  var stage  = $(this).data('stages');
  var dis  = $(this).val();
  var post_url = "<?=base_url().'enq/enquiry_disposition/'?>"+enq;
  $.ajax({
      url: post_url,
      type: 'post',      
      data:{
        disposition:dis,stages:stage
      },
      success: function(res){
        $("select[data-id="+enq+"]").html(res);
      }
  });
});

$(document).on('change','.change_dispositions',function(e){
  var enq  = $(this).data('id');
  var dis  = $(this).val();
  var post_url = "<?=base_url().'enq/enquiry_update_disposition/'?>"+enq;
  $.ajax({
      url: post_url,
      type: 'post',      
      data:{
        disposition:dis
      },
      success: function(res){
        Swal.fire(
            'Good job!',
            'Disposition Updated Successfully!',
            'success'
          )
      }
  });
});

$("#state").change(function() {
    var state_id = $(this).val();
    var html = '';
    $.ajax({
        url: '<?php echo base_url();?>Location/select_city_bystate',
        type: 'POST',
        data: {
            state_id: state_id
        },
        success: function(data) {
            var obj = JSON.parse(data);
            html += '<option value="" style="display:none">---Select City---</option>';
            for (var i = 0; i < (obj.length); i++) {
                html += '<option value="' + obj[i].id + '">' + obj[i].city + '</option>';
            }
            $('#city_name').html(html);
        }
    });
});

$('#city_name').select2({});
$('#state').select2({});

function moveto_client(){
  if($('.checkbox1:checked').size() > 1){
    alert('You can not move more than 1000 <?=display('lead')?> at once');
  }else{
  $.ajax({
  type: 'POST',
  url: '<?php echo base_url();?>enquiry/move_to_client',
  data: $('#enquery_assing_from').serialize(),
  success:function(data){
      if(data.trim()==1){
        alert('Successfully Moved in <?=display('client')?>s'); 
        window.location.href='<?php echo base_url();?>led/index'
      }else{
       alert(data);
      }
  }});
  }
}
function ticket_save_filter(){
var form_data = $("#enq_filter").serialize();       
// alert(form_data);
$.ajax({
url: '<?=base_url()?>ticket/ticket_save_filter/1',
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
  
</script>
<script>
$("#filtered_branch").trigger("change");
 function find_area() { 

            var reg_id = $("select[name='sale_region']").val();
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

            var reg_id = $("select[name='sale_region']").val();
			var area_id = $("select[name='sale_area']").val();
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

 function find_employee() { 
            var dept_id = $("select[name='dept_name']").val();
            var reg_id = $("select[name='sale_region']").val();
			var area_id = $("select[name='sale_area']").val();
			var branch_id = $("select[name='sale_branch']").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>user/select_employee_by_rab',
            data: {dept:dept_id,branch:branch_id,region:reg_id,area:area_id},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';;
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].pk_i_admin_id)+'">'+(obj[i].s_display_name)+' '+(obj[i].last_name)+'-'+(obj[i].s_user_email)+'</option>';
                }
                
                $("#assign_employee").html(html);
                
            }           
            });
}
</script>


<div id="create_task" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Task</h4>
      </div>
      <div class="modal-body" id='task_content'>
      </div>      
    </div>
  </div>
</div>