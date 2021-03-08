<?php
   defined('BASEPATH') OR exit('No direct script access allowed');   
   if(user_access('133')){
    $token=$this->session->userdata('login_token');
    $user_id=$this->session->user_id;
    $count=$this->user_model->checkLoginToken($user_id,$token,0);
    // print_r($this->session->userdata('login_token'));
    if($count==0){
        $this->session->sess_destroy();
        redirect('login');
    }
}
    $settings = $this->db->select("*")
    ->where('comp_id',$this->session->companey_id)
    ->get('setting')
    ->row();
   if(empty($settings)){
       $settings = $this->db->select("*")
       ->where('comp_id',0)
       ->get('setting')
       ->row();
   }       
        if(!empty($nav1)){

        }else{
          $nav1='';
        }
         $panel_menu = $this->db->select("tbl_user_role.user_permissions")
            ->where('pk_i_admin_id',$this->session->user_id)
            ->join('tbl_user_role','tbl_user_role.use_id=tbl_admin.user_permissions')
            ->get('tbl_admin')
            ->row();

            if (!empty($panel_menu->user_permissions)) {
              $module=explode(',',$panel_menu->user_permissions);
            }else{
              $module=array();
            }
            //print_r($module);
           $user_type = $this->session->userdata('user_type');
           $segment1 = $this->uri->segment(1);
           $segment2 = $this->uri->segment(2);
           $segment3 = $this->uri->segment(3);
   ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){  ?>
    <title>Space Internationals</title>
    <?php }else{ ?>
    <title><?php echo $settings->title ?></title>
    <?php } ?>
    <!--<link rel="shortcut icon" href="<?= base_url($this->session->userdata('favicon')) ?>">-->
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <link href="<?php echo base_url('assets/css/jquery-ui.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <?php 
      if (!empty($settings->site_align) && $settings->site_align == "RTL") {  
        ?>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/custom-rtl.css') ?>" rel="stylesheet" type="text/css" />
    <?php 
      } 
      ?>
    <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){  ?>
    <link rel="icon"
        href="https://spaceinternationals.com/wp-content/uploads/2018/02/cropped-SPACE-INTERNATIONALS-LOGO-02-1-32x32.jpg"
        sizes="32x32" />
    <?php }else{ ?>
    <link rel="icon" href="<?=base_url().$settings->favicon?>" sizes="32x32" />
    <?php } ?>

    <!-- Font Awesome 4.7.0 -->
    <link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <!-- semantic css -->
    <link href="<?php echo base_url(); ?>assets/css/semantic.min.css" rel="stylesheet" type="text/css" />
    <!-- sliderAccess css -->
    <link href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.min.css" rel="stylesheet"
        type="text/css" />
    <!-- slider  -->
    <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- DataTables CSS -->
    <link href="<?= base_url('assets/datatables/css/dataTables.min.css?v=1.0') ?>" rel="stylesheet" type="text/css" />
    <!-- pe-icon-7-stroke -->
    <link href="<?php echo base_url('assets/css/pe-icon-7-stroke.css?v=1.0') ?>" rel="stylesheet" type="text/css" />
    <!-- themify icon css -->
    <link href="<?php echo base_url('assets/css/themify-icons.css?v=1.0') ?>" rel="stylesheet" type="text/css" />
    <!-- Pace css -->
    <link href="<?php echo base_url('assets/css/flash.css') ?>" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url('assets/css/custom.css?v=1.0') ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <?php 
      if (!empty($settings->site_align) && $settings->site_align == "RTL") {  
        ?>
    <!-- THEME RTL -->
    <link href="<?php echo base_url('assets/css/custom-rtl.css?v=1.0') ?>" rel="stylesheet" type="text/css" />
    <?php 
      } 
      ?>
    <!-- jQuery  -->

    <script src="<?php echo base_url('assets/js/jquery.min.js?v=1.0') ?>" type="text/javascript"></script>

    <script type="text/javascript">
    window.getCookie = function(name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
        else return false;
    }
    </script>
    <style>
       .icon-class {
        color: #fff;
        font-size: 17px !important;
        background: #43AEEF;
        padding: 7px;
        border-radius: 4px;
        width: 30px !important;
    }
    </style>

    <?php //echo $this->session->telephony_token;
      if (!empty($this->session->ameyo['sessionId'])) { ?>
    <script type="text/javascript">
    function send_parameters(phn) {
        phn = phn.toString();
        phn = phn.substring(phn.length - 10, phn.length);
        console.info(phn);

        var campaignId = "<?=$this->session->ameyo['campaignId']?>";
        $.ajax({
            url: "<?=base_url().'telephony/ameyo_api'?>",
            type: 'POST',
            data: {
                'phone': phn,
                'campaignId': campaignId,
            },
            success: function(data) {
                if (data) {
                    data = JSON.parse(data);
                    if (data.status != 'error') {
                        Swal.fire({
                            title: '',
                            html: '<strong>Dailing <blink>..</blink></strong>',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ok'
                        }).then((result) => {
                            if (result.value) {}
                        });
                    } else {
                        Swal.fire({
                            title: '<strong>Dailing <blink>..</blink></strong>',
                            html: data.reason,
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ok'
                        });
                    }
                }
            }
        });
    }
    </script>
    <?php
      }else{
      if(in_array(220, $module)){
          if(empty($this->session->telephony_token)){  
            if(!empty($this->session->telephony_agent_id)){?>
    <script>
    function send_parameters(phn) {
        phn = phn.toString();
        phn = phn.substring(phn.length - 10, phn.length);
        console.info(phn);

        var agent_id = "<?=$this->session->telephony_agent_id?>";
        if (agent_id) {
            //var url="https://agent.c-zentrixcloud.com/apps/appsHandler.php?transaction_id=CTI_DIAL&agent_id="+agent_id+"&phone_num="+phn+"&ip=https://agent.c-zentrixcloud.com&resFormat=3";


            var url = "https://czagent.c-zentrixcloud.com/apps/appsHandler.php?transaction_id=CTI_DIAL&agent_id=" +
                agent_id + "&phone_num=" + phn + "&ip=&resFormat=3";


            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    Swal.fire({
                        // icon: 'info',
                        html: '<strong>Dailing <blink>..</blink></strong>',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ok'
                    }).then((result) => {
                        if (result.value) {}
                    });
                }
            });
        } else {
            alert('Agent id does not found!');
        }
    }

    function listener(event) {
        var spiltData = event.data.split('|');
        if (spiltData[0] == 'Accept') {


            //alert(event.data);

            if (localStorage.getItem('boolean') == 'false') {
                location.href = "<?=base_url().'telephony/forword_to/'?>" + spiltData[1];
                localStorage.setItem('boolean', 'true');
                url1 = "<?=base_url().'telephony/save_log'?>";
                $.ajax({
                    url: url1,
                    type: 'POST',
                    data: {
                        log: event.data
                    }
                });

            }
        } else if (spiltData[0] == 'Disconnect') {
            localStorage.setItem('boolean', 'false');
        }
    }
    if (window.parent.parent.addEventListener) {
        addEventListener("message", listener, false);
    } else {
        attachEvent("onmessage", listener);
    }
    </script>
    <script>
    function minimize_chats() {
        $('#minimize_chat').css('display', 'none');
        $('#maxmize_chat').css('display', 'block');
    }

    function maxmize_chats() {
        $('#minimize_chat').css('display', 'block');
        $('#maxmize_chat').css('display', 'none');
    }
    </script>

    <div style="position:fixed;z-index:200;float:right;right:0px;bottom:0px;display:block;" id="maxmize_chat">
        <div>
            <span class="btn btn-success" style="bottom:0px;z-index:300;" onclick="maxmize_chats()">
                <i class="fa fa-phone-square" style="font-size:30px;"></i>
            </span>
        </div>
    </div>
    <div style="position:fixed;z-index:200;float:right;right:5px;bottom:0px;display:none;" id="minimize_chat">
        <div>
            <span class="btn btn-primary btn-circle btn-xl"
                style="float:right;right:30px;bottom:0px;z-index:300;font-weight:bold" onclick="minimize_chats()"
                title='hide'>-</span>
        </div>


        <iframe width="320px" height="350px" scrolling="no" frameborder="0" align="right" gesture="media"
            src="http://czadmin.c-zentrixcloud.com/App/cti_handler.php?e=<?=$this->session->telephony_agent_id?>">
        </iframe>

    </div>
    <?php } }else{?>
    <script>
    function send_parameters(phn) {
        var x = phn.toString().length;
        phn = phn.toString();
        if (x >= 10) {
            phn = phn.substring(phn.length - 10, phn.length);
            console.info(phn);
            var url = "<?=base_url().'telephony/click_to_dial/'?>" + phn;
            $.ajax({
                url: url,
                type: 'POST',
                beforeSend: function() {
                    Swal.fire({
                        // icon: 'info',
                        html: '<strong>Dailing <blink>..</blink></strong>',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ok'
                    }).then((result) => {
                        if (result.value) {}
                    });
                },
                success: function(data) {
                    var res = JSON.parse(data);
                    //  console.log(res);
                    if (res.status == 'error') {
                        Swal.fire({
                            // icon: 'info',
                            html: '<strong>' + res.details + '</strong>',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ok'
                        }).then((result) => {
                            if (result.value) {}
                        });
                    } else {
                        if (typeof res.details != 'undefined') {
                            var str = res.details;
                        } else {
                            var str = res.message;
                        }
                        var str = res.details;
                        var matches = str.match(/(\d+)/);
                        Swal.fire({
                            html: '<strong>' + str + '</strong>',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                        }).then((result) => {
                            if (result.value) {}
                        });
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'info',
                html: '<strong>Invalid mobile Number</strong>',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ok'
            }).then((result) => {
                if (result.value) {}
            });
        }
    }
    </script>
    <?php } 
            }
            } ?>




    <!--    <script>
            
            function  minimize_chats(){
                $('#minimize_chat').css('display', 'none');
                $('#maxmize_chat').css('display', 'block');
            }
            function  maxmize_chats(){
                $('#minimize_chat').css('display', 'block');
                $('#maxmize_chat').css('display', 'none');
            }

        </script> -->

    <!--       <div style="position:fixed;z-index:200;float:right;right:0px;bottom:0px;display:block;" id="maxmize_chat">
            <div> 
              <span class="btn btn-success" style="bottom:0px;z-index:300;" onclick="maxmize_chats()"> 
            
                <i class="fa fa-phone-square" style="font-size:30px;"></i>
              </span>              
            </div>
        </div>
        <div style="position:fixed;z-index:200;float:right;right:5px;bottom:0px;display:none;" id="minimize_chat">
            
            <div> 
                <span class="btn btn-primary btn-circle btn-xl" style="float:right;right:30px;bottom:0px;z-index:300;font-weight:bold" onclick="minimize_chats()" title='hide'>-</span>                
            </div>
            
            <iframe width="320px" height="350px"  scrolling="no" frameborder="0" align="right" gesture="media" src="http://czadmin.c-zentrixcloud.com/App/cti_handler.php?e=<?=$this->session->telephony_agent_id?>">
          </iframe>

        </div> -->


    <?php //} ?>



    <style>
    .btn-default:hover {
        color: #333;
        background-color: white;
        border-color: #adadad;
    }

    .content {
        /*min-height: 900px;
       margin-right: auto;
       margin-left: auto;
       padding: 0 10px 10px !important;*/
    }

    .badge-notify {
        background-color: #db2828 !important;
        position: relative;
        top: -46px;
        left: 16px;
    }

    body {
        font-family: 'Montserrat';
    }

    label {
        font-family: 'Montserrat';
    }

    td,
    th {
        font-family: 'Montserrat';
    }

    p,
    a,
    label,
    span {
        font-family: 'Montserrat';
    }

    ;

    div {
        font-family: 'Montserrat';
    }

    ;
    </style>

<!-- <div class="screen-cover" align="center">
  <img src="https://i.stack.imgur.com/kOnzy.gif" style="width: 100px; margin:15%">
</div> -->
<style type="text/css">
  .screen-cover
  {
    height: 100%;
    width: 100%;
    top: 0px;
    left: 0px;
    background: rgb(255 255 255 / 98%);
    position: fixed;
    z-index: 9190;
  }
</style>

</head>

