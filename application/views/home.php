<?php $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING'); ?>
<!-- <link rel="stylesheet" href="<?php echo base_url()?>custom_dashboard/assets/css/dashforge.css"> -->
<link rel="stylesheet" href="<?php echo base_url()?>custom_dashboard/assets/css/dashforge.dashboard.css">
<link href="<?php echo base_url()?>custom_dashboard/lib/morris.js/morris.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/aqua.css">
<link href="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/fullcalendar.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/dashforge.calendar.css" rel="stylesheet">
<style>
.card-graph{
    min-height:250px;
    max-height:500px;
    border:1px solid;    
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}
.card-graph_full{
    min-height:250px;
    max-height:510px;
    border:1px solid;    
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
}
.card-graph_full2{
    border:1px solid;    
    /* margin:2px; */
    box-shadow: 0px 0px 7px -1px;
    border-radius: 6px;
    border-color: transparent;
    overflow: hidden;
    padding: 20px;
}
.card-graph_full:hover{
    box-shadow: 0 0 11px rgba(33,33,33,.2); 
}
.card-graph:hover {
  box-shadow: 0 0 11px rgba(33,33,33,.2); 
}
.card-graph_full2:hover{
    box-shadow: 0 0 11px rgba(33,33,33,.2); 

}

/* @media (min-width: 992px){
    .card-graph {
        width:32%;
    }
} */
.pd-10{
    padding:10px;
}

.highcharts-credits{
  display:none !important;
}
.wd-10 {
    width: 58px !important;
}

.rounded-circle {
    border-radius: 0px !important;
}

#chartdiv {
    width: 100%;
    height: 500px;
}
</style>
<style>
.alert {
    padding: 20px;
    background-color: #f44336;
    color: white;
}

.closebtn {
    margin-left: 15px;
    color: white;
    font-weight: bold;
    float: right;
    font-size: 22px;
    line-height: 20px;
    cursor: pointer;
    transition: 0.3s;
}

.closebtn:hover {
    color: black;
}
</style>
</head>

