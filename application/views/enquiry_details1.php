<?php 
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

?> 

<script src="<?=base_url()?>/assets/summernote/summernote-bs4.min.js"></script>
<link href="<?=base_url()?>/assets/summernote/summernote-bs4.css" rel="stylesheet" />


<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        
<!-- Time line css start -->

<style>

  .hide-timeline{
    display: none;
  }
  #toggle_timeline{
    position: fixed;
    top: 94px;
    right: 0;
  }
  .col-height{
    min-height: 700px;
    max-height: 700px;
    overflow-y: auto;
    border-bottom: solid #c8ced3 1px;
  }
  /* select2 css start*/
  .select2-container--default .select2-selection--single .select2-selection__arrow b:before {
    content: "";
  }
  /* select2 css end*/

.cbp_tmtimeline {
  margin: 30px 0 0 0; 
  padding: 0;
  list-style: none;
  position: relative;
} 

/* The line */
.cbp_tmtimeline:before {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 10px;
  background: #afdcf8;
  left: 20%;
  margin-left: -10px;
}

.cbp_tmtimeline > li {
  position: relative;
}

/* The date/time */
.cbp_tmtimeline > li .cbp_tmtime {
  display: block;
  width: 25%;
  padding-right: 100px;
  position: absolute;
}

.cbp_tmtimeline > li .cbp_tmtime span {
  display: block;
  text-align: right;
}

.cbp_tmtimeline > li .cbp_tmtime span:first-child {
  font-size: 0.9em;
  color: #bdd0db;
}

.cbp_tmtimeline > li .cbp_tmtime span:last-child {
  font-size: 2.9em;
  color: #3594cb;
}

.cbp_tmtimeline > li:nth-child(odd) .cbp_tmtime span:last-child {
  color: #6cbfee;
}

/* Right content */
.cbp_tmtimeline > li .cbp_tmlabel {
  margin: 0 0 15px 25%;
  background: #3594cb;
  color: #fff;
  padding: 10px;
  font-size: 1.2em;
  font-weight: 300;
  line-height: 1.4;
  position: relative;
  border-radius: 5px;
}

.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel {
  background: #6cbfee;
}

.cbp_tmtimeline > li .cbp_tmlabel h2 { 
  margin-top: 0px;
  padding: 0 0 10px 0;
  border-bottom: 1px solid rgba(255,255,255,0.4);
}

/* The triangle */
.cbp_tmtimeline > li .cbp_tmlabel:after {
  right: 100%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
  border-right-color: #3594cb;
  border-width: 10px;
  top: 10px;
}

.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel:after {
  border-right-color: #6cbfee;
}

/* The icons */
.cbp_tmtimeline > li .cbp_tmicon {
  width: 40px;
  height: 40px;
  font-family: 'ecoico';
  speak: none;
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  text-transform: none;
  font-size: 1.4em;
  line-height: 40px;
  -webkit-font-smoothing: antialiased;
  position: absolute;
  color: #fff;
  background: #46a4da;
  border-radius: 50%;
  box-shadow: 0 0 0 8px #afdcf8;
  text-align: center;
  left: 20%;
  top: 0;
  margin: 0 0 0 -25px;
}

.cbp_tmicon-phone:before {
  content: "???";
}

.cbp_tmicon-screen:before {
  content: "???";
} 

.cbp_tmicon-mail:before {
  content: "???";
}

.cbp_tmicon-earth:before {
  content: "???";
}

/* Example Media Queries */
@media screen and (max-width: 65.375em) {
  .cbp_tmtimeline > li .cbp_tmtime span:last-child {
    font-size: 1.5em;
  }
}

@media screen and (max-width: 47.2em) {
  .cbp_tmtimeline:before {
    display: none;
  }

  .cbp_tmtimeline > li .cbp_tmtime {
    width: 100%;
    /*position: relative;*/
    padding: 0 0 20px 0;
  }

  .cbp_tmtimeline > li .cbp_tmtime span {
    text-align: left;
  }

  .cbp_tmtimeline > li .cbp_tmlabel {
    margin: 0 0 30px 0;
    padding: 1em;
    font-weight: 400;
    font-size: 95%;
  }

  .cbp_tmtimeline > li .cbp_tmlabel:after {
    right: auto;
    left: 20px;
    border-right-color: transparent;
    border-bottom-color: #3594cb;
    top: -20px;
  }

  .cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel:after {
    border-right-color: transparent;
    border-bottom-color: #6cbfee;
  }

  .cbp_tmtimeline > li .cbp_tmicon {
    position: relative;
    /*float: right;*/
    left: auto;
    margin: -55px 5px 0 10px;
  } 
}    
</style>

<!-- Time line css end -->


<style type="text/css">
    [data-lettersEmployee]:before  
   {
    content:attr(data-lettersEmployee);
    display:inline-block;
    font-size:1em;
    width:2.5em;
    height:2.5em;
    line-height:2.5em;
    text-align:center;
    border-radius:50%;
    background:#2F353A;
    vertical-align:middle;
    margin-right:1em;
    color:white;
   }
   [data-black]:before {
    content: attr(data-black);
    display: inline-block;
    font-size: 1em;
    width: 4.5em;
    height: 4.5em;
    line-height: 4.5em;
    text-align: center;
    border-radius: 50%;
    background: #283593;
    vertical-align: middle;
    margin-right: 2em;float:left;
    color: white;
   }
   [data-lettersGenerated]:before {
    content: attr(data-lettersGenerated);
    display: inline-block;
    font-size: 1em;
    width: 2.5em;
    height: 2.5em;
    line-height: 2.5em;
    text-align: center;
    border-radius: 50%;
    background: #5FBD75;
    vertical-align: middle;
    margin-right: 1em;
    color: white;
   }
   .nav-tabs > li.active > a:hover {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: none!important;
   }
   .nav-tabs > li.active > a {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: none!important;
   }
   table th,td{font-size:11px;}
   .list-group{margin-top:10px!important;}
   .btnStatus{
    padding: 0px 4px !important;
    color: #fff !important;
    width: 36% !important; 
   }
   .Pending{
   background-color: #337ab7 !important;
   border-color: #337ab7 !important;
   }
   .Processing{
   background-color: #f2711c !important;
   border-color: #f2711c !important;
   }
   .Completed{
   background-color: #37a000 !important;
   border-color: #318d01 !important;
   }
   .Closed{
   background-color: #db2828 !important;
   border-color: #db2828 !important;
   }
   .nav-tabs >li {
    background-color: #283593;
}
#exTab3 .nav-tabs > li > a {
    color: #fff;
}
.nav-tabs > li.active > a {
    color: #555 !important;
    background-color: #fff;
}
#timeline{
    display: none;
}
#disposition{
  display: none;
}
@media screen and (max-width: 900px) {
  #timeline{
    display: inline-block;
  }
  #disposition{
    display: inline-block;
  }
  .mobile-hide{
    display: none;
  }
  .col-height{
    min-height:unset!important;
  }
  .activitytimelinediv{
    display: none;
  }
}

</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<div class="row">
   <div class="col-md-12 mobile-center" style="background-color: #fff;border-bottom: 1px solid #C8CED3;">
      <div class="col-md-6" > 
        <p style="margin-top: 6px;">
      </p>
        <!-- Enquiry / Update Enquiry -->
      </div>
      <div class="col-md-6" >
         <div style="float:right">
           
         </div>
      </div>
   </div>

<!-- Edit dynamic query -->

<div id="edit_dynamic_query" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
          <div id="edit_dynamic_query_data" class="row">
           
            </div>
      
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>
  </div>
</div>
<!--  -->
<!-- For Invnetory COmpany -->
  <div id="addnewdeal" class="modal fade" role="dialog">
  <div class="modal-dialog">
   <form action="<?php echo base_url(); ?>client/re_oreder" method="post" class="form-inner">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Deals</h4>
      </div>
      <div class="modal-body">
        
          <div class="row">
            <div class="form-group col-sm-6">  
            <label>Expected Closure Date</label>                  
            <input class="form-control"  name="expected_date" type="date" readonly>                
            </div>
            
            <div class="form-group col-sm-6">
            <label class="col-form-label">Conversion Probability</label>
            
            <select class="form-control" id="LeadScore" name="lead_score">
            <option></option>                                               
            <?php foreach ($lead_score as $score) {  ?>
                <option value="<?= $score->sc_id?>"><?= $score->score_name?>&nbsp;<?= $score->probability?></option>
                <?php } ?>                       
            </select>
            </div>
            <div class="form-group col-sm-6">  
            <label>Add Comment</label>                  
            <input class="form-control" id="LastCommentGen" name="comment" type="text">
            <input class="form-control" value="<?php echo $leadid; ?>" name="child_id" type="hidden">                
            </div>
          
            <div class="form-group col-sm-12">        
            <input  class="btn btn-success" type="submit" value="Create New Deals " >      
            </div>
            </div>
      
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
     </form>
  </div>
