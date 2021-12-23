<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <div class="row"> 
 <div class="col-md-12" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
 <div class="col-md-6" > Client Details
        </div>
        <div class="col-md-6" >  
        <div style="float:right">
          <div class="btn-group" role="group" aria-label="Button group">
            <a class="btn" onClick="window.location.reload();" title="Refresh">
               <i class="fa fa-refresh icon_color"></i>
            </a>                                                    
          </div>
        <div class="btn-group" role="group" aria-label="Button group">
            <a class="btn" href="<?php echo base_url(); ?>client" title="Refresh">
               <i class="fa fa-arrow-left icon_color"></i>
            </a>                                                    
          </div>
                         
     
          
          </div>
        </div>

      
</div>
</div>     			    
  <div class="row">
    <!-- side partition starts-->
    <div class="card col-md-8" id="ThreadEnq" style="top:10px;">
    <style type="text/css">
  [data-lettersEmployee]:before  
  {
    content:attr(data-lettersEmployee);
    display:inline-block;
    font-size:1em;
    width:2.5em;
    height:2.5em;
    line-height:2.5em;
    text-align:center;
    border-radius:50%;
    background:#2F353A;
    vertical-align:middle;
    margin-right:1em;
    color:white;
  }
  
  [data-lettersGenerated]:before {
    content: attr(data-lettersGenerated);
    display: inline-block;
    font-size: 1em;
    width: 2.5em;
    height: 2.5em;
    line-height: 2.5em;
    text-align: center;
    border-radius: 50%;
    background: #5FBD75;
    vertical-align: middle;
    margin-right: 1em;
    color: white;
}
</style>


