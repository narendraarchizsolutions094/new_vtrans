<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> 

<script src="<?=base_url()?>/assets/summernote/summernote-bs4.min.js"></script>
<link href="<?=base_url()?>/assets/summernote/summernote-bs4.css" rel="stylesheet" />

<style>
  /*TAG STYLE START*/
.tag {
  background: #eee;
  border-radius: 3px 0 0 3px;
  color: red;
  display: inline-block;
  height: 17px;
  line-height: 17px;
  padding: 0 10px 0 19px;
  position: relative;  
  text-decoration: none;
  -webkit-transition: color 0.2s;
  font-size: xx-small !important;  
}

.tag::before {
  background: #fff;
  border-radius: 10px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
  content: '';
  height: 6px;
  left: 10px;
  position: absolute;
  width: 6px;
  top: 6px;
}

.tag::after {
  background: #fff;
  border-bottom: 8px solid transparent;
  border-left: 10px solid #eee;
  border-top: 9px solid transparent;
  content: '';
  position: absolute;
  right: 0;
  top: 0;
}

.tag:hover {
  background-color: crimson;
  color: white;
}

.tag:hover::after {
   border-left-color: crimson; 
}
/*TAG STYLE END*/


.col-half-offset{
  margin-left:2.166667%;
}
.enq_form_filters{
  width: 0px;
}
#active_class{
  font-size: 12px;
}
.hide_countings{
   display:none !important;    
 }
.lead_stage_filter{
  padding: 6px;
  background-color: #e6e9ed;
  cursor: pointer;
}
.lead_stage_filter:active{  
  background-color: #20a8d8;  
}
.lead_stage_filter:hover{  
  background-color: #20a8d8;  
}
.border_bottom_active > label{
  cursor: pointer;
}
.nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
    color: white;
    background-color: #37a000;
}

.nav-pills > li > a {
    border-radius: 5px;
    padding: 10px;
    color: #000;
    font-weight: 600;
}

.nav-pills > li > a:hover {
    color: #000;
    background-color: transparent;
}
              .dropdown-header {
    padding: 8px 20px;
    background: #e4e7ea;
    border-bottom: 1px solid #c8ced3;
}

.dropdown-header {
    display: block;
    padding: 0 1.5rem;
    margin-bottom: 0;
   
    color: #73818f;
    white-space: nowrap;
}
input[name=top_filter]{
  visibility: hidden;
}

input[name=lead_stages]{
  visibility: hidden;
}

.dropdown_css {
  left:auto!important;
  right: 0 ! important;
}
.dropdown_css a,.dropdown_css a h4{
  width:100%;text-align:left! important;
  border-bottom: 1px solid #c8ced3! important;
}

.border_bottom{
  border-bottom:2px solid #E4E5E6;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
}  
.border_bottom_active{
  border-bottom:2px solid #20A8D8;min-height: 7vh;margin-bottom: 1vh;cursor:pointer;
} 

.filter-dropdown-menu li{
  padding-left: 6px;
}

.filter-dropdown-menu li{
  padding-left: 6px;
}
@media screen and (max-width: 900px) {
  #active_class{
    display: none;
  }
}
</style>

<form method="post" id="log_filter" >
<div class="row">
 <div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">  
        <div class="col-md-4 col-sm-4 col-xs-4" > 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>          
        </div>
         <div class="col-md-4 col-sm-8 col-xs-8 pull-right" >  
          <div style="float: right;">      
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
                      <input type="checkbox" value="created_by" id="createdbycheckbox" name="filter_checkbox"> Created By</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="call_type" id="calltypecheckbox" name="filter_checkbox"> Call Type</label>
                    </li>
                    <li>
                      <label>
                      <input type="checkbox" value="client_name" id="clientnamecheckbox" name="filter_checkbox"> Client Name</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="call_status" id="callstatuscheckbox" name="filter_checkbox"> Call Status</label>
                    </li> 
                    <li>                    
                    <li class="text-center">
                      <a href="javascript:void(0)" class="btn btn-sm btn-primary " id='save_advance_filters' title="Save Filters Settings"><i class="fa fa-save"></i></a>
                    </li>                   
                </ul>                
            </div>

            <div class="btn-group" role="group" aria-label="Button group">
              <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Actions">
                <i class="fa fa-sliders"></i>
              </a>  
            <div class="dropdown-menu dropdown_css" style="max-height: 400px;overflow: auto;">
              <a class="btn" data-toggle="modal" data-target="#table-col-conf" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;"><?php echo display('table_config'); ?></a>                           
            </div>                                         
          </div>  
        </div>       
      </div>
