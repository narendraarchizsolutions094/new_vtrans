<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
    </div>
</div>

<div class="row" style="padding: 35px;">

	
	<div class="col-md-6" align="" style="border-right: 1px solid #f0f0f0;">
	<form action="" method="post">
		<div class="form-group" align="right">
			<button type="button" class="btn btn-sm btn-primary edit-btn" onclick="make_edit()"><i class="fa fa-edit"></i> Edit</button>
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
			<input type="" name="password" class="form-control fixed" value="<?=$row->password??''?>" required>
		</div>
		<div class="row">
			<div class="col-md-6" style="padding:0px">
				<div class="form-group">
					<label>Tickets Belongs to <font color="red">*</font></label>
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
			<div class="col-md-6" style="padding:0px;">
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
			</div>
		</div>
		<div class="form-group">
			<label>Email Template on Ticket Generation<font color="red">*</font></label><br>
			<span style="font-size: 10px;">Use <font color="red">@ticketno , @subject, @sender, <!-- @user --></font> for reference of Generated Ticket Number and Send Subject.</span>
			<textarea id="tmp" class="form-control fixed" onkeyup="live_tmp()" name="template" style="height: 200px;"><?php

			$breaks = array("<br />","<br>","<br/>");  
    echo str_ireplace($breaks, "\r\n", $row->template); 
			?></textarea>			
		</div>
		<div class="form-group save-btn" style="display: none;">
			<button class="btn btn-block btn-success"> <i class="fa fa-save"></i> Save</button>
		</div>
	</form>
	</div>
	
	<div class="col-md-6">
		<label>Email View</label><hr>
		<div class="form-group">
			<label>Subject</label>
				<input type="" class="form-control" name="" style="width: 100%; border-radius: 2px;" value="#TICKETNO Ticket Created with Subject 'SUBJECT' ">
		</div>
		<div class="form-group">
			<label>Body</label>
			<div id="view_box" class="form-control" style="min-height: 200px;"></div>
		</div>
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
try{
	live_tmp();
	function live_tmp()
	{	
		var user = $("select[name=belongs_to] option:selected").text();
		var content = $("#tmp").val();
		content = content.replace(/\n\r?/g, '<br />');
		content = content.replace('@ticketno','<span class="replaced">TICKETNO</span>');
		content = content.replace('@subject','<span class="replaced">SUBJECT</span>');
		content = content.replace('@sender','<span class="replaced">SENDER</span>');
		//content = content.replace('@user','<span class="replaced">'+user+'</span>');
		
		//alert(content);
		$("#view_box").html(content);
	}
}catch(e){alert(e)};

	<?php
	if(empty($row)){
		echo'make_edit();';
	}
	else
		echo'	
				$("input,select,textarea").attr("disabled","disabled");
	';
	?>

	function make_edit()
	{
		$(".edit-btn").hide();
		$(".save-btn").show();
		$(".fixed").removeClass('fixed');
		$("input,select,textarea").removeAttr('disabled');
	}
</script>