<body class="sidebar-mini <?php if($this->session->menu==1){echo 'sidebar-collapse';}?>"
    data-baseUrl="<?php echo base_url(); ?>">

    <div id='img-view-light'></div>
    <style>
        .wrapper{
            background: white;
        }
    .main-header .logo {
        background-color: #fff !important;
    }

    .sidebar-menu>li>ul>li.active>a {
        border-left: 3px solid #37a000;
    }

    .main-sidebar::-webkit-scrollbar {
        width: 5px !important;
    }


    .navbar-nav>li>a>i {
        border: 1px solid #fff !important;
        padding: 12px 3px;
        width: 24px;
        text-align: center;
        color: #374767;
        background-color: #fff !important;
        height: 29px;
        font-size: 19px;
    }

    .icon_color {
        background: #fff !important;
        border: none !important;
        color: green !important;
    }

    /* .main-header .logo{box-shadow:0 0 0 0px !important;} */
    .main-header {
        position: fixed !important;
        width: 100% !important;
    }
    </style>
    <!----------------------------------------------------------------------------------------------------alert reminder------------------------------------------------------------->
    <!----------------------------------------------------------------------------------------------------alert reminder------------------------------------------------------------->
    <style>
    .dialog-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;

        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
        z-index: 9999999;
    }

    /* The dialogs themselves */

    .dialog-card {
        box-sizing: border-box;
        width: 570px;
        position: absolute;
        left: 50%;
        margin-left: -285px;
        top: 20%;

        font: bold 14px sans-serif;

        border-radius: 3px;
        background-color: #ffffff;
        box-shadow: 1px 2px 4px 0 rgba(0, 0, 0, 0.12);
        padding: 45px 50px;
    }

    .dialog-card .dialog-question-sign {
        float: left;
        width: 68px;
        height: 68px;
        border-radius: 50%;
        color: #ffffff;
        text-align: center;
        line-height: 68px;
        font-size: 40px;
        margin-right: 50px;
        background-color: #b4d8f3;
    }

    .dialog-card .dialog-info {
        float: left;
        max-width: 350px;
    }

    .dialog-card h5 {
        /* Dialog title */
        color: #383c3e;
        font-size: 24px;
        margin: 5px 0 30px;
    }

    .dialog-card p {
        /* Dialog text */
        color: #595d60;
        font-weight: normal;
        line-height: 21px;
        margin: 30px 0;
    }

    .dialog-card .dialog-confirm-button,
    .dialog-card .dialog-reject-button {
        font-weight: inherit;
        box-sizing: border-box;
        color: #ffffff;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.12);
        padding: 12px 40px;
        border: 0;
        cursor: pointer;
        outline: 0;
    }

    .dialog-card .dialog-confirm-button {
        background-color: #87bae1;
        margin-right: 12px;
    }

    .dialog-card .dialog-reject-button {
        background-color: #e4749e;
    }

    .dialog-card button:hover {
        opacity: 0.96;
    }

    .dialog-card button:active {
        position: relative;
        bottom: -1px;
    }


    /*bell notificaiton style start*/
    .notify-detail {
        width: 400px;
        max-height: 500px;
        overflow-y: scroll;
        top: 60px;
        border-width: 1px;
        border-color: darkslategray;
        text-align: center;
    }

    .bell-tab-content {
        margin: 13px 13px;
    }

    /*bell notificaiton style end*/


    /* search icon style start */
    .master-search {
        /*opacity: 0;*/
        display: none !important;
        -webkit-transition: all .5s ease;
        -moz-transition: all .5s ease;
        transition: all .5s ease;
        width: 0px;
    }

    .master-search.expanded {
        display: block !important;
    }

    .dropdown-large {
        position: static !important;
    }

    .dropdown-menu-large {
        margin-left: 16px;
        margin-right: 16px;
        padding: 20px 0px;
        left: -131px;
        min-width: 250px;
    }

    @media (max-width: 768px) {
        .dropdown-menu-large {
            margin-left: 0;
            margin-right: 0;
        }

        .dropdown-menu-large>li {
            margin-bottom: 30px;
        }

        .dropdown-menu-large>li:last-child {
            margin-bottom: 0;
        }

        .dropdown-menu-large .dropdown-header {
            padding: 3px 15px !important;
        }
    }

    .dropdown-menu-large .cart-items {
        padding: 0px 14px;
        border-bottom: 1px solid #f7f7f7;
    }

    .checkout-btn {
        background-color: red !important;
        line-height: 31px;
        margin: 0px 15px;
        width: 85%;
    }

    /* search icon style end */
    .cart-qty {
        width: 40px;
        text-align: center;
    }

    .cart-items {
        margin: 12px 1px;
        border-bottom: 1px solid red;
        padding-bottom: 11px;
    }
    </style>


    <!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->

    <script>
    function send_data() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>lead/alertList",
            success: function(data) {
                var obj = JSON.parse(data);
                if (obj.status1 == 1) {
                    document.getElementById("popupadd").innerHTML = obj.status_data;
                    document.getElementById("cross_id").innerHTML = obj.status_id;
                    $("#my-confirm-dialogg").fadeIn(750);
                } else {}
            }
        })

    }
    </script>
    <div id="my-confirm-dialogg" class="dialog-overlay" style="display:none;">
        <div class="dialog-card">
            <div class="dialog-question-sign"><i class="fa fa-check"></i></div>
            <div class="dialog-info">
                <div id="popupadd"></div>
            </div>
            <div id="cross_id"></div>
        </div>
    </div>
    <script>
    function hide(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>Lead/alertstatus/" + id,
            success: function(data) {
                $("#my-confirm-dialogg").fadeOut(750);

            }
        });
    }
    </script>
    <style>
    .switch {
        position: relative;
        display: inline-block;
        width: 35px;
        height: 25px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: -15px;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 22px;
        left: 0px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

 
    </style>
    <!------------------------------------------------------------------------------------------------------------alert reminder end----------------------------------------------------->
    <!-- Site wrapper -->
    <div class="wrapper">
        <header class="main-header">
            <?php 
            $logo = $this->session->userdata('logo'); 
            ?>
            <a href="<?php echo base_url('dashboard/home') ?>" class="logo">
                <!-- Logo -->
                <span class="logo-mini">
                    <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){  ?>
                    <img src="<?php echo base_url("assets/images/lgo.png") ?>" alt="">
                    <?php }else{ ?>
                    <img src="<?php echo base_url("assets/images/new_logo.png")//.$settings->logo ?>" alt="">
                    <?php } ?>
                </span>
                <span class="logo-lg">
                    <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){  ?>
                    <img src="<?php echo base_url("assets/images/lgo.png") ?>" alt="">
                    <?php }else{ ?>
                    <img src="<?php echo base_url("assets/images/new_logo.png")//.$settings->logo ?>" alt="">
                    <?php } ?>
                </span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="javascript:void(0)" class="sidebar-toggle" id="something" data-toggle="offcanvas"
                    role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="pe-7s-keypad"></span>
                </a>
                <div class="navbar-custom-menu"
                    style="float:left;margin-top:10px;margin-left:20px;  display:inline-block;">
                    <!-- <div class="row"> -->
                    <!-- <div class="col-md-6 col-sm-12"  > -->

                    <?php 
                  if(!empty($title)){ ?>
                    <h1 style="font-size: 26px;"><?php echo $title; ?></h1>
                    <?php }else{ ?>
                    <h1 style="font-size: 26px;"><?php echo str_replace('_', ' ', ucfirst($segment1)) ?></h1>
                    <?php } ?>
                    <!-- </div> -->
                    <?php  if($this->session->userdata('companey_id')==65 ){?>
                    <style>
                    #center_logoimg {
                        position: absolute;
                        top: -50px;
                        right: -440px;
                        width: 200px;
                    }

                    @media screen and (max-width: 786px) {
                        #center_logo {
                            visibility: hidden;
                            display: none;
                        }
                    }

                    @media screen and (max-width: 1024px) {
                        #center_logo {
                            display: block;
                        }
                    }
                    </style>
                    <div class="col-md-6" id="center_logo">
                        <img id="center_logoimg" src="<?php echo base_url("assets/images/vtrans_logo.png") ?>" alt="">
                    </div>
                    <?php }?>
                    <!-- </div> -->
                </div>
                <div class="navbar-custom-menu" id="mob">
                    <ul class="nav navbar-nav">
                        <?php if($this->session->userdata('companey_id')!=67 && $this->session->userdata('user_right')!=214){ ?>
                        <li class="dropdown dropdown-user master-search-nav">
                            <!-- style="position: absolute;z-index: 74;right: 75px;" -->
                            <br />
                            <?php echo form_open(base_url("master_lead_search"), array("class" => "form-inline", "method"=> "GET",'id'=>'master_search_form')); ?>
                            <div class="input-group">
                                <input type="text" class="form-control master-search" required placeholder="Search here"
                                    name="search"
                                    value="<?php echo (!empty($_GET['search'])) ? $_GET['search'] : ""; ?>"
                                    style="width:77%;">

                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text btn btn-default master-search-icon"><i
                                            class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
                                </div>

                            </div>
                            <?php echo form_close(); ?>
                        </li>

                        <?php } 
          if (user_access(200)) { ?>
                        <li class="dropdown dropdown-user">
                            <button title="<?=display("mark_attendence")?>" class="btn btn-primary"
                                style="margin-top: 20px; border-radius: 50%;" id="mark_attendance"><i
                                    class="fa fa-clock-o"></i> </button>&nbsp;
                        </li>
                        <?php
          }
          ?>
                        <li class="dropdown dropdown-user">
                            <?php
                        if(user_access(230) || user_access(231) || user_access(232) || user_access(233) || user_access(234) || user_access(235) || user_access(236)){                          
                        ?>
                            <div class="btn-group dropdown-filter" style="margin-top: 20px;">
                                &nbsp;<button type="button" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-filter"></i> <span class="caret"></span>
                                </button>
                                <ul class="filter-dropdown-menu dropdown-menu">
                                    <?php
                            $user_process_list = $this->common_model->get_user_product_list();

                            if(!empty($user_process_list)){   
                              $this->load->helper('cookie');                        
                              $process_filter = get_cookie('selected_process');
                             // print_r($process_filter);
                              if (!empty($process_filter)) {
                                $process_filter = explode(',', $process_filter);
                              }else{
                                $process_filter = array();
                              }
                              //var_dump($process_filter);
                              if(user_access(270)){
                                  foreach ($user_process_list as $product) { 
                                      if($product->sb_id !=199 ){?>
                                    <li>
                                        <label>
                                            <input type="checkbox" name='product_filter[]' value="<?=$product->sb_id ?>" <?php if (empty($process_filter) || in_array($product->sb_id, $process_filter)) { echo "checked";                         
                                        }?>> <?=$product->product_name ?>
                                        </label>
                                    </li>
                                    <?php                
                                      }             
                                  }
                                }else{
                            foreach ($user_process_list as $product) { 
                                if($product->sb_id !=199 ){?>
                                    <li>
                                        <label>
                                            <input type="radio" name='product_filter[]' value="<?=$product->sb_id ?>" <?php if (empty($process_filter) || in_array($product->sb_id, $process_filter)) { echo "checked";                         
                                        }?>> <?=$product->product_name ?>
                                        </label>
                                    </li>
                                    <?php                             
                                  } 
                                }
                            }
                          }
                            ?>
                                </ul>
                            </div>

                            <?php
                        }
                        ?>
                        </li>

                        <?php
                  if (user_access('480')) { ?>


                        <li class="dropdown  dropdown-user" style="height: 1px;">
                            <?php                    
                      $cartarr = array();           
                      if(!empty($this->cart->contents())) {                    
                        $cartarr = $this->cart->contents();
                      }
                    ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class="caret"></span>
                            </a>
                            <span class="badge badge-notify" id="nav-cart-count"><?php echo count($cartarr); ?></span>

                            <div class="dropdown-menu dropdown-menu-large" style="width: 280px;">
                                <ul class="cart-dropdown-menu" id="cart-nav-menu" style="padding:0px;list-style:none;max-height: 400px;
    overflow-y: auto;">


                                </ul>
                                <ul style="padding:0px;list-style:none;">
                                    <li><a class="btn btn-danger checkout-btn"
                                            href="<?php echo base_url("buy/checkout") ?>">Check Out</a></li>
                                </ul>
                            </div>
                        </li>
                        <?php
             }
           ?>
                        <li class="dropdown dropdown-user" style="height: 1px;" id="notification_dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                id="anch_notification_dropdown"><i class="fa fa-bell-o"
                                    style="background:#fff !important;border:none!important;color:green;"></i></a>
                            <span class="badge badge-notify" id="bell_notifications_count">0</span>

                            <ul class="dropdown-menu notify-detail" id="notification_dropdown_tabs">
                            </ul>

                        </li>
                        <li><a href="#" id="test_id"></a></li>
                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"
                                    style="background:#fff !important;border:none!important;color:green;"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('dashboard/form'); ?>"><i class="pe-7s-users"></i>
                                        <?php echo display('profile') ?></a></li>
                                <li><a href="<?php echo base_url('setting/change_password')?>"><i
                                            class="pe-7s-lock"></i> <?php echo display('change_password'); ?></a></li>
                                <li><a href="<?php echo base_url('logout') ?>"><i class="pe-7s-key"></i>
                                        <?php echo display('logout') ?></a></li>
                            </ul>
                        </li>
                    </ul>
            </nav>
        </header>
        <?php 
         
         // validity expired popup
         if($this->session->userdata('validity_status')==2){?>
        <script>
        $(document).ready(function(){
            Swal.fire("<?= $this->session->userdata('validity_msg') ?>");
        })
        </script>
        <?php   }?>
        <aside class="main-sidebar" id="btn">
            <div class="sidebar">
                <div class="user-panel text-center">
                    <?php $picture = $this->session->picture; ?>
                    <div class="image">
                        <img src="<?php if(!empty($picture)){ echo base_url().$picture;}else{echo base_url()."assets/images/no-img.png";} ?>"
                            class="img-circle" onerror="this.style.display='none'">
                    </div>
                    <div class="info">
                        <p><?php echo $this->session->userdata('fullname') ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i>
                        </a>
                    </div>

                </div>
                <ul class="sidebar-menu">
                    <?php if(($this->session->user_right!=151 && $this->session->user_right!=180 && $this->session->user_right!=186 && $this->session->user_right!=200 && $this->session->userdata('user_right')!=214 && $this->session->userdata('user_right')!=201)){ ?>
                    <li class="<?php echo (($segment1 == 'dashboard') ? "active" : null) ?>">
                        <a href="<?php echo base_url('dashboard') ?>">
                            <i class="fa fa-home icon-class"></i>
                            &nbsp;<?php echo display('dashboard') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('dashboard') ?></p> <?php } ?>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="" style="display: none;">
                        <a href="<?php echo base_url('master_lead_search'); ?>">
                            <!-- 
                        it calls route        
                        $route['master_lead_search'] = 'lead/lead_search'; 
                      -->
                            <i class="fa fa-search"></i>
                            <?php echo display("lead_master_search"); ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('lead_master_search') ?></p> <?php } ?>
                        </a>
                    </li>
                    <li class="treeview <?php echo (($segment1 == "location" && ($segment1 !='user' && $segment2 !='edit' )) ? "active" : null) ?>" style="<?php if(in_array(10,$module) || in_array(11,$module) || in_array(12,$module) || in_array(12,$module)){ echo 'display:block;'; }else{ 
                    echo 'display:none;'; } ?>">
                        <a href="#">
                            <i class="fa fa-map-marker"
                                style="color:#fff;font-size:17px;background:#43AEEF;padding:7px 7px 7px 10px;border-radius:4px;width:30px;"></i>
                            <?php echo display("location_setting"); ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('location_setting'); ?></p> <?php } ?>
                        </a>
                        <ul class="treeview-menu <?php echo (  $segment2 == "index"  AND ($segment1 == "location") ?"menu-open":'') ?>">
                            <li
                                class="<?php echo (in_array($segment2,array('country'))?"active":'') ?> <?= ($segment1=='location' && $segment2=='create')?"active":'' ?> ">
                                <a
                                    href="<?php echo base_url("location/country") ?>"><?php echo display('country_list') ?></a>
                            </li>

                            <li
                                class="<?php echo (in_array($segment2,array('region','add_region','edit_region')) ?"active":'') ?>">
                                <a
                                    href="<?php echo base_url("location/region") ?>"><?php echo display('region_list') ?></a>
                            </li>

                            <li
                                class="<?php echo (in_array($segment2,array('state','add_state','edit_state')) ?"active":'') ?>">
                                <a
                                    href="<?php echo base_url("location/state") ?>"><?php echo display('state_list') ?></a>
                            </li>

                            <li
                                class="<?php echo (in_array($segment2,array('territory','add_territory','edit_territory')) ?"active":'') ?>">
                                <a
                                    href="<?php echo base_url("location/territory")?>"><?php echo display('territory_list') ?></a>
                            </li>

                            <li
                                class="<?php echo (in_array($segment2,array('city','add_city','edit_city')) ?"active":'') ?>">
                                <a href="<?php echo base_url("location/city") ?>"><?php echo display('city_list') ?></a>
                            </li>

                            <li class="<?php echo (in_array($segment2,array('import')) ?"active":'') ?>">
                                <a href="<?php echo base_url("location/import") ?>"><?php echo display('import') ?></a>
                            </li>

                        </ul>
                    </li>
                    <?php 
                     if (user_access(30)) {
                    ?>
                    <li
                        class="treeview <?php echo (($segment1 == "lead" && $segment2 != "index" && $segment2 != "datasourcelist" && $segment2 != "lead_details") ? "active" : null) ?>">
                        <a href="#">
                            <i class="fa fa-line-chart icon-class"></i>
                            <?php echo display("sales_setting"); ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('sales_setting'); ?></p> <?php } ?>
                        </a>
                        <ul
                            class="treeview-menu <?php echo (($segment1 == "lead" && $segment2 != "index" && $segment2 != "lead_details") ?"menu-open":'') ?>">
                            <?php  if (user_access(100)) { ?>

                            <li class="<?=($segment1=="leadRules")?"active":''?>">
                                <a href="<?php echo base_url(); ?>leadRules"> <?php echo display('lead_rules'); ?> </a>
                            </li>

                            <?php  } if (user_access(31)) { ?>
                            <li class="<?php echo (in_array($segment2,array('stage')) ?"active":'') ?>">
                                <a href="<?php echo base_url(); ?>lead/stage"> <?php echo display("lead_stage"); ?> </a>
                            </li>
                            <?php }   if (user_access(35)) {   ?>
                            <li class="<?php echo (in_array($segment2,array('description')) ?"active":'') ?>">
                                <a href="<?php echo base_url(); ?>lead/description">
                                    <?php echo display("lead_description"); ?> </a>
                            </li>
                            <?php } if (user_access(39)) {  ?>
                            <li class="<?php echo (in_array($segment2,array('lead_score')) ?"active":'') ?>">
                                <a href="<?php echo base_url(); ?>lead/lead_score">
                                    <?php echo display('conversion_probability'); ?></a>
                            </li>
                            <?php }  if (user_access('a35')) {   ?>
                            <li class="<?php echo (in_array($segment2,array('lead_source')) ?"active":'') ?>">
                                <a
                                    href="<?php echo base_url(); ?>lead/lead_source"><?php echo display('lead_source'); ?></a>
                            </li>
                            <?php }  if (user_access('a39')) {   ?>

                            <li
                                class="<?php echo (in_array($segment2,array('subsourcelist','add_subsource','edit_subsource')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/subsourcelist">
                                    <?php echo display('subsource_management');?></a>
                            </li>
                            <?php }  if (user_access('b33')) {   ?>

                            <li class="<?php echo (in_array($segment2,array('add_drop')) ?"active":'') ?>">
                                <a
                                    href="<?php echo base_url(); ?>lead/add_drop"><?php echo display('drop_reason'); ?></a>
                            </li>
                            <?php }  if (user_access('b37')) {   ?>

                            <li
                                class="<?php echo (in_array($segment2,array('load_customer_channel_mater')) ?"active":'') ?>">
                                <a
                                    href="<?php echo base_url('lead/load_customer_channel_mater'); ?>"><?php echo display('enquiry_type'); ?></a>
                            </li>
                            <?php }    if (user_access('c31')) {   ?>

                            <li class="<?php echo (in_array($segment2,array('product-list')) ?"active":'') ?>">
                                <a href="<?php echo base_url('lead/product-list'); ?>">
                                    <?php echo 'Process';?>
                                </a>
                            </li>

                            <?php }    if (user_access('c35')) {   ?>
                            <li
                                class="<?php echo (in_array($segment2,array('productcountry','addproductcountry','edit_institute')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/productcountry">
                                    <?php echo display('product'); ?></a>
                            </li>

                            <?php }      ?>

                            <?php if($this->session->userdata('companey_id')==67 || $this->session->userdata('companey_id')==76 || $this->session->userdata('companey_id')==84) { ?>
                            <li
                                class="<?php echo (in_array($segment2,array('disciplinelist','add_discipline','edit_discipline')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/discipline">
                                    <?php echo display('program_discipline');?>
                                </a>
                            </li>
                            <li
                                class="<?php echo (in_array($segment2,array('levellist','add_level','edit_level')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/level">
                                    <?php echo display('program_level');?>
                                </a>
                            </li>
                            <li
                                class="<?php echo (in_array($segment2,array('lengthlist','add_length','edit_length')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/length">
                                    <?php echo display('program_length');?>
                                </a>
                            </li>
                            <?php } ?>
                            <?php  if (user_access('f31')) {  ?>

                            <li class="<?php echo (in_array($segment2,array('institutelist','add_institute','edit_institute')) ?"active":'') ?>"
                                style="<?php if(in_array(240,$module) || in_array(241,$module) || in_array(242,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url() ?>lead/institutelist">
                                    <?php echo display('institute_management');?>
                                </a>
                            </li>
                            <?php } if (user_access('f35')) {  ?>

                            <li class="<?php echo (in_array($segment2,array('crslist','add_crs','edit_crs')) ?"active":'') ?>"
                                style="<?php if(in_array(350,$module) || in_array(351,$module) || in_array(352,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url() ?>lead/crslist">
                                    <?php echo display('course_master');?>
                                </a>
                            </li>
                            <?php } 
                        if (user_access('g33')) { 
                        ?>

                            <li class="<?php echo (in_array($segment2,array('sub_course','add_sub_course','edit_sub_course')) ?"active":'') ?>"
                                style="<?php if(in_array(350,$module) || in_array(351,$module) || in_array(352,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url() ?>lead/sub_course">
                                    <?php echo display('sub_course');?>
                                </a>
                            </li>
                            <?php   } if (user_access('f39')) {  ?>
                            <li class="<?php echo (in_array($segment2,array('courselist','add_course','edit_course')) ?"active":'') ?>"
                                style="<?php if(in_array(350,$module) || in_array(351,$module) || in_array(352,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url() ?>lead/courselist">
                                    <?php echo display('course_management');?>
                                </a>
                            </li>
                            <?php }?>
                            <?php
                            if ($this->session->companey_id == 67) {
                        if (user_access('g37')) {  ?>

                            ?>
                            <li
                                class="<?php echo (in_array($segment2,array('vidlist','add_video','edit_video')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/add_video">
                                    <?php echo display('add_vid');?>
                                </a>
                            </li>
                            <?php
                        }
                    }
                        ?>
                            <?php  if (user_access('c39')) {  ?>
                            <li
                                class="<?php echo (in_array($segment2,array('taskstatuslist','add_taskstatus','edit_taskstatus')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/taskstatuslist">
                                    <?php echo display('taskstatus_management');?>
                                </a>
                            </li>
                            <?php }  if (user_access('d33')) {  ?>

                            <li class="<?php echo (in_array($segment2,array('centerlist','add_center','edit_center')) ?"active":'') ?>"
                                style="<?php if(in_array(430,$module) || in_array(431,$module) || in_array(432,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url() ?>lead/centerlist">
                                    <?php echo display('center_management');?>
                                </a>
                            </li>
                            <?php }
                            if ($this->session->companey_id == 83) { 
                            if (user_access('h31')) {  ?>
                            <li
                                class="<?php echo (in_array($segment2,array('faq','add_faq','edit_faq')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>lead/faq">
                                    <?php echo display('faq');?>
                                </a>
                            </li>
                            <?php  
                        } 
                    }
                        ?>

                            <?php
                        //if ($this->session->companey_id == 65) {
                        ?>
                            <?php  if (user_access('d37')) {  ?>

                             <li class="<?php echo (in_array($segment2,array('sales_region_list')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/sales_region_list">
                                    Sales Region List
                                </a>
                            </li>

                            <li class="<?php echo (in_array($segment2,array('sales_area_list')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/sales_area_list">
                                    Sales Area List
                                </a>
                            </li>

                             <li class="<?php echo (in_array($segment2,array('zone_list')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/zone_list">
                                    Zone List
                                </a>
                            </li>

                            <li class="<?php echo (in_array($segment2,array('branchList')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/branchList">
                                    Branch List
                                </a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('add_vehicle_type')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/add_vehicle_type">
                                    Vehicle List
                                </a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('competitorList')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/competitorList">
                                    Competitor List
                                </a>
                            </li>
                            <?php }  if (user_access('e31')) {  ?>

                            <li
                                class="<?php echo (in_array($segment2,array('branch_ratelist','editbranchrate')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/branch_ratelist">
                                 Branch Rate List
                                </a>
                            </li>
                             <li
                                class="<?php echo (in_array($segment2,array('zone_ratelist','editbranchrate')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/zone_ratelist">
                                 Zone Rate List
                                </a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('discount_matrix')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/discount_matrix">
                                    Grade Master
                                </a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('oda_matrix')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/oda_matrix">
                                    ODA Matrix
                                </a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('bank_details')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/bank_details">
                                    Bank Details
                                </a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('expense_master')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/expensemaster">
                                    Expense Master
                                </a>
                            </li>
                             <li class="<?php echo (in_array($segment2,array('fuel_surcharge')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/fuel_surcharge">
                                    Fuel Surcharge
                                </a>
                            </li>
                            <?php }  if (user_access('e34')) {  ?>

                            <li
                                class="<?php echo (in_array($segment2,array('document-templates','createdocument_templates')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>setting/document-templates">
                                    Document Templates
                                </a>
                            </li>
                            <?php }  if (user_access('e37')) {  ?>

                            <li class="<?php echo (in_array($segment2,array('reportingList')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>users/reportingList">
                                    Reporting Location
                                </a>
                            </li>
                            <?php }  ?>
                             
							 <li class="<?php echo (in_array($segment2,array('department','add_department','edit_department')) ?"active":'') ?>" style="">
                               <a href="<?php echo base_url() ?>lead/department">
                               <?php echo 'Department Master';?>                             
                               </a>
                             </li>
							 
							 <li class="<?php echo (in_array($segment2,array('designation','add_designation','edit_designation')) ?"active":'') ?>" style="">
                               <a href="<?php echo base_url() ?>lead/designation">
                               <?php echo 'Designation Master';?>                             
                               </a>
                             </li>

                            </ul>
                    </li>
                            <?php } ?>
                           
                     
                    <!--    <li class="treeview <?php echo (($segment1 == "inventory") ? "active" : null) ?>"  style="<?php if(in_array(280,$module) || in_array(281,$module) || in_array(282,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                     <a href="#">
                      <i class="fa fa-cog icon-class" ></i> <?php echo display('Inventory'); ?>
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
            <?php  if($this->session->menu==1){ ?></br><p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;"><?php echo display('Inventory'); ?></p> <?php } ?>
                     </a>
                     
                     <ul class="treeview-menu">

                         <li class="<?php echo (in_array($segment2,array('addwarehouse')) ?"active":'') ?>">
                          <a href="<?php echo base_url() ?>warehouse/warehouse">Warehouse</a>
                        </li>
                         <li class="<?php echo (in_array($segment2,array('typeofproduct')) ?"active":'') ?>">
                          <a href="<?php echo base_url() ?>warehouse/typeofproduct">Type of Product</a>
                        </li>
                         <li class="<?php echo (in_array($segment2,array('brand')) ?"active":'') ?>">
                          <a href="<?php echo base_url() ?>warehouse/brand">Brand</a>
                        </li>

                        <li class="<?php echo (($segment1 == "inventory") ? "active" : null) ?>">
                          <a href="<?php echo base_url("warehouse/inventory") ?>">Inventory List</a>
                        </li>
                     </ul>

                  </li>  -->
                    <?php
                    if(user_access('1010') || user_access('1011') || user_access('1012')){ ?>
                    <li class="<?=( $segment1=='client' AND $segment2=='contacts')?'active':''?>">
                        <a href="<?=base_url('client/contacts')?>">
                            <i class="fa fa-address-book icon-class"></i>
                            <?=display('contacts')?>
                        </a>
                    </li>
                    <?php
                    }

                    if(user_access('1060'))
                    {
                    ?>

                    <li class="<?=($segment2=='company_list')?'active':''?>">
                        <a href="<?=base_url('client/company_list')?>">
                            <i class="fa fa-building icon-class"></i>
                            <?=display('company_list')?>
                        </a>
                    </li>
                    <?php
                    }
                    ?>

                    <li class="treeview"
                        style="<?php if(in_array(440,$module) || in_array(441,$module) || in_array(442,$module) || in_array(443,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="#">
                            <i class="fa fa-cart-plus icon-class"></i>
                            E-commerce
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>

                        </a>

                        <ul class="treeview-menu">

                            <li class="<?php echo (in_array($segment2,array('warehouse')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>warehouse/warehouse">Warehouse</a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('typeofproduct')) ?"active":'') ?>">
                                <a href="<?php echo base_url() ?>warehouse/typeofproduct">Type of Product</a>
                            </li>
                            <!-- <li class="<?php echo (in_array($segment2,array('brand')) ?"active":'') ?>">
                          <a href="<?php echo base_url() ?>warehouse/brand">Brand</a>
                        </li> -->

                            <li
                                class="<?php echo (($segment1 == "product" && $segment2=='category') ? "active" : null) ?>">
                                <a href="<?php echo base_url("product/category"); ?>">Category</a>
                            </li>
                            <li class="<?php echo (($segment1 == "scheme") ? "active" : null) ?>">
                                <a href="<?php echo base_url("scheme/"); ?>">Scheme</a>
                            </li>
                            <li class="<?php echo (($segment1 == "payment") ? "active" : null) ?>">
                                <a href="<?php echo base_url("payment/paylist"); ?>">Payment</a>
                            </li>
                            <li class="<?php echo (($segment2 == "measurement_unit") ? "active" : null) ?>">
                                <a href="<?php echo base_url("product/measurement_unit"); ?>">Measurement Unit</a>
                            </li>
                        </ul>

                    </li>
                    <?php
                  if (user_access('470')) { ?>
                    <li class="treeview <?php echo (($segment1 == "product") ? "active" : null) ?>">
                        <a href="<?php echo base_url("product/") ?>">
                            <i class="fa fa-list-alt icon-class"></i>
                            &nbsp;<?php echo 'Product'; ?>
                        </a>
                    </li>
                    <?php
                  }
                  if (user_access('300')) { ?>
                    <li class="<?php echo (($segment2 == "inventory") ? "active" : null) ?>">
                        <a href="<?php echo base_url("warehouse/inventory") ?>">
                            <i class="fa fa-object-group icon-class"></i>
                            &nbsp;<?php echo 'Inventory List'; ?>
                        </a>
                    </li>
                    <?php
                  }
                  if (in_array(460,$module) || in_array(461,$module) || in_array(462,$module) ||  in_array(463,$module)) { ?>
                    <li class="<?php echo (($segment1 == "order") ? "active" : null) ?>">
                        <a href="<?php echo base_url("order/"); ?>">
                            <i class="fa fa-list icon-class"></i>
                            &nbsp;<?php echo 'Order'; ?>
                        </a>
                    </li>
                    <?php
                  }
                  if (user_access('480')) { ?>
                    <li class="<?php echo (($segment1 == "buy") ? "active" : null) ?>">
                        <a href="<?php echo base_url("buy/"); ?>">
                            <i class="fa fa-shopping-bag icon-class"></i>
                            &nbsp;<?php echo 'Buy'; ?>
                        </a>
                    </li>
                    <?php
                  }
                  ?>
                    <?php                        
                  if(!empty($prod_category)) {       ?>
                    <li class="<?php //echo (($segment1 == 'buy' && empty($_GET)) ? "active" : null) ?>">
                        <a href="javascript:void(0)">
                            Filter by category
                        </a>
                    </li>
                    <?php
                      foreach($prod_category as $ind => $ctg) {              
                        if(!empty($ctg['sub'])){
                          $treemenu  =  true;
                        }else{
                          $treemenu  =  false;
                        }           
                      ?>
                    <li
                        class="<?php echo ((!empty($_GET['c']) && $_GET['c'] == $ind) ? "active" : null) ?> <?php echo ($treemenu == true) ? "treeview" : ""; ?>">
                        <?php if(($treemenu == false)) { ?>
                        <a href="<?php echo base_url('buy?c='.$ind); ?>">
                            <i class="fa fa-list-alt"></i> <?php echo $ctg['title']; ?>
                        </a>
                        <?php }else{ ?>
                        <a href="#" onclick='window.location = "<?php echo base_url('buy?c='.$ind); ?>"'>
                            <i class="fa fa-list-alt icon-class"></i>
                            &nbsp;<?php echo $ctg['title']; ?> <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>

                        </a>

                        <?php } ?>
                        <?php 
                              if(!empty($ctg['sub'])){
                            ?> <ul
                            class="treeview-menu <?php echo (!empty($_GET['c']) && $_GET['c']==$ind)?'active':'' ?>"><?php 
                                foreach($ctg['sub'] as $cind => $sbctg) {                     
                                ?><li
                                class="<?php echo ((!empty($_GET['sc']) && $_GET['sc'] == $sbctg['id']) ? "active" : null) ?>"
                                onclick="if($(this).hasClass('active')){$(this).parent().parent().addClass('active')}">
                                <a
                                    href="<?php echo base_url("buy?sc=".$sbctg['id']); ?>"><?php echo $sbctg['subcat_name']; ?></a>
                            </li><?php
                                }
                                ?></ul><?php
                              }
                                ?>


                    </li>

                    <?php   }
                   } ?>

                    <!-- start  super admin -->
                    <li class="treeview <?php echo in_array($segment1, array('user','customer','userrights')) ? "active" : null ?>"
                        style="<?php if(in_array(130,$module) || in_array(131,$module) || in_array(132,$module) || in_array(133,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="#">
                            <i class="fa fa-user-plus icon-class"></i>
                            &nbsp;<?php echo display('User_mgment') ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('User_mgment'); ?></p> <?php } ?>
                        </a>
                        <!-- <?=$segment1?> -->
                        <ul
                            class="treeview-menu <?php echo in_array($segment1, array('user','customer','userrights')) ? "menu-open" : null ?>">
                            <?php
                        if ($this->session->user_right == 1) {                        
                        ?>
                            <li
                                class="<?php echo (in_array($segment2,array('customer','create','edit')) ?"active":'') ?>">
                                <a href="<?php echo base_url("customer"); ?>"><?php echo display('company_list'); ?></a>
                            </li>

                            <li class="<?php echo (in_array($segment2,array('backupDatabase')) ?"active":'') ?>"> <a
                                    href="<?php echo base_url("dashboard/backupDatabase"); ?>">Backup Databse</a>
                            </li>

                            <li class="<?php echo (in_array($segment2,array('backupfiles')) ?"active":'') ?>"> <a
                                    href="<?php echo base_url("dashboard/backupfiles"); ?>">Backup Files</a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('user_type')) ?"active":'') ?>"> <a
                                    href="<?php echo base_url('user/user_type'); ?>"><?php echo display('user_function') ?></a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('user')) ?"active":'') ?>"> <a
                                    href="<?php echo base_url("user/index"); ?>"><?php echo display('user_list'); ?></a>
                            </li>
                            <li class="<?php echo (in_array($segment2,array('user_tree')) ?"active":'') ?>"> <a
                                    href="<?php echo base_url("user/user_tree") ?>">User Hierarchy</a>
                            </li>
                            <?php
                        }else{ ?>
                            <li class="<?php echo (in_array($segment1,array('user','userrights')) && in_array($segment2,array('user_type','edit_user_role','permissions','userrights')) ?"active":'') ?>"
                                style="<?php if(in_array(140,$module) || in_array(141,$module) || in_array(142,$module)|| in_array(143,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a
                                    href="<?php echo base_url(); ?>user/user_type"><?php echo display('user_function') ?></a>
                            </li>
                            <?php
                          $user_separation  = get_sys_parameter('user_separation','COMPANY_SETTING');
                          if (!empty($user_separation)) {
                            $user_separation = json_decode($user_separation,true);
                            foreach ($user_separation as $key => $value) { ?>
                            <li class="<?php echo ($segment1=='user' && (!empty($_GET['user_role']) && $_GET['user_role']==$key) && in_array($segment2,array('index','create','edit')) || ($segment1=='user'& $segment2=='create') ?"active":'') ?>"
                                style="<?php if(in_array(130,$module)||in_array(131,$module)){ echo 'display:block';}else{echo 'display:none;';}?>">
                                <a
                                    href="<?php echo base_url("user/index?user_role=").$key; ?>"><?php echo $value.' List'; ?></a>
                            </li>
                            <?php    
                            }
                          }
                          ?>
                            <li class="<?php echo ($segment1=='user' && empty($_GET['user_role']) && in_array($segment2,array('index','create','edit')) ?"active":'') ?>"
                                style="<?php if(in_array(130,$module)||in_array(131,$module)){ echo 'display:block';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url("user/index"); ?>"><?php echo display('user_list'); ?></a>
                            </li>

                            <li class="<?php echo ($segment1=='user' && in_array($segment2,array('user_tree')) ?"active":'') ?>"
                                style="<?php if(in_array(130,$module)||in_array(131,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url("user/user_tree") ?>"><?php echo 'User Hierarchy' ?></a>
                            </li>
                            <?php
                        }
                        ?>



                        </ul>

                    </li>
                    <?php
                  if ($this->session->user_right == 1) { ?>
                    <li class="treeview <?php echo (($segment1 == "form") ? "active" : null) ?>">
                        <a href="<?php echo base_url("form/form/tabs") ?>">
                            <i class="fa fa-cogs icon-class"></i>
                            &nbsp;<?php echo 'TABS Settings' ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo 'TABS Settings' ?></p> <?php } ?>
                        </a>
                    </li>
                    <?php
                  }
                  ?>


                    <li class="treeview <?php echo (($segment1 == "whatsappapi" || $segment1 == "smsapi" || $segment1 == "configurations" ||$segment1 == "emailapi") ? "active" : null) ?>"
                        style="<?php if(in_array(50,$module) || in_array(51,$module) || in_array(52,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="#">
                            <i class="fa fa-cog icon-class"></i>
                            &nbsp;<?php echo display('api') ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('api') ?></p> <?php } ?>
                        </a>

                        <ul class="treeview-menu">

                            <li class="<?php echo (($segment1 == "whatsappapi") ? "active" : null) ?>">
                                <a href="<?php echo base_url("whatsappapi") ?>"><?php echo display('whatsapp') ?></a>
                            </li>

                            <li class="<?php echo (($segment1 == "smsapi") ? "active" : null) ?>">
                                <a href="<?php echo base_url("smsapi") ?>"><?php echo display('sms') ?></a>
                            </li>

                            <li class="<?php echo (($segment1 == "emailapi") ? "active" : null) ?>">
                                <a href="<?php echo base_url("emailapi") ?>"><?php echo display('email_setting') ?></a>
                            </li>

                            <li
                                class="<?php echo (($segment1 == "configurations" && $segment2 == "index") ? "active" : null) ?>">
                                <a
                                    href="<?php echo base_url("configurations/index") ?>"><?php echo display('website') ?></a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(2) == "qr_code") ? "active" : null) ?>">
                                <a
                                    href="<?php echo base_url("configurations/qr_code") ?>"><?php echo display('qr_code') ?></a>
                            </li>
                            <?php
                        if ($this->session->companey_id == 81) { ?>
                            <!-- <li>
                          <a href="<?php echo base_url("whatsappapi/facebook") ?>">Facebook</a>
                        </li>   -->
                            <li>
                                <a href="<?php echo base_url("facebook") ?>">Facebook Master</a>
                            </li>
                            <?php
                        }
                        ?>
                        </ul>

                    </li>
                    <?php
                    /*
                        Enquiry -> 1
                        Lead    -> 10
                        Client  -> 20
                        End  
                    */
                    $menu_count = 1;
                    $flag = 0;
                    $main_flag =0;
                    for($menu_count=1; $menu_count <=21; $menu_count++)
                    {

                        if($menu_count==1)
                        {
                    ?>
                    <li class="treeview <?php echo (($segment1 == "enq" || ($segment1 == "enquiry" && $segment2 == "view")) ? "active" : null) ?>"
                        style="<?php if(in_array(60,$module) || in_array(61,$module) || in_array(62,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("enq/index") ?>">
                            <i class="fa fa-question-circle-o icon-class"></i>
                            &nbsp;<?php echo display('enquiry') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('enquiry') ?></p> <?php } ?>
                        </a>
                    </li>
                    <?php
                        }
                        else if($menu_count==10)
                        {
                    ?>
                    <li class="<?php echo ($segment1 == "led" || $segment2 == 'lead_details') ? "active" : null ?>"
                        style="<?php if(in_array(70,$module) || in_array(71,$module) || in_array(72,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">

                        <a href="<?php echo base_url("led/index") ?>">
                            <i class="fa fa-line-chart icon-class"></i>
                            &nbsp;<?php echo display('lead') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('lead') ?></p> <?php } ?>
                        </a>

                    </li>
                    <?php
                        }
                        else if($menu_count==20)
                        {
                    ?>
                    <li class="<?php echo (($segment1 == "client"   || $segment2 == ""|| $segment1 == "index"  ) && empty($_GET['stage'] )  && $segment2!='contacts' && $segment2!='deals' && $segment2!='visits'? "active" : null) ?>"
                        style="<?php if(in_array(80,$module) || in_array(81,$module) || in_array(82,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("client/index") ?>">
                            <i class="fa fa-user-circle-o icon-class"></i>
                            &nbsp;<?php echo display('client') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('client') ?></p> <?php } ?>
                        </a>

                    </li>

                    <?php
                    }

                  $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');                  
                  if (!empty($enquiry_separation)) { 

                    $enquiry_separation = json_decode($enquiry_separation,true);
                     foreach ($enquiry_separation as $key => $value) { 

                        if($main_flag == 1 or (!empty($value['order']) && $value['order']==$menu_count))
                        {
                            $flag=1;
                        ?>
                    <li class="<?php echo (($segment1 == "client") && (!empty($_GET['stage']) && $_GET['stage'] == $key) ) ? "active" : null ?>"
                        style="<?php if(in_array(80,$module) || in_array(81,$module) || in_array(82,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("client/index?stage=").$key ?>">
                            <i class="<?=$value['icon']?> icon-class"></i>
                            &nbsp;<?php echo $value['title'] ?>
                            <?php  
                                if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo $value['title']; ?></p>
                            <?php 
                                } 
                                ?>
                        </a>
                    </li>
                    <?php
                        }//order count if
                    }
                  }

                  $disposition_in_menu  = get_sys_parameter('disposition_in_menu','COMPANY_SETTING');    

                  if(!empty($disposition_in_menu)) 
                  { 
                    //echo json_encode(array(array('stage_id'=>299,'icon'=>'<i class="fa fa-user"></i>')));
                    //echo $disposition_in_menu; exit();
                    $x = json_decode($disposition_in_menu);
                  //print_r($x); exit();
                    foreach ($x as $des)
                    {   
                        if($main_flag == 1 or (!empty($des->order) && $des->order==$menu_count))
                        {
                            $flag =1;
                          $ci = &get_instance();
                          $ci->load->database();
                          $desp = $ci->db->where('stg_id',$des->stage_id)->get('lead_stage')->row();
                          $des_title = '';
                          if(!empty($desp))
                          {
                            $des_title = $desp->lead_stage_name;
                          }
                      ?>
                    <li
                        class="<?php echo (($segment1 == "client") && (!empty($_GET['desposition']) && $_GET['desposition'] == $des->stage_id) ) ? "active" : null ?>">

                        <a href="<?php echo base_url("client/desposition?desposition=").$des->stage_id; ?>">
                            <i class="<?=$des->icon;?> icon-class"></i> &nbsp;<?=$des_title?>
                        </a>

                    </li>
                    <?php
                        }//main flag
                    }

                  }

                  if($menu_count==20 and $flag==0)
                  {
                     $main_flag = 1;
                  }

                }//menucount end


                if(user_access('1020') || user_access('1021') || user_access('1022'))
                {
                    echo' <li class="'.($segment2=='visits'?'active':'').'" >
                        <a href="'.base_url("client/visits").'">
                        <i class="fa fa-car icon-class"
                                ></i>
                         Visits</a>
                        </li>';
                }

                if(user_access('1000') || user_access('1001') || user_access('1002') || user_access('1004') || user_access('1005'))
                {
                    echo' <li class="'.($segment2=='deals'?'active':'').'" >
                        <a href="'.base_url("client/deals").'">
                        <i class="fa fa-handshake-o icon-class"
                               ></i>
                         Deals</a>
                        </li>';
                }

                ?>

                <li class="<?php echo ($segment1 == "ticket" && $_COOKIE['selected_process'] == 199) ? "active" : null ?>">
                    <a href="<?php echo base_url("ticket/index/199") ?>" onclick="setCookie_new('selected_process',[199],365);  ">
                        <i class="fa fa-line-chart icon-class"></i>
                        &nbsp;<?php echo 'FTL Feedback' ?>
                        <?php  if($this->session->menu==1){ ?></br>
                        <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                            <?php echo 'FTL Feedback' ?></p> <?php } ?>
                    </a>
                </li>
                    
                    <li class="<?php echo (($segment1 == "task") ? "active" : null) ?>"
                        style="<?php if(in_array(90,$module) || in_array(91,$module) || in_array(92,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("task/index") ?>">
                            <i class="fa fa-calendar icon-class"></i>
                            &nbsp;<?php echo display('task') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('task') ?></p> <?php } ?>
                        </a>
                    </li>




                    <li class="treeview <?php echo (($segment1 == "target") || ($segment1 == "forecasting" && $segment1=='target_view') ) ? "active" : null ?>"
                        style="<?php if(in_array(210,$module) || in_array(211,$module) || in_array(212,$module) || in_array(213,$module) || in_array(260, $module) || in_array(261, $module) || in_array(262, $module) || in_array(250, $module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="#">
                            <i class="fa fa-cog icon-class"></i>
                            &nbsp;<?=display('target')?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo 'Forecasting'; ?></p> <?php } ?>
                        </a>

                        <ul class="treeview-menu">

                            <li class="<?php echo (($segment1 == "target") ? "active" : null) ?>"
                                style="<?php if(in_array(250,$module) || in_array(251,$module) || in_array(252,$module) || in_array(253,$module) || in_array(261, $module) || in_array(260, $module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url("target") ?>"><?=display('all_goals')?></a>
                            </li>

                            <!--  <li class="<?php echo (($segment2 == "target_view") ? "active" : null) ?>"
                                style="<?php if(in_array(250,$module) || in_array(251,$module) || in_array(252,$module) || in_array(253,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url("forecasting/target_view") ?>">View Target</a>
                            </li> -->


                            <!-- <li class="<?php echo (($segment2 == "user_target") ? "active" : null) ?>"
                                style="<?php if(in_array(260,$module) || in_array(261,$module) || in_array(262,$module) || in_array(263,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                                <a href="<?php echo base_url("forecasting/user_target") ?>">User Forecasting</a>
                            </li>
 -->
                        </ul>
                    </li>

                    <li class="treeview <?php echo (($segment1 == "report") ? "active" : null) ?>"
                        style="<?php if(in_array(120,$module) || in_array(430,$module) || in_array(122,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url('report/index')?>">
                            <i class="fa fa-bar-chart icon-class"></i>
                            &nbsp;<?php echo display('report'); ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('report') ?></p> <?php } ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <?php
                           
                            if(user_access('120'))
                            {
                            ?>
                            <li
                                class="<?php echo (($segment1 == "report" & $segment2=='' ) || ( $segment1 == "report" && $segment2=='view_details' ) ? "active" : null) ?>">
                                <a href="<?=base_url("report/") ?>">Sales Report </a>
                            </li>
                            <?php
                            }
                            if(user_access('122'))
                            {
                            ?>
                            <li
                                class="<?php echo (($segment1 == "report") && $segment2=='ticket_report' ? "active" : null) ?>">
                                <a href="<?=base_url("report/ticket_report") ?>">Ticket Report </a>
                            </li>
                            <?php
                            }                             
                            if(user_access('430'))
                            {
                            ?>
                            <li
                                class="<?php echo (($segment1 == "call_report") && $segment2=='index' ? "active" : null) ?>">
                                <a href="<?=base_url("call_report/index") ?>">Telephony Report </a>
                            </li>
                            <?php
                            } 
                            ?>
                        </ul>
                    </li>

                    <?php if(in_array(200,$module) || in_array(201,$module) || in_array(202,$module) || in_array(203,$module)) { ?>

                    <li class="treeview ">
                        <a href="#">
                            <i class="fa fa-cog icon-class"></i>
                            &nbsp;<?php echo display('user_activity'); ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('user_activity') ?></p> <?php } ?>
                        </a>

                        <ul class="treeview-menu">
                            <li
                                class="<?php echo (($segment1 == "attendance" && in_array($segment2, array('logs'))) ? "active" : null) ?>">
                                <a
                                    href="<?php echo base_url("attendance/logs") ?>"><?php echo display('attendence') ?></a>
                            </li>
                        </ul>

                    </li>
                    <?php
                }
               
                ?>
                    <li class="treeview <?php echo (($this->uri->segment(1) == "knowledge_base") ? "active" : null) ?>"
                        style="<?php if(in_array(170,$module) || in_array(171,$module) || in_array(172,$module) || in_array(173,$module) || in_array(174,$module)|| in_array(175,$module)|| in_array(176,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?=base_url().'leave'?>">
                            <i class="fa fa-quora icon-class"></i>
                            &nbsp; Knowledge Base
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo 'Knowledge Base' ?></p> <?php } ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul
                            class="treeview-menu <?php echo (($this->uri->segment(1) == "knowledge_base") ? "active" : null) ?>">
                            <?php if(user_access('170')==true||user_access('171')==true||user_access('172')==true||user_access('173')==true||user_access('174')==true||user_access('175')==true||user_access('176')==true){?>
                            <li
                                class="<?php echo (($segment1 == "knowledge_base" && in_array($segment2, array('index'))) ? "active" : null) ?>">
                                <a href="<?=base_url('knowledge_base/index')?>">Knowledge Base</a></li>
                            <?php
                            if(user_access('171') || user_access('172') || user_access('173'))
                            {
                            ?>
                            <li
                                class="<?php echo (($segment1 == "knowledge_base" && in_array($segment2, array('articles'))) ? "active" : null) ?>">
                                <a href="<?php echo base_url('knowledge_base/articles')?>">Articles</a></li>
                            <?php
                            }
                            if(user_access('174') || user_access('175') || user_access('176'))
                            {
                            ?>
                            <li
                                class="<?php echo (($segment1 == "knowledge_base" && in_array($segment2, array('category'))) ? "active" : null) ?>">
                                <a href="<?php echo base_url('knowledge_base/category')?>">Category</a></li>
                            <?php 
                            }
                            }?>
                        </ul>
                    </li>

                    <li class="treeview <?php echo (($segment1 == "invoice") ? "active" : null) ?>"
                        style="<?php if(in_array(180,$module) || in_array(181,$module) || in_array(182,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("invoice/invoice/index") ?>">
                            <i class="fa fa-paper-plane-o icon-class"></i>
                            &nbsp;<?php echo display('invoice') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('invoice') ?></p> <?php } ?>
                        </a>

                        <ul
                            class="treeview-menu <?php echo (($this->uri->segment(1) == "invoice") ? "active" : null) ?>">
                            <?php if(user_access('180')==true||user_access('181')==true||user_access('182')==true||user_access('183')==true){?>
                            <li
                                class="<?php echo (($segment2 == "invoice" && $segment3 == "index") ? "active" : null) ?>">
                                <a href="<?=base_url('invoice/invoice/index')?>">Invoice List</a>
                            </li>
                            <li
                                class="<?php echo (($segment2 == "invoice" && $segment3 == "create") ? "active" : null) ?>">
                                <a href="<?php echo base_url('invoice/invoice/create')?>">Create Invoice</a>
                            </li>
                            <li
                                class="<?php echo (($segment2 == "invoice" && $segment3 == "report") ? "active" : null) ?>">
                                <a href="<?php echo base_url('invoice/invoice/report')?>">Invoice Report</a>
                            </li>
                            <li
                                class="<?php echo (($segment2 == "invoice" && $segment3 == "settings") ? "active" : null) ?>">
                                <a href="<?php echo base_url('invoice/invoice/settings')?>">Invoice Settings</a>
                            </li>
                            <?php }?>
                        </ul>

                    </li>

                    <li class="treeview <?php echo (($segment1 == "language") ? "active" : null) ?>"
                        style="<?php if(in_array(190,$module) || in_array(191,$module) || in_array(192,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("language/index") ?>"><i class="fa fa-language icon-class"></i>
                            &nbsp;<?php echo display('language_setting') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('language_setting') ?></p> <?php } ?>
                        </a>
                    </li>

                    <li class="treeview <?php echo (($segment1 == "schedule") ? "active" : null) ?>"
                        style="<?php if(in_array(320,$module) || in_array(321,$module) || in_array(322,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("schedule/index") ?>"><i class="fa fa-language icon-class"></i>
                            &nbsp;<?php echo display('schedule') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('schedule') ?></p> <?php } ?>
                        </a>
                    </li>
                    <?php if($this->session->userdata('user_right')==214)
                    { 
                    ?>
                    <li class="treeview <?php echo (($segment1 == "ticket" && $_COOKIE['selected_process'] != 199) ? "active" : null) ?>"
                        style="<?php if(in_array(310,$module) || in_array(311,$module) || in_array(312,$module) || in_array(313,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("ticket/index/141") ?>"><i class="fa fa-tasks icon-class"></i>
                            &nbsp;<?php echo display('ticketing') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('ticketing') ?></p> <?php } ?>
                        </a>
                    </li>

                    <?php      

                    if(user_access('530'))
                      {
                      ?>
                    <li class="">
                        <a href="<?php echo base_url("holiday/add-festival") ?>">Add Festival</a>
                    </li>

                    <?php
                    }
                    if(user_access('531'))
                    {
                    ?>
                    <li class="">
                        <a href="<?php echo base_url("holiday/") ?>">Add Holiday</a>
                    </li>
                    <?php
                    }