</div>
<style type="text/css">
  #filter_pannel .col-md-3
  {
    height: 63px; 
    font-size: 12px!important;
    margin: 0px;
  }
</style>
<!------ Filter Div ---------->
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
                         <select name="createdby" class="form-control"> 
                          <option value="">--please Select--</option>
                         <?php 
                          if (!empty($created_bylist)) {
                              foreach ($created_bylist as $createdbylist) {?>
                              <option value="<?=$createdbylist->pk_i_admin_id;?>" <?php if($createdbylist->pk_i_admin_id==$filterData['createdby']) {echo 'selected';}?> <?php if(!empty(set_value('createdby'))){if (in_array($product->sb_id,set_value('createdby'))) {echo 'selected';}}?> ><?=$createdbylist->s_display_name.' '.$createdbylist->last_name;?> -  <?=$createdbylist->s_user_email?$createdbylist->s_user_email:$createdbylist->s_phoneno;?>                               
                              </option>
                              <?php }}?>    
                         </select>                       
                        </div>
                         <div class="form-group col-md-3" id="calltypefilter">
                          <label for="">Call Type</label>  
                         <select name="calltype" class="form-control"> 
                          <option value="">--Please Select--</option>
                          <option value="Incoming" <?php if($filterData['call_type']=='Incoming') {echo 'selected';}?>>Incoming</option>
                          <option value="Outgoing" <?php if($filterData['call_type']=='Outgoing') {echo 'selected';}?>>Outgoing</option>						  
                         </select>                          
                        </div>
					</div>
                   <div class="form-row">
                      <div class="form-group col-md-3" id="clientnamefilter">
 						<label for="">Client Name</label>
                        <input type="text" class="form-control" name="clientname" value="<?= $filterData['client_name'] ?>">
                      </div>
					  <div class="form-group col-md-3" id="callstatusfilter">
 						<label for="">Call Status</label>
                        <select class="form-control" name="callstatus">
                            <option value="">--Please Select --</option>
                            <option value="1" <?php if($filterData['call_status']==1) {echo 'selected';}?>> New </option>
                            <option value="2" <?php if($filterData['call_status']==2) {echo 'selected';}?>> Old </option>
                        </select>
                      </div>

                      <div class="form-group col-md-3">
                       <button class="btn btn-success" id="save_filterbutton" type="button" onclick="ticket_save_filter();" style="margin: 20px;">Save</button>        
                        </div>  
                    </div>
          
            </div>
        </div>
    </div>   
</form>

<div style="float:right;">
  <a class='btn btn-xs  btn-primary' href='javascript:void(0)' id='show_quick_counts' title='Show Quick Dashboard'><i class='fa fa-bar-chart'></i></a>
</div>
<div class="row row text-center short_dashboard hide_countings" id="active_class">   

        <div class="wd-14" style="">
            <div  class="col-12 border_bottom" >
                <p style="margin-top: 2vh;font-weight:bold;">
                  <input id='Incoming_radio' value="incoming" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="Incoming_radio">&nbsp;&nbsp;<?php echo 'Incoming'; ?></label>
                  <span  style="float:right;" class="badge badge-pill badge-primary " id="all_incoming"><i class="fa fa-spinner fa-spin"></i></span>
                </p>
            </div>
        </div>
        <div class="wd-14" style="">
            <div  class="col-12 border_bottom" >
                <p style="margin-top: 2vh;font-weight:bold;">
                  <input id='outgoing_radio' value="outgoing" type="radio" name="top_filter" class="enq_form_filters"><i class="fa fa-edit" ></i><label for="outgoing_radio">&nbsp;&nbsp;<?php echo 'Outgoing'; ?></label>
                  <span  style="float:right;" class="badge badge-pill badge-primary " id="all_outgoing"><i class="fa fa-spinner fa-spin"></i></span>
                </p>
            </div>
        </div>
        <div class="wd-14">
              <div class="col-12 border_bottom">
                  <p style="margin-top: 2vh;font-weight:bold;" >
                    <input type="radio" name="top_filter" value="new" class="enq_form_filters" id="new_radio"><i class="fa fa-pencil"></i><label for="new_radio">&nbsp;&nbsp;<?php echo 'New'; ?></label>
					<span style="float:right;background:#ffc107" class="badge badge-pill badge-warning badge badge-dark " id="all_new"><i class="fa fa-spinner fa-spin"></i></span>
                  </p>
              </div>
        </div>
            
        <div class="wd-14">
            <div  class="col-12 border_bottom" >
                  <p style="margin-top: 2vh;font-weight:bold;"  title="<?php echo display('Existing'); ?>"> 
                    <input type="radio" name="top_filter" value="existing" class="enq_form_filters" id="existing_radio"><i class="fa fa-file" ></i><label for="active_radio">&nbsp;&nbsp;<?php echo 'Existing'; ?></label>
					<span style="float:right;" class="badge badge-pill badge-primary " id="all_existing"><i class="fa fa-spinner fa-spin"></i></span>
                  </p>
              </div>
        </div>
        <div class="wd-14">
              <div class="col-12 border_bottom border_bottom_active" >

                  <p style="margin-top: 2vh;font-weight:bold;"  title="<?php echo display('total'); ?>">
                    <input type="radio" name="top_filter" value="all" checked="checked" class="enq_form_filters" id="total_active_radio">
                    <i class="fa fa-list"></i><label for="total_active_radio">&nbsp;&nbsp;<?php echo display('total'); ?></label><span style="float:right;background:#000" class="badge badge-pill badge-dark " id="all_call"><i class="fa fa-spinner fa-spin"></i></span>
                  </p>
              </div>
        </div>   
    </div>
