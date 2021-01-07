<?php         $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING'); ?>
<!-- <link rel="stylesheet" href="<?php echo base_url()?>custom_dashboard/assets/css/dashforge.css"> -->
<link rel="stylesheet" href="<?php echo base_url()?>custom_dashboard/assets/css/dashforge.dashboard.css">
<link href="<?php echo base_url()?>custom_dashboard/lib/morris.js/morris.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/aqua.css">
<link href="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/fullcalendar.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/dashforge.calendar.css" rel="stylesheet">



<style>
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
    <?php if(user_access(541)) { ?>

    <div class="row">
        <a href="<?= base_url('ticket/dashboard') ?>"><button style=" float:right" class="btn btn-primary">
                Ticket Dashboard
            </button></a>
    </div>

    <?php } if($this->session->userdata('user_right')==151) { ?>
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
	<div class="col-lg-2">
        <div class="form-group">
          <label>From</label>
          <input class="d_filter form-control form-date" name="from_date" id="from_date" value="<?php if(!empty($_POST['from_date'])){ echo $this->input->post('from_date'); }?>">
        
        </div>
    </div>

      <div class="col-lg-2">
        <div class="form-group">
          <label>To</label>
           <input  class="d_filter form-control form-date" name="to_date" value="<?php if(!empty($_POST['to_date'])){ echo $this->input->post('to_date'); }?>">
        </div>
      </div>

    <div class="col-lg-2">
        <div class="form-group">
            <label>User</label>
            <?php 
        $all_reporting_ids    =   $this->common_model->get_categories($this->session->user_id);
            $where = "pk_i_admin_id IN (".implode(',', $all_reporting_ids).")";
               $users =$this->db->where($where)->get('tbl_admin');
            ?>
            <select name="users" class="form-control">
                <?php   foreach ($users->result() as $key => $value) {  ?>
                    <option value="<?= $value->pk_i_admin_id ?>"><?= $value->s_display_name ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
     <div class="col-lg-2">
        <div class="form-group">

        	<label>State</label>
                        <label> <?php echo display("state"); ?> <i class="text-danger"></i></label>
                        <select name="state_id" class="form-control" id="fstate">
                           <option value="" >--Select--</option>
                           <?php foreach($state_list as $state){?>
                           <option  value="<?php echo $state->id ?>" <?php if(!empty($_POST['city_id'])){ if($_POST['state_id']==$state->id){echo'selected';} }?>><?php echo $state->state; ?></option>
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
                           <option  value="<?php echo $city->id ?>" <?php if(!empty($_POST['city_id'])){if($_POST['city_id']==$city->id){echo'selected';} }?>><?php echo $city->city; ?></option>
                           <?php } ?>
                                
                            <?php } ?>
                        </select>
                     </div>
                     </div>
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
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-purple">
                        <span class="info-box-icon"><i class="fa fa-question-circle-o" style="color:#fff;"></i></span>
                        <div class="info-box-content1">
                            <div class="box box-widget widget-user-2">
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked">
                                        <li><a href="#"><?php echo display("all_enquiry"); ?> <span
                                                    class="pull-right badge bg-blue"><?php if(!empty($counts['enquiry'])){ echo $counts['enquiry'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?php if(!empty($counts['enq_ct'])){ echo $counts['enq_ct'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?php if(!empty($counts['enq_ut'])){ echo $counts['enq_ut'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['enquiry'])){ echo ($counts['enquiry']-$counts['enq_drp']);}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?php if(!empty($counts['enq_drp'])){ echo $counts['enq_drp'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("unassigned"); ?> <span
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
                                        <li><a href="#"><?php echo display("all_leads"); ?> <span
                                                    class="pull-right badge bg-blue"><?php if(!empty($counts['lead'])){ echo $counts['lead'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?php if(!empty($counts['lead_ct'])){ echo $counts['lead_ct'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?php if(!empty($counts['lead_ut'])){ echo $counts['lead_ut'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['lead'])){ echo ($counts['lead']-$counts['lead_drp']);}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?php if(!empty($counts['lead_drp'])){ echo $counts['lead_drp'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("unassigned"); ?> <span
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
                                        <li><a href="#"><?php echo display("all_clients"); ?> <span
                                                    class="pull-right badge bg-blue"><?php if(!empty($counts['client'])){ echo $counts['client'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?php if(!empty($counts['client_ct'])){ echo $counts['client_ct'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?php if(!empty($counts['client_ut'])){ echo $counts['client_ut'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['client'])){ echo ($counts['client']-$counts['client_drp']);}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?php if(!empty($counts['client_drp'])){ echo $counts['client_drp'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("unassigned"); ?> <span
                                                    class="pull-right badge bg-maroon"><?php if(!empty($counts['client_assign'])){ echo $counts['client_assign'];}else{ echo '0';}; ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php

if (!empty($enquiry_separation)) {
  $enquiry_separation = json_decode($enquiry_separation, true);
      foreach ($enquiry_separation as $key => $value) {   
        
              $ctitle = $enquiry_separation[$key]['title']; 
              $data_s = $this->enquiry_model->Dy_enquiryLeadClientCount($this->session->user_id,$this->session->companey_id,$key);
                   ?>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="<?= $enquiry_separation[$key]['icon'] ?>"
                                style="color:#fff;"></i></span>
                        <div class="info-box-content1">
                            <div class="box box-widget widget-user-2">
                                <div class="box-footer no-padding">

                                    <ul class="nav nav-stacked">
                                        <li><a href="#">All <?php echo $ctitle ?> <span
                                                    class="pull-right badge bg-blue"><?= $data_s['enquiry'] ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("created_today"); ?> <span
                                                    class="pull-right badge bg-aqua"><?= $data_s['enq_ct'] ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("updated_today"); ?> <span
                                                    class="pull-right badge bg-green"><?= $data_s['enq_ut'] ?></span></a>
                                        </li>
                                        <li><a href="#"><?php echo display("active"); ?> <span
                                                    class="pull-right badge bg-red"><?php if(!empty($counts['enquiry'])){ echo $data_s['enquiry']-$data_s['enq_drp']; }else{echo '0';} ?>
                                                </span></a></li>
                                        <li><a href="#"><?php echo display("droped"); ?> <span
                                                    class="pull-right badge bg-purple"><?= $data_s['enq_drp'] ?>
                                                </span></a></li>
                                        <li><a href="#"><?php echo display("unassigned"); ?> <span
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
            </div>

            <!------------------------------------------------------------------html widget End-------------------------------------------->

            <!------------------------------------------------------------------html FUNNEL START-------------------------------------------->

            <div class="row row-xs">
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>



                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <div class="card">
                        <style>
                        #chartdiv {
                            width: 100%;
                            height: 510px;
                        }
                        </style>
                        <div id="chartdiv"></div>
                    </div>
                </div>

                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>
                <div class="col-lg-8 col-xl-7 mg-t-10">
                    <div class="card" style="height:100% !important;">
                        <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                            <h3 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"
                                style="font-size:14px"><?php echo display("enquiries"); ?></h3>
                        </div><!-- card-header -->
                        <canvas id="bar-chart-grouped" width="800" height="450"></canvas>
                    </div><!-- card -->
                </div>
                <div class="col-lg-4 col-xl-4 mg-t-10">
                    <div class="card" style="height:100%;">
                        <div class="card-header pd-y-20 d-md-flex align-items-center">
                            <h3 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"
                                style="font-size:14px"><?php echo display("conversion_probability"); ?></h3>
                        </div>
                       <div id="chartdiv2" style="margin-top:100px;"></div>
                    </div>
                </div>

                <!----------------------------------------------------------------------Process wise charts------------------------------------------>
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>

                <div class="col-lg-4 col-xl-4 mg-t-10">
                    <div class="card" style="height:100%;">
                        <figure class="highcharts-figure">
                            <div id="containersss"></div>
                        </figure>

                    </div>


                </div>

                <div class="col-lg-8 col-xl-8 mg-t-10">
                    <div class="card" style="height:100%;">

                        <figure class="highcharts-figure">
                            <div id="container2"></div>
                        </figure>

                    </div>


                </div>
                <!------------------------Process wise charts End------------------------------------------->
                <!--------------------------------html FUNNEL START END-------------------------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid red !important">
                </div>

                <!------------------------------------------------------------------HTML Map/Calender START-------------------------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <div class="card" style="height:95%;">

                        <style>
                        #calendar {
                            height: 500px;
                        }

                        .fc-title {
                            color: black;
                        }
                        </style>
                        <div id="calendar" class=""></div>

                    </div>
                </div>

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
                <!------------------------------------------------------------------html map/Calender END-------------------------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid red !important">
                </div>

                <!------------------------------------------------------------------Disposition/Source START-------------------------------------------->
                <div class="col-lg-6 col-xl-6 mg-t-10">
                    <div class="card" style="height:95%;">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>

                    </div>
                </div>

                <div class="col-lg-6 col-xl-6 mg-t-10">
                    <div class="card" style="height:95%;">
                        <figure class="highcharts-figure">
                            <div id="container1"></div>
                        </figure>

                    </div>
                </div>
                <!------------------------------------------------------------------Disposition/Source END-------------------------------------------->
                <!------------------------------------------------------------------html map/Calender END-------------------------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid red !important">
                </div>

                <!------------------------------------------------------------------Timeline START-------------------------------------------->
                <?php


//print_r($eldate);exit;
?>
                <div class="col-lg-6 col-xl-12 mg-t-10">
                    <div class="card" style="height:100%;">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="page-header">
                                        <h1><?php echo display('average_follow_up_rate'); ?></h1>
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
                 $minutes= round(($stime)/$Count,0);
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
                                            <?php } } ?>


                                            <!-- // dynamic case end -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!------------------------------------------------------------------Timeline END-------------------------------------------->
                <div class="col-lg-12 col-xl-12 mg-t-10">
                    <hr style="border: 1px solid #3a95e4 !important">
                </div>
                <!-------------------------------------------------------Grapth JS --------------------------------------------->



                <!-----------------------------------------------------Graph JS End------------------------------------------------------------>

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
                // alert(data_s);
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

                                var chart = am4core.create("chartdiv", am4charts
                                    .SlicedChart);
                                chart.hiddenState.properties.opacity =
                                0; // this makes initial fade in effect

                                chart.data = data.data;

                                var series = chart.series.push(new am4charts
                            .FunnelSeries());
                                series.colors.step = 2;
                                series.dataFields.value = "value";
                                series.dataFields.category = "name";
                                series.alignLabels = true;

                                series.labelsContainer.paddingLeft = 15;
                                series.labelsContainer.width = 200;

                                //series.orientation = "horizontal";
                                //series.bottomRatio = 1;

                                chart.legend = new am4charts.Legend();
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
                                data1.push(parseInt(data.enquiryChartData[i]));
                                data2.push(parseInt(data.leadChartData[i]));
                                data3.push(parseInt(data.clientChartData[i]));
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
                                series: [{
                                        name: '<?php  echo display("enquiry"); ?>',
                                        data: data1,
                                    }, {
                                        name: '<?php echo display("lead"); ?>',
                                        data: data2,
                                    }, {
                                        name: '<?php echo display("Client"); ?>',
                                        data: data3,
                                    },
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
                                    <?php } }  ?>

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


                $.ajax({
                    url: "<?=base_url('Dashboard/processWiseChart')?>",
                    type: "post",
                    data:{datas:data_s},
                    dataType: "json",
                    success: function(data) {
                        //response = JSON.parse(data);

                        if (data.status == 'success') {
                            Highcharts.chart('containersss', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Process Wise Data'
                                },
                                subtitle: {
                                    text: ''
                                },
                                xAxis: {
                                    categories: [
                                        '<?php echo display("enquiry"); ?>',
                                        '<?php echo display("lead"); ?>',
                                        '<?php echo display("Client"); ?>',
                                        <?php   $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                 if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);
                      foreach ($enquiry_separation as $key => $value) {
                               $ctitle = $enquiry_separation[$key]['title']; 
                         ?> "<?= $ctitle ?>",
                                        <?php } }  ?>
                                    ],
                                    crosshair: true
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'No of Data'
                                    }
                                },
                                tooltip: {
                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
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
                                series: data.data
                            });

                        }
                    }
                });
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
                        <?php $cnt=1; foreach($taskdata as $task){
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

                    eventSources: [calendarEvents, pendingEvents, waitEvents, notapprovedEvents],
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


                $.ajax({
                    url: "<?=base_url('Dashboard/despositionDataChart')?>",
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
                                data1.push(parseInt(data.enquiryChartData[i]));
                                data2.push(parseInt(data.leadChartData[i]));
                                data3.push(parseInt(data.clientChartData[i]));
                                data4.push(data.desplst[i]['lead_stage_name']);
                            }
                            Highcharts.chart('container', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Lead Stage'
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
                                series: [{
                                        name: '<?php echo display("enquiry"); ?>',
                                        data: data1,
                                    }, {
                                        name: '<?php echo display("lead"); ?>',
                                        data: data2,
                                    }, {
                                        name: '<?php echo display("Client"); ?>',
                                        data: data3,
                                    },
                                    <?php   $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                 if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);
                      foreach ($enquiry_separation as $key => $value) {
                               $ctitle = $enquiry_separation[$key]['title']; 
                               $count = $this->enquiry_model->dy2despositionDataChart($this->session->user_id,$this->session->companey_id,$key);
                         ?> {
                                        name: "<?= $ctitle ?>",
                                        data: [<?= implode(',',$count); ?>]
                                    },
                                    <?php } }  ?>
                                ]
                            });
                        }
                    }
                })
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
                                data1.push(parseInt(data.enquiryChartData[i]));
                                data2.push(parseInt(data.leadChartData[i]));
                                data3.push(parseInt(data.clientChartData[i]));
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
                                    <?php } ?>
                                    <?php   $enquiry_separation  = get_sys_parameter('enquiry_separation', 'COMPANY_SETTING');
                 if (!empty($enquiry_separation)) {
                  $enquiry_separation = json_decode($enquiry_separation, true);
                      foreach ($enquiry_separation as $key => $value) {
                               $ctitle = $enquiry_separation[$key]['title']; 
                               $count = $this->enquiry_model->dysourceDataChart($this->session->user_id,$this->session->companey_id,$key);
                         ?> {
                                        name: "<?= $ctitle ?>",
                                        data: [<?= implode(',',$count); ?>]
                                    },
                                    <?php } }  ?>
                                ]
                            });
                        }
                    }
                })
            })
            </script>
            <!-- source graph ends here -->
            <!------------------------------------------timline JS---------------------------------------------------->
   
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/moment.min.js"></script>
            <script src="<?php echo base_url()?>custom_dashboard/assets/js/amcharts/fullcalendar.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/js/knob.js"></script>
            <?php } ?>

