<?php
$comp_id = 65;
$this->db->where('comp_id',$comp_id);
$failurePoints = $this->db->get('tbl_ticket_subject')->result_array();
$res = array();

if(!empty($failurePoints)){
    foreach($failurePoints as $key =>$value){
        $this->db->select('count(tbl_ticket.ticket_substage) as c,lead_description.description');
        $this->db->from('lead_description');
        $this->db->where('tbl_ticket.category',$value['id']);
        $this->db->join('tbl_ticket','tbl_ticket.ticket_substage=lead_description.id','left');
        $this->db->group_by('tbl_ticket.ticket_substage');   
        $result    =   $this->db->get();

        $k = $value['subject_title'];
        $res[$k] = $result;
    }
}
echo "<pre>";
print_r($res);
echo "<pre>";
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
        if(!empty($failurePoints)){
            foreach($failurePoints as $key=>$value){
                echo "<tr>";
                echo "<th>".$value['subject_title']."</th>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>