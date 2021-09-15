<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>   
          <?php
          if(user_access('1010'))
          {
          ?>     
          <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#save_contact" title="Add Contact"></a> 
          <?php
          }
          ?>        
  </div>

  <div class="col-md-4 pull-right" align="right">
      <div class="btn-group" role="group" aria-label="Button group">
              <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Actions">
                <i class="fa fa-sliders"></i>
              </a>  
            <div class="dropdown-menu dropdown_css" style="max-height: 400px;overflow: auto; left: -136px;">
               <a class="btn" data-toggle="modal" data-target="#table_col_conf" style="color:#000;cursor:pointer;border-radius: 2px;border-bottom: 1px solid #fff;">Table Config</a>                        
            </div>                                         
      </div>
  </div>
</div>
<a class="dropdown-toggle" data-toggle="modal" data-target="#updt_contact" id="open" title="Add Contact" style="display: none;">UPDATE</a> 
<div class="row p-5" style="margin-top: 20px;">
	<div class="col-lg-12">
		<div class="panel panel-success">
			<div class="panel-body">
				<table id="contactTable" class="table table-bordered table-response">
					<thead>               
  	         <tr>
                <th>&nbsp; # &nbsp;</th>
                <th id="th-1">Name</th>
                <th id="th-2" style="width: 20%;">Company group name</th>
                <th id="th-3" style="width: 20%;">Designation</th>
                <th id="th-4" style="width: 20%;">Contact Name</th>
                <th id="th-5" style="width: 20%;">Contact Number</th>
                <th id="th-6" style="width: 20%;">Email ID</th>
                <th id="th-7" style="width: 20%;">Decision Maker</th>
                <th id="th-8" style="width: 20%;">Other Detail</th>
                <th id="th-9" style="width: 20%;">Created At</th>
                <th id="th-10" style="width: 50px;">Action</th>
  	         </tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



<div id="updt_contact" class="modal fade" role="dialog">
  <div class="modal-dialog">
        <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Contacts</h4>
          </div>
          <div class="modal-body">
            <div class="row" id="update_content">
                
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div> 
</div>
<script type="text/javascript">	
function edit_contact(t)
{
  var contact_id = $(t).data('cc-id');
  //alert(contact_id);
  $.ajax({
        url:"<?=base_url('client/edit_contact/')?>",
        type:"post",
        data:{cc_id:contact_id,task:'view',direct_create:1},
        success:function(res)
        {
        			if(res){
        				var cls = document.getElementById("open");
                        cls.click();
        				$("#update_content").html(res);
        				$("#update_content select").select2();
        			}
        },
        error:function(u,v,w)
        {
          alert(w);
        }
  });
}

function deleteContact(t)
{
    var contact_id = $(t).data('cc-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
       // alert(JSON.stringify(result));
        if (result.value) {
            $.ajax({
                        url:"<?=base_url('client/delete_contact/')?>",
                        type:"post",
                        data:{cc_id:contact_id},
                        success:function(res)
                        { 
                          Swal.fire('Done!', '', 'success');
                          $(t).closest('tr').remove();
                        },
                        error:function(u,v,w)
                        {
                          alert(w);
                        }
                });
        }
      });         
}
</script>


<div id="table_col_conf" class="modal fade" role="dialog">
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
              <label class=""><input type="checkbox" class="choose-col" value="1"> Name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="2"> Company group name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="3"> Designation</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="4"> Contact Name</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="5"> Contact Number</label>
            </div>
             <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="6"> Email ID</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="7"> Decision Maker</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="8"> Other Detail</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="9"> Created At</label>
            </div>
            <div class="col-md-4">
              <label class=""><input type="checkbox" class="choose-col" value="10"> Action</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" onclick="save_table_conf()"><i class="fa fa-save"></i> Save</button>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var c = getCookie('contact_allowcols');
</script>

<script type="text/javascript">
  

function save_table_conf()
{
      var x = $(".choose-col:checked");
      var Ary = new Array();
      $(x).each(function(k,v){
        Ary.push(v.value);
      });
      var list = Ary.join(',');
      //alert(list);
      document.cookie = "contact_allowcols="+list+"; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
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
    //alert(z.length);
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


<script type="text/javascript">
var specific_list = "<?=!empty($this->uri->segment(3))?$this->uri->segment(3):''?>";

specific_list = atob(specific_list);

var TempData = {};
$(".d_filter").on('change',function(){

  $('#deals_table').DataTable().ajax.reload();
 
});
$(document).ready(function(){

  $('#contactTable').DataTable({ 

          "processing": true,
          "scrollX": true,
          "serverSide": true,          
          "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          "ajax": {
              "url": "<?=base_url().'client/contacts_load_data'?>",
              "type": "POST",
              "data":function(d){
                     d.view_all=true;
                     d.specific_list = specific_list;
                       if(c && c!='')
                      d.allow_cols = c;

                     console.log(JSON.stringify(d));
                    return d;
              }
          },
         columnDefs: [
                       { orderable: false, targets: -1 }
                    ]
  });

});

</script>

<div id="save_contact" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Contacts</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                <?php
                if(!empty($contact_create_form))
                    echo $contact_create_form;
                ?>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>