</div>
<!-- END -->
<!-- For pasia EXpo -->

  

   <div  class="row" id="ThreadEnq">
      <div class="col-md-3 col-xs-12 col-sm-12 col-height" style="text-align:center;background:#fff;">
      
         <h5 style="text-align:center"><br><?=ucwords($enquiry->name_prefix." ".$enquiry->name." ".$enquiry->lastname); ?>
            <br><?php if($enquiry->gender == 1) { echo 'Male<br>'; }else if($enquiry->gender == 2){ echo 'Female<br>'; }else if($enquiry->gender == 3){ echo 'Other<br>';} 
            $p = $enquiry->phone;
            if (user_access(450)) {
              $p = '##########';
            }
            ?>
            
            <!--<a href='javascript:void(0)' onclick='send_parameters("<?php echo $enquiry->phone ?>")'><?php echo $p ?></a>-->
			<a href='javascript:void(0)'><?php echo $p ?></a>
            <br><?php if(!empty($enquiry->email)) { echo $enquiry->email; } 
            
            
            
            
            if (!empty($enquiry->tag_ids)) {

               $this->db->select('title,color,id,created_by');
               $this->db->where("id IN(" . $enquiry->tag_ids . ")");
               $tags = $this->db->get('tags')->result_array();

               if (!empty($tags)) {
                   foreach ($tags as $key => $value) { ?>
                       <div>
                           <a class="badge" href="javascript:void(0)"
                              style="background:<?php echo $value['color'] ?> ;padding:4px;"><?php echo $value['title'] ?>
                           </a>
                           <a href="<?= base_url('enq/drop_tag/') . $value['id'] ?>"
                              id="tagDrop" data-id="<?= $value['id'] ?>" data-enq="<?= $enquiry->enquiry_id; ?>"
                              class="text-danger" title="Drop Tag"><i
                                       class="fa fa-close"></i></a>
                       </div>
                   <?php }
               }
           }            
         ?>        
         </h5>
         <div class="row">
            <?php if($enquiry->drop_status>0){ ?>
            <a class="btn btn-danger btn-outline-dark btn-md"  href="<?php echo base_url();?>enquiry/active_enquery/<?php echo $enquiry->enquiry_id ?>" title="Active"  data-toggle="modal">
            <i class="fa fa-user-times"></i>
            </a>
            <?php 
            } else { 

              $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');
              if (!empty($enquiry_separation)) {
                $enquiry_separation = json_decode($enquiry_separation,true);          
                $curr_stage  = $enquiry->status;
                $next_stage = $enquiry->status+1;      
              }
              ?>
                             
                <a class="btn btn-primary btn-sm"  data-toggle="modal" type="button" title="Send SMS" data-target="#sendsms<?php echo $enquiry->enquiry_id ?>" data-toggle="modal"  onclick="getTemplates('2','Send SMS')">
                <i class="fa fa-paper-plane-o"></i>
                </a>
                <a class="btn btn-info btn-sm"  data-toggle="modal" type="button" title="Send Email" data-target="#sendsms<?php echo $enquiry->enquiry_id ?>" data-toggle="modal"  onclick="getTemplates('3','Send Email')">
                <i class="fa fa-envelope"></i>
                </a>
                <a class="btn btn-primary btn-sm"  data-toggle="modal" type="button" title="Send Whatsapp" data-target="#sendsms<?php echo $enquiry->enquiry_id ?>" data-toggle="modal"  onclick="getTemplates('1','Send Whatsapp')">
                <i class="fa fa-whatsapp"></i>
                </a>
               <?php   if ($this->session->companey_id==65 && $enquiry->status!=1) {   ?>         

               <!-- // multiple move buttons  -->
               <div class="dropdown" style="display: inline-block;"> 
                  <button class="btn  btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <i class="fa fa-thumbs-o-up"></i>
                     <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="margin-left: -70px;">
                    <?php  if($enquiry->status!=2){
                    ?>  <li><a title="Move to <?=display('lead')?>"  data-target="#genLead" data-toggle="modal" style="font-size: 14px;">Move to <?=display('lead')?></a></li>
                     <?php } ?>
                    <?php  if($enquiry->status!=3){ ?>

                     <!--<li><a  title="Move to <?=display('client')?>" href="<?=base_url().'lead/any_convert_to_any/'.$enquiry->status.'/'.$enquiry->enquiry_id.'/3'?>" onclick="return confirm('Are you sure you want to move this <?=display('lead')?> to <?=display('client')?> ?')" style="font-size: 10px;">Move to <?=display('client')?></a></li>-->
           <li><a onclick="get_modal_move(<?=$data_type?>,<?= $enquiry->enquiry_id; ?>,'3')">
                        Move to <?=display('client')?>
                     </a></li>
                    <?php }

                    if (!empty($enquiry_separation)) {
                     // $enquiry_separation = json_decode($enquiry_separation, true);
                         foreach ($enquiry_separation as $key => $value) {
                        if($enquiry->status!=$key){ ?>                   
                       <!--<li><a  title="" href="<?=base_url().'lead/any_convert_to_any/'.$enquiry->status.'/'.$enquiry->enquiry_id.'/'.$key?>" onclick="return confirm('Are you sure you want to Move this <?=display('client')?> to <?=$enquiry_separation[$key]['title']?> ?')" style="font-size: 10px;"> Move to <?=$enquiry_separation[$key]['title']?></i>
                        </a></li>-->
            <li><a onclick="get_modal_move(<?=$data_type?>,<?= $enquiry->enquiry_id; ?>,<?= $key; ?>)">
                        Move to <?=$enquiry_separation[$key]['title']?>
                        </a></li>
                    <?php } 
                    }
                   }  ?>
                  </ul>
          
<!---------------------------------------------Stage move popup Start------------------------------>
<a title="Mark as <?=display('lead')?>"  data-target="#movelead" data-toggle="modal" id="movepop"></a>
      <div id="movelead" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <?php echo form_open_multipart('lead/any_convert_to_any','class="form-inner"') ?>
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Select Deals and Move</h4>
               </div>
               <div class="modal-body" style="padding-bottom:100px;">
                  <!--<form method="post" action="">-->
                  <!--<?php //echo form_open_multipart('enquiry/move_to_lead','class="form-inner"') ?>   -->
                 <div id="deal-move-content">
         
                 </div>
            <div class="col-md-2"  id="save_button">
                           <div class="col-md-12">                                                
                              <button class="btn btn-primary" type="submit" >Move</button>            
                           </div>
                    </div>
        
                  </form>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
  
<script>
    function get_modal_move(current,id,next){ 
        //alert(next);
       var cls = document.getElementById("movepop");    
       $.ajax({
          url: "<?php echo base_url().'lead/get_all_stage_deals'?>",
          type: 'POST',
          data:{current_stg:current,enq_id:id,next_stg:next},          
          success: function(content) {                       
            $("#deal-move-content").html(content);
            cls.click();
      $("#deal-move-content select").select2();
          }
      });
    
    }
</script>
<!---------------------------------------------stage move popup End------------------------------>
          
                  </div>  
               <!-- // multiple move buttons end  -->
               <a class="btn btn-danger btn-sm"  type="button" title="Drop Lead" data-target="#dropEnquiry" data-toggle="modal">
                  <i class="fa fa-thumbs-o-down"></i>
                </a>
                <?php }else{
                   if($enquiry->status==1){ ?>
                
                <a class="btn  btn-info btn-sm"  type="button" title="Mark as <?=display('lead')?>"  data-target="#genLead" data-toggle="modal">
                <i class="fa fa-thumbs-o-up"></i>
                </a>
                <a class="btn btn-danger btn-sm"  type="button" title="Drop <?=display('enquiry')?>" data-target="#dropEnquiry" data-toggle="modal">
                <i class="fa fa-thumbs-o-down"></i>
                </a>
                <?php }elseif ($enquiry->status==2) { ?>
                  <a class="btn  btn-info btn-sm" title="Mark as <?=display('client')?>" href="<?=base_url().'lead/convert_to_lead/'.$enquiry->enquiry_id?>" onclick="return confirm('Are you sure you want to Mark this <?=display('lead')?> as <?=display('client')?> ?')" >
                  <i class="fa fa-user"></i>
                </a>
                  <a class="btn btn-danger btn-sm"  type="button" title="Drop Lead" data-target="#dropEnquiry" data-toggle="modal">
                  <i class="fa fa-thumbs-o-down"></i>
                </a>
                  <?php
                }elseif ($enquiry->status==3) { 
                  if (!empty($enquiry_separation)) {

                    if (!empty($enquiry_separation[$next_stage])) {
                       ?>                   
                        <a class="btn  btn-info btn-sm" title="Mark as <?=$enquiry_separation[$next_stage]['title']?>" href="<?=base_url().'lead/convert_to_lead/'.$enquiry->enquiry_id?>" onclick="return confirm('Are you sure you want to Mark this <?=display('client')?> as <?=$enquiry_separation[$next_stage]['title']?> ?')" > <i class="fa <?=$enquiry_separation[$next_stage]['icon']?>"></i>
                        </a>
                    <?php
                    }
                  }
                  ?>
                  <a class="btn btn-danger btn-sm"  type="button" title="Drop <?=display('client')?>" data-target="#dropEnquiry" data-toggle="modal">
                    <i class="fa fa-thumbs-o-down"></i>
                  </a>
                  <?php
                }else{                
                  if (!empty($enquiry_separation)) {                    
                    if (!empty($enquiry_separation[$next_stage])) {
                      ?>
                      <a class="btn  btn-info btn-sm" title="Mark as <?=$enquiry_separation[$next_stage]['title']?>" href="<?=base_url().'lead/convert_to_lead/'.$enquiry->enquiry_id?>" onclick="return confirm('Are you sure you want to Mark this <?=$enquiry_separation[$curr_stage]['title']?> as <?=$enquiry_separation[$next_stage]['title']?> ?')" > <i class="fa <?=$enquiry_separation[$next_stage]['icon']?>"></i>
                      </a>
                      <?php
                    }
                    ?>
                    <a class="btn btn-danger btn-sm"  type="button" title="Drop" data-target="#dropEnquiry" data-toggle="modal">
                      <i class="fa fa-thumbs-o-down"></i>
                    </a>
                    <?php
                  }
                }
               }
                ?>                
                <?php 

            }
            ?>
       
            <a class="btn btn-sm  btn-info" title="disposition" id="disposition" href="#" data-toggle="modal" data-target="#dispo_modal" onclick="show_disposition();">
            <i class="fa fa-bars"></i>
            </a>
            <a class="btn btn-sm btn-info" title="Timeline" id="timeline" href="#" onclick="show_timeline();" data-toggle="modal" data-target="#timeline_modal">
                  <i class="fa fa-hourglass-end"></i>
            </a>
         </div>
           <table style="width: 100%;margin-top: 5%;" id="dataTable" class="table table-responsive-sm table-hover table-outline mb-0 mobile-hide" >
              <tbody>
                 <tr>
                    <td><button class="btn btn-basic" type="button" style="width: 100%;">Disposition</button></td>
                 </tr>
              </tbody>
           </table>
         <div id="disposition-section" class="mobile-hide">
              <div class="row" >
                 <?php echo form_open_multipart('lead/update_description/'.$details->enquiry_id,array('id'=>'disposition_save_form','class'=>'form-inner')) ?>
                 <input type="hidden" name="dis_subject">
                 <input type="hidden" name="unique_no" value="<?php echo $details->Enquery_id; ?>" >
                 <input type="hidden" name="url" value="<?php echo $this->uri->segment(1); ?>" >
                 <input type="hidden" name="latest_task_id">
                  <div class="form-group col-sm-12">
                             <!--<label class="col-form-label">Lead Stage</label>-->
                             <select class="form-control" id="lead_stage_change" name="lead_stage" onchange="find_description()">
                                <option>---Select Stage---</option>
                                <?php foreach($all_estage_lists as $single){
                                ?>                              
                                <option value="<?= $single->stg_id?>" <?php if ($single->stg_id == $details->lead_stage) {echo 'selected';}?>><?php echo $single->lead_stage_name; ?></option>
                                <?php } ?>
                             </select>
                      </div>

                      <div class="form-group col-sm-12">                           
                             <select class="form-control" id="lead_description" name="lead_description" onchange="showDiv(this)">
                                 <option>---Select Description---</option>
                              <!--   <?php /*foreach($all_description_lists as $discription){ ?>                                   
                                     <option value="<?php echo $discription->id; ?>"><?php echo $discription->description; ?></option>
                                     <?php }*/ ?> -->
                             </select>
                          </div>


                      <input type="hidden" name="enq_code1"  value="<?php echo  $details->Enquery_id; ?>" >
                    <div class="form-group col-sm-6" style="display:none;">
                    <label>Contact Person Name</label>
                    <input type="text" class="form-control" value="<?php if(!empty($details->name)){echo $details->name;} ?>" name="contact_person1"  placeholder="Contact Person Name">
                 </div>
                 <div class="form-group col-sm-6" style="display:none;">
                    <label>Contact Person Designation</label>
                    <input type="text" class="form-control" name="designation1" value="<?= isset($details->designation)?$details->designation:''?>" placeholder="Contact Person Designation">
                 </div>
                 <div class="form-group col-sm-6" style="display:none;">
                    <label>Contact Person Mobile No</label>
                    <input type="text" class="form-control" value="<?php if(!empty($details->phone)){echo $details->phone;} ?>" name="mobileno1" placeholder="Mobile No">
                 </div>
                 <div class="form-group col-sm-6" style="display:none;">
                    <label>Contact Person Email</label>
                    <input type="text" class="form-control" value="<?php if(!empty($details->email)){echo $details->email;} ?>" name="email1" placeholder="Email">
                 </div>

                <div class="" id="otherTypev">
                    <div class="form-group col-sm-12">
                    <input   name="c_date" id='disposition_c_date' class="form-control form-date" placeholder=""  >
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="time" name="c_time" id='disposition_c_time' class="form-control" placeholder=""  >
                        <input type="hidden" name="dis_notification_id" >
                    </div>
               </div>
                     
                          <div class="form-group col-sm-12">
                                      <!--<label>Remaks</label>-->
                                      <textarea class="form-control" name="conversation"></textarea>
                                  </div>
                 <div class="sgnbtnmn form-group col-md-12">
                    <div class="sgnbtn">
                       <input id="disposition_save_btn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
                    </div>
                 </div>
                 <?php echo form_close()?>
              </div>         
            </div>
            
      </div>

          <style type="text/css">
            .nav-tabs
            {
             overflow-x: hidden;
             overflow-y:hidden;
             white-space: nowrap;
             height: 50px;
            }
            .nav-tabs > li
            {
               white-space: nowrap;
               float: none;
               display: inline-block;
               font-size: 11px;
               padding: 10px 10px;
               color:white; 
               cursor: pointer;
               border:1px solid #283593;
               font-weight: 300;
            }

            .nav-tabs > li.active
            {
              color:#283593;
              background: white;
              font-weight: 600;;
              border: 1px #283593 dotted;
              box-shadow: 1px 2px 5px;
            }
           
          </style> 

      <div class="col-md-6 col-xs-12 col-sm-12 card card-body col-height details-column" style="background:#fff;border-top: unset;">
         <div id="exTab3" class="">
            <ul  class="nav nav-tabs" role="tablist">  
            <span class="scrollTab" style="position: absolute; left: 0; font-size: 22px; line-height: 40px; z-index: 999"><i class="fa fa-caret-left" onclick="tabScroll('left')"></i></span>            
              <li class="active" href="#basic" data-toggle="tab" >Basic</li>  
              
             <?php 
                  if($enquiry->status==3) { 

                 if(in_array(300,$module) || in_array(301,$module) || in_array(302,$module) || in_array(303,$module)){ 
                ?>
                <li  href="#order" data-toggle="tab" >Order Details</li> 
                <?php }
                 }?>     
                 <?php if($this->session->userdata('companey_id')==29){?>  
                <li href="#amount" data-toggle="tab" >Amount</li> 
                <?php }?>      
        

                <?php 
              if(!empty($tab_list)){
                //print_r($tab_list);die;
                foreach ($tab_list as $key => $value) { 
                  if ($value['id'] != 1) { ?>
                    <li href="#<?=str_replace(' ', '_', $value['title'])?>" data-toggle="tab"><?=$value['title']?></li>
                <?php
                  }
                }
              }
              ?>
           <li href="#task" data-toggle="tab" >Task</li>           
           
            
            <?php 
            if(user_access('1000') && $details->status!='1') //
            {  ?>            
            <li href="#COMMERCIAL_INFORMATION" data-toggle="tab">Deals</li>
            <?php
            }
            ?>
            <?php
            if(user_access('1020'))
            {  ?>
            <li href="#vtran_visit" data-toggle="tab" >Visit Details</li>
            <?php
            }
            if(user_access('1004') && $details->status!='1' && $details->status!='2' && $details->status!='3')
            {
            ?>
            <li href="#vtransaggrement" data-toggle="tab" >Agreement</li>
            <?php 
            } 
            if(user_access('1010'))
            { ?>
            <li href="#company_contacts" data-toggle="tab">Contacts</li> 
            <?php
            }
            ?>
            <span class="scrollTab" style="position: absolute; right: 0; font-size: 22px; line-height: 40px; z-index: 999"><i class="fa fa-caret-right"  onclick="tabScroll('right')"></i></span>
            </ul>
            <div class="tab-content clearfix">
                <div class="tab-pane active" id="basic">
                  <?php 
                  echo tab_content(1,$this->session->companey_id,$enquiry_id);

                   ?>
               </div>
            <script type="text/javascript">

            manageScroll();
               function manageScroll()
               {

               if($(".nav-tabs")[0].scrollWidth > $(".nav-tabs")[0].clientWidth)
                           {
               $(".scrollTab").show();
               }
               else
               {
                  $(".scrollTab").hide();
               }
                  }

               $(window).resize(function(){
               manageScroll();
               });

               function tabScroll(side)
               {
                  if(side=='left')
                  {
                     var leftPos = $('.nav-tabs').scrollLeft();
                  
                     $(".nav-tabs").animate({
                           scrollLeft: leftPos - 200
                     }, 100);
                  }
                  else if (side=='right')
                  {   
                     var leftPos = $('.nav-tabs').scrollLeft();
                     
                     $(".nav-tabs").animate({
                           scrollLeft: leftPos + 200
                     }, 100);
                  }
               }
               </script>

               
               <div class="tab-pane" id="order">
                  <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width:20px;"><?php echo display('serial') ?></th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Quantity</th> 
                            <th>Price</th>
                            <th>Date</th>                       
                        </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                   </table>
               </div>

 

    <!-- comission  -->

     <div class="tab-pane" id="amount">
                  <hr>

                  <div class="card card-body" style="overflow-y: scroll;">
                        <table class="table table-bordered">                          
                          <thead>
                            <tr>
                              <th>
                                Amount Disburssed
                              </th>
                              <th>
                                Commission
                              </th>
                              <th>
                              Date of payment
                              </th>
                              <th>
                                TDS
                              </th>
                              <th>
                                Amount Paid
                              </th>
                              <th>
                                Payout Percentage
                              </th>
                              <th>
                                Month
                              </th>
                              <th>
                                Actions
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (!empty($comission_data)) {
                              foreach ($comission_data as $key => $value) { ?>
                                <tr>
                                    <td><?=$value['amt_disb']?></td>                                    
                                    <td><?=$value['comission']?></td>
                                    <td><?=$value['date_of_payment']?></td>
                                    <td><?=$value['tds']?></td>
                                    <td><?=$value['amt_paid']?></td>
                                    <td><?=$value['payout_per']?></td>
                                    <td><?= ucwords($value['month'])?></td>
                                    <td>
                                     <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="delete_comission(<?=$value['id']?>)"><i class="fa fa-trash" ></i></a>
                                      <a href="javascript:void(0)" class="btn btn-primary btn-sm " onclick="open_comission_modal(<?=$value['id']?>)"><i class="fa fa-pencil" ></i></a>
                                    </td>
                                </tr>                                
                              <?php
                              }
                            }else{ ?>
                              <tr style="text-align: center;">
                                <td colspan=16>No record found</td>
                              </tr>
                              <?php
                            }
                            ?>
                          </tbody>
                            
                        </table>
                     </div>


                  <div class="card card-body">
                    <?php echo form_open_multipart('enquiry/add_enquery_comission/'.base64_encode($details->Enquery_id),array('class'=>"form-inner",'id'=>'add_comission_form')) ?>                      
                      <div class="row">                                          
                       <div class="form-group col-sm-4"> 
                          <label>Amount Disburssed</label>
                          <input type="text" class="form-control" name='amtdisb' required>
                                          
                       </div>
                       <div class="form-group col-sm-4"> 
                          <label>Commission </label>
                          <input class="form-control" name="comission" type="text" placeholder="" required>  
                       </div>

                       <div class="form-group col-sm-4"> 
                          <label>Date of payment</label>
                          <input class="form-control form-date" name="dateofpay"   required>  
                       </div>
                      </div>
                      <div class="row">                                          
                       <div class="form-group col-sm-4"> 
                          <label>TDS</label>
                          <input class="form-control " name="tds" type="text" placeholder="TDS" required>  
                       </div>
                                                               
                       <div class="form-group col-sm-4"> 
                          <label>Amount Paid</label>
                          <input class="form-control" name="amtpaid" type="text" placeholder="AMount Paid" required>  
                       </div>
                        <div class="form-group col-sm-4"> 
                        
                          <label>Payout Percentage</label>
                          <input class="form-control" name="payoutper" type="text" placeholder="Payout percentage" required>                 
                       </div>

                     
                     </div>
                     <div class="row">
                       

                       <div class="form-group col-sm-4"> 
                          <label>Month</label>
                          <Select class="form-control" name="month" required>
                            <option value="">Select</option>
                            <option value="january">January</option>
                            <option value="february">February</option>
                            <option value="march">March</option>
                            <option value="april">April</option>
                            <option value="may">May</option>
                            <option value="june">June</option>
                            <option value="july">July</option>
                            <option value="august">August</option>
                            <option value="september">September</option>
                            <option value="october">October</option>
                            <option value="novmber">Novmber</option>
                            <option value="december">December</option>
                          </Select>  
                       </div>
                                                              
                     </div>                                     
                       <br>
                           <div class=""  id="save_button">                                                
                              <div class="col-md-12">                                                
                                    <button class="btn btn-primary" type="submit" >Save</button>            
                              </div>
                            </div>
                       </form>
                    </div>                                                
               </div>
               <!-- END -->                              
               <?php if(user_access('1000'))//
               { ?>
              <div class="tab-pane" id="COMMERCIAL_INFORMATION" >
              <p align="right">
                <a target="_blank" href="<?=base_url('client/commercial_info/'.$details->enquiry_id.'/'.base64_encode($data_type))?>"><button class="btn btn-danger">Add Deal</button></a>
              </p>
                  
                  <table id="deals_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
                     <thead class="thead-light">
                       <tr>                              
                          <th>S.N.</th>
						  <th id="th-24">Quatation number</th>
						  <th id="th-25">Quatation Amt (In Lakhs)</th>
                          <th id="th-21">Company group name</th>
                          <th id="th-22">Client Name</th>
                          <th id="th-3">Business Type</th>
                          <th id="th-4">Booking Type</th>
        <!--                   <th id="th-5">Booking Branch</th>
                          <th id="th-6">Delivery Branch</th>
                          <th id="th-7">Rate</th>
                          <th id="th-8">Discount</th>
                          <th id="th-9">Insurance</th>
                          <th id="th-10">Paymode</th>
                          <th id="th-11">Potential Tonnage</th>
                          <th id="th-12">Potential Amount</th>
                          <th id="th-13">Expected  Tonnage</th>
                          <th id="th-14">Expected  Amount</th>
                          <th id="th-15">Vehicle Type</th>
                          <th id="th-16">Vehicle Carrying Capacity</th>
                          <th id="th-17">Invoice Value</th> -->
						  <th id="th-27">Region Name</th>
						  <th id="th-26">Created By</th>
                          <th id="th-18">Create Date</th>
                          <th id="th-23">No Action</th>
                          <th id="th-19">Status</th>
                          <th id="th-20">Action</th>
                       </tr>
                    </thead>
                      <tbody>
                     </tbody>
                  </table>
             </div>
 <script>             
function update_info_status(id,status)
{
     $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>enquiry/update_info_status?>',
            data: {id:id,status:status},
            success:function(data){
                Swal.fire({
                  title:'Saved!',
                  type:'success',
                  icon:'success',
                });
                location.reload();
            }
        });
}

