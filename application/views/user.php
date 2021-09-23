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
        </div>       
      </div>
            </div>

            <div class="panel-body">
                 <form id="inactive_all" method="POST" action="<?= base_url('user/inactive-all') ?>">   
                   
                <table class="table table-striped table-bordered" id="example" cellspacing="0" width="100%">

                    <thead>

                        <tr>

                        <th class="noExport">
                     <input type='checkbox' class="checked_all1" value="check all"  onclick="event.stopPropagation();">
                     </th>
                            <th>Emp Id</th>
                            <th><?php echo display('disolay_name') ?></th>
                            <th><?php echo display('user_function') ?></th>
							<th>Report To</th>
                            <th>Email</th>
                            <th><?php echo display('mobile') ?></th>
							<th>Sales Region</th>
							<th>Sale Area</th>
							<th>Sales Branch</th>
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

$('#example').DataTable({         
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
     createdRow: function( row, data, dataIndex ) {            
       var th = $("table>th");            
       l = $("table").find('th').length;
       for(j=1;j<=l;j++){
         h = $("table").find('th:eq('+j+')').html();
         $(row).find('td:eq('+j+')').attr('data-th',h);
       }  
     }                
});
  });
</script>