<div class="row">
    
                    
  <div class="col-md-12">
    <div class="row" style="padding-left: 2px;">       
      <div class="col-md-8" style="min-height: 8vh;padding: 15px 17px;">                        
        <h3 style="margin:0px;"><?php echo $clientDetails->cl_name;?></h3>          
        <h4 style="margin:0px;"><?php echo $clientDetails->cl_mobile;?> | <?php echo $clientDetails->cl_email;?></h4>
        <h5 style="margin:0px;"><?php echo $clientDetails->address;?></h5>                          
      </div> 
      <div class="col-md-4" style="min-height: 8vh;padding: 15px 15px;text-align: right">        
        <button class="btn btn-success btn-sm" data-toggle="modal" type="button" title="Send SMS" data-target="#sendsms<?php echo $clientDetails->cli_id;?>" onclick="getTemplates('2')" >
          <i class="fa fa-paper-plane-o"></i>
        </button>
        <button class="btn btn-info btn-sm" data-toggle="modal" type="button" title="Send Email" data-target="#sendsms<?php echo $clientDetails->cli_id;?>" onclick="getTemplates('3')">
          <i class="fa fa-envelope"></i>
        </button>
          
               <button class="btn btn-success btn-sm" data-toggle="modal" type="button" title="Send Whatsapp" data-target="#sendsms<?php echo $clientDetails->cli_id;?>" onclick="getTemplates('1')">
                <i class="fa fa-whatsapp"></i>
              </button>                                        
                                     
      </div>       
       <div class="col-md-12" style="min-height: calc(100vh - 105px);overflow: auto;">
			 
			<div id="exTab3" class="">	
        <ul  class="nav nav-tabs" role="tablist">
			<li class="active">
        <a  href="#info" data-toggle="tab">Info</a>
			</li>
			<li><a href="#task" data-toggle="tab">Tasks and comments</a>
			</li>
			<li><a href="#BOQ" data-toggle="tab">Support Documents</a>
			</li>
			<li><a href="#renewel" data-toggle="tab">Renewals</a>
			</li>
			<li><a href="#invoice" data-toggle="tab">Invoices</a></li>
			<li><a href="#support_tickect" data-toggle="tab">Support Tickets</a></li>
			<li><a href="#contacts" data-toggle="tab">Contacts</a></li>
			<li><a href="<?php echo  base_url('Installationprocess/site_audit_sheet/'.$clientDetails->cli_id.'/'.$clientDetails->customer_code) ?>" >Site Audit Report</a></li>
			<li><a href="<?php echo  base_url('Installationprocess/installation_process/'.$clientDetails->cli_id) ?>" >Installation Process</a></li>
		</ul>

        <div class="tab-content clearfix">
            <div class="tab-pane active" id="info">
        <br>
        <div class="row">
        <?php echo form_open_multipart('client/update_details/'.$clientDetails->cli_id,'class="form-inner"') ?>                      
                
        
        <div class="col-md-12">                              
        
        <div class="row">
        <div class="form-group col-sm-6">
        <label>Client Name*</label>
        <input class="form-control" name="name" type="text" value="<?php echo $clientDetails->cl_name;?>" required="">
        </div>
        <div class="col-sm-6">
        <div class="form-group">
        <label>Contact no.*</label>
        <input class="form-control"  name="mobile" value="<?php echo $clientDetails->cl_mobile;?>" type="text" required="">
        </div>
        </div>
        <div class="col-sm-6">
        <div class="form-group">
        <label>Email ID</label>
        <input class="form-control"  name="email" value="<?php echo $clientDetails->cl_email;?>" type="text">
        </div>
        </div>
        <div class="form-group col-sm-6">
        <label>Address</label>
        <input class="form-control"  name="address" value="<?php echo $clientDetails->address;?>" type="text">                        
        </div>
        <div class="col-sm-12">              
        <div class="row" id="CustomFields"></div>               
        </div>
        <div class="col-sm-6">
        <div class="form-group">
        <label>Status</label>                        
        <select class="form-control" name="status">
        <option></option>
        <option selected="" value="1">Active</option>
        <option value="0">Inactive</option>                                                                  
        </select>
        </div>
        </div>
        </div>                                                                     
        </div>                
        
        <div style="text-align: right">
        <button class="btn btn-sm btn-primary" type="submit">
        <i class="fa fa-dot-circle-o"></i> Update</button>                    
        </div>
        
        </form>          
        </div>
        
        </div>
        
            <div class="tab-pane" id="task">
        <section>
        <form  action="<?php echo base_url(); ?>lead/add_comment" method="POST">  
        <div class="card" style="border: 0px;margin-bottom: 10px;">                        
        <div class="card-body">                                        
        <input class="form-control" id="FormInfo" name="lid" type="hidden" value="<?php echo $clientDetails->cli_id;?>" >
        <input class="form-control" id="FormInfo" name="coment_type" type="hidden" value="3" >
        <input class="form-control" id="LastComment" name="conversation" type="text" placeholder="Add followup comment">
        <br>
        <button class="btn btn-sm btn-dark" style="float: left" type="button" data-toggle="modal" data-target="#createTask">
        <i class="fa fa-dot-circle-o"></i> Create Task</button> 
        &nbsp;
        <button class="btn btn-sm btn-primary" style="float: right" type="submit">
        <i class="fa fa-dot-circle-o"></i> Add Comment</button>                    
        </div>              
        </div>
        </form>
        </section>
        <section style="min-height:50px; overflow-y:scroll;">
        <?php $queryid = $clientDetails->cli_id;
        $this->db->where('lead_id',$queryid);
        $this->db->where('coment_type',3);
         $this->db->order_by("comm_id", "desc");
        $query_response = $this->db->get('tbl_comment')->result();
        if(!empty($query_response)){
        foreach ($query_response as $ld_res) 
        {?>
        <div class="list-group" style="padding:10px;">
        <a class="list-group-item list-group-item-action flex-column align-items-start">
        <div class="d-flex w-100 justify-content-between">
        <p class="mb-1"><?= $ld_res->comment_msg?></p>
        <small><b><?php echo date("j-M-Y h:i:s a",$ld_res->created_date); ?></b></small>
        </div>
        
        </a>
        
        </div> 
        <?php }} ?>
        </section>
        </div>
           
            <div class="tab-pane" id="BOQ">
        <!---<div class="row">-->        
        <table id="dataTable" class="table table-responsive-sm table-hover table-outline mb-0" style="background: #fff;">
        <thead>
        <tr >
        
        <th width="20px">
        S.NO</th>
        <th  >Circuit Sheet</th>
        <th >BoQ</th>
        <th >PI</th>
        <th >Invoice </th>
        <th >PO</th>
        
        
        </tr>
        </thead>
        <tbody>
        <?php $i=1;foreach($boq_list_items as $user){?>
        <tr role="row" class="even">
        
        <td> <?php echo $i;?></td>
        
        
        
        <td ><a  href="<?php echo base_url();?>cricuit-view/<?php echo base64_encode($user->baq_number);?>"><?php echo $user->circuit_sheet;?></a>
        </td>
        <td ><a  href="<?php echo base_url();?>baq-view/<?php echo base64_encode($user->baq_number);?>"> <?php echo $user->baq_number;?></a>
        </td>
      
        <td ><a  href="<?php echo base_url();?>convert-to-pi/<?php echo base64_encode($user->baq_number);?>"> <?php echo $user->baq_number;?></a>
        </td>
                <td ><a  href="<?php echo base_url();?>convert-to-pi/<?php echo base64_encode($user->baq_number);?>"> <?php echo $user->baq_number;?></a>
        </td>
                <td ><a  href="<?php echo base_url();?>convert-to-pi/<?php echo base64_encode($user->baq_number);?>"> <?php echo $user->baq_number;?></a>
        </td>
        
        
        
        </tr>
        <?php $i++; } ?>
        </tbody>
        </table>              
        <!--<br>
        <center><h5><a style="cursor: pointer;" onclick="newEstimate('Auto')">Create new estimate</a></h5></center>
        <br>
        </div>-->
        </div>
        
            <div class="tab-pane" id="renewel">
        <div class="row">          
        <table id="dataTable" class="table table-responsive-sm table-hover table-outline mb-0" style="background: #fff;">
        <thead class="thead-light">
        <tr>                    
        <th>#</th>                                                          
        <th style="width: 25%;">Product</th>                   
        <th style="width: 25%;">Product Key</th>
        <th style="width: 25%;">Renewal Date</th>                   
        <th style="width: 25%;">Renewal Amount</th> 
        <th></th>                                      
        </tr>
        </thead>
        <tbody id="tblDataProduct">
        <tr style="cursor:pointer;">
            <td>1</td>
            <td>Archiz CRM</td>
            <td>2342233</td>
            <td>20-04-2019</td>
            <td>1500</td>
            <td><a style="cursor:pointer;color:red;'" >Delete</a></td>
            </tr>
        </tbody>
        </table> 
        <br>
        <center><h5><a style="cursor: pointer;">Add new renewal</a></h5></center>
        <br>              
        </div>
        </div>
        
            <div class="tab-pane" id="invoice">
        <div class="row">          
        <table id="dataTable" class="table table-responsive-sm table-hover table-outline mb-0" style="background: #fff;">
        <thead class="thead-light">
        <tr>                                                                                                 
        <th style="width: 15%;">Invoice</th> 
        <th style="width: 15%;">Date</th>                   
        <th style="width: 20%;">Product</th>
        <th style="width: 10%;">Rate</th>                   
        <th style="width: 10%;">Qty</th>                                       
        <th style="width: 10%;">Total</th>                                       
        <th style="width: 5%;">Disc.</th>                                       
        <th style="width: 5%;">GST</th>                                       
        <th style="width: 10%;">Amount</th>
        <th style="width: 10%;background: #C7ECFC;color:#2F353A">Payment</th>                                       
        </tr>
        </thead>
        <tbody id="tblDataInvoice">
            <tr style="background-color:#fcfc7b;cursor:pointer">
            <td><b>000001</b></td>
            <td><b>16-04-2019</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>10000</b></td>
            <td><b>0</b></td>
            <td><b>1800</b></td>
            <td><b>11800</b></td>
            <td style="background: #FCADA0;color:#2F353A"><b></b></td>
            </tr>
            
            <tr>
            <td></td>
            <td></td>
            <td>Archiz CRM</td>
            <td>10000</td>
            <td>1</td>
            <td>10000</td>
            <td>0%</td>
            <td>18%</td>
            <td>11800</td>
            <td style="background: #C7ECFC;color:#2F353A"></td>
            </tr>                
                
                </tbody>
        </table>
        <br>
        <center><h5><a style="cursor: pointer;" onclick="window.location='<?php echo base_url(); ?>Invoice/details';">Create new invoice</a></h5></center>
        <br>              
        </div>
        </div>
        
            <div class="tab-pane" id="support_tickect">
        <div class="row">          
        <table id="dataTable" class="table table-responsive-sm table-hover table-outline mb-0" style="background: #fff;">
        <thead class="thead-light">
        <tr>                    
        <th>#</th>                                                          
        <th style="width: 25%;">Ticket No.</th>                   
        <th style="width: 25%;">Problem</th>
        <th style="width: 25%;">Due Date</th>                   
        <th style="width: 25%;">Status</th>                                       
        
        </tr>
        </thead>
        <tbody id="tblDataTicket">
        <?php $sl = 1;
        if(!empty($clientTicketDetails)){
        foreach ($clientTicketDetails as $cltickets) { 
        ?>
        <tr style="cursor:pointer;" onclick="window.location='<?php echo base_url(); ?>ticket/details/<?php  echo $cltickets->tid; ?>';">
        <td><?php  echo $sl; ?></td>
        <td><?php  echo $cltickets->tid; ?></td>
        <td><?php  echo $cltickets->problem_name; ?></td>
        <td><?php  $duedate=$cltickets->due_date; echo date('d-m-Y', strtotime($duedate));;?></td>
        <td><?php  $st= $cltickets->ticket_status; if($st==1){?>
        <button class="btn btn-xs btn-success">Open</button>
        <?php } else {?>
        <button class="btn btn-xs btn-danger">Closed</button>
        <?php } ?>
        </td>
        
        </tr>
        <?php $sl++; }} ?>
        </tbody>
        </table> 
        <br>
        <!--<center><h5><a style="cursor: pointer;" href="<?php echo base_url();?>ticket/addticket/<?php echo $clientDetails->cl_mobile;?>" >Add new Ticket</a></h5></center>-->
        <center><h5><a style="cursor: pointer;"  data-toggle="modal" data-target="#createTicket<?php echo $clientDetails->cli_id ?>" class="btn btn-success">Add new Ticket</a></h5></center>
        <br> 
        
        </div> 
        </div>
        
            <div class="tab-pane" id="contacts">
        <div class="row">          
        <table id="dataTable" class="table table-responsive-sm table-hover table-outline mb-0" style="background: #fff;">
        <thead class="thead-light">
        <tr>                    
        <th>#</th>                                                          
        <th style="width: 25%;">Designation</th>                   
        <th style="width: 25%;">Name</th>
        <th style="width: 25%;">Contact Number</th>                   
        <th style="width: 25%;">Email ID</th>
        <th style="width: 25%;">Other Detail</th>  
        <th></th>                                     
        </tr>
        </thead>
        <tbody id="tblDataContact">
        <?php $sl = 1;
        if(!empty($clientContacts)){
        foreach ($clientContacts as $contact) { 
        ?>
        <tr style="cursor:pointer;" >
        <td><?php  echo $sl; ?></td>
        <td><?php  echo $contact->designation; ?></td>
        <td><?php  echo $contact->c_name; ?></td>
        <td><?php  echo $contact->contact_number; ?></td>
        <td><?php  echo $contact->emailid; ?></td>
        <td><?php  echo $contact->other_detail; ?></td>
        
        </tr>
        <?php $sl++; }} ?>
        </tbody>
        </table> 
        <br>
        <center><h5><a style="cursor: pointer;" data-toggle="modal" data-target="#createnewContact" class="btn btn-success">Add new contact</a></h5></center>
        <br>              
        </div> 
        </div>
        </div>
  </div>
			
			
	   
	   </div> 
    </div>
  </div>                                                        
