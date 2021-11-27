<div class="col-md-6 col-sm-12 card card-body col-height details-column" style="border: 1px solid #c8ced3;padding: 15px;border-top: none;">
<div class="exTab3">
	<ul  class="nav nav-tabs" role="tablist"> 

		<span class="scrollTab" style="position: absolute; left: 0; font-size: 22px; line-height: 40px; z-index: 999"><i class="fa fa-caret-left" onclick="tabScroll('left')"></i></span>    

		<li class="active"><a  href="#basic" data-toggle="tab" style="padding: 10px 10px; ">Basic</a></li> 
		
		 <?php
		 //print_r($tab_list);
            if(!empty($tab_list)){
                //print_r($tab_list);die;
                foreach ($tab_list as $key => $value) 
                { 
                  if ($value['primary_tab'] != 1) 
                  	{ ?>
                    <li><a href="#<?=str_replace(' ', '_', $value['title'])?>" data-toggle="tab" style="padding: 10px 10px;"><?=$value['title']?></a></li>
                   <?php
               		}
               	}
            }

          ?>
		  <li class=""><a  href="#related_tickets" data-toggle="tab" style="padding: 10px 10px; ">Related <?=display('ticket')?></a></li>   


           <span class="scrollTab" style="position: absolute; right: 0; font-size: 22px; line-height: 40px; z-index: 999"><i class="fa fa-caret-right"  onclick="tabScroll('right')"></i></span>

	</ul>
	<div class="tab-content clearfix">
        <div class="tab-pane active" id="basic">
       <?php echo form_open_multipart(base_url("ticket/update_ticket/".$ticket->ticketno)); ?>
			<div class="row">

				<div id="basic_field_data"></div>

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12" style ="background: #f7f7f7;border: 1px solid #ccc;padding: 15px;border-radius: 10px;margin-bottom:25px; display: none;">
				
					<div class="row">
					<div class = "col-md-12">
						<div class = "form-group">
							<label>Review</label>
							<textarea class = "form-control" name = "review"><?php echo (!empty($ticket->review)) ? $ticket->review : "";  ?></textarea>
						</div>
					</div>
					<div class="col-md-4">
							<div class="form-group">
								<label><?=display('ticket_problem')?> </label>
								<select class="form-control" name = "issue">
									<option value = "">-- Select --</option>
								<?php if(!empty($problem)) {
										foreach($problem as $ind => $prblm){
											?><option value = "<?php echo $prblm->id; ?>" <?php echo ($ticket->category == $prblm->id) ? "selected" : "" ?>><?php echo $prblm->subject_title; ?></option><?php
										}	
									} ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Solution </label>
								<select class="form-control" name = "solution">
									<option value = "">-- Select --</option>
									<option value = "1"  <?php echo ($ticket->solution == 1) ? "selected" : "" ?>>Done</option>
									<option value = "2" <?php echo ($ticket->solution == 2) ? "selected" : "" ?>>Proccess</option>
									<option value = "3"  <?php echo ($ticket->solution == 3) ? "selected" : "" ?>>Defer</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Status </label>
								<select class="form-control" name = "status">
									<option value = "">-- Select --</option>
									<option value = "0" <?php echo ($ticket->status == 0) ? "selected" : "" ?>>Unread</option>
									<option value = "1" <?php echo ($ticket->status == 1) ? "selected" : "" ?>>Proccess</option>
									<option value = "2" <?php echo ($ticket->status == 2) ? "selected" : "" ?>>Read</option>
									<option value = "3" <?php echo ($ticket->status == 3) ? "selected" : "" ?>>Dropped</option>
								</select>
							</div>
						</div>
					</div>
					<div class="text-center">					
						<input type ="hidden" name = "ticketno" value = "<?php echo $ticket->id; ?>">
						<input type ="hidden" name = "client" value = "<?php echo $ticket->client; ?>">
						<!-- <button type = "submit" class="btn btn-success">Update</button> -->
					</div>
					
				</div>
				<br>
				<center><button type = "submit" class="btn btn-success">Update</button></center>
			</div>	
		</div>	
	  </div>
	  <?=form_close()?>
	</div>
	<div class="tab-pane" id="related_tickets">
		<div class="row">
		<?php
		if(!empty($related_tickets))
		{
			echo'<table class="table table-bordered">
			<tr>
			'.($this->session->companey_id==65?'<th>'.display('tracking_no').'</th>':'').'
			<th>Ticket Number</th>
			<th>Name</th>
			<th>Type</th>
			<th>Status</th>
			<th>Action</th>
			</tr>';
				foreach ($related_tickets as $row)
				{ 
					$status = $row->ticket_status_name??'Open';
					//print_r($row); exit();
				echo'<tr>

					'.($this->session->companey_id==65?('<td>'.($row->tracking_no==''?'NA':$row->tracking_no).'</td>'):'').'
					<td>'.$row->ticketno.'</td>
					<td>'.$row->name.'</td>
					<td>'.($row->complaint_type=='1'?'Compaint':($row->complaint_type=='2'?'Query':'NA')).'</td>
					<td>'.$status.'</td>
					<td><a href="'.base_url('ticket/view/'.$row->ticketno).'"><button class="btn btn-small btn-primary">View</button></a></td>
					</tr>';
				}	
			echo'</table>';
		}else 
		{
			echo'<div class="alert alert-danger">No Related Ticket found.</div>';
		}
		?>
		</div>
	</div>

	 <?php
	   if(!empty($tab_list))
	   {
	    
	   	 foreach ($tab_list as $key => $value) { ?>
	      <div class="tab-pane" id="<?=str_replace(' ', '_', $value['title'])?>">
	      <?php
	      if ($value['id'] != 1) {
	        echo tab_content($value['id'],$this->session->companey_id,$ticket->ticketno,$value['title'],2); 
	      }
	      ?>
	      </div>
	      <?php
	    }
	    ?>
	    <script type="text/javascript">
      		function edit_dynamic_query(t)
      		{
      			var tab_id = $(t).data('tab-id');
      			var cmnt_id = $(t).data('cmnt');
      			var ticket = $(t).data('ticket');
      			var comp_id = $(t).data('comp-id');
      			var tabname = $(t).data('tab-name')
      			if(cmnt_id!='')
      			{
      				$.ajax({
      					url:'<?=base_url('ticket/edit_query_data')?>',
      					data:{cmnt_id:cmnt_id,tab_id:tab_id,ticket:ticket,comp_id:comp_id,tabname:tabname,task:'view'},
      					type:'post',
      					success:function(res)
      					{
      						Swal.fire({
      							title:'Edit '+tabname,
      							html:res,
      							with:'100%',
      							showConfirmButton:false,
      							showCancelButton:true,
      							cancelButtonText:'Close',
      							cancelButtonColor:'#E5343D'
      						});
      					},
      					error:function(u,v,w)
      					{
      						alert(w);
      					}
      				});
      			}
      			
      		}
      	</script>


	    <?php
	  }
	  ?>
	
  </div>