</div>

<style type="text/css">
  .wd-14{
    width: 13.2%;
    display: inline-block;
  }

.short_dashboard button{
  margin:4px;
}
.short_dashboard
{
  margin-bottom: 15px;
}
</style>

<form class="form-inner" method="post" id="enquery_assing_from" >  
<div class="card-body">
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
  <div class="row">
    <div class="col-md-12" >    
            <table id="log_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
        <thead>
          <tr class="bg-info table_header">
            <th>S.N</th>
             <?php if ($showall == true or in_array(1, $acolarr)) {  ?>
                  <th>Client Name</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(2, $acolarr)) {  ?>
                  <th>Company</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(3, $acolarr)) {  ?>
                  <th>Mobile number</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(4, $acolarr)) {  ?>
                  <th>Email ID</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(5, $acolarr)) {  ?>
                  <th>Call Type</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(6, $acolarr)) {  ?>
                  <th>Duration</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(7, $acolarr)) {  ?>
                  <th>Created By</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(8, $acolarr)) {  ?>
                  <th>Created At</th>
             <?php } ?>
			 <?php if ($showall == true or in_array(9, $acolarr)) {  ?>
                  <th>Action</th>
             <?php } ?>
          </tr>
        </thead>
        <tbody>             
        </tbody>
      </table>
    </div>
  </div>
</div>

<!--------------------TABLE COLOUMN CONFIG----------------------------------------------->
<div id="table-col-conf" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="width: 96%;">
 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Table Column Configuration</h4>
      </div>
      <div class="modal-body">         
           <div class="row">
             <div class="col-md-3">
                <label class=""><input type="checkbox" id="selectall" onclick="select_all()">&nbsp;Select All</label>
             </div>
           </div>
        <hr>
          <div class="row">
          <div class = "col-md-4">             
          <label class=""><input type="checkbox" class="choose-col" id="choose-col" value = "1" <?php echo ($showall == true or in_array(1, $acolarr)) ? "checked" : ""; ?>> Client Name</label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "2"  <?php echo ($showall == true or in_array(2, $acolarr)) ? "checked" : ""; ?>> Company</label> 
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "3"  <?php echo ($showall == true or in_array(3, $acolarr)) ? "checked" : ""; ?>> Mobile number</label> 
          </div>
          <div class = "col-md-4">  
          <label  class=""><input type="checkbox" class="choose-col"  value = "4"  <?php echo ($showall == true or in_array(4, $acolarr)) ? "checked" : ""; ?>> Email Id</label>
          </div>
          <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "5"  <?php echo ($showall == true or in_array(5 , $acolarr)) ? "checked" : ""; ?>> Call Type </label>
          </div>
		  <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "6"  <?php echo ($showall == true or in_array(6 , $acolarr)) ? "checked" : ""; ?>> Duration</label>
          </div>
		  <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "7"  <?php echo ($showall == true or in_array(7 , $acolarr)) ? "checked" : ""; ?>> Created By</label>
          </div>
		  <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "8"  <?php echo ($showall == true or in_array(8 , $acolarr)) ? "checked" : ""; ?>> Created At</label>
          </div>
		  <div class = "col-md-4">  
          <label class=""><input type="checkbox" class="choose-col"  value = "9"  <?php echo ($showall == true or in_array(9 , $acolarr)) ? "checked" : ""; ?>> Action</label>
          </div>
                
            <div class="col-12" style="padding: 0px;">
                <div class="row">              
                  <div class="col-12" style="text-align:center;">                                                
                               
                  </div>
                </div>                                   
            </div> 
                   
      </div>
      <div class="modal-footer">
        <button class="btn btn-success set-col-table" type="button">Save</button> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
