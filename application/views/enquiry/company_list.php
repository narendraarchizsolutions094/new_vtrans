<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
        <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>
		&nbsp;&nbsp;<a href="<?=base_url().'client/userwise_company_list'?>" class="btn btn-primary">Userwise Company</a>
        <!--<a href="<?=base_url().'compay_profile/company_upload_data'?>" class="btn btn-danger">Add Company</a>-->
<!--<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#Addbulk" href="javascript:void(0)"> <i class="fa fa-wrench"></i> Update Company Name</a>-->		
          <?php
          // if(user_access('1010'))
          // {
          ?>     
         <!--  <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Contact" title="Add Contact"></a>  -->
          <?php
          // }
          ?>        
        </div>
		
<!----------------------------------------------------Bulk Upload Start-------------------------------->
<div class="modal fade" id="Addbulk" role="dialog" aria-labelledby="course_upload_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Upload Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url() . 'location/update_company_name' ?>" enctype="multipart/form-data" method='post'>
        <div class="modal-body">
          <div class="row">

            <div class="form-group">
              <label id="label">Choose File </label>
              <input type="file" name="file" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!----------------------------------------------------bulk Upload End-------------------------------->
		
	<div class="btn-group dropdown-filter" style="float:right;margin-right:60px;">
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
                      <input type="checkbox" value="created_by" id="createdbycheckbox" name="filter_checkbox"> Created By</label>
                    </li> 
                             
                   <li>
                      <label>
                      <input type="checkbox" value="sales_region" id="regioncheckbox" name="filter_checkbox">Emp. Region</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="department" id="deptcheckbox" name="filter_checkbox">Emp. Department</label>
                    </li>
                    
                    <li class="text-center">
                      <a href="javascript:void(0)" class="btn btn-sm btn-primary " id='save_advance_filters' title="Save Filters Settings"><i class="fa fa-save"></i></a>
                    </li>                   
                </ul>                
            </div>
</div>

<!--------------------filter's start----------------->
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
                         
                        <div class="form-group col-md-3" id="createdbyfilter">
                          <label for="">Created By</label>
                         <select name="createdby" class="form-control" id="create_reset"> 
                          <option value="">Select</option>
                         <?php 
                          if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>" <?php if($createdbylist->pk_i_admin_id==$filterData['createdby']) {echo 'selected';}?> <?php if(!empty(set_value('createdby'))){if (in_array($product->sb_id,set_value('createdby'))) {echo 'selected';}}?> ><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?>                               
                              </option>
                              <?php }}?>    
                         </select>                       
                        </div>

                        <div class="form-group col-md-3" id="regionfilter">
                        <label for="">Sales Region</label> 
                        <select name="sales_region" class="form-control" id="region_reset">
                          <option value="">Select</option>
                          <?php
                            foreach ($region_lists as $k=>$v) {  ?>
                              <option value="<?=$v->region_id;?>" <?php if(!empty($filterData['sales_region']) && $v->region_id==$filterData['sales_region']) {echo 'selected';}?>><?php echo $v->name; ?></option>
                              <?php }                             
                              ?>
                        </select>
                        </div>
					  
                      </div>

                    <div class="form-row">

                        <div class="form-group col-md-3" id="deptfilter">
                          <label for="">Select Department</label>
                            <select name="department" class="form-control" id="dept_reset">
                              <option value="">---Select---</option>
                              <?php
                            foreach ($dept_lists as $k=>$d) {  ?>
                              <option value="<?=$d->id;?>" <?php if(!empty($filterData['department']) && $d->id==$filterData['department']) {echo 'selected';}?>><?php echo $d->dept_name; ?></option>
                              <?php }                             
                              ?>
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
<!--------------------filter's end----------------->

