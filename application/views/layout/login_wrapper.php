<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
//get site_align setting
$settings = $this->db->select("*")
    ->where('domain',$_SERVER['HTTP_HOST'])
    ->get('setting');
    
if($settings->num_rows() != 1){
    $settings = $this->db->select("*")
    ->where('comp_id',0)
    ->get('setting')
    ->row();
}else{
    $settings = $settings->row();
}    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){	 ?>
    <link rel="icon"
        href="https://spaceinternationals.com/wp-content/uploads/2018/02/cropped-SPACE-INTERNATIONALS-LOGO-02-1-32x32.jpg"
        sizes="32x32" />
    <?php }else if($root=='https://v-trans.thecrm360.com'){ ?>
    <link rel="icon" href="<?=base_url().'assets/images/vtrans_logo.png'?>" sizes="32x32" />
    <?php
}else if(!empty($_GET['c']) && base64_decode($_GET['c']) == 57 && (!empty($_GET['type']) && $_GET['type'] != 'admin') && $root=='https://lalantop.thecrm360.com'){ ?>
    <link rel="icon" href="<?=base_url().'assets/images/Lalantop_logo.jpg'?>" sizes="32x32" />
    <title><?= display('login') ?> - <?php echo 'Lalantop' ?></title>
    <?php
}else{ ?>
    <link rel="icon" href="<?=base_url().$settings->favicon?>"
        sizes="32x32" />
    <title><?= display('login') ?> - <?php echo $settings->title ?></title>
    <?php } ?>
    <!-- Favicon and touch icons -->

    <!-- <link rel="shortcut icon" href="<?php echo (!empty($favicon)?$favicon:null) ?>"> -->

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <?php if (!empty($settings->site_align) && $settings->site_align == "RTL") {  ?>
    <!-- THEME RTL -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/custom-rtl.css') ?>" rel="stylesheet" type="text/css" />
    <?php } ?>

    <!-- 7 stroke css -->
    <link href="<?php echo base_url(); ?>assets/css/pe-icon-7-stroke.css" rel="stylesheet" type="text/css" />

    <!-- style css -->
    <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/custom-font.css') ?>" rel="stylesheet" type="text/css" />


    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <style>
    body {
        font-family: 'Montserrat';
    }
    </style>

</head>

