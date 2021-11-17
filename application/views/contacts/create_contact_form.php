<style>
.ui-widget-content{
	  z-index:99999;
  }
 </style>
<?php
/* please dont execute below commented code here, it will slow down application*/
// $ci = &get_instance();

// $ci->load->model(array('Client_Model','Enquiry_Model','Leads_Model'));
// $company_list= $ci->Client_Model->getCompanyList()->result();
// $enquiry_list = $ci->Enquiry_Model->all_enqueries();
// $all_designation = $ci->Leads_Model->desi_select(); 


/* please dont execute above code here. it will slow down application*/
?>
<form action="<?=base_url('client/create_newcontact/')?>" method="post" class="form-inner"> 

<?php
if(empty($enquiry_id))
{?>
   <!--<div class="form-group col-md-6">
        <label>Company group name</label>
        <select class="form-control" name="company" onchange="load_accounts(this.value)">
          <option value="-1">Select</option>
          <?php

          if(!empty($company_list))
          {
            foreach ($company_list as $key =>  $row)
            {
              echo '<option value="'.$row->id.'">'.$row->company_name.'</option>';
            }
          }
          ?>
        </select>
    </div>-->
	
	<div class="form-group col-md-6">
        <label><?php echo 'Company group name' ?> <i class="text-danger">*</i></label>
        <input class="form-control" value="<?php  echo set_value('company');?> " name="company_nm" id="company_list" type="text"  placeholder="Enter Company" onblur="find_company_id(this.value)" required>
		<input class="form-control" name="company" type="hidden">
    </div>

   <div class="form-group col-md-6">
      <label>Client Name</label>
      <select class="form-control load_account" name="enquiry_id">
      <option value="0">Select </option>
        <?php
        //print_r($enquiry_list);
     	if(!empty($enquiry_list))
     	{
     		foreach ($enquiry_list as $row)
     		{
     			echo'<option value="'.$row->enquiry_id.'">'.$row->client_name.'</option>';
     		}
     	}
      	?>
      </select>      
   </div>
<?php
}
else
{
  echo'<input name="enquiry_id" type="hidden" value="'.$enquiry_id.'">';
}
?>
    <div class="form-group col-md-6">
        <label>Designation</label>
			  <select class="form-control" name="designation" id="designation">
					<option value=''>---Select Designation----</option>
              <?php 
              if (!empty($all_designation)) 
              {
                foreach ($all_designation as $key => $value) 
                { ?>
                    <option value="<?= $value->id;?>"><?= $value->desi_name;?></option>
                <?php
                }
              } 
              ?>
          </select>
		  <i class="fa fa-plus" onclick="showDivAttid('1')" style="color:red"></i>
	</div>
	<div class="form-group col-sm-6 col-md-6" id="new_designation" style="display:none;"> 
        <label><?php echo "New Designation"; ?> <i class="text-danger"></i></label>
        <input class="form-control" name="new_designation" type="text" placeholder="Designation Name">
        <i class="fa fa-times" onclick="showDivAttid('0')" style="color:red"></i>						
    </div>
               <div class="form-group col-md-6">
                  <label>Name</label>
                  <input class="form-control" name="name" placeholder="Contact Name"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Contact No.</label>
                  <input class="form-control" name="mobileno" placeholder="Mobile No." maxlength="10"  type="text"  required>
               </div>
               <div class="form-group col-md-6">
                  <label>Email</label>
                  <input class="form-control" name="email" placeholder="Email"  type="email"  required>
               </div>
                <div class="form-group col-md-12">
                  <label>Decision Maker</label>
                  &nbsp;&nbsp;<input name="decision_maker" value="1" type="checkbox">
               </div>
               <div class="form-group col-md-12">
                  <label>Other Details</label>
                  <textarea class="form-control" name="otherdetails" rows="8"></textarea>
               </div>
			        
               <div class="sgnbtnmn form-group col-md-12">
                  <div class="sgnbtn">
                     <input id="signupbtn" type="submit" value="Add Contact" class="btn btn-primary"  name="Add Contact">
                  </div>
               </div>
</form>
<script type="text/javascript">

function load_accounts(v)
{
      $.ajax({
            url:"<?=base_url('client/account_by_company2')?>",
            type:'get',
            data:{comp_id:v},
            success:function(q){
              $(".load_account").html(q);
              $(".load_account").trigger('change');
            }
      });
    
      //  $.ajax({
      //       url:"<?=base_url('client/contact_by')?>",
      //       type:'get',
      //       data:{key:v,by:'company'},
      //       success:function(q){
      //         $(".load_contact").html(q);
      //         // $("select[name=contact_id]").trigger('change');
      //       }
      // });
}

$(function() {   
          $("input[name=company_nm]").autocomplete({
            source: function( request, response ) {
                 $.ajax({
                  url: "<?=base_url('enquiry/suggest_company')?>",
                  type: 'post',
                  dataType: "json",
                  data: {
                   search: request.term
                  },
                  success: function( data ) {
                   response(data);
                  }
                 });
              },
          });
        });
function find_company_id(key)
{
    $.ajax({
            url:"<?=base_url('client/company_by_name')?>",
            type:'get',
            data:{key:key},
            success:function(q){
              q = q.trim();
              if(q)
              {
				$('input[name=company]').val(q);
                load_accounts(q);
              }

            }
  });
}
</script>
<script>
    function showDivAttid(x){

        if(x==1) {

            document.getElementById("new_designation").style.display = 'block';
        }
        else
        {
            document.getElementById("new_designation").style.display = 'none';
        }
    }

</script>