// if($this->session->companey_id=='65')
                    ?>

                    <?php }else{ ?>
                    <li class="treeview <?php echo (($segment1 == "ticket" && $_COOKIE['selected_process'] != 199) ? "active" : null) ?>"
                        style="<?php if(in_array(310,$module) || in_array(311,$module) || in_array(312,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="#">
                            <i class="fa fa-tasks icon-class" "></i>
                            &nbsp;<?php echo display('ticketing') ?>
                            <span class=" pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                <?php  if($this->session->menu==1){ ?></br>
                                <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                    <?php echo display('ticketing') ?></p> <?php } ?>
                        </a>

                        <ul class="treeview-menu">
                            <?php
                         if(user_access(520)){
                         ?>
                            <li
                                class="<?php echo (($segment1 == "ticket" && $segment2 == "add_subject") ? "active" : null) ?>">
                                <a
                                    href="<?php echo base_url("ticket/add_subject") ?>"><?php echo display('ticket_problem_master') ?></a>
                            </li>
                            <?php
                         }
                         if(user_access('310'))
                         {
                         ?>
                            <li
                                class="<?php echo (($segment1 == "ticket" && $segment2 == "index" && $_COOKIE['selected_process'] != 199) ? "active" : null) ?>">
                                <a href="<?php echo base_url("ticket/index/") ?>"><?php echo display('ticketing') ?></a>
                            </li>
                            <?php
                        }
                        if(user_access('318'))
                        {
                          ?>
                            <li class="">
                                <a href="<?php echo base_url("ticket/auto_add_config") ?>">Auto ticket By Mail</a>
                            </li>

                            <?php
                        }     
                        if(user_access('523')){ ?>
                            <li
                                class="<?php echo (($segment1 == "ticket" && $segment2 == "natureOfComplaintList") ? "active" : null) ?>">
                                <a
                                    href="<?php echo base_url("ticket/natureOfComplaintList") ?>"><?php echo display("natureOfComplaint"); ?></a>
                            </li>
                            <?php
                        }
                        if(user_access('521'))
                        {
                        ?>
                            <li class="<?php echo (in_array($segment2,array('referred_by')) ?"active":'') ?>">
                                <a href="<?php echo base_url(); ?>ticket/referred_by"> <?php echo 'Referred By'; ?> </a>
                            </li>
                            <?php
                        }						
                        ?>

                            <?php           
                    if(user_access('530'))
                    {
                      ?>
                            <li class="">
                                <a href="<?php echo base_url("holiday/add-festival") ?>">Add Festival</a>
                            </li>

                            <?php
                    }
                    if(user_access('531'))
                    {
                    ?>
                            <li class="">
                                <a href="<?php echo base_url("holiday/") ?>">Add Holiday</a>
                            </li>
                            <?php
                    }
                    ?>
                        </ul>
                    </li>
                    <?php }
            ?>
                    <li class="treeview <?php echo (($segment1 == "appointment") ? "active" : null) ?>"
                        style="<?php if(in_array(330,$module) || in_array(331,$module) || in_array(332,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("appointment/index") ?>"><i class="fa fa-language icon-class"></i>
                            &nbsp;<?php echo display('appointment') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('appointment') ?></p> <?php } ?>
                        </a>
                    </li>

                    <li class="treeview <?php echo (($segment2 == "search_programs") ? "active" : null) ?>"
                        style="<?php if(in_array(380,$module) || in_array(381,$module) || in_array(382,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("dashboard/search_programs") ?>"><i
                                class="fa fa-search icon-class"></i>
                            &nbsp;<?php echo display('search_program') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('search_program') ?></p> <?php } ?>
                        </a>
                    </li>




                    <?php if($this->session->userdata('user_right')==151 || $this->session->userdata('user_right')==180 || $this->session->userdata('user_right')==186 || $this->session->userdata('user_right')==214){ ?>
                    <li class="treeview <?php echo (($segment2 == "meeting_room") ? "active" : null) ?>"
                        style="<?php if(in_array(410,$module) || in_array(411,$module) || in_array(412,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("dashboard/meeting_room") ?>"><i
                                class="fa fa-heart-o icon-class"></i>
                            &nbsp;<?php echo display('meeting_room') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('meeting_room') ?></p> <?php } ?>
                        </a>
                    </li>

                    <li class="treeview <?php echo (($segment2 == "user_profile") ? "active" : null) ?>"
                        style="<?php if(in_array(340,$module) || in_array(341,$module) || in_array(342,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("dashboard/user_profile") ?>"><i class="fa fa-user icon-class"></i>
                            &nbsp;<?php echo display('user_profile') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('user_profile') ?></p> <?php } ?>
                        </a>
                    </li>

                    <li class="treeview <?php echo (($segment2 == "my_applications") ? "active" : null) ?>"
                        style="<?php if(in_array(400,$module) || in_array(401,$module) || in_array(402,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("dashboard/my_applications") ?>"><i
                                class="fa fa-heart-o icon-class"></i>
                            &nbsp;<?php echo display('my_applications') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('my_applications') ?></p> <?php } ?>
                        </a>
                    </li>

                    <li class="treeview <?php echo (($segment2 == "my_applicants") ? "active" : null) ?>"
                        style="<?php if(in_array(420,$module) || in_array(421,$module) || in_array(422,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="<?php echo base_url("dashboard/my_applicants") ?>"><i
                                class="fa fa-heart-o icon-class"></i>
                            &nbsp;<?php echo display('my_applications') ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('my_applicants') ?></p> <?php } ?>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if(user_access(440) || user_access(441) || user_access(442) || user_access(443) ) { ?>
                    <!--      <li class="treeview <?php echo (($segment1 == "setting") ? "active" : null) ?>" style="display:block">
                    <a href="<?php echo base_url("setting/enquiryDuplicacySetting") ?>"><i class="fa fa-gear" style="color:#fff;font-size:18px;background:#f4d03f;padding:7px;border-radius:4px;width:30px;"></i> &nbsp;<?php echo display("new_setting") ?>
          
          </a>
                  </li> -->
                    <?php } ?>
                    <li class="treeview <?php echo (($this->uri->segment(1) == "website") ? "active" : null) ?>"
                        style="<?php if(in_array(490,$module) || in_array(491,$module) || in_array(492,$module)){ echo 'display:block;';}else{echo 'display:none;';}?>">
                        <a href="#">
                            <i class="pe-7s-global icon-class"></i>
                            &nbsp;<?php echo display('website') ?>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;">
                                <?php echo display('website') ?></p> <?php } ?>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url('website/setting') ?>"><?php echo display('setting') ?></a>
                            </li>

                            <li><a href="<?php echo base_url('website/slider') ?>"><?php echo display('slider') ?></a>
                            </li>

                            <li><a href="<?php echo base_url('website/section') ?>"><?php echo display('section') ?></a>
                            </li>

                            <li><a
                                    href="<?php echo base_url('website/item') ?>"><?php echo display('section_item') ?></a>
                            </li>

                            <li><a
                                    href="<?php echo base_url('website/comment') ?>"><?php echo display('comments') ?></a>
                            </li>
                        </ul>

                        <?php
                          if ($this->session->companey_id == 79) {
                          ?>
                    <li class="<?php echo (($segment1 == 'chat') ? "active" : null) ?>">
                        <a href="<?php echo base_url().'chat/chat_admin' ?>">
                            <i class="fa fa-comment icon-class"></i>
                            &nbsp;<?php echo 'Chat' ?>
                            <?php  if($this->session->menu==1){ ?></br>
                            <p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;"><?php echo 'Chat' ?>
                            </p> <?php } ?>
                        </a>
                    </li>
                    <?php
                        }
                        ?>


                    </li>


                </ul>
            </div>
        </aside>
        <!-- =============================================== -->
        <div class="content-wrapper">
            <?php if($this->session->companey_id == 57){ ?>
            <div class="alert alert-info  alert-dismissible fade in text-center ">
                <a href="#" class="close btn btn-xs btn-danger" data-dismiss="alert" aria-label="close"
                    style="top: unset; color: white;">&times;</a>
                <strong>Need Help! </strong> Call us on our Tollfree Number <a href="tel:18005722426">18005722426</a>
            </div>
            <?php } ?>
            <div class="content">
                <?php if ($this->session->flashdata('message') != null) {  ?>
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
                <?php } ?>
                <?php if ($this->session->flashdata('exception') != null) {  ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('exception'); ?>
                </div>
                <?php } ?>
                <?php if (validation_errors()) {  ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo validation_errors(); ?>
                </div>
                <?php } ?>
                <!-- content -->
                <?php echo (!empty($content)?$content:null) ?>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <a href="http://archizsolutions.com">
                <?= ($this->session->userdata('footer_text')!=null?$this->session->userdata('footer_text'):null) ?>
            </a>
        </footer>
    </div>
    <div id="uploadbulk" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <?php echo form_open_multipart('enquiry/upload_enquiry','class="form-inner"  ') ?>
            <div class="modal-content card">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-title"></h4>
                </div>
                <div>
                    <div class="form-group col-sm-12">
                        <label><?php echo display('upload_enquiry'); ?></label>
                        <input type="file" name="img_file" class="from-control" accept=".csv">
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="<?php echo base_url(); ?>assets/enquiry/sample_format.csv"
                        type="submit"><?php echo display("download_sample"); ?></a>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success" type="submit"><?php echo display('save'); ?></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?php echo display('close'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <?php
    if(in_array(290,$module)) {             
	  $this->load->view('chats/chats');
	  
	}?>
    <script type="text/javascript">
    $(document).on('click', '.checkout-btn', function(e) {
        var alert1 = "";
        $(".cart-items").each(function() {

            var cartval = parseInt($(this).find('.cart-qty').val());
            var minval = parseInt($(this).find('.minimum').val());
            var prodname = $(this).find('.prodname').text();


            if (minval > cartval) {
                e.preventDefault();
                alert1 += " Please order minimum " + minval + " " + prodname;
            }

        });
        if (alert1 != '') {
            //e.preventDefault();
            alert(alert1);
        }

    });
    </script>

    <script src="<?=base_url().'assets/js/sweetalert2@9.js'?>"></script>


    <div id="callbreak" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form action="<?php echo base_url(); ?>telephony/mark_abilibality" method='post'>
                <div class="modal-content card">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="modal-title"> Mark Status</h4>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="callbreakstatus"> Mark Status</label>
                        <select name="callbreakstatus" class="form-control">
                            <option value="1"> Available</option>
                            <option value="2"> Not Available</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit"><?php echo display('save'); ?></button>
                        <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?php echo display('close'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
    if(($this->session->companey_id!='67')){
    //$this->load->view('chats/chats');
    
  }?>

    <script src="<?=base_url().'assets/js/sweetalert2@9.js'?>"></script>

    <script>
    function remove_cart_item(pid) {
        $.ajax({
            url: "<?php echo base_url('buy/addtocart'); ?>",
            type: "post",
            data: {
                product: pid,
                qty: 0
            },
            success: function(resp) {
                var jresp = JSON.parse(resp);
                if (jresp.status == 2) {
                    $("#cart-li-" + pid).remove();
                    c = $("#nav-cart-count").html();
                    $("#nav-cart-count").html(c - 1);
                    $("a[data-prodid=" + pid + "]>.cart-quantity").html('');
                }
            }
        });
    }
    $(document).ready(function() {

        //manageCartQty();



        /*
        master search start
        */
        if ($(".master-search").val() != '') {
            $('.master-search').addClass('expanded');
        }
        $('.master-search-icon').click(function(e) {
            if ($(".master-search").hasClass('expanded')) {
                if ($(".master-search").val() != '') {
                    $("#master_search_form").submit();
                } else {
                    e.preventDefault();
                }
            } else {
                e.preventDefault();
            }
            $('.master-search').toggleClass('expanded');
            //$('.master-search-icon').toggleClass('fa-times');
        });
        /*
        master search end
        */



        var config = {
            apiKey: "AIzaSyARZpwl0KKW6AUZvRxopOJH1ZBG6ms6j8o",
            authDomain: "new-crm-f6355.firebaseapp.com",
            databaseURL: "https://new-crm-f6355.firebaseio.com",
            storageBucket: "new-crm-f6355.appspot.com",
            //projectId: "new-crm-f6355",
            /*
            messagingSenderId: "397430431725",*/
            //appId: "1:397430431725:web:58565840dc1c3d8a0751e4",
            //measurementId: "G-50R4B2JHCQ"
        }

        firebase.initializeApp(config);

        // Get a reference to the database service
        var database = firebase.database();
        var uid = "<?=$this->session->user_id?>";
        var today_date = "<?=date('Y-m-d')?>";
        var starCountRef = firebase.database().ref('reminders/' + uid).orderByChild('rem_date').equalTo(
            today_date);
        var starCountRef1 = firebase.database().ref('<?php echo $this->session->companey_id; ?>').orderByKey()
            .limitToLast(1);
        //var starCountRef2 =firebase.database().ref('us/').orderByKey().limitToLast(1);

        var rem = [];

        inter = setInterval(function() {
            rem1 = rem[0];
            var rem2;
            var rem_keys;
            if (rem1) {
                rem2 = Object.values(rem1);
                rem_keys = Object.keys(rem1);
            }
            i = 0;
            if (typeof rem2 !== 'undefined') {
                rem2.forEach(function(arrayItem) {
                    notication_id = rem_keys[i];
                    var d = arrayItem.rem_date;
                    var t = arrayItem.rem_time;
                    if (t.length > 5) {
                        t = t.substr(0, 5);
                    }
                    d = new Date();
                    var c_hrs = d.getHours();
                    var c_min = d.getMinutes();

                    if (parseInt(c_min) < 10) {
                        c_min = '0' + c_min;
                    }
                    if (parseInt(c_hrs) < 10) {
                        c_hrs = '0' + c_hrs;
                    }
                    c_time = c_hrs + ':' + c_min;

                    if (t == c_time) {
                        count_bell_notification();
                        reminder_content = arrayItem.reminder_txt;
                        if (notication_id) {
                            var url =
                                "<?=base_url().'notification/web/get_pop_reminder_content'?>";
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    notication_id: notication_id,
                                    enq_id: arrayItem.enq_id
                                },
                                success: function(data) {
                                    reminder_content = data;
                                    Swal.fire({
                                        title: 'Reminder',
                                        html: reminder_content,
                                        imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQSRlLiEna6GOulJgmf3QvBKsy8mp5vcrSl4tFusZEOoIb_8Kb7',
                                        imageWidth: 160,
                                        imageHeight: 150,
                                        imageAlt: 'Custom image',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Snooze!',
                                        cancelButtonText: 'Ok!'
                                    }).then((result) => {
                                        if (result.value) {
                                            (async () => {
                                                const {
                                                    value: snooze_time
                                                } = await Swal
                                                    .fire({
                                                        title: 'Enter snooze time',
                                                        input: 'text',
                                                        showCancelButton: true,
                                                        customClass: 'snooze-time',
                                                        onOpen: function() {
                                                            $('.swal2-input')
                                                                .blur();
                                                            $('.swal2-input')
                                                                .timepicker({
                                                                    timeFormat: 'h:mm p',
                                                                    interval: 1,
                                                                    zindex: 9999999,
                                                                    defaultTime: formatAMPM(
                                                                        new Date
                                                                    )
                                                                });
                                                        },
                                                        inputValidator: (
                                                            value
                                                        ) => {
                                                            return new Promise(
                                                                (
                                                                    resolve
                                                                    ) => {
                                                                    if (
                                                                        value
                                                                        ) {
                                                                        resolve
                                                                            ()
                                                                    } else {
                                                                        resolve
                                                                            (
                                                                                'You need to time'
                                                                                )
                                                                    }
                                                                }
                                                            )
                                                        }
                                                    })
                                                if (snooze_time) {
                                                    var rem_data = {
                                                        enq_id: arrayItem
                                                            .enq_id,
                                                        rem_date: arrayItem
                                                            .rem_date,
                                                        rem_time: format24hours(
                                                            snooze_time
                                                        ),
                                                        reminder_txt: arrayItem
                                                            .reminder_txt,
                                                        uid: arrayItem
                                                            .uid
                                                    };
                                                    firebase
                                                        .database()
                                                        .ref(
                                                            'reminders/' +
                                                            arrayItem
                                                            .uid +
                                                            '/' +
                                                            notication_id
                                                        )
                                                        .update(
                                                            rem_data
                                                        );
                                                    Swal.fire(
                                                        'Your Reminder Snoozed till ' +
                                                        snooze_time
                                                    );
                                                    location
                                                        .reload();
                                                }
                                            })()
                                        }
                                    });
                                }
                            });

                        } else {
                            Swal.fire({
                                title: 'Reminder',
                                html: reminder_content,
                                imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQSRlLiEna6GOulJgmf3QvBKsy8mp5vcrSl4tFusZEOoIb_8Kb7',
                                imageWidth: 160,
                                imageHeight: 150,
                                imageAlt: 'Custom image',
                            });
                        }
                    }
                    i++;
                });
            }
        }, 40000);

        function format24hours(time) {
            //var time = $("#starttime").val();
            var hours = Number(time.match(/^(\d+)/)[1]);
            var minutes = Number(time.match(/:(\d+)/)[1]);
            var AMPM = time.match(/\s(.*)$/)[1];
            if (AMPM == "PM" && hours < 12) hours = hours + 12;
            if (AMPM == "AM" && hours == 12) hours = hours - 12;
            var sHours = hours.toString();
            var sMinutes = minutes.toString();
            if (hours < 10) sHours = "0" + sHours;
            if (minutes < 10) sMinutes = "0" + sMinutes;
            return sHours + ":" + sMinutes;
        }

        function formatAMPM(date) {
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            var strTime = hours + ':' + minutes + '' + ampm;
            return strTime;
        }


        starCountRef.on('value', function(res) {
            rem.push(res.val());
            count_bell_notification();
            /*
                rem1 = rem[0];    
                rem2 = Object.values(rem1);
                rem_keys = Object.keys(rem1);
                //console.log(rem_keys);
                i = 0;
                var bell_html = '';
                rem2.forEach(function (arrayItem) {       
                  notication_id = rem_keys[i];            
                  bell_html += `<li class="notification-box">
                      <div class="row">                          
                        <div class="col-lg-8 col-sm-8 col-8">
                          <strong class="text-info">`+arrayItem.reminder_txt+`</strong>
                          <div>
                            <a href="<?php //echobase_url().'notification/web/notification_redirect/'?>`+arrayItem.enq_id+`">`+arrayItem.enq_id+`</a>
                          </div>
                          <small class="text-warning">`+arrayItem.rem_date+`,`+arrayItem.rem_time+`</small>
                        </div>    
                      </div>
                    </li>
                    <hr>`;
                    i++;
                });
                $("#bell_notifications").html(bell_html);
                $("#bell_notifications_count").html(i);*/
        });

        starCountRef1.on('value', function(res) {
            console.log(res.val());
            var myVar = res.val();
            if (typeof myVar !== 'undefined') {
                var phone = Object.values(res.val());
                phone.forEach(function(arrayItem) {
                    var phone = arrayItem.user_phone; //customer no
                    var uid = arrayItem.uid;
                    var phone_s = arrayItem.users;
                    phone_s = phone_s.replace(/[^\d]/g, ''); //agent no
                    if (phone.length >= 11) {
                        var phone_n = phone.substr(2, 12);
                    } else {
                        var phone_n = phone;
                    }
                    console.log(phone_s + 'a');
                    var user_pho = "<?php echo '91'.$this->session->phone;?>";
                    console.log(user_pho + 'b');
                    if (phone_s == user_pho) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url();?>telephony/get_in_status/" +
                                btoa(uid),
                            success: function(data) {
                                if (data == 1) {
                                    Swal.fire({
                                        icon: 'info',
                                        html: '<strong>Inbound call with this number.<a href="<?=base_url()?>telephony/forword_to/' +
                                            phone_n + '">' + phone_n +
                                            '</a></strong><br><a class="btn btn-info" href="<?=base_url()?>telephony/forword_to/' +
                                            phone_n + '">Go</a>',
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                    }).then((result) => {
                                        if (result.value) {}
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });

    });

    function writeUserData(userId, reminder_txt, enq_id, rem_date, rem_time) {
        var rem_data = {
            uid: userId,
            reminder_txt: reminder_txt,
            enq_id: enq_id,
            rem_date: rem_date,
            rem_time: rem_time
        };
        // Get a key for a new Post.
        var newPostKey = firebase.database().ref().child('reminders/' + userId).push().key;
        // Write the new post's data simultaneously in the posts list and the user's post list.
        var updates = {};
        updates['/reminders/' + userId + '/' + newPostKey] = rem_data;
        //updates['/user-posts/' + uid + '/' + newPostKey] = postData;
        firebase.database().ref().update(updates);
        return newPostKey
    }

    function update_notification(userId, notication_id, reminder_txt, enq_id, rem_date, rem_time) {
        var rem_data = {
            enq_id: enq_id,
            rem_date: rem_date,
            rem_time: rem_time,
            reminder_txt: reminder_txt,
            uid: userId
        };
        //console.log(rem_data);

        // Get a key for a new Post.
        //var newPostKey = firebase.database().ref().child('reminders/'+userId).push().key;
        // Write the new post's data simultaneously in the posts list and the user's post list.
        //var updates = {};
        //updates[] = rem_data;
        //updates['/reminders/' + userId + '/' + notication_id] = rem_data;
        //console.log(notication_id);
        return firebase.database().ref('reminders/' + userId + '/' + notication_id).update(rem_data);
        //return newPostKey
    }


    $("#submit_task_btn").on('click', function(e) {
        e.preventDefault();
        subject = $("input[name='subject']").val();
        time = $("input[name='task_time']").val();
        task_date = $("#enq_task_date").val();
        var uid = "<?=$this->session->user_id?>";
        var enq_id = $("input[name='enqCode']").val();
        id = writeUserData(uid, subject, enq_id, task_date, time);
        //console.log(id);
        $("input[name='notification_id']").val(id);
        $("#task_form").submit();
    });

    function getSelectedText(elementId) {
        var elt = document.getElementById(elementId);
        if (elt.selectedIndex == -1)
            return null;
        return elt.options[elt.selectedIndex].text;
    }
    $("#disposition_save_btn").on('click', function(e) {
        e.preventDefault();

        var name_prefix = $("select[name='name_prefix']").val();
        var enquiry_name = $("input[name='enquirername']").val();
        var lastname = $("input[name='lastname']").val();
        enq_name = name_prefix + ' ' + enquiry_name + ' ' + lastname;
        var disposition = getSelectedText('lead_stage_change');

        var enq_id = $("input[name='enqCode']").val();
        subject = disposition + ' :' + enq_name;
        $("input[name='dis_subject']").val(subject);
        time = $("#disposition_c_time").val();
        task_date = $("#disposition_c_date").val();
        var uid = "<?=$this->session->user_id?>";
        //alert("UID:"+uid+" subj:"+subject+" enqid:"+enq_id+" task:"+task_date+" time:"+time);   
        id = writeUserData(uid, subject, enq_id, task_date, time);
        //console.log(id);
        $("input[name='dis_notification_id']").val(id);
        $("#disposition_save_form").submit();


    });


    $("#visit_create_btn").on('click', function(e) {
        e.preventDefault();

        var vform = $("#visit_create_form");
        var vtype =  $(vform).find('input[name=type]:checked').val();
        
        var _contact = $(vform).find("select[name='contact_id'] option:selected"); 
        var cname =  $(_contact).html();
        var enquiry = $(vform).find("select[name='enq_id'] option:selected"); 
      
       var enq_id = $(enquiry).val();
       var m_purpose = $(vform).find('input[name=m_purpose]').val();
        time = $(vform).find("#vtime").val();
        task_date = $(vform).find("#vdate").val();
        subject = 'Visit : '+cname;
        var uid = "<?=$this->session->user_id?>";

        if(cname=='' || enq_id=='' || time=='' || task_date=='' || subject=='' || uid=='' || m_purpose=='')
        {
            alert('Fill all the fields.');
            return;
        }

        if(vtype==2)
        {   
            // alert("UID:"+uid+" subj:"+subject+" enqid:"+enq_id+" task:"+task_date+" time:"+time); 
           
            $.ajax({
                url:'<?=base_url('enquiry/enq_code_by_id/')?>'+enq_id,
                type:'post',
                success:function(res)
                {
                    id = writeUserData(uid, subject, res.trim(), task_date, time);
                    //console.log(id);

                    $(vform).find("input[name='visit_notification_id']").val(id);
                    $("#visit_create_form").submit();
                }
            });
         
        }
        else
        {
            $("#visit_create_form").submit();
        }
       //

    });


    $(document).on('click', '#task_update_btn', function(e) {
        e.preventDefault();
        //alert('abc');
        subject = $("#task_update_subject").val();
        time = $("#task_update_task_time").val();
        task_date = $("#task_update_enq_task_date").val();
        var uid = $("input[name='task_update_create_by']").val();
        var enq_id = $("input[name='task_enquiry_code']").val();
        notication_id = $("input[name='update_notification_id']").val();
        nid = update_notification(uid, notication_id, subject, enq_id, task_date, time);
        //console.log(nid);
        $("#update_task_form").submit();

    });

    function count_bell_notification() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>notification/web/count_bell_notification",
            success: function(data) {
                $("#bell_notifications_count").html(data);
            }
        });
    }
    $("#anch_notification_dropdown").on('click', function() {
        var url = "<?=base_url().'notification/web/get_bell_notification_content'?>";
        $.ajax({
            url: url,
            type: 'POST',
            beforeSend: function() {
                $("#notification_dropdown_tabs").html('loading...');
            },
            success: function(data, status) {
                $("#notification_dropdown_tabs").html(data);
            },
            error: function(xhr, desc, err) {
                $("#notification_dropdown_tabs").html("error");
            }
        });
    });

    $('#notification_dropdown_tabs').on('click', '.nav-tabs a', function() {
        $(this).closest('.dropdown').addClass('dontClose');
    });
    $('#notification_dropdown').on('hide.bs.dropdown', function(e) {
        if ($(this).hasClass('dontClose')) {
            e.preventDefault();
        }
        $(this).removeClass('dontClose');
    });
    </script>

    <script>
    $("#service").click(function() {
        if ($('#another-element:visible').length)
            $('#another-element').hide();
        else
            $('#another-element').show();
    });
    $("#task_create_div").click(function() {
        if ($('#task_create:visible').length)
            $('#task_create').hide();
        else
            $('#task_create').show();
    });

    function check_stage(id) {
        if (id == 5) {
            document.getElementById('curcit_add').style.display = 'block';
            document.getElementById('add_po').style.display = 'none';
        } else if (id == 8) {
            document.getElementById('add_po').style.display = 'block';
            document.getElementById('curcit_add').style.display = 'none';
        } else {
            document.getElementById('add_po').style.display = 'none';
            document.getElementById('curcit_add').style.display = 'none';
        }
    }

    $(document).ready(function() {
        var url = "<?=base_url().'attendance/check_attendance_status'?>";
        $.ajax({
            url: url,
            type: 'POST',
            beforeSend: function() {
                $("#mark_attendance").html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(data, status) {
                if (data != 'null') {
                    jdata = JSON.parse(data);
                    $("#mark_attendance").removeClass('btn-primary');
                    $("#mark_attendance").addClass('btn-danger');
                    $("#mark_attendance").attr('title', 'Mark attendance');
                    $("#mark_attendance").html('<i class="fa fa-clock-o fa-pulse"></i>');
                    $("#mark_attendance").attr('data-id', jdata.id);
                } else {
                    $("#mark_attendance").html('<i class="fa fa-clock-o"></i>');
                }
            },
            error: function(xhr, desc, err) {
                alert("mark attendance error");
            }
        });
    });
    if (("<?=$this->session->companey_id?>" == "57")) {
        m = $("#mark_attendance").hasClass('btn-danger');
        if (m) {
            record_geolocation();
        }
        setInterval(function() {
            m = $("#mark_attendance").hasClass('btn-danger');
            if (m) {
                record_geolocation();
            }
        }, 300 * 1000);
    }

    function showPosition(position) {
        var url = "<?=base_url().'attendance/record_geolocation'?>";
        var user_id = "<?=$this->session->user_id?>";
        var lat = position.coords.latitude;
        var long = position.coords.longitude;
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                'user_id': user_id,
                'lat': lat,
                'long': long
            }
        });
    }

    function record_geolocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            console.info("Geolocation is not supported by this browser.");
        }
    }
    $("#mark_attendance").on('click', function() {
        var url = "<?=base_url().'attendance/mark_attendance'?>";
        var atID = $("#mark_attendance").attr('data-id');
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                'atID': atID
            },
            beforeSend: function() {
                $("#mark_attendance").html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(data, status) {
                if (data != 'null') {
                    data = JSON.parse(data);
                    if (data.id == 'updated') {
                        $("#mark_attendance").removeClass('btn-danger');
                        $("#mark_attendance").addClass('btn-primary');
                        $("#mark_attendance").attr('title', 'Mark attendance');

                        $("#mark_attendance").html('<i class="fa fa-clock-o"></i>');
                        $("#mark_attendance").removeAttr('data-id');
                    } else {
                        $("#mark_attendance").removeClass('btn-primary');
                        $("#mark_attendance").addClass('btn-danger');
                        $("#mark_attendance").attr('title', 'Check out');
                        $("#mark_attendance").html('<i class="fa fa-clock-o fa-pulse"></i>');
                        $("#mark_attendance").attr('data-id', data.id);
                    }
                    window.location.reload();
                }
            },
            error: function(xhr, desc, err) {
                // alert("mark attendance error");
            }
        });
    });
    if ($(".treeview-menu li").hasClass('active')) {
        $(".treeview-menu li.active").parent().prev().parent().addClass('active');
    }
    </script>
    <script>
    $('#something').click(function() {
        //alert('hi');  
        //document.location = '<?php //echo base_url('dashboard/menu_style'); ?>';
    });
    </script>
    <!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.11.0/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/7.11.0/firebase-database.js"></script>

    <script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script>
    <!---- new js file added by pp ------------->
    <script src="<?php echo base_url('assets/js/dashboard_js.js') ?>" type="text/javascript"></script>
    <!----------------------------------------------------------------------------------------------->
    <!-- bootstrap js -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>

    <!-- bootstrap timepicker -->
    <script src="<?php echo base_url() ?>assets/js/jquery-ui-sliderAccess.js?v=1.0" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/js/select2.min.js?v=1.0" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/sparkline.min.js?v=1.0') ?>" type="text/javascript"></script>

    <!-- ChartJs JavaScript -->
    <script src="<?php echo base_url('assets/js/Chart.min.js?v=1.0') ?>" type="text/javascript"></script>
    <!-- semantic js -->
    <script src="<?php echo base_url() ?>assets/js/semantic.min.js?v=1.0" type="text/javascript"></script>
    <!-- DataTables JavaScript -->
    <script src="<?php echo base_url("assets/datatables/js/dataTables.min.js?v=1.0") ?>"></script>
    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>     -->
    <script src="<?=base_url()?>assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="<?php echo base_url() ?>assets/js/tableHeadFixer.js?v=1.0" type="text/javascript"></script>
    <!-- Admin Script -->
    <script src="<?php echo base_url('assets/js/frame.js?v=1.0?v=1.0') ?>" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/custom.js?v=1.0.1" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