</script>

<div id="downloadQuatation" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Download</h4>
         </div>
         <form action="<?= base_url('dashboard/pdf_gen/') ?>" method="POST">

         <div class="modal-body">
            <!-- <input name="idType" hidden class="idType" id="idType"> -->
            <input name="enquiry_id" hidden value="<?= $details->enquiry_id ?>">
             <div id="data_value" class="data_value" style="padding:10px;"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input id="download-q" type="submit" name="submit" class="btn btn-primary" value="Download">
            <input id="send-q" type="submit" name="submit" value="Email" class="btn btn-primary">
         </div>
         </form> 

      </div>
   </div>
</div>
<script>

function quotation_pdf(info_id) {
   window.open('<?=base_url();?>dashboard/quotation_preview/'+info_id,'Quotation','fullscreen=yes');
}

var c = getCookie('deals_allowcols');
specific_list ='';

var TempData = {};
$(".d_filter").on('change',function(){

  $('#deals_table').DataTable().ajax.reload();
 
});
$(document).ready(function(){

  $('#deals_table').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'enquiry/deals_load_data'?>",
              "type": "POST",
              "data":function(d){
                  d.enq_for = "<?=$details->enquiry_id?>";
                  d.curr_stg = "<?=$data_type?>";
                  d.specific_list = specific_list;
                  TempData = d;                     
                  return d;
              }
          },
		  "drawCallback":function(settings ){
			refresh_table_exs();
          },
          columnDefs: [
                       { orderable: false, targets: -1 }
                    ],
  });

});

</script>
<style>.tr_hover {
background-color: #ffb099; 
}</style>
<script>
function refresh_table_exs(){
      // alert(exstatus);
	  $(".deal_download").show();
      var tr_list = $("#deals_table tbody").find('tr');
      $(tr_list).each(function(k,v){
          var sfa = $(v).find('td > a.sfa > label.label').text();
		  var lfa = $(v).find('td > label.lfa').text();
		  //alert(lfa);
           var string = "Send For Approval";
		   var stringlbl = "Waiting for approval";
          if(sfa === string)
          {
            $(v).addClass('tr_hover');
			$(v).find('td > div.btn-group > a.deal_download').hide();
          }
		  if(lfa === stringlbl)
          {
			$(v).find('td > div.btn-group > a.deal_download').hide();
          }
         
      });
}
</script>
<?php
}
if(user_access('1020'))
{
?>
<div class="tab-pane" id="vtran_visit">
   <div class="row">
   <div class="col-md-12">
<?php if(user_access('1024'))  {  ?>

<a class="btn btn-primary" data-toggle="modal"  data-target="#approve_expense" style="color:white;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff; float:right;" onclick="">Approve</a>                        
<?php } ?>
<?php
          if(user_access('1020'))
          {
          ?>
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Visit" title="Add Visit"></a> 
          <?php
          }
          ?>
   </div>
   </div>


<hr>
 <div id="approve_expense" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title"> Expense Approval</h4>
         </div>
         <div class="modal-body">
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
              <label>Status</label>
             <select id="approve_status" name="approve_status" class="form-control">
               <option value="2">Approve</option>
               <option value="1">Reject</option>
             </select>
            </div>
            </div>
            <input id="visit_id" class="form-control visit_id" name="visit_id"  value="0" hidden type="hidden">

            <div class="col-md-12">
            <div class="form-group">
            <label>Remarks</label>

            <textarea class="form-control" name="remarks" id="remarks" cols="4"></textarea>
            </div>
            </div>
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit" onclick="expense_status();">
              Update Expense</button>                    
               <br>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>
<div id="add_expense" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Add Expense</h4>
         </div>
         <div class="modal-body">
            <form  action="<?php echo base_url(); ?>client/add_expense" method="POST" enctype='multipart/form-data'>  
            <div class="row">
          <input id="visit_id_id" class="form-control visit_id_id" name="visit_id" type="hidden"  hidden >

            <table class="table table-responsive">
                  <thead>
                  <th>Title</th>
                  <th>Amount</th>
                  <th>File(if any)</th>
                  <th><input type="button" value="+ " id="add" class="btn btn-primary"></th>
                  </thead>
                  <tbody class="detail">
                  <tr>
                  <td width="30%">
           <select name="expense[]" class="form-control">
           <?php 
           $expenselist=$this->db->where(array('comp_id'=>$this->session->companey_id,'status'=>1))->get('tbl_expenseMaster')->result();
           foreach ($expenselist as $key => $value) { ?>
            <option value="<?= $value->id ?>"> <?= $value->title ?></option>
          <?php } ?>
           </select> 
          </td>
                  <td width="30%">
                  <input name="amount[]" class="form-control amount" onkeyup="total()" id="amount" value="0"  >
                  </td>
                  <td width="30%">
                  <input name="imagefile[]"  class="form-control" onchange="Filevalidation(this)"  type="file"  >
                  </td>
                  <td width="10%"><a href="javascript:void(0)" class="remove btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
                  </tr>
                  </tbody>
            <tfoot>
            <tr>
            
            <th style="text-align:right">Total: </th>
            <th id="total" class="total"></th><th></th>
            <th></th></tr></tfoot>
            </table>
          
            </div>
               <br>
               <button class="btn btn-sm btn-success" type="submit">
              Add Expense</button>                    
               <br>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>

<div id="Save_Visit" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Visits</h4>
         </div>
         <div class="modal-body">
            <div class="row" >

<form  id="visit_create_form" action="<?=base_url('enquiry/add_visit')?>" class="form-inner" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
          <!--<div class="row">
                        <div class="form-group col-md-12">
                        <label>Select Visit Type</label>
                        <div class="form-check">
                              <label class="radio-inline">
                              <input name="type"  name="type" value="1" type="radio" checked onchange="handleClick_ch(this);">Current Visit</label>
                              <label class="radio-inline">
                              <input type="radio" name="type" value="2" onchange="handleClick_ch(this);">Future Visit</label>
                          </div>
                        </div>
                        </div>-->
    <select style="display: none;" name="enq_id">
      <option value="<?=$details->Enquery_id?>" selected></option>
    </select>
    <input type="hidden" name="enq_code" value="<?=$details->Enquery_id?>">
                        

                <div class="form-group col-md-6 visit-time col-md-6">     
                    <label>Purpose of meeting</label>
                    <input type="text" name="m_purpose" id="m_purpose" class="form-control" required>
                </div>
                <div class="form-group col-md-6 visit-time col-md-6">     
                    <label>Contact</label>
                    <select class="form-control" name="contact_id">
                      <?php
                      if(!empty($all_contact))
                      {
                        foreach ($all_contact as $key => $value) {
                          echo'<option value="'.$value->cc_id.'">'.$value->c_name.'</option>';
                        }
                        
                      }
                      ?>
                    </select>
                </div>
        <div class="form-group col-md-6 col-md-6">     
          <label>Start Location</label>
          <input type="text" name="start_loc" id="sloc" class="form-control">
        </div>
		<div class="form-group col-md-6 col-md-6">     
          <label>End Location</label>
          <input type="text" name="end_loc" id="eloc" class="form-control">
        </div>
		<div class="form-group col-md-6 col-md-6">     
          <label>Mannual KM</label>
          <input type="number" name="mannual_km" id="mkm" class="form-control">
        </div>
        <div class="form-group col-md-6">               
          <label>Back Date  <input value='1' type="checkbox" name='allowbackdate' class="form-control" /> </label>
        </div>
		<div class="form-group col-md-6 visit-date col-md-6">     
          <label>Visit Date</label>
          <input type="date" name="visit_date" id="vdate" readonly class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-6 visit-time col-md-6">     
         <label>Visit Time</label>
          <input type="time" name="visit_time" id="vtime" readonly class="form-control" value="<?= date('H:i') ?>">
        </div>
        <input type="hidden" name="visit_notification_id" value="">
         <div class="row" id="save_button">
            <div class="col-md-12 text-center">
               <input id="visit_create_btn" type="submit" name="submit_only" class="btn btn-primary" value="Save" >
            </div>
         </div>
      </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
    <div class="col-md-12"  style="margin-bottom: 25px; padding: 0px">       
    <table id="visit_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
      <thead>
      <tr>
          <th width="7%"><INPUT type="checkbox" onchange="checkAll(this)" name="chk[]" />Sr. No.</th>
				  <th id="th-1" width="15%">Visit Date</th>
				  <th id="th-2" width="15%">Visit Time</th>
				  <th id="th-13" width="15%">Purpose of meeting</th>
				  <th id="th-31" width="15%">Mobile</th>
				  <th id="th-10">Company group name</th>
          <th id="th-14">Client Name</th>
          <th id="th-15">Contact Person</th>
          <th id="th-16">Start Location</th>
          <th id="th-17">End Location</th>
				  <th id="th-4">Shortest Distance</th>
				  <th id="th-5">Actual Distance</th>
				  <th id="th-30">Manual Distance</th>
				  <th id="th-6">Rating</th>
				  <th id="th-28">Remark</th>
				  <th id="th-29">Employee Region</th>
				  <th id="th-7">Employee</th>
				  <th id="th-11" >Difference (%)</th>
				  <th id="th-8">Travel Expense</th>
				  <th id="th-18">Other Expense</th>
				  <th id="th-19">Total Expense</th>
				  <th id="th-20">Expense Staus</th>
				  <th id="th-21">Region</th>
				  <th id="th-22">Branch</th>
				  <th id="th-23">Area</th>
				  <th id="th-24">Stage Of Call</th>
				  <th id="th-25">Time Spend</th>
				  <th id="th-26">City</th>
				  <th id="th-27">Rate</th>
				  <th id="th-32">Created At</th>
          <th id="th-9">Action</th>
        </tr>
      </thead>
      <thead>
      </thead>
    </table>
    <div class="col-md-12">
            <div class="col-md-6" ></div>
            <div class="col-md-6" >
            
            <table class="table table-responsive table-bordered" >
            <tbody>
            <tr>
            <td width="50%"><b>Total Travel Expense:</b></td><td><span id="totaltravelExp"></span> ???</td>
            </tr>
            <tr><td width="50%"><b>Total Other Expense:</b> </td><td><span id="totalotherExpense"></span> ???</td>
            </tr>
            <tr><td width="50%"><b>Total Expense:</b></td><td><span id="totalExpense"></span> ???</td>
            </tr></tbody>
            </table>
            </div>
            </div>
    </div>
<script type="text/javascript">
function handleClick_ch(myRadio) {
  var valuer= myRadio.value;
//   alert('test');
  if(valuer==1){
  document.getElementById("vdate").disabled = true;  
  document.getElementById("vtime").disabled = true;  
  }else{
    document.getElementById("vdate").disabled = false;  
  document.getElementById("vtime").disabled = false;  
  } 
}

function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }

