<script src="http://demo.phpgang.com/lazy-loading-images-jquery/jquery.devrama.lazyload.min-0.9.3.js"></script> 

<!--  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" ></script>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->

<link href="<?php echo base_url();?>assets/css/fullcalendar.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/css/fullcalendar.print.min.css" media="print">

<style type="text/css">
  .btnStatus{
    padding: 0px 4px !important;
    color: #fff !important;
    width: 79px!important; 
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
  .Cancelled{
    background-color: #db2828 !important;
    border-color: #db2828 !important;
  }
  #content_tabss_paginate{
    text-align: center!important;
  }
</style>
<br>
<div class="col-md-12 text-center">  
<?php  if(user_access(90)==true){ ?>
    <button type="button" class="btn btn-secondary" data-toggle="modal" id="task_create" data-target="#create_task">
      Add Reminder
    </button>
<?php }?>
   </div>
<?php
if(user_access(310)){
?>
<div class="col-md-12 text-center">    
<br>
  <select class='btn btn-sm btn-default text-center' id='for_filter'>
    <option value='1'>
      Sales Related
    </option>
    <option value='2' <?=(!empty($_GET['for']) && $_GET['for']==2)?'selected':''?>>
      Ticket Related
    </option>
  </select>
</div>
<?php
}
?>
<div class="col-md-12">
   <br>
   <div class="col-md-4"></div>   
     <div class="col-md-4">
        <select class="form-control" name="org_name" id="user_id_fortask" >
           <option value="" style="display:none;">--Select---</option>
           <?php foreach($user_list as $user){?>
           <option value="<?=$user->pk_i_admin_id ?>" <?php if($this->session->user_id==$user->pk_i_admin_id){echo 'selected';} ?>><?=$user->s_display_name ?>&nbsp;<?=$user->last_name; ?> -  <?=$user->s_user_email?$user->s_user_email:$user->s_phoneno;?></option>
           <?php } ?>
        </select>
        <br>
     </div>   
</div>
<br>
<br>
<br>
<br>


<?php  if(user_access(90)==true && 0){ ?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id='set_reminder' method="post">                
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Set Reminder</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <label>Subject</label>
                <input type="text" name="reminder_txt" class="form-control">                            
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <label>Reminder Date</label>
                <input   name="reminder_date" class="form-control form-date">                            
              </div>
              <div class="col-md-6">
                <label>Reminder Time</label>
                <input type="time" placeholder="12:00:00" name="reminder_time" class="form-control">                            
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Reminder</button>
      </div>
    </div>
      </form>
  </div>
</div>
<?php } ?>

<div class="card-body">
      <div class="col-md-12">
         <div id="calendar" style="width:100%"></div>
         <br><br>
      </div>
         
   </div>
<div class="col-md-12"  style="background-color:#fff;" id="task_div1">
  <table class="table table-striped table-bordered mobile-optimised" id="content_tabss" style="width: 100%!important;">
     <thead>
        <tr>
           <th>Date</th>
           <th>Time</th>
           <th>Task</th>
           <th>Task Type</th>
           <th>Remark</th>
           <th>Person Name</th>           
           <th>Created By</th>
           <th>Mobile No </th>
           <th>Status </th>
           <th>Actions</th>
        </tr>
     </thead>
     <tbody>
     </tbody>
   </table>  
</div>
<br>
</div> 
</div>
<br>
<br>
<br>


<div id="task_edit" class="modal fade in" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content " id="update-task-content">
      </div>
   </div>
</div>

<div id="create_task" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Task</h4>
      </div>
      <div class="modal-body" id='task_content'>
      </div>      
    </div>
  </div>
</div>
<script>
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
  $(document).ready(function() {

    var table  = $('#content_tabss').DataTable( {         
        "processing": true,
        /*"scrollX": true,*/
        'searching': true,
        "ordering": false,
        "scrollY": 800,
        "dom": "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",         
        "buttons": [  
            {extend: 'copy', className: 'btn-sm'}, 
            {extend: 'csv',  className: 'btn-sm'}, 
            {extend: 'excel', className: 'btn-sm'}, 
            {extend: 'pdf', className: 'btn-sm'}, 
            {extend: 'print', className: 'btn-sm'} 
        ], 
        "serverSide": true,
        "lengthMenu": [ [30, 50, -1], [30, 50, "All"] ],
        "ajax": {
            "url": "<?=base_url().'task/task_load'?>",
            "type": "POST",
            "data": {
              "filter_user_id": $("#user_id_fortask").val(),
              "task_for": "<?=!empty($_GET['for'])?$_GET['for']:''?>"
            }
        },
        "columnDefs": [ {
        "targets": -1,
        "orderable": false
        } ],
        "createdRow": function( row, data, dataIndex ) {            
            var th = $("table>th");            
            l = $(".mobile-optimised").find('th').length;
            for(j=0;j<l;j++){
              h = $(".mobile-optimised").find('th:eq('+j+')').html();
              $(row).find('td:eq('+j+')').attr('data-th',h);
            }                       
        }        
    } );

    $("#user_id_fortask").on('change',function(){
      id = $(this).val();
      var task_for = "<?=!empty($_GET['for'])?'?for='.$_GET['for']:''?>";
      var events = {
        url: "<?php echo base_url().'task/get_calandar_feed/'?>"+task_for,
        type: 'POST',
        data: {
            start: $('#calendar').fullCalendar('getView').start,
            end: $('#calendar').fullCalendar('getView').end,
            user_id: id
        }
      }
      //remove old data
      $('#calendar').fullCalendar('removeEvents');       
      //Getting new event json data
      $("#calendar").fullCalendar('addEventSource', events);
      //Updating new events
      $('#calendar').fullCalendar('rerenderEvents');      
      table.ajax.reload();
    });
   
   
   
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
        var task_for = "<?=!empty($_GET['for'])?'?for='.$_GET['for']:''?>";
        jQuery.ajax({
            url: "<?php echo base_url().'task/get_calandar_feed'?>"+task_for,
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
         $('td').dblclick(function(){           
        }); 
         
         ser_date = date.format();
          $.ajax({
          type: 'POST',
          url: '<?php echo base_url();?>task/search_comment_and_task/'+ser_date,
          data:{
            task_for:"<?=!empty($_GET['for'])?$_GET['for']:''?>"                        
          }
          })
          .done(function(data){                       
              $("#task_div1").html(data);
          })
   
        
       },
   
     })
     });

   
</script>
<script>
$("#task_create").on('click',function(){
  $("#task_content").load("<?=base_url().'task/create_task_form'?>",function(){
     try{
   $("select").select2();
 }catch(e){alert(e);}
  });
});
$("#for_filter").on('change',function(){
  if($(this).val()==2){
    window.location = "<?=base_url().'task/index?for=2'?>"
  }else{
    window.location = "<?=base_url().'task/index'?>"
  }
})

// $(document).ready(function(){
//     $("select").select2();
//   });
</script>


<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>

<script src="<?php echo base_url();?>assets/js/fullcalendar.min.js"></script>

<script src="<?php echo base_url();?>assets/js/moment.js"></script>