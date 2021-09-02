<div class="row" style="padding:15px;">
	<div class="col-lg-12" style="padding:10px; border: 1px dashed #cecece; border-radius: 4px;"> 
		<div class="col-lg-4">
			<div class="form-group"> 
		        <label>For</label><br>
		        <div class="form-control">
		        	<?=$details->name_prefix.' '.$details->name.' '.$details->lastname?>
		        </div>
				<input type="hidden" name="for" value="<?=$details->enquiry_id?>">
		    </div>
		</div>
		<div class="col-lg-2">
			<label>Deal Type</label>
	        <select class="form-control" name="deal_type" onchange="{
	        	$('input[name=deal_type]').val(this.value);
	        }" multiple>
	            <?php
		        $d_array = array();
		        if(!empty($deal->deal_type))
		        {
		        	$d_array = explode(',', $deal->deal_type);
		        }
		        ?>
		        <option value="domestic" <?=in_array('domestic',$d_array)?'selected':''?>>Domestic</option>
	            <option value="saarc" <?=in_array('saarc',$d_array)?'selected':''?>>Saarc</option>
	        </select>
		</div>
		<div class="col-lg-2">
			<div class="form-group"> 
		        <label>Booking Type</label>
		        <select class="form-control" name="booking_type" id="booking_type" onchange="set_type(this)">
				<option>#-Select Here</option>
		            <?php
		        	if($deal->booking_type=='sundry')
		        	{
		        	?>
		            <option value="sundry" <?=$deal->booking_type=='sundry'?'selected':''?>>Sundry</option>
		            <?php
		        	}else
		        	{?>
		            <option value="ftl" <?=$deal->booking_type=='ftl'?'selected':''?>>FTL</option>
		            <?php
		        	}
		        	?>
		        </select>
		    </div>
		</div>
		<div class="col-lg-2">
			<label>Business Type</label>
	        <select class="form-control" name="business_type" id="business_type">
			<option>#-Select Here</option>
	            <option value="in"<?=$deal->business_type=='in'?'selected':''?>>Inward</option>
	            <option value="out"<?=$deal->business_type=='out'?'selected':''?>>Outward</option>
	        </select>
		</div>
		<div class="col-lg-2">
			<label>Insurance</label>
	        <select class="form-control" name="insurance" id="insurance">
			<option>#-Select Here</option>
	            <option value="carrier" <?=$deal->insurance=='carrier'?'selected':''?>>Carrier</option>
                <option value="owner" <?=$deal->insurance=='owner'?'selected':''?>>Owner risk</option>
	        </select>
		</div>
	</div>
</div>

<div class="row" style="padding:15px;">
	<div class="col-lg-6">
		<div class="form-group"> 
	        <label>Type <font color="red">*</font></label>
			<select class="form-control" name="btype" onchange="load_branch(this)" data-type="booking">
				<option value="branch" <?=$deal->btype=='branch'?'selected':''?>>Branch</option>
				<option value="zone" <?=$deal->btype=='zone'?'selected':''?>>Zone</option>
				<option value="area" disabled>Area</option>
			</select>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="form-group"> 
	        <label>Type <font color="red">*</font></label>
			<select class="form-control" name="dtype" data-type="delivery" >
				<!-- onchange="load_branch(this)"  -->
				<option value="branch" <?=$deal->dtype=='branch'?'selected':''?>>Branch</option>
				<option value="zone" <?=$deal->dtype=='zone'?'selected':''?>>Zone</option>
				<option value="area" disabled>Area</option>
			</select>
		</div>
	</div>
</div>

<div class="row panel-box">
<?php
$did=1;
$type=$deal->btype;
$this->load->model('Branch_model');
$region_list = $this->Branch_model->sales_region_list()->result();

$list = $this->db->query("SELECT deal_data.*,GROUP_CONCAT(deal_data.delivery_branch) as dlist, book.area_id barea, book.region_id bregion ,del.area_id darea, del.region_id dregion FROM `deal_data` left join branch book on book.branch_id=deal_data.booking_branch  left join branch del on del.branch_id=deal_data.delivery_branch where deal_id=".$deal->id." GROUP by deal_data.booking_branch")->result();

/* echo '<pre>';
print_r($list);exit;
echo '</pre>'; */

