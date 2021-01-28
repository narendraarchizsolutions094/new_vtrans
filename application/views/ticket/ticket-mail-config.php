<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
    </div>
</div>

<?php

if(empty($this->session->process) || count($this->session->process)!=1 || !$email_integration)
{
	if(empty($email_integration))
		echo'<div class="alert alert-danger">Please Configure EMAIL Api first for sending Email(s).<br><br>
	<small><code>API Configuration > Email > Add New Integration</code></small>
	</div>';
	else
	echo'<div class="alert alert-danger">Please Select Only 1 process.</div>';

}
else
{
?>

<div class="row" id="tick_config" style="padding: 35px;">

	<!-- <div class="col-lg-12">
		<table class="table table-bordered table-responsive">
			<thead>
				<tr>
					<th>#</th>
					<th>Hostname</th>
					<th>Username</th>
					<th>Password</th>
					<th>Ticket Created By</th>
					<th>Ticket Process</th>
					<th>Template</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($res as $row)
				{
					echo'<tr>
					<td>'.$row->hostname.'</td>
					<td>'.$row->username.'</td>
					<td>'.$row->password.'</td>
					<td>'.$row->belongs_to.'</td>
					<td>'.$row->process.'</td>
					<td>'.$row->template.'<td>
				</tr>';
				}
				?>
			</tbody>
		</table>
	</div> -->



<div class="col-md-10 col-md-offset-1" align="" style=" padding-bottom: 30px;/*border-right: 1px solid #f0f0f0;*/">
	<form action="" method="post">
		<div class="form-group edit-btn">
			<div class="row">
			<div class="col-sm-6 col-lg-6 col-xs-6" align="left" style="padding-left: 0px; ">
				<label>Next Hit : <label class="label label-primary"><?=!empty($row->next_hit)?(date('d-M-Y h:i A',strtotime($row->next_hit))):'';?></label></label>
			</div>
			<div class="col-sm-6 col-lg-6 col-xs-6" align="right">
				<button type="button" class="btn btn-sm btn-primary" onclick="make_edit()"><i class="fa fa-edit"></i> Edit</button>
			</div>
			</div>
		</div>
		<div class="form-group">
			<label>Hostname <font color="red">*</font></label>
			<input type="" name="hostname" class="form-control fixed" value="<?=$row->hostname??''?>" required>
		</div>
		<div class="form-group">
			<label>Username <font color="red">*</font></label>
			<input type="" name="username" class="form-control fixed" value="<?=$row->username??''?>" required>
		</div>
		<div class="form-group">
			<label>Password <font color="red">*</font></label>
			<input type="password" name="password" class="form-control fixed" value="<?=$row->password??''?>" required>
		</div>
		<div class="row">
			<div class="col-md-12" style="padding:0px">
				<div class="form-group">
					<label>Tickets Created By <font color="red">*</font></label>
					<select name="belongs_to" class="form-control fixed" required>
						<?php
						if(!empty($user_list))
						{
							foreach ($user_list as $key => $user)
							{
								echo'<option value="'.$user->pk_i_admin_id.'" '.($row->belongs_to==$user->pk_i_admin_id?'selected':'').'>'.$user->s_display_name.'</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- <div class="col-md-6" style="padding:0px;">
				<div class="form-group">
					<label>Tickets Process <font color="red">*</font></label>
					<select name="process_id" class="form-control fixed" required>
						<?php
						if(!empty($process_list))
						{
							foreach ($process_list as $key => $p)
							{
								echo'<option value="'.$p->sb_id.'" '.($row->process_id==$p->sb_id?'selected':'').'>'.$p->product_name.'</option>';
							}
						}
						?>
					</select>
				</div>
			</div> -->
		</div>
		<div class="form-group">
			<label>Email Template on Ticket Generation<font color="red">*</font></label><br>
			<div style="font-size: 10px; padding: 5px">Use <font color="red">@ticketno , @subject, @sender,</font> for reference of Generated Ticket Number and Send Subject.</div>

			<select name="temp_id" class="form-control fixed select2" required>
						<?php
						if(!empty($tmp_list))
						{
							foreach ($tmp_list as $key => $tmp_row)
							{
								echo'<option value="'.$tmp_row->temp_id.'" '.($row->template==$tmp_row->temp_id?'selected':'').'>'.$tmp_row->template_name.'</option>';
							}
						}
						?>
			</select>
		</div>

		<div class="form-group">
			<label>Email Fetch In Every <font color="red">*</font></label>
			
				<select name="fetch_time" class="form-control" style="border-radius: 0px!important;">
					<?php
					for($i=20;$i<=59;$i++)
						echo'<option value="'.$i.'" '.($i==$row->fetch_time?'selected':'').'>'.$i.' Minutes</option>';
					for($i=1;$i<=4;$i++)
					{	echo'<option value="'.(60*$i).'"  '.((60*$i)==$row->fetch_time?'selected':'').'>'.$i.' Hour</option>';
						for($j=1;$j<=59;$j++)
						echo'<option value="'.((60*$i)+$j).'"  '.(((60*$i)+$j)==$row->fetch_time?'selected':'').'>'.$i.' Hour '.$j.' Minutes</option>';
					}
						
					?>
				</select>

		</div>

		<div class="form-group">
			<label>Status</label><br>
			<input type="radio" name="status" value="1" <?=$row->status?'checked':''?>> Active &nbsp; &nbsp; 
			<input type="radio" name="status" value="0" <?=$row->status?'':'checked'?>> In-active 
		</div>
		<div class="form-group save-btn" style="display: none;">
			<button class="btn btn-block btn-success"> <i class="fa fa-save"></i> Save</button>
		</div>
	</form>
	</div>


</div>

<style type="text/css">
	.content
	{
		min-height: 620px;
	}

	.fixed{
	border: 0px;
    border-bottom: 1px dashed gray;
    border-radius: 0px;
    background: white!important;
	}
.replaced
{
	font-weight: 700;
	color:green;
	font-style: italic;;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
$(".select2").select2();
});
	
// try{
// 	live_tmp();
// 	function live_tmp()
// 	{	
// 		var user = $("select[name=belongs_to] option:selected").text();
// 		var content = $("#tmp").val();
// 		content = content.replace(/\n\r?/g, '<br />');
// 		content = content.replace('@ticketno','<span class="replaced">TICKETNO</span>');
// 		content = content.replace('@subject','<span class="replaced">SUBJECT</span>');
// 		content = content.replace('@sender','<span class="replaced">SENDER</span>');
// 		//content = content.replace('@user','<span class="replaced">'+user+'</span>');
		
// 		//alert(content);
// 		$("#view_box").html(content);
// 	}
// }catch(e){alert(e)};

	<?php
	if(empty($row)){
		echo'make_edit();';
	}
	else
		echo'	
				$("#tick_config").find("input,select,textarea").attr("disabled","disabled");
	';
	?>

	function make_edit()
	{
		$(".edit-btn").hide();
		$(".save-btn").show();
		$(".fixed").removeClass('fixed');
		$("#tick_config").find("input,select,textarea").removeAttr('disabled');
	}
</script>
<!-- 			<textarea id="tmp" class="form-control fixed" onkeyup="live_tmp()" name="template" style="height: 200px;"><?php
			if(!empty($row->template))
			{
			$breaks = array("<br />","<br>","<br/>");  
    echo str_ireplace($breaks, "\r\n", $row->template); 
			}
			?></textarea> -->
<?php
}
?>