</div>  
</div>  
<div class="col-md-4 card card-body" id="sideData" style="height: calc(100vh - 105px);padding: 0px;background:#fff;top:10px;">      
      <div class="col-12" style="height: 100%;overflow-x: hidden;overflow-y: auto;padding: 0px;" > 
      
        <!----------------- Calender View ------------>
        <link href="<?php echo base_url();?>assets/css/fullcalendar.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/css/fullcalendar.print.min.css" media="print">
        
        <div id="calendar2"></div>
        

      </div>                         
    </div>
         
  </div>

			    
			    
			    

        
        
        
       
        


<style>
    #exTab3 .nav-tabs > li > a {
  border-radius: 4px 4px 0 0 ;
}

#exTab3 .tab-content {
  /*color : white;*/
  background-color: #fff;
  padding : 5px 15px;
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

</style>
    




<div id="sendsms<?php echo $clientDetails->cli_id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
      <?php echo form_open_multipart('message/send_sms','class="form-inner"') ?> 
    <div class="modal-content card">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal-title"></h4>
      </div>
      <div>
          <div class="form-group col-sm-12">
                <label>Template</label>
                <select class="form-control" name="templates" required id="templates">
                
                </select>
                </div>
                <div class="form-group col-sm-12"> 
                <label><?php echo display('message') ?></label>
                <textarea class="form-control" name="message_name" rows="10" type="number" id="message_name"></textarea>  
                </div>
      </div>
      
       <div class="col-md-12">
                                                               
                    <button class="btn btn-success" type="submit">Send</button>            
                  <input type="hidden" value="" id="mesge_type" name="mesge_type">
                   <input type="hidden" value="<?php echo $clientDetails->cl_email;?>"  name="email">
                    <input type="hidden" value="<?php echo $clientDetails->cl_mobile;?>"  name="phone">
              </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</form>
  </div>
