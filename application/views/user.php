<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script src="<?=base_url()?>/assets/summernote/summernote-bs4.min.js"></script>
<link href="<?=base_url()?>/assets/summernote/summernote-bs4.css" rel="stylesheet" />
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
            <div class="panel-heading no-print">
                <?php if(user_access(130)){ ?>
                    <div class="btn-group"> 

                        <a class="btn btn-success" href="<?php echo base_url("user/create") ?>"> <i class="fa fa-plus"></i>  <?php echo display('add_user') ?> </a>  

                    </div>
                <?php } ?>
                <div class="col-md-4 col-sm-8 col-xs-8 pull-right">  
          <div style="float: right;">     
            <div class="btn-group" role="group" aria-label="Button group">
              <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Actions">
                <i class="fa fa-sliders"></i>
              </a>  
            <div class="dropdown-menu dropdown_css" style="max-height: 400px;overflow: auto; margin-left: -131px;">
              <?php
              if(user_access('131'))
              {
              ?>
                <a  class="btn" id="saveButton" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom :1px solid #fff;">Inactive Selected</a>
				<a class="btn " data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('3','Send Email');" style="color:#000;cursor:pointer;">Send Email</a>
				<a class="btn " data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('1','Send Whatsapp');" style="color:#000;cursor:pointer;">Send Whatsapp</a>
				<a class="btn " data-target="#sendsms" data-toggle="modal"  onclick="getTemplates('4','Send Notification');" style="color:#000;cursor:pointer;">Send Notification</a>
                <?php
              }
              ?>
            </div>                                         
          </div>

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
                      <input type="checkbox" value="email" id="emailcheckbox" name="filter_checkbox"> Email</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="phone" id="phonecheckbox" name="filter_checkbox"> Phone</label>
                    </li>
					
					<li>
                      <label>
                      <input type="checkbox" value="created_by_dept" id="createdbydeptcheckbox" name="filter_checkbox"> Department</label>
                    </li>
					
					<li>
                      <label>
                      <input type="checkbox" value="sales_region" id="regioncheckbox" name="filter_checkbox"> Employee Region</label>
                    </li>
                    
                    <li>
                      <label>                    
                        <input type="checkbox" value="status" id="statuscheckbox" name="filter_checkbox"> Status
                      </label>
                    </li>
                    
                    <li class="text-center">
                      <a href="javascript:void(0)" class="btn btn-sm btn-primary " id='save_advance_filters' title="Save Filters Settings"><i class="fa fa-save"></i></a>
                    </li>                   
                </ul>                
            </div>
        </div>       
      </div>
	  
	            <!------ Filter Div ---------->
<form method="post" id="enq_filter">
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
                         
                        <div class="form-group col-md-3" id="emailfilter">
                          <label for=""><?php echo display("email"); ?></label>
                          <input type="text" name="email" class="form-control" value="<?= $filterData['email'] ?>">
                        </div>

                         <div class="form-group col-md-3" id="empfilter">
                          <label for=""><?php echo 'Name'; ?></label>
                          <input type="text" class="form-control chosen-select" name="employee" id="employee" value="<?= $filterData['employee'] ?>">   
                        </div>
                      </div>
					  
					  <div class="form-group col-md-3" id="createdbydeptfilter">
                          <label for="">Department</label>
                         <select name="createdbydept" class="form-control" id="dept_reset"> 
                          <option value="">Select</option>
                         <?php 
                          if (!empty($dept_lists)) {
                              foreach ($dept_lists as $createdbydept) { ?>
                              <option value="<?=$createdbydept->id;?>" <?php if($createdbydept->id==$filterData['createdbydept']) {echo 'selected';}?> <?php if(!empty(set_value('createdbydept'))){if (in_array($createdbydept->id,set_value('createdbydept'))) {echo 'selected';}}?> ><?=$createdbydept->dept_name;?>                               
                              </option>
                              <?php }}?>    
                         </select>                       
                        </div>
					
					<div class="form-group col-md-3" id="regionfilter">
                        <label for="">Employee Region</label> 
                        <select name="sales_region" class="form-control" id="sales_reset">
                          <option value="">Select</option>
                          <?php
                            foreach ($region_lists as $k=>$v) {  ?>
                              <option value="<?=$v->region_id;?>" <?php if(!empty($filterData['sales_region']) && $v->region_id==$filterData['sales_region']) {echo 'selected';}?>><?php echo $v->name; ?></option>
                              <?php }                             
                              ?>
                        </select>
                    </div>

                    <div class="form-row">                      
                        <div class="form-group col-md-3" id="phonefilter">
                          <label for="">Phone</label>
                         <input type="text" name="phone" class="form-control" value="<?= $filterData['phone'] ?>">                        
                        </div>

                        <div class="form-group col-md-3" id="statusfilter">
                          <label for="">Status</label>
                            <select name="status" id="status" class="form-control">
                              <option value="">--- Select Status ---</option>
                              <option value="1" <?= ($filterData['status'] == 1)?'selected':''; ?>>Active</option>
                              <option value="2" <?= ($filterData['status'] == 2)?'selected':''; ?>>Inactive</option>
                            </select>                     
                        </div>
                        
                    </div>

                      <div class="form-group col-md-3">
					    <button class="btn btn-warning btn-sm" id="reset_filterbutton" type="button" onclick="ticket_reset_filter();" style="margin: 25px 5px;">Reset</button>
                        <button class="btn btn-primary btn-sm" id="find_filterbutton" type="button" style="margin: 25px 5px;">Filter</button>						
                        <button class="btn btn-success btn-sm" id="save_filterbutton" type="button" onclick="ticket_save_filter();" style="margin: 25px 5px;">Save</button>        
                      </div>
            </div>
        </div>
    </div>   
