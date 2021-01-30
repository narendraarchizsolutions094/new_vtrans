<div class="row" style="background-color: #fff;padding:7px;border-bottom: 1px solid #C8CED3;">
	<div class="col-md-4 col-sm-4 col-xs-4"> 
          <a class="pull-left fa fa-arrow-left btn btn-circle btn-default btn-sm" onclick="history.back(-1)" title="Back"></a>   
          <?php
          // if(user_access('1010'))
          // {
          ?>     
         <!--  <a class="dropdown-toggle btn btn-danger btn-circle btn-sm fa fa-plus" data-toggle="modal" data-target="#Save_Contact" title="Add Contact"></a>  -->
          <?php
          // }
          ?>        
        </div>
</div>

<div class="row p-5" style="margin-top: 20px;">
	<div class="col-lg-12">
    <div class="row">
      <div class="col-sm-6" style="padding: 5px 0px;">
          <div style="display: inline-block;">Show</div>
          <div style="width: 80px; display: inline-block;">
              <select class="form-control" style="height: 30px;" onchange="location.href='<?=base_url('client/company_list/')?>'+this.value+'/<?=$offset?>'">
              <option value="10" <?=$limit=='10'?'selected':''?>>10</option>
              <option value="30" <?=$limit=='30'?'selected':''?>>30</option>
              <option value="50" <?=$limit=='50'?'selected':''?>>50</option>
              <option value="100" <?=$limit=='100'?'selected':''?>>100</option>
              <option value="500" <?=$limit=='500'?'selected':''?>>500</option>
              <option value="1000"<?=$limit=='1000'?'selected':''?>>1000</option>
            </select>
          </div>
          <div style="display: inline-block;"> entries</div>
      </div>
      <div class="col-sm-6" style="padding: 5px 0px;" align="right">
         <div style="display: inline-block;">Search</div>
          <div style="display: inline-block;">
          <input type="text" id="search_box" name="search" class="form-control" placeholder="Search keyword" style="width: 230px; height: 30px; " value="<?=$search?>" onkeyup="chksubmit(event,this)">
          </div>
      </div>
    </div>
		<div class="panel panel-success">
			<div class="panel-body">
                    <table style="width: 100%" id="companyTable" class=" table table-bordered table-response">
                         <thead>
                             <tr>
                                     <th>&nbsp; # &nbsp;</th>
                                     <th onclick="dosort()"><?=display('company_name')?> <i class="fa fa-caret-<?=$sort?> pull-right" ></i></th>
                                     <th>Contacts</th>
                                     <th>Deals</th>
                                     <th>Visits</th>
                                     <th>Tickets</th>
                                     <th>Accounts</th>
                                     <!-- <th>Links</th> -->   
                             </tr>
                         </thead>
                         <tbody>
                              <?php
                              if(!empty($company_list))
                              {    $j=1;
                                   foreach ($company_list as $row)
                                   {    
                                    $enquires = empty($row->enq_ids)?array():explode(',', $row->enq_ids);

                                    $total_info =  $this->Client_Model->getCompanyData($enquires,'deals')->num_rows();

                                    $total_contact = $this->Client_Model->getCompanyData($enquires,'contacts')->num_rows();

                                    $total_visits  = $this->Client_Model->getCompanyData($enquires,'visits')->num_rows();

                                    $total_tickets  = $this->Client_Model->getCompanyData($enquires,'tickets')->num_rows();
                                   
                                        echo'<tr>

                                             <td>'.$j++.'</td>
                                             <td><a href="'.base_url('client/company_details/').base64_encode($row->company).'">'.$row->company.'</a></td>
                                             <td>'.$total_contact.'</td>
                                             <td>'.$total_info.'</td>
                                             <td>'.$total_visits.'</td>
                                             <td>'.$total_tickets.'</td>
                                             <td>'.count($enquires).'</td>
                                             <!--<td>';
                                             // if(!empty($row->enq_ids))
                                             // {
                                                
                                             //      $ids = explode(',', $row->enq_ids);
                                             //      $names = explode(',', $row->enq_names);
                                             //      $status = explode(',', $row->enq_status);
                                             //      echo'<ul class="list-group">';
                                             //      for($i=0 ; $i< count($ids); $i++)
                                             //      {
                                             //           if($status[$i]=='1')     
                                             //                $url = base_url('enquiry/view/'.$ids[$i]);
                                             //           else if($status[$i]=='2')
                                             //                $url = base_url('lead/lead_details/'.$ids[$i]);
                                             //           else
                                             //                $url = base_url('client/view/'.$ids[$i]);
                                             //          echo'<li style="cursor:pointer" class="list-group-item" onclick="open_link(this)" data-url="'.$url.'">
                                             //                <label class="label label-primary">
                                             //                <i class="fa fa-user"></i></label> &nbsp; '.$names[$i].'
                                             //                </li>';
                                             //      }
                                             //      echo'</ul>';
                                             // }
                                             // else{
                                             //      echo'NA';
                                             // }
                                             echo'</td>-->
                                        </tr>';
                                   }
                              }
                              else
                                echo'<tr>
                              <td colspan="7" align="center">No Record Found</td>
                              </tr>';
                              ?>
                         </tbody>
                    </table>
