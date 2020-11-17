<link href="<?= base_url('assets/datatables/css/dataTables.min.css') ?>" rel="stylesheet" type="text/css"/>  
        <script src="<?php echo base_url('assets/js/jquery.min.js') ?>" type="text/javascript"></script>
	<style>
		#waranty-start,#waranty-end
		{
			line-height:40px;
		}
	</style>

	<?php
	if(!$invalid_process)
	{	
	?>
       	<div class="row">
				 <div class="panel panel-default pt-2"> 
				<div class="panel-heading no-print" style ="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
							<div class="row">
					<div class="col-md-12">
						<a href="<?=base_url('ticket/index')?>" class="btn btn-success"> <i class="fa fa-list"></i> Ticket List 
						</a>
					</div>
				</div>
				</div>
				<div class="panel-body">
				<div class="col-md-2"></div>

				<div class="col-md-8 panel-default panel-body" style ="border:1px solid #f7f7f7">
				<?php echo form_open_multipart(base_url("ticket/add")); ?>
			<div class="row">

				<div id="process_basic_fields">

				</div>

				<div class = "col-md-12 text-center">
					<button class="btn btn-success" type="submit">Save</button>
				</div>
			</div>
				<?php echo form_close(); ?>
				<div class = "row">
					<div class = "col-md-12" id = "oldticket">
					</div>
				</div>
			</div>
			</div>
			</div>
        </div>
    <?php
    }
    else
    {
    	echo'<br><br>
    	<div class="container">
            <div class="alert alert-danger">
              <strong>Please Select one process in which you want to create Ticket</strong>
            </div>
          </div>';
    }
    ?>


<div id="old_ticket" class="modal" role="dialog" style="display:none;">
  <div class="modal-dialog">

     <div class="modal-content">
        
        <div class="modal-body">
        	<h2><center>Old Tickets</center></h2>
        	<div class="old_ticket_data">
         </div>
        <div class="modal-footer">
        	<button type="button" class="btn btn-success" onclick="$('#old_ticket').hide()">New Ticket </button>
          <button type="button" class="btn btn-danger" onclick="$('#old_ticket').hide(),$('input[name=tracking_no]').val('')">Cancel</button>
        </div>
      </div>
     
  </div>
</div>

<div id="no_match" class="modal" role="dialog" style="display:block;">
  <div class="modal-dialog">

     <div class="modal-content">
        
        <div class="modal-body">
        	<h2><center>Old Tickets</center></h2>
        	<div class="old_ticket_data">
         </div>
        <div class="modal-footer">
        	<button type="button" class="btn btn-success" onclick="$('#old_ticket').hide()">New Ticket </button>
          <button type="button" class="btn btn-danger" onclick="$('#old_ticket').hide(),$('input[name=tracking_no]').val('')">Cancel</button>
        </div>
      </div>
     
  </div>
</div>

  <style type="text/css">
  	.swal-width-custom{
  		width: 600px;
  		max-width: 100%!important;
  		overflow: auto;
  	}
  </style>     
<!-- jquery-ui js -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script>      
<!-- DataTables JavaScript -->
<script src="<?php echo base_url("assets/datatables/js/dataTables.min.js") ?>"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>  
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>
<script>
 $(function() {
    var process_id = "<?= $process_id ?>";
    if (process_id) {
      get_basic_field();
    }
  });

  function get_basic_field() {
    var process_id = "<?= $process_id ?>";
    var para = '';
    if ("<?= !empty($_GET['phone']) ? $_GET['phone'] : '' ?>" != "") {
      para = "?phone=<?= !empty($_GET['phone']) ? $_GET['phone'] : '' ?>";
    }
    var url = "<?= base_url() . 'form/form/get_basic_field_by_process' ?>" + para;
    $.ajax({
      type: "POST",
      url: url,
      data: {
        'process_id': process_id,
        field_for:'2'
      },
      success: function(data) {

        $("#process_basic_fields").html(data);
        // $("#fcity").select2();
        // $("#fstate").select2();
         get_custom_field();
      },
     error:function(u,v,w)
     {
     	alert(w);
     }
    });
  }

 function get_custom_field() { 
    var process_id = "<?= $process_id ?>";
    var url = "<?= base_url() . 'form/form/get_custom_field_by_process' ?>";
    $.ajax({
      type: "POST",
      url: url,
      data: {
        'process_id': process_id,
        'field_for': 2,
        'primary_tab': <?=$primary_tab?>
      },
      success: function(data) {
        $("#process_basic_fields").append(data);
        //hide_all_dependent_field();
      }
    });
  }


	$(document).on("change", ".choose-client", function(){		
		$.ajax({
			url     : "<?php echo base_url('ticket/loadinfo'); ?>",
			type    : "post",
			data    : {clientno : $(this).val()},
			success : function (resp){
				var jdata = JSON.parse(resp);
				$("input[name=name]").val(jdata.name);
				$("input[name=email]").val(jdata.email);
				$("input[name=phone]").val(jdata.phone);
			}
		})
	});
	$(document).on("change", ".chg-product", function(){
		$.ajax({
				url     : "<?php echo base_url('ticket/loadamc') ?>",
				type    : "post",
			    data    : {product: $(this).val(), client : $(".choose-client").val()},
				success : function(resp){
					//alert(resp);
					var jresp = JSON.parse(resp);
					if(jresp.status == "found"){
						$("#waranty-start").html("<b>AMC From : </b>"+jresp.from_date+"<br />");
						$("#waranty-end").html("<b>AMC To : </b>"+jresp.to_date+"<br />");
					}else{
						$("#waranty-start").html('');
						$("#waranty-end").html("");
					}
				}
		})
		$("#oldticket").load("<?php echo base_url('ticket/loadoldticket') ?>/"+$(this).val())
	});
</script>
<script>
	$(document).ready(function(){
		
		$(".add-date-picker").datepicker({
			 format: 'yyyy/mm/dd',
			 startDate: '-7d'
		});	

	});


function autoFill(find_by,key)
{ //alert(find_by);
	if(key=='') return;
	$.ajax({
		url:'<?= base_url('ticket/autofill')?>',
		type:'post',
		data:{key:key,find_by:find_by},
		dataType:'JSON',
		success:function(res) 
		{	
			if(res.status=='1')
			{	//alert(JSON.stringify(res));
				//alert(find_by);
				if(find_by!='email')
					$("input[name=email").val(res.email);
				if(find_by!='phone')	
					$("input[name=phone]").val(res.phone);
				
				$("input[name=name]").val(res.name);
				
				$("select[name=client]").find('option[value='+res.problem_for+']').attr("selected","selected");
				//alert(res.html);
				if(res.html!='0')
				{
					Swal.fire({
						title:'Previous Tickets',
						html:res.html,
						customClass:'swal-width-custom',
						//showCancelButton: true,
						cancelButtonText: 'Ok'
					});
				}
			}
		},
		error:function(u,v,w)
		{
			alert(w);
		}
	});
}

<?php

if(!empty($_GET['phone']))
{
	echo'autoFill("phone","'.$_GET['phone'].'"); 
	';
}	

?>
</script>