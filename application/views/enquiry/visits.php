<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
  <div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
          <?php
          if(user_access('1020'))
          {
          ?>
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Visit" title="Add Visit"></a> 
          <?php
          }
          ?>   
          <?php
if(empty($_COOKIE['visits_filter_setting'])) {
	$variable=[];
} else {
$variable=explode(',',$_COOKIE['visits_filter_setting']);
}
?>   

        </div>
        <div class="col-md-2" style="float: right;">
		    <div class="btn-group dropdown-filter">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filter by <span class="caret"></span>
              </button>              
              <ul class="filter-dropdown-menu dropdown-menu">   
                    <li>
                      <label>
                      <input type="checkbox" value="date" id="datecheckbox" name="filter_checkbox" <?php if(in_array('date',$variable)){echo'checked';} ?>> Date </label>
                    </li>  
                    <li>
                      <label>
                      <input type="checkbox" value="for" id="forcheckbox" name="filter_checkbox" <?php if(in_array('for',$variable)){echo'checked';} ?>> For</label>
                    </li> 
                    <li>
                      <label>
                      <input type="checkbox" value="rating" id="ratingcheckbox" name="filter_checkbox" <?php if(in_array('rating',$variable)){echo'checked';} ?>> Rating</label>
                    </li>                
                    <li class="text-center">
                      <a href="javascript:void(0)" class="btn btn-sm btn-primary " id='save_advance_filters' title="Save Filters Settings"><i class="fa fa-save"></i></a>
                    </li>                   
                </ul>                
            </div>
            <div class="btn-group" role="group" aria-label="Button group">
              <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Actions">
                <i class="fa fa-sliders"></i>
              </a>  
            <div class="dropdown-menu dropdown_css" style="max-height: 400px;overflow: auto; left: -136px;">
               <a class="btn" data-toggle="modal" data-target="#table-col-conf" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;">Table Config</a>                        
            </div>                                         
          </div>
		</div>
</div>


<div class="row" style=" margin: 15px 0px; padding: 15px; <?php if(empty($_COOKIE['visits_filter_setting'])){ echo'display:none'; }  ?>" id="filter_pannel">
<div id="datefilter" style="<?php if(!in_array('date',$variable)){echo'display:none';} ?>">
	<div class="col-lg-3"  >
        <div class="form-group">
          <label>From</label>
          <input class="v_filter form-control form-date" name="v_from_date" >
       
        </div>
    </div>

      <div class="col-lg-3" id="tofilter">
        <div class="form-group">
          <label>To</label>
           <input  class="v_filter form-control form-date" name="v_to_date" >
        </div>
      </div>
</div>
    <div class="col-lg-3" id="forfilter" style="<?php if(!in_array('for',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>For</label>
        	<select class="v_filter form-control" name="enquiry_id" >
        		<option value="">Select</option>
        		<?php
        		if(!empty($all_enquiry))
        		{
        			foreach ($all_enquiry as $row) 
        			{  
                $row  = (array)$row;
        				echo'<option value="'.$row['enquiry_id'].'">'.$row['name_prefix'].' '.$row['name'].' '.$row['lastname'].'</option>';
        			}
        		}
        		?>
        	</select>
        </div>
    </div>
     <div class="col-lg-3" id="ratingfilter" style="<?php if(!in_array('rating',$variable)){echo'display:none';} ?>">
        <div class="form-group">
        	<label>Rating</label>
       	<select class="form-control v_filter" name="rating">
              <option value="">Select</option>
              <option value="1 star">1 star</option>
              <option value="2 star"> 2 star</option>
              <option value="3 star"> 3 star</option>
              <option value="4 star"> 4 star</option>
              <option value="5 star"> 5 star</option>
            </select>
        </div>
    </div>