function expense_status(){
      var x = new Array(); 
      
      $($(".checkbox1:checked")).each(function(k,v){
        x.push($(v).val());
      });
       approve_status = document.getElementById("approve_status").value;
       remarks = document.getElementById("remarks").value;
      $.ajax({
              type: 'POST',
              url: '<?= base_url('client/visit_expense_status') ?>',
              data: {exp_ids:x,status:approve_status,remarks:remarks},
              success:function(data){
              //  alert(data);
               location.reload();
              } 
              });
}

$(document).ready(function(){
  $('#visit_table').DataTable({
          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'enquiry/visit_load_data'?>",
              "type": "POST",
              "data":function(d){
                  d.enquiry_id ="<?=$details->enquiry_id?>";
                  d.allow_cols = '';
                  return d;
              }
          },
          "drawCallback": function(settings) {
        $("#totaltravelExp").html(settings.json.totaltravelExp);
          $("#totalotherExpense").html(settings.json.totalotherExpense);
          $("#totalExpense").html(settings.json.totalExpense);
},
          
          "columnDefs": [{ "orderable": false, "targets":0 }],
           "order": [[ 1, "desc" ]],
  });
});
function checkvisit(visitid){
   $(".visit_id_id").val(visitid);                
}


$(function() {
    $('#add').click(function() {
      addnewrow();
    });
    $('body').delegate('.remove', 'click', function() {
      $(this).parent().parent().remove();
    });
    $('body').delegate('.qtys,.price', 'keyup', function() {
      var tr = $(this).parent().parent();
    });
  });

  function total() {
    var t = 0;
    $('.amount').each(function(i, e) {
      var amount = $(this).val() - 0;
      t += amount;
    });
    $('.total').html(t);
  }
  function Filevalidation(t)  {
    var filesize =t.files[0].size;
    filesize=filesize/1024;
   var filesizeinkb= filesize.toFixed(0);
    // alert(filesizeinkb);

    if(filesizeinkb > 1024){
   alert('File Size not exceed ');
    }
  }
  function addnewrow() {
    var n = ($('.detail tr').length - 0) + 1;
    var s = n + 3
    var r = n + 1
    var tr = '<tr>' + '<td width="30%"><select name="expense[]" class="form-control"><?php foreach ($expenselist as $key => $value) { ?><option value="<?= $value->id ?>"><?= $value->title ?></option><?php } ?></select></td>'+'<td width="30%"><input id="amount'+n+'" class="form-control amount" name="amount[]"  onkeyup="total()"></td>'+'<td width="30%"><input name="imagefile[]" class="form-control " onchange="Filevalidation(this)"  id="file'+n+'" type="file"  ></td>'+'<td width="10%"><a href="javascript:void(0)" class="remove btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>' + '</tr>';
    $('.detail').append(tr);    
    document.getElementById('amount' + n).addEventListener('keydown', function(e) {
      var key = e.keyCode ? e.keyCode : e.which;
      if (!([8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
          (key == 65 && (e.ctrlKey || e.metaKey)) ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
          (key >= 96 && key <= 105)
        )) e.preventDefault();
    });
     

  }
  document.getElementById('amount').addEventListener('keydown', function(e) {
      var key = e.keyCode ? e.keyCode : e.which;
      if (!([8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
          (key == 65 && (e.ctrlKey || e.metaKey)) ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
          (key >= 96 && key <= 105)
        )) e.preventDefault();
    });

</script>  
</div>
<?php
}
if(user_access('1004'))
{
?>

<div class="tab-pane" id="vtransaggrement">
 <hr>
<div style="max-width: 100%;">
<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">S.No</th>
	  <th class="th-sm">Quatation.No</th>
      <th class="th-sm">Name</th>
      <th class="th-sm">Mobile</th>
      <th class="th-sm">Email</th>
      <th class="th-sm">Address</th>
      <th class="th-sm">Agreement Date</th>
      <th class="th-sm">Sync With ShipX</th>
    <th class="th-sm">Agreement</th>
<?php if($details->status=='5'){ ?>
    <th class="th-sm">PO File</th>
    <th class="th-sm">Signed Agreement</th>
  <th class="th-sm">Attach PO</th>
  <th class="th-sm">Agreement Uploaded Docs</th>
<?php } ?>
    </tr>
  </thead>
  <tbody>
      <?php $i=1; foreach($aggrement_list as $val){ ?>
    <tr>
      <td><?php echo $i; ?></td>
	  <td><?php echo $val->quatation_number;  ?></td>
      <td><?php echo $val->agg_name;  ?></td>
      <td><?php echo $val->agg_phone;  ?></td>
      <td><?php echo $val->agg_email;  ?></td>
      <td><?php echo $val->agg_adrs; ?></td>
    <td><?php echo $val->agg_date; ?></td>
    <td>
    <?php
    if($val->shipx_push_status == 200){
      ?>
      <a href="javascript:void(0)" class="btn btn-xs btn-primary" >Already Synced</a>
      <?php      
    }else{
      ?>
      <a href="javascript:void(0)" class="btn btn-xs btn-primary" onclick="sync_with_shipx('<?=$val->id?>')">Sync</a>
      <?php
    }
    ?>  
    </td>      
      <td>
<?php if(!empty($val->file)){ 
  $fname= explode('/', $val->file);
  $fname = end($fname);
  ?>
<a href="<?php   echo base_url($val->file); ?>"  target="_blank"><?=$fname?></a>
<?php }else{
  ?>
   <div class="form-group col-sm-2"><a href="#modalagg" data-toggle="modal" class="btn" data-animation="effect-scale"  onclick="set_agreement_id(<?=$val->id?>)"><i class="fa fa-upload" aria-hidden="true"></i></a></div>
<?php } ?>
      </td>
      
<?php if($details->status=='5'){ ?>
<?php if(!empty($val->po_file)){
$poname= explode('/', $val->po_file);
$poname = end($poname);
  ?>
<td><a href="<?php   echo base_url($val->po_file); ?>"  target="_blank"><?=$poname?></a></td>
<?php }else{
   ?>
   <td></td>
   <?php
} ?>
<td>
<?php
if($val->signed_agreement){ ?>
   <a href="<?php echo base_url().$val->signed_agreement; ?>"  target="_blank">Signed Doc</a>
<?php
}else{
   ?>
<div class="form-group col-sm-2"><a href="#modalagg" data-toggle="modal" class="btn" data-animation="effect-scale"  onclick="set_agreement_id(<?=$val->id?>,2)"><i class="fa fa-upload" aria-hidden="true"></i></a></div>
   <?php
}
?>
  </td>
  <td>
  <div class="form-group col-sm-2"><a href="#modalagg" data-toggle="modal" class="btn" data-animation="effect-scale"  onclick="set_agreement_id(<?=$val->id?>)"><i class="fa fa-upload" aria-hidden="true"></i></a></div>
  </td>
<?php } ?>
<td>
  <div class="form-group col-sm-2"><a href="#modalaggdoc" data-toggle="modal" class="btn" data-animation="effect-scale"  onclick="find_agreement_doc(<?=$val->deal_id?>)"><i class="fa fa-file" aria-hidden="true"></i></a></div>
</td>
    </tr>

    <?php $i++;} ?>
  </tbody>
</table> 
</div>
  <script>
     $(document).ready(function () {
  $('#dtBasicExample').DataTable({"scrollX": true});
  $('.dataTables_length').addClass('bs-select');
});

function set_agreement_id(ag_id,type=1)
{
  $("input[name=ide]").val(ag_id);
  if(type==2){
   $("input[name='agreement_attachment']").val(2);
   $("#exampleModalLabel6").html('Upload Signed Agreement');
  }else{
   $("input[name='agreement_attachment']").val(1);
   $("#exampleModalLabel6").html('Upload PO Here');
  }
}

function find_agreement_doc(doc) { 
  $.ajax({
  type: 'POST',
  url: '<?php echo base_url();?>client/get_aggdoc_list',
  data: {doc_id:doc},  
  success:function(data){
      $("#all_doc_table").html(data);
  }
  });
}
</script> 
<hr>
<form target="_blank" action="<?=base_url('client/prepare_vtrans/'.$details->Enquery_id)?>" method="post">
    <div class="row">
        <div class="form-group col-sm-10">
            <label>
              <input type="checkbox" name="agg_same" id="agg_same" value="<?php echo $this->uri->segment(3); ?>" onclick="myaggrement()"> If Details Are Same As Previous Data.
            </label>
            
        </div>  
        <div class="form-group col-sm-6">
              <label>Name <i class="text-danger">*</i></label>
            <input type="text" id="agg_user" name="agg_user" value="" class="form-control" required>
        </div>
         
        <div class="form-group col-sm-6">
            <label>Mobile <i class="text-danger">*</i></label>
            <input type="text" id="agg_mobile" name="agg_mobile" value="" class="form-control" required> 
        </div>
                                                  
        <div class="form-group col-sm-6">
            <label>Email <i class="text-danger">*</i></label>
            <input type="text" id="agg_email" name="agg_email" value="" class="form-control" required> 
        </div>
         
        <div class="form-group col-sm-6">
            <label>Address <i class="text-danger">*</i></label>
            <input type="text" id="agg_adrs" name="agg_adrs" value="" class="form-control" required> 
        </div>
              
        <div class="form-group col-sm-6">
            <label>Agreement Date <i class="text-danger">*</i></label>
            <input  id="agg_date" name="agg_date" value="<?=date('Y-m-d')?>" class="form-control form-date" required> 
        </div>
<!--     </div>
    <div class="row" style="padding: 16px 0px;"> -->
      <div class="col-md-6">
          <div class="form-group">
              <label>Deal <i class="text-danger">*</i></label>
              <select class="form-control"  name="deal_id" required> 
                <option value="">Select Deal</option>
               <?php
               $ci = &get_instance();
               $ci->load->model('Branch_model');
               $deal_list = $ci->Branch_model->deal_list(array('status'=>1,'enquiry_id'=>$details->enquiry_id,'stage_id'=>$data_type));
                if(!empty($deal_list))
                {
                  foreach ($deal_list as $key => $drow) {
                    echo'<option value="'.$drow->id.'">'.$drow->quatation_number.'</option>';
                  }
                 
                }
               ?>
              </select>           
          </div>
      </div>
      
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="form-group">                              
            <button class="btn btn-primary" type="submit">Genereate Agreement</button>    
          </div>       
        </div>
    </div>
</form>
<!--------------------------------Modal Popup for Aggrement----------------------------------------------------------------------------->
                      
<div class="modal fade" id="modalagg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
     
<form method="post" action="<?php echo base_url(); ?>client/upload_aggrement_team" enctype="multipart/form-data">
         <input name='agreement_attachment' value='1' hidden/>
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel6">Upload PO Here</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body col-sm-12">
              <input type="hidden" name="ide" class="form-control" placeholder="" required>
            <div class="form-group col-sm-6">
                <input type="file" name="file" class="form-control" placeholder="" required>
            </div>
            <div class="form-group col-sm-6">
            <div class="sgnbtn">
                <input id="signupbtn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
            </div>   
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary tx-13" data-dismiss="modal">Close</button>
          </div>
        </div>
       </form>
      </div>
    </div>
	
<div class="modal fade" id="modalaggdoc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content tx-14">
          <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel6">Aggrement Documents</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body col-sm-12" id="all_doc_table">
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary tx-13" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

<!-----------------------------------------------END------------------------------------>

<script>
function myaggrement() {
if (document.getElementById('agg_same').checked) 
  {
    var cdata=$("#agg_same").val();
     $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>client/find_same',
            data: {cdata:cdata},
         success:function(data){
            res = JSON.parse(data);
              if(res){              
                $("input[name='agg_user']").val(res.name_prefix + res.name  +' '+ res.lastname);
                $("input[name='agg_mobile']").val(res.phone);                
                $("input[name='agg_email']").val(res.email);
                $("input[name='agg_adrs']").val(res.address);              
              }
         }               
     });        
    }else{
                $("input[name='agg_user']").val('');
                $("input[name='agg_mobile']").val('');                
                $("input[name='agg_email']").val('');
                $("input[name='agg_adrs']").val('');  
    }      
}
</script>