<body class="page-profile" style="background-color:#fff;">
    
    <div class="row">
    <?php
        if(user_access(541)) { ?>
        <a href="<?= base_url('ticket/dashboard') ?>"><button style=" float:right" class="btn btn-primary">
                Ticket Dashboard
            </button></a>
            <?php
        }
		if(user_access('ftl2')) { ?>
        <a href="<?= base_url('ticket/feedback_dash') ?>"><button style=" float:right" class="btn btn-primary">
                FTL Feedback
            </button></a>
            <?php
        }
        if(user_access(1006)) { ?>        
        <a href="<?= base_url('deal_dashboard/dashboard') ?>"><button style=" float:right;margin-right:2px;" class="btn btn-primary">
            Deal Dashboard
        </button></a>
        <?php
        }
        ?>
    </div>

    <?php  if($this->session->userdata('user_right')==151) { ?>
    <?php //include('student/course_wrapper.php'); ?>
    <?php }else{ ?>

    <div class="col-md-6">
        <div style="float:right">           
        </div>
    </div>
    </br>
    <div class="row"  style="margin-top: 15px;">
        <form method="POST" >
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="form-row" style="padding: 10px;">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-2">
                            <div class="form-group">
                            <label>From</label>
                            <input  class="d_filter form-control" type='date' name="from_date" id="from_date" value="<?php if(!empty($fdata['from_date'])){echo $fdata['from_date'];} ?>">
                            </div>
                        </div>
                    
                        <div class="col-lg-2">
                            <div class="form-group">
                            <label>To</label>
                            <input  class="d_filter form-control" type='date' name="to_date" value="<?php if(!empty($fdata['to_date'])){echo $fdata['to_date'];} ?>">
                            </div>
                        </div>
                        <?php
                        $this->db->where('type',1);
                        $region_arr = $this->db->get('sales_region')->result_array();
                        ?>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Employee Region</label>                                
                                <select name="region" class="form-control">
                                    <option value="" >--Select--</option>
                                    <?php
                                    if(!empty($region_arr)){
                                        foreach($region_arr as $key=>$value){
                                            ?>                                            
                                            <option value="<?=$value['region_id']?>" <?php if(!empty($fdata['region'])){ if($fdata['region']==$value['region_id']){echo'selected';}} ?> ><?=$value['name']?></option>";
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>User</label>
                                <?php 
                                $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
                                $where = "pk_i_admin_id IN (".implode(',', $all_reporting_ids).")";
                                $where .= " AND b_status = 1 AND dept_name!=6";
                                if(!empty($fdata['region'])){
                                    $where .= " AND sales_region = ".$fdata['region'];
                                }
                                $users =$this->db->where($where)->get('tbl_admin');
                                ?>
                                <select name="users" class="form-control">
                                <option value="" >--Select--</option>
                                    <?php   foreach ($users->result() as $key => $value) {  ?>
                                        <option value="<?= $value->pk_i_admin_id ?>" <?php if(!empty($fdata['users'])){ if($fdata['users']==$value->pk_i_admin_id ){echo'selected';}} ?>><?= $value->s_display_name.' '.$value->last_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>






                        <!-- <div class="col-lg-2">
                            <div class="form-group">
                                <label>State</label> 
                                <label> <?php echo display("state"); ?> <i class="text-danger"></i></label>
                                <select name="state_id" class="form-control" id="fstate">
                                    <option value="" >--Select--</option>
                                    <?php foreach($state_list as $state){?>
                                    <option  value="<?php echo $state->id ?>" <?php if(!empty($fdata['state_id'])){ if($fdata['state_id']==$state->id ){echo'selected';}} ?>><?php echo $state->state; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class=" col-lg-2">
                            <div class="form-group">
                                <label><?php echo display("city"); ?> <i class="text-danger"></i></label>
                                <select name="city_id" class="form-control" id="fcity">
                                    <option value="" style="display:none;">--Select--</option>
                                    <?php if($_POST['city_id']){?>
                                        <?php foreach($city_list as $city){?>
                                    <option  value="<?php echo $city->id ?>" <?php if(!empty($fdata['city_id'])){ if($fdata['city_id']==$city->id ){echo'selected';}} ?>><?php echo $city->city; ?></option>
                                    <?php } ?>
                                        
                                    <?php } ?>
                                </select>
                            </div>
                        </div> -->
                        <div class=" col-lg-2">
                            <div class="form-group" style="padding:20px;">
                                <button name="submit" type="submit" class="btn btn-primary" >Filter</button>
                            </div>
                        </div>
                    </div>                      
                </div>            
            </div>        
        </form>
    </div>
    <div class="content">
    <?php       
     $user_id   = $this->session->user_id;
     $user_role = $this->session->user_role;
     $region_id = $this->session->region_id;
     $assign_country = $this->session->country_id;
     $assign_region = $this->session->region_id;
     $assign_territory = $this->session->territory_id;
     $assign_state = $this->session->state_id;
     $assign_city = $this->session->city_id;     
    ?>
        </br>


        <div id="content_tabs"></div>
        <div id="content_tabs1">


            <!-----------------------------------------------------------------------------html widget----------------------------------------->
            <div class="row row-xs">
                <?php if($msg!='') { ?>
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <strong><?=$msg;?></strong>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <?php  if(user_access(60)){ ?>
                <div class="col-md-4 col-sm-6 col-xs-12 ">
                    <div class="info-box bg-purple">
                        <span class="info-box-icon"><i class="fa fa-question-circle-o" style="color:#fff;"></i></span>
                        <div class="info-box-content1">
                            <div class="box box-widget widget-user-2">
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked">
                                        <li><a href="<?php echo base_url().'enq/index'; ?>"><?php echo display("all_enquiry"); ?> <span
                                                    class="pull-right badge bg-blue"><?php if(!empty($counts['enquiry'])){ echo $counts['enquiry'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'enq/index'; ?>"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?php if(!empty($counts['enq_ct'])){ echo $counts['enq_ct'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'enq/index'; ?>"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?php if(!empty($counts['enq_ut'])){ echo $counts['enq_ut'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'enq/index'; ?>"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['enquiry'])){ echo ($counts['enquiry']-$counts['enq_drp']);}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'enq/index'; ?>"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?php if(!empty($counts['enq_drp'])){ echo $counts['enq_drp'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'enq/index'; ?>"><?php echo display("unassigned"); ?> <span
                                                    class="pull-right badge bg-maroon"><?php if(!empty($counts['enq_assign'])){ echo $counts['enq_assign'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php  if(user_access(70)){ ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-line-chart" style="color:#fff;"></i></span>
                        <div class="info-box-content1">
                            <div class="box box-widget widget-user-2">
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked">
                                        <li><a href="<?php echo base_url().'led/index'; ?>"><?php echo display("all_leads"); ?> <span
                                                    class="pull-right badge bg-blue"><?php if(!empty($counts['lead'])){ echo $counts['lead'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'led/index'; ?>"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?php if(!empty($counts['lead_ct'])){ echo $counts['lead_ct'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'led/index'; ?>"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?php if(!empty($counts['lead_ut'])){ echo $counts['lead_ut'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'led/index'; ?>"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['lead'])){ echo ($counts['lead']-$counts['lead_drp']);}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'led/index'; ?>"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?php if(!empty($counts['lead_drp'])){ echo $counts['lead_drp'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'led/index'; ?>"><?php echo display("unassigned"); ?> <span
                                                    class="pull-right badge bg-maroon"><?php if(!empty($counts['lead_assign'])){ echo $counts['lead_assign'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php  if(user_access(80)){ ?>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="fa fa-user-circle-o" style="color:#fff;"></i></span>
                        <div class="info-box-content1">
                            <div class="box box-widget widget-user-2">
                                <div class="box-footer no-padding">

                                    <ul class="nav nav-stacked">
                                        <li><a href="<?php echo base_url().'client/index'; ?>"><?php echo display("all_clients"); ?> <span
                                                    class="pull-right badge bg-blue"><?php if(!empty($counts['client'])){ echo $counts['client'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index'; ?>"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?php if(!empty($counts['client_ct'])){ echo $counts['client_ct'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index'; ?>"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?php if(!empty($counts['client_ut'])){ echo $counts['client_ut'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index'; ?>"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['client'])){ echo ($counts['client']-$counts['client_drp']);}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index'; ?>"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?php if(!empty($counts['client_drp'])){ echo $counts['client_drp'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index'; ?>"><?php echo display("unassigned"); ?> <span
                                                    class="pull-right badge bg-maroon"><?php if(!empty($counts['client_assign'])){ echo $counts['client_assign'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
                <?php
                }
                
                if(user_access(553)){ 
if (!empty($enquiry_separation)) {
  $enquiry_separation = json_decode($enquiry_separation, true);
      foreach ($enquiry_separation as $key => $value) {   
        
              $ctitle = $enquiry_separation[$key]['title']; 
              $data_s = $this->enquiry_model->Dy_enquiryLeadClientCount($this->session->user_id,$this->session->companey_id,$key);
                   ?>

                <div class="col-md-4 col-sm-6 col-xs-12 ">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="<?= $enquiry_separation[$key]['icon'] ?>"
                                style="color:#fff;"></i></span>
                        <div class="info-box-content1">
                            <div class="box box-widget widget-user-2">
                                <div class="box-footer no-padding">

                                    <ul class="nav nav-stacked">
                                        <li><a href="<?php echo base_url().'client/index?stage='.$key; ?>">All <?php echo $ctitle ?> <span
                                                    class="pull-right badge bg-blue"><?= $data_s['enquiry'] ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index?stage='.$key; ?>"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?= $data_s['enq_ct'] ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index?stage='.$key; ?>"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?= $data_s['enq_ut'] ?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url().'client/index?stage='.$key; ?>"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['enquiry'])){ echo $data_s['enquiry']-$data_s['enq_drp']; }else{echo '0';} ?>
                                                </span></a></li>
                                        <li><a href="<?php echo base_url().'client/index?stage='.$key; ?>"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?= $data_s['enq_drp'] ?>
                                                </span></a></li>
                                        <li><a href="<?php echo base_url().'client/index?stage='.$key; ?>"><?php echo display("unassigned"); ?> <span
                                                    class="pull-right badge bg-maroon"><?= $data_s['enq_assign'] ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <?php } } ?>
                <?php } ?>

            </div>




        <!-- visit card dashboard start-->
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-car" style="color:#fff;"></i></span>
                <div class="info-box-content1">
                    <div class="box box-widget widget-user-2">
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li><a href="<?php echo base_url().'client/visits' ?>">All Visit<span
                                            class="pull-right badge bg-blue"><?php if(!empty($visit_counts)){ echo $visit_counts; }else{ echo "0";} ?></span></a>
                                </li>
                                <li><a href="<?php echo base_url().'client/visits' ?>"><?php echo display("created_today"); ?> <span
                                            class="pull-right badge bg-aqua"><?php if(!empty($visit_counts_today)){ echo $visit_counts_today;}else{ echo '0';}; ?></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- visit card dashbaord end -->


        <!-- Deal card dashboard start-->
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-handshake-o" style="color:#fff;"></i></span>
                <div class="info-box-content1">
                    <div class="box box-widget widget-user-2">
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li><a href="<?php echo base_url().'client/deals' ?>">All Deals<span
                                            class="pull-right badge bg-blue"><?php if(!empty($deal_total_count)){ echo $deal_total_count; }else{ echo "0";} ?></span></a>
                                </li>
                                <li><a href="<?php echo base_url().'client/deals' ?>">Deal Amount <span
                                            class="pull-right badge bg-aqua"><?php if(!empty($get_deal_count_amount)){ echo $get_deal_count_amount;}else{ echo '0';}; ?> Lacs</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Deal card dashbaord end -->



            <!-------------------------------------html widget End--------------------------->

            <!-----------------------html FUNNEL START------------------------->

            <div class="row row-xs">
                <div class="col-md-12">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>
            </div>
                <div class="row pd-20" style="width:100%;">
                <div class="col-md-12" style="padding:10px;">
                    <div class="card card-graph_full" style="height: 610px;">
                    <div id="chartdiv" style="height: 510px;"></div>
                    </div>
                </div>
                </div>

                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>
                <div class="row pd-20" style="width:100%;">

                <div class="col-md-6 " style="padding:10px;">
                    <div class="card card-graph" style="padding:10px;">
                        <div class="card-header pd-y-10 d-md-flex align-items-center justify-content-between">
						<text x="347" text-anchor="middle" class="highcharts-title" data-z-index="4" style="color:#333333;font-size:18px;fill:#333333;font-weight:900;" y="24" aria-hidden="true"><?php echo 'All Clients'; ?></text>
                        </div><!-- card-header -->
                        <canvas id="bar-chart-grouped" width="800" ></canvas>
                    </div><!-- card -->
                </div>
                <div class="col-md-6 " style="padding:10px;">
                    <div class="card card-graph">
                        <div class="card-header pd-y-20 d-md-flex align-items-center" style="padding:10px;">
								<text x="347" text-anchor="middle" class="highcharts-title" data-z-index="4" style="color:#333333;font-size:18px;fill:#333333;font-weight:900" y="24" aria-hidden="true"><?php echo display("conversion_probability"); ?></text>
                        </div>
                       <div id="chartdiv2" style="height: 343px;" ></div>
                    </div>
                </div>
                </div>

                <!--------------------------Process wise charts----------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>
                <div class="row pd-20">
                <!-- <div class="col-md-6 " style="padding:20px;">
                    <div class="card card-graph" style="height:100%;">
                        <figure class="highcharts-figure">
                            <div id="containersss"></div>
                        </figure>
                    </div>
                </div> -->
               
                </div>

                <!------------------------Process wise charts End------------------------------------------->
                <!--------------------------------html FUNNEL START END-------------------------------------------->
                <div class="col-md-12">
                    <hr style="border: 1px solid red !important">
                </div>

                <!-----------------------HTML Map/Calender START---------------------------->
                <div class="row pd-20">
              
                <div class="col-lg-12" >
                    <div class="card  " style="height:95% !important;">
                        <style>
                        #calendar {
                            height: 500px;
                        }
                        .fc-title {
                            color: black;
                        }
						.fc-center h2{
						color:#333333;
						font-size:18px !important;
						fill:#333333;
						font-family: 'Montserrat' !important;
                        font-weight:900;						
						}
                        </style>
                        <div class='pull-right' style="float: right;margin-right: 78px;margin-left: 5px;">
                            <div class="btn-group dropdown-filter">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class='fa fa-cogs'></i><span class="caret"></span>
                                </button>              
                                <ul class="filter-dropdown-menu dropdown-menu">   
                                    <li>
                                        <label>
                                            <input type="checkbox" value="1" name="task_filter" checked> 
                                            Task <i style='background:#0073b7;color:#0073b7;' class="fa fa-square"></i>
                                        </label>
                                    </li>  
                                    <li>
                                        <label>
                                            <input type="checkbox" value="2" name="task_filter" checked> 
                                            Follow Up <i style='background:#ff0000;color:#ff0000;' class="fa fa-square"></i>
                                        </label>
                                    </li>  
                                    <li>
                                        <label>
                                            <input type="checkbox" value="3" name="task_filter" checked> 
                                            Visits <i style='background:#0073b7;color:0073b7;' class="fa fa-square"></i>
                                        </label>
                                    </li>  
                                </ul>                
                                
                            </div>
                        </div>
                        <div id="calendar" ></div>

                    </div>
                </div>
                </div>
                <!------------------------------HTML Map/Calender END------------------------------->

                <div class="col-lg-6 col-xl-6 mg-t-10">
                    <div class="card" style="height:95%;">

                        <style>
                        #container12 {
                            height: 500px;
                            margin: 0 auto;
                        }

                        .loading {
                            margin-top: 10em;
                            text-align: center;
                            color: gray;
                        }
                        </style>
                        <div id="container12" style="display: none;"></div>

                    </div>
                </div>
                <!--------------------------------html map/Calender END-------------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid red !important">
                </div>

                <!-------------------------------------Disposition/Source START------------------------------->
                <div class="row pd-20">
		<style>
          .highcharts-title{
			font-weight:900 !important;
            font-family: 'Montserrat' !important;			
		  }
		</style>
                <div class="col-lg-12 pd-20">
                    <div class="card card-graph" style="height:400px;">
                        <figure class="highcharts-figure">
                            <div id="container1"></div>
                        </figure>

                    </div>
                </div>
                
                <!-------------------------------Disposition/Source END---------------------------->
                <!-------------------------------html map/Calender END------------------------------------>
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid red !important">
                </div>

                <!------------------------------Timeline START--------------------------------->
                <div class="row pd-20">
                <div class="col-md-12 pd-20">

                    <div class="card card-graph_full2 " style="height:100%; width:100% !important;">

                                    <div class="page-header">
                                     <center><text x="347" text-anchor="middle" class="highcharts-title" data-z-index="4" style="color:#333333;font-size:18px;fill:#333333;font-weight:900;" y="24" aria-hidden="true"><?php echo display('average_follow_up_rate'); ?></text></center>
                                    </div>
                                    <div style="display:inline-block;width:100%;overflow-y:auto;">
                                        <ul class="timeline timeline-horizontal">
                                            <?php   if(user_access(60)){  ?>

                                            <li class="timeline-item">
                                                <div class="timeline-badge primary"
                                                    style="width:150px !important;border-radius: 30px;">
                                                    <?php echo display("enquiry"); ?>
                                                </div>
                                            </li>
                                            <li class="timeline-item" style="left:40px !important;">
                                                <div class="timeline-badge success"><i
                                                        class="glyphicon glyphicon-check"></i></div>
                                                <div class="timeline-panel"
                                                    style="width:100px !important;border-radius: 30px;">
                                                    <div class="timeline-heading">
                                                        <!--<h4 class="timeline-title">Average</h4>-->

                                                        <p><small class="text-muted"><i
                                                                    class="glyphicon glyphicon-time"></i>&nbsp;
                                                                <?php
                   $leadTime=$leadSum->row()->time;
                    if ($leadTime!=0) {
                $minutes=  round(($leadTime/$leadCount),0);
                echo $this->enquiry_model->secsToStr($minutes);
            }else{echo 'N/A';
                } ?>
                                                            </small></p>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>
                                            <?php   if(user_access(70)){  ?>

                                            <li class="timeline-item">
                                                <div class="timeline-badge info"
                                                    style="width:150px !important;border-radius: 30px;">
                                                    <?php echo display("lead"); ?>
                                                </div>

                                            </li>
                                            <li class="timeline-item" style="left:40px !important;">
                                                <div class="timeline-badge danger"><i
                                                        class="glyphicon glyphicon-check"></i></div>
                                                <div class="timeline-panel"
                                                    style="width:100px !important;border-radius: 30px;">
                                                    <div class="timeline-heading">
                                                        <!--<h4 class="timeline-title">Average</h4>-->
                                                        <p><small class="text-muted"><i
                                                                    class="glyphicon glyphicon-time"></i> &nbsp;
                                                                <?php  if ($clientsum->row()->time!=0) {
                 $minutes= round(($clientsum->row()->time)/$clientCount2,0);
                echo $this->enquiry_model->secsToStr($minutes);
            }else{echo 'N/A';} ?>
                                                            </small></p>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>
                                            <?php   if(user_access(80)){  ?>
                                            <li class="timeline-item">
                                                <div class="timeline-badge warning"
                                                    style="width:150px !important;border-radius: 30px;">
                                                    <?php echo display("Client"); ?>
                                                </div>
                                            </li>
                                            <?php } ?>
                                            <!-- //dynamic case -->
                                            <?php  if(user_access(553)){ ?>
                                            <?php
        $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');

    if (!empty($enquiry_separation)) {
$enquiry_separation = json_decode($enquiry_separation, true);
    foreach ($enquiry_separation as $key => $value) {
            $ctitle = $enquiry_separation[$key]['title']; 
            $Count=$this->dashboard_model->countLead($key);
            $sum=$this->dashboard_model->dataLead($key);
            $stime= $sum->row()->time;
           
           ?>
                                            <li class="timeline-item" style="left:40px !important;">
                                                <div class="timeline-badge danger"><i
                                                        class="glyphicon glyphicon-check"></i></div>
                                                <div class="timeline-panel"
                                                    style="width:100px !important;border-radius: 30px;">
                                                    <div class="timeline-heading">
                                                        <!--<h4 class="timeline-title">Average</h4>-->
                                                        <p><small class="text-muted"><i
                                                                    class="glyphicon glyphicon-time"></i> &nbsp;<?php  if ($stime!=0) {
                 if($stime && $Count){
                    $minutes= round(($stime)/$Count,0);
                 }else{
                    $minutes = 0;
                }
                echo $this->enquiry_model->secsToStr($minutes);
                 
                 }else{echo 'N/A';} ?> </small></p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="timeline-item">
                                                <div class="timeline-badge warning"
                                                    style="width:150px !important;border-radius: 30px;">
                                                    <?= $ctitle ?>
                                                </div>
                                            </li>
                                            <?php } }  } ?>


                                            <!-- // dynamic case end -->
                                        </ul>
                                    </div>
                        </div>

                    </div>
                </div>
                <div class="row row-xs">
                <div class="col-md-12">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>
            </div>
<div class="row pd-20">
<div class="col-md-6 pd-20">
<div class="card card-graph_full2 pd-20" style="height:430px;">
    <center><text x="347" text-anchor="middle" class="highcharts-title" data-z-index="4" style="color:#333333;font-size:18px;fill:#333333;font-weight:900;" y="24" aria-hidden="true">Drop Data</text></center>
    <canvas id="process_Monthwise" width="800" height="400"></canvas>
</div>
</div>
<div class="col-md-6 ">
<div class="card card-graph" style="height:100%;">
    <figure class="highcharts-figure">
        <div id="container2"></div>
    </figure>
</div>
</div>
</div>
<br>
<div class="row row-xs">
                <div class="col-md-12">
                    <hr style="border: 1px solid #3b95e4 !important">
                </div>
            </div>


                <!----------------------------------Timeline END---------------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>
                <!--------------------------Grapth JS --------------------------->



                <!----------------------------------Graph JS End-------------------------------------->

            </div><!-- container -->

            <script src="<?php echo base_url()?>custom_dashboard/lib/jquery.flot/jquery.flot.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/lib/jquery.flot/jquery.flot.stack.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/lib/jquery.flot/jquery.flot.resize.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/lib/chart.js/Chart.bundle.min.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/lib/jqvmap/jquery.vmap.min.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/lib/jqvmap/maps/jquery.vmap.usa.js"></script>

            <script src="<?php echo base_url()?>custom_dashboard/assets/js/dashforge.sampledata.js"></script>
            <script src="<?php echo base_url()?>assets/js/custom-chart.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/lib/morris.js/morris.min.js"></script>
            <!----- custom link ---------------->
            <!--<script type="text/javascript" src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/ammap.js"></script>-->
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/india-js-map.js"></script>
            <script type="text/javascript" src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/indiaLow.js">
            </script>
            <script type="text/javascript"
                src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/jquery.canvasjs.min.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/lib/raphael/raphael.min.js"></script>
            <!--<script src="<?php echo base_url()?>custom_dashboard/assets/js/chart.morris.js"></script>-->

            <script src="<?php echo base_url()?>custom_dashboard/lib/peity/jquery.peity.min.js"></script>

            <script src="<?php echo base_url()?>custom_dashboard/assets/js/chart.chartjs.js"></script>

            <script type="text/javascript" src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/loader.js">
            </script>

            <!--------------amcharts funnel-------------------------------------------------------->
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/core.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/charts.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/animated.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/maps.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/worldLow.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/countries2.js"></script>
            <script src="https://www.amcharts.com/lib/4/plugins/timeline.js"></script>
            <!--------------End here -------------------------------------------------------------->

            <!------------------------------high chart---------------------------------------------->
            <script src="https://code.highcharts.com/maps/highmaps.js"></script>
            <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
            <script src="https://code.highcharts.com/mapdata/countries/in/in-all.js"></script>

            <script src="https://code.highcharts.com/highcharts.js"></script>
            <script src="https://code.highcharts.com/modules/exporting.js"></script>
            <script src="https://code.highcharts.com/modules/export-data.js"></script>
            <script src="https://code.highcharts.com/modules/accessibility.js"></script>
            <!-------------------------------------------high chart end---------------------------------->

            <?php if(user_access('60')==true||user_access('70')==true||user_access('80')==true){?>
            <script>
            function sales_dash() {
                $("#content_tabs").html('');
                $("#content_tabs1").css('display', 'block');
            }
            </script>
            <?php }else if(user_access('100')==true ||user_access('101')==true||user_access('102')==true||user_access('103')==true||user_access('104')==true||user_access('105')==true||user_access('106')==true||user_access('110')==true||user_access('111')==true||user_access('112')==true||user_access('113')==true||user_access('114')==true||user_access('115')==true||user_access('116')==true){?>

            ?>
            <script type='text/javascript'>
            $(window).load(function() {
                $("#content_tabs").load('<?php echo base_url()?>dashboard/sales_dashboard');
                $("#content_tabs1").css('display', 'none');
            });
            </script>
            <?php  }?>
            <script>
            function changeMenu(menu, submenu, options) {
                $("#content_tabs").load('<?php echo base_url()?>/' + menu + '/' + submenu);
                $("#content_tabs1").css('display', 'none');

            }

            function show_funnel1() {
                $("#show_funnel").css('display', 'none');
                $("#show_funnel1").css('display', 'block');
            }

            function show_funnel() {
                $("#show_funnel1").css('display', 'none');
                $("#show_funnel").css('display', 'block');
            }

            //Get drop down value..
            function getEventTarget(e) {
                e = e || window.event;
                return e.target || e.srcElement;
            }         

          
            </script>
            <!------------------------------------------------------------------knob js Start---------------------------------------------->
            <script>
            $(function() {
                /* jQueryKnob */

                $(".knob").knob({
                    /*change : function (value) {
                     //console.log("change : " + value);
                     },
                     release : function (value) {
                     console.log("release : " + value);
                     },
                     cancel : function () {
                     console.log("cancel : " + this.value);
                     },*/
                    draw: function() {

                        // "tron" case
                        if (this.$.data('skin') == 'tron') {

                            var a = this.angle(this.cv) // Angle
                                ,
                                sa = this.startAngle // Previous start angle
                                ,
                                sat = this.startAngle // Start angle
                                ,
                                ea // Previous end angle
                                , eat = sat + a // End angle
                                ,
                                r = true;

                            this.g.lineWidth = this.lineWidth;

                            this.o.cursor &&
                                (sat = eat - 0.3) &&
                                (eat = eat + 0.3);

                            if (this.o.displayPrevious) {
                                ea = this.startAngle + this.angle(this.value);
                                this.o.cursor &&
                                    (sa = ea - 0.3) &&
                                    (ea = ea + 0.3);
                                this.g.beginPath();
                                this.g.strokeStyle = this.previousColor;
                                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea,
                                    false);
                                this.g.stroke();
                            }

                            this.g.beginPath();
                            this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat,
                                false);
                            this.g.stroke();

                            this.g.lineWidth = 2;
                            this.g.beginPath();
                            this.g.strokeStyle = this.o.fgColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this
                                .lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                            this.g.stroke();

                            return false;
                        }
                    }
                });
                /* END JQUERY KNOB */

                //INITIALIZE SPARKLINE CHARTS
                $(".sparkline").each(function() {
                    var $this = $(this);
                    $this.sparkline('html', $this.data());
                });

                /* SPARKLINE DOCUMENTATION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
                drawDocSparklines();
                drawMouseSpeedDemo();

            });

            function drawDocSparklines() {

                // Bar + line composite charts
                $('#compositebar').sparkline('html', {
                    type: 'bar',
                    barColor: '#aaf'
                });
                $('#compositebar').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7], {
                    composite: true,
                    fillColor: false,
                    lineColor: 'red'
                });


                // Line charts taking their values from the tag
                $('.sparkline-1').sparkline();

                // Larger line charts for the docs
                $('.largeline').sparkline('html', {
                    type: 'line',
                    height: '2.5em',
                    width: '4em'
                });

                // Customized line chart
                $('#linecustom').sparkline('html', {
                    height: '1.5em',
                    width: '8em',
                    lineColor: '#f00',
                    fillColor: '#ffa',
                    minSpotColor: false,
                    maxSpotColor: false,
                    spotColor: '#77f',
                    spotRadius: 3
                });

                // Bar charts using inline values
                $('.sparkbar').sparkline('html', {
                    type: 'bar'
                });

                $('.barformat').sparkline([1, 3, 5, 3, 8], {
                    type: 'bar',
                    tooltipFormat: '{{value:levels}} - {{value}}',
                    tooltipValueLookups: {
                        levels: $.range_map({
                            ':2': 'Low',
                            '3:6': 'Medium',
                            '7:': 'High'
                        })
                    }
                });

                // Tri-state charts using inline values
                $('.sparktristate').sparkline('html', {
                    type: 'tristate'
                });
                $('.sparktristatecols').sparkline('html', {
                    type: 'tristate',
                    colorMap: {
                        '-2': '#fa7',
                        '2': '#44f'
                    }
                });

                // Composite line charts, the second using values supplied via javascript
                $('#compositeline').sparkline('html', {
                    fillColor: false,
                    changeRangeMin: 0,
                    chartRangeMax: 10
                });
                $('#compositeline').sparkline([4, 1, 5, 7, 9, 9, 8, 7, 6, 6, 4, 7, 8, 4, 3, 2, 2, 5, 6, 7], {
                    composite: true,
                    fillColor: false,
                    lineColor: 'red',
                    changeRangeMin: 0,
                    chartRangeMax: 10
                });

                // Line charts with normal range marker
                $('#normalline').sparkline('html', {
                    fillColor: false,
                    normalRangeMin: -1,
                    normalRangeMax: 8
                });
                $('#normalExample').sparkline('html', {
                    fillColor: false,
                    normalRangeMin: 80,
                    normalRangeMax: 95,
                    normalRangeColor: '#4f4'
                });

                // Discrete charts
                $('.discrete1').sparkline('html', {
                    type: 'discrete',
                    lineColor: 'blue',
                    xwidth: 18
                });
                $('#discrete2').sparkline('html', {
                    type: 'discrete',
                    lineColor: 'blue',
                    thresholdColor: 'red',
                    thresholdValue: 4
                });

                // Bullet charts
                $('.sparkbullet').sparkline('html', {
                    type: 'bullet'
                });

                // Pie charts
                $('.sparkpie').sparkline('html', {
                    type: 'pie',
                    height: '1.0em'
                });

                // Box plots
                $('.sparkboxplot').sparkline('html', {
                    type: 'box'
                });
                $('.sparkboxplotraw').sparkline([1, 3, 5, 8, 10, 15, 18], {
                    type: 'box',
                    raw: true,
                    showOutliers: true,
                    target: 6
                });

                // Box plot with specific field order
                $('.boxfieldorder').sparkline('html', {
                    type: 'box',
                    tooltipFormatFieldlist: ['med', 'lq', 'uq'],
                    tooltipFormatFieldlistKey: 'field'
                });

                // click event demo sparkline
                $('.clickdemo').sparkline();
                $('.clickdemo').bind('sparklineClick', function(ev) {
                    var sparkline = ev.sparklines[0],
                        region = sparkline.getCurrentRegionFields();
                    value = region.y;
                    alert("Clicked on x=" + region.x + " y=" + region.y);
                });

                // mouseover event demo sparkline
                $('.mouseoverdemo').sparkline();
                $('.mouseoverdemo').bind('sparklineRegionChange', function(ev) {
                    var sparkline = ev.sparklines[0],
                        region = sparkline.getCurrentRegionFields();
                    value = region.y;
                    $('.mouseoverregion').text("x=" + region.x + " y=" + region.y);
                }).bind('mouseleave', function() {
                    $('.mouseoverregion').text('');
                });
            }

            /**
             ** Draw the little mouse speed animated graph
             ** This just attaches a handler to the mousemove event to see
             ** (roughly) how far the mouse has moved
             ** and then updates the display a couple of times a second via
             ** setTimeout()
             **/
            function drawMouseSpeedDemo() {
                var mrefreshinterval = 500; // update display every 500ms
                var lastmousex = -1;
                var lastmousey = -1;
                var lastmousetime;
                var mousetravel = 0;
                var mpoints = [];
                var mpoints_max = 30;
                $('html').mousemove(function(e) {
                    var mousex = e.pageX;
                    var mousey = e.pageY;
                    if (lastmousex > -1) {
                        mousetravel += Math.max(Math.abs(mousex - lastmousex), Math.abs(mousey - lastmousey));
                    }
                    lastmousex = mousex;
                    lastmousey = mousey;
                });
                var mdraw = function() {
                    var md = new Date();
                    var timenow = md.getTime();
                    if (lastmousetime && lastmousetime != timenow) {
                        var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
                        mpoints.push(pps);
                        if (mpoints.length > mpoints_max)
                            mpoints.splice(0, 1);
                        mousetravel = 0;
                        $('#mousespeed').sparkline(mpoints, {
                            width: mpoints.length * 2,
                            tooltipSuffix: ' pixels per second'
                        });
                    }
                    lastmousetime = timenow;
                    setTimeout(mdraw, mrefreshinterval);
                };
                // We could use setInterval instead, but I prefer to do it this way
                setTimeout(mdraw, mrefreshinterval);
            }
            </script>
            <!------------------------------------------------------------------knob js End---------------------------------------------->
            <!-----------------------------chart js-------------------->
            <!-- funnel chart Chart code start -->
            <script>
            $(document).ready(function() {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';                
                $.ajax({
                    url: "<?=base_url('Dashboard/enquiryLeadClientChart')?>",
                    type: "post",
                    data:{datas:data_s},
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 'success') {
                            am4core.ready(function() {
                                // Themes begin
                                am4core.useTheme(am4themes_animated);
                                // Themes end
                                var chart = am4core.create("chartdiv", am4charts.SlicedChart);
                                chart.hiddenState.properties.opacity = 0; 
                                chart.data = data.data;
                                var series = chart.series.push(new am4charts.FunnelSeries());
                                series.colors.step = 2;
                                series.calculatePercent = true;
                                series.dataFields.value = "value";
                                series.dataFields.category = "name";
                                series.alignLabels = true;
                                series.labelsContainer.paddingLeft = 15;
                                series.labelsContainer.width = 200;
                                chart.legend = new am4charts.Legend();                                
                                chart.legend.labels.template.truncate = false;
                                chart.legend.position = "left";
                                chart.legend.valign = "bottom";
                                chart.legend.margin(5, 5, 20, 5);
                            });
                        }
                    }
                });
            })
            // end am4core.ready()
            </script>
            <!-- monthwise chart starts -->
            <script>
            $(document).ready(function() {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';
                $.ajax({
                    url: "<?=base_url('Dashboard/monthWiseChart')?>",
                    type: "post",
                    data:{datas:data_s},
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 'success') {
                            new Chart(document.getElementById("bar-chart-grouped"), {
                                type: 'bar',
                                data: {
                                    labels: ["JUN", "FEB", "MAR", "APR", "MAY", "JUNE",
                                        "JULY", "AUG", "SEP", "OCT", "NOV", "DEC"
                                    ],
                                    datasets: [
                                        <?php if(user_access(60)){ ?> {
                                            label: "<?=display("enquiry") ?>",
                                            backgroundColor: "#3e95cd",
                                            data: [parseInt(data.data.ejan), parseInt(
                                                    data.data.efeb), parseInt(data
                                                    .data.emar), parseInt(data.data
                                                    .eapr), parseInt(data.data
                                                .emay), parseInt(data.data.ejun),
                                                parseInt(data.data.ejuly), parseInt(
                                                    data.data.eaug), parseInt(data
                                                    .data.esep), parseInt(data.data
                                                    .eoct), parseInt(data.data
                                                .enov), parseInt(data.data.edec)
                                            ]
                                        },
                                        <?php } if(user_access(70)){ ?> {
                                            label: "<?=display("lead") ?>",
                                            backgroundColor: "#8e5ea2",
                                            data: [parseInt(data.data.ljan), parseInt(
                                                    data.data.lfeb), parseInt(data
                                                    .data.lmar), parseInt(data.data
                                                    .lapr), parseInt(data.data
                                                .lmay), parseInt(data.data.ljun),
                                                parseInt(data.datajuly), parseInt(
                                                    data.data.laug), parseInt(data
                                                    .data.lsep), parseInt(data.data
                                                    .loct), parseInt(data.data
                                                .lnov), parseInt(data.data.ldec)
                                            ]
                                        },
                                        <?php } 
                 if(user_access(80)){
                ?> {
                                            label: "<?=display("client") ?>",
                                            backgroundColor: "#c45850",
                                            data: [parseInt(data.data.cjan), parseInt(
                                                    data.data.cfeb), parseInt(data
                                                    .data.cmar), parseInt(data.data
                                                    .capr), parseInt(data.data
                                                .cmay), parseInt(data.data.cjun),
                                                parseInt(data.data.cjuly), parseInt(
                                                    data.data.caug), parseInt(data
                                                    .data.csep), parseInt(data.data
                                                    .coct), parseInt(data.data
                                                .cnov), parseInt(data.data.cdec)
                                            ]
                                        },
                                        <?php } ?>
                                        <?php 
                                        if(user_access(553)){ 
        $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');

                 if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);

                      foreach ($enquiry_separation as $key => $value) {
                              $ctitle = $enquiry_separation[$key]['title']; 
                        $count = $this->enquiry_model->DYmonthWiseChart($this->session->user_id,$this->session->companey_id,$key);
                            ?> {
                                            label: "<?= $ctitle ?>",
                                            backgroundColor: "<?=  sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>",
                                            data: [<?= $count['ejan']?>,
                                                <?= $count['efeb']?>,
                                                <?= $count['emar']?>,
                                                <?= $count['eapr']?>,
                                                <?= $count['emay']?>,
                                                <?= $count['ejun']?>,
                                                <?= $count['ejuly']?>,
                                                <?= $count['eaug']?>,
                                                <?= $count['esep']?>,
                                                <?= $count['eoct']?>,
                                                <?= $count['enov']?>,
                                                <?= $count['edec']?>,
                                            ],
                                        },

                                        <?php 

                      }

                    }

                } 
                ?>
                                    ]
                                },
                                options: {
                                    title: {
                                        display: true,
                                        //text: 'Vertical Bar Graph'
                                    }
                                }
                            });
                        }
                    }
                })
            })
            </script>

            <!-- monthwise chart ends -->

            <!-- drop wise chart start here -->
            <script>
            $(document).ready(function() {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';


                $.ajax({
                    url: "<?=base_url('Dashboard/dropDataChart')?>",
                    type: "post",
                    data:{datas:data_s},
                    dataType: "json",
                    success: function(data) {
                        var data1 = [];
                        var data2 = [];
                        var data3 = [];
                        var data4 = [];

                        if (data.status == 'success') {
                            for (var i = 0; i < data.enquiryChartData.length; i++) {
<?php if(user_access(60)){ ?> 

                                data1.push(parseInt(data.enquiryChartData[i]));
                                <?php } if(user_access(70)){ ?> 

                                data2.push(parseInt(data.leadChartData[i]));
<?php } if(user_access(80)){ ?> 

                                data3.push(parseInt(data.clientChartData[i]));
                                <?php }  ?> 

                                data4.push(data.droplst[i]['drop_reason']);
                            }
                            Highcharts.chart('container2', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: '<?php echo display("dropdata"); ?>'
                                },
                                xAxis: {
                                    categories: data4,
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'Total drop data'
                                    },
                                    stackLabels: {
                                        enabled: true,
                                        style: {
                                            fontWeight: 'bold',
                                            color: ( // theme
                                                Highcharts.defaultOptions.title.style &&
                                                Highcharts.defaultOptions.title.style
                                                .color
                                            ) || 'gray'
                                        }
                                    }
                                },
                                legend: {
                                    align: 'right',
                                    x: -30,
                                    verticalAlign: 'top',
                                    y: 25,
                                    floating: true,
                                    backgroundColor: Highcharts.defaultOptions.legend
                                        .backgroundColor || 'white',
                                    borderColor: '#CCC',
                                    borderWidth: 1,
                                    shadow: false
                                },
                                tooltip: {
                                    headerFormat: '<b>{point.x}</b><br/>',
                                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                                },
                                plotOptions: {
                                    column: {
                                        stacking: 'normal',
                                        dataLabels: {
                                            enabled: true
                                        }
                                    }
                                },
                                series: [
<?php if(user_access(60)){ ?> 
                                    
                                    {
                                        name: '<?php  echo display("enquiry"); ?>',
                                        data: data1,
                                    },
<?php } if(user_access(70)){ ?> 
                                     {
                                        name: '<?php echo display("lead"); ?>',
                                        data: data2,
                                    },
<?php } if(user_access(80)){ ?> 
                                     {
                                        name: '<?php echo display("Client"); ?>',
                                        data: data3,
                                    },
<?php } if(user_access(553)){ ?> 

                                    <?php   $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                 if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);
                      foreach ($enquiry_separation as $key => $value) {
                               $ctitle = $enquiry_separation[$key]['title']; 
                               $count = $this->enquiry_model->DydropDataChart($this->session->user_id,$this->session->companey_id,$key);
                         ?> {
                                        name: "<?= $ctitle ?>",
                                        data: [<?= implode(',',$count); ?>]
                                    },
                                    <?php } }  } ?>

                                ]
                            });
                        }
                    }
                })
            });
            </script>

            <!-- drop wise chart ends here -->


            <!-- conversion probability chart starts-->
            <script>
            $(document).ready(function() {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';


                $.ajax({
                    url: "<?=base_url('Dashboard/conversionProbabilityChart')?>",
                    type: "post",
                    data:{datas:data_s},
                    dataType: "json",
                    success: function(res) {
                        am4core.ready(function() {

                            // Themes begin
                            am4core.useTheme(am4themes_animated);
                            // Themes end

                            // Create chart instance
                            var chart = am4core.create("chartdiv2", am4charts.PieChart);

                            // Add data
                            chart.data = res.data;

                            // Add and configure Series
                            var pieSeries = chart.series.push(new am4charts.PieSeries());
                            pieSeries.dataFields.value = "litres";
                            pieSeries.dataFields.category = "country";
                            pieSeries.slices.template.stroke = am4core.color("#fff");
                            pieSeries.slices.template.strokeWidth = 2;
                            pieSeries.slices.template.strokeOpacity = 1;

                            // This creates initial animation
                            pieSeries.hiddenState.properties.opacity = 1;
                            pieSeries.hiddenState.properties.endAngle = -90;
                            pieSeries.hiddenState.properties.startAngle = -90;

                        });
                    }
                })
            });
            // end am4core.ready()
            </script>
            <!-- conversion probability chart ends-->
            <!--------------------------------------------process wise data graph ------------------------------->



            <script>
            $(document).ready(function(e) {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';


              
            });
            </script>
            <!--------------------------------------------process wise data graph End------------------------------->
            <!------------------------------------------------------------calendar------------------------------------->
            <script type="text/javascript">
            // sample calendar events data
            $(document).ready(function() {
                var curYear = moment().format('YYYY');
                var curMonth = moment().format('MM');
                // Calendar Event Source
                var calendarEvents = {
                    backgroundColor: 'rgba(1,104,250, .15)',
                    borderColor: '#0168fa',
                    events: [
                        <?php
                        $cnt=1; 
                        foreach($taskdata as $task){
                            $t_date = $task->task_date;
                        if(!empty($task->subject)){
                            $ttl = $task->subject;  
                        }else{
                            $ttl = 'None';  
                        }
                        if(!empty($task->task_remark)){
                            $remrk = $task->task_remark;  
                        }else{
                            $remrk = 'None';  
                        } 
                        $newDate = date("Y-m-d", strtotime($t_date));
                        ?> {
                            id: <?= $cnt;?>,
                            start: '<?php echo $newDate; ?>',
                            end: '<?php echo $newDate; ?>',
                            title: '<?php echo $ttl; ?>',
                            description: ''
                        },
                        <?php  $cnt++; } ?>
                    ]
                };
                $("input[name='task_filter']").on('change',function(){
                    id = $(this).val();                
                    var events = {
                        url: "<?php echo base_url().'task/get_calandar_feed?for=1'?>",
                        type: 'POST',
                        data: {
                            start: $('#calendar').fullCalendar('getView').start,
                            end: $('#calendar').fullCalendar('getView').end,
                            user_id:"<?=$this->session->user_id?>",
                            filter: $('input[name=task_filter]:checked').map(function(){
                                return this.value;
                                }).get()
                        }
                    }
                    //remove old data
                    $('#calendar').fullCalendar('removeEvents');       
                    //Getting new event json data
                    $("#calendar").fullCalendar('addEventSource', events);
                    //Updating new events
                    $('#calendar').fullCalendar('rerenderEvents');                          
                });
                var pendingEvents = {
                    backgroundColor: 'rgba(16,183,89, .25)',
                    borderColor: '#10b759',
                    events: [

                    ]
                };
                var waitEvents = {
                    backgroundColor: 'rgba(241,0,117,.25)',
                    borderColor: '#f10075',
                    events: [

                    ]
                };
                var notapprovedEvents = {
                    backgroundColor: 'rgba(253,126,20,.25)',
                    borderColor: '#fd7e14',
                    events: [

                    ]
                };

                // ..............................................................
                'use strict'

                // Initialize tooltip
                $('[data-toggle="tooltip"]').tooltip()

                // Sidebar calendar
                $('#calendarInline').datepicker({
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    beforeShowDay: function(date) {

                        // add leading zero to single digit date
                        var day = date.getDate();
                        console.log(day);
                        return [true, (day < 10 ? 'zero' : '')];
                    }
                });

                // Initialize fullCalendar
                $('#calendar').fullCalendar({
                    height: 'parent',
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listWeek'
                    },
                    navLinks: true,
                    selectable: true,
                    selectLongPressDelay: 100,
                    editable: true,
                    nowIndicator: true,
                    defaultView: 'listMonth',
                    eventLimit: 2,
                    views: {
                        agenda: {
                            columnHeaderHtml: function(mom) {
                                return '<span>' + mom.format('ddd') + '</span>' +
                                    '<span>' + mom.format('DD') + '</span>';
                            }
                        },
                        day: {
                            columnHeader: false
                        },
                        listMonth: {
                            listDayFormat: 'ddd DD',
                            listDayAltFormat: false
                        },
                        listWeek: {
                            listDayFormat: 'ddd DD',
                            listDayAltFormat: false
                        },
                        agendaThreeDay: {
                            type: 'agenda',
                            duration: {
                                days: 3
                            },
                            titleFormat: 'MMMM YYYY'
                        }
                    },

                    // eventSources: [
                    //     {
                    //         url: "<?php echo base_url().'task/get_calandar_feed?for=1'?>",
                    //         type: 'POST',
                    //         data: {
                    //             start: $('#calendar').fullCalendar('getView').start,
                    //             end: $('#calendar').fullCalendar('getView').end,
                    //             user_id:"<?=$this->session->user_id?>",
                    //             filter: $('input[name=task_filter]:checked').map(function(){
                    //                 return this.value;
                    //             }).get()
                    //         }
                    //     }, 
                    //     pendingEvents, 
                    //     waitEvents, 
                    //     notapprovedEvents
                    // ],
                    events    : function(start, end, timezone, callback) {                        
                        jQuery.ajax({
                            url: "<?php echo base_url().'task/get_calandar_feed?for=1'?>",
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                start: start.format(),
                                end: end.format(),
                                user_id:"<?=$this->session->user_id?>",
                                filter: $('input[name=task_filter]:checked').map(function(){
                                    return this.value;
                                }).get()
                            },
                            success: function(doc) { 
                                var events = doc;                
                                callback(events);
                            }
                        });
                    },
                    eventAfterAllRender: function(view) {
                        if (view.name === 'listMonth' || view.name === 'listWeek') {
                            var dates = view.el.find('.fc-list-heading-main');
                            dates.each(function() {
                                var text = $(this).text().split(' ');
                                var now = moment().format('DD');

                                $(this).html(text[0] + '<span>' + text[1] + '</span>');
                                if (now === text[1]) {
                                    $(this).addClass('now');
                                }
                            });
                        }

                        console.log(view.el);
                    },
                    eventRender: function(event, element) {
                        
                        if (event.description) {
                            element.find('.fc-list-item-title').append('<span class="fc-desc">' +
                                event.description + '</span>');
                            element.find('.fc-content').append('<span class="fc-desc">' + event
                                .description + '</span>');
                        }

                        var eBorderColor = (event.source.borderColor) ? event.source.borderColor :
                            event.borderColor;
                        element.find('.fc-list-item-time').css({
                            color: eBorderColor,
                            borderColor: eBorderColor
                        });

                        element.find('.fc-list-item-title').css({
                            borderColor: eBorderColor
                        });

                        element.css('borderLeftColor', eBorderColor);
                    },
                });

                var calendar = $('#calendar').fullCalendar('getCalendar');

                // change view to week when in tablet
                if (window.matchMedia('(min-width: 576px)').matches) {
                    calendar.changeView('agendaWeek');
                }

                // change view to month when in desktop
                if (window.matchMedia('(min-width: 992px)').matches) {
                    calendar.changeView('month');
                }

                // change view based in viewport width when resize is detected
                calendar.option('windowResize', function(view) {
                    if (view.name === 'listWeek') {
                        if (window.matchMedia('(min-width: 992px)').matches) {
                            calendar.changeView('month');
                        } else {
                            calendar.changeView('listWeek');
                        }
                    }
                });


                $('.select2-modal').select2({
                    minimumResultsForSearch: Infinity,
                    dropdownCssClass: 'select2-dropdown-modal',
                });

                $('.calendar-add').on('click', function(e) {
                    e.preventDefault()

                    $('#modalCreateEvent').modal('show');
                });

            });

            // ....................................calander end
            </script>

            <!-- desposition graph start here -->
            <script>
            $(document).ready(function() {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';


            })
            </script>

            <!-- desposition graph ends here -->


            <!-- source graph start here -->
            <script>
            $(document).ready(function() {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';
                $.ajax({
                    url: "<?=base_url('Dashboard/sourceDataChart')?>",
                    type: "post",
                    data:{datas:data_s},

                    dataType: "json",
                    success: function(data) {
                        var data1 = [];
                        var data2 = [];
                        var data3 = [];
                        var data4 = [];

                        if (data.status == 'success') {
                            //response = JSON.parse(data);
                            console.log(data.enquiryChartData[0]);
                            for (var i = 0; i < data.enquiryChartData.length; i++) {
                                <?php   if(user_access(60)){ ?> 

                                data1.push(parseInt(data.enquiryChartData[i]));
                                <?php  } if(user_access(70)){ ?> 

                                data2.push(parseInt(data.leadChartData[i]));
                                <?php  } if(user_access(80)){ ?> 

                                data3.push(parseInt(data.clientChartData[i]));
                                <?php } ?>
                                data4.push(data.srclst[i]['lead_name']);
                            }
                            Highcharts.chart('container1', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Source'
                                },
                                subtitle: {
                                    //text: 'Source: WorldClimate.com'
                                },
                                xAxis: {
                                    categories: data4,
                                    crosshair: true
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'Data (No.)'
                                    }
                                },
                                tooltip: {
                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                                    footerFormat: '</table>',
                                    shared: true,
                                    useHTML: true
                                },
                                plotOptions: {
                                    column: {
                                        pointPadding: 0.2,
                                        borderWidth: 0
                                    }
                                },
                                series: [
                                    <?php if(user_access(60)){ ?> {
                                        name: '<?php echo display("enquiry"); ?>',
                                        data: data1,

                                    },
                                    <?php } 
                                    if(user_access(70)){ ?> {
                                        name: '<?php echo display("lead"); ?>',
                                        data: data2,
                                    },
                                    <?php } 
                                        if(user_access(80)){
                                        ?> {
                                        name: '<?php echo display("Client"); ?>',
                                        data: data3,
                                    },
                                    <?php }  
                                if(user_access(553)){ 
                                    $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                 if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);
                      foreach ($enquiry_separation as $key => $value) {
                               $ctitle = $enquiry_separation[$key]['title']; 
                               $count = $this->enquiry_model->dysourceDataChart($this->session->user_id,$this->session->companey_id,$key);
                         ?> {
                                        name: "<?= $ctitle ?>",
                                        data: [<?= implode(',',$count); ?>]
                                    },
                                    <?php } } } ?>
                                ]
                            });
                        }
                    }
                })
            })
            </script>
            <!-- source graph ends here -->
            <!------------------------------------------timline JS---------------------------------------------------->
   
<!-- Target Forecasting graph sg -->

<?php
if(user_access('260') || user_access('261') || user_access('250'))
{
?>
<script type="text/javascript">
$(document).ready(function(){

try{
var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';

$.ajax({
    url:'<?=base_url('target/current_year_target_forecast')?>',
    type:'post',
    data:{datas:data_s},
    success:function(q)
    {   var j = JSON.parse(q);
        var fore = j[0].forecast.toString().split(',');
        var ach = j[0].achieved.toString().split(',');
       
         new Chart(document.getElementById("target_forecasting"), {
                                type: 'bar',
                                data: {
                                    labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUNE",
                                        "JULY", "AUG", "SEP", "OCT", "NOV", "DEC"
                                    ],
                                    datasets: [
                                        {
                                            label: "Forecast",
                                            backgroundColor: "#3e95cd",
                                            data: fore
                                        },
                                        {
                                            label: "Achieved",
                                            backgroundColor: "#84edb1",
                                            data:ach
                                        },

                                    ]
                                },
                                options: {
                                            title: {
                                                display: true,
                                                //text: 'Vertical Bar Graph'
                                            }
                                        }
                            });

        var p_fore = j[1].forecast.toString().split(',');
        var p_ach = j[1].achieved.toString().split(',');

         new Chart(document.getElementById("target_forecasting_previous"), {
                                type: 'bar',
                                data: {
                                    labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUNE",
                                        "JULY", "AUG", "SEP", "OCT", "NOV", "DEC"
                                    ],
                                    datasets: [
                                        {
                                            label: "Forecast <?=date('Y')?>",
                                            backgroundColor: "#7c71a5",
                                            data: fore
                                        },
                                        {
                                            label: "Forecast <?=date('Y')-1?>",
                                            backgroundColor: "#b0abc0",
                                            data: p_fore
                                        },
                                        {
                                            label: "Achieved <?=date('Y')?>",
                                            backgroundColor: "#49d44e",
                                            data:ach
                                        },
                                        {
                                            label: "Achieved <?=date('Y')-1?>",
                                            backgroundColor: "#98e1b6",
                                            data:p_ach
                                        },
                                        
                                    ]
                                },
                                options: {
                                            title: {
                                                display: true,
                                                //text: 'Vertical Bar Graph'
                                            }
                                        }
                            });
    }
});

}catch(e){alert(e);}


});



</script>
<?php
}
?>
<!--  -->
  
<!-- Target Forecasting graph sg -->

<?php
if(user_access('260') || user_access('261') || user_access('250'))
{
?>
<script type="text/javascript">
$(document).ready(function(){

try{
var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';

$.ajax({
    url:'<?=base_url('target/current_year_target_forecast')?>',
    type:'post',
    data:{datas:data_s},
    success:function(q)
    {   var j = JSON.parse(q);
        var fore = j[0].forecast.toString().split(',');
        var ach = j[0].achieved.toString().split(',');
       
         new Chart(document.getElementById("target_forecasting"), {
                                type: 'bar',
                                data: {
                                    labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUNE",
                                        "JULY", "AUG", "SEP", "OCT", "NOV", "DEC"
                                    ],
                                    datasets: [
                                        {
                                            label: "Forecast",
                                            backgroundColor: "#3e95cd",
                                            data: fore
                                        },
                                        {
                                            label: "Achieved",
                                            backgroundColor: "#84edb1",
                                            data:ach
                                        },

                                    ]
                                },
                                options: {
                                            title: {
                                                display: true,
                                                //text: 'Vertical Bar Graph'
                                            }
                                        }
                            });

        var p_fore = j[1].forecast.toString().split(',');
        var p_ach = j[1].achieved.toString().split(',');

         new Chart(document.getElementById("target_forecasting_previous"), {
                                type: 'bar',
                                data: {
                                    labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUNE",
                                        "JULY", "AUG", "SEP", "OCT", "NOV", "DEC"
                                    ],
                                    datasets: [
                                        {
                                            label: "Forecast <?=date('Y')?>",
                                            backgroundColor: "#7c71a5",
                                            data: fore
                                        },
                                        {
                                            label: "Forecast <?=date('Y')-1?>",
                                            backgroundColor: "#b0abc0",
                                            data: p_fore
                                        },
                                        {
                                            label: "Achieved <?=date('Y')?>",
                                            backgroundColor: "#49d44e",
                                            data:ach
                                        },
                                        {
                                            label: "Achieved <?=date('Y')-1?>",
                                            backgroundColor: "#98e1b6",
                                            data:p_ach
                                        },
                                        
                                    ]
                                },
                                options: {
                                            title: {
                                                display: true,
                                                //text: 'Vertical Bar Graph'
                                            }
                                        }
                            });
    }
});

}catch(e){alert(e);}


});



</script>
<?php
}
?>
<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
</style>

<script>
            $(document).ready(function() {
                var data_s= '<?php  if(!empty($filterData)){ echo $filterData; } ?>';


                $.ajax({
                    url: "<?=base_url('Dashboard/process_Monthwise')?>",
                    type: "post",
                    data:{datas:data_s},
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 'success') {
                            new Chart(document.getElementById("process_Monthwise"), {
                                type: 'bar',
                                data: {
                                    labels: ["JUN", "FEB", "MAR", "APR", "MAY", "JUNE",
                                        "JULY", "AUG", "SEP", "OCT", "NOV", "DEC"
                                    ],
                                    datasets: [
                                        <?php if(user_access(60)){ ?> {
                                            label: "<?=display("enquiry") ?>",
                                            backgroundColor: "#3e95cd",
                                            data: [parseInt(data.data.ejan), parseInt(
                                                    data.data.efeb), parseInt(data
                                                    .data.emar), parseInt(data.data
                                                    .eapr), parseInt(data.data
                                                .emay), parseInt(data.data.ejun),
                                                parseInt(data.data.ejuly), parseInt(
                                                    data.data.eaug), parseInt(data
                                                    .data.esep), parseInt(data.data
                                                    .eoct), parseInt(data.data
                                                .enov), parseInt(data.data.edec)
                                            ]
                                        },
                                        <?php } if(user_access(70)){ ?> {
                                            label: "<?=display("lead") ?>",
                                            backgroundColor: "#8e5ea2",
                                            data: [parseInt(data.data.ljan), parseInt(
                                                    data.data.lfeb), parseInt(data
                                                    .data.lmar), parseInt(data.data
                                                    .lapr), parseInt(data.data
                                                .lmay), parseInt(data.data.ljun),
                                                parseInt(data.datajuly), parseInt(
                                                    data.data.laug), parseInt(data
                                                    .data.lsep), parseInt(data.data
                                                    .loct), parseInt(data.data
                                                .lnov), parseInt(data.data.ldec)
                                            ]
                                        },
                                        <?php } 
                 if(user_access(80)){
                ?> {
                                            label: "<?=display("client") ?>",
                                            backgroundColor: "#c45850",
                                            data: [parseInt(data.data.cjan), parseInt(
                                                    data.data.cfeb), parseInt(data
                                                    .data.cmar), parseInt(data.data
                                                    .capr), parseInt(data.data
                                                .cmay), parseInt(data.data.cjun),
                                                parseInt(data.data.cjuly), parseInt(
                                                    data.data.caug), parseInt(data
                                                    .data.csep), parseInt(data.data
                                                    .coct), parseInt(data.data
                                                .cnov), parseInt(data.data.cdec)
                                            ]
                                        },
                                        <?php } ?>
                                        <?php  if(user_access(553)){ 

        $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');

                 if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);

                      foreach ($enquiry_separation as $key => $value) {
                              $ctitle = $enquiry_separation[$key]['title']; 
                        $count = $this->enquiry_model->DYdropmonthWiseChart($this->session->user_id,$this->session->companey_id,$key);
                            ?> {            label: "<?= $ctitle ?>",
                                            backgroundColor: "<?=  sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>",
                                            data: [<?= $count['ejan']?>,
                                                <?= $count['efeb']?>,
                                                <?= $count['emar']?>,
                                                <?= $count['eapr']?>,
                                                <?= $count['emay']?>,
                                                <?= $count['ejun']?>,
                                                <?= $count['ejuly']?>,
                                                <?= $count['eaug']?>,
                                                <?= $count['esep']?>,
                                                <?= $count['eoct']?>,
                                                <?= $count['enov']?>,
                                                <?= $count['edec']?>,
                                            ],
                                        },
                                        <?php    }  }  }  ?>
                                    ]
                                },
                                options: {
                                    title: {
                                        display: true,
                                        //text: 'Vertical Bar Graph'
                                    }
                                }
                            });
                        }
                    }
                })
            })
            $("select[name='region']").on('change', function(){
                var region = $(this).val();                
                $.ajax({
                    url:'<?=base_url('dashboard/get_user_by_region')?>',
                    type:'post',
                    data:{region_id:region},
                    success:function(q){
                        $("select[name='users']").html(q);
                    }
                });
            });
            </script>

            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/moment.min.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/fullcalendar.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/js/knob.js"></script>
            <?php } ?>