<body data-baseUrl="<?php echo base_url(); ?>" style='background: white;'>
    <!-- Content Wrapper -->
    <div class="login-wrapper">
        <div class="text-center">
            <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
            if($root=='https://student.spaceinternationals.com'){	 ?>
            <img style="width: 26%;" src="<?php echo base_url('assets/images/lgo.png'); ?>" class="m-r-sm">
            <?php }else if ($root=='https://v-trans.thecrm360.com'){ ?>
            <img style="width: 26%;" src="<?=base_url().'assets/images/vtrans_logo.png'?>" class="m-r-sm">
            <?php }else if (!empty($_GET['c']) && base64_decode($_GET['c']) == 57 && (!empty($_GET['type']) && $_GET['type'] != 'admin')){ ?>
            <img style="width: 26%;" src="<?=base_url().'assets/images/Lalantop_logo.jpg'?>" class="m-r-sm">
            <?php } else{ ?>
            <img style="width: 26%;" src="<?=base_url().$settings->logo?>" class="m-r-sm">
            <?php } ?>
        </div>
        <div class="container-center" style="margin-top: auto;">
            <div class="panel panel-bd" style="-webkit-box-shadow: 0px 0px 28px -9px rgba(0, 0, 0, 0.74) !important;">
                <div class="panel-heading">
                    <div class="view-header">
                        <div class="header-icon">
                            <i class="pe-7s-unlock"></i>
                        </div>
                        <div class="header-title">
                            <?php
                                if($root=='https://student.spaceinternationals.com'){    ?>
                            <!-- <h3><?php echo (!empty($title)?$title:null) ?></h3> -->
                            <?php }else if ($root=='https://v-trans.thecrm360.com') { ?>
                            <h3>V-Trans</h3>
                            <?php
                                }else if (!empty($_GET['c']) && base64_decode($_GET['c']) == 57) { ?>
                            <h3>Lalantop.com</h3>
                            <?php
                                }else{
                                    ?>
                            <h3><?php echo $settings->title ?></h3>
                            <?php
                                } ?>
                            <small><strong id="login-title"><?= display('please_login') ?></strong></small>
                        </div>
                    </div>
                    <div class="">
                        <br><br>
                        <!-- alert message -->
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
                    </div>
                </div>


                <div class="panel-body" id="LoginDiv">
                    <?php echo form_open('dashboard/validate_login','id="loginForm" novalidate'); ?>
                    <div class="form-group">
                        <label class="control-label" for="email"><?= 'Enter Login id' ?></label>
                        <input type="text" placeholder="<?= 'Enter Login id' ?>" name="email" id="email"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="password"><?= display('password') ?></label>
                        <input type="password" placeholder="<?= display('password') ?>" name="password" id="password"
                            class="form-control">
                    </div>

                    <div class="form-group" style="display: none;">
                        <label class="control-label" for="process"><?php echo display('proccess'); ?></label>

                        <select class="form-control" name="process" required>
                            <?php
                                    if (!empty($products)) {
                                        foreach ($products as $key => $value) { ?>
                            <option value="<?=$value->sb_id?>"><?=$value->product_name?></option>
                            <?php
                                        }
                                    }
                                    ?>
                        </select>
                    </div>


                    <div class="row">
                        <span class="pull-left"><input type="checkbox" name="remember_me" value="1"> Remember me </span>
                        <span class="pull-right"><a href="javascript:"
                                id="fpassword"><?php echo display("forget_password"); ?></a></span>
                    </div><br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><?= display('login') ?></button>
                    </div>
                    <br>
                    <br>
                    <?php
                                $c = !empty($_GET['c'])?$_GET['c']:'';

                                if($root=='https://student.spaceinternationals.com' || $c == base64_encode(57)){    ?>
                    <div class="text-center">
                        New User ? <a href="javascript:void(0)" id="signup">Create an account</a>
                    </div>
                    <?php
                                }
                                ?>

                    </form>
                </div>

                <!-----------------------Forget Password -------------------------------->
                <div class="panel-body" id="ForgetPasswordDiv" style="display:none">
                    <form id="ForgetPassword">
                        <div class="form-group">
                            <label class="control-label" for="email"><?php echo "Enter Email";?></label>
                            <input type="text" placeholder="<?php echo "Enter Email"; ?>" name="femail"
                                id="femail" class="form-control" required>
                        </div>
						<div class="form-group">
                            <label class="control-label" for="email"><?php echo "Employee Code";?></label>
                            <input type="text" placeholder="<?php echo "Enter Employee Code"; ?>" name="fecode"
                                id="fecode" class="form-control" required>
                        </div>
                        <div class="form-group">

                            <input type="text" placeholder="<?php echo "Enter OTP"; ?>" name="otp" id="otp"
                                class="form-control" style="display:none">
                        </div>

                        <div>
                            <button type="button" class="btn btn-success" style="display:none" id="votp"
                                onclick="verifyOTP()">Verify OTP</button>
                            <button type="submit" id="fsubmit"
                                class="btn btn-success"><?php echo display('submit'); ?></button> <span
                                class="pull-right"><a href="javascript:"
                                    id="Llogin"><?php echo display("login"); ?></a></span>
                        </div>

                    </form>
                </div>


            </div>
        </div>
    </div>





    <!-- Modal -->
    <div id="process_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 27% !important;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <!--<h4 class="modal-title">Modal Header</h4> -->
                </div>
                <div class="modal-body text-center">
                    <form id="process_form">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <label>Select Process</label>
                                <div id='process_html'></div>

                                <br>
                                <br>
                                <input type="submit" name="Go" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-wrapper -->
    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/jquery.min.js') ?>" type="text/javascript"></script>
    <!-- bootstrap js -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="<?=base_url().'assets/js/sweetalert2@9.js'?>"></script>

    <script>
    $(function() {

        $("#fpassword").click(function() {

            $("#LoginDiv").hide();

            $("#ForgetPasswordDiv").show();

        });

        $("#Llogin").click(function() {

            $("#LoginDiv").show();

            $("#ForgetPasswordDiv").hide();

        });

    });
    </script>
    <script>
    $(function() {

        $("#process_form").submit(function(e) {
            e.preventDefault();
            var process_ids = $("#process_elem").val();

            console.log(process_ids);

            var email = $("#email").val();
            var password = $("#password").val();

            $.ajax({
                url: '<?php echo base_url('dashboard/login_in_process')?>',
                type: 'POST',
                data: {
                    'process_ids': process_ids,
                    'email': email,
                    'password': password
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status) {
                        window.location.reload();
                    } else {
                        Swal.fire("Warning!", data.message, "warning");
                    }
                }
            });
        });

        $("#ForgetPassword").submit(function(e) {

            e.preventDefault();

            $.ajax({

                url: '<?php echo base_url('dashboard/forgot_password')?>',

                type: 'POST',

                data: $(this).serialize(),
                success: function(data) {
                    console.log(data);
                    if (data == 1) {

                        Swal.fire({
                            title: "Congratulation",
                            text: "Your password reset link is sent on your email id",
                            icon: "success",
                            button: "OK",
                        }).then((value) => {

                            location.reload();


                        });
                    } else if (data == 3) {
                        Swal.fire("Warning!", "This email doesn't exist", "warning");
                    } else if (data == 4) {
                        Swal.fire("Warning!", "Email is not configured", "warning");
                    } else if (data == 5) {
                        Swal.fire("Warning!", "Please Check Employee Code And Email Id Are Not Associated With Same Account!", "warning");
                    } else if (data == 99) {
                        Swal.fire({
                            title: "Congratulation",
                            text: "Please Enter otp received on phone",
                            icon: "success",
                            button: "OK",
                        }).then((value) => {

                            $("#otp").css('display', 'block');
                            $("#votp").css('display', 'block');
                            $("#fsubmit").css('display', 'none');


                        });
                    } else {
                        Swal.fire({
                            title: "Warning",
                            text: "Password reset link is already sent to your email id",
                            icon: "warning",
                            button: "OK",
                            dangerMode: true,
                        })
                    }
                }
            });
        });
    });

    $("#loginForm").on("submit", function(e) {
        e.preventDefault();
        var form = $(this);
        var para = '';
        if ("<?=$this->input->get('type')?>") {
            para = "?type=" + "<?=$this->input->get('type')?>";
        }
        var url = form.attr('action') + para;
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data) {
                data = JSON.parse(data);
                if (data.process) {
                    if (data.status) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: form.serialize(), // serializes the form's elements.
                            success: function(data) {
                                data = JSON.parse(data);
                                if (data.status) {
                                    $("#process_html").html(data.process);
                                    $("#process_modal").modal('show');
                                } else {
                                    Swal.fire({
                                        title: "Warning",
                                        html: data.message,
                                        icon: "warning",
                                        button: "OK",
                                        dangerMode: true,
                                    });
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Warning",
                            html: data.message,
                            icon: "warning",
                            button: "OK",
                            dangerMode: true,
                        })
                    }

                } else {
                    if (data.status) {
                        window.location.reload();
                    } else {
                        Swal.fire({
                            title: "Warning",
                            html: data.message,
                            icon: "warning",
                            button: "OK",
                            dangerMode: true,
                        })
                    }
                }
            }
        });
    });
    $("#signup").on('click', function() {
        $.ajax({
            url: '<?php echo base_url('auth/signup_content')?>',
            type: 'POST',
            data: {
                c: "<?=!empty($_GET['c'])?$_GET['c']:''?>"
            },
            success: function(data) {
                $("#login-title").html('Please signup');
                $("#LoginDiv").html(data);
            }
        });
    });

    function verifyOTP() {
        var mobno = $("#femail").val();
        var otp = $("#otp").val();
        $.ajax({
            url: '<?=base_url();?>/dashboard/verifyOTP',
            type: "post",
            data: {
                'mobno': mobno,
                'otp': otp
            },
            dataType: "json",
            success: function(data) {
                if (data.status == "verified") {
                    Swal.fire({
                        title: "Congratulation",
                        text: "Your otp has been verified successfully",
                        icon: "success",
                        button: "OK",
                    }).then((value) => {
                        window.location.href = "<?=base_url('change-password')?>/" + data.user;
                        //location.reload();


                    });
                } else {
                    Swal.fire('warning', 'opt you have entered is incorrect please enter valid otp',
                        'warning');
                }
            }

        })
    }
    </script>
</body>

</html>