foreach($list as $row)
{
    echo'<div class="row" style=" margin-bottom: 20px;
    border-bottom: 1px solid #d6d6d6; padding-bottom:20px;">
            <div class="col-lg-6">
                <div class="form-group">';
                if($type=='branch')
                {
                echo'<div class="col-md-6">
                            <label>Region</label>
                            <select data-did="'.$did.'" name="b_region" id="b_reg'.$did.'" class="form-control" onchange="load_areas(this)">
                                <option value="">Select Region</option>';

                            if(!empty($region_list))
                            {
                                foreach ($region_list as $reg)
                                {
                                    echo'<option value="'.$reg->region_id.'" '.($row->bregion==$reg->region_id?'selected':'').'>'.$reg->name.'</option>';
                                }
                                
                            }
                        
                    echo'</select>
                    </div>
                     <div class="col-md-6">
                        <label>Area</label>
                        <select class="form-control" name="barea" id="b_area'.$did.'" data-did="'.$did.'"  onchange="load_branch_particular(this)">';

                        $res =	$this->Branch_model->sales_area_list(0,array('sales_area.region_id'=>$row->bregion))->result();
						if(!empty($res))
						{	echo'<option value="">Select Area</option>';
							foreach ($res as $key => $value) {
								echo'<option value="'.$value->area_id.'" '.($value->area_id==$row->barea?'selected':'').'>'.$value->area_name.'</option>';
							}
						}

                        echo'</select>
                    </div>';
                }
                    echo'<div class="col-md-12">
                        <label>Booking From <font color="red">*</font></label>
                        <select id="bbranch'.$did.'" name="bbranch['.$did.']" class="form-control booking_from" required onchange="generate_table()" data-close-on-select="false">';
                        if($type=='zone')
                        {
                            //$zones =   $this->Branch_model->zone_list()->result();
                            $zones =	$this->Branch_model->zone_list(0,"zones.zone_id IN (".$row->booking_branch.")")->result();
                            foreach ($zones as $key => $value)
                            {
                                echo'<option value="'.$value->zone_id.'" '.($value->zone_id==$row->booking_branch).'>'.$value->name.'</option>';
                            }
                        }
                        else
                        {
                        	$res =	$this->Branch_model->branch_list(0,array('branch.area_id'=>$row->barea))->result();
							if(!empty($res))
							{	
								foreach ($res as $key => $value) {
									echo'<option value="'.$value->branch_id.'" '.($value->branch_id==$row->booking_branch?'selected':'').'>'.$value->branch_name.'</option>';
								}
							}
                        }
                    echo'</select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">';
            if($type=='branch')
            {
                echo'<div class="col-md-6">
                        <label>Region</label>
                        <select name="region" id="d_reg'.$did.'" data-did="'.$did.'" class="form-control" onchange="load_areas(this)">
                            <option value="">Select Region</option>';

                            if(!empty($region_list))
                            {
                                foreach ($region_list as $reg)
                                {
                                    echo'<option value="'.$reg->region_id.'" '.($row->dregion==$reg->region_id?'selected':'').'>'.$reg->name.'</option>';
                                }
                                
                            }
                    
                echo'</select>
                    </div>
                    <div class="col-md-6">
                        <label>Area</label>
                        <select id="d_area'.$did.'" data-did="'.$did.'" class="form-control" name="area"  onchange="load_branch_particular(this)">';
                        $resda =	$this->Branch_model->sales_area_list(0,array('sales_area.region_id'=>$row->dregion))->result();
						if(!empty($resda))
						{	echo'<option value="">Select Area</option>';
							foreach ($resda as $key => $value) {
								echo'<option value="'.$value->area_id.'" '.($value->area_id==$row->darea?'selected':'').'>'.$value->area_name.'</option>';
							}
						}
                       echo '</select>
                    </div>
                    <div class="col-md-12">
                        <select id="dbranch_holder'.$did.'" data-did="'.$did.'" onchange="include_branch(this)" multiple data-close-on-select="false">';
						$sel_res =	$this->Branch_model->branch_list(0,"branch.branch_id IN (".$row->dlist.")")->result();
						$all = array();
						foreach ($sel_res as $key => $val) {
							$all[] = $val->branch_id;
						}

						$resbd =	$this->Branch_model->branch_list(0,"branch.area_id = (".$row->darea.")")->result();

							if(!empty($resbd))
							{	
								foreach ($resbd as $key => $value) {
									echo'<option value="'.$value->branch_id.'" '.(in_array($value->branch_id,$all)?"selected":"").'>'.$value->branch_name.'</option>';
								}
							}
                        echo '</select>
                    </div>
                    ';
                }
                echo'<div class="col-md-12">
                        <label>Delivery To<font color="red">*</font></label>
                        <select class="form-control delivery_to" name="dbranch['.$did.']" id="dbranch'.$did.'" onchange="generate_table()" multiple required data-close-on-select="false">';
                  	 //$dlist = explode(',',$row->dlist);
                        if($type=='zone')
                        {
                            //$zones =   $this->Branch_model->zone_list()->result();
							$zones =	$this->Branch_model->zone_list(0,"zones.zone_id IN (".$row->dlist.")")->result();
                           
                            foreach ($zones as $key => $value)
                            {
                                echo'<option value="'.$value->zone_id.'" selected>'.$value->name.'</option>';
                            }
                        }
                        else
                        {
                        	$res =	$this->Branch_model->branch_list(0,"branch.branch_id IN (".$row->dlist.")")->result();
							if(!empty($res))
							{	
								foreach ($res as $key => $value) {
									echo'<option value="'.$value->branch_id.'" selected>'.$value->branch_name.'</option>';
								}
							}
                        }
                    echo'</select>
            </div>
        </div>
    </div>
</div>';
$did++;
}
?>	
</div>
<div class="row">
	<div class="col-lg-12" align="right">
		<button class="btn btn-primary" onclick="make_clone()"><i class="fa fa-plus"></i> Add</button>
		<button class="btn btn-info" id="edit_charge"><i class="fa fa-edit"></i> Edit</button>
			<!-- <button class="btn btn-primary" onclick="generate_table()">Go</button> -->
	</div>
