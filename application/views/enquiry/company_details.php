<div class="row">
  <div class="col-lg-12">
    <div style="    padding: 15px 0px 7px 15px; text-transform: capitalize;" align="center"><?=$company_name?></div>
  </div>
</div>

<style type="text/css">
  #left-btn{
    font-size: 20px;
    position: absolute;
    line-height: 40px;
    color: #283593!important;
    left:10px;
  }
   #right-btn{
    font-size: 20px;
    position: absolute;
    line-height: 40px;
    color: #283593!important;
    right:12px;
  }
  .flex-column{
    border-bottom: 1px solid #ddd;
    margin: 0px 28px;
    height: 42px;
    white-space: nowrap;
    overflow: hidden;
  }
  .nav-tabs>li {
    display: inline-block!important;
    white-space: nowrap!important;
    float: none;
  }

</style>


<div class="row" style="padding:15px;">
  <div class="col-lg-12" style="padding: 0px;">
    <i class="fa fa-arrow-circle-left text-primary" onclick="tabScroll('left')" id="left-btn" style="font-size: 20px;"></i>
   <ul class="nav flex-column nav-tabs">
    <!--   <li class="nav-item active">
         <a class="nav-link" data-toggle="tab" href="#basic">Basic</a>
      </li> -->
      <li class="nav-item active">
         <a class="nav-link" data-toggle="tab" href="#deals">Deals 
           <!--  <label class="custom_badge">8</label> -->
         </a>
      </li>
      <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#visits">Visits</a>
      </li>
      <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#contacts">Contacts</a>
      </li>
      <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#accounts" onclick="load_account(1)">Clients(Stage Wise)</a>
      </li>
      <!-- <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#accounts" onclick="load_account(2)"><?=display('lead')?></a>
      </li>
      <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#accounts" onclick="load_account(3)"><?=display('client')?></a>
      </li> -->

      <?php
      // $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');                  
      // if (!empty($enquiry_separation)) 
      // { 
      //     $enquiry_separation = json_decode($enquiry_separation,true);
      //     foreach ($enquiry_separation as $key => $value)
      //     { 
      //     // echo'<li class="nav-item">
      //     //        <a class="nav-link" data-toggle="tab" href="#'.strtolower(str_replace(' ','_',$value['title'])).'" onclick="load_account('.$key.')">'.$value['title'].'</a>
      //     //     </li>';

      //       echo'<li class="nav-item">
      //            <a class="nav-link" data-toggle="tab" href="#accounts" onclick="load_account('.$key.')">'.$value['title'].'</a>
      //         </li>';
      //     }
      // }
      ?>

      <li class="nav-item">
         <a class="nav-link" data-toggle="tab" href="#tickets"><?=display('ticket')?></a>
      </li>
      <i class="fa fa-arrow-circle-right text-primary" onclick="tabScroll('right')" id="right-btn" style="font-size: 20px;"></i>
   </ul>