</div>
     <?php } ?>
     

               
               <div class="tab-pane" id="task">
                   <hr>
                <div   style="overflow-x: hidden;overflow-y: auto;" onscroll="scrolled(this)">
                  <link href="<?php echo base_url();?>assets/css/fullcalendar.min.css" rel="stylesheet">
                  <link href="<?php echo base_url();?>assets/css/fullcalendar.print.min.css" media="print">
                  <div id="calendar" style="margin-left:20px;"></div>
               </div>
                  <hr>
                  <div class="col-md-12" style="border:none!important;border-radius:0px!important;">
                     <div  style="overflow-x: hidden;overflow-y: auto;" onscroll="scrolled(this)">
                        <!----------------- Calender View ------------>
                        <link href="<?php echo base_url();?>assets/css/fullcalendar.min.css" rel="stylesheet">
                        <link href="<?php echo base_url();?>assets/css/fullcalendar.print.min.css" media="print">
                        <div id="calendar4"></div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="col-md-6" style="display: none;">
                           <div class="card"   style="margin-top:10px;">
                              <div class="card-body">   
                                 <span style="font-size: 14px;font-weight: bold;">
                                 <?=display('enquiry')?> Details: 
                                 </span>
                                 <button class="btn btn-sm btn-primary" style="float: right"  type="button" data-toggle="modal" data-target="#Coment">
                                 <i class="fa fa-dot-circle-o"></i> Add Comment</button>                    
                              </div>
                           </div>
                           <div id="comment_div" style="max-height:300px; overflow-y:scroll;">
                              <?php 
                                 if(!empty($comment_details)){               
                                     foreach ($comment_details as $ld_res) 
                                 {?>
                              <div class="list-group"  style="margin-top:10px;">
                                 <a class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                       <p class="mb-1"><?= $ld_res->comment_msg?></p>
                                       <small><b><?php echo date("j-M-Y h:i:s a",strtotime($ld_res->created_date)); ?></b></small>
                                    </div>
                                 </a>
                              </div>
                              <?php } }?>
                           </div>
                           <div  id="comment_div1" style="display:none;">
                           </div>
                        </div>
                        <div class="col-md-12" >
                           <div class=" card"  style="margin-top:10px;">
                              <div class="card-body">   
                                 <span style="font-size: 14px;font-weight: bold;">
                                 Task Details:
                                 </span>
                                 <button class="btn btn-sm btn-primary" style="float: right"  type="button" data-toggle="modal" data-target="#createTask">
                                 <i class="fa fa-dot-circle-o"></i> Create Task</button> 
                              </div>
                           </div>
                           <div  id="task_div" class='card' style="max-height:300px; overflow-y:scroll;margin-top:10px;">
                              <?php 
                                 foreach ($recent_tasks as $task)
                                 {?>
                              <div class="list-group">
                                 <div class="col-md-12 list-group-item list-group-item-action flex-column align-items-start" style="margin-top:10px;">
                                    <div class="d-flex w-100 justify-content-between">
                                       
                                       <div class="col-md-12">
                                          <b>Subject :</b>
                                          <?=$task->subject?>
                                        </div>                                      
                                     
                                       
                                       <div class="col-md-12">
                                            <b>Remark  : </b>
                                            <?= $task->task_remark?>
                                        </div>
                                       
                                       <div class="col-md-12">
                                          <b>Task Date  : </b>
                                          <?php echo date("d-m-Y",strtotime($task->task_date)); ?>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <b>Task Time  : </b>
                                            <?php echo $task->task_time; ?>
                                        </div>

                                        <div class="col-md-12">
                                          <a href='' class="fa fa-pencil btn btn-primary btn-sm" style="float:right;" data-toggle="modal" data-target="#task_edit" title='Edit Task' onclick="get_modal_content(<?=$task->resp_id?>)"></a>
                                          <?php
                                          if(user_access(92)){ ?>
                                            <i class="fa fa-trash btn btn-danger btn-sm" style="float:right;" onclick="delete_row(<?=$task->resp_id?>)"title='Delete Task'></i>&nbsp;&nbsp;&nbsp;
                                          <?php
                                          }
                                          ?>

                                       </div>
                                    </div>
                                 </div> 
                                 </div>                                
                              <?php } ?>
                           </div>
                           <div class="list-group" id="task_div1" style="display:none;">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>     

               <?php
               if(user_access('1010'))
               {?>          
               <div class="tab-pane" id="company_contacts">
                  <hr>
                  <div class="row" style="overflow-x: hidden;">
                     <table id="dataTableContact" class="table table-bordered table-striped table-responsive-sm" style="background: #fff;">
                        <thead>               
                         <tr>
                            <th>&nbsp; # &nbsp;</th>
                            <th id="th-2" style="width: 20%;">Company group name</th>
                            <th id="th-3" style="width: 20%;">Designation</th>
                            <th id="th-4" style="width: 20%;">Contact Name</th>
                            <th id="th-5" style="width: 20%;">Contact Number</th>
                            <th id="th-6" style="width: 20%;">Email ID</th>
                            <th id="th-7" style="width: 20%;">Decision Maker</th>
                            <th id="th-8" style="width: 20%;">Other Detail</th>
                            <th id="th-9" style="width: 20%;">Created At</th>
                            <th id="th-10" style="width: 50px;">Action</th>
                         </tr>
                      </thead>
                        <tbody>
                           
                        </tbody>
                     </table>
                     <br>
                     <script type="text/javascript">
                       
                       $(document).ready(function(){
                        //var c =   ('contact_allowcols');
                        $('#dataTableContact').DataTable({ 

                                "processing": true,
                                "scrollX": true,
                                "serverSide": true,          
                                "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
                                "ajax": {
                                    "url": "<?=base_url().'client/contacts_load_data'?>",
                                    "type": "POST",
                                    "data":function(d){
                                         
                                           d.enquiry_id="<?=$details->enquiry_id?>";
                                           d.specific_list = '';
                                            
                                          d.allow_cols = '';

                                           console.log(JSON.stringify(d));
                                          return d;
                                    }
                                },
                                columnDefs: [
                                             { orderable: false, targets: -1 }
                                          ]
                        });

                      });

                     </script>
                     <center>
                      <?php
                      if(user_access('1010'))
                      {
                      ?>
                       <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#Save_Contact" class="btn btn-primary">Add Contact</a></h5>
                      <?php
                      }
                      ?>
                     </center>
                     <br>              
                  </div>
               </div>
               <?php
              } ?>
              <div class="tab-pane" id="related_enquiry">                  
              </div>
             <!--  <div class="tab-pane" id="company_contacts">  
                    <h2>Contacts</h2>
              </div> -->

               <?php
               if(!empty($tab_list)){
                foreach ($tab_list as $key => $value) { ?>
                  <div class="tab-pane" id="<?=str_replace(' ', '_', $value['title'])?>">
                  <?php
                  if ($value['id'] != 1) {
                    echo tab_content($value['id'],$this->session->companey_id,$enquiry_id,str_replace(' ', '_', $value['title'])); 
                  }
                  ?>
                  </div>
                  <?php
                }
              }
              ?>
            </div>
      </div>
      </div>
      <div>
         <span class="toogle-timeline badge badge-danger pull-right" data-vis="1"><i class="fa fa-caret-right"></i></span>
        <div class="col-md-3 col-xs-12 col-sm-12 col-height timeline"> 

           <h3 class="text-center">Activity Timeline
           </h3>
           <hr>
           <p  class="text-center" style="font-size: 10px;margin-top:-20px;"><?='Created '.get_time_ago(strtotime($details->enq_created_date))?></p>
           <div class="activitytimelinediv"></div>
            
        </div>
      </div> 
      <style>
         #exTab3 .nav-tabs > li > a {
         border-radius: 4px 4px 0 0 ;
         }
         #exTab3 .tab-content {
         /*color : white;*/
         background-color: #fff;
         padding : 5px 15px;
         }
         .card {
         position: relative;
         display: -ms-flexbox;
         display: flex;
         -ms-flex-direction: column;
         flex-direction: column;
         min-width: 0;
         word-wrap: break-word;
         /*background-color: #fff;*/
         background-clip: border-box;
         border: 1px solid #c8ced3;
         border-radius: 0.25rem;
         }
         .card-body {
         -ms-flex: 1 1 auto;
         flex: 1 1 auto;
         padding: 1.25rem;
         }
         .list-group {
         display: -ms-flexbox;
         display: flex;
         -ms-flex-direction: column;
         flex-direction: column;
         padding-left: 0;
         margin-bottom: 0;
         }
         .list-group-item {
         position: relative;
         display: block;
         padding: 0.75rem 1.25rem;
         margin-bottom: -1px;
         background-color: #fff;
         border: 1px solid rgba(0, 0, 0, 0.125);
         }
         .list-group-item-action {
         width: 100%;
         color: #5c6873;
         text-align: inherit;
         }
      </style>


<!-- comission -->
<button type="button" data-toggle="modal" data-target="#edit-comission-frm" id='comission_modal_btn' style="visibility: hidden;">  
</button>
<div id="edit-comission-frm" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update</h4>
         </div>
         <div class="modal-body" id="edit-comission-content">
            
         </div>
      </div>
   </div>


</div>
<!-- END -->





