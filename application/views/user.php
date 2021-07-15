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
							<th>Gread</th>
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