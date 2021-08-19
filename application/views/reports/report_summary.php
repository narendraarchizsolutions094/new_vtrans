<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading no-print">
                <div class="btn-group">
                    <a class="btn btn-primary" href="<?= base_url() ?>report/index"> <i class="fa fa-list"></i> All Reports </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="widget-title">
                    Report Summary
                </div>
                <hr>
                <?php
                    //print_r($crumb);
                    if(!empty($crumb)){
                        foreach(array_reverse($crumb) as $value){
                            $this->db->where('pk_i_admin_id',$value);
                            $k = $this->db->get('tbl_admin')->row_array();
                            ?>
                            <a href="<?=base_url().'report/report_summary/'.$k['pk_i_admin_id']?>"><u><?=$k['s_display_name']?></u>                              
                                >>                        
                        </a>
                            <?php
                        }
                        $this->db->where('pk_i_admin_id',$current_user);
                        $last = $this->db->get('tbl_admin')->row_array();
                        ?>
                        <a href="javascript:void(0)"><u><?=$last['s_display_name']?></u> 
                        <?php
                    }
                    ?>
                    <br>
                    <br>
                <div class="row">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Team</th>
                                <th>CURRENT LOCATION AS PER GPS</th>
                                <th>DAY TRAVEL MAP AS PER GOOGLE </th>
                                <th>NOS OF VISITS TODAY COUNT</th>
                                <th>NOS OF VISITS UTD COUNT</th>                                
                            </tr>
                        </thead>
                        <tbody>
                             <?php
                             if(!empty($employee)){
                                foreach($employee as $key => $value) {
                                     ?>
                                    <tr>                                    
                                        <td><a href="<?=base_url().'report/report_summary/'.$value['pk_i_admin_id']?>"><?=$value['s_display_name'].' '.$value['last_name']?></a></td>
                                        <td>sdfsd</td>
                                        <td>

                                            <?php
                                                $this->db->from('tbl_visit');
                                                $curr_date = date('Y-m-d');
                                                $user_vist_row = $this->db->where(array('user_id'=>$value['pk_i_admin_id'],'date(created_at)'=>$curr_date))->get()->row_array();                                        
                                                
                                            ?>
                                        </td>
                                        <td>

                                        <?php
                                        $this->db->from('tbl_visit');
                                        $curr_date = date('Y-m-d');
                                        echo $this->db->where(array('user_id'=>$value['pk_i_admin_id'],'date(created_at)'=>$curr_date))->count_all_results();                                        
                                        ?>

                                        </td>
                                        <td>
                                        <?php
                                        $this->db->from('tbl_visit');
                                        $curr_date = date('Y-m-d');
                                        echo $this->db->where(array('user_id'=>$value['pk_i_admin_id']))->count_all_results();                                        
                                        ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                             }
                             ?>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>