<div id="Save_Contact" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Contacts</h4>
         </div>
         <div class="modal-body">
              <div class="row">
                 <?php
                  if(!empty($create_contact_form))
                      echo $create_contact_form;
                  ?>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>     
      <!---- Create Task Start ---->
  <div id="createTask" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Create New Task</h4>
         </div>
         <div class="modal-body">
            <?php echo form_open_multipart('lead/enquiry_response_task',array('class'=>"form-inner",'id'=>'task_form')) ?>
            <div class="profile-edit">
               
                <div class="form-group col-sm-6" >
                  <label>Subject</label>
                  <input type="text" class="form-control" value="<?php if(!empty($details->subject)){echo $details->subject;} ?>" name="subject"  placeholder="Subject">
               </div>
               <div class="form-group col-sm-6" style="display:none;">
                  <label>Contact Person Name</label>
                  <input type="text" class="form-control" value="<?php if(!empty($details->name)){echo $details->name;} ?>" name="contact_person"  placeholder="Contact Person Name">
               </div>
               <div class="form-group col-sm-6" style="display:none;">
                  <label>Contact Person Designation</label>
                  <input type="text" class="form-control" name="designation" value="<?= isset($details->designation)?$details->designation:''?>" placeholder="Contact Person Designation">
               </div>
               <div class="form-group col-sm-6" style="display:none;">
                  <label>Contact Person Mobile No</label>
                  <input type="text" class="form-control" value="<?php if(!empty($details->phone)){echo $details->phone;} ?>" name="mobileno" placeholder="Mobile No">
               </div>
               <div class="form-group col-sm-6" style="display:none;">
                  <label>Contact Person Email</label>
                  <input type="text" class="form-control" value="<?php if(!empty($details->email)){echo $details->email;} ?>" name="email" placeholder="Email">
               </div>
               <div class="form-group col-sm-6" >
                  <label>Status</label>
                  <select class="form-control" name="task_status">
                     <?php foreach($taskstatus_list as $key=>$val){ ?>
                     <option value="<?php echo $val->taskstatus_id; ?>"><?php echo $val->taskstatus_name; ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group col-sm-6">
                  <label>
                     Task Date <!-----Demo Plan Date--->
                  </label>
                  <input class="form-control" name="task_date"  type="date" placeholder="Task date" id="enq_task_date">
               </div>
               <div class="form-group col-sm-6">
                  <label>
                     Task Time <!-----Demo Plan Date--->
                  </label>
                  <input class="form-control " name="task_time"  type="time" value="" >
               </div>


               <div class="form-group col-sm-12">
                  <label>Remark Details</label>
                  <textarea class="form-control"   name="task_remark" placeholder="Remark here..."></textarea>
               </div>               
               <div class="form-group text-center">
                  <input type="hidden" name="enq_code"  value="<?php echo  $details->Enquery_id; ?>" >
                  <input type="hidden" name="task_type" value="1">
                  <input type="hidden" name="notification_id">
                  <input type="submit" name="update" id='submit_task_btn' class="btn btn-primary"  value="<?php echo display('create_Task');?>" >
               </div>
            </div>
            </form> 
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
      <!---- Create Task End ---->
      <div id="genLead" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <?php echo form_open_multipart('enquiry/move_to_lead_details','class="form-inner"') ?>
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Enter info and Move to <?=display('lead')?></h4>
               </div>
               <div class="modal-body">
                  <!--<form method="post" action="">-->
                  <!--<?php //echo form_open_multipart('enquiry/move_to_lead','class="form-inner"') ?>   -->
                  <div class="row">
                     <div class="form-group col-sm-6">  
                        <label>Expected Closer Date <i class="text-danger">*</i></label>                  
                        <input class="form-control"  name="expected_date" type="date" placeholder="Expected Closer Date" required>                
                     </div>
                     <div class="form-group col-sm-6">
                        <label class="col-form-label">Conversion Probability</label>
                        <select class="form-control" id="LeadScore" name="lead_score">
                           <option></option>
                           <?php foreach ($lead_score as $score) {  ?>
                           <option value="<?= $score->sc_id?>"><?= $score->score_name?>&nbsp;<?= $score->probability?></option>
                           <?php } ?>
                        </select>
                     </div>

                     <div class="form-group col-md-6">
                        <label><?php echo display('lead_stage') ?> <i class="text-danger">*</i></label>                  
                        <select class="form-control" id="move_lead_stage_change" name="move_lead_stage" onchange="find_description1()" required>
                           <option value="">-- Select <?=display('lead')?> Stage --</option>
                             
                              <?php foreach ($all_estage_lists as $stage) {  ?>
                              <option value="<?= $stage->stg_id?>" <?php if ($stage->stg_id == $details->lead_stage) {echo 'selected';}?>><?php echo $stage->lead_stage_name; ?></option>
                              <?php } ?>                                        
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label><?php echo display('lead_description') ?></label>                  
                        <select class="form-control" name="lead_description" id="lead_description1">
                           <option value="">Select Description</option>
                                                                   
                        </select>
                     </div>
                     <div class="form-group col-md-6">  
            <label>Select Employee</label> 
            <div id="imgBack"></div>
            <select class="form-control"  name="assign_employee">   
               <option value="0">--Select--</option>                 
            <?php if(!empty($created_bylist)){foreach ($created_bylist as $user) { 
                            
                          if (!empty($user->user_permissions)) {
                            $module=explode(',',$user->user_permissions);
                          }                           
                            
                            ?>
                            <option value="<?php echo $user->pk_i_admin_id; ?>">
                              <?=$user->s_display_name ?>&nbsp;<?=$user->last_name.' - '.$user->s_user_email; ?>                                
                            </option>
                            <?php 
                          //}
                        } } ?>                                                      
            </select> 
            </div>
                     <div class="form-group col-sm-12">  
                        <label><?php echo display('comments') ?></label>                  
                        <textarea class="form-control" id="LastCommentGen" name="comment" type="text" placeholder="Enter comment"></textarea>               
                     </div>
                     <div class="col-md-6"  id="save_button">
                        <div class="row">
                           <div class="col-md-12">                                                
                              <button class="btn btn-primary" type="submit" >Save</button>            
                           </div>
                        </div>
                     </div>
                     <div class="form-group col-md-6" id="curcit_add" style="display:none;">
                        <div class="col-12" >
                           <div class="row">
                              <div class="col-md-6">                                                
                                 <button class="btn btn-primary" type="submit" >Create Circuit Sheet</button>            
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-group col-md-6" id="add_po" style="display:none;">
                        <div class="col-12" >
                           <div class="row">
                              <div class="col-md-12">                                                
                                 <button class="btn btn-primary" type="submit" >Attached Po</button>            
                              </div>
                           </div>
                        </div>
                     </div>
                     <input type="hidden" value="<?php echo $enquiry->enquiry_id;?>" name='enquiry_id'>
                  </div>
                  </form>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<style>
   .avatar {
   position: relative;
   display: inline-block;
   width: 36px;
   height: 36px;
   }
   [data-lettersbig]:before {
   content: attr(data-lettersbig);
   display: inline-block;
   font-size: 1.5em;
   width: 4em;
   height: 4em;
   line-height: 4em;
   text-align: center;
   border-radius: 50%;
   background: #37a000;
   vertical-align: middle;
   margin-right: 1em;
   color: white;
   }
   [data-letters]:before {
   content: attr(data-letters);
   display: inline-block;
   font-size: 1em;
   width: 2.5em;
   height: 2.5em;
   line-height: 2.5em;
   text-align: center;
   border-radius: 50%;
   background: #37a000;
   vertical-align: middle;
   margin-right: 1em;
   color: white;
   }
   .card-body {
   -ms-flex: 1 1 auto;
   flex: 1 1 auto;
   padding: 1.25rem;
   }
   .card {
   position: relative;
   display: -ms-flexbox;
   display: flex;
   -ms-flex-direction: column;
   flex-direction: column;
   min-width: 0;
   word-wrap: break-word;
   background-color: #fff;
   background-clip: border-box;
   border: 1px solid #c8ced3;
   border-radius: 0.25rem;
   }
   .card {
   margin-bottom: 1.5rem;
   }
</style>
<!-------------------------------------UPDATE DETAILS------------------------------------------------>

<div id="sendsms<?php echo $enquiry->enquiry_id ?>" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <?php echo form_open_multipart('message/send_sms','class="form-inner" id="whatsaap"') ?>
      <div class="modal-content card">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="modal-titlesms"></h4>
         </div>
         <div>
            <div class="form-group col-sm-12">
               <label>Template</label>
               <select class="form-control" name="templates" required id="templates"  onchange="getMessage(),this.form.reset();">
               </select>
            </div>
            <div class="form-group col-sm-12">
               <label><?php echo display('subject') ?></label>
               <input type="text" name="email_subject" class="form-control" id="email_subject">
               <label><?php echo display('message') ?></label>
               <textarea class="form-control" name="message_name"  rows="10" id="template_message"></textarea>  
            </div>
         </div>
         <div class="form-group col-sm-12">
            <div class="col-md-6">
            <label>Schedule</label>
<style>
.timepicker-picker {
  height: 240px; 
  margin: 0 80px; 
  display: inline-flex; 
  flex-direction: column;
  justify-content: center; 
}
.calendar {
  cursor: pointer;
}
.timepicker-picker table td {
    height: 24px;
    line-height: 24px;
    width: 24px;
}
.bootstrap-datetimepicker-widget table td.separator { 
  width: 1px;
  height: 1px;
  line-height: 1px;
} 
.bootstrap-datetimepicker-widget .timepicker-hour,
.bootstrap-datetimepicker-widget .timepicker-minute,
.bootstrap-datetimepicker-widget .glyphicon-chevron-up,
.bootstrap-datetimepicker-widget .glyphicon-chevron-down {
  width: 20px;
  height: 15px; 
  line-height: 1;
}
.bootstrap-datetimepicker-widget table td span:hover {
    background: none;
}
</style>
            <label class="radio-inline">
      <input value="1" style="margin-top:25px" class="form-control" type="radio" onclick="handleClick(this);" name="schedule" checked>Send Now
    </label>
    <label class="radio-inline">
      <input value="2" style="margin-top:25px" class="form-control" type="radio" onclick="handleClick(this);" name="schedule">Send Later
    </label>
         </div>
         <div class="col-md-6">
         <div id="schedule_time" style="display: none;">
       <div class="col-md-8">
       <label >Date & Time</label>
       <div class="input-group input-group-sm" id='datetimepicker'  >
            <input type="text" class="form-control" style="width: 100%;" name="schedule_time"/>   
            <span class="input-group-addon calendar">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>
         </div>
         </div>
         </div>
            </div>
            <div style="margin-top: 20px;" class="form-group col-md-12">
            <input type="hidden"  id="mesge_type" name="mesge_type">
            <input type="hidden"  id="message_from" name="message_from" value="1">
            <input  type="hidden" id="mobile" name="mobile" value="<?php echo $enquiry->phone ?>">
            <input type="hidden" id="mail" name="mail" value="<?php echo $enquiry->email ?>">
            <input type="hidden" id="" name="enquiry_id" value="<?php echo $enquiry->enquiry_id ?>">
            <input type="hidden" id="Enquiry_id" name="Enquiry_id" value="<?php echo $enquiry->Enquery_id ?>">
            <button class="btn btn-primary" onclick="send_sms()" type="button">Send</button>            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
      </form>
   </div>
</div>

<div id="timelineshow_d" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">
            <div class="modal-content card" id="timelinesdata">
            </div>
</div>
</div>
<script>
   var currentValue = 0;
function handleClick(myRadio) {
   if(myRadio.value==1){
      $('#schedule_time').hide();
   }else{
      $('#schedule_time').show();
   }
}
   function getTimelinestatus(timelineId){ 
      $.ajax({               
         url : '<?php echo base_url('enquiry/EnqtimelinePopup') ?>',
         type: 'POST',
         data: {timelineId:timelineId},
         success:function(content){
         //  var obj = JSON.parse(data);
            //  $("#timelinesdata").append(obj.msg);
            $("#timelinesdata").html(content);

         }               
     });        
    }
  // jquery
$(function () {
    $('#datetimepicker').datetimepicker({ 
        allowInputToggle: true,
        showClose: true, //close the picker
        format: 'YYYY-MM-DD HH:mm', //YYYY-MMM-DD LT
        calendarWeeks: true,
        inline: false,
        sideBySide: true
    });
    $('#datetimepicker-sidebyside').datetimepicker({
        showTodayButton: true,
        showClose: true, //close the picker
        showClear: true, //clear selection 
        format: 'YYYY-MM-DD HH:mm', //YYYY-MMM-DD LT
        calendarWeeks: true,
        inline: true,
        sideBySide: true
    });
    $('#datetimepicker-collapse').datetimepicker({
        showClose: true, //close the picker
        format: 'YYYY-MM-DD HH:mm', //YYYY-MMM-DD LT
        calendarWeeks: true,
        inline: true,
        collapse: true
    }); 
});

</script>

<!---------------------------- DROP Enquiry -------------------------------->
<div id="dropEnquiry" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <?php
            $drop_title = 'Drop';
            if ($enquiry->status == 1) {
              $drop_title = 'Drop '.display('enquiry');
            }else if ($enquiry->status == 2) {
              $drop_title = 'Drop '.display('lead');
            }else if ($enquiry->status == 3) {
              $drop_title = 'Drop '.display('client');
            }
            ?>
            <h4 class="modal-title"><?=$drop_title?> <?= ucfirst($enquiry->name); ?></h4>
         </div>
         <div class="modal-body">
            <!--<form>-->
            <?php echo form_open_multipart('enquiry/drop_enquiry/'.$enquiry->enquiry_id,'class="form-inner"') ?>                      
            <div class="row">
               <div class="form-group col-sm-12">
                  <label>Drop Reason</label>                  
                  <select class="form-control"  name="drop_status">
                     <option value="" style="display:none">---Select---</option>
                     <?php if(!empty($drops)){foreach ($drops as $drop) {   ?>
                     <option value="<?php echo $drop->d_id; ?>"><?php echo $drop->drop_reason; ?></option>
                     <?php }} ?>                                             
                  </select>
               </div>
               <div class="form-group col-sm-12"> 
                  <label>Drop Comment</label>
                  <input class="form-control" name="reason" type="text" required="">  
               </div>
            </div>
            <div class="col-12" style="padding: 0px;">
               <div class="row">
                  <div class="col-12" style="text-align:center;">                                                
                     <button class="btn btn-primary" type="submit">Save</button>            
                  </div>
               </div>
            </div>
            </form> 
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<div id="range" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Search</h4>
         </div>
         <div class="modal-body">
            <form  action="" method="POST" id="searc_task">  
               <input class="form-control" id="FormInfo" name="task_id" type="hidden" value="<?php echo $enquiry->Enquery_id ; ?>" >
               <label>Date From:</label>
               <input class="form-control" id="txtDate" name="task_start" type="text" placeholder="Start Task Date" readonly>                
               <br> 
               <label>Date To:</label>
               <input class="form-control" id="txtDate2" name="task_end" type="text" placeholder="End Task Date" readonly>
               <br>
               <button class="btn btn-sm btn-primary" style="float: right" type="button" onclick="search_comment_and_task()">
               <i class="fa fa-dot-circle-o"></i> <?php echo display('search'); ?></button>                    
               <br>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>
<div id="Coment" class="modal fade in" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="closedmodel()">&times;</button>
            <h4 class="modal-title">Add Comment</h4>
         </div>
         <div class="modal-body">
            <form  action="<?php echo base_url(); ?>lead/add_comment" method="POST">  
               <input class="form-control" id="FormInfo" name="lid" type="hidden" value="<?php echo $enquiry->Enquery_id ; ?>" >
               <input class="form-control" id="FormInfo" name="coment_type" type="hidden" value="1" >
               <textarea class="form-control" id="LastComment" name="conversation" type="text" placeholder="Add followup comment"></textarea>
               <br>
               <button class="btn btn-sm btn-primary" style="float: right">
               <i class="fa fa-dot-circle-o"></i> Add Comment</button>                    
               <br>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closedmodel()">Close</button>
         </div>
      </div>
   </div>
</div>





<div id="task_edit" class="modal fade in" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content " id="update-task-content">
      </div>
   </div>
</div>


<div id="dispo_modal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Disposition</h4>
         </div>
         <div class="modal-body">
            
         </div>
      </div>
   </div>
</div>

<div id="timeline_modal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Activity Timeline</h4>
         </div>
         <div class="modal-body">
            
         </div>
      </div>
   </div>
</div>
<a class="dropdown-toggle" data-toggle="modal" data-target="#updt_Contact" id="open" title="Add Contact" style="display:none;"></a> 
<div id="updt_Contact" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Contacts</h4>
        </div>
        <div class="modal-body">
          <div class="row" id="update_content">
            
          </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div> 
</div>
<style type="text/css">
  .toogle-timeline{
    position: absolute;
    top:70px;
   z-index: 9090;
    right: 5px;
  }
  .timeline
  {
    padding-left: 0px;
  }
</style>
<script type="text/javascript">
function edit_contact(t)
{
  var contact_id = $(t).data('cc-id');
  
  $.ajax({
        url:"<?=base_url('client/edit_contact/')?>",
        type:"post",
        data:{cc_id:contact_id,task:'view'},
        success:function(res)
        {
      if(res){
        var cls = document.getElementById("open");
                cls.click();
        $("#update_content").html(res);
        $("#update_content select").select2();
      }
       
        },
        error:function(u,v,w)
        {
          alert(w);
        }
  });
}

function deleteContact(t)
{
    var contact_id = $(t).data('cc-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
       
        if (result.value) {
            $.ajax({
                        url:"<?=base_url('client/delete_contact/')?>",
                        type:"post",
                        data:{cc_id:contact_id},
                        success:function(res)
                        {
                          Swal.fire('Done!', '', 'success');
                          $(t).closest('tr').remove();
                        },
                        error:function(u,v,w)
                        {
                          alert(w);
                        }
                });
        }
      });
   
                
          
}
//AUTO HIDE SHOW TIMELINE SESSION CODE
 $(document).ready(function()
  {
	  var t_id = <?=$this->session->timline_sts;?>;
    if(t_id == 1){
		$(".timeline").hide(500,function(){
            $(".details-column").removeClass('col-md-6');
            $(".details-column").addClass('col-md-9');
        });
        $(this).data('vis','0');
		$("#toggle_timeline").addClass('fa-angle-left');
        $("#toggle_timeline").removeClass('fa-angle-right');
	}else{
	    $(".timeline").show(500);
        $(this).data('vis','1');
        $(".details-column").addClass('col-md-6');
        $(".details-column").removeClass('col-md-9');
        $("#toggle_timeline").removeClass('fa-angle-left');
        $("#toggle_timeline").addClass('fa-angle-right');		
	}
  })

