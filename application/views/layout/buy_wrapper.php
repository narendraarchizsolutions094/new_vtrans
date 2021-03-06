<?php
   defined('BASEPATH') OR exit('No direct script access allowed'); 
   $settings = $this->db->select("site_align")
       ->get('setting')
       ->row();
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
  
  $this->load->model('sell_model');
  $category = $this->sell_model->subCategory();
   
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
  <title>thecrm360 - <?php echo (!empty($title)?$title:null) ?></title>
<?php } ?>
      <!--<link rel="shortcut icon" href="<?= base_url($this->session->userdata('favicon')) ?>">-->
      <link href="<?php echo base_url('assets/css/jquery-ui.min.css') ?>" rel="stylesheet" type="text/css"/>
      <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

      <?php 
      if (!empty($settings->site_align) && $settings->site_align == "RTL") {  
        ?>
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>      
        <link href="<?php echo base_url('assets/css/custom-rtl.css') ?>" rel="stylesheet" type="text/css"/>
        <?php 
      } 
      ?>
     <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){  ?> 
    <link rel="icon" href="https://spaceinternationals.com/wp-content/uploads/2018/02/cropped-SPACE-INTERNATIONALS-LOGO-02-1-32x32.jpg" sizes="32x32" />
<?php }else{ ?>
  <link rel="icon" href="https://archizsolutions.com/wp-content/uploads/2018/03/cropped-Archiz-logo-1-32x32.jpg" sizes="32x32" />
<?php } ?>
      
      <!-- Font Awesome 4.7.0 -->
      <link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css"/>
      <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
      <!-- semantic css -->
      <link href="<?php echo base_url(); ?>assets/css/semantic.min.css" rel="stylesheet" type="text/css"/>
      <!-- sliderAccess css -->
      <link href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.min.css" rel="stylesheet" type="text/css"/>
      <!-- slider  -->
      <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" type="text/css"/>
      <!-- DataTables CSS -->
      <link href="<?= base_url('assets/datatables/css/dataTables.min.css?v=1.0') ?>" rel="stylesheet" type="text/css"/>
      <!-- pe-icon-7-stroke -->
      <link href="<?php echo base_url('assets/css/pe-icon-7-stroke.css?v=1.0') ?>" rel="stylesheet" type="text/css"/>
      <!-- themify icon css -->
      <link href="<?php echo base_url('assets/css/themify-icons.css?v=1.0') ?>" rel="stylesheet" type="text/css"/>
      <!-- Pace css -->
      <link href="<?php echo base_url('assets/css/flash.css') ?>" rel="stylesheet" type="text/css"/>
      <!-- Theme style -->
      <link href="<?php echo base_url('assets/css/custom.css?v=1.0') ?>" rel="stylesheet" type="text/css"/>
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
      <?php 
      if (!empty($settings->site_align) && $settings->site_align == "RTL") {  
        ?>
        <!-- THEME RTL -->
        <link href="<?php echo base_url('assets/css/custom-rtl.css?v=1.0') ?>" rel="stylesheet" type="text/css"/>
      <?php 
      } 
      ?>
      <!-- jQuery  -->

      <script src="<?php echo base_url('assets/js/jquery.min.js?v=1.0') ?>" type="text/javascript"></script>
      

      
    
      <style>
      .content {
       /*min-height: 900px;
       margin-right: auto;
       margin-left: auto;
       padding: 0 10px 10px !important;*/
      }
     .badge-notify{
      background-color: #db2828 !important;
      position:relative;
      top: -46px;
      left: 16px;
      }     
      body{       
       font-family: 'Montserrat';
      }
      label{       
       font-family: 'Montserrat';
      }
      td,th{       
       font-family: 'Montserrat';
      }     
      p,a,label,span{
        font-family: 'Montserrat';       
      };
      div{
        font-family: 'Montserrat';
      };
  </style>
      