</div>



<script>
function getTemplates(SMS){
$.ajax({
type: 'POST',
url: '<?php echo base_url();?>message/get_templates/'+SMS,
})
.done(function(data){
    $('#modal-title').html('Send SMS');
    $('#mesge_type').val(SMS);
    $('#templates').html(data);
})
.fail(function() {
alert( "fail!" );

});
}
</script>
<script>
function getMessage(id){
$.ajax({
type: 'POST',
url: '<?php echo base_url();?>message/getMessage/'+id,
})
.done(function(data){
    alert(data);
    $('#message_name').innerHtml(data);
})
.fail(function() {
alert( "fail!" );

});
}
</script>

<!-------------------- Create New Ticket ------------------------------>

<div id="createTicket<?php echo $clientDetails->cli_id;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Ticket</h4>
      </div>
      <div class="modal-body">
         <div class="row" >
				
				<?php echo form_open_multipart('ticket/addticket','class="form-inner"') ?> 
                <input class="form-control" name="clientid"  type="hidden" value="<?php echo $clientDetails->cli_id; ?>" required>
            <div class="form-group col-md-6">
            <label>Name</label>
            <input class="form-control" name="name" placeholder="Name"  type="text" value="<?php echo $clientDetails->cl_name; ?>" required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Mobile No.</label>
            <input class="form-control" name="mobile" placeholder="Mobile No."  type="number" value="<?php echo $clientDetails->cl_mobile; ?>" required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Email</label>
            <input class="form-control" name="email" placeholder="Email"  type="text" value="<?php echo $clientDetails->cl_email; ?>" required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Address</label>
            <input class="form-control" name="address" placeholder="Address"  type="text" value="<?php echo $clientDetails->address; ?>" required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Product</label>
            <input class="form-control" name="product" placeholder="Product"  type="text" value="" required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Problem</label>
            <select class="form-control" name="problem">
                <option></option>
                <?php foreach ($problems as $problem) { ?>
                <option value="<?php echo $problem->tp_id; ?>"><?php echo $problem->problem_name; ?></option>
                <?php } ?>
            </select>
            </div>
            
            <div class="form-group col-md-6">
            <label>Priority</label>
            <select class="form-control" name="priority">
                <option></option>
                <?php foreach ($ticketpriority as $priority) { ?>
                <option value="<?php echo $priority->priority_id; ?>"><?php echo $priority->priority_name; ?></option>
                <?php } ?>
                
            </select>
            </div>
            
            <div class="form-group col-md-6">
            <label>Source</label>
            <select class="form-control" name="source">
                <option></option>
                <?php foreach ($ticketsource as $tsource) { ?>
                <option value="<?php echo $tsource->ts_id; ?>"><?php echo $tsource->ticket_source; ?></option>
                <?php } ?>
                
            </select>
            </div>
            
            
            <div class="form-group col-md-6">
            <label>Due Date</label>
            <input class="form-control" name="due_date" placeholder="Due Date"  type="date" value="" required>
            </div>
            
            
            
            
            
            <div class="sgnbtnmn form-group col-md-12">
            <div class="sgnbtn">
            <input id="signupbtn" type="submit" value="Add Ticket" class="btn btn-success"  name="Add Ticket">
            </div>
            </div>
        
				<?php echo form_close()?>
				
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<!------------------------------------------------------------------------>
 
 
 
 <!------------------------- Create New Contact ------------------------------>
 
 <div id="createnewContact" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Contact</h4>
      </div>
      <div class="modal-body">
         <div class="row" >
				
				<?php echo form_open_multipart('client/create_newcontact/'.$clientDetails->cli_id,'class="form-inner"') ?> 
                
            <div class="form-group col-md-6">
            <label>Designation</label>
            <input class="form-control" name="designation" placeholder="Designation"  type="text" required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Name</label>
            <input class="form-control" name="name" placeholder="Contact Name"  type="text"  required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Contact No.</label>
            <input class="form-control" name="mobileno" placeholder="Mobile No."  type="number"  required>
            </div>
            
            <div class="form-group col-md-6">
            <label>Email</label>
            <input class="form-control" name="email" placeholder="Email"  type="text"  required>
            </div>
            
            <div class="form-group col-md-12">
            <label>Other Details</label>
            <textarea class="form-control" name="otherdetails" rows="8"></textarea>
            </div>
            
            
            
            <div class="sgnbtnmn form-group col-md-12">
            <div class="sgnbtn">
            <input id="signupbtn" type="submit" value="Add Contact" class="btn btn-success"  name="Add Contact">
            </div>
            </div>
        
				<?php echo form_close()?>
				
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
 
 
 
 <!---------------------------------------------------------------------------->
