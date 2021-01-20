<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a> 
    </div>
</div>

<div class="row" style="padding: 35px;">

	<form action="" method="post">
	<div class="col-md-6 col-md-offset-3" align="">
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
		<div class="form-group save-btn" style="display: none;">
			<button class="btn btn-block btn-success"> <i class="fa fa-save"></i> Save</button>
		</div>
	</div>
	</form>
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

</style>
<script type="text/javascript">
	<?php
	if(empty($row)){
		echo'make_edit();';
	}
	else
		echo'	
				$("input,select").attr("disabled","disabled");
	';
	?>

	function make_edit()
	{
		$(".edit-btn").hide();
		$(".save-btn").show();
		$(".fixed").removeClass('fixed');
		$("input,select").removeAttr('disabled');
	}
</script>