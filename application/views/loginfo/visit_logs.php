<div class="row">
        <div class="col-sm-12" style="background:aliceblue;padding:7px">        
            <div style="margin-left: 16px;">
        
        <?php     
        if(!empty($crumb)){
            foreach(array_reverse($crumb) as $value){
                $this->db->where('pk_i_admin_id',$value);
                $k = $this->db->get('tbl_admin')->row_array();
                ?>
                <a href="<?=base_url().'attendance/myteam/'.$k["pk_i_admin_id"].'?fdate='.$from.'&tdate='.$to?>"><u><?=$k['s_display_name']?></u>                              
                    >>                        
            </a>
                <?php
            }
            $this->db->where('pk_i_admin_id',$current_user);
            $last = $this->db->get('tbl_admin')->row_array();
            ?>
            <a href="javascript:void(0)"><u><?=$last['s_display_name']?></u> </a>
            <?php
        }
        ?>
            </div>
        </div>
    </div>
    <br>


<div class="row">        
    <div class="col-sm-4">        
    </div>
    <div class="col-sm-4">        
        <ul  class="nav nav-tabs navbar-team-report" role="tablist">              
        <li class="btn btn-primary" href="#basic" data-toggle="tab" style="margin-right:3px;">Team</li>  
            <li class="btn btn-primary" href="#basicnew" data-toggle="tab" >Visit Activity</li>  
        </ul>
    </div>
</div>

<br>

<div class="tab-content clearfix">
    <div class="tab-pane active" id="basic">
    
                
    <div class="row">
        <!--  table area -->
        <div class="col-sm-12">        
            <div class="panel-body">
                <!--<h2><?=$att_date?></h2>-->
                <table id="vistbl" class="datatable table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo display('serial') ?></th>
                            <th>Emp Id</th>
                            <th><?php echo ucfirst(display('disolay_name')) ?></th>
                            <th>Designation/UR</th>
                            <th>Region</th>
                            <th>Leads</th>
                            <th>Deals</th>
                            <th>Visits</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)) { ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo $user->employee_id; ?></td>
                                    <td><?php echo "<a href='".base_url()."attendance/myteam/".$user->pk_i_admin_id."?fdate=".$from."&tdate=".$to."'>".$user->s_display_name;echo '&nbsp;';echo $user->last_name."</a>"; ?></td>
                                    <td><?php echo $user->user_role;?></td>
                                    <td><?php echo $user->sale_region;?></td>
                                    <?php
                                    $filter = '?employee='.$user->pk_i_admin_id;
                                    if($from && $to ){
                                        $filter .= "&from=".$from."&to=".$to;
                                    } 

                                    ?>
                                    <td><?php echo "<a style='font-size: 20px;text-decoration: underline;' target='_blank' href='".base_url()."enq/index/all".$filter."'>".$user->t_enq.'</a>';?></td>
                                    <td><?php echo "<a style='font-size: 20px;text-decoration: underline;' target='_blank' href='".base_url()."client/deals".$filter."'>".$user->t_deal.'</a>';?></td>
                                    <td><?php echo "<a style='font-size: 20px; text-decoration: underline;' target='_blank' href='".base_url()."client/visits".$filter."'>".$user->t_vis.'</a>';?></td>                                                                        
                                </tr>                                
                                <?php $sl++; ?>
                            <?php } ?> 
                        <?php } ?> 
                    </tbody>
                </table>  <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>
    <div class="tab-pane " id="basicnew">
        <?php
        echo $visit_activity;
        ?>
    </div>
</div>







<button type="button" id='modal_btn' data-toggle="modal" data-target="#myModal" style="visibility: hidden;"></button>
<script type="text/javascript">
// Basic example
$(document).ready(function () {
  $('#vistbl').DataTable({
    "paging": true // false to disable pagination (or any other option)
  });
  $('.dataTables_length').addClass('bs-select');
});

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
      function get_modal_content(id,curr_date){        
            $.ajax({
            url: "<?=base_url().'visits/visit_live/'?>"+id,
            type: 'post',
            dataType: 'json',
            data:{
                'id':id,
                'curr_date':curr_date
            },
            success: function(responseData){
                if(responseData.status == true){
                    $("#map_model_content").html(responseData.data);
                    $("#modal_btn").click();
                }else{
                    $("#map_model_content").html('');                    

                    alert('No Map Found!');
                }
            }
        });
      }
     $(".chosen-select").chosen({
      no_results_text: "Oops, nothing found!"
    });
  </script>