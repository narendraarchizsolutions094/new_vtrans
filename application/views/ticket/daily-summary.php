<?php
$comp_id = 65;
$this->db->where('comp_id',$comp_id);
$this->db->limit(10);
$failurePoints = $this->db->get('tbl_ticket_subject')->result_array();
$res = array();

if(!empty($failurePoints)){
    foreach($failurePoints as $key =>$value){
        $this->db->select('count(tbl_ticket.ticket_substage) as c,lead_description.description');
        $this->db->from('lead_description');        
        $this->db->where('lead_description.comp_id',$comp_id);
        $this->db->join('(select * from tbl_ticket where date(coml_date)="'.$_GET["date"].'" AND category='.$value['id'].') as tbl_ticket','tbl_ticket.ticket_substage=lead_description.id','left');
        $this->db->group_by('lead_description.id');   
        $result    =   $this->db->get()->result_array();
        $k = $value['subject_title'];
        $res[$k] = $result;
    }
}
?>
<div class="row">
    <!--  form area -->
    <div class="col-sm-12">
        <div  class="panel panel-default thumbnail">
 
            <!-- <div class="panel-heading no-print">   
                <div class="btn-group"> 
                    <a class="btn btn-primary" href="<?php // echo base_url("customer") ?>"> <i class="fa fa-list"></i>  <?php // echo display('doctor_list') ?> </a>  
                </div>
            </div> -->
            <div class="panel-body panel-form">
			
                <div class="row">
                            
                    <table id="summ_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Failure Points
                                </th>        
                                <?php
                                $this->db->where('comp_id',$comp_id);
                                $description    =   $this->db->get('lead_description')->result_array();
                                if(!empty($description)){
                                    foreach($description as $d=>$v){
                                        echo "<th>".$v['description']."</th>";
                                    }
                                }
                                ?>
                                <th style='background:yellow;'>Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($res)){
                                foreach($res as $key=>$value){
                                    $t = 0;
                                    foreach($value as $k =>$v){
                                        if($v['c'] > 0){
                                            $t = 1;
                                        }
                                    }
                                    $row_total = 0;
                                    if($t){
                                        echo "<tr>";
                                        echo "<td>".$key."</td>";
                                        if(!empty($value)){

                                            foreach($value as $k =>$v){
                                                echo "<td>".$v['c']."</td>";
                                                $row_total += $v['c'];
                                            }
                                        }
                                        echo "<td style='background:yellow'>".$row_total."</td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                            ?>                            
                        </tbody>
                        <tfoot style="background: yellow;">
                        <tr>
                            <td>
                                Grand Total
                            </td>  
                            <?php
                                if(!empty($description)){
                                    foreach($description as $key=>$value){
                                        echo "<td></td>";
                                    }
                                }
                            ?>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>


                <div class="row">                            
                    <table class="table table-bordered">

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">    
    $(document).ready(function() {
        $('#summ_table thead th').each(function(i) {
            if(i){
                calculateColumn(i);
            }
        });
    });

    function calculateColumn(index) {
        var total = 0;
        $('#summ_table tr').each(function() {
            var value = parseInt($('td', this).eq(index).text());
            if (!isNaN(value)) {
                total += value;
            }
        });
        $('#summ_table tfoot td').eq(index).html('<b>' + total+'</b>');
    }    
</script>