</div>

<div class="row" style="padding: 5px; padding-bottom:40px;">
		<div class="col-lg-12 tablebox">
		</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.panel-box').find('select').select2();
});

function set_type(t)
{
	if(t.value=='ftl')
	{
		$('select[name=btype]').val('branch').select2({disabled:'readonly'}).trigger('change');
	}
	else
	{
		$('select[name=btype]').val('branch').select2({disabled:false}).trigger('change');
	}
}

$(document).on('submit','#data_table',function(e){
	e.preventDefault();
	var formdata = $(this).serialize();
	$.ajax({
		url:'<?=base_url('client/save_deal_data')?>',
		type:'post',
		data:formdata,
		beforeSend:function(){
			Swal.fire({

				imageUrl:'https://mir-s3-cdn-cf.behance.net/project_modules/disp/35771931234507.564a1d2403b3a.gif',
				showConfirmButton:false,
				allowOutsideClick: false,
  				allowEscapeKey: false
			});
		},
		success:function(res){
			res = res.trim();
			Swal.close();
			if(res=='1')
			{
				
				Swal.fire({
					title:'Saved!',
					icon:'success',
					type:'success',
					timer: 2000,
				});
				<?php
				if(empty($by))
				{
				?>
					location.href="<?=base_url('enquiry/enq_page/'.$details->enquiry_id.'/'.base64_encode($data_type))?>#COMMERCIAL_INFORMATION";
				<?php
				}
				else
				{?>
					location.href="<?=base_url('client/deals')?>";
				<?php
					}
				?>
			}
			else{
				Swal.fire({
					title:'Something Went Wrong!',
					icon:'error',
					type:'error',
					timer: 2000,
				});
			}
			
		},
		error:function(u,v,w)
		{
			alert(w);
		}
	});
});

generate_table();

function generate_table()
{
	var BList= new Array();
	var blist = $(".booking_from");
	var dlist = $(".delivery_to");
	$(blist).each(function(k,v){
		var bkey =$(v).val();
		var dval = $(dlist[k]).val();
		BList.push({key:bkey,val:dval});	
	});
	var deal_id = "<?=$deal->id?>";
	var deal_type = $("select[name=deal_type]").val();
	var unique_no = "<?=$deal->quatation_number?>";
	var booking_type = $("select[name=booking_type]").val();
	var business_type = $("select[name=business_type]").val();
	var insurance = $("select[name=insurance]").val();

	var btype = $("select[name=btype]").val();
	var dtype = $("select[name=dtype]").val();
	var enq_for = $("input[name=for]").val();
	var stage_for = "<?=$data_type?>";
//alert(unique_no);
	if(blist.length==0 || dlist.length==0)
	{
		var msg = 'Fill required fields.';
		if(bbranch==null)
			msg='Booking branch is required.';
		if(dbranch==null)
			msg='Delivery branch is required.';
		Swal.fire({
			title:msg,
			icon:'error',
			showConfirmButton:true,

		})
		$(".tablebox").html('');
		return;
	}

	$.ajax({
		url:'<?=base_url('client/gen_table')?>',
		type:'POST',
		data:{	
				deal_type:deal_type,
				unique_no:unique_no,
				booking_type:booking_type,
				business_type:business_type,
				insurance:insurance,
				chain:BList,
				btype:btype,
				dtype:dtype,
				enq_for:enq_for,
				deal_id:deal_id,
				stage_for:stage_for
			},
		beforeSend:function()
		{
			$(".tablebox").html('<center><i class="fa fa-spinner fa-spin" style="font-size:23px;"></i></center>');
		},
		success:function(res){
			$(".tablebox").html(res);
			$(".tablebox").find('select:not(.exclude_select2)').select2();
			// $("#delivery_branch").select2({ closeOnSelect: false});
			// $("#booking_branch").select2({ closeOnSelect: false});
		},
		error:function(u,v,w)
		{
			alert(w);
		}
	});
}

