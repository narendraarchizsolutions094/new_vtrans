<?php
// echo "<pre>";
// print_r($result);
// echo "</pre>";
?>
<h1 class='text-center'>GC Details</h1>

<table class='table table-bordered'>
    <thead>

    </thead>
    <tbody>
        <?php
        $row = 0;
        foreach($result['lr'] as $key => $value){
            if(!is_array($value)){
                if($row == 0){
                    echo "<tr>";
                }
                echo '<td><b>'.$key.'</b></td>';
                if(is_array($value)){
                    echo '<td>';
                    print_r($value);
                    echo '</td>';
                }else{
                    echo '<td>'.$value.'</td>';
                }
                if($row >= 2){
                    echo "</tr>";
                    $row = 0;
                }else{
                    $row++;
                }
            }
        }
        ?>
    </tbody>
</table>

<br>
<br>
<h1 class='text-center'>Events</h1>
<table class='table table-bordered'>
    <thead>
        <tr>
            <td>event_type</td>
            <td>description</td>
            <td>event_location_display_name</td>
            <td>occurred_at</td>
            <td>recorded_at</td>
            <td>lat</td>
            <td>lng</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $events = $result['lr']['events'];
        if(!empty($events)){
            foreach($events as $key=> $value){
                echo "<tr>";
                echo "<td>".$value['event_type']."</td>";
                echo "<td>".$value['description']."</td>";
                echo "<td>".$value['event_location_display_name']."</td>";
                echo "<td>".$value['occurred_at']."</td>";
                echo "<td>".$value['recorded_at']."</td>";
                echo "<td>".$value['lat']."</td>";
                echo "<td>".$value['lng']."</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

<h1 class='text-center'>Consignment Invoices</h1>


<table class='table table-bordered'>
    <thead>
        <tr>
            <td>invoice_number</td>
            <td>pickable_units</td>
            <td>display_invoice_date</td>
            <td>delivery_order_number</td>
            <td>eway_bill_number</td>
            <td>display_eway_bill_expiry_date</td>
            <td>eway_bill_status</td>
            <td>additional_refs</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $consignment_invoices = $result['lr']['consignment_invoices'];
        if(!empty($consignment_invoices)){
            foreach($consignment_invoices as $key=> $value){
                echo "<tr>";
                echo "<td>".$value['invoice_number']."</td>";
                echo "<td>".$value['pickable_units']."</td>";
                echo "<td>".$value['display_invoice_date']."</td>";
                echo "<td>".$value['delivery_order_number']."</td>";
                echo "<td>".$value['eway_bill_number']."</td>";
                echo "<td>".$value['display_eway_bill_expiry_date']."</td>";
                echo "<td>".$value['eway_bill_status']."</td>";
                echo "<td>".$value['additional_refs']."</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

<h1 class='text-center'>Products</h1>

<table class='table table-bordered'>
    <thead>
        <tr>
            <td>product_name</td>
            <td>product_description</td>
            <td>inventory_reference_number</td>
            <td>units</td>
            <td>unit_type</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $products = $result['lr']['products'];
        if(!empty($products)){
            foreach($products as $key=> $value){
                echo "<tr>";
                echo "<td>".$value['product_name']."</td>";
                echo "<td>".$value['product_description']."</td>";
                echo "<td>".$value['inventory_reference_number']."</td>";
                echo "<td>".$value['units']."</td>";
                echo "<td>".$value['unit_type']."</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

<h1 class='text-center'>Attachments</h1>
<table class='table table-bordered'>
    <thead>
        <tr>
            <td>document_file_name</td>
            <td>document_type</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $attachments = $result['lr']['attachments'];
        if(!empty($attachments)){
            foreach($attachments as $key=> $value){
                echo "<tr>";
                echo "<td>".$value['document_file_name']."</td>";
                echo "<td>".$value['document_type']."</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>