$(document).ready(function(){
$("select").select2(); 
});


  function select_all(){

var select_all = document.getElementById("selectall"); //select all checkbox
var checkboxes = document.getElementsByClassName("choose-col"); //checkbox items

//select all checkboxes
select_all.addEventListener("change", function(e){
  for (i = 0; i < checkboxes.length; i++) { 
    checkboxes[i].checked = select_all.checked;
  }
});


for (var i = 0; i < checkboxes.length; i++) {
  checkboxes[i].addEventListener('change', function(e){ //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){
      select_all.checked = false;
    }
    //check "select all" if all checkbox items are checked
    if(document.querySelectorAll('.choose-col:checked').length == checkboxes.length){
      select_all.checked = true;
    }
  });
}

}

</script>
</form>

<script type="text/javascript">

  $(document).on("click", ".set-col-table", function(e){
    
    e.preventDefault();
    if($(".choose-col:checked").length == 0 && $(".dchoose-col:checked").length == 0 ){
      
      return false;
    }
    var chkval = "";
    $(".choose-col:checked").each(function(){
      
      chkval += $(this).val()+",";
    });
    var dchkval = "";
    $(".dchoose-col:checked").each(function(){
      
      dchkval += $(this).val()+",";
    });
    
    document.cookie = "allowcols="+chkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
    document.cookie = "dallowcols="+dchkval+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
    location.reload();    
  });

  $(document).ready(function() {
       
   var table  = $('#log_table').DataTable(
        {         
          "processing": true,
          "scrollX": true,
          "scrollY": 520,
          "pagingType": "simple",
          "bInfo": false,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'client/log_load_data'?>",
              "type": "POST",
              "data":function(d){
                d.data_type = "";               
                return d;
              }
          },
        <?php if(user_access(500)) { ?>
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
        <?php
        }
        ?>
          "columnDefs": [{ "orderable": false, "targets":0 }],
           "order": [[ 1, "desc" ]],
          createdRow: function( row, data, dataIndex ) {            
            var th = $("table>th");            
            l = $("table").find('th').length;
            for(j=1;j<=l;j++){
              h = $("table").find('th:eq('+j+')').html();
              $(row).find('td:eq('+j+')').attr('data-th',h);
            }                       
        },
        drawCallback: function (settings) {
          var api = this.api();
          var $table = $(api.table().node());  
          var info = table.page.info();
          
          returned_rows = table.rows().count();
          
          if(returned_rows == 0 || returned_rows < info.length){
            $('#log_table_next').addClass('disabled');
          }
          
          $('#log_table_previous').after('<li><a class="btn btn-secondary btn-sm" style="padding: 4px;line-height: 2;" href="javascript:void(0)">'+info.page+'</a></li>');
        }
      });

  

$(document).on('click',".top_pill",function(){

     if(!$(this).hasClass('top-active'))
     {
        $(".top_pill").removeClass('top-active');
        $(this).addClass('top-active');
        var form_data = $("#log_filter").serialize();
          $.ajax({
            url: '<?=base_url()?>enq/enquiry_set_filters_session',
            type: 'post',
            data: form_data,
            success: function(responseData){
              $('#log_table').DataTable().ajax.reload();   
             // update_top_filter_counter(); 
          }
        });
        
     }
     else
     {
        $(".top_pill").removeClass('top-active');
        var form_data = $("#log_filter").serialize(); 
          form_data+="&stage=";
          $.ajax({
            url: '<?=base_url()?>enq/log_set_filters_session',
            type: 'post',
            data: form_data,
            success: function(responseData){
              $('#log_table').DataTable().ajax.reload();    
               //update_top_filter_counter();
          }
        });
     }
    $('#log_table').DataTable().ajax.reload();
});

      $("input[name='product_filter[]']").on('change',function(){    
        $('#log_table').DataTable().ajax.reload();
        process_change_fun();
      });
      $("#show_quick_counts").on('click',function(){
        $(this).hide();
        $("#active_class").removeClass('hide_countings');    
        update_top_filter_counter();      
      });
      function update_top_filter_counter(){
        $.ajax({
        url: "<?=base_url().'client/log_dashboard_count'?>",
        type: 'post',
        data:{data_type:""},
        dataType: 'json',
        success: function(responseData){
        $('#all_incoming').html(responseData.all_incoming);
        $('#all_outgoing').html(responseData.all_outgoing);
        $('#all_new').html(responseData.all_new);
        $('#all_existing').html(responseData.all_existing);
        $('#all_call').html(responseData.all_call);       
        all_lead_stage_c  = $("input[name='top_filter']:checked").next().next().next().html();     
        $('#lead_stage_-1').text(all_lead_stage_c);     
        },
        error:function(u,v,w)
        {
          alert(w);
        }
    });
      }

      set_filter_session();
      $('#log_filter').change(function() {
        set_filter_session();
      });

      function set_filter_session(){ 
        var form_data = $("#log_filter").serialize();  
        console.log(form_data);
          $.ajax({
          url: '<?=base_url()?>enq/log_set_filters_session',
          type: 'post',
          data: form_data,
          success: function(responseData){
            $('#log_table').DataTable().ajax.reload();         
          if(!$("#active_class").hasClass('hide_countings')){
            update_top_filter_counter();      
          }
          }
        });
      }
  } );