</body>

</html>


<script>
var content = document.getElementById("main");
var btn = document.getElementById("btn");
$(document).ready(function() {
    $("#btn").hover(function() {
        if ($('body').hasClass('sidebar-collapse')) {
            $("body").removeClass("sidebar-collapse");
            $("body").addClass("sidebar-byme");
        }
    }, function() {
        if ($('body').hasClass('sidebar-byme')) {
            var timeout = setTimeout(function() {
                $("body").addClass("sidebar-collapse");
            }, 500);
        }

    });
});

$("#cart-nav-menu").load("<?=base_url().'buy/cart'?>", function() {

    //alert("in");
    $(".cart-qty").change(function() {
        $.ajax({
            url: "<?php echo base_url('buy/addtocart'); ?>",
            type: "post",
            data: {
                product: $(this).data("prodid"),
                qty: $(this).val()
            },
            success: function(resp) {
                var jresp = JSON.parse(resp);

                if (jresp.status == 2) {
                    console.log(jresp.price);
                    // alert(jresp.price);
                    //alert(jresp.price * jresp.qty);
                    var final = jresp.price * jresp.qty;
                    $('.item-price-' + jresp.prodid).html(final.toFixed(2));
                }
            },
            error: function(u, v, w) {
                alert(w);
            }
        });
    });

});
jQuery(function($) { //on document.ready
    $('.form-date').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
// $(document).on('ready',function(){
//   $(".screen-cover").hide();
// });
</script>