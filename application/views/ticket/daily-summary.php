<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ticket Data</title>
    <link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="<?= base_url() ?>" sizes="32x32" />
    <!-- Font Awesome 4.7.0 -->
    <link href="<?= base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <!-- semantic css -->
    <link href="<?= base_url() ?>assets/css/semantic.min.css" rel="stylesheet" type="text/css" />
    <!-- sliderAccess css -->
    <link href="<?= base_url() ?>assets/css/jquery-ui-timepicker-addon.min.css" rel="stylesheet"
        type="text/css" />
    <!-- slider  -->
    <link href="<?= base_url() ?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- DataTables CSS -->
    <link href="<?= base_url() ?>assets/datatables/css/dataTables.min.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- pe-icon-7-stroke -->
    <link href="<?= base_url() ?>assets/css/pe-icon-7-stroke.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- themify icon css -->
    <link href="<?= base_url() ?>assets/css/themify-icons.css?v=1.0" rel="stylesheet" type="text/css" />
    <!-- Pace css -->
    <link href="<?= base_url() ?>assets/css/flash.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?= base_url() ?>assets/css/custom.css?v=1.0" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
        <!-- jQuery  -->
    <script src="<?= base_url() ?>assets/js/jquery.min.js?v=1.0" type="text/javascript"></script>

</head>
<body>

<style>
th{
    font-size:10px;
    padding:2px;
}
.small-tr td{
    font-size:10px;
    padding:2px;
}
</style>
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
            <h1>Ticket Summary (<?=$_GET['date']?>)</h1>
             <div class="panel-heading no-print">   
                <div class="btn-group"> 
                     <a class="btn btn-primary" href="javascript:void(0)" onclick="window.history.back();"> <i class="fa fa-arrow-left"></i>  Back </a>  
                </div>
            </div>
            <div class="panel-body panel-form">
			
                <div class="row">
                            
                    <table id="summ_table" class="datatable1 table table-bordered">
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
                                        echo "<tr class='small-tr'>";
                                        echo "<td style='font-size:8px;'>".$key."</td>";
                                        if(!empty($value)){

                                            foreach($value as $k =>$v){
                                                echo "<td style='font-size:8px;'>".$v['c']."</td>";
                                                $row_total += $v['c'];
                                            }
                                        }
                                        echo "<td style='background:yellow;font-size:8px;'>".$row_total."</td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                            ?>                            
                        </tbody>                        
                        <tr class="tfoot small-tr" style="background: yellow;">
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
                    </table>
                </div>


                <div class="row">                            
                    <table id="summ_table2" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align:center;">Group</th>                            
                            <?php
                            if($process_list){
                                foreach($process_list as $key=>$value){
                                    echo "<th style='text-align:center;' colspan='2'>".$value->product_name."</th>";
                                }
                            }
                            ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class='head-tr' style='background:yellow;'>
                                <td>Type Of Call</td>
                                <?php
                                if($process_list){
                                    foreach($process_list as $key=>$value){
                                        echo "<td>Count</td>";
                                        echo "<td>%Age</td>";
                                    }
                                }
                                ?>
                            </tr>
                            <?php
                            
                            $this->db->select('count(process_id) as c,process_id');
                            $this->db->where('date(coml_date)',$_GET["date"]);       
                            $this->db->group_by('process_id');
                            $process_count = $this->db->get('tbl_ticket')->result_array();

                            if(!empty($description)){
                                foreach($description as $key=>$value){
                                    ?>
                                    <tr>
                                        <td><?=$value['description']?></td>
                                        <?php
                                            if($process_list){
                                                foreach($process_list as $k=>$v){
                                                    $this->db->select('count(ticket_substage) as c');
                                                    $this->db->from('tbl_ticket');
                                                    $this->db->where('company',$comp_id);
                                                    $this->db->where('date(coml_date)',$_GET["date"]);       
                                                    $this->db->where('process_id',$v->sb_id);
                                                    $this->db->where('ticket_substage',$value['id']);
                                                    $this->db->group_by('ticket_substage');
                                                    $r    =   $this->db->get()->row_array();
                                                    $t = 0;
                                                    $c = $r['c'];
                                                    if($r['c']){
                                                        foreach($process_count as $a=>$b){
                                                            if($v->sb_id == $b['process_id']){
                                                                $t = $b['c'];
                                                            }
                                                        }
                                                        echo "<td>".$c."</td>";
                                                    }else{
                                                        $c = 0;
                                                        echo "<td>0</td>";
                                                    }
                                                    ?>
                                                    <td>
                                                    <?php
                                                    if($t){
                                                        $p = ($c/$t)*100;
                                                        echo round($p,2);
                                                    }else{
                                                        echo 0;
                                                    }
                                                    ?>
                                                    </td>
                                                    <?php
                                                }
                                            }
                                        ?>       
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                        <tr class="tfoot small-tr" style='background:yellow;'>
                            <td>
                                Grand Total
                            </td> 
                            <?php
                            if($process_list){
                                foreach($process_list as $key=>$value){
                                    echo "<td colspan='2'></td>";
                                    // echo "<td></td>";
                                }
                            }
                            ?>
                        </tr>                       
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
        $('#summ_table .tfoot td').eq(index).html('<b>' + total+'</b>');
    }    



    $(document).ready(function() {
        $('.head-tr td').each(function(i) {
            if(i){
                calculateColumn1(i);
            }
        });
    });

    function calculateColumn1(index) {
        var total = 0;
        $('#summ_table2 tr').each(function() {
            var value = parseInt($('td', this).eq(index).text());
            if (!isNaN(value)) {
                total += value;
            }
        });
        $('#summ_table2 .tfoot td').eq(index).html('<b>' + total+'</b>');
    }    
</script>
</body>
</html>