<div class="row" style="padding:15px;">
	<div class="col-lg-12" style="padding:10px; border: 1px dashed #cecece; border-radius: 4px;"> 
		<div class="col-lg-4">
			<div class="form-group"> 
		        <label>Client Name</label><br>
		        <div class="form-control">
		        	<?=$details->client_name?>
		        </div>
				<input type="hidden" name="for" value="<?=$details->enquiry_id?>">
		    </div>
		</div>
		<div class="col-lg-2">
			<label>Deal Type</label>
	        <select class="form-control" name="deal_type" onchange="{
	        	$('input[name=deal_type]').val(this.value);
	        }" multiple>
	            <option value="domestic">Domestic</option>
	            <option value="saarc">SAARC</option>
	        </select>
		</div>
		<div class="col-lg-2">
			<div class="form-group"> 
		        <label>Booking Type</label>
		        <select class="form-control" name="booking_type" id="booking_type" onchange="set_type(this)">
		            <option value="sundry">Sundry</option>
		            <option value="ftl">FTL</option>
		        </select>
		    </div>
		</div>
		<div class="col-lg-2">
			<label>Business Type</label>
	        <select class="form-control" name="business_type" id="business_type">
	            <option value="in">Inward</option>
	            <option value="out">Outward</option>
	        </select>
		</div>
		<div class="col-lg-2">
			<label>Insurance</label>
	        <select class="form-control" name="insurance" id="insurance">
			    <option>Select Here</option>
	            <option value="carrier">Carrier</option>
                <option value="owner">Owner risk</option>
	        </select>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-5">
		<div class="form-group"> 
	        <label>Type <font color="red">*</font></label>
			<select class="form-control" name="btype" onchange="load_branch(this)" data-type="booking">
				<option value="branch">Branch</option>
				<option value="zone">Zone</option>
				<option value="area" disabled>Area</option>
			</select>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="form-group"> 
	        <label>Type <font color="red">*</font></label>
			<select class="form-control" name="dtype" data-type="delivery" >
				<!-- onchange="load_branch(this)"  -->
				<option value="branch">Branch</option>
				<option value="zone">Zone</option>
				<option value="area">Area</option>
			</select>
		</div>
	</div>
</div>

<div class="row panel-box">
</div>
<div class="row">
	<div class="col-lg-12" align="right">
		<button class="btn btn-primary" onclick="make_clone()"><i class="fa fa-plus"></i> Add</button>
			<!-- <button class="btn btn-primary" onclick="generate_table()">Go</button> -->
	</div>
</div>

<div class="row" style="padding: 5px; padding-bottom:40px;">
		<div class="col-lg-12 tablebox">
		</div>
</div>
<script type="text/javascript">

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
$(".tablebox").html('');	
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
					title:'Saved And Moved To Approach Stage Successfully!',
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

	var deal_type = $("select[name=deal_type]").val();
	var booking_type = $("select[name=booking_type]").val();
	var business_type = $("select[name=business_type]").val();
	var insurance = $("select[name=insurance]").val();

	var btype = $("select[name=btype]").val();
	var dtype = $("select[name=dtype]").val();
	var enq_for = $("input[name=for]").val();
	var stage_for = "<?=$data_type?>";
//alert('d');
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
				booking_type:booking_type,
				business_type:business_type,
				insurance:insurance,
				chain:BList,
				btype:btype,
				dtype:dtype,
				enq_for:enq_for,
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
		     	else
	     			$('select[id="dbranch_holder'+did+'"]').html(q);
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
var count = 1;
//make_clone();
function make_clone()
{//alert('ss');
	var type = $("select[name=btype]").val();
	$.ajax({
		url:'<?=base_url('client/branch-panel-clone/')?>'+count+'/'+type,
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

$('#booking_branch').on('change', function() {
return ;
    var delivery_branch = $("select[name='dbranch[]']").val()??[];
    var booking_branch = $("select[name='bbranch[]']").val()??[];

    var btype = $("select[name=btype]").val();

    $("#delivery_branch").find('option').removeAttr('disabled');

    $(booking_branch).each(function(k,v){
    	if(btype=='branch'){
			$("#delivery_branch").find('option[value="'+v+'"]').attr('disabled','disabled');
    	}
    });
    
    // if(delivery_branch.includes(booking_branch))
    // {
    	var ARY =new Array();
       $(delivery_branch).each(function(k,v){
          if(!booking_branch.includes(v))
            	ARY.push(v);
       });
       //alert(ARY.toString());
       $("#delivery_branch").val(ARY);
       $('#delivery_branch').trigger('change');
    //}
});

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


	if(discount>max_discount)
	{
		Swal.fire({
			title:'You are allowed to give discount upto '+max_discount+'% only.',
			icon:'warning',
			type:'warning',
			showConfirmButton:false,
		});
		$(f).find("input[name='discount["+qid+"]']").val(max_discount);
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

$(".panel-box").html('');
	count=1;
		make_clone();

	//if(key=='branch')
		return;
	// if(dtype=='booking' && key=='branch')
	// {
	// 	$("select[name=dtype]").val('branch');
	// 	$("select[name=dtype] option").removeAttr('disabled');
	// 	$("select[name=dtype] :not(option[value=branch])").attr('disabled','disabled');
	// 	$("select[name=dtype]").trigger('change');
	// }
	// else if(dtype=='booking' && key=='zone')
	// {
	// 	$("select[name=dtype]").val(key);
	// 	$("select[name=dtype] option").removeAttr('disabled');
	// 	$("select[name=dtype] option[value=branch]").attr('disabled','disabled');
	// 	$("select[name=dtype]").trigger('change');
	// }

	$.ajax({
		url:'<?=base_url('setting/load_branchs')?>',
		type:'POST',
		data:{dtype:dtype,key:key},
		beforeSend:function(){
			// if(dtype=='booking')
			// 	$("#booking_branch").parent().find('font').html('<');
			// else
			// 	$("#delivery_branch").parent().find('font').html();
		},
		success:function(res)
		{
			if(dtype=='booking')
				$("#booking_branch").html(res);
			else
				$("#delivery_branch").html(res);
		}
	})
}

function rep_discount()
{
		var ref =	$(".discount_ip");
		var fixed = $(ref[0]).val();

		$(".discount_ip").each(function(k,v){
			$(v).val(fixed);
			$(v).trigger('change');
		});
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