function load_areas(t)
{
	var type = t.id;
	var did = $(t).data('did');
	//alert(type);
	if(t.value!='')
	{
	  	var rid =t.value;
	  	
	    $.ajax({
	      url:'<?=base_url('setting/area_by_region')?>',
	      data:{region:rid},
	      type:'post',
	      success:function(q){
	      	if(type=='b_reg'+did)
	     		$('#b_area'+did).html(q);
	     	else
	     		$('#d_area'+did).html(q);
	      }
	    });
	}
	else
	{
		if(type=='b_reg'+did)
	     	$('#b_area'+did).html('');
	    else
	     	$('#d_area'+did).html('');
	}
}

function load_branch_particular(t)
{	var type = t.id;
	var did = $(t).data('did');

	if(t.value!='')
	{
		var area = t.value;
		 $.ajax({
	      url:'<?=base_url('setting/branch_by_area')?>',
	      data:{area:area},
	      type:'post',
	      success:function(q){

		      	if(type=='b_area'+did)
		     		$('select[name="bbranch['+did+']"]').html(q);
		     	else{

	     			$('select[id="dbranch_holder'+did+'"]').html(q);
		     	}
	     		//$('select[name="bbranch['+did+']"]').trigger('change');
	      }
	    });
	}
	else
	{
		 $(t).parents('.row:first').find('#delivery_branch').html('');
		 $('select[name="bbranch['+did+']"]').trigger('change');
	}
	
}
var count = <?=$did?>;
//make_clone();
function make_clone()
{//alert('ss');

	var type = $("select[name=btype]").val();
	$.ajax({
		url:'<?=base_url('client/branch_panel_clone/')?>'+count+'/'+type,
		success:function(res)
		{
			$('.panel-box').append(res);
			$('.panel-box').find('select').select2();
			count++;
		}
	});
}


function include_branch(t)
{
	var holder_list = $(t).find('option:selected');
	var did = $(t).data('did');
	//alert(JSON.stringify(holder_list));
	var avail =$('select[name="dbranch['+did+']"]').val();
	if(avail==null)
		avail=[];

	$(holder_list).each(function(k,v){
		var kv = $(v).attr('value');
		var vv = $(v).text();
		if(!avail.includes(kv))
		{
			$('select[name="dbranch['+did+']"]').append('<option value="'+kv+'" selected>'+vv+'</option>');
		}
		else
		{
			$('select[name="dbranch['+did+']"]').find('option[value="'+kv+'"]').attr('selected','selected');
		}
	});
	$('select[name="dbranch['+did+']"]').trigger('change');
}

var max_discount = <?=$max_discount?>;
$(document).on('change keyup click','#data-box input',function(e){
	var f = $(".tablebox");

var type = $("select[name=booking_type]").val();
if(type=='ftl')
return;	

	var qid = $(this).data('id');
		if(this.value!='' && (this.value<0 || this.value ===NaN))
			this.value=0;

	var rate = $(f).find("input[name='rate["+qid+"]']").val();
	var discount = $(f).find("input[name='discount["+qid+"]']").val();
	var eton = $(f).find("input[name='eton["+qid+"]']").val();
	var pton = $(f).find("input[name='pton["+qid+"]']").val();
	rate = parseFloat(rate);
	discount = parseFloat(discount);
	eton = parseInt(eton);
	pton = parseInt(pton);

	var cal_rate = rate.toFixed(2) - ((rate*discount)/100).toFixed(2);
	var cal_eamnt = cal_rate * eton * 1000; 
	var cal_pamnt = cal_rate * pton * 1000; 
	
	var in_lakh_eamnt = (cal_eamnt/100000).toFixed(6);
	var in_lakh_pamnt = (cal_pamnt/100000).toFixed(6);
	in_lakh_pamnt = parseFloat(in_lakh_pamnt);
	in_lakh_eamnt = parseFloat(in_lakh_eamnt);
	
	$(f).find("input[name='eamnt["+qid+"]']").val(in_lakh_eamnt);
	$(f).find("input[name='pamnt["+qid+"]']").val(in_lakh_pamnt);
});