</script>

<script>
function reset_input(){
$('input:checkbox').removeAttr('checked');
}

$('.checked_all1').on('change', function() {     
    $('.checkbox1').prop('checked', $(this).prop("checked"));    
});
        
</script>



<script type='text/javascript'>
$(window).load(function(){  
$("#active_class p").click(function() {
    $('.border_bottom_active').removeClass('border_bottom_active');
    $(this).addClass("border_bottom_active");

    $(this).find('label').trigger('click');
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
    setCookie('log_filter_setting',arr,365);      
    
    Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Your custom filters saved successfully.',
  showConfirmButton: false,
  timer: 1000
});
  }) 



var log_filters  = getCookie('log_filter_setting');
if (log_filters=='') {
    $('#filter_pannel').hide();
    $('#save_filterbutton').hide();

}else{
  $('#filter_pannel').show();
  $('#save_filterbutton').show();

}


if (!log_filters.includes('date')) {
  $('#fromdatefilter').hide();
  $('#todatefilter').hide();
}else{
  $("input[value='date']").prop('checked', true);
}

if (!log_filters.includes('call_type')) {
  $('#calltypefilter').hide();
}else{
  $("input[value='call_type']").prop('checked', true);
}

if (!log_filters.includes('client_name')) {
  $('#clientnamefilter').hide();
}else{
  $("input[value='client_name']").prop('checked', true);
}

if (!log_filters.includes('call_status')) {
  $('#callstatusfilter').hide();
}else{
  $("input[value='call_status']").prop('checked', true);
}

if (!log_filters.includes('created_by')) {
  $('#createdbyfilter').hide();
}else{
  $("input[value='created_by']").prop('checked', true);
}

$('input[name="filter_checkbox"]').click(function(){  
  if($('#datecheckbox').is(":checked") || $('#calltypecheckbox').is(":checked") 
	|| $('#clientnamecheckbox').is(":checked") || $('#callstatuscheckbox').is(":checked") ||
  $('#createdbycheckbox').is(":checked")){ 
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
		
        if($('#calltypecheckbox').is(":checked")){
        $('#calltypefilter').show();
        $("#buttongroup").show();
        }
        else{
          $('#calltypefilter').hide();
          $("#buttongroup").hide();
        }
        

        if($('#clientnamecheckbox').is(":checked")){
          $('#clientnamefilter').show();
          $("#buttongroup").show();
        }
        else{
          $('#clientnamefilter').hide();
          $("#buttongroup").hide();
        }

        if($('#callstatuscheckbox').is(":checked")){
          $('#callstatusfilter').show();
          $("#buttongroup").show();
        }
        else{
          $('#callstatusfilter').hide();
          $("#buttongroup").hide();
        }

        if($('#createdbycheckbox').is(":checked")){
          $('#createdbyfilter').show();
        }
        else{
          $('#createdbyfilter').hide();
        }            
    });
})

function ticket_save_filter(){
var form_data = $("#log_filter").serialize();
$.ajax({
url: '<?=base_url()?>ticket/ticket_save_filter/log',
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
  
</script>