$(".toogle-timeline").click(function(){
    if($(this).data('vis')=='1')
      { 
        $.ajax({
          url: "<?php echo base_url().'enquiry/timeline_access'?>",
          type: 'POST',          
          data: {
              'pk_id':<?=$this->session->user_id;?>,
			  'status':'1'
          },
        });	  
        $(".timeline").hide(500,function(){
            $(".details-column").removeClass('col-md-6');
            $(".details-column").addClass('col-md-9');
        });
        $(this).data('vis','0');
        $(this).find('i').removeClass('fa-caret-right');
        $(this).find('i').addClass('fa-caret-left');
      }
    else
    {
        $.ajax({
          url: "<?php echo base_url().'enquiry/timeline_access'?>",
          type: 'POST',          
          data: {
              'pk_id':<?=$this->session->user_id;?>,
			  'status':'0'
          },
        }); 
		
        $(".timeline").show(500);
        $(this).data('vis','1');
        $(".details-column").addClass('col-md-6');
        $(".details-column").removeClass('col-md-9');
        $(this).find('i').addClass('fa-caret-right');
        $(this).find('i').removeClass('fa-caret-left');
    }
    //setTimeout(manageScroll,1000);
});
//AUTO HIDE SHOW TIMELINE SESSION CODE END
  $(document).ready(function()
  {

    $.ajax({
          url: "<?php echo base_url().'enquiry/activityTimeline'?>",
          type: 'POST',          
          data: {
              'id':<?=$enquiry_id?>
          },
          success: function(content) {     
          //alert(content);                  
            $(".activitytimelinediv").html(content);
           // $("#task_edit").modal('show');
          }
      });
  })
</script>

<script>
    function get_modal_content(tid){            
      $.ajax({
          url: "<?php echo base_url().'task/get_update_task_content'?>",
          type: 'POST',          
          data: {
              'id':tid
          },
          success: function(content) {                       
            $("#update-task-content").html(content);
           // $("#task_edit").modal('show');
          }
      });
    
    }
</script>


<!-- end chat-->
     
<!----------------------------------------------------------chat section end--------------------------------------------------------->
<script>
    function add_more_phone(add_more_phone) {
       var html='<div class="form-group col-sm-6 col-md-6"><label>Other No </label><input class="form-control"  name="other_no[]" type="text" placeholder="Other Number"   ></div>';
        $('#'+add_more_phone).append(html);          
    }
   function delete_comission(id){
    //alert(id);    
    var url = "<?=base_url().'Enquiry/delete_comission'?>";
    $.ajax({
         type: "POST",
         url: url,
         data: {id:id}, // serializes the form's elements.
         success: function(data)
         {              
            data = JSON.parse(data);
            alert(data.msg);
            if(data.status == true){
              window.location.reload();
            }
         }
    });

  }


  function open_comission_modal(id){
    //alert(id);
    var enquiry_id = "<?=$enquiry->Enquery_id?>";
    //alert(enquiry_id);
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>enquiry/get_update_enquery_comission_content',
        data: {id:id,Enquiry_id:enquiry_id},            
        success:function(data){              
          $("#edit-comission-content").html(data);
          $("#comission_modal_btn").click();
        }
    });
  }

  $("#add_comission_form").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    var url = form.attr('action');
      $.ajax({
         type: "POST",
         url: url,
         data: form.serialize(), // serializes the form's elements.
         success: function(data)
         {              
            data = JSON.parse(data);
            alert(data.msg);
            if(data.status == true){
              window.location.reload();
            }
         }
       });
  });


   function search_comment_and_task(){
        $.ajax({
         type: 'POST',
         url: '<?php echo base_url();?>enquiry/search_comment_and_task',
         data: $('#searc_task').serialize()
        })
        .done(function(data){
             $("#comment_div").attr("style", "display:none");
             $("#comment_div1").attr("style", "display:block");
             $("#task_div").attr("style", "display:none");
             $("#task_div1").attr("style", "display:block");
             $("#comment_div1").html(data.details);
             $("#task_div1").html(data.details1);
             $("#range").attr("style", "display:none");
        })
        .fail(function(){
             alert( "fail!" );   
        });
   }   
   function getTemplates(SMS,type){       
    if(type != 'Send Email'){
      $("#email_subject").hide();
      $("#email_subject").prev().hide();
      $("#template_message").summernote('code','');
      $("#template_message").summernote('destroy');
      //alert("empt");
    }else{
      $("#template_message").html('');
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
   url: '<?php echo base_url();?>message/get_templates/<?php echo $enquiry->product_id ?>/<?php echo $enquiry->status ?>/'+SMS,
   })
   .done(function(data){

       $('#modal-titlesms').html(type);
       $('#mesge_type').val(SMS);       
       $('#templates').html(data);
   })
   .fail(function() {
   alert( "fail!" );   
   });
   } 
   function  send_sms(){   
      var sms_type = $("#mesge_type").val();
     $('#mesge_type').val(sms_type);    
      //if ("<?=$this->session->companey_id?>" == 81 && sms_type!=1) {
       // url =  '<?php echo base_url();?>message/send_sms_career_ex';
      //}else{
       url =  '<?php echo base_url();?>message/send_sms';
     // }    
       $.ajax({            
           type: 'POST',
           url: url,
           data: $('#whatsaap').serialize()
           })
           .done(function(data){               
              //console.log(data);
               alert(data);
               location.reload();
           })
           .fail(function() {
           alert( "fail!" );       
       });     
       }
       
       
       
   $( "#task_create_div1" ).click(function() {     
       if($('#task_create1:visible').length)
           $('#task_create1').hide();
       else
           $('#task_create1').show();        
   });   
   
    function closedmodel(){
     $("#range").attr("style", "display:none");
    }  

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
          var date = parseDate($(this).val());   
          if (! isValidDate(date)) {
            date = moment().format('YYYY-MM-DD');
          }   
          $(this).val(date);
        });
    }      
      var isValidDate = function(value, format) {
        format = format || false;
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
          var date = parseDate($(this).val());   
          if (! isValidDate(date)) {
            date = moment().format('YYYY-MM-DD');
          }   
          $(this).val(date);      
        });
      }
      
    var isValidDate = function(value, format) {
      format = format || false;
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
  $(document).ready(function() {
      $('#txtDate').datepicker({format:'DD-MM-YYYY'});
      $('#txtDate').focus(function() {
        $(this).datepicker("show");
        setTimeout(function() {
          $('#txtDate').datepicker("hide");
          $('#txtDate').blur();
        }, 2000)
      })    
   });
    $(document).ready(function() {
      $('#txtDate2').datepicker({format:'DD-MM-YYYY'});
      $('#txtDate2').focus(function() {
        $(this).datepicker("show");
        setTimeout(function() {
          $('#txtDate2').datepicker("hide");
          $('#txtDate2').blur();
        }, 2000)
      })    
   });       
</script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>


<script type="text/javascript">
  makeMultiselect();
$('#edit_dynamic_query').on('hidden.bs.modal', function () {
   makeMultiselect();
});

function makeMultiselect()
{
$(document).ready(function() {
     $('.multiple-select').select2();
});
}
function edit_dynamic_query(t)
{
        var tab_id = $(t).data('tab-id');
        var cmnt_id = $(t).data('cmnt');
        var enq_code = $(t).data('enq-code');
        var comp_id = $(t).data('comp-id');
        var tabname = $(t).data('tab-name');
        if(cmnt_id!='')
        {
          $.ajax({
            url:'<?=base_url('enquiry/edit_query_data')?>',
            data:{cmnt_id:cmnt_id,tab_id:tab_id,enq_code:enq_code,comp_id:comp_id,tabname:tabname,task:'view'},
            type:'post',
            success:function(res)
            {
              $("#edit_dynamic_query .modal-title").html(tabname);
              $("#edit_dynamic_query_data").html(res);
            },
            error:function(u,v,w)
            {
              alert(w);
            }
          });
        }
        
}
</script>


<script type="text/javascript">
   $(function () {   
     function init_events(ele) {
       ele.each(function () {
         var eventObject = {
           title: $.trim($(this).text())
         }
         $(this).data('eventObject', eventObject)
         $(this).draggable({
           zIndex        : 1070,
           revert        : true,
           revertDuration: 0
         })
       })
     }   
     init_events($('#external-events div.external-event'))
     var date = new Date()
     var d    = date.getDate(),
         m    = date.getMonth(),
         y    = date.getFullYear()
     $('#calendar__').fullCalendar({
       header    : {
         left  : 'prev,next today',
         center: 'title',
         right : 'month,agendaWeek,agendaDay'
       },
       buttonText: {
         today: 'today',
         month: 'month',
         week : 'week',
         day  : 'day'
       },
       events    : [
           <?php foreach ($recent_tasks as $task):
      ?>
         {
           title          : '<?php echo $task->contact_person;?>',
           start          : new Date(y, m, <?php $dt = strtotime($task->nxt_date); echo date('d',$dt); ?>),
           backgroundColor: '#0073b7', 
           url            : '',
           borderColor    : '#0073b7'           
         },
         <?php endforeach; ?>        
       ],
     
     })


     $('#calendar').fullCalendar({
       header    : {
         left  : 'prev,next today',
         center: 'title',
         right : 'month,agendaWeek,agendaDay'
       },
       buttonText: {
         today: 'today',
         month: 'month',
         week : 'week',
         day  : 'day'
       },
       //Random default events
       
       events    : function(start, end, timezone, callback) {
        jQuery.ajax({
            url: "<?php echo base_url().'task/get_calandar_feed/'.$enquiry->Enquery_id?>",
            type: 'POST',
            dataType: 'json',
            data: {
                start: start.format(),
                end: end.format()
            },
            success: function(doc) {
                var events = doc;                
                callback(events);
            }
        });
    }
       ,
       dayClick:function(date,isEvent,view,resourseobj){
    
        
       },
   
     })

   })
   
   
   
   function getMessage(){           
     var tmpl_id = document.getElementById('templates').value;           
     $.ajax({               
         url : '<?php echo base_url('enquiry/msg_templates') ?>',
         type: 'POST',
         data: {tmpl_id:tmpl_id},
         success:function(data){
          //alert(data);
             var obj = JSON.parse(data);
              $('#templates option[value='+tmpl_id+']').attr("selected", "selected");
              $("#template_message").summernote('destroy');
                
              if($("#email_subject").is(':visible'))
              {
                   $("#template_message").summernote("code", obj.template_content);
                   $("#email_subject").val(obj.mail_subject);
              }
              else
              {
                $("#template_message").html(obj.template_content);
              }
             

              
             
         }               
     });        
    } 
     
  function check_status(s){
    if(s==1){  
        $(".dynamic_field").css("display","block")
    } else{
        $(".dynamic_field").css("display","none")
    }  
  }
</script>

<script type="text/javascript">
  
   function find_description(f=0) { 
        auto_followup();
           if(f==0){
            var l_stage = $("#lead_stage_change").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/select_des_by_stage',
            data: {lead_stage:l_stage},
            
            success:function(data){
                //alert(data);
                var html='';
                var obj = JSON.parse(data);                
                html +='<option value="" style="display:none">---Select---</option>';
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].id)+'">'+(obj[i].description)+'</option>';
                }                
                $("#lead_description").html(html);
                
            }
            
            
            });
           }

            }
      
  function auto_followup(){
    var lead_stage = $("#lead_stage_change").val();
    $.ajax({
      type: 'POST',
      url: '<?php echo base_url();?>leadRules/auto_followup_rule',            
      success:function(data){
        if (data) {
          data = JSON.parse(data);
          $.each(data,function(key,val){
            str = val.rule_sql;
            var res = str.replace(/=/g, "==");
            var res = res.replace(/OR/g, "||");
            var res = res.replace(/AND/g, "&&");
            if (eval(res)) {
              ac = val.rule_action;              
              if (ac) {
                $.ajax({
                  type: 'POST',
                  url: '<?php echo base_url();?>leadRules/data_time_add_hr/'+ac,            
                  success:function(data){                    
                    if(data){
                      data = JSON.parse(data);
                      console.log(data);
                      $("#disposition_c_date").val(data[0]);
                      $("#disposition_c_time").val(data[1]);
                    }
                  }
                });
              }
            }
          });
        }
      }
    });
  }

  function find_description1(f=0) { 
           if(f==0){
            var l_stage = $("select[name='move_lead_stage']").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/select_des_by_stage',
            data: {lead_stage:l_stage},            
            success:function(data){
                var html='';
                var obj = JSON.parse(data);                
                html +='<option value="" style="display:none">---Select---</option>';
                for(var i=0; i <(obj.length); i++){
                    html +='<option value="'+(obj[i].id)+'">'+(obj[i].description)+'</option>';
                }
                
                $("#lead_description1").html(html);
                
            }
            
            
            });
           }

            }
            set_default_disposition();
            function set_default_disposition(){
              var lead_stage = $("#lead_stage_change").val();
              var unique_no = $("input[name='unique_no']").val();           
               $.ajax({
                type: 'POST',
                url: '<?php echo base_url();?>lead/get_last_task_by_code',
                data: {enq_code:unique_no},            
                success:function(data){              
                  res = JSON.parse(data);
                  if(res){              
                    task_date  = res.task_date;
                    task_time  = res.task_time;              
                    $("input[name='c_date']").val(task_date);
                    $("input[name='c_time']").val(task_time);                
                    $("input[name='latest_task_id']").val(res.resp_id);
                    $("textarea[name='conversation']").val(res.task_remark);              
                  }              
                }
              });
            }

   document.getElementById("lead_description").onchange = function() {    
    var lead_stage = $("#lead_description").val();

          if(lead_stage == 'new'){
            document.getElementById("otherTypev").style.display = "block"; 
          }else if(lead_stage == 'updt'){            
            var lead_stage = $("#lead_stage_change").val();
            var unique_no = $("input[name='unique_no']").val();           
           $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/get_last_task_by_code',
            data: {enq_code:unique_no},            
            success:function(data){              
              res = JSON.parse(data);
              if(res){              
                task_date  = res.task_date;
                task_time  = res.task_time;              
                $("input[name='c_date']").val(task_date);
                $("input[name='c_time']").val(task_time);                
                $("input[name='latest_task_id']").val(res.resp_id);
                $("textarea[name='conversation']").val(res.task_remark);              
              }              
            }
          });
        }else{
          $("input[name='c_date']").val('');
          $("input[name='c_time']").val('');                
          $("input[name='latest_task_id']").val('');
          $("textarea[name='conversation']").val(''); 
        }
      } 