<script type="text/javascript">
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

  <!-- Tab panes -->
  <div class="tab-content">

      <!-- <div id="basic" class="container tab-pane"><br>
         
      </div> -->

      <div id="deals" class="container tab-pane active"><br>
        <table id="deals_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
             <thead class="thead-light">
               <tr>                              
                  <th>S.N.</th>
                  <th id="th-1">Name</th>
                  <th id="th-21">Company</th>
                  <th id="th-22">Client Name</th>
                  <th id="th-3">Business Type</th>
                  <th id="th-4">Booking Type</th>
                  <th id="th-18">Create Date</th>
                  <th id="th-19">Status</th>
                  <th id="th-20" style="width: 100px;">Action</th>
               </tr>
            </thead>
              <tbody>
             </tbody>
          </table>
      </div>

      <div id="visits" class="container tab-pane fade"><br>
        <table id="visit_table" class="table table-bordered table-hover " >
              <thead>
                <tr>
                  <th width="7%">S. No.</th>
                  <th id="th-1" width="15%">Visit Date</th>
                  <th id="th-2" width="15%">Visit Time</th>
                  <th id="th-13" width="15%">Purpose of meeting</th>
                  <th id="th-3" >Name</th>
                  <th id="th-10">Company Name</th>
                  <th id="th-14">Client Name</th>
                  <th id="th-15">Contact Person</th>
                  <th id="th-16">Start Location</th>
                  <th id="th-17">End Location</th>
                  <th id="th-4">Shortest Distance</th>
                  <th id="th-5">Actual Distancee</th>
                  <th id="th-6">Rating</th>
                  <th id="th-11" >Difference (%)</th>
                  <th >Travel Expense</th>
                  <th>Other Expense</th>
                  <th>Total Expense</th>
                  <th>Expense Staus</th>
                  <th id="th-9">Action</th>
                </tr>
              </thead>
              <tbody>
             </tbody>
          </table>
      </div>

      <div id="contacts" class="container tab-pane fade"><br>
        <table id="contactTable" class="table table-bordered table-response">
          <thead>                 
                 <tr>
                    <th>&nbsp; # &nbsp;</th>
                    <th id="th-1">Name</th>
                    <th id="th-2" style="width: 20%;">Company</th>
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
      </div>
      <div id="accounts" class="container tab-pane fade"><br>

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

          <table id="enq_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
            <thead>
              <tr class="bg-info table_header">
               <!--  <th class="noExport">
                  <input type='checkbox' class="checked_all1" value="check all" >
                </th> -->

                  <th></th>
                  <th>S.N</th>
             <?php if ($showall == true or in_array(1, $acolarr)) {  ?>
                  <th><?php echo display("source"); ?></th>
             <?php } ?>
              <?php if ($showall == true or in_array(16, $acolarr)) {  ?>
                  <th >Sub Source</th>
            <?php } ?>
              <?php if ($showall == true or in_array(2, $acolarr)) {  ?>
                  <th><?php echo display("company_name"); ?></th>
                  <th>Account Status</th>
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
			 <?php if ($showall == true or in_array(30, $acolarr)) {  ?>
            <th>Lead stage</th>
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

          </table>
      </div>

    <?php
    // Extra type of data other then lead enquiry client

      // $enquiry_separation  = get_sys_parameter('enquiry_separation','COMPANY_SETTING');                  
      // if (!empty($enquiry_separation)) 
      // { 
      //     $enquiry_separation = json_decode($enquiry_separation,true);
      //     foreach ($enquiry_separation as $key => $value)
      //     { 
      //       echo'<div id="'.strtolower(str_replace(' ','_',$value['title'])).'" class="container tab-pane fade">
            
      //       <br>
      //       </div>';


      //     }
      // }
      ?>


 <?php 

 $dfields = $ticket_dfields;
        $acolarr = array();
        $dacolarr = array();
        if(isset($_COOKIE["ticket_allowcols"])) {
          $showall = false;
          $acolarr  = explode(",", trim($_COOKIE["ticket_allowcols"], ","));       
        }else{          
          $showall = true;
        }         
        if(isset($_COOKIE["ticket_dallowcols"])) {
          $dshowall = false;
          $dacolarr  = explode(",", trim($_COOKIE["ticket_dallowcols"], ","));       
        }else{
          $dshowall = false;
        }       
 ?>
      <div id="tickets" class="container tab-pane fade"><br>
         <table id="ticket_table" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                    <th class="noExport sorting_disabled" style="display: none;">
                        <input type='checkbox' class="checked_all1" value="check all" >
                     </th>
                      <th>S.No.</th>
                      <?=($showall or in_array(1,$acolarr))?'<th>Ticket</th>':''?>

                      <?php
                      if($this->session->companey_id==65)
                      {
                      ?>
                        <?=($showall or in_array(15,$acolarr))?'<th>'.display('tracking_no').'</th>':''?>
                      <?php
                      }
                      ?>
                      <?=($showall or in_array(7,$acolarr))?'<th>Created By</th>':''?>
                      <?=($showall or in_array(9,$acolarr))?'<th>Date</th>':''?>
                      <?=($showall or in_array(18,$acolarr))?'<th>'.display('last_updated').'</th>':''?>                      
                      <?=($showall or in_array(2,$acolarr))?'<th>'.display('problem_for').'</th>':''?>
                      <?=($showall or in_array(3,$acolarr))?'<th>Email</th>':''?>
                      <?=($showall or in_array(4,$acolarr))?'<th>Phone</th>':''?>
                      <?=($showall or in_array(5,$acolarr))?'<th>Product</th>':''?>
                      <?=($showall or in_array(6,$acolarr))?'<th>Assign To</th>':''?>
                      <?=($showall or in_array(17,$acolarr))?'<th>Assign By</th>':''?>
                      <?=($showall or in_array(8,$acolarr))?'<th>Priority</th>':''?>
                      <?=($showall or in_array(19,$acolarr))?'<th>'.display('ticket_problem').'</th>':''?>
                      <?=($showall or in_array(10,$acolarr))?'<th>Referred By</th>':''?>
                      <?=($showall or in_array(11,$acolarr))?'<th>'.display('data_source').'</th>':''?>
                      <?=($showall or in_array(12,$acolarr))?'<th>'.display('stage').'</th>':''?>
                      <?=($showall or in_array(13,$acolarr))?'<th>Sub Stage</th>':''?>
                      <?=($showall or in_array(14,$acolarr))?'<th>'.display('ticket_remark').'</th>':''?>
                      <?=($showall or in_array(16,$acolarr))?'<th>Status</th>':''?>


                      <?php 
                      if(!empty($dacolarr) and !empty($dfields))
                      {
                        foreach($dfields as $ind => $flds)
                        {                
                          if(!empty(in_array($flds->input_id, $dacolarr )))
                          {            
                          ?><th><?php echo $flds->input_label; ?></th><?php 
                          }
                        }
                       } ?>

                    </thead>
                    <tbody>
                    </tbody>
                  </table>

      </div>

  </div>
</div>
</div>
<style type="text/css">
.custom_badge
{
   font-size: 11px;
   background: #ff0101;
   padding: 2px 6px;
   border-radius: 50%;
   color: white;
   top:-2px;
}
.nav-tabs .active a
{
   background: #283593!important;
   color:white!important;
}
.tab-pane{
  max-width: 100%;
}



</style>


<script type="text/javascript">

function update_info_status(id,status)
{
     $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>enquiry/update_info_status/?>',
            data: {id:id,status:status},
            success:function(data){
                Swal.fire({
                  title:'Saved!',
                  type:'success',
                  icon:'success',
                });
            }
        });
}