$(document).on('change keyup click','.discount_ip',function(e){
	var f = $(".tablebox");

var type = $("select[name=booking_type]").val();
if(type=='ftl')
return;	

	var qid = $(this).data('id');
		if(this.value!='' && (this.value<0 || this.value ===NaN))
			this.value=0;

	var rate = $(f).find("input[name='rate["+qid+"]']").val();
	var discount = $(f).find("input[name='discount["+qid+"]']").val();
	var eton = $(f).find("input[name='eton["+qid+"]']").val();
	var pton = $(f).find("input[name='pton["+qid+"]']").val();
	rate = parseFloat(rate);
	discount = parseFloat(discount);
	eton = parseInt(eton);
	pton = parseInt(pton);


	if(discount > max_discount)
	{
		Swal.fire({
			title:'You are authorized to give discount upto '+max_discount+'% only. Please send this quotation to your reporting manager for further approval.',
			icon:'warning',
			type:'warning',
			showConfirmButton:false,
		});
		//$(f).find("input[name='discount["+qid+"]']").val(max_discount);
		return false;
	}
	var cal_rate = rate.toFixed(2) - ((rate*discount)/100).toFixed(2);
	var cal_eamnt = cal_rate * eton * 1000; 
	var cal_pamnt = cal_rate * pton * 1000; 
	
	var in_lakh_eamnt = (cal_eamnt/100000).toFixed(6);
	var in_lakh_pamnt = (cal_pamnt/100000).toFixed(6);
	in_lakh_pamnt = parseFloat(in_lakh_pamnt);
	in_lakh_eamnt = parseFloat(in_lakh_eamnt);
	
	$(f).find("input[name='eamnt["+qid+"]']").val(in_lakh_eamnt);
	$(f).find("input[name='pamnt["+qid+"]']").val(in_lakh_pamnt);
});

function load_branch(t)
{
	var dtype = $(t).data('type');
	var key = t.value;
	if(dtype=='booking')
	{
		$("select[name=dtype]").val(key);
		$("select[name=dtype] option").removeAttr('disabled');
		$("select[name=dtype] :not(option[value="+key+"])").attr('disabled','disabled');
		$("select[name=dtype]").trigger('change');
	}

// $(".panel-box").html('');
// 	count=1;
// 		make_clone();
}

$(document).on('click','#edit_charge',function(){
	$(this).hide();
	Swal.fire({
			title:'Now deal will be required to be approved.',
			icon:'info',
			type:'info',
			showConfirmButton:false,
		});
	$('#data_table').find("input[name=edited]").val('1');
	$('#oc-box').find('input').removeAttr('readonly');
	$('#oc-box').find('input:first').focus();
	$('.disc-box').find('input').removeAttr('readonly');
	$('.disc-box').find('input:first').focus();
	$(".edit_remark").show();
});

function rep_discount()
{
		var ref =	$(".discount_ip");
		var fixed = $(ref[0]).val();

		$(".discount_ip").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
}

function rep_final_rate()
{
		var ref =	$(".final_rate");
		var fixed = $(ref[0]).val();

		$(".final_rate").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
}

function final_rate_calculate(uid)
{
		var rate =	$("#rate_"+uid).val();
		var discount =	$("#discount_"+uid).val();
		var final_rate =	$("#final_rate_"+uid).val();
		var dis_rate = rate/100*discount;
		var finalrate = rate-dis_rate;
		var finalrate = finalrate.toFixed(1);
		var finalrate = finalrate+'0';
		$("#final_rate_"+uid).val(finalrate);
}