</div>
</div>
 <style>
 	 .col-height{
    min-height: 700px;
    max-height: 700px;
    overflow-y: auto;
    border-bottom: solid #c8ced3 1px;
  }
		.nav-tabs
        {
         overflow-x: hidden;
         overflow-y:hidden;
         white-space: nowrap;
         height: 50px;
        }
        .nav-tabs > li
        {
           white-space: nowrap;
           float: none;
           display: inline-block;
           font-size: 11px;
           background-color: #283593;
        }

		.nav-tabs > li.active > a {
		    color: #555 !important;
		    background-color: #fff;
		}
        .nav-tabs > li > a {
         border-radius: 4px 4px 0 0 ;
         color: #fff!important;
         }
         #exTab3 .tab-content {
         /*color : white;*/
         background-color: #fff;
         padding : 5px 15px;
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
         .active .badge{color: white!important;}
      </style>
<script>
	 manageScroll();
function manageScroll()
{
  if($(".nav-tabs")[0].scrollWidth > $(".nav-tabs")[0].clientWidth)
            {
              $(".scrollTab").show();
            }
            else
            {
               $(".scrollTab").hide();
            }
}

$(window).resize(function(){
  manageScroll();
});

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

<!-- jquery-ui js -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>" type="text/javascript"></script>      
<!-- DataTables JavaScript -->
<script src="<?php echo base_url("assets/datatables/js/dataTables.min.js") ?>"></script>  
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>
<script>

function get_basic_field() {
	//alert("c");
    var process_id = "<?= $process_id ?>";
   
    var url = "<?= base_url() . 'form/form/get_basic_field_by_process_update/'.$ticket->ticketno ?>";
    $.ajax({
      type: "POST",
      url: url,
      data: {
        'process_id': process_id,
        field_for:'2'
      },
      success: function(data) {
      	//alert(data);
        $("#basic_field_data").html(data);
        // $("#fcity").select2();
        // $("#fstate").select2();
        get_custom_field();
      },
     error:function(u,v,w)
     {
     	alert(w);
     }
    });
    disableForm();
  }

function get_custom_field() { 
    var process_id = "<?= $process_id ?>"; 
    var url = "<?= base_url() . 'form/form/get_custom_field_in_basic' ?>";
    $.ajax({
      type: "POST",
      url: url,
      data: {
        'process_id': process_id,
        'field_for': 2,
        'primary_tab': <?=$primary_tab?>,
        'ticketno': "<?=$ticket->ticketno?>"
      },
      success: function(data) 
      {
      	//alert(data);
        $("#basic_field_data").append(data);
        disableForm();
        //hide_all_dependent_field();
      }
    });
    
  }

get_basic_field();

	$(document).ready(function(){
		
		$(".add-date-picker").datepicker({
			 format: 'yyyy/mm/dd',
			 startDate: '-7d'
		});	
	});
	$(document).on("change", ".choose-client", function(){	

		$.ajax({
			url     : "<?php echo base_url('ticket/loadinfo'); ?>",
			type    : "post",
			data    : {clientno : $(this).val()},
			success : function (resp){
				var jdata = JSON.parse(resp);
				$("input[name=name]").val(jdata.name);
				$("input[name=email]").val(jdata.email);
			}
		})
	});
function tabchange(t,key)
{
	$(".btn").removeClass("active");
	if(key)
	{
		
		$(t).addClass('active');
		$("#old_tickets").show();
		$("#ticket_details").hide();
	}
	else
	{
		$(t).addClass('active');
		$("#old_tickets").hide();
		$("#ticket_details").show();
	}
}
function disableForm()
{
<?php
if(!user_access('311'))
{
?>

$(document).find('input,select,textarea').attr('disabled','disabled');
$(document).find('form').removeAttr('action');
$(document).find('button[type=submit],input[type=submit]').remove();
<?php
}
?>
}
</script>