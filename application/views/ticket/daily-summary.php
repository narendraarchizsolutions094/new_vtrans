<?php
$comp_id = 65;
$this->db->where('comp_id',$comp_id);
//$this->db->limit(10);
$failurePoints = $this->db->get('tbl_ticket_subject')->result_array();
$res = array();

if(!empty($failurePoints)){
    foreach($failurePoints as $key =>$value){
        $this->db->select('count(tbl_ticket.ticket_substage) as c,lead_description.description');
        $this->db->from('lead_description');        
        $this->db->where('lead_description.comp_id',$comp_id);
        $this->db->join('(select * from tbl_ticket where date(coml_date)='.$_GET['date'].' AND category='.$value['id'].') as tbl_ticket','tbl_ticket.ticket_substage=lead_description.id','left');
        $this->db->group_by('lead_description.id');   
        $result    =   $this->db->get()->result_array();
        $k = $value['subject_title'];
        $res[$k] = $result;
    }
}
// echo "<pre>";
// print_r($res);
// echo "<pre>";
?>
<table class="datatable table table-striped table-bordered">
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
        </tr>
    </thead>
    <tbody>
    <?php
        if(!empty($res)){
            foreach($res as $key=>$value){
                echo "<tr>";
                echo "<td>".$key."</td>";
                if(!empty($value)){
                    foreach($value as $k =>$v){
                        echo "<td>".$v['c']."</td>";
                    }
                }
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>