</div>
<script>
  $(document).ready(function(){
	 $("#save_advance_filters").on('click',function(e){
	  e.preventDefault();
	  var arr = Array();  
	  $("input[name='filter_checkbox']:checked").each(function(){
		arr.push($(this).val());
	  });        
	  setCookie('visits_filter_setting',arr,365);      
	  // alert('Your custom filters saved successfully.');
	  Swal.fire({
	position: 'top-end',
	icon: 'success',
	title: 'Your custom filters saved successfully.',
	showConfirmButton: false,
	timer: 1000
  });
	});


});
$('input[name="filter_checkbox"]').click(function(){  
  if($('#datecheckbox').is(":checked")||$('#forcheckbox').is(":checked")||$('#ratingcheckbox').is(":checked")){ 
    $('#filter_pannel').show();
  }else{
    $('#filter_pannel').hide();
  }
});
$('input[name="filter_checkbox"]').click(function(){              
        if($('#datecheckbox').is(":checked")){
         $('#datefilter').show();
        } else{
           $('#datefilter').hide();
             }
      
		if($('#forcheckbox').is(":checked")){
        $('#forfilter').show();
            }
        else{
          $('#forfilter').hide();
		}
		if($('#ratingcheckbox').is(":checked")){
        $('#ratingfilter').show();
            }
        else{
          $('#ratingfilter').hide();
		}
		
});
		

</script>
<br>
	<div class="row" >
	<div class="col-lg-12" >

				<table id="visit_table" class="table table-bordered table-hover mobile-optimised" style="width:100%;">
				      <thead>
				        <tr>
				          <th>#</th>
				          <th id="th-1">Visit Date</th>
				          <th id="th-2">Visit Time</th>
				          <th id="th-3">Name</th>
				          <th id="th-4">Distance Travelled</th>
				          <th id="th-5">Travelled Type</th>
				          <th id="th-6">Rating</th>
				          <th id="th-7">Next Visit Date</th>
                  <th id="th-10">Next Visit Time</th>
				          <th id="th-8">Next Visit Location</th>
                  <th id="th-9">Action</th>
				        </tr>
				      </thead>
				      <tbody>
		     		 </tbody>
    			</table>
	</div>
</div>

<script type="text/javascript">

var c = getCookie('visit_allowcols');

var Data = {"from_data":"","to_date":"","from_time":"","to_time":""};
$(".v_filter").change(function(){
  // var obj = $(".v_filter:input").serializeArray();

  // Data["from_date"]= obj[0]["value"];
  // Data["to_date"] = obj[1]["value"];
  // Data["from_time"] = obj[2]["value"];
  // Data["to_time"] = obj[3]["value"];
 $("#visit_table").DataTable().ajax.reload(); 
});

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
                      var obj = $(".v_filter:input").serializeArray();
                     d.from_date = obj[0]['value'];
                     d.from_time = '';//obj[1]["value"];
                     d.enquiry_id =obj[2]["value"];
                     d.rating = obj[3]["value"];
                     d.to_date = obj[1]['value'];
                     d.to_time = '';//obj[5]['value'];
                     d.view_all=true;

                    if(c && c!='')
                      d.allow_cols = c;

                     console.log(JSON.stringify(d));
                    return d;
              }
          },
  });

});

$("select").select2();

$(document).delegate('.visit-delete', 'click', function() {    
        var vid =  $(this).data('id'); 
        var ecode =  $(this).data('ecode');    
        //alert(ecode);  
        if(confirm('Are you sure?')){      
           $.ajax({
           url:"<?=base_url('enquiry/delete_visit')?>",
           type:"post",
           data:{
              vid:vid,
              enq_code:ecode,
            },
           success:function(res)
           { 
              $("#visit_table").DataTable().ajax.reload(); 
              Swal.fire('Visit Deleted!', '', 'success');
           }
           });
        }
     });  
</script>  


<div id="Save_Visit" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Visits</h4>
         </div>
         <div class="modal-body">
            <div class="row" >