$(document).ready(function(){
//var c = getCookie('deals_allowcols');
var specific_list = "<?=!empty($specific_deals)?$specific_deals:''?>";

  $('#deals_table').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'enquiry/deals_load_data'?>",
              "type": "POST",
              "data":function(d){
                     //  var obj = $(".v_filter:input").serializeArray();

                     // d.top_filter = $("input[name=top_filter]:checked").val();
                     // d.date_from = $("input[name=d_from_date]").val();
                     // d.date_to = $("input[name=d_to_date]").val();
                     // d.enq_for = $("select[name=d_enquiry_id]").val();
                     // d.from_date = obj[0]['value'];
                     // d.from_time = '';//obj[1]["value"];
                     // d.enquiry_id =obj[2]["value"];
                     // d.rating = obj[3]["value"];
                     // d.to_date = obj[1]['value'];
                     // d.to_time = '';//obj[5]['value'];
                     d.view_all=true;
                     d.specific_list = specific_list;
                     TempData = d;
                     console.log(JSON.stringify(d));
                    return d;
              }
          },
          "drawCallback":function(settings ){
            //  update_top_filter();
          },
          columnDefs: [
                       { orderable: false, targets: -1 }
                    ]
  });



var specific_list = "<?=!empty($specific_visits)?$specific_visits:''?>";
$('#visit_table').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'enquiry/visit_load_data'?>",
              "type": "POST",
              "data":function(d){
                     //  var obj = $(".v_filter:input").serializeArray();

                     
                     // d.from_date = obj[0]['value'];
                     // d.from_time = '';//obj[1]["value"];
                     // d.enquiry_id =obj[2]["value"];
                     // d.rating = obj[3]["value"];
                     // d.to_date = obj[1]['value'];
                     // d.to_time = '';//obj[5]['value'];
                    d.view_all=true;
                    d.specific_list = specific_list;
                     console.log(JSON.stringify(d));
                    return d;
              }
          },
});