</form>
		  
        

            <div class="panel-body">
                 <form id="inactive_all" method="POST" action="<?= base_url('user/inactive-all') ?>">   
                   
                <table class="table table-striped table-bordered" id="user_dlist2" cellspacing="0" width="100%">

                    <thead>

                        <tr>

                        <th class="noExport">
                     <input type='checkbox' class="checked_all1" value="check all"  onclick="event.stopPropagation();">
                     </th>
                            <th>Emp Id</th>
							<th>App Version</th>
                            <th><?php echo display('disolay_name') ?></th>
                            <th><?php echo display('user_function') ?></th>
							<th>Report To</th>
                            <th>Email</th>
                            <th><?php echo display('mobile') ?></th>
							<th>Employee Region</th>
							<th>Employee Area</th>
							<th>Employee Branch</th>
							<th>Grade</th>
							<th>Department</th>
                            <th><?php echo display("proccess"); ?></th>
                            <th>Start Billing Date</th> 
                            <th>Valid upto</th> 
                            <th>Last Login</th> 
                            <th><?php echo display("created_date"); ?></th>

                            <th><?php echo display('status') ?></th>

                           

                        </tr>

                    </thead>

                    
                </table>  <!-- /.table-responsive -->
                 </form>
            </div>
        </div>
    </div>
</div>

<div id="sendsms" class="modal fade " role="dialog">
  <div class="modal-dialog modal-lg">
 <form class="form-inner" method="post" id="notification_send_from" >
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
                       <input type="hidden" id="user_ids_email" name="user_ids" value="">					   
                    <button class="btn btn-success" onclick="send_sms()" type="button">Send</button>            
                 

              </div>
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</form>
  </div>
</div>

<script>
  function getTemplates(SMS,type){
	var checked = []
    $("input[name='user_ids[]']:checked").each(function ()
      {
          checked.push(parseInt($(this).val()));
      });
      // console.log(checked);
    $("#user_ids_email").val(checked);
	  
     if(type != 'Send Email'){
      $("#email_subject").hide();
      $("#email_subject").prev().hide();
       $("#template_message").summernote('destroy');
       $("#template_message").html('');
    }else{
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
  url: '<?php echo base_url();?>message/get_templates_for_user/'+SMS,
  })
  .done(function(data){
      
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
  $("#loader1").show();
  if($('.checkbox1:checked').size() > 1000){
    alert('You can not send more that 1000 sms at once');
  }else{
    var sms_type = $("#mesge_type").val();
     url =  '<?php echo base_url();?>message/employee_send_sms';
     $.ajax({
    type: 'POST',
    url: url,
    data: $('#notification_send_from').serialize()
    })
    .done(function(data){
Swal.fire({
  icon: 'success',
  title: 'Response!',
  text: data,
  confirmButtonText: `OK`,
}).then((result) => {
  if (result.isConfirmed) {
    location.reload();
  }
})
    })
    .fail(function() {
Swal.fire({
  icon: 'error',
  title: 'Response!',
  text: 'Email',
  confirmButtonText: `OK`,
}).then((result) => {
  if (result.isConfirmed) {
    location.reload();
  }
})
    
    });   
  }
}

function getMessage(){
        
        var tmpl_id = document.getElementById('templates').value;       
        $.ajax({
            
            url : '<?php echo base_url('enquiry/msg_templates') ?>',
            type: 'POST',
            data: {tmpl_id:tmpl_id},
            success:function(data){
                
                var obj = JSON.parse(data);
                 $('#templates option[value='+tmpl_id+']').attr("selected", "selected");
                $("#template_message").html(obj.template_content);
                $("#email_subject").val(obj.mail_subject);
            }
            
        });
      
  }
</script>

<script>
function reset_input(){
$('input:checkbox').removeAttr('checked');
}

$('.checked_all1').on('change', function() {     
    // $('.checkbox1').prop('checked', $(this).prop("checked"));    
    $('input:checkbox').not(this).prop('checked', this.checked);
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
    setCookie('enquiry_filter_setting',arr,365);
    // $('#enq_filter').reset();  
    // $("#filter_pannel").trigger('reset'); 
    $(".form-control").val('');  
    ticket_save_filter();
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Your custom filters saved successfully.',
      showConfirmButton: false,
      timer: 1000
    });
  });

 

  var enq_filters  = getCookie('enquiry_filter_setting');
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