</head>
   <body class="sidebar-mini <?php if($this->session->menu==1){echo 'sidebar-collapse';}?>" data-baseUrl="<?php echo base_url(); ?>">      
      <style>
        .main-header .logo{
          background-color:#fff !important;
        }
        .sidebar-menu > li > ul > li.active > a {
          border-left: 3px solid #37a000;     
        }
        .navbar-nav > li > a > i {
         border :1px solid #fff !important;
         padding: 12px 3px;
         width: 24px;
         text-align: center;
         color: #374767;
         background-color: #fff !important;
         height: 29px;
         font-size: 19px;
       }
        .icon_color{
          background:#fff !important;border:none!important;color:green!important;
        }   
         /* .main-header .logo{box-shadow:0 0 0 0px !important;} */ 
        .main-header { 
          position: fixed!important;width:100%!important;
        }

      </style>
      <!----------------------------------------------------------------------------------------------------alert reminder------------------------------------------------------------->
      <!----------------------------------------------------------------------------------------------------alert reminder------------------------------------------------------------->
      <style>
      .dialog-overlay{
        display: none;
        position: fixed;
        top:0;
        left:0;

        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.3);
        z-index: 9999999;
      }

/* The dialogs themselves */

    .dialog-card{
      box-sizing: border-box;
      width: 570px;
      position: absolute;
      left: 50%;
      margin-left: -285px;
      top: 20%;

      font: bold 14px sans-serif;

      border-radius: 3px;
      background-color:  #ffffff;
      box-shadow: 1px 2px 4px 0 rgba(0, 0, 0, 0.12);
      padding: 45px 50px;
    }

  .dialog-card .dialog-question-sign{
    float: left;
    width: 68px;
    height: 68px;
    border-radius: 50%;
    color:  #ffffff;
    text-align: center;
    line-height: 68px;
    font-size: 40px;
    margin-right: 50px;
    background-color:  #b4d8f3;
  }

  .dialog-card .dialog-info{
    float: left;
    max-width: 350px;
  }

  .dialog-card h5{  /* Dialog title */
    color:  #383c3e;
    font-size: 24px;
    margin: 5px 0 30px;
  }

  .dialog-card p{   /* Dialog text */
    color:  #595d60;
    font-weight: normal;
    line-height: 21px;
    margin: 30px 0;
  }

  .dialog-card .dialog-confirm-button,
  .dialog-card .dialog-reject-button{
    font-weight: inherit;
    box-sizing: border-box;
    color: #ffffff;
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.12);
    padding: 12px 40px;
    border: 0;
    cursor: pointer;
    outline: 0;
  }

  .dialog-card .dialog-confirm-button{
    background-color:  #87bae1;
    margin-right: 12px;
  }

  .dialog-card .dialog-reject-button{
    background-color:  #e4749e;
  }

  .dialog-card button:hover{
    opacity:0.96;
  }

  .dialog-card button:active{
    position:relative;
    bottom:-1px;
  }


  /*bell notificaiton style start*/
  .notify-detail{
    width: 400px; 
    max-height: 500px;
    overflow-y: scroll;
    top:60px;
    border-width: 1px;
    border-color: darkslategray;
    text-align: center;
  }
  .bell-tab-content{
    margin: 13px 13px;
  }
  /*bell notificaiton style end*/


/* search icon style start */
  .master-search{
    opacity: 0;
    -webkit-transition: all .5s ease;
    -moz-transition: all .5s ease;
    transition: all .5s ease;
    width:0px;
  }
  .master-search.expanded {
    opacity: 1;
  }
.dropdown-large {
  position: static !important;
}
.dropdown-menu-large {
  margin-left: 16px;
  margin-right: 16px;
  padding: 20px 0px;
  left: -131px;
  min-width:250px;
}

@media (max-width: 768px) {
  .dropdown-menu-large {
    margin-left: 0 ;
    margin-right: 0 ;
  }
  .dropdown-menu-large > li {
    margin-bottom: 30px;
  }
  .dropdown-menu-large > li:last-child {
    margin-bottom: 0;
  }
  .dropdown-menu-large .dropdown-header {
    padding: 3px 15px !important;
  }
}
 .dropdown-menu-large .cart-items{
	padding: 0px 14px;
	border-bottom: 1px solid #f7f7f7;
}
.checkout-btn{
	background-color: red !important;
  line-height: 31px;
  margin: 0px 15px;
	width:85%;
}
/* search icon style end */

</style>