<!------------------- Create Task ------------------------->

<div id="createTask" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Create New Task</h4>
      </div>
      <div class="modal-body">
        
            	<?php echo form_open_multipart('lead/enquiry_response_task','class="form-inner"') ?> 
                      <div class="profile-edit">
						
						<input type="hidden" name="enq_code" value="<?php echo $clientDetails->customer_code;?>">
						<input type="hidden" name="task_type" value="3">
                        <div class="form-group col-sm-6">
                          <label>Actual Meet Date <!-----Demo Plan Date---></label>
                          
						  <input class="form-control date" name="meeting_date"  type="text" >
                           
                        </div>
						<div class="form-group col-sm-6">
                          <label>Contact Person Name</label>
                            <input type="text" class="form-control" name="contact_person" value="">
                        </div>
						<div class="form-group col-sm-6">
                          <label>Contact Person Mobile No</label>
                            <input type="text" class="form-control" name="mobileno" value="">
                        </div>
						<div class="form-group col-sm-6">
                          <label>Contact Person Email</label>
                            <input type="text" class="form-control" name="email" value="">
                        </div>
						<div class="form-group col-sm-6">
                          <label>Contact Person Designation</label>
                            <input type="text" class="form-control" name="designation" value="">
                        </div>
                        <div class="form-group col-sm-6">
                          <label>Conversaion Details</label>
                            <textarea class="form-control" name="conversation">                             </textarea>
                        </div>
                       <div class="form-group">
                          <input type="submit" name="update" class="btn btn-primary" value="Update">
						  <!--<button class="btn btn-primary" type="button" onclick="is_Active()">Update</button>-->
						  
                        </div>
                          </div>
                       </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!--------------------------------------------------------->