if (!enq_filters.includes('emp')) {
  $('#empfilter').hide();
}else{
  $("input[value='emp']").prop('checked', true);
}

if (!enq_filters.includes('phone')) {
  $('#phonefilter').hide();
}else{
  $("input[value='phone']").prop('checked', true);
}

if (!enq_filters.includes('email')) {
  $('#emailfilter').hide();
}else{
  $("input[value='email']").prop('checked', true);
}

if (!enq_filters.includes('created_by_dept')) {
  $('#createdbydeptfilter').hide();
}else{
  $("input[value='created_by_dept']").prop('checked', true);
}

if (!enq_filters.includes('sales_region')) {
  $('#regionfilter').hide();
}else{
  $('#regionfilter').show();

  $("input[value='sales_region']").prop('checked', true);
}

if (!enq_filters.includes('status')) {
  $('#statusfilter').hide();
}else{
  $('#statusfilter').show();
  $("input[value='status']").prop('checked', true);
}


$('input[name="filter_checkbox"]').click(function(){  
  if($('#datecheckbox').is(":checked")||$('#emailcheckbox').is(":checked")||
  $('#createdbydeptcheckbox').is(":checked")|| $('#regioncheckbox').is(":checked") ||
  $('#phonecheckbox').is(":checked")||$('#empcheckbox').is(":checked")||$('#statuscheckbox').is(":checked")){ 
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
        

        if($('#emailcheckbox').is(":checked")){
          $('#emailfilter').show();
        }
        else{
          $('#emailfilter').hide();
        }
        
        if($('#phonecheckbox').is(":checked")){
          $('#phonefilter').show();
        }
        else{
          $('#phonefilter').hide();
        }
		
		if($('#createdbydeptcheckbox').is(":checked")){
          $('#createdbydeptfilter').show();
        }
        else{
          $('#createdbydeptfilter').hide();
        }
		
		if($('#regioncheckbox').is(":checked")){
          $('#regionfilter').show();
        }
        else{
          $('#regionfilter').hide();
        }
       
        if($('#statuscheckbox').is(":checked")){
          $('#statusfilter').show();
        }
        else{
          $('#statusfilter').hide();
        }	  

            
    });
})



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


function ticket_reset_filter(){
$('input[name=from_created').val('');
$('input[name=to_created').val('');
$('input[name=email').val('');
$('input[name=employee').val('');
$('input[name=phone').val('');
$('#dept_reset').val(null).trigger("change");
$('#sales_reset').val(null).trigger("change");
$('#status').val(null).trigger("change");

var form_data = $("#enq_filter").serialize();       

$.ajax({
url: '<?=base_url()?>ticket/ticket_save_filter/1',
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
</script>


<script> 

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

$(document).ready(function(){
  $('#saveButton').click(function(){
    $("#inactive_all").submit(); //if requestNew is the id of your form
  });
});

// Pipelining function for DataTables. To be used to the `ajax` option of DataTables

$(document).ready(function() {
role = "<?=!empty($_GET['user_role'])?'?user_role='.$_GET['user_role']:''?>";
$("#user_dlist2").dataTable().fnDestroy();

$('#user_dlist2').DataTable({         
    "processing": true,
    "scrollX": true,
    "scrollY": 520,
    "serverSide": true,          
    "lengthMenu": [ [10,30, 50,100,500,1000], [10,30, 50,100,500,1000] ],
    "ajax": {
        "url": "<?=base_url().'user/departments'?>"+role,
        "type": "POST",
        //"dataType":"html",
        //success:function(q){ //alert(q); //document.write(q);},
        error:function(u,v,w)
        {
          alert(w); 
        }
        },
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
        ] ,
     createdRow: function( row, data, dataIndex ) {            
       var th = $("table>th");            
       l = $("table").find('th').length;
       for(j=1;j<=l;j++){
         h = $("table").find('th:eq('+j+')').html();
         $(row).find('td:eq('+j+')').attr('data-th',h);
       }  
     }                
});




set_filter_session();

$('#find_filterbutton').click(function() {
        set_filter_session();
      });

      function set_filter_session(){
          
          //update_top_filter_counter(); 
          var form_data = $("#enq_filter").serialize();  
          //console.log(form_data);
          // alert(form_data);
  
            $.ajax({
            url: '<?=base_url()?>enq/enquiry_set_filters_session',
            type: 'post',
            data: form_data,
            success: function(responseData){
              $('#user_dlist2').DataTable().ajax.reload();         
            /* if(!$("#active_class").hasClass('hide_countings')){
              update_top_filter_counter();      
            } */
            }
          });
        }

        
  });
</script>