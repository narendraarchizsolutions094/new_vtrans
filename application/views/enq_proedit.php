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
  <?php if($this->session->companey_id == 29) {        
    $c = 0;
    if ($enquiry->partner_id && $enquiry->status == 3) {
      $this->db->from('enquiry');
      $this->db->where('reference_name',$enquiry->partner_id);        
      $c = $this->db->count_all_results();      
    }            
    if($c == 0){ ?>
      a[href$="amount"],[href$="Payout_Preference"]{
        display: none !important;
    	}
    <?php
    }          

  }

  ?>
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
  content: "☣";
}

.cbp_tmicon-screen:before {
  content: "☣";
}

.cbp_tmicon-mail:before {
  content: "☣";
}

.cbp_tmicon-earth:before {
  content: "☣";
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
    position: relative;
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
    float: right;
    left: auto;
    margin: -55px 5px 0 0px;
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

</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<div class="row">




   <div  class="row" id="ThreadEnq">
      <div class="col-md-3 col-height">        
         
      </div>
      <div class="col-md-6 card card-body col-height" style="background:#fff;border-top: unset;">
         <div id="exTab3" class="">
            <ul  class="nav nav-tabs" role="tablist">              
              <li class="active"><a  href="#basic" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">Basic</a></li>
			  <?php if($this->session->userdata('user_right')!=151){ ?>
              <li><a href="#task" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">Task</a></li>
			  <?php } ?>			  
             <?php if($this->session->userdata('companey_id')==292) {  if($enquiry->status==3) {?>
              <li><a href="#followup" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">AMC</a></li>

               <?php } } if($enquiry->status==3) { 

                 if(in_array(300,$module) || in_array(301,$module) || in_array(302,$module) || in_array(303,$module)){ 
                ?>
                <li><a href="#order" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">Order Details</a></li> 
                <?php }
                 }?>     
                 <?php if($this->session->userdata('companey_id')==29){?>  
                <li><a href="#amount" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">Amount</a></li> 
                <?php }?>      
              <?php
              if (user_access('240')===true || $this->session->userdata('user_right')==151) { ?>
               <li><a href="#institute" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">Institute</a></li>
                <?php
              }
              if(!empty($tab_list)){
                foreach ($tab_list as $key => $value) { 
                  if ($value['id'] != 1) { ?>
                    <li><a href="#<?=str_replace(' ', '_', $value['title'])?>" data-toggle="tab" style="padding: 10px 10px; font-size:10px;"><?=$value['title']?></a></li>
                <?php
                  }
                }
              }
              ?>
			<?php if ($this->session->companey_id=='67') { ?>			  
			  <li><a href="#payment" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">Payment</a></li>
              <li><a href="#aggrement" data-toggle="tab" style="padding: 10px 10px; font-size:10px;">Aggrement</a></li>
			<?php } ?>
            </ul>
            <div class="tab-content clearfix">
                <div class="tab-pane active" id="basic">
                  <?php echo tab_content(1,$this->session->companey_id,$enquiry_id); ?>
               </div>

               
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


<div class="tab-pane" id="followup">
<div class="action-btn">
<a href="" class="btn btn-xs btn-success" data-toggle="modal" data-target="#addamc">Add AMC</a>                       
</div>
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width:20px;"><?php echo display('serial') ?></th>
                            <th>Product Name</th>
                           
                            <th>From Date</th> 
                            <th>To Date</th> 
                                                    
                       
                        </tr>
                    </thead>
                    <tbody>
                       <?php if(!empty($amc_list)){
                         $sl=1;
                        foreach($amc_list as $amclist){?>
                          <tr>
                            <td><?= $sl; ?></td>
                            <td><?= ucwords($amclist['country_name']); ?></td>
                            <td><?= $amclist['amc_fromdate']?></td>
                            <td><?= $amclist['amc_todate']?></td>

                          </tr>

                        <?php }  $sl++;

                      } ?>
                    </tbody>
                  </table>
 <div id="addamc" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    
    <div class="modal-content">
       <?php echo form_open_multipart('client/add_amc'); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add AMC</h4>
      </div>
     
      <div class="modal-body">
            
              <div class="row">
                  
            <input type="hidden" name="enqid" value="<?= $enquiry_id?>">
            <div class="form-group col-md-12">  
            <label>Select Product</label> 
            <select class="form-control"  name="productlist" id="productlist">   
            <option value="">Select</option>
            <?php if(!empty($prod_list)){

              foreach($prod_list as $prodlist){ ?>

             <option value="<?= $prodlist['id'] ?>"><?= $prodlist['country_name']?></option>

         <?php  }
             }  
             ?>                                                          
            </select> 
            </div>

            <div class="form-group col-md-6">  
            <label>From Date</label>    
            <input type="date" name="fromdate" class="form-control" id="fromdate">       
            </div>

            <div class="form-group col-md-6">  
            <label>To Date</label>    
            <input type="date" name="todate" class="form-control" id="todate">       
            </div>

            <div class="form-group col-md-12" style="display: none">  
            <label>Attach PO</label>    
            <input type="file" name="po" class="form-control" id="po">       
            </div>
            
            <input type="hidden" value="<?= $compid; ?>" class="" name="compid" id="compid">  
            <!-- <input type="hidden" value="<?= $enqid ?>" class="" name="userid" id="userid">          -->
     
            </div>
            
         <div class="modal-footer">
           <button class="btn btn-success" type="submit">Add</button>   
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
               
            <?php echo form_close(); ?>

            </div>
          </div>
        </div>
      </div>
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
                          <input class="form-control" name="dateofpay" type="date" required>  
                       </div>
                      </div>
                      <div class="row">                                          
                       <div class="form-group col-sm-4"> 
                          <label>TDS</label>
                          <input class="form-control" name="tds" type="text" placeholder="TDS" required>  
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

               <div class="tab-pane" id="institute">
                  <hr>
                  <div class="card card-body" style="overflow-y: scroll;">
                        <table class="table table-bordered">                          
                          <thead>
                            <tr>
                              <th>
                                Institute Name
                              </th>
							  <th>
                                Course Name
                              </th>
							  <th>
                                Program Discipline
                              </th>
							  <th>
                                Program Lavel
                              </th>
							  <th>
                                Program Length
                              </th>
							  <th>
                                Tuition Fee
                              </th>
							  <th>
                                Offer letter fee
                              </th>
                              <th>
                                Application URL
                              </th>
							  <?php if ($this->session->companey_id!='67') { ?>
                              <th>
                                Major
                              </th>
                              <th>
                                Username
                              </th>
                              <th>
                                Password
                              </th>
							  <?php } ?>
                              <th>
                                App status
                              </th>
                              <th>
                                App Fee
                              </th>
                              <th>
                                Transcripts
                              </th>
                              <th>
                                LORs
                              </th>
                              <th>
                                SOP
                              </th>
                              <th>
                                CV
                              </th>
							  <?php if ($this->session->companey_id!='67') { ?>
                              <th>
                                GRE/GMAT
                              </th>
							  <?php } ?>
                              <th>
                                TOEFL/IELTS /PTE
                              </th>
                              <th>
                                Remarks
                              </th>
                              <th>
                                Followup Comments
                              </th>
                              <th>
                                Actions
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (!empty($institute_data)) {
                              foreach ($institute_data as $key => $value) { ?>
                                <tr>
                                    <td><?=$value['institute_name']?></td>
<td><?php foreach($course_list as $cl){if($cl->crs_id==$value['course_id']){echo $cl->course_name;}}?></td>
<td><?php foreach($discipline as $dc){if($dc->id==$value['p_disc']){echo $dc->discipline;}}?></td>
<td><?php foreach($level as $lvl){if($lvl->id==$value['p_lvl']){echo $lvl->level;}}?></td>
<td><?php foreach($length as $lg){if($lg->id==$value['p_length']){echo $lg->length;}}?></td>
<td><?=$value['t_fee']?></td>
<td><?=$value['ol_fee']?></td>                                    
                                    <td><?=$value['application_url']?></td>
									<?php if ($this->session->companey_id!='67') { ?>
                                    <td><?=$value['major']?></td>
                                    <td><?=$value['user_name']?></td>
                                    <td><?=$value['password']?></td>
									<?php } ?>
                                    <td><?=$value['app_status_title']?></td>
                                    <td><?=$value['app_fee']?></td>
                                    <td><?=$value['transcript']?></td>
                                    <td><?=$value['lors']?></td>
                                    <td><?=$value['sop']?></td>
                                    <td><?=$value['cv']?></td>
									<?php if ($this->session->companey_id!='67') { ?>
                                    <td><?=$value['gre_gmt']?></td>
									<?php } ?>
                                    <td><?=$value['toefl']?></td>
                                    <td><?=$value['remark']?></td>
                                    <td><?=$value['followup_comment']?></td>
                                    <td>
                                      <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="delete_institute(<?=$value['id']?>)"><i class="fa fa-trash" ></i></a>
                                      <a href="javascript:void(0)" class="btn btn-primary btn-sm " onclick="open_institute_modal(<?=$value['id']?>)"><i class="fa fa-pencil" ></i></a>
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
                    <?php echo form_open_multipart('enquiry/add_enquery_institute/'.base64_encode($details->Enquery_id),array('class'=>"form-inner",'id'=>'add_institute_form')) ?>                      
                      <div class="row">                                          
                       <div class="form-group col-sm-4"> 
                          <label>Institute Name <i class="text-danger">*</i></label>
                          <select class="form-control" name='institute_id' id='institute_id' required>
                          <option value="">Please Select</option>
						  <?php
                          if(!empty($institute_list)){
                            foreach ($institute_list as $key => $value) { ?>
                              <option value="<?=$value->institute_id?>"><?=$value->institute_name?></option>
                            <?php
                            }
                          }
                          ?>
                          </select>                        
                       </div>
 
<?php if ($this->session->companey_id=='67') { ?>

<div class="form-group col-sm-4">                         
                          <label><?php echo display('program_discipline')?> </label>                          
                          <select name="p_disc" id="p_disc" class="form-control" onchange="">
						      <option value="">Select</option>
                             <?php foreach($discipline as $dc){ ?>                                   
                        <option value="<?php echo $dc->id; ?>"><?php echo $dc->discipline; ?></option>
                    <?php } ?>
                    </select>							  
                          </select>                          
</div>
<div class="form-group col-sm-4">                         
                          <label>Program Lavel </label>                          
                          <select name="p_lvl" id="p_lvl" class="form-control" onchange="find_level()">
						      <option value="">Select</option>
                             <?php foreach($level as $lc){ ?>                                   
                        <option value="<?php echo $lc->id; ?>"><?php echo $lc->level; ?></option>
                    <?php } ?>							  
                          </select>                          
</div>
<div class="form-group col-sm-4">                         
                          <label>Program Length </label>                          
                          <select name="p_length" id="p_length" class="form-control" onchange="find_app_crs()">
							  
                          </select>                          
</div>

<div class="form-group col-sm-4"> 
                        
                          <label>Select Course </label>                          
                          <select name="app_course" id="app_course" class="form-control">
                                                     
                          </select>                          
                       </div>

<div class="form-group col-sm-4"> 
                          <label>Tuition fee</label>
                          <input class="form-control" name="t_fee" type="text" placeholder="Tuition fee" >  
</div>
<div class="form-group col-sm-4"> 
                          <label>Offer letter fee</label>
                          <input class="form-control" name="ol_fee" type="text" placeholder="O.letter fee" >  
</div>
<?php } ?>					   
                       <div class="form-group col-sm-4"> 
                          <label>Application URL </label>
                          <input class="form-control" name="application_url" type="text" placeholder="Application Url" >  
                       </div>
<?php if ($this->session->companey_id!='67') { ?>
                       <div class="form-group col-sm-4"> 
                          <label>Major </label>
                          <input class="form-control" name="major" type="text" placeholder="Major" >  
                       </div>
					  
                       <div class="form-group col-sm-4"> 
                          <label>User Name </label>
                          <input class="form-control" name="username" type="text" placeholder="Username" >  
                       </div>
                                                               
                       <div class="form-group col-sm-4"> 
                          <label>Password </label>
                          <input class="form-control" name="password" type="text" placeholder="Password" >  
                       </div>
<?php } ?>
                       <div class="form-group col-sm-4"> 
                        
                          <label>App Status </label>                          
                          <select name="app_status" class="form-control" >
                          <?php                                                    
                          if (!empty($institute_app_status)) {
                            foreach ($institute_app_status as $key => $value) {
                              ?>
                              <option value="<?=$value['id']?>"><?=$value['title']?></option>
                              <?php
                            }
                          }
                          ?>                            
                          </select>                          
                       </div>
                     
                       <div class="form-group col-sm-4"> 
                          <label>App Fee </label>
                          <input class="form-control" name="app_fee" type="text" placeholder="App Fee" >  
                       </div>
                      
                      
                       <div class="form-group col-sm-4"> 
                          <label>Transcript </label>
                          <input class="form-control" name="transcript" type="text" placeholder="Transcript" >  
                       </div>

                       <div class="form-group col-sm-4"> 
                          <label>LORs </label>
                          <input class="form-control" name="lors" type="text" placeholder="Lors" >  
                       </div>
                      
                       <div class="form-group col-sm-4"> 
                          <label>SOP </label>
                          <input class="form-control" name="sop" type="text" placeholder="SOP" >  
                       </div>

                                                             
                       <div class="form-group col-sm-4"> 
                          <label>CV </label>
                          <input class="form-control" name="cv" type="text" placeholder="cv" >  
                       </div>
<?php if ($this->session->companey_id!='67') { ?>
                       <div class="form-group col-sm-4"> 
                          <label>GRE/GMAT </label>
                          <input class="form-control" name="gre_gmt" type="text" placeholder="GRE/GMAT" >  
                       </div>
<?php } ?>
                      
                       <div class="form-group col-sm-4"> 
                          <label>TOEFL/IELTS/PTS </label>
                          <input class="form-control" name="tofel_ielts_pts" type="text" placeholder="TOEFL/IELTS/PTS" >  
                       </div>

                                                              
                       <div class="form-group col-sm-4"> 
                          <label>Remarks </label>
                          <textarea class="form-control" placeholder="Remark" name="remark"></textarea>
                       </div>

                       <div class="form-group col-sm-4"> 
                          <label>Followup Comments </label>
                          <textarea class="form-control" placeholder="Followup comments" name="followup_comment"></textarea>
                       </div>
                     
 <?php if ($this->session->companey_id!='67') { ?>                        
                       <div class="form-group col-sm-4"> 
                          <label>Reference No </label>
                          <input class="form-control" name="reference_no" type="text" placeholder="Reference No" >  
                       </div>
                                                           
                       <div class="form-group col-sm-4"> 
                          <label>Courier Status </label>
                          <input class="form-control" name="courier_status" type="text" placeholder="Courier Status" >  
                       </div>
 <?php } ?>
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

              <div class="tab-pane" id="personaltab">
                  <hr>
                  <?php echo form_open_multipart('client/updateclientpersonel/'.$details->enquiry_id,'class="form-inner"') ?>  
                  <input type="hidden" name="form" value="client">
                    <!--------------------------------------------------start----------------------------->
                    <?php if(!empty($personel_list)){ ?>
                    <?php foreach($personel_list as $alldetails){ ?>
                        <input class="form-control" name="unique_number" type="hidden" value="<?php echo $alldetails->unique_number; ?>">                      
                     <div class="form-group col-sm-4"> 
                        <label>Date of Birth</label>
                        <input class="form-control" name="date_of_birth" type="date" value="<?php echo $alldetails->date_of_birth; ?>" style="padding:0px !important;">  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Marital status</label>
                        <select class="form-control" name="marital_status" id="marital_status">
                            <option value="">-Select Marital Status-</option>
                            <option value="Single" <?php if(!empty($personel_list)){if($alldetails->marital_status=='Single'){echo 'selected';}} ?>>Single</option>
                            <option value="Married" <?php if(!empty($personel_list)){if($alldetails->marital_status=='Married'){echo 'selected';}} ?>>Married</option>
                            <option value="Widowed" <?php if(!empty($personel_list)){if($alldetails->marital_status=='Widowed'){echo 'selected';}} ?>>Widowed</option>
                            <option value="Separated" <?php if(!empty($personel_list)){if($alldetails->marital_status=='Separated'){echo 'selected';}} ?>>Separated</option>
                            <option value="Divorced" <?php if(!empty($personel_list)){if($alldetails->marital_status=='Divorced'){echo 'selected';}} ?>>Divorced</option>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Last Communication</label>
                        <input class="form-control" name="last_comm" type="date" value="<?php echo $alldetails->last_comm; ?>" style="padding:0px !important;">  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Mode Of Communication</label>
                        <input class="form-control" name="mode_of_comm" type="text" value="<?php echo $alldetails->mode_of_comm; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Remark</label>
                        <input class="form-control" name="remark" type="text" value="<?php echo $alldetails->remark; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Mother Toung</label>
                        <input class="form-control" name="mother_tongue" type="text" value="<?php echo $alldetails->mother_tongue; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Other Language</label>
                        <input class="form-control" name="other_language" type="text" value="<?php echo $alldetails->other_language; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Address Line 1</label>
                        <input class="form-control" name="corres_add_line1" type="text" value="<?php echo $alldetails->corres_add_line1; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Address Line 2</label>
                        <input class="form-control" name="corres_add_line2" type="text" value="<?php echo $alldetails->corres_add_line2; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Address Line 3</label>
                        <input class="form-control" name="corres_add_line3" type="text" value="<?php echo $alldetails->corres_add_line3; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Country</label>
                        <select class="form-control" name="corres_country_id" id="country_id" onchange="find_state()">
                            <option value="">-Select Country-</option>
                            <?php foreach($allcountry_list as $country){ ?>
                            <option value="<?php echo $country->id_c; ?> " <?php if(!empty($allcountry_list)){if($alldetails->corres_country_id==$country->id_c){echo 'selected';}} ?>><?php echo $country->country_name; ?> </option>
                            <?php } ?>
                        </select>


                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence State</label>
                        <select class="form-control" name="corres_state_id" id="state_id">
                            <option value="">-Select State-</option>
                            <?php foreach($allstate_list as $state){ ?>
                            <option value="<?php echo $state->id; ?> " <?php if(!empty($allstate_list)){if($alldetails->corres_state_id==$state->id){echo 'selected';}} ?>><?php echo $state->state; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence District</label>
                        <select class="form-control" name="corres_district_id" id="corres_district_id">
                            <option value="">-Select District-</option>
                            <?php foreach($allcity_list as $city){ ?>
                            <option value="<?php echo $city->id; ?> " <?php if(!empty($allcity_list)){if($alldetails->corres_district_id==$city->id){echo 'selected';}} ?>><?php echo $city->city; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Pincode</label>
                        <input class="form-control" name="corres_pincode" type="text" value="<?php echo $alldetails->corres_pincode; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Landmark</label>
                        <input class="form-control" name="corres_landmark" type="text" value="<?php echo $alldetails->corres_landmark; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Address Line 1</label>
                        <input class="form-control" name="perm_add_line1" type="text" value="<?php echo $alldetails->perm_add_line1; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Address Line 2</label>
                        <input class="form-control" name="perm_add_line2" type="text" value="<?php echo $alldetails->perm_add_line2; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Address Line 3</label>
                        <input class="form-control" name="perm_add_line3" type="text" value="<?php echo $alldetails->perm_add_line3; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Country</label>
                        <select class="form-control" name="perm_country_id" id="perm_country_id">
                            <option value="">-Select Country-</option>
                            <?php foreach($allcountry_list as $country){ ?>
                            <option value="<?php echo $country->id_c; ?> " <?php if(!empty($allcountry_list)){if($alldetails->perm_country_id==$country->id_c){echo 'selected';}} ?>><?php echo $country->country_name; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent State</label>
                      <select class="form-control" name="perm_state_id" id="perm_state_id">
                          <option value="">-Select State-</option>
                          <?php foreach($allstate_list as $state){ ?>
                          <option value="<?php echo $state->id; ?> " <?php if(!empty($allstate_list)){if($alldetails->perm_state_id==$state->id){echo 'selected';}} ?>><?php echo $state->state; ?> </option>
                          <?php } ?>
                      </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent District</label>
                        <select class="form-control" name="perm_district_id" id="perm_district_id">
                            <option value="">-Select District-</option>
                            <?php foreach($allcity_list as $city){ ?>
                            <option value="<?php echo $city->id; ?> " <?php if(!empty($allcity_list)){if($alldetails->perm_district_id==$city->id){echo 'selected';}} ?>><?php echo $city->city; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Pincode</label>
                        <input class="form-control" name="perm_pincode" type="text" value="<?php echo $alldetails->perm_pincode; ?>" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Landmark</label>
                        <input class="form-control" name="perm_landmark" type="text" value="<?php echo $alldetails->perm_landmark; ?>" >  
                     </div>
                      <?php } ?>
                      <?php }else{ ?>
                      <!-----------------------------------------------start-------------------------------------------->
                       <input class="form-control" name="unique_number" type="hidden" value="">  
                    
                     <div class="form-group col-sm-4"> 
                        <label>Date of Birth</label>
                        <input class="form-control" name="date_of_birth" type="date" value="" style="padding:0px !important;">  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Marital status</label>
                        <select class="form-control" name="marital_status" id="marital_status">
                            <option value="">-Select Marital Status-</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Last Communication</label>
                        <input class="form-control" name="last_comm" type="date" value="" style="padding:0px !important;">  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Mode Of Communication</label>
                        <input class="form-control" name="mode_of_comm" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Remark</label>
                        <input class="form-control" name="remark" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Mother Toung</label>
                        <input class="form-control" name="mother_tongue" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Other Language</label>
                        <input class="form-control" name="other_language" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Address Line 1</label>
                        <input class="form-control" name="corres_add_line1" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Address Line 2</label>
                        <input class="form-control" name="corres_add_line2" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Address Line 3</label>
                        <input class="form-control" name="corres_add_line3" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Country</label>
                        <select class="form-control" name="corres_country_id" id="corres_country_id1">
                          <option value="">-Select Country-</option>
                          <?php foreach($allcountry_list as $country){ ?>
                          <option value="<?php echo $country->id_c; ?> "><?php echo $country->country_name; ?> </option>
                          <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence State</label>
                        <select class="form-control" name="corres_state_id" id="corres_state_id1">
                            <option value="">-Select State-</option>
                            <?php foreach($allstate_list as $state){ ?>
                            <option value="<?php echo $state->id; ?> "><?php echo $state->state; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence District</label>
                        <select class="form-control" name="corres_district_id" id="corres_district_id">
                            <option value="">-Select District-</option>
                            <?php foreach($allcity_list as $city){ ?>
                            <option value="<?php echo $city->id; ?> "><?php echo $city->city; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Pincode</label>
                        <input class="form-control" name="corres_pincode" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Correspondence Landmark</label>
                        <input class="form-control" name="corres_landmark" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Address Line 1</label>
                        <input class="form-control" name="perm_add_line1" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Address Line 2</label>
                        <input class="form-control" name="perm_add_line2" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Address Line 3</label>
                        <input class="form-control" name="perm_add_line3" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Country</label>
                        <select class="form-control" name="perm_country_id" id="perm_country_id">
                            <option value="">-Select Country-</option>
                            <?php foreach($allcountry_list as $country){ ?>
                            <option value="<?php echo $country->id_c; ?> "><?php echo $country->country_name; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent State</label>
                        <select class="form-control" name="perm_state_id" id="perm_state_id">
                            <option value="">-Select State-</option>
                            <?php foreach($allstate_list as $state){ ?>
                            <option value="<?php echo $state->id; ?> "><?php echo $state->state; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent District</label>
                        <select class="form-control" name="perm_district_id" id="perm_district_id">
                            <option value="">-Select District-</option>
                            <?php foreach($allcity_list as $city){ ?>
                            <option value="<?php echo $city->id; ?>"><?php echo $city->city; ?> </option>
                            <?php } ?>
                        </select>
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Pincode</label>
                        <input class="form-control" name="perm_pincode" type="text" value="" >  
                     </div>
                     <div class="form-group col-sm-4"> 
                        <label>Permanent Landmark</label>
                        <input class="form-control" name="perm_landmark" type="text" value="" >  
                     </div>
                     <?php } ?>
                     <div class="col-md-6"  id="save_button">
                        <div class="row">
                           <div class="col-md-12">                                                
                              <button class="btn btn-primary" type="submit" >Save</button>            
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="kyctab">
                  <hr>
                  <div class="row">
                     <table class="table table-responsive-sm" style="background: #fff;">
                        <thead class="thead-light">
                           <tr>                              
                              <th>S.N.</th>
                              <th>Document Name</th>
                              <th>Document Number</th>
                              <th>Valid Up To</th>
                              <th>Created On</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sl = 1;
                              if(!empty($kyc_doc_list)){
                              foreach ($kyc_doc_list as $kycDoc) { 
                              ?>
                           <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $kycDoc->doc_name; ?></td>
                              <td><?php echo $kycDoc->doc_number; ?></td>
                              <td><?php echo ($kycDoc->doc_validity !='')?$kycDoc->doc_validity:'N/A'; ?></td>
                              <td><?php echo $kycDoc->created_date; ?></td>
                              <td><a href="<?php echo base_url($kycDoc->doc_file);?>" target="_blank"><i class="fa fa-eye" style="color:green;"></i></a></td>
                           </tr>
                           <?php $sl++; }} ?>
                        </tbody>
                     </table>
                     <br>
                     <center>
                        <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewKyc" class="btn btn-primary">Add new</a></h5>
                     </center>
                     <br>              
                  </div>
               </div>
               <div class="tab-pane" id="workhistorytab">
                  <hr>
                  <div class="row">
                     <table class="table table-responsive-sm" style="background: #fff;">
                        <thead class="thead-light">
                           <tr>                              
                              <th>S.N.</th>
                              <th>Company Name</th>
                              <th>Designation</th>
                              <th>Start Date</th>
                              <th>End Date</th>
                              <th>Current CTC <small>(Lac)</small></th>
                              <th>Created On</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sl = 1;
                              if(!empty($work_history_list)){
                              foreach ($work_history_list as $itemObj) { 
                              ?>
                           <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $itemObj->company; ?></td>
                              <td><?php echo $itemObj->designation; ?></td>
                              <td><?php echo date('d-m-Y',strtotime($itemObj->start_date)); ?></td>
                              <td><?php echo date('d-m-Y',strtotime($itemObj->end_date)); ?></td>
                              <td><?php echo $itemObj->current_ctc; ?></td>
                              <td><?php echo $itemObj->created_date; ?></td>
                              <td></td>
                           </tr>
                           <?php $sl++; }} ?>
                        </tbody>
                     </table>
                     <br>
                     <center>
                        <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewWork" class="btn btn-primary">Add new</a></h5>
                     </center>
                     <br>              
                  </div>
               </div>
               <div class="tab-pane" id="educationtab">
                  <hr>
                  <div class="row">
                     <table class="table table-responsive-sm" style="background: #fff;">
                        <thead class="thead-light">
                           <tr>                              
                              <th>S.N.</th>
                              <th>Title</th>
                              <th>University</th>
                              <th>Year of Passing</th>
                              <th>Created On</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sl = 1;
                              if(!empty($education_list)){
                              foreach ($education_list as $itemObj) { 
                              ?>
                           <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $itemObj->title; ?></td>
                              <td><?php echo $itemObj->university; ?></td>
                              <td><?php echo $itemObj->passing_year; ?></td>
                              <td><?php echo $itemObj->created_date; ?></td>
                           </tr>
                           <?php $sl++; }} ?>
                        </tbody>
                     </table>
                     <br>
                     <center>
                        <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewEducation" class="btn btn-primary">Add new</a></h5>
                     </center>
                     <br>              
                  </div>
               </div>
                <div class="tab-pane" id="socialprofiletab">
                  <hr>
                  <div class="row">
                     <table class="table table-responsive-sm" style="background: #fff;">
                        <thead class="thead-light">
                           <tr>                              
                              <th>S.N.</th>
                              <th>Social Media</th>
                              <th>Profile URL</th>
                              <th>Created On</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sl = 1;
                              if(!empty($social_profile_list)){
                              foreach ($social_profile_list as $itemObj) { 
                              ?>
                           <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $itemObj->title; ?></td>
                              <td><?php echo $itemObj->profile; ?></td>
                              <td><?php echo $itemObj->created_date; ?></td>
                           </tr>
                           <?php $sl++; }} ?>
                        </tbody>
                     </table>
                     <br>
                     <center>
                        <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewSprofile" class="btn btn-primary">Add new</a></h5>
                     </center>
                     <br>              
                  </div>
               </div>
                <div class="tab-pane" id="travelhistorytab">
                  <hr>
                  <div class="row">
                     <table class="table table-responsive-sm" style="background: #fff;">
                        <thead class="thead-light">
                           <tr>                              
                              <th>S.N.</th>
                              <th>County</th>
                              <th>Travel Date</th>
                              <th>Visa Type</th>
                              <th>Visa Duration</th>
                              <th>Is Rejected</th>
                              <th>Created On</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sl = 1;
                              if(!empty($travel_history_list)){
                              foreach ($travel_history_list as $itemObj) { 
                              ?>
                           <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $itemObj->country_name; ?></td>
                              <td><?php echo date('d-m-Y',strtotime($itemObj->travel_date)); ?></td>
                              <td><?php echo $itemObj->visa_type; ?></td>
                              <td><?php echo date('d-m-Y',strtotime($itemObj->dfrom_date)).'-'.date('d-m-Y',strtotime($itemObj->dto_date)); ?></td>
                              <td><?php echo ($itemObj->is_rejected ==1)?'Yes':'No'; ?></td>
                              <td><?php echo $itemObj->created_date; ?></td>
                           </tr>
                           <?php $sl++; }} ?>
                        </tbody>
                     </table>
                     <br>
                     <center>
                        <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewTravel" class="btn btn-primary">Add new</a></h5>
                     </center>
                     <br>              
                  </div>
               </div> 
                <div class="tab-pane" id="familydetailtab">
                  <hr>
                  <div class="row">
                     <table class="table table-responsive-sm" style="background: #fff;">
                        <thead class="thead-light">
                           <tr>                              
                              <th>S.N.</th>
                              <th>Name</th>
                              <th>Contact</th>
                              <th>Email</th>
                              <th>County</th>
                              <th>relationship</th>
                              <th>Visa Status</th>
                              <th>They Help</th>
                              <th>Created On</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sl = 1;
                              if(!empty($close_femily_list)){
                              foreach ($close_femily_list as $itemObj) { 
                              ?>
                           <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $itemObj->name; ?></td>
                              <td><?php echo $itemObj->contact_number; ?></td>
                              <td><?php echo $itemObj->contact_email; ?></td>
                              <td><?php echo $itemObj->country_name; ?></td>
                              <td><?php echo $itemObj->relationship; ?></td>
                              <td><?php echo ($itemObj->visa_status ==1)?'Valid':'Expired'; ?></td>
                              <td><?php echo ($itemObj->they_help ==1)?'Yes':'No'; ?></td>
                              <td><?php echo $itemObj->created_date; ?></td>
                           </tr>
                           <?php $sl++; }} ?>
                        </tbody>
                     </table>
                     <br>
                     <center>
                        <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewMember" class="btn btn-primary">Add new</a></h5>
                     </center>
                     <br>              
                  </div>
               </div>
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
                                 Enquiry Details: 
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
               <div class="tab-pane" id="contacts">
                  <hr>
                  <div class="row">
                     <table id="dataTable" class="table table-responsive-sm" style="background: #fff;">
                        <thead class="thead-light">
                           <tr>
                              <th>#</th>
                              <th style="width: 20%;">Designation</th>
                              <th style="width: 20%;">Name</th>
                              <th style="width: 20%;">Contact Number</th>
                              <th style="width: 20%;">Email ID</th>
                              <th style="width: 20%;">Other Detail</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody id="tblDataContact">
                           <?php $sl = 1;
                              if(!empty($all_contact_list)){
                              foreach ($all_contact_list as $contact) { 
                              ?>
                           <tr>
                              <td>#</td>
                              <td ><?php echo $contact->designation; ?></td>
                              <td ><?php echo $contact->c_name; ?></td>
                              <td ><?php echo $contact->contact_number; ?></td>
                              <td ><?php echo $contact->emailid; ?></td>
                              <td ><?php echo $contact->other_detail; ?></td>
                              <td></td>
                           </tr>
                           <?php $sl++; }} ?>
                        </tbody>
                     </table>
                     <br>
                     <center>
                        <h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewContact" class="btn btn-primary">Add new contact</a></h5>
                     </center>
                     <br>              
                  </div>
               </div>
               <?php
               if(!empty($tab_list)){
                foreach ($tab_list as $key => $value) { ?>
                  <div class="tab-pane" id="<?=str_replace(' ', '_', $value['title'])?>">
                  <?php
                  if ($value['id'] != 1) {
                    echo tab_content($value['id'],$this->session->companey_id,$enquiry_id); 
                  }
                  ?>
                  </div>
                  <?php
                }
              }
              ?>
			  <?php include('tab_payment.php'); ?>
			  <?php include('tab_aggriment.php'); ?>
            </div>
      </div>
      </div>
      <div class="col-md-3 col-height">        
         
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
 <div id="createnewKyc" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New KYC</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('lead/addnewkyc/'.$details->Enquery_id,'class="form-inner"') ?>
               <div class="form-group col-md-6">
                  <label>Document Name</label>
                  <input class="form-control" id="doc_name" name="doc_name" placeholder="Document Name"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Document No.</label>
                  <input class="form-control" id="doc_number" name="doc_number" placeholder="Document No."  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Upload File</label>
                  <input class="form-control" name="doc_file" id="doc_file" placeholder="Upload File"  type="file" >
               </div>
                <div class="form-group col-md-6">
                  <label>Valid Up To</label>
                  <input class="datepicker form-control" id="doc_validity" name="doc_validity" placeholder="Valid Up To"  type="text" >
               </div>
                <input type="hidden" id="kyc_unique_number" name="unique_number" value="<?php echo $details->Enquery_id;?>">
                <input type="hidden" id="kyc_enquiry_id" name="kyc_enquiry_id" value="<?php echo $details->enquiry_id;?>">
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
                  </div>
               </div>
               <?php echo form_close()?>
            </div>
         </div>
      </div>
   </div>
</div>

<div id="createnewWork" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Work History</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('lead/addnewwork/'.$details->Enquery_id,'class="form-inner"') ?>
               <div class="form-group col-md-6">
                  <label>Company Name</label>
                  <input class="form-control" id="company" name="company" placeholder="Company Name"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Designation</label>
                  <input class="form-control" id="designation" name="designation" placeholder="Designation"  type="text"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>Start Date</label>
                  <input class="datepicker form-control" id="start_date" name="start_date" placeholder="Start Date"  type="text"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>End Date</label>
                  <input class="datepicker form-control" id="end_date" name="end_date" placeholder="End Date"  type="text">
               </div>
                <div class="form-group col-md-6">
                  <label>Current CTC <small>(In Lac)</small></label>
                  <input class="form-control" id="current_ctc" name="current_ctc" placeholder="Current CTC"  type="text">
               </div>
                <input type="hidden" id="work_unique_number" name="work_unique_number" value="<?php echo $details->Enquery_id;?>">
                <input type="hidden" id="work_enquiry_id" name="work_enquiry_id" value="<?php echo $details->enquiry_id;?>">
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
                  </div>
               </div>
               <?php echo form_close()?>
            </div>
         </div>
      </div>
   </div>

</div>
<button type="button" data-toggle="modal" data-target="#edit-institute-frm" id='institute_modal_btn' style="visibility: hidden;">  
</button>
<div id="edit-institute-frm" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Institute</h4>
         </div>
         <div class="modal-body" id="edit-institute-content">
            
         </div>
      </div>
   </div>


</div>
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
<div id="createnewEducation" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Education</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('lead/addnewedu/'.$details->Enquery_id,'class="form-inner"') ?>
               <div class="form-group col-md-6">
                  <label>Title of Education</label>
                  <input class="form-control" id="title" name="title" placeholder="Title of Education"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>University Name</label>
                  <input class="form-control" id="university" name="university" placeholder="University Name"  type="text"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>Year of Passing</label>
                  <input class="form-control" id="passing_year" name="passing_year" placeholder="Year of Passing"  type="text"  required>
                </div>
                <input type="hidden" id="edu_unique_number" name="edu_unique_number" value="<?php echo $details->Enquery_id;?>">
                <input type="hidden" id="edu_enquiry_id" name="edu_enquiry_id" value="<?php echo $details->enquiry_id;?>">
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
                  </div>
               </div>
               <?php echo form_close()?>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="createnewSprofile" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Social Profile</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('lead/addnewsprof/'.$details->Enquery_id,'class="form-inner"') ?>
               <div class="form-group col-md-6">
                  <label>Name of Social Media</label>
                  <input class="form-control" id="title" name="title" placeholder="Name of Social Media"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Profile URL</label>
                  <input class="form-control" id="profile" name="profile" placeholder="Profile URL"  type="text"  required>
               </div>
                <input type="hidden" id="sprof_unique_number" name="sprof_unique_number" value="<?php echo $details->Enquery_id;?>">
                <input type="hidden" id="sprof_enquiry_id" name="sprof_enquiry_id" value="<?php echo $details->enquiry_id;?>">
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
                  </div>
               </div>
               <?php echo form_close()?>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="createnewTravel" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Travel History</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('lead/addnewtravel/'.$details->Enquery_id,'class="form-inner"') ?>
               <div class="form-group col-md-6">
                  <label>County</label>
                  <select name="country_id" class="form-control">
                    <?php foreach($all_country_list as $product){?>
                    <option value="<?=$product->id_c ?>"><?=$product->country_name ?></option>
                    <?php } ?>
                 </select>
               </div>                
               <div class="form-group col-md-6">
                  <label>Travel Date</label>
                  <input class="datepicker form-control" id="travel_date" name="travel_date" placeholder="Travel Date"  type="date" style="padding-top:0px;"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>Visa Type</label>
                  <input class="form-control" id="visa_type" name="visa_type" placeholder="Visa Type"  type="text"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>From Date</label>
                  <input class="datepicker form-control" id="dfrom_date" name="dfrom_date" placeholder="From Date"  type="date" style="padding-top:0px;"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>To Date</label>
                  <input class="datepicker form-control" id="dto_date" name="dto_date" placeholder="To Date"  type="date" style="padding-top:0px;"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>Is Rejected</label>
                  <select name="is_rejected" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                  </select>
               </div>
                <div class="form-group col-md-6">
                  <label>Reject Remark</label>
                  <input class="form-control" id="reject_reason" name="reject_reason" placeholder="Reject Remark"  type="text">
               </div>
                <input type="hidden" id="travel_unique_number" name="travel_unique_number" value="<?php echo $details->Enquery_id;?>">
                <input type="hidden" id="travel_enquiry_id" name="travel_enquiry_id" value="<?php echo $details->enquiry_id;?>">
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
                  </div>
               </div>
               <?php echo form_close()?>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="createnewMember" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Member</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('lead/addnewmember/'.$details->Enquery_id,'class="form-inner"') ?>
               <div class="form-group col-md-6">
                  <label>County</label>
                  <select name="country_id" class="form-control">
                    <?php foreach($all_country_list as $product){?>
                    <option value="<?=$product->id_c ?>"><?=$product->country_name ?></option>
                    <?php } ?>
                 </select>
               </div>
                <div class="form-group col-md-6">
                  <label>Name</label>
                  <input class="form-control" id="name" name="name" placeholder="Name"  type="text"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>Contact Number</label>
                  <input class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number"  type="text"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>Contact Email</label>
                  <input class="form-control" id="contact_email" name="contact_email" placeholder="Contact Email"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Relationship</label>
                  <input class="form-control" id="relationship" name="relationship" placeholder="Relationship"  type="text"  required>
               </div>
                <div class="form-group col-md-6">
                  <label>Visa Type</label>
                  <select name="visa_status" class="form-control">
                    <option value="1" selected>Valid</option>
                    <option value="0">Expired</option>
                  </select>
               </div>
               <div class="form-group col-md-6">
                  <label>They Help</label>
                  <select name="they_help" class="form-control">
                    <option value="0">No</option>
                    <option value="1" selected>Yes</option>
                  </select>
               </div>
                <input type="hidden" id="mem_unique_number" name="mem_unique_number" value="<?php echo $details->Enquery_id;?>">
                <input type="hidden" id="mem_enquiry_id" name="mem_enquiry_id" value="<?php echo $details->enquiry_id;?>">
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Submit" class="btn btn-primary"  name="Submit">
                  </div>
               </div>
               <?php echo form_close()?>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="createTicket<?php echo $details->enquiry_id;?>" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Create New Ticket</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('ticket/addticket','class="form-inner"') ?> 
               <input class="form-control" name="clientid"  type="hidden" value="<?php echo $details->enquiry_id; ?>" required>
               <div class="form-group col-md-6">
                  <label>Name</label>
                  <input class="form-control" name="name" placeholder="Name"  type="text" value="<?php echo $details->name; ?>" required>
               </div>
               <div class="form-group col-md-6">
                  <label>Mobile No.</label>
                  <input class="form-control" name="mobile" placeholder="Mobile No."  type="text" value="<?php echo $details->phone; ?>"  maxlength='10' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
               </div>
               <div class="form-group col-md-6">
                  <label>Email</label>
                  <input class="form-control" name="email" placeholder="Email"  type="text" value="<?php echo $details->email; ?>">
               </div>
               <div class="form-group col-md-6">
                  <label>Address</label>
                  <input class="form-control" name="address" placeholder="Address"  type="text" value="<?php echo $details->address; ?>" required>
               </div>
               <div class="form-group col-md-6">
                  <label>Product</label>
                  <input class="form-control" name="product" placeholder="Product"  type="text" value="" required>
               </div>
               <div class="form-group col-md-6">
                  <label>Problem</label>
                  <select class="form-control" name="problem">
                     <option></option>
                     <?php foreach ($problems as $problem) { ?>
                     <option value="<?php echo $problem->tp_id; ?>"><?php echo $problem->problem_name; ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group col-md-6">
                  <label>Priority</label>
                  <select class="form-control" name="priority">
                     <option></option>
                     <?php foreach ($ticketpriority as $priority) { ?>
                     <option value="<?php echo $priority->priority_id; ?>"><?php echo $priority->priority_name; ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group col-md-6">
                  <label>Source</label>
                  <select class="form-control" name="source">
                     <option></option>
                     <?php foreach ($ticketsource as $tsource) { ?>
                     <option value="<?php echo $tsource->ts_id; ?>"><?php echo $tsource->ticket_source; ?></option>
                     <?php } ?>
                  </select>
               </div>
               <div class="form-group col-md-6">
                  <label>Due Date</label>
                  <input class="form-control" name="due_date" placeholder="Due Date"  type="date" value="" required>
               </div>
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Add Ticket" class="btn btn-primary"  name="Add Ticket">
                  </div>
               </div>
               <?php echo form_close()?>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<div id="createnewContact" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Create New Contact</h4>
         </div>
         <div class="modal-body">
            <div class="row" >
               <?php echo form_open_multipart('client/create_newcontact/'.$details->enquiry_id.'/'.$details->Enquery_id,'class="form-inner"') ?> 
               <div class="form-group col-md-6">
                  <label>Designation</label>
                  <input class="form-control" name="designation" placeholder="Designation"  type="text" required>
               </div>
               <div class="form-group col-md-6">
                  <label>Name</label>
                  <input class="form-control" name="name" placeholder="Contact Name"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Contact No.</label>
                  <input class="form-control" name="mobileno" placeholder="Mobile No." maxlength="10"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Email</label>
                  <input class="form-control" name="email" placeholder="Email"  type="text"  required>
               </div>
               <div class="form-group col-md-12">
                  <label>Other Details</label>
                  <textarea class="form-control" name="otherdetails" rows="8"></textarea>
               </div>
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Add Contact" class="btn btn-primary"  name="Add Contact">
                  </div>
               </div>
               <?php echo form_close()?>
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
                  <textarea class="form-control"   name="task_remark" placeholder="Conversaion Details"> 
                  </textarea>
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
                  <h4 class="modal-title">Enter info and Move to lead</h4>
               </div>
               <div class="modal-body">
                  <!--<form method="post" action="">-->
                  <!--<?php //echo form_open_multipart('enquiry/move_to_lead','class="form-inner"') ?>   -->
                  <div class="row">
                     <div class="form-group col-sm-6">  
                        <label>Expected Closer Date <i class="text-danger">*</i></label>                  
                        <input class="form-control date2"  name="expected_date" type="text" placeholder="Expected Closer Date" readonly required>                
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
                           <option value="">-- Select Lead Stage --</option>
                              <?php foreach($all_estage_lists as $single){                               
                              $id=$single->lead_stage;                              
                              }
                              ?>
                              <?php foreach ($all_stage_lists as $stage) {  ?>
                              <option value="<?= $stage->stg_id?>" <?php if ($stage->stg_id == $id) {echo 'selected';}?>><?php echo $stage->lead_stage_name; ?></option>
                              <?php } ?>                                        
                        </select>
                     </div>
                     <div class="form-group col-md-6">
                        <label><?php echo display('lead_description') ?></label>                  
                        <select class="form-control" name="lead_description" id="lead_description1">
                           <option value="">Lead Description</option>
                          <?php foreach($all_description_lists as $discription){ ?>
                                   
                                   <option value="<?php echo $discription->id; ?>"><?php echo $discription->description; ?></option>
                                   <?php } ?>                                             
                        </select>
                     </div>
                     <div class="form-group col-sm-12">  
                        <label><?php echo display('comments') ?></label>                  
                        <input class="form-control" id="LastCommentGen" name="comment" type="text" placeholder="Enter comment">                
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
<div id="updatedetails<?php echo $enquiry->enquiry_id ?>" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update Enquiry Details</h4>
         </div>
         <div class="modal-body">
            <?php echo form_open_multipart('enquiry/view/'.$enquiry->enquiry_id,'class="form-inner"') ?>
             <input type="hidden" name="enqCode" value="<?php echo $enquiry->Enquery_id ?>">
            <div class="row">
               <div class="form-group col-sm-6">
                  <label>First Name <i class="text-danger">*</i> </label>
                  <div class = "input-group">
                     <span class = "input-group-addon" style="padding:0px !important;border:0px !important;width:28%;">
                        <select class="form-control" name="name_prefix">
                           <?php foreach($name_prefix as $n_prefix){?>
                           <option value="<?= $n_prefix->prefix ?>" <?php if($n_prefix->prefix==$enquiry->name_prefix){ echo 'selected';} ?>><?= $n_prefix->prefix ?></option>
                           <?php } ?>
                        </select>
                     </span>
                     <input class="form-control" name="enquirername" type="text" value="<?php echo $enquiry->name ?>" placeholder="Enter First Name" style="width:100%;" required=""/>
                  </div>
               </div>
               <div class="form-group col-sm-6"> 
                  <label>Last Name <i class="text-danger">*</i></label>
                  <input class="form-control" value="<?php echo $enquiry->lastname ?>" name="lastname" type="text" placeholder="Last Name" >  
               </div>
               <div class="form-group col-sm-6"> 
                  <label><?php echo display('mobile') ?></label>
                  <input class="form-control" name="mobileno" type="text" maxlength='10' value="<?php echo $enquiry->phone ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" >  
               </div>
               <div class="form-group col-sm-6"> 
                  <label><?php echo display('email') ?></label>
                  <input class="form-control" name="email" type="email" value="<?php echo $enquiry->email ?>">  
               </div>

            <div class="form-group col-sm-6">
               <label><?php echo display('lead_source') ?></label>
               <select class="form-control" name="lead_source">
                  <option value=""><?php echo display('lead_source') ?></option>
                  <?php foreach ($leadsource as $post){ ?>
                  <option value="<?= $post->lsid?>" <?php if($enquiry->enquiry_source==$post->lsid){echo 'selected';}?>><?= $post->lead_name?></option>
                  <?php } ?>
               </select>
            </div>   
            <div class="form-group col-sm-6">
              <label><?php echo display('company_name') ?> <i class="text-danger">*</i></label>
              <input class="form-control" name="company" type="company" value="<?php echo $enquiry->company; ?>"> 
            </div>
           <div class="form-group col-sm-6">
              <label><?php echo display('address') ?> <i class="text-danger">*</i></label>
              <input class="form-control" name="address" type="address" value="<?php echo $enquiry->address; ?>">
           </div>

            <div class="form-group col-sm-12"> 
            <label>Remarks</label>
            <textarea class="form-control" rows="3" id="remarks"  name="enquiry" placeholder="Remarks"><?php  echo set_value('remarks');?><?php echo $enquiry->enquiry?></textarea>
            </div>
               <br>
               <div id="task_create1" style="display:none;">
                  <div class="form-group col-md-6">  
                     <label>Task Detail</label>                  
                     <input class="form-control"  name="task_detail" type="text" placeholder="Enter Task Details">                
                  </div>
                  <div class="form-group col-md-6">  
                     <label>Task Date</label>                  
                     <input class="form-control date" name="task_date" type="text" placeholder="Enter Task Date" readonly>                
                  </div>
               </div>
               <div class="col-md-6"  id="save_button">
                  <div class="row">
                     <div class="col-md-12">                                                
                        <button class="btn btn-primary" type="submit" >Save</button>            
                     </div>
                  </div>
               </div>
               <div class="form-group col-md-6" id="curcit_add3" style="display:none;">
                  <div class="col-12" >
                     <div class="row">
                        <div class="col-md-6">                                                
                           <button class="btn btn-primary" type="submit" >Create Circuit Sheet</button>            
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group col-md-6" id="add_po3" style="display:none;">
                  <div class="col-12" >
                     <div class="row">
                        <div class="col-md-12">                                                
                           <button class="btn btn-primary" type="submit" >Attached Po</button>            
                        </div>
                     </div>
                  </div>
               </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
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
               <textarea class="form-control summernote" name="message_name"  rows="10" id="template_message"></textarea>  
            </div>
         </div>
         <div class="col-md-12">
            <input type="hidden"  id="mesge_type" name="mesge_type">
            <input type="hidden" id="mobile" name="mobile" value="<?php echo $enquiry->phone ?>">
            <input type="hidden" id="mail" name="mail" value="<?php echo $enquiry->email ?>">
            <button class="btn btn-primary" onclick="send_sms()" type="button">Send</button>            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
      </form>
   </div>
</div>
<!---------------------------- DROP Enquiry -------------------------------->
<div id="dropEnquiry" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <?php
            if ($enquiry->status == 1) {
              $drop_title = 'Drop Enquiry';
            }else if ($enquiry->status == 2) {
              $drop_title = 'Drop Lead';
            }else if ($enquiry->status == 3) {
              $drop_title = 'Drop Client';
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
                     <?php foreach ($drops as $drop) {   ?>
                     <option value="<?php echo $drop->d_id; ?>"><?php echo $drop->drop_reason; ?></option>
                     <?php } ?>                                             
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
<!----------------------------------------------------------chat section ---------------------------------------------------------->
		 
		 <!--chat start---->

<style>
.chat-window{
    bottom:0;
    position:fixed;

    right:0;
    margin-left:10px;z-index:9999999;
}
.chat-window > div > .panel{
    border-radius: 5px 5px 0 0;
}
.icon_minim{
    padding:2px 10px;
}
.msg_container_base{background-color:#fff;
  background: #e5e5e5;
  margin: 0;
  padding: 0 10px 10px;
  max-height:300px;
  overflow-x:hidden;
}
.top-bar {
  background: #666;
  color: white;
  padding: 10px;
  position: relative;
  overflow: hidden;
}
.msg_receive{
    padding-left:0;
    margin-left:0;
}
.msg_sent{
    padding-bottom:20px !important;
    margin-right:0;
}
.messages {
  background: white;
  padding: 10px;
  border-radius: 2px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  max-width:100%;
}
.messages > p {
    font-size: 13px;
    margin: 0 0 0.2rem 0;
  }
.messages > time {
    font-size: 11px;
    color: #ccc;
}
.msg_container {
    padding: 10px;
    overflow: hidden;
    display: flex;
}

.avatar {
    position: relative;
}
.base_receive > .avatar:after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border: 5px solid #FFF;
    border-left-color: rgba(0, 0, 0, 0);
    border-bottom-color: rgba(0, 0, 0, 0);
}

.base_sent {
  justify-content: flex-end;
  align-items: flex-end;
}
.base_sent > .avatar:after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 0;
    border: 5px solid white;
    border-right-color: transparent;
    border-top-color: transparent;
    box-shadow: 1px 1px 2px rgba(black, 0.2); // not quite perfect but close
}

.msg_sent > time{
    float: right;
}



.msg_container_base::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

.btn-group.dropup{
    position:fixed;
    left:0px;
    bottom:0;
}
</style>

<div class="container">
    <div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="display: none;">
        <div class="col-xs-12 col-md-12">
        	<div class="panel panel-default">
                <div class="panel-heading top-bar">
                    <div class="col-md-8 col-xs-8">
                        <h5  style="font-size:9px;"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;<?php echo  $enquiry->name." ".$enquiry->lastname ?></h5>
                    </div>
                    <div class="col-md-4 col-xs-4" style="text-align: right;">
                        <a href="#"><span id="minim_chat_window" class="glyphicon glyphicon-minus icon_minim"></span></a>
                        <a href="#"><span class="glyphicon glyphicon-remove icon_close" data-id="chat_window_1"></span></a>
                    </div>
                </div>
                <div class="panel-body msg_container_base" id="chat_window">
				<?php
               				
				foreach(json_decode($get_message) as $msg){
				if($msg->type!='OUT'){?>
				<div class="row msg_container base_sent">
                        <div class="col-md-2 col-xs-2 avatar">
                            <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive ">
                        </div>
                        <div class="col-md-10 col-xs-10">
                            <div class="messages msg_receive">
                                <p><?php echo $msg->text;  ?></p>
                                <time datetime="2009-11-13T20:00"><?php echo $msg->creation_date; ?></time>
                            </div>
                        </div>
                    </div>
					
				<?php }else{ ?>
				 <div class="row msg_container base_receive ">
                        <div class="col-md-10 col-xs-10">
                            <div class="messages msg_sent">
                                <p><?php echo $msg->text;  ?></p>
                                <time datetime="2009-11-13T20:00"><?php echo $msg->creation_date; ?></time>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-2 avatar">
                            <img src="http://www.bitrebels.com/wp-content/uploads/2011/02/Original-Facebook-Geek-Profile-Avatar-1.jpg" class=" img-responsive ">
                        </div>
                    </div>
				
				<?php } }?>
                    

                 </div>
				 <form   id="chat_start">
                <div class="panel-footer">
                    <div class="input-group">
					
					 <input value="<?php echo $enquiry->phone; ?>" name="phone" type="hidden"  placeholder="Write your message here..." />
                      <input id="btn-input" type="text" name="message" class="form-control input-sm chat_input" placeholder="Write your message here..." />
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-sm" id="send_message" onclick="chat_start()" o>Send</button>
                        </span>
				   
                    </div>
                </div>
				 </form>
    		</div>
        </div>
    </div>
    
    <div class="btn-group dropup">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-cog"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#" id="new_chat"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>
            <li><a href="#"><span class="glyphicon glyphicon-list"></span> Ver outras</a></li>
            <li><a href="#"><span class="glyphicon glyphicon-remove"></span> Fechar Tudo</a></li>
            <li class="divider"></li>
            <li><a href="#"><span class="glyphicon glyphicon-eye-close"></span> Invisivel</a></li>
        </ul>
    </div>
</div>



<div id="task_edit" class="modal fade in" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content " id="update-task-content">
      </div>
   </div>
</div>

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
       var html='<div class="form-group col-sm-4"><label>Other No </label><input class="form-control"  name="other_no[]" type="text" placeholder="Other Number"   ></div>';
        $('#'+add_more_phone).append(html);          
    }
  function delete_institute(id){
    //alert(id);    
    var url = "<?=base_url().'Enquiry/delete_institute'?>";
    $.ajax({
         type: "POST",
         url: url,
         data: {inst_id:id}, // serializes the form's elements.
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

  // this is the id of the form
$("#add_institute_form").submit(function(e) {
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
    }else{
      $("#email_subject").show();
      $("#email_subject").prev().show();
    }
   $.ajax({
   type: 'POST',
   url: '<?php echo base_url();?>message/get_templates/'+SMS,
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
       $.ajax({            
           type: 'POST',
           url: '<?php echo base_url();?>message/send_sms',
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
       
       function  chat_start(){
       $.ajax({            
           type: 'POST',
           url: '<?php echo base_url();?>message/chat_start',
           data: $('#chat_start').serialize()
           })
           .done(function(data){               
               alert(data);
              location.reload();
           })
           .fail(function() {
           alert( "fail!" );
       });  
      }
       
       $(document).ready(function(){
       $('#chat_window').animate({
        scrollTop: $('#chat_window')[0].scrollHeight}, 2000);
       });

    document.getElementById('chat_start').addEventListener('keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
		      $.ajax({
           type: 'POST',
           url: '<?php echo base_url();?>message/chat_start',
           data: $('#chat_start').serialize()
           })
           .done(function(data){               
              alert(data);
              location.reload();
           })
           .fail(function() {
           alert( "fail!" );
       });
      }
    });
       
   $( "#service1" ).click(function() {     
       if($('#another-element1:visible').length)
           $('#another-element1').hide();
       else
           $('#another-element1').show();        
   });   
   $( "#task_create_div1" ).click(function() {     
       if($('#task_create1:visible').length)
           $('#task_create1').hide();
       else
           $('#task_create1').show();        
   });   
   function check_stage3(id){
       if(id==5){
          document.getElementById('curcit_add3').style.display='block'; 
           document.getElementById('add_po3').style.display='none';
       }else if(id==8){
           document.getElementById('add_po3').style.display='block'; 
           document.getElementById('curcit_add3').style.display='none';
       }else{
          document.getElementById('add_po3').style.display='none';
         document.getElementById('curcit_add3').style.display='none';
       }       
       }
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
      /* dayClick:function(date,isEvent,view,resourseobj){
         $('td').dblclick(function(){             
              $("#range").attr("style", "display:block");           
        });         
                     $.ajax({
                      type: 'POST',
                      url: '<?php echo base_url();?>enquiry/search_comment_and_task/'+date.format()+'/<?php echo $enquiry->Enquery_id ?>',
                     })
                     .done(function(data){
                          $("#comment_div").attr("style", "display:none");
                         $("#comment_div1").attr("style", "display:block");
                        
                         $("#task_div").attr("style", "display:none");
                         $("#task_div1").attr("style", "display:block");
                         $("#comment_div1").html(data.details);
                         $("#task_div1").html(data.details1);
                     })
                     .fail(function() {
                     alert( "fail!" );                     
                     });        
       }*/  
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
        /* $('td').dblclick(function(){           
        }); 
         
         ser_date = date.format();

                     $.ajax({
                      type: 'POST',
                      url: '<?php echo base_url();?>task/search_comment_and_task/'+ser_date,
                     })
                     .done(function(data){
                       
                         $("#task_div1").html(data);
                     })*/
   
        
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
             var obj = JSON.parse(data);
              $('#templates option[value='+tmpl_id+']').attr("selected", "selected");
              $(".summernote").summernote("code", obj.template_content);
              $("#email_subject").val(obj.mail_subject);
             //$("#template_message").html(obj.template_content);
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
  
  function open_institute_modal(id){
    //alert(id);
    var enquiry_id = "<?=$enquiry->Enquery_id?>";
    //alert(enquiry_id);
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>enquiry/get_update_enquery_institute_content',
        data: {id:id,Enquiry_id:enquiry_id},            
        success:function(data){              
          $("#edit-institute-content").html(data);
          $("#institute_modal_btn").click();
        }
    });
  }
   function find_description(f=0) { 

           if(f==0){
            var l_stage = $("#lead_stage_change").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/select_des_by_stage',
            data: {lead_stage:l_stage},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';
        html +='<option value="new" style="">New</option>';
        html +='<option value="updt" style="">Update</option>';
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].id)+'">'+(obj[i].description)+'</option>';
                }
                
                $("#lead_description").html(html);
                
            }
            
            
            });
           }

            }

  function find_description1(f=0) { 

           if(f==0){
            var l_stage = $("select[name='move_lead_stage']").val();
            //console.log('l_stage'+l_stage);
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/select_des_by_stage',
            data: {lead_stage:l_stage},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';
        html +='<option value="new" style="">New</option>';
        html +='<option value="updt" style="">Update</option>';
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
              //$("#lead_stage_change").change();
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
         //document.getElementById("otherTypev").style.display = "none"; 
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

  $(document).on('focus', '.panel-footer input.chat_input', function (e) {
      var $this = $(this);
      if ($('#minim_chat_window').hasClass('panel-collapsed')) {
          $this.parents('.panel').find('.panel-body').slideDown();
          $('#minim_chat_window').removeClass('panel-collapsed');
          $('#minim_chat_window').removeClass('glyphicon-plus').addClass('glyphicon-minus');
      }
  });

  $(document).on('click', '#new_chat', function (e) {
      var size = $( ".chat-window:last-child" ).css("margin-left");
       size_total = parseInt(size) + 400;
      alert(size_total);
      var clone = $( "#chat_window_1" ).clone().appendTo( ".container" );
      clone.css("margin-left", size_total);
  });
  $(document).on('click', '.icon_close', function (e) {
      //$(this).parent().parent().parent().parent().remove();
      $( "#chat_window_1" ).remove();
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
          console.log(obj);
            //$('#fstate option[value="'+obj.state_id+'"]').attr("selected","selected");
            $('#fstate').val(obj.state_id);
            $('#myList').select2("val", obj.state_id);

            //$('#fstate').trigger('change');
          }
        })
      } 
    });

</script>


    <?php
    if ($this->session->companey_id==51) {
    ?>

<script type="text/javascript">
    function hide_all_dependent_field(){
      $(".service_related_issue_type").hide();                       
      $(".service_related_issue_sub_type").hide();                       
      $(".detail_of_issue").hide();                       
      $(".error_coming").hide();                       
      $(".dnd_sender_id").hide();                       
      $(".issue_date").hide();                       
      $(".promotional_sms_call_date_for_dnd").hide(); 

      $(".balace_deduction_issue_type").hide();            
      $(".balance_deduction_issue_sub_type").hide();            
      $(".amount_deducted").hide();            
      $(".date_of_deduction").hide();            
      $(".waiver_required").hide();            
      $(".blacklist_consent").hide(); 

      $(".recharge_issue_type").hide();
      $(".recharge_issue_sub_type").hide();
      $(".recharge_denomination").hide();
      $(".mode_of_recharge").hide();
      $(".date_of_recharge").hide(); 


      $(".network_issue_type").hide();
      $(".network_issue_sub_type").hide();
      $(".technology").hide();     

      $(".alt_number").hide();            
      $(".sim_service_issue_type").hide();            
      $(".sim_service_issue_sub_type").hide();            
      $(".date_of_simex").hide();            
      $(".vms_name").hide();     

      
      $(".self_help_issue_type").hide();            
      $(".self_help_issue_sub_type").hide();            
      $(".date_of_problem").hide();

      $(".other-issue-type").hide();
      $(".voc").hide();
    }

    function show_dependent_field(service){
      
      hide_all_dependent_field();

      if (service==103) {
        $(".network_issue_type").show();
        $(".network_issue_sub_type").show();
        $(".technology").show();


      }else if (service==104) {
        $(".recharge_issue_type").show();
        $(".recharge_issue_sub_type").show();
        $(".recharge_denomination").show();
        $(".mode_of_recharge").show();
        $(".date_of_recharge").show(); 

       
      }else if (service==105) {
        $(".balace_deduction_issue_type").show();            
        $(".balance_deduction_issue_sub_type").show();            
        $(".amount_deducted").show();            
        $(".date_of_deduction").show();            
        $(".waiver_required").show();            
        $(".blacklist_consent").show(); 
        
      }else if (service==106) {
        $(".alt_number").show();            
        $(".sim_service_issue_type").show();            
        $(".sim_service_issue_sub_type").show();            
        $(".date_of_simex").show();            
        $(".vms_name").show();   


      }else if (service==107) {
        $(".self_help_issue_type").show();            
        $(".self_help_issue_sub_type").show();            
        $(".date_of_problem").show(); 

      }else if (service==108) {
        $(".service_related_issue_type").show();                       
        $(".service_related_issue_sub_type").show();                       
        $(".detail_of_issue").show();                       
        $(".error_coming").show();                       
        $(".dnd_sender_id").show();                       
        $(".issue_date").show();                       
        $(".promotional_sms_call_date_for_dnd").show(); 
      }
      else if (service==110) {
        $(".other-issue-type").show();
        $(".voc").show();
      }

    }
      
  $("#sub_source").on('change',function(){
    var service  = $("#sub_source").val();
    show_dependent_field(service);
  });
  var service  = $("#sub_source").val();
  show_dependent_field(service);

</script>
<?php
}else if($this->session->companey_id == 29){ ?>
  <script type="text/javascript">
      function hide_all_dependent_field(){
        $(".desired-loan-amount").hide();
        $(".net-monthly-income").hide();
        $(".bank-name").hide();
        $(".personal-details").hide();        

        $(".gross-annual-turnover").hide();
        $(".net-profit-after-tax").hide();
        
        $(".company-name").hide();
        $(".company-type").hide();
        $(".occupation-type").hide();
        $(".credit-card-name").hide();        

        $(".profession").hide();
        $(".years-in-occupation").hide();
        $(".years-in-occupation").hide();
        $(".annual-income").hide();

      }

      function show_dependent_field(service){        
        hide_all_dependent_field();
        if (service == 83) {
          $(".desired-loan-amount").show();
          $(".net-monthly-income").show();
          $(".bank-name").show();
          $(".personal-details").show();
        
        }else if (service == 84) {
          $(".desired-loan-amount").show();          
          $(".gross-annual-turnover").show();
          $(".net-profit-after-tax").show();
          $(".company-name").show();
          $(".company-type").show();
          $(".bank-name").show();

        }else if (service == 111) {
          $(".occupation-type").show();
          $(".net-monthly-income").show();          
          $(".bank-name").show();
          $(".credit-card-name").show();

        }else if (service == 112) {
          $(".desired-loan-amount").show();          
          $(".profession").show();
          $(".years-in-occupation").show();
          $(".bank-name").show();   
          $(".annual-income").show();
        }        
      }
        
    $("#sub_source").on('change',function(){
      var service  = $("#sub_source").val();
      show_dependent_field(service);
    });  
    var service  = $("#sub_source").val();
    show_dependent_field(service);
  </script>
<?php
}
?>

<script type="text/javascript">
$('div.ieltsappeard').hide();
	$('div.ieltsnappeard').hide();
	$('div.ieltsdt').hide();
	$('div.ieltslisten').hide();
	$('div.ieltsread').hide();
	$('div.ieltswrite').hide();
	$('div.ieltsspeak').hide();
	$('div.ieltsfinal').hide();
	
	$('div.pteappeard').hide();
	$('div.ptenappeard').hide();
	$('div.ptedt').hide();
	$('div.ptelisten').hide();
	$('div.pteread').hide();
	$('div.ptewrite').hide();
	$('div.ptespeak').hide();
	$('div.ptefinal').hide();
	
	
$('#ielts').change(function(){
  if($(this).prop("checked")) {
    $('div.ieltsappeard').show();

  } else {
    $('div.ieltsappeard').hide();
	$('div.ieltsnappeard').hide();
	$('div.ieltsdt').hide();
	$('div.ieltslisten').hide();
	$('div.ieltsread').hide();
	$('div.ieltswrite').hide();
	$('div.ieltsspeak').hide();
	$('div.ieltsfinal').hide();
  }
}); 
 

$("input[id=ieltsappeard]").on( "click", function() {

var test = $(this).val();
	if(test=='Appeared'){
    $('div.ieltsdt').show();
	$('div.ieltslisten').show();
	$('div.ieltsread').show();
	$('div.ieltswrite').show();
	$('div.ieltsspeak').show();
	$('div.ieltsfinal').show();
		 }else{
	$('div.ieltsdt').hide();
	$('div.ieltslisten').hide();
	$('div.ieltsread').hide();
	$('div.ieltswrite').hide();
	$('div.ieltsspeak').hide();
	$('div.ieltsfinal').hide();		 
		 }
    } );
 

$('#pte').change(function(){
  if($(this).prop("checked")) {
    $('div.pteappeard').show();

  } else {
    $('div.pteappeard').hide();
	$('div.ptenappeard').hide();
	$('div.ptedt').hide();
	$('div.ptelisten').hide();
	$('div.pteread').hide();
	$('div.ptewrite').hide();
	$('div.ptespeak').hide();
	$('div.ptefinal').hide();
  }
}); 

$("input[id=pteappeard]").on( "click", function() {

var test = $(this).val();
	if(test=='Appeared'){
    $('div.ptedt').show();
	$('div.ptelisten').show();
	$('div.pteread').show();
	$('div.ptewrite').show();
	$('div.ptespeak').show();
	$('div.ptefinal').show();
		 }else{
	$('div.ptedt').hide();
	$('div.ptelisten').hide();
	$('div.pteread').hide();
	$('div.ptewrite').hide();
	$('div.ptespeak').hide();
	$('div.ptefinal').hide();		 
		 }
    } ); 
</script>
<script>
function find_app_crs() { 
            var c_stage = $("#institute_id").val();
			//alert(c_stage);
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/select_app_by_ins',
            data: {c_course:c_stage},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].crs_id)+'">'+(obj[i].course_name)+'</option>';
                }
                
                $("#app_course").html(html);
                
            }
            
            
            });

    }

</script>
<script>
function find_app_crs() { 
            var c_stage = $("#institute_id").val();
			var l_stage = $("#p_lvl").val();
			var lg_stage = $("#p_length").val();
			var d_stage = $("#p_disc").val();
			//alert(c_stage);
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/select_app_by_ins',
            data: {c_course:c_stage,c_lvl:l_stage,c_length:lg_stage,c_disc:d_stage},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select---</option>';
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].crs_id)+'">'+(obj[i].course_name)+'</option>';
                }
                
                $("#app_course").html(html);
                
            }
            
            
            });

    }

</script>
<script>
function find_level() { 

            var l_stage = $("#p_lvl").val();
            $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>lead/select_length_lvl',
            data: {lead_stage:l_stage},
            
            success:function(data){
               // alert(data);
                var html='';
                var obj = JSON.parse(data);
                
                html +='<option value="" style="display:none">---Select length---</option>';
                for(var i=0; i <(obj.length); i++){
                    
                    html +='<option value="'+(obj[i].id)+'">'+(obj[i].length)+'</option>';
                }
                
                $("#p_length").html(html);
                
            }
            
            
            });

            }	
</script>