</script>
<script>
    function find_sub() {
     var sid =  document.getElementById("lead_source").value; 
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>enquiry/get_sub_byid1',
        data: {'sid': sid},
      })
      .done(function(data){
        if(data!=''){
          document.getElementById('sub_source').innerHTML=data;
        }else{
          document.getElementById('sub_source').innerHTML='';   
        }
      })
      .fail(function() {
      });
    }
</script>
<script type="text/javascript">
    function find_state() {    
      var enq_state = $("#country_id").val();
      $.ajax({
      type: 'POST',
      url: '<?php echo base_url();?>enquiry/select_state_by_con',
      data: {enq_state:enq_state},      
      success:function(data){         
          var html='';
          var obj = JSON.parse(data);          
          html +='<option value="" style="display:none">---Select---</option>';
          for(var i=0; i <(obj.length); i++){              
              html +='<option value="'+(obj[i].id)+'">'+(obj[i].state)+'</option>';
          }          
          $("#state_id").html(html);
      }
      });
    }
</script>
        
<script>
  $(document).on('click', '.panel-heading span.icon_minim', function (e) {
      var $this = $(this);
      if (!$this.hasClass('panel-collapsed')) {
          $this.parents('.panel').find('.panel-body').slideUp();
          $this.addClass('panel-collapsed');
          $this.removeClass('glyphicon-minus').addClass('glyphicon-plus');
      } else {
          $this.parents('.panel').find('.panel-body').slideDown();
          $this.removeClass('panel-collapsed');
          $this.removeClass('glyphicon-plus').addClass('glyphicon-minus');
      }
  });

  
  
</script>
<script src="<?php echo base_url();?>assets/js/fullcalendar.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript">
   $(function () {
    $("#enquiry_date").datepicker({dateFormat:'yy-mm-dd'});
  });

   jQuery(document).ready(function(){
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false                 // set focus to editable area after initializing summernote
    });
  });

   // delete task
    function delete_row(id){
      var result = confirm("Want to delete?");
      if (result) { 
        url = "<?=base_url().'task/delete_task_row'?>"
        $.ajax({
          type: "POST",
          url: url,
          data: {'id':id},
          success: function(data){                
            data = JSON.parse(data);
            alert(data.msg);
            if(data.status){
              location.reload();
            }
          }
        });
      }
    }
   // $("select[name='institute_id']").select2();
    $("#fcity").select2();
    $("#fstate").select2();



    $(document).on("change",'#fcity',function () {
      var selDpto = $(this).val();
      if (selDpto) {        
        $.ajax({
          url: "<?php echo base_url();?>lead/select_state_by_city",
          async: false,
          type: "POST",
          data: {city_id:selDpto},
          dataType: 'html',
          success: function(data) {
          var obj=JSON.parse(data);
            $('#fstate').val(obj.state_id);
            $('#myList').select2("val", obj.state_id);
        }
        })
      } 
    });

</script>

<script>

$("a[href$='#related_enquiry']").on('click',function(){
  var phone = "<?=$details->phone?>";
  var enquiry_id = "<?=$details->enquiry_id?>";
  $.ajax({
    type: 'POST',
    url: '<?php echo base_url();?>enquiry/related_enquiry',
    data: {phone:phone,enquiry_id:enquiry_id},  
    success:function(data){
      $("#related_enquiry").html(data);
    }
  });
});


  $("input[name='submit_and_next']").on('click',function(){
    $(this).next().val('save_and_new');
  });

  $("input[name='submit_only']").on('click',function(){
    $(this).next().next().val('save_only');
  });
  
  /*ajax form submit*/
  $('.tabbed_form').on('submit',function(e) {    
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    var url = form.attr('action');
    var formData = new FormData($(this)[0]);
    if("<?=$this->session->companey_id?>"== 90 && ($("input[name='mobileno']").val() == '' && $("input[name='email']").val()=='' )){
       alert('Either Mobile or Email filed is requiry');
       return false;
    }

    $.ajax({
       type: "POST",
       url: url,
       data: formData, // serializes the form's elements.
       async: false,
       cache: false,
       contentType: false,
       processData: false,
       beforeSend:function(){
        $(form).find('input[type=submit]').attr('disabled','disabled');
       },
       success: function(data)
       {
           res = JSON.parse(data);
           if (res.status) {
              Swal.fire(
                'Good job!',
                res.msg,
                'success'
              );
              var btn = form.find("input[name='go_new_tab']").val();              
              if (btn == 'save_and_new') {                
                var next = jQuery('.nav-tabs > .active').next('li');
                if(next.length){
                  next.find('a').trigger('click');
                }else{
                  jQuery('#myTabs a:first').tab('show');
                }
              }
           }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Something went wrong!'
            })
           }  
           $(form).find('input[type=submit]').removeAttr('disabled');         
       }
     });
  });
   function show_disposition(){
    h  = $("#disposition-section").html();
    $("#dispo_modal .modal-body").html(h);
  }
  
  function show_timeline(){
    h  = $(".activitytimelinediv").html();    
    $("#timeline_modal .modal-body").html(h);    
  }
  $("#institute-tab").on('click',function(){
    $("#institute").load("<?=base_url().'enquiry/get_institute_tab_content/'.$enquiry_id?>");
  })
</script>


 <script>
jQuery(document).ready(function(){
   $('#main_tab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');  
   });
 
   // store the currently selected tab in the hash value
   $("ul.nav-tabs > li").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;
      setTimeout(function() {
         window.scrollTo(0, 0);           
         $(".col-height").scrollTop(0);
      },0);
   });
   // on load of the page: switch to the currently selected tab
   var hash = window.location.hash;   
   if(hash==''){
      $('li[href="#basic"]').click();      
   }else{
      $('li[href="' + hash + '"]').click();
   } 
});

if("<?=$this->session->companey_id?>"==65){
   $(".enq-remark").insertAfter(".website");
}

<?php
if($this->session->process[0]=='200')
{
  //moble number relocate in V-trans process 200
?>

$(document).ready(function(){ 
  relocate_mobile();
});
  function relocate_mobile()
  {
    var enq_form = $("#basic");
    var mobile_fld= $(enq_form).find('input[name=mobileno]').parents('div.form-group');
    var more_phone = $("#add_more_phone");
    $(mobile_fld).find('label').html('Parents Mobile No <font color="red">*</font>');
    var mobile_clone = $(mobile_fld).clone();
    var more_clone = more_phone.clone();
   $(enq_form).find('#parent-name').parents('div.form-group').after(mobile_clone);
   //$(enq_form).find('input[name=mobileno]').parents('div.form-group').after(mobile_clone);
   $(mobile_clone).after(more_clone);
   $(mobile_fld).remove();
   $(more_phone).remove();

  }
<?php
}
?>
$(".nav-tabs li").on('click',function(){
  $(window).trigger('resize');
  $(window).trigger('resize');
});

   $(document).on("click", "#tagDrop", function (e) {
        let id = $(this).attr('data-id');
        let enqId = $(this).attr('data-enq');
        // alert(enqId);
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to Delete This Tag',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: "<?= base_url('enq/drop_tag/')?>",
                    type: "POST",
                    data: {
                        tag_delete: true,
                        id: id,
                        enq: enqId
                    },
                    success: function (data) {
                        // console.log(data);
                        Swal.fire(
                            'Deleted!',
                            'Your Tag deleted.',
                            'success',
                            5000
                        );
                        location.reload();
                    },
                    error: function (err) {          
                        Swal.fire(
                            'Error!',
                            'Something Was Worng!',
                            'error',
                            5000
                        );
                    }
                });

            }
        })
    });
    function sync_with_shipx(agreement_id){
      if(confirm('Are you sure ? Your data will be send to shipx portal.') && agreement_id){
        $.ajax({
          url: "<?= base_url('shipx_integration/push_data/')?>"+agreement_id,
          type: "POST",
          success: function (response) {
            res = JSON.parse(response);
            if(res.status == 1){
              Swal.fire(
                    'Data Pushed To Shipx',
                    '',
                    'success'
                );
                location.reload();
            }else{
              Swal.fire(
                'Error!',
                res.msg,
                'error'
                );
            }
          }
        });
      }
    }
</script>