var specific_list = "<?=!empty($specific_tickets)?$specific_tickets:''?>";

var table = $('#ticket_table').DataTable({         
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
              "url": "<?=base_url().'Ticket/ticket_load_data'?>",
              "type": "POST",
              "data":function(d){
                    d.specific_list = specific_list;

                    },
              },
              <?php if(user_access(317)) { ?>
        // "lengthMenu": [[30, 60, 90, -1], [30, 60, 90, "All"]], 
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
             ] ,  <?php  } ?>               
            // drawCallback: function (settings) {   
            //   var api = this.api();
            // var $table = $(api.table().node());  
            //   console.log(settings);               
            //   console.log(table);               
            //     var info = table.page.info();
            //     returned_rows = table.rows().count();
            //     if(returned_rows == 0 || returned_rows < info.length){
            //       $('#ticket_table_next').addClass('disabled');
            //     }
            //     $('#ticket_table_previous').after('<li><a class="btn btn-secondary btn-sm" style="padding: 4px;line-height: 2;" href="javascript:void(0)">'+info.page+'</a></li>');
            // }
});





});

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
   $($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust();
});

var table = false;
var DataType = 0;
try{
function load_account(data_type)
{
  DataType = '1,2,3,4,5,6';

  var specific_list = "<?=!empty($specific_accounts)?$specific_accounts:''?>";

    if(table==false)
    {
    $('#enq_table').DataTable(
        {         
          "processing": true,
          "scrollX": true,
          // "scrollY": 520,
          // "pagingType": "simple",
          // "bInfo": false,
          "bFilter": true, 
          "bInfo": true,
          "paging": true,

          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'Enq/enq_load_data'?>",
              "type": "POST",
              "data":function(d){
                d.data_type = DataType; 
                d.specific_list = specific_list;
                return d;
              }
            
          },
          "columnDefs": [{ "orderable": false, "targets":0 }],
           // "order": [[ 1, "desc" ]],
      });
    }
    else
    {
        $('#enq_table').DataTable().ajax.reload();
    }
    table = true;
}
}catch(e){alert(e);}

</script>
<a class="dropdown-toggle" data-toggle="modal" data-target="#updt_Contact" id="open" title="Add Contact" style="display:none;"></a> 
<script type="text/javascript">
function edit_contact(t)
{
  var contact_id = $(t).data('cc-id');

  $.ajax({
        url:"<?=base_url('client/edit_contact/')?>",
        type:"post",
        data:{cc_id:contact_id,task:'view',direct_create:1},
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

$(document).ready(function(){
var c='';
var specific_list = "<?=!empty($specific_contacts)?$specific_contacts:''?>";; 
  $('#contactTable').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'client/contacts_load_data'?>",
              "type": "POST",
              "data":function(d){
                     d.view_all=true;
                     d.specific_list = specific_list;
                       if(c && c!='')
                      d.allow_cols = c;

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

<script type="text/javascript">
  $(".nav-tabs li").on('click',function(){
  $(window).trigger('resize');
  $(window).trigger('resize');
});
</script>