<script>
    
 
 $(function () {
   var bindDatePicker = function() {
		$(".date").datetimepicker({
        format:'DD-MM-YYYY hh:mm:ss a',
			icons: {
				time: "fa fa-clock-o",
				date: "fa fa-calendar",
				up: "fa fa-arrow-up",
				down: "fa fa-arrow-down"
			}
		}).find('input:first').on("blur",function () {
			// check if the date is correct. We can accept dd-mm-yyyy and yyyy-mm-dd.
			// update the format if it's yyyy-mm-dd
			var date = parseDate($(this).val());

			if (! isValidDate(date)) {
				//create date based on momentjs (we have that)
				date = moment().format('YYYY-MM-DD');
			}

			$(this).val(date);
		});
	}
   
   var isValidDate = function(value, format) {
		format = format || false;
		// lets parse the date to the best of our knowledge
		if (format) {
			value = parseDate(value);
		}

		var timestamp = Date.parse(value);

		return isNaN(timestamp) == false;
   }
   
   var parseDate = function(value) {
		var m = value.match(/^(\d{1,2})(\/|-)?(\d{1,2})(\/|-)?(\d{4})$/);
		if (m)
			value = m[5] + '-' + ("00" + m[3]).slice(-2) + '-' + ("00" + m[1]).slice(-2);

		return value;
   }
   
   bindDatePicker();
 });
  
  
    
</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>

<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar2').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      //Random default events
      
      events    : [
          
        {
          title          : 'as',
          start          : new Date(y, m, 1; echo date('d',2); ?>),
          backgroundColor: '#0073b7', 
          url            : '',
          borderColor    : '#0073b7' 
          
        },
     
       
      ],
      
      editable  : false,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        

      }
    })

  
  })
</script>


<script src="<?php echo base_url();?>assets/js/fullcalendar.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.js"></script>