<div class="row p-5" style="margin-top: 20px;">
	<div class="col-lg-12">
		<div class="panel panel-success">
			<div class="panel-body">
        <table style="width: 100%" id="companyTable" class=" table table-bordered table-response">
             <thead>
                 <tr>
                   <th>&nbsp; # &nbsp;</th>
                   <th onclick="dosort()"><?=display('company_name')?></th>
				   <th>Created By</th>
				   <th>Emp. Region</th>
				   <th>Emp. Department</th>
				   <th>Create At</th>
                   <th>Contacts</th>
                   <th>Deals</th>
                   <th>Visits</th>
                   <th>Tickets</th>
                   <th>Clients</th>  
                 </tr>
             </thead>
             <tbody>
               
             </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  // function open_link(t)
  // {
  //   window.open($(t).data('url'),'_blank');
  // }
var specific_list='';
var c='';
$(document).ready(function(){
  $('#companyTable').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'client/company_load_data'?>",
              "type": "POST",
              "data":function(d){

                     // d.date_from = $("input[name=d_from_date]").val();
                     // d.date_to = $("input[name=d_to_date]").val();
                     
                     d.view_all=true;
                     d.specific_list = specific_list;
                     TempData = d;

                      if(c && c!='')
                      d.allow_cols = c;

                     console.log(JSON.stringify(d));
                   return d;
             }
         },
		 
		 dom: "<'row text-center'<'col-sm-12 col-xs-12 col-md-4'l><'col-sm-12 col-xs-12 col-md-4 text-center'B><'col-sm-12 col-xs-12 col-md-4'f>>tp", 
        buttons: [  
            {extend: 'copy', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'csv', title: 'company_list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'excel', title: 'company_list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn', title: 'exportTitle',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'pdf', title: 'company_list<?=date("Y-m-d H:i:s")?>', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }}, 
            {extend: 'print', className: 'btn-xs btn',exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }} 
        ] ,
		
          columnDefs: [
                       { orderable: false, targets: -1 }
                    ]
 });
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

if (!enq_filters.includes('created_by')) {
  $('#createdbyfilter').hide();
}else{
  $("input[value='created_by']").prop('checked', true);
}

if (!enq_filters.includes('sales_region')) {
  $('#regionfilter').hide();
}else{
  $("input[value='sales_region']").prop('checked', true);
}

if (!enq_filters.includes('department')) {
  $('#deptfilter').hide();
}else{
  $("input[value='department']").prop('checked', true);
}


$('input[name="filter_checkbox"]').click(function(){  
  if($('#datecheckbox').is(":checked")||$('#createdbycheckbox').is(":checked")||
  $('#regioncheckbox').is(":checked")||$('#deptcheckbox').is(":checked")){ 
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
         if($('#createdbycheckbox').is(":checked")){
        $('#createdbyfilter').show();
        $("#buttongroup").show();
        }
        else{
          $('#createdbyfilter').hide();
          $("#buttongroup").hide();
        }
        

        if($('#regioncheckbox').is(":checked")){
          $('#regionfilter').show();
        }
        else{
          $('#regionfilter').hide();
        }
        
        if($('#deptcheckbox').is(":checked")){
          $('#deptfilter').show();
        }
        else{
          $('#deptfilter').hide();
        }
            
    });
})


set_filter_session();

//CHANGE DUE TO RESET BUTTON
      /* $('#enq_filter').change(function() {
        set_filter_session();
      }); */
//END*

$('#find_filterbutton').click(function() {
        set_filter_session();
      });
	  
      function set_filter_session(){
          
          //update_top_filter_counter(); 
          var form_data = $("#enq_filter").serialize();  
          console.log(form_data);
          // alert(form_data);
  
            $.ajax({
            url: '<?=base_url()?>enq/enquiry_set_filters_session',
            type: 'post',
            data: form_data,
            success: function(responseData){
              $('#companyTable').DataTable().ajax.reload();         
            if(!$("#active_class").hasClass('hide_countings')){
              update_top_filter_counter();      
            }
            }
          });
        }

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
$('#create_reset').val(null).trigger("change");
$('#region_reset').val(null).trigger("change");
$('#dept_reset').val(null).trigger("change");

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