<form action="<?=base_url('enquiry/add_visit')?>" class="form-inner" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">

                <div class="form-group col-md-6">
                    <label>Company</label>
                    <select class="form-control" name="company" onchange="filter_related_to(this.value)">
                      <option value="-1">Select</option>
                      <?php
                      if(!empty($company_list))
                      {
                        foreach ($company_list as $key =>  $row)
                        {
                          echo '<option value="'.$key.'">'.$row->company.'</option>';
                        }
                      }
                      ?>
                    </select>
                </div>

               <div class="form-group col-md-6">
                  <label>Related To (Primary Contact)</label>
                  <select class="form-control" name="enquiry_id">
                    <option value="">Select</option>
                    <?php
                  if(!empty($all_enquiry))
                  {
                    foreach ($all_enquiry as $row)
                    {
                      echo'<option value="'.$row->enquiry_id.'">'.$row->name.'</option>';
                    }
                  }
                    ?>
                  </select>
               </div>

                <div class="form-group col-md-6 visit-date col-md-6">     
          <label>Visit Date</label>
          <input type="date" name="visit_date" class="form-control">
        </div>
    
        <div class="form-group col-md-6 visit-time col-md-6">     
         <label>Visit Time</label>
          <input type="time" name="visit_time" class="form-control">
        </div>
    
        <div class="form-group col-md-6 distance-travelled col-md-6">     
        <label>Distance Travelled</label>
           <input type="text" name="travelled" class="form-control">
        </div>
    
        <div class="form-group col-md-6 distance-travelled-type col-md-6">      
        <label>DISTANCE TRAVELLED TYPE</label>
           <input type="text" name="travelled_type" class="form-control">
        </div>
    
        <div class="form-group col-md-6 customer-rating col-md-6">      
        <label>Customer Rating</label>
          <select class="form-control" name="rating">
              <option value="">Select</option>
              <option value="1 star">1 star</option>
              <option value="2 star"> 2 star</option>
              <option value="3 star"> 3 star</option>
              <option value="4 star"> 4 star</option>
              <option value="5 star"> 5 star</option>
            </select>
        </div>
        
         
      <div class="col-md-12">
      <label style="color:#283593;">Next Visit Information<i class="text-danger"></i></label>
       <hr>
      </div>
        
          <div class="form-group col-md-6 next-visit-date col-md-6">      
            <label>Next Visit Date</label>
             <input type="date" name="next_visit_date" class="form-control">
          </div>
      
          <div class="form-group col-md-6 next-visit-location col-md-6">      
           <label>Next Visit Location</label>
             <input type="text" name="next_location" class="form-control">
          </div>
                  
         <div class="row" id="save_button">
            <div class="col-md-12 text-center">
               <input type="submit" name="submit_only" class="btn btn-primary" value="Save">
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
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="1">  Visit Date</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="2">  Visit Time</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="3">Name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="4">  Distance Travelled</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="5">  Distance Travelled Type</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="6">  Rating</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="7">Next Visit Date</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="10">Next Visit Time</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="8">  Next Visit Location</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="9">  Action</label>
            </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-success" onclick="save_table_conf()"><i class="fa fa-save"></i> Save</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var LIST = <?php echo !empty($company_list)? json_encode($company_list): '{}'?>;
  var OLD_LIST  = <?=!empty($all_enquiry) ? json_encode($all_enquiry):'{}'?>;
  function filter_related_to(v)
  {
      if(Object.keys(LIST).length>0 && v!='-1')
      { 
        var l = '';
        var y = LIST[v];
        var ids = y.enq_ids.split(',');
        var names = y.enq_names.split(',');
        $(ids).each(function(k,id){
            l+="<option value='"+id+"'>"+names[k]+"</option>";
        });
        //alert(l);
        $("select[name=enquiry_id]").html(l);
      }
      else
      { var l = '';
          $(OLD_LIST).each(function(k,v){
            l+="<option value='"+v.enquiry_id+"'>"+v.name_prefix+" "+v.name+" "+v.lastname+"</option>";
          });
          $("select[name=enquiry_id]").html(l);
      }
  }


function save_table_conf()
{
      var x = $(".choose-col:checked");
      var Ary = new Array();
      $(x).each(function(k,v){
        Ary.push(v.value);
      });
      var list = Ary.join(',');
      document.cookie = "visit_allowcols="+list+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
      Swal.fire({
        title:'Table Configuration Saved.',
        icon:'success',
        type:'success',

      });
      location.reload();
}

if(c && c!='')
{ 
    var z = c.split(',');

    if($('.choose-col').length == z.length)
        $('#selectall').prop('checked',true);

    $("th[id*=th-").addClass('rmv');
    $(z).each(function(k,v){
        $('.choose-col[value='+v+']').prop('checked',true);
        $('#th-'+v).removeClass('rmv');
     });
    $('.rmv').remove();
}
else
{
  $('.choose-col').prop('checked',true);
  $('#selectall').prop('checked',true);

}

$("#selectall").click(function(){
    if(this.checked)
    {
      $('.choose-col').prop('checked',true);
    }
    else
    {
      $('.choose-col').prop('checked',false);
    }
});

$('.choose-col').change(function(){
    if($('.choose-col').length == $('.choose-col:checked').length)
        $('#selectall').prop('checked',true);
    else
      $('#selectall').prop('checked',false);
});
</script>