function rep_paymode()
{
		var ref =	$(".paymode_ip");
		var fixed = $(ref[0]).val();
		$(".paymode_ip").val(fixed);
		$(".paymode_ip").trigger('change');
}
function rep_insurance()
{
		var ref =	$(".insurance_ip");
		var fixed = $(ref[0]).val();
		$(".insurance_ip").val(fixed);
		$(".insurance_ip").trigger('change');
}
function rep_eton()
{
		var ref =	$(".eton_ip");
		var fixed = $(ref[0]).val();

		$(".eton_ip").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
}
function rep_pton()
{
		var ref =	$(".pton_ip");
		var fixed = $(ref[0]).val();

		$(".pton_ip").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
}

function rep_vtype()
{
		var ref =	$(".vtype_ip");
		var fixed = $(ref[0]).val();

		$(".vtype_ip").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
}
function rep_capacity()
{
		var ref =	$(".capacity_ip");
		var fixed = $(ref[0]).val();

		$(".capacity_ip").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
}
function rep_invoice()
{
		var ref =	$(".invoice_ip");
		var fixed = $(ref[0]).val();

		$(".invoice_ip").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
}

function oda_cal()
{
	var dis = $('#oda_distance').val();
	var we = $('#oda_weight').val();
	$.ajax({
		url:'<?=base_url('setting/oda_calculate')?>',
		type:'post',
		data:{dis:dis,we:we},
		success:function(res)
		{
			$('#oda_value').val(res);
		}

	});
}

function uoda_cal()
{
	var dis = $('#uoda_distance').val();
	var we = $('#uoda_weight').val();
	$.ajax({
		url:'<?=base_url('setting/oda_calculate')?>',
		type:'post',
		data:{dis:dis,we:we},
		success:function(res)
		{
			$('#uoda_value').val(res);
		}

	});
}

function add_door()
{	
	//alert('s');
	var box = $("#door_sample");
	var clone = $(box).clone();
	$(clone).wrap('<div></div>');
	//$(clone).removeAttr('id');

	$(clone).find('.door_unit_sel').remove();
	$(clone).append('<div class="door_unit_sel" style="width:28%; display:inline-block"><select name="oc[19][unit][]"><option value="per_kg">per KG</option><option value="per_gc">per GC</option><option value="per_trip">per Trips</option></select></div><button type="button" onclick="remove_door(this)" class="btn btn-xs btn-danger pull-right" style="max-width:8%;display:inline-block"><i class="fa fa-times"></i></button>');
	$(clone).find('select').select2();
	$("#door_box").append(clone);
	//$('#door_box select').select2();
}

function add_mile_del()
{	
	//alert('s');
	var box = $("#mile_sample");
	var clone = $(box).clone();
	$(clone).wrap('<div></div>');
	//$(clone).removeAttr('id');

	$(clone).find('.mile_unit_sel').remove();
	$(clone).append('<div class="mile_unit_sel" style="width:28%; display:inline-block"><select name="oc[20][unit][]"><option value="per_kg">per KG</option><option value="per_gc">per GC</option><option value="per_trip">per Trips</option></select></div><button type="button" onclick="remove_door(this)" class="btn btn-xs btn-danger pull-right" style="max-width:8%;display:inline-block"><i class="fa fa-times"></i></button>');
	$(clone).find('select').select2();
	$("#mile_box").append(clone);
	//$('#door_box select').select2();
}

function remove_door(t)
{
	$(t).parents('div:first').remove();
}

$(document).ready(function(){
	$("select[name=btype]").trigger('change');
});

$(document).on('click','.toggle-btn',function(){
	if($(this).data('view')=='show')
	{
		$(this).parent().find('.t_box').hide(600);
		$(this).data('view','hide');
	}
	else
	{
		$(this).parent().find('.t_box').show(600);
		$(this).data('view','show');
	}
	
});

function rate_alert(){         
     $.ajax({
          url: "<?php echo base_url().'client/get_exist_oda'?>",
          type: 'POST',
		 // data: {parameter:parameter},
          
          success: function(content) {
if(content!=0){			  
Swal.fire(
  'ODA Matrix Details...',
  content
)
}
          }
      });    
}
</script>
<style type="text/css">
	.tablebox input:not(input[type=radio])
	{
		width: 100%;
		padding:0.65vw;
		border:1px solid #cecece;
		border-radius: 4px;
	}
	.tablebox input[type=radio]
	{
		display:inline;
	}
	.toggle-btn
	{
		display: inline-block;
	    border: 2px solid black;
	    padding: 3px 6px;
	    border-radius: 50%;
	    background: #d9edf7;
	    position: relative;
	}
</style>
