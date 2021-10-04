<div class="row">
    <!--  table area -->
    <div class="col-sm-12">        
        <div class="panel-body">
            <h2><?=$att_date?></h2>
            <table class="datatable table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo display('serial') ?></th>
                        <th>Emp Id</th>
                        <th><?php echo ucfirst(display('disolay_name')) ?></th>
						<th>Designation</th>
						<th>Region</th>
                        <th>Check In Time</th>
                        <th>Check Out Time</th>
                        <!--<th>Break</th>
                        <th>Total</th>-->
                        <th>Action</th>
						<th>Map</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                                <td><?php echo $sl; ?></td>
                                <td><?php echo $user->employee_id; ?></td>
                                <td><?php echo $user->s_display_name;echo '&nbsp;';echo $user->last_name; ?></td>
								<td><?php echo $user->designation;?></td>
								<td><?php echo $user->sale_region;?></td>
                                <td><?php echo $user->check_in;?></td>                                     
                                <td><?php echo $user->check_out;?></td>
                                <!--<td>
                                    <?php
                                    /* $time1 = new DateTime($user->check_in);
                                    $time2 = new DateTime($user->check_out);
                                    $timediff = $time1->diff($time2);
                                    
                                    $time1 = new DateTime($timediff->format('%H:%i:%S'));
                                    $time2 = new DateTime($user->total);
                                    $diff        =   $time2->diff($time1);
                                    
                                    if($user->total){
                                        echo $diff->format('%H:%i:%S');
                                    } */
                                    ?>
                                </td>
                                <td>
                                    <?php                                     
                                    //echo $user->total;
                                    ?>
                                </td>-->
                                <td><a class="btn btn-primary btn-sm" href="<?=base_url().'attendance/view/'.$user->pk_i_admin_id?>">View</a></td>
								<td><a href="javascript:void(0)" onclick="get_modal_content(<?=$user->pk_i_admin_id?>)" class="btn btn-sm btn-success"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td>
                            </tr>                                
                            <?php $sl++; ?>
                        <?php } ?> 
                    <?php } ?> 
                </tbody>
            </table>  <!-- /.table-responsive -->
        </div>
    </div>
</div>
<button type="button" id='modal_btn' data-toggle="modal" data-target="#myModal" style="visibility: hidden;"></button>
<script type="text/javascript">

    $("#att_date").on('change',function(){
        var date_f = $(this).val();
        $.ajax({
            url: '<?=base_url()?>attendance/att_set_filters_session',
            type: 'post',
            data: {'date':date_f},
            success: function(responseData){
                location.reload();
            }
        });        
    });

</script>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width: 70%">
      <div class="modal-content" id="map_model_content">
        
      </div>
    </div>
  </div>
 <script type="text/javascript">
      function get_modal_content(id){        
            $.ajax({
            url: "<?=base_url().'field_map/map_model_content/'?>",
            type: 'post',
            dataType: 'json',
            data:{'id':id},
            success: function(responseData){
                if(responseData.status == true){
                    $("#map_model_content").html(responseData.data);
                    $("#modal_btn").click();
                }else{
                    $("#map_model_content").html('');                    
                    alert(responseData.msg);
                }
            }
        });
      }
     $(".chosen-select").chosen({
      no_results_text: "Oops, nothing found!"
    });
  </script>