<nav aria-label="Page navigation example" align="right">
  <ul class="pagination justify-content-end">
    <?php
   $na= $nhref= $phref = $pa= '';

    $phref = 'href="'.base_url('client/company_list/'.$limit.'/'.($offset-$limit)).'"';
    if(!($offset>$limit))
    { 
      $pa = 'disabled';
      $phref = '';
    }
    echo'<li class="page-item '.$pa.'">
      <a class="page-link" '.$phref.'  tabindex="-1">Previous</a>
    </li>';
  
    $num = 0;
    $total = $company_count;
    $i=1;
      while($company_count>0)
      {

        echo'<li class="page-item '.(($i*$limit)==($limit+$offset)?'active':($total<$limit?'active':'')).'"><a class="page-link" href="'.base_url('client/company_list/'.$limit.'/'.$num).'">'.$i++.'</a></li>';
        $num = $num + $limit;
        $company_count = $company_count - $limit;
      }

      $nhref = 'href="'.base_url('client/company_list/'.$limit.'/'.($limit+$offset)).'"';
    if(!($total > ($limit+$offset)))
    {
       $na = 'disabled';
      $nhref = '';
    }
      echo'<li class="page-item '.$na.'">
                <a class="page-link" '.$nhref.' >Next</a>
              </li>';
   
    ?>
  </ul>
</nav>
               </div>

          </div>
     </div>
</div>
<script type="text/javascript">
  // function open_link(t)
  // {
  //   window.open($(t).data('url'),'_blank');
  // }


//$(document).ready(function(){

  //$('#companyTable').DataTable({ 

          // "processing": true,
          // "scrollX": true,
          // "serverSide": true,          
          // "lengthMenu": [ [10,30, 50,100,500,1000, -1], [10,30, 50,100,500,1000, "All"] ],
          // "ajax": {
          //     "url": "<?=base_url().'client/company_load_data'?>",
          //     "type": "POST",
          //     "data":function(d){

                     // d.date_from = $("input[name=d_from_date]").val();
                     // d.date_to = $("input[name=d_to_date]").val();
                     // d.enq_for = $("select[name=d_enquiry_id]").val();
                     // d.booking_type = $("select[name=d_booking_type]").val();
                     // d.booking_branch  =  $("select[name=d_booking_branch]").val();
                     // d.delivery_branch  =  $("select[name=d_delivery_branch]").val();
                     // d.paymode  =  $("select[name=d_paymode]").val();
                     // d.p_amnt_from  =  $("input[name=d_p_amnt_from]").val();
                     // d.p_amnt_to =  $("input[name=d_p_amnt_to]").val();
                     
                     // // d.from_date = obj[0]['value'];
                     // // d.from_time = '';//obj[1]["value"];
                     // // d.enquiry_id =obj[2]["value"];
                     // // d.rating = obj[3]["value"];
                     // // d.to_date = obj[1]['value'];
                     // // d.to_time = '';//obj[5]['value'];
                     // d.view_all=true;
                     // d.specific_list = specific_list;
                     // TempData = d;

                     //  if(c && c!='')
                     //  d.allow_cols = c;

                     //console.log(JSON.stringify(d));
                  //  return d;
             // }
         // },
          // "drawCallback":function(settings ){
          //   update_top_filter();
          // },
          // columnDefs: [
          //              { orderable: false, targets: -1 }
          //           ]
 // });

//});
function chksubmit(e,t)
{
  if(e.keyCode==13)
  {
    var z = t.value==''?0:t.value;
    location.href="<?=base_url('client/company_list/'.$limit.'/'.$offset.'/')?>"+z;
  }
}
function dosort()
{
  var x = <?=$sort=='up'?'"down"':'"up"'?>;
  var search = $("#search_box").val();
  var z = search==''?0:search;
 // alert(z);
  location.href="<?=base_url('client/company_list/'.$limit.'/'.$offset.'/')?>"+z+'/'+x;
}
</script>