<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script>
   function send_data(){
       $.ajax({
         type: "POST",
         url: "<?php echo base_url();?>lead/alertList", 
         success: 
              function(data){
                  var obj = JSON.parse(data);
                  if(obj.status1==1){ document.getElementById("popupadd").innerHTML = obj.status_data;document.getElementById("cross_id").innerHTML = obj.status_id; $("#my-confirm-dialogg").fadeIn(750);
                  }else{  
                  }
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
       url: "<?php echo base_url();?>Lead/alertstatus/"+id, 
       success: 
        function(data){ 
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

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
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
         <header class="main-header" >
            <?php 
            $logo = $this->session->userdata('logo'); 
            ?>
            <a href="<?php echo base_url('dashboard/home') ?>" class="logo" >
               <!-- Logo -->
               <span class="logo-mini">
         <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){  ?> 
    <img src="<?php echo base_url("assets/images/lgo.png") ?>" alt="">
<?php }else{ ?>
  <img src="<?php echo base_url("assets/images/new_logo.png") ?>" alt="">
<?php } ?>
               </span>
               <span class="logo-lg">
         <?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
if($root=='https://student.spaceinternationals.com'){  ?> 
    <img src="<?php echo base_url("assets/images/lgo.png") ?>" alt="">
<?php }else{ ?>
  <img src="<?php echo base_url("assets/images/new_logo.png") ?>" alt="">
<?php } ?>
               </span>
            </a>
            <nav class="navbar navbar-static-top" >
               <a href="#" class="sidebar-toggle" id="something" data-toggle="offcanvas" role="button">
               <span class="sr-only">Toggle navigation</span>
               <span class="pe-7s-keypad"></span>
               </a>                    
               <div class="navbar-custom-menu" id='title' style="float:left;margin-top:10px;margin-left:20px;">
                  <?php 
                  if(!empty($title)){ ?>                            
                    <h1 style="font-size: 26px;"><?php echo $title; ?></h1>
                  <?php }else{ ?>                                
                    <h1 style="font-size: 26px;"><?php echo str_replace('_', ' ', ucfirst($segment1)) ?></h1>
                  <?php } ?>
               </div>
               <div class="navbar-custom-menu">
                  <ul class="nav navbar-nav">   
					   <li  class="dropdown  dropdown-user" style="height: 1px;">
                      <?php
					$cartarr = array();	
					  if(!empty($this->cart->contents())) {
										
								$cartarr = $this->cart->contents();

							}
										?>
								  <a href = "#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class="caret"></span>
								  </a>
								  <span class="badge badge-notify"  id = "nav-cart-count"><?php echo count($cartarr); ?></span>
								 
							<div class="dropdown-menu dropdown-menu-large">	  
								  <ul class ="cart-dropdown-menu"  id = "cart-nav-menu" style = "padding:0px;list-style:none;">
								<?php 
								
								
								if(!empty($this->cart->contents())) {
										
										$cartarr = $this->cart->contents();
										foreach($cartarr as $ind => $cart) {
										
											?><li id = "cart-li-<?php echo $cart['id']; ?>" > 
										
											<div class = "cart-items"><h4><a href = ""> <?php echo $cart['name'] ?></a></h4>
													<p><a href = ""> Price : <i class = "fa fa-price"></i> <?php echo  $cart['price']." X ".$cart['qty']; ?> = <i class = "fa fa-rupee"></i> <?php echo $cart['price']*$cart['qty']  ?> </a> 
														
													</p>
													<hr />
													</div>
											</li><?php
										} ?>
											
							<?php	} ?>	
										
								  </ul>
								  <ul style = "padding:0px;list-style:none;">
								  <li><a class = "btn btn-danger checkout-btn" href = "<?php echo base_url("buy/checkout") ?>">Check Out</a></li>
								  </ul>
							</div>	
					 </li>
                     

               <li class="dropdown dropdown-user" style="height: 1px;" id="notification_dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="anch_notification_dropdown"><i class="fa fa-bell-o" style="background:#fff !important;border:none!important;color:green;"></i></a>
                  <span class="badge badge-notify" id="bell_notifications_count">0</span>

                  <ul class="dropdown-menu notify-detail" id="notification_dropdown_tabs">
                  </ul>  

              </li> <li><a href="#" id="test_id"></a></li>             
                 <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings" style="background:#fff !important;border:none!important;color:green;"></i></a>
                    <ul class="dropdown-menu">
                       <li><a href="<?php echo base_url('dashboard/form'); ?>"><i class="pe-7s-users"></i> <?php echo display('profile') ?></a></li>
                       <li><a href="<?php echo base_url('setting/change_password')?>"><i class="pe-7s-lock"></i> <?php echo display('change_password'); ?></a></li>
                       <li><a href="<?php echo base_url('logout') ?>"><i class="pe-7s-key"></i> <?php echo display('logout') ?></a></li>
                    </ul>
                 </li>
              </ul>               
            </nav>
         </header>
         <aside class="main-sidebar">
            <div class="sidebar">
               <div class="user-panel text-center">
                  <?php $picture = $this->session->picture; ?>
                  <div class="image">
                     <img src="<?php if(!empty($picture)){ echo base_url().$picture;}else{echo base_url()."assets/images/no-img.png";} ?>" class="img-circle" onerror="this.style.display='none'">
                  </div>
                  <div class="info">
                     <p><?php echo $this->session->userdata('fullname') ?></p>
                     <a href="#"><i class="fa fa-circle text-success"></i>
                     </a>
                  </div>
			
               </div>
               <ul class="sidebar-menu">
       
                  <li class="<?php echo (($segment1 == 'buy' && empty($_GET)) ? "active" : null) ?>">
                     <a href="<?php echo base_url('buy') ?>">
                    All Category          
                     </a>
                  </li>
			
        
     
			
			 <?php       
        if(!empty($category)) {				
				 		foreach($category as $ind => $ctg) {							
							if(!empty($ctg['sub'])){
								$treemenu  =  true;
							}else{
								$treemenu  =  false;
							}						
						?>
							<li class="<?php echo ((!empty($_GET['c']) && $_GET['c'] == $ind) ? "active" : null) ?> <?php echo ($treemenu == true) ? "treeview" : ""; ?>">								
										<?php if(($treemenu == false)) { ?>											
										 <a href="<?php echo base_url('buy?c='.$ind); ?>">
										 <i class="fa fa-list-alt"></i>  <?php echo $ctg['title']; ?>							  
										 </a>											
										<?php }else{ ?>
												 <a href="#" onclick='window.location = "<?php echo base_url('buy?c='.$ind); ?>"'>
										 <i class="fa fa-list-alt" style="color:#fff;font-size:17px;background:#2ecc71;padding:7px;border-radius:4px;width:30px;"></i> &nbsp;<?php echo $ctg['title']; ?>   <span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								  </span>
							  
										 </a>
											
										<?php } ?>
									<?php 
										if(!empty($ctg['sub'])){
									?> <ul class="treeview-menu <?php echo (!empty($_GET['c']) && $_GET['c']==$ind)?'active':'' ?>"><?php	
											foreach($ctg['sub'] as $cind => $sbctg) {											
											?><li class="<?php echo ((!empty($_GET['sc']) && $_GET['sc'] == $sbctg['id']) ? "active" : null) ?>" onclick="if($(this).hasClass('active')){$(this).parent().parent().addClass('active')}"><a href = "<?php echo base_url("buy?sc=".$sbctg['id']); ?>"><?php echo $sbctg['subcat_name']; ?></a></li><?php
											}
											?></ul><?php
										}
											?>
									
									
						  </li>
						
			<?php		}
				 } ?>
				 
				 
				 <!-- 
                  <li class="treeview ">
                    <a href="<?php echo base_url("enq/index") ?>">
                    <i class="fa fa-question-circle-o" style="color:#fff;font-size:20px;background:#008080;padding:7px;border-radius:4px;width:30px;"></i> &nbsp;Category
                    <?php  if($this->session->menu==1){ ?></br><p style="color:#fff;font-size:9px;margin-left:-12px;padding-top:10px;"><?php echo display('enquiry') ?></p> <?php } ?>
          </a>
          </li> -->


               </ul>
            </div>
         </aside>
         <!-- =============================================== -->
         <div class="content-wrapper" style="">
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
                  <a href="<?php echo base_url(); ?>assets/enquiry/sample_format.csv" type="submit" ><?php echo display("download_sample"); ?></a>
               </div>
               <div class="col-md-6">
                  <button class="btn btn-success" type="submit" ><?php echo display('save'); ?></button>            
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo display('close'); ?></button>
               </div>
            </div>
            </form>
         </div>
      </div>

	
	  <script src="<?=base_url().'assets/js/sweetalert2@9.js'?>"></script>


<div id="callbreak" class="modal fade" role="dialog" >
         <div class="modal-dialog">
            <!-- Modal content-->
            <?php echo form_open_multipart('telephony/mark_abilibality','class="form-inner"  ') ?> 
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
                <button class="btn btn-success" type="submit" ><?php echo display('save'); ?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo display('close'); ?></button>
               </div>
            </div>
            </form>
         </div>
      </div>
     
         <script>
    $(document).ready(function(){




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
  var starCountRef = firebase.database().ref('reminders/'+uid).orderByChild('rem_date').equalTo(today_date);
  var starCountRef1 =firebase.database().ref('us/').orderByKey().limitToLast(1);
  //var starCountRef2 =firebase.database().ref('us/').orderByKey().limitToLast(1);
  
  var rem = [];  

  inter = setInterval(function(){    
    rem1 = rem[0];  
    var rem2;
    var rem_keys;
    if(rem1){
      rem2 = Object.values(rem1);
      rem_keys = Object.keys(rem1);
    }      
    i = 0; 
    if (typeof rem2 !== 'undefined') {
      rem2.forEach(function (arrayItem) { 
        notication_id = rem_keys[i];              
        var d = arrayItem.rem_date;
        var t = arrayItem.rem_time;      
        if(t.length>5){
          t =t.substr(0,5);
        }
        d = new Date();
        var c_hrs = d.getHours();
        var c_min = d.getMinutes();
        
        if(parseInt(c_min)<10){
          c_min = '0'+c_min;
        }
        if(parseInt(c_hrs)<10){
          c_hrs = '0'+c_hrs;
        }      
        c_time = c_hrs+':'+c_min;
        
        if(t == c_time){
          count_bell_notification();
          reminder_content = arrayItem.reminder_txt;        
          if(notication_id){
            var url  = "<?=base_url().'notification/web/get_pop_reminder_content'?>";         
            $.ajax({
              url: url,
              type: 'POST',
              data:{
                notication_id:notication_id,
                enq_id:arrayItem.enq_id
              },
              success: function (data){
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
                      if (result.value) { (async () => {
                          const { value: snooze_time } = await Swal.fire({
                            title: 'Enter snooze time',
                            input: 'text',    
                            showCancelButton: true,
                            customClass: 'snooze-time',
                            onOpen: function() {  
                              $('.swal2-input').blur();   
                                $('.swal2-input').timepicker({        
                                  timeFormat: 'h:mm p',
                                  interval: 1,        
                                  zindex:9999999,
                                  defaultTime:formatAMPM(new Date)
                              });  
                            },
                            inputValidator: (value) => {
                              return new Promise((resolve) => {
                                if (value) {
                                  resolve()
                                } else {
                                  resolve('You need to time')
                                }
                              })
                            }
                          })
                          if (snooze_time) {
                            var rem_data = {
                              enq_id : arrayItem.enq_id,
                              rem_date : arrayItem.rem_date,
                              rem_time : format24hours(snooze_time),
                              reminder_txt: arrayItem.reminder_txt,
                              uid: arrayItem.uid
                            };
                            firebase.database().ref('reminders/' + arrayItem.uid + '/' + notication_id).update(rem_data);
                            Swal.fire('Your Reminder Snoozed till '+snooze_time);
                            location.reload();
                          }
                      })()
                    }
                });
              }
            });

          }else{
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

function format24hours(time){
  //var time = $("#starttime").val();
  var hours = Number(time.match(/^(\d+)/)[1]);
  var minutes = Number(time.match(/:(\d+)/)[1]);
  var AMPM = time.match(/\s(.*)$/)[1];
  if(AMPM == "PM" && hours<12) hours = hours+12;
  if(AMPM == "AM" && hours==12) hours = hours-12;
  var sHours = hours.toString();
  var sMinutes = minutes.toString();
  if(hours<10) sHours = "0" + sHours;
  if(minutes<10) sMinutes = "0" + sMinutes;
  return sHours + ":" + sMinutes;
}

function formatAMPM(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
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

starCountRef1.on('value',function(res){
     // console.log(res.val());
        var phone=Object.values(res.val());
          phone.forEach(function (arrayItem) { 
           var phone=arrayItem.user_phone;
           var uid=  arrayItem.uid;
       var phone_s=arrayItem.users;
           phone_s = phone_s.replace(/[^\d]/g, '');
           if(phone.length >= 11){var phone_n = phone.substr(2,12);}else{var phone_n = phone;}
         console.log(phone_s);   
       var user_pho="<?php echo '91'.$this->session->phone;?>"; 
            if(phone_s == user_pho){
         $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>telephony/get_in_status/"+btoa(uid),        
                success: function(data){ 
                  if(data==1){ 
                 Swal.fire({            
                 icon: 'info',
                 html:'<strong>Inbound call with this number.<a href="https://v-trans.thecrm360.com/telephony/forword_to/'+phone_n+'">'+phone_n+'</a></strong><br><a class="btn btn-info" href="https://v-trans.thecrm360.com/telephony/forword_to/'+phone_n+'">Go</a>',
                 showCancelButton: false,
                 showConfirmButton: false,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',             
                 }).then((result) => {
                 if (result.value) {                                 
                     }
          }); 
          }    
        }
           }); 
         }
          });
      
  });
      
  });
function writeUserData(userId,reminder_txt,enq_id,rem_date,rem_time) {
  var rem_data = {
    uid: userId,
    reminder_txt: reminder_txt,
    enq_id : enq_id,
    rem_date : rem_date,
    rem_time : rem_time
  };
  // Get a key for a new Post.
  var newPostKey = firebase.database().ref().child('reminders/'+userId).push().key;
  // Write the new post's data simultaneously in the posts list and the user's post list.
  var updates = {};
  updates['/reminders/' + userId+'/'+newPostKey] = rem_data;
  //updates['/user-posts/' + uid + '/' + newPostKey] = postData;
  firebase.database().ref().update(updates);
  return newPostKey
}

function update_notification(userId,notication_id,reminder_txt,enq_id,rem_date,rem_time){
  var rem_data = {
    enq_id : enq_id,
    rem_date : rem_date,
    rem_time : rem_time,
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


    $('#set_reminder').on('submit',function(e) {  
        e.preventDefault();          
        var uid = "<?=$this->session->user_id?>";
        var rem_date = $("input[name='reminder_date']").val();  
        var rem_time = $("input[name='reminder_time']").val();                                    
        var reminder_txt = $("input[name='reminder_txt']").val();
        var enq_id = '';
        writeUserData(uid,reminder_txt,enq_id,rem_date,rem_time);
        location.reload();
    });

    $("#submit_task_btn").on('click',function(e){
      e.preventDefault();
      subject  = $("input[name='subject']").val();
      time  =   $("input[name='task_time']").val();
      task_date =   $("#enq_task_date").val();            
      var uid = "<?=$this->session->user_id?>";      
      var enq_id = $("input[name='enqCode']").val();
      id  = writeUserData(uid,subject,enq_id,task_date,time);
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
    $("#disposition_save_btn").on('click',function(e){      
      e.preventDefault();      
      
      var name_prefix = $("select[name='name_prefix']").val();
      var enquiry_name = $("input[name='enquirername']").val();
      var lastname = $("input[name='lastname']").val();
      enq_name = name_prefix+' '+enquiry_name+' '+lastname;      
      var disposition = getSelectedText('lead_stage_change');

      var enq_id = $("input[name='enqCode']").val();
      subject  = disposition+' :'+enq_name;
      $("input[name='dis_subject']").val(subject);
      time  =   $("#disposition_c_time").val();      
      task_date =   $("#disposition_c_date").val();            
      var uid = "<?=$this->session->user_id?>";      
      id  = writeUserData(uid,subject,enq_id,task_date,time);
      //console.log(id);
      $("input[name='dis_notification_id']").val(id);
      $("#disposition_save_form").submit();


    });

    $(document).on('click','#task_update_btn',function(e){
      e.preventDefault();
      //alert('abc');
      subject  = $("#task_update_subject").val();
      time  =   $("#task_update_task_time").val();
      task_date =   $("#task_update_enq_task_date").val();            
      var uid = $("input[name='task_update_create_by']").val();     
      var enq_id = $("input[name='task_enquiry_code']").val();
      notication_id  = $("input[name='update_notification_id']").val();
      nid  = update_notification(uid,notication_id,subject,enq_id,task_date,time);
      //console.log(nid);
      $("#update_task_form").submit();    

});  
function count_bell_notification(){
    $.ajax({
       type: "POST",
       url: "<?php echo base_url();?>notification/web/count_bell_notification",        
       success: function(data){              
        $("#bell_notifications_count").html(data);    
      }
    });
  }
$("#anch_notification_dropdown").on('click',function(){  
      var url  = "<?=base_url().'notification/web/get_bell_notification_content'?>";
      $.ajax({
        url: url,
        type: 'POST', 
        beforeSend: function() {
          $("#notification_dropdown_tabs").html('loading...');                
        },                                   
        success: function (data, status){          
            $("#notification_dropdown_tabs").html(data);                
        },
        error: function (xhr, desc, err){
          $("#notification_dropdown_tabs").html("error");
        }
      });
});

  $('#notification_dropdown_tabs').on('click','.nav-tabs a', function(){    
    $(this).closest('.dropdown').addClass('dontClose');
  });
  $('#notification_dropdown').on('hide.bs.dropdown', function(e) {    
      if ( $(this).hasClass('dontClose') ){
          e.preventDefault();
      }
      $(this).removeClass('dontClose');
  });


    </script>
  
      <script>
         $( "#service" ).click(function() {     
             if($('#another-element:visible').length)
                 $('#another-element').hide();
             else
                 $('#another-element').show();        
         });
         $( "#task_create_div" ).click(function() {     
             if($('#task_create:visible').length)
                 $('#task_create').hide();
             else
                 $('#task_create').show();        
         });
         
         function check_stage(id){
             if(id==5){
                document.getElementById('curcit_add').style.display='block'; 
                 document.getElementById('add_po').style.display='none';
             }else if(id==8){
                 document.getElementById('add_po').style.display='block'; 
                 document.getElementById('curcit_add').style.display='none';
             }else{
                document.getElementById('add_po').style.display='none';
               document.getElementById('curcit_add').style.display='none';
             }
         }
          
    $(document).ready(function(){
      var url  = "<?=base_url().'attendance/check_attendance_status'?>";
      $.ajax({
        url: url,
        type: 'POST', 
        beforeSend: function() {
          $("#mark_attendance").html('<i class="fa fa-spinner fa-spin"></i>');                
        },                                   
        success: function (data, status){
          if(data != 'null'){                                 
          jdata = JSON.parse(data);
            $("#mark_attendance").removeClass('btn-primary');
            $("#mark_attendance").addClass('btn-danger');
            $("#mark_attendance").attr('title','Mark attendance');
            $("#mark_attendance").html('<i class="fa fa-clock-o fa-pulse"></i>');
            $("#mark_attendance").attr('data-id',jdata.id);                
          }else{
           $("#mark_attendance").html('<i class="fa fa-clock-o"></i>');                
          }
        },
        error: function (xhr, desc, err){
          alert("mark attendance error");
        }
      });      
    });  
      
        $("#mark_attendance").on('click',function(){      
        var url  = "<?=base_url().'attendance/mark_attendance'?>";
        var atID  = $("#mark_attendance").attr('data-id');
        $.ajax({
            url: url,
            type: 'POST',
            data:{'atID':atID},                        
            beforeSend: function() {
                $("#mark_attendance").html('<i class="fa fa-spinner fa-spin"></i>');                
            },
            success: function (data, status){
              if(data != 'null'){                                 
                data = JSON.parse(data);
                if(data.id=='updated'){
                  $("#mark_attendance").removeClass('btn-danger');                  
                  $("#mark_attendance").addClass('btn-primary');  
                  $("#mark_attendance").attr('title','Mark attendance');

                  $("#mark_attendance").html('<i class="fa fa-clock-o"></i>');
                  $("#mark_attendance").removeAttr('data-id');                
                }else{
                  $("#mark_attendance").removeClass('btn-primary');
                  $("#mark_attendance").addClass('btn-danger');
                  $("#mark_attendance").attr('title','Check out');
                  $("#mark_attendance").html('<i class="fa fa-clock-o fa-pulse"></i>');
                  $("#mark_attendance").attr('data-id',data.id);                                  
                }
              }
            },
            error: function (xhr, desc, err){
              alert("mark attendance error");
            }
        });

    });

        if($(".treeview-menu li").hasClass('active')){
          $(".treeview-menu li.active").parent().prev().parent().addClass('active');
        }
</script>
<script>
$('#something').click(function() {
//alert('hi');  
    //document.location = '<?php echo base_url('dashboard/menu_style'); ?>';
} );
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
      <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>      
      <script src="<?php echo base_url() ?>assets/js/tableHeadFixer.js?v=1.0" type="text/javascript"></script> 
      <!-- Admin Script -->
      <script src="<?php echo base_url('assets/js/frame.js?v=1.0?v=1.0') ?>" type="text/javascript"></script> 
      <!-- Custom Theme JavaScript -->
      <script src="<?php echo base_url() ?>assets/js/custom.js?v=1.0.1" type="text/javascript"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
   </body>
</html>
